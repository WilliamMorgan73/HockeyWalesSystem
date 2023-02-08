<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
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
              Login as either a player or a club admin
              <!-- Login form -->
            <form action="includes/login.inc.php" method="post">
              <!-- Email -->
              <p class="title">Email</p>
              <input type="email" id="email" name="email" class="club-player-search" />
              <!-- Password -->
              <p class="title">Password</p>
              <input type="password" id="password" name="password" class="club-player-search" />
              <!-- Forgot password link -->
              <a class="sub-heading" style="display: block; text-align: right;" href="forgotPassword.php">Forgot password?</a>

              <!-- Error message -->
              <?php
              //Empty input
              if (isset($_GET['error']) && $_GET['error'] === "emptyinput") {
                $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
              }

              //Incorrect details
              if (isset($_GET['error']) && $_GET['error'] === "incorrectdetails") {
                $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
              }
              ?>

              <!-- Login button -->
              <button type="submit" name="submit" class="btn btn-login">Login</button>
            </form>
            <!-- Signup link -->
            <a class="sub-heading" style="display: block; text-align: center" href="signup.php">Need an account? Signup</a>
            <a class="sub-heading" style="display: block; text-align: center" href="index.php">View leagues</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>