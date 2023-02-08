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
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $clubAdminName ?>'s player approval</title>

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
                        <li class="nav-item ">
                            <a href="clubAdminDashboard.php" class="nav-link">
                                <i class="nav-icon bi bi-house-fill"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <a href="#" class="nav-link">
                            <i class="far bi bi-people-fill nav-icon"></i>
                            <p>Team management</p>
                        </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
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
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb">
                        <div class="col-sm">
                            <h1>Player approval</h1>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="card card-solid">
                    <div class="card-body pb-0">
                        <div class="row">
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
                                        <div class="card bg-light d-flex flex-fill">
                                            <div class="card-body pt-0">
                                                <div class="col-7">
                                                    <h2 class="header"><b><?php echo $row['firstName'];
                                                                            echo " ";
                                                                            echo $row['lastName']; ?></b></h2>
                                                    <p class="text-muted text-sm">
                                                        About:
                                                    <p>Date of birth: <?php echo $row['DOB']; ?></p>
                                                    <p>Email: <?php echo $tempUserRow['email']; ?></p>
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
                            <?php
                                }
                            } else {
                                echo "<h1>No players to approve</h1>";
                            }
                            ?>
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