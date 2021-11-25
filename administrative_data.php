<?php

session_start();
error_reporting(0);

include_once("fcts.php");
include_once("common.php");

define('ID_USER', 'noreply.idiscover');
define('ID_PASS', 'Qwerty@1234');

require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
header("Content-type: image/png");
	
	
if (isset($_GET["elemid"])) {

    $elemid = $_GET["elemid"];
    $conn=connect();

	if(!$conn) {
		header("Location: error_db.php");
	}

    $dom='';

	switch ($elemid) {
		
		case "zones":
			
			if($_GET['id_project']!=0){
				$id_project = $_GET['id_project'];
				$cond=" AND id_project=$id_project";
			} else {
				$cond="";
			}
	
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION["id_supchain_type"];

			if($id_supchain_type == 228){
				$sql = "SELECT 
				  DISTINCT v_project.project_name, 
				  v_project.id_project, 
				  v_project.project_type, 
				  v_project.project_type_name, 
				  v_project_tasks.task_owner_id,
				  v_project_tasks.task_delegated_id,
				  v_project.region_quadrant,
				  v_project.zone_color
				FROM 
				  public.v_project, 
				  public.v_project_tasks
				WHERE 
				  v_project.id_project = v_project_tasks.id_project
				 AND v_project.project_type=490
				 AND v_project_tasks.task_delegated_id=$id_company
				 AND v_project.region_quadrant IS NOT NULL
				 $cond
				ORDER BY v_project.id_project DESC";
				
			} else
			if($id_supchain_type == 331){
				$sql = "SELECT DISTINCT project_name, project_type_name, id_project, region_quadrant, zone_color
					FROM v_project 
				WHERE project_type=490 
				 AND cooperative_id=$id_company 
				 AND region_quadrant IS NOT NULL
				 $cond
				ORDER BY id_project DESC";
				
			} else {
				$sql = "SELECT DISTINCT project_name, project_type_name, id_project, region_quadrant, zone_color
					FROM v_project 
				WHERE project_type=490 
				 AND id_company=$id_company  
				 AND region_quadrant IS NOT NULL
				 $cond
				ORDER BY id_project DESC";
			}
			
			$result = pg_query($conn, $sql);

			$geojson = array('type' => 'FeatureCollection', 'features' => array());
			
			while($row = pg_fetch_assoc($result)){
				$properties = $row;

				$feature = array(
					'type' => 'Feature',
					'geometry' => json_decode($row['region_quadrant']),
					'properties' => $properties
				);

				array_push($geojson['features'], $feature);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson['features']);
			
		break;
		
		
		case "regions":
		
			$id_country = $_SESSION['id_country'];
		
			$sql="SELECT *, public.ST_AsGeoJSON(geom,6) AS geojson FROM regions WHERE geom IS NOT NULL AND id_country=$id_country";
			$result = pg_query($conn, $sql);

			$geojson = array('type' => 'FeatureCollection', 'features' => array());		

			while ($arr = pg_fetch_assoc($result)) {
				$properties = $arr;

				unset($properties['geojson']);
				unset($properties['geom']);

				$feature = array(
					'type' => 'Feature',
					'geometry' => json_decode($arr['geojson'], true),
					'properties' => $properties
				);

				array_push($geojson['features'], $feature);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson['features'], JSON_NUMERIC_CHECK);  
		
		break;
		
		
		case "departements":
		
			$id_country = $_SESSION['id_country'];
		
			$sql="SELECT *, public.ST_AsGeoJSON(geom,6) AS geojson FROM departments WHERE geom IS NOT NULL AND id_country=$id_country";
			$result = pg_query($conn, $sql);

			$geojson = array('type' => 'FeatureCollection', 'features' => array());		

			while ($arr = pg_fetch_assoc($result)) {
				$properties = $arr;

				unset($properties['geojson']);
				unset($properties['geom']);

				$feature = array(
					'type' => 'Feature',
					'geometry' => json_decode($arr['geojson'], true),
					'properties' => $properties
				);

				array_push($geojson['features'], $feature);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson['features'], JSON_NUMERIC_CHECK); 
			
		break;
		
		
		case "sous_prefectures":
		
			$id_country = $_SESSION['id_country'];
		
			$sql="SELECT *, public.ST_AsGeoJSON(geom,6) AS geojson FROM subprefectures WHERE geom IS NOT NULL AND id_country=$id_country";
			$result = pg_query($conn, $sql);

			$geojson = array('type' => 'FeatureCollection', 'features' => array());		

			while ($arr = pg_fetch_assoc($result)) {
				$properties = $arr;

				unset($properties['geojson']);
				unset($properties['geom']);

				$feature = array(
					'type' => 'Feature',
					'geometry' => json_decode($arr['geojson'], true),
					'properties' => $properties
				);

				array_push($geojson['features'], $feature);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson['features'], JSON_NUMERIC_CHECK); 
			
		break;
		
		
		case "towns":

			$id_country = $_SESSION['id_country'];
			$id_primary_company = $_SESSION['id_primary_company'];
			
			$coords="";
			if($id_primary_company == 636) {
				$sql = "SELECT DISTINCT
				 (p.id_town),
				 p.name_town,
				 t.x, t.y
				FROM
				 plantation p, towns t
				WHERE
				 p.id_contact in(
				  SELECT
				   ct.id_contracting_party FROM contract ct
				  WHERE (ct.id_contractor = 645
				   OR ct.id_contractor = 646
				   OR ct.id_contractor = 647)) 
				  AND t.id_town = p.id_town 
				  AND t.x IS NOT NULL
				";
			} else {
				$sql="SELECT DISTINCT
				 (p.id_town),
				 p.name_town,
				 t.x, t.y
				FROM
				 plantation p, towns t
				WHERE
				 p.id_contact in(
				  SELECT
				   ct.id_contracting_party FROM contract ct
				  WHERE (ct.id_contractor = $id_primary_company)
				  AND ct.id_contract_type = 499)
				  AND t.id_town = p.id_town 
				  AND t.x IS NOT NULL
				";
			}
			// else {
				// $sql = "SELECT name_town, x, y FROM towns WHERE x IS NOT NULL AND id_country = $id_country";
			// }
			
			$result = pg_query($conn, $sql);
			
			while($row = pg_fetch_assoc($result)){
				$coords .= $row['name_town'].'##'.$row['x'].'##'.$row['y'].'@@';
			}
			
			$coords .= 'end';
			
			$dom=$coords;
		
		break;
		
		
		case "infrastructures":
			
			$id_company = $_SESSION['id_company'];
			
			$coords="";
			$sql = "SELECT infrastructure_type, coordx, coordy, description1 FROM infrastructure WHERE coordx IS NOT NUL AND id_proj_company = $id_company";
			$result = pg_query($conn, $sql);
			
			while($row = pg_fetch_assoc($result)){
				$coords .= getRegvalues($row['infrastructure_type'], $lang['DB_LANG_stat']).'##'.$row['coordx'].'##'.$row['coordy'].'##'.$row['description1'].'@@';
			}
			
			$coords .= 'end';
			
			$dom=$coords;
		
		break;

	}

}


echo $dom;


?>
