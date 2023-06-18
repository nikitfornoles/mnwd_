<?php
	$conn = mysqli_connect('localhost', 'nikit', 'nikit1234');
	if (!$conn) {
		die('Could not connect: ' . mysql_error());
	}
	else {
		$conn2 = mysqli_connect('localhost', 'nikit', 'nikit1234', "waterbilling");
	}
?>