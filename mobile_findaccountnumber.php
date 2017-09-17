<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountnum = test_input($_POST['accountno']);

		if (empty($accountnum)) {
			echo "Enter account number";
		}
		else {
			$sql = "SELECT * FROM `account` WHERE `accountno` = '$accountnum'";
			$result = mysqli_query($dbconn, $sql);

			if (mysqli_num_rows($result) == 1) {
				$row = mysqli_fetch_array($result);
				$activated = $row['activated'];

				if ($activated == 1) {
					echo "Account number is already registered";
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
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>