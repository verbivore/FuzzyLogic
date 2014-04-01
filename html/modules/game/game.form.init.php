<?php  # game.form.init.php
/**
 * Initialization of the html form for a single game
 * File name: game.form.init.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-18 Added stats fields to game_form_fields.  DHD
 * 14-03-08 Original.  DHD
 */
dbg("+".basename(__FILE__).";");
# game record to be used for this page
$gamz = new Game;

# list of form fields to process
//$game_form_fields = array("game_id", "member_snack", "member_host", "game_date", "stamp", "member_gear", "member_caller", "maybe_cnt", "no_cnt", "flake_cnt", "score");
$game_form_fields = array("game_id", "game_date", "member_snack", "member_host", "member_gear", "member_caller", "stamp");

# list of member names associated with bonus fields
$member_names = array("snack" => null, "host" => null, "gear" => null, "caller" => null);

# Initialize error message fields
# Create an associative array of form fields to hold error messages, initialized to ""
$game_error_msgs = array();
$game_error_msgs['count'] = 0;
$game_error_msgs['errorDiv'] = "";
foreach ($game_form_fields as $field) {
    $game_error_msgs["$field"] = "";
}
$player_error_msgs = array();
$player_error_msgs['count'] = 0;
$player_error_msgs['errorDiv'] = "";

if (isset($_POST['player_count'])) {
    for ($i = 0; $i < $_POST['player_count']; $i++) {
        $player_error_msgs["$i"] = "";
    }
}

dbg("=".basename(__FILE__).";fields=" . sizeof($game_form_fields) . ";msgs=" . sizeof($game_error_msgs) . "");
dbg("-".basename(__FILE__).";");
#if ($debug) { foreach ($error_msgs as $col => $val) { echo "gamz.error_field=$col:$val"); }
//******************************************************************************
// End of game.form.init.php
//******************************************************************************
?>

