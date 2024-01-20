<?php


//   ********************  This Page Contain All Function and Ajax Function ***************************


// session_start();

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
        $userID       = $_POST['UserSessionID'];
        $statusUpdate = 30;

        $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = :new_status, LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            TICKET_START_DATE = CURRENT_TIMESTAMP, LAST_UPDATED_BY = :t_user
                            WHERE TICKET_NO = :t_id";

        $status = oci_parse($conn, $statusTicket);

        oci_bind_by_name($status, ':new_status', $statusUpdate);
        oci_bind_by_name($status, ':t_id', $ticketid);
        oci_bind_by_name($status, ':t_user', $userID);

        $run = oci_execute($status);

        if ($run) {
            echo 'done';
        } else {
            $e = oci_error($status);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
        }
    } elseif ($action == 'solve') {  // Solved Ticket ( Change Ticket Status To Solved )

        $ticketid       = $_POST['tickid'];
        $issue          = $_POST['issue'];
        $resolution     = $_POST['resolution'];
        $userID         = $_POST['UserSessionID'];
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
    } elseif ($action == 'add') {                               // Add New Ticket Function

        $userName       = $_POST['name'];       // user who create the ticket
        $serviceType    = $_POST['service'];
        $details        = $_POST['details'];
        $device         = $_POST['device'];  // its will be not empty if $details = custody  else then = null 
        $description    = $_POST['description'];
        $ticketStatus   = 10;                   // Ticket Status Static Because All Ticket Created With Status Initial (new) with code (10)

        // -- Increment the ID for the new Ticket
        $max        = "SELECT MAX(TICKET_NO) FROM TICKETING.TICKETS";
        $incrId     = oci_parse($conn, $max);
        oci_execute($incrId);
        $id         = oci_fetch_assoc($incrId);
        $ticketNO   = ++$id['MAX(TICKET_NO)'];

        // Query to fetch users Information based on User Name
        $userInfo   = "SELECT *  FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = :t_name";
        $info       = oci_parse($conn, $userInfo);
        // Bind the variables
        oci_bind_by_name($info, ":t_name", $userName);
        // Execute the query
        oci_execute($info);
        $row        = oci_fetch_assoc($info);

        $departmentNo       = $row['COST_CENTER'];  // We Goona Use It For 2 Column (Depasrtment No, Cost Center)
        $ebsEmployee        = $row['EBS_EMPLOYEE_ID'];
        $userEnName         = $row['USER_EN_NAME'];
        $userID             = $row['USER_ID'];  // We Goona Use It For 3 Column (Created By, User ID, Last Update By)
        $email              = $row['EMAIL'];
        $empDepartment      = $row['EMP_DEPARTMENT'];
        $branchCode         = $row['BRANCH_CODE'];
        $jopDesc            = $row['JOB_DESC'];

        //  Query to Insert Ticket New Ticket Information
        $addTicket = "INSERT INTO TICKETING.TICKETS (TICKET_NO, REQUEST_TYPE_NO, TICKET_START_DATE, 
                                                    DEVICE_NO, DEPARTMENT_NO, TICKET_STATUS,
                                                    ISSUE_DESCRIPTION, CREATED_BY, CREATION_DATE,
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE, SERVICE_DETAIL_NO,
                                                    EBS_EMPLOYEE_ID, USER_EN_NAME, USERNAME, 
                                                    USER_ID, EMAIL, COST_CENTER, 
                                                    EMP_DEPARTMENT, BRANCH_CODE_NEW, JOB_DESC) 
                                            VALUES (:t_no, :t_type, CURRENT_TIMESTAMP, 
                                                    :t_device, :t_dept, :t_status,
                                                    :t_des, :t_create, CURRENT_TIMESTAMP, 
                                                    :t_update, CURRENT_TIMESTAMP, :t_details, 
                                                    :t_ebs, :t_enname, :t_name, 
                                                    :t_id, :t_email, :t_cost,
                                                    :t_empdept, :t_branch, :t_job)";
        $add = oci_parse($conn, $addTicket);

        oci_bind_by_name($add, ':t_no', $ticketNO);
        oci_bind_by_name($add, ':t_type', $serviceType);
        // TICKET_START_DATE (CURRENT_TIMESTAMP) dont eed bind 
        oci_bind_by_name($add, ':t_device', $device);
        oci_bind_by_name($add, ':t_dept', $departmentNo);
        oci_bind_by_name($add, ':t_status', $ticketStatus);
        oci_bind_by_name($add, ':t_des', $description);
        oci_bind_by_name($add, ':t_create', $userID);  // created by
        //  CREATION_DATE  (CURRENT_TIMESTAMP) dont eed bind 
        oci_bind_by_name($add, ':t_update', $userID);  // last update by
        //  LAST_UPDATE_DATE (CURRENT_TIMESTAMP) dont eed bind 
        oci_bind_by_name($add, ':t_details', $details);
        oci_bind_by_name($add, ':t_ebs', $ebsEmployee);
        oci_bind_by_name($add, ':t_enname', $userEnName);
        oci_bind_by_name($add, ':t_name', $userName);
        oci_bind_by_name($add, ':t_id', $userID);
        oci_bind_by_name($add, ':t_email', $email);
        oci_bind_by_name($add, ':t_cost', $departmentNo);
        oci_bind_by_name($add, ':t_empdept', $empDepartment);
        oci_bind_by_name($add, ':t_branch', $branchCode);
        oci_bind_by_name($add, ':t_job', $jopDesc);
        $run = oci_execute($add);

        //  Query to Insert Ticket Information In Ticket Action History
        $addTicketAction = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                                    ACTION_DATE, COMMENTS, CREATED_BY,
                                                                    CREATION_DATE, LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                                            VALUES (:t_id, :t_seq, :t_action, 
                                                                    CURRENT_TIMESTAMP, :t_comment,
                                                                    :t_by, CURRENT_TIMESTAMP, :t_last, 
                                                                    CURRENT_TIMESTAMP)";
        $action = oci_parse($conn, $addTicketAction);

        $sequenceNumber = 1;

        oci_bind_by_name($action, ':t_id', $ticketNO);
        oci_bind_by_name($action, ':t_seq', $sequenceNumber);
        oci_bind_by_name($action, ':t_action', $ticketStatus);
        // TICKET_START_DATE (CURRENT_TIMESTAMP) dont eed bind 
        oci_bind_by_name($action, ':t_comment', $description);
        oci_bind_by_name($action, ':t_by', $userID);
        // TICKET_START_DATE (CURRENT_TIMESTAMP) dont eed bind 
        oci_bind_by_name($action, ':t_last', $userID);
        // TICKET_START_DATE (CURRENT_TIMESTAMP) dont eed bind 
        $resualt = oci_execute($action);

        if ($resualt) {
            echo $ticketNO;
        } else {
            $e = oci_error($resualt);
            var_dump($e); // Output detailed error information
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
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
    ////////////////////////////////////////////////////////////   Service & Service Details Page Request Functions Start 
    elseif ($action == 'NewService') {                        // Add New Service Function

        $NewServiceName         = $_POST['serviceName'];
        $UserSession            = $_POST['UserSessionID'];

        $checkServiceName = "SELECT SERVICE_NAME FROM TICKETING.SERVICE WHERE SERVICE_NAME = '" . $NewServiceName . "'";

        $serviceName = oci_parse($conn, $checkServiceName);
        // Execute the query
        oci_execute($serviceName);
        $empty = oci_fetch($serviceName);
        if ($empty == 0) {
            // Query to fetch Last Service ID To Create The Next ID
            $lastServiceID = "SELECT MAX(SERVICE_NO) FROM TICKETING.SERVICE";
            $serviceNo     = oci_parse($conn, $lastServiceID);
            oci_execute($serviceNo);
            $result        = oci_fetch_array($serviceNo);
            $NewServiceID  = ++$result['MAX(SERVICE_NO)'];

            $NewService = "INSERT INTO TICKETING.SERVICE (SERVICE_NO, SERVICE_NAME, CREATED_BY, 
                                            CREATION_DATE, LAST_UPDATED_BY, LAST_UPDATE_DATE)
                                    VALUES ($NewServiceID, '$NewServiceName' , $UserSession, CURRENT_TIMESTAMP, $UserSession, CURRENT_TIMESTAMP)";
            $AddNewService = oci_parse($conn, $NewService);
            $run = oci_execute($AddNewService);

            if ($run) {
                // Query to fetch Service Type 
                $serviceType = "SELECT SERVICE_NO, SERVICE_NAME FROM TICKETING.SERVICE";
                $service = oci_parse($conn, $serviceType);
                oci_execute($service);
                // Build HTML options for users
                $options = '';
                while ($row = oci_fetch_assoc($service)) {
                    $options .= "<option value='{$row['SERVICE_NO']}'>{$row['SERVICE_NAME']}</option>";
                }
                echo $options;
            } else {
                $e = oci_error($AddNewService);
                echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            }
        } else {
            echo 'wrong';
        }
    } elseif ($action == 'NewServiceDetails') {                 // Add New Service Details Function

        $NewServiceDetailsName                  = $_POST['NewServiceDetailsName'];
        $UserSession                            = $_POST['UserSessionID'];
        $GetServiceTypeID                      = $_POST['GetServiceTypeID'];
        $ServiceDetailsDescription              = $_POST['ServiceDetailsDescription'];

        $checkServiceDetailName = "SELECT SERVICE_DETAIL_NAME, SERVICE_NO FROM TICKETING.SERVICE_DETAILS WHERE SERVICE_NO=" . $GetServiceTypeID . " AND SERVICE_DETAIL_NAME = '" . $NewServiceDetailsName . "'";

        $serviceDetailName = oci_parse($conn, $checkServiceDetailName);
        // Execute the query
        oci_execute($serviceDetailName);
        $empty = oci_fetch($serviceDetailName);

        if ($empty == 0) {

            // Query to fetch Last Service Details ID To Create The Next ID
            $lastServiceDetailsID = "SELECT MAX(SERVICE_DETAIL_NO) FROM TICKETING.SERVICE_DETAILS";
            $serviceDetailsNo     = oci_parse($conn, $lastServiceDetailsID);
            oci_execute($serviceDetailsNo);
            $result        = oci_fetch_array($serviceDetailsNo);
            $NewServiceDetailsNo  = ++$result['MAX(SERVICE_DETAIL_NO)'];

            $NewServiceDetails = "INSERT INTO TICKETING.SERVICE_DETAILS (SERVICE_DETAIL_NO, SERVICE_NO, SERVICE_DETAIL_NAME, 
                                                    DESCRIPTION,  CREATED_BY, CREATION_DATE, 
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE)
                                            VALUES ($NewServiceDetailsNo, $GetServiceTypeID, '$NewServiceDetailsName', 
                                                    '$ServiceDetailsDescription',  $UserSession, CURRENT_TIMESTAMP, 
                                                    $UserSession, CURRENT_TIMESTAMP)";
            $AddNewServiceDetails = oci_parse($conn, $NewServiceDetails);
            $run = oci_execute($AddNewServiceDetails);

            if ($run) {
                echo 'done';
            } else {
                $e = oci_error($AddNewServiceDetails);
                echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            }
        } else {
            echo 'wrong';
        }
    } elseif ($action == 'NewServiceDetailsTeam') {             // Add New Service Details Function

        $GetServiceDetailsName                  = $_POST['GetServiceDetailsName'];
        $GetServiceDetailsTeamNumber            = $_POST['GetServiceDetailsTeamNumber'];
        $GetServiceDetailsID                    = $_POST['GetServiceDetailsID'];
        $UserSessionID                          = $_POST['UserSessionID'];

        // Query to fetch Last Service Details ID To Create The Next ID
        $lastSetailsTeamID = "SELECT MAX(DETAISL_TEAMS_ID) FROM TICKETING.SERVICE_DETAILS_TEAMS";
        $serviceDetailsTeamNo     = oci_parse($conn, $lastSetailsTeamID);
        oci_execute($serviceDetailsTeamNo);
        $result        = oci_fetch_array($serviceDetailsTeamNo);
        $NewServiceDetailsTeamNo  = ++$result['MAX(DETAISL_TEAMS_ID)'];

        $NewServiceDetailsTeam = "INSERT INTO TICKETING.SERVICE_DETAILS_TEAMS 
                                                    (DETAISL_TEAMS_ID, SERVICE_DETAIL_NO, 
                                                    TEAM_NO,  CREATED_BY, CREATION_DATE, 
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE)
                                            VALUES ($NewServiceDetailsTeamNo, $GetServiceDetailsID, 
                                                    $GetServiceDetailsTeamNumber, $UserSessionID, 
                                                    CURRENT_TIMESTAMP,$UserSessionID, CURRENT_TIMESTAMP)";
        $AddNewServiceDetailsTeam = oci_parse($conn, $NewServiceDetailsTeam);
        $run = oci_execute($AddNewServiceDetailsTeam);

        if ($run) {
            echo 'done';
        } else {
            $e = oci_error($AddNewServiceDetailsTeam);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
        }
    } elseif ($action == 'updateTeamTable') {                   // Update Team Enabled Function

        $teamEnabled = json_decode($_POST['teamEnabled'], true);
        $userID = $_POST['userID'];

        foreach ($teamEnabled as $row) {
            $teamNo             = $row['teamNo'];
            $newStatus          = $row['newStatus'];
            $serviceDetailsID   = $row['serviceDetailsID'];

            $ServiceDetailTeamID = "UPDATE TICKETING.SERVICE_DETAILS_TEAMS SET 
                                    ENABLED ='" . $newStatus . "', LAST_UPDATED_BY = " . $userID . ",
                                    LAST_UPDATE_DATE = CURRENT_TIMESTAMP  WHERE TEAM_NO= " . $teamNo . " AND SERVICE_DETAIL_NO=" . $serviceDetailsID;
            $TeamID = oci_parse($conn, $ServiceDetailTeamID);
            $run = oci_execute($TeamID);

            if (!$run) {
                http_response_code(500); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($TeamID)['message']]);
                exit;
            }
        }

        // Send a success response with HTTP status 200
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
    } elseif ($action == 'updateServiceDetailsTable') {         // Update Custody And Private In Service Details Table Function

        $custodyColumnJson = json_decode($_POST['custodyColumnJson'], true);
        $privateColumnJson = json_decode($_POST['privateColumnJson'], true);
        $userID = $_POST['userID'];

        foreach ($custodyColumnJson as $row) {
            $servaiceDetailsNo              = $row['servaiceDetailsNo'];
            $newStatus                      = $row['newStatus'];

            $ServiceDetailID = "UPDATE TICKETING.SERVICE_DETAILS SET 
                                CUSTODY_LINK ='" . $newStatus . "', LAST_UPDATED_BY = " . $userID . ",
                                LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE SERVICE_DETAIL_NO= " . $servaiceDetailsNo;
            $custodyStatus = oci_parse($conn, $ServiceDetailID);
            $run = oci_execute($custodyStatus);

            if (!$run) {
                http_response_code(500); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($custodyStatus)['message']]);
                exit;
            }
        }

        foreach ($privateColumnJson as $row) {
            $servaiceDetailsNo              = $row['servaiceDetailsNo'];
            $newStatus                      = $row['newStatus'];

            $ServiceDetailID = "UPDATE TICKETING.SERVICE_DETAILS SET 
                                PRIVATE_FLAG ='" . $newStatus . "', LAST_UPDATED_BY = " . $userID . ",
                                LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE SERVICE_DETAIL_NO= " . $servaiceDetailsNo;
            $privateStatus = oci_parse($conn, $ServiceDetailID);
            $run = oci_execute($privateStatus);

            if (!$run) {
                http_response_code(500); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($privateStatus)['message']]);
                exit;
            }
        }

        // Send a success response with HTTP status 200
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
    } elseif ($action == 'EditServiceDetailsInformation') {     // Update Service Details Information In Service Details Table Function

        $EditServiceDetailsName         = $_POST['EditServiceDetailsName'];
        $UserSessionID                  = $_POST['UserSessionID'];
        $EditServiceDetailsDescription  = $_POST['EditServiceDetailsDescription'];
        $EditServiceDetailsID           = $_POST['EditServiceDetailsID'];

        $UpdateServiceDetailsInfo = "UPDATE TICKETING.SERVICE_DETAILS SET " .
            "SERVICE_DETAIL_NAME = '" . $EditServiceDetailsName . "'" . ",
                                            DESCRIPTION = '" . $EditServiceDetailsDescription . "'" . ",
                                            LAST_UPDATED_BY = " . $UserSessionID . ",
                                            LAST_UPDATE_DATE = CURRENT_TIMESTAMP
                                            WHERE SERVICE_DETAIL_NO =" . $EditServiceDetailsID;

        $updatedServiceDetailsinfo = oci_parse($conn, $UpdateServiceDetailsInfo);

        $run = oci_execute($updatedServiceDetailsinfo);

        if ($run) {
            echo 'success';
        } else {
            $e = oci_error($updatedServiceDetailsinfo);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
        }
    }   ////////////////////////////////////////////////////////////   Service & Service Details Page Request Functions End 

    ////////////////////////////////////////////////////////////   Team & Team Member Page Request Functions Start
    elseif ($action == 'NewTeam') {                        // Add New Team Function

        $NewTeamName            = $_POST['NewTeamName'];
        $branchCode             = $_POST['branchCode'];
        $description            = $_POST['description'];
        $departmentID           = $_POST['departmentID'];
        $UserSessionID          = $_POST['UserSessionID'];

        $checkTeamName = "SELECT TEAM_NAME FROM TICKETING.TEAMS WHERE TEAM_NAME = '" . $NewTeamName . "'";

        $teamName = oci_parse($conn, $checkTeamName);
        // Execute the query
        oci_execute($teamName);
        $empty = oci_fetch($teamName);
        if ($empty == 0) {
            // Query to fetch Last Team ID To Create The Next ID
            $lastTeamID = "SELECT MAX(TEAM_NO) FROM TICKETING.TEAMS";
            $TeamNo     = oci_parse($conn, $lastTeamID);
            oci_execute($TeamNo);
            $result        = oci_fetch_array($TeamNo);
            $NewTeamID  = ++$result['MAX(TEAM_NO)'];

            $NewTeam = "INSERT INTO TICKETING.TEAMS (TEAM_NO, TEAM_NAME, DESCRIPTION, CREATED_BY, 
                                            CREATION_DATE, LAST_UPDATED_BY, LAST_UPDATE_DATE, BRANCH_CODE, DEPT_ID)
                                    VALUES ($NewTeamID, '$NewTeamName', '$description', $UserSessionID,
                                    CURRENT_TIMESTAMP, $UserSessionID, CURRENT_TIMESTAMP, '$branchCode', $departmentID)";
            $AddNewTeam = oci_parse($conn, $NewTeam);
            $run = oci_execute($AddNewTeam);

            if ($run) {
                // Query to fetch Team Name 
                $teamsName = "SELECT TEAM_NO, TEAM_NAME FROM TICKETING.TEAMS";
                $Team = oci_parse($conn, $teamsName);
                oci_execute($Team);
                // Build HTML options for users
                $options = '';
                while ($row = oci_fetch_assoc($Team)) {
                    $options .= "<option value='{$row['TEAM_NO']}'>{$row['TEAM_NAME']}</option>";
                }
                echo $options;
            } else {
                $e = oci_error($AddNewTeam);
                echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            }
        } else {
            http_response_code(500);
        }
    } elseif ($action == 'EditTeamInformation') {                        // Add New Team Function

        $EditTeamID            = $_POST['EditTeamID'];
        $UserSessionID         = $_POST['UserSessionID'];
        $EditTeamName          = $_POST['EditTeamName'];
        $EditTeamDescription   = $_POST['EditTeamDescription'];
        $EditTeamBranchCode    = $_POST['EditTeamBranchCode'];
        $EditTeamStatus        = $_POST['EditTeamStatus'];
        $EditTeamDepartmentID  = $_POST['EditTeamDepartmentID'];

        $NewTeamInfo = "UPDATE TICKETING.TEAMS SET " .
            "TEAM_NAME = '" . $EditTeamName . "'" . ",
                                    ACTIVE = '" . $EditTeamStatus . "'" . ",
                                    DESCRIPTION = '" . $EditTeamDescription . "'" . ",
                                    LAST_UPDATED_BY = " . $UserSessionID . ",
                                    LAST_UPDATE_DATE = CURRENT_TIMESTAMP, 
                                    BRANCH_CODE = '" . $EditTeamBranchCode . "'" . ",
                                    DEPT_ID = " . $EditTeamDepartmentID . "
                                    WHERE TEAM_NO =" . $EditTeamID;

        $UpdateTeamInfo = oci_parse($conn, $NewTeamInfo);
        $run = oci_execute($UpdateTeamInfo);

        if ($run) {
            // Query to fetch Team Name 
            $teamsName = "SELECT TEAM_NO, TEAM_NAME FROM TICKETING.TEAMS";
            $Team = oci_parse($conn, $teamsName);
            oci_execute($Team);
            // Build HTML options for users
            $options = '';
            while ($row = oci_fetch_assoc($Team)) {
                $options .= "<option value='{$row['TEAM_NO']}'>{$row['TEAM_NAME']}</option>";
            }
            echo $options;
        } else {
            $e = oci_error($UpdateTeamInfo);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
        }
    }  ////////////////////////////////////////////////////////////   Team & Team Member Page Request Functions End

}




