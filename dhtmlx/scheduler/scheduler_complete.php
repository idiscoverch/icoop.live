<?php

include('connector/scheduler_connector.php'); 
include('connector/options_connector.php'); 
require("connector/db_postgre.php");


$dbtype="Postgre"; 

$postrgre_connection = "host=185.142.213.227 port=5433 dbname=icollect_dev user=icollect password=icollect";
$res=pg_connect($postrgre_connection);  	

$scheduler = new schedulerConnector($res, $dbtype);  
$scheduler->render_sql("SELECT id_task, task_titleshort, agent_id, planned_start_date, planned_end_date FROM project_task WHERE planned_start_date IS NOT NULL","id_task","planned_start_date,planned_end_date,task_titleshort,agent_id");

