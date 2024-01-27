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

    if ($action == 'assignTicket') {

        $ticketNumber           = $_POST['ticketNumber'];
        $UserSessionID          = $_POST['UserSessionID'];
        $ticketWeight           = $_POST['ticketWeight'];
        $ticketPeriority        = $_POST['ticketPeriority'];
        $assignTeam             = $_POST['assignTeam'];
        $memberAssigned         = json_decode($_POST['memberAssigned'], true);
        $statusUpdate           = 20;

        $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_PERIORITY = " . $ticketPeriority . ", 
                            TICKET_STATUS = " . $statusUpdate . ", 
                            LAST_UPDATE_DATE = CURRENT_TIMESTAMP, 
                            LAST_UPDATED_BY = " . $UserSessionID . ", 
                            TICKET_WEIGHT = " . $ticketWeight . "
                            WHERE TICKET_NO = " . $ticketNumber;

        $status = oci_parse($conn, $statusTicket);
        $check = oci_execute($status);

        if ($check) {
            foreach ($memberAssigned as $row) {
                $userID = $row['userID'];
                $userName = $row['userName'];
                $name = $row['name'];
                $description = $row['description'];
                $teamLeader = $row['teamLeader'];

                $lastSequanceNo = "SELECT MAX(SEQUENCE_NUMBER) FROM  TICKETING.TICKET_ACTION_HISTORY
                                    WHERE TICKET_NO = " . $ticketNumber;
                $seqStatment = oci_parse($conn, $lastSequanceNo);
                oci_execute($seqStatment);
                $SeqResult = oci_fetch_assoc($seqStatment);
                $SeqNo   = ++$SeqResult['MAX(SEQUENCE_NUMBER)'];

                $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                        (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                        ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
                                                        LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                                VALUES ($ticketNumber, $SeqNo, $statusUpdate, 
                                                        CURRENT_TIMESTAMP, '$name' , $UserSessionID, CURRENT_TIMESTAMP,
                                                        $UserSessionID, CURRENT_TIMESTAMP)";
                $newHistory = oci_parse($conn, $addTicketHistory);
                $resault = oci_execute($newHistory);

                if ($resault) {

                    $addTicketTeamMember = "INSERT INTO TICKETING.TICKET_TEAM_MEMBERS 
                                        (TEAM_NO, TICKET_NO, TEAM_LEADER, TEAM_MEMBER, DESCRIPTION, 
                                        CREATED_BY, CREATION_DATE,
                                        LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                VALUES ($assignTeam, $ticketNumber, '$teamLeader', $userID,
                                        '$description', $UserSessionID, CURRENT_TIMESTAMP, 
                                        $UserSessionID, CURRENT_TIMESTAMP)";
                    $newTeamMembreAssigned = oci_parse($conn, $addTicketTeamMember);
                    $run = oci_execute($newTeamMembreAssigned);

                    if ($run) {
                        http_response_code(200);
                        echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
                    } else {
                        http_response_code(404); // Internal Server Error
                        echo json_encode(['status' => 'error', 'message' => oci_error($newTeamMembreAssigned)['message']]);
                    }
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(['status' => 'error', 'message' => oci_error($newHistory)['message']]);
                }
            }
        } else {
            http_response_code(400); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => oci_error($status)['message']]);
        }
    } elseif ($action == 'start') {  // Start Solving Ticket ( Change Ticket Status To Started ) 

        $ticketid       = $_POST['tickid'];
        $userID         = $_POST['UserSessionID'];
        $comments       = 'Ticket Started';
        $statusUpdate = 30;

        $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = " . $statusUpdate . ", LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            TICKET_START_DATE = CURRENT_TIMESTAMP, LAST_UPDATED_BY = " . $userID . "
                            WHERE TICKET_NO = " . $ticketid;

        $status = oci_parse($conn, $statusTicket);
        $run = oci_execute($status);

        if ($run) {

            $lastSequanceNo = "SELECT MAX(SEQUENCE_NUMBER) FROM  TICKETING.TICKET_ACTION_HISTORY
                                WHERE TICKET_NO = " . $ticketid;
            $seqStatment = oci_parse($conn, $lastSequanceNo);
            oci_execute($seqStatment);
            $SeqResult = oci_fetch_assoc($seqStatment);
            $SeqNo   = ++$SeqResult['MAX(SEQUENCE_NUMBER)'];

            $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                    (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                    ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                            VALUES ($ticketid, $SeqNo, $statusUpdate, 
                                                    CURRENT_TIMESTAMP, '$comments' , $userID, CURRENT_TIMESTAMP,
                                                    $userID, CURRENT_TIMESTAMP)";
            $newHistory = oci_parse($conn, $addTicketHistory);
            $resault = oci_execute($newHistory);

            if ($resault) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($newHistory)['message']]);
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => oci_error($status)['message']]);
        }
    } elseif ($action == 'solve') {  // Solved Ticket ( Change Ticket Status To Solved )

        $ticketid       = $_POST['tickid'];
        $issue          = $_POST['issue'];
        $resolution     = $_POST['resolution'];
        $userID         = $_POST['UserSessionID'];
        $comments       = 'Ticket Solved ';
        $statusUpdate = 60;

        $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = " . $statusUpdate . ", LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            LAST_UPDATED_BY = " . $userID . ", 
                            TECHNICAL_ISSUE_DESCRIPTION = '" . $issue . "', 
                            TECHNICAL_ISSUE_RESOLUTION = '" . $resolution . "'
                            WHERE TICKET_NO = " . $ticketid;
        $status = oci_parse($conn, $statusTicket);
        $run = oci_execute($status);

        if ($run) {
            $lastSequanceNo = "SELECT MAX(SEQUENCE_NUMBER) FROM  TICKETING.TICKET_ACTION_HISTORY
                                WHERE TICKET_NO = " . $ticketid;
            $seqStatment = oci_parse($conn, $lastSequanceNo);
            oci_execute($seqStatment);
            $SeqResult = oci_fetch_assoc($seqStatment);
            $SeqNo   = ++$SeqResult['MAX(SEQUENCE_NUMBER)'];

            $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                    (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                    ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                            VALUES ($ticketid, $SeqNo, $statusUpdate, 
                                                    CURRENT_TIMESTAMP, '$comments' ,  $userID, CURRENT_TIMESTAMP,
                                                    $userID, CURRENT_TIMESTAMP)";
            $newHistory = oci_parse($conn, $addTicketHistory);
            $resault = oci_execute($newHistory);

            if ($resault) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($newHistory)['message']]);
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => oci_error($status)['message']]);
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
    } elseif ($action == 'add') {       // Add New Ticket Function

        $userName       = $_POST['name'];       // user who create the ticket
        $serviceType    = $_POST['service'];
        $details        = $_POST['details'];
        $description    = $_POST['description'];
        $ticketStatus   = 10;                   // Ticket Status Static Because All Ticket Created With Status Initial (new) with code (10)
        $device         = $_POST['device'];  // its will be not empty if $details = custody  else then = null 

        if ($device === null) {
            $deviceValue = "null";  // Do not enclose NULL in quotes
        } else {
            $deviceValue = $device;  // Use the actual numeric value
        }

        // -- Increment the ID for the new Ticket
        $max        = "SELECT MAX(TICKET_NO) FROM TICKETING.TICKETS";
        $incrId     = oci_parse($conn, $max);
        oci_execute($incrId);
        $id         = oci_fetch_assoc($incrId);
        $ticketNO   = ++$id['MAX(TICKET_NO)'];

        // Query to fetch users Information based on User Name
        $userInfo   = "SELECT *  FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = '" . $userName . "'";
        $info       = oci_parse($conn, $userInfo);
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
                                            VALUES ($ticketNO, $serviceType, CURRENT_TIMESTAMP, 
                                                    '$deviceValue', $departmentNo, $ticketStatus,
                                                    '$description', $userID, CURRENT_TIMESTAMP, 
                                                    $userID, CURRENT_TIMESTAMP, $details, 
                                                    $ebsEmployee, '$userEnName', '$userName', 
                                                    $userID, '$email', $departmentNo,
                                                    '$empDepartment', '$branchCode', '$jopDesc')";
        $add = oci_parse($conn, $addTicket);
        $run = oci_execute($add);

        if ($run) {
            $sequenceNumber = 1;
            //  Query to Insert Ticket Information In Ticket Action History
            $addTicketAction = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                                    ACTION_DATE, COMMENTS, CREATED_BY,
                                                                    CREATION_DATE, LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                                            VALUES ($ticketNO, $sequenceNumber, $ticketStatus, 
                                                                    CURRENT_TIMESTAMP, '$description',
                                                                    $userID, CURRENT_TIMESTAMP, $userID, 
                                                                    CURRENT_TIMESTAMP)";
            $action = oci_parse($conn, $addTicketAction);
            $resualt = oci_execute($action);

            if ($resualt) {
                echo $ticketNO;
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($action)['message']]);
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => oci_error($add)['message']]);
        }
    } elseif ($action == 'complete') {  // Confirm Ticket ( Change Ticket Status To Confirm )

        $ticketid       = $_POST['tickid'];
        $userID         = $_POST['UserSessionID'];
        $statusUpdate   = 40;

        $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = " . $statusUpdate . ", LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            TICKET_START_DATE = CURRENT_TIMESTAMP, LAST_UPDATED_BY = " . $userID . "
                            WHERE TICKET_NO = " . $ticketid;

        $status = oci_parse($conn, $statusTicket);
        $run = oci_execute($status);

        if ($run) {

            $lastSequanceNo = "SELECT MAX(SEQUENCE_NUMBER) FROM  TICKETING.TICKET_ACTION_HISTORY
                                WHERE TICKET_NO = " . $ticketid;
            $seqStatment = oci_parse($conn, $lastSequanceNo);
            oci_execute($seqStatment);
            $SeqResult = oci_fetch_assoc($seqStatment);
            $SeqNo   = ++$SeqResult['MAX(SEQUENCE_NUMBER)'];

            $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                    (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                    ACTION_DATE, CREATED_BY, CREATION_DATE,
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                            VALUES ($ticketid, $SeqNo, $statusUpdate, 
                                                    CURRENT_TIMESTAMP, $userID, CURRENT_TIMESTAMP,
                                                    $userID, CURRENT_TIMESTAMP)";
            $newHistory = oci_parse($conn, $addTicketHistory);
            $resault = oci_execute($newHistory);

            if ($resault) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($newHistory)['message']]);
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => oci_error($status)['message']]);
        }
    } elseif ($action == 'cancel') {    // Cancel Ticket ( Change Ticket Status To Canceled )

        $ticketid       = $_POST['tickid'];
        $userID         = $_POST['UserSessionID'];
        $comments       = 'Ticket Canceled';
        $statusUpdate   = 70;

        $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = " . $statusUpdate . ", LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            TICKET_START_DATE = CURRENT_TIMESTAMP, LAST_UPDATED_BY = " . $userID . "
                            WHERE TICKET_NO = " . $ticketid;

        $status = oci_parse($conn, $statusTicket);
        $run = oci_execute($status);

        if ($run) {

            $lastSequanceNo = "SELECT MAX(SEQUENCE_NUMBER) FROM  TICKETING.TICKET_ACTION_HISTORY
                                WHERE TICKET_NO = " . $ticketid;
            $seqStatment = oci_parse($conn, $lastSequanceNo);
            oci_execute($seqStatment);
            $SeqResult = oci_fetch_assoc($seqStatment);
            $SeqNo   = ++$SeqResult['MAX(SEQUENCE_NUMBER)'];

            $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                    (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                    ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                            VALUES ($ticketid, $SeqNo, $statusUpdate, 
                                                    CURRENT_TIMESTAMP, '$comments' , $userID, CURRENT_TIMESTAMP,
                                                    $userID, CURRENT_TIMESTAMP)";
            $newHistory = oci_parse($conn, $addTicketHistory);
            $resault = oci_execute($newHistory);
            if ($resault) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($newHistory)['message']]);
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => oci_error($status)['message']]);
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
    } elseif ($action == 'updateTeamMemberTable') {         // Update Manager And Supervisor And Active Columns In Team Member Table Function

        $userID                 = $_POST['userID'];

        if (!empty($_POST['activeColumnJson'])) {
            $activeColumnJson       = json_decode($_POST['activeColumnJson'], true);
            foreach ($activeColumnJson as $row) {
                $TeamMemberNo                   = $row['TeamMemberNo'];
                $newStatus                      = $row['newStatus'];

                $TeamMemberActive = "UPDATE TICKETING.TEAM_MEMBERS SET 
                                    ACTIVE ='" . $newStatus . "', LAST_UPDATED_BY = " . $userID . ",
                                    LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE TEAM_MEMBER_USER_ID = " . $TeamMemberNo;
                $activeStatus = oci_parse($conn, $TeamMemberActive);
                $run = oci_execute($activeStatus);

                if (!$run) {
                    $e = oci_error($activeStatus);
                    echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
                }
            }
        }

        if (!empty($_POST['supervisorColumnJson'])) {
            $supervisorColumnJson   = json_decode($_POST['supervisorColumnJson'], true);
            foreach ($supervisorColumnJson as $row) {
                $TeamMemberNo                   = $row['TeamMemberNo'];
                $newStatus                      = $row['newStatus'];

                if ($newStatus  == 'Y') {
                    $newStatus = 3;
                    $TeamMemberSupervisor = "UPDATE TICKETING.TKT_REL_ROLE_USERS SET 
                                    ROLE_ID =" . $newStatus . ", LAST_UPDATED_BY = " . $userID . ",
                                    LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE USER_ID = " . $TeamMemberNo;
                    $supervisorStatus = oci_parse($conn, $TeamMemberSupervisor);
                    $run = oci_execute($supervisorStatus);

                    if (!$run) {
                        http_response_code(500); // Internal Server Error
                        echo json_encode(['status' => 'error', 'message' => oci_error($supervisorStatus)['message']]);
                        exit;
                    }
                } else {
                    $newStatus = 4;
                    $TeamMemberSupervisor = "UPDATE TICKETING.TKT_REL_ROLE_USERS SET 
                                            ROLE_ID =" . $newStatus . ", LAST_UPDATED_BY = " . $userID . ",
                                            LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE USER_ID = " . $TeamMemberNo;
                    $supervisorStatus = oci_parse($conn, $TeamMemberSupervisor);
                    $run = oci_execute($supervisorStatus);

                    if (!$run) {
                        http_response_code(500); // Internal Server Error
                        echo json_encode(['status' => 'error', 'message' => oci_error($supervisorStatus)['message']]);
                        exit;
                    }
                }
            }
        }

        if (!empty($_POST['managerColumnJson'])) {
            $managerColumnJson      = json_decode($_POST['managerColumnJson'], true);
            foreach ($managerColumnJson as $row) {
                $TeamMemberNo                   = $row['TeamMemberNo'];
                $newStatus                      = $row['newStatus'];

                if ($newStatus  == 'Y') {
                    $newStatus = 1;
                    $TeamMemberManager = "UPDATE TICKETING.TKT_REL_ROLE_USERS SET 
                                                ROLE_ID =" . $newStatus . ", LAST_UPDATED_BY = " . $userID . ",
                                                LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE USER_ID = " . $TeamMemberNo;
                    $managerStatus = oci_parse($conn, $TeamMemberManager);
                    $run = oci_execute($managerStatus);

                    if (!$run) {
                        http_response_code(500); // Internal Server Error
                        echo json_encode(['status' => 'error', 'message' => oci_error($managerStatus)['message']]);
                        exit;
                    }
                } else {
                    $newStatus = 4;
                    $TeamMemberManager = "UPDATE TICKETING.TKT_REL_ROLE_USERS SET 
                                                ROLE_ID =" . $newStatus . ", LAST_UPDATED_BY = " . $userID . ",
                                                LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE USER_ID = " . $TeamMemberNo;
                    $managerStatus = oci_parse($conn, $TeamMemberManager);
                    $run = oci_execute($managerStatus);

                    if (!$run) {
                        http_response_code(500); // Internal Server Error
                        echo json_encode(['status' => 'error', 'message' => oci_error($managerStatus)['message']]);
                        exit;
                    }
                }
            }
        }

        // Send a success response with HTTP status 200
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
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
                        TICKETING.TEAM_MEMBERS.ACTIVE, TICKETING.xxajmi_ticket_user_info.USERNAME, USER_EN_NAME,USER_ID
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
            'ID'            => $row['USER_ID'],
            'name'          => $row['USERNAME'],
            'Ename'         => $row['USER_EN_NAME'],
            'active'        => $row['ACTIVE']
        );
    }
    echo json_encode($data);
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


    // Query to fetch Service Details based on the selected Service Type
    $ticketWeight = "SELECT CODE, MEANING From TICKETING.LOOKUP_VALUES WHERE lookup_type_id =" . 8;

    $weight = oci_parse($conn, $ticketWeight);
    oci_execute($weight);
    $weights = '';
    while ($row = oci_fetch_assoc($weight)) {
        $weights .= "<option value='{$row['CODE']}'>{$row['MEANING']}</option>";
    }

    // Query to fetch Service Details based on the selected Service Type
    $ticketPeriority = "SELECT CODE, MEANING From TICKETING.LOOKUP_VALUES WHERE lookup_type_id =" . 4;

    $periority = oci_parse($conn, $ticketPeriority);
    oci_execute($periority);
    $perioritys = '';
    while ($row = oci_fetch_assoc($periority)) {
        $perioritys .= "<option value='{$row['CODE']}'>{$row['MEANING']}</option>";
    }


    $response = array(
        'weights' => '<option value="0" selected>Select Weight...</option>' . $weights,
        'teams' => '<option value="0" >Select Team</option>' . $options,
        'priorities' => '<option value="0" selected>Select Priority...</option>' . $perioritys,
    );

    // Return the JSON-encoded response
    echo json_encode($response);
    exit(); // Exit to prevent further output
}

