<?php     
ob_start();
include('PDFheaderfooter_gg_certification.php');
include_once("../fcts.php");
$conn=connect();

if (isset($_GET['id_contact'])) { $id_contact=$_GET['id_contact'];  }


if(!empty($id_contact) 
){

$sql_cont = "SELECT 
	name_town, civil_status, firstname, lastname, contact_code, postalcode, phone1
FROM v_icw_contacts WHERE id_contact=$id_contact";
$result_cont = pg_query($conn, $sql_cont);
$arr_cont = pg_fetch_assoc($result_cont);

$name_town = $arr_cont['name_town'];
$civil_status = $arr_cont['civil_status'];
$firstname = $arr_cont['firstname'];
$lastname = $arr_cont['lastname'];
$contact_code = $arr_cont['contact_code'];
$postalcode = $arr_cont['postalcode'];
if($postalcode!=""){
	$code_posal = $postalcode . ' | ';
} else { $code_posal = ""; }

$phone1 = $arr_cont['phone1'];


// Instanciation de la classe dérivée
$PDF = new PDF();
$PDF->AliasNbPages();
$PDF->AddPage();


$PDF->Ln(15);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Numéro d\'ordre dans le registre de producteur: ............'),'C');

$PDF->Ln(10);
$PDF->Cell(2);
$PDF->Cell(3,1,utf8_decode('Nom du porteur du certificat'),'C');
$PDF->Rect(5,42,85,10,'');

$PDF->Cell(80);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode("Plantations Modernes de Côte d'Ivoire (PMCI)"),'C');
$PDF->Rect(90,42,115,10,'');

$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Identification fiscale'),'C');
$PDF->Rect(5,52,85,10,'');

$PDF->Cell(80);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode('SARL'),'C');
$PDF->Rect(90,52,115,10,'');  

$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Nom du producteur'),'C');
$PDF->Rect(5,62,85,10,'');  

$PDF->Cell(80);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode($firstname.' '.$lastname),'C');
$PDF->Rect(90,62,115,10,'');  

$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Code du producteur '),'C');
$PDF->Rect(5,72,85,10,'');  

$PDF->Cell(80);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode($contact_code),'C');
$PDF->Rect(90,72,115,10,'');  

$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Adresse et coordonnées du producteur'),'C');
$PDF->Rect(5,82,85,10,'');  

$PDF->Cell(80);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode('10 BP 2229 ABIDJAN 10 | ') . $phone1,'C');
$PDF->Rect(90,82,115,10,'');  


$PDF->Ln(10);
$PDF->Cell(2);
$PDF->SetFont('Arial','',12);
$PDF->Cell(3,1,utf8_decode('Situation matrimoniale'),'C');
$PDF->Rect(5,92,85,10,'');  

$PDF->Cell(80);
$PDF->SetFont('Arial','B',12);
$PDF->Cell(3,1,utf8_decode(getRegvalues($civil_status, 'fr')),'C');
$PDF->Rect(90,92,115,10,'');  


$PDF->Ln(10);
$PDF->Cell(5);
$PDF->SetFont('Arial','',9);
$PDF->Cell(3,1,utf8_decode('Code Parcelle'),'C');
$PDF->Rect(5,102,50,10,'');  

$PDF->Cell(55);
$PDF->Cell(3,1,utf8_decode('Localisation'),'C');
$PDF->Rect(55,102,50,10,'');   

$PDF->Cell(43);
$PDF->Cell(3,1,utf8_decode('Année de création'),'C');
$PDF->Rect(105,102,50,10,'');  

$PDF->Cell(53);
$PDF->Cell(3,1,utf8_decode('Superficie'),'C');
$PDF->Rect(155,102,50,10,'');  


$PDF->Ln(10);
$PDF->Cell(75);
$PDF->SetFont('Arial','B',9);
$PDF->SetFillColor(192);
$PDF->Rect(5,112,200,10,'F'); 
$PDF->Cell(3,1,utf8_decode('Site de plantation de noix de COCO'),'C'); 


$PDF->Ln(5);

$tabhcont[0] = 50;
$tabhcont[1] = 50;
$tabhcont[2] = 50;
$tabhcont[3] = 50;
$tabhcont[4] = "[LB]";
$tabhcont[5] = "[LB]";
$tabhcont[6] = "[LB]";
$tabhcont[7] = "[LB]";

$number_staff_temporary = 0;  
$number_staff_permanent = 0;

$sql_plant = "SELECT code_parcelle, estimate_production,
	number_staff_temporary, number_staff_permanent, surface_ha, year_creation, name_town
FROM v_plantation WHERE id_contact=$id_contact";
$result_plant = pg_query($conn, $sql_plant);

