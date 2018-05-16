<?php
	require('fpdf/fpdf.php'); 
	include 'connect.php';

	$query = "SELECT * FROM `incident_report` ORDER BY `reportdate` DESC";
	$result = mysqli_query($dbconn, $query);
	$count = mysqli_num_rows($result);

	class PDF_receipt extends FPDF {
		function __construct ($orientation = 'L', $unit = 'pt', $format = 'Letter', $margin = 20) {
			$this->FPDF($orientation, $unit, $format);
			$this->SetTopMargin($margin);
			$this->SetLeftMargin($margin);
			$this->SetRightMargin($margin);
			$this->SetAutoPageBreak(true, $margin);
		}

		function Header () {
			//$this->Image('logoFinal.png', 10, 10, 10);
			$this->SetFont('Times', 'B', 20);
			$this->Cell(0, 50, "Incident Reports", 0, 1, 'C');
		}

		function Footer () {
			$this->SetFont('Times', '', 12);
			$this->SetTextColor(0);
			$this->SetXY(40, -60);
			$this->Cell(0, 20, "Thank you!", 'T', 0, 'C');
		}
	}

	$pdf = new PDF_receipt();
	$pdf->SetTitle('Print Incident Report');
	$pdf->AddPage('L');
	$pdf->SetFont('Times', 'B', 12);
	$pdf->SetFillColor(0, 0, 0);

	$pdf->SetFont('Times', 'B');
	$pdf->Cell(95, 15, "Incident Type", 1);
	
	$pdf->SetFont('Times', 'B');
	$pdf->Cell(70, 15, "Account #", 1);
	
	$pdf->SetFont('Times', 'B');
	$pdf->Cell(68, 15, "Report Date", 1);

	$pdf->SetFont('Times', 'B');
	$pdf->Cell(95, 15, "Username", 1);

	$pdf->SetFont('Times', 'B', 'C');
	$pdf->Cell(330, 15, "Description", 1);

	//$pdf->SetFont('Times', 'B');
	//$pdf->Cell(300, 15, "Address", 1);

	$pdf->ln(17);

	if ($count > 0) {
		$item = 0;
		
		while ($row = mysqli_fetch_array($result)) {
			$reportid = $row['reportid'];
			$incidentid = $row['incidentid'];
			$accountid = $row['accountid'];
			$description = $row['description'];
			$reportdate = $row['reportdate'];

			$incidenttype = "SELECT `incidenttype` FROM `incident` WHERE `incidentid` = $incidentid";
			$res = mysqli_query ($dbconn, $incidenttype);
			$rowi = mysqli_fetch_array($res);
			$incidenttype = $rowi['incidenttype'];

			$sql = "SELECT * FROM `account` WHERE `accountid` = $accountid";
			$res = mysqli_query ($dbconn, $sql);
			$row = mysqli_fetch_array($res);
			$accountno = $row['accountno'];
			$address = $row['address'];
			$userid = $row['userid'];

			$sql = "SELECT * FROM `user` WHERE `userid` = $userid";
			$res = mysqli_query ($dbconn, $sql);
			$row = mysqli_fetch_array($res);
			$firstname = $row['firstname'];
			$lastname = $row['lastname'];
			$name = $firstname . ' ' . $lastname;

			$item = $item+1;
			$pdf->SetFont('Arial', 'I');
			$pdf->Cell(95, 15, $incidenttype, 1, 'C');
			$pdf->Cell(70, 15, $accountno, 1, 'C');
			$pdf->Cell(68, 15, $reportdate, 1, 'C');
			$pdf->Cell(95, 15, $name, 1, 'C');
			$pdf->Cell(330, 15, $description, 1, 'L');
			//$pdf->Cell(300, 15, $address, 1, 'C', false);
			$pdf->ln(17);
		}
	}
	
	$pdf->Ln(100);
	$pdf->Output();
?>