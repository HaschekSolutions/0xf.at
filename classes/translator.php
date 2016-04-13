<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of translator
 *
 * @author Christian
 */
class Translator
{
        /**
         *  Gendert ein Wort nach den eingegebenen kriterien
         * @param type $user für welchen Benutzer soll das Geschlecht geprüft werden?
         * @param type $word das wort, welches gegendert wird
         * @param type $append dieser String wird angehängt, wenn die bedingung erfüllt ist
         * @param type $appendif wenn 1 und geschlecht weiblich dann häng $append an, wenn 2 und männlich dann auch
         * @return type das gegenderte wort
         */
        function genderIn($user,$word,$append,$appendif=2)
        {
            $um = new UsersModel($user);
            switch($um->getSex())
            {
                case 1: if($appendif==1) return $word.$append; break;
                case 2: if($appendif==2) return $word.$append; break;
                default: return $word.'/'.$append;
            }
            return $word;
        }
        function translateUserStatus($status)
        {
            switch($status)
            {
                case 0: return 'Administrator';
                case 1: return 'Lehrer';
                case 2: return 'Schüler';
                case 3: return 'Absolvent';
                case 4: return 'Abgänger';
                case 5: return 'Unternehmen';
                case 6: return 'Moderator';
            }
        }

        function translateOnlineStatus($status)
        {
            switch($status)
            {
                case 0: return 'Offline';
                case 1: return 'Online';
                case 2: return 'Idle';
            }
        }
        
        function translateOffer($o)
        {
            switch($o)
            {
                case 0: return 'Suche';
                case 1: return 'Biete';
            }
        }

        function translateBirthdayDisplayStatus($status)
        {
            switch($status)
            {
                case 0: return 'Nicht anzeigen';
                case 1: return 'Nur Datum anzeigen';
                case 2: return 'Datum ohne Jahr';
                case 3: return 'Datum und Alter anzeigen';
                case 4: return 'Nur Alter anzeigen';
            }
        }
        
        function translatePictureSize($size)
        {
            switch($size)
            {
                case 1: return '50x50';
                case 2: return '100x100';
                case 3: return '200x200';
                case 4: return '648x480';
                case 4: return '800x600';
                case 4: return '1024x768';
            }
        }

        function translateLastnameDisplayStatus($status)
        {
            switch($status)
            {
                case 0: return 'Vollständig sichtbar';  
                case 1: return 'Nur erster Buchstabe';
                case 2: return 'Nicht sichtbar';
            }
        }

        function translateUserSchoolStatus($status)
        {
            switch($status)
            {
                case 0: return 'Schuladministrator';
                case 1: return 'Direktor';
                case 2: return 'Abteilungsvorstand';
                case 3: return 'Professor';
                case 4: return 'Fachlehrer';
                case 5: return 'Dozent';
                case 6: return 'Schulsprecher';
                case 7: return 'Abteilungssprecher';
                case 8: return 'Klassensprecher';
                case 9: return 'Student';
                case 10: return 'Schüler';
            }
        }

        function translateSchoolType($type)
        {
            switch($type)
            {
                        case 0: return 'Universität';
                    case 1: return 'Fachhochschule';
                    case 2: return 'Akademie';
                        case 3: return 'Kolleg';
                        case 4: return 'HTL';
                        case 5: return 'HAK';
                        case 6: return 'HLW';
                        case 7: return 'Fachschule';
                        case 8: return 'Handelsschule';
                        case 9: return 'Berufsschule';
                        case 10: return 'Poly';
                        case 11: return 'AHS';
                        case 12: return 'Hauptschule';
                        case 13: return 'Sonderschule';
                        case 14: return 'Volksschule';
                        case 15: return 'WKS';
                        case 16: return 'BAKIP';
                        case 17: return 'Volksschule';
                }
        }

        function translateTimestampToDate($timestamp,$style)
        {
            if(is_numeric($style)) $style = $this->translateTimestampStyle($style);
                return date($style,$timestamp);
        }
        
        function translateTimestampStyle($style=0)
        {
            switch($style)
            {
                case 1: return "j.n.Y";
                case 2: return "j.n.y";
                case 3: return "j.n";
                case 4: return "j.n.y (G:i)";
                    
                default:
                    return "j.n.Y (G:i)";
                    
            }
        }

        function translateDateToTimestamp($date)
        {
                return strtotime($date);
        }

        function translateRelationshipStatus($status)
        {
                switch($status)
                {
                        case 0: return 'Keine Angabe';
                        case 1: return 'Single';
                        case 2: return 'Verliebt';
                        case 3: return 'Vergeben';
                        case 4: return 'Verlobt';
                        case 5: return 'Verheiratet';
                        case 6: return 'In einer offenen Beziehung';
                        case 7: return 'In einer komplizierten Beziehung';
                        case 8: return 'Voller Liebeskummer';
                        case 9: return 'Einsam';
                }
        }

        function translateGrade($grade)
        {
                switch($grade)
                {
                        case 1: return 'Sehr Gut';
                        case 2: return 'Gut';
                        case 3: return 'Befriedigend';
                        case 4: return 'Genügend';
                        case 5: return 'Nicht Genügend';
                }
        }

