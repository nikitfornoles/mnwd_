<?php
	function computeMinimumCharge ($accountid, $dbconn) {
		$sql = "SELECT * FROM `account` WHERE `accountid` = $accountid";
		$result = mysqli_query($dbconn, $sql);
		$row = mysqli_fetch_array($result);
		$classcode = $row ['classcode'];
		$sizeid = $row ['sizeid'];

		$sql = "SELECT * FROM `min_charge` WHERE `classcode` = '$classcode' AND `sizeid` = $sizeid";
		$result = mysqli_query($dbconn, $sql);
		$row = mysqli_fetch_array($result);
		$mincharge = $row ['mincharge'];

		return array($classcode, $mincharge);
	}

	function computeMinCharge ($classcode, $sizeid, $dbconn) {
		$sql = "SELECT * FROM `min_charge` WHERE `classcode` = '$classcode' AND `sizeid` = $sizeid";
		$result = mysqli_query($dbconn, $sql);
		$row = mysqli_fetch_array($result);
		$mincharge = $row ['mincharge'];

		return $mincharge;
	}

	function getMinMinimum ($dbconn) {
		$min_min = "SELECT MIN(`min`) FROM `consumption_range`";
		$result = mysqli_query($dbconn, $min_min);
		$row = mysqli_fetch_array($result);
		$min_min = $row[0];
		return $min_min;
	}

	function getMaxMaximum ($dbconn) {
		$max_max = "SELECT MAX(`max`) FROM `consumption_range`";
		$result = mysqli_query($dbconn, $max_max);
		$row = mysqli_fetch_array($result);
		$max_max = $row[0];
		return $max_max;
	}

	function getTotalRange ($dbconn) {
		$rangecount = "SELECT COUNT(*) FROM `consumption_range`";
		$result = mysqli_query($dbconn, $rangecount);
		$row = mysqli_fetch_array($result);
		$rangecount = $row[0];
		return $rangecount;
	}

	function computeBill ($dbconn, $cubic_meter_used, $classcode, $min_min, $rangecount, $billamount) {
		if ($cubic_meter_used >= $min_min) {
			for ($i = 1; $i <= $rangecount; $i++) {
				$sql = "SELECT * FROM `consumption_range` WHERE `rangeid` = '$i'";
				$result = mysqli_query($dbconn, $sql);
				$row = mysqli_fetch_array($result);
				$rangemin = $row['min'];
				$rangemax = $row['max'];

				$rate = "SELECT * FROM `water_rate` WHERE `rangeid` = '$i' AND `classcode` = '$classcode'";
				$result = mysqli_query($dbconn, $rate);
				$row = mysqli_fetch_array($result);
				$rate = $row['rate'];

				if ($cubic_meter_used >= $rangemin) {
					if ($cubic_meter_used >= $rangemax) {
						$multiplier_count = ($rangemax - $rangemin) + 1;
					}
					else {
						$multiplier_count = ($cubic_meter_used - $rangemin) + 1;
					}
					$billamount = $billamount + ($multiplier_count * $rate);
				}
				else {
					break;
				}
			}
		}
		return $billamount;
	}

	function isSeniorCitizen ($seniorcitizen, $predicted_usage, $billamount, $dbconn) {
		list ($billamount, $discount_amount, $discount_rate) = computeDiscount ($seniorcitizen, $predicted_usage, $billamount, $dbconn);
		return $billamount;
	}

	function computeDiscount ($type, $cubicMeterUsed, $billamount, $dbconn) {
		$discount_amount = 0;
		$discount_rate = 0;
		if ($type && $cubicMeterUsed <= 30) {
			$discount_rate = 0.1;
			$discount_amount = $billamount * $discount_rate;
			$billamount = $billamount * (1-$discount_rate);
		}
		return array($billamount, $discount_amount, $discount_rate);
	}
?>