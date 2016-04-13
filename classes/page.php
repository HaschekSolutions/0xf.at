<?php


/**
 * Page is the Controller Class
 * which should be extended by any page
 *
 * @author Christian Haschek
 */
class Page
{
	protected $_controller;
	protected $_action;
	protected $_template;
    public $variables;
    public $params;
	public $render;
	public $menu_text;
    public $menu_image;
	public $menu_priority;
    public $submenu;

	function __construct($controller, $action, $r=1,$params=null)
	{
		$this->_controller = $controller;
		$this->_action = $action;
		//$this->_template = new Template($controller,$action);
		$this->render = $r;
                $this->submenu = array();
                //$this->variables = array();
                $this->setMenu();
                $this->params = $params;
                $this->menu_image = '/css/imgs/empty.png';
	}
        
        function setMenu()
        {
            $this->menu_text = '#';
            $this->menu_priority = 1;
        }
        
        /**
         * override this function to check if a user can use this object
         * @return true -> user will be able to access
         * @return false -> user will not be able to access and this page won't
         * be shown in the menu
         *  
         */
        public function maySeeThisPage()
        {
            return true;
        }

	function set($name,$value)
	{
		$this->variables[$name] = $value;
	}
        
    function addSubmenuItem($text, $action)
    {
        global $url;
        global $default;
        $u = explode('/',$url);
        $active = $u[1];
        if(!$active) $active = $default['action'];
        
        
        if($active == $action)
             $act = 1;
        else $act = 0;
        
        $this->submenu[] = array('text' => $text, 'action' => $action,'active'=>$act);
    }
    
    function submenu()
    {
        
    }
    
    function getSubmenu($active=0)
    {
        $prepare = array();
        if(!$_SESSION['user']) return $prepare;
        if(is_array($this->submenu))
            foreach($this->submenu as $key => $var)
                $prepare[] = array('text'=>$var['text'],'base'=>strtolower($this->_controller),'action'=>$var['action'],'active'=>$var['active']);
        
        return $prepare;
    }

	function __destruct()
	{
//		if($this->render)
//			$this->_template->render();
	}
        
        function getContent($vars=null)
        {
//            return $this->_template->renderToString($vars);
        }
        
        function render()
        {
            $html = new HTML;
            $menu = $html->menu(getMenu(),'','menu_li');
            //$submenu = $html->submenu(getSubMenu(),'','submenu_li');
            //$response = $html->error($_GET['e']);
            //$js  = $html->includeJs('backend_handler');
            $activepage = $this->_controller;

            if (file_exists(ROOT . DS . 'js' . DS . $this->_controller . '.js'))
                    $js.='<script src="'. DS . 'js' . DS . $this->_controller . '.js'.'"></script>';
            if (file_exists(ROOT . DS . 'css' . DS . $this->_controller . '.css'))
                    $meta.='<LINK rel="stylesheet" href="'. DS . 'css' . DS . $this->_controller . '.css'.'" type="text/css">';
            //var_dump($this->variables);
            if(is_array($this->variables))
                extract($this->variables);
            include (ROOT . DS . 'template.php');

        }
        
}