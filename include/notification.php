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
		
		case "clear_all_notifications":
		
			$username = $_GET['username'];
			$sql_update = 'select * from public."MarkAllReadUser"(\''.$username.'\'); ';
			$result = pg_query($conn, $sql_update);
			
			if($result) {
				$dom=1;
			} else {
				$dom=0;
			}
			
		break;
		
		
		case "upload_notification_counter":

			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
			$id_contact = $_SESSION['id_contact'];
			$id_user = $_SESSION['id_user'];
			$id_company = $_SESSION['id_company'];
			
			if($id_user_supchain_type==312){
				$sql_notification = "select * from v_documents 
				where sm=1 AND active=1 and id_document not in ( select id_document from v_doc_read 
				where user_id=$id_user) 
				and ord_order_id in ( select id_ord_order from ord_order where ord_sm_person_id=$id_contact )
				union all
				select * from v_documents 
				where msg_recipients like '%$id_contact%'
				and ord_order_id not in ( select id_ord_order from ord_order where ord_sm_person_id=$id_contact )
				order by created_date desc
				";
				
				$sql_notification_count = "SELECT SUM(q1.total) AS total FROM (
					select COUNT(id_document) AS total from v_documents
					where sm=1 AND active=1 and id_document not in ( 
					 select id_document from v_doc_read where user_id=$id_user)
							and ord_order_id in ( select id_ord_order from ord_order where ord_sm_person_id=$id_contact )
					union all
					select COUNT(id_document) AS total from v_documents 
					where msg_recipients like '%$id_contact%'
						and ord_order_id not in ( select id_ord_order from ord_order where ord_sm_person_id=$id_contact )
					) q1
				";
				
			} else {
				if($id_supchain_type==110){
					$sql_notification = "select * from v_documents 
					where client=1 AND active=1 and id_document not in ( select id_document from v_doc_read 
					where user_id=$id_user)
					and ord_order_id in 
					( select id_ord_order from ord_order where ord_cus_contact_id=$id_company )
					Union all
					select * from v_documents 
					where msg_recipients like '%$id_contact%'
					and ord_order_id not in ( select id_ord_order from ord_order where ord_cus_contact_id=$id_company )
					order by created_date desc
					";
					
					$sql_notification_count = "SELECT SUM(q1.total) AS total FROM (
						select COUNT(id_document) AS total from v_documents
						where client=1 AND active=1 and id_document not in ( 
							select id_document from v_doc_read where user_id=$id_user)
								and ord_order_id in ( select id_ord_order from ord_order where ord_cus_contact_id=$id_company )
						union all
						select COUNT(id_document) AS total from v_documents 
						where msg_recipients like '%$id_contact%'
							and ord_order_id not in ( select id_ord_order from ord_order where ord_cus_contact_id=$id_company )
						) q1
					";
				
				} else 
				if($id_supchain_type==112){
					if($id_company==717){
						$sql_notification = "select * from v_documents where ord_order_id in ( select id_ord_order from ord_order
							where ord_imp_contact_id=$id_company )
							and id_document not in ( select id_document from v_doc_read where user_id=$id_user)
							union all
							select * from v_documents 
							where msg_recipients like '%$id_contact%'
							and id_document not in ( select id_document from v_doc_read where user_id=$id_user)
							and ord_order_id in (( select id_ord_order from ord_order
							where ord_imp_contact_id=$id_company 
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
							) q2 ) ))
						";

						$sql_notification_count = "SELECT SUM(q3.total) AS total FROM (
							select COUNT(id_document) AS total from v_documents
								where ord_order_id in ( select id_ord_order from ord_order
									where ord_imp_contact_id=$id_company )
									and id_document not in ( select id_document from v_doc_read where user_id=$id_user)
									union all
									select COUNT(id_document) AS total from v_documents 
									where msg_recipients like '%$id_contact%'
									and id_document not in ( select id_document from v_doc_read where user_id=$id_user)
									and ord_order_id in (( select id_ord_order from ord_order
									where ord_imp_contact_id=$id_company 
									Or ord_imp_contact_id in ( select id_contact from (
										select id_contact from (  
											select * from contact where id_contact in (  
												select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where  
												id_contact=(select id_contact from users where id_user=$id_user ) ) 
											) 
											UNION 
											select * from contact where id_primary_company in (  
												select id_contracting_party from contract where id_contractor = ( select id_primary_company from contact where  
												id_contact=(select id_contact from users where id_user=$id_user ) ) 
											) 
											and id_contact in ( select id_contact from users )  
											union 
											select * from contact where id_contact in (  
												select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where  
												id_contact=(select id_contact from users where id_user=$id_user ) ) 
											) 
											union 
											select * from contact where id_primary_company in (  
												select id_contractor from contract where id_contracting_party = ( select id_primary_company from contact where  
												id_contact=(select id_contact from users where id_user=$id_user ) ) 
											) 
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
                                    ) q2 )))
								)q3
						";
			
					} else 
					if($id_company==641){
							$sql_notification = "select * from v_documents
                               where importer=1 AND active=1 and id_document not in (
                                               select id_document from v_doc_read where user_id=$id_user)
                               and ord_order_id in (( select id_ord_order from ord_order
                                                                              where ord_imp_contact_id=$id_company 
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
                                                                              ) q2 ))
                                                               )
                               union all
                               select * from v_documents
                               where msg_recipients like '%$id_contact%' and id_document not in (
                                               select id_document from v_doc_read where user_id=$id_user)
                               and ord_order_id in (( select id_ord_order from ord_order
                                                                              where ord_imp_contact_id=$id_company 
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
                                               ) q2 ) )) order by created_date desc
						";
		
						$sql_notification_count = "SELECT SUM(q3.total) AS total FROM (
                               select COUNT(id_document) AS total from v_documents
                               where importer=1 AND active=1 and id_document not in (
                                               select id_document from v_doc_read where user_id=$id_user)
                               and ord_order_id in (( select id_ord_order from ord_order
                                                                              where ord_imp_contact_id=$id_company
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
                                                                              ) q2 ))
                                                               )
                               union all
                               select COUNT(id_document) AS total from v_documents
                               where msg_recipients like '%$id_contact%'
                               and id_document not in (
                                               select id_document from v_doc_read where user_id=$id_user)
                               and ord_order_id in (( select id_ord_order from ord_order
                                                                              where ord_imp_contact_id=$id_company
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
                                               ) q2 ) ))
                               )                              q3
						";
						
					} else {}

				} else
				if($id_supchain_type==113){
					$sql_notification = "select * from v_documents 
					where exporter=1 AND active=1 and id_document not in ( select id_document from v_doc_read 
					where user_id=$id_user)
					and ord_order_id in 
					( select distinct ord_order_id from ord_ocean_schedule where supplier_contact_id=$id_company )
					Union all
					select * from v_documents 
					where msg_recipients like '%$id_contact%'
					and ord_order_id not in ( select distinct ord_order_id from ord_ocean_schedule where supplier_contact_id=$id_company )
					order by created_date desc
					";
					
					$sql_notification_count = "SELECT SUM(q1.total) AS total FROM (
					select COUNT(id_document) AS total from v_documents
						where exporter=1 AND active=1 and id_document not in ( 
						 select id_document from v_doc_read where user_id=$id_user)
								and ord_order_id in ( select distinct ord_order_id from ord_ocean_schedule where supplier_contact_id=$id_company )
						union all
						select COUNT(id_document) AS total from v_documents 
						where msg_recipients like '%$id_contact%'
							and ord_order_id not in ( select distinct ord_order_id from ord_ocean_schedule where supplier_contact_id=$id_company )
						) q1
					";
				} else 
				if($id_supchain_type==289){
					$sql_notification = "select * from v_documents 
					where exporter=1 AND active=1 and id_document not in ( select id_document from v_doc_read 
					where user_id=$id_user)
					and ord_order_id in 
					( select distinct ord_order_id from ord_con_booking where fa_contact_id=$id_company )
					Union all
					select * from v_documents 
					where msg_recipients like '%$id_contact%'
					and ord_order_id not in ( select distinct ord_order_id from ord_con_booking where fa_contact_id=$id_company )
					order by created_date desc
					";
					
					$sql_notification_count = "SELECT SUM(q1.total) AS total FROM (
						select COUNT(id_document) AS total from v_documents
						where exporter=1 AND active=1 and id_document not in ( 
						 select id_document from v_doc_read where user_id=$id_user)
								and ord_order_id in ( select distinct ord_order_id from ord_con_booking where fa_contact_id=$id_company )
						union all
						select COUNT(id_document) AS total from v_documents 
						where msg_recipients like '%$id_contact%'
								and ord_order_id not in ( select distinct ord_order_id from ord_con_booking where fa_contact_id=$id_company )
						) q1
					";
				} else {
					$sql_notification = "select * from v_documents where id_document=0";
					
					$sql_notification_count = "select COUNT(id_document) AS total from v_documents where id_document=0";
				}
			}
		
			$rs_notification = pg_query($conn, $sql_notification);

			$notification_list = '';
			while ($row_notification = pg_fetch_assoc($rs_notification)) {
				if($row_notification['doc_type_id'] == 80){
					$doc='fa-envelope';
				} else {
					$doc='fa-file-text';
				}

				if(file_exists('img/avatar/' . $row_notification['id_contact'] . ".jpg")) {
					$img = 'img/avatar/' . $row_notification['id_contact'] . ".jpg";
				} else { $img = 'img/' . "user.jpg"; }


				$notification_list .= '<li class="col-xs-12 no-padding">
					<a href="#" onclick="viewBookingDoc(\''. $row_notification['doc_filename'] .'\',\''. $row_notification['id_document'] .'\');" class="col-xs-12" style="padding:3px 0;">
						<div> 
							<div class="col-xs-1 no-padding text-center">
								<i class="fa '.$doc.' fa-fw" style="font-size:14px; margin-top:8px;"></i>
							</div>
							
							<div class="col-xs-11">
								<span class="text-muted small pull-right" style="line-height:0px;">
									<i class="fa fa-clock-o"></i> '. $row_notification['created_date'] .'
								</span>
								<img src="'.$img.'" class="img-circle" height="20" />
								'.$row_notification['firstname'].'
								<div style="white-space:nowrap; text-overflow:ellipsis; overflow:hidden; line-height:17px;">
									<strong> '.substr($row_notification['document_desc'], 0, 40).'.. </strong><br/>
									'. $row_notification['doctype_name'] .'<br/>'. $row_notification['doc_filename'] .'
								</div>
							</div> 
						</div> 
					</a>
				</li>
				<li class="divider col-xs-12 no-padding"></li>';
			}
			
			$result_notification_count = pg_query($conn, $sql_notification_count);
			$arr_notification_count = pg_fetch_assoc($result_notification_count);
			$nt_counter = $arr_notification_count['total'];	

			$dom=$notification_list.'##'.$nt_counter;
		
		break;
	}
	
}

echo $dom;