<?php

require('fpdf/fpdf.php'); 

class PDF_receipt extends FPDF {
	function __construct ($orientation = 'P', $unit = 'pt', $format = 'Letter', $margin = 40) {
		$this->FPDF($orientation, $unit, $format);
		$this->SetTopMargin($margin);
		$this->SetLeftMargin($margin);
		$this->SetRightMargin($margin);		

	
		$this->SetAutoPageBreak(true, $margin);

	}

	function Header () {
		$this->SetFont('Times', 'B', 20);
		$this->Cell(0, 30, "Metropolitan Naga Water District", 0, 1, 'C');
	}

	function Footer () {
		$this->SetFont('Times', '', 12);
		$this->SetTextColor(0);
		$this->SetXY(40, -60);
		$this->Cell(0, 20, "Thank you!", 'T', 0, 'C');
	}
}

$pdf = new PDF_receipt();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 12);



$pdf->SetXY(225, 97);
$pdf->Cell(500, 15, "B I L L I N G    N O T I C E");
$pdf->SetXY(225, 101);
$pdf->Cell(500, 15, "________________________");

$pdf->SetXY(50, 150);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Reference #:");
$pdf->SetXY(135, 150);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['refno']);

$pdf->SetXY(50, 180);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Meter #:");
$pdf->SetXY(135, 180);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['meterno']);

$pdf->SetXY(50, 210);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Account #:");
$pdf->SetXY(135, 210);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['accountno']);

$pdf->SetXY(50, 240);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Concessionaire:");
$pdf->SetXY(135, 240);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['concessionaire']);

$pdf->SetXY(50, 300);
$pdf->Cell(500, 15, "-------------------------------------------For the Month of January 2017----------------------------------------");

$pdf->SetXY(190, 330);
$pdf->SetFont('Times', 'B');
$pdf->Cell(500, 15, "DATE");

$pdf->SetXY(260, 330);
$pdf->SetFont('Times', 'B');
$pdf->Cell(500, 15, "READING");

$pdf->SetXY(353, 330);
$pdf->SetFont('Times', 'B');
$pdf->Cell(500, 15, "CUM USED");

$pdf->SetXY(80, 360);
$pdf->SetFont('Times', 'B');
$pdf->Cell(500, 15, "PRESENT");
$pdf->SetXY(175, 360);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['presentdate']);
$pdf->SetXY(272, 360);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['presentreading']);
$pdf->SetXY(376, 360);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['presentcumused']);

$pdf->SetXY(80, 390);
$pdf->SetFont('Times', 'B');
$pdf->Cell(500, 15, "PREVIOUS");
$pdf->SetXY(175, 390);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['previousdate']);
$pdf->SetXY(272, 390);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['previousreading']);
$pdf->SetXY(376, 390);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['previouscumused']);


$pdf->SetXY(50, 420);
$pdf->Cell(500, 15, "----------------------------------------------------------------------------------------------------------------------------");


$pdf->SetXY(50, 480);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Current Bill:");
$pdf->SetXY(175, 480);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['currentbill']);

$pdf->SetXY(50, 510);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Balance/Arrears:");
$pdf->SetXY(175, 510);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['balanceorarrears']);
$pdf->SetXY(175, 520);
$pdf->Cell(500, 15, "-------------");

$pdf->SetXY(50, 540);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Total Bill:");
$pdf->SetXY(175, 540);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['totalbill']);

$pdf->SetXY(50, 600);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Amount after Due Date:");
$pdf->SetXY(175, 600);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['amount']);

$pdf->SetXY(50, 630);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Due Date:");
$pdf->SetXY(175, 630);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 15, $_POST['duedate']);

$pdf->SetXY(50, 660);
$pdf->SetFont('Times', '');
$pdf->Cell(500, 15, "Disconnection:");
$pdf->SetXY(175, 660);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(500, 20, $_POST['notice']);

$pdf->Ln(100);
$pdf->Output();
