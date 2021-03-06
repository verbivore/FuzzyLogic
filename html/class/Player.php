<?php
/**
 * Class definition for a player.
 * File name: Player.php
 * @author David Demaree <dave.demaree@yahoo.com>
 *  Future: 
 *    Add bonus_cnt attribute and use it in score calc.
 *    Get the "ON UPDATE CURRENT_TIMESTAMP" working.
 *    Prevent overwriting player on update if member_id is changed.
 * ** History ***  
 * 14-04-05 Adding rank.  DHD
 * 14-04-02 Updated with Player::constants.  DHD
 * 14-03-23 Added dbg() function.  DHD
 * 14-03-20 Updated for phpDoc.  DHD
 * 14-03-18 Fixed final echo in insert().  Updated 2210 message. 
 *          Moved attributes to top.  
 *          Changed file name from player.class.php to Player.php.
 *          Fixed set_to_POST.  DHD
 * 14-03-08 Original.  DHD
 */
dbg("+".basename(__FILE__)."");

class Player extends Member
{

/**
 * Attributes
 */
    protected $invite_cnt;
    protected $yes_cnt;
    protected $maybe_cnt;
    protected $no_cnt;
    protected $flake_cnt;
    protected $score;
    protected $rank;

/**
 * List of names of SQL columns for the members table
 */
    private $PLAYER_TABLE_COLUMNS = array("member_id", "nickname", "name_last", 
                "name_first", "status", "email", "phone", "stamp");

// Error message constants
// 1st digit: 0=Info, 1=Warning, 3&4=Validation, 6=Navigation, 8=DB, 9=PDO
// 2nd digit: Module: 3=Members, 4=Players, 5=Games, 6=Seats
// 3rd digit: Method: 1=Validate, 2=Find, 3=Get, 4=Insert, 5=Update, 6=Delete
// 4&5 digit: Id
    const FIND_ERR_ZERO    = 84210; # No row found
    const FIND_ERR_ONE     = 84211; # One row found
    const FIND_ERR_MULTI   = 84212; # Multiple rows found
    const FIND_ERR_PDO     = 94200; # PDO error

    const GET_INFO_ADD_NEW = 04301; # Ready to add a new entry
    const GET_WARN_NO_PREV = 64363; # No previous entry found
    const GET_WARN_NO_NEXT = 64364; # No next entry found
    const GET_WARN_NO_SEAT = 64367; # No seat rows found
    const GET_ERR_ZERO     = 84310; # No row found
    const GET_ERR_ONE      = 84311; # One row found
    const GET_ERR_MULTI    = 84312; # Multiple rows found
    const GET_ERR_ARR_1ST  = 84371; # 1st Get failed for Array
    const GET_ERR_ARR      = 84370; # Get failed for Array
    const GET_ERR_PDO      = 94300; # PDO error
    const GET_ERR_NEXT_PDO = 94301; # Next PDO error
    const GET_ERR_NEW_PDO  = 94317; # PDO error on getNew
    const GET_ERR_ARR_PDO  = 94370; # 1st Get PDO failure for Array

    const INS_ERR_VALIDTN  = 34400; # Insert failed: data validation error(s)
    const INS_ERR_DUP      = 84402; # Insert failed: duplicate key
    const INS_ERR_PDO      = 94400; # PDO error

    const UPD_ERR_VALIDTN  = 34500; # Insert failed: data validation error(s)
    const UPD_ERR_ZERO     = 84510;
    const UPD_ERR_DUP      = 84511;
    const UPD_ERR_MULTI    = 84512;
    const UPD_ERR_PDO      = 94500;

