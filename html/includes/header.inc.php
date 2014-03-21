<!-----------------------------------------------------------------------------
  -  File name: header.inc.php
  -  @author David Demaree <dave.demaree@yahoo.com>
  -  Contact: dave.demaree@yahoo.com
  -  Purpose: Set constants and error handler
  --- History ---  
  - 14-03-08 Original.  DHD
  ----------------------------------------------------------------------------->
<html>
  <head>
<!-- not supported yet :(  <link rel="import" href="../inc/headmeta.html"> -->
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <title><?php echo "{$page_title}";?></title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/poker.css">
  </head>
  <body>
<?php if ($debug) { echo "file:" . __FILE__ . ";>>>>>>>.<br>"; } ?>
<?php if ($debug) { echo "host=$host; local=$local; debug=$debug; counter={$_SESSION['counter']}; start={$_SESSION['startTime']}; HTTP_HOST={$_SERVER['HTTP_HOST']}.<br>"; } ?> 

    <form id="pokerMain" method="POST" action="<?php $self ?>">
      <input type="submit" id="main" name="main" value="Main" >
      <input type="submit" id="play" name="play" value="Players" >
      <input type="submit" id="game" name="game" value="Games" >
      <input type="submit" id="join" name="join" value="Join" >
    <br>
<?php if ($debug) { echo "file:" . __FILE__ . ";^^^^^^^.<br>"; } ?>


