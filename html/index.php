<?php # index.php
/**
 *  Set up and show the page.
 *  File name: index.php
 *  @author David Demaree <dave.demaree@yahoo.com> (from Larry Ulman) <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-18 Recovered member class include. Renamed classes to title case.  
            Added play-delt & from_page_id.  DHD
 * 14-03-08 Original.  DHD
 */

/*
<?php if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; } ?>
<?php if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; } ?>
if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
tail -f /var/log/apache2/*.log
*/
// Require the configuration file before any PHP code:
require('./includes/config.inc.php');

// Determine which page to show:
if (isset($_POST['main'])) {
    $page_id = 'main';
} elseif (isset($_POST['play'])) {
    $page_id = 'play';
} elseif (isset($_POST['p-find'])) {
    $page_id = 'play-find';
} elseif (isset($_POST['p-list'])) {
    $page_id = 'play-list';
} elseif (isset($_POST['p-updt'])) {
    $page_id = 'play-updt';
} elseif (isset($_POST['p-delt'])) {
    $page_id = 'play-delt';
} elseif (isset($_POST['p-burp'])) {
    $page_id = 'play-burp';
} elseif (isset($_POST['game'])) {
    $page_id = 'game';
} elseif (isset($_POST['g-prev'])) {
    $page_id = 'game-prev';
} elseif (isset($_POST['g-find'])) {
    $page_id = 'game-find';
} elseif (isset($_POST['g-next'])) {
    $page_id = 'game-next';
} elseif (isset($_POST['g-list'])) {
    $page_id = 'game-list';
} elseif (isset($_POST['g-updt'])) {
    $page_id = 'game-updt';
} elseif (isset($_POST['g-delt'])) {
    $page_id = 'game-delt';
} elseif (isset($_POST['g-burp'])) {
    $page_id = 'game-burp';





} elseif (isset($_POST['join'])) {
    $page_id = 'join';
} elseif (isset($_GET['p'])) {
    $page_id = $_GET['p'];
} else {
    $page_id = NULL;
}

// Determine which page to display:
switch ($page_id) {
    case 'play':
        $page_file = 'player/player.inc.php';
        $page_title = 'Players | Poker';
        break;
    case 'play-find':
        $page_file = 'player/player.inc.php';
        $page_title = 'Find | Players | Poker';
        break;
    case 'play-list':
        $page_file = 'player/player.inc.php';
        $page_title = 'List | Players | Poker';
        break;
    case 'play-updt':
        $page_file = 'player/player.inc.php';
        $page_title = 'Update | Players | Poker';
        break;
    case 'play-delt':
        $page_file = 'player/player.inc.php';
        $page_title = 'Delete | Players | Poker';
        break;
    case 'play-burp':
        $page_file = 'player/player.inc.php';
        $page_title = 'Burp | Players | Poker';
        break;
    case 'game':
        $page_file = 'game/game.inc.php';
        $page_title = 'Games';
        break;


    case 'game-prev':
        $page_file = 'game/game.inc.php';
        $page_title = 'Previous | Games | Poker';
        break;
    case 'game-find':
        $page_file = 'game/game.inc.php';
        $page_title = 'Find | Games | Poker';
        break;
    case 'game-next':
        $page_file = 'game/game.inc.php';
        $page_title = 'Next | Games | Poker';
        break;
    case 'game-list':
        $page_file = 'game/game.inc.php';
        $page_title = 'List | Games | Poker';
        break;
    case 'game-updt':
        $page_file = 'game/game.inc.php';
        $page_title = 'Update | Games | Poker';
        break;
    case 'game-delt':
        $page_file = 'game/game.inc.php';
        $page_title = 'Delete | Games | Poker';
        break;
    case 'game-burp':
        $page_file = 'game/game.inc.php';
        $page_title = 'Burp | Games | Poker';
        break;


  
    case 'join':
        $page_file = 'seat/seat.inc.php';
        $page_title = 'Reserve a Seat';
        break;
  
    // Default is to include the main page.
    default:
        $page_file = 'main.inc.php';
        $page_title = 'Poker';
        break;
      
} // End of main switch.

//Make sure the file exists:

if (!file_exists(BASE_URI . 'modules/' . $page_file)) {
    $page_file = 'main.inc.php';
    $page_title = 'Poker Default Page';
}

// Include the header file:
require('./includes/header.inc.php');
if ($debug) { echo "file:" . __FILE__ . ";>>>>>>>.<br>"; }
// Include the module files:
require_once(BASE_URI . 'class/Member.php');
require_once(BASE_URI . 'class/Player.php');
require_once(BASE_URI . 'class/PlayerArray.php');
require_once(BASE_URI . 'class/Game.php');

// Include the content-specific module:
// $page_file is determined from the above switch.
#echo "tail -f /var/log/apache2/*.log <br>";
if ($debug) { echo "page_id=$page_id, page_file=$page_file, page_title=$page_title.<br>"; }
require(BASE_URI . 'modules/' . $page_file);

// Include the footer file to complete the template:
require(BASE_URI . 'includes/footer.inc.php');
$_SESSION['from_page_id'] = $page_id;
if ($debug) { echo "file:" . __FILE__ . ";^^^^^^^.<br>"; }
?>

