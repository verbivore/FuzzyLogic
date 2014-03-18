<?php
/******************************************************************************
 *  File name: player.class.php
 *  Created by: David Demaree
 *  Contact: dave.demaree@yahoo.com
 *  Purpose: Class definition for a player
 *** History ***  
 * 14-03-18 Fixed final echo in insert().  Updated 2210 message.  DHD
 * 14-03-08 Original.  DHD
 *****************************************************************************/

class player extends member
{

//*****************************************************************************
// Getters
//*****************************************************************************
  public function get_invite_cnt() { return $this->invite_cnt; }
  public function get_yes_cnt() { return $this->yes_cnt; }
  public function get_maybe_cnt() { return $this->maybe_cnt; }
  public function get_no_cnt() { return $this->no_cnt; }
  public function get_flake_cnt() { return $this->flake_cnt; }
  public function get_score() { return $this->score; }

//*****************************************************************************
// Setters
//*****************************************************************************
  public function set_invite_cnt($P) { $this->invite_cnt = $P; }
  public function set_yes_cnt($P) { $this->yes_cnt = $P; }
  public function set_maybe_cnt($P) { $this->maybe_cnt = $P; }
  public function set_no_cnt($P) { $this->no_cnt = $P; }
  public function set_flake_cnt($P) { $this->flake_cnt = $P; }
  public function set_score($P) { $this->score = $P; }
/*
//*****************************************************************************
// Validation for individual column values.
//*****************************************************************************
  public function validate_member_id() {
    # numeric
    # !> highest existing member_id + 1
//    if ($debug) { echo "player.validate_member_id=$this->member_id.<br>"; }
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

//*****************************************************************************
// Validate members fields
//*****************************************************************************
  public function validate()
  {
    global $debug;
    if ($debug) { echo "player.validate={$this->member_id}.<br>"; }
    $errors = array();
    $foo = array();
#    global $PLAYER_TABLE_COLUMNS;
    # validate fields
    foreach ($this->PLAYER_TABLE_COLUMNS as $column) {
      $func = "validate_$column";
//      if ($debug) { echo "player.validate column={$func}.<br>"; }
      $foo = $this->$func();
//      if ($debug) { echo "player.validate col:$column="; var_dump($foo); echo ".<br>"; }
      if ($foo[0]) {
        $errors["$column"][0] = $foo[0];
        $errors["$column"][1] = $foo[1];
//        if ($debug) { echo "player.validate col:$column:$foo[0]:$foo[1].<br>"; }
      }
    }
    if ($debug) {
    foreach ($errors as $col => $val) {
//      echo "player.validate errors=$col:"; list($n,$s) = $val; echo "$n:$s.<br>"; }
      echo "player.validate errors=$col:$val[0]:$val[1].<br>"; }
    }
#    if ($debug) { echo "player.validate arraysize="; echo sizeof($errors); echo ".<br>"; }
    return($errors);
  }

//*****************************************************************************
// Data members
//*****************************************************************************
  protected $invite_cnt;
  protected $yes_cnt;
  protected $maybe_cnt;
  protected $no_cnt;
  protected $flake_cnt;
  protected $score;

//*****************************************************************************
// List of names of SQL columns for the members table
//*****************************************************************************
  private $PLAYER_TABLE_COLUMNS = array("member_id", "nickname", "name_last", 
                               "name_first", "stamp");

//******************************************************************************
// create a list of comma-separated column names for SQL statements.          
//******************************************************************************
  private function sql_column_name_list() {
    global $debug;
    $list = "";
    foreach ($this->PLAYER_TABLE_COLUMNS as $item) {
      $list .= "$item, ";
    }
    $list = rtrim($list, ", ");
//    if ($debug) { echo "player:sql_column_name_list()=$list.<br>"; }
    return $list;
  }  

//******************************************************************************
// create a list of quoted, comma-separated column values for SQL statements. 
//******************************************************************************
  private function sql_column_value_list() {
    global $debug;
    $list = "";
    foreach ($this->PLAYER_TABLE_COLUMNS as $item) {
      $list = $list . "\"{$this->$item}\", ";
    }
    $list = rtrim($list, ", ");
//    if ($debug) { echo "player:sql_column_value_list()=$list.<br>"; }
    return $list;
  }  

//******************************************************************************
// create a list of pairs of column names with values, used for SQL INSERT    
//******************************************************************************
  private function sql_column_name_value_pairs() {
    global $debug;
    $list = "";
    foreach ($this->PLAYER_TABLE_COLUMNS as $item) {
      $list = $list . "$item = \"{$this->$item}\", ";
    }
    $list = rtrim($list, ", ");
//    if ($debug) { echo "player:sql_column_name_value_pairs()=$list.<br>"; }
    return $list;
  }  


//******************************************************************************
// constructor
//******************************************************************************
  function __construct()
  {
    global $debug;
    if ($debug) { echo "player:__construct.<br>"; }
    parent::__construct();
    $this->invite_cnt = null;
    $this->yes_cnt = null;
    $this->maybe_cnt = null;
    $this->no_cnt = null;
    $this->flake_cnt = null;
    $this->score = null;
  }

//******************************************************************************
// set the data members of this player to the values found in $_POST.       
//******************************************************************************
  public function set_to_POST()
  {
  parent::set_to_POST();
/*
  $this->set_invite_cnt($_POST['invite_cnt']);
  $this->set_yes_cnt($_POST['yes_cnt']);
  $this->set_maybe_cnt($_POST['maybe_cnt']);
  $this->set_no_cnt($_POST['no_cnt']);
  $this->set_flake_cnt($_POST['flake_cnt']);
  $this->set_score($_POST['score']);
*/
  }

