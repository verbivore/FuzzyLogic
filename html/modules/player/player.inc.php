<?php # player.inc.php
/******************************************************************************
 *  File name: player.inc.php
 *  Created by: David Demaree
 *  Contact: dave.demaree@yahoo.com
 *  Purpose: Do Player actions.
 *** History ***  
 * 14-03-19 Added button attributes.  DHD
 * 14-03-18 Removed playerListDeprecated.  Stubbed playerDelete().  DHD
 * 14-03-09 Original.  DHD
 * Future:
 * Add playerDelete
 * After list, Find & Delete buttons show blank form, Update button goes to playerNew.
 *****************************************************************************/
if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
if ($debug) { echo "include file=player.inc.php:$page_id.<br>"; }
post_dump();
session_dump();
require(BASE_URI . "modules/player/player.update.inc.php");
$butt_att_find = "";
$butt_att_updt = "";
$butt_att_list = "";
$butt_att_delt = "";
$butt_att_burp = " disabled";

// Determine which page to display:
switch ($page_id) {
  case 'play-find':
    if ($_SESSION['from_page_id'] == 'play-list') {
      playerNew();
    } else {
      playerFind();
    }
    break;
  case 'play-list':
    $butt_att_updt .= " disabled";
    $butt_att_delt .= " disabled";
    playerList();
    break;
  case 'play-updt':
    if ($_SESSION['from_page_id'] == 'play-list') {
      playerNew();
    } else {
      playerUpdate();
    }
    break;
  case 'play-delt':
    $butt_att_updt .= " disabled";
    if ($_SESSION['from_page_id'] == 'play-list') {
      playerNew();
    } else {
//      echo "Player Delete not implemented yet.<br>";
      playerDelete();
    }
    break;
 
  // Default is a blank player form.
  default:
    playerNew();
    break;
   
} // End of main switch.

?> 

<!--  Player tab buttons  -->
    <input type="submit" id="find" name="p-find" value="Find"   <?php echo "$butt_att_find"; ?> >
    <input type="submit" id="updt" name="p-updt" value="Update" <?php echo "$butt_att_updt"; ?> >
    <input type="submit" id="list" name="p-list" value="List"   <?php echo "$butt_att_list"; ?> >
    <input type="submit" id="delt" name="p-delt" value="Delete" <?php echo "$butt_att_delt"; ?> >
    <input type="submit" id="burp" name="burp" value="burp"     <?php echo "$butt_att_burp"; ?> >
  <br>
<?php

if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; }

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
* playerDelete()
* Purpose: Delete a player
*******************************************************************************/
function playerDelete() {
  # declare globals
  global $debug;

# initialize the player form
require(BASE_URI . "modules/player/player.form.init.php");

  if ($debug) { echo "plyr:plyrDelete.<br>"; }

  # Get the next available player id number
#  $plyr->get_next_id();
  $error_msgs['errorDiv'] = "Delete player not yet implemented.";

  if ($debug) { echo "plyr:plyrDelete:end={$plyr->get_member_id()}.<br>"; }

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


  $players = new PlayerArray;
  if ($debug) { echo "player:List count={$players->playerCount}:" . count($players->playerList) . ".<br>"; }
#  $players->listing();
#  $players->sortNick();
  usort($players->playerList, array('PlayerArray','sortNick')); 

  $players->listing();
  usort($players->playerList, array('PlayerArray','sortScore')); 

require(BASE_URI . "modules/player/player.list.form.php");

}

//******************************************************************************
// End of player.inc.php
//******************************************************************************
