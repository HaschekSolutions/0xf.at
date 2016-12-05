<?php

class Hackit36  implements Hackit {
    private $level = ''; // ヘ(◕。◕ヘ)
    public $author = '43r04';
    private $timelimit = 10;
    private $checkPassword = false;
    private $passwordLength = 14;

    /**
     * Hackit36 constructor.
     */
    public function __construct()
    {
        $this->level = basename(__FILE__, '.php');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Scrambled captcha';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "
            <p>
                This captcha generation was scrambled slightly to much.
            </p>
            <p>
                Each line in this captcha originally started with a grey pixel, e.g.: '|0123456789' was scrambled to '789|0123456'.<br/>
                The pipe ('|') represents the grey dot, the numbers (0-9) represent the remaining pixels in each line.
            </p>
            <p>
                Unscramble the word and you will get the result.
            </p>";
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return 'Programming,Logic';
    }

    /**
     *
     * The prepare method is called before
     * rendering the level. You can define
     * the password for this level, start
     * timers.. everything you want!
     *
     * @return      NULL
     *
     */
    public function prepare()
    {
        if(isset($_SESSION['levels'][$this->level]['password'])) {
            $this->checkPassword = $_SESSION['levels'][$this->level]['password'];
        }
        // if(empty($_SESSION['levels'][$this->level]) || (time() - $_SESSION['levels'][$this->level]['starttime']) > $this->timelimit) {
            $_SESSION['levels'][$this->level] = Array();
            $_SESSION['levels'][$this->level]['password'] = $this->random_str($this->passwordLength);
            $_SESSION['levels'][$this->level]['starttime'] = time();
        // }
    }

    /**
     *
     * The render method is called last and
     * returns the HTML code that will be
     * displayed on the level. You can use
     * variables defined in the prepare
     * method since render is called last
     *
     * @return      NULL
     *
     */
    public function render()
    {
        return '            
			<div>
			    '.$this->getName().'
			</div>
			<div>
			    '.$this->getDescription().'
			</div>
			<div>
			    <p>
			        You have '.$this->timelimit.' seconds.
                </p>
            </div>
            <div>
                <p>
                    <img src="data:image/png;base64,'.$this->getCaptcha().'" alt="that escalated quickly..." id="img36" />
                </p>
            </div>
			
            <input id="pw" type="password" /><br/>
            <input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW() {
                    el = document.getElementById("pw");
                    document.location.href = "?pw=" + encodeURI(el.value);
                }
                setTimeout(checkPW,'. ($this->timelimit * 1000) .');
            </script>';
    }

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
        if(isset($_REQUEST['pw']) && !is_null($_REQUEST['pw']) && $this->checkPassword !== false && $_REQUEST['pw'] == $this->checkPassword && (time() - $_SESSION['levels'][$this->level]['starttime']) <= $this->timelimit) {
            return true;
        }
        else
            return false;
    }

    /**
     * @return string
     */
    private function getCaptcha()
    {
        $imageWidth = 400;
        $imageHeight = 60;
        $fontHeight = 24;
        $rumbleMin = 1;
        $rumbleMax = $imageWidth - 10;
        $greyScale = 40;


        $fontBaseLine = ($imageHeight / 2) + ($fontHeight / 2);

        $im = imagecreatetruecolor($imageWidth, $imageHeight);
        $red = ImageColorAllocate ($im, 120, 0, 0);
        $white = ImageColorAllocate ($im, $greyScale, $greyScale, $greyScale);

        putenv('GDFONTPATH=' . dirname(dirname(__DIR__))."/fonts/");

        $font_file = 'Verdana';
        imagefttext($im, $fontHeight, 0, 20, $fontBaseLine, $red, $font_file, $_SESSION['levels'][$this->level]['password']);
        imageline($im, 0, 0, 0, $imageHeight, $white);

        // Image is set, now read all items in array
        $image = [];
        for($y = 0; $y < $imageHeight; $y++) {
            for($x = 0; $x < $imageWidth; $x++) {
                if(!array_key_exists($y, $image)) {
                    $image[$y] = [];
                }
                $image[$y][$x] = imagecolorat($im, $x, $y);
            }
        }

        // Now let´s scrumble each row a little bit....
        /*
         * |0123456789
         * |0123456789
         * |0123456789
         * |0123456789
         *
         * becomes:
         *
         * 6789|012345
         * 23456789|01
         * 789|0123456
         * 56789|01234
         */
        for($y = 0; $y < $imageHeight; $y++) {
            $currentRow = $image[$y];
            $rumbleIndex = rand($rumbleMin, $rumbleMax);
            $newRow = [];
            for($x = $rumbleIndex; $x < count($currentRow); $x++) {
                array_push($newRow, $currentRow[$x]);
            }
            for($x = 0; $x < $rumbleIndex; $x++) {
                array_push($newRow, $currentRow[$x]);
            }
            $image[$y] = $newRow;
        }

        // And now write the image again
        foreach($image as $y => $row) {
            foreach ($row as $x => $color) {
                imagesetpixel($im, $x, $y, $color);
            }
        }

        // Get image as base64-value
        ob_start();
            imagepng($im);
            $image_data = ob_get_contents ();
        ob_end_clean();
        $image_data_base64 = base64_encode($image_data);

        return $image_data_base64;
    }

    /**
     * @param $length
     *
     * @return string
     */
    private function random_str($length)
    {
        $characters = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSTUVWXYZ$';
        $result = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $result .= $characters[rand(0, $max)];
        }
        return $result;
    }
}