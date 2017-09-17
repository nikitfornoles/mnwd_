<?php
	$userid = 2;
	require_once('mobile_notification_accountactivation.php');
	list ($total_account, $total_activated_account, $accountid, $accountno) = getAccountStats($userid);
	
	echo "Total Account: $total_account <br>";
	echo "Total Activated Account: $total_activated_account <br>";
	echo "Account ID: $accountid <br>";
	echo "Account No: $accountno <br>";
?>