<?php
//Path: includes\functions.inc.php

$conn = require __DIR__ . '/dbhconfig.php';

//Presence check | Function to check for empty fields in the signup form
function emptyInputSignup($email, $password, $firstName, $lastName, $club, $DOB, $accountType)
{
    $result = '';
    // Check if any of the fields are empty
    if (empty($email) || empty($password) || empty($firstName) || empty($lastName) || empty($club) || empty($DOB) || empty($accountType)) {
        $result = true; // If any field is empty, set result to true
    } else {
        $result = false; // Otherwise, set result to false
    }
    return $result; // Return the result
}

//Presence check | Function to check for empty fields in the system admin form
function emptyInputSystemAdmin($email, $password)
{
    $result = '';
    // Check if email or password is empty
    if (empty($email) || empty($password)) {
        $result = true; // If either field is empty, set result to true
    } else {
        $result = false; // Otherwise, set result to false
    }
    return $result; // Return the result
}

//Cross field check | Function to check if password and confirm password match
function passwordMatch($password, $confirmPassword)
{
    $result = '';
    // Check if password and confirm password match
    if ($password !== $confirmPassword) {
        $result = true; // If passwords don't match, set result to true
    } else {
        $result = false; // Otherwise, set result to false
    }
    return $result; // Return the result
}

// Function to check if email already exists in the users table
function emailExists($email)
{
    global $conn; // Access the global database connection variable
    $sql = "SELECT * FROM user WHERE email = ?;"; // Prepare a query to check if the email already exists in the users table
    $stmt = mysqli_stmt_init($conn); // Initialize a new mysqli_stmt object
    if (!mysqli_stmt_prepare($stmt, $sql)) { // If the statement preparation fails, redirect to the signup page with an error message
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email); // Bind the email parameter to the statement
    mysqli_stmt_execute($stmt); // Execute the statement
    $resultData = mysqli_stmt_get_result($stmt); // Get the result of the statement

    if ($row = mysqli_fetch_assoc($resultData)) { // If a row is returned, the email already exists in the users table
        return $row; // Return the row
    } else {
        $result = false; // Otherwise, set result to false
        return $result; // Return the result
    }
}

//Function to check if email exists in temp users table
function emailExistsInTempUsers($email)
{
    global $conn;
    $sql = "SELECT * FROM tempuser WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        // Error handling for failed statement preparation
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

//Length check | Function to check if fields are correct length
function checkLength($email, $password, $firstName, $lastName, $club)
{
    $result = '';
    if (strlen($email) > 50 || strlen($password) > 50 || strlen($firstName) > 50 || strlen($lastName) > 50 || strlen($club) > 50) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

//Function to check if club has already has a club admin
function clubHasAdmin($clubID)
{
    global $conn;
    echo $clubID;
    // Prepare the query
    $stmt = $conn->prepare('SELECT * FROM clubAdmin WHERE clubID = ?');
    $stmt->bind_param('i', $clubID);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a result is found, output an error
    if ($result->num_rows > 0) {
        header("Location: ../signup.php?error=AlreadyHasAdmin&message=" . urlencode("The club you are trying to join already have a club admin"));
    }
}

//Presence check | Function to check for empty fields in login form
function emptyInputLogin($email, $password)
{
    $result = '';
    if (empty($email) || empty($password)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

//Function to get club ID
function getClubID($club)
{
    global $conn;
    $sql = "SELECT clubID FROM club WHERE clubName = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        // Error handling for failed statement preparation
        echo "SQL statement failed: " . $conn->error;
        exit;
    } else {
        $stmt->bind_param("s", $club);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            return $row['clubID'];
        } else {
            return false;
        }
    }
}

//Function to create a player
function createPlayer($email, $password, $firstName, $lastName, $club, $DOB, $accountType)
{
    global $conn; // Access the global database connection object
    $conn->begin_transaction(); // Start a database transaction

    // Define SQL statement to insert values into the temporary user table
    $sql = "INSERT INTO tempUser (email, password, accountType) 
    VALUES (?, ?, ?)";

    $stmt = $conn->stmt_init(); // Initialize a prepared statement
    // Prepare the SQL statement with the prepared statement object
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        $conn->rollback(); // Roll back the transaction if there is an error
        exit;
    } else {
        // Hash the password using the default hashing algorithm and bind the parameters to the statement object
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $email, $hashedPassword, $accountType);
        $stmt->execute(); // Execute the statement
    }

    // Get the user ID of the newly created user account
    $userID = $conn->insert_id;

    // Define SQL statement to insert values into the temporary player table
    $sql = "INSERT INTO tempPlayer (firstName, lastName, clubID, DOB, tempUserID) 
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->stmt_init(); // Initialize a prepared statement
    // Prepare the SQL statement with the prepared statement object
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        $conn->rollback(); // Roll back the transaction if there is an error
        exit;
    } else {
        // Bind the parameters to the statement object
        $stmt->bind_param("ssssi", $firstName, $lastName, $club, $DOB, $userID);
        $stmt->execute(); // Execute the statement
        $conn->commit(); // Commit the transaction if both SQL statements are executed successfully
        header("Location: ../signup.php?error=playeraccountcreated&message=" . urlencode("Account created, waiting for club admin to accept")); // Redirect to the signup page with a success message
    }
}

//Function to create a club admin
function createClubAdmin($email, $password, $firstName, $lastName, $clubID, $DOB, $accountType)
{
    global $conn; // access to global variable $conn, which is the database connection object
    $conn->begin_transaction(); // starts a transaction

    $sql = "INSERT INTO tempUser (email, password, accountType) 
VALUES (?, ?, ?)"; // SQL statement to insert user data into the tempUser table

    $stmt = $conn->stmt_init(); // initializes a prepared statement
    if (!$stmt->prepare($sql)) { // check if the statement is valid
        echo "SQL statement failed: " . $conn->error; // output error message if failed
        $conn->rollback(); // roll back the transaction if failed
        exit; // exit the function
    } else {
        //Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // hash the user's password
        $stmt->bind_param("sss", $email, $hashedPassword, $accountType); // bind parameters to the statement
        $stmt->execute(); // execute the statement
    }

    $tempuserID = $conn->insert_id; // retrieve the ID generated for an AUTO_INCREMENT column

    $sql = "INSERT INTO tempclubadmin (firstName, lastName, clubID, DOB, tempUserID) 
VALUES (?, ?, ?, ?, ?)"; // SQL statement to insert club admin data into the tempclubadmin table

    $stmt = $conn->stmt_init(); // initializes a prepared statement
    if (!$stmt->prepare($sql)) { // check if the statement is valid
        echo "SQL statement failed: " . $conn->error; // output error message if failed
        $conn->rollback(); // roll back the transaction if failed
        exit; // exit the function
    } else {
        $stmt->bind_param("ssssi", $firstName, $lastName, $clubID, $DOB, $tempuserID); // bind parameters to the statement
        $stmt->execute(); // execute the statement

        if ($stmt->error) { // check if there is an error
            // If the second query fails, roll back both queries and output an error message
            echo "Second SQL statement failed: " . $stmt->error; // output error message
            $conn->rollback(); // roll back the transaction
            exit; // exit the function
        } else {
            // If both queries succeed, commit the transaction and redirect the user
            $conn->commit(); // commit the transaction
            header("Location: ../signup.php?error=clubadminaccountcreated&message=" . urlencode("Account created, waiting for a system admin to accept")); // redirect to the signup page with success message
        }
    }
}

