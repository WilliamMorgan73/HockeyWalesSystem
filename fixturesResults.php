<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

//Variables

$leagueID = $_POST['leagueID'];
$leagueName = getLeagueName($leagueID);

?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $leagueName ?> fixtures/results</title>

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
                <span class="brand-text font-weight-bolder"><?php echo $leagueName ?></span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a style="cursor: pointer;" class="nav-link">
                                <form action="leagueDashboard.php" method="post">
                                    <input type="hidden" name="leagueID" value="<?php echo $leagueID; ?>">
                                    <i class="far bi bi-house-fill nav-icon"></i>
                                    <button type="submit" style="background: transparent; border: none;">
                                        <p>Home</p>
                                    </button>
                                </form>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="cursor: pointer;" class="nav-link">
                                <form action="leagueTeams.php" method="post">
                                    <input type="hidden" name="leagueID" value="<?php echo $leagueID; ?>">
                                    <i class="far bi bi-people-fill nav-icon"></i>
                                    <button type="submit" style="background: transparent; border: none;">
                                        <p>Teams</p>
                                    </button>
                                </form>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="cursor:pointer" class="nav-link active">
                                <i class="far bi bi-calendar-date-fill nav-icon"></i>
                                <p>Fixtures/Results</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="cursor: pointer;" class="nav-link">
                                <form action="predictedLeagueDashboard.php" method="post">
                                    <input type="hidden" name="leagueID" value="<?php echo $leagueID; ?>">
                                    <i class="far bi bi-bar-chart-fill nav-icon"></i>
                                    <button type="submit" style="background: transparent; border: none;">
                                        <p>Predictions</p>
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
                            <h1>
                                <?php
                                // Get the selected week from the form
                                if (isset($_POST['selectedWeek'])) {
                                    $selectedWeek = $_POST['selectedWeek'];
                                } else {
                                    $query = "SELECT MIN(matchWeek) AS minWeek FROM fixture";
                                    $result = mysqli_query($conn, $query);
                                    if (mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_assoc($result);
                                        $selectedWeek = $row['minWeek'];
                                    } else {
                                        echo "No game week numbers found in the fixture table";
                                    }
                                }
                                //Get the maximum week number from the result table
                                $query = "SELECT MAX(matchWeek) AS maxWeek FROM result";
                                $result = mysqli_query($conn, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                    $maxWeek = $row['maxWeek'];

                                    if ($selectedWeek > $maxWeek) {
                                        echo "Fixtures";
                                    } else {
                                        echo "Results";
                                    }
                                } else {
                                    echo "No game week numbers found in the result table";
                                }
                                ?>

                            </h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="card card-solid">
                    <div class="card-header pb-0">
                        <form action="" method="post">
                            <input type="hidden" name="leagueID" value="<?php echo $leagueID; ?>">
                            <div class="form-group">
                                <label for="selectedWeek">Select Game Week:</label>
                                <select name="selectedWeek" id="selectedWeek" class="form-control">
                                    <?php
                                    // Get the game weeks from the database
                                    $query = "SELECT gameWeekID, gameDate FROM gameweek";
                                    $result = mysqli_query($conn, $query);
                                    // Display the game weeks in the select box
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $matchWeekID = $row['gameWeekID'];
                                            $gameDate = $row['gameDate'];
                                            echo "<option value='$matchWeekID'>$gameDate</option>";
                                        }
                                    } else {
                                        echo "No game weeks found";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-danger" style="margin-bottom:10px">Submit</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-body pb-0">
                        <div class="row">
                            <?php
                            // Get the fixtures and results for the selected week
                            $query = "SELECT * FROM fixture WHERE matchWeek = '$selectedWeek' AND leagueID = '$leagueID' UNION ALL SELECT * FROM result WHERE matchWeek = '$selectedWeek' AND leagueID = '$leagueID'";
                            $result = mysqli_query($conn, $query);
                            // Display the fixtures and results
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    $homeTeamID = $row['homeTeamID'];
                                    $awayTeamID = $row['awayTeamID'];
                                    // Get the team names and club IDs for the home and away teams
                                    $query = "SELECT teamName, clubID FROM team WHERE teamID = '$homeTeamID'";
                                    $result2 = mysqli_query($conn, $query);
                                    $homeRow = mysqli_fetch_array($result2);
                                    $homeTeamName = $homeRow['teamName'];
                                    $homeClubID = $homeRow['clubID'];

                                    $query = "SELECT teamName, clubID FROM team WHERE teamID = '$awayTeamID'";
                                    $result2 = mysqli_query($conn, $query);
                                    $awayRow = mysqli_fetch_array($result2);
                                    $awayTeamName = $awayRow['teamName'];
                                    $awayClubID = $awayRow['clubID'];
                            ?>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                        <div class="card bg-light d-flex flex-fill">
                                            <div class="card-body pt-0 text-center">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <b><?php echo $homeTeamName; ?></b>
                                                        <?php $homeClubImage = "images/clubLogos/" . $homeClubID . ".jpg"; ?>
                                                        <img src="<?php echo $homeClubImage; ?>" style="display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">
                                                        <br><br>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <b><?php echo $awayTeamName; ?></b>
                                                        <?php $awayClubImage = "images/clubLogos/" . $awayClubID . ".jpg"; ?>
                                                        <img src="<?php echo $awayClubImage; ?>" style="display: block; margin-left: auto; margin-right: auto; width: 50%; display: block;">
                                                        <br><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="text-center">
                                                    <?php
                                                    // Display the result if the match has been played, otherwise display the location and time
                                                    if (array_key_exists("homeTeamScore", $row)) {
                                                        echo "Result: " . $row['homeTeamScore'] . " - " . $row['awayTeamScore'];
                                                    } else {
                                                        echo "Location: " . $row['location'] . "<br>Time: " . $row['dateTime'];
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "No matches found";
                            }
                            mysqli_close($conn);
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