if (isset($_POST['actionHistory'])) {   // retrive Service Details Information based on Service Number from DB
    $ticketNumber = $_POST['actionHistory'];  // Service Number
    $lookupTypeID =  1;

    // Query to fetch Service Details based on the selected Service Number

    $actionHistory = "SELECT 
                        TICKETING.TICKET_ACTION_HISTORY.SEQUENCE_NUMBER, 
                        TICKETING.TICKET_ACTION_HISTORY.ACTION_CODE, 
                        TICKETING.TICKET_ACTION_HISTORY.ACTION_DATE, 
                        TICKETING.TICKET_ACTION_HISTORY.COMMENTS, 
                        TICKETING.TICKET_ACTION_HISTORY.CREATED_BY , 
                        TICKETING.LOOKUP_VALUES.CODE,
                        TICKETING.LOOKUP_VALUES.MEANING,
                        TICKETING.xxajmi_ticket_user_info.USERNAME
                        FROM TICKETING.TICKET_ACTION_HISTORY 
                        JOIN
                        TICKETING.xxajmi_ticket_user_info
                        ON
                        TICKETING.xxajmi_ticket_user_info.USER_ID = TICKETING.TICKET_ACTION_HISTORY.CREATED_BY
                        JOIN
                        TICKETING.LOOKUP_VALUES
                        ON
                        TICKETING.LOOKUP_VALUES.CODE = TICKETING.TICKET_ACTION_HISTORY.ACTION_CODE
                        WHERE TICKETING.TICKET_ACTION_HISTORY.TICKET_NO = " . $ticketNumber . "
                        AND TICKETING.LOOKUP_VALUES.lookup_type_id = " . $lookupTypeID;
    $action = oci_parse($conn, $actionHistory);

    oci_execute($action);

    $data = array();
    while ($row = oci_fetch_assoc($action)) {
        $data[] = array(
            'SEQUENCE_NUMBER'       => $row['SEQUENCE_NUMBER'],
            'ACTION_CODE'           => $row['MEANING'],
            'ACTION_DATE'           => $row['ACTION_DATE'],
            'COMMENTS'              => $row['COMMENTS'],
            'CREATED_BY'            => $row['USERNAME']
        );
    }
    echo json_encode($data);
}

