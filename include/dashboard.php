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
		
		case "dashboard_vessels":
			
			$id_user = $_SESSION['id_user'];
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];

			if($id_user_supchain_type == 312){
				$sql_stats = "SELECT st.id_ship_track, st.mmsi, st.lat, st.lon, st.speed, st.status, st.timest, st.shipname, st.course, st.con_booking_id, st.ord_schedule_id, current_date, os.photo, sn.shipment_number
							FROM ord_ship_Track st
								left join ord_ship os on os.mmsi = st.mmsi
								left join (select public.get_maporder_nr(v_logistics.id_ord_schedule, 312) as shipment_number, ord_ship_fleet.mmsi, v_logistics.id_ord_schedule
							from v_logistics, ord_ship_fleet
							where ord_ship_fleet.ord_schedule_id=v_logistics.id_ord_schedule) sn on sn.id_ord_schedule = st.ord_schedule_id
						 WHERE st.id_ship_track IN (SELECT max (st2.id_ship_track) FROM ord_ship_Track st2 WHERE st2.mmsi = st.mmsi) AND st.mmsi IN
					(SELECT mmsi FROM ord_ship_fleet  WHERE ord_schedule_id IN (SELECT id_ord_schedule FROM v_logistics WHERE ord_sm_person_id = $id_contact))";
				
			} else {
				if($id_supchain_type == 110){
					$sql_stats = "SELECT st.id_ship_track, st.mmsi, st.lat, st.lon, st.speed, st.status, st.timest, st.shipname, st.course, st.con_booking_id, log.id_ord_schedule, current_date, os.photo, 
						public.get_maporder_nr(log.id_ord_schedule, 110) as shipment_number
							   FROM ord_ship_Track st, ord_ship os, 
									ord_ship_fleet osf, v_logistics log 
									where os.mmsi=st.mmsi
									and osf.mmsi=st.mmsi
									and log.ord_cus_contact_id=$id_company
									and log.id_ord_schedule=osf.ord_schedule_id
									and st.id_ship_track IN (SELECT max (st2.id_ship_track) FROM ord_ship_Track st2 WHERE st2.mmsi = st.mmsi) 
									AND st.mmsi IN
					(SELECT mmsi FROM ord_ship_fleet  WHERE ord_schedule_id IN (SELECT id_ord_schedule FROM v_logistics WHERE ord_cus_contact_id = $id_company))";
	
				} else
				if($id_supchain_type == 112){
					if($id_company==717){
						$sql_stats = "SELECT st.id_ship_track, st.mmsi, st.lat, st.lon, st.speed, st.status, st.timest, st.shipname, st.course, st.con_booking_id, st.ord_schedule_id, current_date, os.photo, sn.shipment_number
							FROM ord_ship_Track st
								left join ord_ship os on os.mmsi = st.mmsi
								left join (select public.get_maporder_nr(v_logistics.id_ord_schedule, 112) as shipment_number, ord_ship_fleet.mmsi, v_logistics.id_ord_schedule
							from v_logistics, ord_ship_fleet
							where ord_ship_fleet.ord_schedule_id=v_logistics.id_ord_schedule) sn on sn.id_ord_schedule = st.ord_schedule_id
						  WHERE st.id_ship_track IN (SELECT max (st2.id_ship_track) FROM ord_ship_Track st2 WHERE st2.mmsi = st.mmsi) AND st.mmsi IN
						 (SELECT mmsi FROM ord_ship_fleet  WHERE ord_schedule_id IN (SELECT id_ord_schedule FROM v_logistics WHERE ord_imp_contact_id = $id_company))";
						
					} else {
						$sql_stats = "select st.id_ship_track, st.mmsi, st.lat, st.lon, st.speed, st.status, st.timest, st.shipname, st.course, st.con_booking_id, st.ord_schedule_id, current_date, os.photo, sn.shipment_number 
						from ord_ship_Track st 
							left join ord_ship os on os.mmsi = st.mmsi
							left join (select public.get_maporder_nr(v_logistics.id_ord_schedule, 112) as shipment_number, ord_ship_fleet.mmsi, v_logistics.id_ord_schedule
							from v_logistics, ord_ship_fleet
							where ord_ship_fleet.ord_schedule_id=v_logistics.id_ord_schedule) sn on sn.id_ord_schedule = st.ord_schedule_id
						where st.id_ship_track in ( select max(st2.id_ship_track) from ord_ship_Track st2 where st2.mmsi=st.mmsi )
						and st.mmsi in ( select mmsi from ord_ship_fleet  where ord_order_id in  
						( select id_ord_order from ord_order where ord_imp_contact_id=$id_company  
						 Or ord_imp_contact_id in ( select id_contact from ( 
						  select id_contact from (   
						   select * from contact where id_contact in (   
						select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where   
						   id_contact=(select id_contact from users where id_user=$id_user) ) )  
						   UNION  
						   select * from contact where id_primary_company in (   
						select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where   
						   id_contact=(select id_contact from users where id_user=$id_user) ) )  
						   and id_contact in ( select id_contact from users )   
						   union  
						   select * from contact where id_contact in (   
						   select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where   
						   id_contact=(select id_contact from users where id_user=$id_user) ) )  
						   union  
						   select * from contact where id_primary_company in (   
						   select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where   
						   id_contact=(select id_contact from users where id_user=$id_user) ) )  
						   and id_contact in ( select id_contact from users )  
						   union  
						   select * from contact where id_primary_company in ( select id_primary_company from contact where   
						   id_contact=(select id_contact from users where id_user=$id_user) )  
						   union  
						   select * from contact where id_primary_company in ( select id_link from contact_links where   
						   id_contact in ( select id_primary_company from contact where   
						   id_contact=(select id_contact from users where id_user=$id_user) ))  
						   union  
						   select * from contact where id_primary_company in ( select id_contact from contact_links where   
						   id_link in ( select id_primary_company from contact where   
						   id_contact=(select id_contact from users where id_user=$id_user) ))  
						  ) q1  
						  where id_type=10 and id_supchain_type=112  
						) q2) )) ";
					}
					
				} else
				if($id_supchain_type == 113){
					$sql_stats = "SELECT st.id_ship_track, st.mmsi, st.lat, st.lon, st.speed, st.status, st.timest, st.shipname, st.course, st.con_booking_id, st.ord_schedule_id, current_date, os.photo, sn.shipment_number
							FROM ord_ship_Track st
								left join ord_ship os on os.mmsi = st.mmsi
								left join (select public.get_maporder_nr(v_logistics.id_ord_schedule, 113) as shipment_number, ord_ship_fleet.mmsi, v_logistics.id_ord_schedule
							from v_logistics, ord_ship_fleet
							where ord_ship_fleet.ord_schedule_id=v_logistics.id_ord_schedule) sn on sn.id_ord_schedule = st.ord_schedule_id
						 WHERE st.id_ship_track IN (SELECT max (st2.id_ship_track) FROM ord_ship_Track st2 WHERE st2.mmsi = st.mmsi) AND st.mmsi IN
					(SELECT mmsi FROM ord_ship_fleet  WHERE ord_schedule_id IN (SELECT id_ord_schedule FROM v_logistics WHERE supplier_contact_id=$id_company))";
					
				} else
				if($id_supchain_type == 289){
					$sql_stats = "SELECT st.id_ship_track, st.mmsi, st.lat, st.lon, st.speed, st.status, st.timest, st.shipname, st.course, st.con_booking_id, st.ord_schedule_id, current_date, os.photo, sn.shipment_number
							FROM ord_ship_Track st
								left join ord_ship os on os.mmsi = st.mmsi
								left join (select public.get_maporder_nr(v_logistics.id_ord_schedule, 289) as shipment_number, ord_ship_fleet.mmsi, v_logistics.id_ord_schedule
							from v_logistics, ord_ship_fleet
							where ord_ship_fleet.ord_schedule_id=v_logistics.id_ord_schedule) sn on sn.id_ord_schedule = st.ord_schedule_id
						 WHERE st.id_ship_track IN (SELECT max (st2.id_ship_track) FROM ord_ship_Track st2 WHERE st2.mmsi = st.mmsi) AND st.mmsi IN
					(SELECT mmsi FROM ord_ship_fleet  WHERE ord_schedule_id IN (SELECT id_ord_schedule FROM v_logistics WHERE ord_fa_contact_id=$id_company))";
					
				} else {}
			}

			$result = pg_query($conn, $sql_stats);
		
			$vessels=''; 
			while($arr = pg_fetch_assoc($result)){
				$vessels .= $arr['lat'] .'##'. $arr['lon'] .'##'. $arr['mmsi'] .'##'. $arr['speed'] .'##'. $arr['shipname'] .'##'. $arr['timest'] .'##'. $arr['photo'] .'##'. $arr['shipment_number'] .'##'. $arr['con_booking_id'].'??';
			}
			
			$vessels .='end';
			
			$dom=$vessels;
		
		break;
		
		
		case "vessel_details":
		
			$con_booking_id = $_GET["con_booking_id"];
			$mmsi = $_GET["mmsi"];
			$sn0 = $_GET["sn"];
			
			$sql="select mmsi, speed, current_port_name, last_port_name, next_port_name, timest,
			distance_to_go, eta_calc, eta_updated, destination, lat, lon, ais_type_summary, shipname
			from ord_ship_track 
			where mmsi=$mmsi order by id_ship_track desc limit 1";
			
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			$table = '<table class="table" style="font-size:13px;">
				<tr>
					<td><strong>'.$lang['VT_MMSI'].':</strong></td>
					<td>'. $arr['mmsi'] .'</td>
				</tr>
				<tr>
					<td><strong>'.$lang['VT_SPEED'].':</strong></td>
					<td>'. $arr['speed'] .'</td>
				</tr>
				<tr>
					<td><strong>'.$lang['VT_PT_NAME'].':</strong></td>
					<td>'. $arr['current_port_name'] .'</td>
				</tr>
				<tr>
					<td><strong>'.$lang['VT_LPT_NAME'].':</strong></td>
					<td>'. $arr['last_port_name'] .'</td>
				</tr>
				<tr>
					<td><strong>'.$lang['VT_NPT_NAME'].':</strong></td>
					<td>'. $arr['next_port_name'] .'</td>
				</tr>
				<tr>
					<td><strong>'.$lang['VT_DISTANCE_TG'].':</strong></td>
					<td>'. $arr['distance_to_go'] .'</td>
				</tr>
				<tr>
					<td><strong>'.$lang['VT_ETA_CALC'].':</strong></td>
					<td>'. $arr['eta_calc'] .'</td>
				</tr>
				<tr>
					<td><strong>'.$lang['VT_ETA_UDT'].':</strong></td>
					<td>'. $arr['eta_updated'] .'</td>
				</tr>
				<tr>
					<td><strong>'.$lang['VT_DESTINATION'].':</strong></td>
					<td>'. $arr['destination'] .'</td>
				</tr>
				<tr>
					<td><strong>'.$lang['VT_LAST_REPORT'].':</strong></td>
					<td>'. $arr['timest'] .'</td>
				</tr>
			</table>
			<div class="text-right">
				<a class="btn btn-xs btn-white" onclick="dashboard(1)"><i class="fa fa-search"></i> '.$lang['VT_HOME_BTN'].' </a>
				<a class="btn btn-xs btn-white" onclick="zoom_vessel_plus('.$arr['lat'].','.$arr['lon'].')"><i class="fa fa-search-plus"></i> '.$lang['VT_ZOOM_BTN'].' </a>
			</div>';
	
			
			$sn="";
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];

			if($id_supchain_type == 110){
				$sn ='<small class="col-md-12 no-margins no-padding">'. $sn0 .'</small>';	
			} else {
				if($id_user_supchain_type == 312){
					$num=$id_user_supchain_type;
				} else {
					$num=$id_supchain_type;
				}
				
				$sql_sn="select public.get_maporder_nr(v_logistics.id_ord_schedule, $num) as shipment_number, ord_ship_fleet.mmsi
				from v_logistics, ord_ship_fleet where ord_ship_fleet.ord_schedule_id=v_logistics.id_ord_schedule
				AND ord_ship_fleet.mmsi = $mmsi";
				
				$rst_sn = pg_query($conn, $sql_sn);
				while($arr_sn = pg_fetch_assoc($rst_sn)){
					$sn .='<small class="col-md-12 no-margins no-padding">'. $arr_sn['shipment_number'] .'</small>';
				}
			}
			
			$header ='<span>'. $arr['ais_type_summary'] .'</span>
			<h2 class="font-bold" style="margin:5px 0 0 0;">'. $arr['shipname'] .'</h2>
			'. $sn;
			
			
			if($con_booking_id!=""){
				$sql_booking="select pod_id, eta from v_con_booking where id_con_booking=$con_booking_id";
				$rst_booking = pg_query($conn, $sql_booking);
				$arr_booking = pg_fetch_assoc($rst_booking);
				
				$booking = '<small><strong>POD:</strong> '. $arr_booking['pod_id'] .'</small>
				<small class="pull-right"><strong>ETA:</strong> '. $arr_booking['eta'] .'</small>';
			} else {
				$booking = '';
			}
			
			$dom=$table.'##'.$header.'##'.$booking;
			
		break;
		
		
		case "dashboard_gantt":
			
			$id_user = $_SESSION['id_user'];
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
			$filtre = $_GET['filtre'];
			if($filtre == ""){
				$cond = "";
			} else {
				if($id_user_supchain_type==312){
					$cond = " and public.get_reference_nr(id_ord_schedule, 312) like '%$filtre%'";
					
				} else {
					if($id_supchain_type==110){
						$cond = " and public.get_reference_nr(id_ord_schedule, 110) like '%$filtre%'";
						
					} else
					if($id_supchain_type==112){
						
						if($id_company==717){
							$cond = " and public.get_reference_nr(id_ord_schedule, 112) like '%$filtre%'";
						
						} else {
							$cond = " and public.get_reference_nr(id_ord_schedule, 112) like '%$filtre%'";
						}
						
					} else
					if($id_supchain_type==113){
						$cond = " and public.get_reference_nr(id_ord_schedule, 113) like '%$filtre%'";
						
					} else 
					if($id_supchain_type==289){
						$cond = " and public.get_reference_nr(id_ord_schedule, 289) like '%$filtre%'";
					} 
				}
			}
			
			// Gantt
			$gantt='';
			if($id_user_supchain_type==312){
				$sql_gantt = "select *, public.get_reference_nr(id_ord_schedule, 312) as shipment_number, Sup_reference_nr as order_number from v_gantt 
				where ord_sm_person_id=$id_contact and pipeline_id <299
				$cond
				order by month_etd, shipment_number";
				
			} else {
				if($id_supchain_type==110){
					$sql_gantt = "select *, public.get_reference_nr(id_ord_schedule, 110) as shipment_number, Customer_reference_nr as order_number from v_gantt 
					where ord_cus_contact_id=$id_company and pipeline_id <299
					$cond
					order by month_etd, shipment_number";
					
				} else
				if($id_supchain_type==112){
					
					if($id_company==717){
						$sql_gantt = "select *, public.get_reference_nr(id_ord_schedule, 113) as shipment_number, Order_nr as order_number from v_gantt 
						where ord_imp_contact_id=717 and pipeline_id <299 and from_text IS NOT NULL
						$cond
						order by month_etd, shipment_number";
					
					} else {
						$sql_gantt = "select *, public.get_reference_nr(id_ord_schedule, 112) as shipment_number, Order_nr as order_number from v_gantt 
						  where ord_imp_contact_id=$id_company and pipeline_id <299 and from_text IS NOT NULL
						  $cond
						ORDER by month_etd, shipment_number";
					}
					
				} else
				if($id_supchain_type==113){
					$sql_gantt = "Select * from ( 
					select *, public.get_reference_nr(id_ord_schedule, 113) as shipment_number, Sup_reference_nr as order_number     from v_gantt 
					where ord_order_id in ( select distinct ord_order_id from ord_ocean_schedule where supplier_contact_id=$id_company )
					and pipeline_id <299) q1
					$cond
					order by month_etd, shipment_number";
					
				} else 
				if($id_supchain_type==289){
					$sql_gantt = "Select * from ( 
					select *, public.get_reference_nr(id_ord_schedule, 289) as shipment_number, Fa_reference_nr as order_number     from v_gantt 
					where ord_order_id in ( select distinct ord_order_id from ord_con_booking where fa_contact_id=$id_company ) 
					and pipeline_id <299) q1
					$cond
					order by month_etd, shipment_number ";
				
				} else {}
			}

			$result_gantt = pg_query($conn, $sql_gantt);
			while($arr_gantt = pg_fetch_assoc($result_gantt)){
				$gantt.= $arr_gantt['shipment_number'].'|'.$arr_gantt['from_text'].'|'.$arr_gantt['nr_days'].'??';
			}
			
			$dom=$gantt;
			
		break;
		
		
		case "dashboard_analytics":
		
			$id_user = $_SESSION['id_user'];
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
		
			if($id_user_supchain_type==312){
					$sql = "select c.pipeline_name,
						s.ord_order_id,
						s.id_ord_schedule,
						s.order_ship_nr,
						s.ord_cus_contact_code, 
						s.ord_cus_contact_id,
						s.ord_fa_contact_id,
						s.ord_imp_contact_id,
						s.supplier_contact_id,
						s.supplier_lastname,
						s.ord_sm_person_id,
						c.importer,
						c.importer_person,
						get_contact_code(s.ord_fa_contact_id) fa_contact_code,
						get_contact_code(c.supplier_contact_id) sup_contact_code,
						get_contact_name(s.ord_sm_person_id) sm_name,
						get_contact_code(s.ord_sm_person_id) sm_code,
						(s.customer_reference_nr::text || '.'::text) || s.customer_ref_ship_nr::text as no_cus,
						(s.ord_fa_reference_nr::text || '.'::text) || s.fa_reference_nr::text as no_fa,
						(s.sup_reference_nr::text || '.'::text) || s.supplier_reference_nr::text as no_sup,
						(s.order_nr::text || '.'::text)|| s.order_ship_nr::text as no_imp,
						s.product_code,
						s.nr_containers,
						s.package_name,
						ltrim(to_char(s.weight_shipment,'999G999'),' ') weight_total,
						getregvalue(s.supplier_incoterms_id) sup_incoterms,
						getregvalue(s.cus_incoterms_id) cus_incoterms,
						s.pod_name,
						s.pod_code,
						s.month_etd,
						s.week_etd,
						getregvalue(s.price_currency_id) sup_currency,
						s.price_sup_eur as sup_cost_mt,
						c.total_product,
						c.total_services,
						c.total_cost,
						c.unit_cost,
						c.total_value,
						c.unit_value,
						c.margin_tone,
						c.margin_tone*c.weight_shipment margin_total,
						getregvalue(c.proposal_currency_id) sales_currency,
						get_port_name(s.pol_id) pol_name,
						s.pol_code,
						s.month_eta,
						s.week_eta,
						get_booking_nr(s.id_ord_schedule) booking_nr,
						get_vessel_name(s.id_ord_Schedule) vessel_name,
						get_contact_code(get_carrier_id(s.id_ord_schedule)) carrier
						from v_order_schedule s,v_schedule_calc c
						where c.id_ord_schedule=s.id_ord_schedule
						and c.pipeline_id in ( 296, 298 )
						and s.ord_sm_person_id=$id_contact 
					";
					
				} else {
					if($id_supchain_type==110){
						$sql = "select c.pipeline_name,
							s.ord_order_id,
							s.id_ord_schedule,
							s.order_ship_nr,
							s.ord_cus_contact_code, 
							s.ord_cus_contact_id,
							s.ord_fa_contact_id,
							s.ord_imp_contact_id,
							s.supplier_contact_id,
							s.supplier_lastname,
							s.ord_sm_person_id,
							c.importer,
							c.importer_person,
							get_contact_code(s.ord_fa_contact_id) fa_contact_code,
							get_contact_code(c.supplier_contact_id) sup_contact_code,
							get_contact_name(s.ord_sm_person_id) sm_name,
							get_contact_code(s.ord_sm_person_id) sm_code,
							(s.customer_reference_nr::text || '.'::text) || s.customer_ref_ship_nr::text as no_cus,
							(s.ord_fa_reference_nr::text || '.'::text) || s.fa_reference_nr::text as no_fa,
							(s.sup_reference_nr::text || '.'::text) || s.supplier_reference_nr::text as no_sup,
							(s.order_nr::text || '.'::text)|| s.order_ship_nr::text as no_imp,
							s.product_code,
							s.nr_containers,
							s.package_name,
							ltrim(to_char(s.weight_shipment,'999G999'),' ') weight_total,
							getregvalue(s.supplier_incoterms_id) sup_incoterms,
							getregvalue(s.cus_incoterms_id) cus_incoterms,
							s.pod_name,
							s.pod_code,
							s.month_etd,
							s.week_etd,
							getregvalue(s.price_currency_id) sup_currency,
							s.price_sup_eur as sup_cost_mt,
							c.total_product,
							c.total_services,
							c.total_cost,
							c.unit_cost,
							c.total_value,
							c.unit_value,
							c.margin_tone,
							c.margin_tone*c.weight_shipment margin_total,
							getregvalue(c.proposal_currency_id) sales_currency,
							get_port_name(s.pol_id) pol_name,
							s.pol_code,
							s.month_eta,
							s.week_eta,
							get_booking_nr(s.id_ord_schedule) booking_nr,
							get_vessel_name(s.id_ord_Schedule) vessel_name,
							get_contact_code(get_carrier_id(s.id_ord_schedule)) carrier
							from v_order_schedule s,v_schedule_calc c
							where c.id_ord_schedule=s.id_ord_schedule
							and c.pipeline_id in ( 296, 298 )
							and s.ord_cus_contact_id=$id_company
						";
						
					} else
					if($id_supchain_type==112){
						if($id_company==717){
							$sql = "select c.pipeline_name,
								s.ord_order_id,
								s.id_ord_schedule,
								s.order_ship_nr,
								s.ord_cus_contact_code, 
								s.ord_cus_contact_id,
								s.ord_fa_contact_id,
								s.ord_imp_contact_id,
								s.supplier_contact_id,
								s.supplier_lastname,
								s.ord_sm_person_id,
								c.importer,
								c.importer_person,
								get_contact_code(s.ord_fa_contact_id) fa_contact_code,
								get_contact_code(c.supplier_contact_id) sup_contact_code,
								get_contact_name(s.ord_sm_person_id) sm_name,
								get_contact_code(s.ord_sm_person_id) sm_code,
								(s.customer_reference_nr::text || '.'::text) || s.customer_ref_ship_nr::text as no_cus,
								(s.ord_fa_reference_nr::text || '.'::text) || s.fa_reference_nr::text as no_fa,
								(s.sup_reference_nr::text || '.'::text) || s.supplier_reference_nr::text as no_sup,
								(s.order_nr::text || '.'::text)|| s.order_ship_nr::text as no_imp,
								s.product_code,
								s.nr_containers,
								s.package_name,
								ltrim(to_char(s.weight_shipment,'999G999'),' ') weight_total,
								getregvalue(s.supplier_incoterms_id) sup_incoterms,
								getregvalue(s.cus_incoterms_id) cus_incoterms,
								s.pod_name,
								s.pod_code,
								s.month_etd,
								s.week_etd,
								getregvalue(s.price_currency_id) sup_currency,
								s.price_sup_eur as sup_cost_mt,
								c.total_product,
								c.total_services,
								c.total_cost,
								c.unit_cost,
								c.total_value,
								c.unit_value,
								c.margin_tone,
								c.margin_tone*c.weight_shipment margin_total,
								getregvalue(c.proposal_currency_id) sales_currency,
								get_port_name(s.pol_id) pol_name,
								s.pol_code,
								s.month_eta,
								s.week_eta,
								get_booking_nr(s.id_ord_schedule) booking_nr,
								get_vessel_name(s.id_ord_Schedule) vessel_name,
								get_contact_code(get_carrier_id(s.id_ord_schedule)) carrier
								from v_order_schedule s,v_schedule_calc c
								where c.id_ord_schedule=s.id_ord_schedule
								and c.pipeline_id in ( 296, 298 )
								and s.ord_imp_contact_id=$id_company
							";
						} else {
							$sql = "select c.pipeline_name,
								s.ord_order_id,
								s.id_ord_schedule,
								s.order_ship_nr,
								s.ord_cus_contact_code, 
								s.ord_cus_contact_id,
								s.ord_fa_contact_id,
								s.ord_imp_contact_id,
								s.supplier_contact_id,
								s.supplier_lastname,
								s.ord_sm_person_id,
								c.importer,
								c.importer_person,
								get_contact_code(s.ord_fa_contact_id) fa_contact_code,
								get_contact_code(c.supplier_contact_id) sup_contact_code,
								get_contact_name(s.ord_sm_person_id) sm_name,
								get_contact_code(s.ord_sm_person_id) sm_code,
								(s.customer_reference_nr::text || '.'::text) || s.customer_ref_ship_nr::text as no_cus,
								(s.ord_fa_reference_nr::text || '.'::text) || s.fa_reference_nr::text as no_fa,
								(s.sup_reference_nr::text || '.'::text) || s.supplier_reference_nr::text as no_sup,
								(s.order_nr::text || '.'::text)|| s.order_ship_nr::text as no_imp,
								s.product_code,
								s.nr_containers,
								s.package_name,
								ltrim(to_char(s.weight_shipment,'999G999'),' ') weight_total,
								getregvalue(s.supplier_incoterms_id) sup_incoterms,
								getregvalue(s.cus_incoterms_id) cus_incoterms,
								s.pod_name,
								s.pod_code,
								s.month_etd,
								s.week_etd,
								getregvalue(s.price_currency_id) sup_currency,
								s.price_sup_eur as sup_cost_mt,
								c.total_product,
								c.total_services,
								c.total_cost,
								c.unit_cost,
								c.total_value,
								c.unit_value,
								c.margin_tone,
								c.margin_tone*c.weight_shipment margin_total,
								getregvalue(c.proposal_currency_id) sales_currency,
								get_port_name(s.pol_id) pol_name,
								s.pol_code,
								s.month_eta,
								s.week_eta,
								get_booking_nr(s.id_ord_schedule) booking_nr,
								get_vessel_name(s.id_ord_Schedule) vessel_name,
								get_contact_code(get_carrier_id(s.id_ord_schedule)) carrier
								from v_order_schedule s,v_schedule_calc c
								where ( c.id_ord_schedule=s.id_ord_schedule
								and c.pipeline_id in ( 296, 298 )
								and s.ord_imp_contact_id=$id_company  )
							";
						}
						
					} else
					if($id_supchain_type==113){
						$sql = " select c.pipeline_name,
							s.ord_order_id,
							s.id_ord_schedule,
							s.order_ship_nr,
							s.ord_cus_contact_code, 
							s.ord_cus_contact_id,
							s.ord_fa_contact_id,
							s.ord_imp_contact_id,
							s.supplier_contact_id,
							s.supplier_lastname,
							s.ord_sm_person_id,
							c.importer,
							c.importer_person,
							get_contact_code(s.ord_fa_contact_id) fa_contact_code,
							get_contact_code(c.supplier_contact_id) sup_contact_code,
							get_contact_name(s.ord_sm_person_id) sm_name,
							get_contact_code(s.ord_sm_person_id) sm_code,
							(s.customer_reference_nr::text || '.'::text) || s.customer_ref_ship_nr::text as no_cus,
							(s.ord_fa_reference_nr::text || '.'::text) || s.fa_reference_nr::text as no_fa,
							(s.sup_reference_nr::text || '.'::text) || s.supplier_reference_nr::text as no_sup,
							(s.order_nr::text || '.'::text)|| s.order_ship_nr::text as no_imp,
							s.product_code,
							s.nr_containers,
							s.package_name,
							ltrim(to_char(s.weight_shipment,'999G999'),' ') weight_total,
							getregvalue(s.supplier_incoterms_id) sup_incoterms,
							getregvalue(s.cus_incoterms_id) cus_incoterms,
							s.pod_name,
							s.pod_code,
							s.month_etd,
							s.week_etd,
							getregvalue(s.price_currency_id) sup_currency,
							s.price_sup_eur as sup_cost_mt,
							c.total_product,
							c.total_services,
							c.total_cost,
							c.unit_cost,
							c.total_value,
							c.unit_value,
							c.margin_tone,
							c.margin_tone*c.weight_shipment margin_total,
							getregvalue(c.proposal_currency_id) sales_currency,
							get_port_name(s.pol_id) pol_name,
							s.pol_code,
							s.month_eta,
							s.week_eta,
							get_booking_nr(s.id_ord_schedule) booking_nr,
							get_vessel_name(s.id_ord_Schedule) vessel_name,
							get_contact_code(get_carrier_id(s.id_ord_schedule)) carrier
							from v_order_schedule s,v_schedule_calc c
							where c.id_ord_schedule=s.id_ord_schedule
							and c.pipeline_id in ( 296, 298 )
							and s.id_ord_schedule in ( select distinct id_ord_schedule from ord_ocean_schedule where supplier_contact_id=$id_company ) 
						";
			
					} else 
					if($id_supchain_type==289){
						$sql = "select c.pipeline_name,
                            s.ord_order_id,
                            s.id_ord_schedule,
                            s.order_ship_nr,
							s.ord_cus_contact_code,
                            s.ord_cus_contact_id,
                            s.ord_fa_contact_id,
                            s.ord_imp_contact_id,
                            s.supplier_contact_id,
                            s.supplier_lastname,
                            s.ord_sm_person_id,
                            c.importer,
                            c.importer_person,
							get_contact_code(s.ord_fa_contact_id) fa_contact_code,
                            get_contact_code(c.supplier_contact_id) sup_contact_code,
                            get_contact_name(s.ord_sm_person_id) sm_name,
                            get_contact_code(s.ord_sm_person_id) sm_code,
                            (s.customer_reference_nr::text || '.'::text) || s.customer_ref_ship_nr::text as no_cus,
                            (s.ord_fa_reference_nr::text || '.'::text) || s.fa_reference_nr::text as no_fa,
                            (s.sup_reference_nr::text || '.'::text) || s.supplier_reference_nr::text as no_sup,
                            (s.order_nr::text || '.'::text)|| s.order_ship_nr::text as no_imp,
                            s.product_code,
                            s.nr_containers,
                            s.package_name,
                            ltrim(to_char(s.weight_shipment,'999G999'),' ') weight_total,
                            getregvalue(s.supplier_incoterms_id) sup_incoterms,
                            getregvalue(s.cus_incoterms_id) cus_incoterms,
                            s.pod_name,
                            s.pod_code,
                            s.month_etd,
                            s.week_etd,
                            getregvalue(s.price_currency_id) sup_currency,
                            s.price_sup_eur as sup_cost_mt,
                            c.total_product,
                            c.total_services,
                            c.total_cost,
                            c.unit_cost,
                            c.total_value,
                            c.unit_value,
                            c.margin_tone,
                            c.margin_tone*c.weight_shipment margin_total,
                            getregvalue(c.proposal_currency_id) sales_currency,
                            get_port_name(s.pol_id) pol_name,
                            s.pol_code,
                            s.month_eta,
                            s.week_eta,
                            get_booking_nr(s.id_ord_schedule) booking_nr,
                            get_vessel_name(s.id_ord_Schedule) vessel_name,
                            get_contact_code(get_carrier_id(s.id_ord_schedule)) carrier
                            from v_order_schedule s,v_schedule_calc c
                            where c.id_ord_schedule=s.id_ord_schedule
                            and c.pipeline_id in ( 296, 298 )
                            and s.id_ord_schedule in ( select distinct ord_schedule_id from ord_con_booking where fa_contact_id=$id_company )
						";
					} 
				}

			$data_list = '';
			
			$result = pg_query($conn, $sql);
		
			while($arr = pg_fetch_assoc($result)){  
				
				if($id_user_supchain_type==312){
					$sn=$arr['no_sup'];
					$sn_cus='<td>'.$arr['no_cus'].'</td>';
					$week='<td>'.$arr['week_eta'].'</td>';
				} else {
					if($id_supchain_type==110){
						$sn=$arr['no_cus'];
						$sn_cus='';
						$week='';
					} else
					if($id_supchain_type==112){
						$sn=$arr['no_imp'];
						$sn_cus='<td>'.$arr['no_cus'].'</td>';
						$week='';
					} else
					if($id_supchain_type==113){
						$sn=$arr['no_sup'];
						$sn_cus='<td>'.$arr['no_cus'].'</td>';
						$week='';
					} else 
					if($id_supchain_type==289){
						$sn=$arr['no_fa'];
						$sn_cus='';
						$week='';
					} 
				}
				
				if(!empty($arr['booking_nr'])){
					$booking = '<i class="fa fa-check"></i>';
				} else { $booking = ''; }
				
				$data_list .= '<tr>
					<td>'.substr($arr['pipeline_name'],0,3).'</td>
					<td>'.$arr['ord_cus_contact_code'].'</td>
					<td>'.$sn.'</td>
					'.$sn_cus.'
					<td>'.$arr['supplier_lastname'].'</td>
					<td>'.$arr['nr_containers'].'</td>
					<td>'.substr($arr['package_name'],0,6).'</td>
					<td>'.$arr['product_code'].'</td>
					<td><a onclick="analyticCalendar(\''. $arr['month_etd'] .'\');" class="ana_cal_date">'.$arr['month_etd'].'</a></td>
					'.$week.'
					<td>'.$booking.'</td>
				</tr>';
			}
			
			
			$a_ = $_GET['a_week'];
			
			// Arrivals
			if($id_user_supchain_type==312){
				$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
					get_reference_nr_1(b.ord_schedule_id, 113), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id, os.ord_sm_person_id
					from v_con_booking b, ord_con_loading_header l, v_order_schedule os
					where eta>date_trunc('week', current_date)
					and eta<date_trunc('week', current_date) + interval '$a_ week'
					and l.ord_schedule_id=b.ord_schedule_id
					and l.ord_schedule_id=os.id_ord_schedule
					and os.ord_sm_person_id=$id_contact
					order by eta asc
				";
				
			} else {
				if($id_supchain_type==110){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 110), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.ord_cus_contact_id=$id_company
						order by eta asc
					";
					
				} else
				if($id_supchain_type==112){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 112), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id, os.ord_sm_person_id, os.ord_imp_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.ord_imp_contact_id=$id_company
						order by eta asc
					";
					
				} else
				if($id_supchain_type==113){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 113), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.supplier_contact_id=$id_company
						order by eta asc
					";
			
				} else 
				if($id_supchain_type==289){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 289), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.ord_fa_contact_id=$id_company
						order by eta asc
					";
				
				} else 
				if($id_supchain_type==288){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 113), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.supplier_contact_id=$id_company
						order by eta asc
					";
				} 
			}
	
			$arrivals_list = '';
			
			$result_arrival = pg_query($conn, $sql_arrival);
		
			while($arr_arrival = pg_fetch_assoc($result_arrival)){
				$date_arrival = explode(" ", $arr_arrival['eta']);
				
				$arrivals_list .= '<tr>
					<td>'.$arr_arrival['get_reference_nr_1'].'</td>
					<td>'.$date_arrival[0].'</td>
					<td>'.$arr_arrival['pod_code'].'</td>
				</tr>';
			}
			
			$d_ = $_GET['d_week'];
			
			// Departures
			if($id_user_supchain_type==312){
				$sql_departure = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, 
				b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, 
				l.container_nr, get_reference_nr_1(b.ord_schedule_id, 312), get_product_code(b.ord_order_id) as product_code
					from v_con_booking b, ord_con_loading_header l
					where etd>date_trunc('week', current_date)
					and etd<date_trunc('week', current_date) + interval '$d_ week'
					and l.ord_schedule_id=b.ord_schedule_id
					and b.ord_sm_person_id=$id_contact
					order by etd asc
				";
				
			} else {
				if($id_supchain_type==110){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, 
					b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, 
					l.container_nr, get_reference_nr_1(b.ord_schedule_id, 110), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.ord_cus_contact_id=$id_company
						order by etd asc
					";
					
				} else
				if($id_supchain_type==112){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, 
					b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, 
					l.container_nr, get_reference_nr_1(b.ord_schedule_id, 112), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.ord_imp_contact_id=$id_company
						order by etd asc
					";
					
				} else
				if($id_supchain_type==113){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, 
					b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, 
					l.container_nr, get_reference_nr_1(b.ord_schedule_id, 113), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.supplier_contact_id=$id_company
						order by etd asc
					";
			
				} else 
				if($id_supchain_type==289){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, 
					b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, 
					l.container_nr, get_reference_nr_1(b.ord_schedule_id, 289), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.fa_contact_id=$id_company
						order by etd asc
					";
					
				} else 
				if($id_supchain_type==288){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, 
					b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, 
					l.container_nr, get_reference_nr_1(b.ord_schedule_id, 288), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.forwarder_company_id=$id_company
						order by etd asc
					";
				} 
			}
	
			$departures_list = '';
			
			$result_departure = pg_query($conn, $sql_departure);
		
			while($arr_departure = pg_fetch_assoc($result_departure)){
				$date_departure = explode(" ", $arr_departure['etd']);
				
				$departures_list .= '<tr>
					<td>'.$arr_departure['get_reference_nr_1'].'</td>
					<td>'.$date_departure[0].'</td>
					<td>'.$arr_departure['pol_code'].'</td>
				</tr>';
			}
			
			$type='';
			$l_cond='';
			
			$l_ = $_GET['l_week'];
			
			// Loading
			if($id_user_supchain_type==312){
				$l_cond = "and os.ord_sm_person_id=$id_contact ";
				$type=$id_user_supchain_type;
			} else {
				if($id_supchain_type==110){
					$l_cond = "and os.ord_cus_contact_id=$id_company ";
					
				} else
				if($id_supchain_type==112){
					if($id_company == 717){
						$l_cond = "and os.ord_imp_contact_id=$id_company ";
					} else {
						$l_cond = "";
					}
					
				} else
				if($id_supchain_type==113){
					$l_cond = "and os.supplier_contact_id=$id_company ";
			
				} else 
				if($id_supchain_type==289){
					$l_cond = "and os.ord_fa_contact_id=$id_company ";
					
				} else 
				if($id_supchain_type==288){
					$l_cond = "and b.forwarder_company_id=$id_company ";
				} 
				$type=$id_supchain_type;
			}
			
			$sql_loading = "select b.tport_etd,b.trans_port_id, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, 
				b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
				get_reference_nr_1 (b.ord_schedule_id, $type), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id, 
				os.ord_sm_person_id, os.ord_imp_contact_id, con_load_date_from
				from v_con_booking b, ord_con_loading_header l, v_order_schedule os
				WHERE con_load_date_from > date_trunc ('week', current_date -10)
				AND con_load_date_to <date_trunc ('week', current_date) + INTERVAL '$l_ week'
				and l.ord_schedule_id=b.ord_schedule_id
				and l.ord_schedule_id=os.id_ord_schedule
				$l_cond
				order by eta
			";

			$loading_list = '';
			$result_loading = pg_query($conn, $sql_loading);
		
			while($arr_loading = pg_fetch_assoc($result_loading)){
				$loading_list .= '<tr>
					<td>'.$arr_loading['get_reference_nr_1'].'</td>
					<td>'.$arr_loading['pol_code'].'</td>
					<td>'.$arr_loading['con_load_date_from'].'</td>
				</tr>';
			}
			
			
			$dom = $data_list.'##'.$arrivals_list.'##'.$departures_list.'##'.$loading_list;
		
		break;
		
		
		case "dashboard_account":
		
			$id_user = $_SESSION['id_user'];
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			
			if(isset($_GET['id_con_booking'])){
				$id_con_booking = $_GET['id_con_booking'];
			} else { $id_con_booking = 0; }
			
			if(isset($_GET['inv_done'])){
				$inv_done = $_GET['inv_done'];
				$conf = " AND v.inv_done = $inv_done";
			} else { $conf = ""; }
			
			
			if($id_supchain_type==112){
				$sql = "SELECT s.id_ord_schedule, v.id_con_booking,
				 get_contact_code (s.ord_imp_contact_id) importer,
				 s.product_code,
				 s.ord_cus_contact_code,
				 s.order_nr||'-'||s.order_ship_nr as order_nr,
				 s.customer_ref_ship_nr,
				 to_char (v.pol_etd_actual, 'dd-mm-yyyy') AS etd,
				 to_char (v.pod_eta_actual, 'yyyy-mm-dd') AS eta_ocean,
				 get_pod_act_barge(v.id_ord_schedule) as eta_basel,
				 s.pod_code,
				 v.inv1_date,
				 v.inv1_due_date1,
				 v.inv1_due_date2,
				 v.inv1_paid1,
				 v.inv1_paid2,
				 v.inv2_date,
				 v.inv2_due_date1,
				 v.inv2_paid1,
				 v.weight_ticket_in,
				 v.inv_done,
				 v.inv_note
				FROM v_order_schedule s, v_con_booking v
				WHERE s.id_ord_schedule = v.ord_schedule_id 
					AND v.booking_segment = 1
					AND s.ord_imp_contact_id=$id_company
					AND s.pipeline_sched_id=299
					$conf
				ORDER BY order_nr, s.customer_ref_ship_nr";

				$result = pg_query($conn, $sql);
				
				$data_list = '';
				while($arr = pg_fetch_assoc($result)){  
					
					if($arr['weight_ticket_in']==1){
						$wticket_in = '<td><input type="checkbox" class="i-checks weight_ticket" checked value="'.$arr['weight_ticket_in'].'##'.$arr['id_con_booking'].'"></td>';
					} else { $wticket_in='<td><input type="checkbox" class="i-checks weight_ticket" value="'.$arr['weight_ticket_in'].'##'.$arr['id_con_booking'].'"></td>'; }
					
					if($arr['inv_done']==1){
						$inv_done = '<td><input type="checkbox" class="i-checks inv_done" checked value="'.$arr['inv_done'].'##'.$arr['id_con_booking'].'##'.$arr['id_ord_schedule'].'"></td>';
					} else { $inv_done='<td><input type="checkbox" class="i-checks inv_done" value="'.$arr['inv_done'].'##'.$arr['id_con_booking'].'##'.$arr['id_ord_schedule'].'"></td>'; }
					
					
					$data_list .= '<tr>
						<td>'.$arr['ord_cus_contact_code'].'</td>
						<td>'.$arr['product_code'].'</td>
						<td>'.$arr['order_nr'].'</td>
						<td>'.$arr['customer_ref_ship_nr'].'</td>
						<td>'.$arr['etd'].'</td>
						<td>'.$arr['eta_ocean'].'</td>
						<td>'.$arr['eta_basel'].'</td>
						<td>'.$arr['pod_code'].'</td>
						<td>--</td>';
						
						if(($id_con_booking!=0)&&($id_con_booking==$arr['id_con_booking'])){
							$data_list .= '<td><input class="form-control edit_delivery_date" style="width:50px;" type="text" value="'.$arr['inv1_due_date1'].'" id="inv1_due_date1_'.$arr['id_con_booking'].'" /></td>
							<td><input class="form-control edit_delivery_date" type="text" style="width:50px;" value="'.$arr['inv1_due_date2'].'" id="inv1_due_date2_'.$arr['id_con_booking'].'" /></td>
							<td><input class="form-control edit_delivery_date" type="text" style="width:50px;" value="'.$arr['inv1_paid1'].'" id="inv1_paid1_'.$arr['id_con_booking'].'" /></td>
							<td><input class="form-control edit_delivery_date" type="text" style="width:50px;" value="'.$arr['inv1_paid2'].'" id="inv1_paid2_'.$arr['id_con_booking'].'" /></td>
							<td><input class="form-control edit_delivery_date" type="text" style="width:50px;" value="'.$arr['inv2_due_date1'].'" id="inv2_due_date1_'.$arr['id_con_booking'].'" /></td>
							<td><input class="form-control edit_delivery_date" type="text" style="width:50px;" value="'.$arr['inv2_paid1'].'" id="inv2_paid1_'.$arr['id_con_booking'].'" /></td>
							<td><input class="form-control" type="text" style="width:50px;" value="'.$arr['inv_note'].'" id="inv_note_'.$arr['id_con_booking'].'" /></td>
							'.$wticket_in.$inv_done.'
							<td><a href="#" onclick="saveEditedDbAccount('.$arr['id_con_booking'].');" class="btn btn-white btn-sm"><i class="fa fa-check" style="color:green;"></i></a> 
							<a href="#" onclick="dashboard(4);" class="btn btn-white btn-sm"><i class="fa fa-times" style="color:red;"></i></a></td>';
						
						} else {
							$data_list .= '<td>'.$arr['inv1_due_date1'].'</td>
							<td>'.$arr['inv1_due_date2'].'</td>
							<td>'.$arr['inv1_paid1'].'</td>
							<td>'.$arr['inv1_paid2'].'</td>
							<td>'.$arr['inv2_due_date1'].'</td>
							<td>'.$arr['inv2_paid1'].'</td>
							<td>'.$arr['inv_note'].'</td>
							'.$wticket_in.$inv_done.'
							<td><a href="#" onclick="editDbAccount('.$arr['id_con_booking'].');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i></a></td>';
						}
					
					$data_list .= '</tr>';
				}
				
				$dom = $data_list;
			
			} else {
				$dom="";
			} 
			
		break;
		
		
		case "departures_by_week":
		
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
			$d_ = $_GET['d_week'];
			
			// Departures
			if($id_user_supchain_type==312){
				$sql_departure = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, 
				b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr, 
				get_reference_nr_1(b.ord_schedule_id, 312), get_product_code(b.ord_order_id) as product_code
					from v_con_booking b, ord_con_loading_header l
					where etd>date_trunc('week', current_date)
					and etd<date_trunc('week', current_date) + interval '$d_ week'
					and l.ord_schedule_id=b.ord_schedule_id
					and b.pipeline_sched_id=298
					and b.ord_sm_person_id=$id_contact
				";
				
			} else {
				if($id_supchain_type==110){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, 
					b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr, 
					get_reference_nr_1(b.ord_schedule_id, 110), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.pipeline_sched_id=298
						and b.ord_cus_contact_id=$id_company
					";
					
				} else
				if($id_supchain_type==112){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, 
					b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr, 
					get_reference_nr_1(b.ord_schedule_id, 112), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.pipeline_sched_id=298
						and b.ord_imp_contact_id=$id_company
					";
					
				} else
				if($id_supchain_type==113){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, 
					b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr, 
					get_reference_nr_1(b.ord_schedule_id, 113), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.pipeline_sched_id=298
						and b.supplier_contact_id=$id_company
					";
			
				} else 
				if($id_supchain_type==289){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, 
					b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr, 
					get_reference_nr_1(b.ord_schedule_id, 289), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.pipeline_sched_id=298
						and b.fa_contact_id=$id_company
					";
					
				} else 
				if($id_supchain_type==288){
					$sql_departure = "select b.id_ord_schedule, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, 
					b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr, 
					get_reference_nr_1(b.ord_schedule_id, 288), get_product_code(b.ord_order_id) as product_code
						from v_con_booking b, ord_con_loading_header l
						where etd>date_trunc('week', current_date)
						and etd<date_trunc('week', current_date) + interval '$d_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and b.pipeline_sched_id=298
						and b.forwarder_company_id=$id_company
					";
				} 
			}
		
			$departures_list = '';
			$result_departure = pg_query($conn, $sql_departure);
		
			while($arr_departure = pg_fetch_assoc($result_departure)){
				$date_departure = explode(" ", $arr_departure['etd']);
				
				$departures_list .= '<tr>
					<td>'.$arr_departure['get_reference_nr_1'].'</td>
					<td>'.$date_departure[0].'</td>
					<td>'.$arr_departure['pol_code'].'</td>
				</tr>';
			}
			
			$dom = $departures_list;
			
		break;
		
		
		case "arrivals_by_week":
		
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
		
			$a_ = $_GET['a_week'];
			
			// Arrivals
			if($id_user_supchain_type==312){
				$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
					get_reference_nr_1(b.ord_schedule_id, 113), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id, os.ord_sm_person_id
					from v_con_booking b, ord_con_loading_header l, v_order_schedule os
					where eta>date_trunc('week', current_date)
					and eta<date_trunc('week', current_date) + interval '$a_ week'
					and l.ord_schedule_id=b.ord_schedule_id
					and l.ord_schedule_id=os.id_ord_schedule
					and os.ord_sm_person_id=$id_contact
					order by eta
				";
				
			} else {
				if($id_supchain_type==110){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 110), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.ord_cus_contact_id=$id_company
						order by eta
					";
					
				} else
				if($id_supchain_type==112){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 112), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id, os.ord_sm_person_id, os.ord_imp_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.ord_imp_contact_id=$id_company
						order by eta
					";
					
				} else
				if($id_supchain_type==113){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 113), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.supplier_contact_id=$id_company
					";
			
				} else 
				if($id_supchain_type==289){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 289), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.ord_fa_contact_id=$id_company
						order by eta
					";
				
				} else 
				if($id_supchain_type==288){
					$sql_arrival = "select b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
						get_reference_nr_1(b.ord_schedule_id, 113), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id
						from v_con_booking b, ord_con_loading_header l, v_order_schedule os
						where eta>date_trunc('week', current_date)
						and eta<date_trunc('week', current_date) + interval '$a_ week'
						and l.ord_schedule_id=b.ord_schedule_id
						and l.ord_schedule_id=os.id_ord_schedule
						and os.supplier_contact_id=$id_company
					";
				} 
			}
	
			$arrivals_list = '';
			$result_arrival = pg_query($conn, $sql_arrival);
		
			while($arr_arrival = pg_fetch_assoc($result_arrival)){
				$date_arrival = explode(" ", $arr_arrival['eta']); 
				
				$arrivals_list .= '<tr>
					<td>'.$arr_arrival['get_reference_nr_1'].'</td>
					<td>'.$date_arrival[0].'</td>
					<td>'.$arr_arrival['pod_code'].'</td>
				</tr>';
			}
			
			$dom = $arrivals_list;
			
		break;
		
		
		case "loading_by_week":
		
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
		
			$l_ = $_GET['l_week'];
			
			$type='';
			$l_cond='';
			
			// Loading
			if($id_user_supchain_type==312){
				$l_cond = "and os.ord_sm_person_id=$id_contact ";
				$type=$id_user_supchain_type;
			} else {
				if($id_supchain_type==110){
					$l_cond = "and os.ord_cus_contact_id=$id_company ";
					
				} else
				if($id_supchain_type==112){
					if($id_company == 717){
						$l_cond = "and os.ord_imp_contact_id=$id_company ";
					} else {
						$l_cond = "";
					}
					
				} else
				if($id_supchain_type==113){
					$l_cond = "and os.supplier_contact_id=$id_company ";
			
				} else 
				if($id_supchain_type==289){
					$l_cond = "and os.ord_fa_contact_id=$id_company ";
					
				} else 
				if($id_supchain_type==288){
					$l_cond = "and b.forwarder_company_id=$id_company ";
				} 
				$type=$id_supchain_type;
			}
	
			$sql_loading = "select b.tport_etd,b.trans_port_id, b.vessel_mmsi_id, b.vessel_name, b.etd, b.eta,b.pol_name, b.pod_name, 
				b.pol_code, b.pod_code, b.booking_nr, b.booking_segment, l.total_vgm_weight, l.container_nr,
				get_reference_nr_1 (b.ord_schedule_id, $type), os.ord_cus_contact_id, os.ord_fa_contact_id, os.supplier_contact_id, 
				os.ord_sm_person_id, os.ord_imp_contact_id, con_load_date_from
				from v_con_booking b, ord_con_loading_header l, v_order_schedule os
				WHERE con_load_date_from > date_trunc ('week', current_date -10)
				AND con_load_date_to <date_trunc ('week', current_date) + INTERVAL '$l_ week'
				and l.ord_schedule_id=b.ord_schedule_id
				and l.ord_schedule_id=os.id_ord_schedule
				$l_cond
				order by eta
			";
			
			$loading_list = '';
			$result_loading = pg_query($conn, $sql_loading);
		
			while($arr_loading = pg_fetch_assoc($result_loading)){
				$loading_list .= '<tr>
					<td>'.$arr_loading['get_reference_nr_1'].'</td>
					<td>'.$arr_loading['pol_code'].'</td>
					<td>'.$arr_loading['con_load_date_from'].'</td>
				</tr>';
			}
			
			$dom = $loading_list;
			
		break;
		
		
		case "save_edited_db_account":
		
			$id_con_booking = $_GET['id_con_booking'];
			
			if(isset($_GET['inv1_due_date1'])){
				$inv1_due_date1 = $_GET['inv1_due_date1'];
				$req_inv1_due_date1 = " inv1_due_date1='$inv1_due_date1',";
			} else { $req_inv1_due_date1 = " inv1_due_date1=NULL,"; }
			
			if(isset($_GET['inv1_due_date2'])){
				$inv1_due_date2 = $_GET['inv1_due_date2'];
				$req_inv1_due_date2 = " inv1_due_date2='$inv1_due_date2',";
			} else { $req_inv1_due_date2 = " inv1_due_date2=NULL,"; }
			
			if(isset($_GET['inv1_paid1'])){
				$inv1_paid1 = $_GET['inv1_paid1'];
				$req_inv1_paid1 = " inv1_paid1='$inv1_paid1',";
			} else { $req_inv1_paid1 = " inv1_paid1=NULL,"; }
			
			if(isset($_GET['inv1_paid2'])){
				$inv1_paid2 = $_GET['inv1_paid2'];
				$req_inv1_paid2 = " inv1_paid2='$inv1_paid2',";
			} else { $req_inv1_paid2 = " inv1_paid2=NULL,"; }
			
			if(isset($_GET['inv2_due_date1'])){
				$inv2_due_date1 = $_GET['inv2_due_date1'];
				$req_inv2_due_date1 = " inv2_due_date1='$inv2_due_date1',";
			} else { $req_inv2_due_date1 = " inv2_due_date1=NULL,"; }
			
			if(isset($_GET['inv2_paid1'])){
				$inv2_paid1 = $_GET['inv2_paid1'];
				$req_inv2_paid1 = " inv2_paid1='$inv2_paid1',";
			} else { $req_inv2_paid1 = " inv2_paid1=NULL,"; }
			
			if(isset($_GET['inv_note'])){
				$inv_note = $_GET['inv_note'];
				$req_inv_note = " inv_note='$inv_note',";
			} else { $req_inv_note = " inv_note=NULL,"; }

			$createdby = $_SESSION['id_contact'];
			$created_date = gmdate("Y-m-d H:i");
		
			$sql = "UPDATE public.ord_con_booking
				SET $req_inv1_due_date1 $req_inv1_due_date2 $req_inv1_paid1 $req_inv1_paid2
				$req_inv2_due_date1 $req_inv2_paid1 $req_inv_note 
				modified_by='$createdby', modified_date='$created_date'
			WHERE id_con_booking=$id_con_booking";

			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "weight_ticket_in_value":
		
			$id_con_booking = $_GET['id_con_booking'];
			$weight_ticket_in = $_GET['weight_ticket_in'];
			
			$sql = "UPDATE public.ord_con_booking
				SET weight_ticket_in=$weight_ticket_in
			WHERE id_con_booking=$id_con_booking";
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "inv_done_value":
		
			$id_con_booking = $_GET['id_con_booking'];
			$inv_done = $_GET['inv_done'];
			
			if(isset($_GET['id_ord_schedule']) && $inv_done==1){
				$id_ord_schedule = $_GET['id_ord_schedule'];
				
				$sql_ppl = "UPDATE public.ord_ocean_schedule
					SET pipeline_sched_id=300
				WHERE id_ord_schedule=$id_ord_schedule";
				pg_query($conn, $sql_ppl);
			}
			
			$sql = "UPDATE public.ord_con_booking
				SET inv_done=$inv_done
			WHERE id_con_booking=$id_con_booking";
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
	}
	
}

echo $dom;