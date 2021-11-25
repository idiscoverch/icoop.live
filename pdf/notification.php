<?php    

session_start();
 ob_start();
include('PDFheaderfooter.php');
include_once("../fcts.php");
$conn=connect();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  

if (isset($_GET['id_ord_schedule'])) { $id_ord_schedule=$_GET['id_ord_schedule']; }
if (isset($_GET['doc_filename'])) { $doc_filename=$_GET['doc_filename']; }
if (isset($_GET['old_month_eta'])) { $old_month_eta=$_GET['old_month_eta']; }
if (isset($_GET['old_nr_containers'])) { $old_nr_containers=$_GET['old_nr_containers']; }
if (isset($_GET['conf'])) { $conf=$_GET['conf']; }

if($conf == 'add'){ $cond="and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$id_ord_schedule )"; }
else { $cond="and v_order_schedule.id_ord_schedule = $id_ord_schedule"; }


$sql_header = "SELECT  
	v_order_schedule.id_ord_schedule,
	v_order_schedule.month_eta,
	v_order_schedule.nr_containers,
	v_order_msg.imp_mail,
	v_order_msg.imp_admin_mail,
	v_order_msg.cus_email,
	v_order_msg.cus_admin_mail,
	v_order_msg.sm_mail,
	v_order_schedule.nr_shipments,
	v_order.customer_code||'-'||v_order.order_nr||'.'||v_order_schedule.nr_shipments as imp_reference,
	v_order.customer_code||'-'||v_order.customer_reference_nr||'.'||v_order_schedule.customer_ref_ship_nr as cus_reference,
	v_order.customer_code||'-'||v_order.sup_reference_nr||'.'||v_order_schedule.supplier_reference_nr as sup_reference,
	l.ref_code_fa,
	l.ref_code_cus,
	l.ref_code_imp,
	l.ref_code_sup
	 FROM v_order_schedule, v_order_msg, v_order, v_logistics_schedule l
	where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
	and v_order.id_ord_order=v_order_schedule.ord_order_id 
	and v_order_schedule.id_ord_schedule = l.id_ord_schedule
	$cond
";
	

$rs_header = pg_query($conn, $sql_header);
$row_header = pg_fetch_assoc($rs_header);

$ref_code_cus = trim($row_header['ref_code_cus']);
$ref_code_imp = trim($row_header['ref_code_imp']);
$ref_code_sup = trim($row_header['ref_code_sup']);
$ref_code_fa = trim($row_header['ref_code_fa']);

if($ref_code_cus!=""){ $no_cus = $ref_code_cus; } else { $no_cus=""; }
if($ref_code_fa!=""){ $no_fa = ' / '.$ref_code_fa; } else { $no_fa=""; }
if($ref_code_imp!=""){ $no_imp = ' / '.$ref_code_imp; } else { $no_imp=""; }
if($ref_code_sup!=""){ $no_sup = ' / '.$ref_code_sup; } else { $no_sup=""; }

$month_eta = $row_header['month_eta'];
$nr_containers = $row_header['nr_containers'];
	
if(!empty($row_header['imp_mail'])){ $imp_mail = $row_header['imp_mail'].','; }
if(!empty($row_header['imp_admin_mail'])){ $imp_admin_mail = $row_header['imp_admin_mail'].','; }
if(!empty($row_header['cus_email'])){ $cus_email = $row_header['cus_email'].','; }
if(!empty($row_header['cus_admin_mail'])){ $cus_admin_mail = $row_header['cus_admin_mail'].','; }
if(!empty($row_header['sm_mail'])){ $sm_mail = $row_header['sm_mail'].','; }

$nr_shipments = $row_header['nr_shipments'];
$imp_reference = $row_header['imp_reference'];
$cus_reference = $row_header['cus_reference'];
$sup_reference = $row_header['sup_reference'];


if(!empty($id_ord_schedule) 
AND !empty($doc_filename) 
AND !empty($old_month_eta) 
AND !empty($old_nr_containers) 
AND !empty($conf) 
){

// Instanciation de la classe dérivée
$PDF = new PDF();
$PDF->AliasNbPages();
$PDF->AddPage();

$PDF->Ln(2);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$no_cus.$no_fa.$no_imp.$no_sup,'C');

$PDF->Image('../img/icrm_logo.png',12,16);
$PDF->Ln(14);
$PDF->Cell(23);
$PDF->Cell(3,1,'iCRM.live Message from the iDiscover Back Office.','C');

if($conf == 'edit'){
	$PDF->Ln(20);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'ETA before','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$old_month_eta,'C');
	$PDF->Cell(48);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'ETA now','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$month_eta,'C');


	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'No Containers before','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$old_nr_containers,'C');
	$PDF->Cell(48);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'No. of Containers now','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$nr_containers,'C');
	
} else 
if($conf == 'add'){
	$PDF->Ln(20);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'A new Shipment has been added:','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Shipment No.','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$nr_shipments,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'No. of Containers','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$nr_containers,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'ETA','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$month_eta,'C');
	
} else
if($conf == 'del'){
	$PDF->Ln(20);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'A shipment has been deleted:','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Shipment No.','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$nr_shipments,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'No. of Containers','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$nr_containers,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'ETA','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$month_eta,'C');
}

$PDF->SetLineWidth(0.1);
$PDF->Line(10, 65, 210-10, 65);

$PDF->Ln(18);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,'Message delivered by iDiscover.live back office on behalf of:','C');
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
$PDF->Cell(3,1,$_SESSION['username'],'C');

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
$PDF->SetLineWidth(0.1);
$PDF->Line(10, 98, 210-10, 98);

$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,'Before printing think about the ENVIRONMENT!','C');
$PDF->Ln(7);
$PDF->Cell(3,1,'Warning: If you have received this email by error, please delete it and inform the sender immediately. ','C');
$PDF->Ln(5);
$PDF->Cell(3,1,'This message or attachments may contain information which is confidential, therefore its use,','C');
$PDF->Ln(5);
$PDF->Cell(3,1,'reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.','C');



$pdfFile=$_SERVER['DOCUMENT_ROOT']."/crm/img/documents/".$doc_filename;
$PDF->Output($pdfFile,'F');

	
$PDF->Output();

}

?>
