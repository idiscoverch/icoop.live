<?php    

session_start(); 
ob_start();
include('PDFheaderfooter.php');

if (isset($_GET['from'])) { $from=$_GET['from']; }
if (isset($_GET['to'])) { $to=$_GET['to']; }
if (isset($_GET['subject'])) { $subject=$_GET['subject']; }
if (isset($_GET['content'])) { $content=$_GET['content']; }
if (isset($_GET['datetime'])) { $datetime=$_GET['datetime']; }
if (isset($_GET['filename'])) { $filename=$_GET['filename']; }

if (isset($_GET['ref'])) { $ref=$_GET['ref']; }
if (isset($_GET['cc'])) { $cc=$_GET['cc']; }
if (isset($_GET['bcc'])) { $bcc=$_GET['bcc']; }

$var=0;

if(!empty($from) 
AND !empty($to) 
AND !empty($datetime) 
AND !empty($subject) 
AND !empty($content) 
AND !empty($filename) 
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
$PDF->Cell(3,1,$datetime,'C');
$PDF->SetLineWidth(0.1);
$PDF->Line(10, 15, 210-10, 15);

$PDF->Ln(16);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'From: ','C');
$PDF->Cell(28);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$from,'C');
$PDF->Ln(5);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'To: ','C');
$PDF->Cell(28);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$to,'C');

if(($cc!="")&&($cc!="null,")){
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'CC: ','C');
	$PDF->Cell(28);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$cc,'C');
	$var=$var+4;
}

// if(($bcc!="")&&($bcc!="null")){
	// $PDF->Ln(5);
	// $PDF->SetFont('HelveticaNeueLight','B',10);
	// $PDF->Cell(3,1,'Bcc: ','C');
	// $PDF->Cell(28);
	// $PDF->SetFont('HelveticaNeueLight','',10);
	// $PDF->Cell(3,1,$bcc,'C');	
	// $var=$var+4;
// }

$PDF->Ln(5);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Subject: ','C');
$PDF->Cell(28);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$subject,'C');

$PDF->Ln(16);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$ref,'C');

$PDF->Image('../img/icrm_logo.png',12,62+$var);
$PDF->Ln(16);
$PDF->Cell(23);
$PDF->Cell(3,1,'iCRM.live Message from the iCoop Back Office.','C');
$PDF->Ln(20);
// $PDF->Cell(3,1,WriteHTML(utf8_decode($content)),'C');

$PDF->WriteHTML(utf8_decode($content));

// $PDF->Ln(20);
// $PDF->SetLineWidth(0.1);
// $PDF->Line(10, 85, 210-10, 85);

$PDF->Ln(18);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,'Message delivered by iCoop.live back office on behalf of:','C');
$PDF->Ln(7);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Company:','C');
$PDF->Cell(38);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$_SESSION['company_name'],'C');
$PDF->Ln(5);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Name:','C');
$PDF->Cell(38);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$_SESSION['name'],'C');
$PDF->Ln(5);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Email:','C');
$PDF->Cell(38);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$_SESSION['p_email'],'C');

if($_SESSION['p_phone']!=""){
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Phone:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$_SESSION['p_phone'],'C');
}

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
