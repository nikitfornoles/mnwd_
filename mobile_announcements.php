<?php
	require_once('mobile_connect.php');

	$sql = "SELECT * FROM `announcement` WHERE `isimage` = 0 ORDER BY `date` DESC";
	$r = mysqli_query($dbconn, $sql);

	//creating a blank array
	$result = array();

	//looping through all the records fetched
	while ($row = mysqli_fetch_array($r)) {
		//Pushing  announcementid and announcement in the blank array
		array_push (
			$result, 
			array(
				"announcementid" => $row['announcementid'],
				"announcement" => $row['announcement'],
				"date" => $row['date']
			)
		);
	}

	//Displaying the array in json format
	echo json_encode(array('result'=>$result));
	
	mysqli_close($dbconn);
?>