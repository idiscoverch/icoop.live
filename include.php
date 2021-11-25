<?php

include_once("fcts.php");

$conn=connect();

if(!$conn) {
	header("Location: error_db.php");
}


$sql_measure_unit = 'SELECT * FROM v_regvalues WHERE id_register=19 ORDER BY cvalue ASC';
$rs_measure_unit = pg_query($conn, $sql_measure_unit);

$measure_unit_list = '<option value="">-- Select product --</option>';
while ($row_measure_unit = pg_fetch_assoc($rs_measure_unit)) {
	if($row_measure_unit['id_regvalue'] == 3){ $sel_measure_unit = 'selected'; }else{ $sel_measure_unit = ''; }
    $measure_unit_list .= '<option value="'.$row_measure_unit['id_regvalue'] .'"'.$sel_measure_unit.'>'.$row_measure_unit['cvalue'] .'</option>';
}


$sql_group_product = 'SELECT * FROM v_regvalues WHERE id_register=49 AND id_regvalue=284 ORDER BY cvalue ASC';
$rs_group_product = pg_query($conn, $sql_group_product);

$group_product_list = '<option value="">-- Select product --</option>';
while ($row_group_product = pg_fetch_assoc($rs_group_product)) {
	if($row_group_product['id_regvalue'] == 284){ $sel_group_product = 'selected'; }else{ $sel_group_product = ''; }
    $group_product_list .= '<option value="'.$row_group_product['id_regvalue'] .'"'.$sel_group_product.'>'.$row_group_product['cvalue'] .'</option>';
}


$sql_carrier = 'SELECT id_contact, name FROM public.contact WHERE id_supchain_type=319';
$rs_carrier = pg_query($conn, $sql_carrier);

$carrier_list = '<option value="">-- Select carrier --</option>';
while ($row_carrier = pg_fetch_assoc($rs_carrier)) {
    $carrier_list .= '<option value="'.$row_carrier['id_contact'] .'">'.$row_carrier['name'] .'</option>';
}


$sql_typ_package = 'SELECT * FROM v_regvalues WHERE id_register=47 ORDER BY cvalue ASC';
$rs_typ_package = pg_query($conn, $sql_typ_package);

$typ_package_list = '<option value="">-- Select package --</option>';
while ($row_typ_package = pg_fetch_assoc($rs_typ_package)) {
	if($row_typ_package['id_regvalue'] == 269){ $sel_typ_package = 'selected'; }else{ $sel_typ_package = ''; }
    $typ_package_list .= '<option value="'.$row_typ_package['id_regvalue'] .'"'.$sel_typ_package.'>'.$row_typ_package['cvalue'] .'</option>';
}


$sql_town_ports = 'SELECT * FROM public.ord_towns_port WHERE port_type_id=273 OR port_type_id=275 ORDER BY portname ASC';  
$rs_town_ports = pg_query($conn, $sql_town_ports);

$town_ports_list = '<option value="">-- Select port --</option>';
while ($row_town_ports = pg_fetch_assoc($rs_town_ports)) {
    $town_ports_list .= '<option value="'.$row_town_ports['id_townport'] .','.$row_town_ports['port_code'] .'">'.$row_town_ports['portname'] .'</option>';
}


$sql_incoterms = "SELECT * FROM v_regvalues WHERE id_register=46 ORDER BY cvalue ASC";
$rs_incoterms = pg_query($conn, $sql_incoterms);

$incoterms_list = '<option value="">-- Select incoterms --</option>';
while ($row_incoterms = pg_fetch_assoc($rs_incoterms)) {
    $incoterms_list .= '<option value="'.$row_incoterms['id_regvalue'] .'">'.$row_incoterms['cvalue'] .'</option>';
}


$sql_objects = "SELECT id_object, name, locked FROM public.objects ORDER BY name ASC";
$rs_objects = pg_query($conn, $sql_objects);

