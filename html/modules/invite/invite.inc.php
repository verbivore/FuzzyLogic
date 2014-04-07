<?php # invite.inc.php
/**
 * Do Game actions.
 * @author David Demaree <dave.demaree@yahoo.com>
 * File name: invite.inc.php
 *** History ***  
 * 14-04-06 Cloned from game.inc.php.  DHD
 */
dbg("+".basename(__FILE__).";$page_id");
//post_dump();
//session_dump();
//require(BASE_URI . "modules/invite/invite.update.inc.php");
$butt_att_prev = "";
$butt_att_find = "";
$butt_att_next = "";
$butt_att_updt = "";
$butt_att_list = "";
$butt_att_delt = "";
$butt_att_burp = "";

// Determine which page to display:
switch ($page_id) {
    case 'invite-prev':
        if ($_POST['from_page_id'] == 'invite-list') {
            # find first invite
            $_POST['invite_id'] = 0;
            inviteFind("next");
        } else {
            inviteFind("prev");
        }
        break;
    case 'invite-find':
        if ($_POST['from_page_id'] == 'invite-list') {
            # find last invite
            $_POST['invite_id'] = 9999;
            inviteFind("prev");
        } else {
            inviteFind("");
        }
        break;
    case 'invite-next':
        if ($_POST['from_page_id'] == 'invite-list') {
            # find last invite
            $_POST['invite_id'] = 9999;
            inviteFind("prev");
        } else {
            inviteFind("next");
        }
        break;
    case 'invite-list':
        $butt_att_updt .= " disabled";
        $butt_att_delt .= " disabled";
        inviteList();
        break;
    case 'invite-updt':
        if ($_POST['from_page_id'] == 'invite-list') {
            inviteNew();
        } else {
            inviteUpdate();
        }
        break;
    case 'invite-delt':
        $butt_att_updt .= " disabled";
        $butt_att_delt .= " disabled";
        if ($_POST['from_page_id'] == 'invite-list') {
            inviteNew();
        } else {
            inviteDelete();
        }
        break;
    case 'invite-burp':
        inviteTest();
        break;
  
    // Default is a blank invite form.
    default:
        inviteNew();
        break;
      
} // End of main switch.

?> 

<!--  Game tab buttons  -->
    <input type="submit" id="prev" name="i-prev" value="Previous" 
        <?php echo "$butt_att_prev"; ?> >
    <input type="submit" id="find" name="i-find" value="Find" 
        <?php echo "$butt_att_find"; ?> >
    <input type="submit" id="next" name="i-next" value="Next"
        <?php echo "$butt_att_next"; ?> >
    <input type="submit" id="updt" name="i-updt" value="Update"
        <?php echo "$butt_att_updt"; ?> >
    <input type="submit" id="list" name="i-list" value="List"
        <?php echo "$butt_att_list"; ?> >
    <input type="submit" id="delt" name="i-delt" value="Delete"
        <?php echo "$butt_att_delt"; ?> >
    <input type="submit" id="burp" name="i-burp" value="burp"
        <?php echo "$butt_att_burp"; ?> >
  <br>
<?php

/**
 * Set up a blank invite form, ready for find or add
 */
function inviteNew() {
    # declare globals
    global $page_id;
    dbg("+".__FUNCTION__.";inviteNew");

# initialize the invite form
require(BASE_URI . "modules/invite/invite.form.init.php");


    # Get the next available invite id number
    $gamz->getNew();
    $invite_error_msgs['errorDiv'] = "Add new invite:";

    dbg("=".__FUNCTION__."={$gamz->get_invite_id()}");

# Show the invite form
require(BASE_URI . "modules/invite/invite.form.php");
# Show the invite add form
#require(BASE_URI . "modules/invite/invite.form.add.php");

    dbg("-".__FUNCTION__.";inviteNew");
}

/**
 * Search for an existing invite and display the results
 */
