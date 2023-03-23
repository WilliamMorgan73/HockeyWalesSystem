<?php

// Path: includes\datechecker.inc.php

// This file is used to check the date and time of the fixtures and move them to the tempresult table if the date and time has passed. This is done so that the user can enter the result of the fixture.

//Variables
$conn = require __DIR__ . '/dbhconfig.php';

// Get the current date and time
$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");

// Query to select the matchweek and dateTime from the fixture table where the date has passed and the time has also passed so that the fixture can be moved to the tempresult table
$query = "SELECT f.fixtureID, f.homeTeamID, f.awayTeamID, f.leagueID, f.matchweek, f.dateTime
          FROM fixture f
          JOIN gameweek g ON g.gameWeekID = f.matchweek
          WHERE g.gameDate < '$currentDate' OR (g.gameDate = '$currentDate' AND f.dateTime < '$currentTime')";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Array to store the fixtureIDs of fixtures being moved so that they can be deleted from the fixture table
$fixtureIDs = array();

// Loop through the results to get the fixtureID, homeTeamID, awayTeamID, leagueID, matchweek and dateTime so that they can be moved to the tempresult table
while ($row = mysqli_fetch_assoc($result)) {
    $fixtureID = $row['fixtureID'];
    $homeTeamID = $row['homeTeamID'];
    $awayTeamID = $row['awayTeamID'];
    $leagueID = $row['leagueID'];
    $matchweek = $row['matchweek'];

    // Store the fixtureID of the fixture being moved
    $fixtureIDs[] = $fixtureID;

    // Get all playerIDs of players who were available for the fixture so that their appearances count can be updated
    $query = "SELECT playerID
              FROM availability
              WHERE fixtureID = $fixtureID AND available = 1";
    $playerIDs = mysqli_query($conn, $query);

    if (!$playerIDs) {
        die("Query failed: " . mysqli_error($conn));
    }

    while ($player = mysqli_fetch_assoc($playerIDs)) {
        $playerID = $player['playerID'];

        // Check if the player already has an entry in the appearances table so that their appearances count can be updated,
        $query = "SELECT appearanceID
                  FROM appearance
                  WHERE playerID = $playerID";
        $appearance = mysqli_query($conn, $query);

        if (!$appearance) {
            die("Query failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($appearance) > 0) {
            // If the player already has an entry in the appearances table, update their appearances count
            $query = "UPDATE appearance
                      SET numOfAppearances = numOfAppearances + 1
                      WHERE playerID = $playerID";
            mysqli_query($conn, $query);
        } else {
            // If the player does not have an entry in the appearances table, create a new entry for them with appearances count 1
            $query = "INSERT INTO appearance (playerID, numOfAppearances)
VALUES ($playerID, 1)";
            mysqli_query($conn, $query);
        }
    }

    // Move the fixture to the tempresult table
    $query = "INSERT INTO tempresult (fixtureID, homeTeamID, awayTeamID, leagueID, matchweek)
          VALUES ($fixtureID, $homeTeamID, $awayTeamID, $leagueID, $matchweek)";
    mysqli_query($conn, $query);
}

// Delete the fixtures from the fixture table
if (!empty($fixtureIDs)) {
    $fixtureIDs = implode(", ", $fixtureIDs);
    $query = "DELETE FROM fixture
WHERE fixtureID IN ($fixtureIDs)";
    mysqli_query($conn, $query);
}
