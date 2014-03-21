<!doctype html>
<?php
#Future: create file documentation header
#Future: null Termination Date displays as 0000-00-00; change it to blank.
#Future: validate (term date =) effective date >= hire date > birth date.
#Future: screen hop on errorDiv
#Future: error message class: field(array), banner, error count, warning count.
#Future:
#Future:
#Future:
#Future:
# 14-02-26 DHD Replaced radio buttons with regular buttons for Add/Update, Prev, Next, Burp.
# 14-02-27 DHD Setting form message to "player added/eff rec added".  Differentiating errorClass vs infoClass.
//******************************************************************************
// Housekeeping                                                            
//******************************************************************************
require_once("../inc/once.php"); #
require_once("../inc/player_class.php");
require_once("../inc/testdata_class.php");

if ($debug) { echo "plyr:start:post="; post_dump(); echo "<br>"; }

# player record to be used for this page
$plyr = new player;
# list of form fields to process
$form_fields = array("player_id", "name_last", "name_first", "nickname", "stamp");

# Initialize error message fields
# Create an associative array of form fields to hold error messages, initialized to ""
$error_msgs = array();
$error_msgs['count'] = 0;
$error_msgs['errorDiv'] = "";
foreach ($form_fields as $field) {
    $error_msgs["$field"] = "";
}

#if ($debug) { foreach ($error_msgs as $col => $val) { echo "plyr.error_field=$col:$val.<br>"; } }

# Determine which action has been requested
if (isset($_POST['prev'])) {
    plyrFind("Previous");
} elseif (isset($_POST['updt'])) {
    plyrUpdate();
} elseif (isset($_POST['next'])) {
    plyrFind("Next");
} elseif (isset($_POST['burp'])) {
    plyrTest();
} else {
    plyrNew();
}
if ($debug) { echo "plyr={$plyr->get_player_id()}:{$error_msgs['count']}:{$error_msgs['errorDiv']}.<br>"; }

# set banner message and style
$message_banner = "{$error_msgs['errorDiv']}";
if ("{$error_msgs['count']}" != "0") {
    $message_class = "errorClass";
    $message_banner .= " ({$error_msgs['count']}).";
} else {
    $message_class = "infoClass";
}

if ($debug) { echo "plyr ID={$plyr->get_player_id()}:{$message_banner}.<br>"; }

?>

<html>
  <head>
