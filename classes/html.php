<?php

class HTML {

    private $js = array();

    function shortenUrls($data) {
        $data = preg_replace_callback('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', array(get_class($this), '_fetchTinyUrl'), $data);
        return $data;
    }
    
    function getIcon($class,$size=false,$color=false)
    {
        return '<i style="'.($size?'font-size:'.$size.'pt;':'').($color?'color:'.$color.';':'').'" class="'.$class.'"></i>';
    }
    
    function small($text,$class='')
    {
        return '<small class="'.$class.'">'.$text.'</small>';
    }

    function red($text)
    {
        return '<span class="text-danger">'.$text.'</span>';
    }

    function green($text)
    {
        return '<span class="text-success">'.$text.'</span>';
    }
    
    function p($text,$class='')
    {
        return '<p class="'.$class.'">'.$text.'</p>';
    }
    
    /**
     * 
     * @param type $arr array array('home'=>'/home','stuff'=>'/home/stuff,'aktuell');
     * @param type $lastisactive
     * @return string
     */
    function navi($arr)
    {
        if(!is_array($arr)) return;
        $o = '<ul class="breadcrumb" style="margin-bottom: 5px;">';

        foreach($arr as $text => $link)
        {
            if(!$link)
                $o.='<li class="active">'.$text.'</li>';
            else
                $o.='<li><a href="'.$link.'">'.$text.'</a></li>';
        }
        $o.= '</ul>';
        
        return $o;
    }

    private function _fetchTinyUrl($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url[0]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return '<a href="' . $data . '" target = "_blank" >' . $data . '</a>';
    }

    function getCheckbox($name,$text='',$value=1,$checked=false,$disabled=false)
    {
        return '<div class="checkbox">
                    <label>
                        <input type="checkbox" name="'.$name.'" value="'.$value.'" '.($checked?'checked':'').' '.($disabled?'disabled':'').'> '.$text.'
                    </label>
                </div>';
    }
    
    function arrayToCheckbox($data,$name,$checked=0)
    {
        if(!is_array($data)) return;
        foreach($data as $key=>$d)
        {
            if(is_array($checked) &&  in_array($d, $checked))
                    $checked = ' checked';
            else $checked = '';
            $t = '<input type="checkbox" name="'.$name.'" value="'.$d.'" '.$checked.'/>';
            if($key)
                $t = $key.' '.$t;
            $o[] = $t;
        }
        
        return $o;
    }
    
    function code($data,$class='markdown')
    {
        return '<pre><code class="'.$class.'">'.$data.'</code></pre>';
    }
    
    function form($data,$submitvalue=BUTTON_SAVE,$action="",$submitname="submit")
    {
        $submit = '<div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <input type="submit" class="btn btn-primary" name="submit" value="'.$submitvalue.'" />
                    </div>
                </div>';
        return '<form role="form" enctype="multipart/form-data" method="POST" action="'.$action.'"><fieldset>'.$data.'<br/>'.$submit.'</fieldset></form>';
    }
    
    function number($z,$nachkommastellen=0)
    {
        return number_format($z, $nachkommastellen, ',', '.');
    }
    
    function textarea($name,$data='',$cols=50,$rows=10,$forcewysiwyg=false)
    {
        $cs = new CubeshopModel;
        if($_SESSION['user'] && ($cs->hasUserItem('bbcode') || $forcewysiwyg))
            $textarea = $this->getWYSIWYGEditor($name,$data);
        else $textarea = '<textarea name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'">'.$data.'</textarea>';
        
        return $textarea;
    }
    
    function center($data)
    {
        return '<center>'.$data.'</center>';
    }
    
    function displayError($e)
    {
        $text = addslashes($this->error($e));
        return '<script>$(document).ready(function(){error("'.$text.'");});</script>';
    }
    
    function displaySuccess($e)
    {
        $text = addslashes($this->success($e));
        return '<script>$(document).ready(function(){error("'.$text.'");});</script>';
    }
    
