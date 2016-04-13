<?php


class Hackit11 implements Hackit
{
	public function getName() { return 'Understand the algorithm';}
	public function getDescription(){return 'The password of this level is calculated by the following function';}
	public function getTags(){return 'PHP';}

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
		$_SESSION['levels'][basename(__FILE__, '.php')] = date("d.m.Y");
	}

	public function render()
	{
		$a = new Algorithms;
		return '            <p>'.$this->getDescription().'</p>
            <pre><code class="language-php">function pwCheck($password)
{
    if($password==date("d.m.Y")) //GMT +1
        return true;
    else return false;
}</code></pre><br/>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">

                function checkPW()
                {
                    var el = document.getElementById("pw");
                    document.location.href="?pw="+el.value;
                }
                
            </script>';
	}
}