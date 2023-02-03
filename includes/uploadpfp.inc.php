<?php

// User ID
session_start();
$userID = $_SESSION['userID'];

// Where the file is going to be placed 
$target_path = "../images/pfp/";

/* Add the user ID and original file extension to the target path.  
Result is "../images/pfp/userID.extension" */
$target_path = $target_path . $userID . "." . pathinfo($_FILES['uploadedfile']['name'], PATHINFO_EXTENSION); 

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
    " has been uploaded and renamed to " . $userID . "." . pathinfo($_FILES['uploadedfile']['name'], PATHINFO_EXTENSION);
} else{
    echo "There was an error uploading the file, please try again!";
}

?>
