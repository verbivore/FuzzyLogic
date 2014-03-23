<?php
/**
 *  Class definition for a game
 *  File name: Game.php
 *  @author David Demaree <dave.demaree@yahoo.com>
 *** History ***
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
        global $debug;
#    if ($debug) { echo "Game:__construct.<br>"; }
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
//    if ($debug) { echo "Game.validate_game_id=$this->game_id.<br>"; }
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
        global $debug;
        if ($debug) { echo "Game.validate={$this->game_id}.<br>"; }
        $errors = array();
        $foo = array();
#    global $GAME_TABLE_COLUMNS;
        # validate fields
        foreach ($this->GAME_TABLE_COLUMNS as $column) {
            $func = "validate_$column";
//      if ($debug) { echo "Game.validate column={$func}.<br>"; }
            $foo = $this->$func();
//      if ($debug) { echo "Game.validate col:$column="; var_dump($foo); echo ".<br>"; }
            if ($foo[0]) {
                $errors["$column"][0] = $foo[0];
                $errors["$column"][1] = $foo[1];
//        if ($debug) { echo "Game.validate col:$column:$foo[0]:$foo[1].<br>"; }
            }
        }
        if ($debug) {
        foreach ($errors as $col => $val) {
//      echo "Game.validate errors=$col:"; list($n,$s) = $val; echo "$n:$s.<br>"; }
            echo "Game.validate errors=$col:$val[0]:$val[1].<br>"; }
        }
#    if ($debug) { echo "Game.validate arraysize="; echo sizeof($errors); echo ".<br>"; }
        return($errors);
    }

/**
 * create a list of comma-separated column names for SQL statements.          
 */
    private function sql_column_name_list() {
        global $debug;
        $list = "";
        foreach ($this->GAME_TABLE_COLUMNS as $item) {
            $list .= "$item, ";
        }
        $list = rtrim($list, ", ");
//    if ($debug) { echo "Game:sql_column_name_list()=$list.<br>"; }
        return $list;
    }  

/**
 * create a list of quoted, comma-separated column values for SQL statements. 
 */
    private function sql_column_value_list() {
        global $debug;
        $list = "";
        foreach ($this->GAME_TABLE_COLUMNS as $item) {
            $list = $list . "\"{$this->$item}\", ";
        }
        $list = rtrim($list, ", ");
//    if ($debug) { echo "Game:sql_column_value_list()=$list.<br>"; }
        return $list;
    }  


