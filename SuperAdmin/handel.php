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
            return 'done';
        } else {
            $e = oci_error($assign);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }

        // Simulate a success response for demonstration purposes

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
    } elseif ($action == 'new') {

        $max = "SELECT MAX(ID) FROM USERS";

        // -- Increment the ID for the new User
        $incrId = oci_parse($conn, $max);
        oci_execute($incrId);
        $id = oci_fetch_assoc($incrId);

        $username       = $_POST['username'];
        $password       = $_POST['password'];
        $email          = $_POST['email'];
        $department     = $_POST['department'];
        $usertype       = $_POST['usertype'];
        $phone          = $_POST['phone'];
        $admin          = $_POST['admin'];
        $userid         = ++$id['MAX(ID)'];
        $status         = 'Active';

        $users = "SELECT NAME FROM users WHERE NAME = :t_name";

        $user = oci_parse($conn, $users);
        oci_bind_by_name($user, ':t_name', $username);

        // Execute the query
        oci_execute($user);

        $row = oci_fetch_assoc($user);

        if ($row['NAME'] == $username) {
            echo 'exist';
        } else {

            $addUser = "INSERT INTO USERS (ID, NAME, TYPE, PASSWORD, EMAIL, DEPARTMENT, PHONE_NUMBER, STATUS, created_date, created_by) 
            VALUES (:t_id, :t_user, :t_type, :t_pass, :t_email,  :t_dep, :t_phone, :t_status, CURRENT_TIMESTAMP, :t_create)";

            $add = oci_parse($conn, $addUser);

            oci_bind_by_name($add, ':t_id', $userid);
            oci_bind_by_name($add, ':t_user', $username);
            oci_bind_by_name($add, ':t_type', $usertype);
            oci_bind_by_name($add, ':t_pass', $password);
            oci_bind_by_name($add, ':t_email', $email);
            oci_bind_by_name($add, ':t_dep', $department);
            oci_bind_by_name($add, ':t_phone', $phone);
            oci_bind_by_name($add, ':t_status', $status);
            oci_bind_by_name($add, ':t_create', $admin);

            $run = oci_execute($add, OCI_NO_AUTO_COMMIT);

            if ($run) {
                oci_commit($conn);
                echo 'success';
            } else {
                $e = oci_error($add);
                echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
                oci_rollback($conn);
            }
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
