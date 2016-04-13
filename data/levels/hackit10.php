<?php


class Hackit10 implements Hackit
{
	public function getName() { return 'Variables';}
	public function getDescription(){return 'Try not to be fooled';}
	public function getTags(){return 'JavaScript';}

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
		$_SESSION['levels'][basename(__FILE__, '.php')] = 'moo'.substr(md5(substr(session_id(), 1,9)),-3);
	}

	public function render()
	{
		$a = new Algorithms;
		return '            <p>'.$this->getDescription().'</p>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">var CodeCode = "'.$_SESSION['levels'][basename(__FILE__, '.php')].'";

                function checkPW()
                {
                    "+CodeCode+" == "0xf.at_hackit";
                    var el = document.getElementById("pw");
                    if(el.value == ""+CodeCode+"")
                        document.location.href="?pw="+el.value;
                    else alert("Wrong password");
                }
                
            </script>';
	}
}