//Function to create system admin
function createSystemAdmin($email, $password, $accountType)
{
    global $conn; // access to global variable $conn, which is the database connection object
    $conn->begin_transaction(); // starts a transaction

    $sql = "INSERT INTO user (email, password, accountType) 
    VALUES (?, ?, ?)";

    $stmt = $conn->stmt_init(); // initialize a prepared statement
    if (!$stmt->prepare($sql)) { // check if the SQL statement failed to prepare
        echo "SQL statement failed: " . $conn->error; // output an error message
        $conn->rollback(); // roll back the transaction
        exit; // stop script execution
    } else {
        //Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // hash the user's password using bcrypt
        $stmt->bind_param("sss", $email, $hashedPassword, $accountType); // bind the parameters to the prepared statement
        $stmt->execute(); // execute the prepared statement

        if ($stmt->error) { // check if the prepared statement resulted in an error
            // If the query fails, roll back the transaction and output an error message
            echo "SQL statement failed: " . $stmt->error; // output an error message
            $conn->rollback(); // roll back the transaction
            exit; // stop script execution
        } else {
            // If the query succeeds, commit the transaction and redirect the user
            $conn->commit(); // commit the transaction
            header("Location: ../systemAdminDashboard.php?error=systemadminaccountcreated&message=" . urlencode("Account created")); // redirect the user to the system admin dashboard page
        }
    }
}

//Function to create a club

function createClub($clubName)
{
    global $conn; // Access the global variable $conn which is the database connection

    // SQL statement to insert club name into club table
    $sql = "INSERT INTO club (clubName) VALUES (?)";

    // Prepare the SQL statement to be executed by the database
    $stmt = $conn->prepare($sql);

    // Check if the statement failed to prepare and handle error if true
    if (!$stmt) {
        // Handle error here
        echo "Error: " . $conn->error;
        exit();
    }

    // Bind the parameter $clubName to the prepared statement
    $stmt->bind_param("s", $clubName);

    // Execute the prepared statement
    $stmt->execute();
}

//Function to create league

function createLeague($leagueName)
{
    global $conn; // Access the global variable $conn which is the database connection

    // SQL statement to insert league name into league table
    $sql = "INSERT INTO league (leagueName) VALUES (?)";

    // Prepare the SQL statement to be executed by the database
    $stmt = $conn->prepare($sql);

    // Check if the statement failed to prepare and handle error if true
    if (!$stmt) {
        // Handle error here
        echo "Error: " . $conn->error;
        exit();
    }

    // Bind the parameter $leagueName to the prepared statement
    $stmt->bind_param("s", $leagueName);

    // Execute the prepared statement
    $stmt->execute();
}

//Function to create a team

function createTeam($teamName, $clubID, $leagueID)
{
    global $conn; // Access the global variable $conn which is the database connection

    // SQL statement to insert team name, club ID, and league ID into team table
    $sql = "INSERT INTO team (teamName, clubID, leagueID) VALUES (?, ?, ?)";

    // Prepare the SQL statement to be executed by the database
    $stmt = $conn->prepare($sql);

    // Check if the statement failed to prepare and handle error if true
    if (!$stmt) {
        // Handle error here
        echo "Error: " . $conn->error;
        exit();
    }

    // Bind the parameters $teamName, $clubID, and $leagueID to the prepared statement
    $stmt->bind_param("sii", $teamName, $clubID, $leagueID);

    // Execute the prepared statement
    $stmt->execute();
}


//Function to login a user

function loginUser($email, $password)
{
    global $conn;
    // Check if the email exists in the database
    $emailExists = emailExists($email);

    if ($emailExists === false) {
        // If the email does not exist, look for the email in the temporary user table
        $query = "SELECT * FROM tempuser WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $query)) {
            // Handle SQL error
            header("Location: ../login.php?error=sqlerror&message=" . urlencode("SQL error"));
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $passwordHashed = $row["password"];
                $checkPassword = password_verify($password, $passwordHashed);
                if ($checkPassword === false) {
                    // Handle incorrect email or password error
                    header("Location: ../login.php?error=incorrectdetails&message=" . urlencode("Incorrect email or password"));
                    exit();
                } else if ($checkPassword === true) {
                    // Handle pending approval error
                    header("Location: ../login.php?error=pendingapproval&message=" . urlencode("Your account is still pending approval"));
                    exit();
                }
            } else {
                // Handle incorrect email or password error
                header("Location: ../login.php?error=incorrectdetails&message=" . urlencode("Incorrect email or password"));
                exit();
            }
        }
    } else {
        // If the email exists, check the password
        $passwordHashed = $emailExists["password"];
        $checkPassword = password_verify($password, $passwordHashed);
        if ($checkPassword === false) {
            // Incorrect password
            header("Location: ../login.php?error=incorrectdetails&message=" . urlencode("Incorrect email or password"));
            exit();
        } else if ($checkPassword === true) {
            // Start a new session and store user details
            session_start();
            $_SESSION["userID"] = $emailExists["userID"];
            $_SESSION["email"] = $emailExists["email"];
            $_SESSION["accountType"] = $emailExists["accountType"];
            if ($_SESSION["accountType"] == "Player") {
                // Redirect to player dashboard
                header("Location: ../playerDashboard.php");
            } elseif ($_SESSION["accountType"] == "Club Admin") {
                // Redirect to club admin dashboard
                header("Location: ../clubAdminDashboard.php");
            } else {
                // Redirect to system admin dashboard
                header("Location: ../systemAdminDashboard.php");
            }
            exit();
        }
    }
}

// Function to get the player's name from the player table
function getPlayerName($userID)
{
    global $conn;
    $sql = "SELECT firstName FROM player WHERE userID = ?;";
    $stmt = mysqli_stmt_init($conn);
    // Check if SQL statement is prepared successfully
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userID); // Bind parameter to SQL statement
    mysqli_stmt_execute($stmt); // Execute SQL statement

    $resultData = mysqli_stmt_get_result($stmt); // Get the result of the executed statement

    // Check if there's a row with a matching userID in the player table
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['firstName']; // Return the player's first name
    } else {
        $result = false;
        return $result; // Return false if there's no matching userID in the player table
    }
}

