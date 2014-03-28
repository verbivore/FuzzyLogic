<?php  # player.list.form.php
/**
 * Shows the html form for a list of players
 * File name: player.list.form.php
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

14-03-23 Merged with game.form.php

$message_class = "infoClass";
$message_banner = "Select players to invite.";

$players = new PlayerArray;
usort($players->playerList, array('PlayerArray','sortScore')); 

# ************************* #
# ***** Show the form ***** #

?>
  <div>
    <fieldset>
      <legend> Player List (<?php echo "{$players->playerCount} players)"; ?> </legend>
      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 
      <p>
        <table border='1'>
        <th>Invite?</th>
        <th>Score</th>
        <th>Nickname</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Invited</th>
        <th>Yes</th>
        <th>Maybe</th>
        <th>No</th>
        <th>Flake</th>
        </tr>


<?php  
    $counter = 0;
    foreach ($players->playerList as $row) {
        echo "<tr>";
        echo "<td><input type='checkbox' name=invite_" . $counter .  
                    " value=".$row->get_member_id()." id='checkbox' /></td>";
        echo "<td>" . $row->get_score() . "</td>";
        echo "<td>" . $row->get_nickname() . "</td>";
        echo "<td>" . $row->get_name_last() . "</td>";
        echo "<td>" . $row->get_name_first() . "</td>";
        echo "<td>" . $row->get_invite_cnt() . "</td>";
        echo "<td>" . $row->get_yes_cnt() . "</td>";
        echo "<td>" . $row->get_maybe_cnt() . "</td>";
        echo "<td>" . $row->get_no_cnt() . "</td>";
        echo "<td>" . $row->get_flake_cnt() . "</td>";
        echo "</tr>";
        $counter++;
    }
    echo "</table>";
    # save the seat count for the update process
    echo "<input type='text' name='player_count' value=";
    echo $players->playerCount . ">d";

?>
  </div>
<?php
dbg("-".basename(__FILE__)."");
# ***** Show the form ***** #
# ************************* #

