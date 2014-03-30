<?php
/**
 *  Class definition for a seat
 *  File name: Seat.php
 *  @author David Demaree <dave.demaree@yahoo.com>
 *** History ***
 * 14-03-29 Added const error codes.  Added validation and specialty getters.  DHD
 * 14-03-29 Udated testSeat.  Added bindvalue on update.  DHD
 * 14-03-23 Added dbg().  DHD
 * 14-03-21 Cloned from Member.  DHD
 * Future:
 *  Finish field validation stubs.
 */
dbg("+".basename(__FILE__)."");

class Seat
{

/**
 *Attributes
 */
    protected $game_id;
    protected $member_id;
    protected $response;
    protected $note_member;
    protected $note_master;
    protected $stamp;

// List of names of SQL columns for the seats table
    private $SEAT_TABLE_COLUMNS = array("game_id", "member_id", "response", 
                                        "note_member", "note_master", 
                                        "stamp");
// List of valid responses
    private $SEAT_RESPONSES = array("I", "Y", "M", "N", "F");
    private $RESPONSE_NAMES = array("I" => "Invited", "Y" => "Yes", "M" => "Maybe", "N" => "No", "F" => "Flake");

// Error message constants
// 1st digit: 0=Info, 1=Warning, 3&4=Validation, 6=Navigation, 8=DB, 9=PDO
// 2nd digit: Module: 3=Members, 4=Players, 5=Games, 6=Seats
// 3rd digit: Method: 1=Validate, 2=Find, 3=Get, 4=Insert, 5=Update, 6=Delete
// 4&5 digit: Id
    const FIND_ERR_ZERO    = 86210; # No row found
    const FIND_ERR_ONE     = 86211; # One row found
    const FIND_ERR_MULTI   = 86212; # Multiple rows found
    const FIND_ERR_PDO     = 96200; # PDO error

    const GET_INFO_ADD_NEW = 06301; # Ready to add a new entry
    const GET_WARN_NO_PREV = 66363; # No previous entry found
    const GET_WARN_NO_NEXT = 66364; # No next entry found
    const GET_ERR_ZERO     = 86310; # No row found
    const GET_ERR_ONE      = 86311; # One row found
    const GET_ERR_MULTI    = 86312; # Multiple rows found
    const GET_ERR_NEW_PDO  = 96317; # PDO error on getNew
    const GET_ERR_PDO      = 96300; # PDO error

    const INS_ERR_VALIDTN  = 36400; # Insert failed: data validation error(s)
    const INS_ERR_DUP      = 86402; # Insert failed: duplicate key
    const INS_ERR_PDO      = 96400; # PDO error

    const UPD_ERR_VALIDTN  = 36500; # Insert failed: data validation error(s)
    const UPD_ERR_ZERO     = 86510;
    const UPD_ERR_DUP      = 86511;
    const UPD_ERR_MULTI    = 86512;
    const UPD_ERR_PDO      = 96500;

    const DEL_ERR_ZERO     = 86610;
    const DEL_ERR_MULTI    = 86612;
    const DEL_ERR_PDO      = 96618;