while($arr_plant = pg_fetch_assoc($result_plant)) {
	$tabvalues[]="[C]".utf8_decode($arr_plant['code_parcelle']);
	$tabvalues[]="[C]".utf8_decode($arr_plant['name_town']);
	$tabvalues[]="[C]".utf8_decode($arr_plant['year_creation']);
	$tabvalues[]="[C]".number_format($arr_plant['surface_ha'],4).' ha';
	
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
	


$PDF->Ln(10);
$PDF->SetFont('Arial','B',9);
$PDF->Cell(3,1,utf8_decode("La société PMCI SARL s'engage à :"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->SetFont('Arial','',9);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Informer et former les producteurs membres aux référentiels GLOBAL GAP, GRASP et SMETA"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Élaborer le Règlement de Contrôle Interne et la liste des différentes sanctions en cas de"),'C');
$PDF->Ln(5);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("non-conformités."),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Mettre en oeuvre un Système de Management Qualité (QMS) pour la certification GLOBAL GAP,"),'C');
$PDF->Ln(5);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("GRASP et SMETA"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Inspecter au moins une fois par an chacune des plantations de ses producteurs membres."),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Assurer la mise à jour et la diffusion des documents du Système de Management de Qualité"),'C');
$PDF->Ln(5);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("(Procédures, Chartes, Fiches, registres, etc.)."),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Faire respecter les sanctions qu'elle émet et celles de l'organisme de certification, chargée de réaliser"),'C');
$PDF->Ln(5);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("le contrôle externe."),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Garantir le respect des exigences GLOBAL GAP, GRASP et SMETA relatives au conditionnement"),'C');
$PDF->Ln(5);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("et la traçabilité des noix de coco"),'C');

$PDF->SetLineWidth(.6);
$PDF->SetDrawColor(0,0,0);
$PDF->Line(2,246,2,36);



$PDF->AddPage();

$PDF->Ln(20);
$PDF->SetFont('Arial','B',9);
$PDF->Cell(3,1,utf8_decode("Le producteur membre s'engage à :"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->SetFont('Arial','',9);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Se conformer aux exigences des référentiels GLOBAL GAP, GRASP et SMETA"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Respecter toutes les procédures documentées de la société PMCI SARL qui seront mises à sa"),'C');
$PDF->Ln(5);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("disposition,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Respecter toutes les politiques et chartes de la société PMCI SARL qui seront mises à sa disposition,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Respecter tous les conseils techniques des agents encadreurs de la société PMCI SARL,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Respecter les bonnes pratiques agricoles dans sa plantation de noix de coco,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Respecter la sécurité sanitaire de sa production de noix de coco,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Eviter de polluer les ressources naturelles telles que l'eau, le sol et l'air par ses pratiques,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Utiliser les produits phytosanitaires homologués en Côte d'Ivoire et informer les agents de la"),'C');
$PDF->Ln(5);
$PDF->Cell(12);
$PDF->Cell(3,1,utf8_decode("société PMCI SARL avant toute manipulation et épandage"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Ne pas avoir recours au feu lors de la préparation des sols et toutes autres activités,"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Ne pas s'inscrire dans des pratiques telles que : le travail et les pires formes de travail des enfants, la"),'C');
$PDF->Ln(5);
$PDF->Cell(10);
$PDF->Cell(3,1,utf8_decode("discrimination sexuelle et raciale, le harcèlement en milieu du travail."),'C');

$PDF->Ln(12);
$PDF->Cell(10);
$PDF->Cell(3,1,utf8_decode("Il s'engage en outre à vendre toute sa production de noix de coco à la société PMCI SARL."),'C');

$PDF->Ln(20);
$PDF->Cell(10);
$PDF->Cell(3,1,utf8_decode("En cas de non-respect des exigences GLOBAL GAP, GRASP, SMETA et des conditions internes à la société PMCI"),'C');

$PDF->Ln(10);
$PDF->Cell(10);
$PDF->Cell(3,1,utf8_decode("SARL ; le producteur recevra :"),'C');


$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Un avertissement"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Une Suspension"),'C');

$PDF->Ln(7);
$PDF->Cell(10);
$PDF->Cell(3,1,chr(127) . utf8_decode(" Et voir même un retrait du registre interne des producteurs de noix de coco."),'C');


$PDF->SetLineWidth(.6);
$PDF->SetDrawColor(0,0,0);
$PDF->Line(2,196,2,36);


$PDF->Ln(35);
$PDF->SetFont('Arial','B',10);
$PDF->Cell(3,1,utf8_decode("Date et signature du producteur"),'C');
$PDF->Cell(115);
$PDF->Cell(3,1,utf8_decode("Responsable Agronomique"),'C');




$filename=$_SERVER['DOCUMENT_ROOT']."/ic/img/certification_document_2/".$id_contact.".pdf";
$PDF->Output($filename,'F');

$doc_link = $id_contact.".pdf";
$doc_date = gmdate("Y/m/d H:i");

$sqlP="SELECT id_contact/10000+to_char(now(),'MMDDHHMISS')::integer AS new_id FROM v_icw_contacts WHERE id_contact = $id_contact";
$rstP = pg_query($conn, $sqlP);
$arrP = pg_fetch_assoc($rstP);
	
$new_id = $arrP['new_id'];


$sql_stats = "INSERT INTO public.contact_docs(
	id_condoc, contact_id, doc_link, doc_type, doc_date)
VALUES ($new_id, $id_contact, '$doc_link', 809, '$doc_date')";

$result = pg_query($conn, $sql_stats);

$PDF->Output();

}

?>
