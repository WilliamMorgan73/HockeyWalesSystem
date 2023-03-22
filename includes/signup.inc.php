<?php
// Path: includes\signup.inc.php

require('functions.inc.php');

//Variables

$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$club = $_POST['club'];
$DOB = date('Y-m-d', strtotime($_POST['DOB']));
$accountType = $_POST['accountType'];

$conn = require __DIR__ . '/dbhconfig.php';

if (isset($_POST['submit'])) {
    //Presence check | Function call to check for empty fields
    if (emptyInputSignup($email, $password, $firstName, $lastName, $club, $DOB, $accountType) !== false) {
        header("Location: ../signup.php?error=emptyinput&message=" . urlencode("Please fill in all the required fields."));
        exit();
    }


    //Cross field check | Function call to check if password and confirm password match

    if (passwordMatch($password, $confirmPassword) !== false) {
        header("Location: ../signup.php?error=passwordsdontmatch&message=" . urlencode("Passwords do not match."));
        exit();
    }

    //Function call to check if email already exists

    if (emailExists($email) !== false) {
        header("Location: ../signup.php?error=emailalreadyexists&message=" . urlencode("Email already exists."));
        exit();
    }

    //Function call to check if email exists in temp users table

    if (emailExistsInTempUsers($email) !== false) {
        header("Location: ../signup.php?error=pendingapproval&message=" . urlencode("Your account is still pending approval"));
        exit();
    }

    //Length check | Function call to check if fields are correct length

    if (checkLength($email, $password, $firstName, $lastName, $club) !== false) {
        header("Location: ../signup.php?error=fieldlength&message=" . urlencode("Fields are not the correct length."));
        exit();
    }

    //Check if account type is player or club admin

    if ($accountType == 'Player') {
        //Function call to create a player
        createPlayer($email, $password, $firstName, $lastName, $club, $DOB, $accountType);
        exit();
    } else if ($accountType == 'Club Admin') {
        //Function call to check if club already has a club admin
        clubHasAdmin($club);
        //Function call to create a club admin
        createClubAdmin($email, $password, $firstName, $lastName, $club, $DOB, $accountType);
        exit();
    } else {
        header("Location: ../signup.php?error=accounttype");
        exit();
    }
}