$objects_list = '<option value="">-- Select object --</option>';
while ($row_objects = pg_fetch_assoc($rs_objects)) {
    $objects_list .= '<option value="'.$row_objects['id_object'] .'">'.$row_objects['name'] .'</option>';
}



$sql_currency = "SELECT id_regvalue, cvalue FROM public.v_regvalues WHERE id_register = 51";
$rs_currency = pg_query($conn, $sql_currency);

$currency_list = '<option value="">-- Select currency --</option>';
while ($row_currency = pg_fetch_assoc($rs_currency)) {
    $currency_list .= '<option value="'.$row_currency['id_regvalue'] .'">'.$row_currency['cvalue'] .'</option>';
}


$sql_roles = "SELECT   id_role,  name,  name_en,  name_ge,  name_fr,  name_es,  name_po FROM  roles ORDER BY name ASC";
$rs_roles = pg_query($conn, $sql_roles);

$roles_list = '';
while ($row_roles = pg_fetch_assoc($rs_roles)) {
    $roles_list .= '<option value="'.$row_roles['id_role'] .'">'.$row_roles['name'] .'</option>';
}


$sql_townports_pol = 'SELECT * FROM public.ord_towns_port WHERE port_type_id=272 OR port_type_id=274 ORDER BY portname ASC';  
$rs_townports_pol = pg_query($conn, $sql_townports_pol);

$townports_pol_list = '<option value="">-- Select port --</option>';
while ($row_townports_pol = pg_fetch_assoc($rs_townports_pol)) {
    $townports_pol_list .= '<option value="'.$row_townports_pol['id_townport'] .'">'.$row_townports_pol['portname'] .'</option>';
}


$sql_townports_pod = 'SELECT * FROM public.ord_towns_port WHERE port_type_id=273 OR port_type_id=275 ORDER BY portname ASC';  
$rs_townports_pod = pg_query($conn, $sql_townports_pod);

$townports_pod_list = '<option value="">-- Select port --</option>';
while ($row_townports_pod = pg_fetch_assoc($rs_townports_pod)) {
    $townports_pod_list .= '<option value="'.$row_townports_pod['id_townport'] .'">'.$row_townports_pod['portname'] .'</option>';
}


$sql_townports_trans = 'SELECT * FROM public.ord_towns_port WHERE port_type_id=276 ORDER BY portname ASC';  
$rs_townports_trans = pg_query($conn, $sql_townports_trans);

$townports_trans_list = '<option value="">-- Select port --</option>';
while ($row_townports_trans = pg_fetch_assoc($rs_townports_trans)) {
    $townports_trans_list .= '<option value="'.$row_townports_trans['id_townport'] .'">'.$row_townports_trans['portname'] .'</option>';
}


$sql_measure_unit_rcost = 'SELECT * FROM v_regvalues WHERE id_register=45 ORDER BY cvalue ASC';
$rs_measure_unit_rcost = pg_query($conn, $sql_measure_unit_rcost);

$measure_unit_rcost_list = '<option value="">-- Select unit --</option>';
while ($row_measure_unit_rcost = pg_fetch_assoc($rs_measure_unit_rcost)) {
    $measure_unit_rcost_list .= '<option value="'.$row_measure_unit_rcost['id_regvalue'] .'">'.$row_measure_unit_rcost['cvalue'] .'</option>';
}


$sql_port = "SELECT id_regvalue, cvalue FROM public.v_regvalues WHERE id_register = 52";
$rs_port = pg_query($conn, $sql_port);

$port_list = '<option value="">-- Select port --</option>';
while ($row_port = pg_fetch_assoc($rs_port)) {
    $port_list .= '<option value="'.$row_port['id_regvalue'] .'">'.$row_port['cvalue'] .'</option>';
}


$sql_qm_contact = "SELECT id_contact, name FROM public.contact WHERE id_supchain_type = 327 AND id_type = 10";
$rs_qm_contact = pg_query($conn, $sql_qm_contact);

