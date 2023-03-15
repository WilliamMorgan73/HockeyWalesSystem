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
              Enter your email here to send a password change request to your club admin.
              Already sent? Enter your email to be able to reset your password.
            </p>
            <!-- Change password form -->
            <form action="includes/passwordChangeRequest.inc.php" method="post">
              <!-- Email -->
              <p class="title">Enter your email</p>
              <input type="email" id="email" name="email" class="club-player-search" />
              <!-- Know password -->
              <a class="sub-heading" style="display: block; text-align: right;" href="login.php">Know your password?</a>

              <!-- Error message -->
              <?php
              if (isset($_GET['error'])) {
                switch ($_GET['error']) {
                  case 'emptyinput':
                    echo '<p class="title" style="text-align: center; padding: 2% 0% 2% 0%;">Please enter an email address.</p>';
                    break;
                  case 'emailnotfound':
                    echo '<p class="title" style="text-align: center; padding: 2% 0% 2% 0%;">This email address is not registered with us. Please try again or sign up for an account.</p>';
                    break;
                  case 'requestalreadyexists':
                    echo '<p class="title" style="text-align: center; padding: 2% 0% 2% 0%;">A password change request has already been sent for this email address. Please wait for an admin to approve it.</p>';
                    break;
                  case 'stmtfailed':
                    echo '<p class="title" style="text-align: center; padding: 2% 0% 2% 0%;">Something went wrong. Please try again later.</p>';
                    break;
                  case 'requestsubmitted':
                    echo '<p class="title" style="text-align: center; padding: 2% 0% 2% 0%;">Your password change request has been sent to an admin. Please wait for them to approve it.</p>';
                    break;
                }
              }
              ?>

              <!-- Change password button -->
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