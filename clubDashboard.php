<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

$clubID = $_POST['clubID'];
$topTeamLeague = getTopTeamLeague($clubID, $conn);
$query = "SELECT clubName FROM club WHERE clubID = $clubID";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$clubName = $row['clubName'];
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $clubName ?>'s dashboard</title>

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
                        <a style="cursor:pointer" class="nav-link active">
                            <i class="nav-icon bi bi-house-fill"></i>
                            <p>Home</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link">
                            <form action="players.php" method="post">
                                <input type="hidden" name="clubID" value="<?php echo $clubID; ?>">
                                <i class="far bi bi-people-fill nav-icon"></i>
                                <button type="submit" style="background: transparent; border: none;">
                                    <p>Players</p>
                                </button>
                            </form>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link">
                            <form action="clubFixtureResults.php" method="post">
                                <input type="hidden" name="clubID" value="<?php echo $clubID; ?>">
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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb">
                    <div class="col-sm">
                        <h1><?php echo $clubName ?> HC</h1>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="card card-solid">
                <div class="card-body-0">
                    <div class="container-fluid">
                        <!-- First row - League table, top scorers, and results -->
                        <div class="row" style="padding-top:1%;">
                            <!-- League table -->
                            <div class="col-md-6">
                                <div class="card shadow" style="width: 100%">
                                    <div class="card-body p-0">
                                        <table class=" table table-striped" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th onclick="sortTable(0)">Team</th>
                                                    <th onclick="sortTable(1)">W</th>
                                                    <th onclick="sortTable(2)">D</th>
                                                    <th onclick="sortTable(3)">L</th>
                                                    <th onclick="sortTable(4)">GD</th>
                                                    <th onclick="sortTable(5)">PTS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Assuming the database connection is established and stored in the variable $conn
                                                $sql = "SELECT teamID, teamName FROM team WHERE leagueID = $topTeamLeague";
                                                $result = mysqli_query($conn, $sql);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $teams = [];
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $teams[] = $row;
                                                    }

                                                    $points = getTeamPoints($teams, $conn);
                                                    // Display the teams in descending order of points
                                                    foreach ($points as $team) {
                                                        echo "<tr>
              <td>" . $team["teamName"] . "</td>
              <td>" . getTeamWins($team["teamID"], $conn) . "</td>
              <td>" . getTeamDraws($team["teamID"], $conn) . "</td>
              <td>" . getTeamLosses($team["teamID"], $conn) . "</td>
              <td>" . getTeamGoalDifference($team["teamID"], $conn) . "</td>
              <td>" . $team["points"] . "</td>
            </tr>";
                                                    }
                                                } else {
                                                    echo "0 results";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- End of league table -->
                            <!-- Results -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-outline shadow">
                                            <div class="card-body text-center">
                                                <h1 class="card-title">Results</h1>
                                                <br />
                                                <div class="col-md-12">
                                                    <?php

                                                    $query = "SELECT homeTeamID, homeTeamScore, awayTeamID, awayTeamScore FROM result WHERE (homeTeamID = '$clubID' OR awayTeamID = '$clubID')";
                                                    $resultdetailresult = mysqli_query($conn, $query);

                                                    if (mysqli_num_rows($resultdetailresult) > 0) {
                                                        while ($row = mysqli_fetch_array($resultdetailresult)) {
                                                            $homeTeamID = $row['homeTeamID'];
                                                            $awayTeamID = $row['awayTeamID'];
                                                            $homeTeamScore = $row['homeTeamScore'];
                                                            $awayTeamScore = $row['awayTeamScore'];

                                                            $query = "SELECT teamName FROM team WHERE teamID = '$homeTeamID'";
                                                            $result2 = mysqli_query($conn, $query);
                                                            $row2 = mysqli_fetch_array($result2);
                                                            $homeTeamName = $row2['teamName'];

                                                            $query = "SELECT teamName FROM team WHERE teamID = '$awayTeamID'";
                                                            $result3 = mysqli_query($conn, $query);
                                                            $row3 = mysqli_fetch_array($result3);
                                                            $awayTeamName = $row3['teamName'];
                                                    ?>
                                                            <div class="card-body">
                                                                <h2 class="lead"><b><?php echo "$homeTeamName - $homeTeamScore   -   $awayTeamScore $awayTeamName" . "<br>"; ?>
                                                            </div>
                                                    <?php
                                                        }
                                                    } else {
                                                        echo "No fixtures found for club with ID '$clubID'";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Fixtures -->
                                    <div class="col-md-12 text-center">
                                        <div class="card card-outline shadow">
                                            <div class="card-body">
                                                <h1 class="card-title">Fixtures</h1>
                                                <br />
                                                <div class="col-md-12">
                                                    <?php
                                                    $query = "SELECT teamID FROM team WHERE clubID = '$clubID'";
                                                    $result = mysqli_query($conn, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        $teamIDs = array();
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            array_push($teamIDs, $row['teamID']);
                                                        }

                                                        $query = "SELECT MIN(matchWeek) AS minWeek FROM fixture WHERE homeTeamID IN ('" . implode("','", $teamIDs) . "') OR awayTeamID IN ('" . implode("','", $teamIDs) . "')";
                                                        $result = mysqli_query($conn, $query);

                                                        if (mysqli_num_rows($result) > 0) {
                                                            $row = mysqli_fetch_assoc($result);
                                                            $minWeek = $row['minWeek'];

                                                            $query = "SELECT homeTeamID, awayTeamID FROM fixture WHERE (homeTeamID IN ('" . implode("','", $teamIDs) . "') OR awayTeamID IN ('" . implode("','", $teamIDs) . "')) AND matchWeek = '$minWeek'";
                                                            $resultFixtures = mysqli_query($conn, $query);

                                                            if (mysqli_num_rows($resultFixtures) > 0) {
                                                                while ($row = mysqli_fetch_array($resultFixtures)) {
                                                                    $homeTeamID = $row['homeTeamID'];
                                                                    $awayTeamID = $row['awayTeamID'];

                                                                    $query = "SELECT teamName FROM team WHERE teamID = '$homeTeamID'";
                                                                    $result2 = mysqli_query($conn, $query);
                                                                    $row2 = mysqli_fetch_array($result2);
                                                                    $homeTeamName = $row2['teamName'];

                                                                    $query = "SELECT teamName FROM team WHERE teamID = '$awayTeamID'";
                                                                    $result3 = mysqli_query($conn, $query);
                                                                    $row3 = mysqli_fetch_array($result3);
                                                                    $awayTeamName = $row3['teamName'];
                                                    ?>
                                                                    <div class="card-body">
                                                                        <h2 class="lead"><b><?php echo "$homeTeamName - $awayTeamName" . "<br>"; ?>
                                                                    </div>
                                                    <?php
                                                                }
                                                            } else {
                                                                echo "No fixtures with the lowest match week number found for the club";
                                                            }
                                                        } else {
                                                            echo "No match week numbers found in the fixtures table for the club";
                                                        }
                                                    } else {
                                                        echo "No match week numbers found in the fixtures table";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of fixtures -->
                                </div>

                                <div class="row">
                                    <!-- Top scorers -->
                                    <div class="col-md-12 text-center">
                                        <div class="card card-outline shadow">
                                            <div class="card-body">
                                                <h1 class="card-title">Top scorers</h1>
                                                <br />
                                                <?php
                                                $query = "SELECT teamID FROM team WHERE clubID = '$clubID'";
                                                $result = mysqli_query($conn, $query);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $teamIDs = [];
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        $teamIDs[] = $row['teamID'];
                                                    }
                                                    $teamIDs = implode(',', $teamIDs);
                                                    $query = "SELECT * FROM player WHERE teamID IN ($teamIDs)";
                                                    $playersResult = mysqli_query($conn, $query);
                                                    if (mysqli_num_rows($playersResult) > 0) {
                                                        $players = [];
                                                        while ($row = mysqli_fetch_array($playersResult)) {
                                                            $playerID = $row['playerID'];
                                                            $firstName = $row['firstName'];
                                                            $lastName = $row['lastName'];
                                                            $teamID = $row['teamID'];

                                                            $query = "SELECT teamName FROM team WHERE teamID = '$teamID'";
                                                            $teamNameResult = mysqli_query($conn, $query);
                                                            $row = mysqli_fetch_array($teamNameResult);
                                                            $teamName = $row['teamName'];

                                                            $query = "SELECT numOfGoals FROM goal WHERE playerID = '$playerID' ORDER BY numOfGoals DESC LIMIT 6";
                                                            $numOfGoalsResult = mysqli_query($conn, $query);
                                                            if (mysqli_num_rows($numOfGoalsResult) > 0) {
                                                                while ($row = mysqli_fetch_array($numOfGoalsResult)) {
                                                                    $numOfGoals = $row['numOfGoals'];
                                                                    $players[] = [
                                                                        'firstName' => $firstName,
                                                                        'lastName' => $lastName,
                                                                        'teamName' => $teamName,
                                                                        'numOfGoals' => $numOfGoals,
                                                                    ];
                                                                }
                                                            }
                                                        }
                                                    }
                                                    usort($players, function ($a, $b) {
                                                        return $b['numOfGoals'] - $a['numOfGoals'];
                                                    });
                                                    foreach ($players as $player) {
                                                ?>
                                                        <div class="card-body col-md-12" style="padding-bottom:0px;">
                                                            <h3 class="lead"><b><?php echo $player['firstName'];
                                                                                echo " ";
                                                                                echo $player['lastName']; ?></b></h3>
                                                            <p class="text-muted text-sm"><b>Number of goals:</b> <?php echo $player['numOfGoals']; ?></p>
                                                            <p class="text-muted text-sm"><b>Club:</b> <?php echo $player['teamName']; ?></p>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of top scorers -->

                                </div>

                            </div>

                            <!-- End of results -->
                        </div>
                        <!-- End of first row -->

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

<script>
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


<!-- Add error messages when there are no games played or no scorers etc -->

</html>