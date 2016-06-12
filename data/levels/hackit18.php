<?php


class Hackit18 implements Hackit
{
	public function getName() { return 'Morse code';}
	public function getDescription(){return 'The following password is encoded in morse code. Each character is seperated by a blank.<br/>After 60 seconds this page will submit/refresh automatically. So.. be faster than that.';}
	public function getTags(){return 'Programming';}

	public function isSolved()
	{
        if(strtolower($_REQUEST['pw'])==strtolower($_SESSION['morse_pw']))
            return true;
        else
            return false;
	}

	public function prepare()
	{
		//$a = new Algorithms;
		//$_SESSION['levels'][basename(__FILE__, '.php')] = strtolower(substr(md5(substr(session_id(), 2,10)),-25));
        if(!$_REQUEST['pw'] || ($_REQUEST['pw'] && !$this->isSolved()))
            $_SESSION['morse_pw'] = substr(md5($firstname.time().rand(1,2000)),0,8);
	}

	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
<pre><code class="language-php">'.$this->text2Morse($_SESSION['morse_pw']).'</code></pre>
        <input id="pw" type="password" />
        <br/><input type="button" value="OK" onClick="checkPW()"/>
        <script type="text/javascript">
            function checkPW()
            {
                var pw = document.getElementById("pw");
                window.location.href="?pw="+pw.value;
            }
            setTimeout(function(){checkPW();}, 60000);
        </script>';
	}

    function text2Morse($text)
    {
        $text = str_split($text);
        foreach($text as $char)
            $o.=$this->c2morse($char).' ';
        return $o;
    }

    function c2morse($c)
    {
        switch(strtoupper($c))
        {
            case '0': return '−−−−−';
            case '1': return '.−−−−';
            case '2': return '..−−−';
            case '3': return '...−−';
            case '4': return '....−';
            case '5': return '.....';
            case '6': return '−....';
            case '7': return '−−...';
            case '8': return '−−−..';
            case '9': return '−−−−.';
            case 'A': return '.−';
            case 'B': return '−...';
            case 'C': return '−.−.';
            case 'D': return '−..';
            case 'E': return '.';
            case 'F': return '..−.';
            case 'G': return '−−.';
            case 'H': return '....';
            case 'I': return '..';
            case 'J': return '.−−−';
            case 'K': return '−.−';
            case 'L': return '.−..';
            case 'M': return '−−';
            case 'N': return '−.';
            case 'O': return '−−−';
            case 'P': return '.−−.';
            case 'Q': return '−−.−';
            case 'R': return '.−.';
            case 'S': return '...';
            case 'T': return '−';
            case 'U': return '..−';
            case 'V': return '...−';
            case 'W': return '.−−';
            case 'X': return '−..−';
            case 'Y': return '−.−−';
            case 'Z': return '−−..';
            case ' ': return ' ';
        }
    }
}