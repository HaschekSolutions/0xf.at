<?php


class Hackit24 implements Hackit
{
	public function getName() { return 'Packet analyzing';}
	public function getDescription(){return 'To get back at Jerry for his sh*t I\'ve installed a packet tracer on his computer and sniffed some traffic with <a target="_blank" href="https://www.wireshark.org/">Wireshark</a>.<br/>
                                            He always uses unencrypted FTP to get his tools from a computer in his LAN.<br/>
                                            Can you find his username and password?<br/><strong ><a class="green" href="/data/files/ftp.pcap.gz">&gt;&gt; ftp.pcap.gz (2 KB) &lt;&lt;</a></strong>';}
	public function getTags(){return 'Analyzing';}

	public function isSolved()
	{
        $a = new Algorithms;
        if($_REQUEST['user'] == 'jerry' && $_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
            return true;
        else
            return false;
	}

	public function prepare()
	{
        $_SESSION['levels'][basename(__FILE__, '.php')] = 'saymynameheisenberg';
	}


	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
            Username:<br/><input id="user" type="text" /><br/><br/>
            Password:<br/><input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var pw = document.getElementById("pw");
                    var user = document.getElementById("user");
                    window.location.href="?pw="+pw.value+"&user="+user.value;
                }
            </script>';
	}
}