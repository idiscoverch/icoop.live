<?php    
ob_start();
include('PDFheaderfooter.php');
include_once("../fcts.php");
$conn=connect();  

if (isset($_GET['from'])) { $from=$_GET['from']; }
if (isset($_GET['to'])) { $to=$_GET['to']; }
if (isset($_GET['subject'])) { $subject=$_GET['subject']; }
if (isset($_GET['datetime'])) { $datetime=$_GET['datetime']; }
if (isset($_GET['filename'])) { $filename=$_GET['filename']; }

if (isset($_GET['ref'])) { $ref=$_GET['ref']; }
if (isset($_GET['cc'])) { $cc=$_GET['cc']; }
if (isset($_GET['bcc'])) { $bcc=$_GET['bcc']; }
if (isset($_GET['id'])) { $id=$_GET['id']; }


if(!empty($from) 
AND !empty($to) 
AND !empty($datetime) 
AND !empty($subject) 
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

if($cc!=""){
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'CC: ','C');
	$PDF->Cell(28);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$cc,'C');	
}

if($bcc!=""){
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Bcc: ','C');
	$PDF->Cell(28);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$bcc,'C');	
}

$PDF->Ln(5);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Subject: ','C');
$PDF->Cell(28);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$subject,'C');

$PDF->Ln(10);
$PDF->Image('../img/icrm_logo.png',12,51);


// $PDF->Cell(3,1,strip_tags($content),'C');

