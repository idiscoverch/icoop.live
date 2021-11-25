<?php
	
// Check form token

define('IMG_PATH', 'uploads/mbtiles/');
	
	
// Check if file was uploaded
if( ! isset($_FILES['mbtile']) || ! is_uploaded_file($_FILES['mbtile']['tmp_name'])){
	$data='File not uploaded. #'.$_FILES["mbtile"]["error"];
	
} else {
	// And if it was ok
	if($_FILES['mbtile']['error'] !== UPLOAD_ERR_OK){
		$data='Upload failed. Error code: '.$_FILES['mbtile']['error'];
		
	} else {
		if (!file_exists(IMG_PATH)) {
			mkdir(IMG_PATH);
		}
		
		$filename = basename($_FILES['mbtile']['name']);
	
		$target_file_path = IMG_PATH . $filename;  

		move_uploaded_file($_FILES['mbtile']['tmp_name'], $target_file_path);
		
		if (file_exists($target_file_path)) {
			$data = '1##'.$filename.'##'.$_POST['mbtiles_desc'].'##'.$_POST['mbtiles_maptype'];
		} else {
			$data = "Not uploaded because of error #".$_FILES["mbtile"]["error"];
		}
	}
}

echo $data;