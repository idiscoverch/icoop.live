<?php     
ob_start();
include('PDFheaderfooter.php');
include_once("../fcts.php");
$conn=connect();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  

if (isset($_GET['ord_order_id'])) { $ord_order_id=$_GET['ord_order_id']; }
if (isset($_GET['cus_incoterms_id'])) { $cus_incoterms_id=$_GET['cus_incoterms_id']; }
if (isset($_GET['qualite'])) { $qualite=utf8_decode($_GET['qualite']); }
if (isset($_GET['parity'])) { $parity=utf8_decode($_GET['parity']); }
if (isset($_GET['contract'])) { $contract=utf8_decode($_GET['contract']); }
if (isset($_GET['payment'])) { $payment=utf8_decode($_GET['payment']); }
if (isset($_GET['save'])) { $save=$_GET['save']; }
if (isset($_GET['pdf'])) { $doc_filename=$_GET['pdf']; }
if (isset($_GET['ware_added'])) { $ware_added=$_GET['ware_added']; } else { $ware_added=""; }
if (isset($_GET['spezifikationen'])) { $spezifikationen=str_replace("@","&",utf8_decode($_GET['spezifikationen'])); }


if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
	$sql_header = "SELECT
	v_order_schedule.id_ord_schedule,
	v_order_schedule.supplier_name,
	v_order_schedule.person_name,
	to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
	to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
	v_order_schedule.created_by_name,
	v_order_schedule.product_code,
	v_order_schedule.product_name,
	v_order_schedule.package_name,
	v_order_schedule.package_type_id,
	v_order_schedule.port_name,
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
	v_order_msg.cus_email,
	v_order_msg.cus_email3,
	v_order_msg.imp_admin_mail,
	v_order_msg.imp_admin_mail3,
	v_order_msg.cus_admin_mail,
	v_order_msg.cus_admin_mail3,
	v_order_msg.id_ord_order,
	v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number,
	v_order.importer,
	v_order.ord_imp_contact_id,
	v_order.order_nr AS contract_number,
	v_order_msg.imp_phone,
	v_order_msg.imp_skype,
	v_order.ord_imp_contact_id,
	get_contact_name(v_order.ord_imp_person_id) ord_imp_person_name,
	get_contact_pstreet(v_order.ord_imp_contact_id) importer_street,
	get_contact_postalcode(v_order.ord_imp_contact_id) importer_postalcode,
	get_contact_paddress(v_order.ord_imp_contact_id) importer_town,
	v_order_msg.order_nr as imp_reference_nr,
	v_order_schedule.order_nr,
	get_contact_name(v_order.ord_cus_contact_id) customer_name,
	get_contact_pstreet(v_order.ord_cus_contact_id) customer_street,
	get_contact_postalcode(v_order.ord_cus_contact_id) customer_postalcode,
	get_contact_paddress(v_order.ord_cus_contact_id) customer_town,
	get_contact_name(v_order.ord_cus_person_id) customer_contact,
	getregvalue(v_order_schedule.cus_incoterms_id) as cus_incoterms,
	v_order_msg.customer_reference_nr,
	v_order_schedule.pod_id,
	v_order.customer_name,
	v_order.pod_name as cus_pod_name,
	v_order.ord_cus_contact_id,
	v_order.order_incoterms_id
	FROM v_order_schedule, v_order_msg, v_order
	where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
	and v_order.id_ord_order=v_order_schedule.ord_order_id
	and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id )";
	
} else {
	$sql_header = "SELECT
	v_order_schedule.id_ord_schedule,
	v_order_schedule.supplier_name,
	v_order_schedule.person_name,
	to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
	to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
	v_order_schedule.created_by_name,
	v_order_schedule.product_code,
	v_order_schedule.product_name,
	v_order_schedule.package_name,
	v_order_schedule.package_type_id,
	v_order_schedule.port_name,
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
	v_order_msg.cus_email,
	v_order_msg.cus_email3,
	v_order_msg.imp_admin_mail,
	v_order_msg.imp_admin_mail3,
	v_order_msg.cus_admin_mail,
	v_order_msg.cus_admin_mail3,
	v_order_msg.id_ord_order,
	v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number,
	v_order.importer,
	v_order.ord_imp_contact_id,
	v_order.order_nr AS contract_number,
	v_order_msg.imp_phone,
	v_order_msg.imp_skype,
	get_contact_name(v_order.ord_imp_person_id) ord_imp_person_name,
	get_contact_pstreet(v_order.ord_imp_contact_id) importer_street,
	get_contact_postalcode(v_order.ord_imp_contact_id) importer_postalcode,
	get_contact_paddress(v_order.ord_imp_contact_id) importer_town,
	v_order_msg.order_nr as imp_reference_nr,
	v_order_schedule.order_nr,
	get_contact_name(v_order.ord_cus_contact_id) customer_name,
	get_contact_pstreet(v_order.ord_cus_contact_id) customer_street,
	get_contact_postalcode(v_order.ord_cus_contact_id) customer_postalcode,
	get_contact_paddress(v_order.ord_cus_contact_id) customer_town,
	get_contact_name(v_order.ord_cus_person_id) customer_contact,
	getregvalue(v_order_schedule.cus_incoterms_id) as cus_incoterms,
	v_order_msg.customer_reference_nr,
	v_order_schedule.pod_id,
	v_order.customer_name,
	v_ocean_freight.dem_pol_cost_after,
	v_ocean_freight.dem_pol_free,
	v_ocean_freight.dem_pod_cost_after,
	v_ocean_freight.dem_pod_free,
	v_ocean_freight.pol_townport_id,
	v_ocean_freight.pod_name,
	v_ocean_freight.dem_pol_free2,
	v_ocean_freight.dem_pol_cost_after2,
	v_ocean_freight.dem_pod_cost_after2,
	v_ocean_freight.dem_pod_free2,
	v_order.pod_name as cus_pod_name,
	v_order.ord_cus_contact_id,
	v_order.order_incoterms_id
	FROM v_order_schedule, v_order_msg, v_order, v_ocean_freight
	where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
	and v_order.id_ord_order=v_order_schedule.ord_order_id
	and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id )
	and v_ocean_freight.sequence_nr=1 and v_ocean_freight.id_ord_schedule=v_order_schedule.id_ord_schedule;";
}
	

