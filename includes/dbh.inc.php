<?php

// This file contains the database access information. This file also establishes a connection to MySQL and selects the database.

// Set the database access information as constants:
$serverName = "localhost";
$userName = "root";
$password = "";
$databaseName = "hockeywales";

// Make the connection:
$connection = mysqli_connect($serverName, $userName, $password, $databaseName);
