<?php    
ob_start(); 
include('PDFheaderfooter.php');
include_once("../fcts.php");
$conn=connect();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  

if (isset($_GET['ord_schedule_id'])) { $ord_schedule_id=$_GET['ord_schedule_id']; }
if (isset($_GET['save'])) { $save=$_GET['save']; }
if (isset($_GET['conf'])) { $conf=$_GET['conf']; }

if (isset($_GET['save'])) { $filename=$_GET['doc_filename']; }
else { $filename=""; }


if($ord_schedule_id){
	
	$sql_header = "select s.id_ord_schedule, 
		 get_contact_name(s.ord_imp_contact_id) importer_name,
		 get_contact_postalcode(s.ord_imp_contact_id) importer_postalcode,
		 get_contact_paddress(s.ord_imp_contact_id) importer_town,
		 get_contact_pstreet(s.ord_imp_contact_id) importer_street,
		 o.customer_code||'-'||o.order_nr||'-'||o.product_code as order_number_imp,
		 s.supplier_name,
		 get_contact_postalcode(s.supplier_contact_id) supplier_postalcode,
		 get_contact_paddress(s.supplier_contact_id) supplier_town,
		 get_contact_pstreet(s.supplier_contact_id) supplier_street,
		 o.sup_reference_nr||'.'||s.supplier_reference_nr order_number_supp,
		 to_char(h.total_vgm_weight,'999G999D999') total_vgm_weight,
		 b.booking_nr,
		 s.customer_ref_ship_nr,
		 b.id_con_booking,
		 b.qm_contact_id,
		 b.qm_contact_name,
		 b.qm_person_id,
		 get_contact_name(b.qm_person_id) person_name,
		 get_contact_postalcode(b.qm_contact_id) qm_contact_postalcode,
		 get_contact_paddress(b.qm_contact_id) qm_contact_town,
		 get_contact_pstreet(b.qm_contact_id) qm_contact_street
		 from v_order o, v_order_schedule s, v_con_booking b, ord_con_loading_header h
		 where s.id_ord_Schedule=$ord_schedule_id
		 and b.booking_segment=1 and
		 h.ord_schedule_id=s.id_ord_schedule and  
		 b.ord_schedule_id=s.id_ord_schedule and 
		 s.ord_order_id=o.id_ord_order
	";

	
	$rs_header = pg_query($conn, $sql_header);
	$row_header = pg_fetch_assoc($rs_header);
	
	$importer_name = $row_header['importer_name'];
	$importer_postalcode = $row_header['importer_postalcode'];
	$importer_town = $row_header['importer_town'];
	$importer_street = $row_header['importer_street'];
	$order_number_imp = $row_header['order_number_imp'];
	
	$supplier_name = $row_header['supplier_name'];
	$supplier_postalcode = $row_header['supplier_postalcode'];
	$supplier_town = $row_header['supplier_town'];
	$supplier_street = $row_header['supplier_street'];
	$order_number_supp = $row_header['order_number_supp'];
	$booking_nr = $row_header['booking_nr'];
	$customer_ref_ship_nr = $row_header['customer_ref_ship_nr'];
	
	$total_vgm_weight = $row_header['total_vgm_weight'];
	$id_con_booking = $row_header['id_con_booking'];
	$qm_contact_id = $row_header['qm_contact_id'];
	$qm_contact_name = $row_header['qm_contact_name'];
	$qm_person_id = $row_header['qm_person_id'];
	$person_name = $row_header['person_name'];
	$qm_contact_postalcode = $row_header['qm_contact_postalcode'];
	$qm_contact_town = $row_header['qm_contact_town'];
	$qm_contact_street = $row_header['qm_contact_street'];


	$sql_detail = " select l.container_nr,
               l.cus_con_ref1,
               to_char(l.vgm_weight/1000,'999G999D999') vgm_weight,
                to_char(l.tare/1000::numeric,'999G999D999') tare,
               COALESCE(l.seal_1_nr,'')::text||','||coalesce(l.seal_2_nr,'')::text||','||coalesce(l.seal_3_nr,'')::text||','||coalesce(l.seal_4_nr,'')::text||','||coalesce(l.seal_5_nr,'')  as seals_imp,
               get_seals(l.id_con_list) seals,
               to_char(i.end_loading,'dd.mm.yyyy') end_loading
               from ord_con_list l, ord_con_loading_item i
               where l.con_booking_id=$id_con_booking
               and i.con_list_id=l.id_con_list
             order by l.cus_con_ref1
	";
	
	$rs_detail = pg_query($conn, $sql_detail);
	
	if($conf == 'importer'){
		$order_number = $order_number_imp;
	}
	else
	if($conf == 'supplier'){
		$order_number = $order_number_supp;
	} 
	else {}
}


