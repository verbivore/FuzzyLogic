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
dbg("+".basename(__FILE__)."");
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
//dbg("=".basename(__FILE__)."plyr ID={$plyr->get_seat_id()}:{$message_banner}");

# ************************* #
# ***** Show the form ***** #

?>
  <div>
    <fieldset>
      <legend> Game List (<?php echo "{$seats->seatCount} seats)"; ?> </legend>
      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 
      <p>
        <table border='1'>
        <th>Game ID</th>
        <th>Member ID</th>
        <th>Response</th>
        <th>Member Note</th>
        <th>Notes</th>
        <th>Stamp</th>
        </tr>


<?php  
    foreach ($seats->seatList as $row) {
//      $counter++;
        echo "<tr>";
        echo "<td>" . $row->get_game_id() . "</td>";
        echo "<td>" . $row->get_member_id() . "</td>";
        echo "<td>" . $row->get_response() . "</td>";
        echo "<td>" . $row->get_note_member() . "</td>";
        echo "<td>" . $row->get_note_master() . "</td>";
        echo "<td>" . $row->get_stamp() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
?>
  </div>
<?php
dbg("=".basename(__FILE__)."");
# ***** Show the form ***** #
# ************************* #

