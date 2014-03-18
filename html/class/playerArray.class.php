<?php
/******************************************************************************
 *  File name: playerArray.class.php
 *  Created by: David Demaree
 *  Contact: dave.demaree@yahoo.com
 *  Purpose: Class definition for a player
 *** History ***  
 * 2014-03-08 Original.  DHD
 *****************************************************************************/
class playerArray extends player
{
  public $playerList = array();
  public $playerCount;

  function __construct()
  {
    global $debug;
    if ($debug) { echo __METHOD__ . ".<br>"; }
    $this->playerCount = 0;
    try {
# Open poker database
require(BASE_URI . "includes/pok_open.inc.php");
      # get number of players
      $query = "SELECT COUNT(member_id) AS playerCount FROM members ";
      if ($debug) { echo "playerArray:players:count:query=$query.<br>"; }
      $stmt = $pokdb->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch();
      if ($debug) { echo "playerArray:playerCount={$row['playerCount']}.<br>"; }
      $this->playerCount = $row['playerCount'];
      # get members row
      $query = "SELECT MIN(member_id) AS first FROM members ";
      if ($debug) { echo "playerArray:players:next:query=$query.<br>"; }
      $stmt = $pokdb->prepare($query);
      $stmt->execute();
      $row_count = $stmt->rowCount();
      if ($row_count == 1) {
        $row = $stmt->fetch();
        if ($debug) { echo "playerArray:first={$row['first']}.<br>"; }
        $next_member_id = $row['first'];
        $loaded = 0;
        for ($i=0; $i < $this->playerCount; $i++) {
#        for ($i=0; $i < 3; $i++) {
          $this->playerList[$i] = new player;
          $this->playerList[$i]->set_member_id($next_member_id);
          # Save row
          $this->playerList[$i]->get();
          $loaded++;
          # set up next iteration
          $query = "SELECT MIN(member_id) AS next FROM members WHERE member_id > {$next_member_id} ";
#          if ($debug) { echo "player:players:get:query=$query.<br>"; }
          $stmt = $pokdb->prepare($query);
          $stmt->execute();
          $row = $stmt->fetch();
          $next_member_id = $row['next'];
        } 
      } else {
        if ($debug) { echo "playerArray:rows=$row_count.<br>"; }
      }
    } catch (PDOException $e) {
      echo "PDO Exception: " . __FILE__ . " line: " . __LINE__ . "<br/>";
      echo $e->getCode() . ": " . $e->getMessage() . "<br/>";  
    } catch (Exception $e) {
      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br/>";  
    }
# if loaded <> playerCount???
  }
/*
public static function sortNick() 
{
  usort(playerList, array('playerList','nickSort'));
}
*/
public static function sortNick($a, $b) 
{
    return strcmp($a->nickname, $b->nickname);
}

public static function sortScore($a, $b) 
{
    return ($a->score < $b->score);
}

//******************************************************************************
// List a player
//******************************************************************************
  public function listing()
  {
    global $debug;
    if ($debug) { echo "playerArray:" . __FUNCTION__ . ".<br>"; }

    echo "*** Dump players *** ({$this->playerCount} players)<br>";
    echo "<table border='1'>";
    echo "<th>ID</th>";
    echo "<th>Nickname</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
    echo "<th>Invited</th>";
    echo "<th>Yes</th>";
    echo "<th>Maybe</th>";
    echo "<th>No</th>";
    echo "<th>Flake</th>";
    echo "<th>Score</th>";
    echo "<th>Stamp</th>";
    echo "</tr>";
  
    $counter = 0;
    if ($debug) { echo "playerArray:listing count={$this->playerCount}:"; count($this->playerList); echo ".<br>"; }
  #  echo "playerArray:listing [0]="; $this[0]->listRow(); echo ".<br>";
    foreach ($this->playerList as $row) {
      $counter++;
//      if ($debug) { echo "player {$row->get_member_id()} ($counter of {$this->playerCount})<br>";}
#      $row->listRow();
      echo "<tr>";
      echo "<td>" . $row->get_member_id() . "</td>";
      echo "<td>" . $row->get_nickname() . "</td>";
      echo "<td>" . $row->get_name_last() . "</td>";
      echo "<td>" . $row->get_name_first() . "</td>";
      echo "<td>" . $row->get_invite_cnt() . "</td>";
      echo "<td>" . $row->get_yes_cnt() . "</td>";
      echo "<td>" . $row->get_maybe_cnt() . "</td>";
      echo "<td>" . $row->get_no_cnt() . "</td>";
      echo "<td>" . $row->get_flake_cnt() . "</td>";
      echo "<td>" . $row->get_score() . "</td>";
      echo "<td>" . $row->get_stamp() . "</td>";
      echo "</tr>";
    }
    echo "</table>";
#    echo ".<br>";
  }
//******************************************************************************
} # end class playerArray
//******************************************************************************
?>
