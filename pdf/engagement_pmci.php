<?php     
ob_start();
include('PDFheaderfooter_engagement.php');
include_once("../fcts.php");
$conn=connect();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  

if (isset($_GET['id_plantation'])) { $id_plantation=$_GET['id_plantation']; }
if (isset($_GET['id_contact'])) { $id_contact=$_GET['id_contact']; }
if (isset($_GET['doc_date'])) { $doc_date=$_GET['doc_date']; } 
if (isset($_GET['save'])) { $save=$_GET['save']; }

// $filename = 'AFS-'.$id_plantation.'-'.strtotime("now").'.pdf';
$doc_link = 'AFS-'.$id_plantation.'.pdf';

if(!empty($id_contact)){

	$sql_stats = "SELECT 
		firstname,
		lastname,
		name_town,
		phone1,
		contact_code
	FROM v_icw_contacts WHERE id_contact=$id_contact";

	$result = pg_query($conn, $sql_stats);
	$arr = pg_fetch_assoc($result);

	$firstname = $arr['firstname'];
	$lastname = $arr['lastname'];
	$name_town = utf8_decode($arr['name_town']);
	$phone1 = $arr['phone1'];
	$contact_code = $arr['contact_code'];
	
	
	// $sql_nbPlant = "SELECT count(gid_plantation) AS nb_plantation FROM v_plantation WHERE id_contact=$id_contact";
	// $result_nbPlant = pg_query($conn, $sql_nbPlant);
	// $arr_nbPlant = pg_fetch_assoc($result_nbPlant);
	// $nb_plantation = $arr_nbPlant['nb_plantation'];
	
	
	$sql_plant = "SELECT 
		seed_type_name,
		variety,
		id_culture1,
		surface_ha,
		estimate_production
	FROM v_plantation WHERE gid_plantation=$id_plantation";

	$result_plant = pg_query($conn, $sql_plant);
	$arr_plant = pg_fetch_assoc($result_plant);

	$seed_type_name = utf8_decode($arr_plant['seed_type_name']);
	if(!empty($arr_plant['variety'])) {
		$variety = ' / '.$arr_plant['variety'];
	} else { $variety = ""; }

	$surface_ha = $arr_plant['surface_ha'];
	$plant_id_culture1 = $arr_plant['id_culture1'];
	// $estimate_production = $arr_plant['estimate_production'];
	
	
// Instanciation de la classe dérivée
$PDF = new PDF();
$PDF->AliasNbPages();
$PDF->AddPage();

//

$PDF->Ln(20);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,'Il est convenu entre les','C');
$PDF->Cell(42);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,'Plantations Modernes de Côte d’Ivoire (PMCI Sarl)','C');
$PDF->Cell(98);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,', 10 BP 2229 ','C');

$PDF->Ln(7);
$PDF->Cell(3,1,'Abidjan 10, représentée par Monsieur ','C');
$PDF->SetFont('Arial','B',12);
$PDF->Cell(69);
$PDF->Cell(3,1,'Ralph ERDMANN','C');
$PDF->SetFont('Arial','',12);
$PDF->Cell(32);
$PDF->Cell(3,1,', Co-gérant,','C');

$PDF->Ln(7);
$PDF->Cell(3,1,'Dénommé le porteur de certificat,','C');

$PDF->Ln(16);
$PDF->Cell(3,1,'D’une part ','C');

$PDF->Ln(10);
$PDF->Cell(3,1,'Et Monsieur ' . $lastname .' '. $firstname,'C');
$PDF->Cell(101);
$PDF->SetFont('Arial','B',11);
$PDF->Cell(3,1,'Localité : ','C');
$PDF->Cell(16);
$PDF->SetFont('Arial','',11);
$PDF->Cell(3,1,$name_town,'C');

$PDF->Ln(8);
$PDF->SetFont('Arial','B',11);
$PDF->Cell(3,1,'Téléphone : ','C');
$PDF->Cell(23);
$PDF->SetFont('Arial','',11);
$PDF->Cell(3,1,$phone1,'C');
$PDF->Cell(75);
$PDF->SetFont('Arial','B',11);
$PDF->Cell(3,1,'Adresse : ','C');
$PDF->SetFont('Arial','',11);
$PDF->Cell(16);
$PDF->Cell(3,1,' 10 BP 2229 Abidjan 10','C');   

$PDF->Ln(8);
$PDF->SetFont('Arial','B',11);
$PDF->Cell(3,1,'Variété de noix de coco :','C');
$PDF->Cell(46);
$PDF->SetFont('Arial','',11);
$PDF->Cell(3,1,$seed_type_name . $variety,'C');

