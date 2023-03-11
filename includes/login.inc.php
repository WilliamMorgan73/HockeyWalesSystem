<?php
// Path: includes\login.inc.php

require('functions.inc.php');
    
//Variables
$email = $_POST['email'];
$password = $_POST['password'];

if (isset($_POST["submit"])){

    //Function call to check for empty fields

    if (emptyInputLogin($email, $password) !== false) {
        header("Location: ../login.php?error=emptyinput&message=" . urlencode("Please fill all fields"));
        exit();
    }

    //Function call to login user

    loginUser($email, $password);
}
else{
    header("Location: ../login.php?error=incorrectdetails&message=" . urlencode("Incorrect email or password"));
    exit();
}