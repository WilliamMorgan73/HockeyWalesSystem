<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

session_start();
$userID = $_SESSION['userID'];
$clubAdminName = getclubAdminName($conn, $userID);
$clubName = getClubName($conn, $userID);
$clubID = getClubID($conn, $clubName);
$leagueID = getLeagueID($userID, $conn);
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
    <aside class="main-sidebar sidebar-light-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-bolder"><?php echo $clubName ?></span>
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
              <a href="#" class="nav-link">
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
              <a href="#" class="nav-link">
                <i class="far bi bi-calendar-date-fill nav-icon"></i>
                <p>Fixture availability</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far bi bi-bar-chart-fill nav-icon"></i>
                <p>Result apporval</p>
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
              <h1 class="m-0">Hi <?php echo $clubAdminName ?>,</h1>
              <h5>You can manage your club from here</h5>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

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
                            // Output data of each row
                            while ($row = mysqli_fetch_assoc($result)) {
                              $teamID = $row["teamID"];
                              echo "<tr>
                  <td>" . $row["teamName"] . "</td>
                  <td>" . getTeamWins($teamID, $conn) . "</td>
                  <td>" . getTeamDraws($teamID, $conn) . "</td>
                  <td>" . getTeamLosses($teamID, $conn) . "</td>
                  <td>" . getTeamGoalDifference($teamID, $conn) . "</td>
                  <td>" . getTeamPoints($teamID, $conn) . "</td>
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
                          <div class="row">
                            <div class="col-md-4">
                              <div class="card pt-0">
                                <div class="col-12">
                                  <h6 class="header"><b>Swansea 1s</b> vs <b>Whitchurch 1s</b></h6>
                                  <p>Date: 07/02/23</p>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card pt-0">
                                <div class="col-12">
                                  <h6 class="header"><b>Swansea 2s</b> vs <b>Whitchurch 2s</b></h6>
                                  <p>Date: 07/02/23</p>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card pt-0">
                                <div class="col-12">
                                  <h6 class="header"><b>Swansea 3s</b> vs <b>Whitchurch 3s</b></h6>
                                  <p>Date: 07/02/23</p>
                                </div>
                              </div>
                            </div>
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
</body>

</html>