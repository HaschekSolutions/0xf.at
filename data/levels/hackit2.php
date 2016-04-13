<?php


class Hackit2 implements Hackit
{
	public function getDescription(){return 'Not that hard either..';}
	public function getName() { return 'JavaScript: Do you speak it?';}
	public function getTags(){return 'JavaScript';}

	public function isSolved()
	{
		if($_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
			return true;
		else
			return false;
	}

	public function prepare()
	{
		$_SESSION['levels'][basename(__FILE__, '.php')] = substr(md5(substr(session_id(), 5,10)),-5);
	}

	public function render()
	{
		$a = new Algorithms;
		return '                        <div>'.$this->getDescription().'</div>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var pw = "'.$a->stringToHex($_SESSION['levels'][basename(__FILE__, '.php')]).'";
                    var el = document.getElementById(\'pw\');
                    if(el.value==unescape(pw))
                        window.location.href="?pw="+el.value;
                    else alert("Wrong password");
                }
            </script>';
	}
}