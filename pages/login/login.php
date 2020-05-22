<?php

/*
 * Login site
 */

class Login extends Page
{
    function setMenu()
    {
        if($_SESSION['user'])
        {
            $this->menu_text = 'Logout';
            $this->menu_image = 'fa fa-sign-out';
        }
        else
        {
            $this->menu_text = 'Login';
            $this->menu_image = 'fa fa-lock';
        }
        $this->menu_priority = 999;
    }
    
    function index()
    {
        $html = new HTML;
        $um = new UsersModel();
        $lv = new LoginView();
        global $url;

        if($_SESSION['user'])
        {
            unset($_SESSION['user']);
            $o = '<script>localStorage.sid = "";</script>';
            $o.= $html->goToLocation('/',false);
            $this->set('content',$o);
            return;
        }

        if($_POST['submit']=='Login')
        {
            $nick = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", trim($_POST['nick'])));
            $pw = trim($_POST['password']);

            $data = $um->loadUser($nick,$pw);

            if(!$nick||!$pw)
            {
                $o = $html->error('Please enter a nick and password');
            }
            else if(!$data)
            {
                $o = $html->error('Nick not found or wrong password');
            }
            else
            {
                $um->loginUser($data);
                
                
                if($_POST['save_sid']=='1')
                    $html->goToLocation('/login/savesid');
                else
                {
                    $html->goToLocation('/');
                }
            }
        }

        if($_POST['submit']=='Register')
        {
            $a = new Algorithms;
            $nick = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", trim($_POST['nick'])));
            $pw1 = trim($_POST['password1']);
            $pw2 = trim($_POST['password2']);

            $basedir = ROOT.DS.'data'.DS.'users'.DS;
            $filename = $basedir.$nick.'.txt';
            $captcha = false;

            if(defined('RECAPTCHA_KEY') && RECAPTCHA_KEY != '')
            {
                $captcha = true;
                $post = $a->post('https://www.google.com/recaptcha/api/siteverify',array('secret'=>RECAPTCHA_SECRET,'response'=>$_POST['g-recaptcha-response'],'remoteip'=>$_SERVER['REMOTE_ADDR']));
                $pj = json_decode($post,true);
            }

            if($captcha === true && !$pj['success'])
                $o = $html->error("Captcha failed");
            else if(file_exists($filename))
                $o = $html->error('This nick is already taken!');
            else if(!$nick || $nick != strtolower(trim($_POST['nick'])))
                $o = $html->error('Enter a valid nick (only alpha-numberic values)');
            else if($pw1!=$pw2)
                $o = $html->error('Your passwords don\'t match');
            else if(!$pw1 || strlen($pw1)<7)
                $o = $html->error('Use a password that is &gt; 7 characters');
            else
            {
                $data = $um->createUser($nick,$pw1);
                $um->loginUser($data);
                $html->goToLocation('/');
            }
        }
        
        $o.= $lv->renderLoginForm();
        $o.= $lv->renderRegisterForm();



        $this->set('title','0xf.at | Login/Register');
        $this->set('content',$o);
    }

    function bysid()
    {
        $html = new HTML();
        $sid = $this->params[0];
        $location = $this->params[1];

        $a = explode(';', $sid);
        $nick = base64_decode($a[0]);
        $nick = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", trim($nick)));

        if(!$sid){$o = '<script>localStorage.sid="";</script>';$this->set('content', $o);return false;}
        if(file_exists(ROOT.DS.'data'.DS.'users'.DS.$nick.'.txt'))
        {
            $um = new UsersModel();

            $data = $um->loadUser($nick,$sid,false);
            if($data)
                $um->loginUser($data);
            $loc = $_SESSION['triedtovisit']?$_SESSION['triedtovisit']:'/';
            unset($_SESSION['triedtovisit']);
            $html->goToLocation($loc);
        }
        else
        {
            $o = '<script>localStorage.sid="";</script>';
            $this->set('content', $o);
        }
    }

    function savesid()
    {
        $um = new UsersModel();
        $sid = $_SESSION['sid'];
        if($sid)
            $o = '<script>localStorage.sid = "'.$sid.'";window.location.href="/";</script>';
        //$o.= $sid;
        $this->set('content', $o);
    }
    
    function maySeeThisPage()
    {
        return true;
    }
}