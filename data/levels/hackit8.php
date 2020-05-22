<?php


class Hackit8 implements Hackit
{
	public function getName() { return 'The password field';}
	public function getDescription(){return 'Nice, someone already entered the password but they made a small mistake.<br/>The FIRST occurring 0 (zero) should actually be a small "o". Can you fix it?';}
	public function getTags(){return 'HTML';}

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
		if(!$_SESSION['levels'][basename(__FILE__, '.php')] || !$_SESSION['wrong'][basename(__FILE__, '.php')])
		{
			$a = new Algorithms;
			$right = $a->get_random_string('abcdefghijkmnpqrstuvwxyz123456789',32);
			$wrong = $right;
			$zeropos = rand(0,31);

			$right[$zeropos] = 'o';
			$wrong[$zeropos] = '0';

			$_SESSION['levels'][basename(__FILE__, '.php')] = $right;
			$_SESSION['wrong'][basename(__FILE__, '.php')] = $wrong;
		}
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
            </script>























































































































































































<script type="text/javascript">
var _0x6a7e=["\x2F\x62\x61\x63\x6B\x65\x6E\x64\x2E\x70\x68\x70\x3F\x61\x3D\x73\x26\x6C\x3D\x38","\x70\x61\x72\x73\x65","\x72\x65\x73\x75\x6C\x74","\x76\x61\x6C","\x23\x70\x77","\x67\x65\x74","\x72\x65\x61\x64\x79"];$(document)[_0x6a7e[6]](function(){$[_0x6a7e[5]](_0x6a7e[0],function(_0xa388x1){var _0xa388x2=JSON[_0x6a7e[1]](_0xa388x1);$(_0x6a7e[4])[_0x6a7e[3]](_0xa388x2[_0x6a7e[2]]);})});
</script>';
	}
}