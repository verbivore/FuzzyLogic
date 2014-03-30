<?php # seat.inc.php
/**
 *  Add or update a Game.
 *  File name: seat.update.inc.php
 *  @author David Demaree <dave.demaree@yahoo.com>
  *** History ***  
 * 14-03-23 Added dbg() function.  DHD
 * 14-03-20 Updated for phpDoc.  DHD
 * 14-03-09 Original.  DHD
 * Future:
 */
dbg("+".basename(__FILE__).";");
function seatUpdate() {
/**
 * Add or Update                                                           
 */
    # declare globals
    global $seaz, $seat_form_fields, $error_msgs, $member_names;
    dbg("+".__FUNCTION__."={$_POST['game_id']}={$_POST['member_id']}");
# initialize the seat form
require(BASE_URI . "modules/seat/seat.form.init.php");
    $seaz->set_to_POST();   # initialize seat with data from $_POST

    seatValidate();
//  dbg("=".__FUNCTION__.";seatUpdate={$seaz->get_game_id()}:{$error_msgs['count']}");
    if ($error_msgs['count'] == 0) {
        try {
            # is this an insert or an update?
            $row_count = $seaz->find();
            if($row_count == 0) {  
                dbg("=".__FUNCTION__.";seatUpdate:inserting:{$seaz->get_game_id()}");
                $seaz->insert();
            } elseif($row_count == 1) {
                dbg("=".__FUNCTION__.";seatUpdate:updating:{$seaz->get_game_id()}");
                $seaz->update();
            } else {
                $e = new Exception("Multiple ($row_count) seat records for seat ({$seaz->get_game_id()}).", 20000);
                throw new Exception($e);
            }
        } catch (PokerException $e) {
            switch ($e->getCode()) {
            case Seat::INS_ERR_VALIDTN: # Column validation failed before insert/update
            case Seat::UPD_ERR_VALIDTN:
                $err_list = array();
                $err_list[] = array();
                $error_msgs['errorDiv'] = $e->getMessage() . " (2104)";
                $err_list = $e->getOptions();
                dbg("=".__FUNCTION__.";arraysize=".sizeof($err_list)."");
                foreach ($err_list as $col => $val) {
//          echo "seaz.update errors=$col:$val[0]:$val[1].<br>";
                    $error_msgs["$col"] = $val[1];
                    $error_msgs['count'] += 1;
                    dbg("=".__FUNCTION__.";seatUpdate err col=$col:{$error_msgs["$col"]}");
/*
                    $errMsgField="$col" . "ErrorMsg";
                    ${$errMsgField} = $val[1];
                    dbg("=".__FUNCTION__.";seatUpdate errMsgField=$errMsgField:${$errMsgField}");
*/
                }
                break;
            default:
                echo "Game insert/update failed:{$seaz->get_game_id()}:" . $e->getMessage() . ":" . $e->getCode() . ".<br>";
                $p = new Exception($e->getPrevious());
                echo "Game Previous exception:{$seaz->get_game_id()}:" . $p->getMessage() . ".<br>";
                throw new Exception($p);
            }
            
#      if ($e->getCode() > 0) {  # Assume that message is user-friendly
#      } else {  # Undefined error
        } 
    } 
    $_POST["ID"] = $seaz->get_game_id();
    if ($error_msgs['count'] == 0) {
        if($row_count == 0) {  
            $error_msgs['errorDiv'] = "seat record added.";
        } else {
            $error_msgs['errorDiv'] = "seat record updated.";
        }
        # get the new row to verify and get stamp
        try {
            $seaz->get("");
//            seatGetNames();
        } catch (PokerException $e) {
            echo "Game insert/update failed:{$seaz->get_game_id()}:" . $e->getMessage() . ":" . $e->getCode() . ".<br>";
            $p = new Exception($e->getPrevious());
            echo "Game Previous exception:{$seaz->get_game_id()}:" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }

    dbg("-".__FUNCTION__.";{$seaz->get_game_id()}");
# Future: Get seat stats


# Show the seat form
require(BASE_URI . "modules/seat/seat.form.php");

}


function seatValidate() {
/**
 * Validate seat data                                                   
 */
    # declare globals
    global $seaz, $seat_form_fields, $error_msgs;
    dbg("+".__FUNCTION__.";ID={$_POST['game_id']}");
    dbg("=".__FUNCTION__.";fields=" . sizeof($seat_form_fields) . ":msgs=" . sizeof($error_msgs) . "");

#kluge!!!  Should be picked up in seat.form.init.php!
#$seat_form_fields = array("game_id", "name_last", "name_first", "nickname");

        dbg("=".__FUNCTION__.";seat_form_fields=");
//print_r($seat_form_fields).
        # validate fields
        foreach ($seat_form_fields as $field) {
            try {
                $func = "validate_$field";
//              dbg("=".__FUNCTION__.";fields={$func}");
                $seaz->$func();
            }
            catch (PokerException $e) {
                dbg("=".__FUNCTION__.";error={$e->getMessage()}");
                $error_msgs["$field"] = $e->getMessage();
                $error_msgs['count'] += 1;
//              $error_msgs['errorDiv'] = "See errors below";
            }
        }
#    session_dump();


    dbg("-".__FUNCTION__.";={$seaz->get_game_id()}:{$error_msgs['count']}");

}

dbg("-".basename(__FILE__).";");
//******************************************************************************
// End of seat.update.inc.php
//******************************************************************************
?>
