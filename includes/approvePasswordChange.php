<?php
//Path: includes\approvePasswordChange.php

// This script is used to approve a password change request
$conn = require __DIR__ . '/dbhconfig.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the data from the form
    $userID = $_POST['userID'];

    // Update the password change request status to approved so that it is not displayed on the password change approval page
    $query = "UPDATE passwordchangerequest SET status = 'approved' WHERE userID = $userID";
    mysqli_query($conn, $query);

    // Redirect the user back to the password change approval page
    header("Location: ../approvePasswordChange.php");
    exit;
}
