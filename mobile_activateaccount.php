<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountno = test_input($_POST['accountno']);

		$sql = "UPDATE `account` SET `activated` = 1 WHERE `accountno` = '$accountno'";

		if (mysqli_query($dbconn, $sql)) {
			echo "Account successfully activated.";
		}
		else {
			echo "Error in activating account. Please try again.";
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>