<?php


class Hackit22 implements Hackit
{
	public function getName() { return 'Fast mirror';}
	public function getDescription(){return 'You have <strong class="green"> 10 Seconds</strong> to mirror the text below after the last character';}
	public function getTags(){return 'Programming';}

	public function isSolved()
	{
        if((time()-$_SESSION['starttime'][basename(__FILE__, '.php')])<13 && $_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
            return true;
        else
            return false;
	}

	public function prepare()
	{
        if(!$_REQUEST['pw'] || ($_REQUEST['pw'] && !$this->isSolved()))
        {
            $word = md5(rand(1,2000).time());
            $_SESSION['levels'][basename(__FILE__, '.php')] = $word.substr(strrev($word),1);
            $_SESSION['word'][basename(__FILE__, '.php')] = $word;
            $_SESSION['starttime'][basename(__FILE__, '.php')]=time();
        }
	}


	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
            <pre><code class="">'.$_SESSION['word'][basename(__FILE__, '.php')].'</code></pre><br/>
            <div><strong>Example:</strong><br/>
            Text: <span class="blue">abcdefg1234567890</span><br/>
            Solution: <span class="blue">abcdefg1234567890</span><span class="green">987654321gfedcba</span><br/><br/></div>
            Decrypted password:<br/><input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var pw = document.getElementById("pw");
                    window.location.href="?pw="+pw.value;
                }
                setTimeout(function(){checkPW();}, 10000);
            </script>';
	}
}