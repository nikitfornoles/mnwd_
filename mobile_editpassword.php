<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$userid = test_input($_POST['userid']);
		$password = test_input($_POST['password']);
		$password_new = test_input($_POST['password_new']);
		$password_confirm = test_input($_POST['password_confirm']);

		if ($password == '' || $password_new == '' || $password_confirm == '') {
			echo "Required Fields";
		}
		else {
			$enc_password = md5($password);
			$query = "SELECT * FROM `user` WHERE `userid` = $userid AND `password` = '$enc_password'";
			$result = mysqli_query($dbconn, $query);
			$count = mysqli_num_rows($result);

			if ($count == 1) {
				if ($password_new == $password_confirm) {
					if (strlen($password_new) < 10) {
						echo "New password is too short. Try again.";
					}
					else {
						require_once('string_checker.php');
						if (!hasLowerCase($password_new) || !hasUpperCase($password_new) || !hasNumber($password_new) || !hasPunctuation($password_new)) {
							echo "Kindly include ";
							if (!hasLowerCase($password_new)) {
								echo "[lowercase character] ";
							}
							if (!hasUpperCase($password_new)) {
								echo "[uppercase character] ";
							}
							if (!hasNumber($password_new)) {
								echo "[number] ";
							}
							if (!hasPunctuation($password_new)) {
								echo "[punctuation] ";
							}
							echo "in your password";
						}
						else {
							$enc_password_new = md5($password_new);
							$sql = "UPDATE `user` SET `password` = '$enc_password_new' WHERE `userid` = $userid";
							if (mysqli_query($dbconn, $sql)) {
								echo "Password updated successfully";
							}
							else {
								echo "Error updating record: ".mysqli_error($dbconn);
							}						
						}
					}
				}
				else {
					echo "New password does not match confirmation. Try again.";
				}
			}
			else {
				echo "Wrong current password";
			}
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>