<?php # player.inc.php
/**
 * Do Player actions.
 * File name: player.inc.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-04-02 Updated with Player::constants.  DHD
 * 14-03-23 Added prev/next buttons.  Added dbg().  DHD
 * 14-03-20 Updated for phpDoc.  DHD
 * 14-03-19 Added button attributes.  DHD
 * 14-03-18 Removed playerListDeprecated.  Stubbed playerDelete().  DHD
 * 14-03-09 Original.  DHD
 * Future:
 * Add playerDelete
 * After list, Find & Delete buttons show blank form, Update button goes to playerNew.
 */

dbg("+".basename(__FILE__) . ":$page_id");
//post_dump();
//session_dump();
require(BASE_URI . "modules/player/player.update.inc.php");
$butt_att_prev = "";
$butt_att_find = "";
$butt_att_next = "";
$butt_att_updt = "";
$butt_att_list = "";
$butt_att_delt = "";
$butt_att_burp = "";
//echo "{$_POST['from_page_id']}:$page_id<br>";
// Determine which page to display:
switch ($page_id) {
    case 'play-prev':
        if ($_POST['from_page_id'] == 'play-list') {
            # find first game
            $_POST['member_id'] = 0;
            playerFind("next");
        } else {
            playerFind("prev");
        }
        break;
    case 'play-find':
        if ($_POST['from_page_id'] == 'play-list') {
            # find first game
            $_POST['member_id'] = 9999;
            playerFind("prev");
        } else {
            playerFind("");
        }
        break;
    case 'play-next':
        if ($_POST['from_page_id'] == 'play-list') {
            # find first game
            $_POST['member_id'] = 9999;
            playerFind("prev");
        } else {
            playerFind("next");
        }
        break;
    case 'play-list':
        $butt_att_updt .= " disabled";
        $butt_att_delt .= " disabled";
        playerList();
        break;
    case 'play-updt':
        if ($_POST['from_page_id'] == 'play-list') {
            playerNew();
        } else {
            playerUpdate();
        }
        break;
    case 'play-delt':
        $butt_att_updt .= " disabled";
        if ($_POST['from_page_id'] == 'play-list') {
            playerNew();
        } else {
            playerDelete();
        }
        break;
    case 'play-burp':
//        $butt_att_updt .= " disabled";
//        if ($_POST['from_page_id'] == 'play-list') {
            playerTest();
//        } else {
//            playerDelete();
//        }
        break;
  
    // Default is a blank player form.
    default:
        playerNew();
        break;
      
} // End of main switch.

?> 

<!--  Player tab buttons  -->
    <input type="submit" id="prev" name="p-prev" value="Previous" <?php echo "$butt_att_prev"; ?> >
    <input type="submit" id="find" name="p-find" value="Find"     <?php echo "$butt_att_find"; ?> >
    <input type="submit" id="next" name="p-next" value="Next"     <?php echo "$butt_att_next"; ?> >
    <input type="submit" id="updt" name="p-updt" value="Update"   <?php echo "$butt_att_updt"; ?> >
    <input type="submit" id="list" name="p-list" value="List"     <?php echo "$butt_att_list"; ?> >
    <input type="submit" id="delt" name="p-delt" value="Delete"   <?php echo "$butt_att_delt"; ?> >
    <input type="submit" id="burp" name="p-burp" value="burp"     <?php echo "$butt_att_burp"; ?> >
  <br>
<?php

/**
 * Set up a blank player form, ready for find or add
 */
function playerNew() {
    # declare globals
    global $debug;

# initialize the player form
require(BASE_URI . "modules/player/player.form.init.php");

    dbg("+".__FUNCTION__."");

    # Get the next available player id number
    $plyr->get_next_id();
    $error_msgs['errorDiv'] = "Add new player:";

    dbg("-".__FUNCTION__."={$plyr->get_member_id()}");

# Show the player form
require(BASE_URI . "modules/player/player.form.php");

}


