<?php


class Hackit14 implements Hackit
{
	public function getName() { return 'Understand the algorithm IV';}
	public function getDescription(){return 'The following function defines the login process';}
	public function getTags(){return 'PHP';}

	public function isSolved()
	{
		$guid = $_REQUEST['guid'];
        $users = file_get_contents(ROOT.DS.'data/login_info.json');
        $json = json_decode($users,true);

        if(is_array($json['result']))
            foreach($json['result'] as $data)
                if($data['guid']==$guid && $data['password'] == $_REQUEST['pw'] && $data['account_status']=='active')
                    return true;
        return false;
	}

	public function prepare()
	{
		//$a = new Algorithms;
		//$_SESSION['levels'][basename(__FILE__, '.php')] = $a->sumBetween(1,(404+$a->sumOfNumbersInString(session_id())));
	}

	public function render()
	{
		$a = new Algorithms;
		return '            <p>'.$this->getDescription().'</p>
            
            <div></div>
            <pre><code class="language-php">function pwCheck($guid,$password)
{
	if(!$guid || !$password) return false;
    $users = implode(file(\'/data/login_info.json\'));
	$json = json_decode($users,true);

	foreach($json[\'result\'] as $data)
		if($data[\'guid\']==$guid &amp;&amp; $data[\'password\'] == $password &amp;&amp; $data[\'account_status\']==\'active\')
			return true;
	return false;
}
</code></pre>
            GUID<br/>
            <input id="guid" type="text" />
            <br/>
            Password<br/>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var el = document.getElementById("guid");
                    var pw = document.getElementById("pw");
                    window.location.href="?pw="+pw.value+"&guid="+el.value;
                }
            </script>';
	}
}