<?php     
ob_start();
include('PDFheaderfooter_gg_certification.php');
include_once("../fcts.php");
$conn=connect();

if (isset($_GET['id_contact'])) { $id_contact=$_GET['id_contact'];  }


if(!empty($id_contact) 
){

$sql_cont = "SELECT 
	name_town, civil_status, firstname, lastname, contact_code
FROM v_icw_contacts WHERE id_contact=$id_contact";
$result_cont = pg_query($conn, $sql_cont);
$arr_cont = pg_fetch_assoc($result_cont);

$name_town = $arr_cont['name_town'];
$civil_status = $arr_cont['civil_status'];
$firstname = $arr_cont['firstname'];
$lastname = $arr_cont['lastname'];
$contact_code = $arr_cont['contact_code'];


// Instanciation de la classe dérivée
$PDF = new PDF();
$PDF->AliasNbPages();
$PDF->AddPage();


$PDF->Ln(25);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Nom du porteur du certificat'),'C');
$PDF->Rect(5,42,80,10,'');

$PDF->Cell(75);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode("Plantations Modernes de Côte d'Ivoire (PMCI)"),'C');
$PDF->Rect(85,42,120,10,'');

$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Adresse du porteur du certificat'),'C');
$PDF->Rect(5,52,80,10,'');

$PDF->Cell(75);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode('Adiaké'),'C');
$PDF->Rect(85,52,120,10,'');  

$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Nom du planteur'),'C');
$PDF->Rect(5,62,80,10,'');  

$PDF->Cell(75);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode($firstname.' '.$lastname),'C');
$PDF->Rect(85,62,120,10,'');  

$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Code/numéro du planteur '),'C');
$PDF->Rect(5,72,80,10,'');  

$PDF->Cell(75);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode($contact_code),'C');
$PDF->Rect(85,72,120,10,'');  

$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Adresse du planteur '),'C');
$PDF->Rect(5,82,80,10,'');  

$PDF->Cell(75);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode($name_town ),'C');
$PDF->Rect(85,82,120,10,'');  


$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Situation matrimoniale'),'C');
$PDF->Rect(5,92,80,10,'');  

$PDF->Cell(75);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode(getRegvalues($civil_status, 'fr')),'C');
$PDF->Rect(85,92,120,10,'');  


$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',9);
$PDF->Cell(3,1,utf8_decode('N° Parcelle'),'C');
$PDF->Rect(5,102,30,10,'');  

$PDF->Cell(25);
$PDF->Cell(3,1,utf8_decode('Localisation'),'C');
$PDF->Rect(35,102,30,10,'');   

$PDF->Cell(27);
$PDF->Cell(3,1,utf8_decode('Année de création'),'C');
$PDF->Rect(65,102,40,10,'');  

$PDF->Cell(37);
$PDF->Cell(3,1,utf8_decode('Superficie'),'C');
$PDF->Rect(105,102,30,10,'');  

$PDF->Cell(27);
$PDF->Cell(3,1,utf8_decode('Nombre de pieds'),'C');
$PDF->Rect(135,102,35,10,'');  

$PDF->Cell(30);
$PDF->Cell(3,1,utf8_decode('Production annuelle'),'C');
$PDF->Rect(170,102,35,10,'');


$PDF->Ln(10);
$PDF->Cell(75);
$PDF->SetFont('Arial','B',9);
$PDF->SetFillColor(192);
$PDF->Rect(5,112,200,10,'F'); 
$PDF->Cell(3,1,utf8_decode('Parcelles de COCO'),'C'); 


$PDF->Ln(5);

$tabhcont[0] = 30;
$tabhcont[1] = 30;
$tabhcont[2] = 40;
$tabhcont[3] = 30;
$tabhcont[4] = 35;
$tabhcont[5] = 35;
$tabhcont[6] = "[LB]";
$tabhcont[7] = "[LB]";
$tabhcont[8] = "[LB]";
$tabhcont[9] = "[LB]";
$tabhcont[10] = "[LB]";
$tabhcont[11] = "[LB]";

$number_staff_temporary = 0;  
$number_staff_permanent = 0;

$sql_plant = "SELECT code_parcelle, estimate_production,
	number_staff_temporary, number_staff_permanent, surface_ha, year_creation, name_town
FROM v_plantation WHERE id_contact=$id_contact";
$result_plant = pg_query($conn, $sql_plant);

