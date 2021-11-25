<?php

session_start();
error_reporting(0);

include_once("fcts.php");
include_once("common.php");

define('ID_USER', 'noreply@icoop.live');
define('ID_PASS', 'Qwerty4321');

require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';


header("Content-type: image/png");


function get_weekth($y, $m, $d) {
	return intval(date('W',strtotime($y.'-'.$m.'-'.$d)));
}


function IsInjected($str) {

	$injections = array('(\n+)',
		'(\r+)',
		'(\t+)',
		'(%0A+)',
		'(%0D+)',
		'(%08+)',
		'(%09+)'
	);

	$inject = join('|', $injections);
	$inject = "/$inject/i";

	if(preg_match($inject,$str)){
		return true;
	} else {
		return false;
	}
}


// include_once("mail/libraries/afterlogic/api.php"); 

// function sendEmailMessage($sEmail, $sPassword, $sSubject, $sText, $sTo, $sCc, $sBcc, $bReadingConfirmation = false) 
// {
  // if (class_exists('CApi') && CApi::IsValid()) 
  // { 
    // try 
    // { 
      // $oApiIntegratorManager = CApi::Manager('integrator'); 
      // $oAccount = $oApiIntegratorManager->LoginToAccount($sEmail, $sPassword); 
      // if ($oAccount) 
      // { 
        // $oApiMailManager = \CApi::Manager('mail'); 
        // $oMessage = \MailSo\Mime\Message::NewInstance(); 
        // $oMessage->RegenerateMessageId(); 
        // $oFrom = \MailSo\Mime\Email::NewInstance($oAccount->Email, $oAccount->FriendlyName); 
        // $oMessage 
          // ->SetFrom($oFrom) 
          // ->SetSubject($sSubject); 

        // $oToEmails = \MailSo\Mime\EmailCollection::NewInstance($sTo); 
        // if ($oToEmails && $oToEmails->Count()) 
        // { 
          // $oMessage->SetTo($oToEmails); 
        // } 
        // $oCcEmails = \MailSo\Mime\EmailCollection::NewInstance($sCc); 
        // if ($oCcEmails && $oCcEmails->Count()) 
        // { 
          // $oMessage->SetCc($oCcEmails); 
        // } 

        // $oBccEmails = \MailSo\Mime\EmailCollection::NewInstance($sBcc); 
        // if ($oBccEmails && $oBccEmails->Count()) 
        // { 
          // $oMessage->SetBcc($oBccEmails); 
        // } 
        
        // if ($bReadingConfirmation) 
        // { 
          // $oMessage->SetReadConfirmation($oAccount->Email); 
        // } 
        // $sTextConverted = \MailSo\Base\HtmlUtils::ConvertHtmlToPlain($sText); 
        // $oMessage->AddText($sTextConverted, false); 
          
        // $mFoundDataURL = array(); 
        // $aFoundedContentLocationUrls = array(); 
        // $aFoundCids = array(); 
          
        // $htmlTextConverted = \MailSo\Base\HtmlUtils::BuildHtml($sText, $aFoundCids, $mFoundDataURL, $aFoundedContentLocationUrls); 
        // $oMessage->AddText($htmlTextConverted, true); 
          
        // $sSentFolder = "Sent Items";
        // $oFolders = $oApiMailManager->getFolders($oAccount);
        // $aFolders = array();
        // $oFolders->foreachWithSubFolders(function ($oFolder) use (&$aFolders,&$sSentFolder) {
          // if ($oFolder->getType() === EFolderType::Sent) {
            // $sSentFolder = $oFolder->getFullName();
          // }
        // }
        // );
      	// return $oApiMailManager->sendMessage($oAccount, $oMessage, null, $sSentFolder); 
      // }
      // else 
      // {
        // echo $oApiIntegratorManager->GetLastErrorMessage(); 
      // } 
    // } 
    // catch (Exception $oException) 
    // { 
      // echo $oException->getMessage(); 
    // } 
  // } 
  // else 
  // { 
    // echo 'API is not available'; 
  // } 
// }


	
	
if (isset($_GET["elemid"])) {

    $elemid = $_GET["elemid"];
    $conn=connect();

	if(!$conn) {
		header("Location: error_db.php");
	}

    $dom='';

	switch ($elemid) {
		
		case "eMail_form":

			$id_company = $_SESSION['id_company'];
			$id_primary_company = $_SESSION['id_primary_company'];
			
			if(($id_primary_company == 646) OR ($id_primary_company == 645) OR ($id_primary_company == 647) OR ($id_primary_company == 636)) {
				$sql="select id_contact, name, p_email, skype_id from contact 
					where (id_primary_company=646 or id_primary_company=645 or id_primary_company=647 or id_primary_company=636) 
				and id_type=9 AND p_email IS NOT NULL";
			} else
			if(($id_primary_company == 15065) OR ($id_primary_company == 15064) OR ($id_primary_company == 23128)) {
				$sql="select id_contact, name, p_email, skype_id from contact 
					where (id_primary_company=15064 or id_primary_company=15065 or id_primary_company=23128) 
				and id_type=9 AND p_email IS NOT NULL";
			} else {
				$sql="select id_contact, name, p_email, skype_id from contact 
					where id_primary_company=$id_company 
				and id_type=9 AND p_email IS NOT NULL";
			}
			
			$result = pg_query($conn, $sql);
	
			$email_list = "";
			while($arr = pg_fetch_assoc($result)){
				if($arr['p_email']!=""){
					$email_list .= '<option value="'.trim($arr['p_email']).'">'.trim($arr['name']).'</option>';
				}
			}
			
			$dom=$email_list;
			
		break;
		
		case "ag_grid":
		
			$sql_stats = "SELECT contact.name,
			mobcrmticker.id_mobconticker,
			mobcrmticker.id_plantation,
			mobcrmticker.field_name,
			mobcrmticker.field_value,
			mobcrmticker.ticker_time,
			mobcrmticker.coordx,
			mobcrmticker.coordy,
			get_contact_name(mobcrmticker.id_agent) agentname,
			contact.id_town,
			get_town_name(contact.id_town) town_name,
			towns.x as town_coordx,
			towns.y as town_coordy,
			id_project,
			id_task,
			field_table
			 FROM
			public.mobcrmticker,
			public.contact,
			public.towns
			  WHERE
			contact.id_contact = mobcrmticker.id_contact and
			towns.id_town=contact.id_town
			 ORDER BY mobcrmticker.id_mobconticker DESC";
			$result = pg_query($conn, $sql_stats);

			$data="";
			while($arr = pg_fetch_assoc($result)){
				$data .= $arr['name']."##".$arr['id_mobconticker']."##".$arr['id_plantation']."##".$arr['field_name']."##".$arr['field_value']."##".$arr['ticker_time'].'##'.$arr['coordx']."##".$arr['coordy']."##".$arr['agentname']."##".$arr['id_town']."##".$arr['town_name']."##".$arr['town_coordx']."##".$arr['town_coordy']."##".$arr['id_project']."##".$arr['id_task']."##".$arr['field_table']."@@";
			}

			$data.= 'end';
		
			$dom = $data;
		
		break;
		

		case "save_profile_data":

			// $name = $_GET["name"];
			// $username = $_GET["username"];
			// $company_name = $_GET["company_name"];
			// $id_supchain_type = $_GET["id_supchain_type"];
			// $name_country = $_GET["name_country"];
			// $name_town = $_GET["name_town"];

			// if($_SESSION['username'] != $username){
				// $id = $_SESSION['id_contact']
				// $sql_uname = "UPDATE users SET username='$username' WHERE id_contact ='$id'";

				// $result = pg_query($conn, $sql_uname) or die(pg_last_error());
				// $count = pg_num_rows($result);
			// }

			// $sql_stats = "UPDATE public.plantation
			   // SET area='$area', year_creation='$year_creation', variety='$variety',
				   // statut='$statut'
			// WHERE id_contact ='$id'";

			// $result = pg_query($conn, $sql_stats) or die(pg_last_error());
			// $count = pg_num_rows($result);

			// if($count==0){
				// $dom="1##Profil updated successfully";
			// } else {
				// $dom="0##Unable to update profil";
			// }

		break;


		case "regvalues":

			if(isset($_GET['id_register'])){
				$cond = " WHERE id_register ='". $_GET['id_register'] ."'";
			} else { $cond = ''; }

			$regvalues_list='';
			$sql_stats = "SELECT id_register, regname, regcode, id_regvalue, cvalue, cvaluede,
				cvaluefr, cvaluept, cvaluees, cvaluesw, cvalueit
			FROM public.v_regvalues $cond ORDER BY id_regvalue ASC";
			$result = pg_query($conn, $sql_stats);
		
			while($arr = pg_fetch_assoc($result)){
				$regvalues_list .= '<tr>
					<td>'. $arr['id_regvalue'] .'</td>
					<td>'. $arr['cvalue'] .'</td>
					<td>'. $arr['cvaluede'] .'</td>
					<td>'. $arr['cvaluefr'] .'</td>
					<td>'. $arr['cvaluept'] .'</td>
					<td>'. $arr['cvaluees'] .'</td>
					<td>'. $arr['cvaluesw'] .'</td>
					<td>'. $arr['cvalueit'] .'</td>
					<td class="row_actions">
						<div style="width:40px;">
							<a href="#" data-toggle="modal" onclick="regvaluesManagement(\'show\',\''. $arr['id_regvalue'] .'\',\'mod\');" data-target="#modalRegvalue"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
							<a href="javascript:regvaluesManagement(\'del\',\''. $arr['id_regvalue'] .'\',\'\');" onclick="return confirm(\'Are you sure you want to delete regvalue ID:'. $arr['id_regvalue'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
						</div>
					</td>
				</tr>';
			}

			
			$sql_register = "SELECT id_register, regname, regcode FROM public.registers ORDER BY id_register ASC";
			$rs_register = pg_query($conn, $sql_register);

			$register_list = '';
			while ($row_register = pg_fetch_assoc($rs_register)) {
				$register_list .= '<tr>
					<td>'. $row_register['id_register'] .'</td>
					<td>'. $row_register['regname'] .'</td>
					<td>'. $row_register['regcode'] .'</td>
					<td class="row_actions">
						<a href="#" data-toggle="modal" onclick="registerManagement(\'show\',\''. $row_register['id_register'] .'\',\'mod\');" data-target="#modalRegister"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
						<a href="javascript:registerManagement(\'del\',\''. $row_register['id_register'] .'\',\'\');" onclick="return confirm(\'Are you sure you want to delete register ID:'. $row_register['id_register'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
					</td>
				</tr>';
			}

			$dom=$regvalues_list.'##'.$register_list;

		break;


		case "chain_list":

			$chain_list='';
			$sql_stats = "SELECT id_regvalue, cvalue, cvaluede, cvaluefr, cvaluept, cvaluees FROM v_regvalues WHERE id_register=25";
			$result = pg_query($conn, $sql_stats);
			while($arr = pg_fetch_assoc($result)){
				if($_SESSION['id_supchain_type'] == $arr['id_regvalue']){
					$select="selected='selected'";
				} else { $select=""; }
				$chain_list .= '<option value="'. $arr['id_regvalue'] .'" '. $select .'>'. $arr['cvalue'] .'</option>';
			}

			$dom=$chain_list;

		break;


		case "connexion":

			$message = "";
			$username = $_GET["username"];
			$password = $_GET["password"];

			if(IsInjected($username))
			{
			   $message .= "0##Mauvaise valeur du nom utilisateur!<br>";
			}

			if(IsInjected($password))
			{
			   $message .= "0##Mauvaise valeur du mot de passe!<br>";
			}

			if(empty($message)){

				if(verifUsrename($username,$conn)){
					if(verification($username, $password)){
				     	if(verifExporter($username)){
						  $message .= "1##".$_SESSION['username'];
						} else {
							$message .= "0##Votre compte a &eacute;t&eacute; d&eacute;sactiv&eacute; par l'administrateur!<br>";
						}
					} else {
						$message .= "0##Le mot de passe n'est pas correct!";
					}
				} else {
					$message .= "0##Cet utilisateur n'existe pas!<br>";
				}
			}

			echo $message;

        break;
		

		case "agent_management":

			$id_primary_company = $_SESSION['id_primary_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_company = $_SESSION['id_company'];
		
			if(!empty($_GET['agent_id'])){ 
				$agent_id = $_GET['agent_id']; 
				$cond_agent = " AND mobcrmticker.id_agent = $agent_id"; 
			} else { $cond_agent = ""; $agent_id=""; } 
			
			if(!empty($_GET['id_contact'])){ 
				$id_contact = $_GET['id_contact'];
				$cond_farmer = " AND contact.id_contact = $id_contact"; 
			} else { $cond_farmer = ""; $id_contact = ""; } 
			
			if(!empty($_GET['id_town'])){ 
				$id_town = $_GET['id_town'];
				$cond_town = " AND contact.id_town = $id_town"; 
			} else { $cond_town = ""; $id_town = ""; }
			
			if(!empty($_GET['limit'])){
				$cond = $_GET['limit'];
			} else {
				$cond = 100;
			}
			
			if($id_supchain_type == 114){
				$sql="select contact.name,
					mobcrmticker.id_mobconticker,
					mobcrmticker.id_plantation,
					mobcrmticker.field_name,
					mobcrmticker.field_value,
					mobcrmticker.ticker_time,
					mobcrmticker.coordx,
					mobcrmticker.coordy,
					get_contact_name(mobcrmticker.id_agent) agentname,
					contact.id_town,
					get_town_name(contact.id_town) town_name,
					towns.x as town_coordx,
					towns.y as town_coordy,
					id_project,
					id_task,
					field_table
					from mobcrmticker, contact, towns
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where project_id in (
					select id_project from project where id_company=$id_primary_company )))
					and contact.id_contact = mobcrmticker.id_contact 
					and towns.id_town=contact.id_town
					$cond_agent $cond_farmer $cond_town
					ORDER BY mobcrmticker.ticker_time DESC
					LIMIT $cond
				";
				
				$sql_agent="select DISTINCT mobcrmticker.id_agent,
					get_contact_name(mobcrmticker.id_agent) agentname
					from mobcrmticker
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where project_id in (
					select id_project from project where id_company=$id_primary_company )))
				";
				
				$sql_farmer="select DISTINCT contact.id_contact,
					contact.name
					from mobcrmticker, contact
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where project_id in (
					select id_project from project where id_company=$id_primary_company )))
					and contact.id_contact = mobcrmticker.id_contact 
					ORDER BY name ASC
				";
				
				$sql_town="select DISTINCT contact.id_town,
					get_town_name(contact.id_town) town_name
					from mobcrmticker, contact, towns
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where project_id in (
					select id_project from project where id_company=$id_primary_company )))
					and contact.id_contact = mobcrmticker.id_contact and
					towns.id_town=contact.id_town
					ORDER BY town_name ASC
				";
				
			} else
			if($id_supchain_type == 331){
				$sql="select contact.name,
					mobcrmticker.id_mobconticker,
					mobcrmticker.id_plantation,
					mobcrmticker.field_name,
					mobcrmticker.field_value,
					mobcrmticker.ticker_time,
					mobcrmticker.coordx,
					mobcrmticker.coordy,
					get_contact_name(mobcrmticker.id_agent) agentname,
					contact.id_town,
					get_town_name(contact.id_town) town_name,
					towns.x as town_coordx,
					towns.y as town_coordy,
					id_project,
					id_task,
					field_table
					from mobcrmticker, contact, towns
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where project_id in (
					select id_project from project where cooperative_id=$id_primary_company )))
					and contact.id_contact = mobcrmticker.id_contact 
					and towns.id_town=contact.id_town
					$cond_agent $cond_farmer $cond_town
					ORDER BY mobcrmticker.ticker_time DESC
					LIMIT $cond
				";
				
				$sql_agent="select DISTINCT mobcrmticker.id_agent,
					get_contact_name(mobcrmticker.id_agent) agentname
					from mobcrmticker
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where project_id in (
					select id_project from project where cooperative_id=$id_primary_company )))                 
				";
				
				$sql_farmer="select DISTINCT contact.id_contact,
					contact.name
					from mobcrmticker, contact
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where project_id in (
					select id_project from project where cooperative_id=$id_primary_company )))
					and contact.id_contact = mobcrmticker.id_contact 
					ORDER BY name ASC
				";
				
				$sql_town="select DISTINCT contact.id_town,
					get_town_name(contact.id_town) town_name
					from mobcrmticker, contact, towns
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where project_id in (
					select id_project from project where cooperative_id=$id_primary_company )))
					and contact.id_contact = mobcrmticker.id_contact and
					towns.id_town=contact.id_town
					ORDER BY town_name ASC
				";
				
			} else
			if($id_supchain_type == 228) {
				$sql="select contact.name,
					mobcrmticker.id_mobconticker,
					mobcrmticker.id_plantation,
					mobcrmticker.field_name,
					mobcrmticker.field_value,
					mobcrmticker.ticker_time,
					mobcrmticker.coordx,
					mobcrmticker.coordy,
					get_contact_name(mobcrmticker.id_agent) agentname,
					contact.id_town,
					get_town_name(contact.id_town) town_name,
					towns.x as town_coordx,
					towns.y as town_coordy,
					id_project,
					id_task,
					field_table
					from mobcrmticker, contact, towns
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where task_delegated_id=$id_primary_company ))
					and contact.id_contact = mobcrmticker.id_contact 
					and towns.id_town=contact.id_town 
					$cond_agent $cond_farmer $cond_town
					ORDER BY mobcrmticker.ticker_time DESC
					LIMIT $cond
				";
				
				$sql_agent="select DISTINCT mobcrmticker.id_agent,
					get_contact_name(mobcrmticker.id_agent) agentname
					from mobcrmticker
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where task_delegated_id=$id_primary_company ))
				";
				
				$sql_farmer="select DISTINCT contact.id_contact,
					contact.name
					from mobcrmticker, contact
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where task_delegated_id=$id_primary_company ))
					and contact.id_contact = mobcrmticker.id_contact 
					ORDER BY name ASC
				";
				
				$sql_town="select DISTINCT contact.id_town,
					get_town_name(contact.id_town) town_name
					from mobcrmticker, contact, towns
					where mobcrmticker.id_contact in (select distinct contact_id from project_members where task_id in ( select id_task from project_task where task_delegated_id=$id_primary_company ))
					and contact.id_contact = mobcrmticker.id_contact and
					towns.id_town=contact.id_town
					ORDER BY town_name ASC
				";
				
			} else {
				
				if($id_primary_company == 636) {
					$case="task_delegated_id IN (645, 646, 647)"; 
				} else {
					$case="task_delegated_id=$id_company"; 
				}
				
				$sql="select contact.name,
					mobcrmticker.id_mobconticker,
					mobcrmticker.id_plantation,
					mobcrmticker.field_name,
					mobcrmticker.field_value,
					mobcrmticker.ticker_time,
					mobcrmticker.coordx,
					mobcrmticker.coordy,
					get_contact_name(mobcrmticker.id_agent) agentname,
					contact.id_town,
					get_town_name(contact.id_town) town_name,
					towns.x as town_coordx,
					towns.y as town_coordy,
					id_project,
					id_task,
					field_table
					from mobcrmticker, contact, towns
					where mobcrmticker.id_contact in (
					select id_Contact from contact where id_town in (
					select distinct town_id from project_task where $case )) and
					contact.id_contact = mobcrmticker.id_contact 
					and towns.id_town=contact.id_town
					$cond_agent $cond_farmer $cond_town
					ORDER BY mobcrmticker.ticker_time DESC
					LIMIT $cond
				";
				
				$sql_agent="select DISTINCT mobcrmticker.id_agent,
					get_contact_name(mobcrmticker.id_agent) agentname
					from mobcrmticker
					where mobcrmticker.id_contact in (
					select id_Contact from contact where id_town in (
					select distinct town_id from project_task where $case ))
				";
				
				$sql_farmer="select DISTINCT contact.id_contact,
					contact.name
					from mobcrmticker, contact
					where mobcrmticker.id_contact in (
					select id_Contact from contact where id_town in (
					select distinct town_id from project_task where $case ))
					and contact.id_contact = mobcrmticker.id_contact 
					ORDER BY name ASC
				";
				
				$sql_town="select DISTINCT contact.id_town,
					get_town_name(contact.id_town) town_name
					from mobcrmticker, contact, towns
					where mobcrmticker.id_contact in (
					select id_Contact from contact where id_town in (
					select distinct town_id from project_task where $case )) and
					contact.id_contact = mobcrmticker.id_contact and
					towns.id_town=contact.id_town
					ORDER BY town_name ASC
				";
			}

			$manag_list ='';
			$agent_coord ='';

			$result = pg_query($conn, $sql);
			while($row_agentm = pg_fetch_assoc($result)){
				
				if(($row_agentm['coordx']=="") AND ($row_agentm['coordy']=="")){
					$marker = "";
				} else {
					$marker = '<a href="javascript:agentLocation(\''. $row_agentm['coordx'] .'\',\''. $row_agentm['coordy'] .'\',\''. $row_agentm['name'] .'\',\''. $row_agentm['town_coordx'] .'\',\''. $row_agentm['town_coordy'] .'\',\''. $row_agentm['town_name'] .'\')">
						<i class="fa fa-map-marker" aria-hidden="true"></i>
					</a>';
				}
				
				$manag_list .= '<tr>
					<td><strong>'. $row_agentm['agentname'] .'</strong></td>
					<td style="width:120px;">'. $row_agentm['field_name'] .' </td>
					<td style="width:120px;">'. substr($row_agentm['field_value'], 0, 22) .'</td>
					<td>'. $row_agentm['name'] .' </td>
					<td>'. $row_agentm['town_name'] .' </td>
					<td>'. $row_agentm['ticker_time'] .' </td>
					<td> '. $marker .' </td>
				</tr>';

				$agent_coord .= $row_agentm['coordx'].'#'.$row_agentm['coordy'].'#'.$row_agentm['name'].'??';
			}

			$agent_coord .= 'end';
		
			$agent_list ='<option value="0">-- ' . $lang['PROJECT_TASK_AGENT'] .' --</option>';
			$result_agent = pg_query($conn, $sql_agent);
			while($row_agent = pg_fetch_assoc($result_agent)){
				if($agent_id == $row_agent['id_agent']){ $sel_agent = 'selected'; } else { $sel_agent = ''; }
				$agent_list .= '<option value="'. $row_agent['id_agent'] .'" '. $sel_agent .'>'. $row_agent['agentname'] .'</option>';
			}
			
			$farmer_list ='<option value="0">-- ' . $lang['PROJECT_TASK_FARMER'] .' --</option>';
			$result_farmer = pg_query($conn, $sql_farmer);
			while($row_farmer = pg_fetch_assoc($result_farmer)){
				if($id_contact == $row_farmer['id_contact']){ $sel_farmer = 'selected'; } else { $sel_farmer = ''; }
				$farmer_list .= '<option value="'. $row_farmer['id_contact'] .'" '. $sel_farmer .'>'. $row_farmer['name'] .'</option>';
			}
		
			$town_list ='<option value="0">-- ' . $lang['PROJECT_TOWNS'] .' --</option>';
			$result_town = pg_query($conn, $sql_town);
			while($row_town = pg_fetch_assoc($result_town)){
				if($id_town == $row_town['id_town']){ $sel_town = 'selected'; } else { $sel_town = ''; }
				$town_list .= '<option value="'. $row_town['id_town'] .'" '. $sel_town .'>'. $row_town['town_name'] .'</option>';
			}

			$dom = $manag_list .'**'. $agent_coord .'**'. $agent_list .'**'. $farmer_list .'**'. $town_list;

		break;


		case "users_management":

			$id_role = $_GET['id_role'];
			$editRight = $_GET['editRight'];
			$createRight = $_GET['createRight'];
			$deleteRight = $_GET['deleteRight'];
			
			if($id_role == 0){ $where_role = ''; }
			else { $where_role = ' AND roles.id_role = ' . $id_role; }
			$id_user = $_SESSION['id_user'];

			$sql_list = "select c.firstname, c.lastname, u.username, u.id_user
			from users u, contact c
			where u.id_contact=c.id_contact ORDER By firstname ASC";

			// $sql_list = "select * from contact where id_primary_company=( select id_primary_company from contact where
			// id_contact=(select id_contact from users where id_user=$id_user ) )";

			if($editRight ==1){ $edit=""; } else { $edit="hide"; }
			if($deleteRight ==1){ $delete=""; } else { $delete="hide"; }
			
			$users_list ='';
			$result_list = pg_query($conn, $sql_list);
			while($row_users = pg_fetch_assoc($result_list)){
				$users_list .= '<tr>
					<td>'. $row_users['firstname'] .'</td>
					<td>'. $row_users['lastname'] .'</td>
					<td>'. $row_users['username'] .'</td>';
					$users_list .= '<td class="row_actions">
						<a href="#" class="'.$edit.'" onclick="userRoleForm(\''. $row_users['firstname'] .'\',\''. $row_users['lastname'] .'\',\''. $row_users['id_user'] .'\');"><i class="fa fa-cog" aria-hidden="true" title="User role"></i></a>
					</td>';
					
					// $users_list .= '<td class="row_actions">
						// <a href="" class="'.$edit.'"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
						// <a href="" class="'.$delete.'"><i class="fa fa-trash" aria-hidden="true"></i></a>
					// </td>';
				$users_list .= '</tr>';
			}


			$sql_notYet = "select name from contact where id_primary_company=( select id_primary_company from contact where
			id_contact=(select id_contact from users where id_user=$id_user ) )
			and id_contact not in ( select id_contact from users )";

			$users_notYet ='<option>-- Select a user --</option>';
			$result_notYet = pg_query($conn, $sql_notYet);
			while($row_notYet = pg_fetch_assoc($result_notYet)){
				$users_notYet .= '<option value="'. $row_notYet['name'] .'">'. $row_notYet['name'] .'</option>';
			}

			$dom = $users_list.'##'.$users_notYet;

		break;



		case "roles_definition":

			$createRight = $_GET['createRight'];
			$deleteRight = $_GET['deleteRight'];
			$editRight = $_GET['editRight'];
			
			$sql_roles = "SELECT id_role, name, name_en, name_ge, name_fr, name_es, name_po FROM roles ORDER BY name ASC";

			if($deleteRight ==1){ $delete=""; }else{ $delete="hide"; }
			if($editRight ==1){ $edit=""; }else{ $edit="hide"; }
			
			$roles_list ='';
			$result_roles = pg_query($conn, $sql_roles);
			while($row_roles = pg_fetch_assoc($result_roles)){
				$roles_list .= '<tr>
					<td><input type="radio" value="'. $row_roles['id_role'] .'" id="radioRoleDef'. $row_roles['id_role'] .'" name="id_role_radio" onchange="objectInRole(\''. $row_roles['id_role'] .'\');" class="radioBtnDefClass"></td>
					<td style="padding:0;"><label for="radioRoleDef'. $row_roles['id_role'] .'">'. $row_roles['name'] .'</label></td>
				  <td class="row_actions">
					<a href="#" class="'.$edit.'" data-toggle="modal" onclick="roleManagement(\'show\',\''. $row_roles['id_role'] .'\');" data-target="#editRolemodal"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
					<a href="" class="'.$delete.'"><i class="fa fa-trash" onclick="roleManagement(\'del\',\''. $row_roles['id_role'] .'\');" aria-hidden="true"></i></a>
				  </td>
				</tr>';
			}


			$sql_object = "SELECT 
				  objects.name, 
				  objects.id_object
				FROM 
				  public.objects
				ORDER By name ASC
			";

			$object_list ='';
			$result_object = pg_query($conn, $sql_object);
			while($row_object = pg_fetch_assoc($result_object)){
				$object_list .= '<tr>
					<td><a href="#" class="'.$edit.'" onclick="addObjectToRole(\''. $row_object['id_object'] .'\',\''. $row_object['name'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
					<td>'. $row_object['name'] .'</td>
				</tr>';
			}

			$dom = $roles_list . '##' . $object_list;

		break;
		
		
		
		case "port_costs":
		
			$update_port = $_GET['update_port'];
			$delete_port = $_GET['delete_port'];
			$update_cost = $_GET['update_cost'];
			$delete_cost = $_GET['delete_cost'];
			
			$sql_port = "select id_townport, portname, port_type_id, getregvalue(port_type_id) port_type_name 
			from ord_towns_port ORDER BY port_type_id";

			$ports_list ='';
			$result_port = pg_query($conn, $sql_port);
			while($row_port = pg_fetch_assoc($result_port)){
				
				if($row_port['port_type_id']==272){
					$text_color='text-success';
				} else
				if($row_port['port_type_id']==273){
					$text_color='text-info';
				} else
				if($row_port['port_type_id']==274){
					$text_color='text-warning';
				} else {
					$text_color='text-danger';
				}
			
				$ports_list .= '<tr>
					<td><input type="radio" value="'. $row_port['id_townport'] .'" id="radioPort'. $row_port['id_townport'] .'" name="id_port_radio" onchange="regCostList(\''. $row_port['id_townport'] .'\');" class="radioBtnPortClass"></td>
					<td style="padding:0;"><label for="radioPort'. $row_port['id_townport'] .'">'. $row_port['portname'] .'</label></td>
					<td><span class="'. $text_color .'">'. $row_port['port_type_name'] .'</span></td>
				  <td class="row_actions">';
				  
					if($update_port == 1){
						$ports_list .= '<a href="#" data-toggle="modal" onclick="portManagement(\'show\',\''. $row_port['id_townport'] .'\',\'mod\');" data-target="#modalPort"><i class="fa fa-pen-square" aria-hidden="true"></i></a> ';
					}
					
					if($delete_port == 1){
						$ports_list .= ' <a href="javascript:portManagement(\'del\',\''. $row_port['id_townport'] .'\',\'\');" onclick="return confirm(\'Are you sure you want to delete : '. $arr['portname'].' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
					}
					
				 $ports_list .= '</td>
				</tr>';
			}
			
			
			// Reg costs
			$sql_port_reg = "Select * from ord_reg_cost ORDER BY sequence_nr ASC";

			$ports_reg_list ='';
			$result_port_reg = pg_query($conn, $sql_port_reg);
			while($row_port_reg = pg_fetch_assoc($result_port_reg)){

				$ports_reg_list .= '<tr>';
					if($update_cost == 1){
						$ports_reg_list .= '<td><a href="#" onclick="addToCostPort(\''. $row_port_reg['id_reg_cost'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>';
					}
					
					$ports_reg_list .= '<td>'. $row_port_reg['item_name'] .'</td>
					<td class="row_actions">';
					
						if($update_cost == 1){
							$ports_reg_list .= '<a href="#" data-toggle="modal" onclick="showDelSysPortCost(\'show\',\''. $row_port_reg['id_reg_cost'] .'\');" data-target="#modalPortCost"><i class="fa fa-pen-square" aria-hidden="true"></i></a> ';
						}
						
						if($delete_cost == 1){
							$ports_reg_list .= ' <a href="javascript:showDelSysPortCost(\'del\',\''. $row_port_reg['id_reg_cost'] .'\');" onclick="return confirm(\'Are you sure you want to delete '. $row_port_reg['item_name'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
						}

				    $ports_reg_list .= '</td>
				</tr>';
			}

			$dom=$ports_list.'##'.$ports_reg_list;
			
		break;
		
		
		case "roles_management":

			$createRight = $_GET['createRight'];
			$editRight = $_GET['editRight'];
			$deleteRight = $_GET['deleteRight'];
			
			$sql_roles = "SELECT id_role, name, name_en, name_ge, name_fr, name_es, name_po FROM roles ORDER BY name ASC";

			if($editRight == 1){ $edit=""; } else { $edit="hide"; }
			if($deleteRight == 1){ $delete=""; } else { $delete="hide"; }
			
			$roles_list ='';
			$result_roles = pg_query($conn, $sql_roles);
			while($row_roles = pg_fetch_assoc($result_roles)){
				$roles_list .= '<tr>
					<td><input type="radio" value="'. $row_roles['id_role'] .'" id="radioRole'. $row_roles['id_role'] .'" name="id_role_radio" onchange="userInRole(\''. $row_roles['id_role'] .'\');" class="radioBtnClass"></td>
					<td style="padding:0;"><label for="radioRole'. $row_roles['id_role'] .'">'. $row_roles['name'] .'</label></td>
				  <td class="row_actions">
					<a href="#" class="'.$edit.'" data-toggle="modal" onclick="roleManagement(\'show\',\''. $row_roles['id_role'] .'\');" data-target="#editRolemodal"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
					<a href="" class="'.$delete.'"><i class="fa fa-trash" onclick="roleManagement(\'del\',\''. $row_roles['id_role'] .'\');" aria-hidden="true"></i></a>
				  </td>
				</tr>';
			}


			$sql_users = "select c.firstname, c.lastname, u.username, u.id_user
			from users u, contact c
			where u.id_contact=c.id_contact ORDER By firstname ASC";

			$users_list ='';
			$result_users = pg_query($conn, $sql_users);
			while($row_users = pg_fetch_assoc($result_users)){
				$users_list .= '<tr>
					<td><a href="#" class="'.$edit.'" onclick="addUserToRole(\''. $row_users['id_user'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
					<td>'. $row_users['firstname'] .'</td>
					<td>'. $row_users['lastname'] .'</td>
					<td>'. $row_users['username'] .'</td>
				</tr>';
			}

			$dom = $roles_list . '##' . $users_list;

		break;



		case "add_object_to_role":

			$menu_name = $_GET['name'];
			$id_role = $_GET['id_role'];
			$id_object = $_GET['id_object'];

			$sql = "Insert into role_object_perm ( id_role, id_object, menu_name ) values ( $id_role, $id_object, '$menu_name' )";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		
		case "add_user_to_role":

			$id_role = $_GET['id_role'];
			$id_user = $_GET['id_user'];

			$sql = "insert into user_role (id_role, id_user) values ($id_role, $id_user)";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;

		
		
		case "add_cost_to_port":

			$id_townport = $_GET['id_townport'];
			$id_reg_cost = $_GET['id_reg_cost'];

			$sql = "insert into ord_port_cost_item(townport_id, reg_cost_id, id_owner) values ($id_townport, $id_reg_cost, 641)";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;



		case "remove_object_from_role":

			$id_role = $_GET['id_role'];
			$id_object = $_GET['id_object'];

			$sql = "delete from role_object_perm where id_role=$id_role and id_object=$id_object";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		case "remove_user_from_role":

			$id_role = $_GET['id_role'];
			$id_user = $_GET['id_user'];

			$sql = "delete from user_role where id_role=$id_role and id_user=$id_user";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;



		case "usersInrole":

			$id_role = $_GET['id_role'];
			$typ = $_GET['typ'];
			
			$editRight = $_GET['editRight'];
			if($editRight == 1){ $edit=""; } else { $edit="hide"; }

			$sql_users = "select r.id_role, c.firstname, c.lastname, u.username, u.id_user
			from users u, roles r, user_role ur, contact c
			where ur.id_role=$id_role and u.id_user=ur.id_user and r.id_role=ur.id_role
			and c.id_contact=u.id_contact";

			$users_list ='';
			$result_users = pg_query($conn, $sql_users);
			while($row_users = pg_fetch_assoc($result_users)){
				$users_list .= '<tr>
					<td>'. $row_users['firstname'] .'</td>
					<td>'. $row_users['lastname'] .'</td>
					<td>'. $row_users['username'] .'</td>';

					if($typ == 'role'){
						$users_list .= '<td><a href="#" class="'.$edit.'" onclick="removeUserFromRole(\''. $row_users['id_role'] .'\',\''. $row_users['id_user'] .'\');"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></td>';
					}

				$users_list .= '</tr>';
			}


			$sql_role = "SELECT id_role,  name,  name_en,  name_ge,  name_fr,  name_es,  name_po FROM  roles WHERE id_role = '$id_role'";
			$result_role = pg_query($conn, $sql_role);
			$row_role = pg_fetch_assoc($result_role);


			// Permission
			$users_list_perm ='';
			if($typ == 'permission'){
				$sql_users_perm = "select o.name, rop.menu_name, rop.r_create, rop.r_read, rop.r_update, rop.r_delete, rop.id_permission
				  from role_object_perm rop, objects o
				  where rop.id_role='$id_role'
				  and o.id_object=rop.id_object
				  order by o.name asc
				";

				$result_users_perm = pg_query($conn, $sql_users_perm);
				while($row_users_perm = pg_fetch_assoc($result_users_perm)){

					if($row_users_perm['r_create'] == 1){ $check_create = 'checked'; } else { $check_create = ''; }
					if($row_users_perm['r_read'] == 1){ $check_read = 'checked'; } else { $check_read = ''; }
					if($row_users_perm['r_update'] == 1){ $check_update = 'checked'; } else { $check_update = ''; }
					if($row_users_perm['r_delete'] == 1){ $check_delete = 'checked'; } else { $check_delete = ''; }

					if(($row_users_perm['r_create'] == 1) AND
					($row_users_perm['r_read'] == 1) AND
					($row_users_perm['r_update'] == 1) AND
					($row_users_perm['r_delete'] == 1)) {
						$check_full = 'checked'; $value_full=1;
					} else { $check_full = ''; $value_full=0; }

					$users_list_perm .= '<tr>
						<td>'. $row_users_perm['name'] .'</td>
						<td><input type="checkbox" '. $check_full .' value="'. $row_users_perm['id_permission'] .'#full#'. $value_full .'" class="i-checks check_RolePerm"></td>
						<td><input type="checkbox" '. $check_create .' value="'. $row_users_perm['id_permission'] .'#create#'. $row_users_perm['r_create'] .'" class="i-checks check_RolePerm"></td>
						<td><input type="checkbox" '. $check_read .' value="'. $row_users_perm['id_permission'] .'#read#'. $row_users_perm['r_read'] .'" class="i-checks check_RolePerm"></td>
						<td><input type="checkbox" '. $check_update .' value="'. $row_users_perm['id_permission'] .'#update#'. $row_users_perm['r_update'] .'" class="i-checks check_RolePerm"></td>
						<td><input type="checkbox" '. $check_delete .' value="'. $row_users_perm['id_permission'] .'#delete#'. $row_users_perm['r_delete'] .'" class="i-checks check_RolePerm"></td>
					</tr>';
				}
			}

			$role_def_list ='';
			if($typ == 'role'){
				$sql_role_def = "SELECT 
					  objects.name, 
					  role_object_perm.id_role
					FROM 
					  public.role_object_perm, 
					  public.objects
					WHERE 
					  objects.id_object = role_object_perm.id_object
					AND role_object_perm.id_role=$id_role
					ORDER BY objects.name ASC
				";

				$result_role_def = pg_query($conn, $sql_role_def);
				$x=1;
				while($row_role_def = pg_fetch_assoc($result_role_def)){
					$role_def_list .= '<tr>
						<td>'. $x .'</td>
						<td>'. $row_role_def['name'] .'</td>
					</tr>';
					
					$x++;
				}
				
				// Users list not in role
				$sql_n_users = "Select us.id_user, us.username, ct.firstname, ct.lastname from users us
					LEFT JOIN contact ct ON us.id_contact = ct.id_contact
				where id_user not in ( 
					select id_user from user_role where id_role=$id_role 
				) ORDER By ct.firstname ASC";

				$n_users_list ='';
				$result_n_users = pg_query($conn, $sql_n_users);
				while($row_n_users = pg_fetch_assoc($result_n_users)){
					$n_users_list .= '<tr>
						<td><a href="#" onclick="addUserToRole(\''. $row_n_users['id_user'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
						<td>'. $row_n_users['firstname'] .'</td>
						<td>'. $row_n_users['lastname'] .'</td>
						<td>'. $row_n_users['username'] .'</td>
					</tr>';
				}
			}

			$dom = $users_list . '##' . $row_role['name'] . '##' . $users_list_perm . '##' . $role_def_list . '##' . $n_users_list;

		break;

		
		case "objectsInrole":

			$editRight = $_GET['editRight'];
			$id_role = $_GET['id_role'];
			$typ = $_GET['typ'];

			$sql_role_def = "SELECT 
				  objects.name, 
				  objects.id_object,
				  role_object_perm.id_role
				FROM 
				  public.role_object_perm, 
				  public.objects
				WHERE 
				  objects.id_object = role_object_perm.id_object
				AND role_object_perm.id_role=$id_role
				ORDER BY objects.name ASC
			";

			$role_def_list ='';
			if($editRight == 1){ $edit=""; } else { $edit="hide"; }
			
			$result_role_def = pg_query($conn, $sql_role_def);
			while($row_role_def = pg_fetch_assoc($result_role_def)){
				$role_def_list .= '<tr>
					<td>'. $row_role_def['name'] .'</td>';
					
					if($typ == 'object'){
						$role_def_list .= '<td><a href="#" class="'.$edit.'" onclick="removeObjectFromRole(\''. $row_role_def['id_role'] .'\',\''. $row_role_def['id_object'] .'\');"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></td>';
					}
					
				$role_def_list .= '</tr>';
			}

			$sql_role = "SELECT id_role,  name,  name_en,  name_ge,  name_fr,  name_es,  name_po FROM  roles WHERE id_role = '$id_role'";
			$result_role = pg_query($conn, $sql_role);
			$row_role = pg_fetch_assoc($result_role);

			
			// Objects list not in role
			$sql_object = "Select id_object, name from objects where id_object not in ( 
					Select id_object from role_object_perm where id_role=$id_role 
				) ORDER By name ASC
			";

			$object_list ='';
			$result_object = pg_query($conn, $sql_object);
			while($row_object = pg_fetch_assoc($result_object)){
				$object_list .= '<tr>
					<td><a href="#" onclick="addObjectToRole(\''. $row_object['id_object'] .'\',\''. $row_object['name'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
					<td>'. $row_object['name'] .'</td>
				</tr>';
			}
			
			
			$dom = $role_def_list . '##' . $row_role['name'] . '##' . $object_list;

		break;
		

		
		case "reg_cost_list":

			$id_townport = $_GET['id_townport'];
			$assign_port = $_GET['assign_port'];
			// $typ = $_GET['typ'];

			$sql_port_cost = "Select * from ord_reg_cost where id_reg_cost not in ( 
				select reg_cost_id from ord_port_cost_item where townport_id = $id_townport 
			) ORDER BY sequence_nr ASC
			";

			// reg_cost not in
			$ports_reg_list ='';
			$result_port_cost = pg_query($conn, $sql_port_cost);
			while($row_port_cost = pg_fetch_assoc($result_port_cost)){
				$ports_reg_list .= '<tr>';
					if($assign_port == 1){
						$ports_reg_list .= '<td><a href="#" onclick="addToCostPort(\''. $row_port_cost['id_reg_cost'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>';
					}
					
					$ports_reg_list .= '<td>'. $row_port_cost['item_name'] .'</td>
					<td class="row_actions">
					  <a href="#" data-toggle="modal" onclick="portCostManagement(\'show\',\''. $row_port_cost['id_reg_cost'] .'\');" data-target="#"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
					  <a href=""><i class="fa fa-trash" onclick="portCostManagement(\'del\',\''. $row_port_cost['id_reg_cost'] .'\');" aria-hidden="true"></i></a>
				    </td>
				</tr>';
			}

			$sql_reg_cost = "select id_townport, portname, port_type_id, getregvalue(port_type_id) port_type_name from ord_towns_port WHERE id_townport = '$id_townport'";
			$result_reg_cost = pg_query($conn, $sql_reg_cost);
			$row_reg_cost = pg_fetch_assoc($result_reg_cost);

			
			// reg_cost in
			$sql_reg_costin = "Select * from ord_reg_cost where id_reg_cost in ( 
				select reg_cost_id from ord_port_cost_item where townport_id = $id_townport 
			) ORDER BY sequence_nr ASC";

			$reg_costin_list ='';
			$result_reg_costin = pg_query($conn, $sql_reg_costin);
			while($row_reg_costin = pg_fetch_assoc($result_reg_costin)){
				$reg_costin_list .= '<tr>
					<td>'. $row_reg_costin['item_name'] .'</td>';
					
					if($assign_port == 1){
						$reg_costin_list .= '<td><a href="#" onclick="removeCostFromPort(\''. $row_reg_costin['id_reg_cost'] .'\');"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></td>';
					}
					
				$reg_costin_list .= '</tr>';
			}
			
			
			$dom = $ports_reg_list . '##' . $row_reg_cost['portname'] . '##' . $reg_costin_list;

		break;

		
		
		
		case "roles_configurations":

			$conf = $_GET['conf'];

			if($conf == 'add'){

				$name = $_GET['role_name'];
				
				if($_GET['Role_name_en']!=""){
					$name_en = $_GET['Role_name_en'];
					$name_en_req = 'name_en,';
					$name_en_val = '\'$name_en\',';
				} else { $name_en_req = ''; $name_en_val = ''; }
				
				if($_GET['Role_name_ge']!=""){
					$name_ge = $_GET['Role_name_ge'];
					$name_ge_req = 'name_ge,';
					$name_ge_val = '\'$name_ge\',';
				} else { $name_ge_req = ''; $name_ge_val = ''; }
				
				if($_GET['Role_name_fr']!=""){
					$name_fr = $_GET['Role_name_fr'];
					$name_fr_req = 'name_fr,';
					$name_fr_val = '\'$name_fr\',';
				} else { $name_fr_req = ''; $name_fr_val = ''; }
				
				if($_GET['Role_name_es']!=""){
					$name_es = $_GET['Role_name_es'];
					$name_es_req = 'name_es,';
					$name_es_val = '\'$name_es\',';
				} else { $name_es_req = ''; $name_es_val = ''; }
				
				if($_GET['Role_name_po']!=""){
					$name_po = $_GET['Role_name_po'];
					$name_po_req = 'name_po,';
					$name_po_val = '\'$name_po\',';
				} else { $name_po_req = ''; $name_po_val = ''; }
	
	
				$sql = "Insert into roles ($name_en_req $name_ge_req $name_fr_req $name_es_req $name_po_req name)
					Values ($name_en_val $name_ge_val $name_fr_val $name_es_val $name_po_val '$name')
				";
				
				
				$result = pg_query($conn, $sql);

				if ($result) {
					$dom=1;
				} else {
					$dom=0;
				}

			} else
			if($conf == 'show'){

				$id_role = $_GET['id_role'];

				$sql_role = "SELECT id_role, name, name_en, name_ge, name_fr, name_es, name_po FROM roles WHERE id_role = '$id_role'";
				$result_role = pg_query($conn, $sql_role);
				$row_role = pg_fetch_assoc($result_role);

				$dom=$row_role['name'].'#'.$row_role['name_en'].'#'.$row_role['name_ge'].'#'.$row_role['name_fr'].'#'.$row_role['name_es'].'#'.$row_role['name_po'].'#'.$row_role['id_role'];

			} else
			if($conf == 'edit'){

				$name = $_GET['editRole_name'];
				$name_en = $_GET['editRole_name_en'];
				$name_ge = $_GET['editRole_name_ge'];
				$name_fr = $_GET['editRole_name_fr'];
				$name_es = $_GET['editRole_name_es'];
				$name_po = $_GET['editRole_name_po'];
				$id_role = $_GET['editRole_id'];

				$sql = "Update roles set name='$name', name_en='$name_en', name_ge='$name_ge', name_fr='$name_fr', name_es='$name_es', name_po='$name_po'
					Where id_role = '$id_role'";
				$result = pg_query($conn, $sql);

				if ($result) {
					$dom=1;
				} else {
					$dom=0;
				}

			}else
			if($conf == 'del'){

				$id_role = $_GET['id_role'];

				$sql = "Delete from roles where id_role='$id_role'";
				$result = pg_query($conn, $sql);

				if ($result) {
					$dom=1;
				} else {
					$dom=0;
				}

			} else {

			}

		break;



		case "add_new_object":

			$name = $_GET['name'];

			$sql = "INSERT INTO objects(name, locked) VALUES ('$name', 0)";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;

		

		case "change_permission":

			$id_permission = $_GET['id_permission'];
			$type = $_GET['type'];
			$valeur = $_GET['valeur'];

			$req_1 = 0; $req_2 = 0;

			if($type == 'create'){
				$_element = 'r_create='.$valeur; $req_1 = 1;
			} else
			if($type == 'read'){
				$_element = 'r_read='.$valeur; $req_1 = 1;
			} else
			if($type == 'update'){
				$_element = 'r_update='.$valeur; $req_1 = 1;
			} else
			if($type == 'delete'){
				$_element = 'r_delete='.$valeur; $req_1 = 1;
			} else
			if($type == 'full'){
				$req_2 = 1;
			} else {}

			if($req_1 == 1){ $sql = "update role_object_perm set $_element where id_permission=$id_permission"; }
			if($req_2 == 1){
				if($valeur == 1){ $sql = "update role_object_perm set r_create=1, r_read=1, r_update=1, r_delete=1 where id_permission=$id_permission"; }
				else { $sql = "update role_object_perm set r_create=0, r_read=0, r_update=0, r_delete=0 where id_permission=$id_permission"; }
			}

			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;



		case "wizard_form1":

			$id_user = $_SESSION['id_user'];

			$cond=" AND id_supchain_type =110";

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
				id_contact=(select id_contact from users where id_user=$id_user ) )) $cond ORDER BY name ASC
			";

			$contact_list = '<option value="">-- Select a client --</option>';

			$result = pg_query($conn, $sql_stats);
			while($row = pg_fetch_assoc($result)){
				$contact_list .= '<option value="'. $row['id_contact'] .'">'. $row['name'] .'</option>';
			}

			$dom = $contact_list;

		break;
		
		
		case "cancel_wizard":
			
			$id_ord_order = $_GET['ord_order_id'];
			
			if($id_ord_order!=""){
				
				// Ocean_schedule
				$sql_1 = "select ord_order_id from ord_ocean_schedule where ord_order_id=$id_ord_order";
				$r_1 = pg_query($conn, $sql_1);
				if ($r_1 && pg_num_rows($r_1) > 0) {
					$shedule=1;
				} else {
					$shedule=0;
				}
				
				// Order_items
				$sql_2 = "select ord_order_id from ord_order_item where ord_order_id=$id_ord_order";
				$r_2 = pg_query($conn, $sql_2);
				if ($r_2 && pg_num_rows($r_2) > 0) {
					$items=1;
				} else {
					$items=0;
				}
			
				// Order
				$sql_3 = "select id_ord_order from ord_order where id_ord_order=$id_ord_order";
				$r_3 = pg_query($conn, $sql_3);
				if ($r_3 && pg_num_rows($r_3) > 0) {
					$order=1;
				} else {
					$order=0;
				}
				
				
				// Check step
				if(($shedule == 1) && ($items == 1) && ($order == 1)) {
					$step=3;
				} else
				if(($shedule == 0) && ($items == 1) && ($order == 1)) {
					$step=2;
				} else 
				if(($shedule == 0) && ($items == 0) && ($order == 1)) {
					$step=1;
				} else {
					$step=0;
				}
			
				// Delete records
				if($step==3){
					$sql1 = "delete from ord_ocean_schedule where ord_order_id=$id_ord_order";
					$result1 = pg_query($conn, $sql1);
					$done1 = pg_affected_rows($result1);
					
					$sql2 = "delete from ord_order_item where ord_order_id=$id_ord_order";
					$result2 = pg_query($conn, $sql2);
					$done2 = pg_affected_rows($result2);
					
					$sql3 = "delete from ord_order where id_ord_order=$id_ord_order";
					$result3 = pg_query($conn, $sql3);
					$done3 = pg_affected_rows($result3);
				
					if(($done1>0)&&($done2>0)&&($done3>0)){
						$dom=1;
					} else {
						$dom=0;
					}
					
				} else 
				if($step==2){
					$sql1 = "delete from ord_order_item where ord_order_id=$id_ord_order";
					$result1 = pg_query($conn, $sql1);
					$done1 = pg_affected_rows($result1);
					
					$sql2 = "delete from ord_order where id_ord_order=$id_ord_order";
					$result2 = pg_query($conn, $sql2);
					$done2 = pg_affected_rows($result2);
					
					if(($done1>0)&&($done2>0)){
						$dom=1;
					} else {
						$dom=0;
					}
					
				} else
				if($step==1){
					$sql1 = "delete from ord_order where id_ord_order=$id_ord_order";
					$result1 = pg_query($conn, $sql1);
					$done1 = pg_affected_rows($result1);
					
					if($done1>0){
						$dom=1;
					} else {
						$dom=0;
					}
					
				} else {
					$dom=0;
				}
				
			} else {
				$dom=3;
			}
			
		break;
		
		
		case "product_saveorder":
		
			$product_type = $_GET['product_type'];

			$sql_stats = "SELECT id_product, id_culture, product_code, product_name, product_upc, 
				   product_cas, product_barcode, product_msds, product_desc, measure_unit, product_type
			  FROM public.product 
			WHERE product_type = '$product_type' ORDER BY product_name ASC";

			$product_list = '<option value="">-- Select product --</option>';

			$result = pg_query($conn, $sql_stats);
			while($row = pg_fetch_assoc($result)){
				$product_list .= '<option value="'. $row['id_product'] .','. $row['product_code'] .'">'. $row['product_name'] .'</option>';
			}

			// INSERT ORD_ORDER
			
			$status_id = $_GET['status_id'];
			$pipeline_id = $_GET['pipeline_id'];
			$ord_cus_contact_id = $_GET['ord_cus_contact_id'];
			$ord_cus_person_id = $_GET['ord_cus_person_id'];
			$customer_reference_nr = $_GET['customer_reference_nr'];
			$order_incoterms_id = $_GET['order_incoterms_id'];
			
			if($_GET['port_id']!=""){ $port_id = $_GET['port_id']; }
			else { $port_id = 0; }
			
			$port_code = $_GET['port_code'];
			$package_type_id = $_GET['package_type_id'];
			$nr_shipments = $_GET['nr_shipments'];
			
			// $sel_month_year = $_GET['delivery_date'];
			// $sel_year_month = explode('/', $sel_month_year);
			// $dateToTest = $sel_year_month[1] ."-". $sel_year_month[0] ."-1";
			// $lastday = date('t',strtotime($dateToTest));
			// $delivery_date = $sel_year_month[1] ."-". $sel_year_month[0] . "-" . $lastday;

			// $delivery_date = $_GET['delivery_date'];
			// $delivery_week = $_GET['delivery_week'];
			
			if(!empty($_GET['delivery_date'])){
				$delivery_date = $_GET['delivery_date'];
				$delivery_week = $_GET['delivery_week'];
				
			} else {
				$delivery_date = $_GET['delivery_date2'];
				$delivery_week = $_GET['delivery_week2'];
			}
			
			$ord_cus_person_id2 = $_GET['ord_cus_person_id2'];
			
			if($_SESSION['id_supchain_type'] == 110){
				$ord_cus_person_id = $_GET['ord_cus_person_id'];
				
				if(($_SESSION['id_company'] == 689)OR($_SESSION['id_company'] == 4898)){
					$ord_imp_contact_id = 717;
					$ord_imp_person_id = 0;
					
				} else {
					$ord_imp_contact_id = 641;
					$ord_imp_person_id = 0;
				}
				
			} else {
				
				$ord_imp_person_id = $_SESSION['id_contact'];
				$ord_cus_person_id = $ord_cus_person_id2;
				
				if($ord_cus_contact_id == 689){
					$ord_imp_contact_id = 717;
					
				} else {
					$ord_imp_contact_id = $_SESSION['id_company'];
				}
			}
			
			$created_date = gmdate("Y/m/d H:i");
			$created_by = $_SESSION['id_user'];
			$request_date = $created_date;
	
			$date = explode('-', gmdate("Y-m-d-H-i-s"));
			$internal_reference_nr = $ord_cus_contact_id . $date[0] . $date[1] . $date[2] . $date[3] . $date[4] . $date[5];
			
			$order_infos = "";
			
			// Check for existing record
			
			$sql_getrec = "SELECT * FROM public.ord_order WHERE 
				status_id='$status_id' AND
				pipeline_id='$pipeline_id' AND
				ord_cus_contact_id='$ord_cus_contact_id' AND
				nr_shipments='$nr_shipments' AND
				ord_cus_person_id='$ord_cus_person_id' AND
				ord_imp_contact_id='$ord_imp_contact_id' AND
				customer_reference_nr='$customer_reference_nr' AND
				ord_imp_person_id='$ord_imp_person_id' AND
				order_incoterms_id='$order_incoterms_id' AND
				pod_id='$port_id' AND
				port_code='$port_code' AND
				package_type_id='$package_type_id' AND
				product_type='$product_type' AND
				delivery_week='$delivery_week' AND
				created_by='$created_by' 
			";
		
			$add=1;
			$result_getrec = pg_query($conn, $sql_getrec) or die(pg_last_error());
			$count = pg_num_rows($result_getrec);
			if($count==1){
				// $add=0;
			}
		
			if($add==1){
				$sql = "INSERT INTO public.ord_order(
				ord_cus_contact_id, status_id, 
				pipeline_id, customer_reference_nr, delivery_date, 
				order_incoterms_id, pod_id, ord_fa_contact_id,
				nr_shipments, ord_cus_person_id, ord_imp_contact_id, ord_imp_person_id, port_code, 
				product_type, request_date, package_type_id, 
				created_date, internal_reference_nr, created_by, id_owner, delivery_week)
				VALUES ('$ord_cus_contact_id', '$status_id', '$pipeline_id', '$customer_reference_nr', 
				'$delivery_date', '$order_incoterms_id', 
				'$port_id', 4900, '$nr_shipments', '$ord_cus_person_id', '$ord_imp_contact_id', '$ord_imp_person_id', 
				'$port_code', '$product_type', '$request_date', '$package_type_id', '$created_date', '$internal_reference_nr',
				'$created_by', '$ord_imp_contact_id', '$delivery_week')";
		
				$result = pg_query($conn, $sql);
				
				if ($result) {
					
					$sql_id = "SELECT id_ord_order FROM public.ord_order WHERE 
						ord_cus_contact_id='$ord_cus_contact_id' AND
						status_id='$status_id' AND
						pipeline_id='$pipeline_id' AND
						delivery_date='$delivery_date' AND
						order_incoterms_id='$order_incoterms_id' AND
						pod_id='$port_id' AND
						nr_shipments='$nr_shipments' AND
						ord_cus_person_id='$ord_cus_person_id' AND
						ord_imp_contact_id='$ord_imp_contact_id' AND
						ord_imp_person_id='$ord_imp_person_id' AND
						product_type='$product_type' AND
						package_type_id='$package_type_id' AND
						created_by='$created_by' 
					";
			
					$result_id = pg_query($conn, $sql_id);
					$row_id = pg_fetch_assoc($result_id);
					$last_id = $row_id['id_ord_order'];
					
					$order_infos = $internal_reference_nr .'?'. $created_date .'?'.$created_by . '?' . $ord_imp_contact_id;
					
					$dom='1##'.$product_list.'##'.$order_infos.'##'.$last_id;
					
				} else {
					
					$row_getrec = pg_fetch_assoc($result_getrec);
					$order_infos = $row_getrec['internal_reference_nr'] .'?'. $row_getrec['created_date'] .'?'.$row_getrec['created_by'] . '?' . $row_getrec['ord_imp_contact_id'];
					$last_id = $row_getrec['id_ord_order']; 
					
					$dom='0##'.$product_list.'##'.$order_infos.'##'.$last_id;
				}
				
			} else {
				$row_getrec = pg_fetch_assoc($result_getrec);
				$order_infos = $row_getrec['internal_reference_nr'] .'?'. $row_getrec['created_date'] .'?'.$row_getrec['created_by'] . '?' . $row_getrec['ord_imp_contact_id'];
				$last_id = $row_getrec['id_ord_order']; 
				
				$dom='3##'.$product_list.'##'.$order_infos.'##'.$last_id;
			}

		break;
		
		
		
		case "saveproduct_oceanschedule":
			
			$ocean=0;
			$product=0;
		
			if(isset($_GET['order_infos'])){  
				
				$order_infos = explode('?', $_GET['order_infos']); 
			
				$internal_reference_nr = $order_infos[0];
				$created_date = $order_infos[1];
				$created_by = $order_infos[2];
				$id_owner = $order_infos[3];
				
			
				$sql_getid = "SELECT id_ord_order FROM public.ord_order WHERE internal_reference_nr='$internal_reference_nr'";
				
				$result_getid = pg_query($conn, $sql_getid);
				$row_getid = pg_fetch_assoc($result_getid);
				$id_ord_order=$row_getid['id_ord_order'];
				
				if($id_ord_order!=""){
					
					if($_GET['pod_id']!=""){
						$pod_id = $_GET['pod_id'];
					} else { $pod_id = 0; }
					
					$cus_incoterms_id = $_GET['cus_incoterms_id'];
					
					$id_product = $_GET['id_product'];
					$product_code = $_GET['product_code'];
					$measure_unit = $_GET['measure_unit'];
					$product_quantity = $_GET['product_quantity'];
					$package_type_id = $_GET['package_type_id'];
					$weight_total = $_GET['weight_total'];
					$weight_unit = $_GET['weight_unit'];
					
					$nr_shipments = $_GET['nr_shipments'];
					$new_creation_date = gmdate("Y/m/d H:i");
			
					if(!empty($_GET['delivery_date'])){
						$fieldMonth="month_eta";
						$fieldWeek="week_eta";
						
						$valMonth=$_GET['delivery_date'];
						$valWeek=$_GET['delivery_week'];
						
					} else {
						$fieldMonth="month_etd";
						$fieldWeek="week_etd";
						
						$valMonth=$_GET['delivery_date2'];
						$valWeek=$_GET['delivery_week2'];
					}
					
					$sql_product = "INSERT INTO public.ord_order_item(
						ord_order_id, product_id, measure_unit, product_quantity, product_code, 
						weight_total, package_type_id, created_date, created_by, 
							weight_unit, id_owner)
					VALUES ('$id_ord_order', '$id_product', '$measure_unit', '$product_quantity', '$product_code', 
						'$weight_total', '$package_type_id', '$new_creation_date', '$created_by', 
						'$weight_unit', '$id_owner')
					";
	
					$result_product = pg_query($conn, $sql_product);
		
					if ($result_product) {  
						
						for ($i = 1; $i <= $nr_shipments; $i++) {
							
							if($i==1){
								$month=$valMonth;
								$week=$valWeek;
							
							} else {	
								$x=$i-1;
								$time = strtotime($valMonth);
								$month = date("Y/m/d", strtotime("+".$x." month", $time));
								
								$d = explode('/', $month); 
								$week=get_weekth($d[0],$d[1],$d[2]);
							}
								
							$sql_ocean = "INSERT INTO public.ord_ocean_schedule(
								ord_order_id, rq_date, nr_containers, $fieldMonth, 
								order_ship_nr, weight_container, weight_shipment, 
								created_date, created_by, id_owner, $fieldWeek, pod_id, cus_incoterms_id)
							VALUES ('$id_ord_order', '$created_date', '$product_quantity', '$month',
								'$i', '$weight_unit', '$weight_total', 
								'$new_creation_date', '$created_by', '$id_owner', '$week', '$pod_id', $cus_incoterms_id)";
				
							$result_ocean = pg_query($conn, $sql_ocean);
								
							if($result_ocean){
								$ocean=1;
							} else {
								$ocean=0;
							}
						}

						$product=1;
						
					} else {
						$product=0;
					}
			
					$grid_list = '';
					$sql_grid = " SELECT * FROM public.v_logistics_schedule WHERE ord_order_id=$id_ord_order ";
					$result_grid = pg_query($conn, $sql_grid);
				
					$tBheader="";
					while($row = pg_fetch_assoc($result_grid)){
						
						if($row['month_eta']!=""){
							$date='<div class="input-group pull-left date" style="width:160px;">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" class="form-control edit_delivery_date" id="date-'. $row['id_ord_schedule'] .'" name="edit_delivery_date" onchange="schedule_date(\''. $row['id_ord_schedule'] .'\',\'ETA\');" value="'. $row['month_eta'] .'">
							</div>
							<div class="pull-right" id="sch_week_'. $row['id_ord_schedule'] .'" style="line-height:34px;">'. $row['week_eta'] .'</div>';
							$tBheader="ArrMonth";
						} else {
							$date='<div class="input-group pull-left date" style="width:160px;">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" class="form-control edit_delivery_date" id="date-'. $row['id_ord_schedule'] .'" name="edit_delivery_date" onchange="schedule_date(\''. $row['id_ord_schedule'] .'\',\'ETD\');" value="'. $row['month_etd'] .'">
							</div>
							<div class="pull-right" id="sch_week_'. $row['id_ord_schedule'] .'" style="line-height:34px;">'. $row['week_etd'] .'</div>';
							$tBheader="DptMonth";
						}
						
						$grid_list .= '<tr class="gradeX">
							<td style="line-height:34px;">
								No '.$row['order_ship_nr'].'
							</td> 
							<td>
								<input id="qty-'. $row['id_ord_schedule'] .'" onchange="schedule_product_qty('. $row['id_ord_schedule'] .','. $row['weight_container'] .');" style="width:120px;" name="edit_product_quantity" value="'. $row['nr_containers'] .'" type="number" min="0" class="form-control">
							</td>
							<td class="center" style="line-height:34px;">'. $row['weight_container'] .'</td>
							<td class="center" style="line-height:34px;">'. $row['weight_shipment'] .'</td>
							<td>'.$date.'</td>
						</tr>';
					}
 
					$sql_imp = "SELECT importer FROM public.v_order WHERE id_ord_order = $id_ord_order";
					$result_imp = pg_query($conn, $sql_imp);
					$row_imp = pg_fetch_assoc($result_imp);
					
					$importer = trim($row_imp['importer']);
					
					$dom=$product.'#'.$ocean.'#'.$grid_list.'#'.$importer.'#'.$tBheader;
					
				} else {
					$dom='zzz';
				}
			
			} else {
				$dom='0#0';
			}
			
		break;
		
		
		case "savenote":
		
			$order_infos = $_GET['order_infos'];
			$notes = $_GET['notes'];
			$id_ord_order = "";
			
			if($_SESSION['id_supchain_type'] == 110){
				$notes_customer = $notes;
				$cond="notes_customer='$notes_customer'";
			} else {
				$notes_internal = $notes;	
				$cond="notes_internal='$notes_internal'";
			}
			
			
			$order_infos = explode('?', $_GET['order_infos']); 
			
			$internal_reference_nr = $order_infos[0];
			$id_owner = $order_infos[3];
			
			$sql = "UPDATE public.ord_order
			   SET $cond
			WHERE internal_reference_nr='$internal_reference_nr' AND id_owner='$id_owner'";

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				
				$sql_id = "SELECT id_ord_order FROM public.ord_order WHERE internal_reference_nr='$internal_reference_nr' AND id_owner='$id_owner'";
				$result_id = pg_query($conn, $sql_id);
				$row_id = pg_fetch_assoc($result_id);
				$id_ord_order = $row_id['id_ord_order'];
				
				$send = 0;
				if($id_ord_order!=""){
					$sql_header = "SELECT v_order_schedule.id_ord_schedule, 
						v_order_schedule.supplier_name, 
						v_order_schedule.person_name, 
						to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date, 
						v_order_schedule.product_code, 
						v_order_schedule.port_name, 
						v_order_schedule.incoterms, 
						v_order_schedule.offer_validity_date, 
						v_order_schedule.notes_sup, 
						get_contact_email(v_order_schedule.supplier_person_id) AS email_contact, 
						v_order.importer_person, 
						v_order_msg.cus_email, 
						v_order_msg.ord_cus_admin_id, 
						v_order_msg.ord_cus_person_id,
						v_order_msg.imp_mail, 
						v_order_msg.imp_phone, 
						v_order_msg.imp_skype, 
						v_order_msg.imp_admin_mail, 
						v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number, 
						v_order.importer,  
						v_order.ord_imp_contact_id,
						v_order_msg.ord_imp_admin_id,
						v_order.ord_imp_person_id,
						v_order.created_by_name, 
						v_order.created_date, 
						v_order.customer_name, 
						v_order.customer_contact, 
						v_order.notes_customer, 
						v_order.port_discharge, 
						v_order.incoterms as order_incoterms 
					   FROM v_order_schedule, v_order_msg, v_order 
					   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
					   and v_order.id_ord_order=v_order_schedule.ord_order_id 
					   and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) 
					from v_order_schedule where ord_order_id=$id_ord_order )"; 

					$result_header = pg_query($conn, $sql_header);
					$row_header = pg_fetch_assoc($result_header);
					
					$id_ord_schedule = $row_header['id_ord_schedule'];
					$supplier_name = $row_header['supplier_name'];
					$person_name = $row_header['person_name'];
					$proposal_date = $row_header['proposal_date'];
					$product_code = $row_header['product_code'];
					$port_name = $row_header['port_name'];
					$incoterms = $row_header['incoterms'];
					$offer_validity_date = $row_header['offer_validity_date'];
					$notes_sup = $row_header['notes_sup'];
					$email_contact = $row_header['email_contact'];
					$importer_person = $row_header['importer_person'];
					$cus_email = $row_header['cus_email'];
					$cus_admin_id = $row_header['cus_admin_id'];
					$imp_mail = $row_header['imp_mail'];
					$imp_phone = $row_header['imp_phone'];
					$imp_skype = $row_header['imp_skype'];
					$imp_admin_mail = $row_header['imp_admin_mail'];
					$order_number = $row_header['order_number'];
					$importer = $row_header['importer'];
					$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
					$created_by_name = $row_header['created_by_name'];
					$created_date = $row_header['created_date'];
					$customer_name = $row_header['customer_name'];
					$customer_contact = $row_header['customer_contact'];
					$notes_customer = $row_header['notes_customer'];
					$port_discharge = $row_header['port_discharge'];
					$order_incoterms = $row_header['order_incoterms'];
					$imp_admin_id = $row_header['ord_imp_admin_id'];
					$ord_imp_person_id = $row_header['ord_imp_person_id'];
					$ord_cus_person_id = $row_header['ord_cus_person_id'];
					
					$content ='';
					
					$sql_detail = "select customer_reference_nr||'.'||customer_ref_ship_nr as no, to_char(month_eta,'Monyy')as month_eta, week_eta,
					to_char(month_etd,'Monyy')as month_etd, week_etd, nr_containers as no_con,      
					to_char(weight_shipment,'999G999') as weight from     
					v_order_schedule where ord_order_id=$id_ord_order order by no";

					
					$result_detail = pg_query($conn, $sql_detail);
					while($row_detail = pg_fetch_assoc($result_detail)){
						if($row_detail['month_eta']!=""){
							$hd="ETA"; $val=$row_detail['month_eta'].'/'.$row_detail['week_eta'];
						} else { $hd="ETD"; $val=$row_detail['month_etd'].'/'.$row_detail['week_etd']; }
						
						$content .='<tr style="padding:0;text-align:left;vertical-align:top">
							<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_detail['no'].'</td>
							<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_detail['no_con'].'</td>
							<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_detail['weight'].'</td>
							<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$val.'</td>
						</tr>';	
					}
				
					$send = 1;
				}
			
				if($send == 1){
				
					$to = 'croth53@gmail.com';
					$subject = preg_replace('/\s+/', '', $order_number) . ': Your new Request for Quote';

					$headers = "From: NewRFQ <noreply@icollect.live>\r\n";
					$headers .= "Reply-To: Sales <$imp_mail>\r\n";
					$headers .= "CC: $imp_mail, $imp_admin_mail\r\n"; 
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				
				
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
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
																									<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
																								</td>
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																									iCRM.live Message from '.$importer.' icollect.live Back Office:
																								</td>
																							</tr>
																						</table>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Thank you! This is to confirm that we have received a new RFQ from you:
																						</p>
																						
																						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
																									</td>
																								</tr>
																							</tbody>
																						</table>
																						
																						<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
																										<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Client</strong><br>'.$customer_name.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Contact</strong><br>'.$customer_contact.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Port of Destination</strong><br>'.$port_discharge.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Incoterms</strong><br>'.$order_incoterms.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Product</strong><br>'.$product_code.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																													
																													<th class="small-12 large-6 columns last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Date of request entered in iDiscover:</strong><br>'.$created_date.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Requested entered by:</strong><br>'.$created_by_name.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Our Reference:</strong><br>'.$order_number.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																									
																									<th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0">
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Our Ref.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Cont.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">MT</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">'.$hd.'</th>
																								</tr>
																								
																								'. $content . '
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
																							Message delivered by icollect.live back office on behalf of:
																						</h4>
																						
																						<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
																										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Company:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Sales Manager:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Email:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Phone:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Skype:</strong>
																														</p>
																													</th>
																													
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$importer.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$importer_person.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$imp_mail.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$imp_phone.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$imp_skype.'
																														</p>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Before printing think about the ENVIRONMENT!<br>
																							Warning: If you have received this email by error, please delete it and
																							inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
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
					
					if($imp_mail==""){$imp_mail="c.roth@dev4impact.com";}
					$sender="noreply@icollect.live";
					$recipient='croth53@gmail.com';
				
					$mail = new PHPMailer;
					$mail->isSMTP();
					// $mail->SMTPDebug = 2;
					// $mail->SMTPSecure = 'ssl';
					$mail->Debugoutput = 'html';
					$mail->Host = "d4i.maxapex.net";
					$mail->Port = 587;
					$mail->SMTPAuth = true;
					$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
					$mail->Username = ID_USER;
					$mail->Password = ID_PASS;
					$mail->setFrom('noreply@icollect.live', 'NewRFQ');
					$mail->AddCC($imp_mail);
					$mail->AddCC($imp_admin_mail);
					// $mail->AddCC($cus_email);
					$mail->AddBCC('croth53@gmail.com');
					$mail->addReplyTo($imp_mail, 'Sales');
					$mail->addAddress($imp_mail, $imp_person_name);
					$mail->Subject = $subject;
					$mail->msgHTML($message);
					$mail->AltBody = 'This is a plain-text message body';

					//send the message, check for errors
					if (!$mail->send()) {
						$save=0;
					} else {
						$save=1;
					}
					
					if($save==1){
						// Save Email  $imp_mail, $imp_admin_mail
						$msg_recipients = $ord_imp_person_id.', '.$imp_admin_id;
						
						$sql1 = "SELECT v_order.customer_code||'-'||v_order.order_nr as file_name FROM v_order WHERE id_ord_order=$id_ord_order";
						$rst = pg_query($conn, $sql1);
						$row = pg_fetch_assoc($rst);
						
						$date = date_create();
						$timestamp = date_timestamp_get($date);
						
						$file = trim($row['file_name']);
						$file_name = str_replace(' ', '_', $file);
						
						$doc_filename=$file_name.'-80-'.$timestamp.'.pdf';
					
						$email_sender_company_id = $_SESSION['id_company'];
						$created_by = $_SESSION['id_user'];
						$id_owner = $_SESSION['id_contact'];
						$created_date = gmdate("Y/m/d H:i");
						
						$sql = "insert into ord_document (ord_order_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
						email_sender_id, email_sender_company_id, msg_recipients) 
						values ($id_ord_order, '$doc_filename', 80, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
						$created_by, $email_sender_company_id, '$msg_recipients') RETURNING id_document";
						$result = pg_query($conn, $sql);
						
						$arr = pg_fetch_assoc($result);
				
						$id_document = $arr['id_document'];
						$user_id = $_SESSION['id_user'];
				
						$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
						VALUES ($id_document, $user_id, 1)";
						pg_query($conn, $sql2);
					}
				}
				
				$cc=$imp_mail.','.$imp_admin_mail;
				$dom='1##Note saved##NewRFQ##'.$to.'##'.$subject.'##'.$created_date.'##'.$doc_filename.'##'.$id_ord_order.'##'.$cc;

			} else {
				$dom="0##Note not saved";
			}
			
		break;
		
		
		case "product_details":
		
			$id_product = $_GET['id_product'];

			$sql_stats = 'SELECT id_product, product_cas, "product_HS"
			  FROM public.product 
			WHERE id_product = '.$id_product.'';

			$result = pg_query($conn, $sql_stats);
			$row = pg_fetch_assoc($result);
			$product_cas=$row['product_cas'];
			$product_hs=$row['product_HS'];
			

			$dom = $product_cas.'##'.$product_hs;
		
		break;

		
		case "select_client":
		
			$company_id = $_GET['company_id'];
			
			$sql_stats = "SELECT id_contact, company_name, name_town, id_country, name_country, 
				   code, username, password, id_exporter, id_buyer, id_farmer, id_culture, 
				   id_supchain_type, supchain_type, id_user, id_company, name, id_town, 
				   idview, id_cooperative, downline, active
			  FROM public.v_security_new WHERE id_company = '$company_id'
			ORDER BY company_name ASC";

			$client_list = '<option value="">-- Select order client --</option>';

			$result = pg_query($conn, $sql_stats);
			while($row = pg_fetch_assoc($result)){
				$client_list .= '<option value="'. $row['id_contact'] .'">'. $row['name'] .'</option>';
			}
			
			$dom=$client_list;
			
		break;
		
		
		
		case "order_reference_nr":
		
			$id_ord_order = $_GET['id_ord_order'];
			$pipeline_id = $_GET['pipeline_id'];
			
			$id_user = $_SESSION['id_user'];
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
		
			
			if($id_ord_order != 0){
				$cond =', AND id_ord_order =' . $id_ord_order;
			} else {
				$cond ='';
			}
			
			if($pipeline_id != 0){
				$pipeline_cond =' AND ord.pipeline_id =' . $pipeline_id;
			} else {
				$pipeline_cond ='';
			}
			
			if($id_user_supchain_type==312){
				$sql = "Select ct.contact_code, ord.ord_cus_contact_id, ord.id_ord_order, ord.created_date, itm.product_code, pipeline, ord.pipeline_id, 
				Sup_reference_nr as order_number,
				((get_contact_code(ord.ord_cus_contact_id)::text || ':'::text) || (ord.customer_reference_nr::text)) AS ref_code_cus,
				((get_contact_code(ord.ord_fa_contact_id)::text || ':'::text) || (ord.fa_reference_nr::text)) ref_code_fa,
				((get_contact_code(ord.ord_imp_contact_id)::text || ':'::text) || (ord.order_nr::text)) AS ref_code_imp,
				(('SUP' || ':'::text) || (ord.sup_reference_nr::text)) AS ref_code_sup 
				from ord_order ord
				LEFT JOIN contact ct ON ct.id_contact = ord.ord_cus_contact_id 
				LEFT JOIN public.ord_order_item itm ON itm.ord_order_id = ord.id_ord_order 
				LEFT JOIN (SELECT id_regvalue, cvalue As pipeline FROM regvalues WHERE id_register=53) pline ON pline.id_regvalue = ord.pipeline_id 
				where ord_sm_person_id=$id_contact $cond $pipeline_cond And ord.status_id<=230 Order by ord.request_date DESC"; 

			} else {
				if($id_supchain_type==110){
					$sql = "Select ct.contact_code, ord.ord_cus_contact_id, ord.id_ord_order, ord.created_date, itm.product_code, pipeline, ord.pipeline_id, 
					Customer_reference_nr as order_number,
					((get_contact_code(ord.ord_cus_contact_id)::text || ':'::text) || (ord.customer_reference_nr::text)) AS ref_code_cus,
					((get_contact_code(ord.ord_fa_contact_id)::text || ':'::text) || (ord.fa_reference_nr::text)) ref_code_fa,
					((get_contact_code(ord.ord_imp_contact_id)::text || ':'::text) || (ord.order_nr::text)) AS ref_code_imp,
					(('SUP' || ':'::text) || (ord.sup_reference_nr::text)) AS ref_code_sup  
					from ord_order ord
					LEFT JOIN contact ct ON ct.id_contact = ord.ord_cus_contact_id 
					LEFT JOIN public.ord_order_item itm ON itm.ord_order_id = ord.id_ord_order 
					LEFT JOIN (SELECT id_regvalue, cvalue As pipeline FROM regvalues WHERE id_register=53) pline ON pline.id_regvalue = ord.pipeline_id 
					where ord_cus_contact_id=$id_company $cond $pipeline_cond And ord.status_id<=230 Order by ord.request_date DESC";

				} else
				if($id_supchain_type==112){
					
					if($id_company==717){
						$sql = "Select ct.contact_code, ord.ord_cus_contact_id, ord.id_ord_order, ord.created_date, itm.product_code, pipeline, ord.pipeline_id, 
						Order_nr as order_number,
						((get_contact_code(ord.ord_cus_contact_id)::text || ':'::text) || (ord.customer_reference_nr::text)) AS ref_code_cus,
						((get_contact_code(ord.ord_fa_contact_id)::text || ':'::text) || (ord.fa_reference_nr::text)) ref_code_fa,
						((get_contact_code(ord.ord_imp_contact_id)::text || ':'::text) || (ord.order_nr::text)) AS ref_code_imp,
						(('SUP' || ':'::text) || (ord.sup_reference_nr::text)) AS ref_code_sup 
						from ord_order ord
						LEFT JOIN contact ct ON ct.id_contact = ord.ord_cus_contact_id 
						LEFT JOIN public.ord_order_item itm ON itm.ord_order_id = ord.id_ord_order 
						LEFT JOIN (SELECT id_regvalue, cvalue As pipeline FROM regvalues WHERE id_register=53) pline ON pline.id_regvalue = ord.pipeline_id 
						where ord_imp_contact_id=$id_company $cond $pipeline_cond And ord.status_id<=230 Order by ord.request_date DESC"; 

					} else {
						$sql = "Select ct.contact_code, ord.ord_cus_contact_id, ord.id_ord_order, ord.created_date, itm.product_code, pipeline, ord.pipeline_id, 
						Order_nr as order_number,
						((get_contact_code(ord.ord_cus_contact_id)::text || ':'::text) || (ord.customer_reference_nr::text)) AS ref_code_cus,
						((get_contact_code(ord.ord_fa_contact_id)::text || ':'::text) || (ord.fa_reference_nr::text)) ref_code_fa,
						((get_contact_code(ord.ord_imp_contact_id)::text || ':'::text) || (ord.order_nr::text)) AS ref_code_imp,
						(('SUP' || ':'::text) || (ord.sup_reference_nr::text)) AS ref_code_sup 
						from ord_order ord
						LEFT JOIN contact ct ON ct.id_contact = ord.ord_cus_contact_id 
						LEFT JOIN public.ord_order_item itm ON itm.ord_order_id = ord.id_ord_order 
						LEFT JOIN (SELECT id_regvalue, cvalue As pipeline FROM regvalues WHERE id_register=53) pline ON pline.id_regvalue = ord.pipeline_id 
						where ord_imp_contact_id=$id_company $pipeline_cond
						Or ord_imp_contact_id in ( select id_contact from ( 
						select id_contact from ( 
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
						) q1
						where id_type=10 and id_supchain_type=112
						) q2 
						) $cond $pipeline_cond And ord.status_id<=230 Order by ord.request_date DESC"; 
					}
					
				} else
				if($id_supchain_type==113){
					$sql = " Select ct.contact_code, ord.ord_cus_contact_id, ord.id_ord_order, ord.created_date, itm.product_code, pipeline, ord.pipeline_id, 
					Sup_reference_nr as order_number,
					((get_contact_code(ord.ord_cus_contact_id)::text || ':'::text) || (ord.customer_reference_nr::text)) AS ref_code_cus,
					((get_contact_code(ord.ord_fa_contact_id)::text || ':'::text) || (ord.fa_reference_nr::text)) ref_code_fa,
					((get_contact_code(ord.ord_imp_contact_id)::text || ':'::text) || (ord.order_nr::text)) AS ref_code_imp,
					(('SUP' || ':'::text) || (ord.sup_reference_nr::text)) AS ref_code_sup 
					from ord_order ord
					LEFT JOIN contact ct ON ct.id_contact = ord.ord_cus_contact_id 
					LEFT JOIN public.ord_order_item itm ON itm.ord_order_id = ord.id_ord_order 
					LEFT JOIN (SELECT id_regvalue, cvalue As pipeline FROM regvalues WHERE id_register=53) pline ON pline.id_regvalue = ord.pipeline_id 
					where ord.id_ord_order in ( select distinct ord_order_id from ord_ocean_schedule where supplier_contact_id=$id_company ) 
					 $cond $pipeline_cond And ord.status_id<=230 Order by ord.request_date DESC";
				
				} else
				if($id_supchain_type==289){
					$sql = " Select ct.contact_code, ord.ord_cus_contact_id, ord.id_ord_order, ord.created_date, itm.product_code, pipeline, ord.pipeline_id, 
					Fa_reference_nr as order_number,
					((get_contact_code(ord.ord_cus_contact_id)::text || ':'::text) || (ord.customer_reference_nr::text)) AS ref_code_cus,
					((get_contact_code(ord.ord_fa_contact_id)::text || ':'::text) || (ord.fa_reference_nr::text)) ref_code_fa,
					((get_contact_code(ord.ord_imp_contact_id)::text || ':'::text) || (ord.order_nr::text)) AS ref_code_imp,
					(('SUP' || ':'::text) || (ord.sup_reference_nr::text)) AS ref_code_sup 
					from ord_order ord
					LEFT JOIN contact ct ON ct.id_contact = ord.ord_cus_contact_id 
					LEFT JOIN public.ord_order_item itm ON itm.ord_order_id = ord.id_ord_order
					LEFT JOIN (SELECT id_regvalue, cvalue As pipeline FROM regvalues WHERE id_register=53) pline ON pline.id_regvalue = ord.pipeline_id 
					where ord.id_ord_order in ( select distinct ord_order_id from ord_ocean_schedule where ord_fa_contact_id=$id_company ) 
					 $cond $pipeline_cond And ord.status_id<=230 Order by ord.request_date DESC"; 

				} else {}
			}
			
			$result = pg_query($conn, $sql);
		
			$reference_nr_list = '';
			while($arr = pg_fetch_assoc($result)){
				if($arr['pipeline_id']==293){
					$lbl='info';
				} else
				if($arr['pipeline_id']==294){
					$lbl='success';
				} else
				if($arr['pipeline_id']==295){
					$lbl='warning';
				} else
				if($arr['pipeline_id']==296){
					$lbl='danger';
				} else {
					$lbl='default';
				}
			
				
				$internal_reference_nr = $arr['contact_code'].'-' .$arr['order_number'].'-'.$arr['product_code'];

				$reference_nr_list .= '<li><a href="javascript:showOrderSummary(\''. $arr['id_ord_order'] .'\',\'\',\''. $arr['contact_code'] .'\',\''. $arr['product_code'] .'\',\''. $arr['order_number'] .'\',\''. $arr['pipeline_id'] .'\');" class="reference_nr">
					'. $internal_reference_nr .' 
					<span class="label label-'.$lbl.' pull-right" style="font-weight:normal">'. $arr['pipeline'] .'</span><br/>
					<span style="color:#aaa; font-size:9px;" class="pull-right">'. $arr['created_date'] .'</span>
					<small style="color:#aaa; font-size:9px;">'.$arr['ref_code_cus'].'</small><br/>
					<div class="hide">
						<small style="color:#aaa; font-size:9px;">'.$arr['ref_code_fa'].'</small><br/>
						<small style="color:#aaa; font-size:9px;">'.$arr['ref_code_imp'].'</small><br/>
						<small style="color:#aaa; font-size:9px;">'.$arr['ref_code_sup'].'</small>
					</div>
				</a></li>';
			}
			
			$dom=$reference_nr_list;  
			
		break;
		
		
		case "show_order_summary":
		
			$id_ord_order = $_GET["id_ord_order"];
			$contact_code = $_GET["contact_code"];
			$product_code = $_GET["product_code"];
			$sched_update = $_GET["sched_update"];
			$sched_create = $_GET["sched_create"];
			$edit_schedule_line = $_GET['edit_schedule_line'];

			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
			$edPiView="";
			if($id_supchain_type == 112){ 
				$edPiView='<i class="fa fa-edit hide" id="editSumPipeline"  style="cursor:pointer;" onclick="editSumPipeline('.$id_ord_order.');"></i>'; 
			}
			
			
			$sql = "SELECT * FROM public.v_order WHERE id_ord_order = '" .$id_ord_order. "'";
			
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			// Importer contact
			$list_imp_ct='';
			$sql_imp_ct = "SELECT id_contact, name FROM public.v_security_new WHERE id_supchain_type=112";
			$result_imp_ct = pg_query($conn, $sql_imp_ct);
			while($arr_imp_ct = pg_fetch_assoc($result_imp_ct)){
				if($arr['ord_imp_person_id']==$arr_imp_ct['id_contact']){ $sel_ct='selected'; }else{ $sel_ct=''; }
				$list_imp_ct.='<option value="'.$id_ord_order.'#'.$arr_imp_ct['id_contact'].'"'. $sel_ct .'>'.$arr_imp_ct['name'].'</option>';
			}
			
			// SM person
			$list_sm_manager='<option value="">-- '.$lang['CONTRACT_SEL_MANAGER'].' --</option>';
			$sql_sm_manager = "SELECT id_contact, name FROM public.contact WHERE id_supchain_type=312";
			$result_sm_manager = pg_query($conn, $sql_sm_manager);
			while($arr_sm_manager = pg_fetch_assoc($result_sm_manager)){
				if($arr['sm_person_id']==$arr_sm_manager['id_contact']){ $sel_sm='selected'; }else{ $sel_sm=''; }
				$list_sm_manager.='<option value="'.$id_ord_order.'#'.$arr_sm_manager['id_contact'].'"'. $sel_sm .'>'.$arr_sm_manager['name'].'</option>';
			}
			
			// FA Company
			$sql_freight_agent = "select * from contact where id_supchain_type=289";
			$rs_freight_agent = pg_query($conn, $sql_freight_agent);
			$list_freight_agent = '<option value="">-- '.$lang['CONTRACT_SEL_FREIGHT_AGENT'].' --</option>';
			while ($row_freight_agent = pg_fetch_assoc($rs_freight_agent)) {
				if($row_freight_agent['id_contact'] == $arr['ord_fa_contact_id']){ $sel_freight_a="selected='selected'"; } else { $sel_freight_a=""; }
				$list_freight_agent .= '<option value="'. $row_freight_agent['id_contact'] .'"'.$sel_freight_a.'>'. $row_freight_agent['name'] .'</option>';
			}
			
			// Status
			if(($arr['status_id']==246) OR
				($arr['status_id']==247) OR
				($arr['status_id']==248)){
				$status_bg_color ='bg-success';
			} else {
				$status_bg_color ='bg-danger';
			}
			
			// Pipeline
			$pipeline_id = $arr['pipeline_id'];
			if($pipeline_id==293){
				$lbl='info';
			} else
			if($pipeline_id==294){
				$lbl='success';
			} else
			if($pipeline_id==295){
				$lbl='warning';
			} else
			if($pipeline_id==296){
				$lbl='danger';
			} else {
				$lbl='default';
			}
			
			// Get last shipment number
			$sql_lship = "SELECT order_ship_nr FROM public.ord_ocean_schedule 
				WHERE ord_order_id = '" .$id_ord_order. "' 
			  ORDER By order_ship_nr DESC LIMIT 1
			";
			$result_lship = pg_query($conn, $sql_lship);
			$arr_lship = pg_fetch_assoc($result_lship);
			$order_ship_nr = $arr_lship['order_ship_nr'];
		
			// Tab status
			$exp_status = $arr['exp_status'];
			$proposal_status = $arr['proposal_status'];
			$calculate_status = $arr['calculate_status'];
			$order_status = $arr['order_status'];
			$freight_status = $arr['freight_status'];
		
			// Schedule
			$grid_list = ''; 
			$quote_list = ''; 
			$freight_list=''; 
			$quote_list_calc = '';
			
			$i_order_id = ''; 
			$i_nr_containers = ''; 
			$i_date_eta = ''; 
			$i_user_id = ''; 
			$i_modify_date = ''; 
			
			$sql_grid = " SELECT * FROM public.v_logistics_schedule WHERE ord_order_id=$id_ord_order ORDER BY order_ship_nr ASC";
			$result_grid = pg_query($conn, $sql_grid);

			$x=1;
			while($row = pg_fetch_assoc($result_grid)){
				
				if($edit_schedule_line){ $edit_line = $edit_schedule_line; } 
				else { $edit_line=NULL; }
				
				$grid_list .= '<tr class="gradeX">
					<td>
						No '.$row['order_ship_nr'].'<br/>
						<small style="color:#aeaeae; font-size:9px;">
						'.$row['ref_shipcode_cus'].'<br/>
						'.$row['ref_shipcode_sup'].'<br/>
						'.$row['ref_shipcode_fa'].'</small>
					</td>
					<td>';
						if($edit_line == $row['id_ord_schedule']){
							$grid_list .= '<input id="qty-'. $row['id_ord_schedule'] .'" style="width:120px;" name="edit_product_quantity" value="'. $row['nr_containers'] .'" type="number" min="0" class="form-control">
								<input id="wgt-'. $row['id_ord_schedule'] .'" value="'. $row['weight_container'] .'" type="hidden">';
						} else {
							$grid_list .= $row['nr_containers'];
						}
					$grid_list .= '</td>
					<td class="center">'. $row['weight_container'] .'</td>
					<td class="center">'. $row['weight_shipment'] .'</td>
					<td>'. $row['month_etd'] .'<span class="pull-right">'. $row['week_etd'] .'</span></td>
					<td>';
						if($edit_line == $row['id_ord_schedule']){
							$grid_list .= '<div class="input-group date" style="width:160px;">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" class="form-control edit_delivery_date" id="date-'. $row['id_ord_schedule'] .'" name="edit_delivery_date" value="'. $row['month_eta'] .'">
							</div>';
						} else {
							$grid_list .= $row['month_eta'];
							$grid_list .= '<span class="pull-right">'. $row['week_eta'] .'</span>';
						}	
					$grid_list .= '</td>';
					
					if($id_user_supchain_type==312){
						$order_number = $arr['order_nr'];

					} else {
						if($id_supchain_type==110){
							$order_number = $arr['customer_reference_nr'];
	
						} else
						if($id_supchain_type==112){
							$order_number = $arr['order_nr'];
							
						} else
						if($id_supchain_type==113){
							$order_number = $arr['sup_reference_nr'];
						
						} else
						if($id_supchain_type==289){
							$order_number = $arr['fa_reference_nr'];
							
						} else {}
					}
			
					$date = explode(" ", $row['month_eta']); 
					$year = explode("-", $date[0]); 
					
					if($edit_line == $row['id_ord_schedule']){
						$grid_list .='<td>
						<a href="#" onclick="saveEditScheduleLine('.$id_ord_order.','.$row['id_ord_schedule'].');" class="btn btn-white btn-sm">
						<i style="color:green;" class="fa fa-check"></i></a>
						
						<a href="#" onclick="showEditScheduleLine('.$id_ord_order.',\'\',\''. $arr['contact_code'] .'\',\''. $arr['product_code'] .'\',\''. $order_number .'\',\''. $row['pipeline_id'] .'\');" class="btn btn-white btn-sm">
						<i style="color:red;" class="fa fa-times"></i></a>';
						
						if($arr['pipeline_id']<296){
							$grid_list .='<a href="#" class="pull-right btn btn-white btn-sm" onclick="deleteShipment('.$row['id_ord_schedule'].','.$id_ord_order.');">
								<i class="fa fa-trash" aria-hidden="true"></i>
							</a>';
						}
				
					} else {
						if($sched_update==1){
							
							$id_user = $row['sched_modified_by'];
							if($id_user!=""){
								$sql_uid = " SELECT contact_code FROM public.v_security_new WHERE id_user=$id_user ";
								$result_uid = pg_query($conn, $sql_uid);
								$row_uid = pg_fetch_assoc($result_uid);
								$contact_code = $row_uid['contact_code'];
							} else {
								$contact_code ="";
							}
							
							// if($arr['pipeline_id']<296){ $editGrid=""; } else { $editGrid="disabled"; }
						
							$grid_list .='<td><button onclick="showEditScheduleLine('.$id_ord_order.','.$row['id_ord_schedule'].',\''. $arr['contact_code'] .'\',\''. $arr['product_code'] .'\',\''. $order_number .'\',\''. $row['pipeline_id'] .'\');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i></button>
							<span class="pull-right">'.$contact_code.'</span>
							</td>'; 
						} else { $grid_list .=''; }
					}
					
					$grid_list .='<td class="center">
						<a href="#" style="color:#676a6c" onclick="eMailForm(\''.$row['id_ord_schedule'].'\',\'logistics\',\'1\');">
							<i class="fa fa-envelope"></i>
						</a>
					</td>';
					
				$grid_list .='</tr>';
			
				
				$quote_num = $x.'-'.$row['nr_containers'].'-'.$row['weight_shipment'].'-'.$row['pol_code'];
				$quote_list .= '<li>
					<a href="javascript:showQuoteForm(\''. $row['id_ord_schedule'] .'\',\''. $order_ship_nr .'\',\''. $id_ord_order.'\');" class="quote_num">
						'. htmlentities($quote_num, ENT_QUOTES) .' 
						<div style="color:#aaa; font-size:9px;">'. $row['arrival_month'] .' '.$year[0].'</div>
						<small style="color:#aeaeae; font-size:9px;">
						'.$row['ref_shipcode_cus'].'<br/>
						'.$row['ref_shipcode_sup'].'<br/>
						'.$row['ref_shipcode_fa'].'</small>
					</a>
				</li>';
				
				
				if(($row['order_incoterms_id']!=263) && ($row['order_incoterms_id']!=264)){
					$freight_list .='<li>';
					
						// if($row['freight_calc']!=1){ 
							// $freight_list .='<span class="label label-primary pull-right"  onclick="cheickForFreight(\''. $row['pol_id'] .'\',\''. $row['order_pod_id'] .'\',\''. $row['order_incoterms_id'] .'\',\''. $row['package_type_id'] .'\',\''. $row['id_ord_schedule'] .'\')" style="font-weight:normal; padding:3px; margin-right:5px; cursor:pointer;">
								// <i class="fa fa-plus no-margins" style="color:white;" aria-hidden="true"></i>
							// </span>';
						// }
						
						if($row['order_ship_nr'] == $arr['nr_shipments']){
							$last_shipment=1;
						} else {
							$last_shipment=0;
						}
				
						$freight_list .='<a href="#" onclick="showFreightList(\''. $row['id_ord_schedule'] .'\',\''. $last_shipment .'\',\''. $id_ord_order.'\');" class="quote_num">
						'. htmlentities($quote_num, ENT_QUOTES).'<br/>
							<div style="color:#aaa; font-size:9px;">'. $row['arrival_month'] .' '.$year[0].'</div>
							<small style="color:#aeaeae; font-size:9px;">
							'.$row['ref_shipcode_cus'].'<br/>
							'.$row['ref_shipcode_sup'].'<br/>
							'.$row['ref_shipcode_fa'].'</small>
						</a>
					</li>';
					
				}

				
				$quote_list_calc .= '<li>
					<a href="javascript:showCalcFormAndTable(\''. $row['id_ord_schedule'] .'\',\''. $order_ship_nr .'\',\''. $row['ord_order_id'] .'\',\''. $pipeline_id .'\');" class="quote_num">
						'. htmlentities($quote_num, ENT_QUOTES) .' 
						<div style="color:#aaa; font-size:9px;">'. $row['arrival_month'] .' '.$year[0].'</div>
						<small style="color:#aeaeae; font-size:9px;">
						'.$row['ref_shipcode_cus'].'<br/>
						'.$row['ref_shipcode_sup'].'<br/>
						'.$row['ref_shipcode_fa'].'</small>
					</a>
				</li>';
				
				
				$quote_list_proposal .= '<li>
					<a href="javascript:showSalesForm(\''. $row['id_ord_schedule'] .'\',\''. $order_ship_nr .'\',\''. $row['ord_order_id'] .'\',\''. $pipeline_id .'\');" class="quote_num">
						'. htmlentities($quote_num, ENT_QUOTES) .' 
						<div style="color:#aaa; font-size:9px;">'. $row['arrival_month'] .' '.$year[0].'</div>
						<small style="color:#aeaeae; font-size:9px;">
						'.$row['ref_shipcode_cus'].'<br/>
						'.$row['ref_shipcode_sup'].'<br/>
						'.$row['ref_shipcode_fa'].'</small>
					</a>
				</li>';
				
				$quote_list_order_conf .= '<li>
					<a href="javascript:showOrderConfirm(\''. $row['id_ord_schedule'] .'\',\''. $row['ord_order_id'] .'\');" class="quote_num">
						'. htmlentities($quote_num, ENT_QUOTES) .' 
						<div style="color:#aaa; font-size:9px;">'. $row['arrival_month'] .' '.$year[0].'</div>
						<small style="color:#aeaeae; font-size:9px;">
						'.$row['ref_shipcode_cus'].'<br/>
						'.$row['ref_shipcode_sup'].'<br/>
						'.$row['ref_shipcode_fa'].'</small>
					</a>
				</li>';
				
				$i_order_id = $id_ord_order;
				$i_nr_containers = $row['nr_containers'];
				$i_date_eta = $row['month_eta'];
				$i_user_id = $_SESSION['id_user'];
				if(!empty($row['modified_date'])){
					$i_modify_date = $row['modified_date'];
				} else {
					$i_modify_date = $row['created_date'];
				}
				
				$x++;
			}

			if($arr['modify_date']!="" && $arr['modified_by_name']!=""){
				$contract_modified = '<div class="form-group">
					<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label> '. $arr['modified_by_name'] .' <br/>
					<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].': </label> '. $arr['modify_date'] .'
				</div>';
			} else {
				$contract_modified = "";
			}  
	
			$user_summary = '<div class="col-md-6">
					<label class="ord_sum_label">'.$lang['CONTRACT_CUST_NAME'].' </label> <br/>'.$arr['customer_name'].'<br/>					
					<label class="ord_sum_label">'.$lang['CONTRACT_CUST_CONRTACT'].' </label> <br/>'.$arr['customer_contact'].'<br/>					
					<label class="ord_sum_label">'.$lang['CONTRACT_CUST_REF_NUMB'].' </label> <br/>
						<span id="cusRefNumbShow">'.$arr['customer_reference_nr'].'</span>
						<div class="form-group hide" id="cusRefNumbInput">
							<input id="edit_customer_reference_nr" type="text" value="'.$arr['customer_reference_nr'].'" class="form-control" />
						</div>
						<span id="cusRefNumbManagBtn" class="hide">
							<a href="#" onclick="editCusRefNumb('.$id_ord_order.');" class="btn btn-white btn-sm">
								<i class="fa fa-edit"></i>
							</a>
						</span>
					<br/>
					<label class="ord_sum_label">'.$lang['CONTRACT_NUMB_SHIP'].' </label> <br/>'.$arr['nr_shipments'].'<br/>
					<label class="ord_sum_label">'.$lang['CONTRACT_CUST_NOTE'].' </label> <br/>
						<span id="cusNotesShow">'.$arr['notes_customer'].'</span>
						<div class="form-group hide" id="cusNotesInput">
							<textarea id="edit_notes_customer" style="height:80px;" class="form-control">'.$arr['notes_customer'].'</textarea>
						</div>
						<span id="cusNotesManagBtn" class="hide">
							<a href="#" onclick="editCusNotes('.$id_ord_order.');" class="btn btn-white btn-sm">
								<i class="fa fa-edit"></i>
							</a>
						</span>
				</div>
				
				<div class="col-md-6">
					<label class="ord_sum_label">'.$lang['CONTRACT_PRODUCT'].' </label> <br/>'.$arr['product'].'<br/>							
					<label class="ord_sum_label">'.$lang['CONTRACT_PKG_TYPE'].' </label> <br/>'.$arr['package_type'].'<br/>							
					<label class="ord_sum_label">'.$lang['CONTRACT_INCOTERMS'].' </label> <br/>'.$arr['incoterms'].' '.$arr['port_discharge'].'<br/>							
					<label class="ord_sum_label">'.$lang['CONTRACT_TT_WEIGHT'].' </label> <br/>'.$arr['total_weight'].'<br/>	
					<label class="ord_sum_label">'.$lang['CONTRACT_RQST_DATE'].' </label> <br/>'.$arr['request_date'].' / '.$arr['created_by_name'].'						
				</div>
			
				<div class="col-md-12 hide" id="sumCusRequestToggler">
					<button class="btn btn-success pull-right" onclick="showEditCusNotes();" type="button"><i class="fa fa-edit"></i></button>
				</div>';
			
			$importer_summary = '<div class="col-md-6">
					<label class="ord_sum_label">'.$lang['CONTRACT_IMP_COMP'].' </label> <br/>'.$arr['importer'].'<br/>		
					<label class="ord_sum_label">'.$lang['CONTRACT_NOTES'].' </label> <br/>
					<span id="intNotesShow">'.$arr['notes_internal'].'</span>
					<div class="form-group hide" id="intNotesInput">
						<textarea id="edit_notes_internal" style="height:80px;" class="form-control">'.$arr['notes_internal'].'</textarea>
					</div>
					<span id="intNotesManagBtn" class="hide">
						<a href="#" onclick="editIntNotes('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span>
				</div>
				
				<div class="col-md-6">
					<span class="toggle_edit"><label for="ord_imp_person_id" class="ord_sum_label">'.$lang['CONTRACT_IMP_CONTACT'].' </label> <br/> 
					<select class="normal_select" onchange="editImpPerson();" style="border:none;" name="ord_imp_person_id" id="ord_imp_person_id" disabled>
						'.$list_imp_ct.'
					</select><i class="fa fa-edit"></i></span><br/>
					
					<span class="toggle_edit"><label for="sm_person_id" class="ord_sum_label">'.$lang['CONTRACT_SORCING_MNGR'].' </label> <br/>
					<select class="normal_select" onchange="editOrderSmManager();" style="border:none;" name="sm_person_id" id="sm_person_id" disabled>
						'.$list_sm_manager.'
					</select><i class="fa fa-edit"></i></span><br/>
					
					<label class="ord_sum_label">'.$lang['CONTRACT_CRT_CTRCT_NUMB'].' </label> <br/>
					<span id="orderNrOldShow">'.$arr['order_nr_old'].'</span>
					<div class="form-group hide" id="orderNrOldInput">
						<input id="edit_order_nr_old" class="form-control" value="'.$arr['order_nr_old'].'" />
					</div>
					<span id="orderNrOldManagBtn" class="hide">
						<a href="#" onclick="editorderNrOld('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span>
				</div>
				
				<div class="col-md-12 no-padding">
					<div class="col-md-3 pull-left" style="margin-top:20px;">
						<label for="status_id" class="ord_sum_label">'.$lang['CONTRACT_STATUS'].' 
							<i class="fa fa-edit hide" id="editSumStatus" style="cursor:pointer;" onclick="editSumStatus('.$id_ord_order.','.$pipeline_id.');"></i>
						</label>
						<div class="col-md-12 p-xs b-r-sm '.$status_bg_color.'">
							<small id="sumStatusName">'.$arr['status_name'].'</small>
						</div>
					</div>
					
					<div class="col-md-3 pull-right" style="margin-top:20px;">
						<label for="status_id" class="ord_sum_label">'.$lang['CONTRACT_PIPELINE'].' '.$edPiView.'</label>
						<div class="col-md-12 p-xs b-r-sm bg-'.$lbl.'">
							<small id="sumPipelineName">'.$arr['pipeline_name'].'</small>
						</div>
					</div>
				</div>
				
				<div class="col-md-12 no-padding hide" id="sumBtnsToggler">
					<button class="btn btn-success pull-right" onclick="showSumEditBtns();" style="margin-top:10px; margin-right:20px;" type="button">
					<i class="fa fa-edit"></i></button>
			</div>';
			
			$contract_summary = '<div class="col-md-6">
				<label class="ord_sum_label">'.$lang['CONTRACT_CLT_NAME'].' </label> <br/> '.$arr['customer_name'].' <br/>	
				
				<label class="ord_sum_label">'.$lang['CONTRACT_CLT_CONTRACT_NUMB'].' </label> <br/>
				<span id="orderCusRefNrLabel">'.$arr['customer_reference_nr'].'</span>	
				<div class="form-group hide" id="orderCusRefNrInput">
					<input id="customer_reference_nr_CT" class="form-control" value="'.$arr['customer_reference_nr'].'" />
				</div>
				<span id="cusRefNumbCTManagBtn" class="hide">
					<a href="#" onclick="editCusRefNumbCT('.$id_ord_order.');" class="btn btn-white btn-sm">
						<i class="fa fa-edit"></i>
					</a>
				</span><br/>	
				
				<label class="ord_sum_label">'.$lang['CONTRACT_PRODUCT'].' </label> <br/> '.$arr['product'].' <br/>	
				<label class="ord_sum_label">'.$lang['CONTRACT_N_SHIPMENT'].' </label> <br/> '.$arr['nr_shipments'].' <br/>	
				
				<label class="ord_sum_label">'.$lang['CONTRACT_N_CONTAINER_C'].' </label> <br/> '.$arr['product_quantity'].' <br/>	  
				<label class="ord_sum_label">'.$lang['CONTRACT_TT_WEIGHT_C'].'</label> <br/> '.$arr['total_weight'].' <br/>	
			</div>
			
			<div class="col-md-6">
				<label class="ord_sum_label">'.$lang['CONTRACT_IMP_NAME'].' </label> <br/> '.$arr['importer_person'].' <br/>	
				<label class="ord_sum_label">'.$lang['CONTRACT_IMP_CONTRACT_NUMB'].' </label> <br/> '.$arr['order_nr'].' <br/>	
				
				<label class="ord_sum_label">'.$lang['CONTRACT_EXP_CONTRACT_NUMB'].' </label> <br/> 
				<span id="orderSupRefNrLabel">	'.$arr['sup_reference_nr'].' </span>	
				<div class="form-group hide" id="orderSupRefNrInput">
					<input id="sup_reference_nr_CT" class="form-control" value="'.$arr['sup_reference_nr'].'" />
				</div>
				<span id="orderSupRefNrManagBtn" class="hide">
					<a href="#" onclick="editorderSupRefNr('.$id_ord_order.');" class="btn btn-white btn-sm">
						<i class="fa fa-edit"></i>
					</a>
				</span><br/>	
				
				<label class="ord_sum_label">'.$lang['CONTRACT_FREIGHT_AGT_COMP'].' </label> <br/> 
				<span id="orderFaCompLabel"> '.$arr['fa_contact_name'].' </span>
				<div class="form-group hide" id="orderFaCompSelect">
					<select id="ord_fa_contact_id" class="form-control">
						'.$list_freight_agent.'
					</select> 
				</div>
				<span id="orderFaCompManagBtn" class="hide">
					<a href="#" onclick="editorderFaComp('.$id_ord_order.');" class="btn btn-white btn-sm">
						<i class="fa fa-edit"></i>
					</a>
				</span><br/>
				
				<label class="ord_sum_label">'.$lang['CONTRACT_FREIGHT_AGT_CONTRACT_NUMB'].' </label> <br/>
				<span id="orderFaRefNrLabel">'.$arr['fa_reference_nr'].'</span>	
				<div class="form-group hide" id="orderFaRefNrInput">
					<input id="fa_reference_nr_CT" class="form-control" value="'.$arr['fa_reference_nr'].'" />
				</div>
				<span id="orderFaRefNrManagBtn" class="hide">
					<a href="#" onclick="editorderFaRefNr('.$id_ord_order.');" class="btn btn-white btn-sm">
						<i class="fa fa-edit"></i>
					</a>
				</span><br/>	
			</div>
			
			<div class="col-md-12" style="margin-top:20px;">
				<div class="pull-left" id="contract_modified">
					'.$contract_modified.'
				</div>
				<div id="contractTabEdit" class="hide">
					<button class="btn btn-success pull-right" onclick="edit_contract(\''.$id_ord_order.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
				</div>
			</div>';
			
			if($sched_create == 1){
				$addShipment = '<button class="btn btn-primary btn-sm" '.$editGrid.' id="addShipmentBTN" onclick="addShipment(\''.$i_order_id.'\',\''.$i_nr_containers.'\',\''.$i_date_eta.'\',\''.$i_modify_date.'\')">'.$lang['CONTRACT_ADD_SHIPMENT_BTN'].'</button>';
			} else {
				$addShipment = '';
			}
		
			
			$sch_header = '<strong>'.$lang['CONTRACT_CUST_REQUEST'].'</strong> : '.$arr['product'].'- '.$arr['package_type'].'- '.$arr['incoterms'].' '.$arr['port_discharge'];

			$dom=$user_summary.'##'.$importer_summary.'##'.$grid_list.'##'.$quote_list.'##'.$freight_list.'##'.$sch_header.'##'.$quote_list_calc.'##'.$quote_list_proposal.'##'.$quote_list_order_conf.'##'.$contract_summary.'##'.$exp_status.'##'.$proposal_status.'##'.$calculate_status.'##'.$order_status.'##'.$freight_status.'##'.$addShipment;
		
		break;
		
		
		
		
		
		
		case "show_sales_form":
		
			$id_ord_schedule = $_GET['id_ord_schedule'];
			$last_order_ship_nr = $_GET['order_ship_nr'];
			$pipeline_id = $_GET['pipeline_id'];
			
			$content ='';
			
			$sql_proposal = "select 
				vp.product,
				vs.port_name AS pol,
				vs.month_etd,
				vs.week_etd,
				get_port_name(vs.pod_id) AS pod,
				vs.month_eta,
				vs.week_eta,
				vp.package_type,
				vs.nr_containers,
				vs.weight_shipment,
				vs.pipeline_id,
				vp.incoterms,
				oc.proposal_price_chf as sales_price_mt_chf,
				oc.total_price_chf,
				oc.ship_sales_value_tone,
				oc.ship_sales_value,
				oc.proposal_currency_id,
				getregvalue(oc.proposal_currency_id) currency_name,
				vp.ord_order_id,
				vp.order_ship_nr				
			from 
				ord_proposal_calc oc, v_proposal vp, v_order_schedule vs
				
				WHERE vp.id_ord_schedule=oc.ord_schedule_id 
			AND vs.id_ord_schedule=vp.id_ord_schedule
			AND vp.id_ord_schedule='$id_ord_schedule'";  
			
			$rst_proposal = pg_query($conn, $sql_proposal);
			$row = pg_fetch_assoc($rst_proposal);
			
			setlocale(LC_MONETARY, 'de_DE.UTF-8');
			
			if($row['order_ship_nr'] == $last_order_ship_nr){
				$proposal_btn = '<div class="row">
					<div class="col-sm-12">
						<button class="btn btn-success pull-left" id="create_proposal_doc_btn" onclick="proposalDoc('.$id_ord_schedule.','.$row['order_ship_nr'].','.$row['ord_order_id'].');" style="margin-top:10px;" type="button" disabled><i class="fa fa-paper-plane"></i>&nbsp;'.$lang['CONTRACT_SEND_PROPOSAL_NEGO_BTN'].'</button>
					</div>
				
					<div class="col-sm-6">
						<button class="btn btn-info" id="sales_pipeline_btn" onclick="sales_pipeline('.$id_ord_schedule.','.$row['order_ship_nr'].');" style="margin-top:10px;" type="button" disabled>'.$lang['CONTRACT_PROPOSAL_ACCEP_CUS_BTN'].'</button>
					</div>';
					
					// if($pipeline_id<296){
						$proposal_btn .= '<div class="col-sm-6"><span class="pull-right hide" id="proposal_doc_toggle">
							<button class="btn btn-success" onclick="showProposalDocBtn('.$row['ord_order_id'].','.$row['order_ship_nr'].','.$row['pipeline_id'].');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
						</span></div>';
					// }
					
				$proposal_btn .= '</div>';
				
			} else {
				$proposal_btn = '';
			}
			
			$content .='<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_PRODUCT'].'</label><br/>
							'.$row['product'].'
						</div>
						
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_POL'].'</label><br/>
							'.$row['pol'].'
						</div>
						
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_SHIPPING_WEEK'].'</label><br/>
							'.$row['month_etd'].' / '.$row['week_etd'].'
						</div>
						
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_POD'].'</label><br/>
							'.$row['pod'].'
						</div>
						
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_ARRIVAL_WEEK'].'</label><br/>
							'.$row['month_eta'].' / '.$row['week_eta'].'
						</div>
						
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_PACKAGE_TYPE'].'</label><br/>  
							'.$row['package_type'].'
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_N_CONTAINER'].'</label><br/>
							'.$row['nr_containers'].'
						</div>
						
						<div class="form-group">    
							<label class="ord_sum_label">'.$lang['CONTRACT_TT_WEIGHT'].'</label><br/>
							'.$row['weight_shipment'].'
						</div>
						
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_INCOTERMS'].'</label><br/>
							'.$row['incoterms'].'
						</div>
						
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_CURRENCY'].'</label><br/>
							'.$row['currency_name'].'
						</div>
					
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_SALES_PRICE_MT'].'</label><br/>
							'.money_format('%!n', $row['ship_sales_value']).'
						</div>
						
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_TT_SHIPMENT_PRICE'].'</label><br/>
							'.money_format('%!n', $row['ship_sales_value_tone']).'
						</div>
					</div>
					
					<div class="col-md-12">
						'.$proposal_btn.'
					</div>
				</div>
			';
		
			$dom=$content;
			
		break;
		
		
		case "sales_pipeline":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			
			$sql = "SELECT ord_order_id FROM ord_ocean_Schedule WHERE id_ord_schedule = $id_ord_schedule";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			$id_order = $arr['ord_order_id'];
			
			$sales_modified_by = $_SESSION['id_user'];
			$sales_modified_date = gmdate("Y/m/d H:i");
			
			
			if($id_order!=0 AND $id_order!=""){
				$sql_sales = "UPDATE public.ord_ocean_Schedule
				   SET sales_modified_by='$sales_modified_by', sales_modified_date='$sales_modified_date'
				WHERE id_ord_schedule=$id_ord_schedule";
				$result_sales = pg_query($conn, $sql_sales) or die(pg_last_error());
				
				
				$sql = "UPDATE public.ord_order
				   SET pipeline_id=296, order_status=1, proposal_status=1
				WHERE id_ord_order=$id_order";

				$result = pg_query($conn, $sql) or die(pg_last_error());
				$count = pg_num_rows($result);

				if($count==0){
					$dom='1#'.$id_order;
				} else {
					$dom='0#0';
				}
				
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "save_order_confirmation":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			
			$sales_modified_by = $_SESSION['id_user'];
			$sales_modified_date = gmdate("Y/m/d H:i");
			
			$sql_sales = "UPDATE public.ord_ocean_Schedule
				   SET sales_modified_by='$sales_modified_by', sales_modified_date='$sales_modified_date'
				WHERE id_ord_schedule=$id_ord_schedule";
				
			$result_sales = pg_query($conn, $sql_sales) or die(pg_last_error());
			$count = pg_num_rows($result_sales);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "save_edit_schedule":
			
			$sched_update = $_GET["sched_update"];
			$id_ord_order = $_GET["id_ord_order"];
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$order_incoterms_id = $_GET["order_incoterms_id"];

			$month_eta = $_GET['month_eta'];
			$week_eta = $_GET['week_eta'];
			
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
			$nr_containers = $_GET["nr_containers"];
			$weight_container = $_GET["weight_container"];
			$weight_shipment = $nr_containers * $weight_container;
			
			$mail=0;
			$doc="";
			
			$sql_header = "SELECT  
				v_order_schedule.id_ord_schedule,
				v_order_schedule.month_eta,
				v_order_schedule.nr_containers,
				v_order_msg.imp_mail,
				v_order_msg.imp_admin_mail,
				v_order_msg.cus_email,
				v_order_msg.cus_admin_mail,
				v_order_msg.sm_mail,
				v_order_msg.ord_imp_admin_id,
				v_order_msg.ord_imp_person_id,
				v_order_msg.ord_sm_person_id,
				v_order_schedule.nr_shipments,
				v_order_schedule.order_ship_nr,
				v_order.customer_code||'-'||v_order.order_nr||'.'||v_order_schedule.order_ship_nr as imp_reference,
				v_order.customer_code||'-'||v_order.customer_reference_nr||'.'||v_order_schedule.customer_ref_ship_nr as cus_reference,
				v_order.customer_code||'-'||v_order.sup_reference_nr||'.'||v_order_schedule.supplier_reference_nr as sup_reference,
				l.ref_code_fa,
				l.ref_code_cus,
				l.ref_code_imp,
				l.ref_code_sup
			   FROM v_order_schedule, v_order_msg, v_order, v_logistics_schedule l
			   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
			   and v_order.id_ord_order=v_order_schedule.ord_order_id 
			   and v_order_schedule.id_ord_schedule = l.id_ord_schedule
			   and v_order_schedule.id_ord_schedule = $id_ord_schedule
			";
			
			$result_header = pg_query($conn, $sql_header);
			$row_header = pg_fetch_assoc($result_header);
			
			if(!empty($row_header)){
				$ref_code_cus = $row_header['ref_code_cus'];
				$ref_code_imp = $row_header['ref_code_imp'];
				$ref_code_sup = $row_header['ref_code_sup'];
				$ref_code_fa = $row_header['ref_code_fa'];
				
				$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
				$ord_imp_person_id = $row_header['ord_imp_person_id'];
				$ord_sm_person_id = $row_header['ord_sm_person_id'];
				
				if($ref_code_cus!=""){ $no_cus = $ref_code_cus; } else { $no_cus=""; }
				if($ref_code_fa!=""){ $no_fa = ' / '.$ref_code_fa; } else { $no_fa=""; }
				if($ref_code_imp!=""){ $no_imp = ' / '.$ref_code_imp; } else { $no_imp=""; }
				if($ref_code_sup!=""){ $no_sup = ' / '.$ref_code_sup; } else { $no_sup=""; }
			
				$old_month_eta = trim($row_header['month_eta']);
				$old_nr_containers = trim($row_header['nr_containers']);
				
				if(!empty($row_header['imp_mail'])){ $imp_mail = $row_header['imp_mail'].','; }
				if(!empty($row_header['imp_admin_mail'])){ $imp_admin_mail = $row_header['imp_admin_mail'].','; }
				if(!empty($row_header['cus_email'])){ $cus_email = $row_header['cus_email'].','; }
				if(!empty($row_header['cus_admin_mail'])){ $cus_admin_mail = $row_header['cus_admin_mail'].','; }
				if(!empty($row_header['sm_mail'])){ $sm_mail = $row_header['sm_mail'].','; }

				$nr_shipments = trim($row_header['nr_shipments']);
				$imp_reference = trim($row_header['imp_reference']);
				$cus_reference = trim($row_header['cus_reference']);
				$sup_reference = trim($row_header['sup_reference']);
				
				if($old_nr_containers != $nr_containers){
					$mail=1;
				} else 
				if($old_month_eta != $week_eta){
					$mail=1;
				} else {
					$mail=0;
				}
			}
	
			
			$sched_modified_by = $_SESSION['id_user'];
			$sched_modified_date = gmdate("Y/m/d H:i");
			
			$sql = "UPDATE public.ord_ocean_schedule
			   SET month_eta='$month_eta', week_eta='$week_eta', nr_containers=$nr_containers, weight_shipment=$weight_shipment,
			   sched_modified_by=$sched_modified_by, sched_modified_date='$sched_modified_date'
			WHERE id_ord_schedule=$id_ord_schedule";

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				if($mail == 1){
					$to = "$imp_person_name <$imp_mail>";
					$subject = $imp_reference . ' Shipping Schedule Change Notification';
					
					$headers = "From: <noreply@icollect.live>\r\n";
					// $headers .= "CC: $imp_mail $imp_admin_mail $cus_email $cus_admin_mail $sm_mail\r\n"; 
					$headers .= "CC: $imp_mail $imp_admin_mail $sm_mail\r\n"; 
					$headers .= "Bcc: croth53@gmail.com\r\n"; 
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					
					$sText = '<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody><tr style="padding:0;text-align:left;vertical-align:top">
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> ETA before </strong>
								</p>
							
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> No Containers before </strong>
								</p>
							</th>
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$old_month_eta.'
								</p>
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$old_nr_containers.'
								</p>
							</th>
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> ETA now </strong>
								</p>
							
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> No. of Containers now </strong>
								</p>
							</th>
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$month_eta.'
								</p>
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$nr_containers.'
								</p>
							</th>
						</tr></tbody>
					</table>';
				
			
					$message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width"><title></title></head><body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background:#f3f3f3!important;box-sizing:border-box;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100%!important">
						<style type="text/css" align="center" class="float-center">@media only screen{html{min-height:100%;background:#ffffff}}@media only screen and (max-width:596px){.small-float-center{margin:0 auto!important;float:none!important;text-align:center!important}.small-text-center{text-align:center!important}.small-text-left{text-align:left!important}.small-text-right{text-align:right!important}}@media only screen and (max-width:596px){.hide-for-large{display:block!important;width:auto!important;overflow:visible!important;max-height:none!important;font-size:inherit!important;line-height:inherit!important}}@media only screen and (max-width:596px){table.body table.container .hide-for-large,table.body table.container .row.hide-for-large{display:table!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .callout-inner.hide-for-large{display:table-cell!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .show-for-large{display:none!important;width:0;mso-hide:all;overflow:hidden}}@media only screen and (max-width:596px){table.body img{width:auto;height:auto}table.body center{min-width:0!important}table.body .container{width:95%!important}table.body .column,table.body .columns{height:auto!important;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;padding-left:16px!important;padding-right:16px!important}table.body .column .column,table.body .column .columns,table.body .columns .column,table.body .columns .columns{padding-left:0!important;padding-right:0!important}table.body .collapse .column,table.body .collapse .columns{padding-left:0!important;padding-right:0!important}td.small-1,th.small-1{display:inline-block!important;width:8.33333%!important}td.small-2,th.small-2{display:inline-block!important;width:16.66667%!important}td.small-3,th.small-3{display:inline-block!important;width:25%!important}td.small-4,th.small-4{display:inline-block!important;width:33.33333%!important}td.small-5,th.small-5{display:inline-block!important;width:41.66667%!important}td.small-6,th.small-6{display:inline-block!important;width:50%!important}td.small-7,th.small-7{display:inline-block!important;width:58.33333%!important}td.small-8,th.small-8{display:inline-block!important;width:66.66667%!important}td.small-9,th.small-9{display:inline-block!important;width:75%!important}td.small-10,th.small-10{display:inline-block!important;width:83.33333%!important}td.small-11,th.small-11{display:inline-block!important;width:91.66667%!important}td.small-12,th.small-12{display:inline-block!important;width:100%!important}.column td.small-12,.column th.small-12,.columns td.small-12,.columns th.small-12{display:block!important;width:100%!important}table.body td.small-offset-1,table.body th.small-offset-1{margin-left:8.33333%!important;Margin-left:8.33333%!important}table.body td.small-offset-2,table.body th.small-offset-2{margin-left:16.66667%!important;Margin-left:16.66667%!important}table.body td.small-offset-3,table.body th.small-offset-3{margin-left:25%!important;Margin-left:25%!important}table.body td.small-offset-4,table.body th.small-offset-4{margin-left:33.33333%!important;Margin-left:33.33333%!important}table.body td.small-offset-5,table.body th.small-offset-5{margin-left:41.66667%!important;Margin-left:41.66667%!important}table.body td.small-offset-6,table.body th.small-offset-6{margin-left:50%!important;Margin-left:50%!important}table.body td.small-offset-7,table.body th.small-offset-7{margin-left:58.33333%!important;Margin-left:58.33333%!important}table.body td.small-offset-8,table.body th.small-offset-8{margin-left:66.66667%!important;Margin-left:66.66667%!important}table.body td.small-offset-9,table.body th.small-offset-9{margin-left:75%!important;Margin-left:75%!important}table.body td.small-offset-10,table.body th.small-offset-10{margin-left:83.33333%!important;Margin-left:83.33333%!important}table.body td.small-offset-11,table.body th.small-offset-11{margin-left:91.66667%!important;Margin-left:91.66667%!important}table.body table.columns td.expander,table.body table.columns th.expander{display:none!important}table.body .right-text-pad,table.body .text-pad-right{padding-left:10px!important}table.body .left-text-pad,table.body .text-pad-left{padding-right:10px!important}table.menu{width:100%!important}table.menu td,table.menu th{width:auto!important;display:inline-block!important}table.menu.small-vertical td,table.menu.small-vertical th,table.menu.vertical td,table.menu.vertical th{display:block!important}table.menu[align=center]{width:auto!important}table.button.small-expand,table.button.small-expanded{width:100%!important}table.button.small-expand table,table.button.small-expanded table{width:100%}table.button.small-expand table a,table.button.small-expanded table a{text-align:center!important;width:100%!important;padding-left:0!important;padding-right:0!important}table.button.small-expand center,table.button.small-expanded center{min-width:0}}</style>
						<span class="preheader" style="color:#ffffff;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
						<table class="body" style="Margin:0;background:#ffffff!important;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"><aside data-parsed="" style="min-width:580px;width:100%"><table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;padding:0;text-align:center;vertical-align:top;width:580px"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
						
						<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
						<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
						'.utf8_decode($no_cus) . utf8_decode($no_fa) . utf8_decode($no_imp) . utf8_decode($no_sup).'
						</p>
						
						<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left"><table class="row footer text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:25%"><table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
							<tbody>
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
											<tr>
												<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
													<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
												</td>
												<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
													iCRM.live Message from the iDiscover Back Office.
												</td>
											</tr>
										</table>
									</th>
								</tr>
							</tbody>
						</table>
						
						</th></tr></tbody></table>
						
						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
						<span style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
						'.$sText.'
						</span>
						
						<br/><hr>
						
						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
							Message delivered by icollect.live back office on behalf of:
						</h4>
				
						<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
							<tbody>
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
											<tbody>
												<tr style="padding:0;text-align:left;vertical-align:top">
													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Company:</strong>
														</p>
												
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Name:</strong>
														</p>
											
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Email:</strong>
														</p>
													';
													if($_SESSION['p_phone']!=""){
														$message .= '<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Phone:</strong>
														</p>';
													}
													
												$message .= '</th>
													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['company_name'].'
														</p>
												
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['name'].'
														</p>
												
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['p_email'].'
														</p>
														
													';
													if($_SESSION['p_phone']!=""){
														$message .= '<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['p_phone'].'
														</p>';
													}
												
												$message .= '</th>
												</tr>
											</tbody>
										</table>
									</th>
								</tr>
							</tbody>
						</table>
				
						<hr>
						
						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
							Before printing think about the ENVIRONMENT!<br>Warning: If you have received this email by error, please delete it and inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
						</p>
						
						</th></tr></tbody></table></th></tr></tbody></table></td></tr></tbody></table></aside></td></tr></tbody></table>
						<div style="display:none;white-space:nowrap;font:15px courier;line-height:0">&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;</div>
						</body>
					</html>';
					
					if($imp_mail==""){$imp_mail="c.roth@dev4impact.com";}
					$sender="noreply@icollect.live";
					$recipient=$imp_person_name;
				
					$mail = new PHPMailer;
					$mail->isSMTP();
					// $mail->SMTPDebug = 2;
					// $mail->SMTPSecure = 'ssl';
					$mail->Debugoutput = 'html';
					$mail->Host = "d4i.maxapex.net";
					$mail->Port = 587;
					$mail->SMTPAuth = true;
					$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
					$mail->Username = ID_USER;
					$mail->Password = ID_PASS;
					$mail->setFrom('noreply@icollect.live', $_SESSION['name']);
					$mail->AddCC($imp_mail);
					$mail->AddCC($imp_admin_mail);
					$mail->AddCC($sm_mail);
					$mail->AddBCC('croth53@gmail.com');
					$mail->addReplyTo($imp_mail, $imp_person_name);
					$mail->addAddress($imp_mail, $imp_person_name);
					$mail->Subject = $subject;
					$mail->msgHTML($message);
					$mail->AltBody = 'This is a plain-text message body';

					//send the message, check for errors
					if (!$mail->send()) {
						$save=0;
					} else {
						$save=1;
					}
					
					if($save == 1){
						$sql = "SELECT v_order.customer_code||'-'||v_order.order_nr||'.'||v_order_schedule.order_ship_nr as file_name 
						FROM v_order, v_order_schedule 
						WHERE v_order_schedule.ord_order_id=v_order.id_ord_order
						AND v_order_schedule.id_ord_schedule=$id_ord_schedule";
					
						$rst = pg_query($conn, $sql);
						$row = pg_fetch_assoc($rst);
					
						$date = date_create();
						$timestamp = date_timestamp_get($date);
						
						$file = trim($row['file_name']);
						$file_name = str_replace(' ', '_', $file);
						
						$doc_filename=$file_name.'-83-'.$timestamp.'.pdf';
					
						$email_sender_company_id = $_SESSION['id_company'];
						$created_by = $sched_modified_by;
						$id_owner = $_SESSION['id_contact'];
						$created_date = $sched_modified_date;
						
						$msg_recipients = $ord_imp_admin_id.','.$ord_imp_person_id.','.$ord_sm_person_id;
				
					
						$sql_mail = "insert into ord_document (ord_schedule_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
						email_sender_id, email_sender_company_id, msg_recipients) 
						values ($id_ord_schedule, '$doc_filename', 83, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
						$created_by, $email_sender_company_id, '$msg_recipients') RETURNING id_document";
			
						$result_mail = pg_query($conn, $sql_mail);
						if($result_mail){ 
							$doc=$doc_filename; 
						
							$arr = pg_fetch_assoc($result_mail);
							
							$id_document = $arr['id_document'];
							$user_id = $_SESSION['id_user'];
						
							$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
							VALUES ($id_document, $user_id, 1)";
							pg_query($conn, $sql2);
						}
					}
				}
				
				$dom="1##Schedule successfully updated##".$doc.'##'.$old_month_eta.'##'.$old_nr_containers;
				
			} else {
				$dom="0##Schedule not updated";
			}
			
		break;
		
		
		
		case "refresh_shipment_grid":
		
			$id_ord_order = $_GET["id_ord_order"];
			
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];

			$grid_list = ''; 
		
			$sql_grid = " SELECT * FROM public.v_order_schedule WHERE ord_order_id=$id_ord_order ORDER BY order_ship_nr ASC";
			$result_grid = pg_query($conn, $sql_grid);

			$x=1;
			while($row = pg_fetch_assoc($result_grid)){
				
				if($edit_schedule_line){ $edit_line = $edit_schedule_line; } 
				else { $edit_line=NULL; }
				
				$grid_list .= '<tr class="gradeX">
					<td>No '.$row['order_ship_nr'].'<br/>
						<small style="color:#aeaeae; font-size:9px;">
						'.$row['ref_shipcode_cus'].'<br/>
						'.$row['ref_shipcode_sup'].'<br/>
						'.$row['ref_shipcode_fa'].'</small>
					</td>
					<td> '. $row['nr_containers'] .'</td>
					<td class="center">'. $row['weight_container'] .'</td>
					<td class="center">'. $row['weight_shipment'] .'</td>
					<td>'. $row['month_etd'] .'<span class="pull-right">'. $row['week_etd'] .'</span></td>
					<td>'.$row['month_eta'].'<span class="pull-right">'. $row['week_eta'] .'</span></td>';
					
					if($id_user_supchain_type==312){
						$order_number = $arr['order_nr'];

					} else {
						if($id_supchain_type==110){
							$order_number = $arr['customer_reference_nr'];
	
						} else
						if($id_supchain_type==112){
							$order_number = $arr['order_nr'];
							
						} else
						if($id_supchain_type==113){
							$order_number = $arr['sup_reference_nr'];
						
						} else
						if($id_supchain_type==289){
							$order_number = $arr['fa_reference_nr'];
							
						} else {}
					}
			
					$id_user = $row['sched_modified_by'];
					if($id_user!=""){
						$sql_uid = " SELECT contact_code FROM public.v_security_new WHERE id_user=$id_user ";
						$result_uid = pg_query($conn, $sql_uid);
						$row_uid = pg_fetch_assoc($result_uid);
						$contact_code = $row_uid['contact_code'];
					} else {
						$contact_code ="";
					}
			
					$grid_list .='<td><a href="#" onclick="showEditScheduleLine('.$id_ord_order.','.$row['id_ord_schedule'].',\''. $arr['contact_code'] .'\',\''. $arr['product_code'] .'\',\''. $order_number .'\',\''. $row['pipeline_id'] .'\');" class="btn btn-white btn-sm">
						<i class="fa fa-edit"></i></a>
						<span class="pull-right">'.$contact_code.'</span>
					</td>';
					
					$grid_list .='<td class="center">
						<a href="#" style="color:#676a6c" onclick="eMailForm(\''.$row['id_ord_schedule'].'\',\'logistics\',\'1\');">
							<i class="fa fa-envelope"></i>
						</a>
					</td>';
					
				$grid_list .='</tr>';
			}
		
			$dom=$grid_list;
			
		break;
		
		
		case "edit_schedule_date":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$type = $_GET["type"];
			
			if($type == "ETA"){
				$month_eta = $_GET['month_eta'];
				$week_eta = $_GET['week_eta'];
				$set_values="month_eta='$month_eta', week_eta='$week_eta'";
			} else {
				$month_etd = $_GET['month_etd'];
				$week_etd = $_GET['week_etd'];
				$set_values="month_etd='$month_etd', week_etd='$week_etd'";
			}
			
			$sql = "UPDATE public.ord_ocean_schedule
			   SET $set_values
			WHERE id_ord_schedule=$id_ord_schedule";

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##Schedule date successfully updated";
			} else {
				$dom="0##Schedule date not updated";
			}
			
		break;
		
		
		case "edit_schedule_quantity":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$nr_containers = $_GET["nr_containers"];
			$weight_container = $_GET["weight_container"];
			
			$weight_shipment = $nr_containers * $weight_container;
			
			$sql = "UPDATE public.ord_ocean_schedule
			   SET nr_containers=$nr_containers, weight_shipment=$weight_shipment  
			WHERE id_ord_schedule=$id_ord_schedule";

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##Numbers of containers successfully updated";
			} else {
				$dom="0##Numbers of containers not updated";
			}
			
		break;
		
		
		
		case "update_importer_person":
		
			$id_ord_order = $_GET["id_ord_order"];
			$ord_imp_person_id = $_GET["ord_imp_person_id"];

			$modified_by = $_SESSION["id_user"];
			$modify_date = gmdate("Y/m/d H:i");
			
			$sql = "UPDATE public.ord_order
			   SET ord_imp_person_id=$ord_imp_person_id, modified_by=$modified_by, modify_date='$modify_date'
			WHERE id_ord_order=$id_ord_order";

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##Importer contact successfully updated";
			} else {
				$dom="0##Importer contact not updated";
			}
		
		break;
		
		
		case "update_sm_person":
		
			$id_ord_order = $_GET["id_ord_order"];
			$sm_person_id = $_GET["sm_person_id"];

			$modified_by = $_SESSION["id_user"];
			$modify_date = gmdate("Y/m/d H:i");
			
			$sql = "UPDATE public.ord_order
			   SET ord_sm_person_id=$sm_person_id, modified_by=$modified_by, modify_date='$modify_date'
			WHERE id_ord_order=$id_ord_order";

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##SM Manager successfully updated";
			} else {
				$dom="0##SM Manager not updated";
			}
		
		break;
		
		
		case "update_pipeline":
		
			$id_ord_order = $_GET["id_ord_order"];
			$pipeline_id = $_GET["pipeline_id"];

			$sql = "UPDATE public.ord_order
			   SET pipeline_id=$pipeline_id 
			WHERE id_ord_order=$id_ord_order";

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##Pipeline successfully updated";
			} else {
				$dom="0##Pipeline not updated";
			}
		
		break;
		
		
		case "update_order_status":
		
			$id_ord_order = $_GET["id_ord_order"];
			$status_id = $_GET["status_id"];

			$sql = "UPDATE public.ord_order
			   SET status_id=$status_id 
			WHERE id_ord_order=$id_ord_order";

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom="1##Status successfully updated";
			} else {
				$dom="0##Status not updated";
			}
		
		break;
		
		
		case "by_supplier_id_company":
		
			$id_company = $_GET["id_company"];
			if(!empty($id_company)){ $conf=" AND id_company = '$id_company'"; }
			else { $conf=''; }
			
			$sql_supplier = "SELECT id_contact, name FROM v_security_new WHERE id_supchain_type = 113 $conf ORDER BY name ASC";
			$result_supplier = pg_query($conn, $sql_supplier);
			$supplier_list='<option value="">-- Select supplier person --</option>';
			while($arr_supplier = pg_fetch_assoc($result_supplier)){ 
				if($arr['supplier_person_id']==$arr_supplier['id_contact']){
					$sel_supplier="selected";
				} else { $sel_supplier=""; }
				$supplier_list .= '<option value="'. $arr_supplier['id_contact'] .'" '. $sel_supplier .'>'. $arr_supplier['name'] .'</option>';
			}
			
			// Port of loading
			// select id_country 
			if(!empty($id_company)){
				$sql_country = "SELECT DISTINCT id_country FROM v_security_new WHERE id_country IS NOT NULL $conf";
				$rs_country = pg_query($conn, $sql_country);
				$row_country = pg_fetch_assoc($rs_country);
				$country=$row_country['id_country'];
				
				if($country){ $conf1=" AND id_country='$country'"; }
				else { $conf1=""; }
				
			} else { $conf1=""; }
			
			$sql_pol = "SELECT * FROM public.ord_towns_port WHERE port_type_id=272 $conf1";
			$rs_pol = pg_query($conn, $sql_pol);
			$pol_list = '<option value="">-- Select port of loading --</option>';
			while ($row_pol = pg_fetch_assoc($rs_pol)) {
				$pol_list .= '<option value="'.$row_pol['id_townport'] .'">'.$row_pol['portname'] .'</option>';
			}
			
			$dom=$supplier_list.'##'.$pol_list;
		
		break;
		
		
		case "show_quote_form":
		
			$update_right = $_GET["update_right"];
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$last_ship_nr = $_GET["last_ship_nr"];
			
			$sql = " SELECT 
				oc.notes_sup,
				oc.id_ord_schedule,
				oc.supplier_reference_nr,
				oc.week_etd,
				oc.month_etd,
				oc.ord_order_id,
				oc.offer_validity_date,
				o.pipeline_id,
				oc.price_sup_usd,
				oc.price_sup_eur,
				oc.order_ship_nr,
				oc.exp_modified_date,
				oc.exp_modified_contact,
				oc.price_currency_id,
				oc.pol_id,
				oc.supplier_incoterms_id,
				oc.supplier_contact_id,
				oc.supplier_person_id,
				oc.tank_provider,
				o.product_code,
				o.sup_reference_nr,
				o.customer_code,
				o.order_incoterms_id
			FROM public.v_order_schedule oc 
				LEFT JOIN public.v_order o ON o.id_ord_order = oc.ord_order_id
			WHERE oc.id_ord_schedule='$id_ord_schedule' ";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);    

			$order_number = preg_replace('/\s+/', '', $arr['customer_code']).'-'.$arr['sup_reference_nr'].'-'.$arr['product_code'];
  
			// Supplier person
			if($arr['supplier_contact_id']!=""){
				$sql_supplier = "SELECT id_contact, name FROM v_security_new WHERE id_supchain_type = 113 AND id_company =".$arr['supplier_contact_id']." ORDER BY name ASC";
			} else {
				$sql_supplier = "SELECT id_contact, name FROM v_security_new WHERE id_supchain_type = 113 ORDER BY name ASC";
			}
			
			$result_supplier = pg_query($conn, $sql_supplier);
			$supplier_list='<option value="">-- '.$lang['CONTRACT_SEL_SUP_PERSON'].' --</option>';
			while($arr_supplier = pg_fetch_assoc($result_supplier)){ 
				if($arr['supplier_person_id']==$arr_supplier['id_contact']){
					$sel_supplier="selected";
				} else { $sel_supplier=""; }
				$supplier_list .= '<option value="'. $arr_supplier['id_contact'] .'" '. $sel_supplier .'>'. $arr_supplier['name'] .'</option>';
			}
			
			$supp_contact_id = "";
			$supp_contact_name ="";
			
			// Supplier company
			$sql_supplier_comp = "SELECT DISTINCT company_name, id_company FROM v_security_new WHERE id_supchain_type = 113 ORDER BY company_name ASC";
			$result_supplier_comp = pg_query($conn, $sql_supplier_comp);
			$supplier_comp_list='<option value="">-- '.$lang['CONTRACT_SEL_SUP'].' --</option>';
			while($arr_supplier_comp = pg_fetch_assoc($result_supplier_comp)){ 
				if($arr['supplier_contact_id']==$arr_supplier_comp['id_company']){
					$supp_contact_name = $arr_supplier_comp['company_name'];
					$supp_contact_id = $arr_supplier_comp['id_company'];
					$sel_supplier_comp="selected";
				} else { $sel_supplier_comp=""; }
				$supplier_comp_list .= '<option value="'. $arr_supplier_comp['id_company'] .'"'. $sel_supplier_comp .'>'. $arr_supplier_comp['company_name'] .'</option>';
			}
			
			// Incoterms
			$sql_incoterms = "SELECT * FROM v_regvalues WHERE id_register=46 ORDER BY cvalue ASC";
			$rs_incoterms = pg_query($conn, $sql_incoterms);
			$incoterms_list = '<option value="">-- '.$lang['CONTRACT_SEL_INCOTERMS'].' --</option>';
			while ($row_incoterms = pg_fetch_assoc($rs_incoterms)) {
				if($arr['supplier_incoterms_id']==$row_incoterms['id_regvalue']){
					$sel_incoterms="selected";
				} else { $sel_incoterms=""; }
				$incoterms_list .= '<option value="'.$row_incoterms['id_regvalue'] .'"'. $sel_incoterms .'>'.$row_incoterms['cvalue'] .'</option>';
			}
			
			// Port of loading
			$sql_pol = "SELECT * FROM public.ord_towns_port WHERE port_type_id=272";
			$rs_pol = pg_query($conn, $sql_pol);
			$pol_list = '<option value="">-- '.$lang['CONTRACT_SEL_POL'].' --</option>';
			while ($row_pol = pg_fetch_assoc($rs_pol)) {
				if($arr['pol_id']!=""){
					if($arr['pol_id']==$row_pol['id_townport']){
						$sel_pol="selected";
					} else { $sel_pol=""; }
				} else {
					$sel_pol=""; 
				}
				
				$pol_list .= '<option value="'.$row_pol['id_townport'] .'"'. $sel_pol .'>'.$row_pol['portname'] .'</option>';
			}

			// Currency
			$sql_currency = "SELECT * FROM v_regvalues WHERE id_register=51 ORDER BY cvalue ASC";
			$rs_currency = pg_query($conn, $sql_currency);
			$currency_list = '<option value="">-- '.$lang['CONTRACT_SEL_CURRENCY'].' --</option>';
			while ($row_currency = pg_fetch_assoc($rs_currency)) {
				if($row_currency['id_regvalue']!=279){
					if($arr['price_currency_id']==$row_currency['id_regvalue']){
						$sel_currency="selected";
					} else { $sel_currency=""; }
					$currency_list .= '<option value="'.$row_currency['id_regvalue'] .'"'. $sel_currency .'>'.$row_currency['cvalue'] .'</option>';
				}
			}

			$mod_infos = '';
			if(($arr['exp_modified_contact']!="") && ($arr['exp_modified_date']!="")){
				$mod_infos = '<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label> '. $arr['exp_modified_contact'] .' <br/>
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].': </label> '. $arr['exp_modified_date'] .'
					</div>';
			} 
	
			if($arr['order_ship_nr'] == $last_ship_nr){ 
				// if($arr['pipeline_id']<296){
					$send_btn ='<button class="btn btn-primary" id="sendProposalBtnId" onclick="sendProposal(\''. $id_ord_schedule .'\');" style="margin-top:10px;" type="button" disabled>
						<i class="fa fa-paper-plane"></i>&nbsp;'.$lang['CONTRACT_SEND_SUP_PROPOSAL_BTN'].'
					</button>'; 
				// }
			} else { $send_btn =''; }
	
			if($arr['price_sup_eur']!=""){ $price='<input type="text" id="req_quote_price_sup_eur" value="'. $arr['price_sup_eur'] .'" class="form-control">'; }
			else{ $price='<input type="text" id="req_quote_price_sup_eur" value="'. $arr['price_sup_usd'] .'" class="form-control">'; }
			
			
			if($update_right == 1){
				$editQuote='<button class="btn btn-success" onclick="exporter_quote_editForm(\''.$id_ord_schedule.'\',\''.$last_ship_nr.'\',\''.$arr['ord_order_id'].'\',\''.$arr['pipeline_id'].'\');" type="button"><i class="fa fa-edit"></i></button>';
			} else {
				$editQuote="";
			}
			
			if($arr['offer_validity_date']==""){
				$offer_validity_date=gmdate("Y/m/d");
			} else {
				$offer_validity_date=$arr['offer_validity_date'];
			}
			
			if($_SESSION['id_user'] == 36){ 
				$supplier_contact = '<br/>'.$supp_contact_name .' 
				<input type="hidden" id="req_quote_supplier_contact_id" value="'.$supp_contact_id.'" />';
			} else
			if($supp_contact_id == 635){
				$supplier_contact = '<br/>'.$supp_contact_name .' 
				<input type="hidden" id="req_quote_supplier_contact_id" value="'.$supp_contact_id.'" />';
			} else {
				$supplier_contact = '<select id="req_quote_supplier_contact_id" onchange="bySupplierContactId(this.value);" class="form-control">
					'.$supplier_comp_list.'
				</select>';
			}
			
			$dom='<div class="row no-padding" id="exporter_quote_formContent">
				<div class="col-md-6">
					<div class="form-group">
						<label for="req_quote_supplier_contact_id" class="ord_sum_label">'.$lang['CONTRACT_SUPPLIER'].'</label>
						'.$supplier_contact.'
					</div>
					
					<div class="form-group">
						<label for="req_quote_incoterms_id" class="ord_sum_label">'.$lang['CONTRACT_INCOTERMS'].'</label>
						<select id="req_quote_incoterms_id" class="form-control">
							'.$incoterms_list.'
						</select>
					</div>
					
					<div class="form-group">
						<label for="req_quote_pol_id" class="ord_sum_label">'.$lang['CONTRACT_PORT_OF_LOADING'].'</label>
						<select id="req_quote_pol_id" onchange="transitDaysCal(this.value,\''.$arr['ord_order_id'].'\',\''.$id_ord_schedule.'\',\''.$arr['order_incoterms_id'].'\');" class="form-control">
							'.$pol_list.'
						</select>
						<span id="transDays"></span>
					</div>
					
					<div class="form-group">
						<label for="req_quote_month_etd" class="ord_sum_label">'.$lang['CONTRACT_MONTH_SHIP'].'</label>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-8 no-padding">
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" onchange="getWeekShowQuote_etd();" value="'. $arr['month_etd'] .'" id="req_quote_month_etd">
									</div>
								</div>
								
								<div class="col-md-2 no-padding pull-right">
									<input type="text" id="req_quote_week_etd" value="'. $arr['week_etd'] .'" class="form-control" disabled>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="req_quote_currency_id" class="ord_sum_label">'.$lang['CONTRACT_CURRENCY'].'</label>
						<select id="req_quote_currency_id" class="form-control">
							'.$currency_list.'
						</select>
					</div>
					
					<div class="form-group">
						<label for="area" class="ord_sum_label">'.$lang['CONTRACT_PRICE_PER_MT'].'</label>
						'.$price.'
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label for="req_quote_supplier_person_id" class="ord_sum_label">'.$lang['CONTRACT_SUPP_CONTACT_PERSON'].'</label>
						<select id="req_quote_supplier_person_id" class="form-control">
							'.$supplier_list.'
						</select>
					</div>
					
					<div class="form-group">
						<label for="area" class="ord_sum_label">'.$lang['CONTRACT_SUPP_REF_NUMB'].'</label>
						<input type="text" id="req_quote_reference_nr" value="'. $arr['supplier_reference_nr'] .'" class="form-control">
					</div>
					
					<div class="form-group">
						<label for="req_quote_sup_quote_validity" class="ord_sum_label">'.$lang['CONTRACT_QUOTE_VALID_UNTIL_DATE'].'</label>
						<div class="input-group date" style="width:160px;">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control edit_delivery_date" id="req_quote_sup_quote_validity" value="'. $offer_validity_date .'">
						</div>
					</div>
					
					<input type="hidden" class="form-control" id="id_ord_schedule" value="'. $arr['id_ord_schedule'] .'">
					<input type="hidden" class="form-control" id="req_quote_supplier_cf_date" value="'. gmdate("Y/m/d H:i") .'">
					<input type="hidden" id="req_quote_week_eta" value="" />
					<input type="hidden" id="req_quote_month_eta" value="" />
					
					<div class="form-group">
						<label for="area" class="ord_sum_label">'.$lang['CONTRACT_NOTES'].'</label>
						<textarea id="req_quote_sm_notes" style="height:34px;" class="form-control">'. $arr['notes_sup'] .'</textarea>
					</div>
					
					<div class="form-group">
						<label for="req_quote_tank_provider" class="ord_sum_label">'.$lang['CONTRACT_TANK_PROVIDER'].'</label>
						<input type="text" id="req_quote_tank_provider" value="'. $arr['tank_provider'] .'" class="form-control">
					</div>
		
					'. $mod_infos . $cf_date .'
					
				</div>
				
				<div class="col-md-12 no-padding">
					<input type="button" onclick="copyExporterQuote(\''.$id_ord_schedule.'\');" class="btn btn-info pull-left" value="'.$lang['CONTRACT_COPY_EXP_QUOTE_BTN'].'" style="margin-left:15px;" />
				</div>
			</div>
			
			<div id="exporterQuoteBtn" style="margin-top:10px;" class="pull-right">
				'.$editQuote.'
			</div>
			
			' . $send_btn . '';
		
		break;
		
		
		case "transit_days_calcul":
		
			$ord_order_id = $_GET["ord_order_id"];
			$id_townport = $_GET["id_townport"];
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$ord_incoterms_id = $_GET["order_incoterms_id"];
		
			// Exporter quote
			// $sql = "SELECT order_incoterms_id FROM public.ord_order WHERE id_ord_order = '$ord_order_id'";
			// $rs = pg_query($conn, $sql);
			// $row = pg_fetch_assoc($rs);
			// $ord_incoterms_id = $row['ord_incoterms_id'];
			
			// Transit days
			$sql_td = "SELECT transit_days FROM public.ord_towns_port WHERE id_townport = '$id_townport'";
			$rs_td = pg_query($conn, $sql_td);
			$row_td = pg_fetch_assoc($rs_td);
			$transit_days = $row_td['transit_days'];
			
			// Transit days
			$sql_mt = "SELECT month_eta, month_etd, week_etd FROM public.ord_ocean_schedule WHERE id_ord_schedule = '$id_ord_schedule'";
			$rs_mt = pg_query($conn, $sql_mt);
			$row_mt = pg_fetch_assoc($rs_mt);
			$month_etd = $row_mt['month_etd'];
			$month_eta = $row_mt['month_eta'];
			$week_etd = $row_mt['week_etd'];
	
			$month_eta_days = "";
			$month_eta_date = ""; 
			$month_etd_days = "";
			$month_etd_date = ""; 
			
			
			if((!empty($month_eta))&&(empty($month_etd))){
				$month_etd_days = $transit_days;
				$month_etd_date = date('Y/m/d', strtotime($month_eta. ' - '.$transit_days.' days'));
				
			} else
			if((!empty($month_etd))&&(empty($month_eta))){
				$month_eta_days = $transit_days;
				$month_eta_date = date('Y/m/d', strtotime($month_etd. ' + '.$transit_days.' days'));
				
			} else {
				$month_etd_days = $transit_days + 12;
				$month_etd_date = date('Y/m/d', strtotime($month_eta. ' - '.$month_etd_days.' days'));
			}
			
			$dom=$month_etd_date.'##'.$month_etd_days.'##'.$month_eta_date.'##'.$month_eta_days;
		
		break;
		
		
		case "save_exporter_quote":
		
			if(isset($_GET["req_quote_supplier_contact_id"])){
				$req_quote_supplier_contact_id = $_GET["req_quote_supplier_contact_id"];
			} else { $req_quote_supplier_contact_id = ""; }
			
			if(isset($_GET["req_quote_incoterms_id"])){
				$req_quote_incoterms_id = $_GET["req_quote_incoterms_id"];
			} else { $req_quote_incoterms_id = ""; }
			
			if(isset($_GET["req_quote_pol_id"])){
				$req_quote_pol_id = $_GET["req_quote_pol_id"];
			} else { $req_quote_pol_id = ""; }
			
			if(isset($_GET["req_quote_week_etd"])){
				$req_quote_week_etd = $_GET["req_quote_week_etd"];
			} else { $req_quote_week_etd = ""; }
			
			if(isset($_GET["req_quote_currency_id"])){
				$req_quote_currency_id = $_GET["req_quote_currency_id"];
			} else { $req_quote_currency_id = ""; }
			
			if(isset($_GET["req_quote_price_sup_eur"])){
				$req_quote_price_sup_eur = $_GET["req_quote_price_sup_eur"];
			} else { $req_quote_price_sup_eur = ""; }
			
			if(isset($_GET["req_quote_supplier_person_id"])){
				$req_quote_supplier_person_id = $_GET["req_quote_supplier_person_id"];
			} else { $req_quote_supplier_person_id = ""; }
			
			if(isset($_GET["req_quote_reference_nr"])){
				$req_quote_reference_nr = $_GET["req_quote_reference_nr"];
				$rq_ref="supplier_reference_nr='$req_quote_reference_nr',";
			} else { $req_quote_reference_nr = ""; $rq_ref=""; }
			
			if(isset($_GET["req_quote_sup_quote_validity"])){
				$req_quote_sup_quote_validity = $_GET["req_quote_sup_quote_validity"];
			} else { $req_quote_sup_quote_validity = ""; }
			
			if(isset($_GET["req_quote_supplier_cf_date"]) && isset($_GET["proposal"]) && $_GET["proposal"]==1){
				$req_quote_supplier_cf_date = $_GET["req_quote_supplier_cf_date"];
				$rq_date=" supplier_cf_date='$req_quote_supplier_cf_date',"; 
			} else { $req_quote_supplier_cf_date = ""; $rq_date=""; }
			
			if(isset($_GET["req_quote_sm_notes"])){
				$req_quote_sm_notes = $_GET["req_quote_sm_notes"];
			} else { $req_quote_sm_notes = ""; }
			
			if(isset($_GET["req_quote_week_eta"])){
				$req_quote_week_eta = $_GET["req_quote_week_eta"];
				$edit_week_eta = "week_eta='$req_quote_week_eta',";
			} else { $req_quote_week_eta = ""; $edit_week_eta = "";}
			
			if(isset($_GET["req_quote_month_etd"])){
				$req_quote_month_etd = $_GET["req_quote_month_etd"];
				$month_etd="month_etd='$req_quote_month_etd',";
				
			} else { $req_quote_month_etd = ""; $month_etd=""; }
			
			if(isset($_GET["req_quote_month_eta"])){
				$req_quote_month_eta = $_GET["req_quote_month_eta"];
				$month_eta="month_eta='$req_quote_month_eta',";				
			} else { $req_quote_month_eta = ""; $month_eta=""; }
			
			if(isset($_GET["req_quote_tank_provider"])){
				$req_quote_tank_provider = $_GET["req_quote_tank_provider"];
				$edit_tank_provider = "tank_provider='$req_quote_tank_provider',";
			} else { $req_quote_tank_provider = ""; $edit_tank_provider = "";}
	
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$id_user = $_SESSION['id_user'];
			
			
			if($req_quote_currency_id == 277){
				$req_price=" price_sup_usd='$req_quote_price_sup_eur',";
			} else
			if($req_quote_currency_id == 278){
				$req_price=" price_sup_eur='$req_quote_price_sup_eur',";
			} else {
				$req_price="";
			}
		
			$exp_modified_by = $_SESSION['id_user'];
			$exp_modified_date = gmdate("Y/m/d H:i");

			if($id_ord_schedule){

				$sql_qm = "Select qm_org_contact_id from ord_towns_port where id_townport=$req_quote_pol_id ";
				$rs_qm = pg_query($conn, $sql_qm);
				$row_qm = pg_fetch_assoc($rs_qm);
				$qm_contact_id = $row_qm['qm_org_contact_id'];
			
				if(($qm_contact_id!="")&&($id_ord_schedule!="")){
					$sql_stats = "UPDATE public.ord_ocean_schedule
					   SET supplier_contact_id='$req_quote_supplier_contact_id', supplier_incoterms_id='$req_quote_incoterms_id',
							pol_id='$req_quote_pol_id', $month_eta $month_etd price_currency_id='$req_quote_currency_id',
							$req_price $edit_tank_provider supplier_person_id='$req_quote_supplier_person_id', 
							$rq_ref $rq_date offer_validity_date='$req_quote_sup_quote_validity', $edit_week_eta
							notes_sup='$req_quote_sm_notes', week_etd='$req_quote_week_etd', modified_by='$id_user', qm_contact_id=$qm_contact_id, 
							exp_modified_by='$exp_modified_by', exp_modified_date='$exp_modified_date'
					WHERE id_ord_schedule='$id_ord_schedule'";

					$result = pg_query($conn, $sql_stats) or die(pg_last_error());
					$count = pg_num_rows($result);

					if($count==0){
						$sql_calc = 'SELECT * FROM public."CalculateSchedule"('.$id_ord_schedule.');';
						$rs_calc = pg_query($conn, $sql_calc) or die(pg_last_error());
						
						$dom=1;
					} else {
						$dom=0;
					}
					
				} else {
					$dom=0;
				}
				
			} else {
				$dom=0;
			}
			
			
		break;
		
		
		case "send_proposal":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			
			$prop_modified_by = $_SESSION['id_user'];
			$prop_modified_date = gmdate("Y/m/d H:i");
	
			// Select ord_order_id from ord_ocean_schedule
			$sql_sord = "SELECT ord_order_id, order_incoterms_id FROM public.v_order_schedule WHERE id_ord_schedule = '$id_ord_schedule'";
			$rs_sord = pg_query($conn, $sql_sord);
			$row_sord = pg_fetch_assoc($rs_sord);
			$ord_order_id = $row_sord['ord_order_id'];
			$incoterms = $row_sord['order_incoterms_id'];
			
			if($ord_order_id){
				$sql_prop = "UPDATE public.ord_ocean_schedule 
					SET prop_modified_by='$prop_modified_by', prop_modified_date='$prop_modified_date' 
				WHERE id_ord_schedule='$id_ord_schedule'";
				$rs_prop = pg_query($conn, $sql_prop) or die(pg_last_error());
				
				if(($incoterms == 264)OR($incoterms == 263)){
					$cond=', exp_status=0';
				} else {
					$cond=', exp_status=1';
				}
				
				// Update pipeline_id in ord_order
				$sql_uord = "UPDATE public.ord_order SET pipeline_id=294 $cond, freight_status=1, calculate_status=1 WHERE id_ord_order='$ord_order_id'";
				$rs_uord = pg_query($conn, $sql_uord) or die(pg_last_error());
				$count = pg_num_rows($rs_uord);
				if($count==0){
					$dom=1;
				} else {
					$dom=0;
				}
				
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "send_proposal_mail":
		
			$send = $_GET["proposal_mail"];
			$id_ord_schedule = $_GET["id_ord_schedule"];
			
			// Select ord_order_id from ord_ocean_schedule
			$sql_sord = "SELECT ord_order_id FROM public.v_order_schedule WHERE id_ord_schedule = '$id_ord_schedule'";
			$rs_sord = pg_query($conn, $sql_sord);
			$row_sord = pg_fetch_assoc($rs_sord);
			$ord_order_id = $row_sord['ord_order_id'];
			
			if($ord_order_id){
				
				// Email header
				$sql_header = "SELECT  
					v_order_schedule.id_ord_schedule, 
					v_order_schedule.supplier_name, 
					v_order_schedule.supplier_contact_id,
					v_order_schedule.person_name, 
					to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date, 
					v_order_schedule.product_code, 
					v_order_schedule.port_name, 
					v_order_schedule.incoterms, 
					v_order_schedule.offer_validity_date, 
					v_order_schedule.tank_provider, 
					v_order.notes_customer,
					get_contact_email(v_order_schedule.supplier_person_id) AS email_contact, 
					v_order.sm_person_name as sm_manager, 
					v_order_msg.ord_sm_person_id, 
					v_order_msg.ord_cus_person_id,
					v_order_msg.ord_cus_admin_id,
					v_order_msg.ord_imp_admin_id,
					v_order_msg.sm_mail, 
					v_order_msg.imp_mail, 
					v_order_msg.sup_mail, 
					v_order.customer_name,
					v_order.customer_contact,
					v_order.port_discharge, 
					v_order_msg.imp_admin_mail, 
					v_order_msg.id_ord_order, 
					v_order.customer_code||'-'||v_order.sup_reference_nr||'-'||v_order.product_code as order_number, 
					v_order.importer, 
					v_order.ord_imp_contact_id, 
					v_order.ord_cus_contact_id,  
					v_order_msg.sm_phone, 
					v_order_msg.sm_skype 
				   FROM v_order_schedule, v_order_msg, v_order 
				   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
				   and v_order.id_ord_order=v_order_schedule.ord_order_id 
				   and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id );";
				   
				$rs_header = pg_query($conn, $sql_header);
				$row_header = pg_fetch_assoc($rs_header);
			
				$supplier_contact_id = $row_header['supplier_contact_id'];
				$supplier_name = $row_header['supplier_name'];
				$person_name = $row_header['person_name'];
				$proposal_date = $row_header['proposal_date'];
				$product_code = $row_header['product_code'];
			
				$port_name = $row_header['port_name'];
				$incoterms = $row_header['incoterms'];
				$offer_validity_date = $row_header['offer_validity_date'];
				$email_contact = $row_header['email_contact'];
				
				$sm_manager = $row_header['sm_manager'];
				$sm_mail = $row_header['sm_mail'];
				$imp_mail = $row_header['imp_mail'];
				$sup_mail = $row_header['sup_mail'];
				$imp_admin_mail = $row_header['imp_admin_mail'];
				$order_number = preg_replace('/\s+/', '', $row_header['order_number']);  
				$sm_phone = $row_header['sm_phone'];
				$sm_skype = $row_header['sm_skype'];
				
				$ord_sm_person_id = $row_header['ord_sm_person_id'];
				$id_ord_order = $row_header['id_ord_order'];
				$importer = $row_header['importer'];
				$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
				$ord_cus_contact_id = $row_header['ord_cus_contact_id'];
				
				$ord_cus_person_id = $row_header['ord_cus_person_id'];
				$ord_cus_admin_id = $row_header['ord_cus_admin_id'];
				$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
				
				$customer_name = $row_header['customer_name'];
				$customer_contact = $row_header['customer_contact'];
				$port_discharge = $row_header['port_discharge'];
				$tank_provider = $row_header['tank_provider'];
			
				$notes_customer = $row_header['notes_customer'];
				if($notes_customer!=""){
					$notes='<tr style="padding:0;text-align:left;vertical-align:top">
						<td colspan="7" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">Customer Note:<br>'.$notes_sup.'</td>
					</tr>';
					
				} else {
					$notes='';
				}
			
				// Email content
				$content ='';
				$sql_content = "select order_nr||'.'|| order_ship_nr as no, 
					to_char(month_etd,'Monyy')as month_etd, 
					week_etd, 
					to_char(month_eta,'Monyy')as month_eta,
					week_eta, 
					nr_containers as no_con, 
					to_char(weight_shipment,'999G999') as weight, 
					to_char(price_sup,'999G999D99') as priceunit, 
					to_char(weight_shipment*price_sup,'999G999') as total, 
					pol_country as origin 
				from v_order_schedule where ord_order_id=$ord_order_id order by order_ship_nr::integer";
				
				$i=1;
				$rs_content = pg_query($conn, $sql_content);
				while($row_content = pg_fetch_assoc($rs_content)){
					$content .='<tr style="padding:0;text-align:left;vertical-align:top">
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['no'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['month_etd'].'/'.$row_content['week_etd'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['month_eta'].'/'.$row_content['week_eta'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['no_con'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['weight'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['priceunit'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['total'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.strtoupper($row_content['origin']).'</td>
					</tr>';
					$i++;
				}
			
				if($i>1){ $s='S'; } else { $s=''; }
				
				// Email footer
				$sql_footer = "select getregvalue(max(price_currency_id)) currency, to_char(sum(weight_shipment*price_sup),'999G999G999') as totalprice 
				from v_order_schedule where ord_order_id=$ord_order_id ";
				$rs_footer = pg_query($conn, $sql_footer);
				$row_footer = pg_fetch_assoc($rs_footer);
			
				$currency = $row_footer['currency']; 
				$total = $row_footer['totalprice'];
			
				$to = "$imp_mail";
				$subject = $order_number . ': Your Request for Proposal';

				$headers = "From: $sm_manager <noreply@icollect.live>\r\n";
				$headers .= "Reply-To: $sm_manager <$sm_mail>\r\n";
				$headers .= "CC: $imp_admin_mail, $sm_mail\r\n";
				$headers .= "Bcc: icollect.live@gmail.com\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

				if($send==1){
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
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
																									<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
																								</td>
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																									iCRM.live Message from icollect.live Back Office:<br>
																									Thank you for your inquiry. Please find our Proposal based on your request as follows:
																								</td>
																							</tr>
																						</table>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							<strong>Proposal Date</strong>: '.$proposal_date.'
																						</p>
																						
																						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
																									</td>
																								</tr>
																							</tbody>
																						</table>
																						
																						<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
																										<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Product</strong><br>'.$product_code.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Proposal valid until</strong><br>'.$offer_validity_date.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Customer</strong><br>'.$customer_name.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Contact</strong><br>'.$customer_contact.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Destination</strong><br>'.$port_discharge.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																													
																													<th class="small-12 large-6 columns last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Supplier Incoterms</strong><br>'.$incoterms.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Currency</strong><br>'.$currency.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Amount Contract</strong><br>'.$total.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Container Provider</strong><br>'.$tank_provider.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																									
																									<th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0">
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
																							<strong>SHIPMENT'.$s.'</strong>
																						</h4>
																						
																						<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Our Ref.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">ETD</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">ETA</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Cont.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">MT</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Price/MT</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">TTL</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Origin</th>
																								</tr>
																								
																								'. $content . $notes .'
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
																							Message delivered by icollect.live back office on behalf of:
																						</h4>
																						
																						<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
																										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Importer:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>SM Manager:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Email:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Phone:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Skype:</strong>
																														</p>
																													</th>
																													
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$importer.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$sm_manager.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$sm_mail.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$sm_phone.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$sm_skype.'
																														</p>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Before printing think about the ENVIRONMENT!<br>
																							Warning: If you have received this email by error, please delete it and
																							inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
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
					
					if($imp_mail==""){$imp_mail="c.roth@dev4impact.com";}
					$sender="noreply@icollect.live";
					$recipient=$imp_mail;
				
					$mail = new PHPMailer;
					$mail->isSMTP();
					// $mail->SMTPDebug = 2;
					// $mail->SMTPSecure = 'ssl';
					$mail->Debugoutput = 'html';
					$mail->Host = "d4i.maxapex.net";
					$mail->Port = 587;
					$mail->SMTPAuth = true;
					$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
					$mail->Username = ID_USER;
					$mail->Password = ID_PASS;
					$mail->setFrom('noreply@icollect.live', $sm_manager);
					$mail->AddCC($imp_admin_mail);
					$mail->AddCC($sm_mail);
					$mail->AddBCC('croth53@gmail.com');
					$mail->addReplyTo($imp_mail);
					$mail->addAddress($imp_mail);
					$mail->Subject = $subject;
					$mail->msgHTML($message);
					$mail->AltBody = 'This is a plain-text message body';
	
					//send the message, check for errors
					if (!$mail->send()) {
						$save=0;
					} else {
						$save=1;
					}
					
					if($save==1){
						// Save mail
						$msg_recipients = $ord_sm_person_id.', '.$ord_imp_contact_id.', '.$ord_imp_admin_id;
						
						$sql = "SELECT v_order.customer_code||'-'||v_order.order_nr as file_name FROM v_order WHERE id_ord_order=$ord_order_id";
						$rst = pg_query($conn, $sql);
						$row = pg_fetch_assoc($rst);
						
						$date = date_create();
						$timestamp = date_timestamp_get($date);
						
						$file = trim($row['file_name']);
						$file_name = str_replace(' ', '_', $file);
						
						$doc_filename=$file_name.'-80-'.$timestamp.'.pdf';
					
						$email_sender_company_id = $_SESSION['id_company'];
						$created_by = $_SESSION['id_user'];
						$id_owner = $_SESSION['id_contact'];
						$created_date = gmdate("Y/m/d H:i");
						
						$sql = "insert into ord_document (ord_order_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
						email_sender_id, email_sender_company_id, msg_recipients) 
						values ($ord_order_id, '$doc_filename', 80, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
						$created_by, $email_sender_company_id, '$msg_recipients') RETURNING id_document";
						$result = pg_query($conn, $sql);
						
						$arr = pg_fetch_assoc($result);
						$id_document = $arr['id_document'];
						$user_id = $_SESSION['id_user'];
					
						$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
						VALUES ($id_document, $user_id, 1)";
						pg_query($conn, $sql2);
						
						$cc=$imp_admin_mail.','.$sm_mail;
						$dom='1##'.$sm_manager.'##'.$to.'##'.$subject.'##'.$cc.'##'.$created_date.'##'.$doc_filename.'##'.$ord_order_id;
					} else {
						$dom='0##0';
					}
					
				} else {
					$message .= '<table class="body" style="Margin:0;background:#f3f3f3!important;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody>
							<tr style="padding:0;text-align:left;vertical-align:top">
								<td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
									<center data-parsed="" style="min-width:580px;width:100%">
					
										<table align="center" class="container float-center" style="Margin:0 auto;background:#fefefe;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580px">
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
														
														<table class="" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
															<tbody>
																<tr style="padding:0;text-align:left;vertical-align:top">
																	<th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
																		<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																			<tbody>
																				<tr style="padding:0;text-align:left;vertical-align:top">
																					<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
																							 Message from icollect.live Back Office.<br>
																							 Thank you for your inquiry. Please find our Proposal based on your request as follows:
																						</p>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							<strong>Proposal Date</strong> : '.$proposal_date.'
																						</p>
																						
																						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
																									</td>
																								</tr>
																							</tbody>
																						</table>
																						
																						<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
																										<table class="" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Product</strong><br>'.$product_code.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Proposal valid until</strong><br>'.$offer_validity_date.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Customer</strong><br>'.$customer_name.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Contact</strong><br>'.$customer_contact.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Destination</strong><br>'.$port_discharge.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																													
																													<th class="small-12 large-6 columns last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Supplier Incoterms</strong><br>'.$incoterms.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Currency</strong><br>'.$currency.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Amount Contract</strong><br>'.$total.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Container Provider</strong><br>'.$tank_provider.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																									
																									<th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0">
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
																							<strong>SHIPMENT'.$s.'</strong>
																						</h4>
																						
																						<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Our Ref.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">ETD</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">ETA</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Cont.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">MT</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Price/MT</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">TTL</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Origin</th>
																								</tr>
																								
																								'. $content . $notes .'
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
																							Message delivered by icollect.live back office on behalf of:
																						</h4>
																						
																						<table class="text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
																										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Importer:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>SM Manager:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Email:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Phone:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Skype:</strong>
																														</p>
																													</th>
																													
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$importer.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$sm_manager.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$sm_mail.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$sm_phone.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$sm_skype.'
																														</p>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Before printing think about the ENVIRONMENT!<br>
																							Warning: If you have received this email by error, please delete it and
																							inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
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
					</table>';
					
					$dom=$message;
				}
			}
			
		break;
		
		
		case "create_proposal_document":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$order_ship_nr = $_GET["order_ship_nr"];
			
			$prop_modified_by = $_SESSION['id_user'];
			$prop_modified_date = gmdate("Y/m/d H:i");
	
			// Select ord_order_id from ord_ocean_schedule
			$sql_sord = "SELECT ord_order_id FROM public.ord_ocean_schedule WHERE id_ord_schedule = '$id_ord_schedule'";
			$rs_sord = pg_query($conn, $sql_sord);
			$row_sord = pg_fetch_assoc($rs_sord);
			$ord_order_id = $row_sord['ord_order_id'];
	
			if($ord_order_id){
				$sql_prop = "UPDATE public.ord_ocean_schedule 
					SET prop_modified_by='$prop_modified_by', prop_modified_date='$prop_modified_date' 
				WHERE id_ord_schedule='$id_ord_schedule'";
				$rs_prop = pg_query($conn, $sql_prop) or die(pg_last_error());
				
				$dir_name = $ord_order_id . '.' . $order_ship_nr;
			
				if (!file_exists('shipping_document/'.$dir_name)) {
					mkdir('shipping_document/'.$dir_name, 0777, true);
				}
		
				if($rs_prop){
					$dom=1;
				} else {
					$dom=0;
				}
				
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "attached_port":
		
			$assign_port = $_GET["assign_port"];
			$id_townport = $_GET["id_townport"];
			
			// reg_cost in
			$sql_reg_costin = "Select * from ord_reg_cost where id_reg_cost in ( 
				select reg_cost_id from ord_port_cost_item where townport_id = $id_townport 
			) ORDER BY sequence_nr ASC";

			$reg_costin_list ='';
			$result_reg_costin = pg_query($conn, $sql_reg_costin);
			while($row_reg_costin = pg_fetch_assoc($result_reg_costin)){
				$reg_costin_list .= '<tr>
					<td>'. $row_reg_costin['item_name'] .'</td>';
					
					if($assign_port == 1){
						$reg_costin_list .= '<td><a href="#" onclick="removeCostFromPort(\''. $row_reg_costin['id_reg_cost'] .'\');"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></td>';
					}
					
				$reg_costin_list .= '</tr>';
			}
		
			$sql_port_cost = "Select * from ord_reg_cost where id_reg_cost not in ( 
				select reg_cost_id from ord_port_cost_item where townport_id = $id_townport 
			) ORDER BY sequence_nr ASC
			";

			// reg_cost not in
			$ports_reg_list ='';
			$result_port_cost = pg_query($conn, $sql_port_cost);
			while($row_port_cost = pg_fetch_assoc($result_port_cost)){
				$ports_reg_list .= '<tr>';
					if($assign_port == 1){
						$ports_reg_list .= '<td><a href="#" onclick="addToCostPort(\''. $row_port_cost['id_reg_cost'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>';
					}
					
					$ports_reg_list .= '<td>'. $row_port_cost['item_name'] .'</td>
					<td class="row_actions">
					  <a href="#" data-toggle="modal" onclick="portCostManagement(\'show\',\''. $row_port_cost['id_reg_cost'] .'\');" data-target="#"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
					  <a href=""><i class="fa fa-trash" onclick="portCostManagement(\'del\',\''. $row_port_cost['id_reg_cost'] .'\');" aria-hidden="true"></i></a>
				    </td>
				</tr>';
			}
			
			
			$dom=$reg_costin_list . '##' . $ports_reg_list;
			
		break;
		
		
		
		case "remove_cost_from_port":
		
			$id_townport = $_GET["id_townport"];
			$id_reg_cost = $_GET["id_reg_cost"];
			
			$id_role = $_GET['id_role'];

			$sql = "Delete from ord_port_cost_item where townport_id='$id_townport' AND reg_cost_id='$id_reg_cost'";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		
		
		
		
		case "save_first_freight":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$id_con_box_fr = $_GET["id_con_box_fr"];
		
			$sql_check = "SELECT id_ord_schedule_fr FROM public.ord_schedule_freight WHERE ord_ocean_schedule_id=$id_ord_schedule";
			$rst_check = pg_query($conn, $sql_check);
			$row_check = pg_fetch_assoc($rst_check);
			
			if(!empty($row_check['id_ord_schedule_fr'])){
				$id_ord_schedule_fr = $row_check['id_ord_schedule_fr'];
				
				$sql_save = "UPDATE public.ord_schedule_freight SET 
					ord_ocean_schedule_id=$id_ord_schedule, ord_con_freight_id=$id_con_box_fr, sequence_nr=1
				WHERE id_ord_schedule_fr=$id_ord_schedule_fr";
			
			} else {
				$sql_save = "Insert into ord_schedule_freight (ord_ocean_schedule_id, ord_con_freight_id, sequence_nr) 
				Values ($id_ord_schedule, $id_con_box_fr, 1)";
			}
			
			$result_save = pg_query($conn, $sql_save);

			if ($result_save) {
				
				$sql_stats = "UPDATE public.ord_ocean_schedule SET freight_calc=1 WHERE id_ord_schedule='$id_ord_schedule'";
				$rslt = pg_query($conn, $sql_stats);
				
				if($rslt){
					// Get ord_order_id
					$sql_get = "SELECT ord_order_id FROM public.ord_ocean_schedule WHERE id_ord_schedule='$id_ord_schedule'";
					$rst_get = pg_query($conn, $sql_get);
					$row_get = pg_fetch_assoc($rst_get);
					
					$ord_order_id = $row_get['ord_order_id'];
					
					$dom="1##".$row['pol_name']."##".$row['dem_pol_free']."##".$row['incoterm_name']."##".$row['pod_name']."##".$row['dem_pod_free']."##".$row['shipping_company']."##".$row['rate_valid_until']."##".$row['packaging_type_name']."##".$row['trans_delay'];
				
				} else {
					$sql_del = "delete from ord_schedule_freight where ord_ocean_schedule_id=$id_ord_schedule and ord_con_freight_id=$id_con_box_fr";
					$result_del = pg_query($conn, $sql_del);
					$dom="0##0";
				}
				
			} else {
				$dom="0##0";
			} 
			
		break;	
		
		
		case "products_exporters":
		
			$typ = $_GET["typ"];
			$update_right = $_GET["update_right"];
			$delete_right = $_GET["delete_right"];
			
			if($typ == 'exporter'){
				// Exporter list
				$sql_exp = "Select id_exporter, name_exporter, name_town from v_exporters";
			
				$element_list='';
				$result_exp = pg_query($conn, $sql_exp);
				while($row_exp = pg_fetch_assoc($result_exp)){
					$element_list .= '<tr>
						<td><input type="radio" value="'. $row_exp['id_exporter'] .'" id="radioExport'. $row_exp['id_exporter'] .'" name="id_exporter_radio" onchange="exportersProducts(\''. $row_exp['id_exporter'] .'\');" class="radioBtnExpClass"></td>
						<td style="padding:padding:10px 0 0 0;"><label for="radioExport'. $row_exp['id_exporter'] .'">'. $row_exp['name_exporter'] .'</label></td>
						<td>'. $row_exp['name_town'] .'</td>
					</tr>';
				}
				
				// Product list
				$sql_pdt = "Select id_product, product_code, product_name from product";
				
				$product_list='';
				$result_pdt = pg_query($conn, $sql_pdt);
				while($row_pdt = pg_fetch_assoc($result_pdt)){
					$product_list .= '<tr>
						<td><a href="#" onclick="addProductToExporter(\''. $row_pdt['id_product'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
						<td>'. $row_pdt['product_name'] .'</td>
						<td>'. $row_pdt['product_code'] .'</td>
					</tr>';
				}
			} else 
		
			if($typ=='client'){
				// Client list
				$sql_clt = "Select id_client, name_client, name_town from v_clients";
			
				$element_list='';
				$result_clt = pg_query($conn, $sql_clt);
				while($row_clt = pg_fetch_assoc($result_clt)){
					$element_list .= '<tr>
						<td><input type="radio" value="'. $row_clt['id_client'] .'" id="radioClient'. $row_clt['id_client'] .'" name="id_client_radio" onchange="clientsProducts(\''. $row_clt['id_client'] .'\');" class="radioBtnCltClass"></td>
						<td style="padding:10px 0 0 0;"><label for="radioClient'. $row_clt['id_client'] .'">'. $row_clt['name_client'] .'</label></td>
						<td>'. $row_clt['name_town'] .'</td>
					</tr>';
				}
				
				// Product list
				$sql_pdt = "Select id_product, product_code, product_name from product";
				
				$product_list='';
				$result_pdt = pg_query($conn, $sql_pdt);
				while($row_pdt = pg_fetch_assoc($result_pdt)){
					$product_list .= '<tr>
						<td><a href="#" onclick="addProductToClient(\''. $row_pdt['id_product'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
						<td>'. $row_pdt['product_name'] .'</td>
						<td>'. $row_pdt['product_code'] .'</td>
					</tr>';
				}
			}
			
			else {}
			
			$sql_pdts = "Select pdt.id_product, pdt.product_code, pdt.product_name, cul.name_culture from product pdt
				LEFT JOIN ( SELECT id_culture, name_culture FROM culture) cul ON cul.id_culture = pdt.id_culture
			ORDER BY pdt.id_product ASC";
			
			$products='';
			$result_pdts = pg_query($conn, $sql_pdts);
			while($row_pdts = pg_fetch_assoc($result_pdts)){
				
				$product_name = preg_replace('/\s+/', '', $row_pdts['product_name']);
				
				$products .= '<tr>
					<td>'. $row_pdts['id_product'] .'</td>
					<td>'. $row_pdts['product_code'] .'</td>
					<td>'. $product_name .'</td>
					<td>'. $row_pdts['name_culture'] .'</td>
					<td class="row_actions">';
					
						if($update_right == 1){
							$products .= '<a href="#" data-toggle="modal" onclick="productManagement(\'show\',\''. $row_pdts['id_product'] .'\',\'mod\');" data-target="#modalProduct"><i class="fa fa-pen-square"></i></a> ';
						}
						
						if($delete_right == 1){
							$products .= ' <a href="javascript:productManagement(\'del\',\''. $row_pdts['id_product'] .'\',\'\');" onclick="return confirm(\'Are you sure you want to delete '. $product_name .' ?\')"><i class="fa fa-trash"></i></a>';
						}
						
					$products .= '</td>
				</tr>';
			}

			$dom=$element_list.'##'.$product_list.'##'.$products;
			
		break;
		
		
		
		case "products_selected_exporter":
			
			$id_exporter = $_GET['id_exporter'];
			$typ = $_GET['typ'];
			$name='';
			
			if($typ=='exporter'){
				// Product attached to exporter
				$sql_product_a = "Select id_product, product_code, product_name from product Where id_product in ( 
				select product_id from contact_product where contact_id=$id_exporter)";

				$product_attached ='';
				$result_product_a = pg_query($conn, $sql_product_a);
				while($row_product_a = pg_fetch_assoc($result_product_a)){
					$product_attached .= '<tr>
						<td>'. $row_product_a['product_name'] .'</td>
						<td>'. $row_product_a['product_code'] .'</td>
						<td><a href="#" onclick="removeProductFromExporter(\''. $row_product_a['id_product'] .'\');"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></td>
					</tr>';
				}

				$sql_exp = "Select id_exporter, name_exporter, name_town from v_exporters WHERE id_exporter = '$id_exporter'";
				$result_exp = pg_query($conn, $sql_exp);
				$row_exp = pg_fetch_assoc($result_exp);

				
				// Product not attached to exporter
				$sql_product_na = "Select id_product, product_code, product_name from product Where id_product not in ( 
				select product_id from contact_product where contact_id= '$id_exporter') ";

				$product_not_attached ='';
				$result_product_na = pg_query($conn, $sql_product_na);
				while($row_product_na = pg_fetch_assoc($result_product_na)){
					$product_not_attached .= '<tr>
						<td><a href="#" onclick="addProductToExporter(\''. $row_product_na['id_product'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
						<td>'. $row_product_na['product_name'] .'</td>
						<td>'. $row_product_na['product_code'] .'</td>
					</tr>';
				}
				
				$name=$row_exp['name_exporter'];
			} else 
			
			if($typ=='client'){
				
				$id_client=$id_exporter;
				
				// Product attached to client
				$sql_product_a = "SELECT id_product, product_code, product_name  FROM product WHERE id_product 
				IN (SELECT product_id FROM contact_product WHERE contact_id =$id_client)";

				$product_attached ='';
				$result_product_a = pg_query($conn, $sql_product_a);
				while($row_product_a = pg_fetch_assoc($result_product_a)){
					$product_attached .= '<tr>
						<td>'. $row_product_a['product_name'] .'</td>
						<td>'. $row_product_a['product_code'] .'</td>
						<td><a href="#" onclick="removeProductFromClient(\''. $row_product_a['id_product'] .'\');"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></td>
					</tr>';
				}

				$sql_clt = "Select id_client, name_client, name_town from v_clients WHERE id_client = $id_client";
				$result_clt = pg_query($conn, $sql_clt);
				$row_clt = pg_fetch_assoc($result_clt);

				
				// Product not attached to client
				$sql_product_na = "SELECT id_product, product_code, product_name FROM product WHERE id_product NOT IN 
				(SELECT product_id FROM contact_product WHERE contact_id =$id_client)";

				$product_not_attached ='';
				$result_product_na = pg_query($conn, $sql_product_na);
				while($row_product_na = pg_fetch_assoc($result_product_na)){
					$product_not_attached .= '<tr>
						<td><a href="#" onclick="addProductToClient(\''. $row_product_na['id_product'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
						<td>'. $row_product_na['product_name'] .'</td>
						<td>'. $row_product_na['product_code'] .'</td>
					</tr>';
				}
				
				$name=$row_clt['name_client'];
			}
			
			else {
				$product_attached='';
				$product_not_attached='';
			}
		
			$dom = $product_attached . '##' . $name . '##' . $product_not_attached;
			
		break;
		
		
		
		case "productAttachedAndNot":

			$id_exporter = $_GET['id_exporter'];
			$typ = $_GET['typ'];
	
			if($typ=='exporter'){
				
				// Product attached to exporter
				$sql_product_a = "Select id_product, product_code, product_name from product Where id_product in ( 
				select product_id from contact_product where contact_id=$id_exporter)";

				$product_attached ='';
				$result_product_a = pg_query($conn, $sql_product_a);
				while($row_product_a = pg_fetch_assoc($result_product_a)){
					$product_attached .= '<tr>
						<td>'. $row_product_a['product_name'] .'</td>
						<td>'. $row_product_a['product_code'] .'</td>
						<td><a href="#" onclick="removeProductFromExporter(\''. $row_product_a['id_product'] .'\');"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></td>
					</tr>';
				}
				
				// Product not attached to exporter
				$sql_product_na = "Select id_product, product_code, product_name from product Where id_product not in ( 
				select product_id from contact_product where contact_id= '$id_exporter') ";

				$product_not_attached ='';
				$result_product_na = pg_query($conn, $sql_product_na);
				while($row_product_na = pg_fetch_assoc($result_product_na)){
					$product_not_attached .= '<tr>
						<td><a href="#" onclick="addProductToExporter(\''. $row_product_na['id_product'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
						<td>'. $row_product_na['product_name'] .'</td>
						<td>'. $row_product_na['product_code'] .'</td>
					</tr>';
				}
				
			} else 
	
			if($typ=='client'){
				
				$id_client = $id_exporter;
				
				// Product attached to exporter
				$sql_product_a = "Select id_product, product_code, product_name from product Where id_product in ( 
				select product_id from contact_product where contact_id=$id_client)";

				$product_attached ='';
				$result_product_a = pg_query($conn, $sql_product_a);
				while($row_product_a = pg_fetch_assoc($result_product_a)){
					$product_attached .= '<tr>
						<td>'. $row_product_a['product_name'] .'</td>
						<td>'. $row_product_a['product_code'] .'</td>
						<td><a href="#" onclick="removeProductFromClient(\''. $row_product_a['id_product'] .'\');"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></td>
					</tr>';
				}
				
				// Product not attached to exporter
				$sql_product_na = "Select id_product, product_code, product_name from product Where id_product not in ( 
				select product_id from contact_product where contact_id= '$id_client') ";

				$product_not_attached ='';
				$result_product_na = pg_query($conn, $sql_product_na);
				while($row_product_na = pg_fetch_assoc($result_product_na)){
					$product_not_attached .= '<tr>
						<td><a href="#" onclick="addProductToClient(\''. $row_product_na['id_product'] .'\');"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></td>
						<td>'. $row_product_na['product_name'] .'</td>
						<td>'. $row_product_na['product_code'] .'</td>
					</tr>';
				}
			}
			
			else {}
			
			
			$dom = $product_attached . '##' . $product_not_attached;

		break;
		
		
		
		case "add_product_to_exporter":

			$contact_id = $_GET['contact_id'];
			$id_product = $_GET['id_product'];

			$sql = "Insert into contact_product ( contact_id, product_id ) values ( $contact_id, $id_product ) ";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		
		case "remove_product_from_exporter":

			$contact_id = $_GET['contact_id'];
			$id_product = $_GET['id_product'];

			$sql = "delete from contact_product where contact_id=$contact_id and product_id=$id_product";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		case "show_order_confirmation":
		
			$ord_order_id = $_GET['ord_order_id'];
			$id_ord_schedule = $_GET['id_ord_schedule'];
			
			$sql = "SELECT 
				get_contact_name(os.ord_cus_contact_id) as client_name, 
				os.customer_reference_nr, 
				os.customer_ref_ship_nr,
				os.product_code,
				os.pol_id,
				os.pod_id,
				get_port_name(os.pol_id) pol_name,
                get_port_name(os.pod_id) pod_name,
				os.month_etd,
				os.week_etd,
				os.month_eta, 
				os.week_eta, 
				os.weight_shipment, 
				get_contact_name(os.ord_imp_contact_id) as importer_name,
				os.ord_order_id,
				os.order_ship_nr,
				oc.proposal_currency_id,
				oc.ship_sales_value_tone,
				oc.ship_sales_value, 
				os.fa_reference_nr,
				os.supplier_reference_nr,
				os.sales_modified_contact,
				os.sales_modified_date,
				os.cus_incoterms_id,
				vo.order_nr,
				vo.nr_shipments,
				vo.incoterms
			  from v_order_schedule os, ord_proposal_calc oc, v_order vo 
			  where oc.ord_schedule_id=os.id_ord_schedule
			  and oc.order_id=vo.id_ord_order 
			and oc.ord_schedule_id=".$id_ord_schedule."";

			$rs = pg_query($conn, $sql);
			$row = pg_fetch_assoc($rs);

			setlocale(LC_MONETARY, 'de_DE.UTF-8');
			
			$sql_nbSup = "select count(distinct supplier_contact_id) As num_sup from ord_ocean_Schedule where ord_ocean_schedule.ord_order_id=$ord_order_id";
			$rs_nbSup = pg_query($conn, $sql_nbSup);
			$row_nbSup = pg_fetch_assoc($rs_nbSup);
			
			$po_to_sup = '';
			if($row_nbSup['num_sup']==2){
				$sql_supId = "select distinct supplier_contact_id, get_contact_name(supplier_contact_id) supplier_name from ord_ocean_Schedule where ord_ocean_schedule.ord_order_id=$ord_order_id";
				$rs_supId = pg_query($conn, $sql_supId);
				
				$x=1;
				while($row_supId = pg_fetch_assoc($rs_supId)){
					$po_to_sup .= '<div class="col-md-10 no-padding">
						<button class="btn btn-primary pull-left" id="puchase_order_supp_btn'.$x.'" disabled onclick="puchase_order_supp('.$ord_order_id.','.$row['cus_incoterms_id'].','.$id_ord_schedule.','.$row_supId['supplier_contact_id'].');" style="margin-top:10px;" type="button">'.$lang['CONTRACT_CLT_PO_SUP_BTN'].' ('.$row_supId['supplier_name'].')</button> 
					</div>';
					$x++;
				}
				
			} else {
				$po_to_sup .= '<div class="col-md-10 no-padding"><button class="btn btn-primary pull-left" id="puchase_order_supp_btn" disabled onclick="puchase_order_supp('.$ord_order_id.','.$row['cus_incoterms_id'].','.$id_ord_schedule.',\'\');" style="margin-top:10px;" type="button">'.$lang['CONTRACT_CLT_PO_SUP_BTN'].'</button> </div>';
			}
			
			$sql_last_ship = "select max(id_ord_schedule) As last_id from ord_ocean_schedule where ord_order_id = $ord_order_id";
			$rs_last_ship = pg_query($conn, $sql_last_ship);
			$row_last_ship = pg_fetch_assoc($rs_last_ship);
		
			if($id_ord_schedule == $row_last_ship['last_id']){
				// $btn = '<button class="btn btn-success pull-left" id="offer_accepted_btn" onclick="sales_pipeline('.$id_ord_schedule.');" style="margin-top:10px;" type="button" disabled><i class="fa fa-check"></i>&nbsp;Offer accepted</button>';
				$btn = '<div class="col-md-10 no-padding"><button class="btn btn-info pull-left" id="create_contract_btn" disabled onclick="create_contract('.$ord_order_id.','.$row['cus_incoterms_id'].');" style="margin-top:10px;" type="button">'.$lang['CONTRACT_SALES_CONTRACT_BTN'].'</button> </div>
				<div class="col-md-10 no-padding"><button class="btn btn-primary pull-left" id="puchase_order_fa_btn" disabled onclick="puchase_order('.$ord_order_id.','.$row['cus_incoterms_id'].','.$id_ord_schedule.');" style="margin-top:10px;" type="button">'.$lang['CONTRACT_CLT_PO_FA_BTN'].'</button> </div>
				' . $po_to_sup;
			} else {
				$btn = '';
			}
		
			if(($row['sales_modified_contact']!="")&&($row['sales_modified_date']!="")){
				$mod='<div class="form-group" style="margin-top:10px;">
					<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label>'.$row['sales_modified_contact'].'<br/>
					<label class="ord_sum_label"> '.$lang['CONTRACT_MODIFIED_DATE'].': </label>'.$row['sales_modified_date'].'
				</div>';
				
			} else { $mod=''; }
			
			
			$content .='<div class="row" style="background:#f5f5f5;">
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_CLT_NAME'].'</label><br/>
						'.$row['client_name'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_CONTRACT_NUMB'].'</label><br/>
						'.$row['customer_reference_nr'].'
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_IMP_NAME'].'</label><br/>
						'.$row['importer_name'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_CONTRACT_NUMB'].'</label><br/>
						'.$row['order_nr'].'
					</div>
				</div>
			</div>
			
			<div class="row">			
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_PO_NUMB'].'</label><br/>
						<span id="cusRefShipNrShow">'.$row['customer_ref_ship_nr'].'</span>
						<div class="form-group hide" id="cusRefShipNrInput">
							<input id="customer_ref_ship_nr" class="form-control" value="'.$row['customer_ref_ship_nr'].'" />
						</div>
						<span id="cusRefShipNrManagBtn" class="hide">
							<a href="#" onclick="editCusRefShipNr('.$id_ord_schedule.');" class="btn btn-white btn-sm">
								<i class="fa fa-edit"></i>
							</a>
						</span>
					</div>
	
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_PRODUCT'].'</label><br/>
						'.$row['product_code'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_INCOTERMS'].'</label><br/>
						'.$row['incoterms'].'
					</div>
			
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_FROM'].'</label><br/>
						'.$row['pol_name'].'
					</div>
			
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_TO'].'</label><br/>
						'.$row['pod_name'].'
					</div>
			
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_ETD'].'</label><br/>
						'.$row['month_etd'].' / '.$row['week_etd'].'
					</div>
			
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_ETA'].'</label><br/>
						'.$row['month_eta'].' / '.$row['week_eta'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_TT_WEIGHT'].'</label><br/>
						'.$row['weight_shipment'].'
					</div>
					
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_SHIPMENT_NUMB'].'</label><br/>
						'.$row['order_ship_nr'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_CURRENCY'].'</label><br/>
						'.$row['proposal_currency_id'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_SALES_PER_MT'].'</label><br/>
						'.money_format('%!n', $row['ship_sales_value_tone']).'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_TT_SHIPMENT_PRICE'].'</label><br/>
						'.money_format('%!n', $row['ship_sales_value']).'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_FREIGHT_AGT_PO_N'].'</label><br/>
						<span id="faRefNrShow">'.$row['fa_reference_nr'].'</span>
						<div class="form-group hide" id="faRefNrInput">
							<input id="fa_reference_nr_OC" class="form-control" value="'.$row['fa_reference_nr'].'" />
						</div>
						<span id="faRefNrManagBtn" class="hide">
							<a href="#" onclick="editfaRefNr('.$id_ord_schedule.');" class="btn btn-white btn-sm">
								<i class="fa fa-edit"></i>
							</a>
						</span>
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_EXP_PO_N'].'</label><br/>
						<span id="supRefNrShow">'.$row['supplier_reference_nr'].'</span>
						<div class="form-group hide" id="supRefNrInput">
							<input id="supplier_reference_nr" class="form-control" value="'.$row['supplier_reference_nr'].'" />
						</div>
						<span id="supRefNrManagBtn" class="hide">
							<a href="#" onclick="editsupRefNr('.$id_ord_schedule.');" class="btn btn-white btn-sm">
								<i class="fa fa-edit"></i>
							</a>
						</span>
					</div>
					
					'.$mod.'
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					'. $btn .'
					<span id="showhideOrdConfirmEditBtn">
						<button class="btn btn-success pull-right" onclick="edit_order_confirmation('.$id_ord_schedule.');" style="margin-top:10px;" type="button">
						<i class="fa fa-edit"></i></button>
					</span>
				</div>
			</div>';
			
			$dom=$content;
		
		break;
		
		
		case "show_calc_form_and_table":
		
			$update_right = $_GET['update_right'];
			$order_ship_nr = $_GET['order_ship_nr'];
			$id_ord_schedule = $_GET['id_ord_schedule'];
			$pipeline_id = $_GET['pipeline_id'];
			$content ='';
			
			$sql_form = "select oc.id_proposal_calc, os.pol_id, 
				get_port_name(os.pol_id) pol_name, 
				os.pod_id, os.id_ord_schedule,
				get_port_name(os.pod_id) pod_name, 
				getregvalue(os.cus_incoterms_id) cus_incoterms, 
				oo.order_nr, 
				oo.nr_shipments, 
				os.nr_containers, 
				os.weight_container, 
				os.weight_shipment, 
				oc.usd_chf_exc_rate, 
				oc.eur_chf_exc_rate, 
				oc.usd_eur_exc_rate, 
				oc.ship_sales_value_tone, 
				oc.ship_sales_value, 
				oc.ship_cost_tone, 
				oc.ship_tts, 
				oc.ship_ttc, 
				oo.order_status,
				oc.proposal_currency_id,
				oc.margin_per_ton as margin_mt, 
				os.ord_order_id,
				oc.proposal_price_chf as sales_price_mt_chf,
				oc.exch_datetime,
				oc.ship_sales_surcharge,
				oc.ship_sales_surcharge_amount
			from 
				ord_proposal_calc oc, ord_ocean_Schedule os, 
				ord_order oo 
				where oo.id_ord_order=os.ord_order_id 
				and os.id_ord_schedule=oc.ord_schedule_id 
			AND os.id_ord_schedule='$id_ord_schedule'";  
			
			$rst_form = pg_query($conn, $sql_form);
			$row_form = pg_fetch_assoc($rst_form);
			
			// $ship_sales_value_tone = $row_form['ship_sales_value_tone'];
			// $margin_mt = $row_form['margin_mt'];
			
			
			if($row_form['nr_shipments'] == $order_ship_nr){
				$last_shipment=1;
			} else {
				$last_shipment=0;
			}
		
			if($row_form['exch_datetime']!=""){
				$exch_datetime='Open Exchange Rates @ '.$row_form['exch_datetime'];
			} else {
				$exch_datetime='';
			}
			
			$sql = "SELECT calc_modified_date, calc_modified_contact FROM public.v_order_schedule WHERE id_ord_schedule = $id_ord_schedule";
			$rst = pg_query($conn, $sql);
			
			$mod_infos = '';
			if($rst){
				$row = pg_fetch_assoc($rst);
				$mod_infos = '<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].'</label>: '.$row['calc_modified_contact'].' <label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].'</label>: '.$row['calc_modified_date'];
			}
			
			$sql_currency = 'SELECT * FROM v_regvalues WHERE id_register=51 ORDER BY cvalue ASC';
			$rs_currency = pg_query($conn, $sql_currency);

			$currency_list = '<option value="">-- '.$lang['CONTRACT_SEL_CURRENCY'].' --</option>';
			while ($row_currency = pg_fetch_assoc($rs_currency)) {
				if($row_currency['id_regvalue'] == $row_form['proposal_currency_id']){ $sel_cur = 'selected'; }else{ $sel_cur = ''; }
				$currency_list .= '<option value="'.$row_currency['id_regvalue'] .'"'.$sel_cur.'>'.$row_currency['cvalue'] .'</option>';
			}

			setlocale(LC_MONETARY, 'de_DE.UTF-8');
			$id_proposal_calc = $row_form['id_proposal_calc'];
			
			$content .='<div class="row" style="padding:10px 5px 5px 5px; margin-bottom:20px; background:#f5f5f5;" id="calcVariableBloc">
					<div class="col-md-12">
						<input type="button" onclick="saveCalcVariables(\''.$id_proposal_calc.'\',\''.$row_form['ord_order_id'].'\');" class="btn btn-info pull-left" value="'.$lang['CONTRACT_COPY_VARIABLES_BTN'].'" />
						<input type="button" onclick="calculateAll(\''.$id_ord_schedule.'\');" class="btn btn-info pull-left" value="'.$lang['CONTRACT_RECALCULATE_ALL_BTN'].'" style="margin-left:15px;" />
						<input type="button" onclick="getCurrency(\''.$id_proposal_calc.'\');" class="btn btn-info pull-left" value="'.$lang['CONTRACT_GET_CURRENCY_BTN'].'" style="margin-left:15px;" />
						<span id="show_saveCalcVariables">';
						
						if($update_right == 1){
							$content .='<button onclick="editCalcVariables(\''.$id_ord_schedule.'\',\''.$row_form['order_status'].'\');" class="btn btn-success pull-right"><i class="fa fa-edit"></i></button>';
						}
						
						if($pipeline_id<296){
							if($update_right == 1){
								// $content .='<button onclick="editCalcVariables(\''.$id_ord_schedule.'\',\''.$row_form['order_status'].'\');" class="btn btn-success pull-right"><i class="fa fa-edit"></i></button>';
							}
							$active='disabled';
						} else {
							$active='';
						}
						
						$content .='</span>
					</div>
					
					<div class="row no-margins">
						<div class="col-md-4">
							<div class="form-group">
								<label class="ord_sum_label">'.$lang['CONTRACT_CALCULATE_IN'].'</label><br/>
								<select id="saveIdCurrency" class="form-control">
									'.$currency_list.'
								</select>
							</div>
						</div>
						
						<div class="pull-right" style="padding-right:15px;">
							<div style="padding-top:30px;">
								<input type="button" onclick="addSurchage('.$id_proposal_calc.');" class="btn btn-primary pull-right" value="Add" />
							</div>
						</div>
						
						<div class="col-md-2 pull-right">
							<div class="form-group">
								<label class="ord_sum_label">Amount</label><br/>
								<input type="text" id="calcAmount" value="'.number_format($row_form['ship_sales_surcharge_amount'], 2, '.', '').'" onchange="calcSurchargePerctage();" class="form-control">
							</div>
						</div>
						
						<div class="col-md-2 pull-right">
							<div class="form-group">
								<label class="ord_sum_label">Percentage</label><br/>
								<input type="text" id="calcPercentage" value="'.number_format($row_form['ship_sales_surcharge'], 2, '.', '').'" onchange="calcSurchargePerctage();" class="form-control">
							</div>
						</div>
						
						<div class="col-md-2 pull-right" style="padding-top:35px;">
							Surcharge
						</div>
					</div>	
					
					<input type="hidden" id="calcSalesMt" value="'.$row_form['ship_sales_value_tone'].'" />	
					
					<hr style="margin-top:5px; margin-bottom:5px;" />
				
					<div class="row no-margins">	
						<div class="col-md-2">
							<div class="form-group">
								<label class="ord_sum_label">US$/CHF</label><br/>
								<input type="text" id="saveIdUsdChf" value="'.number_format($row_form['usd_chf_exc_rate'], 2, '.', '').'" class="form-control">
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
								<label class="ord_sum_label">US$/EUR</label><br/>
								<input type="text" id="saveIdUsdEur" value="'.number_format($row_form['usd_eur_exc_rate'], 2, '.', '').'" class="form-control">
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
								<label class="ord_sum_label">EUR/CHF</label><br/>
								<input type="text" id="saveIdEurChf" value="'.number_format($row_form['eur_chf_exc_rate'], 2, '.', '').'" class="form-control">
							</div>
						</div>
						
						<div class="col-md-2 pull-right">
							<div class="form-group">
								<label class="ord_sum_label">'.$lang['CONTRACT_MARGIN_MT'].'</label><br/>
								<input type="text" id="saveIdMargin" value="'.$row_form['margin_mt'].'" class="form-control">
							</div>
						</div>
					</div>
					
					<div class="row no-margins">
						<div class="col-md-7">
							<label class="ord_sum_label" id="saveIdTimeStampExcRt">
								'.$exch_datetime.'
							</label>
						</div>
						
						<div class="col-md-2">
							<div class="hide" id="edSalesMT">
								<a href="#" onclick="reduiceSP(\''.$id_proposal_calc.'\',\''.$row_form['ship_sales_value_tone'].'\',\''.$row_form['margin_mt'].'\',\''.$id_ord_schedule.'\');" style="color:red;"><i class="fa fa-minus"></i></a>
								&nbsp;&nbsp;<a href="#" onclick="increaseSP(\''.$id_proposal_calc.'\',\''.$row_form['ship_sales_value_tone'].'\',\''.$row_form['margin_mt'].'\',\''.$id_ord_schedule.'\');" style="color:green;"><i class="fa fa-plus"></i></a>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<input type="number" id="salesMTnewVal" value="" class="form-control hide" />
							</div>
						</div>	
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-2" style="width:19%;">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_TOTAL_SERVICES'].'</label><br/>
							'. money_format('%!n', $row_form['ship_tts']) .'
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_SERVICES_MT'].'</label><br/>
							'. money_format('%!n', $row_form['ship_cost_tone']) .'
						</div>
					</div>
			
					<div class="col-md-2">
						<div class="form-group">
							<label class="ord_sum_label"> '.$lang['CONTRACT_TOTAL_COAST'].' </label><br/>
							'. money_format('%!n', $row_form['ship_ttc']) .'
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_TOTAL_SALES'].'</label><br/>
							'. money_format('%!n', $row_form['ship_sales_value']) .'
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_SALES_MT'].'</label><br/>
							'. money_format('%!n', $row_form['ship_sales_value_tone']) .'
						</div>
					</div>
				</div>
	
			<div class="row">
				<div class="col-md-12">
					'.$mod_infos.'
				</div>
			</div>';
			
			
			if($id_proposal_calc){
				$sql_table = "select id_ord_calc_item, ord_proposal_id, 
					sequence_nr, item_name, getregvalue(measure_unit) measure_unit, 
					cost_usd, cost_eur, cost_chf, active 
				from ord_proposal_item 
					WHERE ord_proposal_id='$id_proposal_calc'
					order by sequence_nr 
				";
				
				$grid_list ='';
			
				$rst_table = pg_query($conn, $sql_table);
				while($row_table = pg_fetch_assoc($rst_table)){
					if($row_table['active']=='t'){
						$check='checked';
					} else { $check=''; }
					
					setlocale(LC_MONETARY, 'en_US');
					$cost_usd = money_format('%!n', $row_table['cost_usd']);
					// $cost_usd = $row_table['cost_usd'];
					
					setlocale(LC_MONETARY, 'de_DE.UTF-8');
					$cost_eur = money_format('%!n', $row_table['cost_eur']);
					$cost_chf = money_format('%!n', $row_table['cost_chf']);
				
					// $cost_eur = $row_table['cost_eur'];
					// $cost_chf = $row_table['cost_chf'];
					
					$grid_list .='<tr class="gradeX">
						<td>'. $row_table['sequence_nr'] .'</td>
						<td>'. $row_table['item_name'] .'</td>
						<td>'. $row_table['measure_unit'] .'</td>
						<td class="center">'. $cost_usd .'</td>
						<td class="center">'. $cost_eur .'</td>
						<td class="center">'. $cost_chf .'</td>
						<td><input type="checkbox" class="i-checks" '.$active.' value="'. $row_table['active'] .'#'.$row_table['id_ord_calc_item'].'#'.$id_proposal_calc.'" '.$check.'></td>
						<td>';
						
						if($pipeline_id<296){
							if($update_right == 1){
								$grid_list .='<a href="#" onclick="showEditCalcLine('.$row_table['id_ord_calc_item'].','.$id_proposal_calc.');" class="btn btn-white btn-sm">
									<i class="fa fa-edit"></i>
								</a>';
							}
						} else {
							$grid_list .='---';
						}
						
						$grid_list .='</td>					
					</tr>';
				}
			
				$content .='<div class="row" style="margin-top:20px; padding:0 5px;">
					<div style="margin-bottom:20px;" class="col-md-12">
						<button type="submit" id="tableCalcBtn" onclick="showCalcTable('.$id_proposal_calc.','.$id_ord_schedule.','.$last_shipment.');" class="btn pull-left"><i class="fa fa-calculator"></i> '.$lang['CONTRACT_CALCULATE_BTN'].' </button>
					</div>
					
					<table class="table table-striped table-bordered table-hover dataTables-example" id="tableCalc" style="font-size:13px;">
						<thead>
							<th>'.$lang['CONTRACT_SEQ#'].'</th>
							<th>'.$lang['CONTRACT_ITEM'].'</th>
							<th>'.$lang['CONTRACT_UNIT'].'</th>
							<th>US$</th>
							<th>EUR</th>
							<th>CHF</th>
							<th>'.$lang['CONTRACT_ACTIVE'].'</th>
							<th>'.$lang['CONTRACT_EDIT'].'</th>
						</thead>
					
						<tbody id="calcTable">
							'.$grid_list.'
						</tbody>
					</table>
				</div>';
			}
			
			$dom=$content;
			
			
		break;
		
		
		case "save_calculation_variables":
		
			$order_id = $_GET['ord_order_id'];
			$currency_id = $_GET['currency_id'];
			$usd_chf_exc_rate = $_GET['usd_chf'];
			$eur_chf_Exc_rate = $_GET['eur_chf'];
			$usd_eur_exc_rate = $_GET['usd_eur'];
			$margin_per_ton = $_GET['margin'];  
			
			$id_proposal_calc = $_GET['id_proposal_calc'];
			
			$sql = "update ord_proposal_calc set proposal_currency_id=$currency_id,
			usd_chf_exc_rate=$usd_chf_exc_rate,
			eur_chf_Exc_rate=$eur_chf_Exc_rate,
			usd_eur_exc_Rate=$usd_eur_exc_rate,
			margin_per_ton=$margin_per_ton
			where order_id=$order_id";

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);
			
			if($count==0){
				$sql_save = 'select public."SaveVariables"(\''.$id_proposal_calc.'\')';
				$rst = pg_query($conn, $sql_save);
				$count_calc = pg_num_rows($rst);
				
				if($count_calc==1){
					$dom=1;
				} else {
					$dom=0;
				}	
			}

		break;
		
		
		case "save_edited_calculation_item":
		
			$ord_proposal_id = $_GET['id_proposal_calc'];
			$id_ord_calc_item = $_GET['id_ord_calc_item'];
			
			if(isset($_GET['cost_usd'])){ $cost_usd_req = "cost_usd = '".$_GET['cost_usd']."',"; } else { $cost_usd_req = ""; }
			if(isset($_GET['cost_eur'])){ $cost_cost_eur = "cost_eur = '".$_GET['cost_eur']."',"; } else { $cost_cost_eur = ""; }
			if(isset($_GET['cost_chf'])){ $cost_cost_chf = "cost_chf = '".$_GET['cost_chf']."',"; } else { $cost_cost_chf = ""; }
			
			$sql_stats = "UPDATE public.ord_proposal_item SET ".$cost_usd_req . $cost_cost_eur . $cost_cost_chf . "   
				ord_proposal_id = $ord_proposal_id
			WHERE   id_ord_calc_item = $id_ord_calc_item";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "save_calculation_modify_by":
		
			$id_ord_schedule = $_GET['id_ord_schedule'];
			
			$calc_modified_by = $_SESSION['id_user'];
			$calc_modified_date = gmdate("Y/m/d H:i");
			
			$sql_calc = "UPDATE public.ord_ocean_schedule
				SET calc_modified_by='$calc_modified_by', calc_modified_date='$calc_modified_date'
			WHERE id_ord_schedule=$id_ord_schedule";
			$result_calc = pg_query($conn, $sql_calc) or die(pg_last_error());
			$count = pg_num_rows($result_calc);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "save_edited_proposal_calc":
		
			$id_proposal_calc = $_GET['id_proposal_calc'];
			$margin_per_ton = $_GET['margin_mt'];
			$currency_id = $_GET['currency_id'];
			$eur_chf_exc_rate = $_GET['eur_chf_exc_rate'];
			$usd_chf_exc_rate = $_GET['usd_chf_exc_rate'];
			$usd_eur_exc_rate = $_GET['usd_eur_exc_rate'];
			$id_ord_schedule = $_GET['id_ord_schedule'];
			$last_shipment = $_GET['last_shipment'];
			
			$calc_modified_by = $_SESSION['id_user'];
			$calc_modified_date = gmdate("Y/m/d H:i");
			
			$sql_calc = "UPDATE public.ord_ocean_schedule
				SET calc_modified_by='$calc_modified_by', calc_modified_date='$calc_modified_date'
			WHERE id_ord_schedule=$id_ord_schedule";
			pg_query($conn, $sql_calc) or die(pg_last_error());
			
			
			$sql_stats = "UPDATE ord_proposal_calc
				SET margin_per_ton=$margin_per_ton,
					proposal_currency_id=$currency_id,				
					usd_chf_exc_rate=$usd_chf_exc_rate, 
					eur_chf_exc_rate=$eur_chf_exc_rate, 
					usd_eur_exc_rate=$usd_eur_exc_rate
			WHERE id_proposal_calc=$id_proposal_calc";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				
				$sql_id_order = "SELECT ord_order_id, order_ship_nr, pipeline_id FROM v_order_schedule WHERE id_ord_schedule = $id_ord_schedule ";
				$rslt_id_order = pg_query($conn, $sql_id_order);
				$arr = pg_fetch_assoc($rslt_id_order);
				$id_ord_order = $arr['ord_order_id'];
				$order_ship_nr = $arr['order_ship_nr'];
				$pipeline_id = $arr['pipeline_id'];
				
				if($last_shipment==1){
					if($id_ord_order!=""){
						$sql = "UPDATE public.ord_order SET calculate_status = 1 WHERE id_ord_order=$id_ord_order ";
						$rslt = pg_query($conn, $sql);
					}
				}
				
				$sql = 'SELECT * FROM public."Recalculate"('.$id_ord_schedule.') ';
		
				$rst = pg_query($conn, $sql) or die(pg_last_error());
				$count_calc = pg_num_rows($rst);
				
				if($count_calc==1){
					$dom='1##'.$order_ship_nr.'##'.$id_ord_order.'##'.$pipeline_id;
				} else {
					$dom=0;
				}
				
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "save_active_state":
		
			$id_ord_calc_item = $_GET['id_ord_calc_item'];
			$state = $_GET['active'];
			if($state==1){ $active=0; }
			else { $active=1; }
			
			$sql_stats = "UPDATE public.ord_proposal_item
			   SET active='$active'
			WHERE id_ord_calc_item ='$id_ord_calc_item'";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		
		case "edit_calc_table_line":
		
			$edit_action = $_GET['id_ord_calc_item'];
			$id_proposal_calc = $_GET['id_proposal_calc'];
			
			$sql_table = "select id_ord_calc_item, ord_proposal_id, 
				sequence_nr, item_name, getregvalue(measure_unit) measure_unit, 
				cost_usd, cost_eur, cost_chf, active 
			from ord_proposal_item 
				WHERE ord_proposal_id='$id_proposal_calc'
				order by sequence_nr 
			";
			
			$grid_list ='';
			
			$rst_table = pg_query($conn, $sql_table);
			while($row_table = pg_fetch_assoc($rst_table)){
				if($row_table['active']=='t'){
					$check='checked';
				} else { $check=''; }
				
				$grid_list .='<tr class="gradeX">
					<td>'. $row_table['sequence_nr'] .'</td>
					<td>'. $row_table['item_name'] .'</td>
					<td>'. $row_table['measure_unit'] .'</td>';
					
					setlocale(LC_MONETARY, 'en_US');
					$cost_usd = money_format('%!n', $row_table['cost_usd']);

					setlocale(LC_MONETARY, 'de_DE.UTF-8');
					$cost_eur = money_format('%!n', $row_table['cost_eur']);
					$cost_chf = money_format('%!n', $row_table['cost_chf']);
				
					if($row_table['id_ord_calc_item']==$edit_action){
						$grid_list .='<td><input type="number" min=0 value="'. $row_table['cost_usd'] .'" id="calc_cost_usd" class="form-control"></td>
						<td><input type="number" min=0 value="'. $row_table['cost_eur'] .'" id="calc_cost_eur" class="form-control"></td>
						<td><input type="number" min=0 value="'. $row_table['cost_chf'] .'" id="calc_cost_chf" class="form-control"></td>';
						
					} else {
						$grid_list .='<td class="center">'. $cost_usd .'</td>
						<td class="center">'. $cost_eur .'</td>
						<td class="center">'. $cost_chf .'</td>';
					}
					
					$grid_list .='<td>
						<input type="checkbox" class="i-checks" value="'. $row_table['active'] .'#'.$row_table['id_ord_calc_item'].'#'.$id_proposal_calc.'" '.$check.'></td>
					<td>';
						
						if($row_table['id_ord_calc_item']==$edit_action){
							$grid_list .='<a href="#" onclick="saveEditCalcLine('.$row_table['id_ord_calc_item'].','.$id_proposal_calc.');" class="btn btn-white btn-sm">
								<i style="color:green;" class="fa fa-check"></i>
							</a>
							
							<a href="#" onclick="showEditCalcLine(0,'.$id_proposal_calc.');" class="btn btn-white btn-sm">
								<i style="color:red;" class="fa fa-times"></i>
							</a>';
							
						} else {
							$grid_list .='<a href="#" onclick="showEditCalcLine('.$row_table['id_ord_calc_item'].','.$id_proposal_calc.');" class="btn btn-white btn-sm">
								<i class="fa fa-edit"></i>
							</a>';
						}
						
					$grid_list .='</td>	
				</tr>';
			}
		
			$dom=$grid_list;
			
		break;
		

		case "get_agents_list":
		
			$val = $_GET['val'];
			$company_id = $_GET['company_id'];
			
			$sql_agent = "select * from contact where id_primary_company=$company_id";
			$rs_agent = pg_query($conn, $sql_agent);
		
			$list_agents = '<option value="">-- Select agent --</option>';
		
			while ($row_agent = pg_fetch_assoc($rs_agent)) {
				if($val=='name'){ $value=$row_agent['name']; }
				else{ $value=$row_agent['id_contact']; }
				$list_agents .= '<option value="'. $value .'">'. $row_agent['name'] .'</option>';
			}
		
			$dom=$list_agents;
			
		break;
		
		
		case "insert_booking_header":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			$id_con_booking = $_GET['id_con_booking'];
			$booking_nr = $_GET["booking_nr"];
			$type = $_GET['type'];
			
			$id_owner = $_SESSION['id_user'];
			
			if(isset($_GET["freight_agent"])){
				$freight_agent = $_GET["freight_agent"];
			} else { $freight_agent = ""; }
	
			if(isset($_GET["agent_note"])){
				$agent_note = $_GET["agent_note"];
				$req_agent_note = 'fa_note, '; $val_agent_note = ''.$agent_note.', ';
				$edit_agent_note = "fa_note='$agent_note', ";
			} else { $agent_note = ""; $req_agent_note = ''; $val_agent_note = ''; $edit_agent_note = '';}
			
			if(isset($_GET["carrier_name"])){
				$carrier_name = $_GET["carrier_name"];
			} else { $carrier_name = ""; }
			
			if(isset($_GET["carrier_note"])){
				$carrier_note = $_GET["carrier_note"];
				$req_carrier_note = 'sl_note, '; $val_carrier_note = ''.$carrier_note.', ';
				$edit_sl_note = "sl_note='$carrier_note', ";
			} else { $carrier_note = ""; $req_carrier_note = ''; $val_carrier_note = ''; $edit_sl_note = ''; }

			if(isset($_GET["forwarder_name"])){
				$forwarder_name = $_GET["forwarder_name"];
				$req_forwarder_name = 'log_contact_id, '; $val_forwarder_name = ''.$forwarder_name.', ';
				$edit_forwarder_name = "log_contact_id='$forwarder_name', ";
			} else { $forwarder_name = ""; $req_forwarder_name = ''; $val_forwarder_name = ''; $edit_forwarder_name = ''; }
			
			if(isset($_GET["forwarder_note"])){
				$forwarder_note = $_GET["forwarder_note"];
				$req_forwarder_note = 'log_note, '; $val_forwarder_note = ''.$forwarder_note.', ';
				$edit_log_note = "log_note='$forwarder_note', "; 
			} else { $forwarder_note = ""; $req_forwarder_note = ''; $val_forwarder_note = ''; $edit_log_note = ''; }

			if(isset($_GET["vessel_name"])){
				$vessel_name = strtoupper($_GET["vessel_name"]);
			} else { $vessel_name = ""; }
			
			if(isset($_GET["vessel_mmsi_id"])){
				$vessel_mmsi_id = $_GET["vessel_mmsi_id"];
				$req_vessel_mmsi_id = 'vessel_mmsi_id, '; $val_vessel_mmsi_id = ''.$vessel_mmsi_id.', ';
				$edit_vessel_mmsi_id = "vessel_mmsi_id='$vessel_mmsi_id', "; 
			} else { $vessel_mmsi_id = ""; }
			
			if(isset($_GET["voyage_nr"])){
				$voyage_nr = $_GET["voyage_nr"];
			} else { $voyage_nr = ""; }
			
			if(isset($_GET["pol"])){
				$pol = $_GET["pol"];
			} else { $pol = ""; }
			
			if(isset($_GET["cutoff_date"])){
				$cutoff_date = $_GET["cutoff_date"];
			} else { $cutoff_date = ""; }
			
			if(isset($_GET["cutoff_time"])){
				$cutoff_time = $_GET["cutoff_time"];
			} else { $cutoff_time = ""; }
			
			if(isset($_GET["vgm_cutoff"])){
				$vgm_cutoff = $_GET["vgm_cutoff"];
			} else { $vgm_cutoff = ""; }
			
			if(isset($_GET["vgm_cutoff_time"])){
				$vgm_cutoff_time = $_GET["vgm_cutoff_time"];
			} else { $vgm_cutoff_time = ""; }
			
			if(isset($_GET["etd"])){
				$etd = $_GET["etd"];
			} else { $etd = ""; }
			
			if(isset($_GET["etd_time"])){
				$etd_time = $_GET["etd_time"];
			} else { $etd_time = ""; }
			
			if(isset($_GET["pod"])){
				$pod = $_GET["pod"];
			} else { $pod = ""; }
			
			if(isset($_GET["eta"])){
				$eta = $_GET["eta"];
			} else { $eta = ""; }
			
			if(isset($_GET["eta_time"])){
				$eta_time = $_GET["eta_time"];
			} else { $eta_time = ""; }
			
			$cutoff_datetime = $cutoff_date.' '.$cutoff_time;
			$vgm_cutoff_date_time = $vgm_cutoff.' '.$vgm_cutoff_time;
			$eta_date_time = $eta.' '.$eta_time;
			$etd_date_time = $etd.' '.$etd_time;
			
			if(isset($_GET["log_contact_name"])){
				$log_contact_name = $_GET["log_contact_name"];
				$req_log_contact_name = 'log_contact_person_id, '; $val_log_contact_name = ''.$log_contact_name.', ';
				$edit_log_contact_name = "log_contact_person_id='$log_contact_name', ";
			} else { $log_contact_name = ""; $req_log_contact_name = ''; $val_log_contact_name = ''; $edit_log_contact_name = ''; }
			
			if(isset($_GET["sl_contact_name"])){
				$sl_contact_name = $_GET["sl_contact_name"];  
				$req_sl_contact_name = 'sl_contact_person_id, '; $val_sl_contact_name = ''.$sl_contact_name.', ';
				$edit_sl_contact_name = "sl_contact_person_id='$sl_contact_name', ";
			} else { $sl_contact_name = ""; $req_sl_contact_name = ''; $val_sl_contact_name = ''; $edit_sl_contact_name = ''; }
			
			if(isset($_GET["fa_contact_person_id"])){
				$fa_contact_person_id = $_GET["fa_contact_person_id"];
				$req_fa_contact_person_id = 'fa_contact_person_id, '; $val_fa_contact_person_id = ''.$fa_contact_person_id.', ';
				$edit_fa_contact_person_id = "fa_contact_person_id='$fa_contact_person_id', "; 
			} else { $fa_contact_person_id = ""; $req_fa_contact_person_id = ''; $val_fa_contact_person_id = ''; $edit_fa_contact_person_id = ''; }
			
			if(isset($_GET["fa_reference_nr"])){
				$fa_reference_nr = $_GET["fa_reference_nr"];
			} else { $fa_reference_nr = ""; }
			
			if(isset($_GET["trans_port_id"])){
				$trans_port_id = $_GET["trans_port_id"];
				$req_trans_port_id = 'trans_port_id, '; $val_trans_port_id = ''.$trans_port_id.', ';
				$edit_trans_port_id = "trans_port_id='$trans_port_id', ";
			} else { $trans_port_id = ''; $req_trans_port_id = ''; $val_trans_port_id = ''; $edit_trans_port_id = ''; }
			
			if(isset($_GET["vessel_feeder_name"])){
				$vessel_feeder_name = $_GET["vessel_feeder_name"];
				$req_vessel_feeder_name = 'vessel_feeder_name, '; $val_vessel_feeder_name = "'$vessel_feeder_name', ";
				$edit_vessel_feeder_name = "vessel_feeder_name='$vessel_feeder_name', ";
			} else { $vessel_feeder_name = ""; $req_vessel_feeder_name = ''; $val_vessel_feeder_name = ''; $edit_vessel_feeder_name = ''; }
			
			if(isset($_GET["vessel_feeder_mmsi_id"])){
				$vessel_feeder_mmsi_id = $_GET["vessel_feeder_mmsi_id"];
				$req_vessel_feeder_mmsi_id = 'vessel_feeder_mmsi_id, '; $val_vessel_feeder_mmsi_id = ''.$vessel_feeder_mmsi_id.', ';
				$edit_vessel_feeder_mmsi_id = "vessel_feeder_mmsi_id='$vessel_feeder_mmsi_id', ";
			} else { $vessel_feeder_mmsi_id = ""; $req_vessel_feeder_mmsi_id = ''; $val_vessel_feeder_mmsi_id = ''; $edit_vessel_feeder_mmsi_id = ''; }
			
			if(isset($_GET["tport_eta"])){
				$tport_eta = $_GET["tport_eta"];
			} else { $tport_eta = ""; }
			
			if(isset($_GET["tport_eta_time"])){
				$tport_eta_time = $_GET["tport_eta_time"];
			} else { $tport_eta_time = ""; }
			
			$tport_eta_datetime = $tport_eta.' '.$tport_eta_time;
			
			if(($tport_eta)AND($tport_eta_time)){
				$req_tport_eta = 'tport_eta, '; $val_tport_eta = "'$tport_eta_datetime', ";
				$edit_tport_eta = "tport_eta='$tport_eta_datetime', ";
			} else { $req_tport_eta = ''; $val_tport_eta = ''; $edit_tport_eta = ''; }
			
			if(isset($_GET["tport_etd"])){
				$tport_etd = $_GET["tport_etd"];
			} else { $tport_etd = ""; }
			
			if(isset($_GET["tport_etd_time"])){
				$tport_etd_time = $_GET["tport_etd_time"];
			} else { $tport_etd_time = ""; }
			
			$tport_etd_datetime = $tport_etd.' '.$tport_etd_time;
	
			if(($tport_etd)AND($tport_etd_time)){
				$req_tport_etd = 'tport_etd, '; $val_tport_etd = "'$tport_etd_datetime', ";
				$edit_tport_etd = "tport_etd='$tport_etd_datetime', ";
			} else { $req_tport_etd = ''; $val_tport_etd = ''; $edit_tport_etd = ''; }
			
			if(isset($_GET["booking_type_id"])){
				$booking_type_id = $_GET["booking_type_id"];
				$req_booking_type_id = 'booking_type_id, '; $val_booking_type_id = ''.$booking_type_id.', ';
			} else { $booking_type_id = ""; $req_booking_type_id = ''; $val_booking_type_id = ''; }
			
			if(isset($_GET["vessel_imo_id"])){
				$vessel_imo_id = $_GET["vessel_imo_id"];
				$req_vessel_imo_id = 'vessel_imo_id, '; $val_vessel_imo_id = ''.$vessel_imo_id.', ';
				$edit_vessel_imo_id = "vessel_imo_id='$vessel_imo_id', ";
			} else { $vessel_imo_id = ""; $req_vessel_imo_id = ''; $val_vessel_imo_id = ''; $edit_vessel_imo_id = ''; }
			
			if(isset($_GET["pod_id"])){
				$pod_id = $_GET["pod_id"];
				$req_pod_id = 'pod_id, '; $val_pod_id = ''.$pod_id.', ';
				$edit_pod_id = "pod_id='$pod_id', ";
			} else { $pod_id = ""; $req_pod_id = ''; $val_pod_id = ''; $edit_pod_id = ''; }
			
			if(isset($_GET["pol_id"])){
				$pol_id = $_GET["pol_id"];
				$req_pol_id = 'pol_id, '; $val_pol_id = ''.$pol_id.', ';
				$edit_pol_id = "pol_id='$pol_id', ";
			} else { $pol_id = ""; $req_pol_id = ''; $val_pol_id = ''; $edit_pol_id = ''; }
			
			
			if(isset($_GET["transport_id1"])){
				$trans_port_id1 = $_GET["transport_id1"];
				$req_trans_port_id1 = 'trans_port_id1, '; $val_trans_port_id1 = ''.$trans_port_id1.', ';
				$edit_trans_port_id1 = "trans_port_id1='$trans_port_id1', ";
			} else { $trans_port_id1 = ""; $req_transport_id1 = ''; $val_trans_port_id1 = ''; $edit_trans_port_id1 = ''; }
			
			if(isset($_GET["vessel_feeder_name1"])){
				$vessel_feeder_name1 = $_GET["vessel_feeder_name1"];
				$req_vessel_feeder_name1 = 'vessel_feeder_name1, '; $val_vessel_feeder_name1 = ''.$vessel_feeder_name1.', ';
				$edit_vessel_feeder_name1 = "vessel_feeder_name1='$vessel_feeder_name1', ";
			} else { $vessel_feeder_name1 = ""; $req_vessel_feeder_name1 = ''; $val_vessel_feeder_name1 = ''; $edit_vessel_feeder_name1 = ''; }
			
			if(isset($_GET["vessel_feeder_mmsi_id1"])){
				$vessel_feeder_mmsi_id1 = $_GET["vessel_feeder_mmsi_id1"];
				$req_vessel_feeder_mmsi_id1 = 'vessel_feeder_mmsi_id1, '; $val_vessel_feeder_mmsi_id1 = ''.$vessel_feeder_mmsi_id1.', ';
				$edit_vessel_feeder_mmsi_id1 = "vessel_feeder_mmsi_id1='$vessel_feeder_mmsi_id1', ";
			} else { $vessel_feeder_mmsi_id1 = ""; $req_vessel_feeder_mmsi_id1 = ''; $val_vessel_feeder_mmsi_id1 = ''; $edit_vessel_feeder_mmsi_id1 = ''; }
			
			if(isset($_GET["tport_etd1"])){
				$tport_etd1 = $_GET["tport_etd1"];
			} else { $tport_etd1 = ""; }
			
			if(isset($_GET["tport_etd_time1"])){
				$tport_etd_time1 = $_GET["tport_etd_time1"];
			} else { $tport_etd_time1 = ""; }
			
			$tport_etd1_datetime = $tport_etd1.' '.$tport_etd_time1;
			
			if(($tport_etd1)AND($tport_etd_time1)){
				$req_tport_etd1 = 'tport_etd1, '; $val_tport_etd1 = "'$tport_etd1_datetime', ";
				$edit_tport_etd1 = "tport_etd1='$tport_etd1_datetime', ";
			} else { $req_tport_etd1 = ''; $val_tport_etd1 = ''; $edit_tport_etd1 = ''; }
		
		
			if(isset($_GET["tport_eta1"])){
				$tport_eta1 = $_GET["tport_eta1"];
			} else { $tport_eta1 = ""; }
			
			if(isset($_GET["tport_eta_time1"])){
				$tport_eta_time1 = $_GET["tport_eta_time1"];
			} else { $tport_eta_time1 = ""; }
			
			$tport_eta1_datetime = $tport_eta1.' '.$tport_eta_time1;
			
			if(($tport_eta1)AND($tport_eta_time1)){
				$req_tport_eta1 = 'tport_eta1, '; $val_tport_eta1 = "'$tport_eta1_datetime', ";
				$edit_tport_eta1 = "tport_eta1='$tport_eta1_datetime', ";
			} else { $req_tport_eta1 = ''; $val_tport_eta1 = ''; $edit_tport_eta1 = ''; }
			
			if((!empty($vessel_feeder_name1))AND(!empty($trans_port_id1))){
				$vessel_feeder2 = 1;
			} else { $vessel_feeder2 = 0; }
			
			
			if(isset($_GET["booking_segment"])){
				$booking_segment = $_GET["booking_segment"];
			} else { $booking_segment = ""; }
			
			$con_empty = 0;
			
			if($type == 'add'){
				$sql = "INSERT INTO public.ord_con_booking 
				 (ord_schedule_id, booking_nr, sl_contact_id, $req_carrier_note vessel_name,
				 $req_vessel_mmsi_id voyage_nr, pol, $req_forwarder_name $req_forwarder_note 
				 fa_contact_id, $req_agent_note cutoff_date, pol_etd, pod, pod_eta, $req_pod_id $req_pol_id
				 $req_log_contact_name $req_sl_contact_name $req_fa_contact_person_id
				 $req_trans_port_id $req_vessel_feeder_name $req_vessel_feeder_mmsi_id $req_tport_eta $req_tport_etd
				 $req_trans_port_id1 $req_vessel_feeder_name1 $req_vessel_feeder_mmsi_id1 $req_tport_etd1 $req_tport_eta1 
				 vessel_feeder2, vgm_cutoff, $req_booking_type_id $req_vessel_imo_id booking_segment, id_owner) 

				VALUES 
				 ('$ord_schedule_id', '$booking_nr', '$carrier_name', $val_carrier_note '$vessel_name',
				 $val_vessel_mmsi_id '$voyage_nr', '$pol', $val_forwarder_name $val_forwarder_note
				 '$freight_agent', $val_agent_note '$cutoff_datetime', '$etd_date_time', '$pod', '$eta_date_time', $val_pod_id $val_pol_id
				 $val_log_contact_name $val_sl_contact_name $val_fa_contact_person_id
				 $val_trans_port_id $val_vessel_feeder_name $val_vessel_feeder_mmsi_id $val_tport_eta $val_tport_etd
				 $val_trans_port_id1 $val_vessel_feeder_name1 $val_vessel_feeder_mmsi_id1 $val_tport_etd1 $val_tport_eta1
				 $vessel_feeder2,'$vgm_cutoff_date_time', $val_booking_type_id $val_vessel_imo_id '$booking_segment', '$id_owner')"; 
				 
				 $con_empty = 1;
				 
			} else {
				$sql = "UPDATE public.ord_con_booking SET 
					booking_nr='$booking_nr', sl_contact_id='$carrier_name', $edit_sl_note vessel_name='$vessel_name',
				 $edit_vessel_mmsi_id voyage_nr='$voyage_nr', pol='$pol', $edit_forwarder_name $edit_log_note 
				 fa_contact_id='$freight_agent', $edit_agent_note cutoff_date='$cutoff_datetime', pol_etd='$etd_date_time', pod='$pod', pod_eta='$eta_date_time', $edit_pod_id $edit_pol_id
				 $edit_log_contact_name $edit_sl_contact_name $edit_fa_contact_person_id
				 $edit_trans_port_id $edit_vessel_feeder_name $edit_vessel_feeder_mmsi_id $edit_tport_eta $edit_tport_etd
				 $edit_trans_port_id1 $edit_vessel_feeder_name1 $edit_vessel_feeder_mmsi_id1 $edit_tport_etd1 $edit_tport_eta1
				 vessel_feeder2=$vessel_feeder2, vgm_cutoff='$vgm_cutoff_date_time', fa_reference_nr='$fa_reference_nr', $edit_vessel_imo_id booking_type_id='$booking_type_id'
				  WHERE id_con_booking ='$id_con_booking'
				";
			}

			$result = pg_query($conn, $sql);

			if ($result) {
				if($ord_schedule_id!=""){
					$modified_by = $_SESSION["id_user"];
					$modified_date = gmdate("Y/m/d H:i");
					
					if($con_empty ==1){
						$flag='flag_onw=1,';
					} else {
						$flag='';
					}
					
					$sql_update = "UPDATE public.ord_ocean_schedule SET $flag modified_by=$modified_by, modified_date='$modified_date'
					WHERE id_ord_schedule = $ord_schedule_id ";
					
					pg_query($conn, $sql_update);
				}
				
				$sql_booking = "select id_con_booking from public.ord_con_booking where booking_nr='$booking_nr' AND ord_schedule_id=$ord_schedule_id ";
				$rs_booking = pg_query($conn, $sql_booking);
				$row_booking = pg_fetch_assoc($rs_booking);
				$booking_id = $row_booking['id_con_booking'];
				
				if($con_empty ==1){
					$sql_con = 'select * from public."CreateConList"('.$booking_id.'); ';
					pg_query($conn, $sql_con);
				}
				
				if($con_empty ==1){
					if($booking_id!=""){
						$sql_header="SELECT ob.id_con_booking,
							l.no_sup||'-'||vo.product_code as sup_reference,
							l.ref_code_cus,
							l.ref_code_fa,
							l.ref_code_imp,
							l.ref_code_sup,
							l.supplier_name,
							ob.booking_nr,
							ob.ord_schedule_id,
							ob.booking_type_id,
							ob.con_load_date_from,
							ob.con_load_date_to,
							ob.cutoff_date,
							l.nr_containers,
							l.pol_code,
							ob.pol_etd,
							ob.vessel_feeder_name,
							ob.trans_port_id as feeder_trans_port_code,
							get_port_name(ob.trans_port_id) as feeder_trans_port_code_name,    
							ob.tport_etd,
							ob.tport_eta,
							ob.vessel_name,
							ob.pod,
							ob.pol,
							ob.pod_eta,
							ob.pod_id as final_port_code,
							get_port_name(ob.pod_id) as final_port_code_name,
							o.ord_imp_person_id,
							m.imp_mail,
							o.ord_imp_admin_id,
							m.imp_admin_mail,
							o.ord_cus_person_id,
							m.cus_email,
							o.ord_cus_admin_id,
							m.cus_admin_mail,
							o.ord_fa_person_id,
							m.fa_mail,
							o.ord_fa_admin_id,
							m.fa_admin_mail,
							o.ord_cus_contact_id AS customer,
							m.ord_sup_person_id,
							m.sup_mail,
							m.ord_sup_admin_id,
							m.sup_admin_mail,
							vo.sm_person_name,
							m.sm_phone,
							m.sm_mail,
							m.sm_skype,
							vo.sm_person_id,
							ob.load_manager_id,
							get_contact_name(ob.load_manager_id) as load_manager_name,
							get_contact_email(ob.load_manager_id) as load_manager_email,
							vo.customer_code||'-'||vo.order_nr||'.'||l.order_ship_nr as file_name,
							l.ref_code_cus, 
							l.ref_code_fa,
							l.ref_code_imp,
							l.ref_code_sup
						FROM ord_con_booking ob,
							v_logistics     l,
							ord_order       o,
							v_order         vo,
							v_order_msg     m
						WHERE ob.id_con_booking = $booking_id
						   AND l.id_ord_schedule = ob.ord_schedule_id
						   AND o.id_ord_order = l.ord_order_id
						   AND vo.id_ord_order = l.ord_order_id
						   AND m.id_ord_order = l.ord_order_id
						";
						
						$rs_header = pg_query($conn, $sql_header);
						$row_header = pg_fetch_assoc($rs_header);
						
						$id_con_booking = $row_header['id_con_booking'];
						$sup_reference = $row_header['sup_reference'];
						$ref_code_cus = $row_header['ref_code_cus'];
						$ref_code_fa = $row_header['ref_code_fa'];
						$ref_code_imp = $row_header['ref_code_imp'];
						$ref_code_sup = $row_header['ref_code_sup'];
						$supplier_name = $row_header['supplier_name'];
						$booking_nr = $row_header['booking_nr'];
						$con_load_date_from = $row_header['con_load_date_from'];
						$con_load_date_to = $row_header['con_load_date_to'];
						$cutoff_date = $row_header['cutoff_date'];
						$nr_containers = $row_header['nr_containers'];
						$pol_code = $row_header['pol_code'];
						$pol_etd = $row_header['pol_etd'];
						$vessel_feeder_name = $row_header['vessel_feeder_name'];
						$feeder_trans_port_code = $row_header['feeder_trans_port_code'];
						$feeder_trans_port_code_name = $row_header['feeder_trans_port_code_name'];
						$tport_etd = $row_header['tport_etd'];
						$tport_eta = $row_header['tport_eta'];
						$vessel_name = $row_header['vessel_name'];
						$pol = $row_header['pol'];
						$pod = $row_header['pod'];
						$pod_eta = $row_header['pod_eta'];
						$final_port_code = $row_header['final_port_code'];
						$final_port_code_name = $row_header['final_port_code_name'];
						$ord_imp_person_id = $row_header['ord_imp_person_id'];
						$imp_mail = trim($row_header['imp_mail']);
						$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
						$imp_admin_mail = $row_header['imp_admin_mail'];
						$ord_cus_person_id = $row_header['ord_cus_person_id'];
						$cus_email = $row_header['cus_email'];
						$ord_cus_admin_id = $row_header['ord_cus_admin_id'];
						$cus_admin_mail = $row_header['cus_admin_mail'];
						$ord_fa_person_id = $row_header['ord_fa_person_id'];
						$fa_mail = $row_header['fa_mail'];
						$ord_fa_admin_id = $row_header['ord_fa_admin_id'];
						$fa_admin_mail = $row_header['fa_admin_mail'];
						$customer = $row_header['customer'];
						$ord_sup_person_id = $row_header['ord_sup_person_id'];
						$sup_mail = $row_header['sup_mail'];
						$ord_sup_admin_id = $row_header['ord_sup_admin_id'];
						$sup_admin_mail = $row_header['sup_admin_mail'];
						$sm_person_name = $row_header['sm_person_name'];
						$sm_phone = $row_header['sm_phone'];
						$sm_mail = $row_header['sm_mail'];
						$sm_skype = $row_header['sm_skype'];
						$sm_person_id = $row_header['sm_person_id'];
						$load_manager_id = $row_header['load_manager_id'];
						$load_manager_name = $row_header['load_manager_name'];
						$load_manager_email = $row_header['load_manager_email'];
						$ord_schedule_id = $row_header['ord_schedule_id'];
						$booking_type_id = $row_header['booking_type_id'];
						$file_name = $row_header['file_name'];
						
						$sText = '';
						$subject = trim(utf8_decode($sup_reference))." - New Vessel Booking";
						
						$sText .= '<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
							<tbody>
					
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
							<tr style="padding:0;text-align:left;vertical-align:top">
								<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;">
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<strong>Charger</strong>
									</p>
								
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<strong>Booking Number</strong>
									</p>
								
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<strong>No Containers </strong>
									</p>
								</th>
								<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										'.$supplier_name.'
									</p>
								
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										'.$booking_nr.'
									</p>
								
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										'.$nr_containers.'
									</p>
								</th>
							</tr>
							
							<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
							<tbody>
					
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
						';
					
						if($booking_type_id==0){
							$sText .= '<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="height:14px;"></th>
								</tr>
								
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>Feeder Vessel</strong>
										</p>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$vessel_feeder_name.'
										</p>
									</th>
								</tr>
							
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>Port of Loading</strong>
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>Transshipment</strong>
										</p><br/>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$pol.'
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$feeder_trans_port_code_name.'
										</p><br/>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>ETD</strong>
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>ETA</strong>
										</p><br/>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$tport_etd.'
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$tport_eta.'
										</p><br/>
									</th>
								</tr>
								
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>Vessel Name</strong>
										</p>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$vessel_name.'
										</p>
									</th>
								</tr>
								
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>Port of Loading</strong>
										</p>
										
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>Port of Discharge</strong>
										</p>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$feeder_trans_port_code_name.'
										</p>
										
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$pod.'
										</p>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>ETD</strong>
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>ETA</strong>
										</p>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$pol_etd.'
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$pod_eta.'
										</p>
									</th>
								</tr>
							';
						
						} else 
						if($booking_type_id==1){
							$sText .= '<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="height:14px;"></th>
								</tr>
								
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>Vessel Name</strong>
										</p>
									</th>	
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$vessel_name.'
										</p>
									</th>
								</tr>
								
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>Port of Loading</strong>
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>Port of Discharge</strong>
										</p>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$pol.'
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$pod.'
										</p>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>ETD</strong>
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<strong>ETA</strong>
										</p>
									</th>
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$pol_etd.'
										</p>
									
										<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											'.$pod_eta.'
										</p>
									</th>
								</tr>
							';
						
						} else {}
					
						$sText .= '<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="height:14px;"></th>
								</tr>
								
							<tr style="padding:0;text-align:left;vertical-align:top">
								<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<strong>Loading from</strong> : 
									</p>
								</th>
								<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										'.$con_load_date_from.'
									</p>
								</th>
								<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<strong>To</strong> : 
									</p>
								</th>
								<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										'.$con_load_date_to.'
									</p>
								</th>
							</tr>
						';
					
						$sText .= '</th>
							</tbody>
							</table>
						</th></tbody></table>';
					
					
						$message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width"><title></title></head><body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background:#f3f3f3!important;box-sizing:border-box;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100%!important">
							<style type="text/css" align="center" class="float-center">@media only screen{html{min-height:100%;background:#ffffff}}@media only screen and (max-width:696px){.small-float-center{margin:0 auto!important;float:none!important;text-align:center!important}.small-text-center{text-align:center!important}.small-text-left{text-align:left!important}.small-text-right{text-align:right!important}}@media only screen and (max-width:596px){.hide-for-large{display:block!important;width:auto!important;overflow:visible!important;max-height:none!important;font-size:inherit!important;line-height:inherit!important}}@media only screen and (max-width:596px){table.body table.container .hide-for-large,table.body table.container .row.hide-for-large{display:table!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .callout-inner.hide-for-large{display:table-cell!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .show-for-large{display:none!important;width:0;mso-hide:all;overflow:hidden}}@media only screen and (max-width:596px){table.body img{width:auto;height:auto}table.body center{min-width:0!important}table.body .container{width:95%!important}table.body .column,table.body .columns{height:auto!important;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;padding-left:16px!important;padding-right:16px!important}table.body .column .column,table.body .column .columns,table.body .columns .column,table.body .columns .columns{padding-left:0!important;padding-right:0!important}table.body .collapse .column,table.body .collapse .columns{padding-left:0!important;padding-right:0!important}td.small-1,th.small-1{display:inline-block!important;width:8.33333%!important}td.small-2,th.small-2{display:inline-block!important;width:16.66667%!important}td.small-3,th.small-3{display:inline-block!important;width:25%!important}td.small-4,th.small-4{display:inline-block!important;width:33.33333%!important}td.small-5,th.small-5{display:inline-block!important;width:41.66667%!important}td.small-6,th.small-6{display:inline-block!important;width:50%!important}td.small-7,th.small-7{display:inline-block!important;width:58.33333%!important}td.small-8,th.small-8{display:inline-block!important;width:66.66667%!important}td.small-9,th.small-9{display:inline-block!important;width:75%!important}td.small-10,th.small-10{display:inline-block!important;width:83.33333%!important}td.small-11,th.small-11{display:inline-block!important;width:91.66667%!important}td.small-12,th.small-12{display:inline-block!important;width:100%!important}.column td.small-12,.column th.small-12,.columns td.small-12,.columns th.small-12{display:block!important;width:100%!important}table.body td.small-offset-1,table.body th.small-offset-1{margin-left:8.33333%!important;Margin-left:8.33333%!important}table.body td.small-offset-2,table.body th.small-offset-2{margin-left:16.66667%!important;Margin-left:16.66667%!important}table.body td.small-offset-3,table.body th.small-offset-3{margin-left:25%!important;Margin-left:25%!important}table.body td.small-offset-4,table.body th.small-offset-4{margin-left:33.33333%!important;Margin-left:33.33333%!important}table.body td.small-offset-5,table.body th.small-offset-5{margin-left:41.66667%!important;Margin-left:41.66667%!important}table.body td.small-offset-6,table.body th.small-offset-6{margin-left:50%!important;Margin-left:50%!important}table.body td.small-offset-7,table.body th.small-offset-7{margin-left:58.33333%!important;Margin-left:58.33333%!important}table.body td.small-offset-8,table.body th.small-offset-8{margin-left:66.66667%!important;Margin-left:66.66667%!important}table.body td.small-offset-9,table.body th.small-offset-9{margin-left:75%!important;Margin-left:75%!important}table.body td.small-offset-10,table.body th.small-offset-10{margin-left:83.33333%!important;Margin-left:83.33333%!important}table.body td.small-offset-11,table.body th.small-offset-11{margin-left:91.66667%!important;Margin-left:91.66667%!important}table.body table.columns td.expander,table.body table.columns th.expander{display:none!important}table.body .right-text-pad,table.body .text-pad-right{padding-left:10px!important}table.body .left-text-pad,table.body .text-pad-left{padding-right:10px!important}table.menu{width:100%!important}table.menu td,table.menu th{width:auto!important;display:inline-block!important}table.menu.small-vertical td,table.menu.small-vertical th,table.menu.vertical td,table.menu.vertical th{display:block!important}table.menu[align=center]{width:auto!important}table.button.small-expand,table.button.small-expanded{width:100%!important}table.button.small-expand table,table.button.small-expanded table{width:100%}table.button.small-expand table a,table.button.small-expanded table a{text-align:center!important;width:100%!important;padding-left:0!important;padding-right:0!important}table.button.small-expand center,table.button.small-expanded center{min-width:0}}</style>
							<span class="preheader" style="color:#ffffff;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
							<table class="body" style="Margin:0;background:#ffffff!important;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
							<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"><aside data-parsed="" style="min-width:680px;width:100%"><table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580px"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
							<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
							<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
						
							<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px"><table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left"><table class="row footer text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:25%"><table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
								<tbody>
									<tr style="padding:0;text-align:left;vertical-align:top">
										<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
											<p>'.$ref_code_fa.' | '.$ref_code_imp.' | '.$ref_code_cus.' | '.$ref_code_sup.'</p>
											
											<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
												<tr>
													<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
														<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
													</td>
													<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
														iCRM.live Message from the iDiscover Back Office.
													</td>
												</tr>
											</table>
										</th>
									</tr>
								</tbody>
							</table>
							
							</th></tr></tbody></table>
							
							<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
							'.$sText.'<hr>
							
							<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
								Message delivered by icollect.live back office on behalf of:
							</h4>
							
							<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px"><table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top">
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong>Name:</strong>
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong>Email:</strong>
								</p>
							
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong>Phone:</strong>
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong>Skype:</strong>
								</p>
							</th>
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$sm_person_name.'
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$sm_mail.'
								</p>
							
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$sm_phone.'
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$sm_skype.'
								</p>
							</th></tr></tbody></table></th></tr></tbody></table>
					
							<hr>
							<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
								Before printing think about the ENVIRONMENT!<br>Warning: If you have received this email by error, please delete it and inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
							</p>
							
							</th></tr></tbody></table></th></tr></tbody></table></td></tr></tbody></table></aside></td></tr></tbody></table>
							
							</body>
						</html>';

						
						$start = $pol_etd;
						$end = $pod_eta;
						$name = str_replace(' ', '_', "booking-".trim($sup_reference));
						$description = "New Booking \nN:".$booking_nr." \nETD:".$start." \nETA:".$end;
						$location = "iDiscover";
						$data_start = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\n";
						$data_end = "END:VCALENDAR\n";
						$ics = "BEGIN:VEVENT\nDTSTART:".date("Ymd\THis\Z",strtotime($start))."\nDTEND:".date("Ymd\THis\Z",strtotime($end))."\nLOCATION:".$location."\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:\nDTSTAMP:".date("Ymd\THis\Z")."\nSUMMARY:".$name."\nDESCRIPTION:".$description."\nPRIORITY:1\nCLASS:PUBLIC\nBEGIN:VALARM\nTRIGGER:-PT10080M\nACTION:DISPLAY\nDESCRIPTION:Reminder\nEND:VALARM\nEND:VEVENT\n";
						$data = $data_start.$ics.$data_end;
						
						file_put_contents('img/icalendar/'.$name.'.ics',$data);
						
						if($imp_mail==""){$imp_mail="c.roth@dev4impact.com";}
						$sender="noreply@icollect.live";
						$recipient=$imp_mail;
					
						
						$mail = new PHPMailer;
						$mail->isSMTP();
						// $mail->SMTPDebug = 2;
						// $mail->SMTPSecure = 'ssl';
						$mail->Debugoutput = 'html';
						$mail->Host = "d4i.maxapex.net";
						$mail->Port = 587;
						$mail->SMTPAuth = true;
						$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
						$mail->Username = ID_USER;
						$mail->Password = ID_PASS;
						$mail->setFrom('noreply@icollect.live', $sm_person_name);
						$mail->AddCC($imp_admin_mail); 
						$mail->AddCC($fa_mail); 
						$mail->AddCC($fa_admin_mail); 
						$mail->AddCC($sm_mail, $sm_person_name); 
						$mail->AddCC($load_manager_email, $load_manager_name);  
						$mail->AddBCC("croth53@gmail.com"); 
						$mail->addReplyTo($sm_mail, $sm_person_name);
						$mail->addAddress($imp_mail);
						$mail->Subject = $subject;
						$mail->msgHTML($message);
						$mail->AltBody = 'This is a plain-text message body';
				
						//For sending ical
						if(file_exists('img/icalendar/'.$name.'.ics')) {
							$mail->addStringAttachment($data,'img/icalendar/'.$name.'.ics','base64','text/calendar');
						}

						//send the message, check for errors
						if (!$mail->send()) {
							$save=0;
						} else {
							$save=1;
						}
						
						$doc="";
						if($save==1){
						
							$sql = "SELECT v_order.customer_code||'-'||v_order.order_nr||'.'||v_order_schedule.order_ship_nr as file_name 
							FROM v_order, v_order_schedule 
							WHERE v_order_schedule.ord_order_id=v_order.id_ord_order
							AND v_order_schedule.id_ord_schedule=$ord_schedule_id";
							
							$rst = pg_query($conn, $sql);
							$row = pg_fetch_assoc($rst);
						
							$date = date_create();
							$timestamp = date_timestamp_get($date);
							
							$file = trim($row['file_name']);
							$file_name = str_replace(' ', '_', $file);
							
							$doc_filename=$file_name.'-83-'.$timestamp.'.pdf';
						
							$email_sender_company_id = $_SESSION['id_company'];
							$created_by = $_SESSION['id_user'];
							$id_owner = $_SESSION['id_contact'];
							$created_date = gmdate("Y/m/d H:i");
						
							$msg_recipients = $ord_imp_person_id.','.$ord_imp_admin_id.','.$ord_fa_person_id.','.$ord_fa_admin_id.','.$sm_person_id.','.$load_manager_id;
							
							$sql_mail = "insert into ord_document (ord_schedule_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
							email_sender_id, email_sender_company_id, msg_recipients) 
							values ($ord_schedule_id, '$doc_filename', 83, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
							$created_by, $email_sender_company_id, '$msg_recipients') RETURNING id_document";
							
							$result_mail = pg_query($conn, $sql_mail);
							
							if($result_mail){
								$doc=$doc_filename;
								
								$arr = pg_fetch_assoc($result_mail);
								$id_document = $arr['id_document'];
								$user_id = $_SESSION['id_user'];
							
								$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
								VALUES ($id_document, $user_id, 1)";
								pg_query($conn, $sql2);
							}
						}
					}
				}
				
				$doc_right = $_GET['doc_right'];
				$schedule_id = $_GET['ord_schedule_id'];
				
				$id_supchain_type = $_SESSION['id_supchain_type'];
				$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
				if($id_user_supchain_type == 312){
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, $id_user_supchain_type) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, $id_user_supchain_type) as shipment_number_2, cus_incoterms_id,
						Order_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
						from v_logistics 
					where id_ord_schedule = $schedule_id";
					
				} else {
					
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, $id_supchain_type) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, $id_supchain_type) as shipment_number_2, cus_incoterms_id,
						Order_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
						from v_logistics 
					where id_ord_schedule = $schedule_id";
				}

				$rs_freight = pg_query($conn, $sql_freight);
				$row_freight = pg_fetch_assoc($rs_freight); 
				
				$pod_name = preg_replace('/\s+/', '', $row_freight['o_pod_name']);
				$pol_name = preg_replace('/\s+/', '', $row_freight['pol_name']);
				$supplier_name = preg_replace('/\s+/', '', $row_freight['supplier_name']);
				$supplier_name = str_replace("'", '', $supplier_name);
				
				$reference_nr = preg_replace('/\s+/', '', $row_freight['fa_reference_nr']);
				$id_ord_schedule = preg_replace('/\s+/', '', $row_freight['id_ord_schedule']);
				$cus_incoterms_id = preg_replace('/\s+/', '', $row_freight['cus_incoterms_id']);
				
				$id_con_booking = preg_replace('/\s+/', '', $row_freight['id_con_booking']);
				$pol_id = preg_replace('/\s+/', '', $row_freight['pol_id']);
				$pod_id = preg_replace('/\s+/', '', $row_freight['pod_id']);
				
				$flag_book_add = preg_replace('/\s+/', '', $row_freight['flag_book_add']);
				$flag_onw = preg_replace('/\s+/', '', $row_freight['flag_onw']);
				$flag_onw_add = preg_replace('/\s+/', '', $row_freight['flag_onw_add']);
				
				$ord_fa_contact_id = preg_replace('/\s+/', '', $row_freight['ord_fa_contact_id']);
				$pipeline_sched_id = preg_replace('/\s+/', '', $row_freight['pipeline_sched_id']);
				
				$shipment_number = trim($row_freight['shipment_number_1']).' '.trim($row_freight['shipment_number_2']);
				
				$bookingData = $id_ord_schedule .'@@'. $reference_nr .'@@'. $supplier_name .'@@'. $pol_name .'@@'. $pod_name .'@@'. $cus_incoterms_id .'@@'. $id_con_booking .'@@'. $shipment_number .'@@'. $pol_id .'@@'. $pod_id .'@@'. $doc_right .'@@'. $flag_book_add .'@@'. $flag_onw .'@@'. $flag_onw_add .'@@'. $ord_fa_contact_id .'@@'. $pipeline_sched_id;
		
		
				$cc=$imp_admin_mail.', '.$fa_mail.', '.$fa_admin_mail.', '.$sm_mail.', '.$load_manager_email;
				$dom='1#'.$booking_id.'#'.$doc.'#'.$sender.'#'.$imp_mail.'#'.$subject.'#'.$created_date.'#'.$cc.'#'.$bookingData;
				
			} else {
				$dom='0#0';
			}
		
		break;
		
		
		case "add_onward_addendum":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			
			$sql_update = "UPDATE public.ord_ocean_schedule SET flag_onw_add=1 WHERE id_ord_schedule = $ord_schedule_id ";
			$rs_update = pg_query($conn, $sql_update);
			
			if($rs_update){
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "insert_onward_carriage":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			$id_con_booking = $_GET['id_con_booking'];
			$booking_nr = $_GET["booking_nr"];
			$type = $_GET['type'];
			
			$id_owner = $_SESSION['id_user'];
			
			if(isset($_GET["vessel_name"])){
				$vessel_name = strtoupper($_GET["vessel_name"]);
			} else { $vessel_name = ""; }
		
			if(isset($_GET["vessel_mmsi_id"])){
				$vessel_mmsi_id = $_GET["vessel_mmsi_id"];
				$req_vessel_mmsi_id = 'vessel_mmsi_id, '; $val_vessel_mmsi_id = ''.$vessel_mmsi_id.', ';
				$edit_vessel_mmsi_id = "vessel_mmsi_id='$vessel_mmsi_id', "; 
			} else { $vessel_mmsi_id = ""; }
			
			if(isset($_GET["pol"])){
				$pol = $_GET["pol"];
			} else { $pol = ""; }
			
			if(isset($_GET["pol_id"])){
				$pol_id = $_GET["pol_id"];
			} else { $pol_id = ""; }
			
			if(isset($_GET["etd"])){
				$etd = $_GET["etd"];
			} else { $etd = ""; }
			
			if(isset($_GET["pod"])){
				$pod = $_GET["pod"];
			} else { $pod = ""; }
			
			if(isset($_GET["pod_id"])){
				$pod_id = $_GET["pod_id"];
			} else { $pod_id = ""; }
			
			if(isset($_GET["eta"])){
				$eta = $_GET["eta"];
			} else { $eta = ""; }
			
			$eta_date_time = $eta;
			$etd_date_time = $etd;
			
			if(isset($_GET["booking_segment"])){
				$booking_segment = $_GET["booking_segment"];
			} else { $booking_segment = ""; }
			
			if(isset($_GET["booking_type_id"])){
				$booking_type_id = $_GET["booking_type_id"];
				$req_booking_type_id = 'booking_type_id, '; $val_booking_type_id = ''.$booking_type_id.', ';
			} else { $booking_type_id = ""; $req_booking_type_id = ''; $val_booking_type_id = ''; }
			
			
			if($type == 'add'){
				$sql = "INSERT INTO public.ord_con_booking 
				 (ord_schedule_id, booking_nr, vessel_name, $req_vessel_mmsi_id
				 pol, pol_id, pol_etd, pod, pod_id, pod_eta, booking_segment, id_owner, booking_type_id) 

				VALUES 
				 ($ord_schedule_id, '$booking_nr', '$vessel_name', $val_vessel_mmsi_id 
				 '$pol', $pol_id, '$etd_date_time', '$pod', $pod_id, '$eta_date_time', $booking_segment, $id_owner, 0)"; 
				
			} else {
				$sql = "UPDATE public.ord_con_booking SET 
					booking_nr='$booking_nr', vessel_name='$vessel_name',
				 $edit_vessel_mmsi_id pol='$pol', pol_id=$pol_id, pol_etd='$etd_date_time', 
				 pod='$pod', pod_id=$pod_id, pod_eta='$eta_date_time'
				  WHERE id_con_booking ='$id_con_booking'
				";
			}

			$result = pg_query($conn, $sql);

			if ($result) {
				// if($ord_schedule_id!=""){
					// $modified_by = $_SESSION["id_user"];
					// $modified_date = gmdate("Y/m/d H:i");
					
					// $sql_update = "UPDATE public.ord_ocean_schedule SET modified_by=$modified_by, modified_date='$modified_date'
					// WHERE id_ord_schedule = $ord_schedule_id ";
					
					// pg_query($conn, $sql_update);
				// }
	
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "edit_booking_containers":
		
			$container_nr = $_GET['container_nr'];
			$id_con_list = $_GET['id_con_list'];
			$ord_schedule_id = $_GET['ord_schedule_id'];
			
			if(isset($_GET["tare"])){
				$tare = $_GET["tare"];
				$edit_tare = "tare='$tare', ";
			} else { $tare = ""; $edit_tare = ''; }
			
			if(isset($_GET["vgm_weight"])){
				$vgm_weight = $_GET["vgm_weight"];
				$edit_vgm_weight = "vgm_weight='$vgm_weight', ";
			} else { $vgm_weight = ""; $edit_vgm_weight = ''; }
			
			if(isset($_GET["seal_1_nr"])){
				$seal_1_nr = $_GET["seal_1_nr"];
				$edit_seal_1_nr = "seal_1_nr='$seal_1_nr', ";
			} else { $seal_1_nr = ""; $edit_seal_1_nr = ''; }
			
			if(isset($_GET["seal_2_nr"])){
				$seal_2_nr = $_GET["seal_2_nr"];
				$edit_seal_2_nr = "seal_2_nr='$seal_2_nr', ";
			} else { $seal_2_nr = ""; $edit_seal_2_nr = ''; }
			
			if(isset($_GET["seal_3_nr"])){
				$seal_3_nr = $_GET["seal_3_nr"];
				$edit_seal_3_nr = "seal_3_nr='$seal_3_nr', ";
			} else { $seal_3_nr = ""; $edit_seal_3_nr = ''; }
			
			if(isset($_GET["seal_4_nr"])){
				$seal_4_nr = $_GET["seal_4_nr"];
				$edit_seal_4_nr = "seal_4_nr='$seal_4_nr', ";
			} else { $seal_4_nr = ""; $edit_seal_4_nr = ''; }
			
			if(isset($_GET["seal_5_nr"])){
				$seal_5_nr = $_GET["seal_5_nr"];
				$edit_seal_5_nr = "seal_5_nr='$seal_5_nr', ";
			} else { $seal_5_nr = ""; $edit_seal_5_nr = ''; }
			
			if(isset($_GET["date_loaded"])){
				$date_loaded = $_GET["date_loaded"];
				$edit_date_loaded = "date_loaded='$date_loaded', ";
			} else { $date_loaded = ""; $edit_date_loaded = ''; }
			

			$sql = "UPDATE public.ord_con_list SET $edit_tare $edit_vgm_weight 
				$edit_seal_1_nr $edit_seal_2_nr $edit_seal_3_nr $edit_seal_4_nr $edit_seal_5_nr $edit_date_loaded
			container_nr='$container_nr' WHERE id_con_list = $id_con_list ";
			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_container = "Select * From v_booking_conlist where ord_schedule_id= $ord_schedule_id Order by cus_con_ref1";
				$rs_container = pg_query($conn, $sql_container);
	
				$list_container = '';
			
				while ($row_container = pg_fetch_assoc($rs_container)) {
					if($row_container['task_done']==2){
						$loading=' - <span class="text-navy">loading...</span>';
					} else 
					if($row_container['task_done']==1){
						$loading=' - <span class="text-success">Loading complete</span>';
					} else {
						$loading='';
					}
				
					$list_container .= '<tr><td>  
							<a href="javascript:loadingForm2(\''. $row_container['ord_loading_id'] .'\',\''. $row_container['id_ord_loading_item'] .'\',\''. $row_container['booking_nr'] .'\',\''. $row_container['ord_schedule_id'] .'\',\''. $row_container['id_con_list'] .'\',\''. $row_container['container_nr'] .'\');" class="reference_nr">
								'. $row_container['cus_con_ref1'] . '
							</a>
						</td>
						
						<td> '.$row_container['container_nr']. $loading .'</td>  
						<td> T: '. $row_container['tare'] . ' </td>  
						<td> W: '. $row_container['vgm_weight'] . ' </td>    
						<td> '. $row_container['date_loaded'] . ' </td>  
						
						<td style="width:60px">
							<a href="#" onclick="loadingForm2(\''. $row_container['ord_loading_id'] .'\',\''. $row_container['id_ord_loading_item'] .'\',\''. $row_container['booking_nr'] .'\',\''. $row_container['ord_schedule_id'] .'\',\''. $row_container['id_con_list'] .'\',\''. $row_container['container_nr'] .'\');"><i class="fa fa-eye"></i></a>
							<span class="containers_action_tb">
								&nbsp;<a href="#" onclick="edit_container(\''.$row_container['id_con_list'].'\',\''. $row_container['container_nr'] .'\',\''. $ord_schedule_id .'\',\''. $row_container['tare'] .'\',\''. $row_container['vgm_weight'] .'\',\''. $row_container['seal_1_nr'] .'\',\''. $row_container['seal_2_nr'] .'\',\''. $row_container['seal_3_nr'] .'\',\''. $row_container['seal_4_nr'] .'\',\''. $row_container['seal_5_nr'] .'\',\''. $row_container['date_loaded'] .'\');"><i class="fa fa-pen-square"></i></a>
								&nbsp;<a href="#" class="'.$ocean_cc.'" onclick="contdeleteConfirm(\''. $row_container['id_con_list'] .'\',\''. $row_container['container_nr'] .'\',\''. $ord_schedule_id .'\',\''. $row_container['tare'] .'\',\''. $row_container['vgm_weight'] .'\',\''. $row_container['date_loaded'] .'\');"><i class="fa fa-trash"></i></a>
							</span>
						</td>
					</tr>';
				}
				
				$dom='1??'.$list_container;
			} else {
				$dom='0??0';
			}
			
		break;
		
		
		case "crm_freight":
		
			$update_right = $_GET['update_right'];
			$delete_right = $_GET['delete_right'];
			
			$sql_stats = "Select * from v_con_freight";
			$result = pg_query($conn, $sql_stats);
			
			// $carrier='';
			// $sql_carr = "Select id_contact, name from public.contact where id_supchain_type=327";
			// $res_carr = pg_query($conn, $sql_carr);
			
			$i=1;
			$crm_freight='';
			while($arr = pg_fetch_assoc($result)){
				
				$pol_name = preg_replace('/\s+/', '', $arr['pol_name']);
				$pod_name = preg_replace('/\s+/', '', $arr['pod_name']);
				
				$crm_freight .= '<tr>
					<td>'. $i .'</td>
					<td>'. $pol_name .'</td>
					<td>'. $arr['incoterm_name'] .'</td>
					<td>'. $pod_name .'</td>
					<td>'. $arr['shipping_company'] .'</td>
					<td>'. $arr['packaging_type_name'] .'</td>
					<td class="row_actions">';
						
						if($update_right == 1){
							$crm_freight .= '<a href="#" data-toggle="modal" onclick="showSystemFreight('. $arr['id_con_box_fr'] .');" data-target="#newSystFreightmodal"><i class="fa fa-pen-square"></i></a> ';
						}
						
						if($delete_right == 1){
							$crm_freight .= ' <a href="javascript:deleteSystemFreight('. $arr['id_con_box_fr'] .');" onclick="return confirm(\'Are you sure you want to delete freight from '. $pol_name .' to '. $pod_name .' ?\')"><i class="fa fa-trash"></i></a>';
						}
						
					$crm_freight .= '</td>
				</tr>';
				$i++;
			}
			
			$dom=$crm_freight;
		
		break;
		
		
		case "save_booking_doc":
		
			$confirmation_document = $_GET['confirmation_document'];
			$id_con_booking = $_GET['id_con_booking'];
			
			$sql = "UPDATE public.ord_con_booking SET 
					confirmation_document='$confirmation_document'
				WHERE id_con_booking ='$id_con_booking'
			";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "view_booking_document":

			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
			$state="";
			// if(($id_supchain_type == 112)&&($id_user_supchain_type==312)){
				
				$id_document = $_GET['id_document'];
				if($id_document!=0){
					$sql="select active, ord_schedule_id, ord_order_id, document_desc, doc_type_id from public.ord_document where id_document=$id_document";
					$result = pg_query($conn, $sql);
					$arr = pg_fetch_assoc($result);
					$document_desc = $arr['document_desc'];
					$ord_order_id = $arr['ord_order_id'];
					if($arr['ord_schedule_id']!=""){
						$ord_schedule_id = $arr['ord_schedule_id'];
					} else { $ord_schedule_id = 0; }
					
					$doc_type_id = $arr['doc_type_id'];
					$active = $arr['active'];
					
					if($active==1){ $val="checked"; } else { $val=""; }
					
					$state='<div class="pull-left col-md-8" style="background:#efefef; padding-top:5px; margin-left:30px;">
						<div class="pull-left col-md-7">
							<div class="form-group" style="margin-bottom:5px;">
								<label style="font-size:10px;" class="pull-left">Document Description</label>
								<input type="text" id="popup_doc_desc" value="'.$document_desc.'" class="form-control" />
							</div>
						</div>
						<div class="pull-left col-md-3">
							<label style="font-size:10px;" class="pull-left">Document Status</label>
							<div class="pull-left">
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" '.$val.' class="onoffswitch-checkbox" id="doc_active" value="'.$active.'">
									   <label class="onoffswitch-label" for="doc_active">
											<span class="onoffswitch-inner"></span>
											<span class="onoffswitch-switch"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" id="popup_doc_type_id" value="'.$doc_type_id.'" />
						<div class="col-md-2" style="margin-top:15px;">
							<button type="button" class="btn btn-primary pull-right" onclick="docTatus('.$id_document.','.$ord_schedule_id.','.$ord_order_id.');" data-dismiss="modal">
								<i class="fa fa-save"></i>
							</button>
						</div>
					</div>';
				} 
			// }
		
			$confirmation_document = $_GET['confirmation_document'];
			$target_file_path = 'img/documents/' . $confirmation_document;
			
			$footer='<a href="'.$target_file_path.'" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</a>'.$state.'
			<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
			
			if (file_exists($target_file_path)) {
				$data = '<div><iframe src="'.$target_file_path.'" style="width:100%; height:500px;"></iframe></div>';
			} else {
				$data = 'File is missing';
			}
			
			$dom=$data.'##'.$footer;
		
		break;
		
		
		
		
		
		
		
		
		case "reset_password":
		
			$pass = $_GET["password"];
			if($_GET['user_id']==0){
				$id_user = $_SESSION['id_user'];
			} else {
				$id_user = $_GET['user_id'];
			}
			
			$modified_date = gmdate("Y/m/d H:i");
			
			$password_2=md5($pass);
			
			$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
			
			$password = hash('sha256', $pass . $salt);
			
			for($round = 0; $round < 65536; $round++) 
			{ 
				$password = hash('sha256', $password . $salt); 
			} 
			
			$sql = "UPDATE public.users
			   SET  password='$password', salt='$salt', modified_date='$modified_date', modified_by=$id_user, pwd_reset=1, password_2='$password_2'
			 WHERE id_user=$id_user";
			$result = pg_query($conn, $sql);
			
			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "reset_pass_link":
		
			$username = $_GET["username"];
			
			$sql = "SELECT id_user, name FROM public.v_security_new WHERE username = '$username'";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			if(($arr['name']!="")&&($arr['id_user']!="")){

				$user_id = $arr['id_user'];
				$name = $arr['name'];

				$url = 'https://icollect.live/crm/reset_password.php?user_id=' . urlencode($user_id);
				
				// $to = $_SESSION["p_email"];
				// $email_subject = "iDiscover || Password reset";
				// $email_body = "Hi $name,\n Welcome to iDiscover - the traceability and transparency platform!\n\nWe're thrilled to have you on board. For full access, please paste the URL below into your web browser to be able to change your password.\n $url\n\nHappy (i)discovering...\n\nThe iDiscover Crew";
		
				// $headers = "From: noreply@icollect.live\n";  
				
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
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
																									<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
																								</td>
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																									iCRM.live Message from icollect.live Back Office:
																								</td>
																							</tr>
																						</table>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Hi '. $name .',
																						</h4><br/>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Welcome to iDiscover - the traceability and transparency platform!
																						</p>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							We\'re thrilled to have you on board. For full access, please paste the URL below into your web browser to be able to change your password.<br/>
																							<a href="'. $url .'">'. $url .'</a> <br/>
																							<br/>
																							Happy (i)discovering...<br/>
																							The iDiscover Crew
																						</p>
																						
																						<hr>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Before printing think about the ENVIRONMENT!<br>
																							Warning: If you have received this email by error, please delete it and
																							inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
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
				
				$sender="noreply@icollect.live";
				
				$mail = new PHPMailer;
				$mail->isSMTP();
				// $mail->SMTPDebug = 2;
				// $mail->SMTPSecure = 'ssl';
				$mail->Debugoutput = 'html';
				$mail->Host = "d4i.maxapex.net";
				$mail->Port = 587;
				$mail->SMTPAuth = true;
				$mail->MessageID = "<" . time() ."-" . md5($sender . $username) . "@icollect.live>";
				$mail->Username = ID_USER;
				$mail->Password = ID_PASS;
				$mail->setFrom('noreply@icollect.live');
				$mail->addAddress($username, $name);
				$mail->Subject = "iDiscover || Password reset";
				$mail->msgHTML($message);
				$mail->AltBody = 'This is a plain-text message body';

				//send the message, check for errors
				if (!$mail->send()) {
					$dom=2;
				} else {
					$dom=1;
				}
			
				// if(mail($to,$email_subject,$email_body,$headers)){
					// $dom=1;
				// } else {
					// $dom=0;
				// }

			} else {
				$dom=0;
			}
			
		break;
		
		
		case "manage_system_freight":
		
			if(isset($_GET["shipping_company"])){
				$shipping_company = $_GET["shipping_company"];
			} else { $shipping_company = ""; }
			
			if(isset($_GET["shipping_company_id"])){
				$shipping_company_id = $_GET["shipping_company_id"]; 
				$shipping_company_id_req = " shipping_company_id='$shipping_company_id',";
				$shipping_company_id_name = " shipping_company_id,";
				$shipping_company_id_val = " '$shipping_company_id',";
			} else { $shipping_company_id = ""; $shipping_company_id_req = ""; $shipping_company_id_name = ""; $shipping_company_id_val = ""; }
			
			if(isset($_GET["freight_eur"])){
				$freight_eur = $_GET["freight_eur"]; 
				$freight_eur_req = " freight_eur='$freight_eur',";
				$freight_eur_name = " freight_eur,";
				$freight_eur_val = " '$freight_eur',";
			} else { $freight_eur = ""; $freight_eur_req = ""; $freight_eur_name = ""; $freight_eur_val = ""; }
			
			if(isset($_GET["freight_usd"])){
				$freight_usd = $_GET["freight_usd"];
				$freight_usd_req = " freight_usd='$freight_usd',";
				$freight_usd_name = " freight_usd,";
				$freight_usd_val = " '$freight_usd',";
			} else { $freight_usd = ""; $freight_usd_req = ""; $freight_usd_name = ""; $freight_usd_val = ""; }
			
			if(isset($_GET["freight_chf"])){
				$freight_chf = $_GET["freight_chf"];
				$freight_chf_req = " freight_chf='$freight_chf',";
				$freight_chf_name = " freight_chf,";
				$freight_chf_val = " '$freight_chf',";
			} else { $freight_chf = ""; $freight_chf_req = ""; $freight_chf_name = ""; $freight_chf_val = ""; }
			
			if(isset($_GET["add_eur"])){
				$add_eur = $_GET["add_eur"];
				$add_eur_req = " add_eur='$add_eur',";
				$add_eur_name = " add_eur,";
				$add_eur_val = " '$add_eur',";
			} else { $add_eur = ""; $add_eur_req = ""; $add_eur_name = ""; $add_eur_val = ""; }
			
			if(isset($_GET["add_usd"])){
				$add_usd = $_GET["add_usd"];
				$add_usd_req = " add_usd='$add_usd',";
				$add_usd_name = " add_usd,";
				$add_usd_val = " '$add_usd',";
			} else { $add_usd = ""; $add_usd_req = ""; $add_usd_name = ""; $add_usd_val = ""; }
			
			if(isset($_GET["add_chf"])){
				$add_chf = $_GET["add_chf"];
				$add_chf_req = " add_chf='$add_chf',";
				$add_chf_name = " add_chf,";
				$add_chf_val = " '$add_chf',";
			} else { $add_chf = ""; $add_chf_req = ""; $add_chf_name = ""; $add_chf_val = ""; }
			
			if(isset($_GET["total_eur"])){
				$total_eur = $_GET["total_eur"];
				$total_eur_req = " total_eur='$total_eur',";
				$total_eur_name = " total_eur,";
				$total_eur_val = " '$total_eur',";
			} else { $total_eur = ""; $total_eur_req = ""; $total_eur_name = ""; $total_eur_val = ""; }
			
			if(isset($_GET["total_usd"])){
				$total_usd = $_GET["total_usd"];
				$total_usd_req = " total_usd='$total_usd',";
				$total_usd_name = " total_usd,";
				$total_usd_val = " '$total_usd',";
			} else { $total_usd = ""; $total_usd_req = ""; $total_usd_name = ""; $total_usd_val = ""; }
			
			if(isset($_GET["total_chf"])){
				$total_chf = $_GET["total_chf"];
				$total_chf_req = " total_chf='$total_chf',";
				$total_chf_name = " total_chf,";
				$total_chf_val = " '$total_chf',";
			} else { $total_chf = ""; $total_chf_req = ""; $total_chf_name = ""; $total_chf_val = ""; }
			
			if(isset($_GET["dem_pol_free"])){
				$dem_pol_free = $_GET["dem_pol_free"];
				$dem_pol_free_req = " dem_pol_free='$dem_pol_free',";
				$dem_pol_free_name = " dem_pol_free,";
				$dem_pol_free_val = " '$dem_pol_free',";
			} else { $dem_pol_free = ""; $dem_pol_free_req = ""; $dem_pol_free_name = ""; $dem_pol_free_val = ""; }
			
			if(isset($_GET["dem_pol_cost_after"])){
				$dem_pol_cost_after = $_GET["dem_pol_cost_after"];
				$dem_pol_cost_after_req = " dem_pol_cost_after='$dem_pol_cost_after',";
				$dem_pol_cost_after_name = " dem_pol_cost_after,";
				$dem_pol_cost_after_val = " '$dem_pol_cost_after',";
			} else { $dem_pol_cost_after = ""; $dem_pol_cost_after_req = ""; $dem_pol_cost_after_name = ""; $dem_pol_cost_after_val = ""; }
		
			if(isset($_GET["dem_pod_free"])){
				$dem_pod_free = $_GET["dem_pod_free"];
				$dem_pod_free_req = " dem_pod_free='$dem_pod_free',";
				$dem_pod_free_name = " dem_pod_free,";
				$dem_pod_free_val = " '$dem_pod_free',";
			} else { $dem_pod_free = ""; $dem_pod_free_req = ""; $dem_pod_free_name = ""; $dem_pod_free_val = ""; }
		
			if(isset($_GET["dem_pod_cost_after"])){
				$dem_pod_cost_after = $_GET["dem_pod_cost_after"];
				$dem_pod_cost_after_req = " dem_pod_cost_after='$dem_pod_cost_after',";
				$dem_pod_cost_after_name = " dem_pod_cost_after,";
				$dem_pod_cost_after_val = " '$dem_pod_cost_after',";
			} else { $dem_pod_cost_after = ""; $dem_pod_cost_after_req = ""; $dem_pod_cost_after_name = ""; $dem_pod_cost_after_val = ""; }
			
			//2
			if(isset($_GET["dem_pol_free2"])){
				$dem_pol_free2 = $_GET["dem_pol_free2"];
				$dem_pol_free_req2 = " dem_pol_free2='$dem_pol_free2',";
				$dem_pol_free_name2 = " dem_pol_free2,";
				$dem_pol_free_val2 = " '$dem_pol_free2',";
			} else { $dem_pol_free2 = ""; $dem_pol_free_req2 = ""; $dem_pol_free_name2 = ""; $dem_pol_free_val2 = ""; }
			
			if(isset($_GET["dem_pol_cost_after2"])){
				$dem_pol_cost_after2 = $_GET["dem_pol_cost_after2"];
				$dem_pol_cost_after_req2 = " dem_pol_cost_after2='$dem_pol_cost_after2',";
				$dem_pol_cost_after_name2 = " dem_pol_cost_after2,";
				$dem_pol_cost_after_val2 = " '$dem_pol_cost_after2',";
			} else { $dem_pol_cost_after2 = ""; $dem_pol_cost_after_req2 = ""; $dem_pol_cost_after_name2 = ""; $dem_pol_cost_after_val2 = ""; }
		
			if(isset($_GET["dem_pod_free2"])){
				$dem_pod_free2 = $_GET["dem_pod_free2"];
				$dem_pod_free_req2 = " dem_pod_free2='$dem_pod_free2',";
				$dem_pod_free_name2 = " dem_pod_free2,";
				$dem_pod_free_val2 = " '$dem_pod_free2',";
			} else { $dem_pod_free2 = ""; $dem_pod_free_req2 = ""; $dem_pod_free_name2 = ""; $dem_pod_free_val2 = ""; }
		
			if(isset($_GET["dem_pod_cost_after2"])){
				$dem_pod_cost_after2 = $_GET["dem_pod_cost_after2"];
				$dem_pod_cost_after_req2 = " dem_pod_cost_after2='$dem_pod_cost_after2',";
				$dem_pod_cost_after_name2 = " dem_pod_cost_after2,";
				$dem_pod_cost_after_val2 = " '$dem_pod_cost_after2',";
			} else { $dem_pod_cost_after2 = ""; $dem_pod_cost_after_req2 = ""; $dem_pod_cost_after_name2 = ""; $dem_pod_cost_after_val2 = ""; }
		
		
			if(isset($_GET["transit_time"])){
				$transit_time = $_GET["transit_time"];
				$transit_time_req = " transit_time='$transit_time',";
				$transit_time_name = " transit_time,";
				$transit_time_val = " '$transit_time',";
			} else { $transit_time = ""; $transit_time_req = ""; $transit_time_name = ""; $transit_time_val = ""; }
		
			if(isset($_GET["trans_location_id"])){
				$trans_location_id = $_GET["trans_location_id"];
				$trans_location_id_req = "  trans_location_id='$trans_location_id',";
				$trans_location_id_name = " trans_location_id,";
				$trans_location_id_val = " '$trans_location_id',";
			} else { $trans_location_id = ""; $trans_location_id_req = ""; $trans_location_id_name = ""; $trans_location_id_val = ""; }
		
			if(isset($_GET["incoterm_id"])){
				$incoterm_id = $_GET["incoterm_id"];
			} else { $incoterm_id = ""; }
		
			if(isset($_GET["trans_type_id"])){
				$trans_type_id = $_GET["trans_type_id"];
				$trans_type_id_req = "trans_type_id='$trans_type_id',";
				$trans_type_id_name = " trans_type_id,";
				$trans_type_id_val = " '$trans_type_id',";
			} else { $trans_type_id = ""; $trans_type_id_req = ""; $trans_type_id_name = ""; $trans_type_id_val = ""; }
		
			if(isset($_GET["returns_empty_id"])){
				$returns_empty_id = $_GET["returns_empty_id"];
				$returns_empty_id_req = " returns_empty_id='$returns_empty_id',";
				$returns_empty_id_name = " returns_empty_id,";
				$returns_empty_id_val = " '$returns_empty_id',";
			} else { $returns_empty_id = ""; $returns_empty_id_req = ""; $returns_empty_id_name = ""; $returns_empty_id_val = ""; }
		
			if(isset($_GET["rate_valid_until"])){
				$rate_valid_until = $_GET["rate_valid_until"];
				$rate_valid_until_req = " rate_valid_until='$rate_valid_until',";
				$rate_valid_until_name = " rate_valid_until,";
				$rate_valid_until_val = " '$rate_valid_until',";
			} else { $rate_valid_until = ""; $rate_valid_until_req = ""; $rate_valid_until_name = ""; $rate_valid_until_val = ""; }
		
			if(isset($_GET["packaging_type_id"])){
				$packaging_type_id = $_GET["packaging_type_id"];
			} else { $packaging_type_id = ""; }
		
			if(isset($_GET["weight_packaging_type"])){
				$weight_packaging_type = $_GET["weight_packaging_type"];
				$weight_packaging_type_req = " weight_packaging_type='$weight_packaging_type',";
				$weight_packaging_type_name = " weight_packaging_type,";
				$weight_packaging_type_val = " '$weight_packaging_type',";
			} else { $weight_packaging_type = ""; $weight_packaging_type_req = ""; $weight_packaging_type_name = ""; $weight_packaging_type_val = ""; }
		
			if(isset($_GET["pod_townport_id"])){
				$pod_townport_id = $_GET["pod_townport_id"];
			} else { $pod_townport_id = ""; }
		
			if(isset($_GET["pol_townport_id"])){
				$pol_townport_id = $_GET["pol_townport_id"];
			} else { $pol_townport_id = ""; }
		
			if(isset($_GET["transport_type_id"])){
				$transport_type_id = $_GET["transport_type_id"];
			} else { $transport_type_id = ""; }
			
			if(isset($_GET["id_con_box_fr"])){
				$id_con_box_fr = $_GET["id_con_box_fr"];
			} else { $id_con_box_fr = ""; }
		
			$conf = $_GET["conf"];
			$id_owner = $_SESSION["id_user"];
		
			if($conf == 'add'){
				$sql = "INSERT INTO PUBLIC.ord_con_freight (shipping_company, $freight_eur_name $shipping_company_id_name $freight_usd_name $add_eur_name  
				$add_usd_name $total_eur_name packaging_type_id, $transit_time_name $trans_location_id_name $dem_pol_free_name  
				$dem_pol_cost_after_name $dem_pod_free_name $dem_pod_cost_after_name $returns_empty_id_name $rate_valid_until_name $total_usd_name 
				$freight_chf_name $total_chf_name $add_chf_name 
				$dem_pol_free_name2 $dem_pol_cost_after_name2 $dem_pod_free_name2 $dem_pod_cost_after_name2
				transport_type_id, $weight_packaging_type_name 
				id_owner, $trans_type_id_name incoterm_id, pod_townport_id, pol_townport_id)  
			VALUES('$shipping_company', $freight_eur_val $shipping_company_id_val $freight_usd_val $add_eur_val $add_usd_val $total_eur_val '$packaging_type_id',  
				$transit_time_val $trans_location_id_val $dem_pol_free_val $dem_pol_cost_after_val $dem_pod_free_val 
				$dem_pod_cost_after_val $returns_empty_id_val $rate_valid_until_val $total_usd_val $freight_chf_val $total_chf_val $add_chf_val  
				$dem_pol_free_val2 $dem_pol_cost_after_val2 $dem_pod_free_val2 $dem_pod_cost_after_val2
				'$transport_type_id', $weight_packaging_type_val  
				'$id_owner', $trans_type_id_val '$incoterm_id', '$pod_townport_id', '$pol_townport_id');";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.ord_con_freight SET shipping_company='$shipping_company', $freight_eur_req $shipping_company_id_req
				$freight_usd_req $add_eur_req $add_usd_req $total_eur_req packaging_type_id='$packaging_type_id',  
				$transit_time_req $trans_location_id_req $dem_pol_free_req  
				$dem_pol_cost_after_req $dem_pod_free_req $dem_pod_cost_after_req 
				$dem_pol_free_req2 $dem_pol_cost_after_req2 $dem_pod_free_req2 $dem_pod_cost_after_req2
				$returns_empty_id_req $rate_valid_until_req $total_usd_req $freight_chf_req  
				$total_chf_req $add_chf_req transport_type_id='$transport_type_id', $weight_packaging_type_req  
				$trans_type_id_req  incoterm_id='$incoterm_id', pod_townport_id='$pod_townport_id', pol_townport_id='$pol_townport_id'   
				WHERE id_con_box_fr='$id_con_box_fr';";
				
			} else {}
	
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "show_system_freight":
		
			$id_con_box_fr = $_GET["id_con_box_fr"];
			
			$sql_stats = "SELECT 
				shipping_company, freight_eur, freight_usd, add_eur,
				add_usd, total_eur, packaging_type_id, transit_time,
				trans_location_id, dem_pol_free, dem_pol_cost_after, dem_pod_free,
				dem_pod_cost_after, dem_pol_free2, dem_pol_cost_after2, dem_pod_free2,
				dem_pod_cost_after2, returns_empty_id, rate_valid_until, total_usd,
				freight_chf, total_chf, add_chf, transport_type_id,
				weight_packaging_type, incoterm_id, pod_townport_id, pol_townport_id,
				trans_type_id, shipping_company_id
			  FROM v_con_freight 
			WHERE id_con_box_fr=$id_con_box_fr";
			
			$result = pg_query($conn, $sql_stats);

			$arr = pg_fetch_assoc($result);
			
			$dom = $arr['shipping_company'].'#'.$arr['freight_eur'].'#'.$arr['freight_usd'].'#'.$arr['add_eur'].'#'.  
				$arr['add_usd'].'#'.$arr['total_eur'].'#'.$arr['packaging_type_id'].'#'.$arr['transit_time'].'#'.
				$arr['trans_location_id'].'#'.$arr['dem_pol_free'].'#'. $arr['dem_pol_cost_after'].'#'.$arr['dem_pod_free'].'#'.
				$arr['dem_pod_cost_after'].'#'.$arr['returns_empty_id'].'#'. $arr['rate_valid_until'].'#'.$arr['total_usd'].'#'.
				$arr['freight_chf'].'#'.$arr['total_chf'].'#'.$arr['add_chf'].'#'. $arr['transport_type_id'].'#'.
				$arr['weight_packaging_type'].'#'. $arr['incoterm_id'].'#'. $arr['pod_townport_id'].'#'.$arr['pol_townport_id'].'#'.
				$arr['trans_type_id'].'#'.$arr['shipping_company_id'].'#'.
			$arr['dem_pol_free2'].'#'.$arr['dem_pol_cost_after2'].'#'.$arr['dem_pod_free2'].'#'.$arr['dem_pod_cost_after2'];
			
		break;
		
		
		case "delete_system_freight":
		
			$id_con_box_fr = $_GET['id_con_box_fr'];
			
			$sql = "DELETE FROM public.ord_con_freight WHERE id_con_box_fr = $id_con_box_fr";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "manag_system_port_cost":
		
			if(isset($_GET["item_name"])){
				$item_name = $_GET["item_name"];
			} else { $item_name = ""; }
		
			if(isset($_GET["measure_unit_id"])){
				$measure_unit_id = $_GET["measure_unit_id"];
			} else { $measure_unit_id = ""; }
		
			if(isset($_GET["active"])){
				$active = $_GET["active"];
				$req_active = " active,";
				$val_active = " '$active',";
				$updt_active = " active='$active',";
			} else { $active = ""; }
		
			if(isset($_GET["validity_date"])){
				$validity_date = $_GET["validity_date"];
				$req_validity_date="validity_date,"; 
				$val_validity_date="'$validity_date',"; 
				$updt_validity_date="validity_date='$validity_date',";
			} else { $validity_date = ""; $val_validity_date=""; $req_validity_date=""; $updt_validity_date=""; }
		
			if(isset($_GET["sequence_nr"])){
				$sequence_nr = $_GET["sequence_nr"];
			} else { $sequence_nr = ""; }
		
			if(isset($_GET["currency_id"])){
				$currency_id = $_GET["currency_id"];
			} else { $currency_id = ""; }
		
			if(isset($_GET["cost_eur"])){
				$cost_eur = $_GET["cost_eur"];
				$req_cost_eur = " cost_eur,";
				$val_cost_eur = " $cost_eur,";
				$updt_cost_eur = " cost_eur=$cost_eur,";
			} else { $cost_eur = ""; $req_cost_eur = ""; $val_cost_eur = ""; $updt_cost_eur = ""; }
		
			if(isset($_GET["cost_usd"])){
				$cost_usd = $_GET["cost_usd"];
				$req_cost_usd = " cost_usd,";
				$val_cost_usd = " $cost_usd,";
				$updt_cost_usd = " cost_usd=$cost_usd,";
			} else { $cost_usd = ""; $req_cost_usd = ""; $val_cost_usd = ""; $updt_cost_usd = "";  }
		
			if(isset($_GET["cost_chf"])){
				$cost_chf = $_GET["cost_chf"];
				$req_cost_chf = " cost_chf,";
				$val_cost_chf = " $cost_chf,";
				$updt_cost_chf = " cost_chf=$cost_chf,";
			} else { $cost_chf = ""; $req_cost_chf = ""; $val_cost_chf = ""; $updt_cost_chf = ""; }
		
			if(isset($_GET["calculation_method"])){
				$calculation_method = $_GET["calculation_method"];
			} else { $calculation_method = ""; }
			
			if(isset($_GET["id_reg_cost"])){
				$id_reg_cost = $_GET["id_reg_cost"];
			} else { $id_reg_cost = ""; }
	
			$conf = $_GET["conf"];
			$id_owner = $_SESSION['id_user'];
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.ord_reg_cost (item_name, measure_unit_id, $req_active $req_validity_date  
				sequence_nr, currency_id, $req_cost_eur $req_cost_usd $req_cost_chf calculation_method, id_owner)  
				VALUES ('$item_name', $measure_unit_id, $val_active $val_validity_date $sequence_nr, $currency_id,  
				$val_cost_eur $val_cost_usd $val_cost_chf $calculation_method, $id_owner)";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.ord_reg_cost SET item_name='$item_name', measure_unit_id=$measure_unit_id,  
				$updt_active $updt_validity_date sequence_nr=$sequence_nr, currency_id=$currency_id,  
				$updt_cost_eur $updt_cost_usd $updt_cost_chf calculation_method=$calculation_method
				WHERE id_reg_cost=$id_reg_cost;";
				
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "show_system_port_cost":
		
			$id_reg_cost = $_GET["id_reg_cost"];
			
			$sql_stats = "SELECT * FROM ord_reg_cost WHERE id_reg_cost=$id_reg_cost";
			$result = pg_query($conn, $sql_stats);

			$arr = pg_fetch_assoc($result);
			
			$dom=$arr['item_name'].'#'.$arr['measure_unit_id'].'#'.$arr['active'].'#'.$arr['validity_date'].'#'.  
			$arr['sequence_nr'].'#'.$arr['currency_id'].'#'.$arr['cost_eur'].'#'.$arr['cost_usd'].'#'.
			$arr['cost_chf'].'#'.$arr['calculation_method'].'#'.$arr['id_reg_cost'];
		
		break;
		
		
		case "delete_system_port_cost":
		
			$id_reg_cost = $_GET["id_reg_cost"];
			
			$sql = "DELETE FROM public.ord_reg_cost WHERE id_reg_cost = $id_reg_cost";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "port_costs_table":
			
			$update_right = $_GET["update_right"];
			$delete_right = $_GET["delete_right"];
			
			$sql_stats = "SELECT rc.cost_eur, rc.cost_usd, rc.cost_chf, rc.currency_id,
				rc.id_reg_cost,
				rc.item_name,
				un.cvalue As unit,
				cy.cvalue As currency
			FROM ord_reg_cost rc
				LEFT JOIN ( SELECT id_regvalue, cvalue FROM v_regvalues WHERE id_register=45 ) un ON un.id_regvalue = rc.measure_unit_id
				LEFT JOIN ( SELECT id_regvalue, cvalue FROM public.v_regvalues WHERE id_register = 51 ) cy ON cy.id_regvalue = rc.currency_id ";
			$result = pg_query($conn, $sql_stats);

			$port_list ='';
			while($arr = pg_fetch_assoc($result)){
				
				if($arr['currency_id'] == 277){
					$cost = $arr['cost_usd'];
				} else 
				if($arr['currency_id'] == 278){
					$cost = $arr['cost_eur'];
				} else
				if($arr['currency_id'] == 279){
					$cost = $arr['cost_chf'];
				} else {
					$cost = '';
				}
				
				$port_list .= '<tr>
					<td>'. $arr['id_reg_cost'].'</td>
					<td>'. $arr['item_name'].'</td>
					<td>'. $arr['currency'].'</td>
					<td>' . $cost .'</td>
					<td>'. $arr['unit'].'</td>
					<td class="row_actions">';
					
						if($update_right == 1){
							$port_list .= '<a href="#" data-toggle="modal" onclick="showDelSysPortCost(\'show\',\''. $arr['id_reg_cost'] .'\');" data-target="#modalPortCost"><i class="fa fa-pen-square" aria-hidden="true"></i></a> ';
						}
						
						if($delete_right == 1){
							$port_list .= ' <a href="#"><i class="fa fa-trash" onclick="showDelSysPortCost(\'del\',\''. $arr['id_reg_cost'] .'\');" aria-hidden="true"></i></a>';
						}
					
				    $port_list .= '</td>
				</tr>';
			}
			
			$dom=$port_list;
		
		break;
		
		
		case "manag_system_port":
		
			if(isset($_GET["portname"])){
				$portname = $_GET["portname"];
			} else { $portname = ""; }
	
			if(isset($_GET["port_type_id"])){
				$port_type_id = $_GET["port_type_id"];
			} else { $port_type_id = ""; }
	
			if(isset($_GET["port_code"])){
				$port_code = $_GET["port_code"];
				$port_code_req = " port_code='$port_code',";
				$port_code_name = " port_code,";
				$port_code_val = " '$port_code',";
			} else { $port_code = ""; $port_code_req = ""; $port_code_name = ""; $port_code_val = ""; }
	
			if(isset($_GET["qm_org_contact_id"])){
				$qm_org_contact_id = $_GET["qm_org_contact_id"];
				$qm_org_contact_id_req = " qm_org_contact_id=$qm_org_contact_id,";
				$qm_org_contact_id_name = " qm_org_contact_id,";
				$qm_org_contact_id_val = " $qm_org_contact_id,";
			} else { $qm_org_contact_id = ""; $qm_org_contact_id_req = ""; $qm_org_contact_id_name = ""; $qm_org_contact_id_val = ""; }
			
			if(isset($_GET["transit_days"])){
				$transit_days = $_GET["transit_days"];
				$transit_days_req = " transit_days=$transit_days,";
				$transit_days_name = " transit_days,";
				$transit_days_val = " $transit_days,";
			} else { $transit_days = ""; $transit_days_req = ""; $transit_days_name = ""; $transit_days_val = ""; }
			
			if(isset($_GET["town_id"])){
				$town_id = $_GET["town_id"];
				
				$sql_towns = "SELECT id_country FROM public.towns WHERE gid_town=$town_id";
				$rs_towns = pg_query($conn, $sql_towns);
				$row_towns = pg_fetch_assoc($rs_towns);
				$id_country = $row_towns['id_country'];
				
			} else { $town_id = ""; $id_country = ""; }
			
			if(isset($_GET["onward_delay"])){
				$onward_delay = $_GET["onward_delay"];
				$onward_delay_req = " onward_delay=$onward_delay,";
				$onward_delay_name = " onward_delay,";
				$onward_delay_val = " $onward_delay,";
			} else { $onward_delay = ""; $onward_delay_req = ""; $onward_delay_name = ""; $onward_delay_val = ""; }
	
			if(isset($_GET["id_townport"])){
				$id_townport = $_GET["id_townport"];
			} else { $id_townport = ""; }
	
			$conf = $_GET["conf"];
			$id_owner = $_SESSION["id_user"];
			
			if($conf == 'add'){
				$sql = "INSERT INTO PUBLIC.ord_towns_port (portname, town_id, port_type_id, $qm_org_contact_id_name $port_code_name  
				$onward_delay_name id_owner, $transit_days_name id_country)  
				VALUES ('$portname', $town_id, $port_type_id, $qm_org_contact_id_val $port_code_val $onward_delay_val $id_owner,  
				$transit_days_val $id_country);";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.ord_towns_port SET portname='$portname', town_id=$town_id, port_type_id=$port_type_id,  
				$qm_org_contact_id_req $port_code_req $onward_delay_req id_owner=$id_owner,  
				$transit_days_req id_country=$id_country 
				WHERE id_townport=$id_townport;";
				
			} else {}
			
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		case "show_system_port":
		
			$id_townport = $_GET["id_townport"];
			
			$sql_stats = "SELECT * FROM ord_towns_port WHERE id_townport=$id_townport";
			$result = pg_query($conn, $sql_stats);

			$arr = pg_fetch_assoc($result);
			
			$dom=$arr['portname'].'#'.$arr['port_type_id'].'#'.$arr['port_code'].'#'.$arr['qm_org_contact_id'].'#'.  
			$arr['transit_days'].'#'.$arr['town_id'].'#'.$arr['onward_delay'];
		
		break;
		
		
		case "delete_system_port":
		
			$id_townport = $_GET["id_townport"];
			
			$sql = "DELETE FROM public.ord_towns_port WHERE id_townport = $id_townport";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "port_table":
		
			$update_right = $_GET["update_right"];	
			$delete_right = $_GET["delete_right"];
		
			$sql_stats = "SELECT pt.portname, tp.port_type, pt.id_townport,
				pt.port_code, ct.org_contact, tw.name_town
			FROM ord_towns_port pt
				LEFT JOIN ( SELECT id_regvalue, cvalue AS port_type FROM public.v_regvalues WHERE id_register = 52 ) tp ON tp.id_regvalue = pt.port_type_id
				LEFT JOIN ( SELECT id_contact, name AS org_contact FROM public.contact WHERE id_supchain_type = 328 ) ct ON ct.id_contact = pt.qm_org_contact_id
				LEFT JOIN ( SELECT gid_town, name_town FROM public.towns ) tw ON tw.gid_town = pt.town_id
			ORDER By tw.name_town ASC";
			
			$result = pg_query($conn, $sql_stats);

			$port_table ='';
			while($arr = pg_fetch_assoc($result)){
				$port_table .= '<tr>
					<td>'. $arr['id_townport'].'</td>
					<td>'. $arr['portname'].'</td>
					<td>'. $arr['port_type'].'</td>
					<td>' . $arr['port_code'] .'</td>
					<td>'. $arr['org_contact'].'</td>
					<td>'. $arr['name_town'].'</td>
					<td class="row_actions">';
					
						if($update_right == 1){
							$port_table .= '<a href="#" data-toggle="modal" onclick="portManagement(\'show\',\''. $arr['id_townport'] .'\',\'mod\');" data-target="#modalPort"><i class="fa fa-pen-square" aria-hidden="true"></i></a> ';
						}
						
						if($delete_right == 1){
							$port_table .= ' <a href="javascript:portManagement(\'del\',\''. $arr['id_townport'] .'\',\'\');" onclick="return confirm(\'Are you sure you want to delete : '. $arr['portname'].' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
						}
						
				    $port_table .= '</td>
				</tr>';
			}
			
			$dom=$port_table;
			
		break;
		
		
		
		case "manag_system_product":
		
			if(isset($_GET["product_name"])){
				$product_name = $_GET["product_name"];
			} else { $product_name = ""; }
	
			if(isset($_GET["id_culture"])){
				$id_culture = $_GET["id_culture"];
			} else { $id_culture = ""; }
	
			if(isset($_GET["product_code"])){
				$product_code = $_GET["product_code"];
				$product_code_req = " product_code='$product_code',";
				$product_code_name = " product_code,";
				$product_code_val = " '$product_code',";
			} else { $product_code = ""; $product_code_req = ""; $product_code_name = ""; $product_code_val = ""; }
	
			if(isset($_GET["product_desc"])){
				$product_desc = $_GET["product_desc"];
				$product_desc_req = " product_desc='$product_desc',";
				$product_desc_name = " product_desc,";
				$product_desc_val = " '$product_desc',";
			} else { $product_desc = ""; $product_desc_req = ""; $product_desc_name = ""; $product_desc_val = ""; }
			
			if(isset($_GET["measure_unit"])){
				$measure_unit = $_GET["measure_unit"];
			} else { $measure_unit = ""; }
			
			if(isset($_GET["product_type"])){
				$product_type = $_GET["product_type"];
			} else { $product_type = ""; }
			
			if(isset($_GET["product_hs"])){
				$product_hs = $_GET["product_hs"];
				$product_hs_req = ' "product_HS"=$product_hs,';
				$product_hs_name = ' "product_HS",';
				$product_hs_val = " $product_hs,";
			} else { $product_hs = ""; $product_hs_req = ""; $product_hs_name = ""; $product_hs_val = ""; }
			
			if(isset($_GET["product_cas"])){
				$product_cas = $_GET["product_cas"];
				$product_cas_req = " product_cas='$product_cas',";
				$product_cas_name = " product_cas,";
				$product_cas_val = " '$product_cas',";
			} else { $product_cas = ""; $product_cas_req = ""; $product_cas_name = ""; $product_cas_val = ""; }
			
			if(isset($_GET["product_name_de"])){
				$product_name_de = $_GET["product_name_de"];
				$product_name_de_req = " product_name_de='$product_name_de',";
				$product_name_de_name = " product_name_de,";
				$product_name_de_val = " '$product_name_de',";
			} else { $product_name_de = ""; $product_name_de_req = ""; $product_name_de_name = ""; $product_name_de_val = ""; }
	
			if(isset($_GET["q_ffa"])){
				$q_ffa = $_GET["q_ffa"];
				$q_ffa_req = " q_ffa='$q_ffa',";
				$q_ffa_name = " q_ffa,";
				$q_ffa_val = " '$q_ffa',";
			} else { $q_ffa = ""; $q_ffa_req = ""; $q_ffa_name = ""; $q_ffa_val = ""; }
	
			if(isset($_GET["q_mineraloil"])){
				$q_mineraloil = $_GET["q_mineraloil"];
				$q_mineraloil_req = " q_mineraloil='$q_mineraloil',";
				$q_mineraloil_name = " q_mineraloil,";
				$q_mineraloil_val = " '$q_mineraloil',";
			} else { $q_mineraloil = ""; $q_mineraloil_req = ""; $q_mineraloil_name = ""; $q_mineraloil_val = ""; }
	
			if(isset($_GET["q_humidity"])){
				$q_humidity = $_GET["q_humidity"];
				$q_humidity_req = " q_humidity='$q_humidity',";
				$q_humidity_name = " q_humidity,";
				$q_humidity_val = " '$q_humidity',";
			} else { $q_humidity = ""; $q_humidity_req = ""; $q_humidity_name = ""; $q_humidity_val = ""; }
	
			if(isset($_GET["c18_1"])){
				$c18_1 = $_GET["c18_1"];
				$c18_1_req = " c18_1='$c18_1',";
				$c18_1_name = " c18_1,";
				$c18_1_val = " '$c18_1',";
			} else { $c18_1 = ""; $c18_1_req = ""; $c18_1_name = ""; $c18_1_val = ""; }
	
			if(isset($_GET["c18_2"])){
				$c18_2 = $_GET["c18_2"];
				$c18_2_req = " c18_2='$c18_2',";
				$c18_2_name = " c18_2,";
				$c18_2_val = " '$c18_2',";
			} else { $c18_2 = ""; $c18_2_req = ""; $c18_2_name = ""; $c18_2_val = ""; }
	
			if(isset($_GET["q_impurity"])){
				$q_impurity = $_GET["q_impurity"];
				$q_impurity_req = " q_impurity='$q_impurity',";
				$q_impurity_name = " q_impurity,";
				$q_impurity_val = " '$q_impurity',";
			} else { $q_impurity = ""; $q_impurity_req = ""; $q_impurity_name = ""; $q_impurity_val = ""; }
	
			if(isset($_GET["q_dobi"])){
				$q_dobi = $_GET["q_dobi"];
				$q_dobi_req = " q_dobi='$q_dobi',";
				$q_dobi_name = " q_dobi,";
				$q_dobi_val = " '$q_dobi',";
			} else { $q_dobi = ""; $q_dobi_req = ""; $q_dobi_name = ""; $q_dobi_val = ""; }
	
			if(isset($_GET["q_m_i"])){
				$q_m_i = $_GET["q_m_i"];
				$q_m_i_req = " q_m_i='$q_m_i',";
				$q_m_i_name = " q_m_i,";
				$q_m_i_val = " '$q_m_i',";
			} else { $q_m_i = ""; $q_m_i_req = ""; $q_m_i_name = ""; $q_m_i_val = ""; }
	
			if(isset($_GET["id_product"])){
				$id_product = $_GET["id_product"];
			} else { $id_product = ""; }
	
			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.product(
					id_culture, $product_code_name product_name,  
					$product_desc_name measure_unit, 
					$product_hs_name $product_cas_name $product_name_de_name
					$q_ffa_name $q_mineraloil_name $q_humidity_name $c18_1_name $c18_2_name 
					$q_impurity_name $q_dobi_name $q_m_i_name product_type)
				VALUES ($id_culture, $product_code_val '$product_name',
					$product_desc_val $measure_unit,
					$product_hs_val $product_cas_val $product_name_de_val 
					$q_ffa_val $q_mineraloil_val $q_humidity_val $c18_1_val $c18_2_val 
					$q_impurity_val $q_dobi_val $q_m_i_val $product_type);";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.product SET id_culture=$id_culture, $product_code_req product_name='$product_name',  
				$product_desc_req measure_unit=$measure_unit, 
				$product_hs_req $product_cas_req $product_name_de_req $q_ffa_req $q_mineraloil_req $q_humidity_req 
				$c18_1_req $c18_2_req $q_impurity_req $q_dobi_req $q_m_i_req
				product_type=$product_type
				WHERE id_product=$id_product";
				
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "show_system_product":
		
			$id_product = $_GET["id_product"];
			
			$sql_stats = "SELECT * FROM product WHERE id_product=$id_product";
			$result = pg_query($conn, $sql_stats);

			$arr = pg_fetch_assoc($result);
			
			$dom=$arr['id_culture'].'#'.$arr['product_code'].'#'.$arr['product_name'].'#'.$arr['product_desc'].'#'.  
			$arr['measure_unit'].'#'.$arr['product_type'].'#'.$arr['product_HS'].'#'.$arr['product_cas'];
		
		break;
		
		
		case "delete_system_product":
		
			$id_product = $_GET["id_product"];
			
			$sql = "DELETE FROM public.product WHERE id_product = $id_product";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "culture":
		
			$update_right = $_GET["update_right"];
			$delete_right = $_GET["delete_right"];
			
			$sql_stats = "SELECT id_culture, name_culture FROM public.culture";
			
			$result = pg_query($conn, $sql_stats);

			$culture_table ='';
			while($arr = pg_fetch_assoc($result)){
				
				$name_culture = preg_replace('/\s+/', '', $arr['name_culture']);
				
				$culture_table .= '<tr>
					<td>'. $arr['id_culture'].'</td>
					<td>'. $name_culture.'</td>
					<td class="row_actions">';
					
					if($update_right == 1){
						$culture_table .= '<a href="#" data-toggle="modal" onclick="cultureManagement(\'show\',\''. $arr['id_culture'] .'\',\'mod\');" data-target="#modalCulture"><i class="fa fa-pen-square" aria-hidden="true"></i></a>';
					}
					
					if($delete_right == 1){
						$culture_table .= '<a href="javascript:cultureManagement(\'del\',\''. $arr['id_culture'] .'\',\'\');" onclick="return confirm(\'Are you sure you want to delete ' . $name_culture . ' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
					}
			
					$culture_table .= '</td>
				</tr>';
			}
			
			$dom=$culture_table;
		
		break;
		
		
		case "manag_system_culture":
		
			if(isset($_GET["name_culture"])){
				$name_culture = $_GET["name_culture"];
			} else { $name_culture = ""; }
		
			if(isset($_GET["id_culture"])){
				$id_culture = $_GET["id_culture"];
			} else { $id_culture = ""; }
	
			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sql = "Insert into culture ( name_culture ) values ( '$name_culture' )";
				
			} else
			if($conf == 'edit'){
				$sql = "Update culture set name_culture='$name_culture' where id_culture=$id_culture";
				
			} else {}
	
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "show_system_culture":
		
			$id_culture = $_GET["id_culture"];
			
			$sql_stats = "SELECT * FROM culture WHERE id_culture=$id_culture";
			$result = pg_query($conn, $sql_stats);

			$arr = pg_fetch_assoc($result);
			
			$dom=$arr['name_culture'];
		
		break;
		
		
		case "delete_system_culture":
		
			$id_culture = $_GET["id_culture"];
			
			$sql = "DELETE FROM public.culture WHERE id_culture = $id_culture";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "manag_system_regvalue":
		
			if(isset($_GET["id_register"])){
				$id_register = $_GET["id_register"];
			} else { $id_register = ""; }
			
			if(isset($_GET["cvalue"])){
				$cvalue = pg_escape_string($_GET["cvalue"]);
			} else { $cvalue = ""; }
			
			if(isset($_GET["cvaluede"])){
				$cvaluede = pg_escape_string($_GET["cvaluede"]);
				$cvaluede_req = " cvaluede = '{$cvaluede}',";
				$cvaluede_name = " cvaluede,";
				$cvaluede_val = " '{$cvaluede}',";
			} else { $cvaluede = ""; $cvaluede_req = ""; $cvaluede_name = ""; $cvaluede_val = ""; }
			
			if(isset($_GET["cvaluefr"])){
				$cvaluefr = pg_escape_string($_GET["cvaluefr"]);
				$cvaluefr_req = " cvaluefr = '{$cvaluefr}',";
				$cvaluefr_name = " cvaluefr,";
				$cvaluefr_val = " '{$cvaluefr}',";
			} else { $cvaluefr = ""; $cvaluefr_req = ""; $cvaluefr_name = ""; $cvaluefr_val = ""; }
			
			if(isset($_GET["cvaluept"])){
				$cvaluept = pg_escape_string($_GET["cvaluept"]);
				$cvaluept_req = " cvaluept = '{$cvaluept}',";
				$cvaluept_name = " cvaluept,";
				$cvaluept_val = " '{$cvaluept}',";
			} else { $cvaluept = ""; $cvaluept_req = ""; $cvaluept_name = ""; $cvaluept_val = ""; }
			
			if(isset($_GET["cvaluees"])){
				$cvaluees = pg_escape_string($_GET["cvaluees"]);
				$cvaluees_req = " cvaluees = '{$cvaluees}',";
				$cvaluees_name = " cvaluees,";
				$cvaluees_val = " '{$cvaluees}',";
			} else { $cvaluees = ""; $cvaluees_req = ""; $cvaluees_name = ""; $cvaluees_val = ""; }
			
			if(isset($_GET["cvaluesw"])){
				$cvaluesw = pg_escape_string($_GET["cvaluesw"]);
				$cvaluesw_req = " cvaluesw = '{$cvaluesw}',";
				$cvaluesw_name = " cvaluesw,";
				$cvaluesw_val = " '{$cvaluesw}',";
			} else { $cvaluesw = ""; $cvaluesw_req = ""; $cvaluesw_name = ""; $cvaluesw_val = ""; }
			
			if(isset($_GET["cvalueit"])){
				$cvalueit = pg_escape_string($_GET["cvalueit"]);
				$cvalueit_req = " cvalueit = '{$cvalueit}',";
				$cvalueit_name = " cvalueit,";
				$cvalueit_val = " '{$cvalueit}',";
			} else { $cvalueit = ""; $cvalueit_req = ""; $cvalueit_name = ""; $cvalueit_val = ""; }
			
			if(isset($_GET["comment"])){
				$comment = pg_escape_string($_GET["comment"]);
				$comment_req = " comment = '{$comment}',";
				$comment_name = " comment,";
				$comment_val = " '{$comment}',";
			} else { $comment = ""; $comment_req = ""; $comment_name = ""; $comment_val = ""; }
			
			if(isset($_GET["id_regvalue"])){
				$id_regvalue = $_GET["id_regvalue"];
			} else { $id_regvalue = ""; }
	
			$conf = $_GET["conf"];
			$id_user = $_SESSION["id_user"];
			$actual_date = gmdate("Y/m/d H:i");
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.regvalues(id_register, cvalue, $cvaluede_name $cvaluefr_name 
					$cvaluept_name $cvaluees_name $comment_name $cvaluesw_name $cvalueit_name created_by, created_date) 
				VALUES ($id_register, '{$cvalue}', $cvaluede_val $cvaluefr_val $cvaluept_val 
				  $cvaluees_val $comment_val $cvaluesw_val $cvalueit_val '$id_user', '$actual_date'); ";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.regvalues SET id_register = $id_register, cvalue = '{$cvalue}',  
				$cvaluede_req $cvaluefr_req $cvaluept_req $cvaluees_req       
				$comment_req $cvaluesw_req $cvalueit_req modified_by = '$id_user', modified_date = '$actual_date'
				WHERE id_regvalue = '$id_regvalue'";
				
			} else {}
	
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "show_system_regvalue":
		
			$id_regvalue = $_GET["id_regvalue"];
			
			$sql_stats = "SELECT id_register, cvalue, cvaluede, cvaluefr, cvaluept, cvaluees, cvaluesw, cvalueit, comment
				FROM regvalues 
			WHERE id_regvalue=$id_regvalue";
			$result = pg_query($conn, $sql_stats);
		
			$arr = pg_fetch_assoc($result);
			
			$dom=$arr['id_register'].'#'.$arr['cvalue'].'#'.$arr['cvaluede'].'#'.$arr['cvaluefr'].'#'.$arr['cvaluept'].'#'.$arr['cvaluees'].'#'.$arr['cvaluesw'].'#'.$arr['cvalueit'].'#'.$arr['comment'];
		
		break;
		
		
		case "delete_system_regvalue":
		
			$id_regvalue = $_GET["id_regvalue"];
			
			$sql = "DELETE FROM public.regvalues WHERE id_regvalue = $id_regvalue";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "manag_system_register":
		
			if(isset($_GET["regcode"])){
				$regcode = $_GET["regcode"];
			} else { $regcode = ""; }
			
			if(isset($_GET["regname"])){
				$regname = $_GET["regname"];
			} else { $regname = ""; }
			
			if(isset($_GET["regnamede"])){
				$regnamede = $_GET["regnamede"];
				$regnamede_req = " regnamede = '$regnamede',";
				$regnamede_name = " regnamede,";
				$regnamede_val = " '$regnamede',";
			} else { $regnamede = ""; $regnamede_req = ""; $regnamede_name = ""; $regnamede_val = ""; }
			
			if(isset($_GET["regnamefr"])){
				$regnamefr = $_GET["regnamefr"];
				$regnamefr_req = " regnamefr = '$regnamefr',";
				$regnamefr_name = " regnamefr,";
				$regnamefr_val = " '$regnamefr',";
			} else { $regnamefr = ""; $regnamefr_req = ""; $regnamefr_name = ""; $regnamefr_val = ""; }
			
			if(isset($_GET["regnamept"])){
				$regnamept = $_GET["regnamept"];
				$regnamept_req = " regnamept = '$regnamept',";
				$regnamept_name = " regnamept,";
				$regnamept_val = " '$regnamept',";
			} else { $regnamept = ""; $regnamept_req = ""; $regnamept_name = ""; $regnamept_val = ""; }
			
			if(isset($_GET["regnamees"])){
				$regnamees = $_GET["regnamees"];
				$regnamees_req = " regnamees = '$regnamees',";
				$regnamees_name = " regnamees,";
				$regnamees_val = " '$regnamees',";
			} else { $regnamees = ""; $regnamees_req = ""; $regnamees_name = ""; $regnamees_val = ""; }
			
			
			if(isset($_GET["id_register"])){
				$id_register = $_GET["id_register"];
			} else { $id_register = ""; }
	
			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.registers (regname,  $regnamede_name  $regnamefr_name  $regnamept_name  $regnamees_name regcode) 
				VALUES ('$regname', $regnamede_val $regnamefr_val $regnamept_val $regnamees_val '$regcode');";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.registers SET regname = '$regname', $regnamede_req 
					$regnamefr_req $regnamept_req $regnamees_req
					regcode = '$regcode'
				WHERE id_register = $id_register";
				
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "show_system_register":
		
			$id_register = $_GET["id_register"];
			
			$sql_stats = "SELECT regname, regnamede, regnamefr, regnamept, regnamees, regcode
				FROM registers 
			WHERE id_register=$id_register";
			$result = pg_query($conn, $sql_stats);
		
			$arr = pg_fetch_assoc($result);
			
			$dom=$arr['regname'].'#'.$arr['regnamede'].'#'.$arr['regnamefr'].'#'.$arr['regnamept'].'#'.$arr['regnamees'].'#'.$arr['regcode'];
		
		break;
		
		
		case "delete_system_register":
		
			$id_register = $_GET["id_register"];
			
			$sql = "DELETE FROM public.registers WHERE id_register = $id_register";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "country_table":
		
			$sql_stats = "SELECT name_country, code, capitale, number_population, area, capitale_x, capitale_y, culture, id_country
				FROM public.country";
			
			$result = pg_query($conn, $sql_stats);

			$country_table ='';
			while($arr = pg_fetch_assoc($result)){
				
				$name_country = preg_replace('/\s+/', '', $arr['name_country']);
				
				$country_table .= '<tr>
					<td>'. $arr['code'].'</td>
					<td>'. $name_country.'</td>
					<td>'. $arr['capitale'].'</td>
					<td>'. $arr['number_population'].'</td>
					<td>'. $arr['area'].'</td>
					<td>'. $arr['culture'].'</td>
					<td class="row_actions">
					  <a href="#" data-toggle="modal" onclick="countryManagement(\'show\',\''. $arr['id_country'] .'\',\'mod\');" data-target="#modalCountry"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
					  <a href="javascript:countryManagement(\'del\',\''. $arr['id_country'] .'\',\'\');" onclick="return confirm(\'Are you sure you want to delete ' . $name_country . ' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
				    </td>
				</tr>';
			}
			
			$dom=$country_table;
		
		break;
		
		
		case "manag_system_country":
		
			if(isset($_GET["name_country"])){
				$name_country = $_GET["name_country"];
			} else { $name_country = ""; }
			
			if(isset($_GET["code"])){
				$code = $_GET["code"];
			} else { $code = ""; }
			
			if(isset($_GET["capitale"])){
				$capitale = $_GET["capitale"];
			} else { $capitale = ""; }
			
			if(isset($_GET["capitale_x"])){
				$capitale_x = $_GET["capitale_x"];
				$capitale_x_req = " capitale_x = '$capitale_x',";
				$capitale_x_name = " capitale_x,";
				$capitale_x_val = " '$capitale_x',";
			} else { $capitale_x = ""; $capitale_x_req = ""; $capitale_x_name = ""; $capitale_x_val = ""; }
			
			if(isset($_GET["capitale_y"])){
				$capitale_y = $_GET["capitale_y"];
				$capitale_y_req = " capitale_y = '$capitale_y',";
				$capitale_y_name = " capitale_y,";
				$capitale_y_val = " '$capitale_y',";
			} else { $capitale_y = ""; $capitale_y_req = ""; $capitale_y_name = ""; $capitale_y_val = ""; }
			
			if(isset($_GET["number_population"])){
				$number_population = $_GET["number_population"];
				$number_population_req = " number_population = '$number_population',";
				$number_population_name = " number_population,";
				$number_population_val = " '$number_population',";
			} else { $number_population = ""; $number_population_req = ""; $number_population_name = ""; $number_population_val = ""; }
			
			if(isset($_GET["area"])){
				$area = $_GET["area"];
				$area_req = " area = '$area',";
				$area_name = " area,";
				$area_val = " '$area',";
			} else { $area = ""; $area_req = ""; $area_name = ""; $area_val = ""; }
			
			if(isset($_GET["culture"])){
				$culture = $_GET["culture"];
				$culture_req = " culture = '$culture',";
				$culture_name = " culture,";
				$culture_val = " '$culture',";
			} else { $culture = ""; $culture_req = ""; $culture_name = ""; $culture_val = ""; }
			
			
			if(isset($_GET["id_country"])){
				$id_country = $_GET["id_country"];
			} else { $id_country = ""; }
	
			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.country(name_country, code, $number_population_name $area_name 
					$capitale_x_name $capitale_y_name $culture_name capitale) 
				VALUES('$name_country', '$code',  $number_population_val $area_val 
					$capitale_x_val $capitale_y_val $culture_val '$capitale');";
			
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.country SET name_country = '$name_country', code = '$code', $number_population_req 
					$area_req $capitale_x_req $capitale_y_req $culture_req
					capitale = '$capitale'
				WHERE id_country = $id_country";
				
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "show_system_country":
		
			$id_country = $_GET["id_country"];
			
			$sql_stats = "SELECT name_country, code, number_population, area, capitale_x, capitale_y, culture, capitale
				FROM country 
			WHERE id_country=$id_country";
			$result = pg_query($conn, $sql_stats);
		
			$arr = pg_fetch_assoc($result);
			
			$dom=$arr['name_country'].'#'.$arr['code'].'#'.$arr['number_population'].'#'.$arr['area'].'#'.$arr['capitale_x'].'#'.$arr['capitale_y'].'#'.$arr['culture'].'#'.$arr['capitale'];
		
		break;
		
		
		case "delete_system_country":
		
			$id_country = $_GET["id_country"];
			
			$sql = "DELETE FROM public.country WHERE id_country = $id_country";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "town_table":
		
			$id_user = $_SESSION['id_user'];
			$user_country = $_SESSION['id_country'];
			
			if(isset($_GET["id_country"]) && $_GET["id_country"]!=""){
				$id_country = $_GET["id_country"];
				$cond=" and c.id_country=$id_country";
			} else { $cond=" and c.id_country = $user_country"; }
			
			if($id_user == 158) {
				$sql_stats = "SELECT t.gid_town, t.name_town, c.name_country, c.code, t.x, t.y, t.timezone, t.population, t.code_town, t.region1, t.region2, t.region3, t.region4, t.language, t.postcode, t.iso 
					from towns t, country c 
				where t.id_country=c.id_country $cond ";
			} else { 
				$sql_stats = "SELECT t.gid_town, t.name_town, c.name_country, c.code, t.x, t.y, t.timezone, t.population, t.code_town, t.region1, t.region2, t.region3, t.region4, t.language, t.postcode, t.iso 
					from towns t, country c 
				where t.id_country=c.id_country $cond";
			}
			
			$result = pg_query($conn, $sql_stats);

			$town_table ='';
			while($arr = pg_fetch_assoc($result)){
				
				$name_town = preg_replace('/\s+/', '', $arr['name_town']);
				
				$town_table .= '<tr>
					<td>'. $arr['code_town'].'</td>
					<td>'. $name_town.'</td>
					<td>'. $arr['region1'].'</td>
					<td>'. $arr['name_country'].'</td>
					<td>'. $arr['iso'].'</td>
					<td>'. $arr['language'].'</td>
					<td class="row_actions">
					  <a href="#" data-toggle="modal" onclick="townManagement(\'show\',\''. $arr['gid_town'] .'\',\'mod\');" data-target="#modalTown"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
					  <a href="javascript:townManagement(\'del\',\''. $arr['gid_town'] .'\',\'\');" onclick="return confirm(\'Are you sure you want to delete ' . $name_town . ' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
				    </td>
				</tr>';
			}
		
		
			$sql_country = "SELECT name_country, id_country FROM public.country";
			$r_country = pg_query($conn, $sql_country);

			$country_list ='<option value="">---</option>';
			while($arr_country = pg_fetch_assoc($r_country)){
				$name_country = preg_replace('/\s+/', '', $arr_country['name_country']);
				$country_list .= '<option value="'. $arr_country['id_country'].'">'. $name_country .'</option>';
			}

			$dom=$town_table.'??'.$country_list;
		
		break; 
		
		
		case "town_country":
			
			$id_country = $_GET["id_country"];
			
			if($id_country!=0){ $list=0;
				$cond=" WHERE id_country=$id_country";
			} else { $cond=""; $list=1; }
			
			$sql_stats = "SELECT name_country, id_country FROM public.country $cond";
			$result = pg_query($conn, $sql_stats);

			if($list==1){
				$country_list ='<option value="">---</option>';
				while($arr = pg_fetch_assoc($result)){
					
					$name_country = preg_replace('/\s+/', '', $arr['name_country']);
					
					$country_list .= '<option value="'. $arr['id_country'].'">'. $name_country .'</option>';
				}
				
				$dom=$country_list;
				
			} else {
				
				$arr = pg_fetch_assoc($result);
				$name_country = preg_replace('/\s+/', '', $arr['name_country']);
				
				$dom=$name_country;
			}
			
		
		break;
		
		
		case "manag_system_town":
		
			if(isset($_GET["name_country"])){
				$name_country = str_replace("'", ' ', $_GET["name_country"]);
			} else { $name_country = ""; }
			
			if(isset($_GET["id_country"])){
				$id_country = $_GET["id_country"];
			} else { $id_country = ""; }
			
			if(isset($_GET["name_town"])){
				$name_town = $_GET["name_town"];
			} else { $name_town = ""; }
		
			if(isset($_GET["code_town"])){
				$code_town = $_GET["code_town"];
				$code_town_req = " code_town = '$code_town',";
				$code_town_name = " code_town,";
				$code_town_val = " '$code_town',";
			} else { $code_town = ""; $code_town_req = ""; $code_town_name = ""; $code_town_val = ""; }
			
			if(isset($_GET["x"])){
				$x = $_GET["x"];
				$x_req = " x = '$x',";
				$x_name = " x,";
				$x_val = " '$x',";
			} else { $x = ""; $x_req = ""; $x_name = ""; $x_val = ""; }
			
			if(isset($_GET["y"])){
				$y = $_GET["y"];
				$y_req = " y = '$y',";
				$y_name = " y,";
				$y_val = " '$y',";
			} else { $y = ""; $y_req = ""; $y_name = ""; $y_val = ""; }
			
			if(isset($_GET["timezone"])){
				$timezone = $_GET["timezone"];
				$timezone_req = " timezone = '$timezone',";
				$timezone_name = " timezone,";
				$timezone_val = " '$timezone',";
			} else { $timezone = ""; $timezone_req = ""; $timezone_name = ""; $timezone_val = ""; }
			
			if(isset($_GET["population"])){
				$population = $_GET["population"];
				$population_req = " population = '$population',";
				$population_name = " population,";
				$population_val = " '$population',";
			} else { $population = ""; $population_req = ""; $population_name = ""; $population_val = ""; }
		
			if(isset($_GET["description_en"])){
				$description_en = $_GET["description_en"];
				$description_en_req = " description_en = '$description_en',";
				$description_en_name = " description_en,";
				$description_en_val = " '$description_en',";
			} else { $description_en = ""; $description_en_req = ""; $description_en_name = ""; $description_en_val = ""; }
		
			if(isset($_GET["description_de"])){
				$description_de = $_GET["description_de"];
				$description_de_req = " description_de = '$description_de',";
				$description_de_name = " description_de,";
				$description_de_val = " '$description_de',";
			} else { $description_de = ""; $description_de_req = ""; $description_de_name = ""; $description_de_val = ""; }
		
			if(isset($_GET["description_fr"])){
				$description_fr = $_GET["description_fr"];
				$description_fr_req = " description_fr = '$description_fr',";
				$description_fr_name = " description_fr,";
				$description_fr_val = " '$description_fr',";
			} else { $description_fr = ""; $description_fr_req = ""; $description_fr_name = ""; $description_fr_val = ""; }
		
			if(isset($_GET["description_pt"])){
				$description_pt = $_GET["description_pt"];
				$description_pt_req = " description_pt = '$description_pt',";
				$description_pt_name = " description_pt,";
				$description_pt_val = " '$description_pt',";
			} else { $description_pt = ""; $description_pt_req = ""; $description_pt_name = ""; $description_pt_val = ""; }
		
			if(isset($_GET["description_es"])){
				$description_es = $_GET["description_es"];
				$description_es_req = " description_es = '$description_es',";
				$description_es_name = " description_es,";
				$description_es_val = " '$description_es',";
			} else { $description_es = ""; $description_es_req = ""; $description_es_name = ""; $description_es_val = ""; }
		
			if(isset($_GET["region1"])){
				$region1 = $_GET["region1"];
				$region1_req = " region1 = '$region1',";
				$region1_name = " region1,";
				$region1_val = " '$region1',";
			} else { $region1 = ""; $region1_req = ""; $region1_name = ""; $region1_val = ""; }
		
			if(isset($_GET["region2"])){
				$region2 = $_GET["region2"];
				$region2_req = " region2 = '$region2',";
				$region2_name = " region2,";
				$region2_val = " '$region2',";
			} else { $region2 = ""; $region2_req = ""; $region2_name = ""; $region2_val = ""; }
		
			if(isset($_GET["region3"])){
				$region3 = $_GET["region3"];
				$region3_req = " region3 = '$region3',";
				$region3_name = " region3,";
				$region3_val = " '$region3',";
			} else { $region3 = ""; $region3_req = ""; $region3_name = ""; $region3_val = ""; }
		
			if(isset($_GET["region4"])){
				$region4 = $_GET["region4"];
				$region4_req = " region4 = '$region4',";
				$region4_name = " region4,";
				$region4_val = " '$region4',";
			} else { $region4 = ""; $region4_req = ""; $region4_name = ""; $region4_val = ""; }
		
			if(isset($_GET["iso"])){
				$iso = $_GET["iso"];
				$iso_req = " iso = '$iso',";
				$iso_name = " iso,";
				$iso_val = " '$iso',";
			} else { $iso = ""; $iso_req = ""; $iso_name = ""; $iso_val = ""; }
		
			if(isset($_GET["language"])){
				$language = $_GET["language"];
				$language_req = " language = '$language',";
				$language_name = " language,";
				$language_val = " '$language',";
			} else { $language = ""; $language_req = ""; $language_name = ""; $language_val = ""; }
		
			if(isset($_GET["postcode"])){
				$postcode = $_GET["postcode"];
				$postcode_req = " postcode = '$postcode',";
				$postcode_name = " postcode,";
				$postcode_val = " '$postcode',";
			} else { $postcode = ""; $postcode_req = ""; $postcode_name = ""; $postcode_val = ""; }
		
			if(isset($_GET["suburb"])){
				$suburb = $_GET["suburb"];
				$suburb_req = " suburb = '$suburb',";
				$suburb_name = " suburb,";
				$suburb_val = " '$suburb',";
			} else { $suburb = ""; $suburb_req = ""; $suburb_name = ""; $suburb_val = ""; }
		
			if(isset($_GET["utc"])){
				$utc = $_GET["utc"];
				$utc_req = " utc = '$utc',";
				$utc_name = " utc,";
				$utc_val = " '$utc',";
			} else { $utc = ""; $utc_req = ""; $utc_name = ""; $utc_val = ""; }
		
			if(isset($_GET["dst"])){
				$dst = $_GET["dst"];
				$dst_req = " dst = '$dst',";
				$dst_name = " dst,";
				$dst_val = " '$dst',";
			} else { $dst = ""; $dst_req = ""; $dst_name = ""; $dst_val = ""; }
		
			
			if(isset($_GET["gid_town"])){
				$gid_town = $_GET["gid_town"];
			} else { $gid_town = ""; }
	
			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sqlID="SELECT MAX(gid_town)+1 AS new_id FROM towns";
				$resultID = pg_query($conn, $sqlID);
				$arrID = pg_fetch_assoc($resultID);
				$new_id = $arrID['new_id'];
				
				$sql = "INSERT INTO public.towns(gid_town, id_town, name_town, name_country, $x_name $y_name $timezone_name $population_name
				$description_en_name $description_de_name $description_fr_name $description_pt_name $description_es_name 
				$code_town_name $region1_name $region2_name $region3_name $region4_name $iso_name $language_name $postcode_name 
				$suburb_name $utc_name $dst_name id_country) 
				VALUES ($new_id, $new_id, '$name_town', '$name_country', $x_val $y_val $timezone_val $population_val $description_en_val
				$description_de_val $description_fr_val $description_pt_val $description_es_val $code_town_val 
				$region1_val $region2_val $region3_val $region4_val $iso_val $language_val $postcode_val $suburb_val 
				$utc_val $dst_val '$id_country');";
			
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.towns SET name_town = '$name_town', name_country = '$name_country', $x_req $y_req $id_country_req
				$timezone_req $population_req $description_en_req $description_de_req $description_fr_req $description_pt_req $description_es_req
				$code_town_req $region1_req $region2_req $region3_req $region4_req $iso_req $language_req $postcode_req $suburb_req $utc_req
				$dst_req id_country = $id_country
				WHERE gid_town = $gid_town ";
		
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "show_system_town":
		
			$gid_town = $_GET["gid_town"];
			
			$sql_stats = "SELECT name_town, name_country, x, y, id_country, timezone, population, description_en, description_de,
			description_fr, description_pt, description_es, code_town, region1, region2, region3, region4, iso, language, postcode,
			suburb, utc, dst 
				FROM public.towns
			WHERE  gid_town = $gid_town";
			$result = pg_query($conn, $sql_stats);
		
			$arr = pg_fetch_assoc($result);
			
			$dom=$arr['name_town'].'#'.$arr['name_country'].'#'.$arr['x'].'#'.$arr['y'].'#'.$arr['id_country'].'#'.$arr['timezone'].'#
			'.$arr['population'].'#'.$arr['description_en'].'#'.$arr['description_de'].'#'.$arr['description_fr'].'#'.$arr['description_pt'].'#
			'.$arr['description_es'].'#'.$arr['code_town'].'#'.$arr['region1'].'#'.$arr['region2'].'#'.$arr['region3'].'#'.$arr['region4'].'#
			'.$arr['iso'].'#'.$arr['language'].'#'.$arr['postcode'].'#'.$arr['suburb'].'#'.$arr['utc'].'#'.$arr['dst'];
		
		break;
		
		
		case "delete_system_town":
		
			$gid_town = $_GET["gid_town"];
			
			$sql = "DELETE FROM public.towns WHERE gid_town = $gid_town";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "save_edited_cus_ref_number":
		
			$id_ord_order = $_GET["id_ord_order"];
			$customer_reference_nr = $_GET["customer_reference_nr"];
			
			$sql = "UPDATE public.ord_order SET customer_reference_nr = '$customer_reference_nr' WHERE id_ord_order = $id_ord_order ";
			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_stats = "SELECT customer_reference_nr FROM public.v_order WHERE  id_ord_order = $id_ord_order";
				$result_stats = pg_query($conn, $sql_stats);
				$arr = pg_fetch_assoc($result_stats);
			
				$dom='1##'.$arr['customer_reference_nr'];
				
			} else {
				$dom='0##0';
			}
			
		break;
		
		
		case "save_edited_cus_notes":
		
			$id_ord_order = $_GET["id_ord_order"];
			$notes_customer = $_GET["notes_customer"];
			
			$sql = "UPDATE public.ord_order SET notes_customer = '$notes_customer' WHERE id_ord_order = $id_ord_order ";
			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_stats = "SELECT notes_customer FROM public.v_order WHERE  id_ord_order = $id_ord_order";
				$result_stats = pg_query($conn, $sql_stats);
				$arr = pg_fetch_assoc($result_stats);
			
				$dom='1##'.$arr['notes_customer'];
				
			} else {
				$dom='0##0';
			}
			
		break;
		
		
		case "save_edited_sum_status":
		
			$id_ord_order = $_GET["id_ord_order"];
			$status_id = $_GET["status_id"];
			
			if($status_id==393){
				$sql_stats = 'SELECT * FROM public."DeleteOrder"('.$id_ord_order.')';
				$result_stats = pg_query($conn, $sql_stats);
				
				if ($result_stats) {
					$dom=1;
				} else {
					$dom='0##0';
				}
			} else {
				$modified_by = $_SESSION["id_user"];
				$modify_date = gmdate("Y/m/d H:i");
			
				$sql = "UPDATE public.ord_order SET status_id = '$status_id', 
					modified_by=$modified_by, modify_date='$modify_date'
				WHERE id_ord_order = $id_ord_order ";
				$result = pg_query($conn, $sql);

				if ($result) {
					$sql_stats = "SELECT status_name FROM public.v_order WHERE  id_ord_order = $id_ord_order";
					$result_stats = pg_query($conn, $sql_stats);
					$arr = pg_fetch_assoc($result_stats);
				
					$dom='1##'.$arr['status_name'];
					
				} else {
					$dom='0##0';
				}
			}
			
		break;
		
		
		case "save_edited_sum_pipeline":
		
			$id_ord_order = $_GET["id_ord_order"];
			$pipeline_id = $_GET["pipeline_id"];
			
			$modified_by = $_SESSION["id_user"];
			$modify_date = gmdate("Y/m/d H:i");
			
			$sql = "UPDATE public.ord_order SET pipeline_id = '$pipeline_id', 
				modified_by=$modified_by, modify_date='$modify_date'
			WHERE id_ord_order = $id_ord_order ";
			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_stats = "SELECT pipeline_name FROM public.v_order WHERE  id_ord_order = $id_ord_order";
				$result_stats = pg_query($conn, $sql_stats);
				$arr = pg_fetch_assoc($result_stats);
			
				$dom='1##'.$arr['pipeline_name'];
				
			} else {
				$dom='0##0';
			}
			
		break;
		
		
		case "save_edited_int_notes":
		
			$id_ord_order = $_GET["id_ord_order"];
			$notes_internal = $_GET["notes_internal"];
			
			$modified_by = $_SESSION["id_user"];
			$modify_date = gmdate("Y/m/d H:i");
			
			$sql = "UPDATE public.ord_order SET notes_internal = '$notes_internal',
				modified_by = $modified_by, modify_date = '$modify_date'
			WHERE id_ord_order = $id_ord_order ";
			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_stats = "SELECT notes_internal FROM public.v_order WHERE  id_ord_order = $id_ord_order";
				$result_stats = pg_query($conn, $sql_stats);
				$arr = pg_fetch_assoc($result_stats);
			
				$dom='1##'.$arr['notes_internal'];
				
			} else {
				$dom='0##0';
			}
			
		break;
		
		
		case "save_edited_order_nr_old":
		
			$id_ord_order = $_GET["id_ord_order"];
			$order_nr_old = $_GET["order_nr_old"];
			
			$modified_by = $_SESSION["id_user"];
			$modify_date = gmdate("Y/m/d H:i");
			
			$sql = "UPDATE public.ord_order SET order_nr_old = '$order_nr_old',
				modified_by = $modified_by, modify_date = '$modify_date'
			WHERE id_ord_order = $id_ord_order ";
			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_stats = "SELECT order_nr FROM public.v_order WHERE  id_ord_order = $id_ord_order";
				$result_stats = pg_query($conn, $sql_stats);
				$arr = pg_fetch_assoc($result_stats);
			
				$dom='1##'.$arr['order_nr'];
				
			} else {
				$dom='0##0';
			}
			
		break;
		
		
		
		case "cus_po_document":
		
			$document_desc = $_GET["document_desc"];
			
			if($_GET["ord_order_id"]){
				$ord_order_id = $_GET["ord_order_id"];
				$req_ord_order_id = " ord_order_id,";
				$val_ord_order_id = " $ord_order_id,";
			} else { $val_ord_order_id = ""; $req_ord_order_id = ""; }
			
			if($_GET["ord_schedule_id"]){
				$ord_schedule_id = $_GET["ord_schedule_id"];
				$req_ord_schedule_id = " ord_schedule_id,";
				$val_ord_schedule_id = " $ord_schedule_id,";
			} else { $val_ord_schedule_id = ""; $req_ord_schedule_id = ""; }
			
			$doc_type_id = $_GET["doc_type_id"];
			
			$created_by = $_SESSION['id_user'];
  
			$doc_filename = $_GET["doc_filename"];
			
			
			$user_id = $_SESSION["id_user"];
			$doc_Date = gmdate("Y-m-d");
			$created_date = gmdate("Y-m-d H:i:s");
			
			$sql = "INSERT INTO public.ord_document($req_ord_order_id doc_type_id, doc_Date, doc_filename, 
			  document_desc, $req_ord_schedule_id created_by, user_id, created_date, active) 
				VALUES ($val_ord_order_id $doc_type_id, '$doc_Date', '$doc_filename', 
			  '$document_desc', $val_ord_schedule_id $created_by, $created_by, '$created_date', 1)
			 RETURNING id_document";
	
			$result = pg_query($conn, $sql);

			if ($result) {
				
				$arr = pg_fetch_assoc($result);
				
				$id_document = $arr['id_document'];
				
				$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
				VALUES ($id_document, $user_id, 1)";
				pg_query($conn, $sql2);				
			
				$dom=1;
				
			} else {
				$dom=0;
			}
		
		break;
		
		
		
		case "document_loading":
			
			$doc_edit = $_GET['doc_edit'];
			$id_ord_order = $_GET['id_ord_order'];
			$id_ord_schedule = $_GET['id_ord_schedule'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
			if($id_user_supchain_type==312){
				$cond=" AND sm=1 ";
				
			} else {
				if($id_supchain_type==110){
					$cond=" AND client=1 ";
					
				} else
				if($id_supchain_type==112){
					$cond=" AND importer=1 ";
				
				} else
				if($id_supchain_type==113){
					$cond=" AND exporter=1 ";
			
				} else 
				if($id_supchain_type==289){
					$cond=" AND freight_agent=1 ";
				} 
			}
			
			
			$position = $_GET['position'];  
			$type = $_GET['type'];
			if($type == 'crm'){
				if($position==""){
					$type_req = " WHERE sequence_nr <= 3 $cond ORDER BY sequence_nr";
				} else {
					$type_req = " WHERE sequence_nr <= 6 AND sequence_nr >= 4 $cond ORDER BY sequence_nr";
				}
				
			} else {
				$type_req = " WHERE sequence_nr <= 20 AND sequence_nr >= 7 $cond ORDER BY sequence_nr";
			}
			
			$sql = "SELECT id_doc_type, doctype_name, sequence_nr FROM public.ord_doc_type $type_req";
			$result = pg_query($conn, $sql);
			
			$list_type = '<option value="">--</option>';
			while ($arr = pg_fetch_assoc($result)){
				$list_type .= '<option value="'. $arr['id_doc_type'] .'">'. $arr['sequence_nr'] .'-'. $arr['doctype_name'] .'</option>';
			}
			
			if($doc_edit == 1){
				$doc = '<div class="bg-success" id="document_toggler">
					<a href="#" onclick="openDocUploader();"><i class="fa fa-file-text-o"></i></a>
				</div>';
			} else {
				$doc = '';
			}
			
			
			$dom = '<div class="row">
				'.$doc.'
			
				<div id="docUploaderCt" class="col-md-12 hide">
					<form id="upload_document" class="dropzone" style="background:#efefef; overflow:hidden; padding:0 0 10px 10px;" action="upload_shipping_doc.php" method="post" enctype="multipart/form-data">
						
						<input id="po_doc_order_id" name="ord_order_id" type="hidden" value="'.$id_ord_order.'" />
						<input id="po_doc_schedule_id" name="ord_schedule_id" type="hidden" value="'.$id_ord_schedule.'" />
						
						<div class="form-group" style="padding-right:10px;">
							<label class="ord_sum_label">Document type</label><br/>
							<select id="po_doc_type_id" name="doc_type_id" onchange="docTypeSelect(this.value);" class="form-control">
								'.$list_type.'
							</select>
						</div>
						
						<div class="form-group file-area" style="padding-right:10px;">
							<input accept=".pdf" type="file" name="image" id="po_document" onchange="docFile(this.value);" />
							<div class="file-dummy po_bg_default">
							  <div id="po_success" class="success hide">Great, your file is selected. Keep on.</div>
							  <div id="po_default" class="default"><i class="fa fa-file-pdf-o"></i> Drop files here or click to upload.</div>
							</div>
						</div>
						
						<div class="form-group" style="padding-right:10px;">
							<label class="ord_sum_label">Description</label><br/>
							<input type="text" name="document_desc" class="form-control" id="po_document_desc" />
						</div>
						
						<input type="hidden" name="new_name" id="po_doc_newName" value="" />
						<input type="hidden" name="po_type" id="po_type" value="'.$type.'" />
						<button id="uploadDocBtn" onclick="documents();" disabled class="btn btn-primary"><i class="fa fa-upload"></i></button>
					</form>
				</div>
				
				<div class="col-md-12">
					<div class="tabs-container">
                        <ul class="nav cnt_nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#po_document_tab"> 
								<i class="fa fa-file-text fa-fw"></i>
							</a></li>
                            <li class=""><a data-toggle="tab" href="#po_email_tab">
								<i class="fa fa-envelope fa-fw"></i>
							</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="po_document_tab" class="tab-pane active">
								<div class="panel-body" style="padding:10px 5px;">
									<ul id="po_doc_list" style="padding-left:0;"></ul>
								</div>
                            </div>
                            <div id="po_email_tab" class="tab-pane">
                                <div class="panel-body" style="padding:10px 5px;">
                                    <ul id="po_mail_list" style="padding-left:0;"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>';
			
		break;
		
		
		case "document_list":

			if($_GET['id_ord_order']!=""){
				$order_id = $_GET['id_ord_order'];
			} else { $order_id=""; }
			
			if(($_GET['ord_schedule_id']!="")&&($_GET['ord_schedule_id']!=0)){
				$schedule_id = $_GET['ord_schedule_id'];
			} else { $schedule_id=""; }
			
			$type = $_GET['type']; 
			$position = $_GET['position']; 
			
			$id_contact = $_SESSION['id_contact'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
	
			if($id_user_supchain_type==312){
				if($type=='crm'){
					if($position==""){
						$sql="Select * from v_documents where ord_order_id=$order_id and sm=1 and active=1 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
						$sql_mail="Select * from v_documents where ord_order_id=$order_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
					} else {
						$sql="Select * from v_documents where ord_order_id=$order_id and sm=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
						$sql_mail="Select * from v_documents where ord_order_id=$order_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
					}
					
				} else {
					$sql="Select * from v_Documents where ord_schedule_id=$schedule_id and sm=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 ORDER BY sequence_nr ASC";
					$sql_mail="Select * from v_Documents where ord_schedule_id=$schedule_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' ORDER BY created_date DESC";
				}
				
			} else {
				if($id_supchain_type==110){
					if($type=='crm'){
						if($position==""){
							$sql="Select * from v_documents where ord_order_id=$order_id and client=1 and active=1 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
							$sql_mail="Select * from v_documents where ord_order_id=$order_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
						} else {
							$sql="Select * from v_documents where ord_order_id=$order_id and client=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
							$sql_mail="Select * from v_documents where ord_order_id=$order_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
						}
						
					} else {
						$sql="Select * from v_Documents where ord_schedule_id=$schedule_id and client=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 ORDER BY sequence_nr ASC";
						$sql_mail="Select * from v_Documents where ord_schedule_id=$schedule_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' ORDER BY created_date DESC";
					}
					
				} else
				if($id_supchain_type==112){
					if($type=='crm'){
						if($position==""){
							$sql="Select * from v_documents where ord_order_id=$order_id and importer=1 and active=1 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
							$sql_mail="Select * from v_documents where ord_order_id=$order_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
						} else {
							$sql="Select * from v_documents where ord_order_id=$order_id and importer=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
							$sql_mail="Select * from v_documents where ord_order_id=$order_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
						}
						
					} else {
						$sql="Select * from v_Documents where ord_schedule_id=$schedule_id and importer=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 ORDER BY sequence_nr ASC";
						$sql_mail="Select * from v_Documents where ord_schedule_id=$schedule_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' ORDER BY created_date DESC";
					}
				
				} else
				if($id_supchain_type==113){
					if($type=='crm'){
						if($position==""){
							$sql="Select * from v_documents where ord_order_id=$order_id and exporter=1 and active=1 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
							$sql_mail="Select * from v_documents where ord_order_id=$order_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
						} else {
							$sql="Select * from v_documents where ord_order_id=$order_id and exporter=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
							$sql_mail="Select * from v_documents where ord_order_id=$order_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
						}
						
					} else {
						$sql="Select * from v_Documents where ord_schedule_id=$schedule_id and exporter=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 ORDER BY sequence_nr ASC";
						$sql_mail="Select * from v_Documents where ord_schedule_id=$schedule_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' ORDER BY created_date DESC";
					}
						
				} else 
				if($id_supchain_type==289){
					if($type=='crm'){
						if($position==""){
							$sql="Select * from v_documents where ord_order_id=$order_id and freight_agent=1 and active=1 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
							$sql_mail="Select * from v_documents where ord_order_id=$order_id AND doc_type_id = 80 AND doc_type_id = 83 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
						} else {
							$sql="Select * from v_documents where ord_order_id=$order_id and freight_agent=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 AND ord_schedule_id iS NULL ORDER BY sequence_nr ASC";
							$sql_mail="Select * from v_documents where ord_order_id=$order_id and AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' AND ord_schedule_id iS NULL ORDER BY created_date DESC";
						}
						
					} else {
						$sql="Select * from v_Documents where ord_schedule_id=$schedule_id and freight_agent=1 and active=1 and sequence_nr <= 20 and sequence_nr >= 4 ORDER BY sequence_nr ASC";
						$sql_mail="Select * from v_Documents where ord_schedule_id=$schedule_id AND doc_type_id = 80 AND doc_type_id = 83 AND active=1 AND msg_recipients LIKE '%$id_contact%' ORDER BY created_date DESC";
					}
				} 
			}
		
			$list_doc = '';
			$result = pg_query($conn, $sql);
			while ($arr = pg_fetch_assoc($result)){
				
				if(file_exists('img/avatar/' . $arr['id_contact'] . ".jpg")) {
					$img = 'img/avatar/' . $arr['id_contact'] . ".jpg";
				} else { $img = 'img/' . "user.jpg"; }
				
				$list_doc .= '<li class="col-xs-12 no-padding docListClass">
					<a href="#" onclick="viewBookingDoc(\''. $arr['doc_filename'] .'\',\''. $arr['id_document'] .'\');" class="col-xs-12" style="padding:3px 0;">
						<div> 
							<div class="col-xs-1 no-padding text-center">
								<strong>'. $arr['sequence_nr'] .' </strong>
							</div>
							
							<div class="col-xs-11">
								<strong class="pull-left" style="width:100%;">'.$arr['doctype_name'].'</strong>
								<img src="'.$img.'" class="img-circle pull-right" height="30" style="position: absolute; right: 10px; top: 2px;"/>
								<span class="text-muted small">
									<i class="fa fa-clock-o"></i> '. $arr['created_date'] .'
								</span>
								<div style="white-space:nowrap; text-overflow:ellipsis; overflow:hidden; line-height:17px;">
									<strong> '.substr($arr['document_desc'], 0, 40).'</strong><br/>
									'. $arr['doc_filename'] .'
								</div>
							</div> 
						</div> 
					</a>
				</li>';
			}
			
			$list_mail = '';
			$result_mail = pg_query($conn, $sql_mail);
			while ($arr_mail = pg_fetch_assoc($result_mail)){
				
				if(file_exists('img/avatar/' . $arr_mail['id_contact'] . ".jpg")) {
					$img = 'img/avatar/' . $arr_mail['id_contact'] . ".jpg";
				} else { $img = 'img/' . "user.jpg"; }
	
				$list_mail .= '<li class="col-xs-12 no-padding docListClass">
					<a href="#" onclick="viewBookingDoc(\''. $arr_mail['doc_filename'] .'\',\''. $arr_mail['id_document'] .'\');" class="col-xs-12" style="padding:3px 0;">
						<div> 
							<div class="col-xs-12">
								<img src="'.$img.'" class="img-circle pull-right" height="30" />
								<div style="white-space:nowrap; text-overflow:ellipsis; overflow:hidden; line-height:17px;">
									<strong> '.substr($arr_mail['document_desc'], 0, 40).'</strong>
								</div>
								<span class="text-muted small">
									<i class="fa fa-clock-o"></i> '. $arr_mail['created_date'] .'
								</span>
							</div> 
						</div> 
					</a>
				</li>';
			}
			
			$dom = $list_doc.'##'.$list_mail;
		
		break;
		
		
		case "save_edited_contract":
		
			if($_GET['sup_reference_nr']!=""){
				$sup_reference_nr = $_GET['sup_reference_nr'];
			} else { $sup_reference_nr=""; }
			
			if($_GET['fa_reference_nr']!=""){
				$fa_reference_nr = $_GET['fa_reference_nr'];
			} else { $fa_reference_nr=""; }
			
			if($_GET['customer_reference_nr']!=""){
				$customer_reference_nr = $_GET['customer_reference_nr'];
			} else { $customer_reference_nr=""; }
			
			if($_GET['ord_fa_contact_id']!=""){
				$ord_fa_contact_id = $_GET['ord_fa_contact_id'];
				$editord_fa_contact_id = "ord_fa_contact_id = '$ord_fa_contact_id',";
			} else { $ord_fa_contact_id=""; $editord_fa_contact_id = ''; }
		
			$id_ord_order = $_GET['id_ord_order'];
			
			$contract_modified_by = $_SESSION["id_user"];
			$contract_modified_date = gmdate("Y/m/d H:i");
			
			$sql = "UPDATE public.ord_order SET sup_reference_nr = '$sup_reference_nr',
				fa_reference_nr = '$fa_reference_nr', customer_reference_nr = '$customer_reference_nr',
				$editord_fa_contact_id
				contract_modified_by = $contract_modified_by, contract_modified_date = '$contract_modified_date'
			WHERE id_ord_order = $id_ord_order ";
	
			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_stats = "SELECT sup_reference_nr, fa_reference_nr, customer_reference_nr, fa_contact_name FROM public.v_order WHERE  id_ord_order = $id_ord_order";
				$result_stats = pg_query($conn, $sql_stats);
				$arr = pg_fetch_assoc($result_stats);
			
				$dom='1##'.$arr['sup_reference_nr'].'##'.$arr['fa_reference_nr'].'##'.$arr['customer_reference_nr'].'##'.$arr['fa_contact_name'];
				
			} else {
				$dom='0##0';
			}
			
		break;
		
		
		case "save_edited_customer_reference_nrd":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$customer_ref_ship_no = $_GET["customer_ref_ship_nr"];

			$sql_update = "UPDATE public.ord_ocean_Schedule SET customer_ref_ship_no = '$customer_ref_ship_no' WHERE id_ord_schedule = $id_ord_schedule ";
			$result = pg_query($conn, $sql_update);

			if ($result) {
				$sql_stats = "SELECT customer_ref_ship_nr FROM public.v_order_schedule WHERE id_ord_schedule = $id_ord_schedule";
				$result_stats = pg_query($conn, $sql_stats);
				$arr = pg_fetch_assoc($result_stats);
			
				$dom='1##'.$arr['customer_ref_ship_nr'];
				
			} else {
				$dom='0##0';
			}
			
		break;
		
		
		case "save_edited_supplier_reference_nrd":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$supplier_reference_nr = $_GET["supplier_reference_nr"];

			$sql = "UPDATE public.ord_ocean_Schedule SET supplier_reference_nr = '$supplier_reference_nr'
			WHERE id_ord_schedule = $id_ord_schedule ";
			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_stats = "SELECT supplier_reference_nr FROM public.v_order_schedule WHERE id_ord_schedule = $id_ord_schedule";
				$result_stats = pg_query($conn, $sql_stats);
				$arr = pg_fetch_assoc($result_stats);
			
				$dom='1##'.$arr['supplier_reference_nr'];
				
			} else {
				$dom='0##0';
			}
			
		break;
		
		
		case "save_edited_freight_agent_reference_nrd":
		
			$id_ord_schedule = $_GET["id_ord_schedule"];
			$fa_reference_nr = $_GET["fa_reference_nr"];

			$sql = "UPDATE public.ord_ocean_Schedule SET fa_reference_nr = '$fa_reference_nr'
			WHERE id_ord_schedule = $id_ord_schedule ";
			$result = pg_query($conn, $sql);

			if ($result) {
				$sql_stats = "SELECT fa_reference_nr FROM public.v_order_schedule WHERE id_ord_schedule = $id_ord_schedule";
				$result_stats = pg_query($conn, $sql_stats);
				$arr = pg_fetch_assoc($result_stats);
			
				$dom='1##'.$arr['fa_reference_nr'];
				
			} else {
				$dom='0##0';
			}
			
		break;
		

		case "calculate_all":
			
			$id_ord_schedule = $_GET["id_ord_schedule"];
			
			$sql_update = 'select * from public."CalculateAll"('.$id_ord_schedule.'); ';
			$result = pg_query($conn, $sql_update);
			
			if ($result) {
				$sql_flag = "UPDATE public.ord_ocean_schedule SET flag_calc = 1 WHERE id_ord_schedule=$id_ord_schedule ";
				$rslt_flag = pg_query($conn, $sql_flag);
				
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "copy_exporter_quote":
			
			$id_ord_schedule = $_GET["id_ord_schedule"];
			
			$sql_update = 'select * from public."CopyExporterQuote"('.$id_ord_schedule.'); ';
			$result = pg_query($conn, $sql_update);

			if ($result) {
				$sql_flag = "UPDATE public.ord_ocean_schedule SET flag_expquote = 1 WHERE id_ord_schedule=$id_ord_schedule ";
				$rslt_flag = pg_query($conn, $sql_flag);
				
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "refresh_containers_list":
		
			$ord_schedule_id = $_GET["ord_schedule_id"];
			
			// Ocean Container rights
			$oceanCont_create = $_GET['oceanCont_create'];
			if($oceanCont_create == 1){ $ocean_cc=''; }else{ $ocean_cc='hide'; }
			
			// Move Container rights
			$LogContMove = $_GET['LogContMove'];
			if($LogContMove == 1){ $move_cr=''; }else{ $move_cr='hide'; }
			
			
			$sql_container = "Select * From v_booking_conlist where ord_schedule_id= $ord_schedule_id  Order by cus_con_ref1";
			$rs_container = pg_query($conn, $sql_container);
			
			$list_container = '';
			
			while ($row_container = pg_fetch_assoc($rs_container)) {
				if($row_container['task_done']==2){
					$loading=' - <span class="text-navy">loading...</span>';
				} else 
				if($row_container['task_done']==1){
					$loading=' - <span class="text-success">Loading complete</span>';
				} else {
					$loading='';
				}
			
				$list_container .= '<tr><td>  
						<a href="javascript:loadingForm2(\''. $row_container['ord_loading_id'] .'\',\''. $row_container['id_ord_loading_item'] .'\',\''. $row_container['booking_nr'] .'\',\''. $row_container['ord_schedule_id'] .'\',\''. $row_container['id_con_list'] .'\',\''. $row_container['container_nr'] .'\');" class="reference_nr">
							'. $row_container['cus_con_ref1'] . '
						</a>
					</td>
					
					<td> '.$row_container['container_nr']. $loading .'</td>  
					<td> T: '. $row_container['tare'] . ' </td>  
					<td> W: '. $row_container['vgm_weight'] . ' </td>    
					<td> '. $row_container['date_loaded'] . ' </td>  
					
					<td style="width:60px">
						<a href="#" onclick="loadingForm2(\''. $row_container['ord_loading_id'] .'\',\''. $row_container['id_ord_loading_item'] .'\',\''. $row_container['booking_nr'] .'\',\''. $row_container['ord_schedule_id'] .'\',\''. $row_container['id_con_list'] .'\',\''. $row_container['container_nr'] .'\');"><i class="fa fa-eye"></i></a>
						<span class="containers_action_tb">
							&nbsp;<a href="#" onclick="edit_container(\''.$row_container['id_con_list'].'\',\''. $row_container['container_nr'] .'\',\''. $ord_schedule_id .'\',\''. $row_container['tare'] .'\',\''. $row_container['vgm_weight'] .'\',\''. $row_container['seal_1_nr'] .'\',\''. $row_container['seal_2_nr'] .'\',\''. $row_container['seal_3_nr'] .'\',\''. $row_container['seal_4_nr'] .'\',\''. $row_container['seal_5_nr'] .'\',\''. $row_container['date_loaded'] .'\');"><i class="fa fa-pen-square"></i></a>
							&nbsp;<a href="#" class="'.$ocean_cc.'" onclick="contdeleteConfirm(\''. $row_container['id_con_list'] .'\',\''. $row_container['container_nr'] .'\',\''. $ord_schedule_id .'\',\''. $row_container['tare'] .'\',\''. $row_container['vgm_weight'] .'\',\''. $row_container['date_loaded'] .'\');"><i class="fa fa-trash"></i></a>
							&nbsp;<a href="#" class="'.$move_cr.'" onclick="moveContainerModal(\''.$row_container['id_con_list'].'\',\''. $ord_order_id .'\',\''. $ord_schedule_id .'\');"><i class="fa fa-long-arrow-right"></i></a>
						</span>
					</td>
				</tr>';
			}
			
			$dom = $list_container;
			
		break;
		
		
		case "save_openexchangerates_date":
			
			$exch_datetime = $_GET["exch_datetime"];
			$id_proposal_calc = $_GET["id_proposal_calc"];
			
			$sql_stats = "UPDATE public.ord_proposal_calc SET 
				exch_by = 'Exchange rate provideer',
				exch_datetime = '$exch_datetime'
			WHERE id_proposal_calc=$id_proposal_calc";
			$rslt = pg_query($conn, $sql_stats);
		
		break;
		
		
		case "proposal_to_customer_mail":
			
			$send = $_GET["proposal_mail"];
			$ord_order_id = $_GET["ord_order_id"];
			
			if($ord_order_id!=""){
				
				// Email header
				$sql_header = "SELECT   
					v_order_schedule.id_ord_schedule,  
					v_order_schedule.supplier_name,  
					v_order_schedule.person_name,  
					to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,  
					to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,  
					v_order_schedule.created_by_name,  
					v_order_schedule.product_code,  
					v_order_schedule.port_name, 
					v_order_schedule.pod_name,	 
					v_order.incoterms,				
					v_order_schedule.offer_validity_date,  
					v_order_schedule.notes_sup,  
					get_contact_email(v_order_schedule.supplier_person_id) AS email_contact,  
					v_order.sm_person_name as sm_manager,  
					v_order_msg.ord_sm_person_id,  
					v_order_msg.sm_mail,  
					v_order_msg.imp_mail,  
					v_order_msg.cus_email,  
					v_order_msg.imp_admin_mail,  
					v_order_msg.cus_admin_mail,  
					v_order_msg.id_ord_order, 
					v_order.importer_person,					
					v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number,  
					v_order.importer,  
					v_order.ord_imp_contact_id,     
					v_order_msg.imp_phone,  
					v_order_msg.imp_skype,    
					get_contact_name(v_order.ord_imp_contact_id) ord_imp_contact_name,  
					v_order_schedule.order_nr, 
					v_order.ord_cus_contact_id,
					v_order.ord_cus_person_id,
					v_order_msg.ord_imp_person_id,
					v_order_msg.ord_imp_admin_id,
					get_contact_name(v_order.ord_cus_contact_id) customer_name,  
					get_contact_name(v_order.ord_cus_person_id) customer_contact  
				   FROM v_order_schedule, v_order_msg, v_order  
				   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id  
				   and v_order.id_ord_order=v_order_schedule.ord_order_id  
				   and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id ); ";
				   
				$rs_header = pg_query($conn, $sql_header);
				$row_header = pg_fetch_assoc($rs_header);

				$id_ord_schedule = $row_header['id_ord_schedule'];
				$supplier_name = $row_header['supplier_name'];
				$person_name = $row_header['person_name'];
				$proposal_date = $row_header['proposal_date'];
				
				$cus_admin_id = $row_header['cus_admin_id'];
				$ord_cus_person_id = $row_header['ord_cus_person_id'];
				$ord_imp_person_id = $row_header['ord_imp_person_id'];
				$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
			
				$created_date = $row_header['created_date'];
				$created_by_name = $row_header['created_by_name'];
				$product_code = $row_header['product_code'];
				$port_name = $row_header['port_name'];
				
				$incoterms = $row_header['incoterms'];
				$offer_validity_date = $row_header['offer_validity_date'];
				$notes_sup = $row_header['notes_sup'];
				$email_contact = $row_header['email_contact'];
				$sm_manager = $row_header['sm_manager'];
				$ord_sm_person_id = $row_header['ord_sm_person_id'];  
				$sm_mail = $row_header['sm_mail'];
				$imp_mail = $row_header['imp_mail'];
				
				$cus_email = $row_header['cus_email'];
				$imp_admin_mail = $row_header['imp_admin_mail'];
				$cus_admin_mail = $row_header['cus_admin_mail'];
				$id_ord_order = $row_header['id_ord_order'];
				$order_number = $row_header['order_number'];
				$importer = $row_header['importer'];
				$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
				
				$imp_phone = $row_header['imp_phone'];
				$imp_skype = $row_header['imp_skype'];
				$ord_imp_contact_name = $row_header['ord_imp_contact_name'];
				$order_nr = $row_header['order_nr'];
				$customer_name = $row_header['customer_name'];
				$customer_contact = $row_header['customer_contact'];
				$importer_person = $row_header['importer_person'];
				$pod_name = $row_header['pod_name'];
				
				$ord_cus_contact_id = $row_header['ord_cus_contact_id'];
				$ord_cus_person_id = $row_header['ord_cus_person_id'];
			
				// Email content
				$content ='';
				$sql_content = "select order_nr||'.'|| order_ship_nr as no,
                    to_char(month_etd,'Mon-yy')||'/'||week_etd as etd,
                    pol_country as origin,
                    to_char(month_eta,'Mon-yy')||'/'||week_eta as eta,
                    nr_containers,
                    to_char(weight_shipment,'999G999D9') as weight,
                    to_char(unit_value,'999G999') as proposal_price,
                    to_char(total_value,'999G999') as proposal_value
                from v_schedule_calc where ord_order_id=$ord_order_id
                order by order_ship_nr::integer";
				
				$i=1;
				$rs_content = pg_query($conn, $sql_content);
				while($row_content = pg_fetch_assoc($rs_content)){
					$content .='<tr style="padding:0;text-align:left;vertical-align:top">
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['no'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['etd'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['eta'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['nr_containers'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['weight'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['proposal_price'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['proposal_value'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'. strtoupper($row_content['origin']) .'</td>
					</tr>';
					$i++;
				}
			
				// Email footer
				$sql_footer = "select getregvalue(max(proposal_currency_id)) currency, to_char(sum(total_value),'999G999G999') as total 
				from v_schedule_calc where ord_order_id=$ord_order_id ";
				$rs_footer = pg_query($conn, $sql_footer);
				$row_footer = pg_fetch_assoc($rs_footer);
			
				$currency = $row_footer['currency']; 
				$total = $row_footer['total'];
			
				$to = "$cus_email";
				$subject = preg_replace('/\s+/', '', $order_number) . ': Sales Proposal for your request dated '.$created_date;

				$headers = "From: $importer_person <noreply@icollect.live>\r\n";
				$headers .= "Reply-To: $importer_person <$imp_mail>\r\n";
				$headers .= "CC: $imp_mail, $imp_admin_mail\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

				if($send==1){
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
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
																									<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
																								</td>
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																									iCRM.live Message from the icollect.live Back Office:<br>
																									Thank you again for your request for a quote! Please find below the details of our offer:
																								</td>
																							</tr>
																						</table>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							<strong>Our Reference</strong>: '.$order_number.'
																						</p>
																						
																						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
																									</td>
																								</tr>
																							</tbody>
																						</table>
																						
																						<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
																										<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Client</strong><br>'.$customer_name.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Contact</strong><br>'.$customer_contact.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Destination</strong><br>'.$pod_name.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Incoterms</strong><br>'.$incoterms.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																													
																													<th class="small-12 large-6 columns last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Product</strong><br>'.$product_code.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Currency</strong><br>'.$currency.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Amount Contract</strong><br>'.$total.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Proposal valid until</strong><br>'.$offer_validity_date.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																									
																									<th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0">
																									</th>
																								</tr>
																							</tbody>
																						</table>
																					
																						<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Our Ref.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">ETD</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">ETA</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Cont.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">MT</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Price/MT</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Total Price</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Origin</th>
																								</tr>
																								
																								'. $content .'
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
																							Message delivered by icollect.live back office on behalf of:
																						</h4>
																						
																						<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
																										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Company:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Sales Manager:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Email:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Phone:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Skype:</strong>
																														</p>
																													</th>
																													
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$importer.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$importer_person.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$imp_mail.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$imp_phone.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$imp_skype.'
																														</p>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Before printing think about the ENVIRONMENT!<br>
																							Warning: If you have received this email by error, please delete it and
																							inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
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
					
					
					$sender="noreply@icollect.live";
					$recipient=$imp_mail;
				
					$mail = new PHPMailer;
					$mail->isSMTP();
					// $mail->SMTPDebug = 2;
					// $mail->SMTPSecure = 'ssl';
					$mail->Debugoutput = 'html';
					$mail->Host = "d4i.maxapex.net";
					$mail->Port = 587;
					$mail->SMTPAuth = true;
					$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
					$mail->Username = ID_USER;
					$mail->Password = ID_PASS;
					$mail->setFrom('noreply@icollect.live', $importer_person);
					$mail->AddCC($imp_mail);
					$mail->AddCC($imp_admin_mail);
					// $mail->AddBCC('croth53@gmail.com');
					$mail->addReplyTo($imp_mail, $importer_person);
					$mail->addAddress('croth53@gmail.com');
					$mail->Subject = $subject;
					$mail->msgHTML($message);
					$mail->AltBody = 'This is a plain-text message body';
	
					//send the message, check for errors
					if (!$mail->send()) {
						$save=0;
					} else {
						$save=1;
					}

					if($save==1){
						// Save Email
						$msg_recipients = $ord_sm_person_id.', '.$ord_imp_person_id.', '.$ord_imp_admin_id;
						
						$sql = "SELECT v_order.customer_code||'-'||v_order.order_nr as file_name FROM v_order WHERE id_ord_order=$ord_order_id";
						$rst = pg_query($conn, $sql);
						$row = pg_fetch_assoc($rst);
						
						$date = date_create();
						$timestamp = date_timestamp_get($date);
						
						$file = trim($row['file_name']);
						$file_name = str_replace(' ', '_', $file);
						
						$doc_filename=$file_name.'-80-'.$timestamp.'.pdf';
					
						$email_sender_company_id = $_SESSION['id_company'];
						$created_by = $_SESSION['id_user'];
						$id_owner = $_SESSION['id_contact'];
						$created_date = gmdate("Y/m/d H:i");
						
						$sql = "insert into ord_document (ord_order_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
						email_sender_id, email_sender_company_id, msg_recipients) 
						values ($ord_order_id, '$doc_filename', 80, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
						$created_by, $email_sender_company_id, '$msg_recipients') RETURNING id_document";
						$result = pg_query($conn, $sql);
						
						$arr = pg_fetch_assoc($result);
						$id_document = $arr['id_document'];
						$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
						VALUES ($id_document, $created_by, 1)";
						pg_query($conn, $sql2);


						// Update pipeline_id in ord_order
						$sql_uord = "UPDATE public.ord_order SET pipeline_id=295 WHERE id_ord_order='$ord_order_id'";
						$rs_uord = pg_query($conn, $sql_uord) or die(pg_last_error());
						
						$cc=$imp_mail.','.$imp_admin_mail;
						$dom='1##'.$importer_person.'##'.$to.'##'.$subject.'##'.$cc.'##'.$created_date.'##'.$doc_filename.'##'.$ord_order_id;
					} else {
						$dom='0##0';
					}
					
				} else {
					$message .= '<table class="body" style="Margin:0;background:#f3f3f3!important;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody>
							<tr style="padding:0;text-align:left;vertical-align:top">
								<td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
									<center data-parsed="" style="min-width:580px;width:100%">
					
										<table align="center" class="container float-center" style="Margin:0 auto;background:#fefefe;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580px">
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
														
														<table class="" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
															<tbody>
																<tr style="padding:0;text-align:left;vertical-align:top">
																	<th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
																		<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																			<tbody>
																				<tr style="padding:0;text-align:left;vertical-align:top">
																					<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																						<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
																							<tr>
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
																									<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
																								</td>
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																									iCRM.live Message from the icollect.live Back Office:<br>
																									Thank you again for your request for a quote! Please find below the details of our offer:
																								</td>
																							</tr>
																						</table>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							<strong>Our Reference</strong>: '.$order_number.'
																						</p>
																						
																						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
																									</td>
																								</tr>
																							</tbody>
																						</table>
																						
																						<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
																										<table class="" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Client</strong><br>'.$customer_name.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Contact</strong><br>'.$customer_contact.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Port of Destination</strong><br>'.$pod_name.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Incoterms</strong><br>'.$incoterms.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																													
																													<th class="small-12 large-6 columns last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Product</strong><br>'.$product_code.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Currency</strong><br>'.$currency.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Amount Contract</strong><br>'.$total.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Proposal valid until</strong><br>'.$offer_validity_date.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																									
																									<th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0">
																									</th>
																								</tr>
																							</tbody>
																						</table>
																					
																						<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Our Ref.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">ETD</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">ETA</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Cont.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">MT</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Price/MT</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Total Price</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Origin</th>
																								</tr>
																								
																								'. $content .'
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
																							Message delivered by icollect.live back office on behalf of:
																						</h4>
																						
																						<table class=" text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
																										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Company:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Contact:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Email:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Phone:</strong>
																														</p>
																													</th>
																													
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$importer.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$_SESSION['name'].'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$_SESSION['p_email'].'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$_SESSION['p_phone'].'
																														</p>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Before printing think about the ENVIRONMENT!<br>
																							Warning: If you have received this email by error, please delete it and
																							inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
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
					</table>';
					
					$dom=$message;
				}
			}
		
		break;
		
		
		case "sm_manager_mail":
		
			$ord_order_id = $_GET["ord_order_id"];
			
			$sql_header = "SELECT v_order_schedule.id_ord_schedule, 
				v_order_schedule.supplier_name, 
				v_order_schedule.person_name, 
				to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date, 
				v_order_schedule.product_code, 
				v_order_schedule.port_name, 
				v_order_schedule.incoterms, 
				v_order_schedule.offer_validity_date, 
				v_order_schedule.notes_sup, 
				get_contact_email(v_order_schedule.supplier_person_id) AS email_contact, 
				v_order.importer_person, 
				v_order.sm_person_name,
				v_order_msg.cus_email,  
				v_order_msg.imp_mail, 
				v_order_msg.imp_skype, 
				v_order_msg.imp_phone, 
				v_order.ord_cus_contact_id,
				v_order_msg.imp_admin_mail, 
				v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number, 
				v_order.importer, 
				v_order.ord_imp_contact_id,  
				v_order.created_by_name, 
				v_order.created_date, 
				v_order.customer_name, 
				v_order.customer_contact, 
				v_order.notes_customer, 
				v_order.port_discharge, 
				v_order_msg.ord_sm_person_id, 
				v_order_msg.ord_sup_person_id,
				v_order_msg.ord_sup_admin_id,
				v_order.ord_imp_person_id,
				v_order_msg.ord_imp_admin_id,
				v_order_msg.sm_mail, 
				v_order.sm_person_name as sm_manager,
				v_order.incoterms as order_incoterms 
			   FROM v_order_schedule, v_order_msg, v_order 
			   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
			   and v_order.id_ord_order=v_order_schedule.ord_order_id 
			   and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) 
			from v_order_schedule where ord_order_id=$ord_order_id ); ";
				   
				$rs_header = pg_query($conn, $sql_header);
				$row_header = pg_fetch_assoc($rs_header);

				$id_ord_schedule = $row_header['id_ord_schedule'];
				$supplier_name = $row_header['supplier_name'];
				$person_name = $row_header['person_name'];
				$proposal_date = $row_header['proposal_date'];
				
				$product_code = $row_header['product_code'];
				$port_name = $row_header['port_name'];
				$incoterms = $row_header['incoterms'];
				$offer_validity_date = $row_header['offer_validity_date'];
				
				$notes_sup = $row_header['notes_sup'];
				$email_contact = $row_header['email_contact'];
				$importer_person = $row_header['importer_person'];
				$sm_person_name = $row_header['sm_person_name'];
				$cus_email = $row_header['cus_email'];
				
				$cus_admin_id = $row_header['cus_admin_id'];
				$imp_mail = $row_header['imp_mail'];
				$imp_phone = $row_header['imp_phone'];
				$imp_skype = $row_header['imp_skype'];
				$imp_admin_mail = $row_header['imp_admin_mail'];
				$order_number = $row_header['order_number'];
				$importer = $row_header['importer'];
				$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
				
				$created_by_name = $row_header['created_by_name'];
				$created_date = $row_header['created_date'];  
				$customer_name = $row_header['customer_name'];
				$customer_contact = $row_header['customer_contact'];
				$notes_customer = $row_header['notes_customer'];
				
				$port_discharge = $row_header['port_discharge'];
				$order_incoterms = $row_header['order_incoterms'];
				
				$ord_sm_person_id = $row_header['ord_sm_person_id'];
				$ord_sup_person_id = $row_header['ord_sup_person_id'];
				$ord_sup_admin_id = $row_header['ord_sup_admin_id'];
				$ord_imp_person_id = $row_header['ord_imp_person_id'];
				$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
		
				$sm_manager = $row_header['sm_manager'];
				$sm_mail = $row_header['sm_mail'];
				
			
				// Email content
				$content ='';
				$sql_content = "select order_nr||'.'|| order_ship_nr as no, 
				to_char(month_eta,'Mon-yy')as month_eta, week_eta, nr_containers as no_con,
				to_char(weight_shipment,'999G999') as weight from
				v_order_schedule where ord_order_id=$ord_order_id
				order by order_ship_nr::integer";
				
				$i=1;
				$rs_content = pg_query($conn, $sql_content);
				while($row_content = pg_fetch_assoc($rs_content)){
					
					if(($row_content['month_eta']!="")&&($row_content['week_eta']!="")){
						$ct_month=$row_content['month_eta'].'/'.$row_content['week_eta'];
						$tbh='ETA';
					} else {
						$ct_month=$row_content['month_etd'].'/'.$row_content['week_etd'];
						$tbh='ETD';
					}
					
					$content .='<tr style="padding:0;text-align:left;vertical-align:top">
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['no'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$ct_month.'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['no_con'].'</td>
						<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">'.$row_content['weight'].'</td>
					</tr>';
					$i++;
				}
			
				// $to = 'croth53@gmail.com';
				$to = "$sm_mail";
				$subject = preg_replace('/\s+/', '', $order_number) . ': New Request ready for Sourcing';

				$headers = "From: $importer_person <noreply@icollect.live>\r\n";
				$headers .= "Reply-To: $importer_person <$imp_mail>\r\n";
				$headers .= "CC: $imp_mail, $imp_admin_mail\r\n";
				$headers .= "Bcc: icollect.live@gmail.com\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

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
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
																									<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
																								</td>
																								<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																									iCRM.live Message from icollect.live Back Office:
																								</td>
																							</tr>
																						</table>
																						
																						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
																									</td>
																								</tr>
																							</tbody>
																						</table>
																						
																						<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
																										<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Company</strong><br>'.$customer_name.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Contact</strong><br>'.$customer_contact.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																													
																													<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
																														<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																															<tbody>
																																<tr style="padding:0;text-align:left;vertical-align:top">
																																	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Port of Destination</strong><br>'.$port_discharge.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Incoterms</strong><br>'.$order_incoterms.'
																																		</p>
																																		
																																		<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																																			<strong>Product</strong><br>'.$product_code.'
																																		</p>
																																	</th>
																																</tr>
																															</tbody>
																														</table>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																									
																									<th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0">
																										<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																											<strong>Our Reference</strong>: '.$order_number.'
																										</p>
																									</th>
																								</tr>
																							</tbody>
																						</table>
																					
																						<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Our Ref.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">'.$tbh.'</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">Cont.</th>
																									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">MT</th>
																								</tr>
																								
																								'. $content .'
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
																							Message delivered by icollect.live back office on behalf of:
																						</h4>
																						
																						<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
																										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Company:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Sales Manager:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Email:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Phone:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Skype:</strong>
																														</p>
																													</th>
																													
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$importer.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$importer_person.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$imp_mail.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$imp_phone.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$imp_skype.'
																														</p>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Before printing think about the ENVIRONMENT!<br>
																							Warning: If you have received this email by error, please delete it and
																							inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
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
				
				if($sm_mail==""){$sm_mail="c.roth@dev4impact.com";}
				$sender="noreply@icollect.live";
				$recipient=$sm_mail;
				
				$mail = new PHPMailer;
				$mail->isSMTP();
				// $mail->SMTPDebug = 2;
				// $mail->SMTPSecure = 'ssl';
				$mail->Debugoutput = 'html';
				$mail->Host = "d4i.maxapex.net";
				$mail->Port = 587;
				$mail->SMTPAuth = true;
				$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
				$mail->Username = ID_USER;
				$mail->Password = ID_PASS;
				$mail->setFrom('noreply@icollect.live', $importer_person);
				$mail->AddCC($imp_mail);
				$mail->AddCC($imp_admin_mail);
				$mail->AddBCC('croth53@gmail.com');
				$mail->addReplyTo($sm_mail, $sm_manager);
				$mail->addAddress($sm_mail);
				$mail->Subject = $subject;
				$mail->msgHTML($message);
				$mail->AltBody = 'This is a plain-text message body';
	
				//send the message, check for errors
				if (!$mail->send()) {
					$save=0;
				} else {
					$save=1;
				}

				if($save==1){
					// Save Email  
					$msg_recipients = $ord_sm_person_id.', '.$ord_imp_person_id.', '.$ord_imp_admin_id;
				
					$sql = "SELECT v_order.customer_code||'-'||v_order.order_nr as file_name FROM v_order WHERE id_ord_order=$ord_order_id";
					$rst = pg_query($conn, $sql);
					$row = pg_fetch_assoc($rst);
					
					$date = date_create();
					$timestamp = date_timestamp_get($date);
					
					$file = trim($row['file_name']);
					$file_name = str_replace(' ', '_', $file);
				
					$doc_filename=$file_name.'-80-'.$timestamp.'.pdf';
					
					$email_sender_company_id = $_SESSION['id_company'];
					$created_by = $_SESSION['id_user'];
					$id_owner = $_SESSION['id_contact'];
					$created_date = gmdate("Y/m/d H:i");
					
					$sql = "insert into ord_document (ord_order_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
					email_sender_id, email_sender_company_id, msg_recipients) 
					values ($ord_order_id, '$doc_filename', 80, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
					$created_by, $email_sender_company_id, '$msg_recipients') RETURNING id_document";
					$result = pg_query($conn, $sql);
				
					$arr = pg_fetch_assoc($result);
				
					$id_document = $arr['id_document'];
				
					$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
					VALUES ($id_document, $created_by, 1)";
					pg_query($conn, $sql2);


					$cc=$imp_mail.','.$imp_admin_mail;
					$dom='1##'.$importer_person.'##'.$to.'##'.$subject.'##'.$cc.'##'.$created_date.'##'.$doc_filename;
					
				} else {
					$dom='0##0';
				}
			
			break;
		
			
		case "check_for_vessel_mmsi":
		
			$vessel_name = strtoupper($_GET["vessel_name"]);
			
			$sql = "SELECT mmsi FROM ord_ship WHERE shipname = '$vessel_name'";
			
			$result = pg_query($conn, $sql);
			
			if($result){
				$arr = pg_fetch_assoc($result);
				$dom = $arr['mmsi'];
			} else {
				$dom = 0;
			}
			
		break;
		
		
		case "create_contract":
		
			$ord_order_id = $_GET["ord_order_id"];
			$cus_incoterms_id = $_GET["cus_incoterms_id"];
			
			$sql_doc = "select doc_filename from ord_document
			where ord_order_id=$ord_order_id and active=1
			and doc_type_id=3";

			$rs_doc = pg_query($conn, $sql_doc);
			$row_doc = pg_fetch_assoc($rs_doc);

			if($row_doc['doc_filename']!=""){
				$dom = '3##'.$row_doc['doc_filename'];
				
			} else {
				
				if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
					$sql_header = "SELECT
					v_order_schedule.id_ord_schedule,
					v_order_schedule.supplier_name,
					v_order_schedule.person_name,
					to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
					to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
					v_order_schedule.created_by_name,
					v_order_schedule.product_code,
					v_order_schedule.product_name,
					v_order_schedule.package_name,
					v_order_schedule.package_type_id,
					v_order_schedule.port_name,
					v_order_schedule.incoterms as sup_incoterms,
					v_order_schedule.offer_validity_date,
					v_order_schedule.notes_sup,
					get_contact_email(v_order_schedule.supplier_person_id) AS email_contact,
					v_order.sm_person_name as sm_manager,
					v_order_msg.ord_sm_person_id,
					v_order_msg.sm_mail,
					v_order_msg.sm_mail3,
					v_order_msg.imp_mail,
					v_order_msg.imp_mail3,
					v_order_msg.cus_email,
					v_order_msg.cus_email3,
					v_order_msg.imp_admin_mail,
					v_order_msg.imp_admin_mail3,
					v_order_msg.cus_admin_mail,
					v_order_msg.cus_admin_mail3,
					v_order_msg.id_ord_order,
					v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number,
					v_order.importer,
					v_order.ord_imp_contact_id,
					v_order.order_nr AS contract_number,
					v_order_msg.imp_phone,
					v_order_msg.imp_skype,
					v_order.ord_imp_contact_id,
					get_contact_name(v_order.ord_imp_person_id) ord_imp_person_name,
					get_contact_pstreet(v_order.ord_imp_contact_id) importer_street,
					get_contact_postalcode(v_order.ord_imp_contact_id) importer_postalcode,
					get_contact_paddress(v_order.ord_imp_contact_id) importer_town,
					v_order_msg.order_nr as imp_reference_nr,
					v_order_schedule.order_nr,
					get_contact_name(v_order.ord_cus_contact_id) customer_name,
					get_contact_pstreet(v_order.ord_cus_contact_id) customer_street,
					get_contact_postalcode(v_order.ord_cus_contact_id) customer_postalcode,
					get_contact_paddress(v_order.ord_cus_contact_id) customer_town,
					get_contact_name(v_order.ord_cus_person_id) customer_contact,
					getregvalue(v_order_schedule.cus_incoterms_id) as cus_incoterms,
					v_order_msg.customer_reference_nr,
					v_order_schedule.pod_id,
					v_order.customer_name,
					v_order.pod_name as cus_pod_name,
					v_order.ord_cus_contact_id,
					v_order.order_incoterms_id
					FROM v_order_schedule, v_order_msg, v_order
					where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
					and v_order.id_ord_order=v_order_schedule.ord_order_id
					and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id )";
				
				} else {
					$sql_header = "SELECT
					v_order_schedule.id_ord_schedule,
					v_order_schedule.supplier_name,
					v_order_schedule.person_name,
					to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
					to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
					v_order_schedule.created_by_name,
					v_order_schedule.product_code,
					v_order_schedule.product_name,
					v_order_schedule.package_name,
					v_order_schedule.package_type_id,
					v_order_schedule.port_name,
					v_order_schedule.incoterms as sup_incoterms,
					v_order_schedule.offer_validity_date,
					v_order_schedule.notes_sup,
					get_contact_email(v_order_schedule.supplier_person_id) AS email_contact,
					v_order.sm_person_name as sm_manager,
					v_order_msg.ord_sm_person_id,
					v_order_msg.sm_mail,
					v_order_msg.sm_mail3,
					v_order_msg.imp_mail,
					v_order_msg.imp_mail3,
					v_order_msg.cus_email,
					v_order_msg.cus_email3,
					v_order_msg.imp_admin_mail,
					v_order_msg.imp_admin_mail3,
					v_order_msg.cus_admin_mail,
					v_order_msg.cus_admin_mail3,
					v_order_msg.id_ord_order,
					v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number,
					v_order.importer,
					v_order.ord_imp_contact_id,
					v_order.order_nr AS contract_number,
					v_order_msg.imp_phone,
					v_order_msg.imp_skype,
					get_contact_name(v_order.ord_imp_person_id) ord_imp_person_name,
					get_contact_pstreet(v_order.ord_imp_contact_id) importer_street,
					get_contact_postalcode(v_order.ord_imp_contact_id) importer_postalcode,
					get_contact_paddress(v_order.ord_imp_contact_id) importer_town,
					v_order_msg.order_nr as imp_reference_nr,
					v_order_schedule.order_nr,
					get_contact_name(v_order.ord_cus_contact_id) customer_name,
					get_contact_pstreet(v_order.ord_cus_contact_id) customer_street,
					get_contact_postalcode(v_order.ord_cus_contact_id) customer_postalcode,
					get_contact_paddress(v_order.ord_cus_contact_id) customer_town,
					get_contact_name(v_order.ord_cus_person_id) customer_contact,
					getregvalue(v_order_schedule.cus_incoterms_id) as cus_incoterms,
					v_order_msg.customer_reference_nr,
					v_order_schedule.pod_id,
					v_order.customer_name,
					v_ocean_freight.dem_pol_cost_after,
					v_ocean_freight.dem_pol_free,
					v_ocean_freight.dem_pod_cost_after,
					v_ocean_freight.dem_pod_free,
					v_ocean_freight.pol_townport_id,
					v_ocean_freight.pod_name,
					v_ocean_freight.dem_pol_free2,
					v_ocean_freight.dem_pol_cost_after2,
					v_ocean_freight.dem_pod_cost_after2,
					v_ocean_freight.dem_pod_free2,
					v_order.pod_name as cus_pod_name,
					v_order.ord_cus_contact_id,
					v_order.order_incoterms_id
				   FROM v_order_schedule, v_order_msg, v_order, v_ocean_freight
				   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
				   and v_order.id_ord_order=v_order_schedule.ord_order_id
				   and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id )
				   and v_ocean_freight.sequence_nr=1 and v_ocean_freight.id_ord_schedule=v_order_schedule.id_ord_schedule;";
				}
				
				
				$rs_header = pg_query($conn, $sql_header);
				$row_header = pg_fetch_assoc($rs_header);
			
				$id_ord_schedule = $row_header['id_ord_schedule'];
				$supplier_name = $row_header['supplier_name'];
				$person_name = $row_header['person_name'];
				$proposal_date = $row_header['proposal_date'];
				$created_date = $row_header['created_date'];
				$created_by_name = $row_header['created_by_name'];
				$product_code = $row_header['product_code'];
				$product_name = trim($row_header['product_name']);
				$package_name = $row_header['package_name'];
				$port_name = $row_header['port_name'];
				$sup_incoterms = $row_header['sup_incoterms'];
				$offer_validity_date = $row_header['offer_validity_date'];
				$notes_sup = $row_header['notes_sup'];
				$email_contact = $row_header['email_contact'];
				$sm_manager = $row_header['sm_manager'];
				$ord_sm_person_id = $row_header['ord_sm_person_id'];
				$sm_mail = $row_header['sm_mail'];
				$sm_mail3 = $row_header['sm_mail3'];
				$imp_mail = $row_header['imp_mail'];
				$imp_mail3 = $row_header['imp_mail3'];
				$cus_email = $row_header['cus_email'];
				$cus_email3 = $row_header['cus_email3'];
				$imp_admin_mail = $row_header['imp_admin_mail'];
				$imp_admin_mail3 = $row_header['imp_admin_mail3'];
				$cus_admin_mail = $row_header['cus_admin_mail'];
				$cus_admin_mail3 = $row_header['cus_admin_mail3'];
				$id_ord_order = $row_header['id_ord_order'];
				$order_number = $row_header['order_number'];
				$importer = trim($row_header['importer']);
				$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
				$imp_phone = $row_header['imp_phone'];
				$imp_skype = $row_header['imp_skype'];
				$ord_imp_person_name = $row_header['ord_imp_person_name'];
				$importer_street = trim($row_header['importer_street']);
				$importer_postalcode = trim($row_header['importer_postalcode']);
				$importer_town = trim($row_header['importer_town']);
				$imp_reference_nr = $row_header['imp_reference_nr'];
				$order_nr = $row_header['order_nr'];
				$customer_name = trim($row_header['customer_name']);
				$customer_street = trim($row_header['customer_street']);
				$customer_postalcode = trim($row_header['customer_postalcode']);
				$customer_town = trim($row_header['customer_town']);
				$customer_contact = $row_header['customer_contact'];
				$cus_incoterms = $row_header['cus_incoterms'];
				$customer_reference_nr = $row_header['customer_reference_nr'];
				$pod_id = $row_header['pod_id'];
				$dem_pol_cost_after = $row_header['dem_pol_cost_after'];
				$dem_pol_free = $row_header['dem_pol_free'];
				$dem_pod_cost_after = $row_header['dem_pod_cost_after'];
				$dem_pod_free = $row_header['dem_pod_free'];
				$pol_townport_id = $row_header['pol_townport_id'];
				$pod_name = $row_header['pod_name'];
				$dem_pol_free2 = $row_header['dem_pol_free2'];
				$dem_pol_cost_after2 = $row_header['dem_pol_cost_after2'];
				$dem_pod_cost_after2 = $row_header['dem_pod_cost_after2'];
				$dem_pod_free2 = $row_header['dem_pod_free2'];
				$cus_pod_name = $row_header['cus_pod_name'];
				$contract_number = $row_header['contract_number'];
				$ord_cus_contact_id = $row_header['ord_cus_contact_id'];
				$order_incoterms_id = $row_header['order_incoterms_id'];
				$package_type_id = $row_header['package_type_id'];
				
				if(!empty($dem_pol_free2)){
					$demurage_cond2 = '<br/>Ab '.$dem_pod_free2.'. Tag werden '.$dem_pod_cost_after2.' EUR/Tag/Container fällig';
				} else {
					$demurage_cond2 = '';
				}
				
				$chassis ='';
				if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
					$delivery = $cus_incoterms .' '.$pod_name;
					$demurage = '';
					
				} else {
					
					if(($order_incoterms_id == 265)&&($ord_cus_contact_id == 688)){
						$chassis = '<div class="col-md-12" style="padding:5px 0;">
							<div class="col-md-2"><label class="contract_label">Chassis</label></div>
							<div class="col-md-10">3 Tage inklusive, anschliessend 60 Euro / Tag / Tank</div>
						</div>';
					}
					
					$delivery = $cus_incoterms .' '.$cus_pod_name;
					$demurage = '<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Demurrage</label></div>
						<div class="col-md-10">'.$dem_pod_free.' Tage frei ab ETA '.$pod_name.', anschliessend '.$dem_pod_cost_after.' EUR/Tag/Container <br/>
							(Stand Januar 2018 – die Demurrage Kosten sind variabel)'.$demurage_cond2.'
						</div>
					</div>' . $chassis;
				}
				
				$sql_detail = "select order_nr||'.'|| order_ship_nr as no, to_char(month_eta,'Mon-yyyy') as month_eta, 
				to_char(month_etd,'Mon-yyyy') as month_etd, nr_containers, to_char(weight_shipment,'999G999') weight,
				to_char(unit_value,'999G999') as proposal_price, to_char(total_value,'999G999') as proposal_value,
				pol_code, pol_country, pol_country_name
				from v_schedule_calc where ord_order_id=$ord_order_id
				order by order_ship_nr::integer";
				
				$tb_content='';
				$rs_detail = pg_query($conn, $sql_detail);
				while($row_detail = pg_fetch_assoc($rs_detail)) {
					
					if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
						$month = $row_detail['month_etd'];
					} else { $month = $row_detail['month_eta']; }
					
					$tb_content .='<tr>
						<td>'.$row_detail['no'].'</td>
						<td>'.$month.'</td>
						<td>'.$row_detail['nr_containers'].'</td>
						<td>'.$row_detail['weight'].'</td>
						<td>'.$row_detail['proposal_price'].'</td>
						<td>'.$row_detail['proposal_value'].'</td>
						<td>'.strtoupper($row_detail['pol_country']).'</td>
					</tr>';
				}
				
				$sql_foot = "select getregvalue(max(proposal_currency_id)) currency, to_char(sum(total_value),'999G999G999') as total,
					to_char(sum(weight_shipment),'999G999G999') as total_weight
				from v_schedule_calc where ord_order_id=$ord_order_id";
				
				$rs_foot = pg_query($conn, $sql_foot);
				$row_foot = pg_fetch_assoc($rs_foot);
				
				$currency = $row_foot['currency'];
				$total_weight = $row_foot['total_weight'];
				$total = $row_foot['total'];

				// Qualität
				$sql_qualite = 'SELECT * FROM v_regvalues WHERE id_register=56 ORDER BY cvalue ASC';
				$rs_qualite = pg_query($conn, $sql_qualite);

				$qualite_list = '<option value="">--</option>';
				while ($row_qualite = pg_fetch_assoc($rs_qualite)) {
					$qualite_list .= '<option value="'.$row_qualite['id_regvalue'] .'??'. $row_qualite['cvaluede'] .'">'.$row_qualite['cvaluede'] .'</option>';
				}

				// Parität
				$sql_parity = 'SELECT * FROM v_regvalues WHERE id_register=58 ORDER BY cvalue ASC';
				$rs_parity = pg_query($conn, $sql_parity);

				$parity_list = '<option value="">--</option>';
				while ($row_parity = pg_fetch_assoc($rs_parity)) {
					$parity_list .= '<option value="'.$row_parity['id_regvalue'] .'??'.$row_parity['cvaluede'] .'">'.$row_parity['cvaluede'] .'</option>';
				}
				
				// Basis Kontrakt
				$sql_contract = 'SELECT * FROM v_regvalues WHERE id_register=57 ORDER BY cvalue ASC';
				$rs_contract = pg_query($conn, $sql_contract);

				$contract_list = '<option value="">--</option>';
				while ($row_contract = pg_fetch_assoc($rs_contract)) {
					$contract_list .= '<option value="'.$row_contract['id_regvalue'] .'??'.$row_contract['cvaluede'] .'">'.$row_contract['cvaluede'] .'</option>';
				}
				
				//	Zahlungsbedingungen	
				$sql_payment = 'SELECT * FROM v_regvalues WHERE id_register=54 ORDER BY cvalue ASC';
				$rs_payment = pg_query($conn, $sql_payment);

				$payment_list = '<option value="">--</option>';
				while ($row_payment = pg_fetch_assoc($rs_payment)) {
					$payment_list .= '<option value="'.$row_payment['id_regvalue'] .'??'.$row_payment['cvaluede'] .'">'.$row_payment['cvaluede'] .'</option>';
				}
				
				if($package_type_id == 268){
					$label_starts = "***";
					$d_table_starts = "*** plus/minus 28 Tage je nach Verfügbarkeit der Seefrachter.";
				} else {
					$label_starts = "";
					$d_table_starts = "";
				}
				
				$content='<div class="row text-center" style="padding-top:20px; padding-bottom:20px;">
					<h1>'.$importer.'</h1>
					'.$importer_street.' '.$importer_postalcode.', '.$importer_town.' 
					<hr/>
				</div>
			
				<div class="row">
					<div class="col-md-12">
						'.$customer_name.'<br/>
						'.$customer_street.'<br/>
						'.$customer_postalcode.' '.$customer_town.'<br/>
					</div>
				</div>
				
				
				<div class="row" style="margin-top:30px; font-size:11px;">
					<div class="col-md-12 no-padding">
						<div class="col-md-2"><h3>Verkaufskontrakt</h3></div>
						<div class="col-md-10"><h3>'.$contract_number.'.000</h3></div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:30px;">
						<div class="col-md-2"><label class="contract_label">Datum</label></div>
						<div class="col-md-10">'.gmdate("d/m/Y").'</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Verkäufer</label></div>
						<div class="col-md-10">'.$importer.', '.$importer_postalcode.' '.$importer_town.'</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Käufer</label></div>
						<div class="col-md-10">'.$customer_name.', '.$customer_postalcode.' '.$customer_town.'</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Ware</label></div>
						<div class="col-md-10">
							<div class="pull-left">'.$product_name.' - aus LDC - '.$total_weight.' MT </div>
							<div class="pull-left"><input id="addedWare" type="text" class="form-control" style="font-size:11px;height:20px;width:20em;margin-left:15px;"/></div>
						</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Spezifikationen</label></div>
						<div class="col-md-10">
							<textarea id="spezifikationen" class="form-control" style="font-size:11px;"></textarea>
						</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Währung</label></div>
						<div class="col-md-10">'.$currency.'</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Menge/Liefertemine</label></div>
						<div class="col-md-10">
							<table class="table table-striped table-bordered" style="font-size:11px;">
								<thead>
									<tr>
										<th>Nr.</th>
										<th>Lieferung'.$label_starts.'</th>
										<th>Anz. Cont.</th>
										<th>Gewicht in MT*</th>
										<th>Preis/MT**</th>
										<th>Preis/Lieferung**</th>
										<th>Land</th>
									</tr>
								</thead>
								
								<tbody>
									'.$tb_content.'
								</tbody>
								
								<tfoot>
									<tr>
										<td colspan="5">Total</td>
										<td colspan="2">'.$total.'</td>
									</tr>
								</tfoot>
							</table>
							
							<div class="col-md-12">
								* Gewicht: +/- 10% **Preis exkl. MwSt <br/>'.$d_table_starts.'
							</div>
						</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Qualität</label></div>
						<div class="col-md-10">
							<div class="col-md-4 no-padding">
								<select class="form-control" id="qualite_contr" style="font-size:11px;">
									'.$qualite_list.'
								</select>
							</div>
						</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Verpackung</label></div>
						<div class="col-md-10">'.$package_name.'</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Lieferbedignung</label></div>
						<div class="col-md-10">'.$delivery.'</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Parität</label></div>
						<div class="col-md-10">
							<div class="col-md-4 no-padding">
								<select class="form-control" id="parity_contr" style="font-size:11px;">
									'.$parity_list.'
								</select>
							</div>
						</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Basis-Kontrakt</label></div>
						<div class="col-md-10">
							<div class="col-md-4 no-padding">
								<select class="form-control" id="contract_contr" style="font-size:11px;">
									'.$contract_list.'
								</select>
							</div>
						</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Zahlungskonditionen</label></div>
						<div class="col-md-10">
							<div class="col-md-4 no-padding">
								<select class="form-control" id="payment_contr" style="font-size:11px;">
									'.$payment_list.'
								</select>
							</div>
						</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Importeur</label></div>
						<div class="col-md-10">'.$customer_name.', die Ware wird auf Abruf im Ursprungsland produziert<br/>
						und wird auf '.$customer_name.', '.$customer_postalcode.' '.$customer_town.' verzollt</div>
					</div>
					
					'.$demurage.'
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Stand-, Warte- und Reinigungskosten</label></div>
						<div class="col-md-10">werden nach Aufwand in Rechnung gestellt</div>
					</div>
				</div>
				
				<div class="row" style="margin-top:30px;">
					<div class="col-md-12" style="padding:20px 0;">
						<div class="col-md-6 text-center">
							<label class="contract_label">Käufer</label><br/>
							'.$customer_name.'
						</div>
						<div class="col-md-6 text-center">
							<label class="contract_label">Verkäufer</label><br/>
							'.$importer.'
						</div>
					</div>
				</div>';
				
				$sql1 = "SELECT v_order.customer_code||'-'||v_order.order_nr as file_name FROM v_order WHERE id_ord_order=$ord_order_id";
				$rst = pg_query($conn, $sql1);
				$row = pg_fetch_assoc($rst);
				
				$date = date_create();
				$timestamp = date_timestamp_get($date);
				
				$file = trim($row['file_name']);
				$file_name = str_replace(' ', '_', $file);
				
				$doc_filename=$file_name.'-3-'.$timestamp.'.pdf';
				
				$dom = $content.'##'.$doc_filename;
			}

		break;
		
		
		case "save_contract":
		
			$user_id = $_SESSION["id_user"];
			$ord_order_id = $_GET["ord_order_id"];
			$pdf = $_GET["pdf"];
			$doc_Date = gmdate("Y-m-d");
			$created_date = gmdate("Y-m-d H:i:s");
			
			$var_parity = $_GET["parity"];
			$var_quality = $_GET["qualite"];
			$var_basecontract = $_GET["contract"];
			$var_payment_cond = $_GET["payment"];
			
			$sql = "insert into ord_document ( ord_order_id, doc_type_id, doc_Date, doc_filename,
				external_Ref1, external_ref2, document_desc,
				importer_id, client_id, exporter_id, user_id, active, read, created_date, message_viewed, message_read )
				SELECT
				v_order_msg.id_ord_order,
				3, '$doc_Date', '$pdf',
				v_order_msg.order_nr as imp_reference_nr,
				v_order_msg.customer_reference_nr,
				'Sales contract $ord_order_id',
				v_order.ord_imp_contact_id,
				v_order.ord_cus_contact_id,
				1, $user_id, 1, 0, '$created_date', 0, 0
				FROM v_order, v_order_msg
				where v_order_msg.id_ord_order=v_order.id_ord_order
				and v_order.id_ord_order=$ord_order_id
			RETURNING id_document";
			$result = pg_query($conn, $sql);
		
			if ($result) {
				$sql_update = "UPDATE public.ord_order SET var_quality=$var_quality, var_parity=$var_parity, var_basecontract=$var_basecontract, var_payment_cond=$var_payment_cond WHERE id_ord_order=$ord_order_id";
				pg_query($conn, $sql_update);
	
				$arr = pg_fetch_assoc($result);
				
				$id_document = $arr['id_document'];
			
				$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
				VALUES ($id_document, $user_id, 1)";
				pg_query($conn, $sql2);


				$dom=1;
				
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "save_new_carrier":
		
			$lastname = $_GET["lastname"];
			$fname = $_GET["fname"];
			$contact_code = $_GET["contact_code"];
			$town_name = $_GET["town_name"];
			$name = $lastname.' '.$fname;
			$id_type = 10;
			$id_supchain_type = 319;
			
			$sql = "insert into contact (lastname, fname, contact_code, town_name, name, id_type, id_supchain_type) 
			values ($lastname, $fname, $contact_code, $town_name, $name, $id_type, $id_supchain_type)";
			$result = pg_query($conn, $sql);

			if ($result) {
				
				//Carrier name list
				$sql_carrier_name = "select * from contact where id_supchain_type=319";
				$rs_carrier_name = pg_query($conn, $sql_carrier_name);
			
				$list_carrier_name = '<option value="">-- Select carrier --</option>';
			
				while ($row_carrier_name = pg_fetch_assoc($rs_carrier_name)) {
					$list_carrier_name .= '<option value="'. $row_carrier_name['id_contact'] .'">'. $row_carrier_name['name'] .'</option>';
				}
				
				$dom='1##'.$list_carrier_name;
			} else {
				$dom='0##0';
			}
		
		break;
		
		
		case "save_new_forwarder":
		
			$lastname = $_GET["lastname"];
			$fname = $_GET["fname"];
			$contact_code = $_GET["contact_code"];
			$town_name = $_GET["town_name"];
			$name = $lastname.' '.$fname;
			$id_type = 10;
			$id_supchain_type = 288;
			
			$sql = "insert into contact (lastname, firstname, contact_code, town_name, name, id_type, id_supchain_type) 
			values ('$lastname', '$fname', '$contact_code', '$town_name', '$name', $id_type, $id_supchain_type)";
			$result = pg_query($conn, $sql);

			if ($result) {
				
				//Forwarder name list
				$sql_forwarder_name = "select * from contact where id_supchain_type=288";
				$rs_forwarder_name = pg_query($conn, $sql_forwarder_name);
			
				$list_forwarder_name = '<option value="">-- Select forwarder --</option>';
			
				while ($row_forwarder_name = pg_fetch_assoc($rs_forwarder_name)) {
					$list_forwarder_name .= '<option value="'. $row_forwarder_name['id_contact'] .'">'. $row_forwarder_name['name'] .'</option>';
				}
				
				$dom='1##'.$list_forwarder_name;
			} else {
				$dom='0##0';
			}
		
		break;
		
		
		case "crm_ship":
		
			$update_right = $_GET['update_right'];
			$delete_right = $_GET['delete_right'];
			
			$sql_stats = "SELECT id_ship, mmsi, imo, shipname, photo FROM public.ord_ship ORDER BY shipname";
			$result = pg_query($conn, $sql_stats);
			
			$crm_ship='';
			while($arr = pg_fetch_assoc($result)){
				
				$crm_ship .= '<tr>
					<td>'. $arr['id_ship'] .'</td>
					<td>'. $arr['shipname'] .'</td>
					<td>'. $arr['mmsi'] .'</td>
					<td>'. $arr['imo'] .'</td>
					<td><a href="#" data-toggle="modal" data-target="#newSystShipPhotomodal" onclick="sysShipPhoto('. $arr['id_ship'] .');">
						<img src="'. $arr['photo'] .'" style="width:64px;" class="img-responsive" />
					</a></td>
					<td class="row_actions">';
						
						if($update_right == 1){
							$crm_ship .= '<a href="#" data-toggle="modal" onclick="showSystemShip('. $arr['id_ship'] .');" data-target="#newSystShipmodal"><i class="fa fa-pen-square"></i></a> ';
						}
						
						if($delete_right == 1){
							$crm_ship .= ' <a href="javascript:deleteSystemShip('. $arr['id_ship'] .');" onclick="return confirm(\'Are you sure you want to delete '. $arr['shipname'] .' ?\')"><i class="fa fa-trash"></i></a>';
						}
						
					$crm_ship .= '</td>
				</tr>';
			}
			
			$dom=$crm_ship;
		
		break;
		
		
		case "manage_system_ship":
		
			if(isset($_GET["shipname"])){
				$shipname = $_GET["shipname"];
			} else { $shipname = ""; }
			
			if(isset($_GET["mmsi"])){
				$mmsi = $_GET["mmsi"];
			} else { $mmsi = ""; }
			
			if(isset($_GET["imo"])){
				$imo = $_GET["imo"]; 
				$imo_req = " imo='$imo',";
				$imo_name = " imo,";
				$imo_val = " '$imo',";
			} else { $imo = ""; $imo_req = ""; $imo_name = ""; $imo_val = ""; }
			
			if(isset($_GET["photo"])){
				$photo = $_GET["photo"]; 
				$photo_req = " photo='$photo',";
				$photo_name = " photo,";
				$photo_val = " '$photo',";
			} else { $photo = ""; $photo_req = ""; $photo_name = ""; $photo_val = ""; }
			
			if(isset($_GET["id_ship"])){
				$id_ship = $_GET["id_ship"];
			} else { $id_ship = ""; }
			
			$conf = $_GET["conf"];
		
			if($conf == 'add'){
				$sql = "INSERT INTO public.ord_ship(mmsi, $imo_name $photo_name shipname) 
				VALUES ($mmsi, $imo_val $photo_val '$shipname');";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.ord_ship SET mmsi = $mmsi, $imo_req $photo_req shipname = '$shipname' WHERE id_ship = $id_ship";
				
			} else {}
	
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "show_system_ship":
		
			$id_ship = $_GET["id_ship"];
			
			$sql_stats = "SELECT mmsi, imo, shipname, photo FROM public.ord_ship WHERE id_ship=$id_ship";
			
			$result = pg_query($conn, $sql_stats);

			$arr = pg_fetch_assoc($result);
			
			$dom = $arr['mmsi'].'#'.$arr['imo'].'#'.$arr['shipname'].'#'.$arr['photo'];
			
		break;
		
		
		case "delete_system_ship":
		
			$id_ship = $_GET['id_ship'];
			
			$sql = "DELETE FROM public.ord_ship WHERE id_ship = $id_ship";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "send_eMail":
		
			$company_name = $_SESSION['company_name'];
			$name = utf8_decode($_SESSION['name']);
			$user_email = $_SESSION['p_email'];
			$p_phone = $_SESSION['p_phone'];
			$id_contact = $_SESSION['id_contact'];
		
			$sRecipient = $_GET['recipient'];

			$sSubject = trim(utf8_decode($_GET['subject']));
			$sText = utf8_decode($_GET['contenu']);
	
			$created_date = gmdate("Y/m/d H:i");
 
			$to = $sRecipient;
			$subject = $sSubject;

			$message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
					<meta name="viewport" content="width=device-width">
					<title></title>
				</head>
				<body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background:#f3f3f3!important;box-sizing:border-box;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100%!important">
					<style type="text/css" align="center" class="float-center">
					@media only screen{html{min-height:100%;background:#f3f3f3}}
					@media only screen and (max-width:596px){.small-float-center{margin:0 auto!important;float:none!important;text-align:center!important}.small-text-center{text-align:center!important}.small-text-left{text-align:left!important}.small-text-right{text-align:right!important}}
					@media only screen and (max-width:596px){.hide-for-large{display:block!important;width:auto!important;overflow:visible!important;max-height:none!important;font-size:inherit!important;line-height:inherit!important}}
					@media only screen and (max-width:596px){table.body table.container .hide-for-large,table.body table.container .row.hide-for-large{display:table!important;width:100%!important}}
					@media only screen and (max-width:596px){table.body table.container .callout-inner.hide-for-large{display:table-cell!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .show-for-large{display:none!important;width:0;mso-hide:all;overflow:hidden}}
					@media only screen and (max-width:596px){table.body img{width:auto;height:auto}table.body center{min-width:0!important}table.body .container{width:95%!important}table.body .column,table.body .columns{height:auto!important;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;padding-left:16px!important;padding-right:16px!important}table.body .column .column,table.body .column .columns,table.body .columns .column,table.body .columns .columns{padding-left:0!important;padding-right:0!important}table.body .collapse .column,table.body .collapse .columns{padding-left:0!important;padding-right:0!important}td.small-1,th.small-1{display:inline-block!important;width:8.33333%!important}td.small-2,th.small-2{display:inline-block!important;width:16.66667%!important}td.small-3,th.small-3{display:inline-block!important;width:25%!important}td.small-4,th.small-4{display:inline-block!important;width:33.33333%!important}td.small-5,th.small-5{display:inline-block!important;width:41.66667%!important}td.small-6,th.small-6{display:inline-block!important;width:50%!important}td.small-7,th.small-7{display:inline-block!important;width:58.33333%!important}td.small-8,th.small-8{display:inline-block!important;width:66.66667%!important}td.small-9,th.small-9{display:inline-block!important;width:75%!important}td.small-10,th.small-10{display:inline-block!important;width:83.33333%!important}td.small-11,th.small-11{display:inline-block!important;width:91.66667%!important}td.small-12,th.small-12{display:inline-block!important;width:100%!important}.column td.small-12,.column th.small-12,.columns td.small-12,.columns th.small-12{display:block!important;width:100%!important}table.body td.small-offset-1,table.body th.small-offset-1{margin-left:8.33333%!important;Margin-left:8.33333%!important}table.body td.small-offset-2,table.body th.small-offset-2{margin-left:16.66667%!important;Margin-left:16.66667%!important}table.body td.small-offset-3,table.body th.small-offset-3{margin-left:25%!important;Margin-left:25%!important}table.body td.small-offset-4,table.body th.small-offset-4{margin-left:33.33333%!important;Margin-left:33.33333%!important}table.body td.small-offset-5,table.body th.small-offset-5{margin-left:41.66667%!important;Margin-left:41.66667%!important}table.body td.small-offset-6,table.body th.small-offset-6{margin-left:50%!important;Margin-left:50%!important}table.body td.small-offset-7,table.body th.small-offset-7{margin-left:58.33333%!important;Margin-left:58.33333%!important}table.body td.small-offset-8,table.body th.small-offset-8{margin-left:66.66667%!important;Margin-left:66.66667%!important}table.body td.small-offset-9,table.body th.small-offset-9{margin-left:75%!important;Margin-left:75%!important}table.body td.small-offset-10,table.body th.small-offset-10{margin-left:83.33333%!important;Margin-left:83.33333%!important}table.body td.small-offset-11,table.body th.small-offset-11{margin-left:91.66667%!important;Margin-left:91.66667%!important}table.body table.columns td.expander,table.body table.columns th.expander{display:none!important}table.body .right-text-pad,table.body .text-pad-right{padding-left:10px!important}table.body .left-text-pad,table.body .text-pad-left{padding-right:10px!important}table.menu{width:100%!important}table.menu td,table.menu th{width:auto!important;display:inline-block!important}table.menu.small-vertical td,table.menu.small-vertical th,table.menu.vertical td,table.menu.vertical th{display:block!important}table.menu[align=center]{width:auto!important}table.button.small-expand,table.button.small-expanded{width:100%!important}table.button.small-expand table,table.button.small-expanded table{width:100%}table.button.small-expand table a,table.button.small-expanded table a{text-align:center!important;width:100%!important;padding-left:0!important;padding-right:0!important}table.button.small-expand center,table.button.small-expanded center{min-width:0}}
					</style>
					
					<span class="preheader" style="color:#f3f3f3;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
					<table class="body" style="Margin:0;background:#f3f3f3!important;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody>
							<tr style="padding:0;text-align:left;vertical-align:top">
								<td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
									<center data-parsed="" style="min-width:580px;width:100%">
										<table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;padding:0;text-align:center;vertical-align:top;width:580px">
											<tbody>
												<tr style="padding:0;text-align:left;vertical-align:top">
													<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
														<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
															<tbody>
																<tr style="padding:0;text-align:left;vertical-align:top">
																	<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td>
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
																						<table class="row footer text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:25%">
																										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														
																														<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
																															<tr>
																																<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
																																	<img src="https://icoop.live/ic/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
																																</td>
																																<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																																	iCRM.com Message from the iCoop Back Office.
																																</td>
																															</tr>
																														</table>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td>
																								</tr>
																							</tbody>
																						</table>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							<span style="font-size:12px;">'.$sText.'</span>
																						</p>
																						
																						<hr>
																						
																						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
																							Message delivered by icoop.live back office on behalf of:
																						</h4>
																						
																						<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
																							<tbody>
																								<tr style="padding:0;text-align:left;vertical-align:top">
																									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
																										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
																											<tbody>
																												<tr style="padding:0;text-align:left;vertical-align:top">
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Company:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Name:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Email:</strong>
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															<strong>Phone:</strong>
																														</p>
																													</th>
																												
																													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$company_name.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$name.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$user_email.'
																														</p>
																														
																														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
																															'.$p_phone.'
																														</p>
																													</th>
																												</tr>
																											</tbody>
																										</table>
																									</th>
																								</tr>
																							</tbody>
																						</table>
																						
																						<hr>
																						
																						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
																							Before printing think about the ENVIRONMENT!<br>Warning: If you have received this email by error, please delete it and inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
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
			$recipient="croth53@gmail.com";
		
			$mail = new PHPMailer;
			$mail->isSMTP();
			// $mail->SMTPDebug = 2;
			$mail->SMTPSecure = 'ssl';
			$mail->Debugoutput = 'html';
			$mail->Host = "mail.icoop.live";
			$mail->Port = 465;
			$mail->SMTPAuth = true;
			$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icoop.live>";
			$mail->Username = ID_USER;
			$mail->Password = ID_PASS;
			$mail->setFrom('noreply@icoop.live', $name);
			$mail->AddCC($user_email);
			if(!empty($_GET['cc']) && $_GET['cc']!='null'){
				$sCc = $_GET['cc'];
				$addrCc = explode(',',$sCc);
				foreach ($addrCc as $cc) {
					$mail->AddCC(trim($cc));       
				}
			} 
			// $mail->AddBCC("croth53@gmail.com");
			if(!empty($_GET['bcc']) && $_GET['bcc']!='null'){
				$sBcc = $_GET['bcc'];
				$addrBcc = explode(',',$sBcc);
				foreach ($addrBcc as $bcc) {
					$mail->AddBCC(trim($bcc));       
				}
			} 
			$mail->addReplyTo($user_email, $name);
			$addr = explode(',',$sRecipient);
			foreach ($addr as $ad) {
				$mail->AddAddress(trim($ad));       
			}
			// $mail->AddAddress('charlessabenin@gmail.com');
			$mail->Subject = $subject;
			$mail->msgHTML($message);
			$mail->AltBody = 'This is a plain-text message body';
	
			//send the message, check for errors
			if (!$mail->send()) {
				$save=0;
			} else {
				$save=1;
			}


			if($save==1){
				
				$msg_recipients = $_GET['msg_recipients'] . $id_contact;
				
				$date = date_create();
				$timestamp = date_timestamp_get($date);
				
				$doc_filename=$id_contact.'-80-'.$timestamp.'.pdf';
			
				$email_sender_company_id = $_SESSION['id_company'];
				$created_by = $_SESSION['id_user'];
				$id_owner = $_SESSION['id_contact'];
				$created_date = gmdate("Y/m/d H:i");
				
				$sql = "insert into ord_document (doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
				email_sender_id, email_sender_company_id, msg_recipients) 
				values ('$doc_filename', 80, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
				$created_by, $email_sender_company_id, '$msg_recipients') RETURNING id_document";
				$result = pg_query($conn, $sql);
				
				$arr = pg_fetch_assoc($result);
				
				$id_document = $arr['id_document'];
				$user_id = $_SESSION['id_user'];
			
				$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
				VALUES ($id_document, $user_id, 1)";
				pg_query($conn, $sql2);

				$dom = '1##'.$name.'##'.$created_date.'##'.$doc_filename.'##'.$user_email;
			} else {
				$dom = '0##0';
			}
			
		break;

		
		case "document_as_read":
		
			$user_id = $_SESSION['id_user'];
			$id_document = $_GET['id_document'];
			
			$sql = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
			VALUES ($id_document, $user_id, 1)";  

			$result = pg_query($conn, $sql) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "naming_file": 
		
			$id = $_GET['id'];
			$type = $_GET['type'];
			$doc_type_id = $_GET['doc_type_id'];
			
			if($type=="crm"){
				$sql = "SELECT v_order.customer_code||'-'||v_order.order_nr as file_name 
				FROM v_order WHERE id_ord_order=$id";
			} else 
			if($type=="logistics"){
				$sql = "SELECT v_order.customer_code||'-'||v_order.order_nr||'.'||v_order_schedule.order_ship_nr as file_name 
				FROM v_order, v_order_schedule 
				WHERE v_order_schedule.ord_order_id=v_order.id_ord_order
				AND v_order_schedule.id_ord_schedule=$id";
			} else {}

			$rst = pg_query($conn, $sql);
			$row = pg_fetch_assoc($rst);
			
			$date = date_create();
			$timestamp = date_timestamp_get($date);
			
			$file = trim($row['file_name']);
			$file_name = str_replace(' ', '_', $file);
				
			$dom=$file_name.'-'.$doc_type_id.'-'.$timestamp.'.pdf';
		
		break;
		
		
		case "booking_pod_id":
		
			$portname = $_GET['portname'];
			
			$sql_o_pod = "Select * from v_port where port_type_id=273 and portname='$portname'";
			$rs_o_pod = pg_query($conn, $sql_o_pod);
			$row_o_pod = pg_fetch_assoc($rs_o_pod);

			$dom=$row_o_pod['id_townport'];
			
		break;
		
		
		case "edit_onward_container":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			$id_con_list = $_GET['id_con_list'];
			
			$sql_container_onward = "Select * From v_booking_conlist 
				where ord_schedule_id=$ord_schedule_id 
				and id_con_list=$id_con_list
			Order by cus_con_ref1";
	
			$rs_container_onward = pg_query($conn, $sql_container_onward);
			$row_container_onward = pg_fetch_assoc($rs_container_onward);
			
			$dom='<div class="form-group">
					<label>No.</label>
					<input type="text" class="form-control" id="onw_cus_con_ref1" value="'. $row_container_onward['cus_con_ref1'] .'" />
				</div>
			
				<div class="form-group">
					<label>Container Number</label>
					<br/>'. $row_container_onward['container_nr'] .'
				</div>
				
				<div class="form-group">
					<label>Tare</label><br/>
					'. $row_container_onward['tare'] . '
					<input type="hidden" class="form-control" id="onw_tare" value="'. $row_container_onward['tare'] .'" />
				</div>
				
				<div class="form-group">
					<label>Gross Weight</label>
					<div class="input-group">
						<input type="number" min="0" class="form-control" id="onw_gross_weight_arrival" value="'. $row_container_onward['gross_weight_arrival'] .'" />
						<span class="input-group-btn"> 
							<button type="button" onclick="GrossCalc(\'onward\');" style="height: 35px;" title="Calculate Arrival Weight" class="btn btn-primary">
								<i class="fa fa-calculator" aria-hidden="true"></i>
							</button>
						</span>
					</div>
				</div>
				
				<div class="form-group">
					<label>ArrWeight</label>
					<input type="number" min="0" class="form-control" id="onw_vgm_delivery" value="'. $row_container_onward['vgm_delivery'] .'" />
				</div>
				
				<div class="form-group">
					<label>Diff</label><br/>
					'. $row_container_onward['vgm_diff'] . '
				</div>
				
				<div class="form-group">
					<label>Date</label><br/>
					'. $row_container_onward['vgm_delivery_date'] . '
				</div>
				
				<div class="form-group">
					<label>By</label><br/>
					'. $row_container_onward['vgm_delivery_by_name'] . '
				</div>
			';
			
		break;
		
		
		case "refresh_onward_container":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			
			$sql_container_onward = "Select * From v_booking_conlist where ord_schedule_id=$ord_schedule_id  Order by cus_con_ref1";
			$rs_container_onward = pg_query($conn, $sql_container_onward);
	
			$list_container_onward = '';
		
			while ($row_container_onward = pg_fetch_assoc($rs_container_onward)) {
				if($row_container_onward['task_done']==2){
					$loading_onward=' - <span class="text-navy">loading...</span>';
				} else 
				if($row_container_onward['task_done']==1){
					$loading_onward=' - <span class="text-success">Loading complete</span>';
				} else {
					$loading_onward='';
				}
				
				$list_container_onward .= '<tr><td>  
						<a href="javascript:loadingForm2(\''. $row_container_onward['id_con_list'] .'\',\''. $row_container_onward['id_ord_loading_item'] .'\');" class="reference_nr">
							'. $row_container_onward['cus_con_ref1'] . '
						</a>
					</td>
			
					<td> '.$row_container_onward['container_nr']. $loading_onward .'</td>  
					<td> '. $row_container_onward['tare'] . ' </td>  
					<td> '. $row_container_onward['vgm_weight'] . ' </td>    
					<td> '. $row_container_onward['gross_weight_arrival'] . ' </td>    
					<td> '. $row_container_onward['vgm_delivery'] . ' </td>    
					<td> '. $row_container_onward['vgm_diff'] . ' </td>  
					
					<td style="width:60px">
						<a href="#" onclick="loadingForm2(\''. $row_container_onward['id_con_list'] .'\',\''. $row_container_onward['id_ord_loading_item'] .'\');"><i class="fa fa-eye"></i></a>
						<span class="containers_action_tb_onward">
							&nbsp;<a href="#" class="'.$onward_cc.' editContainer" onclick="edit_container_onward(\''.$row_container_onward['id_con_list'].'\',\''. $ord_schedule_id .'\',\''. $row_container_onward['vgm_weight'] . '\');"><i class="fa fa-pen-square"></i></a>
						</span>
					</td>
				</tr>';
			}
			
			$dom=$list_container_onward;
			
		break;
		
		
		case "save_edit_container_onward":
		
			$id_con_list = $_GET['id_con_list'];
			
			if($_GET['vgm_delivery']){
				$vgm_delivery = $_GET['vgm_delivery'];
				$edit_vgm_delivery = "vgm_delivery='$vgm_delivery',";
			} else { $vgm_delivery=""; $edit_vgm_delivery=""; }
			
			if($_GET['cus_con_ref1']){
				$cus_con_ref1 = $_GET['cus_con_ref1'];
				$edit_cus_con_ref1 = "cus_con_ref1='$cus_con_ref1',";
			} else { $cus_con_ref1=""; $edit_cus_con_ref1=""; }
			
			if($_GET['gross_weight_arrival']){
				$gross_weight_arrival = $_GET['gross_weight_arrival'];
				$edit_gross_weight_arrival = "gross_weight_arrival='$gross_weight_arrival',";
			} else { $gross_weight_arrival=""; $edit_gross_weight_arrival=""; }
			
			if($_GET['vgm_weight']){
				$vgm_weight = $_GET['vgm_weight'];
			} else { $vgm_weight=""; }
			
			if(($vgm_delivery!="")&&($vgm_weight!="")){
				$vgm_diff = $vgm_delivery - $vgm_weight;
				$edit_vgm_diff = "vgm_diff='$vgm_diff',";
			} else { $edit_vgm_diff=""; $vgm_diff=""; }
			
			$vgm_delivery_date = gmdate("Y/m/d");
			$vgm_delivery_by = $_SESSION['id_user'];
			
			$sql_stats = "UPDATE public.ord_con_list
			   SET $edit_vgm_delivery $edit_cus_con_ref1 $edit_gross_weight_arrival
				   $edit_vgm_diff vgm_delivery_date='$vgm_delivery_date', vgm_delivery_by=$vgm_delivery_by
			WHERE id_con_list=$id_con_list";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "edit_onward_container_disposition":
			
			$ord_schedule_id = $_GET['ord_schedule_id'];
			$id_con_list = $_GET['id_con_list'];
			
			$sql_container_onward = "Select * From v_booking_conlist 
				where ord_schedule_id=$ord_schedule_id  
				and id_con_list=$id_con_list
			Order by cus_con_ref1";
			
			$rs_container_onward = pg_query($conn, $sql_container_onward);
			$row_container_onward = pg_fetch_assoc($rs_container_onward);
	
			$dom='<div class="form-group">
						<label>Order No</label>
						<input type="text" class="form-control" id="onw_disp_dispo_order_nr" value="'. $row_container_onward['dispo_order_nr'] .'" autofocus />
					</div>
					
				<div class="form-group">
					<label>Delivery No</label>
					<input type="text" class="form-control" id="onw_disp_dispo_delivery_nr" value="'. $row_container_onward['dispo_delivery_nr'] .'" />
				</div>
				
				<div class="form-group">
					<label>Available @Terminal</label>
					<input type="text" class="form-control edit_delivery_date" id="onw_disp_terminal_date" value="'. $row_container_onward['terminal_date'] .'" />
				</div>
				
				<div class="form-group">
					<label>Delivery Date Planned</label>
					<input type="text" class="form-control edit_delivery_date" id="onw_disp_terminal_dispo" value="'. $row_container_onward['terminal_dispo'] .'" />
				</div>
				
				<div class="form-group">
					<label>Delivery Time</label>
					<div class="input-group clockpicker" data-autoclose="true">
						<input type="text" class="form-control" id="onw_disp_dispo_hour" value="'. $row_container_onward['dispo_hour'] .'" >
						<span class="input-group-addon">
							<span class="fa fa-clock-o"></span>
						</span>
					</div>
				</div>
				
				<div class="form-group">
					<label>Date</label><br/>
					'. $row_container_onward['dispo_date'] . '
				</div>
				
				<div class="form-group">
					<label>By</label><br/>
					'. $row_container_onward['dispo_by_name'] . '
				</div>
			';

		break;
		
		
		case "save_edit_onward_container_disposition":
		
			$id_con_list = $_GET['id_con_list'];
			
			if(!empty($_GET['dispo_order_nr'])){
				$dispo_order_nr = $_GET['dispo_order_nr'];
				$edit_dispo_order_nr = "dispo_order_nr='$dispo_order_nr',";
			} else { $edit_dispo_order_nr = ''; }
			
			if(!empty($_GET['dispo_delivery_nr'])){
				$dispo_delivery_nr = $_GET['dispo_delivery_nr'];
				$edit_dispo_delivery_nr = "dispo_delivery_nr='$dispo_delivery_nr',";
			} else { $edit_dispo_delivery_nr = ''; }
			
			if(!empty($_GET['terminal_date'])){
				$terminal_date = $_GET['terminal_date'];
				$edit_terminal_date = "terminal_date='$terminal_date',";
			} else { $edit_terminal_date = ''; }
			
			if(!empty($_GET['terminal_dispo'])){
				$terminal_dispo = $_GET['terminal_dispo'];
				$edit_terminal_dispo = "terminal_dispo='$terminal_dispo',";
			} else { $edit_terminal_dispo = ''; }
			
			if(!empty($_GET['dispo_hour'])){
				$dispo_hour = $_GET['dispo_hour'];
				$edit_dispo_hour = "dispo_hour='$dispo_hour',";
			} else { $edit_dispo_hour = ''; }
			
			$dispo_date = gmdate("Y/m/d");
			$dispo_by = $_SESSION['id_user'];
			
			$sql_stats = "UPDATE public.ord_con_list
			   SET $edit_dispo_order_nr $edit_dispo_delivery_nr $edit_terminal_date
				   $edit_terminal_dispo $edit_dispo_hour dispo_date='$dispo_date', dispo_by=$dispo_by
			WHERE id_con_list=$id_con_list";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "refresh_onward_container_disposition":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			
			$sql_container_onward = "Select * From v_booking_conlist where ord_schedule_id=$ord_schedule_id  Order by cus_con_ref1";
			$rs_container_onward = pg_query($conn, $sql_container_onward);
	
			$list_container_disposition_onward = '';
		
			while ($row_container_onward = pg_fetch_assoc($rs_container_onward)) {
				if($row_container_onward['task_done']==2){
					$loading_onward=' - <span class="text-navy">loading...</span>';
				} else 
				if($row_container_onward['task_done']==1){
					$loading_onward=' - <span class="text-success">Loading complete</span>';
				} else {
					$loading_onward='';
				}
				
				$list_container_disposition_onward .= '<tr><td>  
						<a href="javascript:loadingForm2(\''. $row_container_onward['id_con_list'] .'\',\''. $row_container_onward['id_ord_loading_item'] .'\');" class="reference_nr">
							'. $row_container_onward['cus_con_ref1'] . '
						</a>
					</td>
					
					<td> '.$row_container_onward['container_nr']. $loading_onward .'</td>   
					<td> '. $row_container_onward['dispo_order_nr'] . ' </td>    
					<td> '. $row_container_onward['dispo_delivery_nr'] . ' </td>    
					<td> '. $row_container_onward['terminal_date'] . ' </td>    
					<td> '. $row_container_onward['terminal_dispo'] . ' </td>    
					<td> '. $row_container_onward['dispo_hour'] . ' </td>
					
					<td style="width:60px">
						<a href="#" onclick="loadingForm2(\''. $row_container_onward['id_con_list'] .'\',\''. $row_container_onward['id_ord_loading_item'] .'\');"><i class="fa fa-eye"></i></a>
						<span class="containers_dispo_action_tb_onward">
							&nbsp;<a href="#" class="'.$onward_cc.' editContainer" onclick="edit_onward_container_disposition(\''.$row_container_onward['id_con_list'].'\',\''. $ord_schedule_id .'\');"><i class="fa fa-pen-square"></i></a>
						</span>
					</td>
				</tr>';
			}
			
			$dom=$list_container_disposition_onward;
			
		break;
		
		
		case "delete_shipment":
	
			$id_ord_schedule = $_GET["id_ord_schedule"];
			
			$mail=0;
			$doc="";
			
			$sql_header = "SELECT  
				v_order_schedule.id_ord_schedule,
				v_order_schedule.month_eta,
				v_order_schedule.nr_containers,
				v_order_msg.imp_mail,
				v_order_msg.imp_admin_mail,
				v_order_msg.cus_email,
				v_order_msg.cus_admin_mail,
				v_order_msg.sm_mail,
				v_order_msg.ord_imp_admin_id,
				v_order_msg.ord_imp_person_id,
				v_order_msg.ord_sm_person_id,
				v_order_schedule.nr_shipments,
				v_order.customer_code||'-'||v_order.order_nr||'.'||v_order_schedule.nr_shipments as imp_reference,
				v_order.customer_code||'-'||v_order.customer_reference_nr||'.'||v_order_schedule.customer_ref_ship_nr as cus_reference,
				v_order.customer_code||'-'||v_order.sup_reference_nr||'.'||v_order_schedule.supplier_reference_nr as sup_reference,
				l.ref_code_fa,
				l.ref_code_cus,
				l.ref_code_imp,
				l.ref_code_sup
			   FROM v_order_schedule, v_order_msg, v_order, v_logistics_schedule l
			   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
			   and v_order.id_ord_order=v_order_schedule.ord_order_id 
			   and v_order_schedule.id_ord_schedule = l.id_ord_schedule
			   and v_order_schedule.id_ord_schedule = $id_ord_schedule
			";
			
			$result_header = pg_query($conn, $sql_header);
			$row_header = pg_fetch_assoc($result_header);
			
			if(!empty($row_header)){
				$ref_code_cus = $row_header['ref_code_cus'];
				$ref_code_imp = $row_header['ref_code_imp'];
				$ref_code_sup = $row_header['ref_code_sup'];
				$ref_code_fa = $row_header['ref_code_fa'];
				
				$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
				$ord_imp_person_id = $row_header['ord_imp_person_id'];
				$ord_sm_person_id = $row_header['ord_sm_person_id'];
				
				if($ref_code_cus!=""){ $no_cus = $ref_code_cus; } else { $no_cus=""; }
				if($ref_code_fa!=""){ $no_fa = ' / '.$ref_code_fa; } else { $no_fa=""; }
				if($ref_code_imp!=""){ $no_imp = ' / '.$ref_code_imp; } else { $no_imp=""; }
				if($ref_code_sup!=""){ $no_sup = ' / '.$ref_code_sup; } else { $no_sup=""; }
			
				$old_month_eta = trim($row_header['month_eta']);
				$old_nr_containers = trim($row_header['nr_containers']);
				
				if(!empty($row_header['imp_mail'])){ $imp_mail = $row_header['imp_mail']; }
				if(!empty($row_header['imp_admin_mail'])){ $imp_admin_mail = $row_header['imp_admin_mail']; }
				if(!empty($row_header['cus_email'])){ $cus_email = $row_header['cus_email']; }
				if(!empty($row_header['cus_admin_mail'])){ $cus_admin_mail = $row_header['cus_admin_mail']; }
				if(!empty($row_header['sm_mail'])){ $sm_mail = $row_header['sm_mail']; }

				$nr_shipments = trim($row_header['nr_shipments']);
				$imp_reference = trim($row_header['imp_reference']);
				$cus_reference = trim($row_header['cus_reference']);
				$sup_reference = trim($row_header['sup_reference']);
				
				$mail=1;
			}
			
			$sql1 = "delete from ord_proposal_item where ord_proposal_id in (
			select id_proposal_calc from ord_proposal_calc where ord_schedule_id = $id_ord_schedule)";
			pg_query($conn, $sql1);
			
			$sql2 = "delete from ord_proposal_calc where ord_schedule_id = $id_ord_schedule";
			pg_query($conn, $sql2);
			
			$sql3 = "delete from ord_schedule_freight where ord_ocean_schedule_id = $id_ord_schedule";
			$result3 = pg_query($conn, $sql3);

			$sql4 = "delete from ord_ocean_Schedule where id_ord_schedule = $id_ord_schedule";
			$result4 = pg_query($conn, $sql4);

			if ($result4) {
				
				if($mail == 1){
					$to = "$imp_person_name <$imp_mail>";
					$subject = $imp_reference . ' Shipment deleted';
					
					$headers = "From: <no-reply@icollect.live>\r\n";
					// $headers .= "CC: $imp_mail $imp_admin_mail $cus_email $cus_admin_mail $sm_mail\r\n"; 
					$headers .= "CC: $imp_mail $imp_admin_mail $sm_mail\r\n"; 
					$headers .= "Bcc: croth53@gmail.com\r\n"; 
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					
					$sText = '<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody><tr style="padding:0;text-align:left;vertical-align:top">
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> Shipment No. </strong>
								</p>
							
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> No. of Containers </strong>
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> ETA </strong>
								</p>
							</th>
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$nr_shipments.'
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$nr_containers.'
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$month_eta.'
								</p>
							</th>
						</tr></tbody>
					</table>';
				
			
					$message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width"><title></title></head><body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background:#f3f3f3!important;box-sizing:border-box;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100%!important">
						<style type="text/css" align="center" class="float-center">@media only screen{html{min-height:100%;background:#ffffff}}@media only screen and (max-width:596px){.small-float-center{margin:0 auto!important;float:none!important;text-align:center!important}.small-text-center{text-align:center!important}.small-text-left{text-align:left!important}.small-text-right{text-align:right!important}}@media only screen and (max-width:596px){.hide-for-large{display:block!important;width:auto!important;overflow:visible!important;max-height:none!important;font-size:inherit!important;line-height:inherit!important}}@media only screen and (max-width:596px){table.body table.container .hide-for-large,table.body table.container .row.hide-for-large{display:table!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .callout-inner.hide-for-large{display:table-cell!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .show-for-large{display:none!important;width:0;mso-hide:all;overflow:hidden}}@media only screen and (max-width:596px){table.body img{width:auto;height:auto}table.body center{min-width:0!important}table.body .container{width:95%!important}table.body .column,table.body .columns{height:auto!important;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;padding-left:16px!important;padding-right:16px!important}table.body .column .column,table.body .column .columns,table.body .columns .column,table.body .columns .columns{padding-left:0!important;padding-right:0!important}table.body .collapse .column,table.body .collapse .columns{padding-left:0!important;padding-right:0!important}td.small-1,th.small-1{display:inline-block!important;width:8.33333%!important}td.small-2,th.small-2{display:inline-block!important;width:16.66667%!important}td.small-3,th.small-3{display:inline-block!important;width:25%!important}td.small-4,th.small-4{display:inline-block!important;width:33.33333%!important}td.small-5,th.small-5{display:inline-block!important;width:41.66667%!important}td.small-6,th.small-6{display:inline-block!important;width:50%!important}td.small-7,th.small-7{display:inline-block!important;width:58.33333%!important}td.small-8,th.small-8{display:inline-block!important;width:66.66667%!important}td.small-9,th.small-9{display:inline-block!important;width:75%!important}td.small-10,th.small-10{display:inline-block!important;width:83.33333%!important}td.small-11,th.small-11{display:inline-block!important;width:91.66667%!important}td.small-12,th.small-12{display:inline-block!important;width:100%!important}.column td.small-12,.column th.small-12,.columns td.small-12,.columns th.small-12{display:block!important;width:100%!important}table.body td.small-offset-1,table.body th.small-offset-1{margin-left:8.33333%!important;Margin-left:8.33333%!important}table.body td.small-offset-2,table.body th.small-offset-2{margin-left:16.66667%!important;Margin-left:16.66667%!important}table.body td.small-offset-3,table.body th.small-offset-3{margin-left:25%!important;Margin-left:25%!important}table.body td.small-offset-4,table.body th.small-offset-4{margin-left:33.33333%!important;Margin-left:33.33333%!important}table.body td.small-offset-5,table.body th.small-offset-5{margin-left:41.66667%!important;Margin-left:41.66667%!important}table.body td.small-offset-6,table.body th.small-offset-6{margin-left:50%!important;Margin-left:50%!important}table.body td.small-offset-7,table.body th.small-offset-7{margin-left:58.33333%!important;Margin-left:58.33333%!important}table.body td.small-offset-8,table.body th.small-offset-8{margin-left:66.66667%!important;Margin-left:66.66667%!important}table.body td.small-offset-9,table.body th.small-offset-9{margin-left:75%!important;Margin-left:75%!important}table.body td.small-offset-10,table.body th.small-offset-10{margin-left:83.33333%!important;Margin-left:83.33333%!important}table.body td.small-offset-11,table.body th.small-offset-11{margin-left:91.66667%!important;Margin-left:91.66667%!important}table.body table.columns td.expander,table.body table.columns th.expander{display:none!important}table.body .right-text-pad,table.body .text-pad-right{padding-left:10px!important}table.body .left-text-pad,table.body .text-pad-left{padding-right:10px!important}table.menu{width:100%!important}table.menu td,table.menu th{width:auto!important;display:inline-block!important}table.menu.small-vertical td,table.menu.small-vertical th,table.menu.vertical td,table.menu.vertical th{display:block!important}table.menu[align=center]{width:auto!important}table.button.small-expand,table.button.small-expanded{width:100%!important}table.button.small-expand table,table.button.small-expanded table{width:100%}table.button.small-expand table a,table.button.small-expanded table a{text-align:center!important;width:100%!important;padding-left:0!important;padding-right:0!important}table.button.small-expand center,table.button.small-expanded center{min-width:0}}</style>
						<span class="preheader" style="color:#ffffff;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
						<table class="body" style="Margin:0;background:#ffffff!important;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"><aside data-parsed="" style="min-width:580px;width:100%"><table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;padding:0;text-align:center;vertical-align:top;width:580px"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
						
						<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
						<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
						'.utf8_decode($no_cus) . utf8_decode($no_fa) . utf8_decode($no_imp) . utf8_decode($no_sup).'
						</p>
						
						<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left"><table class="row footer text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:25%"><table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
							<tbody>
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
											<tr>
												<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
													<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
												</td>
												<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
													iCRM.live Message from the iDiscover Back Office.
												</td>
											</tr>
										</table>
									</th>
								</tr>
							</tbody>
						</table>
						
						</th></tr></tbody></table>
						
						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
						
						<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
							'.$subject.'
						</p>
						
						<br/><hr>
						
						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
							Message delivered by icollect.live back office on behalf of:
						</h4>
				
						<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
							<tbody>
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
											<tbody>
												<tr style="padding:0;text-align:left;vertical-align:top">
													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Company:</strong>
														</p>
												
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Name:</strong>
														</p>
											
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Email:</strong>
														</p>
													';
													if($_SESSION['p_phone']!=""){
														$message .= '<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Phone:</strong>
														</p>';
													}
													
												$message .= '</th>
													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['company_name'].'
														</p>
												
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['name'].'
														</p>
												
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['p_email'].'
														</p>
														
													';
													if($_SESSION['p_phone']!=""){
														$message .= '<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['p_phone'].'
														</p>';
													}
												
												$message .= '</th>
												</tr>
											</tbody>
										</table>
									</th>
								</tr>
							</tbody>
						</table>
				
						<hr>
						
						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
							Before printing think about the ENVIRONMENT!<br>Warning: If you have received this email by error, please delete it and inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
						</p>
						
						</th></tr></tbody></table></th></tr></tbody></table></td></tr></tbody></table></aside></td></tr></tbody></table>
						</body>
					</html>';
					
					if($imp_mail==""){$imp_mail="c.roth@dev4impact.com";}
					$sender="noreply@icollect.live";
					$recipient=$imp_mail;
				
					$mail = new PHPMailer;
					$mail->isSMTP();
					// $mail->SMTPDebug = 2;
					// $mail->SMTPSecure = 'ssl';
					$mail->Debugoutput = 'html';
					$mail->Host = "d4i.maxapex.net";
					$mail->Port = 587;
					$mail->SMTPAuth = true;
					$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
					$mail->Username = ID_USER;
					$mail->Password = ID_PASS;
					$mail->setFrom('noreply@icollect.live', $_SESSION['name']);
					$mail->AddCC($imp_mail); 
					$mail->AddCC($imp_admin_mail); 
					$mail->AddCC($sm_mail); 
					$mail->AddBCC("croth53@gmail.com"); 
					$mail->addReplyTo($imp_mail, $imp_person_name);
					$mail->addAddress($imp_mail, $imp_person_name);
					$mail->Subject = $subject;
					$mail->msgHTML($message);
					$mail->AltBody = 'This is a plain-text message body';
			
					//send the message, check for errors
					if (!$mail->send()) {
						$save=0;
					} else {
						$save=1;
					}
					
					if($save==1){
					
						$sql = "SELECT v_order.customer_code||'-'||v_order.order_nr||'.'||v_order_schedule.order_ship_nr as file_name 
						FROM v_order, v_order_schedule 
						WHERE v_order_schedule.ord_order_id=v_order.id_ord_order
						AND v_order_schedule.id_ord_schedule=$id_ord_schedule";
					
						$rst = pg_query($conn, $sql);
						$row = pg_fetch_assoc($rst);
					
						$date = date_create();
						$timestamp = date_timestamp_get($date);
						
						$file = trim($row['file_name']);
						$file_name = str_replace(' ', '_', $file);
						
						$doc_filename=$file_name.'-83-'.$timestamp.'.pdf';
					
						$email_sender_company_id = $_SESSION['id_company'];
						$created_by = $_SESSION['id_user'];
						$id_owner = $_SESSION['id_contact'];
						$created_date = gmdate("Y/m/d H:i");
						
						$msg_recipients = $ord_imp_admin_id.','.$ord_imp_person_id.','.$ord_sm_person_id;
						
						
						$sql_mail = "insert into ord_document (ord_schedule_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
						email_sender_id, email_sender_company_id, msg_recipients) 
						values ($ord_schedule_id, '$doc_filename', 83, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
						$created_by, $email_sender_company_id, '$msg_recipients') RETURNING id_document";
						
						$result_mail = pg_query($conn, $sql_mail);
						if($result_mail){ 
							$doc=$doc_filename; 
							
							$arr = pg_fetch_assoc($result);
				
							$id_document = $arr['id_document'];
							$user_id = $_SESSION['id_user'];
					
							$sqlQueue = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
							VALUES ($id_document, $user_id, 1)";
							pg_query($conn, $sqlQueue);
						}
					}
				}
				
				$dom='1##'.$doc;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "add_shipment":
		
			$order_id = $_GET["order_id"];
			$nr_containers = $_GET["nr_containers"];
			$month_eta = $_GET["month_eta"];
			$modify_date = $_GET["modify_date"];
			$user_id = $_SESSION['id_user'];
			
			$mail=0;
			$doc="";
			
			$sql_header = "SELECT  
				v_order_schedule.id_ord_schedule,
				v_order_schedule.month_eta,
				v_order_schedule.nr_containers,
				v_order_msg.imp_mail,
				v_order_msg.imp_admin_mail,
				v_order_msg.cus_email,
				v_order_msg.cus_admin_mail,
				v_order_msg.sm_mail,
				v_order_msg.ord_imp_admin_id,
				v_order_msg.ord_imp_person_id,
				v_order_msg.ord_sm_person_id,
				v_order_schedule.nr_shipments,
				v_order.customer_code||'-'||v_order.order_nr||'.'||v_order_schedule.nr_shipments as imp_reference,
				v_order.customer_code||'-'||v_order.customer_reference_nr||'.'||v_order_schedule.customer_ref_ship_nr as cus_reference,
				v_order.customer_code||'-'||v_order.sup_reference_nr||'.'||v_order_schedule.supplier_reference_nr as sup_reference,
				l.ref_code_fa,
				l.ref_code_cus,
				l.ref_code_imp,
				l.ref_code_sup
			   FROM v_order_schedule, v_order_msg, v_order, v_logistics_schedule l
			   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id 
			   and v_order.id_ord_order=v_order_schedule.ord_order_id 
			   and v_order_schedule.id_ord_schedule = l.id_ord_schedule
			   and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$order_id )
			";
			
			$result_header = pg_query($conn, $sql_header);
			$row_header = pg_fetch_assoc($result_header);
			
			if(!empty($row_header)){
				$ref_code_cus = $row_header['ref_code_cus'];
				$ref_code_imp = $row_header['ref_code_imp'];
				$ref_code_sup = $row_header['ref_code_sup'];
				$ref_code_fa = $row_header['ref_code_fa'];
				
				$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
				$ord_imp_person_id = $row_header['ord_imp_person_id'];
				$ord_sm_person_id = $row_header['ord_sm_person_id'];

				if($ref_code_cus!=""){ $no_cus = $ref_code_cus; } else { $no_cus=""; }
				if($ref_code_fa!=""){ $no_fa = ' / '.$ref_code_fa; } else { $no_fa=""; }
				if($ref_code_imp!=""){ $no_imp = ' / '.$ref_code_imp; } else { $no_imp=""; }
				if($ref_code_sup!=""){ $no_sup = ' / '.$ref_code_sup; } else { $no_sup=""; }
			
				$old_month_eta = trim($row_header['month_eta']);
				$old_nr_containers = trim($row_header['nr_containers']);
				$id_ord_schedule = trim($row_header['id_ord_schedule']);
				
				if(!empty($row_header['imp_mail'])){ $imp_mail = $row_header['imp_mail']; }
				if(!empty($row_header['imp_admin_mail'])){ $imp_admin_mail = $row_header['imp_admin_mail']; }
				if(!empty($row_header['cus_email'])){ $cus_email = $row_header['cus_email']; }
				if(!empty($row_header['cus_admin_mail'])){ $cus_admin_mail = $row_header['cus_admin_mail']; }
				if(!empty($row_header['sm_mail'])){ $sm_mail = $row_header['sm_mail']; }

				$nr_shipments = trim($row_header['nr_shipments']);
				$imp_reference = trim($row_header['imp_reference']);
				$cus_reference = trim($row_header['cus_reference']);
				$sup_reference = trim($row_header['sup_reference']);
				
				$mail=1;
			}
			
			$sql = 'SELECT * FROM public."CreateShipment"('.$order_id.', '.$nr_containers.', \''.$month_eta.'\', '.$user_id.', \''.$modify_date.'\') ';

			$rst = pg_query($conn, $sql) or die(pg_last_error());
			$count_add = pg_num_rows($rst);
			
			if($count_add==1){
				if($mail == 1){
					$to = "$imp_person_name <$imp_mail>";
					$subject = $imp_reference . ' Shipment has been added';
					
					$headers = "From: <no-reply@icollect.live>\r\n";
					// $headers .= "CC: $imp_mail $imp_admin_mail $cus_email $cus_admin_mail $sm_mail\r\n"; 
					$headers .= "CC: $imp_mail $imp_admin_mail $sm_mail\r\n"; 
					$headers .= "Bcc: croth53@gmail.com, \r\n"; 
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					
					$sText = '<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody><tr style="padding:0;text-align:left;vertical-align:top">
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> Shipment No. </strong>
								</p>
							
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> No. of Containers </strong>
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									<strong> ETA </strong>
								</p>
							</th>
							<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$nr_shipments.'
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$nr_containers.'
								</p>
								
								<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
									'.$month_eta.'
								</p>
							</th>
						</tr></tbody>
					</table>';
				
			
					$message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width"><title></title></head><body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background:#f3f3f3!important;box-sizing:border-box;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100%!important">
						<style type="text/css" align="center" class="float-center">@media only screen{html{min-height:100%;background:#ffffff}}@media only screen and (max-width:596px){.small-float-center{margin:0 auto!important;float:none!important;text-align:center!important}.small-text-center{text-align:center!important}.small-text-left{text-align:left!important}.small-text-right{text-align:right!important}}@media only screen and (max-width:596px){.hide-for-large{display:block!important;width:auto!important;overflow:visible!important;max-height:none!important;font-size:inherit!important;line-height:inherit!important}}@media only screen and (max-width:596px){table.body table.container .hide-for-large,table.body table.container .row.hide-for-large{display:table!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .callout-inner.hide-for-large{display:table-cell!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .show-for-large{display:none!important;width:0;mso-hide:all;overflow:hidden}}@media only screen and (max-width:596px){table.body img{width:auto;height:auto}table.body center{min-width:0!important}table.body .container{width:95%!important}table.body .column,table.body .columns{height:auto!important;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;padding-left:16px!important;padding-right:16px!important}table.body .column .column,table.body .column .columns,table.body .columns .column,table.body .columns .columns{padding-left:0!important;padding-right:0!important}table.body .collapse .column,table.body .collapse .columns{padding-left:0!important;padding-right:0!important}td.small-1,th.small-1{display:inline-block!important;width:8.33333%!important}td.small-2,th.small-2{display:inline-block!important;width:16.66667%!important}td.small-3,th.small-3{display:inline-block!important;width:25%!important}td.small-4,th.small-4{display:inline-block!important;width:33.33333%!important}td.small-5,th.small-5{display:inline-block!important;width:41.66667%!important}td.small-6,th.small-6{display:inline-block!important;width:50%!important}td.small-7,th.small-7{display:inline-block!important;width:58.33333%!important}td.small-8,th.small-8{display:inline-block!important;width:66.66667%!important}td.small-9,th.small-9{display:inline-block!important;width:75%!important}td.small-10,th.small-10{display:inline-block!important;width:83.33333%!important}td.small-11,th.small-11{display:inline-block!important;width:91.66667%!important}td.small-12,th.small-12{display:inline-block!important;width:100%!important}.column td.small-12,.column th.small-12,.columns td.small-12,.columns th.small-12{display:block!important;width:100%!important}table.body td.small-offset-1,table.body th.small-offset-1{margin-left:8.33333%!important;Margin-left:8.33333%!important}table.body td.small-offset-2,table.body th.small-offset-2{margin-left:16.66667%!important;Margin-left:16.66667%!important}table.body td.small-offset-3,table.body th.small-offset-3{margin-left:25%!important;Margin-left:25%!important}table.body td.small-offset-4,table.body th.small-offset-4{margin-left:33.33333%!important;Margin-left:33.33333%!important}table.body td.small-offset-5,table.body th.small-offset-5{margin-left:41.66667%!important;Margin-left:41.66667%!important}table.body td.small-offset-6,table.body th.small-offset-6{margin-left:50%!important;Margin-left:50%!important}table.body td.small-offset-7,table.body th.small-offset-7{margin-left:58.33333%!important;Margin-left:58.33333%!important}table.body td.small-offset-8,table.body th.small-offset-8{margin-left:66.66667%!important;Margin-left:66.66667%!important}table.body td.small-offset-9,table.body th.small-offset-9{margin-left:75%!important;Margin-left:75%!important}table.body td.small-offset-10,table.body th.small-offset-10{margin-left:83.33333%!important;Margin-left:83.33333%!important}table.body td.small-offset-11,table.body th.small-offset-11{margin-left:91.66667%!important;Margin-left:91.66667%!important}table.body table.columns td.expander,table.body table.columns th.expander{display:none!important}table.body .right-text-pad,table.body .text-pad-right{padding-left:10px!important}table.body .left-text-pad,table.body .text-pad-left{padding-right:10px!important}table.menu{width:100%!important}table.menu td,table.menu th{width:auto!important;display:inline-block!important}table.menu.small-vertical td,table.menu.small-vertical th,table.menu.vertical td,table.menu.vertical th{display:block!important}table.menu[align=center]{width:auto!important}table.button.small-expand,table.button.small-expanded{width:100%!important}table.button.small-expand table,table.button.small-expanded table{width:100%}table.button.small-expand table a,table.button.small-expanded table a{text-align:center!important;width:100%!important;padding-left:0!important;padding-right:0!important}table.button.small-expand center,table.button.small-expanded center{min-width:0}}</style>
						<span class="preheader" style="color:#ffffff;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
						<table class="body" style="Margin:0;background:#ffffff!important;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"><aside data-parsed="" style="min-width:580px;width:100%"><table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;padding:0;text-align:center;vertical-align:top;width:580px"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
						<tbody><tr style="padding:0;text-align:left;vertical-align:top"><td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
						
						<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
						<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
						'.utf8_decode($no_cus) . utf8_decode($no_fa) . utf8_decode($no_imp) . utf8_decode($no_sup).'
						</p>
						
						<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left"><table class="row footer text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:25%"><table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
							<tbody>
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
										<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
											<tr>
												<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
													<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
												</td>
												<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
													iCRM.live Message from the iDiscover Back Office.
												</td>
											</tr>
										</table>
									</th>
								</tr>
							</tbody>
						</table>
						
						</th></tr></tbody></table>
						
						<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr style="padding:0;text-align:left;vertical-align:top"><td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word"></td></tr></tbody></table>
						<span style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
							A new Shipment has been added: 
						</span><br/>
						'.$sText.'
						
						<br/><hr>
						
						<h4 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left;word-wrap:normal">
							Message delivered by icollect.live back office on behalf of:
						</h4>
				
						<table class="row text-center" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:center;vertical-align:top;width:100%">
							<tbody>
								<tr style="padding:0;text-align:left;vertical-align:top">
									<th class="small-12 large-3 columns" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:8px;padding-right:8px;text-align:left;width:129px">
										<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
											<tbody>
												<tr style="padding:0;text-align:left;vertical-align:top">
													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Company:</strong>
														</p>
												
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Name:</strong>
														</p>
											
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Email:</strong>
														</p>
													';
													if($_SESSION['p_phone']!=""){
														$message .= '<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															<strong>Phone:</strong>
														</p>';
													}
													
												$message .= '</th>
													<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['company_name'].'
														</p>
												
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['name'].'
														</p>
												
														<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['p_email'].'
														</p>
														
													';
													if($_SESSION['p_phone']!=""){
														$message .= '<p style="Margin:0;Margin-bottom:4px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
															'.$_SESSION['p_phone'].'
														</p>';
													}
												
												$message .= '</th>
												</tr>
											</tbody>
										</table>
									</th>
								</tr>
							</tbody>
						</table>
				
						<hr>
						
						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
							Before printing think about the ENVIRONMENT!<br>Warning: If you have received this email by error, please delete it and inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
						</p>
						
						</th></tr></tbody></table></th></tr></tbody></table></td></tr></tbody></table></aside></td></tr></tbody></table>
						<div style="display:none;white-space:nowrap;font:15px courier;line-height:0">&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;</div>
						</body>
					</html>';
					
					if($imp_mail==""){$imp_mail="c.roth@dev4impact.com";}
					$sender="noreply@icollect.live";
					$recipient=$imp_mail;
				
					$mail = new PHPMailer;
					$mail->isSMTP();
					// $mail->SMTPDebug = 2;
					// $mail->SMTPSecure = 'ssl';
					$mail->Debugoutput = 'html';
					$mail->Host = "d4i.maxapex.net";
					$mail->Port = 587;
					$mail->SMTPAuth = true;
					$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
					$mail->Username = ID_USER;
					$mail->Password = ID_PASS;
					$mail->setFrom('noreply@icollect.live', $_SESSION['name']);
					$mail->AddCC($imp_mail); 
					$mail->AddCC($imp_admin_mail); 
					$mail->AddCC($sm_mail); 
					$mail->AddBCC("croth53@icollect.live"); 
					$mail->addReplyTo($imp_mail, $imp_person_name);
					$mail->addAddress($imp_mail, $imp_person_name);
					$mail->Subject = $subject;
					$mail->msgHTML($message);
					$mail->AltBody = 'This is a plain-text message body';
			
					//send the message, check for errors
					if (!$mail->send()) {
						$save=0;
					} else {
						$save=1;
					}
					
					if($save==1){
					
						$sql = "SELECT v_order.customer_code||'-'||v_order.order_nr as file_name FROM v_order WHERE id_ord_order=$order_id";
					
						$rst = pg_query($conn, $sql);
						$row = pg_fetch_assoc($rst);
					
						$date = date_create();
						$timestamp = date_timestamp_get($date);
						
						$file = trim($row['file_name']);
						$file_name = str_replace(' ', '_', $file);
						
						$doc_filename=$file_name.'-83-'.$timestamp.'.pdf';
					
						$email_sender_company_id = $_SESSION['id_company'];
						$created_by = $user_id;
						$id_owner = $_SESSION['id_contact'];
						$created_date = $modify_date;
						
						$msg_recipients = $ord_imp_admin_id.','.$ord_imp_person_id.','.$ord_sm_person_id;
					
						$sql_mail = "insert into ord_document (ord_schedule_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
						email_sender_id, email_sender_company_id, msg_recipients) 
						values ($id_ord_schedule, '$doc_filename', 83, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
						$created_by, $email_sender_company_id, '$msg_recipients') RETURNING id_document";
						
						$result_mail = pg_query($conn, $sql_mail);
						if($result_mail){ 
							$doc=$doc_filename; 
							
							$arr = pg_fetch_assoc($result);
				
							$id_document = $arr['id_document'];
							$user_id = $_SESSION['id_user'];
						
							$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
							VALUES ($id_document, $user_id, 1)";
							pg_query($conn, $sql2);
						}
					}
				}
				
				$dom='1##'.$doc;
			} else {
				$dom=0;
			}

		break;
		
		
		case "save_invoice":
		
			$typ = $_GET["typ"];
			$conf = $_GET["conf"];
			$doc_filename = $_GET["doc_filename"];
			$ord_schedule_id = $_GET["ord_schedule_id"];
			$id_user = $_SESSION['id_user']; 
			$created_date = gmdate("Y/m/d H:i");
			$doc_Date = gmdate("Y/m/d");
			$pipeline = 0;
			
			if($typ == 'packing_list'){
				if($conf == 'supplier'){
					$doc_type = 8;
				} else
				if($conf == 'importer'){
					$doc_type = 178;
				}
			} else
			if($typ == 'invoice1'){
				$doc_type = 16;
				$pipeline = 1;
			} else
			if($typ == 'invoice2'){
				$doc_type = 176;
			} else
			if($typ == 'invoice_customs'){
				$doc_type = 179;
				$pipeline = 1;
			}
			
			if($pipeline == 1){
				$sql_ppl = "update ord_ocean_schedule set pipeline_sched_id=299 where id_ord_schedule=$ord_schedule_id"; 
				pg_query($conn, $sql_ppl);
			}
			
			if($doc_type == 8){
				$sql = "update ord_con_booking b set packlist_sup_status=1
					where ord_schedule_id=$ord_schedule_id
				and booking_segment=1"; 
				pg_query($conn, $sql);
			}
			
			if($doc_type == 178){
				$sql = "update ord_con_booking b set packlist_status=1
					where ord_schedule_id=$ord_schedule_id
				and booking_segment=1"; 
				pg_query($conn, $sql);
			}
			
			
			if($doc_type == 16){
				$sql1 = "update ord_con_booking b set inv1_amount=(select q1.total_invoice from 
				( select h.total_vgm_weight,c.proposal_price,
				h.total_vgm_weight*c.proposal_price total_invoice, 
				h.vgm_deliver_total, h.vgm_diff_total, c.proposal_price, h.vgm_diff_total*c.proposal_price total_diff
				 from ord_con_loading_header h, v_schedule_calc c
				 where h.ord_schedule_id=$ord_schedule_id
				 and c.id_ord_schedule=h.ord_schedule_id ) q1 )
				where ord_schedule_id=$ord_schedule_id
				and booking_segment=1 ";
				
				$result1 = pg_query($conn, $sql1);
				if ($result1) {
					$sql2 = "update ord_con_booking b set inv1_date=current_date
					where ord_schedule_id=$ord_schedule_id
					and booking_segment=1 ";
					
					$result2 = pg_query($conn, $sql2);
					if ($result2) {
						$sql3 = "update ord_con_booking b set inv1_by=$id_user, inv1_status=1
						where ord_schedule_id=$ord_schedule_id
						and booking_segment=1";
						
						$result3 = pg_query($conn, $sql3);
						if ($result3) {
							$sql4 = "update ord_proposal_calc set inv1_amount=(select inv1_amount from ord_con_booking
							where ord_schedule_id=$ord_schedule_id and booking_segment=1)";
							$result4 = pg_query($conn, $sql4);
						}
					}
				}
			}
			
		
			if($doc_type == 176){
				$sql1 = "update ord_con_booking b set inv2_amount=(select
					(h.vgm_diff_total*c.proposal_price) total_diff
					 from ord_con_loading_header h, v_schedule_calc c
					 where h.ord_schedule_id=$ord_schedule_id
					 and c.id_ord_schedule=h.ord_schedule_id)
				  where ord_schedule_id=$ord_schedule_id
				and booking_segment=1 ";
				
				$result1 = pg_query($conn, $sql1);
				if ($result1) {
					$sql2 = "update ord_con_booking b set inv2_date=current_date
					where ord_schedule_id=$ord_schedule_id
					and booking_segment=1 ";
					
					$result2 = pg_query($conn, $sql2);
					if ($result2) {
						$sql3 = "update ord_con_booking b set inv2_by=$id_user, inv2_status=1
						where ord_schedule_id=$ord_schedule_id
						and booking_segment=1";
						
						$result3 = pg_query($conn, $sql3);
						if ($result3) {
							$sql4 = "update ord_proposal_calc set inv1_amount=(select inv1_amount from ord_con_booking
							where ord_schedule_id=$ord_schedule_id and booking_segment=1)";
							$result4 = pg_query($conn, $sql4);
							
							if ($result4) {
								$sql5 = "update ord_con_loading_header set vgm_deliver_total=(
									select h.vgm_deliver_total
									from ord_con_loading_header h, v_schedule_calc c
									where h.ord_schedule_id=$ord_schedule_id
									and c.id_ord_schedule=h.ord_schedule_id), vgm_diff_total=(
									select h.vgm_diff_total
									from ord_con_loading_header h, v_schedule_calc c
									where h.ord_schedule_id=$ord_schedule_id
									and c.id_ord_schedule=h.ord_schedule_id)
								where ord_schedule_id=$ord_schedule_id";
								
								$result5 = pg_query($conn, $sql5);
							}
						}
					}
				}
			}
			

			$sql = "insert into ord_document ( ord_order_id, ord_schedule_id, doc_type_id, doc_Date, doc_filename,
                external_Ref1, external_ref2, 
                importer_id, client_id, exporter_id, user_id, active, read, created_date, message_viewed, message_read )
                SELECT   m.id_ord_order, s.id_ord_schedule, $doc_type, '$doc_Date', '$doc_filename',
                    m.order_nr as imp_reference_nr,
                    m.customer_reference_nr,
                    o.ord_imp_contact_id,
                    o.ord_cus_contact_id,
                    1, $id_user, 1, 0, '$created_date', 0, 0
                from v_order o, v_order_schedule s, v_order_msg m
                where s.id_ord_schedule=$ord_schedule_id
                and o.id_ord_order=s.ord_order_id
            and m.id_ord_order=s.ord_order_id
			RETURNING id_document";
			
			$result = pg_query($conn, $sql);
			
			if ($result) {
				$arr = pg_fetch_assoc($result);
				
				$id_document = $arr['id_document'];
				$user_id = $_SESSION['id_user'];
		
				$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
				VALUES ($id_document, $user_id, 1)";
				pg_query($conn, $sql2);


				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "refresh_invoice_tables":
			
			$type = $_GET["type"];
			$ord_schedule_id = $_GET["ord_schedule_id"];
			
			$sql_display="SELECT * FROM public.v_con_booking_display WHERE ord_schedule_id = $ord_schedule_id and booking_segment=1";
			$rs_display = pg_query($conn, $sql_display);
			$row_display = pg_fetch_assoc($rs_display);
			
			if($type == 'invoice1'){
				$total_vgm_weight = $row_display['total_vgm_weight'];
				$proposal_price = $row_display['proposal_price'];
				$inv1_amount = $row_display['inv1_amount'];
				$inv1_date = $row_display['inv1_date'];
				$inv1_by_name = $row_display['inv1_by_name'];
				
				$table ='<tr>
					<td>'.$total_vgm_weight.'</td>
					<td>'.$proposal_price.'</td>
					<td>'.$inv1_amount.'</td>
					<td>'.$inv1_date.'</td>
					<td>'.$inv1_by_name.'</td>
				</tr>';
			}
			
			if($type == 'invoice2'){
				$proposal_price = $row_display['proposal_price'];
				$vgm_deliver_total = $row_display['vgm_deliver_total'];
				$vgm_diff_total = $row_display['vgm_diff_total'];
				$inv2_amount = $row_display['inv2_amount'];
				$inv2_date = $row_display['inv2_date'];
				$inv2_by_name = $row_display['inv2_by_name'];
				
				$table ='<tr>
					<td>'.$vgm_deliver_total.'</td>
					<td>'.$vgm_diff_total.'</td>
					<td>'.$proposal_price.'</td>
					<td>'.$inv2_amount.'</td>
					<td>'.$inv2_date.'</td>
					<td>'.$inv2_by_name.'</td>
				</tr>';
			}
			
			$dom=$table;
		
		break;
		
		
		case "register_new_cooperative":
		
			$created_date = gmdate("Y/m/d H:i");
			$created_by = $_SESSION['id_contact'];
			
			if(isset($_GET["firstname"])){
				$firstname = pg_escape_string($_GET["firstname"]);
			} else { $firstname = ""; }
			
			if(isset($_GET["lastname"])){
				$lastname = pg_escape_string($_GET["lastname"]);
			} else { $lastname = ''; }
			
			if(isset($_GET["national_lang"])){
				$national_lang = $_GET["national_lang"];
				$req_national_lang = 'national_lang, '; $val_national_lang = "'$national_lang', ";
			} else { $national_lang = ""; $req_national_lang = ''; $val_national_lang = ''; }
			
			if(isset($_GET["p_phone"])){
				$p_phone = $_GET["p_phone"];
				$req_p_phone = 'p_phone, '; $val_p_phone = "'$p_phone', ";
			} else { $p_phone = ""; $req_p_phone = ''; $val_p_phone = ''; }
			
			if(isset($_GET["p_phone2"])){
				$p_phone2 = $_GET["p_phone2"];
				$req_p_phone2 = 'p_phone2, '; $val_p_phone2 = "'$p_phone2', ";
			} else { $p_phone2 = ""; $req_p_phone2 = ''; $val_p_phone2 = ''; }
			
			if(isset($_GET["p_phone3"])){
				$p_phone3 = $_GET["p_phone3"];
				$req_p_phone3 = 'p_phone3, '; $val_p_phone3 = "'$p_phone3', ";
			} else { $p_phone3 = ""; $req_p_phone3 = ''; $val_p_phone3 = ''; }
			
			if(isset($_GET["p_phone4"])){
				$p_phone4 = $_GET["p_phone4"];
				$req_p_phone4 = 'p_phone4, '; $val_p_phone4 = "'$p_phone4', ";
			} else { $p_phone4 = ""; $req_p_phone4 = ''; $val_p_phone4 = ''; }
			
			if(isset($_GET["p_phone5"])){
				$p_phone5 = $_GET["p_phone5"];
				$req_p_phone5 = 'p_phone5, '; $val_p_phone5 = "'$p_phone5', ";
			} else { $p_phone5 = ""; $req_p_phone5 = ''; $val_p_phone5 = ''; }
			
			if(isset($_GET["bankname"])){
				$bankname = $_GET["bankname"];
				$req_bankname = 'bankname, '; $val_bankname = "'$bankname', ";
			} else { $bankname = ""; $req_bankname = ''; $val_bankname = ''; }
			
			if(isset($_GET["p_email"])){
				$p_email = $_GET["p_email"];
				$req_p_email = 'p_email, '; $val_p_email = "'$p_email', ";
			} else { $p_email = ""; $req_p_email = ''; $val_p_email = ''; }
			
			if(isset($_GET["postalcode"])){
				$postalcode = $_GET["postalcode"];
				$req_postalcode = 'p_postalcode1, '; $val_postalcode = "'$postalcode', ";
			} else { $postalcode = ""; $req_postalcode = ''; $val_postalcode = ''; }
			
			if(isset($_GET["p_street"])){
				$p_street1 = $_GET["p_street"];
				$req_p_street1 = 'p_street1, '; $val_p_street1 = "'$p_street1', ";
			} else { $p_street1 = ""; $req_p_street1 = ''; $val_p_street1 = ''; }
			
			if(isset($_GET["town_name"])){
				$data = explode("@", $_GET["town_name"]);
				$town_name = $data[1];
				$id_town = $data[0];
				$req_town_name = 'town_name, id_town,'; $val_town_name = "'$town_name', $id_town,";
			} else { $town_name = ""; $req_town_name = ''; $val_town_name = ''; }
			
			if(isset($_GET["notes"])){
				$notes = $_GET["notes"];
				$req_notes = 'notes, '; $val_notes = "'$notes', ";
			} else { $notes = ""; $req_notes = ''; $val_notes = ''; }
			
			$sql_idcontact = "SELECT ($created_by*10000)+(to_char(now(),'MMDDHHMISS')::integer) As new_id FROM users LIMIT 1";
			$result_idcontact = pg_query($conn, $sql_idcontact);
			$row_idcontact = pg_fetch_assoc($result_idcontact);
			
			if(!empty($row_idcontact['new_id'])){
				$id_contact = $row_idcontact['new_id'];
				
				$sql = "INSERT INTO public.contact 
					(id_contact, firstname, lastname, $req_national_lang $req_p_phone $req_p_phone2 $req_p_phone3 $req_p_phone4 $req_p_phone5  
					$req_bankname $req_p_email $req_postalcode $req_p_street1 $req_town_name $req_notes
					created_date, created_by, id_type, id_supchain_type, id_category) 

				  VALUES 
					($id_contact, '{$firstname}', '{$lastname}', $val_national_lang $val_p_phone $val_p_phone2 $val_p_phone3 $val_p_phone4 $val_p_phone5
					$val_bankname $val_p_email $val_postalcode $val_p_street1 $val_town_name $val_notes
					'$created_date', '$created_by', 10, 331, 7)
				"; 
				
				$result = pg_query($conn, $sql);
	 
				if ($result) {
					$dom=1;
				} else {
					$dom=0;
				}
			
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "register_new_contact":
		
			$created_date = gmdate("Y/m/d H:i");
			$created_by = $_SESSION['id_contact'];
		
			if(isset($_GET["lastname"])){
				$lastname = pg_escape_string($_GET["lastname"]);
			} else { $lastname = ""; }
			
			if(isset($_GET["firstname"])){
				$firstname = pg_escape_string($_GET["firstname"]);
			} else { $firstname = ""; }
			
			if(isset($_GET["middlename"])){
				$middlename = pg_escape_string($_GET["middlename"]);
				$req_middlename = 'middlename, '; $val_middlename = "'{$middlename}', ";
			} else { $middlename = ""; $req_middlename = ''; $val_middlename = ''; }
			
			if(isset($_GET["gender"])){
				$gender = $_GET["gender"];
				$req_gender = 'id_gender, '; $val_gender = "'$gender', ";
			} else { $gender = ""; $req_gender = ''; $val_gender = ''; }
			
			if(isset($_GET["birth_date"])){
				$birth_date = $_GET["birth_date"];
				$req_birth_date = 'birth_date, '; $val_birth_date = "'$birth_date', ";
			} else { $birth_date = ""; $req_birth_date = ''; $val_birth_date = ''; }
			
			if(isset($_GET["birth_year"])){
				$birth_year = $_GET["birth_year"];
				$req_birth_year = 'birth_year, '; $val_birth_year = "'$birth_year', ";
			} else { $birth_year = ""; $req_birth_year = ''; $val_birth_year = ''; }
			
			if(isset($_GET["national_lang"])){
				$national_lang = $_GET["national_lang"];
				$req_national_lang = 'national_lang, '; $val_national_lang = "'$national_lang', ";
			} else { $national_lang = ""; $req_national_lang = ''; $val_national_lang = ''; }
			
			if(isset($_GET["primary_company"])){
				$primary_company = $_GET["primary_company"];
				$req_primary_company = 'id_primary_company, '; $val_primary_company = "'$primary_company', ";
			} else { $primary_company = ""; $req_primary_company = ''; $val_primary_company = ''; }
			
			// if(isset($_GET["street"])){
				// $street = $_GET["street"];
				// $req_street = 'p_streetno, '; $val_street = ''.$street.', ';
			// } else { $street = ""; $req_street = ''; $val_street = ''; }
			
			if(isset($_GET["p_street"])){
				$p_street = $_GET["p_street"];
				$req_p_street = 'p_street, '; $val_p_street = "'$p_street', ";
			} else { $p_street = ""; $req_p_street = ''; $val_p_street = ''; }
			
			// if(isset($_GET["p_street1"])){
				// $p_street1 = $_GET["p_street1"];
				// $req_p_street1 = 'p_street1, '; $val_p_street1 = ''.$p_street1.', ';
			// } else { $p_street1 = ""; $req_p_street1 = ''; $val_p_street1 = ''; }
			
			if(isset($_GET["id_town"])){
				$id_town = $_GET["id_town"];
				$req_id_town = 'id_town, '; $val_id_town = ''.$id_town.', ';
			} else { $id_town = ""; $req_id_town = ''; $val_id_town = ''; }
			
			if(isset($_GET["town_name"])){
				$town_name = $_GET["town_name"];
				$req_town_name = 'town_name, '; $val_town_name = "'$town_name', ";
			} else { $town_name = ""; $req_town_name = ''; $val_town_name = ''; }
	
			if(isset($_GET["name"])){
				$name = pg_escape_string($_GET["name"]);
				$req_name = 'name, '; $val_name = "'{$name}', ";
			} else { $name = ""; $req_name = ''; $val_name = ''; }
			
			// if(isset($_GET["p_postalcode"])){
				// $p_postalcode = $_GET["p_postalcode"];
				// $req_p_postalcode = 'p_postalcode, '; $val_p_postalcode = "'$p_postalcode', ";
			// } else { $p_postalcode = ""; $req_p_postalcode = ''; $val_p_postalcode = ''; }
			
			// if(isset($_GET["coordx"])){
				// $coordx = $_GET["coordx"];
				// $req_coordx = 'coordx, '; $val_coordx = "'$coordx', ";
			// } else { $coordx = ""; $req_coordx = ''; $val_coordx = ''; }
			
			// if(isset($_GET["coordy"])){
				// $coordy = $_GET["coordy"];
				// $req_coordy = 'coordy, '; $val_coordy = "'$coordy', ";
			// } else { $coordy = ""; $req_coordy = ''; $val_coordy = ''; }
			
			if(isset($_GET["notes"])){
				$notes = $_GET["notes"];
				$req_notes = 'notes, '; $val_notes = "'$notes', ";
			} else { $notes = ""; $req_notes = ''; $val_notes = ''; }
			
			if(isset($_GET["p_phone"])){
				$p_phone = $_GET["p_phone"];
				$req_p_phone = 'p_phone, '; $val_p_phone = "'$p_phone', ";
			} else { $p_phone = ""; $req_p_phone = ''; $val_p_phone = ''; }
			
			if(isset($_GET["p_phone2"])){
				$p_phone2 = $_GET["p_phone2"];
				$req_p_phone2 = 'p_phone2, '; $val_p_phone2 = "'$p_phone2', ";
			} else { $p_phone2 = ""; $req_p_phone2 = ''; $val_p_phone2 = ''; }
			
			if(isset($_GET["p_phone3"])){
				$p_phone3 = $_GET["p_phone3"];
				$req_p_phone3 = 'p_phone3, '; $val_p_phone3 = "'$p_phone3', ";
			} else { $p_phone3 = ""; $req_p_phone3 = ''; $val_p_phone3 = ''; }
			
			if(isset($_GET["p_phone4"])){
				$p_phone4 = $_GET["p_phone4"];
				$req_p_phone4 = 'p_phone4, '; $val_p_phone4 = "'$p_phone4', ";
			} else { $p_phone4 = ""; $req_p_phone4 = ''; $val_p_phone4 = ''; }
			
			if(isset($_GET["p_phone5"])){
				$p_phone5 = $_GET["p_phone5"];
				$req_p_phone5 = 'p_phone5, '; $val_p_phone5 = "'$p_phone5', ";
			} else { $p_phone5 = ""; $req_p_phone5 = ''; $val_p_phone5 = ''; }
			
			if(isset($_GET["p_email"])){
				$p_email = $_GET["p_email"];
				$req_p_email = 'p_email, '; $val_p_email = "'$p_email', ";
			} else { $p_email = ""; $req_p_email = ''; $val_p_email = ''; }
			
			if(isset($_GET["p_email2"])){
				$p_email2 = $_GET["p_email2"];
				$req_p_email2 = 'p_email2, '; $val_p_email2 = "'$p_email2', ";
			} else { $p_email2 = ""; $req_p_email2 = ''; $val_p_email2 = ''; }
			
			// if(isset($_GET["p_email3"])){
				// $p_email3 = $_GET["p_email3"];
				// $req_p_email3 = 'p_email3, '; $val_p_email3 = ''.$p_email3.', ';
			// } else { $p_email3 = ""; $req_p_email3 = ''; $val_p_email3 = ''; }
			
			if(isset($_GET["skype_id"])){
				$skype_id = $_GET["skype_id"];
				$req_skype_id = 'skype_id, '; $val_skype_id = "'$skype_id', ";
			} else { $skype_id = ""; $req_skype_id = ''; $val_skype_id = ''; }
			
			
			$sql = "INSERT INTO public.contact 
				(firstname, lastname, $req_middlename $req_gender $req_birth_date $req_birth_year $req_national_lang $req_primary_company 
				$req_p_street $req_id_town $req_town_name $req_name 
				$req_notes $req_p_phone $req_p_phone2 $req_p_phone3 $req_p_phone4 $req_p_phone5 $req_p_email $req_p_email2  
				$req_skype_id created_date, created_by, id_type) 

			  VALUES 
				('{$firstname}', '{$lastname}', $val_middlename $val_gender $val_birth_date $val_birth_year $val_national_lang $val_primary_company 
				$val_p_street $val_id_town $val_town_name $val_name 
				$val_notes $val_p_phone $val_p_phone2 $val_p_phone3 $val_p_phone4 $val_p_phone5 $val_p_email $val_p_email2  
				$val_skype_id '$created_date', '$created_by', 9)
			"; 

			$result = pg_query($conn, $sql);
 
			if ($result) {
				
				if(!empty($p_email)){
					
					$id_exporter = 0; $id_buyer = 0; $id_cooperative = 0;
					
					if(isset($_GET["id_supchain_type"])){
						$id_supchain_type = $_GET["id_supchain_type"];
						
						if($id_supchain_type == 113){ $id_exporter=$primary_company; } else { $id_exporter=0; }
						if($id_supchain_type == 114){ $id_buyer=$primary_company; } else { $id_buyer=0; }
						if($id_supchain_type == 331){ $id_cooperative=$primary_company; } else { $id_cooperative=0; }
					} 
			
					$sql_idcontact = "SELECT id_contact FROM contact WHERE firstname='$firstname' AND lastname='$lastname'";
					$result_idcontact = pg_query($conn, $sql_idcontact);
					$row_idcontact = pg_fetch_assoc($result_idcontact);
					
					if(!empty($row_idcontact['id_contact'])){
						$id_contact = $row_idcontact['id_contact'];
						
						if(isset($_GET["agent_type"])){
							
							if($_GET["agent_type"] == 621){ $agent_type = 1; }
							elseif($_GET["agent_type"] == 622){ $agent_type = 5; }
							elseif($_GET["agent_type"] == 623){ $agent_type = 6; }
							elseif($_GET["agent_type"] == 624){ $agent_type = 3; }
							elseif($_GET["agent_type"] == 637){ $agent_type = 2; }
							elseif($_GET["agent_type"] == 638){ $agent_type = 4; }
							elseif($_GET["agent_type"] == 777){ $agent_type = 8; }
							else { $agent_type = null; }
							
							$req_agent_type = 'agent_type, '; $val_agent_type = "'$agent_type', ";
						} else { $agent_type = ""; $req_agent_type = ''; $val_agent_type = ''; }
						
						$sql_user = "INSERT INTO public.users 
							(username, password, id_contact, id_exporter, id_buyer, idview, id_cooperative, created_date, created_by, $req_agent_type pwd_reset) 
						  VALUES 
							('$p_email', '1234', $id_contact, $id_exporter, $id_buyer, 0, $id_cooperative, '$created_date', '$created_by', $val_agent_type 0)
						"; 
						
						$result_user = pg_query($conn, $sql_user);
						
						if ($result_user) {
							$dom=1;
						} else {
							$sql_del="DELETE FROM public.contact WHERE id_contact=$id_contact";
							pg_query($conn, $sql_del);
							
							$dom=0;
						}
					}
				}

			} else {
				$dom=0;
			}
			
		break;
		

		case "puchase_order":
		
			$ord_order_id = $_GET["ord_order_id"];
			$cus_incoterms_id = $_GET["cus_incoterms_id"];
			
			$sql_doc = "select id_document, doc_filename from ord_document
			where ord_order_id=$ord_order_id and active=1
			and doc_type_id=5";

			$rs_doc = pg_query($conn, $sql_doc);
			$row_doc = pg_fetch_assoc($rs_doc);

			if($row_doc['doc_filename']!=""){
				$dom = '3##'.$row_doc['doc_filename'].'##'.$row_doc['id_document'];
				
			} else {
				
				if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
					$sql_header = "select v_order_schedule.id_ord_schedule,
						v_order_schedule.supplier_name,
						v_order_schedule.person_name,
						v_order.sup_reference_nr as sup_ord_ref_no,
						v_order_schedule.sup_reference_nr as sup_ship_ref_no,
						v_order_schedule.product_code,
						v_order_schedule.product_name,
						product.product_name_de,
						product.q_dobi,
						product.q_ffa,
						product.q_humidity,
						product.q_impurity,
						product.q_m_i,
						product.q_mineraloil,
						v_order_schedule.package_name,
						v_order_schedule.port_name, 
						v_order_schedule.pod_code, 
						v_order_schedule.pod_id,
						v_order.pod_name AS cus_pod_name,
						v_order_schedule.pol_id,
						v_order_schedule.pol_code,
						v_order_schedule.pol_country as country_origin,
						v_order_schedule.incoterms AS sup_incoterms,
						v_order_schedule.offer_validity_date,
						v_order_schedule.notes_sup,
						get_contact_email (v_order_schedule.supplier_person_id) AS email_contact,
						v_order.sm_person_name AS sm_manager,
						v_order_msg.ord_sm_person_id,
						v_order_msg.sm_mail,
						v_order_msg.sm_mail3,
						v_order_msg.imp_mail,
						v_order_msg.imp_mail3,
						v_order_msg.ord_imp_person_id,
						v_order_msg.cus_email,
						v_order_msg.ord_cus_person_id,
						v_order_msg.cus_email3,
						v_order_msg.imp_admin_mail,
						v_order_msg.imp_admin_mail3,
						v_order_msg.ord_imp_admin_id,
						v_order_msg.cus_admin_mail,
						v_order_msg.cus_admin_mail3,
						v_order_msg.ord_cus_admin_id,
						v_order_msg.sup_mail,
						v_order_msg.ord_sup_person_id,
						v_order_msg.sup_admin_mail,
						v_order_msg.ord_sup_admin_id,
						v_order_msg.id_ord_order,
						v_order.customer_code|| '-'|| v_order.customer_reference_nr||'-'||v_order.product_code AS order_number,
						v_order.importer,
						v_order.ord_imp_contact_id,
						v_order_msg.imp_phone,
						v_order_msg.imp_skype,
						v_order.ord_imp_person_id,
						get_contact_name (v_order.ord_imp_person_id) ord_imp_person_name,
						get_contact_pstreet (v_order.ord_imp_contact_id) importer_street,
						get_contact_postalcode (v_order.ord_imp_contact_id) importer_postalcode,
						get_contact_paddress (v_order.ord_imp_contact_id) importer_town,
						v_order_msg.order_nr AS imp_reference_nr,
						v_order_schedule.order_nr,
						get_contact_name (v_order.ord_cus_contact_id) customer_name,
						get_contact_pstreet (v_order.ord_cus_contact_id) customer_street,
						get_contact_postalcode (v_order.ord_cus_contact_id) customer_postalcode,
						get_contact_paddress (v_order.ord_cus_contact_id) customer_town,
						get_contact_name (v_order.ord_cus_person_id) customer_contact,
						getregvalue (v_order_schedule.cus_incoterms_id) AS cus_incoterms,
						get_contact_name (v_order.ord_fa_contact_id) fa_name,
						get_contact_pstreet (v_order.ord_fa_contact_id) fa_street,
						get_contact_postalcode (v_order.ord_fa_contact_id) fa_postalcode,
						get_contact_paddress (v_order.ord_fa_contact_id) fa_town,
						get_contact_name (v_order_schedule. supplier_contact_id) sup_name,
						get_contact_pstreet (v_order_schedule. supplier_contact_id) sup_street,
						get_contact_postalcode (v_order_schedule. supplier_contact_id) sup_postalcode,
						get_contact_paddress (v_order_schedule. supplier_contact_id) sup_town,
						v_order_msg.customer_reference_nr,
						v_order.customer_name,
						to_char (v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
						to_char (v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
						v_order_schedule.created_by_name,
						v_order.var_basecontract,
						v_order.var_misc_cost,
						v_order.var_parity,
						v_order.var_payment_cond,
						v_order.var_quality
						FROM v_order_schedule,
						v_order_msg,
						v_order,
						product
						WHERE v_order_msg.id_ord_order=v_order_schedule.ord_order_id
						AND v_order.id_ord_order=v_order_schedule.ord_order_id
						AND v_order_schedule.id_ord_schedule=(select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id)
						AND product.id_product=v_order_Schedule.product_id
					";
				
				} else {
					$sql_header = "SELECT v_order_schedule.order_nr,
						v_order_schedule.id_ord_schedule,
						v_order_schedule.supplier_name,
						v_order_schedule.person_name,
						v_order.sup_reference_nr as sup_ord_ref_no,
						v_order_schedule.sup_reference_nr as sup_ship_ref_no,    
						v_order_schedule.product_code,
						v_order_schedule.product_name,
						product.product_name_de,
						product.q_dobi,
						product.q_ffa,
						product.q_humidity,
						product.q_impurity,
						product.q_m_i,
						product.q_mineraloil,
						v_order_schedule.package_name,
						v_order_schedule.port_name,
						v_ocean_freight.pol_townport_id,
						v_order_schedule.pol_id,
						v_order_schedule.pol_code,
						v_order_schedule.pol_country as country_origin,
						v_order_schedule.pod_code,
						v_order_schedule.pod_id,
						v_order.pod_name as cus_pod_name,
						v_order_schedule.incoterms as sup_incoterms,
						v_order_schedule.offer_validity_date,
						v_order_schedule.notes_sup,
						get_contact_email(v_order_schedule.supplier_person_id) AS email_contact,
						v_order.sm_person_name as sm_manager,
						v_order_msg.ord_sm_person_id,
						v_order_msg.sm_mail,
						v_order_msg.sm_mail3,
						v_order_msg.imp_mail,
						v_order_msg.imp_mail3,
						v_order_msg.ord_imp_person_id,
						v_order_msg.cus_email,
						v_order_msg.cus_email3,
						v_order_msg.ord_cus_person_id,
						v_order_msg.imp_admin_mail,
						v_order_msg.imp_admin_mail3,
						v_order_msg.ord_imp_admin_id,
						v_order_msg.cus_admin_mail,
						v_order_msg.cus_admin_mail3,
						v_order_msg.ord_cus_admin_id,
						v_order_msg.sup_mail,
						v_order_msg.ord_sup_person_id,
						v_order_msg.sup_admin_mail,
						v_order_msg.ord_sup_admin_id,
						v_order_msg.id_ord_order,
						v_order.customer_code||'-'||v_order.customer_reference_nr||'-'||v_order.product_code as order_number,
						v_order.importer,
						v_order.ord_imp_contact_id,
						v_order_msg.imp_phone,
						v_order_msg.imp_skype,
						v_order.ord_imp_person_id,
						get_contact_name(v_order.ord_imp_person_id) ord_imp_person_name,
						get_contact_pstreet(v_order.ord_imp_contact_id) importer_street,
						get_contact_postalcode(v_order.ord_imp_contact_id) importer_postalcode,
						get_contact_paddress(v_order.ord_imp_contact_id) importer_town,
						v_order_msg.order_nr as imp_reference_nr,
						get_contact_name(v_order.ord_cus_contact_id) customer_name,
						get_contact_pstreet(v_order.ord_cus_contact_id) customer_street,
						get_contact_postalcode(v_order.ord_cus_contact_id) customer_postalcode,
						get_contact_paddress(v_order.ord_cus_contact_id) customer_town,
						get_contact_name(v_order.ord_cus_person_id) customer_contact,
						getregvalue(v_order_schedule.cus_incoterms_id) as cus_incoterms,
						get_contact_name (v_order.ord_fa_contact_id) fa_name,
						get_contact_pstreet (v_order.ord_fa_contact_id) fa_street,
						get_contact_postalcode (v_order.ord_fa_contact_id) fa_postalcode,
						get_contact_paddress (v_order.ord_fa_contact_id) fa_town,
						get_contact_name (v_order_schedule. supplier_contact_id) sup_name,
						get_contact_pstreet (v_order_schedule. supplier_contact_id) sup_street,
						get_contact_postalcode (v_order_schedule. supplier_contact_id) sup_postalcode,
						get_contact_paddress (v_order_schedule. supplier_contact_id) sup_town,
						v_order_msg.customer_reference_nr,
						v_order.customer_name,
						v_ocean_freight.dem_pol_cost_after,
						v_ocean_freight.dem_pol_free,
						v_ocean_freight.dem_pod_cost_after,
						v_ocean_freight.dem_pod_free,
						to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
						to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
						v_order_schedule.created_by_name,
						   v_order.var_basecontract,
						   v_order.var_misc_cost,
						   v_order.var_parity,
						   v_order.var_payment_cond,
						   v_order.var_quality
					   FROM v_order_schedule, v_order_msg, v_order, v_ocean_freight, product
					   where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
					   and v_order.id_ord_order=v_order_schedule.ord_order_id
					   and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id )
					   and v_ocean_freight.sequence_nr=1 and v_ocean_freight.id_ord_schedule=v_order_schedule.id_ord_schedule
					and product.id_product=v_order_Schedule.product_id
					";
				}

				$rs_header = pg_query($conn, $sql_header);
				$row_header = pg_fetch_assoc($rs_header);
	
	
				$order_nr = $row_header['order_nr'];
				$id_ord_schedule = $row_header['id_ord_schedule'];
				$supplier_name = $row_header['supplier_name'];
				$person_name = $row_header['person_name'];
				$sup_ord_ref_no = $row_header['sup_ord_ref_no'];
				$sup_ship_ref_no = $row_header['sup_ship_ref_no'];
				$product_code = $row_header['product_code'];
				$product_name = $row_header['product_name'];
				$product_name_de = $row_header['product_name_de'];
				$q_dobi = $row_header['q_dobi'];
				$q_ffa = $row_header['q_ffa'];
				$q_humidity = $row_header['q_humidity'];
				$q_impurity = $row_header['q_impurity'];
				$q_m_i = $row_header['q_m_i'];
				$q_mineraloil = $row_header['q_mineraloil'];
				$package_name = $row_header['package_name'];
				$port_name = $row_header['port_name'];
				$pol_townport_id = $row_header['pol_townport_id'];
				$pol_id = $row_header['pol_id'];
				$pol_code = $row_header['pol_code'];
				$country_origin = $row_header['country_origin'];
				$pod_code = $row_header['pod_code'];
				$pod_id = $row_header['pod_id'];
				$cus_pod_name = $row_header['cus_pod_name'];
				$sup_incoterms = $row_header['sup_incoterms'];
				$offer_validity_date = $row_header['offer_validity_date'];
				$notes_sup = $row_header['notes_sup'];
				$email_contact = $row_header['email_contact'];
				$sm_manager = $row_header['sm_manager'];
				$ord_sm_person_id = $row_header['ord_sm_person_id'];
				$sm_mail = $row_header['sm_mail'];
				$sm_mail3 = $row_header['sm_mail3'];
				$imp_mail = $row_header['imp_mail'];
				$imp_mail3 = $row_header['imp_mail3'];
				$cus_email = $row_header['cus_email'];
				$cus_email3 = $row_header['cus_email3'];
				$ord_cus_person_id = $row_header['ord_cus_person_id'];
				$imp_admin_mail = $row_header['imp_admin_mail'];
				$imp_admin_mail3 = $row_header['imp_admin_mail3'];
				$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
				$cus_admin_mail = $row_header['cus_admin_mail'];
				$cus_admin_mail3 = $row_header['cus_admin_mail3'];
				$ord_cus_admin_id = $row_header['ord_cus_admin_id'];
				$sup_mail = $row_header['sup_mail'];
				$ord_sup_person_id = $row_header['ord_sup_person_id'];
				$sup_admin_mail = $row_header['sup_admin_mail'];
				$ord_sup_admin_id = $row_header['ord_sup_admin_id'];
				$order_number = $row_header['order_number'];
				$importer = $row_header['importer'];
				$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
				$imp_phone = $row_header['imp_phone'];
				$imp_skype = $row_header['imp_skype'];
				$ord_imp_person_id = $row_header['ord_imp_person_id'];
				$ord_imp_person_name = $row_header['ord_imp_person_name'];
				$importer_street = $row_header['importer_street'];
				$importer_postalcode = $row_header['importer_postalcode'];
				$importer_town = $row_header['importer_town'];
				$fa_name = $row_header['fa_name'];
				$fa_street = $row_header['fa_street'];
				$fa_postalcode = $row_header['fa_postalcode'];
				$fa_town = $row_header['fa_town'];
				$sup_name = $row_header['sup_name'];
				$sup_street = $row_header['sup_street'];
				$sup_postalcode = $row_header['sup_postalcode'];
				$sup_town = $row_header['sup_town'];
				$imp_reference_nr = $row_header['imp_reference_nr'];
				$customer_name = $row_header['customer_name'];
				$customer_street = $row_header['customer_street'];
				$customer_postalcode = $row_header['customer_postalcode'];
				$customer_town = $row_header['customer_town'];
				$customer_contact = $row_header['customer_contact'];
				$cus_incoterms = $row_header['cus_incoterms'];
				$customer_reference_nr = $row_header['customer_reference_nr'];
				$dem_pol_cost_after = $row_header['dem_pol_cost_after'];
				$dem_pol_free = $row_header['dem_pol_free'];
				$dem_pod_cost_after = $row_header['dem_pod_cost_after'];
				$dem_pod_free = $row_header['dem_pod_free'];
				$proposal_date = $row_header['proposal_date'];
				$created_date = $row_header['created_date'];
				$created_by_name = $row_header['created_by_name'];
				$var_basecontract = $row_header['var_basecontract'];
				$var_misc_cost = $row_header['var_misc_cost'];
				$var_parity = $row_header['var_parity'];
				$var_payment_cond = $row_header['var_payment_cond'];
				$var_quality = $row_header['var_quality'];
				
				
				if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
					$Lieferbedingungen = $cus_incoterms .' '.$port_name;
					$Einkaufsbedingungen = $sup_incoterms;
					$title='Lieferplanung';
					
				} else {
					$Lieferbedingungen = $cus_incoterms .' '.$cus_pod_name;
					$Einkaufsbedingungen = $sup_incoterms .' '.$port_name;
					$title='Purchase Order';
				}
				
				$sql_detail = "select order_nr||'.'||order_ship_nr as imp_no, 
					v.customer_reference_nr||'.'||v.customer_ref_ship_nr as cus_no, 
					to_char(month_etd,'Mon-yyyy')||'/'||week_etd etd_month, 
					nr_containers, v.customer_ref_ship_nr,
					to_char(weight_shipment,'999G999D9') as weight, 
					v.supplier_reference_nr as sup_no,
					v.supplier_lastname,
					pol_code, 
					pol_country, 
					pol_country_name, 
					v.ord_cus_contact_code
					from v_order_schedule v where ord_order_id=$ord_order_id
					order by order_ship_nr::integer
				";
				
				$tb_content='';
				$rs_detail = pg_query($conn, $sql_detail);
				while($row_detail = pg_fetch_assoc($rs_detail)) {
					$tb_content .='<tr>
						<td>'.$row_detail['imp_no'].'</td>
						<td>'.$row_detail['customer_ref_ship_nr'].'</td>
						<td>'.$row_detail['supplier_lastname'].'</td>
						<td>'.$row_detail['sup_no'].'</td>
						<td>'.$row_detail['etd_month'].'</td>
						<td>'.$row_detail['nr_containers'].'</td>
						<td>'.$row_detail['weight'].'</td>
						<td>'.strtoupper($row_detail['pol_country']).'</td>
					</tr>';
				}
				
				
				$content='<div class="row text-center" style="padding-top:20px; padding-bottom:20px;">
					<h1>'.$importer.'</h1>
					'.$importer_street.' '.$importer_postalcode.', '.$importer_town.' 
					<hr/>
				</div>
			
				<div class="row">
					<div class="col-md-12">
						'.$fa_name.'<br/>
						'.$fa_street.'<br/>
						'.$fa_postalcode.' '.$fa_town.'<br/>
					</div>
				</div>
				
				
				<div class="row" font-size:11px;">
					<div class="col-md-12 no-padding" style="margin-top:30px;">
						<div class="col-md-10"><h3>'.$title.'</h3></div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:30px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Unsere Kontrakt Nr</label></div>
						<div class="col-md-10">'.$imp_reference_nr.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Datum</label></div>
						<div class="col-md-10">'.gmdate("Y/m/d").'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Importeur</label></div>
						<div class="col-md-10">'.$importer.', '.$importer_postalcode.' '.$importer_town.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Kunde</label></div>
						<div class="col-md-10">'.$customer_name.', '.$customer_postalcode.' '.$customer_town.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Kunden-Kontrakt-Nr</label></div>
						<div class="col-md-10">'.$customer_reference_nr.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Ware</label></div>
						<div class="col-md-10">'.$product_name_de.' ('.$product_code.')</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Verpackung</label></div>
						<div class="col-md-10">'.$package_name.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Lieferbedingungen</label></div>
						<div class="col-md-10">'.$Lieferbedingungen.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Einkaufsbedingungen</label></div>
						<div class="col-md-10">'.$Einkaufsbedingungen.'</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label">Ware</label></div>
						<div class="col-md-10">
							<table class="table table-striped table-bordered" style="font-size:11px;">
								<thead>
									<tr>
										<th>Unsere PO-Nr.</th>
										<th>Kd-PO-Nr.</th>
										<th>Lieferant</th>
										<th>Lief.-PO-Nr.</th>
										<th>ETD/Woche</th>
										<th>Anz. Container</th>
										<th>Gewicht</th>
										<th>Ursprung</th>
									</tr>
								</thead>
								
								<tbody>
									'.$tb_content.'
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"></div>
						<div class="col-md-5">
							<label class="contract_label">ITS</label><br/>
							'.$fa_name.'
						</div>
						<div class="col-md-5">
							<label class="contract_label">Importeur</label><br/>
							'.$importer.'
						</div>
					</div>
					
				</div>';
		
				$dom = $content.'##0##0';
			}

		break;
		
		
		
		case "puchase_order_supp":
		
			$ord_order_id = $_GET["ord_order_id"];
			$cus_incoterms_id = $_GET["cus_incoterms_id"];
		
			if(!empty($_GET["supplier_contact_id"])){
				$supplier_contact_id = $_GET["supplier_contact_id"];
				$cond=" AND v_order_schedule.supplier_contact_id = $supplier_contact_id ";
				$details_cond=" and s.supplier_contact_id = $supplier_contact_id ";
				$footer_cond=" and supplier_contact_id = $supplier_contact_id ";
			} else { $cond=""; $details_cond=""; $footer_cond=""; }
			
			$sql_doc = "select id_document, doc_filename from ord_document
			where ord_order_id=$ord_order_id
			and doc_type_id=4";

			$rs_doc = pg_query($conn, $sql_doc);
			$row_doc = pg_fetch_assoc($rs_doc);

			if($row_doc['doc_filename']!=""){
				$dom = '3##'.$row_doc['doc_filename'].'##'.$row_doc['id_document'];
				
			} else {
				
				// if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
					$sql_header = "select v_order_schedule.id_ord_schedule,
						v_order_schedule.supplier_name,
						v_order_schedule.supplier_contact_id,
						v_order_schedule.person_name,
						v_order.sup_reference_nr as sup_ord_ref_no,
						v_order_schedule.sup_reference_nr as sup_ship_ref_no,
						v_order_schedule.product_code,
						v_order_schedule.product_name,
						product.product_name_de,
						product.q_dobi,
						product.q_ffa,
						product.q_humidity,
						product.q_impurity,
						product.q_m_i,
						product.q_mineraloil,
						v_order_schedule.package_name,
						v_order_schedule.port_name, 
						v_order_schedule.pod_code, 
						v_order_schedule.pod_id,
						v_order.pod_name AS cus_pod_name,
						v_order_schedule.pol_id,
						v_order_schedule.pol_code,
						v_order_schedule.pol_country as country_origin,
						v_order_schedule.incoterms AS sup_incoterms,
						v_order_schedule.offer_validity_date,
						v_order_schedule.notes_sup,
						get_contact_email (v_order_schedule.supplier_person_id) AS email_contact,
						v_order.sm_person_name AS sm_manager,
						v_order_msg.ord_sm_person_id,
						v_order_msg.sm_mail,
						v_order_msg.sm_mail3,
						v_order_msg.imp_mail,
						v_order_msg.imp_mail3,
						v_order_msg.ord_imp_person_id,
						v_order_msg.cus_email,
						v_order_msg.ord_cus_person_id,
						v_order_msg.cus_email3,
						v_order_msg.imp_admin_mail,
						v_order_msg.imp_admin_mail3,
						v_order_msg.ord_imp_admin_id,
						v_order_msg.cus_admin_mail,
						v_order_msg.cus_admin_mail3,
						v_order_msg.ord_cus_admin_id,
						v_order_msg.sup_mail,
						v_order_msg.ord_sup_person_id,
						v_order_msg.sup_admin_mail,
						v_order_msg.ord_sup_admin_id,
						v_order_msg.id_ord_order,
						v_order.customer_code|| '-'|| v_order.order_nr||'-'||v_order.product_code AS order_number,
						v_order.importer,
						v_order.ord_imp_contact_id,
						v_order_msg.imp_phone,
						v_order_msg.imp_skype,
						v_order.ord_imp_person_id,
						get_contact_name (v_order.ord_imp_person_id) ord_imp_person_name,
						get_contact_pstreet (v_order.ord_imp_contact_id) importer_street,
						get_contact_postalcode (v_order.ord_imp_contact_id) importer_postalcode,
						get_contact_paddress (v_order.ord_imp_contact_id) importer_town,
						v_order_msg.order_nr AS imp_reference_nr,
						v_order_schedule.order_nr,
						get_contact_name (v_order.ord_cus_contact_id) customer_name,
						get_contact_pstreet (v_order.ord_cus_contact_id) customer_street,
						get_contact_postalcode (v_order.ord_cus_contact_id) customer_postalcode,
						get_contact_paddress (v_order.ord_cus_contact_id) customer_town,
						get_contact_name (v_order.ord_cus_person_id) customer_contact,
						getregvalue (v_order_schedule.cus_incoterms_id) AS cus_incoterms,
						get_contact_name (v_order.ord_fa_contact_id) fa_name,
						get_contact_pstreet (v_order.ord_fa_contact_id) fa_street,
						get_contact_postalcode (v_order.ord_fa_contact_id) fa_postalcode,
						get_contact_paddress (v_order.ord_fa_contact_id) fa_town,
						get_contact_name (v_order_schedule. supplier_contact_id) sup_name,
						get_contact_pstreet (v_order_schedule. supplier_contact_id) sup_street,
						get_contact_postalcode (v_order_schedule. supplier_contact_id) sup_postalcode,
						get_contact_paddress (v_order_schedule. supplier_contact_id) sup_town,
						v_order_msg.customer_reference_nr,
						v_order.customer_name,
						to_char (v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
						to_char (v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
						v_order_schedule.created_by_name,
						v_order.var_basecontract,
						v_order.var_misc_cost,
						v_order.var_parity,
						v_order.var_payment_cond,
						v_order.var_quality
						FROM v_order_schedule,
						v_order_msg,
						v_order,
						product
						WHERE v_order_msg.id_ord_order=v_order_schedule.ord_order_id
						AND v_order.id_ord_order=v_order_schedule.ord_order_id
						AND v_order_schedule.id_ord_schedule in (select id_ord_schedule from v_order_schedule where ord_order_id=$ord_order_id)
						AND product.id_product=v_order_Schedule.product_id
						$cond
					";
				
				// } else {
					// $sql_header = "SELECT v_order_schedule.order_nr,
						// v_order_schedule.id_ord_schedule,
						// v_order_schedule.supplier_contact_id,
						// v_order_schedule.supplier_name,
						// v_order_schedule.person_name,
						// v_order.sup_reference_nr as sup_ord_ref_no,
						// v_order_schedule.sup_reference_nr as sup_ship_ref_no,    
						// v_order_schedule.product_code,
						// v_order_schedule.product_name,
						// product.product_name_de,
						// product.q_dobi,
						// product.q_ffa,
						// product.q_humidity,
						// product.q_impurity,
						// product.q_m_i,
						// product.q_mineraloil,
						// v_order_schedule.package_name,
						// v_order_schedule.port_name,
						// v_ocean_freight.pol_townport_id,
						// v_order_schedule.pol_id,
						// v_order_schedule.pol_code,
						// v_order_schedule.pol_country as country_origin,
						// v_order_schedule.pod_code,
						// v_order_schedule.pod_id,
						// v_order.pod_name as cus_pod_name,
						// v_order_schedule.incoterms as sup_incoterms,
						// v_order_schedule.offer_validity_date,
						// v_order_schedule.notes_sup,
						// get_contact_email(v_order_schedule.supplier_person_id) AS email_contact,
						// v_order.sm_person_name as sm_manager,
						// v_order_msg.ord_sm_person_id,
						// v_order_msg.sm_mail,
						// v_order_msg.sm_mail3,
						// v_order_msg.imp_mail,
						// v_order_msg.imp_mail3,
						// v_order_msg.ord_imp_person_id,
						// v_order_msg.cus_email,
						// v_order_msg.cus_email3,
						// v_order_msg.ord_cus_person_id,
						// v_order_msg.imp_admin_mail,
						// v_order_msg.imp_admin_mail3,
						// v_order_msg.ord_imp_admin_id,
						// v_order_msg.cus_admin_mail,
						// v_order_msg.cus_admin_mail3,
						// v_order_msg.ord_cus_admin_id,
						// v_order_msg.sup_mail,
						// v_order_msg.ord_sup_person_id,
						// v_order_msg.sup_admin_mail,
						// v_order_msg.ord_sup_admin_id,
						// v_order_msg.id_ord_order,
						// v_order.customer_code||'-'||v_order.order_nr||'-'||v_order.product_code as order_number,
						// v_order.importer,
						// v_order.ord_imp_contact_id,
						// v_order_msg.imp_phone,
						// v_order_msg.imp_skype,
						// v_order.ord_imp_person_id,
						// get_contact_name(v_order.ord_imp_person_id) ord_imp_person_name,
						// get_contact_pstreet(v_order.ord_imp_contact_id) importer_street,
						// get_contact_postalcode(v_order.ord_imp_contact_id) importer_postalcode,
						// get_contact_paddress(v_order.ord_imp_contact_id) importer_town,
						// v_order_msg.order_nr as imp_reference_nr,
						// get_contact_name(v_order.ord_cus_contact_id) customer_name,
						// get_contact_pstreet(v_order.ord_cus_contact_id) customer_street,
						// get_contact_postalcode(v_order.ord_cus_contact_id) customer_postalcode,
						// get_contact_paddress(v_order.ord_cus_contact_id) customer_town,
						// get_contact_name(v_order.ord_cus_person_id) customer_contact,
						// getregvalue(v_order_schedule.cus_incoterms_id) as cus_incoterms,
						// get_contact_name (v_order.ord_fa_contact_id) fa_name,
						// get_contact_pstreet (v_order.ord_fa_contact_id) fa_street,
						// get_contact_postalcode (v_order.ord_fa_contact_id) fa_postalcode,
						// get_contact_paddress (v_order.ord_fa_contact_id) fa_town,
						// get_contact_name (v_order_schedule. supplier_contact_id) sup_name,
						// get_contact_pstreet (v_order_schedule. supplier_contact_id) sup_street,
						// get_contact_postalcode (v_order_schedule. supplier_contact_id) sup_postalcode,
						// get_contact_paddress (v_order_schedule. supplier_contact_id) sup_town,
						// v_order_msg.customer_reference_nr,
						// v_order.customer_name,
						// v_ocean_freight.dem_pol_cost_after,
						// v_ocean_freight.dem_pol_free,
						// v_ocean_freight.dem_pod_cost_after,
						// v_ocean_freight.dem_pod_free,
						// to_char(v_order_schedule.modified_date, 'dd.mm.yyyy'::text) AS proposal_date,
						// to_char(v_order_schedule.created_date, 'dd.mm.yyyy'::text) AS created_date,
						// v_order_schedule.created_by_name,
						   // v_order.var_basecontract,
						   // v_order.var_misc_cost,
						   // v_order.var_parity,
						   // v_order.var_payment_cond,
						   // v_order.var_quality
					   // FROM v_order_schedule, v_order_msg, v_order, v_ocean_freight, product
					   // where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
					   // and v_order.id_ord_order=v_order_schedule.ord_order_id
					   // and v_order_schedule.id_ord_schedule = ( select id_ord_schedule from v_order_schedule where ord_order_id=$ord_order_id )
					   // and v_ocean_freight.sequence_nr=1 and v_ocean_freight.id_ord_schedule=v_order_schedule.id_ord_schedule
					   // and product.id_product=v_order_Schedule.product_id
						// $cond
					// ";
				// }
	
	
				$rs_header = pg_query($conn, $sql_header);
				$row_header = pg_fetch_assoc($rs_header);
	
	
				$order_nr = $row_header['order_nr'];
				$id_ord_schedule = $row_header['id_ord_schedule'];
				$supplier_name = $row_header['supplier_name']; 
				$person_name = $row_header['person_name'];
				$sup_ord_ref_no = $row_header['sup_ord_ref_no'];
				$sup_ship_ref_no = $row_header['sup_ship_ref_no'];
				$product_code = $row_header['product_code'];
				$product_name = $row_header['product_name'];
				$product_name_de = $row_header['product_name_de'];
				$q_dobi = $row_header['q_dobi'];
				$q_ffa = $row_header['q_ffa'];
				$q_humidity = $row_header['q_humidity'];
				$q_impurity = $row_header['q_impurity'];
				$q_m_i = $row_header['q_m_i'];
				$q_mineraloil = $row_header['q_mineraloil'];
				$package_name = $row_header['package_name'];
				$port_name = $row_header['port_name'];
				$pol_townport_id = $row_header['pol_townport_id'];
				$pol_id = $row_header['pol_id'];
				$pol_code = $row_header['pol_code'];
				$country_origin = $row_header['country_origin'];
				$pod_code = $row_header['pod_code'];
				$pod_id = $row_header['pod_id'];
				$cus_pod_name = $row_header['cus_pod_name'];
				$sup_incoterms = $row_header['sup_incoterms'];
				$offer_validity_date = $row_header['offer_validity_date'];
				$notes_sup = $row_header['notes_sup'];
				$email_contact = $row_header['email_contact'];
				$sm_manager = $row_header['sm_manager'];
				$ord_sm_person_id = $row_header['ord_sm_person_id'];
				$sm_mail = $row_header['sm_mail'];
				$sm_mail3 = $row_header['sm_mail3'];
				$imp_mail = $row_header['imp_mail'];
				$imp_mail3 = $row_header['imp_mail3'];
				$cus_email = $row_header['cus_email'];
				$cus_email3 = $row_header['cus_email3'];
				$ord_cus_person_id = $row_header['ord_cus_person_id'];
				$imp_admin_mail = $row_header['imp_admin_mail'];
				$imp_admin_mail3 = $row_header['imp_admin_mail3'];
				$ord_imp_admin_id = $row_header['ord_imp_admin_id'];
				$cus_admin_mail = $row_header['cus_admin_mail'];
				$cus_admin_mail3 = $row_header['cus_admin_mail3'];
				$ord_cus_admin_id = $row_header['ord_cus_admin_id'];
				$sup_mail = $row_header['sup_mail'];
				$ord_sup_person_id = $row_header['ord_sup_person_id'];
				$sup_admin_mail = $row_header['sup_admin_mail'];
				$ord_sup_admin_id = $row_header['ord_sup_admin_id'];
				$order_number = $row_header['order_number'];
				$importer = $row_header['importer'];
				$ord_imp_contact_id = $row_header['ord_imp_contact_id'];
				$imp_phone = $row_header['imp_phone'];
				$imp_skype = $row_header['imp_skype'];
				$ord_imp_person_id = $row_header['ord_imp_person_id'];
				$ord_imp_person_name = $row_header['ord_imp_person_name'];
				$importer_street = $row_header['importer_street'];
				$importer_postalcode = $row_header['importer_postalcode'];
				$importer_town = $row_header['importer_town'];
				$fa_name = $row_header['fa_name'];
				$fa_street = $row_header['fa_street'];
				$fa_postalcode = $row_header['fa_postalcode'];
				$fa_town = $row_header['fa_town'];
				$sup_name = $row_header['sup_name'];
				$sup_street = $row_header['sup_street'];
				$sup_postalcode = $row_header['sup_postalcode'];
				$sup_town = $row_header['sup_town'];
				$imp_reference_nr = $row_header['imp_reference_nr'];
				$customer_name = $row_header['customer_name'];
				$customer_street = $row_header['customer_street'];
				$customer_postalcode = $row_header['customer_postalcode'];
				$customer_town = $row_header['customer_town'];
				$customer_contact = $row_header['customer_contact'];
				$cus_incoterms = $row_header['cus_incoterms'];
				$customer_reference_nr = $row_header['customer_reference_nr'];
				// $dem_pol_cost_after = $row_header['dem_pol_cost_after'];
				// $dem_pol_free = $row_header['dem_pol_free'];
				// $dem_pod_cost_after = $row_header['dem_pod_cost_after'];
				// $dem_pod_free = $row_header['dem_pod_free'];
				$proposal_date = $row_header['proposal_date'];
				$created_date = $row_header['created_date'];
				$created_by_name = $row_header['created_by_name'];
				$var_basecontract = $row_header['var_basecontract'];
				$var_misc_cost = $row_header['var_misc_cost'];
				$var_parity = $row_header['var_parity'];
				$var_payment_cond = $row_header['var_payment_cond'];
				$var_quality = $row_header['var_quality'];
				
				
				if($cus_incoterms_id == 263){
					$delivery_conditions = $sup_incoterms;
				} else {
					$delivery_conditions = $sup_incoterms .' '.$sup_pod_name;
				}
				
				$sql_detail = "SELECT order_nr || '.' || order_ship_nr AS imp_ref,
					s.fa_reference_nr as fa_ref,
					s.customer_reference_nr|| '.' ||s.customer_ref_ship_nr as cus_ref,
							 to_char (s.month_etd, 'Monyy')                   AS month_etd,
							 week_etd,
							 to_char (s.month_eta, 'Monyy')                   AS month_eta,
							 s.week_eta,
							 s.nr_containers                                  AS no_con,
							 to_char (s.weight_shipment, '999G999D9')           AS weight,
							 to_char (s.price_sup, '999G999')                 AS priceunit,
							 to_char (s.weight_shipment * price_sup, '999G999') AS total,
							 s.pol_country                                   AS origin,
							 s.incoterms, s.port_name
						FROM v_order_schedule s
					   WHERE ord_order_id = $ord_order_id
					   $details_cond
					ORDER BY order_ship_nr::integer
				";
				
				$tb_content='';
				$rs_detail = pg_query($conn, $sql_detail);
				while($row_detail = pg_fetch_assoc($rs_detail)) {
					$tb_content .='<tr>
						<td>'.$row_detail['imp_ref'].'</td>
						<td>'.$row_detail['fa_ref'].'</td>
						<td>'.$row_detail['cus_ref'].'</td>
						<td>'.$row_detail['month_etd'].'/'.$row_detail['week_etd'].'</td>
						<td>'.$row_detail['no_con'].'</td>
						<td>'.$row_detail['weight'].'</td>
						<td>'.$row_detail['priceunit'].'</td>
						<td>'.$row_detail['total'].'</td>
						<td>'.strtoupper($row_detail['origin']).'</td>
					</tr>';
				}
				
				
				$sql_footer = "select ord_order_id, getregvalue(max(price_currency_id)) currency,
                to_char(sum(weight_shipment*price_sup),'999G999G999') as totalprice,
                to_char(sum(nr_containers),'999G999') as no_cont,
                to_char(sum(weight_shipment),'999G999') as weight,
                to_char(max(weight_container),'999G999') as weight_cont
                from v_order_schedule 
                where ord_order_id=$ord_order_id
				$footer_cond
                group by ord_order_id";
				
				$rs_footer = pg_query($conn, $sql_footer);
				$row_footer = pg_fetch_assoc($rs_footer);
	
	
				$currency = $row_footer['currency'];
				$totalprice = $row_footer['totalprice'];
				$no_cont = $row_footer['no_cont'];
				$weight = $row_footer['weight'];
				$weight_cont = $row_footer['weight_cont'];
				
				
				$content='<div class="row text-center" style="padding-top:20px; padding-bottom:20px;">
					<h1>'.$importer.'</h1>
					'.$importer_street.' '.$importer_postalcode.', '.$importer_town.' 
					<hr/>
				</div>
			
				<div class="row">
					<div class="col-md-12">
						'.$sup_name.'<br/>
						'.$sup_street.'<br/>
						'.$sup_postalcode.' '.$sup_town.'<br/>
					</div>
				</div>
				
				
				<div class="row" font-size:11px;">
					<div class="col-md-12 no-padding" style="margin-top:30px;">
						<div class="col-md-2"><h3>Contract-No.</h3></div>
						<div class="col-md-10"><h3>'.$order_number.'</h3></div>
					</div>
				
					<div class="col-md-12 no-padding" style="margin-top:30px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Date</label></div>
						<div class="col-md-10">'.gmdate("Y/m/d").'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Client</label></div>
						<div class="col-md-10">'.$customer_name.', '.$customer_postalcode.' '.$customer_town.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Product</label></div>
						<div class="col-md-10">'.$product_name.', '.$product_code.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Currency</label></div>
						<div class="col-md-10">'.$currency.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Packaging</label></div>
						<div class="col-md-10">'.$package_name.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Total All Shipments</label></div>
						<div class="col-md-10">'.$totalprice.' '.$currency.'</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"><label class="contract_label" style="font-size:12px;">Delivery conditions</label></div>
						<div class="col-md-10">'.$delivery_conditions.' '.$port_name.'</div>
					</div>
					
					<div class="col-md-12" style="padding:5px 0;">
						<div class="col-md-2"><label class="contract_label"></label></div>
						<div class="col-md-10">
							<table class="table table-striped table-bordered" style="font-size:11px;">
								<thead>
									<tr>
										<th>Our-PO-No.</th>
										<th>FA PO-No.</th>
										<th>Cust-PO-No.</th>
										<th>Month ETD</th>
										<th>No Cont.</th>
										<th>TtlWeight</th>
										<th>Price/MT</th>
										<th>Shipment Price</th>
										<th>Origin</th>
									</tr>
								</thead>
								
								<tbody>
									'.$tb_content.'
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="col-md-12 no-padding" style="margin-top:10px;">
						<div class="col-md-2"></div>
						<div class="col-md-5">
							<label class="contract_label">Supplier</label><br/>
							'.$sup_name.'
						</div>
						<div class="col-md-5">
							<label class="contract_label">Importeur</label><br/>
							'.$importer.'
						</div>
					</div>
					
				</div>';
		
				$dom = $content.'##0##0';
			}

		break;
		
		
		
		case "save_puchase_order":
		
			$ord_order_id = $_GET['ord_order_id'];
			$doc_filename = $_GET['doc_filename'];
			$doc_type_id = $_GET['doc_type_id'];
			
			$email_sender_company_id = $_SESSION['id_company'];
			$created_by = $_SESSION['id_user'];
			$created_date = gmdate("Y/m/d H:i");
			
			$sql = "insert into ord_document (ord_order_id, doc_filename, doc_type_id, doc_date, created_by, created_date, id_owner, 
			user_id, email_sender_id, email_sender_company_id) 
			values ($ord_order_id, '$doc_filename', $doc_type_id, '$created_date', $created_by, '$created_date', $created_by, 
			$created_by, $created_by, $email_sender_company_id) RETURNING id_document";
			$result = pg_query($conn, $sql);
			
			if($result){
				
				$arr = pg_fetch_assoc($result);
				
				$id_document = $arr['id_document'];
				$user_id = $_SESSION['id_user'];
			
				$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
				VALUES ($id_document, $user_id, 1)";
				pg_query($conn, $sql2);

				
				$id_ord_schedule = $_GET['id_ord_schedule'];
				if($id_ord_schedule!=""){
					if($doc_type_id==4){
						$cond="flag_po_fa=1";
					} else {
						$cond="flag_po_sup=1";
					}
					
					$sql_flag = "update public.ord_ocean_schedule set $cond where id_ord_schedule=$id_ord_schedule";
					$result_flag = pg_query($conn, $sql_flag);
				}
				
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		
		case "send_puchase_order":
		
			$info = $_GET['info'];
			$doc_filename = $_GET['doc_filename'];
			$ord_order_id = $_GET["ord_order_id"];
			$cus_incoterms_id = $_GET["cus_incoterms_id"];
			
			if(($cus_incoterms_id == 263) OR ($cus_incoterms_id == 264)){
				
				$title='Lieferplanung';
				
				$sql_header = "select v_order_schedule.id_ord_schedule,
					v_order_msg.imp_mail,
					v_order_msg.imp_admin_mail,
					v_order_msg.sup_mail,
					v_order_msg.sup_admin_mail,
					v_order_msg.sm_mail,
					v_order_msg.fa_admin_mail,
					v_order_msg.fa_mail,
					get_contact_name (v_order.ord_imp_person_id) ord_imp_person_name
				
					FROM v_order_schedule,
					v_order_msg,
					v_order,
					product
					WHERE v_order_msg.id_ord_order=v_order_schedule.ord_order_id
					AND v_order.id_ord_order=v_order_schedule.ord_order_id
					AND v_order_schedule.id_ord_schedule=(select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id)
					AND product.id_product=v_order_Schedule.product_id
				";
				
			} else {
				
				$title='Purchase Order';
				
				$sql_header = "SELECT v_order_schedule.order_nr,
					v_order_msg.imp_mail,
					v_order_msg.imp_admin_mail,
					v_order_msg.sup_mail,
					v_order_msg.sup_admin_mail,
					v_order_msg.sm_mail,
					v_order_msg.fa_admin_mail,
					v_order_msg.fa_mail,
					get_contact_name (v_order.ord_imp_person_id) ord_imp_person_name
					
					FROM v_order_schedule, v_order_msg, v_order, v_ocean_freight, product
					where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
					and v_order.id_ord_order=v_order_schedule.ord_order_id
					and v_order_schedule.id_ord_schedule = ( select max(id_ord_schedule) from v_order_schedule where ord_order_id=$ord_order_id )
					and v_ocean_freight.sequence_nr=1 and v_ocean_freight.id_ord_schedule=v_order_schedule.id_ord_schedule
					and product.id_product=v_order_Schedule.product_id
				";
			}
	

			$rs_header = pg_query($conn, $sql_header);
			$row_header = pg_fetch_assoc($rs_header);	
	
	
			$imp_mail = $row_header['imp_mail'];
			$imp_admin_mail = $row_header['imp_admin_mail'];
			$sup_mail = $row_header['sup_mail'];
			$sup_admin_mail = $row_header['sup_admin_mail'];
			$sm_mail = $row_header['sm_mail'];
			$fa_admin_mail = $row_header['fa_admin_mail'];
			$fa_mail = $row_header['fa_mail'];
			$ord_imp_person_name = $row_header['ord_imp_person_name'];
		
			
			if($info == 'fa'){
				$name        = $_SESSION['name'];
				// $email       = "$fa_mail, $fa_admin_mail";
				$cc    		 = "$imp_mail, $imp_admin_mail"; 
				
			} else
			if($info == 'supp'){
				$name        = $ord_imp_person_name;
				// $email       = "$sup_mail, $sup_admin_mail";
				$cc    		 = "$imp_mail, $imp_admin_mail, $sm_mail";
			}
			
			$email       = "croth53@gmail.com";
			
			// Settings
			$to          = "$email";
			$from        = "$name <noreply@icollect.live>";
			$subject     = "$title";
			$mainMessage = "Hi,\n\nFind attached $title document.";
			$fileatt     = "img/documents/$doc_filename";
			$fileatttype = "application/pdf";
			$fileattname = "$doc_filename";
			$headers = "From: $from";
			
			
			// File
			$file = fopen($fileatt, 'rb');
			$data = fread($file, filesize($fileatt));
			fclose($file);
			
			
			// This attaches the file
			$semi_rand     = md5(time());
			$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
			$headers      .= "\nMIME-Version: 1.0\n" .
			"Content-Type: multipart/mixed;\n" .
			" boundary=\"{$mime_boundary}\"";
			// $headers      .= "CC: $cc\r\n";
			$message = "This is a multi-part message in MIME format.\n\n" .
			"-{$mime_boundary}\n" .
			"Content-Type: text/plain; charset=\"iso-8859-1\n" .
			"Content-Transfer-Encoding: 7bit\n\n" .
			$mainMessage  . "\n\n";
			$data = chunk_split(base64_encode($data));
			$message .= "--{$mime_boundary}\n" .
			"Content-Type: {$fileatttype};\n" .
			" name=\"{$fileattname}\"\n" .
			"Content-Disposition: attachment;\n" .
			" filename=\"{$fileattname}\"\n" .
			"Content-Transfer-Encoding: base64\n\n" .
			$data . "\n\n" .
			"-{$mime_boundary}-\n";
			
			
			// Send the email
			if(mail($to, $subject, $message, $headers)) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "add_to_archive":
		
			$ord_schedule_id = $_GET['ord_schedule_id']; 
			$sql="update ord_ocean_schedule set pipeline_sched_id=300 where id_ord_schedule=$ord_schedule_id";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "accounting":
		
			$ord_schedule_id = $_GET['ord_schedule_id']; 
			$sql="update ord_ocean_schedule set pipeline_sched_id=299 where id_ord_schedule=$ord_schedule_id";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "save_increased_sp":
		
			$id_ord_schedule = $_GET['id_ord_schedule']; 
			$old_margin_mt = $_GET['old_margin_mt']; 
			$new_sales_price = $_GET['new_sales_price']; 
			
			$id_proposal_calc = $_GET['id_proposal_calc']; 
			$ship_sales_value_tone = $_GET['ship_sales_value_tone']; 
			
			$margin_per_ton = ($new_sales_price - $ship_sales_value_tone) + $old_margin_mt;
			
			$sql="update ord_proposal_calc set margin_per_ton=$margin_per_ton where id_proposal_calc=$id_proposal_calc";
			$result = pg_query($conn, $sql);

			if ($result) {
				$order_ship_nr = "";
				$ord_order_id = "";
				$pipeline_id = "";
				
				if($id_ord_schedule!=""){
					$sql_grid = " SELECT order_ship_nr, ord_order_id, pipeline_id FROM public.v_logistics_schedule WHERE id_ord_schedule=$id_ord_schedule";
					$result_grid = pg_query($conn, $sql_grid);
					$row = pg_fetch_assoc($result_grid);	
	
					$order_ship_nr = $row['order_ship_nr'];
					$ord_order_id = $row['ord_order_id'];
					$pipeline_id = $row['pipeline_id'];
					
					$sql_calc = 'SELECT * FROM public."Calculate"('.$id_ord_schedule.'); ';
					pg_query($conn, $sql_calc);
				}
				$dom='1##'.$order_ship_nr.'##'.$ord_order_id.'##'.$pipeline_id;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "save_reduiced_sp":
		
			$id_ord_schedule = $_GET['id_ord_schedule']; 
			$old_margin_mt = $_GET['old_margin_mt']; 
			$new_sales_price = $_GET['new_sales_price']; 
			
			$id_proposal_calc = $_GET['id_proposal_calc']; 
			$ship_sales_value_tone = $_GET['ship_sales_value_tone'];  
			
			$margin_per_ton = $old_margin_mt + ($new_sales_price - $ship_sales_value_tone);
			
			$sql="update ord_proposal_calc set margin_per_ton=$margin_per_ton where id_proposal_calc=$id_proposal_calc";
			$result = pg_query($conn, $sql);

			if ($result) {
				$order_ship_nr = "";
				$ord_order_id = "";
				$pipeline_id = "";
				
				if($id_ord_schedule!=""){
					$sql_grid = " SELECT order_ship_nr, ord_order_id, pipeline_id FROM public.v_logistics_schedule WHERE id_ord_schedule=$id_ord_schedule";
					$result_grid = pg_query($conn, $sql_grid);
					$row = pg_fetch_assoc($result_grid);	
	
					$order_ship_nr = $row['order_ship_nr'];
					$ord_order_id = $row['ord_order_id'];
					$pipeline_id = $row['pipeline_id'];
					
					$sql_calc = 'SELECT * FROM public."Calculate"('.$id_ord_schedule.'); ';
					pg_query($conn, $sql_calc);
				}
				$dom='1##'.$order_ship_nr.'##'.$ord_order_id.'##'.$pipeline_id;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "document_status":
		
			$active = $_GET['active'];
			$doc_desc = $_GET['doc_desc'];
			$id_document = $_GET['id_document'];
			
			$modified_by = $_SESSION["id_user"];
			$modified_date = gmdate("Y/m/d H:i");
			
			$sql = "update ord_document set active=$active, document_desc='$doc_desc', 
				modified_by=$modified_by, modified_date='$modified_date'
			where id_document=$id_document";
		
			$result = pg_query($conn, $sql);
			if ($result) { 
				$dom=1;		
			} else {
				$dom=0;	
			}
		
		break;
		
		
		case "role_list":
		
			$id_user = $_GET['id_user'];
			$id_role = 0;
			
			$sql_r = "select id_role from user_role where id_user=$id_user";
			$result_r = pg_query($conn, $sql_r);
			if($result_r){
				$row_r = pg_fetch_assoc($result_r);
				$id_role = $row_r['id_role'];				
			}
			
			$sql = "select * from roles where id_supchain_type=113";
			$result = pg_query($conn, $sql);
			
			$list="<option>-- Role --</option>";
			while($row = pg_fetch_assoc($result)){
				if($id_role!=0){ $sel="selected='selected'"; } else { $sel=""; }
				$list .='<option value="'.$row['id_role'].'" '.$sel.'>'.$row['name'].'</option>';
			}
			
			if($id_role!=0){ $conf="edit"; } else { $conf="add"; }
			
			$dom=$list.'##'.$conf;
		
		break;
		
		
		case "update_user_role":
		
			$id_user = $_GET['id_user'];
			$id_role = $_GET['id_role'];
			$conf = $_GET['conf'];
			
			if($conf=="edit"){
				$sql_update = "UPDATE public.user_role SET id_role=$id_role WHERE id_user = $id_user";
				$rs_update = pg_query($conn, $sql_update);
	 			if($rs_update){
					$dom=1;
				} else {
					$dom=1;
				}
				
			} else
			if($conf=="add"){
				$sql_insert = "insert into public.user_role (id_role, id_user) values ($id_role, $id_user)";
				$rs_insert = pg_query($conn, $sql_insert);
	 			if($rs_insert){
					$dom=1;
				} else {
					$dom=1;
				}
			}
			
		break;
		
		
		case "order_reference_nr2":
		
			$id_user = $_SESSION['id_user'];
			$id_contact = $_SESSION['id_contact'];
			$id_company = $_SESSION['id_company'];
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			
			$pipeline_sched_id = $_GET['pipeline_sched_id'];
			
			if($pipeline_sched_id!=0){
				$condition = " AND pipeline_sched_id = $pipeline_sched_id";
				$condition2 = "pipeline_sched_id = $pipeline_sched_id AND";
			}
			
			if($id_user_supchain_type == 312){
				$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 312) as shipment_number_1, 
					public.get_reference_nr_2(id_ord_schedule, 312) as shipment_number_2, cus_incoterms_id,
				Sup_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup
				from v_logistics_shipment  
				where ord_sm_person_id=$id_contact $condition And status_id<=230
				order by created_date DESC, ord_order_id, order_ship_nr";
				
			} else {
				if($id_supchain_type == 110){
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 110) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 110) as shipment_number_2, cus_incoterms_id,
					Customer_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
					from v_logistics_shipment  
					where ord_cus_contact_id=$id_company $condition And status_id<=230 
					order by created_date DESC, ord_order_id, order_ship_nr";
					
				} else
				if($id_supchain_type == 112){
					if($id_company == 717){
						$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 113) as shipment_number_1, 
							public.get_reference_nr_2(id_ord_schedule, 113) as shipment_number_2, cus_incoterms_id,
						Order_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
						from v_logistics_shipment  
						where ord_imp_contact_id=$id_company $condition order by created_date DESC";
						
					} else {
						$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 112) as shipment_number_1, 
							public.get_reference_nr_2(id_ord_schedule, 112) as shipment_number_2, cus_incoterms_id,
						Order_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
						 from v_logistics_shipment 
						where ord_imp_contact_id=$id_company $condition
						Or ( $condition2 ord_imp_contact_id in ( select id_contact from (
						select id_contact from ( 
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
						) q1
						where id_type=10 and id_supchain_type=112
						) q2)) $cond And status_id<=230 order by created_date DESC, ord_order_id, order_ship_nr ";
					}
					
				} else
				if($id_supchain_type == 113){
					$sql_freight = "select l.*, public.get_reference_nr_1(id_ord_schedule, 113) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 113) as shipment_number_2, 
					ord.Sup_reference_nr as order_number, l.ref_code_cus, l.ref_code_fa, l.ref_code_imp, l.ref_code_sup
						from v_logistics l, v_order ord, ord_order o
					where l.id_ord_schedule in ( select distinct id_ord_schedule from ord_ocean_schedule where supplier_contact_id=$id_company ) 
					and ord.id_ord_order=l.ord_order_id
					and o.id_ord_order=l.ord_order_id $condition
					order by week_etd";
					
				} else
				if($id_supchain_type == 289){
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 289) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 289) as shipment_number_2, cus_incoterms_id,
					Fa_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup 
					from v_logistics_shipment  
					where ord_order_id in ( select distinct ord_order_id from ord_con_booking where fa_contact_id=$id_company ) 
					And status_id<=230 $condition
					order by created_date DESC, ord_order_id, order_ship_nr";
					
				} else
				if($id_supchain_type == 288){
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 288) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 288) as shipment_number_2, cus_incoterms_id,
					Sup_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup
					from v_logistics_shipment 
					where id_ord_schedule in ( select distinct ord_schedule_id from v_con_booking where forwarder_company_id=$id_company ) 
					And status_id<=230 $condition
					order by created_date DESC, ord_order_id, order_ship_nr";
				
				} else
				if($id_supchain_type == 327){
					$sql_freight = "select *, public.get_reference_nr_1(id_ord_schedule, 327) as shipment_number_1, 
						public.get_reference_nr_2(id_ord_schedule, 327) as shipment_number_2, cus_incoterms_id,
					Sup_reference_nr as order_number, ref_code_cus, ref_code_fa, ref_code_imp, ref_code_sup
					from v_logistics_shipment 
					where id_ord_schedule in ( select distinct ord_schedule_id from v_con_booking where qm_contact_id=$id_company and pipeline_sched_id in ( 296, 298 ) ) 
					and pol_id in ( select distinct id_townport from ord_towns_port where qm_org_contact_id=$id_company) 
					And status_id<=230 $condition
					order by created_date DESC, ord_order_id, order_ship_nr";
				
				} else {}
			}

			$rs_freight = pg_query($conn, $sql_freight);
	
			$list_freight = '';
	
			while ($row_freight = pg_fetch_assoc($rs_freight)) {
				
				$id_ord_schedule = preg_replace('/\s+/', '', $row_freight['id_ord_schedule']);
				$cus_incoterms_id = preg_replace('/\s+/', '', $row_freight['cus_incoterms_id']);
				
				$id_con_booking = preg_replace('/\s+/', '', $row_freight['id_con_booking']);
			
				$pipeline_sched_id = preg_replace('/\s+/', '', $row_freight['pipeline_sched_id']);
				$ord_order_id = preg_replace('/\s+/', '', $row_freight['ord_order_id']);
				
				
				if($row_freight['pipeline_sched_id']==293){
					$label='label-info'; $style='';
				} else
				if($row_freight['pipeline_sched_id']==294){
					$label='label-success'; $style='';
				} else
				if($row_freight['pipeline_sched_id']==295){
					$label='label-warning'; $style='';
				} else
				if($row_freight['pipeline_sched_id']==296){
					$label='label-danger'; $style='';
				} else
				if($row_freight['pipeline_sched_id']==299){
					$label='label-primary'; $style='';
				} else
				if($row_freight['pipeline_sched_id']==298){
					$label='label-muted'; $style='';
				} else
				if($row_freight['pipeline_sched_id']==357){
					$label=''; $style='background:#000; color:#FFF';
				} else { $label=''; $style='';}
				
				$ref_number = $row_freight['shipment_number_1'];
				
				$list_freight .= '<li><a href="javascript:new_crm_loading(\''. $id_ord_schedule .'\',\''. $cus_incoterms_id .'\',\''. $id_con_booking .'\',\''. $ord_order_id .'\',\''. $pipeline_sched_id .'\',\''. $ref_number .'\',\'\');" class="reference_nr2">
					'. $row_freight['shipment_number_1'] .' 
					<small class="pull-right" style="color:#aaa; font-size:9px;">'. $row_freight['created_date'] .'</small> <br/>
					<span class="label '.$label.' pull-right" style="font-weight:normal; '.$style.'">'. $row_freight['pipeline_sched_name'] .'</span>
					<span style="color:#aaa; font-size:10px;">'. $row_freight['shipment_number_2'] .'</span><br/>
					<small style="color:#aaa; font-size:9px;">'.$row_freight['ref_code_cus'].'</small><br/>
					<small style="color:#aaa; font-size:9px;">'.$row_freight['ref_code_fa'].'</small><br/>
					<small style="color:#aaa; font-size:9px;">'.$row_freight['ref_code_imp'].'</small><br/>
					<small style="color:#aaa; font-size:9px;">'.$row_freight['ref_code_sup'].'</small>
				</a></li>';
			}

			$dom=$list_freight;
		
		break;
		
		
		case "new_crm_loading":
		
			$sched_update = $_GET['sched_update'];
			$sched_create = $_GET['sched_create'];
			
			$id_ord_schedule = $_GET['id_ord_schedule'];
			$cus_incoterms_id = $_GET['cus_incoterms_id'];
			$id_con_booking = $_GET['id_con_booking'];
			$id_ord_order = $_GET['ord_order_id'];
			$pipeline_sched_id = $_GET['pipeline_sched_id'];
			$ref_number = $_GET['ref_number'];
			$grid_id = $_GET['grid_id'];
			
			$id_supchain_type = $_SESSION['id_supchain_type'];
			
			$sql = "SELECT * FROM public.v_order WHERE id_ord_order = $id_ord_order";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			// Tab status
			$exp_status = $arr['exp_status'];
			$proposal_status = $arr['proposal_status'];
			$calculate_status = $arr['calculate_status'];
			$order_status = $arr['order_status'];
			$freight_status = $arr['freight_status'];
			
			
			// Status
			if(($arr['status_id']==246) OR
				($arr['status_id']==247) OR
				($arr['status_id']==248)){
				$status_bg_color ='bg-success';
			} else {
				$status_bg_color ='bg-danger';
			}
			
			// Pipeline
			$pipeline_id = $arr['pipeline_id'];
			if($pipeline_id==296){
				$lbl='danger'; $style='';
			} else
			if($pipeline_id==299){
				$lbl='primary'; $style='';
			} else
			if($pipeline_id==298){
				$lbl='default'; $style='';
			} else
			if($pipeline_id==357){
				$lbl=''; $style='background:#000; color:#FFF';
			} else { $label=''; $style='';}
			

			// Importer contact
			$list_imp_ct='';
			$sql_imp_ct = "SELECT id_contact, name FROM public.v_security_new WHERE id_supchain_type=112";
			$result_imp_ct = pg_query($conn, $sql_imp_ct);
			while($arr_imp_ct = pg_fetch_assoc($result_imp_ct)){
				if($arr['ord_imp_person_id']==$arr_imp_ct['id_contact']){ $sel_ct='selected'; }else{ $sel_ct=''; }
				$list_imp_ct.='<option value="'.$id_ord_order.'#'.$arr_imp_ct['id_contact'].'"'. $sel_ct .'>'.$arr_imp_ct['name'].'</option>';
			}
			
			// SM person
			$list_sm_manager='<option value="">-- '.$lang['CONTRACT_SEL_MANAGER'].' --</option>';
			$sql_sm_manager = "SELECT id_contact, name FROM public.contact WHERE id_supchain_type=312";
			$result_sm_manager = pg_query($conn, $sql_sm_manager);
			while($arr_sm_manager = pg_fetch_assoc($result_sm_manager)){
				if($arr['sm_person_id']==$arr_sm_manager['id_contact']){ $sel_sm='selected'; }else{ $sel_sm=''; }
				$list_sm_manager.='<option value="'.$id_ord_order.'#'.$arr_sm_manager['id_contact'].'"'. $sel_sm .'>'.$arr_sm_manager['name'].'</option>';
			}
			
			
			if($arr['modify_date']!="" && $arr['modified_by_name']!=""){
				$contract_modified = '<div class="form-group">
					<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label> '. $arr['modified_by_name'] .' <br/>
					<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].': </label> '. $arr['modify_date'] .'
				</div>';
			} else {
				$contract_modified = "";
			} 
			
			// FA Company
			$sql_freight_agent = "select * from contact where id_supchain_type=289";
			$rs_freight_agent = pg_query($conn, $sql_freight_agent);
			$list_freight_agent = '<option value="">-- '.$lang['CONTRACT_SEL_FREIGHT_AGENT'].' --</option>';
			while ($row_freight_agent = pg_fetch_assoc($rs_freight_agent)) {
				if($row_freight_agent['id_contact'] == $arr['ord_fa_contact_id']){ $sel_freight_a="selected='selected'"; } else { $sel_freight_a=""; }
				$list_freight_agent .= '<option value="'. $row_freight_agent['id_contact'] .'"'.$sel_freight_a.'>'. $row_freight_agent['name'] .'</option>';
			}
			
			
			$edPiView="";
			if($id_supchain_type == 112){ 
				$edPiView='<i class="fa fa-edit hide" id="editSumPipeline2"  style="cursor:pointer;" onclick="editSumPipeline2('.$id_ord_order.');"></i>'; 
			}
			
			
			/****
			*
			* SUMMARY - CUSTOMER REQUEST
			*
			*****/
			
			$user_summary = '<div class="col-md-6">
				<label class="ord_sum_label">'.$lang['CONTRACT_CUST_NAME'].' </label> <br/>'.$arr['customer_name'].'<br/>					
				<label class="ord_sum_label">'.$lang['CONTRACT_CUST_CONRTACT'].' </label> <br/>'.$arr['customer_contact'].'<br/>					
				<label class="ord_sum_label">'.$lang['CONTRACT_CUST_REF_NUMB'].' </label> <br/>
					<span id="cusRefNumbShow2">'.$arr['customer_reference_nr'].'</span>
					<div class="form-group hide" id="cusRefNumbInput2">
						<input id="edit_customer_reference_nr2" type="text" value="'.$arr['customer_reference_nr'].'" class="form-control" />
					</div>
					<span id="cusRefNumbManagBtn2" class="hide">
						<a href="#" onclick="editCusRefNumb2('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span>
					<br/>
					<label class="ord_sum_label">'.$lang['CONTRACT_N_SHIPMENT'].' </label> <br/>'.$arr['nr_shipments'].'<br/>
					<label class="ord_sum_label">'.$lang['CONTRACT_CUST_NOTE'].' </label> <br/>
					<span id="cusNotesShow2">'.$arr['notes_customer'].'</span>
					<div class="form-group hide" id="cusNotesInput2">
						<textarea id="edit_notes_customer2" style="height:80px;" class="form-control">'.$arr['notes_customer'].'</textarea>
					</div>
					<span id="cusNotesManagBtn2" class="hide">
						<a href="#" onclick="editCusNotes2('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span>
				</div>
				
				<div class="col-md-6">
					<label class="ord_sum_label">'.$lang['CONTRACT_PRODUCT'].' </label> <br/>'.$arr['product'].'<br/>							
					<label class="ord_sum_label">'.$lang['CONTRACT_PKG_TYPE'].' </label> <br/>'.$arr['package_type'].'<br/>							
					<label class="ord_sum_label">'.$lang['CONTRACT_INCOTERMS'].' </label> <br/>'.$arr['incoterms'].' '.$arr['port_discharge'].'<br/>							
					<label class="ord_sum_label">'.$lang['CONTRACT_TT_WEIGHT'].' </label> <br/>'.$arr['total_weight'].'<br/>	
					<label class="ord_sum_label">'.$lang['CONTRACT_RQST_DATE'].' </label> <br/>'.$arr['request_date'].' / '.$arr['created_by_name'].'						
				</div>
			
				<div class="col-md-12 hide" id="sumCusRequestToggler2">
					<button class="btn btn-success pull-right" onclick="showEditCusNotes2();" type="button"><i class="fa fa-edit"></i></button>
				</div>
			';
			
			/****
			*
			* SUMMARY - NOTES
			*
			*****/
			
			$importer_summary = '<div class="col-md-6">
					<label class="ord_sum_label">'.$lang['CONTRACT_IMP_COMP'].' </label> <br/>'.$arr['importer'].'<br/>		
					<label class="ord_sum_label">'.$lang['CONTRACT_NOTES'].' </label> <br/>
					<span id="intNotesShow2">'.$arr['notes_internal'].'</span>
					<div class="form-group hide" id="intNotesInput2">
						<textarea id="edit_notes_internal2" style="height:80px;" class="form-control">'.$arr['notes_internal'].'</textarea>
					</div>
					<span id="intNotesManagBtn2" class="hide">
						<a href="#" onclick="editIntNotes2('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span>
				</div>
				
				<div class="col-md-6">
					<span class="toggle_edit"><label for="ord_imp_person_id" class="ord_sum_label">'.$lang['CONTRACT_IMP_CONTACT'].' </label> <br/> 
					<select class="normal_select" onchange="editImpPerson2();" style="border:none;" name="ord_imp_person_id" id="ord_imp_person_id2" disabled>
						'.$list_imp_ct.'
					</select><i class="fa fa-edit"></i></span><br/>
					
					<span class="toggle_edit"><label for="sm_person_id" class="ord_sum_label">'.$lang['CONTRACT_SORCING_MNGR'].' </label> <br/>
					<select class="normal_select" onchange="editOrderSmManager2();" style="border:none;" name="sm_person_id" id="sm_person_id2" disabled>
						'.$list_sm_manager.'
					</select><i class="fa fa-edit"></i></span><br/>
					
					<label class="ord_sum_label">'.$lang['CONTRACT_CRT_CTRCT_NUMB'].' </label> <br/>
					<span id="orderNrOldShow2">'.$arr['order_nr_old'].'</span>
					<div class="form-group hide" id="orderNrOldInput2">
						<input id="edit_order_nr_old2" class="form-control" value="'.$arr['order_nr_old'].'" />
					</div>
					<span id="orderNrOldManagBtn2" class="hide">
						<a href="#" onclick="editorderNrOld2('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span>
				</div>
				
				<div class="col-md-12 no-padding">
					<div class="col-md-3 pull-left" style="margin-top:20px;">
						<label for="status_id" class="ord_sum_label">'.$lang['CONTRACT_STATUS'].' 
							<i class="fa fa-edit hide" id="editSumStatus2" style="cursor:pointer;" onclick="editSumStatus2('.$id_ord_order.');"></i>
						</label>
						<div class="col-md-12 p-xs b-r-sm '.$status_bg_color.'">
							<small id="sumStatusName2">'.$arr['status_name'].'</small>
						</div>
					</div>
					
					<div class="col-md-3 pull-right" style="margin-top:20px;">
						<label for="status_id" class="ord_sum_label">'.$lang['CONTRACT_PIPELINE'].' '.$edPiView.'</label>
						<div class="col-md-12 p-xs b-r-sm bg-'.$lbl.'" style="font-weight:normal; '.$style.'">
							<small id="sumPipelineName2">'.$arr['pipeline_name'].'</small>
						</div>
					</div>
				</div>
				
				<div class="col-md-12 no-padding hide" id="sumBtnsToggler2">
					<button class="btn btn-success pull-right" onclick="showSumEditBtns2();" style="margin-top:10px; margin-right:20px;" type="button">
					<i class="fa fa-edit"></i></button>
				</div>
			';
			
			/****
			*
			* SUMMARY - CONTRACT
			*
			*****/
			
			$contract_summary = '<div class="col-md-6">
					<label class="ord_sum_label">'.$lang['CONTRACT_CLT_NAME'].' </label> <br/> '.$arr['customer_name'].' <br/>	
					
					<label class="ord_sum_label">'.$lang['CONTRACT_CLT_CONTRACT_NUMB'].' </label> <br/>
					<span id="orderCusRefNrLabel2">'.$arr['customer_reference_nr'].'</span>	
					<div class="form-group hide" id="orderCusRefNrInput2">
						<input id="customer_reference_nr_CT2" class="form-control" value="'.$arr['customer_reference_nr'].'" />
					</div>
					<span id="cusRefNumbCTManagBtn2" class="hide">
						<a href="#" onclick="editCusRefNumbCT2('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span><br/>	
					
					<label class="ord_sum_label">'.$lang['CONTRACT_PRODUCT'].' </label> <br/> '.$arr['product'].' <br/>	
					<label class="ord_sum_label">'.$lang['CONTRACT_N_SHIPMENT'].' </label> <br/> '.$arr['nr_shipments'].' <br/>	
					
					<label class="ord_sum_label">'.$lang['CONTRACT_N_CONTAINER_C'].' </label> <br/> '.$arr['product_quantity'].' <br/>	  
					<label class="ord_sum_label">'.$lang['CONTRACT_TT_WEIGHT_C'].'</label> <br/> '.$arr['total_weight'].' <br/>	
				</div>
			
				<div class="col-md-6">
					<label class="ord_sum_label">'.$lang['CONTRACT_IMP_NAME'].' </label> <br/> '.$arr['importer_person'].' <br/>	
					<label class="ord_sum_label">'.$lang['CONTRACT_IMP_CONTRACT_NUMB'].' </label> <br/> '.$arr['order_nr'].' <br/>	
					
					<label class="ord_sum_label">'.$lang['CONTRACT_EXP_CONTRACT_NUMB'].' </label> <br/> 
					<span id="orderSupRefNrLabel2">	'.$arr['sup_reference_nr'].' </span>	
					<div class="form-group hide" id="orderSupRefNrInput2">
						<input id="sup_reference_nr_CT2" class="form-control" value="'.$arr['sup_reference_nr'].'" />
					</div>
					<span id="orderSupRefNrManagBtn2" class="hide">
						<a href="#" onclick="editorderSupRefNr2('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span><br/>	
					
					<label class="ord_sum_label">'.$lang['CONTRACT_FREIGHT_AGT_COMP'].' </label> <br/> 
					<span id="orderFaCompLabel2"> '.$arr['fa_contact_name'].' </span>
					<div class="form-group hide" id="orderFaCompSelect2">
						<select id="ord_fa_contact_id2" class="form-control">
							'.$list_freight_agent.'
						</select> 
					</div>
					<span id="orderFaCompManagBtn2" class="hide">
						<a href="#" onclick="editorderFaComp2('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span><br/>
					
					<label class="ord_sum_label">'.$lang['CONTRACT_FREIGHT_AGT_CONTRACT_NUMB'].' </label> <br/>
					<span id="orderFaRefNrLabel2">'.$arr['fa_reference_nr'].'</span>	
					<div class="form-group hide" id="orderFaRefNrInput2">
						<input id="fa_reference_nr_CT2" class="form-control" value="'.$arr['fa_reference_nr'].'" />
					</div>
					<span id="orderFaRefNrManagBtn2" class="hide">
						<a href="#" onclick="editorderFaRefNr2('.$id_ord_order.');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i>
						</a>
					</span><br/>	
				</div>
				
				<div class="col-md-12" style="margin-top:20px;">
					<div class="pull-left" id="contract_modified">
						'.$contract_modified.'
					</div>
					<div id="contractTabEdit2" class="hide">
						<button class="btn btn-success pull-right" onclick="edit_contract2(\''.$id_ord_order.'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
					</div>
				</div>
			';
			
			
			/****
			*
			* REQUEST - SCHEDULE
			*
			*****/
			
			$sched_update = $_GET['sched_update'];
			
			$grid_list = "";
			$sql_grid = " SELECT * FROM public.v_logistics_schedule WHERE ord_order_id=$id_ord_order AND id_ord_schedule=$id_ord_schedule ORDER BY order_ship_nr ASC";
			$result_grid = pg_query($conn, $sql_grid);

			$x=1;
			while($row = pg_fetch_assoc($result_grid)){
				
				if($grid_id){ $edit_line = $grid_id; } 
				else { $edit_line=NULL; }
				
				$grid_list .= '<tr class="gradeX">
					<td>
						No '.$row['order_ship_nr'].'<br/>
						<small style="color:#aeaeae; font-size:9px;">
						'.$row['ref_shipcode_cus'].'<br/>
						'.$row['ref_shipcode_sup'].'<br/>
						'.$row['ref_shipcode_fa'].'</small>
					</td>
					<td>';
						if($edit_line == $row['id_ord_schedule']){
							$grid_list .= '<input id="qty2-'. $row['id_ord_schedule'] .'" style="width:120px;" name="edit_product_quantity" value="'. $row['nr_containers'] .'" type="number" min="0" class="form-control">
								<input id="wgt2-'. $row['id_ord_schedule'] .'" value="'. $row['weight_container'] .'" type="hidden">';
						} else {
							$grid_list .= $row['nr_containers'];
						}
					$grid_list .= '</td>
					<td class="center">'. $row['weight_container'] .'</td>
					<td class="center">'. $row['weight_shipment'] .'</td>
					<td>'. $row['month_etd'] .'<span class="pull-right">'. $row['week_etd'] .'</span></td>
					<td>';
						if($edit_line == $row['id_ord_schedule']){
							$grid_list .= '<div class="input-group date" style="width:160px;">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" class="form-control edit_delivery_date" id="date2-'. $row['id_ord_schedule'] .'" name="edit_delivery_date" value="'. $row['month_eta'] .'">
							</div>';
						} else {
							$grid_list .= $row['month_eta'];
							$grid_list .= '<span class="pull-right">'. $row['week_eta'] .'</span>';
						}	
					$grid_list .= '</td>';
					
					if($id_user_supchain_type==312){
						$order_number = $arr['order_nr'];

					} else {
						if($id_supchain_type==110){
							$order_number = $arr['customer_reference_nr'];
	
						} else
						if($id_supchain_type==112){
							$order_number = $arr['order_nr'];
							
						} else
						if($id_supchain_type==113){
							$order_number = $arr['sup_reference_nr'];
						
						} else
						if($id_supchain_type==289){
							$order_number = $arr['fa_reference_nr'];
							
						} else {}
					}
			
					$date = explode(" ", $row['month_eta']); 
					$year = explode("-", $date[0]); 
					
					if($edit_line == $row['id_ord_schedule']){
						$grid_list .='<td>
						<a href="#" onclick="saveEditScheduleLine2('.$id_ord_order.','.$row['id_ord_schedule'].');" class="btn btn-white btn-sm">
						<i style="color:green;" class="fa fa-check"></i></a>
						
						<a href="#" onclick="showEditScheduleLine2('.$row['id_ord_schedule'].',\''.$cus_incoterms_id.'\',\''. $id_con_booking .'\',\''. $id_ord_order .'\',\''. $row['pipeline_id'] .'\',\''. $ref_number .'\');" class="btn btn-white btn-sm">
						<i style="color:red;" class="fa fa-times"></i></a>';
			
						if($arr['pipeline_id']<296){
							$grid_list .='<a href="#" class="pull-right btn btn-white btn-sm" onclick="deleteShipment('.$row['id_ord_schedule'].','.$id_ord_order.');">
								<i class="fa fa-trash" aria-hidden="true"></i>
							</a>';
						}
				
					} else {
						if($sched_update==1){
							
							$id_user = $row['sched_modified_by'];
							if($id_user!=""){
								$sql_uid = " SELECT contact_code FROM public.v_security_new WHERE id_user=$id_user ";
								$result_uid = pg_query($conn, $sql_uid);
								$row_uid = pg_fetch_assoc($result_uid);
								$contact_code = $row_uid['contact_code'];
							} else {
								$contact_code ="";
							}
							
							if($arr['pipeline_id']<296){ $editGrid=""; } else { $editGrid="disabled"; }
						
							$grid_list .='<td><button onclick="showEditScheduleLine2(\''. $id_ord_schedule .'\',\''. $cus_incoterms_id .'\',\''. $id_con_booking .'\',\''. $id_ord_order .'\',\''. $pipeline_sched_id .'\',\''. $ref_number .'\',\''. $row['id_ord_schedule'] .'\');" class="btn btn-white btn-sm">
							<i class="fa fa-edit"></i></button>
							<span class="pull-right">'.$contact_code.'</span>
							</td>'; 
						} else { $grid_list .=''; }
					}
					
					$grid_list .='<td class="center">
						<a href="#" style="color:#676a6c" onclick="eMailForm(\''.$row['id_ord_schedule'].'\',\'logistics\',\'1\');">
							<i class="fa fa-envelope"></i>
						</a>
					</td>';
					
				$grid_list .='</tr>';
				
				$i_nr_containers = $row['nr_containers'];
				$i_date_eta = $row['month_eta'];
				if(!empty($row['modified_date'])){
					$i_modify_date = $row['modified_date'];
				} else {
					$i_modify_date = $row['created_date'];
				}
			}
			
			
			// Get last shipment number
			$sql_lship = "SELECT order_ship_nr FROM public.ord_ocean_schedule 
				WHERE ord_order_id = '" .$id_ord_order. "' 
			  ORDER By order_ship_nr DESC LIMIT 1
			";
			$result_lship = pg_query($conn, $sql_lship);
			$arr_lship = pg_fetch_assoc($result_lship);
			$last_ship_nr = $arr_lship['order_ship_nr'];
			
			
			/****
			*
			* REQUEST - EXPORTER
			*
			*****/
			
			$update_request_exporter = $_GET["update_request_exporter"];
			
			$sql_exporter = " SELECT 
				oc.notes_sup,
				oc.id_ord_schedule,
				oc.supplier_reference_nr,
				oc.week_etd,
				oc.month_etd,
				oc.ord_order_id,
				oc.offer_validity_date,
				o.pipeline_id,
				oc.price_sup_usd,
				oc.price_sup_eur,
				oc.order_ship_nr,
				oc.exp_modified_date,
				oc.exp_modified_contact,
				oc.price_currency_id,
				oc.pol_id,
				oc.supplier_incoterms_id,
				oc.supplier_contact_id,
				oc.supplier_person_id,
				oc.tank_provider,
				o.product_code,
				o.sup_reference_nr,
				o.customer_code,
				o.order_incoterms_id
			FROM public.v_order_schedule oc 
				LEFT JOIN public.v_order o ON o.id_ord_order = oc.ord_order_id
			WHERE oc.id_ord_schedule='$id_ord_schedule' ";
			$result_exporter = pg_query($conn, $sql_exporter);
			$arr_exporter = pg_fetch_assoc($result_exporter);

			$order_number = preg_replace('/\s+/', '', $arr_exporter['customer_code']).'-'.$arr_exporter['sup_reference_nr'].'-'.$arr_exporter['product_code'];
  
			// Supplier person   
			if($arr_exporter['supplier_contact_id']!=""){
				$sql_supplier = "SELECT id_contact, name FROM v_security_new WHERE id_supchain_type = 113 AND id_company =".$arr_exporter['supplier_contact_id']." ORDER BY name ASC";
			} else {
				$sql_supplier = "SELECT id_contact, name FROM v_security_new WHERE id_supchain_type = 113 ORDER BY name ASC";
			}

			$result_supplier = pg_query($conn, $sql_supplier);
			$supplier_list='<option value="">-- '.$lang['CONTRACT_SEL_SUP_PERSON'].' --</option>';
			while($arr_supplier = pg_fetch_assoc($result_supplier)){ 
				if($arr_exporter['supplier_person_id']==$arr_supplier['id_contact']){
					$sel_supplier="selected";
				} else { $sel_supplier=""; }
				$supplier_list .= '<option value="'. $arr_supplier['id_contact'] .'" '. $sel_supplier .'>'. $arr_supplier['name'] .'</option>';
			}
			
			// Supplier company
			$sql_supplier_comp = "SELECT DISTINCT company_name, id_company FROM v_security_new WHERE id_supchain_type = 113 ORDER BY company_name ASC";
			$result_supplier_comp = pg_query($conn, $sql_supplier_comp);
			$supplier_comp_list='<option value="">-- '.$lang['CONTRACT_SEL_SUP'].' --</option>';
			while($arr_supplier_comp = pg_fetch_assoc($result_supplier_comp)){ 
				if($arr_exporter['supplier_contact_id']==$arr_supplier_comp['id_company']){
					$sel_supplier_comp="selected";
				} else { $sel_supplier_comp=""; }
				$supplier_comp_list .= '<option value="'. $arr_supplier_comp['id_company'] .'"'. $sel_supplier_comp .'>'. $arr_supplier_comp['company_name'] .'</option>';
			}
			
			// Incoterms
			$sql_incoterms = "SELECT * FROM v_regvalues WHERE id_register=46 ORDER BY cvalue ASC";
			$rs_incoterms = pg_query($conn, $sql_incoterms);
			$incoterms_list = '<option value="">-- '.$lang['CONTRACT_SEL_INCOTERMS'].' --</option>';
			while ($row_incoterms = pg_fetch_assoc($rs_incoterms)) {
				if($arr_exporter['supplier_incoterms_id']==$row_incoterms['id_regvalue']){
					$sel_incoterms="selected";
				} else { $sel_incoterms=""; }
				$incoterms_list .= '<option value="'.$row_incoterms['id_regvalue'] .'"'. $sel_incoterms .'>'.$row_incoterms['cvalue'] .'</option>';
			}
			
			// Port of loading
			$sql_pol = "SELECT * FROM public.ord_towns_port WHERE port_type_id=272";
			$rs_pol = pg_query($conn, $sql_pol);
			$pol_list = '<option value="">-- '.$lang['CONTRACT_SEL_POL'].' --</option>';
			while ($row_pol = pg_fetch_assoc($rs_pol)) {
				if($arr_exporter['pol_id']==$row_pol['id_townport']){
					$sel_pol="selected";
				} else { $sel_pol=""; }
				$pol_list .= '<option value="'.$row_pol['id_townport'] .'"'. $sel_pol .'>'.$row_pol['portname'] .'</option>';
			}

			// Currency
			$sql_currency = "SELECT * FROM v_regvalues WHERE id_register=51 ORDER BY cvalue ASC";
			$rs_currency = pg_query($conn, $sql_currency);
			$currency_list = '<option value="">-- '.$lang['CONTRACT_SEL_CURRENCY'].' --</option>';
			while ($row_currency = pg_fetch_assoc($rs_currency)) {
				if($row_currency['id_regvalue']!=279){
					if($arr_exporter['price_currency_id']==$row_currency['id_regvalue']){
						$sel_currency="selected";
					} else { $sel_currency=""; }
					$currency_list .= '<option value="'.$row_currency['id_regvalue'] .'"'. $sel_currency .'>'.$row_currency['cvalue'] .'</option>';
				}
			}

			$mod_infos = '';
			if(($arr_exporter['exp_modified_contact']!="") && ($arr_exporter['exp_modified_date']!="")){
				$mod_infos = '<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label> '. $arr_exporter['exp_modified_contact'] .' <br/>
						<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].': </label> '. $arr_exporter['exp_modified_date'] .'
					</div>';
			} 
		
			if($arr_exporter['price_sup_eur']!=""){ $price='<input type="text" id="req_quote_price_sup_eur2" value="'. $arr_exporter['price_sup_eur'] .'" class="form-control">'; }
			else{ $price='<input type="text" id="req_quote_price_sup_eur2" value="'. $arr_exporter['price_sup_usd'] .'" class="form-control">'; }
			
			if($arr_exporter['order_ship_nr'] == $last_ship_nr){ 
				// if($arr_exporter['pipeline_id']<296){
					$send_btn ='<button class="btn btn-primary" id="sendProposalBtnId2" onclick="sendProposal(\''. $id_ord_schedule .'\');" style="margin-top:10px;" type="button" disabled>
						<i class="fa fa-paper-plane"></i>&nbsp;'.$lang['CONTRACT_SEND_SUP_PROPOSAL_BTN'].'
					</button>'; 
				// }
			} else { $send_btn =''; }
			
			
			if($arr_exporter['offer_validity_date']==""){
				$offer_validity_date=gmdate("Y/m/d");
			} else {
				$offer_validity_date=$arr_exporter['offer_validity_date'];
			}
			
			if($update_request_exporter == 1){
				$editQuote='<button class="btn btn-success" onclick="exporter_quote_editForm2(\''.$id_ord_schedule.'\',\''.$last_ship_nr.'\',\''.$arr['ord_order_id'].'\',\''.$arr['pipeline_id'].'\');" type="button"><i class="fa fa-edit"></i></button>';
			} else {
				$editQuote="";
			}
			
			$req_exporter='<div class="row no-padding" id="exporter_quote_formContent2">
				<div class="col-md-6">
					<div class="form-group">
						<label for="req_quote_supplier_contact_id2" class="ord_sum_label">'.$lang['CONTRACT_SUPPLIER'].'</label>
						<select id="req_quote_supplier_contact_id2" onchange="bySupplierContactId2(this.value);" class="form-control">
							'.$supplier_comp_list.'
						</select>
					</div>
					
					<div class="form-group">
						<label for="req_quote_incoterms_id2" class="ord_sum_label">'.$lang['CONTRACT_INCOTERMS'].'</label>
						<select id="req_quote_incoterms_id2" class="form-control">
							'.$incoterms_list.'
						</select>
					</div>
					
					<div class="form-group">
						<label for="req_quote_pol_id2" class="ord_sum_label">'.$lang['CONTRACT_PORT_OF_LOADING'].'</label>
						<select id="req_quote_pol_id2" onchange="transitDaysCal2(this.value,\''.$arr_exporter['ord_order_id'].'\',\''.$id_ord_schedule.'\',\''.$arr_exporter['order_incoterms_id'].'\');" class="form-control">
							'.$pol_list.'
						</select>
						<span id="transDays2"></span>
					</div>
					
					<div class="form-group">
						<label for="req_quote_month_etd2" class="ord_sum_label">'.$lang['CONTRACT_MONTH_SHIP'].'</label>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-8 no-padding">
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" onchange="getWeekShowQuote_etd2();" value="'. $arr_exporter['month_etd'] .'" id="req_quote_month_etd2">
									</div>
								</div>
								
								<div class="col-md-2 no-padding pull-right">
									<input type="text" id="req_quote_week_etd2" value="'. $arr_exporter['week_etd'] .'" class="form-control" disabled>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="req_quote_currency_id2" class="ord_sum_label">'.$lang['CONTRACT_CURRENCY'].'</label>
						<select id="req_quote_currency_id2" class="form-control">
							'.$currency_list.'
						</select>
					</div>
					
					<div class="form-group">
						<label for="area" class="ord_sum_label">'.$lang['CONTRACT_PRICE_PER_MT'].'</label>
						'.$price.'
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label for="req_quote_supplier_person_id2" class="ord_sum_label">'.$lang['CONTRACT_SUPP_CONTACT_PERSON'].'</label>
						<select id="req_quote_supplier_person_id2" class="form-control">
							'.$supplier_list.'
						</select>
					</div>
					
					<div class="form-group">
						<label for="area" class="ord_sum_label">'.$lang['CONTRACT_SUPP_REF_NUMB'].'</label>
						<input type="text" id="req_quote_reference_nr2" value="'. $arr_exporter['supplier_reference_nr'] .'" class="form-control">
					</div>
					
					<div class="form-group">
						<label for="req_quote_sup_quote_validity" class="ord_sum_label">'.$lang['CONTRACT_QUOTE_VALID_UNTIL_DATE'].'</label>
						<div class="input-group date" style="width:160px;">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control edit_delivery_date" id="req_quote_sup_quote_validity2" value="'. $offer_validity_date .'">
						</div>
					</div>
					
					<input type="hidden" class="form-control" id="exporter_id_ord_schedule" value="'. $arr_exporter['id_ord_schedule'] .'">
					<input type="hidden" class="form-control" id="req_quote_supplier_cf_date2" value="'. gmdate("Y/m/d H:i") .'">
					<input type="hidden" id="req_quote_week_eta2" value="" />
					<input type="hidden" id="req_quote_month_eta2" value="" />
					
					<div class="form-group">
						<label for="area" class="ord_sum_label">'.$lang['CONTRACT_NOTES'].'</label>
						<textarea id="req_quote_sm_notes2" style="height:34px;" class="form-control">'. $arr_exporter['notes_sup'] .'</textarea>
					</div>
					
					<div class="form-group">
						<label for="req_quote_tank_provider2" class="ord_sum_label">'.$lang['CONTRACT_TANK_PROVIDER'].'</label>
						<input type="text" id="req_quote_tank_provider2" value="'. $arr_exporter['tank_provider'] .'" class="form-control">
					</div>
		
					'. $mod_infos . $cf_date .'
					
				</div>
				
				<div class="col-md-12 no-padding">
					<input type="button" onclick="copyExporterQuote(\''.$id_ord_schedule.'\');" class="btn btn-info pull-left" value="Copy Exporter Quote" style="margin-left:15px;" />
				</div>
			</div>
			
			<div id="exporterQuoteBtn2" style="margin-top:10px;" class="pull-right">
				'.$editQuote.'
			</div>
			
			' . $send_btn . '';
			
			if($sched_create == 1){
				$addShipment = '<button class="btn btn-primary btn-sm" '.$editGrid.' onclick="addShipment(\''.$id_ord_order.'\',\''.$i_nr_containers.'\',\''.$i_date_eta.'\',\''.$i_modify_date.'\')">'.$lang['CONTRACT_ADD_SHIPMENT_BTN'].'</button>';
			} else {
				$addShipment = '';
			}
			
			
			/****
			*
			* REQUEST - FREIGHT
			*
			*****/
			
			$request_freight="";
	
			if(($cus_incoterms_id!=263) && ($cus_incoterms_id!=264)){
				
				$sql_freight = "Select f.id_con_box_fr,f.shipping_company,f.dem_pol_free,f.dem_pod_free,f.incoterm_name,f.pod_name,f.pol_name 
				From v_con_freight f, ord_schedule_freight sf 
					Where 
					sf.ord_ocean_schedule_id=$id_ord_schedule and 
					sf.ord_con_freight_id = f.id_con_box_fr  
					Order by sf.sequence_nr 
				";
				
				$result_freight = pg_query($conn, $sql_freight);
				$count_freight = pg_num_rows($result_freight); 
			
				if($count_freight==0){
					$sql_grid2 = " SELECT pol_id, order_pod_id, order_incoterms_id, package_type_id, id_ord_schedule, freight_calc, ord_order_id
						FROM public.v_order_schedule 
					WHERE id_ord_schedule=$id_ord_schedule";
					
					$result_grid2 = pg_query($conn, $sql_grid2);
					$row_grid2 = pg_fetch_assoc($result_grid2);
					$ord_order_id_freight = $row_grid2['ord_order_id'];
				
					if($ord_order_id_freight!=""){
						$sql_fstatus = " SELECT freight_status
							FROM public.v_order
						WHERE id_ord_order=$id_ord_order";
						
						$result_fstatus = pg_query($conn, $sql_fstatus);
						$r_fstatus = pg_fetch_assoc($result_fstatus);
						$freight_status = $r_fstatus['freight_status'];
						
					} else {
						$freight_status="";
					}
					
					$request_freight="0??".$row_grid2['pol_id']."#".$row_grid2['order_pod_id']."#".$row_grid2['order_incoterms_id']."#".$row_grid2['package_type_id']."#".$row_grid2['id_ord_schedule']."#".$row_grid2['freight_calc']."#".$freight_status;
					
				} else {
					
					$freight_list ='';
					$id_con_box_fr ='';
				
					while($row_freight = pg_fetch_assoc($result_freight)){
						$id_con_box_fr = $row_freight['id_con_box_fr'];
						
						$sql_show = "Select id_con_box_fr,shipping_company,freight_eur,freight_usd,add_eur,add_usd,total_eur,packaging_type_id,    
							packaging_type_name,transit_time,trans_type_id,trans_type_name,trans_location_id,trans_location_name,dem_pol_free,    
							dem_pol_cost_after,dem_pod_free,dem_pod_cost_after,returns_empty_id,rate_valid_until,total_usd,freight_chf,total_chf,    
							add_chf,transport_type_id,transport_type_name,weight_packaging_type,transit_time_position,transit_time_position_ready,    
							trans_delay,id_owner,incoterm_id,incoterm_name,pod_townport_id,pod_name,pol_townport_id,pol_name,sup_incoterms,sup_incoterms_name 
						from v_con_freight 
							Where 
							id_con_box_fr = $id_con_box_fr
						";
					
						$result_show = pg_query($conn, $sql_show);
						$row_show = pg_fetch_assoc($result_show);
						
						$freight_list .= $row_show['pol_name']."##".$row_show['dem_pol_free']."##".$row_show['incoterm_name']."##".$row_show['pod_name']."##".$row_show['dem_pod_free']."##".$row_show['shipping_company']."##".$row_show['rate_valid_until']."##".$row_show['packaging_type_name']."##".$row_show['trans_delay']."%%";
					}
					
					$freight_list .="end";
					
					$sql_fgrid = " SELECT pol_id, order_pod_id, order_incoterms_id, package_type_id, id_ord_schedule, freight_calc, ord_order_id
						FROM public.v_order_schedule 
					WHERE id_ord_schedule=$id_ord_schedule";
			
					$result_fgrid = pg_query($conn, $sql_fgrid);
					$row_fgrid = pg_fetch_assoc($result_fgrid);
					$ord_order_id_fgrid = $row_fgrid['ord_order_id'];
				
					if($ord_order_id_fgrid!=""){
						$sql_frgt = " SELECT freight_status
							FROM public.v_order
						WHERE id_ord_order=$ord_order_id_fgrid";
						
						$result_frgt = pg_query($conn, $sql_frgt);
						$r_frgt = pg_fetch_assoc($result_frgt);
						$freight_status = $r_frgt['freight_status'];
						
					} else {
						$freight_status="";
					}
				
					$request_freight="1??".$freight_list."??".$row_fgrid['pol_id']."#".$row_fgrid['order_pod_id']."#".$row_fgrid['order_incoterms_id']."#".$row_fgrid['package_type_id']."#".$row_fgrid['id_ord_schedule']."#".$row_fgrid['freight_calc']."#".$freight_status;
				}
				
			} else {
				$request_freight="3??3"; 
			}
			
	
			/****
			*
			* REQUEST - CALCULATION
			*
			*****/
			
			$calc_update = $_GET['calc_update'];
			
			$request_calculation ='';
			$sql_calculation = "select oc.id_proposal_calc, os.pol_id, 
				get_port_name(os.pol_id) pol_name, 
				os.pod_id, os.id_ord_schedule,
				get_port_name(os.pod_id) pod_name, 
				getregvalue(os.cus_incoterms_id) cus_incoterms, 
				oo.order_nr, 
				oo.nr_shipments, 
				os.nr_containers, 
				os.weight_container, 
				os.weight_shipment, 
				oc.usd_chf_exc_rate, 
				oc.eur_chf_exc_rate, 
				oc.usd_eur_exc_rate, 
				oc.ship_sales_value_tone, 
				oc.ship_sales_value, 
				oc.ship_cost_tone, 
				oc.ship_tts, 
				oc.ship_ttc, 
				oo.order_status,
				oc.proposal_currency_id,
				oc.margin_per_ton as margin_mt, 
				os.ord_order_id,
				oc.proposal_price_chf as sales_price_mt_chf,
				oc.exch_datetime
			from 
				ord_proposal_calc oc, ord_ocean_Schedule os, 
				ord_order oo 
				where oo.id_ord_order=os.ord_order_id 
				and os.id_ord_schedule=oc.ord_schedule_id 
			AND os.id_ord_schedule=$id_ord_schedule";  

			$rst_calculation = pg_query($conn, $sql_calculation);
			$row_calculation = pg_fetch_assoc($rst_calculation);
		
			if($row_calculation['nr_shipments'] == $last_ship_nr){
				$last_shipment=1;
			} else {
				$last_shipment=0;
			}
		
			if($row_calculation['exch_datetime']!=""){
				$exch_datetime='Open Exchange Rates @ '.$row_calculation['exch_datetime'];
			} else {
				$exch_datetime='';
			}
			
			$sql4 = "SELECT calc_modified_date, calc_modified_contact FROM public.v_order_schedule WHERE id_ord_schedule = $id_ord_schedule";
			$rst4 = pg_query($conn, $sql4);
			
			$mod_infos = '';
			if($rst4){
				$row4 = pg_fetch_assoc($rst4);
				$mod_infos = '<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].'</label>: '.$row4['calc_modified_contact'].' <label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_DATE'].'</label>: '.$row4['calc_modified_date'];
			}
			
			$sql_currency_calc = 'SELECT * FROM v_regvalues WHERE id_register=51 ORDER BY cvalue ASC';
			$rs_currency_calc = pg_query($conn, $sql_currency_calc);

			$currency_list_calc = '<option value="">-- '.$lang['CONTRACT_SEL_CURRENCY'].' --</option>';
			while ($row_currency_calc = pg_fetch_assoc($rs_currency_calc)) {
				if($row_currency_calc['id_regvalue'] == $row_calculation['proposal_currency_id']){ $sel_cur_calc = 'selected'; }else{ $sel_cur_calc = ''; }
				$currency_list_calc .= '<option value="'.$row_currency_calc['id_regvalue'] .'"'.$sel_cur_calc.'>'.$row_currency_calc['cvalue'] .'</option>';
			}

			setlocale(LC_MONETARY, 'de_DE.UTF-8');
			$id_proposal_calc = $row_calculation['id_proposal_calc'];
			
			
			$request_calculation .='<div class="row" style="padding:10px 5px 5px 5px; margin-bottom:20px; background:#f5f5f5;" id="calcVariableBloc2">
				<div class="col-md-12">
					<input type="button" onclick="saveCalcVariables(\''.$id_proposal_calc.'\',\''.$row_calculation['ord_order_id'].'\');" class="btn btn-info pull-left" value="'.$lang['CONTRACT_COPY_VARIABLES_BTN'].'" />
					<input type="button" onclick="calculateAll(\''.$id_ord_schedule.'\');" class="btn btn-info pull-left" value="'.$lang['CONTRACT_RECALCULATE_ALL_BTN'].'" style="margin-left:15px;" />
					<input type="button" onclick="getCurrency(\''.$id_proposal_calc.'\');" class="btn btn-info pull-left" value="'.$lang['CONTRACT_GET_CURRENCY_BTN'].'" style="margin-left:15px;" />
					<span id="show_saveCalcVariables2">';
					
					if($pipeline_id<296){
						if($calc_update == 1){
							$request_calculation .='<button onclick="editCalcVariables(\''.$id_ord_schedule.'\',\''.$row_calculation['order_status'].'\');" class="btn btn-success pull-right"><i class="fa fa-edit"></i></button>';
						}
						$active_calc='disabled';
					} else {
						$active_calc='';
					}
						
					$request_calculation .='</span>
				</div>
			
				<div class="row no-margins">
					<div class="col-md-4">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_CALCULATE_IN'].'</label><br/>
							<select id="saveIdCurrency2" class="form-control">
								'.$currency_list_calc.'
							</select>
						</div>
					</div>
				
					<div class="col-md-2">
						<div class="form-group">
							<label class="ord_sum_label">US$/CHF</label><br/>
								<input type="text" id="saveIdUsdChf2" value="'.number_format($row_calculation['usd_chf_exc_rate'], 2, '.', '').'" class="form-control">
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
								<label class="ord_sum_label">US$/EUR</label><br/>
								<input type="text" id="saveIdUsdEur2" value="'.number_format($row_calculation['usd_eur_exc_rate'], 2, '.', '').'" class="form-control">
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
								<label class="ord_sum_label">EUR/CHF</label><br/>
								<input type="text" id="saveIdEurChf2" value="'.number_format($row_calculation['eur_chf_exc_rate'], 2, '.', '').'" class="form-control">
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
								<label class="ord_sum_label">'.$lang['CONTRACT_MARGIN_MT'].'</label><br/>
								<input type="text" id="saveIdMargin2" value="'.$row_calculation['margin_mt'].'" class="form-control">
							</div>
						</div>
					</div>
					
					<div class="row no-margins">
						<div class="col-md-7">
							<label class="ord_sum_label" id="saveIdTimeStampExcRt2">
								'.$exch_datetime.'
							</label>
						</div>
						
						<div class="col-md-2">
							<div class="hide" id="edSalesMT2">
								<a href="#" onclick="reduiceSP(\''.$id_proposal_calc.'\',\''.$row_calculation['ship_sales_value_tone'].'\',\''.$row_calculation['margin_mt'].'\',\''.$id_ord_schedule.'\');" style="color:red;"><i class="fa fa-minus"></i></a>
								&nbsp;&nbsp;<a href="#" onclick="increaseSP(\''.$id_proposal_calc.'\',\''.$row_calculation['ship_sales_value_tone'].'\',\''.$row_calculation['margin_mt'].'\',\''.$id_ord_schedule.'\');" style="color:green;"><i class="fa fa-plus"></i></a>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<input type="number" id="salesMTnewVal2" value="" class="form-control hide" />
							</div>
						</div>	
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-2" style="width:19%;">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_TOTAL_SERVICES'].'</label><br/>
							'. money_format('%!n', $row_calculation['ship_tts']) .'
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_SERVICES_MT'].'</label><br/>
							'. money_format('%!n', $row_calculation['ship_cost_tone']) .'
						</div>
					</div>
			
					<div class="col-md-2">
						<div class="form-group">
							<label class="ord_sum_label"> '.$lang['CONTRACT_TOTAL_COAST'].'</label><br/>
							'. money_format('%!n', $row_calculation['ship_ttc']) .'
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_TOTAL_SALES'].'</label><br/>
							'. money_format('%!n', $row_calculation['ship_sales_value']) .'
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label class="ord_sum_label">'.$lang['CONTRACT_SALES_MT'].'</label><br/>
							'. money_format('%!n', $row_calculation['ship_sales_value_tone']) .'
						</div>
					</div>
				</div>
	
			<div class="row">
				<div class="col-md-12">
					'.$mod_infos.'
				</div>
			</div>';
			
			if($id_proposal_calc){
				$sql_table_calc = "select id_ord_calc_item, ord_proposal_id, 
					sequence_nr, item_name, getregvalue(measure_unit) measure_unit, 
					cost_usd, cost_eur, cost_chf, active 
				from ord_proposal_item 
					WHERE ord_proposal_id='$id_proposal_calc'
					order by sequence_nr 
				";
				
				$grid_list_calc ='';
			
				$rst_table_calc = pg_query($conn, $sql_table_calc);
				while($row_table_calc = pg_fetch_assoc($rst_table_calc)){
					if($row_table_calc['active']=='t'){
						$check='checked';
					} else { $check=''; }
					
					setlocale(LC_MONETARY, 'en_US');
					$cost_usd = money_format('%!n', $row_table_calc['cost_usd']);
					
					setlocale(LC_MONETARY, 'de_DE.UTF-8');
					$cost_eur = money_format('%!n', $row_table_calc['cost_eur']);
					$cost_chf = money_format('%!n', $row_table_calc['cost_chf']);
				
					$grid_list_calc .='<tr class="gradeX">
						<td>'. $row_table_calc['sequence_nr'] .'</td>
						<td>'. $row_table_calc['item_name'] .'</td>
						<td>'. $row_table_calc['measure_unit'] .'</td>
						<td class="center">'. $cost_usd .'</td>
						<td class="center">'. $cost_eur .'</td>
						<td class="center">'. $cost_chf .'</td>
						<td><input type="checkbox" class="i-checks" '.$active_calc.' value="'. $row_table_calc['active'] .'#'.$row_table_calc['id_ord_calc_item'].'#'.$id_proposal_calc.'" '.$check.'></td>
						<td>';
						
						if($pipeline_id<296){
							if($calc_update == 1){
								$grid_list_calc .='<a href="#" onclick="showEditCalcLine('.$row_table_calc['id_ord_calc_item'].','.$id_proposal_calc.');" class="btn btn-white btn-sm">
									<i class="fa fa-edit"></i>
								</a>';
							}
						} else {
							$grid_list_calc .='---';
						}
						
						$grid_list_calc .='</td>					
					</tr>';
				}
			
				$request_calculation .='<div class="row" style="margin-top:20px; padding:0 5px;">
					<div style="margin-bottom:20px;" class="col-md-12">
						<button type="submit" id="tableCalcBtn2" onclick="showCalcTable('.$id_proposal_calc.','.$id_ord_schedule.','.$last_shipment.');" class="btn pull-left"><i class="fa fa-calculator"></i> '.$lang['CONTRACT_CALCULATE_BTN'].'</button>
					</div>
					
					<table class="table table-striped table-bordered table-hover dataTables-example" id="tableCalc" style="font-size:13px;">
						<thead>
							<th>'.$lang['CONTRACT_SEQ#'].'</th>
							<th>'.$lang['CONTRACT_ITEM'].'</th>
							<th>'.$lang['CONTRACT_UNIT'].'</th>
							<th>US$</th>
							<th>EUR</th>
							<th>CHF</th>
							<th>'.$lang['CONTRACT_ACTIVE'].'</th>
							<th>'.$lang['CONTRACT_EDIT'].'</th>
						</thead>
					
						<tbody id="calcTable">
							'.$grid_list_calc.'
						</tbody>
					</table>
				</div>';
			}
			
			/****
			*
			* REQUEST - PROPOSAL
			*
			*****/
			
			$schedule_proposal ='';
			$sql_proposal = "select 
				vp.product,
				vs.port_name AS pol,
				vs.month_etd,
				vs.week_etd,
				get_port_name(vs.pod_id) AS pod,
				vs.month_eta,
				vs.week_eta,
				vp.package_type,
				vs.nr_containers,
				vs.weight_shipment,
				vs.pipeline_id,
				vp.incoterms,
				oc.proposal_price_chf as sales_price_mt_chf,
				oc.total_price_chf,
				oc.ship_sales_value_tone,
				oc.ship_sales_value,
				oc.proposal_currency_id,
				getregvalue(oc.proposal_currency_id) currency_name,
				vp.ord_order_id,
				vp.order_ship_nr				
			from 
				ord_proposal_calc oc, v_proposal vp, v_order_schedule vs
				
				WHERE vp.id_ord_schedule=oc.ord_schedule_id 
			AND vs.id_ord_schedule=vp.id_ord_schedule
			AND vp.id_ord_schedule=$id_ord_schedule";  
			
			$rst_proposal = pg_query($conn, $sql_proposal);
			$row_proposal = pg_fetch_assoc($rst_proposal);
			
			setlocale(LC_MONETARY, 'de_DE.UTF-8');
			
			if($row_proposal['order_ship_nr'] == $last_ship_nr){
				$proposal_btn = '<div class="row">
					<div class="col-sm-12">
						<button class="btn btn-success pull-left" id="create_proposal_doc_btn" onclick="proposalDoc('.$id_ord_schedule.','.$row_proposal['order_ship_nr'].','.$row_proposal['ord_order_id'].');" style="margin-top:10px;" type="button" disabled><i class="fa fa-paper-plane"></i>&nbsp;'.$lang['CONTRACT_SEND_PROPOSAL_NEGO_BTN'].'</button>
					</div>
				
					<div class="col-sm-6">
						<button class="btn btn-info" id="sales_pipeline_btn" onclick="sales_pipeline('.$id_ord_schedule.','.$row_proposal['order_ship_nr'].');" style="margin-top:10px;" type="button" disabled>'.$lang['CONTRACT_PROPOSAL_ACCEP_CUS_BTN'].'</button>
					</div>';
					
					// if($pipeline_id<296){
						$proposal_btn .= '<div class="col-sm-6"><span class="pull-right hide" id="proposal_doc_toggle">
							<button class="btn btn-success" onclick="showProposalDocBtn('.$row_proposal['ord_order_id'].','.$row_proposal['order_ship_nr'].','.$row_proposal['pipeline_id'].');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>
						</span></div>';
					// }
					
				$proposal_btn .= '</div>';
				
			} else {
				$proposal_btn = '';
			}
			
			$schedule_proposal .='<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_PRODUCT'].'</label><br/>
						'.$row_proposal['product'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_POL'].'</label><br/>
						'.$row_proposal['pol'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_SHIPPING_WEEK'].'</label><br/>
						'.$row_proposal['month_etd'].' / '.$row_proposal['week_etd'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_POD'].'</label><br/>
						'.$row_proposal['pod'].'
					</div>
				
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_ARRIVAL_WEEK'].'</label><br/>
						'.$row_proposal['month_eta'].' / '.$row_proposal['week_eta'].'
					</div>
				
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_PACKAGE_TYPE'].'</label><br/>  
						'.$row_proposal['package_type'].'
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_N_CONTAINER'].'</label><br/>
						'.$row_proposal['nr_containers'].'
					</div>
					
					<div class="form-group">    
						<label class="ord_sum_label">'.$lang['CONTRACT_TT_WEIGHT'].'</label><br/>
						'.$row_proposal['weight_shipment'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_INCOTERMS'].'</label><br/>
						'.$row_proposal['incoterms'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_CURRENCY'].'</label><br/>
						'.$row_proposal['currency_name'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_SALES_PRICE_MT'].'</label><br/>
						'.money_format('%!n', $row_proposal['ship_sales_value']).'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_TT_SHIPMENT_PRICE'].'</label><br/>
						'.money_format('%!n', $row_proposal['ship_sales_value_tone']).'
					</div>
				</div>
				
				<div class="col-md-12">
					'.$proposal_btn.'
				</div>
			</div>';
		
		
			// ORDER
			$sql_order = "SELECT 
				get_contact_name(os.ord_cus_contact_id) as client_name, 
				os.customer_reference_nr, 
				os.customer_ref_ship_nr,
				os.product_code,
				os.pol_id,
				os.pod_id,
				get_port_name(os.pol_id) pol_name,
                get_port_name(os.pod_id) pod_name,
				os.month_etd,
				os.week_etd,
				os.month_eta, 
				os.week_eta, 
				os.weight_shipment, 
				get_contact_name(os.ord_imp_contact_id) as importer_name,
				os.ord_order_id,
				os.order_ship_nr,
				oc.proposal_currency_id,
				oc.ship_sales_value_tone,
				oc.ship_sales_value, 
				os.fa_reference_nr,
				os.supplier_reference_nr,
				os.sales_modified_contact,
				os.sales_modified_date,
				os.cus_incoterms_id,
				vo.order_nr,
				vo.nr_shipments,
				vo.incoterms
			  from v_order_schedule os, ord_proposal_calc oc, v_order vo 
			  where oc.ord_schedule_id=os.id_ord_schedule
			  and oc.order_id=vo.id_ord_order 
			and oc.ord_schedule_id=$id_ord_schedule";

			$rs_order = pg_query($conn, $sql_order);
			$row_order = pg_fetch_assoc($rs_order);

			setlocale(LC_MONETARY, 'de_DE.UTF-8');
			
			$sql_nbSup = "select count(distinct supplier_contact_id) As num_sup from ord_ocean_Schedule where ord_ocean_schedule.ord_order_id=$id_ord_order";
			$rs_nbSup = pg_query($conn, $sql_nbSup);
			$row_nbSup = pg_fetch_assoc($rs_nbSup);
			
			$po_to_sup = '';
			if($row_nbSup['num_sup']==2){
				$sql_supId = "select distinct supplier_contact_id, get_contact_name(supplier_contact_id) supplier_name from ord_ocean_Schedule where ord_ocean_schedule.ord_order_id=$id_ord_order";
				$rs_supId = pg_query($conn, $sql_supId);
				
				$x=1;
				while($row_supId = pg_fetch_assoc($rs_supId)){
					$po_to_sup .= '<div class="col-md-10 no-padding">
						<button class="btn btn-primary pull-left" id="puchase_order_supp_btn2'.$x.'" disabled onclick="puchase_order_supp('.$id_ord_order.','.$row_order['cus_incoterms_id'].','.$id_ord_schedule.','.$row_supId['supplier_contact_id'].');" style="margin-top:10px;" type="button">'.$lang['CONTRACT_CLT_PO_SUP_BTN'].' ('.$row_supId['supplier_name'].')</button>
					</div>';
					$x++;
				}
				
			} else {
				$po_to_sup .= '<div class="col-md-10 no-padding"><button class="btn btn-primary pull-left" id="puchase_order_supp_btn2" disabled onclick="puchase_order_supp('.$id_ord_order.','.$row_order['cus_incoterms_id'].','.$id_ord_schedule.',\'\');" style="margin-top:10px;" type="button">'.$lang['CONTRACT_CLT_PO_SUP_BTN'].'</button> </div>';
			}
			
			$sql_last_ship = "select max(id_ord_schedule) As last_id from ord_ocean_schedule where ord_order_id = $id_ord_order";
			$rs_last_ship = pg_query($conn, $sql_last_ship);
			$row_last_ship = pg_fetch_assoc($rs_last_ship);
		
			if($id_ord_schedule == $row_last_ship['last_id']){
				$btn = '<div class="col-md-10 no-padding"><button class="btn btn-info pull-left" id="create_contract_btn2" disabled onclick="create_contract('.$id_ord_order.','.$row_order['cus_incoterms_id'].');" style="margin-top:10px;" type="button">'.$lang['CONTRACT_SALES_CONTRACT_BTN'].'</button> </div>
				<div class="col-md-10 no-padding"><button class="btn btn-primary pull-left" id="puchase_order_fa_btn2" disabled onclick="puchase_order('.$id_ord_order.','.$row_order['cus_incoterms_id'].','.$id_ord_schedule.');" style="margin-top:10px;" type="button">'.$lang['CONTRACT_CLT_PO_FA_BTN'].'</button> </div>
				';
			} else {
				$btn = '';
			}
		
			if(($row_order['sales_modified_contact']!="")&&($row_order['sales_modified_date']!="")){
				$mod='<div class="form-group" style="margin-top:10px;">
					<label class="ord_sum_label">'.$lang['CONTRACT_MODIFIED_BY'].': </label>'.$row_order['sales_modified_contact'].'<br/>
					<label class="ord_sum_label"> '.$lang['CONTRACT_MODIFIED_DATE'].': </label>'.$row_order['sales_modified_date'].'
				</div>';
				
			} else { $mod=''; }
			
			
			$schedule_order .='<div class="row" style="background:#f5f5f5;">
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_CLT_NAME'].'</label><br/>
						'.$row_order['client_name'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_CONTRACT_NUMB'].'</label><br/>
						'.$row_order['customer_reference_nr'].'
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_IMP_NAME'].'</label><br/>
						'.$row_order['importer_name'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_CONTRACT_NUMB'].'</label><br/>
						'.$row_order['order_nr'].'
					</div>
				</div>
			</div>
			
			<div class="row">			
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_PO_NUMB'].'</label><br/>
						<span id="cusRefShipNrShow2">'.$row_order['customer_ref_ship_nr'].'</span>
						<div class="form-group hide" id="cusRefShipNrInput2">
							<input id="customer_ref_ship_nr2" class="form-control" value="'.$row_order['customer_ref_ship_nr'].'" />
						</div>
						<span id="cusRefShipNrManagBtn2" class="hide">
							<a href="#" onclick="editCusRefShipNr2('.$id_ord_schedule.');" class="btn btn-white btn-sm">
								<i class="fa fa-edit"></i>
							</a>
						</span>
					</div>
	
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_PRODUCT'].'</label><br/>
						'.$row_order['product_code'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_INCOTERMS'].'</label><br/>
						'.$row_order['incoterms'].'
					</div>
			
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_FROM'].'</label><br/>
						'.$row_order['pol_name'].'
					</div>
			
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_TO'].'</label><br/>
						'.$row_order['pod_name'].'
					</div>
			
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_ETD'].'</label><br/>
						'.$row_order['month_etd'].' / '.$row_order['week_etd'].'
					</div>
			
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_ETA'].'</label><br/>
						'.$row_order['month_eta'].' / '.$row_order['week_eta'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_TT_WEIGHT'].'</label><br/>
						'.$row_order['weight_shipment'].'
					</div>
					
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_SHIPMENT_NUMB'].'</label><br/>
						'.$row_order['order_ship_nr'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_CURRENCY'].'</label><br/>
						'.$row_order['proposal_currency_id'].'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_SALES_PER_MT'].'</label><br/>
						'.money_format('%!n', $row_order['ship_sales_value_tone']).'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_TT_SHIPMENT_PRICE'].'</label><br/>
						'.money_format('%!n', $row_order['ship_sales_value']).'
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_FREIGHT_AGT_PO_N'].'</label><br/>
						<span id="faRefNrShow2">'.$row_order['fa_reference_nr'].'</span>
						<div class="form-group hide" id="faRefNrInput2">
							<input id="fa_reference_nr_OC2" class="form-control" value="'.$row_order['fa_reference_nr'].'" />
						</div>
						<span id="faRefNrManagBtn2" class="hide">
							<a href="#" onclick="editfaRefNr2('.$id_ord_schedule.');" class="btn btn-white btn-sm">
								<i class="fa fa-edit"></i>
							</a>
						</span>
					</div>
					
					<div class="form-group">
						<label class="ord_sum_label">'.$lang['CONTRACT_EXP_PO_N'].'</label><br/>
						<span id="supRefNrShow2">'.$row_order['supplier_reference_nr'].'</span>
						<div class="form-group hide" id="supRefNrInput2">
							<input id="supplier_reference_nr2" class="form-control" value="'.$row_order['supplier_reference_nr'].'" />
						</div>
						<span id="supRefNrManagBtn2" class="hide">
							<a href="#" onclick="editsupRefNr2('.$id_ord_schedule.');" class="btn btn-white btn-sm">
								<i class="fa fa-edit"></i>
							</a>
						</span>
					</div>
					
					'.$mod.'
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					'. $btn .'
					<span id="showhideOrdConfirmEditBtn2">
						<button class="btn btn-success pull-right" onclick="edit_order_confirmation2('.$id_ord_schedule.');" style="margin-top:10px;" type="button">
						<i class="fa fa-edit"></i></button>
					</span>
				</div>
			</div>';
			
			$dom=$user_summary.'@@'.$importer_summary.'@@'.$contract_summary.'@@'.$grid_list.'@@'.$req_exporter.'@@'.$addShipment.'@@'.$request_freight.'@@'.$last_ship_nr.'@@'.$request_calculation.'@@'.$schedule_proposal.'@@'.$schedule_order.'@@'.$exp_status.'@@'.$calculate_status.'@@'.$order_status.'@@'.$freight_status;
		
		break;
		
		
		case "summary_status_liste":
		
			$id_ord_order = $_GET['id_ord_order'];
			$show_delete = $_GET['show_delete'];
			$typ_sum_status = '<option value="">-- Select a status --</option>';
		
			$sql_sum_status = 'SELECT id_regvalue, cvalue FROM v_regvalues WHERE id_register=43 AND id_regvalue!=393 ORDER BY id_regvalue ASC';
			$rs_sum_status = pg_query($conn, $sql_sum_status);
			while ($row_sum_status = pg_fetch_assoc($rs_sum_status)) {
				$typ_sum_status .= '<option value="'.$row_sum_status['id_regvalue'] .'">'.$row_sum_status['cvalue'] .'</option>';
			}
			
			if($show_delete == 1){
				$sql_delete = 'SELECT id_regvalue, cvalue FROM v_regvalues WHERE id_regvalue=393';
				$rs_delete = pg_query($conn, $sql_delete);
				$row_delete = pg_fetch_assoc($rs_delete);
				$typ_sum_status .= '<option value="'.$row_delete['id_regvalue'] .'">'.$row_delete['cvalue'] .'</option>';
			}
			
			$dom = $typ_sum_status;
		
		break;
		
		
		case "invoice_customs_data":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			
			$sql_header = "select to_char(current_date,'dd.mm.yyyy') as invoice_date,
				v_order.order_nr||'.'||v_order_schedule.order_ship_nr as invoice_number
				FROM v_order_schedule, v_order_msg, v_order, product, v_logistics, v_schedule_calc
				where v_order_msg.id_ord_order=v_order_schedule.ord_order_id
				and v_order.id_ord_order=v_order_schedule.ord_order_id
				and v_order_schedule.id_ord_schedule = $ord_schedule_id
				and product.id_product=v_order_Schedule.product_id
				and v_logistics. id_ord_schedule=v_order_schedule.id_ord_schedule
				and v_schedule_calc.id_ord_schedule=v_order_schedule.id_ord_schedule
			";
		
			$rs_header = pg_query($conn, $sql_header);
			$row_header = pg_fetch_assoc($rs_header);
			
			$invoice_date = $row_header['invoice_date'];
			$invoice_number = $row_header['invoice_number'];
			
			$dom = $invoice_date. '##' .$invoice_number;
		
		break;
		
		
		case "invoice_1_data":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
		
			$sql_display="SELECT v_order.order_nr||'.'||v_order_schedule.order_ship_nr as invoice_number,
			v_con_booking_display.inv1_date
			FROM v_order_schedule, v_con_booking_display, v_order
			WHERE v_order.id_ord_order=v_order_schedule.ord_order_id
			AND v_order_schedule.id_ord_schedule=v_con_booking_display.ord_schedule_id
			AND v_con_booking_display.ord_schedule_id=$ord_schedule_id
			AND booking_segment=1";
			
			$rs_display = pg_query($conn, $sql_display);
			$row_display = pg_fetch_assoc($rs_display);
			
			$dom = $row_display['inv1_date'].'##'.$row_display['invoice_number'].'##'.gmdate("Y.m.d");
		
		break;
		
		
		case "invoice_2_data":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
		
			$sql_display="SELECT v_order.order_nr||'.'||v_order_schedule.order_ship_nr as invoice_number,
			v_con_booking_display.inv2_date
			FROM v_order_schedule, v_con_booking_display, v_order
			WHERE v_order.id_ord_order=v_order_schedule.ord_order_id
			AND v_order_schedule.id_ord_schedule=v_con_booking_display.ord_schedule_id
			AND v_con_booking_display.ord_schedule_id=$ord_schedule_id
			AND booking_segment=1";
			
			$rs_display = pg_query($conn, $sql_display);
			$row_display = pg_fetch_assoc($rs_display);
		
			$dom = $row_display['inv2_date'].'##'.$row_display['invoice_number'].'##'.gmdate("Y.m.d");
		
		break;
		
		
		
		
		
		case "system_admin_mail":
		
			$ord_order_id = $_GET['ord_order_id'];
			$ord_schedule_id = $_GET['ord_schedule_id'];
			$doc_type_id = $_GET['doc_type_id'];
			
			$sql = "SELECT v_order.order_nr||'.'||v_order_schedule.order_ship_nr as file_name 
				FROM v_order, v_order_schedule 
				WHERE v_order_schedule.ord_order_id=v_order.id_ord_order
				AND v_order_schedule.id_ord_schedule=$ord_schedule_id
			";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);
			
			$file_name = $row['file_name'];
			
			if($doc_type_id == 13){
				$subject = 'B/L document added - '. $file_name;
			} else {
				$subject = 'Lab Analysis Shipment document added - '. $file_name;
			}
			
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
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
						<td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
						<center data-parsed="" style="min-width:580px;width:100%"><table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;padding:0;text-align:center;vertical-align:top;width:580px">
					<tbody>
					
					<tr style="padding:0;text-align:left;vertical-align:top">
					<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					
					<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					</td></tr></tbody></table>
		
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
					<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tr>
						<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
							<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
						</td>
						<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
							iCRM.live Message from icollect.live Back Office:
						</td>
					</tr></table>
	
					<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					</td></tr></tbody></table>
			
					<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
					<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
							<strong>'.$subject.'</strong>
						</p>
					</th></tr></tbody></table></th></tr></tbody></table></th>
					<th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0">
					</th></tr></tbody></table>
				
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						Before printing think about the ENVIRONMENT!<br>
						Warning: If you have received this email by error, please delete it and
						inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
					</p></th></tr></tbody></table></th></tr></tbody></table></td></tr></tbody></table></center></td></tr></tbody>
				</table></body>
			</html>';
			
			$sender="noreply@icollect.live";
			$recipient='croth53@gmail.com';
			
			$mail = new PHPMailer;
			$mail->isSMTP();
			// $mail->SMTPDebug = 2;
			// $mail->SMTPSecure = 'ssl';
			$mail->Debugoutput = 'html';
			$mail->Host = "d4i.maxapex.net";
			$mail->Port = 587;
			$mail->SMTPAuth = true;
			$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
			$mail->Username = ID_USER;
			$mail->Password = ID_PASS;
			$mail->setFrom('no-reply@icollect.live');
			$mail->AddCC('zoran.kuret@alfa24.ba');
			// $mail->addReplyTo($imp_mail, 'Sales');
			$mail->addAddress('croth53@gmail.com');
			$mail->Subject = $subject;
			$mail->msgHTML($message);
			$mail->AltBody = 'This is a plain-text message body';
			//send the message, check for errors
			if (!$mail->send()) {
				$dom=0;
			} else {
				$dom=1;
			}
		
		break;
		
		
		case "delete_container":
		
			$id_con_list = $_GET['id_con_list'];
			
			$sql1 = "delete from ord_con_loading_item where con_list_id=$id_con_list";
			$result1 = pg_query($conn, $sql1);
			
			if ($result1) {
				$sql = "delete from ord_con_list where id_con_list=$id_con_list";
				$result = pg_query($conn, $sql);
		
				if ($result) {
					$dom=1;
				} else {
					$dom=0;
				}
				
			} else {
				$dom=0;
			}
			
		break;

		
		case "update_traceability_document":
			
			$ord_schedule_id = $_GET["ord_schedule_id"];
			$id_con_booking = $_GET["id_con_booking"];
		
			if(isset($_GET["trace_doc_nr"])){
				$trace_doc_nr = $_GET["trace_doc_nr"];
				$edit_trace_doc_nr = "trace_doc_nr='$trace_doc_nr', ";
			} else { $trace_doc_nr = "";  $edit_trace_doc_nr = ''; }
			
			if(isset($_GET["trace_doc_date"])){
				$trace_doc_date = $_GET["trace_doc_date"];
				$edit_trace_doc_date = "trace_doc_date='$trace_doc_date', ";
			} else { $trace_doc_date = "";  $edit_trace_doc_date = ''; }
		
			if(isset($_GET["trace_doc_publish"])){
				$trace_doc_publish = $_GET["trace_doc_publish"];
				$edit_trace_doc_publish = "trace_doc_publish='$trace_doc_publish', ";
			} else { $trace_doc_publish = "";  $edit_trace_doc_publish = ''; }
		
			if(isset($_GET["trace_buyer_days"])){
				$trace_buyer_days = $_GET["trace_buyer_days"];
				$edit_trace_buyer_days = "trace_buyer_days='$trace_buyer_days', ";
			} else { $trace_buyer_days = "";  $edit_trace_buyer_days = ''; }
		
		
			$sql = "UPDATE public.ord_con_booking SET 
				$edit_trace_doc_nr $edit_trace_doc_date $edit_trace_doc_publish $edit_trace_buyer_days
				ord_schedule_id='$ord_schedule_id'
			WHERE id_con_booking=$id_con_booking";

			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "send_order_trace_pdf":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			$id_con_booking = $_GET['id_con_booking'];
			
			$sql = "SELECT v_order.order_nr||'.'||v_order_schedule.order_ship_nr as file_name, 
				v_order.id_ord_order
				FROM v_order, v_order_schedule 
				WHERE v_order_schedule.ord_order_id=v_order.id_ord_order
				AND v_order_schedule.id_ord_schedule=$ord_schedule_id
			";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);
			
			$file_name = $row['file_name'];
			$id_ord_order = $row['id_ord_order'];
			
			$subject = 'Order Code ('.$file_name.') - Traceability Certificate Order';
			
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
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
						<td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
						<center data-parsed="" style="min-width:580px;width:100%"><table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;padding:0;text-align:center;vertical-align:top;width:580px">
					<tbody>
					
					<tr style="padding:0;text-align:left;vertical-align:top">
					<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					
					<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					</td></tr></tbody></table>
		
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
					<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tr>
						<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
							<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
						</td>
						<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
							iCRM.live Message from icollect.live Back Office:
						</td>
					</tr></table>
	
					<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					</td></tr></tbody></table>
			
					<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
					<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
							Order Code ('.$file_name.')<br/>
							'.$id_ord_order.'<br/>
							'.$ord_schedule_id.'<br/>
							'.$id_con_booking.'
						</p>
					</th></tr></tbody></table></th></tr></tbody></table></th>
					<th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0">
					</th></tr></tbody></table>
				
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						Before printing think about the ENVIRONMENT!<br>
						Warning: If you have received this email by error, please delete it and
						inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
					</p></th></tr></tbody></table></th></tr></tbody></table></td></tr></tbody></table></center></td></tr></tbody>
				</table></body>
			</html>';
			
			$sender="noreply@icollect.live";
			$recipient='croth53@gmail.com';
			
			$mail = new PHPMailer;
			$mail->isSMTP();
			// $mail->SMTPDebug = 2;
			// $mail->SMTPSecure = 'ssl';
			$mail->Debugoutput = 'html';
			$mail->Host = "d4i.maxapex.net";
			$mail->Port = 587;
			$mail->SMTPAuth = true;
			$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
			$mail->Username = ID_USER;
			$mail->Password = ID_PASS;
			$mail->setFrom('no-reply@icollect.live');
			$mail->AddCC('zoran.kuret@alfa24.ba');
			$mail->addAddress('croth53@gmail.com');
			$mail->Subject = $subject;
			$mail->msgHTML($message);
			$mail->AltBody = 'This is a plain-text message body';
			//send the message, check for errors
			if (!$mail->send()) {
				$dom='0##0';
			} else {
				$date = date_create();
				$timestamp = date_timestamp_get($date);
				
				$file = trim($file_name);
				$file_name2 = str_replace(' ', '_', $file);
			
				$doc_filename=$file_name2.'-80-'.$timestamp.'.pdf';
			
				$email_sender_company_id = $_SESSION['id_company'];
				$created_by = $_SESSION['id_user'];
				$id_owner = $_SESSION['id_contact'];
				$created_date = gmdate("Y/m/d H:i");
				
				$sql = "insert into ord_document (ord_order_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
				email_sender_id, email_sender_company_id, msg_recipients) 
				values ($id_ord_order, '$doc_filename', 80, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
				$created_by, $email_sender_company_id, '647, 648') RETURNING id_document";
				$result = pg_query($conn, $sql);
				
				if($result){
					$arr = pg_fetch_assoc($result);
				
					$id_document = $arr['id_document'];
					$user_id = $_SESSION['id_user'];
				
					$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
					VALUES ($id_document, $user_id, 1)";
					pg_query($conn, $sql2);



					$sql_date = " UPDATE ord_con_booking SET trace_certificate_request='$created_date' WHERE id_con_booking=$id_con_booking ";
					pg_query($conn, $sql_date);
					
					$dom='1##'.$doc_filename;
				} else {
					$dom='0##0';
				}
				
			}
		
		break;
		
		
		case "liste_stories":
		
			$cond="";
			if(!empty($_GET['media'])){
				$media=$_GET['media'];
				$cond .="AND story.media_type=$media";
			} else { $media=""; }
			
			if(!empty($_GET['country'])){
				$country=$_GET['country'];
				$cond .="AND story.id_country=$country";
			} else { $country=""; }
			
			$stories_table="";
			
			$sql="SELECT 	
				story.media_type, 
				story.media_link, 
				story.story_titleen AS title, 
				story.id_country, 
				story.id_story,
				country.name_country
				FROM 
					public.story, country
				WHERE 
					story.id_country = country.id_country	
					$cond
			ORDER BY id_country, id_story";
		
			$rs = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($rs)){
				if ($row['media_type'] == 2 ){
					$media = 'fa-file-image-o'; 					
				} else {
					$media = 'fa-video-camera';	
				}
		
				$stories_table.='<tr>
					<td><input type="radio" value="'. $row['id_story'] .'" id="storyManag_'. $row['id_story'] .'" name="storyElement" onchange="storySteps(\''. $row['id_story'] .'\');" class="radioBtnDefClass"></td>  
					<td><label style="font-weight:normal;" for="storyManag_'. $row['id_story'] .'">'.$row['id_story'].'</label></td>
					<td><label style="font-weight:normal;" for="storyManag_'. $row['id_story'] .'"><i class="fa '.$media.'" style="color:#1ab394"></i>&nbsp;&nbsp;'.$row['title'].'</label></td>
					<td><label style="font-weight:normal;" for="storyManag_'. $row['id_story'] .'">'.$row['name_country'].'</label></td>
					<td class="row_actions text-center">
						<a href="#" data-toggle="modal" class="st_edit_action hide" onclick="editStory('. $row['id_story'] .');" data-target="#modalStory"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
						<a href="javascript:deleteStory('. $row['id_story'] .');" class="st_delete_action hide" onclick="return confirm(\'Are you sure you want to delete this story:'. $row['title'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
					</td>
				</tr>';
			}
			
			$dom = $stories_table;
		
		break;
		
		
		case "liste_steps":
		
			$id_story=$_GET['id_story'];
			
			$sql_step = "SELECT id_storycon, id_story, seq_number, seq_mediatype, seq_texten, 
				seq_coordx, seq_coordy     
				FROM story_con  
				WHERE id_story = $id_story
				ORDER BY id_story, seq_number
			";  
			
			$steps_table="";
			$rs_step = pg_query($conn, $sql_step);
			while($row = pg_fetch_assoc($rs_step)){
				if ($row['seq_mediatype'] == 2 ){
					$media = 'fa-file-image-o'; 					
				} else {
					$media = 'fa-video-camera';	
				}
				
				if($row['seq_number']==1){
					$line="";
				} else {
					$line='<a href="#" class="hide st_edit_action" data-toggle="modal" onclick="stepLine('. $row['id_storycon'] .','.$id_story.');" data-target="#modalStepLine"><i class="fa fa-route" aria-hidden="true"></i></a>';
				}
		
				$steps_table.='<tr>
					<td>'.$row['seq_number'].'</td>
					<td><i class="fa '.$media.'" style="color:#1ab394"></i>&nbsp;&nbsp;'.$row['seq_texten'].'</td>
					<td>'.$row['seq_coordx'].'</td>
					<td>'.$row['seq_coordy'].'</td>
					<td class="row_actions text-center">
						<a href="#" data-toggle="modal" class="st_edit_action hide" onclick="editStep('. $row['id_storycon'] .','.$id_story.');" data-target="#modalStep"><i class="fa fa-pen-square" aria-hidden="true"></i></a>
						<a href="javascript:deleteStep('. $row['id_storycon'] .','.$id_story.');" class="st_delete_action hide" onclick="return confirm(\'Are you sure you want to delete this step:'. $row['seq_texten'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>
						'.$line.'
					</td>
				</tr>';
			}
			
			$dom=$steps_table;
		
		break;
		
		
		case "save_story":
		
			$conf = $_GET['conf'];
			$id_story = $_GET['id_story'];
			
			if(isset($_GET['story_titleen'])){
				// $story_titleen = $_GET['story_titleen'];
				$story_titleen = htmlspecialchars(pg_escape_string($_GET['story_titleen']));
				$field_story_titleen = "story_titleen,";
				$val_story_titleen = "'$story_titleen',";
				$req_story_titleen = " story_titleen='$story_titleen',";
			} else { $field_story_titleen = ""; $val_story_titleen = ""; $req_story_titleen = ""; }
			
			if(isset($_GET['story_titlede'])){
				// $story_titlede = $_GET['story_titlede'];
				$story_titlede = htmlspecialchars(pg_escape_string($_GET['story_titlede']));
				$field_story_titlede = "story_titlede,";
				$val_story_titlede = "'$story_titlede',";
				$req_story_titlede = " story_titlede='$story_titlede',";
			} else { $field_story_titlede = ""; $val_story_titlede = ""; $req_story_titlede = ""; }
			
			if(isset($_GET['story_titlefr'])){
				// $story_titlefr = $_GET['story_titlefr'];
				$story_titlefr = htmlspecialchars(pg_escape_string($_GET['story_titlefr']));
				$field_story_titlefr = "story_titlefr,";
				$val_story_titlefr = "'$story_titlefr',";
				$req_story_titlefr = " story_titlefr='$story_titlefr',";
			} else { $field_story_titlefr = ""; $val_story_titlefr = ""; $req_story_titlefr = ""; }
			
			if(isset($_GET['story_titlept'])){
				// $story_titlept = $_GET['story_titlept'];
				$story_titlept = htmlspecialchars(pg_escape_string($_GET['story_titlept']));
				$field_story_titlept = "story_titlept,";
				$val_story_titlept = "'$story_titlept',";
				$req_story_titlept = " story_titlept='$story_titlept',";
			} else { $field_story_titlept = ""; $val_story_titlept = ""; $req_story_titlept = ""; }
			
			if(isset($_GET['story_titlees'])){
				// $story_titlees = $_GET['story_titlees'];
				$story_titlees = htmlspecialchars(pg_escape_string($_GET['story_titlees']));
				$field_story_titlees = "story_titlees,";
				$val_story_titlees = "'$story_titlees',";
				$req_story_titlees = " story_titlees='$story_titlees',";
			} else { $field_story_titlees = ""; $val_story_titlees = ""; $req_story_titlees = ""; }
			
			if(isset($_GET['story_titleit'])){
				// $story_titleit = $_GET['story_titleit'];
				$story_titleit = htmlspecialchars(pg_escape_string($_GET['story_titleit']));
				$field_story_titleit = "story_titleit,";
				$val_story_titleit = "'$story_titleit',";
				$req_story_titleit = " story_titleit='$story_titleit',";
			} else { $field_story_titleit = ""; $val_story_titleit = ""; $req_story_titleit = ""; }
			
			if(isset($_GET['id_country'])){
				$id_country = $_GET['id_country'];
				$field_id_country = "id_country,";
				$val_id_country = "$id_country,";
				$req_id_country = " id_country='$id_country',";
			} else { $field_id_country = ""; $val_id_country = ""; $req_id_country = ""; }
			
			if(isset($_GET['id_exporter'])){
				$id_exporter = $_GET['id_exporter'];
				$field_id_exporter = "id_exporter,";
				$val_id_exporter = "$id_exporter,";
				$req_id_exporter = " id_exporter='$id_exporter',";
			} else { $field_id_exporter = ""; $val_id_exporter = ""; $req_id_exporter = ""; }
		
			if(isset($_GET['media_type'])){
				$media_type = $_GET['media_type'];
				$field_media_type = "media_type,";
				$val_media_type = "$media_type,";
				$req_media_type = " media_type='$media_type',";
			} else { $field_media_type = ""; $val_media_type = ""; $req_media_type = ""; }
		
			if(isset($_GET['media_link'])){
				if($media_type == 2) {
					$media_link = $_FILES['image']['name'];
				} else { $media_link = $_GET['media_link']; }
				$field_media_link = "media_link,";
				$val_media_link = "'$media_link',";
				$req_media_link = " media_link='$media_link',"; 
			} else { $field_media_link = ""; $val_media_link = ""; $req_media_link = ""; }
		
			$createdby = $_SESSION['id_contact'];
			$created_date = gmdate("Y-m-d H:i");
		
			if($conf == 'add'){
				$sql="INSERT INTO public.story($field_id_country $field_id_exporter $field_story_titleen $field_story_titlede 
					$field_story_titlefr $field_story_titlept $field_story_titlees $field_story_titleit 
					$field_media_type $field_media_link
					story_createdby, created_by, id_author, created_date
				 ) VALUES ($val_id_country $val_id_exporter $val_story_titleen $val_story_titlede 
					$val_story_titlefr $val_story_titlept $val_story_titlees $val_story_titleit
					$val_media_type $val_media_link
					$createdby, $createdby, $createdby, $created_date
				)";
				
			} else {
				$sql = "UPDATE public.story
					SET $req_id_country $req_id_exporter $req_story_titleen $req_story_titlede 
					$req_story_titlefr $req_story_titlept $req_story_titlees $req_story_titleit $req_media_type $req_media_link
					modified_by='$createdby', modified_date='$created_date'
				WHERE id_story=$id_story";
			} 
	
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "select_exporter":
		
			$id_country = $_GET['id_country'];
			if($id_country != 0){
				$cond=" WHERE id_country = $id_country";
			} else { $cond=""; }
			
			$sql="SELECT id_exporter, name_exporter FROM v_exporters $cond";
			$result = pg_query($conn, $sql);
			
			$exporter="<option value=''>Select an exporter</option>";
			while($arr = pg_fetch_assoc($result)){
				$exporter .= '<option value="'. $arr['id_exporter'] .'">'. $arr['name_exporter'] .'</option>';
			}
			
			$dom=$exporter;
		
		break;
		
		
		case "edit_story":
		
			$id_story = $_GET['id_story'];
			
			$sql="SELECT * FROM story WHERE id_story = $id_story";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			$dom=$arr['story_titleen'].'##'.$arr['story_titlede'].'##'.$arr['story_titlefr'].'##'.$arr['story_titlept'].'##'.$arr['story_titlees'].'##'.$arr['id_country'].'##'.$arr['media_type'].'##'.trim($arr['media_link']).'##'.$arr['id_exporter'].'##'.$arr['story_titleit'];
		
		break;
		
		
		case "delete_story":
		
			$id_story = $_GET['id_story'];
			
			$sql = "DELETE FROM story WHERE id_story=$id_story";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "save_step":
		
			$conf = $_GET['conf'];
			$id_story = $_GET['id_story'];
			$id_storycon = $_GET['id_storycon'];
			
			if(isset($_GET['seq_texten'])){
				// $seq_texten = $_GET['seq_texten'];
				$seq_texten = htmlspecialchars(pg_escape_string($_GET['seq_texten']));
				$field_seq_texten = "seq_texten,";
				$val_seq_texten = "'$seq_texten',";
				$req_seq_texten = " seq_texten='$seq_texten',";
			} else { $field_seq_texten = ""; $val_seq_texten = ""; $req_seq_texten = ""; }
			
			if(isset($_GET['seq_textde'])){
				// $seq_textde = $_GET['seq_textde'];
				$seq_textde = htmlspecialchars(pg_escape_string($_GET['seq_textde']));
				$field_seq_textde = "seq_textde,";
				$val_seq_textde = "'$seq_textde',";
				$req_seq_textde = " seq_textde='$seq_textde',";
			} else { $field_seq_textde = ""; $val_seq_textde = ""; $req_seq_textde = ""; }
			
			if(isset($_GET['seq_textfr'])){
				// $seq_textfr = $_GET['seq_textfr'];
				$seq_textfr = htmlspecialchars(pg_escape_string($_GET['seq_textfr']));
				$field_seq_textfr = "seq_textfr,";
				$val_seq_textfr = "'$seq_textfr',";
				$req_seq_textfr = " seq_textfr='$seq_textfr',";
			} else { $field_seq_textfr = ""; $val_seq_textfr = ""; $req_seq_textfr = ""; }
			
			if(isset($_GET['seq_textpt'])){
				// $seq_textpt = $_GET['seq_textpt'];
				$seq_textpt = htmlspecialchars(pg_escape_string($_GET['seq_textpt']));
				$field_seq_textpt = "seq_textpt,";
				$val_seq_textpt = "'$seq_textpt',";
				$req_seq_textpt = " seq_textpt='$seq_textpt',";
			} else { $field_seq_textpt = ""; $val_seq_textpt = ""; $req_seq_textpt = ""; }
			
			if(isset($_GET['seq_textes'])){
				// $seq_textes = $_GET['seq_textes'];
				$seq_textes = htmlspecialchars(pg_escape_string($_GET['seq_textes']));
				$field_seq_textes = "seq_textes,";
				$val_seq_textes = "'$seq_textes',";
				$req_seq_textes = " seq_textes='$seq_textes',";
			} else { $field_seq_textes = ""; $val_seq_textes = ""; $req_seq_textes = ""; }
			
			if(isset($_GET['seq_textit'])){
				// $seq_textit = $_GET['seq_textit'];
				$seq_textit = htmlspecialchars(pg_escape_string($_GET['seq_textit']));
				$field_seq_textit = "seq_textit,";
				$val_seq_textit = "'$seq_textit',";
				$req_seq_textit = " seq_textit='$seq_textit',";
			} else { $field_seq_textit = ""; $val_seq_textit = ""; $req_seq_textit = ""; }
			
			if(isset($_GET['seq_mediatype'])){
				$seq_mediatype = $_GET['seq_mediatype'];
				$field_seq_mediatype = "seq_mediatype,";
				$val_seq_mediatype = "'$seq_mediatype',";
				$req_seq_mediatype = " seq_mediatype='$seq_mediatype',";
			} else { $field_seq_mediatype = ""; $val_seq_mediatype = ""; $req_seq_mediatype = ""; }
			
			if(isset($_GET['seq_link'])){
				if($seq_mediatype == 2) {
					$seq_link = $_FILES['image']['name'];
				} else { $seq_link = $_GET['seq_link']; }
				$field_seq_link = "seq_link,";
				$val_seq_link = "'$seq_link',";
				$req_seq_link = " seq_link='$seq_link',"; 
			} else { $field_seq_link = ""; $val_seq_link = ""; $req_seq_link = ""; }
		
			if(isset($_GET['seq_number'])){
				$seq_number = $_GET['seq_number'];
				$field_seq_number = "seq_number,";
				$val_seq_number = "'$seq_number',";
				$req_seq_number = " seq_number='$seq_number',";
			} else { $field_seq_number = ""; $val_seq_number = ""; $req_seq_number = ""; }
		
			if(isset($_GET['seq_coordx'])){
				$seq_coordx = $_GET['seq_coordx'];
				$field_seq_coordx = "seq_coordx,";
				$val_seq_coordx = "'$seq_coordx',";
				$req_seq_coordx = " seq_coordx='$seq_coordx',";
			} else { $field_seq_coordx = ""; $val_seq_coordx = ""; $req_seq_coordx = ""; }
		
			if(isset($_GET['seq_coordy'])){
				$seq_coordy = $_GET['seq_coordy'];
				$field_seq_coordy = "seq_coordy,";
				$val_seq_coordy = "'$seq_coordy',";
				$req_seq_coordy = " seq_coordy='$seq_coordy',";
			} else { $field_seq_coordy = ""; $val_seq_coordy = ""; $req_seq_coordy = ""; }

			$createdby = $_SESSION['id_contact'];
			$created_date = gmdate("Y-m-d H:i");
		
			if($conf == 'add'){
				$sql="INSERT INTO public.story_con($field_seq_texten $field_seq_textde $field_seq_textfr $field_seq_textpt
					$field_seq_textes $field_seq_textit $field_seq_mediatype $field_seq_link $field_seq_number $field_seq_coordx
					$field_seq_coordy id_story
				 ) VALUES ($val_seq_texten $val_seq_textde $val_seq_textfr $val_seq_textpt
					$val_seq_textes $val_seq_textit $val_seq_mediatype $val_seq_link $val_seq_number $val_seq_coordx
					$val_seq_coordy $id_story
				)";
				
			} else {
				$sql = "UPDATE public.story_con
					SET $req_seq_texten $req_seq_textde $req_seq_textfr $req_seq_textpt
					$req_seq_textes $req_seq_textit $req_seq_mediatype $req_seq_link $req_seq_number $req_seq_coordx
					$req_seq_coordy seq_modified='$created_date'
				WHERE id_storycon=$id_storycon";
			} 
		
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "story_title_lastStep":
		
			$id_story = $_GET['id_story'];
		
			$sql="SELECT story_titleen AS title FROM public.story WHERE id_story = $id_story";
			$rs = pg_query($conn, $sql);
			$row = pg_fetch_assoc($rs);
			
			$title = $row['title'];
			
			
			$sql_step = "SELECT id_storycon, id_story, seq_number, seq_mediatype, seq_texten, 
				seq_coordx, seq_coordy     
				FROM story_con  
				WHERE id_story = $id_story
				ORDER BY seq_number DESC LIMIT 1
			";
			
			$rs_step = pg_query($conn, $sql_step);
			$row_step = pg_fetch_assoc($rs_step);
			
			$coordx = $row_step['seq_coordx'];
			$coordy = $row_step['seq_coordy'];
			
			$dom=$title.'##'.$coordx.'##'.$coordy;
			
		break;
		
		
		case "edit_step":
		
			$id_story = $_GET['id_story'];
			$id_storycon = $_GET['id_storycon'];
			
			$sql="SELECT * FROM story_con WHERE id_storycon = $id_storycon";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			
			// Last Step coords
			$sql_step = "SELECT seq_coordx, seq_coordy FROM story_con 
				WHERE id_storycon < $id_storycon AND id_story = $id_story 
			ORDER BY seq_number DESC LIMIT 1";
			
			$rs_step = pg_query($conn, $sql_step);
			$arr_step = pg_fetch_assoc($rs_step);
			if($arr_step){
				$lastx=$arr_step['seq_coordx'];
				$lasty=$arr_step['seq_coordy'];
			} else {
				$lastx="";
				$lasty="";
			}
			
			$dom=$arr['seq_texten'].'##'.$arr['seq_textde'].'##'.$arr['seq_textfr'].'##'.$arr['seq_textpt'].'##'.$arr['seq_textes'].'##'.$arr['seq_number'].'##'.$arr['seq_mediatype'].'##'.trim($arr['seq_link']).'##'.$arr['seq_coordx'].'##'.$arr['seq_coordy'].'##'.$lastx.'##'.$lasty.'##'.$arr['seq_textit'];
		
		break;
		
		
		case "delete_step":
		
			$id_storycon = $_GET['id_storycon'];
			
			$sql = "DELETE FROM story_con WHERE id_storycon=$id_storycon";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "stored_step_line":
		
			$id_story = $_GET['id_story'];
			$id_storycon = $_GET['id_storycon'];
			
			$sql="SELECT * FROM story_con WHERE id_storycon = $id_storycon";
			$result = pg_query($conn, $sql);
			$arr = pg_fetch_assoc($result);
			
			
			// Last Step coords
			$sql_step = "SELECT seq_coordx, seq_coordy FROM story_con 
				WHERE id_storycon < $id_storycon AND id_story = $id_story 
			ORDER BY seq_number DESC LIMIT 1";
			
			$rs_step = pg_query($conn, $sql_step);
			$arr_step = pg_fetch_assoc($rs_step);
			if($arr_step){
				$lastx=$arr_step['seq_coordx'];
				$lasty=$arr_step['seq_coordy'];
			} else {
				$lastx="";
				$lasty="";
			}
			
			$dom=$arr['seq_texten'].'##'.$arr['seq_number'].'##'.$arr['seq_coordx'].'##'.$arr['seq_coordy'].'##'.$lastx.'##'.$lasty.'##'.$arr['track_line'];
		
		break;
		
		
		case "save_step_line":
		
			$id_storycon = $_GET['id_storycon'];
			
			if(isset($_GET['newLine'])){
				$track_line = $_GET['newLine'];
				$req_track_line = " track_line='$track_line'";
				
				$sql = "UPDATE public.story_con  
					SET $req_track_line
				WHERE id_storycon=$id_storycon";
			
				$result = pg_query($conn, $sql);

				if ($result) {
					$dom=1;
				} else {
					$dom=0;
				}
			
			} else { $dom=0; }
			
		break;
		
		
		case "labAnalysis_dataEntry":
		
			$ord_schedule_id = $_GET['ord_schedule_id'];
			$id_con_booking = $_GET['id_con_booking'];
			$ord_order_id = $_GET['ord_order_id'];
			
			$sql = "SELECT v_order.order_nr||'.'||v_order_schedule.order_ship_nr as file_name, 
				v_order.id_ord_order
				FROM v_order, v_order_schedule 
				WHERE v_order_schedule.ord_order_id=v_order.id_ord_order
				AND v_order_schedule.id_ord_schedule=$ord_schedule_id
			";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);
			
			$file_name = $row['file_name'];
			
			$subject = 'Order Code ('.$file_name.') - Request live.loading certificate';
			
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
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
						<td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
						<center data-parsed="" style="min-width:580px;width:100%"><table align="center" class="container float-center" style="background:#fefefe;border-collapse:collapse;border-spacing:0;float:left;padding:0;text-align:center;vertical-align:top;width:580px">
					<tbody>
					
					<tr style="padding:0;text-align:left;vertical-align:top">
					<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					
					<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					</td></tr></tbody></table>
		
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:16px;padding-right:16px;text-align:left;width:564px">
					<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tr>
						<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;width:58px">
							<img src="https://icollect.live/crm/img/icrm_logo-57x57.png" alt="iCCRM-Logo" style="-ms-interpolation-mode:bicubic;outline:0;text-decoration:none;width:auto">
						</td>
						<td style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
							iCRM.live Message from icollect.live Back Office:
						</td>
					</tr></table>
	
					<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
					</td></tr></tbody></table>
			
					<table class="callout" style="Margin-bottom:16px;border-collapse:collapse;border-spacing:0;margin-bottom:16px;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th class="callout-inner secondary" style="Margin:0;background:#ebebeb;border:1px solid #444;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:10px;text-align:left;width:100%">
					<table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top">
					<th class="small-12 large-6 columns first" style="Margin:0 auto;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:16px;padding-left:0!important;padding-right:0!important;text-align:left;width:50%">
					<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
					<tbody><tr style="padding:0;text-align:left;vertical-align:top"><th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
						<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
							Order Code ('.$file_name.')<br/>
							'.$ord_order_id.'<br/>
							'.$ord_schedule_id.'<br/>
							'.$id_con_booking.'
						</p>
					</th></tr></tbody></table></th></tr></tbody></table></th>
					<th class="expander" style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0">
					</th></tr></tbody></table>
				
					<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0;text-align:left">
						Before printing think about the ENVIRONMENT!<br>
						Warning: If you have received this email by error, please delete it and
						inform the sender immediately. This message or attachments may contain information which is confidential, therefore its use, reproduction, distribution and/or publication are strictly forbidden. Thank you for your cooperation.
					</p></th></tr></tbody></table></th></tr></tbody></table></td></tr></tbody></table></center></td></tr></tbody>
				</table></body>
			</html>';
			
			$sender="noreply@icollect.live";
			$recipient='croth53@gmail.com';
			
			$mail = new PHPMailer;
			$mail->isSMTP();
			// $mail->SMTPDebug = 2;
			// $mail->SMTPSecure = 'ssl';
			$mail->Debugoutput = 'html';
			$mail->Host = "d4i.maxapex.net";
			$mail->Port = 587;
			$mail->SMTPAuth = true;
			$mail->MessageID = "<" . time() ."-" . md5($sender . $recipient) . "@icollect.live>";
			$mail->Username = ID_USER;
			$mail->Password = ID_PASS;
			$mail->setFrom('no-reply@icollect.live');
			$mail->AddCC('zoran.kuret@alfa24.ba');
			$mail->addAddress('croth53@gmail.com');
			$mail->Subject = $subject;
			$mail->msgHTML($message);
			$mail->AltBody = 'This is a plain-text message body';
			//send the message, check for errors
			if (!$mail->send()) {
				$dom='0##0';
			} else {
				$date = date_create();
				$timestamp = date_timestamp_get($date);
				
				$file = trim($file_name);
				$file_name2 = str_replace(' ', '_', $file);
			
				$doc_filename=$file_name2.'-80-'.$timestamp.'.pdf';
			
				$email_sender_company_id = $_SESSION['id_company'];
				$created_by = $_SESSION['id_user'];
				$id_owner = $_SESSION['id_contact'];
				$created_date = gmdate("Y/m/d H:i");
				
				$sql = "insert into ord_document (ord_order_id, doc_filename, doc_type_id, doc_date, document_desc, created_by, created_date, id_owner, user_id,
				email_sender_id, email_sender_company_id, msg_recipients) 
				values ($ord_order_id, '$doc_filename', 80, '$created_date', '$subject', $created_by, '$created_date', $id_owner, $created_by,
				$created_by, $email_sender_company_id, '647, 648') RETURNING id_document";
				$result = pg_query($conn, $sql);
				
				if($result){
					$arr = pg_fetch_assoc($result);
					
					$id_document = $arr['id_document'];
					$user_id = $_SESSION['id_user'];
					
					$sql2 = "INSERT INTO public.ord_document_msg_queue(ord_document_id, user_id, msg_view) 
					VALUES ($id_document, $user_id, 1)";
					pg_query($conn, $sql2);


					$sql_date = "UPDATE ord_con_booking SET loading_certificate_requested='$created_date' WHERE id_con_booking=$id_con_booking";
					pg_query($conn, $sql_date);
					
					$dom='1##'.$doc_filename.'##'.$created_date;
				} else {
					$dom='0##0';
				}
				
			}
		
		break;
		
		
		case "add_surcharge_to_marge":
		
			$id_proposal_calc = $_GET["id_proposal_calc"];
			$ship_sales_surcharge_amount = $_GET["ship_sales_surcharge_amount"];
			$ship_sales_surcharge = $_GET["ship_sales_surcharge"];
			$sales_mt = $_GET["sales_mt"];
			
			
			$sql_stats = "UPDATE public.ord_proposal_calc
			   SET ship_sales_surcharge_amount='$ship_sales_surcharge_amount', ship_sales_surcharge='$ship_sales_surcharge', 
			   ship_sales_value_tone='$sales_mt'
			WHERE id_proposal_calc=$id_proposal_calc";

			$result = pg_query($conn, $sql_stats) or die(pg_last_error());
			$count = pg_num_rows($result);

			if($count==0){
				
				$sql_form = "select oc.id_proposal_calc, 
					os.id_ord_schedule, oo.nr_shipments, 
					oo.id_ord_order, oo.pipeline_id,
					os.order_ship_nr
					from 
						ord_proposal_calc oc, ord_ocean_Schedule os, 
						ord_order oo 
						where oo.id_ord_order=os.ord_order_id 
						and os.id_ord_schedule=oc.ord_schedule_id 
				AND oc.id_proposal_calc=$id_proposal_calc";  
				
				$rst_form = pg_query($conn, $sql_form);
				$row_form = pg_fetch_assoc($rst_form);
				
				$id_ord_schedule = $row_form['id_ord_schedule'];
				$id_ord_order = $row_form['id_ord_order'];
				$pipeline_id = $row_form['pipeline_id'];
				$order_ship_nr = $row_form['order_ship_nr'];
				
				if($row_form['nr_shipments'] == $order_ship_nr){
					$last_shipment=1;
				} else {
					$last_shipment=0;
				}
			
				$dom='1##'.$id_proposal_calc.'##'.$id_ord_schedule.'##'.$last_shipment.'##'.$id_ord_order.'##'.$pipeline_id.'##'.$order_ship_nr;
			} else {
				$dom='0##0';
			}
			
		break;
	}

}


echo $dom;


?>