$qm_contact_list = '<option value="">-- Contact --</option>';
while ($row_qm_contact = pg_fetch_assoc($rs_qm_contact)) {
    $qm_contact_list .= '<option value="'.$row_qm_contact['id_contact'] .'">'.$row_qm_contact['name'] .'</option>';
}


$sql_towns = "SELECT gid_town, name_town FROM public.towns ORDER BY name_town ASC";
$rs_towns = pg_query($conn, $sql_towns);

$towns_list = '<option value="">-- Town --</option>';
while ($row_towns = pg_fetch_assoc($rs_towns)) {
    $towns_list .= '<option value="'.$row_towns['gid_town'] .'">'.$row_towns['name_town'] .'</option>';
}


$sql_culture = "SELECT id_culture, name_culture FROM culture";
$rs_culture = pg_query($conn, $sql_culture);

$culture_list = '<option value="">-- Culture --</option>';
while ($row_culture = pg_fetch_assoc($rs_culture)) {
    $culture_list .= '<option value="'.$row_culture['id_culture'] .'">'.$row_culture['name_culture'] .'</option>';
}


$sql_gender = 'SELECT * FROM v_regvalues WHERE id_register=41 ORDER BY cvalue ASC';
$rs_gender = pg_query($conn, $sql_gender);

$gender_list = '<option value="">-- Select gender --</option>';
while ($row_gender = pg_fetch_assoc($rs_gender)) {
    $gender_list .= '<option value="'.$row_gender['id_regvalue'] .'">'.$row_gender['cvalue'] .'</option>';
}


$sql_language = 'SELECT * FROM v_regvalues WHERE id_register=7 ORDER BY cvalue ASC';
$rs_language = pg_query($conn, $sql_language);

$language_list = '<option value="">-- Select Language --</option>';
while ($row_language = pg_fetch_assoc($rs_language)) {
    $language_list .= '<option value="'.$row_language['id_regvalue'] .'">'.$row_language['cvalue'] .'</option>';
}


$sql_transport_type = "SELECT id_regvalue, cvalue FROM public.v_regvalues WHERE id_register = 48";
$rs_transport_type = pg_query($conn, $sql_transport_type);

$transport_type_list = '<option value="">-- Select transport type --</option>';
while ($row_transport_type = pg_fetch_assoc($rs_transport_type)) {
    $transport_type_list .= '<option value="'.$row_transport_type['id_regvalue'] .'">'.$row_transport_type['cvalue'] .'</option>';
}


$sql_sum_pipeline = 'SELECT id_regvalue, cvalue FROM v_regvalues WHERE id_register=53 AND id_regvalue BETWEEN 295 AND 300 ORDER BY id_regvalue ASC';
$rs_sum_pipeline = pg_query($conn, $sql_sum_pipeline);

$typ_sum_pipeline = '<option value="">-- Select a pipeline --</option>';
while ($row_sum_pipeline = pg_fetch_assoc($rs_sum_pipeline)) {
    $typ_sum_pipeline .= '<option value="'.$row_sum_pipeline['id_regvalue'] .'">'.$row_sum_pipeline['cvalue'] .'</option>';
}

$sql_all_pipelines = 'SELECT id_regvalue, cvalue FROM v_regvalues WHERE id_register=53 ORDER BY id_regvalue ASC';
$rs_all_pipelines = pg_query($conn, $sql_all_pipelines);

$list_all_pipelines = '<option value="0">-- Select a pipeline --</option>';
while ($row_all_pipelines = pg_fetch_assoc($rs_all_pipelines)) {
    $list_all_pipelines .= '<option value="'.$row_all_pipelines['id_regvalue'] .'">'.$row_all_pipelines['cvalue'] .'</option>';
}


// $sql_sum_status = 'SELECT id_regvalue, cvalue FROM v_regvalues WHERE id_register=43 ORDER BY id_regvalue ASC';
// $rs_sum_status = pg_query($conn, $sql_sum_status);