    const VAL_ERR_RESP     = 36130; # Invalid response value
    const VAL_ERR_MEMBER   = 36140; # Invalid response value
    const VAL_ERR_MASTER   = 36150; # Invalid response value

/*
 * Constructor
 */
    function __construct()
    {
        
    dbg("=".__METHOD__.";".self::GET_ERR_ZERO);
        $this->game_id = null;
        $this->member_id = null;
        $this->response = null;
        $this->note_member = null;
        $this->note_master = null;
        $this->stamp = null;
    }

/**
 * Getters
 */
    public function get_game_id() { return $this->game_id; }
    public function get_member_id() { return $this->member_id; }
    public function get_response() { return ($this->response); }
    public function get_note_member() { return $this->note_member; }
    public function get_note_master() { return $this->note_master; }
    public function get_stamp() { return $this->stamp; }
    public function get_response_name() { 
        if (array_key_exists($this->response, $this->RESPONSE_NAMES)) {
            return $this->RESPONSE_NAMES["{$this->response}"]; 
        } else {
            return "";
        }
    }
    public function get_member_name() {
        $name = "";
        $mbr = new Member;
        $mbr->set_member_id($this->get_member_id());
        try {
           $mbr->get();
           $name = $mbr->get_full_name();
        } catch (PokerException $e) {
            switch ($e->getCode()) {
            case Member::GET_ERR_ZERO:  # no rows retrieved
                break;
            default:
                throw new PokerException($e);
                break;
            }
        }
//        return date($mbr->get_member_nickname()); 
        return ($name); 
    }

/**
 * Setters
 */
    public function set_game_id($P) { $this->game_id = $P; }
    public function set_member_id($P) { $this->member_id = $P; }
    public function set_response($P) { $this->response = $P; }
    public function set_note_member($P) { $this->note_member = $P; }
    public function set_note_master($P) { $this->note_master = $P; }
    public function set_stamp($P) { $this->stamp = $P; }

/**
 * Validate seats fields
 */
    public function validate()
    {

        dbg("+".__METHOD__."={$this->game_id}");
        $val_msgs = array();
        $foo = array();
        # validate fields
        foreach ($this->SEAT_TABLE_COLUMNS as $column) {
            $func = "validate_$column";
            dbg("=".__METHOD__."={$func}");
            $foo = $this->$func();
//            dbg("=".__METHOD__.":Seat.validate col:$column="; var_dump($foo); echo "");
            if ($foo[0]) {
                $val_msgs["$column"][0] = $foo[0];
                $val_msgs["$column"][1] = $foo[1];
                dbg("=".__METHOD__.":Seat.validate col:$column:$foo[0]:$foo[1]");
            }
        }
//        if ($debug) {
//        foreach ($val_msgs as $col => $val) {
//            echo "Seat.validate errors=$col:"; list($n,$s) = $val; echo "$n:$s");
//            echo "Seat.validate errors=$col:$val[0]:$val[1]");
//        }
        dbg("-".__METHOD__.":error arraysize=".sizeof($val_msgs));
        return($val_msgs);
    }

/**
 * Validation for individual column values.
 */
    public function validate_game_id() {
        $v = array(0,"");
        $row_count = -1;
        # Exists in the game table
        dbg("=".__METHOD__."=$this->game_id");
        $gm = new Game;
        $gm->set_game_id($this->game_id);
//        try {
            $row_count=$gm->find();
/*        }   catch (PokerException $e) {
            switch ($e->getCode()) {
            case Game::FIND_ERR_ZERO: # Not found
                dbg("=".__METHOD__.";error={$e->getMessage()}");
                $v[0] = Game::FIND_ERR_ZERO;
                $v[1] = "No game for this ID ({$this->game_id}) found";
                break;
            default:
                throw $e;
            }
        }
*/
        if ($row_count != 1) {
            $v[0] = Game::FIND_ERR_ZERO;
            $v[1] = "No game for this ID ({$this->game_id}) found";
        }
        return($v);
    }

    public function validate_member_id() {
        $v = array(0,"");
        $row_count = -1;
        # Exists in the members table
        dbg("=".__METHOD__."=$this->member_id");
        $mbr = new Member;
        $mbr->set_member_id($this->member_id);
//        try {
            $row_count = $mbr->find();
/*        }   catch (PokerException $e) {
            switch ($e->getCode()) {
            case Member::FIND_ERR_ZERO: # Not found????
                dbg("=".__METHOD__.";error={$e->getMessage()}");
                $v[0] = Member::FIND_ERR_ZERO;
                $v[1] = "No member for this ID ({$this->member_id}) found";
                break;
            default:
                throw $e;
            }
        }
*/
        if ($row_count != 1) {
            $v[0] = Member::FIND_ERR_ZERO;
            $v[1] = "No member for this ID ({$this->member_id}) found";
        }
        return($v);
    }

