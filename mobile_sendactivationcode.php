<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountno = test_input($_POST['accountno']);
		$email = test_input($_POST['email']);

		$sql = "SELECT * FROM `account` WHERE `accountno` = '$accountno'";
		$result = mysqli_query($dbconn, $sql);
		$row = mysqli_fetch_array($result);
		$accountid = $row['accountid'];

		$sql = "SELECT * FROM `account_activation` WHERE `accountid` = $accountid";
		$check = mysqli_fetch_array(mysqli_query($dbconn,$sql));

		if (isset($check)) {
			echo "An activation code for account number $accountno was already sent to your email address.";
		}
		else {
			$accountid = $row['accountid'];


			$activation_code = '';
			for ($i=0; $i < 6; $i++) { 
				$activation_code = $activation_code . mt_rand(0, 9);
			}

			//send generated activation code to user's email address
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

			$mail -> Subject = "Activation Code";
			$mail -> Body = "<p>The activation code for your account number $accountno is <b>$activation_code</b></p>";
			$mail -> ContentType = "text/html";

			$msg = '';
			if ($mail -> Send()) {
				$msg = "Activation code sent.";
				$sql = "INSERT INTO `account_activation` (`accountid`, `activation_code`)
						VALUES ($accountid, '$activation_code')";
				
				if (mysqli_query($dbconn, $sql)) {
					$msg = "Activation code successfully set." . $msg;
				}
				else {
					$msg = "Error setting account activation code.";
				}
			}
			else{
				$msg = "Error sending activation code";
			}
			echo "$msg";
		}
	}
	else { 
		echo "<center><h1>Illegal access detected!<h1></center>"; 
	}
	mysqli_close($dbconn);
?>