<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	ini_set('memory_limit', '-1');

	include('fcts.php');
	
	
	$conn=connect();
	
	$dom="";

	$sqlP="SELECT id_plantdoc, doc_link, plantation_id FROM plantation_docs WHERE id_plantdoc BETWEEN 428 AND 448";
	$rstP = pg_query($conn, $sqlP);
	while($arrP = pg_fetch_assoc($rstP)) {
	
		$doc_link = $arrP['doc_link'];
		$id_plantdoc = $arrP['id_plantdoc'];
		$plantation_id = $arrP['plantation_id'];
		
		require_once "cloudinary_php/autoload.php";
		
		\Cloudinary::config(array( 
			"cloud_name" => 'www-idiscover-live', 
			"api_key" => '582937155511965', 
			"api_secret" => 'dZlMbtlOCpES1RpKRgd64uiD-N8',
			"secure" => true
		));

		$fp = 'uploads/plantation/'.$doc_link; 
	  
		if (file_exists($fp)) {
			$filename = $plantation_id.'_'.gmdate("Y-m-d_H-i");
			
			$arr_result = \Cloudinary\Uploader::upload(__DIR__. '/'.$fp, array(
				"folder" => "mob_plantation/",
				"public_id" => $filename,
			));
			
			// print_r($arr_result);

			if($arr_result){
				$new_doc_link = $arr_result['secure_url'];

				$sql_stats = "UPDATE public.plantation_docs SET doc_link = '$new_doc_link' WHERE id_plantdoc = $id_plantdoc";
				$result = pg_query($conn, $sql_stats);
				
				if($result){
					$dom .= $id_plantdoc. '<br/>';
				} else {
					$dom .= $id_plantdoc. " Echec de l'enregistrement<br/>";
				}
			} else {
				$dom .= $id_plantdoc. " Upload Error<br/>";
			}
		
		} else {
			$dom .= $id_plantdoc. " Le fichier n'existe pas.<br/>";
		}
	}
	
	echo $dom;
?>
