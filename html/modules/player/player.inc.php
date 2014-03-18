<?php # player.inc.php
/******************************************************************************
 *  File name: player.inc.php
 *  Created by: David Demaree
 *  Contact: dave.demaree@yahoo.com
 *  Purpose: Set constants and error handler
 *** History ***  
 * 2014-03-09 Original.  DHD
 *****************************************************************************/
if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
if ($debug) { echo "include file=player.inc.php:$page_id.<br>"; }

// Determine which page to display:
switch ($page_id) {
  case 'play-find':
    playerFind();
    break;
  case 'play-list':
    playerList();
    break;
  case 'play-updt':
    playerList();
    break;
 
  // Default is a blank player form.
  default:
    playerNew();
    break;
   
} // End of main switch.

/*******************************************************************************
* playerNew()
* Purpose: Set up a blank player form, ready for find or add
*******************************************************************************/
function playerNew() {
  # declare globals
  global $debug;

# initialize the player form
require(BASE_URI . "modules/player/player.form.init.php");

  if ($debug) { echo "plyr:plyrNew.<br>"; }

  # Get the next available player id number
  $plyr->get_next_id();
  $error_msgs['errorDiv'] = "Add new player:";

  if ($debug) { echo "plyr:plyrNew:end={$plyr->get_member_id()}.<br>"; }

# Show the player form
require(BASE_URI . "modules/player/player.form.php");

}


/*******************************************************************************
* playerFind()
* Purpose: Search for an existing player and display the results
*******************************************************************************/
function playerFind() {
  # declare globals
  global $debug;
  if ($debug) { echo "plyr:playerFind={$_POST['member_id']}.<br>"; }
  #post_dump();

# initialize the player form
require(BASE_URI . "modules/player/player.form.init.php");

  # Look for player by id 
  $plyr->set_member_id($_POST['member_id']);
  if ($debug) { echo "plyr finding:{$plyr->get_member_id()}. <br>"; }
  try {
      $plyr->get();
  }
  catch (playerException $d) {
    #echo "plyr get failed:{$plyr->get_member_id()}.<br>";
    switch ($d->getCode()) {
    case 2210:
      $error_msgs['member_id'] = "player not found. ({$d->getCode()})";
      $error_msgs['errorDiv'] = "See errors below";
      $error_msgs['count'] += 1;
      break;
    default:
      echo "plyr find failed:plyr->get_member_id():" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
      $p = new Exception($d->getPrevious());
      echo "plyr Previous exception:plyr->get_member_id():" . $p->getMessage() . ".<br>";
      throw new Exception($p);
    }
  }
  if ($error_msgs['count'] == 0) {
    $error_msgs['errorDiv'] = "player found.";
  }

  if ($debug) { echo "plyr:playerFind:end={$plyr->get_member_id()}:{$error_msgs['count']}:{$error_msgs['errorDiv']}.<br>"; }

# Show the player form
require(BASE_URI . "modules/player/player.form.php");

}

/*******************************************************************************
* plyrTest()
* Purpose: Show some test data for add functions
*******************************************************************************/
function plyrTest() {
  if ($debug) { echo "plyr:plyrTest={$_POST['member_id']}.<br>"; }

  # declare globals
  global $plyr, $error_msgs;
  $plyr->testData();
  $error_msgs['errorDiv'] = "Test player created.  Press \"Add/Update\" to add the player.";

  if ($debug) { echo "plyr:plyrTest:end={$plyr->get_member_id()}.<br>"; }

}


