<?php
  session_start();

  if(!isset($_SESSION['user_id'])) {
    echo '<script>windows: location="index.php"</script>';
  }
?>

<?php
  $session_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MNWD</title>
    <meta name="description" content="">
    <meta name="author" content="templatemo">
    <!-- 
    Visual Admin Template
    http://www.templatemo.com/preview/templatemo_455_visual_admin
    -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,700' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/templatemo-style.css" rel="stylesheet">
  </head>
  
  <body>  
    <!-- Left column -->
    <div class="templatemo-flex-row">

      <div class="templatemo-sidebar">
        <header class="templatemo-site-header">
          <div class="square"></div>
          <h1><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?></h1>
        </header>
        <div class="profile-photo-container">
          <img src="images/profile-photo.jpg" alt="Profile Photo" class="img-responsive">
          <div class="profile-photo-overlay"></div>
        </div>      
        <div class="mobile-menu-icon">
            <i class="fa fa-bars"></i>
        </div>
        <nav class="templatemo-left-nav">          
          <ul>
            <li><a href="home.php"><i class="fa fa-home fa-fw"></i>Dashboard</a></li>
            <li><a href="announcements.php"><i class="fa fa-bullhorn fa-fw"></i>Announcements</a></li>
            <li><a href="billinfo.php"><i class="fa fa-info-circle fa-fw"></i>Bill Information</a></li>
            <li><a href="incidentreports.php"><i class="fa fa-binoculars fa-fw"></i>Incident Reports</a></li>
            <li><a href="managestaffs.php"  class="active"><i class="fa fa-users fa-fw"></i>Manage Staffs</a></li>
            <li><a href="profile.php"><i class="fa fa-user fa-fw"></i>Profile</a></li>
            <li><a href="logout.php"><i class="fa fa-eject fa-fw"></i>Sign Out</a></li>
          </ul>  
        </nav>
      </div>

      <!-- Main content --> 
      <div class="templatemo-content col-1 light-gray-bg">
        <div class="templatemo-content-container">
          <?php
            if (isset($_GET['msg'])) {
              echo "<div id = 'res' class='alert alert-info' role='alert'> $_GET[msg] </div>";
            }
          ?>

          <?php
            require_once('connect.php');
            require_once('security_check.php');

            $firstname = $lastname = $email = $module = $modulename = "";
            $firstnameErr = $lastnameErr = $emailErr = $moduleErr = "";
            $emailstatus = $module_billinfo = $module_incidentreport = 0;

            if(isset($_POST['addstaff'])) {
              ($_POST['firstname'] == "" ? $firstnameErr = "First name is required" : $firstname = test_input($_POST['firstname']));
              ($_POST['lastname'] == "" ? $lastnameErr = "Last name is required" : $lastname = test_input($_POST['lastname']));

              if ($_POST['email'] == "") {
                $emailErr = "Email is required";
              }
              else {
                $email = test_input($_POST['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $emailErr = "Invalid email format";
                }
                else {
                  $emailstatus = 1;
                }
              }

              if (empty($_POST['module'])) {
                $moduleErr = "Module is required";
              }
              else {
                $module = test_input($_POST['module']);
                if ($module == 'announcement') {
                  $modulename = $module;
                }
                else if ($module == 'billinfo') {
                  $modulename = 'bill info';
                }
                else if ($module == 'incidentreport') {
                  $modulename = 'incident report';
                }
              }

              //all inputs are okay
              if (empty($firstnameErr) && empty($lastnameErr) && empty($emailErr) && empty($moduleErr)) {
                $sql = "SELECT * FROM `employee` WHERE `firstname` = '$firstname' AND `lastname` = '$lastname'";
                $result = mysqli_query($dbconn, $sql);
                $count = mysqli_num_rows($result);

                if ($count > 0) {
                  $row = mysqli_fetch_array($result);
                  $employeeid = $row['employeeid'];
                  $firstname = $row['firstname'];
                  $lastname = $row['lastname'];

                  $usertype = 'staff';
                  $email = ($emailstatus == 0? NULL : $email);
                  require_once('mobile_generatepassword.php');
                  $encpassword = md5($password);

                  # generate unique username
                  # ----------------------------------------------------------------------
                  $username = $lastname . '_';
                  for ($i=0; $i<strlen($firstname); $i++) {
                    $username = $username . $firstname[$i];
                    if ($i == 2) {
                      break;
                    }
                  }

                  for ($i=1; $i < 100 ; $i++) {
                    $username_ = $username . $i;

                    $query = "SELECT * FROM `user` WHERE `username` = '$username_'";
                    $check = mysqli_fetch_array(mysqli_query($dbconn, $query));

                    if (!isset($check)) {
                      $username = $username_;
                      break;
                    }
                  }
                  # ----------------------------------------------------------------------

                  //send generated password to and username to staff's email address
                  require_once('../phpmailer/PHPMailerAutoload.php');
                  $mail = new PHPMailer();
                  $mail -> CharSet =  "utf-8";
                  $mail -> IsSMTP(); // Set mailer to use SMTP
                  $mail -> SMTPDebug = 1;  // Enable verbose debug output
                  $mail -> SMTPAuth = true; // Enable SMTP authentication
                  $mail -> Username = "mnwdtest@gmail.com"; //Sender's Authentic Email ID
                  $mail -> Password = "Nawasa.MetroNaga2"; //Sender's Password
                  $mail -> SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                  $mail -> Host = "smtp.gmail.com"; // SMTP 
                  $mail -> Port = "587"; // TCP port to connect to

                  $mail -> setFrom($mail->Username, 'MNWD');
                  $mail -> AddAddress("$email" , "Staff"); //Recipient

                  //$mail->addAttachment('path/file.png');         // Add attachments

                  $mail -> Subject = "Login Details";
                  $mail -> Body = "<p>You may now login using <b>$username</b> as username and <b>$password</b> as password.</p>";
                  $mail -> ContentType = "text/html";

                  $msg = '';
                  if ($mail -> Send()) {
                    $msg = "Login details successfully sent.";

                    //set username and password
                    $sql = "INSERT INTO `staffinfo` (`staffinfoid`, `modulename`, `employeeid`)
                            VALUES ('', '$module', $employeeid)";

                    if (mysqli_query($dbconn, $sql)) {
                      //set username and password
                      $sql = "INSERT INTO `user` (`userid`, `firstname`, `lastname`, `usertype`, `username`, `password`, `email`, `registered`, `seniorcitizen`, `staffinfoid`)
                              VALUES ('', '$firstname', '$lastname', $usertype, '$username', '$encpassword', '$email', 1, 0, $staffinfoid)";

                      if (mysqli_query($dbconn, $sql)) {
                        $msg = 'Staff successfully added';
                        header('Location:managestaffs.php?msg='.$msg.'');
                      }
                    }
                  }
                  else{
                    $msg = "Error sending login details.";
                    header('Location:managestaffs.php?msg='.$msg.'');
                  }
                }

                else {
                  $msg = 'Employee does not exist';
                  header('Location:managestaffs.php?msg='.$msg.'');
                } 
              }
            }
            mysqli_close($dbconn);
          ?>

          <!-- REGISTRATION FORM starts -->
          <div class="panel panel-default margin-10">
            <div class="panel-heading">
              <center><h2 class="text-uppercase">ADD STAFF</h2></center>
            </div>
            <div class="panel-body">
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="templatemo-login-form">

                <div class="row form-group">
                  <div class="col-lg-6 col-md-6 form-group">
                    <label>First Name</label>
                    <div class="<?php if($firstnameErr != "") echo "col-lg-12 has-error form-group"; else echo ""; ?>">
                      <input type="text" class="form-control" name="firstname" placeholder="Enter first name" value="<?php echo $firstname;?>" <?php if($firstname == "") echo "autofocus"; ?> autocomplete="off" id="<?php if($firstnameErr != "") echo "inputWithError"; else echo ""; ?>">
                      <div class="col-lg-12 has-error form-group">
                        <label class="control-label"><?php echo $firstnameErr;?></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 form-group">
                    <label>Last Name</label>
                    <div class="<?php if(!empty($lastnameErr)) echo "col-lg-12 has-error form-group"; else echo ""; ?>">
                      <input type="text" class="form-control" name="lastname" placeholder="Enter last name" value="<?php echo $lastname;?>" <?php if($lastname == "") echo "autofocus"; ?> autocomplete="off" id="<?php if(!empty($lastnameErr)) echo "inputWithError"; else echo ""; ?>">
                      <div class="col-lg-12 has-error form-group">
                        <label class="control-label"><?php echo $lastnameErr;?></label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col-lg-6 col-md-6 form-group">
                    <label>Email Address</label>
                    <div class="<?php if(!empty($emailErr)) echo "col-lg-12 has-error form-group"; else echo ""; ?>">
                      <input type="email" class="form-control" name="email" placeholder="Enter email" value="<?php echo $email;?>" <?php if($email == "") echo "autofocus"; ?> autocomplete="off" id="<?php if(!empty($emailErr)) echo "inputWithError"; else echo ""; ?>">
                      <div class="col-lg-12 has-error form-group">
                        <label class="control-label"><?php echo $emailErr;?></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 form-group">
                    <label>Module</label><br>
                    <div class="margin-right-15 templatemo-inline-block">
                      <input type="radio" name="module" id="r4" <?php if (isset($module) && $module=="announcement") echo "checked";?> value="announcement">
                      <label for="r4" class="font-weight-400"><span></span>Announcement</label>
                    </div>
                    <div class="margin-right-15 templatemo-inline-block">
                      <input type="radio" name="module" id="r5" <?php if (isset($module) && $module=="billinfo") echo "checked";?> value="billinfo">
                      <label for="r5" class="font-weight-400"><span></span>Bill Info</label>
                    </div>
                    <div class="margin-right-15 templatemo-inline-block">
                      <input type="radio" name="module" id="r6" <?php if (isset($module) && $module=="incidentreport") echo "checked";?> value="incidentreport">
                      <label for="r6" class="font-weight-400"><span></span>Incident Report</label>
                    </div>
                    <div class="col-lg-12 has-error form-group">
                      <label class="control-label"><?php echo $moduleErr;?></label>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <center><button type="submit" class="templatemo-blue-button" name="addstaff">Submit</button></center>
                </div>

              </form>
            </div>                
          </div>

          <hr>

          <div class="templatemo-content-widget white-bg">
            <h2 class="margin-bottom-10">Staff Information</h2>

            <?php
              include 'connect.php';
              $query = "SELECT * FROM `user` WHERE `usertype` = 'staff'";
              $result = mysqli_query($dbconn, $query);
              $count = mysqli_num_rows($result);

              echo "<div class='templatemo-content-widget no-padding'>";
              echo "<div class='panel panel-default table-responsive'>";
              echo "<table class = 'table table-striped table-bordered templatemo-user-table'>";
              echo "<thead><tr><td>First Name</td><td>Last Name</td><td>Assigned Module</td>";
              echo "</tr></thead> <tbody>";

              if ($count == 0) {
                echo "<tr><td colspan = 9 align=center> 0 results </td></tr>";
              }
              else if ($count > 0) {
                while ($row = mysqli_fetch_array($result)) {
                  $firstname = $row['firstname'];
                  $lastname = $row['lastname'];
                  $staffinfoid = $row['staffinfoid'];

                  $query = "SELECT * FROM `staffinfo` WHERE `staffinfoid` = $staffinfoid";
                  $result = mysqli_query($dbconn, $query);
                  $row = mysqli_fetch_array($result);
                  $modulename = $row['modulename'];
                  
                  echo "<tr>";
                  //echo "<td> $readingid </td>";
                  echo "<td> $firstname </td>";
                  echo "<td> $lastname </td>";
                  echo "<td> $modulename </td>";
                  echo "</tr>";
                }
              }
              echo "</tbody>";
              echo "</table></div></div>";
            ?>
          </div>
          <!-- REGISTRATION FORM ends -->
        </div>
      </div>
    </div>
    
    <!-- JS -->
    <script src="js/jquery-1.11.2.min.js"></script>      <!-- jQuery -->
    <script src="js/jquery-migrate-1.2.1.min.js"></script> <!--  jQuery Migrate Plugin -->
    <script src="https://www.google.com/jsapi"></script> <!-- Google Chart -->
    <script type="text/javascript" src="js/templatemo-script.js"></script>      <!-- Templatemo Script -->
  </body>
</html>

<script type="text/javascript">
  $("#res").show().delay(3500).hide(1);
</script>