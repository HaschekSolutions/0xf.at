<?php

class Hackit30 implements Hackit {

    /* Stuff */    
    private $level=''; //I don't actually know what goes here
    public $author = 'Joel I'; //don't actually have a twitter like the other guy who eats cookies
    public function __construct() { $this->level = basename(__FILE__, '.php'); }

    public function getName()        { return 'Sharp eyes';            }
    public function getDescription() { return 'Virtual eyes, that is'; }  //have never actually seen the text from here anywhere
    public function getTags()        { return 'Programming';           }  //Tesseract works great for this
    
    
  public function isSolved() {
        if ( 
            array_key_exists('starttime',  $_SESSION['levels'][$this->level]) && //safety first
            time() - $_SESSION['levels'][$this->level]['starttime'] < 30      && //verify we created the image <30 seconds ago
            array_key_exists('pw', $_REQUEST)                                 && //more saftey
            $_REQUEST['pw'] == $_SESSION['levels'][$this->level]['solution']     //actually verify password
        ) {
            return true;
        }
        
        return false;
    }
    
    public function prepare() {
        //make sure we have an array set to the session
        if (gettype($_SESSION['levels'][$this->level]) != "array") $_SESSION['levels'][$this->level] = [];
        
        //check if the timer has expired or never existed in the first place
        if (
            (!array_key_exists('starttime', $_SESSION['levels'][$this->level])) || 
            (time() - $_SESSION['levels'][$this->level]['starttime'] >= 30)
        ) {
        
            //delete the old image, if there is one
            if (
                array_key_exists('filename', $_SESSION['levels'][$this->level]) &&
                file_exists(ROOT.$_SESSION['levels'][$this->level]['filename'])
            ) unlink(ROOT.$_SESSION['levels'][$this->level]['filename']);
            
            //create a new one and set up the new session values
            $image = $this->makeImage();
            
            $_SESSION['levels'][$this->level]['filename']  = str_replace(DS, "/", $image['filename']); //replace separator with a regular forward slash
            $_SESSION['levels'][$this->level]['solution']  = $image['solution'];
            $_SESSION['levels'][$this->level]['starttime'] = time();
        }
    }
    
    public function render() {
        return '
            <p>Below is an image with green tiles of varying color, each with a character label.</p>
            <p>The password to this level is a string of characters which orders their tiles from <span class="green">lightest to darkest.</span></p>
            <p>You have <span class="blue">30 seconds</span> to solve this level.</p>
            <img src="'.$_SESSION['levels'][$this->level]['filename'].'?nocache='.time().'" />
            <br />
            <input id="pw" type="password" />
            <input type="button" value="OK" onclick="checkPW()" />
            <script type="text/javascript">
                function checkPW() {
                    var el = document.getElementById("pw");
                    document.location.href = "?pw=" + el.value;
                }
                
                setTimeout(checkPW, 30000);
            </script>
        ';
    }
        
    public function makeImage() {
        $cellsize = 40;
        
        //size by five cells
        $img = imagecreate( $cellsize * 6, $cellsize * 5 );
        
        $shades = [];
        $cells  = [];
        
        for ($x = 0; $x < 30; $x++) {
            array_push($shades, imagecolorallocate($img, 0, 255 - $x, 0));
            array_push($cells,  $x);
        }
        
        //limited alphabet to be a bit more OCR friendly
        $characters = explode(" ", "B C D E F G H I J K L N P R T U V X 1 2 3 4 5 6 7 8 9 : > <");
        
        $white = imagecolorallocate( $img, 255, 255, 255 );

        //cells consist of the cellsize to make a square and are tiled over the image 6 wide and 5 tall
        //the image size (line 6) is changed to reflect the cell size
        //cell size should not be an issue, because regardless we are only indexing 257 colors to the image
        //however, the fonts used by php are apparently very limited in size and will not scale any larger than this
        function drawCell($img, $cellsize, $white, $x, $color, $character) {
            
            $y = 0;
            
            //we want to convert a number into a coord, so we basically need to do y modulo 6 and set x to floor(y/6)
            while ($x > 5) {
                //wrap around after 5
                $x -= 6;
                $y++;
            }
            
            $x *= $cellsize;
            $y *= $cellsize;
            
            //ctx.fillRect, right?
            imagefilledrectangle($img, $x, $y, $x + $cellsize - 1 , $y + $cellsize - 1, $color);
            //... except not. apparently it draws things 1 pixel bigger than needed.
            //and because of the way we're jumping around with the cell number, we 
            //get overlapping corners that can give away order if you look close enough.
            //luckily -1 is easy.
            
            //+16 and +12 for padding on the letters
            imagestring($img, 5, $x + 16, $y + 12, $character, $white);
        }
        
        
        /* 
          in the main creation loop, there are three factors: color, character, and cell number
          at least two of these must be random, the third can be sequential
          ordinarily, you would make the cell number sequential and the color and letter random,
          and set the cells in order, left to right and top to bottom. but by making the color 
          be sequential, we can push the character (random) onto a solution string for later use.
          remember, the problem is asking for the letters sorted by color
        */
        $solution = "";
        
        for ($shade = 0; count($cells) != 0; $shade++) {
            //although the cell number is a random integer 0-29, 
            //we still need an array to keep track of which ones we've picked already
            
            //pick an offset, use it, and then splice it out
            $cellnumber    = mt_rand(1, count($cells)     ) - 1; //fenceposting is bad
            $characterpick = mt_rand(1, count($characters)) - 1;
            
            drawCell($img, $cellsize, $white, $cells[$cellnumber], $shades[$shade], $characters[$characterpick]);
            $solution .= $characters[$characterpick];
            
            //splice over unset to fixup indices automatically
            array_splice($cells,      $cellnumber,    1);
            array_splice($characters, $characterpick, 1);
        }
    

        $file = DS.'data'.DS.'tmp'.DS.uniqid().".png";
        imagepng($img, ROOT.$file);
        
        return ["filename" => $file, "solution" => $solution];
    }
    
}