<?php
// Path: includes\addClub.php

require('functions.inc.php');

$conn = require __DIR__ . '/dbhconfig.php';

//Variables

$clubName = $_POST['clubName'];

if (isset($_POST['submitClub'])) {
    //Presence check
    if (empty($clubName)) {
        header("Location: ../systemAdminDashboard.php?error=emptyinputClub&message=" . urlencode("Please fill in all fields."));
        exit();
    }

    // Add club to database
    createClub($clubName);

    // Get the ID of the newly inserted club
    $clubID = mysqli_insert_id($conn);

    // Save the club logo with the club ID as the file name
    $file_extension = pathinfo($_FILES['clubLogo']['name'], PATHINFO_EXTENSION);
    $target_dir = "../images/clubLogos/";
    $target_path = $target_dir . $clubID . "." . $file_extension;
    if (move_uploaded_file($_FILES['clubLogo']['tmp_name'], $target_path)) {
        // If the file upload is successful, redirect the user
        header("Location: ../systemAdminDashboard.php?error=clubcreated&message=" . urlencode("Club created"));
        exit;
    } else {
        echo "There was an error uploading the file, please try again!";
    }
}
