<?php  # game.form.php
/******************************************************************************
 * Shows the html form for a single game
 * File name: game.form.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-27 Added $_POST['from_page_id'].  DHD
 * 14-03-26 Added players table.  DHD
 * 14-03-18 Changed stats fields to readonly.  DHD
 * 14-03-08 Original.  DHD
 *****************************************************************************/
dbg("+".basename(__FILE__).";");
# set banner message and style
$message_banner = "{$error_msgs['errorDiv']}";
dbg("=".basename(__FILE__).";errors count={$error_msgs['count']}:" . sizeof($error_msgs) . "");


if ("{$error_msgs['count']}" == "0") {
    $message_class = "infoClass";
    if ("{$error_msgs['game_date']}" == "" ) {
        $error_msgs['game_date'] = $gamz->get_game_day();
    }
    if ("{$error_msgs['member_snack']}" == "" ) {
        $error_msgs['member_snack'] = $member_names['snack'];
    }
    if ("{$error_msgs['member_host']}" == "" ) {
        $error_msgs['member_host'] = $member_names['host'];
    }
    if ("{$error_msgs['member_gear']}" == "" ) {
        $error_msgs['member_gear'] = $member_names['gear'];
    }
    if ("{$error_msgs['member_caller']}" == "" ) {
        $error_msgs['member_caller'] = $member_names['caller'];
    }
} else {
    $message_class = "errorClass";
    $message_banner .= " ({$error_msgs['count']}).";
}

dbg("=".basename(__FILE__).";ID={$gamz->get_game_id()}:{$message_banner}");

# ************************* #
# ***** Show the form ***** #

