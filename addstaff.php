<?php
	require_once('connect.php');
	require_once('security_check.php');

	if (isset($_POST['add_staff'])) {
		$firstname = test_input($_POST['firstname']);
		$lastname = test_input($_POST['lastname']);
		$email = test_input($_POST['email']);

		$modulename = test_input($_POST['employee_option']);
		$employeeID = test_input($_POST['employeeID']);

		//SET VALUES OF OTHER ATTRIBUTES
		$usertype = 'staff';

		# generate unique username
		# [Lastname]_[first 3 letters of Firstname][integer]
		# ----------------------------------------------------------------------
		$username = $lastname . '_';
		for ($i=0; $i<strlen($firstname); $i++) {
			$username = $username . $firstname[$i];
			if ($i == 2) {
				break;
			}
		}

		for ($i=1; $i < 100 ; $i++) {
			$username_ = $username . $i;

			$query = "SELECT * FROM `user` WHERE `username` = '$username_'";
			$check = mysqli_fetch_array(mysqli_query($dbconn, $query));

			if (!isset($check)) {
				$username = $username_;
				break;
			}
		}

		//generate password
		require_once('mobile_generatepassword.php');
		$encpassword = md5($password);


		# SEND GENERATED PASSWORD AND USERNAME TO STAFF'S EMAIL ADDRESS
		#________________________________________________________________________
		require_once('../phpmailer/PHPMailerAutoload.php');

		$mail = new PHPMailer(true);
		try {
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
			$mail -> AddAddress("$email" , "Staff"); //Recipient

			//$mail->addAttachment('path/file.png');         // Add attachments

			$mail -> Subject = "Login Details";
			$mail -> Body = "<p>You may now login using <b>$username</b> as username and <b>$password</b> as password.</p>";
			$mail -> ContentType = "text/html";

			$msg = '';
			# IF EMAIL WAS SUCCESSFULLY SENT
			if ($mail -> send()) {
				//$msg = "Login details successfully sent.";

				# ADD employee as Web App User
				$sql = "INSERT INTO `user` (`userid`, `firstname`, `lastname`, `usertype`, `username`, `password`, `email`, `registered`, `seniorcitizen`)
							VALUES ('', '$firstname', '$lastname', '$usertype', '$username', '$encpassword', '$email', 1, 0)";
				$result = mysqli_query($dbconn, $sql);

				if (mysqli_affected_rows($dbconn) == 1) {
					$userID = mysqli_insert_id($dbconn);

					//set username and password
					$sql1 = "INSERT INTO `staffinfo` (`staffinfoid`, `modulename`, `employeeid`, `userid`)
							VALUES ('', '$modulename', '$employeeID', '$userID')";

					if (mysqli_query($dbconn, $sql1)) {
						$msg = 'Staff successfully added';
						header('Location:managestaffs.php?msg='.$msg.'');
					}
					else {
						$msg = 'Error inserting in staffinfo table';
						header('Location:managestaffs.php?msg='.$msg.'');	
					}
				}
				else {
					$msg = 'Error inserting in user table';
					header('Location:managestaffs.php?msg='.$msg.'');
				}
			}
			else {
				# IF EMAIL WASN'T SUCCESSFULLY SENT TO STAFF'S EMAIL ADDRESS
				$msg = "T_TError sending login details. Mailer Error: " . $mail->ErrorInfo;
				header('Location:managestaffs.php?msg='.$msg.'');
			}
		}
		catch (Exception $e) {
			# IF EMAIL WASN'T SUCCESSFULLY SENT TO STAFF'S EMAIL ADDRESS
			$msg = "Error sending login details. Mailer Error: " . $mail->ErrorInfo;
			header('Location:managestaffs.php?msg='.$msg.'');
		}
		#______________ END OF CODE BLOCK FOR SENDING EMAIL _____________________

		mysqli_close($dbconn);
		//header('Location:managestaffs.php?msg='.$employeeID.''.$modulename.''.$msg.'');
	}
?>