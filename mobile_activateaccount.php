<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountno = test_input($_POST['accountno']);
		$activation_code = test_input($_POST['activation_code']);

		$sql = "SELECT * FROM `account` WHERE `accountno` = '$accountno'";
		$result = mysqli_query($dbconn, $sql);
		$row = mysqli_fetch_array($result);
		$accountid = $row['accountid'];

		$sql = "SELECT * FROM `account_activation` WHERE `accountid` = $accountid AND `activation_code` = '$activation_code'";
		$result = mysqli_query($dbconn, $sql);
		$count = mysqli_num_rows($result);

		if ($count == 1) {
			$sql = "UPDATE `account` SET `activated` = 1 WHERE `accountno` = '$accountno'";

			if (mysqli_query($dbconn, $sql)) {
				echo "Account successfully activated.";
			}
			else {
				echo "Error in activating account. Please try again.";
			}
		}
		else {
			echo "Wrong activation code";
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>