<?php

session_start();
error_reporting(0);

if(!isset($_SESSION['username'])){
	header("Location: ../login.php");
}


include_once("../fcts.php");
include_once("../common.php");


define('ID_USER', 'noreply@idiscover.ch');
define('ID_PASS', 'Qwerty1234');

require '../vendor/phpmailer/phpmailer/PHPMailerAutoload.php';


header("Content-type: image/png");


if (isset($_GET["elemid"])) {

    $elemid = $_GET["elemid"];
    $conn=connect();

	if(!$conn) {
		header("Location: error_db.php");
	}

    $dom='';

	switch ($elemid) {
		
		case "project_task_remove_farmer":
		
			$id_task = $_GET['id_task'];
			$id_project = $_GET['id_project'];
			$id_contact = $_GET['id_contact'];
			
			// $sql = "Delete from project_members where project_id=$id_project
			// And task_id=$id_task
			// And contact_id=$id_contact";
			
			$sql = "Delete from project_act_members where contact_id=$id_contact";	
			$result = pg_query($conn, $sql);

			if ($result) {
				
				$sql2 = "Delete from project_members where project_id=$id_project
				And task_id=$id_task And contact_id=$id_contact";	
				$result2 = pg_query($conn, $sql2);
			
				if($result2) {
					$dom=1;
				} else {
					$dom=3;
				}
				
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "project_task_add_all_farmer_in":
		
			$id_task = $_GET['id_task'];
			$agent_id = $_GET['agent_id'];
			$id_town = $_GET['id_town'];
			$id_company = $_GET['id_company'];
			$id_project = $_GET['id_project'];
			
			$sql_farmers_notIn="select * from v_plantation where gid_town=$id_town
			and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company)
			and gid_plantation not in ( select coalesce(plantation_id,0) from project_members where task_id=$id_task and agent_id=$agent_id and membertype_id=624)
			ORDER BY name_farmer";
			
			$result_farmers = pg_query($conn, $sql_farmers_notIn);
			$rows = pg_num_rows($result_farmers);
			
			$i = 0;
			while($row_farmers = pg_fetch_assoc($result_farmers)){
				
				$plantation_id = $row_farmers['gid_plantation'];
				$id_contact = $row_farmers['id_contact'];
				
				$sql = "INSERT INTO public.project_members (project_id, task_id, contact_id, membertype_id, status, cost_hour, cost_day, cost_total, agent_id, plantation_id)
				VALUES($id_project, $id_task, $id_contact, 624, 1, NULL, NULL, NULL, $agent_id, $plantation_id)";
				$result = pg_query($conn, $sql);		

				if($result) { $i = $i + 1; }
			}
			
			if ($rows == $i) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		case "project_task_remove_all_farmer_out":
		
			$id_task = $_GET['id_task'];
			$agent_id = $_GET['agent_id'];
			$id_town = $_GET['id_town'];
			$id_company = $_GET['id_company'];
			$id_project = $_GET['id_project'];
			
			$sql_farmers_in="Select * from v_plantation where gid_plantation in 
			( select plantation_id from project_members where task_id=$id_task and agent_id = $agent_id and membertype_id=624 )
			order by name_farmer asc";
			
			$result_farmers = pg_query($conn, $sql_farmers_in);
			$rows = pg_num_rows($result_farmers);
			
			$i = 0;
			while($row_farmers = pg_fetch_assoc($result_farmers)){
				
				$plantation_id = $row_farmers['gid_plantation'];
				$id_contact = $row_farmers['id_contact'];
				
				$sql = "Delete from project_act_members where contact_id=$id_contact";	
				$result = pg_query($conn, $sql);

				if ($result) {
					
					$sql2 = "Delete from project_members where project_id=$id_project
					And task_id=$id_task And contact_id=$id_contact";	
					$result2 = pg_query($conn, $sql2);
				
					if($result2) { $i = $i + 1; } 
				} 		
			}
			
			if ($rows == $i) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		case "project_task_add_farmer_in":
		
			$id_task = $_GET['id_task'];
			$agent_id = $_GET['agent_id'];
			$id_contact = $_GET['id_contact'];
			$id_project = $_GET['id_project'];
			$plantation_id = $_GET['plantation_id'];
			
			// $sql = "INSERT INTO public.project_members (project_id, task_id, contact_id, membertype_id, status, cost_hour, cost_day, cost_total, agent_id)
			// VALUES($id_project , $id_task, $id_contact, 624, 1, NULL, NULL, NULL, $agent_id)";	
			
			$sql = "INSERT INTO public.project_members (project_id, task_id, contact_id, membertype_id, status, cost_hour, cost_day, cost_total, agent_id, plantation_id)
			VALUES($id_project, $id_task, $id_contact, 624, 1, NULL, NULL, NULL, $agent_id, $plantation_id)";	
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "update_towns_coords":
		
			$id_town = $_GET['id_town'];
			$x = $_GET['x'];
			$y = $_GET['y'];

			$sql = "UPDATE public.towns
				SET x='$x', y='$y'
			WHERE id_town=$id_town";	
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "project_timeline_agents":
		
			$id_project = $_GET['id_project'];

			$sql_agent="SELECT DISTINCT agent_id, agent_name FROM v_project_tasks WHERE id_project = $id_project AND agent_id IS NOT NULL";
			$agents ='<option value="0">-- Agent List --</option>';
			
			$result = pg_query($conn, $sql_agent);

			while($row = pg_fetch_assoc($result)){
				$agents .='<option value="'. $row['agent_id'] .'">'. $row['agent_name'] .'</option>';
			}
			
			$dom='<select class="form-control" id="TM_agent_selector" onchange="showAgentTimeLine(this.value,'.$id_project.');">
				'.$agents.'
			</select>';
			
		break;
		
		
		case "show_project_plantations":
		
			$id_project = $_GET['id_project'];

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
			FROM v_project_tasks, v_plantation
			WHERE v_project_tasks.town_id = v_plantation.id_town
			AND v_plantation.geom_json IS NOT NULL
			AND v_project_tasks.id_project=$id_project";

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
		
		
		case "show_project_collection_points":
		
			$id_project = $_GET['id_project'];

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
			FROM v_project_tasks, v_plantation
			WHERE v_project_tasks.town_id = v_plantation.id_town
			AND v_plantation.coordx IS NOT NULL AND v_plantation.coordy IS NOT NULL
			AND v_project_tasks.id_project=$id_project";

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
		
		
		case "update_towns_sequence":
		
			$sequence = $_GET['sequence'];
			$id_task = $_GET['id_task'];
			
			$sql = "UPDATE public.project_task
				SET  sequence='$sequence'
			WHERE id_task=$id_task";	
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "projects_list":
		
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION["id_supchain_type"];
			$id_exporter = $_SESSION["id_exporter"];

			if(($id_exporter != "") AND ($id_exporter != 0)) {
				// $sql = "select * from project where project_mgr_company_id =$id_exporter";
				$sql = "SELECT 
				  DISTINCT v_project.project_name, 
				  v_project.id_project, 
				  v_project.project_type, 
				  v_project.project_type_name
				FROM 
				  public.v_project, 
				  public.v_project_tasks
				WHERE 
				v_project.id_project = v_project_tasks.id_project AND
					v_project.project_mgr_company_id = $id_exporter
				ORDER BY v_project.project_name ASC";
				
			} else
			if($id_supchain_type == 228){
				$sql = "SELECT 
				  DISTINCT v_project.project_name, 
				  v_project.id_project, 
				  v_project.project_type, 
				  v_project.project_type_name, 
				  v_project_tasks.task_delegated_id
				FROM 
				  public.v_project, 
				  public.v_project_tasks
				WHERE 
				  v_project.id_project = v_project_tasks.id_project
				 AND v_project.project_type=490
				 AND v_project_tasks.task_delegated_id=$id_company
				ORDER BY v_project.project_name ASC";
				
				// v_project_tasks.task_owner_id, has been removed 16/06/2020
				
			} else
			if($id_supchain_type == 331){
				$sql = "SELECT DISTINCT project_name, project_type_name, id_project 
					FROM v_project 
				WHERE project_type=490 AND cooperative_id=$id_company
				ORDER BY project_name ASC";
				
			} else {
				$sql = "SELECT DISTINCT project_name, project_type_name, id_project 
					FROM v_project 
				WHERE project_type=490 AND id_company=$id_company
				ORDER BY project_name ASC";
			}
			
			$list ='';
			$timeline ='';
			$result = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($result)){

				$list .= '<li><a href="javascript:showProjectSummary(\''. $row['id_project'] .'\',\''. $row['project_name'] .'\');" class="project_reference_nr">
					'. $row['project_name'] .' <br>
					<small style="color:#aaa; font-size:9px;">'.$row['project_type_name'].'</small>
				</a></li>';
				
				
				$timeline .= '<li><a href="javascript:showProjectTimeLine(\''. $row['id_project'] .'\');" class="project_time_reference_nr">
					'. $row['project_name'] .' <br>
					<small style="color:#aaa; font-size:9px;">'.$row['project_type_name'].'</small>
				</a></li>';
			}

			$dom = $list.'##'.$timeline;

		break;
		
		
		case "new_projects_listlie":
		
			$id_supchain_type = $_SESSION["id_supchain_type"];
			$id_company = $_SESSION['id_company'];
			$id_contact = $_SESSION["id_contact"];
			$id_country = $_SESSION["id_country"];
			$username = $_SESSION["username"];
			$user_id = $_SESSION["id_user"];
			
			$project_id_company = $_GET["project_id_company"];
			
			// Country
			if($id_supchain_type == 228){
				$sql_country = "SELECT name_country, id_country FROM country ORDER BY name_country ASC";

				$country ='<option value="">-- Select a country --</option>';
				$result_country = pg_query($conn, $sql_country);
				while($row_country = pg_fetch_assoc($result_country)){
					$country .= '<option value="'. $row_country['id_country'] .'">'. $row_country['name_country'] .'</option>';
				}
				
			} else {
				
				$country ='<option value="">-- Select a country --</option>';
				$sql_country = "select id_country, name_country, code from v_icw_contacts where id_contact = ( select id_contact from users where id_contact='$id_contact' )";
				$result_country = pg_query($conn, $sql_country);
				
				while($row_country = pg_fetch_assoc($result_country)){
					$country .='<option value="'. $row_country['id_country'] .'">'. $row_country['name_country'] .'</option>';
				}
			}
			
			// Company
			if($id_supchain_type == 228){
				$sql_company = "SELECT name_exporter, id_country, id_contact FROM v_exporters ORDER BY name_exporter ASC";

				$company ='<option value="">-- Select a company --</option>';
				$result_company = pg_query($conn, $sql_company);
				while($row_company = pg_fetch_assoc($result_company)){
					$company .= '<option value="'. $row_company['id_contact'] .'??'. $row_company['id_country'] .'">'. $row_company['name_exporter'] .'</option>';
				}
				
			} else {
				
				$sql_company = "select id_primary_company, primary_company, id_country from v_icw_contacts where id_contact = ( select id_contact from users where id_contact=$id_contact )";
				$result_company = pg_query($conn, $sql_company);
				$row_company = pg_fetch_assoc($result_company);
				
				$company ='<option value="'. $row_company['id_primary_company'] .'??'. $row_company['id_country'] .'" selected>'. $row_company['primary_company'] .'</option>';
			}
			
			

			
			// Region
			$sql_region = "SELECT DISTINCT region1 FROM v_towns_plantation where id_country=$id_country ORDER BY region1 ASC";

			$region ='<option value="">-- Select a region --</option>';
			
			$result_region = pg_query($conn, $sql_region);
			while($row_region = pg_fetch_assoc($result_region)){
				$region .= '<option value="'. $row_region['region1'] .'">'. $row_region['region1'] .'</option>';
			}
			
			// Towns
			$sql_town="SELECT id_town, name_town FROM towns ORDER BY name_town ASC";
		
			$towns ='';
			$result_town = pg_query($conn, $sql_town);
			while($row_town = pg_fetch_assoc($result_town)){
				$towns .= '<option value="'. $row_town['id_town'] .'">'. $row_town['name_town'] .'</option>';
			}
			
			// Project Type
			$sql_type="SELECT id_regvalue, cvalue FROM regvalues WHERE id_regvalue=490";
		
			$types ='<option value="">-- Select a type --</option>';
			$result_type = pg_query($conn, $sql_type);
			while($row_type = pg_fetch_assoc($result_type)){
				$types .= '<option value="'. $row_type['id_regvalue'] .'">'. $row_type['cvalue'] .'</option>';
			}
			
			// Culture
			if($id_supchain_type == 228){
				$sql_culture="SELECT id_culture, name_culture FROM culture ORDER BY name_culture DESC";
			
				$cultures ='<option value="">-- Select a culture --</option>';
				$result_culture = pg_query($conn, $sql_culture);
				while($row_culture = pg_fetch_assoc($result_culture)){
					$cultures .= '<option value="'. $row_culture['id_culture'] .'">'. $row_culture['name_culture'] .'</option>';
				}
				
			} else {
				
				$sql_culture="select * from culture where id_culture in ( select id_culture from contact_cul where id_contact = ( select get_contact_company(id_contact) from users where username like '%$username%' ) )";
				$result_culture = pg_query($conn, $sql_culture);
				
				$cultures ='<option value="">-- Select a culture --</option>';
				while($row_culture = pg_fetch_assoc($result_culture)){
					$cultures .='<option value="'. $row_culture['id_culture'] .'">'. $row_culture['name_culture'] .'</option>';
				}
			}
			
			// Status
			$sql_status="SELECT id_regvalue, cvalue FROM regvalues WHERE id_register=43";
		
			$status ='<option value="">-- Select a status --</option>';
			$result_status = pg_query($conn, $sql_status);
			while($row_status = pg_fetch_assoc($result_status)){
				$status .= '<option value="'. $row_status['id_regvalue'] .'">'. $row_status['cvalue'] .'</option>';
			}
			
			// Cooperative
			$sql_coop = "SELECT con.id_contract,
				con.id_contractor,
				con.id_contracting_party,
				con.id_contract_type,
				getregvalue(con.id_contract_type) AS contract_type,
				con.contract_date,
				con.start_date,
				get_contact_name(con.id_contractor) AS contractor,
				get_contact_name(con.id_contracting_party) AS contracting_party
			   FROM contract con
			where id_contract_type=163 and id_contractor=$id_company";
			$result_coop = pg_query($conn, $sql_coop);
			
			$coops ='<option value="">-- Select a cooperative --</option>';
			while($row_coops = pg_fetch_assoc($result_coop)){
				$coops .= '<option value="'. $row_coops['id_contracting_party'] .'">'. $row_coops['contracting_party'] .'</option>';
			}
			
			// HQ
			$sql_hq = "select id_contact, name from contact where id_contact IN (select id_primary_company from contact where id_contact=$project_id_company)";
			$result_hq = pg_query($conn, $sql_hq);
			
			$hq ='<option value="">-- Select a Project Owner HQ --</option>';
			while($row_hq = pg_fetch_assoc($result_hq)){
				$hq .= '<option value="'. $row_hq['id_contact'] .'">'. $row_hq['name'] .'</option>';
			}
			
			// Manager ID
			// if($id_company == 15012) {
				// $sql_mgr = "select * from users where id_primary_company=$id_company";
			// } else {
				// $sql_mgr = "select id_contact, name from contact where id_primary_company= 645 and id_type=9";
			// }
			
			$sql_mgr = "select * from   
				(  
				select * from contact where id_contact in ( 
				select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where 
				id_contact=(select id_contact from users where id_user=$user_id ) ) )  AND id_primary_company=$id_company AND id_type = '9' 
				UNION 
				select * from contact where id_primary_company in ( 
				select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where 
				id_contact=(select id_contact from users where id_user=$user_id ) ) ) 
				and id_contact in ( select id_contact from users )  AND id_primary_company=$id_company AND id_type = '9' 
				union 
				select * from contact where id_contact in ( 
				select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where 
				id_contact=(select id_contact from users where id_user=$user_id ) ) )  AND id_primary_company=$id_company AND id_type = '9' 
				union 
				select * from contact where id_primary_company in ( 
				select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where 
				id_contact=(select id_contact from users where id_user=$user_id ) ) ) 
				and id_contact in ( select id_contact from users )  AND id_type = '9' 
				union 
				select * from contact where id_primary_company in ( select id_primary_company from contact where 
				id_contact=(select id_contact from users where id_user=$user_id ) )  AND id_primary_company=$id_company AND id_type = '9' 
				union 
				select * from contact where id_primary_company in ( select id_link from contact_links where 
				id_contact in ( select id_primary_company from contact where 
				id_contact=(select id_contact from users where id_user=$user_id ) ))  AND id_primary_company=$id_company AND id_type = '9' 
				union 
				select * from contact where id_primary_company in ( select id_contact from contact_links where 
				id_link in ( select id_primary_company from contact where 
				id_contact=(select id_contact from users where id_user=$user_id ) ))  AND id_primary_company=$id_company AND id_type = '9'  
				union 
				select * from contact where id_contact in ( select id_primary_company from contact where 
				id_contact=(select id_contact from users where id_user=$user_id ) )  AND id_primary_company=$id_company AND id_type = '9'  
			ORDER BY name ASC )  c";
			
			$result_mgr = pg_query($conn, $sql_mgr);
			
			$mgr ='<option value="">-- Select a Manager --</option>';
			while($row_mgr = pg_fetch_assoc($result_mgr)){
				$mgr .= '<option value="'. $row_mgr['id_contact'] .'">'. $row_mgr['name'] .'</option>';
			}
			
			$dom=$country.'##'.$company.'##'.$region.'##'.$towns.'##'.$types.'##'.$cultures.'##'.$status.'##'.$coops.'##'.$hq.'##'.$mgr;
		
		break;
		
		
		case "countries_of_selected_company":
		
			$id_country = $_GET["id_country"];
			
			// Country
			$sql_country = "SELECT name_country, id_country FROM country WHERE id_country = $id_country ORDER BY name_country ASC";

			$country ='<option value="">-- Select a country --</option>';
			$result_country = pg_query($conn, $sql_country);
			while($row_country = pg_fetch_assoc($result_country)){
				$country .= '<option value="'. $row_country['id_country'] .'">'. $row_country['name_country'] .'</option>';
			}
			
			$dom=$country;
		
		break;
		
		
		case "regions_and_towns_of_selected_country":
		
			$id_country = $_GET["id_country"];
			
			// Region
			$sql_region = "SELECT DISTINCT region1 FROM towns WHERE region1 IS NOT NULL AND id_country=$id_country ORDER BY region1 ASC";

			$region ='<option value="">-- Select a region --</option>';
			
			$result_region = pg_query($conn, $sql_region);
			while($row_region = pg_fetch_assoc($result_region)){
				$region .= '<option value="'. $row_region['region1'] .'">'. $row_region['region1'] .'</option>';
			}
			
			// Towns
			$sql_town="SELECT id_town, name_town FROM towns WHERE id_country=$id_country ORDER BY name_town ASC";
		
			$towns ='';
			$result_town = pg_query($conn, $sql_town);
			while($row_town = pg_fetch_assoc($result_town)){
				$towns .= '<option value="'. $row_town['id_town'] .'">'. $row_town['name_town'] .'</option>';
			}
			
			$dom=$region.'##'.$towns;
		
		break;
		
		
		case "towns_of_selected_region_id":
			
			$cond="";
			$region1 = $_GET["region1"];
			$id_country = $_GET["id_country"];
			if($id_country!=""){
				$cond=" AND id_country=$id_country";
			}
			
			// Region
			$sql_region = "SELECT DISTINCT region2 FROM towns WHERE region1='$region1' $cond ORDER BY region2 ASC";

			$region ='<option value="">-- Select a region --</option>';
			
			$result_region = pg_query($conn, $sql_region);
			while($row_region = pg_fetch_assoc($result_region)){
				$region .= '<option value="'. $row_region['region2'] .'">'. $row_region['region2'] .'</option>';
			}
			
			// Towns
			$sql_town="SELECT id_town, name_town FROM towns WHERE region1='$region1' $cond ORDER BY name_town ASC";
		
			$towns ='';
			$result_town = pg_query($conn, $sql_town);
			while($row_town = pg_fetch_assoc($result_town)){
				$towns .= '<option value="'. $row_town['id_town'] .'">'. $row_town['name_town'] .'</option>';
			}
			
			$dom=$region.'##'.$towns;
		
		break;
		
		
		case "towns_of_selected_region_name":
		
			$cond="";
			$cond2="";
			$cond3="";
			$cond4="";
			$region="";
			
			$region1 = $_GET["region1"];
			$id_country = $_GET["id_country"];
			if($id_country!=""){
				$cond=" AND id_country=$id_country";
			}
			
			$region2 = $_GET["region2"]; 
			if($region2!=""){
				$cond2=" AND region2='$region2'"; $region="region3";
			}
			
			$region3 = $_GET["region3"]; 
			if($region3!=""){
				$cond3=" AND region3='$region3'"; $region="region4";
			}
			
			$region4 = $_GET["region4"]; 
			if($region4!=""){
				$cond4=" AND region4='$region4'"; 
			}
		
			// Region
			if($region4==""){
				$sql_region = "SELECT DISTINCT $region FROM towns WHERE region1='$region1' $cond $cond2 $cond3 ORDER BY $region ASC";

				$regionName ='<option value="">-- Select a region --</option>';
				
				$result_region = pg_query($conn, $sql_region);
				while($row_region = pg_fetch_assoc($result_region)){
					$regionName .= '<option value="'. $row_region[$region] .'">'. $row_region[$region] .'</option>';
				}
				
			} else {
				$regionName="";
			}
			
			// Towns
			$sql_town="SELECT id_town, name_town FROM towns WHERE region1='$region1' $cond $cond2 $cond3 $cond4 ORDER BY name_town ASC";
		
			$towns ='';
			$result_town = pg_query($conn, $sql_town);
			while($row_town = pg_fetch_assoc($result_town)){
				$towns .= '<option value="'. $row_town['id_town'] .'">'. $row_town['name_town'] .'</option>';
			}
			
			$dom=$regionName.'##'.$towns;
			
		break;
		
		
		case "project_management":
		
			$id_user = $_SESSION['id_user'];
			$username = $_SESSION['username'];
		
			if(isset($_GET["project_name"])){
				$project_name = $_GET["project_name"];
				$project_name_field = " project_name,";
				$project_name_val = " '$project_name',";
				$project_name_edit = "project_name='$project_name',";
			} else { $project_name_field = ""; $project_name_val = ""; $project_name_edit = ""; }
			
			if(isset($_GET["project_type"])){
				$project_type = $_GET["project_type"];
				$project_type_field = " project_type,";
				$project_type_val = " '$project_type',";
				$project_type_edit = " project_type='$project_type',";
			} else { $project_type_field = ""; $project_type_val = ""; $project_type_edit = ""; }
			
			if(isset($_GET["start_date"])){
				$start_date = $_GET["start_date"];
				$start_date_field = " start_date,";
				$start_date_val = " '$start_date',";
				$start_date_edit = " start_date='$start_date',";
			} else { $start_date_field = ""; $start_date_val = ""; $start_date_edit = ""; }
	
			if(isset($_GET["due_date"])){
				$due_date = $_GET["due_date"];
				$due_date_field = " due_date,";
				$due_date_val = " '$due_date',";
				$due_date_edit = " due_date='$due_date',";
			} else { $due_date_field = ""; $due_date_val = ""; $due_date_edit = ""; }
			
			if(isset($_GET["project_status"])){
				$project_status = $_GET["project_status"];
				$project_status_field = " project_status,";
				$project_status_val = " '$project_status',";
				$project_status_edit = " project_status='$project_status',";
			} else { $project_status_field = ""; $project_status_val = ""; $project_status_edit = ""; }
			
			if(isset($_GET["id_company"])){
				$id_company = $_GET["id_company"];
				$id_company_field = " id_company,";
				$id_company_val = " '$id_company',";
				$id_company_edit = " id_company='$id_company',";
			} else { $id_company_field = ""; $id_company_val = ""; $id_company_edit=""; }
			
			if(isset($_GET["id_culture"])){
				$id_culture = $_GET["id_culture"];
				$id_culture_field = " id_culture,";
				$id_culture_val = " '$id_culture',";
				$id_culture_edit = " id_culture='$id_culture',";
			} else { $id_culture_field = ""; $id_culture_val = ""; $id_culture_edit =""; }
			
			if(isset($_GET["country_id"])){
				$country_id = $_GET["country_id"];
				$country_id_field = " country_id,";
				$country_id_val = " '$country_id',";
				$country_id_edit = " country_id='$country_id',";
			} else { $country_id_field = ""; $country_id_val = ""; $country_id_edit = ""; }
			
			if(isset($_GET["region_name"])){
				$region_name = $_GET["region_name"];
				$region_name_field = " region_name,";
				$region_name_val = " '$region_name',";
				$region_name_edit = " region_name='$region_name',";
			} else { $region_name_field = ""; $region_name_val = ""; $region_name_edit = ""; }
			
			if(isset($_GET["cooperative_id"])){
				$cooperative_id = $_GET["cooperative_id"];
				$cooperative_id_field = " cooperative_id,";
				$cooperative_id_val = " '$cooperative_id',";
				$cooperative_id_edit = " cooperative_id='$cooperative_id',";
			} else { $cooperative_id_field = ""; $cooperative_id_val = ""; $cooperative_id_edit = ""; }
			
			if(isset($_GET["project_mgr_company_id"])){
				$project_mgr_company_id = $_GET["project_mgr_company_id"];
				$project_mgr_company_id_field = " project_mgr_company_id,";
				$project_mgr_company_id_val = " '$project_mgr_company_id',";
				$project_mgr_company_id_edit = " project_mgr_company_id='$project_mgr_company_id',";
			} else { $project_mgr_company_id_field = ""; $project_mgr_company_id_val = ""; $project_mgr_company_id_edit = ""; }
			
			if(isset($_GET["project_manager_id"])){
				$project_manager_id = $_GET["project_manager_id"];
				$project_manager_id_field = " project_mgr_id,";
				$project_manager_id_val = " '$project_manager_id',";
				$project_manager_id_edit = " project_mgr_id='$project_manager_id',";
			} else { $project_manager_id_field = ""; $project_manager_id_val = ""; $project_manager_id_edit = ""; }
			
			if(isset($_GET["id_project"])){
				$id_project = $_GET["id_project"];
			} else { $id_project = ""; }
			
			$creation_date = gmdate("Y/m/d H:i");

			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.project(
					id_user, username, project_private, $project_name_field $project_type_field
					$start_date_field $due_date_field $project_status_field
					$id_company_field $id_culture_field $country_id_field  
					$region_name_field $cooperative_id_field $project_mgr_company_id_field $project_manager_id_field creation_date)
				VALUES ($id_user, '$username', '0', $project_name_val $project_type_val
					$start_date_val $due_date_val $project_status_val
					$id_company_val $id_culture_val $country_id_val 
					$region_name_val $cooperative_id_val $project_mgr_company_id_val $project_manager_id_val '$creation_date');
				";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.project
				   SET $project_name_edit $project_type_edit 
					   $start_date_edit $due_date_edit $project_status_edit
					   $id_company_edit $id_culture_edit $country_id_edit
					   $region_name_edit $cooperative_id_edit $project_mgr_company_id_edit $project_manager_id_edit 
					   modify_by=$id_user, modified_date='$creation_date'
				WHERE id_project=$id_project";
				
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "show_project_summary":
		
			$id_project = $_GET["id_project"];
			$project_update = $_GET["project_update"];
			if($project_update == 1) { $edit = ""; } else { $edit = "hide"; }
			
			$project ='';
			$sql="SELECT * FROM public.v_project WHERE id_project=$id_project";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);
			$country_id = $row['country_id'];
			$region_quadrant = $row['region_quadrant'];
			$country_id = $row['country_id'];
			$id_company = $row['id_company'];
			
			if($row['project_private'] == 1){
				$project_private = $lang['PROJECT_MODAL_STATE_PRIVATE'];
			} else {
				$project_private = $lang['PROJECT_MODAL_STATE_PUBLIC'];
			}
			

			if($arr['creation_date']!=""){
				$project_creation = '<div class="form-group">
					<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label> '. $arr['creation_date'] .' <br/>
					<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].': </label> 
				</div>';
			} else {
				$project_creation = "";
			}
			
			$project = '<div class="col-md-12">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'. $lang['PROJECT_MODAL_NAME'] .'</label><br/>
							'. $row['project_name'] .'
						</div>
					</div>
				
					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'. $lang['PROJECT_MODAL_TYPE'] .'</label><br/>
							'. $row['project_type_name'] .' 
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'. $lang['PROJECT_MODAL_STATE'] .'</label><br/>
							'. $project_private .'
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'. $lang['PROJECT_MODAL_COUNTRY'] .'</label><br/>    
							'. $row['name_country'] .'  
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'. $lang['PROJECT_MODAL_COMPANY'] .'</label><br/>
							'. $row['company_name'] .' 
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'. $lang['PROJECT_MODAL_COOPERATIVE'] .'</label><br/>
							'. $row['cooperative_name'] .'
						</div>
					</div>
				</div>
				
				<div class="row">				
					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'. $lang['PROJECT_MODAL_REGION'] .'</label><br/>
							'. $row['region_name'] .' 
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'. $lang['PROJECT_MODAL_CULTURE'] .'</label><br/>
							'. $row['name_culture'] .' 
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'. $lang['PROJECT_MODAL_OWNER_HQ'] .'</label><br/>
							'. $row['project_mgr_name'] .' 
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8" style="background:#e4e4e4;padding-top:5px;margin-bottom:5px;border-radius:5px;">
						<div class="col-md-6">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_MODAL_START_DATE'] .'</label>
								<div class="input-group date">
									<i class="fa fa-calendar"></i> '. $row['start_date'] .' 								
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_MODAL_DUE_DATE'] .'</label>
								<div class="input-group date">
									<i class="fa fa-calendar"></i> '. $row['due_date'] .' 
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row" style="margin-top:15px;">
					<div class="pull-left">
						'.$project_creation.'
					</div>
					
					<input type="hidden" id="projectID_company" value="'.$id_company.'" />
					<button class="btn btn-success '.$edit.' pull-right" data-toggle="modal" onclick="showProjectDetails(\''. $id_project .'\');" data-target="#projectModal">
						<i class="fa fa-pencil-alt" aria-hidden="true"></i>
					</button>
				</div>
			</div>';
	
			$dom=$project.'##'.$country_id.'##'.$region_quadrant.'##'.$country_id;
		
		break;
		
		
		case "show_project_details":
		
			$id_project = $_GET["id_project"];
			
			$sql="SELECT * FROM public.project WHERE id_project=$id_project";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);
			
			$dom=$row['project_name'].'##'.$row['project_type'].'##'.$row['project_status'].'##'.$row['start_date'].'##'.$row['end_date'].'##'.$row['country_id'].'##'.$row['id_company'].'??'.$row['country_id'].'##'.$row['id_culture'].'##'.$row['region_name'].'##'.$row['due_date'].'##'.$row['cooperative_id'].'##'.$row['project_mgr_company_id'].'##'.$row['project_mgr_id'];
		
		break;
		
		
		case "new_task_listlie":
		
			$country_id = $_GET["country_id"];
			$id_project = $_GET["id_project"];

			// Region
			$sql_region = "SELECT DISTINCT region1 FROM towns WHERE region1 IS NOT NULL AND id_country = $country_id ORDER BY region1 ASC";

			$region ='<option value="">-- Select a region --</option>';
			
			$result_region = pg_query($conn, $sql_region);
			while($row_region = pg_fetch_assoc($result_region)){
				$region .= '<option value="'. $row_region['region1'] .'">'. $row_region['region1'] .'</option>';
			}
			
			// Towns
			$sql_town="SELECT gid_town, name_town, zone FROM towns WHERE gid_town NOT IN
			(SELECT town_id FROM project_task WHERE id_project = $id_project)
			AND id_country = $country_id ORDER BY name_town ASC";
			
			// $sql_town="SELECT id_town, name_town, zone FROM towns WHERE id_town NOT IN
				// (SELECT town_id FROM project_task WHERE id_project = $id_project)
			// AND id_country = $country_id ORDER BY name_town ASC";
		
			$towns ='';
			$result_town = pg_query($conn, $sql_town);
			while($row_town = pg_fetch_assoc($result_town)){
				if(!empty($row_town['zone'])){ $zone=" (".$row_town['zone'].")"; } else { $zone=""; }
				$towns .= '<option value="'. $row_town['gid_town'] .'">'. $row_town['name_town'] . $zone . '</option>';
			}
			
			$dom=$region.'##'.$towns;
		
		break;
		
		
		case "projectTask_management":
		
			$id_contact = $_SESSION['id_contact'];
			$id_project = $_GET['id_project'];
			
			if(isset($_GET["task_titleshort"])){
				$task_titleshort = pg_escape_string($_GET["task_titleshort"]);
				$task_titleshort_field = " task_titleshort,";
				$task_titleshort_val = " '{$task_titleshort}',";
				$task_titleshort_edit = " task_titleshort='{$task_titleshort}',";
			} else { $task_titleshort_field = ""; $task_titleshort_val = ""; $task_titleshort_edit = ""; }
			
			if(isset($_GET["task_description"])){
				$task_description = $_GET["task_description"];
				$task_description_field = " task_description,";
				$task_description_val = " '$task_description',";
				$task_description_edit = " task_description='$task_description',";
			} else { $task_description_field = ""; $task_description_val = ""; $task_description_edit = ""; }
			
			if(isset($_GET["start_date"])){
				$start_date = $_GET["start_date"];
				$start_date_field = " start_date, planned_start_date,";
				$start_date_val = " '$start_date', '$start_date',";
				$start_date_edit = " start_date='$start_date', planned_start_date='$start_date',";
			} else { $start_date_field = ""; $start_date_val = ""; $start_date_edit = ""; }
			
			if(isset($_GET["end_date"])){
				$end_date = $_GET["end_date"];
				$end_date_field = " end_date, ";
				$end_date_val = " '$end_date', ";
				$end_date_edit = " end_date='$end_date',";
			} else { $end_date_field = ""; $end_date_val = ""; $end_date_edit = ""; }
			
			if(isset($_GET["due_date"])){
				$due_date = $_GET["due_date"];
				$due_date_field = " due_date, planned_end_date,";
				$due_date_val = " '$due_date', '$due_date',";
				$due_date_edit = " due_date='$due_date', planned_end_date='$due_date',";
			} else { $due_date_field = ""; $due_date_val = ""; $due_date_edit = ""; }
			
			if(isset($_GET["task_status"])){
				$task_status = $_GET["task_status"];
				$task_status_field = " task_status,";
				$task_status_val = " '$task_status',";
				$task_status_edit = " task_status='$task_status',";
			} else { $task_status_field = ""; $task_status_val = ""; $task_status_edit = ""; }
			
			$newAgent = 0;
			if(isset($_GET["task_delegated_id"])){
				$task_delegated_id = $_GET["task_delegated_id"];
				$task_delegated_id_field = " task_delegated_id, agent_id,";
				$task_delegated_id_val = " '$task_delegated_id', '$task_delegated_id',";
				$task_delegated_id_edit = " task_delegated_id='$task_delegated_id',";
				$newAgent = 1;
			} else { $task_delegated_id_field = ""; $task_delegated_id_val = ""; $task_delegated_id_edit ="task_delegated_id=null,"; $newAgent = 0; }
			
			// if(isset($_GET["towns"])){
				// $towns = $_GET["towns"];
				// $towns_field = " towns,";
				// $towns_val = " '$towns',";
				// $towns_edit = " towns='$towns',";
			// } else { $towns_field = ""; $towns_val = ""; $towns_edit = ""; }
			
			if(isset($_GET["notification_status"])){
				$notification_status = $_GET["notification_status"];
				$notification_status_edit = " notification_status='$notification_status',";
			} else { $notification_status_edit = ""; }
			
			if(isset($_GET["task_status"])){
				$task_status = $_GET["task_status"];
				$task_status_edit = " task_status='$task_status',";
			} else { $task_status_edit = ""; }
			
			if(isset($_GET["agent_id"])){
				$agent_id = $_GET["agent_id"];
				$agent_id_edit = " agent_id='$agent_id',";
				$newAgent = 1;
			} else { $agent_id_edit = " agent_id=null,"; $newAgent = 0; }
			
			if(isset($_GET["agent_assist_id"])){
				$agent_assist_id = $_GET["agent_assist_id"];
				if($agent_assist_id == 'null') {
					$agent_assist_id_edit = " agent_assist_id=null,";
				} else {
					$agent_assist_id_edit = " agent_assist_id='$agent_assist_id',";
				}
			} else { $agent_assist_id_edit = " agent_assist_id=null,"; }
			
			
			if(isset($_GET["id_task"])){
				$id_task = $_GET["id_task"];
			} else { $id_task = ""; }
			
			$creation_date = gmdate("Y/m/d H:i");

			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				if(isset($_GET["towns"])){
					
					$towns = explode(',',$_GET["towns"]);
					$len = count($towns);
					
					for ( $i = 0; $i < $len; $i++ ) {
						$id_town = $towns[$i];
						$sequence = $i + 1;
						
						$sql_t = "SELECT name_town FROM towns WHERE id_town=$id_town ";  
						$result_t = pg_query($conn, $sql_t);
						$row_t = pg_fetch_assoc($result_t);
						$name_town = pg_escape_string($row_t['name_town']);
						
						$sql = "INSERT INTO public.project_task(id_project, task_type, task_titleshort,
							$task_description_field $start_date_field $end_date_field $due_date_field task_status,
							task_public, notification_status, $task_delegated_id_field town_id,
							task_owner_id, creation_date, sequence)
						VALUES ($id_project, 491, '{$name_town}',
							$task_description_val $start_date_val $end_date_val $due_date_val 229,
							0, 1, $task_delegated_id_val $id_town,
							$id_contact, '$creation_date', $sequence);
						";
						
						$result = pg_query($conn, $sql);
						
						
						$sqlUser = "UPDATE public.users SET agent_type=1 WHERE id_contact=$id_contact";
						pg_query($conn, $sqlUser);
					}

				} else { 
					$dom=0;
				}
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.project_task
				   SET  $task_titleshort_edit
					   $task_description_edit $start_date_edit $end_date_edit $due_date_edit
					    $task_delegated_id_edit $towns_edit
						$notification_status_edit $task_status_edit $agent_id_edit $agent_assist_id_edit 
					   modify_by=$id_contact, modified_date='$creation_date'
				WHERE id_task=$id_task";
				
				$result = pg_query($conn, $sql);

				if($newAgent == 1) {
					$sql_agentCheck = "SELECT * FROM public.project_members WHERE membertype_id=621 AND contact_id=$agent_id AND task_id=$id_task";
					$result_agentCheck = pg_query($conn, $sql_agentCheck);
					$count = pg_num_rows($result_agentCheck);
					
					if($count == 0){
						$sql_agent = "INSERT INTO public.project_members 
						(project_id, task_id, contact_id, membertype_id, status, cost_hour, cost_day, cost_total, agent_id)
						VALUES($id_project, $id_task, $agent_id, 621, 1, NULL, NULL, NULL, $agent_id)";
						pg_query($conn, $sql_agent);
					}
					
				} else {
					$sql_delActmb = "delete from project_act_members where proj_member_id in ( select id_projmember from project_members where task_id= $id_task )";
					$sql_pjAct = "delete from project_activity where task_id=$id_task ";
					
					pg_query($conn, $sql_delActmb);
					pg_query($conn, $sql_pjAct);
				}
				
			} else {
				$dom=0;
			}

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "tasks_list":
		
			$id_project = $_GET['id_project'];
			$project_update = $_GET['project_update'];

			$sql = "SELECT task_titleshort, id_task, task_done, project_name, due_date, town_id, sequence, x, y, id_company, agent_id, id_project
				FROM v_project_tasks 
			  WHERE id_project=$id_project 
			ORDER BY sequence ASC";

			$list1 ='';
			$list2 ='';
			$list3 ='';
			
			$result = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($result)){
				
				if($row['task_done']==1){
					$lbl='default';
					$done="Done";
				} else
				if(($row['due_date']>gmdate())&&($row['task_done']==0)){ 
					$lbl='info';
					$done="Not done";
				} else
				if(($row['due_date']==gmdate())&&($row['task_done']==0)){
					$lbl='warning';
					$done="Not done";
				} else
				if(($row['due_date']<gmdate())&&($row['task_done']==0)){
					$lbl='danger';
					$done="Not done";
				} else {
					$lbl='default';
					$done="Not done";
				}

				if($row['sequence']!=""){
					$sequence = $row['sequence'];
				} else { $sequence = 0; }
				
				$pin = "";
				if($project_update==1){
					if((empty($row['x']))AND(empty($row['y']))){
						$pin = '<div class="pull-left" style="padding-top:8px;"><a href="#" onclick="editTownCoordsModal(\''. $row['town_id'] .'\',\''. $row['task_titleshort'] .'\',\''. $row['x'] .'\',\''. $row['y'] .'\');"><i class="fa fa-map-pin"></i></a></div>';
					}
				}
				
				$list1 .= '<li class="project_task_reference_nr">'. $pin .'
				<div><a href="javascript:showProjectTaskSummary(\''. $row['id_task'] .'\', \''. $id_project .'\');">
					'. $row['task_titleshort'] .'
					<span class="label label-'.$lbl.' pull-right" style="font-weight:normal">'. $done .'</span><br/>
					<span style="color:#aaa; font-size:9px;" class="pull-right">'. $row['due_date'] .'</span>
					<small style="color:#aaa; font-size:9px;">'.$row['project_name'].'</small><br/>
				</a></div></li>';
				
				$list2 .= '<li><a href="javascript:projectTaskAgentList(\''. $row['id_task'] .'\',\''. $row['agent_id'] .'\',\''. $row['town_id'] .'\',\''. $row['id_company'] .'\',\''. $row['id_project'] .'\');" class="project_task_reference_nr2">
					'. $row['task_titleshort'] .'
					<span class="label label-'.$lbl.' pull-right" style="font-weight:normal">'. $done .'</span><br/>
					<span style="color:#aaa; font-size:9px;" class="pull-right">'. $row['due_date'] .'</span>
					<small style="color:#aaa; font-size:9px;">'.$row['project_name'].'</small>
					</a>
				</li>';
				
				$list3 .= '<li class="'.$lbl.'-element" id="'. $row['id_task'] .'">  
                    '. $sequence .' - '. $row['task_titleshort'] .'
                </li>';
			}

			$dom = $list1.'##'.$list2.'##'.$list3;

		break;
		
		
		case "delegated_agents":
		
			$agent_id = $_GET['agent_id'];
			$id_primary_company = $_GET['id_primary_company'];
			
			$sql_agent="SELECT id_contact, name FROM contact WHERE id_primary_company = $id_primary_company AND id_type=9 ORDER BY name ASC ";
			$agents ='<option value="">-- Select an agent --</option>';
			
			$result_agent = pg_query($conn, $sql_agent);
			while($row_agent = pg_fetch_assoc($result_agent)){
				if($agent_id == $row_agent['id_contact']){
					$agents .= '<option value="'. $row_agent['id_contact'] .'" selected>'. $row_agent['name'] .'</option>';
				} else {
					$agents .= '<option value="'. $row_agent['id_contact'] .'">'. $row_agent['name'] .'</option>';
				}
			}
			
			$dom=$agents;
			
		break;
		
		
		case "task_details":
		
			$id_task = $_GET['id_task'];
			$update_right = $_GET['update_right'];
			$id_contact = $_SESSION["id_contact"];
			$id_supchain_type = $_SESSION["id_supchain_type"];
			
			$task="";
			$sql = "SELECT * FROM v_project_tasks WHERE id_task=$id_task";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result); 
			$id_company = $row['id_company'];
			$id_project = $row['id_project'];
			$town_id = $row['town_id'];
			$selProject = $row['project_name'];
			
			// Task Type
			$sql_type="SELECT id_regvalue, cvalue FROM regvalues WHERE id_register=44";
		
			$types ='<option value="">-- Select a type --</option>';
			$result_type = pg_query($conn, $sql_type);
			while($row_type = pg_fetch_assoc($result_type)){
				if($row['task_type'] == $row_type['id_regvalue']){
					$types .= '<option value="'. $row_type['id_regvalue'] .'" selected>'. $row_type['cvalue'] .'</option>';
				} else {
					$types .= '<option value="'. $row_type['id_regvalue'] .'">'. $row_type['cvalue'] .'</option>';
				}
			}
			
			// Task Status
			$sql_status="SELECT id_regvalue, cvalue FROM regvalues WHERE id_register=43";
		
			$status ='<option value="">-- Select a status --</option>';
			$result_status = pg_query($conn, $sql_status);
			while($row_status = pg_fetch_assoc($result_status)){
				if($row['task_status'] == $row_status['id_regvalue']){
					$status .= '<option value="'. $row_status['id_regvalue'] .'" selected>'. $row_status['cvalue'] .'</option>';
				} else {
					$status .= '<option value="'. $row_status['id_regvalue'] .'">'. $row_status['cvalue'] .'</option>';
				}
			}
			
			// Delegated contact
			if($id_supchain_type == 113){
				$sql_contact="select id_contracting_party, contracting_party from v_icw_contracts where id_contractor=$id_company and  ( 
					id_contract_type=128 or id_contract_type=292 or id_contract_type=494 
				) union
				select distinct id_contractor, contractor from v_icw_contracts where id_contractor=$id_company";

			} else 
			if($id_supchain_type == 114){
				$sql_contact="select id_contracting_party, contracting_party from v_icw_contracts 
				where id_contractor=$id_company and ( id_contract_type=163 or id_contract_type=492 )
				union
				select distinct id_contractor, contractor from v_icw_contracts 
				where id_contractor=$id_company and ( id_contract_type=163 or id_contract_type=492 )";

			} else 
			if($id_supchain_type == 331){
				$sql_contact="select id_contracting_party, contracting_party from v_icw_contracts 
				where id_contractor=$id_company and ( id_contract_type=163 or id_contract_type=493 )
				union
				select distinct id_contractor, contractor from v_icw_contracts 
				where id_contractor=$id_company and ( id_contract_type=163 or id_contract_type=493 )";
				
			} else 
			if($id_supchain_type == 228){
				$sql_contact="select id_contractor as id_contracting_party, contractor as contracting_party
				from v_icw_contracts 
				where id_contracting_party=$id_contact";
				
			} else {
				$sql_contact="SELECT id_contact, name FROM contact WHERE id_primary_company = $id_company ORDER BY name ASC";
			}
			$task_delegated ='<option value="">-- Select a contact --</option>';
			
			$result_contact = pg_query($conn, $sql_contact);
			while($row_contact = pg_fetch_assoc($result_contact)){
				
				if($row['task_delegated_id'] == $row_contact['id_contracting_party']){
					$task_delegated .= '<option value="'. $row_contact['id_contracting_party'] .'" selected>'. $row_contact['contracting_party'] .'</option>';
				} else {
					$task_delegated .= '<option value="'. $row_contact['id_contracting_party'] .'">'. $row_contact['contracting_party'] .'</option>';
				}
			}
			
			$agents ='';
			$agents_disabled ='disabled';
			if(!empty($row['task_delegated_id'])){
				$id_primary_company = $row['task_delegated_id'];
				$agents_disabled='';
				
				// $sql_agent="SELECT id_contact, name FROM contact WHERE id_primary_company = $id_primary_company AND id_type=9 ORDER BY name ASC ";
				// $sql_agent="select c.name, c.id_contact, u.id_user from contact c, users u where u.agent_type=7 and u.id_contact=c.id_contact and c.id_primary_company=$id_company"; 4/12/2020
				
				$sql_agent="select c.name, c.id_contact, u.id_user from contact c, users u where u.agent_type=1 and u.id_contact=c.id_contact and c.id_primary_company=$id_company";
				$agents ='<option value="">-- Select an agent --</option>';
				
				$result_agent = pg_query($conn, $sql_agent);
				while($row_agent = pg_fetch_assoc($result_agent)){
					if($row['agent_id'] == $row_agent['id_contact']){
						$agents .= '<option value="'. $row_agent['id_contact'] .'" selected>'. $row_agent['name'] .'</option>';
					} else {
						$agents .= '<option value="'. $row_agent['id_contact'] .'">'. $row_agent['name'] .'</option>';
					}
				}
				
				$sql_agent2="select c.name, c.id_contact, u.id_user from contact c, users u where u.agent_type=7 and u.id_contact=c.id_contact and c.id_primary_company=$id_company";
				$agent_assist ='<option value="null">-- Select an agent --</option>';
				
				$result_agent2 = pg_query($conn, $sql_agent2);
				while($row_agent2 = pg_fetch_assoc($result_agent2)){
					if($row['agent_assist_id'] == $row_agent2['id_contact']){
						$agent_assist .= '<option value="'. $row_agent2['id_contact'] .'" selected>'. $row_agent2['name'] .'</option>';
					} else {
						$agent_assist .= '<option value="'. $row_agent2['id_contact'] .'">'. $row_agent2['name'] .'</option>';
					}
				}
			}

			if($row['task_done'] == 1){
				$done='checked';
			} else { $done=''; }
			
			
			if($row['task_status'] == 1){
				$status_bg_color ='bg-success';
			} else {
				$status_bg_color ='bg-danger';
			}  
			
			$sql_nbFarmers = "select count(*) AS tt_farmers from v_mob_town_contacts where id_town=( select town_id from project_task where id_task=$id_task ) and id_contractor=$id_company";
			$res_nbFarmers = pg_query($conn, $sql_nbFarmers);
			$row_nbFarmers = pg_fetch_assoc($res_nbFarmers); 
			$tt_farmers = $row_nbFarmers['tt_farmers'];
			
			
			$sql_nbFields = "select count(*) AS tt_fields from v_mob_town_plantation where id_town=( select town_id from project_task where id_task=$id_task ) and id_contractor=$id_company";
			$res_nbFields = pg_query($conn, $sql_nbFields);
			$row_nbFields = pg_fetch_assoc($res_nbFields); 
			$tt_fields = $row_nbFields['tt_fields'];
			
			$user_id_company = $_SESSION['id_company'];

			if($id_supchain_type == 228){
				$sqlProjects = "SELECT 
				  DISTINCT v_project.project_name, 
				  v_project.id_project, 
				  v_project.project_type, 
				  v_project.project_type_name, 
				  v_project_tasks.task_delegated_id
				FROM 
				  public.v_project, 
				  public.v_project_tasks
				WHERE 
				  v_project.id_project = v_project_tasks.id_project
				 AND v_project.project_type=490
				 AND v_project_tasks.task_delegated_id=$user_id_company
				ORDER BY v_project.id_project DESC";
				
			} else
			if($id_supchain_type == 331){
				$sqlProjects = "SELECT DISTINCT project_name, project_type_name, id_project 
					FROM v_project 
				WHERE project_type=490 AND cooperative_id=$user_id_company
				ORDER BY id_project DESC";
				
			} else {
				$sqlProjects = "SELECT DISTINCT project_name, project_type_name, id_project 
					FROM v_project 
				WHERE project_type=490 AND id_company=$user_id_company
				ORDER BY id_project DESC";
			}
			
			$listProjects ='';
			$resultProjects = pg_query($conn, $sqlProjects);
			while($rowProjects = pg_fetch_assoc($resultProjects)){
				if($rowProjects['id_project'] == $id_project){ $selP = "selected"; } else { $selP = ""; }
				$listProjects .= '<option value="'. $rowProjects['id_project'] .'@@'.$rowProjects['project_name'].'"'. $selP .'>'.$rowProjects['project_name'].'</option>';
			}
			
			
			if($update_right == 1){
				$editBtn='<button class="btn btn-success pull-right" onclick="showProjectTaskDetails(\''. $id_task .'\',\''. $id_project .'\');">
					<i class="fa fa-pencil-alt" aria-hidden="true"></i>
				</button>';
			} else { $editBtn=""; }
			
			$task='<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_TASK_MODAL_SHOT_TITLE'] .'</label><br/>
								<div class="projectTaskShow">'. $row['task_titleshort'] .' </div>
								<div class="projectTaskEdit hide">
									<input id="projectTaskModal_task_titleshort" value="'. $row['task_titleshort'] .'" type="text" class="form-control" />
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<label class="ord_sum_label">'. $lang['PROJECT_TASK_FARMER'] .'</label><br/>
							'.$tt_farmers.'
						</div>
						
						<div class="col-md-3">
							<label class="ord_sum_label">'. $lang['PROJECT_TASK_FIELD'] .'</label><br/>
							'.$tt_fields.'
						</div>
					</div>
					
					<div class="row" style="background:#e4e4e4;padding-top:5px;margin-bottom:5px;margin-top:10px;">
						<div class="col-md-6">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_TASK_MODAL_START_DATE'] .'</label>
								<div class="projectTaskShow">
									<div class="input-group date">
										<i class="fa fa-calendar"></i> '. $row['planned_start_date'] .' 
									</div>
								</div>
								<div class="projectTaskEdit hide">
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" value="'. $row['planned_start_date'] .'" id="projectTaskModal_start_date">
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-4 hide">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_TASK_MODAL_END_DATE'] .'</label>
								<div class="projectTaskShow">
									<div class="input-group date">
										<i class="fa fa-calendar"></i> '. $row['planned_end_date'] .'   
									</div>
								</div>
								<div class="projectTaskEdit hide">
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" value="'. $row['planned_end_date'] .'" id="projectTaskModal_end_date">
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_TASK_MODAL_DUE_DATE'] .'</label>
								<div class="projectTaskShow">
									<div class="input-group date">
										<i class="fa fa-calendar"></i> '. $row['due_date'] .'   
									</div>
								</div>
								<div class="projectTaskEdit hide">
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" value="'. $row['due_date'] .'" id="projectTaskModal_due_date">
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_TASK_MODAL_DELEGATED'] .'</label><br/>
								<div class="projectTaskShow">'. $row['task_delegated_name'] .'</div> 
								<div class="projectTaskEdit hide">
									<select id="projectTaskModal_task_delegated_id" class="form-control" onchange="delegatedAgents(this.value,'.$row['agent_id'].');">'.$task_delegated.'</select>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_TASK_AGENT'] .'</label><br/>
								<div class="projectTaskShow">'. $row['agent_name'] .'</div>
								<div class="projectTaskEdit hide"> 
									<select id="projectTaskModal_agent_id" class="form-control" disabled>'.$agents.'</select>
									<div id="changeAgentBtn" style="margin-top:10px;">
										<button type="button" class="btn btn-primary" onclick="changeAgent(1);">'.$lang['PROJECT_CHANGE_AGENT'].'</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_TASK_MODAL_STATUS'] .'</label><br/>
								<div class="projectTaskShow">
									<div class="col-md-6 p-xs b-r-sm '.$status_bg_color.'" style="font-weight:normal">'. $row['task_status_name'] .' </div>
								</div> 
								<div class="projectTaskEdit hide">
									<select id="projectTaskModal_task_status" class="form-control">'.$status.'</select> 
								</div>
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_TASK_COMPLETED']. ' <br/><br/>
								<input type="checkbox" id="updateTaskStatus" class="i-checks" '.$done.' value="'. $row['task_done'] .'" disabled> </label>
								<input type="hidden" id="projectTaskModal_id_project" value="'. $row['id_project'] .'" />
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="ord_sum_label">'. $lang['PROJECT_TASK_AGENT_ASSIST'] .'</label><br/>
								<div class="projectTaskShow">'. $row['agent_assist_name'] .'</div>
								<div class="projectTaskEdit hide">
									<select id="projectTaskModal_agent_assist_id" class="form-control" '.$agents_disabled.'>'.$agent_assist.'</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="ord_sum_label">'. $lang['PROJECT_TASK_MODAL_DESCRIPTION'] .'</label><br/>
						<div class="projectTaskShow">'. $row['task_description'] .'</div>
						<div class="projectTaskEdit hide">
							<textarea id="projectTaskModal_task_description" style="height:57px;" class="form-control">'. $row['task_description'] .'</textarea>  
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<label class="ord_sum_label">Project </label><br/>
					<div class="projectTaskShow">'. $selProject .'</div>
					<div class="projectTaskEdit hide">
						<select id="projectTaskModal_task_project_id" class="form-control" disabled>'. $listProjects .'</select>
					</div>
				</div>
				
				<div class="col-md-4 projectTaskEdit hide" id="task_project_idEdit_box">
					<button class="btn" onclick="editMoveTask(\''. $id_task .'\',\''. $id_project .'\',\''. $selProject .'\');" style="margin-top: 30px;">
						<i class="fa fa-edit" aria-hidden="true"></i>
					</button>
				</div>
			</div>
			
			<div class="row" id="projectTaskBtnToggle" style="margin-top:15px;">
				'.$editBtn.'
			</div>';
			
			$dom=$task;
		
		break;
		
		
		
		case "project_task_agent_list":
		
			$id_town = $_GET['id_town'];
			$id_task = $_GET['id_task'];
			$agent_id = $_GET['agent_id'];
			$id_company = $_GET['id_company'];
			$id_project = $_GET['id_project'];
			
			// Agents
			$sql_agents="select get_contact_name(agent_id) as name, agent_id FROM project_members where membertype_id=621 and task_id=$id_task";
		
			$agents ='<option value="">-- Agent list --</option>';
			$result_agents = pg_query($conn, $sql_agents);
			while($row_agents = pg_fetch_assoc($result_agents)){
				if($agent_id!=""){ 
					if($agent_id == $row_agents['agent_id']) {
						$sel_agent = 'selected';
					} else { $sel_agent = ''; }
				}
				$agents .='<option value="'. $row_agents['agent_id'] .'"'. $sel_agent .'>'. $row_agents['name'] .'</option>';
			}
			
			// Farmers NOT IN
			// $sql_farmers="select * from contact where id_category=6 and
			// id_contact in ( select id_contact from plantation where id_town=$id_town )
			// and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
			// and id_contact not in ( select contact_id from project_members where task_id=$id_task 
			// and agent_id = $agent_id and membertype_id=624) order by name asc";
			
			$sql_farmers="select * from v_plantation where gid_town=$id_town
			and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company)
			and gid_plantation not in ( select coalesce(plantation_id,0) from project_members where task_id=$id_task and agent_id=$agent_id and membertype_id=624)
			ORDER BY name_farmer";
		
			$farmers ='';
			$result_farmers = pg_query($conn, $sql_farmers);
			while($row_farmers = pg_fetch_assoc($result_farmers)){
				
				$farmers .='<li>
					<div class="row">
						<div class="col-md-10">
							<a href="javascript:addFarmerInList(\''. $id_project .'\', \''. $id_task .'\', \''. $row_farmers['id_farmer'] .'\', \''. $id_company .'\', \''. $id_town .'\', \''. $row_farmers['gid_plantation'] .'\');" class="project_agents_nr">
								'. $row_farmers['name_farmer'] .' ('.$row_farmers['code_farmer'].') <br> 
								<small style="color:#aaa; font-size:10px;">'.$row_farmers['code_parcelle'].' - '.$row_farmers['name_town'].'</small>
							</a>
						</div>
						<div class="col-md-2">
							<a href="javascript:addFarmerInList(\''. $id_project .'\', \''. $id_task .'\', \''. $row_farmers['id_farmer'] .'\', \''. $id_company .'\', \''. $id_town .'\', \''. $row_farmers['gid_plantation'] .'\');">
								<i class="fa fa-chevron-right" style="line-height:30px; font-size:16px;"></i>
							</a>
						</div>
					</div>
				</li>';
			}
			
			// Farmers IN
			// $sql_farmers2="Select name, contact_code, id_contact from contact where id_contact in
			// ( select contact_id from project_members where task_id=$id_task and agent_id = $agent_id and membertype_id=624) order by name asc";
			
			$sql_farmers2="Select * from v_plantation where gid_plantation in 
			( select plantation_id from project_members where task_id=$id_task and agent_id = $agent_id and membertype_id=624 )
			order by name_farmer asc";
		
			$farmers2 ='';
			$result_farmers2 = pg_query($conn, $sql_farmers2);
			while($row_farmers2 = pg_fetch_assoc($result_farmers2)){
				
				$farmers2 .='<li>
					<div class="row">
						<div class="col-md-2">
							<a href="javascript:removeFarmerInList(\''. $id_task .'\', \''. $agent_id .'\', \''. $id_town .'\', \''. $id_company .'\', \''. $id_project .'\', '.$row_farmers2['id_farmer'].');">
								<i class="fa fa-chevron-left" style="line-height:30px; font-size:16px;"></i>
							</a>
						</div>
						<div class="col-md-10">
							<a href="javascript:removeFarmerInList(\''. $id_task .'\', \''. $agent_id .'\', \''. $id_town .'\', \''. $id_company .'\', \''. $id_project .'\', '.$row_farmers2['id_farmer'].');" class="project_farmers_nr">
								'. $row_farmers2['name_farmer'] .' ('.$row_farmers2['code_farmer'].') <br> 
								<small style="color:#aaa; font-size:10px;">'.$row_farmers2['code_parcelle'].' - '.$row_farmers2['name_town'].'</small>
							</a>
						</div>
					</div>
				</li>';
			}
			
			$dom=$agents.'##'.$farmers.'##'.$farmers2;
		
		break;
		
		
		case "update_project_task_done":
		
			$task_done = $_GET['task_done'];
			$id_task = $_GET['id_task'];
			
			$id_contact = $_SESSION['id_contact'];
			$modified_date = gmdate("Y/m/d H:i");

			$sql = "UPDATE public.project_task
				SET  task_done=$task_done, modify_by=$id_contact, modified_date='$modified_date'
			WHERE id_task=$id_task";	
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "update_project_quadrant":
		
			$id_project = $_GET['id_project'];
			$region_quadrant = $_GET['region_quadrant'];
			
			$id_contact = $_SESSION['id_contact'];
			$modified_date = gmdate("Y/m/d H:i");

			$sql = "UPDATE public.project
				SET region_quadrant='$region_quadrant', modify_by=$id_contact, modified_date='$modified_date'
			WHERE id_project=$id_project";	
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "show_agents_on_map":
		
			$id_town = $_GET['id_town'];
			$id_company = $_GET['id_company'];
			
			$coords="";
			$sql = "select name, coordx, coordy from contact WHERE id_category=6 AND id_town=$id_town
			and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )";
			
			$result = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($result)){
				$coords .= $row['name'].'##'.$row['coordx'].'##'.$row['coordy'].'@@';
			}
			
			$coords .= 'end';
			
			$dom=$coords;
		
		break;
		
		
		case "show_region_on_map":
		
			$id_project = $_GET['id_project'];
			
			$coords="";
			$sql = "SELECT name_town, x, y FROM v_project_tasks WHERE id_project=$id_project";
			$result = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($result)){
				$coords .= $row['name_town'].'##'.$row['x'].'##'.$row['y'].'@@';
			}
			
			$coords .= 'end';
			
			$dom=$coords;
		
		break;
		
		
		case "show_town_notIn_on_map":
		
			$id_project = $_GET['id_project'];	
			$id_company = $_SESSION['id_company'];
			
			$sql_prj="SELECT country_id, region_name FROM project WHERE id_project = $id_project";
			$result_prj = pg_query($conn, $sql_prj);
			$row_prj = pg_fetch_assoc($result_prj);
			
			$coords="";
			if($row_prj) {
				$id_country = $row_prj['country_id'];
				$region_name = $row_prj['region_name'];
				
				// $sql = "SELECT id_town, name_town, x, y FROM towns WHERE id_town NOT IN
				// (SELECT town_id FROM v_project_tasks WHERE id_company=$id_company) AND id_country=$id_country";
				// $result = pg_query($conn, $sql);
				
				// change on the 27/10/2021
				
				$sql = "select id_town, x, y, name_town from towns where id_country=$id_country
				AND towns.x is not NULL 
				and region1 in (select region_name from project where region_name like '%$region_name%')";
				$result = pg_query($conn, $sql);
				
				while($row = pg_fetch_assoc($result)) {
					$coords .= $row['name_town'].'##'.$row['x'].'##'.$row['y'].'@@';
				}
			}
			
			$coords .= 'end';
			
			$dom=$coords;
		
		break;
		
		
		case "show_town_on_map":
		
			$id_task = $_GET['id_task'];
			
			$sql = "SELECT name_town, x, y FROM v_project_tasks WHERE id_task=$id_task";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);
			$coords = $row['name_town'].'##'.$row['x'].'##'.$row['y'];
			
			$dom=$coords;
		
		break;
		
		
		case "region_show_quadrant":
		
			$id_project = $_GET['id_project'];
			
			$sql = "SELECT region_quadrant FROM v_project WHERE id_project=$id_project";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);
			$region_quadrant = $row['region_quadrant'];
			
			$dom=$region_quadrant;
		
		break;
		
		
		case "delete_project_task":
		
			$id_task = $_GET['id_task'];

			$sql = "delete from project_act_members where proj_member_id in ( select id_projmember from project_members where task_id=$id_task )";
			pg_query($conn, $sql);
			
			$sql2 = "delete from project_activity where task_id=$id_task";
			$result2 = pg_query($conn, $sql2);

			if ($result2) {
				$sql3 = "delete from project_members where task_id=$id_task";
				$result3 = pg_query($conn, $sql3);
				
				if ($result3) {
					$sql4 = "delete from project_task where id_task=$id_task";
					$result4 = pg_query($conn, $sql4);
				
					if ($result4) {
						$dom=1;
					} else {
						$dom=0;
					}
					
				} else {
					$dom=0;
				}
				
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "time_line_gantt":
		
			$id_project = $_GET['id_project'];
			
			$sql = "SELECT task_titleshort, task_description,
				to_char(start_date::timestamp with time zone, 'yyyy-mm-dd'::text) AS start_date,
				to_char(end_date::timestamp with time zone, 'yyyy-mm-dd'::text) AS end_date
			FROM v_project_tasks WHERE start_date IS NOT NULL AND id_project=$id_project";
			$result = pg_query($conn, $sql);
			
			$sched="";
			while($row = pg_fetch_assoc($result)){
				$sched.= $row['task_titleshort'].'|'.$row['start_date'].'|'.$row['end_date'].'|'.$row['task_description'].'??';
			}
			
			$dhtmlx='<div id="scheduler_here" class="dhx_cal_container" style="width:100%; height:100%;">
				<div class="dhx_cal_navline">
					<div class="dhx_cal_prev_button">&nbsp;</div>
					<div class="dhx_cal_next_button">&nbsp;</div>
					<div class="dhx_cal_today_button"></div>
					<div class="dhx_cal_date"></div>
					<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
					<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
					<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
				</div>
				<div class="dhx_cal_header">
				</div>
				<div class="dhx_cal_data">
				</div>
			</div>';
			
			$dom=$dhtmlx.'##'.$sched;
		
		break;
		
		
		case "send_quadrant_mail":
		
			$id_project = $_GET['id_project'];
			
			$sql = "SELECT project_name, id_project, region_quadrant, modify_by, modified_date
				FROM project 
			WHERE id_project=$id_project";
			
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);
			
			$project_name = $row['project_name'];
			$id_project = $row['id_project'];
			$modify_by = $row['modify_by'];
			$modified_date = $row['modified_date'];
			$region_quadrant = $row['region_quadrant'];
		
			$json = json_decode($row['region_quadrant'], true);
			$left_long = $json['features'][0]['geometry']['coordinates'][0][0][0];
			$right_long = $json['features'][0]['geometry']['coordinates'][0][2][0];
			$top_lat = $json['features'][0]['geometry']['coordinates'][0][1][1];
			$bottom_lat = $json['features'][0]['geometry']['coordinates'][0][3][1];


			$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				<meta name="viewport" content="width=device-width">
				<title></title>
				</head>
			
				<body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background:#f3f3f3!important;box-sizing:border-box;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100%!important">
				<style type="text/css" align="center" class="float-center">
				@media only screen{html{min-height:100%;background:#f3f3f3}}
				@media only screen and (max-width:596px){.small-float-center{margin:0 auto!important;float:none!important;text-align:center!important}.small-text-center{text-align:center!important}.small-text-left{text-align:left!important}.small-text-right{text-align:right!important}}
				@media only screen and (max-width:596px){.hide-for-large{display:block!important;width:auto!important;overflow:visible!important;max-height:none!important;font-size:inherit!important;line-height:inherit!important}}
				@media only screen and (max-width:596px){table.body table.container .hide-for-large,table.body table.container .row.hide-for-large{display:table!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .callout-inner.hide-for-large{display:table-cell!important;width:100%!important}}
				@media only screen and (max-width:596px){table.body table.container .show-for-large{display:none!important;width:0;mso-hide:all;overflow:hidden}}@media only screen and (max-width:596px){table.body img{width:auto;height:auto}table.body center{min-width:0!important}table.body .container{width:95%!important}table.body .column,table.body .columns{height:auto!important;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;padding-left:16px!important;padding-right:16px!important}table.body .column .column,table.body .column .columns,table.body .columns .column,table.body .columns .columns{padding-left:0!important;padding-right:0!important}table.body .collapse .column,table.body .collapse .columns{padding-left:0!important;padding-right:0!important}td.small-1,th.small-1{display:inline-block!important;width:8.33333%!important}td.small-2,th.small-2{display:inline-block!important;width:16.66667%!important}td.small-3,th.small-3{display:inline-block!important;width:25%!important}td.small-4,th.small-4{display:inline-block!important;width:33.33333%!important}td.small-5,th.small-5{display:inline-block!important;width:41.66667%!important}td.small-6,th.small-6{display:inline-block!important;width:50%!important}td.small-7,th.small-7{display:inline-block!important;width:58.33333%!important}td.small-8,th.small-8{display:inline-block!important;width:66.66667%!important}td.small-9,th.small-9{display:inline-block!important;width:75%!important}td.small-10,th.small-10{display:inline-block!important;width:83.33333%!important}td.small-11,th.small-11{display:inline-block!important;width:91.66667%!important}td.small-12,th.small-12{display:inline-block!important;width:100%!important}.column td.small-12,.column th.small-12,.columns td.small-12,.columns th.small-12{display:block!important;width:100%!important}table.body td.small-offset-1,table.body th.small-offset-1{margin-left:8.33333%!important;Margin-left:8.33333%!important}table.body td.small-offset-2,table.body th.small-offset-2{margin-left:16.66667%!important;Margin-left:16.66667%!important}table.body td.small-offset-3,table.body th.small-offset-3{margin-left:25%!important;Margin-left:25%!important}table.body td.small-offset-4,table.body th.small-offset-4{margin-left:33.33333%!important;Margin-left:33.33333%!important}table.body td.small-offset-5,table.body th.small-offset-5{margin-left:41.66667%!important;Margin-left:41.66667%!important}table.body td.small-offset-6,table.body th.small-offset-6{margin-left:50%!important;Margin-left:50%!important}table.body td.small-offset-7,table.body th.small-offset-7{margin-left:58.33333%!important;Margin-left:58.33333%!important}table.body td.small-offset-8,table.body th.small-offset-8{margin-left:66.66667%!important;Margin-left:66.66667%!important}table.body td.small-offset-9,table.body th.small-offset-9{margin-left:75%!important;Margin-left:75%!important}table.body td.small-offset-10,table.body th.small-offset-10{margin-left:83.33333%!important;Margin-left:83.33333%!important}table.body td.small-offset-11,table.body th.small-offset-11{margin-left:91.66667%!important;Margin-left:91.66667%!important}table.body table.columns td.expander,table.body table.columns th.expander{display:none!important}table.body .right-text-pad,table.body .text-pad-right{padding-left:10px!important}table.body .left-text-pad,table.body .text-pad-left{padding-right:10px!important}table.menu{width:100%!important}table.menu td,table.menu th{width:auto!important;display:inline-block!important}table.menu.small-vertical td,table.menu.small-vertical th,table.menu.vertical td,table.menu.vertical th{display:block!important}table.menu[align=center]{width:auto!important}table.button.small-expand,table.button.small-expanded{width:100%!important}table.button.small-expand table,table.button.small-expanded table{width:100%}table.button.small-expand table a,table.button.small-expanded table a{text-align:center!important;width:100%!important;padding-left:0!important;padding-right:0!important}table.button.small-expand center,table.button.small-expanded center{min-width:0}}
				</style>
			
				<span class="preheader" style="color:#f3f3f3;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
				
				<table class="body" style="Margin:0;background:#f3f3f3!important;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					<center data-parsed="" style="min-width:580px;width:100%"><table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;padding:0;text-align:center;vertical-align:top;width:580px">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
					<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tr>
						<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
							<img src="https://icoop.live/ic/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
						</td>
						<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
							iCRM.live Message from icollect.live Back Office:
						</td>
					</tr></table>
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						Thank you! This is to confirm that new quadrant is added to a project
					</p>
					<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
					<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
					<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						<strong>Project Name</strong><br>'.$project_name.'
					</p>
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						<strong>Project ID</strong><br>'.$id_project.'
					</p>
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						<strong>Added by</strong><br>'.$modify_by.'
					</p>
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						<strong>Date</strong><br>'.$modified_date.'
					</p>
					</th>
					
					<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						<strong>Left Longitude</strong><br>'.$left_long.'
					</p>
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						<strong>Right Longitude</strong><br>'.$right_long.'
					</p>
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						<strong>Top Latitude</strong><br>'.$top_lat.'
					</p>
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						<strong>Bottom Latitude</strong><br>'.$bottom_lat.'
					</p>
					</th></tr></tbody></table></th></tr></tbody></table></th><th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th></tr></tbody></table>
					
			
					<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
					<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
					<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
						<strong>Quadrant JSON</strong><br>'.$region_quadrant.'
					</p></th></tr></tbody></table></th></tr></tbody></table>
			
					<hr>
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						Before printing think about the ENVIRONMENT!<br>
						Warning: If you have received this email by error, please delete it and
						inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
					</p>
					</th></tr></tbody></table></th></tr></tbody></table></td></tr></tbody></table></center></td></tr></tbody>
				</table></body>
			</html>';
	
			$sender="noreply@icoop.live";
			$recipient='croth53@gmail.com';
		
			$mail = new PHPMailer;
			$mail->isSMTP();
			// $mail->SMTPDebug = 4;
			$mail->SMTPSecure = 'ssl';
			$mail->Debugoutput = 'html';
			$mail->Host = "mail.icoop.live ";
			$mail->Port = 465;
			$mail->SMTPAuth = true;
			$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icoop.live>";
			$mail->Username = ID_USER;
			$mail->Password = ID_PASS;
			$mail->setFrom('noreply@icoop.live', 'New Quadrant');
			$mail->AddCC('charlessabenin@gmail.com');
			$mail->addReplyTo('croth53@gmail.com');
			$mail->addAddress('croth53@gmail.com');
			$mail->addAddress('charlessabenin@gmail.com');
			$mail->Subject = 'New Quadrant Added';
			$mail->msgHTML($message);
			$mail->AltBody = 'This is a plain-text message body';

			if (!$mail->send()) {
				$dom=0;
			} else {
				$dom=1;
			}

		break;
		
		
		case "save_mbtiles_file":
		
			$filename = $_GET['filename'];
			$id_project = $_GET['id_project'];
			$description = $_GET['description'];
			$maptype = $_GET['maptype'];
			
			$created_by = $_SESSION['id_user'];
			$creation_date = gmdate("Y/m/d H:i");
			
			$sql = "Insert into mobmbtiles ( id_project, filename, created_by, creation_date, description, maptype, visible ) 
			values ( $id_project, '$filename', $created_by, '$creation_date', '$description', '$maptype', 1)";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "show_mbtiles_liste":
		
			$id_project = $_GET['id_project'];
			
			$sql = "SELECT * FROM public.mobmbtiles WHERE id_project=$id_project ORDER BY id DESC";
			$result = pg_query($conn, $sql);

			$html = '';
			$i=1;
			while ($rows = pg_fetch_assoc($result)) {
				$html .= '<tr>
					<td>'. $i .'</td>
					<td>'. $rows['filename'] .'</td>
					<td>'. $rows['maptype'] .'</td>
					<td>'. $rows['creation_date'] .'</td>
					<td class="row_actions">';
						
						if($rows['visible']==1){
							$icon = '<i class="fas fa-check" style="color:green;font-size:16px;"></i>';
						} else {  
							$icon = '<i class="fas fa-times" style="color:red;font-size:16px;"></i>';
						}
						
						$html .= '<a href="#" onclick="toggleVisibleMbt('.$rows['visible'].','.$rows['id'].','.$id_project.');">'.$icon.'</a> 
						<a href="javascript:deleteMbtiles('.$rows['id'].','.$id_project.');" onclick="return confirm(\'Are you sure you want to delete this file : '. $rows['filename'] .' ?\')"><i class="far fa-trash-alt"></i></a>
					</td>
				</tr>';
				
				$i++;
			}

			$dom=$html;
		
		break;
		
		
		case "delete_mbtiles":
		
			$id = $_GET['id'];
			
			$sql = "delete from mobmbtiles where id=$id";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "toggle_mbtiles_visibility":
		
			$id = $_GET['id'];
			$visible = $_GET['visible'];
			
			$sql_sales = "UPDATE public.mobmbtiles SET visible=$visible WHERE id=$id";
			$result_sales = pg_query($conn, $sql_sales) or die(pg_last_error());
			$count = pg_num_rows($result_sales);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "update_task_project":
		
			$id_project = $_GET['id_project'];
			$id_task = $_GET['id_task'];
		
			$sql = 'SELECT * FROM public."MoveTaskProject"(\''. $id_task .'\', \''. $id_project .'\');';
			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);
			
			if($count==1){
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
	}
	
}

echo $dom;