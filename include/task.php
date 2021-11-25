<?php

session_start();
error_reporting(0);

if(!isset($_SESSION['username'])){
	header("Location: ../login.php");
}


include_once("../fcts.php");
include_once("../common.php");

header("Content-type: image/png");


if (isset($_GET["elemid"])) {

    $elemid = $_GET["elemid"];
    $conn=connect();

	if(!$conn) {
		header("Location: error_db.php");
	}

    $dom='';

	switch ($elemid) {
		
		case "tasks_gantt":

			// $filtre = $_GET['filtre'];
			// if($filtre == ""){
				// $cond = "";
			// } else {
				// $cond = " AND task_titleshort like '%$filtre%'";
			// }
			
			// Gantt
			$project='';
			$task='';
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION["id_supchain_type"];
			$id_primary_company = $_SESSION["id_primary_company"];
			
			if(isset($_GET['agent_id']) AND $_GET['agent_id']!=0){
				$agent_id = $_GET['agent_id'];
				$cond = "AND agent_id = $agent_id";
			} else { $cond = ""; $agent_id = ""; }
			
			if($id_supchain_type == 228){
				$case="task_delegated_id=$id_company";
				
				$sql_projects = "SELECT DISTINCT id_project, project_name,
				to_char(start_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS start_date,
				to_char(due_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS due_date,
				(due_date - start_date) AS duration
				FROM v_project_tasks WHERE task_delegated_id=$id_company $cond ORDER BY id_project DESC";
			
			} if($id_supchain_type == 331){
				$case="id_cooperative=$id_company";
				
				$sql_projects = "SELECT id_project, project_name,
				to_char(start_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS start_date,
				to_char(due_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS due_date,
				(due_date - start_date) AS duration
				FROM v_project WHERE cooperative_id=$id_company ORDER BY id_project DESC";
				
			} else { 
				if($id_primary_company == 636) {
					$case="id_company IN (645, 646, 647)"; 
				} else {
					$case="id_company=$id_company"; 
				}
				
				$sql_projects = "SELECT id_project, project_name,
				to_char(start_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS start_date,
				to_char(due_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS due_date,
				(due_date - start_date) AS duration
				FROM v_project WHERE $case $cond ORDER BY id_project DESC";
			}
			
			// $sql_projects = "SELECT project_name, id_project,
			// to_char(start_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS start_date,
			// to_char(due_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS due_date,
			// (due_date - start_date) AS duration
			// FROM v_project WHERE project_type=490 ORDER BY id_project DESC";
			
			$result_projects = pg_query($conn, $sql_projects);
			while($arr_projects = pg_fetch_assoc($result_projects)){
				$project.= $arr_projects['id_project'].'|'.$arr_projects['project_name'].'|'.$arr_projects['start_date'].'|'.$arr_projects['duration'].'??';
			}
			$project.='end';
			
			
			// $sql_tasks = "SELECT id_task, task_titleshort, id_project, from_text, duration 
				// FROM v_project_tasks 
			// WHERE from_text IS NOT NULL";
			
			$sql_tasks = "SELECT id_task, task_titleshort, 
				to_char(planned_start_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS start_date,
				(planned_end_date - planned_start_date) AS duration, id_project 
			FROM v_project_tasks WHERE start_date IS NOT NULL AND $case $cond";

			$result_tasks = pg_query($conn, $sql_tasks);
			while($arr_tasks = pg_fetch_assoc($result_tasks)){
				$task.= $arr_tasks['id_task'].'|'.$arr_tasks['task_titleshort'].'|'.$arr_tasks['start_date'].'|'.$arr_tasks['duration'].'|'.$arr_tasks['id_project'].'??';
			}
			$task.='end';
			
			
			
			$sql_agents = "SELECT DISTINCT agent_id, agent_name FROM v_project_tasks WHERE agent_id IS NOT NULL AND $case";
			$agents='<option value="0">-- Filter by agent --</option>';
			$result_agents = pg_query($conn, $sql_agents);
			while($arr_agents = pg_fetch_assoc($result_agents)){
				if($agent_id == $arr_agents['agent_id']){ $selected_agent="selected"; } else { $selected_agent=""; }
				$agents.= '<option value="'.$arr_agents['agent_id'].'" '.$selected_agent.'>'.$arr_agents['agent_name'].'</option>';
			}
			
			$dom=$project.'@@'.$task.'@@'.$agents.'@@'.$id_primary_company;
			
		break;
	}
	
}

echo $dom;