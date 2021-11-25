<?php
session_start();
header('Cache-control: private'); // IE 6 FIX

$nav_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2); 

if(isSet($_GET['lang']))
{
$lang = $_GET['lang'];

// register the session and set the cookie
$_SESSION['lang'] = $lang;

setcookie("lang", $lang, time() + (3600 * 24 * 30));
}
else if(isSet($_SESSION['lang']))
{
$lang = $_SESSION['lang'];
}
else if(isSet($_COOKIE['lang']))
{
$lang = $_COOKIE['lang'];
}
else
{
	if($nav_lang == 'en'){ $lang = 'en'; }
	elseif($nav_lang == 'de'){ $lang = 'de'; }
	elseif($nav_lang == 'fr'){ $lang = 'fr'; }
	elseif($nav_lang == 'pt'){ $lang = 'pt'; }
	elseif($nav_lang == 'es'){ $lang = 'es'; }
	elseif($nav_lang == 'it'){ $lang = 'it'; }
	else { $lang = 'en'; }
}
$sitelang = $lang;
switch ($lang) {
  case 'en':
  $lang_file = 'lang_en.php';
  break;

  case 'de':
  $lang_file = 'lang_de.php';
  break;

  case 'fr':
  $lang_file = 'lang_fr.php';
  break;

  case 'pt':
  $lang_file = 'lang_pt.php';
  break;

  case 'es':
  $lang_file = 'lang_es.php';
  break; 
  
  case 'it':
  $lang_file = 'lang_it.php';
  break;

  default:
  $lang_file = 'lang_en.php';

}

include_once 'languages/'.$lang_file;
?>