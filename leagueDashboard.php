<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

$leagueID = $_POST['leagueID'];
$leagueName = getLeagueName($conn, $leagueID);

?>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $leagueName ?> dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte/adminlte.min.css" />
  <!-- Bootstrap Css -->
  <link rel="stylesheet" href="css/bootstrapIcons/bootstrap-icons.css" />


</head>

<body class="hold-transition sidebar-mini"></body>
<div class="wrapper">
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <span class="brand-text font-weight-bolder"><?php echo $leagueName ?></span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">
          <li class="nav-item">
            <a style="cursor:pointer" class="nav-link active">
              <i class="nav-icon bi bi-house-fill"></i>
              <p>Home</p>
            </a>
          </li>
          <li class="nav-item">
            <a style="cursor: pointer;" class="nav-link">
              <form action="leagueTeams.php" method="post">
                <input type="hidden" name="leagueID" value="<?php echo $leagueID; ?>">
                <i class="far bi bi-people-fill nav-icon"></i>
                <button type="submit" style="background: transparent; border: none;">
                  <p>Teams</p>
                </button>
              </form>
            </a>
          </li>
          <li class="nav-item">
            <a style="cursor: pointer;" class="nav-link">
              <form action="fixturesResults.php" method="post">
                <input type="hidden" name="leagueID" value="<?php echo $leagueID; ?>">
                <i class="far bi bi-calendar-date-fill nav-icon"></i>
                <button type="submit" style="background: transparent; border: none;">
                  <p>Fixtures/Results</p>
                </button>
              </form>
            </a>
          </li>
          <li class="nav-item">
            <a style="cursor: pointer;" class="nav-link">
              <form action="predictedLeagueDashboard.php" method="post">
                <input type="hidden" name="leagueID" value="<?php echo $leagueID; ?>">
                <i class="far bi bi-bar-chart-fill nav-icon"></i>
                <button type="submit" style="background: transparent; border: none;">
                  <p>Predictions</p>
                </button>
              </form>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb">
          <div class="col-sm">
            <h1><?php echo $leagueName ?> dashboard</h1>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body-0">
          <div class="container-fluid">
            <!-- First row - League table, top scorers, and results -->
            <div class="row" style="padding-top:1%;">
              <!-- League table -->
              <div class="col-md-6">
                <div class="card shadow" style="width: 100%;">
                  <div class="card-body p-0">
                    <table class=" table table-striped" style="width: 100%">
                      <thead>
                        <tr>
                          <th>Team</th>
                          <th>W</th>
                          <th>D</th>
                          <th>L</th>
                          <th>GD</th>
                          <th>PTS</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        // Assuming the database connection is established and stored in the variable $conn
                        $sql = "SELECT teamID, teamName FROM team WHERE leagueID = $leagueID";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                          $teams = [];
                          while ($row = mysqli_fetch_assoc($result)) {
                            $teams[] = $row;
                          }

                          $points = getTeamPoints($teams, $conn);
                          // Display the teams in descending order of points
                          foreach ($points as $team) {
                            echo "<tr>
              <td>" . $team["teamName"] . "</td>
              <td>" . getTeamWins($team["teamID"], $conn) . "</td>
              <td>" . getTeamDraws($team["teamID"], $conn) . "</td>
              <td>" . getTeamLosses($team["teamID"], $conn) . "</td>
              <td>" . getTeamGoalDifference($team["teamID"], $conn) . "</td>
              <td>" . $team["points"] . "</td>
            </tr>";
                          }
                        } else {
                          echo "0 results";
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- End of league table -->
              <!-- Results -->
              <div class="col-md-6">
                <div class="card card-outline shadow">
                  <div class="card-body text-center">
                    <h1 class="card-title">Result</h1>
                    <br />
                    <div class="col-md-12">
                      <?php
                      $query = "SELECT MAX(matchWeek) AS maxWeek FROM result";
                      $result = mysqli_query($conn, $query);

                      if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $maxWeek = $row['maxWeek'];

                        $query = "SELECT homeTeamID, homeTeamScore, awayTeamID, awayTeamScore FROM result WHERE matchWeek = '$maxWeek' AND leagueID = '$leagueID'";
                        $resultdetailresult = mysqli_query($conn, $query);

                        if (mysqli_num_rows($resultdetailresult) > 0) {
                          while ($row = mysqli_fetch_array($resultdetailresult)) {
                            $homeTeamID = $row['homeTeamID'];
                            $awayTeamID = $row['awayTeamID'];
                            $homeTeamScore = $row['homeTeamScore'];
                            $awayTeamScore = $row['awayTeamScore'];

                            $query = "SELECT teamName FROM team WHERE teamID = '$homeTeamID'";
                            $result2 = mysqli_query($conn, $query);
                            $row2 = mysqli_fetch_array($result2);
                            $homeTeamName = $row2['teamName'];

                            $query = "SELECT teamName FROM team WHERE teamID = '$awayTeamID'";
                            $result3 = mysqli_query($conn, $query);
                            $row3 = mysqli_fetch_array($result3);
                            $awayTeamName = $row3['teamName'];
                      ?>
                            <div class="card-body">
                              <h2 class="lead"><b><?php echo "$homeTeamName - $homeTeamScore   -   $awayTeamScore $awayTeamName" . "<br>"; ?>
                            </div>
                      <?php
                          }
                        } else {
                          echo "No results with the highest game week number found";
                        }
                      } else {
                        echo "No game week numbers found in the result table";
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End of results -->
            </div>
            <!-- End of first row -->
            <!-- Second row- fixtures -->
            <div class="row">
              <!-- Top scorers -->
              <div class="col-md-6 text-center">
                <div class="card card-outline shadow">
                  <div class="card-body">
                    <h1 class="card-title">Top scorers</h1>
                    <br />
                    <?php
                    $query = "SELECT * FROM team WHERE leagueID = '$leagueID'";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                      $players = [];
                      while ($row = mysqli_fetch_array($result)) {
                        $teamID = $row['teamID'];
                        $query = "SELECT * FROM player WHERE teamID = '$teamID'";
                        $teamIDresult = mysqli_query($conn, $query);
                        if (mysqli_num_rows($teamIDresult) > 0) {
                          while ($row = mysqli_fetch_array($teamIDresult)) {
                            $playerID = $row['playerID'];
                            $firstName = $row['firstName'];
                            $lastName = $row['lastName'];
                            $teamID = $row['teamID'];

                            $query = "SELECT teamName FROM team WHERE teamID = '$teamID'";
                            $teamNameresult = mysqli_query($conn, $query);
                            $row = mysqli_fetch_array($teamNameresult);
                            $teamName = $row['teamName'];

                            $query = "SELECT numOfGoals FROM goal WHERE playerID = '$playerID' ORDER BY numOfGoals DESC LIMIT 6";
                            $numOfGoalsresult = mysqli_query($conn, $query);
                            if (mysqli_num_rows($numOfGoalsresult) > 0) {
                              while ($row = mysqli_fetch_array($numOfGoalsresult)) {
                                $numOfGoals = $row['numOfGoals'];
                                $players[] = [
                                  'firstName' => $firstName,
                                  'lastName' => $lastName,
                                  'teamName' => $teamName,
                                  'numOfGoals' => $numOfGoals,
                                ];
                              }
                            }
                          }
                        }
                      }
                      usort($players, function ($a, $b) {
                        return $b['numOfGoals'] - $a['numOfGoals'];
                      });
                      foreach ($players as $player) {
                    ?>
                        <div class="card-body col-md-12" style="padding-bottom:0px;">
                          <h3 class="lead"><b><?php echo $player['firstName'];
                                              echo " ";
                                              echo $player['lastName']; ?></b></h3>
                          <p class="text-muted text-sm"><b>Number of goals:</b> <?php echo $player['numOfGoals']; ?></p>
                          <p class="text-muted text-sm"><b>Club:</b> <?php echo $player['teamName']; ?></p>
                        </div>
                    <?php
                      }
                    }
                    ?>
                  </div>
                </div>
              </div>

              <!-- End of top scorers -->
              <!-- Fixtures -->
              <div class="col-md-6 text-center">
                <div class="card card-outline shadow">
                  <div class="card-body">
                    <h1 class="card-title">Fixtures</h1>
                    <br />
                    <div class="col-md-12">
                      <?php
                      $query = "SELECT MIN(matchWeek) AS minWeek FROM fixture";
                      $result = mysqli_query($conn, $query);

                      if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $minWeek = $row['minWeek'];

                        $query = "SELECT homeTeamID, awayTeamID FROM fixture WHERE matchWeek = '$minWeek' AND leagueID = '$leagueID'";
                        $resultFixtures = mysqli_query($conn, $query);

                        if (mysqli_num_rows($resultFixtures) > 0) {
                          while ($row = mysqli_fetch_array($resultFixtures)) {
                            $homeTeamID = $row['homeTeamID'];
                            $awayTeamID = $row['awayTeamID'];

                            $query = "SELECT teamName FROM team WHERE teamID = '$homeTeamID'";
                            $result2 = mysqli_query($conn, $query);
                            $row2 = mysqli_fetch_array($result2);
                            $homeTeamName = $row2['teamName'];

                            $query = "SELECT teamName FROM team WHERE teamID = '$awayTeamID'";
                            $result3 = mysqli_query($conn, $query);
                            $row3 = mysqli_fetch_array($result3);
                            $awayTeamName = $row3['teamName'];
                      ?>
                            <div class="card-body">
                              <h2 class="lead"><b><?php echo "$homeTeamName - $awayTeamName" . "<br>"; ?>
                            </div>
                      <?php
                          }
                        } else {
                          echo "No fixtures with the lowest match week number found";
                        }
                      } else {
                        echo "No match week numbers found in the fixtures table";
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End of fixtures -->
            </div>
            <!-- End of second row -->
          </div>
        </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- REQUIRED SCRIPTS -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="js/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="js/bootstrap/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte/adminlte.min.js"></script>
<!-- FLOT CHARTS -->
<script src="js/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="js/flot/plugins/jquery.flot.resize.js"></script>


<script>
  <?php

  $query = "SELECT teamID, teamName FROM team WHERE leagueID = '$leagueID'";
  $result = mysqli_query($conn, $query);

  $allPointsPerWeek = [];
  $teams = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $teamID = $row['teamID'];
    $teamName = $row['teamName'];
    $pointsPerWeek = calculatePointsPerWeek($teamID, $conn);
    $allPointsPerWeek[$teamID] = $pointsPerWeek;
    $teams[$teamID] = array(
      'name' => $teamName
    );
  }
  ?>

  $(function() {
    /*
    LINE CHART
     */
    var allPointsPerWeek = <?php echo json_encode($allPointsPerWeek); ?>;
    var teams = <?php echo json_encode($teams); ?>;

    var line_data = [];
    for (var teamID in allPointsPerWeek) {
      line_data.push({
        data: allPointsPerWeek[teamID],
        xaxis: {
          mode: "categories",
          categories: allPointsPerWeek[teamID].map(function(item) {
            return item[0];
          })
        },
        yaxis: {
          show: true
        },
        color: '#3c8dbc'
      });
    }

    $.plot('#line-chart', line_data, {
      grid: {
        hoverable: true,
        borderColor: '#f3f3f3',
        borderWidth: 1,
        tickColor: '#f3f3f3'
      },
      series: {
        shadowSize: 0,
        lines: {
          show: true
        },
        points: {
          show: true
        }
      },
      lines: {
        fill: false,
        color: ['#3c8dbc']
      },
      yaxis: {
        show: true
      },
      xaxis: {
        show: true
      }
    })
    //Initialize tooltip on hover
    $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
      position: 'absolute',
      display: 'none',
      opacity: 0.8
    }).appendTo('body')
    $('#line-chart').bind('plothover', function(event, pos, item) {

      if (item) {
        var x = item.datapoint[0].toFixed(2),
          y = item.datapoint[1].toFixed(2),
          teamName = teams[Object.keys(teams)[item.seriesIndex]].name;

        $('#line-chart-tooltip').html(teamName + ' had ' + y + ' points at week ' + x)
          .css({
            top: item.pageY + 5,
            left: item.pageX + 5
          })
          .fadeIn(200)
      } else {
        $('#line-chart-tooltip').hide()
      }
    })
  })
</script>

</body>


<!-- Add error messages when there are no games played or no scorers etc -->

</html>