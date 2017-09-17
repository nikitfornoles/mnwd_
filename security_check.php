<?php	
	function test_input ($data) {
		//Strip unnecessary characters (extra space, tab, newline) from the user input data
		$data = trim($data);

		//Remove backslashes (\) from the user input data
		$data = stripslashes($data);

		//Avoid $_SERVER["PHP_SELF"] Exploits
		$data = htmlspecialchars($data);
		
		return $data;
	}
?>