<?php


class Hackit20 implements Hackit
{
	public function getName() { return 'MD5 cracking';}
	public function getDescription(){return 'The password of this level is an MD5 encrypted string which was calculated by combining two random words (without spaces) from <a href="/data/wordlist.zip">&gt;&gt;this wordlist&lt;&lt;</a> (144kb).<br/>Can you find out which two words were used?';}
	public function getTags(){return 'Cracking';}

	public function isSolved()
	{
        if(md5($_REQUEST['pw'])==md5($_SESSION['levels'][basename(__FILE__, '.php')]))
            return true;
        else
            return false;
	}

	public function prepare()
	{
        if(!$_SESSION['levels'][basename(__FILE__, '.php')])
            $_SESSION['levels'][basename(__FILE__, '.php')] = $this->getWords();

        //var_dump($_SESSION['levels'][basename(__FILE__, '.php')]);
        //var_dump(md5($_SESSION['levels'][basename(__FILE__, '.php')]));
	}

    private function getWords()
    {
        $lines = file(ROOT.DS.'data'.DS.'wordlist.txt');
        if(!$lines) return 'error';
        $count = count($lines);
        $w1 = trim($lines[rand(0,$count)]);
        $w2 = trim($lines[rand(0,$count)]);

        return $w1.$w2;
    }

	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
            <pre><code class="">'.md5($_SESSION['levels'][basename(__FILE__, '.php')]).'</code></pre><br/>
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