// $typ_sum_status = '<option value="">-- Select a status --</option>';
// while ($row_sum_status = pg_fetch_assoc($rs_sum_status)) {
    // $typ_sum_status .= '<option value="'.$row_sum_status['id_regvalue'] .'">'.$row_sum_status['cvalue'] .'</option>';
// }


$sql_region = "SELECT DISTINCT name_town, gid_town FROM towns WHERE id_country=1 ORDER BY name_town ASC";
$rs_region = pg_query($conn, $sql_region);

$region_list = '<option value="">-- Select Region --</option>';
while ($row_region = pg_fetch_assoc($rs_region)) {
    $region_list .= '<option value="'.$row_region['gid_town'] .'@'.$row_region['name_town'] .'">'.$row_region['name_town'] .'</option>';
}


$sql_register = "SELECT id_register, regname FROM public.registers ORDER BY id_register ASC";
$rs_register = pg_query($conn, $sql_register);

$select_register = '';
while ($row_register = pg_fetch_assoc($rs_register)) {
    $select_register .= '<option value="'.$row_register['id_register'] .'">'.$row_register['id_register'] .' - '.$row_register['regname'] .'</option>';
}


$sql_agent_type = 'SELECT * FROM v_regvalues WHERE id_register=280 ORDER BY cvalue ASC';
$rs_agent_type = pg_query($conn, $sql_agent_type);

$agent_type_list = '<option value="">-- Select Agent Type --</option>';
while ($row_agent_type = pg_fetch_assoc($rs_agent_type)) {
    $agent_type_list .= '<option value="'.$row_agent_type['id_regvalue'] .'">'.$row_agent_type['cvalue'] .'</option>';
}
	

$sql_mobile_created = "SELECT * FROM v_regvalues WHERE id_register=284 ORDER BY id_regvalue ASC";
$rs_mobile_created = pg_query($conn, $sql_mobile_created);

$contact_status = '<option value="">--Status--</option>';
while ($row_mobile_created = pg_fetch_assoc($rs_mobile_created)) {
	if($lang['DB_LANG_stat'] == 'en') {
		$contact_status .= '<option value="'.$row_mobile_created['id_regvalue'] .'">'.$row_mobile_created['cvalue'] .'</option>';
	} else {
		$contact_status .= '<option value="'.$row_mobile_created['id_regvalue'] .'">'.$row_mobile_created['cvalue'. $lang['DB_LANG_stat']] .'</option>';
	}
}

	
$id_supchain_type = $_SESSION['id_supchain_type'];
$id_user_supchain_type = $_SESSION['id_user_supchain_type'];
$id_contact = $_SESSION['id_contact'];
$id_user = $_SESSION['id_user'];
$id_company = $_SESSION['id_company'];
	
	
$sql_cooperative = "select id_contact, name from contact where id_supchain_type = 331
and id_contact in ( select id_contracting_party from contract where id_contractor=$id_company )";
$rs_cooperative = pg_query($conn, $sql_cooperative);

$cooperative_list = '<option value="0">-- Select Cooperative --</option>';
while ($row_cooperative = pg_fetch_assoc($rs_cooperative)) {
    $cooperative_list .= '<option value="'.$row_cooperative['id_contact'] .'">'.$row_cooperative['name'] .'</option>';
}


$sql_headquarter = "SELECT id_contact, name FROM contact WHERE id_contact IN (645, 646, 647) AND id_primary_company = 636";
$rs_headquarter = pg_query($conn, $sql_headquarter);

$headquarter_list = '<option value="0">-- Select a company --</option>';
while ($row_headquarter = pg_fetch_assoc($rs_headquarter)) {
    $headquarter_list .= '<option value="'.$row_headquarter['id_contact'] .'">'.$row_headquarter['name'] .'</option>';
}

	
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

	// $monfichier = fopen('test.txt', 'r+');
	// ftruncate($monfichier,0);
	// fputs($monfichier,$id_supchain_type.'@@'.$sql_notification.'========='.$sql_notification_count);

	
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


