<?php
?>
<!DOCTYPE html>
<!--
PHP intergration
-->
<?php
require_once('includes/functions.inc.php');
$conn = require 'includes/dbhconfig.php';

session_start();
$userID = $_SESSION['userID'];

?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>System admin dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="css/adminlte/adminlte.min.css" />
    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="css/bootstrapIcons/bootstrap-icons.css" />
</head>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-danger elevation-4">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
                <img src="images/hw_feathers2.png" style="width:25%;">
                <span class="brand-text font-weight-bolder">System admin</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="bi bi-list nav-icon"></i>
                                <p>Back</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb">
                        <div class="col-sm">
                            <h1 class="m-0">Manage the whole system from here</h1>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="card card-solid">
                    <div class="card-body-0">
                        <div class="container-fluid">
                            <div class="row" style="padding-top:1%;">
                                <!-- Club admin approval -->
                                <div class="col-md-12">
                                    <div class="card shadow" style="width: 100%">
                                        <div class="card-body">
                                            <h5 class="card-title">Club admin approval</h5>
                                            <br />
                                            <?php
                                            // Query to get all the club admins that are waiting to be approved so the system admin can approve them or deny them
                                            $query = "SELECT * FROM tempclubadmin";
                                            $result = mysqli_query($conn, $query);
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    $tempUserID = $row['tempUserID'];
                                                    $tempUserQuery = "SELECT email FROM tempUser WHERE tempUserID = '$tempUserID'";
                                                    $tempUserResult = mysqli_query($conn, $tempUserQuery);
                                                    $tempUserRow = mysqli_fetch_array($tempUserResult);

                                                    $clubID = $row['clubID'];
                                                    // Query to get the club name from the clubID so the system admin can see which club the club admin is applying for
                                                    $clubQuery = "SELECT clubName FROM club WHERE clubID = '$clubID'";
                                                    $clubResult = mysqli_query($conn, $clubQuery);
                                                    $clubRow = mysqli_fetch_array($clubResult);
                                            ?>
                                                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                                        <div class="card bg-light d-flex flex-fill">
                                                            <div class="card-body pt-0">
                                                                <div class="col-7">
                                                                    <h2 class="header"><b><?php echo $row['firstName'];
                                                                                            echo " ";
                                                                                            echo $row['lastName']; ?></b></h2>
                                                                    <p class="text-muted text-sm">
                                                                        About:
                                                                    <p>Date of birth: <?php echo $row['DOB']; ?></p>
                                                                    <p>Email: <?php echo $tempUserRow['email']; ?></p>
                                                                    <p>Club: <?php echo $clubRow['clubName']; ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="text-left">
                                                                            <!-- Button that when clicks runs the club admin approve script -->
                                                                            <form action="includes/clubAdminApprove.php" method="post">
                                                                                <!-- Hidden input to post the tempUserID -->
                                                                                <input type="hidden" name="tempUserID" value="<?php echo $row['tempUserID']; ?>">

                                                                                <!-- Submit button to approve the club admin -->
                                                                                <button type="submit" class="btn btn-success">Approve Club Admin</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="text-right">
                                                                            <!-- Button that when clicks runs the club admin reject script -->
                                                                            <form action="includes/clubAdminReject.php" method="post">
                                                                                <!-- Hidden input to post the tempUserID -->
                                                                                <input type="hidden" name="tempUserID" value="<?php echo $row['tempUserID']; ?>">
                                                                                <!-- Submit button to reject the club admin -->
                                                                                <button type="submit" class="btn btn-danger">Reject Club Admin</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            } else {
                                                echo "<h1>No club admins to approve</h1>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <!-- End of club admin approval -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Add system admins -->
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h3 class="card-title">Add system admin</h3>
                                        </div>
                                        <form action="includes/addSystemAdmin.php" method="post">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Email address</label>
                                                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required maxlength="50" minlength="5" /> <!-- Length check and presence check -->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="Password">Password</label>
                                                            <input type="password" class="form-control" id="Password" placeholder="Password" name="password" required maxlength="50" minlength="5" /> <!-- Length check and presence check -->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="confirmPassword">Confirm password</label>
                                                            <input type="password" class="form-control" id="confirmPassword" placeholder="Password" name="confirmPassword" required maxlength="50" minlength="5" /> <!-- Length check and presence check -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Error message -->
                                                <?php
                                                //Empty input
                                                if (isset($_GET['error']) && $_GET['error'] === "emptyinputSignup") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                //Password mismatch
                                                if (isset($_GET['error']) && $_GET['error'] === "passwordsdontmatch") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                //Email already exists
                                                if (isset($_GET['error']) && $_GET['error'] === "emailalreadyexists") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                //Account created
                                                if (isset($_GET['error']) && $_GET['error'] === "accountcreated") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                ?>
                                                <!-- End of error message -->
                                            </div>
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn btn-danger" name="submitUser">Add user</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- End of add system admins -->
                                    <!-- Add club -->
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h3 class="card-title">Add club</h3>
                                        </div>
                                        <form enctype="multipart/form-data" action="includes/addClub.php" method="POST">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="clubName">Club name</label>
                                                    <input type="text" class="form-control" id="clubName" placeholder="Club name" name="clubName" required maxlength="50" minlength="5" /> <!-- Length check and presence check -->
                                                </div>
                                                <div class="form-group">
                                                    <label for="clubLogo">Club logo</label>
                                                    <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="clubLogo" name="clubLogo" accept="image/jpeg,image/png">
                                                        <label class="custom-file-label" for="clubLogo">Choose file</label>
                                                    </div>
                                                </div>
                                                <!-- Error message -->
                                                <?php
                                                //Empty input
                                                if (isset($_GET['error']) && $_GET['error'] === "emptyinputClub") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                //Club created
                                                if (isset($_GET['error']) && $_GET['error'] === "clubcreated") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                ?>
                                                <!-- End of error message -->
                                            </div>
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn btn-danger" name="submitClub">Add club</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- End of add club -->
                                </div>
                                <!-- Add league -->
                                <div class="col-md-6">
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h3 class="card-title">Add league</h3>
                                        </div>
                                        <form action="includes/addLeague.php" method="post">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="leagueName">League name</label>
                                                    <input type="text" class="form-control" id="leagueName" placeholder="League name" name="leagueName" required maxlength="50" minlength="5" /> <!-- Length check and presence check -->
                                                </div>
                                                <!-- Error message -->
                                                <?php
                                                //Empty input
                                                if (isset($_GET['error']) && $_GET['error'] === "emptyinputLeague") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                //league created
                                                if (isset($_GET['error']) && $_GET['error'] === "leaguecreated") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                ?>
                                                <!-- End of error message -->
                                            </div>
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn btn-danger" name="submitLeague">Add league</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- End of add league -->
                                    <!-- Add team -->
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h3 class="card-title">Add team</h3>
                                        </div>
                                        <form action="includes/addTeam.php" method="post">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="teamName">Team name</label>
                                                    <input type="text" class="form-control" id="teamName" placeholder="Team name" name="teamName" required maxlength="50" minlength="5" /> <!-- Length check and presence check -->
                                                </div>
                                                <!-- Drop-down list of clubs -->
                                                <div class="form-group">
                                                    <label for="club">Club</label>
                                                    <select name="club" class="form-control">
                                                        <option value="" disabled selected hidden>Please select a club</option>
                                                        <?php
                                                        //Get all clubs from database so the system can select the club that the team will be added to
                                                        $clubQuery = "SELECT * FROM club";
                                                        $clubResult = mysqli_query($conn, $clubQuery);
                                                        while ($clubRow = mysqli_fetch_array($clubResult)) {
                                                        ?>
                                                            <option value="<?php echo $clubRow['clubID']; ?>">
                                                                <?php echo $clubRow['clubName']; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- Drop-down list of leagues -->
                                                <div class="form-group">
                                                    <label for="league">League</label>
                                                    <select name="league" class="form-control">
                                                        <?php
                                                        //Get all leagues from database so the system can select the league that the team will be added to
                                                        $leagueQuery = "SELECT * FROM league";
                                                        $leagueResult = mysqli_query($conn, $leagueQuery);
                                                        while ($leagueRow = mysqli_fetch_array($leagueResult)) {
                                                        ?>
                                                            <option value="<?php echo $leagueRow['leagueID']; ?>">
                                                                <?php echo $leagueRow['leagueName']; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- Error message -->
                                                <?php
                                                //Empty input
                                                if (isset($_GET['error']) && $_GET['error'] === "emptyinputTeam") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                //Team created
                                                if (isset($_GET['error']) && $_GET['error'] === "teamcreated") {
                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%;'>" . htmlspecialchars($message) . "</div>";
                                                }
                                                ?>
                                                <!-- End of error message -->
                                            </div>
                                            <div class="card-footer text-center">
                                                <button type="submit" class="btn btn-danger" name="submitTeam">Add team</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- End of add team -->
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="js/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/adminlte/adminlte.min.js"></script>

</body>

</html>