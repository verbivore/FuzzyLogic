<?php
/**
 *  Class definition for a game
 *  File name: Game.php
 *  @author David Demaree <dave.demaree@yahoo.com>
 *** History ***
 * 14-03-23 Added dbg().  DHD
 * 14-03-21 Cloned from Member.  DHD
 */

class Game
{

/**
 *Attributes
 */
    protected $game_id;
    protected $game_date;
    protected $member_snack;
    protected $member_host;
    protected $member_gear;
    protected $member_caller;
    protected $stamp;

// List of names of SQL columns for the games table
    private $GAME_TABLE_COLUMNS = array("game_id", "game_date", "member_snack", 
                                        "member_host", "member_gear", 
                                        "member_caller", "stamp");

/*
 * Constructor
 */
    function __construct()
    {
        
#    dbg("=".__METHOD__.";");
        $this->game_id = null;
        $this->game_date = null;
        $this->member_snack = null;
        $this->member_host = null;
        $this->member_gear = null;
        $this->member_caller = null;
        $this->stamp = null;
    }

/**
 * Getters
 */
    public function get_game_id() { return $this->game_id; }
    public function get_game_date() { return $this->game_date; }
    public function get_game_day() { return date('l', strtotime($this->game_date)); }
    public function get_member_snack() { return ($this->member_snack); }
    public function get_member_host() { return $this->member_host; }
    public function get_member_gear() { return $this->member_gear; }
    public function get_member_caller() { return $this->member_caller; }
    public function get_stamp() { return $this->stamp; }

/**
 * Setters
 */
    public function set_game_id($P) { $this->game_id = $P; }
    public function set_game_date($P) { $this->game_date = $P; }
    public function set_member_snack($P) { $this->member_snack = $P; }
    public function set_member_host($P) { $this->member_host = $P; }
    public function set_member_gear($P) { $this->member_gear = $P; }
    public function set_member_caller($P) { $this->member_caller = $P; }
    public function set_stamp($P) { $this->stamp = $P; }

/**
 * Validation for individual column values.
 */
    public function validate_game_id() {
        # numeric
        # !> highest existing game_id + 1
//    dbg("=".__METHOD__.":Game.validate_game_id=$this->game_id");
        $e = array(0,"");
        return($e);
    }

    public function validate_game_date() {
        # "S" or "M"
        $e = array(0,"");
        return($e);
    }

    public function validate_member_snack() {
        # string
        # alphabetic/spaces, starts with capital?, 
//  public function validate_member_snack() { if(is_string($P)) { $this->member_snack = (string)$P; } }
        $e = array(0,"");
        return($e);
    }

    public function validate_member_host() {
        # string
        # alphabetic/spaces, starts with capital?, 
        $e = array(0,"");
        return($e);

    }

    public function validate_member_gear() {
        # string
        # alphabetic/spaces, starts with capital?, 
        $e = array(0,"");
        return($e);

    }

    public function validate_member_caller() {
        # string
        # alphabetic/spaces, starts with capital?, 
        $e = array(0,"");
        return($e);

    }

