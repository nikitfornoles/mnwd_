<?php
  session_start();

  if(!isset($_SESSION['user_id'])) {
    echo '<script>windows: location="index.php"</script>';
  }
?>

<?php
  $session_id = $_SESSION['user_id'];
?>

<?php
  $msg = "";
  if (isset($_POST['upload'])) {
    $target = "images/".basename($_FILES['image']['name']);
    //include 'photo_connect.php';
    $dbconn = mysqli_connect("localhost", "root", "", "mnwd");


    $image = $_FILES['image']['name'];

    $sql = "INSERT INTO images (image) VALUES ('$image')";
    mysqli_query($dbconn, $sql);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
      $msg = "Image uploaded successfully.";
    }
    else{
      $msg = "There was a problem uploading image.";

    }

  }
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>MNWD</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/theme.css" rel="stylesheet">
    <link href="css/sticky-footer-navbar.css" rel="stylesheet">

    <script src="js/ie-emulation-modes-warning.js"></script>
  </head>

  <body>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="home.php">MNWD APP</a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">

          <ul class="nav navbar-nav">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="announcements.php">Announcements</a></li>
            <!--
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Announcements <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Post Announcement</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">View Announcements</a></li>
              </ul>
            </li>
            -->

            <li><a href="incidentreports.php">Incident Reports</a></li>
            <li><a href="profile.php">Profile</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->

      </div>
    </nav>

    <?php
      if (isset($_GET['msg'])) {
        echo "<div class='alert alert-info' role='alert'> $_GET[msg] </div>";
      }
    ?>
    
    <div class="container theme-showcase" role="main">
      <h2>Add Announcement</h2>

      <form action = "addannouncement.php" method="POST">
        <fieldset>
          Announcement:<br>
          <textarea rows ="7" cols ="100" placeholder="Your text here..." name="txtannouncement" required autofocus></textarea><br>
          <input type="hidden" name="ann_date" value="">
          <input type="hidden" name="userid" value="<?php echo $session_id; ?>">
          <input type="submit" name="addannouncement" value="Submit" class='btn btn-sm btn-primary'>
        </fieldset>
      </form>

      <br>

      <link rel="stylesheet" type="text/css" href="photo.css">
      <div id="content">
      <?php
                $dbconn = mysqli_connect("localhost", "root", "", "mnwd");
                $sql = "SELECT * FROM `images`";
                $result = mysqli_query($dbconn, $sql);
                
                while ($row = mysqli_fetch_array($result)) {
                  echo "<div id='img_div'>";
                    echo "<img src='images/".$row['image']."' >";
                  echo "</div>";
                }

      ?>
        <form method="post" action="announcements.php" enctype="multipart/form-data">
          <input type="hidden" name="size" value="1000000">
          <div>
            <input type="file" name="image">
          </div>
          <div>
            <input type="submit" name="upload" value="Upload Image">
          </div>
        </form>
      </div>


      <!--
      <form action="upload.php" method="post" enctype="multipart/form-data">
      Select image to upload:
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="Upload Image" name="submit">
      </form>

      <img src=""

      -->

      <br>
      <h2>View Announcements</h2>
      <?php
        include 'connect.php'; 
        $query = "SELECT * FROM `announcement`";
        $result = mysqli_query($dbconn, $query);
        $count = mysqli_num_rows($result);

        if ($count == 0) {
          echo "0 results";
        }
        else if ($count > 0) {
          echo "<div class='row'><div class='col-md-12'><table class = 'table table-striped'>";
          echo "<tr><th>ID</th><th>Announcement</th><th>Date</th><th colspan = 2></th></tr>";

          while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td> $row[announcementid] </td>";
            echo "<td> $row[announcement] </td>";
            echo "<td> $row[date] </td>";
            echo "<td><a href='editannouncementform.php?edit=$row[announcementid]' class='btn btn-sm btn-primary'>Edit</a></td>";
            echo "<td><a href='deleteannouncement.php?delete=$row[announcementid]' class='btn btn-sm btn-primary'>Delete</a></td>";
            echo "</tr>";
          }
          echo "</table></div></div>";
        }
      ?>
      <br>

      <form action="deleteannouncement.php" method="post">
        <input type="submit" name="delete" value="Delete All" class='btn btn-sm btn-primary'>
      </form>

    </div> <!-- /container -->

    <footer class="footer">
      <div class="container">
        <p class="text-muted">Place sticky footer content here.</p>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../js/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>