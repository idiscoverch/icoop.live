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
		
		case "all_charts":
		
			$id_primary_company = $_SESSION["id_primary_company"];
			$id_supchain_type = $_SESSION["id_supchain_type"];
			$id_company = $_SESSION['id_company'];
			$id_cooperative = $_SESSION['id_cooperative'];
			
			$new = 0;
			$review = 0;
			$approved = 0;
			$contracted = 0;
			$sensibilisation = 0;
			$audit = 0;
			$certified = 0;
			$tt_farmers = 0;
			
			$ggap_cand = 0;
			$ggap_appr = 0;
			$ggap_cert = 0;
			$rspo_cand = 0;
			$rspo_appr = 0;
			$rspo_cert = 0;
			$bioue_cand = 0;
			$bioue_appr = 0;
			$bioue_cert = 0;
			$bioss_cand = 0;
			$bioss_appr = 0;
			$bioss_cert = 0;
			$ftrad_cand = 0;
			$ftrad_appr = 0;
			$ftrad_cert = 0;
			
			$gender_men = 0;
			$gender_women = 0;
			$gender_farmers = 0;
			
			$plantation = 0;
			$mapped = 0;
			$point = 0;
			
			$surf_project = "";
			
			if((isset($_GET["id_cooperative"])) && ($_GET["id_cooperative"]!=0)) {
				
				$selected_cooperative = $_GET["id_cooperative"];
				
				// New
				$sql_stNew = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and mobile_Created is not null
				and p.cooperative_id=$selected_cooperative ) 
				and mobile_created=643 ";
				$result_stNew = pg_query($conn, $sql_stNew);
				$row_stNew = pg_fetch_assoc($result_stNew);
				$new = $row_stNew['count'];
				
				// Review
				$sql_stReview = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and mobile_Created is not null
				and p.cooperative_id=$selected_cooperative ) 
				and mobile_created=644 ";
				$result_stReview = pg_query($conn, $sql_stReview);
				$row_stReview = pg_fetch_assoc($result_stReview);
				$review = $row_stReview['count'];
				
				// Approved
				$sql_stApproved = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and mobile_Created is not null
				and p.cooperative_id=$selected_cooperative ) 
				and mobile_created=645 ";
				$result_stApproved = pg_query($conn, $sql_stApproved);
				$row_stApproved = pg_fetch_assoc($result_stApproved);
				$approved = $row_stApproved['count'];
				
				// Contracted
				$sql_stContracted = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and mobile_Created is not null
				and p.cooperative_id=$selected_cooperative ) 
				and mobile_created=663 ";
				$result_stContracted = pg_query($conn, $sql_stContracted);
				$row_stContracted = pg_fetch_assoc($result_stContracted);
				$contracted = $row_stContracted['count'];
				
				// Sensibilisation
				$sql_stSensibilisation = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and mobile_Created is not null
				and p.cooperative_id=$selected_cooperative ) 
				and mobile_created=664 ";
				$result_stSensibilisation = pg_query($conn, $sql_stSensibilisation);
				$row_stSensibilisation = pg_fetch_assoc($result_stSensibilisation);
				$sensibilisation = $row_stSensibilisation['count'];
				
				// Audit ready
				$sql_stAudit = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and mobile_Created is not null
				and p.cooperative_id=$selected_cooperative ) 
				and mobile_created=665 ";
				$result_stAudit = pg_query($conn, $sql_stAudit);
				$row_stAudit = pg_fetch_assoc($result_stAudit);
				$audit = $row_stAudit['count'];
				
				// Certified
				$sql_stCertified = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and mobile_Created is not null
				and p.cooperative_id=$selected_cooperative ) 
				and mobile_created=666 ";
				$result_stCertified = pg_query($conn, $sql_stCertified);
				$row_stCertified = pg_fetch_assoc($result_stCertified);
				$certified = $row_stCertified['count'];
				
				// tt_farmers
				$sql_tt_farmers = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and mobile_Created is not null";
				$result_tt_farmers = pg_query($conn, $sql_tt_farmers);
				$row_tt_farmers = pg_fetch_assoc($result_tt_farmers);
				$tt_farmers = $row_tt_farmers['count'];
				$gender_farmers = $row_tt_farmers['count'];
				
				// globalGap
				
				$sql_ggap_cand = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and globalgap=511";
				$result_ggap_cand = pg_query($conn, $sql_ggap_cand);
				$row_ggap_cand = pg_fetch_assoc($result_ggap_cand);
				$ggap_cand = $row_ggap_cand['count'];
				
				$sql_ggap_appr = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and globalgap=512 ";
				$result_ggap_appr = pg_query($conn, $sql_ggap_appr);
				$row_ggap_appr = pg_fetch_assoc($result_ggap_appr);
				$ggap_appr = $row_ggap_appr['count'];
				
				$sql_ggap_cert = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and globalgap=650 ";
				$result_ggap_cert = pg_query($conn, $sql_ggap_cert);
				$row_ggap_cert = pg_fetch_assoc($result_ggap_cert);
				$ggap_cert = $row_ggap_cert['count'];
			
				// RSPO
				
				$sql_rspo_cand = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and rspo=511 ";
				$result_rspo_cand = pg_query($conn, $sql_rspo_cand);
				$row_rspo_cand = pg_fetch_assoc($result_rspo_cand);
				$rspo_cand = $row_rspo_cand['count'];
				
				$sql_rspo_appr = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and rspo=512 ";
				$result_rspo_appr = pg_query($conn, $sql_rspo_appr);
				$row_rspo_appr = pg_fetch_assoc($result_rspo_appr);
				$rspo_appr = $row_rspo_appr['count'];
				
				$sql_rspo_cert = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and rspo=650 ";
				$result_rspo_cert = pg_query($conn, $sql_rspo_cert);
				$row_rspo_cert = pg_fetch_assoc($result_rspo_cert);
				$rspo_cert = $row_rspo_cert['count'];
				
				// BIO UE
				
				$sql_bioue_cand = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and bio=511 ";
				$result_bioue_cand = pg_query($conn, $sql_bioue_cand);
				$row_bioue_cand = pg_fetch_assoc($result_bioue_cand);
				$bioue_cand = $row_bioue_cand['count'];
				
				$sql_bioue_appr = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and	bio=512 ";
				$result_bioue_appr = pg_query($conn, $sql_bioue_appr);
				$row_bioue_appr = pg_fetch_assoc($result_bioue_appr);
				$bioue_appr = $row_bioue_appr['count'];
				
				$sql_bioue_cert = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and bio=650 ";
				$result_bioue_cert = pg_query($conn, $sql_bioue_cert);
				$row_bioue_cert = pg_fetch_assoc($result_bioue_cert);
				$bioue_cert = $row_bioue_cert['count'];
				
				// BIO SUISSE
				
				$sql_bioss_cand = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and bio_suisse=511 ";
				$result_bioss_cand = pg_query($conn, $sql_bioss_cand);
				$row_bioss_cand = pg_fetch_assoc($result_bioss_cand);
				$bioss_cand = $row_bioss_cand['count'];
				
				$sql_bioue_appr = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and bio_suisse=512 ";
				$result_bioue_appr = pg_query($conn, $sql_bioue_appr);
				$row_bioue_appr = pg_fetch_assoc($result_bioue_appr);
				$bioue_appr = $row_bioue_appr['count'];
				
				$sql_bioss_cert = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and bio_suisse=650 ";
				$result_bioss_cert = pg_query($conn, $sql_bioss_cert);
				$row_bioss_cert = pg_fetch_assoc($result_bioss_cert);
				$bioss_cert = $row_bioss_cert['count'];
				
				// Fair Trade
				
				$sql_ftrad_cand = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and fair_trade=511 ";
				$result_ftrad_cand = pg_query($conn, $sql_ftrad_cand);
				$row_ftrad_cand = pg_fetch_assoc($result_ftrad_cand);
				$ftrad_cand = $row_ftrad_cand['count'];
				
				$sql_ftrad_appr = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and fair_trade=512 ";
				$result_ftrad_appr = pg_query($conn, $sql_ftrad_appr);
				$row_ftrad_appr = pg_fetch_assoc($result_ftrad_appr);
				$ftrad_appr = $row_ftrad_appr['count'];
				
				$sql_ftrad_cert = "select count(*) from plantation
				where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and fair_trade=650 ";
				$result_ftrad_cert = pg_query($conn, $sql_ftrad_cert);
				$row_ftrad_cert = pg_fetch_assoc($result_ftrad_cert);
				$ftrad_cert = $row_ftrad_cert['count'];
				
				
				// Plantation
				$sql_plantation = "select count(*) from plantation where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and  p.cooperative_id=$selected_cooperative ) ";
				$result_plantation = pg_query($conn, $sql_plantation);
				$row_plantation = pg_fetch_assoc($result_plantation);
				$plantation = $row_plantation['count'];
				
				// Mapped
				$sql_mapped = "select count(*) from plantation where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and  p.cooperative_id=$selected_cooperative )
				and geom_json is not null ";
				$result_mapped = pg_query($conn, $sql_mapped);
				$row_mapped = pg_fetch_assoc($result_mapped);
				$mapped = $row_mapped['count'];
				
				// Point
				$sql_point = "select count(*) from plantation where plantation.coordx IS NOT NULL and id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and  p.cooperative_id=$selected_cooperative ) ";
				$result_point = pg_query($conn, $sql_point);
				$row_point = pg_fetch_assoc($result_point);
				$point = $row_point['count'];
				
				// Gender
				
				$sql_gender_men = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and mobile_Created is not null
				and id_gender=213 ";
				$result_gender_men = pg_query($conn, $sql_gender_men);
				$row_gender_men = pg_fetch_assoc($result_gender_men);
				$gender_men = $row_gender_men['count'];
				
				$sql_gender_women = "select count(*) from contact where id_contact in (
				select distinct pm.contact_id from project_members pm, project p
				where p.id_project=pm.project_id
				and p.cooperative_id=$selected_cooperative )
				and mobile_Created is not null
				and id_gender=214 ";
				$result_gender_women = pg_query($conn, $sql_gender_women);
				$row_gender_women = pg_fetch_assoc($result_gender_women);
				$gender_women = $row_gender_women['count'];
				
				// Surface by Project
				
				$sql_srfPjt = "SELECT 
					pr.project_name,
					SUM(p.area_acres) AS acres,
					SUM(p.area) AS m2,
					SUM(p.surface_ha) AS ha
				  FROM project_members pm,
					project_task pt,
					v_plantation p,
					project pr
				  WHERE pm.task_id = pt.id_task 
					AND p.gid_plantation = pm.plantation_id 
					AND pr.id_project = pt.id_project 
					AND p.surface_ha IS NOT NULL
					AND pr.cooperative_id=$selected_cooperative
				  GROUP By pr.project_name
				  order by pr.project_name";
				$result_srfPjt = pg_query($conn, $sql_srfPjt);
				
				while($row_srfPjt = pg_fetch_assoc($result_srfPjt)) {
					$surf_project .= $row_srfPjt['project_name'].'##'.$row_srfPjt['acres'].'##'.$row_srfPjt['m2'].'##'.$row_srfPjt['ha'].'**';
				}
				
			} else {
				if(($id_supchain_type == 114) OR ($id_supchain_type == 113)) {
					
					if(($id_primary_company == 636) OR ($id_primary_company == 15065)) {
						
						if($id_primary_company == 636){
							if((isset($_GET["id_headquarter"])) && ($_GET["id_headquarter"]!=0)) {
								$selected_headquarter = $_GET["id_headquarter"];
								$cond = " and p.id_company=$selected_headquarter ";
								$cond_surf = " AND pr.id_company=$selected_headquarter";
							} else {
								$cond = " and (p.id_company=645 or p.id_company=646 or p.id_company=647) ";
								$cond_surf = " AND (pr.id_company=645 or pr.id_company=646 or pr.id_company=647)";
							}
							
						} else {
							$cond = " and p.id_company=15064";
							$cond_surf = " AND pr.id_company=15064";
						}
						
						// New
						$sql_stNew = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						$cond ) and mobile_created=643 ";
						$result_stNew = pg_query($conn, $sql_stNew);
						$row_stNew = pg_fetch_assoc($result_stNew);
						$new = $row_stNew['count'];
						
						// Review
						$sql_stReview = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						$cond ) and mobile_created=644 ";
						$result_stReview = pg_query($conn, $sql_stReview);
						$row_stReview = pg_fetch_assoc($result_stReview);
						$review = $row_stReview['count'];
						
						// Approved
						$sql_stApproved = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						$cond ) and mobile_created=645 ";
						$result_stApproved = pg_query($conn, $sql_stApproved);
						$row_stApproved = pg_fetch_assoc($result_stApproved);
						$approved = $row_stApproved['count'];
						
						// Contracted
						$sql_stContracted = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						$cond ) and mobile_created=663 ";
						$result_stContracted = pg_query($conn, $sql_stContracted);
						$row_stContracted = pg_fetch_assoc($result_stContracted);
						$contracted = $row_stContracted['count'];
						
						// Sensibilisation
						$sql_stSensibilisation = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						$cond ) and mobile_created=664 ";
						$result_stSensibilisation = pg_query($conn, $sql_stSensibilisation);
						$row_stSensibilisation = pg_fetch_assoc($result_stSensibilisation);
						$sensibilisation = $row_stSensibilisation['count'];
						
						// Audit ready
						$sql_stAudit = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						$cond ) and mobile_created=665 ";
						$result_stAudit = pg_query($conn, $sql_stAudit);
						$row_stAudit = pg_fetch_assoc($result_stAudit);
						$audit = $row_stAudit['count'];
						
						// Certified
						$sql_stCertified = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						$cond ) and mobile_created=666 ";
						$result_stCertified = pg_query($conn, $sql_stCertified);
						$row_stCertified = pg_fetch_assoc($result_stCertified);
						$certified = $row_stCertified['count'];
						
						// tt_farmers
						$sql_tt_farmers = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and mobile_Created is not null";
						$result_tt_farmers = pg_query($conn, $sql_tt_farmers);
						$row_tt_farmers = pg_fetch_assoc($result_tt_farmers);
						$tt_farmers = $row_tt_farmers['count'];
						$gender_farmers = $row_tt_farmers['count'];
						
						// globalGap
						
						$sql_ggap_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and globalgap=511";
						$result_ggap_cand = pg_query($conn, $sql_ggap_cand);
						$row_ggap_cand = pg_fetch_assoc($result_ggap_cand);
						$ggap_cand = $row_ggap_cand['count'];
						
						$sql_ggap_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and globalgap=512 ";
						$result_ggap_appr = pg_query($conn, $sql_ggap_appr);
						$row_ggap_appr = pg_fetch_assoc($result_ggap_appr);
						$ggap_appr = $row_ggap_appr['count'];
						
						$sql_ggap_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and globalgap=650 ";
						$result_ggap_cert = pg_query($conn, $sql_ggap_cert);
						$row_ggap_cert = pg_fetch_assoc($result_ggap_cert);
						$ggap_cert = $row_ggap_cert['count'];
					
						// RSPO
						
						$sql_rspo_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and rspo=511 ";
						$result_rspo_cand = pg_query($conn, $sql_rspo_cand);
						$row_rspo_cand = pg_fetch_assoc($result_rspo_cand);
						$rspo_cand = $row_rspo_cand['count'];
						
						$sql_rspo_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and rspo=512 ";
						$result_rspo_appr = pg_query($conn, $sql_rspo_appr);
						$row_rspo_appr = pg_fetch_assoc($result_rspo_appr);
						$rspo_appr = $row_rspo_appr['count'];
						
						$sql_rspo_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and rspo=650 ";
						$result_rspo_cert = pg_query($conn, $sql_rspo_cert);
						$row_rspo_cert = pg_fetch_assoc($result_rspo_cert);
						$rspo_cert = $row_rspo_cert['count'];
						
						// BIO UE
						
						$sql_bioue_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and bio=511 ";
						$result_bioue_cand = pg_query($conn, $sql_bioue_cand);
						$row_bioue_cand = pg_fetch_assoc($result_bioue_cand);
						$bioue_cand = $row_bioue_cand['count'];
						
						$sql_bioue_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and	bio=512 ";
						$result_bioue_appr = pg_query($conn, $sql_bioue_appr);
						$row_bioue_appr = pg_fetch_assoc($result_bioue_appr);
						$bioue_appr = $row_bioue_appr['count'];
						
						$sql_bioue_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and bio=650 ";
						$result_bioue_cert = pg_query($conn, $sql_bioue_cert);
						$row_bioue_cert = pg_fetch_assoc($result_bioue_cert);
						$bioue_cert = $row_bioue_cert['count'];
						
						// BIO SUISSE
						
						$sql_bioss_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and bio_suisse=511 ";
						$result_bioss_cand = pg_query($conn, $sql_bioss_cand);
						$row_bioss_cand = pg_fetch_assoc($result_bioss_cand);
						$bioss_cand = $row_bioss_cand['count'];
						
						$sql_bioue_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and bio_suisse=512 ";
						$result_bioue_appr = pg_query($conn, $sql_bioue_appr);
						$row_bioue_appr = pg_fetch_assoc($result_bioue_appr);
						$bioue_appr = $row_bioue_appr['count'];
						
						$sql_bioss_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and bio_suisse=650 ";
						$result_bioss_cert = pg_query($conn, $sql_bioss_cert);
						$row_bioss_cert = pg_fetch_assoc($result_bioss_cert);
						$bioss_cert = $row_bioss_cert['count'];
						
						// Fair Trade
						
						$sql_ftrad_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and fair_trade=511 ";
						$result_ftrad_cand = pg_query($conn, $sql_ftrad_cand);
						$row_ftrad_cand = pg_fetch_assoc($result_ftrad_cand);
						$ftrad_cand = $row_ftrad_cand['count'];
						
						$sql_ftrad_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and fair_trade=512 ";
						$result_ftrad_appr = pg_query($conn, $sql_ftrad_appr);
						$row_ftrad_appr = pg_fetch_assoc($result_ftrad_appr);
						$ftrad_appr = $row_ftrad_appr['count'];
						
						$sql_ftrad_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and fair_trade=650 ";
						$result_ftrad_cert = pg_query($conn, $sql_ftrad_cert);
						$row_ftrad_cert = pg_fetch_assoc($result_ftrad_cert);
						$ftrad_cert = $row_ftrad_cert['count'];
						
						// Plantation
						$sql_plantation = "select 'Plantation' as Status, count(*) from plantation where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) ";
						$result_plantation = pg_query($conn, $sql_plantation);
						$row_plantation = pg_fetch_assoc($result_plantation);
						$plantation = $row_plantation['count'];
						
						// Mapped
						$sql_mapped = "select count(*) from plantation where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) and geom_json is not null ";
						$result_mapped = pg_query($conn, $sql_mapped);
						$row_mapped = pg_fetch_assoc($result_mapped);
						$mapped = $row_mapped['count'];
					
						// Point
						$sql_point = "select count(*) from plantation where plantation.coordx IS NOT NULL and id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond ) ";
						$result_point = pg_query($conn, $sql_point);
						$row_point = pg_fetch_assoc($result_point);
						$point = $row_point['count'];
						
						
						// Gender
						
						$sql_gender_men = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond )
						and mobile_Created is not null
						and id_gender=213 ";
						$result_gender_men = pg_query($conn, $sql_gender_men);
						$row_gender_men = pg_fetch_assoc($result_gender_men);
						$gender_men = $row_gender_men['count'];
						
						$sql_gender_women = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						$cond )
						and mobile_Created is not null
						and id_gender=214 ";
						$result_gender_women = pg_query($conn, $sql_gender_women);
						$row_gender_women = pg_fetch_assoc($result_gender_women);
						$gender_women = $row_gender_women['count'];
						
						// Surface by Project
				
						$sql_srfPjt = "SELECT 
							pr.project_name,
							SUM(p.area_acres) AS acres,
							SUM(p.area) AS m2,
							SUM(p.surface_ha) AS ha
						  FROM project_members pm,
							project_task pt,
							v_plantation p,
							project pr
						  WHERE pm.task_id = pt.id_task 
							AND p.gid_plantation = pm.plantation_id 
							AND pr.id_project = pt.id_project
							AND p.surface_ha IS NOT NULL
							$cond_surf 
						  GROUP By pr.project_name
						  order by pr.project_name";
						$result_srfPjt = pg_query($conn, $sql_srfPjt);
						
						while($row_srfPjt = pg_fetch_assoc($result_srfPjt)) {
							$surf_project .= $row_srfPjt['project_name'].'##'.$row_srfPjt['acres'].'##'.$row_srfPjt['m2'].'##'.$row_srfPjt['ha'].'**';
						}
					
					} else { 
						
						// New
						$sql_stNew = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						 and p.id_company=$id_company ) 
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and mobile_created=643 ";
						$result_stNew = pg_query($conn, $sql_stNew);
						$row_stNew = pg_fetch_assoc($result_stNew);
						$new = $row_stNew['count'];
						
						// Review
						$sql_stReview = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						and p.id_company=$id_company ) 
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and mobile_created=644 ";
						$result_stReview = pg_query($conn, $sql_stReview);
						$row_stReview = pg_fetch_assoc($result_stReview);
						$review = $row_stReview['count'];
						
						// Approved
						$sql_stApproved = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						and p.id_company=$id_company ) 
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and mobile_created=645 ";
						$result_stApproved = pg_query($conn, $sql_stApproved);
						$row_stApproved = pg_fetch_assoc($result_stApproved);
						$approved = $row_stApproved['count'];
						
						// Contracted
						$sql_stContracted = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						and p.id_company=$id_company ) 
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and mobile_created=663 ";
						$result_stContracted = pg_query($conn, $sql_stContracted);
						$row_stContracted = pg_fetch_assoc($result_stContracted);
						$contracted = $row_stContracted['count'];
						
						// Sensibilisation
						$sql_stSensibilisation = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						and p.id_company=$id_company ) 
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and mobile_created=664 ";
						$result_stSensibilisation = pg_query($conn, $sql_stSensibilisation);
						$row_stSensibilisation = pg_fetch_assoc($result_stSensibilisation);
						$sensibilisation = $row_stSensibilisation['count'];
						
						// Audit ready
						$sql_stAudit = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						and p.id_company=$id_company ) 
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and mobile_created=665 ";
						$result_stAudit = pg_query($conn, $sql_stAudit);
						$row_stAudit = pg_fetch_assoc($result_stAudit);
						$audit = $row_stAudit['count'];
						
						// Certified
						$sql_stCertified = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and mobile_Created is not null
						and p.id_company=$id_company ) 
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and mobile_created=666 ";
						$result_stCertified = pg_query($conn, $sql_stCertified);
						$row_stCertified = pg_fetch_assoc($result_stCertified);
						$certified = $row_stCertified['count'];
						
						// tt_farmers
						$sql_tt_farmers = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and mobile_Created is not null
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )";
						$result_tt_farmers = pg_query($conn, $sql_tt_farmers);
						$row_tt_farmers = pg_fetch_assoc($result_tt_farmers);
						$tt_farmers = $row_tt_farmers['count'];
						$gender_farmers = $row_tt_farmers['count'];
						
						// globalGap
						
						$sql_ggap_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and globalgap=511";
						$result_ggap_cand = pg_query($conn, $sql_ggap_cand);
						$row_ggap_cand = pg_fetch_assoc($result_ggap_cand);
						$ggap_cand = $row_ggap_cand['count'];
						
						$sql_ggap_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and globalgap=512 ";
						$result_ggap_appr = pg_query($conn, $sql_ggap_appr);
						$row_ggap_appr = pg_fetch_assoc($result_ggap_appr);
						$ggap_appr = $row_ggap_appr['count'];
						
						$sql_ggap_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and globalgap=650 ";
						$result_ggap_cert = pg_query($conn, $sql_ggap_cert);
						$row_ggap_cert = pg_fetch_assoc($result_ggap_cert);
						$ggap_cert = $row_ggap_cert['count'];
					
						// RSPO
						
						$sql_rspo_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and rspo=511 ";
						$result_rspo_cand = pg_query($conn, $sql_rspo_cand);
						$row_rspo_cand = pg_fetch_assoc($result_rspo_cand);
						$rspo_cand = $row_rspo_cand['count'];
						
						$sql_rspo_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and rspo=512 ";
						$result_rspo_appr = pg_query($conn, $sql_rspo_appr);
						$row_rspo_appr = pg_fetch_assoc($result_rspo_appr);
						$rspo_appr = $row_rspo_appr['count'];
						
						$sql_rspo_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and rspo=650 ";
						$result_rspo_cert = pg_query($conn, $sql_rspo_cert);
						$row_rspo_cert = pg_fetch_assoc($result_rspo_cert);
						$rspo_cert = $row_rspo_cert['count'];
						
						// BIO UE
						
						$sql_bioue_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and bio=511 ";
						$result_bioue_cand = pg_query($conn, $sql_bioue_cand);
						$row_bioue_cand = pg_fetch_assoc($result_bioue_cand);
						$bioue_cand = $row_bioue_cand['count'];
						
						$sql_bioue_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and	bio=512 ";
						$result_bioue_appr = pg_query($conn, $sql_bioue_appr);
						$row_bioue_appr = pg_fetch_assoc($result_bioue_appr);
						$bioue_appr = $row_bioue_appr['count'];
						
						$sql_bioue_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and bio=650 ";
						$result_bioue_cert = pg_query($conn, $sql_bioue_cert);
						$row_bioue_cert = pg_fetch_assoc($result_bioue_cert);
						$bioue_cert = $row_bioue_cert['count'];
						
						// BIO SUISSE
						
						$sql_bioss_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and bio_suisse=511 ";
						$result_bioss_cand = pg_query($conn, $sql_bioss_cand);
						$row_bioss_cand = pg_fetch_assoc($result_bioss_cand);
						$bioss_cand = $row_bioss_cand['count'];
						
						$sql_bioue_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and bio_suisse=512 ";
						$result_bioue_appr = pg_query($conn, $sql_bioue_appr);
						$row_bioue_appr = pg_fetch_assoc($result_bioue_appr);
						$bioue_appr = $row_bioue_appr['count'];
						
						$sql_bioss_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and bio_suisse=650 ";
						$result_bioss_cert = pg_query($conn, $sql_bioss_cert);
						$row_bioss_cert = pg_fetch_assoc($result_bioss_cert);
						$bioss_cert = $row_bioss_cert['count'];
						
						// Fair Trade
						
						$sql_ftrad_cand = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and fair_trade=511 ";
						$result_ftrad_cand = pg_query($conn, $sql_ftrad_cand);
						$row_ftrad_cand = pg_fetch_assoc($result_ftrad_cand);
						$ftrad_cand = $row_ftrad_cand['count'];
						
						$sql_ftrad_appr = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and fair_trade=512 ";
						$result_ftrad_appr = pg_query($conn, $sql_ftrad_appr);
						$row_ftrad_appr = pg_fetch_assoc($result_ftrad_appr);
						$ftrad_appr = $row_ftrad_appr['count'];
						
						$sql_ftrad_cert = "select count(*) from plantation
						where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )
						and fair_trade=650 ";
						$result_ftrad_cert = pg_query($conn, $sql_ftrad_cert);
						$row_ftrad_cert = pg_fetch_assoc($result_ftrad_cert);
						$ftrad_cert = $row_ftrad_cert['count'];
						
						// Plantation
						$sql_plantation = "select 'Plantation' as Status, count(*) from plantation where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company ) ";
						$result_plantation = pg_query($conn, $sql_plantation);
						$row_plantation = pg_fetch_assoc($result_plantation);
						$plantation = $row_plantation['count'];
						
						// Mapped
						$sql_mapped = "select count(*) from plantation where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and geom_json is not null ";
						$result_mapped = pg_query($conn, $sql_mapped);
						$row_mapped = pg_fetch_assoc($result_mapped);
						$mapped = $row_mapped['count'];
					
						// Point
						$sql_point = "select count(*) from plantation where plantation.coordx IS NOT NULL and id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company ) ";
						$result_point = pg_query($conn, $sql_point);
						$row_point = pg_fetch_assoc($result_point);
						$point = $row_point['count'];
						
						
						// Gender
						
						$sql_gender_men = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and mobile_Created is not null
						and id_gender=213 ";
						$result_gender_men = pg_query($conn, $sql_gender_men);
						$row_gender_men = pg_fetch_assoc($result_gender_men);
						$gender_men = $row_gender_men['count'];
						
						$sql_gender_women = "select count(*) from contact where id_contact in (
						select distinct pm.contact_id from project_members pm, project p
						where p.id_project=pm.project_id
						and p.id_company=$id_company )
						and mobile_Created is not null
						and id_gender=214 ";
						$result_gender_women = pg_query($conn, $sql_gender_women);
						$row_gender_women = pg_fetch_assoc($result_gender_women);
						$gender_women = $row_gender_women['count'];
						
						// Surface by Project
				
						$sql_srfPjt = "SELECT 
							pr.project_name,
							SUM(p.area_acres) AS acres,
							SUM(p.area) AS m2,
							SUM(p.surface_ha) AS ha
						  FROM project_members pm,
							project_task pt,
							v_plantation p,
							project pr
						  WHERE pm.task_id = pt.id_task 
							AND p.gid_plantation = pm.plantation_id 
							AND pr.id_project = pt.id_project 
							AND p.surface_ha IS NOT NULL
							AND pr.id_company=$id_company
						  GROUP By pr.project_name
						  order by pr.project_name";
						$result_srfPjt = pg_query($conn, $sql_srfPjt);
						
						while($row_srfPjt = pg_fetch_assoc($result_srfPjt)) {
							$surf_project .= $row_srfPjt['project_name'].'##'.$row_srfPjt['acres'].'##'.$row_srfPjt['m2'].'##'.$row_srfPjt['ha'].'**';
						}
					}
					
				} else
				if($id_supchain_type == 331) {
					
					// New
					$sql_stNew = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and mobile_Created is not null
					and p.cooperative_id=$id_company ) 
					and mobile_created=643 ";
					$result_stNew = pg_query($conn, $sql_stNew);
					$row_stNew = pg_fetch_assoc($result_stNew);
					$new = $row_stNew['count'];
					
					// Review
					$sql_stReview = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and mobile_Created is not null
					and p.cooperative_id=$id_company ) 
					and mobile_created=644 ";
					$result_stReview = pg_query($conn, $sql_stReview);
					$row_stReview = pg_fetch_assoc($result_stReview);
					$review = $row_stReview['count'];
					
					// Approved
					$sql_stApproved = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and mobile_Created is not null
					and p.cooperative_id=$id_company ) 
					and mobile_created=645 ";
					$result_stApproved = pg_query($conn, $sql_stApproved);
					$row_stApproved = pg_fetch_assoc($result_stApproved);
					$approved = $row_stApproved['count'];
					
					// Contracted
					$sql_stContracted = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and mobile_Created is not null
					and p.cooperative_id=$id_company ) 
					and mobile_created=663 ";
					$result_stContracted = pg_query($conn, $sql_stContracted);
					$row_stContracted = pg_fetch_assoc($result_stContracted);
					$contracted = $row_stContracted['count'];
					
					// Sensibilisation
					$sql_stSensibilisation = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and mobile_Created is not null
					and p.cooperative_id=$id_company ) 
					and mobile_created=664 ";
					$result_stSensibilisation = pg_query($conn, $sql_stSensibilisation);
					$row_stSensibilisation = pg_fetch_assoc($result_stSensibilisation);
					$sensibilisation = $row_stSensibilisation['count'];
					
					// Audit ready
					$sql_stAudit = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and mobile_Created is not null
					and p.cooperative_id=$id_company ) 
					and mobile_created=665 ";
					$result_stAudit = pg_query($conn, $sql_stAudit);
					$row_stAudit = pg_fetch_assoc($result_stAudit);
					$audit = $row_stAudit['count'];
					
					// Certified
					$sql_stCertified = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and mobile_Created is not null
					and p.cooperative_id=$id_company ) 
					and mobile_created=666 ";
					$result_stCertified = pg_query($conn, $sql_stCertified);
					$row_stCertified = pg_fetch_assoc($result_stCertified);
					$certified = $row_stCertified['count'];
					
					// tt_farmers
					$sql_tt_farmers = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and mobile_Created is not null";
					$result_tt_farmers = pg_query($conn, $sql_tt_farmers);
					$row_tt_farmers = pg_fetch_assoc($result_tt_farmers);
					$tt_farmers = $row_tt_farmers['count'];
					$gender_farmers = $row_tt_farmers['count'];
					
					// globalGap
					
					$sql_ggap_cand = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and globalgap=511";
					$result_ggap_cand = pg_query($conn, $sql_ggap_cand);
					$row_ggap_cand = pg_fetch_assoc($result_ggap_cand);
					$ggap_cand = $row_ggap_cand['count'];
					
					$sql_ggap_appr = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and globalgap=512 ";
					$result_ggap_appr = pg_query($conn, $sql_ggap_appr);
					$row_ggap_appr = pg_fetch_assoc($result_ggap_appr);
					$ggap_appr = $row_ggap_appr['count'];
					
					$sql_ggap_cert = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and globalgap=650 ";
					$result_ggap_cert = pg_query($conn, $sql_ggap_cert);
					$row_ggap_cert = pg_fetch_assoc($result_ggap_cert);
					$ggap_cert = $row_ggap_cert['count'];
				
					// RSPO
					
					$sql_rspo_cand = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and rspo=511 ";
					$result_rspo_cand = pg_query($conn, $sql_rspo_cand);
					$row_rspo_cand = pg_fetch_assoc($result_rspo_cand);
					$rspo_cand = $row_rspo_cand['count'];
					
					$sql_rspo_appr = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and rspo=512 ";
					$result_rspo_appr = pg_query($conn, $sql_rspo_appr);
					$row_rspo_appr = pg_fetch_assoc($result_rspo_appr);
					$rspo_appr = $row_rspo_appr['count'];
					
					$sql_rspo_cert = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and rspo=650 ";
					$result_rspo_cert = pg_query($conn, $sql_rspo_cert);
					$row_rspo_cert = pg_fetch_assoc($result_rspo_cert);
					$rspo_cert = $row_rspo_cert['count'];
					
					// BIO UE
					
					$sql_bioue_cand = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and bio=511 ";
					$result_bioue_cand = pg_query($conn, $sql_bioue_cand);
					$row_bioue_cand = pg_fetch_assoc($result_bioue_cand);
					$bioue_cand = $row_bioue_cand['count'];
					
					$sql_bioue_appr = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and	bio=512 ";
					$result_bioue_appr = pg_query($conn, $sql_bioue_appr);
					$row_bioue_appr = pg_fetch_assoc($result_bioue_appr);
					$bioue_appr = $row_bioue_appr['count'];
					
					$sql_bioue_cert = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and bio=650 ";
					$result_bioue_cert = pg_query($conn, $sql_bioue_cert);
					$row_bioue_cert = pg_fetch_assoc($result_bioue_cert);
					$bioue_cert = $row_bioue_cert['count'];
					
					// BIO SUISSE
					
					$sql_bioss_cand = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and bio_suisse=511 ";
					$result_bioss_cand = pg_query($conn, $sql_bioss_cand);
					$row_bioss_cand = pg_fetch_assoc($result_bioss_cand);
					$bioss_cand = $row_bioss_cand['count'];
					
					$sql_bioue_appr = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and bio_suisse=512 ";
					$result_bioue_appr = pg_query($conn, $sql_bioue_appr);
					$row_bioue_appr = pg_fetch_assoc($result_bioue_appr);
					$bioue_appr = $row_bioue_appr['count'];
					
					$sql_bioss_cert = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and bio_suisse=650 ";
					$result_bioss_cert = pg_query($conn, $sql_bioss_cert);
					$row_bioss_cert = pg_fetch_assoc($result_bioss_cert);
					$bioss_cert = $row_bioss_cert['count'];
					
					// Fair Trade
					
					$sql_ftrad_cand = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and fair_trade=511 ";
					$result_ftrad_cand = pg_query($conn, $sql_ftrad_cand);
					$row_ftrad_cand = pg_fetch_assoc($result_ftrad_cand);
					$ftrad_cand = $row_ftrad_cand['count'];
					
					$sql_ftrad_appr = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and fair_trade=512 ";
					$result_ftrad_appr = pg_query($conn, $sql_ftrad_appr);
					$row_ftrad_appr = pg_fetch_assoc($result_ftrad_appr);
					$ftrad_appr = $row_ftrad_appr['count'];
					
					$sql_ftrad_cert = "select count(*) from plantation
					where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and fair_trade=650 ";
					$result_ftrad_cert = pg_query($conn, $sql_ftrad_cert);
					$row_ftrad_cert = pg_fetch_assoc($result_ftrad_cert);
					$ftrad_cert = $row_ftrad_cert['count'];
					
					
					// Plantation
					$sql_plantation = "select count(*) from plantation where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and  p.cooperative_id=$id_company ) ";
					$result_plantation = pg_query($conn, $sql_plantation);
					$row_plantation = pg_fetch_assoc($result_plantation);
					$plantation = $row_plantation['count'];
					
					// Mapped
					$sql_mapped = "select count(*) from plantation where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and  p.cooperative_id=$id_company )
					and geom_json is not null ";
					$result_mapped = pg_query($conn, $sql_mapped);
					$row_mapped = pg_fetch_assoc($result_mapped);
					$mapped = $row_mapped['count'];
					
					// Point
					$sql_point = "select count(*) from plantation where plantation.coordx IS NOT NULL and id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and  p.cooperative_id=$id_company ) ";
					$result_point = pg_query($conn, $sql_point);
					$row_point = pg_fetch_assoc($result_point);
					$point = $row_point['count'];
					
					// Gender
					
					$sql_gender_men = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and mobile_Created is not null
					and id_gender=213 ";
					$result_gender_men = pg_query($conn, $sql_gender_men);
					$row_gender_men = pg_fetch_assoc($result_gender_men);
					$gender_men = $row_gender_men['count'];
					
					$sql_gender_women = "select count(*) from contact where id_contact in (
					select distinct pm.contact_id from project_members pm, project p
					where p.id_project=pm.project_id
					and p.cooperative_id=$id_company )
					and mobile_Created is not null
					and id_gender=214 ";
					$result_gender_women = pg_query($conn, $sql_gender_women);
					$row_gender_women = pg_fetch_assoc($result_gender_women);
					$gender_women = $row_gender_women['count'];
					
					// Surface by Project
				
					$sql_srfPjt = "SELECT 
						pr.project_name,
						SUM(p.area_acres) AS acres,
						SUM(p.area) AS m2,
						SUM(p.surface_ha) AS ha
					  FROM project_members pm,
						project_task pt,
						v_plantation p,
						project pr
					  WHERE pm.task_id = pt.id_task 
						AND p.gid_plantation = pm.plantation_id 
						AND pr.id_project = pt.id_project 
						AND p.surface_ha IS NOT NULL
						AND pr.cooperative_id=$id_company
					  GROUP By pr.project_name
					  order by pr.project_name";
					$result_srfPjt = pg_query($conn, $sql_srfPjt);
					
					while($row_srfPjt = pg_fetch_assoc($result_srfPjt)) {
						$surf_project .= $row_srfPjt['project_name'].'##'.$row_srfPjt['acres'].'##'.$row_srfPjt['m2'].'##'.$row_srfPjt['ha'].'**';
					}
					
				} else {  }
			}
			
			
			$workflow = $new.'##'.$review.'##'.$approved.'##'.$contracted.'##'.$sensibilisation.'##'.$audit.'##'.$certified.'##'.$tt_farmers;
			$gender = $gender_men.'##'.$gender_women.'##'.$gender_farmers;
			$certification = $ggap_cand.'##'.$ggap_appr.'##'.$ggap_cert.'##'.$rspo_cand.'##'.$rspo_appr.'##'.$rspo_cert.'##'.$bioue_cand.'##'.$bioue_appr.'##'.$bioue_cert.'##'.$bioss_cand.'##'.$bioss_appr.'##'.$bioss_cert.'##'.$ftrad_cand.'##'.$ftrad_appr.'##'.$ftrad_cert;
			$mapping = $plantation.'##'.$mapped.'##'.$point.'##'.$sql_plantation.'##'.$sql_mapped.'##'.$sql_point;
			$surf_project .= 'end';
			
			$dom = $workflow.'@@'.$gender.'@@'.$certification.'@@'.$mapping.'@@'.$surf_project;
			
		break;
	}
	
}

echo $dom;