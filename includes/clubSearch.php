<?php
function binarySearch($arr, $clubName, $left, $right)
{
    if ($left > $right) {
        return false;
    }

    $middle = floor(($left + $right) / 2);
    if ($arr[$middle] == $clubName) {
        return true;
    } else if ($arr[$middle] > $clubName) {
        return binarySearch($arr, $clubName, $left, $middle - 1);
    } else {
        return binarySearch($arr, $clubName, $middle + 1, $right);
    }
}

// Connect to the database
$host = "localhost";
$dbname = "hockeywales";
$dbusername = "root";
$dbpassword = "";

$conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);

// Retrieve the list of club names from the database
$clubs = array();
$stmt = $conn->prepare("SELECT clubName FROM club ORDER BY clubName");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $clubs[] = $row['clubName'];
}

$clubName = "";
if (isset($_POST['clubName'])) {
    $clubName = $_POST['clubName'];
}

$index = binarySearch($clubs, $clubName, 0, count($clubs) - 1);

if ($index == false) {
    echo "Club not found in the database.";
    header("Location: index.php?error=clubNotFound");
    header("Location: ../index.php?error=clubNotFound&message=" . urlencode("The club you entered could not be found"));
} else {
    echo "Club found in the database.";
    // Retrieve the clubID from the database using the clubName
    $stmt = $conn->prepare("SELECT clubID FROM club WHERE clubName = :clubName");
    $stmt->bindParam(":clubName", $clubName);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $clubID = $row['clubID'];

    echo "
            <form id='club-dashboard-form' action='../clubDashboard.php' method='post'>
                <input type='hidden' name='clubID' value='$clubID'>
            </form>
            <script>
                document.getElementById('club-dashboard-form').submit();
            </script>
        ";
}
