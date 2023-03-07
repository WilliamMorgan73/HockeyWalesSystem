<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

session_start();
$userID = $_SESSION['userID'];
$playerID = getPlayerID($conn, $userID);
$playerName = getPlayerName($conn, $userID);
$playerTeam = getPlayerTeam($conn, $userID);
$nextOpponent = getOppositionName($conn, $userID);
$goals = getPlayerGoals($conn, $userID);
$assists = getPlayerAssists($conn, $userID);
$apperances = getPlayerApperances($conn, $userID);
$leagueID = getLeagueID($userID, $conn);
$playerTeamID = getTeamID($userID);
?>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $playerName ?>'s dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte/adminlte.min.css" />
  <!-- Bootstrap Css -->
  <link rel="stylesheet" href="css/bootstrapIcons/bootstrap-icons.css" />
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-danger elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
        <img src="images/hw_feathers2.png" style="width:25%;">
        <span class="brand-text font-weight-bolder">HOCKEY WALES</span>
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
                <form action="teammates.php" method="post">
                  <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                  <i class="far bi bi-people-fill nav-icon"></i>
                  <button type="submit" style="background: transparent; border: none;">
                    <p>Teammates</p>
                  </button>
                </form>
              </a>
            </li>
            <li class="nav-item">
              <a style="cursor: pointer;" class="nav-link">
                <form action="playerFixtures.php" method="post">
                  <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                  <i class="far bi bi-calendar-date-fill nav-icon"></i>
                  <button type="submit" style="background: transparent; border: none;">
                    <p>Fixtures</p>
                  </button>
                </form>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <i class="bi bi-list nav-icon"></i>
                <p>League selection</p>
              </a>
            </li>
          </ul>
          <div class="info">
            <p class="d-block"> Change your profile picture</p>
            <!-- Change user pfp -->
            <form enctype="multipart/form-data" action="includes/uploadpfp.inc.php" method="POST">
              <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
              <input name="uploadedfile" type="file" />
              <input type="submit" value="Upload" />
            </form>
          </div>
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
              <h1><?php echo $playerName ?>'s' dashboard</h1>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="card card-solid">
          <div class="card-body -0">
            <div class="container-fluid">
              <!-- First row - League table, top scorers, and results -->
              <div class="row">
                <!-- League table -->
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12" style=" margin-bottom:1%">
                      <!-- Days till next game -->
                      <h5> Your name game is in <?php getDaysUntilGame($conn, $userID); ?> against <?php echo $nextOpponent ?> </h5>
                    </div>
                  </div>
                  <div class="card shadow" style="width: 100%">
                    <div class="card-body p-0">
                      <table class="table table-striped" style="width: 100%">
                        <thead>
                          <tr>
                            <th onclick="sortTable(0)">Team</th>
                            <th onclick="sortTable(1)">W</th>
                            <th onclick="sortTable(2)">D</th>
                            <th onclick="sortTable(3)">L</th>
                            <th onclick="sortTable(4)">GD</th>
                            <th onclick="sortTable(5)">PTS</th>
                          </tr>
                        </thead>
                        <tbody id="tableBody">
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
                <!-- Stats -->
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card card-widget widget-user shadow">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-danger">
                          <h3 class="widget-user-username"><?php echo $playerName ?></h3>
                          <h5 class="widget-user-desc"><?php echo $playerTeam ?></h5>
                        </div>
                        <div class="widget-user-image">
                          <?php userPfpCheck($userID) ?>
                        </div>
                        <div class="card-footer">
                          <div class="row">
                            <div class="col-sm-4 border-right">
                              <div class="description-block">
                                <h5 class="description-header"><?php echo $goals ?></h5>
                                <span class="description-text">GOALS</span>
                              </div>
                              <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                              <div class="description-block">
                                <h5 class="description-header"><?php echo $assists ?></h5>
                                <span class="description-text">ASSISTS</span>
                              </div>
                              <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                              <div class="description-block">
                                <h5 class="description-header"><?php echo $apperances ?></h5>
                                <span class="description-text">APPEARANCES</span>
                              </div>
                              <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                          </div>
                          <!-- /.row -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <!-- Fixtures -->
                    <div class="col-md-12 text-center">
                      <div class="card card-outline shadow">
                        <div class="card-body">
                          <h1 class="card-title">Fixtures</h1>
                          <br />
                          <div class="col-md-12">
                            <?php
                            $sql = "SELECT teamID FROM player WHERE playerID = $playerID";
                            $teamResult = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($teamResult) > 0) {
                              $teamRow = mysqli_fetch_assoc($teamResult);
                              $teamID = $teamRow['teamID'];

                              $query = "SELECT MIN(matchWeek) AS minWeek FROM fixture";
                              $result = mysqli_query($conn, $query);

                              if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $minWeek = $row['minWeek'];

                                $query = "SELECT homeTeamID, awayTeamID FROM fixture WHERE matchWeek >= '$minWeek' AND leagueID = '$leagueID' AND (homeTeamID = '$teamID' OR awayTeamID = '$teamID') LIMIT 3";
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
                                      <h2 class="lead"><b><?php echo "$homeTeamName - $awayTeamName" . "<br>"; ?></b></h2>
                                    </div>
                            <?php
                                  }
                                } else {
                                  echo "No fixtures found for the team";
                                }
                              } else {
                                echo "No match week numbers found in the fixtures table";
                              }
                            } else {
                              echo "No team found for the player";
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- End of fixtures -->
                  </div>
                  <div class="row">

                    <!-- Teammates -->
                    <div class="col-md-12 text-center">
                      <div class="card card-outline shadow">
                        <div class="card-body">
                          <h5 class="card-title">Teammates</h5>
                          <br />
                          <div class="row">
                            <div class="col-md-12">
                              <div class="row">
                                <!-- teammate loop -->
                                <?php
                                // get all the players who have the same teamID as the current user
                                $sql = "SELECT playerID, userID, firstName, lastName FROM player WHERE teamID = ? AND playerID != ?";
                                $stmt = mysqli_stmt_init($conn);
                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                  header("Location: ../signup.php?error=stmtfailed");
                                  exit();
                                }

                                mysqli_stmt_bind_param($stmt, "ss", $playerTeamID, $playerID);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                $counter = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                  if ($counter == 6) {
                                    break;
                                  }
                                  $userID = $row['userID'];
                                  $firstName = $row['firstName'];
                                  $lastName = $row['lastName'];
                                ?>
                                  <div class="col-md-4">
                                    <div class="card card-danger card-outline">
                                      <div class="card-body box-profile">
                                        <div class="text-center">
                                          <img class="profile-user-img img-fluid img-circle" src="images\pfp\defaultpfp.jpg" alt="User profile picture"> <!-- Player profile picture -->
                                        </div>
                                        <h5 class="profile-username text-center"><?php echo $firstName . ' ' . $lastName; ?></h5> <!-- Player name -->
                                        <p class="text-muted text-center">Team name</p> <!-- Team name -->
                                      </div>
                                    </div>
                                  </div>
                                <?php
                                  $counter++;
                                }
                                ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End of fixtures -->
                  </div>

                </div>
                <!-- End of stats -->
              </div>
              <!-- End of top row -->

              <!-- End of second row -->
              <!-- Third row- Teammates -->

            </div>
          </div>
        </div>
        <!-- /.card -->
      </section>
      <!-- /.content -->
    </div>
  </div>

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="js/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/adminlte/adminlte.min.js"></script>

  <script>
    // Global variable to store the sorting order
    var sortOrder = [];

    // QuickSort function to sort the table
    function quickSort(arr, low, high, column) {
      if (low < high) {
        var pivot = partition(arr, low, high, column);
        quickSort(arr, low, pivot - 1, column);
        quickSort(arr, pivot + 1, high, column);
      }
    }

    // Partition function for QuickSort
    function partition(arr, low, high, column) {
      var pivotValue = arr[high][column];
      var i = low - 1;
      for (var j = low; j <= high - 1; j++) {
        if (arr[j][column] < pivotValue) {
          i++;
          swap(arr, i, j);
        }
      }
      swap(arr, i + 1, high);
      return i + 1;
    }

    // Swap function to swap two elements in the array
    function swap($arr, $a, $b) {
      $temp = $arr[$a];
      $arr[$a] = $arr[$b];
      $arr[$b] = $temp;
    }

    $(document).ready(function() {
      $("th").click(function() {
        var table = $(this).parents("table");
        var rows = table.find("tr:gt(0)").toArray().sort(comparer($(this).index()));
        this.asc = !this.asc;
        if (!this.asc) {
          rows = rows.reverse();
        }
        // Remove the sort icons from other columns
        table.find("th i").remove();
        // Set the sort icon for the clicked column
        if (this.asc) {
          $(this).append(' <i class="bi bi-caret-up-fill"></i>');
        } else {
          $(this).append(' <i class="bi bi-caret-down-fill"></i>');
        }
        // Reorder the rows based on the sorting order
        for (var i = 0; i < rows.length; i++) {
          table.append(rows[i]);
        }
      });
    });

    function comparer(index) {
      return function(a, b) {
        var valA = getCellValue(a, index),
          valB = getCellValue(b, index);
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
      }
    }

    function getCellValue(row, index) {
      return $(row).children("td").eq(index).text();
    }
  </script>

</body>

</html>