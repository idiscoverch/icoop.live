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
		
		case "show_all_plantations":
		
			$id_user = $_SESSION['id_user'];
			
			$cond="";
			if(isset($_GET['bio']) && $_GET['bio']==1) { $cond.=" AND bio>510"; }
			if(isset($_GET['bio_suisse']) && $_GET['bio_suisse']==1) { $cond.=" AND bio_suisse>510"; }
			if(isset($_GET['rspo']) && $_GET['rspo']==1) { $cond.=" AND rspo>510"; }
			if(isset($_GET['fair_trade']) && $_GET['fair_trade']==1) { $cond.=" AND fair_trade>510"; }
			if(isset($_GET['globalgap']) && $_GET['globalgap']==1) { $cond.=" AND globalgap>510"; }
			if(isset($_GET['utz_rainforest']) && $_GET['utz_rainforest']==1) { $cond.=" AND utz_rainforest>510"; }
			if(isset($_GET['perimeter']) && $_GET['perimeter']==1) { $cond.=" AND perimeter=1"; }
			if(isset($_GET['eco_river']) && $_GET['eco_river']==1) { $cond.=" AND eco_river=1"; }
			if(isset($_GET['eco_shallows']) && $_GET['eco_shallows']==1) { $cond.=" AND eco_shallows=1"; }
			if(isset($_GET['eco_wells']) && $_GET['eco_wells']==1) { $cond.=" AND eco_wells=1"; }
			if(isset($_GET['synthetic_fertilizer']) && $_GET['synthetic_fertilizer']==1) { $cond.=" AND synthetic_fertilizer IS NOT NULL"; }
			if(isset($_GET['synthetic_herbicides']) && $_GET['synthetic_herbicides']==1) { $cond.=" AND synthetic_herbicides IS NOT NULL"; }
			if(isset($_GET['synthetic_pesticide']) && $_GET['synthetic_pesticide']==1) { $cond.=" AND synthetic_pesticide IS NOT NULL"; }
			if(isset($_GET['intercropping']) && $_GET['intercropping']==1) { $cond.=" AND intercropping IS NOT NULL"; }
			if(isset($_GET['forest']) && $_GET['forest']==1) { $cond.=" AND forest=508"; }
			if(isset($_GET['sewage']) && $_GET['sewage']==1) { $cond.=" AND sewage=508"; }
			if(isset($_GET['waste']) && $_GET['waste']==1) { $cond.=" AND waste=508"; }
			if(isset($_GET['fire']) && $_GET['fire']==1) { $cond.=" AND fire=508"; }
			if(isset($_GET['irrigation']) && $_GET['irrigation']==1) { $cond.=" AND irrigation=508"; }
			if(isset($_GET['drainage']) && $_GET['drainage']==1) { $cond.=" AND drainage=508"; }
			if(isset($_GET['slope']) && $_GET['slope']==1) { $cond.=" AND slope=508"; }
			if(isset($_GET['pest']) && $_GET['pest']==1) { $cond.=" AND pest IS NOT NULL"; }
			if(isset($_GET['rating'])) { $cond.=" AND rating =" . $_GET['rating']; }
			if(isset($_GET['surface_ha'])) { $area=$_GET['surface_ha']; $cond.=" AND surface_ha>=$area AND surface_ha<($area+1)"; }
			if(isset($_GET['year_creation'])) { $cond.=" AND year_creation >=" . $_GET['year_creation']; } 
			if(isset($_GET['extension']) && $_GET['extension']==1) { $cond.=" AND extension IS NOT NULL"; }
			if(isset($_GET['year_extension'])) { $cond.=" AND year_extension >=" . $_GET['year_extension']; } 
			if(isset($_GET['replanting']) && $_GET['replanting']==1) { $cond.=" AND replanting IS NOT NULL"; }
			if(isset($_GET['year_to_replant'])) { $cond.=" AND year_to_replant >=" . $_GET['year_to_replant']; } 
			if(isset($_GET['road_access']) && $_GET['road_access']==1) { $cond.=" AND road_access = 508"; }
			

			$sql_plan = "select gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
			id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
			code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
			cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
			CONCAT(name_town,'-',zone) AS plantation_town, to_char(surface_ha,'999G999D9999') surface_ha, 
			to_char(area_acres,'999G999D9999') area_acres
			from v_plantation where id_contact <> 0 $cond 
			AND gid_plantation NOT IN (1747959829, 1747960171, 1747961163, 1747961708, 1747961706, 1747961836, 1747959175, 1747961531)
			and id_contact in (
			select id_contact from (
				select * from contact where id_contact in (
				select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) ) AND id_type = '9'
				UNION
				select * from contact where id_primary_company in (
				select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) )
				and id_contact in ( select id_contact from users ) AND id_type = '9'
				union
				select * from contact where id_contact in (
				select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) ) AND id_type = '9'
				union
				select * from contact where id_primary_company in (
				select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) )
				and id_contact in ( select id_contact from users ) AND id_type = '9'
				union
				select * from contact where id_primary_company in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) AND id_type = '9'
				union
				select * from contact where id_primary_company in ( select id_link from contact_links where
				id_contact in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) )) AND id_type = '9'
				union
				select * from contact where id_primary_company in ( select id_contact from contact_links where
				id_link in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) )) AND id_type = '9'
				union
				select * from contact where id_contact in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) AND id_type = '9'
				UNION
				select * from contact where id_cooperative in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) AND id_type = '9'
				ORDER BY name ASC ) c where id_category=6 or id_supchain_type=642 ) 
			";
			
			// $sql_plan = "select gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
				// id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
				// code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
				// cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact
			// from v_plantation where id_contact in (
				// select id_contact from contact where id_contact in (
                // select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
                // id_contact=(select id_contact from users where id_user=$id_user ) ) ) AND id_category=6
                // UNION
                // select id_contact from contact where id_primary_company in (
                // select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
                // id_contact=(select id_contact from users where id_user=$id_user ) ) )
                // and id_contact in ( select id_contact from users ) AND id_category=6
                // union
                // select id_contact from contact where id_contact in (
                // select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
                // id_contact=(select id_contact from users where id_user=$id_user ) ) ) AND id_category=6
                // union
                // select id_contact from contact where id_primary_company in (
                // select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
                // id_contact=(select id_contact from users where id_user=$id_user ) ) )
                // and id_contact in ( select id_contact from users ) AND id_category=6
                // union
                // select id_contact from contact where id_primary_company in ( select id_primary_company from contact where
                // id_contact=(select id_contact from users where id_user=$id_user ) ) AND id_category=6
                // union
                // select id_contact from contact where id_primary_company in ( select id_link from contact_links where
                // id_contact in ( select id_primary_company from contact where
                // id_contact=(select id_contact from users where id_user=$id_user ) )) AND id_category=6
                // union
                // select id_Contact from contact where id_primary_company in ( select id_contact from contact_links where
                // id_link in ( select id_primary_company from contact where
                // id_contact=(select id_contact from users where id_user=$id_user ) )) AND id_category=6  
			// )";

			$result_plan = pg_query($conn, $sql_plan);
			
			$geojson_plantation = array('type' => 'FeatureCollection', 'features' => array());
			
			while($row_plantation = pg_fetch_assoc($result_plan)){
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
			// $dom = $sql_plan; 
			
		break;
		
		
		case "show_plantations":

			$id_farmer = $_GET['id_farmer'];

			$sql_plan = "SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
				id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
				code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
				cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
				to_char(surface_ha,'999G999D9999') surface_ha
			FROM public.v_plantation WHERE id_farmer = $id_farmer";

			$result_plan = pg_query($conn, $sql_plan);
			
			$geojson_plantation = array('type' => 'FeatureCollection', 'features' => array());
			
			while($row_plantation = pg_fetch_assoc($result_plan)){
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
		
		
		case "geolocation_contacts":

			$id_user = $_SESSION['id_user'];
			$id_company = $_SESSION['id_company'];
			$id_primary_company = $_SESSION["id_primary_company"];

			if($id_company == 636){
				$sql_stats = "select * from contact where id_contact in ( 
					select id_contracting_party from contract where id_contractor in ( 645, 646, 647 ) 
				) order by name";
			} else {
				$sql_stats= "select * from (
					select * from contact where id_contact in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )  AND id_type = '9'
					UNION
					select * from contact where id_primary_company in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )
					and id_contact in ( select id_contact from users )  AND id_type = '9'
					union
					select * from contact where id_contact in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )  AND id_type = '9'
					union
					select * from contact where id_primary_company in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )
					and id_contact in ( select id_contact from users )  AND id_type = '9'
					union
					select * from contact where id_primary_company in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = '9'
					union
					select * from contact where id_primary_company in ( select id_link from contact_links where
					id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ))  AND id_type = '9'
					union
					select * from contact where id_primary_company in ( select id_contact from contact_links where
					id_link in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ))  AND id_type = '9'
					union
					select * from contact where id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = '9'
					UNION
					select * from contact where id_cooperative in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = '9'
					ORDER BY name ASC ) c where id_category=6 or id_supchain_type=642
				";
			}

			$result = pg_query($conn, $sql_stats);

			$contact_list = '';
			$geolocation = '';
			$list_towns = '<option value="">Search by towns</option>';

			while($arr = pg_fetch_assoc($result)){
				$mark = ''; $pin = '';
				$sql_plan = "SELECT geom_json, coordx, coordy FROM public.v_plantation WHERE v_plantation.id_farmer = '". $arr['id_contact'] ."'";
				$result_plan = pg_query($conn, $sql_plan);
				
				while($row_plantation = pg_fetch_assoc($result_plan)){
					if($row_plantation['geom_json']!=null) { $mark = '<i class="fa fa-map-marker pull-right"></i>'; } 
					if(($row_plantation['coordx']!=null)AND($row_plantation['coordy']!=null)) { $pin = '<i class="fa fa-map-pin pull-right"></i></i>'; } 
				}
				
				$contact_name = $arr['name'];  
				$contact_list .= '<li><a href="javascript:showPlantations(\''. $arr['id_contact'] .'\');" class="geo_contact_name">
				'. $arr['id_contact'] .'-'. htmlentities($contact_name, ENT_QUOTES). $mark .'&nbsp;'. $pin .' 
						<div style="color:#aaa; font-size:12px; width:60%;">('. $arr['contact_code'].')</div>
						<div style="color:#aaa; font-size:10px; width:60%;">'. $arr['town_name'] .'</div>
					</a>
				</li>';
			}

			if($id_company == 636){
				$sql_towns = "SELECT DISTINCT id_town, name_town FROM v_icw_contacts
				where id_contact in ( select id_contracting_party from contract where id_contractor in ( 645, 646, 647 ) ) order by name_town";
				
			} else {
				$sql_towns = "SELECT DISTINCT id_town, name_town FROM v_icw_contacts WHERE id_contact in (
				  SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
					UNION
					SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor in(
					  SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
					)
				) AND id_category=6 ORDER BY name_town ASC";
			}
			$result_towns = pg_query($conn, $sql_towns);
			while($arr_towns = pg_fetch_assoc($result_towns)){
				$list_towns .= '<option value="'. $arr_towns['id_town'] .'">'. $arr_towns['name_town'] .'</option>';
			}
			
			// Project List
			
			$id_supchain_type = $_SESSION["id_supchain_type"];

			if($id_supchain_type == 228){
				$sql_projects = "SELECT 
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
				
			} else
			if($id_supchain_type == 331){
				$sql_projects = "SELECT DISTINCT project_name, project_type_name, id_project 
					FROM v_project 
				WHERE project_type=490 AND cooperative_id=$id_company
				ORDER BY id_project DESC";
				
			} else {
				
				if($id_primary_company == 636) {
					$case="AND id_company IN (645, 646, 647)"; 
				} else {
					$case="AND id_company=$id_company"; 
				}
				
				$sql_projects = "SELECT DISTINCT project_name, project_type_name, id_project 
					FROM v_project 
				WHERE project_type=490 $case
				ORDER BY id_project DESC";
			}
			
			$list_projects ='<option value="">Search by projects</option>';
			$result_projects = pg_query($conn, $sql_projects);
			while($row_projects = pg_fetch_assoc($result_projects)){
				$list_projects .= '<option value="'. $row_projects['id_project'] .'">'. $row_projects['project_name'] .'</option>';
			}

			$dom = $contact_list.'##'.$list_towns.'##'.$list_projects;

		break;
		
		
		case "show_by_towns":

			$chain_type = $_SESSION['id_supchain_type'];
			$id_company = $_SESSION['id_company'];
			$downline = $_SESSION['downline'];
			$id_contact = $_SESSION['id_contact'];
			$id_town = $_GET['code_town'];
			
			$contact_list = "";

			if($chain_type == 113){
				if($downline == 1){
					$sql_stats = "SELECT * FROM v_icw_contacts WHERE id_contact in (
					  SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
						UNION
						SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor in(
						  SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
						)
					) AND id_category=6 AND id_town = '$id_town' ORDER By lastname ASC";

				} else {
					$sql_stats = "SELECT * FROM v_icw_contacts WHERE id_contact in (
					  SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
					) AND id_category=6 AND id_town = '$id_town' ORDER By lastname ASC";
				}
			} else
			if($chain_type == 114){
				$sql_stats = "SELECT * FROM v_icw_contacts WHERE id_contact in (
					SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
				) AND id_category=6 AND id_town = '$id_town' ORDER BY lastname ASC";

			} else {
				$sql_stats = "SELECT * FROM v_icw_contacts WHERE id_contact = '$id_contact' AND id_category=6";
			}

			$result = pg_query($conn, $sql_stats);

			while($arr = pg_fetch_assoc($result)){
				
				$mark = ''; $pin = '';
				$sql_plan = "SELECT geom_json, coordx, coordy FROM public.v_plantation WHERE v_plantation.id_farmer = '". $arr['id_contact'] ."'";
				$result_plan = pg_query($conn, $sql_plan);
				
				while($row_plantation = pg_fetch_assoc($result_plan)){
					if($row_plantation['geom_json']!="") { $mark = '<i class="fa fa-map-marker pull-right"></i>'; } 
					if(($row_plantation['coordx']!="")AND($row_plantation['coordy']!="")) { $pin = '<i class="fa fa-map-pin pull-right"></i></i>'; } 
				}
				
				$contact_name = $arr['contact_name'];
				$contact_list .= '<li><a href="javascript:showPlantations(\''. $arr['id_contact'] .'\');" class="geo_contact_name">
				'. $arr['id_contact'] .'-'. htmlentities($contact_name, ENT_QUOTES) . $mark .'&nbsp;'. $pin . ' 
						<div style="color:#aaa; font-size:12px; width:60%;">('. $arr['contact_code'].')</div>
						<div style="color:#aaa; font-size:10px; width:60%;">'. $arr['name_town'] .'</div>
					</a>
				</li>';
			}
			
			if($chain_type == 113){
				if($downline == 1){
					$sql_pl = "SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
						id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
						code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
						cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
						CONCAT(name_town,'-',zone) AS plantation_town, to_char(surface_ha,'999G999D9999') surface_ha, 
						to_char(area_acres,'999G999D9999') area_acres
					FROM public.v_plantation WHERE id_contact in ( 
						SELECT id_contact FROM v_icw_contacts WHERE id_contact in (
					  SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
						UNION
						SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor in(
						  SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
						)
					  ) AND id_category=6 AND id_town = '$id_town' 
					)";

				} else {
					$sql_pl = "SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
						id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
						code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
						cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
						CONCAT(name_town,'-',zone) AS plantation_town, to_char(surface_ha,'999G999D9999') surface_ha, 
						to_char(area_acres,'999G999D9999') area_acres
					FROM public.v_plantation WHERE id_contact in ( 
						SELECT id_contact FROM v_icw_contacts WHERE id_contact in (
							SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
						) AND id_category=6 AND id_town = '$id_town'
					)";
				}
			} else
			if($chain_type == 114){
				$sql_pl = "SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
					id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
					code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
					cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
					CONCAT(name_town,'-',zone) AS plantation_town, to_char(surface_ha,'999G999D9999') surface_ha, 
					to_char(area_acres,'999G999D9999') area_acres
				FROM public.v_plantation WHERE id_contact in ( 
					SELECT id_contact FROM v_icw_contacts WHERE id_contact in (
						SELECT id_contracting_party FROM v_icw_contracts WHERE id_contractor='$id_company'
					) AND id_category=6 AND id_town = '$id_town'
				)";

			} else {
				$sql_pl = "SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
					id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
					code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
					cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
					CONCAT(name_town,'-',zone) AS plantation_town, to_char(surface_ha,'999G999D9999') surface_ha, 
					to_char(area_acres,'999G999D9999') area_acres
				FROM public.v_plantation WHERE id_contact in ( 
					SELECT id_contact FROM v_icw_contacts WHERE id_contact = '$id_contact' AND id_category=6
				)";
			}

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

			$dom = $contact_list.'**'.json_encode($geojson_plantation['features']);

		break;
		
		
		case "show_by_project":

			$id_project = $_GET['id_project'];
			
			$id_company = $_SESSION['id_company'];
			$id_user = $_SESSION['id_user'];
			

			if($id_company == 636){
				$sql_stats = "select * from contact c where id_contact in ( 
					select id_contracting_party from contract where id_contractor in ( 645, 646 ) 
				) 
				and exists ( select 1 from project_members m where m.contact_id=c.id_contact and m.project_id=$id_project )";
				
				$sql_pl = "SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
						id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
						code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
						cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
						CONCAT(name_town,'-',zone) AS plantation_town, to_char(surface_ha,'999G999D9999') surface_ha, 
						to_char(area_acres,'999G999D9999') area_acres
					FROM public.v_plantation WHERE id_contact in ( 
						select id_contact from contact c where id_contact in ( 
						select id_contracting_party from contract where id_contractor in ( 645, 646 ) 
					) 
					and exists ( select 1 from project_members m where m.contact_id=c.id_contact and m.project_id=$id_project )
				)";
		
			} else {
				
				$sql_stats= "select * from (
                    select * from contact where id_contact in (
                    select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )  AND id_type = '9'
                    UNION
                    select * from contact where id_primary_company in (
                    select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
                    id_contact=(select id_contact from users where id_user=$id_user ) ) )
                    and id_contact in ( select id_contact from users )  AND id_type = '9'
                    union
                    select * from contact where id_contact in (
                    select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
                    id_contact=(select id_contact from users where id_user=$id_user ) ) )  AND id_type = '9'
                    union
                    select * from contact where id_primary_company in (
                    select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
                    id_contact=(select id_contact from users where id_user=$id_user ) ) )
                    and id_contact in ( select id_contact from users )  AND id_type = '9'
                    union
                    select * from contact where id_primary_company in ( select id_primary_company from contact where
                    id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = '9'
                    union
                    select * from contact where id_primary_company in ( select id_link from contact_links where
                    id_contact in ( select id_primary_company from contact where
                    id_contact=(select id_contact from users where id_user=$id_user ) ))  AND id_type = '9'
                    union
                    select * from contact where id_primary_company in ( select id_contact from contact_links where
					id_link in ( select id_primary_company from contact where
                    id_contact=(select id_contact from users where id_user=$id_user ) ))  AND id_type = '9'
                    union
                    select * from contact where id_contact in ( select id_primary_company from contact where
                    id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = '9'
                    UNION
                    select * from contact where id_cooperative in ( select id_primary_company from contact where
                    id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = '9'
                    ORDER BY name ASC 
				) c where ( id_category=6 or id_supchain_type=642 )
                and exists ( select 1 from project_members m where m.contact_id=c.id_contact and m.project_id=$id_project )";
				
				$sql_pl = "SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
						id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
						code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
						cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
						CONCAT(name_town,'-',zone) AS plantation_town, to_char(surface_ha,'999G999D9999') surface_ha, 
						to_char(area_acres,'999G999D9999') area_acres
                    FROM public.v_plantation WHERE id_contact in (

                        select id_contact from (
                            select * from contact where id_contact in (
                            select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
                            id_contact=(select id_contact from users where id_user=$id_user ) ) )  AND id_type = '9'
                            UNION
                            select * from contact where id_primary_company in (
                            select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
                            id_contact=(select id_contact from users where id_user=$id_user ) ) )
                            and id_contact in ( select id_contact from users )  AND id_type = '9'
                            union
                            select * from contact where id_contact in (
                            select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
                            id_contact=(select id_contact from users where id_user=$id_user ) ) )  AND id_type = '9'
                            union
                            select * from contact where id_primary_company in (
                            select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
                            id_contact=(select id_contact from users where id_user=$id_user ) ) )
                            and id_contact in ( select id_contact from users )  AND id_type = '9'
                            union
                            select * from contact where id_primary_company in ( select id_primary_company from contact where
                            id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = '9'
                            union
                            select * from contact where id_primary_company in ( select id_link from contact_links where
                            id_contact in ( select id_primary_company from contact where
                            id_contact=(select id_contact from users where id_user=$id_user ) ))  AND id_type = '9'
                            union
                            select * from contact where id_primary_company in ( select id_contact from contact_links where
							id_link in ( select id_primary_company from contact where
                            id_contact=(select id_contact from users where id_user=$id_user ) ))  AND id_type = '9'
                            union
                            select * from contact where id_contact in ( select id_primary_company from contact where
                            id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = '9'
                            UNION
                            select * from contact where id_cooperative in ( select id_primary_company from contact where
                            id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = '9'
                            ORDER BY name ASC 
						) c where ( id_category=6 or id_supchain_type=642 )
                        and exists ( select 1 from project_members m where m.contact_id=c.id_contact and m.project_id=$id_project )
                    )
				";
			}

			$result = pg_query($conn, $sql_stats);

			$contact_list = '';

			while($arr = pg_fetch_assoc($result)){
				$mark = ''; $pin = '';
				$sql_plan = "SELECT geom_json, coordx, coordy FROM public.v_plantation WHERE v_plantation.id_farmer = '". $arr['id_contact'] ."'";
				$result_plan = pg_query($conn, $sql_plan);
				
				while($row_plantation = pg_fetch_assoc($result_plan)){
					if($row_plantation['geom_json']!=null) { $mark = '<i class="fa fa-map-marker pull-right"></i>'; } 
					if(($row_plantation['coordx']!=null)AND($row_plantation['coordy']!=null)) { $pin = '<i class="fa fa-map-pin pull-right"></i></i>'; } 
				}
				
				$contact_name = $arr['name'];  
				$contact_list .= '<li><a href="javascript:showPlantations(\''. $arr['id_contact'] .'\');" class="geo_contact_name">
				'. $arr['id_contact'] .'-'. htmlentities($contact_name, ENT_QUOTES). $mark .'&nbsp;'. $pin .' 
						<div style="color:#aaa; font-size:12px; width:60%;">('. $arr['contact_code'].')</div>
						<div style="color:#aaa; font-size:10px; width:60%;">'. $arr['town_name'] .'</div>
					</a>
				</li>';
			}

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

			$dom = $contact_list.'**'.json_encode($geojson_plantation['features']);

		break;
		
		
		case "show_plantations_geom_json":
		
			$id_plantation = $_GET["id_plantation"];
		
			$sql="SELECT geom_json FROM public.plantation WHERE id_plantation=$id_plantation";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			$dom = $arr['geom_json'];
			
		break;
		
		
		case "show_plantations_collection_point":
		
			$id_plantation = $_GET["id_plantation"];
		
			$sql="SELECT coordx, coordy FROM public.plantation WHERE id_plantation=$id_plantation";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			$dom = $arr['coordx'].'##'.$arr['coordy'];
			
		break;
		
		
		case "save_farmer_plantation":
		
			$id_plantation = $_GET["id_plantation"];
			$area_val = $_GET["area_acres"];
			
			$geom_json = $_GET["geom_json"];
			
			$modified_by = $_SESSION['id_contact'];
			$modified_date = gmdate("Y/m/d H:i");

			// $area_acres = $area_val*0.000247105381;  
			// $area = $area_val*0.0001;
			
			
			$area = $area_val;
			$area_acres = $area * 0.000247105381;
			$surface_ha = $area * 0.0001;
	
			$sql_stats = "UPDATE public.plantation
			   SET area_acres='$area_acres', area='$area', surface_ha='$surface_ha', geom_json='$geom_json', 
			   modified_by='$modified_by', modified_date='$modified_date'
			WHERE id_plantation=$id_plantation";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
		
  		break;
		
		
		case "save_farmer_collection_point":
		
			$id_plantation = $_GET["id_plantation"];
			$coordx = $_GET["coordx"];
			$coordy = $_GET["coordy"];
			
			$modified_by = $_SESSION['id_user'];
			$modified_date = gmdate("Y/m/d H:i");

			$sql_stats = "UPDATE public.plantation
			   SET coordx='$coordx', coordy='$coordy', 
			   modified_by='$modified_by', modified_date='$modified_date'
			WHERE id_plantation=$id_plantation";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
		
  		break;
		
		
		case "show_all_plantation_lines":
			
			$id_company = $_SESSION['id_company'];
	
			$sql="SELECT plant_line_id, id_plantation, id_region, geom_json FROM public.plantation_lines WHERE id_company = $id_company AND geom_json IS NOT NULL";
			$result = pg_query($conn, $sql);
		
			$geojson = array('type' => 'FeatureCollection', 'features' => array());
			
			while($row = pg_fetch_assoc($result)){
				$properties = $row;

				$feature = array(
					'type' => 'Feature',
					'geometry' => json_decode($row['geom_json']),
					'properties' => $properties
				);

				array_push($geojson['features'], $feature);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson['features']);
		
		break;
		
		
		case "selected_plantation_lines":
		
			$plant_line_id = $_GET["plant_line_id"];
		
			$sql="SELECT plant_line_id, id_plantation, id_region, geom_json FROM public.plantation_lines WHERE plant_line_id = $plant_line_id";
			$result = pg_query($conn, $sql);
			
			$arr = pg_fetch_assoc($result);

			$dom = $arr['id_region'].'##'.$arr['geom_json'].'##'.$arr['id_plantation'];
		
		break;
		
		
		case "update_plantation_lines_id_region":
		
			$plant_line_id = $_GET["plant_line_id"];
			
			if(!empty($_GET["id_plantation"])){
				$id_plantation = $_GET["id_plantation"];
				$req_id_plantation = "id_plantation='$id_plantation', "; 
			} else { $req_id_plantation = ""; }
			
			if(!empty($_GET["id_region"])){
				$id_region = $_GET["id_region"];
				$req_id_region = "id_region='$id_region', "; 
			} else { $req_id_region = ""; }
		
			$modified_by = $_SESSION['id_contact'];
			$modified_date = gmdate("Y/m/d H:i");
			
			$sql_stats = "UPDATE public.plantation_lines SET 
				$req_id_region $req_id_plantation
				modified_date='$modified_date', modified_by='$modified_by'
			WHERE plant_line_id=$plant_line_id";

			$result = pg_query($conn, $sql_stats);

			if($result){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "delete_plantation_lines":
		
			$plant_line_id = $_GET["plant_line_id"];
			
			$sql="DELETE FROM plantation_lines WHERE plant_line_id=$plant_line_id";
			$result = pg_query($conn, $sql);
			
			if($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "show_plantation_lines":
			
			$plant_line_id = $_GET["plant_line_id"];
		
			$sql="SELECT plant_line_id, id_plantation, id_region, geom_json FROM public.plantation_lines WHERE plant_line_id=$plant_line_id";
			$result = pg_query($conn, $sql);
		
			$geojson = array('type' => 'FeatureCollection', 'features' => array());
			
			while($row = pg_fetch_assoc($result)){
				$properties = $row;

				$feature = array(
					'type' => 'Feature',
					'geometry' => json_decode($row['geom_json']),
					'properties' => $properties
				);

				array_push($geojson['features'], $feature);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson['features']);
		
		break;
		
		
		case "save_plantation_line":
		
			$plant_line_id = $_GET["plant_line_id"];
			$geom_json = $_GET["geom_json"];
			
			$modified_by = $_SESSION['id_user'];
			$modified_date = gmdate("Y/m/d H:i");

			$sql_stats = "UPDATE public.plantation_lines
			   SET geom_json='$geom_json', 
			   modified_by='$modified_by', modified_date='$modified_date'
			WHERE plant_line_id=$plant_line_id";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
		
  		break;
		
		
		case "add_plantation_line":
		
			$geom_json = $_GET["geom_json"];
			
			$id_company = $_SESSION['id_company'];
			$id_agent = $_SESSION['id_contact'];
			
			$created_by = $_SESSION['id_user'];
			$created_date = gmdate("Y/m/d H:i");

			$sql_stats = "INSERT INTO public.plantation_lines(plant_line_id, geom_json, created_by, created_date, id_company, id_agent) 
				VALUES ($id_agent*10000+to_char(now(),'MMDDHHMISS')::integer, '$geom_json', $created_by, '$created_date', $id_company, $id_agent)";

			$result = pg_query($conn, $sql_stats);

			if($result){
				$dom=1;
			} else {
				$dom=0;
			}
		
  		break;
	}
	
}

echo $dom;