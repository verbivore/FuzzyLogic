<?php  # game.form.php
/******************************************************************************
 * Shows the html form to email invites
 * @author David Demaree <dave.demaree@yahoo.com>
 * File name: invite.form.php
 *** History ***  
 * 14-04-06 Cloned from game.form.php.  DHD
 *****************************************************************************/
dbg("+".basename(__FILE__).";");
# set banner message and style
$message_banner = "{$invite_error_msgs['errorDiv']}";
dbg("=".basename(__FILE__).";errors count={$invite_error_msgs['count']}:" . sizeof($invite_error_msgs) . "");


if ("{$invite_error_msgs['count']}" == "0") {
    $message_class = "infoClass";
    if ("{$invite_error_msgs['invite_date']}" == "" ) {
        $invite_error_msgs['invite_date'] = $gamz->get_invite_day();
    }
    if ("{$invite_error_msgs['member_snack']}" == "" ) {
        $invite_error_msgs['member_snack'] = $member_names['snack'];
    }
    if ("{$invite_error_msgs['member_host']}" == "" ) {
        $invite_error_msgs['member_host'] = $member_names['host'];
    }
    if ("{$invite_error_msgs['member_gear']}" == "" ) {
        $invite_error_msgs['member_gear'] = $member_names['gear'];
    }
    if ("{$invite_error_msgs['member_caller']}" == "" ) {
        $invite_error_msgs['member_caller'] = $member_names['caller'];
    }
} else {
    $message_class = "errorClass";
    $message_banner .= " ({$invite_error_msgs['count']}).";
}
dbg("=".basename(__FILE__).";ID={$gamz->get_invite_id()}:{$message_banner}");

$gamz = new Game;
$find_game = TRUE;
if (isset($_POST["game_id"])) {
    $find_game = FALSE;
    $gamz->set_game_id($_POST[game_id]);
    $em_to = "em_to...";
    $em_subject = "Fuzzy Logic " . $gamz->get_game_date();
    $em_message = "em_message...";
    $em_from = "em_from...";
    $em_headers = "em_headers...";

    $seats = new SeatArray($gameId);

    foreach ($players->playerList as $row) {
        if ($row->get_member_id() == "I") {
        echo "          <option value='" . $list_mem_id . "'";
        if ($list_mem_id == $plyr->get_member_id()) {
            echo " selected='selected'";
        }
        echo ">" . $row->get_member_id() . "</option>\n";
    
    }
} else {

?>
  <div>
    <fieldset>
      <legend> Invite </legend>
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


      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 

      <fieldlabel for="em_to">To:* </fieldlabel>
        <input type="date" id="em_to" name="em_to" size="10" maxsize="10" 
               value="<?php echo "{$em_to}"; ?>" >
        <span class="errorFeedback errorSpan" id="em_toError" > <?php echo $invite_error_msgs['em_to']?> </span>
      <br />

      <fieldlabel for="em_subject">Subject:* </fieldlabel>
        <input type="number" id="em_subject" name="em_subject" size="10" maxsize="20" 
               value="<?php echo $em_subject?>" >
        <span class="errorFeedback errorSpan" id="em_subjectError" > <?php echo $invite_error_msgs['em_subject']?> </span>
      <br />

      <fieldlabel for="em_message">Message: </fieldlabel>
        <input type="text" id="em_message" name="em_message" size="2" maxsize="4" 
               value="<?php echo "{$em_message}"; ?>" >
        <span class="errorFeedback errorSpan" id="em_messageError" > <?php echo $invite_error_msgs['em_message']?> </span>
      <br />

      <fieldlabel for="em_from">From: </fieldlabel>
        <input type="text" id="em_from" name="em_from" size="2" maxsize="4" 
               value="<?php echo "{$em_from}"; ?>" >
        <span class="errorFeedback errorSpan" id="em_fromError" > <?php echo $invite_error_msgs['em_from']?> </span>
      <br />

      <fieldlabel for="em_headers">Headers: </fieldlabel>
        <input type="number" id="em_headers" name="em_headers" size="2" maxsize="4" 
               value="<?php echo "{$em_headers}"; ?>" >
        <span class="errorFeedback errorSpan" id="em_headersError" > <?php echo $invite_error_msgs['em_headers']?> </span>
      <br />

      <br />
    </fieldset>
  </div>

<?php  
dbg("-".basename(__FILE__).";");
# ***** Show the form ***** #
# ************************* #
?>