//******************************************************************************
// get a player row by member_id.                   
//******************************************************************************
  public function get()
  {
    global $debug;
#    if ($debug) { echo "player:get={$this->member_id}.<br>"; }
    try {
require(BASE_URI . "includes/pok_open.inc.php");
      # get players row
      $query = "SELECT * FROM members " . 
                 "WHERE member_id = \"$this->member_id\" ";
#      if ($debug) { echo "player:members:get:query=$query.<br>"; }
      $stmt = $pokdb->prepare($query);
      $stmt->execute();
      $row_count = $stmt->rowCount();
#      if ($debug) { echo "player:members:get:$this->member_id:rows=$row_count.<br>"; }
      if ($row_count == 1) {
        $row = $stmt->fetch();
        $this->setThisToMemberRow($row);
        # get seats rows
        $query = "SELECT * FROM seats " . 
                   "WHERE member_id = $this->member_id ";
#        if ($debug) { echo "player:seats:get:query=$query.<br>"; }
        $stmt = $pokdb->prepare($query);
        $stmt->execute();
        $row_count = $stmt->rowCount();
#        if ($debug) { echo "player:seats:get:$this->member_id:rows=$row_count.<br>"; }
        if ($row_count > 0) {
          $rows = $stmt->fetchAll();
          $this->setThisToSeatsRows($rows);
        } else {
#          if ($debug) { echo "player:seats:get=player not found.<br>"; }
          #error_log($e->getTraceAsString());
          throw new playerException('No seats records for player ' . $this->member_id . ' were found', 2210);
        }
      } elseif ($row_count < 1) {
#        if ($debug) { echo "player:get=player not found.<br>"; }
        #error_log($e->getTraceAsString());
        throw new playerException('No records for this player were found', 2210);
      } else {
#        if ($debug) { echo "player:get=multiple player records found.<br>"; }
        #error_log($e->getTraceAsString());
        throw new playerException('Multiple records for this player were found', 2211);
      }
    } catch (PDOException $e) {
      echo "PDO Exception: " . __FILE__ . " line: " . __LINE__ . "<br/>";
      echo $e->getCode() . ": " . $e->getMessage() . "<br/>";  
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
    }
  }
//"SELECT p.*, s.response, @INV := (SELECT COUNT(*) FROM seats AS i WHERE i.member_id = p.member_id ) AS Inv, @YES := (SELECT COUNT(*) FROM seats AS y WHERE y.response = 'Y' AND y.member_id = s.member_id ) AS Yes, @MBE := (SELECT COUNT(*) FROM seats AS m WHERE m.response = 'M' AND m.member_id = s.member_id ) AS Mbe, @NOP := (SELECT COUNT(*) FROM seats AS n WHERE n.response = 'N' AND n.member_id = s.member_id ) AS No, @FLK := (SELECT COUNT(*) FROM seats AS f WHERE f.response = 'F' AND f.member_id = s.member_id ) AS Flk, @RATE := ((@YES + @MBE + @NOP - @FLK) / @INV) AS Rate FROM members AS p LEFT JOIN seats AS s ON p.member_id=s.member_id GROUP BY p.member_id ORDER BY Rate DESC;"
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
//******************************************************************************
// populate a player object with data from the seats table        
//******************************************************************************
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
  }


//******************************************************************************
// Get the next available player number        
//******************************************************************************
/*  public function get_next_id()
  {
    global $debug;
#    if ($debug) { echo "get_next_id:"; }
    try {
      require(BASE_URI . "includes/pok_open.inc.php");
      # get player table
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
*/

//******************************************************************************
// List a player
//******************************************************************************
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
  }

  public function dump()
  {
    echo "<br>player.dump.<br>";
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
    if ($debug) { echo "player:find={$this->member_id}.<br>"; }
    try {
      require(BASE_URI . "includes/pok_open.inc.php");
      # find player rows
      $query = "SELECT * FROM members " . 
                 "WHERE member_id = \"$this->member_id\"  ";
      if ($debug) { echo "player:find:query=$query.<br>"; }
      $stmt = $pokdb->prepare($query);
      $stmt->execute();
      $row_count = $stmt->rowCount();
      if ($debug) { echo "player:find:$this->member_id:rows=$row_count.<br>"; }
    } catch (PDOException $e) {
      echo "PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
      throw new playerException('PDO Exception', -2010, $e);
//    } catch (Exception $e) {
//      echo "Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>"; 
//      rethrow??? 
    }
    return($row_count);
  }

