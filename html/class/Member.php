<?php
/**
 *  Class definition for a member
 *  File name: Member.php
 *  @author David Demaree <dave.demaree@yahoo.com>
 *** History ***
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
    protected $stamp;

// List of names of SQL columns for the members table
    private static $MEMBER_TABLE_COLUMNS = array("member_id", "nickname", "name_last", 
                                                              "name_first", "stamp");

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
        $this->stamp = null;
    }

/**
 * Getters
 */
    public function get_member_id() { return $this->member_id; }
    public function get_nickname() { return $this->nickname; }
    public function get_name_last() { return ($this->name_last); }
    public function get_name_first() { return $this->name_first; }
    public function get_stamp() { return $this->stamp; }
    public function get_full_name() { return $this->name_first . " '" .  $this->nickname . "' " . $this->name_last; }
   

/**
 * Setters
 */
    public function set_member_id($P) { $this->member_id = $P; }
    public function set_nickname($P) { $this->nickname = $P; }
    public function set_name_last($P) { $this->name_last = $P; }
    public function set_name_first($P) { $this->name_first = $P; }
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
//  $this->set_stamp($_POST['stamp']);
    }

/**
 * get a member row by member_id.                   
 */
//    public function get()
    public function get($getType)
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
                throw new Exception('No records for this member were found', 32210);
            } else {
                dbg("-".__METHOD__.";=multiple member records found");
                #error_log($e->getTraceAsString());
                throw new Exception("Multiple {$row_count} records for this member were found", 32211);
            }
        } catch (PDOException $e) {
            dbg("-".__METHOD__.";=PDO Exception");
            echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
            throw new Exception('PDO Exception: ', 32212);
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
        } catch (Exception $e) {
            echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
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
        dbg("+".__METHOD__.";Member:find={$this->member_id}:{$this->eff_date}");
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
            throw new memberException('PDO Exception', -2010, $e);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
        }
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
                    throw new memberException('Duplicate entry', 2110, $e);
                } else {
                    echo "Member.insert: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                    throw new memberException('Unknown error', -2110, $e);
                }
            } catch (Exception $e) {
                echo "Member.insert: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new memberException($e);
            }
        } else {
            throw new memberException("Data validation errors", 2104, null, $val_errors);
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
                    throw new memberException('Duplicate entry', 2110, $e);
                } else {
                    echo "Member.update: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
                    throw new memberException('Unknown error', -2110, $e);
                }
            } catch (Exception $e) {
                echo "Member.update: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
                throw new memberException($e);
            }
        } else {
            throw new memberException("Data validation errors", 2104, null, $val_errors);
        }
        dbg("-".__METHOD__.";Member.update:end:$this->member_id:$this->eff_date");
    }
*/
/**
 * create a fictitious member for test purposes                         
 */
    function testData()
    {
        dbg("=".__METHOD__);
        # id
        $this->get_next_id();
        $testy = new testPerson();
        $this->name_last = $testy->get_name_last();
        $this->name_first = $testy->get_name_first();
        $this->nickname = $testy->get_nickname();
/*
        # name_last
require("../inc/testdb_open.php"); #
        $nameCount = $this->getRowCount($testdb, "family_names");
        $nameId = rand(1, $nameCount);
        $query="SELECT * FROM family_names WHERE name_id = $nameId";
//    echo "testData family_names query=$query.<br>";
        $stmt = $testdb->prepare($query);
        $stmt->execute();
        $row_count = $stmt->rowCount();
        dbg("=".__METHOD__.";Member.testData rows:$row_count");
        if ($row_count == 1) {
            $row = $stmt->fetch();
            $this->name_last = $row['name_last'];
#      echo "Member.testData name_last=$this->name_last.<br>";
        } else {
            echo "testData __construct name_last error: Too many rows:$row_count.<br>";
            throw new Exception('testData name_last SELECT error: Too many rows', -1);
        }
        # name_first
        $gender = rand(0, 1); 
        if ($gender) {
            $table_name = "male_names";
        } else {
            $table_name = "female_names";
        }
        $nameCount = $this->getRowCount($testdb, $table_name);
        $nameId = rand(1, $nameCount); # Pick random male, female
        $query = "SELECT name_first FROM $table_name WHERE name_id = $nameId";
        $stmt = $testdb->prepare($query);
#        dbg("=".__METHOD__.";Member.testData stmt:"; $stmt->debugDumpParams(); echo "");
        $stmt->execute();
        $row_count = $stmt->rowCount();
//        dbg("=".__METHOD__.";Member.testData rows:$row_count");
        if ($row_count == 1) {
            $row = $stmt->fetch();
            $this->name_first = $row['name_first'];
//          echo "Member.testData name_first=$this->name_first.<br>";
        } else {
            echo "testData __construct name_first error: Too many rows:$row_count.<br>";
            throw new Exception('testData name_first SELECT error: Too many rows', -1);
        }

#        echo "Next this:{$this->get_member_id()}.<br>";
        $this->set_nickname("M");
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
// end class Member
//******************************************************************************
dbg("-".basename(__FILE__)."");
?>

