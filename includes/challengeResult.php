<?php
//Path: includes\challengeResult.php

// This script is used to challenge the result of a match
$conn = require __DIR__ . '/dbhconfig.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve the data from the form
  $homeTeamID = $_POST['homeTeamID'];
  $awayTeamID = $_POST['awayTeamID'];

  // Update the status of the tempresult to "challenged"
  $query = "UPDATE tempresult
            SET status = 'challenged'
            WHERE homeTeamID = $homeTeamID AND awayTeamID = $awayTeamID";
  mysqli_query($conn, $query);

  // Redirect the user back to the result approval page
  header('Location: ../resultApproval.php');
  exit;
}
