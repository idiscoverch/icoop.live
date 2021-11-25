<?php     
ob_start();
include('PDFheaderfooter.php');
// include_once("../fcts.php");
// $conn=connect();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  

if (isset($_GET['id_plantation'])) { $id_plantation=$_GET['id_plantation']; }
if (isset($_GET['link'])) { $link=$_GET['link']; }

if(!empty($id_plantation) 
AND !empty($link) 
){

// Instanciation de la classe dérivée
$PDF = new PDF('L','mm','A4');
$PDF->AliasNbPages();
$PDF->AddPage();

$PDF->Image($link,-10,2);

$filename=$_SERVER['DOCUMENT_ROOT']."/ic/img/farmer_document/".$id_plantation.".pdf";
$PDF->Output($filename,'F');

	
$PDF->Output();

}

?>
