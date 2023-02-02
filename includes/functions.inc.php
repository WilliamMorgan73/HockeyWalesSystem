<?php
//Path: includes\functions.inc.php

//Function to check for empty fields in signup form

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

//Function to check for empty fields in login form

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

//Function to login a user

function loginUser($conn, $email, $password)
{
    $emailExists = emailExists($conn, $email);

    if ($emailExists === false) {
        header("Location: ../login.php?error=wronglogin");
        exit();
    }

    $passwordHashed = $emailExists["password"];
    $checkPassword = password_verify($password, $passwordHashed);

    if ($checkPassword === false) {
        header("Location: ../login.php?error=wronglogin");
        exit();
    } else if ($checkPassword === true) {
        session_start();
        $_SESSION["userID"] = $emailExists["userID"];
        $_SESSION["email"] = $emailExists["email"];
        $_SESSION["accountType"] = $emailExists["accountType"];
        if ($_SESSION["accountType"] == "Player") {
            header("Location: ../playerDashboard.php");
        } else {
            header("Location: ../clubAdmin/clubAdminHome.php");
        }
        exit();
    }
}

function getPlayerName($conn, $userID)
{
    $sql = "SELECT firstName FROM player WHERE userID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['firstName'];
    } else {
        $result = false;
        return $result;
    }
}

function getPlayerID($conn, $userID)
{
    $sql = "SELECT playerID FROM player WHERE userID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['playerID'];
    } else {
        $result = false;
        return $result;
    }
}
function getPlayerGoals($conn, $userID)
{
    //get playerID using userID
    $playerID = getPlayerID($conn, $userID);
    $sql = "SELECT numOfGoals FROM goal WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $playerID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['numOfGoals'];
    } else {
        $result = false;
        return $result;
    }
}

function getPlayerAssists($conn, $userID)
{
    //get playerID using userID
    $playerID = getPlayerID($conn, $userID);
    $sql = "SELECT numOfAssists FROM assist WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $playerID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['numOfAssists'];
    } else {
        $result = false;
        return $result;
    }
}

function getPlayerApperances($conn, $userID)
{
    //get playerID using userID
    $playerID = getPlayerID($conn, $userID);
    $sql = "SELECT numOfAppearances FROM apperance WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $playerID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['numOfAppearances'];
    } else {
        $result = false;
        return $result;
    }
}

function getPlayerTeam($conn, $userID)
{
    //get playerID using userID
    $playerID = getPlayerID($conn, $userID);
    //get teamID using playerID
    $sql = "SELECT teamID FROM player WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $playerID);
    mysqli_stmt_execute($stmt);

    //get team name using teamID

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        $teamID = $row['teamID'];
        $sql = "SELECT teamName FROM team WHERE teamID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../signup.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $teamID);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) {
            return $row['teamName'];
        } else {
            $result = false;
            return $result;
        }
    } else {
        $result = false;
        return $result;
    }
}

function getNextGame($conn, $userID)
{
    //get playerID using userID
    $playerID = getPlayerID($conn, $userID);
    //get teamID using playerID
    $sql = "SELECT teamID FROM player WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $playerID);
    mysqli_stmt_execute($stmt);

    //get next fixture using teamID as hometeamID or awayteamID

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        $teamID = $row['teamID'];
        $sql = "SELECT fixtureID FROM fixture WHERE homeTeamID = ? OR awayTeamID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../signup.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $teamID, $teamID);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) {
            $fixtureID = $row['fixtureID'];
            $sql = "SELECT dateTime FROM fixture WHERE fixtureID = ?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../signup.php?error=stmtfailed");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "s", $fixtureID);
            mysqli_stmt_execute($stmt);

            $resultData = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($resultData)) {
                return $row['dateTime'];
            } else {
                $result = false;
                return $result;
            }
        } else {
            $result = false;
            return $result;
        }
    } else {
        $result = false;
        return $result;
    }
}

function getOppositionName($conn, $userID)
{
    //get playerID using userID
    $playerID = getPlayerID($conn, $userID);
    //get teamID using playerID
    $sql = "SELECT teamID FROM player WHERE playerID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $playerID);
    mysqli_stmt_execute($stmt);

    //get oppposition team name using teamID as hometeamID or awayteamID

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        $teamID = $row['teamID'];
        $sql = "SELECT fixtureID FROM fixture WHERE homeTeamID = ? OR awayTeamID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../signup.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $teamID, $teamID);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) {
            $fixtureID = $row['fixtureID'];
            $sql = "SELECT homeTeamID, awayTeamID FROM fixture WHERE fixtureID = ?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../signup.php?error=stmtfailed");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "s", $fixtureID);
            mysqli_stmt_execute($stmt);

            $resultData = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($resultData)) {
                $homeTeamID = $row['homeTeamID'];
                $awayTeamID = $row['awayTeamID'];
                if ($homeTeamID == $teamID) {
                    $sql = "SELECT teamName FROM team WHERE teamID = ?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../signup.php?error=stmtfailed");
                        exit();
                    }

                    mysqli_stmt_bind_param($stmt, "s", $awayTeamID);
                    mysqli_stmt_execute($stmt);

                    $resultData = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($resultData)) {
                        return $row['teamName'];
                    } else {
                        $result = false;
                        return $result;
                    }
                } else {
                    $sql = "SELECT teamName FROM team WHERE teamID = ?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../signup.php?error=stmtfailed");
                        exit();
                    }
                }
            }
        }
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
OUTPUT ERROR MESSAGES ON SCREEN INSTEAD OF REDIRECTING TO LOGIN PAGE
ADD FOREIGN KEYS TO DATABASE
*/
