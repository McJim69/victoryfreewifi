<?php

error_reporting(0);

require('../fpdf/fpdf.php');

require('../connect.php');

$id = $_GET['id'];

$select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE invoice_id=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

require('../getvalue.php');

$cont = $row->invoice_id;
$orid = sprintf('%04d', $cont);

// HEADING
$pdf = new FPDF('P','mm', array(100,230));

$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(80,10,'VICTORY FREE WIFI',0,1,'C');

$pdf->Line(19,18,81,18);
$pdf->Line(19,19,81,19);

$pdf->SetFont('Arial','',10);
$pdf->Cell(80,4,'Capitol Building, Pagadian City',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(80,4,'Zamboanga del Sur',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(80,4,'7016 - PH',0,1,'C');

$pdf->Line(19,32,81,32);
$pdf->Line(19,33,81,33);

// RECEIPT NUMBER
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,12,'Requisition Slip',0,1,'C');

$pdf->SetFont('Courier','B',20);
$pdf->SetTextColor(194,10,10);
$pdf->Cell(80,0,$orid,0,1,'C');

// TABLE 1 - OPERATOR, DATE & TIME, REQUESTOR
$pdf->SetY(50);
$pdf->SetX(4);
$pdf->SetTextColor(0,0,0);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(28,6,'Encoder',1,0,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(22,6,'Date',1,0,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(16,6,'Time',1,0,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(26,6,'Requestor',1,1,'C');

$pdf->SetX(4);
$pdf->SetFont('Arial','',10);
$pdf->Cell(28,5,$row->operator_name,1,0,'C');
$pdf->Cell(22,5,$row->order_date,1,0,'C');
$pdf->Cell(16,5,$row->time_order,1,0,'C');
$pdf->Cell(26,5,$row->requestor,1,1,'C');

// TABLE 2 - ITEM DETAIL
$pdf->SetY(63);

$pdf->SetX(4);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(7,6 ,'No',1,0,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(36,6 ,'Name of Item',1,0,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,6 ,'Qty',1,0,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(12,6 ,'Unit',1,0,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(27,6 ,'Serial/MAC',1,1,'C');

$select = $pdo->prepare("SELECT * FROM tbl_invoice_detail WHERE invoice_id=$id");
$select->execute();

$i=1;
  
while($item = $select->fetch(PDO::FETCH_OBJ)){
	
	$mac  = $item->serial_mac;
	$str  = str_replace(':', '', $mac);
    
	$pdf->SetX(4);
    $pdf->SetFont('Arial','',8.5);
    $pdf->Cell(7,5,$i,1,0,'C');
    $pdf->Cell(36,5,$item->product_name,1,0,'C');
    $pdf->Cell(10,5,$item->qty,1,0,'C');
    $pdf->Cell(12,5,$item->product_unit,1,0,'C');
    $pdf->Cell(27,5,$str,1,1,'C');
	$i++;
}

// ROLLOUT SITE
$brgy = utf8_decode($row->barangay);					
$pdf->Cell(80,3 ,'',0,1,'L');
$pdf->SetX(3);
$pdf->SetFont('Arial','',9);
$pdf->Cell(80,6 ,'Rollout Site: ' .$brgy.', ' .$row->city_mun.', ZDS.',0,1,'L');

// INSTALLATION TYPE
$pdf->Cell(80,-2 ,'',0,1,'L');
$pdf->SetX(3);
$pdf->SetFont('Arial','',9);
$pdf->Cell(80,6 ,'Installation Type: ' .$row->purpose,0,1,'L');


// RECEIVED BY
$pdf->Cell(80,0 ,'',0,1,'L');
$pdf->SetX(3);
$pdf->SetFont('Arial','',10);
$pdf->Cell(80,6 ,'Received by:',0,1,'L');
$pdf->SetX(3);
$pdf->SetFont('Arial','',10);

$pdf->Cell(80,4 ,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(80,0 , $leader,0,1,'C');

$pdf->SetFont('Arial','I',7);
$pdf->Cell(80,1 ,'______________________________________',0,1,'C');
$pdf->Cell(80,5 ,'(Signature over Printed Name - Team Leader)',0,1,'C');

$pdf->Cell(80,3 ,'',0,1,'L');
$pdf->SetX(3);
$pdf->SetFont('Arial','',10);
$pdf->Cell(80,5 ,'APPROVED BY:',0,1,'L');
$pdf->SetX(3);

$pdf->SetFont('Arial','',10);

$pdf->Cell(80,5 ,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(80,0 ,'JUSTIN AHMED PAOLO C. HERRERA',0,1,'C');

$pdf->SetFont('Arial','I',7);

$pdf->Cell(80,1 ,'______________________________________',0,1,'C');
$pdf->Cell(80,5 ,'(Signature Over Printed Name - CISO Chief)',0,1,'C');

$pdf->Output();

