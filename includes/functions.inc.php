<?php
//Path: includes\functions.inc.php

$conn = require __DIR__ . '/dbhconfig.php';

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

//Function to check if fields are correct length

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

//Function to check if club exists

function clubExists($conn, $club)
{
    $sql = "SELECT * FROM club WHERE clubName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $club);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return false;
    } else {
        return true;
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

//Function to get club ID

function getClubID($conn, $club)
{
    $sql = "SELECT clubID FROM club WHERE clubName = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
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

function createPlayer($conn, $email, $password, $firstName, $lastName, $club, $DOB, $accountType)
{
    // Start a transaction
    $conn->begin_transaction();

    $sql = "INSERT INTO tempUser (email, password, accountType) 
    VALUES (?, ?, ?)";

    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        $conn->rollback(); // Roll back the transaction
        exit;
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $email, $hashedPassword, $accountType);
        $stmt->execute();
    }

    $userID = $conn->insert_id;

    $clubID = getClubID($conn, $club);
    if (!$clubID) {
        echo "Error: club not found";
        $conn->rollback(); // Roll back the transaction
        exit;
    }

    $sql = "INSERT INTO tempPlayer (firstName, lastName, clubID, DOB, tempUserID) 
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        $conn->rollback(); // Roll back the transaction
        exit;
    } else {
        $stmt->bind_param("ssssi", $firstName, $lastName, $clubID, $DOB, $userID);
        $stmt->execute();
        $conn->commit(); // Commit the transaction
        header("Location: ../signup.php?error=playeraccountcreated&message=" . urlencode("Account created, waiting for club admin to accept"));
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

    $clubID = getClubID($conn, $club);
    if (!$clubID) {
        echo "Error: club not found";
        $conn->rollback();
        exit;
    }

    $sql = "INSERT INTO clubadmin (firstName, lastName, clubID, DOB, userID) 
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        $conn->rollback();
        exit;
    } else {
        $stmt->bind_param("ssssi", $firstName, $lastName, $clubID, $DOB, $userID);
        $stmt->execute();
        $conn->commit();
        header("Location: ../login.php?signup=success"); //Change to success page until club admin approves
    }
}


//Function to login a user

