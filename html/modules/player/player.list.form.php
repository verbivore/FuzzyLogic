<?php  # player.list.form.php
/******************************************************************************
 *  File name: player.list.form.php
 *  Created by: David Demaree
 *  Contact: dave.demaree@yahoo.com
 *  Purpose: Shows the html form for a list of players
 *** History ***  
 * 2014-03-08 Original.  DHD
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

if ($debug) { echo "plyr ID={$plyr->get_player_id()}:{$message_banner}.<br>"; }

# ************************* #
# ***** Show the form ***** #

?>
  <div>
    <fieldset>
      <legend> player </legend>
      <p>
      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 
      <label for="player_id">ID:* </label>
        <input type="number" id="player_id" name="player_id" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_player_id()}"; ?>" >
        <span class="errorFeedback errorSpan" id="player_idError" > <?php echo $error_msgs['player_id']?> </span>
      <br />
      <label for="nickname">Nickname:* </label>
        <input type="date" id="nickname" name="nickname" size="10" maxsize="10" value="<?php echo "{$plyr->get_nickname()}"; ?>" >
        <span class="errorFeedback errorSpan" id="nicknameError" > <?php echo $error_msgs['nickname']?> </span>
      <br />
      <label for="name_first">First name:* </label>
        <input type="text" id="name_first" name="name_first" size="10" maxsize="20" value="<?php echo "{$plyr->get_name_first()}"; ?>" title="First Name..." >
        <span class="errorFeedback errorSpan" id="name_firstError" > <?php echo $error_msgs['name_first']?> </span>
      <br />
      <label for="name_last">Last Name:* </label>
        <input type="text" id="name_last" name="name_last" size="10" maxsize="20" 
              value="<?php echo "{$plyr->get_name_last()}"; ?>" >
        <span class="errorFeedback errorSpan" id="name_lastError" > <?php echo $error_msgs['name_last']?> </span>
      <br />
Invites: <?php echo "{$plyr->get_invite_cnt()}"; ?>
      <br />
Yesses: <?php echo "{$plyr->get_yes_cnt()}"; ?>
      <br />
Maybes: <?php echo "{$plyr->get_maybe_cnt()}"; ?>
      <br />
Nos: <?php echo "{$plyr->get_no_cnt()}"; ?>
      <br />
Flakes: <?php echo "{$plyr->get_flake_cnt()}"; ?>
      <br />
Score: <?php echo "{$plyr->get_score()}"; ?>
      <br />
Stamp: <?php echo "{$plyr->get_stamp()}"; ?>
<!--
      <label for="stamp">Stamp: </label>
        <input type="date" id="stamp" name="stamp" size="10" maxsize="10" value="<?php echo "{$plyr->get_stamp()}"; ?>" >
        <span class="errorFeedback errorSpan" id="stampError" > <?php echo $error_msgs['stamp']?> </span>
      <br />
      <label for="invite_cnt">Invites: </label>
        <input type="number" id="invite_cnt" name="invite_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_invite_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="invite_cntError" > <?php echo $error_msgs['invite_cnt']?> </span>
      <br />
      <br />
      <label for="yes_cnt">Yesses: </label>
        <input type="number" id="yes_cnt" name="yes_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_yes_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="yes_cntError" > <?php echo $error_msgs['yes_cnt']?> </span>
      <br />
      <label for="maybe_cnt">maybes: </label>
        <input type="number" id="maybe_cnt" name="maybe_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_maybe_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="maybe_cntError" > <?php echo $error_msgs['maybe_cnt']?> </span>
      <br />
      <label for="no_cnt">nos: </label>
        <input type="number" id="no_cnt" name="no_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_no_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="no_cntError" > <?php echo $error_msgs['no_cnt']?> </span>
      <br />
      <label for="flake_cnt">flakes: </label>
        <input type="number" id="flake_cnt" name="flake_cnt" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_flake_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="flake_cntError" > <?php echo $error_msgs['flake_cnt']?> </span>
      <br />
      <label for="score">score: </label>
        <input type="number" id="score" name="score" size="2" maxsize="4" 
              value="<?php echo "{$plyr->get_score()}"; ?>" >
        <span class="errorFeedback errorSpan" id="scoreError" > <?php echo $error_msgs['score']?> </span>
      <br />
-->
  </div>
<?php
if ($debug) { echo "include:" . __FILE__ . ";^^^^^^^.<br>"; }
# ***** Show the form ***** #
# ************************* #

