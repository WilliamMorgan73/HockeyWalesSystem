<?php
//Path: includes\availability.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// This script is used to challenge the result of a match
$conn = require __DIR__ . '/dbhconfig.php';


// Get the playerID and fixtureID from the form submission
$playerID = $_POST['playerID'];
$fixtureID = $_POST['fixtureID'];
$available = $_POST['available'];


// Check if the connection to the database is working correctly
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the player has an availability for this fixture already
$check_query = "SELECT * FROM availability WHERE playerID = '$playerID' AND fixtureID = '$fixtureID'";
$check_result = mysqli_query($conn, $check_query);
$check_count = mysqli_num_rows($check_result);


// If the player doesn't have an availability for this fixture, create one
if ($check_count == 0) {
    $insert_query = "INSERT INTO availability (playerID, fixtureID, available) VALUES ('$playerID', '$fixtureID', '$available')";
    $insert_result = mysqli_query($conn, $insert_query);

    // Check if the insert was successful
    if ($insert_result) {
        echo "Availability created successfully.";
    } else {
        echo "Error creating availability: " . mysqli_error($conn);
    }
} else {
    // If the player has an availability for this fixture, update it to 'available'
    $update_query = "UPDATE availability SET available = '$available' WHERE playerID = '$playerID' AND fixtureID = '$fixtureID'";
    $update_result = mysqli_query($conn, $update_query);

    // Check if the update was successful
    if ($update_result) {
        echo "Availability updated successfully.";
    } else {
        echo "Error updating availability: " . mysqli_error($conn);
    }
}

header("Location: ../playerFixtures.php");