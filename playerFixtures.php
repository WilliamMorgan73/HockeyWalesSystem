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
$playerID = getPlayerID($conn, $userID);
$playerTeam = getPlayerTeam($conn, $userID);

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
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb">
                        <div class="col-sm">
                            <h1>Fixtures</h1>
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
                            <!-- Loop through fixtures where homeTeamID or awayTeamID = $teamID -->
                            <?php

                            $query = "SELECT teamID FROM player WHERE playerID = '$playerID'";
                            $result = mysqli_query($conn, $query);
                            $teamID = mysqli_fetch_array($result)['teamID'];

                            $query = "SELECT * FROM fixture WHERE homeTeamID = '$teamID' OR awayTeamID = '$teamID'";
                            $result = mysqli_query($conn, $query);

                            if ($result === false) {
                                die("Error: " . mysqli_error($conn));
                            }

                            // Loop through the fixtures and display only the fixtures that match the team ID
                            while ($fixture = mysqli_fetch_array($result)) {
                                if ($fixture['homeTeamID'] == $teamID || $fixture['awayTeamID'] == $teamID) {
                                    // Display the fixture
                            ?>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                        <div class="card bg-light d-flex flex-fill">
                                            <div class="card-body pt-0 text-center">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h4 class="header"><b><?php
                                                                                // Get the home team ID from the fixture
                                                                                $homeTeamID = $fixture['homeTeamID'];
                                                                                // Get the home team name from the team database using the home team ID
                                                                                $homeTeamQuery = "SELECT * FROM team WHERE teamID = '$homeTeamID'";
                                                                                $homeTeamResult = mysqli_query($conn, $homeTeamQuery);
                                                                                $homeTeam = mysqli_fetch_array($homeTeamResult);
                                                                                echo $homeTeam['teamName'];
                                                                                ?></b></h4>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <img src="images\clubLogos\<?php
                                                                                    $query = "SELECT clubID FROM team WHERE teamID = '$homeTeamID'";
                                                                                    $result = mysqli_query($conn, $query);
                                                                                    $team = mysqli_fetch_array($result);
                                                                                    $clubID = $team['clubID'];
                                                                                    echo $clubID . '.jpg';
                                                                                    ?>" style="display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h4 class="header"><b><?php
                                                                                // Get the home team ID from the fixture
                                                                                $awayTeamID = $fixture['awayTeamID'];
                                                                                // Get the home team name from the team database using the home team ID
                                                                                $awayTeamQuery = "SELECT * FROM team WHERE teamID = '$awayTeamID'";
                                                                                $awayTeamResult = mysqli_query($conn, $awayTeamQuery);
                                                                                $awayTeam = mysqli_fetch_array($awayTeamResult);
                                                                                echo $awayTeam['teamName'];
                                                                                ?></b></h4>
                                                    </div>
                                                </div>
                                                <div class="row" style="padding-bottom: 1%;">
                                                    <div class="col-12">
                                                        <img src="images\clubLogos\<?php
                                                                                    $query = "SELECT clubID FROM team WHERE teamID = '$awayTeamID'";
                                                                                    $result = mysqli_query($conn, $query);
                                                                                    $team = mysqli_fetch_array($result);
                                                                                    $clubID = $team['clubID'];
                                                                                    echo $clubID . '.jpg';
                                                                                    ?>" style="display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p><?php echo $fixture['location']; ?> | <?php echo date("h:i A", strtotime($fixture['dateTime'])); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="row">
                                                    <div class="col-md-4 text-left">
                                                        <!-- Button that submits players availability as available -->
                                                        <form action="includes\availability.php" method="post">
                                                            <input type="hidden" name="playerID" value="<?php echo $playerID; ?>">
                                                            <input type="hidden" name="fixtureID" value="<?php echo $fixture['fixtureID']; ?>">
                                                            <input type="hidden" name="available" value="1">
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                Available
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <?php
                                                        $query = "SELECT * FROM availability WHERE playerID = $playerID AND fixtureID = " . $fixture['fixtureID'];
                                                        $result = mysqli_query($conn, $query);
                                                        if (mysqli_num_rows($result) > 0) {
                                                            $availability = mysqli_fetch_assoc($result);
                                                            if ($availability['available']) {
                                                                echo "Available";
                                                            } else {
                                                                echo "Unavailable";
                                                            }
                                                        } else {
                                                            echo "Not Yet Responded";
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <!-- Button that submits players availability as unavailable -->
                                                        <form action="includes\availability.php" method="post">
                                                            <input type="hidden" name="playerID" value="<?php echo $playerID; ?>">
                                                            <input type="hidden" name="fixtureID" value="<?php echo $fixture['fixtureID']; ?>">
                                                            <input type="hidden" name="available" value="0">
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                Unavailable
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
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