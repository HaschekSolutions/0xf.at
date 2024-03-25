<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));


if(file_exists('inc/config.inc.php'))
    include_once('inc/config.inc.php');
else exit('rename inc/example.config.inc.php to inc/config.inc.php first!');


$url = array_filter(explode('/',ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/')));

//check for integrated server
if(php_sapi_name()=='cli-server' && file_exists(implode('/',$url)))
    return false;

session_start();

require_once(ROOT.DS.'inc'.DS.'core.php');

// looks like an image for lvl 31

if($url[0]=='data'&&$url[1]=='imgs'&&strlen($url[2])==(32+4))
{
    $model = new HackitsModel();
    $o = $model->backend(['a'=>'img','id'=>$url[2]]);
}

//removeMagicQuotes();
$GLOBALS['params'] = $url;
callHook();