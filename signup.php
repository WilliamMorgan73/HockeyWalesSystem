<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Signup</title>
  <!-- Bootstrap Css -->
  <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" />
  <link rel="stylesheet" href="css/bootstrapIcons/bootstrap-icons.css" />
  <!-- Custom Css -->
  <link rel="stylesheet" href="css/style.css" />
  <!-- JQuery -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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
              Signup as either a player or a club admin
              <!-- Signup form -->
            </p>

            <form action="includes/signup.inc.php" method="post">
              <!-- Player or club admin radio buttons -->
              <p class="title">Account type</p>
              <input class="form-check-input" type="radio" id="Player" name="accountType" value="Player" checked />
              <label for="Player">Player</label><br />
              <input class="form-check-input" type="radio" id="Club Admin" name="accountType" value="Club Admin" />
              <label for="Club Admin">Club Admin</label><br />
              <!-- Email -->
              <p class="title" style=" padding-top:2%">Email</p>
              <input type="email" name="email" id="email" class="form-control club-player-search" />
              <!-- Password and confirm password -->
              <div class="row">
                <div class="col-md-6">
                  <div class="title">Password</div>
                  <input type="password" name="password" id="password" class="form-control club-player-search" />
                </div>
                <!-- Confirm password -->
                <div class="col-md-6">
                  <div class="title">Confirm password</div>
                  <input type="password" name="confirmPassword" id="confirmPassword" class="form-control club-player-search" />
                </div>
              </div>

              <!-- First name and last name-->
              <div class="row">
                <div class="col-md-6">
                  <p class="title">First name</p>
                  <input type="text" id="firstName" name="firstName" class="form-control club-player-search" />
                </div>
                <!-- Last name -->
                <div class="col-md-6">
                  <p class="title">Last name</p>
                  <input type="text" id="lastName" name="lastName" class="form-control club-player-search" />
                </div>
              </div>
              <!-- Club and DOB-->
              <div class="row">
                <div class="col-md-6">
                  <p class="title">Club</p>
                  <input type="text" id="club" name="club" class="form-control club-player-search" />
                </div>
                <!-- Date of birth -->
                <div class="col-md-6">
                  <div class="form-group">
                    <p class="title">Date of birth</p>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="bi bi-calendar-date-fill"></i></span>
                      </div>
                      <input type="text" id="datepicker" name="DOB" class="form-control club-player-search">
                    </div>
                  </div>

                </div>
              </div>
              <!-- Error message -->
              <?php
              //Empty input
              if (isset($_GET['error']) && $_GET['error'] === "emptyinput") {
                $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
              }
              //Password mismatch
              else if (isset($_GET['error']) && $_GET['error'] === "passwordsdontmatch") {
                $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
              }
              //Email already exists
              else if (isset($_GET['error']) && $_GET['error'] === "emailalreadyexists") {
                $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
              }
              //Club doesn't exist
              else if (isset($_GET['error']) && $_GET['error'] === "clubdoesntexist") {
                $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
              }
              //Fields incorrect length
              else if (isset($_GET['error']) && $_GET['error'] === "fieldlength") {
                $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
              }
              //Player account created, waiting for admin approval
              else if (isset($_GET['error']) && $_GET['error'] === "playeraccountcreated") {
                $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
              }
              //Email exists in temp table
              else if (isset($_GET['error']) && $_GET['error'] === "pendingapproval") {
                $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
              }
              ?>


              <!-- End of error message -->
              <!-- Signup button -->
              <button type="submit" name="submit" class="btn btn-login">Signup</button>
            </form>
            <!-- Login link -->
            <a class="sub-heading" style="display: block; text-align: center" href="login.php">Already have an account? Login</a>
            <a class="sub-heading" style="display: block; text-align: center" href="index.php">View leagues</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="js/jquery/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!-- Bootstrap 4 -->
  <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="js/adminlte/adminlte.min.js"></script>

  <script>
    $(function() {
      var sixteenYearsAgo = new Date();
      var hundredYearsAgo = new Date();
      sixteenYearsAgo.setFullYear(sixteenYearsAgo.getFullYear() - 16)
      hundredYearsAgo.setFullYear(hundredYearsAgo.getFullYear() - 100)

      $("#datepicker").datepicker({
        maxDate: sixteenYearsAgo,
        minDate: hundredYearsAgo,
        dateFormat: "yy-mm-dd",
      });
    });
  </script>
</body>

</html>