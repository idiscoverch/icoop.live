<?php     
ob_start();
include('PDFheaderfooter.php');
include_once("../fcts.php");
$conn=connect();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  

if (isset($_GET['ord_order_id'])) { $ord_order_id=$_GET['ord_order_id']; }
if (isset($_GET['cus_incoterms_id'])) { $cus_incoterms_id=$_GET['cus_incoterms_id']; }
if (isset($_GET['doc_filename'])) { $doc_filename=$_GET['doc_filename']; }
if (isset($_GET['tank_provider'])) { $tank_provider=$_GET['tank_provider']; } else { $tank_provider = ""; }


if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
	$sql_header = "select v_order_schedule.id_ord_schedule,
		v_order_schedule.supplier_name,
		v_order_schedule.person_name,
		v_order.sup_reference_nr as sup_ord_ref_no,
		v_order_schedule.sup_reference_nr as sup_ship_ref_no,
		v_order_schedule.product_code,
		v_order_schedule.product_name,
		product.product_name_de,
		product.q_dobi,
		product.q_ffa,
		product.q_humidity,
		product.q_impurity,
		product.q_m_i,
		product.q_mineraloil,
		v_order_schedule.package_name,
		v_order_schedule.port_name, 
		v_order_schedule.pod_code, 
		v_order_schedule.pod_id,
		v_order.pod_name AS cus_pod_name,
		v_order_schedule.pol_id,
		v_order_schedule.pol_code,
		v_order_schedule.pol_country as country_origin,
		v_order_schedule.incoterms AS sup_incoterms,
		v_order_schedule.offer_validity_date,
		v_order_schedule.notes_sup,
		get_contact_email (v_order_schedule.supplier_person_id) AS email_contact,
		v_order.sm_person_name AS sm_manager,
		v_order_msg.ord_sm_person_id,
		v_order_msg.sm_mail,
		v_order_msg.sm_mail3,
		v_order_msg.imp_mail,
		v_order_msg.imp_mail3,
		v_order_msg.ord_imp_person_id,
		v_order_msg.cus_email,
		v_order_msg.ord_cus_person_id,
		v_order_msg.cus_email3,
		v_order_msg.imp_admin_mail,
		v_order_msg.imp_admin_mail3,
		v_order_msg.ord_imp_admin_id,
		v_order_msg.cus_admin_mail,
		v_order_msg.cus_admin_mail3,
		v_order_msg.ord_cus_admin_id,
		v_order_msg.sup_mail,
		v_order_msg.ord_sup_person_id,
		v_order_msg.sup_admin_mail,
		v_order_msg.ord_sup_admin_id,
		v_order_msg.id_ord_order,
		v_order.customer_code|| '-'|| v_order.customer_reference_nr||'-'||v_order.product_code AS order_number,
		v_order.importer,
		v_order.ord_imp_contact_id,
		v_order_msg.imp_phone,
		v_order_msg.imp_skype,
		v_order.ord_imp_person_id,
		get_contact_name (v_order.ord_imp_person_id) ord_imp_person_name,
		get_contact_pstreet (v_order.ord_imp_contact_id) importer_street,
		get_contact_postalcode (v_order.ord_imp_contact_id) importer_postalcode,
		get_contact_paddress (v_order.ord_imp_contact_id) importer_town,
		v_order_msg.order_nr AS imp_reference_nr,
		v_order_schedule.order_nr,
		get_contact_name (v_order.ord_cus_contact_id) customer_name,
		get_contact_pstreet (v_order.ord_cus_contact_id) customer_street,
		get_contact_postalcode (v_order.ord_cus_contact_id) customer_postalcode,
		get_contact_paddress (v_order.ord_cus_contact_id) customer_town,
		get_contact_name (v_order.ord_cus_person_id) customer_contact,
		getregvalue (v_order_schedule.cus_incoterms_id) AS cus_incoterms,
		get_contact_name (v_order.ord_fa_contact_id) fa_name,
		get_contact_pstreet (v_order.ord_fa_contact_id) fa_street,
		get_contact_postalcode (v_order.ord_fa_contact_id) fa_postalcode,
		get_contact_paddress (v_order.ord_fa_contact_id) fa_town,
		get_contact_name (v_order_schedule. supplier_contact_id) sup_name,
		get_contact_pstreet (v_order_schedule. supplier_contact_id) sup_street,
		get_contact_postalcode (v_order_schedule. supplier_contact_id) sup_postalcode,
		get_contact_paddress (v_order_schedule. supplier_contact_id) sup_town,
		v_order_msg.customer_reference_nr,
		v_order.customer_name,
		to_char (v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
		to_char (v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
		v_order_schedule.created_by_name,
		v_order.var_basecontract,
		v_order.var_misc_cost,
		v_order.var_parity,
		v_order.var_payment_cond,
		v_order.var_quality
		FROM v_order_schedule,
		v_order_msg,
		v_order,
		product
		WHERE v_order_msg.id_ord_order=v_order_schedule.ord_order_id
		AND v_order.id_ord_order=v_order_schedule.ord_order_id
		AND v_order_schedule.id_ord_schedule=(select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id)
		AND product.id_product=v_order_Schedule.product_id
	";
	
} else {
	$sql_header = "SELECT v_order_schedule.order_nr,
		v_order_schedule.id_ord_schedule,
		v_order_schedule.supplier_name,
		v_order_schedule.person_name,
		v_order.sup_reference_nr as sup_ord_ref_no,
		v_order_schedule.sup_reference_nr as sup_ship_ref_no,    
		v_order_schedule.product_code,
		v_order_schedule.product_name,
		product.product_name_de,
		product.q_dobi,
		product.q_ffa,
		product.q_humidity,
		product.q_impurity,
		product.q_m_i,
		product.q_mineraloil,
		v_order_schedule.package_name,
		v_order_schedule.port_name,
		v_ocean_freight.pol_townport_id,
		v_order_schedule.pol_id,
		v_order_schedule.pol_code,
		v_order_schedule.pol_country as country_origin,
		v_order_schedule.pod_code,
		v_order_schedule.pod_id,
		v_order.pod_name as cus_pod_name,
		v_order_schedule.incoterms as sup_incoterms,
		v_order_schedule.offer_validity_date,
		v_order_schedule.notes_sup,
		get_contact_email(v_order_schedule.supplier_person_id) AS email_contact,
		v_order.sm_person_name as sm_manager,
		v_order_msg.ord_sm_person_id,
		v_order_msg.sm_mail,
		v_order_msg.sm_mail3,
		v_order_msg.imp_mail,
		v_order_msg.imp_mail3,
		v_order_msg.ord_imp_person_id,
		v_order_msg.cus_email,
		v_order_msg.cus_email3,
		v_order_msg.ord_cus_person_id,
		v_order_msg.imp_admin_mail,
		v_order_msg.imp_admin_mail3,
		v_order_msg.ord_imp_admin_id,
		v_order_msg.cus_admin_mail,
		v_order_msg.cus_admin_mail3,
		v_order_msg.ord_cus_admin_id,
		v_order_msg.sup_mail,
		v_order_msg.ord_sup_person_id,
		v_order_msg.sup_admin_mail,
		v_order_msg.ord_sup_admin_id,
		v_order_msg.id_ord_order,
		v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number,
		v_order.importer,
		v_order.ord_imp_contact_id,
		v_order_msg.imp_phone,
		v_order_msg.imp_skype,
		v_order.ord_imp_person_id,
		get_contact_name(v_order.ord_imp_person_id) ord_imp_person_name,
		get_contact_pstreet(v_order.ord_imp_contact_id) importer_street,
		get_contact_postalcode(v_order.ord_imp_contact_id) importer_postalcode,
		get_contact_paddress(v_order.ord_imp_contact_id) importer_town,
		v_order_msg.order_nr as imp_reference_nr,
		get_contact_name(v_order.ord_cus_contact_id) customer_name,
		get_contact_pstreet(v_order.ord_cus_contact_id) customer_street,
		get_contact_postalcode(v_order.ord_cus_contact_id) customer_postalcode,
		get_contact_paddress(v_order.ord_cus_contact_id) customer_town,
		get_contact_name(v_order.ord_cus_person_id) customer_contact,
		getregvalue(v_order_schedule.cus_incoterms_id) as cus_incoterms,
		get_contact_name (v_order.ord_fa_contact_id) fa_name,
		get_contact_pstreet (v_order.ord_fa_contact_id) fa_street,
		get_contact_postalcode (v_order.ord_fa_contact_id) fa_postalcode,
		get_contact_paddress (v_order.ord_fa_contact_id) fa_town,
		get_contact_name (v_order_schedule. supplier_contact_id) sup_name,
		get_contact_pstreet (v_order_schedule. supplier_contact_id) sup_street,
		get_contact_postalcode (v_order_schedule. supplier_contact_id) sup_postalcode,
		get_contact_paddress (v_order_schedule. supplier_contact_id) sup_town,
		v_order_msg.customer_reference_nr,
		v_order.customer_name,
		v_ocean_freight.dem_pol_cost_after,
		v_ocean_freight.dem_pol_free,
		v_ocean_freight.dem_pod_cost_after,
		v_ocean_freight.dem_pod_free,
		to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
		to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
		v_order_schedule.created_by_name,
		   v_order.var_basecontract,
		   v_order.var_misc_cost,
		   v_order.var_parity,
		   v_order.var_payment_cond,
		   v_order.var_quality
	   FROM v_order_schedule, v_order_msg, v_order, v_ocean_freight, product
	   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
	   and v_order.id_ord_order=v_order_schedule.ord_order_id
	   and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id )
	   and v_ocean_freight.sequence_nr=1 and v_ocean_freight.id_ord_schedule=v_order_schedule.id_ord_schedule
	and product.id_product=v_order_Schedule.product_id";
}
	

