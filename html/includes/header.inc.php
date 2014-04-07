<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
<!-- 
  -  Common page header for site.
  -  Show site page header and main menu.
  -  File name: header.inc.php
  -  @author David Demaree <dave.demaree@yahoo.com>
  --- History ---  
  - 14-03-23 Added dbg().  DHD
  - 14-03-08 Original.  DHD
  -->
<!-- not supported yet :(  <link rel="import" href="../inc/headmeta.html"> -->
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <title><?php echo "{$page_title}";?></title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo "{$page_file_js}";?>"></script>
    <link rel="stylesheet" type="text/css" href="style/poker.css">
  </head>
  <body>
<?php dbg("+".basename(__FILE__)."(in progress)"); ?> 
<?php dbg("=".basename(__FILE__).":host=$host; local=$local; dbug={$_SESSION['dbug']}; counter={$_SESSION['counter']}; start={$_SESSION['startTime']}; HTTP_HOST={$_SERVER['HTTP_HOST']}"); ?> 

    <form id="pokerMain" method="POST" action="<?php $self ?>">
      <input type="submit" id="main" name="main" value="Main" >
      <input type="submit" id="play" name="play" value="Players" >
      <input type="submit" id="game" name="game" value="Games" >
      <input type="submit" id="seat" name="seat" value="Join" >
      <input type="submit" id="invt" name="invt" value="Invite" >
    <br>
<?php 
//post_dump();
//session_dump();
dbg("-".basename(__FILE__).""); 
?>

