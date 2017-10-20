<?php
	require_once('mobile_connect.php');

	$sql = "SELECT * FROM `announcement` ORDER BY `date` DESC";
	$r = mysqli_query($dbconn,$sql);

	$result = array();
	$http = "http://";
	$ip = "192.168.1.16";
	$url = $http . $ip . "/mnwd_/mobile_getImage.php?id=";

	while ($row = mysqli_fetch_array($r)) {
		array_push(
			$result,
			array(
				"url" => $url.$row['announcementid'],
				"date" => $row['date']
			)
		);
	}

	echo json_encode(array("result"=>$result));
	mysqli_close($dbconn);
?>