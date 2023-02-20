<?php
// Path: includes\addLeague.php
require('functions.inc.php');

//Variables

$leagueName = $_POST['leagueName'];


$conn = require __DIR__ . '/dbhconfig.php';

if (isset($_POST['submitLeague'])) {
    //Function call to check for empty fields
    if (empty($leagueName)) {
        header("Location: ../systemAdminDashboard.php?error=emptyinputLeague&message=" . urlencode("Please fill in all fields."));
        exit();
    }

    //Add League league to database

    createLeague($leagueName);
    //Output success message
    header("Location: ../systemAdminDashboard.php?error=leaguecreated&message=" . urlencode("System Admin added successfully."));

}
