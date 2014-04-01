<?php # game.inc.php
/**
 *  Add or update a Game.
 *  File name: game.update.inc.php
 *  @author David Demaree <dave.demaree@yahoo.com>
  *** History ***  
 * 14-03-23 Added dbg() function.  DHD
 * 14-03-20 Updated for phpDoc.  DHD
 * 14-03-09 Original.  DHD
 * Future:
 */
dbg("+".basename(__FILE__).";");

/**
 * Add or Update                                                           
 */
function gameUpdate() {
#post_dump();
    # declare globals
    global $page_id;
    global $gamz, $game_form_fields, $game_error_msgs, $player_error_msgs, $member_names;
    dbg("+".__FUNCTION__."={$_POST['game_id']}:{$_POST['player_count']}");
# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");
    $gamz->set_to_POST();   # initialize game with data from $_POST

    gameValidate();
//  dbg("=".__FUNCTION__.";gameUpdate={$gamz->get_game_id()}:{$game_error_msgs['count']}");
    if ($game_error_msgs['count'] == 0) {
        try {
            # is this an insert or an update?
            $row_count = $gamz->find();
            if($row_count == 0) {  
                dbg("=".__FUNCTION__.";inserting:{$gamz->get_game_id()}");
                $gamz->insert();
            } elseif($row_count == 1) {
                dbg("=".__FUNCTION__.";updating:{$gamz->get_game_id()}");
                $gamz->update();
            } else {
                $e = new Exception("Multiple ($row_count) game records for game ({$gamz->get_game_id()}).", 30000);
                throw new Exception($e);
            }
        } catch (gameException $d) {
            switch ($d->getCode()) {
            case 32010:
                $game_error_msgs['game_id'] = "Game ({$gamz->get_geme_id()}) not found. ({$d->getCode()})";
                $game_error_msgs['errorDiv'] = "See errors below";
                $game_error_msgs['count'] += 1;
                break;
            case 32110:
                $game_error_msgs['nickname'] = "Game with this nickname ({$gamz->get_nickname()}) already exists. ({$d->getCode()})";
                $game_error_msgs['errorDiv'] = "See errors below";
                $game_error_msgs['count'] += 1;
                break;
            case 32104: # Column validation failed before insert/update
                $err_list = array();
                $err_list[] = array();
                $game_error_msgs['errorDiv'] = $d->getMessage() . " (32104)";
                $err_list = $d->getOptions();
                dbg("=".__FUNCTION__.";arraysize=".sizeof($err_list)."");
                foreach ($err_list as $col => $val) {
//          echo "gamz.update errors=$col:$val[0]:$val[1].<br>";
                    $game_error_msgs["$col"] = $val[1];
                    $game_error_msgs['count'] += 1;
                    dbg("=".__FUNCTION__.";gameUpdate err col=$col:{$game_error_msgs["$col"]}");
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
    if ($game_error_msgs['count'] == 0) {
        if($row_count == 0) {  
            $game_error_msgs['errorDiv'] = "game record added.";
        } else {
            $game_error_msgs['errorDiv'] = "game record updated.";
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

    dbg("=".__FUNCTION__."={$gamz->get_game_id()}");
# Future: Get game stats
    gameUpdateSeats();
    dbg("-".__FUNCTION__."={$gamz->get_game_id()}");
# Show the game form
require(BASE_URI . "modules/game/game.form.php");
}

 

/**
 * Add or Update seats
 */
function gameUpdateSeats() {
//post_dump();
    # declare globals
    global $gamz, $game_form_fields, $game_error_msgs, $player_error_msgs;
    dbg("+".__FUNCTION__."={$_POST['game_id']}:{$_POST['player_count']}");
    $player_count = $_POST['player_count'];
    dbg("=".__FUNCTION__."={$player_count}");
    # Scan POST array for invited players to add or update
    for ($i = 0; $i < $player_count; $i++) {
        $invited = FALSE;
        $new_player = FALSE;
        # initialize seat with game_id and member_id
        $invitee = new Seat;
        $invitee->set_game_id($gamz->get_game_id());
        $mbr_id_idx = "mbr_id_row_$i";
        $member_id = $_POST["{$mbr_id_idx}"];
        $invitee->set_member_id($member_id);
        dbg("=".__FUNCTION__.";mbr idx={$mbr_id_idx}=$member_id");
        # Scan checkboxes for new invitees
        $invite_idx = "invite_$i";
        if (isset($_POST["{$invite_idx}"])) {
            $invited = TRUE;
            $new_player = TRUE;
//            $member_id = $_POST["{$invite_idx}"];
            dbg("=".__FUNCTION__.";add={$member_id}");
            $response_idx = "response_$i";
            $invitee->set_response($_POST["$response_idx"]);
            $note_mst_idx = "note_mst_$i";
            $invitee->set_note_master($_POST["$note_mst_idx"]);
            $note_mbr_idx = "note_mbr_$i";
            $invitee->set_note_member($_POST["$note_mbr_idx"]);
#            $invitee->set_stamp;
#           $invitee->listRow();
            $invitee->insert();
        } else {
            # is this member invited? # find seat
            try {
                $invitee->get("");
                # update seat
                dbg("=".__FUNCTION__.";add={$member_id}");
                $response_idx = "response_$i";
                $invitee->set_response($_POST["$response_idx"]);
                $note_mst_idx = "note_mst_$i";
                $invitee->set_note_master($_POST["$note_mst_idx"]);
                $note_mbr_idx = "note_mbr_$i";
                $invitee->set_note_member($_POST["$note_mbr_idx"]);
#                $invitee->set_stamp;
#                try {
                    $invitee->update();
#                } catch (PokerException $e) {
#                    switch ($e->getCode()) {
#                    case Seat::ERR_GET_ZERO:
#                        break;    
#                    }
#                }
            } catch (PokerException $e) {
                switch ($e->getCode()) {
                case Seat::GET_ERR_ZERO:  # No row for this seat yet
                    break;    
                case Seat::UPD_ERR_VALIDTN:  # Validation errors
                    $msg_array = $e->GetOptions();
                    $player_error_msgs['errorDiv'] = "Data validation errors";
                    $player_error_msgs['count']++;
                    $player_error_msgs["$i"] = $msg_array['response'][1] . " (" . $msg_array['response'][0] . ")";
#                    $player_error_msgs["$i"] = "player validatioin err:" . print_r($e->GetOptions(), 1);
                    break;    

                default:
                    throw $e;
                    break;    
                }
            }
        }
        unset($invitee);
    }
    dbg("-".__FUNCTION__."={$gamz->get_game_id()}");
}


function gameValidate() {
/**
 * Validate game data                                                   
 */
    # declare globals
    global $gamz, $game_form_fields, $game_error_msgs, $player_error_msgs;
    dbg("+".__FUNCTION__.";ID={$_POST['game_id']}");
    dbg("=".__FUNCTION__.";fields=" . sizeof($game_form_fields) . ":msgs=" . sizeof($game_error_msgs) . "");

#kluge!!!  Should be picked up in game.form.init.php!
#$game_form_fields = array("game_id", "name_last", "name_first", "nickname");

//        dbg("=".__FUNCTION__.";game_form_fields=".print_r($game_form_fields)."");

        # validate fields
        foreach ($game_form_fields as $field) {
            try {
                $func = "validate_$field";
//              dbg("=".__FUNCTION__.";gameUpdate:validate fields={$func}");
                $gamz->$func();
            }
            catch (gameException $e) {
                dbg("=".__FUNCTION__.";error={$e->getMessage()}");
                $game_error_msgs["$field"] = $e->getMessage();
                $game_error_msgs['count'] += 1;
//              $game_error_msgs['errorDiv'] = "See errors below";
            }
        }
#    session_dump();


    dbg("-".__FUNCTION__.";={$gamz->get_game_id()}:{$game_error_msgs['count']}");

}

dbg("-".basename(__FILE__).";");
//******************************************************************************
// End of game.update.inc.php
//******************************************************************************
?>
