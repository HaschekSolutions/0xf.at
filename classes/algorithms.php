<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of algorithms
 *
 * @author Christian
 */
class Algorithms
{
    function post($url,$data)
    {
        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

    function stringToHex($string)
    {
        $hexString = '';
        for ($i=0; $i < strlen($string); $i++) {
            $hexString .= '%' . bin2hex($string[$i]);
        }
        return $hexString;
    }

    function get_random_string($valid_chars, $length)
    {
        // start with an empty random string
        $random_string = "";

        // count the number of chars in the valid chars string so we know how many choices we have
        $num_valid_chars = strlen($valid_chars);

        // repeat the steps until we've created a string of the right length
        for ($i = 0; $i < $length; $i++)
        {
            // pick a random number from 1 up to the number of valid chars
            $random_pick = mt_rand(1, $num_valid_chars);

            // take the random character out of the string of valid chars
            // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
            $random_char = $valid_chars[$random_pick-1];

            // add the randomly-chosen char onto the end of our string so far
            $random_string .= $random_char;
        }

        // return our finished random string
        return $random_string;
    }

    function sumOfNumbersInString($string)
    {
        $sum = 0;
        for($i=0;$i<strlen($string);$i++)
        {
            $sum+=intval($string[$i]);
        }

        if($sum==0)
            $sum = 8;

        return $sum;
    }
        /**
        * 
        * Wandelt eine sekundenangabe in einen string um zb "10 stunden, 15 minuten, 55 sekunden"
        * @param int $differenz Zeit in Sekunden
        */
        function getTimeDifference($differenz)
        {
        $minuten = $stunden = 0;
        $sekunden = $differenz % 60;
        if ($differenz >= 60) {
            $differenz = floor($differenz/60);
            $minuten = $differenz % 60;
            if ($differenz >= 60) {
            $differenz = floor($differenz/60);
            $stunden = $differenz;
            }    
        }
                if($stunden)
                        $outstring = "$stunden Stunden, ";
                if($minuten || (!$minuten && $stunden))
                        $outstring .= "$minuten Minuten, ";
                $outstring .= $sekunden .' Sekunden';

                return $outstring;
        }
        
        /**
         * converts 0,5 in array(0,30,0)
         * @param type $itime
         */
        function industrialTimeToNormalTime($ihours)
        {
            $h = floor($ihours);
            $m = floor(($ihours-$h)*60);
            $s = floor((($ihours-$h)*60-$m)*60);
            
            return array($h,$m,$s);
        }
        
        function getDaysSince($date)
        {
            $now = time();
            $your_date = strtotime($date);
            $datediff = $now - $your_date;
            return floor($datediff/(60*60*24));
        }
        
        function GetDottedNumber($number)
        {
                return number_format($number, 0, ',', '.');
        }
        
        function convertTextDateToAge($date_text)
        {
                $arr = explode(".",$date_text);
                if(!$this->validator->validateDate($date_text)) return false;

                $age = date("Y") - $arr[2];
                if(date("n") <= $arr[1])
                {
                        $age--;
                        if(date("n") == $arr[1] && date("j") >= $arr[0])
                                $age++;
                }
                return $age;
        }

        function convertTextDateToMonths($date_text,$overall)
        {
                $arr = explode(".",$date_text);
                if(!$this->validator->validateDate($date_text)) return false;

                $month_now = date("n");
                $month = $arr[1];

                if($month>$month_now)
                {
                        $months = 12-($month-$month_now);
                        if($arr[0]>date("d"))
                                $months--;
                        if($months<0)
                                $months = '12';
                }
                else if($month<$month_now)
                {
                        $months = $month_now-$month;

                        if($arr[0]>date("d"))
                                $months--;
                        if($months<0)
                                $months = '12';
                }
                else if($arr[0]>date("d"))
                        $months = 11;
                else
                        $months = 0;

                //PrintDevMessage(date("d"));

                if($overall)
                        $months = ($this->TranslateTextDateToAge($date_text)*12)+$months;

                return $months;
        }
        
        function getRandomHash($digits)
        {
                $hash = md5(microtime()+time()+date("i")+rand(1,1000));
                while($digits > strlen($hash))
                        $hash.=md5(microtime()+rand(1,99999));
                return substr($hash,0,$digits);
        }

        function getRandomWords($amount=2)
        {
            $lines = file(ROOT.DS.'data'.DS.'wordlist.txt');
            if(!$lines) return 'error';
            $count = count($lines);
            for($i=0;$i<$amount;$i++)
            {
                $w[] = trim($lines[rand(0,$count)]);
            }

            return implode("", $w);
        }
        
        /**
         * 
         * @param int $cols Zellen X
         * @param int $rows Zellen Y
         * @param array $field 2D float Array
         * @param array $heat 2D float Array
         * @return array $field wird zurückgegeben
         */
        function GaussSeidel2D($cols,$rows,$field,$heat)
        {
            for($i=0;$i<$cols;$i++)
                for($j=0;$j<$rows;$j++)
                    $field[$i][$j] = (0.25 * ($field[$i-1][$j]+$field[$i+1][$j]+$field[$i][$j-1]+$field[$i][$j+1]) + $heat[$i][$j]);
            return $field;
        }

        function Array2DToTable($array)
        {
            $out  = "";
            $out .= '<table style="margin-left:auto;margin-right:auto;height:200px;width:200px;">';
            foreach($array as $key => $element){
                $out .= "<tr>";
                foreach($element as $subkey => $subelement){
                    $out .= '<td style="border: 1px solid #62c462;padding:2px;">'.round($subelement).'</td>';
                }
                $out .= "</tr>";
            }
            $out .= "</table>";

            return $out;
        }

        function sumBetween($min,$max)
        {
            $sum = 0;
            for($i=$min;$i<($max+1);$i++)
            {
                $sum+=$i;
            }

            return $sum;
        }
        
        function calculateGrade($xp,$max_xp)
        {
                $pos=($max_xp/100)*51;
                if($xp<$pos) return 5;
                $rest = $max_xp-$pos;
                $step = $rest/4;

                $sehrgut = (3*$step)+$pos;
                $gut = (2*$step)+$pos;
                $befr = (1*$step)+$pos;

                //print_r(array($sehrgut,$gut,$befr));

                if($xp>=$sehrgut) return 1;
                if($xp>=$gut) return 2;
                if($xp>=$befr) return 3;
                return 4;
        }
        
        function calculateNextGradeInXP($xp,$max_xp)
        {
            $grade = $this->calculateGrade($xp,$max_xp);
            if($grade==1) return 0;
            $ng = $grade;
            while($grade==$ng)
            {
                $xp++;
                $grade = $this->calculateGrade($xp,$max_xp);
            }
            
            return $xp;
        }

        /**
         * Diese Methode wandelt ein Text Datum in einen Wochentag um
         * Input -> 16.11.2012, Output -> Freitag
         * @param string $date_text Das Datum als Text (zb 15.10.2012)
         * @return boolean
         */
        function convertDateToWeekday($date_text)
        {
            $arr = explode(".",$date_text);
            if(!$this->validator->validateDate($date_text)) return false;

            $d = $arr[0];
            $m = $arr[1];
            $h = $arr[2];
            
            $tagesziffer = $d%7;
            
            switch($m)
            {
                case 1: $monatsziffer = 0; break;
                case 2: $monatsziffer = 3; break;
                case 3: $monatsziffer = 3; break;
                case 4: $monatsziffer = 6; break;
                case 5: $monatsziffer = 1; break;
                case 6: $monatsziffer = 4; break;
                case 7: $monatsziffer = 6; break;
                case 8: $monatsziffer = 2; break;
                case 9: $monatsziffer = 5; break;
                case 10: $monatsziffer = 0; break;
                case 11: $monatsziffer = 3; break;
                case 12: $monatsziffer = 5; break;
            }
            
            $jahresziffer = (substr($h,2) + floor(substr($h,2)/4))%7;
            
            $jahrhundertziffer = (3-(substr($h,0,2)%4))*2;
            $schaltjahr = 0;
            if(substr($h,2)%4!=0 && m<3)
                    $schaltjahr = 6;
                    
            
            $a = ($tagesziffer+$monatsziffer+$jahresziffer+$jahrhundertziffer+$schaltjahr)%7;
            
            $a++;
            if($a>6)$a=0;
            
            return $this->translator->translateWeekday($a);
        }
        
        
        /**
         * 
         * @param int $N Anzahl der Elemente gesamt
         * @param int $K Anzahl mit der gewünschten Eigenschaft
         * @param int $n Entnahme aus der Menge
         * @param int $k Ws. füfr $k elemente aus der stichprobe mit der gewünschten eigenschaft
         * @return float
         */
        function hypergeo($N,$K,$n,$k)
        {
            return ($this->n_over_k($K,$k) * $this->n_over_k($N-$K,$n-$k)) / $this->n_over_k($N,$n);
        }

        /**
        * letterToNumber('a') => 1, 'z' => 26
        */
        function letterToNumber($letter)
        {
            return ord(strtoupper($letter)) - ord('A');
        }
        
        function n_over_k($n,$k)
        {
            return ($this->getFaculty($n)) / ($this->getFaculty($k)*$this->getFaculty($n-$k));
        }
        
        function getFaculty ( $intN ) 
        { 
          if ( $intN <= 1 ) 
          { 
            return 1; 
          } 

          for ( $intFaculty = 1; $intN > 1; --$intN ) 
          { 
            $intFaculty *= $intN; 
          } 
          return $intFaculty; 
        }
        
        function howLongSince($date)
        {
            $jahre = $this->convertTextDateToAge($date);
            $monate = $this->convertTextDateToMonths($date,false);

            if($jahre || $monate)
                    $o = '<strong>';
            if($jahre==1)
                    $o.= $jahre.' Jahr</strong> und <strong>';
            else if($jahre)
                    $o.= $jahre.' Jahre</strong> und <strong>';
            if($monate==1)
                    $o.= $monate.' Monat</strong> her.';
            else
                    $o.= $monate.' Monate</strong> her.';
            
            return $o;
        }
    
    /**
     * Makes a multidimensional recursive array
     * from stackoverflow http://stackoverflow.com/questions/8587341/recursive-function-to-generate-multidimensional-array-from-database-result
     * @param array $elements
     * @param type $parentId
     * @return type
     */
    function buildTree(array $elements, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_pack'] == $parentId)
            {
                $children = $this->buildTree($elements, $element['ID']);
                if ($children)
                {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    function time_duration($seconds, $use = null, $zeros = false)
    {
        // Define time periods
        $periods = array (
            'years'     => 31556926,
            'Months'    => 2629743,
            'weeks'     => 604800,
            'days'      => 86400,
            'hours'     => 3600,
            'minutes'   => 60,
            'seconds'   => 1
            );
     
        // Break into periods
        $seconds = (float) $seconds;
        $segments = array();
        foreach ($periods as $period => $value) {
            if ($use && strpos($use, $period[0]) === false) {
                continue;
            }
            $count = floor($seconds / $value);
            if ($count == 0 && !$zeros) {
                continue;
            }
            $segments[strtolower($period)] = $count;
            $seconds = $seconds % $value;
        }
     
        // Build the string
        $string = array();
        foreach ($segments as $key => $value) {
            $segment_name = substr($key, 0, -1);
            $segment = $value . ' ' . $segment_name;
            if ($value != 1) {
                $segment .= 's';
            }
            $string[] = $segment;
        }
     
        return implode(', ', $string);
    }
}