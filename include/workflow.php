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
		
		case "wf_process_table":

			$editRight = $_GET['update_right'];
			$deleteRight = $_GET['delete_right'];
			
			$sql = "Select * from ord_wf_process ORDER BY id_process ASC";

			$list ='';
			$result = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($result)){

				$list .= '<tr>
					<td><input type="radio" value="'. $row['id_process'] .'" id="radioProcess'. $row['id_process'] .'" name="id_process_radio" onchange="triggerInProcess(\''. $row['id_process'] .'\',\''. $row['process_name'] .'\');" class="radioBtnWfProcess"></td>
					<td><label class="no-padding no-margins" for="radioProcess'. $row['id_process'] .'">'. $row['process_name'] .'</label></td>
					<td class="row_actions">';
					
						if($editRight == 1){
							$list .= '<a href="#" data-toggle="modal" onclick="wfProcessManagement(\'show\',\''. $row['id_process'] .'\');" data-target="#modalWfProcess"><i class="fa fa-pen-square" aria-hidden="true"></i></a> ';
						}
						
						if($deleteRight == 1){
							$list .= ' <a href="javascript:wfProcessManagement(\'del\',\''. $row['id_process'] .'\');" onclick="return confirm(\'Are you sure you want to delete '. $row['process_name'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
						}

				    $list .= '</td>
				</tr>';
			}

			$dom = $list;

		break;
		
		
		case "delete_wf_process":

			$id_process = $_GET['id_process'];

			$sql = "delete from ord_wf_process where id_process=$id_process";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		case "show_wf_process":
		
			$id_process = $_GET['id_process'];
		
			$sql = "SELECT process_name, ord_order_id, ord_schedule_id FROM ord_wf_process WHERE id_process=$id_process";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);

			$dom=$row['process_name'].'#'.$row['ord_order_id'].'#'.$row['ord_schedule_id'];
			
		break;
		
		
		case "wf_process_management":
		
			if(isset($_GET["process_name"])){
				$process_name = $_GET["process_name"];
			} else { $process_name = ""; }
	
			if(isset($_GET["ord_order_id"])){
				$ord_order_id = $_GET["ord_order_id"];
				$ord_order_id_edit = " ord_order_id = '$ord_order_id',";
				$ord_order_id_field = " ord_order_id,";
				$ord_order_id_val = " '$ord_order_id',";
			} else { $ord_order_id = ""; $ord_order_id_edit = ""; $ord_order_id_field = ""; $ord_order_id_val = ""; }
			
			if(isset($_GET["ord_schedule_id"])){
				$ord_schedule_id = $_GET["ord_schedule_id"];
				$ord_schedule_id_edit = " ord_schedule_id = '$ord_schedule_id',";
				$ord_schedule_id_field = " ord_schedule_id,";
				$ord_schedule_id_val = " '$ord_schedule_id',";
			} else { $ord_schedule_id = ""; $ord_schedule_id_edit = ""; $ord_schedule_id_field = ""; $ord_schedule_id_val = ""; }
			
			
			if(isset($_GET["id_process"])){
				$id_process = $_GET["id_process"];
			} else { $id_process = ""; }
	
			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.ord_wf_process ($ord_schedule_id_field  $ord_order_id_field process_name) 
				VALUES ($ord_schedule_id_val $ord_order_id_val '$process_name');";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.ord_wf_process SET $ord_schedule_id_edit $ord_order_id_edit
					process_name = '$process_name'
				WHERE id_process = $id_process";
				
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "wf_trigger_in_process_table":
		
			$id_process = $_GET['id_process'];
		
			$sql = "select * from ord_wf_trigger where process_id=$id_process";

			$list ='';
			$result = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($result)){
				$list .= '<tr>
					<td>'. $row['id_trigger'] .'</td>
					<td>'. $row['trigger_name'] .'</td>
					<td>'. $row['sequence_nr'] .'</td>
				</tr>';
			}

			$dom=$list;
		
		break;
		
		
		case "wf_trigger_table":

			$value = $_GET['value'];
			$editRight = $_GET['update_right'];
			$deleteRight = $_GET['delete_right'];
			
			$conf="";
			if($value!=0){
				$conf=" WHERE process_id=$value";
			}
			
			$sql = "Select * from ord_wf_trigger $conf ORDER BY id_trigger ASC";

			$list ='';
			$result = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($result)){

				$list .= '<tr>
					<td>'. $row['id_trigger'] .'</td>
					<td>'. $row['trigger_name'] .'</td>
					<td class="row_actions">';
					
						if($editRight == 1){
							$list .= '<a href="#" data-toggle="modal" onclick="wfTriggerManagement(\'show\',\''. $row['id_trigger'] .'\');" data-target="#modalWfTrigger"><i class="fa fa-pen-square" aria-hidden="true"></i></a> ';
						}
						
						if($deleteRight == 1){
							$list .= ' <a href="javascript:wfTriggerManagement(\'del\',\''. $row['id_trigger'] .'\');" onclick="return confirm(\'Are you sure you want to delete '. $row['trigger_name'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
						}

				    $list .= '</td>
				</tr>';
			}

			$dom = $list;

		break;
		
		
		case "wf_trigger_management":
		
			if(isset($_GET["trigger_name"])){
				$trigger_name = $_GET["trigger_name"];
			} else { $trigger_name = ""; }
			
			if(isset($_GET["id_process"])){
				$id_process = $_GET["id_process"];
			} else { $id_process = ""; }
	
			if(isset($_GET["sequence_nr"])){
				$sequence_nr = $_GET["sequence_nr"];
				$sequence_nr_edit = " sequence_nr = '$sequence_nr',";
				$sequence_nr_field = " sequence_nr,";
				$sequence_nr_val = " '$sequence_nr',";
			} else { $sequence_nr = ""; $sequence_nr_edit = ""; $sequence_nr_field = ""; $sequence_nr_val = ""; }
			
	
			
			if(isset($_GET["id_trigger"])){
				$id_trigger = $_GET["id_trigger"];
			} else { $id_trigger = ""; }
	
			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.ord_wf_trigger ($sequence_nr_field trigger_name, process_id) 
				VALUES ($sequence_nr_val '$trigger_name', '$id_process');";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.ord_wf_trigger SET $sequence_nr_edit 
					trigger_name = '$trigger_name', process_id = '$id_process'
				WHERE id_trigger = $id_trigger";
				
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "delete_wf_trigger":

			$id_trigger = $_GET['id_trigger'];

			$sql = "delete from ord_wf_trigger where id_trigger=$id_trigger";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		case "show_wf_trigger":
		
			$id_trigger = $_GET['id_trigger'];
		
			$sql = "SELECT * FROM ord_wf_trigger WHERE id_trigger=$id_trigger";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);

			$dom=$row['trigger_name'].'#'.$row['id_process'].'#'.$row['sequence_nr'];
			
		break;
		
		
		case "wf_group_table":
		
			$editRight = $_GET['update_right'];
			$deleteRight = $_GET['delete_right'];
			
			$sql = "Select * from ord_wf_msg_groups ORDER BY id_msg_group ASC";

			$list ='';
			$result = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($result)){

				$list .= '<tr>
					<td><input type="radio" value="'. $row['id_msg_group'] .'##'. $row['group_name'] .'" id="radioGroup'. $row['id_msg_group'] .'" name="id_group_radio" onchange="userInGroup(\''. $row['id_msg_group'] .'\',\''. $row['group_name'] .'\');" class="radioBtnGroupClass"></td>
					<td><label class="no-padding no-margins" for="radioGroup'. $row['id_msg_group'] .'">'. $row['group_name'] .'</label></td>
					<td class="row_actions">';
					
						if($editRight == 1){
							$list .= '<a href="#" data-toggle="modal" onclick="wfGroupManagement(\'show\',\''. $row['id_msg_group'] .'\');" data-target="#modalWfGroup"><i class="fa fa-pen-square" aria-hidden="true"></i></a> ';
						}
						
						if($deleteRight == 1){
							$list .= ' <a href="javascript:wfGroupManagement(\'del\',\''. $row['id_msg_group'] .'\');" onclick="return confirm(\'Are you sure you want to delete '. $row['group_name'] .' ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
						}

				    $list .= '</td>
				</tr>';
			}

			$dom = $list;
		
		break;
		
		
		case "wf_users_list":
		
			$cond="";
			$group_id = $_GET['group_id'];
			if($group_id!=""){
				$cond=" and c.id_contact not in ( select id_contact from ord_wf_msg_members where group_id = $group_id ) ";
			}
			
			$editRight = $_GET['update_right'];
			if($editRight ==1){ $edit=""; } else { $edit="hide"; }
			
			$sql_list="select c.id_contact, c.name, s.id_company, s.company_name, c.p_email, c.p_phone, c.skype_id
				from v_Security_new s, contact c
			where c.id_contact=s.id_contact $cond 
			ORDER BY c.name";
		
			$users_list ='';
			$result_list = pg_query($conn, $sql_list);
			while($row_users = pg_fetch_assoc($result_list)){
				$users_list .= '<tr>
					<td class="row_actions">
						<a href="#" class="'.$edit.'" onclick="addUserToGroup_wf(\''. $row_users['id_contact'] .'\',);"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
					</td>
					<td>'. $row_users['name'] .'</td>
					<td>'. $row_users['company_name'] .'</td>
				</tr>';
			}
			
			$dom=$users_list;
		
		break;
		
		
		case "wf_users_in_group_table":
		
			$group_id = $_GET['group_id'];
			
			$editRight = $_GET['update_right'];
			if($editRight ==1){ $edit=""; } else { $edit="hide"; }
			
			$sql_list="select contact_name, company_name, id_contact from ord_wf_msg_members where group_id=$group_id";
		
			$users_list ='';
			$result_list = pg_query($conn, $sql_list);
			while($row_users = pg_fetch_assoc($result_list)){
				$users_list .= '<tr>
					<td>'. $row_users['contact_name'] .'</td>
					<td>'. $row_users['company_name'] .'</td>
					<td class="row_actions">
						<a href="#" class="'.$edit.'" onclick="removeUserFromGroup_wf(\''. $row_users['id_contact'] .'\',);"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
					</td>
				</tr>';
			}
			
			$dom=$users_list;
		
		break;
		
		
		case "wf_group_management":
		
			if(isset($_GET["group_name"])){
				$group_name = $_GET["group_name"];
			} else { $group_name = ""; }
			
			if(isset($_GET["id_msg_group"])){
				$id_msg_group = $_GET["id_msg_group"];
			} else { $id_msg_group = ""; }
	
			$conf = $_GET["conf"];
			
			if($conf == 'add'){
				$sql = "INSERT INTO public.ord_wf_msg_groups (group_name) VALUES ('$group_name');";
				
			} else
			if($conf == 'edit'){
				$sql = "UPDATE public.ord_wf_msg_groups SET group_name = '$group_name' WHERE id_msg_group = $id_msg_group";
				
			} else {}
			
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "show_wf_group":
		
			$id_msg_group = $_GET['id_msg_group'];
		
			$sql = "SELECT * FROM ord_wf_msg_groups WHERE id_msg_group=$id_msg_group";
			$result = pg_query($conn, $sql);
			$row = pg_fetch_assoc($result);

			$dom=$row['group_name'].'#';
		
		break;
		
		
		case "delete_wf_group":

			$id_msg_group = $_GET['id_msg_group'];

			$sql = "delete from ord_wf_msg_groups where id_msg_group=$id_msg_group";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}

		break;
		
		
		case "remove_user_from_group_wf":
		
			$id_contact = $_GET['id_contact'];

			$sql = "delete from ord_wf_msg_members where id_contact=$id_contact";
			$result = pg_query($conn, $sql);

			if ($result) {
				$dom=1;
			} else {
				$dom=0;
			}
		
		break;
		
		
		case "add_user_to_group_wf":
		
			$id_contact = $_GET['id_contact'];
			$group_id = $_GET['group_id'];
			
			$sql = "INSERT INTO public.ord_wf_msg_members (
				group_id, id_contact, contact_name, id_company, company_name, contact_email, contact_phone, contact_skype
			) SELECT '$group_id', c.id_contact, c.name, s.id_company, s.company_name, c.p_email, c.p_phone, c.skype_id
				FROM v_Security_new s, contact c
			WHERE c.id_contact=s.id_contact AND s.id_contact = $id_contact";
			
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