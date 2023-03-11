<?php
$conn = require __DIR__ . '/dbhconfig.php';
require('functions.inc.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST["homeScore"]) && isset($_POST["awayScore"]) && isset($_POST["tempResultID"])) {  // Check if all required fields are set
    $homeScore = $_POST["homeScore"];
    $awayScore = $_POST["awayScore"];
    $tempResultID = $_POST["tempResultID"];
    $homeScorers = $_POST["homeScorers"];
    $awayScorers = $_POST["awayScorers"];
    $homeAssisters = $_POST["homeAssisters"];
    $awayAssisters = $_POST["awayAssisters"];
    
    $query = "UPDATE tempresult SET homeTeamScore = '$homeScore', awayTeamScore = '$awayScore', status = 'sent' WHERE tempResultID = '$tempResultID'"; // Update tempresult table
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    // Update player stats
    if (count($homeScorers) > 0) {
        foreach ($homeScorers as $homeScorer) {
            $goalsScored = count(array_keys($homeScorers, $homeScorer)); // Count number of times player is in array
            updateGoals($homeScorer, $goalsScored);
        }
    }
    
    if (count($awayScorers) > 0) {
        foreach ($awayScorers as $awayScorer) {
            $goalsScored = count(array_keys($awayScorers, $awayScorer)); // Count number of times player is in array
            updateGoals($awayScorer, $goalsScored);
        }
    }

    if (count($homeAssisters) > 0) {
        foreach ($homeAssisters as $homeAssister) {
            $assists = count(array_keys($homeAssisters, $homeAssister)); // Count number of times player is in array
            updateAssists($homeAssister, $assists);
        }
    }

    if (count($awayAssisters) > 0) {
        foreach ($awayAssisters as $awayAssister) {
            $assists = count(array_keys($awayAssisters, $awayAssister)); // Count number of times player is in array
            updateAssists($awayAssister, $assists);
        }
    }
}