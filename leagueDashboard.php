<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

$leagueID = $_POST['leagueID'];
$leagueName = getLeagueName($conn, $leagueID);

/* 
$leagueName = getLeagueName($conn, $leagueID);
$playerID = getPlayerID($conn, $userID);
$playerName = getPlayerName($conn, $userID);
$playerTeam = getPlayerTeam($conn, $userID);

*/
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

<body class="hold-transition sidebar-mini">
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
              <a href="clubAdminDashboard.php" class="nav-link active">
                <i class="nav-icon bi bi-house-fill"></i>
                <p>Home</p>
              </a>
            </li>
            <a href="#" class="nav-link">
              <i class="far bi bi-people-fill nav-icon"></i>
              <p>Teams</p>
            </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far bi bi-calendar-date-fill nav-icon"></i>
                <p>Fixtures/Results</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far bi bi-bar-chart-fill nav-icon"></i>
                <p>Predictions</p>
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
      <!-- Content Header (Page header) -->
      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="card card-solid">
          <div class="card-body pb-0">

            <div class="container-fluid">
              <div class="row">
                <div class="col-md-6">
                  <!-- League table -->
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
                  <!-- End of league table -->

                </div>
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-6">
                      <!-- Results -->
                      <div class="card card-outline shadow">
                        <div class="card-body">
                          <h5 class="card-title">Results</h5>
                          <br />
                          <div class="col-md-12">
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End of results -->
                    </div>
                    <div class="col-md-6">
                      <!-- Top scorers -->
                      <div class="card card-outline shadow">
                        <div class="card-body">
                          <h5 class="card-title">Top scorers</h5>
                          <br />
                          <div class="col-md-12">
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card">
                                <img src="test" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End of top scorers -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <!-- Fixtures -->
                      <div class="card card-outline shadow">
                        <div class="card-body">
                          <h5 class="card-title">Fixtures</h5>
                          <br />
                          <div class="row">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="card">
                                    <img src="test" />
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="card">
                                    <img src="test" />
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="card">
                                    <img src="test" />
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="card">
                                    <img src="test" />
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="card">
                                    <img src="test" />
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <div class="card">
                                    <img src="test" />
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- End of Fixtures -->
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
  </div>
  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="js/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/adminlte/adminlte.min.js"></script>
</body>

</html>