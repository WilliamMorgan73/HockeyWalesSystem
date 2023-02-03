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
              Please pick the league you wish to view, or search for a player
              or team
            </p>
            <!-- Club search -->
            <p class="title">Club search</p>
            <form>
              <input type="text" class="club-player-search" />
            </form>
            <!-- Player search -->
            <p class="title">Player search</p>
            <form>
              <input type="text" class="club-player-search" />
            </form>
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