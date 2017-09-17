<?php
	require_once('connect.php');
	require_once('security_check.php');

	if(isset($_POST['addannouncement'])) {
		$announcement = test_input($_POST['txtannouncement']);
		$ann_date = test_input($_POST['ann_date']);
		$userid = test_input($_POST['userid']);

		if (empty($announcement)) {
			$msg = 'Announcement can\'t be blank';
			header('Location:announcements.php?msg='.$msg.'');
		}
		else {
			$sql = "INSERT INTO `announcement` (`announcementid`, `announcement`, `isimage`, `date`, `userid`) 
					VALUES('', '$announcement', 0, CURDATE(), $userid)";
			if (mysqli_query($dbconn, $sql)) {
				$msg = 'Announcement successfully added';
				header('Location:announcements.php?msg='.$msg.'');
			}
			else {
				$msg = 'Error adding announcement';
				header('Location:announcements.php?msg='.$msg.'');
			}
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>