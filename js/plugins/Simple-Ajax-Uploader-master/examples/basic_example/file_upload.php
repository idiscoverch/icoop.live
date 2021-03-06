<?php
// $monfichier = fopen('test.txt', 'r+');
                            // ftruncate($monfichier,0);
                            // fputs($monfichier,dirname(__FILE__) . '/../../Uploader.php');
							
							
require(dirname(__FILE__) . '/../../extras/Uploader.php');

$monfichier = fopen('test.txt', 'r+');
                            ftruncate($monfichier,0);
                            fputs($monfichier,'3555');

// Directory where we're storing uploaded images
// Remember to set correct permissions or it won't work
$upload_dir = './';

$uploader = new FileUpload('uploadfile');

// Handle the upload
$result = $uploader->handleUpload($upload_dir);

if (!$result) {
  exit(json_encode(array('success' => false, 'msg' => $uploader->getErrorMsg())));  
}

echo json_encode(array('success' => true));
