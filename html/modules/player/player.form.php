<?php  # player.form.php
/**
 * Shows the html form for a single player
 * File name: player.form.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-04-05 Added combobox for status.  DHD
 * 14-04-02 Added $_POST['from_page_id'].  DHD
 * 14-03-23 Added dbg().  DHD
 * 14-03-18 Changed stats fields to readonly.  DHD
 * 14-03-08 Original.  DHD
 * Future:
 *  Format score, right-justify stats
 */
dbg("+".basename(__FILE__));

    $players = new PlayerArray;
    dbg("".__FUNCTION__.":count={$players->playerCount}:" . count($players->playerList) . "");
#  $players->listing();
#  $players->sortNick();
//    usort($players->playerList, array('PlayerArray','sortNick')); 
//    usort($players->playerList, array('PlayerArray','sortScore')); 



/**
 * List of values for player Status
 */
define ("PLAYER_STATUS_OPTIONS", serialize (array ("A", "X")));
$STAT_OPTS = unserialize (PLAYER_STATUS_OPTIONS);
//for ($i=0; $i<sizeof($STAT_OPTS); $i++) {
//    echo $STAT_OPTS[$i] . "<br>";
//}

# set banner message and style
$message_banner = "{$error_msgs['errorDiv']}";
if ("{$error_msgs['count']}" != "0") {
    $message_class = "errorClass";
    $message_banner .= " ({$error_msgs['count']}).";
} else {
    $message_class = "infoClass";
}

dbg("=".basename(__FILE__).";ID={$plyr->get_member_id()}:{$message_banner}");

# ************************* #
# ***** Show the form ***** #

?>
  <div>
    <fieldset>
      <legend> Player </legend>
      <div id="errorDiv" <?php echo "class={$message_class} >{$message_banner}"; ?> </div> 

      <fieldlabel for="member_id">ID:* </fieldlabel>
        <select id="member_id" name="member_id" size="2" maxsize="2">
<?php
foreach ($players->playerList as $row) {
    $list_mem_id = $row->get_member_id();
    echo "          <option value='" . $list_mem_id . "'";
    if ($list_mem_id == $plyr->get_member_id()) {
        echo " selected='selected'";
    }
    echo ">" . $row->get_member_id() . "</option>\n";
}

if ($page_id == 'play-new' || $page_id == 'play-burp') {
    echo "          <option value='" . "{$plyr->get_member_id()}" . "'";
    echo " selected='selected'>" . "{$plyr->get_member_id()}" . "</option>\n";
    
}

/*              value="<?php echo "{$plyr->get_member_id()}"; ?>" >*/
?>
        </select>
        <span class="errorFeedback errorSpan" id="member_idError" > 
                    <?php echo $error_msgs['member_id'];?> </span>
      <br />

      <fieldlabel for="nickname">Nickname:* </fieldlabel>
        <input type="text" id="nickname" name="nickname" size="10" maxsize="10"
              value="<?php echo "{$plyr->get_nickname()}"; ?>" >
<?php
/*
# future: update the other input fields when this field selection changes

        <select id="nickname" name="nickname" size="10" maxsize="10"> 
foreach ($players->playerList as $row) {

//for ($i=0; $i<sizeof($STAT_OPTS); $i++) {
//    $curr_opt = "{$plyr->get_member_id()}";
    $list_mem_id = $row->get_member_id();
//    $list_mem_name = $row->get_nickname();
//    $stat_opt = $STAT_OPTS[$i];
    echo "          <option value='" . $list_mem_id . "'";
    if ($list_mem_id == $plyr->get_member_id()) {
        echo " selected='selected'";
    }
    echo ">" . $row->get_nickname() . "</option>
";
}
        </select>
*/
?>
        <span class="errorFeedback errorSpan" id="nicknameError" > 
                    <?php echo $error_msgs['nickname'];?> </span>
      <br />

      <fieldlabel for="name_first">First name:* </fieldlabel>
        <input type="text" id="name_first" name="name_first" size="10" maxsize="20" 
              value="<?php echo "{$plyr->get_name_first()}"; ?>" title="First Name..." >
        <span class="errorFeedback errorSpan" id="name_firstError" > 
                    <?php echo $error_msgs['name_first'];?> </span>
      <br />

      <fieldlabel for="name_last">Last Name:* </fieldlabel>
        <input type="text" id="name_last" name="name_last" size="10" maxsize="20" 
              value="<?php echo "{$plyr->get_name_last()}"; ?>" >
        <span class="errorFeedback errorSpan" id="name_lastError" > 
                    <?php echo $error_msgs['name_last'];?> </span>

      <br />
      <fieldlabel for="status">Status:* </fieldlabel>
        <select id="status" name="status" size="2" maxsize="4">
