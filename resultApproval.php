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
$leagueID = getLeagueID($userID, $conn);
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Result approval</title>

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
                            <a href="fixtureAvailability.php" class="nav-link">
                                <i class="far bi bi-calendar-date-fill nav-icon"></i>
                                <p>Fixture availability</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="far bi bi-bar-chart-fill nav-icon"></i>
                                <p>Result apporval</p>
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
                            <h1>Result Approval</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="card card-solid text-center">
                    <div class="card-header pb-0">
                        <div class="text-left">
                            <h3>Approve results</h3> <!-- Make this say team name instead -->
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <!-- Header -->
                        <div class="row border-bottom border-subtle" style="margin-bottom:1%;">
                            <div class="col-md-3">
                                <h5>Home team</h5>
                            </div>
                            <div class="col-md-3">
                                <h5>Away team</h5>
                            </div>
                            <div class="col-md-3">
                                <h5>Scoreline</h5>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Approve</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Challenge</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of header -->
                        <?php

                        $query = "SELECT hometeam.teamName AS hometeam, awayteam.teamName AS awayteam, homeTeamScore, awayTeamScore, tempresult.homeTeamID AS homeTeamID, tempresult.awayTeamID AS awayTeamID
                        FROM tempresult
                        INNER JOIN team hometeam ON tempresult.homeTeamID = hometeam.teamID
                        INNER JOIN team awayteam ON tempresult.awayTeamID = awayteam.teamID
                        WHERE awayteam.clubID = $clubID AND tempresult.status = 'sent'";

                        $result = mysqli_query($conn, $query);

                        // Loop through the data and display it
                        while ($row = mysqli_fetch_assoc($result)) {
                            $hometeam = $row['hometeam'];
                            $awayteam = $row['awayteam'];
                            $scoreline = $row['homeTeamScore'] . "-" . $row['awayTeamScore'];
                        ?>
                            <div class="row" style="margin-bottom:1%;">
                                <div class="col-md-3">
                                    <h5><?php echo $hometeam; ?></h5>
                                </div>
                                <div class="col-md-3">
                                    <h5><?php echo $awayteam; ?></h5>
                                </div>
                                <div class="col-md-3">
                                    <h5><?php echo $scoreline; ?></h5>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form action="includes/approveResult.php" method="post">
                                                <input type="hidden" name="homeTeamID" value="<?php echo $row['homeTeamID']; ?>">
                                                <input type="hidden" name="awayTeamID" value="<?php echo $row['awayTeamID']; ?>">
                                                <input type="submit" class="btn btn-block btn-success" value="Approve">
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <form action="includes/challengeResult.php" method="post">
                                                <input type="hidden" name="homeTeamID" value="<?php echo $row['homeTeamID']; ?>">
                                                <input type="hidden" name="awayTeamID" value="<?php echo $row['awayTeamID']; ?>">
                                                <input type="submit" class="btn btn-block btn-danger" value="Challenge">
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

                <!-- Enter result card -->

                <div class="card card-solid text-center">
                    <div class="card-header pb-0">
                        <div class="text-left">
                            <h3>Enter results</h3> <!-- Make this say team name instead -->
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <!-- Header -->
                        <div class="row border-bottom border-subtle" style="margin-bottom:1%;">
                            <div class="col-md-1">
                                <h5>Home team</h5>
                            </div>
                            <div class="col-md-1">
                                <h5>Away team</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>Scoreline</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Home scorers</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Away scorers</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Home assisters</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Away assisters</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <h5>Submit</h5>
                            </div>
                        </div>
                        <!-- End of header -->
                        <!-- PHP code to fetch data from the database -->
                        <?php
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }
                        $query = "SELECT tr.tempResultID, tr.homeTeamID, tr.awayTeamID, t1.teamName AS homeTeamName, t2.teamName AS awayTeamName
          FROM tempresult tr
          JOIN team t1 ON t1.teamID = tr.homeTeamID
          JOIN team t2 ON t2.teamID = tr.awayTeamID
          WHERE (tr.homeTeamID IN (SELECT teamID FROM team WHERE clubID = $clubID))
          AND (tr.status = 'waiting' OR tr.status = 'challenged')";
                        $result = mysqli_query($conn, $query);
                        if (!$result) {
                            die("Query failed: " . mysqli_error($conn));
                        }
                        // Loop through the results
                        while ($row = mysqli_fetch_assoc($result)) {
                            $tempResultID = $row['tempResultID'];
                            $homeTeamName = $row['homeTeamName'];
                            $awayTeamName = $row['awayTeamName'];
                            $homeTeamID = $row['homeTeamID'];
                            $awayTeamID = $row['awayTeamID'];
                        ?>
                            <!-- HTML code to display the data -->
                            <div class="row" style="margin-bottom:1%;">
                                <div class="col-md-1">
                                    <h5><?php echo $homeTeamName; ?></h5>
                                </div>
                                <div class="col-md-1">
                                    <h5><?php echo $awayTeamName; ?></h5>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center" placeholder="Home" id="homeScore">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center" placeholder="Away" id="awayScore">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="select2" multiple="multiple" data-placeholder="Home scorers" style="width: 100%;" id="homeScorers">
                                                <?php
                                                $query = "SELECT * FROM player WHERE teamID = '$homeTeamID'";
                                                $result = mysqli_query($conn, $query);

                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<option value='" . $row['playerID'] . "'>" . $row['firstName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="select2" multiple="multiple" data-placeholder="Away scorers" style="width: 100%;" id="awayScorers">
                                                <?php
                                                $query = "SELECT * FROM player WHERE teamID = '$awayTeamID'";
                                                $result = mysqli_query($conn, $query);

                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<option value='" . $row['playerID'] . "'>" . $row['firstName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="select2" multiple="multiple" data-placeholder="Home assisters" style="width: 100%;" id="homeAssisters">
                                                <?php
                                                $query = "SELECT * FROM player WHERE teamID = '$homeTeamID'";
                                                $result = mysqli_query($conn, $query);

                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<option value='" . $row['playerID'] . "'>" . $row['firstName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="select2" multiple="multiple" data-placeholder="Away assisters" style="width: 100%;" id="awayAssisters">
                                                <?php
                                                $query = "SELECT * FROM player WHERE teamID = '$awayTeamID'";
                                                $result = mysqli_query($conn, $query);

                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<option value='" . $row['playerID'] . "'>" . $row['firstName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" id="Submit" class="btn btn-block btn-success">Submit</button>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h4>Home Scorers</h4>
                                            <div id="homeScorersRows"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <h4>Away Scorers</h4>
                                            <div id="awayScorersRows"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <h4>Home Assisters</h4>
                                            <div id="homeAssistersRows"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <h4>Away Assisters</h4>
                                            <div id="awayAssistersRows"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
        </div>
        <!-- /.card -->
        </section>
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
    <!-- Select2 -->
    <script src="js/select2/select2.full.min.js"></script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        })
        $(document).ready(function() {
            // Handle changes in home assisters select
            $('#homeAssisters').on('change', function() {
                var selectedOptions = $(this).val();
                // Remove any existing rows for home assisters
                $('#homeAssistersRows').empty();
                // Create new rows for each selected option
                selectedOptions.forEach(function(option) {
                    var optionText = $("#homeAssisters option[value='" + option + "']").text();
                    var newRow = '<div class="form-group"><label>' + optionText + '(Assists)</label><select class="form-control"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div>';
                    $('#homeAssistersRows').append(newRow);
                });
            });

            // Handle changes in away assisters select
            $('#awayAssisters').on('change', function() {
                var selectedOptions = $(this).val();
                // Remove any existing rows for away assisters
                $('#awayAssistersRows').empty();
                // Create new rows for each selected option
                selectedOptions.forEach(function(option) {
                    var optionText = $("#awayAssisters option[value='" + option + "']").text();
                    var newRow = '<div class="form-group"><label>' + optionText + '(Assists)</label><select class="form-control"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div>';
                    $('#awayAssistersRows').append(newRow);
                });
            });
            // Handle changes in home scorers select
            $('#homeScorers').on('change', function() {
                var selectedOptions = $(this).val();
                // Remove any existing rows for home scorers
                $('#homeScorersRows').empty();
                // Create new rows for each selected option
                selectedOptions.forEach(function(option) {
                    var optionText = $("#homeScorers option[value='" + option + "']").text();
                    var newRow = '<div class="form-group"><label>' + optionText + ' (Goals)</label><select class="form-control"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div>';
                    $('#homeScorersRows').append(newRow);
                });
            });
            // Handle changes in away scorers select
            $('#awayScorers').on('change', function() {
                var selectedOptions = $(this).select2('data');
                // Remove any existing rows for away scorers
                $('#awayScorersRows').empty();
                // Create new rows for each selected option
                selectedOptions.forEach(function(option) {
                    var newRow = '<div class="form-group"><label>' + option.text + ' (Goals)</label><select class="form-control"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div>';
                    $('#awayScorersRows').append(newRow);
                });
            });
            $("#Submit").click(function() {
                // Get the input values for home and away score
                var homeScore = $(this).parent().siblings().find("input:first").val();
                var awayScore = $(this).parent().siblings().find("input:last").val();
                var tempResultID = "<?php echo $tempResultID; ?>";
                // Get the selected options for home and away scorers and assisters
                var awayScorers = $(this).parent().siblings().find("#awayScorers").val();
                var homeScorers = $(this).parent().siblings().find("#homeScorers").val();
                var homeAssisters = $(this).parent().siblings().find("#homeAssisters").val();
                var awayAssisters = $(this).parent().siblings().find("#awayAssisters").val();
                // Create arrays to store the number of goals and assists for each player
                var homeScorerGoals = [];
                var awayScorerGoals = [];
                var homeAssisterGoals = [];
                var awayAssisterGoals = [];
                // Get the number of goals for each home scorer
                $('#homeScorersRows .form-group select').each(function() {
                    homeScorerGoals.push($(this).val());
                });
                // Get the number of goals for each away scorer
                $('#awayScorersRows .form-group select').each(function() {
                    awayScorerGoals.push($(this).val());
                });
                // Get the number of assists for each home assister
                $('#homeAssistersRows .form-group select').each(function() {
                    homeAssisterGoals.push($(this).val());
                });
                // Get the number of assists for each away assister
                $('#awayAssistersRows .form-group select').each(function() {
                    awayAssisterGoals.push($(this).val());
                });
                $.ajax({
                    type: "POST",
                    url: 'includes/updateStatus.php',
                    data: {
                        homeScore: homeScore,
                        awayScore: awayScore,
                        tempResultID: tempResultID,
                        awayScorers: awayScorers,
                        homeScorers: homeScorers,
                        awayAssisters: awayAssisters,
                        homeAssisters: homeAssisters,
                        homeScorerGoals: homeScorerGoals,
                        awayScorerGoals: awayScorerGoals,
                        homeAssisterGoals: homeAssisterGoals,
                        awayAssisterGoals: awayAssisterGoals
                    },
                    success: function(data) {
                        alert("Scores and assists updated successfully.");
                        // Reload the page
                        location.reload();
                    }
                });
            });

        });
    </script>
</body>

</html>