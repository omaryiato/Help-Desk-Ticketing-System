<?php

///////  Test Database  Connection ///////////

// Oracle database connection settings
$host = '192.168.15.245';
$port = '1521';
$sid = 'ARCHDEV';
$username = 'selfticket';
$password = 'selfticket';

// Establish a connection to the Oracle database

putenv('NLS_LANG=AMERICAN_AMERICA.AL32UTF8');

$conn  = oci_connect($username, $password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SID=$sid)))");

if (!$conn) {

    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    echo "Connectoin to Oracle Database Failed!<br>";
}
