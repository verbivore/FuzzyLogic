<?php  # game.form.init.php
/**
 * Initialization of the html form for a single game
 * File name: game.form.init.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-18 Added stats fields to game_form_fields.  DHD
 * 14-03-08 Original.  DHD
 */
if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
# game record to be used for this page
$gamz = new Game;
# list of form fields to process
//$game_form_fields = array("game_id", "member_snack", "member_host", "game_date", "stamp", "member_gear", "member_caller", "maybe_cnt", "no_cnt", "flake_cnt", "score");
$game_form_fields = array("game_id", "game_date", "member_snack", "member_host", "member_gear", "member_caller", "stamp");

$member_names = array("snack" => null, "host" => null, "gear" => null, "caller" => null);

# Initialize error message fields
# Create an associative array of form fields to hold error messages, initialized to ""
$error_msgs = array();
$error_msgs['count'] = 0;
$error_msgs['errorDiv'] = "";
foreach ($game_form_fields as $field) {
    $error_msgs["$field"] = "";
}
if ($debug) { echo "game.form.init.php:fields=" . sizeof($game_form_fields) . ":msgs=" . sizeof($error_msgs) . ".<br>"; }
if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; }
#if ($debug) { foreach ($error_msgs as $col => $val) { echo "gamz.error_field=$col:$val.<br>"; } }
//******************************************************************************
// End of game.form.init.php
//******************************************************************************
?>

