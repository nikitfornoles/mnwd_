<?php
	require 'mobile_billpdf.php';

	//$pdf_file = file_put_contents("temp.pdf", file_get_contents("http://192.168.1.11/mnwd_/mobile_billpdf.php"));

	file_put_contents("temp.pdf", file_get_contents("http://192.168.1.11/mnwd_/mobile_billpdf.php"));

	$sql = "INSERT INTO `bill_pdf` (`accountid`, `readingid`, `pdf`) 
                VALUES ($accountid, $readingid, 'temp.pdf')";
    mysqli_query($dbconn, $query);
?>