///////////////////////////////////////////***************** Delegate Supervisor Page Request Functions Start  *************************/////////////////////////////////////////

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

///////////////////////////////////////////***************** Delegate Supervisor Page Request Functions End  *************************/////////////////////////////////////////


///////////////////////////////////////////***************** Change All Solved Ticket To Confirmed Request Functions Start  *************************/////////////////////////////////////////

if (isset($_POST['UserNameSession'])) {   // Change All Status Solved Ticket To Confirmed Status
    $UserSessionID = $_POST['UserNameSession']; // User ID Session
    $NewStatus = 40;
    $oldStatus =  60;

    // Query to fetch Delegated users based on the selected Team Number
    $confirmTicket = "UPDATE TICKETING.TICKETS SET " .
        "TICKET_END_DATE = CURRENT_TIMESTAMP,
                                    TICKET_STATUS = " . $NewStatus .  ",
                                    LAST_UPDATED_BY = " . $UserSessionID . ",
                                    LAST_UPDATE_DATE = CURRENT_TIMESTAMP
                                    WHERE TICKET_STATUS =" . $oldStatus;
    $confirmed = oci_parse($conn, $confirmTicket);
    $run = oci_execute($confirmed);
    if ($run) {
        http_response_code(200);
    } else {
        $e = oci_error($confirmed);
        echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
    }
}

