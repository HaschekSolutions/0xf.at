<?php

/*
 * Home site
 */

class Home extends Page
{
    function setMenu()
    {
        $this->menu_text = '<img height="60px;" src="/css/imgs/logo.png"/>';
        //$this->menu_image = 'fa fa-terminal';
        $this->menu_image = false;
        $this->menu_priority = 1;
    }
    
    function index()
    {
        $html = new HTML;
        $hv = new HomeView();

        $lv = new LevelView();

        $levellist = $lv->renderList();

        $o = '<pre><code class="language-css">p { color: red }</code></pre>';

        $changelog = $html->well('<h2>Changelog</h2>'.$html->liste(
            array(
            'Level 32 has been fixed',
            'Now sorted by id again',
            'Level list can now be sorted. By default sorted by difficulty',
            'Difficulty is now automatically calculated by the players performance',
            'You can now create an account to track your progress. No passwords are stored on our servers!',
            'We are now forcing https for the whole site'
            )));

        $reddit = '<a href="https://www.reddit.com/r/0xfat/"><i class="fa fa-reddit"></i> Visit us on reddit: /r/0xfat</a>';
        $twitter= $html->well($reddit.'<br/><br/><a class="twitter-timeline" href="https://twitter.com/AT0xf" data-widget-id="573564483446112256">Tweets von @AT0xf </a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>');

        $array = array($html->well('<h2>What\'s 0xf.at?</h2>'.
            $html->p('0xf.at (or oxfat it you prefer) is a website without logins or ads where you can solve password-riddles (so called hackits).').
            $html->p('This is a tribute site to the old <a href="http://isatcis.com">Starfleet Academy Hackits</a> site which has been offline for many years now.').

            '<h2>Can I contribute?</h2>'.
            $html->p('You sure can. If you have an awesome idea for a hackit, send it to <a href="mailto:office@haschek-solutions.com">office@haschek-solutions.com</a> and we\'ll gladly put it up and give you credit for it.')

            ).$levellist,$changelog.$twitter);

        $o = $html->row3to1($array);


        $this->set('title','Welcome to 0xf.at - The best hackits site since isatcis.com (not affiliated)');
        //$this->set('header','0xf.at hackits');
        $this->set('content',$o);
    }
    
    function maySeeThisPage()
    {
            return true;
    }
}