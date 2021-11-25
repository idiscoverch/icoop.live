<?php

require __DIR__ . '/vendor/autoload.php';

$options = array(
    'cluster' => 'eu',
    'encrypted' => true
);
  
$pusher = new Pusher\Pusher(
    'e94927ee2910f4db65a6',
    '23cf6d6cc48dc3212019',
    '386520',
    $options
);
  
// $text = htmlspecialchars($_GET['message']);

if(isset($_GET['message'])){
	if($_GET['message'] == 'mail'){
		$text = 'New mail';
	} else
	if($_GET['message'] == 'document'){
		$text = 'New document';
	} else {
		$text = "Alert";
	}
} else {
	$text = "Alert";
}

$data['message'] = $text;
$pusher->trigger('my-channel', 'my-event', $data);

