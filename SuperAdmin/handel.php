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
        $assignedMember = 'assign';

        // $getAssignedMembers = "SELECT TEAM_MEMBER_ASSIGNED_ID FROM tickets WHERE ID = :t_id";
        // $getAssigned = oci_parse($conn, $getAssignedMembers);
        // oci_bind_by_name($getAssigned, ':t_id', $ticketid);
        // oci_execute($getAssigned);
        // $existingMembers = oci_fetch_assoc($getAssigned);

        // $existingMembersID = $existingMembers['TEAM_MEMBER_ASSIGNED_ID'];
        // $ex = [];

        // while ($exis = $existingMembers) {
        //     $ex[] = $exis['TEAM_MEMBER_ASSIGNED_ID'];
        // }

        // foreach ($userID as $userIDs) {
        //     if (!in_array($userIDs, $ex)) {
        //         $stmt5 = "UPDATE tickets SET   (TEAM_LEADER_MEMBER_ID, STATUS, UPDATED_DATE ) 
        //                                  VALUES (:t_id, :t_sts, CURRENT_TIMESTAMP)
        //                                  WHERE ID = :t_IT";
        //         $stmt6 = oci_parse($conn, $stmt5);

        //         oci_bind_by_name($stmt6, ':t_id', $userIDs);
        //         oci_bind_by_name($stmt6, ':t_IT', $ticketid);
        //         oci_bind_by_name($stmt6, ':t_sts', $assignedMember);
        //     }
        // }

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

    } elseif ($action == 'start') {  // Start Solving Ticket ( Change Ticket Status To Started ) 

        $ticketid       = $_POST['tickid'];
        $userName       = $_SESSION['user'];

        $supUser = "SELECT USER_ID FROM DOCARCH.ACT_USERS_VW WHERE USERNAME = :t_name";
        $sup = oci_parse($conn, $supUser);
        // Bind the variables
        oci_bind_by_name($sup, ":t_name", $userName);
        // Execute the query
        oci_execute($sup);
        $row = oci_fetch_assoc($sup);

        $userID = $row['USER_ID'];

        $statusUpdate = 30;

        $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = :new_status, LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            TICKET_START_DATE = CURRENT_TIMESTAMP, LAST_UPDATED_BY = :t_user
                            WHERE TICKET_NO = :t_id";

        $status = oci_parse($conn, $statusTicket);

        oci_bind_by_name($status, ':new_status', $statusUpdate);
        oci_bind_by_name($status, ':t_id', $ticketid);
        oci_bind_by_name($status, ':t_user', $userID);

        $run = oci_execute($status, OCI_NO_AUTO_COMMIT);

        if ($run) {
            oci_commit($conn);
            echo 'done';
        } else {
            $e = oci_error($status);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            oci_rollback($conn);
        }
    } elseif ($action == 'solve') {  // Solved Ticket ( Change Ticket Status To Solved )

        $ticketid       = $_POST['tickid'];
        $issue          = $_POST['issue'];
        $resolution     = $_POST['resolution'];

        $userName       = $_SESSION['user'];

        $supUser = "SELECT USER_ID FROM DOCARCH.ACT_USERS_VW WHERE USERNAME = :t_name";
        $sup = oci_parse($conn, $supUser);
        // Bind the variables
        oci_bind_by_name($sup, ":t_name", $userName);
        // Execute the query
        oci_execute($sup);
        $row = oci_fetch_assoc($sup);

        $userID = $row['USER_ID'];

        $statusUpdate = 60;

        // var_dump($userName, $ticketName, $ticketDes, $userDep, $tags);

        $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = :new_status, LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            LAST_UPDATED_BY = :t_user, TECHNICAL_ISSUE_DESCRIPTION = :t_issue,
                            TECHNICAL_ISSUE_RESOLUTION = :t_resolution
                            WHERE TICKET_NO = :t_id";

        $status = oci_parse($conn, $statusTicket);

        oci_bind_by_name($status, ':new_status', $statusUpdate);
        oci_bind_by_name($status, ':t_issue', $issue);
        oci_bind_by_name($status, ':t_user', $userID);
        oci_bind_by_name($status, ':t_resolution', $resolution);
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
    } elseif ($action == 'add') {

        $details        = $_POST['details'];
        $userName       = $_POST['name'];
        $ticketDes      = $_POST['description'];
        $service        = $_POST['service'];

        $max = "SELECT MAX(TICKET_NO) FROM TICKETING.TICKETS";
        // -- Increment the ID for the new User
        $incrId = oci_parse($conn, $max);
        oci_execute($incrId);
        $id = oci_fetch_assoc($incrId);

        $ticketNO = ++$id['MAX(TICKET_NO)'];

        // Query to fetch users based on the selected department
        $supUser = "SELECT USER_ID, USER_EN_NAME, EMPLOYEE_ID  FROM DOCARCH.ACT_USERS_VW WHERE USERNAME = :t_name";
        $sup = oci_parse($conn, $supUser);
        // Bind the variables
        oci_bind_by_name($sup, ":t_name", $userName);
        // Execute the query
        oci_execute($sup);
        $row = oci_fetch_assoc($sup);

        $userEnName = $row['USER_EN_NAME'];
        $userID = $row['USER_ID'];
        $ebsEmployee = $row['EMPLOYEE_ID'];

        $ticketStatus = 10;

        // Query to Insert the Data To Tickets Table  

        $addTicket = "INSERT INTO TICKETING.TICKETS (TICKET_NO, REQUEST_TYPE_NO, TICKET_STATUS, ISSUE_DESCRIPTION, CREATION_DATE, CREATED_BY, SERVICE_DETAIL_NO, EBS_EMPLOYEE_ID, USER_EN_NAME, USERNAME, USER_ID) 
                                            VALUES (:t_no, :t_type, :t_status, :t_des, CURRENT_TIMESTAMP, :t_create, :t_details, :t_ebs, :t_enname, :t_name, :t_id)";
        $add = oci_parse($conn, $addTicket);

        oci_bind_by_name($add, ':t_no', $ticketNO);
        oci_bind_by_name($add, ':t_type', $service);
        oci_bind_by_name($add, ':t_status', $ticketStatus);
        oci_bind_by_name($add, ':t_des', $ticketDes);
        oci_bind_by_name($add, ':t_create', $userID);
        oci_bind_by_name($add, ':t_details', $details);
        oci_bind_by_name($add, ':t_ebs', $ebsEmployee);
        oci_bind_by_name($add, ':t_enname', $userEnName);
        oci_bind_by_name($add, ':t_name', $userName);
        oci_bind_by_name($add, ':t_id', $userID);

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

if (isset($_POST['type'])) {   // Choose Service Details Debends On Service Type
    $selectedService = $_POST['type'];  // Service Type Number

    // Query to fetch Service Details based on the selected Service Type
    $depUser = "SELECT * FROM TICKETING.SERVICE_DETAILS WHERE SERVICE_NO = :t_service";
    $dept = oci_parse($conn, $depUser);
    // Bind the variables
    oci_bind_by_name($dept, ":t_service", $selectedService);
    // Execute the query
    oci_execute($dept);
    // Build HTML options for users
    $options = '';
    while ($row = oci_fetch_assoc($dept)) {
        $options .= "<option value='{$row['SERVICE_DETAIL_NO']}'>{$row['SERVICE_DETAIL_NAME']}</option>";
    }
    echo $options;
}

if (isset($_POST['team'])) {   // Choose Team Information Debends On Team Number
    $selectedTeam = $_POST['team'];  // Team Number

    // Query to fetch Team Information based on the selected Team Number
    $teamName = "SELECT  TEAM_NAME, ACTIVE, BRANCH_CODE, DEPT_ID FROM TICKETING.TEAMS WHERE TEAM_NO = :t_team";
    $teamname = oci_parse($conn, $teamName);
    // Bind the variables
    oci_bind_by_name($teamname, ":t_team", $selectedTeam);
    // Execute the query
    oci_execute($teamname);
    // Build HTML options for users
    $result = oci_fetch_assoc($teamname);
    echo json_encode($result);
}

if (isset($_POST['member'])) {   // Choose Team Member Debends On Team Number
    $selectedMember = $_POST['member']; // Team Number

    // Query to fetch Team Member based on the selected Team Number
    $teamMember = "SELECT
                        TICKETING.TEAM_MEMBERS.* , 
                        DOCARCH.ACT_USERS_VW.USERNAME,USER_EN_NAME, 
                        TICKETING.TKT_REL_ROLE_USERS.ROLE_ID
                    FROM 
                        TICKETING.TEAM_MEMBERS
                    JOIN
                        DOCARCH.ACT_USERS_VW
                    ON 
                        DOCARCH.ACT_USERS_VW.USER_ID = TICKETING.TEAM_MEMBERS.TEAM_MEMBER_USER_ID
                    JOIN 
                        TICKETING.TKT_REL_ROLE_USERS
                    ON
                        TICKETING.TKT_REL_ROLE_USERS.USER_ID = DOCARCH.ACT_USERS_VW.USER_ID
                    WHERE
                        TICKETING.TEAM_MEMBERS.TEAM_NO = :t_team";
    $team = oci_parse($conn, $teamMember);
    // Bind the variables
    oci_bind_by_name($team, ":t_team", $selectedMember);
    // Execute the query
    oci_execute($team);
    $data = array();
    while ($row = oci_fetch_assoc($team)) {
        $data[] = array(
            'userName' => $row['USERNAME'],
            'name' => $row['USER_EN_NAME'],
            'description' => $row['DESCRIPTION'],
            'active' => $row['ACTIVE'],
            'supervisor' => $row['ROLE_ID'],
            'manager' => $row['ROLE_ID']
        );
    }
    echo json_encode($data);
}

if (isset($_POST['delegate'])) {   // Display Delegated User Debends On Team Number In Team Member Section
    $delegateUser = $_POST['delegate']; // Team Number
    // Query to fetch Delagated Users based on the selected Team Number
    $memberDelegated = "SELECT
                            TICKETING.DELEGATED_TEM_SUPER.* , 
                            DOCARCH.ACT_USERS_VW.USERNAME,USER_EN_NAME
                        FROM 
                            TICKETING.DELEGATED_TEM_SUPER
                        JOIN
                            DOCARCH.ACT_USERS_VW
                        ON 
                            DOCARCH.ACT_USERS_VW.USER_ID = TICKETING.DELEGATED_TEM_SUPER.DELEGATE_USER_ID
                        WHERE
                            TICKETING.DELEGATED_TEM_SUPER.TEAM_NO = :t_team";
    $delegate = oci_parse($conn, $memberDelegated);
    // Bind the variables
    oci_bind_by_name($delegate, ":t_team", $delegateUser);
    // Execute the query
    oci_execute($delegate);
    $data = array();
    while ($row = oci_fetch_assoc($delegate)) {
        $data[] = array(
            'name'  => $row['USER_EN_NAME'],
            'start' => $row['START_DATE'],
            'end'   => $row['END_DATE']
        );
    }
    echo json_encode($data);
}

if (isset($_POST['delegated'])) {   // Display Delegated User Debends On Team Number In Delegate Supervisor Section
    $delegateUser = $_POST['delegated']; // Team Number

    // Query to fetch Delegated users based on the selected Team Number
    $memberDelegated = "SELECT
                            TICKETING.DELEGATED_TEM_SUPER.* , 
                            DOCARCH.ACT_USERS_VW.USERNAME,USER_EN_NAME
                        FROM 
                            TICKETING.DELEGATED_TEM_SUPER
                        JOIN
                            DOCARCH.ACT_USERS_VW
                        ON 
                            DOCARCH.ACT_USERS_VW.USER_ID = TICKETING.DELEGATED_TEM_SUPER.DELEGATE_USER_ID
                        WHERE
                            TICKETING.DELEGATED_TEM_SUPER.TEAM_NO = :t_team";
    $delegate = oci_parse($conn, $memberDelegated);
    // Bind the variables
    oci_bind_by_name($delegate, ":t_team", $delegateUser);
    // Execute the query
    oci_execute($delegate);
    $data = array();
    while ($row = oci_fetch_assoc($delegate)) {
        $data[] = array(
            'name'  => $row['USER_EN_NAME'],
            'start' => $row['START_DATE'],
            'end'   => $row['END_DATE']
        );
    }
    echo json_encode($data);
}

if (isset($_POST['service'])) {   // Choose Service name Debends On Service Number
    $selectedService = $_POST['service'];  // Service Number

    // Query to fetch Service Name based on the selected Service Number
    $serviceName = "SELECT  SERVICE_NAME FROM TICKETING.SERVICE WHERE SERVICE_NO = :t_serv";
    $servicename = oci_parse($conn, $serviceName);
    // Bind the variables
    oci_bind_by_name($servicename, ":t_serv", $selectedService);
    // Execute the query
    oci_execute($servicename);
    // Build HTML options for users
    $result = oci_fetch_assoc($servicename);
    echo $result['SERVICE_NAME'];
}

if (isset($_POST['details'])) {   // Choose Service Details Debends On Service Number
    $serviceDetails = $_POST['details'];  // Service Number

    // Query to fetch Service Details based on the selected Service Number
    $serviceDetail = "SELECT * FROM TICKETING.SERVICE_DETAILS WHERE SERVICE_NO = :t_service";
    $details = oci_parse($conn, $serviceDetail);
    // Bind the variables
    oci_bind_by_name($details, ":t_service", $serviceDetails);
    // Execute the query
    oci_execute($details);
    $data = array();
    while ($row = oci_fetch_assoc($details)) {
        $data[] = array(
            'name'      => $row['SERVICE_DETAIL_NAME'],
            'desc'      => $row['DESCRIPTION'],
            'custody'   => $row['CUSTODY_LINK'],
            'private'   => $row['PRIVATE_FLAG']
        );
    }
    echo json_encode($data);
}

if (isset($_POST['serviceTeam'])) {   // Choose Team Name Debends On Service Type
    $serviceDetailsTeam = $_POST['serviceTeam'];  // Service Number

    // Query to fetch Team Name based on the selected Service Type
    $serviceTeam = "SELECT 
                        TICKETING.SERVICE_DETAILS_TEAMS.*, TICKETING.TEAMS.TEAM_NAME
                    FROM 
                        TICKETING.SERVICE_DETAILS_TEAMS
                    JOIN 
                        TICKETING.TEAMS
                    ON 
                        TICKETING.TEAMS.TEAM_NO = TICKETING.SERVICE_DETAILS_TEAMS.TEAM_NO
                    WHERE 
                        TICKETING.SERVICE_DETAILS_TEAMS.SERVICE_DETAIL_NO = :t_service";
    $teamDetails = oci_parse($conn, $serviceTeam);
    // Bind the variables
    oci_bind_by_name($teamDetails, ":t_service", $serviceDetailsTeam);
    // Execute the query
    oci_execute($teamDetails);
    $data = array();
    while ($row = oci_fetch_assoc($teamDetails)) {
        $data[] = array(
            'name'          => $row['TEAM_NAME'],
            'enable'        => $row['ENABLED']

        );
    }
    echo json_encode($data);
}

if (isset($_POST['teamMember'])) {   // Choose Team Member Debends On Team Number
    $teamNumber = $_POST['teamMember'];  // Service Number

    // Query to fetch Team Member based on the selected Team Number
    $teamMembers = "SELECT 
                        TICKETING.TEAM_MEMBERS.*, DOCARCH.ACT_USERS_VW.USERNAME, USER_EN_NAME
                    FROM 
                        TICKETING.TEAM_MEMBERS 
                    JOIN 
                        DOCARCH.ACT_USERS_VW
                    ON 
                        DOCARCH.ACT_USERS_VW.USER_ID = TICKETING.TEAM_MEMBERS.TEAM_MEMBER_USER_ID
                    WHERE TEAM_NO = :t_team";
    $team = oci_parse($conn, $teamMembers);
    // Bind the variables
    oci_bind_by_name($team, ":t_team", $teamNumber);
    // Execute the query
    oci_execute($team);
    $data = array();
    while ($row = oci_fetch_assoc($team)) {
        $data[] = array(
            'name'         => $row['USERNAME'],
            'Ename'        => $row['USER_EN_NAME'],
            'active'        => $row['ACTIVE']
        );
    }
    echo json_encode($data);
}