$rs_header = pg_query($conn, $sql_header);
$row_header = pg_fetch_assoc($rs_header);

$order_nr = $row_header['order_nr'];
				$id_ord_schedule = $row_header['id_ord_schedule'];
				$supplier_name = $row_header['supplier_name'];
				$person_name = $row_header['person_name'];
				$sup_ord_ref_no = $row_header['sup_ord_ref_no'];
				$sup_ship_ref_no = $row_header['sup_ship_ref_no'];
				$product_code = $row_header['product_code'];
				$product_name = $row_header['product_name'];
				$product_name_de = $row_header['product_name_de'];
				$q_dobi = $row_header['q_dobi'];
				$q_ffa = $row_header['q_ffa'];
				$q_humidity = $row_header['q_humidity'];
				$q_impurity = $row_header['q_impurity'];
				$q_m_i = $row_header['q_m_i'];
				$q_mineraloil = $row_header['q_mineraloil'];
				$package_name = $row_header['package_name'];
				$port_name = $row_header['port_name'];
				$pol_townport_id = $row_header['pol_townport_id'];
				$pol_id = $row_header['pol_id'];
				$pol_code = $row_header['pol_code'];
				$country_origin = $row_header['country_origin'];
				$pod_code = $row_header['pod_code'];
				$pod_id = $row_header['pod_id'];
				$cus_pod_name = $row_header['cus_pod_name'];
				$sup_incoterms = $row_header['sup_incoterms'];
				$offer_validity_date = $row_header['offer_validity_date'];
				$notes_sup = $row_header['notes_sup'];
				$email_contact = $row_header['email_contact'];
				$sm_manager = $row_header['sm_manager'];
				$ord_sm_person_id = $row_header['ord_sm_person_id'];
				$sm_mail = $row_header['sm_mail'];
				$sm_mail3 = $row_header['sm_mail3'];
				$imp_mail = $row_header['imp_mail'];
				$imp_mail3 = $row_header['imp_mail3'];
				$cus_email = $row_header['cus_email'];
				$cus_email3 = $row_header['cus_email3'];
				$ord_cus_person_id = $row_header['ord_cus_person_id'];
				$imp_admin_mail = $row_header['imp_admin_mail'];
				$imp_admin_mail3 = $row_header['imp_admin_mail3'];
				$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
				$cus_admin_mail = $row_header['cus_admin_mail'];
				$cus_admin_mail3 = $row_header['cus_admin_mail3'];
				$ord_cus_admin_id = $row_header['ord_cus_admin_id'];
				$sup_mail = $row_header['sup_mail'];
				$ord_sup_person_id = $row_header['ord_sup_person_id'];
				$sup_admin_mail = $row_header['sup_admin_mail'];
				$ord_sup_admin_id = $row_header['ord_sup_admin_id'];
				$order_number = $row_header['order_number'];
				$importer = $row_header['importer'];
				$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
				$imp_phone = $row_header['imp_phone'];
				$imp_skype = $row_header['imp_skype'];
				$ord_imp_person_id = $row_header['ord_imp_person_id'];
				$ord_imp_person_name = $row_header['ord_imp_person_name'];
				$importer_street = $row_header['importer_street'];
				$importer_postalcode = $row_header['importer_postalcode'];
				$importer_town = $row_header['importer_town'];
				$fa_name = $row_header['fa_name'];
				$fa_street = $row_header['fa_street'];
				$fa_postalcode = trim($row_header['fa_postalcode']);
				$fa_town = $row_header['fa_town'];
				$sup_name = $row_header['sup_name'];
				$sup_street = $row_header['sup_street'];
				$sup_postalcode = $row_header['sup_postalcode'];
				$sup_town = $row_header['sup_town'];
				$imp_reference_nr = $row_header['imp_reference_nr'];
				$customer_name = $row_header['customer_name'];
				$customer_street = $row_header['customer_street'];
				$customer_postalcode = $row_header['customer_postalcode'];
				$customer_town = $row_header['customer_town'];
				$customer_contact = $row_header['customer_contact'];
				$cus_incoterms = $row_header['cus_incoterms'];
				$customer_reference_nr = $row_header['customer_reference_nr'];
				$dem_pol_cost_after = $row_header['dem_pol_cost_after'];
				$dem_pol_free = $row_header['dem_pol_free'];
				$dem_pod_cost_after = $row_header['dem_pod_cost_after'];
				$dem_pod_free = $row_header['dem_pod_free'];
				$proposal_date = $row_header['proposal_date'];
				$created_date = $row_header['created_date'];
				$created_by_name = $row_header['created_by_name'];
				$var_basecontract = $row_header['var_basecontract'];
				$var_misc_cost = $row_header['var_misc_cost'];
				$var_parity = $row_header['var_parity'];
				$var_payment_cond = $row_header['var_payment_cond'];
				$var_quality = $row_header['var_quality'];


