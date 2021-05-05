<?php


class Hackit17 implements Hackit
{
	public function getName() { return 'regEx anyone?';}
	public function getDescription(){return 'Now let\'s play: regEx<br/>Find out what password will make the preg_match function return 1';}
	public function getTags(){return 'PHP';}

	public function isSolved()
	{
        if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $_REQUEST['pw'], $found))
            return true;
        else
            return false;
	}

	public function prepare()
	{
		//$a = new Algorithms;
		//$_SESSION['levels'][basename(__FILE__, '.php')] = strtolower(substr(md5(substr(session_id(), 2,10)),-25));
	}

	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;

		return '            <p>'.$this->getDescription().'</p>
<pre><code class="language-php">function pwCheck($password)
{
    return preg_match(\'/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i\', $password, $found);
}</code></pre>
        <input id="pw" type="password" />
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