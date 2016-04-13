<?php

/*
 * Levels site
 */

class Level extends Page
{
    function setMenu()
    {
        $this->menu_text = 'Levels';
        $this->menu_image = 'fa fa-list';
        $this->menu_priority = 3;
    }
    
    function index()
    {
        $html = new HTML;
        $lv = new LevelView();

        $o = $lv->renderList(true);
        $o.= $lv->renderGraph();
        $o.= $lv->renderClockReport();


        $this->set('title','Welcome to 0xf.at - The best hackits site since isatcis.com (not affiliated)');
        //$this->set('header','0xf.at hackits');
        $this->set('content',$o);
    }
    
    function maySeeThisPage()
    {
        return true;
    }
}