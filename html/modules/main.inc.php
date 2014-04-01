<?php dbg("+".basename(__FILE__).""); ?>

<?php 
post_dump();
session_dump();
//$x = $_SESSION['dbug'] ? "checked" : "not" ; echo $x;
//echo $_SESSION['dbug'] ? "checked" : "not";
if ($local) {
?>


<div class="field form-inline radio">
  <label class="radio" for="dbug">Debug: </label><p>
  <input class="radio" type="radio" name="dbug" value="off"  
     <?php echo $_SESSION['dbug'] ? "" : "checked"; ?>
    /> <span>Off</span><p>
  <input class="radio" type="radio" name="dbug" value="on" 
     <?php echo $_SESSION['dbug'] ? "checked" : ""; ?>
    /> <span>On</span><p>
</div>
 <input type='hidden' name='from_page_id' value='main_form'>
</p>
 <p>
<?php 
}
?>
<?php dbg("-include:".__FILE__.""); ?>

