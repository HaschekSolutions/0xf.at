<?php


class Hackit37 implements Hackit
{
    private $level = '';
	public function __construct(){$this->level = basename(__FILE__, '.php');} //so you can use $this->level to get the level id

	public function getName() { return 'Simple WPA cracking';}	//The name will be displayed in the level table
	public function getDescription(){return nl2br('Jerry got an email from a beautiful girl.
                                                   She sent him some dumped Wifi packages (<a href="/data/files/lvl37.pcap">&gt;&gt; Download &lt;&lt;</a>) and said she\'d go out on a date with him if he can
                                                   find out ');} //This will be displayed when the level is started
	public function getTags(){return 'Cracking';} //Describe what technology you used. Comma,seperated

	/**
	 *
	 * This method is called to check if the 
	 * level has been solved. if it returns
	 * true, it's solved
	 *
	 * @return      bool
	 *
	 */
	public function isSolved()
	{
		if($_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')]['answer'])
			return true;
		else
			return false;
	}


	public function prepare()
	{

        if(is_array($_SESSION['levels'][basename(__FILE__, '.php')])) return;
        $solutions = array('her local IP address'=>'192.168.2.7',
                           'the multicast IP, her Chromecast keeps sending packages to'=>'224.0.0.251',
                           'her wifi password'=>'11111111',
                           'which frame no. the first TCP retransmission package had'=>'212',
                           'which external IP address her phone repeatedly sent TCP packages to'=>'216.58.208.46');

        $index = array_rand($solutions);
		$_SESSION['levels'][basename(__FILE__, '.php')] = array('question'=> $index,'answer'=>$solutions[$index]);
	}

	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'<strong>'.$_SESSION['levels'][basename(__FILE__, '.php')]['question'].'</strong></p>
            <p>Help Jerry!</p>
            Solution:<br/><input id="pw" type="password" />
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