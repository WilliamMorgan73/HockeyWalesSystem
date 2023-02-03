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
$clubID = getClubID($conn, $userID);
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
          <div class="info">
            <p class="d-block"> Change your profile picture</p>
            <!-- Change user pfp -->
            <form enctype="multipart/form-data" action="includes/uploadpfp.inc.php" method="POST">
              <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
              <input name="uploadedfile" type="file" />
              <input type="submit" value="Upload" />
          </div>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Hi <?php echo $clubAdminName ?>,</h1>
              <h5>You can manage your club from here</h5>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
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
              <!-- /.card -->

              <!-- Player approval -->
              <div class="card card-outline shadow">
                <div class="card-body">
                  <h5 class="card-title">Player approval</h5>
                  <br />
                  <?php
                  $query = "SELECT * FROM tempplayer WHERE clubID = '$clubID'";
                  $result = mysqli_query($conn, $query);
                  if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                      $tempUserID = $row['tempUserID'];
                      $tempUserQuery = "SELECT email FROM tempUser WHERE tempUserID = '$tempUserID'";
                      $tempUserResult = mysqli_query($conn, $tempUserQuery);
                      $tempUserRow = mysqli_fetch_array($tempUserResult);
                  ?>
                      <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                          <div class="card bg-light d-flex flex-fill">
                            <div class="card-body pt-0">
                              <div class="row">
                                <div class="col-7">
                                  <h2 class="header"><b><?php echo $row['firstName'];
                                                        echo " ";
                                                        echo $row['lastName']; ?></b></h2>
                                  <p class="text-muted text-sm">
                                    About:
                                  <p>Date of birth:</p> <?php echo $row['DOB']; ?>
                                  </p>
                                  <p>Email:</p> <?php echo $tempUserRow['email']; ?></p>
                                </div>
                              </div>
                            </div>
                            <div class="card-footer">
                              <div class="text-left">
                                <!-- Button that when clicks runs the player approve script -->
                                <form action="includes/playerApprove.inc.php" method="post">
                                  <!-- Hidden input to post the tempUserID -->
                                  <input type="hidden" name="tempUserID" value="<?php echo $row['tempUserID']; ?>">
                                  <!-- Drop-down list of teams -->
                                  <select name="teamID">
                                    <?php
                                    $teamQuery = "SELECT * FROM team WHERE clubID = '$clubID'";
                                    $teamResult = mysqli_query($conn, $teamQuery);
                                    while ($teamRow = mysqli_fetch_array($teamResult)) {
                                    ?>
                                      <option value="<?php echo $teamRow['teamID']; ?>">
                                        <?php echo $teamRow['teamName']; ?>
                                      </option>
                                    <?php
                                    }
                                    ?>
                                  </select>
                                  <!-- Submit button to approve the player -->
                                  <button type="submit">Approve Player</button>
                                </form>
                              </div>
                              <div class="text-right">
                                <!-- Button that when clicks runs the player reject script -->
                                <form action="includes/playerReject.inc.php" method="post">
                                  <!-- Hidden input to post the tempUserID -->
                                  <input type="hidden" name="tempUserID" value="<?php echo $row['tempUserID']; ?>">
                                  <!-- Submit button to reject the player -->
                                  <button type="submit">Reject Player</button>
                              </div>
                            </div>
                          </div>
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

            <!-- League table -->
            <div class="col-lg-6">
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
                      $sql = "SELECT * FROM team t 
                    JOIN club c ON t.clubID = c.clubID
                    WHERE t.leagueID = (SELECT leagueID FROM team WHERE teamID = (SELECT clubID FROM clubAdmin WHERE userID = '$userID'))";
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
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
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