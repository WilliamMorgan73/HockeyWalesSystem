<?php
// Path: includes\playerApprove.inc.php


//Variables
$conn = require __DIR__ . '/dbhconfig.php';
$tempuserID = $_POST['tempUserID'];
$teamID = $_POST['teamID'];

// Move data from tempuser to user
$sql = "INSERT INTO user (email, password, accountType)
SELECT email, password, accountType
FROM tempuser
WHERE tempuserID='$tempuserID'";

if (mysqli_query($conn, $sql)) {
    echo "Data from tempuser table moved successfully to user table.\n";
} else {
    echo "Error moving data from tempuser table: " . mysqli_error($conn) . "\n";
}

// Get the new userID
$userID = mysqli_insert_id($conn);

// Move data from tempplayer to player
$sql = "INSERT INTO player (firstName, lastName, teamID, DOB, userID)
SELECT firstName, lastName, $teamID, DOB, '$userID'
FROM tempplayer
WHERE tempuserID='$tempuserID'";

if (mysqli_query($conn, $sql)) {
    echo "Data from tempplayer table moved successfully to player table and linked with the user table using userID.\n";
} else {
    echo "Error moving data from tempplayer table: " . mysqli_error($conn) . "\n";
}

//Delete data from tempuser and tempplayer

$sql = "DELETE FROM tempuser WHERE tempuserID='$tempuserID'";
if (mysqli_query($conn, $sql)) {
    echo "Data from tempuser table deleted successfully.\n";
} else {
    echo "Error deleting data from tempuser table: " . mysqli_error($conn) . "\n";
}

$sql = "DELETE FROM tempplayer WHERE tempuserID='$tempuserID'";
if (mysqli_query($conn, $sql)) {
    echo "Data from tempplayer table deleted successfully.\n";
} else {
    echo "Error deleting data from tempplayer table: " . mysqli_error($conn) . "\n";
}

// Redirect user back to the player approval page
header("Location: ../playerApproval.php?error=none");
// Close connection
mysqli_close($conn);

?>
