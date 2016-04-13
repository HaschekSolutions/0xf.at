<?php
session_start();
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

if(file_exists('inc/config.inc.php'))
    include_once('inc/config.inc.php');
else exit('rename inc/example.config.inc.php to inc/config.inc.php first!');

require_once(ROOT.DS.'inc'.DS.'core.php');
$url = $_GET['url'];
removeMagicQuotes();
$GLOBALS['params'] = explode('/', $_GET['url']);
callHook();