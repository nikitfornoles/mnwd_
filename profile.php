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
            <li><a href="managestaffs.php"><i class="fa fa-users fa-fw"></i>Manage Staffs</a></li>
            <li><a href="profile.php"  class="active"><i class="fa fa-user fa-fw"></i>Profile</a></li>
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
            include 'connect.php';
            $sql ="SELECT * FROM `user` WHERE `userid` = $_SESSION[user_id]";
            $result = mysqli_query ($dbconn, $sql);
            $row = mysqli_fetch_array($result);

            $username = $row['username'];
            $email = $row['email'];
            $password = $row['password'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
          ?>

          <div class="panel panel-default margin-10">
            <div class="panel-heading">
              <center><h2 class="text-uppercase">PROFILE</h2></center>
            </div>
            <div class="panel-body">
              <p>*Put entry on field/s that you would want to modify.</p>
              <form action="editprofile.php" class="templatemo-login-form" method="POST" enctype="multipart/form-data">
                <div class="row form-group">
                  <div class="col-lg-6 col-md-6 form-group">                  
                      <label for="inputFirstName">First Name</label>
                      <input type="text" name="firstname" class="form-control" id="inputFirstName" placeholder="<?php echo $firstname; ?>">          
                  </div>
                  <div class="col-lg-6 col-md-6 form-group">                  
                      <label for="inputLastName">Last Name</label>
                      <input type="text" name="lastname" class="form-control" id="inputLastName" placeholder="<?php echo $lastname; ?>">
                  </div> 
                </div>
                <div class="row form-group">
                  <div class="col-lg-6 col-md-6 form-group">                  
                      <label for="inputUsername">Username</label>
                      <input type="text" name="username" class="form-control" id="inputUsername" placeholder="<?php echo "$username"; ?>">
                  </div>
                  <div class="col-lg-6 col-md-6 form-group">                  
                      <label for="inputEmail">Email</label>
                      <input type="email" name="email" class="form-control" id="inputEmail" placeholder="<?php echo "$email"; ?>">
                      <input type="hidden" name="userid" value="<?php echo $session_id ;?>">
                  </div> 
                </div>
                <div class="form-group text-center">
                  <input type="submit" value = "Update" name="profileedit" class="templatemo-blue-button">
                </div>                           
              </form>
          </div>
          </div>

          <hr>

          <div class="panel panel-default margin-10">
            <div class="panel-heading">
              <center><h2 class="text-uppercase">Edit Password</h2></center>
            </div>
            <div class="panel-body">
            <form action="editpassword.php" class="templatemo-login-form" method="POST" enctype="multipart/form-data">
              <div class="row form-group">
                <div class="col-lg-6 col-md-6 form-group">                  
                    <label for="inputCurrentPassword">Current Password</label>
                    <input type="password" name="currentpassword" class="form-control" id="inputCurrentPassword" placeholder="<?php for ($i=0; $i<strlen($password); $i++) {echo "*";} ?>" required>
                </div>                
              </div>
              <div class="row form-group">
                <div class="col-lg-6 col-md-6 form-group">                  
                    <label for="inputNewPassword">New Password</label>
                    <input type="password" name="newpassword" class="form-control" id="inputNewPassword" required>
                </div>
                <div class="col-lg-6 col-md-6 form-group">                  
                    <label for="inputConfirmNewPassword">Confirm New Password</label>
                    <input type="password" name="newpassword2" class="form-control" id="inputConfirmNewPassword" required>
                    <input type="hidden" name="userid" value="<?php echo $session_id ;?>">
                </div> 
              </div>
              <div class="form-group text-center">
                <input type="submit" value="Update" name="editpassword" class="templatemo-blue-button">
              </div>
            </form>
          </div>
          </div>
            
        </div>
      </div> <!-- End of Main content-->
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