<?php

/*
 * Levels site
 */

class Create extends Page
{
    function setMenu()
    {
        $this->menu_text = 'Create';
        $this->menu_image = 'fa fa-plus-square-o';
        $this->menu_priority = 4;
    }
    
    function index()
    {
        $html = new HTML;
        $cv = new CreateView();

        $o = $cv->test();


        $this->set('title','Welcome to 0xf.at - The best hackits site since isatcis.com (not affiliated)');
        //$this->set('header','0xf.at hackits');
        $this->set('content',$o);
    }
    
    function maySeeThisPage()
    {
        return false;
        if($_SESSION['user'])
            return true;
        else return false;
    }
}