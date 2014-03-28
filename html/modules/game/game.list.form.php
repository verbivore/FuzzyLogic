<?php  # game.list.form.php
/**
 * Shows the html form for a list of games
 * File name: game.list.form.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-27 Added $_POST['from_page_id'].  DHD
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
//dbg("=".basename(__FILE__)."plyr ID={$plyr->get_game_id()}:{$message_banner}");

# ************************* #
# ***** Show the form ***** #

dbg("=".basename(__FILE__)."*** Dump games *** ({$games->gameCount} games)");
?>
  <div>
    <fieldset>
      <legend> Game List (<?php echo "{$games->gameCount}"; ?> games)</legend>
      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 
      <p>
        <table border='1'>
        </tr>
        <th>ID</th>
        <th>Date</th>
        <th>Snack</th>
        <th>Host</th>
        <th>Gear</th>
        <th>Organizer</th>
        <th>Stamp</th>
        </tr>


<?php  
    foreach ($games->gameList as $row) {
//      $counter++;
        echo "<tr>";
            echo "<td>" . $row->get_game_id() . "</td>";
            echo "<td>" . $row->get_game_date() . "</td>";
            echo "<td>" . $row->get_member_snack() . "</td>";
            echo "<td>" . $row->get_member_host() . "</td>";
            echo "<td>" . $row->get_member_gear() . "</td>";
            echo "<td>" . $row->get_member_caller() . "</td>";
            echo "<td>" . $row->get_stamp() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
?>
  </div>
<?php
echo "<input type='hidden' name='from_page_id' value='game_list'>";
dbg("-".basename(__FILE__).";");
# ***** Show the form ***** #
# ************************* #

