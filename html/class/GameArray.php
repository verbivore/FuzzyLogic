<?php
/**
 *  Class definition for an array of game
 *  @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-23 Added dbg() function.  DHD
 * 14-03-20 Updated __construct to ignore 2210.  DHD
 * 14-03-18 Changed name to title case.  DHD
 * 14-03-08 Original.  DHD
 * Future:
 * Incorporate games stats to game game_id.
 */
dbg("+".basename(__FILE__)."");

class GameArray  extends Game
{
    public $gameList = array();
    public $gameCount;

    function __construct()
    {
        dbg("=".__METHOD__."");
        $this->gameCount = 0;
        try {
# Open poker database
require(BASE_URI . "includes/pok.open.inc.php");
            # get number of games
            $query = "SELECT COUNT(game_id) AS gameCount FROM games ";
            dbg("=".__METHOD__.";query=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch();
            dbg("=".__METHOD__.";gameCount={$row['gameCount']}");
            $this->gameCount = $row['gameCount'];
            # future:  fail if empty???
            # get games row
            $query = "SELECT MIN(game_id) AS first FROM games ";
            dbg("=".__METHOD__.";next:query=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            if ($row_count == 1) {
                $row = $stmt->fetch();
                dbg("=".__METHOD__.";first={$row['first']}");
                $next_game_id = $row['first'];
                $loaded = 0;
                for ($i=0; $i < $this->gameCount; $i++) {
#                for ($i=0; $i < 3; $i++) {
                    $this->gameList[$i] = new Game;
                    $this->gameList[$i]->set_game_id($next_game_id);
                    # Save row
                    try {
                        $this->gameList[$i]->get("");
                    } catch (gameException $d) {
                        switch ($d->getCode()) {
                        default:
                            dbg("=".__METHOD__.";gamz find failed:{$this->gameList[$i]->get_game_id()}:" . $d->getMessage() . ":" . $d->getCode() . "");
                            $p = new Exception($d->getPrevious());
                            dbg("=".__METHOD__.";gamz Previous exception:{$this->gameList[$i]->get_game_id()}:" . $p->getMessage() . "");
                            throw new Exception($p);
                        }
                    }
                        # Bug: 
                        $loaded++;
                        # set up next iteration
                        $query = "SELECT MIN(game_id) AS next FROM games " . 
                                 "WHERE game_id > {$next_game_id} ";
#                        dbg("=".__METHOD__."get:query=$query");
                        $stmt = $pokdb->prepare($query);
                        $stmt->execute();
                        $row = $stmt->fetch();
                        $next_game_id = $row['next'];
                } 
            } else {
                dbg("=".__METHOD__.";rows=$row_count");
            }
        } catch (PDOException $e) {
            echo "PDO Exception: " . __FILE__ . " line: " . __LINE__ . "<br/>";
            echo $e->getCode() . ": " . $e->getMessage() . "<br/>";  
        } catch (Exception $e) {
            echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br/>";  
        }
# if loaded <> gameCount???
    }
/*
public static function sortNick() 
{
    usort(gameList, array('gameList','nickSort'));
}
*/

/**
 * Sort by game_date
 */
    public static function sortDate($a, $b) 
    {
        return strcmp($a->game_date, $b->game_date);
    }

/**
 * Sort by game_id
 */
    public static function sortGameId($a, $b) 
    {
        return ($a->game_id < $b->game_id);
    }

/**
 * Dump in table format
 */
    public function listing()
    {
        dbg("=".__METHOD__."");

        echo "*** Dump games *** ({$this->gameCount} games)<br>";
        echo "<table border='1'>";
        echo "<th>ID</th>";
        echo "<th>Date</th>";
        echo "<th>Snack</th>";
        echo "<th>Host</th>";
        echo "<th>Gear</th>";
        echo "<th>Organizer</th>";
        echo "<th>Stamp</th>";
        echo "</tr>";
    
        $counter = 0;
        dbg("=".__METHOD__.";listing count={$this->gameCount}:".count($this->gameList)."");
    #  echo "GameArray:listing [0]="; $this[0]->listRow(); echo ".<br>";
        foreach ($this->gameList as $row) {
            $counter++;
//      dbg("=".__METHOD__."game {$row->get_game_id()} ($counter of {$this->gameCount})<br>");
#      $row->listRow();
            echo "<tr>";
            echo "<td>" . $row->get_game_id() . "</td>";
            echo "<td>" . $row->get_game_date() . "</td>";
            echo "<td>" . $row->get_member_snack() . "</td>";
            echo "<td>" . $row->get_member_host() . "</td>";
            echo "<td>" . $row->get_member_gear() . "</td>";
            echo "<td>" . $row->get_member_caller() . "</td>";
            echo "<td>" . $row->get_stamp() . "</td>";
            echo "</tr>";
        }
        echo "</table>";
#    echo ".<br>";
    }
//******************************************************************************
} # end class GameArray
//******************************************************************************
dbg("-".basename(__FILE__)."");
?>
