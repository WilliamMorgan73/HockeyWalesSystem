<?php
//Path: includes\updateTeam.php

// This script is used to approve the result of a match
$conn = require __DIR__ . '/dbhconfig.php';

if (isset($_POST['change-team'])) {
    $useriD = $_POST['userID'];
    $selectedTeamID = $_POST['change-team-id'];

    // update the player's teamID in the database
    $sql = "UPDATE player SET teamID = ? WHERE userID = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../teammanagement.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $selectedTeamID, $useriD);
    mysqli_stmt_execute($stmt);

    header("Location: ../teammanagement.php?teamupdated"); // redirect to the team management page
    exit();
}
