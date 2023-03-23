<?php
// Path: includes/clubAdminReject.php

// Connect to database
$conn = require __DIR__ . '/dbhconfig.php';

// Get tempUserID from POST request
$tempUserID = $_POST['tempUserID'];

// Delete data from tempUser table so that it is not displayed on the club admin approval page and cannot be approved again
$sql = "DELETE FROM tempUser WHERE tempUserID = '$tempUserID'";
if (mysqli_query($conn, $sql)) {
    echo "Data from tempUser table deleted successfully.\n";
} else {
    echo "Error deleting data from tempUser table: " . mysqli_error($conn) . "\n";
}
// Delete data from tempClubAdmin table so that it is not displayed on the club admin approval page and cannot be approved again
$sql = "DELETE FROM tempClubAdmin WHERE tempUserID = '$tempUserID'";
if (mysqli_query($conn, $sql)) {
    echo "Data from tempClubAdmin table deleted successfully.\n";
} else {
    echo "Error deleting data from tempClubAdmin table: " . mysqli_error($conn) . "\n";
}

// Redirect user back to the club admin approval page
header("Location: ../systemAdminDashboard.php?error=none");

// Close connection
mysqli_close($conn);
?>
