<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$incidentid = test_input($_POST['incidentid']);
		$accountid = test_input($_POST['accountid']);
		$description = test_input($_POST['description']);

		if (empty($incidentid) || empty($description)) {
			echo "Required Fields";
		}
		else {
			$sql = "INSERT INTO `incident_report` (`reportid`, `incidentid`, `accountid`, `description`, `reportdate`) 
					VALUES ('', $incidentid, $accountid, '$description', CURDATE())";

			if (mysqli_query($dbconn, $sql)) {
				echo "Incident successfully reported";
			}
			else {
				echo "Error in reporting incident";
			}
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>