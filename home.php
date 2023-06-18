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
    <script type="text/javascript" src="js/time.js"></script>
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
            <li><a href="home.php" class="active"><i class="fa fa-home fa-fw"></i>Dashboard</a></li>
            <li><a href="announcements.php"><i class="fa fa-bullhorn fa-fw"></i>Announcements</a></li>
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
          
          <!-- First row start -->
          <div class="templatemo-flex-row flex-content-row templatemo-overflow-hidden"> <!-- overflow hidden for iPad mini landscape view-->
            <div class="col-1 templatemo-overflow-hidden">
              <div class="templatemo-content-widget white-bg templatemo-overflow-hidden">
              <h2 class="text-center">Account Info</h2>
                <i class="fa fa-times"></i>

                <div class="templatemo-flex-row flex-content-row">

                  <div class="col-1 col-lg-6 col-md-12">
                    <div id="id_account_classification" class="templatemo-chart"></div> <!-- Bar chart div -->
                  </div>

                  <div class="col-1 col-lg-6 col-md-12">
                    <div id="id_account_activated" class="templatemo-chart"></div> <!-- Bar chart div -->
                  </div>

                </div>

              </div>
            </div>
          </div>

          <!-- Second row start -->
          <div class="templatemo-flex-row flex-content-row templatemo-overflow-hidden"> <!-- overflow hidden for iPad mini landscape view-->
            <div class="col-1 templatemo-overflow-hidden">
              <div class="templatemo-content-widget white-bg templatemo-overflow-hidden">
                <h2 class="text-center">User Info</h2>
                <i class="fa fa-times"></i>
                <div class="templatemo-flex-row flex-content-row">

                  <div class="col-1 col-lg-6 col-md-12">
                    <div id="id_usertype" class="templatemo-chart"></div> <!-- Pie chart div -->
                  </div>
                  
                  <div class="col-1 col-lg-6 col-md-12">
                    <div id="id_registered_user" class="templatemo-chart"></div> <!-- Pie chart div -->
                  </div>

                </div>                
              </div>
            </div>
          </div>

          <!-- Third row start -->
          <div class="templatemo-flex-row flex-content-row templatemo-overflow-hidden"> <!-- overflow hidden for iPad mini landscape view-->
            <div class="col-1 templatemo-overflow-hidden">
              <div class="templatemo-content-widget white-bg templatemo-overflow-hidden">
                <h2 class="text-center">Consumption Info</h2>
                <i class="fa fa-times"></i>
                <div class="templatemo-flex-row flex-content-row">

                  <div class="col-1 col-lg-6 col-md-12">
                    <div id="id_consumption_monthly" class="templatemo-chart"></div> <!-- Pie chart div -->
                  </div>
                  
                  <div class="col-1 col-lg-6 col-md-12">
                    <div id="id_consumption_yearly" class="templatemo-chart"></div> <!-- Pie chart div -->
                  </div>

                </div>                
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

<script type="text/javascript">
  $("#res").show().delay(3500).hide(1);
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- JS -->
<script src="https://www.google.com/jsapi"></script> <!-- Google Chart -->
<script type="text/javascript" src="js/templatemo-script.js"></script>      <!-- Templatemo Script -->

<script type="text/javascript">
  <?php
    $connect = mysqli_connect("localhost", "nikit", "nikit1234", "mnwd");
    $query = "SELECT `classcode`, count(*) AS `number` FROM `account` GROUP BY `classcode`";
    $result = mysqli_query($connect, $query);
  ?>

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Account Classification', 'Count'],
      <?php
        while($row = mysqli_fetch_array($result)) {
          $classcode = $row["classcode"];
          $sql = "SELECT * FROM `account_classification` WHERE `classcode` = '$classcode'";
          $result1 = mysqli_query($connect, $sql);
          $row1 = mysqli_fetch_array($result1);
          $classification = $row1['classification'];
          echo "['".$classification."', ".$row["number"]."],";
        }
      ?>
    ]);

    var options = {
      title: 'Account Classification',
      //is3D:true,
      //pieHole: 0.4
    };

    var chart = new google.visualization.PieChart(document.getElementById('id_account_classification'));
    chart.draw(data, options);
  }
