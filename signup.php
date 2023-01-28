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

            <form action="includes/signup.inc.php">
              <!-- Player or club admin radio buttons -->
              <p class="title">Account type</p>
              <input type="radio" id="Player" name="accountType" value="Player" />
              <label for="Player">Player</label><br />
              <input type="radio" id="Club Admin" name="accountType" value="Club Admin" />
              <label for="Club Admin">Club Admin</label><br />
              <!-- Email -->
              <p class="title">Email</p>
              <input type="email" id="email" class="club-player-search" />
              <!-- Password and confirm password -->
              <div class="fieldBlock">
                <div class="title">Password</div>
                <input type="password" id="password" class="passwords-textbox" />
              </div>

              <div class="fieldBlock">
                <div class="title">Confirm password</div>
                <input type="password" id="confirmPassword" class="passwords-textbox" />
              </div>
              <!-- Forgot password link -->
              <a class="sub-heading" style="text-align: right" href="#">Forgot password?</a>

              <!-- First name -->
              <p class="title">First name</p>
              <input type="text" id="firstName" class="passwords-textbox" />
              <!-- Last name -->
              <p class="title">Last name</p>
              <input type="text" id="lastName" class="passwords-textbox" />
              <!-- Club -->
              <p class="title">Club</p>
              <input type="text" id="club" class="passwords-textbox" />
              <!-- Date of birth -->
              <p class="title">Date of birth</p>
              <input type="datetime" />
              <br>
              <br>
              <!-- Signup button -->
              <button type="submit" name="submit" class="btn btn-login">Signup</button>
            </form>
            <!-- Login link -->
            <a class="sub-heading" style="display: block; text-align: center" href="login.php">Already have an account? Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>