function loginUser($conn, $email, $password)
{
    $emailExists = emailExists($conn, $email);

    if ($emailExists === false) {
        $query = "SELECT * FROM tempuser WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $query)) {
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
                    header("Location: ../login.php?error=incorrectdetails&message=" . urlencode("Incorrect email or password"));
                    exit();
                } else if ($checkPassword === true) {
                    header("Location: ../login.php?error=pendingapproval&message=" . urlencode("Your account is still pending approval"));
                    exit();
                }
            } else {
                header("Location: ../login.php?error=incorrectdetails&message=" . urlencode("Incorrect email or password"));
                exit();
            }
        }
    } else {
        $passwordHashed = $emailExists["password"];
        $checkPassword = password_verify($password, $passwordHashed);
        if ($checkPassword === false) {
            header("Location: ../login.php?error=incorrectdetails&message=" . urlencode("Incorrect email or password"));
            exit();
        } else if ($checkPassword === true) {
            session_start();
            $_SESSION["userID"] = $emailExists["userID"];
            $_SESSION["email"] = $emailExists["email"];
            $_SESSION["accountType"] = $emailExists["accountType"];
            if ($_SESSION["accountType"] == "Player") {
                header("Location: ../playerDashboard.php");
            } else {
                header("Location: ../clubAdminDashboard.php");
            }
            exit();
        }
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
        $result = 0;
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
        $result = 0;
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
        $result = 0;
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
        $sql = "SELECT fixtureID, matchWeek FROM fixture WHERE homeTeamID = ? OR awayTeamID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../signup.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "ss", $teamID, $teamID);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultData)) {
            $gameWeekID = $row['matchWeek'];

            $sql = "SELECT gameDate FROM gameweek WHERE gameWeekID = ?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../signup.php?error=stmtfailed");
                exit();
            }

            mysqli_stmt_bind_param($stmt, "s", $gameWeekID);
            mysqli_stmt_execute($stmt);

            $resultData = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($resultData)) {
                return $row['gameDate'];
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

//Function to get how many days until next game

function getDaysUntilGame($conn, $userID)
{
    $gameDate = getNextGame($conn, $userID);

    if ($gameDate) {
        $currentDate = new DateTime();
        $gameDate = new DateTime($gameDate);
        $diff = $currentDate->diff($gameDate);

        $days = $diff->format('%d');
        $hours = $diff->format('%h');

        echo "$days days and $hours hours";
    } else {
        echo "No game date found";
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

function userPfpCheck($userID)
{
    $pfpPath = "images/pfp/" . $userID . ".*";
    $matchingFiles = glob($pfpPath);
    if (!empty($matchingFiles)) {
        // Display the user's pfp
        echo '<img class="img-circle elevation-2" style="height:100px; width:100px;"src="' . $matchingFiles[0] . '" alt="User Avatar" />';
    } else {
        // Use default pfp and rename it to user's ID
        $defaultPfpPath = "images/pfp/defaultpfp.*";
        $matchingFiles = glob($defaultPfpPath);
        if (!empty($matchingFiles)) {
            $extension = pathinfo($matchingFiles[0], PATHINFO_EXTENSION);
            $newPfpPath = "images/pfp/" . $userID . "." . $extension;
            if (copy($matchingFiles[0], $newPfpPath)) {
                // set permissions for newly created file
                chmod($newPfpPath, 0644);
                echo '<img class="img-circle elevation-2" style="height:100px; width:100px;" src="' . $newPfpPath . '" alt="User Avatar" />';
            } else {
                echo "Error: default profile picture not found.";
            }
        } else {
            echo "Error: default profile picture not found.";
        }
    }
}

function getclubAdminName($conn, $userID)
{
    $sql = "SELECT firstName FROM clubadmin WHERE userID = ?;";
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

//Function to get club admin's club name
function getClubName($conn, $userID)
{
    $sql = "SELECT clubID FROM clubadmin WHERE userID = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "SQL statement failed: " . $conn->error;
        exit;
    } else {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            $clubID = $row['clubID'];
            $sql = "SELECT clubName FROM club WHERE clubID = ?";
            $stmt = $conn->stmt_init();
            if (!$stmt->prepare($sql)) {
                echo "SQL statement failed: " . $conn->error;
                exit;
            } else {
                $stmt->bind_param("i", $clubID);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                if ($row) {
                    return $row['clubName'];
                } else {
                    return false;
                }
            }
        } else {
            return false;
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
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['teamID'];
    } else {
        $result = false;
        return $result;
    }
}

//Function to get teams from a club

function getTeams($conn, $clubID)
{
    $sql = "SELECT teamID, teamName FROM team WHERE clubID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $clubID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $teams = [];
    while ($row = mysqli_fetch_assoc($resultData)) {
        $teams[] = $row;
    }
    return $teams;
}



//Function to get predicted number of wins, draws and losses for a team

function getLeagueName($conn, $leagueID)
{
    $sql = "SELECT leagueName FROM league WHERE leagueID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $leagueID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row['leagueName'];
    } else {
        $result = false;
        return $result;
    }
}

function getAveragePointPerGame($teamID, $conn)
{
    $sql = "SELECT COUNT(*) AS gamesPlayed
    FROM result
    WHERE homeTeamID = $teamID OR awayTeamID = $teamID;
    ";
    $gamesPlayed = $conn->query($sql);
    $teamWins = getTeamWins($teamID, $conn);
    $teamLosses = getTeamLosses($teamID, $conn);
    $teamDraws = getTeamDraws($teamID, $conn);
    $gamesPlayed = $teamWins + $teamLosses + $teamDraws;

    $averagePointPerGame = (3 * $teamWins + 1 * $teamDraws) / $gamesPlayed;
    return $averagePointPerGame;
}


function getPredictedPoints($teamID, $conn)
{
    $wins = getTeamWins($teamID, $conn);
    $draws = getTeamDraws($teamID, $conn);
    $predictedWins = getPredictedWins($teamID, $conn);
    $predictedDraws = getPredictedDraws($teamID, $conn);
    $predictedPoints = ((3 * $wins) + $draws) + (3 * $predictedWins) + $predictedDraws;
    return $predictedPoints;
}

function getPredictedWins($teamID, $conn)
{
    $fixtures = getFixturesForTeam($teamID, $conn);
    $wins = 0;

    foreach ($fixtures as $fixture) {
        $homeTeamID = $fixture['homeTeamID'];
        $awayTeamID = $fixture['awayTeamID'];

        $homeTeamAvgPoints = getAveragePointPerGame($homeTeamID, $conn);
        $awayTeamAvgPoints = getAveragePointPerGame($awayTeamID, $conn);

        if ($homeTeamAvgPoints > $awayTeamAvgPoints) {
            if ($homeTeamID == $teamID) {
                $wins++;
            }
        } else if ($homeTeamAvgPoints < $awayTeamAvgPoints) {
            if ($awayTeamID == $teamID) {
                $wins++;
            }
        }
    }

    return $wins;
}

function getPredictedDraws($teamID, $conn)
{
    $fixtures = getFixturesForTeam($teamID, $conn);
    $draws = 0;

    foreach ($fixtures as $fixture) {
        $homeTeamID = $fixture['homeTeamID'];
        $awayTeamID = $fixture['awayTeamID'];

        $homeTeamAvgPoints = getAveragePointPerGame($homeTeamID, $conn);
        $awayTeamAvgPoints = getAveragePointPerGame($awayTeamID, $conn);

        if ($homeTeamAvgPoints == $awayTeamAvgPoints) {
            $draws++;
        }
    }

    return $draws;
}

function getPredictedLosses($teamID, $conn)
{
    $fixtures = getFixturesForTeam($teamID, $conn);
    $losses = 0;

    foreach ($fixtures as $fixture) {
        $homeTeamID = $fixture['homeTeamID'];
        $awayTeamID = $fixture['awayTeamID'];

        $homeTeamAvgPoints = getAveragePointPerGame($homeTeamID, $conn);
        $awayTeamAvgPoints = getAveragePointPerGame($awayTeamID, $conn);

        if ($homeTeamAvgPoints < $awayTeamAvgPoints) {
            if ($homeTeamID == $teamID) {
                $losses++;
            }
        } else if ($homeTeamAvgPoints > $awayTeamAvgPoints) {
            if ($awayTeamID == $teamID) {
                $losses++;
            }
        }
    }

    return $losses;
}


function getFixturesForTeam($teamID, $conn)
{
    $sql = "SELECT homeTeamID, awayTeamID FROM fixture WHERE homeTeamID = $teamID OR awayTeamID = $teamID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $fixtures = array();
        while ($row = $result->fetch_assoc()) {
            $fixtures[] = $row;
        }
        return $fixtures;
    } else {
        return null;
    }
}

function getNumberOfGamesLeft($teamID, $conn)
{
    $sql = "SELECT COUNT(*) as totalGames
            FROM fixture
            WHERE (homeTeamID = $teamID OR awayTeamID = $teamID)";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $results = $result->fetch_assoc();
        return $results["totalGames"];
    } else {
        return null;
    }
}

