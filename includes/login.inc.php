<?
include("dbh.inc.php");
session_start();
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['email'] = $email;
        header("Location: index.php");
    } else {
        echo "Incorrect email or password";
    }
}


