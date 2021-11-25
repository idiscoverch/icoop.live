<?php

include('connector/scheduler_connector.php'); 
include('connector/options_connector.php'); 
require("connector/db_postgre.php");


if(isset($_GET['id_project']) && $_GET['id_project']!=0){
	$id_project = $_GET['id_project'];
	$cond = " AND id_project=$id_project";
} else {
	$cond = "";
}

$dbtype="Postgre"; 

$postrgre_connection = "host=185.142.213.227 port=5433 dbname=icollect_dev user=icollect password=icollect";
$res=pg_connect($postrgre_connection);  	
	
if(isset($_GET['agent_id']) && $_GET['agent_id']!=0){
	$agent_id=$_GET['agent_id'];
	
	$list = new OptionsConnector($res, $dbtype); 
	$list->render_complex_sql("SELECT DISTINCT agent_id AS value, id_project, agent_name AS label FROM v_project_tasks WHERE id_project = $id_project AND agent_id IS NOT NULL AND agent_id=$agent_id","value","value,label");

	$scheduler = new schedulerConnector($res, $dbtype);  
	  
	$scheduler->set_options("sections", $list);  
	$scheduler->render_sql("SELECT id_task, task_titleshort, agent_id, planned_start_date, planned_end_date FROM project_task WHERE planned_start_date IS NOT NULL $cond AND agent_id=$agent_id","id_task","planned_start_date,planned_end_date,task_titleshort,agent_id");

} else {
	$list = new OptionsConnector($res, $dbtype); 
	$list->render_complex_sql("SELECT DISTINCT agent_id AS value, id_project, agent_name AS label FROM v_project_tasks WHERE id_project = $id_project AND agent_id IS NOT NULL","value","value,label");

	$scheduler = new schedulerConnector($res, $dbtype);  
	  
	$scheduler->set_options("sections", $list);  
	$scheduler->render_sql("SELECT id_task, task_titleshort, agent_id, planned_start_date, planned_end_date FROM project_task WHERE planned_start_date IS NOT NULL $cond","id_task","planned_start_date,planned_end_date,task_titleshort,agent_id");

}




// $list = new OptionsConnector($res, $dbtype); 
// $list->render_complex_sql("SELECT project.id_project AS value, project.project_name AS label FROM project, users WHERE users.id_user = project.id_user AND project_type=490 AND users.id_contact=$agent_id","value","value,label");

// $scheduler = new schedulerConnector($res, $dbtype);  
  
// $scheduler->set_options("sections", $list);  
// $scheduler->render_sql("SELECT id_task, task_titleshort, id_project, planned_start_date, planned_end_date FROM project_task WHERE planned_start_date IS NOT NULL AND agent_id=$agent_id","id_task","planned_start_date,planned_end_date,task_titleshort,id_project");