    public function validate_response() {
        $v = array(0,"");
        if (!array_key_exists($this->response, $this->RESPONSE_NAMES)) {
            $v[0] = self::VAL_ERR_RESP;
            $v[1] = "Invalid response. Must be one of " . implode(", ", $this->SEAT_RESPONSES) . ".";
        }
        return($v);
    }

    public function validate_note_member() {
        $v = array(0,"");
        # string
        if (!is_string($this->note_member)) {
            $v[0] = self::VAL_ERR_MEMBER;
            $v[1] = "Invalid member note; must be... ";
        }
        return($v);
    }

    public function validate_note_master() {
        $v = array(0,"");
        # string
        if (!is_string($this->note_master)) {
            $v[0] = self::VAL_ERR_MASTER;
            $v[1] = "Invalid master note; must be... ";
        }
        return($v);
    }

    public function validate_stamp() {
        $v = array(0,"");
        return($v);
    }

/**
 * create a list of comma-separated column names for SQL statements.          
 */
    private function sql_column_name_list() {
        
        $list = "";
        foreach ($this->SEAT_TABLE_COLUMNS as $item) {
            if ($item != "stamp") { # stamp must be null for auto-update
                $list .= "$item, ";
            }
        }
        $list = rtrim($list, ", ");
//    dbg("=".__METHOD__.":Seat:sql_column_name_list()=$list");
        return $list;
    }  

/**
 * create a list of quoted, comma-separated column values for SQL statements. 
 */
    private function sql_column_value_list() {
        
        $list = "";
        foreach ($this->SEAT_TABLE_COLUMNS as $item) {
            if ($item != "stamp") { # stamp must be null for auto-update
                $list = $list . "\"{$this->$item}\", ";
            }
        }
        $list = rtrim($list, ", ");
//    dbg("=".__METHOD__.":Seat:sql_column_value_list()=$list");
        return $list;
    }  

/**
 * create a list of column names for SQL bindvalue statements. 
 */
    private function sql_column_parm_list() {
        
        $list = "";
        foreach ($this->SEAT_TABLE_COLUMNS as $item) {
            if ($item != "stamp") { # stamp must be null for auto-update
                $list = $list . ":{$item}, ";
            }
        }
        $list = rtrim($list, ", ");
    dbg("=".__METHOD__."=$list");
        return $list;
    }  


/**
 * create a list of pairs of column names with values, used for SQL INSERT    
 */
    private function sql_column_name_value_pairs() {
        
        $list = "";
        foreach ($this->SEAT_TABLE_COLUMNS as $item) {
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
 * set the data seats of this seat to the values found in $_POST.       
 */
    public function set_to_POST()
    {
    $this->set_game_id($_POST['game_id']);
    $this->set_member_id($_POST['member_id']);
    $this->set_response($_POST['response']);
    $this->set_note_member($_POST['note_member']);
    $this->set_note_master($_POST['note_master']);
    $this->set_stamp($_POST['stamp']);
    }

/**
 * get a seat row by game_id.                   
 */
    public function get($getType="")
    {
        dbg("+".__METHOD__."={$this->game_id};{$this->member_id};$getType");
        $query = $this->assembleGetQuery($getType);
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # get seats row
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
#            dbg("=".__METHOD__.";$this->game_id:rows=$row_count");
            if ($row_count == 1) {
                $row = $stmt->fetch();
                $this->setThisToSeatRow($row);
                #echo "row:"; var_dump($row); echo ".<br>";
            } elseif ($row_count < 1) {
#                dbg("=".__METHOD__.":Seat:get=seat not found");
                #error_log($e->getTraceAsString());
                dbg("-".__METHOD__."={$this->game_id};not found");
                throw new PokerException('Not found', 
                                         self::GET_ERR_ZERO,
                                         NULL, 
                                         array($this->game_id, $this->member_id));
/*
                if ($getType == 'prev') {
                } elseif ($getType == 'next') {
                    $this->getNew();
                    throw new PokerException('Add new seat (' . $this->game_id . ')', 42213);
                } else {
                    throw new PokerException('No seat found with this ID (' . $this->game_id . ')', 42212);
                }
*/
            } else {
#                dbg("=".__METHOD__.":Seat:get=multiple seat records found");
                #error_log($e->getTraceAsString());
                throw new PokerException('Multiple records for this seat were found', self::GET_ERR_MULTI);
            }
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
            throw new PokerException('mySQL Error', self::GET_ERR_PDO);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
        dbg("-".__METHOD__."={$this->game_id}");
    }


/**
 * get a seat row by game_id.                   
 */
    private function assembleGetQuery($getType)
    {
        dbg("+".__METHOD__.";{$getType}");
        $query = "SELECT * FROM seats ";
        $this_game = "game_id = \"$this->game_id\" ";
        $prev_game = "game_id = ("
                     .   "SELECT MAX(game_id) FROM seats "
                     .   "WHERE game_id < \"$this->game_id\") ";
        $next_game = "game_id = ("
                     .   "SELECT MIN(game_id) FROM seats "
                     .   "WHERE game_id > \"$this->game_id\") ";
        $this_player = "member_id = (\"$this->member_id\" ";
        $first_player = "member_id = (SELECT MIN(member_id) FROM seats ";
        $last_player  = "member_id = (SELECT MAX(member_id) FROM seats ";
        $next_player  = "member_id = (SELECT MIN(member_id) FROM seats "
                        .   "WHERE member_id > \"$this->member_id\" ";
        $prev_player  = "member_id = (SELECT MAX(member_id) FROM seats "
                        .   "WHERE member_id < \"$this->member_id\" ";

        
        switch ($getType) {
        case 'preg': 
/*
            $QUOLD = "SELECT * FROM seats " .
                     # previous game
                     "WHERE game_id = (" . 
                         "SELECT MAX(game_id) FROM seats " . 
                         "WHERE game_id < \"$this->game_id\") " .
                     # last player
                     "AND member_id = (" . 
                         "SELECT MAX(member_id) FROM seats " . 
                         # previous game
                         "WHERE game_id = (" . 
                             "SELECT MAX(game_id) FROM seats " . 
                             "WHERE game_id < \"$this->game_id\")) ";
            dbg("=".__METHOD__.";$QUOLD!");
*/
            $query .= "WHERE $prev_game " .
                      "AND $last_player "
                      .   "WHERE $prev_game) ";
                break;
        case 'prep': 
/*
            $QUOLD = "SELECT * FROM seats " .
                             # this game
                             "WHERE game_id = \"$this->game_id\" " .
                             # previous player
                             "AND member_id = (" . 
                                 "SELECT MAX(member_id) FROM seats " . 
                                 "WHERE member_id < \"$this->member_id\" " .
                                 # this game
                                 "AND game_id = \"$this->game_id\")";
            dbg("=".__METHOD__.";$QUOLD!");
*/
            $query .= "WHERE $this_game " .
                      "AND $prev_player "
                      .   "AND $this_game) ";
            break;
        case 'nexg':  
/*
            $QUOLD = "SELECT * FROM seats " .
                         # next game
                         "WHERE game_id = (" . 
                             "SELECT MIN(game_id) FROM seats " . 
                             "WHERE game_id > \"$this->game_id\") " .
                         # first player
                         "AND member_id = (" . 
                             "SELECT MIN(member_id) FROM seats " . 
                             # next game
                             "WHERE game_id = (" . 
                                 "SELECT MIN(game_id) FROM seats " . 
                                     "WHERE game_id > \"$this->game_id\")) ";
            dbg("=".__METHOD__.";$QUOLD!");
*/
            $query .= "WHERE $next_game " . 
                      "AND $first_player "
                      .   "WHERE $next_game) ";
            break;
        case 'nexp':
/*
            $QUOLD = "SELECT * FROM seats " .
                         # this game
                         "WHERE game_id = \"$this->game_id\" " .
                         # next player
                         "AND member_id = (" . 
                             "SELECT MIN(member_id) FROM seats " . 
                             "WHERE member_id > \"$this->member_id\" " .
                             # this game
                             "AND game_id = \"$this->game_id\") ";
            dbg("=".__METHOD__.";$QUOLD!");
*/
            $query .= "WHERE $this_game " . 
                      "AND $next_player "
                      .   "AND $this_game) ";
            break;
        default:
/*
            $QUOLD = "SELECT * FROM seats " .
                     "WHERE game_id = \"$this->game_id\" " . 
                     "AND member_id = \"$this->member_id\" ";
            dbg("=".__METHOD__.";$QUOLD!");
*/
            $query .= "WHERE $this_game " . 
                      "AND $this_player) ";
            break;
        }
        dbg("-".__METHOD__."={$query}");
        return ($query);
    }
/**
 * populate a seat object with data from a seat table row        
 */
    protected function setThisToSeatRow($row) 
    {
        $this->game_id = $row['game_id'];
        $this->member_id = $row['member_id'];
        $this->response = $row['response'];
        $this->note_member = $row['note_member'];
        $this->note_master = $row['note_master'];
        $this->stamp = $row['stamp'];
    }

/**
 * Get the next available game
 */

    public function getNew()
    {
        
        dbg("+".__METHOD__.";");
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # get seat
            $query = "SELECT game_id, member_id FROM seats " .
                     "WHERE game_id =  ("
                     .   "SELECT MAX(game_id) FROM seats) ";
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $foo = array($stmt->fetch());
            $this->game_id = $foo[0][0];
#            dbg("=".__METHOD__.":$this->game_id");
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
        } catch (Exception $e) {
            echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
        }
        dbg("-".__METHOD__.";{$this->game_id}:{$this->member_id}");
    }


/**
 * List a seat
 */
    public function listing()
    {
        echo __METHOD__.".<br>";
        $this->listIt(".<br>");
    }

    public function listRow()
    {
        echo __METHOD__.".<br>";
        $this->listIt("; ");
        echo ".<br>";
    }

    private function listIt($d)
    {
        echo "game_id=$this->game_id$d";
        echo "member_id=$this->member_id$d";
        echo "response=$this->response$d";
        echo "note_master=$this->note_master$d";
        echo "note_member=$this->note_member$d";
        echo "stamp=$this->stamp$d";
    }

    public function dump()
    {
        echo "Seat.dump.<br>";
        var_dump($this);
        echo ".<br>";
    }

/**
 * find a seats row by game_id.
 */
    public function find()
    {
        
        $row_count = -1;
        dbg("+".__METHOD__."={$this->game_id}");
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # find seat rows
            $query = "SELECT * FROM seats " . 
                     "WHERE game_id = \"$this->game_id\"  " .
                     "AND member_id = \"$this->member_id\"  ";
            dbg("=".__METHOD__.":query=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            dbg("=".__METHOD__.":$this->game_id:rows=$row_count");
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
            throw new PokerException('PDO Exception', FIND_ERR_PDO, $e);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
        dbg("-".__METHOD__."={$this->game_id}=$row_count");
        return($row_count);
    }

/**
 * insert a seats row from a seat object
 */
    public function insert()
    {
        
        $val_errors = array ();
        dbg("+".__METHOD__.";$this->game_id:{$this->sql_column_name_value_pairs()}");
        $val_errors = ($this->validate());
        if (sizeof($val_errors) == 0 ) {
            try {
require(BASE_URI . "includes/pok.open.inc.php");
                # insert seat
                $query = "INSERT INTO seats ({$this->sql_column_name_list()}) " .
                  "VALUES ({$this->sql_column_parm_list()})";
                dbg("=".__METHOD__.":query=$query");
                $stmt = $pokdb->prepare($query);
                $stmt->bindValue(':game_id', $this->game_id, PDO::PARAM_INT);
                $stmt->bindValue(':member_id', $this->member_id, PDO::PARAM_INT);
                $stmt->bindValue(':response', $this->response, PDO::PARAM_STR);
                $stmt->bindValue(':note_member', $this->note_member, PDO::PARAM_STR);
                $stmt->bindValue(':note_master', $this->note_master, PDO::PARAM_STR);
//                $stmt->bindValue(':stamp', $this->stamp, PDO::PARAM_INT);
                $stmt->execute();
            } catch (PDOException $e) {
                # case [23000]: Integrity constraint violation ... Duplicate entry
                # case 42000: SQLSTATE[42000]: Syntax error or access violation:
                throw new PokerException("PDO Exception:{$e->getCode()}:{$e->getMessage()}",
                                         self::INS_ERR_PDO,
                                         $e);
            } catch (Exception $e) {
                echo "Seat.insert: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new PokerException($e);
            }
        } else {
            throw new PokerException("Data validation errors", 
                                     self::INS_ERR_VALIDTN, 
                                     null, 
                                     $val_errors);
        }
        dbg("-".__METHOD__.";$this->game_id;$this->member_id;errs=".sizeof($val_errors)."");
    }

/**
 * update a seats row from a seat object                         
 */
    public function update()
    {
        
        dbg("+".__METHOD__."=$this->game_id");
        $val_errors = $this->validate();
//    dbg("=".__METHOD__.":Seat.update error list size:"; echo sizeof($val_errors); echo "");
        if (sizeof($val_errors) == 0 ) {
            try {
# open database
require(BASE_URI . "includes/pok.open.inc.php");
                # update seat
                $update = "UPDATE seats SET {$this->sql_column_name_value_pairs()} " . 
                          "WHERE game_id = \"{$this->game_id}\" " .
                          "AND member_id = \"{$this->member_id}\" ";
                dbg("=".__METHOD__.";stmt_str=$update");
                $stmt = $pokdb->prepare($update);
                $stmt->execute();
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    #error_log($e->getTraceAsString());
                    throw new PokerException('Duplicate entry', self::UPD_ERR_DUP, $e);
                } else {
                    echo "Seat.update: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                    throw new PokerException('Unknown PDO error', self::UPD_ERR_PDO, $e);
                }
            } catch (Exception $e) {
                echo "Seat.update: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new PokerException($e);
            }
        } else {
            throw new PokerException("Data validation errors", 
                                     self::UPD_ERR_VALIDTN, 
                                     null, 
                                     $val_errors);
        }
        dbg("-".__METHOD__.";$this->game_id:".sizeof($val_errors)."");
    }
/**
 * delete all seats and seats rows for a seat                         
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
                dbg("=".__METHOD__.";stmt_str=$delete");
                $stmt = $pokdb->prepare($delete);
                $stmt->execute();
                $deleted_seats = $stmt->rowCount();
                $delete = "DELETE FROM seats " . 
                      " WHERE game_id = \"{$this->game_id}\" ";
                dbg("=".__METHOD__.";stmt_str=$delete");
                $stmt = $pokdb->prepare($delete);
                $stmt->execute();
                $deleted_seats = $stmt->rowCount();
            } catch (PDOException $e) {
                echo "Seat.delete: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                throw new PokerException('Unknown error', -42110, $e);
            } catch (Exception $e) {
                echo "Seat.delete: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new PokerException($e);
            }
//        } else {
//            throw new playerException("Data validation errors", 2104, null, $val_errors);
//        }
        dbg("-".__METHOD__.";$this->game_id");
    }

/**
 * create a fictitious seat for test purposes                         
 */
    function testSeat()
    {
        
        dbg("+".__METHOD__."");
        # game_id
        if ($this->get_game_id() < 1) { # get a random game number
/*
# open database
require(BASE_URI . "includes/pok.open.inc.php");
            $query = "SELECT MAX(game_id) as max FROM games "; 
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row = array($stmt->fetch());
            $max = $row[0][0];
*/
            $gam = new Game;
            $gam->getNew();
            $max = $gam->get_game_id() - 1;
//            $testGame = new Game;
//echo "max:$max.<br>";
            $game_id = rand(1,$max);
//            $game_id = $max + 1;
            $this->set_game_id($game_id);
//            $testGame->set_game_id($game_id);
            dbg("=".__METHOD__.";max game=$max; game_id=$game_id");
        }
        # member_id that's not already in this game
        if ($this->get_member_id() < 1) { # get a random member number
            # get the highest member number
/*
# open database
require(BASE_URI . "includes/pok.open.inc.php");
            $query = "SELECT MAX(member_id) as max FROM members "; 
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row = array($stmt->fetch());
            $max = $row[0][0];
*/
            $mem = new Member;
            $mem->get_next_id();
            $max = $mem->get_member_id() - 1;
            dbg("=".__METHOD__.";max member=$max");
            $found = FALSE;
            $id_list = array();
            while ($found == FALSE && count($id_list) < $max) {
                # get a random member_id
                do {
                    $mem_id = rand(1,$max);
                    dbg("=".__METHOD__.";random mem_id=$mem_id");
                } while (in_array($mem_id, $id_list));
                array_push($id_list, $mem_id);  # save it
                $mem->set_member_id($mem_id);
                if ($mem->find() == 1) { # valid member number?
                    dbg("=".__METHOD__.";valid mem_id=$mem_id");
                    $this->set_member_id($mem_id);
                    if ($this->find() == 0) { # seat doesn't yet exist?
                        dbg("=".__METHOD__.";empty seat=$mem_id");
                        $found = TRUE;
                    }
                }
            }
        }
        # response
//        $SEAT_RESPONSES = array('I','Y','N','M','F');
        $response = $this->SEAT_RESPONSES[rand(0,4)];
        $this->response = $response;
        $t = new testPerson;
        $this->note_member = $t->get_note();
        $this->note_master = $t->get_quote();
//        $this->member_id = rand(1,$high);
        dbg("-".__METHOD__.";{$this->game_id}:{$this->member_id}:{$this->response}:{$this->note_member}:{$this->note_master}");
        unset($testy);
/*
        # response
require("../inc/testdb_open.php"); #
        $nameCount = $this->getRowCount($testdb, "family_names");
        $nameId = rand(1, $nameCount);
        $query="SELECT * FROM family_names WHERE name_id = $nameId";
//    echo "testSeat family_names query=$query.<br>";
        $stmt = $testdb->prepare($query);
        $stmt->execute();
        $row_count = $stmt->rowCount();
        dbg("=".__METHOD__.":Seat.testSeat rows:$row_count");
        if ($row_count == 1) {
            $row = $stmt->fetch();
            $this->response = $row['response'];
#      echo "Seat.testSeat response=$this->response.<br>";
        } else {
            echo "testSeat __construct response error: Too many rows:$row_count.<br>";
            throw new Exception('testSeat response SELECT error: Too many rows', -1);
        }
        # note_member
        $gender = rand(0, 1); 
        if ($gender) {
            $table_name = "male_names";
        } else {
            $table_name = "female_names";
        }
        $nameCount = $this->getRowCount($testdb, $table_name);
        $nameId = rand(1, $nameCount); # Pick random male, female
        $query = "SELECT note_member FROM $table_name WHERE name_id = $nameId";
        $stmt = $testdb->prepare($query);
#    dbg("=".__METHOD__.":Seat.testSeat stmt:"; $stmt->debugDumpParams(); echo "<br>"; }
        $stmt->execute();
        $row_count = $stmt->rowCount();
//    dbg("=".__METHOD__.":Seat.testSeat rows:$row_count");
        if ($row_count == 1) {
            $row = $stmt->fetch();
            $this->note_member = $row['note_member'];
//      echo "Seat.testSeat note_member=$this->note_member.<br>";
        } else {
            echo "testSeat __construct note_member error: Too many rows:$row_count.<br>";
            throw new Exception('testSeat note_member SELECT error: Too many rows', -1);
        }

#  echo "Next this:{$this->get_game_id()}.<br>";
        $this->set_member_id("M");
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
// end class Seat
//******************************************************************************
dbg("-".basename(__FILE__)."");
?>

