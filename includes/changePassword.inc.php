<?php
// Path: includes\changePassword.inc.php

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the data from the form
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Retrieve the user ID from the session
    session_start();
    $userID = $_SESSION['userID'];

    // Check if the passwords match
    if ($password != $confirmPassword) {
        // Redirect back to the change password page with an error message
        header("Location: ../passwordReset.php?userID=$userID; error=passwordsDoNotMatch");
        exit;
    }

    //Check if password is empty

    if ($password = "" || $confirmPassword = "") {
        header("Location: ../passwordReset.php?userID=$userID; error=emptyinput");
        exit;
    }

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = require __DIR__ . '/dbhconfig.php';

    // Delete the password change request record for the specified user ID
    $sql = "DELETE FROM passwordchangerequest WHERE userID='$userID'";
    if (mysqli_query($conn, $sql)) {
        echo "Password change request record deleted successfully.\n";
    } else {
        echo "Error deleting password change request record: " . mysqli_error($conn) . "\n";
    }

    // Update the user's password
    $sql = "UPDATE user SET password='$hashedPassword' WHERE userID='$userID'";
    if (mysqli_query($conn, $sql)) {
        echo "Password updated successfully.\n";
    } else {
        echo "Error updating password: " . mysqli_error($conn) . "\n";
    }

    // Close the database connection
    mysqli_close($conn);

    // Redirect back to the login page
    header("Location: ../login.php?success=passwordChanged");
    exit;
} else {
    // If the form has not been submitted, redirect back to the login page
    header("Location: ../login.php");
    exit;
}