if (isset($_POST['EditServiceType'])) {   // Retrive  Service Detail Name In Update Ticket Imformation Popup
    $EditServiceType = $_POST['EditServiceType'];  // Service Type Number
    $selectedServiceDetailsName = $_POST['EditServiceDetails'];  // Service Type Number

    // Query to fetch Service Details based on the selected Service Type
    $ServiceDetailName = "SELECT
                                TICKETING.SERVICE.SERVICE_NO,
                                TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO,
                                SERVICE_DETAIL_NAME
                            FROM
                                TICKETING.SERVICE
                            JOIN
                                TICKETING.SERVICE_DETAILS ON TICKETING.SERVICE_DETAILS.SERVICE_NO = TICKETING.SERVICE.SERVICE_NO
                            WHERE
                                TICKETING.SERVICE.SERVICE_NAME = '" . $EditServiceType . "'
                                AND TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NAME NOT IN ('" . $selectedServiceDetailsName . "')";

    $EditServiceDetailsName = oci_parse($conn, $ServiceDetailName);
    oci_execute($EditServiceDetailsName);
    $options = '';
    while ($row = oci_fetch_assoc($EditServiceDetailsName)) {
        $options .= "<option value='{$row['SERVICE_DETAIL_NO']}'>{$row['SERVICE_DETAIL_NAME']}</option>";
    }
    echo $options;
}

