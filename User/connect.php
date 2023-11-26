<?php
// Oracle database connection settings
$host = '192.168.15.245';
$port = '1521';
$sid = 'ARCHDEV';
//old
// $username = 'ticketing';
// $password = 'ticketing';
//new
$username = 'selfticket';
$password = 'selfticket';

// Establish a connection to the Oracle database

$conn = oci_connect($username, $password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SID=$sid)))");

if (!$conn) {

    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    echo "Connectoin to Oracle Database Failed!<br>";
}
// $id = 3;
// Query to retrieve a list of tables
// $query = "SELECT ID, NAME, PASSWORD FROM users WHERE ID = :id ";

// $stid = $conn->prepare($query);
// $stid = oci_parse($conn, $query);
// oci_bind_by_name($stid, ":id", $id);
// oci_execute($stid);

// $numrows = oci_fetch_all($stid, $res);
// echo $numrows." Rows";


// // Fetch and display results

// // Execute the query
// echo "Tables in the database:<br>";
// while ($row = oci_fetch_assoc($stid)) {
//     // Assuming $row is an associative array, you might want to loop through its values
//     foreach ($row as $column => $value) {
//         echo $column . ": " . $value . "<br>";
//     }
//     echo "<br>";
// }

// foreach ($res as $row) {
//     // Loop through the associative array for each row
//     foreach ($row as $column ) {
//         echo $row . "<br>";
//     }
//     echo "<br>";
// }


// Close the Oracle connection
// oci_free_statement($stid);
oci_close($conn);