// Function to get the player's ID from the player table
function getPlayerID($userID)
{
    global $conn;
    $sql = "SELECT playerID FROM player WHERE userID = ?;";
    $stmt = mysqli_stmt_init($conn);
    // Check if SQL statement is prepared successfully
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userID); // Bind parameter to SQL statement
    mysqli_stmt_execute($stmt); // Execute SQL statement

    $resultData = mysqli_stmt_get_result($stmt); // Get the result of the executed statement

    // Check if there's a row with a matching userID in the player table
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['playerID']; // Return the player's ID
    } else {
        $result = false;
        return $result; // Return false if there's no matching userID in the player table
    }
}

// Function to get the player's goals from the goal table
function getPlayerGoals($userID)
{
    global $conn;
    // Get playerID using userID
    $playerID = getPlayerID($userID);
    $sql = "SELECT numOfGoals FROM goal WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn);
    // Prepare statement and check for errors
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }
    // Bind parameters and execute statement
    mysqli_stmt_bind_param($stmt, "s", $playerID); 
    mysqli_stmt_execute($stmt);
    // Get result and return numOfGoals if found
    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['numOfGoals'];
    } else {
        // If no goals found, return 0
        $result = 0;
        return $result;
    }
}

// Function to get the player's assists from the assist table
function getPlayerAssists($userID)
{
    global $conn;
    // Get playerID using userID
    $playerID = getPlayerID($userID);
    $sql = "SELECT numOfAssists FROM assist WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn);
    // Prepare statement and check for errors
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }
    // Bind parameters and execute statement
    mysqli_stmt_bind_param($stmt, "s", $playerID);
    mysqli_stmt_execute($stmt);
    // Get result and return numOfAssists if found
    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['numOfAssists'];
    } else {
        // If no assists found, return 0
        $result = 0;
        return $result;
    }
}

// Function to get the player's appearances from the appearance table
function getPlayerApperances($userID)
{
    global $conn;
    // Get playerID using userID
    $playerID = getPlayerID($userID);
    $sql = "SELECT numOfAppearances FROM appearance WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn);
    // Prepare statement and check for errors
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }
    // Bind parameters and execute statement
    mysqli_stmt_bind_param($stmt, "s", $playerID);
    mysqli_stmt_execute($stmt);
    // Get result and return numOfAppearances if found
    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['numOfAppearances'];
    } else {
        // If no appearances found, return 0
        $result = 0;
        return $result;
    }
}

//Function to get the player's team from the player table
function getPlayerTeam($userID)
{
    global $conn;
    //get playerID using userID
    $playerID = getPlayerID($userID); //calls the function getPlayerID which returns the playerID associated with the userID

    //get teamID using playerID
    $sql = "SELECT teamID FROM player WHERE playerID = ?;"; //SQL statement to get the teamID from the player table using playerID
    $stmt = mysqli_stmt_init($conn); //initialize a new prepared statement using the database connection

    if (!mysqli_stmt_prepare($stmt, $sql)) { //Check if the SQL statement is valid and if the prepared statement can be initialized
        header("Location: ../signup.php?error=stmtfailed"); //Redirect to the signup page if the prepared statement cannot be initialized
        exit(); //Stop script execution
    }

    mysqli_stmt_bind_param($stmt, "s", $playerID); //bind the parameter (playerID) to the prepared statement
    mysqli_stmt_execute($stmt); //execute the prepared statement

    //get team name using teamID
    $resultData = mysqli_stmt_get_result($stmt); //store the result set returned by the prepared statement

    if ($row = mysqli_fetch_assoc($resultData)) { //check if there is a row in the result set and store it in an associative array $row
        $teamID = $row['teamID']; //get the teamID value from the $row array
        $sql = "SELECT teamName FROM team WHERE teamID = ?;"; //SQL statement to get the teamName using the teamID
        $stmt = mysqli_stmt_init($conn); //initialize a new prepared statement using the database connection

        if (!mysqli_stmt_prepare($stmt, $sql)) { //Check if the SQL statement is valid and if the prepared statement can be initialized
            header("Location: ../signup.php?error=stmtfailed"); //Redirect to the signup page if the prepared statement cannot be initialized
            exit(); //Stop script execution
        }

        mysqli_stmt_bind_param($stmt, "s", $teamID); //bind the parameter (teamID) to the prepared statement
        mysqli_stmt_execute($stmt); //execute the prepared statement

        $resultData = mysqli_stmt_get_result($stmt); //store the result set returned by the prepared statement

        if ($row = mysqli_fetch_assoc($resultData)) { //check if there is a row in the result set and store it in an associative array $row
            return $row['teamName']; //get the teamName value from the $row array and return it
        } else {
            $result = false; //set $result to false if there is no row in the result set
            return $result; //return $result
        }
    } else {
        $result = false; //set $result to false if there is no row in the result set
        return $result; //return $result
    }
}

// Function to get the player's next game from the fixture table
function getNextGame($userID)
{
    global $conn; // Use the global connection variable
    // Get playerID using userID
    $playerID = getPlayerID($userID);
    // Get teamID using playerID
    $sql = "SELECT teamID FROM player WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn); // Initialize a prepared statement
    if (!mysqli_stmt_prepare($stmt, $sql)) { // Check if the SQL statement is valid
        header("Location: ../signup.php?error=stmtfailed"); // Redirect if it fails
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $playerID); // Bind parameters to the prepared statement
    mysqli_stmt_execute($stmt); // Execute the prepared statement

    // Get next fixture using teamID as hometeamID or awayteamID
    $resultData = mysqli_stmt_get_result($stmt); // Get the result set

    if ($row = mysqli_fetch_assoc($resultData)) { // Check if there is a result row
        $teamID = $row['teamID'];
        $sql = "SELECT fixtureID, matchWeek FROM fixture WHERE homeTeamID = ? OR awayTeamID = ?;";
        $stmt = mysqli_stmt_init($conn); // Re-initialize a new prepared statement
        if (!mysqli_stmt_prepare($stmt, $sql)) { // Check if the SQL statement is valid
            header("Location: ../signup.php?error=stmtfailed"); // Redirect if it fails
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $teamID, $teamID); // Bind parameters to the prepared statement
        mysqli_stmt_execute($stmt); // Execute the prepared statement
        $resultData = mysqli_stmt_get_result($stmt); // Get the result set

        if ($row = mysqli_fetch_assoc($resultData)) { // Check if there is a result row
            $gameWeekID = $row['matchWeek'];

            $sql = "SELECT gameDate FROM gameweek WHERE gameWeekID = ?;";
            $stmt = mysqli_stmt_init($conn); // Re-initialize a new prepared statement
            if (!mysqli_stmt_prepare($stmt, $sql)) { // Check if the SQL statement is valid
                header("Location: ../signup.php?error=stmtfailed"); // Redirect if it fails
                exit();
            }
            mysqli_stmt_bind_param($stmt, "s", $gameWeekID); // Bind parameters to the prepared statement
            mysqli_stmt_execute($stmt); // Execute the prepared statement
            $resultData = mysqli_stmt_get_result($stmt); // Get the result set

            if ($row = mysqli_fetch_assoc($resultData)) { // Check if there is a result row
                return $row['gameDate']; // Return the game date
            } else {
                $result = false; // Set a flag to indicate that no result was found
                return $result; // Return the flag
            }
        } else {
            $result = false; // Set a flag to indicate that no result was found
            return $result; // Return the flag
        }
    } else {
        $result = false; // Set a flag to indicate that no result was found
        return $result; // Return the flag
    }
}

