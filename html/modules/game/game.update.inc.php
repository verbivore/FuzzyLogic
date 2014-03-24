<?php # game.inc.php
/**
 *  Add or update a Game.
 *  File name: game.update.inc.php
 *  @author David Demaree <dave.demaree@yahoo.com>
  *** History ***  
 * 14-03-20 Updated for phpDoc.  DHD
 * 14-03-09 Original.  DHD
 * Future:
 */
if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
if ($debug) { echo "include file=game.update.inc.php:$page_id.<br>"; }
function gameUpdate() {
/**
 * Add or Update                                                           
 */
    # declare globals
    global $debug, $gamz, $game_form_fields, $error_msgs, $member_names;
    if ($debug) { echo "function:" . __FUNCTION__ . "={$_POST['game_id']}.<br>"; }
# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");
    $gamz->set_to_POST();   # initialize game with data from $_POST

    gameValidate();
//  if ($debug) { echo "gameUpdate={$gamz->get_game_id()}:{$error_msgs['count']}.<br>"; }
    if ($error_msgs['count'] == 0) {
        try {
            # is this an insert or an update?
            $row_count = $gamz->find();
            if($row_count == 0) {  
                if ($debug) { echo "gameUpdate:inserting:{$gamz->get_game_id()}. <br>"; }
                $gamz->insert();
            } elseif($row_count == 1) {
                if ($debug) { echo "gameUpdate:updating:{$gamz->get_game_id()}. <br>"; }
                $gamz->update();
            } else {
                $e = new Exception("Multiple ($row_count) game records for game ({$gamz->get_game_id()}).", 20000);
                throw new Exception($e);
            }
        } catch (gameException $d) {
            switch ($d->getCode()) {
            case 2110:
                $error_msgs['nickname'] = "Game with this nickname ({$gamz->get_nickname()}) already exists. ({$d->getCode()})";
                $error_msgs['errorDiv'] = "See errors below";
                $error_msgs['count'] += 1;
                break;
            case 2104: # Column validation failed before insert/update
                $err_list = array();
                $err_list[] = array();
                $error_msgs['errorDiv'] = $d->getMessage() . " (2104)";
                $err_list = $d->getOptions();
                if ($debug) { echo "gameUpdate arraysize="; echo sizeof($err_list); echo ".<br>"; }
                foreach ($err_list as $col => $val) {
//          echo "gamz.update errors=$col:$val[0]:$val[1].<br>";
                    $error_msgs["$col"] = $val[1];
                    $error_msgs['count'] += 1;
                    if ($debug) { echo "gameUpdate err col=$col:{$error_msgs["$col"]}.<br>"; }
/*
                    $errMsgField="$col" . "ErrorMsg";
                    ${$errMsgField} = $val[1];
                    if ($debug) { echo "gameUpdate errMsgField=$errMsgField:${$errMsgField}.<br>"; }
*/
                }
                break;
            default:
                echo "Game insert/update failed:{$gamz->get_game_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
                $p = new Exception($d->getPrevious());
                echo "Game Previous exception:{$gamz->get_game_id()}:" . $p->getMessage() . ".<br>";
                throw new Exception($p);
            }
            
#      if ($d->getCode() > 0) {  # Assume that message is user-friendly
#      } else {  # Undefined error
        } 
    } 
    $_POST["ID"] = $gamz->get_game_id();
    if ($error_msgs['count'] == 0) {
        if($row_count == 0) {  
            $error_msgs['errorDiv'] = "game record added.";
        } else {
            $error_msgs['errorDiv'] = "game record updated.";
        }
        # get the new row to verify and get stamp
        try {
            $gamz->get("");
            gameGetNames();
        } catch (gameException $d) {
            echo "Game insert/update failed:{$gamz->get_game_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "Game Previous exception:{$gamz->get_game_id()}:" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }

    if ($debug) { echo "gameUpdate:end={$gamz->get_game_id()}.<br>"; }
# Future: Get game stats


# Show the game form
require(BASE_URI . "modules/game/game.form.php");

}


function gameValidate() {
/**
 * Validate game data                                                   
 */
    # declare globals
    global $debug, $gamz, $game_form_fields, $error_msgs;
    if ($debug) { echo "gameValidate={$_POST['game_id']}.<br>"; }
    if ($debug) { echo "gameValidate:fields=" . sizeof($game_form_fields) . ":msgs=" . sizeof($error_msgs) . ".<br>"; }

#kluge!!!  Should be picked up in game.form.init.php!
#$game_form_fields = array("game_id", "name_last", "name_first", "nickname");

        if ($debug) { echo "game_form_fields=";
            print_r($game_form_fields);
            echo ".<br>"; 
        }

        # validate fields
        foreach ($game_form_fields as $field) {
            try {
                $func = "validate_$field";
//              if ($debug) { echo "gameUpdate:validate fields={$func}.<br>"; }
                $gamz->$func();
            }
            catch (gameException $e) {
                if ($debug) { echo "gamz:gameValidate error={$e->getMessage()}.<br>"; }
                $error_msgs["$field"] = $e->getMessage();
                $error_msgs['count'] += 1;
//              $error_msgs['errorDiv'] = "See errors below";
            }
        }
#    session_dump();


    if ($debug) { echo "gameValidate:end={$gamz->get_game_id()}:{$error_msgs['count']}.<br>"; }

}

if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; }
//******************************************************************************
// End of game.update.inc.php
//******************************************************************************
?>
