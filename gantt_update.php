<?php

	$conn = pg_connect("host=185.142.213.227 port=5433 dbname=icollect_dev user=icollect password=icollect");
	
	$sql_stats = "SELECT id_task, task_titleshort, 
	to_char(start_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS start_date,
	to_char(due_date::timestamp with time zone, 'dd-mm-yyyy'::text) AS end_date,
	(due_date - start_date) as duration
	FROM project_task ORDER BY id_task ASC";
	$result = pg_query($conn, $sql_stats);
	while($arr = pg_fetch_assoc($result)){

		$sql_sales = "UPDATE public.project_task
			 SET gantt_start_date='". $arr['start_date'] ."', 
			 gantt_end_date='". $arr['end_date'] ."', 
			 duration='". $arr['duration'] ."'
		WHERE id_task=". $arr['id_task'];
	
		
		$result_sales = pg_query($conn, $sql_sales) or die(pg_last_error());
		if($result_sales){
			$count = pg_num_rows($result_sales);

			if($count==0){
				echo 'updated '. $arr['id_task'] .'<br/>';
			} else {
				echo 'Not updated '. $arr['id_task'] .'<br/>';
			}
		} else {
			echo 'Error : '.$sql_sales .' <br/>';
		}
	}