<?php

/**
 *  Every hackit has its own file like this.
 *  This example is from the actual first level.
 *
 *  Technologies you can use:
 *     * Bootstrap    - http://getbootstrap.com/components/
 *     * Font Awesome   - https://fortawesome.github.io/Font-Awesome/icons/
 *     * PrismJS    - http://prismjs.com/#basic-usage
 *     * jQuery     - https://jquery.com/
 *
 *  The methods are called by the framework in the following order:
 *
 *  ===============================
 *  $hackit->prepare();
 *  if($hackit->isSolved()===false)
 *    $hackit->render();
 *  ===============================
 *
 */

// make it so we can use shuffle() inline
function shuffle_except_not_stupid($array) { shuffle($array); return $array; }

class CityGraph {
  /* 
  ** STATICS
  **
  ** Load the sesssion graph */
  public static function loadFromSession()
  {
    if($_SESSION == null || $_SESSION['levels'] == null || $_SESSION['levels'][basename(__FILE__, '.php')] == null) {
      return null;
    }

    return CityGraph::loadFromString($_SESSION['levels'][basename(__FILE__, '.php')]);
  }

  // Load a graph from the session string encoding
  public static function loadFromString($str)
  {
    list($edges, $time) = explode(":", $str);
    $graph = new CityGraph;
    $graph->time = intval($time);

    for($i = 0; $i < strlen($edges); $i+=2)
    {
      $graph->addEdge($edges[$i], $edges[$i+1]);
    }

    return $graph;
  }

  // Generate a random graph.
  public static function randomGraph($size, $minEdges=10, $minSolution=4)
  {
    $graph = new CityGraph;

    do
    {
      $graph->generate($size, $minEdges);
    } while(strlen($graph->getBestSolution()) < $minSolution);

    return $graph;
  }


  /*
  ** INSTANCE VARIABLES
  **
  */
  private $edges = [];     // Keyed by source, values are list of destinations from source, eg. [ "A" => [ "B", "C", "D" ] ]
  public $time;            // Time graph was created

  /*
  ** METHODS
  **
  */



  //
  // Graph info

  // Returns the age of the graph in seconds
  public function age()
  {
    return time() - $this->time;
  }

  // Return the number of cities in the graph
  public function getSize()
  {
    return sizeof($this->edges);
  }

  public function getEdgeCount()
  {
    $total = 0;
    foreach($this->edges as $city => $list)
    {
      $total += sizeof($list);
    }

    return $total;
  }

  // Make a list of cities of the stated size.
  public function getCityList($size=0)
  {
    if($size == 0) $size = $this->getSize();

    $cities = [];
    for($i = 0; $i < $size; $i++)
    {
      $cities[] = $this->getCityName($i);
    }

    return $cities;
  }

  // Turn a number (e.g. 0) into a city name (e.g. A). 0 <= $number < 26.
  public function getCityName($number)
  {
    return chr(ord("A") + $number);
  }



  //
  // Route tests

  // Test if a route string (e.g. ADCEF) is valid (e.g. the edges AD, DC, CE, EF all exist)
  public function isRouteValid($route)
  {
    for($i = 1; $i < strlen($route); $i++) 
    {
      if(!$this->isEdgeValid($route[$i-1], $route[$i])) return false;
    }
    return true;
  }

  // Test if a route string (e.g. ADCEF) is valid, starts at the first city, and ends at the last city
  public function isRouteSolution($route)
  {
    if(strlen($route) < 2) return false;
    if($route[0] != $this->getCityName(0)) return false;
    if($route[strlen($route)-1] != $this->getCityName($this->getSize()-1)) return false;
    return $this->isRouteValid($route);
  }

  // Test if an edge exists in the graph
  public function isEdgeValid($origin, $destination)
  {
    if(!is_array($this->edges[$origin])) return false;
    return in_array($destination, $this->edges[$origin]);
  }

  public function getBestSolution()
  {
    return $this->getShortestRoutes()[$this->getCityList()[$this->getSize()-1]];
  }



  //
  // Generation

