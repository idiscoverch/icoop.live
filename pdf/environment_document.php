<?php     
ob_start();
include('PDFheaderfooter.php');
include_once("../fcts.php");
$conn=connect();            


if (isset($_GET['id_plantation'])) { $id_plantation=$_GET['id_plantation']; }

if(!empty($id_plantation) 
){
	
$sql_plant = "SELECT *, to_char(modified_date, 'dd/mm/yyyy') AS modified_date_fr,
	to_char(cert_approved_date, 'dd/mm/yyyy') AS cert_approved_date_fr,
	get_contact_name(cert_approved_by) AS cert_approved_name
 FROM v_plantation WHERE gid_plantation=$id_plantation";

$result_plant = pg_query($conn, $sql_plant);
$arr_plant = pg_fetch_assoc($result_plant);

if(!empty($arr_plant['modified_date_fr'])) {
	$modified_date = $arr_plant['modified_date_fr'];
} else { $modified_date = ""; }


$name_farmer = $arr_plant['name_farmer'];
$code_farmer = $arr_plant['code_farmer'];
$name_town = $arr_plant['name_town'];
$zone = $arr_plant['zone'];
$code_parcelle = $arr_plant['code_parcelle'];
$cert_approved_date = $arr_plant['cert_approved_date_fr'];
$cert_approved_name = $arr_plant['cert_approved_name']; 

$synthetic_fertlizer = $arr_plant['synthetic_fertilizer'];
$synthetic_herbicide = $arr_plant['synthetic_herbicides'];
$synthetic_pesticide = $arr_plant['synthetic_pesticide'];
$adjoining_cultures = $arr_plant['adjoining_cultures'];
$forest = $arr_plant['forest'];
$fire = $arr_plant['fire'];
$eco_river = $arr_plant['eco_river'];
$eco_shallows = $arr_plant['eco_shallows'];
$eco_wells = $arr_plant['eco_wells'];
$sewage = $arr_plant['sewage'];
$waste = $arr_plant['waste'];
$rating = $arr_plant['rating'];
$perimeter = $arr_plant['perimeter'];

$sqlP="SELECT id_farmer/10000+to_char(now(),'MMDDHHMISS')::integer AS id_farmer FROM v_plantation WHERE gid_plantation = $id_plantation";
$rstP = pg_query($conn, $sqlP);
$arrP = pg_fetch_assoc($rstP);
	
$new_id = $arrP['id_farmer'];


// Instanciation de la classe dérivée
$PDF = new PDF();
$PDF->AliasNbPages();
$PDF->AddPage();

$PDF->SetFont('HelveticaNeueLight','',14);
$PDF->Image('./../img/PMCI-logo_200px.jpg',10,5);
$PDF->Ln(4);
$PDF->Cell(60);
$PDF->Cell(3,1,utf8_decode("Plantations Modernes de Côte d'Ivoire"),'C');
$PDF->SetLineWidth(0.2);
$PDF->Line(71, 18, 195, 18);

$PDF->Ln(17);
$PDF->SetFont('Arial','B',14);
$PDF->Cell(3,1,utf8_decode("Plan d'Action suite à l'inspection initiale du ").$modified_date,'C');

$PDF->Ln(9.5);
$PDF->SetFont('HelveticaNeueLight','',9);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(4);
$PDF->Cell(3,1,'Nom du planteur :','C');
$PDF->Cell(75);
$PDF->Cell(3,1,'Code planteur :','C');
$PDF->Ln(5); 
$PDF->Cell(4);
$PDF->SetFont('HelveticaNeueLight','',11);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,utf8_decode($name_farmer),'C');
$PDF->Cell(75);
$PDF->Cell(3,1,$code_farmer,'C');
$PDF->Cell(75);
$PDF->SetFont('HelveticaNeueLight','',9);
$PDF->SetTextColor(103,106,108);
$PDF->Cell(3,1,'Code plantation :','C');

$PDF->Rect(11,37,75,12,''); 
$PDF->Rect(86,37,75,12,'');

$PDF->Ln(7);
$PDF->Cell(4);
$PDF->Cell(3,1,'Zone :','C');
$PDF->Cell(75);
$PDF->Cell(3,1,utf8_decode('Localité :'),'C');
$PDF->Cell(74);
$PDF->SetFont('HelveticaNeueLight','',11);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(3,1,$code_parcelle,'C');
$PDF->Ln(5); 
$PDF->Cell(4);
$PDF->Cell(3,1,utf8_decode($zone),'C');
$PDF->Cell(75);
$PDF->Cell(3,1,utf8_decode($name_town),'C');

