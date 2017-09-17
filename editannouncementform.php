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
            <?php
              include 'connect.php';

              if (isset($_GET['edit'])) {
                $announcementid = $_GET['edit'];

                $query = "SELECT * FROM `announcement` WHERE `announcementid` = $announcementid";
                $result = mysqli_query($dbconn, $query) or die (mysqli_error($dbconn));
                $row = mysqli_fetch_array($result);

                $announcement = $row['announcement'];
                $isimage = $row['isimage'];
              }
            ?>

            <h2 class="margin-bottom-10">Edit Announcement</h2>
            <form name = "editannform" action="<?php if ($isimage == 0) { echo "editannouncement.php"; } else { echo "$_SERVER[PHP_SELF]"; } ?>" method="POST" class="templatemo-login-form" enctype="multipart/form-data">
              <div class="row form-group">
                <div class="col-lg-12 form-group">                   
                    <label class="control-label" for="inputNote">Announcement</label>
                    <?php
                      if ($isimage == 0) {
                        echo "<textarea class=form-control id=inputNote name=edit_ann rows=5 required autofocus>$announcement</textarea>";
                        echo "<input type=hidden name=announcementid value=$announcementid>";
                      }
                      else {
                        echo "<br>";
                        echo '<img src="data:image;base64,'.$row['announcement'].' " width = "80%" height = "80%">';
                        echo "<br><br><label class=control-label templatemo-block>File Input</label>";
                        echo "<input type=file name=image id=fileToUpload class=filestyle data-buttonName=btn-primary data-buttonBefore=true data-icon=false required>";
                        echo "<input type=hidden name=announcementid value=$announcementid>";
                      }
                    ?>
                </div>
              </div>

              <div class="form-group text-left">
                <button type="submit" class="templatemo-blue-button" name="editann">Submit</button>
                <a href="announcements.php"><input type="button" type="submit" class="templatemo-blue-button" name="cancel" value="Cancel"></a>
              </div>                           
            </form>

            <?php
              require_once('security_check.php');

              if (isset($_POST['editann'])) {
                $announcementid = test_input($_POST['announcementid']);
                
                if (getimagesize($_FILES['image']['tmp_name']) == FALSE) {
                  echo "Please select an image.";
                }
                else {
                  $image = addslashes($_FILES['image']['tmp_name']);
                  $name = addslashes($_FILES['image']['name']);
                  $image = file_get_contents($image);
                  $image = base64_encode($image);
                  saveimage($name, $image, $announcementid);
                }
              }

              function saveimage ($name, $image, $announcementid) {
                include 'connect.php';

                $msg = "";
                $sql = "UPDATE `announcement` SET `announcement` = '$image' AND `imgname` = '$name'  WHERE `announcementid` = $announcementid";
                if (mysqli_query($dbconn, $sql)) {
                  $msg = "Image successfully updated";
                }
                else {
                  $msg = "Image update failed";
                }
                header('Location:announcements.php?msg='.$msg.'');
                mysqli_close($dbconn);
              }
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