    function clear()
    {
        return '<div class="clear"></div>';
    }
    
    function menu($arr, $id = "", $class = "")
    {
        aasort($arr, 'priority');
        $o = '<ul id="' . $id . '" class="nav navbar-nav">';
        foreach ($arr as $key => $val)
        {
            if ($val['active'])
                $c = 'active';
            else
                $c = '';

            $sub = getSubMenu(strtolower($key));
            if($sub)
            {
                $o.='<li class="dropdown menu_item '.$c.'" page="'.strtolower($key).'" id="page_' . strtolower($key) . '">
                        <a href="' . DS . strtolower($key) . '" data-toggle="dropdown" class="dropdown-toggle ' . $c . ' '.$val['class'].'"><center>' . $val['text'] . '<span class="caret"></span></center></a>
                        <ul class="dropdown-menu" role="menu">';
                $o.='<li><a href="' . DS . strtolower($key) . '">'.strip_tags($val['text'],'<i>').'</a></li>'.
                    '<li class="divider"></li>';
                foreach($sub as $key => $item)
                {
                    if($val['active'] && ($val['active_submenu']==strtolower($item['action']))) $cc = 'active'; else $cc = '';

                    if(substr($item['action'], 0, 1)=='/')
                            $base = '';
                    else
                        $base = DS.$item['base'] . DS;
                    
                    if($item['text']=='--divider--')
                        $o.='<li class="divider"></li>';
                    else
                        $o.='<li class="'.$cc.'"><a href="' . $base. $item['action'] . '" class="">'.$item['text'].'</a></li>';
                }
                $o.='   </ul>
                      </li>';
            }
            else
            {
                $o.= '<li class="menu_item ' . $c . '" page="'.strtolower($key).'" id="page_' . strtolower($key) . '">
                        <a href="' . DS . strtolower($key) . '" class="'.$val['class'].'">
                            <center>' . $val['text'] . '</center>
                        </a>
                    </li>';
            }
        }
        $o.= '</ul>';
        return $o;
    }
    
    function strong($text,$id = "", $class = "")
    {
        return '<strong class="'.$class.'" id="'.$id.'">'.$text.'</strong>';
    }
    
    function dfn($text,$desc,$id = "", $class = "")
    {
        return '<dfn class="'.$class.'" id="'.$id.'" title="'.$desc.'">'.$text.'</dfn>';
    }
    
    function tip($text,$id = "", $class = "")
    {
        return '<span class="tip '.$class.'" id="'.$id.'">'.$text.'</span>';
    }

    function submenu($arr, $id = "", $class = "") {
        if (!is_array($arr))
            return false;
        $o = '<ul id="' . $id . '" class="' . $class . '">';
        foreach ($arr as $key => $val) {
            if ($val['active'])
                $c = 'active';
            else
                $c = '';
            $o.= '<li id="sub_' . strtolower($val['action']) . '"><a href="' . DS . $val['base'] . DS . strtolower($val['action']) . '" class="' . $c . '">' . $val['text'] . '</a></li>';
        }
        $o.= '</ul>';
        return $o;
    }
    
    /**
     * $timestamp = zeitpunt des ablaufens in unix timestamp
     */
    function countdown($timestamp,$prestring="",$id=0,$allownegative=false)
    {
        $a = new Algorithms();
        if(!$id) $id = $a->getRandomHash(8);
        $seconds = $timestamp-time(); 
        //return '<span id="'.$id.'"><script>countdown("#'.$id.'",'.$timestamp.',"'.$prestring.'",'.(time()*1000).',0);</script></span>';
        return '<span id="'.$id.'"><script>countdown("#'.$id.'","","'.$prestring.'",'.($seconds*1000).',"'.$allownegative.'");</script></span>';        
    }

