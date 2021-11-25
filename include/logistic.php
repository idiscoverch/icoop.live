<?php

session_start();
error_reporting(0);

if(!isset($_SESSION['username'])){
	header("Location: ../login.php");
}

include_once("../fcts.php");
include_once("../common.php");

header("Content-type: image/png");


if (isset($_GET["elemid"])) {

    $elemid = $_GET["elemid"];
    $conn=connect();

	if(!$conn) {
		header("Location: error_db.php");
	}

    $dom='';

	switch ($elemid) {
		case "logistiques":
			
			$doc_right = $_GET['doc_right'];
			
			$id_cond = "";
			$id_cond2 = "";
			$condition = "";
			$condition2 = "";
			
			if($_GET['cond']!=0){
				$cond = $_GET['cond'];
				$condition = " AND pipeline_sched_id = $cond";
				$condition2 = "pipeline_sched_id = $cond AND";
			}
			
			if($_GET['schedule_id']!=0){
				$schedule_id = $_GET['schedule_id'];
				$id_cond = " AND id_ord_schedule = $schedule_id";
				
				if($condition2=="") { $id_cond2 = "id_ord_schedule = $schedule_id AND"; }
				else { $id_cond2 = $id_cond; }
			}
			
			$id_user = $_SESSION['id_user'];
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
			if($id_user_supchain_type == 312){
				$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 312) as shipment_number_1, 
					public.get_reference_nr_2(id_ord_schedule, 312) as shipment_number_2, cus_incoterms_id,
				Sup_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup
				from v_logistics  
				where ord_sm_person_id=$id_contact $condition $id_cond order by month_etd ";
				
			} else {
				if($id_supchain_type == 110){
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 110) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 110) as shipment_number_2, cus_incoterms_id,
					Customer_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
					from v_logistics  
					where ord_cus_contact_id=$id_company $condition $id_cond order by month_eta ";
					
				} else
				if($id_supchain_type == 112){
					if($id_company == 717){
						$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 113) as shipment_number_1, 
							public.get_reference_nr_2(id_ord_schedule, 113) as shipment_number_2, cus_incoterms_id,
						Order_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
						from v_logistics  
						where ord_imp_contact_id=$id_company $condition $id_cond order by month_etd ";
						
					} else {
						$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 112) as shipment_number_1, 
							public.get_reference_nr_2(id_ord_schedule, 112) as shipment_number_2, cus_incoterms_id,
							Order_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
							 from v_logistics 
							where ord_imp_contact_id=$id_company $condition $id_cond
							Or ( $condition2 $id_cond2 ord_imp_contact_id in ( select id_contact from (
							select id_contact from ( 
							select * from contact where id_contact in ( 
							select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where 
							id_contact=(select id_contact from users where id_user=$id_user ) ) )
							UNION
							select * from contact where id_primary_company in ( 
							select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where 
							id_contact=(select id_contact from users where id_user=$id_user ) ) )
							and id_contact in ( select id_contact from users ) 
							union
							select * from contact where id_contact in ( 
							select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where 
							id_contact=(select id_contact from users where id_user=$id_user ) ) )
							union
							select * from contact where id_primary_company in ( 
							select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where 
							id_contact=(select id_contact from users where id_user=$id_user ) ) )
							and id_contact in ( select id_contact from users )
							union
							select * from contact where id_primary_company in ( select id_primary_company from contact where 
							id_contact=(select id_contact from users where id_user=$id_user ) )
							union
							select * from contact where id_primary_company in ( select id_link from contact_links where 
							id_contact in ( select id_primary_company from contact where 
							id_contact=(select id_contact from users where id_user=$id_user ) ))
							union
							select * from contact where id_primary_company in ( select id_contact from contact_links where 
							id_link in ( select id_primary_company from contact where 
							id_contact=(select id_contact from users where id_user=$id_user ) ))
							) q1
							where id_type=10 and id_supchain_type=112
						) q2)) ORDER by  month_etd, order_ship_nr ";
					}
					
				} else
				if($id_supchain_type == 113){
					$sql_freight = "select l.*, public.get_reference_nr_1(id_ord_schedule, 113) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 113) as shipment_number_2, 
					ord.Sup_reference_nr as order_number, l.ref_code_cus, l.ref_code_fa, l.ref_code_imp, l.ref_code_sup
						from v_logistics l, v_order ord, ord_order o
					where l.id_ord_schedule in ( select distinct id_ord_schedule from ord_ocean_schedule where supplier_contact_id=$id_company ) 
					and ord.id_ord_order=l.ord_order_id
					and o.id_ord_order=l.ord_order_id
					$condition $id_cond 
					order by week_etd ";
					
				} else
				if($id_supchain_type == 289){
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 289) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 289) as shipment_number_2, cus_incoterms_id,
					Fa_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
					from v_logistics  
					where ord_order_id in ( select distinct ord_order_id from ord_con_booking where fa_contact_id=$id_company ) 
					$condition $id_cond order by month_etd ";
					
				} else
				if($id_supchain_type == 288){
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 288) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 288) as shipment_number_2, cus_incoterms_id,
					Sup_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup
					from v_logistics 
					where id_ord_schedule in ( select distinct ord_schedule_id from v_con_booking where forwarder_company_id=$id_company ) 
					$condition $id_cond  order by week_etd ";
				
				} else
				if($id_supchain_type == 327){
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 327) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 327) as shipment_number_2, cus_incoterms_id,
					Sup_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup
					from v_logistics 
					where id_ord_schedule in ( select distinct ord_schedule_id from v_con_booking where qm_contact_id=$id_company and pipeline_sched_id in ( 296, 298 ) ) 
					and pol_id in ( select distinct id_townport from ord_towns_port where qm_org_contact_id=$id_company)
					$condition $id_cond ";
				
				} else {}
			}

			$rs_freight = pg_query($conn, $sql_freight);
		
			$list_freight = '';
	
			while ($row_freight = pg_fetch_assoc($rs_freight)) {
				
				$pod_name = preg_replace('/\s+/', '', $row_freight['o_pod_name']);
				$pol_name = preg_replace('/\s+/', '', $row_freight['pol_name']);
				$supplier_name = preg_replace('/\s+/', '', $row_freight['supplier_name']);
				$supplier_name = str_replace("'", '', $supplier_name);
				
				$reference_nr = preg_replace('/\s+/', '', $row_freight['fa_reference_nr']);
				$id_ord_schedule = preg_replace('/\s+/', '', $row_freight['id_ord_schedule']);
				$cus_incoterms_id = preg_replace('/\s+/', '', $row_freight['cus_incoterms_id']);
				
				$id_con_booking = preg_replace('/\s+/', '', $row_freight['id_con_booking']);
				$pol_id = preg_replace('/\s+/', '', $row_freight['pol_id']);
				$pod_id = preg_replace('/\s+/', '', $row_freight['pod_id']);
				
				$flag_book_add = preg_replace('/\s+/', '', $row_freight['flag_book_add']);
				$flag_onw = preg_replace('/\s+/', '', $row_freight['flag_onw']);
				$flag_onw_add = preg_replace('/\s+/', '', $row_freight['flag_onw_add']);
				
				$ord_fa_contact_id = preg_replace('/\s+/', '', $row_freight['ord_fa_contact_id']);
				$pipeline_sched_id = preg_replace('/\s+/', '', $row_freight['pipeline_sched_id']);
				
				
				if($row_freight['pipeline_sched_id']==296){
					$label='label-danger'; $style='';
				} else
				if($row_freight['pipeline_sched_id']==299){
					$label='label-primary'; $style='';
				} else
				if($row_freight['pipeline_sched_id']==298){
					$label='label-muted'; $style='';
				} else
				if($row_freight['pipeline_sched_id']==357){
					$label=''; $style='background:#000; color:#FFF';
				} else { $label=''; $style='';}
				
				$shipment_number = trim($row_freight['shipment_number_1']).' '.trim($row_freight['shipment_number_2']);
				
				$list_freight .= '<li><a href="javascript:ocean_containers_loading(\''. $id_ord_schedule .'\',\''. $reference_nr .'\',\''. $supplier_name .'\',\''. $pol_name .'\',\''. $pod_name .'\',\''. $cus_incoterms_id .'\',\''. $id_con_booking .'\',\''. $shipment_number .'\',\''. $pol_id .'\',\''. $pod_id .'\',\''. $doc_right .'\',\''. $flag_book_add .'\',\''. $flag_onw .'\',\''. $flag_onw_add .'\',\''. $ord_fa_contact_id .'\',\''. $pipeline_sched_id .'\');" class="reference_nr">
					'. $row_freight['shipment_number_1'] .'<br/>
					<span class="label '.$label.' pull-right" style="font-weight:normal; '.$style.'">'. $row_freight['pipeline_sched_name'] .'</span>
					<span style="color:#aaa; font-size:10px;">'. $row_freight['shipment_number_2'] .'</span><br/>
					<small style="color:#aaa; font-size:9px;">'.$row_freight['ref_code_cus'].'</small><br/>
					<small style="color:#aaa; font-size:9px;">'.$row_freight['ref_code_fa'].'</small><br/>
					<small style="color:#aaa; font-size:9px;">'.$row_freight['ref_code_imp'].'</small><br/>
					<small style="color:#aaa; font-size:9px;">'.$row_freight['ref_code_sup'].'</small>
				</a></li>';
			}

			$dom=$list_freight;
			
		break;
		
		
		case "logistiques_custom":
		
			$list_bl = "";
			$list_cont = "";
			
			$sql_bl="select b.bl_number, b.ord_schedule_id, b.booking_segment from ord_con_booking b";
			$result_bl = pg_query($conn, $sql_bl);
	
			$sql_cont="select l.container_nr, b.ord_schedule_id, b.booking_segment
				from ord_con_list l, ord_con_booking b
			where b.id_con_booking=l.con_booking_id";
			$result_cont = pg_query($conn, $sql_cont);
	
	
			$list_bl .= '<option value="">B/L Number.</option>';
			while($arr_bl = pg_fetch_assoc($result_bl)){
				$list_bl .= '<option value="'.$arr_bl['ord_schedule_id'].'">'.trim($arr_bl['bl_number']).'</option>';
			}
			
			$list_cont .= '<option value="">Container Number.</option>';
			while($arr_cont = pg_fetch_assoc($result_cont)){
				$list_cont .= '<option value="'.$arr_cont['ord_schedule_id'].'">'.trim($arr_cont['container_nr']).'</option>';
			}
			
			$dom=$list_bl.'##'.$list_cont;
			
		break;
		
		
		case "ocean_containers_loading":
			
			$doc_right = $_GET['doc_right']; 
			$shipDoc_create = $_GET['shipDoc_create']; 
		
			$supplier_name = $_GET['supplier_name'];
			$pod_name = $_GET['pod_name'];
			$pol_name = $_GET['pol_name'];
			
			$id_con_booking = $_GET['id_con_booking'];
			$ord_schedule_id = $_GET['ord_schedule_id'];
			$shipment_number = $_GET['shipment_number'];
			$ref_num = $_GET['ref_num'];
			$pol_id = $_GET['pol_id'];
			$pod_id = $_GET['pod_id'];
			$ord_fa_contact_id = $_GET['ord_fa_contact_id'];
			
			$card1 = 1; $card2 = 0; $card3 = 0; $card4 = 0; $card5 = 0;
			$flag_book_add = $_GET['flag_book_add'];
			if($flag_book_add == 1){ $card2 = 1; } 

			$flag_onw = $_GET['flag_onw'];
			if($flag_onw == 1){ $card3 = 1; }

			$flag_onw_add = $_GET['flag_onw_add'];
			if($flag_onw_add == 1){ $card4 = 1; }
			
			$traceability = $_GET['traceability_read'];
			if($traceability == 1){ $card5 = 1; }
			
			$pipeline_sched_id = $_GET['pipeline_sched_id'];
			if($pipeline_sched_id >= 298){ $card3 = 1; }
			
			// Shipping Documents
			$shipDoc_read = $_GET['shipDoc_read'];
			$shipDoc_update = $_GET['shipDoc_update'];
			if($shipDoc_read == 1){ $shipDoc_r=''; }else{ $shipDoc_r='hide'; }
			if($shipDoc_update == 1){ $shipDoc_u=''; }else{ $shipDoc_u='hide'; }
			
			// Lab Analysis
			$labAna_read = $_GET['labAna_read'];
			$labAna_create = $_GET['labAna_create'];
			$labAna_update = $_GET['labAna_update'];
			$labAna_delete = $_GET['labAna_delete'];
			if($labAna_read == 1){ $labAna_r=''; }else{ $labAna_r='hide'; }
			if($labAna_update == 1){ $labAna_u=''; }else{ $labAna_u='hide'; }
			if($labAna_delete == 1){ $labAna_d=''; }else{ $labAna_d='hide'; }
			if($labAna_create == 1){ $labAna_c=''; }else{ $labAna_c='hide'; }
			
			// Ocean rights
			$ocean_read = $_GET['ocean_read'];
			$ocean_update = $_GET['ocean_update'];
			if($ocean_read == 1){ $ocean_r=''; }else{ $ocean_r='hide'; }
			if($ocean_update == 1){ $ocean_u=''; }else{ $ocean_u='hide'; }
			
			// Ocean Container rights
			$oceanCont_read = $_GET['oceanCont_read'];
			$oceanCont_update = $_GET['oceanCont_update'];
			$oceanCont_create = $_GET['oceanCont_create'];
			if($oceanCont_read == 1){ $ocean_cr=''; }else{ $ocean_cr='hide'; }
			if($oceanCont_update == 1){ $ocean_cu=''; }else{ $ocean_cu='hide'; }
			if($oceanCont_create == 1){ $ocean_cc=''; }else{ $ocean_cc='hide'; }
			
			// Move Container rights
			$LogContMove = $_GET['LogContMove'];
			if($LogContMove == 1){ $move_cr=''; }else{ $move_cr='hide'; }
			
			
			if($card1 == 1){
				$sql_ocean = "Select * From v_con_booking where ord_schedule_id=$ord_schedule_id AND booking_segment=1";
		
				$rs_ocean = pg_query($conn, $sql_ocean);
				$row_ocean = pg_fetch_assoc($rs_ocean);
				
				$ord_order_id = $row_ocean['ord_order_id'];
				
				if($row_ocean){ $req='update'; } else { $req='add'; }
			
				// Freight agent list
				$sql_freight_agent = "select * from contact where id_supchain_type=289";
				$rs_freight_agent = pg_query($conn, $sql_freight_agent);
			
				$list_freight_agent = '<option value="">-- '.$lang['CONTRACT_SEL_FREIGHT_AGENT'].' --</option>';
			
				while ($row_freight_agent = pg_fetch_assoc($rs_freight_agent)) {
					if($req=='update'){
						if($row_freight_agent['id_contact'] == $row_ocean['fa_contact_id']){ $sel_freight_a="selected='selected'"; }
						else { $sel_freight_a=""; }
					} else { 
						if($row_freight_agent['id_contact'] == $ord_fa_contact_id){ $sel_freight_a="selected='selected'"; }
						else { $sel_freight_a=""; }
					}
					$list_freight_agent .= '<option value="'. $row_freight_agent['id_contact'] .'"'.$sel_freight_a.'>'. $row_freight_agent['name'] .'</option>';
				}
				
				if(($req=='update')AND($row_ocean['fa_contact_id']!="")){
					// Freight agent person list
					$sql_freight_person_agent = "select * from contact where id_primary_company=".$row_ocean['fa_contact_id']."";
					$rs_freight_person_agent = pg_query($conn, $sql_freight_person_agent);
				
					$list_freight_person_agent = '<option value="">-- '.$lang['LOG_SEL_AGENT'].' --</option>';
				
					while ($row_freight_person_agent = pg_fetch_assoc($rs_freight_person_agent)) {
						if($req=='update'){
							if($row_freight_person_agent['id_contact'] == $row_ocean['fa_person_id']){ $sel_freight_person_a="selected='selected'"; }
							else { $sel_freight_person_a=""; }
						} else { $sel_freight_person_a=""; }
						$list_freight_person_agent .= '<option value="'. $row_freight_person_agent['id_contact'] .'"'.$sel_freight_person_a.'>'. $row_freight_person_agent['name'] .'</option>';
					}
				}
				
				//Carrier name list
				$sql_carrier_name = "select * from contact where id_supchain_type=319";
				$rs_carrier_name = pg_query($conn, $sql_carrier_name);
			
				$list_carrier_name = '<option value="">-- '.$lang['LOG_SEL_CARRIER'].' --</option>';
			
				while ($row_carrier_name = pg_fetch_assoc($rs_carrier_name)) {
					if($req=='update'){
						if($row_carrier_name['id_contact'] == $row_ocean['carrier_company_id']){ $sel_sl_line="selected='selected'"; }
						else { $sel_sl_line=""; }
					} else { $sel_sl_line=""; }
					$list_carrier_name .= '<option value="'. $row_carrier_name['id_contact'] .'"'.$sel_sl_line.'>'. $row_carrier_name['name'] .'</option>';
				}
				
				if(($req=='update') AND ($row_ocean['carrier_company_id']!="")){
					// Carrier name agent list
					$sql_carrier_person_name = "select * from contact where id_primary_company=".$row_ocean['carrier_company_id']."";
					$rs_carrier_person_name = pg_query($conn, $sql_carrier_person_name);
				
					$list_carrier_person_name = '<option value="">-- '.$lang['LOG_SEL_AGENT'].' --</option>';
				
					while ($row_carrier_person_name = pg_fetch_assoc($rs_carrier_person_name)) {
						if($req=='update'){
							if($row_carrier_person_name['id_contact'] == $row_ocean['carrier_person_id']){ $sel_person_sl_line="selected='selected'"; }
							else { $sel_person_sl_line=""; }
						} else { $sel_person_sl_line=""; }
						$list_carrier_person_name .= '<option value="'. $row_carrier_person_name['id_contact'] .'"'.$sel_person_sl_line.'>'. $row_carrier_person_name['name'] .'</option>';
					}
				}
				
				//Forwarder name
				$sql_forwarder_name = "select * from contact where id_supchain_type=288";
				$rs_forwarder_name = pg_query($conn, $sql_forwarder_name);
			
				$list_forwarder_name = '<option value="">-- '.$lang['LOG_SEL_FORWARDER'].' --</option>';
			
				while ($row_forwarder_name = pg_fetch_assoc($rs_forwarder_name)) {
					if($req=='update'){
						if($row_forwarder_name['id_contact'] == $row_ocean['log_contact_id']){ $sel_log_contact="selected='selected'"; }
						else { $sel_log_contact=""; }
					} else { $sel_log_contact=""; }
					
					$list_forwarder_name .= '<option value="'. $row_forwarder_name['id_contact'] .'" '.$sel_log_contact.'>'. $row_forwarder_name['name'] .'</option>';
				}
				
				if(($req=='update')AND($row_ocean['forwarder_company_id']!="")){
					// Forwarder name agent list
					$sql_forwarder_person_name = "select * from contact where id_primary_company=".$row_ocean['forwarder_company_id']."";
					$rs_forwarder_person_name = pg_query($conn, $sql_forwarder_person_name);
				
					$list_forwarder_person_name = '<option value="">-- '.$lang['LOG_SEL_AGENT'].' --</option>';
				
					while ($row_forwarder_person_name = pg_fetch_assoc($rs_forwarder_person_name)) {
						if($req=='update'){
							if($row_forwarder_person_name['id_contact'] == $row_ocean['forwarder_person_id']){ $sel_person_sl_line="selected='selected'"; }
							else { $sel_person_sl_line=""; }
						} else { $sel_person_sl_line=""; }
						
						$list_forwarder_person_name .= '<option value="'. $row_forwarder_person_name['id_contact'] .'" '.$sel_person_sl_line.'>'. $row_forwarder_person_name['name'] .'</option>';
					}
				}
				
				//Transhipment Port
				$sql_transhipment = "Select * from v_port where port_type_id=276";
				$rs_transhipment = pg_query($conn, $sql_transhipment);
			
				$list_transhipment = '<option value="">-- '.$lang['LOG_SEL_TRANS'].' --</option>';
				$list_transhipment2 = '<option value="">-- '.$lang['LOG_SEL_TRANS'].' --</option>';
			
				$transhipment_name = "";
				while ($row_transhipment = pg_fetch_assoc($rs_transhipment)) {
					if($req=='update'){
						if($row_transhipment['id_townport'] == $row_ocean['trans_port_id']){ $sel_trans_port="selected='selected'"; $transhipment_name = $row_transhipment['portname']; } else { $sel_trans_port=""; }
						if($row_transhipment['id_townport'] == $row_ocean['trans_port_id1']){ $sel_trans_port2="selected='selected'"; } else { $sel_trans_port2=""; }
					} else { $sel_trans_port=""; }
					$list_transhipment .= '<option value="'. $row_transhipment['id_townport'] .'"'.$sel_trans_port.'>'. $row_transhipment['portname'] .'</option>';
					$list_transhipment2 .= '<option value="'. $row_transhipment['id_townport'] .'"'.$sel_trans_port2.'>'. $row_transhipment['portname'] .'</option>';
				}
				
				$sql_container = "Select * From v_booking_conlist where ord_schedule_id= $ord_schedule_id  Order by cus_con_ref1";
				$rs_container = pg_query($conn, $sql_container);
				
				$list_container = '';
				
				while ($row_container = pg_fetch_assoc($rs_container)) {
					if($row_container['task_done']==2){
						$loading=' - <span class="text-navy">'.$lang['LOG_LOADING'].'</span>';
					} else 
					if($row_container['task_done']==1){
						$loading=' - <span class="text-success">'.$lang['LOG_LOADING_COMPLET'].'</span>';
					} else {
						$loading='';
					}
				
					$list_container .= '<tr><td>  
							<a href="javascript:loadingForm2(\''. $row_container['id_con_list'] .'\',\''. $row_container['id_ord_loading_item'] .'\');" class="reference_nr">
								'. $row_container['cus_con_ref1'] . '
							</a>
						</td>
						
						<td> '.$row_container['container_nr']. $loading .'</td>  
						<td> T: '. $row_container['tare'] . ' </td>  
						<td> W: '. $row_container['vgm_weight'] . ' </td>    
						<td> '. $row_container['date_loaded'] . ' </td>  
						
						<td style="width:80px">
							<a href="#" onclick="loadingForm2(\''. $row_container['id_con_list'] .'\',\''. $row_container['id_ord_loading_item'] .'\');"><i class="fa fa-eye"></i></a>
							<span class="containers_action_tb">
								&nbsp;<a href="#" class="'.$ocean_cc.' editContainer" onclick="edit_container(\''.$row_container['id_con_list'].'\',\''. $row_container['container_nr'] .'\',\''. $ord_schedule_id .'\',\''. $row_container['tare'] .'\',\''. $row_container['vgm_weight'] .'\',\''. $row_container['seal_1_nr'] .'\',\''. $row_container['seal_2_nr'] .'\',\''. $row_container['seal_3_nr'] .'\',\''. $row_container['seal_4_nr'] .'\',\''. $row_container['seal_5_nr'] .'\',\''. $row_container['date_loaded'] .'\');"><i class="fa fa-pen-square"></i></a>
								&nbsp;<a href="#" class="'.$ocean_cc.'" onclick="contdeleteConfirm(\''. $row_container['id_con_list'] .'\',\''. $row_container['container_nr'] .'\',\''. $ord_schedule_id .'\',\''. $row_container['tare'] .'\',\''. $row_container['vgm_weight'] .'\',\''. $row_container['date_loaded'] .'\');"><i class="fa fa-trash"></i></a>
								&nbsp;<a href="#" class="'.$move_cr.'" onclick="moveContainerModal(\''.$row_container['id_con_list'].'\',\''. $ord_order_id .'\',\''. $ord_schedule_id .'\');"><i class="fa fa-long-arrow-right"></i></a>
							</span>
						</td>
					</tr>';
				}
				
				
				$sql_modby = "Select modified_contact, modified_date From v_order_schedule where id_ord_schedule= $ord_schedule_id";
				$rs_modby = pg_query($conn, $sql_modby);
				
				if($rs_modby){
					$row_modby = pg_fetch_assoc($rs_modby);
					
					$modify_by = '<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label> '. $row_modby['modified_contact'] .' <br/>
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].': </label> '. $row_modby['modified_date'] .'
					</div>';
					
				} else {
					$modify_by = '';
				}

		
				if($req=='update'){
					
					$fa_note = $row_ocean['fa_note']; 
					$sl_note = $row_ocean['sl_note']; 
					$log_note = $row_ocean['log_note']; 
					$fa_reference_nr = $row_ocean['fa_reference_nr']; 
					$booking_nr = $row_ocean['booking_nr']; 
					$pol = $row_ocean['pol']; 
					$cutoff_date_time = explode(" ", $row_ocean['cutoff_date']);
					$cutoff_date = $cutoff_date_time[0];
					$cutoff_time = $cutoff_date_time[1];
					$vgm_cutoff_date_time = explode(" ", $row_ocean['vgm_cutoff']);
					$vgm_cutoff_date = $vgm_cutoff_date_time[0];
					$vgm_cutoff_time = $vgm_cutoff_date_time[1];
					$etd_date_time = explode(" ", $row_ocean['etd']);
					$etd_date = $etd_date_time[0];
					$etd_time = $etd_date_time[1];
					$vessel_feeder_name = $row_ocean['vessel_feeder_name'];
					$vessel_feeder_mmsi_id = $row_ocean['vessel_feeder_mmsi_id'];
					$tport_eta_date_time = explode(" ", $row_ocean['tport_eta']);
					$tport_eta_date = $tport_eta_date_time[0];
					$tport_eta_time = $tport_eta_date_time[1];
					$tport_etd_date_time = explode(" ", $row_ocean['tport_etd']);
					$tport_etd_date = $tport_etd_date_time[0];
					$tport_etd_time = $tport_etd_date_time[1];
					$vessel_name = strtoupper($row_ocean['vessel_name']);
					$vessel_mmsi_id = $row_ocean['vessel_mmsi_id'];
					$vessel_imo_id = $row_ocean['vessel_imo_id'];
					$voyage_nr = $row_ocean['voyage_nr'];
					$pod = $row_ocean['pod_id'];
					$eta_date_time = explode(" ", $row_ocean['eta']);
					$eta_date = $eta_date_time[0];
					$eta_time = $eta_date_time[1];
					$booking_type_id = $row_ocean['booking_type_id'];
					
					$trans_port_name = $row_ocean['trans_port_name'];
					
					$hide_fa_person = '';
					$hide_carrier_person = '';
					$hide_forwarder_person = '';
					
					
					// Container 
					$iso_available = $row_ocean['iso_available'];
					if($iso_available == 1){ 
						$conPosiIn = '';
						$iso_yes='checked'; $iso_no='';
					} else { 
						$conPosiIn = 'in'; 
						$iso_yes=''; $iso_no='checked';
					}
					
					$iso_positioning = $row_ocean['iso_positioning'];
					$iso_pol = $row_ocean['iso_pol'];
					$iso_pod = $row_ocean['pol'];
					$iso_etd = $row_ocean['iso_etd'];
					$iso_eta = $row_ocean['iso_eta'];
					$iso_date_available = $row_ocean['iso_date_available'];
					
					
					$iso_booking = $row_ocean['iso_booking'];
					$iso_vessel_name = $row_ocean['iso_vessel_name'];
					$iso_vessel_mmsi = $row_ocean['iso_vessel_mmsi'];
					$package_type_id = $row_ocean['package_type_id'];
					$con_load_date_from = $row_ocean['con_load_date_from'];
					$con_load_date_to = $row_ocean['con_load_date_to'];
					$qm_contact_name = $row_ocean['qm_contact_name'];
					$qm_contact_id = $row_ocean['qm_contact_id'];
					$sync_agent = $row_ocean['sync_agent'];
					
					$booking_pod_id = $row_ocean['bpod_id'];
					$booking_pol_id = $row_ocean['pol_id'];
					
					$modified_by_name = $row_ocean['modified_by_name'];
					$modified_date = $row_ocean['modified_date'];
					
					$id_con_booking=$row_ocean['id_con_booking'];
					$edit_btn='<a href="#" style="color:#FFF;" onclick="edit_ocean_loading(\''.$row_ocean['id_con_booking'].'\',\''.$ref_num.'\',\''.$ord_schedule_id.'\',\'edit\');"> <i class="fa fa-edit"></i> </a>';
					
					$tport_eta_actual=$row_ocean['tport_eta_actual'];
					$pod_eta_actual=$row_ocean['pod_eta_actual'];
					$loading_manager_id=$row_ocean['load_manager_id'];
					$ids_multiple_agent=$row_ocean['ids_multiple_agent'];
					$pipeline_sched_id=$row_ocean['pipeline_sched_id'];
					
					$bl_number=$row_ocean['bl_number'];
					$pol_etd_actual_datetime=explode(' ', $row_ocean['pol_etd_actual']); 
					$pol_etd_actual=$pol_etd_actual_datetime[0];
					$loading_address_id=$row_ocean['loading_address_id'];
					$vessel_feeder2=$row_ocean['vessel_feeder2'];
					$vessel_feeder_name1=$row_ocean['vessel_feeder_name1'];
					$vessel_feeder_mmsi_id1=$row_ocean['vessel_feeder_mmsi_id1'];
					$tport_etd1_date_time = explode(" ", $row_ocean['tport_etd_1']);
					$tport_etd1_date = $tport_etd1_date_time[0];
					$tport_etd1_time = $tport_etd1_date_time[1];
					$tport_eta1_date_time = explode(" ", $row_ocean['tport_eta1']);
					$tport_eta1_date = $tport_eta1_date_time[0];
					$tport_eta1_time = $tport_eta1_date_time[1];
					
					$tport_eta_actual1=$row_ocean['tport_eta_actual1'];
					$ord_order_id=$row_ocean['ord_order_id'];
					$supplier_contact_id=$row_ocean['supplier_contact_id'];
					$loading_certificate_requested=$row_ocean['loading_certificate_requested'];
					
				} else { 
				
					$fa_note = "";  
					$sl_note = "";  
					$log_note = "";  
					$booking_nr = "";  
					$pol = $pol_name;  
					$cutoff_date = "";  
					$cutoff_time = "08:00"; 
					$vgm_cutoff_date = "";  
					$vgm_cutoff_time = "08:00";  
					$etd_date = "";  
					$etd_time = "08:00";  
					$vessel_feeder_name = "";  
					$vessel_feeder_mmsi_id = "";  
					$tport_eta_date = "";  
					$tport_eta_time = "08:00"; 
					$tport_etd_date = "";  
					$tport_etd_time = "08:00";  
					$vessel_name = "";  
					$vessel_mmsi_id = "";  
					$vessel_imo_id = "";  
					$voyage_nr = "";  
					$pod = $pod_name;  
					$eta_date = "";
					$eta_time = "08:00";
					$fa_reference_nr = $ref_num;
					$booking_type_id = 1;
					
					$trans_port_name = '';
					
					$hide_fa_person = 'hide';
					$hide_carrier_person = 'hide';
					$hide_forwarder_person = 'hide';
					
					
					// Container 
					$conPosiIn = '';
					$iso_positioning = "";
					$iso_pol = "";
					$iso_pod = "";
					$iso_etd = "";
					$iso_eta = "";
					$iso_date_available = "";
					$iso_yes=''; $iso_no='checked';
					$iso_booking = "";
					$iso_vessel_name = "";
					$iso_vessel_mmsi = "";
					$package_type_id = "";
					$con_load_date_from = "";
					$con_load_date_to = "";
					$qm_contact_name = "";
					$qm_contact_id = "";
					$sync_agent = "";
		
					$booking_pod_id = $pod_id;
					$booking_pol_id = $pol_id;
					
					$modified_by_name = "";
					$modified_date = "";
					
					$id_con_booking='';
					$edit_btn='';
					
					$tport_eta_actual='';
					$pod_eta_actual='';
					$loading_manager_id='';
					$ids_multiple_agent='';
					$pipeline_sched_id='';
					
					$bl_number='';
					$pol_etd_actual='';
					$loading_address_id='';
					$vessel_feeder2=0;
					$vessel_feeder_name1='';
					$vessel_feeder_mmsi_id1='';
					$tport_etd1_date='';
					$tport_etd1_time='';
					$tport_eta1_date='';
					$tport_eta1_time='';
					$tport_eta_actual1='';
					$ord_order_id='';
					$supplier_contact_id='';
					$loading_certificate_requested='';
				}
				
				if($loading_certificate_requested==''){
					$btn_cert_rqst='disabled';
				} else { $btn_cert_rqst=''; }
				
		
				if(($modified_by_name != "")&&($modified_date = "")){
					$container_modify_by = '<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label> '. $modified_by_name .' <br/>
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].': </label> '. $modified_date .'
					</div>';
				} else {
					$container_modify_by = "";
				}
		
		
				$sql_o_pod = "Select * from v_port where port_type_id=273";
				$rs_o_pod = pg_query($conn, $sql_o_pod);
				
				$list_o_pod = '<option value="">-- '.$lang['LOG_SEL_PORT'].' --</option>';
				
				while ($row_o_pod = pg_fetch_assoc($rs_o_pod)) {
					if($req=='update'){
						if($row_o_pod['portname'] == $pod){ $sel_o_pod="selected='selected'"; }
						else { $sel_o_pod=""; }
					} else { $sel_o_pod=""; }  
					$list_o_pod .= '<option value="'. $row_o_pod['portname'] .'"'.$sel_o_pod.'>'. $row_o_pod['portname'] .'</option>';
				}
			
				
				if($row_ocean['trans_port_id']!=""){ $hide_transBox = ''; } else { $hide_transBox = 'hide'; }
				if($row_ocean['vessel_feeder2']==1){ $hide_transBox2 = ''; $feeder2_btn = 'hide'; } else { $hide_transBox2 = 'hide'; $feeder2_btn = ''; }
				if($package_type_id == 269) { $hide_contPosiBox=''; } else { $hide_contPosiBox='hide'; }
				
				if($row_ocean['confirmation_document']!=""){
					$hide_view_btn = '';
				} else {
					$hide_view_btn = '<a href="#" class="pull-right" onclick="viewBookingDoc(\''.$row_ocean['confirmation_document'].'\',\'0\');"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;View</a>';
				}

				if($booking_type_id == 1){ $bD='selected'; $bId=''; }
				else if($booking_type_id == 0){ $bD=''; $bId='selected'; }
				else { $bD='selected'; $bId=''; }
				
				$booking_type = '<option value="1" '.$bD.'>'.$lang['LOG_OPT_DIRECT'].'</option>
					<option value="0" '.$bId.'>'.$lang['LOG_OPT_VIA_TRANS'].'</option>';
				
				$mail = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;" onclick="eMailForm(\''.$ord_schedule_id.'\',\'logistics\',\'1\');"><i class="fa fa-envelope"></i></a>';
				
				if($doc_right == 1){
					$doc = '<a href="#" class="pull-right" style="margin-left:10px;" onclick="showDocList(\''.$ord_order_id.'\',\''.$ord_schedule_id.'\',\'logistics\',\'\');"><i class="fa fa-file-text"></i></a>';
					
					$doc_ocean1 = $mail.$doc;
					$doc_ocean2 = $mail.$doc;
					$doc_ocean3 = $mail.$doc;
					
				} else {
					$doc_ocean1 = '';
					$doc_ocean2 = '';
					$doc_ocean3 = '';
				}
				
				
				// Shipping Documents
				// Shipping Documents
				$sql_shipDoc = "Select ord_con_loading_header.ord_con_list_id, 
				ord_con_loading_header.lab_awb_no, 
				ord_con_loading_header.lab_cus_awb_date, 
				ord_con_loading_header.cust_awb_no, 
				ord_con_loading_header.cus_awb_date, 
				ord_con_loading_header.fa_awb_no,
				ord_con_loading_header.fa_awb_date, 
				to_char(ord_con_loading_header.total_vgm_weight,'999G999D999') total_vgm_weight,
				ord_con_loading_header.loading_note,
				ord_con_loading_header.end_state,
				ord_con_loading_header.no_con_shipped,
				ord_con_loading_header.ids_person_id,
				get_contact_name(ord_con_loading_header.ids_person_id) ids_person_name,
				ord_con_loading_header.container_nr,
				ord_con_loading_header.loadin_diff,
				ord_con_loading_header.lab_contact_id
				from ord_con_loading_header where ord_schedule_id=$ord_schedule_id";

				$rs_shipDoc = pg_query($conn, $sql_shipDoc);
				$row_shipDoc = pg_fetch_assoc($rs_shipDoc);
				
				if($row_shipDoc){ $req_doc='update'; } else { $req_doc='add'; }
				
				if($req_doc=='update'){
					$lab_awb_no = $row_shipDoc['lab_awb_no'];
					$lab_cus_awb_date = $row_shipDoc['lab_cus_awb_date'];
					$cust_awb_no = $row_shipDoc['cust_awb_no'];
					$cus_awb_date = $row_shipDoc['cus_awb_date'];
					$fa_awb_no = $row_shipDoc['fa_awb_no'];
					$fa_awb_date = $row_shipDoc['fa_awb_date'];
					$loading_note = $row_shipDoc['loading_note'];
					$end_state = $row_shipDoc['end_state'];
					$no_con_shipped = $row_shipDoc['no_con_shipped'];
					$container_nr = $row_shipDoc['container_nr'];
					$loading_diff = $row_shipDoc['loadin_diff'];
					$ids_person_id = $row_shipDoc['ids_person_id'];
					$ids_person_name = $row_shipDoc['ids_person_name'];
					$total_vgm_weight = $row_shipDoc['total_vgm_weight'];
					$lab_contact_id = $row_shipDoc['lab_contact_id'];
					
				} else {
					$lab_awb_no = "";
					$lab_cus_awb_date = gmdate("Y/m/d");
					$cust_awb_no = "";
					$cus_awb_date = "";
					$fa_awb_no = "";
					$fa_awb_date = gmdate("Y/m/d");
					$loading_note = "";
					$end_state = "";
					$no_con_shipped = "";
					$container_nr = "";
					$loading_diff = "";
					$ids_person_id = "";
					$ids_person_name = "";
					$total_vgm_weight = "";
					$lab_contact_id = "";
				}
				
				$sync1="";
				$sync2="";
				$loading_agent_list1="";
				$loading_agent_list2="";
			
				if($qm_contact_id!=""){  
				
					if($sync_agent!=""){ 
						$sync = explode(',', $sync_agent); 
						$sync1=$sync[0];
						$sync2=$sync[1];
					}
					
					$sql_lagent="select id_contact, name from contact where id_primary_company = $qm_contact_id";
					$rs_lagent = pg_query($conn, $sql_lagent);
					
					$loading_agent_list1='<option value="">-- '.$lang['LOG_SEL_AGENT'].' --</option>';
					while($row_lagent = pg_fetch_assoc($rs_lagent)){
						if($sync1==$row_lagent['id_contact']){ $lA1="selected='selected'"; } else { $lA1=""; }
						$loading_agent_list1.='<option value="'.$row_lagent['id_contact'].'" '.$lA1.'>'.$row_lagent['name'].'</option>';
					}	
					
					$sql_lagent2="select id_contact, name from contact where id_primary_company = $qm_contact_id";
					$rs_lagent2 = pg_query($conn, $sql_lagent2);
					
					$loading_agent_list2='<option value="">-- '.$lang['LOG_SEL_AGENT'].' --</option>';
					while($row_lagent2 = pg_fetch_assoc($rs_lagent2)){
						if($sync2==$row_lagent2['id_contact']){ $lA2="selected='selected'"; } else { $lA2=""; }
						$loading_agent_list2.='<option value="'.$row_lagent2['id_contact'].'" '.$lA2.'>'.$row_lagent2['name'].'</option>';
					}	
				}
				
				
				if($loading_diff<0){ $add_booking_btn=''; }
				else { $add_booking_btn='hide'; }
				
				if($end_state==FALSE){ $sel_loading_complete='<option value="0" selected>---</option><option value="1">Complete</option>'; } 
				else { $sel_loading_complete='<option value="0">---</option><option value="1" selected>Complete</option>'; }
				
				$loading_manager='';
				if(!empty($row_ocean['supplier_contact_id'])){
					$supplier_contact_id = $row_ocean['supplier_contact_id'];
					
					$sql_lm="select id_contact, name from contact where id_primary_company=$supplier_contact_id and id_type=9";
					$rs_lm = pg_query($conn, $sql_lm);
					while($row_lm = pg_fetch_assoc($rs_lm)){
						if($loading_manager_id == $row_lm['id_contact']){
							$sel_LM="selected='selected'";
						} else { $sel_LM=""; }
						$loading_manager.='<option value="'.$row_lm['id_contact'].'" '.$sel_LM.'>'.$row_lm['name'].'</option>';
					}
				}
				
				if($ids_multiple_agent==1){
					$ids_agent_yes='checked'; $ids_agent_no='';
					$l_agent_2='';
				} else {
					$ids_agent_no='checked'; $ids_agent_yes='';
					$l_agent_2='hide';
				}
				
				if($shipDoc_create==1){
					if($pipeline_sched_id>296){ $oceanPKList_cr = ''; } else { $oceanPKList_cr = 'hide'; }
				} else { $oceanPKList_cr = 'hide'; }
				
				
				
				$lab_contact_list="";
				$sql_lab="select id_contact, name from contact where id_supchain_type=379";
				$rs_lab = pg_query($conn, $sql_lab);
				while($row_lab = pg_fetch_assoc($rs_lab)){
					if($lab_contact_id == $row_lab['id_contact']){
						$sel_LAB="selected='selected'";
					} else { $sel_LAB=""; }
					$lab_contact_list.='<option value="'.$row_lab['id_contact'].'" '.$sel_LAB.'>'.$row_lab['name'].'</option>';
				}
				
				
				$list_loading_place="<option value=''>-- ".$lang['LOG_SEL_LOADING_PLACE']." --</option>";
				$sql_loading_place="select ca.id_conadr ,ca.company_name, ca.id_town, ca.coordx, ca.coordy, ca.id_contact, t.name_town
				from contact_addr ca, towns t
				where ca.id_town=t.gid_town
				and ca.id_contact=$supplier_contact_id
				and ca.address_type_id=417";
				$rs_loading_place = pg_query($conn, $sql_loading_place);
				while($row_loading_place = pg_fetch_assoc($rs_loading_place)){
					if($loading_address_id == $row_loading_place['id_conadr']){
						$sel_LP="selected='selected'";
					} else { $sel_LP=""; }
					$list_loading_place.='<option value="'.$row_loading_place['id_conadr'].'" '.$sel_LP.'>'.$row_loading_place['company_name'].'</option>';
				}
				
				
				$sql_labParam="SELECT * FROM ord_prod_params ORDER BY param_name DESC";
				$rs_labParam = pg_query($conn, $sql_labParam);
				$lab_param_list="";
				while($row_labParam = pg_fetch_assoc($rs_labParam)){
					$lab_param_list.='<option value="'.$row_labParam['id_prod_params'].'">'.$row_labParam['param_name'].' ('.$row_labParam['param_unit'].')</option>';
				}
				
				
				$sql_prodAna = "SELECT * FROM ord_prod_analysis WHERE ord_schedule_id=$ord_schedule_id AND con_booking_id=$id_con_booking";
				$rs_prodAna = pg_query($conn, $sql_prodAna);
				$row_prodAna = pg_fetch_assoc($rs_prodAna);
				
				$id_ord_prod_an = $row_prodAna['id_ord_prod_an'];
				$lab_product_id = $row_prodAna['product_id'];
				$lab_supplier_id = $row_prodAna['supplier_id'];
				$lab_ord_order_id = $row_prodAna['ord_order_id'];
			
				$labAnalysis_table="";
				if($id_ord_prod_an!=""){
					$sql_savedlabParam="SELECT a.id_ord_prod_an,
						a.ord_order_id,
						a.ord_schedule_id,
						a.con_booking_id,
						a.date_analysis,
						i.id_analysis_item,
						i.prod_analysis_id,
						i.id_parameter,
						p.param_name,
						p.param_method,
						p.param_unit,
						i.result
					   FROM ord_prod_analysis a,
						ord_prod_anitem i,
						ord_prod_params p
					  WHERE i.prod_analysis_id = a.id_ord_prod_an 
					  AND p.id_prod_params = i.id_parameter
					  AND i.prod_analysis_id = $id_ord_prod_an
					ORDER BY param_name ASC";
					$rs_savedlabParam = pg_query($conn, $sql_savedlabParam);
					while($row_savedlabParam = pg_fetch_assoc($rs_savedlabParam)){
						$labAnalysis_table.='<tr>
							<td>'.$row_savedlabParam['param_name'].'</td>  
							<td>'.$row_savedlabParam['param_unit'].'</td>
							<td>'.$row_savedlabParam['result'].'</td>
							<td class="text-center">
								<a href="#" data-toggle="modal" class="labTble hide" onclick="editLabAna('. $row_savedlabParam['id_analysis_item'] .');" data-target="#modalLabAnalysis"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
								<a href="javascript:deleteLabAna('. $row_savedlabParam['id_analysis_item'] .','.$ord_schedule_id.','.$id_con_booking.');" class="labTble hide '.$labAna_d.'" onclick="return confirm(\'Are you sure you want to delete this lab:'. $row_savedlabParam['param_name'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
								<span class="labTble_no">--</span>
							</td>
						</tr>';
					}
				}
				
				
				$ocean = '<div class="collapse-group">
					<div class="panel panel-primary '.$ocean_r.'" id="ocean_booking">
						<div class="panel-heading" role="tab" id="headingBooking">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseBooking" aria-expanded="true" aria-controls="collapseBooking" id="bookingCard1" class="trigger collapsed text-uppercase"> '.$lang['LOG_BOOKING'].' '.$shipment_number.' </a>
								
								'.$doc_ocean1.'  
								<span class="pull-right '.$ocean_u.'" id="EditOcenLoadingBtnID">'.$edit_btn.'</span>
							</h4>
						</div>
				
						<div id="collapseBooking" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingBooking">
							<div class="panel-body">
								<div class="tabs-container" id="bookingTabID">
									<div class="col-md-6">
										<div class="form-group"><label>'.$lang['LOG_FREIGHT_AGT'].' <span style="color:red;">*</span> </label>
											<select class="form-control" name="" onchange="getFreightAgent(this.value);" id="freight_agent_list">
												'.$list_freight_agent.'
											</select>
										</div>
										
										<input id="booking_pod_id" value="'.$booking_pod_id.'" type="hidden" />
										<input id="booking_pol_id" value="'.$booking_pol_id.'" type="hidden" />
										
										<div class="form-group '.$hide_fa_person.'" id="fa_person_box"><label> '.$lang['LOG_AGT'].' </label>
											<select class="form-control" name="" id="fa_contact_person_id">
												'.$list_freight_person_agent.'
											</select>
										</div>

										<div class="form-group"><label>'.$lang['LOG_FREIGHT_AGT_REF_NUMB'].' <span style="color:red;">*</span> </label>
											<input value="'.$fa_reference_nr.'" type="hidden" id="fa_reference_nr" class="form-control" />
											<br/>'.$fa_reference_nr.'
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_NOTES'].' </label>
											<textarea id="agent_note" style="height:65px;" class="form-control" >'.$fa_note.'</textarea>
										</div>
									
										<div class="form-group">
											<label>'.$lang['LOG_CARRIER_NAME'].' <span style="color:red;">*</span> </label>
											<div class="input-group">
												<select class="form-control" name="" onchange="getCarrierAgent(this.value);" id="carrier_name_list">
													'.$list_carrier_name.'
												</select>
												<span class="input-group-btn"> 
													<input type="button" onclick="newCarrier();" data-toggle="tooltip" data-placement="top" title="New carrier" class="btn btn-primary" value="+" />
												</span>
											</div>
										</div>
										
										<div class="form-group '.$hide_carrier_person.'" id="sl_line_box"><label> '.$lang['LOG_AGT'].' </label>
											<select class="form-control" name="" id="sl_contact_name">
												'.$list_carrier_person_name.'
											</select>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_NOTES'].' </label>
											<textarea id="carrier_note" style="height:65px;" class="form-control" >'.$sl_note.'</textarea>
										</div>
								
										<div class="form-group">
											<label>'.$lang['LOG_FORWARDER_NAME'].' </label>
											<div class="input-group">
												<select class="form-control" name="" onchange="getForwarderAgent(this.value);" id="forwarder_name_list">
													'.$list_forwarder_name.'
												</select>
												<span class="input-group-btn"> 
													<input type="button" onclick="newForwarder();" data-toggle="tooltip" data-placement="top" title="New forwarder" class="btn btn-primary" value="+" />
												</span>
											</div>
										</div>
								
										<div class="form-group '.$hide_forwarder_person.'" id="log_contact_box"><label> '.$lang['LOG_AGT'].' </label>
											<select class="form-control" name="" id="log_contact_name">
												'.$list_forwarder_person_name.'
											</select>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_NOTES'].' </label>
											<textarea id="forwarder_note" style="height:65px;" class="form-control" >'.$log_note.'</textarea>
										</div>
									
										<div class="form-group"><label>'.$lang['LOG_CHARGER'].' </label><br/>
											'.$supplier_name.'
										</div>
										
										'.$modify_by.'
									</div>
									
									<div class="col-md-6">
										<div class="form-group"><label>'.$lang['LOG_BOOKING_TYPE'].' <span style="color:red;">*</span> </label>
											<select id="booking_type_id" class="form-control" onchange="bTypeChoice(this.value);" required>
												<option>--'.$lang['LOG_SEL_TYPE'].'--</option>
												'.$booking_type.'
											</select>
										</div>
									
										<div class="form-group"><label>'.$lang['LOG_BOOKING_NUMB'].' <span style="color:red;">*</span> </label>
											<input value="'.$booking_nr.'" type="text" id="booking_nr" class="form-control" />
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_PORT_OF_LOADING'].' <span style="color:red;">*</span> </label>
											<input value="'.$pol.'" type="text" id="pol" class="form-control" />
										</div>
										
										<div class="form-group"><label>'.$lang['LOG_CLOSING'].' <span style="color:red;">*</span> </label>
											<div class="row">
												<div class="col-sm-6">
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control edit_delivery_date" value="'.$cutoff_date.'" id="cutoff_date" required>
													</div>
												</div>
												
												<div class="col-sm-6">
													<div class="input-group clockpicker" data-autoclose="true">
														<input type="text" class="form-control" id="cutoff_time" value="'.$cutoff_time.'" required>
														<span class="input-group-addon">
															<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group"><label>'.$lang['LOG_VGM_CUT_OFF'].' <span style="color:red;">*</span> </label>
											<div class="row">
												<div class="col-sm-6">
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" value="'.$vgm_cutoff_date.'" class="form-control edit_delivery_date" id="vgm_cutoff">
													</div>
												</div>
												
												<div class="col-sm-6">
													<div class="input-group clockpicker" data-autoclose="true">
														<input type="text" class="form-control" id="vgm_cutoff_time" value="'.$vgm_cutoff_time.'" >
														<span class="input-group-addon">
															<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
										
										
										<div style="margin-bottom:10px;">
											<h3 class="text-center">'.$lang['TC_OCEAN_FEEDER_1_TITLE'].'</h3>
											<div style="padding:10px; background:#e4e4e4;">
												<div class="form-group"><label>'.$lang['LOG_TRANSSHIPMENT'].' </label>
													<select class="form-control" name="" id="trans_port_id" onchange="showTranshipment(this.value);" disabled>
														'.$list_transhipment.'
													</select>
												</div>
												
												<div id="transBox" class="'.$hide_transBox.'">
													<div class="form-group">
														<label>'.$lang['LOG_FEEDER_VESSEL'].' </label>
														<div class="input-group">
															<input type="text" id="vessel_feeder_name" value="'.$vessel_feeder_name.'" class="form-control" />
															<span class="input-group-btn"> 
																<input type="button" onclick="checkMMSI(\'feeder\');" data-toggle="tooltip" data-placement="top" title="Get MMSI" class="btn btn-primary" value="?" />
															</span>
														</div>
													</div>
													
													<div class="form-group"><label>'.$lang['LOG_FEEDER_MMSI'].' </label>
														<input type="number" min="0" id="vessel_feeder_mmsi_id" value="'.$vessel_feeder_mmsi_id.'" class="form-control" />
													</div>
													
													<div class="form-group"><label>'.$lang['CONTRACT_ETD'].' </label>
														<div class="row">
															<div class="col-sm-6">
																<div class="input-group date">
																	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																	<input type="text" class="form-control edit_delivery_date" value="'.$tport_etd_date.'" id="tport_etd">
																</div>
															</div>
															
															<div class="col-sm-6">
																<div class="input-group clockpicker" data-autoclose="true">
																	<input type="text" class="form-control" id="tport_etd_time" value="'.$tport_etd_time.'" >
																	<span class="input-group-addon">
																		<span class="fa fa-clock-o"></span>
																	</span>
																</div>
															</div>
														</div>
													</div>
													
													<div class="form-group"><label>'.$lang['CONTRACT_ETA'].' </label>
														<div class="row">
															<div class="col-sm-6">
																<div class="input-group date">
																	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																	<input type="text" class="form-control edit_delivery_date" value="'.$tport_eta_date.'" id="tport_eta">
																</div>
															</div>
															
															<div class="col-sm-6">
																<div class="input-group clockpicker" data-autoclose="true">
																	<input type="text" class="form-control" id="tport_eta_time" value="'.$tport_eta_time.'" >
																	<span class="input-group-addon">
																		<span class="fa fa-clock-o"></span>
																	</span>
																</div>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label>'.$lang['LOG_ACTUAL_ETA'].'</label>
														'.$tport_eta_actual.'
													</div>
												</div>
											</div>
											
											<div class="form-group '.$feeder2_btn.'" id="feeder2_addbtn">
												<label>'.$lang['LOG_FEEDER_2BTN'].'</label><br/>
												<input type="button" onclick="add_feeder2();" class="btn btn-primary" value="'.$lang['LOG_FEEDER_2BTN'].'" />
											</div>
										</div>
										
										<div style="margin-bottom:10px;" id="transBox2" class="'.$hide_transBox2.'">
											<h3 class="text-center">'.$lang['TC_OCEAN_FEEDER_2_TITLE'].'</h3>
											<div style="background:#e4e4e4; padding:10px;">
												<div class="form-group">
													<label>'.$lang['LOG_FEEDER_2_POL'].'</label>
													<div id="feeder_2_pol">'.$transhipment_name.'</div>		
												</div>
												
												<div class="form-group">
													<label>'.$lang['LOG_FEEDER_2_POD'].'</label>
													<select id="transport_id1" class="form-control">
														'.$list_transhipment2.'
													</select>
												</div>
												
												<div class="form-group">
													<label>'.$lang['LOG_FEEDER_VESSEL'].' </label>
													<div class="input-group">
														<input type="text" id="vessel_feeder_name1" value="'.$vessel_feeder_name1.'" class="form-control" />
														<span class="input-group-btn"> 
															<input type="button" onclick="checkMMSI(\'feeder2\');" data-toggle="tooltip" data-placement="top" title="Get MMSI" class="btn btn-primary" value="?" />
														</span>
													</div>
												</div>
												
												<div class="form-group"><label>'.$lang['LOG_FEEDER_MMSI'].' </label>
													<input type="number" min="0" id="vessel_feeder_mmsi_id1" value="'.$vessel_feeder_mmsi_id1.'" class="form-control" />
												</div>
												
												<div class="form-group"><label>'.$lang['CONTRACT_ETD'].' </label>
													<div class="row">
														<div class="col-sm-6">
															<div class="input-group date">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input type="text" class="form-control edit_delivery_date" value="'.$tport_etd1_date.'" id="tport_etd1">
															</div>
														</div>
														
														<div class="col-sm-6">
															<div class="input-group clockpicker" data-autoclose="true">
																<input type="text" class="form-control" id="tport_etd_time1" value="'.$tport_etd1_time.'" >
																<span class="input-group-addon">
																	<span class="fa fa-clock-o"></span>
																</span>
															</div>
														</div>
													</div>
												</div>
												
												<div class="form-group"><label>'.$lang['CONTRACT_ETA'].' </label>
													<div class="row">
														<div class="col-sm-6">
															<div class="input-group date">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input type="text" class="form-control edit_delivery_date" value="'.$tport_eta1_date.'" id="tport_eta1">
															</div>
														</div>
														
														<div class="col-sm-6">
															<div class="input-group clockpicker" data-autoclose="true">
																<input type="text" class="form-control" id="tport_eta_time1" value="'.$tport_eta1_time.'" >
																<span class="input-group-addon">
																	<span class="fa fa-clock-o"></span>
																</span>
															</div>
														</div>
													</div>
												</div>
												
												<div class="form-group">
													<label>'.$lang['LOG_ACTUAL_ETA'].'</label>
													'.$tport_eta_actual1.'
												</div>
											</div>
										</div>
										
										<h3 class="text-center">'.$lang['LOG_OCEAN_VESSEL'].'</h3>
										<div class="form-group">
											<label>'.$lang['LOG_VESSEL_NAME'].' <span style="color:red;">*</span> </label>
											<div class="input-group">
												<input type="text" id="vessel_name" style="text-transform: uppercase;" value="'.$vessel_name.'" class="form-control" /> 
												<span class="input-group-btn"> 
													<input type="button" onclick="checkMMSI(\'ocean\');" data-toggle="tooltip" data-placement="top" title="Get MMSI" class="btn btn-primary" value="?" />
												</span>
											</div>
										</div>
										
										<div class="form-group"><label>'.$lang['LOG_VESSEL_MMSI'].' </label>
											<input type="number" min="0" id="vessel_mmsi_id" value="'.$vessel_mmsi_id.'" class="form-control" />
										</div>
									
										<div class="form-group"><label>'.$lang['LOG_VESSEL_IMO'].' </label>
											<input type="number" min="0" id="vessel_imo_id" value="'.$vessel_imo_id.'" class="form-control" />
										</div>
									
										<div class="form-group"><label>'.$lang['LOG_VOYAGE_N'].' </label>
											<input type="text" id="voyage_nr" value="'.$voyage_nr.'" class="form-control" />
										</div>
									
										<div class="form-group"><label>'.$lang['CONTRACT_PORT_OF_DISCHARGE'].' <span style="color:red;">*</span> </label>
											<select class="form-control" name="" id="pod" onchange="bookingPodId(this.value);">
												'.$list_o_pod.'
											</select>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_ETD'].' <span style="color:red;">*</span> </label>
											<div class="row">
												<div class="col-sm-6">
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control edit_delivery_date" value="'.$etd_date.'" id="etd">
													</div>
												</div>
												
												<div class="col-sm-6">
													<div class="input-group clockpicker" data-autoclose="true">
														<input type="text" class="form-control" id="etd_time" value="'.$etd_time.'" >
														<span class="input-group-addon">
															<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_ETA'].' <span style="color:red;">*</span> </label>
											<div class="row">
												<div class="col-sm-6">
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control edit_delivery_date" value="'.$eta_date.'" id="eta">
													</div>
												</div>
												
												<div class="col-sm-6">
													<div class="input-group clockpicker" data-autoclose="true">
														<input type="text" class="form-control" id="eta_time" value="'.$eta_time.'" >
														<span class="input-group-addon">
															<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<label>'.$lang['LOG_ACTUAL_ETA'].'</label>
											'.$pod_eta_actual.'
										</div>
									</div>
									
									<div class="col-md-12 '.$ocean_u.'" id="EditOcenLoadingBtnID2">
										<button class="btn btn-success pull-right" onclick="show_ocean_loading_editBtn(\''.$id_con_booking.'\',\''.$ord_schedule_id.'\',\''.$ref_num.'\',\''.$req.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				
					<div class="panel panel-primary '.$ocean_cr.'" id="logistic_container">
						<div class="panel-heading" role="tab" id="headingContainer">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" onclick="isoAvailable('.$iso_available.');" href="#collapseContainer" aria-expanded="true" aria-controls="collapseContainer" class="trigger collapsed text-uppercase"> '.$lang['LOG_CONTAINER'].' '.$shipment_number.' </a>
								
								'.$doc_ocean2.'
							</h4>
						</div>
						
						<div id="collapseContainer" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingContainer">
							<div class="panel-body">
								<div class="tabs-container">
									<div class="row '.$hide_contPosiBox.'">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="pull-left"><label>'.$lang['LOG_ISO_TANK_AVAIL'].'</label></div>
												<div class="pull-left" style="margin-left:20px;">
													<div class="i-checks"><label> '.$lang['LOG_NO'].' <input type="radio" '. $iso_no.' value="0" id="iso_no" name="a" class="iso_available"> </label></div>
												</div>
												<div class="pull-left" style="margin-left:10px;">
													<div class="i-checks"><label> '.$lang['LOG_YES'].' <input type="radio" '.$iso_yes.' value="1" id="iso_yes" name="a" class="iso_available"> </label></div>
												</div>
											</div>
										</div>
									</div>
					
									<div class="row '.$hide_contPosiBox.'" style="margin-top:20px;">
										<div class="col-md-12">
											<div class="panel-group" id="accordion">
												<div class="panel panel-default">
													<div class="panel-heading">
														<h5 class="panel-title">
															<a data-toggle="collapse" data-parent="#accordion" href="#collapseConPositioning">'.$lang['LOG_CONTAINER_POSI'].'</a>
														</h5>
													</div>
													<div id="collapseConPositioning" class="panel-collapse collapse '.$conPosiIn.'">
														<div class="panel-body">
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_BOOKING_NUMB'].'</label><br/>
																		<input type="number" min="0" class="form-control" value="'.$iso_booking.'" id="iso_booking">
																	</div>
																	
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_VESSEL_NAME'].'</label>
																		<div class="input-group">
																			<input type="text" class="form-control" style="text-transform: uppercase;" value="'.$iso_vessel_name.'" id="iso_vessel_name">
																			<span class="input-group-btn"> 
																				<input type="button" onclick="checkMMSI(\'iso\');" data-toggle="tooltip" data-placement="top" title="Get MMSI" class="btn btn-primary" value="?" />
																			</span>
																		</div>
																	</div>
														
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['VT_MMSI'].'</label><br/>
																		<input type="text" class="form-control" value="'.$iso_vessel_mmsi.'" id="iso_vessel_mmsi">
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['CONTRACT_PORT_OF_LOADING'].'</label><br/>
																		<input type="text" class="form-control" value="'.$iso_pol.'" id="iso_pol">
																	</div>
																
																	<div class="form-group" style="height:64px;">
																		<label class="ord_sum_label">'.$lang['CONTRACT_PORT_OF_DISCHARGE'].'</label><br/>
																		'.$iso_pod.'
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['CONTRACT_ETD'].'</label><br/>
																		<div class="input-group date">
																			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																			<input type="text" class="form-control edit_delivery_date" value="'.$iso_etd.'" id="iso_etd">
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['CONTRACT_ETA'].'</label><br/>
																		<div class="input-group date">
																			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																			<input type="text" class="form-control edit_delivery_date" value="'.$iso_eta.'" id="iso_eta">
																		</div>
																	</div>
																
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_ACTUAL_ETA'].'</label>
																		'.$iso_eta_actual.'
																	</div>
																</div>
															</div>
															
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_DATE_AVAIL'].'</label><br/>
																		<div class="input-group date">
																			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																			<input type="text" class="form-control edit_delivery_date" value="'.$iso_date_available.'" id="iso_date_available">
																		</div>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_AUTHORIZED_BY'].'</label><br/>
																		<input type="text" class="form-control" value="'.$iso_positioning.'" id="iso_positioning">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_LOADING_FROM'].'</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" value="'.$con_load_date_from.'" id="con_load_date_from">
												</div>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_LOADING_TO'].'</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" value="'.$con_load_date_to.'" id="con_load_date_to">
												</div>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_SUPERVISOR_CHARGER'].'</label><br/>
												<select id="b_loading_manager_id" class="form-control">
													<option value="0">---</option>
													'.$loading_manager.'
												</select>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_QM_COMP'].'</label><br/>
												'.$qm_contact_name.'
											</div>
											
											<div class="form-group">
												<label>'.$lang['LOG_LOADING_PLACE'].' </label>
												<div class="input-group">
													<select class="form-control" name="" id="loading_place">
														'.$list_loading_place.'
													</select>
													<span class="input-group-btn"> 
														<input type="button" onclick="newLaodingPlcae();" data-toggle="tooltip" data-placement="top" title="New loading place" class="btn btn-primary" value="+" />
													</span>
												</div>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_MULTIPLE_LOADING_AGT'].'</label><br/>
												<div class="pull-left">
													<div class="i-checks"><label> '.$lang['LOG_NO'].' <input type="radio" '.$ids_agent_no.' value="0" id="ids_agent_no" name="ids_agent" class="ids_agent_radio"> </label></div>
												</div>
												<div class="pull-left" style="margin-left:10px;">
													<div class="i-checks"><label> '.$lang['LOG_YES'].' <input type="radio" '.$ids_agent_yes.' value="1" id="ids_agent_yes" name="ids_agent" class="ids_agent_radio"> </label></div>
												</div>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_LOADING_AGT'].'</label>
												<select class="form-control" id="loading_agent_1">
													'.$loading_agent_list1.'
												</select>
											</div>
											
											<div class="form-group '.$l_agent_2.'" id="h_lAgent2">
												<label class="ord_sum_label">'.$lang['LOG_LOADING_AGT'].'</label>
												<select class="form-control" id="loading_agent_2">
													'.$loading_agent_list2.'
												</select>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12">
											<label>'.$lang['LOG_CONTAINER_LIST'].'</label>
											<table class="table table-striped table-hover" style="font-size:13px; margin-top:15px;">
												<tbody id="container_list">
													'.$list_container.'
												</tbody>
											</table>
										</div>
									</div>
									
									<div class="row no-margins">
										<div class="panel panel-primary">
											<div class="panel-heading" role="tab" id="loading_documents">
												<h4 class="panel-title">
													<a role="button" data-toggle="collapse" href="#collapseLoadingDocs" aria-expanded="true" aria-controls="collapseLoadingDocs" class="trigger collapsed"> '.$lang['LOG_LOADING_STATUS'].' </a>
												</h4>
											</div>
					
											<div id="collapseLoadingDocs" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="loading_documents">
												<div class="panel-body">
													<div class="tabs-container">
														<div class="row">
															<div class="col-md-3">
																<label class="ord_sum_label">'.$lang['LOG_LOADING_COMPLET'].'</label><br/>
																<select id="con_end_state" class="form-control">
																	'.$sel_loading_complete.'
																</select>
															</div>
															
															<div class="col-md-2">
																<label class="ord_sum_label">'.$lang['LOG_LOADED'].'</label><br/>
																<input id="no_con_shipped" class="form-control" onchange="getDiff();" value="'.$no_con_shipped.'" type="number" min="0" />
															</div>
															
															<div class="col-md-2">
																<label class="ord_sum_label">'.$lang['LOG_ORDERED'].'</label><br/>
																<input id="cont_container_nr" class="form-control" onchange="getDiff();" value="'.$container_nr.'" type="number" min="0" />
															</div>
															
															<div class="col-md-2">
																<label class="ord_sum_label">'.$lang['LOG_DIFFERENCE'].'</label><br/>
																<input id="cont_loading_diff" class="form-control" value="'.$loading_diff.'" type="number" min="0" />
															</div>
															
															<div class="col-md-2"><br/>
																<button class="btn btn-primary '.$add_booking_btn.'" id="addBooking_btn" onclick="addBooking('.$ord_schedule_id.');" style="margin-top:10px;">'.$lang['LOG_ADD_BOOKING_BTN'].'</button>
															</div>
														</div>
														
														<div class="row">
															<div class="col-md-7">
																<div class="form-group">
																	<label class="ord_sum_label">'.$lang['LOG_NOTE_BY_CONT_LOADING_QM'].'</label><br/>
																	<textarea class="form-control" id="con_loading_note" cols="40" rows="2">'.$loading_note.'</textarea>
																</div>
															</div>
															
															<div class="col-md-2">
																<label class="ord_sum_label">'.$lang['LOG_TT_NET_WEIGHT'].'</label><br/>
																'.$total_vgm_weight.'
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-8">
											'.$container_modify_by.'
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12 '.$ocean_cu.'" id="editContBtn">
											<button class="btn btn-success pull-right" id="containerEditBTN" onclick="show_container_editBtn(\''.$id_con_booking.'\',\''.$ord_schedule_id.'\');" style="margin-top:10px;" type="button" disabled><i class="fa fa-edit"></i></button>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
					
					<div class="panel panel-primary '.$shipDoc_r.'" id="freight_shipping">
						<div class="panel-heading" role="tab" id="headingShipping">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseShipping" aria-expanded="true" aria-controls="collapseShipping" class="trigger collapsed text-uppercase"> '.$lang['LOG_SHIP_DOCS'].' '.$shipment_number.' </a>
								
								'.$doc_ocean3.'
							</h4>
						</div>
			
						<div id="collapseShipping" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingShipping">
							<div class="panel-body">
								<div class="tabs-container">
									<div class="row no-margins">
										<div class="col-md-12">
											<div class="form-group '.$oceanPKList_cr.'">
												<input type="button" onclick="packingList(\'0\',\''.$ord_schedule_id.'\',\'supplier\');" '.$btn_packinglist_supp.' class="btn btn-warning" id="btn_packinglist_supp" value="'.$lang['LOG_PACKING_LIST_BTN'].'" />
											</div>
										</div>
									</div>
								
									<div class="row no-margins">
										<div style="font-weight:bold;">'.$lang['LOG_SAMPLES_LAB'].'</div>
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_DHL_TRAKING_N'].'</label><br/>
												<input type="text" class="form-control" value="'.$lab_awb_no.'" disabled id="lab_awb_no">
											</div>
										</div>
										
										<div class="col-md-4">  
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_DHL_DATE_SENT'].'</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" disabled value="'.$lab_cus_awb_date.'" id="lab_cus_awb_date">
												</div>
											</div>
										</div>
										
										<div class="col-md-4">  
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_LAB'].'</label><br/>
												<select class="form-control" disabled id="lab_contact_id">
													<option value="">--</option>
													'.$lab_contact_list.'
												</select>
											</div>
										</div>
									</div>
									
									<div class="row no-margins">
										<div style="font-weight:bold;">'.$lang['LOG_SAMPLES_CUS'].'</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="text" class="form-control" value="'.$cust_awb_no.'" disabled id="cust_awb_no">
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" disabled value="'.$cus_awb_date.'" id="cus_awb_date">
												</div>
											</div>
										</div>
									</div>
								
									<div class="row no-margins">
										<div style="font-weight:bold;">'.$lang['LOG_SHIP_DOCS'].'</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_SHIP_DOC_DHL'].'</label><br/>
												<input type="text" class="form-control" disabled value="'.$fa_awb_no.'" id="fa_awb_no">
											</div>
										</div>
										
										<div class="col-md-6">  
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_SHIP_DOC_SENT'].'</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" disabled value="'.$fa_awb_date.'" id="fa_awb_date">
												</div>
											</div>
										</div>
									</div>
									
									<div class="row no-margins">
										<div class="col-md-6">
											<div class="form-group">
												<label class="ord_sum_label">B/L Number</label><br/>
												<input type="text" class="form-control" disabled value="'.$bl_number.'" id="bl_number">
											</div>
										</div>
										
										<div class="col-md-6">  
											<div class="form-group">
												<label class="ord_sum_label">Loading Date</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" disabled value="'.$pol_etd_actual.'" id="pol_etd_actual">
												</div>
											</div>
										</div>
									</div>
									
									<div class="row no-margins '.$shipDoc_u.'">
										<div class="col-md-12" id="editShippingDoc">
											<button class="btn btn-success pull-right" id="shippingDocEditBTN" onclick="show_shippingdoc_editBtn(\''.$req_doc.'\',\''.$ord_schedule_id.'\',\''.$ord_con_list_id.'\',\''.$id_con_booking.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
					
					<div class="panel panel-primary '.$labAna_r.'" id="lab_analysis">
						<div class="panel-heading" role="tab" id="headingLabAnalysis">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseLabAnalysis" aria-expanded="true" aria-controls="collapseLabAnalysis" class="trigger collapsed text-uppercase"> '.$lang['LOG_LAB_ANALYSIS'].' '.$shipment_number.' </a>
								
								'.$doc_ocean3.'
							</h4>
						</div>
			
						<div id="collapseLabAnalysis" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingLabAnalysis">
							<div class="panel-body">
								<div class="tabs-container">
									<div class="row no-margins '.$labAna_c.'">
										<div style="font-weight:bold;">'.$lang['LOG_LAB_ANALYSIS'].'</div>
										<div class="col-md-12">
											<div class="row">
												<form id="labForm">
													<div class="col-md-5">
														<div class="form-group">
															<label class="ord_sum_label">'.$lang['LOG_LAB_PARAM'].'</label><br/>
															<select id="id_prod_params" class="form-control" disabled>
																<option value="">--</option>
																'.$lab_param_list.'
															</select>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label class="ord_sum_label">'.$lang['LOG_LAB_DATE'].'</label><br/>
															<div class="input-group date">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input type="text" class="form-control edit_delivery_date" disabled id="date_analysis">
															</div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label class="ord_sum_label">'.$lang['LOG_LAB_RESULT'].'</label><br/>
															<input type="text" class="form-control" disabled id="lab_result">
														</div>
													</div>
												</form>
												
												<div class="col-md-1">
													<input type="button" class="btn btn-primary" value="+" onclick="save_labAna(\''.$ord_order_id.'\',\''.$ord_schedule_id.'\',\''.$id_con_booking.'\');" disabled style="margin-top:18px;">
												</div>
											</div>
										</div>
										
										<div class="col-md-12" style="margin-top:25px;">
											<table class="table table-bordered">
												<thead>
													<th>'.$lang['LOG_LAB_PARAM'].'</th>
													<th>'.$lang['LOG_LAB_UNIT'].'</th>
													<th>'.$lang['LOG_LAB_RESULT'].'</th>
													<th class="text-center">'.$lang['LOG_LAB_TB_ACTION'].'</th>  
												</thead>
												<tbody id="saved_lab_units">
													'.$labAnalysis_table.'
												</tbody>
											</table>
										</div>
										
										<input type="hidden" class="form-control" value="'.$lab_product_id.'" id="lab_product_id">
										<input type="hidden" class="form-control" value="'.$lab_supplier_id.'" id="lab_supplier_id">
									</div>
									
									<div class="row no-margins">
										<div class="col-md-6">
											<input type="button" id="cert_rqst_btn" class="btn btn-primary" '.$btn_cert_rqst.' value="'.$lang['LOG_LAB_DATA_ENTRY'].'" onclick="save_labDataEntry(\''.$ord_order_id.'\',\''.$ord_schedule_id.'\',\''.$id_con_booking.'\');" disabled>
											<small id="cert_rqst_val">'.$loading_certificate_requested.'</small>
										</div>
									</div>
									
									<div class="row no-margins '.$labAna_u.'">
										<div class="col-md-12" id="editLabAnalysis">
											<button class="btn btn-success pull-right" onclick="show_labAna_editBtn(\''.$ord_order_id.'\',\''.$ord_schedule_id.'\',\''.$id_con_booking.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>';
				
			} else {
				$ocean=''; $req='';
			}
			
			// Ocean add rights
			$oceanAdd_read = $_GET['oceanAdd_read'];
			$oceanAdd_update = $_GET['oceanAdd_update'];
			if($oceanAdd_read == 1){ $oceanAdd_r=''; }else{ $oceanAdd_r='hide'; }
			if($oceanAdd_update == 1){ $oceanAdd_u=''; }else{ $oceanAdd_u='hide'; }
			
			// Ocean add Container rights
			$oceanAddCont_read = $_GET['oceanAddCont_read'];
			$oceanAddCont_update = $_GET['oceanAddCont_update'];
			$oceanAddCont_create = $_GET['oceanAddCont_create'];
			if($oceanAddCont_read == 1){ $oceanAdd_cr=''; }else{ $oceanAdd_cr='hide'; }
			if($oceanAddCont_update == 1){ $oceanAdd_cu=''; }else{ $oceanAdd_cu='hide'; }
			if($oceanAddCont_create == 1){ $oceanAdd_cc=''; }else{ $oceanAdd_cc='hide'; }
			
			if($card2 == 1){
				$sql_ocean_add = "Select * From v_con_booking where  ord_schedule_id=$ord_schedule_id AND booking_segment=2";
		
				$rs_ocean_add = pg_query($conn, $sql_ocean_add);
				$row_ocean_add = pg_fetch_assoc($rs_ocean_add);
				
				if($row_ocean_add){ $req_add='update'; } else { $req_add='add'; }
			
				// Freight agent list
				$sql_freight_agent_add = "select * from contact where id_supchain_type=289";
				$rs_freight_agent_add = pg_query($conn, $sql_freight_agent_add);
			
				$list_freight_agent_add = '<option value="">-- '.$lang['CONTRACT_SEL_FREIGHT_AGENT'].' --</option>';
			
				while ($row_freight_agent_add = pg_fetch_assoc($rs_freight_agent_add)) {
					if($req_add=='update'){
						if($row_freight_agent_add['id_contact'] == $row_ocean_add['fa_contact_id']){ $sel_freight_a_add="selected='selected'"; }
						else { $sel_freight_a_add=""; }
					} else { $sel_freight_a_add=""; }
					$list_freight_agent_add .= '<option value="'. $row_freight_agent_add['id_contact'] .'"'.$sel_freight_a_add.'>'. $row_freight_agent_add['name'] .'</option>';
				}
				
				if(($req_add=='update')AND($row_ocean_add['fa_contact_id']!="")){
					// Freight agent person list
					$sql_freight_person_agent_add = "select * from contact where id_primary_company=".$row_ocean_add['fa_contact_id']."";
					$rs_freight_person_agent_add = pg_query($conn, $sql_freight_person_agent_add);
				
					$list_freight_person_agent_add = '<option value="">-- '.$lang['LOG_SEL_AGENT'].' --</option>';
				
					while ($row_freight_person_agent_add = pg_fetch_assoc($rs_freight_person_agent_add)) {
						if($req=='update'){
							if($row_freight_person_agent_add['id_contact'] == $row_ocean_add['fa_person_id']){ $sel_freight_person_a_add="selected='selected'"; }
							else { $sel_freight_person_a_add=""; }
						} else { $sel_freight_person_a_add=""; }
						$list_freight_person_agent_add .= '<option value="'. $row_freight_person_agent_add['id_contact'] .'"'.$sel_freight_person_a_add.'>'. $row_freight_person_agent['name'] .'</option>';
					}
				}
				
				//Carrier name list
				$sql_carrier_name_add = "select * from contact where id_supchain_type=319";
				$rs_carrier_name_add = pg_query($conn, $sql_carrier_name_add);
			
				$list_carrier_name_add = '<option value="">-- '.$lang['LOG_SEL_CARRIER'].' --</option>';
			
				while ($row_carrier_name_add = pg_fetch_assoc($rs_carrier_name_add)) {
					if($req_add=='update'){
						if($row_carrier_name_add['id_contact'] == $row_ocean_add['carrier_company_id']){ $sel_sl_line_add="selected='selected'"; }
						else { $sel_sl_line_add=""; }
					} else { $sel_sl_line_add=""; }
					$list_carrier_name_add .= '<option value="'. $row_carrier_name_add['id_contact'] .'"'.$sel_sl_line_add.'>'. $row_carrier_name_add['name'] .'</option>';
				}
				
				if(($req_add=='update') AND ($row_ocean_add['carrier_company_id']!="")){
					// Carrier name agent list
					$sql_carrier_person_name_add = "select * from contact where id_primary_company=".$row_ocean_add['carrier_company_id']."";
					$rs_carrier_person_name_add = pg_query($conn, $sql_carrier_person_name_add);
				
					$list_carrier_person_name_add = '<option value="">-- '.$lang['LOG_SEL_AGENT'].' --</option>';
				
					while ($row_carrier_person_name_add = pg_fetch_assoc($rs_carrier_person_name_add)) {
						if($req=='update'){
							if($row_carrier_person_name_add['id_contact'] == $row_ocean_add['carrier_person_id']){ $sel_person_sl_line_add="selected='selected'"; }
							else { $sel_person_sl_line_add=""; }
						} else { $sel_person_sl_line_add=""; }
						$list_carrier_person_name_add .= '<option value="'. $row_carrier_person_name_add['id_contact'] .'"'.$sel_person_sl_line_add.'>'. $row_carrier_person_name_add['name'] .'</option>';
					}
				}
				
				//Forwarder name
				$sql_forwarder_name_add = "select * from contact where id_supchain_type=288";
				$rs_forwarder_name_add = pg_query($conn, $sql_forwarder_name_add);
			
				$list_forwarder_name_add = '<option value="">-- '.$lang['LOG_SEL_FORWARDER'].' --</option>';
			
				while ($row_forwarder_name_add = pg_fetch_assoc($rs_forwarder_name_add)) {
					if($req_add=='update'){
						if($row_forwarder_name_add['id_contact'] == $row_ocean_add['log_contact_id']){ $sel_log_contact_add="selected='selected'"; }
						else { $sel_log_contact=""; }
					} else { $sel_log_contact=""; }
					
					$list_forwarder_name_add .= '<option value="'. $row_forwarder_name_add['id_contact'] .'" '.$sel_log_contact_add.'>'. $row_forwarder_name_add['name'] .'</option>';
				}
				
				if(($req_add=='update')AND($row_ocean_add['forwarder_company_id']!="")){
					// Forwarder name agent list
					$sql_forwarder_person_name_add = "select * from contact where id_primary_company=".$row_ocean_add['forwarder_company_id']."";
					$rs_forwarder_person_name_add = pg_query($conn, $sql_forwarder_person_name_add);
				
					$list_forwarder_person_name_add = '<option value="">-- '.$lang['LOG_SEL_AGENT'].' --</option>';
				
					while ($row_forwarder_person_name_add = pg_fetch_assoc($rs_forwarder_person_name_add)) {
						if($req=='update'){
							if($row_forwarder_person_name_add['id_contact'] == $row_ocean_add['forwarder_person_id']){ $sel_person_sl_line_add="selected='selected'"; }
							else { $sel_person_sl_line_add=""; }
						} else { $sel_person_sl_line_add=""; }
						
						$list_forwarder_person_nam_adde .= '<option value="'. $row_forwarder_person_name_add['id_contact'] .'" '.$sel_person_sl_line_add.'>'. $row_forwarder_person_name_add['name'] .'</option>';
					}
				}
				
				//Transhipment Port
				$sql_transhipment_add = "Select * from v_port where port_type_id=276";
				$rs_transhipment_add = pg_query($conn, $sql_transhipment_add);
			
				$list_transhipment_add = '<option value="">-- '.$lang['LOG_SEL_TRANS'].' --</option>';
			
				while ($row_transhipment_add = pg_fetch_assoc($rs_transhipment_add)) {
					if($req_add=='update'){
						if($row_transhipment_add['id_townport'] == $row_ocean_add['trans_port_id']){ $sel_trans_port_add="selected='selected'"; }
						else { $sel_trans_port_add=""; }
					} else { $sel_trans_port_add=""; }
					$list_transhipment_add .= '<option value="'. $row_transhipment_add['id_townport'] .'"'.$sel_trans_port_add.'>'. $row_transhipment_add['portname'] .'</option>';
				}
				
				$sql_container_add = "Select * From v_booking_conlist where ord_schedule_id= $ord_schedule_id  Order by cus_con_ref1";
				$rs_container_add = pg_query($conn, $sql_container_add);
				
				$list_container_add = '';
				
				while ($row_container_add = pg_fetch_assoc($rs_container_add)) {
					if($row_container_add['task_done']==2){
						$loading_add=' - <span class="text-navy">'.$lang['LOG_LOADING'].'</span>';
					} else 
					if($row_container_add['task_done']==1){
						$loading_add=' - <span class="text-success">'.$lang['LOG_LOADING_COMPLET'].'</span>';
					} else {
						$loading_add='';
					}
				
					$list_container_add .= '<tr><td>  
							<a href="javascript:loadingForm2(\''. $row_container_add['id_con_list'] .'\',\''. $row_container_add['id_ord_loading_item'] .'\');" class="reference_nr">
								'. $row_container_add['cus_con_ref1'] . '
							</a>
						</td>
						
						<td> '.$row_container_add['container_nr']. $loading_add .'</td>  
						<td> T: '. $row_container_add['tare'] . ' </td>  
						<td> W: '. $row_container_add['vgm_weight'] . ' </td>    
						<td> '. $row_container_add['date_loaded'] . ' </td>  
						
						<td style="width:60px">
							<a href="#" onclick="loadingForm2(\''. $row_container_add['id_con_list'] .'\',\''. $row_container_add['id_ord_loading_item'] .'\');"><i class="fa fa-eye"></i></a>
							<span class="containers_action_tb_add">
								&nbsp;<a href="#" class="'.$oceanAdd_cc.' editContainer" onclick="edit_container(\''.$row_container_add['id_con_list'].'\',\''. $row_container_add['container_nr'] .'\',\''. $ord_schedule_id .'\');"><i class="fa fa-pen-square"></i></a>
							</span>
						</td>
					</tr>';
				}
				
				
				$sql_modby_add = "Select modified_contact, modified_date From v_order_schedule where id_ord_schedule= $ord_schedule_id";
				$rs_modby_add = pg_query($conn, $sql_modby_add);
				
				if($rs_modby_add){
					$row_modby_add = pg_fetch_assoc($rs_modby_add);
					
					$modify_by_add = '<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label> '. $row_modby_add['modified_contact'] .' <br/>
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].': </label> '. $row_modby_add['modified_date'] .'
					</div>';
					
				} else {
					$modify_by_add = ''; 
				}

		
				if($req_add=='update'){
					
					$fa_note_add = $row_ocean_add['fa_note']; 
					$sl_note_add = $row_ocean_add['sl_note']; 
					$log_note_add = $row_ocean_add['log_note']; 
					$fa_reference_nr_add = $row_ocean_add['fa_reference_nr']; 
					$booking_nr_add = $row_ocean_add['booking_nr']; 
					$pol_add = $row_ocean_add['pol']; 
					$cutoff_date_time_add = explode(" ", $row_ocean_add['cutoff_date']);
					$cutoff_date_add = $cutoff_date_time_add[0];
					$cutoff_time_add = $cutoff_date_time_add[1];
					$vgm_cutoff_date_time_add = explode(" ", $row_ocean_add['vgm_cutoff']);
					$vgm_cutoff_date_add = $vgm_cutoff_date_time[0];
					$vgm_cutoff_time_add = $vgm_cutoff_date_time[1];
					$etd_date_time_add = explode(" ", $row_ocean_add['pol_etd']);
					$etd_date_add = $etd_date_time[0];
					$etd_time_add = $etd_date_time[1];
					$vessel_feeder_name_add = $row_ocean_add['vessel_feeder_name'];
					$vessel_feeder_mmsi_id_add = $row_ocean_add['vessel_feeder_mmsi_id'];
					$tport_eta_date_time_add = explode(" ", $row_ocean_add['tport_eta']);
					$tport_eta_date_add = $tport_eta_date_time[0];
					$tport_eta_time_add = $tport_eta_date_time[1];
					$tport_etd_date_time_add = explode(" ", $row_ocean_add['tport_etd']);
					$tport_etd_date_add = $tport_etd_date_time[0];
					$tport_etd_time_add = $tport_etd_date_time[1];
					$vessel_name_add = strtoupper($row_ocean_add['vessel_name']);
					$vessel_mmsi_id_add = $row_ocean_add['vessel_mmsi_id'];
					$vessel_imo_id_add = $row_ocean_add['vessel_imo_id'];
					$voyage_nr_add = $row_ocean_add['voyage_nr'];
					$pod_add = $row_ocean_add['pod_id'];
					$eta_date_time_add = explode(" ", $row_ocean_add['eta']);
					$eta_date_add = $eta_date_time[0];
					$eta_time_add = $eta_date_time[1];
					$booking_type_id_add = $row_ocean_add['booking_type_id'];
					
					$hide_fa_person_add = '';
					$hide_carrier_person_add = '';
					$hide_forwarder_person_add = '';
					
					
					// Container 
					$iso_available_add = $row_ocean_add['iso_available'];
					if($iso_available_add == 1){ 
						$conPosiIn_add = ''; 
						$iso_yes_add='checked'; $iso_no_add='';
					} else { 
						$conPosiIn_add = 'in'; 
						$iso_yes_add=''; $iso_no_add='checked';
					}
					
					$iso_positioning_add = $row_ocean_add['iso_positioning'];
					$iso_pol_add = $row_ocean_add['iso_pol'];
					$iso_pod_add = $row_ocean_add['pol'];
					$iso_etd_add = $row_ocean_add['iso_etd'];
					$iso_eta_add = $row_ocean_add['iso_eta'];
					$iso_date_available_add = $row_ocean_add['iso_date_available'];
					
					
					$iso_booking_add = $row_ocean_add['iso_booking'];
					$iso_vessel_name_add = $row_ocean_add['iso_vessel_name'];
					$iso_vessel_mmsi_add = $row_ocean_add['iso_vessel_mmsi'];
					$package_type_id_add = $row_ocean_add['package_type_id'];
					$con_load_date_from_add = $row_ocean_add['con_load_date_from'];
					$con_load_date_to_add = $row_ocean_add['con_load_date_to'];
					$qm_contact_name_add = $row_ocean_add['qm_contact_name'];
					
					$booking_pod_id_add = $row_ocean_add['bpod_id'];
					$booking_pol_id_add = $row_ocean_add['pol_id'];
					
					$modified_by_name_add = $row_ocean_add['modified_by_name'];
					$modified_date_add = $row_ocean_add['modified_date'];
					
					$id_con_booking_add=$row_ocean_add['id_con_booking'];
					$edit_btn_add='<a href="#" style="color:#FFF;" onclick="edit_ocean_loading_add(\''.$row_ocean_add['id_con_booking'].'\',\''.$ref_num.'\',\''.$ord_schedule_id.'\',\'edit\');"> <i class="fa fa-edit"></i> </a>';
					
					$tport_eta_actual_add = $row_ocean_add['tport_eta_actual'];
					$pod_eta_actual_add = $row_ocean_add['pod_eta_actual'];
					$iso_eta_actual_add = $row_ocean_add['iso_eta_actual'];
					
					$loading_manager_id_add=$row_ocean_add['load_manager_id'];
					$ids_multiple_agent_add=$row_ocean_add['ids_multiple_agent'];
					
					$bl_number_add=$row_ocean_add['bl_number'];
					$pol_etd_actual_add_datetime=explode(' ', $row_ocean['pol_etd_actual']); 
					$pol_etd_actual_add=$pol_etd_actual_add_datetime[0];
					$ord_order_id_add=$row_ocean_add['ord_order_id'];
					
				} else { 
				
					$fa_note_add = "";  
					$sl_note_add = "";  
					$log_note_add = "";  
					$booking_nr_add = "";  
					$pol_add = $pol_name;  
					$cutoff_date_add = "";  
					$cutoff_time_add = "08:00"; 
					$vgm_cutoff_date_add = "";  
					$vgm_cutoff_time_add = "08:00";  
					$etd_date_add = "";  
					$etd_time_add = "08:00";  
					$vessel_feeder_name_add = "";  
					$vessel_feeder_mmsi_id_add = "";  
					$tport_eta_date_add = "";  
					$tport_eta_time_add = "08:00"; 
					$tport_etd_date_add = "";  
					$tport_etd_time_add = "08:00";  
					$vessel_name_add = "";  
					$vessel_mmsi_id_add = "";  
					$vessel_imo_id_add = "";  
					$voyage_nr_add = "";  
					$pod_add = $pod_name;  
					$eta_date_add = "";
					$eta_time_add = "08:00";
					$fa_reference_nr_add = $ref_num;
					$booking_type_id_add = "";
					
					$hide_fa_person_add = 'hide';
					$hide_carrier_person_add = 'hide';
					$hide_forwarder_person_add = 'hide';
					
					
					// Container 
					$conPosiIn_add = '';
					$iso_positioning_add = "";
					$iso_pol_add = "";
					$iso_pod_add = "";
					$iso_etd_add = "";
					$iso_eta_add = "";
					$iso_date_available_add = "";
					$iso_yes_add=''; $iso_no_add='checked';
					$iso_booking_add = "";
					$iso_vessel_name_add = "";
					$iso_vessel_mmsi_add = "";
					$package_type_id_add = "";
					$con_load_date_from_add = "";
					$con_load_date_to_add = "";
					$qm_contact_name_add = "";
		
					$booking_pod_id_add = $pod_id;
					$booking_pol_id_add = $pol_id;
					
					$modified_by_name_add = "";
					$modified_date_add = "";
					
					$id_con_booking_add='';
					$edit_btn_add='';
					
					$tport_eta_actual_add = '';
					$pod_eta_actual_add = '';
					$iso_eta_actual_add = '';
					
					$loading_manager_id_add= '';
					$ids_multiple_agent_add= '';
					
					$bl_number_add= '';
					$pol_etd_actual_add= '';
					$ord_order_id_add= '';
				}
		

				if($done_ids_add==""){ $sel_loading_complete_add='<option value="0" selected>---</option><option value="1">Complete</option>'; } 
				else { $sel_loading_complete_add='<option value="0">---</option><option value="1" selected>Complete</option>'; }
			
			
				if(($modified_by_name_add != "")&&($modified_date_add = "")){
					$container_modify_by_add = '<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label> '. $modified_by_name_add .' <br/>
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].': </label> '. $modified_date_add .'
					</div>';
				} else {
					$container_modify_by_add = "";
				}
		
		
				$sql_o_pod_add = "Select * from v_port where port_type_id=273";
				$rs_o_pod_add = pg_query($conn, $sql_o_pod_add);
				
				$list_o_pod_add = '<option value="">-- '.$lang['LOG_SEL_PORT'].' --</option>';
				
				while ($row_o_pod_add = pg_fetch_assoc($rs_o_pod_add)) {
					if($req_add=='update'){
						if($row_o_pod_add['portname'] == $pod){ $sel_o_pod_add="selected='selected'"; }
						else { $sel_o_pod_add=""; }
					} else { $sel_o_pod_add=""; }
					$list_o_pod_add .= '<option value="'. $row_o_pod_add['portname'] .'"'.$sel_o_pod_add.'>'. $row_o_pod_add['portname'] .'</option>';
				}
			
				
				if($row_ocean_add['trans_port_id']!=""){ $hide_transBox_add = ''; } else { $hide_transBox_add = 'hide'; }
				if($package_type_id_add == 269) { $hide_contPosiBox_add=''; } else { $hide_contPosiBox_add='hide'; }
				
				if($row_ocean_add['confirmation_document']!=""){
					$hide_view_btn_add = '';
				} else {
					$hide_view_btn_add = '<a href="#" class="pull-right" onclick="viewBookingDoc(\''.$row_ocean_add['confirmation_document'].'\',\'0\');"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;'.$lang['LOG_VIEW'].'</a>';
				}

				if($booking_type_id_add == 1){ $bD_add='selected'; $bId_add=''; }
				else if($booking_type_id_add == 0){ $bD_add=''; $bId_add='selected'; }
				else { $bD_add='selected'; $bId_add=''; }
				
				$booking_type_add = '<option value="1" '.$bD_add.'>'.$lang['LOG_OPT_DIRECT'].'</option>
					<option value="0" '.$bId_add.'>'.$lang['LOG_OPT_VIA_TRANS'].'</option>';
				
				$mail2 = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;" onclick="eMailForm(\''.$ord_schedule_id.'\',\'logistics\',\'2\');"><i class="fa fa-envelope"></i></a>';
				
				if($doc_right == 1){
					$doc_add = '<a href="#" class="pull-right" style="margin-left:10px;" onclick="showDocList(\''.$ord_order_id_add.'\',\''.$ord_schedule_id.'\',\'logistics\',\'\');"><i class="fa fa-file-text"></i></a>';

					$doc_addendum1 = $mail2.$doc_add;
					$doc_addendum2 = $mail2.$doc_add;
					$doc_addendum3 = $mail2.$doc_add;
					
				} else {
					$doc_addendum1 = '';
					$doc_addendum2 = '';
					$doc_addendum3 = '';
				}
			
				// Shipping Documents
				// Shipping Documents
				$sql_shipDoc_add = "Select ord_con_loading_header.ord_con_list_id, 
				ord_con_loading_header.lab_awb_no, 
				ord_con_loading_header.lab_cus_awb_date, 
				ord_con_loading_header.cust_awb_no, 
				ord_con_loading_header.cus_awb_date, 
				ord_con_loading_header.fa_awb_no,
				ord_con_loading_header.fa_awb_date, 
				to_char(ord_con_loading_header.total_vgm_weight,'999G999D999') total_vgm_weight,
				ord_con_loading_header.loading_note,
				ord_con_loading_header.end_state,
				ord_con_loading_header.no_con_shipped,
				ord_con_loading_header.ids_person_id,
				get_contact_name(ord_con_loading_header.ids_person_id) ids_person_name,
				ord_con_loading_header.container_nr,
				ord_con_loading_header.loadin_diff,
				ord_con_loading_header.lab_contact_id
				from ord_con_loading_header where ord_schedule_id=$ord_schedule_id";

				$rs_shipDoc_add = pg_query($conn, $sql_shipDoc_add);
				$row_shipDoc_add = pg_fetch_assoc($rs_shipDoc_add);
				
				if($row_shipDoc_add){ $req_doc_add='update'; } else { $req_doc_add='add'; }
				
				if($req_doc_add=='update'){
					$lab_awb_no_add = $row_shipDoc_add['lab_awb_no'];
					$lab_cus_awb_date_add = $row_shipDoc_add['lab_cus_awb_date'];
					$cust_awb_no_add = $row_shipDoc_add['cust_awb_no'];
					$cus_awb_date_add = $row_shipDoc_add['cus_awb_date'];
					$fa_awb_no_add = $row_shipDoc_add['fa_awb_no'];
					$fa_awb_date_add = $row_shipDoc_add['fa_awb_date'];
					$loading_note_add = $row_shipDoc_add['loading_note'];
					$done_ids_add = $row_shipDoc_add['done_ids'];
					$no_con_shipped_add = $row_shipDoc_add['no_con_shipped'];
					$container_nr_add = $row_shipDoc_add['container_nr'];
					$ids_person_id_add = $row_shipDoc_add['ids_person_id'];
					$ids_person_name_add = $row_shipDoc_add['ids_person_name'];
					$lab_contact_id_add = $row_shipDoc_add['lab_contact_id'];
					
				} else {
					$lab_awb_no_add = "";
					$lab_cus_awb_date_add = gmdate("Y/m/d");
					$cust_awb_no_add = "";
					$cus_awb_date_add = "";
					$fa_awb_no_add = "";
					$fa_awb_date_add = gmdate("Y/m/d");
					$loading_note_add = "";
					$done_ids_add = "";
					$no_con_shipped_add = "";
					$container_nr_add = "";
					$ids_person_id_add = "";
					$ids_person_name_add = "";
					$lab_contact_id_add = "";
				}
				
				$loading_manager_add='';
				if(!empty($row_ocean_add['supplier_contact_id'])){
					$supplier_contact_id = $row_ocean_add['supplier_contact_id'];
					
					$sql_lm_add="select id_contact, name from contact where id_primary_company=$supplier_contact_id_add and id_type=9";
					$rs_lm_add = pg_query($conn, $sql_lm_add);
					while($row_lm_add = pg_fetch_assoc($rs_lm_add)){
						if($loading_manager_id_add == $row_lm_add['id_contact']){
							$sel_LM_add="selected='selected'";
						} else { $sel_LM_add=""; }
						$loading_manager.='<option value="'.$row_lm_add['id_contact'].'" '.$sel_LM_add.'>'.$row_lm_add['name'].'</option>';
					}
				}
				
				if($ids_multiple_agent_add==1){
					$ids_agent_yes_add='checked'; $ids_agent_no_add='';
				} else {
					$ids_agent_no_add='checked'; $ids_agent_yes_add='';
				}
				
				
				$lab_contact_list_add="";
				$sql_lab_add="select id_contact, name from contact where id_supchain_type=379";
				$rs_lab_add = pg_query($conn, $sql_lab_add);
				while($row_lab_add = pg_fetch_assoc($rs_lab_add)){
					if($lab_contact_id_add == $row_lab_add['id_contact']){
						$sel_LAB_add="selected='selected'";
					} else { $sel_LAB_add=""; }
					$lab_contact_list_add.='<option value="'.$row_lab_add['id_contact'].'" '.$sel_LAB_add.'>'.$row_lab_add['name'].'</option>';
				}
				
				$booking_addendum = '<div class="collapse-group">
					<div class="panel panel-primary '.$oceanAdd_r.'" id="freight_booking">
						<div class="panel-heading" role="tab" id="headingBooking2">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseBooking2" aria-expanded="true" aria-controls="collapseBooking2" class="trigger collapsed text-uppercase"> '.$lang['LOG_ONWARD_BOOKING'].' '.$shipment_number.' </a>
							
								'.$doc_addendum1.'
								<span class="pull-right '.$oceanAdd_u.'" id="EditOcenLoadingBtnID_add">'.$edit_btn_add.'</span>
							</h4>
						</div>
				
						<div id="collapseBooking2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingBooking2">
							<div class="panel-body">
								<div class="tabs-container" id="bookingTabID_add">
									<div class="col-md-6">
										<div class="form-group"><label>'.$lang['LOG_FREIGHT_AGT'].' <span style="color:red;">*</span> </label>
											<select class="form-control" name="" onchange="getFreightAgent_add(this.value);" id="freight_agent_list_add">
												'.$list_freight_agent_add.'
											</select>
										</div>
										
										<input id="booking_pod_id_add" value="'.$booking_pod_id_add.'" type="hidden" />
										<input id="booking_pol_id_add" value="'.$booking_pol_id_add.'" type="hidden" />
										
										<div class="form-group '.$hide_fa_person.'" id="fa_person_box_add"><label> '.$lang['LOG_AGT'].' </label>
											<select class="form-control" name="" id="fa_contact_person_id_add">
												'.$list_freight_person_agent_add.'
											</select>
										</div>

										<div class="form-group"><label>'.$lang['LOG_FREIGHT_AGT_REF_NUMB'].' <span style="color:red;">*</span> </label>
											<input value="'.$fa_reference_nr_add.'" type="hidden" id="fa_reference_nr_add" class="form-control" />
											<br/>'.$fa_reference_nr_add.'
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_NOTES'].' </label>
											<textarea id="agent_note_add" style="height:65px;" class="form-control" >'.$fa_note_add.'</textarea>
										</div>
									
										<div class="form-group">
											<label>'.$lang['LOG_CARRIER_NAME'].' <span style="color:red;">*</span> </label>
											<div class="input-group">
												<select class="form-control" name="" onchange="getCarrierAgent_add(this.value);" id="carrier_name_list_add">
													'.$list_carrier_name_add.'
												</select>
												<span class="input-group-btn"> 
													<input type="button" onclick="newCarrier();" data-toggle="tooltip" data-placement="top" title="New carrier" class="btn btn-primary" value="+" />
												</span>
											</div>
										</div>
										
										<div class="form-group '.$hide_carrier_person_add.'" id="sl_line_box_add"><label> '.$lang['LOG_AGT'].' </label>
											<select class="form-control" name="" id="sl_contact_name_add">
												'.$list_carrier_person_name_add.'
											</select>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_NOTES'].' </label>
											<textarea id="carrier_note_add" style="height:65px;" class="form-control" >'.$sl_note_add.'</textarea>
										</div>
								
										<div class="form-group">
											<label>'.$lang['LOG_FORWARDER_NAME'].' </label>
											<div class="input-group">
												<select class="form-control" name="" onchange="getForwarderAgent_add(this.value);" id="forwarder_name_list_add">
													'.$list_forwarder_name_add.'
												</select>
												<span class="input-group-btn"> 
													<input type="button" onclick="newForwarder();" data-toggle="tooltip" data-placement="top" title="New forwarder" class="btn btn-primary" value="+" />
												</span>
											</div>
										</div>
								
										<div class="form-group '.$hide_forwarder_person_add.'" id="log_contact_box_add"><label> '.$lang['LOG_AGT'].' </label>
											<select class="form-control" name="" id="log_contact_name_add">
												'.$list_forwarder_person_name_add.'
											</select>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_NOTES'].' </label>
											<textarea id="forwarder_note_add" style="height:65px;" class="form-control" >'.$log_note_add.'</textarea>
										</div>
									
										<div class="form-group"><label>'.$lang['LOG_CHARGER'].' </label><br/>
											'.$supplier_name_add.'
										</div>
										
										'.$modify_by_add.'
									</div>
									
									<div class="col-md-6">
										<div class="form-group"><label>'.$lang['LOG_BOOKING_TYPE'].' <span style="color:red;">*</span> </label>
											<select id="booking_type_id_add" class="form-control" onchange="bTypeChoice_add(this.value);" required>
												<option>--'.$lang['LOG_SEL_TYPE'].'--</option>
												'.$booking_type_add.'
											</select>
										</div>
									
										<div class="form-group"><label>'.$lang['LOG_BOOKING_NUMB'].' <span style="color:red;">*</span> </label>
											<input value="'.$booking_nr_add.'" type="text" id="booking_nr_add" class="form-control" />
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_PORT_OF_LOADING'].' <span style="color:red;">*</span> </label>
											<input value="'.$pol_add.'" type="text" id="pol_add" class="form-control" />
										</div>
										
										<div class="form-group"><label>'.$lang['LOG_CLOSING'].' <span style="color:red;">*</span> </label>
											<div class="row">
												<div class="col-sm-6">
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control edit_delivery_date" value="'.$cutoff_date_add.'" id="cutoff_date_add" required>
													</div>
												</div>
												
												<div class="col-sm-6">
													<div class="input-group clockpicker" data-autoclose="true">
														<input type="text" class="form-control" id="cutoff_time_add" value="'.$cutoff_time_add.'" required>
														<span class="input-group-addon">
															<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group"><label>'.$lang['LOG_VGM_CUT_OFF'].' <span style="color:red;">*</span> </label>
											<div class="row">
												<div class="col-sm-6">
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" value="'.$vgm_cutoff_date_add.'" class="form-control edit_delivery_date" id="vgm_cutoff_add">
													</div>
												</div>
												
												<div class="col-sm-6">
													<div class="input-group clockpicker" data-autoclose="true">
														<input type="text" class="form-control" id="vgm_cutoff_time_add" value="'.$vgm_cutoff_time_add.'" >
														<span class="input-group-addon">
															<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
									
										<div style="background:#e4e4e4; padding:10px; margin-bottom:10px;">
											<div class="form-group"><label>'.$lang['LOG_TRANSSHIPMENT'].' </label>
												<select class="form-control" name="" id="trans_port_id_add" onchange="showTranshipment_add(this.value);" disabled>
													'.$list_transhipment_add.'
												</select>
											</div>
											
											<div id="transBox_add" class="'.$hide_transBox_add.'">
												<div class="form-group"><label>'.$lang['LOG_FEEDER_VESSEL'].' </label>
													<input type="text" id="vessel_feeder_name_add" value="'.$vessel_feeder_name_add.'" class="form-control" />
												</div>
												
												<div class="form-group"><label>'.$lang['LOG_FEEDER_MMSI'].' </label>
													<input type="number" min="0" id="vessel_feeder_mmsi_id_add" value="'.$vessel_feeder_mmsi_id_add.'" class="form-control" />
												</div>
												
												<div class="form-group"><label>'.$lang['CONTRACT_ETD'].' </label>
													<div class="row">
														<div class="col-sm-6">
															<div class="input-group date">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input type="text" class="form-control edit_delivery_date" value="'.$tport_etd_date_add.'" id="tport_etd_add">
															</div>
														</div>
														
														<div class="col-sm-6">
															<div class="input-group clockpicker" data-autoclose="true">
																<input type="text" class="form-control" id="tport_etd_time_add" value="'.$tport_etd_time_add.'" >
																<span class="input-group-addon">
																	<span class="fa fa-clock-o"></span>
																</span>
															</div>
														</div>
													</div>
												</div>
												
												<div class="form-group"><label>'.$lang['CONTRACT_ETA'].' </label>
													<div class="row">
														<div class="col-sm-6">
															<div class="input-group date">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input type="text" class="form-control edit_delivery_date" value="'.$tport_eta_date_add.'" id="tport_eta_add">
															</div>
														</div>
														
														<div class="col-sm-6">
															<div class="input-group clockpicker" data-autoclose="true">
																<input type="text" class="form-control" id="tport_eta_time_add" value="'.$tport_eta_time_add.'" >
																<span class="input-group-addon">
																	<span class="fa fa-clock-o"></span>
																</span>
															</div>
														</div>
													</div>
												</div>
												
												<div class="form-group">
													<label>'.$lang['LOG_ACTUAL_ETA'].' </label>
													'.$tport_eta_actual_add.'
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<label>'.$lang['LOG_VESSEL_NAME'].' <span style="color:red;">*</span> </label>
											<div class="input-group">
												<input type="text" id="vessel_name_add" style="text-transform: uppercase" value="'.$vessel_name_add.'" class="form-control" /> 
												<span class="input-group-btn"> 
													<input type="button" onclick="checkMMSI(\'ocean_add\');" data-toggle="tooltip" data-placement="top" title="Get MMSI" class="btn btn-primary" value="?" />
												</span>
											</div>
										</div>
							
										<div class="form-group"><label>'.$lang['LOG_VESSEL_MMSI'].' </label>
											<input type="number" min="0" id="vessel_mmsi_id_add" value="'.$vessel_mmsi_id_add.'" class="form-control" />
										</div>
									
										<div class="form-group"><label>'.$lang['LOG_VESSEL_IMO'].' </label>
											<input type="number" min="0" id="vessel_imo_id_add" value="'.$vessel_imo_id_add.'" class="form-control" />
										</div>
									
										<div class="form-group"><label>'.$lang['LOG_VOYAGE_N'].' </label>
											<input type="text" id="voyage_nr_add" value="'.$voyage_nr_add.'" class="form-control" />
										</div>
									
										<div class="form-group"><label>'.$lang['CONTRACT_PORT_OF_DISCHARGE'].' <span style="color:red;">*</span> </label>
											<select class="form-control" name="" id="pod_add">
												'.$list_o_pod_add.'
											</select>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_ETD'].' <span style="color:red;">*</span> </label>
											<div class="row">
												<div class="col-sm-6">
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control edit_delivery_date" value="'.$etd_date_add.'" id="etd_add">
													</div>
												</div>
												
												<div class="col-sm-6">
													<div class="input-group clockpicker" data-autoclose="true">
														<input type="text" class="form-control" id="etd_time_add" value="'.$etd_time_add.'" >
														<span class="input-group-addon">
															<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_ETA'].' <span style="color:red;">*</span> </label>
											<div class="row">
												<div class="col-sm-6">
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control edit_delivery_date" value="'.$eta_date_add.'" id="eta_add">
													</div>
												</div>
												
												<div class="col-sm-6">
													<div class="input-group clockpicker" data-autoclose="true">
														<input type="text" class="form-control" id="eta_time_add" value="'.$eta_time_add.'" >
														<span class="input-group-addon">
															<span class="fa fa-clock-o"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<label>'.$lang['LOG_ACTUAL_ETA'].' </label>
											'.$pod_eta_actual_add.'
										</div>
									</div>
									
									<div class="col-md-12 '.$oceanAdd_u.'" id="EditOcenLoadingBtnID2_add">
										<button class="btn btn-success pull-right" onclick="show_ocean_loading_editBtn_add(\''.$id_con_booking_add.'\',\''.$ord_schedule_id.'\',\''.$ref_num.'\',\''.$req_add.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="panel panel-primary '.$oceanAdd_cr.'" id="freight_container">
						<div class="panel-heading" role="tab" id="headingContainer2">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseContainer_add" aria-expanded="true" aria-controls="collapseContainer_add" class="trigger collapsed text-uppercase"> '.$lang['LOG_CONTAINER'].' '.$shipment_number.' </a>
								'.$doc_addendum2.'
							</h4>
						</div>
						
						<div id="collapseContainer_add" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingContainer2">
							<div class="panel-body">
								<div class="tabs-container">
									<div class="row '.$hide_contPosiBox_add.'">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="pull-left"><label>'.$lang['LOG_ISO_TANK_AVAIL'].'</label></div>
												<div class="pull-left" style="margin-left:20px;">
													<div class="i-checks"><label> '.$lang['LOG_NO'].' <input type="radio" '.$iso_no_add.' value="0" name="a" class="iso_available_add"> </label></div>
												</div>
												<div class="pull-left" style="margin-left:10px;">
													<div class="i-checks"><label> '.$lang['LOG_YES'].' <input type="radio" '.$iso_yes_add.' value="1" name="a" class="iso_available_add"> </label></div>
												</div>
											</div>
										</div>
									</div>
					
									<div class="row '.$hide_contPosiBox_add.'" style="margin-top:20px;">
										<div class="col-md-12">
											<div class="panel-group" id="accordion_add">
												<div class="panel panel-default">
													<div class="panel-heading">
														<h5 class="panel-title">
															<a data-toggle="collapse" data-parent="#accordion_add" href="#collapseConPositioning_add">'.$lang['LOG_CONTAINER_POSI'].'</a>
														</h5>
													</div>
													<div id="collapseConPositioning_add" class="panel-collapse collapse '.$conPosiIn_add.'">
														<div class="panel-body">
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_BOOKING_NUMB'].'</label><br/>
																		<input type="number" min="0" class="form-control" value="'.$iso_booking_add.'" id="iso_booking_add">
																	</div>
																	
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_VESSEL_NAME'].'</label>
																		<div class="input-group">
																			<input type="text" class="form-control" style="text-transform: uppercase;" value="'.$iso_vessel_name_add.'" id="iso_vessel_name_add">
																			<span class="input-group-btn"> 
																				<input type="button" onclick="checkMMSI(\'iso_add\');" data-toggle="tooltip" data-placement="top" title="Get MMSI" class="btn btn-primary" value="?" />
																			</span>
																		</div>
																	</div>
																
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['VT_MMSI'].'</label><br/>
																		<input type="text" class="form-control" value="'.$iso_vessel_mmsi_add.'" id="iso_vessel_mmsi_add">
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['CONTRACT_PORT_OF_LOADING'].'</label><br/>
																		<input type="text" class="form-control" value="'.$iso_pol_add.'" id="iso_pol_add">
																	</div>
																
																	<div class="form-group" style="height:64px;">
																		<label class="ord_sum_label">'.$lang['CONTRACT_PORT_OF_DISCHARGE'].'</label><br/>
																		'.$iso_pod_add.'
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['CONTRACT_ETD'].'</label><br/>
																		<div class="input-group date">
																			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																			<input type="text" class="form-control edit_delivery_date" value="'.$iso_etd_add.'" id="iso_etd_add">
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['CONTRACT_ETA'].'</label><br/>
																		<div class="input-group date">
																			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																			<input type="text" class="form-control edit_delivery_date" value="'.$iso_eta_add.'" id="iso_eta_add">
																		</div>
																	</div>
																	
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_ACTUAL_ETA'].' </label>
																		'.$iso_eta_actual_add.'
																	</div>
																</div>
															</div>
															
															<div class="row">
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_DATE_AVAIL'].'</label><br/>
																		<div class="input-group date">
																			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																			<input type="text" class="form-control edit_delivery_date" value="'.$iso_date_available_add.'" id="iso_date_available_add">
																		</div>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="ord_sum_label">'.$lang['LOG_AUTHORIZED_BY'].'</label><br/>
																		<input type="text" class="form-control" value="'.$iso_positioning_add.'" id="iso_positioning_add">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_LOADING_FROM'].'</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" value="'.$con_load_date_from_add.'" id="con_load_date_from_add">
												</div>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_LOADING_TO'].'</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" value="'.$con_load_date_to_add.'" id="con_load_date_to_add">
												</div>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_SUPERVISOR_CHARGER'].'</label><br/>
												<select id="badd_loading_manager_id" class="form-control">
													<option value="0">---</option>
													'.$loading_manager_add.'
												</select>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_QM_COMP'].'</label><br/>
												'.$qm_contact_name_add.'
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_MULTIPLE_LOADING_AGT'].'</label><br/>
												<div class="pull-left">
													<div class="i-checks"><label> '.$lang['LOG_NO'].' <input type="radio" '.$ids_agent_no_add.' value="0" name="ids_agent" class="ids_agent_radio_add"> </label></div>
												</div>
												<div class="pull-left" style="margin-left:10px;">
													<div class="i-checks"><label> '.$lang['LOG_YES'].' <input type="radio" '.$ids_agent_yes_add.' value="1" name="ids_agent" class="ids_agent_radio_add"> </label></div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12">
											<label>'.$lang['LOG_CONTAINER_LIST'].'</label>
											<table class="table table-striped table-hover" style="font-size:13px; margin-top:15px;">
												<tbody id="container_list_add">
													'.$list_container_add.'
												</tbody>
											</table>
										</div>
									</div>
									
									<div class="row no-margins">
										<div class="panel panel-primary">
											<div class="panel-heading" role="tab" id="loading_documents_add">
												<h4 class="panel-title">
													<a role="button" data-toggle="collapse" href="#collapseLoadingDocs_add" aria-expanded="true" aria-controls="collapseLoadingDocs" class="trigger collapsed"> '.$lang['LOG_LOADING_STATUS'].' </a>
												</h4>
											</div>
					
											<div id="collapseLoadingDocs_add" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="loading_documents_add">
												<div class="panel-body">
													<div class="tabs-container">
														<div class="row">
															<div class="col-md-4">
																<label class="ord_sum_label">'.$lang['LOG_TT_CONT_LOADED'].'</label><br/>
																<input id="no_con_shipped_add" class="form-control" value="'.$no_con_shipped_add.'" type="number" min="0" />
															</div>
															
															<div class="col-md-4">
																<label class="ord_sum_label">'.$lang['LOG_LOADING_COMPLET'].'</label><br/>
																<select id="con_end_state_add" class="form-control">
																	'.$sel_loading_complete_add.'
																</select>
															</div>
															
															<div class="col-md-4">
																<label class="ord_sum_label">'.$lang['LOG_CONT_QM_BY'].'</label><br/>
																<input value="'.$ids_person_id_add.'" type="hidden" id="ids_person_id_add" />
																'.$ids_person_name_add.'
															</div>
															
															<div class="col-md-8">
																<div class="form-group">
																	<label class="ord_sum_label">'.$lang['LOG_NOTE_BY_CONT_LOADING_QM'].'</label><br/>
																	<textarea class="form-control" id="con_loading_note_add" cols="40" rows="2">'.$loading_note_add.'</textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-8">
											'.$container_modify_by_add.'
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12 '.$oceanAdd_cu.'" id="editContBtn_add">
											<button class="btn btn-success pull-right" id="containerEditBTN_add" onclick="show_container_editBtn_add(\''.$id_con_booking.'\',\''.$ord_schedule_id.'\');" style="margin-top:10px;" type="button" disabled><i class="fa fa-edit"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="panel panel-primary '.$shipDoc_r.'" id="freight_shipping_add">
						<div class="panel-heading" role="tab" id="headingShipping2">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseShipping2" aria-expanded="true" aria-controls="collapseShipping2" class="trigger collapsed text-uppercase"> '.$lang['LOG_SHIP_DOCS'].' '.$shipment_number.' </a>
								'.$doc_addendum3.'
							</h4>
						</div>
						
						<div id="collapseShipping2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingShipping2">
							<div class="panel-body">
								<div class="tabs-container">
									<div class="row no-margins">
										<div style="font-weight:bold;">'.$lang['LOG_SAMPLES_LAB'].'</div>
										<div class="col-md-4">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_DHL_TRAKING_N'].'</label><br/>
												<input type="text" class="form-control" value="'.$lab_awb_no.'" disabled id="lab_awb_no_add">
											</div>
										</div>
										
										<div class="col-md-4">  
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_DHL_DATE_SENT'].'</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" disabled value="'.$lab_cus_awb_date.'" id="lab_cus_awb_date_add">
												</div>
											</div>
										</div>
										
										<div class="col-md-4">  
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_LAB'].'</label><br/>
												<select class="form-control" disabled id="lab_contact_id_add">
													<option value="">--</option>
													'.$lab_contact_list_add.'
												</select>
											</div>
										</div>
									</div>
									
									<div class="row no-margins">
										<div style="font-weight:bold;">'.$lang['LOG_SAMPLES_CUS'].'</div>
										<div class="col-md-4">
											<div class="form-group">
												<input type="text" class="form-control" value="'.$cus_awb_no.'" disabled id="cust_awb_no_add">
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" disabled value="'.$cus_awb_date.'" id="cus_awb_date_add">
												</div>
											</div>
										</div>
									</div>
								
									<div class="row no-margins">
										<div style="font-weight:bold;">'.$lang['LOG_SHIP_DOCS'].'</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_SHIP_DOC_DHL'].'</label><br/>
												<input type="text" class="form-control" disabled value="'.$fa_awb_no_add.'" id="fa_awb_no_add">
											</div>
										</div>
										
										<div class="col-md-6">  
											<div class="form-group">
												<label class="ord_sum_label">'.$lang['LOG_SHIP_DOC_SENT'].'</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" disabled value="'.$fa_awb_date_add.'" id="fa_awb_date_add">
												</div>
											</div>
										</div>
									</div>
									
									<div class="row no-margins">
										<div class="col-md-6">
											<div class="form-group">
												<label class="ord_sum_label">B/L Number</label><br/>
												<input type="text" class="form-control" disabled value="'.$bl_number_add.'" id="bl_number_add">
											</div>
										</div>
										
										<div class="col-md-6">  
											<div class="form-group">
												<label class="ord_sum_label">Loading Date</label><br/>
												<div class="input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control edit_delivery_date" disabled value="'.$pol_etd_actual_add.'" id="pol_etd_actual_add">
												</div>
											</div>
										</div>
									</div>
									
									<div class="row no-margins">
										<div class="col-md-12 '.$shipDoc_u.'" id="editShippingDoc_add">
											<button class="btn btn-success pull-right" id="shippingDocEditBTN_add" onclick="show_shippingdoc_editBtn_add(\''.$req_doc_add.'\',\''.$ord_schedule_id.'\',\''.$ord_con_list_id.'\',\''.$id_con_booking.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>';
				
			} else {
				$booking_addendum=''; $req_add='';
			}
			
			// Onward rights
			$onward_read = $_GET['onward_read'];
			$onward_update = $_GET['onward_update'];
			if($onward_read == 1){ $onward_r=''; }else{ $onward_r='hide'; }
			if($onward_update == 1){ $onward_u=''; }else{ $onward_u='hide'; }
			
			// Onward Container rights
			$onwardCont_read = $_GET['onwardCont_read'];
			$onwardCont_update = $_GET['onwardCont_update'];
			$onwardCont_create = $_GET['onwardCont_create'];
			if($onwardCont_read == 1){ $onward_cr=''; }else{ $onward_cr='hide'; }
			if($onwardCont_update == 1){ $onward_cu=''; }else{ $onward_cu='hide'; }
			if($onwardCont_create == 1){ $onward_cc=''; }else{ $onward_cc='hide'; }
			
			// Logistic Onward Invoice rights
			$onwardInvoice_read = $_GET['onwardInvoice_read'];
			$onwardInvoice_update = $_GET['onwardInvoice_update'];
			$onwardInvoice_create = $_GET['onwardInvoice_create'];
			if($onwardInvoice_read == 1){ $onwardInv_cr=''; }else{ $onwardInv_cr='hide'; }
			if($onwardInvoice_update == 1){ $onwardInv_cu=''; }else{ $onwardInv_cu='hide'; }
			if($onwardInvoice_create == 1){ $onwardInv_cc=''; }else{ $onwardInv_cc='hide'; }
			
			if($card3 == 1){
				$sql_onward = "Select * From v_con_booking where ord_schedule_id=$ord_schedule_id AND booking_segment=3";
		
				$rs_onward = pg_query($conn, $sql_onward);
				$row_onward = pg_fetch_assoc($rs_onward);
				
				if($row_onward){ $req_onward='update'; } else { $req_onward='add'; }
				
				$sql_container_onward = "Select * From v_booking_conlist where ord_schedule_id=$ord_schedule_id  Order by cus_con_ref1";
				$rs_container_onward = pg_query($conn, $sql_container_onward);
				
				$list_container_onward = '';
				$list_container_disposition_onward = '';
				
				while ($row_container_onward = pg_fetch_assoc($rs_container_onward)) {
					if($row_container_onward['task_done']==2){
						$loading_onward=' - <span class="text-navy">'.$lang['LOG_LOADING'].'</span>';
					} else 
					if($row_container_onward['task_done']==1){
						$loading_onward=' - <span class="text-success">'.$lang['LOG_LOADING_COMPLET'].'</span>';
					} else {
						$loading_onward='';
					}
				
					$list_container_onward .= '<tr><td>  
							<a href="javascript:loadingForm2(\''. $row_container_onward['id_con_list'] .'\',\''. $row_container_onward['id_ord_loading_item'] .'\');" class="reference_nr">
								'. $row_container_onward['cus_con_ref1'] . '
							</a>
						</td>
						
						<td> '.$row_container_onward['container_nr']. $loading_onward .'</td>  
						<td> '. $row_container_onward['tare'] . ' </td>  
						<td> '. $row_container_onward['vgm_weight'] . ' </td>    
						<td> '. $row_container_onward['gross_weight_arrival'] . ' </td>    
						<td> '. $row_container_onward['vgm_delivery'] . ' </td>    
						<td> '. $row_container_onward['vgm_diff'] . ' </td>   
						
						<td style="width:60px">
							<a href="#" onclick="loadingForm2(\''. $row_container_onward['id_con_list'] .'\',\''. $row_container_onward['id_ord_loading_item'] .'\');"><i class="fa fa-eye"></i></a>
							<span class="containers_action_tb_onward">
								&nbsp;<a href="#" class="'.$onward_cc.' editContainer" onclick="edit_container_onward(\''.$row_container_onward['id_con_list'].'\',\''. $ord_schedule_id .'\',\''. $row_container_onward['vgm_weight'] . '\');"><i class="fa fa-pen-square"></i></a>
							</span>
						</td>
					</tr>';
					
					
					$list_container_disposition_onward .= '<tr><td>  
							<a href="javascript:loadingForm2(\''. $row_container_onward['id_con_list'] .'\',\''. $row_container_onward['id_ord_loading_item'] .'\');" class="reference_nr">
								'. $row_container_onward['cus_con_ref1'] . '
							</a>
						</td>
						
						<td> '.$row_container_onward['container_nr']. $loading_onward .'</td>   
						<td> '. $row_container_onward['dispo_order_nr'] . ' </td>    
						<td> '. $row_container_onward['dispo_delivery_nr'] . ' </td>    
						<td> '. $row_container_onward['terminal_date'] . ' </td>    
						<td> '. $row_container_onward['terminal_dispo'] . ' </td>    
						<td> '. $row_container_onward['dispo_hour'] . ' </td>
						
						<td style="width:60px">
							<a href="#" onclick="loadingForm2(\''. $row_container_onward['id_con_list'] .'\',\''. $row_container_onward['id_ord_loading_item'] .'\');"><i class="fa fa-eye"></i></a>
							<span class="containers_dispo_action_tb_onward">
								&nbsp;<a href="#" class="'.$onward_cc.' editContainer" onclick="edit_onward_container_disposition(\''.$row_container_onward['id_con_list'].'\',\''. $ord_schedule_id .'\');"><i class="fa fa-pen-square"></i></a>
							</span>
						</td>
					</tr>';
				}
				
				if($req_onward=='update'){
					$id_con_booking_onward=$row_onward['id_con_booking'];
					$booking_nr_onward=$row_onward['booking_nr'];
					$etd_onward=$row_onward['etd'];
					$eta_onward=$row_onward['eta'];
					$vessel_name_onward=strtoupper($row_onward['vessel_name']);
					$vessel_mmsi_id_onward=$row_onward['vessel_mmsi_id'];
					$pol_onward=$row_onward['pol_id'];
					$pod_onward=$row_onward['bpod_id'];
					$ord_order_id_onward=$row_onward['ord_order_id'];
					
					$edit_btn_onward='<a href="#" style="color:#FFF;" onclick="edit_onward_carriage(\''.$row_onward['id_con_booking'].'\',\''.$ref_num.'\',\''.$ord_schedule_id.'\',\'edit\');"> <i class="fa fa-edit"></i> </a>';
					
				} else {
					$id_con_booking_onward='';
					$booking_nr_onward='';
					$etd_onward='';
					$eta_onward='';
					$vessel_name_onward='';
					$vessel_mmsi_id_onward='';
					$pol_onward='';
					$pod_onward='';
					
					$edit_btn_onward='';
					$ord_order_id_onward='';
				}
				
				$sql_onward_inv = "Select * From v_con_booking where ord_schedule_id=$ord_schedule_id AND booking_segment=1";
		
				$rs_onward_inv = pg_query($conn, $sql_onward_inv);
				$row_onward_inv = pg_fetch_assoc($rs_onward_inv);
				
				$b_ord_order_id=$row_onward_inv['ord_order_id'];
				$packlist_status=$row_onward_inv['packlist_status'];
				$inv1_status=$row_onward_inv['inv1_status'];
				$inv2_status=$row_onward_inv['inv2_status'];
				$inv3_status=$row_onward_inv['inv3_status'];
				
				$pipeline_sched_id_onward=$row_onward_inv['pipeline_sched_id'];
				
				if($pipeline_sched_id_onward>296){ $onwardPkl_cr=''; }else{ $onwardPkl_cr='hide'; }
				if($pipeline_sched_id_onward==300){ $onwardArc_cr='disabled'; }else{ $onwardArc_cr=''; }
			
				// Onward POL
				$sql_pol_onward = "select id_townport, portname from ord_towns_port where port_type_id=274";
				$rs_pol_onward = pg_query($conn, $sql_pol_onward);
				
				$list_pol_onward = '';
				while ($row_pol_onward = pg_fetch_assoc($rs_pol_onward)) {
					if($pol_onward == $row_pol_onward['id_townport']){
						$sel_onw_pol='selected="selected"';
					} else { $sel_onw_pol=''; }
					$list_pol_onward .= '<option value="'.$row_pol_onward['id_townport'].'??'.$row_pol_onward['portname'].'" '.$sel_onw_pol.'>'.$row_pol_onward['portname'].'</option>';
				}
				
				// Onward POD
				$sql_pod_onward = "select id_townport, portname from ord_towns_port where port_type_id=275";
				$rs_pod_onward = pg_query($conn, $sql_pod_onward);
				
				$list_pod_onward = '';
				while ($row_pod_onward = pg_fetch_assoc($rs_pod_onward)) {
					if($pod_onward == $row_pod_onward['id_townport']){
						$sel_onw_pod='selected="selected"';
					} else { $sel_onw_pod=''; }
					$list_pod_onward .= '<option value="'.$row_pod_onward['id_townport'].'??'.$row_pod_onward['portname'].'"  '.$sel_onw_pod.'>'.$row_pod_onward['portname'].'</option>';
				}
				
				$mail3 = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;" onclick="eMailForm(\''.$ord_schedule_id.'\',\'logistics\',\'3\');"><i class="fa fa-envelope"></i></a>';
				
				if($doc_right == 1){
					$doc_onward = '<a href="#" class="pull-right" style="margin-left:10px;" onclick="showDocList(\''.$ord_order_id_onward.'\',\''.$ord_schedule_id.'\',\'logistics\',\'\');"><i class="fa fa-file-text"></i></a>';

					$doc_onward1 = $mail3.$doc_onward;
					$doc_onward2 = $mail3.$doc_onward;
					$doc_onward3 = $mail3.$doc_onward;
					$doc_onward4 = $mail3.$doc_onward;
					
				} else {
					$doc_onward1 = '';
					$doc_onward2 = '';
					$doc_onward3 = '';
					$doc_onward4 = '';
				}
				
				$sql_display="SELECT * FROM public.v_con_booking_display WHERE ord_schedule_id = $ord_schedule_id and booking_segment=1";
				$rs_display = pg_query($conn, $sql_display);
				$row_display = pg_fetch_assoc($rs_display);
				
				$total_vgm_weight = $row_display['total_vgm_weight'];
				$proposal_price = $row_display['ship_sales_value_tone'];
				$inv1_amount = $row_display['inv1_amount'];
				$inv1_date = $row_display['inv1_date'];
				$inv1_by_name = $row_display['inv1_by_name'];
				
				$vgm_deliver_total = $row_display['vgm_deliver_total'];
				$vgm_diff_total = $row_display['vgm_diff_total'];
				$inv2_amount = $row_display['inv2_amount'];
				$inv2_date = $row_display['inv2_date'];
				$inv2_by_name = $row_display['inv2_by_name'];
				
				$inv3_amount = $row_display['inv3_amount'];
				$inv3_date = $row_display['inv3_date'];
				$inv3_by_name = $row_display['inv3_by_name'];
				
				if($packlist_status==1){ $btn_packinglist='disabled'; }else{ $btn_packinglist=''; }
				
				/* 22/05/2018
				 Dim buttons when document is actif  
				if($inv1_status==1){ $btn_inv1_status='disabled'; }else{ $btn_inv1_status=''; }
				if($inv2_status==1){ $btn_inv2_status='disabled'; }else{ $btn_inv2_status=''; }  */
				
				
				// Invoice 1 button status
				$sql_inv1="SELECT active FROM public.ord_document WHERE ord_schedule_id = $ord_schedule_id AND ord_order_id = $b_ord_order_id AND doc_type_id = 16 AND active=1";
				$rs_inv1 = pg_query($conn, $sql_inv1);
				$row_inv1 = pg_fetch_assoc($rs_inv1);
				
				if($row_inv1['active']==1){ $btn_inv1_status='disabled'; } else { $btn_inv1_status=''; }
				
				// Invoice 2 button status
				$sql_inv2="SELECT active FROM public.ord_document WHERE ord_schedule_id = $ord_schedule_id AND ord_order_id = $b_ord_order_id AND doc_type_id = 176 AND active=1";
				$rs_inv2 = pg_query($conn, $sql_inv2);
				$row_inv2 = pg_fetch_assoc($rs_inv2);
				
				if($row_inv2['active']==1){ $btn_inv2_status='disabled'; } else { $btn_inv2_status=''; }
				
				$sql_total = "select c.proposal_price, h.vgm_diff_total,  
				to_char((h.vgm_diff_total*c.proposal_price),'999G999D99') total_diff
				 from ord_con_loading_header h, v_schedule_calc c
				 where h.ord_schedule_id=$ord_schedule_id
				and c.id_ord_schedule=h.ord_schedule_id";
				
				$rs_total = pg_query($conn, $sql_total);
				$row_total = pg_fetch_assoc($rs_total);

				$total_diff = $row_total['total_diff'];	
	
				$onward_carriage='<div class="collapse-group">
					<div class="panel panel-primary '.$onward_r.'" id="freight_container">
						<div class="panel-heading" role="tab" id="headingCarriage">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseCarriage" aria-expanded="true" aria-controls="collapseCarriage" class="trigger collapsed text-uppercase"> '.$lang['LOG_ONWARD_BOOKING'].' '.$shipment_number.' </a>
								'.$doc_onward1.'
								<span class="pull-right '.$onward_u.'" id="EditOnwardBtnID">'.$edit_btn_onward.'</span>
							</h4>
						</div>
						
						<div id="collapseCarriage" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingCarriage">
							<div class="panel-body">
								<div class="tabs-container" id="onwardTabID">
									<div class="col-md-6">
										<div class="form-group"><label>'.$lang['LOG_BL'].' </label>
											<input type="text" id="booking_nr_onward" value="'.$booking_nr_onward.'" class="form-control" />
										</div>
									
										<div class="form-group">
											<label>'.$lang['LOG_VESSEL_NAME'].' <span style="color:red;">*</span> </label>
											<div class="input-group">
												<input type="text" id="vessel_name_onward" style="text-transform: uppercase" value="'.$vessel_name_onward.'" class="form-control" /> 
												<span class="input-group-btn"> 
													<input type="button" onclick="checkMMSI(\'onward\');" data-toggle="tooltip" data-placement="top" title="Get MMSI" class="btn btn-primary" value="?" />
												</span>
											</div>
										</div>
										
										<div class="form-group"><label>'.$lang['LOG_VESSEL_MMSI'].' </label>
											<input type="text" id="vessel_mmsi_id_onward" value="'.$vessel_mmsi_id_onward.'" class="form-control" />
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_PORT_OF_LOADING'].' </label>
											<select id="pol_onward" class="form-control">
												<option>-- '.$lang['LOG_SEL_PORT'].' --</option>
												'.$list_pol_onward.'
											</select>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group"><label>'.$lang['CONTRACT_ETD'].' <span style="color:red;">*</span></label>
											<div class="input-group date" style="width:160px;">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control edit_delivery_date" value="'.$etd_onward.'" id="etd_onward">
											</div>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_PORT_OF_DISCHARGE'].' </label>
											<select id="pod_onward" class="form-control">
												<option>-- '.$lang['LOG_SEL_PORT'].' --</option>
												'.$list_pod_onward.'
											</select>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_ETA'].' <span style="color:red;">*</span></label>
											<div class="input-group date" style="width:160px;">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control edit_delivery_date" value="'.$eta_onward.'" id="eta_onward">
											</div>
										</div>
									</div>
									
									<div class="col-md-12 '.$onward_u.'" id="EditOnwardBtnID2">
										<button class="btn btn-success pull-right" id="EditOnwardBtn" onclick="show_onward_editBtn(\''.$id_con_booking_onward.'\',\''.$ord_schedule_id.'\',\''.$ref_num.'\',\''.$req_onward.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
									</div>
								</div>	
							</div>
						</div>
					</div>
					
					<div class="panel panel-primary '.$onward_cr.'" id="freight_container_onward">
						<div class="panel-heading" role="tab" id="headingCarriageContainer">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseCarriageContainer" aria-expanded="true" aria-controls="collapseCarriageContainer" class="trigger collapsed text-uppercase"> '.$lang['LOG_CONTAINER'].' '.$shipment_number.' </a>
								'.$doc_onward2.'
							</h4>
						</div>
						
						<div id="collapseCarriageContainer" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingCarriageContainer">
							<div class="panel-body">
								<div class="tabs-container">
									<table class="table table-striped table-hover" style="font-size:13px;">
										<thead>
											<tr>
												<th>'.$lang['LOG_NO'].'</th>
												<th>'.$lang['LOG_CONTAINER_NUMB'].'</th>
												<th>'.$lang['LOG_TARE'].'</th>
												<th>'.$lang['LOG_SHIP_WEIGHT'].'</th>
												<th>'.$lang['LOG_GROSS_WEIGHT'].'</th>
												<th>'.$lang['LOG_ARR_WEIGHT'].'</th>
												<th>'.$lang['LOG_DIFF'].'</th>
												<th>'.$lang['CONTRACT_EDIT'].'</th>
											</tr>
										</thead>
										<tbody id="container_list_carr">
											'.$list_container_onward.'
										</tbody>
									</table>
									
									<div class="row hide">
										<div class="col-md-6">
											<input class="btn btn-success" onclick="addOnward(\''.$ord_schedule_id.'\');" style="margin-top:10px;" value="'.$lang['LOG_SPLIT_CONT'].'" disabled />
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12 '.$onward_cu.'" id="editContBtn_onward">
											<button class="btn btn-success pull-right" id="containerEditBTN_onward" onclick="show_container_editBtn_onward(\''.$id_con_booking_onward.'\',\''.$ord_schedule_id.'\');" style="margin-top:10px;" type="button" disabled><i class="fa fa-edit"></i></button>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
					
					<div class="panel-group" id="accordion_add1">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion_add1" href="#collapseConPositioning_add1" class="text-uppercase">'.$lang['LOG_CONTAINER_DISPO'].' '.$shipment_number.'</a>
									'.$doc_onward3.'
								</h4>
							</div>
							
							<div id="collapseConPositioning_add1" class="panel-collapse collapse">
								<div class="panel-body">
									<table class="table table-striped table-hover" style="font-size:13px;">
										<thead>
											<tr>
												<th>'.$lang['LOG_NO'].'</th>
												<th>'.$lang['LOG_CONTAINER_NUMB'].'</th>
												<th>'.$lang['LOG_ORDER_N'].'</th>
												<th>'.$lang['LOG_DELIVERY_N'].'</th>
												<th>'.$lang['LOG_TERMINAL'].'</th>
												<th>'.$lang['LOG_PLAN'].'</th>
												<th>'.$lang['LOG_HOUR'].'</th>
												<th>'.$lang['CONTRACT_EDIT'].'</th>
											</tr>
										</thead>
										<tbody id="container_dispo">
											'.$list_container_disposition_onward.'
										</tbody>
									</table>
									
									<div class="row">
										<div class="col-md-12 '.$onward_cu.'" id="editContDispoBtn_onward">
											<button class="btn btn-success pull-right" id="containerDispoEditBTN_onward" onclick="show_container_dispo_editBtn_onward(\''.$id_con_booking_onward.'\',\''.$ord_schedule_id.'\');" style="margin-top:10px;" type="button" disabled><i class="fa fa-edit"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				
					<div class="panel panel-primary" id="freight_container_onward">
						<div class="panel-heading" role="tab" id="headingFinalDocument">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseFinalDocument" aria-expanded="true" aria-controls="collapseFinalDocument" class="trigger collapsed text-uppercase"> '.$lang['LOG_FNAL_DOCS'].' '.$shipment_number.' </a>
								'.$doc_onward4.'
							</h4>
						</div>
					
						<div id="collapseFinalDocument" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFinalDocument">
							<div class="panel-body">
								<div class="tabs-container">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group '.$onwardInv_cr.$onwardPkl_cr.'">
												<input type="button" onclick="packingList(\'0\',\''.$ord_schedule_id.'\',\'importer\');" '.$btn_packinglist.' class="btn btn-warning" id="btn_packinglist" value="'.$lang['LOG_PACKING_LIST_BTN'].'" />
											</div>
										</div>
								
										<div class="col-md-12">
											<div class="form-group '.$onwardInv_cr.'">
												<input type="button" onclick="invoice1(\'0\',\''.$ord_schedule_id.'\',\''.$proposal_price.'\');" '.$btn_inv1_status.' class="btn btn-warning" id="btn_inv1_status" value="'.$lang['LOG_INVOICE_1_BTN'].'" />
											</div>
									
											<table class="table table-bordered table-hover" style="font-size:13px;">
												<thead>
													<tr>
														<th>'.$lang['LOG_SHIP_WEIGHT'].'</th>
														<th>'.$lang['LOG_PRICE_MT'].'</th>
														<th>'.$lang['LOG_TT_INVOICE'].'</th>
														<th>'.$lang['LOG_DATE'].'</th>
														<th>'.$lang['LOG_BY'].'</th>
													</tr>
												</thead>
												<tbody id="onward_invoice_1">
													<tr>
														<td>'.$total_vgm_weight.'</td>
														<td>'.$proposal_price.'</td>
														<td>'.$inv1_amount.'</td>
														<td>'.$inv1_date.'</td>
														<td>'.$inv1_by_name.'</td>
													</tr>
												</tbody>
											</table>
										</div>
										
										<div class="col-md-12">
											<div class="form-group '.$onwardInv_cr.'">
												<input type="button" onclick="invoice_customs(\'0\',\''.$ord_schedule_id.'\',\''.$proposal_price.'\');" '.$btn_inv1_status.' class="btn btn-warning" id="btn_invC_status" value="'.$lang['LOG_INVOICE_1_CUSTOM_BTN'].'" />
											</div>
									
											<table class="table table-bordered table-hover" style="font-size:13px;">
												<thead>
													<tr>
														<th>'.$lang['LOG_SHIP_WEIGHT'].'</th>
														<th>'.$lang['LOG_PRICE_MT'].'</th>
														<th>'.$lang['LOG_TT_INVOICE'].'</th>
														<th>'.$lang['LOG_DATE'].'</th>
														<th>'.$lang['LOG_BY'].'</th>
													</tr>
												</thead>
												<tbody id="onward_invoice_1">
													<tr>
														<td>'.$total_vgm_weight.'</td>
														<td>'.$proposal_price.'</td>
														<td>'.$inv1_amount.'</td>
														<td>'.$inv1_date.'</td>
														<td>'.$inv1_by_name.'</td>
													</tr>
												</tbody>
											</table>
										</div>
							
										<div class="col-md-12">
											<div class="form-group '.$onwardInv_cr.'">
												<input type="button" onclick="invoice2_option(\'0\',\''.$ord_schedule_id.'\',\''.$proposal_price.'\',\''.$total_diff.'\');" '.$btn_inv2_status.' class="btn btn-warning" id="btn_inv2_status" value="'.$lang['LOG_INVOICE_2_BTN'].'" />
											</div>
									
											<table class="table table-bordered table-hover" style="font-size:13px;">
												<thead>
													<tr>
														<th>'.$lang['LOG_ARR_WEIGHT'].'</th>
														<th>'.$lang['LOG_DIFF_WGHT'].'</th>
														<th>'.$lang['LOG_PRICE_MT'].'</th>
														<th>'.$lang['LOG_TT_INVOICE'].'</th>
														<th>'.$lang['LOG_DATE'].'</th>
														<th>'.$lang['LOG_BY'].'</th>
													</tr>
												</thead>
												<tbody id="onward_invoice_2">
													<tr>
														<td>'.$vgm_deliver_total.'</td>
														<td>'.$vgm_diff_total.'</td>
														<td>'.$proposal_price.'</td>
														<td>'.$inv2_amount.'</td>
														<td>'.$inv2_date.'</td>
														<td>'.$inv2_by_name.'</td>
													</tr>
												</tbody>
											</table>
										</div>
									
										<div class="col-md-12">
											<div class="form-group '.$onwardInv_cr.'">
												<input type="button" onclick="" class="btn btn-warning" disabled value="'.$lang['LOG_INVOICE_3_BTN'].'" />
											</div>
									
											<table class="table table-bordered table-hover" style="font-size:13px;">
												<thead>
													<tr>
														<th>'.$lang['LOG_TT_INVOICE_POST_SHIP_C'].'</th>
														<th>'.$lang['LOG_DATE'].'</th>
														<th>'.$lang['LOG_BY'].'</th>
													</tr>
												</thead>
												<tbody id="onward_invoice_3">
													<tr>
														<td>'.$inv3_amount.'</td>
														<td>'.$inv3_date.'</td>
														<td>'.$inv3_by_name.'</td>
													</tr>
												</tbody>
											</table>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
												<input type="button" id="onw_archive" onclick="archive(\''. $ord_schedule_id .'\');" class="btn btn-danger" '.$onwardArc_cr.' value="'.$lang['LOG_ARCHIVE_BTN'].'" />
												
												<input type="button" id="onw_accounting" onclick="accounting(\''. $ord_schedule_id .'\');" class="btn btn-success" '.$onwardArc_cr.' value="'.$lang['LOG_ACCOUNTING_BTN'].'" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
				
			} else {
				$onward_carriage=''; $req_onward='';
			}
			
			// Onward add rights
			$onwardAdd_read = $_GET['onwardAdd_read'];
			$onwardAdd_update = $_GET['onwardAdd_update'];
			if($onwardAdd_read == 1){ $onwardAdd_r=''; }else{ $onwardAdd_r='hide'; }
			if($onwardAdd_update == 1){ $onwardAdd_u=''; }else{ $onwardAdd_u='hide'; }
			
			// Onward add Container rights
			$onwardAddCont_read = $_GET['onwardAddCont_read'];
			$onwardAddCont_update = $_GET['onwardAddCont_update'];
			$onwardAddCont_create = $_GET['onwardAddCont_create'];
			if($onwardAddCont_read == 1){ $onwardAdd_cr=''; }else{ $onwardAdd_cr='hide'; }
			if($onwardAddCont_update == 1){ $onwardAdd_cu=''; }else{ $onwardAdd_cu='hide'; }
			if($onwardAddCont_create == 1){ $onwardAdd_cc=''; }else{ $onwardAdd_cc='hide'; }
			
			// Logistic Onward Add Invoice rights
			$onwardInvoiceAdd_read = $_GET['onwardInvoiceAdd_read'];
			$onwardInvoiceAdd_update = $_GET['onwardInvoiceAdd_update'];
			$onwardInvoiceAdd_create = $_GET['onwardInvoiceAdd_create'];
			if($onwardInvoiceAdd_read == 1){ $onwardInvAdd_cr=''; }else{ $onwardInvAdd_cr='hide'; }
			if($onwardInvoiceAdd_update == 1){ $onwardInvAdd_cu=''; }else{ $onwardInvAdd_cu='hide'; }
			if($onwardInvoiceAdd_create == 1){ $onwardInvAdd_cc=''; }else{ $onwardInvAdd_cc='hide'; }
			
			if($card4 == 1){
				
				$sql_onward_add = "Select * From v_con_booking where ord_schedule_id=$ord_schedule_id AND booking_segment=4";
		
				$rs_onward_add = pg_query($conn, $sql_onward_add);
				$row_onward_add = pg_fetch_assoc($rs_onward_add);
				
				if($row_onward_add){ $req_onward_add='update'; } else { $req_onward_add='add'; }
				
				$sql_container_onward_add = "Select * From v_booking_conlist where ord_schedule_id= $ord_schedule_id  Order by cus_con_ref1";
				
				$rs_container_onward_add = pg_query($conn, $sql_container_onward_add);
				
				$list_container_onward_add = '';
				
				while ($row_container_onward_add = pg_fetch_assoc($rs_container_onward_add)) {
					if($row_container_onward_add['task_done']==2){
						$loading_onward_add=' - <span class="text-navy">'.$lang['LOG_LOADING'].'</span>';
					} else 
					if($row_container_onward_add['task_done']==1){
						$loading_onward_add=' - <span class="text-success">'.$lang['LOG_LOADING_COMPLET'].'</span>';
					} else {
						$loading_onward_add='';
					}
				
					$list_container_onward_add .= '<tr><td>  
							<a href="javascript:loadingForm2(\''. $row_container_onward_add['id_con_list'] .'\',\''. $row_container_onward_add['id_ord_loading_item'] .'\');" class="reference_nr">
								'. $row_container_onward_add['cus_con_ref1'] . '
							</a>
						</td>
						
						<td> '.$row_container_onward_add['container_nr']. $loading_onward .'</td>  
						<td> '. $row_container_onward_add['tare'] . ' </td>  
						<td> '. $row_container_onward_add['vgm_weight'] . ' </td>    
						<td> '. $row_container_onward_add['gross_weight_arrival'] . ' </td>    
						<td> '. $row_container_onward_add['vgm_delivery'] . ' </td>    
						<td> '. $row_container_onward_add['vgm_diff'] . ' </td>   
						
						<td style="width:60px">
							<a href="#" onclick="loadingForm2(\''. $row_container_onward_add['id_con_list'] .'\',\''. $row_container_onward_add['id_ord_loading_item'] .'\');"><i class="fa fa-eye"></i></a>
							<span class="containers_action_tb_onward_add">
								&nbsp;<a href="#" class="'.$onwardAdd_cc.' editContainer" onclick="edit_container_onward(\''.$row_container_onward_add['id_con_list'].'\',\''. $ord_schedule_id .'\',\''. $row_container_onward_add['vgm_weight'] . '\');"><i class="fa fa-pen-square"></i></a>
							</span>
						</td>
					</tr>';
					
					
					$list_container_disposition_onward_add .= '<tr><td>  
							<a href="javascript:loadingForm2(\''. $row_container_onward_add['id_con_list'] .'\',\''. $row_container_onward_add['id_ord_loading_item'] .'\');" class="reference_nr">
								'. $row_container_onward_add['cus_con_ref1'] . '
							</a>
						</td>
						
						<td> '.$row_container_onward_add['container_nr']. $loading_onward .'</td>   
						<td> '. $row_container_onward_add['dispo_order_nr'] . ' </td>    
						<td> '. $row_container_onward_add['dispo_delivery_nr'] . ' </td>    
						<td> '. $row_container_onward_add['terminal_date'] . ' </td>    
						<td> '. $row_container_onward_add['terminal_dispo'] . ' </td>    
						<td> '. $row_container_onward_add['dispo_hour'] . ' </td>
						
						<td style="width:60px">
							<a href="#" onclick="loadingForm2(\''. $row_container_onward_add['id_con_list'] .'\',\''. $row_container_onward_add['id_ord_loading_item'] .'\');"><i class="fa fa-eye"></i></a>
							<span class="containers_dispo_action_tb_onward_add">
								&nbsp;<a href="#" class="'.$onwardAdd_cc.' editContainer" onclick="edit_onward_container_disposition(\''.$row_container_onward_add['id_con_list'].'\',\''. $ord_schedule_id .'\');"><i class="fa fa-pen-square"></i></a>
							</span>
						</td>
					</tr>';
				}
				
				
				if($req_onward_add=='update'){
					$id_con_booking_onward_add=$row_onward_add['id_con_booking'];
					$booking_nr_onward_add=$row_onward_add['booking_nr'];
					$etd_onward_add=$row_onward_add['etd'];
					$eta_onward_add=$row_onward_add['eta'];
					$vessel_name_onward_add=strtoupper($row_onward_add['vessel_name']);
					$vessel_mmsi_id_onward_add=$row_onward_add['vessel_mmsi_id'];
					$pol_onward_add=$row_onward_add['pol_name'];
					$pod_onward_add=$row_onward_add['pod_name'];
					$ord_order_id_onward_add=$row_onward_add['ord_order_id'];
					
					$edit_btn_onward_add='<a href="#" style="color:#FFF;" onclick="edit_onward_carriage_add(\''.$row_onward_add['id_con_booking'].'\',\''.$ref_num.'\',\''.$ord_schedule_id.'\',\'edit\');"> <i class="fa fa-edit"></i> </a>';
					
				} else {
					$id_con_booking_onward_add='';
					$booking_nr_onward_add='';
					$etd_onward_add='';
					$eta_onward_add='';
					$vessel_name_onward_add='';
					$vessel_mmsi_id_onward_add='';
					$pol_onward_add='';
					$pod_onward_add='';
					
					$edit_btn_onward_add='';
					$ord_order_id_onward_add='';  
				}
				
				// Onward_add POL
				$sql_pol_onward_add = "select id_townport, portname from ord_towns_port where port_type_id=274";
				$rs_pol_onward_add = pg_query($conn, $sql_pol_onward_add);
				
				$list_pol_onward_add = '';
				while ($row_pol_onward_add = pg_fetch_assoc($rs_pol_onward_add)) {
					if($pol_onward_add == $row_pol_onward_add['portname']){
						$sel_onw_pol_add='selected="selected"';
					} else { $sel_onw_pol_add=''; }
					$list_pol_onward_add .= '<option value="'.$row_pol_onward_add['id_townport'].'??'.$row_pol_onward_add['portname'].'"'.$sel_onw_pol_add.'>'.$row_pol_onward_add['portname'].'</option>';
				}
				
				// Onward_add POD
				$sql_pod_onward_add = "select id_townport, portname from ord_towns_port where port_type_id=275";
				$rs_pod_onward_add = pg_query($conn, $sql_pod_onward_add);
				
				$list_pod_onward_add = '';
				while ($row_pod_onward_add = pg_fetch_assoc($rs_pod_onward_add)) {
					if($pod_onward_add == $row_pod_onward_add['portname']){
						$sel_onw_pod_add='selected="selected"';
					} else { $sel_onw_pod_add=''; }
					$list_pod_onward_add .= '<option value="'.$row_pod_onward_add['id_townport'].'??'.$row_pod_onward_add['portname'].'"'.$sel_onw_pod_add.'>'.$row_pod_onward_add['portname'].'</option>';
				}
				
				$mail4 = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;" onclick="eMailForm(\''.$ord_schedule_id.'\',\'logistics\',\'4\');"><i class="fa fa-envelope"></i></a>';
				
				if($doc_right == 1){
					$doc_onward_add = '<a href="#" class="pull-right" style="margin-left:10px;" onclick="showDocList(\''.$ord_order_id_onward_add.'\',\''.$ord_schedule_id.'\',\'logistics\',\'\');"><i class="fa fa-file-text"></i></a>';

					$doc_onward_add1 = $mail4.$doc_onward_add;
					$doc_onward_add2 = $mail4.$doc_onward_add;
					$doc_onward_add3 = $mail4.$doc_onward_add;
					
				} else {
					$doc_onward_add1 = '';
					$doc_onward_add2 = '';
					$doc_onward_add3 = '';
				}
		
				$carriage_addendum='<div class="collapse-group">
					<div class="panel panel-primary '.$onwardAdd_r.'" id="freight_container_onward_add">
						<div class="panel-heading" role="tab" id="headingCarriage2">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseCarriage2" aria-expanded="true" aria-controls="collapseCarriage2" class="trigger collapsed text-uppercase"> '.$lang['LOG_ONWARD_BOOKING'].' '.$shipment_number.' </a>
								'.$doc_onward_add1.'
								<span class="pull-right '.$onwardAdd_u.'" id="EditOnwardBtnID_add">'.$edit_btn_onward_add.'</span>
							</h4>
						</div>
						
						<div id="collapseCarriage2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingCarriage2">
							<div class="panel-body">
								<div class="tabs-container" id="onwardTabID_add">
									<div class="col-md-6">
										<div class="form-group"><label>'.$lang['LOG_BL'].' </label>
											<input type="text" id="booking_nr_onward_add" value="'.$booking_nr_onward_add.'" class="form-control" />
										</div>
										
										<div class="form-group">
											<label>'.$lang['LOG_VESSEL_NAME'].' <span style="color:red;">*</span> </label>
											<div class="input-group">
												<input type="text" id="vessel_name_onward_add" style="text-transform: uppercase" value="'.$vessel_name_onward_add.'" class="form-control" /> 
												<span class="input-group-btn"> 
													<input type="button" data-toggle="tooltip" data-placement="top" title="Get MMSI" onclick="checkMMSI(\'onward_add\');" class="btn btn-primary" value="?" />
												</span>
											</div>
										</div>
										
										<div class="form-group"><label>'.$lang['LOG_VESSEL_MMSI'].' </label>
											<input type="text" id="vessel_mmsi_id_onward_add" value="'.$vessel_mmsi_id_onward_add.'" class="form-control" />
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_PORT_OF_LOADING'].' </label>
											<select id="pol_onward_add" class="form-control">
												<option>-- '.$lang['LOG_SEL_PORT'].' --</option>
												'.$list_pol_onward_add.'
											</select>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group"><label>'.$lang['CONTRACT_ETD'].' <span style="color:red;">*</span></label>
											<div class="input-group date" style="width:160px;">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control edit_delivery_date" value="'.$etd_onward_add.'" id="etd_onward_add">
											</div>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_PORT_OF_DISCHARGE'].' </label>
											<select id="pod_onward_add" class="form-control">
												<option>-- '.$lang['LOG_SEL_PORT'].' --</option>
												'.$list_pod_onward_add.'
											</select>
										</div>
										
										<div class="form-group"><label>'.$lang['CONTRACT_ETA'].' <span style="color:red;">*</span></label>
											<div class="input-group date" style="width:160px;">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control edit_delivery_date" value="'.$eta_onward_add.'" id="eta_onward_add">
											</div>
										</div>
									</div>
									
									<div class="col-md-12 '.$onwardAdd_u.'" id="EditOnwardBtnID2_add">
										<button class="btn btn-success pull-right" id="EditOnwardBtn_add" onclick="show_onward_editBtn_add(\''.$id_con_booking_onward_add.'\',\''.$ord_schedule_id.'\',\''.$ref_num.'\',\''.$req_onward_add.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
									</div>
								</div>	
							</div>
						</div>
					</div>
					
					<div class="panel panel-primary '.$onwardAdd_cr.'" id="freight_container_onward_add2">
						<div class="panel-heading" role="tab" id="headingCarriageContainer2">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#collapseCarriageContainer_add" aria-expanded="true" aria-controls="collapseCarriageContainer_add" class="trigger collapsed text-uppercase"> '.$lang['LOG_CONTAINER'].' '.$shipment_number.' </a>
								'.$doc_onward_add2.'
							</h4>
						</div>
						
						<div id="collapseCarriageContainer_add" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingCarriageContainer2">
							<div class="panel-body">
								<div class="tabs-container">
									<table class="table table-striped table-hover" style="font-size:13px;">
										<thead>
											<tr>
												<th>'.$lang['LOG_NO'].'</th>
												<th>'.$lang['LOG_CONTAINER_NUMB'].'</th>
												<th>'.$lang['LOG_TARE'].'</th>
												<th>'.$lang['LOG_SHIP_WEIGHT'].'</th>
												<th>'.$lang['LOG_GROSS_WEIGHT'].'</th>
												<th>'.$lang['LOG_ARR_WEIGHT'].'</th>
												<th>'.$lang['LOG_DIFF'].'</th>
												<th>'.$lang['CONTRACT_EDIT'].'</th>
											</tr>
										</thead>
										<tbody id="container_list_carr_add">
											'.$list_container_onward_add.'
										</tbody>
									</table>
							
									<div class="row hide">
										<div class="col-md-12 '.$onwardAdd_cu.'" id="editContBtn_onward_add">
											<button class="btn btn-success pull-right" id="containerEditBTN_onward_add" onclick="show_container_editBtn_onward_add(\''.$id_con_booking_onward_add.'\',\''.$ord_schedule_id.'\');" style="margin-top:10px;" type="button" disabled><i class="fa fa-edit"></i></button>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
					
					<div class="panel-group" id="accordion_add2">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion_add2" href="#collapseConPositioning_add2" class="text-uppercase"> '.$lang['LOG_CONTAINER_DISPO'].' '.$shipment_number.' </a>
									'.$doc_onward_add3.'
								</h4>
							</div>
							<div id="collapseConPositioning_add2" class="panel-collapse collapse">
								<div class="panel-body">
									<table class="table table-striped table-hover" style="font-size:13px;">
										<thead>
											<tr>
												<th>'.$lang['LOG_NO'].'</th>
												<th>'.$lang['LOG_CONTAINER_NUMB'].'</th>
												<th>'.$lang['LOG_ORDER_N'].'</th>
												<th>'.$lang['LOG_DELIVERY_N'].'</th>
												<th>'.$lang['LOG_TERMINAL'].'</th>
												<th>'.$lang['LOG_PLAN'].'</th>
												<th>'.$lang['LOG_HOUR'].'</th>
												<th>'.$lang['CONTRACT_EDIT'].'</th>
											</tr>
										</thead>
										<tbody id="container_dispo">
											'.$list_container_disposition_onward_add.'
										</tbody>
									</table>
									
									<div class="row hide">
										<div class="col-md-12 '.$onwardAdd_cu.'" id="editContDispoBtn_onward_add">
											<button class="btn btn-success pull-right" id="containerDispoEditBTN_onward_add" onclick="show_container_editBtn_onward_add(\''.$id_con_booking_onward_add.'\',\''.$ord_schedule_id.'\');" style="margin-top:10px;" type="button" disabled><i class="fa fa-edit"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
				
			} else {
				$carriage_addendum='';
			}
			
			if($card5 == 1){
				$sql_traceability="SELECT * FROM public.v_con_booking_display WHERE id_con_booking = $id_con_booking";
				$rs_traceability = pg_query($conn, $sql_traceability);
				$row_traceability = pg_fetch_assoc($rs_traceability);
				
				$trace_story_id = $row_traceability['story_id'];
				$trace_doc_nr = $row_traceability['trace_doc_nr'];
				$trace_doc_date = $row_traceability['trace_doc_date'];
				$trace_country_origin = $row_traceability['country_origin'];
				$trace_culture_product = $row_traceability['culture_product'];
				$supplier_contact_id = $row_traceability['supplier_contact_id'];
				if($supplier_contact_id == 5346){ $supplier_contact_id = 1; }
				$trace_doc_publish = $row_traceability['trace_doc_publish'];
				$trace_buyer_days = $row_traceability['trace_buyer_days'];  
				$trace_certificate_request = $row_traceability['trace_certificate_request'];  
				
				
				// Traceability rights
				$traceability_read = $_GET['traceability_read'];
				$traceability_update = $_GET['traceability_update'];
				$traceability_create = $_GET['traceability_create'];
				if($traceability_read == 1){ $trace_r=''; }else{ $trace_r='hide'; }
				if($traceability_update == 1){ $trace_u=''; }else{ $trace_u='hide'; }
				if($traceability_create == 1){ $trace_c=''; }else{ $trace_c='hide'; }
				
				// Traceability Admin rights
				$traceabilityAdmin_read = $_GET['traceabilityAdmin_read'];
				$traceabilityAdmin_update = $_GET['traceabilityAdmin_update'];
				$traceabilityAdmin_create = $_GET['traceabilityAdmin_create'];
				if($traceabilityAdmin_read == 1){ $traceAdmin_r=''; }else{ $traceAdmin_r='hide'; }
				if($traceabilityAdmin_update == 1){ $traceAdmin_u=''; }else{ $traceAdmin_u='hide'; }
				if($traceabilityAdmin_create == 1){ $traceAdmin_c=''; }else{ $traceAdmin_c='hide'; }
				
				
				// Storie
				$sql_stories = "
					SELECT
					  story.media_type,
					  story.media_link,
					  story.story_title".$lang['DB_LANG_stat']." AS title,
					  story.id_country,
					  story.id_story,
					  country.name_country
					FROM
					  public.story, country
					WHERE
						story.id_country = country.id_country
					AND id_exporter = $supplier_contact_id
					ORDER BY id_story;
				";

				$rs_stories = pg_query($conn, $sql_stories);
				$list_trace_story = '';

				while ($row_stories = pg_fetch_assoc($rs_stories)) {
					$caption = utf8_decode($row_stories['title']);
					$name_country = utf8_decode($row_stories['name_country']);
					$valeur=$row_stories['id_story'];
					if ($row_stories['media_type'] == 2 ){
						$media = 'fa-file-image-o';
					} else {
						$media = 'fa-video-camera';
					}

					if($row_stories['id_story']==$trace_story_id){
						$check='checked'; $mark='<i class="fa fa-check"></i>';
					} else { $check=''; $mark=''; }
					
					$list_trace_story .= '<tr class="feature-row" onclick="";>
						<td style="vertical-align: middle;">'.$row_stories['id_story'].'</td>
						<td class="title"><i class="fa '.$media.' white" style="color:#1ab394"></i>&nbsp;&nbsp;'.$row_stories['title'].'</td>
						<td align="center" class="vertical-align: middle;" id="traceStoryStatus">
							<div class="i-checks traceStoryList hide"><input type="radio" value="'.$row_stories['id_story'].'" name="trace_radio" '.$check.'> <i></i></div>
							<div class="trace_story_check">'. $mark .'</div>
						</td>
					<tr>';
				}
				
				$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
				
				$qr_image_200 = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($actual_link . '/crm/traceability_certificate/index.php?id=' . $ord_schedule_id) . "&amp;size=200x200";
				$qr_image_100 = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($actual_link . '/crm/traceability_certificate/index.php?id=' . $ord_schedule_id) . "&amp;size=100x100";
				$qr_image_50 = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($actual_link . '/crm/traceability_certificate/index.php?id=' . $ord_schedule_id) . "&amp;size=50x50";
			
				$days = "";
				for ($x = 1; $x <= 20; $x++) {
					if($trace_buyer_days == $x){
						$days .= '<option value="'. $x .'" selected="selected">'. $x .'</option>';
					} else {
						$days .= '<option value="'. $x .'">'. $x .'</option>';
					}
				}
			
				$certificate_avail = '<option value="">--</option>';
				if($trace_doc_publish ==0){
					$certificate_avail .= '<option value="0" selected>NO</option>';
					$certificate_avail .= '<option value="1">YES</option>';
					$qr_show = "hide";
				} else { 
					$certificate_avail .= '<option value="0">NO</option>';
					$certificate_avail .= '<option value="1" selected>YES</option>';
					$qr_show = "";
				}
				
				
				$view_days='hide';
				if($supplier_contact_id == 1){
					$view_days='';
				}
				
				$traceability='<div class="panel panel-primary '. $qr_show .'" id="traceability_panel">
					<div class="panel-body '.$trace_r.'">
						<div class="">
							<h3>'.$lang['LOG_TRAC_QR_CODE'].'</h3>
							
							<div class="row">
								<div style="position:absolute; z-index:-999;">
									<input type="text" value="'.$actual_link.'/crm/traceability_certificate/index.php?id='.$ord_schedule_id.'&lang='. $lang['DB_LANG_stat'] .'" id="qr-code" />
								</div>
								<div class="col-md-4">
									<div class="col-md-12">
										<img src="'. $qr_image_200 .'" alt="" title="" />
									</div>
									<div class="col-md-12"><label>200x200</label></div>
									<div class="col-md-12">
										<button class="btn btn-danger clipboard_btn" data-clipboard-action="copy" data-clipboard-target="#qr-code">Copy link</button>
										<a class="btn btn-success" href="'. $qr_image_200 .'" download="qr.jpg">Download QR</a>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="col-md-12">
										<img src="'. $qr_image_100 .'" alt="" title="" />
									</div>
									<div class="col-md-12"><label>100x100</label></div>
									<div class="col-md-12">
										<button class="btn btn-danger clipboard_btn" data-clipboard-action="copy" data-clipboard-target="#qr-code">Copy link</button>
										<a class="btn btn-success" href="'. $qr_image_100 .'" download="qr.jpg">Download QR</a>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="col-md-12">
										<img src="'. $qr_image_50 .'" alt="" title="" />
									</div>
									<div class="col-md-12"><label>50x50</label></div>
									<div class="col-md-12">
										<button class="btn btn-danger clipboard_btn" data-clipboard-action="copy" data-clipboard-target="#qr-code">Copy link</button>
										<a class="btn btn-success" href="'. $qr_image_50 .'" download="qr.jpg">Download QR</a>
									</div>
								</div>
							</div>
						
							<div class="row" style="margin-top:20px;">
								<div class="col-md-12 no-padding">
									<div class="col-md-6">
										<div class="form-group text-left">
											<label>'.$lang['LOG_TRACE_ONLINE_CERTIFICATE'].'</label><br/> 
											<div id="trace_days_link">
												<a href="traceability_certificate/index.php?id='. $ord_schedule_id .'&lang='. $lang['DB_LANG_stat'] .'" target="_blank" id="issue_trace_certf" class="btn btn-warning">'.$lang['LOG_TRAC_BTN_SHOW'].'</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="collapse-group '.$traceAdmin_r.'">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#traceCert" href="#traceability_certificate_admin" class="text-uppercase"> '.$lang['LOG_TRACE_ADMIN'].' '.$shipment_number.' </a>
							</h4>
						</div>
						<div id="traceability_certificate_admin" class="panel-collapse collapse">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-4">
										<div class="col-md-12">
											<img src="'. $qr_image_100 .'" alt="" title="" />
										</div>
										<div class="col-md-12"><label>100x100</label></div>
										<div class="col-md-12">
											<div style="position:absolute; z-index:-999;">
												<input type="text" value="https://idiscover.ch/crm/traceability_certificate/index.php?id='.$ord_schedule_id.'&lang='. $lang['DB_LANG_stat'] .'" id="qr-code-admin" />
											</div>
											<button class="btn btn-danger clipboard_btn" data-clipboard-action="copy" data-clipboard-target="#qr-code-admin">Copy link</button>
											<a class="btn btn-success" href="'. $qr_image_100 .'" download="qr.jpg">Download QR</a>
										</div>
									</div>
								</div>
								
								<div id="traceabilityDocID" class="row" style="border-top:1px solid #e7eaec; margin-top:15px;">
									<h3>'.$lang['LOG_TRAC_DOCUMENT'].'</h3>
							
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label>'.$lang['LOG_TRAC_DOC_NO'].' </label>
													<input type="text" id="trace_doc_nr" value="'.$trace_doc_nr.'" class="form-control" />
												</div>
											</div>
										
											<div class="col-md-4">
												<div class="form-group">
													<label>'.$lang['LOG_TRAC_DATE'].'</label>
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control edit_delivery_date" value="'.$trace_doc_date.'" id="trace_doc_date">
													</div>
												</div>
											</div>
											
											<div class="col-md-4 '.$view_days.'">
												<label>'.$lang['LOG_TRAC_DAYS_COPRA_PURCHASE'].'</label>
												<select class="form-control" id="days_copra_purchases" onchange="changeCopraPurchaseDays(this.value,'. $ord_schedule_id .');">
													'. $days .'
												</select>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-4">
												<label>'.$lang['LOG_TRACE_CERTIFICATE_AVAILABLE'].'</label>
												<select class="form-control" id="trace_doc_publish">
													'. $certificate_avail .'
												</select>
											</div>
											
											<div class="col-md-8">
												<div class="form-group pull-right text-right">
													<label>'.$lang['LOG_TRACE_ONLINE_CERTIFICATE_ADMIN'].'</label><br/> 
													<div id="trace_days_link">
														<a href="traceability_certificate/index.php?id='. $ord_schedule_id .'&lang='. $lang['DB_LANG_stat'] .'" target="_blank" id="issue_trace_certf" class="btn btn-warning">'.$lang['LOG_TRAC_BTN_SHOW'].'</a>
													</div>
												</div>
												
												<div class="form-group pull-right text-right" style="margin-right:15px;">
													<label>'.$lang['LOG_TRACE_PDF'].'</label><br/> 
													<div id="trace_pdf_btn">
														<button id="send_trace_order_pdf" onclick="sendOrderTracePdf('. $ord_schedule_id .','.$id_con_booking.');" class="btn btn-primary"><i class="fa fa-paper-plane"></i>&nbsp;'.$lang['LOG_TRACE_PDF_BTN'].'</button>
														<small>'.$trace_certificate_request.'</small>
													</div>
												</div>
											</div>
										</div>
										
										<div class="row '.$trace_u.'">
											<div class="col-md-12" id="editTraceDocBtn">
												<button class="btn btn-success pull-right" id="traceabilityEditBTN" onclick="show_traceability_editBtn(\''.$ord_schedule_id.'\',\''.$id_con_booking.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
											</div>
										</div>
									</div>
								</div>
								
							
								<div id="traceabilityStoryID" class="row" style="border-top:1px solid #e7eaec; margin-top:15px;">
									<h3>'.$lang['LOG_TRAC_STORY_SELC'].'</h3>
									
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>'.$lang['LOG_TRAC_COUNTRY'].' </label><br/>
													'.$trace_country_origin.'
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<label>'.$lang['LOG_TRAC_CULTURE'].' </label><br/>
													'.$trace_culture_product.'
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-12">
												<table class="table table-striped table-hover" style="font-size:13px;">
													<thead>
														<tr>
															<th>'.$lang['LOG_TRACE_STORIE_ID'].'</th>
															<th>'.$lang['LOG_TRACE_STORIE_TITLE'].'</th>
															<th>'.$lang['LOG_TRACE_STORIE_ACTION'].'</th>
														</tr>
													</thead>
													<tbody id="trace_story">
														'.$list_trace_story.'
													</tbody>
												</table>
											</div>
										</div>
										
										<div class="row '.$traceAdmin_u.'">
											<div class="col-md-12" id="EditTraceStory">
												<button class="btn btn-success pull-right" id="EditTraceStoryBtn" onclick="show_traceStory_editBtn(\''.$ord_schedule_id.'\',\''.$id_con_booking.'\',\''.$supplier_contact_id.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
											</div>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
				</div>';
				
			} else {
				$traceability='';
			}
			
			
			$dom=$ocean.'##'.$booking_addendum.'##'.$onward_carriage.'##'.$carriage_addendum.'##'.$req.'##'.$req_add.'##'.$req_onward.'##'.$req_onward_add.'##'.$traceability;
			
		break;
		
		
		case "move_container_to":
		
			$ord_order_id = $_GET['ord_order_id'];
			$id_con_list = $_GET['id_con_list'];
			
			$sql="select id_ord_schedule, id_con_booking, s.ord_order_id, no_imp, no_sup 
			from v_logistics_schedule s where ord_order_id = $ord_order_id 
			order by no_imp";
			$result = pg_query($conn, $sql);
			
			$shipment="";
			while($row = pg_fetch_assoc($result)){
				$shipment.='<tr>
					<td class="text-center"><label style="font-weight:normal;" for="i_schedule_'. $row['id_ord_schedule'] .'">'.$row['no_imp'].'</label></td>
					<td class="text-center"><label style="font-weight:normal;" for="i_schedule_'. $row['id_ord_schedule'] .'">'.$row['no_sup'].'</label></td>
					<td><input type="radio" value="'. $row['id_ord_schedule'] .'" id="i_schedule_'. $row['id_ord_schedule'] .'" onchange="newShipmentSelected('. $id_con_list .','. $row['id_ord_schedule'] .');" name="newSchedule_id" class="radioBtnMoveContainer"></td>  
				</tr>';
			}
			
			$dom=$shipment;
		
		break;
		
		
		case "move_container":
		
			$id_con_list = $_GET['id_con_list'];
			$i_schedule_id = $_GET['i_schedule_id'];
			
			$sql = 'SELECT * FROM public."ChangeConShipment"('.$id_con_list.', '.$i_schedule_id.')';  
			$rst = pg_query($conn, $sql) or die(pg_last_error());  
			$count = pg_num_rows($rst);

			if($count==1){
				$dom=1;
				// $sqld = 'SELECT * FROM public."DeleteCon"('.$id_con_list.')';   
				// $resultd = pg_query($conn, $sqld) or die(pg_last_error());  
				// $countd = pg_num_rows($resultd);
	
				// if($countd==1){
					// $dom=1;
				// } else {
					// $dom=0;
				// }
				
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "update_traceability_story":
		
			$story_id = $_GET['story_id'];
			$id_con_booking = $_GET['id_con_booking'];
			
			$sql_story = "UPDATE public.ord_con_booking
			   SET story_id='$story_id'
			WHERE id_con_booking ='$id_con_booking'";

			$result = pg_query($conn, $sql_story) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "refresh_traceability_story":
		
			$supplier_contact_id = $_GET['supplier_contact_id'];
			if($supplier_contact_id == 5346){ $supplier_contact_id = 1; }
			
			$sql_stories = "
				SELECT
					story.media_type,
					story.media_link,
					story.story_title".$lang['DB_LANG_stat']." AS title,
					story.id_country,
					story.id_story,
					country.name_country
				FROM
				  public.story, country
				WHERE
					story.id_country = country.id_country
				AND id_exporter = $supplier_contact_id
				ORDER BY id_story;
			";

			$rs_stories = pg_query($conn, $sql_stories);
			$list_trace_story = '';

			while ($row_stories = pg_fetch_assoc($rs_stories)) {
				$caption = utf8_decode($row_stories['title']);
				$name_country = utf8_decode($row_stories['name_country']);
				$valeur=$row_stories['id_story'];
				if ($row_stories['media_type'] == 2 ){
					$media = 'fa-file-image-o';
				} else {
					$media = 'fa-video-camera';
				}

				if($row_stories['id_story']==$trace_story_id){
					$check='checked'; $mark='<i class="fa fa-check"></i>';
				} else { $check=''; $mark=''; }
			
				$list_trace_story .= '<tr class="feature-row" onclick="";>
					<td style="vertical-align: middle;">'.$row_stories['id_story'].'</td>
					<td class="title"><i class="fa '.$media.' white" style="color:#1ab394"></i>&nbsp;&nbsp;'.$row_stories['title'].'</td>
					<td align="center" class="vertical-align: middle;" id="traceStoryStatus">
						<div class="i-checks traceStoryList hide"><input type="radio" value="'.$row_stories['id_story'].'" name="trace_radio" '.$check.'> <i></i></div>
						<div class="trace_story_check">'. $mark .'</div>
					</td>
				<tr>';
			}
		
			$dom = $list_trace_story;
			
		break;
		
		
		case "update_container_booking":
		
			$change_pipeline = 0;
			
			if(isset($_GET["iso_booking"])){
				$iso_booking = $_GET["iso_booking"];
				$edit_iso_booking = "iso_booking='$iso_booking', ";
			} else { $iso_booking = "";  $edit_iso_booking = ''; }
			
			if(isset($_GET["iso_vessel_name"])){
				$iso_vessel_name = $_GET["iso_vessel_name"];
				$edit_iso_vessel_name = "iso_vessel_name='$iso_vessel_name', ";
			} else { $iso_vessel_name = "";  $edit_iso_vessel_name = ''; }
			
			if(isset($_GET["iso_vessel_mmsi"])){
				$iso_vessel_mmsi = $_GET["iso_vessel_mmsi"];
				$edit_iso_vessel_mmsi = "iso_vessel_mmsi='$iso_vessel_mmsi', ";
			} else { $iso_vessel_mmsi = "";  $edit_iso_vessel_mmsi = ''; }
			
			if(isset($_GET["iso_pol"])){
				$iso_pol = $_GET["iso_pol"];
				$edit_iso_pol = "iso_pol='$iso_pol', ";
			} else { $iso_pol = "";  $edit_iso_pol = ''; }
			
			// if(isset($_GET["iso_pod"])){
				// $iso_pod = $_GET["iso_pod"];
				// $edit_iso_pod = "iso_pod='$iso_pod', ";
			// } else { $iso_pod = "";  $edit_iso_pod = ''; }
			
			if(isset($_GET["iso_etd"])){
				$iso_etd = $_GET["iso_etd"];
				$edit_iso_etd = "iso_etd='$iso_etd', ";
			} else { $iso_etd = "";  $edit_iso_etd = ''; }
			
			if(isset($_GET["iso_eta"])){
				$iso_eta = $_GET["iso_eta"];
				$edit_iso_eta = "iso_eta='$iso_eta', ";
			} else { $iso_eta = "";  $edit_iso_eta = ''; }
			
			if(isset($_GET["iso_date_available"])){
				$iso_date_available = $_GET["iso_date_available"];
				$edit_iso_date_available = "iso_date_available='$iso_date_available', ";
			} else { $iso_date_available = "";  $edit_iso_date_available = ''; }
			
			if(isset($_GET["con_load_date_from"])){
				$con_load_date_from = $_GET["con_load_date_from"];
				$edit_con_load_date_from = "con_load_date_from='$con_load_date_from', ";
			} else { $con_load_date_from = "";  $edit_con_load_date_from = ''; }
			
			if(isset($_GET["con_load_date_to"])){
				$con_load_date_to = $_GET["con_load_date_to"];
				$edit_con_load_date_to = "con_load_date_to='$con_load_date_to', ";
			} else { $con_load_date_to = "";  $edit_con_load_date_to = ''; }
			
			if(isset($_GET["loading_manager_id"])){
				$loading_manager_id = $_GET["loading_manager_id"];
				$edit_loading_manager_id = "load_manager_id='$loading_manager_id', ";
			} else { $loading_manager_id = "";  $edit_loading_manager_id = ''; }
	
			if(isset($_GET["ids_multiple_agent"])){
				$ids_multiple_agent = $_GET["ids_multiple_agent"];
				$edit_ids_multiple_agent = "ids_multiple_agent='$ids_multiple_agent', ";
			} else { $ids_multiple_agent = "";  $edit_ids_multiple_agent = ''; }
	
			if(isset($_GET["booking_type_id"])){
				$booking_type_id = $_GET["booking_type_id"];
			} else { $booking_type_id = ""; }
		
			// Laoding status
			
			if(isset($_GET["end_state"])){
				$end_state = $_GET["end_state"];
				$edit_end_state = "end_state='$end_state', ";
			} else { $end_state = "";  $edit_end_state = ''; }
			
			if($end_state == 1){ $change_pipeline = 1; }
			
			if(isset($_GET["no_con_shipped"])){
				$no_con_shipped = $_GET["no_con_shipped"];
				$edit_no_con_shipped = "no_con_shipped='$no_con_shipped', ";
			} else { $no_con_shipped = "";  $edit_no_con_shipped = ''; }
			
			if(isset($_GET["container_nr"])){
				$container_nr = $_GET["container_nr"];
				$edit_container_nr = "container_nr='$container_nr', ";
			} else { $container_nr = "";  $edit_container_nr = ''; }
			
			if(isset($_GET["loadin_diff"])){
				$loadin_diff = $_GET["loadin_diff"];
				$edit_loadin_diff = "loadin_diff='$loadin_diff', ";
			} else { $loadin_diff = "";  $edit_loadin_diff = ''; }
			
			if(isset($_GET["iso_available"])){
				$iso_available = $_GET["iso_available"];
				$edit_iso_available = "iso_available=$iso_available, ";
			} else { $iso_available = "";  $edit_iso_available = ''; }
			
			if(isset($_GET["loading_note"])){
				$loading_note = $_GET["loading_note"];
			} else { $loading_note = ""; }
			
			if(isset($_GET["sync_agent_1"])){
				$sync_agent_1 = $_GET["sync_agent_1"];
				if(isset($_GET["sync_agent_2"])){
					$sync_agent_2 = $_GET["sync_agent_2"];
				} else { $sync_agent_2 =""; }
				
				$agents="'$sync_agent_1, $sync_agent_2'";
				$edit_sync_agent = "sync_agent=$agents, ";
			} else { $edit_sync_agent = ''; }
			
			if(isset($_GET["loading_place"])){
				$loading_place = $_GET["loading_place"];
				$edit_loading_place = "loading_address_id=$loading_place, ";
			} else { $loading_place = "";  $edit_loading_place = ''; }
			
			
			$booking_segment = $_GET["booking_segment"];
			$ord_schedule_id = $_GET["ord_schedule_id"];
			$id_con_booking = $_GET["id_con_booking"];
			
			$sql = "UPDATE public.ord_con_booking SET 
				$edit_iso_booking $edit_iso_vessel_name $edit_iso_vessel_mmsi $edit_iso_pol $edit_iso_etd
				$edit_iso_eta $edit_iso_date_available $edit_con_load_date_from $edit_con_load_date_to $edit_iso_available 
				$edit_loading_manager_id $edit_ids_multiple_agent $edit_sync_agent $edit_loading_place
				booking_type_id='$booking_type_id'
				
				WHERE id_con_booking=$id_con_booking
				AND booking_segment=$booking_segment
			";

			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_cont = "UPDATE public.ord_con_loading_header 
					SET $edit_end_state $edit_no_con_shipped $edit_container_nr 
					$edit_loadin_diff loading_note='$loading_note' 
				WHERE ord_schedule_id =$ord_schedule_id ";
		
				pg_query($conn, $sql_cont);
			
				if($id_con_booking!=""){
					$modified_by = $_SESSION["id_user"];
					$modified_date = gmdate("Y/m/d H:i");
					
					$sql_update = "UPDATE public.ord_con_booking SET modified_by=$modified_by, modified_date='$modified_date'
					WHERE id_con_booking = $id_con_booking ";
					pg_query($conn, $sql_update);
				}
				
				if($change_pipeline == 1){
					$sql_pipeline = "UPDATE public.ord_ocean_schedule SET pipeline_sched_id=298 WHERE id_ord_schedule = $ord_schedule_id ";
					pg_query($conn, $sql_pipeline);
				}
				
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "delete_labAnalysis":
		
			$id_analysis_item = $_GET['id_analysis_item'];
			
			$sql = "DELETE FROM ord_prod_anitem WHERE id_analysis_item=$id_analysis_item";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "labAnalysis_editForm":
		
			$id_analysis_item = $_GET['id_analysis_item'];
		
			$sql_prodAna = "SELECT * FROM ord_prod_anitem WHERE id_analysis_item=$id_analysis_item";
			$rs_prodAna = pg_query($conn, $sql_prodAna);
			$row_prodAna = pg_fetch_assoc($rs_prodAna);
			
			$result = $row_prodAna['result'];
			$id_parameter = $row_prodAna['id_parameter'];
			$prod_analysis_id = $row_prodAna['prod_analysis_id'];
			
			if($prod_analysis_id!=""){
				$sql_prodAna2 = "SELECT date_analysis FROM ord_prod_analysis WHERE id_ord_prod_an=$prod_analysis_id";
				$rs_prodAna2 = pg_query($conn, $sql_prodAna2);
				$row_prodAna2 = pg_fetch_assoc($rs_prodAna2);
				$date_analysis = $row_prodAna2['date_analysis'];
			} else { $date_analysis = ""; }
			
			
			$sql_labParam="SELECT * FROM ord_prod_params ORDER BY param_name DESC";
			$rs_labParam = pg_query($conn, $sql_labParam);
			$lab_param_list='<option value="">--</option>';
			while($row_labParam = pg_fetch_assoc($rs_labParam)){
				if($id_parameter== $row_labParam['id_prod_params']){
					$sel="selected='selected'";
				} else { $sel=""; } 
				$lab_param_list.='<option value="'.$row_labParam['id_prod_params'].'" '.$sel.'>'.$row_labParam['param_name'].' ('.$row_labParam['param_unit'].')</option>';
			}
			
			$dom=$lab_param_list.'##'.$result.'##'.$date_analysis;
			
		break;
		
		
		case "save_lab_analysis":
		
			$result = $_GET['result'];
			$date_analysis = $_GET['date_analysis'];
			
			$ord_order_id = $_GET['ord_order_id'];
			$id_con_booking = $_GET['id_con_booking'];
			$ord_schedule_id = $_GET['ord_schedule_id'];
			
			if(isset($_GET['supplier_id'])){
				$supplier_id = $_GET['supplier_id'];
				$field_supplier_id = "supplier_id,";
				$val_supplier_id = "$supplier_id,";
			} else { $field_supplier_id = ""; $val_supplier_id = ""; }
			
			if(isset($_GET['product_id'])){
				$product_id = $_GET['product_id'];
				$field_product_id = "product_id,";
				$val_product_id = "$product_id,";
			} else { $field_product_id = ""; $val_product_id = ""; }
			
			$id_prod_params = $_GET['id_prod_params'];
			
			
			$sql = "INSERT INTO
			public.ord_prod_analysis
			(
				ord_order_id,
				ord_schedule_id,
				con_booking_id,
				$field_supplier_id
				$field_product_id
				date_analysis
			)
			VALUES (
				$ord_order_id,
				$ord_schedule_id,
				$id_con_booking,
				$val_supplier_id
				$val_product_id
				'$date_analysis' 
			)";
	
			$rslt = pg_query($conn, $sql);

			if ($rslt) {
				$sql_last="SELECT id_ord_prod_an FROM ord_prod_analysis WHERE ord_schedule_id=$ord_schedule_id AND con_booking_id=$id_con_booking";
				$result_last = pg_query($conn, $sql_last);
				$row = pg_fetch_assoc($result_last);
				
				if($row['id_ord_prod_an']){
					$id_ord_prod_an = $row['id_ord_prod_an'];
					
					$sql_2="INSERT INTO public.ord_prod_anitem ( prod_analysis_id, id_parameter, result ) 
					VALUES ( $id_ord_prod_an, $id_prod_params, '$result' );";
					$result_2 = pg_query($conn, $sql_2);
					if ($result_2) {
						$dom=1;
					} else {
						$dom=0;
					}
				}
				
			} else {
				$dom=0;
			}

		break;
		
		
		case "refresh_lab_analysis_table":
		
			$labAna_delete = $_GET['labAna_delete'];
			if($labAna_delete == 1){ $labAna_d=''; }else{ $labAna_d='hide'; }
			
			$ord_schedule_id = $_GET['ord_schedule_id'];
			$id_con_booking = $_GET['id_con_booking'];
			
			$sql_prodAna = "SELECT id_ord_prod_an FROM ord_prod_analysis WHERE ord_schedule_id=$ord_schedule_id AND con_booking_id=$id_con_booking";
			$rs_prodAna = pg_query($conn, $sql_prodAna);
			$row_prodAna = pg_fetch_assoc($rs_prodAna);
			
			$id_ord_prod_an = $row_prodAna['id_ord_prod_an'];
			
			$labAnalysis_table="";
			if($id_ord_prod_an!=""){
				$sql_savedlabParam="SELECT a.id_ord_prod_an,
					a.ord_order_id,
					a.ord_schedule_id,
					a.con_booking_id,
					a.date_analysis,
					i.id_analysis_item,
					i.prod_analysis_id,
					i.id_parameter,
					p.param_name,
					p.param_method,
					p.param_unit,
					i.result
				 FROM ord_prod_analysis a,
					ord_prod_anitem i,
					ord_prod_params p
				  WHERE i.prod_analysis_id = a.id_ord_prod_an 
				  AND p.id_prod_params = i.id_parameter
				  AND i.prod_analysis_id = $id_ord_prod_an
				ORDER BY id_analysis_item";
				$rs_savedlabParam = pg_query($conn, $sql_savedlabParam);
				while($row_savedlabParam = pg_fetch_assoc($rs_savedlabParam)){
					$labAnalysis_table.='<tr>
						<td>'.$row_savedlabParam['param_name'].'</td>  
						<td>'.$row_savedlabParam['param_unit'].'</td>
						<td>'.$row_savedlabParam['result'].'</td>
						<td class="text-center">
							<a href="#" data-toggle="modal" onclick="editLabAna('. $row_savedlabParam['id_analysis_item'] .');" data-target="#modalLabAnalysis"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
							<a href="javascript:deleteLabAna('. $row_savedlabParam['id_analysis_item'] .');" class="'.$labAna_d.'" onclick="return confirm(\'Are you sure you want to delete this lab:'. $row_savedlabParam['param_name'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
						</td>
					</tr>';
				}
			}
		
			$dom=$labAnalysis_table;
			
		break;
		
		
		case "edit_lab_analysis":
		
			$id_analysis_item = $_GET['id_analysis_item'];
			$date_analysis = $_GET['date_analysis'];
			
			if(isset($_GET["id_prod_params"])){
				$id_prod_params = $_GET["id_prod_params"];
				$req_id_prod_params = "id_parameter=$id_prod_params, "; 
			} else { $req_id_prod_params = ""; }
			
			// if(isset($_GET["date_analysis"])){
				// $date_analysis = $_GET["date_analysis"];
				// $req_date_analysis = "date_analysis=$date_analysis, "; 
			// } else { $req_date_analysis = ""; }
			
			if(isset($_GET["result"])){
				$result = $_GET["result"];
				$req_result = "result='$result', "; 
			} else { $req_result = ""; }
			
			
			$sql = "Update ord_prod_anitem set 
				$req_id_prod_params $req_result unit=''
			where id_analysis_item=$id_analysis_item ";
			
			$r = pg_query($conn, $sql);
			if ($r) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "save_shipping_documents":
			
			$type = $_GET["type"];
			$ord_schedule_id = $_GET["ord_schedule_id"];
			$id_con_booking = $_GET["id_con_booking"];
		
			if(!empty($_GET["ord_con_list_id"])){
				$ord_con_list_id = $_GET["ord_con_list_id"];
				$req_ord_con_list_id = 'ord_con_list_id, '; $val_ord_con_list_id = ''.$ord_con_list_id.', ';
				$edit_ord_con_list_id = "ord_con_list_id='$ord_con_list_id', ";
			} else { $ord_con_list_id = ""; $req_ord_con_list_id = ''; $val_ord_con_list_id = ''; $edit_ord_con_list_id = ''; }
			
			if(isset($_GET["lab_awb_no"])){
				$lab_awb_no = $_GET["lab_awb_no"];
				$req_lab_awb_no = 'lab_awb_no, '; $val_lab_awb_no = "'$lab_awb_no', ";
				$edit_lab_awb_no = "lab_awb_no='$lab_awb_no', ";
			} else { $lab_awb_no = ""; $req_lab_awb_no = ''; $val_lab_awb_no = ''; $edit_lab_awb_no = ''; }
		
			if(isset($_GET["lab_cus_awb_date"])){
				$lab_cus_awb_date = $_GET["lab_cus_awb_date"];
				$req_lab_cus_awb_date = 'lab_cus_awb_date, '; $val_lab_cus_awb_date = "'$lab_cus_awb_date', ";
				$edit_lab_cus_awb_date = "lab_cus_awb_date='$lab_cus_awb_date', ";
			} else { $lab_cus_awb_date = ""; $req_lab_cus_awb_date = ''; $val_lab_cus_awb_date = ''; $edit_lab_cus_awb_date = ''; }
		
			if(isset($_GET["fa_awb_no"])){
				$fa_awb_no = $_GET["fa_awb_no"];
				$req_fa_awb_no = 'fa_awb_no, '; $val_fa_awb_no = "'$fa_awb_no', ";
				$edit_fa_awb_no = "fa_awb_no='$fa_awb_no', ";
			} else { $fa_awb_no = ""; $req_fa_awb_no = ''; $val_fa_awb_no = ''; $edit_fa_awb_no = ''; }
		
			if(isset($_GET["fa_awb_date"])){
				$fa_awb_date = $_GET["fa_awb_date"];
				$req_fa_awb_date = 'fa_awb_date, '; $val_fa_awb_date = "'$fa_awb_date', ";
				$edit_fa_awb_date = "fa_awb_date='$fa_awb_date', ";
			} else { $fa_awb_date = ""; $req_fa_awb_date = ''; $val_fa_awb_date = ''; $edit_fa_awb_date = ''; }
		
			if(isset($_GET["lab_contact_id"])){
				$lab_contact_id = $_GET["lab_contact_id"];
				$req_lab_contact_id = 'lab_contact_id, '; $val_lab_contact_id = "'$lab_contact_id', ";
				$edit_lab_contact_id = "lab_contact_id='$lab_contact_id', ";
			} else { $lab_contact_id = ""; $req_lab_contact_id = ''; $val_lab_contact_id = ''; $edit_lab_contact_id = ''; }
		
			if(isset($_GET["cust_awb_no"])){
				$cust_awb_no = $_GET["cust_awb_no"];
				$req_cust_awb_no = 'cust_awb_no, '; $val_cust_awb_no = "'$cust_awb_no', ";
				$edit_cust_awb_no = "cust_awb_no='$cust_awb_no', ";
			} else { $cust_awb_no = ""; $req_cust_awb_no = ''; $val_cust_awb_no = ''; $edit_cust_awb_no = ''; }
			
			if(isset($_GET["cus_awb_date"])){
				$cus_awb_date = $_GET["cus_awb_date"];
				$req_cus_awb_date = 'cus_awb_date, '; $val_cus_awb_date = "'$cus_awb_date', ";
				$edit_cus_awb_date = "cus_awb_date='$cus_awb_date', ";
			} else { $cus_awb_date = ""; $req_cus_awb_date = ''; $val_cus_awb_date = ''; $edit_cus_awb_date = ''; }
			
			$b_=0;
			
			if(isset($_GET["bl_number"])){ $b_=1;
				$bl_number = $_GET["bl_number"];
				$req_bl_number = 'bl_number, '; $val_bl_number = "'$bl_number', ";
				$edit_bl_number = "bl_number='$bl_number', ";
			} else { $bl_number = ""; $req_bl_number = ''; $val_bl_number = ''; $edit_bl_number = ''; }
			
			if(isset($_GET["pol_etd_actual"])){ $b_=1;
				$pol_etd_actual = $_GET["pol_etd_actual"];
				$req_pol_etd_actual = 'pol_etd_actual, '; $val_pol_etd_actual = "'$pol_etd_actual', ";
				$edit_pol_etd_actual = "pol_etd_actual='$pol_etd_actual', ";
			} else { $pol_etd_actual = ""; $req_pol_etd_actual = ''; $val_pol_etd_actual = ''; $edit_pol_etd_actual = ''; }
			
			
			$created_by = $_SESSION["id_user"];
			$modified_date = gmdate("Y/m/d H:i");
			
			if($type == 'add'){
				$sql = "Insert into ord_con_loading_header ( $req_ord_con_list_id 
					$req_lab_awb_no $req_lab_cus_awb_date $req_fa_awb_no $req_fa_awb_date $req_lab_contact_id $req_cust_awb_no $req_cus_awb_date 
					ord_schedule_id, created_by )
				Values ( $val_ord_con_list_id
					$val_lab_awb_no $val_lab_cus_awb_date $val_fa_awb_no $val_fa_awb_date $val_lab_contact_id $val_cust_awb_no $val_cus_awb_date 
					$ord_schedule_id, $created_by )";
					
				//ord_con_booking
				if(($id_con_booking!="") AND ($b_!=0)){
					$sql2 = "Insert into ord_con_booking ( $req_bl_number $req_pol_etd_actual modified_by, modified_date )
					Values ( $val_bl_number $val_pol_etd_actual $created_by, $modified_date )";
				}
			
			} else {
				$sql = "Update ord_con_loading_header set 
					$edit_lab_awb_no $edit_lab_cus_awb_date $edit_fa_awb_no $edit_fa_awb_date $edit_lab_contact_id $edit_cust_awb_no $edit_cus_awb_date modify_by=$created_by
				where ord_schedule_id=$ord_schedule_id ";
				
				//ord_con_booking
				if(($id_con_booking!="") AND ($b_!=0)){
					$sql2 = "Update ord_con_booking set 
						$edit_bl_number $edit_pol_etd_actual modified_by=$created_by, modified_date='$modified_date'
					where id_con_booking=$id_con_booking";
				}
			}
	
			//ord_con_booking
			if(($id_con_booking!="") AND ($b_!=0)){
				$result2 = pg_query($conn, $sql2);
			}
	
			$result = pg_query($conn, $sql);

			if ($result) { 
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "add_booking_addendum":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			
			$sql_update = "UPDATE public.ord_ocean_schedule SET flag_book_add=1 WHERE id_ord_schedule = $ord_schedule_id ";
			$rs_update = pg_query($conn, $sql_update);
			
			if($rs_update){
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
	}
	
}

echo $dom;