function inviteFind($findType) {
    # declare globals
    global $page_id;
    global $gamz, $member_names, $invite_error_msgs;
    dbg("+".__FUNCTION__.";={$_POST['invite_id']}");
    #post_dump();

# initialize the invite form
require(BASE_URI . "modules/invite/invite.form.init.php");

    $newGame = FALSE;
    # Look for invite by id 
    $gamz->set_invite_id($_POST['invite_id']);
    dbg("=".__FUNCTION__.";={$gamz->get_invite_id()}");
    try {
        $gamz->get($findType);
    } catch (PokerException $d) {
        #echo "gamz get failed:{$gamz->get_invite_id()}.<br>";
        switch ($d->getCode()) {
        case Game::GET_ERR_ZERO:  # no invites rows
            $invite_error_msgs['invite_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $invite_error_msgs['errorDiv'] = "See error(s) below";
            $invite_error_msgs['count'] += 1;
            break;
        case Game::GET_ERR_MULTI:  # multiple invites rows
            $invite_error_msgs['invite_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $invite_error_msgs['errorDiv'] = "See errors below";
            $invite_error_msgs['count'] += 1;
            break;
        case Game::GET_WARN_NO_PREV:  # no previous invite found
            $invite_error_msgs['invite_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $invite_error_msgs['errorDiv'] = "See warning below";
            $invite_error_msgs['count'] += 1;
            break;
        case Game::GET_INFO_ADD_NEW:  # new invite option
            $invite_error_msgs['errorDiv'] = "{$d->getMessage()} ({$d->getCode()})";
            $newGame = TRUE;
            break;
        default:
            echo "invite.inc:" . __FUNCTION__ . ":Exception:{$gamz->get_invite_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "inviteFind exception:{$gamz->get_invite_id()}:" . $p->getMessage() . ".<br>";
            throw new PokerException($p);
        }
    }
    if ($invite_error_msgs['count'] == 0 && !$newGame) {
        $invite_error_msgs['errorDiv'] = "Game found.";
        inviteGetNames();
    }
    dbg("=".__FUNCTION__.";={$gamz->get_invite_id()}:{$invite_error_msgs['count']}:{$invite_error_msgs['errorDiv']}:{$member_names['snack']}:{$member_names['host']}:{$member_names['gear']}:{$member_names['caller']}");


# Show the invite form
require(BASE_URI . "modules/invite/invite.form.php");

    dbg("-".__FUNCTION__.";invite.inc:" . __FUNCTION__ . ":^^^={$gamz->get_invite_id()}:{$invite_error_msgs['count']}:{$invite_error_msgs['errorDiv']}:{$member_names['snack']}");
}

/**
 * Get a member names for all the bonus fields
 */
function inviteGetNames() {
    # declare globals
    global $gamz, $member_names, $invite_error_msgs;
    inviteGetName("snack");
    inviteGetName("host");
    inviteGetName("gear");
    inviteGetName("caller");
}

/**
 * Get a member name for one of the bonus fields
 */
function inviteGetName($field) {
    # declare globals
    global $gamz, $member_names, $invite_error_msgs;
    dbg("+".__FUNCTION__.";{$_POST['invite_id']}=$field");
    $mmbr = new Member();

    $func = "get_member_$field";
    $mmbr->set_member_id($gamz->$func());
    dbg("=".__FUNCTION__.";$field={$mmbr->get_member_id()}");
    try {
        $mmbr->get("");
        $member_names["$field"] = $mmbr->get_full_name();
    } catch (Exception $d) {
        switch ($d->getCode()) {
        case Member::GET_ERR_ZERO:  # no member rows
            $member_names["$field"] = null;
            break;
        default:
            echo __FUNCTION__.":Find $field name failed:{$mmbr->get_member_id()}:" . 
                $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "previous message:" . $p->getMessage() . ".<br>";
            dbg("-".__FUNCTION__.";{$_POST['invite_id']}=$field=");
            throw new Exception($p);
        }
    }
    dbg("-".__FUNCTION__.";{$_POST['invite_id']}=$field=".$member_names["$field"]);
}


/**
 * Delete a invite
 */
function inviteDelete() {
    # declare globals
    global $page_id;

# initialize the invite form
require(BASE_URI . "modules/invite/invite.form.init.php");

    dbg("=".__FUNCTION__.";inviteDelete");
    $gamz->set_to_POST();
    # Get the next available invite id number
    $gamz->delete();

    dbg("=".__FUNCTION__.";inviteDelete:end={$gamz->get_invite_id()}");

    if ($invite_error_msgs['count'] == 0) {
        $invite_error_msgs['errorDiv'] = "invite deleted.";
    }

# Show the invite form
require(BASE_URI . "modules/invite/invite.form.php");

}
/**
 * Show some test data for add functions
 */
function inviteTest() {
    # declare globals
    global $page_id;
    global $debug, $gamz, $member_names, $invite_error_msgs;
    dbg("=".__FUNCTION__.";inviteTest");
    #post_dump();

# initialize the invite form
require(BASE_URI . "modules/invite/invite.form.init.php");

    # create a test invite
    $gamz->testGame();
    inviteGetNames();
    $invite_error_msgs['errorDiv'] = "Test invite created.  Press \"Add/Update\" to add the invite.";

# Show the invite form
require(BASE_URI . "modules/invite/invite.form.php");

    dbg("=".__FUNCTION__.";invite:" . __FUNCTION__ . ":end={$gamz->get_invite_id()}");

}


/**
 * Show a list of invites.
 */
function inviteList() {
    global $debug;

    $invites = new GameArray;
    dbg("=".__FUNCTION__.";invite:List count={$invites->inviteCount}:" . count($invites->inviteList) . "");
#  $invites->listing();
#  $invites->sortNick();
//    usort($invites->inviteList, array('GameArray','sortNick')); 

    $invites->listing();
    usort($invites->inviteList, array('GameArray','sortDate')); 

require(BASE_URI . "modules/invite/invite.list.form.php");

}
dbg("-".basename(__FILE__).";$page_id");
//******************************************************************************
// End of invite.inc.php
//******************************************************************************
