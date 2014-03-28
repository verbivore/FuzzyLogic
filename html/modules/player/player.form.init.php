<?php  # player.form.init.php
/**
 * Initialization of the html form for a single player
 * File name: player.form.init.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-23 Added dbg().  DHD
 * 14-03-18 Added stats fields to player_form_fields.  DHD
 * 14-03-08 Original.  DHD
 */
dbg("+".basename(__FILE__));
//if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
# player record to be used for this page
$plyr = new Player;
# list of form fields to process
//$player_form_fields = array("member_id", "name_last", "name_first", "nickname", "stamp", "invite_cnt", "yes_cnt", "maybe_cnt", "no_cnt", "flake_cnt", "score");
$player_form_fields = array("member_id", "name_last", "name_first", "nickname",
    "status", "email", "phone", "score", "invite_cnt", "yes_cnt", "maybe_cnt", 
    "no_cnt", "flake_cnt", "stamp");

# Initialize error message fields
# Create an associative array of form fields to hold error messages, initialized to ""
$error_msgs = array();
$error_msgs['count'] = 0;
$error_msgs['errorDiv'] = "";
foreach ($player_form_fields as $field) {
    $error_msgs["$field"] = "";
}
//if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; }
dbg("-".basename(__FILE__));

#if ($debug) { foreach ($error_msgs as $col => $val) { echo "plyr.error_field=$col:$val.<br>"; } }
//******************************************************************************
// End of player.form.init.php
//******************************************************************************
?>

