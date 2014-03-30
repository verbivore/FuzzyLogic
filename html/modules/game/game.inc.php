<?php # game.inc.php
/**
 * Do Game actions.
 * File name: game.inc.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-27 Added $_POST['from_page_id'].  DHD
 * 14-03-23 Added dbg().  DHD
 * 14-03-20 Cloned from Game.  DHD
 */
dbg("+".basename(__FILE__).";$page_id");
//post_dump();
//session_dump();
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
        if ($_POST['from_page_id'] == 'game-list') {
            # find first game
            $_POST['game_id'] = 0;
            gameFind("next");
        } else {
            gameFind("prev");
        }
        break;
    case 'game-find':
        if ($_POST['from_page_id'] == 'game-list') {
            # find last game
            $_POST['game_id'] = 9999;
            gameFind("prev");
        } else {
            gameFind("");
        }
        break;
    case 'game-next':
        if ($_POST['from_page_id'] == 'game-list') {
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
        if ($_POST['from_page_id'] == 'game-list') {
            gameNew();
        } else {
            gameUpdate();
        }
        break;
    case 'game-delt':
        $butt_att_updt .= " disabled";
        $butt_att_delt .= " disabled";
        if ($_POST['from_page_id'] == 'game-list') {
            gameNew();
        } else {
            gameDelete();
        }
        break;
    case 'game-burp':
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

dbg("=".__FUNCTION__.";include:" . __FILE__ . ";^^^^^^^");

/**
 * Set up a blank game form, ready for find or add
 */
function gameNew() {
    # declare globals
    global $page_id;
    dbg("+".__FUNCTION__.";gameNew");

# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");


    # Get the next available game id number
    $gamz->getNew();
    $error_msgs['errorDiv'] = "Add new game:";

    dbg("=".__FUNCTION__."={$gamz->get_game_id()}");

# Show the game form
require(BASE_URI . "modules/game/game.form.php");
# Show the game add form
#require(BASE_URI . "modules/game/game.form.add.php");

    dbg("-".__FUNCTION__.";gameNew");
}

/**
 * Search for an existing game and display the results
 */
function gameFind($findType) {
    # declare globals
    global $page_id;
    global $gamz, $member_names, $error_msgs;
    dbg("+".__FUNCTION__.";={$_POST['game_id']}");
    #post_dump();

# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");

    $newGame = FALSE;
    # Look for game by id 
    $gamz->set_game_id($_POST['game_id']);
    dbg("=".__FUNCTION__.";={$gamz->get_game_id()}");
    try {
        $gamz->get($findType);
    } catch (PokerException $d) {
        #echo "gamz get failed:{$gamz->get_game_id()}.<br>";
        switch ($d->getCode()) {
        case Game::GET_ERR_ZERO:  # no games rows
            $error_msgs['game_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See error(s) below";
            $error_msgs['count'] += 1;
            break;
        case Game::GET_ERR_MULTI:  # multiple games rows
            $error_msgs['game_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See errors below";
            $error_msgs['count'] += 1;
            break;
        case Game::GET_WARN_NO_PREV:  # no previous game found
            $error_msgs['game_id'] = "{$d->getMessage()} ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See warning below";
            $error_msgs['count'] += 1;
            break;
        case Game::GET_INFO_ADD_NEW:  # new game option
            $error_msgs['errorDiv'] = "{$d->getMessage()} ({$d->getCode()})";
            $newGame = TRUE;
            break;
        default:
            echo "game.inc:" . __FUNCTION__ . ":Exception:{$gamz->get_game_id()}:" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "gameFind exception:{$gamz->get_game_id()}:" . $p->getMessage() . ".<br>";
            throw new PokerException($p);
        }
    }
    if ($error_msgs['count'] == 0 && !$newGame) {
        $error_msgs['errorDiv'] = "Game found.";
        gameGetNames();
    }
    dbg("=".__FUNCTION__.";={$gamz->get_game_id()}:{$error_msgs['count']}:{$error_msgs['errorDiv']}:{$member_names['snack']}:{$member_names['host']}:{$member_names['gear']}:{$member_names['caller']}");


# Show the game form
require(BASE_URI . "modules/game/game.form.php");

    dbg("-".__FUNCTION__.";game.inc:" . __FUNCTION__ . ":^^^={$gamz->get_game_id()}:{$error_msgs['count']}:{$error_msgs['errorDiv']}:{$member_names['snack']}");
}

/**
 * Get a member names for all the bonus fields
 */
function gameGetNames() {
    # declare globals
    global $gamz, $member_names, $error_msgs;
    gameGetName("snack");
    gameGetName("host");
    gameGetName("gear");
    gameGetName("caller");
}

/**
 * Get a member name for one of the bonus fields
 */
function gameGetName($field) {
    # declare globals
    global $gamz, $member_names, $error_msgs;
    dbg("+".__FUNCTION__.";{$_POST['game_id']}=$field");
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
            dbg("-".__FUNCTION__.";{$_POST['game_id']}=$field=");
            throw new Exception($p);
        }
    }
    dbg("-".__FUNCTION__.";{$_POST['game_id']}=$field=".$member_names["$field"]);
}


/**
 * Delete a game
 */
function gameDelete() {
    # declare globals
    global $page_id;

# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");

    dbg("=".__FUNCTION__.";gameDelete");
    $gamz->set_to_POST();
    # Get the next available game id number
    $gamz->delete();

    dbg("=".__FUNCTION__.";gameDelete:end={$gamz->get_game_id()}");

    if ($error_msgs['count'] == 0) {
        $error_msgs['errorDiv'] = "game deleted.";
    }

# Show the game form
require(BASE_URI . "modules/game/game.form.php");

}
/**
 * Show some test data for add functions
 */
function gameTest() {
    # declare globals
    global $page_id;
    global $debug, $gamz, $member_names, $error_msgs;
    dbg("=".__FUNCTION__.";gameTest");
    #post_dump();

# initialize the game form
require(BASE_URI . "modules/game/game.form.init.php");

    # create a test game
    $gamz->testGame();
    gameGetNames();
    $error_msgs['errorDiv'] = "Test game created.  Press \"Add/Update\" to add the game.";

# Show the game form
require(BASE_URI . "modules/game/game.form.php");

    dbg("=".__FUNCTION__.";game:" . __FUNCTION__ . ":end={$gamz->get_game_id()}");

}


/**
 * Show a list of games.
 */
function gameList() {
    global $debug;

    $games = new GameArray;
    dbg("=".__FUNCTION__.";game:List count={$games->gameCount}:" . count($games->gameList) . "");
#  $games->listing();
#  $games->sortNick();
//    usort($games->gameList, array('GameArray','sortNick')); 

    $games->listing();
    usort($games->gameList, array('GameArray','sortDate')); 

require(BASE_URI . "modules/game/game.list.form.php");

}
dbg("-".basename(__FILE__).";$page_id");
//******************************************************************************
// End of game.inc.php
//******************************************************************************
