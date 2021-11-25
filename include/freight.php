<?php

session_start();
error_reporting(0);

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
		
		case "cheick_for_freight":
		
			$pol_id = $_GET["pol_id"];
			$pod_id = $_GET["pod_id"];
			$cus_incoterms_id = $_GET["cus_incoterms_id"];
			$package_type_id = $_GET["package_type_id"];
			$id_ord_schedule = $_GET["id_ord_schedule"];
			
			$sql = "SELECT id_con_box_fr,shipping_company,freight_eur,freight_usd,add_eur,    
				add_usd,total_eur,packaging_type_id,packaging_type_name,transit_time,trans_type_id,    
				trans_type_name,trans_location_id,trans_location_name,dem_pol_free,dem_pol_cost_after,    
				dem_pod_free,dem_pod_cost_after,returns_empty_id,rate_valid_until,total_usd,freight_chf,    
				total_chf,add_chf,transport_type_id,transport_type_name,weight_packaging_type,    
				transit_time_position,transit_time_position_ready,trans_delay,id_owner,incoterm_id,    
				incoterm_name,pod_townport_id,pod_name,pol_townport_id,pol_name,sup_incoterms,sup_incoterms_name 
			from v_con_freight 
				Where  
				pol_townport_id=$pol_id 
				And pod_townport_id=$pod_id 
				And incoterm_id=$cus_incoterms_id 
				And packaging_type_id=$package_type_id 
			";
			
			$result = pg_query($conn, $sql);
			$count = pg_num_rows($result);
		
			if($count==0){
				$dom="0##0";
				
			} else {
				$freight_list='';
				while($row = pg_fetch_assoc($result)){
					
					if($row['total_usd'] ==""){ $total_usd = '--'; } else { $total_usd = $row['total_usd']; }
					if($row['total_eur'] ==""){ $total_eur = '--'; } else { $total_eur = $row['total_eur']; }
					if($row['total_chf'] ==""){ $total_chf = '--'; } else { $total_chf = $row['total_chf']; }
					
					$freight_list .= '<tr>
						<td><input type="radio" value="'. $row['id_con_box_fr'] .'" id="radioFreightList'. $row['id_con_box_fr'] .'" name="id_freight_radio" class="radioBtnFreightListClass"></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['pol_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['incoterm_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['pod_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['shipping_company'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['packaging_type_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $total_usd .' / '. $total_eur .' / '. $total_chf .' </label></td>
					</tr>';
				}
				
				$dom="1##".$freight_list;
			}
			
		break;
		
		
		case "first_separate_freights_list":
		
			$pol_id = $_GET["pol_id"];
			$package_type_id = $_GET["package_type_id"];
			
			$sql = "SELECT id_con_box_fr,shipping_company,freight_eur,freight_usd,add_eur,add_usd,total_eur,packaging_type_id,
				packaging_type_name,transit_time,trans_type_id,trans_type_name,trans_location_id,trans_location_name,dem_pol_free,    
				dem_pol_cost_after,dem_pod_free,dem_pod_cost_after,returns_empty_id,rate_valid_until,total_usd,freight_chf,total_chf,    
				add_chf,transport_type_id,transport_type_name,weight_packaging_type,transit_time_position,transit_time_position_ready,    
				trans_delay,id_owner,incoterm_id,incoterm_name,pod_townport_id,pod_name,pol_townport_id,pol_name,sup_incoterms,
				sup_incoterms_name,total_usd,total_eur,total_chf
			from v_con_freight 
				Where  
				pol_townport_id=$pol_id
				and pod_port_type_id=273
				and packaging_type_id=$package_type_id
			";
		
			$result = pg_query($conn, $sql);
			$count = pg_num_rows($result);
			
			if($count==0){
				$dom="0##0";
				
			} else {
				$freight_list='';
				while($row = pg_fetch_assoc($result)){
					
					if($row['total_usd'] ==""){ $total_usd = '--'; } else { $total_usd = $row['total_usd']; }
					if($row['total_eur'] ==""){ $total_eur = '--'; } else { $total_eur = $row['total_eur']; }
					if($row['total_chf'] ==""){ $total_chf = '--'; } else { $total_chf = $row['total_chf']; }
					
					$freight_list .= '<tr>
						<td><input type="radio" value="'. $row['id_con_box_fr'] .'" id="radioFreightList'. $row['id_con_box_fr'] .'" name="id_freight_radio" class="radioBtnFreightListClass"></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['pol_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['incoterm_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['pod_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['shipping_company'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['packaging_type_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $total_usd .' / '. $total_eur .' / '. $total_chf .'</label></td>
					</tr>';
				}
				
				$dom="1##".$freight_list;
			}
			
		break;
		
		
		case "save_freight":
		
			$id_ord_schedule = $_GET['id_ord_schedule'];
			$id_con_box_fr = $_GET['id_con_box_fr'];
			$sequence_nr = $_GET['sequence_nr'];
			
			$freight_modified_by = $_SESSION['id_user'];
			$freight_modified_date = gmdate("Y/m/d H:i");
			
			$sqlCheick = "SELECT id_ord_schedule_fr FROM ord_schedule_freight 
			WHERE ord_ocean_schedule_id=$id_ord_schedule
			AND ord_con_freight_id=$id_con_box_fr
			AND sequence_nr=$sequence_nr";
			$r_cheick = pg_query($conn, $sqlCheick);
			$count = pg_num_rows($r_cheick);
			
			if($count == 1){
				$row_cheick = pg_fetch_assoc($r_cheick);
				$id_ord_schedule_fr=$row_cheick['id_ord_schedule_fr'];
				
				$sql = "UPDATE ord_schedule_freight SET ord_ocean_schedule_id=$id_ord_schedule, 
					ord_con_freight_id=$id_con_box_fr, sequence_nr=$sequence_nr 
				WHERE id_ord_schedule_fr=$id_ord_schedule_fr";
				
			} else {
				$sql = "Insert into ord_schedule_freight (ord_ocean_schedule_id, ord_con_freight_id, sequence_nr) values ($id_ord_schedule, $id_con_box_fr, $sequence_nr) ";	
			}
			
			$result = pg_query($conn, $sql);
			
			if ($result) {
				$sql_stats = "UPDATE public.ord_ocean_schedule SET freight_calc=1, 
					freight_modified_by = $freight_modified_by, freight_modified_date = '$freight_modified_date'
				WHERE id_ord_schedule='$id_ord_schedule'";
				$rslt = pg_query($conn, $sql_stats);
		
				if($rslt){
					// Get ord_order_id
					$sql_get = "SELECT ord_order_id, nr_shipments, order_ship_nr, pipeline_id FROM public.v_order_schedule WHERE id_ord_schedule=$id_ord_schedule";
					$rst_get = pg_query($conn, $sql_get);
					$row_get = pg_fetch_assoc($rst_get);
				
					$ord_order_id = $row_get['ord_order_id'];
					$nr_shipments = $row_get['nr_shipments'];
					$order_ship_nr = $row_get['order_ship_nr'];
					$pipeline_id = $row_get['pipeline_id'];
					
					if($nr_shipments == $order_ship_nr){
						$last_shipment = 1;
					} else {
						$last_shipment = 0;
					}
					
					// Update pipeline_id in ord_order
					$sql_uord = "UPDATE public.ord_order SET freight_status=1, calculate_status=1 WHERE id_ord_order='$ord_order_id'";
					$rs_uord = pg_query($conn, $sql_uord) or die(pg_last_error());
				
					$dom='1#'.$last_shipment.'#'.$ord_order_id.'#'.$pipeline_id;
				} else {
					$sql_del = "delete from ord_schedule_freight where ord_ocean_schedule_id=$id_ord_schedule and ord_con_freight_id=$id_con_box_fr";
					$result_del = pg_query($conn, $sql_del);
					$dom='0#0';
				}
				
			} else {
				$dom='0#0';
			}
		
		break;
		
		
		case "list_of_saved_freight":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$order_ship_nr = $_GET["order_ship_nr"];
			
			$sql = "Select f.id_con_box_fr,f.shipping_company,f.dem_pol_free,f.dem_pod_free,f.incoterm_name,f.pod_name,f.pol_name 
			From v_con_freight f, ord_schedule_freight sf 
				Where 
				sf.ord_ocean_schedule_id=$id_ord_schedule and 
				sf.ord_con_freight_id = f.id_con_box_fr  
				Order by sf.sequence_nr 
			";
			
			$result = pg_query($conn, $sql);
			$count = pg_num_rows($result);
		
			if($count==0){
				$sql_grid = " SELECT pol_id, order_pod_id, order_incoterms_id, package_type_id, id_ord_schedule, freight_calc, ord_order_id
					FROM public.v_order_schedule 
				WHERE id_ord_schedule=$id_ord_schedule";
				
				$result_grid = pg_query($conn, $sql_grid);
				$row = pg_fetch_assoc($result_grid);
				$ord_order_id = $row['ord_order_id'];
			
				if($ord_order_id!=""){
					$sql = " SELECT freight_status
						FROM public.v_order
					WHERE id_ord_order=$ord_order_id";
					
					$result = pg_query($conn, $sql);
					$r = pg_fetch_assoc($result);
					$freight_status = $r['freight_status'];
					
				} else {
					$freight_status="";
				}
				
				$dom="0??".$row['pol_id']."#".$row['order_pod_id']."#".$row['order_incoterms_id']."#".$row['package_type_id']."#".$row['id_ord_schedule']."#".$row['freight_calc']."#".$freight_status;
				
			} else {
				
				$freight_list ='';
				$id_con_box_fr ='';
			
				while($row = pg_fetch_assoc($result)){
					$id_con_box_fr = $row['id_con_box_fr'];
					
					$sql_show = "Select id_con_box_fr,shipping_company,freight_eur,freight_usd,add_eur,add_usd,total_eur,packaging_type_id,    
						packaging_type_name,transit_time,trans_type_id,trans_type_name,trans_location_id,trans_location_name,dem_pol_free,    
						dem_pol_cost_after,dem_pod_free,dem_pod_cost_after,returns_empty_id,rate_valid_until,total_usd,freight_chf,total_chf,    
						add_chf,transport_type_id,transport_type_name,weight_packaging_type,transit_time_position,transit_time_position_ready,    
						trans_delay,id_owner,incoterm_id,incoterm_name,pod_townport_id,pod_name,pol_townport_id,pol_name,sup_incoterms,sup_incoterms_name 
					from v_con_freight 
						Where 
						id_con_box_fr = $id_con_box_fr
					";
		
					$result_show = pg_query($conn, $sql_show);
					$row_show = pg_fetch_assoc($result_show);
					
					$freight_list .=$row_show['pol_name']."##".$row_show['dem_pol_free']."##".$row_show['incoterm_name']."##".$row_show['pod_name']."##".$row_show['dem_pod_free']."##".$row_show['shipping_company']."##".$row_show['rate_valid_until']."##".$row_show['packaging_type_name']."##".$row_show['trans_delay']."%%";
				}
				
				$freight_list .="end";
				
				$sql_grid = " SELECT pol_id, order_pod_id, order_incoterms_id, package_type_id, id_ord_schedule, freight_calc, ord_order_id
					FROM public.v_order_schedule 
				WHERE id_ord_schedule=$id_ord_schedule";
	
				$result_grid = pg_query($conn, $sql_grid);
				$row = pg_fetch_assoc($result_grid);
				$ord_order_id = $row['ord_order_id'];
			
				if($ord_order_id!=""){
					$sql = " SELECT freight_status
						FROM public.v_order
					WHERE id_ord_order=$ord_order_id";
					
					$result = pg_query($conn, $sql);
					$r = pg_fetch_assoc($result);
					$freight_status = $r['freight_status'];
					
				} else {
					$freight_status="";
				}
			
				$dom="1??".$freight_list."??".$row['pol_id']."#".$row['order_pod_id']."#".$row['order_incoterms_id']."#".$row['package_type_id']."#".$row['id_ord_schedule']."#".$row['freight_calc']."#".$freight_status;
			}
			
		break;
		
		
		case "second_separate_freights_list":
		
			$pod_id = $_GET["pod_id"];
			$package_type_id = $_GET["package_type_id"];
			
			$sql = "SELECT id_con_box_fr,shipping_company,freight_eur,freight_usd,add_eur,add_usd,total_eur,packaging_type_id,
				packaging_type_name,transit_time,trans_type_id,trans_type_name,trans_location_id,trans_location_name,dem_pol_free,    
				dem_pol_cost_after,dem_pod_free,dem_pod_cost_after,returns_empty_id,rate_valid_until,total_usd,freight_chf,total_chf,    
				add_chf,transport_type_id,transport_type_name,weight_packaging_type,transit_time_position,transit_time_position_ready,    
				trans_delay,id_owner,incoterm_id,incoterm_name,pod_townport_id,pod_name,pol_townport_id,pol_name,sup_incoterms,
				sup_incoterms_name,total_usd,total_eur,total_chf 
			from v_con_freight 
				Where  
				pol_port_type_id=274
				AND pod_townport_id=$pod_id
				and packaging_type_id=$package_type_id 
			";
	
			$result = pg_query($conn, $sql);
			$count = pg_num_rows($result);
			
			if($count==0){
				$dom="0##0";
				
			} else {
				$freight_list='';
				while($row = pg_fetch_assoc($result)){
					
					if($row['total_usd'] ==""){ $total_usd = '--'; } else { $total_usd = $row['total_usd']; }
					if($row['total_eur'] ==""){ $total_eur = '--'; } else { $total_eur = $row['total_eur']; }
					if($row['total_chf'] ==""){ $total_chf = '--'; } else { $total_chf = $row['total_chf']; }
					
					$freight_list .= '<tr>
						<td><input type="radio" value="'. $row['id_con_box_fr'] .'" id="radioFreightList'. $row['id_con_box_fr'] .'" name="id_freight_radio" class="radioBtnFreightListClass"></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['pol_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['incoterm_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['pod_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['shipping_company'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $row['packaging_type_name'] .'</label></td>
						<td><label style="font-weight:normal;" for="radioFreightList'. $row['id_con_box_fr'] .'">'. $total_usd .' / '. $total_eur .' / '. $total_chf .' </label></td>
					</tr>';
				}
				$dom="1##".$freight_list;
			}
			
		break;
		
		
		case "show_saved_freigt":
		
			$pol_name = $_GET["pol_name"];
			$dem_pol_free = $_GET["dem_pol_free"];
			$incoterm_name = $_GET["incoterm_name"];
			$pod_name = $_GET["pod_name"];
			$dem_pod_free = $_GET["dem_pod_free"];
			$carrier = $_GET["carrier"];
			$rate_valid_until = $_GET["rate_valid_until"];
			$packaging_type_name = $_GET["packaging_type_name"];
			$trans_delay = $_GET["trans_delay"];
			
			
			$freight2='<div class="form-group">
                <label class="freight_label">Port of loading : </label> <br/>
                '.$pol_name.'
            </div>
			
			<div class="form-group">
				<label class="freight_label">Free demmurage @charge : </label> <br/>
				'.$dem_pol_free.'
			</div>
			
			<div class="form-group">
                <label class="freight_label">Freight incoterms : </label> <br/>
                '.$incoterm_name.'
            </div>
			
			<div class="form-group">
                <label class="freight_label">Port of discharge : </label> <br/>
                '.$pod_name.'
            </div>
			
			<div class="form-group">
				<label class="freight_label">Free demmurage @discharge : </label> <br/>
				'.$dem_pod_free.'
			</div>
			
			<div class="form-group">
                <label class="freight_label">Carrier : </label> <br/>
                '.$carrier.'
            </div>
			
			<div class="form-group">
                <label class="freight_label">Freight rate : </label> <br/>
                '.$rate_valid_until.'
            </div>
			
			<div class="form-group">
                <label class="freight_label">Packaging type : </label> <br/>
                '.$packaging_type_name.'
            </div>
			
			<div class="form-group">
                <labe class="freight_label"l>Transit days : </label> <br/>
                '.$trans_delay.'
            </div>';
	
			$dom=$freight2;
			
		break;
		
		
		case "freight_modify_by":
		
			$id_ord_schedule = $_GET['id_ord_schedule'];
			
			$sql = "SELECT freight_modified_contact, freight_modified_date
			  FROM public.v_order_schedule WHERE id_ord_schedule = $id_ord_schedule";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			$dom = '<label class="ord_sum_label">Modify by: </label> '. $arr['freight_modified_contact'] .' <br/>
			<label class="ord_sum_label">Modify date: </label> '. $arr['freight_modified_date'];
			
		break;
		
		
		case "save_edited_freight":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$last_shipment = $_GET["last_shipment"];
			
			$freight_modified_by = $_SESSION['id_user'];
			$freight_modified_date = gmdate("Y/m/d H:i");

			$sql_stats = "UPDATE public.ord_ocean_schedule SET 
				freight_modified_by = $freight_modified_by, freight_modified_date = '$freight_modified_date'
			WHERE id_ord_schedule='$id_ord_schedule'";
			$rslt = pg_query($conn, $sql_stats);
			
			if($rslt){
				if($last_shipment == 1){
					$sql_id_order = "SELECT ord_order_id FROM v_order_schedule WHERE id_ord_schedule = $id_ord_schedule ";
					$rslt_id_order = pg_query($conn, $sql_id_order);
					$arr = pg_fetch_assoc($rslt_id_order);
					$id_ord_order = $arr['ord_order_id'];
					
					if($id_ord_order!=""){
						$sql = "UPDATE public.ord_order SET freight_status = 1, calculate_status = 1, proposal_status = 1 WHERE id_ord_order=$id_ord_order ";
						$rslt = pg_query($conn, $sql);
					}
				}
				
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "freight_copy":
			
			$order_ship_nr = $_GET["order_ship_nr"];
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$last_shipment = $_GET["last_shipment"];
			
			$sql_update = 'select * from public."CopyFreight"('.$id_ord_schedule.'); ';
			$result = pg_query($conn, $sql_update);
			
			if ($result) {
				$sql_flag = "UPDATE public.ord_ocean_schedule SET flag_freight = 1 WHERE id_ord_schedule=$id_ord_schedule ";
				$rslt_flag = pg_query($conn, $sql_flag);
				
				if($last_shipment == 1){
					$sql_id_order = "SELECT ord_order_id FROM v_order_schedule WHERE id_ord_schedule = $id_ord_schedule ";
					$rslt_id_order = pg_query($conn, $sql_id_order);
					$arr = pg_fetch_assoc($rslt_id_order);
					$id_ord_order = $arr['ord_order_id'];
					
					if($id_ord_order!=""){
						$sql = "UPDATE public.ord_order SET freight_status = 1, calculate_status = 1 WHERE id_ord_order=$id_ord_order ";
						$rslt = pg_query($conn, $sql);
					}
				}
				
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
	}
	
}

echo $dom;