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
dbg("+".basename(__FILE__).";");
function gameUpdate() {
/**
 * Add or Update                                                           
 */
    # declare globals
    global $debug, $gamz, $game_form_fields, $error_msgs, $member_names;
    dbg("=".__FUNCTION__.";function:" . __FUNCTION__ . "={$_POST['game_id']}");
# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");
    $gamz->set_to_POST();   # initialize game with data from $_POST

    gameValidate();
//  dbg("=".__FUNCTION__.";gameUpdate={$gamz->get_game_id()}:{$error_msgs['count']}");
    if ($error_msgs['count'] == 0) {
        try {
            # is this an insert or an update?
            $row_count = $gamz->find();
            if($row_count == 0) {  
                dbg("=".__FUNCTION__.";gameUpdate:inserting:{$gamz->get_game_id()}");
                $gamz->insert();
            } elseif($row_count == 1) {
                dbg("=".__FUNCTION__.";gameUpdate:updating:{$gamz->get_game_id()}");
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
                dbg("=".__FUNCTION__.";arraysize=".sizeof($err_list)."");
                foreach ($err_list as $col => $val) {
//          echo "gamz.update errors=$col:$val[0]:$val[1].<br>";
                    $error_msgs["$col"] = $val[1];
                    $error_msgs['count'] += 1;
                    dbg("=".__FUNCTION__.";gameUpdate err col=$col:{$error_msgs["$col"]}");
/*
                    $errMsgField="$col" . "ErrorMsg";
                    ${$errMsgField} = $val[1];
                    dbg("=".__FUNCTION__.";gameUpdate errMsgField=$errMsgField:${$errMsgField}");
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

    dbg("=".__FUNCTION__.";gameUpdate:end={$gamz->get_game_id()}");
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
    dbg("+".__FUNCTION__.";ID={$_POST['game_id']}");
    dbg("=".__FUNCTION__.";fields=" . sizeof($game_form_fields) . ":msgs=" . sizeof($error_msgs) . "");

#kluge!!!  Should be picked up in game.form.init.php!
#$game_form_fields = array("game_id", "name_last", "name_first", "nickname");

        dbg("=".__FUNCTION__.";game_form_fields=".print_r($game_form_fields)."");

        # validate fields
        foreach ($game_form_fields as $field) {
            try {
                $func = "validate_$field";
//              dbg("=".__FUNCTION__.";gameUpdate:validate fields={$func}");
                $gamz->$func();
            }
            catch (gameException $e) {
                dbg("=".__FUNCTION__.";error={$e->getMessage()}");
                $error_msgs["$field"] = $e->getMessage();
                $error_msgs['count'] += 1;
//              $error_msgs['errorDiv'] = "See errors below";
            }
        }
#    session_dump();


    dbg("-".__FUNCTION__.";={$gamz->get_game_id()}:{$error_msgs['count']}");

}

dbg("=".basename(__FILE__).";");
//******************************************************************************
// End of game.update.inc.php
//******************************************************************************
?>
