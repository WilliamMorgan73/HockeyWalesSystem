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
    //Function call to check for empty fields
    if (emptyInputSignup($email, $password, $firstName, $lastName, $club, $DOB, $accountType) !== false) {
        header("Location: ../signup.php?error=emptyinput");
        exit();
    }

    //Function call to check if password and confirm password match

    if (passwordMatch($password, $confirmPassword) !== false) {
        header("Location: ../signup.php?error=passwordsdontmatch");
        exit();
    }

    //Function call to check if email already exists

    if (emailExists($conn, $email) !== false) {
        header("Location: ../signup.php?error=emailalreadyexists");
        exit();
    }

    //Check if account type is player or club admin

    if ($accountType == 'Player') {
        //Function call to create a player
        createPlayer($conn, $email, $password, $firstName, $lastName, $club, $DOB, $accountType);
        exit();
    } else if ($accountType == 'ClubAdmin') {
        //Function call to create a club admin
        createClubAdmin($conn, $email, $password, $firstName, $lastName, $club, $DOB, $accountType);
        exit();
    } else {
        header("Location: ../signup.php?error=accounttype");
        exit();
    }
}
