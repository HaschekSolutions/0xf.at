<?php


class Hackit23 implements Hackit
{
	public function getName() { return 'Easy sockets';}
	public function getDescription(){return 'Jerry set up a TCP server on <span class="green">'.IP.'</span> port <span class="green">2323</span> that holds the password for this level.<br/>All you have to do is connect (with the "telnet" command or <a target="_blank" href="http://netcat.sourceforge.net/">"netcat"</a>), send the auth token below and wait for the password.';}
	public function getTags(){return 'Programming';}

	public function isSolved()
	{
        $a = new Algorithms;
        if(strlen($_REQUEST['pw'])==round($a->sumOfNumbersInString($_SESSION['levels'][basename(__FILE__, '.php')])/3))
            return true;
        else
            return false;
	}

	public function prepare()
	{
        $a = new Algorithms;
        if(!$_REQUEST['pw'] || ($_REQUEST['pw'] && !$this->isSolved()))
        {
            $word = hash('sha256',rand(1,2000).time());
            $_SESSION['levels'][basename(__FILE__, '.php')] = $word;
        }
        if(!$_REQUEST['pw'])
            $_SESSION['starttime'][basename(__FILE__, '.php')]=time();
	}


	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
            <pre><code class="">'.$a->getRandomHash($a->sumOfNumbersInString($_SESSION['levels'][basename(__FILE__, '.php')])).'</code></pre><br/>
            Decrypted password:<br/><input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var pw = document.getElementById("pw");
                    window.location.href="?pw="+pw.value;
                }
            </script>';
	}
}