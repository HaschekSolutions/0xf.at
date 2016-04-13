<?php	
session_start();

if(file_exists('inc/config.inc.php'))
    include_once('inc/config.inc.php');
else exit('rename inc/example.config.inc.php to inc/config.inc.php first!');

require_once (ROOT . DS . 'inc' . DS . 'core.php');
$url = $_GET['url'];
$o='';

$model = 'HackitsModel';

if(!class_exists($model) || !method_exists($model, 'backend')) return;

$model = new $model();
$o = $model->backend($_REQUEST);


if($o)
	echo json_encode($o);