$rs_header = pg_query($conn, $sql_header);
$row_header = pg_fetch_assoc($rs_header);

$id_ord_schedule = $row_header['id_ord_schedule'];
$supplier_name = $row_header['supplier_name'];
$person_name = $row_header['person_name'];
$proposal_date = $row_header['proposal_date'];
$created_date = $row_header['created_date'];
$created_by_name = $row_header['created_by_name'];
$product_code = $row_header['product_code'];
$product_name = trim($row_header['product_name']);
$package_name = $row_header['package_name'];
$port_name = $row_header['port_name'];
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
$imp_admin_mail = $row_header['imp_admin_mail'];
$imp_admin_mail3 = $row_header['imp_admin_mail3'];
$cus_admin_mail = $row_header['cus_admin_mail'];
$cus_admin_mail3 = $row_header['cus_admin_mail3'];
$id_ord_order = $row_header['id_ord_order'];
$order_number = $row_header['order_number'];
$importer = trim($row_header['importer']);
$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
$imp_phone = $row_header['imp_phone'];
$imp_skype = $row_header['imp_skype'];
$ord_imp_person_name = $row_header['ord_imp_person_name'];
$importer_street = trim($row_header['importer_street']);
$importer_postalcode = trim($row_header['importer_postalcode']);
$importer_town = trim($row_header['importer_town']);
$imp_reference_nr = $row_header['imp_reference_nr'];
$order_nr = $row_header['order_nr'];
$customer_name = trim($row_header['customer_name']);
$customer_street = trim($row_header['customer_street']);
$customer_postalcode = trim($row_header['customer_postalcode']);
$customer_town = trim($row_header['customer_town']);
$customer_contact = $row_header['customer_contact'];
$cus_incoterms = trim($row_header['cus_incoterms']);
$customer_reference_nr = $row_header['customer_reference_nr'];
$pod_id = $row_header['pod_id'];
$dem_pol_cost_after = $row_header['dem_pol_cost_after'];
$dem_pol_free = $row_header['dem_pol_free'];
$dem_pod_cost_after = $row_header['dem_pod_cost_after'];
$dem_pod_free = $row_header['dem_pod_free'];
$pol_townport_id = $row_header['pol_townport_id'];
$pod_name = trim($row_header['pod_name']);
$dem_pol_free2 = $row_header['dem_pol_free2'];
$dem_pol_cost_after2 = $row_header['dem_pol_cost_after2'];
$dem_pod_cost_after2 = $row_header['dem_pod_cost_after2'];
$dem_pod_free2 = $row_header['dem_pod_free2'];
$cus_pod_name = trim($row_header['cus_pod_name']);
$contract_number = $row_header['contract_number'];
$ord_cus_contact_id = $row_header['ord_cus_contact_id'];
$order_incoterms_id = $row_header['order_incoterms_id'];
$package_type_id = $row_header['package_type_id'];

