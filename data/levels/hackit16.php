<?php


class Hackit16 implements Hackit
{
	public function getName() { return 'Understand the algorithm III';}
	public function getDescription(){return 'The password of this level is calculated by the following function';}
	public function getTags(){return 'PHP';}

	public function isSolved()
	{
        if($_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
            return true;
        else
            return false;
	}

	public function prepare()
	{
		//$a = new Algorithms;
		$_SESSION['levels'][basename(__FILE__, '.php')] = strtolower(substr(md5(substr(session_id(), 2,10)),-25));
	}

	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;
        if($_REQUEST['text'])
            $ao = $html->warning('Algorithm output: <strong style="color:black;">'.$this->algo($_REQUEST['text']).'</strong>').'<br/><br/>';

		return '            <p>'.$this->getDescription().'</p>
            <pre><code class="language-php">function pwCheck($password)
{
    if(base64_encode($password)=="'.base64_encode($_SESSION['levels'][basename(__FILE__, '.php')]).'")
        return true;
    else return false;
}</code></pre>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">

                function checkPW()
                {
                    var el = document.getElementById("pw");
                    document.location.href="?pw="+el.value;
                }
                
            </script>
';
	}

    function algo($word)
    {
        if(!$word) return false;
        for($i=0;$i<strlen($word);$i++)
        {
            $o.=chr(ord(substr($word,$i,1))+$i);
        }
        return $o;
    }
}