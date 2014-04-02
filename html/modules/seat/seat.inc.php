<?php # seat.inc.php
/**
 * Do Seat actions.
 * File name: seat.inc.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-27 Removed seatGetNames().  DHD
 * 14-03-23 Added dbg().  DHD
 * 14-03-20 Cloned from Game.  DHD
 */
dbg("+".basename(__FILE__).";$page_id");
//post_dump();
//session_dump();
require(BASE_URI . "modules/seat/seat.update.inc.php");
$butt_att_preg = "";
$butt_att_prep = "";
$butt_att_find = "";
$butt_att_nexg = "";
$butt_att_nexp = "";
$butt_att_updt = "";
$butt_att_list = "";
$butt_att_delt = "";
$butt_att_burp = "";

// Determine which page to display:
switch ($page_id) {
    case 'seat-preg':
        if ($_POST['from_page_id'] == 'seat-list') {
            # find first game first player
            $_POST['game_id'] = 0;
            seatFind("nexg");
        } else {
            seatFind("preg");
        }
        break;
    case 'seat-prep':
        if ($_POST['from_page_id'] == 'seat-list') {
            # find first game first player
            $_POST['game_id'] = 0;
            seatFind("nexg");
        } else {
            seatFind("prep");
        }
        break;
    case 'seat-find':
        if ($_POST['from_page_id'] == 'seat-list') {
            # find last game last player
            $_POST['game_id'] = 9999;
            seatFind("prep");
        } else {
            seatFind("");
        }
        break;
    case 'seat-nexg':
        if ($_POST['from_page_id'] == 'seat-list') {
            # find last game last player
            $_POST['game_id'] = 9999;
            seatFind("prep");
        } else {
            seatFind("nexg");
        }
        break;
    case 'seat-nexp':
        if ($_POST['from_page_id'] == 'seat-list') {
            # find last game last player
            $_POST['game_id'] = 9999;
            seatFind("prep");
        } else {
            seatFind("nexp");
        }
        break;
    case 'seat-list':
        $butt_att_updt .= " disabled";
        $butt_att_delt .= " disabled";
        seatList();
        break;
    case 'seat-updt':
        if ($_POST['from_page_id'] == 'seat-list') {
            seatNew();
        } else {
            seatUpdate();
        }
        break;
    case 'seat-delt':
        $butt_att_updt .= " disabled";
        if ($_POST['from_page_id'] == 'seat-list') {
            seatNew();
        } else {
            seatDelete();
        }
        break;
    case 'seat-burp':
//        if ($_POST['from_page_id'] == 'seat-list') {
        seatTest();
        break;
  
    // Default is a blank seat form.
    default:
        seatNew();
        break;
      
} // End of main switch.

?> 

<!--  Seat tab buttons  -->
    <input type="submit" id="preg" name="s-preg" value="Previous Game"   <?php echo "$butt_att_preg"; ?> >
    <input type="submit" id="prep" name="s-prep" value="Previous Player" <?php echo "$butt_att_prep"; ?> >
    <input type="submit" id="find" name="s-find" value="Find"            <?php echo "$butt_att_find"; ?> >
    <input type="submit" id="nexg" name="s-nexg" value="Next Game"       <?php echo "$butt_att_nexg"; ?> >
    <input type="submit" id="nexp" name="s-nexp" value="Next Player"     <?php echo "$butt_att_nexp"; ?> >
    <input type="submit" id="updt" name="s-updt" value="Update"          <?php echo "$butt_att_updt"; ?> >
    <input type="submit" id="list" name="s-list" value="List"            <?php echo "$butt_att_list"; ?> >
    <input type="submit" id="delt" name="s-delt" value="Delete"          <?php echo "$butt_att_delt"; ?> >
    <input type="submit" id="burp" name="s-burp" value="burp"            <?php echo "$butt_att_burp"; ?> >
  <br>
<?php

/**
 * Set up a blank seat form, ready for find or add
 */
function seatNew() {
# initialize the seat form
require(BASE_URI . "modules/seat/seat.form.init.php");

    dbg("+".__METHOD__."");

    # Get the next available seat id number
    $seaz->getNew();
    $error_msgs['errorDiv'] = "Add new seat:";


# Show the seat form
require(BASE_URI . "modules/seat/seat.form.php");

    dbg("-".__METHOD__."={$seaz->get_game_id()}");
}


/**
 * Delete a seat
 */
