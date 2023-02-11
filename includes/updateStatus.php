<?php
$conn = require __DIR__ . '/dbhconfig.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

    if (isset($_POST["homeScore"]) && isset($_POST["awayScore"]) && isset($_POST["tempResultID"])) {
        $homeScore = $_POST["homeScore"];
        $awayScore = $_POST["awayScore"];
        $tempResultID = $_POST["tempResultID"];
        $query = "UPDATE tempresult SET homeTeamScore = '$homeScore', awayTeamScore = '$awayScore', status = 'sent' WHERE tempResultID = '$tempResultID'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
    }