function getTeamWins($teamID, $conn)
{
    $sql = "SELECT homeTeamID, awayTeamID, homeTeamScore, awayTeamScore FROM result WHERE homeTeamID = ? OR awayTeamID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        trigger_error("Prepare statement failed: " . $conn->error, E_USER_ERROR);
    }
    $stmt->bind_param("ii", $teamID, $teamID);
    if (!$stmt->execute()) {
        trigger_error("Execute statement failed: " . $stmt->error, E_USER_ERROR);
    }
    $result = $stmt->get_result();
    $wins = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (isset($row["homeTeamID"]) && isset($row["homeTeamScore"]) && isset($row["awayTeamScore"])) {
                if (
                    $row["homeTeamID"] == $teamID && $row["homeTeamScore"] > $row["awayTeamScore"] ||
                    $row["awayTeamID"] == $teamID && $row["awayTeamScore"] > $row["homeTeamScore"]
                ) {
                    $wins++;
                }
            } else {
                error_log("Error: " . $conn->error . "\n", 3, "error.log");
            }
        }
        return $wins;
    } else {
        return 0;
    }
}


function getTeamDraws($teamID, $conn)
{
    $sql = "SELECT homeTeamID, awayTeamID, homeTeamScore, awayTeamScore FROM result WHERE homeTeamID = ? OR awayTeamID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("getTeamDraws: Error preparing statement: " . $conn->error, 0);
        return 0;
    }
    $stmt->bind_param("ii", $teamID, $teamID);
    if (!$stmt->execute()) {
        error_log("getTeamDraws: Error executing statement: " . $stmt->error, 0);
        return 0;
    }
    $result = $stmt->get_result();
    $draws = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row["homeTeamScore"] == $row["awayTeamScore"]) {
                $draws++;
            }
        }
        return $draws;
    }
    error_log("getTeamDraws: No data found in query result", 0);
    return 0;
}


