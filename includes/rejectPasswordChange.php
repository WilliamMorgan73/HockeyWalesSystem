<?php
// Path: includes\playerReject.inc.php

// Variables
$conn = require __DIR__ . '/dbhconfig.php';
$userID = $_POST['userID'];

// Delete data from passwordChangeRequest table
$sql = "DELETE FROM passwordchangerequest WHERE userID='$userID'";
if (mysqli_query($conn, $sql)) {
    echo "Data from passwordchangerequest table deleted successfully.\n";
} else {
    echo "Error deleting data from passwordchangerequest table: " . mysqli_error($conn) . "\n";
}

// Redirect user back to the password change approval page
header("Location: ../approvePasswordChange.php");
// Close connection
mysqli_close($conn);
