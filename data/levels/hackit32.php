<?php
class Hackit32 implements Hackit {

    private $level = '';
    public $author = 'Joel I';
    public function __construct(){$this->level = basename(__FILE__, '.php');} //so you can use $this->level to get the level id

    public function getName()        { return 'Spellcheck cipher';  } //The name will be displayed in the level table
    public function getDescription() { return 'Something something Zodiac Letters'; } //This will be displayed when the level is started
    public function getTags()        { return 'Programming';        } //Describe what technology you used. Comma,seperated
    
    
    private function setTimeLimit($time) { 
        $_SESSION['levels'][$this->level]['starttime'] = time();
        $_SESSION['levels'][$this->level]['maxtime']   = $time;
        $_SESSION['levels'][$this->level]['timerset']  = true;
    }
    
    private function getTimeLimit() { 
        $isExpired = false;
        $isActive  = false;
        $maxTime   = 0;
        
        if (
            array_key_exists('timerset', $_SESSION['levels'][$this->level]) &&
            $_SESSION['levels'][$this->level]['timerset'] === true
        ) {
            $isActive = true;
            $maxTime  = $_SESSION['levels'][$this->level]['maxtime'];
            
            if (time() - $_SESSION['levels'][$this->level]['starttime'] >= $maxTime)
                $isExpired = true;
            
        }
        
        return (object)[
            "isActive"  => $isActive,
            "isExpired" => $isExpired,
            "maxTime"   => $maxTime
        ];
    }
    

    
    private function setLevelPassword($password) {
        $_SESSION['levels'][$this->level]['password'] = $password;
    }
    
    private function getLevelPassword() {
        if (! array_key_exists('password', $_SESSION['levels'][$this->level]) ) return false;
        
        return $_SESSION['levels'][$this->level]['password'];
    }

    public function isSolved() {
        
        $timer = $this->getTimeLimit();
        if (
            (
             (
                $timer->isActive  === true  &&
                $timer->isExpired === false
             ) || (
                $timer->isActive  === false
             )
            ) && 
            array_key_exists('pw', $_REQUEST) &&
            trim($_REQUEST['pw']) == trim($this->getLevelPassword()) 
        ) return true;
        
        return false;
    }
    

    public function prepare() {
    
        if (!is_array($_SESSION['levels'][$this->level]))
        	$_SESSION['levels'][$this->level] = [];
            
            
        
        $timer = $this->getTimeLimit();
        if (
            $timer->isActive  === false ||
            $timer->isExpired === true
        ) {
            $this->setTimeLimit(10);
            
			$success = false;
			
			while (!$success) $success = $this->makeParagraph();
            
        }

    }
	
	private function makeParagraph() {
		$iHatePHP = [
			'a' => 'b',
			'b' => 'c',
			'c' => 'd',
			'd' => 'e',
			'e' => 'f',
			'f' => 'g',
			'g' => 'h',
			'h' => 'i',
			'i' => 'j',
			'j' => 'k',
			'k' => 'l',
			'l' => 'm',
			'm' => 'n',
			'n' => 'o',
			'o' => 'p',
			'p' => 'q',
			'q' => 'r',
			'r' => 's',
			's' => 't',
			't' => 'u',
			'u' => 'v',
			'v' => 'w',
			'w' => 'x',
			'x' => 'y',
			'y' => 'z',
			'z' => 'a'
		];
		
		//open the latin dictionary
		$file  = file(ROOT.DS."data".DS."latindictionary.txt");
		$words = [];
		
		//set a randomized word length for the paragraph
		$length = mt_rand(150, 230);
		
		//populate an array of words with random lines from the dictionary
		while ($length > count($words)) {
			$word = $file[ mt_rand(0, count($file) - 1) ];
			$word = str_replace("\n", "", $word);
			$word = str_replace("\r", "", $word);
			$word = str_replace(" ",  "", $word);
			
			if ($word != "") array_push($words, $word);
		}
		
		//set a random word from the regular dictionary as the answer
		$wordlist = file(ROOT.DS."data".DS."dictionary.txt");
		$answer = $wordlist[mt_rand(0, count($wordlist) -1)];
		$answer = trim($answer);
		$this->setLevelPassword($answer);
		
		//calculate how many words each letter can be placed into
		//this makes sure the letters stay in their correct order without getting too complex
		$wordsPerLetter = floor($length / strlen($answer));
		$wordsPerLast   = $length - ($wordsPerLetter * strlen($answer));
		
		//the pointer is an offset counter for our paragraph's array
		$pointer = 0;
		
		//for each letter in our answer, populate an array of viable words that can hold it
		for ($x = 0; $x < strlen($answer); $x++) {

			$wordsToCheck = $wordsPerLetter;
			if ($x + 1 == strlen($answer)) $wordsToCheck = $wordsPerLast;
			$searchLetter  = $answer[$x];
			$replaceLetter = $iHatePHP[$searchLetter];
			
			$viableWords = [];
			
			for ($y = $pointer; $y < $pointer + $wordsToCheck; $y++) {
				$pos = strpos($words[$y], $searchLetter);
				if ($pos !== false) array_push($viableWords, $y);
			}
			
			//it's actually easier to just try again if we can't find a match
			//I've never had this trigger more than 5 times for a single generation
			if (count($viableWords) == 0) {
				echo "<!--R-->";
				return false;
			}

			//and then pick a random viable word and replace it
			$pick = $viableWords[mt_rand(0, count($viableWords) - 1)];
			$words[$pick] = preg_replace("/".$searchLetter."/", $replaceLetter, $words[$pick], 1);
			$pointer += $wordsToCheck;
		}
		
		
		$_SESSION['levels'][$this->level]['paragraph'] = $this->formatParagraph( $words );
		return true;
	}
	
	private function formatParagraph($text) {
		$pointer = 0;
		
		array_push($text, "Filler!");
		
		while ($pointer < count($text) - 1) {
			$sentencelength = mt_rand(3, 12);
			
			$text[$pointer] = ucwords($text[$pointer]);
			
			$pointer = min(count($text) - 1, $pointer + $sentencelength + 1);
			
			$text[$pointer - 1] = $text[$pointer - 1] . ".";
		}
		
		array_pop($text); //remove filler
		
		return join(" ", $text);
	}

	
    public function render() {
        return '
        <p>Below is a nonsense paragraph of latin words from <a href="/data/latindictionary.zip"><span class="green">&gt;&gt;this dictionary&lt;&lt;</span></a>(8.6 KB)</p>
		<p>A few letters from random words in the paragraph are spelled with one letter shifted one place forward. Eg A -> B, B -> C, Z -> A</p>
		<p>The password to this level is a string created by the letters which would fix the typos</p>
        <p>You have <span class="green">10 seconds</span> to solve this level</p>
		
        <blockquote style="font-size:100%; text-align: left; background: #191C1F; text-indent: 1em;">
		'.$_SESSION['levels'][$this->level]['paragraph'].'
		</blockquote>
		
        <input type="text" id="pw" />
        <input type="button" onclick="checkPW()" value="GO" />
        <script>
        function checkPW() {
            el = document.getElementById("pw");
            document.location.href = "?pw=" + el.value;
        }
        ' . ( $this->getTimeLimit()->isActive ? "setTimeout(checkPW, " . $this->getTimeLimit()->maxTime * 1000 . ");" : "" ). '    
        </script>';
    }
        
}