<?php
# open poker db
$host="localhost";
$dbname="poker";

$username = getenv('APACHE_DEV_USER');
$directory = getenv('APACHE_DEV_DIR');

#$username="root";
#$directory="foobar";
$dsn="mysql:host=$host;dbname=$dbname;charset=utf8";

//if ($debug) { echo "pok.open:$username:$directory.<br>"; }

try {
    unset ($pokdb);
    $pokdb = new PDO($dsn, $username, $directory);
    $pokdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
#  if ($debug) { echo "poker database open successful.<br>"; }
//    var_dump($pokdb);
//    echo ".<br>";
} 
catch (PDOException $e) {
    echo "PDO Exception: " . __FILE__ . " line: " . __LINE__ . "<br>";
    echo $e->getCode() . ": " . $e->getMessage() . "<br>";
    exit();
} 
catch (Exception $e) {
    echo "Exception: " . __FILE__ . " line: " . __LINE__ . "<br>";
    echo $e->getCode() . ": " . $e->getMessage() . "<br>";
    exit();
}
?>


