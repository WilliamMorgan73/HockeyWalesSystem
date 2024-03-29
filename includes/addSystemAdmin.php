<?php
// Path: includes\addSystemAdmin.php

require('functions.inc.php');

//Variables

$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$accountType = 'System Admin';


$conn = require __DIR__ . '/dbhconfig.php';

if (isset($_POST['submitUser'])) {

    //Presenece check
    if (emptyInputSystemAdmin($email, $password) !== false) {
        header("Location: ../systemAdminDashboard.php?error=emptyinputSignup&message=" . urlencode("Please fill in all fields."));
        exit();
    }

    //Cross field check | Check if passwords match

    if (passwordMatch($password, $confirmPassword) !== false) {
        header("Location: ../systemAdminDashboard.php?error=passwordsdontmatch&message=" . urlencode("Passwords do not match."));
        exit();
    }

    //Function call to check if email already exists

    if (emailExists($email) !== false) {
        header("Location: ../systemAdminDashboard.php?error=emailalreadyexists&message=" . urlencode("Email already exists."));
        exit();
    }

    //Function call to check if email exists in temp users table

    if (emailExistsInTempUsers($email) !== false) {
        header("Location: ../systemAdminDashboard.php?error=emailalreadyexists&message=" . urlencode("Email already exists."));
        exit();
    }

    //Add system admin to user table

    createSystemAdmin($email, $password, $accountType);
    //Output success message
    header("Location: ../systemAdminDashboard.php?error=none&message=" . urlencode("System Admin added successfully."));

}
