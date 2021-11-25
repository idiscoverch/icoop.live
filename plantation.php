<?php

include_once("fcts.php");
include_once("common.php");

header("Content-type: image/png");  


if (isset($_GET["id"])) {

    $id = $_GET["id"];
    $conn=connect();

    $dom='';


	// JSON Plantation
	// $sql_plant = "SELECT *,public.ST_AsGeoJSON(geom,6) AS geojson FROM v_plantation WHERE id_farmer ='$id'";  
	// $result_plant = pg_query($conn, $sql_plant);

	// $geojson_plantation = array(
		// 'type'      => 'FeatureCollection',
		// 'features'  => array()
	// );

	// while ($row_plantation = pg_fetch_assoc($result_plant)) { 
		// $properties_plantation = $row_plantation;
		// unset($properties_plantation['geojson']);
		// unset($properties_plantation['geom']);

		// $feature_plantation = array(
			// 'type' => 'Feature',
			// 'geometry' => json_decode($row_plantation['geojson'], true),
			// 'properties' => $properties_plantation
		// );
		// array_push($geojson_plantation['features'], $feature_plantation);		
	// } 

	
	// $dom=json_encode($geojson_plantation['features'], JSON_NUMERIC_CHECK);
	
	
	
	$sql_plan = "SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture,
		id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
		code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
		cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact
	FROM public.v_plantation WHERE id_farmer = $id";
	
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

}


echo $dom;

