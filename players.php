<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

//Variables

?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Players</title>

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
                <span class="brand-text font-weight-bolder">HOCKEY WALES</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a style="cursor: pointer;" class="nav-link">
                                <form action="clubDashboard.php" method="post">
                                    <input type="hidden" name="#" value="#">
                                    <i class="nav-icon bi bi-house-fill"></i>
                                    <button type="submit" style="background: transparent; border: none;">
                                        <p>Home</p>
                                    </button>
                                </form>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="cursor:pointer" class="nav-link active">
                                <i class="far bi bi-people-fill nav-icon"></i>
                                <p>Players</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="cursor: pointer;" class="nav-link">
                                <form action="clubFixtureResults.php" method="post">
                                    <input type="hidden" name="#" value="#">
                                    <i class="far bi bi-calendar-date-fill nav-icon"></i>
                                    <button type="submit" style="background: transparent; border: none;">
                                        <p>Fixtures/Results</p>
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
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb">
                        <div class="col-sm">
                            <h1>Players</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="card card-solid">
                    <div class="card-header pb-0 text-right">
                        <div class="form-group">
                            <select class="select2" multiple="multiple" data-placeholder="Filters" style="width: 25%;">
                            </select>
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
                                                <h6 class="header"><b>Whitchurch 1's</b></h6>
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
                                                <input type="hidden" name="clubID" value="#">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    View player
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
                                                <h6 class="header"><b>Whitchurch 1's</b></h6>
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
                                                <input type="hidden" name="clubID" value="#">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    View player
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
                                                <h6 class="header"><b>Whitchurch 1's</b></h6>
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
                                                <input type="hidden" name="clubID" value="#">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    View player
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
                                                <h6 class="header"><b>Whitchurch 1's</b></h6>
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
                                                <input type="hidden" name="clubID" value="#">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    View player
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
                                                <h6 class="header"><b>Whitchurch 1's</b></h6>
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
                                                <input type="hidden" name="clubID" value="#">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    View player
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
                                                <h6 class="header"><b>Whitchurch 1's</b></h6>
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
                                                <input type="hidden" name="clubID" value="#">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    View player
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