<?php    

session_start(); 
ob_start();
include('PDFheaderfooter.php');

if (isset($_GET['conf'])) { $conf=$_GET['conf']; }
if (isset($_GET['id_con_booking'])) { $id_con_booking=$_GET['id_con_booking']; }
if (isset($_GET['filename'])) { $filename=$_GET['filename']; }
if (isset($_GET['ord_schedule_id'])) { 
	$ord_schedule_id=$_GET['ord_schedule_id']; 

	$sql = "SELECT v_order.order_nr||'.'||v_order_schedule.order_ship_nr as file_name, 
		v_order.id_ord_order
		FROM v_order, v_order_schedule 
		WHERE v_order_schedule.ord_order_id=v_order.id_ord_order
		AND v_order_schedule.id_ord_schedule=$ord_schedule_id
	";
	$result = pg_query($conn, $sql);
	$row = pg_fetch_assoc($result);

	$file_name = $row['file_name'];
	$id_ord_order = $row['id_ord_order'];
}
	

if(!empty($filename) 
){

// Instanciation de la classe dérivée
$PDF = new PDF();
$PDF->AliasNbPages();
$PDF->AddPage();

$PDF->Ln(2);
$PDF->SetFont('Arial','IB',10);
$PDF->Cell(8);
$PDF->Cell(3,1,'Mail','C');
$PDF->Cell(136);
$PDF->SetFont('Arial','I',10);
$PDF->Cell(3,1,gmdate("Y/m/d H:i"),'C');
$PDF->SetLineWidth(0.1);
$PDF->Line(10, 15, 210-10, 15);

$PDF->Ln(16);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'From: ','C');
$PDF->Cell(28);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,'noreply@idiscover.ch','C');
$PDF->Ln(5);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'To: ','C');
$PDF->Cell(28);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,'croth53@gmail.com','C');

$PDF->Ln(5);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'CC: ','C');
$PDF->Cell(28);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,'zoran.kuret@alfa24.ba','C');

$PDF->Ln(5);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Subject: ','C');
$PDF->Cell(28);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);

if($conf=='trace'){
	$PDF->Cell(3,1,'Order Code ('.$file_name.') - Traceability Certificate Order','C');
} else {
	$PDF->Cell(3,1,'Order Code ('.$file_name.') - Request certificate','C');
}

$PDF->Ln(16);

$PDF->Image('../img/icrm_logo.png',12,92);
$PDF->Ln(16);
$PDF->Cell(23);
$PDF->Cell(3,1,'iCRM.live Message from the iDiscover Back Office.','C');
$PDF->Ln(20);
// $PDF->Cell(3,1,WriteHTML(utf8_decode($content)),'C');

$PDF->Cell(3,1,'Order Code ('.$file_name.')','C');
$PDF->Ln(7);
$PDF->Cell(3,1,$id_ord_order,'C');
$PDF->Ln(7);
$PDF->Cell(3,1,$ord_schedule_id,'C');
$PDF->Ln(7);
$PDF->Cell(3,1,$id_con_booking,'C');


// $PDF->Ln(20);
// $PDF->SetLineWidth(0.1);
// $PDF->Line(10, 85, 210-10, 85);


$PDF->Ln(15);
// $PDF->SetLineWidth(0.1);
// $PDF->Line(10, 122, 210-10, 122);

$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,'Before printing think about the ENVIRONMENT!','C');
$PDF->Ln(7);
$PDF->Cell(3,1,'Warning: If you have received this email by error, please delete it and inform the sender immediately. ','C');
$PDF->Ln(5);
$PDF->Cell(3,1,'This message or attachments may contain information which is confidential, therefore its use,','C');
$PDF->Ln(5);
$PDF->Cell(3,1,'reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.','C');



$pdfFile=$_SERVER['DOCUMENT_ROOT']."/crm/img/documents/".$filename;
$PDF->Output($pdfFile,'F');


$PDF->Output();


}

?>
