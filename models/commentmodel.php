<?php

class CommentModel extends Model
{
	function getCommentsFromLevel($level)
	{
	    if(!$level) return;
	    $file = ROOT.DS.'comments'.DS.'level'.$level.'.json';
	    if(file_exists($file))
	    {
	        $ldata = implode(file($file));
	        $json = json_decode($ldata,true);
	        foreach($json as $key=>$data)
	            if ($data['comment'] && $data['nick'] && $data['timestamp'])
	            {
	                $o.='<h3 id="post_'.$key.'">#'.(++$i).'</h3><div class="well"><strong class="green">'.$data['nick'].'</strong> ('.date("d. M y H:i",$data['timestamp']).')<br/>'.$data['comment'].'</div>';
	            }
	    }
	    


	    if($o)
	        $o = '<h1>Comments</h1>'.$o;
	    
	    return $o;
	}

	function getCommentForm($level)
	{
	    if($_POST['comment'])
	    {
	    	$o = $this->addComment($level,$_SESSION['user']?$_SESSION['user']:'Anonymous',$_POST['comment']);
	    }
		$o.='<p>
				<form method="POST">
					<small>Only people who solved this level will able to see your post</small>
					<br/>Your message:<br/>
					<input type="text" name="comment" size="100"/><br/>
					<input type="submit" name="submit" value="Save post"/></form>
			</p>';

        return $o;
	}

	function addComment($level,$nick,$comment)
	{
		$html = new HTML;
		$nick = trim($nick);$comment = trim($comment);
		if(!$nick) $nick = 'Anonymous';
		if(!$comment) return $html->error('You have to enter a message');
	    $file = ROOT.DS.'comments'.DS.'level'.$level.'.json';
	    if(file_exists($file))
	    {
	        $ldata = implode(file($file));
	        $json = json_decode($ldata,true);
	        foreach($json as $key=>$data)
	        {
	        	if($data['nick']==$nick)
	        		$nc++;
	        	if(($data['sid']==session_id() && !$_SESSION['user']) || $_SESSION['user'] && $nc > 5) return $html->error('Don\'t spam the hall of fame');
	        }
	            
	    }
	    $json[] = array('IP'=>$_SERVER['REMOTE_ADDR'],'nick'=>htmlspecialchars($nick),'sid'=>session_id(),'timestamp'=>time(),'comment'=>htmlspecialchars($comment));
	    
	    $fh = fopen($file, 'w'); 
	    fwrite($fh, json_encode($json)); 
	    fclose($fh);

	    return $html->goToLocation('?#post_'.(count($json)-1),false);
	}
}