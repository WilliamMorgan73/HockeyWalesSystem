<!--
PHP intergration
-->
<?php
$conn = require 'includes/dbhconfig.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>League selection</title>
  <!-- Bootstrap Css -->
  <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" />
  <link rel="stylesheet" href="css/bootstrapIcons/bootstrap-icons.css" />
  <!-- Custom Css -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-5 image-divide">
        <!-- Left side content here -->
      </div>
      <div class="col-md-7">
        <div class="card right-col">
          <div class="card-body">
            <!-- Heading and subheading-->
            <p class="heading">Hockey Wales</p>
            <p class="sub-heading">
              Please pick the league you wish to view, or search for a team.
            </p>
            <!-- Club search -->
            <p class="title">Club search</p>
            <form action="includes/clubSearch.php" method="post">
              <input type="text" name="clubName" class="club-player-search"/ style="margin-bottom: 2%;">
              <input type="submit" value="Search" class="btn btn-login">
            </form>
            <!-- Error message -->
            <?php
            //Club not found
            if (isset($_GET['error']) && $_GET['error'] === "clubNotFound") {
              $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
              echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
            }
            ?>
            <!-- League selection -->
            <p class="title">Leagues</p>
            <form action="leagueDashboard.php" method="post">
              <?php
              $query = "SELECT * FROM league";
              $result = mysqli_query($conn, $query);
              while ($row = mysqli_fetch_array($result)) {
              ?>
                <button type="submit" name="leagueID" value="<?php echo $row['leagueID']; ?>" class="btn btn-league">
                  <?php echo $row['leagueName']; ?>
                </button>
                <br />
              <?php
              }
              ?>
            </form>

            <!-- Login/Signup link -->

            <a class="sub-heading" style="display: block; text-align: center" href="login.php">Login/signup</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>