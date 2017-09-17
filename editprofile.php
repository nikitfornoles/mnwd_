<?php
	require_once('connect.php');
	require_once('security_check.php');

	$msg = "";
	if (isset($_POST['profileedit'])) {
		$firstname = test_input($_POST['firstname']);
		$lastname = test_input($_POST['lastname']);
		$username = test_input($_POST['username']);
		$email = test_input($_POST['email']);
		$userid = test_input($_POST['userid']);

		if (empty($firstname) && empty($lastname) && empty($email) && empty($username)) {
			$msg = $msg . "There were no changes made in the profile. ";
		}
		else {
			if (!empty($email)) {
				$sql = "SELECT * FROM `user` WHERE `email` = '$email'";
				$check = mysqli_fetch_array(mysqli_query($dbconn,$sql));

				if (isset($check)) {
					$msg = $msg . "Email already exists. ";
				}
				else {
					$sql = "UPDATE `user` SET `email` = '$email' WHERE `userid` = $userid";
					if (mysqli_query($dbconn, $sql)) {
						$msg = $msg . "Email successfully changed. ";
					}
					else {
						$msg = $msg . "Error updating email. ";
					}
				}
			}

			if (!empty($username)) {
				$sql = "SELECT * FROM `user` WHERE `username` = '$username'";
				$check = mysqli_fetch_array(mysqli_query($conn,$sql));

				if (isset($check)) {
					$msg = $msg . "Username already exists. ";
				}
				else {
					$sql = "UPDATE `user` SET `username` = '$username' WHERE `userid` = $userid";
					if (mysqli_query($dbconn, $sql)) {
						$msg = $msg . "Username successfully changed. ";
					}
					else {
						$msg = $msg . "Error updating username. ";
					}
				}
			}

			if (!empty($firstname)) {
				session_start();
				$sql = "UPDATE `user` SET `firstname` = '$firstname' WHERE `userid` = $userid";
				if (mysqli_query($dbconn, $sql)) {
					$msg = $msg . "First name successfully changed. ";
					$_SESSION['firstname'] = $firstname;
				}
				else {
					$msg = $msg . "Error updating first name. ";
				}	
			}

			if (!empty($lastname)) {
				session_start();
				$sql = "UPDATE `user` SET `lastname` = '$lastname' WHERE `userid` = $userid";
				if (mysqli_query($dbconn, $sql)) {
					$msg = $msg . "Last name successfully changed. ";
					$_SESSION['lastname'] = $lastname;
				}
				else {
					$msg = $msg . "Error updating last name. ";
				}	
			}
		}
		mysqli_close($dbconn);
		header('Location:profile.php?msg='.$msg.'');
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
?>