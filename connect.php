<?php
	$server = "localhost";
	$user = "nikit";
	$password = "nikit1234";
	$database = "mnwd";
	$port = "5486";
	// $url = "mysql://${{ MYSQLUSER }}:${{ MYSQLPASSWORD }}@${{ MYSQLHOST }}:${{ MYSQLPORT }}/${{ MYSQLDATABASE }}";

	// Create connection
	$conn = mysqli_connect($server, $user, $password);

	// Check connection
	if ($conn) {
		$dbconn = mysqli_connect($server, $user, $password, $database);
		
		if (!$dbconn) {
			$msg = "cannot connect to the database";
		}
		else {
			$msg = "connection success";
		}
	}
	else {
	    die("Connection failed: " . mysqli_connect_error());
	}
?>