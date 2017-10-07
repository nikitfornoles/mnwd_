<?php

	if ($_SERVER['REQUEST_METHOD']=='GET') {
		$id = $_GET['id'];
		$sql = "SELECT * FROM `announcement` WHERE `announcementid` = '$id'";

		$server = "localhost";
		$user = "root";
		$password = "";
		$database = "mnwd";

		// Create connection
		$conn = mysqli_connect($server, $user, $password);

		// Check connection
		if ($conn) {
			$dbconn = mysqli_connect($server, $user, $password, $database);
		}
		else {
		    die("Connection failed: " . mysqli_connect_error());
		}

		$result = mysqli_query($dbconn,$sql);
		$row = mysqli_fetch_array($result);

		header('content-type: image/jpeg');
		echo base64_decode($row['announcement']);
		mysqli_close($dbconn);
	}
	else {
		echo "Error";
	}
?>