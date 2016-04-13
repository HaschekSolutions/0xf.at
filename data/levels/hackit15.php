<?php


class Hackit15 implements Hackit
{
	public function getName() { return 'The 0xf.at Enigma';}
	public function getDescription(){return 'You have to decode the following encrypted password.<br/>We don\'t know how to decrypt it but you can play around with the algorithm that was used to encode it. Maybe you\'ll figure it out';}
	public function getTags(){return 'Logic';}

	public function isSolved()
	{
		
        if($_REQUEST['pw']=='notbad')
            return true;
        else
            return false;
	}

	public function prepare()
	{
		//$a = new Algorithms;
		//$_SESSION['levels'][basename(__FILE__, '.php')] = $a->sumBetween(1,(404+$a->sumOfNumbersInString(session_id())));
	}

	public function render()
	{
        $html = new HTML;
		$a = new Algorithms;
        if($_REQUEST['text'])
            $ao = $html->warning('Algorithm output: <strong style="color:black;">'.$this->algo($_REQUEST['text']).'</strong>').'<br/><br/>';

		return '            <p>'.$this->getDescription().'</p>

<pre><code class="">npveei
</code></pre>

        <form method="GET">
            Testing input: 
            <input name="text" type="text" /> <input type="submit" name="submit" value="Test algorithm"/>
            <br/><br/>'.$ao.'
            Decoded password:<br/>
            <input name="pw" type="text" />
            <br/><input type="submit" name="submit" value="OK"/>
        </form>
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