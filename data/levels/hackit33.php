<?php
class Hackit33 implements Hackit {
    private $level = '';
    public $author = 'Joel I'; //You can enter your name here if you want
    public function __construct(){$this->level = basename(__FILE__, '.php');} //so you can use $this->level to get the level id

    public function getName()        { return 'Bcrypt cracking';                   } //The name will be displayed in the level table
    public function getDescription() { return 'vulgar stuff on that list, really'; } //This will be displayed when the level is started
    public function getTags()        { return 'Cracking';                          } //Describe what technology you used. Comma,seperated
    
	
    public function isSolved() {
		if (!array_key_exists('pw', $_REQUEST)) return $this->randomize();
		if (!array_key_exists('un', $_REQUEST)) return $this->randomize();
		
		if (
            (!array_key_exists( $this->level, $_SESSION['levels'] ))  ||
            gettype($_SESSION['levels'][$this->level]) != "array" 
        ) return $this->randomize();
		
		$result = password_verify($_REQUEST['pw'], $_SESSION['levels'][$this->level][strtolower($_REQUEST['un'])]);
		
		if ($result === false) $this->randomize();
		
		return $result;
    }
    

    public function prepare() {
        if (!is_array($_SESSION['levels'][$this->level]))
         $_SESSION['levels'][$this->level] = [
			"rsmith"      => '',
			"apalmer"     => '',
			"gshermer"    => '',
			"lmichaud"    => '',
			"ijerry"      => '',
			"chaschek"    => '',
			"kgardner"    => '',
			"abush"       => '',
			"pyoumans"    => '',
			"mmurphy"     => '',
			"ccarrington" => ''
		]; 
    }
	
	private function randomize() {
		$users = array_keys($_SESSION['levels'][$this->level]);
		//one user at random uses a weak password
		$weakuser = mt_rand(0, count($users) - 1);
		
		//strong passwords consist of two words from the dictionary with three numbers at the end
		$strongwords = file(ROOT.DS."data".DS."dictionary.txt");
		
		//the weak password consists of one word from the common password dictionary with a number 0-99 at the end
		$weakwords = file(ROOT.DS."data".DS."commonpasswords.txt");
		
		//first we give everyone a strong password
		for ($x = 0; $x < count($users); $x++) {
			$word1 = trim($strongwords[mt_rand(0, count($strongwords) - 1)]);
			$word2 = trim($strongwords[mt_rand(0, count($strongwords) - 1)]);
			
			$number = "".mt_rand(100, 999);
			
			$_SESSION['levels'][$this->level][$users[$x]] = password_hash($word1.$word2.$number, PASSWORD_BCRYPT );
		}
		
		//then go back and give the one moron a bad one
		$word   = trim($weakwords[mt_rand(0, count($weakwords) - 1)]);
		$_SESSION['levels'][$this->level][$users[$weakuser]] = password_hash($word, PASSWORD_BCRYPT );
		
		return false; //util, we only call this if isSolved() returns false, so rather than doing brackets we return the function call
	}

	
	
    public function render() {
		$usertable = '<table id="users"><tr><th>user_id</th><th>username</th><th>hash</th></tr>';
		
		for ($x = 0; $x < count($_SESSION['levels'][$this->level]); $x++) {
			$userForRow = array_keys($_SESSION['levels'][$this->level])[$x];
			$hashForRow = $_SESSION['levels'][$this->level][$userForRow];
			
			$userForRow = strtoupper(substr( $userForRow, 0, 2 ) ).substr($userForRow, 2); //formatting, capitalize first two letters			
			
			
			$usertable .= "<tr>";
			
			$usertable .= "<td>$x</td>";
			$usertable .= "<td>$userForRow</td>";
			$usertable .= "<td>$hashForRow</td>";
			
			$usertable .= "</tr>";					
		}
		
		$usertable .= "</table>";
		
        return '
		<style>
			table#users {
				border-collapse: collapse;
				text-align: left;
				font-family: monospace;
				margin: auto;
				margin-bottom: 10px;
			}
			
			table#users td, table#users th {
				padding: 2px;
				border: 1px solid gray;
			}
		</style>
        <p>Below is a portion of a users table in an SQL database.</p>
		<p>You suspect at least one user was stupid enough to use an <a href="/data/commonpasswords.zip" class="green">&gt;&gt;extremely common password&lt;&lt;</a>(29.4 KB)</p>
		'.$usertable.'
        <input placeholder="username" type="text"     id="un" />
        <input placeholder="password" type="password" id="pw" />
        <input type="button" onclick="checkPW()" value="GO" />
        <script>
        function checkPW() {
			el = document.getElementById("pw");
			un = document.getElementById("un");
            document.location.href = "?pw=" + el.value + "&un=" + un.value;
        }
        </script>';
    }
        
}
?>