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
		
		case "template_list":
			
			$sql = "SELECT * FROM public.sur_template ORDER BY description DESC";
			$result = pg_query($conn, $sql);

			$html="";
			while($arr = pg_fetch_assoc($result)){
				$html .= '<li><a href="javascript:showSurvQuestions(\''. $arr['id_survey'] .'\');">
					'. htmlentities($arr['description'], ENT_QUOTES) .' 
					<span class="label label-danger pull-right">'. getRegvalues($arr['survey_type'], $lang['DB_LANG_stat']) .'</span>
					<div style="color:#aaa; font-size:12px;">'. $arr['survey_date'] .'</div>
				</a></li>';
			}
			
			$dom=$html;
		
		break;
	}
	
}

echo $dom;