<?php


class Hackit4 implements Hackit
{
	public function getName() { return 'Length matters';}
	public function getDescription(){return 'What does .length mean?';}
	public function getTags(){return 'JavaScript';}

	public function isSolved()
	{
		$a = new Algorithms;
		if($_REQUEST['pw']==$a->sumOfNumbersInString(session_id()))
			return true;
		else
			return false;
	}

	public function prepare()
	{
		$a = new Algorithms;
		$_SESSION['levels'][basename(__FILE__, '.php')] = $a->sumOfNumbersInString(session_id());
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
                    var pwinfo = "'.$a->get_random_string('abcdef 012345',$_SESSION['levels'][basename(__FILE__, '.php')]).'";
                    if(el.value==pwinfo.length)
                        window.location.href="?pw="+el.value;
                    else alert("Wrong password");
                }
            </script>';
	}
}