//******************************************************************************
// insert a members row from a player object
//******************************************************************************
  public function insert()
  {
    global $debug;
    $val_errors = array ();
    if ($debug) { echo "<br>player.insert:vvv:$this->member_id:{$this->sql_column_name_value_pairs()}.<br>"; }
    $val_errors = ($this->validate());
    if (sizeof($val_errors) == 0 ) {
      try {
        require(BASE_URI . "includes/pok_open.inc.php");
        # insert player
        $query = "INSERT INTO members ({$this->sql_column_name_list()}) " .
         "VALUES ({$this->sql_column_value_list()})" ;
        if ($debug) { echo "player:find:query=$query.<br>"; }
        $stmt = $pokdb->prepare($query);
        $stmt->execute();
      } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
          #error_log($e->getTraceAsString());
          throw new playerException('Duplicate entry', 2110, $e);
        } else {
          echo "player.insert: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
          throw new playerException('Unknown error', -2110, $e);
        }
      } catch (Exception $e) {
        echo "player.insert: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
        throw new playerException($e);
      }
    } else {
      throw new playerException("Data validation errors", 2104, null, $val_errors);
    }
//    if ($debug) { echo "player added.<br>"; }
//    $inserted_member_id = $pokdb->lastInsertId(); 
//    if ($debug) { echo "player number:$inserted_member_id.<br>"; }
    if ($debug) { echo "<br>player.insert:^^^";}
  }

//******************************************************************************
// update a members row from a player object                         
//******************************************************************************
  public function update()
  {
    global $debug;
    if ($debug) { echo "player.update:vvv:$this->member_id.<br/
>"; }
    $val_errors = $this->validate();
//    if ($debug) { echo "player.update error list size:"; echo sizeof($val_errors); echo ".<br>"; }
    if (sizeof($val_errors) == 0 ) {
      try {
        require(BASE_URI . "includes/pok_open.inc.php");
        # update player
        $update = "UPDATE members SET {$this->sql_column_name_value_pairs()} " . 
           " WHERE member_id = \"{$this->member_id}\" ";
        if ($debug) { echo "player:update:stmt_str=$update.<br>"; }
        $stmt = $pokdb->prepare($update);
        $stmt->execute();
      } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
          #error_log($e->getTraceAsString());
          throw new playerException('Duplicate entry', 2110, $e);
        } else {
          echo "player.update: PDO Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";
          throw new playerException('Unknown error', -2110, $e);
        }
      } catch (Exception $e) {
        echo "player.update: Exception: " . $e->getCode() . ": " . $e->getMessage() . "<br>";  
        throw new playerException($e);
      }
    } else {
      throw new playerException("Data validation errors", 2104, null, $val_errors);
    }
    if ($debug) { echo "player.update:^^^:$this->member_id.<br>"; }
  }

//******************************************************************************
// create a fictitious player for test purposes                         
//******************************************************************************
  function testData()
  {
    global $debug;
    if ($debug) { echo "player.testData:vvv.<br>"; }
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
    if ($debug) { echo "player.testData rows:$row_count.<br>"; }
    if ($row_count == 1) {
      $row = $stmt->fetch();
      $this->name_last = $row['name_last'];
#      echo "player.testData name_last=$this->name_last.<br>";
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
#    if ($debug) { echo "player.testData stmt:"; $stmt->debugDumpParams(); echo "<br>"; }
    $stmt->execute();
    $row_count = $stmt->rowCount();
//    if ($debug) { echo "player.testData rows:$row_count.<br>"; }
    if ($row_count == 1) {
      $row = $stmt->fetch();
      $this->name_first = $row['name_first'];
//      echo "player.testData name_first=$this->name_first.<br>";
    } else {
      echo "testData __construct name_first error: Too many rows:$row_count.<br>";
      throw new Exception('testData name_first SELECT error: Too many rows', -1);
    }

#  echo "Next this:{$this->get_member_id()}.<br>";
    $this->set_nickname("M");

    if ($debug) { echo "player.testData:^^^.<br>"; }

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
} # end class player
//******************************************************************************


class playerException extends Exception
{
#    global $debug;
    private $_options = array();
    // Redefine the exception so message isn't optional
    public function __construct($message, 
                                $code = 0, 
                                Exception $previous = null,
                                $options = array('params')) {

#        if ($debug) { echo "playerException={$message}:$code.<br>"; }
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);

        $this->_options = $options;

    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function GetOptions() { 
#    if ($debug) { echo "playerException:GetOptions="; echo sizeof($this->_options); echo ".<br>"; }
    return $this->_options; 
    }
}
?>

