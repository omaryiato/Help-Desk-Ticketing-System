<?php

session_start();

include 'include/function.php';
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

if (isset($_POST['action'])) {  // Add New Ticket

    $action = $_POST['action'];

    if ($action == 'add') {

        $userName       = $_POST['user'];
        $ticketName     = $_POST['name'];
        $ticketDes      = $_POST['description'];
        $userDep        = $_POST['department'];
        $tags           = $_POST['tags'];

        // Convert the PHP array to a JSON string
        $tagsJson = json_encode($tags);

        $id = rand(1, 1000000);

        $leader = 1;

        // var_dump($userName, $ticketName, $ticketDes, $userDep, $tags);

        // // Query to Insert the Data To Tickets Table  

        $addTicket = "INSERT INTO TICKETS (ID, USER_ID, TEAM_LEADER_MEMBER_ID, NAME, DESCRIPTION, TAGS, created_date, created_by) 
                            VALUES (:t_id, :t_user, :t_leader, :t_name, :t_des,  :t_tags, CURRENT_TIMESTAMP, :t_create)";
        $add = oci_parse($conn, $addTicket);

        oci_bind_by_name($add, ':t_id', $id);
        oci_bind_by_name($add, ':t_user', $userName);
        oci_bind_by_name($add, ':t_leader', $leader);
        oci_bind_by_name($add, ':t_name', $ticketName);
        oci_bind_by_name($add, ':t_des', $ticketDes);
        oci_bind_by_name($add, ':t_tags', $tagsJson);
        oci_bind_by_name($add, ':t_create', $userName);

        $run = oci_execute($add, OCI_NO_AUTO_COMMIT);

        if ($run) {
            oci_commit($conn);
            echo 'done';
        } else {
            $e = oci_error($add);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }
    }  elseif ($action == 'complete') {

        $ticketid       = $_POST['tickid'];
        
        $statusUpdate = 'completed';

        // var_dump($userName, $ticketName, $ticketDes, $userDep, $tags);

        $statusTicket = "UPDATE tickets SET 
                            STATUS = :new_status, UPDATED_DATE = CURRENT_TIMESTAMP
                            WHERE ID = :t_id";

        $status = oci_parse($conn, $statusTicket);

        oci_bind_by_name($status, ':new_status', $statusUpdate);
        oci_bind_by_name($status, ':t_id', $ticketid);

        $run = oci_execute($status, OCI_NO_AUTO_COMMIT);

        if ($run) {
            oci_commit($conn);
            echo 'done';
        } else {
            $e = oci_error($status);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }
    }  elseif ($action == 'delete') {

        $ticketid       = $_POST['tickid'];
        
        $statusTicket = "DELETE FROM tickets WHERE ID = :t_id";

        $status = oci_parse($conn, $statusTicket);

        oci_bind_by_name($status, ':t_id', $ticketid);

        $run = oci_execute($status, OCI_NO_AUTO_COMMIT);

        if ($run) {
            oci_commit($conn);
            echo 'done';
        } else {
            $e = oci_error($status);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }
    }
}
