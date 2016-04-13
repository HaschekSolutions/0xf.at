<?php

class HomeView
{
	function renderStats()
	{
		$html = new HTML;
		$hm = new HackitsModel;

		$o = '<h2>Stats</h2>';

		$o.= '<h4>Levels solved</h4>';

		for($i=0;$i<14;$i++)
		{
			$level = ($i+1);
			$o.=$html->p($html->strong('Level '.$level.':').'&nbsp;&nbsp;<button type="button" title="Level '.$level.' was solved by '.$hm->countSolved($i).'" class="btn btn-default btn-lg">'.$hm->countSolved($i).'</button>');
		}

		return $o;
	}
}