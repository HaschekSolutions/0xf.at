<?php


class Hackit26 implements Hackit
{
	public function getName() { return 'Image offset';}
	public function getDescription(){$html = new HTML;return 'The image you see below contains the password to this level<br/><h4 class="green">Rules:</h4>'.implode('<br/>',
                            array('Every horizontal line stands for one letter',
                                'The pixel\'s offset from the left in each line is equal to its number of the letter in the alphabet (eg: offset 0: A, offset 25: Z)',
                                'The password is not case sensitive',
                                'The color of each pixel is random (but never white) and has no meaning',
                                'When you reload the page a new image (and therefore new password) is generated',
                                'You have <strong class="blue">30 seconds</strong> to solve this level'
                                ));}
	public function getTags(){return 'Programming';}

	public function isSolved()
	{
        if((time()-$_SESSION['starttime'][basename(__FILE__, '.php')])<33 && strtolower($_REQUEST['pw'])==strtolower($_SESSION['levels'][basename(__FILE__, '.php')]['answer']))
        {
            $filename = ROOT.DS.'data'.DS.'tmp'.DS.$_SESSION['levels'][basename(__FILE__, '.php')]['image'];
            if(file_exists($filename))
            unlink($filename);
            return true;
        }
        else
            return false;
	}

	public function prepare()
	{
        if(!$_REQUEST['pw'] || ($_REQUEST['pw'] && !$this->isSolved()))
        {
            $a = new Algorithms;
            $image = md5(substr(session_id(),-8)).'.png';
            $_SESSION['levels'][basename(__FILE__, '.php')]['answer'] = strtolower($a->getRandomWords(4));
            $_SESSION['levels'][basename(__FILE__, '.php')]['image'] = $image;
            $im = ImageCreate(26, strlen($_SESSION['levels'][basename(__FILE__, '.php')]['answer']))
                or die ("ERROR");
            $background_color = ImageColorAllocate ($im, 255, 255, 255);
            $this->doYourMagic($im,$_SESSION['levels'][basename(__FILE__, '.php')]['answer']);
            ImagePNG($im,ROOT.DS.'data'.DS.'tmp'.DS.$image);
            $_SESSION['starttime'][basename(__FILE__, '.php')]=time();
        }
	}

    function doYourMagic($im,$word)
    {
        $a = new Algorithms;
        $word = strtoupper($word);
        for($i=0;$i<strlen($word);$i++)
        {
            $letter = $word[$i];
            $off = $a->letterToNumber($letter);
            $c = imagecolorallocate($im, rand(0,200), rand(0,200), rand(0,200));
            //$c = ImageColorAllocate ($im, 0, 0, 0);
            
            imagesetpixel($im, $off, $i, $c);
            //echo "$letter";
        }

        return $im;
    }


	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
            <div>
                <img src="/data/tmp/'.md5(substr(session_id(),-8)).'.png?anticache='.rand(0,10000).md5(rand(1,2000)).'"/>
            </div>

            Password:<br/><input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var pw = document.getElementById("pw");
                    window.location.href="?pw="+pw.value;
                }
                setTimeout(function(){checkPW();}, 30000);
            </script>';
	}
}