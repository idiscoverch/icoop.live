<?php

$conn = new PDO('pgsql:host=localhost;port=5434;dbname=icollect','icollect','icollect');



$sql_exporter = "
	SELECT * FROM
		public.contact_profile,
		public.v_exporters
	WHERE
		contact_profile.id_contact = v_exporters.id_contact
	AND
		contact_profile.company_type = 'EXPORTER'
	AND
		contact_profile.lang = '" . $lang['DB_LANG'] . "'
";


$sql_pays = "SELECT public.ST_AsGeoJSON(geom,6) AS geojson,* FROM country ;";


$sql_city = "SELECT * FROM v_city;";


$sql_buyer = "
	SELECT * FROM v_buyers, contact_profile
	WHERE  contact_profile.id_contact = v_buyers.id_contact AND
		contact_profile.company_type = 'BUYER'
	AND
		contact_profile.lang = '" . $lang['DB_LANG'] . "'
";


// $sql_village = "
	// SELECT farmers.id_farmer, farmers.code_farmer, farmers.name_farmer,farmers.contact,
        // farmers.number_child, farmers.residence, farmers.ethnic_group, farmers.child_educated,
        // farmers.child_n_educated, farmers.study_level, farmers.matrimonial_situation, farmers.id_culture,
        // farmers.id_farmergroup, farmers.name_farmergroup, farmers.sex, farmers.nationality, farmers.id_town,
        // towns.name_town, farmers.number_plantation, farmers.birthday, farmers.id_buyer,  farmers.name_buyer,
        // towns.x, towns.y, towns.timezone, towns.code_town
	// FROM
		// public.v_farmers as farmers,
		// public.towns
	// WHERE
		// towns.gid_town = farmers.id_town ORDER BY farmers.name_farmer;
// ";

$sql_village = "
	SELECT farmers.id_farmer, farmers.code_farmer, farmers.name_farmer,farmers.contact,
  farmers.number_child, farmers.residence, farmers.ethnic_group, farmers.child_educated,
  farmers.child_n_educated, farmers.study_level, farmers.matrimonial_situation, farmers.id_culture,
  farmers.id_farmergroup, farmers.name_farmergroup, farmers.sex, farmers.nationality, farmers.id_town,
  farmers.name_town, farmers.number_plantation, farmers.birthday, farmers.id_buyer,  farmers.name_buyer,
  farmers.x, farmers.y, farmers.timezone, farmers.code_town
 FROM public.v_farmers as farmers
";


// $sql_plantation = "
	// SELECT plantations.gid_plantation, plantations.area, plantations.year_creation, plantations.perimeter, contact.id_contact,
		// plantations.insure, plantations.variety, plantations.code_farmer, plantations.culture, plantations.id_culture,  plantations.gid_town,
		// public.ST_AsGeoJSON(plantations.geom,6) AS geojson, plantations.name_country, plantations.id_country, plantations.statut, plantations.name_manager,
		// plantations.code_parcelle, plantations.estimate_production, plantations.name_town, farmers.name_farmer,
		// farmers.name_farmergroup, farmers.id_farmergroup, farmers.id_town, farmers.name_town, farmers.id_buyer, farmers.name_buyer
	// FROM
		// public.v_farmers as farmers,  public.v_plantation as plantations, public.contact
	// WHERE
		// farmers.code_farmer = plantations.code_farmer
	// AND contact.id_contact = farmers.id_farmer
	// ORDER BY plantations.code_farmer;
// ";


$sql_plantation = "SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture, coordx, coordy,
	id_culture, gid_town, public.ST_AsGeoJSON(plantations.geom,6) AS geojson, name_country, id_country, statut, name_manager,
	code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
	cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact
	FROM public.v_plantation as plantations
";


$sql_stories = "
	SELECT
	  v_exporters.code_country AS code,
	  v_exporters.id_culture,
	  story.media_link,
	  story.media_type,
	  story.story_title".$lang['DB_LANG_stat']." AS title,
	  story.id_story
	FROM
	  public.story,
	  public.v_exporters
	WHERE
	  story.id_exporter = v_exporters.id_exporter;
";

// $monfichier = fopen('test.txt', 'r+');
// ftruncate($monfichier,0);
// fputs($monfichier,$sql_stories);


$sql_step = "SELECT * FROM story_con;";



$rs_exporter = $conn->query($sql_exporter);
$rs_pays = $conn->query($sql_pays);
$rs_city = $conn->query($sql_city);
$rs_buyer = $conn->query($sql_buyer);
$rs_village = $conn->query($sql_village);
$rs_plantation = $conn->query($sql_plantation);
$rs_stories = $conn->query($sql_stories);
$rs_step = $conn->query($sql_step);



