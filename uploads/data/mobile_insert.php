<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once("../../fcts.php");

$conn=connect();

$queries = file_get_contents('2020-07-29_07.43.41_mobcrmticker.sql');

echo $queries;
pg_query($conn, $queries);

?>
