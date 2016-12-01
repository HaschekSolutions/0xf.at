<?php


class Hackit29 implements Hackit
{
  public function getName() { return 'Heat prediction';}
  public function getDescription(){return 'You see a room which is 10x10m wide (0x0 to 9x9).<br/>
                                          Every square has the exact temperature of <span class="green">'.$_SESSION['base_temp'].'°C</span>. We apply continuously <span class="green">'.$_SESSION['heat_temp'].'°C</span> heat to the field <span class="green">'.$_SESSION['heat_field'].'</span><br/>
                                          What is the temperature (rounded to whole numbers) in the field <span class="green">'.$_SESSION['target_field'].'</span> after <span class="green">'.$_SESSION['temp_iterations'].'</span> iterations according to the <a target="_blank" href="https://blog.haschek.at/post/f8dd4">Gauss-Seidel iteration</a>';}
  public function getTags(){return 'Programming';}

  public function isSolved()
  {
        if($_REQUEST['pw']==$_SESSION['levels'][basename(__FILE__, '.php')])
            return true;
        else
            return false;
  }

  public function prepare()
  {
    $a = new Algorithms;
    $answer = 0;

    if(!$_REQUEST['pw'] || ($_REQUEST['pw'] && !$this->isSolved()))
    {

      while($answer==0)
      {
        $heat_add_field_x = rand(0,9);
        $heat_add_field_y = rand(0,9);

        $heat_read_field_x = rand(0,9);
        $heat_read_field_y = rand(0,9);
        $_SESSION['base_temp'] = rand(1,40);
        $_SESSION['heat_temp'] = rand(61,200);
        $_SESSION['temp_iterations'] = rand(30,150);

        $_SESSION['heat_field'] = $heat_add_field_x.'x'.$heat_add_field_y;
        $_SESSION['target_field'] = $heat_read_field_x.'x'.$heat_read_field_y;


        for($y=0;$y<10;$y++)
          for($x=0;$x<10;$x++)
          {
            $field[$y][$x] = $_SESSION['base_temp'];
            $heat[$y][$x] = 0;
            if($x==$heat_add_field_x && $y==$heat_add_field_y)
              $heat[$y][$x] = $_SESSION['heat_temp'];
          }

          $_SESSION['table'] = '<h4>Field in the beginning</h4>'.$a->Array2DToTable($field);

        for($i=0;$i<$_SESSION['temp_iterations'];$i++)
        {  
            $field = $a->GaussSeidel2D(10,10,$field,$heat);
            if($i==4)
              $_SESSION['table2'] = '<h4>Field after 4 iterations (rounded after 4th iteration)</h4>'.$a->Array2DToTable($field);
        }
        //$_SESSION['table'] = $a->Array2DToTable($field);

        $answer = round($field[$heat_read_field_y][$heat_read_field_x]);
      }
      $_SESSION['levels'][basename(__FILE__, '.php')] = $answer;
    }


      
    //var_dump($_SESSION['levels'][basename(__FILE__, '.php')]);
  }

  public function render()
  {
        $html = new HTML;
    $a = new Algorithms;
        if($_REQUEST['text'])
            $ao = $html->warning('Algorithm output: <strong style="color:black;">'.$this->algo($_REQUEST['text']).'</strong>').'<br/><br/>';

    return '            <p>'.$this->getDescription().'</p>
            <div>'.$_SESSION['table'].'</div><br/>
            <div>'.$_SESSION['table2'].'</div><br/>
            Temperature in field '.$_SESSION['target_field'].' after '.$_SESSION['temp_iterations'].' iterations:<br/>
            <input id="pw" type="text" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <script type="text/javascript">

                function checkPW()
                {
                    var el = document.getElementById("pw");
                    document.location.href="?pw="+el.value;
                }
                
            </script>';
  }

}