<?php

/**
    Shape class
*/
class Shape {
    public function __construct() {
        $this->center = Array(NULL,NULL);
        $this->size = mt_rand(7,20);
        $this->color = Array(mt_rand(20,200),mt_rand(20,200),mt_rand(20,200));
    }
    /**
        Returns true if both shapes intersect
        @return bool
    */
    public function intersect($shape) {
        return false;
    }

    /**
        Returns true if any shape intersect with this shape
        @return bool
    */
    public function intersectsAny($shapes) {
        foreach($shapes as $shape) {
            if($this->intersect($shape))
                return true;
        }
        return false;
    }
}

/**
    Circle class
*/
class Circle extends Shape {
    public function intersect($shape) {
        //If shape is circle
        if(get_class($shape) == "Circle") {
            $dx = $this->center[0] - $shape->center[0];
            $dy = $this->center[1] - $shape->center[1];
            return (sqrt($dx*$dx + $dy*$dy) <= ($this->size + $shape->size + 2));
        //If shape is square
        }else{
            $dx = abs($this->center[0] - $shape->center[0]);
            $dy = abs($this->center[1] - $shape->center[1]);

            if($dx > ($shape->size/2 + $this->size)) { return false; }
            if($dy > ($shape->size/2 + $this->size)) { return false; }

            if($dx <= ($shape->size/2) || $dy <= ($shape->size/2)) { return true; }

            $cd = pow(($dx - $shape->size/2),2) + pow(($dy - $shape->size/2),2);
            return ($cd <= pow($this->size,2));
        }
    }

    public function draw($im) {
        $color = imagecolorallocate($im,$this->color[0],$this->color[1],$this->color[2]);
        imagefilledellipse($im,$this->center[0],$this->center[1],$this->size,$this->size,$color);
    }
}

/**
    Square class
*/
class Square extends Shape {

    public function intersect($shape) {
        if(get_class($shape) == "Circle") {
            return $shape->intersect($this);
        }else{
            $sleft = $shape->center[0] - $shape->size/2 - 1;
            $stop = $shape->center[1] - $shape->size/2 - 1;
            $sright = $shape->center[0] + $shape->size/2 + 1;
            $sbottom = $shape->center[1] + $shape->size/2 + 1;

            $tleft = $this->center[0] - $this->size/2 - 1 ;
            $ttop = $this->center[1] - $this->size/2 - 1;
            $tright = $this->center[0] + $this->size/2 + 1;
            $tbottom = $this->center[1] + $this->size/2 + 1;

            return !($sleft > $tright || $sright < $tleft || $stop > $tbottom || $sbottom < $ttop);
        }
    }

    public function draw($im) {
        $left = $this->center[0] - $this->size/2;
        $top = $this->center[1] - $this->size/2;
        $right = $this->center[0] + $this->size/2;
        $bottom = $this->center[1] + $this->size/2;

        $color = imagecolorallocate($im,$this->color[0],$this->color[1],$this->color[2]);
        imagefilledrectangle($im,$left,$top,$right,$bottom,$color);
    }
}

/**
    Image class
    used to generate image filled with circles and squares
*/
class Image {

    public function __construct($circles) {
        $this->circles = $circles;
    }

    /**
        Generates an image with circles and squares
        saves in data/tmp. Returns filename

        @return String
    */
    public function generateImage() {
        $im = imagecreate(200,200);
        $background = imagecolorallocate($im,255,255,255);

        $shapes = Array();

        //Generate circles
        for($i=0;$i<$this->circles;$i++) {
            $c = new Circle();
            //Make sure circle does not intersect any shapes
            while($c->center[0] == NULL || $c->intersectsAny($shapes)) {
                $c = new Circle();
                $c->center = Array(mt_rand($c->size+2,imagesx($im)-$c->size-2),mt_rand($c->size+2,imagesy($im)-$c->size-2));
            }
            $c->draw($im);
            $shapes[] = $c;
        }

        //Generate squares
        $squares = mt_rand(5,20);
        $max_iterations = 100; //If intersects other shapes only try to repositioning this many times before giving up
        for($i=0;$i<$squares;$i++) {
            $s = new Square();
            $iterations = 0;
            while($iterations < $max_iterations && ($s->center[0] == NULL || $s->intersectsAny($shapes))) {
                $s = new Square();
                $s->center = Array(mt_rand(2+$s->size/2,imagesx($im)-$s->size/2-2),mt_rand(2+$s->size/2,imagesy($im)-$s->size/2-2));
                $iterations++;
            }

            if($iterations < $max_iterations) {
                $s->draw($im);
                $shapes[] = $s;
            }else{
                break;
            }
        }


        //Save image
        $filename = md5(time().mt_rand(0,1000));
        $path = ROOT.DS.'data'.DS.'tmp'.DS.$filename;
        imagepng($im,$path);
        return $filename;

    }

}

/**
 *  Every hackit has its own file like this.
 *  This example is from the actual first level.
 *
 *  Technologies you can use:
 *     * Bootstrap      - http://getbootstrap.com/components/
 *     * Font Awesome   - https://fortawesome.github.io/Font-Awesome/icons/
 *     * PrismJS        - http://prismjs.com/#basic-usage
 *     * jQuery         - https://jquery.com/
 *
 *  The methods are called by the framework in the following order:
 *
 *  ===============================
 *  $hackit->prepare();
 *  if($hackit->isSolved()===false)
 *      $hackit->render();
 *  ===============================
 *
 */
class Hackit35
{
    private $timelimit = 30;    //Timelimit
    private $level='35';
    public $author = 'Oscar Arnflo'; //You can enter your name here if you want
    public function __construct(){$this->level = basename(__FILE__, '.php');} //so you can use $this->level to get the level id

    public function getName() { return 'Circle Mania';} //The name will be displayed in the level table
    public function getDescription(){return 'Count the circles. <br/>Shapes can never intersect and are never broken by image\'s edges';} //This will be displayed when the level is started
    public function getTags(){return 'Programming';} //Describe what technology you used. Comma,seperated

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
        if($_REQUEST['pw'] == $_SESSION['levels'][$this->level]['password'] && (time() - $_SESSION['levels'][$this->level]['starttime']) <= $this->timelimit)
        {
            unlink('data/tmp/'.$_SESSION['levels'][$this->level]['image']);
            return true;
        }
        else
            return false;
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
        if(empty($_SESSION['levels'][$this->level]) || (time() - $_SESSION['levels'][$this->level]['starttime']) > $this->timelimit) {
            $_SESSION['levels'][$this->level] = Array();
            //Number of circles
            $_SESSION['levels'][$this->level]['password'] = mt_rand(40,60);
            //Generate image
            $_SESSION['levels'][$this->level]['image']  = (new Image($_SESSION['levels'][$this->level]['password']))->generateImage();
            //Set start time
            $_SESSION['levels'][$this->level]['starttime'] = time();
        }
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
            <div>'.$this->getDescription().'<br/><img src="/data/tmp/'.$_SESSION['levels'][$this->level]['image'].'"><br/>You have '.$this->timelimit.' seconds.</div>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
        <script>
                function checkPW() {
                    el = document.getElementById("pw");
                    document.location.href = "?pw=" + el.value;
                }
        setTimeout(checkPW,'. ($this->timelimit*1000) .');
        </script>
            ';
    }
}
?>