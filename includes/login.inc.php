<?php
session_set_cookie_params(0);
session_start(); //This begins the PHP session.
include ("dbh.inc.php");

if (isset($_POST['email']) && isset($_POST['password'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        return $data;
    }
    $Email = validate($_POST['email']);
    $Password = validate($_POST['password']);

    if (empty($Email)) {
        header("Location: ../index.php?error=Email is required");
        exit();
    } else if (empty($Password)) {
        header("Location: ../index.php?error=Password is required");
        exit();
    } else {
        # Don't write a query this way as it can lead to SQL injection
        # $sql = "SELECT * FROM Login_Details WHERE Email='$Email' AND Password='$Password'";

        # 1. Replace variables with '?' placeholders
        $sql = 'SELECT * FROM user WHERE email=? AND password=?';

        # 2. Prepare the statement
        $stmt = $conn->prepare($sql);

        # 3. Bind the parameters to the variables in the order of the placeholders

        $stmt->bind_param('ss', $Email, $Password);

        # 4. Execute the statement
        $stmt->execute();

        # 5. Get the result
        # $result = mysqli_query($conn, $sql);
        $result = $stmt->get_result();

        if (mysqli_num_rows($result) === 1) {
            # We have a match so no need to re-check the email and password
            $row = mysqli_fetch_assoc($result);

            # Redirects the user to the dashboard
            $_SESSION['timestamp'] = time();
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: ../index.php?error=Incorect User name or Password"); # Redirects the user back to the login page and displays an error message
            exit();
        }
    }
} else {
    header("Location: ../wdadwd.php"); //Code is currently directing me here when correct or incorrect login details are entered
    exit();
}
