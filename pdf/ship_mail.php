<?php    

ob_start();
include('PDFheaderfooter.php');
include_once("../fcts.php");
$conn=connect();  

if (isset($_GET['second'])) { $second=$_GET['second']; }
if (isset($_GET['pod'])) { $pod=$_GET['pod']; }
if (isset($_GET['to'])) { $to=$_GET['to']; }
if (isset($_GET['subject'])) { $subject=$_GET['subject']; }
if (isset($_GET['datetime'])) { $datetime=$_GET['datetime']; }
if (isset($_GET['filename'])) { $filename=$_GET['filename']; }
if (isset($_GET['ord_schedule_id'])) { $ord_schedule_id=$_GET['ord_schedule_id']; }


if(!empty($second) 
AND !empty($pod) 
AND !empty($to) 
AND !empty($subject) 
AND !empty($datetime) 
AND !empty($filename) 
AND !empty($ord_schedule_id) 
){

	$sql_notif="SELECT f.mmsi,
						f.imo,
						f.shipname,
						f.ord_booking_id,
						f.ord_order_id,
						f.ord_schedule_id,
						f.pod_eta,
						f.pod_port_code,
						f.vessel_type,
						CASE
						 WHEN f.vessel_type = 0 THEN 'Vessel'
						 WHEN f.vessel_type = 1 THEN 'Feeder @origin'
						 WHEN f.vessel_type = 2 THEN 'ISO Positionning Vessel'
						 WHEN f.vessel_type = 3 THEN 'Feeder @onward'
						END
						 AS vessel_type_name,
						f.pod_eta_actual,
						f.timest,
						f.pol_port_code,
						f.pol_etd,
						f.pol_1,
						f.pod_1,
						f.pod_2,
						f.eta_calc,
						l.pipeline_id,
						l.pipeline_name,
						l.id_ord_schedule,
						l.reference_nr,
						l.id_con_booking,
						l.supplier_contact_id,
						l.supplier_name,
						l.pol_id,
						l.pol_name,
						l.pod_id,
						l.pod_name,
						l.booking_type_id,
						l.booking_type_name,
						l.customer_ref_ship_nr,
						l.order_ship_nr,
						l.supplier_reference_nr,
						l.ord_sm_person_id,
						l.ord_cus_contact_id,
						l.ord_imp_contact_id,
						l.ord_order_id,
						l.pol_code,
						l.pod_code,
						l.order_nr,
						l.fa_reference_nr,
						l.sup_reference_nr,
						l.customer_reference_nr,
						l.week_eta,
						l.o_pod_id,
						l.o_pod_name,
						l.o_pod_code,
						l.c_pol_id,
						l.c_pol_name,
						l.c_pol_code,
						l.shiping_company_id,
						l.shiping_company_name,
						l.cus_incoterms_id,
						l.no_con_shipped,
						l.all_shipped,
						l.week_etd,
						l.month_etd,
						l.add_ocean,
						l.add_onward,
						l.month_eta,
						l.booking_segment,
						l.segments_nr,
						l.segment_max,
						l.pipeline_sched_id,
						l.pipeline_sched_name,
						l.flag_book_add,
						l.flag_onw,
						l.flag_onw_add,
						l.nr_containers,
						l.weight_shipment,
						l.ord_fa_contact_id,
						l.no_cus,
						l.no_fa,
						l.no_sup,
						l.no_imp,
						l.ref_code_cus,
						l.ref_code_fa,
						l.ref_code_sup,
						l.ref_code_imp,
						get_contact_name(l.ord_imp_contact_id) as importer_name,
						get_contact_name(l.ord_sm_person_id) as sm_person_name,
						m.ord_imp_person_id,
						get_contact_name (m.ord_imp_person_id) imp_person_name,
						m.imp_mail,
						m.ord_imp_admin_id,
						m.imp_admin_mail,
						m.ord_fa_person_id,
						m.fa_mail,
						m.ord_fa_admin_id,
						m.fa_admin_mail,
						m.ord_cus_person_id,
						m.cus_email,
						m.ord_cus_admin_id,
						m.cus_admin_mail,
						m.ord_sup_person_id,
						m.sup_mail,
						m.ord_sup_admin_id,
						m.sup_admin_mail,
						m.sm_phone,
						m.sm_skype,
						m.sm_mail
						  FROM ord_ship_fleet f, v_logistics l, v_order_msg m
						 WHERE     f.ord_schedule_id = $ord_schedule_id
						AND l.id_ord_schedule = f.ord_schedule_id
						AND m.id_ord_order = l.ord_order_id
	";
	
	$rlt_notif = pg_query($conn, $sql_notif);
	$arr_notif = pg_fetch_assoc($rlt_notif);
	
	$timest = $arr_notif['timest'];
					$pod_code = $arr_notif['pod_code'];
					$pod_eta = $arr_notif['pod_eta'];
					$shipname = $arr_notif['shipname'];
					$vessel_type = $arr_notif['vessel_type'];
					$pol_port_code = $arr_notif['pol_port_code'];
					$pod_port_code = $arr_notif['pod_port_code'];
					$vessel_type_name = $arr_notif['vessel_type_name'];
					$ord_imp_contact_id = $arr_notif['ord_imp_contact_id'];
					$sm_person_id = $arr_notif['ord_sm_person_id'];
					$name = $arr_notif['sm_person_name'];
					$company_name = $arr_notif['importer_name'];
					$ref_code_fa = $arr_notif['ref_code_fa'];
					$ref_code_imp = $arr_notif['ref_code_imp'];
					$ref_code_cus = $arr_notif['ref_code_cus'];
					$ref_code_sup = $arr_notif['ref_code_sup'];
					$email = $arr_notif['sm_mail'];
					$p_phone = $arr_notif['sm_phone'];
					// $reference_nr = $arr_notif['reference_nr'];
					$reference_nr = $arr_notif['no_imp'];
					$created_date = gmdate("Y/m/d H:i");
					
					// Mails
					if($arr_notif['cus_email']!=""){
						$cus_email = trim($arr_notif['cus_email']).',';
					} else { $cus_email = ''; }
					
					if($arr_notif['cus_admin_mail']!=""){
						$cus_admin_mail = trim($arr_notif['cus_admin_mail']).',';
					} else { $cus_admin_mail = ''; }
					
					if($arr_notif['imp_mail']!=""){
						$imp_mail = trim($arr_notif['imp_mail']).',';
					} else { $imp_mail = ''; }
					
					if($arr_notif['imp_admin_mail']!=""){
						$imp_admin_mail = trim($arr_notif['imp_admin_mail']).',';
					} else { $imp_admin_mail = ''; }
					
					if($arr_notif['fa_mail']!=""){
						$fa_mail = trim($arr_notif['fa_mail']).',';
					} else { $fa_mail = ''; }
					
					if($arr_notif['fa_admin_mail']!=""){
						$fa_admin_mail = trim($arr_notif['fa_admin_mail']).',';
					} else { $fa_admin_mail = ''; }
					
					$ord_cus_person_id = $arr_notif['ord_cus_person_id'];
					$ord_cus_admin_id = $arr_notif['ord_cus_admin_id'];
					$ord_imp_person_id = $arr_notif['ord_imp_person_id'];
					$ord_imp_admin_id = $arr_notif['ord_imp_admin_id'];
					$ord_fa_person_id = $arr_notif['ord_fa_person_id'];
					$ord_fa_admin_id = $arr_notif['ord_fa_admin_id'];
					
			
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
			$PDF->Cell(3,1,$to,'C');

			$PDF->Ln(5);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'CC: ','C');
			$PDF->Cell(28);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,trim($imp_admin_mail),'C'); 
			
			if($fa_mail){
				$PDF->Ln(5);
				$PDF->Cell(31);
				$PDF->Cell(3,1,trim($sup_mail),'C'); 
			}
			
			if($fa_admin_mail){
				$PDF->Ln(5);
				$PDF->Cell(31);
				$PDF->Cell(3,1,trim($sup_admin_mail),'C'); 
			}
			
			
			$PDF->Ln(8);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'Subject: ','C');
			$PDF->Cell(28);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$subject,'C');

			$PDF->Ln(21);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$ref_code_fa.' | '.$ref_code_imp.' | '.$ref_code_cus.' | '.$ref_code_sup,'C');

			$PDF->Image('../img/icrm_logo.png',12,94);
			$PDF->Ln(16);
			$PDF->Cell(23);
			$PDF->Cell(3,1,'iCRM.live Message from the iDiscover Back Office.','C');
			$PDF->Ln(20);
		
			if($second==0){ 
			
				$PDF->SetFont('HelveticaNeueLight','',10);
				$PDF->SetTextColor(103,106,108);
				$PDF->Cell(3,1,'Vessel Type ','C');
				$PDF->Cell(38);
				$PDF->SetFont('HelveticaNeueLight','',10);
				$PDF->SetTextColor(0,0,0);
				$PDF->Cell(3,1,$vessel_type_name,'C');
				$PDF->Ln(5);
				
				$PDF->SetFont('HelveticaNeueLight','',10);
				$PDF->SetTextColor(103,106,108);
				$PDF->Cell(3,1,'Vessel ','C');
				$PDF->Cell(38);
				$PDF->SetFont('HelveticaNeueLight','',10);
				$PDF->SetTextColor(0,0,0);
				$PDF->Cell(3,1,$shipname,'C');
				$PDF->Ln(5);

				$PDF->SetFont('HelveticaNeueLight','',10);
				$PDF->SetTextColor(103,106,108);
				$PDF->Cell(3,1,'Arrived at ','C');
				$PDF->Cell(38);
				$PDF->SetFont('HelveticaNeueLight','',10);
				$PDF->SetTextColor(0,0,0);
				$PDF->Cell(3,1,$pod_port_code.' @'.$timest,'C');
				$PDF->Ln(5);
				
			} else {
				if($pod==1){ 
					
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Vessel ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$shipname,'C');
					$PDF->Ln(5);
					
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Port of Discharge ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$pod_port_code,'C');
					$PDF->Ln(5);

					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Planned arrival ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$pod_eta,'C');
					$PDF->Ln(5);
					
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,'The Rhine-Barge just arrived at NEUF-BRISACH (FRNEF) on the way to Basel.','C');
					
				} else
				if($pod==2){
				
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Vessel ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$shipname,'C');
					$PDF->Ln(5);
					
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Port of Discharge ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$pod_port_code,'C');
					$PDF->Ln(5);

					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Planned arrival ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$pod_eta,'C');
					$PDF->Ln(5);
					
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,'The Rhine-Barge just arrived at RHEIN AM WEIL (DEWLR) on the way to Basel.','C');
					
				} else 
				if($pod==3){
					
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Vessel Type ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$vessel_type_name,'C');
					$PDF->Ln(5);
					
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Vessel ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$shipname,'C');
					$PDF->Ln(5);

					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Departed from ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$pol_port_code.' @'.$timest,'C');
					$PDF->Ln(5);
					
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Port of Discharge ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$pod_port_code,'C');
					$PDF->Ln(5);
					
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(103,106,108);
					$PDF->Cell(3,1,'Planned arrival ','C');
					$PDF->Cell(38);
					$PDF->SetFont('HelveticaNeueLight','',10);
					$PDF->SetTextColor(0,0,0);
					$PDF->Cell(3,1,$pod_eta,'C');
					$PDF->Ln(5);
					
				} else {}
			}
	
			
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
			$PDF->Cell(3,1,$name,'C');
			$PDF->Ln(5);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'Email:','C');
			$PDF->Cell(38);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$email,'C');
			$PDF->Ln(5);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(103,106,108);
			$PDF->Cell(3,1,'Phone:','C');
			$PDF->Cell(38);
			$PDF->SetFont('HelveticaNeueLight','',10);
			$PDF->SetTextColor(0,0,0);
			$PDF->Cell(3,1,$p_phone,'C');

			
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

print '<script type="text/javascript">window.top.location.href = "https://idiscover.ch/crm/fleet.php";</script>';

?>