/**
 * create a list of pairs of column names with values, used for SQL INSERT    
 */
    private function sql_column_name_value_pairs() {
        global $debug;
        $list = "";
        foreach ($this->GAME_TABLE_COLUMNS as $item) {
            if ($item == "stamp") { # stamp must be null for auto-update
                $list = $list . "$item = NULL, ";
            } else {
                $list = $list . "$item = \"{$this->$item}\", ";
            }
        }
        $list = rtrim($list, ", ");
        if ($debug) { echo "Game:sql_column_name_value_pairs()=$list.<br>"; }
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
        global $debug;
#        if ($debug) { echo "Game:get={$this->game_id}.<br>"; }
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            switch ($getType) {
                case 'prev':
                    $query = "SELECT * FROM games WHERE game_id = " . 
                             "(SELECT MAX(game_id) FROM games " . 
                             "WHERE game_id < \"$this->game_id\") ";
                    break;
                case 'next':
                    $query = "SELECT * FROM games WHERE game_id = " . 
                             "(SELECT MIN(game_id) FROM games " . 
                             "WHERE game_id > \"$this->game_id\") ";
                    break;
                default:
                    $query = "SELECT * FROM games " . 
                             "WHERE game_id = \"$this->game_id\" ";
                    break;
            }
            # get games row
            if ($debug) { echo "Game:games:get:query=$query.<br>"; }
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
#            if ($debug) { echo "Game:get:$this->game_id:rows=$row_count.<br>"; }
            if ($row_count == 1) {
                $row = $stmt->fetch();
                $this->setThisToGameRow($row);
                #echo "row:"; var_dump($row); echo ".<br>";
            } elseif ($row_count < 1) {
#                if ($debug) { echo "Game:get=game not found.<br>"; }
                #error_log($e->getTraceAsString());
                if ($getType == 'prev') {
                    throw new gameException('No previous game for ID ' . $this->game_id . ' found', 32210);
                } elseif ($getType == 'next') {
                    $this->getNew();
                } else {
                    throw new gameException('No game found with this ID (' . $this->game_id . ')', 32210);
                }
            } else {
#                if ($debug) { echo "Game:get=multiple game records found.<br>"; }
                #error_log($e->getTraceAsString());
                throw new gameException('Multiple records for this game were found', 32211);
            }
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
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
        global $debug;
        if ($debug) { echo "getNew:start.<br>"; }
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # get game
            $stmt = $pokdb->prepare("SELECT game_id, game_date FROM games WHERE game_id =  (SELECT MAX(game_id) FROM games) ");
            $stmt->execute();
            $foo = array($stmt->fetch());
var_dump($foo); echo "<br>";
//            $this->game_id = $stmt->fetchColumn(0) + 1;
//            $this->game_date = $stmt->fetchColumn();
            $prev_game_id = $foo[0][0];
            $this->game_id = $prev_game_id + 1;
            $prev_game_date = $foo[0][1];
            $phpdate = strtotime( $prev_game_date );
            $dayOfMonth = date( 'd', $phpdate );
            echo "Game date:$this->game_date:$phpdate:dayOfMonth:$dayOfMonth.<br>";
            if ($dayOfMonth < 15 ) { # it's the 1st game of the month (Wed) so calc 4th Friday
              $baseDate = date("Y-m-", $phpdate) . "01";
              $nextDate = date("Y-m-d", strtotime("4 weeks friday", strtotime($baseDate)));
            } else { # it's a Friday so...) calc 2nd Wed.
              $nextDate = date("Y-m-d", strtotime("2 weeks wednesday", $phpdate));
            }
            $this->game_date = $nextDate;
            echo "nextDate:$nextDate:$baseDate.<br>";

$wed = date("n/j/Y", strtotime("2 weeks wednesday",mktime(0,0,0,11,1,2014)));
$wed = date("n/j/Y", strtotime("2 weeks wednesday", $phpdate));
echo "2nd Wed:$wed=" . date('l', strtotime($wed)) . ".<br>";


//$month = date("M", $this->game_date);
//echo "Month:$month.<br>";

$tomorrow  = date('F jS, Y = l', mktime(0, 0, 0, date("m", $phpdate)  , date("d", $phpdate)+1, date("Y", $phpdate)));
echo "Tomorrow:$tomorrow.<br>";

echo $this->game_date . ":" . date('l', strtotime( $this->game_date)) . "<br>";
#":" . date_format($this->game_date, 'Y-m-d H:i:s') . 

$tempDate = date('F jS, Y = l', strtotime(" next wednesday {$this->game_date}"));
echo $tempDate . "<br>";


#      if ($debug) { echo "$this->game_id.<br>"; }
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
        } catch (Exception $e) {
            echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
        }
        if ($debug) { echo "getNew:end:{$this->game_id}:{$this->game_date}.<br>"; }
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
        global $debug;
        $row_count = -1;
        if ($debug) { echo "Game:find={$this->game_id}.<br>"; }
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # find game rows
            $query = "SELECT * FROM games " . 
                                  "WHERE game_id = \"$this->game_id\"  ";
            if ($debug) { echo "Game:find:query=$query.<br>"; }
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            if ($debug) { echo "Game:find:$this->game_id:rows=$row_count.<br>"; }
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
            throw new gameException('PDO Exception', -2010, $e);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
        return($row_count);
    }