    public function validate_stamp() {
        # string
        # alphabetic/spaces, starts with capital?, 
        $e = array(0,"");
        return($e);

    }

/**
 * Validate games fields
 */
    public function validate()
    {
        
        dbg("+".__METHOD__."={$this->game_id}");
        $errors = array();
        $foo = array();
#    global $GAME_TABLE_COLUMNS;
        # validate fields
        foreach ($this->GAME_TABLE_COLUMNS as $column) {
            $func = "validate_$column";
//            dbg("=".__METHOD__.":Game.validate column={$func}");
            $foo = $this->$func();
//      dbg("=".__METHOD__.":Game.validate col:$column="; var_dump($foo); echo "");
            if ($foo[0]) {
                $errors["$column"][0] = $foo[0];
                $errors["$column"][1] = $foo[1];
//        dbg("=".__METHOD__.":Game.validate col:$column:$foo[0]:$foo[1]");
            }
        }
//        if ($debug) {
//        foreach ($errors as $col => $val) {
//      echo "Game.validate errors=$col:"; list($n,$s) = $val; echo "$n:$s");
//            echo "Game.validate errors=$col:$val[0]:$val[1]");
//        }
        dbg("-".__METHOD__.":error arraysize=".sizeof($errors));
        return($errors);
    }

/**
 * create a list of comma-separated column names for SQL statements.          
 */
    private function sql_column_name_list() {
        
        $list = "";
        foreach ($this->GAME_TABLE_COLUMNS as $item) {
            if ($item != "stamp") { # stamp must be null for auto-update
                $list .= "$item, ";
            }
        }
        $list = rtrim($list, ", ");
//    dbg("=".__METHOD__.":Game:sql_column_name_list()=$list");
        return $list;
    }  

/**
 * create a list of quoted, comma-separated column values for SQL statements. 
 */
    private function sql_column_value_list() {
        
        $list = "";
        foreach ($this->GAME_TABLE_COLUMNS as $item) {
            if ($item != "stamp") { # stamp must be null for auto-update
                $list = $list . "\"{$this->$item}\", ";
            }
        }
        $list = rtrim($list, ", ");
//    dbg("=".__METHOD__.":Game:sql_column_value_list()=$list");
        return $list;
    }  


/**
 * create a list of pairs of column names with values, used for SQL INSERT    
 */
    private function sql_column_name_value_pairs() {
        
        $list = "";
        foreach ($this->GAME_TABLE_COLUMNS as $item) {
            if ($item == "stamp") { # stamp must be null for auto-update
                $list = $list . "$item = NULL, ";
            } else {
                $list = $list . "$item = \"{$this->$item}\", ";
            }
        }
        $list = rtrim($list, ", ");
        dbg("=".__METHOD__."=$list");
        return $list;
    }  

/**
 * set the data games of this game to the values found in $_POST.       
 */
    public function set_to_POST()
    {
    $this->set_game_id($_POST['game_id']);
    $this->set_game_date($_POST['game_date']);
    $this->set_member_snack($_POST['member_snack']);
    $this->set_member_host($_POST['member_host']);
    $this->set_member_gear($_POST['member_gear']);
    $this->set_member_caller($_POST['member_caller']);
    $this->set_stamp($_POST['stamp']);
    }

/**
 * get a game row by game_id.                   
 */
    public function get($getType)
    {
        
        dbg("+".__METHOD__ . "={$this->game_id}");
            $query = "SELECT * FROM games WHERE game_id = ("; 
            switch ($getType) {
            case 'prev':
                $query .= "SELECT MAX(game_id) FROM games " . 
                             "WHERE game_id < ";
                break;
            case 'next':
                $query .= "SELECT MIN(game_id) FROM games " . 
                             "WHERE game_id > ";
                break;
            default:
                $query .= " ";
                break;
            }
            $query .= " \"$this->game_id\") ";
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # get games row
            dbg("=".__METHOD__.";query=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
#            dbg("=".__METHOD__.":Game:get:$this->game_id:rows=$row_count");
            if ($row_count == 1) {
                $row = $stmt->fetch();
                $this->setThisToGameRow($row);
                #echo "row:"; var_dump($row); echo ".<br>";
            } elseif ($row_count < 1) {
#                dbg("=".__METHOD__.":Game:get=game not found");
                #error_log($e->getTraceAsString());
                if ($getType == 'prev') {
                    throw new gameException('No previous game for ID ' . $this->game_id . ' found', 32210);
                } elseif ($getType == 'next') {
                    $this->getNew();
                    throw new gameException('Add new game (' . $this->game_id . ')', 32213);
                } else {
                    throw new gameException('No game found with this ID (' . $this->game_id . ')', 32212);
                }
            } else {
#                dbg("=".__METHOD__.":Game:get=multiple game records found");
                #error_log($e->getTraceAsString());
                throw new gameException('Multiple records for this game were found', 32211);
            }
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
        dbg("-".__METHOD__ . "={$this->game_id}");
    }
/**
 * populate a game object with data from a game table row        
 */
    protected function setThisToGameRow($row) 
    {
        $this->game_id = $row['game_id'];
        $this->game_date = $row['game_date'];
        $this->member_snack = $row['member_snack'];
        $this->member_host = $row['member_host'];
        $this->member_gear = $row['member_gear'];
        $this->member_caller = $row['member_caller'];
        $this->stamp = $row['stamp'];
    }

/**
 * Get the next available game number and date
 */
    public function getNew()
    {
        
        dbg("+".__METHOD__.";");
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # get game
            $query = "SELECT game_id, game_date FROM games " .
                     "WHERE game_id =  (SELECT MAX(game_id) FROM games) ";
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $foo = array($stmt->fetch());
#var_dump($foo); echo "<br>";
//            $this->game_id = $stmt->fetchColumn(0) + 1;
//            $this->game_date = $stmt->fetchColumn();
            $prev_game_id = $foo[0][0];
            $this->game_id = $prev_game_id + 1;
            $prev_game_date = $foo[0][1];
            $this->setNewDate($prev_game_date);
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
        } catch (Exception $e) {
            echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
        }
        dbg("-".__METHOD__.";{$this->game_id}:{$this->game_date}");
    }


/**
 * Calculate the date of the next game
 */
    private function setNewDate($prev_game_date) {

        $time_int = strtotime( $prev_game_date );
        dbg("+".__METHOD__."=$prev_game_date=$time_int");
        if (date('d', $time_int) < 15 ) { # 2nd wk is 8th-14th
            $calc_str = "fourth friday of this month";
        } else { # 4th wk is 22nd-28th
            $calc_str = "second wednesday of next month";
        }
        $this->game_date = date("Y-m-d", strtotime($calc_str, $time_int));
/*


        $dayOfMonth = date( 'd', $time_int );
            if ($dayOfMonth < 15 ) { # it's the 1st game of the month (Wed) so calc 4th Friday
                $baseDate = date("Y-m-", $time_int) . "01";
                $nextDate = date("Y-m-d", strtotime("fourth friday of this month", strtotime($baseDate)));
                $nextDate = date("Y-m-d", strtotime("fourth friday of this month", $time_int));
                
        dbg("=".__METHOD__."-$baseDate:$nextDate");
            } else { # it's a Friday so...) calc 2nd Wed.
                $nextDate = date("Y-m-d", strtotime("second wednesday of next month", $time_int));
        dbg("=".__METHOD__."+$nextDate");
                $xx = strtotime("second wednesday of next month", $time_int);
                $xxx = date("Y-m-d", $xx);
                $yy = date("Y-m-d", strtotime("second wednesday ", $xx));
        dbg("=".__METHOD__."+$prev_game_date====$xxx====$yy");
            }
            $this->game_date = $nextDate;
            #$wed = date("Y-m-d", strtotime("2 weeks wednesday",mktime(0,0,0,11,1,2014)));
            #$wed = date("Y-m-d", strtotime("2 weeks wednesday", $time_int));
            #echo "2nd Wed:$wed=" . date('l', strtotime($wed)) . ".<br>";
*/

//$month = date("M", $this->game_date);
//echo "Month:$month.<br>";

            #$tomorrow  = date('F jS, Y = l', mktime(0, 0, 0, date("m", $time_int)  , date("d", $time_int)+1, date("Y", $time_int)));
            #echo "Tomorrow:$tomorrow.<br>";

            #echo $this->game_date . ":" . date('l', strtotime( $this->game_date)) . "<br>";
#":" . date_format($this->game_date, 'Y-m-d H:i:s') . 

            #$tempDate = date('F jS, Y = l', strtotime(" next wednesday {$this->game_date}"));
            #echo $tempDate . "<br>";


        dbg("-".__METHOD__.":$this->game_date");
    }

/**
 * List a game
 */
    public function listing()
    {
        echo "Game." . __FUNCTION__ . ".<br>";
        $this->listIt(".<br>");
    }

