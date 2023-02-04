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
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
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
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body -0">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <!-- First row -->
                <div class="row">
                  <!-- League table -->
                  <div class="col-md-6">
                    <div class="card shadow" style="width: 100%">
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
                            $conn = require 'includes/dbhconfig.php';
                            $sql = "SELECT * FROM team WHERE leagueID = $leagueID";
                            $result = mysqli_query($conn, $sql);

                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                              // Output data of each row
                              while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                  <td>" . $row["teamName"] . "</td>
                  <td>" . $row["wins"] . "</td>
                  <td>" . $row["draws"] . "</td>
                  <td>" . $row["losses"] . "</td>
                  <td>" . $row["goalDifference"] . "</td>
                  <td>" . $row["points"] . "</td>
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
                  <!-- Right side split -->
                  <div class="col-md-6">
                    <!-- Top row -->
                    <div class="row">
                      <!-- Recent results -->
                      <div class="col-md-6">
                        <div class="card card-outline shadow">
                          <div class="card-body">
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
                                    <div class="card">
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
                      <!-- End of recent results -->
                      <!-- Top scorers -->
                      <div class="col-md-6">
                        <div class="card card-outline shadow">
                          <div class="card-body">
                            <h1 class="card-title">Top scorers</h1>
                            <br />
                            <?php
                            $query = "SELECT * FROM team WHERE leagueID = '$leagueID'";
                            $result = mysqli_query($conn, $query);
                            if (mysqli_num_rows($result) > 0) {
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

                                    $query = "SELECT numOfGoals FROM goal WHERE playerID = '$playerID' ORDER BY numOfGoals DESC LIMIT 4";
                                    $numOfGoalsresult = mysqli_query($conn, $query);
                                    if (mysqli_num_rows($numOfGoalsresult) > 0) {
                                      while ($row = mysqli_fetch_array($numOfGoalsresult)) {
                                        $numOfGoals = $row['numOfGoals'];
                            ?>
                                        <div class="card col-md-12">
                                          <h2 class="lead"><b><?php echo $firstName;
                                                              echo " ";
                                                              echo $lastName; ?></b></h2>
                                          <p class="text-muted text-sm"><b>Number of goals:</b> <?php echo $numOfGoals; ?></p>
                                          <p class="text-muted text-sm"><b>Club:</b> <?php echo $teamName; ?></p>
                                        </div>
                            <?php
                                      }
                                    }
                                  }
                                }
                              }
                            }
                            ?>
                            <?php
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End of top scorers -->
                  </div>
                  <!-- End of top row -->
                  <!-- Bottom row -->
                  <div class="row">
                    <!-- Upcoming matches -->
                    <div class="col-md-12">
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
                                  <div class="card">
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
                    <!-- End of upcoming matches -->
                  </div>
                  <!-- End of bottom row -->
                </div>
                <!-- End of right side split -->
              </div>
              <!-- End of first row -->
            </div>
          </div>
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
</body>

</html>