    const DEL_ERR_ZERO     = 84610;
    const DEL_ERR_MULTI    = 84612;
    const DEL_ERR_PDO      = 94618;

/**
 * constructor
 */
    function __construct()
    {
        global $debug;
#    dbg("=".__METHOD__.":player:__construct.");
        parent::__construct();
        $this->invite_cnt = null;
        $this->yes_cnt = null;
        $this->maybe_cnt = null;
        $this->no_cnt = null;
        $this->flake_cnt = null;
        $this->score = null;
        $this->rank = null;
    }

/**
 * Getters
 */
    public function get_invite_cnt() { return $this->invite_cnt; }
    public function get_yes_cnt() { return $this->yes_cnt; }
    public function get_maybe_cnt() { return $this->maybe_cnt; }
    public function get_no_cnt() { return $this->no_cnt; }
    public function get_flake_cnt() { return $this->flake_cnt; }
    public function get_score() { return $this->score; }
    public function get_rank() { return $this->rank; }

/**
 * Setters
 */
    public function set_invite_cnt($P) { $this->invite_cnt = $P; }
    public function set_yes_cnt($P) { $this->yes_cnt = $P; }
    public function set_maybe_cnt($P) { $this->maybe_cnt = $P; }
    public function set_no_cnt($P) { $this->no_cnt = $P; }
    public function set_flake_cnt($P) { $this->flake_cnt = $P; }
    public function set_score($P) { $this->score = $P; }
    public function set_rank($P) { $this->rank = $P; }

/**
 * Validate members fields
 */
    public function validate()
    {
        global $debug;
        dbg("+".__METHOD__."={$this->member_id}.");
        $errors = array();
        $foo = array();
        # validate fields
        foreach ($this->PLAYER_TABLE_COLUMNS as $column) {
            $func = "validate_$column";
//            dbg("=".__METHOD__.":player.validate column={$func}.");
            $foo = $this->$func();
//            dbg("=".__METHOD__.":player.validate col:$column="; var_dump($foo); echo ".");
            if ($foo[0]) {
                $errors["$column"][0] = $foo[0];
                $errors["$column"][1] = $foo[1];
//            dbg("=".__METHOD__.":player.validate col:$column:$foo[0]:$foo[1].");
            }
        }
        foreach ($errors as $col => $val) {
//        echo "player.validate errors=$col:"; list($n,$s) = $val; echo "$n:$s.");
            dbg("=".__METHOD__.": errors=$col:{$val[0]}:{$val[1]}");
        }
        dbg("-".__METHOD__.";arraysize=" . sizeof($errors));
        return($errors);
    }

/*
//***
 * Validation for individual column values.
//***
    public function validate_member_id() {
        # numeric
        # !> highest existing member_id + 1
//    dbg("=".__METHOD__.":player.validate_member_id=$this->member_id.");
        $e = array(0,"");
        return($e);
    }

    public function validate_nickname() {
        # "S" or "M"
        $e = array(0,"");
        return($e);
    }

    public function validate_name_last() {
        # string
        # alphabetic/spaces, starts with capital?, 
//  public function validate_name_last() { if(is_string($P)) { $this->name_last = (string)$P; } }
        $e = array(0,"");
        return($e);
    }

    public function validate_name_first() {
        # string
        # alphabetic/spaces, starts with capital?, 
        $e = array(0,"");
        return($e);
    }
*/