    function sanitize($string)
    {
        return preg_replace('~[^a-zÀ-ÖØ-öÿŸ\d]++~ui', ' ', $string);
    }
    
    function specialchars($text,$utf8=0)
    {
        return htmlspecialchars($text);
    }
    
    /*
     * @param string $name
     * @param string $value
     * @param string $type
     * @param string $id
     * @param string $class
     * @param int $size
     */
    function input($name, $value = '', $type = 'text', $id = '', $class = '', $size = '20',$onClick='',$extra='')
    {
        return '<input type="' . $type . '" onClick="'.$onClick.'" value="' . $value . '" class="' . $class . '" id="' . $id . '" name="' . $name . '" size="' . $size . '" '.$extra.' />';
    }
    
    function newInput($name,$placeholder=false,$type='text')
    {
        return '<input type="'.$type.'" class="form-control empty" name="'.$name.'" placeholder="'.$placeholder.'">';
    }
    
    function button($name,$value,$onclick="return true;",$id='',$class="button")
    {
        return '<input type="button" name="'.$name.'" value="'.$value.'" id="'.$id.'" class="'.$class.'" onClick="'.$onclick.'"/>';
    }
    
    function buttonGoTo($value,$link,$id='',$class='btn-sm')
    {
        return '<a href="'.$link.'" id="'.$id.'" class="btn btn-primary button '.$class.'">'.$value.'</a>';
    }
    
    function formGroup($label,$input)
    {
        return '<div class="form-group">
            <label for="inputEmail" class="col-lg-2 control-label">'.$label.'</label>
            <div class="col-lg-10">
                '.$input.'
            </div>
        </div>';
    }
    
    function table($data,$header=1,$style=1,$id='')
    {
        $didbody=false;
        if (!is_array($data))
            return false;
        switch($style)
        {
            case 2: $class = 'table table-striped'; break;
            case 3: $class = 'table table-condensed'; break;
            case 4: $class = 'table table-bordered table-hover';break;
            default:
                $class = 'table table-bordered table-hover';
        }
        $t = '<div class="table-responsive"><table id="'.$id.'" class="' . $class . '">';
        foreach ($data as $key => $val)
        {
            if ($key == 0 && $header)
            {
                $td = 'th';
                $t.='   <thead>'."\n"; 
            }
            else
                $td = 'td';
            if (($key != 0 || !$header) && !$didbody)
            {
               $t.='   <tbody>'."\n";
               $didbody=true;
            }


            $t.='       <tr >'."\n";
            if(is_array($val))
                foreach ($val as $j => $tdata)
                {
                    $t.='           <' . $td . ' '.$colspan.'>' . $tdata . '</' . $td . '>'."\n";
                    $class='';
                    $tid='';
                    $colspan='';
                }
            $t.='       </tr>'."\n";
            if ($key == 0 && $header)
            {
                $t.='   </thead>'."\n"; 
            }
        }
        $t.=' </tbody></table></div>';
        
        return $t;
    }
    

    /*
    *   @todo: cache file for each url
    */
    function QRCode($url)
    {
        $qurl = rawurlencode($url);
        $qr = '<img class="img-responsive" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.$qurl.'&choe=UTF-8" />';

        return $qr;
    }

    function error($msg,$title=false)
    {
        return '<div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    '.($title?'<h4>'.$title.'</h4>':'').'
                    <p>'.$msg.'</p>
                </div>';
    }

    function success($msg,$title=false)
    {
        return '<div class="alert alert-dismissable alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    '.($title?'<h4>'.$title.'</h4>':'').'
                    <p>'.$msg.'</p>
                </div>';
    }

    function warning($msg,$title=false)
    {
        return '<div class="alert alert-dismissable alert-warning">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    '.($title?'<h4>'.$title.'</h4>':'').'
                    <p>'.$msg.'</p>
                </div>';
    }
    
