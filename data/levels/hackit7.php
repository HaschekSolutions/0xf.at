<?php


class Hackit7 implements Hackit
{
	public function getName() { return 'Jerry\'s screw-up';}
	public function getDescription(){return 'Jerry f*cked up, he forgot the password for this level but he mumbled something about a robots.txt file and something about a hint..';}
	public function getTags(){return 'Logic';}

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
		$_SESSION['levels'][basename(__FILE__, '.php')] = 'jerryIsDaBoss'.substr(md5(substr(session_id(), 1,4)),-2);
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
                var el = document.getElementById("pw");
                window.location.href="?pw="+el.value;
            }
        </script>';
	}
}