// $PDF->Ln(8);
// $PDF->SetFont('Arial','B',11);
// $PDF->Cell(3,1,'Superficie totale (Ha) :','C');
// $PDF->Cell(40);
// $PDF->SetFont('Arial','',11);
// $PDF->Cell(3,1,number_format($surface_ha,2),'C');

$PDF->Ln(8);
$PDF->SetFont('Arial','B',11);
$PDF->Cell(3,1,'Code du producteur : ','C');
$PDF->Cell(38);
$PDF->SetFont('Arial','',11);
$PDF->Cell(3,1,$contact_code,'C');

// $PDF->Ln(8);
// $PDF->SetFont('Arial','B',11);
// $PDF->Cell(3,1,'Nombre totale de plantations : ','C');
// $PDF->Cell(56);
// $PDF->SetFont('Arial','',11);
// $PDF->Cell(3,1,$nb_plantation,'C');

// $PDF->Ln(8);
// $PDF->SetFont('Arial','B',11);
// $PDF->Cell(3,1,'Autres plantations que cocotier : ','C');
// $PDF->SetFont('Arial','',10);

// $sql_oPlant = "SELECT id_culture1 FROM v_plantation WHERE id_culture1 != 180 AND id_contact=$id_contact";
// $result_oPlant = pg_query($conn, $sql_oPlant);
// while($arr_oPlant = pg_fetch_assoc($result_oPlant)) {
	
	// $id_culture1 = $arr_oPlant['id_culture1'];
	// $sqlCulture = "select cvaluefr from v_regvalues where id_regvalue = $id_culture1";
	// $resultCulture = pg_query($conn, $sqlCulture);
	// $arrCulture = pg_fetch_assoc($resultCulture);
	
	// $PDF->Ln(6);
	// $PDF->Cell(10);
	// $PDF->Cell(3,1,chr(127),'C');
	// $PDF->Cell(4);
	// $PDF->Cell(3,1,utf8_decode($arrCulture['cvaluefr']),'C');  
// }


$PDF->Ln(8);
$PDF->SetFont('Arial','B',11);
$PDF->Cell(3,1,'Nombre de Plantation dans le projet de production biologique : ','C');

$PDF->Ln(9);
$PDF->SetFont('Arial','B',11);
$PDF->Cell(10);
$PDF->Cell(3,1,'Code Plantation','C'); 
$PDF->Cell(50);
$PDF->Cell(3,1,'Superficie en Ha','C'); 
$PDF->Cell(70);
$PDF->Cell(3,1,'Estimation de production','C'); 
	
$PDF->SetFont('Arial','',10);
$sql_biologiquePlant = "SELECT code_parcelle, surface_ha, estimate_production FROM v_plantation WHERE id_contact=$id_contact";
$result_biologiquePlant = pg_query($conn, $sql_biologiquePlant);
while($arr_biologiquePlant = pg_fetch_assoc($result_biologiquePlant)) {
	$PDF->Ln(6); 
	$PDF->Cell(10);
	$PDF->Cell(3,1,$arr_biologiquePlant['code_parcelle'],'C');  
	$PDF->Cell(50);
	$PDF->Cell(3,1,number_format($arr_biologiquePlant['surface_ha'],2),'C');
	$PDF->Cell(70);
	$PDF->Cell(3,1,$arr_biologiquePlant['estimate_production'],'C');
}   
 
// $PDF->Ln(8);
// $PDF->SetFont('Arial','B',11);
// $PDF->Cell(3,1,'Code des plantations : ','C');
// $PDF->SetFont('Arial','',10);

// $sql_biologiquePlant2 = "SELECT code_parcelle FROM v_plantation WHERE id_contact=$id_contact";
// $result_biologiquePlant2 = pg_query($conn, $sql_biologiquePlant2);
// while($arr_biologiquePlant2 = pg_fetch_assoc($result_biologiquePlant2)) {
	// $PDF->Ln(6); 
	// $PDF->Cell(10);
	// $PDF->Cell(3,1,chr(127),'C');
	// $PDF->Cell(4);
	// $PDF->Cell(3,1,$arr_biologiquePlant2['code_parcelle'],'C');  
// } 

// $PDF->Ln(8);
// $PDF->SetFont('Arial','B',11);
// $PDF->Cell(3,1,'Production annuelle : ','C');
// $PDF->Cell(45);
// $PDF->SetFont('Arial','',11);
// $PDF->Cell(3,1,$estimate_production,'C');

$PDF->Ln(10);
$PDF->SetFont('Arial','',11);
$PDF->Cell(3,1,'Dénommé ','C');
$PDF->SetFont('Arial','B',11);
$PDF->Cell(17);
$PDF->Cell(3,1,'le producteur, ','C');
$PDF->Cell(24);
$PDF->SetFont('Arial','',11);
$PDF->Cell(3,1,$lastname .' '. $firstname,'C');