/**
 * insert a games row from a game object
 */
    public function insert()
    {
        global $debug;
        $val_errors = array ();
        if ($debug) { echo "Game.insert:$this->game_id:{$this->sql_column_name_value_pairs()}.<br>"; }
        $val_errors = ($this->validate());
        if (sizeof($val_errors) == 0 ) {
            try {
require(BASE_URI . "includes/pok.open.inc.php");
                # insert game
                $query = "INSERT INTO games ({$this->sql_column_name_list()}) " .
                  "VALUES ({$this->sql_column_value_list()})" ;
                if ($debug) { echo "Game:find:query=$query.<br>"; }
                $stmt = $pokdb->prepare($query);
                $stmt->execute();
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    #error_log($e->getTraceAsString());
                    throw new gameException('Duplicate entry', 2110, $e);
                } else {
                    echo "Game.insert: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                    throw new gameException('Unknown error', -2110, $e);
                }
            } catch (Exception $e) {
                echo "Game.insert: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new gameException($e);
            }
        } else {
            throw new gameException("Data validation errors", 2104, null, $val_errors);
        }
//    if ($debug) { echo "Game added.<br>"; }
//    $inserted_game_id = $pokdb->lastInsertId(); 
//    if ($debug) { echo "Game number:$inserted_game_id.<br>"; }
    }

/**
 * update a games row from a game object                         
 */
    public function update()
    {
        global $debug;
        if ($debug) { echo "Game.update:$this->game_id.<br/
>"; }
        $val_errors = $this->validate();
//    if ($debug) { echo "Game.update error list size:"; echo sizeof($val_errors); echo ".<br>"; }
        if (sizeof($val_errors) == 0 ) {
            try {
                require(BASE_URI . "includes/pok.open.inc.php");
                # update game
                $update = "UPDATE games SET {$this->sql_column_name_value_pairs()} " . 
                      " WHERE game_id = \"{$this->game_id}\" ";
                if ($debug) { echo "Game:update:stmt_str=$update.<br>"; }
                $stmt = $pokdb->prepare($update);
                $stmt->execute();
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    #error_log($e->getTraceAsString());
                    throw new gameException('Duplicate entry', 2110, $e);
                } else {
                    echo "Game.update: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                    throw new gameException('Unknown error', -2110, $e);
                }
            } catch (Exception $e) {
                echo "Game.update: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new gameException($e);
            }
        } else {
            throw new gameException("Data validation errors", 2104, null, $val_errors);
        }
        if ($debug) { echo "Game.update:end:$this->game_id.<br>"; }
    }

/**
 * create a fictitious game for test purposes                         
 */
    function testGame()
    {
        global $debug;
        if ($debug) { echo "Game.testGame start.<br>"; }
        # id
        $this->get_next_id();
        $testy = new testPerson();
        $this->member_snack = $testy->get_member_snack();
        $this->member_host = $testy->get_member_host();
        $this->game_date = $testy->get_game_date();
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
        if ($debug) { echo "Game.testGame rows:$row_count.<br>"; }
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
#    if ($debug) { echo "Game.testGame stmt:"; $stmt->debugDumpParams(); echo "<br>"; }
        $stmt->execute();
        $row_count = $stmt->rowCount();
//    if ($debug) { echo "Game.testGame rows:$row_count.<br>"; }
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
#    global $debug;
        private $_options = array();
        // Redefine the exception so message isn't optional
        public function __construct($message, 
                                    $code = 0, 
                                    Exception $previous = null,
                                    $options = array('params')) {

#        if ($debug) { echo "GameException={$message}:$code.<br>"; }
                // make sure everything is assigned properly
                parent::__construct($message, $code, $previous);

                $this->_options = $options;

        }

        // custom string representation of object
        public function __toString() {
                return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        }

        public function GetOptions() { 
#    if ($debug) { echo "GameException:GetOptions="; echo sizeof($this->_options); echo ".<br>"; }
        return $this->_options; 
        }
}

?>