if(!empty($ord_schedule_id)){
// Instanciation de la classe dérivée
$PDF = new PDF('L','mm','A4');
$PDF->AliasNbPages();
$PDF->AddPage();

$PDF->Ln(2);
$PDF->SetFont('HelveticaNeueLight','',14);
$PDF->SetTextColor(0,0,0);
if($conf == 'importer'){
	$PDF->Cell(3,1,$importer_name,'C');
	$PDF->Ln(6);
	$PDF->Cell(3,1,$importer_street,'C');
	$PDF->Ln(6);
	$PDF->Cell(3,1,trim($importer_postalcode).' '.trim($importer_town),'C');

} else
if($conf == 'supplier'){
	$PDF->Cell(3,1,$supplier_name,'C');
	$PDF->Ln(6);
	$PDF->Cell(3,1,$supplier_street,'C');
	$PDF->Ln(6);
	$PDF->Cell(3,1,trim($supplier_town),'C');
} 

$PDF->Ln(12);
$PDF->Cell(3,1,'Date: '.gmdate("d.m.Y"),'C');
$PDF->Ln(12);

if($conf == 'importer'){
	$PDF->SetFont('HelveticaNeueLight','',16);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'Packing List: '.$order_number,'C');
} else
if($conf == 'supplier'){	
	$PDF->SetFont('HelveticaNeueLight','',16);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'Packing List','C');
	$PDF->Ln(10);
	$PDF->SetFont('HelveticaNeueLight','',14);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Our Ref-No.: ','C');
	$PDF->Cell(34);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$order_number,'C');
	$PDF->Ln(6);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Booking No.: ','C');
	$PDF->Cell(34);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$booking_nr,'C');
	$PDF->Ln(6);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Customer Ref.: ','C');
	$PDF->Cell(34);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$customer_ref_ship_nr,'C');
} 

$PDF->Ln(15);
$tabhcont[0] = 25;
$tabhcont[1] = 10;
$tabhcont[2] = 37;
$tabhcont[3] = 160;
$tabhcont[4] = 20;
$tabhcont[5] = 25;
$tabhcont[6] = "[LB]Loading Date";
$tabhcont[7] = "[LB]Item";
$tabhcont[8] = "[LB]Container No";
$tabhcont[9] = "[LB]Seals";
$tabhcont[10] = "[RB]Tare";
$tabhcont[11] = "[RB]Weight (MT)";

// Chargement des données
while ($row_detail = pg_fetch_assoc($rs_detail)){

	$date = explode(' ', $row_detail['end_loading']);
	
	$tabvalues[]="[L]".$date[0];
	$tabvalues[]="[L]".$row_detail['cus_con_ref1'];
	$tabvalues[]="[L]".$row_detail['container_nr'];
	$tabvalues[]="[L]".$row_detail['seals'];
	$tabvalues[]="[R]".trim($row_detail['tare']);
	$tabvalues[]="[R]".$row_detail['vgm_weight'];
}
	
$proprietesTableau = array(
'TB_ALIGN' => 'L',
'L_MARGIN' => 0,
'BRD_COLOR' => array(150,150,150),
'BRD_SIZE' => '0.2',
);
// Définition des propriétés du header du tableau.
$proprieteHeader = array(
'T_COLOR' => array(0,0,0),
'T_SIZE' => 10,
'T_FONT' => 'Arial',
'T_ALIGN' => 'L',
'V_ALIGN' => 'M',
'T_TYPE' => 'B',
'LN_SIZE' => 8,
'BG_COLOR_COL0' => array(195, 195, 195),
'BG_COLOR' => array(195, 195, 195),
'BRD_COLOR' => array(160,160,160),
'BRD_SIZE' => 0.2,
'BRD_TYPE' => '1',
'BRD_TYPE_NEW_PAGE' => '',
);
// Contenu du header du tableau.
//$contenuHeader =$tabhcont;
   
// Définition des propriétés du reste du contenu du tableau.
$proprieteContenu = array(
'T_COLOR' => array(0,0,0),
'T_SIZE' => 12,
'T_FONT' => 'HelveticaNeueLight',
'T_ALIGN_COL0' => 'L',
'T_ALIGN' => 'L',
'V_ALIGN' => 'M',
'T_TYPE' => '',
'LN_SIZE' => 6,
'BG_COLOR_COL0' => array(255,255,255),
'BG_COLOR' => array(255,255,255),
'BRD_COLOR' => array(160,160,160),
'BRD_SIZE' => 0.1,
'BRD_TYPE' => '1',
'BRD_TYPE_NEW_PAGE' => '',
);

$PDF->drawTableau($PDF, $proprietesTableau, $proprieteHeader, $tabhcont, $proprieteContenu,$tabvalues);

$PDF->Ln(4);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Total Weight','C');
$PDF->SetTextColor(0,0,0);
$PDF->Cell(0,1,trim($total_vgm_weight),0,0,'R');

if($save == 1){
	$pdfFile=$_SERVER['DOCUMENT_ROOT']."/crm/img/documents/".$filename;
	$PDF->Output($pdfFile,'F');
}
	
$PDF->Output();

}

?>
