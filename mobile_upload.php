<?php

ini_set('display_errors', TRUE);
error_reporting(-1);

header('Access-Control-Allow-Origin: *');

include_once("fcts.php");
$conn=connect();

if(isset($_GET['func'])){ 
	$target_path = "uploads/". $_GET['func'] ."/";
} else { $target_path = "uploads/"; }
 
// mkdir($target_path, 0777);
$target_path = $target_path . basename($_FILES['file']['name']);
 
if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
	echo "Upload and move success";
	
	if($_GET['func'] == "data"){
		$queries = file_get_contents("uploads/data/" . basename( $_FILES['file']['name']));
		pg_query($conn, $queries);
	}
	
	if($_GET['func'] == "avatar"){
		$avatar_path = "img/avatar/" . basename( $_FILES['file']['name']);
		move_uploaded_file($_FILES['file']['tmp_name'], $avatar_path);
	}
	
} else {
	echo $target_path;
	echo "There was an error uploading the file, please try again!";
}

?>
