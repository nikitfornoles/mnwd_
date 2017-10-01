<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountid = test_input($_POST['accountid']);

		$sql = "SELECT * FROM `reading` WHERE `accountid` = $accountid ORDER BY `billingdate` DESC LIMIT 1";
		$result = mysqli_query($dbconn, $sql);

		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_array($result);
			$billingdate = $row['billingdate'];

			list($year, $month, $day) = explode("-", $billingdate);

			//creating a blank array
			$result = array();
			array_push(
				$result,
				array("billingyear" => $year)
			);

			//Displaying the array in json format
			echo json_encode(array('result'=>$result));
		}
		else {
			echo "There are no bills yet that are associated with this account.";
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>