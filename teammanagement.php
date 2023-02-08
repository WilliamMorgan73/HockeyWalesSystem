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
    <title>Teammates</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="css/adminlte/adminlte.min.css" />
    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="css/bootstrapIcons/bootstrap-icons.css" />
    <!-- Select2 -->
    <link rel="stylesheet" href="css/select2/select2.min.css">
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
                            <a href="clubAdminDashboard.php" class="nav-link">
                                <i class="nav-icon bi bi-house-fill"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="far bi bi-people-fill nav-icon" ></i>
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
                            <h1>Teammates</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="card card-solid">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-left">
                                    <h3><?php echo $clubName ?> 1's</h3> <!-- Make this say team name instead -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-right">
                                    <div class="form-group">
                                        <select class="form-control select2" style="width: 30%;">
                                            <option selected="selected"> <?php echo $clubName ?> 1's</option> <!-- Make this say team name instead -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h4 class="header"><b>Ethan Smith</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <img src="images\pfp\defaultpfp.jpg" style="  display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <!-- Button that takes you to the club page -->
                                            <form action="'#" method="post">
                                                <div class="form-group">
                                                    <label> Change Team:</label>
                                                    <select class="form-control select2" style="width: 50%;">
                                                        <option selected="selected"> <?php echo $clubName ?> 1's</option> <!-- Make this say team name instead -->
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Change team
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h4 class="header"><b>Kevin Phillips</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <img src="images\pfp\defaultpfp.jpg" style="  display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <!-- Button that takes you to the club page -->
                                            <form action="'#" method="post">
                                                <div class="form-group">
                                                    <label> Change Team:</label>
                                                    <select class="form-control select2" style="width: 50%;">
                                                        <option selected="selected"> <?php echo $clubName ?> 1's</option> <!-- Make this say team name instead -->
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Change team
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h4 class="header"><b>Pete Davidson</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <img src="images\pfp\defaultpfp.jpg" style="  display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <!-- Button that takes you to the club page -->
                                            <form action="'#" method="post">
                                                <div class="form-group">
                                                    <label> Change Team:</label>
                                                    <select class="form-control select2" style="width: 50%;">
                                                        <option selected="selected"> <?php echo $clubName ?> 1's</option> <!-- Make this say team name instead -->
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Change team
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h4 class="header"><b>David Tennant</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <img src="images\pfp\defaultpfp.jpg" style="  display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <!-- Button that takes you to the club page -->
                                            <form action="'#" method="post">
                                                <div class="form-group">
                                                    <label> Change Team:</label>
                                                    <select class="form-control select2" style="width: 50%;">
                                                        <option selected="selected"> <?php echo $clubName ?> 1's</option> <!-- Make this say team name instead -->
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Change team
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h4 class="header"><b>Matt Smith</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <img src="images\pfp\defaultpfp.jpg" style="  display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <!-- Button that takes you to the club page -->
                                            <form action="'#" method="post">
                                                <div class="form-group">
                                                    <label> Change Team:</label>
                                                    <select class="form-control select2" style="width: 50%;">
                                                        <option selected="selected"> <?php echo $clubName ?> 1's</option> <!-- Make this say team name instead -->
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Change team
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h4 class="header"><b>John Smith</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <img src="images\pfp\defaultpfp.jpg" style="  display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <!-- Button that takes you to the club page -->
                                            <form action="'#" method="post">
                                                <div class="form-group">
                                                    <label> Change Team:</label>
                                                    <select class="form-control select2" style="width: 50%;">
                                                        <option selected="selected"> <?php echo $clubName ?> 1's</option> <!-- Make this say team name instead -->
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Change team
                                                </button>
                                            </form>
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
    <!-- Select2 -->
    <script src="js/select2/select2.full.min.js"></script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        })
    </script>
</body>

</html>