/**
 * Delete a player
 */
function playerDelete() {
    # declare globals

# initialize the player form
require(BASE_URI . "modules/player/player.form.init.php");

    dbg("+".__FUNCTION__."");
    $plyr->set_to_POST();
    # Get the next available player id number
    try {
        $plyr->delete();
    }
    catch (PokerException $d) {
        switch ($d->getCode()) {
        case Player::DEL_ERR_ZERO:  # player not found
            $error_msgs['member_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See note below";
            $error_msgs['count'] += 1;
            break;
        default:
            echo "playerDelete failed:{$plyr->get_member_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "plyr Previous exception:plyr->get_member_id():" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }

    if ($error_msgs['count'] == 0) {
        $error_msgs['errorDiv'] = "Player deleted.";
    }

# Show the player form
require(BASE_URI . "modules/player/player.form.php");

    dbg("-".__FUNCTION__."={$plyr->get_member_id()};{$plyr->get_score()};{$plyr->get_stamp()};");

}


/**
 * Search for an existing player and display the results
 */
function playerFind($getType) {
    # declare globals
    dbg("+".__FUNCTION__.":$getType:{$_POST['member_id']}");
    #post_dump();

# initialize the player form
require(BASE_URI . "modules/player/player.form.init.php");
    $new_player = FALSE;

    # Look for player by id 
    $plyr->set_member_id($_POST['member_id']);
    try {
        $plyr->get("$getType");
    }
    catch (PokerException $d) {
        #echo "plyr get failed:{$plyr->get_member_id()}.<br>";
        switch ($d->getCode()) {
        case Player::GET_WARN_NO_SEAT:  # no seats rows
            $error_msgs['invite_cnt'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See note below";
//            $error_msgs['count'] += 1;
            break;
        case Player::GET_ERR_ZERO:  # no members rows
        case Player::GET_ERR_MULTI:  # multiple member rows
            $error_msgs['member_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See error(s) below";
            $error_msgs['count'] += 1;
            break;
        case Player::GET_INFO_ADD_NEW:  # No next ID found, add it.
            $error_msgs['errorDiv'] = "{$d->getMessage()} ({$d->getCode()})";
            $new_player = TRUE;
            break;
        default:
            echo "plyr find failed:plyr->get_member_id():" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "plyr Previous exception:plyr->get_member_id():" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }
    if ($error_msgs['count'] == 0 && !$new_player) {
        $error_msgs['errorDiv'] = "player found.";
    }

    dbg("-".__FUNCTION__."={$plyr->get_member_id()}:{$error_msgs['count']}:{$error_msgs['errorDiv']}");

# Show the player form
require(BASE_URI . "modules/player/player.form.php");

}

/**
 * Show some test data for add functions
 */
function playerTest() {
    global $debug, $plyr, $error_msgs;
    dbg("+".__FUNCTION__."");
    #post_dump();

# initialize the player form
require(BASE_URI . "modules/player/player.form.init.php");

    # create a test player
    $plyr->testMember();
    $error_msgs['errorDiv'] = "Test player created.  Press \"Add/Update\" to add the player.";

# Show the player form
require(BASE_URI . "modules/player/player.form.php");

    dbg("-".__FUNCTION__."={$plyr->get_member_id()}");

}


/**
 * Show a list of players.
 */
function playerList() {
    global $debug;

    $players = new PlayerArray;
    dbg("".__FUNCTION__.":count={$players->playerCount}:" . count($players->playerList) . "");
#  $players->listing();
#  $players->sortNick();
//    usort($players->playerList, array('PlayerArray','sortNick')); 

    $players->listing();
    usort($players->playerList, array('PlayerArray','sortScore')); 

require(BASE_URI . "modules/player/player.list.form.php");

}
dbg("-".basename(__FILE__)."");
//******************************************************************************
// End of player.inc.php
//******************************************************************************
