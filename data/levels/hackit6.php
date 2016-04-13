<?php


class Hackit6 implements Hackit
{
	public function getName() { return 'Color codes';}
	public function getDescription(){return '<span id="nice" style="color:#cc3333">Nice</span> <span id="though" style="color:#99ff33">colors</span> <span id="colors" style="color:#33ffff">though</span>';}
	public function getTags(){return 'JavaScript';}

	public function isSolved()
	{
		$a = new Algorithms;
		if($_REQUEST['pw']=='99ff33' || rawurldecode($_REQUEST['pw'])=='#99ff33')
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
                    if(el.value==rgb2hex(document.getElementById("though").style.color))
                        window.location.href="?pw="+escape(el.value);
                    else window.location.href="?pw="+escape(el.value);
                }

                function rgb2hex(rgb) {
                    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
                    function hex(x) {
                        return ("0" + parseInt(x).toString(16)).slice(-2);
                    }
                    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
                }

            </script>';
	}
}