function getTeamLosses($teamID, $conn)
{
    $sql = "SELECT homeTeamID, awayTeamID, homeTeamScore, awayTeamScore FROM result WHERE homeTeamID = ? OR awayTeamID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Error preparing SQL statement: " . $conn->error);
        return 0;
    }
    $stmt->bind_param("ii", $teamID, $teamID);
    if ($stmt->execute() === false) {
        error_log("Error executing SQL statement: " . $stmt->error);
        return 0;
    }
    $result = $stmt->get_result();
    if ($result === false) {
        error_log("Error getting result: " . $stmt->error);
        return 0;
    }
    $losses = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (
                $row["homeTeamID"] == $teamID && $row["homeTeamScore"] < $row["awayTeamScore"] ||
                $row["awayTeamID"] == $teamID && $row["awayTeamScore"] < $row["homeTeamScore"]
            ) {
                $losses++;
            }
        }
        return $losses;
    }
    return 0;
}

function getPredictedResults($leagueID, $conn)
{
    $query = "SELECT MIN(matchWeek) AS minWeek FROM fixture";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $minWeek = $row['minWeek'];

        $query = "SELECT homeTeamID, awayTeamID FROM fixture WHERE matchWeek = '$minWeek' AND leagueID = '$leagueID'";
        $resultdetailresult = mysqli_query($conn, $query);

        if (mysqli_num_rows($resultdetailresult) > 0) {
            $predictedResults = array();
            while ($row = mysqli_fetch_array($resultdetailresult)) {
                $homeTeamID = $row['homeTeamID'];
                $awayTeamID = $row['awayTeamID'];

                $homeTeamPPG = getAveragePointPerGame($homeTeamID, $conn);
                $awayTeamPPG = getAveragePointPerGame($awayTeamID, $conn);

                $query = "SELECT teamName FROM team WHERE teamID = '$homeTeamID'";
                $result2 = mysqli_query($conn, $query);
                $row2 = mysqli_fetch_array($result2);
                $homeTeamName = $row2['teamName'];

                $query = "SELECT teamName FROM team WHERE teamID = '$awayTeamID'";
                $result3 = mysqli_query($conn, $query);
                $row3 = mysqli_fetch_array($result3);
                $awayTeamName = $row3['teamName'];

                if ($homeTeamPPG > $awayTeamPPG) {
                    $predictedResult = $homeTeamName . " wins";
                } elseif ($awayTeamPPG > $homeTeamPPG) {
                    $predictedResult = $awayTeamName . " wins";
                } else {
                    $predictedResult = "Draw";
                }

                $predictedResults[] = array(

                    "homeTeam" => $homeTeamName,
                    "awayTeam" => $awayTeamName,
                    "predictedResult" => $predictedResult
                );
            }
            return $predictedResults;
        } else {
            return "No fixtures with the minimum game week number found";
        }
    } else {
        return "No game week numbers found in the result table";
    }
}
function calculatePointsPerWeek($teamID, $conn)
{
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
    $teamWins = getTeamWins($teamID, $conn);
    $teamDraws = getTeamDraws($teamID, $conn);
    $startingPoints = $teamWins * 3 + $teamDraws;

    // Initialize an array to store the team's points per week
    $pointsPerWeek = [];

    // Loop through every game week
    for ($i = $minGWID; $i <= $maxGWID; $i++) {
        // Get the oppositionID and the team's pointPerGame
        $query = "SELECT homeTeamID, awayTeamID FROM fixture WHERE (homeTeamID = '$teamID' OR awayTeamID = '$teamID') AND matchWeek = '$i'";
        $result = mysqli_query($conn, $query);
        $fixtureData = mysqli_fetch_assoc($result);
        if ($fixtureData['homeTeamID'] == $teamID) {
            $oppositionID = $fixtureData['awayTeamID'];
        } else {
            $oppositionID = $fixtureData['homeTeamID'];
        }
        $teamPointPerGame = getAveragePointPerGame($teamID, $conn);

        // Get the opposition's pointPerGame
        $oppositionPointPerGame = getAveragePointPerGame($oppositionID, $conn);

        // Decide the points based on the team's and opposition's pointsPerGame
        if ($teamPointPerGame > $oppositionPointPerGame) {
            $startingPoints += 3;
        } elseif ($teamPointPerGame == $oppositionPointPerGame) {
            $startingPoints += 1;
        }
        $pointsPerWeek[] = [$i, $startingPoints];
    }
    return $pointsPerWeek;
}

