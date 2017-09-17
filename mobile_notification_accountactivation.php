<?php
	function getAccountStats ($userid, $dbconn) {

		$total_account = null; //check
		$total_activated_account = null;
		$accountid = "NULL";
		$accountno = "NULL";

		$query = "SELECT * FROM `account` WHERE `userid` = $userid";
		$result = mysqli_query($dbconn, $query);
		$total_account = mysqli_num_rows($result);

		if ($total_account > 1) {
			$query = "SELECT * FROM `account` 
						WHERE `userid` = $userid AND `activated` = 1";
			$result = mysqli_query($dbconn, $query);
			$total_activated_account = mysqli_num_rows($result);

			if ($total_activated_account == 1) {
				$row = mysqli_fetch_array($result);
				$accountid = $row['accountid'];
				$accountno = $row['accountno'];
			}
		}
		else {
			$total_activated_account = 1;

			$query = "SELECT * FROM `account` WHERE `userid` = $userid";
			$result = mysqli_query($dbconn, $query);
			$row = mysqli_fetch_array($result);
			$accountid = $row['accountid'];
			$accountno = $row['accountno'];
		}
		return array($total_account, $total_activated_account, $accountid, $accountno);
	}	
?>