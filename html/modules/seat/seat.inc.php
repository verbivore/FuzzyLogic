<?php
if ($debug = TRUE) { echo "include file=seat.inc.php.<br>"; }
try {
  # look at seats table structure
  $q = $pokdb->prepare("SELECT * FROM seats");
  $q->execute();
  $seat_count = $q->rowCount();
  $q = $pokdb->prepare("DESCRIBE seats");
  $q->execute();
  $table_columns = $q->fetchAll(PDO::FETCH_COLUMN);
  /* Select queries return a resultset */
  $query="SELECT * FROM seats";
  $player_list = $pokdb->query($query);
  $row_count = $player_list->rowCount();
  echo $row_count.' rows selected.<br/>';
  echo "player_list var dump: ";
  var_dump($player_list);
  echo ".<br>";

  echo "<br>  *** Dump seats table *** ($seat_count seats, {$player_list->rowCount()} entries)<br>";
  echo "<table border='1'>";
  foreach( $table_columns as $title )
  {
    echo "<th>$title</th>";
  }
  echo "</tr>";
  foreach ($pokdb->query($query) as $row) {
    echo "<tr>";
    echo "<tr>";
    echo "<td>" . $row['game_id'] . "</td>";
    echo "<td>" . $row['player_id'] . "</td>";
    echo "<td>" . $row['response'] . "</td>";
    echo "<td>" . $row['note_player'] . "</td>";
    echo "<td>" . $row['note_mgr'] . "</td>";
    echo "<td>" . $row['stamp'] . "</td>";
    echo "</tr>";
  }
  echo "</table>";
  echo ".<br>";

} catch (PDOException $e) {
  echo "PDO Exception: ".$e->getCode().": ".$e->getMessage()."<br/>";  
} catch (Exception $e) {
  echo "Exception: ".$e->getCode().": ".$e->getMessage()."<br/>";  
}
echo "<br>";

echo "_SESSION var dump: ";
var_dump($_SESSION);
echo ".<br> <br>";

?> 