///////////////////////////////////////////***************** Change All Solved Ticket To Confirmed Request Functions End  *************************/////////////////////////////////////////


///////////////////////////////////////////***************** Ticket Transation Page Request Functions Start  *************************/////////////////////////////////////////


if (isset($_POST['teamMembers'])) {   // Choose Team Member Based On Team Number In Assign Page
    $teamNumber = $_POST['teamMembers'];  // Team Number

    // Query to fetch Team Member based on the selected Team Number
    $teamMembers = "SELECT 
                        TICKETING.TEAM_MEMBERS.ACTIVE, TICKETING.xxajmi_ticket_user_info.USERNAME, USER_EN_NAME
                    FROM 
                        TICKETING.TEAM_MEMBERS 
                    JOIN 
                        TICKETING.xxajmi_ticket_user_info
                    ON 
                        TICKETING.xxajmi_ticket_user_info.USER_ID = TICKETING.TEAM_MEMBERS.TEAM_MEMBER_USER_ID
                    WHERE TEAM_NO = " . $teamNumber;
    $team = oci_parse($conn, $teamMembers);

    oci_execute($team);
    $data = array();
    while ($row = oci_fetch_assoc($team)) {
        $data[] = array(
            'name'          => $row['USERNAME'],
            'Ename'         => $row['USER_EN_NAME'],
            'active'        => $row['ACTIVE']
        );
    }
    echo json_encode($data);
}

