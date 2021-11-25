<?php   
ob_start();  
include('PDFheaderfooter_invoice.php');
include_once("../fcts.php");
$conn=connect();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
   
if (isset($_GET['ord_schedule_id'])) { $ord_schedule_id=$_GET['ord_schedule_id']; }   
if (isset($_GET['invoice_number'])) { $invoice_number=$_GET['invoice_number']; }
if (isset($_GET['invoice_date'])) { $invoice_date=$_GET['invoice_date']; }    
if (isset($_GET['save'])) { $save=$_GET['save']; }

if (isset($_GET['save'])) { $filename=$_GET['doc_filename']; }
else { $filename=""; }


if($ord_schedule_id){
	$sql_header = "select v_order_schedule.order_nr,
		v_order_schedule.id_ord_schedule,
		v_order_schedule.product_code,
		v_order_schedule.product_name,
		v_order_schedule.arrival_month,
		TO_char(v_order_schedule.month_eta,'yyyy') year_lieferung,
		product.product_name_de,
		v_order.var_basecontract,
		v_order.var_misc_cost,
		v_order.var_parity,
		v_order.var_payment_cond,
		v_order.var_quality,
		product.q_dobi,
		product.q_ffa,
		product.q_humidity,
		product.q_impurity,
		product.q_m_i,
		product.q_mineraloil,
		v_order_schedule.package_name,
		v_order_schedule.port_name,
		v_order_schedule.pol_id,
		v_order_schedule.pol_code,
		v_order_schedule.pol_country as country_origin,
		v_order_schedule.pod_code,
		v_order_schedule.pod_id,
		v_order.pod_name as cus_pod_name,
		v_order_msg.imp_mail,
		v_order_msg.imp_mail3,
		v_order_msg.cus_email,
		v_order_msg.cus_email3,
		v_order_msg.imp_admin_mail,
		v_order_msg.imp_admin_mail3,
		v_order_msg.cus_admin_mail,
		v_order_msg.cus_admin_mail3,
		v_order_msg.id_ord_order,
		v_order.customer_code||'-'||v_order.order_nr||'.'||v_order_schedule.order_ship_nr as order_number,
		v_order.order_nr||'.'||v_order_schedule.order_ship_nr as invoice_number,
		v_order.importer,
		v_order.ord_imp_contact_id,
		v_order_msg.imp_phone,
		v_order_msg.imp_skype,
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
		v_order_msg.customer_reference_nr,
		v_order_msg.customer_reference_nr||'-'||v_order_schedule.customer_reference_nr as customer_reference,
		to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
		to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
		v_logistics.no_cus,
		v_logistics.no_fa,
		v_logistics.no_imp,
		v_logistics.no_sup,
		v_logistics.no_cus||'-'||v_logistics.no_fa||'-'||v_logistics.no_imp||'-'||v_logistics.no_sup as reference,
		v_order.customer_code||'-'||v_order.order_nr||'.'||v_logistics.order_ship_nr as file_name,
		v_logistics.ref_code_cus,
		v_logistics.ref_code_fa,
		v_logistics.ref_code_imp,
		v_logistics.ref_code_sup,
		v_schedule_calc.proposal_currency_id,
		to_char(current_date,'dd.mm.yyyy') as invoice_date,
		getregvalue(v_schedule_calc.proposal_currency_id) currency,
		v_order.incoterms,
		v_order_schedule.product_id,
		v_order_schedule.package_type_id,
		get_contact_country_de(v_order_schedule.supplier_contact_id) country_de,
		v_order_schedule.month_eta
		FROM v_order_schedule, v_order_msg, v_order, product, v_logistics, v_schedule_calc
		where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
		and v_order.id_ord_order=v_order_schedule.ord_order_id
		and v_order_schedule.id_ord_schedule = $ord_schedule_id
		and product.id_product=v_order_Schedule.product_id
		and v_logistics. id_ord_schedule=v_order_schedule.id_ord_schedule
		and v_schedule_calc.id_ord_schedule=v_order_schedule.id_ord_schedule
	";
	

	$rs_header = pg_query($conn, $sql_header);
	$row_header = pg_fetch_assoc($rs_header);

	$order_nr = $row_header['order_nr'];
	$product_code = $row_header['product_code'];
	$product_name = $row_header['product_name'];
	$arrival_month = $row_header['arrival_month'];
	$year_lieferung = $row_header['year_lieferung'];
	$product_name_de = $row_header['product_name_de'];
	$var_basecontract = $row_header['var_basecontract'];
	$var_misc_cost = $row_header['var_misc_cost'];
	$var_parity = $row_header['var_parity'];
	$var_payment_cond = $row_header['var_payment_cond'];
	$var_quality = $row_header['var_quality'];
	$q_dobi = $row_header['q_dobi'];
	$q_ffa = $row_header['q_ffa'];
	$q_humidity = $row_header['q_humidity'];
	$q_impurity = $row_header['q_impurity'];
	$q_m_i = $row_header['q_m_i'];
	$q_mineraloil = $row_header['q_mineraloil'];
	$package_name = $row_header['package_name'];
	$port_name = $row_header['port_name'];
	$pol_id = $row_header['pol_id'];
	$pol_code = $row_header['pol_code'];
	$country_origin = $row_header['country_origin'];
	$pod_code = $row_header['pod_code'];
	$pod_id = $row_header['pod_id'];
	$cus_pod_name = $row_header['cus_pod_name'];
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
	//$invoice_number = $row_header['invoice_number'];
	$importer = $row_header['importer'];
	$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
	$imp_phone = $row_header['imp_phone'];
	$imp_skype = $row_header['imp_skype'];
	$ord_imp_person_name = $row_header['ord_imp_person_name'];
	$importer_street = $row_header['importer_street'];
	$importer_postalcode = $row_header['importer_postalcode'];
	$importer_town = $row_header['importer_town'];
	$imp_reference_nr = $row_header['imp_reference_nr'];
	$customer_name = $row_header['customer_name'];
	$customer_street = $row_header['customer_street'];
	$customer_postalcode = $row_header['customer_postalcode'];
	$customer_town = $row_header['customer_town'];
	$customer_contact = $row_header['customer_contact'];
	$cus_incoterms = $row_header['cus_incoterms'];
	$customer_reference_nr = $row_header['customer_reference_nr'];
	$customer_reference = $row_header['customer_reference'];
	$proposal_date = $row_header['proposal_date'];
	$created_date = $row_header['created_date'];
	$no_cus = $row_header['no_cus'];
	$no_fa = $row_header['no_fa'];
	$no_imp = $row_header['no_imp'];
	$no_sup = $row_header['no_sup'];
	$reference = $row_header['reference'];
	$file_name = $row_header['file_name'];
	$ref_code_cus = $row_header['ref_code_cus'];
	$ref_code_fa = $row_header['ref_code_fa'];
	$ref_code_imp = $row_header['ref_code_imp'];
	$ref_code_sup = $row_header['ref_code_sup'];
	//$invoice_date = $row_header['invoice_date'];
	$currency = $row_header['currency'];
	$incoterms = $row_header['incoterms'];  
	$product_id = $row_header['product_id'];
	$package_type_id = $row_header['package_type_id'];
	$country_de = trim($row_header['country_de']);
	$month_eta = $row_header['month_eta'];
	$proposal_currency_id = $row_header['proposal_currency_id'];
	

	$sql_detail1 = "select id, container_nr, vgm_weight from (      
		select row_number() over () as id, container_nr,
		to_char(l.vgm_weight/1000,'999G999D999') vgm_weight
		FROM ord_con_booking b, ord_con_list l, ord_con_loading_item i
		where b.ord_schedule_id=$ord_schedule_id and l.con_booking_id=b.id_con_booking and i.con_list_id=l.id_con_list ) q1
		where q1.id <= ( select round(count(*)/2,0) from (      
		select row_number() over () as id, container_nr,
		to_char(l.vgm_weight/1000,'999G999D999') vgm_weight
		FROM ord_con_booking b, ord_con_list l, ord_con_loading_item i
		where b.ord_schedule_id=$ord_schedule_id and l.con_booking_id=b.id_con_booking and i.con_list_id=l.id_con_list ) q2 )
	";
	
	$sql_detail2 = "select id, container_nr, vgm_weight from (      
		select row_number() over () as id, container_nr,
		to_char(l.vgm_weight/1000,'999G999D999') vgm_weight
		FROM ord_con_booking b, ord_con_list l, ord_con_loading_item i
		where b.ord_schedule_id=$ord_schedule_id and l.con_booking_id=b.id_con_booking and i.con_list_id=l.id_con_list ) q1
		where q1.id > ( select round(count(*)/2,0) from (      
		select row_number() over () as id, container_nr,
		to_char(l.vgm_weight/1000,'999G999D999') vgm_weight
		FROM ord_con_booking b, ord_con_list l, ord_con_loading_item i
		where b.ord_schedule_id=$ord_schedule_id and l.con_booking_id=b.id_con_booking and i.con_list_id=l.id_con_list ) q2 )
	";

	$rs_detail1 = pg_query($conn, $sql_detail1);
	$rs_detail2 = pg_query($conn, $sql_detail2);
	
	
	$sql_total = "select h.total_vgm_weight,c.ship_sales_value_tone,
	to_char((h.total_vgm_weight*c.ship_sales_value_tone),'999G999D99') total_invoice,
	h.vgm_deliver_total, h.vgm_diff_total, c.ship_sales_value_tone, h.vgm_diff_total*c.ship_sales_value_tone total_diff
	 from ord_con_loading_header h, v_schedule_calc c
	 where h.ord_schedule_id=$ord_schedule_id
	 and c.id_ord_schedule=h.ord_schedule_id
	";
	
	$rs_total = pg_query($conn, $sql_total);
	$row_total = pg_fetch_assoc($rs_total);

	$total_vgm_weight = $row_total['total_vgm_weight'];
	$proposal_price = $row_total['ship_sales_value_tone'];
	$total_invoice = $row_total['total_invoice'];
	$vgm_deliver_total = $row_total['vgm_deliver_total'];
	$vgm_diff_total = $row_total['vgm_diff_total'];
	$total_diff = $row_total['total_diff'];  

	$iban="";
	if($proposal_currency_id == 277){
		$iban="c.iban_usd AS iban_number,";
	}else
	if($proposal_currency_id == 278){
		$iban="c.iban_euro AS iban_number,";
	}else
	if($proposal_currency_id == 279){
		$iban="c.iban_number,";
	}else { $iban=""; }
	
	$sql_footer = "SELECT c.account_number,
       c.bank_city,
       c.bank_name,
       $iban
	   c.bank_code,
       c.vat_number
	  FROM contact_parameters c
	 WHERE c.id_contact = $ord_imp_contact_id
	";
	
	$rs_footer = pg_query($conn, $sql_footer);
	$row_footer = pg_fetch_assoc($rs_footer);
	
	$account_number = $row_footer['account_number'];
	$bank_city = $row_footer['bank_city'];
	$bank_name = $row_footer['bank_name'];
	$iban_number = $row_footer['iban_number'];
	$bank_code = $row_footer['bank_code'];
	$vat_number = $row_footer['vat_number'];
	
	$_SESSION['var'] = $account_number.'##'.$bank_city.'##'.$bank_name.'##'.$iban_number.'##'.$bank_code.'##'.$vat_number;
	
} 


