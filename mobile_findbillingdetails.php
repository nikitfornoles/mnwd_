<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$givenBillingdate = test_input($_POST['billingdate']);
		$givenReferenceNo = test_input($_POST['referenceno']);
		$accountid = test_input($_POST['accountid']);

		if (empty($givenReferenceNo)) {
			echo "Enter correct reference number.";
		}
		else {
			$sql = "SELECT * FROM `reading` 
					WHERE `billingdate` = '$givenBillingdate' 
					AND `accountid` = '$accountid' 
					AND `refno` = '$givenReferenceNo'";
			$result = mysqli_query($dbconn, $sql);
			$row = mysqli_fetch_array($result);
			
			if (mysqli_num_rows($result) == 1) {
				echo "account billing details found";
			}
			else {
				echo "billing details not found." ;	
			}
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>