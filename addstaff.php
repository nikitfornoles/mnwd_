<?php
	require_once('connect.php');
	require_once('security_check.php');

	if(isset($_POST['addstaff'])) {
		$firstname = test_input($_POST['firstname']);
		$lastname = test_input($_POST['lastname']);
		$email = test_input($_POST['email']);
		$module = test_input($_POST['module']);

		if (empty($firstname) || empty($lastname)) {
			$msg = 'Required Fields';
			header('Location:managestaffs.php?msg='.$msg.'');
		}
		else {
			$status = (!empty($email)? 1:0);
			if ($status) {
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$msg = 'Legit';
				}
				else {
					$msg = 'Invalid email format';
				}
			}
			else {
				$msg = 'No email';
			}
			header('Location:managestaffs.php?msg='.$status.''.$msg.'');
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>