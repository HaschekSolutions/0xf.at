<?php

function __autoload($className)
{
	if (file_exists(ROOT . DS . 'models' . DS . strtolower($className) . '.php'))
		require_once(ROOT . DS . 'models' . DS . strtolower($className) . '.php');
    if (file_exists(ROOT . DS . 'classes' . DS . strtolower($className) . '.php'))
		require_once(ROOT . DS . 'classes' . DS . strtolower($className) . '.php');
    if (file_exists(ROOT . DS . 'pages' . DS . strtolower($className) . DS . strtolower($className) . '.php'))
        require_once(ROOT . DS . 'pages' . DS . strtolower($className) . DS . strtolower($className) . '.php');
    if (file_exists(ROOT . DS . 'pages' . DS . strtolower($className) . DS . strtolower($className) . '.php'))
        require_once(ROOT . DS . 'pages' . DS . strtolower($className) . DS . strtolower($className) . '.php');
    if (file_exists(ROOT . DS . 'pages' . DS . substr(strtolower($className),0,-4) . DS . substr(strtolower($className),0,-4). 'View.php'))
        require_once(ROOT . DS . 'pages' . DS . substr(strtolower($className),0,-4) . DS . substr(strtolower($className),0,-4). 'View.php');

    if (file_exists(ROOT . DS . 'data' . DS.'levels' .DS. strtolower($className) . '.php'))
        require_once(ROOT . DS . 'data' . DS.'levels' .DS. strtolower($className) . '.php');
}

function stripSlashesDeep($value)
{
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function getLang()
{
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    switch($lang)
    {
        case 'de':return 'de';
        default: return 'en';
    }
}

function removeMagicQuotes()
{
    if ( get_magic_quotes_gpc() )
    {
            $_GET    = stripSlashesDeep($_GET   );
            $_POST   = stripSlashesDeep($_POST  );
            $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

function callHook()
{
    global $url;
    global $default;

    $queryString = array();
    
    if (!isset($url))
    {
            $controller = 'home';
            $action = 'index';
    }
    else
    {
            //$url = routeURL($url);
            $urlArray = explode("/",$url);
            $controller = $urlArray[0];
            array_shift($urlArray);
            $params = $urlArray;
            if (isset($urlArray[0]))
            {
                    $action = $urlArray[0];
                    array_shift($urlArray);
            }
            else
                    $action = 'index'; // Default Action
            $queryString = $urlArray;
    }

    if(!file_exists(ROOT . DS . 'pages' . DS . $controller . DS . $controller . '.php'))
    {
            $controller = 'error';
            $action = 'notfound';
            $queryString = array('error'=>$url);
    }


    $controllerName = ucfirst($controller);

    $dispatch = new $controllerName($controller,$action,false);

    if(!$dispatch->maySeeThisPage()) 
    {
        $controllerName = 'error';
        $action = 'notallowed';
        $dispatch = new $controllerName('error',$action,true);
    }
    else
        $dispatch = new $controllerName($controller,$action,true,$queryString);

    if (method_exists($controllerName, $action))
    {
        call_user_func_array(array($dispatch,$action),$queryString);
    } else if(method_exists($controllerName, 'catchAll'))
        call_user_func_array(array($dispatch,'catchAll'),array($params));
    
    //var_dump($dispatch);
    
    $dispatch->render();
}

function getMenu()
{
    $arr = array();
    if ($handle = opendir(ROOT . DS . 'pages'))
    {
            global $url;
            global $default;
            $u = explode('/',$url);
        while (false !== ($file = readdir($handle)))
        {
            if($file=='.' || $file=='..') continue;
            if(is_dir(ROOT . DS . 'pages'. DS. $file))
            {
                $ur = '';
                $ur = strtolower($u[0]);
                if(!$ur) $ur = $default['controller'];
                $class = ucfirst($file);
                if(!class_exists($class)) continue;
                $page = new $class($class,'',false);
                if(!$page->maySeeThisPage()) continue;
                $page->setMenu();
                if(!$page->menu_text) continue;
                if($page->menu_image)
                    $arr[$class]['text'] = '<i class="'.$page->menu_image.' menu_icon_mdi"></i><br/>'.$page->menu_text;
                else
                    $arr[$class]['text'] = '<i class="menu_icon_mdi">'.$page->menu_text.'</i>';
                $arr[$class]['priority'] = $page->menu_priority;
                if($page->menu_class)
                    $arr[$class]['class'] = $page->menu_class;
                if($ur==strtolower($class))
                    $arr[$class]['active'] = 1;
                else $arr[$class]['active'] = 0;
                
                //var_dump($page);
            }
        }
        closedir($handle);
    }
    return $arr;
}

function getSubMenu($controller=false)
{
    if(!$controller)
    {
        global $url;
        global $default;
        $u = explode('/',$url);
        $active = $u[0];
        if(!$active) $active = $default['controller'];
    }
    else
        $active = $controller;
    
    $class = ucfirst($active);
    if(!class_exists($class) || !method_exists($class, 'submenu')) return;
    $page = new $class($class,'submenu',false);
    $page->submenu();
    return $page->getSubmenu($active);
}

function aasort (&$array, $key)
{
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

function loadLevel($name)
{
    $file = ROOT.DS.'levels'.DS.$name.'.html';
    if(file_exists($file))
    {
        ob_start();
        include($file);
        $o = ob_get_clean();    
    }

    $file = ROOT.DS.'levels'.DS.$name.'.php';
    if(file_exists($file))
    {
        ob_start();
        include($file);
        $o = ob_get_clean();    
    }

    $o.= "\n\n".'           <script type="text/javascript">document.onkeydown=function(){if(window.event.keyCode==\'13\'){checkPW();}} //submit on enter</script>'."\n";
    return $o;
}