if (isset($_POST['ticketNumber'])) {   // Choose Team Information Debends On Team Number Assign Popup
    $ticketNumber    = $_POST['ticketNumber'];

    // Query to fetch Last Service Details ID To Create The Next ID
    $ticketInformation = "SELECT 
                                TICKETING.TICKETS.TICKET_NO,USER_EN_NAME , TICKETING.SERVICE.SERVICE_NAME, TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NAME 
                            FROM 
                                TICKETING.TICKETS
                            JOIN
                                TICKETING.SERVICE 
                            ON 
                                TICKETING.SERVICE.SERVICE_NO = TICKETING.TICKETS.REQUEST_TYPE_NO
                            JOIN
                                TICKETING.SERVICE_DETAILS
                            ON
                                TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO = TICKETING.TICKETS.SERVICE_DETAIL_NO
                            WHERE TICKETING.TICKETS.TICKET_NO=" . $ticketNumber;
    $information     = oci_parse($conn, $ticketInformation);
    oci_execute($information);
    // Build HTML options for users
    $result = oci_fetch_assoc($information);
    echo json_encode($result);
}

if (isset($_POST['selectDetailsTeamMember'])) {   // Retrive  Team Member Based On Team Name
    $selectedServiceDetailsName = $_POST['selectDetailsTeamMember'];  // Service Type Number

    // Query to fetch Service Details based on the selected Service Type
    $ServiceDetailTeamMemberName = "SELECT TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO, TICKETING.SERVICE_DETAILS_TEAMS.TEAM_NO,
                            TICKETING.TEAMS.TEAM_NAME
                        FROM 
                            TICKETING.SERVICE_DETAILS
                        JOIN
                        TICKETING.SERVICE_DETAILS_TEAMS
                        ON
                        TICKETING.SERVICE_DETAILS_TEAMS.SERVICE_DETAIL_NO = TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO
                        JOIN
                        TICKETING.TEAMS
                        ON
                        TICKETING.TEAMS.TEAM_NO = TICKETING.SERVICE_DETAILS_TEAMS.TEAM_NO
                        WHERE TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NAME = '" . $selectedServiceDetailsName . "'";

    $TeamMemberName = oci_parse($conn, $ServiceDetailTeamMemberName);
    oci_execute($TeamMemberName);
    $options = '';
    while ($row = oci_fetch_assoc($TeamMemberName)) {
        $options .= "<option value='{$row['TEAM_NO']}'>{$row['TEAM_NAME']}</option>";
    }
    echo $options;
}