$PDF->Rect(11,49,75,12,''); 
$PDF->Rect(86,49,75,12,'');

$PDF->Rect(161,37,40,24,'');

$PDF->Ln(17);
$PDF->SetFont('Arial','UB',10);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(4);
$PDF->Cell(3,1,utf8_decode("Constats fait à l'inspection"),'C');
$PDF->Cell(65);   
$PDF->Cell(3,1,utf8_decode("Résultats"),'C');
$PDF->Cell(45);
$PDF->Cell(3,1,utf8_decode("Actions à mettre en oeuvre"),'C');
$PDF->SetFont('HelveticaNeueLight','',11);
$PDF->SetTextColor(0,0,0);

// $PDF->Rect(11,61,75,24,''); 
// $PDF->Rect(86,61,115,24,'');


$PDF->Ln(7);

$tabhcont[0] = 65;
$tabhcont[1] = 45;
$tabhcont[2] = 80;
$tabhcont[3] = "[LB]";
$tabhcont[4] = "[LB]";
$tabhcont[5] = "[LB]";

// Chargement des données
if(($synthetic_fertlizer!="") AND ($synthetic_fertlizer!=188)) {
	$tabvalues[]="[L]".utf8_decode("Utilisation d'engrais chimique");
	$tabvalues[]="[L]".utf8_decode(getRegvalues($synthetic_fertlizer, 'fr'));
	$tabvalues[]="[L]".utf8_decode("Interdiction d'utilisation d'engrais chimiques, informer les agents PMCI pour tout besoin d'utilisation d'engrais pour une assistance (produits homologués)");
}

if(($synthetic_herbicide!="") AND ($synthetic_herbicide!=590)) {
	$tabvalues[]="[L]".utf8_decode("Utilisation d'herbicide chimique ");
	$tabvalues[]="[L]".utf8_decode(getRegvalues($synthetic_herbicide, 'fr'));
	$tabvalues[]="[L]".utf8_decode("Interdiction d'utilisation d'herbicides chimiques, informer les agents PMCI pour tout besoin d'utilisation d'herbicide chimique pour une assistance (produits homologués)");
}

if(($synthetic_pesticide!="") AND ($synthetic_pesticide!=191)) {
	$tabvalues[]="[L]".utf8_decode("Utilisation de pesticides chimique");
	$tabvalues[]="[L]".utf8_decode(getRegvalues($synthetic_pesticide, 'fr'));
	$tabvalues[]="[L]".utf8_decode("Interdiction d'utilisation de pesticides, informer les agents PMCI pour tout besoin d'utilisation d'engrais pour une assistance (produits homologués)");
}

if($adjoining_cultures!="") {
	$adj_cultures="";
	$ad_culturesData = explode(",", $adjoining_cultures);
	$lenghtC = sizeof($ad_culturesData);
	
	for ($i = 0; $i < $lenghtC; $i++) {
		if($i == ($lenghtC - 1)){
			$adj_cultures.=getRegvalues($ad_culturesData[$i], 'fr');
		} else {
			$adj_cultures.=getRegvalues($ad_culturesData[$i], 'fr').', ';
		}
	}
	
	$tabvalues[]="[L]".utf8_decode("Culture pérenne environnante ");
	$tabvalues[]="[L]".utf8_decode($adj_cultures);
	$tabvalues[]="[L]".utf8_decode("Matérialisation de la zone tampon, respect de la zone tampon, éviter de mélanger les produits de la zone tampon à la production de la plantation");
}

if(($forest!="") AND ($forest!=509)) {
	$tabvalues[]="[L]".utf8_decode("Présence de forêt aux alentours ");
	$tabvalues[]="[L]".utf8_decode(getRegvalues($forest, 'fr'));
	$tabvalues[]="[L]".utf8_decode("Protection de forêt, éviter les défrichages, éviter la destruction de la forêt");
}

if(($fire!="") AND ($fire!=509)) {
	$tabvalues[]="[L]".utf8_decode("Présence de trace de feu");
	$tabvalues[]="[L]".utf8_decode(getRegvalues($fire, 'fr'));
	$tabvalues[]="[L]".utf8_decode("Interdiction de feu en plantation les déchets végétaux doivent être compostés, faire enlever la bourre par des particuliers, les déchets solides doivent être enlevés de la plantation");
}

if(($eco_river="") AND ($eco_river!=0)) {
	$tabvalues[]="[L]Présence de cours d'eau";
	$tabvalues[]="[L]Oui";
	$tabvalues[]="[L]".utf8_decode("Protection des cours d'eau par une zone tampon, éviter les déversements de produits, la pollution du cours d'eau");
}

