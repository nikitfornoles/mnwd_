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
              echo "<thead><tr><td>Employee ID</td><td>First Name</td><td>Last Name</td><td>Email</td><td>Assigned Module</td>";
              echo "</tr></thead> <tbody>";

              if ($count == 0) {
                echo "<tr><td colspan = 9 align=center> 0 results </td></tr>";
              }
              else if ($count > 0) {
                while ($row = mysqli_fetch_array($result)) {
                  $userID = $row['userid'];
                  $firstname = $row['firstname'];
                  $lastname = $row['lastname'];
                  $email = $row['email'];

                  $query1 = "SELECT * FROM `staffinfo` WHERE `userid` = $userID";
                  $result1 = mysqli_query($dbconn, $query1);
                  $row1 = mysqli_fetch_array($result1);
                  $employeeID = $row1['employeeid'];
                  $modulename = $row1['modulename'];
                  
                  echo "<tr>";
                  echo "<td> $employeeID </td>";
                  echo "<td> $firstname </td>";
                  echo "<td> $lastname </td>";
                  echo "<td> $email </td>";
                  echo "<td> $modulename </td>";
                  echo "</tr>";
                }
              }
              echo "</tbody>";
              echo "</table></div></div>";
            ?>
          </div>

          <hr>

          <div class="templatemo-content-widget white-bg">
            <h2 class="margin-bottom-10">MNWD Employees</h2>

            <?php
              include 'connect.php';
              $query = "SELECT * FROM `employee` WHERE `employeeid` NOT IN (SELECT `employeeid` FROM `staffinfo`)";
              $result = mysqli_query($dbconn, $query);
              $count = mysqli_num_rows($result);

              echo "<div class='templatemo-content-widget no-padding'>";
              echo "<div class='panel panel-default table-responsive'>";
              echo "<table class = 'table table-striped table-bordered templatemo-user-table'>";
              echo "<thead><tr><td>Employee ID</td><td>First Name</td><td>Last Name</td><td>Email</td><td>Module to Assign</td><td>Action</td>";
              echo "</tr></thead> <tbody>";

              if ($count == 0) {
                echo "<tr><td colspan = 9 align=center> 0 results </td></tr>";
              }
              else if ($count > 0) {
                while ($row = mysqli_fetch_array($result)) {
                  $employeeID = $row['employeeid'];
                  $firstname = $row['firstname'];
                  $lastname = $row['lastname'];
                  $email = $row['email'];
                  
                  echo "<form action='addstaff.php' method='post'>";
                    echo "<tr>";
                    echo "<td> $employeeID </td>";
                    echo "<td> $firstname </td>";
                    echo "<td> $lastname </td>";
                    echo "<td> $email </td>";
                    ?>
                    <td>
                    <select class="form-control margin-bottom-15" id="singleSelect" name="employee_option">
                      <?php
                        include 'connect.php';
                        $sql ="SELECT * FROM `module`";
                        $result1 = mysqli_query ($dbconn, $sql);

                        while ($row1 = mysqli_fetch_array($result1)) {
                          $modulename = $row1['modulename'];
                          echo "<option value='$modulename'>$modulename</option>";
                        }
                      ?> 
                    </select>
                    </td>
                    <input type="hidden" name="employeeID" value="<?php echo($employeeID); ?>" />
                    <input type="hidden" name="firstname" value="<?php echo($firstname); ?>" />
                    <input type="hidden" name="lastname" value="<?php echo($lastname); ?>" />
                    <input type="hidden" name="email" value="<?php echo($email); ?>" />
                    <td><button class='btn btn-sm btn-primary' type="submit" name="add_staff">Add Staff</button></td>
                  </form>
                  <?php
                  echo "</tr>";
                }
              }
              echo "</tbody>";
              echo "</table></div></div>";
            ?>
          </div>
          
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