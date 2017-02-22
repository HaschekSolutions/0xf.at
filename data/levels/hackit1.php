<?php

class Hackit1 implements Hackit
{
	public function getName() { return 'Easy beginnings';}
	public function getDescription(){return 'Use the source!';}
	public function getTags(){return 'JavaScript';}

	public function isSolved()
	{
		if($_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
			return true;
		else
			return false;
	}

	public function prepare()
	{
		$_SESSION['levels'][basename(__FILE__, '.php')] = 'tooeasy';
	}

	public function render()
	{
		return '<h3>Easy beginnings</h3>
			<form id="level">
				<input type="password" name="pw" />
				<br />
				<input type="submit" value="OK" />
			</form>
			<script type="text/javascript">
				$(\'#level\').submit(function(event) {
					var password = $(event.target).find(\'[name=pw]\').val();
                    if(password !== "'.$_SESSION['levels'][basename(__FILE__, '.php')].'") {
                    	event.preventDefault();
                    	alert(\'Wrong Password!\');
                    }
				});
			</script>';
	}
}