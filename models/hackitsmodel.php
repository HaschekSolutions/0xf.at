<?php

class HackitsModel extends Model
{
	function backend($params)
	{
		switch($params['a'])
		{
			case 's':
				$q=$params['l'];
				if($q=='8')
					return array('status'=>'OK','result'=>$_SESSION['wrong']['hackit8']);
			break;

			case 'img':
				$lvl31 = new Hackit31;
				$lvl31->renderImage($params['id']);
			break;

			case 'stillalive':
				return array('status'=>'OK');
			break;

			case 'create':
				return $this->create($params);
			break;

			case 'sid': return $this->sidExists($params['sid']);
		}
	}

	function create($params)
	{
		$nick = $params['n'];

		switch($params['ad'])
		{
			case 'l':
				return $this->getLevelsOfUser($_SESSION['user']);
			break;
		}

		return array('status'=>'OK','nick'=>$nick);
	}

	function getLevelsOfUser($nick)
	{
		$path = ROOT.DS.'data'.DS.'users'.DS.$nick.DS;
		if(!is_dir($path) || !$_SESSION['user']) return array('status'=>'NOP','levels'=>array('test','test2'));
	}

    function sidExists($sid)
    {
    	$sid = rawurldecode($sid);
    	$um = new UsersModel;
        if($_SESSION['user']) return array('status'=>'ERR');
        $a = explode(';', $sid);
        $nick = base64_decode($a[0]);
        $nick = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", trim($nick)));
        if(file_exists(ROOT.DS.'data'.DS.'users'.DS.$nick.'.txt'))
        {
        	$data = $um->loadUser($nick,$sid,false);
        }

        if($data) return array('status'=>'OK');
        else return array('status'=>'ERR','nick'=>$nick,'sid'=>$sid);
    }

	function getNextLevel($level)
	{
		if(file_exists(ROOT.DS.'data'.DS.'levels'.DS.'hackit'.($level+1).'.php'))return ($level+1);
		else return false;
	}

	function countSolved($lid)
	{
		//$lid--;
		$file = ROOT.DS.'stats'.DS.'level'.$lid.'.csv';
		if(!file_exists($file)) return '0';

		$sum = 0;
		$lines = file($file);
		return count($lines);
		foreach($lines as $line)
		{
			$a = explode(';',trim($line));
			if($seen[$a[0]]==1)
				continue;
			else
			{
				$seen[$a[0]]=1;
				$sum++;
			}
		}

		return $sum;
	}

	function isUserCounted($lid,$uip,$usid)
	{
		if($_SESSION['solved'][$lid]) return false;
		$file = ROOT.DS.'stats'.DS.'level'.$lid.'.csv';
		if(!file_exists($file)) return true;

		$lines = file($file);
		foreach($lines as $line)
		{
			$a = explode(';',trim($line));
			$ip = $a[0];
			$sid = $a[1];
			$time = $a[2];

			$lasthour = time()-3600;

			if($sid==$usid)return false;

			if($ip!=$uip && $sid!=$usid) continue;

			if($ips[$ip]>=2 || $sids[$sid]>=1)
				return false;
			else
			{
				$ips[$ip]++;
				$sids[$sid]++;
			}
		}
		return true;
	}

	function getLevels()
	{
		if ($handle = opendir(ROOT.DS.'data'.DS.'levels'.DS)) {
		    while (false !== ($file = readdir($handle))) {
		        if ($file != "." && $file != "..") {
		            if(substr($file, 0,6)=='hackit')
		            {
		            	$level = substr($file, 6,-4);
		            	$m = "Hackit$level";
		            	$h = new $m;
		            	$o[$level] = array('description'=>$h->getDescription(),'name'=>$h->getName(),'tags'=>$h->getTags());
		            }
		        }
		    }
		    closedir($handle);
		}

		return $o;
	}
}