?>
  <div>
    <fieldset>
      <legend> Game </legend>
      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 

      <fieldlabel for="game_id">ID:* </fieldlabel>
        <input type="number" id="game_id" name="game_id" size="2" maxsize="4" 
               value="<?php echo "{$gamz->get_game_id()}"; ?>" >
        <span class="errorFeedback errorSpan" id="game_idError" > <?php echo $error_msgs['game_id']?> </span>
      <br />

      <fieldlabel for="game_date">Date:* </fieldlabel>
        <input type="date" id="game_date" name="game_date" size="10" maxsize="10" 
               value="<?php echo "{$gamz->get_game_date()}"; ?>" >
        <span class="errorFeedback errorSpan" id="game_dateError" > <?php echo $error_msgs['game_date']?> </span>
      <br />

      <fieldlabel for="member_snack">Snack: </fieldlabel>
        <input type="text" id="member_snack" name="member_snack"  
               size="2" maxsize="4" value="<?php echo "{$gamz->get_member_snack()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_snackError" > <?php echo $error_msgs['member_snack']?> </span>
      <br />

      <fieldlabel for="member_host">Host: </fieldlabel>
        <input type="text" id="member_host" name="member_host"  
               size="2" maxsize="4" value="<?php echo "{$gamz->get_member_host()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_hostError" > <?php echo $error_msgs['member_host']?> </span>
      <br />

      <fieldlabel for="member_gear">Gear: </fieldlabel>
        <input type="number" id="member_gear" name="member_gear"  
               size="2" maxsize="4" value="<?php echo "{$gamz->get_member_gear()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_gearError" > <?php echo $error_msgs['member_gear']?> </span>
      <br />

      <fieldlabel for="member_caller">Organizer: </fieldlabel>
        <input type="number" id="member_caller" name="member_caller" 
               size="2" maxsize="4" value="<?php echo "{$gamz->get_member_caller()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_callerError" > <?php echo $error_msgs['member_caller']?> </span>
      <br />

      <fieldlabel for="stamp">Stamp: </fieldlabel>
        <input type="number" id="stamp" name="stamp" size="10" maxsize="10" readonly="true" 
               value="<?php echo "{$gamz->get_stamp()}"; ?>" >
        <span class="errorFeedback errorSpan" id="stampError" > <?php echo $error_msgs['stamp']?> </span>
      <br />
    </fieldset>

<?php  

$message_class = "infoClass";
$message_banner = "Select players to invite.";

$players = new PlayerArray;
usort($players->playerList, array('PlayerArray','sortScore')); 

# ************************* #
# ***** Show the form ***** #
# Future: Center Invite column
?>
  <div>
    <fieldset>
      <legend> Player List (<?php echo "{$players->playerCount}"; ?> players) </legend>
      <div id="errorDiv" <?php echo "class={$message_class}>{$message_banner}"; ?> </div> 
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
        </tr>


<?php
#        <th>Last Name</th>

//$foo = 0;
//$foo ? printf("%d", $foo) : 'xxx';

    $counter = 0;
    foreach ($players->playerList as $row) {
        $member_id = $row->get_member_id();
        $seater = new Seat;
        $seater->set_game_id($gamz->get_game_id());
        $seater->set_member_id($member_id);
        $invited = FALSE;
        try {
            $seater->get();
            $invited = TRUE;
        } catch (pokerException $e) {
            if (!$e->getCode() == Seat::ERR_GET_ZERO) {
                echo "seat get failed";
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
            echo "<td align='center'>I</td>";
            echo "<td><input type='text' name=response_" . $counter .  
                    ' value="'.$seater->get_response()."\" id='resp_box' size='1' /></td>";
        } else {
            echo "<td align='center'><input type='checkbox' name=invite_" . $counter .  
                    " value=".$member_id." id='checkbox' /></td>";
            echo "<td><input type='text' name=response_" . $counter .  
                    " value='' size='1' /></td>";
        }
        echo "<td align='right'>" . number_format($row->get_score(),2) . "</td>";
        echo "<td>" . $row->get_name_first() . "</td>";
        echo "<td>" . $row->get_nickname() . "</td>";
#        echo "<td>" . $row->get_name_last() . "</td>";
        echo "<td align='right'>" . $row->get_invite_cnt() . "</td>";
        echo "<td align='right'>" . $row->get_yes_cnt() . "</td>";
        echo "<td align='right'>" . $row->get_maybe_cnt() . "</td>";
        echo "<td align='right'>" . $row->get_no_cnt() . "</td>";
        echo "<td align='right'>" . $row->get_flake_cnt() . "</td>";

#$row->get_flake_cnt() ? printf("<td align='right'>%d</td>", $row->get_flake_cnt()) : "<td>foo</td>";
        if ($invited) {
            echo "<td><input type='text' name='note_mst_" . $counter . "'" .
                    " value='" . $seater->get_note_master() . "' size='10' /></td>";
            echo "<td><input type='text' name='note_mbr_" . $counter . "'" .
                    " value='" . $seater->get_note_member() . "' size='10' /></td>";
        } else {
            echo "<td><input type='text' name='note_mst_" . $counter . "'" .
                    " value='' size='10' /></td>";
            echo "<td><input type='text' name='note_mbr_" . $counter . "'" .
                    " value='' size='10' /></td>";
        }
        echo "<td><input type='hidden' name='mbr_id_row_" . $counter . "' value='" . $member_id . "'></td>";
#readonly=\"true\"  
        echo "</tr>";
        $counter++;
    }
    echo "</table>";
    # save the seat count for the update process
    echo '<input type="hidden" name="player_count"  readonly="true"  value=';
    echo $players->playerCount . ">";

?>
  </div>
<?php
dbg("-".basename(__FILE__)."");
# ***** Show the form ***** #
# ************************* #

if (FALSE) {

    $seats = new SeatArray($gamz->get_game_id());

    dbg("=".basename(__FILE__).";seat count=$seats->seatCount");
    if ($seats->seatCount > 0) {
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>Game ID</th>";
        echo "<th>Member ID</th>";
        echo "<th>Response</th>";
        echo "<th>Member Note</th>";
        echo "<th>Notes</th>";
        echo "</tr>";


        foreach ($seats->seatList as $row) {
//            $counter++;
            echo "<tr>";
            echo "<td>" . $row->get_game_id() . "</td>";
            echo "<td>" . $row->get_member_id() . "</td>";
            echo "<td>" . $row->get_response() . "</td>";
            echo "<td>" . $row->get_note_member() . "</td>";
            echo "<td>" . $row->get_note_master() . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>


  </div>
<?php
//$_POST['stamp'] = $gamz->get_stamp();
echo "<input type='hidden' name='from_page_id' value='game_form'>";
dbg("-".basename(__FILE__).";");
# ***** Show the form ***** #
# ************************* #
?>
