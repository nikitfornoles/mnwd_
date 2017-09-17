<?php
	require_once('connect.php');
	require_once('security_check.php');

	// This script stores a file in a database as binary data.
	if (isset($_POST['upload'])) {
		$userid = test_input($_POST['userid']);
		$msg = "";

		$file = mysqli_real_escape_string($dbconn, $_FILES['fileToUpload']['tmp_name']);
		$file_data = file_get_contents($file);
		$name = mysqli_real_escape_string($dbconn, $_FILES['fileToUpload']['name']);
		$file_size = filesize($file);

		// Get the file's MIME type:
		$file_info = finfo_open(FILEINFO_MIME_TYPE);
		$file_type = finfo_file($file_info, $file);

		if ($file_type != "text/plain") {
			$msg = "Filetype not allowed";
		}
		else {
			$query = "INSERT INTO `meter_reading_file` (`fileid`, `filename`, `file`, `userid`) 
					  VALUES (NULL, '$name', '" . mysqli_real_escape_string($dbconn, $file_data) . "', $userid)";
			$result = mysqli_query($dbconn, $query);

			if (mysqli_affected_rows($dbconn) == 1) {
				$msg = "The file has been stored in the database.";
				$last_id = mysqli_insert_id($dbconn);

				//parse file content and insert into database
				$query = "SELECT * FROM `meter_reading_file` WHERE `fileid` = $last_id";
				$result = mysqli_query($dbconn, $query);

				$row = mysqli_fetch_array($result);
				$file = $row['file'];
				echo "$file <br>";

				$successcount = 0;
				$success = false;

				foreach (preg_split("/((\r?\n)|(\r\n?))/", $file) as $line) {
					$l = $line;
					echo "$line <br>";
					list($billdate, $previous, $present, $consumption, $duedate, $disconnection, $refno, $accountid, $bill) = explode("~", $l);
					echo "$l <br>";

					$sql = "INSERT INTO `reading` (`readingid`, `accountid`, `billingdate`, `previous_reading`, `present_reading`, `consumption`, `bill`, `duedate`, `disconnection_date`, `refno`, `fileid`)
							VALUES ('', $accountid, '$billdate', '$previous', '$present', '$consumption', '$bill', '$duedate', '$disconnection', '$refno', $last_id)";

					if (mysqli_query($dbconn, $sql)) {
						$successcount = $successcount + 1;
						$success = true;
					}
				}

				if ($success) {
					$msg = $msg . " Successfully inserted $successcount records in the database.";
				}
			}
			else {
				$msg = "The file could not be stored in the database.";
			}
		}
		mysqli_close($dbconn);
		header('Location:billinfo.php?msg='.$msg.'');
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}