$PDF->Ln(16);
$PDF->Cell(3,1,'D’autre part.','C');

$PDF->Ln(10);
$PDF->Cell(3,1,'Engagement des contractants :','C');  

$PDF->Ln(14);
$PDF->SetFont('Arial','B',11);
$PDF->Cell(3,1,'PMCI s’engage à :','C');

$PDF->SetFont('Arial','',11);
$PDF->Ln(10);
$PDF->Cell(3,1,'1.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Coordonner l\'ensemble du projet biologique dans le Sud Comoé ;','C'); 
$PDF->Ln(7);
$PDF->Cell(3,1,'2.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Fournir des services d\'accompagnement sur les bonnes pratiques agricoles avec des conseils','C'); 
$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,'pour l\'agriculture biologique ;','C'); 
$PDF->Ln(7);
$PDF->Cell(3,1,'3.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Coordonner l\'inspection biologique interne et externe et les audits internes et externes ;','C'); 
$PDF->Ln(7);
$PDF->Cell(3,1,'4.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Acheter la noix de coco biologique à un prix durable et transparent, y compris une éventuelle ','C'); 
$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,'prime biologique (selon le marché) lorsque la noix de coco est de qualité appropriée.','C'); 


// Next Page

$PDF->AddPage();

$PDF->Ln(10);
$PDF->SetFont('Arial','B',11);
$PDF->Cell(3,1,'Le producteur s’engage à:','C');
 
