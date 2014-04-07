<?php  # invite.form.init.php
/**
 * Initialization of the html form for a single invite
 * File name: invite.form.init.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-18 Added stats fields to invite_form_fields.  DHD
 * 14-03-08 Original.  DHD
 */
dbg("+".basename(__FILE__).";");
# invite record to be used for this page
$gamz = new Game;

# list of form fields to process
$invite_form_fields = array("game_id", "game_date", "em_to", "em_subject", "em_message", "em_from", "em_headers");

# Initialize error message fields
# Create an associative array of form fields to hold error messages, initialized to ""
$invite_error_msgs = array();
$invite_error_msgs['count'] = 0;
$invite_error_msgs['errorDiv'] = "";
foreach ($invite_form_fields as $field) {
    $invite_error_msgs["$field"] = "";
}

dbg("=".basename(__FILE__).";fields=" . sizeof($invite_form_fields) . ";msgs=" . sizeof($invite_error_msgs) . "");
dbg("-".basename(__FILE__).";");
#if ($debug) { foreach ($error_msgs as $col => $val) { echo "gamz.error_field=$col:$val"); }
//******************************************************************************
// End of invite.form.init.php
//******************************************************************************
?>

