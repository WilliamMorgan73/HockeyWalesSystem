<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

$leagueID = $_POST['leagueID'];
$leagueName = getLeagueName($conn, $leagueID);

?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $leagueName ?> predictions</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Theme style -->
    <link rel="stylesheet" href="css/adminlte/adminlte.min.css" />
    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="css/bootstrapIcons/bootstrap-icons.css" />
    <!-- chartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.0/dist/chart.umd.min.js"></script>

</head>

<body class="hold-transition sidebar-mini"></body>
<div class="wrapper">
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.php" class="brand-link">
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
                        <a style="cursor: pointer;" class="nav-link">
                            <form action="fixturesResults.php" method="post">
                                <input type="hidden" name="leagueID" value="<?php echo $leagueID; ?>">
                                <i class="far bi bi-calendar-date-fill nav-icon"></i>
                                <button type="submit" style="background: transparent; border: none;">
                                    <p>Fixtures/Results</p>
                                </button>
                            </form>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a style="cursor:pointer" class="nav-link active">
                            <i class="far bi bi-bar-chart-fill nav-icon"></i>
                            <p>Predictions</p>
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
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="card card-solid">
                <div class="card-body -0">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- First row -->
                                <div class="row">
                                    <!-- League table -->
                                    <div class="col-md-6">
                                        <div class="card shadow" style="width: 100%">
                                            <div class="card-body p-0">
                                                <?php
                                                $sql = "SELECT teamID, teamName FROM team WHERE leagueID = $leagueID";
                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    $teams = array();
                                                    while ($row = $result->fetch_assoc()) {
                                                        $teams[] = $row;
                                                    }
                                                } else {
                                                    $teams = null;
                                                }

                                                echo '<table class="table table-striped" style="width: 100%">';
                                                echo '<thead>';
                                                echo '<tr>';
                                                echo '<th>Team</th>';
                                                echo '<th>Predicted wins</th>';
                                                echo '<th>Predicted draws</th>';
                                                echo '<th>Predicted losses</th>';
                                                echo '<th>Predicted points</th>';
                                                echo '</tr>';
                                                echo '</thead>';
                                                echo '<tbody>';

                                                foreach ($teams as $team) {
                                                    $teamID = $team['teamID'];
                                                    $teamName = $team['teamName'];
                                                    $predictedWins = getTeamWins($teamID, $conn) + getPredictedWins($teamID, $conn);
                                                    $predictedDraws = getTeamDraws($teamID, $conn) + getPredictedDraws($teamID, $conn);
                                                    $predictedLosses = getTeamLosses($teamID, $conn) + getPredictedLosses($teamID, $conn);
                                                    $predictedPoints = getPredictedPoints($teamID, $conn);

                                                    echo '<tr>';
                                                    echo '<td>' . $teamName . '</td>';
                                                    echo '<td>' . $predictedWins . '</td>';
                                                    echo '<td>' . $predictedDraws . '</td>';
                                                    echo '<td>' . $predictedLosses . '</td>';
                                                    echo '<td>' . $predictedPoints . '</td>';
                                                    echo '</tr>';
                                                }

                                                echo '</tbody>';
                                                echo '</table>';
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of league table -->
                                    <!-- Right side split -->
                                    <div class="col-md-6">
                                        <!-- Top row -->
                                        <div class="row">
                                            <!-- Predicted results -->
                                            <div class="col-md-6">
                                                <div class="card card-outline shadow">
                                                    <div class="card-body">
                                                        <h1 class="card-title">predicted results</h1>
                                                        <br />
                                                        <div class="col-md-12">
                                                            <?php
                                                            $predictedResults = getPredictedResults($leagueID, $conn);
                                                            if (!empty($predictedResults)) {
                                                                foreach ($predictedResults as $match) {
                                                                    $homeTeam = $match['homeTeam'];
                                                                    $awayTeam = $match['awayTeam'];
                                                                    $predictedResult = $match['predictedResult'];
                                                            ?>
                                                                    <div class="card">
                                                                        <h2 class="lead text-center"><b><?php echo "$homeTeam vs. $awayTeam: $predictedResult" . "<br>"; ?>
                                                                    </div>
                                                            <?php
                                                                }
                                                            } else {
                                                                echo "No predicted results found";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End of predicted results -->
                                            <!-- Predicted top scorers -->
                                            <div class="col-md-6">
                                                <div class="card card-outline shadow">
                                                    <div class="card-body">
                                                        <h1 class="card-title">Predicted top scorers</h1>
                                                        <br />
                                                        <?php
                                                        $query = "SELECT * FROM team WHERE leagueID = '$leagueID'";
                                                        $result = mysqli_query($conn, $query);

                                                        if (mysqli_num_rows($result) > 0) {
                                                            // Take the first team in the team table with a leagueID = $leagueID
                                                            $row = mysqli_fetch_array($result);
                                                            $teamID = $row['teamID'];

                                                            // Get the number of teams in the league
                                                            $numOfTeams = mysqli_num_rows($result) - 1;

                                                            // Calculate the number of games played
                                                            $numOfGames = $numOfTeams * 2;

                                                            // Get the number of records with homeTeamID or awayTeamID = $teamID
                                                            $query = "SELECT * FROM result WHERE homeTeamID = '$teamID' OR awayTeamID = '$teamID'";
                                                            $gamesPlayedResult = mysqli_query($conn, $query);
                                                            $gamesPlayed = mysqli_num_rows($gamesPlayedResult);

                                                            // Calculate the number of games left
                                                            $gamesLeft = $numOfGames - $gamesPlayed;

                                                            $query = "SELECT * FROM player WHERE teamID = '$teamID'";
                                                            $teamIDresult = mysqli_query($conn, $query);
                                                            if (mysqli_num_rows($teamIDresult) > 0) {
                                                                while ($row = mysqli_fetch_array($teamIDresult)) {
                                                                    $playerID = $row['playerID'];
                                                                    $firstName = $row['firstName'];
                                                                    $lastName = $row['lastName'];
                                                                    $teamID = $row['teamID'];

                                                                    $query = "SELECT teamName FROM team WHERE teamID = '$teamID'";
                                                                    $teamNameresult = mysqli_query($conn, $query);
                                                                    $row = mysqli_fetch_array($teamNameresult);
                                                                    $teamName = $row['teamName'];

                                                                    $query = "SELECT sum(numOfGoals) as numOfGoals FROM goal WHERE playerID = '$playerID'";
                                                                    $numOfGoalsresult = mysqli_query($conn, $query);
                                                                    if (mysqli_num_rows($numOfGoalsresult) > 0) {
                                                                        $row = mysqli_fetch_array($numOfGoalsresult);
                                                                        $numOfGoals = $row['numOfGoals'];
                                                                        // Calculate the predicted number of goals
                                                                        $predictedNumOfGoals = $numOfGoals * $gamesLeft / $gamesPlayed;
                                                                        $predictedScorers[] = array("firstName" => $firstName, "lastName" => $lastName, "teamName" => $teamName, "predictedNumOfGoals" => $predictedNumOfGoals);
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        // Sort the predictedScorers array in descending order based on the predicted number of goals
                                                        usort($predictedScorers, function ($a, $b) {
                                                            return $b['predictedNumOfGoals'] <=> $a['predictedNumOfGoals'];
                                                        });

                                                        foreach ($predictedScorers as $player) {
                                                        ?>
                                                            <div class="card col-md-12">
                                                                <h2 class="lead"><b><?php echo $player['firstName'] . ' ' . $player['lastName']; ?></b></h2>
                                                                <p class="text-muted text-sm"><b>Predicted number of goals:</b> <?php echo $player['predictedNumOfGoals']; ?></p>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>

                                                    </div>
                                                    <!-- End of predicted top scorers -->
                                                </div>
                                                <!-- End of top row -->
                                                <!-- Bottom row -->
                                                <div class="row">
                                                    <!-- Predicted points graph -->
                                                    <div class="col-md-12">
                                                        <div class="card card-outline shadow">
                                                            <div class="card-body">
                                                                <h1 class="card-title">Predicted points graph</h1>
                                                                <br />
                                                                <div class="col-md-12" style="width:400px; height:100%;">
                                                                    <canvas id="lineGraph" style="height:400px; width:100%;"></canvas>
                                                                </div>


                                                                <script>
                                                                    var ctx = document.getElementById("lineGraph").getContext("2d");
                                                                    var lineGraph = new Chart(ctx, {
                                                                        type: "line",
                                                                        data: {
                                                                            labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
                                                                            datasets: [{
                                                                                label: "Sales",
                                                                                data: [10, 20, 30, 40],
                                                                                borderColor: "rgba(54, 162, 235, 1)",
                                                                                backgroundColor: "rgba(54, 162, 235, 0.2)",
                                                                                fill: false,
                                                                                pointRadius: 5
                                                                            }]
                                                                        },
                                                                        options: {
                                                                            scales: {
                                                                                yAxes: [{
                                                                                    ticks: {
                                                                                        beginAtZero: true
                                                                                    }
                                                                                }]
                                                                            }
                                                                        }
                                                                    });
                                                                </script>



                                                                <!-- End of predicted point graph -->
                                                            </div>
                                                            <!-- End of bottom row -->
                                                        </div>
                                                        <!-- End of right side split -->
                                                    </div>
                                                    <!-- End of first row -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- REQUIRED SCRIPTS -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="js/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="js/bootstrap/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte/adminlte.min.js"></script>

</body>


<!--
Add error messages when there are no games played or no scorers etc
Install chartJS
                                                                -->

</html>