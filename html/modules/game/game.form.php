<?php  # game.form.php
/******************************************************************************
 * Shows the html form for a single game
 * File name: game.form.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-29 Merged game.form.add to show seats.  Added page-id.  DHD
 * 14-03-27 Added $_POST['from_page_id'].  DHD
 * 14-03-26 Added players table.  DHD
 * 14-03-18 Changed stats fields to readonly.  DHD
 * 14-03-08 Original.  DHD
 *****************************************************************************/
dbg("+".basename(__FILE__).";");
# set banner message and style
$message_banner = "{$game_error_msgs['errorDiv']}";
dbg("=".basename(__FILE__).";errors count={$game_error_msgs['count']}:" . sizeof($game_error_msgs) . "");


if ("{$game_error_msgs['count']}" == "0") {
    $message_class = "infoClass";
    if ("{$game_error_msgs['game_date']}" == "" ) {
        $game_error_msgs['game_date'] = $gamz->get_game_day();
    }
    if ("{$game_error_msgs['member_snack']}" == "" ) {
        $game_error_msgs['member_snack'] = $member_names['snack'];
    }
    if ("{$game_error_msgs['member_host']}" == "" ) {
        $game_error_msgs['member_host'] = $member_names['host'];
    }
    if ("{$game_error_msgs['member_gear']}" == "" ) {
        $game_error_msgs['member_gear'] = $member_names['gear'];
    }
    if ("{$game_error_msgs['member_caller']}" == "" ) {
        $game_error_msgs['member_caller'] = $member_names['caller'];
    }
} else {
    $message_class = "errorClass";
    $message_banner .= " ({$game_error_msgs['count']}).";
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
        <span class="errorFeedback errorSpan" id="game_idError" > <?php echo $game_error_msgs['game_id']?> </span>
      <br />

      <fieldlabel for="game_date">Date:* </fieldlabel>
        <input type="date" id="game_date" name="game_date" size="10" maxsize="10" 
               value="<?php echo "{$gamz->get_game_date()}"; ?>" >
        <span class="errorFeedback errorSpan" id="game_dateError" > <?php echo $game_error_msgs['game_date']?> </span>
      <br />

      <fieldlabel for="member_snack">Snack: </fieldlabel>
        <input type="text" id="member_snack" name="member_snack"  
               size="2" maxsize="4" value="<?php echo "{$gamz->get_member_snack()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_snackError" > <?php echo $game_error_msgs['member_snack']?> </span>
      <br />

      <fieldlabel for="member_host">Host: </fieldlabel>
        <input type="text" id="member_host" name="member_host"  
               size="2" maxsize="4" value="<?php echo "{$gamz->get_member_host()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_hostError" > <?php echo $game_error_msgs['member_host']?> </span>
      <br />

      <fieldlabel for="member_gear">Gear: </fieldlabel>
        <input type="number" id="member_gear" name="member_gear"  
               size="2" maxsize="4" value="<?php echo "{$gamz->get_member_gear()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_gearError" > <?php echo $game_error_msgs['member_gear']?> </span>
      <br />

      <fieldlabel for="member_caller">Organizer: </fieldlabel>
        <input type="number" id="member_caller" name="member_caller" 
               size="2" maxsize="4" value="<?php echo "{$gamz->get_member_caller()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_callerError" > <?php echo $game_error_msgs['member_caller']?> </span>
      <br />

      <fieldlabel for="stamp">Stamp: </fieldlabel>
        <input type="number" id="stamp" name="stamp" size="10" maxsize="10" readonly="true" 
               value="<?php echo "{$gamz->get_stamp()}"; ?>" >
        <span class="errorFeedback errorSpan" id="stampError" > <?php echo $game_error_msgs['stamp']?> </span>
      <br />
    </fieldset>
  </div>

<?php  

if ("{$game_error_msgs['count']}" == "0") {
# Show the game players
require(BASE_URI . "modules/game/game.players.form.php");
}

# Save the form name so that the next page knows what to expect in $_POST
echo "<input type='hidden' name='from_page_id' value='game-form'>";
dbg("-".basename(__FILE__).";");
# ***** Show the form ***** #
# ************************* #
?>