<?php
for ($i=0; $i<sizeof($STAT_OPTS); $i++) {
//    echo  . "<br>";
    $curr_opt = "{$plyr->get_status()}";
    $stat_opt = $STAT_OPTS[$i];
    echo "          <option value='" . $stat_opt . "'";
    if ($stat_opt == $curr_opt) {
        echo " selected='selected'";
    }
    echo ">" . $stat_opt . "</option>
";
}
/*          <option value="<?php echo "{$plyr->get_status()}";?>">X</option>
//              value="<?php echo "{$plyr->get_status()}"; ?>" >
*/
?>
        </select>
        <span class="errorFeedback errorSpan" id="statusError" > 
                    <?php echo $error_msgs['status'];?> </span>
      <br />

      <fieldlabel for="email">Email:* </fieldlabel>
        <input type="number" id="email" name="email" size="20" maxsize="50"  
              value="<?php echo "{$plyr->get_email()}"; ?>" >
        <span class="errorFeedback errorSpan" id="emailError" > 
                    <?php echo $error_msgs['email'];?> </span>
      <br />

      <fieldlabel for="phone">Phone Number:* </fieldlabel>
        <input type="number" id="phone" name="phone" size="8" maxsize="8"  
              value="<?php echo "{$plyr->get_phone()}"; ?>" >
        <span class="errorFeedback errorSpan" id="phoneError" > 
                    <?php echo $error_msgs['phone'];?> </span>

      <br />

      <fieldlabel for="invite_cnt">Invites: </fieldlabel>
        <input type="number" id="invite_cnt" name="invite_cnt" size="2" maxsize="2" readonly="true" 
              value="<?php echo "{$plyr->get_invite_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="invite_cntError" > 
                    <?php echo $error_msgs['invite_cnt'];?> </span>
      <br />

      <fieldlabel for="yes_cnt">Yesses: </fieldlabel>
        <input type="number" id="yes_cnt" name="yes_cnt" size="2" maxsize="2" readonly="true" 
              value="<?php echo "{$plyr->get_yes_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="yes_cntError" > 
                    <?php echo $error_msgs['yes_cnt'];?> </span>
      <br />

      <fieldlabel for="maybe_cnt">Maybes: </fieldlabel>
        <input type="number" id="maybe_cnt" name="maybe_cnt" size="2" maxsize="2" readonly="true" 
              value="<?php echo "{$plyr->get_maybe_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="maybe_cntError" > 
                    <?php echo $error_msgs['maybe_cnt'];?> </span>
      <br />

      <fieldlabel for="no_cnt">Nos: </fieldlabel>
        <input type="number" id="no_cnt" name="no_cnt" size="2" maxsize="2" readonly="true" 
              value="<?php echo "{$plyr->get_no_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="no_cntError" > 
                    <?php echo $error_msgs['no_cnt'];?> </span>
      <br />

      <fieldlabel for="flake_cnt">Flakes: </fieldlabel>
        <input type="number" id="flake_cnt" name="flake_cnt" size="2" maxsize="2" readonly="true" 
              value="<?php echo "{$plyr->get_flake_cnt()}"; ?>" >
        <span class="errorFeedback errorSpan" id="flake_cntError" > 
                    <?php echo $error_msgs['flake_cnt'];?> </span>
      <br />

      <fieldlabel for="score">Score: </fieldlabel>
        <input type="number" id="score" name="score" size="2" maxsize="4" readonly="true" 
              value="<?php echo number_format($plyr->get_score(),2);?>" >
        <span class="errorFeedback errorSpan" id="scoreError" > 
                    <?php echo $error_msgs['score'];?> </span>
      <br />

      <fieldlabel for="stamp">Stamp: </fieldlabel>
        <input type="number" id="stamp" name="stamp" size="10" maxsize="20" readonly="true" 
              value="<?php echo "{$plyr->get_stamp()}"; ?>" >
        <span class="errorFeedback errorSpan" id="stampError" > 
                    <?php echo $error_msgs['stamp'];?> </span>
      <br />

    </fieldset>
  </div>
<?php
//$_POST['stamp'] = $plyr->get_stamp();
# Save the form name so that the next page knows what to expect in $_POST
echo "<input type='hidden' name='from_page_id' value='play-form'>";
dbg("-".basename(__FILE__));
# ***** Show the form ***** #
# ************************* #
?>