///////////////////////////////////////////***************** Ticket Transation Page Request Functions End  *************************/////////////////////////////////////////




////////////////////////////////****************************************** Add New Ticket Page Request Functions Start  ***********************************************///////////////////////////////


if (isset($_POST['type'])) {   // Choose Service Details Debends On Service Type
    $selectedService = $_POST['type'];  // Service Type Number

    // Query to fetch Service Details based on the selected Service Type
    $depUser = "SELECT * FROM TICKETING.SERVICE_DETAILS WHERE SERVICE_NO = " . $selectedService;
    $dept = oci_parse($conn, $depUser);
    oci_execute($dept);
    // Build HTML options for users
    $options = '';
    while ($row = oci_fetch_assoc($dept)) {
        $options .= "<option value='{$row['SERVICE_DETAIL_NO']}'>{$row['SERVICE_DETAIL_NAME']}</option>";
    }
    echo $options;
}

if (isset($_POST['username'])) {   // Choose Device Number Debends On Service Details
    $UserName = $_POST['username'];  // User Name

    // Query to fetch User EBS ID based on the User nMAE
    $empID = "SELECT EBS_EMPLOYEE_ID FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = :t_name";
    $emp = oci_parse($conn, $empID);
    // Bind the variables
    oci_bind_by_name($emp, ":t_name", $UserName);
    // Execute the query
    oci_execute($emp);
    $userEmpID = oci_fetch_assoc($emp);
    $userEBSID = $userEmpID['EBS_EMPLOYEE_ID'];

    // Query to fetch User EBS ID based on the User nMAE
    $deviceNo = "SELECT DEVICE_NO, CATEGORY  FROM CUSTODY.dev_spar_cust_v WHERE EMP_FILE_NO = :t_emp";
    $device = oci_parse($conn, $deviceNo);
    // Bind the variables
    oci_bind_by_name($device, ":t_emp", $userEBSID);
    // Execute the query
    oci_execute($device);

    // Build HTML options for users
    $options = '';
    while ($row = oci_fetch_assoc($device)) {
        $options .= "<option value='{$row['DEVICE_NO']}'>{$row['CATEGORY']}</option>";
    }
    echo $options;
}


