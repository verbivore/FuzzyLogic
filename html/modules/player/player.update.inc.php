<?php # player.inc.php
/******************************************************************************
 *  File name: player.update.inc.php
 *  Created by: David Demaree
 *  Contact: dave.demaree@yahoo.com
 *  Purpose: Add or update a Player.
 *** History ***  
 * 14-03-09 Original.  DHD
 * Future:
 *****************************************************************************/
if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
if ($debug) { echo "include file=player.update.inc.php:$page_id.<br>"; }
function playerUpdate() {
//******************************************************************************
// Add or Update                                                           
//******************************************************************************
  # declare globals
  global $debug, $plyr, $error_msgs;
  if ($debug) { echo "function:" . __FUNCTION__ . "={$_POST['member_id']}.<br>"; }
# initialize the player form
require(BASE_URI . "modules/player/player.form.init.php");
  $plyr->set_to_POST();   # initialize player with data from $_POST

  plyrValidate();
//  if ($debug) { echo "plyr:plyrUpdate={$plyr->get_member_id()}:{$error_msgs['count']}.<br>"; }
  if ($error_msgs['count'] == 0) {
    try {
      # is this an insert or an update?
      $row_count = $plyr->find();
      if($row_count == 0) {  
        if ($debug) { echo "plyr:plyrUpdate:inserting:{$plyr->get_member_id()}. <br>"; }
        $plyr->insert();
      } elseif($row_count == 1) {
        if ($debug) { echo "plyr:plyrUpdate:updating:{$plyr->get_member_id()}. <br>"; }
        $plyr->update();
      } else {
        $e = new Exception("Multiple ($row_count) player ({$plyr->get_member_id()}) records for effective date ({$plyr->get_eff_date()}).", 20000);
        throw new Exception($e);
      }
    }
    catch (playerException $d) {
      switch ($d->getCode()) {
/*      case 2110:
        $error_msgs['eff_date'] = "player ({$plyr->get_member_id()}) with this effective date already exists. ({$d->getCode()})";
        $error_msgs['errorDiv'] = "See errors below";
        $error_msgs['count'] += 1;
        break;
*/
      case 2104: # Column validation failed before insert/update
        $err_list = array();
        $err_list[] = array();
        $error_msgs['errorDiv'] = $d->getMessage() . " (2104)";
        $err_list = $d->getOptions();
        if ($debug) { echo "plyr:plyrUpdate arraysize="; echo sizeof($err_list); echo ".<br>"; }
        foreach ($err_list as $col => $val) {
//          echo "plyr.update errors=$col:$val[0]:$val[1].<br>";
          $error_msgs["$col"] = $val[1];
          $error_msgs['count'] += 1;
          if ($debug) { echo "plyr:plyrUpdate err col=$col:{$error_msgs["$col"]}.<br>"; }
/*
          $errMsgField="$col" . "ErrorMsg";
          ${$errMsgField} = $val[1];
          if ($debug) { echo "plyr:plyrUpdate errMsgField=$errMsgField:${$errMsgField}.<br>"; }
*/
        }
        break;
      default:
        echo "plyr insert/update failed:plyr->get_member_id():" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
        $p = new Exception($d->getPrevious());
        echo "plyr Previous exception:plyr->get_member_id():" . $p->getMessage() . ".<br>";
        throw new Exception($p);
      }
#      if ($d->getCode() > 0) {  # Assume that message is user-friendly
#      } else {  # Undefined error
    } 
  } 
  $_POST["ID"] = $plyr->get_member_id();
  if ($error_msgs['count'] == 0) {
    if($row_count == 0) {  
      $error_msgs['errorDiv'] = "player record added.";
    } else {
      $error_msgs['errorDiv'] = "player record updated.";
    }
  }

  if ($debug) { echo "plyr:plyrUpdate:end={$plyr->get_member_id()}.<br>"; }
# Future: Get player stats


# Show the player form
require(BASE_URI . "modules/player/player.form.php");

}


function plyrValidate() {
//******************************************************************************
// Validate player data                                                   
//******************************************************************************
  # declare globals
  global $debug, $plyr, $player_form_fields, $error_msgs;
  if ($debug) { echo "plyr:plyrValidate={$_POST['member_id']}.<br>"; }
#kluge!!!  Should be picked up in player.form.init.php!
$player_form_fields = array("member_id", "name_last", "name_first", "nickname");

    if ($debug) { echo "player_form_fields=";
      print_r($player_form_fields);
      echo ".<br>"; 
    }

    # validate fields
    foreach ($player_form_fields as $field) {
      try {
        $func = "validate_$field";
//        if ($debug) { echo "plyr:plyrUpdate:validate fields={$func}.<br>"; }
        $plyr->$func();
      }
      catch (playerException $e) {
        if ($debug) { echo "plyr:plyrValidate error={$e->getMessage()}.<br>"; }
        $error_msgs["$field"] = $e->getMessage();
        $error_msgs['count'] += 1;
//      $error_msgs['errorDiv'] = "See errors below";
      }
    }
#    session_dump();


  if ($debug) { echo "plyr:plyrValidate:end={$plyr->get_member_id()}:{$error_msgs['count']}.<br>"; }

}

if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; }
?>
