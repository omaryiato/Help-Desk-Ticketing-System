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
        $comment       = $_POST['comment'];

        $statusUpdate = 'solved';

        // var_dump($userName, $ticketName, $ticketDes, $userDep, $tags);

        $statusTicket = "UPDATE tickets SET 
                            STATUS = :new_status, UPDATED_DATE = CURRENT_TIMESTAMP, admin_comments = :t_comments
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
        $userStatus     = $_POST['userStatus'];
        $usertype       = $_POST['usertype'];
        $phone          = $_POST['phone'];
        $admin          = $_POST['admin'];
        $userid         = ++$id['MAX(ID)'];

        $serviceType = "SELECT DEPARTMENT FROM users WHERE NAME = :t_name";
        $service = oci_parse($conn, $serviceType);
        oci_bind_by_name($service, ':t_name', $admin);
        oci_execute($service);
        $department = oci_fetch_assoc($service);


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
            oci_bind_by_name($add, ':t_dep', $department['DEPARTMENT']);
            oci_bind_by_name($add, ':t_phone', $phone);
            oci_bind_by_name($add, ':t_status', $userStatus);
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
    } elseif ($action == 'edit') {

        $userid         = $_POST['userid'];
        $username       = $_POST['username'];
        $email          = $_POST['email'];
        $phone          = $_POST['phone'];
        $usertype       = $_POST['usertype'];

        $statusTicket = "UPDATE users SET 
                            NAME = :t_name,
                            EMAIL = :t_email,
                            PHONE_NUMBER = :t_phone,
                            STATUS = :t_status,
                            UPDATED_DATE = CURRENT_TIMESTAMP
                            WHERE ID = :t_id";

        $status = oci_parse($conn, $statusTicket);

        oci_bind_by_name($status, ':t_id', $userid);
        oci_bind_by_name($status, ':t_name', $username);
        oci_bind_by_name($status, ':t_email', $email);
        oci_bind_by_name($status, ':t_phone', $phone);
        oci_bind_by_name($status, ':t_status', $usertype);

        $run = oci_execute($status, OCI_NO_AUTO_COMMIT);

        if ($run) {
            oci_commit($conn);
            echo 'done';
        } else {
            $e = oci_error($status);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }
    } elseif ($action == 'service') {

        $max = "SELECT MAX(ID) FROM service_details";

        // -- Increment the ID for the new User
        $incrId = oci_parse($conn, $max);
        oci_execute($incrId);
        $id = oci_fetch_assoc($incrId);

        $name           = $_POST['name'];
        $admin          = $_POST['admin'];
        $userid         = ++$id['MAX(ID)'];

        $serviceType = "SELECT 
                            service_type.*, users.department AS department
                        FROM 
                            service_type
                        JOIN 
                            users 
                        ON 
                            users.department = service_type.type
                        WHERE users.NAME = :t_name";
        $service = oci_parse($conn, $serviceType);
        oci_bind_by_name($service, ':t_name', $admin);
        oci_execute($service);
        $department = oci_fetch_assoc($service);

        $users = "SELECT service_details FROM service_details WHERE service_details = :t_name";
        $user = oci_parse($conn, $users);
        oci_bind_by_name($user, ':t_name', $name);
        // Execute the query
        oci_execute($user);
        $row = oci_fetch_assoc($user);


        if ($row['SERVICE_DETAILS'] == $name) {
            echo 'exist';
        } else {

            $addUser = "INSERT INTO service_details (ID, service_type_id, service_details) 
            VALUES (:t_id, :t_dept, :t_details)";

            $add = oci_parse($conn, $addUser);

            oci_bind_by_name($add, ':t_id', $userid);
            oci_bind_by_name($add, ':t_dept', $department['ID']);
            oci_bind_by_name($add, ':t_details', $name);


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
    } elseif ($action == 'editservice') {

        $serviceID         = $_POST['serviceID'];
        $serviceName       = $_POST['serviceName'];

        $statusTicket = "UPDATE service_details SET 
                            service_details = :t_name
                            WHERE ID = :t_id";

        $status = oci_parse($conn, $statusTicket);

        oci_bind_by_name($status, ':t_id', $serviceID);
        oci_bind_by_name($status, ':t_name', $serviceName);

        $run = oci_execute($status, OCI_NO_AUTO_COMMIT);

        if ($run) {
            oci_commit($conn);
            echo 'done';
        } else {
            $e = oci_error($status);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }
    } elseif ($action == 'deleservice') {

        $serviceid       = $_POST['serviceid'];

        $member = "DELETE FROM service_details WHERE ID = :t_id";

        $user = oci_parse($conn, $member);

        oci_bind_by_name($user, ':t_id', $serviceid);

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
