<?php
//File: includes\passwordChangeRequest.inc.php

//This file is used to add a change password request to the database, which will be approved by the club admin

require('functions.inc.php');
$email = $_POST['email'];
$conn = require __DIR__ . '/dbhconfig.php';

//Check if email is empty
if ($email == "") {
    header("Location: ../passwordReset.php?error=emptyinput");
    exit();
}

//Check if email exists in database

if (emailExists($email) == false) {
    header("Location: ../passwordReset.php?error=emailnotfound");
    exit();
}

//Check if email is already in password change request table

if (emailExistsInPasswordChangeRequest($email) == true) {
    header("Location: ../passwordReset.php?error=emailalreadyrequested");
    exit();
}
//Add request to database

$sql = "INSERT INTO password_change_request (email) VALUES (?)";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../passwordReset.php?error=stmtfailed");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $email);
