<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include('fcts.php');
	
	$id_plantation = $_GET['id'];
	// $id_plantation = 200026;
	
	$conn=connect();
	
	$dom="";

	$sqlP="SELECT id_farmer/10000+to_char(now(),'MMDDHHMISS')::integer AS id_farmer FROM v_plantation WHERE gid_plantation = $id_plantation";
	$rstP = pg_query($conn, $sqlP);
	$arrP = pg_fetch_assoc($rstP);
	
	$new_id = $arrP['id_farmer'];
	
	require_once "cloudinary_php/autoload.php";
	
	\Cloudinary::config(array( 
		"cloud_name" => 'www-idiscover-live', 
		"api_key" => '582937155511965', 
		"api_secret" => 'dZlMbtlOCpES1RpKRgd64uiD-N8',
		"secure" => true
	));

	$image = 'https://sitetoimage.com/api/screenshot?token=token-768ce37760ce31a4dfd49014705d6970ffb80b9a1391d918c273932eb6650aa6&fileType=jpeg&width=1200&url=https://icoop.live/ic/farmer_doc.php?id='.$id_plantation;
 
	function file_get_contents_curl($url) { 
		$ch = curl_init(); 
	  
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_URL, $url); 
	  
		$data = curl_exec($ch); 
		curl_close($ch); 
	  
		return $data; 
	} 
  
	$data = file_get_contents_curl($image); 
	
	$fp = 'img/capture/'.$id_plantation.'.jpg'; 
  
	file_put_contents($fp, $data); 
	$dom = "File downloaded!";


	if (file_exists($fp)) {
		$filename = gmdate("Y-m-d_H-i").'_'.$id_plantation;
		
		$arr_result = \Cloudinary\Uploader::upload(__DIR__. '/'.$fp, array(
			"folder" => "farmer_document/",
			"public_id" => $filename,
		));
		
		// print_r($arr_result);

		if($arr_result){
			$doc_link = $arr_result['secure_url'];
			$doc_date = gmdate("Y/m/d H:i");

			$sql_stats = "INSERT INTO public.plantation_docs(
				id_plantdoc, plantation_id, doc_link, doc_type, doc_date)
			VALUES ($new_id, $id_plantation, '$doc_link', 649, '$doc_date')";

			$result = pg_query($conn, $sql_stats);
			if($result){
				$dom = '1##'.$doc_link;
			} else {
				$dom = "Echec de l'enregistrement";
			}
		}
	
	} else {
		$dom = "Le fichier n'existe pas.";
	}
	
	echo $dom;
?>
