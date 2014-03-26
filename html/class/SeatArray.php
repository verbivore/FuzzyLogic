<?php
/**
 *  Class definition for an array of seats
 *  @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-23 Cloned from Game.  DHD
 * Future:
 */
dbg("+".basename(__FILE__)."");

class SeatArray extends seat
{
    public $seatList = array();
    public $seatCount;

    function __construct($gameId=0)
    {
        dbg("+".__METHOD__."=$gameId");
        $this->seatCount = 0;
        try {
# Open poker database
require(BASE_URI . "includes/pok.open.inc.php");
            # get number of seats
            $query = "SELECT * FROM seats ";
            if ($gameId > 0) {
                $query .= "WHERE game_id = \"$gameId\" ";
            }
            $query .= "ORDER BY game_id ASC, member_id ASC ";
            dbg("=".__METHOD__."=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            $this->seatCount = $row_count;
            dbg("=".__METHOD__.";seats=$this->seatCount");
            $result = $stmt->fetchAll(); 
                $loaded = 0;
//print_r($result[0]);
                for ($i=0; $i < $this->seatCount; $i++) {
                    $this->seatList[$i] = new Seat;
                    $this->seatList[$i]->set_game_id($result[$i]['game_id']);
                    $this->seatList[$i]->set_member_id($result[$i]['member_id']);
                    $this->seatList[$i]->set_response($result[$i]['response']);
                    $this->seatList[$i]->set_note_member($result[$i]['note_member']);
                    $this->seatList[$i]->set_note_master($result[$i]['note_master']);
                    $this->seatList[$i]->set_stamp($result[$i]['stamp']);
                        $loaded++;
                        # set up next iteration
                } 
        } catch (PDOException $e) {
            echo "PDO Exception: " . __FILE__ . " line: " . __LINE__ . "<br/>";
            echo $e->getCode() . ": " . $e->getMessage() . "<br/>";  
        } catch (Exception $e) {
            echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br/>";  
        }
# if loaded <> seatCount???
        dbg("-".__METHOD__."=$gameId;$this->seatCount");
    }
/*
public static function sortNick() 
{
    usort(seatList, array('seatList','nickSort'));
}
*/

/**
 * Sort by member_id
 */
    public static function sortMember($a, $b) 
    {
        return ($a->member_id > $b->member_id);
    }

/**
 * Sort by game
 */
    public static function sortGame($a, $b) 
    {
        return ($a->game_id < $b->game_id);
    }

/**
 * Dump in table format
 */
    public function listing()
    {
        dbg("=".__METHOD__."");

        echo "*** Dump seats *** ({$this->seatCount} seats)<br>";
        echo "<table border='1'>";
        echo "<th>Game ID</th>";
        echo "<th>Member ID</th>";
        echo "<th>Response</th>";
        echo "<th>Member Note</th>";
        echo "<th>Notes</th>";
        echo "<th>Stamp</th>";
        echo "</tr>";
    
        $counter = 0;
        dbg("=".__METHOD__.";listing count={$this->seatCount}:".count($this->seatList)."");
    #  echo "SeatArray:listing [0]="; $this[0]->listRow(); echo ".<br>";
        foreach ($this->seatList as $row) {
            $counter++;
//      dbg("=".__METHOD__."seat {$row->get_game_id()} ($counter of {$this->seatCount})<br>");
#      $row->listRow();
            echo "<tr>";
            echo "<td>" . $row->get_game_id() . "</td>";
            echo "<td>" . $row->get_member_id() . "</td>";
            echo "<td>" . $row->get_response() . "</td>";
            echo "<td>" . $row->get_note_member() . "</td>";
            echo "<td>" . $row->get_note_master() . "</td>";
            echo "<td>" . $row->get_stamp() . "</td>";
            echo "</tr>";
        }
        echo "</table>";
#    echo ".<br>";
    }
//******************************************************************************
} # end class SeatArray
//******************************************************************************
dbg("-".basename(__FILE__)."");
?>