if (isset($_POST['UpdateTicketInformationButton'])) {   // Update Ticket Information

    $EditTicketNumber = $_POST['UpdateTicketInformationButton'];  // Service Type Number
    $selectedServiceDetailsName = $_POST['EditServiceDetails'];  // Service Type Number

    // Query to fetch Service Details based on the selected Service Type
    $ServiceDetailName = "SELECT SERVICE_DETAIL_NO
                            FROM TICKETING.SERVICE_DETAILS
                            WHERE SERVICE_DETAIL_NAME = '" . $selectedServiceDetailsName  . "'";

    $EditTicketInformation = oci_parse($conn, $ServiceDetailName);
    oci_execute($EditTicketInformation);

    $row = oci_fetch_assoc($EditTicketInformation);
    $ServiceNo = $row["SERVICE_DETAIL_NO"];

    // Query to fetch Service Details based on the selected Service Type
    $UpdateTicketInformation = "UPDATE TICKETING.TICKETS SET SERVICE_DETAIL_NO = " . $ServiceNo . " WHERE TICKET_NO = " . $EditTicketNumber;

    $NewTicketInformation = oci_parse($conn, $UpdateTicketInformation);
    $run = oci_execute($NewTicketInformation);

    if ($run) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => oci_error($NewTicketInformation)['message']]);
    }
}

