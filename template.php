<!DOCTYPE html>
<!--
  ___        __       _      _                                                   
 / _ \__  __/ _| __ _| |_   | |__  _   _                                         
| | | \ \/ / |_ / _` | __|  | '_ \| | | |                                        
| |_| |>  <|  _| (_| | |_   | |_) | |_| |                                        
 \___//_/\_\_|(_)__,_|\__|  |_.__/ \__, |                                        
                                   |___/                                         
                      _          _        __       _       _   _                 
  /\  /\__ _ ___  ___| |__   ___| | __   / _\ ___ | |_   _| |_(_) ___  _ __  ___ 
 / /_/ / _` / __|/ __| '_ \ / _ \ |/ /   \ \ / _ \| | | | | __| |/ _ \| '_ \/ __|
/ __  / (_| \__ \ (__| | | |  __/   <    _\ \ (_) | | |_| | |_| | (_) | | | \__ \
\/ /_/ \__,_|___/\___|_| |_|\___|_|\_\   \__/\___/|_|\__,_|\__|_|\___/|_| |_|___/
                                                                                                                                                                   
-->
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Free hackits, no registration needed, no ads">
    <meta name="author" content="<?php echo $author; ?>">
    <link rel="shortcut icon" href="/favicon.ico">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/prism.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/table_sort.css" rel="stylesheet">
    <script src="/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <!-- Open Graph data -->
    <meta property="og:title" content="0xf.at - Hackits for everyone" />
    <meta property="og:image" content="http://www.0xf.at/css/imgs/logo_bg.jpg" />
    <meta property="og:description" content="Hackits for everyone, no registration needed, no ads, free for all" /> 
  </head>

  <body>
    <?php if($playing==false) { ?>
    <div class="container">
      <div class="navbar">
          <ul class="nav nav-justified" id="myNav">
            <?php echo $menu; ?>
          </ul>
      </div>
    </div>
    <?php } 
    ?><div class="container theme-showcase" role="main">
      <?php if(($header||$header_content)&& $playing==false){ 
      ?><div class="jumbotron">
        <h1 class="page-header"><?php echo $header; ?></h1>
        <p><?php echo $header_content; ?></p>
      </div>
      <?php } if($playing==true){
      ?>
      <div class="container-fluid text-center" style="padding-top:20px;"><a href="/"><img src="/css/imgs/logo.png" /></a></div>
      <div class="container-fluid text-center" style="min-height:300px;padding-top:10px;">


        <!-- :::::::::::::::::==== GAME STARTS HERE ====::::::::::::::::: -->
            <?php echo $content; ?>

        <!-- ::::::::::::::::::==== GAME ENDS HERE ====:::::::::::::::::: -->


      </div>
      <?php } else{
      ?>
      <div class="container-fluid" style="min-height:300px;">
          <p>
            <?php echo $content; ?>
          </p>
      </div>
      <?php } ?>

      <div style="padding-top:50px;" class="container text-center">
        <footer>
          <?php echo $footer?$footer:'<p>&copy; '.date("Y").' by<br/><a href="https://www.haschek.solutions"><img width="300" class="text-center" style="margin: 0 auto;" src="https://www.pictshare.net/1cd0faf462.png"></a></p>';?>
        </footer>
      </div>
    </div>

    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery.tablesorter.min.js"></script>
    <script src="/js/docs.min.js"></script>
    <script src="/js/scripts.js"></script>
    <script src="/js/prism.js"></script>
    <?php 
      if(file_exists(ROOT.DS.'tracker.html'))
        include(ROOT.DS.'tracker.html');
    ?>

  </body>
</html>
