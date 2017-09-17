<?php
	$password = md5(uniqid(mt_rand(), true));

	$LETTERS = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	$LETTERS_length = count($LETTERS);
	$LETTERS_random_index = rand(0, $LETTERS_length - 1);

	$PUNCTUATIONS = array('.', '!', '-', '_');
	$PUNCTUATIONS_length = count($PUNCTUATIONS);
	$PUNCTUATIONS_random_index = rand(0, $PUNCTUATIONS_length - 1);

	//insert letter
	$replacement = $LETTERS[$LETTERS_random_index];
	$password_length = strlen($password);
	$password_random_index = rand(0, $password_length-1);
	$password = substr_replace($password, $replacement, $password_random_index, 0);

	//insert punctuation
	$replacement = $PUNCTUATIONS[$PUNCTUATIONS_random_index];
	$password_length = strlen($password);
	$password_random_index = rand(0, $password_length-1);
	$password = substr_replace($password, $replacement, $password_random_index, 0);

	echo "Password: $password <br>";
	echo "Password Length: " . strlen($password) . "<br>";

	require_once('phpmailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();
	$mail -> CharSet =  "utf-8";
	$mail -> IsSMTP(); // Set mailer to use SMTP
	$mail -> SMTPDebug = 0;  // Enable verbose debug output
	$mail -> SMTPAuth = true; // Enable SMTP authentication
	$mail -> Username = "mnwdtestwaterdistrict@gmail.com"; //Your Auth Email ID
	$mail -> Password = "Nawasa.MetroNaga2"; //Your Password
	$mail -> SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
	$mail -> Host = "smtp.gmail.com"; // SMTP 
	$mail -> Port = "587"; // TCP port to connect to

	$mail -> setFrom($mail->Username, 'MNWD');
	$mail -> AddAddress("cfornoles@gbox.adnu.edu.ph" , "Concessionaire"); // Add a recipient

	//$mail->addAttachment('path/file.png');         // Add attachments

	$mail -> Subject = "Password";
	$mail -> Body = "<p>Your password is <b>$password</b></p>";
	$mail -> ContentType = "text/html";

	$msg = '';
	if ($mail -> Send()) {
		$msg = "OK";
	}
	else{
		$msg = "ERR";
	}
	echo "$msg";
?>