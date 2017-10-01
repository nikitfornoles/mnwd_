<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountnum = test_input($_POST['accountno']);

		$sql = "SELECT * FROM `account` WHERE `accountno` = '$accountnum'";
		$result = mysqli_query($dbconn, $sql);

		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_array($result);
			$activated = $row['activated'];
			$userid = $row['userid'];

			$query = "SELECT * FROM `user` WHERE `userid` = $userid";
			$res = mysqli_query($dbconn, $query);
			$row = mysqli_fetch_array($res);
			$registered = $row['registered'];

			if ($registered) {
				if ($activated) {
					echo "You are already registered and this account number is already activated. Proceed to login.";
				}
				else {
					echo "You are already registered using another account that you had activated. Proceed to login and activate this account.";
				}
			}
			else {
				$accountid = $row['accountid'];
				echo "Account ID: $accountid";
			}
		}
		else {
			echo "account number doesn't exist";
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>