if($ref=="ProposaltoCustomer"){
	//ProposaltoCustomer
	
	$sql_header = "SELECT   
		v_order_schedule.id_ord_schedule,  
		v_order_schedule.supplier_name,  
		v_order_schedule.person_name,  
		to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,  
		to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,  
		v_order_schedule.created_by_name,  
		v_order_schedule.product_code,  
		v_order_schedule.port_name, 
		v_order_schedule.pod_name,	 
		v_order.incoterms,				
		v_order_schedule.offer_validity_date,  
		v_order_schedule.notes_sup,  
		get_contact_email(v_order_schedule.supplier_person_id) AS email_contact,  
		v_order.sm_person_name as sm_manager,  
		v_order_msg.ord_sm_person_id,  
		v_order_msg.sm_mail,  
		v_order_msg.imp_mail,  
		v_order_msg.cus_email,  
		v_order_msg.imp_admin_mail,  
		v_order_msg.cus_admin_mail,  
		v_order_msg.id_ord_order, 
		v_order.importer_person,					
		v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number,  
		v_order.importer,  
		v_order.ord_imp_contact_id,     
		v_order_msg.imp_phone,  
		v_order_msg.imp_skype,    
		get_contact_name(v_order.ord_imp_contact_id) ord_imp_contact_name,  
		v_order_schedule.order_nr, 
		v_order.ord_cus_contact_id,
		v_order.ord_cus_person_id,
		v_order_msg.ord_imp_person_id,
		v_order_msg.ord_imp_admin_id,
		get_contact_name(v_order.ord_cus_contact_id) customer_name,  
		get_contact_name(v_order.ord_cus_person_id) customer_contact  
		FROM v_order_schedule, v_order_msg, v_order  
		where v_order_msg.id_ord_order=v_order_schedule.ord_order_id  
		and v_order.id_ord_order=v_order_schedule.ord_order_id  
	and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$id ); ";

	$rs_header = pg_query($conn, $sql_header);
	$row_header = pg_fetch_assoc($rs_header);
	
	$imp_phone = $row_header['imp_phone'];
	$imp_skype = $row_header['imp_skype'];
	$imp_mail = $row_header['imp_mail'];
	$importer = $row_header['importer'];
	$importer_person = $row_header['importer_person'];
	$order_number = $row_header['order_number'];
	$customer_name = $row_header['customer_name'];
	$product_code = $row_header['product_code'];
	$customer_contact = $row_header['customer_contact'];
	$pod_name = $row_header['pod_name'];
	$incoterms = $row_header['incoterms'];
	$offer_validity_date = $row_header['offer_validity_date'];
	

	// Email content
	$sql_content = "select order_nr||'.'|| order_ship_nr as no, 
		to_char(month_etd,'Mon-yy')||'/'||week_etd as etd, 
		pol_country as origin, 
		to_char(month_eta,'Mon-yy')||'/'||week_eta as eta, 
		nr_containers, 
		to_char(weight_shipment,'999G999') as weight,
		to_char(proposal_price,'999G999') as proposal_price, 
		to_char(proposal_value,'999G999') as proposal_value 
	from v_schedule_calc where ord_order_id=$id
	order by order_ship_nr::integer";

	$result_detail = pg_query($conn, $sql_content);

	
	// Email footer
	$sql_footer = "select getregvalue(max(proposal_currency_id)) currency, to_char(sum(proposal_value),'999G999') as total 
	from v_schedule_calc where ord_order_id=$id ";
	$rs_footer = pg_query($conn, $sql_footer);
	$row_footer = pg_fetch_assoc($rs_footer);
	
	$currency = $row_footer['currency']; 
	$total = $row_footer['total'];

	
	$PDF->Ln(12);
	$PDF->Cell(23);
	$PDF->Cell(3,1,'iCRM.live Message from the iDiscover.live Back Office:','C');
	$PDF->Ln(5);
	$PDF->Cell(23);
	$PDF->Cell(3,1,'Thank you again for your request for a quote! Please find below the details of our offer:','C');
	$PDF->Ln(20);
	
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'Our Reference: '.$order_number,'C');
	$PDF->Ln(10);

	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Client','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Product','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$customer_name,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$product_code,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Contact','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Currency','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$customer_contact,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$currency,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Destination','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Amount Contract','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$pod_name,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$total,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Incoterms','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Proposal valid until','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$incoterms,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$offer_validity_date,'C');
	
	$PDF->Ln(10);
	$tabhcont[0] = 23;
	$tabhcont[1] = 25;
	$tabhcont[2] = 20;
	$tabhcont[3] = 14;
	$tabhcont[4] = 14;
	$tabhcont[5] = 20;
	$tabhcont[6] = 22;
	$tabhcont[7] = 20;
	$tabhcont[8] = "[LB]Our Ref.";
	$tabhcont[9] = "[LB]ETD";
	$tabhcont[10] = "[LB]ETA";
	$tabhcont[11] = "[LB]Cont.";
	$tabhcont[12] = "[LB]MT";
	$tabhcont[13] = "[LB]Price/MT";
	$tabhcont[14] = "[LB]Total Price";
	$tabhcont[15] = "[LB]Origin";


	// Chargement des données
	while ($row_detail = pg_fetch_assoc($result_detail)){
		$tabvalues[]="[L]".$row_detail['no'];
		$tabvalues[]="[L]".$row_detail['etd'];
		$tabvalues[]="[L]".$row_detail['eta'];
		$tabvalues[]="[L]".$row_detail['nr_containers'];
		$tabvalues[]="[L]".trim($row_detail['weight']);
		$tabvalues[]="[L]".$row_detail['proposal_price'];
		$tabvalues[]="[L]".trim($row_detail['proposal_value']);
		$tabvalues[]="[L]".strtoupper(trim($row_detail['origin']));
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
	$PDF->Cell(3,1,$importer,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Sales Manager:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$importer_person,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Email:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$imp_mail,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Phone:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$imp_phone,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Skype:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$imp_skype,'C');
	
	
} else
if($ref=="ProposalMail"){
	
	// ProposalMail
	$sql_header = "SELECT  
		v_order_schedule.id_ord_schedule, 
		v_order_schedule.supplier_name, 
		v_order_schedule.supplier_contact_id,
		v_order_schedule.person_name, 
		to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date, 
		v_order_schedule.product_code, 
		v_order_schedule.port_name, 
		v_order_schedule.incoterms, 
		v_order_schedule.offer_validity_date, 
		v_order_schedule.tank_provider, 
		v_order.notes_customer,
		get_contact_email(v_order_schedule.supplier_person_id) AS email_contact, 
		v_order.sm_person_name as sm_manager, 
		v_order_msg.ord_sm_person_id, 
		v_order_msg.ord_cus_person_id,
		v_order_msg.ord_cus_admin_id,
		v_order_msg.ord_imp_admin_id,
		v_order_msg.sm_mail, 
		v_order_msg.imp_mail, 
		v_order_msg.sup_mail, 
		v_order.customer_name,
		v_order.customer_contact,
		v_order.port_discharge, 
		v_order_msg.imp_admin_mail, 
		v_order_msg.id_ord_order, 
		v_order.customer_code||'-'||v_order.sup_reference_nr||'-'||v_order.product_code as order_number, 
		v_order.importer, 
		v_order.ord_imp_contact_id, 
		v_order.ord_cus_contact_id,  
		v_order_msg.sm_phone, 
		v_order_msg.sm_skype 
		 FROM v_order_schedule, v_order_msg, v_order 
		where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
		and v_order.id_ord_order=v_order_schedule.ord_order_id 
	and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$id );";
 
	$rs_header = pg_query($conn, $sql_header);
	$row_header = pg_fetch_assoc($rs_header);
	
	$sm_phone = $row_header['sm_phone'];
	$sm_skype = $row_header['sm_skype'];
	$sm_manager = $row_header['sm_manager'];
	$sm_mail = $row_header['sm_mail'];
	$importer = $row_header['importer'];
	$port_discharge = $row_header['port_discharge'];
	$customer_contact = $row_header['customer_contact'];
	$customer_name = $row_header['customer_name'];
	$offer_validity_date = $row_header['offer_validity_date'];
	$incoterms = $row_header['incoterms'];
	$product_code = $row_header['product_code'];
	$proposal_date = $row_header['proposal_date'];
	$tank_provider = $row_header['tank_provider'];


	$sql_content = "select order_nr||'.'|| order_ship_nr as no, 
		to_char(month_etd,'Monyy')as month_etd, 
		week_etd, 
		to_char(month_eta,'Monyy')as month_eta,
		week_eta, 
		nr_containers as no_con, 
		to_char(weight_shipment,'999G999') as weight, 
		to_char(price_sup,'999G999D99') as priceunit, 
		to_char(weight_shipment*price_sup,'999G999') as total, 
		pol_country as origin 
	from v_order_schedule where ord_order_id=$id order by order_ship_nr::integer";

	$result_detail = pg_query($conn, $sql_content);
	$count = pg_num_rows($result_detail);
	if($count>1){$s='S';}else{$s='';} 

	
	// Email footer
	$sql_footer = "select getregvalue(max(price_currency_id)) currency, to_char(sum(weight_shipment*price_sup),'999G999') as totalprice 
	from v_order_schedule where ord_order_id=$id ";
	$rs_footer = pg_query($conn, $sql_footer);
	$row_footer = pg_fetch_assoc($rs_footer);

	$currency = $row_footer['currency']; 
	$total = $row_footer['totalprice'];
	

	$PDF->Ln(12);
	$PDF->Cell(23);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'iCRM.live Message from iDiscover.live Back Office:','C');
	$PDF->Ln(5);
	$PDF->Cell(23);
	$PDF->Cell(3,1,'Thank you for your inquiry. Please find our Proposal based on your request as follows:','C');
	$PDF->Ln(20);
	
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'Proposal Date: '.$proposal_date,'C');
	$PDF->Ln(10);
	
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Product','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Supplier Incoterms','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$product_code,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$incoterms,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Proposal valid until','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Currency','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$offer_validity_date,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$currency,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Customer','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Amount Contract','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$customer_name,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$total,'C');
	
	$PDF->Ln(7);  
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Contact','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Container Provider','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$customer_contact,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$tank_provider,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Destination','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$port_discharge,'C');
	
	$PDF->Ln(14);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'SHIPMENT'.$s,'C');

	$PDF->Ln(10);
	$tabhcont[0] = 23;
	$tabhcont[1] = 25;
	$tabhcont[2] = 20;
	$tabhcont[3] = 14;
	$tabhcont[4] = 14;
	$tabhcont[5] = 20;
	$tabhcont[6] = 22;
	$tabhcont[7] = 20;
	$tabhcont[8] = "[LB]Our Ref.";
	$tabhcont[9] = "[LB]ETD";
	$tabhcont[10] = "[LB]ETA";
	$tabhcont[11] = "[LB]Cont.";
	$tabhcont[12] = "[LB]MT";
	$tabhcont[13] = "[LB]Price/MT";
	$tabhcont[14] = "[LB]TTL";
	$tabhcont[15] = "[LB]Origin";


	// Chargement des données
	while ($row_detail = pg_fetch_assoc($result_detail)){
		$tabvalues[]="[L]".$row_detail['no'];
		$tabvalues[]="[L]".$row_detail['month_etd'].'/'.$row_detail['week_etd'];
		$tabvalues[]="[L]".$row_detail['month_eta'];
		$tabvalues[]="[L]".$row_detail['no_con'];
		$tabvalues[]="[L]".trim($row_detail['weight']);
		$tabvalues[]="[L]".$row_detail['priceunit'];
		$tabvalues[]="[L]".trim($row_detail['total']);
		$tabvalues[]="[L]".strtoupper(trim($row_detail['origin']));
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

	$PDF->Ln(18);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'Message delivered by iDiscover.live back office on behalf of:','C');
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Importer:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$importer,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'SM Manager:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$sm_manager,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Email:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$sm_mail,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Phone:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$sm_phone,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Skype:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$sm_skype,'C');
	
} else
if($ref=="SM_mail"){
	
	// SM_mail
	$sql_header = "SELECT v_order_schedule.id_ord_schedule, 
		v_order_schedule.supplier_name, 
		v_order_schedule.person_name, 
		to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date, 
		v_order_schedule.product_code, 
		v_order_schedule.port_name, 
		v_order_schedule.incoterms, 
		v_order_schedule.offer_validity_date, 
		v_order_schedule.notes_sup, 
		get_contact_email(v_order_schedule.supplier_person_id) AS email_contact, 
		v_order.importer_person, 
		v_order.sm_person_name,
		v_order_msg.cus_email,  
		v_order_msg.imp_mail, 
		v_order_msg.imp_skype, 
		v_order_msg.imp_phone, 
		v_order.ord_cus_contact_id,
		v_order_msg.imp_admin_mail, 
		v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number, 
		v_order.importer, 
		v_order.ord_imp_contact_id,  
		v_order.created_by_name, 
		v_order.created_date, 
		v_order.customer_name, 
		v_order.customer_contact, 
		v_order.notes_customer, 
		v_order.port_discharge, 
		v_order_msg.ord_sm_person_id, 
		v_order_msg.ord_sup_person_id,
		v_order_msg.ord_sup_admin_id,
		v_order.ord_imp_person_id,
		v_order_msg.ord_imp_admin_id,
		v_order_msg.sm_mail, 
		v_order.sm_person_name as sm_manager,
		v_order.incoterms as order_incoterms 
		FROM v_order_schedule, v_order_msg, v_order 
		where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
		 and v_order.id_ord_order=v_order_schedule.ord_order_id 
		 and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) 
	from v_order_schedule where ord_order_id=$id ); ";
  
	$rs_header = pg_query($conn, $sql_header);
	$row_header = pg_fetch_assoc($rs_header);

	$imp_skype = $row_header['imp_skype'];
	$imp_phone = $row_header['imp_phone'];
	$imp_mail = $row_header['imp_mail'];
	$importer_person = $row_header['importer_person'];
	$importer = $row_header['importer'];
	$order_number = $row_header['order_number'];
	$product_code = $row_header['product_code'];
	$order_incoterms = $row_header['order_incoterms'];
	$customer_contact = $row_header['customer_contact'];
	$port_discharge = $row_header['port_discharge'];
	$customer_name = $row_header['customer_name'];

	
	// Email content
	$sql_content = "select order_nr||'.'|| order_ship_nr as no, 
	to_char(month_eta,'Mon-yy')as month_eta, week_eta, 
	to_char(month_etd,'Mon-yy')as month_etd, week_etd,
	nr_containers as no_con,
	to_char(weight_shipment,'999G999') as weight from
	v_order_schedule where ord_order_id=$id
	order by order_ship_nr::integer";
	
	$result_detail = pg_query($conn, $sql_content);

	
	$PDF->Ln(14);
	$PDF->Cell(23);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'iCRM.live Message from iDiscover.live Back Office:','C');
	$PDF->Ln(20);
	
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Company','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Port of Destination','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$customer_name,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$port_discharge,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Contact','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Incoterms','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$customer_contact,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$order_incoterms,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(55);
	$PDF->Cell(3,1,'Product','C');
	$PDF->Ln(5);
	$PDF->Cell(55);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$product_code,'C');
	
	$PDF->Ln(14);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'Our Reference: '.$order_number.'','C');
	
	$PDF->Ln(10);

	// Chargement des données
	while ($row_detail = pg_fetch_assoc($result_detail)){
		if(($row_detail['month_eta']!="")&&($row_detail['week_eta']!="")){
			$ct_month=$row_detail['month_eta'].'/'.$row_detail['week_eta'];
			$tbh='ETA';
		} else {
			$ct_month=$row_detail['month_etd'].'/'.$row_detail['week_etd'];
			$tbh='ETD';
		}
	
		$tabvalues[]="[L]".$row_detail['no'];
		$tabvalues[]="[L]".$ct_month;
		$tabvalues[]="[L]".$row_detail['no_con'];
		$tabvalues[]="[L]".trim($row_detail['weight']);
	}
	
	$tabhcont[0] = 26;
	$tabhcont[1] = 30;
	$tabhcont[2] = 20;
	$tabhcont[3] = 26;
	$tabhcont[4] = "[LB]Our Ref.";
	$tabhcont[5] = "[LB]".$tbh;
	$tabhcont[6] = "[LB]Cont.";
	$tabhcont[7] = "[LB]MT";

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
	$PDF->Cell(3,1,$importer,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Sales Manager:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$importer_person,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Email:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$imp_mail,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Phone:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$imp_phone,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Skype:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$imp_skype,'C');
	
} else
if($ref=="NewRFQ"){
	
	// NewRFQ
	$sql_header = "SELECT v_order_schedule.id_ord_schedule, 
		v_order_schedule.supplier_name, 
		v_order_schedule.person_name, 
		to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date, 
		v_order_schedule.product_code, 
		v_order_schedule.port_name, 
		v_order_schedule.incoterms, 
		v_order_schedule.offer_validity_date, 
		v_order_schedule.notes_sup, 
		get_contact_email(v_order_schedule.supplier_person_id) AS email_contact, 
		v_order.importer_person, 
		v_order_msg.cus_email, 
		v_order_msg.ord_cus_admin_id, 
		v_order_msg.ord_cus_person_id,
		v_order_msg.imp_mail, 
		v_order_msg.imp_phone, 
		v_order_msg.imp_skype, 
		v_order_msg.imp_admin_mail, 
		v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number, 
		v_order.importer,  
		v_order.ord_imp_contact_id,
		v_order_msg.ord_imp_admin_id,
		v_order.created_by_name, 
		v_order.created_date, 
		v_order.customer_name, 
		v_order.customer_contact, 
		v_order.notes_customer, 
		v_order.port_discharge, 
		v_order.incoterms as order_incoterms 
		 FROM v_order_schedule, v_order_msg, v_order 
		 where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
		 and v_order.id_ord_order=v_order_schedule.ord_order_id 
		 and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) 
	from v_order_schedule where ord_order_id=$id )"; 

	$result_header = pg_query($conn, $sql_header);
	$row_header = pg_fetch_assoc($result_header);

	
	$customer_name = $row_header['customer_name'];
	$created_date = $row_header['created_date'];
	$customer_contact = $row_header['customer_contact'];
	$order_number = $row_header['order_number'];
	$order_incoterms = $row_header['order_incoterms'];
	$product_code = $row_header['product_code'];
	$importer = trim($row_header['importer']);
	$importer_person = $row_header['importer_person'];
	$imp_mail = $row_header['imp_mail'];
	$imp_phone = $row_header['imp_phone'];
	$imp_skype = $row_header['imp_skype'];


	$sql_detail = "select customer_reference_nr||'.'||customer_ref_ship_nr as no, to_char(month_eta,'Monyy')as month_eta, week_eta, 
	to_char(month_etd,'Monyy')as month_etd, week_etd, nr_containers as no_con,      
	to_char(weight_shipment,'999G999') as weight from     
	v_order_schedule where ord_order_id=$id order by no";

	$result_detail = pg_query($conn, $sql_detail);

	$PDF->Ln(14);
	$PDF->Cell(23);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'iCRM.live Message from '.$importer.' iDiscover.live Back Office:','C');
	$PDF->Ln(20);
	
	$PDF->Cell(3,1,'Thank you! This is to confirm that we have received a new RFQ from you:','C');
	$PDF->Ln(10);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Client','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Date of request entered in iDiscover','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$customer_name,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$created_date,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Contact','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Requested entered by','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$customer_contact,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$created_by_name,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Port of Destination','C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,'Our Reference','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$port_discharge,'C');
	$PDF->Cell(52);
	$PDF->Cell(3,1,$order_number,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Incoterms','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$order_incoterms,'C');
	
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Product','C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$product_code,'C');
	
	$PDF->Ln(10);
	$tabhcont[0] = 26;
	$tabhcont[1] = 26;
	$tabhcont[2] = 20;
	$tabhcont[3] = 30;
	$tabhcont[4] = "[LB]Our Ref.";
	$tabhcont[5] = "[LB]Cont.";
	$tabhcont[6] = "[LB]MT";
	if($row_detail['month_eta']!=""){
		$tabhcont[7] = "[LB]ETA";
	} else {
		$tabhcont[7] = "[LB]ETD";
	}

	// Chargement des données
	while ($row_detail = pg_fetch_assoc($result_detail)){
		$tabvalues[]="[L]".$row_detail['no'];
		$tabvalues[]="[L]".$row_detail['no_con'];
		$tabvalues[]="[L]".trim($row_detail['weight']);
		if($row_detail['month_eta']!=""){
			$tabvalues[]="[L]".$row_detail['month_eta'].'/'.$row_detail['week_eta'];
		} else {
			$tabvalues[]="[L]".$row_detail['month_etd'].'/'.$row_detail['week_etd'];
		}
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
	$PDF->Cell(3,1,$importer,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Sales Manager:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$importer_person,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Email:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$imp_mail,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Phone:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$imp_phone,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Skype:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$imp_skype,'C');
	
}  else
if($ref=="NewBooking"){
	
	// NewBooking
	$sql_header = "SELECT ob.id_con_booking,
		l.no_sup||'-'||vo.product_code as sup_reference,
		l.ref_code_cus,
		l.ref_code_fa,
		l.ref_code_imp,
		l.ref_code_sup,
		l.supplier_name,
		ob.booking_nr,
		ob.ord_schedule_id,
		ob.booking_type_id,
		ob.con_load_date_from,
		ob.con_load_date_to,
		ob.cutoff_date,
		l.nr_containers,
		l.pol_code,
		ob.pol_etd,
		ob.vessel_feeder_name,
		ob.trans_port_id as feeder_trans_port_code,
		get_port_name(ob.trans_port_id) as feeder_trans_port_code_name,    
		ob.tport_etd,
		ob.tport_eta,
		ob.vessel_name,
		ob.pol,
		ob.pod,
		ob.pod_eta,
		ob.pod_id as final_port_code,
		get_port_name(ob.pod_id) as final_port_code_name,
		o.ord_imp_person_id,
		m.imp_mail,
		o.ord_imp_admin_id,
		m.imp_admin_mail,
		o.ord_cus_person_id,
		m.cus_email,
		o.ord_cus_admin_id,
		m.cus_admin_mail,
		o.ord_fa_person_id,
		m.fa_mail,
		o.ord_fa_admin_id,
		m.fa_admin_mail,
		o.ord_cus_contact_id AS customer,
		m.ord_sup_person_id,
		m.sup_mail,
		m.ord_sup_admin_id,
		m.sup_admin_mail,
		vo.sm_person_name,
		m.sm_phone,
		m.sm_mail,
		m.sm_skype,
		vo.sm_person_id,
		ob.load_manager_id,
		get_contact_name(ob.load_manager_id) as load_manager_name,
		get_contact_email(ob.load_manager_id) as load_manager_email,
		vo.customer_code||'-'||vo.order_nr||'.'||l.order_ship_nr as file_name,
		l.ref_code_cus, 
		l.ref_code_fa,
		l.ref_code_imp,
		l.ref_code_sup
		FROM ord_con_booking ob,
			v_logistics     l,
			ord_order       o,
			v_order         vo,
			v_order_msg     m
		WHERE ob.id_con_booking = $id
			AND l.id_ord_schedule = ob.ord_schedule_id
			AND o.id_ord_order = l.ord_order_id
			AND vo.id_ord_order = l.ord_order_id
			AND m.id_ord_order = l.ord_order_id 
	"; 

	$result_header = pg_query($conn, $sql_header);
	$row_header = pg_fetch_assoc($result_header);

	$id_con_booking = $row_header['id_con_booking'];
	$sup_reference = $row_header['sup_reference'];
	$ref_code_cus = $row_header['ref_code_cus'];
	$ref_code_fa = $row_header['ref_code_fa'];
	$ref_code_imp = $row_header['ref_code_imp'];
	$ref_code_sup = $row_header['ref_code_sup'];
	$supplier_name = $row_header['supplier_name'];
	$booking_nr = $row_header['booking_nr'];
	$con_load_date_from = $row_header['con_load_date_from'];
	$con_load_date_to = $row_header['con_load_date_to'];
	$cutoff_date = $row_header['cutoff_date'];
	$nr_containers = $row_header['nr_containers'];
	$pol_code = $row_header['pol_code'];
	$pol_etd = $row_header['pol_etd'];
	$vessel_feeder_name = $row_header['vessel_feeder_name'];
	$feeder_trans_port_code = $row_header['feeder_trans_port_code'];
	$feeder_trans_port_code_name = $row_header['feeder_trans_port_code_name'];
	$tport_etd = $row_header['tport_etd'];
	$tport_eta = $row_header['tport_eta'];
	$vessel_name = $row_header['vessel_name'];
	$pol = $row_header['pol'];
	$pod = $row_header['pod'];
	$pod_eta = $row_header['pod_eta'];
	$final_port_code = $row_header['final_port_code'];
	$final_port_code_name = $row_header['final_port_code_name'];
	$ord_imp_person_id = $row_header['ord_imp_person_id'];
	$imp_mail = $row_header['imp_mail'];
	$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
	$imp_admin_mail = $row_header['imp_admin_mail'];
	$ord_cus_person_id = $row_header['ord_cus_person_id'];
	$cus_email = $row_header['cus_email'];
	$ord_cus_admin_id = $row_header['ord_cus_admin_id'];
	$cus_admin_mail = $row_header['cus_admin_mail'];
	$ord_fa_person_id = $row_header['ord_fa_person_id'];
	$fa_mail = $row_header['fa_mail'];
	$ord_fa_admin_id = $row_header['ord_fa_admin_id'];
	$fa_admin_mail = $row_header['fa_admin_mail'];
	$customer = $row_header['customer'];
	$ord_sup_person_id = $row_header['ord_sup_person_id'];
	$sup_mail = $row_header['sup_mail'];
	$ord_sup_admin_id = $row_header['ord_sup_admin_id'];
	$sup_admin_mail = $row_header['sup_admin_mail'];
	$sm_person_name = $row_header['sm_person_name'];
	$sm_phone = $row_header['sm_phone'];
	$sm_mail = $row_header['sm_mail'];
	$sm_skype = $row_header['sm_skype'];
	$sm_person_id = $row_header['sm_person_id'];
	$load_manager_id = $row_header['load_manager_id'];
	$load_manager_name = $row_header['load_manager_name'];
	$load_manager_email = $row_header['load_manager_email'];
	$ord_schedule_id = $row_header['ord_schedule_id'];
	$booking_type_id = $row_header['booking_type_id'];
	$file_name = $row_header['file_name'];

	
	$PDF->Ln(14);
	$PDF->Cell(23);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'iCRM.live Message from the iDiscover Back Office.','C');
	$PDF->Ln(20);
	
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Charger','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$supplier_name,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Booking Number','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$booking_nr,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'No Containers','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$nr_containers,'C');
	$PDF->Ln(7);
	
	if($booking_type_id==0){
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Feeder Vessel','C');
		$PDF->Cell(38);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,$vessel_feeder_name,'C');
		
		$PDF->Ln(5);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Port of Loading','C');
		$PDF->Cell(38);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,$pol,'C');
		$PDF->Cell(71);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'ETD','C');
		$PDF->Cell(12);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,$tport_etd,'C');
		
		$PDF->Ln(5);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Transshipment','C');
		$PDF->Cell(38);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,trim($feeder_trans_port_code_name),'C');
		$PDF->Cell(71);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'ETA','C');
		$PDF->Cell(12);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,$tport_eta,'C');
		
		$PDF->Ln(5);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Vessel Name','C');
		$PDF->Cell(38);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,$vessel_name,'C');
		
		$PDF->Ln(5);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Port of Loading','C');
		$PDF->Cell(38);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,trim($feeder_trans_port_code_name),'C');
		$PDF->Cell(71);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'ETD','C');
		$PDF->Cell(12);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,trim($pol_etd),'C');
		
		$PDF->Ln(5);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Port of Discharge','C');
		$PDF->Cell(38);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,trim($pod),'C');
		$PDF->Cell(71);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'ETA','C');
		$PDF->Cell(12);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,trim($pod_eta),'C');
	
	} else 
	if($booking_type_id==1){
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Vessel Name','C');
		$PDF->Cell(38);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,$vessel_name,'C');
		
		$PDF->Ln(5);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Port of Loading','C');
		$PDF->Cell(38);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,trim($pol),'C');
		$PDF->Cell(71);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'ETD','C');
		$PDF->Cell(12);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,trim($pol_etd),'C');

		$PDF->Ln(5);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'Port of Discharge','C');
		$PDF->Cell(38);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,trim($pod),'C');
		$PDF->Cell(71);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(103,106,108);
		$PDF->Cell(3,1,'ETA','C');
		$PDF->Cell(12);
		$PDF->SetFont('HelveticaNeueLight','',10);
		$PDF->SetTextColor(0,0,0);
		$PDF->Cell(3,1,trim($pod_eta),'C');
		
	}
	
	$PDF->Ln(12);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Loading from','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,trim($con_load_date_from),'C');
	$PDF->Cell(71);
	$PDF->SetFont('HelveticaNeueLight','B',10);
	$PDF->Cell(3,1,'to','C');
	$PDF->Cell(12);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,trim($con_load_date_to),'C');
	
	$PDF->Ln(18);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,'Message delivered by iDiscover.live back office on behalf of:','C');
	$PDF->Ln(7);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Name:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$sm_person_name,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Email:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$sm_mail,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Phone:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$sm_phone,'C');
	$PDF->Ln(5);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(103,106,108);
	$PDF->Cell(3,1,'Skype:','C');
	$PDF->Cell(38);
	$PDF->SetFont('HelveticaNeueLight','',10);
	$PDF->SetTextColor(0,0,0);
	$PDF->Cell(3,1,$sm_skype,'C');
}


$PDF->Ln(15);
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
