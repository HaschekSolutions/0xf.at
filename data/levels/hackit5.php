<?php


class Hackit5 implements Hackit
{
	public function getName() { return 'ASCII fun';}
	public function getDescription(){return 'Have you heard of ASCII?';}
	public function getTags(){return 'JavaScript';}

	public function isSolved()
	{
		$a = new Algorithms;
		if($_REQUEST['pw']==(111+$a->sumOfNumbersInString(session_id())))
			return true;
		else
			return false;
	}

	public function prepare()
	{
		//$_SESSION['levels'][basename(__FILE__, '.php')] = substr(md5(substr(session_id(), 5,10)),-5);
	}

	public function render()
	{
		$a = new Algorithms;
		return '            <div>'.$this->getDescription().'</div>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var el = document.getElementById("pw");
                    if(el.value==(atoi("o")+'.$a->sumOfNumbersInString(session_id()).'))
                        window.location.href="?pw="+el.value;
                    else alert("Falsches Passwort");
                }

                // converts a character to its ASCII number
                function atoi(a)
                {
                   return a.charCodeAt();
                }
            </script>';
	}
}