    public function validate_stamp() {
        # string?, 
        $e = array(0,"");
        return($e);
    }

/**
 * create a list of comma-separated column names for SQL statements.          
 */
    private function sql_column_name_list() {
        global $debug;
        $list = "";
        foreach ($this->PLAYER_TABLE_COLUMNS as $item) {
            $list .= "$item, ";
        }
        $list = rtrim($list, ", ");
        dbg("=".__METHOD__.":player:sql_column_name_list()=$list.");
        return $list;
    }  

/**
 * create a list of quoted, comma-separated column values for SQL statements. 
 */
    private function sql_column_value_list() {
        global $debug;
        $list = "";
        foreach ($this->PLAYER_TABLE_COLUMNS as $item) {
            $list = $list . "\"{$this->$item}\", ";
        }
        $list = rtrim($list, ", ");
        dbg("=".__METHOD__.":player:sql_column_value_list()=$list.");
        return $list;
    }  

/**
 * create a list of pairs of column names with values, used for SQL INSERT    
 */
    private function sql_column_name_value_pairs() {
        global $debug;
        $list = "";
        foreach ($this->PLAYER_TABLE_COLUMNS as $item) {
            if ($item == "stamp") { # stamp must be null for auto-update
                $list = $list . "$item = NULL, ";
            } else {
                $list = $list . "$item = \"{$this->$item}\", ";
            }
        }
        $list = rtrim($list, ", ");
        dbg("=".__METHOD__.":player:sql_column_name_value_pairs()=$list.");
        return $list;
    }  

/**
 * set the data members of this player to the values found in $_POST.       
 */
    public function set_to_POST()
    {
    parent::set_to_POST();
    $this->set_invite_cnt($_POST['invite_cnt']);
    $this->set_yes_cnt($_POST['yes_cnt']);
    $this->set_maybe_cnt($_POST['maybe_cnt']);
    $this->set_no_cnt($_POST['no_cnt']);
    $this->set_flake_cnt($_POST['flake_cnt']);
    $this->set_score($_POST['score']);
    $this->set_rank($_POST['rank']);

    }

/**
 * get a player row by member_id.                   
 */
    public function get($getType="")
    {
        global $debug;
        dbg("+".__METHOD__.";$getType:{$this->member_id}.");
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            switch ($getType) {
                case 'prev':
                    $query = "SELECT * FROM members WHERE member_id = " . 
                             "(SELECT MAX(member_id) FROM members " . 
                             "WHERE member_id < \"$this->member_id\") ";
                    break;
                case 'next':
                    $query = "SELECT * FROM members WHERE member_id = " . 
                             "(SELECT MIN(member_id) FROM members " . 
                             "WHERE member_id > \"$this->member_id\") ";
                    break;
                default:
                    $query = "SELECT * FROM members " . 
                             "WHERE member_id = \"$this->member_id\" ";
                    break;
            }
            # get members row
//            $query = "SELECT * FROM members " . 
//                     "WHERE member_id = \"$this->member_id\" ";
#            dbg("=".__METHOD__.":player:members:get:query=$query.");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            dbg("=".__METHOD__.";$this->member_id:rows=$row_count.");
            if ($row_count == 1) {
                $row = $stmt->fetch();
                $this->setThisToMemberRow($row);
                # get seats rows
                $query = "SELECT * FROM seats " . 
                         "WHERE member_id = $this->member_id ";
                dbg("=".__METHOD__.";seats:get:query=$query.");
                $stmt = $pokdb->prepare($query);
                $stmt->execute();
                $row_count = $stmt->rowCount();
                dbg("=".__METHOD__.";seats:get:$this->member_id:rows=$row_count.");
                if ($row_count > 0) {
                    $rows = $stmt->fetchAll();
                    $this->setThisToSeatsRows($rows);
                } else {
#                  dbg("=".__METHOD__.";seats:get=player not found.");
                    #error_log($e->getTraceAsString());
                    #Future: suppress this message for Game::burp.
                    dbg("-".__METHOD__."={$this->member_id};no invites yet.");
                    throw new PokerException('Player ' . $this->member_id . ' has not been invited to any games yet', 
                                             self::GET_WARN_NO_SEAT,
                                             NULL);
                }
            } elseif ($row_count < 1) {
                if ($getType == 'prev') {
                    dbg("-".__METHOD__."={$this->member_id};no prev player.");
                    throw new PokerException('No previous player for ID ' . $this->member_id . ' found', 
                                              self::GET_WARN_NO_PREV,
                                              NULL);
                } elseif ($getType == 'next') {
                    dbg("-".__METHOD__."={$this->member_id};no next player.");
                    $this->get_next_id();
                    throw new PokerException('Add new player (' . $this->member_id . ')', 
                                              self::GET_INFO_ADD_NEW,
                                              NULL);
                } else {
                    dbg("-".__METHOD__."={$this->member_id};no player found.");
                    throw new PokerException('No player found with this ID (' . $this->member_id . ')', 
                                              self::GET_ERR_ZERO,
                                              NULL);
                }
            } else {
                dbg("-".__METHOD__."={$this->member_id};Multiple player rows.");
                throw new PokerException('Multiple records for player ' . $this->member_id . ' were found', 
                                          self::GET_ERR_MULTI,
                                          NULL);
            }
        } catch (PDOException $e) {
//            $err_string = "PDO Exception: " . __FILE__ . " line: " . __LINE__ . "<br>" . $e->getCode() . ": " . $e->getMessage() . "<br>";  
//            echo $err_string;
            dbg("-".__METHOD__."={$this->member_id};PDO exception.");
            throw new PokerException('PDO Exception, get player:' . $this->member_id, 
                                      self::GET_ERR_PDO,
                                      NULL);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
        dbg("-".__METHOD__.";$getType:{$this->member_id}.");
    }
//******************************************************************************
// populate a player object with data from a player table row        
//******************************************************************************
/*  protected function setThisToPlayerRow($row) 
    {
        $this->member_id = $row['member_id'];
        $this->nickname = $row['nickname'];
        $this->name_last = $row['name_last'];
        $this->name_first = $row['name_first'];
        $this->stamp = $row['stamp'];
    }
*/
/**
 * populate a player object with data from the seats table        
 */
    protected function setThisToSeatsRows($rows) 
    {
        $invites = 0;
        $yess = 0;
        $maybes = 0;
        $nos = 0;
        $flakes = 0;
        foreach($rows as $row) {
            $invites++;
            switch ($row['response']) {
                case 'I':
                    break;
                case 'Y':
                    $yess++;
                    break;
                case 'N':
                    $nos++;
                    break;
                case 'M':
                    $maybes++;
                    break;
                case 'F':
                    $flakes++;
                    break;
            }
        }
        $this->invite_cnt = $invites;
        $this->yes_cnt = $yess;
        $this->maybe_cnt = $maybes;
        $this->no_cnt = $nos;
        $this->flake_cnt = $flakes;
        $this->score = ($yess + $maybes + $nos - $flakes) / $invites;
        $this->rank = 0;
    }

/**
 * List a player
 */
    public function listing()
    {
        echo "player." . __FUNCTION__ . ".<br>";
        $this->listIt(".<br>");
    }

