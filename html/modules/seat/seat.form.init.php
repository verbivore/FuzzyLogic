<?php  # seat.form.init.php
/**
 * Initialization of the html form for a single seat
 * File name: seat.form.init.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-18 Added stats fields to seat_form_fields.  DHD
 * 14-03-08 Original.  DHD
 */
dbg("+".basename(__FILE__).";");
# seat record to be used for this page
$seaz = new Seat;
# list of form fields to process
$seat_form_fields = array("game_id", "member_id", "response", "note_member", "note_master", "stamp");

//$member_names = array("snack" => null, "host" => null, "gear" => null);

# Initialize error message fields
# Create an associative array of form fields to hold error messages, initialized to ""
$error_msgs = array();
$error_msgs['count'] = 0;
$error_msgs['errorDiv'] = "";
foreach ($seat_form_fields as $field) {
    $error_msgs["$field"] = "";
}
dbg("=".basename(__FILE__).";fields=" . sizeof($seat_form_fields) . ";msgs=" . sizeof($error_msgs) . "");
dbg("-".basename(__FILE__).";");
#if ($debug) { foreach ($error_msgs as $col => $val) { echo "seaz.error_field=$col:$val"); }
//******************************************************************************
// End of seat.form.init.php
//******************************************************************************
?>

