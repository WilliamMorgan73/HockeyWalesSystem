<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

//Variables
session_start();
$currentUserID = $_SESSION['userID'];
$currentPlayerID = getPlayerID($currentUserID);
$teamID = getTeamID($currentUserID);

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
                                    <input type="hidden" name="userID" value="<?php echo $currentUserID; ?>">
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
                                <p>Teammates</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="cursor: pointer;" class="nav-link">
                                <form action="playerFixtures.php" method="post">
                                    <input type="hidden" name="userID" value="<?php echo $currentUserID; ?>">
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
                            <!-- PHP code to get all players with the same teamID -->
                            <?php
                            // get all the players who have the same teamID as the current user so they can be displayed
                            $sql = "SELECT playerID, userID, firstName, lastName FROM player WHERE teamID = ? AND playerID != ?";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                header("Location: ../signup.php?error=stmtfailed");
                                exit();
                            }

                            mysqli_stmt_bind_param($stmt, "ss", $teamID, $currentPlayerID);
                            mysqli_stmt_execute($stmt);
                            $resultData = mysqli_stmt_get_result($stmt);
                            //loop through all the players and display their name and profile picture 
                            while ($row = mysqli_fetch_assoc($resultData)) {
                                $userID = $row['userID'];
                                $firstName = $row['firstName'];
                                $lastName = $row['lastName'];
                            ?>
                                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                    <div class="card bg-light d-flex flex-fill">
                                        <div class="card-body pt-0">
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <h4 class="header"><b><?php echo $firstName . " " . $lastName; ?></b></h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <img src="images\pfp\<?php echo $userID . '.jpg'; ?>" style="display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
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