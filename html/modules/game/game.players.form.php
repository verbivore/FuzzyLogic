<?php  # game.player.form.php
/******************************************************************************
 * Shows the html form for a single game
 * File name: game.player.form.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-04-06 Subdivided game.players.form.player.  DHD
 * 14-03-27 Added $_POST['from_page_id'].  DHD
 * 14-03-26 Subdivided from game.form.php.  DHD
 *****************************************************************************/
dbg("+".basename(__FILE__).";");

    define('GAME_PLAYER_ERR_PDO', '30108');

$players = new PlayerArray;
usort($players->playerList, array('PlayerArray','sortScore')); 

$player_message_banner = "{$player_error_msgs['errorDiv']}";
if ("{$player_error_msgs['count']}" == "0") {
    $player_message_class = "infoClass";
    $player_message_banner = "Invite/update players.";
} else {
    $player_message_class = "errorClass";
    $player_message_banner .= " ({$player_error_msgs['count']}).";
}

?>
  <div>
    <fieldset>
      <legend> Player List (<?php echo "{$players->playerCount}"; ?> players) </legend>
      <div id="errorDiv" <?php echo "class={$player_message_class}>{$player_message_banner}"; ?> </div> 




<!--  Game tab buttons  -->
        <table border='1' id='game-players'>
        <th align="center"><input type="button" id="inviteBtn" name="inviteBtn" value="Invite"></th>
        <th align="center">Resp</th>
        <th align="right">Rank</th>
        <th align="right">Score</th>
        <th>First Name</th>
        <th>Nickname</th>
        <th align="right">I</th>
        <th align="right">Y</th>
        <th align="right">M</th>
        <th align="right">N</th>
        <th align="right">F</th>
        <th>Note</th>
        <th>Mbr</th>
        <th>Message</th>
        </tr>

<?php require(BASE_URI . "modules/game/game.players.form.player.php"); ?>

        <!-- game player totals -->
<?php 
$yes_color = '';
if ($tot_yes >= MIN_PLAYERS ) {
    $yes_color = GREEN_BRIGHT;
} elseif (($tot_invite - $tot_no - $tot_flake) >= MIN_PLAYERS) {
    $yes_color = GREEN_PALE;
} else {
    $yes_color = RED;
}
?>
        <table border='1'>
        <th align="right">Invited</th>
        <th align="right">Yes</th>
        <th align="right">Maybe</th>
        <th align="right">No</th>
        <th align="right">Flake</th>
        </tr>
        <tr>
        <td align="right"><?php echo "$tot_invite";?></td>
        <td align="right" bgcolor="<?php echo "$yes_color";?>"><?php echo "$tot_yes";?></td>
        <td align="right"><?php echo "$tot_maybe";?></td>
        <td align="right"><?php echo "$tot_no";?></td>
        <td align="right"><?php echo "$tot_flake";?></td>
        </tr>
        </table>

    <!-- save the seat count for the update process -->
    <input type="hidden" name="player_count" id="player-count" readonly="true"  value="
    <?php echo $players->playerCount;?>">

    </fieldset>
  </div>

<?php dbg("-".basename(__FILE__).""); ?>