//Function to get the amount of points a team has
function getTeamPoints($teams, $conn)
{
    $points = [];
    foreach ($teams as $team) {
        $teamID = $team["teamID"];
        $teamWins = getTeamWins($teamID, $conn);
        $teamDraws = getTeamDraws($teamID, $conn);

        $teamPoints = $teamWins * 3 + $teamDraws;
        $points[] = [
            "teamID" => $teamID,
            "teamName" => $team["teamName"],
            "points" => $teamPoints
        ];
    }

    // Sort the teams based on their points
    usort($points, function ($a, $b) {
        return $b["points"] - $a["points"];
    });

    return $points;
}

//Function to get a team's goal difference

function getTeamGoalDifference($teamID, $conn)
{
    //Get the amount of goals the team has scored and conceded
    $teamGoalsScored = getTeamGoalsScored($teamID, $conn);
    $teamGoalsConceded = getTeamGoalsConceded($teamID, $conn);

    //Calculate the goal difference
    $teamGoalDifference = $teamGoalsScored - $teamGoalsConceded;

    return $teamGoalDifference;
}

//Function to get a team's goals scored

function getTeamGoalsScored($teamID, $conn)
{
    //Get the amount of goals the team has scored
    $query = "SELECT SUM(homeTeamScore) as homeTeamScore, SUM(awayTeamScore) as awayTeamScore FROM result WHERE homeTeamID = '$teamID' OR awayTeamID = '$teamID'";
    $result = mysqli_query($conn, $query);
    $teamGoals = mysqli_fetch_assoc($result);
    $teamGoalsScored = $teamGoals['homeTeamScore'] + $teamGoals['awayTeamScore'];

    return $teamGoalsScored;
}

//Function to get a team's goals conceded

function getTeamGoalsConceded($teamID, $conn)
{
    //Get the amount of goals the team has conceded
    $query = "SELECT SUM(IF(homeTeamID = '$teamID', awayTeamScore, homeTeamScore)) as goalsConceded FROM result WHERE homeTeamID = '$teamID' OR awayTeamID = '$teamID'";
    $result = mysqli_query($conn, $query);
    $teamGoals = mysqli_fetch_assoc($result);
    $teamGoalsConceded = $teamGoals['goalsConceded'];

    return $teamGoalsConceded;
}