    function errorWithBackbutton($err, $class = 'error',$backbutton=true)
    {
        global $error;
        if (is_numeric($err) && $error[$err])
            $err = $error[$err];
        if($backbutton)
            $bb = '<br/><a href="#" onClick="history.back();return false;">Zurück..</a>';
        return '<span class="' . $class . '">' . $err . '</span>'.$bb;
    }
    
    function arrayToString($arr)
    {
        if(!is_array($arr)) return false;
        foreach($arr as $a)
        {
            $o.=$a.';';
        }
        $o = substr($o,0,-1);
        
        return $o;
    }

    function goToLocation($location = '/', $force = true) {
        $script = '<script>window.location.href="' . $location . '"</script>';
        if ($force)
            exit($script);
        else
            return $script;
    }

    function link($text, $path, $prompt = null, $confirmMessage = "Bist du sicher?",$class="")
    {
        $path = str_replace(' ', '-', $path);
        if ($prompt) {
            $data = '<a class="'.$class.'" href="' . $path . '" onclick="return confirm(\'' . $confirmMessage . '\')">' . $text . '</a>';
        } else {
            $data = '<a class="'.$class.'" href="' . $path . '">' . $text . '</a>';
        }
        return $data;
    }
    
    function liste($lines,$ulclass='',$liclass='')
    {
        if(!is_array($lines)) return false;
        $o = '<ul class="'.$ulclass.'">';
        foreach($lines as $line)
            $o.='<li class="'.$liclass.'">'.$line.'</li>';
        $o.= '</ul>';
        
        return $o;
    }

    function content_menu($content_menu)
    {
            $cmdata = '<ul class="nav round-top nav-tabs">';
            if(is_array($content_menu))
                foreach ($content_menu as $cmname=>$cmlink)
                {
                    if($cmname!='render')
                        $cmdata.='  <li><a href="'.$cmlink.'"><span>'.$cmname.'</span></a></li>';
                    else
                        $cmdata.='  <li>'.$cmlink.'</li>';
                }
            $cmdata.='</ul>';
            $content_menu = $cmdata;

        return $content_menu;
    }
    
    /**
     * 
     * @param string $text
     * @param int $color 0->grau,1->blau,2->grün,3->hellblau,4->orange,5->rot
     */
    function getLabel($text,$color=0)
    {
        switch($color)
        {
            case 1:
                $label = 'label-primary';
            break;
        
            case 2:
                $label = 'label-success';
            break;
        
            case 3:
                $label = 'label-info';
            break;
        
            case 4:
                $label = 'label-warning';
            break;
        
            case 5:
                $label = 'label-danger';
            break;
            
            default:
                $label = 'label-default';
        }
        
        return '<span class="label '.$label.'">'.$text.'</span>';
    }
    
    function getListGroup($items,$firsthighlight=false,$withseperator=false)
    {
        $o = '<div class="list-group">';
        if(is_array($items))
            foreach($items as $key=>$item)
            {
                if($firsthighlight&&$key==0)
                    $o.='<div class="list-group-item active">'.$item.'</div>';
                else
                    $o.='<div class="list-group-item">'.$item.'</div>';
                
                if($withseperator)
                    $o.='<div class="list-group-separator"></div>';
            }
        $o.= '</div>';
        
        return $o;
    }
    
    function getArrowRight()
    {
        return '<img src="/css/imgs/arrow_right.png" height="20px" /> ';
    }

    function includeJs($fileName) {
        $data = '<script src="' . BASE_PATH . '/js/' . $fileName . '.js"></script>';
        return $data;
    }

    function includeCss($fileName) {
        $data = '<style href="' . BASE_PATH . '/css/' . $fileName . '.css"></script>';
        return $data;
    }
    
    function getInfoMessage($message,$text=false)
    {
        $message = str_replace('"', "'", $message);
        $message = str_replace("'", "\'", $message);
        return '<img class="tooltip" title="'.(($message)).'" src="/css/imgs/info.png" />';
        //return '<span onmouseover="Tip(\''.$message.'\')" onmouseout="UnTip()">'.($text?$text:'<img src="/css/imgs/info.png" />').'</span>';
    }
    