if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
	$Lieferbedingungen = trim($cus_incoterms) .' '.trim($port_name);
	$Einkaufsbedingungen = trim($sup_incoterms);
	$title='Lieferplanung';
} else {
	$Lieferbedingungen = trim($cus_incoterms) .' '.trim($cus_pod_name);
	$Einkaufsbedingungen = trim($sup_incoterms) .' '.trim($port_name);
	$title='Purchase Order';
}

$sql_detail = "select order_nr||'.'||order_ship_nr as imp_no, v.customer_reference_nr||'.'||v.customer_ref_ship_nr as cus_no, 
v.supplier_reference_nr as sup_no, v.customer_ref_ship_nr,
v.supplier_lastname,
to_char(month_etd,'dd.mm.yyyy')||'/'||week_etd etd_month, nr_containers, to_char(weight_shipment,'999G999D9') weight, pol_code, pol_country, pol_country_name, v.ord_cus_contact_code
from v_order_schedule v where ord_order_id=$ord_order_id
order by order_ship_nr::integer
";

$rs_detail = pg_query($conn, $sql_detail);


if(!empty($ord_order_id) 
AND !empty($cus_incoterms_id) 
AND !empty($doc_filename) 
){

// Instanciation de la classe dérivée
$PDF = new PDF();
$PDF->AliasNbPages();
$PDF->AddPage();

$PDF->Ln(2);
$PDF->SetFont('HelveticaNeueLight','',22);

if(trim($importer) == "Ceres AG"){
	$PDF->Cell(85);
} else {
	$PDF->Cell(65);
}
$PDF->Cell(3,1,trim($importer),'C');
$PDF->Ln(8);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->Cell(74);
$PDF->Cell(3,1,trim($importer_street).' '.trim($importer_postalcode).', '.trim($importer_town),'C');
$PDF->SetLineWidth(0.1);
$PDF->Line(15, 25, 210-15, 25);

$PDF->Ln(14);
$PDF->SetTextColor(0,0,0);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->Cell(3,1,$fa_name,'C');
$PDF->Ln(6);
$PDF->Cell(3,1,$fa_street,'C');
$PDF->Ln(6);
$PDF->Cell(3,1,$fa_postalcode.' '.$fa_town,'C');

$PDF->Ln(16);
$PDF->SetFont('HelveticaNeueLight','',14);
$PDF->Cell(3,1,$title,'C');


$PDF->Ln(15);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Unsere Kontrakt Nr','C');
$PDF->Cell(48);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$imp_reference_nr,'C');
$PDF->Ln(7);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Datum','C');
$PDF->Cell(48);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,gmdate("Y/m/d"),'C');
$PDF->Ln(7);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Importeur','C');
$PDF->Cell(48);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,trim($importer).', '.trim($importer_postalcode).' '.trim($importer_town),'C');
$PDF->Ln(7);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Kunde','C');
$PDF->Cell(48);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,trim($customer_name).', '.trim($customer_postalcode).' '.trim($customer_town),'C');
$PDF->Ln(7);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Kunden-Kontrakt-Nr','C');
$PDF->Cell(48);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$customer_reference_nr,'C');
$PDF->Ln(7);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Ware','C');
$PDF->Cell(48);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,utf8_decode($product_name_de).' ('.trim($product_code).')','C'); 
$PDF->Ln(7);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Verpackung','C');
$PDF->Cell(48);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,trim($package_name).' '.$tank_provider,'C'); 
$PDF->Ln(7);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Lieferbedingungen','C');
$PDF->Cell(48);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$Lieferbedingungen,'C');  
$PDF->Ln(7);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Einkaufsbedingungen','C');
$PDF->Cell(48);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$Einkaufsbedingungen,'C');  
$PDF->Ln(7);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Ware','C');
$PDF->Cell(48);


