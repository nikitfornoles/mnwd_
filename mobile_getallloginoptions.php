<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$userid = test_input($_POST['userid']);

		$query = "SELECT * FROM `account` 
					WHERE `userid` = $userid AND `activated` = 1";
		$r = mysqli_query ($dbconn, $query);

		//creating a blank array
		$result = array();

		//looping through all the records fetched
		while ($row = mysqli_fetch_array($r)) {
			$accountid = $row['accountid'];
			$accountno = $row['accountno'];

			//Pushing accountno in the blank array
			array_push(
				$result, 
				array(
					"accountid" => $accountid,
					"accountno" => $accountno
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