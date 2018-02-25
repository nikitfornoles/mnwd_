<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountid = test_input($_POST['accountid']);

		$sql = "SELECT DISTINCT (SELECT EXTRACT(YEAR FROM `billingdate`)) FROM `reading` WHERE `accountid` = $accountid ORDER BY `billingdate` DESC";
		$r = mysqli_query($dbconn, $sql);

		//creating a blank array
		$result = array();

		//looping through all the records fetched
		while ($row = mysqli_fetch_array($r)) {
			$billingyear = $row[0];

			//Pushing billingdate, consumption and billamount in the blank array
			array_push(
				$result, 
				array(
					"billingyear" => $billingyear
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