$sql_stories = "
	SELECT
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

	ORDER BY id_country;
";

$rs_stories = pg_query($conn, $sql_stories);
$dom_stories = '';

$i = 1;
while ($row_stories = pg_fetch_assoc($rs_stories)) {
	$caption = utf8_decode($row_stories['title']);
	$name_country = utf8_decode($row_stories['name_country']);
    $valeur=$row_stories['id_story'];
	if ($row_stories['media_type'] == 2 ){
		$media = 'fa-file-image-o';
	} else {
		$media = 'fa-video-camera';
	}

    $dom_stories .= '<tr class="feature-row" onclick="";>
		<td style="vertical-align: middle;">'.$i.'</td>
		<td class="title"><i class="fa '.$media.' white" style="color:#1ab394"></i>&nbsp;&nbsp;'.$caption.'</td>
		<td align="center" style="vertical-align: middle; width:12%;" class="country">'.$name_country.'</td>
		<td style="vertical-align: middle;text-align:center; width:8%;">'.$row_stories['id_story'].'</td>
		<td align="center" class="vertical-align: middle;">
			<a href="#"  onclick="fenetre(\'story\',\'modif\','.$row_stories['id_story'] .');" ><i class="fa fa-pencil-square-o" alt="Modifier"></i></a>
			<a href="#"  onclick="enregistrer(\'story\',\'delete\','.$row_stories['id_story'] .');"><i class="fa fa-trash-o"></i></a>
		</td>
	<tr>';
	$i += 1;
}


$sql_step = "SELECT id_storycon, id_story, seq_number, seq_mediatype, seq_texten,
	seq_coordx, seq_coordy
  FROM story_con
	ORDER BY id_story, seq_number;
";

$rs_step = pg_query($conn, $sql_step);

$dom_steps = '';

$j = 1;
while ($row_step = pg_fetch_assoc($rs_step)) {
	$caption = utf8_decode($row_step['seq_texten']);
	$id_story=$row_step['id_story'];
    $seq_number=$row_step['seq_number'];
	if ($row_step['seq_mediatype'] == 2 ){
		$media = 'fa-file-image-o';
	} else {
		$media = 'fa-video-camera';
	}

    $dom_steps .= '<tr class="feature-row" onclick="";>
		<td style="vertical-align: middle;">'.$j.'</td>
		<td style="vertical-align: middle;text-align:center;" class="story_id">'.$row_step['id_story'].'</td>
		<td style="vertical-align: middle;">Sequence '.$row_step['seq_number'].'</td>
		<td class="content"><i class="fa '.$media.' white" style="color:#1ab394"></i>&nbsp;&nbsp;'.$caption.'</td>
		<td style="vertical-align: middle;">'.$row_step['seq_coordx'].'</td>
		<td style="vertical-align: middle;">'.$row_step['seq_coordy'].'</td>
		<td align="center" class="vertical-align: middle;">
			<a href="#"  onclick="fenetre(\'step\',\'modif\','.$row_step['id_storycon'] .');" ><i class="fa fa-pencil-square-o" alt="Modifier"></i></a>
			<a href="#"  onclick="enregistrer(\'step\',\'delete\','.$row_step['id_storycon'] .');"><i class="fa fa-trash-o"></i></a>
		</td>
	<tr>';
	$j += 1;
}



$sql_roles = "SELECT id_user, username, id_role, rolename, id_object, objname, _create,
       _read, _update, _delete
  FROM public.v_user_roles WHERE id_user = ". $_SESSION['id_user'] ."
";

$rs_roles = pg_query($conn, $sql_roles);
$rows = array();

while ($row_roles = pg_fetch_assoc($rs_roles)) {
	$rows[] = $row_roles;
}

?>



<script type="text/javascript">
	var user_roles = <?php echo json_encode($rows); ?>;
</script>