        function translatePostStatus($status) //wer darf posten?
        {
                switch($status)
                {
                        case 0: return 'Eigentümer';	//nur Eigentümer
                        case 1: return 'Moderatoren';	//Eigentümer + Moderatoren
                        case 2: return 'Jeder';
                }
        }

        function translateAdPosition($position)
        {
                switch($position)
                {
                        case 0: return 'wide oben';
                        case 1: return 'wide unten';
                        case 2: return 'skyscraper links';
                        case 3: return 'skyscraper rechts';
                        case 4: return 'eingeschoben'; //(in content eingeschoben)
                }
        }

        function translateAchievementDifficulty($difficulty)
        {
                switch($difficulty)
                {
                        case 0: return 'Bronze';
                        case 1: return 'Silber';
                        case 2: return 'Gold';
                        case 3: return 'Epic';
                        case 4: return 'Legendary';
                }
        }

        

        function translateUserSex($sex)
        {
                switch($sex)
                {
                        case 0: return 'Keine Angabe';
                        case 1: return 'Männlich';
                        case 2: return 'Weiblich';
                }
        }
        
        function translateByteToMB($byte)
        {
            return round($byte/1048576,2);
        }

        function translateAgeClass($ageclass)
        {
                switch($ageclass)
                {
                        case 0: return '< 10 Jahre';
                        case 1: return '10-14 Jahre';
                        case 2: return '15-19 Jahre';
                        case 3: return '20-24 Jahre';
                        case 4: return '25-29 Jahre';
                        case 5: return '30-34 Jahre';
                        case 6: return '35-39 Jahre';
                        case 7: return '40-49 Jahre';
                        case 8: return '50-59 Jahre';
                        case 9: return '60-69 Jahre';
                        case 10: return '70+ Jahre';
                }
        }
        
        function translateWeekday($day)
        {
                switch($day)
                {
                        case 0: return 'Sonntag';
                        case 1: return 'Montag';
                        case 2: return 'Dienstag';
                        case 3: return 'Mittwoch';
                        case 4: return 'Donnerstag';
                        case 5: return 'Freitag';
                        case 6: return 'Samstag';
                }
        }

        function translateMonthToWord($month)
        {
                switch($month)
                {
                        case 1:
                                return 'Jänner';
                        case 2:
                                return 'Februar';
                        case 3:
                                return 'März';
                        case 4:
                                return 'April';
                        case 5:
                                return 'Mai';
                        case 6:
                                return 'Juni';
                        case 7:
                                return 'Juli';
                        case 8:
                                return 'August';
                        case 9:
                                return 'September';
                        case 10:
                                return 'Oktober';
                        case 11:
                                return 'November';
                        case 12:
                                return 'Dezember';
                }
        }

        function translateHoursToWords($time)
        {
                $arr = explode(':',$time);
                $time = $arr[0];
                if($time>=0 && $time <=3)
                        return 'Nacht';
                else if($time>=4 && $time <=9)
                        return 'Früh';
                else if($time>=10 && $time<=11)
                        return 'Vormittag';
                else if($time>=12 && $time <=13)
                        return 'Mittag';
                else if($time>=14 && $time <= 18)
                        return 'Nachmittag';
                else
                        return 'Abend';
        }
        
        function translateSecondsToNiceString($secs,$withseconds=false)
        {
            $units = array(
                    "Jahre"   => 365*24*3600,
                    "Monate"   => 30*24*3600,
                    "Woche"   => 7*24*3600,
                    "Tage"    =>   24*3600,
                    "Stunde"   =>      3600,
                    "Minute" =>        60,
                    "Sekunde" =>        1,
            );
            
            if(!$withseconds)
                unset($units["Sekunde"]);

            if ( $secs == 0 ) return "0 Sekunden";

            $s = "";

            foreach ( $units as $name => $divisor ) {
                    if ( $quot = intval($secs / $divisor) ) {
                            $s .= "$quot $name";
                            $s .= (abs($quot) > 1 ? "n" : "") . ", ";
                            $secs -= $quot * $divisor;
                    }
            }

            return substr($s, 0, -2);
            
//            if(!$seconds) return '0 Minuten';
//            $y = (date("y",$seconds)-70);
//            $mo = (date("n",$seconds)-1);
//            $d =  (date("j",$seconds)-1);
//            $h =  (date("h",$seconds)-1);
//            $m = date("i",$seconds);
//            $s = date("s",$seconds);
//            
//            if(substr($s,0,-1)=='0')
//                    $s = substr($s,1);
//            if(substr($m,0,-1)=='0')
//                    $m = substr($m,1);
//
//            if($y)
//                $o = $y.' '.$this->morethan1($y,'Jahr','e').', ';
//            if($mo)
//                $o .= $mo.' '.$this->morethan1($mo,'Monat','e').', ';
//            if($d)
//                $o .= $d.' '.$this->morethan1($d,'Tag','e').', ';
//            if($h)
//                $o .= $h.' '.$this->morethan1($h,'Stunde','n').', ';
//            if($m && $m!='00')
//            {
//                $o .= $m.' '.$this->morethan1($m,'Minute','n');
//                if($withseconds) $o.=', ';
//            }
//            if($withseconds)
//                $o.= $s.' '.$this->morethan1($s,'Sekunde','n');

            return $o;
        }
        
        function morethan1($count,$word,$letter_to_add)
        {
            if($count>1)
            return $word.$letter_to_add;
            else return $word;
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
