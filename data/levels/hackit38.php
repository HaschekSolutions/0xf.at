<?php


class Hackit38 implements Hackit
{
    private $level = '';
	private $zeroes = 7;
	public function __construct(){$this->level = basename(__FILE__, '.php');} //so you can use $this->level to get the level id

	public function getName() { return 'Proof of Work';}	//The name will be displayed in the level table
	public function getDescription(){return nl2br('You don\'t need a Bitcoin miner to proof that you can do some work.
												  Seal the block by finding a <strong><a href="https://en.wikipedia.org/wiki/Cryptographic_nonce">nonce</a></strong> that (combined with the data) will return a sha1 hash with '.$this->zeroes.' leading zeroes');} //This will be displayed when the level is started
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
		if( substr(strval(sha1($_SESSION['string1'][basename(__FILE__, '.php')].$_SESSION['string2'][basename(__FILE__, '.php')].$_REQUEST['pw'])),0,$this->zeroes)==='0000000' )
			return true;
		else
			return false;
	}


	public function prepare()
	{
		if(!$_REQUEST['pw'] || ($_REQUEST['pw'] && !$this->isSolved()))
        {
            $word = md5(rand(1,2000).time());
            $_SESSION['string1'][basename(__FILE__, '.php')] = bin2hex(random_bytes(5))." gives ".rand(1,200)." 0xfcoins to";
            $_SESSION['string2'][basename(__FILE__, '.php')] = bin2hex(random_bytes(5));
            $_SESSION['starttime'][basename(__FILE__, '.php')]=time();
        }
	}

	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
		<pre><code class="language-markup">
sha1 = ( "'.$_SESSION['string1'][basename(__FILE__, '.php')].'" + "'.$_SESSION['string2'][basename(__FILE__, '.php')].'" + nonce)
</code></pre><br/>

<strong>Example:</strong><br/>
nonce = "dnt28wclnx"<br/>
sha1("string1"+"string2"+nonce) -> Results in sha1 hash 000000140e9731bbf369d8cbc4f7919961020793 so dnt28wclnx would be a solution if we were looking for 6 leading zeroes
<br/><br/>
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