$geojson_exporter = array(
	'type'      => 'FeatureCollection',
	'features'  => array()
);


$geojson_pays = array(
	'type'      => 'FeatureCollection',
	'features'  => array()
);


$geojson_city = array(
	'type'      => 'FeatureCollection',
	'features'  => array()
);


$geojson_buyer = array(
	'type'      => 'FeatureCollection',
	'features'  => array()
);


$geojson_village = array(
	'type'      => 'FeatureCollection',
	'features'  => array()
);


$geojson_plantation = array(
	'type'      => 'FeatureCollection',
	'features'  => array()
);


$geojson_stories = array(
	'type'      => 'FeatureCollection',
	'features'  => array()
);


$geojson_step = array(
	'type'      => 'FeatureCollection',
	'features'  => array()
);




$dom_village = '';

while ($row_village = $rs_village->fetch(PDO::FETCH_ASSOC)) {
	$properties_village = $row_village;
	$feature_village = array(
		'type' => 'Feature',
		'properties' => $properties_village
	);
	array_push($geojson_village['features'], $feature_village);
}



$dom_buyer = '';

while ($row_buyer = $rs_buyer->fetch(PDO::FETCH_ASSOC)) {
	$id_buyer = $row_buyer['id_buyer'];
	$properties_buyer = $row_buyer;
	$feature_buyer = array(
		'type' => 'Feature',
		'properties' => $properties_buyer
	);
	array_push($geojson_buyer['features'], $feature_buyer);
}



$dom_city = '';

while ($row_city = $rs_city->fetch(PDO::FETCH_ASSOC)) {
	$id_city = $row_city['id_city'];
	$properties_city = $row_city;
	$feature_city = array(
		'type' => 'Feature',
		'properties' => $properties_city
	);
	array_push($geojson_city['features'], $feature_city);
}



$dom_exporter = '';

while ($row_exporter = $rs_exporter->fetch(PDO::FETCH_ASSOC)) {
	$id_exporter = $row_exporter['id_exporter'];
	$properties_exporter = $row_exporter;
	$feature_exporter = array(
		'type' => 'Feature',
		'properties' => $properties_exporter
	);
	array_push($geojson_exporter['features'], $feature_exporter);
}



$dom_pays = '';

while ($row_pays = $rs_pays->fetch(PDO::FETCH_ASSOC)) {
	$properties_pays = $row_pays;
	unset($properties_pays['geojson']);
	unset($properties_pays['geom']);

	$feature_pays = array(
		'type' => 'Feature',
		'geometry' => json_decode($row_pays['geojson'], true),
		'properties' => $properties_pays
	);
	array_push($geojson_pays['features'], $feature_pays);
}



while ($row_plantation = $rs_plantation->fetch(PDO::FETCH_ASSOC)) {
	$properties_plantation = $row_plantation;
	unset($properties_plantation['geojson']);
	unset($properties_plantation['geom']);

	$feature_plantation = array(
		'type' => 'Feature',
		'geometry' => json_decode($row_plantation['geojson'], true),
		'properties' => $properties_plantation
	);
	array_push($geojson_plantation['features'], $feature_plantation);
}


while ($row_stories = $rs_stories->fetch(PDO::FETCH_ASSOC)) {
	$properties_stories = $row_stories;
	$feature_stories = array(
		'type' => 'Feature',
		'properties' => $properties_stories
	);
	array_push($geojson_stories['features'], $feature_stories);
}



while ($row_step = $rs_step->fetch(PDO::FETCH_ASSOC)) {
	$properties_step = $row_step;
	$feature_step = array(
		'type' => 'Feature',
		'properties' => $properties_step
	);
	array_push($geojson_step['features'], $feature_step);
}


ini_set('memory_limit', '-1');

?>





<script type="text/javascript">

	var json_step = <?php echo json_encode($geojson_step['features'], JSON_NUMERIC_CHECK); ?>;
	var json_stories = <?php echo json_encode($geojson_stories['features'], JSON_NUMERIC_CHECK); ?>;
	var json_exporter = <?php echo json_encode($geojson_exporter['features'], JSON_NUMERIC_CHECK); ?>;
	var json_city = <?php echo json_encode($geojson_city['features'], JSON_NUMERIC_CHECK); ?>;
	var json_buyer = <?php echo json_encode($geojson_buyer['features'], JSON_NUMERIC_CHECK); ?>;
	var json_village = <?php echo json_encode($geojson_village['features'], JSON_NUMERIC_CHECK); ?>;
	var json_pays = <?php echo json_encode($geojson_pays['features'], JSON_NUMERIC_CHECK); ?>;
	var json_plantation = <?php echo json_encode($geojson_plantation['features'], JSON_NUMERIC_CHECK); ?>;

</script>
