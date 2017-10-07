<?php
	include 'connect.php';

	//delete a record
	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];

		$sql = "DELETE FROM `announcement` WHERE `announcementid` = $id";

		if (mysqli_query($dbconn, $sql)) {
			$msg = "Announcement successfully deleted.";
		}
		else {
			$msg = "Error deleting announcement: ".mysqli_error($dbconn);
		}
		header("Location:announcements.php?msg=".$msg.'');
	}

	//delete all records
	if(isset($_POST['delete'])) {
		$sql = "DELETE FROM `announcement`";
		if (mysqli_query($dbconn, $sql)) {
			$msg = "Successfully deleted all announcements.";
		}
		else {
			$msg = "Error deleting all announcements: ".mysqli_error($dbconn);
		}
		header("Location:announcements.php?msg=".$msg.'');
	}

	mysqli_close($dbconn);
?>