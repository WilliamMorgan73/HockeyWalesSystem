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

</head>

<body class="hold-transition sidebar-mini"></body>
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
        <!-- Main content -->
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb">
                        <div class="col-sm">
                            <h1><?php echo $leagueName ?> predictions</h1>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- Default box -->
            <div class="card card-solid">
                <div class="card-body -0">
                    <div class="container-fluid">
                        <!-- First row - League table, top scorers, and results -->
                        <div class="row">
                            <!-- League table -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
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

                                                    // Sort the teams based on their predicted points
                                                    usort($teams, function ($a, $b) use ($conn) {
                                                        $teamAID = $a['teamID'];
                                                        $teamBID = $b['teamID'];
                                                        $predictedPointsA = getPredictedPoints($teamAID, $conn);
                                                        $predictedPointsB = getPredictedPoints($teamBID, $conn);
                                                        return $predictedPointsB - $predictedPointsA;
                                                    });
                                                } else {
                                                    $teams = null;
                                                }

                                                echo '<table class="table table-striped" style="width: 100%">';
                                                echo '<thead>';
                                                echo '<tr>';
                                                echo '<th onclick="sortTable(0)">Team</th>';
                                                echo '<th onclick="sortTable(1)">Predicted wins</th>';
                                                echo '<th onclick="sortTable(2)">Predicted draws</th>';
                                                echo '<th onclick="sortTable(3)">Predicted losses</th>';
                                                echo '<th onclick="sortTable(4)">Predicted points</th>';
                                                echo '</tr>';
                                                echo '</thead>';
                                                echo '<tbody id="tableBody">';

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
                                </div>
                                <!-- End of league table -->
                                <div class="row">
                                    <!-- Predicted top scorers -->
                                    <div class="col-md-12">
                                        <div class="card card-outline shadow">
                                            <div class="card-body">
                                                <h1 class="card-title">Predicted top scorers</h1>
                                                <br />
                                                <div class="col-md-12">
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

                                                        if (!$gamesPlayedResult) {
                                                            echo "Error: " . mysqli_error($conn);
                                                            exit();
                                                        }

                                                        $gamesPlayed = mysqli_num_rows($gamesPlayedResult);

                                                        // Calculate the number of games left
                                                        $gamesLeft = $numOfGames - $gamesPlayed;

                                                        $query = "SELECT player.* FROM player
        INNER JOIN team ON player.teamID = team.teamID
        WHERE team.leagueID = '$leagueID'";

                                                        $teamIDresult = mysqli_query($conn, $query);

                                                        if (!$teamIDresult) {
                                                            echo "Error: " . mysqli_error($conn);
                                                            exit();
                                                        }

                                                        if (mysqli_num_rows($teamIDresult) > 0) {
                                                            while ($row = mysqli_fetch_array($teamIDresult)) {
                                                                $playerID = $row['playerID'];
                                                                $firstName = $row['firstName'];
                                                                $lastName = $row['lastName'];
                                                                $teamID = $row['teamID'];

                                                                $query = "SELECT teamName FROM team WHERE teamID = '$teamID'";
                                                                $teamNameresult = mysqli_query($conn, $query);

                                                                if (!$teamNameresult) {
                                                                    echo "Error: " . mysqli_error($conn);
                                                                    exit();
                                                                }

                                                                $row = mysqli_fetch_array($teamNameresult);
                                                                $teamName = $row['teamName'];

                                                                $query = "SELECT numOfGoals FROM goal WHERE playerID = '$playerID'";
                                                                $numOfGoalsresult = mysqli_query($conn, $query);

                                                                if (!$numOfGoalsresult) {
                                                                    echo "Error: " . mysqli_error($conn);
                                                                    exit();
                                                                }

                                                                $numOfGoals = 0;

                                                                if (mysqli_num_rows($numOfGoalsresult) > 0) {
                                                                    while ($row = mysqli_fetch_array($numOfGoalsresult)) {
                                                                        $numOfGoals += $row['numOfGoals'];
                                                                    }
                                                                }

                                                                $query = "SELECT numOfAppearances FROM appearance WHERE playerID = '$playerID'";
                                                                $appearancesResult = mysqli_query($conn, $query);



                                                                if (!$appearancesResult) {
                                                                    echo "Error: " . mysqli_error($conn);
                                                                    exit();
                                                                }
                                                                $appearancesRow = mysqli_fetch_array($appearancesResult);
                                                                $appearances = $appearancesRow['numOfAppearances'];

                                                                // Calculate the predicted number of goals
                                                                if ($appearances > 0) {
                                                                    $predictedNumOfGoals = round($numOfGoals * $gamesLeft / $appearances);
                                                                } else {
                                                                    $predictedNumOfGoals = 0;
                                                                }

                                                                $predictedScorers[] = array("firstName" => $firstName, "lastName" => $lastName, "teamName" => $teamName, "predictedNumOfGoals" => $predictedNumOfGoals);
                                                            }
                                                        }
                                                    }
                                                    // Sort the predictedScorers array in descending order based on the predicted number of goals
                                                    usort($predictedScorers, function ($a, $b) {
                                                        return $b['predictedNumOfGoals'] <=> $a['predictedNumOfGoals'];
                                                    });

                                                    foreach ($predictedScorers as $player) {
                                                    ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <h2 class="lead"><b><?php echo $player['firstName'] . ' ' . $player['lastName']; ?></b></h2>
                                                                    <p class="text-muted text-sm"><b>Team:</b> <?php echo $player['teamName']; ?></p>
                                                                    <p class="text-muted text-sm"><b>Predicted number of goals: </b> <?php echo $player['predictedNumOfGoals']; ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of predicted top scorers -->
                                </div>
                            </div>
                            <!-- Predicted results -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-outline shadow">
                                            <div class="card-body">
                                                <h1 class="card-title">Predicted results</h1>
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
                                                            <div class="card-body">
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
                                </div>
                                <div class="row">
                                    <!-- Predicted graph -->
                                    <div class="col-md-12 text-center">
                                        <div class="card card-outline shadow">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    Predicted points throughout the season
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div id="line-chart" style="height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of predicted graph -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of predicted results -->
                </div>
            </div>
            <!-- End of first row -->
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
<!-- FLOT CHARTS -->
<script src="js/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="js/flot/plugins/jquery.flot.resize.js"></script>


<script>
    <?php
    $query = "SELECT teamID, teamName FROM team WHERE leagueID = '$leagueID'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Query failed: " . mysqli_error($conn);
        exit;
    }

    $allPointsPerWeek = [];
    $teams = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $teamID = $row['teamID'];
        $teamName = $row['teamName'];
        $pointsPerWeek = calculatePointsPerWeek($teamID, $conn);
        $allPointsPerWeek[$teamID] = $pointsPerWeek;
        $teams[$teamID] = array(
            'name' => $teamName
        );
    }
    ?>

    $(function() {
        /*
        LINE CHART
         */
        var allPointsPerWeek = <?php echo json_encode($allPointsPerWeek); ?>;
        var teams = <?php echo json_encode($teams); ?>;

        var line_data = [];
        for (var teamID in allPointsPerWeek) {
            line_data.push(allPointsPerWeek[teamID].map(function(item) {
                return [item[0], item[1]];
            }));
        }

        $.plot('#line-chart', line_data, {
            grid: {
                hoverable: true,
                borderColor: '#f3f3f3',
                borderWidth: 1,
                tickColor: '#f3f3f3'
            },
            series: {
                shadowSize: 0,
                lines: {
                    show: true
                },
                points: {
                    show: true
                }
            },
            lines: {
                fill: false,
                color: ['#3c8dbc']
            },
            yaxis: {
                show: true,
                axisLabel: "Predicted points",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 3
            },
            xaxis: {
                show: true,
                axisLabel: "Game Week",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 10
            }
        })
        //Initialize tooltip on hover
        $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
            position: 'absolute',
            display: 'none',
            opacity: 0.8
        }).appendTo('body')
        $('#line-chart').bind('plothover', function(event, pos, item) {

            if (item) {
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2),
                    teamName = teams[Object.keys(teams)[item.seriesIndex]].name;

                $('#line-chart-tooltip').html(teamName + ' will have ' + y + ' points at week ' + x)
                    .css({
                        top: item.pageY + 5,
                        left: item.pageX + 5
                    })
                    .fadeIn(200)
            } else {
                $('#line-chart-tooltip').hide()
            }
        })
    })

    // Global variable to store the sorting order
    var sortOrder = [];

    // QuickSort function to sort the table
    function quickSort(arr, low, high, column) {
        if (low < high) {
            var pivot = partition(arr, low, high, column);
            quickSort(arr, low, pivot - 1, column);
            quickSort(arr, pivot + 1, high, column);
        }
    }

    // Partition function for QuickSort
    function partition(arr, low, high, column) {
        var pivotValue = arr[high][column];
        var i = low - 1;
        for (var j = low; j <= high - 1; j++) {
            if (arr[j][column] < pivotValue) {
                i++;
                swap(arr, i, j);
            }
        }
        swap(arr, i + 1, high);
        return i + 1;
    }

    // Swap function to swap two elements in the array
    function swap($arr, $a, $b) {
        $temp = $arr[$a];
        $arr[$a] = $arr[$b];
        $arr[$b] = $temp;
    }

    $(document).ready(function() {
        $("th").click(function() {
            var table = $(this).parents("table");
            var rows = table.find("tr:gt(0)").toArray().sort(comparer($(this).index()));
            this.asc = !this.asc;
            if (!this.asc) {
                rows = rows.reverse();
            }
            // Remove the sort icons from other columns
            table.find("th i").remove();
            // Set the sort icon for the clicked column
            if (this.asc) {
                $(this).append(' <i class="bi bi-caret-up-fill"></i>');
            } else {
                $(this).append(' <i class="bi bi-caret-down-fill"></i>');
            }
            // Reorder the rows based on the sorting order
            for (var i = 0; i < rows.length; i++) {
                table.append(rows[i]);
            }
        });
    });

    function comparer(index) {
        return function(a, b) {
            var valA = getCellValue(a, index),
                valB = getCellValue(b, index);
            return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
        }
    }

    function getCellValue(row, index) {
        return $(row).children("td").eq(index).text();
    }
</script>

</body>

</html>