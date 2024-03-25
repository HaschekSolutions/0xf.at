<?php


class Hackit27 implements Hackit
{
	public function getName() { return 'TCP bot';}
	public function getDescription(){return 'Connect to the TCP server on <span class="green">'.IP.'</span> port <span class="green">2727</span> and do what it says.<br/>You\'ll have to program a bot that communicates with the server to solve the math problems fast enough.';}
	public function getTags(){return 'Programming';}

	public function isSolved()
	{
        $f = preg_match("/^[a-zA-Z0-9]{0,}$/", "", $_REQUEST['pw']);
        if(!$f)
            $f ='vvcxsaaasdadsasdads';
        $file = ROOT.DS.'data'.DS.'tmp'.DS.$f;
        if(file_exists($file) && $_REQUEST['pw'])
        {
            unlink($file);
            return true;
        }
        else
            return false;
	}

	public function prepare()
	{

	}


	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>

            Password:<br/><input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var pw = document.getElementById("pw");
                    window.location.href="?pw="+pw.value;
                }
            </script>';
	}
}