// Function to get how many days until next game
function getDaysUntilGame($userID)
{
    // Get the next game date for the user
    $gameDate = getNextGame($userID);

    // If there is a game date, calculate the difference between the current date and game date
    if ($gameDate) {
        $currentDate = new DateTime();
        $gameDate = new DateTime($gameDate);
        $diff = $currentDate->diff($gameDate);

        // Format the difference to get the days and hours
        $days = $diff->format('%d');
        $hours = $diff->format('%h');

        // Display the number of days and hours until the next game
        echo "$days days and $hours hours";
    }
    // If there is no game date found for the user, display an error message
    else {
        echo "No game date found";
    }
}

//Function to get the players opposition team name
function getOppositionName($userID)
{
    global $conn; //accessing global variable $conn
    //get playerID using userID
    $playerID = getPlayerID($userID); //calling the function getPlayerID and storing the result in $playerID
    //get teamID using playerID
    $sql = "SELECT teamID FROM player WHERE playerID = ?;"; //creating a SQL query
    $stmt = mysqli_stmt_init($conn); //initialize a new statement using the $conn variable
    if (!mysqli_stmt_prepare($stmt, $sql)) { //preparing the statement and checking for any errors
        header("Location: ../signup.php?error=stmtfailed"); //redirecting to the signup page with an error message if the statement fails to execute
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $playerID); //binding the parameter $playerID to the statement
    mysqli_stmt_execute($stmt); //executing the statement

    //get opposition team name using teamID as hometeamID or awayteamID
    $resultData = mysqli_stmt_get_result($stmt); //getting the results from the executed statement

    if ($row = mysqli_fetch_assoc($resultData)) { //fetching the results as an associative array and storing it in $row
        $teamID = $row['teamID']; //storing the value of $row['teamID'] in $teamID
        $sql = "SELECT fixtureID FROM fixture WHERE homeTeamID = ? OR awayTeamID = ?;"; //creating a new SQL query
        $stmt = mysqli_stmt_init($conn); //initializing a new statement using the $conn variable
        if (!mysqli_stmt_prepare($stmt, $sql)) { //preparing the statement and checking for any errors
            header("Location: ../signup.php?error=stmtfailed"); //redirecting to the signup page with an error message if the statement fails to execute
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $teamID, $teamID); //binding the parameters $teamID and $teamID to the statement
        mysqli_stmt_execute($stmt); //executing the statement

        $resultData = mysqli_stmt_get_result($stmt); //getting the results from the executed statement

        if ($row = mysqli_fetch_assoc($resultData)) { //fetching the results as an associative array and storing it in $row
            $fixtureID = $row['fixtureID']; //storing the value of $row['fixtureID'] in $fixtureID
            $sql = "SELECT homeTeamID, awayTeamID FROM fixture WHERE fixtureID = ?;"; //creating a new SQL query
            $stmt = mysqli_stmt_init($conn); //initializing a new statement using the $conn variable
            if (!mysqli_stmt_prepare($stmt, $sql)) { //preparing the statement and checking for any errors
                header("Location: ../signup.php?error=stmtfailed"); //redirecting to the signup page with an error message if the statement fails to execute
                exit();
            }

            mysqli_stmt_bind_param($stmt, "s", $fixtureID); //binding the parameter $fixtureID to the statement
            mysqli_stmt_execute($stmt); //executing the statement

            $resultData = mysqli_stmt_get_result($stmt); //getting the results from the executed statement

            if ($row = mysqli_fetch_assoc($resultData)) { //fetching the results as an associative array and storing it in $row
                $homeTeamID = $row['homeTeamID'];  //storing the value of $row['homeTeamID'] in $homeTeamID
                $awayTeamID = $row['awayTeamID'];  //storing the value of $row['awayTeamID'] in $awayTeamID
                if ($homeTeamID == $teamID) {
                    $sql = "SELECT teamName FROM team WHERE teamID = ?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../signup.php?error=stmtfailed"); //redirecting to the signup page with an error message if the statement fails to execute
                        exit();
                    }

                    mysqli_stmt_bind_param($stmt, "s", $awayTeamID);  //binding the parameter $awayTeamID to the statement
                    mysqli_stmt_execute($stmt);

                    $resultData = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($resultData)) {
                        return $row['teamName'];  //returning the value of $row['teamName']
                    } else {
                        $result = false;
                        return $result;  //returning false if the query fails
                    }
                } else {
                    $sql = "SELECT teamName FROM team WHERE teamID = ?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../signup.php?error=stmtfailed");  //redirecting to the signup page with an error message if the statement fails to execute
                        exit();
                    }
                }
            }
        }
    }
}

//Function to check if the user has a profile picture
function userPfpCheck($userID)
{
    $pfpPath = "images/pfp/" . $userID . ".*";  //creating a path to the user's pfp
    $matchingFiles = glob($pfpPath);  //getting the path to the user's pfp
    if (!empty($matchingFiles)) {
        // Display the user's pfp
        echo '<img class="img-circle elevation-2" style="height:100px; width:100px;"src="' . $matchingFiles[0] . '" alt="User Avatar" />';
    } else {
        // Use default pfp and rename it to user's ID
        $defaultPfpPath = "images/pfp/defaultpfp.*";
        $matchingFiles = glob($defaultPfpPath);
        if (!empty($matchingFiles)) {
            $extension = pathinfo($matchingFiles[0], PATHINFO_EXTENSION);  //getting the extension of the default pfp
            $newPfpPath = "images/pfp/" . $userID . "." . $extension;
            if (copy($matchingFiles[0], $newPfpPath)) {
                // set permissions for newly created file
                chmod($newPfpPath, 0644);
                echo '<img class="img-circle elevation-2" style="height:100px; width:100px;" src="' . $newPfpPath . '" alt="User Avatar" />';  //displaying the user's pfp
            } else {
                echo "Error: default profile picture not found.";  //displaying an error message if the default pfp is not found
            }
        } else {
            echo "Error: default profile picture not found.";  //displaying an error message if the default pfp is not found
        }
    }
}

