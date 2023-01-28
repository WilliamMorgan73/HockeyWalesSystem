<?php
error_reporting(E_ALL);
// Path: includes\signup.inc.php

if (isset($_POST['submit'])) {
    //Collect data from form
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];
    $club = $_POST['club'];
    $accountType = $_POST['accountType'];
    $DOB = '2000-01-01';

    include 'dbh.inc.php';
    include 'functions.inc.php';
/*
    //Check if fields are empty

    if (emptyInputSignup($firstName, $lastName, $email, $password, $passwordConfirm, $club, $accountType, $DOB) !== false) {
        header("location: ../signup.php?error=emptyinput");
        exit();
    }

    //Check if email is valid

    if (invalidEmail($email) !== false) {
        header("location: ../signup.php?error=invalidemail");
        exit();
    }

    //Check if passwords match

    if (passwordMatch($password, $passwordConfirm) !== false) {
        header("location: ../signup.php?error=passwordsdontmatch");
        exit();
    }

    //Check if email is already taken

    if (emailExists($connection, $email) !== false) {
        header("location: ../signup.php?error=emailtaken");
        exit();
    }
*/
    //Check account type

    switch ($accountType) {
        case 'Club Admin':
            //Create club admin account
            createClubAdmin($connection, $firstName, $lastName, $email, $password, $club, $DOB);
            break;
        case 'Player':
            //Create player account
            createPlayer($connection, $firstName, $lastName, $email, $password, $club, $dob, $accountType);
            break;
        default:
            echo 'Error';
    }
}
