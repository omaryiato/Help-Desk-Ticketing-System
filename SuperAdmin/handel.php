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

// if (isset($_POST['assignTicket'])) {  // Assign Ticket To The Team Member  

//     $userID         = $_POST['user'];
//     $userDep        = $_POST['department'];
//     $ticketid       = $_POST['id'];
//     $assignedMember = 'assign';

//     // // Query to Insert the Data To Tickets Table  

//     $assignTicket = "UPDATE tickets SET 
//                             TEAM_MEMBER_ASSIGNED_ID = :t_member, STATUS = :new_status
//                             WHERE ID = :t_id";

//     $assign = oci_parse($conn, $assignTicket);

//     oci_bind_by_name($assign, ':t_member', $userID);
//     oci_bind_by_name($assign, ':new_status', $assignedMember);
//     oci_bind_by_name($assign, ':t_id', $ticketid);

//     oci_execute($assign, OCI_NO_AUTO_COMMIT);

//     oci_commit($conn);  // commits all new Data

//     redirect("dashboard.php");
// } else {
//     echo 'Theres Somting Wrong';
// }


if (isset($_POST['action'])) {  // Assign Ticket To The Team Member

    $action = $_POST['action'];

    if ($action == 'assign') {

        $userID       = $_POST['user'];
        $ticketid     = $_POST['tickid'];
        $userDep        = $_POST['department'];
        $assignedMember = 'assign';

        // var_dump($userName, $ticketName, $ticketDes, $userDep, $tags);

        $assignTicket = "UPDATE tickets SET 
                            TEAM_MEMBER_ASSIGNED_ID = :t_member, STATUS = :new_status, UPDATED_DATE = CURRENT_TIMESTAMP
                            WHERE ID = :t_id";

        $assign = oci_parse($conn, $assignTicket);

        oci_bind_by_name($assign, ':t_member', $userID);
        oci_bind_by_name($assign, ':new_status', $assignedMember);
        oci_bind_by_name($assign, ':t_id', $ticketid);

        $run = oci_execute($assign, OCI_NO_AUTO_COMMIT);

        if ($run) {
            oci_commit($conn);
            echo 'done';
        } else {
            $e = oci_error($add);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }
    } elseif ($action == 'start') {

        $ticketid       = $_POST['tickid'];

        $statusUpdate = 'started';

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
    } elseif ($action == 'solve') {

        $ticketid       = $_POST['tickid'];

        $statusUpdate = 'solved';

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
    } elseif ($action == 'reject') {

        $ticketid       = $_POST['tickid'];

        $statusUpdate = 'rejected';

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
    } elseif ($action == 'remove') {

        $userid       = $_POST['userid'];

        $member = "DELETE FROM users WHERE ID = :t_id";

        $user = oci_parse($conn, $member);

        oci_bind_by_name($user, ':t_id', $userid);

        $run = oci_execute($user, OCI_NO_AUTO_COMMIT);

        if ($run) {
            oci_commit($conn);
            echo 'done';
        } else {
            $e = oci_error($user);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }
    }
}


if (isset($_POST['department'])) {   // Choose User Debends On His Department
    $selectedDepartment = $_POST['department'];

    // Query to fetch users based on the selected department
    $depUser = "SELECT ID, NAME FROM users WHERE DEPARTMENT = :department";
    $dept = oci_parse($conn, $depUser);

    // Bind the variables
    oci_bind_by_name($dept, ":department", $selectedDepartment);

    // Execute the query
    oci_execute($dept);

    // Build HTML options for users
    $options = '';
    while ($row = oci_fetch_assoc($dept)) {
        $options .= "<option value='{$row['ID']}'>{$row['NAME']}</option>";
    }

    echo $options;
}