//Function to get club admin's first name
function getclubAdminName($userID)
{
    global $conn;
    $sql = "SELECT firstName FROM clubadmin WHERE userID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");  //redirecting to the signup page with an error message if the statement fails to execute
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['firstName'];  //returning the value of $row['firstName']
    } else {
        $result = false;
        return $result;  //returning false if the query fails
    }
}

//Function to get club admin's club name
function getClubName($userID)
{
    global $conn;
    $sql = "SELECT clubID FROM clubadmin WHERE userID = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error; //displaying an error message if the statement fails to execute
        exit;
    } else {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            $clubID = $row['clubID'];  //storing the value of $row['clubID'] in $clubID
            $sql = "SELECT clubName FROM club WHERE clubID = ?";
            $stmt = $conn->stmt_init();
            if (!$stmt->prepare($sql)) {
                echo "SQL statement failed: " . $conn->error;
                exit;
            } else {
                $stmt->bind_param("i", $clubID);  //binding the parameter $clubID to the statement
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                if ($row) {
                    return $row['clubName'];  //returning the value of $row['clubName']
                } else {
                    return false;  //returning false if the query fails
                }
            }
        } else {
            return false;  //returning false if the query fails
        }
    }
}

//Function to get the teamID of the player

function getTeamID($userID)
{
    global $conn;
    $sql = "SELECT teamID FROM player WHERE userID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");  //redirecting to the signup page with an error message if the statement fails to execute
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userID);  //binding the parameter $userID to the statement
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['teamID'];  //returning the value of $row['teamID']
    } else {
        $result = false;
        return $result;  //returning false if the query fails
    }
}

//Function to get teams from a club

function getTeams($clubID)
{
    global $conn;
    $sql = "SELECT teamID, teamName FROM team WHERE clubID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");  //redirecting to the signup page with an error message if the statement fails to execute
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $clubID);  //binding the parameter $clubID to the statement
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $teams = [];
    while ($row = mysqli_fetch_assoc($resultData)) {
        $teams[] = $row;  //storing the values of $row in $teams
    }
    return $teams;  //returning $teams
}



//Function to get predicted number of wins, draws and losses for a team

function getLeagueName($leagueID)
{
    global $conn;
    $sql = "SELECT leagueName FROM league WHERE leagueID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");  //redirecting to the signup page with an error message if the statement fails to execute
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $leagueID);  //binding the parameter $leagueID to the statement
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['leagueName'];  //returning the value of $row['leagueName']
    } else {
        $result = false;
        return $result;  //returning false if the query fails
    }
}

//Function to get the average points per game for a team
function getAveragePointPerGame($teamID)
{
    global $conn;
    $sql = "SELECT COUNT(*) AS gamesPlayed
    FROM result
    WHERE homeTeamID = $teamID OR awayTeamID = $teamID;
    ";  //query to get the number of games played by a team
    $gamesPlayed = $conn->query($sql);
    $teamWins = getTeamWins($teamID);
    $teamLosses = getTeamLosses($teamID);
    $teamDraws = getTeamDraws($teamID);
    $gamesPlayed = $teamWins + $teamLosses + $teamDraws;  //calculating the number of games played by a team

    $averagePointPerGame = (3 * $teamWins + 1 * $teamDraws) / $gamesPlayed;  //calculating the average points per game
    return $averagePointPerGame;  //returning the average points per game
}

//Function to get the predicted points for a team
function getPredictedPoints($teamID)
{
    $wins = getTeamWins($teamID);
    $draws = getTeamDraws($teamID);
    $predictedWins = getPredictedWins($teamID);
    $predictedDraws = getPredictedDraws($teamID);
    $predictedPoints = ((3 * $wins) + $draws) + (3 * $predictedWins) + $predictedDraws;  //calculating the predicted points
    return $predictedPoints;  //returning the predicted points
}

//Function to get predicted number of wins for a team
function getPredictedWins($teamID)
{
    $fixtures = getFixturesForTeam($teamID);  //getting the fixtures for a team
    if (is_array($fixtures)) {
        $wins = 0;  //initialising the number of wins to 0
        foreach ($fixtures as $fixture) {
            $homeTeamID = $fixture['homeTeamID'];  //getting the home team ID
            $awayTeamID = $fixture['awayTeamID'];  //getting the away team ID

            $homeTeamAvgPoints = getAveragePointPerGame($homeTeamID);  //getting the average points per game for the home team
            $awayTeamAvgPoints = getAveragePointPerGame($awayTeamID);  //getting the average points per game for the away team

            if ($homeTeamAvgPoints > $awayTeamAvgPoints) {
                if ($homeTeamID == $teamID) {
                    $wins++;  //incrementing the number of wins if the home team has a higher average points per game
                }
            } else if ($homeTeamAvgPoints < $awayTeamAvgPoints) {
                if ($awayTeamID == $teamID) {
                    $wins++;  //incrementing the number of wins if the away team has a higher average points per game
                }
            }
            return $wins;  //returning the number of wins
        }
    } else {
        return 0;  //returning 0 if the query fails
    }
}

//Function to get predicted number of draws for a team
function getPredictedDraws($teamID)
{
    $fixtures = getFixturesForTeam($teamID);  //getting the fixtures for a team
    if (is_array($fixtures)) {
        $draws = 0;  //initialising the number of draws to 0

        foreach ($fixtures as $fixture) {
            $homeTeamID = $fixture['homeTeamID'];  //getting the home team ID
            $awayTeamID = $fixture['awayTeamID'];  //getting the away team ID

            $homeTeamAvgPoints = getAveragePointPerGame($homeTeamID);  //getting the average points per game for the home team
            $awayTeamAvgPoints = getAveragePointPerGame($awayTeamID);  //getting the average points per game for the away team

            if ($homeTeamAvgPoints == $awayTeamAvgPoints) {
                $draws++;  //incrementing the number of draws if the home team and away team have the same average points per game
            }
        }

        return $draws;  //returning the number of draws
    } else {
        return 0;
    }
}

