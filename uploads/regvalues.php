<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once("../fcts.php");

$conn=connect();

unlink('regvalues.sql');

$txt ="";
$sql = "SELECT * FROM v_regvalues";
$result = pg_query($conn, $sql);

while($row = pg_fetch_assoc($result)){
	$monfichier = fopen('regvalues.sql', 'w');
	
	$regname = trim(pg_escape_string($row["regname"]));
	$regcode = trim(pg_escape_string($row["regcode"]));
	
	$nvalue = pg_escape_string($row["nvalue"]);
	$cvalue = pg_escape_string($row["cvalue"]);
	$cvaluede = pg_escape_string($row["cvaluede"]);
	$cvaluefr = pg_escape_string($row["cvaluefr"]);
	$cvaluept = pg_escape_string($row["cvaluept"]);
	$cvaluees = pg_escape_string($row["cvaluees"]);
	$dvalue = pg_escape_string($row["dvalue"]);
	
	$txt .= PHP_EOL . "INSERT or IGNORE INTO registervalues(id_regvalue, id_register, regname, regcode, nvalue, cvalue, cvaluede, cvaluefr, cvaluept, cvaluees, dvalue) VALUES (".$row['id_regvalue'].", ".$row['id_register'].", '{$regname}', '{$regcode}', '{$nvalue}', '{$cvalue}', '{$cvaluede}', '{$cvaluefr}', '{$cvaluept}', '{$cvaluees}', '{$dvalue}');";
	// fwrite($monfichier, $txt);	
	fputs($monfichier, $txt);
	
	// echo $txt.'<br/>';
}

fclose($monfichier);

?>