  // Generate a graph.
  // It will have a total node count of $size, and the first node will have at least one route to the last node.
  // It will also have at least $minEdges edges.
  public function generate($size, $minEdges)
  {
    $this->edges = [];
    $cities = $this->getCityList($size);
    foreach($cities as $city) { $this->edges[$city] = []; }

    $numEdges = $this->addRandomRoute($cities[0], $cities[$size-1]);

    while($numEdges < $minEdges) {
      if(!$this->addRandomEdge()) return;
      $numEdges++;
    }

    $this->time = time();
  }

  // Add a random route from the specific origin to the specified destination to the graph.
  // Returns the number of edges added.
  public function addRandomRoute($origin, $destination)
  {
    $trimmed = $this->getCityList();
    foreach([$origin, $destination] as $city) { unset($trimmed[array_search($city, $trimmed)]); }
    $route = $origin . implode("", shuffle_except_not_stupid($trimmed)) . $destination . $origin;
    $num_edges_added = 0;

    for($i = 1; $i < strlen($route); $i++)
    {
      if($this->addEdge($route[$i-1], $route[$i])) $num_edges_added++;
    }

    return $num_edges_added;
  }

  // Add a random edge to the graph. 
  public function addRandomEdge()
  {
    foreach(shuffle_except_not_stupid($this->getCityList()) as $origin)
    {
      foreach(shuffle_except_not_stupid($this->getCityList()) as $destination)
      {
        if($this->addEdge($origin, $destination)) return true;
      }
    }

    return false; // every possible edge is already added!
  }

  // Add an edge to the graph, if it is not already present.
  public function addEdge($a, $b)
  {
    if($a == $b || $this->isEdgeValid($a, $b)) return false;
    foreach(array($a, $b) as $city) if(!array_key_exists($city, $this->edges)) $this->edges[$city] = [];
    $this->edges[$a][] = $b;
    return true;
  }


  //
  // Route computation

  // Build a table for shortest route to each destination from A.
  // Returns an assoc array keyed by city name, containing string routes.
  public function getShortestRoutes()
  {
    $cities = $this->getCityList();
    $origin = $cities[0];
    $dist = [ $origin => 0 ];
    $prev = [ $origin => null ];
    $unvisited = [];
    $infinity = PHP_INT_MAX;

    // OK, let's Dijkstra.
    foreach($cities as $city)
    {
      if($city != $origin)
      {
        $dist[$city] = $infinity;
        $prev[$city] = null;
      }

      $unvisited[] = $city;
    }

    while(sizeof($unvisited) > 0)
    {
      $closest = null;
      foreach($unvisited as $city)
      {
        if($closest == null || $dist[$city] < $dist[$closest]) $closest = $city;
      }

      if($closest == $infinity) break; // $unvisited becomes a list of unreachable nodes
      unset($unvisited[array_search($closest, $unvisited)]);
      $new_dist = $dist[$closest]+1;

      foreach($this->edges[$closest] as $neighbor)
      {
        if(array_search($neighbor, $unvisited) == false) continue;
        if($new_dist >= $dist[$neighbor]) continue;

        $dist[$neighbor] = $new_dist;
        $prev[$neighbor] = $closest;
      }
    }

    $routes = [];
    foreach($cities as $city)
    {
      if(array_search($city, $unvisited) != false) {
        $routes[$city] = null;
        continue; // no route to unreachable node
      }

      $routes[$city] = "";
      for($node = $city; $node != null; $node = $prev[$node])
      {
        $routes[$city] = $node . $routes[$city];
      }
    }

    return $routes;
  }


  //
  // Encoding

  // Return an array of strings, each of the form "A -> B"
  public function getArrowFormattedEdges()
  {
    $result = [];
    foreach($this->edges as $origin => $list)
    {
      foreach($list as $dest)
      {
        $result[] = "$origin -> $dest";
      }
    }

    return $result;
  }

  // Return a string of the form "ABACAD". Each pair of letters indicates a origin and destination, respectively.
  // We store this in the session string to remember what someone's graph was.
  public function getSessionString()
  {
    $str = "";
    foreach($this->edges as $origin => $list)
    {
      foreach($list as $dest)
      {
        $str .= $origin . $dest;
      }
    }

    $str .= ":$this->time";
    return $str;
  }

  // Store this graph to the session.
  public function storeToSession()
  {
    $_SESSION['levels'][basename(__FILE__, '.php')] = $this->getSessionString();
  }

