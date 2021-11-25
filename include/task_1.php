<?php

session_start();
error_reporting(0);

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

			$filtre = $_GET['filtre'];
			if($filtre == ""){
				$cond = "";
			} else {
				$cond = " AND task_titleshort like '%$filtre%'";
			}
			
			// Gantt
			$gantt='';

			$sql_gantt = "SELECT task_titleshort, from_text, duration 
				FROM v_project_tasks 
			WHERE from_text IS NOT NULL $cond";
			
			
			$result_gantt = pg_query($conn, $sql_gantt);
			while($arr_gantt = pg_fetch_assoc($result_gantt)){
				$gantt.= $arr_gantt['task_titleshort'].'|'.$arr_gantt['from_text'].'|'.$arr_gantt['duration'].'??';
			}
			
			$dom=$gantt;
			
		break;
	}
	
}

echo $dom;