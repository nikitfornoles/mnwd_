<?php
	require_once('mobile_connect.php');
	require_once('security_check.php');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$ip = test_input($_POST['ip']);
		$port = test_input($_POST['port']);
		$repository = test_input($_POST['repository']);

		$sql = "SELECT * FROM `announcement` ORDER BY `date` DESC";
		$r = mysqli_query($dbconn,$sql);

		$result = array();
		$http = "http://";
		$url = $http . $ip . ":" . $port . $repository . "mobile_getImage.php?id=";

		while ($row = mysqli_fetch_array($r)) {
			$expiration_date = $row['expiration_date'];
			$expiration_date = new DateTime ("$expiration_date");

			$now = new DateTime();
			$now = date("Y-m-d", strtotime('+7 hours'));
			$now = new DateTime("$now");

			$announcementid = $row['announcementid'];

			if ($expiration_date > $now) {
				array_push(
					$result,
					array(
						"url" => $url.$announcementid,
						"date" => $row['date']
					)
				);
			}
			else {
				$sql1 = "DELETE FROM `announcement` WHERE `announcementid` = $announcementid";
				mysqli_query($dbconn, $sql1);
			}
		}

		echo json_encode(array("result"=>$result));
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>