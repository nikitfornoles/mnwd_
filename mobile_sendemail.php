<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$email = test_input($_POST['email']);
		$accountid = test_input($_POST['accountid']);

		if (empty($email)) {
			echo "Enter email address";
		}
		else {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$sql = "SELECT * FROM `user` WHERE `email` = '$email'";
				$check = mysqli_fetch_array(mysqli_query($dbconn,$sql));

				if (isset($check)) {
					echo 'Another MNWD user is already registered with that email address. Enter another email address.';
				}
				else {
					//find user id
					$userid = "SELECT `userid` FROM `account` WHERE `accountid` = $accountid";
					$result = mysqli_query($dbconn, $userid);
					$row = mysqli_fetch_array($result);
					$userid = $row[0];

					require_once('mobile_generatepassword.php');

					//send generated password to user's email address
					require_once('../phpmailer/PHPMailerAutoload.php');
					$mail = new PHPMailer();
					$mail -> CharSet =  "utf-8";
					$mail -> IsSMTP(); // Set mailer to use SMTP
					$mail -> SMTPDebug = 1;  // Enable verbose debug output
					$mail -> SMTPAuth = true; // Enable SMTP authentication
					$mail -> Username = "mnwdtest@gmail.com"; //Sender's Authentic Email ID
					$mail -> Password = "Nawasa.MetroNaga2"; //Sender's Password
					$mail -> SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
					$mail -> Host = "smtp.gmail.com"; // SMTP 
					$mail -> Port = "587"; // TCP port to connect to

					$mail -> setFrom($mail->Username, 'MNWD');
					$mail -> AddAddress("$email" , "Concessionaire"); //Recipient

					//$mail->addAttachment('path/file.png');         // Add attachments

					$mail -> Subject = "Password";
					$mail -> Body = "<p>Your password is <b>$password</b></p>";
					$mail -> ContentType = "text/html";

					$msg = '';
					if ($mail -> Send()) {
						$msg = "Password successfully sent!$userid";

						//set email and password
						$encpassword = md5($password);
						$sql = "UPDATE `user` 
								SET `password` = '$encpassword', `email` = '$email', `registered` = 1 
								WHERE `userid` = $userid";
						mysqli_query($dbconn, $sql);
						
						if (mysqli_query($dbconn, $sql)) {
							$sql = "UPDATE `account` SET `activated` = 1 WHERE `accountid` = $accountid";
							mysqli_query($dbconn, $sql);
						}
					}
					else{
						$msg = "Error sending password";
					}
					echo "$msg";
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