while($arr_plant = pg_fetch_assoc($result_plant)) {
	$tabvalues[]="[L]".utf8_decode($arr_plant['code_parcelle']);
	$tabvalues[]="[L]".utf8_decode($arr_plant['name_town']);
	$tabvalues[]="[L]".utf8_decode($arr_plant['year_creation']);
	$tabvalues[]="[L]".number_format($arr_plant['surface_ha'],4).' ha';
	$tabvalues[]="[L]";
	$tabvalues[]="[L]".utf8_decode($arr_plant['estimate_production']);
	
	$number_staff_temporary = $arr_plant['number_staff_temporary'] + $number_staff_temporary;  
	$number_staff_permanent = $arr_plant['number_staff_permanent'] + $number_staff_permanent;
}

$proprietesTableau = array(
	'TB_ALIGN' => 'R',
	'L_MARGIN' => 5,
	'BRD_COLOR' => array(0,0,0),
	'BRD_SIZE' => '0.2',
);
// Définition des propriétés du header du tableau.
$proprieteHeader = array(
	'T_COLOR' => array(0,0,0),
	'T_SIZE' => 7,
	'T_FONT' => 'Arial',
	'T_ALIGN' => 'L',
	'V_ALIGN' => 'M',
	'T_TYPE' => 'B',
	'LN_SIZE' => 10,
	'BG_COLOR_COL0' => array(195, 195, 195),
	'BG_COLOR' => array(195, 195, 195),
	'BRD_COLOR' => array(0,0,0),
	'BRD_SIZE' => 0.2,
	'BRD_TYPE' => '1',
	'BRD_TYPE_NEW_PAGE' => '',
);
// Contenu du header du tableau.
//$contenuHeader =$tabhcont;
   
// Définition des propriétés du reste du contenu du tableau.
$proprieteContenu = array(
	'T_COLOR' => array(0,0,0),
	'T_SIZE' => 8,
	'T_FONT' => 'HelveticaNeueLight',
	'T_ALIGN_COL0' => 'L',
	'T_ALIGN' => 'L',
	'V_ALIGN' => 'T',
	'T_TYPE' => '',
	'LN_SIZE' => 10,
	'BG_COLOR_COL0' => array(255,255,255),
	'BG_COLOR' => array(255,255,255),
	'BRD_COLOR' => array(0,0,0),
	'BRD_SIZE' => 0.1,
	'BRD_TYPE' => '1',
	'BRD_TYPE_NEW_PAGE' => '',
);

$PDF->drawTableau($PDF, $proprietesTableau, $proprieteHeader, $tabhcont, $proprieteContenu,$tabvalues);


$tabhcont2[0] = 80;
$tabhcont2[1] = 120;
$tabhcont2[2] = "[LB]";
$tabhcont2[3] = "[LB]";

$tabvalues2[]="[L]".utf8_decode("Type de main d'oeuvre et nombre");
$tabvalues2[]="[L]".utf8_decode("Permanent : ".$number_staff_permanent." \n Journalier : ".$number_staff_temporary);


$proprietesTableau2 = array(
	'TB_ALIGN' => 'R',
	'L_MARGIN' => 5,
	'BRD_COLOR' => array(0,0,0),
	'BRD_SIZE' => '0.2',
);
// Définition des propriétés du header du tableau.
$proprieteHeader2 = array(
	'T_COLOR' => array(0,0,0),
	'T_SIZE' => 7,
	'T_FONT' => 'Arial',
	'T_ALIGN' => 'L',
	'V_ALIGN' => 'M',
	'T_TYPE' => 'B',
	'LN_SIZE' => 10,
	'BG_COLOR_COL0' => array(195, 195, 195),
	'BG_COLOR' => array(195, 195, 195),
	'BRD_COLOR' => array(0,0,0),
	'BRD_SIZE' => 0.2,
	'BRD_TYPE' => '1',
	'BRD_TYPE_NEW_PAGE' => '',
);
// Contenu du header du tableau.
//$contenuHeader =$tabhcont;
   
// Définition des propriétés du reste du contenu du tableau.
$proprieteContenu2 = array(
	'T_COLOR' => array(0,0,0),
	'T_SIZE' => 10,
	'T_FONT' => 'HelveticaNeueLight',
	'T_ALIGN_COL0' => 'L',
	'T_ALIGN' => 'L',
	'V_ALIGN' => 'T',
	'T_TYPE' => '',
	'LN_SIZE' => 6,
	'BG_COLOR_COL0' => array(255,255,255),
	'BG_COLOR' => array(255,255,255),
	'BRD_COLOR' => array(0,0,0),
	'BRD_SIZE' => 0.1,
	'BRD_TYPE' => '1',
	'BRD_TYPE_NEW_PAGE' => '',
);

