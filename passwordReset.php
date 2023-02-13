<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot password</title>
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
              Enter a new password here to change your password
              <!-- Change password form -->
            <form action="#" method="post">
              <!-- Password -->
              <p class="title">Password</p>
              <input type="password" id="password" name="password" class="club-player-search" />
              <!-- Confirm password -->
              <p class="title">Confirm password</p>
              <input type="password" id="confirmPassword" name="confirmPassword" class="club-player-search" />
              <!-- Error message -->
              <?php
              //Empty input
              
              //Password doesn't match
              ?>

              <!-- Login button -->
              <button type="submit" name="submit" class="btn btn-login">Change password</button>
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