// if (isset($_POST['includeMember'])) {               // Start Solving Ticket ( Change Ticket Status To Started ) 

//     $ticketid       = $_POST['includeMember'];
//     $userID         = $_POST['UserSessionID'];
//     $description    = $_POST['UserAssigned'];
//     $statusUpdate   = 100;

//     $lastSequanceNo = "SELECT MAX(SEQUENCE_NUMBER) FROM  TICKETING.TICKET_ACTION_HISTORY
//                             WHERE TICKET_NO = " . $ticketid;
//     $seqStatment = oci_parse($conn, $lastSequanceNo);
//     oci_execute($seqStatment);
//     $SeqResult = oci_fetch_assoc($seqStatment);
//     $SeqNo   = ++$SeqResult['MAX(SEQUENCE_NUMBER)'];

//     $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
//                                                 (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
//                                                 ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
//                                                 LAST_UPDATED_BY, LAST_UPDATE_DATE) 
//                                         VALUES ($ticketid, $SeqNo, $statusUpdate, 
//                                                 CURRENT_TIMESTAMP, '$description' , $userID, CURRENT_TIMESTAMP,
//                                                 $userID, CURRENT_TIMESTAMP)";
//     $newHistory = oci_parse($conn, $addTicketHistory);
//     $resault = oci_execute($newHistory);