$PDF->drawTableau($PDF, $proprietesTableau2, $proprieteHeader2, $tabhcont2, $proprieteContenu2, $tabvalues2);	
	


$PDF->Ln(20);
$PDF->Cell(3,1,utf8_decode("En tant que planteur, je déclare que je comprends les exigences des règlements ci-dessous :"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" GLOBAL GAP Modalités Générales / Partie I - Exigences Générales"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" GLOBAL GAP Modalités Générales / Partie II - Règles du Système de Gestion de la Qualité"),'C');
$PDF->Ln(7);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("(Règles QMS) version 5.2"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" GLOBAL GAP Système Raisonné de culture et d'élévage / Module applicable à l'ensemble"),'C');
$PDF->Ln(7);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("des exploitations version 5.2"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" GLOBALG.A.P. Évaluation des risques sur la pratique sociale (GRASP) version 1.3"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Critères de mesure de l'audit éthique du commerce des membres de Sedex (SMETA)"),'C');



$PDF->AddPage();

$PDF->Ln(25);
$PDF->Cell(3,1,utf8_decode("1- Je m'engage à me conformer aux exigences du référentiel GLOBALG.A.P. suivantes :"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Respecter et appliquer des bonnes pratiques agricoles,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Respecter la traçabilité,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Respecter les consignes pour la maitrise des risques de contamination,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Respecter la sécurité sanitaire,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Eviter de polluer les ressources naturelles telles que l'eau, le sol et l'air,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Lutter contre les ravageurs et les maladies par des voies qui s'inscrivent dans le plan de gestion"),'C');
$PDF->Ln(7);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("intégrée des nuisibles,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Non utilisation de substances interdites comme les herbicides et les fertilisants non enregistrés "),'C');
$PDF->Ln(7);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("et non autorisés en côte d'ivoire,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Promotion de l'utilisation des substances autorisées par des méthodes qui réduisent les risques "),'C');
$PDF->Ln(7);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("pour la santé des personnes et de l'environnement,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Non recours au feu pour la préparation des sols,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Interdiction de souscrire à des pratiques telles que le travail et les pires formes de travail des "),'C');
$PDF->Ln(7);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("enfants, la discrimination sexuelle et raciale, le harcèlement en milieu du travail et l'atteinte à la "),'C');
$PDF->Ln(7);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("capacité de reproduction des femmes,"),'C');

$PDF->Ln(12);
$PDF->Cell(3,1,utf8_decode("2- Je m'engage au respect des procédures documentées de PMCI, de ses politiques Qualité, d'hygiène, "),'C');
$PDF->Ln(7);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("Sécurité et, le cas échéant, de ses conseils techniques"),'C');


$PDF->Ln(12);
$PDF->Cell(3,1,utf8_decode("3- Je déclare que :"),'C');
// $PDF->Ln(7);
// $PDF->Cell(12);
// $PDF->Cell(3,1,utf8_decode("Sécurité et, le cas échéant, de ses conseils techniques"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" La carte détaillée de la plantation a été réalisée par PMCI et est disponible."),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Une tenue du carnet planteur de tous les produits intrants et sortant est disponible."),'C');


$PDF->Ln(12);
$PDF->Cell(3,1,utf8_decode("4- Je m'engage aux respects des sanctions susceptibles d'être appliquées en cas de non-respect des "),'C');
$PDF->Ln(7);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("exigences GLOBALG.A.P. et éventuellement d'autres conditions internes. "),'C');

$PDF->Ln(35);
$PDF->SetFont('Arial','B',10);
$PDF->Cell(3,1,utf8_decode("Date et signature du planteur"),'C');
$PDF->Cell(115);
$PDF->Cell(3,1,utf8_decode("Date et signature du Chef de projet"),'C');




$filename=$_SERVER['DOCUMENT_ROOT']."/ic/img/certification_document/".$id_contact.".pdf";
$PDF->Output($filename,'F');

$doc_link = $id_contact.".pdf";
$doc_date = gmdate("Y/m/d H:i");

$sqlP="SELECT id_contact/10000+to_char(now(),'MMDDHHMISS')::integer AS new_id FROM v_icw_contacts WHERE id_contact = $id_contact";
$rstP = pg_query($conn, $sqlP);
$arrP = pg_fetch_assoc($rstP);
	
$new_id = $arrP['new_id'];


$sql_stats = "INSERT INTO public.contact_docs(
	id_condoc, contact_id, doc_link, doc_type, doc_date)
VALUES ($new_id, $id_contact, '$doc_link', 654, '$doc_date')";

$result = pg_query($conn, $sql_stats);

$PDF->Output();

}

?>
