<?php

include('connector/gantt_connector.php'); 
include('connector/options_connector.php'); 
require("connector/db_postgre.php");


$dbtype="Postgre"; 

$postrgre_connection = "host=185.142.213.227 port=5433 dbname=icollect_dev user=icollect password=icollect";
$res=pg_connect($postrgre_connection);  	

if(isset($_GET['agent_id']) && $_GET['agent_id']!=0 && $_GET['agent_id']!=""){
	$agent_id = $_GET['agent_id'];
	$cond = " AND agent_id=$agent_id";
} else {
	$cond = "";
}

$gantt = new JSONGanttConnector($res, $dbtype);
$gantt->enable_log("log.txt");

$gantt->sql->attach("Update","Update project_task set planned_start_date=to_date('{planned_start_date}', 'DD MM YYYY'), planned_end_date=to_date('{end_date}', 'DD MM YYYY'), task_titleshort='{task_titleshort}' where id_task={id_task}");
$gantt->render_sql("SELECT id_task, task_titleshort, to_char(planned_start_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS planned_start_date, planned_end_date, (planned_end_date - planned_start_date) AS duration FROM project_task WHERE planned_start_date IS NOT NULL $cond ORDER BY id_task DESC","id_task","planned_start_date,planned_end_date,task_titleshort");


