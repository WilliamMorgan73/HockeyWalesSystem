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
    $conn->begin_transaction();

    $sql = "INSERT INTO tempUser (email, password, accountType) 
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

    $sql = "INSERT INTO tempPlayer (firstName, lastName, clubID, DOB, tempUserID) 
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
            header("Location: ../clubAdminDashboard.php");
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

function userPfpCheck($userID)
{
    $pfpPath = "images/pfp/" . $userID . ".*";
    $matchingFiles = glob($pfpPath);
    if (!empty($matchingFiles)) {
        // Display the user's pfp
        echo '<img class="img-circle elevation-2" src="' . $matchingFiles[0] . '" alt="User Avatar" />';
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
                echo '<img class="img-circle elevation-2" src="' . $newPfpPath . '" alt="User Avatar" />';
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
    $results = array();
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
    $results = array();
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
    $results = array();
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
            }
            return $wins;
        }
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

function getPredictedResultsPerWeek($leagueID, $conn)
{
    error_log("[".date("Y-m-d H:i:s")."] getPredictedResultsPerWeek called with leagueID: $leagueID\n", 3, "/var/log/my-errors.log");
    $query = "SELECT MIN(matchWeek) AS minWeek, MAX(matchWeek) AS maxWeek FROM fixture";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $minWeek = $row['minWeek'];
        $maxWeek = $row['maxWeek'];
        $pointsPerGameWeek = array();

        for ($week = $minWeek; $week <= $maxWeek; $week++) {
            $query = "SELECT homeTeamID, awayTeamID FROM fixture WHERE matchWeek = '$week' AND leagueID = '$leagueID'";
            $resultDetailResult = mysqli_query($conn, $query);

            if (mysqli_num_rows($resultDetailResult) > 0) {
                while ($row = mysqli_fetch_array($resultDetailResult)) {
                    $homeTeamID = $row['homeTeamID'];
                    $awayTeamID = $row['awayTeamID'];

                    $homeTeamPPG = getAveragePointPerGame($homeTeamID, $conn);
                    $awayTeamPPG = getAveragePointPerGame($awayTeamID, $conn);

                    if ($homeTeamPPG > $awayTeamPPG) {
                        $homeTeamPoints = 3;
                        $awayTeamPoints = 0;
                    } elseif ($homeTeamPPG < $awayTeamPPG) {
                        $homeTeamPoints = 0;
                        $awayTeamPoints = 3;
                    } else {
                        $homeTeamPoints = 1;
                        $awayTeamPoints = 1;
                    }

                    if (isset($pointsPerGameWeek[$homeTeamID])) {
                        $pointsPerGameWeek[$homeTeamID][$week] = $homeTeamPoints + $pointsPerGameWeek[$homeTeamID][$week - 1];
                    } else {
                        $pointsPerGameWeek[$homeTeamID][$week] = $homeTeamPoints;
                    }

                    if (isset($pointsPerGameWeek[$awayTeamID])) {
                        $pointsPerGameWeek[$awayTeamID][$week] = $awayTeamPoints + $pointsPerGameWeek[$awayTeamID][$week - 1];
                    } else {
                        $pointsPerGameWeek[$awayTeamID][$week] = $awayTeamPoints;
                    }

                    $homeTeamPPG = ($homeTeamPPG + $homeTeamPoints) / 2;
                    $awayTeamPPG = ($awayTeamPPG + $awayTeamPoints) / 2;

                    updateAveragePointPerGame($homeTeamID, $homeTeamPPG, $conn);
                    updateAveragePointPerGame($awayTeamID, $awayTeamPPG, $conn);
                }
            }
        }

        return $pointsPerGameWeek;
    } else {
        return "No game week numbers found in the result table";
    }
}

function updateAveragePointPerGame($teamID, $result, $conn)
{
    error_log("[".date("Y-m-d H:i:s")."] updateAveragePointPerGame called with teamID: $teamID, result: $result\n", 3, "/var/log/my-errors.log");
    $teamWins = getTeamWins($teamID, $conn);
    $teamDraws = getTeamDraws($teamID, $conn);
    $teamLosses = getTeamLosses($teamID, $conn);
    $gamesPlayed = $teamWins + $teamDraws + $teamLosses;

    if ($result == "win") {
        $teamWins += 1;
    } elseif ($result == "draw") {
        $teamDraws += 1;
    } elseif ($result == "loss") {
        $teamLosses += 1;
    }

    $averagePointPerGame = (3 * $teamWins + 1 * $teamDraws) / ($gamesPlayed + 1);

    return $averagePointPerGame;
}


/*

TODO:
Add length checks to all fields that need them, can pass maxium and minimum lengths as parameters
Maybe password strength check
Add a check to see if clubs have a club admin already
Add validation email to club admin only which is sent to me to approve
OUTPUT ERROR MESSAGES ON SCREEN INSTEAD OF REDIRECTING TO LOGIN PAGE
ADD FOREIGN KEYS TO DATABASE
ALLOW LEAGUE TABLE HEADER SORTS
Combine functions such as getplayername and getclubname into one function
Make signup functions not work if an error occurs in the second query as atm it will still add the user to the database
On index make league buttons be outputted from database instead of hard coded
Maybe make email to user when they are added to a club
On player approval : make rows work when more than 3 players are added to a club - Same with clubs in league
Make player next game actually be the next game and not a past game or a futher future game
Add dummy data to player and club dashboard
Tidy up comments in playerDashboard.php
Output team logos on player dashboard in background of profiel stat thing aswell as in all league tables
Make all buttons that you are currently on have this code: <a style="cursor:pointer" class="nav-link active">
When uploading a pfp make it so that it is the same size as the default pfp and make it so that it is a circle and delete the old pfp
Check if club admin league table is for the top team and not just outputting all teams within the club
Make all files saved in the database of type .jpg
*/
