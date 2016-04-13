<?php


class Hackit13 implements Hackit
{
	public function getName() { return 'Understand the algorithm II';}
	public function getDescription(){return 'The password of this level is calculated by the following function';}
	public function getTags(){return 'PHP';}

	public function isSolved()
	{
		$a = new Algorithms;
		if(!$_REQUEST['name'] || !$_REQUEST['pw']) return false;

		if($_REQUEST['pw']==strlen($_REQUEST['name']))
			return true;
		else
			return false;
	}

	public function prepare()
	{
		$a = new Algorithms;
		//$_SESSION['levels'][basename(__FILE__, '.php')] = $a->sumBetween(1,(404+$a->sumOfNumbersInString(session_id())));
	}

	public function render()
	{
		$a = new Algorithms;
		return '            <p>'.$this->getDescription().'</p>
            <pre><code class="language-php">function pwCheck($username,$password)
{
    if(!$username || !$password) return false;
    if(strlen($username)==$password)
        return true;
    else return false;
}</code></pre><br/>
            Username
            <input id="user" name="user" type="text" />
            <br/>
            Password
            <input id ="pw" name="pw" type="password" /><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">

                function checkPW()
                {
                    var pw = document.getElementById("pw");
                    var user = document.getElementById("user");
                    document.location.href="?pw="+pw.value+"&name="+user.value;
                }
                
            </script>';
	}
}