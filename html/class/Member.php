<?php
/**
 *  Class definition for a member
 *  File name: Member.php
 *  @author David Demaree <dave.demaree@yahoo.com>
 *** History ***
 * 14-03-29 Added const error codes.  DHD
 * 14-03-23 Added status, email, phone.  DHD
 * 14-03-23 Added dbg().  DHD
 * 14-03-20 Updated for phpDoc.  DHD
 * 14-03-18 Renamed from member.class.php to Member.php.  Moved attributes to top. DHD
 * 14-03-08 Original.  DHD
 */
dbg("+".basename(__FILE__)."");

class Member
{

/**
 *Attributes
 */
    protected $member_id;
    protected $nickname;
    protected $name_last;
    protected $name_first;
    protected $status;
    protected $email;
    protected $phone;
    protected $stamp;

// List of names of SQL columns for the members table
    private static $MEMBER_TABLE_COLUMNS = array("member_id", "nickname", 
                   "name_last", "name_first", "status", "email", "phone", 
                   "stamp");

// Error message constants
// 1st digit: 0=Info, 1=Warning, 3&4=Validation, 6=Navigation, 8=DB, 9=PDO
// 2nd digit: Module: 3=Members, 4=Players, 5=Games, 6=Seats
// 3rd digit: Method: 1=Validate, 2=Find, 3=Get, 4=Insert, 5=Update, 6=Delete
// 4&5 digit: Id
    const FIND_ERR_ZERO    = 83210; # No row found
    const FIND_ERR_ONE     = 83211; # One row found
    const FIND_ERR_MULTI   = 83212; # Multiple rows found
    const FIND_ERR_PDO     = 93200; # PDO error

    const GET_INFO_ADD_NEW = 03301; # Ready to add a new entry
    const GET_WARN_NO_PREV = 63363; # No previous entry found
    const GET_WARN_NO_NEXT = 63364; # No next entry found
    const GET_ERR_ZERO     = 83310; # No row found
    const GET_ERR_ONE      = 83311; # One row found
    const GET_ERR_MULTI    = 83312; # Multiple rows found
    const GET_ERR_NEW_PDO  = 93317; # PDO error on getNew
    const GET_ERR_PDO      = 93300; # PDO error

    const INS_ERR_VALIDTN  = 33400; # Insert failed: data validation error(s)
    const INS_ERR_DUP      = 83402; # Insert failed: duplicate key
    const INS_ERR_PDO      = 93400; # PDO error

    const UPD_ERR_VALIDTN  = 33500; # Insert failed: data validation error(s)
    const UPD_ERR_ZERO     = 83510;
    const UPD_ERR_DUP      = 83511;
    const UPD_ERR_MULTI    = 83512;
    const UPD_ERR_PDO      = 93500;

