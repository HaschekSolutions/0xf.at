<?php

/*
 * Error handler
 */

class Error extends Page
{
    function setMenu()
    {
        $this->menu_text = '';
        $this->menu_priority = 9000;
    }
    
    function index()
    {
        $this->set('title','Socialcube - Test');
        $this->set('content_header','Header');
        $this->set('content',"fehlerrrr");
    }
    
    public function notfound()
    {
        $this->set('title','Error 404 - Not found');
        $this->set('content_header','Error <strong class="red">404</strong> - Seite nicht gefunden');
        $this->set('content',"Error! Page not found");
    }
    
    public function notallowed()
    {
        $this->set('title','Error 403 - Not allowed');
        $this->set('content_header','Error <strong class="red">403</strong> - Du bist nicht berechtigt, diese Seite aufrufen zu können');
        $this->set('content',"Not allowed");
    }
    
    function maySeeThisPage()
    {
            return true;
    }
    
    function submenu()
    {
        $this->addSubmenuItem('Übersicht', 'index');
        $this->addSubmenuItem('Bearbeiten', 'edit');
    }
}