$PDF->SetFont('Arial','',10.5);
$PDF->Ln(10);
$PDF->Cell(3,1,'1.','C'); 
$PDF->Cell(6);
$PDF->Cell(3,1,'Être membre du projet de production biologique des noix de coco piloté et mise en œuvre ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'par PMCI Sarl ;','C'); 
$PDF->Ln(5.5);
$PDF->Cell(3,1,'2.','C'); 
$PDF->Cell(6);
$PDF->Cell(3,1,'Suivre les principes de l\'agriculture biologique décrits dans la norme biologique interne, la ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'charte sociale interne ainsi que le système de contrôle interne (SCI) ;','C'); 
$PDF->Ln(5.5);
$PDF->Cell(3,1,'3.','C'); 
$PDF->Cell(6);
$PDF->Cell(3,1,'Ne pas utiliser de pesticides, d\'herbicides ou d\'engrais synthétiques sur toutes les cultures ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'dans mes champs certifiés biologiques ou en conversion ;','C');
$PDF->Ln(5.5);
$PDF->Cell(3,1,'4.','C'); 
$PDF->Cell(6);
$PDF->Cell(3,1,'Maintenir au moins les principes organiques suivants :','C'); 
$PDF->Ln(5.5);
$PDF->Cell(15);
$PDF->Cell(3,1,chr(127),'C');
$PDF->Cell(4);
$PDF->Cell(3,1,'Suivre le règlement interne biologique ;','C');
$PDF->Ln(5.5);
$PDF->Cell(15);
$PDF->Cell(3,1,chr(127),'C');
$PDF->Cell(4);
$PDF->Cell(3,1,'Maintenir et améliorer la fertilité des sols en paillant tous les résidus de culture (pas de ','C');
$PDF->Ln(5.5);
$PDF->Cell(22);
$PDF->Cell(3,1,'brûlage) et en appliquant de la matière organique, du compost, du fumier, de l\'engrais ','C');
$PDF->Ln(5.5);
$PDF->Cell(22);
$PDF->Cell(3,1,'vert et/ou d\'autres techniques ;','C');
$PDF->Ln(5.5);
$PDF->Cell(15);
$PDF->Cell(3,1,chr(127),'C');
$PDF->Cell(4);
$PDF->Cell(3,1,'Prévenir l\'érosion du sol en gardant le sol couvert en tout temps, en construisant des ','C');
$PDF->Ln(5.5);
$PDF->Cell(22);
$PDF->Cell(3,1,'bordures de contour si nécessaire ;','C');
$PDF->Ln(5.5);
$PDF->Cell(15);
$PDF->Cell(3,1,chr(127),'C');
$PDF->Cell(4);
$PDF->Cell(3,1,'Éviter la dégradation de l\'environnement : abattage d\'arbres inutilement, brûlage des ','C');
$PDF->Ln(5.5);
$PDF->Cell(22);
$PDF->Cell(3,1,'restes de récolte ou de toute autre matière organique ; le déversement de matières ','C');
$PDF->Ln(5.5);
$PDF->Cell(22);
$PDF->Cell(3,1,'toxiques (batteries) ou la combustion de plastiques ;','C');
$PDF->Ln(5.5);
$PDF->Cell(3,1,'5.','C'); 
$PDF->Cell(6);
$PDF->Cell(3,1,'Faire en sorte qu\'aucune contamination des champs ou des cultures certifiées ne puisse avoir','C'); 
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'lieu, par exemple par dérive des champs voisins en matérialisant des zones tampon avec des ','C');
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'plantes ou en réservant 7% de la surface agricole pour l\'amélioration de la biodiversité ;','C');
$PDF->Ln(5.5);
$PDF->Cell(3,1,'6.','C'); 
$PDF->Cell(6);
$PDF->Cell(3,1,'Ne cultiver aucune noix de coco conventionnelle afin d\'éviter la production parallèle ;','C'); 
$PDF->Ln(5.5);
$PDF->Cell(3,1,'7.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Suivre les règles de la production biologique également dans mes champs pour ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'l\'autoconsommation ;','C');
$PDF->Ln(5.5);
$PDF->Cell(3,1,'8.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Assurer que les exigences de la charte sociale interne s\'appliqueront à chaque membre de la ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'famille, employé, travailleur saisonnier ou journalier, par exemple en ce qui concerne les ','C');
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'droits de l\'homme, les droits de l\'enfant, les exigences de santé et de sécurité et les salaires. ','C');
$PDF->Ln(5.5);
$PDF->Cell(3,1,'9.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Vendre uniquement la production biologique de ses champs biologiques à PMCI Sarl ; ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(3,1,'10.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Suivre le programme de formation en gestion biologique organisé par PMCI Sarl; ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(3,1,'11.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Signaler toute violation du règlement interne biologique à l\'inspecteur interne ou à une ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'autre personne responsable de PMCI ;','C');
$PDF->Ln(5.5);
$PDF->Cell(3,1,'12.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'A comprendre que toute violation des principes biologiques, même par un seul producteur, ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'entraînera l\'exclusion de ce producteur ou de l\'ensemble du groupe. Je comprends que je ','C');
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'serai sanctionné pour les écarts ;','C');
$PDF->Ln(5.5);
$PDF->Cell(3,1,'13.','C'); 
$PDF->Cell(7);
$PDF->Cell(3,1,'Autoriser les inspections par des personnes autorisées par PMCI Sarl  et/ou par l’organisme ','C'); 
$PDF->Ln(5.5);
$PDF->Cell(10);
$PDF->Cell(3,1,'de certification et donnerai accès aux champs, magasins et documents.','C');
$PDF->Ln(10);
$PDF->Cell(107); 

setlocale (LC_TIME, 'fr_FR.utf8','fra');
$date = strftime( "%d %B %Y" , strtotime($doc_date) );
$PDF->Cell(3,1,'Fait Adiaké, le '. utf8_decode($date) .' (en deux exemplaires)','C');


$PDF->SetFont('Arial','B',11);
$PDF->Ln(13);
$PDF->Cell(3,1,'Pour PMCI Sarl','C');
$PDF->Cell(147);
$PDF->Cell(3,1,'Pour le Producteur','C');

$PDF->SetFont('Arial','',11);
// $PDF->Ln(7);
 

$PDF->Ln(16);

// $PDF->Cell(4);
$PDF->Cell(3,1,'Direction','C');
$PDF->Cell(0,0,$lastname .' '. $firstname,0,0,'R');
// $PDF->Cell(147);
// $PDF->Cell(3,1,'« Lu et approuvé »','C');


if($save == 1){
	$sqlP="SELECT id_contact/10000+to_char(now(),'MMDDHHMISS')::integer AS new_id FROM v_icw_contacts WHERE id_contact = $id_contact";
	$rstP = pg_query($conn, $sqlP);
	$arrP = pg_fetch_assoc($rstP);
	
	if(!empty($arrP['new_id'])) {
		$new_id = $arrP['new_id'];
		$sql_save = "INSERT INTO public.plantation_docs(
			id_plantdoc, plantation_id, doc_link, doc_type, doc_date, description)
		VALUES ($new_id, $id_plantation, '$doc_link', 1167, '$doc_date', 'CONTRAT D''ENGAGEMENT PMCI - PRODUCTEUR A LA PRODUCTION BIOLOGIQUE DE NOIX DE COCO')";

		pg_query($conn, $sql_save);
	}
	
	$pdfFile=$_SERVER['DOCUMENT_ROOT']."/ic/img/engagement_pmci/".$doc_link;
	$PDF->Output($pdfFile,'F');
}
	
$PDF->Output();

}

?>