    function BBCode($Text) 
    { 
        //$Text = utf8_encode($Text);
         // Replace any html brackets with HTML Entities to prevent executing HTML or script
         // Don't use strip_tags here because it breaks [url] search by replacing & with amp
         $Text = str_replace("<", "&lt;", $Text); 
         $Text = str_replace(">", "&gt;", $Text); 

         // Convert new line chars to html <br /> tags 
         $Text = nl2br($Text); 

         // Set up the parameters for a URL search string 
         $URLSearchString = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'"; 
         // Set up the parameters for a MAIL search string 
         $MAILSearchString = $URLSearchString . " a-zA-Z0-9\.@";

         // Perform URL Search 
         $Text = preg_replace("/\[url\]([$URLSearchString]*)\[\/url\]/", '<a href=\'$1\' target=\'_blank\'>$1</a>', $Text); 
         $Text = preg_replace("(\[url\=([$URLSearchString]*)\](.+?)\[/url\])", '<a href=\'$1\' target=\'_blank\'>$2</a>', $Text); 
      //$Text = preg_replace("(\[url\=([$URLSearchString]*)\]([$URLSearchString]*)\[/url\])", '<a href="$1" target="_blank">$2</a>', $Text);

         // Perform MAIL Search 
         $Text = preg_replace("(\[mail\]([$MAILSearchString]*)\[/mail\])", '<a href=\'mailto:$1\'>$1</a>', $Text); 
         $Text = preg_replace("/\[mail\=([$MAILSearchString]*)\](.+?)\[\/mail\]/", '<a href=\'mailto:$1\'>$2</a>', $Text); 
       
         // Check for bold text 
         $Text = preg_replace("(\[b\](.+?)\[\/b])is",'<strong>$1</strong>',$Text); 
         
         // Check for H1-H3
         $Text = preg_replace("(\[h1\](.+?)\[\/h1])is",'<h1>$1</h1>',$Text);
         $Text = preg_replace("(\[h2\](.+?)\[\/h2])is",'<h2>$1</h2>',$Text);
         $Text = preg_replace("(\[h3\](.+?)\[\/h3])is",'<h3>$1</h3>',$Text);

         // Check for Italics text 
         $Text = preg_replace("(\[i\](.+?)\[\/i\])is",'<em>$1</em>',$Text); 

         // Check for Underline text 
         $Text = preg_replace("(\[u\](.+?)\[\/u\])is",'<span style=\'text-decoration: underline;\'>$1</span>',$Text);

         // Check for strike-through text 
         $Text = preg_replace("(\[s\](.+?)\[\/s\])is",'<span style=\'text-decoration: line-through;\'>$1</span>',$Text); 

         // Check for over-line text 
         $Text = preg_replace("(\[o\](.+?)\[\/o\])is",'<span style=\'text-decoration: overline;\'>$1</span>',$Text); 

         // Check for colored text 
         $Text = preg_replace("(\[color=(.+?)\](.+?)\[\/color\])is","<span style='color: $1'>$2</span>",$Text); 

         // Check for sized text 
         $Text = preg_replace("(\[size=(.+?)\](.+?)\[\/size\])is","<span style='font-size: $1px'>$2</span>",$Text);

         // Check for list text 
         $Text = preg_replace("/\[ul\](.+?)\[\/ul\]/is", '<ul class=\'listbullet\'>$1</ul>' ,$Text);
         $Text = preg_replace("/\[list\](.+?)\[\/list\]/is", '<ul class=\'listbullet\'>$1</ul>' ,$Text);
         $Text = preg_replace("/\[list=1\](.+?)\[\/list\]/is", '<ul class=\'listdecimal\'>$1</ul>' ,$Text); 
         $Text = preg_replace("/\[list=i\](.+?)\[\/list\]/s", '<ul class=\'listlowerroman\'>$1</ul>' ,$Text); 
         $Text = preg_replace("/\[list=I\](.+?)\[\/list\]/s", '<ul class=\'listupperroman\'>$1</ul>' ,$Text); 
         $Text = preg_replace("/\[list=a\](.+?)\[\/list\]/s", '<ul class=\'listloweralpha\'>$1</ul>' ,$Text); 
         $Text = preg_replace("/\[list=A\](.+?)\[\/list\]/s", '<ul class=\'listupperalpha\'>$1</ul>' ,$Text); 
         $Text = str_replace("[*]", "<li>", $Text); 
         $Text = preg_replace("/\[li\](.+?)\[\/li\]/s", '<li>$1</li>' ,$Text); 

         // Check for font change text 
         $Text = preg_replace("(\[font=(.+?)\](.+?)\[\/font\])","<span style='font-family: $1;'>$2</span>",$Text); 
         
         $Text = preg_replace("(\[code=(.+?)\](.+?)\[\/code])is","<pre>$1 code:<code class=\"$1\">$2</code></pre>",$Text);
         $Text = preg_replace("(\[code\](.+?)\[\/code])is","<pre>Code:<code class=\"markdown\">$1</code></pre>",$Text); 
         
         $Text = preg_replace("(\[spoiler](.+?)\[\/spoiler])is","<div class='spoiler'><input type='button' value='Spoiler anzeigen' onClick=\"$(this).parent().children('.spoiltext').fadeIn();\"/><div class='spoiltext markdown invisible'>$1</div></div>",$Text); 
         //$Text = preg_replace("(\[b\](.+?)\[\/b])is",'<strong>$1</strong>',$Text); 

//         // Declare the format for [code] layout 
//         $CodeLayout = '<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
//                             <tr> 
//                                 <td class="quotecodeheader"> Code:</td>
//                             </tr> 
//                             <tr> 
//                                 <td class="codebody">$1</td> 
//                             </tr> 
//                        </table>'; 
//         // Check for [code] text 
//         $Text = preg_replace("/\[code\](.+?)\[\/code\]/is","$CodeLayout", $Text); 
//         // Declare the format for [php] layout 
//         $phpLayout = '<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
//                             <tr> 
//                                 <td class="quotecodeheader"> Code:</td>
//                             </tr> 
//                             <tr> 
//                                 <td class="codebody">$1</td> 
//                             </tr> 
//                        </table>'; 
//         // Check for [php] text 
//         $Text = preg_replace("/\[php\](.+?)\[\/php\]/is",$phpLayout, $Text); 

         // Declare the format for [quote] layout 
         $QuoteLayout = '<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr> 
                                 <td class="quotecodeheader"> Quote:</td>
                             </tr> 
                             <tr> 
                                 <td class="quotebody">$1</td> 
                             </tr> 
                        </table>'; 
                   
         // Check for [quote] text 
         $Text = preg_replace("/\[quote\](.+?)\[\/quote\]/is", $this->tip('Zitat:')."<div class=\"well\">$1</div>", $Text);
         $Text = preg_replace("(\[quote\=([$URLSearchString]*)\](.+?)\[/quote\])", $this->tip('Zitat von $1:')."<div class=\"well\">$2</div>", $Text); 
       
         // Images 
         // [img]pathtoimage[/img] 
         $Text = preg_replace("/\[img\](.+?)\[\/img\]/", '<img src=\'$1\'>', $Text); 
       
         // [img=widthxheight]image source[/img] 
         $Text = preg_replace("/\[img\=([0-9]*)x([0-9]*)\](.+?)\[\/img\]/", '<img src=\'$3\' height=\'$2\' width=\'$1\'>', $Text); 
       
        return $Text; 
    }

