<?php
	include 'connect.php';
	$query = "SELECT * FROM `incident_report` ORDER BY `reportdate` DESC";
	$result = mysqli_query($dbconn, $query);
	$count = mysqli_num_rows($result);

	if ($count > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$reportid = $row['reportid'];
			$incidentid = $row['incidentid'];
			$accountid = $row['accountid'];
			$description = $row['description'];
			$reportdate = $row['reportdate'];

			$incidenttype = "SELECT `incidenttype` FROM `incident` WHERE `incidentid` = $incidentid";
			$res = mysqli_query ($dbconn, $incidenttype);
			$rowi = mysqli_fetch_array($res);
			$incidenttype = $rowi['incidenttype'];

			$sql = "SELECT * FROM `account` WHERE `accountid` = $accountid";
			$res = mysqli_query ($dbconn, $sql);
			$row = mysqli_fetch_array($res);
			$accountno = $row['accountno'];
			$address = $row['address'];
			$userid = $row['userid'];

			$sql = "SELECT * FROM `user` WHERE `userid` = $userid";
			$res = mysqli_query ($dbconn, $sql);
			$row = mysqli_fetch_array($res);
			$firstname = $row['firstname'];
			$lastname = $row['lastname'];
		}
	}
?>