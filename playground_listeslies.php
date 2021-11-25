<?php

session_start();

include_once("fcts.php");
include_once("common.php");


header("Content-type: image/png");  

 
function IsInjected($str)
{
	$injections = array('(\n+)','(\r+)','(\t+)','(%0A+)','(%0D+)','(%08+)','(%09+)');
	$inject = join('|', $injections);
	$inject = "/$inject/i";

	if(preg_match($inject,$str)){
		return true;
	} else {
		return false;
	}
}

 

if (isset($_GET["elemid"])) {

    $elemid = $_GET["elemid"];
    $conn=connect();

    $dom='';

	switch ($elemid) {
		
		case "imgManagement":
		
			$val = $_GET["val"];
			$id = $_GET["id"];
			
			$list_img = "";
			
			if(($val)&&($id)){
				$cond = " WHERE $val = '$id'";
				
				$sql_img = "SELECT path FROM media_lib $cond";
				$result = pg_query($conn, $sql_img);

				while ($arr_img = pg_fetch_assoc($result)){
					
					$path = preg_replace('/\s+/', '', $arr_img['path']);
					
					$dir = "img/playground/".$path."/1/";
				
					if (is_dir($dir)) {
						if ($dh = opendir($dir)) {
							while (($file = readdir($dh)) !== false) {
								if( $file != '.' && $file != '..') {
									$list_img .= "<a href='img/playground/".$path."/2/$file' data-gallery=''><img src='img/playground/".$path."/1/$file' height='120' /></a>\n";
								}
							}
							closedir($dh);
						}
					}
				}
			}
			
			$dom = $list_img;
	
		break;
		
		
		
		case "connexion":  
		   
			$message = "";
			$username = $_GET["username"];
			$password = $_GET["password"];
			
			if(IsInjected($username))
			{
			   $message .= "0##Mauvaise valeur du nom utilisateur!<br>";
			}
			
			if(IsInjected($password))
			{
			   $message .= "0##Mauvaise valeur du mot de passe!<br>";
			}
			
			if(empty($message)){
				
				if(verifUsrename($username,$conn)){
					if(verification($username, $password)){
				     	if(verifExporter($username)){
						  $message .= "1##".$_SESSION['username'];	
						} else {
							$message .= "0##Votre compte a &eacute;t&eacute; d&eacute;sactiv&eacute; par l'administrateur!<br>";
						}							   
					} else {
						$message .= "0##Le mot de passe n'est pas correct!";
					}		
				} else {
					$message .= "0##Cet utilisateur n'existe pas!<br>";
				}		
			}
			
			echo $message;
	
        break;
		
		

		case "rightPanel":

		

		$culture = $_GET["culture"];

		$nbre_pays = $_GET["nbre_pays"];

		$nbre_produit = $_GET["nbre_produit"];

		$nbre_supplier = $_GET["nbre_supplier"];

		$nbre_farmer = $_GET["nbre_farmer"];

		$nbre_surface = $_GET["nbre_surface"];



		$dom = '<div id="img_right"><img src="img/playground/'.$culture.'.jpg" style="width:100%; margin-bottom:20px;"/></div>

			<div class="ibox-content no-padding">

			<table class="table table-hover no-margins">

				<tbody>

					<tr><td><span class="label label-default" id="countries" style="font-size: 12px;">'.$nbre_pays.'</span>&nbsp;&nbsp;'.$lang['RB_Countries'].'

						<span class="label label-primary pull-right" data-toggle="modal" data-target="#countryModal"><i class="fa fa-table"></i></span></td>

					</tr>

					<tr><td><span class="label label-success" id="products" style="font-size: 12px;">'.$nbre_produit.'</span>&nbsp;&nbsp;'.$lang['RB_Products'].'

						<span class="label label-primary pull-right" data-toggle="modal" data-target="#productModal"><i class="fa fa-table"></i></span></td>

					</tr>

					<tr><td><span class="label label-info" id="suppliers" style="font-size: 12px;">'.$nbre_supplier.'</span> &nbsp;&nbsp;'.$lang['RB_Suppliers'].'

						<span class="label label-primary pull-right" data-toggle="modal" data-target="#suppliertModal"><i class="fa fa-table"></i></span></td>

					</tr>

					<tr><td><span class="label label-danger" id="farmers" style="font-size: 12px;">'.$nbre_farmer.'</span>&nbsp;&nbsp;'.$lang['RB_Farmers'].'

						<span class="label label-primary pull-right" data-toggle="modal" data-target="#farmerstModal"><i class="fa fa-table"></i></span></td>

					</tr>';

					// $dom .= '<tr><td><span class="label label-warning" id="surface" style="font-size: 12px;">'.$nbre_surface.'</span>&nbsp;&nbsp;'.$lang['RB_Surface'].'

						// <span class="label label-primary pull-right" data-toggle="modal" data-target="#surfacetModal"><i class="fa fa-table"></i></span></td>

					// </tr>';

				$dom .= '</tbody>

			</table>

		</div>';

		

		break;

		

		

		

		case "bottomBoxes":

			$code = $_GET["code"];
			$culture = $_GET["cult"];
			$code_pays = explode('|', $code);

			$agriculture = "";
			$permanent = "";
			$contracted = "";
			$mapped = "";

			if($code!="0"){ 
				$items = $code_pays[0]."_number, ".$code_pays[0]."_proc"; 
				$where_p = " WHERE id_culture = '" . $culture . "' AND (code_pays = '" . $code_pays[0] . "'"; 
				$where_m = " code_pays = '" . $code_pays[0] . "'"; 

				if($code_pays[1] != ''){
					$items .= ','.$code_pays[1]."_number, ".$code_pays[1]."_proc"; 
					$where_p .= "  OR code_pays = '" . $code_pays[1] . "')"; 
					$where_m .= " OR code_pays = '" . $code_pays[1] . "'"; 
					$where_m = "(".$where_m. ")"; 

				} else {  $where_p .= ")";  }

				$where_m = " AND id_culture = '" . $culture . "' AND ".$where_m;

			} else {
				$items = "total "; $where_p = "";  
			}
			

			$sql_agriculture = "SELECT id_factresult, fact".$lang['DB_LANG_stat']." as fact, statgroup_name, statitem_name, year, " . $items . " FROM stat_country_db WHERE id_factresult = 17";
			$sql_permanent = "SELECT id_factresult, fact".$lang['DB_LANG_stat']." as fact, statgroup_name, statitem_name, year, " . $items . " FROM stat_country_db WHERE id_factresult = 19";
			$sql_contracted = "SELECT SUM(area) AS p_contracted FROM v_plantation as plantations  " . $where_p;
			$sql_mapped = "SELECT COUNT(area) AS p_mapped FROM v_plantation as plantations  WHERE geom IS NOT NULL " . $where_m;

			$result_agriculture = pg_query($conn, $sql_agriculture);
			$arr_agriculture = pg_fetch_assoc($result_agriculture);
			$result_permanent = pg_query($conn, $sql_permanent);
			$arr_permanent = pg_fetch_assoc($result_permanent);
			
			$result_contracted = pg_query($conn, $sql_contracted);
			$arr_contracted = pg_fetch_assoc($result_contracted);
			$result_mapped = pg_query($conn, $sql_mapped);
			$arr_mapped = pg_fetch_assoc($result_mapped);


			if($code!="0"){ 
				$agriculture = $arr_agriculture[$code_pays[0].'_number']; 
				$permanent = $arr_permanent[$code_pays[0].'_number']; 
				$contracted = $arr_contracted['p_contracted']; 
				$mapped = number_format($arr_mapped['p_mapped'],0,","," "); 
				
				if($code_pays[1] != ''){
					$agriculture += $arr_agriculture[$code_pays[1].'_number']; 
					$permanent += $arr_permanent[$code_pays[1].'_number'];  
				}

			} else { 
				$agriculture = $arr_agriculture['total']; 
				$permanent = $arr_permanent['total']; 
				$contracted = $arr_contracted['p_contracted']; 

				$mapped = number_format($arr_mapped['p_mapped'],0,","," "); 
			}

			$dom = number_format($agriculture,0,","," "). '#' . number_format($permanent,0,","," ") . '#' . number_format($contracted,0,","," ") . '#' . $mapped.'#'.$arr_agriculture['fact'].'#'.$arr_permanent['fact'].'#'.$lang['DB_LANG_plan_contract'].'#'.$lang['DB_LANG_plan_map'];

		break;



		case "stats":		

			$code = $_GET["code"];
			
			$life = "";
			$social = "";
			$economy = "";
			
			if($code!=""){ $items = $code."_number as number, ".$code."_proc as pourcent"; } else { $items = ""; }
			
			$sql_stats = "SELECT id_factresult, statgroup_name, statitem_name, 
				fact".$lang['DB_LANG_stat']." as fact,  year, $items,  ch_number, ch_proc,  world_number, 
				world_proc, total
			FROM stat_country_db;";
			

			$result = pg_query($conn, $sql_stats);
			while ($arr_f = pg_fetch_assoc($result)){
				if($arr_f['id_factresult']==28){
					
					$life28 = ' <li>
						<div class="text-center">
							<h5>'.$arr_f['fact'].'</h5><br/><br/>
							<div class="m-r-md inline">
								<input type="text" value="'.$arr_f['number'].'" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" disabled />
							</div>
						</div>
					</li>';

				} else
				if($arr_f['id_factresult']==13){

					$life13 = ' <li>
						<div class="ibox">
							<div class="ibox-content text-center">
								<h5 style="margin-bottom: 0px;">'.$arr_f['fact'].'</h5>
								<div style="width:100%;display:inline-block;">
									<h2 class="pull-left text-navy" style="margin-bottom: 0px;">'.$arr_f['number'].'</h2>
									<h2 class="pull-right text-danger" style="margin-bottom: 0px;">'.$arr_f['ch_number'].'</h2>
								</div>

								<div class="progress progress-mini" style="margin-top: 5px;">
									<div style="width: '.($arr_f['number']*100/($arr_f['number']+$arr_f['ch_number'])).'%;" class="progress-bar"></div>
									<div style="width: '.($arr_f['ch_number']*100/($arr_f['number']+$arr_f['ch_number'])).'%;" class="progress-bar progress-bar-danger"></div>
								</div>			

								<div style="width:100%;display:inline-block;">
									<div class="m-t-sm small pull-left text-navy">'.$lang['DB_PAYS_' . $code].'</div>
									<div class="m-t-sm small pull-right text-danger">'.$lang['DB_PAYS_ch'].'</div>
								</div>
							</div>
						</div>
					</li>';

				}  else
				if($arr_f['id_factresult']==69){

					$life69 = ' <li>
						<div class="ibox">
							<div class="ibox-content text-center">
							
								<h5 style="margin-bottom: 0px;">'.$arr_f['fact'].'</h5>

								<div style="width:100%;display:inline-block;">
									<h2 class="pull-left text-navy" style="margin-bottom: 0px;">'.$arr_f['pourcent'].'</h2>
									<h2 class="pull-right text-danger" style="margin-bottom: 0px;">'.$arr_f['ch_proc'].'</h2>
								</div>

								<div class="progress progress-mini" style="margin-top: 5px;">
									<div style="width: '.($arr_f['pourcent']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar"></div>
									<div style="width: '.($arr_f['ch_proc']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar progress-bar-danger"></div>
								</div>			

								<div style="width:100%;display:inline-block;">
									<div class="m-t-sm small pull-left text-navy">'.$lang['DB_PAYS_' . $code].'</div>
									<div class="m-t-sm small pull-right text-danger">'.$lang['DB_PAYS_ch'].'</div>
								</div>
							</div>
						</div>
					</li>';

				} else
				if($arr_f['id_factresult']==70){

					$life70 = ' <li>
						<div class="ibox">
							<div class="ibox-content text-center">
								<h5>'.$arr_f['fact'].'</h5><br/><br/>
								
								<div style="width:100%;display:inline-block;">
									<h2 class="pull-left text-navy" style="margin-bottom: 0px;">'.$arr_f['pourcent'].'</h2>
									<h2 class="pull-right text-danger" style="margin-bottom: 0px;">'.$arr_f['ch_proc'].'</h2>
								</div>

								<div class="progress progress-mini" style="margin-top: 5px;">
									<div style="width: '.($arr_f['pourcent']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar"></div>
									<div style="width: '.($arr_f['ch_proc']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar progress-bar-danger"></div>
								</div>			

								<div style="width:100%;display:inline-block;">
									<div class="m-t-sm small pull-left text-navy">'.$lang['DB_PAYS_' . $code].'</div>
									<div class="m-t-sm small pull-right text-danger">'.$lang['DB_PAYS_ch'].'</div>
								</div>
							</div>
						</div>
					</li>';

				} else
				if($arr_f['id_factresult']==67){

					$economy67 = ' <li>
						<div class="ibox" style="margin-bottom: 0px;">
							<div class="ibox-content text-center" style="padding: 0px 20px;">
								<h5 style="margin-bottom: 0px;">'.$arr_f['fact'].'</h5>
								<div style="width:100%;display:inline-block;">
									<h2 class="pull-left text-navy" style="margin-bottom: 0px;">'.$arr_f['pourcent'].'</h2>
									<h2 class="pull-right text-danger" style="margin-bottom: 0px;">'.$arr_f['ch_proc'].'</h2>
								</div>

								<div class="progress progress-mini" style="margin-top: 5px;">
									<div style="width: '.($arr_f['pourcent']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar"></div>
									<div style="width: '.($arr_f['ch_proc']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar progress-bar-danger"></div>
								</div>			

								<div style="width:100%;display:inline-block;">
									<div class="m-t-sm small pull-left text-navy">'.$lang['DB_PAYS_' . $code].'</div>
									<div class="m-t-sm small pull-right text-danger">'.$lang['DB_PAYS_ch'].'</div>
								</div>
							</div>
						</div>
					</li>';

				}  else
				if($arr_f['id_factresult']==11){

					$economy11 = ' <li>
						<div class="ibox" style="margin-bottom: 0px;">
							<div class="ibox-content text-center" style="padding: 0px 20px;">
								<h5 style="margin-bottom: 0px;">'.$arr_f['fact'].'</h5>
								<div style="width:100%;display:inline-block;">
									<h2 class="pull-left text-navy" style="margin-bottom: 0px;">'.number_format($arr_f['number'],0,","," ").'</h2>
									<h2 class="pull-right text-danger" style="margin-bottom: 0px;">'.number_format($arr_f['ch_number'],0,","," ").'</h2>
								</div>

								<div class="progress progress-mini" style="margin-top: 5px;">
									<div style="width: '.($arr_f['number']*100/($arr_f['number']+$arr_f['ch_number'])).'%;" class="progress-bar"></div>
									<div style="width: '.($arr_f['ch_number']*100/($arr_f['number']+$arr_f['ch_number'])).'%;" class="progress-bar progress-bar-danger"></div>
								</div>				

								<div style="width:100%;display:inline-block;">
									<div class="m-t-sm small pull-left text-navy">'.$lang['DB_PAYS_' . $code].'</div>
									<div class="m-t-sm small pull-right text-danger">'.$lang['DB_PAYS_ch'].'</div>
								</div>
							</div>
						</div>
					</li>';

				} else
				if($arr_f['id_factresult']==68){

					$economy_pie1 = '<div class="pull-left" style="width:50%">
						<h5>'.$arr_f['fact'].' </h5>
						<div class="m-r-md inline">
							<input type="text" value="'.$arr_f['pourcent'].'" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" disabled />
						</div>
					</div>';

				} else
				if($arr_f['id_factresult']== 10){

					$economy_pie2 = '<div class="pull-left" style="width:50%">
						<h5>'.$arr_f['fact'].'</h5><br/>
						<div class="m-r-md inline">
							<input type="text" value="'.$arr_f['pourcent'].'" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" disabled />
						</div>
					</div>';

				} else
				if($arr_f['id_factresult']==55){

					$social55 = ' <div class="ibox" style="margin-bottom: 0px;">
						<div class="ibox-content text-center" style="padding: 0px 20px; border-width:0px 0px;">
							<h5 style="margin-bottom: 0px;">'.$arr_f['fact'].'</h5>
							<div style="width:100%;display:inline-block;">
								<h2 class="pull-left text-navy" style="margin-bottom: 0px;">'.number_format($arr_f['number'],0,","," ").'</h2>
								<h2 class="pull-right text-danger" style="margin-bottom: 0px;">'.number_format($arr_f['ch_number'],0,","," ").'</h2>
							</div>

							<div class="progress progress-mini" style="margin-top: 5px;">
								<div style="width: '.($arr_f['number']*100/($arr_f['number']+$arr_f['ch_number'])).'%;" class="progress-bar"></div>
								<div style="width: '.($arr_f['ch_number']*100/($arr_f['number']+$arr_f['ch_number'])).'%;" class="progress-bar progress-bar-danger"></div>
							</div>

							<div style="width:100%;display:inline-block;">
								<div class="m-t-sm small pull-left text-navy">'.$lang['DB_PAYS_' . $code].'</div>
								<div class="m-t-sm small pull-right text-danger">'.$lang['DB_PAYS_ch'].'</div>
							</div>
						</div>
					</div>';

				} else
				if($arr_f['id_factresult']==8){

					$social8 = ' <div class="ibox" style="margin-bottom: 0px;">
						<div class="ibox-content text-center" style="padding: 0px 20px;">
							<h5 style="margin-bottom: 0px;">'.$arr_f['fact'].'</h5>
							<div style="width:100%;display:inline-block;">
								<h2 class="pull-left text-navy" style="margin-bottom: 0px;">'.number_format($arr_f['pourcent'],0,","," ").'</h2>
								<h2 class="pull-right text-danger" style="margin-bottom: 0px;">'.number_format($arr_f['ch_proc'],0,","," ").'</h2>
							</div>

							<div class="progress progress-mini" style="margin-top: 5px;">
								<div style="width: '.($arr_f['pourcent']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar"></div>
								<div style="width: '.($arr_f['ch_proc']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar progress-bar-danger"></div>
							</div>

							<div style="width:100%;display:inline-block;">
								<div class="m-t-sm small pull-left text-navy">'.$lang['DB_PAYS_' . $code].'</div>
								<div class="m-t-sm small pull-right text-danger">'.$lang['DB_PAYS_ch'].'</div>
							</div>
						</div>
					</div>';

				} else
				if($arr_f['id_factresult']==9){

					$social9 = ' <div class="ibox" style="margin-bottom: 0px;">
						<div class="ibox-content text-center" style="padding: 0px 20px;">
							<h5 style="margin-bottom: 0px;">'.$arr_f['fact'].'</h5>
							<div style="width:100%;display:inline-block;">
								<h2 class="pull-left text-navy" style="margin-bottom: 0px;">'.number_format($arr_f['pourcent'],0,","," ").'</h2>
								<h2 class="pull-right text-danger" style="margin-bottom: 0px;">'.number_format($arr_f['ch_proc'],0,","," ").'</h2>
							</div>

							<div class="progress progress-mini" style="margin-top: 5px;">
								<div style="width: '.($arr_f['pourcent']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar"></div>
								<div style="width: '.($arr_f['ch_proc']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar progress-bar-danger"></div>
							</div>

							<div style="width:100%;display:inline-block;">
								<div class="m-t-sm small pull-left text-navy">'.$lang['DB_PAYS_' . $code].'</div>
								<div class="m-t-sm small pull-right text-danger">'.$lang['DB_PAYS_ch'].'</div>
							</div>
						</div>
					</div>';

				} else
				if($arr_f['id_factresult']==5){

					$social5 = ' <div class="ibox" style="margin-bottom: 0px;">
						<div class="ibox-content text-center" style="padding: 0px 20px;">
							<h5 style="margin-bottom: 0px;">'.$arr_f['fact'].' </h5>
							<div style="width:100%;display:inline-block;">
								<h2 class="pull-left text-navy" style="margin-bottom: 0px;">'.number_format($arr_f['pourcent'],0,","," ").'</h2>
								<h2 class="pull-right text-danger" style="margin-bottom: 0px;">'.number_format($arr_f['ch_proc'],0,","," ").'</h2>
							</div>

							<div class="progress progress-mini" style="margin-top: 5px;">
								<div style="width: '.($arr_f['pourcent']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar"></div>
								<div style="width: '.($arr_f['ch_proc']*100/($arr_f['pourcent']+$arr_f['ch_proc'])).'%;" class="progress-bar progress-bar-danger"></div>
							</div>

							<div style="width:100%;display:inline-block;">
								<div class="m-t-sm small pull-left text-navy">'.$lang['DB_PAYS_' . $code].'</div>
								<div class="m-t-sm small pull-right text-danger">'.$lang['DB_PAYS_ch'].'</div>
							</div>
						</div>
					</div>';	
				} 

			}


			$life .= $life28.$life13.$life69.$life70; 

			$economy .= '<li><div class="text-center">'.$economy_pie1.$economy_pie2.'</div></li>'.$economy67.$economy11 ; 
			$social = $social55.$social8.$social9.$social5;

			$dom = $economy . '##' . $social . '##' . $life;


		break;

		

		case "ticker":

			$ticker = '';

			$sql = 'SELECT delivery_date, "from", "to", product_name, unit, quantity
			FROM public.v_ticker ORDER BY delivery_date DESC';
	  

			$result = pg_query($conn, $sql);

			while ($arr_f = pg_fetch_assoc($result)){
				$ticker .= $arr_f['delivery_date'] .'|'. $arr_f['from'] .'|'. $arr_f['to'] .'|'. $arr_f['product_name'] .'|'.$arr_f['quantity'].' '.$arr_f['unit'].'##'; 
			}

			$dom = $ticker;

		break;

		

		case "voletdroit":

			$param = $_GET["param"];
			$valeur = $_GET["valeur"];
			
			$social = ''; $histoire = '';

			if($param == 'pays') {	  

				$sql_pays = "SELECT * FROM pays WHERE code ='$valeur'";
				$result_pays = pg_query($conn, $sql_pays);
				$arr_p = pg_fetch_assoc($result_pays);

				
				$founisseur = '';  

				$sql_f = "SELECT * FROM fournisseurs WHERE code ='$valeur'";
				$result_f = pg_query($conn, $sql_f);

				while ($arr_f = pg_fetch_assoc($result_f)){

					$founisseur .= '<div class="fournisseur" onclick="zoomfournisseur('. $arr_f['id_fournisseur'] .');">
						<h2>'. $arr_f['nom_four'] .'</h2>
						<p>'.$arr_f['company_name'] .'</p>
					</div>';
				}


				$social .= '<div class="ibox-content" style="display:inline-block; padding:0; border-width:0; position:relative; background: #ffa500; margin-bottom:10px; width:100%;">
					<span class="label label-primary pull-right" style="position:absolute; top:10px; left:20px; box-shadow: 0 1px 6px 0 rgba(0,0,0,.3);">
						<i class="fa fa-globe"></i> '.$arr_p['pays'].'
					</span>

					<img height="200px" src="img/playground/vue_'.$valeur.'.jpg" style="width:100%;"/><br>
					<img src="img/playground/'.$valeur.'.png" height="32" style="position:absolute; z-index:9999; right:20px; top:185px; box-shadow: 0 1px 6px 0 rgba(0,0,0,.3);">

					<div class="pull-left" style="color:white; padding:10px 20px;">
					  <strong>Capitale : </strong>'.$arr_p['capitale'].'<br>
					  <strong>Nombre d\'habitants : </strong>'.$arr_p['nbre_hbts'].'<br>
					  <strong>Superficie : </strong>'.$arr_p['superficie'].' km²</div>
					</div>
				</div>

				<div id="fournissuerBox">'.$founisseur.'</div>';

			
				// Histoire

				$hist=''; 
				$sql_hst = "SELECT * FROM histoires WHERE code_pays ='$valeur'";
				$result_hst = pg_query($conn, $sql_hst);

				$i=1;

				while($arr_hst = pg_fetch_assoc($result_hst)){

					$link = 'img/playground/histoire/'.$valeur.'/'.$arr_hst['photo'];
					if($i==1){$active='active';} else {$active='';}

					$hist .=' <div class="item '.$active.'">

						<div style="height:250px; overflow:hidden; width:100%;">';

							if($arr_hst['photo']!='') { $hist .='<img alt="image" style="width:100%;" class="img-responsive" src="'.$link.'">'; }
							else { $hist .='<figure><iframe style="width:100%;" height="250" src="'.$arr_hst['youtube'].'" frameborder="0" marginwidth="0" marginheight="0" allowfullscreen></iframe></figure>'; }

					$hist .='</div>
						<div class="carCtn">
							<div>'.$arr_hst['titre'].'</div>
							'.$arr_hst['description'].'<br/><br/>
							<button type="button" onclick="histoire('.$arr_hst['gid'].');" class="pull-right  btn btn-primary btn-xs animation_select" data-animation="bounceOut">Visualiser l\'histoire</button>
						</div>	
					</div>';

					$i++;
				}

				$histoire .= '<div class="carousel slide" id="carousel1" style="margin:10px;">
					<div class="carousel-inner">
						'.$hist.'
					</div>

					<a data-slide="prev" href="#carousel1" class="left carousel-control" style="background-image:none; height:30px;top:50%;">
						<span class="icon-prev"></span>
					</a>

					<a data-slide="next" href="#carousel1" class="right carousel-control"  style="background-image:none;height:30px;top:50%;">
						<span class="icon-next"></span>
					</a>
				</div>';

			} else if($param == 'fournisseur') {


			} else if($param == 'histoire') {


			} else {

				$social .= '<div id="img_right"><img src="img/playground/TraceabilityFlowGreen.jpg" style="width:100%; margin-bottom:20px;"/></div>
					<div class="ibox-content no-padding">
						<table class="table table-hover no-margins">
							<tbody>
								<tr><td ><span class="label label-default" id="countries" style="font-size: 12px;">6</span>&nbsp;&nbsp;  '. $lang['RB_Countries'] .' 
									<span class="label label-primary pull-right" data-toggle="modal" data-target="#myModal2"><i class="fa fa-table"></i></span></td>
								</tr>

								<tr><td><span class="label label-success" id="products" style="font-size: 12px;">5</span>&nbsp;&nbsp;  '. $lang['RB_Products'] .' 
									<span class="label label-primary pull-right" data-toggle="modal" data-target="#myModal2"><i class="fa fa-table"></i></span></td>
								</tr>

								<tr><td><span class="label label-info" id="suppliers" style="font-size: 12px;">12</span>&nbsp;&nbsp; '. $lang['RB_Suppliers'] .' 
									<span class="label label-primary pull-right" data-toggle="modal" data-target="#myModal2"><i class="fa fa-table"></i></span></td>
								</tr>

								<tr><td><span class="label label-danger" id="farmers" style="font-size: 12px;">1151</span> &nbsp;&nbsp; '. $lang['RB_Farmers'] .' 
									<span class="label label-primary pull-right" data-toggle="modal" data-target="#myModal2"><i class="fa fa-table"></i></span></td>
								</tr>

								<tr><td><span class="label label-warning" id="surface" style="font-size: 12px;">4462</span> &nbsp;&nbsp; '. $lang['RB_Surface'] .' 
									<span class="label label-primary pull-right" data-toggle="modal" data-target="#myModal2"><i class="fa fa-table"></i></span></td>
								</tr>
							</tbody>
						</table>
				</div>';


				$histoire .='';
			}
			

			$dom = $social.'##'.$histoire;

	
		break;

		

		case "historique":

			$id_histoire = $_GET["id_histoire"];
			$code_pays = $_GET["code_pays"];
			$culture = $_GET["culture"];
			$hist = "";
			$hist_coord = "";

			$sql = "SELECT id_storycon, seq_text".$lang['DB_LANG_stat']." as content, seq_mediatype, seq_coordx, seq_coordy, id_story, seq_link, seq_zoom  
				FROM story_con
			WHERE id_story ='$id_histoire' order by id_storycon;";

			$result = pg_query($conn, $sql);

			while ($arr_hst = pg_fetch_assoc($result)) {

				if($arr_hst['seq_mediatype'] == 1) { 
					$vid ='<iframe style="width:100%; height:200px;" frameborder="0" allowfullscreen="true" src="https://www.youtube.com/embed/'.$arr_hst['seq_link'].'?autoplay=0&rel=0&loop=1" allowTransparency="true" frameborder="0" scrolling="no"></iframe>';

				} else {  
					$vid ='<img alt="image" style="height:200px; width:100%;" class="img-responsive" src="img/playground/story/'.$arr_hst['seq_link'].'">';
				}

				$hist .='<h3></h3><section>  
					<div class="carCtn">
						<div style="height:auto;overflow:hidden;width:100%;">'.$vid.'</div>
						<div style="font-weight:unset;font-size:14px;text-align:justify;overflow-y: scroll;height:80px;">'.$arr_hst['content'].'</div>
					</div>
				</section>';

				$hist_coord .= '#'.$arr_hst['seq_coordx'].','.$arr_hst['seq_coordy'].','.$arr_hst['seq_zoom'];	
			}	

			$dom = '<div id="example-vertical">'.$hist.'</div>|'.$hist_coord; 

		break;

		

		case "zone":

			$choixcode = $_GET["code"];
			$zone = "";

			$sql = "SELECT coordx, coordy, nom_zone FROM zone WHERE code_pays ='$choixcode'";
			$result = pg_query($conn, $sql);

			while ($arr = pg_fetch_assoc($result)) {
				$zone .= $arr['coordx'].'#'.$arr['coordy'].'#'.$arr['nom_zone'].'??';
			}

			$zone .= 'end';
			$dom = $zone;  

		break;


		case "founisseur":

			$choixcode = $_GET["code"];
			$founisseur ='';

			$sql = "SELECT coordx, coordy, nom_entier FROM fournisseurs WHERE code_pays ='$choixcode'";
			$result = pg_query($conn, $sql);

		
			while ($arr = pg_fetch_assoc($result)) {
				$founisseur .= $arr['coordx'].'#'.$arr['coordy'].'#'.$arr['nom_entier'].'??';
			}

			$founisseur .= 'end';

			$dom = $founisseur;  

		break;


		case "zoomfournisseur":

			$id = $_GET["id"];

			$sql = "SELECT * FROM fournisseurs WHERE id_fournisseur ='$id'";
			$result = pg_query($conn, $sql);

			$arr = pg_fetch_assoc($result);

			$dom = $arr['coordx'].'#'.$arr['coordy'].'#'.$arr['nom_entier'].'#'.$arr['code_pays'].'#'.$arr['localisation'].'#'.$arr['boite_postal'].'#'.$arr['contact1'].'#'.$arr['contact1'].'#'.$arr['fax'].'#'.$arr['infos'].'#'.$arr['photo'];

		break;



		case "pays":

			$choixcode = $_GET["code"];

			if($choixcode=="0"){
				$condition=""; 
			} else {
				$condition=" WHERE code ='$choixcode'";   
			}

			$sql_pays="SELECT *, public.ST_AsGeoJSON(geom,6) AS geojson FROM pays $condition";
			$result_pays = pg_query($conn, $sql_pays);


			$geojson_pays = array('type' => 'FeatureCollection', 'features' => array());		

			while ($arr = pg_fetch_assoc($result_pays)) {
				$pays = $arr['pays']; 
				$code = $arr['code'];

				if($choixcode=="0"){
					if($code=='ch'){$couche='suisse';}
					else{$couche='pays';}
				} else { $couche='pays_contours'; }

				$properties_pays = $arr;
				unset($properties_pays['geojson']);
				unset($properties_pays['geom']);

				$feature_pays = array(
					'type' => 'Feature',
					'geometry' => json_decode($arr['geojson'], true),
					'properties' => $properties_pays,
					'couche' => $couche
				);
				array_push($geojson_pays['features'], $feature_pays);
			}


			header('Content-type: application/json');

			$dom = json_encode($geojson_pays['features'], JSON_NUMERIC_CHECK).'#'.$pays.'#'.$code;  

		break;

		

		

		case "inscription":

			$message = "";
			$firstname = $_GET["firstname"];
			$lastname = $_GET["lastname"];
			$email = $_GET["email"];
			$username = $_GET["username"];
			$password = $_GET["password"];
			$icode = $_GET["icode"];

			
			if(IsInjected($firstname))
			{
				$message .= "0##Mauvaise valeur du nom!<br>";
			}

			if(IsInjected($lastname))
			{
				$message .= "0##Mauvaise valeur du prénom!<br>";
			}

			if(IsInjected($email))
			{
				$message .= "0##Mauvaise valeur de l'email!<br>";
			}

			
			if(IsInjected($username))
			{
				$message .= "0##Mauvaise valeur du pseudo!<br>";
			}

			if(IsInjected($password))
			{
				$message .= "0##Mauvaise valeur du mot de passe!<br>";
			}

			if(IsInjected($icode))
			{
				$message .= "0##Mauvaise valeur du code d'invitation!<br>";
			}
		

			if(empty($message)){    
				if(empty($message)){  
					$co = verifCode($icode,$conn);
					if($co == 2) {
						if(verifUsrename($username,$conn)){
							$message .= "0##Cet utilisateur existe déj&#224;!";
						} else {
							$motdepass = md5($password);  
							
							$date_inscription = date("Y-m-d");   
							$sql="UPDATE utilisateurs  SET nom='$firstname', prenoms='$lastname', email='$email', pseudo='$username', motdepass='$motdepass', date_inscription='$date_inscription' WHERE icode='$icode'";

							$res = pg_query($conn,$sql) or die(pg_last_error());
							$row= pg_fetch_assoc($res);

							pg_Close ($conn);

							$message .= "1##Inscription Réussie!";

							$to = $email;
							$subject="Inscription sur l'application SIG-PMCI";
							$from = "ADMINISTRATEUR_SIG_PMCI";
							$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

							$body = "Vous vous etes inscrit(e) sur l'application SIG-PHARMACIE :\n\n".
								"Nom : $firstname\n".
								"Prenom: $lastname\n".
								"Email: $email \n".
								"Pseudo: $username \n".
								"Mot de passe: $password\n\n\n ".
								"IP: $ip\n
							";    

							$headers = "De: $from \r\n";
							$headers .= "Repondre ຠ$email \r\n";
						}

					} else if($co == 1) {
						$message .= "0##Ce code d'invitation a déj&#224; été utilisé!";
					} else {
						$message .= "0##Ce code d'invitation n'existe pas!";
					}
				}
			}

			echo $message;

		break;


		case "update":  

			$menu = $_GET["menu"];  

			if($menu == 'user') {
				$titrepersonne = $_GET["titrepersonne"];   
				$id_utilisateurs = $_GET["id_utilisateurs"];   
				$nom = $_GET["nom"];   
				$prenoms = $_GET["prenoms"];   
				$telephone = $_GET["telephone"];   
				$email = $_GET["email"];   
				$pseudo = $_GET["pseudo"];   
				$roles = $_GET["roles"];   
				$date_inscription = $_GET["date_inscription"];   
				$question = $_GET["question"];   
				$reponse = $_GET["reponse"];   

				if(empty($nom)||empty($prenoms)||empty($pseudo)||empty($roles)) {
					$errors .= "\n Les champs Nom, Prénom, Pseudo, Mot de passe et R&#244;les sont obligatoires!<br>";    
				} elseif(isDate($date_inscription) == false) {
					$errors .= "\n Mauvais format de la date!<br>";
				} 

				if(empty($errors)){
					if(IsInjected($nom))
					{
						$errors .= "\n Mauvaise valeur du nom!<br>";
					}

					if(IsInjected($prenoms))
					{
						$errors .= "\n Mauvaise valeur du prénom!<br>";
					}

					if(IsInjected($telephone))
					{
						$errors .= "\n Mauvaise valeur du téléphone!<br>";
					}

					if(IsInjected($email))
					{
						$errors .= "\n Mauvaise valeur de l'email!<br>";
					}

					if(IsInjected($question))
					{
						$errors .= "\n Mauvaise valeur de la question!<br>";
					}

					if(IsInjected($reponse))
					{
						$errors .= "\n Mauvaise valeur de la réponse!<br>";
					}
				}

				if(empty($errors)){
					$sql="UPDATE utilisateurs SET titrepersonne='$titrepersonne' , nom='$nom' , prenoms='$prenoms' , telephone='$telephone' , 
					email='$email' , roles=$roles , date_inscription='$date_inscription' WHERE   id_utilisateurs=".$id_utilisateurs;

					$res = pg_query($conn,$sql);
					$row= pg_fetch_assoc($res);

					$sql2="UPDATE motpassoublie SET question='$question', reponse='$reponse'  WHERE id_utilisateurs=".$id_utilisateurs;
					$res2 = pg_query($conn,$sql2);

					pg_Close ($conn);
				} 
			} 

			$dom=$errors;

		break; 


		case "delete":  

			$menu = $_GET["menu"];  
			$id= $_GET["id"];  

			if($menu == 'user') {
				$sql2="DELETE FROM motpassoublie WHERE id_utilisateurs=$id";
				$res2 = pg_query($conn,$sql2);

				$sql="DELETE FROM utilisateurs WHERE id_utilisateurs=$id";
				$res = pg_query($conn,$sql);
				$row= pg_fetch_assoc($res);

				pg_Close ($conn);
			} 

			$dom='';

		break;  

		
		case "init_pass":  

			$id= $_GET["id"];  

			$sql="SELECT id_utilisateurs, pseudo FROM utilisateurs  WHERE id_utilisateurs=".$id;
			$res = pg_query($conn,$sql);
			$row= pg_fetch_assoc($res);

			$retour = '';
			$retour .='<a href="#"><span onclick="javascript:close_fenetre();" class="close_fenetre" style="float:right">X</span></a>
				<p align="center" style="">
				<form id = "inscription" name="inscription" method="POST" action=""><fieldset>
				<h2 style="border-bottom: 1px solid #eee; "><strong>Formulaire d\'initialisation de mot de passe</strong></h2><br>
				<p><div id="erreur_user" style="max-width:400px; max-height: 670px; color:red; overflow:auto;"></div><I>Les champs marqu&eacute;s * doivent obligatoirement &ecirc;tre renseign&eacute;s </I></p>
				<table bgcolor="#FFFFFF" >';

			$retour .='<td><label>Pseudo : </label></td>';
			$retour .='<td><input type="text"  name="pseudo" id="pseudo" value="'.$row['pseudo'].'" readonly/></td></tr>';
			$retour .='<td><label>Ancien mot de passe * : </label></td>';
			$retour .='<td><input type="password"  name="ancien_pass" id="ancien_pass" value="" required/></td></tr>';
			$retour .='<td><label>Nouveau mot de passe * : </label></td>';
			$retour .='<td><input type="password"  name="nouveau_pass" id="nouveau_pass" value="" required/></td></tr>';
			$retour .='<td><label>R&eacute;p&eacute;ter le nouveau mot de passe* : </label></td>';
			$retour .='<td><input type="password"  name="nouveau_pass2"  id="nouveau_pass2" value="" required/></td></tr>';
			$retour .='</table>';
			$retour .='  <br><p align="center"> <input type="button" name="action" value="Valider" onclick="javascript:change_pass('.$id.')"></p>';
			$retour .='</fieldset></form>';

			$dom= $retour;

		break;  


		case "change_pass":  

			$id= $_GET["id"];  
			$ancien_pass= $_GET["ancien_pass"];  
			$nouveau_pass= $_GET["nouveau_pass"];  
			$nouveau_pass2= $_GET["nouveau_pass2"];  

			if(empty($ancien_pass)||empty($nouveau_pass)||empty($nouveau_pass2)){
				$errors .= "\n Les champs Ancien mot de passe, Nouveau mot de passe sont obligatoires!<br>";    
			} elseif( $nouveau_pass != $nouveau_pass2) {
				$errors .= "\n Les champs  nouveau mot de passe doivent &#234;tre identiques!<br>";   
			} 

			if(empty($errors)){
				if(IsInjected($ancien_pass))
				{
					$errors .= "\n Mauvaise valeur du nom!<br>";
				}

				if(IsInjected($nouveau_pass))
				{
					$errors .= "\n Mauvaise valeur du nom!<br>";
				}

				if(IsInjected($nouveau_pass2))
				{
					$errors .= "\n Mauvaise valeur du nom!<br>";
				}						
			}

			if(empty($errors)){		
				$sql="SELECT id_utilisateurs, pseudo, motdepass FROM utilisateurs  WHERE id_utilisateurs=".$id;
				$res = pg_query($conn,$sql);
				$row= pg_fetch_assoc($res);

				if(md5($ancien_pass) != $row['motdepass'])
				{
					$errors .= "\n L'ancien mot de passe n'est pas correct!<br>";
				}
			}

			if(empty($errors)){
				$nouveau_pass = md5($nouveau_pass);
				$sql2="UPDATE utilisateurs SET  motdepass='$nouveau_pass' WHERE   id_utilisateurs=".$id;

				$res2 = pg_query($conn,$sql2);
				$row2= pg_fetch_assoc($res2);
			}

			$dom= $errors;

		break; 



        case "controle_date":  

			$date_debut = $_GET["date_debut"];  
			$date_fin = $_GET["date_fin"]; 
			$condition = "";


			if (isset($_GET["garde_id"])) {
				$garde_id = $_GET["garde_id"]; 
				$condition = " AND garde_id <> $garde_id ";  
			}


			if(empty($date_debut)||empty($date_fin)) {
				$errors .= "\n Valeur de date incorrecte !!!<br>";   
			} else {
				$sql = "SELECT garde_id from garde where (((debut_garde,fin_garde) OVERLAPS (DATE '$date_debut'-1,DATE '$date_fin'+1)) = true) $condition";					  
				$res = pg_query($conn,$sql);

				$numrows = pg_num_rows ($res);

				if ($numrows > 0) {
					$errors .= "\n La garde saisie existe d&eacute;j&agrave;.<br>";   
				}
			}

			$dom= $errors;

        break;  


		case "connexion":  
		
			$message = "";
			$username = $_GET["username"];
			$password = $_GET["password"];

			if(IsInjected($username))
			{
			   $message .= "0##Mauvaise valeur du nom utilisateur!<br>";
			}

			if(IsInjected($password))
			{
			   $message .= "0##Mauvaise valeur du mot de passe!<br>";
			}

			if(empty($message)){
				if(verifUsrename($username,$conn)){
					if(verification1($username, $password)){
						if(verifActive($username)){
							$message .= "1##".$_SESSION['role'];	
						} else {
							$message .= "0##Votre compte a &eacute;t&eacute; d&eacute;sactiv&eacute; par l'administrateur!<br>";
						}		   

					} else {
						$message .= "0##Le mot de passe n'est pas correct!";
					}							

				} else {			
					$message .= "0##Cet utilisateur n'existe pas!<br>";			
				}
			}

			echo $message;

		break;
		

		case "update_user":

			$message = '';
			$action = $_GET["action"];
			$code = $_GET["code"];

			$conn1 = new PDO('pgsql:host=localhost;dbname=bd_pharm','postgres','postgres');

			if ($action == 'active'){
				$sql2 = "UPDATE utilisateurs  SET active=1 WHERE icode='$code'   ";
				$rs2 = $conn1->query($sql2);

			} else if ($action == 'desactive'){
				$sql2 = "UPDATE utilisateurs  SET active=2 WHERE icode='$code'   ";
				$rs2 = $conn1->query($sql2);
				
			} else if ($action == 'supprimer'){
				$sql2 = "DELETE FROM utilisateurs WHERE icode='$code'   ";
				$rs2 = $conn1->query($sql2);

			} else if ($action == 'ajouter'){
				if (verifCode($code,$conn) == 0) {
					$sql2 = "INSERT INTO utilisateurs(icode) VALUES ('$code') RETURNING id_utilisateurs;";
					$rs2 = $conn1->query($sql2);
					$arr2 = $rs2->fetch(PDO::FETCH_ASSOC);

					$message = 'ajout_ok##'.$arr2['id_utilisateurs'];

				} else {
					$message = 'Le code saisi existe d&eacute;j&agrave;.';
				}
			}

			echo $message;

		break;

		

		case "timeline":
		
			$dom='<div id="vertical-timeline" class="vertical-container dark-timeline center-orientation">
					<div class="vertical-timeline-block">
						<div class="vertical-timeline-icon navy-bg">
							<i class="fa fa-handshake-o"></i>
						</div>

						<div class="vertical-timeline-content" style="width:40%">
							<h2><a href="#" onclick="showPlantations();">Coconuts</a></h2>
							<p>these farmers delivered coconuts within the past 20 days</p>
							
							<span class="vertical-date" style="left:150%;">
								2’000’000 Coconuts needed to produce <br/>
								<small>300 metric tons of Dried Copra </small>
							</span>
						</div>
					</div>

					<div class="vertical-timeline-block">
						<div class="vertical-timeline-icon blue-bg">
							<i class="fa fa-industry"></i>
						</div>

						<div class="vertical-timeline-content" style="width:40%">
							<h2><a href="#" onclick="showBuyers();">Copra</a></h2>
							<p>These Copra dryers delivered to HMA within the past 20 days a total of </p>
							
							<span class="vertical-date" style="right:150%;">
								300 metric tons of Copra were delivered to produce <br/>
								<small>155 metric tons of Coconut Oil </small>
							</span>
						</div>
					</div>

					<div class="vertical-timeline-block">
						<div class="vertical-timeline-icon navy-bg">
							<i class="fa fa-truck"></i>
						</div>

						<div class="vertical-timeline-content" style="width:40%">
							<h2>Containers</h2>
							<p>Containers were delivered to the port</p>
							
							<span class="vertical-date" style="left:150%;">
								<small>Containers </small>
							</span>
						</div>
					</div>
					
					<div class="vertical-timeline-block">
						<div class="vertical-timeline-icon blue-bg">
							<i class="fa fa-ship"></i>
						</div>

						<div class="vertical-timeline-content" style="width:40%">
							<h2>IRENES RESOLVE</h2>
							<p>
							ETD : 2017-04-07<br/>16:50:31<br/>
							<br/>
							ETA : 2017-04-19<br/>01:57:26<br/>
							
								<a href="javascript:showVessel(\'IRENES RESOLVE\');">show</a>
							</p>
							
							<span class="vertical-date" style="right:150%;">
								IRENES RESOLVE<br/>
								<small>Vessel </small>
							</span>
						</div>
					</div>
					
					<div class="vertical-timeline-block">
						<div class="vertical-timeline-icon navy-bg">
							<i class="fa fa-ship"></i>
						</div>

						<div class="vertical-timeline-content" style="width:40%">
							<h2>Onward Carriage</h2>
							<p>Onward Carriage from Antwerp to Basel</p>
							
							<span class="vertical-date" style="left:150%;">
								<small>Onward Carriage </small>
							</span>
						</div>
					</div>
					
				</div>';

		break;
	
	
		case "show_vessel_line":

			$ship_name = $_GET["ship_name"];
			
			$sql = "SELECT * FROM ship_tracking WHERE ship_name = '$ship_name' order by id ASC";

			$point_list ='';
			$result = pg_query($conn, $sql);
			while($row = pg_fetch_assoc($result)){
				$point_list .= $row['lat'].'#'.$row['lon'].'??';
			}
			
			$dom=$point_list;
			
		break;
		
		
		case "show_plantations":
	
			$sql="SELECT gid_plantation, area, year_creation, perimeter, insure, variety, code_farmer, culture, coordx, coordy,
			  id_culture, gid_town, public.ST_AsGeoJSON(plantations.geom,6) AS geojson, name_country, id_country, statut, name_manager,
			  code_parcelle, estimate_production, id_town, name_town, name_farmer, id_buyer, name_buyer, code_buyer,
			  cooperative_name AS name_farmergroup, id_cooperative AS id_farmergroup, id_farmer AS id_contact
			FROM public.v_plantation as plantations WHERE culture = 'Coconut' ";
				
			$result = pg_query($conn, $sql);
				
			$geojson = array(
				'type'      => 'FeatureCollection',
				'features'  => array()
			);			
				
			while ($arr = pg_fetch_assoc($result)) {
				$properties = $arr;
				unset($properties['geojson']);
				unset($properties['geom']);
				$feature = array(
					'type' => 'Feature',
					'geometry' => json_decode($arr['geojson'], true),
					'properties' => $properties					 
				);
				array_push($geojson['features'], $feature);
			}
				
			header('Content-type: application/json');
				
			$dom = json_encode($geojson['features'], JSON_NUMERIC_CHECK);
		
		break;
		
		
		case "show_buyers":
		
			$sql ="SELECT coord_x, coord_y, name_buyer FROM v_buyers, contact_profile
			  WHERE  contact_profile.id_contact = v_buyers.id_contact 
			AND contact_profile.company_type = 'BUYER'
			AND contact_profile.lang = '" . $lang['DB_LANG'] . "'
			AND id_country=1 "; 

			$result = pg_query($conn, $sql);  

			$li_buyer ='';
			
			while ($arr = pg_fetch_assoc($result)) {
				$li_buyer .= $arr['coord_x'].'#'.$arr['coord_y'].'#'.$arr['name_buyer'].'??';		
			}

			$dom = $li_buyer.'end'; 
		
		break;
	}	

}	


echo $dom;



?>