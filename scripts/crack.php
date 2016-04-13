<?php

$pw = "301caa60358fe0ae095634a8a83ad3ed";
$lines = file('../data/wordlist.txt');
$all = pow(count($lines),2);
$ii = 0;
for($i=0;$i<count($lines);$i++)
{
	for($j=0;$j<count($lines);$j++)
	{
		$w1 = trim($lines[$i]);
		$w2 = trim($lines[$j]);
		if(md5($w1.$w2)==$pw)
		{
			echo "\nPassword was: $w1$w2\n\n";
			return;
		}
		$ii++;
	}
	
	$p = round(($ii/$all)*100,3);
	//echo "\n Gesamt: $p%\n";
	//echo '.';
	echo "\r$ii/$all => $p";
	$ii++;
}
