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

        $details        = $_POST['details'];
        $userName       = $_POST['name'];
        $ticketDes      = $_POST['description'];
        $service        = $_POST['service'];
        $tags           = $_POST['tags'];


        // Convert the PHP array to a JSON string
        $tagsJson = json_encode($tags);

        $id = rand(1, 1000000);

        $leader = $_SESSION['employee'];

        // Query to fetch users based on the selected department
        $supUser = "SELECT ID FROM users WHERE NAME = :t_name";
        $sup = oci_parse($conn, $supUser);

        // Bind the variables
        oci_bind_by_name($sup, ":t_name", $leader);

        // Execute the query
        oci_execute($sup);

        $row = oci_fetch_assoc($sup);

        $supLeader = 15;

        // Query to fetch users based on the selected department
        $memUser = "SELECT ID FROM users WHERE NAME = :t_name";
        $mem = oci_parse($conn, $memUser);

        // Bind the variables
        oci_bind_by_name($mem, ":t_name", $userName);

        // Execute the query
        oci_execute($mem);

        $row = oci_fetch_assoc($mem);

        $memberuser = $row['ID'];

        // // Query to Insert the Data To Tickets Table  

        $addTicket = "INSERT INTO TICKETS (ID, USER_ID, TEAM_LEADER_MEMBER_ID, DESCRIPTION, TAGS, created_date, created_by, SERVICE_TYPE, SERVICE_DETAILS) 
                            VALUES (:t_id, :t_user, :t_leader, :t_des,  :t_tags, CURRENT_TIMESTAMP, :t_create, :t_type, :t_details)";
        $add = oci_parse($conn, $addTicket);

        oci_bind_by_name($add, ':t_id', $id);
        oci_bind_by_name($add, ':t_user', $memberuser);
        oci_bind_by_name($add, ':t_leader', $supLeader);
        oci_bind_by_name($add, ':t_des', $ticketDes);
        oci_bind_by_name($add, ':t_tags', $tagsJson);
        oci_bind_by_name($add, ':t_create', $memberuser);
        oci_bind_by_name($add, ':t_type', $service);
        oci_bind_by_name($add, ':t_details', $details);

        $run = oci_execute($add, OCI_NO_AUTO_COMMIT);

        if ($run) {
            oci_commit($conn);
            echo 'done';
        } else {
            $e = oci_error($add);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }
    } elseif ($action == 'complete') {

        $ticketid       = $_POST['tickid'];
        $comment       = $_POST['comment'];

        $statusUpdate = 'completed';

        // var_dump($userName, $ticketName, $ticketDes, $userDep, $tags);

        $statusTicket = "UPDATE tickets SET 
                            STATUS = :new_status, UPDATED_DATE = CURRENT_TIMESTAMP, comments = :t_comments
                            WHERE ID = :t_id";

        $status = oci_parse($conn, $statusTicket);

        oci_bind_by_name($status, ':new_status', $statusUpdate);
        oci_bind_by_name($status, ':t_comments', $comment);
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
    } elseif ($action == 'delete') {

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
    } elseif ($action == 'updateProfile') {

        $userid       = $_POST['userid'];
        $userName       = $_POST['userName'];
        $userNumber       = $_POST['userNumber'];

        $statusTicket = "UPDATE users SET 
                            NAME = :t_name, PHONE_NUMBER = :t_number
                            WHERE ID = :t_id";

        $status = oci_parse($conn, $statusTicket);

        oci_bind_by_name($status, ':t_name', $userName);
        oci_bind_by_name($status, ':t_number', $userNumber);
        oci_bind_by_name($status, ':t_id', $userid);

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


if (isset($_POST['type'])) {   // Choose User Debends On His Department
    $selectedService = $_POST['type'];

    // Query to fetch users based on the selected department
    $depUser = "SELECT * FROM service_details WHERE service_type_id = :t_service";
    $dept = oci_parse($conn, $depUser);

    // Bind the variables
    oci_bind_by_name($dept, ":t_service", $selectedService);

    // Execute the query
    oci_execute($dept);

    // Build HTML options for users
    $options = '';
    while ($row = oci_fetch_assoc($dept)) {
        $options .= "<option value='{$row['ID']}'>{$row['SERVICE_DETAILS']}</option>";
    }

    echo $options;
}
