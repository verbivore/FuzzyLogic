<?php
/**
 *  Class definition for an array of player
 *  @author David Demaree <dave.demaree@yahoo.com>
 *** History ***  
 * 14-04-06 Added player ranks.  DHD
 * 14-03-23 Added dbg() function.  DHD
 * 14-03-20 Updated __construct to ignore 2210.  DHD
 * 14-03-18 Changed name to title case.  DHD
 * 14-03-08 Original.  DHD
 * Future:
 * Incorporate games stats to player score.
 */
dbg("+".basename(__FILE__)."");

class PlayerArray extends player
{

    public $playerList = array();
    public $playerCount;

    function __construct()
    {
        dbg("=".__METHOD__."");
        $player_rank = array();
        $this->playerCount = 0;
        try {
# Open poker database
require(BASE_URI . "includes/pok.open.inc.php");
            # get number of players
            $query = "SELECT COUNT(member_id) AS playerCount FROM members ";
            dbg("=".__METHOD__.";query=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch();
            dbg("=".__METHOD__.";playerCount={$row['playerCount']}");
            $this->playerCount = $row['playerCount'];
            # future:  fail if empty???
            # get members row
            $query = "SELECT MIN(member_id) AS first FROM members ";
            dbg("=".__METHOD__.";next:query=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            if ($row_count == 1) {
                $row = $stmt->fetch();
                dbg("=".__METHOD__.";first={$row['first']}");
                $next_member_id = $row['first'];
                $loaded = 0;
                for ($i=0; $i < $this->playerCount; $i++) {
                    $this->playerList[$i] = new Player;
                    $this->playerList[$i]->set_member_id($next_member_id);
                    # Save row
                    try {
                        $this->playerList[$i]->get("");
                        dbg("=".__METHOD__.";Adding:$next_member_id={$this->playerList[$i]->get_score()}");
                        $player_rank[$next_member_id] = "{$this->playerList[$i]->get_score()}";
                    } catch (PokerException $d) {
                        switch ($d->getCode()) {
                        case Player::GET_WARN_NO_SEAT:  # no seats rows
                            dbg("=".__METHOD__.":".Player::GET_WARN_NO_SEAT.";No seats rows for={$this->playerList[$i]->get_member_id()}");
                            break;
                        default:
                            throw new PokerException("PlayerArray get failed:{$this->playerList[$i]->get_member_id()}",
                                                     GET_ERR_ARR,
                                                     $d);
                        }
                    }
                    # Bug: 
                    $loaded++;
                    # set up next iteration
                    $query = "SELECT MIN(member_id) AS next FROM members " . 
                             "WHERE member_id > {$next_member_id} ";
#                    dbg("=".__METHOD__."get:query=$query");
                    $stmt = $pokdb->prepare($query);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $next_member_id = $row['next'];
                } 
            } else {
                dbg("=".__METHOD__.";rows=$row_count");
            }
        } catch (PokerException $d) {
            switch ($d->getCode()) {
            case Player::GET_WARN_NO_SEAT:  # no seats rows
                dbg("=".__METHOD__.":".Player::GET_WARN_NO_SEAT.";No seats rows for={$this->playerList[$i]->get_member_id()}");
                break;
            default:
                throw new PokerException("PlayerArray get failed:{$this->playerList[$i]->get_member_id()}",
                                         GET_ERR_ARR_1ST,
                                         $d);
            }
        } catch (PDOException $e) {
            throw new PokerException("PlayerArray get failed:{$this->playerList[$i]->get_member_id()}",
                                     GET_ERR_ARR_PDO,
                                     $e);
        }
        # sort the array of scores descending to get rank
        arsort($player_rank,SORT_NUMERIC);
//print_r($player_rank); echo "<br>";
        $ranking = 0;
        $prev_score = 9999;
        $prev_rank = 0;
        foreach ($player_rank as $id => $rank) {
            $ranking++;
//            echo "Rank:{$id}={$rank}<br>";
            # Get playerList entry for this player
            for ($i=0; $i < $this->playerCount; $i++) {
                # Set player rank
                if ($this->playerList[$i]->get_member_id() == $id) {
                    # Give identical ranks to identical scores
                    if ($this->playerList[$i]->get_score() == $prev_score) {
                       $this_rank = $prev_rank;
                    } else {
                       $this_rank = $ranking;
                    }                  
                    $this->playerList[$i]->set_rank($this_rank);
//                    echo "id:{$this->playerList[$i]->get_member_id()}=$this_rank=$rank.<br>";
                    $prev_rank = $this_rank;
                    $prev_score = $this->playerList[$i]->get_score();
                    $i = $this->playerCount; # exit for loop
                }
            }
        }
# if loaded <> playerCount???
    }
/*
public static function sortNick() 
{
    usort(playerList, array('playerList','nickSort'));
}
*/

/**
 * Sort by nickname
 */
    public static function sortNick($a, $b) 
    {
        return strcmp($a->nickname, $b->nickname);
    }

/**
 * Sort by score
 */
    public static function sortScore($a, $b) 
    {
        return ($a->score < $b->score);
    }

/**
 * Dump in table format
 */
    public function listing()
    {
        dbg("=".__METHOD__."");

        echo "*** Dump players *** ({$this->playerCount} players)<br>";
        echo "<table border='1'>";
        echo "<th>ID</th>";
        echo "<th>Nickname</th>";
        echo "<th>First Name</th>";
        echo "<th>Last Name</th>";
        echo "<th>Invited</th>";
        echo "<th>Yes</th>";
        echo "<th>Maybe</th>";
        echo "<th>No</th>";
        echo "<th>Flake</th>";
        echo "<th>Score</th>";
        echo "<th>Rank</th>";
        echo "<th>Stamp</th>";
        echo "</tr>";
    
        $counter = 0;
        dbg("=".__METHOD__.";listing count={$this->playerCount}:".count($this->playerList)."");
    #  echo "PlayerArray:listing [0]="; $this[0]->listRow(); echo ".<br>";
        foreach ($this->playerList as $row) {
            $counter++;
//      dbg("=".__METHOD__."player {$row->get_member_id()} ($counter of {$this->playerCount})<br>");
#      $row->listRow();
            echo "<tr>";
            echo "<td>" . $row->get_member_id() . "</td>";
            echo "<td>" . $row->get_nickname() . "</td>";
            echo "<td>" . $row->get_name_last() . "</td>";
            echo "<td>" . $row->get_name_first() . "</td>";
            echo "<td>" . $row->get_invite_cnt() . "</td>";
            echo "<td>" . $row->get_yes_cnt() . "</td>";
            echo "<td>" . $row->get_maybe_cnt() . "</td>";
            echo "<td>" . $row->get_no_cnt() . "</td>";
            echo "<td>" . $row->get_flake_cnt() . "</td>";
            echo "<td>" . number_format($row->get_score(),2) . "</td>";
            echo "<td>" . $row->get_rank() . "</td>";
            echo "<td>" . $row->get_stamp() . "</td>";
            echo "</tr>";
        }
        echo "</table>";
#    echo ".<br>";
    }
//******************************************************************************
} # end class PlayerArray
//******************************************************************************
dbg("-".basename(__FILE__)."");
?>
