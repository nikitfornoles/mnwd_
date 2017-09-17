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
            <h2 class="margin-bottom-10">Add Announcement</h2>
            <form action="addannouncement.php" method="POST" class="templatemo-login-form" enctype="multipart/form-data">
              <div class="row form-group">
                <div class="col-lg-12 form-group">                   
                    <label class="control-label" for="inputNote">Announcement</label>
                    <textarea class="form-control" id="inputNote" name="txtannouncement" rows="5" placeholder="Your text here..." required autofocus></textarea>
                    <input type="hidden" name="ann_date" value="">
                    <input type="hidden" name="userid" value="<?php echo $session_id; ?>">
                </div>
              </div>

              <div class="form-group text-left">
                <button type="submit" class="templatemo-blue-button" name="addannouncement">Submit</button>
              </div>                           
            </form>

            <hr>

            <form method="POST" class="templatemo-login-form" enctype="multipart/form-data">
              <div class="row form-group">
                <div class="col-lg-12">
                  <label class="control-label templatemo-block">File Input</label>
                  <input type="file" name="image" id="fileToUpload" class="filestyle" data-buttonName="btn-primary" data-buttonBefore="true" data-icon="false" required> 
                  <input type="hidden" name="userid" value="<?php echo $session_id; ?>">               
                </div>
              </div>

              <div class="form-group text-left">
                <button type="submit" class="templatemo-blue-button" name="upload">Upload</button>
              </div>
            </form>

            <?php
              if (isset($_POST['upload'])) {
                require_once('connect.php');
                $userid = $_POST['userid'];
                
                if (getimagesize($_FILES['image']['tmp_name']) == FALSE) {
                  echo "Please select an image.";
                }
                else {
                  $image = mysqli_real_escape_string($dbconn, $_FILES['image']['tmp_name']);
                  $name = mysqli_real_escape_string($dbconn, $_FILES['image']['name']);
                  $image = file_get_contents($image);
                  $image = base64_encode($image);
                  saveimage($name, $image, $userid);
                }
              }

              function saveimage ($name, $image, $userid) {
                require_once('connect.php');

                $msg = "";
                $query = "INSERT INTO `announcement` (`announcementid`, `announcement`, `isimage`, `imgname`, `date`, `userid`) 
                          VALUES ('', '$image', 1, '$name', CURDATE(), $userid)";
                if (mysqli_query($dbconn, $query)) {
                  if (!isset($_GET['msg'])) {
                    $msg = "Image successfully uploaded";
                    header('Location:announcements.php?msg='.$msg.'');
                  }
                }
                else {
                  if (!isset($_GET['msg'])) {
                    $msg = "Image upload failed";
                    header('Location:announcements.php?msg='.$msg.'');
                  }
                }
                mysqli_close($dbconn);
              }
            ?>
          </div>

          <hr>

          <div class="templatemo-content-widget white-bg">
            <h2 class="margin-bottom-10">View Announcements</h2>

            <?php
              require_once('connect.php'); 
              $query = "SELECT * FROM `announcement` ORDER BY `date` DESC";
              $result = mysqli_query($dbconn, $query);
              $count = mysqli_num_rows($result);

              echo "<div class='templatemo-content-widget no-padding'><div class='panel panel-default table-responsive'><table class = 'table table-striped table-bordered templatemo-user-table'>";
              echo "<thead><tr><td>ID</td><td>Announcement</td><td>Date</td><td colspan = 2></td></tr></thead>";
              echo "<tbody>";

              if ($count == 0) {
                echo "<tr><td colspan = 4 align=center> 0 results </td></tr>";
              }
              else if ($count > 0) {
                while ($row = mysqli_fetch_array($result)) {
                  echo "<tr>";
                  echo "<td> $row[announcementid] </td>";

                  if ($row['isimage'] == 0) {
                    echo "<td> $row[announcement] </td>";
                  }
                  else {
                    echo '<td> <img src="data:image;base64,'.$row['announcement'].' " width = "80%" height = "80%"> </td>';
                  }

                  echo "<td> $row[date] </td>";
                  if ($row['isimage'] == 0) {
                    echo "<td><a href='editannouncementform.php?edit=$row[announcementid]' class='btn btn-sm btn-primary'>Edit</a></td>";
                    echo "<td><a href='deleteannouncement.php?delete=$row[announcementid]' class='btn btn-sm btn-primary'>Delete</a></td>";
                  }
                  else {
                    echo "<td colspan = 2><a href='deleteannouncement.php?delete=$row[announcementid]' class='btn btn-sm btn-primary'>Delete</a></td>";
                  }
                  echo "</tr>";
                }
              }
              echo "</tbody>";
              echo "</table></div></div>";
            ?>

            <form action="deleteannouncement.php" class="templatemo-login-form" method="post" enctype="multipart/form-data">
              <div class="form-group text-left">
              <?php
                if ($count > 0) {
                  echo "<button type=submit class=templatemo-blue-button name=delete>Delete All</button>";
                }
              ?>
              </div>                           
            </form>

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