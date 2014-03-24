<?php # game.inc.php
/**
 * Do Game actions.
 * File name: game.inc.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-20 Cloned from Game.  DHD
 */

if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
if ($debug) { echo "include file=game.inc.php:$page_id.<br>"; }
post_dump();
session_dump();
require(BASE_URI . "modules/game/game.update.inc.php");
$butt_att_prev = "";
$butt_att_find = "";
$butt_att_next = "";
$butt_att_updt = "";
$butt_att_list = "";
$butt_att_delt = "";
$butt_att_burp = "";

// Determine which page to display:
switch ($page_id) {
    case 'game-prev':
        if ($_SESSION['from_page_id'] == 'game-list') {
            # find first game
            $_POST['game_id'] = 0;
            gameFind("next");
        } else {
            gameFind("prev");
        }
        break;
    case 'game-find':
        if ($_SESSION['from_page_id'] == 'game-list') {
            # find last game
            $_POST['game_id'] = 9999;
            gameFind("prev");
        } else {
            gameFind("");
        }
        break;
    case 'game-next':
        if ($_SESSION['from_page_id'] == 'game-list') {
            # find last game
            $_POST['game_id'] = 9999;
            gameFind("prev");
        } else {
            gameFind("next");
        }
        break;
    case 'game-list':
        $butt_att_updt .= " disabled";
        $butt_att_delt .= " disabled";
        gameList();
        break;
    case 'game-updt':
        if ($_SESSION['from_page_id'] == 'game-list') {
            gameNew();
        } else {
            gameUpdate();
        }
        break;
    case 'game-delt':
        $butt_att_updt .= " disabled";
        if ($_SESSION['from_page_id'] == 'game-list') {
            gameNew();
        } else {
            gameDelete();
        }
        break;
    case 'game-burp':
//        if ($_SESSION['from_page_id'] == 'game-list') {
        gameTest();
        break;
  
    // Default is a blank game form.
    default:
        gameNew();
        break;
      
} // End of main switch.

?> 

<!--  Game tab buttons  -->
    <input type="submit" id="prev" name="g-prev" value="Previous" <?php echo "$butt_att_prev"; ?> >
    <input type="submit" id="find" name="g-find" value="Find"     <?php echo "$butt_att_find"; ?> >
    <input type="submit" id="next" name="g-next" value="Next"     <?php echo "$butt_att_next"; ?> >
    <input type="submit" id="updt" name="g-updt" value="Update"   <?php echo "$butt_att_updt"; ?> >
    <input type="submit" id="list" name="g-list" value="List"     <?php echo "$butt_att_list"; ?> >
    <input type="submit" id="delt" name="g-delt" value="Delete"   <?php echo "$butt_att_delt"; ?> >
    <input type="submit" id="burp" name="g-burp" value="burp"     <?php echo "$butt_att_burp"; ?> >
  <br>
<?php

if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; }

/**
 * Set up a blank game form, ready for find or add
 */
function gameNew() {
    # declare globals
    global $debug;

# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");

    if ($debug) { echo "gameNew.<br>"; }

    # Get the next available game id number
    $gamz->getNew();
    $error_msgs['errorDiv'] = "Add new game:";

    if ($debug) { echo "gameNew:end={$gamz->get_game_id()}.<br>"; }

# Show the game form
require(BASE_URI . "modules/game/game.form.php");

}


/**
 * Delete a game
 */
function gameDelete() {
    # declare globals
    global $debug;

# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");

    if ($debug) { echo "gameDelete.<br>"; }
    $gamz->set_to_POST();
    # Get the next available game id number
    $gamz->delete();
//    $error_msgs['errorDiv'] = "Delete game not yet implemented.";

    if ($debug) { echo "gameDelete:end={$gamz->get_game_id()}.<br>"; }

    if ($error_msgs['count'] == 0) {
        $error_msgs['errorDiv'] = "game deleted.";
    }

# Show the game form
require(BASE_URI . "modules/game/game.form.php");

}


/**
 * Search for an existing game and display the results
 */
