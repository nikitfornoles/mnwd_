<?php
  include 'connect.php';
?>

<?php
  session_start();

  if(isset($_SESSION['user_id'])) {
    echo '<script>windows: location="home.php"</script>';
  }
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
	
	<body class="light-gray-bg">
		<?php
			if (isset($_GET['msg'])) {
				echo "<div id = 'res' class='alert alert-info' role='alert'> $_GET[msg] </div>";
			}
		?>
		<br><br><br>
		<div class="templatemo-content-widget templatemo-login-widget white-bg">
			<header class="text-center">
	          <div class="square"></div>
	          <h1>MNWD Admin</h1>
	        </header>
	        <form action="signinprocess.php" method="POST" class="templatemo-login-form" autocomplete="off">
	        	<div class="form-group">
	        		<div class="input-group">
		        		<div class="input-group-addon"><i class="fa fa-user fa-fw"></i></div>	        		
		              	<input type="text" name="username" class="form-control" placeholder="js@dashboard.com" required autofocus autocomplete="off">           
		          	</div>	
	        	</div>
	        	<div class="form-group">
	        		<div class="input-group">
		        		<div class="input-group-addon"><i class="fa fa-key fa-fw"></i></div>	        		
		              	<input type="password" name="password" class="form-control" placeholder="******" required>           
		          	</div>	
	        	</div>	          	
	          	<div class="form-group">
				    <div class="checkbox squaredTwo">
				        <input type="checkbox" id="c1" name="cc" />
						<label for="c1"><span></span>Remember me</label>
				    </div>				    
				</div>
				<div class="form-group">
					<button type="submit" name="login" class="templatemo-blue-button width-100">Login</button>
				</div>
	        </form>
		</div>
	</body>
</html>

<script type="text/javascript">
  $("#res").show().delay(3500).hide(1);
</script>