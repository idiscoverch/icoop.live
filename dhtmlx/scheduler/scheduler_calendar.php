<?php

include('connector/scheduler_connector.php'); 
include('connector/options_connector.php'); 
require("connector/db_postgre.php");


$dbtype="Postgre"; 

$postrgre_connection = "host=185.142.213.227 port=5433 dbname=icollect_dev user=icollect password=icollect";
$res=pg_connect($postrgre_connection);  	
	

$scheduler = new schedulerConnector($res, $dbtype);  
$scheduler->enable_log("log.txt");

$scheduler->sql->attach("Update","Update project_task set planned_start_date='{planned_start_date}', planned_end_date='{planned_end_date}', task_titleshort='{task_titleshort}' where id_task={id_task}");	
$scheduler->render_sql("SELECT id_task, task_titleshort, planned_start_date, planned_end_date, duration FROM project_task WHERE planned_start_date IS NOT NULL ORDER BY id_task DESC","id_task","planned_start_date,planned_end_date,task_titleshort");