    function well($content)
    {
        return '<div class="well">'.$content.'</div>';
    }
    
    function getProgressBar($percent,$class="",$text='')
    {
        if($percent<0)$percent = 0;
        if($percent>100)$percent = 100;
        
        return '<div class="progress">
                <div class="progress-bar '.$class.'" role="progressbar" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent.'%;">
                    '.($text?$text:'<span class="sr-only">'.$percent.'</span>').'
                </div>
              </div>';
        
        /*
        return '<div width="'.$width.'" class="progressbar_wrapper"><strong style="color:black;float:left;margin-left:50%;margin-top:4px;">'.$alternatetext.'</strong>
                    <div style="width:'.$percent.'%" class="progressbar_progress"></div>
               </div>';
         * 
         */
    }

    function row3to1($array)
    {
        if(!is_array($array)) return;
        return '<div class="row">
                  <div class="col-md-8">'.$array[0].'</div>
                  <div class="col-md-4">'.$array[1].'</div>
                </div>';
    }
    
    function row($array,$parts=3)
    {
        switch($parts)
        {
            case 2: $p = 6; break;
            case 4: $p = 3; break;
            default:
                case 3: $p = 4; break;
        }
        if(!is_array($array)) return;
        $o = '<div class="row">';
        foreach($array as $item)
        {
            $o.='<div class="col-sm-'.$p.'">'.$item.'</div>'."\n";
        }
        
        $o.='</div>';
        
        return $o;
    }
    
