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
$teams = getTeams($conn, $clubID);
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage teams</title>

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
                        <li class="nav-item">
                            <a href="clubAdminDashboard.php" class="nav-link">
                                <i class="nav-icon bi bi-house-fill"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
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
                            <h1>Manage teams</h1>
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
                                    <?php
                                    $selectedTeamName = '';
                                    $selectedTeamID = null;
                                    if (isset($_POST['selected-team-id'])) {
                                        $selectedTeamID = $_POST['selected-team-id'];
                                        foreach ($teams as $team) {
                                            if ($team['teamID'] == $selectedTeamID) {
                                                $selectedTeamName = $team['teamName'];
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <h3 id="selected-team-name"><?php echo $selectedTeamName ? $selectedTeamName : $clubName . " 1's"; ?></h3>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-right">
                                    <div class="form-group">
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <select class="form-control select2" style="width: 30%;" name="selected-team-id">
                                                <?php
                                                foreach ($teams as $team) {
                                                    $teamID = $team['teamID'];
                                                    $teamName = $team['teamName'];
                                                    $selected = '';
                                                    if ($selectedTeamID == $teamID) {
                                                        $selected = 'selected';
                                                    }
                                                    echo "<option value='$teamID' $selected>$teamName</option>";
                                                }
                                                ?>
                                            </select>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <?php
                            // get the selected team ID from the hidden input
                            if (!isset($_POST["selected-team-id"])) {
                                // Get the lowest teamID for the club
                                $query = "SELECT MIN(teamID) as lowestTeamID, teamName FROM team WHERE clubID = '$clubID'";
                                $result = mysqli_query($conn, $query);
                                $row = mysqli_fetch_assoc($result);
                                $teamName = $row['teamName'];
                                $teamID = $row['lowestTeamID'];
                            } elseif (isset($_POST["selected-team-id"])) {
                                $teamID = $_POST["selected-team-id"];
                            }

                            // get all the players who have the same teamID as the current user
                            $sql = "SELECT playerID, userID, firstName, lastName FROM player WHERE teamID = ?";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                header("Location: ../signup.php?error=stmtfailed");
                                exit();
                            }

                            mysqli_stmt_bind_param($stmt, "s", $teamID);
                            mysqli_stmt_execute($stmt);
                            $resultData = mysqli_stmt_get_result($stmt);

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
                                        <div class="card-footer">
                                            <div class="text-center">
                                                <form action="includes/updateTeam.php" method="post">
                                                    <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                                                    <input type="hidden" name="clubID" value="<?php echo $clubID; ?>">
                                                    <!-- Drop down to change the player's team -->
                                                    <select name="change-team-id">
                                                        <?php
                                                        // get all the teams from the database
                                                        $sql = "SELECT teamID, teamName FROM team WHERE clubID = $clubID";
                                                        $result = mysqli_query($conn, $sql);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $teamID = $row['teamID'];
                                                            $teamName = $row['teamName'];
                                                            if ($teamID == $selectedTeamID) {
                                                                continue;
                                                            }
                                                        ?>
                                                            <option value="<?php echo $teamID; ?>"><?php echo $teamName; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <!-- Submit button to change the player's teamID -->
                                                    <button type="submit" name="change-team">Change Team</button>
                                                </form>
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