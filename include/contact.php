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
		
		case "organisation_details":
		
			$id_contact = $_GET['id_contact'];
		
			$sql = "SELECT * FROM contact_profile WHERE id_contact = $id_contact";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
		
			$dom = '<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default" style="margin-bottom: 20px;padding: 5px 10px;width:100%;opacity:0.8">
						<small style="color:#1ab394;">'.$lang['ORG_CERTIFICATIONS'].'</small>
						<div style="padding-bottom:10px">'.$arr['e3'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_PRODUCTION_CAMPAGNE_2019'].'</small>
						<div style="padding-bottom:10px">'.$arr['e1'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_PRODUCTION_CAMPAGNE_2020'].'</small>
						<div style="padding-bottom:10px">'.$arr['e1_01'].'</div>
				
						<small style="color:#1ab394;">'.$lang['ORG_LIVRAISON_CAMPAGNE'].'</small>
						<div style="padding-bottom:10px">'.$arr['e1_02'].'</div>
				
						<small style="color:#1ab394;">'.$lang['ORG_CLIENTS_CAMPAGNE'].'</small>
						<div style="padding-bottom:10px">'.$arr['e2'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_DIFFICULTES_CAMPAGNE'].'</small>
						<div style="padding-bottom:10px">'.$arr['e2_01'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_NB_divRODUCTEURS'].'</small>
						<div style="padding-bottom:10px">'.$arr['e2_02'].'</div>
						
						<small style="color:#1ab394;">'.$lang['ORG_SUdivERFICE_TT'].'</small>
						<div style="padding-bottom:10px">'.$arr['e2_03'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_FORMATION_EFFECTUEE'].'</small>
						<div style="padding-bottom:10px">'.$arr['e2_04'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_NB_ENCADREURS'].'</small>
						<div style="padding-bottom:10px">'.$arr['e2_05'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_GEOLOCALISATION_PLANTEURS'].'</small>
						<div style="padding-bottom:10px">'.$arr['e2_06'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_ZONE_ACTIVITEES'].'</small>
						<div>'.$arr['e2_07'].'</div>
						<div>'.$arr['e2_08'].'</div>
					</div>
					
					<div class="panel panel-default" style="margin-bottom: 20px; padding: 5px 10px;width:100%;opacity:0.8">
						<small style="color:#1ab394;">'.$lang['ORG_LOGISTIQUE_CAMIONS'].'</small>
						<div style="padding-bottom:10px">'.$arr['e4'].'</div>
						
						<small style="color:#1ab394;">'.$lang['ORG_LOGISTIQUE_REMORQUES'].'</small>
						<div style="padding-bottom:10px">'.$arr['e4_01'].'</div>
						
						<small style="color:#1ab394;">'.$lang['ORG_FINANCEMENT'].'</small>
						<div style="padding-bottom:10px">'.$arr['e4_02'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_NO_ENTREPOT_STOCKAGE'].'</small>
						<div style="padding-bottom:10px">'.$arr['e4_03'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_CAPACITE_STOCKAGE'].'</small>
						<div style="padding-bottom:10px">'.$arr['e4_04'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_AERATION_ENTREPOT'].'</small>
						<div style="padding-bottom:10px">'.$arr['e4_05'].'</div>
				
						<small style="color:#1ab394;">'.$lang['ORG_SYSTEM_STOCKAGE'].'</small>
						<div style="padding-bottom:10px">'.$arr['e4_06'].'</div>
					
						<small style="color:#1ab394;">'.$lang['ORG_PHYTOSANITAIRE_ENTREPOT'].'</small>
						<div>'.$arr['e4_07'].'</div>
						<div>'.$arr['e4_08'].'</div>
					</div>
					
					<div class="panel panel-default" style="margin-bottom: 20px; padding: 5px 10px;width:100%;opacity:0.9; margin-bottom:45px;">
						<small style="color:#1ab394;">'.$lang['ORG_ACHATS_PRODUCTEURS'].'</small>
						<div style="padding-bottom:10px">'.$arr['e4_09'].'</div>
						
						<small style="color:#1ab394;">'.$lang['ORG_MOYEN_FERMENTATION'].'</small>
						<div style="padding-bottom:10px">'.$arr['e3_01'].'</div>
						
						<small style="color:#1ab394;">'.$lang['ORG_OSERVATION'].'</small>
						<div>'.$arr['e3_02'].'</div>
					</div>
				</div>
			</div>';
		
		break;
		
		
		case "farmer_town_marker":
		
			$id_contact = $_GET["id_contact"];
			
			$sql = "SELECT id_town FROM v_icw_contacts WHERE id_contact = $id_contact";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			$id_town = $arr['id_town'];
			
			$sql2 = "SELECT id_town, name_town, x, y FROM towns WHERE id_town = $id_town";
			$result2 = pg_query($conn, $sql2);
			$arr2 = pg_fetch_assoc($result2);
			
			$dom = $arr2['name_town'].'##'.$arr2['x'].'##'.$arr2['y'];
			
		break;
		
		
		case "location_markers":
		
			$id_contact = $_GET["id_contact"];
			
			$sql = "SELECT coordx, coordy FROM v_icw_contacts WHERE id_contact = $id_contact";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			$dom = $arr['coordx'].'@@'.$arr['coordy'];
			
		break;
		
		
		case "update_household":

			$id_household = $_GET["id_household"];
			
			if(isset($_GET["firstname"])){
				$firstname = pg_escape_string($_GET["firstname"]);
				$firstname_edit = "firstname='{$firstname}',";
			} else { $firstname_edit = ""; }
			
			if(isset($_GET["lastname"])){
				$lastname = pg_escape_string($_GET["lastname"]);
				$lastname_edit = "lastname='{$lastname}',";
			} else { $lastname_edit = ""; }
			
			if(isset($_GET["birth_year"])){
				$birth_year = $_GET["birth_year"];
				$birth_year_edit = "birth_year='$birth_year',";
			} else { $birth_year_edit = ""; }
			
			if(isset($_GET["relation"])){
				$relation = $_GET["relation"];
				$relation_edit = "relation='$relation',";
			} else { $relation_edit = ""; }
			
			if(isset($_GET["graduate_primary"])){
				$graduate_primary = $_GET["graduate_primary"];
				$graduate_primary_edit = "graduate_primary='$graduate_primary',";
			} else { $graduate_primary_edit = ""; }
			
			if(isset($_GET["graduate_secondary"])){
				$graduate_secondary = $_GET["graduate_secondary"];
				$graduate_secondary_edit = "graduate_secondary='$graduate_secondary',";
			} else { $graduate_secondary_edit = ""; }
			
			if(isset($_GET["graduate_tertiary"])){
				$graduate_tertiary = $_GET["graduate_tertiary"];
				$graduate_tertiary_edit = "graduate_tertiary='$graduate_tertiary',";
			} else { $graduate_tertiary_edit = ""; }
			
			if(isset($_GET["working_on_farm"])){
				$working_on_farm = $_GET["working_on_farm"];
				$working_on_farm_edit = "working_on_farm='$working_on_farm',";
			} else { $working_on_farm_edit = ""; }
			
			if(isset($_GET["working_off_farm"])){
				$working_off_farm = $_GET["working_off_farm"];
				$working_off_farm_edit = "working_off_farm='$working_off_farm',";
			} else { $working_off_farm_edit = ""; }
			
			if(isset($_GET["gender"])){
				$gender = $_GET["gender"];
				$gender_edit = "gender='$gender',";
			} else { $gender_edit = ""; }
			
			if(isset($_GET["read_write"])){
				$read_write = $_GET["read_write"];
				$read_write_edit = "read_write='$read_write',";
			} else { $read_write_edit = ""; }
			
			if(isset($_GET["schooling"])){
				$schooling = $_GET["schooling"];
				$schooling_edit = "schooling='$schooling',";
			} else { $schooling_edit = ""; }

			$modified_by = $_SESSION['id_user'];
			$modified_date = gmdate("Y/m/d H:i");

			$sql_stats = "UPDATE public.contact_household
			   SET $firstname_edit $lastname_edit $birth_year_edit $relation_edit $graduate_primary_edit
				   $graduate_secondary_edit $graduate_tertiary_edit $working_on_farm_edit $working_off_farm_edit
				   $gender_edit $read_write_edit $schooling_edit modified_date='$modified_date', modified_by='$modified_by'
			WHERE id_household ='$id_household'";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##Contact household updated successfully";
			} else {
				$dom="0##Unable to update contact household";
			}

		break;
		
		
		case "selected_household_details":
		
			$conf = $_GET['conf'];
			$id_household = $_GET['id_household'];
			
			$sql_hh = "SELECT * FROM v_contact_household WHERE id_household=$id_household";

			$result_hh = pg_query($conn, $sql_hh);
			$arr_hh = pg_fetch_assoc($result_hh);
		
			if($arr_hh['id_relation'] == 551) {
				$id = $arr_hh['contact_id'];
				$sql_avatar = "SELECT doc_link FROM contact_docs WHERE contact_id = $id 
					AND doc_type = 154  AND id_household IS NULL
				ORDER BY id_condoc DESC LIMIT 1";
				$result_avatar = pg_query($conn, $sql_avatar);
				$arr_avatar = pg_fetch_assoc($result_avatar);
				
				if($arr_avatar['doc_link']!="") {
					$hh_avatar = $arr_avatar['doc_link'];
				} else {
					$hh_avatar = './img/household.png';
				}
			
			} else {
				if($arr_hh['avatar_path']!=""){
					$hh_avatar = $arr_hh['avatar_path'];
				} else { $hh_avatar = './img/household.png'; }
			}
			
			if($arr_hh['firstname']!=''){
				$act_firstname_h = 'class="active"';
				$row_firstname_h = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_FIRSTNAME'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_hh['firstname'].'</td></tr>';
			} else{$act_firstname_h = ''; $row_firstname_h = '';}
			
			if($arr_hh['lastname']!=''){
				$act_lastname_h = 'class="active"';
				$row_lastname_h = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_LASTNAME'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_hh['lastname'].'</td></tr>';
			} else{$act_lastname_h = ''; $row_lastname_h = '';}
		
			if($arr_hh['birth_year']!=''){
				$act_birth_year_h = 'class="active"';
				$row_birth_year_h = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BIRTH_YEAR'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_hh['birth_year'].'</td></tr>';
			} else{$act_birth_year_h = ''; $row_birth_year_h = '';}  
			
			if($arr_hh['relation']!=''){
				$act_relation_h = 'class="active"';
				$row_relation_h = '<tr><td style="width:35%;" align="right"> 
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_RELATION'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. getRegvalues($arr_hh['id_relation'], $lang['DB_LANG_stat']) .'</td></tr>';
			} else{$act_relation_h = ''; $row_relation_h = '';}  
			
			if($arr_hh['graduate_primary']!=''){
				$act_graduate_primary_h = 'class="active"';
				if($arr_hh['id_graduate_primary'] == 577) { $graduate_primary = '<i class="fas fa-times text-danger"></i>'; }
				else { $graduate_primary = getRegvalues($arr_hh['id_graduate_primary'], $lang['DB_LANG_stat']); }
				
				$row_graduate_primary_h = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GRADUATE_PRIMARY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $graduate_primary .'</td></tr>';
			} else{$act_graduate_primary_h = ''; $row_graduate_primary_h = '';}  
			
			if($arr_hh['graduate_secondary']!=''){
				$act_graduate_secondary_h = 'class="active"';
				if($arr_hh['id_graduate_secondary'] == 577){ $graduate_secondary = '<i class="fas fa-times text-danger"></i>'; } 
				else { $graduate_secondary = getRegvalues($arr_hh['id_graduate_secondary'], $lang['DB_LANG_stat']); }
				
				$row_graduate_secondary_h = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GRADUATE_SECONDARY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $graduate_secondary .'</td></tr>';
			} else{$act_graduate_secondary_h = ''; $row_graduate_secondary_h = '';}  
			
			if($arr_hh['graduate_tertiary']!=''){
				$act_graduate_tertiary_h = 'class="active"';
				if($arr_hh['id_graduate_tertiary'] == 577) { $graduate_tertiary = '<i class="fas fa-times text-danger"></i>'; }
				else { $graduate_tertiary = getRegvalues($arr_hh['id_graduate_tertiary'], $lang['DB_LANG_stat']); }
				
				$row_graduate_tertiary_h = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GRADUATE_TERTIARY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $graduate_tertiary .'</td></tr>';
			} else{$act_graduate_tertiary_h = ''; $row_graduate_tertiary_h = '';}

			if($arr_hh['working_on_farm']!=''){
				$act_working_on_farm_h = 'class="active"';
				if($arr_hh['working_on_farm'] == 'Yes'){ $working_on_farm = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } 
				else { $working_on_farm = '<i class="fas fa-times text-danger"></i>'; }

				$row_working_on_farm_h = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_WORKING_ON_FARM'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $working_on_farm .'</td></tr>';
			} else{$act_working_on_farm_h = ''; $row_working_on_farm_h = '';}		

			if($arr_hh['working_off_farm']!=''){
				$act_working_off_farm_h = 'class="active"';
				if($arr_hh['working_off_farm'] == 'Yes'){ $working_off_farm = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } 
				else { $working_off_farm = '<i class="fas fa-times text-danger"></i>'; }
				
				$row_working_off_farm_h = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_WORKING_OFF_FARM'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $working_off_farm .'</td></tr>';
			} else{$act_working_off_farm_h = ''; $row_working_off_farm_h = '';}	
			
			if($arr_hh['gender']!=''){
				$act_gender_h = 'class="active"';
				$row_gender_h = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GENDER'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. getRegvalues($arr_hh['id_gender'], $lang['DB_LANG_stat']) .'</td></tr>';
			} else{$act_gender_h = ''; $row_gender_h = '';}	
			
			if($arr_hh['read_write']!=''){
				$act_read_write = 'class="active"';
				if($arr_hh['read_write'] == 508){ $read_write = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $read_write = '<i class="fas fa-times text-danger"></i>'; }
				
				$row_read_write = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_READ_WRITE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $read_write .'</td></tr>';
			} else{$act_read_write = ''; $row_read_write = '';}	
			
			if($arr_hh['schooling']!=''){
				$act_schooling = 'class="active"';
				if($arr_hh['schooling'] == 508){ $schooling = '<i class="fas fa-check" style="color:#1ab394;"></i>'; } else { $schooling = '<i class="fas fa-times text-danger"></i>'; }
				
				$row_schooling = '<tr><td style="width:35%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SCHOOLING'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $schooling .'</td></tr>';
			} else{$act_schooling = ''; $row_schooling = '';}	
			
			// Relation
			$sql_hh_relation = "select id_regvalue, cvalue, cvaluefr from regvalues where id_register=271 order by cvalue";
			$rs_hh_relation  = pg_query($conn, $sql_hh_relation);

			$cvalue_r="";
			$hh_relation_list = '<option value="">-- Select relation --</option>';
			
			while ($row_hh_relation = pg_fetch_assoc($rs_hh_relation)) {
				if($lang['DB_LANG_stat'] == 'fr'){ $cvalue_r=$row_hh_relation['cvaluefr']; } else { $cvalue_r=$row_hh_relation['cvalue']; }
	
				if($arr_hh['id_relation'] == $row_hh_relation['id_regvalue']){ $sel_hh_relation='selected="selected"'; } else { $sel_hh_relation=''; }
				$hh_relation_list .= '<option value="'.$row_hh_relation['id_regvalue'] .'" '.$sel_hh_relation.'>'. $cvalue_r .'</option>';
			}
			
			// Graduate 
			$sql_hh_graduate = "select id_regvalue, cvalue, cvaluefr from regvalues where id_register=273 order by cvalue";
			$rs_hh_graduate  = pg_query($conn, $sql_hh_graduate);

			$cvalue_g="";
			$hh_graduate_primary_list = '<option value="">-- Select graduation --</option>';
			$hh_graduate_secondary_list = '<option value="">-- Select graduation --</option>';
			$hh_graduate_tertiary_list = '<option value="">-- Select graduation --</option>';
			
			while ($row_hh_graduate = pg_fetch_assoc($rs_hh_graduate)) {
				if($lang['DB_LANG_stat'] == 'fr'){ $cvalue_g=$row_hh_graduate['cvaluefr']; } else { $cvalue_g=$row_hh_graduate['cvalue']; }
	
				// Graduate Primary
				if($arr_hh['id_graduate_primary'] == $row_hh_graduate['id_regvalue']){ $sel_hh_primary='selected="selected"'; } else { $sel_hh_primary=''; }
				$hh_graduate_primary_list .= '<option value="'.$row_hh_graduate['id_regvalue'] .'" '.$sel_hh_primary.'>'. $cvalue_g .'</option>';
				
				// Graduate Secondary
				if($arr_hh['id_graduate_secondary'] == $row_hh_graduate['id_regvalue']){ $sel_hh_secondary='selected="selected"'; } else { $sel_hh_secondary=''; }
				$hh_graduate_secondary_list .= '<option value="'.$row_hh_graduate['id_regvalue'] .'" '.$sel_hh_secondary.'>'. $cvalue_g .'</option>';
				
				// Graduate Tertiary
				if($arr_hh['id_graduate_tertiary'] == $row_hh_graduate['id_regvalue']){ $sel_hh_tertiary='selected="selected"'; } else { $sel_hh_tertiary=''; }
				$hh_graduate_tertiary_list .= '<option value="'.$row_hh_graduate['id_regvalue'] .'" '.$sel_hh_tertiary.'>'. $cvalue_g .'</option>';
			}
		
			// Working
			$sql_hh_working = "select id_regvalue, cvalue, cvaluefr from regvalues where id_register=262 order by cvalue";
			$rs_hh_working  = pg_query($conn, $sql_hh_working);

			$cvalue_w="";
			$hh_working_on_farm_list = '<option value="">-- Select working --</option>';
			$hh_working_off_farm_list = '<option value="">-- Select working --</option>';
			$hh_read_write_list = '<option value="">-- Select literacy --</option>';
			$hh_schooling_list = '<option value="">-- Select schooling --</option>';
			
			while ($row_hh_working = pg_fetch_assoc($rs_hh_working)) {
				if($lang['DB_LANG_stat'] == 'fr'){ $cvalue_w=$row_hh_working['cvaluefr']; } else { $cvalue_w=$row_hh_working['cvalue']; }
		
				// Working On Farm
				if($arr_hh['id_working_on_farm'] == $row_hh_working['id_regvalue']){ $sel_hh_working_on='selected="selected"'; } else { $sel_hh_working_on=''; }
				$hh_working_on_farm_list .= '<option value="'.$row_hh_working['id_regvalue'] .'" '.$sel_hh_working_on.'>'. $cvalue_w .'</option>';
				
				// Working Off Farm
				if($arr_hh['id_working_off_farm'] == $row_hh_working['id_regvalue']){ $sel_hh_working_off='selected="selected"'; } else { $sel_hh_working_off=''; }
				$hh_working_off_farm_list .= '<option value="'.$row_hh_working['id_regvalue'] .'" '.$sel_hh_working_off.'>'. $cvalue_w .'</option>';
				
				// Literacy
				if($arr_hh['read_write'] == $row_hh_working['id_regvalue']){ $sel_hh_read_write='selected="selected"'; } else { $sel_hh_read_write=''; }
				$hh_read_write_list .= '<option value="'.$row_hh_working['id_regvalue'] .'" '.$sel_hh_read_write.'>'. $cvalue_w .'</option>';
				
				// Schooling
				if($arr_hh['schooling'] == $row_hh_working['id_regvalue']){ $sel_hh_schooling='selected="selected"'; } else { $sel_hh_schooling=''; }
				$hh_schooling_list .= '<option value="'.$row_hh_working['id_regvalue'] .'" '.$sel_hh_schooling.'>'. $cvalue_w .'</option>';
			}
			
			// Gender
			$sql_hh_gender = "select id_regvalue, cvalue, cvaluefr from regvalues where id_register=41 order by cvalue";
			$rs_hh_gender  = pg_query($conn, $sql_hh_gender);

			$cvalue="";
			$hh_gender_list = '<option value="">-- Select gender --</option>';
			
			while ($row_hh_gender = pg_fetch_assoc($rs_hh_gender)) {
				if($lang['DB_LANG_stat'] == 'fr'){ $cvalue=$row_hh_gender['cvalue']; } else { $cvalue=$row_hh_gender['cvaluefr']; }
	
				if($arr_hh['gender'] == $row_hh_gender['id_regvalue']){ $sel_hh_gender='selected'; } else { $sel_hh_gender=''; }
				$hh_gender_list .= '<option value="'.$row_hh_gender['id_regvalue'] .'" '.$sel_hh_gender.'>'. $cvalue .'</option>';
			}
			
			if($conf == 'contact') {
				$card_val = 5;
				$edit_hh = '<div style="border-bottom:1px solid #e4e4e4;padding:12px 0 4px 0;">
					<a class="rotate-btn" data-card="card-4" onclick="editHousehold();"><i class="fa fa-edit"></i></a>
				</div>';
			} else {
				$card_val = 8;
				$edit_hh = "";
			}
			
			$dom = '<div class="card-wrapper">
				<div id="card-'.$card_val.'-modal" class="card-bg hide"></div>
				<div id="card-'.$card_val.'" class="card-rotating effect__click">

				<div class="face front">
					'. $edit_hh .'
					
					<div class="contact-box" style="border:none;">
						<div class="text-center" style="margin-bottom:20px;">
							<img alt="image" class="img-circle" height="70" width="70" src="'.$hh_avatar.'">
						</div>
						
						<table style="width:100%; font-size:12px;">
							<tr><td style="width:35%;" align="right"> 
							<label style="color:#aaa; font-size:12px; font-weight:normal;">ID : </label></td>
							<td align="left" style="padding-left:10px;">'. $id_household .'</td></tr>
					
							'.$row_firstname_h.$row_lastname_h.$row_birth_year_h.$row_relation_h.$row_graduate_primary_h.
							$row_graduate_secondary_h.$row_graduate_tertiary_h.$row_working_on_farm_h.$row_working_off_farm_h.
							$row_gender_h.$row_read_write.$row_schooling.'
						</table>
				
						<div class="clearfix"></div>
					</div>
				</div>

				<div class="face back hide animated front_face" id="edit_household">
					<div class="card-block">
						<div class="pull-left" style="border-bottom:1px solid #e4e4e4;padding:4px 0 10px 0; margin-bottom:15px;width:100%;">
							<a class="rotate-btn pull-left" onclick="updateHousehold(\''.$arr_hh['id_household'].'\');" data-card="card-4"><i class="fa fa-save"></i></a>
							<a class="rotate-btn pull-right" style="color:red;" data-card="card-4" onclick="CancelEditHousehold();"><i class="fa fa-ban"></i></a>
						</div>
						
						<div class="form-group"> 
								<label '.$act_firstname_h.' for="hh_firstname" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_FIRSTNAME'].'</label>
								<input id="hh_firstname" type="text" class="form-control" value="'.$arr_hh['firstname'].'">
							</div>
							
							<div class="form-group"> 
								<label '.$act_lastname_h.' for="hh_lastname" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_LASTNAME'].'</label>
								<input id="hh_lastname" type="text" class="form-control" value="'.$arr_hh['lastname'].'">
							</div>
							
							<div class="form-group"> 
								<label '.$act_birth_year_h.' for="hh_birth_year" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BIRTH_YEAR'].'</label>
								<input id="hh_birth_year" class="form-control" value="'.$arr_hh['birth_year'].'">
							</div>
							
							<div class="form-group"> 
								<label '.$act_relation_h.' for="hh_relation" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_RELATION'].'</label>
								<select id="hh_relation" class="form-control">'.$hh_relation_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_graduate_primary_h.' for="hh_graduate_primary" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GRADUATE_PRIMARY'].'</label>
								<select id="hh_graduate_primary" class="form-control">'.$hh_graduate_primary_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_graduate_secondary_h.' for="hh_graduate_secondary" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GRADUATE_SECONDARY'].'</label>
								<select id="hh_graduate_secondary" class="form-control">'.$hh_graduate_secondary_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_graduate_tertiary_h.' for="hh_graduate_tertiary" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GRADUATE_TERTIARY'].'</label>
								<select id="hh_graduate_tertiary" class="form-control">'.$hh_graduate_tertiary_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_working_on_farm_h.' for="hh_working_on_farm" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_WORKING_ON_FARM'].'</label>
								<select id="hh_working_on_farm" class="form-control">'.$hh_working_on_farm_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_working_off_farm_h.' for="hh_working_off_farm" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_WORKING_OFF_FARM'].'</label>
								<select id="hh_working_off_farm" class="form-control">'.$hh_working_off_farm_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_gender_h.' for="hh_gender" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GENDER'].'</label>
								<select id="hh_gender" class="form-control">'.$hh_gender_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_read_write.' for="hh_read_write" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_READ_WRITE'].'</label>
								<select id="hh_read_write" class="form-control">'.$hh_read_write_list.'</select>
							</div>
							
							<div class="form-group"> 
								<label '.$act_schooling.' for="hh_schooling" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SCHOOLING'].'</label>
								<select id="hh_schooling" class="form-control">'.$hh_schooling_list.'</select>
							</div>
					</div>
				</div>
			</div></div>';  
			
		break;
		
		
		case "delete_contract":
		
			$id_contract = $_GET['id_contract'];

			$sql = "DELETE FROM public.contract WHERE id_contract = $id_contract";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		case "show_contract_details":
		
			$id_contract = $_GET["id_contract"];
			
			$sql="SELECT * FROM public.contract WHERE id_contract=$id_contract";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);
			
			$dom=$row['id_contractor'].'##'.$row['id_contracting_party'].'##'.$row['contract_code'].'##'.$row['id_contract_type'].'##'.$row['contract_date'].'##'.$row['start_date'].'##'.$row['end_date'].'##'.$row['contract_desc'];
		
		break;
		
		
		case "has_relation_list":
			
			$id = $_GET["id_contact"];
			
			$has_relation_list = '';
			$sql_HasRelation = "select id_contract, id_contracting_party, contracting_party, contract_type from v_icw_contracts where id_contractor=$id";
			$result_HasRelation = pg_query($conn, $sql_HasRelation);

			while($arr_HasRelation = pg_fetch_assoc($result_HasRelation)){
				$has_relation_list .= '<li style="padding:5px 0 5px 0;">
					<span class="col-sm-9 no-padding contracting_party">
						'. utf8_decode($arr_HasRelation['contracting_party']) .'
					</span>
					<span class="col-sm-3 no-padding">
						<a href="#" onclick="deleteContract(\''. $arr_HasRelation['id_contract'] .'\',\''.$id.'\');" style="float:right;"> <i class="fa fa-trash"></i></a>
						<a href="#" onclick="editContract(\''. $arr_HasRelation['id_contract'] .'\',\''.$id.'\');" style="float:right;"> <i class="fa fa-edit"></i></a>	
					</span>
					<div style="color:#aaa; font-size:10px;">'. $arr_HasRelation['contract_type'] .'</div>
				</li>';
			}
			
			$dom=$has_relation_list;
			
		break;
		
		
		case "contract_management":

			if(isset($_GET["id_contractor"])){
				$id_contractor = $_GET["id_contractor"];
				$id_contractor_field = " id_contractor,";
				$id_contractor_val = " '$id_contractor',";
				$id_contractor_edit = "id_contractor='$id_contractor',";
			} else { $id_contractor_field = ""; $id_contractor_val = ""; $id_contractor_edit = ""; }

			if(isset($_GET["contract_code"])){
				$contract_code = $_GET["contract_code"];
				$contract_code_field = " contract_code,";
				$contract_code_val = " '$contract_code',";
				$contract_code_edit = " contract_code='$contract_code',";
			} else { $contract_code_field = ""; $contract_code_val = ""; $contract_code_edit = ""; }
	
			if(isset($_GET["id_contract_type"])){
				$id_contract_type = $_GET["id_contract_type"];
				$id_contract_type_field = " id_contract_type,";
				$id_contract_type_val = " '$id_contract_type',";
				$id_contract_type_edit = " id_contract_type='$id_contract_type',";
			} else { $id_contract_type_field = ""; $id_contract_type_val = ""; $id_contract_type_edit = ""; }
			
			if(isset($_GET["contract_date"])){
				$contract_date = $_GET["contract_date"];
				$contract_date_field = " contract_date,";
				$contract_date_val = " '$contract_date',";
				$contract_date_edit = " contract_date='$contract_date',";
			} else { $contract_date_field = ""; $contract_date_val = ""; $contract_date_edit = ""; }
			
			if(isset($_GET["contract_desc"])){
				$contract_desc = $_GET["contract_desc"];
				$contract_desc_field = " contract_desc,";
				$contract_desc_val = " '$contract_desc',";
				$contract_desc_edit = " contract_desc='$contract_desc',";
			} else { $contract_desc_field = ""; $contract_desc_val = ""; $contract_desc_edit = ""; }
			
			$id_contracting_party = $_GET["id_contracting_party"];
			$start_date = $_GET["start_date"];
			$end_date = $_GET["end_date"];
			
			if(isset($_GET["id_contract"])){
				$id_contract = $_GET["id_contract"];
			} else { $id_contract = ""; }
	
			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.contract(
					id_contracting_party, $id_contractor_field
					$contract_code_field $id_contract_type_field
					$contract_date_field $contract_desc_field
					start_date, end_date)
				VALUES ($id_contracting_party, $id_contractor_val
					$contract_code_val $id_contract_type_val
					$contract_date_val $contract_desc_val
					'$start_date', '$end_date');
				";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.contract
				   SET id_contracting_party=$id_contracting_party,
				   $contract_code_edit $id_contract_type_edit
				   $contract_date_edit $contract_desc_edit
					start_date='$start_date', end_date='$end_date'
				WHERE id_contract=$id_contract";
				
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "new_contract":
		
			$id_contact = $_GET['id_contact'];
			
			// Contact
			$sql_contact = "SELECT firstname, lastname FROM v_icw_contacts WHERE id_contact=$id_contact";
			$rs_contact = pg_query($conn, $sql_contact);
			$row_contact = pg_fetch_assoc($rs_contact);
			$name = $row_contact['firstname'] .' '. $row_contact['lastname'];
			
			$contractor = '<label for="contractModal_id_contractor">'. $lang['CONTRACT_MODAL_CONTRACTOR'] .'</label><br/>'.$name.'
				<input id="contractModal_id_contractor" type="hidden" value="'.$id_contact.'" class="form-control"/>';
			
			
			$sql_contracting_party  = 'select id_contact, contact_name, supchain_type from v_icw_contacts where id_type=10 order by supchain_type, contact_name';
			$rs_contracting_party = pg_query($conn, $sql_contracting_party);

			$contracting_party_list = '<option value="">-- Select Contracting party --</option>';
			while ($row_contracting_party = pg_fetch_assoc($rs_contracting_party)) {
				$contracting_party_list .= '<option value="'.$row_contracting_party['id_contact'] .'">'.$row_contracting_party['contact_name'] .'</option>';
			}
			
			// relationships/contracts
			if($lang['DB_LANG_stat'] == 'en'){
				$sql_relationships = "select id_regvalue, cvalue from regvalues where id_register=27 order by cvalue";
			} else {
				$sql_relationships = "select id_regvalue, cvalue".$lang['DB_LANG_stat']." as cvalue from regvalues where id_register=27 order by cvalue";
			}
			$rs_relationships  = pg_query($conn, $sql_relationships);

			$relationships_list = '<option value="">-- Select Contracting party --</option>';
			while ($row_relationships = pg_fetch_assoc($rs_relationships)) {
				$relationships_list .= '<option value="'.$row_relationships['id_regvalue'] .'">'.$row_relationships['cvalue'] .'</option>';
			}
			
			$dom=$contracting_party_list.'##'.$relationships_list.'##'.$contractor;
		
		break;
		
		case "second_edit_contact_form":
		
			$id = $_GET['id_contact'];
			
			$sql_stats = "SELECT * FROM v_icw_contacts WHERE id_contact ='$id'";

			$result = pg_query($conn, $sql_stats);
			$arr = pg_fetch_assoc($result);

			if($arr['lastname']!=''){
				$act_lastname = 'class="active"';
			}else{$act_lastname = ''; }
			
			if($arr['firstname']!=''){
				$act_firstname = 'class="active"';
			}else{$act_firstname = ''; }
			
			if($arr['middlename']!=''){
				$act_middlename = 'class="active"';
			}else{$act_middlename = ''; }
		
			if($arr['primary_company']!=''){
				$act_primary_company = 'class="active"';
			}else{$act_primary_company = ''; }

			if($arr['lang_name']!=''){
				$act_national_lang = 'class="active"';
			}else{$act_national_lang = '';}

			if($arr['notes']!=''){
				$act_notes = 'class="active"';
			}else{$act_notes = ''; }

			if($arr['p_street']!=''){
				$act_p_street = 'class="active"';
			}else{$act_p_street = '';}

			if($arr['name_town']!=''){
				$act_name_town = 'class="active"';
			}else{$act_name_town = '';}

			if($arr['postalcode']!=''){
				$act_postalcode = 'class="active"';
			}else{$act_postalcode = '';}

			if($arr['birthyear']!=''){
				$act_birthyear = 'class="active"';
			}else{$act_birthyear = '';}
			
			if($arr['birthday']!=''){
				$act_birthday = 'class="active"';
			}else{$act_birthday = '';}

			if($arr['phone1']!=''){
				$act_p_phone = 'class="active"';
			}else{$act_p_phone = '';}

			if($arr['phone2']!=''){
				$act_p_phone2 = 'class="active"';
			}else{$act_p_phone2 = '';}

			if($arr['p_phone3']!=''){
				$act_p_phone3 = 'class="active"';
			}else{$act_p_phone3 = '';}

			if($arr['p_phone4']!=''){
				$act_p_phone4 = 'class="active"';
			}else{$act_p_phone4 = '';}

			if($arr['p_phone5']!=''){
				$act_p_phone5 = 'class="active"';
			}else{$act_p_phone5 = '';}

			if($arr['p_email']!=''){
				$act_p_email = 'class="active"';
			}else{$act_p_email = '';}

			if($arr['p_email2']!=''){
				$act_p_email2 = 'class="active"';
			}else{$act_p_email2 = '';}

			if($arr['skype_id']!=''){
				$act_skype_id = 'class="active"';
			}else{$act_skype_id = '';}
			
			if($arr['id_town']!=''){
				$act_reg = 'class="active"';
			}else{$act_reg = '';}

			// Gender_list
			$sql_gender = "SELECT id_regvalue, cvalue FROM regvalues WHERE id_register =41";
			$result_gender = pg_query($conn, $sql_gender);

			$list_gender='<option value="">---</option>';
			while($arr_gender = pg_fetch_assoc($result_gender)){
				if($arr['id_gender']==$arr_gender['id_regvalue']){$gder_slt = 'selected="selected"';} else { $gder_slt = '';}
				$list_gender .='<option value="'.$arr_gender['id_regvalue'].'" '. $gder_slt.'>'.$arr_gender['cvalue'].'</option>';
			}
			
			// region_list
			$sql_region = "SELECT DISTINCT name_town, gid_town FROM towns WHERE id_country=1 ORDER BY name_town ASC";
			$result_region = pg_query($conn, $sql_region);

			$region_list='<option value="">---</option>';
			while($arr_region = pg_fetch_assoc($result_region)){
				if($arr['id_town']==$arr_region['gid_town']){$reg_slt = 'selected="selected"';} else { $reg_slt = '';}
				$region_list .='<option value="'.$arr_region['gid_town'].'@'.$arr_region['name_town'].'" '. $reg_slt.'>'.$arr_region['name_town'].'</option>';
			}

			// Language
			$sql_language = 'SELECT * FROM v_regvalues WHERE id_register=7 ORDER BY cvalue ASC';
			$rs_language = pg_query($conn, $sql_language);

			$language_list = '<option value="">-- Select Language --</option>';
			while ($row_language = pg_fetch_assoc($rs_language)) {
				if($arr['national_lang']==$row_language['id_regvalue']){$langer_slt = 'selected="selected"';} else { $langer_slt = '';}
				$language_list .= '<option value="'.$row_language['id_regvalue'] .'" '. $langer_slt.'>'.$row_language['cvalue'] .'</option>';
			}
		
			$dom='<div class="card-block">
				<div class="pull-left" style="border-bottom:1px solid #e4e4e4;padding:4px 0 10px 0; margin-bottom:15px;width:100%;">
					<a class="rotate-btn pull-left" onclick="updateProfil2(\''.$arr['id_contact'].'\');" data-card="card-1"><i class="fa fa-save"></i></a>
					<a class="rotate-btn pull-right" style="color:red;" data-card="card-1" onclick="showContactDetails(\''.$arr['id_contact'].'\');"><i class="fa fa-ban"></i></a>
				</div>	
			
				<div class="form-group">
					<label '.$act_firstname.' for="ctE2_firstname" style="color:#aaa; font-size:12px; font-weight:normal;">First Name</label>
					<input type="text" value="'.$arr['firstname'].'" id="ctE2_firstname" class="form-control">
				</div>
				
				<div class="form-group">
					<label '.$act_lastname.' for="ctE2_lastname" style="color:#aaa; font-size:12px; font-weight:normal;">Last Name</label>
					<input type="text" value="'.$arr['lastname'].'" id="ctE2_lastname" class="form-control">
				</div>

				<div class="form-group">
					<label '.$act_nickname.' for="ctE2_middlename" style="color:#aaa; font-size:12px; font-weight:normal;">Middlename</label>
					<input type="text" value="'.$arr['middlename'].'" id="ctE2_middlename" class="form-control">
				</div>

				<div class="form-group">
					<label '.$act_p_phone.' for="ctE2_p_phone" style="color:#aaa; font-size:12px; font-weight:normal;">Mobile 1</label>
					<input type="text" value="'.$arr['phone1'].'" id="ctE2_p_phone" class="form-control">
				</div>
			
				<div class="form-group">
					<label '.$act_p_phone2.' for="ctE2_p_phone2" style="color:#aaa; font-size:12px; font-weight:normal;">Mobile 2</label>
					<input type="text" value="'.$arr['phone2'].'" id="ctE2_p_phone2" class="form-control">
				</div>

				<div class="form-group">
					<label '.$act_p_phone3.' for="ctE2_p_phone3" style="color:#aaa; font-size:12px; font-weight:normal;">Mobile 3</label>
					<input type="text" value="'.$arr['p_phone3'].'" id="ctE2_p_phone3" class="form-control">
				</div>
			
				<div class="form-group">
					<label '.$act_p_phone4.' for="ctE2_p_phone4" style="color:#aaa; font-size:12px; font-weight:normal;">Mobile Money</label>
					<input type="text" value="'.$arr['p_phone4'].'" id="ctE2_p_phone4" class="form-control">
				</div>
				
				<div class="form-group">
					<label '.$act_p_phone5.' for="ctE2_p_phone5" style="color:#aaa; font-size:12px; font-weight:normal;">Phone Fix</label>
					<input type="text" value="'.$arr['p_phone5'].'" id="ctE2_p_phone5" class="form-control">
				</div>
				
				<div class="form-group">
					<label '.$act_p_email.' for="ctE2_p_email" style="color:#aaa; font-size:12px; font-weight:normal;">eMail Business *</label>
					<input type="email" value="'.$arr['p_email'].'" id="ctE2_p_email" class="form-control">
				</div>
				
				<div class="form-group">
					<label '.$act_p_email2.' for="ctE2_p_email2" style="color:#aaa; font-size:12px; font-weight:normal;">eMail Private</label>
					<input type="email" value="'.$arr['p_email2'].'" id="ctE2_p_email2" class="form-control">
				</div>
				
				<div class="form-group">
					<label '.$act_skype_id.' for="ctE2_skype_id" style="color:#aaa; font-size:12px; font-weight:normal;">Skype id</label>
					<input type="text" value="'.$arr['skype_id'].'" id="ctE2_skype_id" class="form-control">
				</div>
				
				<div class="form-group">
					<label '.$act_p_street.' for="ctE2_p_street" style="color:#aaa; font-size:12px; font-weight:normal;">Address</label>
					<input type="text" value="'.$arr['p_street'].'" id="ctE2_p_street" class="form-control">
				</div>
				
				<div class="form-group">
					<label '.$act_reg.' for="ctE2_town_name" style="top:10px; color:#aaa; font-size:12px; font-weight:normal;">Town</label>
					<select class="form-control" id="ctE2_town_name">'.$region_list.'</select>
				</div>
				
				<div class="form-group">
					<label '.$act_postalcode.' for="ctE2_postalcode" style="top:10px; color:#aaa; font-size:12px; font-weight:normal;">Postal Code</label>
					<input type="text" value="'.$arr['postalcode'].'" id="ctE2_postalcode" class="form-control">
				</div>
			
				<div class="form-group">
					<label '.$act_gender.' for="ctE2_gender" style="top:-14px; color:#aaa; font-size:12px; font-weight:normal;">Gender</label>
					<select class="form-control" id="ctE2_gender">'.$list_gender.'</select>
				</div>

				<div class="form-group">
					<label '.$act_birthday.' for="ctE2_birthday" style="color:#aaa; font-size:12px; font-weight:normal;">Birthday</label>
					<input type="text" value="'.$arr['birthday'].'" id="ctE2_birthday" class="form-control edit_delivery_date">
				</div>
				
				<div class="form-group">
					<label '.$act_national_lang.' for="ctE2_national_lang" style="color:#aaa; font-size:12px; font-weight:normal;">National Language</label>
					<select class="form-control" id="ctE2_national_lang">'.$language_list.'</select>
				</div>
				
				<div class="form-group">
					<label '.$act_notes.' for="ctE2_notes" style="top:10px; color:#aaa; font-size:12px; font-weight:normal;">Note</label>
					<textarea id="ctE2_notes" style="height:108px;" class="form-control">'.$arr['notes'].'</textarea>
				</div>
			</div>';
			
		break;
		
		case "update_profil":

			$id = $_GET["id"];

			if(isset($_GET["contact_code"])){
				$contact_code = pg_escape_string($_GET["contact_code"]);
				$req_contact_code = "contact_code='$contact_code', "; 
			} else { $req_contact_code = ""; }
			
			if(isset($_GET["mobile_created"])){
				$mobile_created = pg_escape_string($_GET["mobile_created"]);
				$req_mobile_created = "mobile_created='$mobile_created', "; 
			} else { $req_mobile_created = ""; }
			
			if(isset($_GET["firstname"])){
				$firstname = pg_escape_string($_GET["firstname"]);
				$req_firstname = "firstname='{$firstname}', "; 
			} else { $req_firstname = ""; }

			if(isset($_GET["lastname"])){
				$lastname = pg_escape_string($_GET["lastname"]);
				$req_lastname = "lastname='{$lastname}', "; 
			} else { $req_lastname = ""; }

			if(isset($_GET["middlename"])){
				$middlename = pg_escape_string($_GET["middlename"]);
				$req_middlename = "middlename='{$middlename}', "; 
			} else { $req_middlename = ""; }
			
			if(isset($_GET["name"])){
				$name = pg_escape_string($_GET["name"]);
				$req_name = "name='{$name}', "; 
			} else { $req_name = ""; }

			if(isset($_GET["p_phone"])){
				$p_phone = pg_escape_string($_GET["p_phone"]);
				$req_p_phone = "p_phone='{$p_phone}', "; 
			} else { $req_p_phone = ""; }

			if(isset($_GET["p_phone2"])){
				$p_phone2 = pg_escape_string($_GET["p_phone2"]);
				$req_p_phone2 = "p_phone2='{$p_phone2}', "; 
			} else { $req_p_phone2 = ""; }

			if(isset($_GET["p_phone3"])){
				$p_phone3 = pg_escape_string($_GET["p_phone3"]);
				$req_p_phone3 = "p_phone3='{$p_phone3}', "; 
			} else { $req_p_phone3 = ""; }

			if(isset($_GET["p_phone4"])){
				$p_phone4 = pg_escape_string($_GET["p_phone4"]);
				$req_p_phone4 = "p_phone4='{$p_phone4}', "; 
			} else { $req_p_phone4 = ""; }

			if(isset($_GET["p_phone5"])){
				$p_phone5 = pg_escape_string($_GET["p_phone5"]);
				$req_p_phone5 = "p_phone5='{$p_phone5}', "; 
			} else { $req_p_phone5 = ""; }
			
			if(isset($_GET["bankname"])){
				$bankname = pg_escape_string($_GET["bankname"]);
				$req_bankname = "bankname='{$bankname}', "; 
			} else { $req_bankname = ""; }

			if(isset($_GET["p_email"])){
				$p_email = $_GET["p_email"];
				$req_p_email = "p_email='$p_email', "; 
			} else { $req_p_email = ""; }

			if(isset($_GET["p_email2"])){
				$p_email2 = $_GET["p_email2"];
				$req_p_email2 = "p_email2='$p_email2', "; 
			} else { $req_p_email2 = ""; }
			
			if(isset($_GET["p_email3"])){
				$p_email3 = $_GET["p_email3"];
				$req_p_email3 = "p_email3='$p_email3', "; 
			} else { $req_p_email3 = ""; }

			if(isset($_GET["skype_id"])){
				$skype_id = $_GET["skype_id"];
				$req_skype_id = "skype_id='$skype_id', "; 
			} else { $req_skype_id = ""; }

			if(isset($_GET["p_street"])){
				$p_street = pg_escape_string($_GET["p_street"]);
				$req_p_street = "p_street='{$p_street}', "; 
			} else { $req_p_street = ""; }

			if(isset($_GET["town_name"])){
				$town_name = pg_escape_string($_GET["town_name"]);
				$req_town_name = "town_name='{$town_name}', "; 
			} else { $req_town_name = ""; }
			
			if(isset($_GET["id_town"])){
				$id_town = $_GET["id_town"];
				$req_id_town = "id_town='$id_town', "; 
			} else { $req_id_town = ""; }

			if(isset($_GET["postalcode"])){
				$postalcode = $_GET["postalcode"];
				$req_postalcode = "p_postalcode='$postalcode', "; 
			} else { $req_postalcode = ""; }

			if(isset($_GET["notes"])){
				$notes = $_GET["notes"];
				$req_notes = "notes='$notes', "; 
			} else { $req_notes = ""; }

			if(isset($_GET["gender"])){
				$gender = $_GET["gender"];
				$req_gender = "id_gender='$gender', "; 
			} else { $req_gender = ""; }

			if(isset($_GET["birth_date"])){
				$birth_date = $_GET["birth_date"];
				$req_birth_date = "birth_date='$birth_date', "; 
			} else { $req_birth_date = ""; }

			if(isset($_GET["birth_year"])){
				$birth_year = $_GET["birth_year"];
				$req_birth_year = "birth_year='$birth_year', "; 
			} else { $req_birth_year = ""; }

			if(isset($_GET["national_lang"])){
				$national_lang = $_GET["national_lang"];
				$req_national_lang = "national_lang='$national_lang', "; 
			} else { $req_national_lang = ""; }
			
			$modified_by = $_SESSION['id_contact'];
			$modified_date = gmdate("Y/m/d H:i");
			
			$sql_stats = "UPDATE public.contact
			   SET $req_mobile_created $req_contact_code $req_firstname $req_lastname $req_middlename $req_name 
				  $req_p_phone $req_p_phone2 $req_p_phone3 $req_p_phone4 $req_p_phone5 $req_bankname
				  $req_p_email $req_p_email2 $req_p_email3 $req_skype_id $req_p_street $req_town_name 
				  $req_id_town $req_postalcode $req_notes $req_gender
				  $req_birth_date $req_birth_year $req_national_lang
				modified_date='$modified_date', modified_by='$modified_by'
			WHERE id_contact ='$id'";

			$result = pg_query($conn, $sql_stats);
			// $count = pg_num_rows($result);

			if($result){
				if(isset($_GET["agent_type"])){
					if($_GET["agent_type"] == 621){ $agent_type = 1; }
					elseif($_GET["agent_type"] == 622){ $agent_type = 5; }
					elseif($_GET["agent_type"] == 623){ $agent_type = 6; }
					elseif($_GET["agent_type"] == 624){ $agent_type = 3; }
					elseif($_GET["agent_type"] == 637){ $agent_type = 2; }
					elseif($_GET["agent_type"] == 638){ $agent_type = 4; }
					elseif($_GET["agent_type"] == 777){ $agent_type = 8; }
					else { $agent_type = null; }
					
					$sql = "UPDATE public.users SET agent_type = $agent_type WHERE id_contact ='$id'";
					pg_query($conn, $sql);
				} 
			
				$dom="1##Profil updated successfully";
			} else {
				$dom="0##Unable to update profil";
			}
			
		break;
		
		
		case "show_contact":

			$contact = '';
			$links = '';
			$id = $_GET["id"];
			$type = $_GET["type"];
			
			// Bio
			$bio_create = $_GET["bio_create"];
			$bio_update = $_GET["bio_update"];
			$bio_delete = $_GET["bio_delete"];
			
			// Links
			$links_create = $_GET["links_create"];
			$links_update = $_GET["links_update"];
			$links_delete = $_GET["links_delete"];
			
			if($type == 9){
				// Demography
				$demog_create = $_GET["demog_create"];
				$demog_update = $_GET["demog_update"];
				$demog_delete = $_GET["demog_delete"];
				
				// Plantation
				$plant_create = $_GET["plant_create"];
				$plant_update = $_GET["plant_update"];
				$plant_delete = $_GET["plant_delete"];
				
				$status_showhide = "hide";
				
			} else
			if($type == 115){
				$status_showhide = "";
				
			} else {
				$status_showhide = "hide";
			}
			
			$update_right = $_GET["update_right"];
			

			$sql_stats = "SELECT * FROM v_icw_contacts WHERE id_contact ='$id'";

			$result = pg_query($conn, $sql_stats);
			$arr = pg_fetch_assoc($result);

			$primary_company = $arr['lastname'];
			$id_supchain_type = $arr['id_supchain_type'];
			$coordx = $arr['coordx'];
			$coordy = $arr['coordy'];
			
			if($arr['lastname']!=''){
				$act_lastname = 'class="active"';
				$row_lastname = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NAME'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['lastname'].'</td></tr>';
			}else{$act_lastname = ''; $row_lastname = '';}
			
			if($arr['firstname']!=''){
				$act_firstname = 'class="active"';
				$row_firstname = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_FIRSTNAME'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['firstname'].'</td></tr>';
			}else{$act_firstname = ''; $row_firstname = '';}
			
			if($arr['middlename']!=''){
				$act_middlename = 'class="active"';
				$row_middlename = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Middlename : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['middlename'].'</td></tr>';
			}else{$act_middlename = ''; $row_middlename = '';}
		
			if($arr['primary_company']!=''){
				$act_primary_company = 'class="active"';
				$row_primary_company = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Primary Company : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['primary_company'].'</td></tr>';
			}else{$act_primary_company = ''; $row_primary_company = '';}

			if($arr['gender']!=''){
				$act_gender = 'class="active"';		
				$row_gender = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GENDER'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. getRegvalues($arr['id_gender'],$lang['DB_LANG_stat']) .'</td></tr>';
			}else{$row_gender = ''; }

			if($arr['national_lang']!=''){
				$act_national_lang = 'class="active"';
				$row_national_lang = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_LANGUAGE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.getRegvalues($arr['national_lang'],$lang['DB_LANG_stat']).'</td></tr>';
			}else{$act_national_lang = ''; $row_national_lang = '';}
			
			if($arr['other_lang']!=''){
				$act_other_lang = 'class="active"';
				$row_other_lang = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_OTHER_LANGUAGE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.getRegvalues($arr['other_lang'],$lang['DB_LANG_stat']).'</td></tr>';
			}else{$act_other_lang = ''; $row_other_lang = '';}

			if($arr['notes']!=''){
				$act_notes = 'class="active"';
				$row_notes = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NOTES'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['notes'].'</td></tr>';
			}else{$act_notes = ''; $row_notes = '';}

			if($arr['p_street']!=''){
				$act_p_street = 'class="active"';
				$row_p_street = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_STREET'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['p_street'].'</td></tr>';
			}else{$act_p_street = ''; $row_p_street = '';}
			
			if($arr['p_street1']!=''){
				$act_p_street1 = 'class="active"';
				$row_p_street1 = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_QUARTIER'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['p_street1'].'</td></tr>';
			}else{$act_p_street1 = ''; $row_p_street1 = '';}

			if($arr['name_town']!=''){
				$act_name_town = 'class="active"';
				$row_name_town = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_TOWN'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['name_town'].'</td></tr>';
			}else{$act_name_town = ''; $row_name_town = '';}

			if(($row_p_street!="") OR ($row_p_street1!="") OR ($row_name_town!="")) { $sh_residence = ''; } else { $sh_residence = 'hide'; }


			if($arr['postalcode']!=''){
				$act_postalcode = 'class="active"';
				$row_postalcode = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_POSTAL_CODE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['postalcode'].'</td></tr>';
			}else{$act_postalcode = ''; $row_postalcode = '';}

			if($arr['birthyear']!=''){
				$act_birthyear = 'class="active"';
				$row_birthyear = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BIRTH_YEAR'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['birthyear'].'</td></tr>';
			}else{$act_birthyear = ''; $row_birthyear = '';}
			
			if($arr['birthdate']!=''){
				$act_birthdate = 'class="active"';
				$row_birthdate = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BIRTH_DAY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['birthdate'].'</td></tr>';
			}else{$act_birthdate = ''; $row_birthdate = '';}

			if($arr['phone1']!=''){
				$act_p_phone = 'class="active"';
				$row_p_phone = '<tr><td style="width:30%; padding:3px;" align="right" class="bg-success "></td>
					<td style="padding:3px 3px 3px 10px;" class="bg-success"> Communication</td></tr>
					<tr><td colspan="2"><div style="height:5px;"></div></td></tr>
					
				<tr><td style="width:30%;  padding-top:5px;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MOBILE'].' 1 : </label></td>
					<td align="left" style="padding-left:10px; padding-top:5px;"><a href="tel:'.$arr['phone1'].'">'.$arr['phone1'].'</a></td></tr>';
			}else{$act_p_phone = ''; $row_p_phone = '';}

			if($arr['phone2']!=''){
				$act_p_phone2 = 'class="active"';
				$row_p_phone2 = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MOBILE'].' 2 : </label></td>
					<td align="left" style="padding-left:10px;"><a href="tel:'.$arr['phone2'].'">'.$arr['phone2'].'</a></td></tr>';
			}else{$act_p_phone2 = ''; $row_p_phone2 = '';}

			if($arr['p_phone3']!=''){
				$act_p_phone3 = 'class="active"';
				$row_p_phone3 = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MOBILE'].' 3 : </label></td>
					<td align="left" style="padding-left:10px;"><a href="tel:'.$arr['p_phone3'].'">'.$arr['p_phone3'].'</a></td></tr>';
			}else{$act_p_phone3 = ''; $row_p_phone3 = '';}

			if($arr['p_phone4']!=''){
				$act_p_phone4 = 'class="active"';
				$row_p_phone4 = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MOBILE_MONEY'].' : </label></td>
					<td align="left" style="padding-left:10px;"><a href="tel:'.$arr['p_phone4'].'">'.$arr['p_phone4'].'</a></td></tr>';
			}else{$act_p_phone4 = ''; $row_p_phone4 = '';}
			
			if($arr['p_phone5']!=''){
				$act_p_phone5 = 'class="active"';
				$row_p_phone5 = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PHONE_FIX'].' : </label></td>
					<td align="left" style="padding-left:10px;"><a href="tel:'.$arr['p_phone5'].'">'.$arr['p_phone5'].'</a></td></tr>';
			}else{$act_p_phone5 = ''; $row_p_phone5 = '';}
			
			if($arr['bankname']!=''){
				$act_bankname = 'class="active"';
				$row_bankname = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BANKNAME'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['bankname'].'</td></tr>';
			}else{$act_bankname = ''; $row_bankname = '';}

			if($arr['p_email']!=''){
				$act_p_email = 'class="active"';
				$row_p_email = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_EMAIL_BUSINESS'].' : </label></td>
					<td align="left" style="padding-left:10px;"><a href="mailto:'.$arr['p_email'].'">'.$arr['p_email'].'</a></td></tr>';
			}else{$act_p_email = ''; $row_p_email = '';}

			if($arr['p_email2']!=''){
				$act_p_email2 = 'class="active"';
				$row_p_email2 = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_EMAIL_PRIVATE'].' : </label></td>
					<td align="left" style="padding-left:10px;"><a href="mailto:'.$arr['p_email2'].'">'.$arr['p_email2'].'</a></td></tr>';
			}else{$act_p_email2 = ''; $row_p_email2 = '';}

			if($arr['p_email3']!=''){
				$act_p_email3 = 'class="active"';
				$row_p_email3 = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_EMAIL_ICCOP'].' : </label></td>
					<td align="left" style="padding-left:10px;"><a href="mailto:'.$arr['p_email3'].'">'.$arr['p_email3'].'</a></td></tr>';
			}else{$act_p_email3 = ''; $row_p_email3 = '';}
			
			if($arr['skype_id']!=''){
				$act_skype_id = 'class="active"';
				$row_skype_id = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SKYPE_ID'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['skype_id'].'</td></tr>';
			}else{$act_skype_id = ''; $row_skype_id = '';}
			
			
			if($arr['code_external']!=''){
				$act_code_external = 'class="active"';
				$row_code_external = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_INTERNAL_CODE'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr['code_external'].'</td></tr>';
			}else{$act_code_external = ''; $row_code_external = '';}
			
			// if($arr['id_coop_member']!=''){
				$act_id_coop_member = 'class="active"';
				if($arr['id_coop_member'] == 1) {
					$id_coop_member = $lang['LOG_YES'];
				} else {
					$id_coop_member = $lang['LOG_NO'];
				}
				
				$row_id_coop_member = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MEMBER_OF_COOP'].' : </label></td>
					<td align="left" style="padding-left:10px;">'.$id_coop_member.'</td></tr>';
			// }else{$act_id_coop_member= ''; $row_id_coop_member = '';}
			
			if($arr['civil_status']!=''){
				$act_civil_status = 'class="active"';
				$row_civil_status = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CIVIL_STATUS'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. getRegvalues($arr['civil_status'], $lang['DB_LANG_stat']) .'</td></tr>';
			}else{$act_civil_status = ''; $row_civil_status = '';}
			
			if($arr['nationality']!=''){
				$act_nationality = 'class="active"';
				$row_nationality = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NATIONALITY'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. getRegvalues($arr['nationality'], $lang['DB_LANG_stat']) .'</td></tr>';
			}else{$act_nationality = ''; $row_nationality = '';}
			
			if($arr['place_birth']!=''){
				$act_place_birth = 'class="active"';
				$row_place_birth = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PLACE_BIRTH'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $arr['place_birth'] .'</td></tr>';
			}else{$act_place_birth = ''; $row_place_birth = '';}
			
			if($arr['mobile_money_operator']!=''){
				$act_mobile_money_operator = 'class="active"';
				$row_mobile_money_operator = '<tr><td style="width:30%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MOBILE_MONEY_OPERATOR'].' : </label></td>
					<td align="left" style="padding-left:10px;">'. $arr['mobile_money_operator'] .'</td></tr>';
			}else{$act_mobile_money_operator = ''; $row_mobile_money_operator = '';}
	

			// Data Collection

			if($arr['dc_completed'] == 1) {
				$dc_completed = $lang['LOG_YES'];
			} else {
				$dc_completed = $lang['LOG_NO'];
			}
			$row_dc_completed = '<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_DC_COMPLETED'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$dc_completed.'</td></tr>';
			
			$row_dc_completed_by = '<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_DC_COMPLETED_BY'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$arr['dc_completed_by'].'</td></tr>';
			
			$row_dc_completed_date = '<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_DC_COMPLETED_DATE'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$arr['dc_completed_date'].'</td></tr>';
			
			$row_created_by = '<tr><td colspan="2" height="15"></td></tr>
			<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CREATED_BY'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$arr['created_name'].'</td></tr>';
			
			$row_created_date = '<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CREATION_DATE'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$arr['created_date'].'</td></tr>';
				
			$row_modified_by = '<tr><td colspan="2" height="15"></td></tr>
			<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MODIFIED_BY'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$arr['modified_by_name'].'</td></tr>';
			
			$row_modified_date = '<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MODIFIED_DATE'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$arr['modified_date'].'</td></tr>';
			
			
			// Check Out
		
			$check_todo=""; $check_done=""; $check_checked="";
			if($arr['check_out'] == 0) { $check_todo = "checked"; }
			if($arr['check_out'] == 1) { $check_done = "checked"; }
			if($arr['check_out'] == 2) { $check_checked = "checked"; }
			
			$row_check_out_data = '<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CHECK_TODO'].' : </label></td>
				<td align="left" style="padding-left:10px;">
					<div class="i-checks"><label> <input type="radio" '.$check_todo.' value="0" name="check_out_cont"> <i></i> '. $lang['CONT_TODO'] .' </label></div>
                    <div class="i-checks"><label> <input type="radio" '.$check_done.' value="1" name="check_out_cont"> <i></i> '. $lang['CONT_DONE'] .' </label></div>
                    <div class="i-checks"><label> <input type="radio" '.$check_checked.' value="2" name="check_out_cont"> <i></i> '. $lang['CONT_CHECK'] .' </label></div>
				</td></tr>';
				
			$row_check_out_date = '<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CHECKOUT_DATE'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$arr['check_out_date'].'</td></tr>';
			
			$row_check_out_by = '<tr><td style="width:30%;" align="right">
				<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CHECKOUT_BY'].' : </label></td>
				<td align="left" style="padding-left:10px;">'.$arr['check_out_by'].'</td></tr>';
		
			
			
			//region_list
			$sql_region = "SELECT DISTINCT name_town, gid_town FROM towns WHERE id_country=1 ORDER BY name_town ASC";
			$rs_region = pg_query($conn, $sql_region);

			$region_list = '<option value="">---</option>';
			while ($row_region = pg_fetch_assoc($rs_region)) {
				if($arr['id_town']==$row_region['gid_town']){$reg_slt = 'selected="selected"';} else { $reg_slt = '';}
				$region_list .= '<option value="'.$row_region['gid_town'] .'@'.$row_region['name_town'] .'"'. $reg_slt.'>'.$row_region['name_town'] .'</option>';
			}

			// Gender_list
			$sql_gender = "SELECT * FROM v_regvalues WHERE id_register=41";
			$result_gender = pg_query($conn, $sql_gender);

			$list_gender='<option value="">---</option>';
			while($arr_gender = pg_fetch_assoc($result_gender)){
				if($arr['id_gender']==$arr_gender['id_regvalue']){$gder_slt = 'selected="selected"';} else { $gder_slt = '';}
				
				if($lang['DB_LANG_stat'] == 'en') {
					$list_gender .= '<option value="'.$arr_gender['id_regvalue'] .'" '. $gder_slt.'>'.$arr_gender['cvalue'] .'</option>';
				} else {
					$list_gender .= '<option value="'.$arr_gender['id_regvalue'] .'" '. $gder_slt.'>'.$arr_gender['cvalue'. $lang['DB_LANG_stat']] .'</option>';
				}
			}

			if($bio_update == 1){
				if($coordx!=""){ $x=$coordx; } else { $x=0; }
				if($coordy!=""){ $y=$coordy; } else { $y=0; }
				
				// $editContact = '<a class="rotate-btn" data-card="card-1" onclick="editBio();"><i class="fa fa-edit"></i></a>
				// <a class="rotate-btn pull-right" onclick="loadContactMap(1,'.$id.','.$x.','.$y.');" data-toggle="modal" data-target="#modalContactMap"><i class="fa fa-map-marker"></i></a>';
				
				$editContact = '<a class="rotate-btn" data-card="card-1" onclick="editBio();"><i class="fa fa-edit"></i></a>';
			} else {
				$editContact = '';
			}
			
			$sql_language = 'SELECT * FROM v_regvalues WHERE id_register=7 ORDER BY id_regvalue ASC';
			$rs_language = pg_query($conn, $sql_language);

			$language_list = '<option value="">-- Select Language --</option>';
			while ($row_language = pg_fetch_assoc($rs_language)) {
				if($arr['national_lang']==$row_language['id_regvalue']){$langer_slt = 'selected="selected"';} else { $langer_slt = '';}
				
				if($lang['DB_LANG_stat'] == 'en') {
					$language_list .= '<option value="'.$row_language['id_regvalue'] .'" '.$langer_slt.'>'.$row_language['cvalue'] .'</option>';
				} else {
					$language_list .= '<option value="'.$row_language['id_regvalue'] .'" '.$langer_slt.'>'.$row_language['cvalue'. $lang['DB_LANG_stat']] .'</option>';
				}
			}

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
			
			
			if($type == 10){
				if($bio_create == 1){
					$createContact = '<a onclick="addContactForm();" style="margin-bottom:5px;"><i class="fa fa-plus"></i> New Contact</a>';
					$createCoop = '<a onclick="addCoopForm();" style="margin-bottom:5px; float:right;"><i class="fa fa-plus"></i> New Cooperative</a>';
				} else {
					$createContact = "";
					$createCoop = "";
				}
			} else {
				$createContact = "";
				$createCoop = "";
			}
			
			
			$contactList = '';
			$sql_contactList = "select * from v_icw_contacts where id_primary_company = $id";
			$result_contactList = pg_query($conn, $sql_contactList);
		
			while($arr_contactList = pg_fetch_assoc($result_contactList)){
				if(file_exists('img/avatar/' . $arr_contactList['id_contact'] . ".jpg")) {
					$avatar_c = 'img/avatar/' . $arr_contactList['id_contact'] . ".jpg";
				} else { $avatar_c = 'img/user.jpg'; }
				
				$contactList .= '<tr><td><img src="'.$avatar_c.'" class="img-circle" height="35" /></td>
				<td>'. $arr_contactList['firstname'] .' '. $arr_contactList['lastname'] .'
				<div style="color:#aaa; font-size:9px;">'. $arr_contactList['name_town'] .'</div></td>';
				
				if($update_right == 1){
					$contactList .= '<td><a class="rotate-btn" data-card="card-1" onclick="editBio2('. $arr_contactList['id_contact'] .');">
					<i class="fa fa-eye"></i></a></td>';
				} else {
					$contactList .= '<td></td>';
				}
			
				$contactList .= '</tr>';
			}
			
			
			//Agent Type
			$sql_agent_type = "SELECT * FROM v_regvalues WHERE id_register=280 ORDER BY id_regvalue ASC";
			$rs_agent_type = pg_query($conn, $sql_agent_type);

			$agent_type_list = '<option value="">---</option>';
			while ($row_agent_type = pg_fetch_assoc($rs_agent_type)) {
				
				if($arr["agent_type"] == 1){ $agent_type = 621; }
				elseif($arr["agent_type"] == 5){ $agent_type = 622; }
				elseif($arr["agent_type"] == 6){ $agent_type = 623; }
				elseif($arr["agent_type"] == 3){ $agent_type = 624; }
				elseif($arr["agent_type"] == 2){ $agent_type = 637; }
				elseif($arr["agent_type"] == 4){ $agent_type = 638; }
				elseif($arr["agent_type"] == 8){ $agent_type = 777; }
				else { $agent_type = null; }
			
				if($agent_type==$row_agent_type['id_regvalue']){$agtyp_slt = 'selected="selected"';} else { $agtyp_slt = '';}
			
				if($lang['DB_LANG_stat'] == 'en') {
					$agent_type_list .= '<option value="'.$row_agent_type['id_regvalue'] .'"'. $agtyp_slt.'>'.$row_agent_type['cvalue'] .'</option>';
				} else {
					$agent_type_list .= '<option value="'.$row_agent_type['id_regvalue'] .'"'. $agtyp_slt.'>'.$row_agent_type['cvalue'. $lang['DB_LANG_stat']] .'</option>';
				}
			}
			
			
			//Mobile Created
			$sql_mobile_created = "SELECT * FROM v_regvalues WHERE id_register=284 ORDER BY id_regvalue ASC";
			$rs_mobile_created = pg_query($conn, $sql_mobile_created);

			$mobile_created_list = '<option value="">---</option>';
			while ($row_mobile_created = pg_fetch_assoc($rs_mobile_created)) {
				if($arr['mobile_created']==$row_mobile_created['id_regvalue']){$mobCrt_slt = 'selected="selected"';} else { $mobCrt_slt = '';}
				
				if($lang['DB_LANG_stat'] == 'en') {
					$mobile_created_list .= '<option value="'.$row_mobile_created['id_regvalue'] .'"'. $mobCrt_slt.'>'.$row_mobile_created['cvalue'] .'</option>';
				} else {
					$mobile_created_list .= '<option value="'.$row_mobile_created['id_regvalue'] .'"'. $mobCrt_slt.'>'.$row_mobile_created['cvalue'. $lang['DB_LANG_stat']] .'</option>';
				}
			}
			
			if($arr['dc_completed']==1){ $dc_completed = $lang['LOG_YES']; } else { $dc_completed = $lang['LOG_NO']; }
			
			
			
			// Show agent_type
			$sql_agenType = "SELECT agent_type FROM users WHERE id_contact=$id";
			$rs_agenType = pg_query($conn, $sql_agenType);
			$row_agenType = pg_fetch_assoc($rs_agenType);
			
			if($row_agenType['agent_type'] != "") {
				if($row_agenType['agent_type']==1){
					$id_regvalue = 621;
				} else if($row_agenType['agent_type']==2){
					$id_regvalue = 637;
				} else if($row_agenType['agent_type']==3){
					$id_regvalue = 624;
				} else if($row_agenType['agent_type']==4){
					$id_regvalue = 638;
				} else if($row_agenType['agent_type']==5){
					$id_regvalue = 622;
				} else {
					$id_regvalue = 623;
				}
				
				$sql_agenTypeR = "SELECT * FROM v_regvalues WHERE id_regvalue=". $id_regvalue;
				$rs_agenTypeR = pg_query($conn, $sql_agenTypeR);
				$row_agenTypeR = pg_fetch_assoc($rs_agenTypeR);
			
				if($lang['DB_LANG_stat'] == 'en'){
					$agent_type = $row_agenTypeR['cvalue'];
				} else {
					$agent_type = $row_agenTypeR['cvalue' . $lang['DB_LANG_stat']];
				}
			
			} else { $agent_type = ""; }
		
			$contact = '<div class="card-wrapper">
				<div id="card-1-modal" class="card-bg hide"></div>
				<div id="card-1" class="card-rotating effect__click">

				<div class="face front">
					<div style="border-bottom:1px solid #e4e4e4;padding:12px 0 4px 0;">
						'.$editContact.'
					</div>

					<div class="contact-box" style="border:none;">
						<div class="text-center" style="margin-bottom:10px;">
							<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
							<div class="m-t-xs font-bold">'.$agent_type.'</div>
							<div class="m-t-xs">'.$arr['lastname'].' '.$arr['firstname'].'</div>
							<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
							<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
						</div>

						<table style="width:100%; font-size:12px;">
							<tr><td style="width:30%;" align="right">
							<label style="color:#aaa; font-size:12px; font-weight:normal;">Contact Code : </label></td>
							<td align="left" style="padding-left:10px;">'. $arr['contact_code'] .'</td></tr>
							
							<tr><td style="width:30%;" align="right">
							<label style="color:#aaa; font-size:12px; font-weight:normal;">'. $lang['CONT_INTERNAL_CODE'] .' : </label></td>
							<td align="left" style="padding-left:10px;">'. $row_code_external .'</td></tr>
							
							<tr><td style="width:30%;" align="right">
							<label style="color:#aaa; font-size:12px; font-weight:normal;">Contact ID : </label></td>
							<td align="left" style="padding-left:10px;">'. $arr['id_contact'] .'</td></tr>
							
							<tr><td style="width:30%;" align="right">
							<label style="color:#aaa; font-size:12px; font-weight:normal;">Status : </label></td>
							<td align="left" style="padding-left:10px;">'.  getRegvalues($arr['mobile_created'], $lang['DB_LANG_stat']) .'</td></tr>
						</table>
					
						<table style="width:100%; font-size:12px;">
							<tr><td style="width:30%; padding:3px;" align="right" class="bg-success"></td>
							<td style="padding:3px 3px 3px 10px;" class="bg-success">Contact Data</td></tr>
							<tr><td colspan="2"><div style="height:5px;"></div></td></tr>
							'.$row_firstname.$row_lastname.$row_middlename.$row_gender.$row_birthdate.$row_birthyear.$row_national_lang.$row_other_lang.$row_id_coop_member.$row_civil_status.$row_nationality.$row_place_birth.'
						</table>
					
						<table style="width:100%; font-size:12px;">
							'.$row_p_phone.$row_p_phone2.$row_p_phone3.$row_p_phone4.$row_p_phone5.$row_bankname.$row_p_email.$row_p_email2.$row_p_email3.$row_skype_id.$row_postalcode.$row_mobile_money_operator.'
						</table>
						
						<table style="width:100%; font-size:12px;">
							<tr><td style="width:30%; padding:3px;" align="right" class="bg-success '.$sh_residence.'"></td>
							<td style="padding:3px 3px 3px 10px;" class="bg-success '.$sh_residence.'">'. $lang['CONT_RESIDENCE'] .'</td></tr>
							<tr><td colspan="2"><div style="height:5px;"></div></td></tr>
							'.$row_p_street.$row_p_street1.$row_name_town.'
						</table>
						
						<table style="width:100%; font-size:12px;">
							'.$row_notes.'
						</table>
						
						<table style="width:100%; font-size:12px;">
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
					
					<div class="row no-margins">
						'. $createContact . $createCoop .'
						<table class="table table-striped" style="font-size:12px;">
							<tbody id="comp_contactList">
								'. $contactList .'
							</tbody>
						</table>
					</div>
					
					<div class="face back hide animated front_face" id="edit_bio2">
					
					</div>
				</div>

				<div class="face back hide animated front_face" id="edit_bio">
					<div class="card-block">
						<div class="pull-left" style="border-bottom:1px solid #e4e4e4;padding:4px 0 10px 0; margin-bottom:15px;width:100%;">
							<a class="rotate-btn pull-left" onclick="updateProfil(\''.$arr['id_contact'].'\',\''.$type.'\');" data-card="card-1"><i class="fa fa-save"></i></a>
							<a class="rotate-btn pull-right" style="color:red;" data-card="card-1" onclick="CancelEditBio();"><i class="fa fa-ban"></i></a>
						</div>

						<div class="form-group">
							<label for="ctE_agent_type" style="top:-14px; color:#aaa; font-size:12px; font-weight:normal;">Agent Type</label>
							<select class="form-control" id="ctE_agent_type">'.$agent_type_list.'</select>
						</div>
						
						<div class="form-group">
							<label for="ctE_contact_code" style="color:#aaa; font-size:12px; font-weight:normal;">Contact Code</label>
							<input type="text" value="'.$arr['contact_code'].'" id="ctE_contact_code" class="form-control">
						</div>
						
						<div class="form-group '. $status_showhide.'">
							<label for="ctE_mobile_created" style="top:-14px; color:#aaa; font-size:12px; font-weight:normal;">Status</label>
							<select class="form-control" id="ctE_mobile_created">'.$mobile_created_list.'</select>
						</div>
						
						<div class="form-group">
							<label '.$act_firstname.' for="ctE_firstname" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_FIRSTNAME'].'</label>
							<input type="text" value="'.$arr['firstname'].'" id="ctE_firstname" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_lastname.' for="ctE_lastname" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_LASTNAME'].'</label>
							<input type="text" value="'.$arr['lastname'].'" id="ctE_lastname" class="form-control">
						</div>

						<div class="form-group">
							<label '.$act_nickname.' for="ctE_middlename" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MIDDLENAME'].'</label>
							<input type="text" value="'.$arr['middlename'].'" id="ctE_middlename" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_gender.' for="ctE_gender" style="top:-14px; color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_GENDER'].'</label>
							<select class="form-control" id="ctE_gender">'.$list_gender.'</select>
						</div>
						
						<div class="form-group">
							<label '.$act_birthday.' for="ctE_birthday" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BIRTH_DAY'].'</label>
							<input type="text" value="'.$arr['birthday'].'" id="ctE_birthday" class="form-control edit_delivery_date">
						</div>
						
						<div class="form-group">
							<label '.$act_national_lang.' for="ctE_national_lang" style="top:-14px; color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_LANGUAGE'].'</label>
							<select class="form-control" id="ctE_national_lang">'.$language_list.'</select>
						</div>
						
						<div class="form-group">
							<label '.$act_p_phone.' for="ctE_p_phone" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MOBILE'].' 1</label>
							<input type="text" value="'.$arr['phone1'].'" id="ctE_p_phone" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_p_phone2.' for="ctE_p_phone2" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MOBILE'].' 2</label>
							<input type="text" value="'.$arr['phone2'].'" id="ctE_p_phone2" class="form-control">
						</div>

						<div class="form-group">
							<label '.$act_p_phone3.' for="ctE_p_phone3" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MOBILE'].' 3</label>
							<input type="text" value="'.$arr['p_phone3'].'" id="ctE_p_phone3" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_p_phone4.' for="ctE_p_phone4" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_MOBILE_MONEY'].'</label>
							<input type="text" value="'.$arr['p_phone4'].'" id="ctE_p_phone4" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_p_phone5.' for="ctE_p_phone5" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_PHONE_FIX'].'</label>
							<input type="text" value="'.$arr['p_phone5'].'" id="ctE_p_phone5" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_bankname.' for="ctE_bankname" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_BANKNAME'].'</label>
							<input type="text" value="'.$arr['bankname'].'" id="ctE_bankname" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_p_email.' for="ctE_p_email" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_EMAIL_BUSINESS'].' *</label>
							<input type="email" value="'.$arr['p_email'].'" id="ctE_p_email" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_p_email2.' for="ctE_p_email2" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_EMAIL_PRIVATE'].'</label>
							<input type="email" value="'.$arr['p_email2'].'" id="ctE_p_email2" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_p_email3.' for="ctE_p_email3" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_EMAIL_ICCOP'].'</label>
							<input type="email" value="'.$arr['p_email3'].'" id="ctE_p_email3" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_skype_id.' for="ctE_skype_id" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SKYPE_ID'].'</label>
							<input type="text" value="'.$arr['skype_id'].'" id="ctE_skype_id" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_postalcode.' for="ctE_postalcode" style="top:10px; color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_POSTAL_CODE'].'</label>
							<input type="text" value="'.$arr['postalcode'].'" id="ctE_postalcode" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_p_street.' for="ctE_p_street" style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_ADRESS'].'</label>
							<input type="text" value="'.$arr['p_street'].'" id="ctE_p_street" class="form-control">
						</div>
						
						<div class="form-group">
							<label '.$act_name_town.' for="ctE_town_name" style="top:10px; color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_TOWN'].'</label>
							<select class="form-control" id="ctE_town_name">'.$region_list.'</select>
						</div>
						
						<div class="form-group">
							<label '.$act_notes.' for="ctE_notes" style="top:10px; color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_NOTES'].'</label>
							<textarea id="ctE_notes" class="form-control">'.$arr['notes'].'</textarea>
						</div>
					</div>
				</div>

			</div></div>';
			
			$contactDocs="";
			
			$sql_contPic = "SELECT * FROM contact_docs WHERE contact_id = $id 
				AND doc_link IS NOT NULL AND doc_type != 654
			ORDER BY id_condoc ASC";
			$result_contPic = pg_query($conn, $sql_contPic);
			
			$i=0;
			$img="";
			while ($arr_contPic = pg_fetch_assoc($result_contPic)){
				
				// $img .= '<div class="col-lg-6 col-md-6 col-xs-6 thumb">
					// <a class="thumbnail" href="#" data-image-id="'. getRegvalues($arr_contPic['doc_type'], $lang['DB_LANG_stat']) .'" data-toggle="modal" data-title=""
						 // data-image="' .$arr_contPic['doc_link']. '" style="margin-bottom:5px;"
						 // data-target="#image-gallery">
						// <img class="img-thumbnail"
						 // src="' .$arr_contPic['doc_link']. '"
						 // alt="Another alt text">
					// </a>
					// <div class="text-center" style="margin-bottom:10px;">
						// <b>'. getRegvalues($arr_contPic['doc_type'], $lang['DB_LANG_stat']) .'</b>
						// <p>'. $arr_contPic['description'] .'</p>
					// </div>
				// </div>';
				
				$img .= '<li style="width:100%; float:left; border-bottom:1px solid #e6e5e5; padding-bottom:5px; padding-top:5px;">
					<div class="col-lg-3 col-md-3 col-xs-3">
					<a class="thumbnail" href="#" data-image-id="'. getRegvalues($arr_contPic['doc_type'], $lang['DB_LANG_stat']) .'" data-toggle="modal" data-title=""
						 data-image="' .$arr_contPic['doc_link']. '" style="padding:0px; margin-bottom:0px;"
						 data-target="#image-gallery">
						<img style="height:60px; width:auto;"
						 src="' .$arr_contPic['doc_link']. '"
						 alt="Another alt text">
					</a></div>
					
					<div class="col-lg-8 col-md-8 col-xs-8" style="padding-left: 0px;">
					<b>'. getRegvalues($arr_contPic['doc_type'], $lang['DB_LANG_stat']) .'</b>
						<p>'. $arr_contPic['description'] .'</p>
					</div>
				</li>';	
				
				$i++;
			}
			
			if($i!=0){
				// $contactDocs='<div>
					// <div class="row">
						// '. $img .'
					// </div>
				// </div>';
				
				$contactDocs='<div style="height:45vh; overflow-y:auto;">
					<ul style="list-style: none; padding-left: 8px;">
						'. $img .'
					</ul>
				</div>';
			}

			$more = '';
			$platation_ct=0;
			$supchain_type='';
			$edtit_relationships='';

			if($id_supchain_type==115){

				$platation_ct=1;

				$sql_bcrop = "SELECT * FROM public.v_icw_contracts WHERE id_contracting_party = '".$arr['id_contact']."'";
				$result_bcrop = pg_query($conn, $sql_bcrop);
				$arr_bcrop = pg_fetch_assoc($result_bcrop);

				$more = '<tr><td style="width:40%;" align="right">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Buyer of crop : </label></td>
					<td align="left" style="padding-left:10px;">'.$arr_bcrop['contractor'].'</td></tr>';

			} else
			if(($id_supchain_type==113)||($id_supchain_type==114)){

				$sql_cul = "SELECT * FROM public.v_icw_culture WHERE id_contact = '".$arr['id_contact']."'";
				$result_cul = pg_query($conn, $sql_cul);

				$x=1;
				$edtit_cul ='';
				while($arr_cul = pg_fetch_assoc($result_cul)){
					$more .= '<tr><td style="width:40%;" align="right">
						<label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_CULTURE'].' '.$x.': </label></td>
						<td align="left" style="padding-left:10px;">'.$arr_cul['name_culture'].'</td></tr>';

					$cul_selc1=''; $cul_selc2=''; $cul_selc3=''; $cul_selc4=''; $cul_selc5='';

					if($arr_cul['id_culture']==1){$cul_selc1 = 'selected="selected"';}
					if($arr_cul['id_culture']==2){$cul_selc2 = 'selected="selected"';}
					if($arr_cul['id_culture']==3){$cul_selc3 = 'selected="selected"';}
					if($arr_cul['id_culture']==4){$cul_selc4 = 'selected="selected"';}
					if($arr_cul['id_culture']==5){$cul_selc5 = 'selected="selected"';}

					$edtit_cul .= '<div class="form-group">
							<label for="name_country" style="color:#aaa; font-size:12px; font-weight:normal;">Culture '.$x.'</label>
							<select class="form-control" id="user_culture">
								<option value="">---</option>
								<option value="1#Coconut" '.$cul_selc1.'>Cocobut</option>
								<option value="2#Palm" '.$cul_selc2.'>Palm</option>
								<option value="3#Peanut" '.$cul_selc3.'>Peanut</option>
								<option value="4#Sunflower" '.$cul_selc4.'>Sunflower</option>
								<option value="5#Safflower" '.$cul_selc5.'>Safflower</option>
							</select>
						</div>';
					$x++;
				}
			} 

			$supchain_list='<option value="">---</option>';
			$sql_supchain_edit = "SELECT id_regvalue, cvalue FROM regvalues WHERE id_register =25";
			$result_supchain_edit = pg_query($conn, $sql_supchain_edit);

			while($arr_supchain_edit = pg_fetch_assoc($result_supchain_edit)){
				if($arr['id_supchain_type']==$arr_supchain_edit['id_regvalue']){$supchain_slt = 'selected="selected"';} else { $supchain_slt = '';}
				$supchain_list .='<option value="'.$arr_supchain_edit['id_regvalue'].'" '. $supchain_slt.'>'.$arr_supchain_edit['cvalue'].'</option>';
			}

			if($arr['primary_company']!=''){
				$act_primary_company = 'class="active"';
				$row_primary_company = $arr['primary_company'];
			}else{$act_primary_company = ''; $row_primary_company = '';}

			
			if($links_update == 1){
				$editLinks = '<a class="rotate-btn" data-card="card-2" onclick="editLinks();"><i class="fa fa-edit"></i></a>';
			} else {
				$editLinks = '';
			}
			
			$contractors_list = '';
			$sql_isArelation = "select id_contractor, contractor, contract_type from v_icw_contracts
			where id_contracting_party=$id";
			$result_isArelation = pg_query($conn, $sql_isArelation);
			
			while($arr_isArelation = pg_fetch_assoc($result_isArelation)){
				$contractors_list .= '<tr>
					<td>'.$arr_isArelation['contractor'].'</td>
					<td style="color:#aaa; font-size:10px;" align="right">'.$arr_isArelation['contract_type'].'</td>
				</tr>';
			}
			
			if($contractors_list!=""){ $class_contractors=''; } else { $class_contractors='hide'; }
			
			$has_relation_list = '';
			$sql_HasRelation = "select id_contract, id_contracting_party, contracting_party, contract_type from v_icw_contracts where id_contractor=$id";
			$result_HasRelation = pg_query($conn, $sql_HasRelation);

			while($arr_HasRelation = pg_fetch_assoc($result_HasRelation)){
				
				if($links_update == 1){
					$edit_contact='<a href="#" onclick="editContract(\''. $arr_HasRelation['id_contract'] .'\',\''.$id.'\');" style="float:right;"> <i class="fa fa-edit"></i></a>	';
				} else { $edit_contact=""; }
				
				if($links_delete == 1){
					$delete_contact='<a href="#" onclick="deleteContract('. $arr_HasRelation['id_contract'] .',\''.$id.'\');" style="float:right;"> <i class="fa fa-trash"></i></a>';
				} else { $delete_contact=""; }
				
				
				$has_relation_list .= '<li style="padding:5px 0 5px 0;">
					<span class="col-sm-9 no-padding contracting_party">
						'. $arr_HasRelation['contracting_party'] .'
					</span>
					<span class="col-sm-3 no-padding">
						'. $delete_contact . $edit_contact .'	
					</span>
					<div style="color:#aaa; font-size:10px;">'. $arr_HasRelation['contract_type'] .'</div>
				</li>';
			}
			
			$new_contact="";
			if($links_create == 1){
				$new_contact='<a onclick="newContractForm(\'new\',\''.$id.'\');" style="margin-bottom:5px;"><i class="fa fa-plus"></i> '. $lang['MENU_NEW'] .'</a>';
			}
			
			$links = '<div class="card-wrapper">
				<div id="card-2-modal" class="card-bg hide"></div>
				<div id="card-2" class="card-rotating effect__click">

				<div class="face front">
					<div class="contact-box" style="margin-top:30px;">
						<div class="text-center" style="margin-bottom:10px;">
							<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
							<div class="m-t-xs font-bold">'.$agent_type.'</div>
							<div class="m-t-xs">'.$arr['lastname'].' '.$arr['firstname'].'</div>
							<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
							<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div> 
							<div class="m-t-xs">'.$arr['primary_company'].'</div>
						</div>
					</div>
					
					
					<div style="border-bottom:1px solid #e4e4e4;padding:12px 0 4px 0;">
						'.$editLinks.'
					</div>

					<div class="contact-box" style="border:none;">
						<table style="width:100%; font-size:12px;">';

							// $links .= '<tr><td style="width:40%;" align="right">
							// <label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_LINKED_TO'].' : </label></td>
							// <td align="left" style="padding-left:10px;">'.$row_primary_company.'</td></tr>';

							// $links .= '<tr><td style="width:40%;" align="right"><label>Member Coop : </label></td>
							// <td align="left" style="padding-left:10px;"></td></tr>';

							$links .= '<tr><td style="width:40%;" align="right"><label style="color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SUP_CHAIN'].' : </label></td>
							<td align="left" style="padding-left:10px;">'. getRegvalues($arr['id_supchain_type'], $lang['DB_LANG_stat']) .'</td></tr>';
							
						$links .= '</table>

						<table style="width:100%; font-size:12px;">'. $more .'</table>

						<div class="clearfix"></div>
					</div>
					
					<div class="row no-margins">
						<div class="form-group '.$class_contractors.'">
							<label for="" style="top:-14px color:#aaa; font-size:12px; font-weight:bold;">'.$lang['CONT_IS_A_RELATION'].'</label>
						</div>
						
						<div>
							<table class="table table-striped" style="font-size:12px;">
								'. $contractors_list .'
							</table>
						</div>
						
						
						<div id="c_party">
							<div class="row no-margins no-padding">
								<div class="form-group col-md-6 no-padding no-margins">
									<label for="primary_company" style="top:-14px color:#aaa; font-size:12px; font-weight:bold; width:100%;">'.$lang['CONT_HAS_RELATIONS'].'</label>
									'. $new_contact .'
								</div>
								
								<div class="input-group col-md-6" id="">
									<input type="text" class="form-control input-lg search no-margins" placeholder="'. $lang['MENU_SEARCH'] .'" style="height:25px; margin-bottom:10px; border-radius:5px; font-size:12px;" />
								</div>
							</div>
							
							<div style="height:30vh; overflow-y:auto; border-top:1px solid #e4e4e4; margin-top: 5px;">
								<ul class="folder-list m-b-md list" id="contracting_content" style="padding: 0;">
									'. $has_relation_list .'
								</ul>
							</div>
						</div>
					</div>
				</div>
				
				<div class="face back hide animated front_face" id="edit_links">
					<div class="card-block">
						<div class="pull-left" style="border-bottom:1px solid #e4e4e4;padding:4px 0 10px 0; margin-bottom:15px;width:100%;">
							<a class="rotate-btn pull-left" onclick="updateLinks(\''.$arr['id_contact'].'\');" data-card="card-2"><i class="fa fa-save"></i></a>
							<a class="rotate-btn pull-right" style="color:red;" data-card="card-2" onclick="CancelEditLinks();"><i class="fa fa-ban"></i></a>
						</div>

						<div class="form-group" style="padding-bottom:15px;">'.$arr['lastname'].' '.$arr['firstname'].'</div>

						<div class="form-group">
							<label '.$act_primary_company.' for="primary_company" style="top:-14px color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_LINKED_TO'].'</label>
							<input type="text" value="'.$arr['primary_company'].'" id="primary_company" class="form-control">
						</div>

						<div class="form-group">
							<label '.$act_gender.' for="id_supchain_type" style="top:-14px; color:#aaa; font-size:12px; font-weight:normal;">'.$lang['CONT_SUP_CHAIN'].'</label>
							<select class="form-control" id="id_supchain_type">'.$supchain_list.'</select>
						</div>

						'. $edtit_cul .'
					</div>
				</div>
				
			</div></div>';

			$id_plantation="";
			
			if($platation_ct==1){
				
				if($demog_update == 1){
					$editDemography = '<a class="rotate-btn" data-card="card-3" onclick="editDemog();"><i class="fa fa-edit"></i></a>';
				} else {
					$editDemography = '';
				}
				
				$cardTitle="";
				$survey_ques_ansList = '';
				$sql_survey = "select * from v_survey_answers where id_contact = $id ORDER BY id_surq DESC";
				$result_survey = pg_query($conn, $sql_survey);
			
				while($arr_survey = pg_fetch_assoc($result_survey)){
					if($lang['DB_LANG_stat'] == 'fr'){ $reponse=$arr_survey['ans_text_fr']; } else { $reponse=$arr_survey['ans_text_en']; }
					$survey_ques_ansList .= '<li style="padding: 5px 0;"><div style="color:#aaa; font-size:11px;">'. $arr_survey['q_text'] .'</div>'. htmlentities($reponse, ENT_QUOTES) .' </li>';
					$cardTitle = $arr_survey['description'];
				}
		

				$demography = '<div class="card-wrapper">
					<div id="card-3-modal" class="card-bg hide"></div>
					<div id="card-3" class="card-rotating effect__click">

					<div class="face front">
						<div class="contact-box" style="margin-top:30px;">
							<div class="text-center" style="margin-bottom:10px;">
								<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
								<div class="m-t-xs font-bold">'.$agent_type.'</div>
								<div class="m-t-xs">'.$arr['lastname'].' '.$arr['firstname'].'</div>
								<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
								<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
							</div>
						</div>
					
					
						<div style="border-bottom:1px solid #e4e4e4;padding:12px 0 4px 0;">
							'.$editDemography.'
						</div>

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

				</div></div>';

				// Plantation
				
				$id_farmer="";
				$plantations_list = "";
				$plantManager_list = "";
				$busiFinace_list = "";
				$environment_list = "";
				$certification_list = "";
				
				$sql_plantList = "SELECT gid_plantation, code_parcelle, culture1_name, name_town, area, area_acres, surface_ha, id_culture1 FROM v_plantation WHERE id_farmer ='$id' ORDER BY gid_plantation ASC";
				$result_plantList = pg_query($conn, $sql_plantList);
				
				$e=0;
				while($arr_plantList = pg_fetch_assoc($result_plantList)){
					if($e==0){ $id_plantation=$arr_plantList['gid_plantation']; 
						$pLclass="on3"; $pCertclass="on5"; $pEnvclass="on6"; $pbFclass="on7";  $pMclass="on8"; 
					} else { $pLclass=""; $pCertclass=""; $pEnvclass=""; $pbFclass=""; $pMclass=""; }
					
					if(!empty($arr_plantList['area'])){ $area=$arr_plantList['area']; } else { $area=0; }
					if(!empty($arr_plantList['area_acres'])){ $area_acres=$arr_plantList['area_acres']; } else { $area_acres=0; }
					if(!empty($arr_plantList['surface_ha'])){ $surface_ha=$arr_plantList['surface_ha']; } else { $surface_ha=0; }
					
					$culture1_name = getRegvalues($arr_plantList['id_culture1'], $lang['DB_LANG_stat']);
					
					$farmer_name = $arr['lastname'].' '.$arr['firstname'];
					
					$plantations_list .= '<li style="padding:5px 0 5px 0; cursor:pointer;" class="'.$pLclass.'" onclick="showContactPlantationDetails(\''. $arr_plantList['gid_plantation'] .'\');">
						<div class="row">
							<div class="col-md-2">
								<img src="./img/land.png" class="img-thumbnail" width="30" />
							</div>
							
							<div class="col-md-10">
								'. $arr_plantList['name_town'] .' - '. $arr_plantList['code_parcelle'] .' - '. $culture1_name .'
							</div>
						</div>
					</li>';
					
					$plantManager_list .= '<li style="padding:5px 0 5px 0; cursor:pointer;" class="'.$pMclass.'" onclick="showContactPlantationManagerDetails(\''. $arr_plantList['gid_plantation'] .'\');">
						<div class="row">
							<div class="col-md-2">
								<img src="./img/land.png" class="img-thumbnail" width="30" />
							</div>
							
							<div class="col-md-10">
								'. $arr_plantList['name_town'] .' - '. $arr_plantList['code_parcelle'] .' - '. $culture1_name .'
							</div>
						</div>
					</li>';
					
					$certification_list .= '<li style="padding:5px 0 5px 0; cursor:pointer;" class="'.$pCertclass.'" onclick="showContactCertificationDetails(\''. $arr_plantList['gid_plantation'] .'\');">
						<div class="row">
							<div class="col-md-2">
								<img src="./img/land.png" class="img-thumbnail" width="30" />
							</div>
							
							<div class="col-md-10">
								'. $arr_plantList['name_town'] .' - '. $arr_plantList['code_parcelle'] .' - '. $culture1_name .'
							</div>
						</div>
					</li>';
					
					$environment_list .= '<li style="padding:5px 0 5px 0; cursor:pointer;" class="'.$pEnvclass.'" onclick="showContactEnvironmentDetails(\''. $arr_plantList['gid_plantation'] .'\');">
						<div class="row">
							<div class="col-md-2">
								<img src="./img/land.png" class="img-thumbnail" width="30" />
							</div>
							
							<div class="col-md-10">
								'. $arr_plantList['name_town'] .' - '. $arr_plantList['code_parcelle'] .' - '. $culture1_name .'
							</div>
						</div>
					</li>';
					
					$busiFinace_list .= '<li style="padding:5px 0 5px 0; cursor:pointer;" class="'.$pbFclass.'" onclick="showContactbusiFinaceDetails(\''. $arr_plantList['gid_plantation'] .'\');">
						<div class="row">
							<div class="col-md-2">
								<img src="./img/land.png" class="img-thumbnail" width="30" />
							</div>
							
							<div class="col-md-10">
								'. $arr_plantList['name_town'] .' - '. $arr_plantList['code_parcelle'] .' - '. $culture1_name .'
							</div>
						</div>
					</li>';
					
					$e++;
				}
				

				if($plant_update == 1){
					$editPlantation = '<a class="rotate-btn" data-card="card-4" onclick="editPlantation();"><i class="fa fa-edit"></i></a>';
				} else {
					$editPlantation = '';
				}
				
			
				$plantation = '<div class="contact-box" style="margin-top:30px;">
						<div class="text-center" style="margin-bottom:10px;">
							<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
							<div class="m-t-xs font-bold">'.$agent_type.'</div>
							<div class="m-t-xs">'.$arr['lastname'].' '.$arr['firstname'].'</div>
							<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
							<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
						</div>
					</div>
				
				  <div style="overflow-y: auto; overflow-x: hidden; height: 120px;">
					<ul class="folder-list m-b-md list" id="plantation_content" style="padding: 0;">
						'. $plantations_list .'
					</ul>
				  </div>
				
				
				<div id="ct_plant_content">
				  
				</div>';

			} else {
				$demography = '';
				$plantation = '';
			}


			$householdList = '';
			$sql_householdList = "SELECT id_household, avatar_path, contact_id, firstname, lastname, birth_year, id_relation FROM v_contact_household WHERE contact_id='$id'";
			$result_householdList = pg_query($conn, $sql_householdList);
			
			$g=0;
			$id_household="";
			while($arr_householdList = pg_fetch_assoc($result_householdList)){
				if($g==0){ $id_household=$arr_householdList['id_household']; $hhLclass="on4"; } else { $hhLclass=""; }
				
				if($arr_householdList['id_relation'] == 551) {
					$id = $arr_householdList['contact_id'];
					$sql_avatar = "SELECT doc_link FROM contact_docs WHERE contact_id = $id 
						AND doc_type = 154  AND id_household IS NULL
					ORDER BY id_condoc DESC LIMIT 1";
					$result_avatar = pg_query($conn, $sql_avatar);
					$arr_avatar = pg_fetch_assoc($result_avatar);
					
					if($arr_avatar['doc_link']) {
						$img_link = $arr_avatar['doc_link'];
					} else {
						$img_link = './img/household.png';	
					}
				} else {
					if($arr_householdList['avatar_path']!="") { $img_link = $arr_householdList['avatar_path']; }
					else { $img_link = './img/household.png'; }
				}
				
				$age = date('Y') - $arr_householdList['birth_year'];
			
				$householdList .= '<li style="padding:5px 0 5px 0; cursor:pointer;" class="'.$hhLclass.'" onclick="showContactHouseholdDetails(\''. $arr_householdList['id_household'] .'\',\'contact\');">
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
		
			$household = '<div class="row">
				<div class="col-md-12">
					<div class="contact-box" style="margin-top:30px;">
						<div class="text-center" style="margin-bottom:10px;">
							<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
							<div class="m-t-xs font-bold">'.$agent_type.'</div>
							<div class="m-t-xs">'.$arr['lastname'].' '.$arr['firstname'].'</div>
							<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
							<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
						</div>
						
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
					
					<ul class="folder-list m-b-md list" id="household_list_content" style="padding: 0;">
						'. $householdList .'
					</ul>
				</div>
			</div>';
			
			
			$manager = '<div class="contact-box" style="margin-top:30px;">
					<div class="text-center" style="margin-bottom:10px;">
						<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
						<div class="m-t-xs font-bold">'.$agent_type.'</div>
						<div class="m-t-xs">'.$arr['lastname'].' '.$arr['firstname'].'</div>
						<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
						<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
					</div>
				</div>
				
				<div style="overflow-y: auto; overflow-x: hidden; height: 120px;">
					<ul class="folder-list m-b-md list" id="plantManager_content" style="padding: 0;">
						'. $plantManager_list .'
					</ul>
				</div>
			';
			
			
			$certification = '<div class="contact-box" style="margin-top:30px;">
					<div class="text-center" style="margin-bottom:10px;">
						<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
						<div class="m-t-xs font-bold">'.$agent_type.'</div>
						<div class="m-t-xs">'.$arr['lastname'].' '.$arr['firstname'].'</div>
						<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
						<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
					</div>
				</div>
				
				<div style="overflow-y: auto; overflow-x: hidden; height: 120px;">
					<ul class="folder-list m-b-md list" id="certification_content" style="padding: 0;">
						'. $certification_list .'
					</ul>
				</div>
					
			  <div id="ct_certification_content">
				  
			</div>';
			
			
			$environment = '<div class="contact-box" style="margin-top:30px;">
					<div class="text-center" style="margin-bottom:10px;">
						<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
						<div class="m-t-xs font-bold">'.$agent_type.'</div>
						<div class="m-t-xs">'.$arr['name'].'</div>
						<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
						<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
					</div>
				</div>
				
				<div style="overflow-y: auto; overflow-x: hidden; height: 120px;">
					<ul class="folder-list m-b-md list" id="environment_content" style="padding: 0;">
						'. $environment_list .'
					</ul>
				</div>
				
			  <div id="ct_environment_content">
				  
			</div>';
			
			
			$busiFinace = '<div class="contact-box" style="margin-top:30px;">
					<div class="text-center" style="margin-bottom:10px;">
						<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
						<div class="m-t-xs font-bold">'.$agent_type.'</div>
						<div class="m-t-xs">'.$arr['lastname'].' '.$arr['firstname'].'</div>
						<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
						<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
					</div>
				</div>
				
				<div style="overflow-y: auto; overflow-x: hidden; height: 120px;">
					<ul class="folder-list m-b-md list" id="busiFinace_content" style="padding: 0;">
						'. $busiFinace_list .'
					</ul>
				</div>
				
			  <div id="ct_busiFinace_content">
				  
			</div>';
			
			
			$activityLog = '<div class="contact-box" style="margin-top:30px;">
					<div class="text-center" style="margin-bottom:10px;">
						<img alt="image" class="img-circle" height="70" width="70" src="'.$avatar.'">
						<div class="m-t-xs font-bold">'.$agent_type.'</div>
						<div class="m-t-xs">'.$arr['lastname'].' '.$arr['firstname'].'</div>
						<div class="m-t-xs">'.$arr['name_cooperative'].'</div>
						<div style="height:2px; width:100%; margin-top:5px;" class="bg-success"></div>
					</div>
				</div>
				
			  <div id="ct_activityLog_content">
				  
			</div>';
			
			$activity_list = '';
			
			$sql_activity="SELECT mobcrmticker.id_mobconticker, 
				mobcrmticker.field_name, 
				mobcrmticker.field_value, 
				mobcrmticker.ticker_time,
				get_contact_name(mobcrmticker.id_contact) AS name,
				get_contact_name(mobcrmticker.id_agent) agentname,
				get_town_name(contact.id_town) town_name
				FROM mobcrmticker, contact
				WHERE mobcrmticker.id_contact = $id
				AND contact.id_contact = mobcrmticker.id_contact 
				ORDER BY mobcrmticker.id_mobconticker DESC
			";
			
			$result_activity = pg_query($conn, $sql_activity);
			while($row_activity = pg_fetch_assoc($result_activity)){
				$activity_list .= '<tr>
					<td><strong>'. $row_activity['agentname'] .'</strong></td>
					<td style="width:120px;">'. $row_activity['field_name'] .' </td>
					<td style="width:120px;">'. substr($row_activity['field_value'], 0, 22) .'</td>
					<td>'. $row_activity['town_name'] .' </td>
					<td>'. $row_activity['ticker_time'] .' </td>
				</tr>';
			}
			

			$dom = $contact.'@@'.$demography.'@@'.$links.'@@'.$plantation.'@@'.$primary_company.'@@'.$coordx.'@@'.$coordy.'@@'.$id_supchain_type.'@@'.$household.'@@'.$id_household.'@@'.$id_plantation.'@@'.$contactDocs.'@@'.$certification.'@@'.$environment.'@@'.$busiFinace.'@@'.$activityLog.'@@'.$manager.'@@'.$activity_list;

        break;
		
		
		case "update_plantation":

			$id_plantation = $_GET["id_plantation"];
			
			if(isset($_GET["dc_completed"])){
				$dc_completed = $_GET["dc_completed"];
				$dc_completed_edit = "dc_completed='$dc_completed',";
			} else { $dc_completed_edit = ""; }
			
			if(isset($_GET["bio"])){
				$bio = $_GET["bio"];
				$bio_edit = "bio='$bio',";
			} else { $bio_edit = ""; }
			
			if(isset($_GET["bio_suisse"])){
				$bio_suisse = $_GET["bio_suisse"];
				$bio_suisse_edit = "bio_suisse='$bio_suisse',";
			} else { $bio_suisse_edit = ""; }
			
			if(isset($_GET["name_town"])){
				$name_town = $_GET["name_town"];
				$name_town_edit = "name_town='$name_town',";
			} else { $name_town_edit = ""; }
			
			if(isset($_GET["area"])){
				$area = $_GET["area"];
				$area_edit = "area='$area',";
			} else { $area_edit = ""; }
			
			if(isset($_GET["area_acres"])){
				$area_acres = $_GET["area_acres"];
				$area_acres_edit = "area_acres='$area_acres',";
			} else { $area_acres_edit = ""; }
			
			if(isset($_GET["surface_ha"])){
				$surface_ha = $_GET["surface_ha"];
				$surface_ha_edit = "surface_ha='$surface_ha',";
			} else { $surface_ha_edit = ""; }
			
			if(isset($_GET["year_creation"])){
				$year_creation = $_GET["year_creation"];
				$year_creation_edit = "year_creation='$year_creation',";
			} else { $year_creation_edit = ""; }
			
			if(isset($_GET["variety"])){
				$variety = $_GET["variety"];
				$variety_edit = "variety='$variety',";
			} else { $variety_edit = ""; }
			
			if(isset($_GET["statut"])){
				$statut = $_GET["statut"];
				$statut_edit = "statut='$statut',";
			} else { $statut_edit = ""; }
			
			if(isset($_GET["property"])){
				$property = $_GET["property"];
				$property_edit = "property='$property',";
			} else { $property_edit = ""; }
			
			if(isset($_GET["title_deed"])){
				$title_deed = $_GET["title_deed"];
				$title_deed_edit = "title_deed='$title_deed',";
			} else { $title_deed_edit = ""; }
			
			if(isset($_GET["perimeter"])){
				$perimeter = $_GET["perimeter"];
				$perimeter_edit = "perimeter='$perimeter',";
			} else { $perimeter_edit = ""; }
			
			if(isset($_GET["eco_river"])){
				$eco_river = $_GET["eco_river"];
				$eco_river_edit = "eco_river='$eco_river',";
			} else { $eco_river_edit = ""; }
			
			if(isset($_GET["eco_shallows"])){
				$eco_shallows = $_GET["eco_shallows"];
				$eco_shallows_edit = "eco_shallows='$eco_shallows',";
			} else { $eco_shallows_edit = ""; }
			
			if(isset($_GET["eco_wells"])){
				$eco_wells = $_GET["eco_wells"];
				$eco_wells_edit = "eco_wells='$eco_wells',";
			} else { $eco_wells_edit = ""; }
			
			if(isset($_GET["seed_type"])){
				$seed_type = $_GET["seed_type"];
				$seed_type_edit = "seed_type='$seed_type',";
			} else { $seed_type_edit = ""; }
			
			if(isset($_GET["name_manager"])){
				$name_manager = $_GET["name_manager"];
				$name_manager_edit = "name_manager='$name_manager',";
			} else { $name_manager_edit = ""; }
			
			if(isset($_GET["manager_phone"])){
				$manager_phone = $_GET["manager_phone"];
				$manager_phone_edit = "manager_phone='$manager_phone',";
			} else { $manager_phone_edit = ""; }
			
			if(isset($_GET["inactive"])){
				$inactive = $_GET["inactive"];
				$inactive_edit = "inactive='$inactive',";
			} else { $inactive_edit = ""; }
			
			if(isset($_GET["inactive_date"])){
				$inactive_date = $_GET["inactive_date"];
				$inactive_date_edit = "inactive_date='$inactive_date',";
			} else { $inactive_date_edit = ""; }
			
			$notes = $_GET["notes"];
			

			$sql_stats = "UPDATE public.plantation
			   SET $dc_completed_edit $bio_edit $bio_suisse_edit $name_town_edit $area_edit $area_acres_edit
				   $surface_ha_edit $year_creation_edit $statut_edit $variety_edit $property_edit 
				   $title_deed_edit $perimeter_edit $eco_river_edit $eco_shallows_edit $eco_wells_edit
				   $seed_type_edit $name_manager_edit $manager_phone_edit $inactive_edit $inactive_date_edit
				   notes='$notes'
			WHERE id_plantation ='$id_plantation'";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##Profil updated successfully";
			} else {
				$dom="0##Unable to update profil";
			}

		break;
		
		case "update_links":

			$id = $_GET["id"];
			$id_supchain_type = $_GET["id_supchain_type"];

			$sql_stats = "UPDATE public.contact
			   SET id_supchain_type='$id_supchain_type'
			WHERE id_contact ='$id'";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##Profil updated successfully";
			} else {
				$dom="0##Unable to update profil";
			}

		break;
		
		case "second_show_contact_form":
		
			$id = $_GET['id_contact'];
			
			$sql_stats = "SELECT * FROM v_icw_contacts WHERE id_contact ='$id'";

			$result = pg_query($conn, $sql_stats);
			$arr = pg_fetch_assoc($result);

			$dom='<div class="card-block">
				<div class="pull-left" style="border-bottom:1px solid #e4e4e4;padding:4px 0 10px 0; margin-bottom:15px;width:100%;">
					<a class="rotate-btn pull-left" onclick="editContactContent(\''.$arr['id_contact'].'\');" data-card="card-1"><i class="fa fa-pen-square"></i></a>
					<a class="rotate-btn pull-right" style="color:red;" data-card="card-1" onclick="CancelEditBio2();"><i class="fa fa-times"></i></a>
				</div>	
			
				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">First Name</label><br/>
					'.$arr['firstname'].'
				</div>
				
				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Last Name</label><br/>
					'.$arr['lastname'].'
				</div>

				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Middlename</label><br/>
					'.$arr['middlename'].'
				</div>

				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Mobile 1</label><br/>
					'.$arr['phone1'].'
				</div>
			
				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Mobile 2</label><br/>
					'.$arr['phone2'].'
				</div>

				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Mobile 3</label><br/>
					'.$arr['p_phone3'].'
				</div>
			
				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Mobile Money</label><br/>
					'.$arr['p_phone4'].'
				</div>
				
				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Phone Fix</label><br/>
					'.$arr['p_phone5'].'
				</div>
				
				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">eMail Business *</label><br/>
					'.$arr['p_email'].'
				</div>
				
				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">eMail Private</label><br/>
					'.$arr['p_email2'].'
				</div>
				
				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Skype id</label><br/>
					'.$arr['skype_id'].'
				</div>
				
				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Address</label><br/>
					'.$arr['p_street'].'
				</div>
				
				<div class="form-group">
					<label style="top:10px; color:#aaa; font-size:12px; font-weight:normal;">Town</label><br/>
					'.$arr['name_town'].'
				</div>
				
				<div class="form-group">
					<label style="top:10px; color:#aaa; font-size:12px; font-weight:normal;">Postal Code</label><br/>
					'.$arr['postalcode'].'
				</div>
			
				<div class="form-group">
					<label style="top:-14px; color:#aaa; font-size:12px; font-weight:normal;">Gender</label><br/>
					'.$arr['gender'].'
				</div>

				<div class="form-group">
					<label style="color:#aaa; font-size:12px; font-weight:normal;">Birthday</label><br/>
					'.$arr['birthday'].'
				</div>
				
				<div class="form-group">
					<label '.$act_national_lang.' for="ctE2_national_lang" style="color:#aaa; font-size:12px; font-weight:normal;">National Language</label><br/>
					'.$arr['lang_name'].'
				</div>
				
				<div class="form-group">
					<label style="top:10px; color:#aaa; font-size:12px; font-weight:normal;">Note</label><br/>
					'.$arr['notes'].'
				</div>
			</div>';
			
		break;
		
		case "contact_list":

			$contact_list = '';
			$conf = $_GET["conf"];
			$status = $_GET["status"];
			$completed = $_GET["completed"];
			$pending = $_GET["pending"];
			$id_cooperative = $_GET["id_cooperative"];
			
			$id_contact = $_SESSION['id_contact'];
			$id_user = $_SESSION['id_user'];

			if($status!=0){ $cond2 = " mobile_created='$status' AND "; } else { $cond2=""; }
			
			if(($completed == 1) AND ($pending == 1)) {
				$completed_cond=" AND dc_completed IN (1,0)"; 
			} else
			if(($completed == 1) AND ($pending == 0)){ 
				$completed_cond=" AND dc_completed=1"; 
			} else 
			if(($completed == 0) AND ($pending == 1)){ 
				$completed_cond=" AND dc_completed=0"; 
			} else { $completed_cond=""; }
			
			if(($conf!=0) AND ($conf!=115)){ $cond=" AND id_type = '$conf'"; $where=" where $cond2 id_category is null and id_type=$conf "; }
			elseif($conf==115) { $cond=" AND id_type = '9'"; $where=" where $cond2 id_category=6 and id_supchain_type=115 $completed_cond "; }
			else { $cond=''; $where=""; }
			
			if($id_cooperative!=0){
				$coop_cond=" and id_cooperative=$id_cooperative 
				";
			} else { $coop_cond=""; }
			
			
			if(($conf == 9) OR ($conf == 115)){
				$sql_stats= "select * from (
					select * from contact where id_contact in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) ) $cond
					UNION
					select * from contact where id_primary_company in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )
					and id_contact in ( select id_contact from users ) $cond
					union
					select * from contact where id_contact in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) ) $cond
					union
					select * from contact where id_primary_company in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )
					and id_contact in ( select id_contact from users ) $cond
					union
					select * from contact where id_primary_company in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) $cond
					union
					select * from contact where id_primary_company in ( select id_link from contact_links where
					id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )) $cond
					union
					select * from contact where id_primary_company in ( select id_contact from contact_links where
					id_link in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )) $cond
					union
					select * from contact where id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) $cond
					UNION
					select * from contact where id_cooperative in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) $cond
					ORDER BY name ASC ) c $where $coop_cond
				";
			} 
			
			if($conf == 10){
				$sql_stats = "select * from contact where id_contact in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) ) $cond
					UNION
					select * from contact where id_primary_company in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )
					and id_contact in ( select id_contact from users ) $cond
					union
					select * from contact where id_contact in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) ) $cond
					union
					select * from contact where id_primary_company in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )
					and id_contact in ( select id_contact from users ) $cond
					union
					select * from contact where id_primary_company in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) $cond
					union
					select * from contact where id_primary_company in ( select id_link from contact_links where
					id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )) $cond
					union
					select * from contact where id_primary_company in ( select id_contact from contact_links where
					id_link in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )) $cond 
					union
					select * from contact where id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) $cond 
					ORDER BY name ASC $coop_cond
				";
			}

			$result = pg_query($conn, $sql_stats);

			while($arr = pg_fetch_assoc($result)){
				$mark = ''; $pin = '';
				$sql_plan = "SELECT geom_json, coordx, coordy FROM public.v_plantation WHERE v_plantation.id_farmer = '". $arr['id_contact'] ."'";
				$result_plan = pg_query($conn, $sql_plan);
				
				while($row_plantation = pg_fetch_assoc($result_plan)){
					if($row_plantation['geom_json']!=null) { $mark = '<i class="fa fa-map-marker" style="color:#999898; font-size:12px;"></i>'; } 
					if(($row_plantation['coordx']!=null)AND($row_plantation['coordy']!=null)) { $pin = '<i class="fa fa-map-pin" style="color:#999898; font-size:12px;"></i>'; } 
				}
				
				$contact_name = $arr['name'];
				if(!empty($arr['contact_code'])) { $code = ' ('.$arr['contact_code'].')'; } else { $code=""; }
				if($arr['dc_completed']==1) { $dc_completed = '<i class="fas fa-check" style="color:green";></i>'; } else { $dc_completed=""; }
				
				if($arr['mobile_created']==643) { $label = '<span class="label label-danger pull-right">'. getRegvalues(643, $lang['DB_LANG_stat']) .'</span>'; } 
				elseif($arr['mobile_created']==644) { $label = '<span class="label label-warning pull-right">'. getRegvalues(644, $lang['DB_LANG_stat']) .'</span>'; } 
				elseif($arr['mobile_created']==645) { $label = '<span class="label label-primary pull-right">'. getRegvalues(645, $lang['DB_LANG_stat']) .'</span>'; } 
				elseif($arr['mobile_created']==663) { $label = '<span class="label label-success pull-right">'. getRegvalues(663, $lang['DB_LANG_stat']) .'</span>'; } 
				elseif($arr['mobile_created']==664) { $label = '<span class="label label-default pull-right">'. getRegvalues(664, $lang['DB_LANG_stat']) .'</span>'; } 
				elseif($arr['mobile_created']==665) { $label = '<span class="label label-info pull-right">'. getRegvalues(665, $lang['DB_LANG_stat']) .'</span>'; } 
				elseif($arr['mobile_created']==666) { $label = '<span class="label pull-right">'. getRegvalues(666, $lang['DB_LANG_stat']) .'</span>'; } 
				else { $label =""; }
				
				$contact_list .= '<li>
					<a href="javascript:showContact(\''. $arr['id_contact'] .'\',\''. $conf .'\');" class="contact_name">
						'. $dc_completed. htmlentities($contact_name, ENT_QUOTES) . $label .'
						<span style="display:none;">'. $arr['id_contact'] .'</span>
						<span style="display:none;">'. $arr['contact_code'] .'</span>
						<div style="color:#aaa; font-size:12px;">'. $code .'</div>
						<div style="color:#aaa; font-size:10px;">'. $arr['town_name'] .'<span class="pull-right">' . $mark .'&nbsp;'. $pin .'</span></div>
					</a>
				</li>';
			}

			$count_ple='';
			$count_org='';
			$count_farmer='';

			$sql_count_pp = "select count(*) from  (
				select * from contact where id_contact in (
				select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) ) 
				UNION
				select * from contact where id_primary_company in (
				select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) )
				and id_contact in ( select id_contact from users ) 
				union
				select * from contact where id_contact in (
				select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) )  
				union
				select * from contact where id_primary_company in (
				select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) )
				and id_contact in ( select id_contact from users )  
				union
				select * from contact where id_primary_company in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) 
				union
				select * from contact where id_primary_company in ( select id_link from contact_links where
				id_contact in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) )) 
				union
				select * from contact where id_primary_company in ( select id_contact from contact_links where
				id_link in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ))  
				union
				select * from contact where id_contact in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) )  
				ORDER BY name ASC )  c 
			Where id_type=9 and id_category is null";
			
			$result_count_pp = pg_query($conn, $sql_count_pp);
			$arr_count_pp = pg_fetch_assoc($result_count_pp);
			$count_ple=$arr_count_pp['count'];
			
			
			$sql_count_org = "select count(*) from  (
				select * from contact where id_contact in (
				select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) ) 
				UNION
				select * from contact where id_primary_company in (
				select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) )
				and id_contact in ( select id_contact from users ) 
				union
				select * from contact where id_contact in (
				select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) )  
				union
				select * from contact where id_primary_company in (
				select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) )
				and id_contact in ( select id_contact from users )  
				union
				select * from contact where id_primary_company in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ) 
				union
				select * from contact where id_primary_company in ( select id_link from contact_links where
				id_contact in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) )) 
				union
				select * from contact where id_primary_company in ( select id_contact from contact_links where
				id_link in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) ))  
				union
				select * from contact where id_contact in ( select id_primary_company from contact where
				id_contact=(select id_contact from users where id_user=$id_user ) )  
				ORDER BY name ASC )  c 
			Where id_type=10";
			
			$result_count_org = pg_query($conn, $sql_count_org);
			$arr_count_org = pg_fetch_assoc($result_count_org);
			$count_org=$arr_count_org['count'];
			
		
			
			$sql_countFarmer = "select count(*) as tt_farmers from (
					select * from contact where id_contact in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )
					UNION
					select * from contact where id_primary_company in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )
					and id_contact in ( select id_contact from users )
					union
					select * from contact where id_contact in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) ) 
					union
					select * from contact where id_primary_company in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )
					and id_contact in ( select id_contact from users ) 
					union
					select * from contact where id_primary_company in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) 
					union
					select * from contact where id_primary_company in ( select id_link from contact_links where
					id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ))
					union
					select * from contact where id_primary_company in ( select id_contact from contact_links where
					id_link in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ))
					union
					select * from contact where id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) 
					UNION
					select * from contact where id_cooperative in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )
					ORDER BY name ASC ) c where id_category=6 and id_supchain_type=115"; 

			$result_countFarmer = pg_query($conn, $sql_countFarmer);
			$arr_countFarmer = pg_fetch_assoc($result_countFarmer);
			$count_farmer = $arr_countFarmer['tt_farmers'];
		
			$dom = $contact_list.'##'.$count_ple.'##'.$count_org.'##'.$count_farmer.'##'.$sql_stats;

        break;
		
		
		case "refresh_contact_list":
			
			$id=$_GET['id_primary_company'];
			$update_right=$_GET['update_right'];
			
			$contactList = '';
			$sql_contactList = "select * from v_icw_contacts where id_primary_company = $id";
			$result_contactList = pg_query($conn, $sql_contactList);
		
			while($arr_contactList = pg_fetch_assoc($result_contactList)){
				if(file_exists('img/avatar/' . $arr_contactList['id_contact'] . ".jpg")) {
					$avatar = 'img/avatar/' . $arr_contactList['id_contact'] . ".jpg";
				} else { $avatar = 'img/user.jpg'; }
				
				$contactList .= '<tr><td><img src="'.$avatar.'" class="img-circle" height="35" /></td>
				<td>'. $arr_contactList['firstname'] .' '. $arr_contactList['lastname'] .'
				<div style="color:#aaa; font-size:9px;">'. $arr_contactList['name_town'] .'</div></td>';
				
				if($update_right == 1){
					$contactList .= '<td><a class="rotate-btn" data-card="card-1" onclick="editBio2('. $arr_contactList['id_contact'] .');">
					<i class="fa fa-eye"></i></a></td>';
				} else {
					$contactList .= '<td></td>';
				}
			
				$contactList .= '</tr>';
			}
			
			$dom=$contactList;
			
		break;
		
		
		case "check_out":
		
			$id_contact = $_GET['id_contact'];
			$check_out = $_GET['check_out'];
			$id_user = $_SESSION['id_contact'];
			$check_out_date = gmdate("Y/m/d H:i");
			
			$sql_stats = "UPDATE public.contact SET check_out=$check_out,
				check_out_date='$check_out_date', check_out_by='$id_user'
			WHERE id_contact ='$id_contact'";

			$result = pg_query($conn, $sql_stats);

			if($result){
				$dom=1;
			} else {
				$dom=$sql_stats;
			}
		
		break;
	}
	
}

echo $dom;