    /**
     * 
     * @param type $title
     * @param type $content
     * @param int $type (0->grau,1->blau,2->grün,3->rot)
     * @return string
     */
    function getPanel($content,$title="",$type=0,$class='')
    {
        switch($type)
        {
            case 1:
                $panel = 'panel-primary';
            break;
        
            case 2:
                $panel = 'panel-success';
            break;
        
            case 3:
                $panel = 'panel-danger';
            break;
        
            default: $panel = 'panel-default';
        }
        
        return '<div class="panel '.$panel.' '.$class.'">
                    <div class="panel-heading">
                      <h3 class="panel-title">'.$title.'</h3>
                    </div>
                    <div class="panel-body">'.$content.'</div>
                </div>';
    }
    
    function getBadge($text,$class='')
    {
        return '<span class="badge '.$class.'">'.$text.'</span>';
    }
    
    function getContentBox($content,$title='',$id='',$class='',$bid='',$collapse=true,$start=0)
    {
        $flip = $start?'flip':'';
        if($collapse)
            $control = '<a class="contentbox_toggle right '.$flip.'" href="#" onClick="return false;"><img title="Aus/Einfahren" src="/css/imgs/contentBoxenArrow.png" class="flip" /></a>';
        if($start)
            $invis = 'invisible';
//        $o = '<div id="'.$bid.'" class="content_box '.$class.'">
//                    <div class="content_box_header">'.$control.$title.'</div>
//                    <div id="'.$id.'" class="'.$invis.' content_box_content">'.$content.'</div>
//              </div>';
        
        $o = '<div id="'.$bid.'" class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">'.$title.'</h3>
                </div>
                <div id="'.$id.'" class="panel-body '.$invis.' '.$class.' content_box_content">'.$content.'</div>
              </div>';
        
        return $o;
    }
    
    function div($data,$id='',$class='')
    {
        return '<div id="'.$id.'" class="'.$class.'">'.$data.'</div>';
    }

    function span($text,$class='')
    {
        return '<span class="'.$class.'">'.$text.'</span>';
    }
    
    function select($name,$data=null,$selected=null,$id='')
    {
        $o = '<select id="'.$id.'" class="form-control" name="'.$name.'">';
        if(is_array($data))
            foreach($data as $key=>$val)
            {
                if($selected==$key) $sel = ' selected';
                else $sel = '';
                $o.='<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
            }
        $o.= '</select>';
        
        return $o;
    }
    

}