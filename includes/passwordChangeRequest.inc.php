<?php
//File: includes\passwordChangeRequest.inc.php

//This file is used to add a change password request to the database, which will be approved by the club admin

require('functions.inc.php');
$email = $_POST['email'];
$conn = require __DIR__ . '/dbhconfig.php';


//Get userID from email

$userID = getUserIDByEmail($email);

//Presence check | Check if email is empty
if ($email == "") {
    header("Location: ../forgotPassword.php?error=emptyinput");
    exit();
}

//Check if email exists in database

if (emailExists($email) == false) {
    header("Location: ../forgotPassword.php?error=emailnotfound");
    exit();
}

//Check if userID is already in password change request table and status is waiting

if (userWaitingInPasswordChangeRequest($userID) == true) {
    header("Location: ../forgotPassword.php?error=requestalreadyexists");
    exit();
}

//Check if userID is already in password change request table and status is approved

if(userApprovedInPasswordChangeRequest($userID) == true) {
    header("Location: ../passwordReset.php?userID=$userID");
    exit();
}

// Add request to database using the userID from the email

$sql = "INSERT INTO passwordChangeRequest (userID, status) VALUES (?, 'waiting')";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../forgotPassword.php?error=stmtfailed");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);

//Redirect to password reset request success page
header("Location: ../forgotPassword.php?error=requestsubmitted");
exit();
