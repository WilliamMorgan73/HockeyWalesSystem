<?php
//Path: includes\functions.inc.php

//Function to check for empty fields

function emptyInputSignup($email, $password, $firstName, $lastName, $club, $DOB, $accountType)
{
    $result = '';
    if (empty($email) || empty($password) || empty($firstName) || empty($lastName) || empty($club) || empty($DOB) || empty($accountType)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

//Email validation done in the HTML form

//Function to check if password and confirm password match

function passwordMatch($password, $confirmPassword)
{
    $result = '';
    if ($password !== $confirmPassword) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

//Function to check if email already exists

function emailExists($conn, $email)
{
    $sql = "SELECT * FROM user WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }
}

//Function to create a player

function createPlayer($conn, $email, $password, $firstName, $lastName, $club, $DOB, $accountType)
{
    $conn->begin_transaction();

    $sql = "INSERT INTO user (email, password, accountType) 
    VALUES (?, ?, ?)";

    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        $conn->rollback();
        exit;
    } else {
        //Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $email, $hashedPassword, $accountType);
        $stmt->execute();
    }

    $userID = $conn->insert_id;

    $sql = "INSERT INTO player (firstName, lastName, club, DOB, userID) 
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        $conn->rollback();
        exit;
    } else {
        $stmt->bind_param("ssssi", $firstName, $lastName, $club, $DOB, $userID);
        $stmt->execute();
        $conn->commit();
        header("Location: ../login.php?signup=success");
    }
}


//Function to create a club admin

function createClubAdmin($conn, $email, $password, $firstName, $lastName, $club, $DOB, $accountType)
{
    $conn->begin_transaction();

    $sql = "INSERT INTO user (email, password, accountType) 
    VALUES (?, ?, ?)";

    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        $conn->rollback();
        exit;
    } else {
        //Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $email, $hashedPassword, $accountType);
        $stmt->execute();
    }

    $userID = $conn->insert_id;

    $sql = "INSERT INTO clubAdmin (firstName, lastName, club, DOB, userID) 
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        $conn->rollback();
        exit;
    } else {
        $stmt->bind_param("ssssi", $firstName, $lastName, $club, $DOB, $userID);
        $stmt->execute();
        $conn->commit();
        header("Location: ../login.php?signup=success");
    }
}

/*

TODO:
Add length checks to all fields that need them, can pass maxium and minimum lengths as parameters
Maybe password strength check
Move details to temppalyerTable until approved by club admin
Add a check to see if clubs have a club admin already
Change to insert clubID into player table instead of club name by searching for clubID in club table using a join
Add validation email to club admin only which is sent to me to approve


*/
