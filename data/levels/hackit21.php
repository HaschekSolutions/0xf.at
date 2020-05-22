<?php


class Hackit21 implements Hackit
{
	public function getName() { return 'Unscramble words';}
	public function getDescription(){return 'The text below are semicolon seperated and scrambled words from <a href="/data/dictionary.zip">&gt;&gt;THIS DICTIONARY&lt;&lt;</a> (134 KB).<br/>Can you unscramble them <strong class="green">in 30 Seconds</strong> or less?';}
	public function getTags(){return 'Programming';}

	public function isSolved()
	{
        if((time()-$_SESSION['starttime'][basename(__FILE__, '.php')])<33 && $_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
            return true;
        else
            return false;
	}

	public function prepare()
	{
        if(!$_REQUEST['pw'] || ($_REQUEST['pw'] && !$this->isSolved()))
        {
            $words = $this->getRandomWords(10);
            $answer = implode(";", $words['orig']);
            $_SESSION['levels'][basename(__FILE__, '.php')] = $answer;
            $_SESSION['wrong'][basename(__FILE__, '.php')] = implode(";", $words['words']);
            $_SESSION['starttime'][basename(__FILE__, '.php')]=time();
        }
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
            <pre><code class="">'.$_SESSION['wrong'][basename(__FILE__, '.php')].'</code></pre><br/>
            <div><strong>Example:</strong><br/>
            Text: <span class="blue">masmei;ixamme;ineram;vpamrie</span><br/>
            Solution: <span class="green">sammie;maxime;marine;vampire</span><br/></div>
            Decrypted password:<br/><input id="pw" type="password" />
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

    private function getRandomWords($amount)
    {
        $lines = file(ROOT.DS.'data'.DS.'dictionary.txt');
        $words = array();
        $min = 1;
        $max = count($lines)-1;
        if(is_array($lines))
            for($i=0;$i<$amount;$i++)
            {
                $word = trim($lines[rand($min,$max)]);
                $words[] = str_shuffle($word);
                $orig[] = $word;
            }
            
        return array('words'=>$words,'orig'=>$orig);
    }
}