<?php # config.inc.php
/******************************************************************************
 *  File name: config.inc.php
 *  Created by: David Demaree (from Larry Ullman)
 *  Contact: dave.demaree@yahoo.com
 *  Purpose: Set constants and error handler
 *** History ***  
 * 14-03-18 Moved directory from poker to FuzzyLogic.  DHD
 * 14-03-08 Original.  DHD
 *****************************************************************************/

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
    
    // Define the constants:
    define('BASE_URI', '/home/dave/dev/FuzzyLogic/html/');
    define('BASE_URL', 'http://localhost/dev/FuzzyLogic/');
    define('DB', '/path/to/mysql.inc.php');
    
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

if (!isset($_SESSION['startTime'])) {
  $_SESSION['startTime'] = date("M/d/y g:i:sa");
}
$_SESSION['counter'] += 1;

# ***** SETTINGS ***** #
# ******************** #


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
