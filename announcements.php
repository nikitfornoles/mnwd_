<?php
  session_start();

  if(!isset($_SESSION['user_id'])) {
    echo '<script>windows: location="index.php"</script>';
  }
  else {
    if ($_SESSION['usertype'] == 'staff') {
      if ($_SESSION['modulename'] != 'announcement') {
        $msg = "You do not have the permission to access the announcements page.";
        echo '<script>windows: location="home.php?msg='.$msg.'"</script>';
      }
    }
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
            <li><a href="announcements.php" class="active"><i class="fa fa-bullhorn fa-fw"></i>Announcements</a></li>
            <li><a href="billinfo.php"><i class="fa fa-info-circle fa-fw"></i>Bill Information</a></li>
            <li><a href="incidentreports.php"><i class="fa fa-binoculars fa-fw"></i>Incident Reports</a></li>
            <li><a href="managestaffs.php"><i class="fa fa-users fa-fw"></i>Manage Staffs</a></li>
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
            <h2 class="margin-bottom-10">Add Announcement</h2>

            <form method="POST" class="templatemo-login-form" enctype="multipart/form-data">
              <div class="row form-group">
                <div class="col-lg-6 col-md-6 form-group">  
                  <label class="control-label templatemo-block">File Input</label>
                  <input type="file" name="image" id="fileToUpload" class="filestyle" data-buttonName="btn-primary" data-buttonBefore="true" data-icon="false" required>         
                </div>
                <div class="col-lg-6 col-md-6 form-group">
                  <label for="expiration_date">Expiration Date</label>
                  <input type="date" name="expiration_date" id="expiration_date" class="form-control" required><br>
                  <input type="hidden" name="userid" value="<?php echo $session_id; ?>">       
                </div>
              </div>

              <div class="form-group text-left">
                <button type="submit" class="templatemo-blue-button" name="upload">Upload</button>
              </div>
            </form>

            <?php
              if (isset($_POST['upload'])) {
                require('connect.php');
                $userid = $_POST['userid'];
                $expiration_date = $_POST['expiration_date'];
                
                if (getimagesize($_FILES['image']['tmp_name']) == FALSE) {
                  echo "Please select an image.";
                }
                else {
                  $image = mysqli_real_escape_string($dbconn, $_FILES['image']['tmp_name']);
                  $name = mysqli_real_escape_string($dbconn, $_FILES['image']['name']);
                  $image = file_get_contents($image);
                  $image = base64_encode($image);
                  saveimage($name, $image, $expiration_date, $userid);
                }
              }

              function saveimage ($name, $image, $expiration_date, $userid) {
                include 'connect.php';

                $msg = "";
                $query = "INSERT INTO `announcement` (`announcementid`, `announcement`, `imgname`, `date`, `expiration_date`, `userid`) 
                          VALUES ('', '$image', '$name', CURDATE(), '$expiration_date', $userid)";
                if (mysqli_query($dbconn, $query)) {
                    $msg = "Image successfully uploaded.";
                    header('Location:announcements.php?msg='.$msg.'');
                }
                else {
                    $msg = "Image upload failed.";
                    header('Location:announcements.php?msg='.$msg.'');
                }
                mysqli_close($dbconn);
              }
            ?>
          </div>

          <hr>

          <div class="templatemo-content-widget white-bg">
            <h2 class="margin-bottom-10">View Announcements</h2>

            <?php
              include 'connect.php'; 
              $query = "SELECT * FROM `announcement` ORDER BY `expiration_date` ASC";
              $result = mysqli_query($dbconn, $query);
              $count = $result? mysqli_num_rows($result) : 0;

              echo "<div class='templatemo-content-widget no-padding'><div class='panel panel-default table-responsive'><table class = 'table table-striped table-bordered templatemo-user-table'>";
              echo "<thead><tr><td>Announcement</td><td>Expiration Date</td></tr></thead>";
              echo "<tbody>";

              if ($count == 0) {
                echo "<tr><td colspan = 4 align=center> 0 results </td></tr>";
              }
              else if ($count > 0) {
                while ($row = mysqli_fetch_array($result)) {
                  $expiration_date = $row['expiration_date'];
                  $expiration_date = new DateTime ("$expiration_date");

                  $now = new DateTime();
                  $now = date("Y-m-d", strtotime('+7 hours'));
                  $now = new DateTime("$now");

                  $announcementid = $row['announcementid'];

                  if ($expiration_date > $now) {
                    echo "<tr>";
                    echo '<td> <img src="data:image;base64,'.$row['announcement'].' " width = "80%" height = "80%"> </td>';
                    echo "<td> $row[expiration_date] </td>";
                    echo "</tr>";
                  }
                  else {
                    $sql = "DELETE FROM `announcement` WHERE `announcementid` = $announcementid";
                    mysqli_query($dbconn, $sql);
                  }
                }
              }
              echo "</tbody>";
              echo "</table></div></div>";
            ?>
          </div>

        </div>
      </div> <!-- End of Main content-->

    </div>

    <!-- JS -->
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>        <!-- jQuery -->
    <script type="text/javascript" src="js/bootstrap-filestyle.min.js"></script>  <!-- http://markusslima.github.io/bootstrap-filestyle/ -->
    <script type="text/javascript" src="js/templatemo-script.js"></script>        <!-- Templatemo Script -->
  </body>
</html>

<script type="text/javascript">
  $("#res").show().delay(3500).hide(1);
</script>