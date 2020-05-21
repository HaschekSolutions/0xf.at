<?php

class LevelView
{
	function renderGraph()
	{
		$html = new HTML;
		foreach($_SESSION['stats'] as $level=>$data)
		{
			$rows[] = '[\'Level '.$level.'\','.$data['solved'].',\'#62c462\']';
			$time[] = '[\'Level '.$level.'\','.(is_numeric($data['avgtime'])?ceil(($data['avgtime']/60)):$data['avgtime']).',\'#62c462\']';
		}

		$rows = implode(',', $rows);
		$time = implode(',', $time);

		return '<script type="text/javascript">

			  // Load the Visualization API and the piechart package.
			  google.load(\'visualization\', \'1.0\', {\'packages\':[\'corechart\']});

			  // Set a callback to run when the Google Visualization API is loaded.
			  google.setOnLoadCallback(drawChart);
			  google.setOnLoadCallback(drawChart2);

			  // Callback that creates and populates a data table,
			  // instantiates the pie chart, passes in the data and
			  // draws it.
			  function drawChart() {

			    // Create the data table.
			    var data = new google.visualization.arrayToDataTable([
			         [\'Level\', \'Solved by\', { role: \'style\' }],
			         '.$rows.']);

			    // Set chart options
			    var options = {\'height\':500,
								backgroundColor: \'#1c1e22\',
								legendTextStyle: {color:\'#FFF\'},
								colors: [\'#62c462\', \'#62c462\', \'#62c462\', \'#cdded1\'],
								hAxis: {
								    textStyle:{color: \'#FFF\'},
								    gridlines: {
								        color: "#FFF"
								    },
								    baselineColor: \'#FFF\'
								}};

			    // Instantiate and draw our chart, passing in some options.
			    var chart = new google.visualization.ColumnChart(document.getElementById(\'chart_div\'));
			    chart.draw(data, options);
			  }

			  function drawChart2() {

			    // Create the data table.
			    var data = new google.visualization.arrayToDataTable([
			         [\'Level\', \'Average solve time in minutes\', { role: \'style\' }],
			         '.$time.']);

			    // Set chart options
			    var options = {\'height\':500,
								backgroundColor: \'#1c1e22\',
								legendTextStyle: {color:\'#FFF\'},
								colors: [\'#62c462\', \'#62c462\', \'#62c462\', \'#cdded1\'],
								hAxis: {
								    textStyle:{color: \'#FFF\'},
								    gridlines: {
								        color: "#FFF"
								    },
								    baselineColor: \'#FFF\'
								}};

			    // Instantiate and draw our chart, passing in some options.
			    var chart = new google.visualization.ColumnChart(document.getElementById(\'chart_div2\'));
			    chart.draw(data, options);
			  }

			  $(window).resize(function(){
				  drawChart();
				  drawChart2();
				});
			</script>'.$html->well('<h2>Levels solved</h2><div id="chart_div"></div>').$html->well('<h2>Average solve time</h2><div id="chart_div2"></div>');
	}


	function renderClockReport()
	{
		$html = new HTML;
		$file = ROOT.DS.'stats'.DS.'all.csv';
		if(!file_exists($file)) return 'Not enough data';
		$lines = file($file);

		for($i=0;$i<24;$i++)
		{
			$times[$i] = 0;
		}

		

		foreach($lines as $line)
		{
			$a = explode(';',trim($line));

			$level = $a[0];
			$ip = $a[1];
			$sid = $a[2];
			$time = $a[3];
			$duration = $a[4];
			$times[date("G",$time)]++;
		}

		foreach($times as $hour=>$count)
		{
			$t[] = '[\''.$hour.' H\','.$count.',\'#62c462\']';
		}

		$rows = implode(',', $t);

		return '<script type="text/javascript">

			  // Set a callback to run when the Google Visualization API is loaded.
			  google.setOnLoadCallback(drawTimeChart);

			  // Callback that creates and populates a data table,
			  // instantiates the pie chart, passes in the data and
			  // draws it.
			  function drawTimeChart() {

			    // Create the data table.
			    var data = new google.visualization.arrayToDataTable([
			         [\'Hour\', \'Hackits solved\', { role: \'style\' }],
			         '.$rows.']);

			    // Set chart options
			    var options = {\'height\':500,
								backgroundColor: \'#1c1e22\',
								legendTextStyle: {color:\'#FFF\'},
								colors: [\'#62c462\', \'#62c462\', \'#62c462\', \'#cdded1\'],
								hAxis: {
								    textStyle:{color: \'#FFF\'},
								    gridlines: {
								        color: "#FFF"
								    },
								    baselineColor: \'#FFF\'
								}};

			    // Instantiate and draw our chart, passing in some options.
			    var chart = new google.visualization.ColumnChart(document.getElementById(\'chart_time\'));
			    chart.draw(data, options);
			  }


			  $(window).resize(function(){
				  drawTimeChart();
				});
			</script>'.$html->well('<h2>Most common times</h2><div id="chart_time"></div>');
	}

	function renderList($withtime=false)
	{
		$html = new HTML;
		$hm = new HackitsModel;
		$al = new Algorithms;

		$o = '<h2>Levels</h2>';

		$d[] = array('ID&nbsp;&nbsp;&nbsp;','Name/Description','Type','Solved by','Difficulty');

		if($withtime)
			$d[0][]='Average time to solve';
		else
			$o.= '<a href="/level" class="blue">More infos &amp; stats</a>';

		$levels = $hm->getLevels();

		ksort($levels);
		foreach($levels as $level=>$data)
		{
			$name = $data['name'];
			if($_SESSION['solved'][$level])
				$name = $html->span('<i class="fa fa-check"></i> '.$name,'green');
			if($_SESSION['solvedtime'][$level])
				$yavg = '<br/>'.$html->span('You: '.$al->time_duration($_SESSION['solvedtime'][$level]),'green');
			else $yavg = '';

			if($withtime)
			{
				$solved = $hm->countSolved($level);
				$avgtime = $this->getAvgTimeOfLevel($level);
				$d[] = array($level,$html->link($name,'/play/'.$level),$data['tags'],$solved,$this->calculateDifficulty($level,$solved),$avgtime.$yavg);
				$_SESSION['stats'][$level] = array('solved'=>$solved,'avgtime'=>$this->getAvgTimeOfLevel($level,true));
			}
			else
				$d[] = array($level,$html->link($name,'/play/'.$level),$data['tags'],$hm->countSolved($level),$this->calculateDifficulty($level,$hm->countSolved($level)));
		}

		$o.= $html->table($d,1,1,'levels');

		$o.= '<script>$(document).ready(function() 
				    { 
				        //$("#levels").tablesorter({sortList: [[4,0], [0,0]]} ); //sort by difficulty
				        $("#levels").tablesorter(); //sort by ID
				    } 
				);
			</script>';

		return $html->well($o);
	}

	function calculateDifficulty($level,$solved)
	{
		$html = new HTML;
		//	var_dump($solved);
		if($solved<5)
			return $html->getProgressBar(100,'progress-bar-danger','Not yet rated');
		$hm = new HackitsModel;
		//$proz = ceil(($this->getAvgTimeOfLevel($level,true)/2000)*100);

		$proz = sqrt($this->getAvgTimeOfLevel($level,true)*2);

		return $html->getProgressBar($proz,'progress-bar-success');
	}

	function getAvgTimeOfLevel($level,$raw=false)
	{
		$al = new Algorithms;
		$file = ROOT.DS.'stats'.DS.'level'.$level.'.csv';
		if(!file_exists($file)) return 'Not enough data';
		$lines = file($file);

		foreach($lines as $line)
		{
			$a = explode(';',trim($line));

			$ip = $a[0];
			$sid = $a[1];
			$time = $a[2];
			$duration = $a[3];
			$user = $a[4];

			if($duration>3)
			{
			  $dursum+=$duration;
			  $durc++;
			}

		}

		if((!$dursum || !$durc) && $raw)
			return 0;
		else if((!$dursum || !$durc))
			return '<span class="red">Not enough data</span>';

		$durav = ceil($dursum/$durc);
		if($raw)
			return $durav;
		return $al->time_duration($durav);

	}

}