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
            <li><a href="billinfo.php" class="active"><i class="fa fa-info-circle fa-fw"></i>Bill Information</a></li>
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
            <h2 class="margin-bottom-10">Upload Meter Reading File</h2>
            <p>*Meter reading file taken from the meter reading device</p>

            <form action="uploadmrf.php" class="templatemo-login-form" method="post" enctype="multipart/form-data">
              <div class="row form-group">
                <div class="col-lg-12">
                  <label class="control-label templatemo-block">File Input</label> 
                  <input type="file" name="fileToUpload" id="fileToUpload" class="filestyle" data-buttonName="btn-primary" data-buttonBefore="true" data-icon="false" required>
                  <input type="hidden" name="userid" value="<?php echo $session_id; ?>">
                </div>
              </div>

              <div class="form-group text-left">
                <button type="submit" class="templatemo-blue-button" name="upload">Upload</button>
              </div>                           
            </form>
          </div>

          <hr>

          <div class="templatemo-content-widget white-bg">
            <h2 class="margin-bottom-10">Meter Reading Details</h2>

            <?php
              include 'connect.php';
              $query = "SELECT * FROM `reading` ORDER BY `billingdate` DESC, `accountid` DESC";
              $result = mysqli_query($dbconn, $query);
              $count = mysqli_num_rows($result);

              echo "<div class='templatemo-content-widget no-padding'>";
              echo "<div class='panel panel-default table-responsive'>";
              echo "<table class = 'table table-striped table-bordered templatemo-user-table'>";
              echo "<thead><tr><td>Bill Date</td><td>Account No</td><td>Refno</td><td>Reading</td>";
              echo "<td>CuM</td><td>Bill</td><td>Duedate</td><td>Disconnection</td></tr></thead>";
              echo "<tbody>";

              if ($count == 0) {
                echo "<tr><td colspan = 9 align=center> 0 results </td></tr>";
              }
              else if ($count > 0) {
                while ($row = mysqli_fetch_array($result)) {
                  $readingid = $row['readingid'];
                  $billingdate = $row['billingdate'];
                  $previous_reading = $row['previous_reading'];
                  $present_reading = $row['present_reading'];
                  $consumption = $row['consumption'];
                  $bill = $row['bill'];
                  $duedate = $row['duedate'];
                  $disconnection_date = $row['disconnection_date'];
                  $refno = $row['refno'];

                  $accountid = $row['accountid'];
                  $sql = "SELECT `accountno` FROM `account` WHERE `accountid` = $accountid";
                  $result1 = mysqli_query($dbconn, $sql);
                  $row1 = mysqli_fetch_array($result1);
                  $accountno = $row1 [0];
                  
                  echo "<tr>";
                  //echo "<td> $readingid </td>";
                  echo "<td> $billingdate </td>";
                  echo "<td> $accountno </td>";
                  echo "<td> $refno </td>";
                  //echo "<td> $previous_reading </td>";
                  echo "<td> $present_reading </td>";
                  echo "<td> $consumption </td>";
                  echo "<td> $bill </td>";
                  echo "<td> $duedate </td>";
                  echo "<td> $disconnection_date </td>";
                  echo "</tr>";
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