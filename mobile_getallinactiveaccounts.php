<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$userid = test_input($_POST['userid']);

		require_once('mobile_notification_accountactivation.php');
		list ($total_account, $total_activated_account, $accountid, $accountno) = getAccountStats($userid, $dbconn);

		$total_inactive_account = $total_account - $total_activated_account;
		if ($total_account == 1) {
			echo "~You have no other account to activate.";
		}
		else if ($total_account > 1) {
			if ($total_inactive_account == 0) {
				echo "~All of your accounts are already activated.";
			}
			else {
				$query = "SELECT * FROM `account` 
							WHERE `userid` = $userid
							AND `activated` = 0";
				$r = mysqli_query($dbconn, $query);

				//creating a blank array
				$result = array();

				//looping through all the records fetched
				while ($row = mysqli_fetch_array($r)) {
					$accountno = $row['accountno'];

					//Pushing accountno in the blank array
					array_push(
						$result, 
						array(
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