<?php
	require_once('connect.php');
	require_once('security_check.php');

	if (isset($_POST['editann'])) {
		$announcementid = test_input($_POST['announcementid']);
		$edit_ann = test_input($_POST['edit_ann']);
		
		$sql = "UPDATE `announcement` SET `announcement` = '$edit_ann'  WHERE `announcementid` = $announcementid";

		if (mysqli_query($dbconn, $sql)) {
			$msg = "Announcement successfully updated.";
			echo $msg;
		}
		else {
			$msg = "Error updating record: ".mysqli_error($dbconn);
		}
		header("Location:announcements.php?msg=".$msg.'');
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>