<!-- not supported yet :(  <link rel="import" href="../inc/headmeta.html"> -->
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <title>Village Roots | player</title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="plyr.js"></script>
    <link rel="stylesheet" type="text/css" href="plyr.css">
</head>
  <body>
    <form id="plyrForm" method="POST" action="<?php $self ?>">
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
            <label for="name_first">First name:* </label>
                <input type="text" id="name_first" name="name_first" size="10" maxsize="20" value="<?php echo "{$plyr->get_name_first()}"; ?>" title="First Name..." >
                <span class="errorFeedback errorSpan" id="name_firstError" > <?php echo $error_msgs['name_first']?> </span>
            <br />
            <label for="name_last">Last Name:* </label>
                <input type="text" id="name_last" name="name_last" size="10" maxsize="20" 
                            value="<?php echo "{$plyr->get_name_last()}"; ?>" >
                <span class="errorFeedback errorSpan" id="name_lastError" > <?php echo $error_msgs['name_last']?> </span>
            <br />
            <label for="nickname">Birth date:* </label>
                <input type="date" id="nickname" name="nickname" size="10" maxsize="10" value="<?php echo "{$plyr->get_nickname()}"; ?>" >
                <span class="errorFeedback errorSpan" id="nicknameError" > <?php echo $error_msgs['nickname']?> </span>
            <br />
            <label for="stamp">Hire date:* </label>
                <input type="date" id="stamp" name="stamp" size="10" maxsize="10" value="<?php echo "{$plyr->get_stamp()}"; ?>" >
                <span class="errorFeedback errorSpan" id="stampError" > <?php echo $error_msgs['stamp']?> </span>
            <br />
            <label for="term_date">Termination date: </label>
                <input type="date" id="term_date" name="term_date" size="10" maxsize="10" value="<?php echo "{$plyr->get_term_date()}"; ?>" >
                <span class="errorFeedback errorSpan" id="term_dateError" > <?php echo $error_msgs['term_date']?> </span>
            <br />
            <label for="eff_date">Effective date:*</label> 
                <input type="date" id="eff_date" name="eff_date" size="10" maxsize="10" value="<?php echo "{$plyr->get_eff_date()}"; ?>" >
                <span class="errorFeedback errorSpan" id="eff_dateError" > <?php echo $error_msgs['eff_date']?> </span>
            <br />
            <label for="pay_rate">Hourly rate: </label>
                <input type="number" id="pay_rate" name="pay_rate" size="5" maxsize="5" value="<?php echo "{$plyr->get_pay_rate()}"; ?>" >
                <span class="errorFeedback errorSpan" id="pay_rateError" > <?php echo $error_msgs['pay_rate']?> </span>
            <br />
            <label for="fed_status">Marital status (fed): </label>
                <input type="number" id="fed_status" name="fed_status" size="1" maxsize="1"  value="<?php echo "{$plyr->get_fed_status()}"; ?>" >
                <span class="errorFeedback errorSpan" id="fed_statusError" > <?php echo $error_msgs['fed_status']?> </span>
            <br />
            <label for="fed_allow">Allowances (fed): </label>
                <input type="number" id="fed_allow" name="fed_allow" size="1" maxsize="2" value="<?php echo "{$plyr->get_fed_allow()}"; ?>" >
                <span class="errorFeedback errorSpan" id="fed_allowError" > <?php echo $error_msgs['fed_allow']?> </span>
            <br />
            <label for="state_status">Marital status (state): </label>
                <input type="number" id="state_status" name="state_status" size="1" maxsize="1" value="<?php echo "{$plyr->get_state_status()}"; ?>" >
                <span class="errorFeedback errorSpan" id="state_statusError" > <?php echo $error_msgs['state_status']?> </span>
            <br />
            <label for="state_allow">Allowances (state): </label>
                <input type="number" id="state_allow" name="state_allow" size="1" maxsize="2" value="<?php echo "{$plyr->get_state_allow()}"; ?>" >
                <span class="errorFeedback errorSpan" id="state_allowError" > <?php echo $error_msgs['state_allow']?> </span>
            <p>Action:

<!-- js uses id; php uses name (eg:$_POST['prev']) -->    
            <input type="submit" id="prev" name="prev" value="Previous" >
            <input type="submit" id="updt" name="updt" value="Add/Update" >
            <input type="submit" id="next" name="next" value="Next" >
            <input type="submit" id="burp" name="burp" value="burp" >

<!--
-->

<script type="text/javascript">
$("plyrForm input[type=submit]").click(function() {
        $("input[type=submit]", $(this).parents("plyrForm")).removeAttr("clicked");
        $(this).attr("clicked", "true");
});
</script>

        </fieldset>
    </div>
    </form>
<?php
//******************************************************************************
// Closing    ??? any reason for this ???                                                          
//******************************************************************************
# Unset unused global/session variables
#  if ($debug) { echo "plyr:closing.<br>"; }

#  if ($debug) { echo "plyr:closed.<br>"; }
?>

<?php
//******************************************************************************
// First Pass                                                              
//******************************************************************************
# Get ready to add a new player
function plyrNew() {
    # declare globals
    global $plyr, $error_msgs;
    if ($debug) { echo "plyr:plyrNew.<br>"; }

    # Get the next available player id number
    $plyr->get_next_id();
    $error_msgs['errorDiv'] = "Add new player:";

    if ($debug) { echo "plyr:plyrNew:end={$plyr->get_player_id()}.<br>"; }
}

function plyrUpdate() {
//******************************************************************************
// Add or Update                                                           
//******************************************************************************
    if ($debug) { echo "plyr:plyrUpdate={$_POST['player_id']}.<br>"; }

    # declare globals
    global $plyr, $error_msgs;

    $plyr->set_to_POST();   # initialize player with data from $_POST

    plyrValidate();
//  if ($debug) { echo "plyr:plyrUpdate={$plyr->get_player_id()}:{$error_msgs['count']}.<br>"; }
    if ($error_msgs['count'] == 0) {
        try {
            # is this an insert or an update?
            $row_count = $plyr->find();
            if($row_count == 0) {  
                if ($debug) { echo "plyr:plyrUpdate:inserting:{$plyr->get_player_id()}. <br>"; }
                $plyr->insert();
            } elseif($row_count == 1) {
                if ($debug) { echo "plyr:plyrUpdate:updating:{$plyr->get_player_id()}. <br>"; }
                $plyr->update();
            } else {
                $e = new Exception("Multiple ($row_count) player ({$plyr->get_player_id()}) records for effective date ({$plyr->get_eff_date()}).", 20000);
                throw new Exception($e);
            }
        }
        catch (playerException $d) {
            switch ($d->getCode()) {
            case 2110:
                $error_msgs['eff_date'] = "player ({$plyr->get_player_id()}) with this effective date already exists. ({$d->getCode()})";
                $error_msgs['errorDiv'] = "See errors below";
                $error_msgs['count'] += 1;
                break;
            case 2104: # Column validation failed before insert/update
                $err_list = array();
                $err_list[] = array();
                $error_msgs['errorDiv'] = $d->getMessage() . " (2104)";
                $err_list = $d->getOptions();
                if ($debug) { echo "plyr:plyrUpdate arraysize="; echo sizeof($err_list); echo ".<br>"; }
                foreach ($err_list as $col => $val) {
//          echo "plyr.update errors=$col:$val[0]:$val[1].<br>";
                    $error_msgs["$col"] = $val[1];
                    $error_msgs['count'] += 1;
                    if ($debug) { echo "plyr:plyrUpdate err col=$col:{$error_msgs["$col"]}.<br>"; }
/*
                    $errMsgField="$col" . "ErrorMsg";
                    ${$errMsgField} = $val[1];
                    if ($debug) { echo "plyr:plyrUpdate errMsgField=$errMsgField:${$errMsgField}.<br>"; }
*/
                }
                break;
            default:
                echo "plyr insert/update failed:plyr->get_player_id():" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
                $p = new Exception($d->getPrevious());
                echo "plyr Previous exception:plyr->get_player_id():" . $p->getMessage() . ".<br>";
                throw new Exception($p);
            }
#      if ($d->getCode() > 0) {  # Assume that message is user-friendly
#      } else {  # Undefined error
        } 
    } 
    $_POST["ID"] = $plyr->get_player_id();
    if ($error_msgs['count'] == 0) {
        if($row_count == 0) {  
            $error_msgs['errorDiv'] = "player record added.";
        } else {
            $error_msgs['errorDiv'] = "player record updated.";
        }
    }

    if ($debug) { echo "plyr:plyrUpdate:end={$plyr->get_player_id()}:{$_POST['eff_date']}.<br>"; }
}


function plyrValidate() {
//******************************************************************************
// Validate player data                                                   
//******************************************************************************
    if ($debug) { echo "plyr:plyrValidate={$_POST['player_id']}.<br>"; }

    # declare globals
    global $plyr, $form_fields, $error_msgs;

        # validate fields
        foreach ($form_fields as $field) {
            try {
                $func = "validate_$field";
//        if ($debug) { echo "plyr:plyrUpdate:validate fields={$func}.<br>"; }
                $plyr->$func();
            }
            catch (playerException $e) {
                if ($debug) { echo "plyr:plyrValidate error={$e->getMessage()}.<br>"; }
                $error_msgs["$field"] = $e->getMessage();
                $error_msgs['count'] += 1;
//      $error_msgs['errorDiv'] = "See errors below";
            }
        }
#    session_dump();


    if ($debug) { echo "plyr:plyrValidate:end={$plyr->get_player_id()}:{$error_msgs['count']}.<br>"; }

}

function plyrFind($findType) {
//******************************************************************************
// Find                                                                    
//******************************************************************************
    if ($debug) { echo "plyr:plyrFind={$_POST['player_id']}:{$_POST['eff_date']}.<br>"; }
#post_dump();

    # declare globals
    global $plyr, $error_msgs;

    # Look for ee by id and eff date
    $plyr->set_player_id($_POST['player_id']);
    $plyr->set_eff_date($_POST['eff_date']);
    if ($debug) { echo "plyr finding:{$plyr->get_player_id()}:{$plyr->get_eff_date()}. <br>"; }
    try {  #Future: switch/case?
        if ($findType == "Next") {
            $plyr->get("N");
        } elseif ($findType == "Previous") {
            $plyr->get("P");
        } else {
            echo "plyr:plyrFind:Unknown action:$findType.<br>";
            $e = new Exception("plyr:plyrFind:Unknown action:$findType.");
            throw new Exception($e);
        }
    }
    catch (playerException $d) {
        #echo "plyr get failed:{$plyr->get_player_id()}.<br>";
        switch ($d->getCode()) {
        case 2210:
            $error_msgs['player_id'] = "player not found. ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See errors below";
            $error_msgs['count'] += 1;
            break;
        case 2220: # requested date is later than latest effective record
            $error_msgs['eff_date'] = "No record found later than {$plyr->get_eff_date()}. ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See error below";
            $error_msgs['count'] += 1;
            break;
        case 2221: # requested date is equal to the latest effective record
            $error_msgs['eff_date'] = "Latest effective date found:{$plyr->get_eff_date()}. ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See warning below";
            $error_msgs['count'] += 1;
            break;
        case 2230:
            $error_msgs['eff_date'] = "No record found earlier than {$plyr->get_eff_date()}. ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See error below";
            $error_msgs['count'] += 1;
            break;
        case 2231:
            $error_msgs['eff_date'] = "Earliest effective date found:{$plyr->get_eff_date()}. ({$d->getCode()})";
            $error_msgs['errorDiv'] = "See warning below";
            $error_msgs['count'] += 1;
            break;
        default:
            echo "plyr find failed:plyr->get_player_id():" . $d->getMessage() . ":" . $d->getCode() . ".<br>";
            $p = new Exception($d->getPrevious());
            echo "plyr Previous exception:plyr->get_player_id():" . $p->getMessage() . ".<br>";
            throw new Exception($p);
        }
    }
    $_POST["ID"] = $plyr->get_player_id();
    if ($error_msgs['count'] == 0) {
        $error_msgs['errorDiv'] = "player found.";
    }

    if ($debug) { echo "plyr:plyrfind:end={$plyr->get_player_id()}:{$error_msgs['count']}:{$error_msgs['errorDiv']}.<br>"; }
}

function plyrTest() {
//******************************************************************************
// Create some test data                                                   
//******************************************************************************
    if ($debug) { echo "plyr:plyrTest={$_POST['player_id']}.<br>"; }

    # declare globals
    global $plyr, $error_msgs;
    $plyr->testData();
    $error_msgs['errorDiv'] = "Test player created.  Press \"Add/Update\" to add the player.";

    if ($debug) { echo "plyr:plyrTest:end={$plyr->get_player_id()}.<br>"; }

}
?>

</body>
</html>
