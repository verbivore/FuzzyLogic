<?php  # seat.form.php
/******************************************************************************
 * Shows the html form for a single seat
 * File name: seat.form.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-23 Added dbg() function.  DHD
 * 14-03-23 Original, from game.form.php.  DHD
 *****************************************************************************/
dbg("+".basename(__FILE__).";");
# set banner message and style
$message_banner = "{$error_msgs['errorDiv']}";
dbg("=".basename(__FILE__).";errors count={$error_msgs['count']}:" . sizeof($error_msgs) . "");

/*
if ("{$error_msgs['count']}" == "0") {
    $message_class = "infoClass";
    if ("{$error_msgs['member_id']}" == "" ) {
        $error_msgs['member_id'] = $seaz->get_member_name();
    }
    if ("{$error_msgs['response']}" == "" ) {
        $error_msgs['response'] = $member_names['snack'];
    }
    if ("{$error_msgs['note_member']}" == "" ) {
        $error_msgs['note_member'] = $member_names['host'];
    }
    if ("{$error_msgs['note_master']}" == "" ) {
        $error_msgs['note_master'] = $member_names['gear'];
    }
} else { */
    $message_class = "errorClass";
    $message_banner .= " ({$error_msgs['count']}).";
//}

dbg("=".basename(__FILE__).";{$seaz->get_game_id()};{$seaz->get_member_id()};{$message_banner}");

# ************************* #
# ***** Show the form ***** #

?>
  <div>
    <fieldset>
      <legend> Seat </legend>
      <p>
      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 
      <fieldlabel for="game_id">Game: </fieldlabel>
        <input type="number" id="game_id" name="game_id" size="2" maxsize="4" 
               value="<?php echo "{$seaz->get_game_id()}"; ?>" >
        <span class="errorFeedback errorSpan" id="game_idError" > <?php echo $error_msgs['game_id']?> </span>
      <br />
      <fieldlabel for="member_id">Player: </fieldlabel>
        <input type="number" id="member_id" name="member_id" size="10" maxsize="10" 
               value="<?php echo "{$seaz->get_member_id()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_idError" > <?php echo $error_msgs['member_id']?> </span>
      <br />
      <fieldlabel for="response">Response: </fieldlabel>
        <input type="text" id="response" name="response" size="2" maxsize="4" 
               value="<?php echo "{$seaz->get_response()}"; ?>" >
        <span class="errorFeedback errorSpan" id="responseError" > <?php echo $error_msgs['response']?> </span>
      <br />
      <fieldlabel for="note_member">Player Note: </fieldlabel>
        <input type="text" id="note_member" name="note_member" size="2" maxsize="4" 
               value="<?php echo "{$seaz->get_note_member()}"; ?>" >
        <span class="errorFeedback errorSpan" id="note_memberError" > <?php echo $error_msgs['note_member']?> </span>
      <br />
      <fieldlabel for="note_master">Notes: </fieldlabel>
        <input type="number" id="note_master" name="note_master" size="2" maxsize="4" 
               value="<?php echo "{$seaz->get_note_master()}"; ?>" >
        <span class="errorFeedback errorSpan" id="note_masterError" > <?php echo $error_msgs['note_master']?> </span>
      <br />
      <fieldlabel for="stamp">Stamp: </fieldlabel>
        <input type="number" id="stamp" name="stamp" size="10" maxsize="10" readonly="true" 
               value="<?php echo "{$seaz->get_stamp()}"; ?>" >
        <span class="errorFeedback errorSpan" id="stampError" > <?php echo $error_msgs['stamp']?> </span>
      <br />
    </fieldset>
  </div>
<?php
$_POST['stamp'] = $seaz->get_stamp();
dbg("-".basename(__FILE__).";");
# ***** Show the form ***** #
# ************************* #
?>
