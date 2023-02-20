<?php
// Path: includes\addTeam.php
require('functions.inc.php');

//Variables

$teamName = $_POST['teamName'];
$clubID = $_POST['club'];
$leagueID = $_POST['league'];

$conn = require __DIR__ . '/dbhconfig.php';

if (isset($_POST['submitTeam'])) {
    //Function call to check for empty fields
    if (empty($teamName)|| empty($leagueID) || empty($clubID)) {
        header("Location: ../systemAdminDashboard.php?error=emptyinputTeam&message=" . urlencode("Please fill in all fields."));
        exit();
    }

    //Add team to database
    createTeam($teamName, $clubID, $leagueID);

    //Output success message
    header("Location: ../systemAdminDashboard.php?error=teamcreated&message=" . urlencode("Team added successfully."));
}
