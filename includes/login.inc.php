<?php
// Path: includes\login.inc.php

require('functions.inc.php');
    
//Variables
$email = $_POST['email'];
$password = $_POST['password'];

if (isset($_POST["submit"])){

    $conn = require __DIR__ . '/dbhconfig.php';

    //Function call to check for empty fields

    if (emptyInputLogin($email, $password) !== false) {
        header("Location: ../login.php?error=emptyinput");
        exit();
    }

    //Function call to login user

    loginUser($conn, $email, $password);
}
else{
    header("Location: ../login.php");
    exit();
}