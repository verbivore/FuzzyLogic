<?php  # player.list.form.php
/**
 * Shows the html form for a list of players
 * File name: player.list.form.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-27 Added $_POST['from_page_id'].  DHD
 * 14-03-27 Added dbg().  DHD
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
//dbg("=".basename(__FILE__)."plyr ID={$plyr->get_player_id()}:{$message_banner}");

# ************************* #
# ***** Show the form ***** #

dbg("=".basename(__FILE__)."*** Dump players *** ({$players->playerCount} players)");
?>
  <div>
    <fieldset>
      <legend> Player List (<?php echo "{$players->playerCount} players)"; ?> </legend>
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
    foreach ($players->playerList as $row) {
//      $counter++;
        echo "<tr>";
        echo "<td align='right'>" . $row->get_member_id() . "</td>";
        echo "<td>" . $row->get_nickname() . "</td>";
        echo "<td>" . $row->get_name_last() . "</td>";
        echo "<td>" . $row->get_name_first() . "</td>";
        echo "<td align='right'>" . $row->get_invite_cnt() . "</td>";
        echo "<td align='right'>" . $row->get_yes_cnt() . "</td>";
        echo "<td align='right'>" . $row->get_maybe_cnt() . "</td>";
        echo "<td align='right'>" . $row->get_no_cnt() . "</td>";
        echo "<td align='right'>" . $row->get_flake_cnt() . "</td>";
        echo "<td align='right'>" . number_format($row->get_score(),2) . "</td>";
        echo "<td>" . $row->get_stamp() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
?>
  </div>
<?php
# save the form name
echo "<input type='hidden' name='from_page_id' value='play-list'>";
dbg("-".basename(__FILE__)."");
# ***** Show the form ***** #
# ************************* #