//Function to get predicted number of losses for a team
function getPredictedLosses($teamID)
{
    $fixtures = getFixturesForTeam($teamID);  //getting the fixtures for a team
    if (is_array($fixtures)) {
        $losses = 0;  //initialising the number of losses to 0
        foreach ($fixtures as $fixture) {
            $homeTeamID = $fixture['homeTeamID'];  //getting the home team ID
            $awayTeamID = $fixture['awayTeamID'];  //getting the away team ID

            $homeTeamAvgPoints = getAveragePointPerGame($homeTeamID);  //getting the average points per game for the home team
            $awayTeamAvgPoints = getAveragePointPerGame($awayTeamID);  //getting the average points per game for the away team

            if ($homeTeamAvgPoints < $awayTeamAvgPoints) {
                if ($homeTeamID == $teamID) {
                    $losses++;  //incrementing the number of losses if the home team has a lower average points per game
                }
            } else if ($homeTeamAvgPoints > $awayTeamAvgPoints) {
                if ($awayTeamID == $teamID) {
                    $losses++;  //incrementing the number of losses if the away team has a lower average points per game
                }
            }
        }
        return $losses;  //returning the number of losses
    } else {
        return 0;  //returning 0 if the query fails
    }
}

//Function to get the fixtures for a team
function getFixturesForTeam($teamID)
{
    global $conn;
    $sql = "SELECT homeTeamID, awayTeamID FROM fixture WHERE homeTeamID = $teamID OR awayTeamID = $teamID";  //query to get the fixtures for a team
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $fixtures = array();  //initialising the fixtures array
        while ($row = $result->fetch_assoc()) {
            $fixtures[] = $row;  //adding the fixtures to an array
        }
        return $fixtures;  //returning the fixtures
    } else {
        return null;  //returning null if the query fails
    }
}

//Function to get the amount of points a team has
function getTeamPoints($teams)
{
    $points = [];  //initialising the points array
    foreach ($teams as $team) {
        $teamID = $team["teamID"];  //getting the team ID
        $teamWins = getTeamWins($teamID);  //getting the number of wins for a team
        $teamDraws = getTeamDraws($teamID);  //getting the number of draws for a team

        $teamPoints = $teamWins * 3 + $teamDraws;  //calculating the points for a team
        $points[] = [  //adding the team ID, team name and the points to an array
            "teamID" => $teamID,
            "teamName" => $team["teamName"],
            "points" => $teamPoints
        ];
    }

    // Sort the teams based on their points
    usort($points, function ($a, $b) {
        return $b["points"] - $a["points"];
    });

    return $points;  //returning the points
}

function getNumberOfGamesLeft($teamID)
{
    global $conn;
    $sql = "SELECT COUNT(*) as totalGames
            FROM fixture
            WHERE (homeTeamID = $teamID OR awayTeamID = $teamID)";  //query to get the number of games left for a team

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $results = $result->fetch_assoc();
        return $results["totalGames"];  //returning the number of games left
    } else {
        return null;  //returning null if the query fails
    }
}

//Function to get the number of wins for a team
function getTeamWins($teamID)
{
    global $conn;
    $sql = "SELECT homeTeamID, awayTeamID, homeTeamScore, awayTeamScore FROM result WHERE homeTeamID = ? OR awayTeamID = ?";  //query to get the results for a team
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        trigger_error("Prepare statement failed: " . $conn->error, E_USER_ERROR);  //triggering an error if the query fails
    }
    $stmt->bind_param("ii", $teamID, $teamID);
    if (!$stmt->execute()) {
        trigger_error("Execute statement failed: " . $stmt->error, E_USER_ERROR);  //triggering an error if the query fails
    }
    $result = $stmt->get_result();
    $wins = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (isset($row["homeTeamID"]) && isset($row["homeTeamScore"]) && isset($row["awayTeamScore"])) {
                if (
                    $row["homeTeamID"] == $teamID && $row["homeTeamScore"] > $row["awayTeamScore"] ||
                    $row["awayTeamID"] == $teamID && $row["awayTeamScore"] > $row["homeTeamScore"]  //checking if the home team or away team has a higher score
                ) {
                    $wins++;
                }
            } else {
                error_log("Error: " . $conn->error . "\n", 3, "error.log");  //logging the error
            }
        }
        return $wins;  //returning the number of wins
    } else {
        return 0;  //returning 0 if the query fails
    }
}

//Function to get the number of draws for a team
function getTeamDraws($teamID)
{
    global $conn;
    $sql = "SELECT homeTeamID, awayTeamID, homeTeamScore, awayTeamScore FROM result WHERE homeTeamID = ? OR awayTeamID = ?";  //query to get the results for a team
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("getTeamDraws: Error preparing statement: " . $conn->error, 0);  //logging the error
        return 0;
    }
    $stmt->bind_param("ii", $teamID, $teamID);
    if (!$stmt->execute()) {
        error_log("getTeamDraws: Error executing statement: " . $stmt->error, 0);  //logging the error
        return 0;
    }
    $result = $stmt->get_result();
    $draws = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row["homeTeamScore"] == $row["awayTeamScore"]) {
                $draws++;  //increasing the number of draws if the home team score is equal to the away team score
            }
        }
        return $draws;  //returning the number of draws
    }
    error_log("getTeamDraws: No data found in query result", 0);  //logging the error
    return 0;  //returning 0 if the query fails
}

//Function to get the number of losses for a team
function getTeamLosses($teamID)
{
    global $conn;
    $sql = "SELECT homeTeamID, awayTeamID, homeTeamScore, awayTeamScore FROM result WHERE homeTeamID = ? OR awayTeamID = ?";  //query to get the results for a team
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Error preparing SQL statement: " . $conn->error);  //logging the error
        return 0;
    }
    $stmt->bind_param("ii", $teamID, $teamID);
    if ($stmt->execute() === false) {
        error_log("Error executing SQL statement: " . $stmt->error);  //logging the error
        return 0;
    }
    $result = $stmt->get_result();
    if ($result === false) {
        error_log("Error getting result: " . $stmt->error);  //logging the error
        return 0;
    }
    $losses = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (
                $row["homeTeamID"] == $teamID && $row["homeTeamScore"] < $row["awayTeamScore"] ||
                $row["awayTeamID"] == $teamID && $row["awayTeamScore"] < $row["homeTeamScore"]  //checking if the home team or away team has a lower score
            ) {
                $losses++;  //increasing the number of losses if the home team or away team has a lower score
            }
        }
        return $losses;  //returning the number of losses
    }
    return 0;  //returning 0 if the query fails
}

