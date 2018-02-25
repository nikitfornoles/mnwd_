<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountClassification = test_input($_POST['classification']);
		$cubicMeterUsed = test_input($_POST['cubicmeterused']);
		$meterSize = test_input($_POST['size']);
		$type = test_input($_POST['type']);

		if (empty($accountClassification) || $cubicMeterUsed == "" || empty($meterSize) || empty($type)) {
			echo "Required Fields";
		}
		else {
			$classcode = "SELECT `classcode` FROM `account_classification` 
						  WHERE `classification` = '$accountClassification'";
			$result = mysqli_query($dbconn, $classcode);
			$row = mysqli_fetch_array($result);
			$classcode = $row[0];

			if ($meterSize == "1/2") {
				$meterSize = "0.5";
			}
			else if ($meterSize == "3/4") {
				$meterSize = "0.75";
			}

			$sizeid = "SELECT `sizeid` FROM `meter_size` WHERE `size` = '$meterSize'";
			$result = mysqli_query($dbconn, $sizeid);
			$row = mysqli_fetch_array($result);
			$sizeid = $row [0];

			$type = ($type == "Senior Citizen"? 1:0);

			require_once('mobile_computebill.php');

			$mincharge = computeMinCharge($classcode, $sizeid, $dbconn);
			$min_min = getMinMinimum ($dbconn);
			$max_max = getMaxMaximum ($dbconn);
			$rangecount = getTotalRange ($dbconn);
			$billamount = $mincharge;

			$billamount = computeBill ($dbconn, $cubicMeterUsed, $classcode, $min_min, $rangecount, $billamount);

			list ($billamount, $discount_amount, $discount_rate) = computeDiscount($type, $cubicMeterUsed, $billamount, $dbconn);
			$billamount = number_format((float)$billamount, 2, '.', '');
			$discount_amount = number_format((float)$discount_amount, 2, '.', '');
			$discount_rate = $discount_rate * 100;
			$discount_rate = $discount_rate . "%";

			//creating a blank array
			$r = array();
			array_push(
				$r, 
				array(
					//"readingid" => $readingid,
					"billamount" => $billamount,
					"discount_amount" => $discount_amount,
					"discount_rate" => $discount_rate
				)
			);
			echo json_encode(array('result'=>$r));
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>