<?php

session_start();
error_reporting(0);

if(!isset($_SESSION['username'])){
	header("Location: ../login.php");
}


include_once("../fcts.php");
include_once("../common.php");

header("Content-type: image/png");

if($lang['DB_LANG_stat']=='en') { $field = "cvalue"; } else { $field = "cvalue".$lang['DB_LANG_stat']; }

if (isset($_GET["elemid"])) {

    $elemid = $_GET["elemid"];
    $conn=connect();

	if(!$conn) {
		header("Location: error_db.php");
	}

    $dom='';

	switch ($elemid) {
		
		case "plantation_town_marker":
		
			$id_town = $_GET["id_town"];

			$sql2 = "SELECT id_town, name_town, x, y FROM towns WHERE id_town = $id_town";
			$result2 = pg_query($conn, $sql2);
			$arr2 = pg_fetch_assoc($result2);
			
			$dom = $arr2['name_town'].'##'.$arr2['x'].'##'.$arr2['y'];
			
		break;
		
		
		case "edit_farmer_certification":
		
			$id_plantation=$_GET['id_plantation'];
			$field_name=$_GET['field_name'];
			$new_value=$_GET['new_value'];
			
			$sql = "UPDATE plantation SET $field_name='$new_value' WHERE id_plantation=$id_plantation";
			$result = pg_query($conn, $sql);
			
			if($result) {
				$dom = 1;
			} else {
				$dom = 0;
			}
			
		break;
		
		
		case "get_certification_list": 
		
			$field_value=$_GET['field_value'];
			
			$sql = "SELECT id_regvalue FROM v_regvalues WHERE id_register = 263";

			$values = '<option value="">---</option>';
			$result = pg_query($conn, $sql);
			while($arr = pg_fetch_assoc($result)) {
				if($field_value == $arr['id_regvalue']) { $sel = 'selected'; } else { $sel = ''; }
				$values .= '<option value="'. $arr['id_regvalue'] .'" '.$sel.'>'. getRegvalues($arr['id_regvalue'], $lang['DB_LANG_stat']) .'</option>';
			}
			
			$dom = $values;
		
		break;
		
		
		case "show_plantation_picture":
		
			$id_plantdoc=$_GET['id_plantdoc'];
			
			$sql_plant = "SELECT coordx, coordy, description, doc_type from plantation_docs WHERE id_plantdoc=$id_plantdoc";

			$result_plant = pg_query($conn, $sql_plant);
			$arr_plant = pg_fetch_assoc($result_plant);
			
			$dom=$arr_plant['coordx'].'##'.$arr_plant['coordy'].'##'.$arr_plant['description'].'##'.getRegvalues($arr_plant['doc_type'], $lang['DB_LANG_stat']);
			
		break;
		
		
		case "selected_plantation_manager_details":
		
			$manager="";
			
			$id_plantation=$_GET['id_plantation'];
		
			$sql_plant = "SELECT * FROM v_plantation WHERE gid_plantation=$id_plantation";

			$result_plant = pg_query($conn, $sql_plant);
			$arr_plant = pg_fetch_assoc($result_plant);


				if($arr_plant['id_owner_manager']!=''){
					$act_managed_by = 'class="active"';
					$row_managed_by = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PLANT_MANAGED_BY'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['id_owner_manager'], $lang['DB_LANG_stat']) .'</td></tr>';
				} else{$act_managed_by = ''; $row_managed_by = '';}
				
				if($arr_plant['name_manager']!=''){
					$act_name_manager = 'class="active"';
					$row_name_manager = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NAME_MANAGER'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['name_manager'].'</td></tr>';
				} else{$act_name_manager = ''; $row_name_manager = '';}
				
				if($arr_plant['manager_civil']!=''){
					$act_manager_civil = 'class="active"';
					$row_manager_civil = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MANAGER_CIVIL'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['manager_civil'], $lang['DB_LANG_stat']) .'</td></tr>';
				} else{$act_manager_civil = ''; $row_manager_civil = '';}
				
				if($arr_plant['manager_phone']!=''){
					$act_manager_phone = 'class="active"';
					$row_manager_phone = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MANAGER_PHONE'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['manager_phone'].'</td></tr>';
				} else{$act_manager_phone = ''; $row_manager_phone = '';}
				
				if($arr_plant['year_extension']!=''){
					$act_year_extension = 'class="active"';
					$row_year_extension = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_YEAR_EXTENSION'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. $arr_plant['year_extension'] .'</td></tr>';
				} else{$act_year_extension = ''; $row_year_extension = '';}
				
				if($arr_plant['year_replanting']!=''){
					$act_year_replanting = 'class="active"';
					$row_year_replanting = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_YEAR_REPLANTING'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. $arr_plant['year_replanting'] .'</td></tr>';
				} else{$act_year_replanting = ''; $row_year_replanting = '';}
				
				if(($row_managed_by!="") OR ($row_name_manager!="") OR ($row_manager_civil!="") OR ($row_manager_phone!="") OR ($row_year_extension!="") OR ($row_year_replanting!="")) {
					$sh_manager = '';
				} else { $sh_manager = 'hide'; }
				
				
				if($arr_plant['farmer_experience']!=''){
					$act_farmer_experience = 'class="active"';
					$row_farmer_experience = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_FARMER_EXPERIENCE'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. $arr_plant['farmer_experience'] .'</td></tr>';
				} else{$act_farmer_experience = ''; $row_farmer_experience = '';}
				
				if($arr_plant['number_staff_permanent']!=''){
					$act_number_staff_permanent = 'class="active"';
					$row_number_staff_permanent = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NB_STAFF_PERM'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['number_staff_permanent'].'</td></tr>';
				} else{$act_number_staff_permanent = ''; $row_number_staff_permanent = '';}
				
				if($arr_plant['number_staff_temporary']!=''){
					$act_number_staff_temporary = 'class="active"';
					$row_number_staff_temporary = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NB_STAFF_TEMP'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['number_staff_temporary'].'</td></tr>';
				} else{$act_number_staff_temporary = ''; $row_number_staff_temporary = '';}
				
				if($arr_plant['gender_workers']!=''){
					$act_gender_workers = 'class="active"';
					$row_gender_workers = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GENDER_WORKERS'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['gender_workers'], $lang['DB_LANG_stat']) .'</td></tr>';
				} else{$act_gender_workers = ''; $row_gender_workers = '';}
				
				if($arr_plant['migrant_workers']!=''){
					$act_migrant_workers = 'class="active"';
					$row_migrant_workers = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MIGRANT_WORKERS'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['migrant_workers'], $lang['DB_LANG_stat']) .'</td></tr>';
				} else{$act_migrant_workers = ''; $row_migrant_workers = '';}
				
				if($arr_plant['day_worker_pay']!=''){
					$act_day_worker_pay = 'class="active"';
					$row_day_worker_pay = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_DAY_WORKER_PAY'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. $arr_plant['day_worker_pay'] .'</td></tr>';
				} else{$act_day_worker_pay = ''; $row_day_worker_pay = '';}
				
				if($arr_plant['farmer_experience_level']!=''){
					$act_farmer_experience_level = 'class="active"';
					$row_farmer_experience_level = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_FARMER_EXPERIENCE_LEVEL'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. $arr_plant['farmer_experience_level'] .'</td></tr>';
				} else{$act_farmer_experience_level = ''; $row_farmer_experience_level = '';}
				
				if($arr_plant['children_work']!=''){
					$act_children_work = 'class="active"';
					$row_children_work = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CHILDREN_WORK'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['children_work'], $lang['DB_LANG_stat']) .'</td></tr>';
				} else{$act_children_work = ''; $row_children_work = '';}
				
				
				if(($row_number_staff_permanent!="") OR ($row_number_staff_temporary!="") OR ($row_farmer_experience!="") OR ($row_gender_workers!="") OR ($row_migrant_workers!="") OR ($row_day_worker_pay!="") OR ($row_farmer_experience_level!="") OR ($row_children_work !="")) {
					$sh_staff = '';
				} else { $sh_staff = 'hide'; }
				
				
				
				if($arr_plant['notes']!=''){
					$act_notes = 'class="active"';
					$row_notes = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NOTES'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['notes'].'</td></tr>';
				} else{ $act_notes = ''; $row_notes = '';}
			
				if($row_notes!=""){ $sh_notes = ''; } else { $sh_notes = 'hide'; }
				
			$id = $arr_plant['id_owner_manager'];
			
			$sql_avatar = "SELECT doc_link FROM contact_docs WHERE contact_id = $id 
				AND doc_type = 154  AND id_household IS NULL
			ORDER BY id_condoc DESC LIMIT 1";
			$result_avatar = pg_query($conn, $sql_avatar);
			$arr_avatar = pg_fetch_assoc($result_avatar);
			
			if($arr_avatar['doc_link']) {
				$pieces = explode("upload", $arr_avatar['doc_link']);
				$avatar = $pieces[0].'upload/w_500,h_500,c_crop,g_auto/'.$pieces[1];
			} else {
				if(file_exists('../img/avatar/' . $arr['id_contact'] . ".jpg")) {
					$avatar = 'img/avatar/' . $arr['id_contact'] . ".jpg";
				} else { $avatar = 'img/user.jpg'; }
			}
			
			
			$manager .='<div class="card-wrapper">
				<div id="card-7-modal" class="card-bg hide"></div>
				<div id="card-7" class="card-rotating effect__click">

					<div class="face front">
						<div style="border-bottom:1px solid #e4e4e4;padding:12px 0 4px 0;">
						
						</div>

						<div class="contact-box" style="border:none;">
							<div class="text-center" style="margin-bottom:10px;">
								<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
							</div>
						
							<table style="width:100%; font-size:12px;">
								<tr><td style="width:30%; padding:3px;" align="right" class="bg-success '.$sh_manager.'"></td>
								<td style="padding:3px 3px 3px 10px;" class="bg-success '.$sh_manager.'">'.$lang['CONT_PLANT_MANAGEMENT'].'</td></tr>
								<tr><td colspan="2" class="'.$sh_manager.'"><div style="height:5px;"></div></td></tr>
							
								'.$row_managed_by.$row_name_manager.$row_year_extension.$row_year_replanting.$row_manager_civil.$row_manager_phone.'
							</table> 
							
							<table style="width:100%; font-size:12px;">
								<tr><td style="width:30%; padding:3px;" align="right" class="bg-success '.$sh_staff.'"></td>
								<td style="padding:3px 3px 3px 10px;" class="bg-success '.$sh_staff.'">'.$lang['CONT_PLANT_WORKERS'].'</td></tr>
								<tr><td colspan="2" class="'.$sh_staff.'"><div style="height:5px;"></div></td></tr>
							
								'.$row_farmer_experience.$row_number_staff_permanent.$row_number_staff_temporary.$row_farmer_experience_level.$row_gender_workers.$row_migrant_workers.$row_day_worker_pay.$row_children_work.'
							</table> 
					
							<table style="width:100%; font-size:12px;">
								<tr><td style="width:30%; padding:3px;" align="right" class="bg-success '.$sh_notes.'"></td>
								<td style="padding:3px 3px 3px 10px;" class="bg-success '.$sh_notes.'">'.$lang['CONT_PLANT_NOTES'].'</td></tr>
								<tr><td colspan="2" class="'.$sh_notes.'"><div style="height:5px;"></div></td></tr>
							
								'.$row_notes.'
							</table> 

							<div class="clearfix"></div>
						</div>
					</div>

					<div class="face back hide animated front_face" id="edit_plantation_manager">
						<div class="card-block">
							
						</div>
					</div>
				</div>
			</div>';
			
			
			$id_manager = $arr_plant['id_manager'];
			
			$sql_stats = "SELECT nr_household_members, number_children, lastname, firstname FROM v_icw_contacts WHERE id_contact ='$id_manager'";

			$result = pg_query($conn, $sql_stats);
			$arr = pg_fetch_assoc($result);
			
			
			
			$householdList = '';
			$sql_householdList = "SELECT id_household, avatar_path, contact_id, firstname, lastname, birth_year, id_relation FROM v_contact_household WHERE contact_id='$id_manager'";
			$result_householdList = pg_query($conn, $sql_householdList);
			
			$g=0;
			$id_household="";
			while($arr_householdList = pg_fetch_assoc($result_householdList)){
				if($g==0){ $id_household=$arr_householdList['id_household']; $hhLclass="on9"; } else { $hhLclass=""; }
				
				if($arr_householdList['avatar_path']!="") { $img_link = $arr_householdList['avatar_path']; }
				else { $img_link = './img/household.png'; }
				
				$age = date('Y') - $arr_householdList['birth_year'];
			
				$householdList .= '<li style="padding:5px 0 5px 0; cursor:pointer;" class="'.$hhLclass.'" onclick="showContactHouseholdDetails(\''. $arr_householdList['id_household'] .'\',\'plantation\');">
					<div class="row">
						<div class="col-md-2">
							<img src="'. $img_link .'" class="img-thumbnail" width="50" />
						</div>
						
						<div class="col-md-10">
							'. $arr_householdList['firstname'] .' '. $arr_householdList['lastname'] .'<br/>
							<span style="color:#b0b0b0;">
								'. getRegvalues($arr_householdList['id_relation'], $lang['DB_LANG_stat']) .' ('. $age .' Years old)
							</span>
						</div>
					</div>
				</li>';
		
				$g++;
			}
		
			$household = '<div class="contact-box">
						<table style="width:100%; font-size:12px;">
							<tr>
								<td style="width:40%;" align="right"><label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NB_HOUSEHOLD_MB'].' : </label></td>
								<td align="left" style="padding-left:10px;">'.$arr['nr_household_members'].'</td>
							</tr>
							
							<tr>
								<td style="width:40%;" align="right"><label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NB_CHILDREN'].' : </label></td>
								<td align="left" style="padding-left:10px;">'.$arr['number_children'].'</td>
							</tr>
						</table>
					</div>
					
					<ul class="folder-list m-b-md list" id="manager_household_list_content" style="padding: 0;">
						'. $householdList .'
					</ul>
				';
			

			$survey_ques_ansList = '';
			$sql_survey = "select * from v_survey_answers where id_contact = $id_manager ORDER BY id_surq DESC";
			$result_survey = pg_query($conn, $sql_survey);
			
			while($arr_survey = pg_fetch_assoc($result_survey)){
				if($lang['DB_LANG_stat'] == 'fr'){ $reponse=$arr_survey['ans_text_fr']; } else { $reponse=$arr_survey['ans_text_en']; }
				$survey_ques_ansList .= '<li style="padding: 5px 0;"><div style="color:#aaa; font-size:11px;">'. $arr_survey['q_text'] .'</div>'. htmlentities($reponse, ENT_QUOTES) .' </li>';
				$cardTitle = $arr_survey['description'];
			}
		

			$demography = '<div class="row">
			<div class="col-md-6">
				<div class="card-wrapper">
					<div id="card-9-modal" class="card-bg hide"></div>
					<div id="card-9" class="card-rotating effect__click">

					<div class="face front">
						<div class="contact-box" style="border:none;">
							<table style="width:100%; font-size:12px;">
								<tr>
									<td style="width:40%;" align="right"><label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NAME'].' : </label></td>
									<td align="left" style="padding-left:10px;">'.$arr['lastname'].' '.$arr['firstname'].'</td>
								</tr>
							</table>
						
							<h4 class="text-center">'.$cardTitle.'</h4>
							<ul class="folder-list m-b-md list" style="padding: 5px 0 0 0;">
								'.$survey_ques_ansList.'
							</ul>

							<div class="clearfix"></div>
						</div>
					</div>

					<div class="face back hide animated front_face" id="edit_demog">
						<div class="card-block">
							<div class="pull-left" style="border-bottom:1px solid #e4e4e4;padding:4px 0 10px 0; margin-bottom:15px;width:100%;">
								<a class="rotate-btn pull-left" onclick="updateDemog();" data-card="card-3"><i class="fa fa-save"></i></a>
								<a class="rotate-btn pull-right" style="color:red;" data-card="card-3" onclick="CancelEditDemog();"><i class="fa fa-ban"></i></a>
							</div>

						</div>
					</div>

				</div></div>
			</div>
			
			<div class="col-md-6">
				<div class="text-center">
					<img src="https://www.poverty-action.org/sites/default/files/poverty_action_logo.jpg" class="img-responsive text-center" />
					<br/>
					<a href="https://www.poverty-action.org" target="_blank">https://www.poverty-action.org</a>
				</div>
			</div>
			</div>';
	
			$dom = $manager.'##'.$household.'##'.$demography.'##'.$id_household;
		
		break;
		
		
		case "delete_plantation_document":
		
			$id_plantdoc = $_GET["id_plantdoc"];
			
			$sql="DELETE FROM plantation_docs WHERE id_plantdoc = $id_plantdoc";
			$result = pg_query($conn, $sql);
			if($result){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "delete_contact_document":
		
			$id_condoc = $_GET["id_condoc"];
			
			$sql="DELETE FROM contact_docs WHERE id_condoc = $id_condoc";
			$result = pg_query($conn, $sql);
			if($result){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "update_plantation_special":
		
			$id_plantation = $_GET["id_plantation"];
			
			if(isset($_GET["code_plantation"])){
				$code_plantation = pg_escape_string($_GET["code_plantation"]);
				$req_code_plantation = "code_plantation='$code_plantation', "; 
			} else { $req_code_plantation = ""; }
			
			if(isset($_GET["notes"])){
				$notes = pg_escape_string($_GET["notes"]);
				$req_notes = "notes='{$notes}', "; 
			} else { $req_notes = ""; }
			
			if(isset($_GET["year_creation"])){
				$year_creation = $_GET["year_creation"];
				$req_year_creation = "year_creation='$year_creation', "; 
			} else { $req_year_creation = ""; }
			
			$modified_by = $_SESSION['id_user'];
			$modified_date = gmdate("Y/m/d H:i");
			
			$sql_stats = "UPDATE public.plantation
			   SET $req_code_plantation $req_notes $req_year_creation
				modified_date='$modified_date', modified_by='$modified_by'
			WHERE id_plantation ='$id_plantation'";

			$result = pg_query($conn, $sql_stats);
			if($result){
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
	
		case "show_plantation_special":
		
			$id_plantation = $_GET["id_plantation"];
			
			$sql = "SELECT code_plantation, notes, year_creation FROM plantation WHERE id_plantation = $id_plantation";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			$dom = $arr['code_plantation'].'##'.$arr['notes'].'##'.$arr['year_creation'];
		
		break;
		
		
		case "wharehouse": 
		
			$id_contact = $_GET["id_contact"];
			
			$sql = "SELECT storage_coordx, storage_coordy FROM v_plantation WHERE id_contact = $id_contact";
			$result = pg_query($conn, $sql);
			
			$coords = "";
			while($arr = pg_fetch_assoc($result)) {
				$coords .= $arr['storage_coordx'].'##'.$arr['storage_coordy'].'@@';
			}
			$coords .= "end";
			
			$dom = $coords;
		
		break;
		
		
		case "plantation_pictures_on_map":
		
			$id_plantation = $_GET["id_plantation"];
			$conf = $_GET["conf"];
			
			if($conf == 'plantation'){
				$cond = " AND doc_type = 502";
			} else
			if($conf == 'environment'){
				$cond = " AND doc_type != 502 
				AND doc_type != 649 
				AND doc_type != 655";
			} else {
				$cond = "";
			}
			
			$sql="SELECT doc_link, getregvalue(doc_type) AS pic_type, doc_date, description, coordx, coordy, heading
			FROM plantation_docs WHERE plantation_id = $id_plantation $cond
			AND doc_link IS NOT NULL AND coordx IS NOT NULL AND coordy IS NOT NULL";
			$result = pg_query($conn, $sql);
			
			$data = "";
			while($row = pg_fetch_assoc($result)){
				$data .= $row['coordx'].'##'.$row['coordy'].'##'.$row['pic_type'].'##'.$row['doc_link'].'##'.$row['doc_date'].'##'.$row['description'].'##'.$row['heading'].'@@';
			}
			
			$data .= 'end';
			
			$dom = $data;
			
		break;
		
		
		case "show_plantation_lines":
			
			$id_plantation = $_GET["id_plantation"];
		
			$sql="SELECT plant_line_id, id_plantation, id_region, geom_json FROM public.plantation_lines WHERE id_plantation=$id_plantation";
			$result = pg_query($conn, $sql);
		
			$geojson = array('type' => 'FeatureCollection', 'features' => array());
			
			while($row = pg_fetch_assoc($result)){
				$properties = $row;

				$feature = array(
					'type' => 'Feature',
					'geometry' => json_decode($row['geom_json']),
					'properties' => $properties
				);

				array_push($geojson['features'], $feature);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson['features']);
		
		break;
		
		
		case "show_plantation_and_collection":
		
			$id = $_GET["id"];
			
			$sql_plan = "select gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, 
				id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
				code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
				cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
				CONCAT(name_town,'-',zone) AS plantation_town, to_char(surface_ha,'999G999D9999') surface_ha, 
				to_char(area_acres,'999G999D9999') area_acres, id_culture1, cul1.lvalue AS culture
			from v_plantation p
			LEFT JOIN (SELECT id_regvalue, cvalue, $field As lvalue FROM v_regvalues) cul1 ON cul1.id_regvalue = p.id_culture1
			WHERE gid_plantation = $id";
		
			$result_plan = pg_query($conn, $sql_plan);
			
			$geojson_plantation = array('type' => 'FeatureCollection', 'features' => array());
			
			while($row_plantation = pg_fetch_assoc($result_plan)){
				$properties_plantation = $row_plantation;

				$feature_plantation = array(
					'type' => 'Feature',
					'geometry' => json_decode($row_plantation['geom_json']),
					'properties' => $properties_plantation
				);

				array_push($geojson_plantation['features'], $feature_plantation);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson_plantation['features']);  
			// $dom = $sql_plan;  
			
		break;
		
		
		case "show_all_farmer_plantations":
		
			$id_farmer = $_GET["id_farmer"]; 
			
			$sql_plan = "select gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, 
				id_culture, gid_town, geom_json, coordx, coordy, name_country, id_country, statut, name_manager,
				code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
				cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact,
				CONCAT(name_town,'-',zone) AS plantation_town, to_char(surface_ha,'999G999D9999') surface_ha, 
				to_char(area_acres,'999G999D9999') area_acres, id_culture1, cul1.lvalue AS culture
			from v_plantation p
			LEFT JOIN (SELECT id_regvalue, cvalue, $field As lvalue FROM v_regvalues) cul1 ON cul1.id_regvalue = p.id_culture1
			WHERE id_farmer = $id_farmer AND geom_json IS NOT NULL";
		
			$result_plan = pg_query($conn, $sql_plan);
			
			$geojson_plantation = array('type' => 'FeatureCollection', 'features' => array());
			
			while($row_plantation = pg_fetch_assoc($result_plan)){
				$properties_plantation = $row_plantation;

				$feature_plantation = array(
					'type' => 'Feature',
					'geometry' => json_decode($row_plantation['geom_json']),
					'properties' => $properties_plantation
				);

				array_push($geojson_plantation['features'], $feature_plantation);
			}

			header('Content-type: application/json');

			$dom = json_encode($geojson_plantation['features']);  
			
		break;
		
		
		case "selected_plantation_details":
		
			$plantation="";
			$certification="";
			$environment="";
			$bus_finance="";
			
			$farmer_name=$_GET['farmer_name'];
			$id_plantation=$_GET['gid_plantation'];
			
			$id_company = $_SESSION['id_company'];
		
			$sql_plant = "SELECT * FROM v_plantation WHERE gid_plantation=$id_plantation";
			$result_plant = pg_query($conn, $sql_plant);
			$arr_plant = pg_fetch_assoc($result_plant);

			$id_town = $arr_plant['id_town'];

			$sql_titledeed = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 626 ORDER By id_plantdoc DESC LIMIT 1";
			$result_titledeed = pg_query($conn, $sql_titledeed);
			$arr_titledeed = pg_fetch_assoc($result_titledeed);
			$title_deed_photo = $arr_titledeed['doc_link'];
			
			
				if($arr_plant['code_parcelle']!=''){
					$act_code_parcelle = 'class="active"';
					$row_code_parcelle = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PLANTATION_CODE'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['code_parcelle'].'</td></tr>';
				} else{$act_code_parcelle = ''; $row_code_parcelle = '';}
				
				// if($arr_plant['dc_completed']!=''){
					$act_plantstat = 'class="active"';
					if($arr_plant['dc_completed'] != 0){ $statut = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $statut = '<i class="fas fa-times text-danger"></i>'; }
					$row_plantstat = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_STATUS'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$statut.'</td></tr>';
				// } else{$act_plantstat = ''; $row_plantstat = '';}
				
				if($arr_plant['inactive']!=''){
					$act_inactive = 'class="active"';
					if($arr_plant['inactive_name'] == 'Yes'){ $inactive = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $inactive = '<i class="fas fa-times text-danger"></i>'; }
					$row_inactive = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_INACTIVE'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$inactive.'</td></tr>';
				} else{$act_inactive = ''; $row_inactive = '';}
				
				// if($arr_plant['inactive_date']!=''){
					// $act_inactive_date = 'class="active"';
					// $row_inactive_date = '<tr><td style="width:35%;" align="right">
						// <label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_INACTIVE_DATE'].' : </label></td>
						// <td align="left" style="padding-left:10px;">'.$arr_plant['inactive_date'].'</td></tr>';
				// } else{$act_inactive_date = ''; $row_inactive_date = '';}

				if($arr_plant['name_town']!=''){
					$act_name_town = 'class="active"';
					$row_name_town = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['DB_farmer_village'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['name_town'].'</td></tr>';
				} else{$act_name_town = ''; $row_name_town = '';}
				
				if($arr_plant['road_access']!=''){
					$act_name_town = 'class="active"';
					if($arr_plant['road_access'] == 508){ $road_access = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $road_access = '<i class="fas fa-times text-danger"></i>'; }
					$row_road_access = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['DB_road_access'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$road_access.'</td></tr>';
				} else{$act_road_access = ''; $row_road_access = '';}
				
				if($arr_plant['id_culture1']!=''){
					$act_culture = 'class="active"';
					$row_culture = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CULTURE'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['id_culture1'], $lang['DB_LANG_stat']) .'</td></tr>';
				} else{$act_culture = ''; $row_culture = '';}
				
				// Data Collection

				if($arr_plant['dc_completed'] == 1){ $dc_completed = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $dc_completed = '<i class="fas fa-times text-danger"></i>'; }
				$row_dc_completed = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_DC_COMPLETED'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$dc_completed.'</td></tr>';
				
				$row_dc_completed_by = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_DC_COMPLETED_BY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['dc_completed_name'].'</td></tr>';
				
				$row_dc_completed_date = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_DC_COMPLETED_DATE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['dc_completed_date'].'</td></tr>';
				
				$row_created_by = '<tr><td colspan="2" height="15"></td></tr>
				<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CREATED_BY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['created_by_name'].'</td></tr>';
				
				$row_created_date = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CREATION_DATE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['created_date'].'</td></tr>';
					
				$row_modified_by = '<tr><td colspan="2" height="15"></td></tr>
				<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MODIFIED_BY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['modified_by_name'].'</td></tr>';
				
				$row_modified_date = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MODIFIED_DATE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['modified_date'].'</td></tr>';
				
				
				$check_todo=""; $check_done=""; $check_checked="";
				if($arr_plant['check_out'] === 0) { $check_todo = "checked"; }
				if($arr_plant['check_out'] == 1) { $check_done = "checked"; }
				if($arr_plant['check_out'] == 2) { $check_checked = "checked"; }
				
				$row_check_out_data = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CHECK_TODO'].' : </label></td>
					<td align="left" style="padding-left:10px;">
						<div class="i-checks"><label> <input type="radio" '.$check_todo.' value="0" name="check_out_plant"> <i></i> '. $lang['CONT_TODO'] .' </label></div>
						<div class="i-checks"><label> <input type="radio" '.$check_done.' value="1" name="check_out_plant"> <i></i> '. $lang['CONT_DONE'] .' </label></div>
						<div class="i-checks"><label> <input type="radio" '.$check_checked.' value="2" name="check_out_plant"> <i></i> '. $lang['CONT_CHECK'] .' </label></div>
					</td></tr>';
					
				$row_check_out_date = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CHECKOUT_DATE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['check_out_date'].'</td></tr>';
				
				$row_check_out_by = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CHECKOUT_BY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['check_out_by'].'</td></tr>';
		
		
				
				if($plant_update == 1){
					$editPlantation = '<a class="rotate-btn" data-card="card-4" onclick="editPlantation();"><i class="fa fa-edit"></i></a>';
				} else {
					$editPlantation = '';
				}
				
				$plantationPictures="";
				
				$sql_plantPic = "SELECT id_plantdoc, doc_link, description FROM plantation_docs WHERE plantation_id = $id_plantation 
				AND doc_link IS NOT NULL AND coordx IS NOT NULL AND coordy IS NOT NULL
				AND doc_type = 502";
				$result_plantPic = pg_query($conn, $sql_plantPic);
				
				$i=0;
				$img="";
				$id_plantdoc = "";
				
				while ($arr_plantPic = pg_fetch_assoc($result_plantPic)){
					if($i == 0){ $id_plantdoc = $arr_plantPic['id_plantdoc']; }
					
					// $img .= '<img class="img-responsive flky" src="' .$arr_plantPic['doc_link']. '" alt="' .$arr_plantPic['description']. '" id="'. $arr_plantPic['id_plantdoc'] .'">';
					$img .= '<img class="flky" style="height:120px;" src="' .$arr_plantPic['doc_link']. '" alt="' .$arr_plantPic['description']. '" id="'. $arr_plantPic['id_plantdoc'] .'">';

					// $img .= '<div class="plantPictures" onclick="showPlantPic(\''.$arr_plantPic['coordx'].'\',\''.$arr_plantPic['coordy'].'\');">
                            // <div class="ibox-content">
								// <img class="img-thumbnail" src="' .$arr_plantPic['doc_link']. '" alt="Another alt text">
                                // <p>' .$arr_plantPic['description']. '</p>
                            // </div>
                    // </div>';	
					
					$i++;
				}
				
				if($id_company == 14167) {
					$doc_cond = " AND description = 'Agrivar'";
					$pdf = $id_plantation ."_agv.pdf";
				} else {
					$doc_cond = " AND description IS NULL";
					$pdf = $id_plantation .".pdf";
				}
				
				$sql_plantDoc = "SELECT * FROM plantation_docs WHERE plantation_id = ".$arr_plant['gid_plantation']." $doc_cond AND doc_type = 649";
				$result_plantDoc = pg_query($conn, $sql_plantDoc);
				
				while($arr_plantDoc = pg_fetch_assoc($result_plantDoc)) {
					$fp = $_SERVER['DOCUMENT_ROOT']."/ic/img/farmer_document/". $pdf;
					
					if (file_exists($fp)) {
						$plantationPictures .='<div style="padding:5px;">
							<div class="row" style="padding-top:5px; background-color:#e4e4e4;">
								<div class="col-md-10"><label><a href="https://icoop.live/ic/img/farmer_document/'. $pdf .'" target="_blank"><i class="fa fa-file"></i> Farmer Profil ('.$arr_plant['code_parcelle'].') </a></label></div>
								<div class="col-md-2 text-right"><a href="javascript:deletePlantDoc(\''.$id_plantation.'\',\''.$arr_plantDoc['id_plantdoc'].'\')" onclick="return confirm(\'Voulez vous vraiment supprimer cet document ?\');"><i class="fa fa-trash"></i></a></div>
							</div>
						</div>';
					}  
				}
				
				
				if($i!=0){
					$plantationPictures .='<div style="position: relative;">
						<div class="main-carousel">'. $img .'</div>
						<button class="button flickity-button custum--flickity --prev">
							<i class="fa fa-chevron-left" style="font-size: 26px;"></i>
						</button>
				
						<button class="button flickity-button custum--flickity --next">
							<i class="fa fa-chevron-right" style="font-size: 26px;"></i>
						</button>
					</div>';

					// $plantationPictures='<div class="col-md-10 col-md-offset-1">
						// <div class="slick_demo_2">'. $img .'</div>
					// </div>';
				}
				
				
				// dc_completed
				$dc_completed_sltNo = '';
				$dc_completed_sltYes = '';
				if($arr_plant['dc_completed']==0){$dc_completed_sltNo = 'selected';} else { $dc_completed_sltNo = '';}
				if($arr_plant['dc_completed']==1){$dc_completed_sltYes = 'selected';} else { $dc_completed_sltYes = '';}
			
				$dc_completed_list='<option value="">---</option>
				<option value="0" '. $dc_completed_sltNo .'>Not Complete</option>
				<option value="1" '. $dc_completed_sltYes .'>Complete</option>';
				
				// bio
				$bio_sltNo = '';
				$bio_sltYes = '';
				if($arr_plant['bio']==0){$bio_sltNo = 'selected';} else { $bio_sltNo = '';}
				if($arr_plant['bio']==1){$bio_sltYes = 'selected';} else { $bio_sltYes = '';}
			
				$bio_list='<option value="">---</option>
				<option value="0" '. $bio_sltNo .'>No</option>
				<option value="1" '. $bio_sltYes .'>Yes</option>';
				
				// bio_suisse
				$bio_suisse_slt0 = '';
				$bio_suisse_slt1 = '';
				$bio_suisse_slt2 = '';
				if($arr_plant['bio_suisse']==0){$bio_suisse_slt0 = 'selected';} else { $bio_suisse_slt0 = '';}
				if($arr_plant['bio_suisse']==1){$bio_suisse_slt1 = 'selected';} else { $bio_suisse_slt1 = '';}
				if($arr_plant['bio_suisse']==2){$bio_suisse_slt2 = 'selected';} else { $bio_suisse_slt2 = '';}
			
				$bio_suisse_list='<option value="">---</option>
				<option value="0" '. $bio_suisse_slt0 .'>No</option>
				<option value="0" '. $bio_suisse_slt1 .'>Candidate</option>
				<option value="1" '. $bio_suisse_slt2 .'>Achieved</option>';
				
				// property
				$property_sltNo = '';
				$property_sltYes = '';
				if($arr_plant['property']==0){$property_sltNo = 'selected';} else { $property_sltNo = '';}
				if($arr_plant['property']==1){$property_sltYes = 'selected';} else { $property_sltYes = '';}
			
				$property_list='<option value="">---</option>
				<option value="0" '. $property_sltNo .'>No</option>
				<option value="1" '. $property_sltYes .'>Yes</option>';
				
				// title_deed
				$title_deed0 = '';
				$title_deed1 = '';
				$title_deed2 = '';
				$title_deed3 = '';
				if($arr_plant['title_deed']==0){$title_deed0 = 'selected';} else { $title_deed0 = '';}
				if($arr_plant['title_deed']==1){$title_deed1 = 'selected';} else { $title_deed1 = '';}
				if($arr_plant['title_deed']==2){$title_deed2 = 'selected';} else { $title_deed2 = '';}
				if($arr_plant['title_deed']==3){$title_deed3 = 'selected';} else { $title_deed3 = '';}
			
				$title_deed_list='<option value="">---</option>
				<option value="0" '. $title_deed0 .'>None</option>
				<option value="1" '. $title_deed1 .'>Attestation Villageoise</option>
				<option value="2" '. $title_deed2 .'>Titre foncier</option>
				<option value="3" '. $title_deed3 .'>Propriétaire terriain</option>';  
				
				// perimeter
				$perimeter_sltNo = '';
				$perimeter_sltYes = '';
				if($arr_plant['perimeter']==0){$perimeter_sltNo = 'selected';} else { $perimeter_sltNo = '';}
				if($arr_plant['perimeter']==1){$perimeter_sltYes = 'selected';} else { $perimeter_sltYes = '';}
			
				$perimeter_list='<option value="">---</option>
				<option value="0" '. $perimeter_sltNo .'>No</option>
				<option value="1" '. $perimeter_sltYes .'>Yes</option>';
				
				// eco_river
				$eco_river_sltNo = '';
				$eco_river_sltYes = '';
				if($arr_plant['eco_river']==0){$eco_river_sltNo = 'selected';} else { $eco_river_sltNo = '';}
				if($arr_plant['eco_river']==1){$eco_river_sltYes = 'selected';} else { $eco_river_sltYes = '';}
			
				$eco_river_list='<option value="">---</option>
				<option value="0" '. $eco_river_sltNo .'>No</option>
				<option value="1" '. $eco_river_sltYes .'>Yes</option>';
				
				// eco_shallows
				$eco_shallows_sltNo = '';
				$eco_shallows_sltYes = '';
				if($arr_plant['eco_shallows']==0){$eco_shallows_sltNo = 'selected';} else { $eco_shallows_sltNo = '';}
				if($arr_plant['eco_shallows']==1){$eco_shallows_sltYes = 'selected';} else { $eco_shallows_sltYes = '';}
			
				$eco_shallows_list='<option value="">---</option>
				<option value="0" '. $eco_shallows_sltNo .'>No</option>
				<option value="1" '. $eco_shallows_sltYes .'>Yes</option>';
				
				// eco_wells
				$eco_wells_sltNo = '';
				$eco_wells_sltYes = '';
				if($arr_plant['eco_wells']==0){$eco_wells_sltNo = 'selected';} else { $eco_wells_sltNo = '';}
				if($arr_plant['eco_wells']==1){$eco_wells_sltYes = 'selected';} else { $eco_wells_sltYes = '';}
			
				$eco_wells_list='<option value="">---</option>
				<option value="0" '. $eco_wells_sltNo .'>No</option>
				<option value="1" '. $eco_wells_sltYes .'>Yes</option>';
				
				// seed_type
				$seed_type_sltNo = '';
				$seed_type_sltYes = '';
				if($arr_plant['seed_type']==0){$seed_type_sltNo = 'selected';} else { $seed_type_sltNo = '';}
				if($arr_plant['seed_type']==1){$seed_type_sltYes = 'selected';} else { $seed_type_sltYes = '';}
			
				$seed_type_list='<option value="">---</option>
				<option value="0" '. $seed_type_sltNo .'>No</option>
				<option value="1" '. $seed_type_sltYes .'>Yes</option>';
			
			$sqlP="SELECT doc_link, description FROM public.plantation_docs WHERE plantation_id = $id_plantation AND doc_type = 649";
			$rstP = pg_query($conn, $sqlP);
			$arrP = pg_fetch_assoc($rstP);
			
			$doc_link = $arrP['doc_link'];	
			$description = $arrP['description'];	
			
			$fm_doc="";
			if($doc_link!="") {				
				$file = 'img/farmer_document/'.$pdf;
	
				if(file_exists($file)) {
					$fm_doc = '<a class="pull-right" href="#" onclick="showFarmerDocPDF(\''.$pdf.'\',\'0\',\''.$id_plantation.'\',\'\');"><i class="fas fa-print"></i></a>';
				} else {
					$fm_doc = '<a class="pull-right" href="#" onclick="showFarmerDocPDF(\''.$doc_link.'\',\'1\',\''.$id_plantation.'\',\''.$id_company.'\');"><i class="fas fa-print"></i></a>';
				}
			} else {
				if($arr_plant['geom_json']!=""){
					if($id_company == 14167) {
						$fm_doc = '<a class="pull-right" href="#" onclick="createFarmerDocCaptureAgv(\''.$id_plantation.'\');"><i class="fas fa-print"></i></a>';
					} else {
						$fm_doc = '<a class="pull-right" href="#" onclick="createFarmerDocCapture(\''.$id_plantation.'\');"><i class="fas fa-print"></i></a>';
					}
				} else { $fm_doc=""; }
			}
			
			$plantation .='<div class="card-wrapper">
				<div id="card-4-modal" class="card-bg hide"></div>
				<div id="card-4" class="card-rotating effect__click">

					<div class="face front">
						<div style="border-bottom:1px solid #e4e4e4;padding:12px 0 4px 0;">
							<a class="rotate-btn" data-card="card-4" onclick="editPlantationSpecial(\''.$id_plantation.'\');"><i class="fa fa-edit"></i></a>
							'. $fm_doc .'
						</div>

						<div class="contact-box" style="border:none;">
							<table style="width:100%; font-size:12px;">
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['PROJECT_MODAL_TITLE'].' : </label></td>
								<td align="left" style="padding-left:10px;">'.$arr_plant['project_name'].'</td></tr>
								
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['PROJECT_TASK'].' : </label></td>
								<td align="left" style="padding-left:10px;">'.$arr_plant['task_titleshort'].'</td></tr>
							
								
								<tr><td style="width:30%; padding:3px;" align="right" class="bg-success"></td>
								<td style="padding:3px 3px 3px 10px;" class="bg-success">'.$lang['CONT_PLANT_DATA'].'</td></tr>
								<tr><td colspan="2"><div style="height:5px;"></div></td></tr>
							
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">ID Plantation : </label></td>
								<td align="left" style="padding-left:10px;">'.$arr_plant['gid_plantation'].'</td></tr>
						
								'.$row_plantstat.$row_inactive.$row_code_parcelle.$row_name_town.$row_road_access.$row_culture.'
							
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">Hectare : </label></td>
								<td align="left" style="padding-left:10px;">'.number_format($arr_plant['surface_ha'],4).'</td></tr>
								
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">m² : </label></td>
								<td align="left" style="padding-left:10px;">'.number_format($arr_plant['area'], 0, ',', ' ').'</td></tr>
								
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">Acre : </label></td>
								<td align="left" style="padding-left:10px;">'.number_format($arr_plant['area_acres'],3).'</td></tr>
								
								
								<tr><td colspan="2" style="padding:3px; height:20px;"></td></tr>
							
								<tr><td style="width:30%; padding:3px;" align="right" class="bg-success"></td>
								<td style="padding:3px 3px 3px 10px;" class="bg-success">'. $lang['CONT_DATA_COLLECTION'] .'</td></tr>
								<tr><td colspan="2"><div style="height:5px;"></div></td></tr>
								'.$row_dc_completed.$row_dc_completed_by.$row_dc_completed_date.'
								
								<tr><td style="width:30%; padding:3px;" align="right" class="bg-success"></td>
								<td style="padding:3px 3px 3px 10px;" class="bg-success">'. $lang['CONT_CHECK_TODO'] .'</td></tr>
								<tr><td colspan="2"><div style="height:5px;"></div></td></tr>
								'.$row_check_out_data.$row_check_out_date.$row_check_out_by.'
								
								<tr><td colspan="2"><div style="height:10px; border-bottom:3px solid #1ab394;;"></div></td></tr>
								'.$row_created_by.$row_created_date.$row_modified_by.$row_modified_date.'
							</table>
						
							<div class="clearfix"></div>
						</div>
					</div>

					<div class="face back hide animated front_face" id="edit_plantation">
						<div class="card-block">
							<div class="pull-left" style="border-bottom:1px solid #e4e4e4;padding:4px 0 10px 0; margin-bottom:15px;width:100%;">
								<a class="rotate-btn pull-left" onclick="updatePlantation(\''.$arr_plant['gid_plantation'].'\',\''.$arr_plant['id_farmer'].'\', \''.$type.'\');" data-card="card-4"><i class="fa fa-save"></i></a>
								<a class="rotate-btn pull-right" style="color:red;" data-card="card-4" onclick="CancelEditPlantation();"><i class="fa fa-ban"></i></a>
							</div>
							
							<div class="form-group">
								<label '.$act_dc_completed.' for="dc_completed" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_DC_COMPLETED'].'</label>
								<select id="dc_completed" class="form-control">'.$dc_completed_list.'</select>
							</div>
							
							<div class="form-group">
								<label '.$act_bio.' for="plant_bio" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BIO'].'</label>
								<select id="plant_bio" class="form-control">'.$bio_list.'</select>
							</div>
							
							<div class="form-group">
								<label '.$act_bio_suisse.' for="bio_suisse" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BIO_SUISSE'].'</label>
								<select id="bio_suisse" class="form-control">'.$bio_suisse_list.'</select>
							</div>
							
							<div class="form-group">
								<label '.$act_name_town.' for="name_town" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PLANT_CITY'].'</label>
								<input type="text" value="'.$arr_plant['name_town'].'" id="name_town" class="form-control">
							</div>
							
							<div class="form-group">
								<label '.$act_plantarea.' for="area" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SURFACE'].'</label>
								<input type="text" value="'.$arr_plant['area'].'" id="area" class="form-control">
							</div>
							
							<div class="form-group">
								<label '.$act_area_acres.' for="area_acres" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_AREA_ACRES'].'</label>
								<input type="text" value="'.$arr_plant['area_acres'].'" id="area_acres" class="form-control">
							</div>
							
							<div class="form-group">
								<label '.$act_surface_ha.' for="surface_ha" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SURFACE_HA'].'</label>
								<input type="text" value="'.$arr_plant['surface_ha'].'" id="surface_ha" class="form-control">
							</div>

							<div class="form-group">
								<label '.$act_plantyear.' for="year_creation" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_LAND_SINCE'].'</label>
								<input type="text" value="'.$arr_plant['year_creation'].'" id="year_creation" class="form-control">
							</div>

							<div class="form-group">
								<label '.$act_plantseed.' for="variety" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SEED_VARIETY'].'</label>
								<input type="text" value="'.$arr_plant['variety'].'" id="variety" class="form-control">
							</div>
							
							<div class="form-group"> 
								<label '.$act_property.' for="property" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PROPERTY'].'</label>
								<select id="property" class="form-control">'.$property_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_title_deed.' for="title_deed" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_TITLE_DEED'].'</label>
								<select id="title_deed" class="form-control">'.$title_deed_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_perimeter.' for="perimeter" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PERIMETER'].'</label>
								<select id="perimeter" class="form-control">'.$perimeter_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_eco_river.' for="eco_river" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_ECO_RIVER'].'</label>
								<select id="eco_river" class="form-control">'.$eco_river_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_eco_shallows.' for="eco_shallows" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_ECO_SHALLOWS'].'</label>
								<select id="eco_shallows" class="form-control">'.$eco_shallows_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_eco_wells.' for="eco_wells" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_ECO_WELLS'].'</label>
								<select id="eco_wells" class="form-control">'.$eco_wells_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_seed_type.' for="seed_type" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SEED_TYPE'].'</label>
								<select id="seed_type" class="form-control">'.$seed_type_list.'</select>
							</div>
							
							<div class="form-group">
								<label '.$act_name_manager.' for="name_manager" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NAME_MANAGER'].'</label>
								<input type="text" value="'.$arr_plant['name_manager'].'" id="name_manager" class="form-control">
							</div>
							
							<div class="form-group">
								<label '.$act_manager_phone.' for="manager_phone" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MANAGER_PHONE'].'</label>
								<input type="text" value="'.$arr_plant['manager_phone'].'" id="manager_phone" class="form-control">
							</div>
							
							<div class="form-group">
								<label '.$act_inactive.' for="inactive" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_INACTIVE'].'</label>
								<input type="text" value="'.$arr_plant['inactive'].'" id="inactive" class="form-control">
							</div>
							
							<div class="form-group">
								<label '.$act_inactive.' for="inactive_date" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_INACTIVE_DATE'].'</label>
								<input type="text" value="'.$arr_plant['inactive_date'].'" id="inactive_date" class="form-control">
							</div>
							
							<div class="form-group">
								<label '.$act_notes.' for="notes" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NOTES'].'</label>
								<textarea id="notes" class="form-control">'.$arr_plant['notes'].'</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>';
			
	
			$dom = $plantation.'##'.$plantationPictures.'##'.$id_town.'##'.$id_plantdoc.'##'.$arr_plant['id_farmer'];
		
		break;
		
		
		case "selected_plantation_certification_details":
			
			$certification="";
			
			$id_company=$_SESSION['id_company'];
			$id_plantation=$_GET['gid_plantation'];
		
			$sql_plant = "SELECT * FROM v_plantation WHERE gid_plantation=$id_plantation";

			$result_plant = pg_query($conn, $sql_plant);
			$arr_plant = pg_fetch_assoc($result_plant);
			
			// Certifications
		
			if($arr_plant['numb_feet']!=''){
				$act_numb_feet = 'class="active"';
				$row_numb_feet = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NUMB_FEET'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['numb_feet'].'</td></tr>';
			} else{$act_numb_feet = ''; $row_numb_feet = '';}
			
			if($arr_plant['globalgap']!=''){
				$act_globalgap = 'class="active"';
				if($arr_plant['globalgap'] == 510){ $globalgap = '<i class="fas fa-times text-danger"></i>'; } 
				else { $globalgap = getRegvalues($arr_plant['globalgap'], $lang['DB_LANG_stat']) . '<a href="#" onclick="editPlantCertification(\'globalgap\',\''.$arr_plant['globalgap'].'\',\''.$id_plantation.'\');" class="pull-right"><i class="fa fa-pencil-alt"></i></a>'; }
				$row_globalgap = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GLOBALGAP'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $globalgap .'</td></tr>';
			} else{$act_globalgap = ''; $row_globalgap = '';}
			
			if($arr_plant['cert_approved_by']!=''){
				$act_cert_approved_by = 'class="active"';
				$row_cert_approved_by = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_BY'] .' : </label></td>
					<td align="left" style="padding-left:10px;">'. $arr_plant['cert_approved_name'] .'</td></tr>';
			} else{$act_cert_approved_by = ''; $row_cert_approved_by = '';}
			
			if($arr_plant['cert_notes']!=''){
				$act_cert_notes = 'class="active"';
				$row_cert_notes = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_NOTES'] .' : </label></td>
					<td align="left" style="padding-left:10px;">'. $arr_plant['cert_notes'] .'</td></tr>';
			} else{$act_cert_notes = ''; $row_cert_notes = '';}
			
			if($arr_plant['cert_approved_date']!=''){
				$act_cert_approved_date = 'class="active"';
				$date = explode(" ", $arr_plant['cert_approved_date']);
				$date_globalgap = $date[0];
				$row_cert_approved_date = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_DATE'] .' : </label></td>
					<td align="left" style="padding-left:10px;">'. $date_globalgap .'</td></tr>
					<tr><td colspan="2" style="height:15px;"></td></tr>';
			} else{$act_cert_approved_date = ''; $row_cert_approved_date = ''; $date_globalgap="";}

			if($arr_plant['rspo']!=''){
				$act_rspo = 'class="active"';
				if($arr_plant['rspo'] == 510){ $rspo = '<i class="fas fa-times text-danger"></i>'; } 
				else { $rspo = getRegvalues($arr_plant['rspo'], $lang['DB_LANG_stat']) . '<a href="#" onclick="editPlantCertification(\'rspo\',\''.$arr_plant['rspo'].'\',\''.$id_plantation.'\');" class="pull-right"><i class="fa fa-pencil-alt"></i></a>'; }
				$row_rspo = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_RSPO'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $rspo .'</td></tr>';
			} else{$act_rspo = ''; $row_rspo = '';}
			
			if($arr_plant['bio']!=''){
				$act_bio = 'class="active"';
				if($arr_plant['bio'] == 510){ $bio = '<i class="fas fa-times text-danger"></i>'; } 
				else { $bio = getRegvalues($arr_plant['bio'], $lang['DB_LANG_stat']) . '<a href="#" onclick="editPlantCertification(\'bio\',\''.$arr_plant['bio'].'\',\''.$id_plantation.'\');" class="pull-right"><i class="fa fa-pencil-alt"></i></a>'; }
				$row_bio = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BIO'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $bio .'</td></tr>';
			} else{$act_bio = ''; $row_bio = '';}
			
			if($arr_plant['bio_suisse']!=''){
				$act_bio_suisse = 'class="active"';
				if($arr_plant['bio_suisse'] == 510){ $bio_suisse = '<i class="fas fa-times text-danger"></i>'; } 
				else { $bio_suisse = getRegvalues($arr_plant['bio_suisse'], $lang['DB_LANG_stat']) . '<a href="#" onclick="editPlantCertification(\'bio_suisse\',\''.$arr_plant['bio_suisse'].'\',\''.$id_plantation.'\');" class="pull-right"><i class="fa fa-pencil-alt"></i></a>'; }
				$row_bio_suisse = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BIO_SUISSE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $bio_suisse .'</td></tr>';
			} else{$act_bio_suisse = ''; $row_bio_suisse = '';}
			
			if($arr_plant['fair_trade']!=''){
				$act_fair_trade = 'class="active"';
				if($arr_plant['fair_trade'] == 510){ $fair_trade = '<i class="fas fa-times text-danger"></i>'; } 
				else { $fair_trade = getRegvalues($arr_plant['fair_trade'], $lang['DB_LANG_stat']) . '<a href="#" onclick="editPlantCertification(\'fair_trade\',\''.$arr_plant['fair_trade'].'\',\''.$id_plantation.'\');" class="pull-right"><i class="fa fa-pencil-alt"></i></a>'; }
				$row_fair_trade = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">FairTrade : </label></td>
					<td align="left" style="padding-left:10px;">'. $fair_trade .'</td></tr>';
			} else{$act_fair_trade = ''; $row_fair_trade = '';}
		
			if($arr_plant['utz_rainforest']!=''){
				$act_utz_rainforest = 'class="active"';
				if($arr_plant['utz_rainforest'] == 510){ $utz_rainforest = '<i class="fas fa-times text-danger"></i>'; } 
				else { $utz_rainforest = getRegvalues($arr_plant['utz_rainforest'], $lang['DB_LANG_stat']) . '<a href="#" onclick="editPlantCertification(\'utz_rainforest\',\''.$arr_plant['utz_rainforest'].'\',\''.$id_plantation.'\');" class="pull-right"><i class="fa fa-pencil-alt"></i></a>'; }
				$row_utz_rainforest = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_UTZ_RAINFOREST'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $utz_rainforest .'</td></tr>';
			} else{$act_utz_rainforest = ''; $row_utz_rainforest = '';}
		
			if(($row_globalgap!="") OR ($row_rspo!="") OR ($row_bio!="") OR ($row_bio_suisse!="") OR ($row_numb_feet!="") OR ($row_fair_trade!="") OR ($row_utz_rainforest!="")){
				$sh_certifications = '';
			} else { $sh_certifications = 'hide'; }
			
			
			$globalgap_list = '<option value="">---</option>';
			$sql_globalgap = "SELECT * FROM regvalues WHERE id_register = 263 ORDER BY id_regvalue ASC";
			$result_globalgap = pg_query($conn, $sql_globalgap);
			while($arr_globalgap = pg_fetch_assoc($result_globalgap)) {
				if($lang['DB_LANG_stat']=='en') {
					$globalgap_value=$arr_globalgap['cvalue'];
				} else {
					$globalgap_value=$arr_globalgap['cvalue'.$lang['DB_LANG_stat']];
				}
				if($arr_globalgap['id_regvalue']==$arr_plant['globalgap']){ $sel_gGap='selected'; } else { $sel_gGap=''; }
				
				$globalgap_list .= '<option value="'. $arr_globalgap['id_regvalue'] .'"'. $sel_gGap .'>'. $globalgap_value .'</option>';
			}

			$id_farmer = $arr_plant['id_farmer'];
			$code_farmer = $arr_plant['code_farmer'];

			$sqlP="SELECT doc_link, doc_type FROM public.contact_docs WHERE contact_id = $id_farmer AND doc_type IN (654, 809)";
			$rstP = pg_query($conn, $sqlP);
			$arrP = pg_fetch_assoc($rstP);
			
			$doc_link = $arrP['doc_link'];	
			$doc_type = $arrP['doc_type'];	
			
			$fm_doc="";
			$fm_edit="";
			if($id_company == 19) {
				$fm_edit='<a class="rotate-btn" data-card="card-6" onclick="editPlantationCertificate();"><i class="fa fa-edit"></i></a>';
				
				// if($doc_link!="") {
					// $fm_doc = '<a class="pull-right" href="#" onclick="showCertDocPDF(\''.$doc_link.'\',\''.$doc_type.'\');"><i class="fas fa-print"></i></a>';
				// } else {
					$fm_doc = '<a class="pull-right" href="#" onclick="selectCertDocVersion(\''.$id_farmer.'\',\''.$id_plantation.'\');"><i class="fas fa-print"></i></a>';
				// }
			}

			$certification .='<div class="card-wrapper">
				<div id="card-6-modal" class="card-bg hide"></div>
				<div id="card-6" class="card-rotating effect__click">

					<div class="face front">
						<div style="border-bottom:1px solid #e4e4e4;padding:12px 0 4px 0;">
							'. $fm_edit . $fm_doc .'
						</div>
			
						<div class="contact-box" style="border:none;">
							<table style="width:100%; font-size:12px;">
								<tr><td style="width:30%; padding:3px;" align="right" class="bg-success '.$sh_certifications.'"></td>
								<td style="padding:3px 3px 3px 10px;" class="bg-success '.$sh_certifications.'">'.$lang['CONT_CERTIFICATIONS'].'</td></tr>
								<tr><td colspan="2" class="'.$sh_certifications.'"><div style="height:5px;"></div></td></tr>
									
								'.$row_numb_feet.$row_globalgap.$row_cert_approved_by.$row_cert_notes.$row_cert_approved_date.$row_rspo.$row_bio.$row_bio_suisse.$row_fair_trade.$row_utz_rainforest.'
							</table>
							
							<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
							
							<table style="width:100%; font-size:12px; margin-top:15px;">
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_FIRST_INSPECTION'] .' : </label></td>
								<td align="left" style="padding-left:10px;"> </td></tr>
								
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_DATE'] .' : </label></td>
								<td align="left" style="padding-left:10px;"> </td></tr>
								
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_BY'] .' : </label></td>
								<td align="left" style="padding-left:10px;"> </td></tr>
							</table>
							
							<table style="width:100%; font-size:12px; margin-top:15px;">
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_APPROVAL_NO'] .' : </label></td>
								<td align="left" style="padding-left:10px;"> </td></tr>
							</table>
							
							<table style="width:100%; font-size:12px; margin-top:15px;">
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_MR_INSPECTION'] .' : </label></td>
								<td align="left" style="padding-left:10px;"> </td></tr>
								
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_DATE'] .' : </label></td>
								<td align="left" style="padding-left:10px;"> </td></tr>
								
								<tr><td style="width:35%;" align="right">
								<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_BY'] .': </label></td>
								<td align="left" style="padding-left:10px;"> </td></tr>
							</table>
							
							<div class="clearfix"></div>
						</div>
					</div>
					
					<div class="face back hide animated front_face" id="edit_plantation_certificate">
						<div class="card-block">
							<div class="pull-left" style="border-bottom:1px solid #e4e4e4;padding:4px 0 10px 0; margin-bottom:15px;width:100%;">
								<a class="rotate-btn pull-left" onclick="updatePlantationCertificate(\''.$arr_plant['gid_plantation'].'\');" data-card="card-6"><i class="fa fa-save"></i></a>
								<a class="rotate-btn pull-right" style="color:red;" data-card="card-6" onclick="CancelEditPlantationCertificate();"><i class="fa fa-ban"></i></a>
							</div>
							
							<div class="form-group"> 
								<label '.$act_globalgap.' for="cert_globalgap" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GLOBALGAP'].'</label>
								<select id="cert_globalgap" class="form-control">'.$globalgap_list.'</select>
							</div>
							
							<div class="form-group">
								<label '.$act_inactive.' for="cert_approved_date" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_STATUS_DATE'].'</label>
								<input type="text" value="'.$date_globalgap.'" id="cert_approved_date" class="form-control edit_delivery_date">
							</div>
							
							<div class="form-group">
								<label '.$act_cert_notes.' for="cert_notes" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NOTES'].'</label>
								<textarea id="cert_notes" class="form-control">'.$arr_plant['cert_notes'].'</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>';
			
			$certDocument = '<div class="bg-success text-center" style="padding:3px; margin-top:10px;">GlobalGap</div>';
			$sql_plantDoc = "SELECT * FROM public.contact_docs WHERE contact_id = $id_farmer AND doc_type IN (654, 809)";
			$result_plantDoc = pg_query($conn, $sql_plantDoc);
			
			while($arr_plantDoc = pg_fetch_assoc($result_plantDoc)){
				if($arr_plantDoc['doc_type'] == 654){
					$fp = $_SERVER['DOCUMENT_ROOT']."/ic/img/certification_document/". $id_farmer .".pdf";
					
					if (file_exists($fp)) {
						$certDocument .='<div class="col-md-12">
							<div class="row" style="padding-top:5px; background-color:#e4e4e4;">
								<div class="col-md-10"><label><a href="https://icoop.live/ic/img/certification_document/'. $id_farmer .'.pdf" target="_blank"><i class="fa fa-file"></i> GlobalGap Agreement ('.$code_farmer.') <br/> '.$arr_plantDoc['doc_date'].'</a></label></div>
								<div class="col-md-2 text-right"><a href="javascript:deleteCertDoc(\''.$id_plantation.'\',\''.$arr_plantDoc['id_condoc'].'\')" onclick="return confirm(\'Voulez vous vraiment supprimer cet document ?\');"><i class="fa fa-trash"></i></a></div>
							</div>
						</div>';
					}
				}
				
				if($arr_plantDoc['doc_type'] == 809){
					$fp2 = $_SERVER['DOCUMENT_ROOT']."/ic/img/certification_document_2/". $id_farmer .".pdf";
					
					if (file_exists($fp2)) {
						$certDocument .='<div class="col-md-12">
							<div class="row" style="padding-top:5px; background-color:#e4e4e4;">
								<div class="col-md-10"><label><a href="https://icoop.live/ic/img/certification_document_2/'. $id_farmer .'.pdf" target="_blank"><i class="fa fa-file"></i> GlobalGap Agreement 2 ('.$code_farmer.') <br/> '.$arr_plantDoc['doc_date'].'</a></label></div>
								<div class="col-md-2 text-right"><a href="javascript:deleteCertDoc(\''.$id_plantation.'\',\''.$arr_plantDoc['id_condoc'].'\')" onclick="return confirm(\'Voulez vous vraiment supprimer cet document ?\');"><i class="fa fa-trash"></i></a></div>
							</div>
						</div>';
					}
				}
			}
			
			$afs_btn = '';
			$sql_plantDoc = "SELECT * FROM plantation_docs WHERE plantation_id = $id_plantation AND doc_type = 1167";
					$result_plantDoc = pg_query($conn, $sql_plantDoc);
					$arr_plantDoc = pg_fetch_assoc($result_plantDoc);
					
					if($arr_plantDoc) {
						if(file_exists($_SERVER['DOCUMENT_ROOT'].'/ic/img/engagement_pmci/AFS-'.$id_plantation.'.pdf')) {
							$afs_btn ='<div class="bg-success text-center" style="padding:3px; margin-top:10px;">Contrat BIO</div>
							<div class="col-md-12">
								<div class="row" style="padding-top:5px; background-color:#e4e4e4;">
									<div class="col-md-10"><label><a href="https://icoop.live/ic/img/engagement_pmci/AFS-'.$id_plantation.'.pdf" target="_blank"><i class="fa fa-file"></i> '.$arr_plant['code_parcelle'].' </a></label></div>
									<div class="col-md-2 text-right"><a href="javascript:deleteEngagementDoc(\''.$id_plantation.'\',\''.$arr_plantDoc['id_plantdoc'].'\')" onclick="return confirm(\'Voulez vous vraiment supprimer cet document ?\');"><i class="fa fa-trash"></i></a></div>
								</div>
							</div>';	
						}
						
					} else {
						$afs_btn = '<div class="bg-success text-center" style="padding:3px; margin-top:10px;">Contrat BIO</div>
						<div class="col-md-12">
							<a class="pull-right" href="#" onclick="getAFSContract(\''.$id_plantation.'\',\''.$id_farmer.'\');">
								<i class="fas fa-plus"></i> New
							</a>
						</div>';
					}
			
			$dom = $certification.'##'.$certDocument.'##'.$afs_btn;
		
		break;
		
		
		case "selected_plantation_environment_details":
		
			$environment="";
			
			$id_company = $_SESSION['id_company'];
			$id_plantation=$_GET['gid_plantation'];
		
			$sql_plant = "SELECT * FROM v_plantation WHERE gid_plantation=$id_plantation";

			$result_plant = pg_query($conn, $sql_plant);
			$arr_plant = pg_fetch_assoc($result_plant);
			
		
			$sql_fertilizer = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 596 ORDER By id_plantdoc DESC LIMIT 1";
			$result_fertilizer = pg_query($conn, $sql_fertilizer);
			$arr_fertilizer = pg_fetch_assoc($result_fertilizer);
			$fertilizer_photo = $arr_fertilizer['doc_link'];
			
			$sql_herbicides = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 597 ORDER By id_plantdoc DESC LIMIT 1";
			$result_herbicides = pg_query($conn, $sql_herbicides);
			$arr_herbicides = pg_fetch_assoc($result_herbicides);
			$herbicides_photo = $arr_herbicides['doc_link'];
			
			$sql_pesticide = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 598 ORDER By id_plantdoc DESC LIMIT 1";
			$result_pesticide = pg_query($conn, $sql_pesticide);
			$arr_pesticide = pg_fetch_assoc($result_pesticide);
			$pesticide_photo = $arr_pesticide['doc_link'];
			
			$sql_adjcultures = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 599 ORDER By id_plantdoc DESC LIMIT 1";
			$result_adjcultures = pg_query($conn, $sql_adjcultures);
			$arr_adjcultures = pg_fetch_assoc($result_adjcultures);
			$adjcultures_photo = $arr_adjcultures['doc_link'];
			
			$sql_forest = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 600 ORDER By id_plantdoc DESC LIMIT 1";
			$result_forest = pg_query($conn, $sql_forest);
			$arr_forest = pg_fetch_assoc($result_forest);
			$forest_photo = $arr_forest['doc_link'];
			
			$sql_fire = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 601 ORDER By id_plantdoc DESC LIMIT 1";
			$result_fire = pg_query($conn, $sql_fire);
			$arr_fire = pg_fetch_assoc($result_fire);
			$fire_photo = $arr_fire['doc_link'];
			
			$sql_waste = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 602 ORDER By id_plantdoc DESC LIMIT 1";
			$result_waste = pg_query($conn, $sql_waste);
			$arr_waste = pg_fetch_assoc($result_waste);
			$waste_photo = $arr_waste['doc_link'];
			
			$sql_river = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 605 ORDER By id_plantdoc DESC LIMIT 1";
			$result_river = pg_query($conn, $sql_river);
			$arr_river = pg_fetch_assoc($result_river);
			$river_photo = $arr_river['doc_link'];
			
			$sql_shallow = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 606 ORDER By id_plantdoc DESC LIMIT 1";
			$result_shallow = pg_query($conn, $sql_shallow);
			$arr_shallow = pg_fetch_assoc($result_shallow);
			$shallow_photo = $arr_shallow['doc_link'];
			
			$sql_well = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 607 ORDER By id_plantdoc DESC LIMIT 1";
			$result_well = pg_query($conn, $sql_well);
			$arr_well = pg_fetch_assoc($result_well);
			$well_photo = $arr_well['doc_link'];
			
			$sql_bufferzone = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 608 ORDER By id_plantdoc DESC LIMIT 1";
			$result_bufferzone = pg_query($conn, $sql_bufferzone);
			$arr_bufferzone = pg_fetch_assoc($result_bufferzone);
			$bufferzone_photo = $arr_bufferzone['doc_link'];
			
			$sql_irrigation = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 690 ORDER By id_plantdoc DESC LIMIT 1";
			$result_irrigation = pg_query($conn, $sql_irrigation);
			$arr_irrigation = pg_fetch_assoc($result_irrigation);
			$irrigation_photo = $arr_irrigation['doc_link'];
			
			$sql_drainage = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 691 ORDER By id_plantdoc DESC LIMIT 1";
			$result_drainage = pg_query($conn, $sql_drainage);
			$arr_drainage = pg_fetch_assoc($result_drainage);
			$drainage_photo = $arr_drainage['doc_link'];
			
			$sql_slope = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 692 ORDER By id_plantdoc DESC LIMIT 1";
			$result_slope = pg_query($conn, $sql_slope);
			$arr_slope = pg_fetch_assoc($result_slope);
			$slope_photo = $arr_slope['doc_link'];
			
			// Water source
			
			// if($arr_plant['perimeter']!=''){
				$act_perimeter = 'class="active"';
				if($arr_plant['perimeter'] == 1){ $perimeter = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $perimeter = '<i class="fas fa-times text-danger"></i>'; }
				if($bufferzone_photo!=""){ $prv_bufferzone_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$bufferzone_photo.'\',\''.getRegvalues(608, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_bufferzone_photo = ""; }
				$row_perimeter = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PERIMETER'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$perimeter.$prv_bufferzone_photo.'</td></tr>'; 
			// } else{$act_perimeter = ''; $row_perimeter = '';}
			
			// if($arr_plant['eco_river']!=''){
				$act_eco_river = 'class="active"';
				if($arr_plant['eco_river'] == 1){ $eco_river = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $eco_river = '<i class="fas fa-times text-danger"></i>'; }
				if($river_photo!=""){ $prv_river_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$river_photo.'\',\''.getRegvalues(605, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_river_photo = ""; }
				$row_eco_river = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_ECO_RIVER'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$eco_river. $prv_river_photo .'</td></tr>';
			// } else{$act_eco_river = ''; $row_eco_river = '';}
			
			// if($arr_plant['eco_shallows']!=''){
				$act_eco_shallows = 'class="active"';
				if($arr_plant['eco_shallows'] == 1){ $eco_shallows = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $eco_shallows = '<i class="fas fa-times text-danger"></i>'; }
				if($shallow_photo!=""){ $prv_shallow_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$shallow_photo.'\',\''.getRegvalues(606, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_shallow_photo = ""; }
				$row_eco_shallows = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_ECO_SHALLOWS'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$eco_shallows. $prv_shallow_photo.'</td></tr>';
			// } else{$act_eco_shallows = ''; $row_eco_shallows = '';}
			
			// if($arr_plant['eco_wells']!=''){
				$act_eco_wells = 'class="active"';
				if($arr_plant['eco_wells'] == 1){ $eco_wells = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $eco_wells = '<i class="fas fa-times text-danger"></i>'; }
				if($well_photo!=""){ $prv_well_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$well_photo.'\',\''.getRegvalues(607, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_well_photo = ""; }
				$row_eco_wells = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_ECO_WELLS'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$eco_wells. $prv_well_photo.'</td></tr>';
			// } else{$act_eco_wells = ''; $row_eco_wells = '';}
			
			if($arr_plant['forest']!=''){
				$act_forest = 'class="active"';
				if($arr_plant['forest'] == 508){ $forest = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $forest = '<i class="fas fa-times text-danger"></i>'; }
				if($forest_photo!=""){ $prv_forest_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$forest_photo.'\',\''.getRegvalues(600, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_forest_photo = ""; }
				$row_forest = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_FOREST'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$forest. $prv_forest_photo.'</td></tr>';
			} else{$act_forest = ''; $row_forest = '';}
			
			if($arr_plant['fire']!=''){
				$act_fire = 'class="active"';
				if($arr_plant['fire'] == 508){ $fire = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $fire = '<i class="fas fa-times text-danger"></i>'; }
				if($fire_photo!=""){ $prv_fire_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$fire_photo.'\',\''.getRegvalues(601, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_fire_photo = ""; }
				$row_fire = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_FIRE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$fire.$prv_fire_photo.'</td></tr>';
			} else{$act_fire = ''; $row_fire = '';}
			
			if($arr_plant['waste']!=''){
				$act_waste = 'class="active"';
				if($arr_plant['waste'] == 508){ $waste = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $waste = '<i class="fas fa-times text-danger"></i>'; }
				if($waste_photo!=""){ $prv_waste_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$waste_photo.'\',\''.getRegvalues(602, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_waste_photo = ""; }
				$row_waste = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_WASTE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$waste.$prv_waste_photo.'</td></tr>';
			} else{$act_waste = ''; $row_waste = '';}
			
			if($arr_plant['irrigation']!=''){
				$act_irrigation = 'class="active"';
				if($arr_plant['irrigation'] == 508){ $irrigation = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $irrigation = '<i class="fas fa-times text-danger"></i>'; }
				if($irrigation_photo!=""){ $prv_irrigation_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$irrigation_photo.'\',\''.getRegvalues(690, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_irrigation_photo = ""; }
				$row_irrigation = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_IRRIGATION'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$irrigation. $prv_irrigation_photo.'</td></tr>';
			} else{$act_irrigation = ''; $row_irrigation = '';}
			
			if($arr_plant['drainage']!=''){
				$act_drainage = 'class="active"';
				if($arr_plant['drainage'] == 508){ $drainage = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $drainage = '<i class="fas fa-times text-danger"></i>'; }
				if($drainage_photo!=""){ $prv_drainage_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$drainage_photo.'\',\''.getRegvalues(691, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_drainage_photo = ""; }
				$row_drainage = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_DRAINAGE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$drainage. $prv_drainage_photo.'</td></tr>';
			} else{$act_drainage = ''; $row_drainage = '';}
			
			if($arr_plant['slope']!=''){
				$act_slope = 'class="active"';
				if($arr_plant['slope'] == 508){ $slope = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $slope = '<i class="fas fa-times text-danger"></i>'; }
				if($slope_photo!=""){ $prv_slope_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$slope_photo.'\',\''.getRegvalues(692, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_slope_photo = ""; }
				$row_slope = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SLOPE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$slope. $prv_slope_photo.'</td></tr>';
			} else{$act_slope = ''; $row_slope = '';}
			
			if($arr_plant['slope_text']!=''){
				$act_slope_text = 'class="active"';
				$row_slope_text = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SLOPE_TEXT'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['slope_text'].'</td></tr>';
			} else{$act_slope_text = ''; $row_slope_text = '';}
			
			if(($row_perimeter!="") OR ($row_eco_river!="") OR ($row_eco_shallows!="") OR ($row_eco_wells!="") 
				OR ($row_irrigation!="") OR ($row_drainage!="") OR ($row_slope!="") OR ($row_slope_text!="") 
				OR ($row_forest!="") OR ($row_fire!="") OR ($row_waste!="")
			){
				$sh_watersource = '';
			} else { $sh_watersource = 'hide'; }
			
			// Inputs
			
			if($arr_plant['pest']!=''){
				$act_pest = 'class="active"';
				$row_pest = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PEST'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $arr_plant['pest'] .'</td></tr>';
			} else{$act_pest = ''; $row_pest = '';}
			
			if($arr_plant['synthetic_fertilizer']!=''){
				$act_synthetic_fertilizer = 'class="active"';
				if($fertilizer_photo!=""){ $prv_fertilizer_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$fertilizer_photo.'\',\''.getRegvalues(596, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_fertilizer_photo = ""; }
				$row_synthetic_fertilizer = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SYNT_FERTILIZER'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['synthetic_fertilizer'], $lang['DB_LANG_stat']) . $prv_fertilizer_photo .'</td></tr>';
			} else{$act_synthetic_fertilizer = ''; $row_synthetic_fertilizer = '';}
			
			if($arr_plant['synthetic_herbicides']!=''){
				$act_synthetic_herbicides = 'class="active"';
				if($herbicides_photo!=""){ $prv_herbicides_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$herbicides_photo.'\',\''.getRegvalues(597, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_herbicides_photo = ""; }
				$row_synthetic_herbicides = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SYNT_HERBICIDES'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['synthetic_herbicides'], $lang['DB_LANG_stat']) . $prv_herbicides_photo .'</td></tr>';
			} else{$act_synthetic_herbicides = ''; $row_synthetic_herbicides = '';}
			
			if($arr_plant['synthetic_pesticide']!=''){
				$act_synthetic_pesticide = 'class="active"';
				if($pesticide_photo!=""){ $prv_pesticide_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$pesticide_photo.'\',\''.getRegvalues(598, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_pesticide_photo = ""; }
				$row_synthetic_pesticide = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SYNT_PESTICIDE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['synthetic_pesticide'], $lang['DB_LANG_stat']) . $prv_pesticide_photo .'</td></tr>';
			} else{$act_synthetic_pesticide = ''; $row_synthetic_pesticide = '';}
			
			if(($row_pes!="") OR ($row_synthetic_fertilizer!="") OR ($row_synthetic_herbicides!="") OR ($row_synthetic_pesticide!="") ){
				$sh_input = '';
			} else { $sh_input = 'hide'; }
			
			// Plantation state

			if($arr_plant['adjoining_cultures']!=''){
				$act_adjoining_cultures = 'class="active"';
				
				$adjoining_cultures="";
				$ad_culturesData = explode(",", $arr_plant['adjoining_cultures']);
				$lenghtC = sizeof($ad_culturesData);
				
				for ($i = 0; $i < $lenghtC; $i++) {
					$sqlClt = "SELECT * FROM regvalues WHERE id_regvalue = " . $ad_culturesData[$i];
					$result_sqlClt = pg_query($conn, $sqlClt);
					$arr_sqlClt = pg_fetch_assoc($result_sqlClt);
						
					if($lang['DB_LANG_stat']=='en') {
						$adjoining_cultures.=$arr_sqlClt['cvalue'].', ';
					} else {
						$adjoining_cultures.=$arr_sqlClt['cvalue'.$lang['DB_LANG_stat']].', ';
					}
				}
				
				if($adjcultures_photo!=""){ $prv_adjcultures_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$adjcultures_photo.'\',\''.getRegvalues(599, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_adjcultures_photo = ""; }
				$row_adjoining_cultures = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_ADJOINING_CULTURES'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$adjoining_cultures. $prv_adjcultures_photo .'</td></tr>';
			} else{$act_adjoining_cultures = ''; $row_synthetic_pesticide = '';}
			
			if($arr_plant['intercropping']!=''){
				$act_intercropping = 'class="active"';
				$row_intercropping = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_INTERCROPPING'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['intercropping'].'</td></tr>';
			} else{$act_intercropping = ''; $row_intercropping = '';}
				
			if($arr_plant['harvest']!=''){
				$act_harvest = 'class="active"';
					
				$harvestData = explode(",", $arr_plant['harvest']);
				$lenght = sizeof($harvestData);
				
				for ($i = 0; $i < $lenght; $i++) {
					if($harvestData[$i] == 'jan') { $harvest_jan = $lang['JANUARY'] .', '; }
					if($harvestData[$i] == 'feb') { $harvest_feb = $lang['FEBRUARY'] .', '; }
					if($harvestData[$i] == 'mar') { $harvest_mar = $lang['MARCH'] .', '; }
					if($harvestData[$i] == 'apr') { $harvest_apr = $lang['APRIL'] .', '; }
					if($harvestData[$i] == 'may') { $harvest_may = $lang['MAY'] .', '; }
					if($harvestData[$i] == 'jun') { $harvest_jun = $lang['JUNE'] .', '; }
					if($harvestData[$i] == 'jul') { $harvest_jul = $lang['JULY'] .', '; }
					if($harvestData[$i] == 'aug') { $harvest_aug = $lang['AUGUST'] .', '; }
					if($harvestData[$i] == 'sep') { $harvest_sep = $lang['SEPTEMBER'] .', '; }
					if($harvestData[$i] == 'oct') { $harvest_oct = $lang['OCTOBER'] .', '; }
					if($harvestData[$i] == 'nov') { $harvest_nov = $lang['NOVEMBER'] .', '; }
					if($harvestData[$i] == 'dec') { $harvest_dec = $lang['DECEMBER'] .', '; }
				}
				
				$harvest=$harvest_jan.$harvest_feb.$harvest_mar.$harvest_apr.$harvest_may.$harvest_jun.$harvest_jul.$harvest_aug.$harvest_sep.$harvest_oct.$harvest_nov.$harvest_dec;
				
				$row_harvest = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_HARVEST'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$harvest.'</td></tr>';
			} else{$act_harvest = ''; $row_harvest = '';}
			
			if($arr_plant['rating']!=''){
				$act_rating = 'class="active"';
				$row_rating = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_RATING'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['rating'].'/<b>10</b></td></tr>';
			} else{$act_rating = ''; $row_rating = '';}
		
			if(($row_adjoining_cultures!="") OR ($row_intercropping!="") OR ($row_harvest!="") OR
				($row_name_manager!="") OR ($row_manager_civil!="") OR ($row_manager_phone!="") OR ($row_number_staff_permanent!="") OR 
				($row_number_staff_temporary!="") OR ($row_notes!="")
			){
				$sh_plant_state = '';
			} else { $sh_plant_state = 'hide'; }
			
			
			$environementPictures="";
			
			$sql_plantPic = "SELECT id_plantdoc, doc_link, description FROM plantation_docs WHERE plantation_id = $id_plantation 
			AND doc_link IS NOT NULL AND coordx IS NOT NULL AND coordy IS NOT NULL
			AND doc_type != 502
			AND doc_type != 649
			AND doc_type != 655";
			$result_plantPic = pg_query($conn, $sql_plantPic);
			
			$i=0;
			$img="";
			$id_plantdoc="";
		
			while ($arr_plantPic = pg_fetch_assoc($result_plantPic)){
				if($i == 0){ $id_plantdoc=$arr_plantPic['id_plantdoc']; }
				$img .= '<img class="flky_env" style="height:120px;" src="' .$arr_plantPic['doc_link']. '" alt="' .$arr_plantPic['description']. '" id="' .$arr_plantPic['id_plantdoc']. '">';
				$i++;
			}
			
			$sql_plantDoc = "SELECT * FROM plantation_docs WHERE plantation_id = $id_plantation AND doc_type = 655";
			$result_plantDoc = pg_query($conn, $sql_plantDoc);
			$arr_plantDoc = pg_fetch_assoc($result_plantDoc);
			
			if($arr_plantDoc) { 
				$fp = $_SERVER['DOCUMENT_ROOT']."/ic/img/environment_document/". $id_plantation .".pdf";
				
				if (file_exists($fp)) {
					$environementPictures .='<div style="padding:5px;">
						<div class="row" style="padding-top:5px; background-color:#e4e4e4;">
							<div class="col-md-10"><label><a href="https://icoop.live/ic/img/environment_document/'. $id_plantation .'.pdf" target="_blank"><i class="fa fa-file"></i> Action Initiale ('.$arr_plant['code_parcelle'].') </a></label></div>
							<div class="col-md-2 text-right"><a href="javascript:deleteEnvDoc(\''.$id_plantation.'\',\''.$arr_plantDoc['id_plantdoc'].'\')" onclick="return confirm(\'Voulez vous vraiment supprimer cet document ?\');"><i class="fa fa-trash"></i></a></div>
						</div>
					</div>';
				} 
			}
		
			if($i!=0){
				$environementPictures .='<div style="position: relative;">
					<div class="main-carousel">'. $img .'</div>
					<button class="button flickity-button custum--flickity --prev">
						<i class="fa fa-chevron-left" style="font-size: 26px;"></i>
					</button>
				
					<button class="button flickity-button custum--flickity --next">
						<i class="fa fa-chevron-right" style="font-size: 26px;"></i>
					</button>
				</div>';
			}
			
			$sqlP="SELECT doc_link FROM public.plantation_docs WHERE plantation_id = $id_plantation AND doc_type = 655";
			$rstP = pg_query($conn, $sqlP);
			$arrP = pg_fetch_assoc($rstP);
			
			$doc_link = $arrP['doc_link'];	
			
			$fm_doc="";
			if($id_company == 19) {
				if($doc_link!="") {
					$fm_doc = '<a class="pull-right" href="#" onclick="showEnvDocPDF(\''.$doc_link.'\');"><i class="fas fa-print"></i></a>';
				} else {
					$fm_doc = '<a class="pull-right" href="#" onclick="createEnvDocPDF(\''.$id_plantation.'\');"><i class="fas fa-print"></i></a>';
				}
			}
			
			$environment .= '<div class="row" style="margin-bottom:5px;">
				<div class="col-md-12">'. $fm_doc .'</div>
			  </div> 
			  <div class="card-wrapper">
				<div class="contact-box" style="border:none;">
					<table style="width:100%; font-size:12px;">
						<tr><td style="width:30%; padding:3px;" align="right" class="bg-success '.$sh_watersource.'"></td>
						<td style="padding:3px 3px 3px 10px;" class="bg-success '.$sh_watersource.'">'.$lang['CONT_WATER_SOURCE'].'</td></tr>
						<tr><td colspan="2" class="'.$sh_watersource.'"><div style="height:5px;"></div></td></tr>
							
						'.$row_irrigation.$row_drainage.$row_slope.$row_slope_text.$row_eco_river.$row_eco_shallows.$row_eco_wells.$row_perimeter.$row_forest.$row_fire.$row_waste.'
					</table> 
					
					<table style="width:100%; font-size:12px;">
						<tr><td style="width:30%; padding:3px;" align="right" class="bg-success '.$sh_input.'"></td>
						<td style="padding:3px 3px 3px 10px;" class="bg-success '.$sh_input.'">'.$lang['CONT_INPUT'].'</td></tr>
						<tr><td colspan="2" class="'.$sh_input.'"><div style="height:5px;"></div></td></tr>
							
						'.$row_pest.$row_synthetic_fertilizer.$row_synthetic_herbicides.$row_synthetic_pesticide.'
					</table>
					
					<table style="width:100%; font-size:12px;">
						<tr><td style="width:30%; padding:3px;" align="right" class="bg-success '.$sh_plant_state.'"></td>
						<td style="padding:3px 3px 3px 10px;" class="bg-success '.$sh_plant_state.'">'.$lang['CONT_STATE_OF_PLANTATION'].'</td></tr>
						<tr><td colspan="2" class="'.$sh_plant_state.'"><div style="height:5px;"></div></td></tr>
							
						'.$row_adjoining_cultures.$row_intercropping.$row_harvest.$row_rating.'
					</table>
					
					<div class="clearfix"></div>
				</div>
			</div>';
			
			$dom = $environment.'##'.$environementPictures.'##'.$id_plantdoc;
			
		break;
		
		
		case "selected_plantation_busiFinace_details":
		
			$bus_finance="";
			
			$id_plantation=$_GET['gid_plantation'];
		
			$sql_plant = "SELECT * FROM v_plantation WHERE gid_plantation=$id_plantation";

			$result_plant = pg_query($conn, $sql_plant);
			$arr_plant = pg_fetch_assoc($result_plant);
			
			$sql_title_deedP = "SELECT doc_link FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 626 ORDER By id_plantdoc DESC LIMIT 1";
			$result_title_deedP = pg_query($conn, $sql_title_deedP);
			$arr_title_deedP = pg_fetch_assoc($result_title_deedP);
			$title_deed_photo = $arr_title_deedP['doc_link'];
			
			
			// Property
			
			if($arr_plant['title_deed']!=''){
				$act_title_deed = 'class="active"';
				if($arr_plant['title_deed']==1){ $title_deed = $lang['CONT_ATTESTATION_VILLAGOISE']; }
				elseif($arr_plant['title_deed']==2){ $title_deed = $lang['CONT_TITRE_FONCIER']; }
				elseif($arr_plant['title_deed']==3){ $title_deed = $lang['CONT_PROPRIETAIRE_TERRIAIN']; }
				else { $title_deed = '<i class="fas fa-times text-danger"></i>'; }
				if($title_deed_photo!=""){ $prv_title_deed_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$title_deed_photo.'\',\''.getRegvalues(626, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_title_deed_photo = ""; }
				$row_title_deed = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_TITTLE_DEED'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$title_deed.$prv_title_deed_photo.'</td></tr>';
			} else{$act_title_deed = ''; $row_title_deed = '';}
			
			if($arr_plant['numb_feet']!=''){
					$act_numb_feet = 'class="active"';
					$row_numb_feet = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NUMB_FEET'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. $arr_plant['numb_feet'] .'</td></tr>';
				} else{$act_numb_feet = ''; $row_numb_feet = '';}
				
				if($arr_plant['replanting']!=''){
					$act_replanting = 'class="active"';
					$row_replanting = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_REPLANTING'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['replanting'], $lang['DB_LANG_stat']) .'</td></tr>';
				} else{$act_replanting = ''; $row_replanting = '';}
				
				if($arr_plant['year_to_replant']!=''){
					$act_year_to_replant = 'class="active"';
					$row_year_to_replant = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_YEAR_EXTENSION'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. $arr_plant['year_to_replant'] .'</td></tr>';
				} else{$act_year_to_replant = ''; $row_year_to_replant = '';}
				
				if($arr_plant['extension']!=''){
					$act_extension = 'class="active"';
					$row_extension = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_EXTENSION'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['extension'], $lang['DB_LANG_stat']) .'</td></tr>';
				} else{$act_extension = ''; $row_extension = '';}  
				
				if($arr_plant['year_extension']!=''){
					$act_year_extension = 'class="active"';
					$row_year_extension = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_YEAR_EXTENSION'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. $arr_plant['year_extension'] .'</td></tr>';
				} else{$act_year_extension = ''; $row_year_extension = '';}  
				
			if($arr_plant['property']!=''){
				$act_property = 'class="active"';
				if($arr_plant['property']==1){ $property = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $property = '<i class="fas fa-times text-danger"></i>'; }
				$row_property = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PROPERTY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$property.'</td></tr>';
			} else{$act_property = ''; $row_property = '';}
			
			if($arr_plant['lands_rights_conflict']!=''){
				$act_lands_rights_conflict = 'class="active"';
				if($arr_plant['lands_rights_conflict']==508){ $lands_rights_conflict = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $lands_rights_conflict = '<i class="fas fa-times text-danger"></i>'; }
				$row_lands_rights_conflict = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_LANDS_RIGHTS_CONFLICT'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$lands_rights_conflict.'</td></tr>';
			} else{$act_lands_rights_conflict = ''; $row_lands_rights_conflict = '';}
			
			if($arr_plant['lands_rights_conflict_note']!=''){
				$act_lands_rights_conflict_note = 'class="active"';
				$row_lands_rights_conflict_note = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NOTES'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_plant['lands_rights_conflict_note'].'</td></tr>';
			} else{$act_lands_rights_conflict_note = ''; $row_lands_rights_conflict_note = '';}
			
				if($arr_plant['area']!=''){
					$act_plantarea = 'class="active"';
					$row_plantarea = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_AREA_ESTIMATE'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.number_format($arr_plant['area'], 2, ',', ' ').' Ha</td></tr>';
				}else{$act_plantarea = ''; $row_plantarea = '';}

				if($arr_plant['area_acres']!=''){
					$act_area_acres = 'class="active"';
					$row_area_acres = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_AREA_ACRES'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.number_format($arr_plant['area_acres'],3).' Acres</td></tr>';
				} else{$act_area_acres = ''; $row_area_acres = '';}
				
				// if($arr_plant['surface_ha']!=''){
					// $act_surface_ha = 'class="active"';
					// $row_surface_ha = '<tr><td style="width:35%;" align="right">
						// <label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SURFACE_HA'].' : </label></td>
						// <td align="left" style="padding-left:10px;">'.number_format($arr_plant['surface_ha'],4).' Ha</td></tr>';
				// } else{$act_surface_ha = ''; $row_surface_ha = '';}
				
				if($arr_plant['yield_estimate']!=''){
					$act_yield_estimate = 'class="active"';
					$row_yield_estimate = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_YIELD_ESTIMATE'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['yield_estimate'].'</td></tr>';
				} else{$act_yield_estimate = ''; $row_yield_estimate = '';} 
				
				if($arr_plant['year_creation']!=''){
					$act_year_creation = 'class="active"';
					$row_year_creation = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_FIRST_USE'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['year_creation'].'</td></tr>';
				} else{$act_year_creation = ''; $row_year_creation = '';}
				
				if($arr_plant['property']!=''){
					$act_property = 'class="active"';
					$row_property = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PROPERTY'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['property_name'].'</td></tr>';
				} else{$act_property = ''; $row_property = '';}
				
				if($arr_plant['title_deed']!=''){
					$act_title_deed = 'class="active"';
					if($title_deed_photo!=""){ $prv_title_deed_photo = '<a class="pull-right" onclick="plantImgPreview(\''.$title_deed_photo.'\',\''.getRegvalues(626, $lang['DB_LANG_stat']).'\');" href="#"><i class="far fa-eye text-danger"></i></a>'; } else { $prv_title_deed_photo = ""; }
					$row_title_deed = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_TITLE_DEED'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['title_deed_name'].$prv_title_deed_photo.'</td></tr>';
				} else{$act_title_deed = ''; $row_title_deed = '';} 
				
				if($arr_plant['variety']!=''){
					$act_variety = 'class="active"';
					$row_variety = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['DB_plantation_culture_variety'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['variety'].'</td></tr>';
				} else{$act_variety = ''; $row_variety = '';}
				
				if($arr_plant['seed_type']!=''){
					$act_seed_type = 'class="active"';
					$row_seed_type = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SEED_TYPE'].' : </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_plant['seed_type_name'].'</td></tr>';
				}else{$act_seed_type = ''; $row_seed_type = '';}
				
				if($arr_plant['road_access']!=''){
					$act_road_access = 'class="active"';
					$row_road_access = '<tr><td style="width:35%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_ROAD_ACCES'].' : </label></td>
						<td align="left" style="padding-left:10px;">'. getRegvalues($arr_plant['road_access'], $lang['DB_LANG_stat']) .'</td></tr>';
				}else{$act_road_access = ''; $row_road_access = '';}
				
				if(($row_plantarea!="") OR ($row_area_acres!="") OR ($row_extension!="") OR ($row_yield_estimate!="") OR 
				($row_year_creation!="") OR ($row_property!="") OR ($row_title_deed!="") OR ($row_variety!="") OR ($row_year_to_replant!="") OR 
				($row_seed_type!="") OR ($row_lands_rights_conflict!="") OR ($row_lands_rights_conflict_note!="") OR ($row_replanting!="") OR ($row_road_access!="")) {
					$sh_input = '';
				} else { $sh_input = 'hide'; }
				
			
			$bus_finance .='<div class="card-wrapper">
				<div class="contact-box" style="border:none;">
					<table style="width:100%; font-size:12px;">
						<tr><td style="width:30%; padding:3px;" align="right" class="bg-success '.$sh_input.'"></td>
						<td style="padding:3px 3px 3px 10px;" class="bg-success '.$sh_input.'">'.$lang['CONT_PROPERTY'].'</td></tr>
						<tr><td colspan="2" class="'.$sh_input.'"><div style="height:5px;"></div></td></tr>
						'.$row_numb_feet.$row_plantarea.$row_area_acres.$row_yield_estimate.$row_year_creation.$row_property.$row_extension.$row_year_extension.$row_replanting.$row_year_to_replant.$row_title_deed.$row_variety.$row_seed_type.$row_lands_rights_conflict.$row_lands_rights_conflict_note.$row_road_access.'
					</table>

				<div class="clearfix"></div>
				</div>
			</div>';
			
			
			$sql_plantDoc = "SELECT * FROM plantation_docs WHERE plantation_id=$id_plantation AND doc_type = 626 AND doc_link IS NOT NULL ";

			$result_plantDoc = pg_query($conn, $sql_plantDoc);
			

			$i=0;
			$img="";
			$bus_finance_doc="";
			while ($arr_plantDoc = pg_fetch_assoc($result_plantDoc)){
				
				$img .= '<div class="col-lg-6 col-md-6 col-xs-6 thumb">
					<a class="thumbnail" href="#" data-image-id="'. getRegvalues($arr_plantDoc['doc_type'], $lang['DB_LANG_stat']) .'" data-toggle="modal" data-title=""
						 data-image="' .$arr_plantDoc['doc_link']. '" style="margin-bottom:5px;"
						 data-target="#image-gallery">
						<img class="img-thumbnail"
						 src="' .$arr_plantDoc['doc_link']. '"
						 alt="Another alt text">
					</a>
					<div class="text-center" style="margin-bottom:10px;">'. getRegvalues($arr_plantDoc['doc_type'], $lang['DB_LANG_stat']) .'</div>
				</div>';	
				
				$i++;
			}
			
			if($i!=0){
				$bus_finance_doc='<div>
					<div class="row">
						'. $img .'
					</div>
				</div>';
			}
			
			$dom = $bus_finance.'##'.$bus_finance_doc;
	
		break;
		
		
		case "update_plantation_certificate":

			$id_plantation = $_GET["id_plantation"];
			
			if(isset($_GET["globalgap"])){
				$globalgap = $_GET["globalgap"];
				$globalgap_edit = "globalgap='$globalgap',";
			} else { $globalgap_edit = ""; }
			
			if(isset($_GET["cert_approved_date"])){
				$cert_approved_date = $_GET["cert_approved_date"];
				$cert_approved_date_edit = "cert_approved_date='$cert_approved_date',";
			} else { $cert_approved_date_edit = ""; }
			
			if(isset($_GET["cert_notes"])){
				$cert_notes = $_GET["cert_notes"];
				$cert_notes_edit = "cert_notes='$cert_notes',";
			} else { $cert_notes_edit = ""; }
	
			$cert_approved_by = $_SESSION['id_contact'];
			
			$sql_stats = "UPDATE public.plantation
			   SET $globalgap_edit $cert_approved_date_edit $cert_notes_edit
				cert_approved_by='$cert_approved_by'
			WHERE id_plantation ='$id_plantation'";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##Certificate updated successfully";
			} else {
				$dom="0##Unable to update certificate";
			}

		break;
		
		
		case "check_out":
		
			$id_plantation = $_GET['id_plantation'];
			$check_out = $_GET['check_out'];
			$id_user = $_SESSION['id_contact'];
			$check_out_date = gmdate("Y/m/d H:i");
			
			$sql_stats = "UPDATE public.plantation SET check_out=$check_out,
				check_out_date='$check_out_date', check_out_by=$id_user
			WHERE id_plantation ='$id_plantation'";

			$result = pg_query($conn, $sql_stats);

			if($result){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
	}
}


echo $dom;

