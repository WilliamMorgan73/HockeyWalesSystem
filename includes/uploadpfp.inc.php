<?php

// User ID
session_start();
$userID = $_SESSION['userID'];

// Where the file is going to be placed 
$target_dir = "../images/pfp/";

// Find the file extension of the uploaded file
$file_extension = pathinfo($_FILES['uploadedfile']['name'], PATHINFO_EXTENSION); 

// Loop through all possible file extensions and delete the file with the same name if it exists
$possible_extensions = array("png", "jpg", "jpeg");
foreach ($possible_extensions as $extension) {
    // Check if a file with the same name and the current extension exists
    $path = $target_dir . $userID . "." . $extension;
    if (file_exists($path)) {
        // If it exists, delete the file
        unlink($path);
        break;
    }
}

// Add the user ID and file extension to the target path
$target_path = $target_dir . $userID . "." . $file_extension;

// Move the uploaded file to its target path
if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    // Refresh the page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo "There was an error uploading the file, please try again!";
}

?>
