<?php

class Hackit1 implements Hackit
{
	public function getName() { return 'Easy beginnings';}
	public function getDescription(){return 'Use the source!';}
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
		$_SESSION['levels'][basename(__FILE__, '.php')] = 'tooeasy';
	}

	public function render()
	{
		return '            <div>Easy beginnings</div>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var el = document.getElementById(\'pw\');
                    if(el.value=="'.$_SESSION['levels'][basename(__FILE__, '.php')].'")
                        document.location.href="?pw="+el.value;
                    else alert("Wrong password!");
                }
            </script>';
	}
}