////////////////////////////////****************************************** Add New Ticket Page Request Functions Start  ***********************************************///////////////////////////////






////////////////////////////////****************************************** Service & Service Details Page Request Functions Start  ***********************************************///////////////////////////////

if (isset($_POST['notassignedteam'])) {   // Retrive Not Selected Service Details Teams Debends On Service Details Number
    $notassignedteam = $_POST['notassignedteam'];  // Service Type Number

    // Query to retrieve a list of teams not associated with the service details
    $notAssignedTeams = "SELECT TEAM_NO, TEAM_NAME FROM TICKETING.TEAMS WHERE TEAM_NO NOT IN 
    (SELECT TEAM_NO FROM TICKETING.SERVICE_DETAILS_TEAMS WHERE SERVICE_DETAIL_NO = " . $notassignedteam . ")";

    $notAssignedTeams = oci_parse($conn, $notAssignedTeams);
    oci_execute($notAssignedTeams);
    // Build HTML options for users
    $options = '';
    while ($row = oci_fetch_assoc($notAssignedTeams)) {
        $options .= "<option value='{$row['TEAM_NO']}'>{$row['TEAM_NAME']}</option>";
    }
    echo $options;
}

if (isset($_POST['ServiceDetailsInformation'])) {   // Retrive Service Details Information For Update PopUP
    $selectedServiceDetailsID = $_POST['ServiceDetailsInformation'];  // Team Number

    // Query to fetch Team Information based on the selected Team Number
    $serviceDetailsInfo = "SELECT  SERVICE_DETAIL_NAME, DESCRIPTION FROM TICKETING.SERVICE_DETAILS WHERE SERVICE_DETAIL_NO =" . $selectedServiceDetailsID;
    $serviceDetails = oci_parse($conn, $serviceDetailsInfo);
    oci_execute($serviceDetails);
    // Build HTML options for users
    $result = oci_fetch_assoc($serviceDetails);
    echo json_encode($result);
}