//Function to get the predicted results for a league
function getPredictedResults($leagueID)
{
    global $conn;
    $query = "SELECT MIN(matchWeek) AS minWeek FROM fixture"; //query to get the minimum match week
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $minWeek = $row['minWeek']; //getting the minimum match week

        $query = "SELECT homeTeamID, awayTeamID FROM fixture WHERE matchWeek = '$minWeek' AND leagueID = '$leagueID'"; //query to get the fixtures for the minimum match week
        $resultdetailresult = mysqli_query($conn, $query);

        if (mysqli_num_rows($resultdetailresult) > 0) {
            $predictedResults = array();
            while ($row = mysqli_fetch_array($resultdetailresult)) {
                $homeTeamID = $row['homeTeamID']; //getting the home team ID
                $awayTeamID = $row['awayTeamID']; //getting the away team ID

                $homeTeamPPG = getAveragePointPerGame($homeTeamID); //getting the average points per game for the home team
                $awayTeamPPG = getAveragePointPerGame($awayTeamID); //getting the average points per game for the away team

                $query = "SELECT teamName FROM team WHERE teamID = '$homeTeamID'";
                $result2 = mysqli_query($conn, $query);
                $row2 = mysqli_fetch_array($result2);
                $homeTeamName = $row2['teamName']; //getting the home team name

                $query = "SELECT teamName FROM team WHERE teamID = '$awayTeamID'";
                $result3 = mysqli_query($conn, $query);
                $row3 = mysqli_fetch_array($result3);
                $awayTeamName = $row3['teamName']; //getting the away team name

                if ($homeTeamPPG > $awayTeamPPG) {
                    $predictedResult = $homeTeamName . " wins";
                } elseif ($awayTeamPPG > $homeTeamPPG) {
                    $predictedResult = $awayTeamName . " wins";
                } else {
                    $predictedResult = "Draw"; //predicting the result
                }

                $predictedResults[] = array(

                    "homeTeam" => $homeTeamName,
                    "awayTeam" => $awayTeamName,
                    "predictedResult" => $predictedResult
                );
            }
            return $predictedResults; //returning the predicted results
        } else {
            return "No fixtures with the minimum game week number found"; //returning an error message if the query fails
        }
    } else {
        return "No game week numbers found in the result table"; //returning an error message if the query fails
    }
}

//Function to calculate the points per week for a team
function calculatePointsPerWeek($teamID)
{
    global $conn;
    // Get the lowest and highest gameWeekID from the fixture table
    $query = "SELECT MIN(matchWeek) as minGWID, MAX(matchWeek) as maxGWID FROM fixture WHERE homeTeamID = '$teamID' OR awayTeamID = '$teamID'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        // Output error message or perform other debugging actions
        echo "Query failed: " . mysqli_error($conn);
        exit;
    }

    $gwIDs = mysqli_fetch_assoc($result);
    $minGWID = $gwIDs['minGWID'];
    $maxGWID = $gwIDs['maxGWID'];

    // Get the starting number of points by calling the functions teamWins and teamDraws
    $teamWins = getTeamWins($teamID);
    $teamDraws = getTeamDraws($teamID);
    $startingPoints = $teamWins * 3 + $teamDraws;

    // Initialize an array to store the team's points per week
    $pointsPerWeek = [];

    // Loop through every game week to calculate the team's points
    for ($i = $minGWID; $i <= $maxGWID; $i++) {
        // Get the fixtures for the current game week
        $query = "SELECT homeTeamID, awayTeamID FROM fixture WHERE (homeTeamID = '$teamID' OR awayTeamID = '$teamID') AND matchWeek = '$i'";
        $result = mysqli_query($conn, $query);

        // Loop through each fixture for the current game week and calculate the points
        $numGames = 0;
        while ($fixtureData = mysqli_fetch_assoc($result)) {
            if ($fixtureData['homeTeamID'] == $teamID || $fixtureData['awayTeamID'] == $teamID) {
                $numGames++;
                $oppositionID = ($fixtureData['homeTeamID'] == $teamID) ? $fixtureData['awayTeamID'] : $fixtureData['homeTeamID'];
                $teamPointPerGame = getAveragePointPerGame($teamID);
                $oppositionPointPerGame = getAveragePointPerGame($oppositionID);

                // Decide the points based on the team's and opposition's pointsPerGame
                if ($teamPointPerGame > $oppositionPointPerGame) {
                    $startingPoints += 3;
                } elseif ($teamPointPerGame == $oppositionPointPerGame) {
                    $startingPoints += 1;
                }
            }
        }

        // Add the starting points for the current game week
        if ($numGames > 0) {
            $pointsPerWeek[] = [$i, $startingPoints];
        }
    }
    return $pointsPerWeek; //returning the points per week
}




//Function to get a team's goal difference

function getTeamGoalDifference($teamID)
{
    //Get the amount of goals the team has scored and conceded
    $teamGoalsScored = getTeamGoalsScored($teamID);
    $teamGoalsConceded = getTeamGoalsConceded($teamID);

    //Calculate the goal difference
    $teamGoalDifference = $teamGoalsScored - $teamGoalsConceded;

    return $teamGoalDifference;
}

//Function to get a team's goals scored

function getTeamGoalsScored($teamID)
{
    global $conn;
    //Get the amount of goals the team has scored
    $query = "SELECT SUM(homeTeamScore) as homeTeamScore, SUM(awayTeamScore) as awayTeamScore FROM result WHERE homeTeamID = '$teamID' OR awayTeamID = '$teamID'";
    $result = mysqli_query($conn, $query);
    $teamGoals = mysqli_fetch_assoc($result);
    $teamGoalsScored = $teamGoals['homeTeamScore'] + $teamGoals['awayTeamScore'];

    return $teamGoalsScored;
}

//Function to get a team's goals conceded

function getTeamGoalsConceded($teamID)
{
    global $conn;
    //Get the amount of goals the team has conceded
    $query = "SELECT SUM(IF(homeTeamID = '$teamID', awayTeamScore, homeTeamScore)) as goalsConceded FROM result WHERE homeTeamID = '$teamID' OR awayTeamID = '$teamID'";
    $result = mysqli_query($conn, $query);
    $teamGoals = mysqli_fetch_assoc($result);
    $teamGoalsConceded = $teamGoals['goalsConceded'];

    return $teamGoalsConceded;
}


//Function to get the leagueID based on the playerID
function getLeagueID($userID)
{
    global $conn;
    //Check account type
    $query = "SELECT accountType FROM user WHERE userID = '$userID'";
    $result = mysqli_query($conn, $query);
    $accountType = mysqli_fetch_assoc($result)['accountType'];

    if ($accountType == 'Player') {
        //Get the teamID of the player
        $query = "SELECT teamID FROM player WHERE userID = '$userID'";
        $result = mysqli_query($conn, $query);
        $teamID = mysqli_fetch_assoc($result)['teamID'];

        //Get the leagueID of the team
        $query = "SELECT leagueID FROM team WHERE teamID = '$teamID'";
        $result2 = mysqli_query($conn, $query);
        $leagueID = mysqli_fetch_assoc($result2)['leagueID'];
    } else {
        $result = mysqli_query($conn, $query);
        if (!$result) {
            // Handle the error, for example by echoing an error message
            echo "Error: " . mysqli_error($conn);
        } else {
            // Continue with processing the query result
            if (mysqli_num_rows($result) > 0) {
                // Get the club ID
                $query = "SELECT clubID FROM clubadmin WHERE userID = '$userID'";
                $result = mysqli_query($conn, $query);
                $clubID = mysqli_fetch_assoc($result)['clubID'];

                // Get the lowest league ID for the club
                $query = "SELECT MIN(leagueID) as lowestLeagueID FROM team WHERE clubID = '$clubID'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $leagueID = $row['lowestLeagueID'];
            }
        }
    }
    return $leagueID;
}

