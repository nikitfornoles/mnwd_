<?php
	$conn = mysqli_connect('localhost', 'root', '');
	if (!$conn) {
		die('Could not connect: ' . mysql_error());
	}
	else {
		$conn2 = mysqli_connect('localhost', 'root', '', "waterbilling");
	}
?>