</script>

<script type="text/javascript">
  <?php
    $connect = mysqli_connect("localhost", "nikit", "nikit1234", "mnwd");
    $query = "SELECT `activated`, count(*) AS `number` FROM `account` GROUP BY `activated`";
    $result = mysqli_query($connect, $query);
  ?>

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Label', 'Count'],
      <?php
        while($row = mysqli_fetch_array($result)) {
          echo "['";
          $label = ($row['activated'] ? "Activated" : "Not Activated");
          echo $label;
          echo "', ";
          echo $row["number"];
          echo "],";
        }
      ?>
    ]);

    var options = {
      title: 'Account Activation',
      //is3D:true,
      //pieHole: 0.4
    };

    var chart = new google.visualization.BarChart(document.getElementById('id_account_activated'));
    chart.draw(data, options);
  }
</script>

<script type="text/javascript">
  <?php
    $connect = mysqli_connect("localhost", "nikit", "nikit1234", "mnwd");
    $query = "SELECT `registered`, count(*) AS `number` FROM `user` GROUP BY `registered`";
    $result = mysqli_query($connect, $query);
  ?>

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Registered', 'Number'],
      <?php
        while($row = mysqli_fetch_array($result)) {

          echo "['";
          if ($row['registered'] == 0) {
            echo "Not Registered";
          }
          else {
            echo "Registered";
          }
          echo "', ";
          echo $row["number"];
          echo "],";
        }
      ?>
    ]);

    var options = {
      title: 'Percentage of Registered and Non-registered User',
      //is3D:true,
      pieHole: 0.4
    };

    var chart = new google.visualization.PieChart(document.getElementById('id_registered_user'));
    chart.draw(data, options);
  }
</script>

<script type="text/javascript">
  <?php
    $connect = mysqli_connect("localhost", "nikit", "nikit1234", "mnwd");
    $query = "SELECT `usertype`, count(*) AS `number` FROM `user` GROUP BY `usertype`";
    $result = mysqli_query($connect, $query);
  ?>

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Usertype', 'Number'],
      <?php
        while($row = mysqli_fetch_array($result)) {
          echo "['".$row["usertype"]."', ".$row["number"]."],";
        }
      ?>
    ]);

    var options = {
      title: 'Usertype',
      //is3D:true,
      //pieHole: 0.4
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('id_usertype'));
    chart.draw(data, options);
  }
</script>

<script type="text/javascript">
  <?php
    $connect = mysqli_connect("localhost", "nikit", "nikit1234", "mnwd");
    $query = "SELECT `consumption`, SUM(consumption) AS `cum` FROM `reading` GROUP BY MONTH(`billingdate`)";
    $result = mysqli_query($connect, $query);
  ?>

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Consumption', 'CuM'],
      <?php
        while($row = mysqli_fetch_array($result)) {
          echo "['".$row["consumption"]."', ".$row["cum"]."],";
        }
      ?>
    ]);

    var options = {
      title: 'Monthly Water Consumption (in CuM)',
      curveType: 'function',
      legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('id_consumption_monthly'));
    chart.draw(data, options);
  }
</script>

<script type="text/javascript">
  <?php
    $connect = mysqli_connect("localhost", "nikit", "nikit1234", "mnwd");
    $query = "SELECT `consumption`, SUM(`consumption`) AS `cum` FROM `reading` GROUP BY YEAR(`billingdate`)";
    $result = mysqli_query($connect, $query);
  ?>

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Consumption', 'CuM'],
      <?php
        while($row = mysqli_fetch_array($result)) {
          echo "['".$row["consumption"]."', ".$row["cum"]."],";
        }
      ?>
    ]);

    var options = {
      title: 'Yearly Water Consumption (in CuM)',
      curveType: 'function',
      legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('id_consumption_yearly'));
    chart.draw(data, options);
  }
</script>