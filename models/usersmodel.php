<?php

class UsersModel extends Model
{
    function createUser($nick,$password)
    {
        $cm = new CryptoModel();
        $basedir = ROOT.DS.'data'.DS.'users'.DS;
        $filename = $basedir.$nick.'.txt';

        $password = base64_encode($nick).';'.$cm->mySha512($password, SALT, 512,80);

        $data = array(  'registered'=>time(),
                        'session'=>session_id(),
                        'solved'=>array(),
                        'sid'=>$password,
                        'nick'=>$nick);
        $fh = fopen($filename, "w");
        fwrite($fh, $cm->encrypt(json_encode($data),$password,$nick));
        fclose($fh);

        return $data;
    }

    function loadUser($nick,$password,$multicrypt=true)
    {
        $basedir = ROOT.DS.'data'.DS.'users'.DS;
        $filename = $basedir.$nick.'.txt';
        if(!file_exists($filename)) return false;
        $encrypted = implode(NULL, file($filename));

        $cm = new CryptoModel;
        if($multicrypt)
            $password = base64_encode($nick).';'.$cm->mySha512($password, SALT, 512,80);
        $decrypted = $cm->decrypt($encrypted,$password,$nick);
        if(!$decrypted) return false;

        $decrypted = json_decode($decrypted,true);

        //var_dump("logged in with Nick: $nick PW: $password");

        return $decrypted;
    }

    function updateUser()
    {
        $cm = new CryptoModel;
        if(!$_SESSION['user']) return false;
        $data = $this->loadUser($_SESSION['user'],$_SESSION['sid'],false);
        $data['solvedtime'] = $_SESSION['solvedtime'];
        $data['solved'] = $_SESSION['solved'];

        $basedir = ROOT.DS.'data'.DS.'users'.DS;
        $filename = $basedir.$_SESSION['user'].'.txt';
        if(!file_exists($filename)) return false;
        $fh = fopen($filename, "w");
        fwrite($fh, $cm->encrypt(json_encode($data),$_SESSION['sid'],$_SESSION['user']));
        fclose($fh);

        //var_dump(json_encode($data));

        return true;
    }

    function loginUser($data)
    {
        //session_id($data['session']);
        $_SESSION['solved'] = $data['solved'];
        $_SESSION['user'] = $data['nick'];
        $_SESSION['sid'] = $data['sid'];
        $_SESSION['solvedtime'] = $data['solvedtime'];
    }
}