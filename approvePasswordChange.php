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
    <title><?php echo $clubName ?> password requests</title>

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
                        <li class="nav-item">
                            <a href="clubAdminDashboard.php" class="nav-link">
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
                            <a href="#" class="nav-link active">
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
                            <h5>You can manage all your team's password requests here</h5>
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
                                <?php
                                // Get all teamIDs with a clubID = $clubID
                                $teamIDs = array();
                                $result = mysqli_query($conn, "SELECT teamID FROM team WHERE clubID = '$clubID'");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $teamIDs[] = $row['teamID'];
                                }

                                // Get all playerIDs with a teamID in $teamIDs
                                $playerIDs = array();
                                if (!empty($teamIDs)) {
                                    $teamIDsStr = implode(',', $teamIDs);
                                    $result = mysqli_query($conn, "SELECT playerID FROM player WHERE teamID IN ($teamIDsStr)");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $playerIDs[] = $row['playerID'];
                                    }
                                }

                                // Get all userIDs with a playerID in $playerIDs
                                $userIDs = array();
                                if (!empty($playerIDs)) {
                                    $playerIDsStr = implode(',', $playerIDs);
                                    $result = mysqli_query($conn, "SELECT userID FROM player WHERE playerID IN ($playerIDsStr)");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $userIDs[] = $row['userID'];
                                    }
                                }

                                // Get all password change requests for userIDs in $userIDs where status is "waiting"
                                if (!empty($userIDs)) {
                                    $userIDsStr = implode(',', $userIDs);
                                    $result = mysqli_query($conn, "SELECT * FROM passwordchangerequest WHERE userID IN ($userIDsStr) AND status='waiting'");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Get user details from userID
                                        $userID = $row['userID'];
                                        $userDetails = getUserDetails($userID);
                                        $firstName = $userDetails['firstName'];
                                        $lastName = $userDetails['lastName'];
                                        $DOB = $userDetails['DOB'];

                                        // Display the user details and profile picture
                                        echo '<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">';
                                        echo '<div class="card bg-light d-flex flex-fill">';
                                        echo '<div class="card-body pt-0">';
                                        echo '<div class="row">';
                                        echo '<div class="col-12 text-center">';
                                        echo '<h4 class="header"><b>' . $firstName . ' ' . $lastName . '</b></h4>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="row">';
                                        echo '<div class="col-12 text-center">';
                                        echo '<img src="images/pfp/' . $userID . '.jpg" style="display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="row">';
                                        echo '<div class="col-12 text-center">';
                                        echo '<p><b>Date of Birth:</b> ' . $DOB . '</p>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="row">';
                                        echo '<div class="col-md-6 text-left">';
                                        echo '<!-- Button that approves password change request -->';
                                        echo '<form action="includes\approvePasswordChange.php" method="post">';
                                        echo '<input type="hidden" name="userID" value="' . $userID . '">';
                                        echo '<button type="submit" class="btn btn-sm btn-success">';
                                        echo 'Approve';
                                        echo '</button>';
                                        echo '</form>';
                                        echo '</div>';
                                        echo '<div class="col-md-6 text-right">';
                                        echo '<!-- Button that rejects password change request -->';
                                        echo '<form action="includes\rejectPasswordChange.php" method="post">';
                                        echo '<input type="hidden" name="userID" value="' . $userID . '">';
                                        echo '<button type="submit" class="btn btn-sm btn-danger">';
                                        echo 'Reject';
                                        echo '</button>';
                                        echo '</form>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo "No password change requests found for players in this club.";
                                }
                                ?>
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