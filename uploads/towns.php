<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


include_once("../fcts.php");

$conn=connect();

$id_country = $_GET['id_country'];
unlink('towns.sql');

$txt ="";
$sql = "SELECT * FROM towns WHERE id_country = $id_country";
$result = pg_query($conn, $sql);

while($row = pg_fetch_assoc($result)){
	$monfichier = fopen('towns.sql', 'w');
	
	$name_town = trim(pg_escape_string($row["name_town"]));
	$region1 = trim(pg_escape_string($row["region1"]));
	$region2 = trim(pg_escape_string($row["region2"]));
	$region3 = trim(pg_escape_string($row["region3"]));
	
	
	$txt .= PHP_EOL . "INSERT or IGNORE INTO towns(gid_town, name_town, region1, region2, region3) VALUES (".$row['gid_town'].", '{$name_town}', '{$region1}', '{$region2}', '{$region3}');";
	// fwrite($monfichier, $txt);	
	fputs($monfichier, $txt);
	
	// echo $txt.'<br/>';
}

fclose($monfichier);

?>
