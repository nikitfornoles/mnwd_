<!DOCTYPE html>
<html>
<body>
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
	?>
</body>
</html>