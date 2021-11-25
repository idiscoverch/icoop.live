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
		
		case "project_list":
		
			$project_list = "";
			
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION["id_supchain_type"];

			if($id_supchain_type == 228){
				$sql = "SELECT 
				  DISTINCT v_project.project_name, 
				  v_project.id_project, 
				  v_project.project_type, 
				  v_project.project_type_name, 
				  v_project_tasks.task_owner_id,
				  v_project_tasks.task_delegated_id
				FROM 
				  public.v_project, 
				  public.v_project_tasks
				WHERE 
				  v_project.id_project = v_project_tasks.id_project
				 AND v_project.project_type=490
				 AND v_project_tasks.task_delegated_id=$id_company
				ORDER BY v_project.id_project DESC";
				
			} else {
				$sql = "SELECT DISTINCT project_name, project_type_name, id_project 
					FROM v_project 
				WHERE project_type=490 AND id_company=$id_company
				ORDER BY id_project DESC";
			}
			
			$result = pg_query($conn, $sql);
			
			while($arr = pg_fetch_assoc($result)){
				$project_name = $arr['project_name'];
				$project_list .= '<li><a href="javascript:dtCollect_agents(\''. $arr['id_project'] .'\');" class="dt_collect_projectName">'. htmlentities($project_name, ENT_QUOTES). '<div style="color:#aaa; font-size:10px; width:100%;">'. $arr['project_type_name'] .'</div></a></li>';
			}
			
			$dom = $project_list;
		
		break;
		
		
		case "agent_list":
		
			$id_project = $_GET['id_project'];
			$id_company = $_SESSION['id_company'];
			
			if($id_project == 0) {
				$sql = "select distinct agent_id, id_project, agent_name, project_name from v_project_tasks WHERE id_company = $id_company ";
			} else {
				$sql = "select distinct agent_id, id_project, agent_name, project_name from v_project_tasks where id_project = $id_project" ;
			}
			
			$result = pg_query($conn, $sql);
			
			$agent_list = '<option value="0">--Agent list--</option>';
			
			while($arr = pg_fetch_assoc($result)){
				$agent_name = $arr['agent_name'];
				$agent_list .= '<option value="'. $arr['agent_id'] .'">'. htmlentities($agent_name, ENT_QUOTES). '</option>';
			}
			
			$dom = '<select class="form-control"  onchange="dtCollect_agentData(\''. $id_project .'\', \'this.value\');">
				'.$agent_list.'
			</select>';
		
		break;
		
		
		case "agent_data":
		
			$id_project = $_GET['id_project'];
			$id_company = $_SESSION['id_company'];
			
			if($id_project == 0) {
				$cond="where id_company = ".$id_company;
			} else {
				$cond="where id_project = ".$id_project;
			}
			
			$sql_agent="select count(*) nr_agents from ( select distinct agent_id from v_project_tasks $cond ) as agents";
			$result_agent = pg_query($conn, $sql_agent);
			$arr_agent = pg_fetch_assoc($result_agent);
			
			$sql_farmer="select count(*) nr_farmers from ( select distinct m.id_contact from mobcrmticker m
			where m.id_agent in ( select distinct agent_id from v_project_tasks $cond ) ) as farmers";
			$result_farmer = pg_query($conn, $sql_farmer);
			$arr_farmer = pg_fetch_assoc($result_farmer);
			
			$sql_plantation="select count(*) nr_plant from ( select distinct m.id_plantation  from mobcrmticker m 
			where m.id_agent in ( select distinct agent_id from v_project_tasks $cond ) ) as farmers ";
			$result_plantation = pg_query($conn, $sql_plantation);
			$arr_plantation = pg_fetch_assoc($result_plantation);
			
			// Surface
			$sql_surface="select sum(surface_ha) as surface_ha from v_plantation where gid_plantation in ( select id_plantation from mobcrmticker where id_agent in 
			( select distinct agent_id from v_project_tasks $cond ) )";
			$result_surface = pg_query($conn, $sql_surface);
			$arr_surface = pg_fetch_assoc($result_surface);
			
			$sql_surface_acres="select sum(area_acres) as area_acres from v_plantation where gid_plantation in ( select id_plantation from mobcrmticker where id_agent in 
			( select distinct agent_id from v_project_tasks $cond ) )";
			$result_surface_acres = pg_query($conn, $sql_surface_acres);
			$arr_surface_acres = pg_fetch_assoc($result_surface_acres);
			
			// Point Mapped
			$sql_ptMapped="select count(*) nr_points from ( select distinct m.id_plantation  from mobcrmticker m 
			 where m.id_agent in ( select distinct agent_id from v_project_tasks $cond ) 
			 and m.id_plantation in ( select id_plantation from plantation where coordx is not null )
			 ) as farmers ";
			$result_ptMapped = pg_query($conn, $sql_ptMapped);
			$arr_ptMapped = pg_fetch_assoc($result_ptMapped);
			
			$sql_ptMapped_all="select count(*) nr_points from ( select distinct m.id_plantation  from mobcrmticker m 
			 where m.id_agent in ( select distinct agent_id from v_project_tasks where id_company = $id_company ) 
			 and m.id_plantation in ( select id_plantation from plantation where coordx is not null )
			 ) as farmers ";
			$result_ptMapped_all = pg_query($conn, $sql_ptMapped_all);
			$arr_ptMapped_all = pg_fetch_assoc($result_ptMapped_all);
			
			// Field Mapped
			$sql_fieldMapped="select count(*) nr_fields from ( select distinct m.id_plantation  from mobcrmticker m 
			 where m.id_agent in ( select distinct agent_id from v_project_tasks $cond ) 
			 and m.id_plantation in ( select id_plantation from plantation where geom_json is not null )
			 ) as farmers ";
			$result_fieldMapped = pg_query($conn, $sql_fieldMapped);
			$arr_fieldMapped = pg_fetch_assoc($result_fieldMapped);
			
			$sql_fieldMapped_all="select count(*) nr_fields from ( select distinct m.id_plantation  from mobcrmticker m 
			 where m.id_agent in ( select distinct agent_id from v_project_tasks where id_company = $id_company ) 
			 and m.id_plantation in ( select id_plantation from plantation where geom_json is not null )
			 ) as farmers ";
			$result_fieldMapped_all = pg_query($conn, $sql_fieldMapped_all);
			$arr_fieldMapped_all = pg_fetch_assoc($result_fieldMapped_all);
			
			// Household
			$sql_houseHold="select count(*) nr_households from ( select distinct m.id_contact from mobcrmticker m
     		 where m.id_agent in ( select distinct agent_id from v_project_tasks $cond )
			and m.field_table='contact_households' ) as farmers";
			$result_houseHold = pg_query($conn, $sql_houseHold);
			$arr_houseHold = pg_fetch_assoc($result_houseHold);
			
			$sql_houseHold_all="select count(*) nr_households from ( select distinct m.id_contact from mobcrmticker m
     		 where m.id_agent in ( select distinct agent_id from v_project_tasks where id_company = $id_company )
			and m.field_table='contact_households' ) as farmers";
			$result_houseHold_all = pg_query($conn, $sql_houseHold_all);
			$arr_houseHold_all = pg_fetch_assoc($result_houseHold_all);
			
			// Contact Completed
			$sql_ctCompleted="select count(*) nr_contacts_completed from ( select distinct m.id_contact from mobcrmticker m
			 where public.get_contact_completed(m.id_contact)='1'
			and m.id_agent in ( select distinct agent_id from v_project_tasks $cond ) ) as farmers ";
			$result_ctCompleted = pg_query($conn, $sql_ctCompleted);
			$arr_ctCompleted = pg_fetch_assoc($result_ctCompleted);
			
			$sql_ctCompleted_all="select count(*) nr_contacts_completed from ( select distinct m.id_contact from mobcrmticker m
			 where public.get_contact_completed(m.id_contact)='1'
			and m.id_agent in ( select distinct agent_id from v_project_tasks where id_company = $id_company ) ) as farmers ";
			$result_ctCompleted_all = pg_query($conn, $sql_ctCompleted_all);
			$arr_ctCompleted_all = pg_fetch_assoc($result_ctCompleted_all);
			
			// Field Completed
			$sql_fldCompleted="select count(*) nr_fields_completed from ( select distinct m.id_contact from mobcrmticker m
			 where public.get_field_completed(m.id_plantation)='1'
			and m.id_agent in ( select distinct agent_id from v_project_tasks $cond ) ) as farmers ";
			$result_fldCompleted = pg_query($conn, $sql_fldCompleted);
			$arr_fldCompleted = pg_fetch_assoc($result_fldCompleted);
			
			$sql_fldCompleted_all="select count(*) nr_fields_completed from ( select distinct m.id_contact from mobcrmticker m
			 where public.get_field_completed(m.id_plantation)='1'
			and m.id_agent in ( select distinct agent_id from v_project_tasks where id_company = $id_company ) ) as farmers ";
			$result_fldCompleted_all = pg_query($conn, $sql_fldCompleted_all);
			$arr_fldCompleted_all = pg_fetch_assoc($result_fldCompleted_all);
			
			
			$dom = '<div class="row" style="padding-top:10px;">
				<div class="col-md-12">
					<span style="color:#aaa; font-size:11px;">NoAgents</span><br/>
					'. $arr_agent['nr_agents'] .'
				</div>
			  </div>
				
			  <div class="row">
				<div class="col-md-4">
					<span style="color:#aaa; font-size:11px;">NoFarmers</span><br/>
					'. $arr_farmer['nr_farmers'] .'
				</div>
				<div class="col-md-4">
					<span style="color:#aaa; font-size:11px;">NoPlantations</span><br/>
					'. $arr_plantation['nr_plant'] .'
				</div>
				<div class="col-md-4">
					<span style="color:#aaa; font-size:11px;">Surface</span><br/>
					'. round($arr_surface['surface_ha'],0,PHP_ROUND_HALF_UP) .' ha / '. round($arr_surface_acres['area_acres'],0,PHP_ROUND_HALF_UP) .' acres
				</div>
			  </div>
				
			  <div class="row" style="padding-bottom: 10px;">
				<div class="col-md-12">
					<span style="color:#aaa; font-size:11px;">Plantations sizes</span><br/>
				</div>
			  </div>
				
			  <div class="row" style="padding-bottom: 10px;">
				<div class="col-md-4">
					<span style="color:#aaa; font-size:11px;">NoPointsMapped</span><br/>
					'. $arr_ptMapped['nr_points'] .' / '. $arr_ptMapped_all['nr_points'] .'
				</div>
				<div class="col-md-4">
					<span style="color:#aaa; font-size:11px;">NoFieldsMapped</span><br/>
					'. $arr_fieldMapped['nr_fields'] .' / '. $arr_fieldMapped_all['nr_fields'] .'
				</div>
				<div class="col-md-4">
					<span style="color:#aaa; font-size:11px;">NoHouseholds</span><br/>
					'. $arr_houseHold['nr_households'] .' / '. $arr_houseHold_all['nr_households'] .'
				</div>
			  </div>
				
			  <div class="row" style="padding-bottom: 10px;">
				<div class="col-md-4">
					<span style="color:#aaa; font-size:11px;">NoContactDCompleted</span><br/>
					'. $arr_ctCompleted['nr_contacts_completed'] .' / '. $arr_ctCompleted_all['nr_contacts_completed'] .'
				</div>
				<div class="col-md-4">
					<span style="color:#aaa; font-size:11px;">NoFieldDCompleted</span><br/>
					'. $arr_fldCompleted['nr_fields_completed'] .' / '. $arr_fldCompleted_all['nr_fields_completed'] .'
				</div>
				<div class="col-md-4">
					<span style="color:#aaa; font-size:11px;">NoVisits</span><br/>
					
				</div>
			</div>';
		
		break;
		
		
		case "agent_visits":
		
			$agent_id = $_GET['agent_id'];
			$id_company = $_SESSION['id_company'];
			
			if($agent_id == 0) {
				$sql = "select distinct m.id_plantation, p.code_parcelle, p.code_farmer, p.name_farmer1 from mobcrmticker m, v_plantation p
				where p.gid_plantation=m.id_plantation 
				and m.id_contact in ( select id_contracting_party from contract where id_contractor=$id_company ) ";
			} else {
				$sql="select distinct m.id_plantation, p.code_parcelle, p.code_farmer, p.name_farmer1 from mobcrmticker m, v_plantation p
				where p.gid_plantation=m.id_plantation and m.id_agent = $agent_id ";
			}
			
			$result = pg_query($conn, $sql);
			
			$farmer_list = "";
			
			while($arr = pg_fetch_assoc($result)){
				$farmer_name = $arr['name_farmer1'];
				$farmer_list .= '<li><a href="javascript:farmerOnMap(\''. $arr['id_plantation'] .'\');" class="dt_collect_farmerName">'. htmlentities($farmer_name, ENT_QUOTES). '</a><div style="color:#aaa; font-size:10px; width:100%;">'. $arr['code_parcelle'] .'</div></li>';
			}
		
			$dom = $farmer_list;
			
		break;
		
		
		case "farmer_plantations":
		
			$id_plantation = $_GET['id_plantation'];
			
			$sql_pl = "SELECT 
				v_plantation.gid_plantation, 
				v_plantation.area, 
				v_plantation.year_creation, 
				v_plantation.perimeter, 
				v_plantation.variety, 
				v_plantation.code_farmer, 
				v_plantation.culture,
				v_plantation.id_culture, 
				v_plantation.gid_town, 
				v_plantation.geom_json, 
				v_plantation.coordx, 
				v_plantation.coordy,  
				v_plantation.name_manager,
				v_plantation.code_parcelle, 
				v_plantation.id_town, 
				v_plantation.name_town, 
				v_plantation.name_farmer, 
				v_plantation.id_buyer, 
				v_plantation.name_buyer, 
				v_plantation.code_buyer,
				v_plantation.cooperative_name AS name_farmergroup, 
				v_plantation.id_cooperative AS id_farmergroup, 
				v_plantation.id_farmer AS id_contact
			FROM v_plantation
			WHERE v_plantation.gid_plantation=$id_plantation";

			$result_pl = pg_query($conn, $sql_pl);
			
			$geojson_plantation = array('type' => 'FeatureCollection', 'features' => array());
			
			while($row_plantation = pg_fetch_assoc($result_pl)){
				$properties_plantation = $row_plantation;

				$feature_plantation = array(
					'type' => 'Feature',
					'geometry' => json_decode($row_plantation['geom_json']),
					'properties' => $properties_plantation
				);

				array_push($geojson_plantation['features'], $feature_plantation);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson_plantation['features']);
		
		break;
	}
	
}

echo $dom;