//setlocale(LC_MONETARY, 'de_DE.UTF-8');

$sql_detail = "select order_nr||'.'|| order_ship_nr as no, to_char(month_eta,'Mon-yyyy') as month_eta,
to_char(month_etd,'Mon-yyyy') as month_etd, nr_containers, ROUND(weight_shipment,3) as weight,
ROUND(unit_value,3) as proposal_price, ROUND(total_value, 2) as proposal_value,
pol_code, pol_country, pol_country_name
from v_schedule_calc where ord_order_id=$ord_order_id
order by order_ship_nr::integer";

$rs_detail = pg_query($conn, $sql_detail);

	
$sql_foot = "select getregvalue(max(proposal_currency_id)) currency, trim(to_char(ROUND(sum(total_value),2),'999G999G999D99')) as total,
	to_char(sum(weight_shipment),'999G999G999') as total_weight
from v_schedule_calc where ord_order_id=$ord_order_id";

$rs_foot = pg_query($conn, $sql_foot);
$row_foot = pg_fetch_assoc($rs_foot);

$currency = $row_foot['currency'];
$total_weight = $row_foot['total_weight'];
$total = $row_foot['total'];

if(!empty($ord_order_id) 
AND !empty($cus_incoterms_id) 
AND !empty($qualite) 
AND !empty($parity) 
AND !empty($contract) 
AND !empty($payment) 
){

// Instanciation de la classe dérivée
$PDF = new PDF();
$PDF->AliasNbPages();
$PDF->AddPage();

$PDF->Ln(2);
$PDF->SetFont('HelveticaNeueLight','',22);
$PDF->SetTextColor(0,0,0);

if(trim($importer) == "Ceres AG"){
	$PDF->Cell(75);
} else {
	$PDF->Cell(55);
}
$PDF->Cell(3,1,$importer,'C');
$PDF->Ln(8);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(62);
$PDF->Cell(3,1,$importer_street.' '.$importer_postalcode.', '.$importer_town,'C');
$PDF->SetLineWidth(0.1);
$PDF->Line(15, 25, 210-15, 25);

$PDF->Ln(26);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(8);
$PDF->Cell(3,1,$customer_name,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->Cell(3,1,$customer_street,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->Cell(3,1,$customer_postalcode.' '.$customer_town,'C');

$PDF->Ln(20);
$PDF->SetFont('HelveticaNeueLight','',14);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(8);
$PDF->Cell(3,1,'Verkaufskontrakt','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$contract_number.'.000','C');

$PDF->Ln(10);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Datum','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,gmdate("d/m/Y"),'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Verkäufer'),'C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$importer.', '.$importer_postalcode.' '.$importer_town,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Käufer'),'C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$customer_name.', '.$customer_postalcode.' '.$customer_town,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Ware','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$product_name.' - aus LDC - '.trim($total_weight).' MT '.$ware_added,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Spezifikationen','C');

$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Multicell(0,5,$spezifikationen,'C'); 

$PDF->Ln(5);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Währung'),'C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$currency,'C');  
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Menge/Liefertermine','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);

if($package_type_id == 268){
	$label_starts = "***";
	$d_table_starts = "*** plus/minus 28 Tage je nach Verfügbarkeit der Seefrachter.";
} else {
	$label_starts = "";
	$d_table_starts = "";
}

$tabhcont[0] = 14;
$tabhcont[1] = 25;
$tabhcont[2] = 20;
$tabhcont[3] = 24;
$tabhcont[4] = 22;
$tabhcont[5] = 25;
$tabhcont[6] = 12;
$tabhcont[7] = "[LB]Nr.";
$tabhcont[8] = "[LB]Lieferung" . $label_starts;
$tabhcont[9] = "[LB]Anz. Cont.";
$tabhcont[10] = "[LB]Gewicht in MT*";
$tabhcont[11] = "[LB]Preis/MT**";
$tabhcont[12] = "[LB]Preis/Lieferung**";
$tabhcont[13] = "[LB]Land";

