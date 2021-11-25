<?php    

ob_start();
include('PDFheaderfooter.php');
include_once("../fcts.php");
$conn=connect();  

if (isset($_GET['type'])) { $type=$_GET['type']; }
if (isset($_GET['filename'])) { $filename=$_GET['filename']; }
if (isset($_GET['datetime'])) { $datetime=$_GET['datetime']; }


if(!empty($type) 
AND !empty($filename) 
AND !empty($datetime) 
){
	if($type == 'end'){
		$cond='oh.end_state_status=1';
	} else {
		$cond='oh.start_status=1';
	}

	$sql="select ob.id_con_booking,
		vo.customer_code||'-'||l.no_sup||'-'||vo.product_code as subject,
		o.port_code,
		ob.booking_nr,
		ob.booking_type_id,
		ob.pol,
		CASE
		WHEN ob.vessel_feeder_mmsi_id is null THEN 0
		WHEN ob.vessel_feeder_mmsi_id is not null THEN 1
		END
		AS vessel_type,
		CASE
		WHEN ob.vessel_feeder_mmsi_id is null THEN 'Vessel'
		WHEN ob.vessel_feeder_mmsi_id is not null THEN 'Feeder Vessel'
		END
		AS vessel_type_name,
		CASE
		WHEN ob.vessel_feeder_mmsi_id is null THEN ob.vessel_name
		WHEN ob.vessel_feeder_mmsi_id is not null THEN ob.vessel_feeder_name
		END
		AS shipname,
		get_port_code(ob.pol_id) as pol_port_code,
		get_port_code(ob.pod_id) as pod_port_code,
		ob.pod_eta,
		ob.pol_etd,
		oh.start_loading, 
		oh.end_loading,
		l.id_ord_schedule,
		l.ref_code_cus,
		l.ref_code_fa,
		l.ref_code_imp,
		l.ref_code_sup,
		l.supplier_name,
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
		l.nr_containers,
		vo.sm_person_id,
		ob.load_manager_id,
		get_contact_name(ob.load_manager_id) as load_manager_name,
		get_contact_email(ob.load_manager_id) as load_manager_email,
		vo.customer_code||'-'||vo.order_nr||'.'||l.order_ship_nr as file_name,
		vo.importer_person,
		l.pol_code,ob.pol_etd,
		ob.vessel_feeder_name,
		ob.ord_schedule_id,
		ob.trans_port_id as feeder_trans_port_code,
		get_port_name(ob.trans_port_id) as feeder_trans_port_code_name,    
		ob.tport_etd,
		ob.tport_eta,
		ob.vessel_name,ob.pod,ob.pod_eta,
		ob.pod_id as final_port_code,
		get_port_name(ob.pod_id) as final_port_code_name
		from ord_con_booking ob, ord_con_loading_header oh,  v_logistics l, ord_order o, v_order vo, v_order_msg m
		where $cond
		and oh.ord_con_booking_id=ob.id_con_booking
		and l.id_ord_schedule=ob.ord_schedule_id
		and o.id_ord_order=l.ord_order_id
		and vo.id_ord_order=l.ord_order_id
		and m.id_ord_order=l.ord_order_id
	";
	
	$result = pg_query($conn, $sql);
	
	if($result){
		while($arr = pg_fetch_assoc($result)){
			
			$ref_code_cus = $arr['ref_code_cus'];
			$ref_code_fa = $arr['ref_code_fa'];
			$ref_code_imp = $arr['ref_code_imp'];
			$ref_code_sup = $arr['ref_code_sup'];
			$end_loading = $arr['end_loading'];
			$start_loading = $arr['start_loading'];
			$supplier_name = $arr['supplier_name'];
			$booking_nr = $arr['booking_nr'];
			$nr_containers = $arr['nr_containers'];
			$shipname = $arr['shipname'];
			$pol_port_code = $arr['pol_port_code'];
			$pod_port_code = $arr['pod_port_code'];
			// $vessel_type_name = $arr['vessel_type_name'];
			$pol_etd = $arr['pol_etd'];
			$pod_eta = $arr['pod_eta'];
		
			$sm_person_name = $arr['sm_person_name'];
			$sm_phone = $arr['sm_phone'];
			$sm_mail = $arr['sm_mail'];
			$sm_skype = $arr['sm_skype'];
			
			$ord_imp_person_id = $arr['ord_imp_person_id'];
			$imp_mail = $arr['imp_mail'];
			$ord_imp_admin_id = $arr['ord_imp_admin_id'];
			$imp_admin_mail = $arr['imp_admin_mail'];
			$ord_cus_person_id = $arr['ord_cus_person_id'];
			$cus_email = $arr['cus_email'];
			$ord_cus_admin_id = $arr['ord_cus_admin_id'];
			$cus_admin_mail = $arr['cus_admin_mail'];
			$ord_fa_person_id = $arr['ord_fa_person_id'];
			$fa_mail = $arr['fa_mail'];
			$ord_fa_admin_id = $arr['ord_fa_admin_id'];
			$fa_admin_mail = $arr['fa_admin_mail'];
			$customer = $arr['customer'];
			$ord_sup_person_id = $arr['ord_sup_person_id'];
			$sup_mail = $arr['sup_mail'];
			$ord_sup_admin_id = $arr['ord_sup_admin_id'];
			$sup_admin_mail = $arr['sup_admin_mail'];
			$sm_person_id = $arr['sm_person_id'];
			$load_manager_id = $arr['load_manager_id'];
			$load_manager_email = $arr['load_manager_email'];
			$load_manager_name = $arr['load_manager_name'];
		
			$vessel_feeder_name = $arr['vessel_feeder_name'];
			$ord_schedule_id = $arr['ord_schedule_id'];
			$file_name = $arr['file_name'];
			
			$vessel_type = $arr['vessel_type'];
			if($vessel_type == 0){
				$feeder="";
				$vesselName = $shipname;
				$portOfLoading = $pol_port_code;
			} else {
				$feeder="Feeder";
				$vesselName = $vessel_feeder_name;
				$portOfLoading = $pol_port_code;
			}
			
			
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
			$PDF->Cell(3,1,'noreply@idiscover.ch','C');
			$PDF->Ln(5);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'To: ','C');
			$PDF->Cell(28);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$imp_mail,'C');

			$PDF->Ln(5);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'CC: ','C');
			$PDF->Cell(28);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,trim($imp_admin_mail),'C'); 
			
			if($sup_mail){
				$PDF->Ln(5);
				$PDF->Cell(31);
				$PDF->Cell(3,1,trim($sup_mail),'C'); 
			}
			
			if($sup_admin_mail){
				$PDF->Ln(5);
				$PDF->Cell(31);
				$PDF->Cell(3,1,trim($sup_admin_mail),'C'); 
			}
			
			if($fa_mail){
				$PDF->Ln(5);
				$PDF->Cell(31);
				$PDF->Cell(3,1,trim($fa_mail),'C'); 
			}
			
			if($fa_admin_mail){
				$PDF->Ln(5);
				$PDF->Cell(31);
				$PDF->Cell(3,1,trim($fa_admin_mail),'C'); 
			}
			
			if($sm_person_name){
				$PDF->Ln(5);
				$PDF->Cell(31);
				$PDF->Cell(3,1,trim($sm_person_name),'C'); 
			}
			
			if($load_manager_email){
				$PDF->Ln(5);
				$PDF->Cell(31);
				$PDF->Cell(3,1,trim($load_manager_email),'C'); 
			}
			
			$PDF->Ln(8);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'Subject: ','C');
			$PDF->Cell(28);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			
			if($type == 'end'){
				$PDF->Cell(3,1,$ref_code_sup.' - live.loading ended @'.$end_loading,'C');
			} else {
				$PDF->Cell(3,1,$ref_code_sup.' - live.loading started @'.$start_loading,'C');
			}

			$PDF->Ln(21);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$ref_code_fa.' | '.$ref_code_imp.' | '.$ref_code_cus.' | '.$ref_code_sup,'C');

			$PDF->Image('../img/icrm_logo.png',12,94);
			$PDF->Ln(16);
			$PDF->Cell(23);
			$PDF->Cell(3,1,'iCRM.live Message from the iDiscover Back Office.','C');
			$PDF->Ln(20);
		
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'Charger ','C');
			$PDF->Cell(38);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$supplier_name,'C');
			$PDF->Ln(5);
			
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'Booking Number ','C');
			$PDF->Cell(38);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$booking_nr,'C');
			$PDF->Ln(5);

			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'No Containers ','C');
			$PDF->Cell(38);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$nr_containers,'C');
			$PDF->Ln(5);

			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'Port of Loading ','C');
			$PDF->Cell(38);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$portOfLoading,'C');
			$PDF->Ln(5);
			
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,$feeder.' Vessel Name','C');
			$PDF->Cell(38);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$vesselName,'C');
			$PDF->Ln(5);

			
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

			if($type == 'end'){
				$sql_update = "update ord_con_loading_header set end_state_status=2 where ord_schedule_id=$ord_schedule_id";
			} else {
				$sql_update = "update ord_con_loading_header set start_status=2 where ord_schedule_id=$ord_schedule_id";
			}
			pg_query($conn, $sql_update);

			
			$pdfFile=$_SERVER['DOCUMENT_ROOT']."/crm/img/documents/".$filename;
			$PDF->Output($pdfFile,'F');	
			
			$PDF->Output();
		}
	}

}

?>
