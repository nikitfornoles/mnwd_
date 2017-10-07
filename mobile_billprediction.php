<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$session_accountid = test_input($_POST['accountid']);

		$sql = "SELECT * FROM `account` WHERE `accountid` = $session_accountid";
		$result = mysqli_query($dbconn, $sql);
		$row = mysqli_fetch_array($result);

		$classcode = $row['classcode'];
		$sizeid = $row['sizeid'];
		$seniorcitizen = $row['seniorcitizen'];

		$sql = "SELECT * FROM `reading` WHERE `accountid` = $session_accountid ORDER BY `billingdate` DESC LIMIT 6";
		$result = mysqli_query($dbconn, $sql);

		$sum = 0;
		$count = 0;
		$latestbilldate;

		while ($row = mysqli_fetch_array($result)) {
			$billingdate = $row['billingdate'];
			$consumption = $row['consumption'];

			$sum = $sum + $consumption;
			if ($count == 0) {
				$latestbilldate = $billingdate;
			}
			$count = $count + 1;
		}

		$predicted_usage = $sum / 6;
		$predicted_usage = round($predicted_usage);
		$billamount = 0;

		require_once('mobile_computebill.php');

		$mincharge = computeMinCharge($classcode, $sizeid, $dbconn);
		$min_min = getMinMinimum ($dbconn);
		$max_max = getMaxMaximum ($dbconn);
		$rangecount = getTotalRange ($dbconn);
		$billamount = $mincharge;

		$billamount = computeBill ($dbconn, $predicted_usage, $classcode, $min_min, $rangecount, $billamount);
		$billamount = isSeniorCitizen($seniorcitizen, $predicted_usage, $billamount, $dbconn);
		$billamount = number_format((float)$billamount, 2, '.', '');

		echo "$latestbilldate~$predicted_usage~$billamount";
	}
	else { 
		echo "<center><h1>Illegal access detected!<h1></center>"; 
	}
	mysqli_close($dbconn);
?>