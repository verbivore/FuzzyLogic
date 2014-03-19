<?php
/******************************************************************************
 *  File name: Member.php
 *  Created by: David Demaree
 *  Contact: dave.demaree@yahoo.com
 *  Purpose: Class definition for a member
 *** History ***  
 * 14-03-18 Renamed from member.class.php to Member.php.  Moved attributes to top. DHD
 * 14-03-08 Original.  DHD
 *****************************************************************************/

class Member
{

//*****************************************************************************
// Attributes
//*****************************************************************************
  protected $member_id;
  protected $nickname;
  protected $name_last;
  protected $name_first;
  protected $stamp;

//*****************************************************************************
// Getters
//*****************************************************************************
  public function get_member_id() { return $this->member_id; }
  public function get_nickname() { return $this->nickname; }
  public function get_name_last() { return ($this->name_last); }
  public function get_name_first() { return $this->name_first; }
  public function get_stamp() { return $this->stamp; }

//*****************************************************************************
// Setters
//*****************************************************************************
  public function set_member_id($P) { $this->member_id = $P; }
  public function set_nickname($P) { $this->nickname = $P; }
  public function set_name_last($P) { $this->name_last = $P; }
  public function set_name_first($P) { $this->name_first = $P; }
  public function set_stamp($P) { $this->stamp = $P; }

//*****************************************************************************
// Validation for individual column values.
//*****************************************************************************
  public function validate_member_id() {
    # numeric
    # !> highest existing member_id + 1
//    if ($debug) { echo "Member.validate_member_id=$this->member_id.<br>"; }
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


//*****************************************************************************
// Validate members fields
//*****************************************************************************
  public function validate()
  {
    global $debug;
    if ($debug) { echo "Member.validate={$this->member_id}.<br>"; }
    $errors = array();
    $foo = array();
#    global $MEMBER_TABLE_COLUMNS;
    # validate fields
    foreach ($this->MEMBER_TABLE_COLUMNS as $column) {
      $func = "validate_$column";
//      if ($debug) { echo "Member.validate column={$func}.<br>"; }
      $foo = $this->$func();
//      if ($debug) { echo "Member.validate col:$column="; var_dump($foo); echo ".<br>"; }
      if ($foo[0]) {
        $errors["$column"][0] = $foo[0];
        $errors["$column"][1] = $foo[1];
//        if ($debug) { echo "Member.validate col:$column:$foo[0]:$foo[1].<br>"; }
      }
    }
    if ($debug) {
    foreach ($errors as $col => $val) {
//      echo "Member.validate errors=$col:"; list($n,$s) = $val; echo "$n:$s.<br>"; }
      echo "Member.validate errors=$col:$val[0]:$val[1].<br>"; }
    }
#    if ($debug) { echo "Member.validate arraysize="; echo sizeof($errors); echo ".<br>"; }
    return($errors);
  }

//*****************************************************************************
// List of names of SQL columns for the members table
//*****************************************************************************
  private $MEMBER_TABLE_COLUMNS = array("member_id", "nickname", "name_last", 
                               "name_first", "stamp");

//******************************************************************************
// create a list of comma-separated column names for SQL statements.          
//******************************************************************************
  private function sql_column_name_list() {
    global $debug;
    $list = "";
    foreach ($this->MEMBER_TABLE_COLUMNS as $item) {
      $list .= "$item, ";
    }
    $list = rtrim($list, ", ");
//    if ($debug) { echo "Member:sql_column_name_list()=$list.<br>"; }
    return $list;
  }  

//******************************************************************************
// create a list of quoted, comma-separated column values for SQL statements. 
//******************************************************************************
  private function sql_column_value_list() {
    global $debug;
    $list = "";
    foreach ($this->MEMBER_TABLE_COLUMNS as $item) {
      $list = $list . "\"{$this->$item}\", ";
    }
    $list = rtrim($list, ", ");
//    if ($debug) { echo "Member:sql_column_value_list()=$list.<br>"; }
    return $list;
  }  

//******************************************************************************
// create a list of pairs of column names with values, used for SQL INSERT    
//******************************************************************************
  private function sql_column_name_value_pairs() {
    global $debug;
    $list = "";
    foreach ($this->MEMBER_TABLE_COLUMNS as $item) {
      $list = $list . "$item = \"{$this->$item}\", ";
    }
    $list = rtrim($list, ", ");
//    if ($debug) { echo "Member:sql_column_name_value_pairs()=$list.<br>"; }
    return $list;
  }  


//******************************************************************************
// constructor
//******************************************************************************
  function __construct()
  {
    global $debug;
    if ($debug) { echo "Member:__construct.<br>"; }
    $this->member_id = null;
    $this->nickname = null;
    $this->name_last = null;
    $this->name_first = null;
    $this->stamp = null;
  }

//******************************************************************************
// set the data members of this member to the values found in $_POST.       
//******************************************************************************
  public function set_to_POST()
  {
  $this->set_member_id($_POST['member_id']);
  $this->set_nickname($_POST['nickname']);
  $this->set_name_last($_POST['name_last']);
  $this->set_name_first($_POST['name_first']);
//  $this->set_stamp($_POST['stamp']);
  }

//******************************************************************************
// get a member row by member_id.                   
//******************************************************************************
  public function get()
  {
    global $debug;
#    if ($debug) { echo "Member:get={$this->member_id}.<br>"; }
    try {
require(BASE_URI . "includes/pok_open.inc.php");
      # get members row
      $query = "SELECT * FROM members " . 
                 "WHERE member_id = \"$this->member_id\" ";
#      if ($debug) { echo "Member:members:get:query=$query.<br>"; }
      $stmt = $pokdb->prepare($query);
      $stmt->execute();
      $row_count = $stmt->rowCount();
#      if ($debug) { echo "Member:members:get:$this->member_id:rows=$row_count.<br>"; }
      if ($row_count == 1) {
        $row = $stmt->fetch();
        $this->setThisTomemberRow($row);
      } elseif ($row_count < 1) {
#        if ($debug) { echo "Member:get=member not found.<br>"; }
        #error_log($e->getTraceAsString());
        throw new memberException('No records for this member were found', 2210);
      } else {
#        if ($debug) { echo "Member:get=multiple member records found.<br>"; }
        #error_log($e->getTraceAsString());
        throw new memberException('Multiple records for this member were found', 2211);
      }
    } catch (PDOException $e) {
      echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
    }
  }
//******************************************************************************
// populate a member object with data from a member table row        
//******************************************************************************
  protected function setThisTomemberRow($row) 
  {
    $this->member_id = $row['member_id'];
    $this->nickname = $row['nickname'];
    $this->name_last = $row['name_last'];
    $this->name_first = $row['name_first'];
    $this->stamp = $row['stamp'];
  }

//******************************************************************************
// Get the next available member number        
//******************************************************************************
  public function get_next_id()
  {
    global $debug;
#    if ($debug) { echo "get_next_id:"; }
    try {
      require(BASE_URI . "includes/pok_open.inc.php");
      # get member table
      $stmt = $pokdb->prepare("SELECT MAX(member_id) FROM members");
      $stmt->execute();
      $this->member_id = $stmt->fetchColumn() + 1;
#      if ($debug) { echo "$this->member_id.<br>"; }
    } catch (PDOException $e) {
      echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
    } catch (Exception $e) {
      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
    }
  }


//******************************************************************************
// List a member
//******************************************************************************
  public function listing()
  {
    echo "Member." . __FUNCTION__ . ".<br>";
    $this->listIt(".<br>");
  }

  public function listRow()
  {
    echo "Member." . __FUNCTION__ . ":";
    $this->listIt("; ");
    echo ".<br>";
  }

  private function listIt($d)
  {
    echo "Member_id=$this->member_id$d";
    echo "nickname=$this->nickname$d";
    echo "name_last=$this->name_last$d";
    echo "name_first=$this->name_first$d";
    echo "stamp=$this->stamp$d";
  }

  public function dump()
  {
    echo "Member.dump.<br>";
    var_dump($this);
    echo ".<br>\n";
  }

//******************************************************************************
// find a members row by member_id.
//******************************************************************************
  public function find()
  {
    global $debug;
    $row_count = -1;
    if ($debug) { echo "Member:find={$this->member_id}:{$this->eff_date}.<br>"; }
    try {
require(BASE_URI . "includes/pok_open.inc.php");
      # find member rows
      $query = "SELECT * FROM members " . 
                 "WHERE member_id = \"$this->member_id\"  ";
      if ($debug) { echo "Member:find:query=$query.<br>"; }
      $stmt = $pokdb->prepare($query);
      $stmt->execute();
      $row_count = $stmt->rowCount();
      if ($debug) { echo "Member:find:$this->member_id:rows=$row_count.<br>"; }
    } catch (PDOException $e) {
      echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
      throw new memberException('PDO Exception', -2010, $e);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
    }
    return($row_count);
  }

//******************************************************************************
// insert a members row from a member object
//******************************************************************************
  public function insert()
  {
    global $debug;
    $val_errors = array ();
    if ($debug) { echo "Member.insert:$this->member_id:$this->eff_date:
{$this->sql_column_name_value_pairs()}.<br>"; }
    $val_errors = ($this->validate());
    if (sizeof($val_errors) == 0 ) {
      try {
require(BASE_URI . "includes/pok_open.inc.php");
        # insert member
        $query = "INSERT INTO members ({$this->sql_column_name_list()}) " .
         "VALUES ({$this->sql_column_value_list()})" ;
        if ($debug) { echo "Member:find:query=$query.<br>"; }
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
//    if ($debug) { echo "Member added.<br>"; }
//    $inserted_member_id = $pokdb->lastInsertId(); 
//    if ($debug) { echo "Member number:$inserted_member_id.<br>"; }
  }

//******************************************************************************
// update a members row from a member object                         
//******************************************************************************
  public function update()
  {
    global $debug;
    if ($debug) { echo "Member.update:$this->member_id:$this->eff_date.<br/
>"; }
    $val_errors = $this->validate();
//    if ($debug) { echo "Member.update error list size:"; echo sizeof($val_errors); echo ".<br>"; }
    if (sizeof($val_errors) == 0 ) {
      try {
        require(BASE_URI . "includes/pok_open.inc.php");
        # update member
        $update = "UPDATE members SET {$this->sql_column_name_value_pairs()} " . 
           " WHERE member_id = \"{$this->member_id}\" AND eff_date = \"{$this->eff_date}\" ";
        if ($debug) { echo "Member:update:stmt_str=$update.<br>"; }
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
    if ($debug) { echo "Member.update:end:$this->member_id:$this->eff_date.<br>"; }
  }

//******************************************************************************
// create a fictitious member for test purposes                         
//******************************************************************************
  function testData()
  {
    global $debug;
    if ($debug) { echo "Member.testData start.<br>"; }
    # id
    $this->get_next_id();
    # name_last
    require("../inc/testdb_open.php"); #
    $nameCount = $this->getRowCount($testdb, "family_names");
    $nameId = rand(1, $nameCount);
    $query="SELECT * FROM family_names WHERE name_id = $nameId";
//    echo "testData family_names query=$query.<br>";
    $stmt = $testdb->prepare($query);
    $stmt->execute();
    $row_count = $stmt->rowCount();
    if ($debug) { echo "Member.testData rows:$row_count.<br>"; }
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
#    if ($debug) { echo "Member.testData stmt:"; $stmt->debugDumpParams(); echo "<br>"; }
    $stmt->execute();
    $row_count = $stmt->rowCount();
//    if ($debug) { echo "Member.testData rows:$row_count.<br>"; }
    if ($row_count == 1) {
      $row = $stmt->fetch();
      $this->name_first = $row['name_first'];
//      echo "Member.testData name_first=$this->name_first.<br>";
    } else {
      echo "testData __construct name_first error: Too many rows:$row_count.<br>";
      throw new Exception('testData name_first SELECT error: Too many rows', -1);
    }

#  echo "Next this:{$this->get_member_id()}.<br>";
    $this->set_nickname("M");


  }


//******************************************************************************
// get the number of rows in a table from the test database                         
//******************************************************************************
  private function getRowCount($testdb, $table_name)
  {
    # Get number of rows to choose from in table
    $nameCount = 0;
    $sql = "SELECT COUNT(*) FROM $table_name";
    if ($res = $testdb->query($sql)) {
      /* Check the number of rows that match the SELECT statement */
      $nameCount = $res->fetchColumn();
    }
    #echo "testdata $table_name count=$nameCount.<br>";
    return ($nameCount);
  }

//******************************************************************************
} # end class Member
//******************************************************************************
?>
