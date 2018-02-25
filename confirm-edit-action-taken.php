<?php
	include 'connect.php';
	include 'security_check.php';
	
	$reportid = $_POST['reportid'];
	$action_taken = $_POST['action_taken'];

	$query = "UPDATE `incident_report` SET `action_taken` = '$action_taken' WHERE `reportid` = $reportid";

	if (mysqli_query($dbconn, $query)) {
		$msg = "Action taken successfully updated.";
	}
	else {
		$msg = mysqli_error($dbconn);
	}
	header('Location:incidentreports.php?msg='.$msg.'');	
?>