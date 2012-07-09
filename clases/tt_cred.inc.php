<?php
 
/**
 * ac_cred.inc.php: Secret Connection Credentials for a database class
 * @package Oracle
 */
 
/**
 * DB user name
 */
define('SCHEMA', 'dranzer');
 
/**
 * DB Password.
 *
 * Note: In practice keep database credentials out of directories
 * accessible to the web server.
 */
define('PASSWORD', 'fabulousmax');
 
/**
 * DB connection identifier
 */
define('DATABASE', 'localhost/XE');
 
/**
 * DB character set for returned data
 */
define('CHARSET', 'UTF8');
 
/**
 * Client Information text for DB tracing
 */
define('CLIENT_INFO', 'TodoTicket.com Inc.');
 
?>