if (isset($_POST['details'])) {   // retrive Service Details Information based on Service Number from DB
    $serviceNo = $_POST['details'];  // Service Number

    // Query to fetch Service Details based on the selected Service Number
    $serviceDetail = "SELECT 
                            TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO, SERVICE_DETAIL_NAME, DESCRIPTION, CUSTODY_LINK, PRIVATE_FLAG, TICKETING.SERVICE.SERVICE_NAME 
                        FROM 
                            TICKETING.SERVICE_DETAILS
                        JOIN 
                            TICKETING.SERVICE
                        ON
                            TICKETING.SERVICE.SERVICE_NO = TICKETING.SERVICE_DETAILS.SERVICE_NO
                        WHERE TICKETING.SERVICE_DETAILS.SERVICE_NO =" . $serviceNo;
    $details = oci_parse($conn, $serviceDetail);

    oci_execute($details);
    $data = array();
    while ($row = oci_fetch_assoc($details)) {
        $data[] = array(
            'id'                        => $row['SERVICE_DETAIL_NO'],
            'serviceTypeName'           => $row['SERVICE_NAME'],
            'name'                      => $row['SERVICE_DETAIL_NAME'],
            'desc'                      => $row['DESCRIPTION'],
            'custody'                   => $row['CUSTODY_LINK'],
            'private'                   => $row['PRIVATE_FLAG']
        );
    }
    echo json_encode($data);
}

if (isset($_POST['ServiceDetailsID'])) {   // Choose Service Details Team Name Debends On Service Type

    $ServiceDetailsID = $_POST['ServiceDetailsID'];

    // Query to fetch Team Name based on the selected Service Type
    $serviceTeam = "SELECT 
                        TICKETING.SERVICE_DETAILS_TEAMS.TEAM_NO, ENABLED, TICKETING.TEAMS.TEAM_NAME, TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NAME
                    FROM 
                        TICKETING.SERVICE_DETAILS_TEAMS
                    JOIN 
                        TICKETING.TEAMS
                    ON 
                        TICKETING.TEAMS.TEAM_NO = TICKETING.SERVICE_DETAILS_TEAMS.TEAM_NO
                    JOIN
                        TICKETING.SERVICE_DETAILS
                    ON
                        TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO = TICKETING.SERVICE_DETAILS_TEAMS.SERVICE_DETAIL_NO
                    WHERE 
                        TICKETING.SERVICE_DETAILS_TEAMS.SERVICE_DETAIL_NO =" . $ServiceDetailsID;
    $teamDetails = oci_parse($conn, $serviceTeam);
    oci_execute($teamDetails);
    $data = array();
    while ($row = oci_fetch_assoc($teamDetails)) {
        $data[] = array(
            'teamID'                        => $row['TEAM_NO'],
            'serviceDetailsID'              => $ServiceDetailsID,
            'serviceDetailName'             => $row['SERVICE_DETAIL_NAME'],
            'name'                          => $row['TEAM_NAME'],
            'enable'                        => $row['ENABLED']
        );
    }
    echo json_encode($data);
}

////////////////////////////////****************************************** Service & Service Details Page Request Functions Start  ***********************************************///////////////////////////////









////////////////////////////////****************************************** Team Member Page Request Functions Start  ***********************************************///////////////////////////////

