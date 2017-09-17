<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$userid = test_input($_POST['userid']);
		$session_accountid = test_input($_POST['accountid']);

		require_once('mobile_notification_accountactivation.php');
		list ($total_account, $total_activated_account, $accountid, $accountno) = getAccountStats($userid, $dbconn);

		if ($total_account == 1) {
			echo "~You have no other account to switch to.";
		}
		else if ($total_account > 1) {
			if ($total_activated_account == 1) {
				$total_inactive_account = $total_account - $total_activated_account;
				echo "~No other activated account to switch to. You have $total_inactive_account non-activated account/s."; 
			}
			else if ($total_activated_account > 1) {
				$query = "SELECT * FROM `account` 
							WHERE `userid` = $userid 
							AND `activated` = 1 
							AND `accountid` != $session_accountid";
				$r = mysqli_query($dbconn, $query);

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
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>