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
		
		case "status":
		
			$cond="";
			$id_cooperative = $_GET['id_cooperative'];
			if($id_cooperative!=0) { $cond=" AND pt.id_cooperative=$id_cooperative"; }
			
			$id_supchain_type = $_SESSION['id_supchain_type'];
			$id_primary_company = $_SESSION['id_primary_company'];
			$id_company = $_SESSION['id_company'];
			$html = "";
			$head = "";
			
			if(($id_company == 19) OR ($id_company == 15064) OR ($id_company == 23103)) {
				$sql = "select name_farmer as \"Producteur\", 
					project_name,
					code_farmer as \"Code C\", 
					name_town as \"Localisation\", 
					to_char(coordx,'99D99999')||';'||to_char(coordy,'99D99999') as \"Coordonnées GPS\",
					case
					when property=1 then 'Propriétaire'
					when property=0 then 'Locataire' 
					end as \"Statut du Terrain\",
					getregvalue_translate(id_culture1,'FR') as \"Type de Culture\", 
					to_char(surface_ha,'990.999') as \"Superficie (ha)\", 
					get_culture_list(substring(adjoining_cultures,1,3),0)||get_culture_list(substring(adjoining_cultures,5,3),1)||get_culture_list(substring(adjoining_cultures,9,3),1)||get_culture_list(substring(adjoining_cultures,14,3),1) as  \"Cultures Environanntes\", 
					intercropping as \"Cultures associées\", 
					number_staff_permanent+number_staff_temporary+number_staff_permanent as \"No employees\", 
					'Suivante' as \"Type de récolte\", 
					'Toute l''année' as \"Période de récolte\", 
					'Allemagne, Pays bas, Suisse, Angleterre' as \"Pays de destination du produit\", 
					'Non' as \"Production parallèle?\", 
					'Non' as \"Propriété parallèle?\", 
					'OUI' as \"Manipulation Incluse\", 
					'Bord champs' as \"Lieu de Manipulation\", 
					'BNA' as \"Organisme de certification\", 
					getregvalue_translate(globalgap,'FR') as globalgap,
					code_parcelle,
					geom_json
					from v_plantation
					where gid_plantation in
					(select pm.plantation_id from v_project_members_plant pm, v_project_tasks pt
					where pt.id_task=pm.task_id
					and pt.id_company=$id_company $cond )
				order by code_farmer";
				
				$head = "<tr>
												<th>Producteur</th>
												<th>Zone</th>
												<th>Code C</th>
												<th>Localisation</th>
												<th>Coordonnées GPS</th>
												<th>Statut du Terrain</th>
												<th>Type de Culture</th>
												<th>Superficie (ha)</th>
												<th>Cultures Environantes</th>
												<th>Cultures associées</th>
												<th>No employées</th>
												<th>Type de récolte</th>
												<th>Période de récolte</th>
												<th>Pays de destination du produit</th>
												<th>Production parallèle?</th>
												<th>Propriété parallèle?</th>
												<th>Manipulation Incluse</th>
												<th>Lieu de Manipulation</th>
												<th>Organisme de certification</th>
												<th>Globalgap</th>
												<th>Code P</th>
												<th>Cartographie</th>
											</tr>";
				
			} else
			if(($id_company == 14167) OR ($id_company == 645) OR ($id_company == 646) OR ($id_company == 647) OR ($id_primary_company == 636)){
				
				if($id_primary_company == 636) {
					$case="and pt.id_company IN (645, 646, 647)"; 
				} else {
					$case="and pt.id_company=$id_company"; 
				}
				
				$sql = "select name_farmer as \"Producteur\",
					project_name,
					code_farmer as \"Code Producteur\",
					code_parcelle as \"Code Plantation\",
					name_town as \"Localisation\",
					to_char(coordx,'99D99999')||';'||to_char(coordy,'99D99999') as \"Coordonnées GPS\",
					case
					when property=1 then 'Propriétaire'
					when property=0 then 'Locataire'
					end as \"Statut du Terrain\",
					getregvalue_translate(id_culture1,'FR') as \"Type de Culture\",
					to_char(surface_ha,'990.999') as \"Superficie (ha)\",
					get_culture_list(substring(adjoining_cultures,1,3),0)||get_culture_list(substring(adjoining_cultures,5,3),1)||get_culture_list(substring(adjoining_cultures,9,3),1)||get_culture_list(substring(adjoining_cultures,14,3),1) as \"Cultures Environanntes\",
					intercropping as \"Cultures associées\",
					number_staff_permanent+number_staff_temporary+number_staff_permanent as \"No employées\",
					getregvalue_translate(rspo,'FR') as \"RSPO\",
					getregvalue_translate(bio,'FR') as \"Bio UE\",
					getregvalue_translate(bio_suisse,'FR') as \"Bio-Suisse\",
					cooperative_name as \"Coopérative\",
					geom_json
					from v_plantation
					where gid_plantation in
					(select pm.plantation_id from v_project_members_plant pm, v_project_tasks pt
					where pt.id_task=pm.task_id
					$case $cond )
					order by code_farmer";
					
					$head = "<tr>
												<th>Code Producteur</th>
												<th>Zone</th>
												<th>Producteur</th>
												<th>Code Plantation</th>
												<th>Localisation</th>
												<th>Coordonnées GPS</th>
												<th>Statut du Terrain</th>
												<th>Type de Culture</th>
												<th>Superficie (ha)</th>
												<th>Cultures Environanntes</th>
												<th>Cultures associées</th>
												<th>No employées</th>
												<th>RSPO</th>
												<th>Bio UE</th>
												<th>Bio-Suisse</th>
												<th>Coopérative</th>
												<th>Cartographie</th>
											</tr>";
			} else
			if ($id_supchain_type == 331) {
				
				$sql = "select name_farmer as \"Producteur\",
					project_name,
					code_farmer as \"Code Producteur\",
					code_parcelle as \"Code Plantation\",
					name_town as \"Localisation\",
					to_char(coordx,'99D99999')||';'||to_char(coordy,'99D99999') as \"Coordonnées GPS\",
					case
					when property=1 then 'Propriétaire'
					when property=0 then 'Locataire'
					end as \"Statut du Terrain\",
					getregvalue_translate(id_culture1,'FR') as \"Type de Culture\",
					to_char(surface_ha,'990.999') as \"Superficie (ha)\",
					get_culture_list(substring(adjoining_cultures,1,3),0)||get_culture_list(substring(adjoining_cultures,5,3),1)||get_culture_list(substring(adjoining_cultures,9,3),1)||get_culture_list(substring(adjoining_cultures,14,3),1) as \"Cultures Environanntes\",
					intercropping as \"Cultures associées\",
					number_staff_permanent+number_staff_temporary+number_staff_permanent as \"No employées\",
					getregvalue_translate(rspo,'FR') as \"RSPO\",
					getregvalue_translate(bio,'FR') as \"Bio UE\",
					getregvalue_translate(bio_suisse,'FR') as \"Bio-Suisse\",
					cooperative_name as \"Coopérative\",
					geom_json
					from v_plantation
					where gid_plantation in
					(select pm.plantation_id from v_project_members_plant pm, v_project_tasks pt
					where pt.id_task=pm.task_id
					and pt.id_cooperative=$id_company )
					order by code_farmer";
					
				$head = "<tr>
												<th>Code Producteur</th>
												<th>Zone</th>
												<th>Producteur</th>
												<th>Code Plantation</th>
												<th>Localisation</th>
												<th>Coordonnées GPS</th>
												<th>Statut du Terrain</th>
												<th>Type de Culture</th>
												<th>Superficie (ha)</th>
												<th>Cultures Environanntes</th>
												<th>Cultures associées</th>
												<th>No employées</th>
												<th>RSPO</th>
												<th>Bio UE</th>
												<th>Bio-Suisse</th>
												<th>Coopérative</th>
												<th>Cartographie</th>
											</tr>";
			}
			
			$result = pg_query($conn, $sql);
		
			if(($id_company == 19) OR ($id_company == 15064) OR ($id_company == 23103)) {
				while($row = pg_fetch_assoc($result)) {
					
					if($row['geom_json']!="") { $carto="YES"; } else {  $carto="NO"; }
						
					$html .= '<tr>
						<td>'.$row['Producteur'].'</td>
						<td>'.$row['project_name'].'</td>
						<td>'.$row['No GGN'].'</td>
						<td>'.$row['Localisation'].'</td>
						<td>'.$row['Coordonnées GPS'].'</td>
						<td>'.$row['Statut du Terrain'].'</td>
						<td>'.$row['Type de Culture'].'</td>
						<td>'.$row['Superficie (ha)'].'</td>
						<td>'.$row['Cultures Environanntes'].'</td>
						<td>'.$row['Cultures associées'].'</td>
						<td>'.$row['No employees'].'</td>
						<td>'.$row['Type de récolte'].'</td>
						<td>'.$row['Période de récolte'].'</td>
						<td>'.$row['Pays de destination du produit'].'</td>
						<td>'.$row['Production parallèle?'].'</td>
						<td>'.$row['Propriété parallèle?'].'</td>
						<td>'.$row['Manipulation Incluse'].'</td>
						<td>'.$row['Lieu de Manipulation'].'</td>
						<td>'.$row['Organisme de certification'].'</td>
						<td>'.$row['globalgap'].'</td>
						<td>'.$row['code_parcelle'].'</td>
						<td>'.$carto.'</td>
					</tr>';
				}
				
			} else {
				
				while($row = pg_fetch_assoc($result)) {
					
					if($row['geom_json']!="") { $carto="YES"; } else {  $carto="NO"; }
					
					$html .= '<tr>
						<td>'.$row['Code Producteur'].'</td>
						<td>'.$row['project_name'].'</td>
						<td>'.$row['Producteur'].'</td>
						<td>'.$row['Code Plantation'].'</td>
						<td>'.$row['Localisation'].'</td>
						<td>'.$row['Coordonnées GPS'].'</td>
						<td>'.$row['Statut du Terrain'].'</td>
						<td>'.$row['Type de Culture'].'</td>
						<td>'.$row['Superficie (ha)'].'</td>
						<td>'.$row['Cultures Environanntes'].'</td>
						<td>'.$row['Cultures associées'].'</td>
						<td>'.$row['No employées'].'</td>
						<td>'.$row['RSPO'].'</td>
						<td>'.$row['Bio UE'].'</td>
						<td>'.$row['Bio-Suisse'].'</td>
						<td>'.$row['Coopérative'].'</td>
						<td>'.$carto.'</td>
					</tr>';
				}

			}
		
			$dom = $html.'@@'.$head;
			
		break;
	}
	
}

echo $dom;