if (isset($_POST['teamInfo'])) {   // Choose Team Information Debends On Team Number
    $selectedTeamID = $_POST['teamInfo'];  // Team Number

    // Query to fetch Team Information based on the selected Team Number
    $teamInfo = "SELECT TICKETING.TEAMS.ACTIVE AS TEAM_ACTIVE,
                        TICKETING.TEAMS.BRANCH_CODE,
                        TICKETING.TEAMS.DEPT_ID,
                        TICKETING.TEAMS.DESCRIPTION AS TEAM_DESCRIPTION,
                        TICKETING.xxajmi_ticket_user_info.EMP_DEPARTMENT
                    FROM    
                        TICKETING.TEAMS
                    JOIN
                        TICKETING.xxajmi_ticket_user_info
                    ON
                        TICKETING.xxajmi_ticket_user_info.COST_CENTER =  TICKETING.TEAMS.DEPT_ID
                    WHERE   TICKETING.TEAMS.TEAM_NO =" . $selectedTeamID;
    $team = oci_parse($conn, $teamInfo);
    oci_execute($team);
    // Build HTML options for users
    $result = oci_fetch_assoc($team);
    echo json_encode($result);
}

if (isset($_POST['teamMember'])) {   // Choose Team Member Debends On Team Number
    $selectedMember = $_POST['teamMember']; // Team Number

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
                        TICKETING.TEAM_MEMBERS.TEAM_NO =" . $selectedMember;
    $team = oci_parse($conn, $teamMember);
    oci_execute($team);
    $data = array();
    while ($row = oci_fetch_assoc($team)) {
        $data[] = array(
            'userName'      => $row['USERNAME'],
            'name'          => $row['USER_EN_NAME'],
            'description'   => $row['DESCRIPTION'],
            'active'        => $row['ACTIVE'],
            'supervisor'    => $row['ROLE_ID'],
            'manager'       => $row['ROLE_ID']
        );
    }
    echo json_encode($data);
}

if (isset($_POST['delegateTeamMember'])) {   // Display Delegated User Debends On Team Number In Team Member Section
    $delegateUser = $_POST['delegateTeamMember']; // Team Number
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
                            TICKETING.DELEGATED_TEM_SUPER.TEAM_NO =" . $delegateUser;
    $delegate = oci_parse($conn, $memberDelegated);
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

///////////////////////////////////**************************************** Team Member Page Request Functions End  ************************************************///////////////////////////////


/*********************************************************** End  ***********************************************************************/
/*
    ** Title Function That Echo The Page Title In Case The Page
    ** Has The Variable $pageTitle And Echo Default Title For Other Pages
*/

function getTitle()
{
    global $pageTitle;

    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'Ticketing System';
    }
}

/*
    ** Redirect Function That Redirect to the prives page 
    ** Has The Parmeter $url that reference to the next page 
*/

function redirect($url)
{
    header('Location:' . $url);
    exit();
}

/*
    ** Count Function That Echo The Numbers of  Ticket in differnt Status
    ** Has one Paramter $status to display number of rows for each status
*/

function getcount($status)
{

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

    // Select Tickets Based On User Permission and Ticket Status
    $allTickets = "SELECT 
                        *  
                    FROM 
                    TICKETING.TICKETS_TRANSACTIONS_SUB_V 
                    WHERE  TICKET_STATUS = :t_status ";
    $alltick = oci_parse($conn, $allTickets);
    oci_bind_by_name($alltick, ":t_status", $status);

    // Execute the query
    oci_execute($alltick);

    while ($row = oci_fetch_assoc($alltick)) {
        // Process each row
    }
    $allRows = oci_num_rows($alltick);
    return $allRows;
}

/*
    ** Insert Function That Insert User 
    Into global_temp_table Table and Return User 
    Permissions To Ticket Transation Table **
*/

function InsertUserID()
{

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

    // Select UserID Based on UserName To Insert It Into Table global_temp_table
    $userNamePre = "SELECT USER_ID FROM ACT_USERS_VW WHERE USERNAME = :t_name";
    $prevlag = oci_parse($conn, $userNamePre);
    oci_bind_by_name($prevlag, ":t_name", $_SESSION["user"]);
    oci_execute($prevlag);
    $prevlegs = oci_fetch_assoc($prevlag);
    $userIDPreResault = $prevlegs['USER_ID'];
    $userNamePreResault = 'USER_ID';

    // Insert UserID Into global_temp_table Table After Returned From User Table
    $ticketTransation = "INSERT INTO ticketing.global_temp_table (NAME, VALUE)  
                        VALUES (:t_user, :t_value)";
    $insertValue = oci_parse($conn, $ticketTransation);
    oci_bind_by_name($insertValue, ':t_user', $userNamePreResault);
    oci_bind_by_name($insertValue, ':t_value', $userIDPreResault);
    $run = oci_execute($insertValue, OCI_NO_AUTO_COMMIT);
}
