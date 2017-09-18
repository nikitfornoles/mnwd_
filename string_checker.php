<?php
	function hasLowerCase ($string) {
		if(preg_match('/[a-z]/', $string)){
			return true;
		}
	}

	function hasUpperCase ($string) {
		if(preg_match('/[A-Z]/', $string)){
			return true;
		}
	}

	function hasNumber ($string) {
		if(preg_match('/[0-9]/', $string)){
			return true;
		}	
	}

	function hasPunctuation ($string) {
		if(preg_match("/[\p{P}]/", $string)) {
			return true;
		}
	}
?>