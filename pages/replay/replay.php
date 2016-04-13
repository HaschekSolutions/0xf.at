<?php

/*
 * Replay site
 */

class Replay extends Page
{
    function setMenu()
    {
        $this->menu_text = '';
        //$this->menu_image = 'fa fa-terminal';
        $this->menu_image = false;
        $this->menu_priority = 999;
    }
    
    function index()
    {
        $html = new HTML;
        $hv = new HomeView();
        $o = $html->goToLocation('/');


        $this->set('title','Welcome to 0xf.at - The best hackits site since isatcis.com (not affiliated)');
        //$this->set('header','0xf.at hackits');
        $this->set('content',$o);
    }

    function catchall($params)
    {
        $html = new HTML;
        $level = $params[0];
        if(!$level){$this->index();return;}

        if(file_exists(ROOT.DS.'data'.DS.'levels'.DS.'hackit'.$level.'.php'))
        {
            unset($_SESSION['solved'][$level]);
            unset($_SESSION['solvedtime'][$level]);
            unset($_SESSION['levels']['hackit'.$level]);
            if($_SESSION['user'])
            {
                $um = new UsersModel;
                $um->updateUser();
            }
            $html->goToLocation('/play/'.$level);
        }
        else
            $html->goToLocation('/');
    }
    
    function maySeeThisPage()
    {
            return true;
    }
}