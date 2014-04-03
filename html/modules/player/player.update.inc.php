<?php # player.inc.php
/**
 *  Add or update a Player.
 *  File name: player.update.inc.php
 *  @author David Demaree <dave.demaree@yahoo.com>
  *** History ***  
 * 14-04-02 Updated with Player::constants.  DHD
 * 14-03-20 Updated for phpDoc.  DHD
 * 14-03-09 Original.  DHD
 * Future:
 *  timestamp =now, !=0
 */
dbg("+".basename(__FILE__).";$page_id");
function playerUpdate() {
/**
 * Add or Update                                                           
 */
    # declare globals
    global $plyr, $error_msgs, $player_form_fields;
    dbg("+".__FUNCTION__."={$_POST['member_id']}");
# initialize the player form
require(BASE_URI . "modules/player/player.form.init.php");
    $plyr->set_to_POST();   # initialize player with data from $_POST

    plyrValidate();
//  dbg("=".__FUNCTION__."={$plyr->get_member_id()}:{$error_msgs['count']}");
    if ($error_msgs['count'] == 0) {
        try {
            # is this an insert or an update?
            $row_count = $plyr->find();
            if($row_count == 0) {  
                dbg("=".__FUNCTION__.":inserting:{$plyr->get_member_id()}");
                $plyr->insert();
            } elseif($row_count == 1) {
                dbg("=".__FUNCTION__.":updating:{$plyr->get_member_id()}");
                $plyr->update();
            } else {
                # Future:  Log the error somehow
                $error_msgs['member_id'] = "Multiple rows found for player ({$plyr->get_member_id()})";
                $error_msgs['errorDiv'] = "See errors below";
                $error_msgs['count'] += 1;
            }
        }
        catch (PokerException $d) {
            switch ($d->getCode()) {
            case Player::INS_ERR_VALIDTN: # Column validation failed before insert
            case Player::UPD_ERR_VALIDTN: # Column validation failed before update
                $err_list = array();
                $err_list[] = array();
                $error_msgs['errorDiv'] = $d->getMessage() . " (2104)";
                $err_list = $d->getOptions();
                dbg("=".__FUNCTION__.":arraysize=" . sizeof($err_list));
                foreach ($err_list as $col => $val) {
                    $error_msgs["$col"] = $val[1];
                    $error_msgs['count'] += 1;
                    dbg("=".__FUNCTION__.":err col=$col:{$error_msgs["$col"]}");
                }
                break;
            case Player::INS_ERR_DUP:
            case Player::UPD_ERR_DUP:
                $error_msgs['nickname'] = "Player with this nickname ({$plyr->get_nickname()}) already exists. ({$d->getCode()})";
                $error_msgs['errorDiv'] = "See errors below";
                $error_msgs['count'] += 1;
                break;
            default:
                # Future:  Make a user-friendly error
                dbg("-".__FUNCTION__."={$plyr->get_member_id()}:exception");
                throw new PokerException('Insert/update failed for player:' . $plyr->get_member_id(), 
                                         self::UPD_ERR, 
                                         $e);
            }
        } 
    } 
//    $_POST["ID"] = $plyr->get_member_id();
    if ($error_msgs['count'] == 0) {
        if($row_count == 0) {  
            $error_msgs['errorDiv'] = "player record added.";
        } else {
            $error_msgs['errorDiv'] = "player record updated.";
        }
    }

# Future: Get player stats


# Show the player form
require(BASE_URI . "modules/player/player.form.php");
    dbg("-".__FUNCTION__.":end={$plyr->get_member_id()}");
}


function plyrValidate() {
/**
 * Validate player data                                                   
 */
    # declare globals
    global $plyr, $player_form_fields, $error_msgs;
    dbg("+".__FUNCTION__.":{$_POST['member_id']}");
#kluge!!!  Should be picked up in player.form.init.php!
#$player_form_fields = array("member_id", "name_last", "name_first", "nickname");

//        dbg("=".__FUNCTION__.":player_form_fields=" . print_r($player_form_fields));
        # validate fields
        foreach ($player_form_fields as $field) {
            try {
                $func = "plup_validate_$field";
//        dbg("=".__FUNCTION__.":plyr:plyrUpdate:validate fields={$func}");
                $func();
            }
            catch (PokerException $e) {
                dbg("=".__FUNCTION__.":validation error={$e->getMessage()}");
                $error_msgs["$field"] = $e->getMessage();
                $error_msgs['count'] += 1;
//                $error_msgs['errorDiv'] = "See errors below";
            }
        }
#    session_dump();

    dbg("-".__FUNCTION__."={$plyr->get_member_id()}:{$error_msgs['count']}");
}

# Future: Flesh out these validation functions
function plup_validate_member_id() { return TRUE; }
function plup_validate_name_last() { return TRUE; }
function plup_validate_name_first() { return TRUE; }
function plup_validate_nickname() { return TRUE; }
function plup_validate_status() { return TRUE; }
function plup_validate_email() { return TRUE; }
function plup_validate_phone() { return TRUE; }
function plup_validate_invite_cnt() { return TRUE; }
function plup_validate_yes_cnt() { return TRUE; }
function plup_validate_maybe_cnt() { return TRUE; }
function plup_validate_no_cnt() { return TRUE; }
function plup_validate_flake_cnt() { return TRUE; }
function plup_validate_score() { return TRUE; }
function plup_validate_stamp() { return TRUE; }

dbg("-".basename(__FILE__)."");
//******************************************************************************
// End of player.update.inc.php
//******************************************************************************
?>
