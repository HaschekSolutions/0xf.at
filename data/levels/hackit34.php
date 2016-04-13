<?php
class Hackit34  implements Hackit {
    private $level = '';
    public $author = 'Joel I'; //You can enter your name here if you want
    public function __construct(){$this->level = basename(__FILE__, '.php');} //so you can use $this->level to get the level id

    public function getName()        { return 'Sequence solving';                     }
    public function getDescription() { return 'I heard the answer\'s on the ceiling'; } //Get it? you have to lookup
    public function getTags()        { return 'Programming,Mathematics';              } 
    
    
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
            $_REQUEST['pw'] == $this->getLevelPassword() 
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
            $this->setTimeLimit(60);
            $this->randomize();
        }                
    }
    
    private function func($x, $karr) {
        $k1 = $_SESSION['levels'][$this->level]['Kvals'][$karr]['K1'];
        $k2 = $_SESSION['levels'][$this->level]['Kvals'][$karr]['K2'];                
        
        return bcpowmod($x+2, $k1, ($k1-2)*($k2-2));
    }
    
    private function randomize() {
        $_SESSION['levels'][$this->level]['Kvals'] = [
            'A' => [
                'K1' => mt_rand(1000, 1200),
                'K2' => mt_rand(1000, 1200)
            ],
            'B' => [
                'K1' => mt_rand(1000, 1200),
                'K2' => mt_rand(1000, 1200)
            ],
            'C' => [
                'K1' => mt_rand(1000, 1200),
                'K2' => mt_rand(1000, 1200)
            ],
            'D' => [
                'K1' => mt_rand(1000, 1200),
                'K2' => mt_rand(1000, 1200)
            ]
        ];
        
        $this->setLevelPassword(
            $this->func(4, 'A') +
            $this->func(4, 'B') +
            $this->func(4, 'C') +
            $this->func(4, 'D')
        );
        
        $_SESSION['levels'][$this->level]['gvalues'] = implode("\n", [
            '<tr><td id="A"></td><td>'. $this->func(1, 'A') .','. $this->func(2, 'A') .','. $this->func(3, 'A') . "</td></tr>",
            '<tr><td id="B"></td><td>'. $this->func(1, 'B') .','. $this->func(2, 'B') .','. $this->func(3, 'B') . "</td></tr>",
            '<tr><td id="C"></td><td>'. $this->func(1, 'C') .','. $this->func(2, 'C') .','. $this->func(3, 'C') . "</td></tr>",
            '<tr><td id="D"></td><td>'. $this->func(1, 'D') .','. $this->func(2, 'D') .','. $this->func(3, 'D') . "</td></tr>"
        ]);
    }
    

    public function render() {
        return '
        <style>
            table#numbers {
                margin: auto;
            }
            
            table#numbers td {
                text-align: left;
                font-family: monospace;
                background: #2E3338;
                padding: 4px;
                border: 1px solid gray;
            }
            
            #A,#B,#C,#D {
                color: white
            }
            
            /* :befores to be copy-paste friendly */
            #A:before { content: "A" }
            #B:before { content: "B" }
            #C:before { content: "C" }
            #D:before { content: "D" }
            
            table#numbers td + td:after {
                content: ", ____?";
            }

            pre#function {
                width: auto;
                display: inline-block;
                text-align: left;
            }
        </style>
        
        <p>The numbers below are determined by the following function:</p>
        <pre id="function">function Y(int x, int k1, int k2):
    return pow(x + 2, k1) mod (k1-2)*(k2-2)</pre>

        <p>Sequences A, B, C, and D each use different values for K<sub>1</sub> and K<sub>2</sub>, all random integers between <span class="blue">1000 and 1200</span>. The first three values are given. (Y(1), Y(2), Y(3))</p>
        <p>The solution to this level is the <span class="green">sum</span> of the next numbers in the seqeunce for A, B, C, and D.</p>
        <table id="numbers">'.
        $_SESSION['levels'][$this->level]['gvalues']
        .'</table>
        <p>You have <span class="green">60 seconds</span> to solve this level</p>

        <input type="text" id="pw" />
        <input type="button" onclick="checkPW()" value="GO" />
        <script>
        function checkPW() {
            el = document.getElementById("pw");
            document.location.href = "?pw=" + el.value;
        }
        //<!-- if the level time limit is active, print out some JS to refresh the page -->
        ' . ( $this->getTimeLimit()->isActive ? "setTimeout(checkPW, " . $this->getTimeLimit()->maxTime * 1000 . ");" : "" ). '    
        </script>';
    }
        
}