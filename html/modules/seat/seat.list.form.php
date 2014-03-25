<?php  # seat.list.form.php
/**
 * Shows the html form for a list of seats
 * File name: seat.list.form.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-20 Updated for phpDoc.  DHD
 * 14-03-19 Original.  DHD
 * Future
 */
if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
/*
# set banner message and style
$message_banner = "{$error_msgs['errorDiv']}";
if ("{$error_msgs['count']}" != "0") {
    $message_class = "errorClass";
    $message_banner .= " ({$error_msgs['count']}).";
} else {
    $message_class = "infoClass";
}
*/
$message_class = "infoClass";
$message_banner = "message banner";
//if ($debug) { echo "plyr ID={$plyr->get_seat_id()}:{$message_banner}.<br>"; }

# ************************* #
# ***** Show the form ***** #

if ($debug) { echo "*** Dump seats *** ({$seats->seatCount} seats)<br>"; }
?>
  <div>
    <fieldset>
      <legend> Game List (<?php echo "{$seats->seatCount} seats)"; ?> </legend>
      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 
      <p>
        <table border='1'>
        <th>ID</th>
        <th>Nickname</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Invited</th>
        <th>Yes</th>
        <th>Maybe</th>
        <th>No</th>
        <th>Flake</th>
        <th>Score</th>
        <th>Stamp</th>
        </tr>


<?php  
    foreach ($seats->seatList as $row) {
//      $counter++;
        echo "<tr>";
        echo "<td>" . $row->get_member_id() . "</td>";
        echo "<td>" . $row->get_nickname() . "</td>";
        echo "<td>" . $row->get_name_last() . "</td>";
        echo "<td>" . $row->get_name_first() . "</td>";
        echo "<td>" . $row->get_invite_cnt() . "</td>";
        echo "<td>" . $row->get_yes_cnt() . "</td>";
        echo "<td>" . $row->get_maybe_cnt() . "</td>";
        echo "<td>" . $row->get_no_cnt() . "</td>";
        echo "<td>" . $row->get_flake_cnt() . "</td>";
        echo "<td>" . $row->get_score() . "</td>";
        echo "<td>" . $row->get_stamp() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
?>
  </div>
<?php
if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; }
# ***** Show the form ***** #
# ************************* #

