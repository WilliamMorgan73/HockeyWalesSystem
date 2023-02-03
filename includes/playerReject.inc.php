<?php
// Path: includes\playerReject.inc.php

//Variables
$conn = require __DIR__ . '/dbhconfig.php';
$tempuserID = $_POST['tempUserID'];

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