//     if ($resault) {
//         http_response_code(200);
//         echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
//     } else {
//         http_response_code(500); // Internal Server Error
//         echo json_encode(['status' => 'error', 'message' => oci_error($newHistory)['message']]);
//     }
// }

// if (isset($_POST['excludeMember'])) {               // Start Solving Ticket ( Change Ticket Status To Started ) 

//     $ticketid       = $_POST['excludeMember'];
//     $userID         = $_POST['UserSessionID'];
//     $description    = $_POST['UserAssigned'];
//     $statusUpdate   = 90;

//     $lastSequanceNo = "SELECT MAX(SEQUENCE_NUMBER) FROM  TICKETING.TICKET_ACTION_HISTORY
//                             WHERE TICKET_NO = " . $ticketid;
//     $seqStatment = oci_parse($conn, $lastSequanceNo);
//     oci_execute($seqStatment);
//     $SeqResult = oci_fetch_assoc($seqStatment);
//     $SeqNo   = ++$SeqResult['MAX(SEQUENCE_NUMBER)'];

//     $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
//                                                 (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
//                                                 ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
//                                                 LAST_UPDATED_BY, LAST_UPDATE_DATE) 
//                                         VALUES ($ticketid, $SeqNo, $statusUpdate, 
//                                                 CURRENT_TIMESTAMP, '$description' ,  $userID, CURRENT_TIMESTAMP,
//                                                 $userID, CURRENT_TIMESTAMP)";
//     $newHistory = oci_parse($conn, $addTicketHistory);
//     $resault = oci_execute($newHistory);

