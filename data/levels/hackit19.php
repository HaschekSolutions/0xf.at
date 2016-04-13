<?php


class Hackit19 implements Hackit
{
	public function getName() { return 'Telephone codes';}
	public function getDescription(){return 'Remember them old phone pads?';}
	public function getTags(){return 'Logic,Programming';}

	public function isSolved()
	{
        if((time()-$_SESSION['starttime'][basename(__FILE__, '.php')])<18 && $_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
            return true;
        else
            return false;
	}

	public function prepare()
	{
		//$a = new Algorithms;
        if(!$_REQUEST['pw'] || ($_REQUEST['pw'] && !$this->isSolved()))
        {
            $word = md5(time().session_id().rand(0,100000));
            $answer = $this->L34Algo($word);
            $_SESSION['levels'][basename(__FILE__, '.php')] = $answer;
            $_SESSION['phonecode'] = $word;
            $_SESSION['starttime'][basename(__FILE__, '.php')]=time();
        }
	}

	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
        <div>
                <img src="/data/imgs/keypad.png" /><br/><br/>
            The following password has been encrypted by the telephone alphabet pattern. The solution for this level is the <strong>digit sum</strong> of the number code<br/><br/>
            <strong>Example:</strong><br/>
            A =&gt; <span class="blue">22</span><br/>
            B =&gt; <span class="blue">222</span><br/>
            C =&gt; <span class="blue">2222</span><br/>
            1 =&gt; <span class="blue">1</span><br/>
            2 =&gt; <span class="blue">2</span><br/>
            5 =&gt; <span class="blue">5</span><br/>
            I =&gt; <span class="blue">4444</span><br/>
            W =&gt; <span class="blue">99</span><br/>
            Z =&gt; <span class="blue">99999</span><br/><br/>
            
            <p>"HEY" =&gt; <span class="blue">444 333 9999</span> =&gt; <span class="blue">4+4+4+3+3+3+9+9+9+9</span> = <u>57</u></p>
            
            <p>You have <strong>15 seconds</strong> to submit the right solution. Each time you reload this page the password changes.</p>
        </div>
<pre><code class="">'.$_SESSION['phonecode'].'</code></pre>
        <input id="pw" type="password" />
        <br/><input type="button" value="OK" onClick="checkPW()"/>
        <script type="text/javascript">
            function checkPW()
            {
                var pw = document.getElementById("pw");
                window.location.href="?pw="+pw.value;
            }
            setTimeout(function(){checkPW();}, 15000);
        </script>';
	}

    function L34Algo($word)
    {
        $zisu=0;
        for($i=0;$i<strlen($word);$i++)
        {
            $l = substr($word, $i, 1);
            $code=$this->letterToTelCode($l);
            for($j=0;$j<strlen($code);$j++)
            {
                $z = substr($code, $j, 1);
                $zisu+=$z;
            }
        }
        
        return $zisu;
    }

    function letterToTelCode($letter)
    {
        if(strlen($letter)!=1) return false;
        if(is_numeric($letter)) return $letter;
        $letter = strtoupper($letter);
        switch($letter)
        {
            case 'A': return 22;
            case 'B': return 222;
            case 'C': return 2222;
            case 'D': return 33;
            case 'E': return 333;
            case 'F': return 3333;
            case 'G': return 44;
            case 'H': return 444;
            case 'I': return 4444;
            case 'J': return 55;
            case 'K': return 555;
            case 'L': return 5555;
            case 'M': return 66;
            case 'N': return 666;
            case 'O': return 6666;
            case 'P': return 77;
            case 'Q': return 777;
            case 'R': return 7777;
            case 'S': return 77777;
            case 'T': return 88;
            case 'U': return 888;
            case 'V': return 8888;
            case 'W': return 99;
            case 'X': return 999;
            case 'Y': return 9999;
            case 'Z': return 99999;
        }
    }
}