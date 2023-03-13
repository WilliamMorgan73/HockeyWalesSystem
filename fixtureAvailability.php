<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

session_start();
$userID = $_SESSION['userID'];

$clubAdminName = getclubAdminName($userID);
$clubName = getClubName($userID);
$clubID = getClubID($clubName);
$teams = getTeams($clubID);

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
                            <a href="#" class="nav-link active">
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
                            <a href="approvePasswordChange.php" class="nav-link">
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
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb">
                        <div class="col-sm">
                            <h1>Fixture availability</h1>
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
                                    // Get the lowest teamID with the same clubID
                                    if ($selectedTeamID == null) {
                                        // Get the lowest teamID for the club
                                        $query = "SELECT MIN(teamID) as lowestTeamID, teamName FROM team WHERE clubID = '$clubID'";
                                        $result = mysqli_query($conn, $query);
                                        $row = mysqli_fetch_assoc($result);
                                        $selectedTeamName = $row['teamName'];
                                        $selectedTeamID = $row['lowestTeamID'];
                                    }
                                    // If the user has selected a team, get the team name
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
                                            <select class="form-control select2 " style="width: 30%;" name="selected-team-id">
                                                <?php
                                                // Get all teams for the club
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
                                            <button type="submit" class="btn btn-danger">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <?php
                        //Get fixtures where the home team or away team has clubID = $clubID
                        $fixtures = getFixturesByTeamID($selectedTeamID);
                        //Loop through each fixture
                        foreach ($fixtures as $fixture) {
                            $homeTeamID = $fixture['homeTeamID'];
                            $awayTeamID = $fixture['awayTeamID'];
                            $fixtureID = $fixture['fixtureID'];
                            $homeTeamName = getTeamNameByID($homeTeamID);
                            $awayTeamName = getTeamNameByID($awayTeamID);
                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3><?php echo "$homeTeamName - $awayTeamName"; ?></h3>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-md-4 border-right broder-dark">
                                            <h5>Available</h5>
                                            <?php
                                            //Get all players with availability = 1 for this fixture
                                            $availablePlayers = getAvailablePlayersByFixtureID($fixtureID);
                                            foreach ($availablePlayers as $player) {
                                                $playerID = $player['playerID'];
                                                $playerName = getPlayerNameByID($playerID);
                                            ?>
                                                <p><?php echo $playerName; ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-4 border-right broder-dark">
                                            <h5>Unavailable</h5>
                                            <?php
                                            //Get all players with availability = 0 for this fixture
                                            $unavailablePlayers = getUnavailablePlayersByFixtureID($fixtureID);
                                            foreach ($unavailablePlayers as $player) {
                                                $playerID = $player['playerID'];
                                                $playerName = getPlayerNameByID($playerID);
                                            ?>
                                                <p><?php echo $playerName; ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>Unanswered</h5>
                                            <?php
                                            //Get all players with no entry in the availability table for this fixture
                                            $unansweredPlayers = getUnansweredPlayersByFixtureID($fixtureID);
                                            foreach ($unansweredPlayers as $player) {
                                                $playerID = $player['playerID'];
                                                $playerName = getPlayerNameByID($playerID);
                                            ?>
                                                <p><?php echo $playerName; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
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