/*******************************************************************************
* playerList()
* Purpose: Show a list of players.
*******************************************************************************/
function playerList() {
global $debug;


$players = new playerArray;
if ($debug) { echo "player:List count={$players->count}:" . count($players->playerList) . ".<br>"; }
$players->listing();
#$players->sortNick();
usort($players->playerList, array('playerArray','sortNick')); 

$players->listing();
/*
//foreach($players as $p) { $p->listing(); }
usort($players, function($a, $b)
{
    return ($a->get_score() < $b->get_score());
});
*/
//foreach($players as $p) { $p->listing(); }
}
/*******************************************************************************
* playerListingDeprecated()
* Purpose: Show a list of players.
*******************************************************************************/
function playerListingDeprecated() {
global $debug;


try {
  require(BASE_URI . "includes/pok_open.inc.php");
  # look at players table structure
  $q = $pokdb->prepare("SELECT DISTINCT member_id FROM players");
  $q->execute();
  $player_count = $q->rowCount();
  $q = $pokdb->prepare("DESCRIBE players");
  $q->execute();
  $table_columns = $q->fetchAll(PDO::FETCH_COLUMN);
  /* Select queries return a resultset */
  $query="SELECT p.*, s.response, " . 
    "@INV := (SELECT COUNT(*) FROM seats AS i WHERE i.member_id = p.member_id ) AS Inv, " . 
    "@YES := (SELECT COUNT(*) FROM seats AS y WHERE y.response = 'Y' AND y.member_id = s.member_id ) AS Yes, " . 
    "@MBE := (SELECT COUNT(*) FROM seats AS m WHERE m.response = 'M' AND m.member_id = s.member_id ) AS Mbe, " . 
    "@NOP := (SELECT COUNT(*) FROM seats AS n WHERE n.response = 'N' AND n.member_id = s.member_id ) AS No, " . 
    "@FLK := (SELECT COUNT(*) FROM seats AS f WHERE f.response = 'F' AND f.member_id = s.member_id ) AS Flk, " . 
    "@RATE := ((@YES + @MBE + @NOP - @FLK) / @INV) AS Rate " . 
    "FROM players AS p LEFT JOIN seats AS s ON p.member_id=s.member_id GROUP BY p.member_id ORDER BY Rate DESC;";
#    "(('Yes' + 'Mbe' + 'No' - 'Flk') / 'Inv' * 100 ) AS Rate " . 
  $player_list = $pokdb->query($query); # group by p.member_id  #'Invited, Yes, No, Maybe, Flaked'
  $row_count = $player_list->rowCount();
  echo $row_count.' rows selected.<br/>';
  echo "player_list var dump: ";
  var_dump($player_list);
  echo ".<br>";

  echo "<br>  *** Dump players table *** ($player_count players, {$player_list->rowCount()} entries)<br>";
  echo "<table border='1'>";
  foreach( $table_columns as $title )
  {
    echo "<th>$title</th>";
  }
#  echo "<th>Games</th>";
  echo "<th>Inv</th>";
  echo "<th>Yes</th>";
  echo "<th>Mbe</th>";
  echo "<th>No</th>";
  echo "<th>Flk</th>";
  echo "<th>%</th>";
  echo "</tr>";

  $playsArray = array();
#  $players['id'] = '33';

  $player1 = new player;
  foreach ($pokdb->query($query) as $row) {
    # 
    if (!isset($players)) {
    }

    echo "<tr>";
    echo "<td>" . $row['member_id'] . "</td>";
#    echo "<td>" . $row['eff_date'] . "</td>";
    echo "<td>" . $row['nickname'] . "</td>";
    echo "<td>" . $row['name_last'] . "</td>";
    echo "<td>" . $row['name_first'] . "</td>";
    echo "<td>" . $row['stamp'] . "</td>";
#    echo "<td>" . $row['response'] . "</td>";
    echo "<td>" . $row['Inv'] . "</td>";
    echo "<td>" . $row['Yes'] . "</td>";
    echo "<td>" . $row['Mbe'] . "</td>";
    echo "<td>" . $row['No'] . "</td>";
    echo "<td>" . $row['Flk'] . "</td>";
    echo "<td>" . $row['Rate'] . "</td>";
/*    if ($row['Inv'] == 0) {
      $rate = "?";
    } else {
      $rate = ($row['Yes'] + $row['Mbe'] + $row['No'] - $row['Flk']) / $row['Inv'] * 100;
    }
    echo "<td>" . $rate . "</td>";
*/
    echo "</tr>";
    $playsArray[] = array('member_id' => $row['member_id'], 
#      'eff_date' => $row['eff_date'],
      'nickname' => $row['nickname'], 
      'name_last' => $row['name_last'], 
      'name_first' => $row['name_first'], 
      'stamp' => $row['stamp'], 
      'Inv' => $row['Inv'], 
      'Yes' => $row['Yes'], 
      'Mbe' => $row['Mbe'], 
      'No' => $row['No'], 
      'Flk' => $row['Flk'], 
      'rate' => $row['Rate'] #$rate
    );
  }
  echo "</table>";
  echo ".<br>";
/*
foreach ($players as $pla) {
  echo "member_id={$pla['member_id']},
      eff_date={$pla['eff_date']},
      nickname={$pla['nickname']}, 
      name_last={$pla['name_last']}, 
      name_first={$pla['name_first']}, 
      stamp={$pla['stamp']}, 
      Inv={$pla['Inv']}, 
      Yes={$pla['Yes']}, 
      Mbe={$pla['Mbe']}, 
      No={$pla['No']}, 
      Flk={$pla['Flk']}, 
      rate=$rate
 .<br>";
}
*/
  echo ".<br>";
print_r($playsArray);
  echo ".<br>";

} catch (PDOException $e) {
  echo "PDO Exception: ".$e->getCode().": ".$e->getMessage()."<br/>";  
} catch (Exception $e) {
  echo "Exception: ".$e->getCode().": ".$e->getMessage()."<br/>";  
}
echo "<br>";

echo "_SESSION var dump: ";
var_dump($_SESSION);
echo ".<br> <br>";
} // End function playerListingDeprecated



?> 

<!--  Player tab buttons  -->
    <input type="submit" id="find" name="p-find" value="Find" >
    <input type="submit" id="updt" name="updt" value="Update" >
    <input type="submit" id="list" name="p-list" value="List" >
    <input type="submit" id="delt" name="delt" value="Delete" >
    <input type="submit" id="burp" name="burp" value="burp" >
  <br>
<?php if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; } ?>
<!--  End of player.form.php  -->
