<?php

/**
 *	Every hackit has its own file like this.
 *	This example is from the actual first level.
 *
 *	Technologies you can use:
 *	   * Bootstrap 		- http://getbootstrap.com/components/
 *	   * Font Awesome 	- https://fortawesome.github.io/Font-Awesome/icons/
 *	   * PrismJS 		- http://prismjs.com/#basic-usage
 *	   * jQuery 		- https://jquery.com/
 *
 *	The methods are called by the framework in the following order:
 *
 *	===============================
 *	$hackit->prepare();
 *	if($hackit->isSolved()===false)
 *		$hackit->render();
 *	===============================
 *
 */
class HackitX implements Hackit
{
	private $level='';
	public function __construct(){$this->level = basename(__FILE__, '.php');} //so you can use $this->level to get the level id

	public function getName() { return 'Easy beginnings';}	//The name will be displayed in the level table
	public function getDescription(){return 'Use the source!';} //This will be displayed when the level is started
	public function getTags(){return 'JavaScript';} //Describe what technology you used. Comma,seperated

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
		if($_REQUEST['pw']==$_SESSION['levels'][$this->level])
			return true;
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
		$_SESSION['levels'][basename(__FILE__, '.php')] = 'tooeasy';
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
			<div>Easy beginnings</div>
            <input id="pw" type="password" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">
                function checkPW()
                {
                    var el = document.getElementById(\'pw\');
                    if(el.value=="'.$_SESSION['levels'][$this->level].'")
                        document.location.href="?pw="+el.value;
                    else alert("Wrong password!");
                }
            </script>';
	}
}