    public function listRow()
    {
        echo "Game." . __FUNCTION__ . ":";
        $this->listIt("; ");
        echo ".<br>";
    }

    private function listIt($d)
    {
        echo "Game_id=$this->game_id$d";
        echo "game_date=$this->game_date$d";
        echo "member_host=$this->member_host$d";
        echo "member_snack=$this->member_snack$d";
        echo "member_gear=$this->member_gear$d";
        echo "member_caller=$this->member_caller$d";
        echo "stamp=$this->stamp$d";
    }

    public function dump()
    {
        echo "Game.dump.<br>";
        var_dump($this);
        echo ".<br>";
    }

/**
 * find a games row by game_id.
 */
    public function find()
    {
        
        $row_count = -1;
        dbg("+".__METHOD__ . "={$this->game_id}");
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # find game rows
            $query = "SELECT * FROM games " . 
                          "WHERE game_id = \"$this->game_id\"  ";
            dbg("=".__METHOD__ . ":query=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            dbg("=".__METHOD__ . ":$this->game_id:rows=$row_count");
            $foo = $stmt->fetchAll();
//        echo '<pre>'; var_dump($foo); print ".<br/>"; echo '</pre>';

        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
            dbg("-".__METHOD__ . "={$this->game_id};PDO Exception");
            throw new gameException('PDO Exception', 32010, $e);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
        dbg("-".__METHOD__ . "={$this->game_id}=$row_count");
        return($row_count);
    }

/**
 * insert a games row from a game object
 */
    public function insert()
    {
        
        $val_errors = array ();
        dbg("+".__METHOD__.";$this->game_id:{$this->sql_column_name_value_pairs()}");
        $val_errors = ($this->validate());
        if (sizeof($val_errors) == 0 ) {
            try {
require(BASE_URI . "includes/pok.open.inc.php");
                # insert game
                $query = "INSERT INTO games ({$this->sql_column_name_list()}) " .
                  "VALUES ({$this->sql_column_value_list()})" ;
                dbg("=".__METHOD__.";query=$query");
                $stmt = $pokdb->prepare($query);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Game.insert: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                throw new gameException('Unknown error', -32110, $e);
            } catch (Exception $e) {
                echo "Game.insert: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new gameException($e);
            }
        } else {
            throw new gameException("Data validation errors", 2104, null, $val_errors);
        }
//    dbg("=".__METHOD__.":Game added");
//    $inserted_game_id = $pokdb->lastInsertId(); 
//    dbg("=".__METHOD__.":Game number:$inserted_game_id");
        dbg("-".__METHOD__.";$this->game_id:".sizeof($val_errors)."");
    }

/**
 * update a games row from a game object                         
 */
    public function update()
    {
        
        dbg("+".__METHOD__."=$this->game_id");
        $val_errors = $this->validate();
//    dbg("=".__METHOD__.":Game.update error list size:"; echo sizeof($val_errors); echo "");
        if (sizeof($val_errors) == 0 ) {
            try {
# open database
require(BASE_URI . "includes/pok.open.inc.php");
                # update game
                $update = "UPDATE games SET {$this->sql_column_name_value_pairs()} " . 
                      " WHERE game_id = \"{$this->game_id}\" ";
                dbg("=".__METHOD__.";stmt_str=$update");
                $stmt = $pokdb->prepare($update);
                $stmt->execute();
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    #error_log($e->getTraceAsString());
                    throw new gameException('Duplicate entry', 32110, $e);
                } else {
                    echo "Game.update: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                    throw new gameException('Unknown error', -32110, $e);
                }
            } catch (Exception $e) {
                echo "Game.update: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new gameException($e);
            }
        } else {
            throw new gameException("Data validation errors", 32104, null, $val_errors);
        }
        dbg("-".__METHOD__.";$this->game_id:".sizeof($val_errors)."");
    }
/**
 * delete all games and seats rows for a game                         
 */
    public function delete()
    {
        $deleted_seats = 0;
        $deleted_members = 0;
        dbg("+".__METHOD__.";$this->game_id");
//        dbg("=".__METHOD__.":player.delete error list size:"; echo sizeof($val_errors); echo "");
//        if (sizeof($val_errors) == 0 ) {
            try {
require(BASE_URI . "includes/pok.open.inc.php");
                # delete attendance
                $delete = "DELETE FROM seats " . 
                      " WHERE game_id = \"{$this->game_id}\" ";
                dbg("=".__METHOD__.":Game::" . __FUNCTION__ . ":stmt_str=$delete");
                $stmt = $pokdb->prepare($delete);
                $stmt->execute();
                $deleted_seats = $stmt->rowCount();
                $delete = "DELETE FROM games " . 
                      " WHERE game_id = \"{$this->game_id}\" ";
                dbg("=".__METHOD__.":Game::" . __FUNCTION__ . ":stmt_str=$delete");
                $stmt = $pokdb->prepare($delete);
                $stmt->execute();
                $deleted_games = $stmt->rowCount();
            } catch (PDOException $e) {
                echo "Game.delete: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                throw new gameException('Unknown error', -32110, $e);
            } catch (Exception $e) {
                echo "Game.delete: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new gameException($e);
            }
//        } else {
//            throw new playerException("Data validation errors", 2104, null, $val_errors);
//        }
        dbg("-".__METHOD__.";$this->game_id");
    }

/**
 * create a fictitious game for test purposes                         
 */
    function testGame()
    {
        
        dbg("+".__METHOD__."");
        # id, date
        $this->getNew();
        # get highest player_id
        $testPlay = new Player();
        $testPlay->get_next_id();
        $high = $testPlay->get_member_id();
        $this->member_snack = rand(1,$high);
        $this->member_host = rand(1,$high);
        $this->member_gear = rand(1,$high);
        $this->member_caller = rand(1,$high);
//        $this->game_date = rand(1,$high);
        dbg("=".__METHOD__.":Game::" . __FUNCTION__ . ":end:high=$high:{$this->game_id}:{$this->game_date}:{$this->member_snack}:{$this->member_host}:{$this->member_gear}:{$this->member_caller}");
        unset($testy);
        dbg("-".__METHOD__."");
/*
        # member_snack
require("../inc/testdb_open.php"); #
        $nameCount = $this->getRowCount($testdb, "family_names");
        $nameId = rand(1, $nameCount);
        $query="SELECT * FROM family_names WHERE name_id = $nameId";
//    echo "testGame family_names query=$query.<br>";
        $stmt = $testdb->prepare($query);
        $stmt->execute();
        $row_count = $stmt->rowCount();
        dbg("=".__METHOD__.":Game.testGame rows:$row_count");
        if ($row_count == 1) {
            $row = $stmt->fetch();
            $this->member_snack = $row['member_snack'];
#      echo "Game.testGame member_snack=$this->member_snack.<br>";
        } else {
            echo "testGame __construct member_snack error: Too many rows:$row_count.<br>";
            throw new Exception('testGame member_snack SELECT error: Too many rows', -1);
        }
        # member_host
        $gender = rand(0, 1); 
        if ($gender) {
            $table_name = "male_names";
        } else {
            $table_name = "female_names";
        }
        $nameCount = $this->getRowCount($testdb, $table_name);
        $nameId = rand(1, $nameCount); # Pick random male, female
        $query = "SELECT member_host FROM $table_name WHERE name_id = $nameId";
        $stmt = $testdb->prepare($query);
#    dbg("=".__METHOD__.":Game.testGame stmt:"; $stmt->debugDumpParams(); echo "<br>"; }
        $stmt->execute();
        $row_count = $stmt->rowCount();
//    dbg("=".__METHOD__.":Game.testGame rows:$row_count");
        if ($row_count == 1) {
            $row = $stmt->fetch();
            $this->member_host = $row['member_host'];
//      echo "Game.testGame member_host=$this->member_host.<br>";
        } else {
            echo "testGame __construct member_host error: Too many rows:$row_count.<br>";
            throw new Exception('testGame member_host SELECT error: Too many rows', -1);
        }

#  echo "Next this:{$this->get_game_id()}.<br>";
        $this->set_game_date("M");
*/

    }


/**
 * get the number of rows in a table from the test database                         
 */
/*
    private function getRowCount($testdb, $table_name)
    {
        # Get number of rows to choose from in table
        $nameCount = 0;
        $sql = "SELECT COUNT(*) FROM $table_name";
        if ($res = $testdb->query($sql)) {
            # Check the number of rows that match the SELECT statement
            $nameCount = $res->fetchColumn();
        }
        #echo "testdata $table_name count=$nameCount.<br>";
        return ($nameCount);
    }
*/
} 

//******************************************************************************
// end class Game
//******************************************************************************
class GameException extends Exception
{
#    
    private $_options = array();
    // Redefine the exception so message isn't optional
    public function __construct($message, 
                                $code = 0, 
                                Exception $previous = null,
                                $options = array('params')) {
#        dbg("=".__METHOD__.":GameException={$message}:$code");
            // make sure everything is assigned properly
            parent::__construct($message, $code, $previous);

            $this->_options = $options;

    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function GetOptions() { 
#    dbg("=".__METHOD__.":GameException:GetOptions="; echo sizeof($this->_options); echo "");
    return $this->_options; 
    }
}

?>