//Function to get the top team ID for a club

function getTopTeamLeague($clubID)
{
    global $conn;
    //Get the lowest league ID for the club
    $query = "SELECT MIN(leagueID) as lowestLeagueID FROM team WHERE clubID = '$clubID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $lowestLeagueID = $row['lowestLeagueID'];

    return $lowestLeagueID;
}

//Function to get available players for a fixture
function getAvailablePlayersByFixtureID($fixtureID)
{
    global $conn;
    $players = array(); //array to store the players

    $query = "SELECT playerID FROM availability WHERE fixtureID = $fixtureID AND available = 1 AND playerID IN (SELECT playerID FROM player WHERE teamID = (SELECT homeTeamID FROM fixture WHERE fixtureID = $fixtureID) OR teamID = (SELECT awayTeamID FROM fixture WHERE fixtureID = $fixtureID))"; //query to get the players
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $players[] = array('playerID' => $row['playerID']); //add the player to the array
    }

    return $players; //return the array
}

//Function to get team name by team ID
function getTeamNameByID($teamID)
{
    global $conn;
    $teamName = ""; //variable to store the team name
    $query = "SELECT teamName FROM team WHERE teamID = $teamID"; //query to get the team name
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $teamName = $row['teamName']; //store the team name

    return $teamName; //return the team name
}

//Function to get a team's fixtures
function getFixturesByTeamID($teamID)
{
    global $conn;
    $fixtures = array(); //array to store the fixtures

    $query = "SELECT fixtureID, homeTeamID, awayTeamID FROM fixture WHERE homeTeamID = $teamID OR awayTeamID = $teamID"; //query to get the fixtures
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $fixtures[] = $row; //add the fixture to the array
    }

    return $fixtures; //return the array
}

//Function to get unavailable players for a fixture
function getUnavailablePlayersByFixtureID($fixtureID)
{
    global $conn;
    $players = array(); //array to store the players

    $query = "SELECT playerID FROM availability WHERE fixtureID = $fixtureID AND available = 0 AND playerID IN (SELECT playerID FROM player WHERE teamID = (SELECT homeTeamID FROM fixture WHERE fixtureID = $fixtureID) OR teamID = (SELECT awayTeamID FROM fixture WHERE fixtureID = $fixtureID))"; //query to get the players
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $players[] = array('playerID' => $row['playerID']); //add the player to the array
    }

    return $players; //return the array
}

//Function to get players who haven't responded to a fixture
function getUnansweredPlayersByFixtureID($fixtureID)
{
    global $conn;
    $players = array(); //array to store the players

    $query = "SELECT playerID FROM player WHERE playerID NOT IN (SELECT playerID FROM availability WHERE fixtureID = $fixtureID) AND playerID IN (SELECT playerID FROM player WHERE teamID = (SELECT homeTeamID FROM fixture WHERE fixtureID = $fixtureID) OR teamID = (SELECT awayTeamID FROM fixture WHERE fixtureID = $fixtureID))"; //query to get the players who haven't responded
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $players[] = array('playerID' => $row['playerID']); //add the player to the array
    }

    return $players; //return the array
}

//Function to get the player's name by player ID
function getPlayerNameByID($playerID)
{
    global $conn;
    $query = "SELECT firstName, lastName FROM player WHERE playerID = $playerID"; //query to get the player's name
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn); //display error message
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $firstName = $row['firstName']; //store the first name
        $lastName = $row['lastName']; //store the last name
        return $firstName . " " . $lastName; //return the full name
    } else {
        return ""; //return an empty string
    }
}

//Update goals scored for a player

function updateGoals($playerID, $goals)
{
    global $conn;
    $query = "SELECT * FROM goal WHERE playerID = $playerID"; //query to get the player's goals
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO goal (playerID, numOfGoals) VALUES ($playerID, $goals)"; //query to insert the player's goals
    } else {
        $query = "UPDATE goal SET numOfGoals = numOfGoals + $goals WHERE playerID = $playerID"; //query to update the player's goals
    }
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn); //display error message
        exit;
    }
}

//Update assists for a player

function updateAssists($playerID, $assists)
{
    global $conn;
    $query = "SELECT * FROM assist WHERE playerID = $playerID"; //query to get the player's assists
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO assist (playerID, numOfAssists) VALUES ($playerID, $assists)"; //query to insert the player's assists
    } else {
        $query = "UPDATE assist SET numOfAssists = numOfAssists + $assists WHERE playerID = $playerID"; //query to update the player's assists
    }
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn); //display error message
        exit;
    }
}

//Function to get all clubs in database

function getClubs()
{

    global $conn;
    $clubs = array(); //array to store the clubs

    $query = "SELECT clubID, clubName FROM club"; //query to get the clubs
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn); //display error message
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $clubs[] = $row; //add the club to the array
    }

    return $clubs; //return the array
}

//Check if userID exists in the changePassword table and status is waiting

function userWaitingInPasswordChangeRequest($userID)
{
    global $conn;
    $query = "SELECT * FROM passwordchangerequest WHERE userID = '$userID' AND status = 'Waiting'"; //query to check if userID exists and status is waiting
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return true; //return true if userID exists and status is waiting
    } else {
        return false; //return false if userID doesn't exist or status is not waiting
    }
}

//Function to check if userID exists in the changePassword table and status is approved

function userApprovedInPasswordChangeRequest($userID)
{
    global $conn;
    $query = "SELECT * FROM passwordchangerequest WHERE userID = '$userID' AND status = 'Approved'"; //query to check if userID exists and status is approved
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return true; //return true if userID exists and status is approved
    } else {
        return false; //return false if userID doesn't exist or status is not approved
    }
}

//Function to get userID from the user table using email

function getUserIDByEmail($email)
{
    global $conn;
    $query = "SELECT userID FROM user WHERE email = '$email'"; //query to get the userID
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn); //display error message
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userID = $row['userID']; //store the userID
        return $userID; //return the userID
    } else {
        return ""; //return an empty string
    }
}

//Function to get user details from userID

function getUserDetails($userID)
{
    global $conn;
    $userDetails = array();
    $result = mysqli_query($conn, "SELECT firstName, lastName, teamID, DOB FROM player WHERE userID = $userID");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $userDetails['firstName'] = $row['firstName'];
        $userDetails['lastName'] = $row['lastName'];
        $userDetails['teamID'] = $row['teamID'];
        $userDetails['DOB'] = $row['DOB'];
    }
    return $userDetails;
}
