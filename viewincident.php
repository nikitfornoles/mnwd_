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

    <!-- facebox -->
    <script src="js/jquery-1.11.2.min.js" type="text/javascript"></script>
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="src/facebox.js" type="text/javascript"></script>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('a[rel*=facebox]').facebox({
          loadingImage : 'src/loading.gif',
          closeImage   : 'src/closelabel.png'
        })
      })
    </script>
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
            <li><a href="incidentreports.php"  class="active"><i class="fa fa-binoculars fa-fw"></i>Incident Reports</a></li>
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

              if (isset($_GET['view'])) {
                $reportid = $_GET['view'];

                $query = "SELECT * FROM `incident_report` WHERE `reportid` = $reportid";
                $result = mysqli_query($dbconn, $query) or die (mysqli_error($dbconn));
                $row = mysqli_fetch_array($result);

                $incidentid = $row['incidentid'];
                $accountid = $row['accountid'];
                $description = $row['description'];
                $reportdate = $row['reportdate'];
                $action_taken = $row['action_taken'];

                $incidenttype = "SELECT `incidenttype` FROM `incident` WHERE `incidentid` = $incidentid";
                $res = mysqli_query ($dbconn, $incidenttype);
                $rowi = mysqli_fetch_array($res);
                $incidenttype = $rowi['incidenttype'];

                $sql = "SELECT * FROM `account` WHERE `accountid` = $accountid";
                $result = mysqli_query ($dbconn, $sql);
                $row = mysqli_fetch_array($result);
                $accountno = $row['accountno'];
                $address = $row['address'];
                $userid = $row['userid'];

                $sql = "SELECT * FROM `user` WHERE `userid` = $userid";
                $result = mysqli_query ($dbconn, $sql);
                $row = mysqli_fetch_array($result);
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
              }
            ?>
            <h2 class="margin-bottom-10">View Incident Report</h2>

            <div class="templatemo-content-widget white-bg col-3">
            <div class="row form-group">
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <tr>
                      <td bgcolor="light-blue-bg"><p style="color:white;">Report ID</p></td>
                      <td> <?php echo $reportid; ?></td>                    
                    </tr> 
                    <tr>
                      <td bgcolor="light-blue-bg"><p style="color:white;">Incident Type</p></td>
                      <td><?php echo $incidenttype; ?></td>                    
                    </tr>  
                    <tr>
                      <td bgcolor="light-blue-bg"><p style="color:white;">Account Number</p></td>
                      <td><?php echo $accountno; ?></td>                    
                    </tr>  
                    <tr>
                      <td bgcolor="light-blue-bg"><p style="color:white;">Desciption</p></td>
                      <td><?php echo $description; ?></td>                    
                    </tr>      
                    <tr>
                      <td bgcolor="light-blue-bg"><p style="color:white;">Address</p></td>
                      <td><?php echo $address; ?></td>                    
                    </tr>
                    <tr>
                      <td bgcolor="light-blue-bg"><p style="color:white;">Username</p></td>
                      <td><?php echo $firstname . ' ' . $lastname; ?></td>                    
                    </tr>
                    <tr>
                      <td bgcolor="light-blue-bg"><p style="color:white;">Report Date</p></td>
                      <td><?php echo $reportdate; ?></td>                    
                    </tr>
                    <tr>
                      <td bgcolor="light-blue-bg"><p style="color:white;">Action Taken</p></td>
                      <td><?php echo $action_taken; ?></td>
                    </tr>                          
                  </tbody>
                </table>
              </div>
              </div>

              <div class="form-group text-center">
                <a rel='facebox' href='edit-action-taken.php?reportid=<?php echo $reportid ?>'><input type="button" type="submit" class="templatemo-blue-button" value="update"></a>
                <a href="incidentreports.php"><input type="button" type="submit" class="templatemo-blue-button" value="Back"></a>
              </div>  
            </div>

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