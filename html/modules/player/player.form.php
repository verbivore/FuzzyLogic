<?php  # player.form.php
/******************************************************************************
 *  File name: player.form.php
 *  @author David Demaree <dave.demaree@yahoo.com>
 *  Contact: dave.demaree@yahoo.com
 *  Purpose: Shows the html form for a single player
 *** History ***  
 * 14-03-18 Changed stats fields to readonly.  DHD
 * 14-03-08 Original.  DHD
 *****************************************************************************/
if ($debug) { echo "include:" . __FILE__ . ";VVVVVVV.<br>"; }
# set banner message and style
$message_banner = "{$error_msgs['errorDiv']}";
if ("{$error_msgs['count']}" != "0") {
  $message_class = "errorClass";
  $message_banner .= " ({$error_msgs['count']}).";
} else {
  $message_class = "infoClass";
}

if ($debug) { echo "plyr ID={$plyr->get_member_id()}:{$message_banner}.<br>"; }

# ************************* #
# ***** Show the form ***** #

?>
  <div>
    <fieldset>
      <legend> player </legend>
      <p>
      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 
      <fieldlabel for="member_id">ID:* </fieldlabel>
        <input type="number" id="member_id" name="member_id" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_member_id()}"; ?>" >
        <span class="errorFeedback errorSpan" id="member_idError" > <?php echo $error_msgs['member_id']?> </span>
      <br />
      <fieldlabel for="nickname">Nickname:* </fieldlabel>
        <input type="date" id="nickname" name="nickname" size="10" maxsize="10" value="<?php echo "{$plyr->get_nickname()}"; ?>" >
        <span class="errorFeedback errorSpan" id="nicknameError" > <?php echo $error_msgs['nickname']?> </span>
      <br />
      <fieldlabel for="name_first">First name:* </fieldlabel>
        <input type="text" id="name_first" name="name_first" size="10" maxsize="20" value="<?php echo "{$plyr->get_name_first()}"; ?>" title="First Name..." >
        <span class="errorFeedback errorSpan" id="name_firstError" > <?php echo $error_msgs['name_first']?> </span>
      <br />
      <fieldlabel for="name_last">Last Name:* </fieldlabel>
        <input type="text" id="name_last" name="name_last" size="10" maxsize="20" 
              value="<?php echo "{$plyr->get_name_last()}"; ?>" >
        <span class="errorFeedback errorSpan" id="name_lastError" > <?php echo $error_msgs['name_last']?> </span>
      <br />
      <fieldlabel for="invite_cnt">Invites: </fieldlabel>
        <input type="number" id="invite_cnt" name="invite_cnt" size="2" maxsize="4" readonly="true" 
              value="<?php echo "{$plyr->get_invite_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="invite_cntError" > <?php echo $error_msgs['invite_cnt']?> </span>
      <br />
      <fieldlabel for="yes_cnt">Yesses: </fieldlabel>
        <input type="number" id="yes_cnt" name="yes_cnt" size="2" maxsize="4" readonly="true" 
              value="<?php echo "{$plyr->get_yes_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="yes_cntError" > <?php echo $error_msgs['yes_cnt']?> </span>
      <br />
      <fieldlabel for="maybe_cnt">maybes: </fieldlabel>
        <input type="number" id="maybe_cnt" name="maybe_cnt" size="2" maxsize="4" readonly="true" 
              value="<?php echo "{$plyr->get_maybe_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="maybe_cntError" > <?php echo $error_msgs['maybe_cnt']?> </span>
      <br />
      <fieldlabel for="no_cnt">nos: </fieldlabel>
        <input type="number" id="no_cnt" name="no_cnt" size="2" maxsize="4" readonly="true" 
              value="<?php echo "{$plyr->get_no_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="no_cntError" > <?php echo $error_msgs['no_cnt']?> </span>
      <br />
      <fieldlabel for="flake_cnt">flakes: </fieldlabel>
        <input type="number" id="flake_cnt" name="flake_cnt" size="2" maxsize="4" readonly="true" 
              value="<?php echo "{$plyr->get_flake_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="flake_cntError" > <?php echo $error_msgs['flake_cnt']?> </span>
      <br />
      <fieldlabel for="score">score: </fieldlabel>
        <input type="number" id="score" name="score" size="2" maxsize="4" readonly="true" 
              value="<?php echo "{$plyr->get_score()}"; ?>" >
        <span class="errorFeedback errorSpan" id="scoreError" > <?php echo $error_msgs['score']?> </span>
      <br />
<!--
      <br />
      <fieldlabel for="yesses">Yesses: </fieldlabel> <?php echo "{$plyr->get_yes_cnt()}"; ?>
      <br />
      <fieldlabel for="maybes">Maybes: </fieldlabel> <?php echo "{$plyr->get_maybe_cnt()}"; ?>
      <br />
      <fieldlabel for="nos">Nos: </fieldlabel> <?php echo "{$plyr->get_no_cnt()}"; ?>
      <br />
      <fieldlabel for="flakes">Flakes: </fieldlabel> <?php echo "{$plyr->get_flake_cnt()}"; ?>
      <br />
      <fieldlabel for="score">Score: </fieldlabel> <?php echo "{$plyr->get_score()}"; ?>
      <br />
      <fieldlabel for="stamp">Stamp: </fieldlabel> <?php echo "{$plyr->get_stamp()}"; ?>
      <fieldlabel for="stamp">Stamp: </fieldlabel>
        <input type="date" id="stamp" name="stamp" size="10" maxsize="10" value="<?php echo "{$plyr->get_stamp()}"; ?>" >
        <span class="errorFeedback errorSpan" id="stampError" > <?php echo $error_msgs['stamp']?> </span>
      <br />
      <fieldlabel for="invites">Invites: </fieldlabel> <?php echo "{$plyr->get_invite_cnt()}"; ?>
      <br />
      <fieldlabel for="invite_cnt">Invites: </fieldlabel>
        <input type="number" id="invite_cnt" name="invite_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_invite_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="invite_cntError" > <?php echo $error_msgs['invite_cnt']?> </span>
      <br />
      <br />
      <fieldlabel for="yes_cnt">Yesses: </fieldlabel>
        <input type="number" id="yes_cnt" name="yes_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_yes_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="yes_cntError" > <?php echo $error_msgs['yes_cnt']?> </span>
      <br />
      <fieldlabel for="maybe_cnt">maybes: </fieldlabel>
        <input type="number" id="maybe_cnt" name="maybe_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_maybe_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="maybe_cntError" > <?php echo $error_msgs['maybe_cnt']?> </span>
      <br />
      <fieldlabel for="no_cnt">nos: </fieldlabel>
        <input type="number" id="no_cnt" name="no_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_no_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="no_cntError" > <?php echo $error_msgs['no_cnt']?> </span>
      <br />
      <fieldlabel for="flake_cnt">flakes: </fieldlabel>
        <input type="number" id="flake_cnt" name="flake_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_flake_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="flake_cntError" > <?php echo $error_msgs['flake_cnt']?> </span>
      <br />
      <fieldlabel for="score">score: </fieldlabel>
        <input type="number" id="score" name="score" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_score()}"; ?>" >
        <span class="errorFeedback errorSpan" id="scoreError" > <?php echo $error_msgs['score']?> </span>
      <br />
-->
  </div>
<?php
$_POST['stamp'] = $plyr->get_stamp();
if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; }
# ***** Show the form ***** #
# ************************* #
?>