function gameFind($findType) {
    # declare globals
    global $debug, $gamz, $member_names, $error_msgs;
    if ($debug) { echo "game.inc:" . __FUNCTION__ . "vvv={$_POST['game_id']}.<br>"; }
    #post_dump();

# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");
    $newGame = FALSE;
    # Look for game by id 
    $gamz->set_game_id($_POST['game_id']);
    if ($debug) { echo "game.inc:" . __FUNCTION__ . ":{$gamz->get_game_id()}. <br>"; }
    try {
        $gamz->get($findType);
    } catch (gameException $d) {
        #echo "gamz get failed:{$gamz->get_game_id()}.<br>";
        switch ($d->getCode()) {
        case 32210:  # no games rows
        case 32212:  # no games rows
            $error_msgs['game_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See error(s) below";
            $error_msgs['count'] += 1;
            break;
        case 32211:  # multiple games rows
            $error_msgs['game_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See errors below";
            $error_msgs['count'] += 1;
            break;
        case 32213:  # new game option
#            $error_msgs['game_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "{$d->getMessage()} ({$d->getCode()})";
#            $error_msgs['count'] += 1;
            $newGame = TRUE;
            break;
        default:
            echo "game.inc:" . __FUNCTION__ . ":Exception:{$gamz->get_game_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "gameFind exception:{$gamz->get_game_id()}:" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }
    if ($error_msgs['count'] == 0 && !$newGame) {
        $error_msgs['errorDiv'] = "Game found.";
        gameGetNames();
    }

    if ($debug) { echo "game.inc:" . __FUNCTION__ . ":^^^={$gamz->get_game_id()}:{$error_msgs['count']}:{$error_msgs['errorDiv']}:{$member_names['snack']}.<br>"; }

# Show the game form
require(BASE_URI . "modules/game/game.form.php");

}

/**
 * Get player names for member_ids
 */
function gameGetNames() {
    # declare globals
    global $debug, $gamz, $member_names, $error_msgs;
    if ($debug) { echo "gameGetNames={$_POST['game_id']}.<br>"; }

    $mmbr = new Member();
    $mmbr->set_member_id($gamz->get_member_snack());
    if ($debug) { echo "gameGetNames snack:member={$mmbr->get_member_id()}.<br>"; }
    try {
        $mmbr->get("");
        $member_names['snack'] = $mmbr->get_full_name();
    } catch (Exception $d) {
        switch ($d->getCode()) {
        case 32210:  # no member rows
            $member_names['snack'] = null;
            break;
        default:
            echo "gameFind snack name failed:{$mmbr->get_member_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "previous message:" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }
    $mmbr->set_member_id($gamz->get_member_host());
    try {
        $mmbr->get("");
        $member_names['host'] = $mmbr->get_full_name();
    } catch (Exception $d) {
        switch ($d->getCode()) {
        case 32210:  # no member rows
            $member_names['host'] = null;
            break;
        default:
            echo "gameFind host name failed:{$mmbr->get_member_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "previous message:" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }
    $mmbr->set_member_id($gamz->get_member_gear());
    try {
        $mmbr->get("");
        $member_names['gear'] = $mmbr->get_full_name();
    } catch (Exception $d) {
        switch ($d->getCode()) {
        case 32210:  # no member rows
            $member_names['gear'] = null;
            break;
        default:
            echo "gameFind gear name failed:{$mmbr->get_member_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "previous message:" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }
    $mmbr->set_member_id($gamz->get_member_caller());
    try {
        $mmbr->get("");
        $member_names['caller'] = $mmbr->get_full_name();
    } catch (Exception $d) {
        switch ($d->getCode()) {
        case 32210:  # no member rows
            $member_names['caller'] = null;
            break;
        default:
            echo "gameFind caller name failed:{$mmbr->get_member_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "previous message:" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }

    if ($debug) { echo "game:" . __FUNCTION__ . ":end={$gamz->get_game_id()}:{$error_msgs['count']}:{$error_msgs['errorDiv']}:{$member_names['snack']}:{$member_names['host']}:{$member_names['gear']}:{$member_names['caller']}.<br>"; }

}

/**
 * Show some test data for add functions
 */
function gameTest() {
    global $debug, $gamz, $member_names, $error_msgs;
    if ($debug) { echo "gameTest.<br>"; }
    #post_dump();

# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");

    # create a test game
    $gamz->testGame();
    gameGetNames();
    $error_msgs['errorDiv'] = "Test game created.  Press \"Add/Update\" to add the game.";

# Show the game form
require(BASE_URI . "modules/game/game.form.php");

    if ($debug) { echo "game:" . __FUNCTION__ . ":end={$gamz->get_game_id()}.<br>"; }

}


/**
 * Show a list of games.
 */
function gameList() {
    global $debug;

    $games = new GameArray;
    if ($debug) { echo "game:List count={$games->gameCount}:" . count($games->gameList) . ".<br>"; }
#  $games->listing();
#  $games->sortNick();
//    usort($games->gameList, array('GameArray','sortNick')); 

    $games->listing();
    usort($games->gameList, array('GameArray','sortScore')); 

require(BASE_URI . "modules/game/game.list.form.php");

}

//******************************************************************************
// End of game.inc.php
//******************************************************************************
