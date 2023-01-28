<?php

// This file contains the database access information. This file also establishes a connection to MySQL and selects the database.

// Set the database access information as constants:
$serverName = "localhost";
$userName = "root";
$password = "";
$databaseName = "hockeywales";

// Make the connection:
$connection = mysqli_connect($serverName, $userName, $password, $databaseName);

// If no connection could be made, trigger an error:
if (!$connection) {
    trigger_error('Could not connect to MySQL: ' . mysqli_connect_error());
}