    public function listRow()
    {
        echo "player." . __FUNCTION__ . ":";
        $this->listIt("; ");
        echo ".<br>";
    }

    private function listIt($d)
    {
        echo "member_id=$this->member_id$d";
        echo "nickname=$this->nickname$d";
        echo "name_last=$this->name_last$d";
        echo "name_first=$this->name_first$d";
        echo "stamp=$this->stamp$d";
        echo "invites=$this->invite_cnt$d";
        echo "yess=$this->yes_cnt$d";
        echo "maybes=$this->maybe_cnt$d";
        echo "nos=$this->no_cnt$d";
        echo "flakes=$this->flake_cnt$d";
        echo "score=$this->score$d";
        echo "rank=$this->rank$d";
    }

    public function dump()
    {
        echo "<br>player.dump.<br>";
        var_dump($this);
        echo ".<br>\n";
    }

/**
 * find a members row by member_id.
 */
    public function find()
    {
        global $debug;
        $row_count = -1;
        dbg("+".__METHOD__."={$this->member_id}.");
        try {
            require(BASE_URI . "includes/pok.open.inc.php");
            # find player rows
            $query = "SELECT * FROM members " . 
                     "WHERE member_id = \"$this->member_id\"  ";
            dbg("=".__METHOD__.":player:find:query=$query.");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            dbg("=".__METHOD__.":player:find:$this->member_id:rows=$row_count.");
        } catch (PDOException $e) {
            dbg("-".__METHOD__."={$this->member_id};PDO exception.");
            throw new PokerException(__METHOD__ . ' PDO error', 
                                     self::FIND_ERR_PDO, 
                                     $e);
        }
        dbg("-".__METHOD__."={$this->member_id}.");
        return($row_count);
    }

/**
 * insert a members row from a player object
 */
    public function insert()
    {
        global $debug;
        $val_errors = array ();
        dbg("+".__METHOD__.";$this->member_id:{$this->sql_column_name_value_pairs()}.");
        $val_errors = ($this->validate());
        if (sizeof($val_errors) == 0 ) {
            try {
                require(BASE_URI . "includes/pok.open.inc.php");
                # insert player FUTURE: move to Member
                $query = "INSERT INTO members ({$this->sql_column_name_list()}) " .
                  "VALUES ({$this->sql_column_value_list()})" ;
                dbg("=".__METHOD__.":player:find:query=$query.");
                $stmt = $pokdb->prepare($query);
                $stmt->execute();
            } catch (PDOException $e) {
                dbg("-".__METHOD__."={$this->member_id};PDO exception.");
                switch ($e->getCode()) {
                case 23000:
                    #error_log($e->getTraceAsString());
                    throw new PokerException('Duplicate entry', 
                                             self::INS_ERR_DUP, 
                                             $e);
                    break;
                case 42000:
                    #error_log($e->getTraceAsString());
                    throw new PokerException('PDO syntax error', 
                                             self::INS_ERR_PDO_SYN, 
                                             $e);
                    break;
                default:
                    throw new PokerException(__METHOD__ . ' PDO error', 
                                             self::INS_ERR_PDO, 
                                             $e);
                    break;
                }
            }
        } else {
            dbg("-".__METHOD__."={$this->member_id};validation exception.");
            throw new PokerException("Player (" . $this->member_id . ") insert validation errors", 
                                     self::INS_ERR_VALIDTN, 
                                     null, 
                                     $val_errors);
        }
        dbg("-".__METHOD__.":player added:$this->member_id.");
    }

/**
 * update a members row from a player object                         
 * Future: use Members::update?
 */
    public function update()
    {
        global $debug;
        dbg("+".__METHOD__.";$this->member_id:$this->stamp.");
        $val_errors = $this->validate();
//    dbg("=".__METHOD__.":player.update error list size:"; echo sizeof($val_errors); echo ".");
        if (sizeof($val_errors) == 0 ) {
            try {
require(BASE_URI . "includes/pok.open.inc.php");
                # update player
                $this->set_stamp(null);
                $update = "UPDATE members SET {$this->sql_column_name_value_pairs()} " . 
                      " WHERE member_id = \"{$this->member_id}\" ";
                dbg("=".__METHOD__.":player:update:stmt_str=$update.");
                $stmt = $pokdb->prepare($update);
                $stmt->execute();
            } catch (PDOException $e) {
                dbg("-".__METHOD__."={$this->member_id};PDO exception.");
                switch ($e->getCode()) {
                case 23000:
                    #error_log($e->getTraceAsString());
                    throw new PokerException('Duplicate entry', 
                                             self::UPD_ERR_DUP, 
                                             $e);
                    break;
                case 42000:
                    #error_log($e->getTraceAsString());
                    throw new PokerException('PDO syntax error', 
                                             self::UPD_ERR_PDO_SYN, 
                                             $e);
                    break;
                default:
                    throw new PokerException(__METHOD__ . ' PDO error', 
                                             self::UPD_ERR_PDO, 
                                             $e);
                    break;
                }
            }
        } else {
            dbg("-".__METHOD__."={$this->member_id};validation exception.");
            throw new PokerException("Player (" . $this->member_id . ") update validation errors", 
                                     self::UPD_ERR_VALIDTN, 
                                     null, 
                                     $val_errors);
        }
        dbg("-".__METHOD__.";$this->member_id.");
    }

/**
 * delete all members and seats rows for a player                         
 */
    public function delete()
    {
        dbg("+".__METHOD__.";$this->member_id");
        $deleted_seats = 0;
        $deleted_members = 0;
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # delete attendance
            $delete = "DELETE FROM seats " . 
                      " WHERE member_id = \"{$this->member_id}\" ";
            dbg("=".__METHOD__.":stmt_str=$delete.");
            $stmt = $pokdb->prepare($delete);
            $stmt->execute();
            $deleted_seats = $stmt->rowCount();
            # delete members row
            dbg("=".__METHOD__.":seats deleted:" . $deleted_seats . ".");
            $delete = "DELETE FROM members " . 
                      " WHERE member_id = \"{$this->member_id}\" ";
            dbg("=".__METHOD__.":stmt_str=$delete.");
            $stmt = $pokdb->prepare($delete);
            $stmt->execute();
            $deleted_members = $stmt->rowCount();
            dbg("=".__METHOD__.":members deleted:" . $deleted_members . ".");
        } catch (PDOException $e) {
//            echo "player.delete: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
            dbg("-".__METHOD__.";$this->member_id;$rows_deleted;$this->score;$this->stamp");
            throw new PokerException("Player (" . $this->member_id . ") delete PDO error", 
                                     self::DEL_ERR_PDO, 
                                     $e);
        }
        $rows_deleted = $deleted_seats + $deleted_members;
        if ($rows_deleted == 0 ) {
            throw new PokerException("Member ({$this->member_id}) not found", 
                                     self::DEL_ERR_ZERO, 
                                     NULL);
        }
        dbg("-".__METHOD__.";$this->member_id;$rows_deleted;$this->score;$this->stamp");
    }

//******************************************************************************
} // end class Player
//******************************************************************************
/*
class playerException extends Exception
{
#    global $debug;
        private $_options = array();
        // Redefine the exception so message isn't optional
        public function __construct($message, 
                                                                $code = 0, 
                                                                Exception $previous = null,
                                                                $options = array('params')) {

#        dbg("=".__METHOD__.":playerException={$message}:$code.");
                // make sure everything is assigned properly
                parent::__construct($message, $code, $previous);

                $this->_options = $options;

        }

        // custom string representation of object
        public function __toString() {
                return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        }

        public function GetOptions() { 
#    dbg("=".__METHOD__.":playerException:GetOptions="; echo sizeof($this->_options); echo ".");
        return $this->_options; 
        }
//******************************************************************************
} // end class playerException
//******************************************************************************
*/
dbg("-".basename(__FILE__)."");
?>

