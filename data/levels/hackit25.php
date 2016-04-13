<?php


class Hackit25 implements Hackit
{
	public function getName() { return 'WPA Cracking';}
	public function getDescription(){return 'Some jerk has hacked the wifi of my neighbor (who is currently on holiday) and changed the name to "tryandhackme" <span class="green">BSSID: c8:be:19:60:a6:d6</span><br/>
                                            I wrote my neighbor an email and he said I should try and change it back, so I started <a target="_blank" href="http://www.aircrack-ng.org/">Airodump</a> and was able to get a WPA handshake!<br/>
                                            All you have to do is crack the password from the iv (pcap) file. I also added a .hccap file you can crack with <a target="_blank" href="http://hashcat.net/hashcat/">hashcat</a>.<br/>The only hint I got is that it\'s an old wifi router that only allows 8 digit alphanumeric passwords.<br/>Also I got an anonymous mail that said "?l?l?l?d?d?d?d?d" .. whatever that means<br/><strong ><a class="green" href="/data/files/tryandhackme.zip">&gt;&gt; tryandhackme.zip (1 KB) &lt;&lt;</a></strong>';}
	public function getTags(){return 'Cracking';}

	public function isSolved()
	{
        if($_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
            return true;
        else
            return false;
	}

	public function prepare()
	{
        $_SESSION['levels'][basename(__FILE__, '.php')] = 'wsm25586';
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