if(($eco_shallows="") AND ($eco_shallows!=0)) {
	$tabvalues[]="[L]Mares";
	$tabvalues[]="[L]Oui";
	$tabvalues[]="[L]".utf8_decode("Protection de la mare par une zone tampon, éviter les déversements de produits, la pollution par des déchets. \n Eviter la stagnation des eaux dans les plantations, faire le drainage des eaux de pluies.");
}

if(($eco_wells="") AND ($eco_wells!=0)) {
	$tabvalues[]="[L]Puits";
	$tabvalues[]="[L]Oui";
	$tabvalues[]="[L]".utf8_decode("Protection du puit par un couvercle, éviter la contamination par des produits de l'eau du puit");
}

// if($perimeter==1) {
	// $tabvalues[]="[L]Zone Tampon";
	// $tabvalues[]="[L]Oui";
	// $tabvalues[]="[L]".utf8_decode("Matérialisation de la zone tampon, respect de la zone tampon, éviter de mélanger les produits de la zone tampon à la production de la plantation");
// }

// $sewage

if(($waste="") AND ($waste!=509)) {
	$tabvalues[]="[L]".utf8_decode("Présence de déchets");
	$tabvalues[]="[L]".utf8_decode(getRegvalues($waste, 'fr'));
	$tabvalues[]="[L]".utf8_decode("Eviter la présence de déchets solides dans les plantations, enlever tout déchets solides et les mettre à la décharge");
}

if($rating!=""){
	$tabvalues[]="[L]La plantation est-elle propre";
	$tabvalues[]="[L]".$rating."/10";
	$tabvalues[]="[L]".utf8_decode("Nettoyage de la plantation par le désherbage manuelle, éviter l'utilisation d'herbicide chimique");
}

$tabvalues[]="[L]";
$tabvalues[]="[L]";
$tabvalues[]="[L]Maintenir les bonnes pratiques agricoles";

	
$proprietesTableau = array(
'TB_ALIGN' => 'R',
'L_MARGIN' => 1,
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
'T_SIZE' => 11,
'T_FONT' => 'HelveticaNeueLight',
'T_ALIGN_COL0' => 'L',
'T_ALIGN' => 'L',
'V_ALIGN' => 'T',
'T_TYPE' => '',
'LN_SIZE' => 7,
'BG_COLOR_COL0' => array(255,255,255),
'BG_COLOR' => array(255,255,255),
'BRD_COLOR' => array(160,160,160),
'BRD_SIZE' => 0.1,
'BRD_TYPE' => '1',
'BRD_TYPE_NEW_PAGE' => '',
);

$PDF->drawTableau($PDF, $proprietesTableau, $proprieteHeader, $tabhcont, $proprieteContenu,$tabvalues);


$PDF->Ln(10);
$PDF->SetFont('HelveticaNeueLight','',10);
$PDF->Cell(3,1,utf8_decode("Plantation approuvée dans le cadre du projet de certification GLOBALG.A.P. le ").$cert_approved_date,'C');
$PDF->Ln(5);
$PDF->Cell(3,1,utf8_decode("par ").$cert_approved_name,'C');
 

$PDF->Ln(15);
$PDF->SetFont('HelveticaNeueLight','U',9);
$PDF->SetTextColor(0,0,0);
$PDF->Cell(4);
$PDF->Cell(3,1,"Signature du producteur ",'C');
$PDF->Cell(75);
$PDF->Cell(3,1,"Signature du chef de zone",'C');
$PDF->Cell(75);
$PDF->Cell(3,1,"Date",'C');

// $PDF->Ln(10);
// $PDF->SetFont('HelveticaNeueLight','',9);
// $PDF->Cell(82);
// $PDF->Cell(3,1,$cert_approved_name,'C');
// $PDF->Cell(70);
// $PDF->Cell(3,1,gmdate('d/m/Y'),'C');


$filename=$_SERVER['DOCUMENT_ROOT']."/ic/img/environment_document/".$id_plantation.".pdf";
$PDF->Output($filename,'F');

$doc_link = $id_plantation.".pdf";
$doc_date = gmdate("Y/m/d H:i");

$sql_stats = "INSERT INTO public.plantation_docs(
	id_plantdoc, plantation_id, doc_link, doc_type, doc_date)
VALUES ($new_id, $id_plantation, '$doc_link', 655, '$doc_date')";

$result = pg_query($conn, $sql_stats);


$PDF->Output();

}

?>
