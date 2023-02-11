<?php

// Path: includes\datechecker.inc.php

// This file is used to check the date and time of the fixtures and move them to the tempresult table if the date and time has passed. This is done so that the user can enter the result of the fixture.

//Variables
$conn = require __DIR__ . '/dbhconfig.php';

// Get the current date and time
$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");

// Query to select the matchweek and dateTime from the fixture table where the date has passed and the time has also passed
$query = "SELECT f.fixtureID, f.homeTeamID, f.awayTeamID, f.leagueID, f.matchweek, f.dateTime
          FROM fixture f
          JOIN gameweek g ON g.gameWeekID = f.matchweek
          WHERE g.gameDate < '$currentDate' OR (g.gameDate = '$currentDate' AND f.dateTime < '$currentTime')";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Array to store the fixtureIDs of fixtures being moved
$fixtureIDs = array();

// Loop through the results
while ($row = mysqli_fetch_assoc($result)) {
    $fixtureID = $row['fixtureID'];
    $homeTeamID = $row['homeTeamID'];
    $awayTeamID = $row['awayTeamID'];
    $leagueID = $row['leagueID'];
    $matchweek = $row['matchweek'];

    // Store the fixtureID of the fixture being moved
    $fixtureIDs[] = $fixtureID;

    // Insert the data into the tempresult table with status "waiting"
    $query = "INSERT INTO tempresult (homeTeamID, awayTeamID, leagueID, matchweek, status)
            VALUES ($homeTeamID, $awayTeamID, $leagueID, $matchweek, 'waiting')";
    mysqli_query($conn, $query);
}

// Delete the rows from the fixture table according to the fixtureIDs of fixtures being moved
if (!empty($fixtureIDs)) {
    $fixtureIDs = implode(',', $fixtureIDs);
    $query = "DELETE FROM fixture
              WHERE fixtureID IN ($fixtureIDs)";
    mysqli_query($conn, $query);
}
