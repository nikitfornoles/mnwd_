<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$email = test_input($_POST['email']);
		$password = test_input($_POST['password']);

		if (empty($email) || empty($password)) {
			echo "Required fields";
		}
		else {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$encpassword = md5($password);
				$query = "SELECT * FROM `user` WHERE `email` = '$email' AND `password` = '$encpassword'";
				$result = mysqli_query($dbconn, $query);

				if (mysqli_num_rows($result) > 0) {
					$row = mysqli_fetch_array($result);
					$userid = $row['userid'];
					$firstname = $row['firstname'];
					$lastname = $row['lastname'];
					$usertype = $row['usertype'];

					if ($usertype == 'concessionaire') {
						require_once('mobile_notification_accountactivation.php');
						list ($total_account, $total_activated_account, $accountid, $accountno) = getAccountStats($userid, $dbconn);

						echo "login successful!$userid~$total_account~$total_activated_account~$accountid~$accountno~$firstname~$lastname";
					}
					else if ($usertype == 'admin') {
						echo "Admin accounts can only access the web app";
					}
				}
				else {
					$query = "SELECT * FROM `user` WHERE `email` = '$email'";
					$result = mysqli_query($dbconn, $query);
					$count = mysqli_num_rows($result);

					if ($count == 1) {
						echo 'Wrong password';
					}
					else {
						echo 'No user is registered with that email address';
					}
				}
			}
			else {
				echo "Invalid email format";
			}
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>