    const DEL_ERR_ZERO     = 83610;
    const DEL_ERR_MULTI    = 83612;
    const DEL_ERR_PDO      = 93618;

/*
 * Constructor
 */
    function __construct()
    {
#    dbg("=".__METHOD__.";Member:__construct");
        $this->member_id = null;
        $this->nickname = null;
        $this->name_last = null;
        $this->name_first = null;
        $this->status = null;
        $this->email = null;
        $this->phone = null;
        $this->stamp = null;
    }

/**
 * Getters
 */
    public function get_member_id() { return $this->member_id; }
    public function get_nickname() { return $this->nickname; }
    public function get_name_last() { return ($this->name_last); }
    public function get_name_first() { return $this->name_first; }
    public function get_status() { return $this->status; }
    public function get_email() { return $this->email; }
    public function get_phone() { return $this->phone; }
    public function get_stamp() { return $this->stamp; }
    public function get_full_name() { return $this->name_first . " '" .  $this->nickname . "' " . $this->name_last; }
   

/**
 * Setters
 */
    public function set_member_id($P) { $this->member_id = $P; }
    public function set_nickname($P) { $this->nickname = $P; }
    public function set_name_last($P) { $this->name_last = $P; }
    public function set_name_first($P) { $this->name_first = $P; }
    public function set_status($P) { $this->status = $P; }
    public function set_email($P) { $this->email = $P; }
    public function set_phone($P) { $this->phone = $P; }
    public function set_stamp($P) { $this->stamp = $P; }

/**
 * Validation for individual column values.
 */
    public function validate_member_id() {
        # numeric
        # !> highest existing member_id + 1
//    dbg("=".__METHOD__.";Member.validate_member_id=$this->member_id");
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

    public function validate_status() {
        # string
        # alphabetic/spaces, starts with capital?, 
        $e = array(0,"");
        return($e);
    }

    public function validate_email() {
        # string
        # alphabetic/spaces, starts with capital?, 
        $e = array(0,"");
        return($e);
    }

    public function validate_phone() {
        # string
        # alphabetic/spaces, starts with capital?, 
        $e = array(0,"");
        return($e);
    }

/**
 * Validate members fields
 */
    public function validate()
    {
        dbg("+".__METHOD__."={$this->member_id}");
        $errors = array();
        $foo = array();
#    global $MEMBER_TABLE_COLUMNS;
        # validate fields
        foreach ($this->MEMBER_TABLE_COLUMNS as $column) {
            $func = "validate_$column";
//      dbg(.__METHOD__.":column={$func}");
            $foo = $this->$func();
//      dbg("=".__METHOD__." col:$column="; var_dump($foo); echo "");
            if ($foo[0]) {
                $errors["$column"][0] = $foo[0];
                $errors["$column"][1] = $foo[1];
//        dbg("=".__METHOD__." col:$column:$foo[0]:$foo[1]");
            }
        }
//        if ($debug) {
//        foreach ($errors as $col => $val) {
//            echo "Member.validate errors=$col:"; list($n,$s) = $val; echo "$n:$s");
//            echo "Member.validate errors=$col:$val[0]:$val[1].<br>"; }
//        }
        dbg("-".__METHOD__." arraysize=".sizeof($errors));
        return($errors);
    }

/**
 * create a list of comma-separated column names for SQL statements.          
 */
    private function sql_column_name_list() {
        $list = "";
        foreach ($this->MEMBER_TABLE_COLUMNS as $item) {
            if ($item != "stamp") {
                $list .= "$item, ";
            }
        }
        $list = rtrim($list, ", ");
//    dbg("=".__METHOD__."=$list");
        return $list;
    }  
/**
 * create a list of quoted, comma-separated column values for SQL statements. 
 */
    private function sql_column_value_list() {
        $list = "";
        foreach ($this->MEMBER_TABLE_COLUMNS as $item) {
            if ($item != "stamp") {
                $list = $list . "\"{$this->$item}\", ";
            }
        }
        $list = rtrim($list, ", ");
//        dbg("=".__METHOD__."=$list");
        return $list;
    }  

/**
 * create a list of pairs of column names with values, used for SQL INSERT    
 */
    private function sql_column_name_value_pairs() {
        $list = "";
        foreach ($this->MEMBER_TABLE_COLUMNS as $item) {
            if ($item != "stamp") {
                $list = $list . "$item = \"{$this->$item}\", ";
            }
        }
        $list = rtrim($list, ", ");
        dbg("=".__METHOD__."=$list");
        return $list;
    }  

/**
 * set the data members of this member to the values found in $_POST.       
 */
    public function set_to_POST()
    {
    $this->set_member_id($_POST['member_id']);
    $this->set_nickname($_POST['nickname']);
    $this->set_name_last($_POST['name_last']);
    $this->set_name_first($_POST['name_first']);
    $this->set_status($_POST['status']);
    $this->set_email($_POST['email']);
    $this->set_phone($_POST['phone']);
    $this->set_stamp($_POST['stamp']);
    }

/**
 * get a member row by member_id.                   
 */
//    public function get()
    public function get($getType="")
    {
        dbg("+".__METHOD__.";$getType={$this->member_id}");
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # get members row
            $query = "SELECT * FROM members " . 
                     "WHERE member_id = \"$this->member_id\" ";
#            dbg("=".__METHOD__.";Member:members:get:query=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
#            dbg("=".__METHOD__.";Member:members:get:$this->member_id:rows=$row_count");
            if ($row_count == 1) {
                $row = $stmt->fetch();
                $this->setThisTomemberRow($row);
            } elseif ($row_count < 1) {
                dbg("-".__METHOD__.";=member not found");
                #error_log($e->getTraceAsString());
                throw new PokerException('No records for this member were found', Member::GET_ERR_ZERO);
            } else {
                dbg("-".__METHOD__.";=multiple member records found");
                #error_log($e->getTraceAsString());
                throw new PokerException("Multiple {$row_count} records for this member were found", 32211);
            }
        } catch (PDOException $e) {
            dbg("-".__METHOD__.";=PDO Exception");
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
            throw new PokerException('PDO Exception: ', 32212);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
        dbg("-".__METHOD__.";$getType={$this->member_id}");
    }
/**
 * populate a member object with data from a member table row        
 */
    protected function setThisTomemberRow($row) 
    {
        $this->member_id = $row['member_id'];
        $this->nickname = $row['nickname'];
        $this->name_last = $row['name_last'];
        $this->name_first = $row['name_first'];
        $this->status = $row['status'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
        $this->stamp = $row['stamp'];
    }

/**
 * Get the next available member number        
 */
    public function get_next_id()
    {
        dbg("+".__METHOD__);
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # get member table
            $stmt = $pokdb->prepare("SELECT MAX(member_id) FROM members");
            $stmt->execute();
            $this->member_id = $stmt->fetchColumn() + 1;
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
        } catch (PokerException $e) {
            echo "PokerException: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
        }
        dbg("-".__METHOD__."=$this->member_id");
    }


/**
 * List a member
 */
    public function listing()
    {
        dbg("=".__METHOD__);
        $this->listIt(".<br>");
    }

    public function listRow()
    {
        dbg("=".__METHOD__);
        $this->listIt("; ");
        echo ".<br>";
    }

    private function listIt($d)
    {
        dbg("=".__METHOD__);
        echo "Member_id=$this->member_id$d";
        echo "nickname=$this->nickname$d";
        echo "name_last=$this->name_last$d";
        echo "name_first=$this->name_first$d";
        echo "status=$this->status$d";
        echo "email=$this->email$d";
        echo "phone=$this->phone$d";
        echo "stamp=$this->stamp$d";
    }

    public function dump()
    {
        dbg("=".__METHOD__);
        var_dump($this);
        echo ".<br>\n";
    }

/**
 * find a members row by member_id.
 */
    public function find()
    {
        dbg("+".__METHOD__.";Member:find={$this->member_id}");
        $row_count = -1;
        try {
require(BASE_URI . "includes/pok.open.inc.php");
            # find member rows
            $query = "SELECT * FROM members " . 
                                  "WHERE member_id = \"$this->member_id\"  ";
            dbg("=".__METHOD__.";query=$query");
            $stmt = $pokdb->prepare($query);
            $stmt->execute();
            $row_count = $stmt->rowCount();
            dbg("=".__METHOD__.";$this->member_id:rows=$row_count");
        } catch (PDOException $e) {
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
            throw new PokerException('PDO Exception', -2010, $e);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
        dbg("-".__METHOD__.";Member:find={$row_count}");
        return($row_count);
    }

/**
 * insert a members row from a member object
 */
/* not used yet
    public function insert()
    {
        $val_errors = array ();
        dbg("+".__METHOD__.";$this->member_id:$this->eff_date:
{$this->sql_column_name_value_pairs()}");
        $val_errors = ($this->validate());
        if (sizeof($val_errors) == 0 ) {
            try {
require(BASE_URI . "includes/pok.open.inc.php");
                # insert member
                $query = "INSERT INTO members ({$this->sql_column_name_list()}) " .
                  "VALUES ({$this->sql_column_value_list()})" ;
                dbg("=".__METHOD__.";query=$query");
                $stmt = $pokdb->prepare($query);
                $stmt->execute();
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    #error_log($e->getTraceAsString());
                    throw new PokerException('Duplicate entry', 2110, $e);
                } else {
                    echo "Member.insert: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                    throw new PokerException('Unknown error', -2110, $e);
                }
            } catch (Exception $e) {
                echo "Member.insert: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new PokerException($e);
            }
        } else {
            throw new PokerException("Data validation errors", 2104, null, $val_errors);
        }
//    dbg("=".__METHOD__.";added");
//    $inserted_member_id = $pokdb->lastInsertId(); 
//    dbg("-".__METHOD__.";number:$inserted_member_id");
    }
*/
/**
 * update a members row from a member object                         
 */
/* not used yet
    public function update()
    {
        dbg("+".__METHOD__.";Member.update:$this->member_id:$this->eff_date.<br/
>"; }
        $val_errors = $this->validate();
//    dbg("=".__METHOD__.";Member.update error list size:"; echo sizeof($val_errors); echo "");
        if (sizeof($val_errors) == 0 ) {
            try {
                require(BASE_URI . "includes/pok.open.inc.php");
                # update member
                $update = "UPDATE members SET {$this->sql_column_name_value_pairs()} " . 
                      " WHERE member_id = \"{$this->member_id}\" AND eff_date = \"{$this->eff_date}\" ";
                dbg("=".__METHOD__.";Member:update:stmt_str=$update");
                $stmt = $pokdb->prepare($update);
                $stmt->execute();
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    #error_log($e->getTraceAsString());
                    throw new PokerException('Duplicate entry', 2110, $e);
                } else {
                    echo "Member.update: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                    throw new PokerException('Unknown error', -2110, $e);
                }
            } catch (Exception $e) {
                echo "Member.update: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new PokerException($e);
            }
        } else {
            throw new PokerException("Data validation errors", 2104, null, $val_errors);
        }
        dbg("-".__METHOD__.";Member.update:end:$this->member_id:$this->eff_date");
    }
*/
/**
 * create a fictitious member for test purposes                         
 */
    public function testMember()
    {
        dbg("=".__METHOD__);
        $act = array("A", "X");
        # id
        $this->get_next_id();
        $testy = new testPerson();
        $this->nickname = $testy->get_nickname();
        $this->name_last = $testy->get_surname();
        $this->name_first = $testy->get_name_first();
        $this->status = $act[rand(0,count($act)-1)];
        $this->email = $testy->get_email();
        $this->phone = $testy->get_phone();
    }

//******************************************************************************
} // end class Member
//******************************************************************************
dbg("-".basename(__FILE__)."");
?>

