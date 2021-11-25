<?php

session_start();
error_reporting(0);

if(!isset($_SESSION['username'])){
	header("Location: ../login.php");
}


include_once("../fcts.php");
include_once("../common.php");

define('ID_USER', 'no_reply@icoop.live');
define('ID_PASS', 'Qwerty4321');

require '../vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

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
				$html .= '<li><a href="javascript:showSurvQuestions(\''. $arr['id_survey'] .'\');" class="sv_template_name">
					'. htmlentities($arr['description'], ENT_QUOTES) .' 
					<span class="label label-danger pull-right">'. getRegvalues($arr['survey_type'], $lang['DB_LANG_stat']) .'</span>
					<div style="color:#aaa; font-size:12px;">'. $arr['survey_date'] .'</div>
				</a></li>';
			}
			
			$dom=$html;
		
		break;
		
		
		case "questions_list":
		
			$id_survey = $_GET['id_survey'];
			$deleteRight = $_GET['deleteRight'];
			$editRight = $_GET['editRight'];
		
			$sql = "SELECT * FROM public.sur_questions WHERE surtemplate_id = $id_survey ORDER BY q_seq ASC";
			$result = pg_query($conn, $sql);

			if($deleteRight ==1){ $delete=""; }else{ $delete="hide"; }
			if($editRight ==1){ $edit=""; }else{ $edit="hide"; }
			
			$html="";
			while($arr = pg_fetch_assoc($result)){
				$html .= '<tr>
					<td><input type="radio" value="'. $arr['id_surq'] .'" id="radioSvQst'. $arr['id_surq'] .'" name="id_sv_question_radio" onchange="showSurvAnswers(\''. $arr['id_surq'] .'\');" class="radioBtnDefClass"></td>
					<td style="padding:10px 0 5px 0;"><label for="radioSvQst'. $arr['id_surq'] .'" style="font-weight: normal;">
						<strong>'. $arr['q_seq'] .' - </strong> '. $arr['q_text'] .'
					</label></td>
					<td class="row_actions">
						<a href="#" class="'.$edit.'" data-toggle="modal" onclick="editQuestion(\''. $arr['id_surq'] .'\');" data-target="#surveyModal"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
						<a href="#" class="'.$delete.'"><i class="fa fa-trash" onclick="delQuestion(\''. $arr['id_surq'] .'\');" aria-hidden="true"></i></a>
					</td>
				</tr>';
			}
			
			$dom=$html;
		
		break;
		
		
		case "answers_list":
		
			$id_surq = $_GET['id_surq'];
			$deleteRight = $_GET['deleteRight'];
			$editRight = $_GET['editRight'];
		
			$sql = "SELECT * FROM public.sur_answers WHERE surq_id = $id_surq";
			$result = pg_query($conn, $sql);
			
			if($deleteRight ==1){ $delete=""; }else{ $delete="hide"; }
			if($editRight ==1){ $edit=""; }else{ $edit="hide"; }

			$html="";
			while($arr = pg_fetch_assoc($result)){
				$html .= '<tr>
					<td style="padding:10px 0 5px 0;"><span for="radioSvAsw'. $arr['id_suranswer'] .'">
						<strong>En</strong> : '. $arr['ans_text_en'] .'<br/>
						<strong>Fr</strong> : '. $arr['ans_text_fr'] .'
					</span></td>
					<td class="row_actions">
						<a href="#" class="'.$edit.'" data-toggle="modal" onclick="editAnswer(\''. $arr['id_suranswer'] .'\');" data-target="#surveyModal"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
						<a href="#" class="'.$delete.'"><i class="fa fa-trash" onclick="delAnswer(\''. $arr['id_suranswer'] .'\');" aria-hidden="true"></i></a>
					</td>
				</tr>';
			}
			
			$dom=$html;
		
		break;
		
		
		case "save_new_template":
		
			$description = $_GET['description'];
			$survey_date = $_GET['survey_date'];
			
			$sql = "INSERT INTO sur_template ( description, survey_date, survey_type ) values ( '$description', '$survey_date', 66 )";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "save_new_question":
		
			$surtemplate_id = $_GET['surtemplate_id'];
			$q_seq = $_GET['q_seq'];
			$q_text = $_GET['q_text'];
			
			$sql = "INSERT INTO sur_questions ( surtemplate_id, q_seq, q_text ) values ( '$surtemplate_id', '$q_seq', '{$q_text}' )";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "save_new_answer":
		
			$id_surq = $_GET['id_surq'];
			$ans_text_en = pg_escape_string($_GET['ans_text_en']);
			
			if(isset($_GET["ans_text_fr"])){
				$ans_text_fr = pg_escape_string($_GET["ans_text_fr"]);
				$ans_text_fr_field = "ans_text_fr,";
				$ans_text_fr_value = "'{$ans_text_fr}',";
			} else { $ans_text_fr_field = ""; $ans_text_fr_value = ""; }
			
			$ans_code = $_GET['ans_code'];
			$score = $_GET['score'];
			
			$sql = "INSERT INTO sur_answers ( surq_id, $ans_text_fr_field ans_text_en, ans_code, score ) values ( '$id_surq', $ans_text_fr_value '{$ans_text_en}', '$ans_code', '$score' )";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "edit_answer":
		
			$id_suranswer = $_GET['id_suranswer'];
		
			$sql = "SELECT * FROM public.sur_answers WHERE id_suranswer = $id_suranswer";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
		
			$dom = '<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="surv_score">Score *</label>
						<input type="number" class="form-control" value="'.$arr['score'].'" id="surv_score">
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label for="surv_ans_code">Code *</label>
						<input type="text" class="form-control" value="'.$arr['ans_code'].'" id="surv_ans_code">
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="form-group">
						<label for="surv_ans_text_en">Answer En *</label>
						<textarea class="form-control" id="surv_ans_text_en">'.$arr['ans_text_en'].'</textarea>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="form-group">
						<label for="surv_ans_text_fr">Answer Fr</label>
						<textarea class="form-control" id="surv_ans_text_fr">'.$arr['ans_text_fr'].'</textarea>
					</div>
				</div>
			</div>';
		
		break;
		
		
		case "save_edited_answer":
		
			$id_suranswer = $_GET['id_suranswer'];
			$ans_text_en = pg_escape_string($_GET['ans_text_en']);
			
			if(isset($_GET["ans_text_fr"])){
				$ans_text_fr = pg_escape_string($_GET["ans_text_fr"]);
				$ans_text_fr_edit = "ans_text_fr = '{$ans_text_fr}',";
			} else { $ans_text_fr_edit = ""; }
			
			$ans_code = $_GET['ans_code'];
			$score = $_GET['score'];
			
			$sql = "UPDATE sur_answers SET  $ans_text_fr_edit ans_text_en='{$ans_text_en}', ans_code='$ans_code', score='$score' WHERE id_suranswer=$id_suranswer";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "edit_question":
		
			$id_surq = $_GET['id_surq'];
		
			$sql = "SELECT * FROM public.sur_questions WHERE id_surq = $id_surq";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			$dom = '<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="surv_q_seq">Sequence *</label>
						<input type="number" class="form-control" value="'.$arr['q_seq'].'" id="surv_q_seq">
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="form-group">
						<label for="surv_q_text">Question *</label>
						<input type="text" class="form-control" value="'.$arr['q_text'].'" id="surv_q_text">
					</div>
				</div>
			</div>';
		
		break;
		
		
		case "save_edited_question":
		
			$id_surq = $_GET['id_surq'];
			$q_seq = $_GET['q_seq'];
			$q_text = $_GET['q_text'];
			
			$sql = "UPDATE sur_questions SET q_text='{$q_text}', q_seq='$q_seq' WHERE id_surq=$id_surq";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "delete_answer":
		
			$id_suranswer = $_GET['id_suranswer'];
			
			$sql = "DELETE FROM public.sur_answers WHERE id_suranswer = $id_suranswer";
			$result = pg_query($conn, $sql);
			
			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "delete_question":
		
			$id_surq = $_GET['id_surq'];
			
			$sql = "DELETE FROM public.sur_answers WHERE surq_id = $id_surq";
			$result = pg_query($conn, $sql);
			
			if ($result) {
				$sql2 = "DELETE FROM public.sur_questions WHERE id_surq = $id_surq";
				$result2 = pg_query($conn, $sql2);
				
				if ($result2) {
					$dom=1;
				} else {
					$dom=0;
				}
				
			} else {
				$dom=$sql;
			}
		
		break;
		
		
		case "organisation_list":
		
			$conf = $_GET['conf'];
			$contact_list="";
			$id_user = $_SESSION['id_user'];
			
			$sql_stats = "select * from contact where id_contact in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )   AND id_type = 10
					UNION
					select * from contact where id_primary_company in (
					select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )  AND id_type = 10
					and id_contact in ( select id_contact from users ) 
					union
					select * from contact where id_contact in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )  AND id_type = 10
					union
					select * from contact where id_primary_company in (
					select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ) )  AND id_type = 10
					and id_contact in ( select id_contact from users ) 
					union
					select * from contact where id_primary_company in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = 10
					union
					select * from contact where id_primary_company in ( select id_link from contact_links where
					id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ))  AND id_type = 10
					union
					select * from contact where id_primary_company in ( select id_contact from contact_links where
					id_link in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) ))  AND id_type = 10
					union
					select * from contact where id_contact in ( select id_primary_company from contact where
					id_contact=(select id_contact from users where id_user=$id_user ) )  AND id_type = 10
					ORDER BY name ASC 
				";
			
			$result = pg_query($conn, $sql_stats);

			while($arr = pg_fetch_assoc($result)){

				$contact_name = $arr['name'];
				if(!empty($arr['contact_code'])) { $code = ' ('.$arr['contact_code'].')'; } else { $code=""; }
				
				if($conf == 'campaign'){
					$contact_list .= '<li>
						<a href="javascript:showCampContacts(\''. $arr['id_contact'] .'\');" class="camp_org_name">
							'. htmlentities($contact_name, ENT_QUOTES) .'
							<span style="display:none;">'. $arr['id_contact'] .'</span>
							<span style="display:none;">'. $arr['contact_code'] .'</span>
							<div style="color:#aaa; font-size:12px;">'. $code .'</div>
						</a>
					</li>';
				
				} else {
					$contact_list .= '<li>
						<a href="javascript:showCampResultContacts(\''. $arr['id_contact'] .'\');" class="campResult_org_name">
							'. htmlentities($contact_name, ENT_QUOTES) .'
							<span style="display:none;">'. $arr['id_contact'] .'</span>
							<span style="display:none;">'. $arr['contact_code'] .'</span>
							<div style="color:#aaa; font-size:12px;">'. $code .'</div>
						</a>
					</li>';
				}
			}
			
			$template_list="";
			$sql = "SELECT * FROM public.sur_template ORDER BY description DESC";
			$result2 = pg_query($conn, $sql);

			while($arr2 = pg_fetch_assoc($result2)){
				$template_list .= '<option value="'. $arr2['id_survey'] .'">'. htmlentities($arr2['description'], ENT_QUOTES) .'</option>';
			}
			
			$dom = $contact_list.'@@'.$template_list;
		
		break;
		
		
		case "contact_list":
		
			$conf = $_GET['conf'];
			$id_company = $_GET['id_company'];
			
			$contactList = '';
			$sql_contactList = "SELECT * from v_icw_contacts WHERE id_primary_company = $id_company";
			$result_contactList = pg_query($conn, $sql_contactList);
		
			while($arr_contactList = pg_fetch_assoc($result_contactList)){
				if(file_exists('img/avatar/' . $arr_contactList['id_contact'] . ".jpg")) {
					$avatar_c = 'img/avatar/' . $arr_contactList['id_contact'] . ".jpg";
				} else { $avatar_c = 'img/user.jpg'; }
				
				if($conf == 'campaign'){
					$contactList .= '<li class="camp_ct_name"><div class="row" style="padding-top: 10px; margin: 0;">
						<div class="col-md-2" style="padding-right:0;"><input type="checkbox" class="i-checks" value="'. $arr_contactList['p_email'] .'" name="camp_contact" /></div>
						<div class="col-md-10" style="padding-left:0;">
							<div class="col-md-2" style="padding:0;"><img src="'.$avatar_c.'" class="img-circle" height="35" /></div>
							<div class="col-md-10" style="padding-left:0;">'. $arr_contactList['firstname'] .' '. $arr_contactList['lastname'] .'<br/>
							<span style="color:#aaa; font-size:12px;">'. $arr_contactList['p_email'] .'</span></div>
						</div></div>
					</li>';
				
				} else {
					$contactList .= '<li class="camp_ct_name"><div class="row" style="padding-top: 10px; margin: 0;">
						<div class="col-md-2" style="padding-right:0;"><input type="radio" class="i-checks" value="'. $arr_contactList['id_contact'] .'" name="comp_result" /></div>
						<div class="col-md-10" style="padding-left:0;">
							<div class="col-md-2" style="padding:0;"><img src="'.$avatar_c.'" class="img-circle" height="35" /></div>
							<div class="col-md-10" style="padding-left:0;">'. $arr_contactList['firstname'] .' '. $arr_contactList['lastname'] .'<br/>
							<span style="color:#aaa; font-size:12px;">'. $arr_contactList['p_email'] .'</span></div>
						</div></div>
					</li>';
				}
			}
			
			$dom = $contactList;
		
		break;
		
		
		case "save_user_servey":
		
			$surtemplate_id = $_GET['surtemplate_id'];
			$id_suranswer = $_GET['id_suranswer'];
			$q_seq = $_GET['q_seq'];
	
			$id_agent = $_GET['id_agent'];
			$id_contact = $_GET['id_contact'];
			$sur_datetime = gmdate("Y/m/d H:i:s");
			
			$sql = "SELECT * FROM public.sur_answers WHERE id_suranswer = $id_suranswer";
			$result = pg_query($conn, $sql);
			
			if($result){
				$arr = pg_fetch_assoc($result);
				
				$surquest_id = $arr['surq_id'];
				$suranswer_id = $arr['id_suranswer'];
				$suranswer = pg_escape_string($arr['ans_text_fr']);
				$surscore = $arr['score'];
				
				
				$sql2 = "INSERT INTO sur_survey_answers ( surtemplate_id, surquest_id, suranswer_id, suranswer, surscore, id_contact, sur_datetime, id_agent ) 
				values ( $surtemplate_id, $surquest_id, $suranswer_id, '{$suranswer}', $surscore, $id_contact, '$sur_datetime', $id_agent )";
				$result2 = pg_query($conn, $sql2);

				if ($result2) {
					
					$answers = "";
					$next_seq = $q_seq + 1;
					
					$sqlQ = "SELECT id_surq, q_seq, q_text FROM sur_questions WHERE surtemplate_id = $surtemplate_id AND q_seq = $next_seq";
					$resulQ = pg_query($conn, $sqlQ);
					$arrQ = pg_fetch_assoc($resulQ);
					
					$question = $arrQ['q_text'];
					$id_surq = $arrQ['id_surq'];
				
					if(!empty($id_surq)) {
						$sqlAnswers = "SELECT * FROM public.sur_answers WHERE surq_id = $id_surq ORDER BY ans_text_en ASC";
						$rsAnswers = pg_query($conn, $sqlAnswers);
						while($rowAnswers = pg_fetch_assoc($rsAnswers)) {
							$answers .= '<div class="m-xs">
								<input type="radio" name="radio_ans" id="radio_ans_'.$rowAnswers['id_suranswer'].'" class="i-checks" value="'.$rowAnswers['id_suranswer'].'">
								<label for="radio_ans_'.$rowAnswers['id_suranswer'].'">'.$rowAnswers['ans_text_en'].'</label>
							</div>';
						}
						
					} else {
						$question = "end";
					}
					
					$dom='1##'.$question.'##'.$next_seq.'##'.$answers;
					
				} else {
					$dom=$sql2;
				}
				
			} else {
				$dom=0;
			}

		
		break;
		
		
		case "send_campaign":
		
			$subject = $_GET['subject'];
			$template = $_GET['template'];
			$contenu = utf8_decode($_GET['contenu']);
			$to = $_GET['to'];
			
			
			$sql = "SELECT id_contact FROM v_icw_contacts WHERE p_email = '$to'";
			$rs = pg_query($conn, $sql);
			$row = pg_fetch_assoc($rs);
			
			if($row['id_contact']!=""){
				
				// Template
				$sqlTemplate = "SELECT description FROM public.sur_template WHERE id_survey = $template";
				$rsTemplate= pg_query($conn, $sqlTemplate);
				$rowTemplate = pg_fetch_assoc($rsTemplate);
				
				$description = $rowTemplate['description'];
		
				$id_contact = $row['id_contact'];
				$id_agent = $_SESSION['id_contact'];
			
				$link = 'https://icoop.live/ic/survey/?u='.$id_contact.'&tp='.$template.'&s='.$id_agent;
			
				$message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
					<meta name="viewport" content="width=device-width">
					<title></title>
					</head>
					
					<body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background:#f3f3f3!important;box-sizing:border-box;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100%!important">
					<style type="text/css" align="center" class="float-center">
					@media only screen{html{min-height:100%;background:#f3f3f3}}
					@media only screen and (max-width:596px){.small-float-center{margin:0 auto!important;float:none!important;text-align:center!important}.small-text-center{text-align:center!important}.small-text-left{text-align:left!important}.small-text-right{text-align:right!important}}
					@media only screen and (max-width:596px){.hide-for-large{display:block!important;width:auto!important;overflow:visible!important;max-height:none!important;font-size:inherit!important;line-height:inherit!important}}
					@media only screen and (max-width:596px){table.body table.container .hide-for-large,table.body table.container .row.hide-for-large{display:table!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .callout-inner.hide-for-large{display:table-cell!important;width:100%!important}}
					@media only screen and (max-width:596px){table.body table.container .show-for-large{display:none!important;width:0;mso-hide:all;overflow:hidden}}@media only screen and (max-width:596px){table.body img{width:auto;height:auto}table.body center{min-width:0!important}table.body .container{width:95%!important}table.body .column,table.body .columns{height:auto!important;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;padding-left:16px!important;padding-right:16px!important}table.body .column .column,table.body .column .columns,table.body .columns .column,table.body .columns .columns{padding-left:0!important;padding-right:0!important}table.body .collapse .column,table.body .collapse .columns{padding-left:0!important;padding-right:0!important}td.small-1,th.small-1{display:inline-block!important;width:8.33333%!important}td.small-2,th.small-2{display:inline-block!important;width:16.66667%!important}td.small-3,th.small-3{display:inline-block!important;width:25%!important}td.small-4,th.small-4{display:inline-block!important;width:33.33333%!important}td.small-5,th.small-5{display:inline-block!important;width:41.66667%!important}td.small-6,th.small-6{display:inline-block!important;width:50%!important}td.small-7,th.small-7{display:inline-block!important;width:58.33333%!important}td.small-8,th.small-8{display:inline-block!important;width:66.66667%!important}td.small-9,th.small-9{display:inline-block!important;width:75%!important}td.small-10,th.small-10{display:inline-block!important;width:83.33333%!important}td.small-11,th.small-11{display:inline-block!important;width:91.66667%!important}td.small-12,th.small-12{display:inline-block!important;width:100%!important}.column td.small-12,.column th.small-12,.columns td.small-12,.columns th.small-12{display:block!important;width:100%!important}table.body td.small-offset-1,table.body th.small-offset-1{margin-left:8.33333%!important;Margin-left:8.33333%!important}table.body td.small-offset-2,table.body th.small-offset-2{margin-left:16.66667%!important;Margin-left:16.66667%!important}table.body td.small-offset-3,table.body th.small-offset-3{margin-left:25%!important;Margin-left:25%!important}table.body td.small-offset-4,table.body th.small-offset-4{margin-left:33.33333%!important;Margin-left:33.33333%!important}table.body td.small-offset-5,table.body th.small-offset-5{margin-left:41.66667%!important;Margin-left:41.66667%!important}table.body td.small-offset-6,table.body th.small-offset-6{margin-left:50%!important;Margin-left:50%!important}table.body td.small-offset-7,table.body th.small-offset-7{margin-left:58.33333%!important;Margin-left:58.33333%!important}table.body td.small-offset-8,table.body th.small-offset-8{margin-left:66.66667%!important;Margin-left:66.66667%!important}table.body td.small-offset-9,table.body th.small-offset-9{margin-left:75%!important;Margin-left:75%!important}table.body td.small-offset-10,table.body th.small-offset-10{margin-left:83.33333%!important;Margin-left:83.33333%!important}table.body td.small-offset-11,table.body th.small-offset-11{margin-left:91.66667%!important;Margin-left:91.66667%!important}table.body table.columns td.expander,table.body table.columns th.expander{display:none!important}table.body .right-text-pad,table.body .text-pad-right{padding-left:10px!important}table.body .left-text-pad,table.body .text-pad-left{padding-right:10px!important}table.menu{width:100%!important}table.menu td,table.menu th{width:auto!important;display:inline-block!important}table.menu.small-vertical td,table.menu.small-vertical th,table.menu.vertical td,table.menu.vertical th{display:block!important}table.menu[align=center]{width:auto!important}table.button.small-expand,table.button.small-expanded{width:100%!important}table.button.small-expand table,table.button.small-expanded table{width:100%}table.button.small-expand table a,table.button.small-expanded table a{text-align:center!important;width:100%!important;padding-left:0!important;padding-right:0!important}table.button.small-expand center,table.button.small-expanded center{min-width:0}}
					</style>
					
					<span class="preheader" style="color:#f3f3f3;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
					
					<table class="body" style="Margin:0;background:#f3f3f3!important;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody>
							<tr style="padding:0;text-align:left;vertical-align:top">
								<td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
									<center data-parsed="" style="min-width:580px;width:100%">
					
										<table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;padding:0;text-align:center;vertical-align:top;width:580px">
											<tbody>
												<tr style="padding:0;text-align:left;vertical-align:top">
													<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					
														<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
															<tbody>
																<tr style="padding:0;text-align:left;vertical-align:top">
																	<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
																	</td>
																</tr>
															</tbody>
														</table>
														
														<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
															<tbody>
																<tr style="padding:0;text-align:left;vertical-align:top">
																	<th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
																		<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																			<tbody>
																				<tr style="padding:0;text-align:left;vertical-align:top">
																					<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																						
																						<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
																						<tr>
																							<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																								<h1>'. $description .'</h1>
																							</td>
																						</tr></table>
					
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							'.$contenu.' <br/>
																							Cliquez <a href="'. $link. '"> ici </a> pour la campagne ou sur le lien ci-dessous.<br/><br/>'. $link .'
																						</p>
																					</th>
																				</tr>
																			</tbody>
																		</table>
																	</th>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</center>
								</td>
							</tr>
						</tbody>
					</table>
					
					</body>
					</html>';
					
					$sender="noreply@icoop.live";
				
					$mail = new PHPMailer;
					$mail->isSMTP();
					// $mail->SMTPDebug = 2;
					$mail->SMTPSecure = 'ssl';
					$mail->Debugoutput = 'html';
					$mail->Host = "mail.icoop.live";
					$mail->Port = 465;
					$mail->SMTPAuth = true;
					$mail->MessageID = "<" . time() ."-" . md5($sender . $to) . "@icoop.live>";
					$mail->Username = 'noreply@icoop.live';
					$mail->Password = 'Qwerty@1234';
					$mail->setFrom('noreply@icoop.live', 'Survey');
					$mail->addAddress($to);
					$mail->Subject = $subject;
					$mail->msgHTML($message);
					$mail->AltBody = 'This is a plain-text message body';
					//send the message, check for errors
					if (!$mail->send()) {
						$dom=0;
					} else {
						$dom=1;
					}
					
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "campaign_results_contact":
		
			$surtemplate_id = $_GET['surtemplate_id'];
			$id_contact = $_GET['id_contact'];
			
			$html="";
			
			$sqlUserAns = "SELECT COUNT(id_suranswer) tt_answers FROM public.sur_survey_answers WHERE id_contact = $id_contact AND surtemplate_id = $surtemplate_id";
			$rsUserAns = pg_query($conn, $sqlUserAns);
			$rowUserAns = pg_fetch_assoc($rsUserAns);
			
			$tt_answers = $rowUserAns['tt_answers'];
			
			
			$sqlAnswers="SELECT * FROM public.sur_survey_answers WHERE surtemplate_id = $surtemplate_id AND id_contact = $id_contact";
			$rsAnswers= pg_query($conn, $sqlAnswers);
			
			while($rowAnswers = pg_fetch_assoc($rsAnswers)) {
				
				$id_surq = $rowAnswers['surquest_id'];
				$id_suranswer = $rowAnswers['id_suranswer'];
				$answer = $rowAnswers['suranswer'];
				
				$sqlQuestion="SELECT q_text, q_seq FROM public.sur_questions WHERE surtemplate_id = $surtemplate_id AND id_surq = $id_surq ORDER BY q_seq ASC";
				$rsQuestion = pg_query($conn, $sqlQuestion);
				$rowQuestion = pg_fetch_assoc($rsQuestion);
				
				$question = $rowQuestion['q_text'];
				$sequence = $rowQuestion['q_seq'];
				
				if($tt_answers == $sequence){
					$delete = '<a href="javascript:delCampUserAns('.$id_suranswer.');"><i class="fas fa-trash-alt"></i></a>';
				} else { $delete = ''; }
				
				$html.='<div class="row" style="padding-top:15px;"><div class="col-md-10">
						<div class="col-md-12"><strong>'.$sequence.' - '.$question.'</strong></div>
						<div class="col-md-12">'.$answer.'</div>
					</div><div class="col-md-2">'.$delete.'</div>
				</div>';
			}
			
			$dom = $html;
			
		break;
		
		
		case "delete_user_survey_answer":
		
			$id_suranswer = $_GET['id_suranswer'];
			
			$sql = "DELETE FROM public.sur_survey_answers WHERE id_suranswer = $id_suranswer";
			$result = pg_query($conn, $sql);
			
			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "delete_all_user_survey_answer":
		
			$id_contact = $_GET['id_contact'];
			$surtemplate_id = $_GET['surtemplate_id'];
			
			$sql = "DELETE FROM public.sur_survey_answers WHERE id_contact = $id_contact AND surtemplate_id = $surtemplate_id";
			$result = pg_query($conn, $sql);
			
			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
	}
	
}

echo $dom;