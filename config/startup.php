<?php
session_cache_limiter ('private');
session_cache_expire(0);
session_start();
ini_set('display_errors','on'); 
error_reporting (E_ALL ^E_NOTICE);
if (version_compare(phpversion(), '5.1.0', '<') == true) { die ('PHP5.1 Only'); }
if (ini_get('safe_mode') != 0) die ('Please turn off safe mode');
header('Content-Type: text/html; charset=utf-8');

define ('DIRSEP', DIRECTORY_SEPARATOR);

//sciezka glowna
$main_path = dirname($_SERVER["SCRIPT_NAME"]);
define ('main_path', $main_path == DIRSEP ? '' : $main_path);

//ile zamowien na stronie
define ('max_records', 20);

//sciezka linkow
define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST'].main_path);

//Widoczne rozróżnienie na język czy tylko jeden język (pl) i wszystkie schowane
define ('MULTILANGUAGE', true);

// Stałe:

// pobieramy sciezke strony
$site_path = realpath(dirname(__FILE__) . DIRSEP . '..' . DIRSEP) . DIRSEP;
define ('site_path', $site_path);

//sciezki do templatek
define ('template_path', main_path . '/templates/');
define ('img_path', template_path .'img/');
define ('style_path', template_path . 'css/');
define ('js_path', template_path . 'js/');


// Smarty
define ('smarty_path', site_path . 'libraries' . DIRSEP . 'smarty' . DIRSEP);

$katalogi = array();
if (!is_writable(smarty_path . 'templates_c')) {
    $katalogi[] = smarty_path . 'templates_c';
}
if (!empty($katalogi)) die('Katalogi nie ustrawione do zapisu: <br>'.implode('<br>', $katalogi));

require smarty_path . 'Smarty.class.php';

// DATABASE
define ('adodb_path', site_path . 'libraries' . DIRSEP . 'adodb5' . DIRSEP);
require(adodb_path . 'adodb.inc.php');
require(adodb_path . 'adodb-exceptions.inc.php');

// Ładujemy klase
function __autoload($class_name) {
	$autoloadPaths = array('controllers','classes','modules','modules' . DIRSEP . 'DbConnectors','libraries');
	$filename = $class_name . '.php';
	foreach ($autoloadPaths as $dir) {
		$low_file = site_path . $dir . DIRSEP . strtolower($filename);
		$uc_file = site_path . $dir . DIRSEP . ucfirst($filename);
		if (file_exists($low_file) == true) {
			include_once($low_file);
			return true;
		} elseif (file_exists($uc_file) == true) {
			include_once($uc_file);
			return true;
		}
	}
	//die('Blad! Klasa '.$class_name.' nie istnieje.');
	return false;
}
$registry = new Registry;
?>