if(!empty($ord_schedule_id)){

// Instanciation de la classe dérivée
$PDF = new PDF();
$PDF->AliasNbPages();
$PDF->AddPage();

$PDF->Ln(2);
$PDF->SetFont('HelveticaNeueLight','',22);
$PDF->SetTextColor(0,0,0);

if(trim($importer) == "Ceres AG"){
	$PDF->Cell(70);
} else {
	$PDF->Cell(60);
}
$PDF->Cell(3,1,trim($importer),'C');
$PDF->Ln(8);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(65);
$PDF->Cell(3,1,trim($importer_street).' '.trim($importer_postalcode).', '.trim($importer_town),'C');
$PDF->SetLineWidth(0.1);
$PDF->Line(15, 25, 210-15, 25);

$PDF->Ln(28);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(8);
$PDF->Cell(3,1,trim($customer_name),'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->Cell(3,1,trim($customer_street),'C');
$PDF->Ln(6);
$PDF->Cell(8);
$PDF->Cell(3,1,trim($customer_postalcode).' '.trim($customer_town),'C');

$PDF->Ln(24);
$PDF->SetFont('HelveticaNeueLight','',14);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(8);
$PDF->Cell(3,1,'Rechnung','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$invoice_number,'C');

$PDF->Ln(15);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(8);
$PDF->Cell(3,1,'Datum ','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$invoice_date,'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Käufer'),'C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,trim($customer_name).', '.trim($customer_postalcode).' '.trim($customer_town),'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Ihre Referenz','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,trim($no_cus),'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Verkäufer'),'C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,trim($importer).', '.trim($importer_postalcode).' '.trim($importer_town),'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Ware','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,utf8_decode(trim($product_name_de)).' aus LDC, '.utf8_decode($country_de),'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Lieferungstermin','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,strtoupper(trim($arrival_month)).' '.$year_lieferung,'C');    
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Lieferbedingungen','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);

if((trim($incoterms) == "FOB")||(trim($incoterms) == "EXW")){
	$PDF->Cell(3,1,trim($cus_incoterms).' '.$port_name,'C');
} else {
	$PDF->Cell(3,1,trim($cus_incoterms).' '.$cus_pod_name,'C');
}	

$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Qualität'),'C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,trim(utf8_decode($var_quality)),'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Verpackung','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$package_name,'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,utf8_decode('Parität'),'C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$var_parity,'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Basis Kontrakt','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$var_basecontract,'C');
$PDF->Ln(6);
$PDF->Cell(51);
$PDF->Cell(3,1,utf8_decode('Allgemeine Verkaufsbedingungen siehe Rückseite'),'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Zahlungsbedingungen','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$var_payment_cond,'C');
$PDF->Ln(7);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Menge / Preis','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$total_vgm_weight.' MT Rohware lose - '.trim($currency).' '.$proposal_price.' / MT - '.trim($currency).' '.$total_invoice,'C');

$PDF->Ln(12);
$PDF->Cell(8);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Container-Nr','C');
$PDF->Cell(40);
$PDF->SetTextColor(0,0,0);


$data1=array();
while ($row_detail1 = pg_fetch_assoc($rs_detail1)){
	array_push($data1, $row_detail1['container_nr']."#".trim($row_detail1['vgm_weight']));
}
$nb1=count($data1);

$data2=array();
while ($row_detail2 = pg_fetch_assoc($rs_detail2)){
	array_push($data2, $row_detail2['container_nr']."#".trim($row_detail2['vgm_weight']));
}
$nb2=count($data2);


if($nb1>$nb2){
	$arr_length=$nb1;
} else {
	$arr_length=$nb2;
}


if(($data1)&&($data2)){
	$tabhcont[0] = 32;
	$tabhcont[1] = 7;
	$tabhcont[2] = 18;
	$tabhcont[3] = 8;
	$tabhcont[4] = 10;
	$tabhcont[5] = 32;
	$tabhcont[6] = 7;
	$tabhcont[7] = 18;
	$tabhcont[8] = 8;
	$tabhcont[9] = "[LB]";
	$tabhcont[10] = "[LB]";
	$tabhcont[11] = "[RB]";
	$tabhcont[12] = "[RB]";
	$tabhcont[13] = "[RB] ";
	$tabhcont[14] = "[LB]";
	$tabhcont[15] = "[LB]";
	$tabhcont[16] = "[RB]";
	$tabhcont[17] = "[RB]";
} else {
	$tabhcont[0] = 32;
	$tabhcont[1] = 7;
	$tabhcont[2] = 18;
	$tabhcont[3] = 8;
	$tabhcont[4] = 74;
	$tabhcont[5] = "[LB]";
	$tabhcont[6] = "[LB]";
	$tabhcont[7] = "[RB]";
	$tabhcont[8] = "[RB]";
	$tabhcont[9] = "[RB] ";
}


for($i=0;$i<$arr_length;$i++) 
{ 
	if($data1[$i]){
		$d1=explode('#', $data1[$i]);
		$container_nr1=$d1[0];
		$vgm_weight1=$d1[1];
	} else {
		$container_nr1="--";
		$vgm_weight1="";
	}
	
	if($data2[$i]){
		$d2=explode('#', $data2[$i]);
		$container_nr2=$d2[0];
		$vgm_weight2=$d2[1];
	} else {
		$container_nr2="--";
		$vgm_weight2="";
	}
	
	if(($data1)&&($data2)){
		$tabvalues[]="[L]".$container_nr1;
		$tabvalues[]="[L] = ";
		$tabvalues[]="[R]".$vgm_weight1;
		$tabvalues[]="[R]MT";
		$tabvalues[]="[R] ";
		$tabvalues[]="[L]".$container_nr2;
		$tabvalues[]="[L] = ";
		$tabvalues[]="[R]".$vgm_weight2;
		$tabvalues[]="[R]MT";
		
	} elseif($data1){
		$tabvalues[]="[L]".$container_nr1;
		$tabvalues[]="[L] = ";
		$tabvalues[]="[R]".$vgm_weight1;
		$tabvalues[]="[R]MT";
		
	} elseif($data2){
		$tabvalues[]="[L]".$container_nr2;
		$tabvalues[]="[L] = ";
		$tabvalues[]="[R]".$vgm_weight2;
		$tabvalues[]="[R]MT";
		
	} else {}
}


$proprietesTableau = array(
'TB_ALIGN' => 'R',
'L_MARGIN' => 0,
'BRD_COLOR' => array(255,255,255),
'BRD_SIZE' => 0,
);

// Définition des propriétés du header du tableau.
$proprieteHeader = array(
'T_COLOR' => array(0,0,0),
'T_SIZE' => 0,
'T_FONT' => 'Arial',
'T_ALIGN' => 'L',
'V_ALIGN' => 'M',
'T_TYPE' => 'B',
'LN_SIZE' => 0,
'BG_COLOR_COL0' => array(255,255,255),
'BG_COLOR' => array(255,255,255),
'BRD_COLOR' => array(255,255,255),
'BRD_SIZE' => 0,
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
'BRD_COLOR' => array(255,255,255),
'BRD_SIZE' => 0.1,
'BRD_TYPE' => '1',
'BRD_TYPE_NEW_PAGE' => '',
);

$PDF->drawTableau($PDF, $proprietesTableau, $proprieteHeader, $tabhcont, $proprieteContenu,$tabvalues);

$PDF->Ln(10);
$PDF->SetFont('HelveticaNeueLight','',11);
$PDF->Cell(50);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Total','C');
// $PDF->Cell(166);
// $PDF->Cell(3,1,$currency.' '.$total_invoice,'C');
$PDF->SetTextColor(0,0,0);
$PDF->Cell(0,1,trim($currency).' '.$total_invoice,0,0,'R');


if($save == 1){
	$pdfFile=$_SERVER['DOCUMENT_ROOT']."/crm/img/documents/".$filename;
	$PDF->Output($pdfFile,'F');
}
	
$PDF->Output();

}

unset($_SESSION["var"]);

?>
