<?php


class Hackit9 implements Hackit
{
	public function getName() { return 'Calculations';}
	public function getDescription(){return 'Let\'s think this through';}
	public function getTags(){return 'JavaScript';}

	public function isSolved()
	{
		$a = new Algorithms;
		if(strlen($_REQUEST['pw'])==$_SESSION['levels'][basename(__FILE__, '.php')])
			return true;
		else
			return false;
	}

	public function prepare()
	{
		$a = new Algorithms;
		$_SESSION['levels'][basename(__FILE__, '.php')] = 7+ceil(($a->sumOfNumbersInString(session_id())+17)/100);
	}

	public function render()
	{
		$a = new Algorithms;
		return '            <p>'.$this->getDescription().'</p>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var foo = 5 + 6 * 7;
                    var bar = foo % 8; //modulo.. look it up if you don\'t know what it does
                    var moo = bar + '.ceil(($a->sumOfNumbersInString(session_id())+17)/100).';
                    var rar = moo / 3;
                    var el = document.getElementById("pw");
                    if(el.value.length == moo)
                        window.location.href="?pw="+el.value;
                    else alert("Wrong password");
                }
            </script>';
	}
}