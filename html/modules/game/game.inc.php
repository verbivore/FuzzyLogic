<?php
if ($debug = TRUE) { echo "include file=game.inc.php.<br>"; }
try {
  # look at games table structure
  $q = $pokdb->prepare("SELECT DISTINCT game_id FROM games");
  $q->execute();
  $game_count = $q->rowCount();
  $q = $pokdb->prepare("DESCRIBE games");
  $q->execute();
  $table_columns = $q->fetchAll(PDO::FETCH_COLUMN);
  /* Select queries return a resultset */
  $query="SELECT * FROM games";
  $player_list = $pokdb->query($query);
  $row_count = $player_list->rowCount();
  echo $row_count.' rows selected.<br/>';
  echo "player_list var dump: ";
  var_dump($player_list);
  echo ".<br/>\n";

  echo "<br/>\n  *** Dump games table *** ($game_count games, {$player_list->rowCount()} entries)<br/>\n";
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
    echo "<td>" . $row['game_date'] . "</td>";
    echo "<td>" . $row['player_snack'] . "</td>";
    echo "<td>" . $row['player_host'] . "</td>";
    echo "<td>" . $row['player_gear'] . "</td>";
    echo "<td>" . $row['player_caller'] . "</td>";
    echo "<td>" . $row['stamp'] . "</td>";
    echo "</tr>";
  }
  echo "</table>";
  echo ".<br/>\n";

} catch (PDOException $e) {
  echo "PDO Exception: ".$e->getCode().": ".$e->getMessage()."<br/>";  
} catch (Exception $e) {
  echo "Exception: ".$e->getCode().": ".$e->getMessage()."<br/>";  
}
echo "<br/>\n";
?>