function seatDelete() {
    # declare globals

# initialize the seat form
require(BASE_URI . "modules/seat/seat.form.init.php");

    dbg("=".__METHOD__."");
    $seaz->set_to_POST();
    # Get the next available seat id number
    $seaz->delete();
//    $error_msgs['errorDiv'] = "Delete seat not yet implemented.";

    dbg("=".__METHOD__.";end={$seaz->get_game_id()}");

    if ($error_msgs['count'] == 0) {
        $error_msgs['errorDiv'] = "seat deleted.";
    }

# Show the seat form
require(BASE_URI . "modules/seat/seat.form.php");

}


/**
 * Search for an existing seat and display the results
 */
function seatFind($findType) {
    # declare globals
    global $seaz, $member_names, $error_msgs;
    dbg("+".__FUNCTION__.";$findType;{$_POST['game_id']};{$_POST['member_id']}");
    #post_dump();

# initialize the seat form
require(BASE_URI . "modules/seat/seat.form.init.php");
    $newSeat = FALSE;
    # Look for seat by id 
    $seaz->set_game_id($_POST['game_id']);
    $seaz->set_member_id($_POST['member_id']);
    dbg("=".__FUNCTION__.";$findType;{$seaz->get_game_id()};".Seat::GET_ERR_ZERO);
    try {
        $seaz->get($findType);
    } catch (PokerException $d) {
        #echo "seaz get failed:{$seaz->get_game_id()}.<br>";
        switch ($d->getCode()) {
        case Seat::GET_ERR_ZERO:  # no rows retrieved
            $error_msgs['errorDiv'] = "See error(s) below";
            $error_msgs['count'] += 1;
            switch ($findType) {
            case 'preg': 
                $error_msgs['game_id'] = "Previous game not found.";
                break;
            case 'prep': 
                $error_msgs['member_id'] = "Previous player for game {$seaz->get_game_id()} not found.";
                break;
            case 'nexg':  
                $error_msgs['game_id'] = "Next game not found.";
                break;
            case 'nexp':  
                $error_msgs['member_id'] = "Next player for game {$seaz->get_game_id()} not found.";
                break;
            default:  
                $error_msgs['game_id'] = "Seat not found for this game and player.";
                break;
            }
            break;
        case Seat::ERR_GET_MULTI:  # multiple seats rows
            $error_msgs['game_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See errors below";
            $error_msgs['count'] += 1;
            break;
        case Seat::GET_INFO_ADD_NEW:  # new seat option
            $error_msgs['errorDiv'] = "{$d->getMessage()} ({$d->getCode()})";
            $newSeat = TRUE;
            break;
        default:
            echo "seat.inc:" . __FUNCTION__ . ":Exception:{$seaz->get_game_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "seatFind exception:{$seaz->get_game_id()}:" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }

    dbg("-".__FUNCTION__."={$seaz->get_game_id()}:{$error_msgs['count']}:{$error_msgs['errorDiv']}");

# Show the seat form
require(BASE_URI . "modules/seat/seat.form.php");

}


/**
 * Show some test data for add functions
 */
function seatTest() {
    global $debug, $seaz, $member_names, $error_msgs;
    dbg("+".__METHOD__."seatTest");
    #post_dump();

# initialize the seat form
require(BASE_URI . "modules/seat/seat.form.init.php");

    # create a test seat
    try {
        $seaz->testSeat();
        $error_msgs['errorDiv'] = "Test seat created.  Press \"Add/Update\" to add the seat.";
    } catch (PokerException $e) {
        $error_msgs['errorDiv'] = "Test seat creation failed";
        $error_msgs['count'] += 1;
    }



# Show the seat form
require(BASE_URI . "modules/seat/seat.form.php");

    dbg("-".__METHOD__.";end={$seaz->get_game_id()}");

}


/**
 * Show a list of seats.
 */
function seatList($gameId=0) {
    dbg("+".__METHOD__.";$gameId");

    $seats = new SeatArray($gameId);
    dbg("=".__METHOD__.";count={$seats->seatCount}:" . count($seats->seatList) . "");
#  $seats->listing();
#  $seats->sortNick();
//    usort($seats->seatList, array('SeatArray','sortNick')); 

    $seats->listing();
    usort($seats->seatList, array('SeatArray','sortMember')); 

require(BASE_URI . "modules/seat/seat.list.form.php");

    dbg("-".__METHOD__.";count={$seats->seatCount}:" . count($seats->seatList) . "");
}
dbg("-".basename(__FILE__).";$page_id");
//******************************************************************************
// End of seat.inc.php
//******************************************************************************