//     if ($resault) {
//         http_response_code(200);
//         echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
//     } else {
//         http_response_code(500); // Internal Server Error
//         echo json_encode(['status' => 'error', 'message' => oci_error($newHistory)['message']]);
//     }
// }

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
                        TICKETING.xxajmi_ticket_user_info.USERNAME,USER_EN_NAME, 
                        TICKETING.TKT_REL_ROLE_USERS.ROLE_ID, STATUS
                    FROM 
                        TICKETING.TEAM_MEMBERS
                    JOIN
                        TICKETING.xxajmi_ticket_user_info
                    ON 
                        TICKETING.xxajmi_ticket_user_info.USER_ID = TICKETING.TEAM_MEMBERS.TEAM_MEMBER_USER_ID
                    JOIN 
                        TICKETING.TKT_REL_ROLE_USERS
                    ON
                        TICKETING.TKT_REL_ROLE_USERS.USER_ID = TICKETING.xxajmi_ticket_user_info.USER_ID
                    WHERE
                        TICKETING.TEAM_MEMBERS.TEAM_NO =" . $selectedMember;
    $team = oci_parse($conn, $teamMember);
    oci_execute($team);
    $data = array();
    while ($row = oci_fetch_assoc($team)) {
        $data[] = array(
            'userID'        => $row['TEAM_MEMBER_USER_ID'],
            'userName'      => $row['USERNAME'],
            'name'          => $row['USER_EN_NAME'],
            'description'   => $row['DESCRIPTION'],
            'active'        => $row['ACTIVE'],
            'supervisor'    => $row['ROLE_ID'],
            'manager'       => $row['ROLE_ID'],
            'STATUS'        => $row['STATUS'],
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

if (isset($_POST['GetMember'])) {   // Choose Team Member Based On Team Number In Assign Page
    $DepartmentNumber = $_POST['GetMember'];  // Team Number
    $TeamNumber = $_POST['GetTeam'];  // Team Number

    // Query to fetch Team Member based on the selected Team Number
    $UsersDepartmentID = "SELECT USER_EN_NAME, USER_ID FROM TICKETING.xxajmi_ticket_user_info  WHERE USER_ID NOT IN 
                        (SELECT TEAM_MEMBER_USER_ID FROM TICKETING.TEAM_MEMBERS WHERE TEAM_NO = " . $TeamNumber . ") AND COST_CENTER = " . $DepartmentNumber;
    $member = oci_parse($conn, $UsersDepartmentID);

    oci_execute($member);
    $options = '';
    while ($row = oci_fetch_assoc($member)) {
        $options .= "<option value='{$row['USER_ID']}'>{$row['USER_EN_NAME']}</option>";
    }
    echo $options;
}

if (isset($_POST['GetTeamID'])) {   // Add New Team Member To The Team Based On Team Number And Department ID

    $GetTeamID = $_POST['GetTeamID'];  // Team Number
    $UserSessionID = $_POST['UserSessionID'];
    $GetMemberName = $_POST['GetMemberName'];
    $GetMemberDeacription = $_POST['GetMemberDeacription'];
    $active = 'Y';

    // Query to fetch Last Team Member No To Create The Next ID
    $lastTeamMemberNo = "SELECT MAX(TEAM_MEMBER_NO) FROM TICKETING.TEAM_MEMBERS";
    $TeamMemberNo     = oci_parse($conn, $lastTeamMemberNo);
    oci_execute($TeamMemberNo);
    $result        = oci_fetch_array($TeamMemberNo);
    $NewTeamMemberNo  = ++$result['MAX(TEAM_MEMBER_NO)'];

    $TeamMember = "INSERT INTO TICKETING.TEAM_MEMBERS (TEAM_MEMBER_NO, TEAM_MEMBER_USER_ID, ACTIVE, 
                                                        DESCRIPTION, TEAM_NO,  CREATED_BY,  CREATION_DATE)
                                                VALUES ($NewTeamMemberNo, $GetMemberName , '$active', 
                                                '$GetMemberDeacription', $GetTeamID, $UserSessionID, CURRENT_TIMESTAMP)";
    $AddNewTeamMember = oci_parse($conn, $TeamMember);
    $run = oci_execute($AddNewTeamMember);

    if ($run) {
        http_response_code(200); // Internal Server Error
        echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => oci_error($action)['message']]);
    }
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
                    TICKETING.TICKETS_TRANSACTIONS_V
                    WHERE  TICKET_STATUS = " . $status;
    $alltick = oci_parse($conn, $allTickets);
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
    $userNamePre = "SELECT USER_ID FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = :t_name";
    $prevlag = oci_parse($conn, $userNamePre);
    oci_bind_by_name($prevlag, ":t_name", $_SESSION["user"]);
    oci_execute($prevlag);
    $prevlegs = oci_fetch_assoc($prevlag);
    $userIDPreResault = $prevlegs['USER_ID'];
    $userNamePreResault = 'USER_ID';

    // Insert UserID Into global_temp_table Table After Returned From User Table
    $ticketTransation = "INSERT INTO ticketing.global_temp_table (NAME, VALUE)  
                        VALUES ('$userNamePreResault', $userIDPreResault)";
    $insertValue = oci_parse($conn, $ticketTransation);
    $run = oci_execute($insertValue);
}
