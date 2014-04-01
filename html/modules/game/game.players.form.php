<?php  # game.player.form.php
/******************************************************************************
 * Shows the html form for a single game
 * File name: game.player.form.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
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
        <table border='1'>
        <th align="center">Invite?</th>
        <th align="center">Resp</th>
        <th align="right">Score</th>
        <th>First Name</th>
        <th>Nickname</th>
        <th align="right">Invited</th>
        <th align="right">Yes</th>
        <th align="right">Maybe</th>
        <th align="right">No</th>
        <th align="right">Flake</th>
        <th>Note</th>
        <th>Mbr</th>
        <th>Message</th>
        </tr>


<?php
    $player_idx = 0;
    $tot_invite = 0;
    $tot_yes = 0;
    $tot_maybe = 0;
    $tot_no = 0;
    $tot_flake = 0;
    foreach ($players->playerList as $player) {
//        $mbr_id_idx = "mbr_id_row_$i";
        $seater = new Seat;
        $seater->set_game_id($gamz->get_game_id());
        $member_id = $player->get_member_id();
        $seater->set_member_id($member_id);
        $invited = FALSE;
        try {
            $seater->get();
            $invited = TRUE;
        } catch (pokerException $e) {
            if ($e->getCode() == Seat::GET_ERR_ZERO) {
                if ($page_id == 'game-burp') {
//echo "get test seat<br>";
                    $seater->testSeat();
                }
            } else {
//                echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                throw new PokerException('Seat get failed game'.$seater->get_game_id().' player'.$seater->get_member_id().' error:' . $e->getCode(),
                                         GAME_PLAYER_ERR_PDO,
                                         $e);
            }
        }
        echo "<tr>";
#        $resp_cell = "<input type=\"text\" id=\"game_id" name="game_id" size="2" maxsize="4" ";
/*
      
        <input type="number" id="game_id" name="game_id" size="2" maxsize="4" 
               value="<?php echo "{$gamz->get_game_id()}"; ?>" >
        <span class="errorFeedback errorSpan" id="game_idError" > <?php echo $error_msgs['game_id']?> </span>
      <br />
*/

        if ($invited) {
            $tot_invite++;
            $resp = $seater->get_response();
            echo "<td align='center'>I</td>";
            echo "<td><input type='text' name=response_" . $player_idx .  
                    ' value="'.$resp."\" id='resp_box' size='1' /></td>";
            switch ($resp) {
            case 'I':
                break;
            case 'Y':
                $tot_yes++;
                break;
            case 'M':
                $tot_maybe++;
                break;
            case 'N':
                $tot_no++;
                break;
            case 'F':
                $tot_flake++;
                break;
            }
        } else {
            echo "<td align='center'><input type='checkbox' name=invite_" . $player_idx .  
                    " value=".$member_id." id='checkbox' /></td>";
//            echo "<td><input type='text' name=response_" . $player_idx .  
//                    " value='' size='1' /></td>";
            echo "<td><input type='text' name=response_" . $player_idx .  
                    ' value="'.$seater->get_response()."\" id='resp_box' size='1' /></td>";
        }
        echo "<td align='right'>" . number_format($player->get_score(),2) . "</td>";
        echo "<td>" . $player->get_name_first() . "</td>";
        echo "<td>" . $player->get_nickname() . "</td>";
        echo "<td align='right'>" . $player->get_invite_cnt() . "</td>";
        echo "<td align='right'>" . $player->get_yes_cnt() . "</td>";
        echo "<td align='right'>" . $player->get_maybe_cnt() . "</td>";
        echo "<td align='right'>" . $player->get_no_cnt() . "</td>";
        echo "<td align='right'>" . $player->get_flake_cnt() . "</td>";

#$player->get_flake_cnt() ? printf("<td align='right'>%d</td>", $player->get_flake_cnt()) : "<td>foo</td>";
        echo "<td><input type='text' name='note_mst_" . $player_idx . "'" .
                    " value='" . $seater->get_note_master() . "' size='10' /></td>";
        echo "<td><input type='text' name='note_mbr_" . $player_idx . "'" .
                    " value='" . $seater->get_note_member() . "' size='10' /></td>";
        # error messages
        if (!isset($player_error_msgs["{$player_idx}"])) $player_error_msgs["{$player_idx}"] = "";
        echo "<td class='errorFeedback errorSpan' id='err_player_'".$player_idx." size='10'>".
            $player_error_msgs["{$player_idx}"]."</td>";
        # player row id
        echo "<td><input type='hidden' name='mbr_id_row_" . $player_idx . "' value='" . $member_id . "'></td>";
#readonly=\"true\"  
        echo "</tr>";
        $player_idx++;
    }
    echo "</table>";

?>
        <!-- game player totals -->
        <table border='1'>
        <th align="right">Invited</th>
        <th align="right">Yes</th>
        <th align="right">Maybe</th>
        <th align="right">No</th>
        <th align="right">Flake</th>
        </tr>
        <tr>
        <td align='right'><?php echo "$tot_invite";?></td>
        <td align='right'><?php echo "$tot_yes";?></td>
        <td align='right'><?php echo "$tot_maybe";?></td>
        <td align='right'><?php echo "$tot_no";?></td>
        <td align='right'><?php echo "$tot_flake";?></td>
        </tr>
        </table>

    <!-- save the seat count for the update process -->
    <input type="hidden" name="player_count"  readonly="true"  value='
    <?php echo $players->playerCount;?>'>

    </fieldset>
  </div>

<?php dbg("-".basename(__FILE__).""); ?>
