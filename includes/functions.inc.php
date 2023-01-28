<?php

//Function to check if all fields are filled in

function emptyInputSignup($firstName, $lastName, $email, $password, $confirmPassword, $club, $accountType, $DOB)
{
    $result = false;
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword) || empty($club) || empty($accountType) || empty($DOB)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

//Function to check if email is valid

function invalidEmail($email)
{
    $result = false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

//Function to check if passwords match

function passwordMatch($password, $confirmPassword)
{
    $result = false;
    if ($password !== $confirmPassword) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

//Function to check if email is already taken

function emailExists($connection, $email)
{
    $sql = "SELECT * FROM users WHERE email = ?;";   //We use ? instead of $email because we are using prepared statements - A prepared statement is a feature used to execute the same (or similar) SQL statements repeatedly with high efficiency.
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
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

//Function to create the user if account type is club admin

function createClubAdmin($connection, $firstName, $lastName, $email, $password, $club, $accountType)
{
    // Insert email, password, and account type into user table
    $sql = "INSERT INTO user (email, password, accountType) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    // Hashing the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $email, $hashedPassword, $accountType);
    mysqli_stmt_execute($stmt);
    $user_id = mysqli_stmt_insert_id($stmt);  //get last inserted id
    mysqli_stmt_close($stmt);

    //Inserting rest of the details into clubAdmin table 
    $sql = "INSERT INTO clubAdmin (FirstName, LastName, Club, user_id) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "sssii", $firstName, $lastName, $club, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../clubAdminDashboard.php?error=none");
    exit();
}

//Function to create the user if account type is player

function createPlayer($connection, $firstName, $lastName, $email, $password, $club, $dob, $accountType)
{
    // Insert email, password, and account type into user table
    $sql = "INSERT INTO user (email, password, accountType) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    // Hashing the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $email, $hashedPassword, $accountType);
    mysqli_stmt_execute($stmt);
    $user_id = mysqli_stmt_insert_id($stmt);  //get last inserted id
    mysqli_stmt_close($stmt);

    //Inserting rest of the details into player table 
    $sql = "INSERT INTO player (firstName, lastName, teamID, DOB, userID) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ssidi", $firstName, $lastName, $club, $dob, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../playerDashboard.php?error=none");
    exit();
}
