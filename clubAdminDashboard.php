<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';
include('includes/datechecker.inc.php');

session_start();
$userID = $_SESSION['userID'];
$clubAdminName = getclubAdminName($userID);
$clubName = getClubName($userID);
$clubID = getClubID($clubName);
$leagueID = getLeagueID($userID);
?>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $clubAdminName ?>'s dashboard</title>

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
        <span class="brand-text font-weight-bolder"><?php echo $clubName ?></span> <!-- Club name -->
      </a>
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">
            <li class="nav-item menu-open">
              <a href="#" class="nav-link active">
                <i class="nav-icon bi bi-house-fill"></i>
                <p>Home</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="teammanagement.php" class="nav-link">
                <i class="far bi bi-people-fill nav-icon"></i>
                <p>Team management</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="playerApproval.php" class="nav-link">
                <i class="far bi bi-check2 nav-icon"></i>
                <p>Player approval</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="fixtureAvailability.php" class="nav-link">
                <i class="far bi bi-calendar-date-fill nav-icon"></i>
                <p>Fixture availability</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="resultApproval.php" class="nav-link">
                <i class="far bi bi-bar-chart-fill nav-icon"></i>
                <p>Result approval</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="approvePasswordChange.php" class="nav-link">
                <i class="far bi bi-person-fill-lock"></i>
                <p>Password requests</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <i class="bi bi-list nav-icon"></i>
                <p>League selection</p>
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
              <h1 class="m-0">Hi <?php echo $clubAdminName ?>,</h1> <!-- Club admin name -->
              <h5>You can manage your club from here</h5>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <div class="content">
        <div class="card card-solid">
          <div class="card-body-0">
            <div class="container-fluid">
              <div class="row" style="padding-top:1%;">
                <!-- League table -->
                <div class="col-md-6">
                  <div class="card shadow" style="width: 100%">
                    <div class="card-body p-0">
                      <table class="table table-striped" style="width: 100%"> <!-- League table -->
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
                          // get team details from the team database where the leagueID matches the leagueID of the club's best team, so that the league table can be displayed
                          $sql = "SELECT teamID, teamName FROM team WHERE leagueID = $leagueID";
                          $result = mysqli_query($conn, $sql);
                          if (mysqli_num_rows($result) > 0) {
                            $teams = [];
                            while ($row = mysqli_fetch_assoc($result)) {
                              $teams[] = $row;
                            }

                            $points = getTeamPoints($teams);
                            // Display the teams in descending order of points
                            foreach ($points as $team) {
                              echo "<tr>
  <td>" . $team["teamName"] . "</td>
  <td>" . getTeamWins($team["teamID"]) . "</td>
  <td>" . getTeamDraws($team["teamID"]) . "</td>
  <td>" . getTeamLosses($team["teamID"]) . "</td>
  <td>" . getTeamGoalDifference($team["teamID"]) . "</td>
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
                <div class="col-lg-6">

                  <!-- Results approval -->
                  <div class="card card-outline shadow">
                    <div class="card-body">
                      <h5 class="card-title">Results approval</h5>
                      <br />
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row text-center">
                            <?php
                            // Get the results waiting approval from the tempresult database so the user can see what results need to be approved
                            $query = "SELECT hometeam.teamName AS hometeam, awayteam.teamName AS awayteam, homeTeamScore, awayTeamScore, tempresult.homeTeamID AS homeTeamID, tempresult.awayTeamID AS awayTeamID
                        FROM tempresult
                        INNER JOIN team hometeam ON tempresult.homeTeamID = hometeam.teamID
                        INNER JOIN team awayteam ON tempresult.awayTeamID = awayteam.teamID
                        WHERE awayteam.clubID = $clubID AND tempresult.status = 'sent'";

                            $result = mysqli_query($conn, $query);

                            // Loop through the results waiting approval and display them
                            while ($row = mysqli_fetch_assoc($result)) {
                              $hometeam = $row['hometeam'];
                              $awayteam = $row['awayteam'];
                              $scoreline = $row['homeTeamScore'] . "-" . $row['awayTeamScore'];
                            ?>
                              <div class="col-md-4">
                                <div class="card pt-0">
                                  <div class="col-12">
                                    <h6 class="header"><b><?php echo $hometeam; ?></b> vs <b><?php echo $awayteam; ?></b></h6>
                                    <p>Score: <?php echo $scoreline; ?></p>
                                  </div>
                                </div>
                              </div>
                            <?php
                            }
                            ?>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.card -->

                  <!-- Player approval -->
                  <div class="card card-outline shadow">
                    <div class="card-body">
                      <h5 class="card-title">Player approval</h5>
                      <br />
                      <?php
                      $query = "SELECT * FROM tempplayer WHERE clubID = '$clubID'";
                      $result2 = mysqli_query($conn, $query);
                      // Loop through all temp player data and display it
                      if (mysqli_num_rows($result2) > 0) {
                        while ($row = mysqli_fetch_array($result2)) {
                          $tempUserID = $row['tempUserID'];
                          $tempUserQuery = "SELECT email FROM tempUser WHERE tempUserID = '$tempUserID'";
                          $tempUserResult = mysqli_query($conn, $tempUserQuery);
                          $tempUserRow = mysqli_fetch_array($tempUserResult);
                      ?>
                          <div class="card pt-0">
                            <div class="col-12">
                              <h2 class="header"><b><?php echo $row['firstName'];
                                                    echo " ";
                                                    echo $row['lastName']; ?></b></h2>
                              <p class="text-muted text-sm">
                                About:
                              <p>Date of birth: <?php echo $row['DOB']; ?></p>
                              <p>Email: <?php echo $tempUserRow['email']; ?></p>
                            </div>
                          </div>
                      <?php
                        }
                      } else {
                        echo "<h1>No players to approve</h1>";
                      }
                      ?>
                    </div>
                  </div>
                  <!-- End of player approval -->
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


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
        var rows = table.find("tr:gt(0)").toArray().sort(comparer($(this).index())); // Get the rows
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
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB); // Compare the values
      }
    }

    function getCellValue(row, index) {
      return $(row).children("td").eq(index).text(); // Get the value of the cell
    }
  </script>

</body>

</html>