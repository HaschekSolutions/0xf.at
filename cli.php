<?php	
session_start();
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('SALT','FhBrftYp6tHdRWIWEhZ10t1xR6WRF7wWnrRWdzQI9rL1R6u0B7MYjuVGqz3Jbx1VDgkMbtXYkWgdbLNq7HPD8Bc0hsDEQxwL5P7isKuelE37pNyggRJ2T4QTM696mvkP');
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors','On');

require_once (ROOT . DS . 'inc' . DS . 'core.php');
$url = $_GET['url'];
//removeMagicQuotes();
//callHook();

$o='';

$model = 'HackitsModel';

if(!class_exists($model) || !method_exists($model, 'backend')) return;

$model = new $model();
$o = $model->backend($_REQUEST);

$h = new Hackit28;


$h->testIfItWorks();