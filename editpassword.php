<?php
	require_once('connect.php');
	require_once('security_check.php');

	if (isset($_POST['editpassword'])) {
		$id = test_input($_POST['userid']);
		$currentPassword = test_input($_POST['currentpassword']);
		$newPassword = test_input($_POST['newpassword']);
		$newPassword2 = test_input($_POST['newpassword2']);

		if (empty($currentPassword) || empty($newPassword) || empty($newPassword2)) {
			$msg = 'Required Fields';
			header('Location:profile.php?msg='.$msg.'');
		}
		else {
			$encCurrentPassword = md5($currentPassword);

			//check if given current password is correct
			$query = "SELECT * FROM `user` WHERE `userid` = $id AND `password` = '$encCurrentPassword'";
			$result = mysqli_query($dbconn, $query) or die (mysqli_error($dbconn));
			$count = mysqli_num_rows($result);

			//current password given is correct
			if ($count == 1) {
				if ($newPassword == $newPassword2) {
					$encNewPassword = md5($newPassword);
					$sql = "UPDATE `user` SET `password` = '$encNewPassword'  WHERE `userid` = $id";
					if (mysqli_query($dbconn, $sql)) {
						$msg = "Password updated successfully";
					}
					else {
						$msg = "Error updating record: ".mysqli_error($dbconn);
					}
					header("Location:profile.php?msg=".$msg.'');
				}
				else {
					$msg = "Password confirmation does not match new password.";
					header("Location:profile.php?msg=".$msg.'');
				}
			}
			else {
				$msg = 'Wrong current password';
				header('Location:profile.php?msg='.$msg.'');
			}
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>