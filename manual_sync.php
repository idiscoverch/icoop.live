<?php

ini_set('display_errors', TRUE);
error_reporting(-1);

include_once("fcts.php");
$conn=connect();

$queries = file_get_contents("uploads/data/15048_2021-05-03_12.07.10_mtk.sql");
pg_query($conn, $queries);