<?php # config.inc.php
/**
 * Set constants and error handler
 * File name: config.inc.php
 * @author David Demaree (from Larry Ullman) <dave.demaree@yahoo.com>
 *** History ***  
 * 14-03-20 Updated for phpDoc.  DHD
 * 14-03-18 Moved directory from poker to FuzzyLogic.  Added post_dump and session_dump.  DHD
 * 14-03-08 Original.  DHD
 */

# ******************** #
# ***** SETTINGS ***** #

// Errors are emailed here:
$contact_email = 'dave.demaree@yahoo.com'; 

// Determine whether we're working on a local server
// or on the real server:
$host = substr($_SERVER['HTTP_HOST'], 0, 5);
if (in_array($host, array('local', '127.0', '192.1'))) {
    $local = TRUE;
} else {
    $local = FALSE;
}

// Determine location of files and the URL of the site:
// Allow for development on different servers.
if ($local) {

    // Always debug when running locally:
    $debug = TRUE;
    $_SESSION['dbug'] = TRUE;
//    $_SESSION['dbug'] = 0;
    
    // Define the constants:
    define('BASE_URI', '/home/dave/dev/FuzzyLogic/html/');
    define('BASE_URL', 'http://localhost/dev/FuzzyLogic/');
    define('DB', '/path/to/mysql.inc.php');
    define('TEST_URI', '/home/dave/dev/utl/html/');
//    define('TEST_DB', '/home/dave/dev/utl/html/');
    
} else {

    define('BASE_URI', '/path/to/live/html/folder/');
    define('BASE_URL', 'http://www.example.com/');
    define('DB', '/path/to/live/mysql.inc.php');
    
}
    
/* 
 *  Most important setting!
 *  The $debug variable is used to set error management.
 *  To debug a specific page, add this to the index.php page:

if ($p == 'thismodule') $debug = TRUE;
require('./includes/config.inc.php');

 *  To debug the entire site, do

$debug = TRUE;

 *  before this next conditional.
 */

// Assume debugging is off. 
if (!isset($debug)) {
    $debug = FALSE;
}


# initializing php code for the first page
session_start();

if (!isset($from_page_id)) {
    $from_page_id = "new";
}
if (!isset($_SESSION['startTime'])) {
    $_SESSION['startTime'] = date("M/d/y g:i:sa");
}
if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 0;
}
$_SESSION['counter'] += 1;

# ***** SETTINGS ***** #
# ******************** #


function session_dump() 
{
    # Dump _SESSION array
    global $debug;
    if ($debug) {
#        echo '<pre>'; # formats list one per line
        print "_SESSION var dump: ";
        var_dump($_SESSION);
        print ".<br/>";
#    echo '</pre>';
    }
}

function session_list() 
{
  # List contents of the _SESSION array
    global $debug;
    if ($debug) {
        echo '<pre>'; # formats list one per line
        print "_SESSION var list: ";
        var_dump($_SESSION);
        print ".<br/>";
        echo '</pre>';
    }
}

function post_dump() 
{
    # Dump _POST array
    global $debug;
    if ($debug) {
        print "_POST var dump: ";
        var_dump($_POST);
        print ".<br/>";
    }
}

function post_list() 
{
    # List contents of the _POST array
    global $debug;
    if ($debug) {
        echo '<pre>'; # formats list one per line
        print "_POST var list: ";
        var_dump($_POST);
        print ".<br/>";
        echo '</pre>';
    }
}

function dbg($parm) 
{
    $preChar = ">";
    static $depth = 0;
//session_list();
    if ($_SESSION['dbug']) {
        $prefix = "";
        $direction = substr($parm, 0, 1);
        if ($direction == "+") {
            $depth++;
            $prefix = str_repeat("+", $depth);
        } elseif ($direction == "-") {
            $prefix = str_repeat("-", $depth);
            $depth--;
        } else {
            $prefix = str_repeat("=", $depth);
        }
        echo '<div class="dbgClass">';
        echo $prefix . ":" . $parm . ".<br\n>";
        echo '</div>';
    }
//        echo '<span class="dbgClass"'; # formats list one per line
//        echo $prefix . ":" . $parm;
//        echo '>' . ".<br\n>";

}





# **************************** #
# ***** ERROR MANAGEMENT ***** #

// Create the error handler:
function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {

    global $debug, $contact_email;
    
    // Build the error message:
    $message = "An error occurred in script '$e_file' on line $e_line: $e_message";
    
    // Append $e_vars to the $message:
    $message .= print_r($e_vars, 1);
    
    if ($debug) { // Show the error.
    
        echo '<div class="error">' . $message . '</div>';
        debug_print_backtrace();
        
    } else { 

        // Log the error:
        error_log ($message, 1, $contact_email); // Send email.

        // Only print an error message if the error isn't a notice or strict.
        if ( ($e_number != E_NOTICE) && ($e_number < 2048)) {
            echo '<div class="error">A system error occurred. We apologize for the inconvenience.</div>';
        }

    } // End of $debug IF.

} // End of my_error_handler() definition.

// Use my error handler:
#set_error_handler('my_error_handler');

# ***** ERROR MANAGEMENT ***** #
# **************************** #
