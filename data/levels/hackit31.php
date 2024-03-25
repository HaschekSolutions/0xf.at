<?php


class Hackit31 implements Hackit
{
    private $views = 50;
	public function getName() { return 'Server admin';}
	public function getDescription(){return 'If you are a <span class="blue">server admin</span> or own a website this shouldn\'t be so hard.<br/>Below you see the URL of an image.<br/>
                                            The image is actually a php script that records how many times this image has been seen.<br/>
                                            To solve this level the image has to be accessed by <span class="green">'.$this->views.' unique IP addresses</span><br/>
                                            This window will refresh every 10 seconds and update the view counter you see below<br/>
                                            Keep in mind you have to keep your browser window open so your session won\'t run out';}
	public function getTags(){return 'Sysadmin';}

	public function isSolved()
	{
        if($this->getImageCount()>=$this->views)
            return true;
        else
            return false;
	}

	public function prepare()
	{
        if(!$_SESSION['levels'][basename(__FILE__, '.php')])
        {
            $_SESSION['levels'][basename(__FILE__, '.php')] = md5(session_id().rand(0,10000));
            while(file_exists(ROOT.DS.'data'.DS.'tmp'.DS.'lvl31_'.$_SESSION['levels'][basename(__FILE__, '.php')].'.txt'))
            {
                $_SESSION['levels'][basename(__FILE__, '.php')] = md5(session_id().rand(0,10000));
            }
        }
	}

    private function getImageCount()
    {
        $file = ROOT.DS.'data'.DS.'tmp'.DS.'lvl31_'.$_SESSION['levels'][basename(__FILE__, '.php')].'.txt';
        if(!file_exists($file)) return '0';
        return (int)count(file($file));
    }

    public function renderImage($id)
    {
        header ("Content-type: image/png");
        if(strpos($id,'.')) // if there is a file extension, remove it
            $id = substr($id,0,strpos($id,'.'));
        $im = imageCreateFromPng(ROOT.DS.'data'.DS.'imgs'.DS.'0xfat_button.png');
        $background_color = ImageColorAllocate($im, 255,255,255);
        $file = ROOT.DS.'data'.DS.'tmp'.DS.'lvl31_'.$id.'.txt';

        if($this->isIPCounted($_SERVER['REMOTE_ADDR'],$id))
        {
            $fh = fopen($file, 'a');
            fwrite($fh, $_SERVER['REMOTE_ADDR']."\n");
            fclose($fh);
        }
        ImagePNG ($im);
    }

    private function isIPCounted($ip,$id)
    {
        $ip = $ip."\n";
        $file = ROOT.DS.'data'.DS.'tmp'.DS.'lvl31_'.$id.'.txt';
        if(!file_exists($file)) return true;
        $lines = file($file);
        $exists = in_array($ip, $lines);

        if($exists==true) return false;
        return true;
    }

	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
        <h3>Your image has been seen by <span class="blue">'.$this->getImageCount().'</span> unique IP addresses</h3>
        <h4 class="green">Finished '.round(($this->getImageCount()/$this->views)*100).'%</h4><br/>
        
        <h4>Image URL:</h4>
        <pre><code class="">https://www.0xf.at/data/imgs/'.$_SESSION['levels'][basename(__FILE__, '.php')].'.jpg</code></pre><br/>

        <h4>HTML code to include on your website:</h4>
        <pre><code class="">'.htmlentities('<a href="https://www.0xf.at"><img src="https://www.0xf.at/data/imgs/'.$_SESSION['levels'][basename(__FILE__, '.php')].'.jpg" /></a>').'</code></pre>

        <br/><input type="button" value="Refresh" onClick="checkPW()"/>
        <script type="text/javascript">
            function checkPW()
            {
                window.location.href="?pw=";
            }
            setTimeout(function(){checkPW();}, 10000);
        </script>';
	}
}