<?php # index.php
/**
 *  Set up and show the page.
 *  File name: index.php
 *  @author David Demaree <dave.demaree@yahoo.com> (from Larry Ulman) <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-23 Added dbg() function.  DHD
 * 14-03-18 Recovered member class include. Renamed classes to title case.  
            Added play-delt & from_page_id.  DHD
 * 14-03-08 Original.  DHD
 */

/*
<?php dbg("+include:" . __FILE__ . "");?>
dbg("+include:" . __FILE__ . "");
tail -f /var/log/apache2/*.log
*/
// Require the configuration file before any PHP code:
require('./includes/config.inc.php');


// Determine which page to show:
if (isset($_POST['main'])) {
    $page_id = 'main';
} elseif (isset($_POST['play'])) {
    $page_id = 'play';
} elseif (isset($_POST['p-prev'])) {
    $page_id = 'play-prev';
} elseif (isset($_POST['p-find'])) {
    $page_id = 'play-find';
} elseif (isset($_POST['p-next'])) {
    $page_id = 'play-next';
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


} elseif (isset($_POST['seat'])) {
    $page_id = 'seat';
} elseif (isset($_POST['s-preg'])) {
    $page_id = 'seat-preg';
} elseif (isset($_POST['s-prep'])) {
    $page_id = 'seat-prep';
} elseif (isset($_POST['s-find'])) {
    $page_id = 'seat-find';
} elseif (isset($_POST['s-nexg'])) {
    $page_id = 'seat-nexg';
} elseif (isset($_POST['s-nexp'])) {
    $page_id = 'seat-nexp';
} elseif (isset($_POST['s-list'])) {
    $page_id = 'seat-list';
} elseif (isset($_POST['s-updt'])) {
    $page_id = 'seat-updt';
} elseif (isset($_POST['s-delt'])) {
    $page_id = 'seat-delt';
} elseif (isset($_POST['s-burp'])) {
    $page_id = 'seat-burp';



} elseif (isset($_GET['p'])) {
    $page_id = $_GET['p'];
} else {
    $page_id = NULL;
}

$page_file_js = '';
// Determine which page to display:
switch ($page_id) {
    case 'play':
        $page_file = 'player/player.inc.php';
        $page_title = 'Players | Poker';
        break;
    case 'play-prev':
        $page_file = 'player/player.inc.php';
        $page_title = 'Previous | Player | Poker';
        break;
    case 'play-find':
        $page_file = 'player/player.inc.php';
        $page_title = 'Find | Player | Poker';
        break;
    case 'play-next':
        $page_file = 'player/player.inc.php';
        $page_title = 'Next | Player | Poker';
        break;
    case 'play-list':
        $page_file = 'player/player.inc.php';
        $page_title = 'List | Players | Poker';
        break;
    case 'play-updt':
        $page_file = 'player/player.inc.php';
        $page_title = 'Update | Player | Poker';
        break;
    case 'play-delt':
        $page_file = 'player/player.inc.php';
        $page_title = 'Delete | Player | Poker';
        break;
    case 'play-burp':
        $page_file = 'player/player.inc.php';
        $page_title = 'Burp | Player | Poker';
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


  
    case 'seat':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'Seats';
        break;
    case 'seat-preg':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'Previous Game | Seats | Poker';
        break;
    case 'seat-prep':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'Previous Player | Seats | Poker';
        break;
    case 'seat-find':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'Find | Seats | Poker';
        break;
    case 'seat-nexg':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'Next Game | Seats | Poker';
        break;
    case 'seat-nexp':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'Next Player | Seats | Poker';
        break;
    case 'seat-list':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'List | Seats | Poker';
        break;
    case 'seat-updt':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'Update | Seats | Poker';
        break;
    case 'seat-delt':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'Delete | Seats | Poker';
        break;
    case 'seat-burp':
        $page_file = 'seat/seat.inc.php';
        $page_file_js = 'js/seat.js';
        $page_title = 'Burp | Seats | Poker';
        break;
  
    // Default is to include the main page.
    default:
        $page_file = 'main.inc.php';
        $page_file_js = 'js/main.js';
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
dbg("+".basename(__FILE__).";(in progress, header set)");

define('FROM_PAGE_NEW', 'NEW');

# Reset SESSION variables
if (isset($_SESSION['player_cnt'])) {
    $game_update_player_cnt = $_SESSION['player_cnt'];
    unset($_SESSION['player_cnt']);
}
# Get standard POST variables
if (!isset($_POST['from_page_id'])) {
    echo "not set<br>";
    $_POST['from_page_id'] = FROM_PAGE_NEW;
}
echo "(from {$_POST['from_page_id']})";

// Include the module files:
dbg("=".basename(__FILE__).";loading Member");
require_once(BASE_URI . 'class/Member.php');
dbg("=".basename(__FILE__).";loading Game");
require_once(BASE_URI . 'class/Game.php');
dbg("=".basename(__FILE__).";loading GameArray");
require_once(BASE_URI . 'class/GameArray.php');
dbg("=".basename(__FILE__).";loading Player");
require_once(BASE_URI . 'class/Player.php');
dbg("=".basename(__FILE__).";loading PlayerArray");
require_once(BASE_URI . 'class/PlayerArray.php');
dbg("=".basename(__FILE__).";loading Seat");
require_once(BASE_URI . 'class/Seat.php');
dbg("=".basename(__FILE__).";loading SeatArray");
require_once(BASE_URI . 'class/SeatArray.php');
dbg("=".basename(__FILE__).";loading testPerson");
require_once(TEST_URI . 'testPerson.class.php');

// Include the content-specific module:
// $page_file is determined from the above switch.
#echo "tail -f /var/log/apache2/*.log <br>";
dbg("=".basename(__FILE__).";page_id=$page_id, page_file=$page_file, page_title=$page_title");
require(BASE_URI . 'modules/' . $page_file);

// Include the footer file to complete the template:
require(BASE_URI . 'includes/footer.inc.php');
$_SESSION['from_page_id'] = $page_id;
dbg("-".basename(__FILE__).";endof");
?>

