<?php
// Path: includes/clubadminApprove.inc.php

//Variables
$conn = require __DIR__ . '/dbhconfig.php';
$tempUserID = $_POST['tempUserID'];

// insert the data from the tempUser table into the user table so that it can safely be deleted from the tempclubadmin table
$sql = "INSERT INTO user (email, password, accountType)
SELECT email, password, accountType
FROM tempuser
WHERE tempUserID='$tempUserID'";

if (mysqli_query($conn, $sql)) {
    echo "Data from tempclubadmin table moved successfully to user table.\n";
} else {
    echo "Error moving data from tempclubadmin table to user table: " . mysqli_error($conn) . "\n";
}

// Get the new userID
$userID = mysqli_insert_id($conn);
//Insert data from tempclubadmin table into clubadmin table so that the data can safely be deleted from the tempclubadmin table
$sql = "INSERT INTO clubadmin (firstName, lastName, clubID, DOB, userID)
SELECT firstName, lastName, clubID, DOB, '$userID'
FROM tempclubadmin
WHERE tempUserID='$tempUserID'";

if (mysqli_query($conn, $sql)) {
    echo "Data from tempclubadmin table moved successfully to clubadmin table.\n";
} else {
    echo "Error moving data from tempclubadmin table to clubadmin table: " . mysqli_error($conn) . "\n";
}

//Delete data from tempclubadmin and tempuser so that they are not displayed on the clubadmin approval page and cannot be approved again

$sql = "DELETE FROM tempclubadmin WHERE tempUserID='$tempUserID'";
if (mysqli_query($conn, $sql)) {
    echo "Data from tempclubadmin table deleted successfully.\n";
} else {
    echo "Error deleting data from tempclubadmin table: " . mysqli_error($conn) . "\n";
}
$sql = "DELETE FROM tempuser WHERE tempUserID='$tempUserID'";
if (mysqli_query($conn, $sql)) {
    echo "Data from tempuser table deleted successfully.\n";
} else {
    echo "Error deleting data from tempuser table: " . mysqli_error($conn) . "\n";
}


// Redirect user back to the clubadmin approval page
header("Location: ../systemAdminDashboard.php?error=none");
// Close connection
mysqli_close($conn);
