<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountid = test_input($_POST['accountid']);

		$sql = "SELECT * FROM `reading` WHERE `accountid` = $accountid ORDER BY `billingdate` DESC LIMIT 2";
		$r = mysqli_query($dbconn, $sql);

		//creating a blank array
		$result = array();

		//looping through all the records fetched
		$index = 0;
		while ($row = mysqli_fetch_array($r)) {
			if ($index == 0) {
				$billingdate = $row['billingdate'];
				$previous_reading = $row['previous_reading'];
				$present_reading = $row['present_reading'];
				$consumption = $row['consumption'];
				$bill = $row['bill'];
				$duedate = $row['duedate'];
				$disconnection_date = $row['disconnection_date'];
				$refno = $row['refno'];

				$query = "SELECT MONTHNAME ('$billingdate')";
				$res = mysqli_query($dbconn, $query);
				$row2 = mysqli_fetch_array($res);

				$billingmonth = $row2[0];

				$query = "SELECT YEAR ('$billingdate')";
				$res = mysqli_query($dbconn, $query);
				$row2 = mysqli_fetch_array($res);
				
				$billingyear = $row2[0];
			}
			else {
				$previous_billingdate = $row['billingdate'];
			}
			$index = $index + 1;
		}

		array_push(
			$result,
			array (
				"billingmonth" => $billingmonth,
				"billingdate" => $billingdate,
				"previous_reading" => $previous_reading,
				"present_reading" => $present_reading,
				"consumption" => $consumption,
				"billamount" => $bill,
				"duedate" => $duedate,
				"disconnection_date" => $disconnection_date,
				"refno" => $refno,
				"previous_billingdate" => $previous_billingdate,
				"billingyear" => $billingyear
			)
		);

		//Displaying the array in json format
		echo json_encode(array('result'=>$result));
	}
	else { 
		echo "<center><h1>Illegal access detected!<h1></center>"; 
	}
	mysqli_close($dbconn);
?>