//Function to get the leagueID based on the playerID
function getLeagueID($userID, $conn)
{
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

function getTopTeamLeague($clubID, $conn)
{
    //Get the lowest league ID for the club
    $query = "SELECT MIN(leagueID) as lowestLeagueID FROM team WHERE clubID = '$clubID'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $lowestLeagueID = $row['lowestLeagueID'];

    return $lowestLeagueID;
}

function getAvailablePlayersByFixtureID($fixtureID, $conn)
{
    $players = array();

    $query = "SELECT playerID FROM availability WHERE fixtureID = $fixtureID AND available = 1 AND playerID IN (SELECT playerID FROM player WHERE teamID = (SELECT homeTeamID FROM fixture WHERE fixtureID = $fixtureID) OR teamID = (SELECT awayTeamID FROM fixture WHERE fixtureID = $fixtureID))";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $players[] = array('playerID' => $row['playerID']);
    }

    return $players;
}

function getTeamNameByID($teamID, $conn)
{
    $teamName = "";

    $query = "SELECT teamName FROM team WHERE teamID = $teamID";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $teamName = $row['teamName'];

    return $teamName;
}

function getFixturesByTeamID($teamID, $conn)
{
    $fixtures = array();

    $query = "SELECT fixtureID, homeTeamID, awayTeamID FROM fixture WHERE homeTeamID = $teamID OR awayTeamID = $teamID";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $fixtures[] = $row;
    }

    return $fixtures;
}

function getUnavailablePlayersByFixtureID($fixtureID, $conn)
{
    $players = array();

    $query = "SELECT playerID FROM availability WHERE fixtureID = $fixtureID AND available = 0 AND playerID IN (SELECT playerID FROM player WHERE teamID = (SELECT homeTeamID FROM fixture WHERE fixtureID = $fixtureID) OR teamID = (SELECT awayTeamID FROM fixture WHERE fixtureID = $fixtureID))";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $players[] = array('playerID' => $row['playerID']);
    }

    return $players;
}

function getUnansweredPlayersByFixtureID($fixtureID, $clubID, $conn)
{
    $players = array();

    $query = "SELECT playerID FROM player WHERE playerID NOT IN (SELECT playerID FROM availability WHERE fixtureID = $fixtureID) AND playerID IN (SELECT playerID FROM player WHERE teamID = (SELECT homeTeamID FROM fixture WHERE fixtureID = $fixtureID) OR teamID = (SELECT awayTeamID FROM fixture WHERE fixtureID = $fixtureID))";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $players[] = array('playerID' => $row['playerID']);
    }

    return $players;
}

function getPlayerNameByID($playerID, $conn)
{
    $query = "SELECT firstName, lastName FROM player WHERE playerID = $playerID";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        return $firstName . " " . $lastName;
    } else {
        return "";
    }
}

//Update goals scored for a player

function updateGoals($playerID, $goals, $conn)
{
    $query = "SELECT * FROM goal WHERE playerID = $playerID";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO goal (playerID, numOfGoals) VALUES ($playerID, $goals)";
    } else {
        $query = "UPDATE goal SET numOfGoals = numOfGoals + $goals WHERE playerID = $playerID";
    }
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }
}

//Update assists for a player

function updateAssists($playerID, $assists, $conn)
{
    $query = "SELECT * FROM assist WHERE playerID = $playerID";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO assist (playerID, numOfAssists) VALUES ($playerID, $assists)";
    } else {
        $query = "UPDATE assist SET numOfAssists = numOfAssists + $assists WHERE playerID = $playerID";
    }
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }
}
/*

TODO:
Add a check to see if clubs have a club admin already
Add validation email to club admin only which is sent to a system admin to approve
OUTPUT ERROR MESSAGES ON SCREEN INSTEAD OF REDIRECTING TO LOGIN PAGE
ADD FOREIGN KEYS TO DATABASE
ALLOW LEAGUE TABLE HEADER SORTS
Maybe make email to user when they are added to a club
Make player next game actually be the next game and not a past game or a futher future game
Tidy up comments in playerDashboard.php
Check if club admin league table is for the top team and not just outputting all teams within the club
Make all files saved in the database of type .jpg
MAYBE MAKE IT PASS PLAYERID AND CLUBADMINID ISNTEAD OF USERID ON DASHBOARDS TO ALLOW OTHERS TO VIEW OTHERS DASHBOARDS - maybe store player and club ID in header
FIX GOAL DIFFERENCE ON LEAGUE TABLESSSS
*/
