<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountid = test_input($_POST['accountid']);

		$sql = "SELECT * FROM `reading` WHERE `accountid` = $accountid ORDER BY `billingdate` DESC";
		$r = mysqli_query($dbconn, $sql);

		//creating a blank array
		$result = array();

		//looping through all the records fetched
		while ($row = mysqli_fetch_array($r)) {
			$billingdate = $row['billingdate'];
			$cubic_meter_used = $row['consumption'];
			$billamount = $row['bill'];
			$billamount = number_format((float)$billamount, 3, '.', '');

			//Pushing readingid, billingdate, and consumption in the blank array
			array_push(
				$result, 
				array(
					//"readingid" => $readingid,
					"billingdate" => $billingdate,
					"consumption" => $cubic_meter_used,
					"billamount" => $billamount
				)
			);
		}

		//Displaying the array in json format
		echo json_encode(array('result'=>$result));
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>