  public function inspect()
  {
    $lines = [];
    foreach($this->edges as $city => $neighbors)
    {
      sort($neighbors);
      $lines[] = "$city -> " . implode(", ", $neighbors) . "\n";
    }

    return implode("", $lines);
  }
}

class Hackit28 implements Hackit
{
  private $level='';
  private $deadline=30;
  private $graph_size_min=26; //26 minimum number of cities (inclusive)
  private $graph_size_max=26; //26 maximum number of cities (inclusive)
  private $graph_extra_edges_min=40; // minimum number of edges in graph. inclusive.
  private $graph_extra_edges_max=60; // maximum number of edges in graph. inclusive.
  private $graph_min_solution_length=5; // minimum length of best solution to graph

  public $author = '<a target="_blank" href="https://twitter.com/jonasacres">Jonas Acres</a>'; //You can enter your name here if you want
  public function __construct(){$this->level = basename(__FILE__, '.php');} //so you can use $this->level to get the level id

  public function getName() { return 'Roadtripping with 0xf.at';}  //The name will be displayed in the level table
  public function getDescription(){return 'Here is a list of roads between cities.<br/>You need to find the <span class="blue">FASTEST</span> way to get <span class="green">from city A to city Z</span>.<br/>Each road is one-way. For instance, A -> B means there\'s a road from city A to city B, and B -> A means there\'s a road from city B to city A.';} //This will be displayed when the level is started
  public function getTags(){return 'Programming,Mathematics';} //Describe what technology you used. Comma,seperated

  // Test if they have a graph in their session that is valid and non-expired
  // Returns graph on success, null on fail.
  public function existingGraph()
  {
    $existing = CityGraph::loadFromSession();
    if($existing != null && $existing->age < $this->deadline) return $existing;
    return null;
  }

  /**
   *
   * This method is called to check if the 
   * level has been solved. if it returns
   * true, it's solved
   *
   * @return      bool
   *
   */
  public function isSolved()
  {
    $graph = $this->existingGraph();
    if($graph == null) return false;

    // easy mode (any valid route is OK)
    // return $graph->isRouteSolution($_REQUEST['pw']);

    // hard mode (must be fastest route)
    return strtolower($_REQUEST['pw']) == strtolower($graph->getBestSolution());
  }


  /**
   *
   * The prepare method is called before
   * rendering the level. You can define
   * the password for this level, start
   * timers.. everything you want!
   *
   * @return      NULL
   *
   */
  public function prepare()
  {
    // Make sure we don't replace an existing valid graph, since otherwise we'd cause validation to fail
    $existing = $this->existingGraph();
    if($existing != null) return;

    // no existing graph, make a new one
    $cityCount = rand($this->graph_size_min, $this->graph_size_max);
    $extraEdgeCount = rand($this->graph_extra_edges_min, $this->graph_extra_edges_max);
    CityGraph::randomGraph($cityCount, $extraEdgeCount, $this->graph_min_solution_length)->storeToSession();
  }

  /**
   *
   * The render method is called last and
   * returns the HTML code that will be
   * displayed on the level. You can use
   * variables defined in the prepare
   * method since render is called last
   *
   * @return      NULL
   *
   */
  public function render()
  {
    $graph = CityGraph::loadFromSession();
    $edgeList = implode(shuffle_except_not_stupid($graph->getArrowFormattedEdges()), "<br />\n");
    return '            
            <p>'.$this->getDescription().'</p><br/>
            Solution (like this: ACDTVZ)<br/>
            <input id="pw" type="text" />
            <br/><input type="button" value="OK" onClick="checkPW()"/>
            <div>' . $edgeList.'</div>
            <script type="text/javascript">
                function checkPW()
                {
                    var pw = document.getElementById("pw");
                    window.location.href="?pw="+pw.value;
                }
                setTimeout(function(){checkPW();}, 30000);
            </script>';
  }

  public function testIfItWorks()
  {
    while(true)
    {
      $cityCount = rand($this->graph_size_min, $this->graph_size_max);
      $extraEdgeCount = rand($this->graph_extra_edges_min, $this->graph_extra_edges_max);
      CityGraph::randomGraph(26, 50, 5)->storeToSession();
      $graph = $this->existingGraph();
      $solution = $graph->getBestSolution();
      if($solution[strlen($solution)-1] != "Z")
      {
        echo "UH-OH! " . $solution . "\n";
        exit(1);
      }
    }
  }
}