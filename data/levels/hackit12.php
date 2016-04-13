<?php


class Hackit12 implements Hackit
{
	public function getName() { return 'Sums';}
	public function getDescription(){$a = new Algorithms;return 'The password is the sum of all numbers from 1 to '.(404+$a->sumOfNumbersInString(session_id()));}
	public function getTags(){return 'Mathematics';}

	public function isSolved()
	{
		$a = new Algorithms;
		if($_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
			return true;
		else
			return false;
	}

	public function prepare()
	{
		$a = new Algorithms;
		$_SESSION['levels'][basename(__FILE__, '.php')] = $a->sumBetween(1,(404+$a->sumOfNumbersInString(session_id())));
	}

	public function render()
	{
		$a = new Algorithms;
		return '            <p>'.$this->getDescription().'</p>
            <br/>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">

                function checkPW()
                {
                    var el = document.getElementById("pw");
                    document.location.href="?pw="+el.value;
                }
                
            </script>';
	}
}