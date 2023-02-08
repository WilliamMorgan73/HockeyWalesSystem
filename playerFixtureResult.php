<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

//Variables
session_start();
$userID = $_SESSION['userID'];

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

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
                <span class="brand-text font-weight-bolder">HOCKEY WALES</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a style="cursor: pointer;" class="nav-link">
                                <form action="playerDashboard.php" method="post">
                                    <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                                    <i class="nav-icon bi bi-house-fill"></i>
                                    <button type="submit" style="background: transparent; border: none;">
                                        <p>Home</p>
                                    </button>
                                </form>
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
                            <a style="cursor:pointer" class="nav-link active">
                                <i class="far bi bi-calendar-date-fill nav-icon"></i>
                                <p>Fixtures</p>
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
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0 text-center">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Whitchurch 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <img src="images\clubLogos\1.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Swansea 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1%;">
                                            <div class="col-12">
                                                <img src="images\clubLogos\3.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p>WIS | 11/02/23 | 12:00 </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Available
                                                    </button>
                                                </form>
                                            </div>
                                            <div class=" col-md-6 text-right">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Unavailable
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0 text-center">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Whitchurch 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <img src="images\clubLogos\1.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Gwent 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1%;">
                                            <div class="col-12">
                                                <img src="images\clubLogos\2.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p>Treforest | 18/02/23 | 14:00</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Available
                                                    </button>
                                                </form>
                                            </div>
                                            <div class=" col-md-6 text-right">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Unavailable
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0 text-center">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Whitchurch 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <img src="images\clubLogos\1.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Whitchurch 2's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1%;">
                                            <div class="col-12">
                                                <img src="images\clubLogos\1.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p>WIS | 25/02/23 | 8:30 </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Available
                                                    </button>
                                                </form>
                                            </div>
                                            <div class=" col-md-6 text-right">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Unavailable
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0 text-center">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Whitchurch 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <img src="images\clubLogos\1.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Gwent 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1%;">
                                            <div class="col-12">
                                                <img src="images\clubLogos\2.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p>Whitchurch high school | 04/03/23 | 16:00 </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Available
                                                    </button>
                                                </form>
                                            </div>
                                            <div class=" col-md-6 text-right">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Unavailable
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0 text-center">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Whitchurch 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <img src="images\clubLogos\1.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Swansea 2's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1%;">
                                            <div class="col-12">
                                                <img src="images\clubLogos\3.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p>WIS | 11/03/23 | 8:30 </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Available
                                                    </button>
                                                </form>
                                            </div>
                                            <div class=" col-md-6 text-right">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Unavailable
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                    <div class="card-body pt-0 text-center">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Whitchurch 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <img src="images\clubLogos\1.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="header"><b>Swansea 1's</b></h4>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 1%;">
                                            <div class="col-12">
                                                <img src="images\clubLogos\3.jpg" style="margin-left: auto; margin-right: auto; width: 25%; height: 100%;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p>WIS | 19/03/23 | 9:15</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Available
                                                    </button>
                                                </form>
                                            </div>
                                            <div class=" col-md-6 text-right">
                                                <!-- Button that takes you to the club page -->
                                                <form action="'#" method="post">
                                                    <input type="hidden" name="#" value="#">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Unavailable
                                                    </button>
                                                </form>
                                            </div>
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