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
		
		case "project_calendar":
		
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION["id_supchain_type"];
			$id_primary_company = $_SESSION["id_primary_company"];
			
			if($id_supchain_type == 228){
				$case="AND task_delegated_id=$id_company";
			} else 
			if($id_supchain_type == 331){
				$case="AND id_cooperative=$id_company"; 
			} else { 
				if($id_primary_company == 636) {
					$case="AND id_company IN (645, 646, 647)"; 
				} else {
					$case="AND id_company=$id_company"; 
				}
			}
			
			if(isset($_GET['agent_id'])){
				$agent_id = $_GET['agent_id'];
				$cond = "AND agent_id = $agent_id";
			} else { $cond = ""; $agent_id = ""; }
			
			$sql = "SELECT id_task, task_titleshort, task_description,
				to_char(planned_start_date::timestamp with time zone, 'yyyy-mm-dd HH:MM:SS'::text) AS planned_start_date,
				to_char(planned_end_date::timestamp with time zone, 'yyyy-mm-dd HH:MM:SS'::text) AS planned_end_date
			FROM v_project_tasks WHERE planned_start_date IS NOT NULL $case $cond";
			
			// $sql = "SELECT id_task, task_titleshort, task_description, gantt_start_date, gantt_end_date
			// FROM project_task WHERE start_date IS NOT NULL AND task_delegated_id=$id_company";
			$result = pg_query($conn, $sql);
			
			$sched="";
			while($row = pg_fetch_assoc($result)){
				$sched.= $row['id_task'].'|'.$row['task_titleshort'].'|'.$row['planned_start_date'].'|'.$row['planned_end_date'].'|'.$row['task_description'].'??';
			}
			
			$dhtmlx='<div style="float: left; padding:10px;">
				<div id="cal_here" style="width:250px;"></div>
			</div>
			<div id="scheduler_calendar" class="dhx_cal_container" style="width:auto; height:100%;">
				<div class="dhx_cal_navline">
					<div class="dhx_cal_prev_button">&nbsp;</div>
					<div class="dhx_cal_next_button">&nbsp;</div>
					<div class="dhx_cal_today_button"></div>
					<div class="dhx_cal_date"></div>
					<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
					<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
					<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
				</div>
				<div class="dhx_cal_header">
				</div>
				<div class="dhx_cal_data">
				</div>
			</div>';
			
			$sql_agents = "SELECT DISTINCT agent_id, agent_name FROM v_project_tasks WHERE agent_id IS NOT NULL $case";
			$agents='<option value="0">-- Filter by agent --</option>';
			$result_agents = pg_query($conn, $sql_agents);
			while($arr_agents = pg_fetch_assoc($result_agents)){
				if($agent_id == $arr_agents['agent_id']){ $selected_agent="selected"; } else { $selected_agent=""; }
				$agents.= '<option value="'.$arr_agents['agent_id'].'" '.$selected_agent.'>'.$arr_agents['agent_name'].'</option>';
			}
			
			$dom=$dhtmlx.'##'.$sched.'##'.$agents;
		
		break;
		
		
		case "project_timeline":
		
			$project='';
			$agent='';
			$task='';
			
			$sql_projects = "SELECT project_name, id_project FROM v_project WHERE project_type=490 ORDER BY id_project DESC";

			$result_projects = pg_query($conn, $sql_projects);
			while($arr_projects = pg_fetch_assoc($result_projects)){
				$project.= $arr_projects['id_project'].'|'.$arr_projects['project_name'].'??';
			}
			$project.='end';
			
			
			$sql_tasks = "SELECT id_task, task_titleshort, id_project, agent_id, planned_start_date, planned_end_date 
				FROM v_project_tasks 
			WHERE planned_start_date IS NOT NULL";
			
			
			$result_tasks = pg_query($conn, $sql_tasks);
			while($arr_tasks = pg_fetch_assoc($result_tasks)){
				$task.= $arr_tasks['id_task'].'|'.$arr_tasks['task_titleshort'].'|'.$arr_tasks['planned_start_date'].'|'.$arr_tasks['planned_end_date'].'|'.$arr_tasks['id_project'].'??';
			}
			$task.='end';
			
			$sql_agents = "SELECT DISTINCT agent_id, id_project, agent_name FROM v_project_tasks WHERE agent_id IS NOT NULL";
			
			
			$result_agents = pg_query($conn, $sql_agents);
			while($arr_agents = pg_fetch_assoc($result_agents)){
				$agent.= $arr_agents['agent_id'].'|'.$arr_agents['id_project'].'|'.$arr_agents['agent_name'].'??';
			}
			$agent.='end';
			
			$dom=$project.'@@'.$task.'@@'.$agent;
		
		break;
	}
	
}

echo $dom;