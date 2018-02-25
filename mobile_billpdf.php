<?php
	require 'fpdf/fpdf.php';
	require_once('security_check.php');

	$dbconn = mysqli_connect('localhost', 'root', '', 'mnwd');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountid = test_input($_POST['accountid']);
		$ip = test_input($_POST['ip']);
		$port = test_input($_POST['port']);

		$sql = "SELECT * FROM `reading` WHERE `accountid` = $accountid ORDER BY `billingdate` DESC LIMIT 2";
		$result = mysqli_query($dbconn, $sql);

		//looping through all the records fetched
		$index = 0;
		while ($row = mysqli_fetch_array($result)) {
			if ($index == 0) {
				$readingid = $row['readingid'];
				$billingdate = $row['billingdate'];
				$previous_reading = $row['previous_reading'];
				$present_reading = $row['present_reading'];
				$consumption = $row['consumption'];
				$bill = $row['bill'];
				$duedate = $row['duedate'];
				$disconnection_date = $row['disconnection_date'];
				$refno = $row['refno'];

				$query = "SELECT MONTHNAME ('$billingdate')";
				$res = mysqli_query($dbconn, $query);
				$row2 = mysqli_fetch_array($res);
				$billingmonth = $row2[0];

				$query = "SELECT YEAR ('$billingdate')";
				$res = mysqli_query($dbconn, $query);
				$row2 = mysqli_fetch_array($res);
				$billingyear = $row2[0];

				$query = "SELECT * FROM `account` WHERE `accountid` = $accountid";
				$res = mysqli_query($dbconn, $query);
				$row2 = mysqli_fetch_array($res);
				$accountno = $row2['accountno'];
				$meterno = $row2['meterno'];
				$userid = $row2['userid'];

				$query = "SELECT * FROM `user` WHERE `userid` = $userid";
				$res = mysqli_query($dbconn, $query);
				$row2 = mysqli_fetch_array($res);
				$firstname = $row2['firstname'];
				$lastname = $row2['lastname'];
			}
			else {
				$previous_billingdate = $row['billingdate'];
			}
			$index = $index + 1;
		}

		class myPDF extends FPDF
		{
			function header()
			{
				$this -> Image('images/smalllogo.png', 30, 12);

				$this -> SetFont('Arial', 'B', 14);
				$this -> Cell(190, 5, 'Metropolitan Naga Water District', 0, 0, 'C');
				$this -> Ln(10);

				$this -> SetFont('Times', '', 12);
				$this -> Cell(190, 5, '#40 J. Miranda Ave., Naga City', 0, 0, 'C');
				$this -> Ln();

				$this -> Cell(190, 5, 'Camarines Sur 4400', 0, 0, 'C');
				$this -> Ln(20);

				$this -> SetFont('Arial', 'B', 14);
				$this -> Cell(190, 10, 'BILLING NOTICE', 0, 0, 'C');
				$this -> Ln();

				$this -> SetFont('Times', '', 12);
				$this -> Cell(190, 1, '____________________________', 0, 0, 'C');
				$this -> Ln(20);
			}

			function viewBill ($dbconn, $accountid, $readingid, $billingdate, $previous_reading, $present_reading, $consumption, $bill, $duedate, $disconnection_date, $refno, $billingmonth, $billingyear, $accountno, $meterno, $firstname, $lastname, $previous_billingdate) {
				$this -> SetFont('Arial', 'B', 12);
				$this -> Cell(276, 10, $firstname . ' ' . $lastname, 0, 0, 'L');
				$this -> Ln();

				$this -> SetFont('Times', '', 11);
				$this -> Cell(63, 10, '     Reference #:     ' . $refno, 1, 0, 'L');
				$this -> Cell(63, 10, '     Meter #:     ' . $meterno, 1, 0, 'L');
				$this -> Cell(63, 10, '     Account #:     ' . $accountno, 1, 0, 'L');
				$this -> Ln(20);

				$this -> SetFont('Arial', 'B', 12);
				$this -> SetTextColor(255, 255, 255);
				$this -> SetFillColor(0, 0, 200);
				$this -> Cell(190, 10, 'For the Month of ' . $billingmonth . ' ' . $billingyear, 0, 0, 'C', 1);
				$this -> Ln(20);

				$this -> SetTextColor(0, 0, 0);
				$this -> SetFont('Times', 'B', 11);
				$this -> Cell(40, 10, '', 0, 0, 'C');
				$this -> Cell(40, 10, 'DATE', 0, 0, 'C');
				$this -> Cell(40, 10, 'READING', 0, 0, 'C');
				$this -> Cell(40, 10, 'CuM USED', 0, 0, 'C');
				$this -> Ln();

				$this -> Cell(40, 10, 'PRESENT', 0, 0, 'C');

				$this -> SetFont('Times', '', 11);
				$this -> Cell(40, 10, $billingdate, 0, 0, 'C');
				$this -> Cell(40, 10, $present_reading, 0, 0, 'C');
				$this -> Cell(40, 10, $consumption, 0, 0, 'C');
				$this -> Ln();

				$this -> SetFont('Times', 'B', 11);
				$this -> Cell(40, 10, 'PREVIOUS', 0, 0, 'C');

				$this -> SetFont('Times', '', 11);
				$this -> Cell(40, 10, $previous_billingdate, 0, 0, 'C');
				$this -> Cell(40, 10, $previous_reading, 0, 0, 'C');
				$this -> Cell(40, 10, '', 0, 0, 'C');
				$this -> Ln(30);

				$this -> SetFont('Arial', 'B', 11);
				$this -> SetTextColor(255, 255, 255);
				$this -> SetFillColor(0, 0, 255);
				$this -> SetLeftMargin(50);
				$this -> Cell(50, 10, 'CURRENT BILL', 0, 0, 'R', 1);

				$this -> SetDrawColor(150);
				$this -> SetFont('Times', 'B', 12);
				$this -> SetTextColor(0, 0, 0);
				$this -> SetLeftMargin(0);
				$this -> Cell(40, 10, $bill, 1, 0, 'R');
				$this -> Ln(11);

				$this -> SetFont('Arial', 'B', 11);
				$this -> SetTextColor(255, 255, 255);
				$this -> SetFillColor(0, 0, 255);
				$this -> SetLeftMargin(50);
				$this -> Cell(50, 10, 'BALANCE/ARREARS', 0, 0, 'R', 1);

				$this -> SetFont('Times', 'B', 12);
				$this -> SetTextColor(0, 0, 0);
				$this -> SetLeftMargin(0);
				$this -> Cell(40, 10, '0.00', 1, 0, 'R');
				$this -> Ln();

				$this -> SetFont('Times', '', 11);
				$this -> SetLeftMargin(50);
				$this -> Cell(50, 10, '', 0, 0, 'L');

				$this -> SetFont('Times', 'B', 12);
				$this -> SetTextColor(0, 0, 0);
				$this -> SetLeftMargin(0);
				$this -> Cell(40, 1, '_______________', 0, 0, 'R');
				$this -> Ln();

				$this -> SetFont('Arial', 'B', 11);
				$this -> SetTextColor(255, 255, 255);
				$this -> SetFillColor(0, 0, 255);
				$this -> SetLeftMargin(50);
				$this -> Cell(50, 10, 'TOTAL BILL', 0, 0, 'R', 1);

				$this -> SetFont('Times', 'B', 12);
				$this -> SetTextColor(0, 0, 0);
				$this -> SetLeftMargin(0);
				$this -> Cell(40, 10, 'P ' . $bill, 1, 0, 'R');
				$this -> Ln(30);

				$billwpenalty = $bill + ($bill * 0.1);
				$billwpenalty = number_format((float)$billwpenalty, 2, '.', '');
				$this -> SetLeftMargin(10);

				$this -> SetFont('Times', '', 11);
				$this -> Cell(60, 10, 'AMOUNT AFTER DUE DATE', 0, 0, 'L');
				$this -> SetFont('Times', 'B', 12);
				$this -> Cell(30, 10, 'P ' . $billwpenalty, 0, 0, 'R');
				$this -> Ln();

				$this -> SetFont('Times', '', 11);
				$this -> Cell(60, 10, 'DUE DATE', 0, 0, 'L');
				$this -> SetFont('Times', 'B', 12);
				$this -> Cell(30, 10, $duedate, 0, 0, 'R');
				$this -> Ln();

				$this -> SetFont('Times', '', 11);
				$this -> Cell(60, 10, 'DISCONNECTION DATE', 0, 0, 'L');
				$this -> SetFont('Times', 'B', 12);
				$this -> Cell(30, 10, $disconnection_date, 0, 0, 'R');
				$this -> Ln();
			}
		}

		$http = "http://";
		$directory = "/mnwd_/pdf/";
		$filename = 'bill~' . $readingid . '.' . 'pdf';
		$location = 'pdf/' . $filename;

		$pdf = new myPDF();
		$file = $pdf -> AliasNbPages() . $pdf -> AddPage('P', 'A4', 0);

		$file = $pdf -> viewBill ($dbconn, $accountid, $readingid, $billingdate, $previous_reading, $present_reading, $consumption, $bill, $duedate, $disconnection_date, $refno, $billingmonth, $billingyear, $accountno, $meterno, $firstname, $lastname, $previous_billingdate);
		$file = $pdf -> Output($location, 'F');

		$sql = "SELECT * FROM `bill_pdf` WHERE `readingid` = $readingid";
		$check = mysqli_fetch_array(mysqli_query($dbconn,$sql));
		$url = $http . $ip . ":" . $port . $directory . $filename;

		if (!isset($check)) {
			$sql = "INSERT INTO `bill_pdf` (`readingid`) VALUES ($readingid)";

			if (!mysqli_query($dbconn, $sql)) {
				echo "Error! Please try again.";
			}
			else {
				echo "$url";
			}
		}
		else {
			echo "$url";
		}
	}
	else {
		echo "<center><h1>Illegal access detected!<h1></center>";
	}
	mysqli_close($dbconn);
?>