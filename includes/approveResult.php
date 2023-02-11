<?php
//Path: includes\approveResult.php

// This script is used to approve the result of a match
$conn = require __DIR__ . '/dbhconfig.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the data from the form
    $homeTeamID = $_POST['homeTeamID'];
    $awayTeamID = $_POST['awayTeamID'];

    // Move the data from tempresult to result table
    $query = "INSERT INTO result (homeTeamID, awayTeamID, homeTeamScore, awayTeamScore, leagueID, matchWeek)
              SELECT homeTeamID, awayTeamID, homeTeamScore, awayTeamScore, leagueID, matchWeek
              FROM tempresult
              WHERE homeTeamID = $homeTeamID AND awayTeamID = $awayTeamID";
    mysqli_query($conn, $query);

    // Delete the data from tempresult table
    $query = "DELETE FROM tempresult WHERE homeTeamID = $homeTeamID AND awayTeamID = $awayTeamID";
    mysqli_query($conn, $query);

    // Redirect the user back to the result approval page
    header('Location: resultApproval.php');
    exit;
}

