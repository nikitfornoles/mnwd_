<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$session_accountid = test_input($_POST['accountid']);
		$userid = test_input($_POST['userid']);

		$r = array();

		require_once('mobile_notification_accountactivation.php');
		list ($total_account, $total_activated_account, $accountid, $accountno) = getAccountStats($userid, $dbconn);

		$non_activated_account = -1;

		if (($total_account > 1) && ($total_account != $total_activated_account)) {
			$non_activated_account = $total_account - $total_activated_account;
		}

		$sql = "SELECT * FROM `reading` WHERE `accountid` = $session_accountid ORDER BY `billingdate` DESC LIMIT 1";
		$result = mysqli_query($dbconn, $sql);
		$row = mysqli_fetch_array($result);

		$billingdate  = $row['billingdate'];
		$bill = $row['bill'];
		$duedate = $row['duedate'];
		$disconnection_date = $row['disconnection_date'];

		$sql = "SELECT CURDATE()";
		$result = mysqli_query($dbconn, $sql);
		$row = mysqli_fetch_array($result);
		$curr_date = $row[0];

		$due_date_countdown = -1;
		if ($curr_date <= $duedate) {
			$sql = "SELECT DATEDIFF('$duedate', '$curr_date')";
			$result = mysqli_query($dbconn, $sql);
			$row = mysqli_fetch_array($result);
			$due_date_countdown = $row[0];
		}

		$disconnection_date_countdown = -1;
		if ($curr_date <= $disconnection_date && $due_date_countdown == -1) {
			$sql = "SELECT DATEDIFF('$disconnection_date', '$curr_date')";
			$result = mysqli_query($dbconn, $sql);
			$row = mysqli_fetch_array($result);
			$disconnection_date_countdown = $row[0];
		}

		array_push(
			$r,
			array (
				"notif1" => $non_activated_account,
				"notif2" => $due_date_countdown,
				"notif3" => $disconnection_date_countdown
			)
		);

		echo json_encode(array('result'=>$r));
	}
	else { 
		echo "<center><h1>Illegal access detected!<h1></center>"; 
	}
	mysqli_close($dbconn);
?>