$tabhcont[0] = 15;
$tabhcont[1] = 22;
$tabhcont[2] = 20;
$tabhcont[3] = 20;
$tabhcont[4] = 21;
$tabhcont[5] = 15;
$tabhcont[6] = 14;
$tabhcont[7] = 14;
$tabhcont[8] = "[LB]U/PO-Nr.";
$tabhcont[9] = "[LB]Kd-PO-Nr.";
$tabhcont[10] = "[LB]Lieferant";
$tabhcont[11] = "[LB]lief.-PO Nr.";
$tabhcont[12] = "[LB]ETD/Woche";
$tabhcont[13] = "[LB]Anz. Cont";
$tabhcont[14] = "[LB]Gewicht";
$tabhcont[15] = "[LB]Ursprung";


// Chargement des données
while ($row_detail = pg_fetch_assoc($rs_detail)){

	$tabvalues[]="[L]".$row_detail['imp_no'];
	$tabvalues[]="[L]".$row_detail['customer_ref_ship_nr'];
	$tabvalues[]="[L]".trim($row_detail['supplier_lastname']);
	$tabvalues[]="[L]".$row_detail['sup_no'];
	$tabvalues[]="[L]".$row_detail['etd_month'];
	$tabvalues[]="[L]".$row_detail['nr_containers'];
	$tabvalues[]="[L]".$row_detail['weight'];
	$tabvalues[]="[L]".strtoupper(trim($row_detail['pol_country']));
}
	
$proprietesTableau = array(
'TB_ALIGN' => 'R',
'L_MARGIN' => 3,
'BRD_COLOR' => array(150,150,150),
'BRD_SIZE' => '0.2',
);
// Définition des propriétés du header du tableau.
$proprieteHeader = array(
'T_COLOR' => array(0,0,0),
'T_SIZE' => 7,
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
'T_SIZE' => 8,
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


$PDF->Ln(20);
$PDF->Cell(51);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'ITS','C');
$PDF->Cell(71);
$PDF->Cell(3,1,'Importeur','C');
$PDF->Ln(7);	
$PDF->Cell(51);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$fa_name,'C');
$PDF->Cell(71);
$PDF->Cell(3,1,$importer,'C');


$filename=$_SERVER['DOCUMENT_ROOT']."/crm/img/documents/".$doc_filename;
$PDF->Output($filename,'F');

	
$PDF->Output();

}

?>
