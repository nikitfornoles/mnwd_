<?php
	include '..\connect.php';

	$account_total = "SELECT COUNT(*) FROM `account`";
	$result = mysqli_query($dbconn, $account_total);
	$row = mysqli_fetch_array($result);
	$account_total = $row[0];

	$billdate = '2012-01-19';
	$previous = array_fill(0, $account_total, 0);
	$present = array_fill(0, $account_total, 0);
	$refno_post = array_fill(0, $account_total, '');
	$refno = array_fill(0, $account_total, '');

	for ($i=0; $i < $account_total; $i++) {
		for ($j=0; $j < 3; $j++) { 
			$refno_post [$i] = $refno_post [$i] . mt_rand(0, 9); 
		}
	}

	//January 2012 to September 2017
	for ($i=0; $i < 69; $i++) { 
		$duedate = "SELECT DATE_ADD('$billdate', INTERVAL 10 DAY)";
		$result = mysqli_query($dbconn, $duedate);
		$row = mysqli_fetch_array($result);
		$duedate = $row[0];

		$disconnection = "SELECT DATE_ADD('$billdate', INTERVAL 15 DAY)";
		$result = mysqli_query($dbconn, $disconnection);
		$row = mysqli_fetch_array($result);
		$disconnection = $row[0];

		list($year, $month, $day) = explode("-", $billdate);

		$pred_rand = array(mt_rand(50, 70), mt_rand(30, 50), mt_rand(45, 65), mt_rand(10, 20));
		for ($j=0; $j < $account_total; $j++) {
			$present[$j] = $present[$j] + $pred_rand [$j];
		}

		$myfile = fopen("$billdate.txt", "w+") or die("Unable to open file!");
		chmod("$billdate.txt", 0777);
		
		echo $billdate . "<br>";
		for ($j=0; $j < $account_total; $j++) { 
			$k = $j+1;
			
			$accountno = "SELECT `accountno` FROM `account` WHERE `accountid` = $k";
			$result = mysqli_query($dbconn, $accountno);
			$row = mysqli_fetch_array($result);
			$accountno = $row[0];
			list($first, $second, $third) = explode("-", $accountno); 

			$refno [$j] = substr($year, 2) . $month . $first . $refno_post[$j];
			$billinfo = $billdate . '~' . $previous[$j] . '~' . $present[$j] . '~' . $duedate . '~' . $disconnection . '~' . $refno[$j] . '~' . $k . PHP_EOL;
			echo $billinfo;
			fwrite($myfile, $billinfo);
		}
		fclose($myfile);
		echo "<br>";

		$billdate = "SELECT DATE_ADD('$billdate', INTERVAL 1 MONTH)";
		$result = mysqli_query($dbconn, $billdate);
		$row = mysqli_fetch_array($result);
		$billdate = $row[0];

		for ($j=0; $j < count($previous); $j++) {
			$previous [$j] = $present [$j];
		}
	}
?>