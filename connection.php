<?php

/**
 * LOGINS
 * Data Reader
 * User: db_datareader
 * Pass: sb[kD!nt2OgH]_lY
 * 
 * Data Writer
 * User: db_datawriter
 * Pass: 3F*SkPmQvvKW7rbR
 *  
 * Data Admin
 * User: db_dataadmin
 * Pass: os)mSUGEZDP6E2am
 * 
 * FROM BLOG ASSIGNMENT
 * 
 *   define('ADMIN_LOGIN', 'wally');
 *   define('ADMIN_PASSWORD', 'mypass');
 *   if (
 *       !isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])
 *       || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)
 *       || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)
 *   ) {
 *       header('HTTP/1.1 401 Unauthorized');
 *       header('WWW-Authenticate: Basic realm="Our Blog"');
 *       exit("Access Denied: Username and password required.");
 *   }
 */

define('DB_DSN', 'mysql:host=localhost;dbname=mtgpricer;charset=utf8');
define('DB_USER', 'db_dataadmin');
define('DB_PASS', 'os)mSUGEZDP6E2am');

try {
    $db = new PDO(DB_DSN, DB_USER, DB_PASS);
} catch (PDOException $e) {
    print "Error: " . $e->getMessage();
    die(); // Force execution to stop on errors.
}