$x=0;
// Chargement des données
while ($row_detail = pg_fetch_assoc($rs_detail)){

	if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
		$month = $row_detail['month_etd'];
	} else { $month = $row_detail['month_eta']; }
	
	$tabvalues[]="[L]".$row_detail['no'];
	$tabvalues[]="[L]".$month;
	$tabvalues[]="[L]".$row_detail['nr_containers'];
	$tabvalues[]="[L]".$row_detail['weight'];
	$tabvalues[]="[L]".money_format('%!n', $row_detail['proposal_price']); 
	$tabvalues[]="[L]".money_format('%!n', $row_detail['proposal_value']);
	$tabvalues[]="[L]".strtoupper(trim($row_detail['pol_country']));
	$x++;
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
'T_SIZE' => 8,
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
'T_SIZE' => 10,
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

$PDF->Ln(3);
$PDF->Cell(50);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Total','C');
$PDF->Cell(103);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$total,'C');

$PDF->Ln(6);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->Cell(50);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,'* Gewicht: +/- 10% **Preis exkl. MwSt ','C');

if($d_table_starts!=""){
	$PDF->Ln(6);
	$PDF->Cell(50);
	$PDF->Cell(3,1,utf8_decode($d_table_starts),'C');
}

$PDF->Ln(10);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Qualität'),'C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$qualite,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Verpackung','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$package_name,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Lieferbedingung','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);

if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
	$PDF->Cell(3,1,$cus_incoterms,'C');
	$PDF->Cell(6);
	$PDF->Cell(3,1,$pod_name,'C');
} else {
	$PDF->Cell(3,1,$cus_incoterms,'C');
	$PDF->Cell(6);
	$PDF->Cell(3,1,$cus_pod_name,'C');
}	
	
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Parität'),'C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$parity,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Basis-Kontrakt','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$contract,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Zahlungskonditionen','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$payment,'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Importeur','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$customer_name.', die Ware wird auf Abruf im Ursprungsland produziert','C');

$PDF->Ln(6);
$PDF->Cell(51);
$PDF->Cell(3,1,'und wird auf '.$customer_name.', '.$customer_postalcode.' '.$customer_town.' verzollt','C');

if($x>9){ 
	$PDF->Ln(35); 
	$PDF->Cell(51);
	$PDF->Cell(3,1,'','C');
	$PDF->Ln(20);
} else { $PDF->Ln(6); }
	
if(($cus_incoterms_id != 263) AND ($cus_incoterms_id != 264)){
	$PDF->Cell(8);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Demurrage','C');
	$PDF->Cell(40);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$dem_pod_free.' Tage frei ab ETA '.$pod_name.', anschliessend '.$dem_pod_cost_after.' EUR/Tag/Container','C');
	$PDF->Ln(6);
	$PDF->Cell(51);
	$PDF->Cell(3,1,utf8_decode('(Stand Januar 2018 - die Demurrage Kosten sind variabel)'),'C');
	
	if(!empty($dem_pol_free2)){
		$PDF->Ln(6);
		$PDF->Cell(51);
		$PDF->Cell(3,1,'Ab '.$dem_pod_free2.'. Tag werden '.$dem_pod_cost_after2. utf8_decode(' EUR/Tag/Container fällig'),'C');
	} 
	
	if(($order_incoterms_id == 265)&&($ord_cus_contact_id == 688)){
		$PDF->Ln(6);
		$PDF->Cell(8);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Chassis','C');
		$PDF->Cell(40);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,'3 Tage inklusive, anschliessend 60 Euro / Tag / Tank','C');
	}
} 

if($x>5){
	if($x<9){ 
		$PDF->Ln(35); 
		$PDF->Cell(51);
		$PDF->Cell(3,1,'','C');
		$PDF->Ln(20);
	} else {
		$PDF->Ln(6);
	}
} else {
	$PDF->Ln(6);
}

$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Stand-, Warte- und','C');
$PDF->Cell(40);	
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,'werden nach Aufwand in Rechnung gestellt','C');
$PDF->Ln(5);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Reinigungskosten','C');	
$PDF->Ln(4);
$PDF->Cell(8);
$PDF->Cell(3,1,'Hoch- und Nieder','C');
$PDF->Ln(4);
$PDF->Cell(8);
$PDF->Cell(3,1,utf8_decode('wasserzuschläge'),'C');


$PDF->Ln(12);
$PDF->Cell(51);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Käufer'),'C');
$PDF->Cell(71);
$PDF->Cell(3,1,utf8_decode('Verkäufer'),'C');	
$PDF->Ln(5);
$PDF->Cell(51);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$customer_name,'C');
$PDF->Cell(71);
$PDF->Cell(3,1,$importer,'C');

if($save == 1){
	$pdfFile=$_SERVER['DOCUMENT_ROOT']."/crm/img/documents/".$doc_filename;
	$PDF->Output($pdfFile,'F');
}  
	
$PDF->Output();

}

?>
