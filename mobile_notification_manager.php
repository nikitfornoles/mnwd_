<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$session_accountid = test_input($_POST['accountid']);
		$userid = test_input($_POST['userid']);

		$result = array();

		require_once('mobile_notification_accountactivation.php');
		list ($total_account, $total_activated_account, $accountid, $accountno) = getAccountStats($userid, $dbconn);

		if (($total_account > 1) && ($total_account != $total_activated_account)) {
			$non_activated_account = $total_account - $total_activated_account;
			$notif_account_activation = "You have $non_activated_account non activated accounts.";
			array_push(
				$result,
				array(
					"notif_account_activation"
				)
			);
		}

		
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>