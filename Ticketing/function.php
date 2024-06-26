<?php

//   ********************  This Page Contain All Function and Ajax Function ***************************

include 'DBConnection.php';


///////////////////////////////////////////***************** Ticket Transation Page Request Functions Start  *************************/////////////////////////////////////////

if (isset($_POST['action'])) {   // All Ticket Transaction Ajax Requeset

    $action = $_POST['action'];

    if ($action == 'TicketTransactionFilter') {                        // Fetch All Ticket From  DB Based On User ID and Ticket Status

        try {

            $userNamePreResault         =  $_POST['userNamePreResault']; // User Who Logged In 
            $TicketTransactionSessionID           = $_POST['TicketTransactionSessionID'];
            $Filter                     = $_POST['Filter']; // In this Case Its Equal 0
            $order                      = !empty($_POST['order']) ? $_POST['order'] :  'TICKET_NO';
            $sortOrder                  = $_POST['sortOrder'];

            // Insert UserID Into global_temp_table Table After Returned From User Table
            $ticketTransation = "INSERT INTO ticketing.global_temp_table (NAME, VALUE)  
                            VALUES ('$userNamePreResault', $TicketTransactionSessionID)";
            $insertValue = oci_parse($conn, $ticketTransation);
            $run = oci_execute($insertValue);

            // $getActionDate = "SELECT TICKET_NO, TICKET_STATUS, ACTION_DATE FROM TICKETING.TICKETS";
            // $actionDateForCalTimeUpdate = oci_parse($conn, $getActionDate);
            // oci_execute($actionDateForCalTimeUpdate);

            // while ($actionDate = oci_fetch_assoc($actionDateForCalTimeUpdate)) {
            //     if ($actionDate['ACTION_DATE'] !== null) {
            //         $actionDateData = json_decode($actionDate['ACTION_DATE'], true);

            //         // Check if the key "Confirmed By User" exists and if its value is in the expected format
            //         if (isset($actionDateData['Confirmed By User'])) {
            //             $lastValue = DateTime::createFromFormat('d/m/y H:i:s', $actionDateData['Confirmed By User']);
            //         } else {
            //             // If the key doesn't exist or the value is not in the expected format, set $dateOne to null
            //             $lastValue =  new DateTime();
            //         }

            //         // // Create a DateTime object from the first value
            //         $firstDateTime = DateTime::createFromFormat('d/m/y H:i:s', $actionDateData['Creation Date']);
            //         // // Calculate the difference between the two DateTime objects
            //         $interval = $firstDateTime->diff($lastValue);

            //         $DaysDifference = $interval->format('%a');
            //         $HoursDifference = $interval->format('%h');
            //         $MinDifference = $interval->format('%i');
            //         $SecDifference = $interval->format('%s');

            //         $difference = $DaysDifference . " Day " . $HoursDifference .  " Hours " .  $MinDifference . " Minutes " . $SecDifference . " Sec ";

            //         // // Update the TOTAL_TIME column for this specific row
            //         $updateActionDate = "UPDATE TICKETING.TICKETS SET TOTAL_TIME = '$difference' WHERE TICKET_NO = " . $actionDate['TICKET_NO'];
            //         $up = oci_parse($conn, $updateActionDate);

            //         oci_execute($up);
            //     }
            // }
            if ($run) {
                // Fetch Ticket From  DB Based On User ID And Ticket Status

                $allTicket = "SELECT 
                        TICKET_NO, 
                        SERVICE_TYPE, 
                        SERVICE_DETAIL, 
                        TICKET_PERIORITY_MEANING, 
                        TICKET_STATUS, 
                        REQUEST_TYPE_NO, 
                        SERVICE_DETAIL_NO, 
                        TICKET_PERIORITY,
                        ISSUE_DESCRIPTION, 
                        TECHNICAL_ISSUE_DESCRIPTION, 
                        TECHNICAL_ISSUE_RESOLUTION,
                        USERNAME, 
                        DEPARTMENT_NAME, 
                        TICKET_START_DATE, 
                        BRANCH_CODE,  
                        ASSIGNED_TO ,
                        TICKET_END_DATE,  
                        TTOTAL_TIME, 
                        TOTAL_TIME,
                        TICKET_STATUS_MEANING, 
                        USER_EN_NAME,
                        EMAIL,
                        EMP_DEPARTMENT,
                        RESPONSE_TIME,
                        TECHNICIAN_ATTITUDE,
                        SERVICE_EVALUATION,
                        REQUESTOR_COMMENTS,
                        EVALUATION_FLAG
                    FROM 
                        TICKETING.TICKETS_TRANSACTIONS_V
                        WHERE TICKET_STATUS = " . $Filter . "
                        ORDER BY $order $sortOrder ";
                $all = oci_parse($conn, $allTicket);
                // Execute the query
                $resault = oci_execute($all);

                $data = array();
                while ($row = oci_fetch_assoc($all)) {
                    $data[] = array(
                        'TICKET_NO'                     => $row['TICKET_NO'],
                        'SERVICE_TYPE'                  => $row['SERVICE_TYPE'],
                        'SERVICE_DETAIL'                => $row['SERVICE_DETAIL'],
                        'TICKET_PERIORITY_MEANING'      => $row['TICKET_PERIORITY_MEANING'],
                        'TICKET_STATUS'                 => $row['TICKET_STATUS'],
                        'REQUEST_TYPE_NO'               => $row['REQUEST_TYPE_NO'],
                        'SERVICE_DETAIL_NO'             => $row['SERVICE_DETAIL_NO'],
                        'TICKET_PERIORITY'              => $row['TICKET_PERIORITY'],
                        'ISSUE_DESCRIPTION'             => $row['ISSUE_DESCRIPTION'],
                        'TECHNICAL_ISSUE_DESCRIPTION'   => $row['TECHNICAL_ISSUE_DESCRIPTION'],
                        'TECHNICAL_ISSUE_RESOLUTION'    => $row['TECHNICAL_ISSUE_RESOLUTION'],
                        'USERNAME'                      => $row['USERNAME'],
                        'DEPARTMENT_NAME'               => $row['DEPARTMENT_NAME'],
                        'TICKET_START_DATE'             => $row['TICKET_START_DATE'],
                        'BRANCH_CODE'                   => $row['BRANCH_CODE'],
                        'ASSIGNED_TO'                   => $row['ASSIGNED_TO'],
                        'TICKET_END_DATE'               => $row['TICKET_END_DATE'],
                        'TTOTAL_TIME'                   => $row['TTOTAL_TIME'],
                        'TOTAL_TIME'                    => $row['TOTAL_TIME'],
                        'TICKET_STATUS_MEANING'         => $row['TICKET_STATUS_MEANING'],
                        'USER_EN_NAME'                  => $row['USER_EN_NAME'],
                        'EMAIL'                         => $row['EMAIL'],
                        'EMP_DEPARTMENT'                => $row['EMP_DEPARTMENT'],
                        'RESPONSE_TIME'                 => $row['RESPONSE_TIME'],
                        'TECHNICIAN_ATTITUDE'           => $row['TECHNICIAN_ATTITUDE'],
                        'SERVICE_EVALUATION'            => $row['SERVICE_EVALUATION'],
                        'REQUESTOR_COMMENTS'            => $row['REQUESTOR_COMMENTS'],
                        'EVALUATION_FLAG'               => $row['EVALUATION_FLAG']
                    );
                }


                if ($resault) {
                    echo json_encode($data);
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($all)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                echo 'empty';
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'assignTicket') {                      // Assign Ticket To The Team Member Function

        $ticketNumber                       = $_POST['ticketNumber'];       // Ticket Number
        $TicketTransactionSessionID          = $_POST['TicketTransactionSessionID'];      // User Who Asssign The Ticket
        $assignTeam                          = $_POST['assignTeam'];         // Team Assigned Number
        $memberAssigned                     = json_decode($_POST['memberAssigned'], true);  // Member Team Assigned
        $statusUpdate                        = 20; // Ticket  Status Update (Assigned)

        // Ticket Weight
        if ($_POST['ticketWeight'] == 0) {
            $ticketWeight           = NULL;
        } else {
            $ticketWeight           = $_POST['ticketWeight'];
        }

        if ($_POST['ticketPeriority'] == 0) {
            $ticketPeriority = NULL;
        } else {
            $ticketPeriority        = $_POST['ticketPeriority'];    // Ticket Periority
        }

        try {
            // Update Ticket Status In Ticket Table
            $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_PERIORITY = '" . $ticketPeriority . "', 
                            TICKET_STATUS = " . $statusUpdate . ", 
                            LAST_UPDATE_DATE = CURRENT_TIMESTAMP, 
                            LAST_UPDATED_BY = " . $TicketTransactionSessionID . ", 
                            TICKET_WEIGHT = '" . $ticketWeight . "'
                            WHERE TICKET_NO = " . $ticketNumber;

            $status = oci_parse($conn, $statusTicket);
            $check = oci_execute($status);

            if ($check) {
                foreach ($memberAssigned as $row) {
                    $userID = $row['userID'];
                    $userName = $row['userName'];
                    $description = $row['description'];
                    $teamLeader = $row['teamLeader'];
                    $UN = "Assigned To: " . $userName;

                    // Insert This Action (Assign) To The Action History Table
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
                                    CURRENT_TIMESTAMP, '$UN' , $TicketTransactionSessionID, CURRENT_TIMESTAMP,
                                    $TicketTransactionSessionID, CURRENT_TIMESTAMP)";
                    $newHistory = oci_parse($conn, $addTicketHistory);
                    $resault = oci_execute($newHistory);

                    if ($resault) {

                        // Insert This Action (Assign) To The Ticket Team Member Table
                        $addTicketTeamMember = "INSERT INTO TICKETING.TICKET_TEAM_MEMBERS 
                                            (TEAM_NO, TICKET_NO, TEAM_LEADER, TEAM_MEMBER, DESCRIPTION, 
                                            CREATED_BY, CREATION_DATE) 
                                            VALUES ($assignTeam, $ticketNumber, '$teamLeader', $userID,
                                            '$description', $TicketTransactionSessionID, CURRENT_TIMESTAMP)";
                        $newTeamMembreAssigned = oci_parse($conn, $addTicketTeamMember);
                        $run = oci_execute($newTeamMembreAssigned);

                        if ($run) {
                            actionDate('Assigned By Supervisor', date("d/m/y H:i:s"), $ticketNumber, 'Creation Date', 'Assigned By Supervisor After');
                            // CalTime(  $ticketNumber, );
                            http_response_code(200);
                            echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
                        } else {
                            http_response_code(500); // Internal Server Error
                            $errorMessage = oci_error($newTeamMembreAssigned)['message'];
                            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                            // Optionally, you can log the error message for debugging purposes
                            error_log("Error occurred: $errorMessage");
                        }
                    } else {
                        http_response_code(500); // Internal Server Error
                        $errorMessage = oci_error($newHistory)['message'];
                        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                        // Optionally, you can log the error message for debugging purposes
                        error_log("Error occurred: $errorMessage");
                    }
                }
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($status)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($statusTicket)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($action == 'assignTicketChange') {                // Change Assign Team Ticket To The New Team Member Function

        $ticketNumber           = $_POST['ticketNumber'];           // Ticket Number
        $TicketTransactionSessionID          = $_POST['TicketTransactionSessionID'];          // User Who Assign The Ticket 

        $assignTeam             = $_POST['assignTeamChange'];       // Team Assigned Number
        $memberAssigned         = json_decode($_POST['memberAssignedChange'], true); // Member Team Assigned
        $statusUpdate           = 20;       // Ticket  Status Update (Assigned)

        if ($_POST['ticketWeightChange'] == 0 || $_POST['ticketWeightChange'] == 'null') {
            $ticketWeight           = NULL;     // Ticket Weight
        } else {
            $ticketWeight           = $_POST['ticketWeightChange'];
        }

        if ($_POST['ticketPeriorityChange'] == 0 || $_POST['ticketPeriorityChange'] == 'null') {
            $ticketPeriority        = NULL;  // Ticket Periority
        } else {
            $ticketPeriority        = $_POST['ticketPeriorityChange'];
        }

        try {

            // Update Ticket Status In Ticket Table
            $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_PERIORITY = '" . $ticketPeriority . "', 
                            LAST_UPDATE_DATE = CURRENT_TIMESTAMP, 
                            LAST_UPDATED_BY = " . $TicketTransactionSessionID . ", 
                            TICKET_WEIGHT = '" . $ticketWeight . "'
                            WHERE TICKET_NO = " . $ticketNumber;

            $status = oci_parse($conn, $statusTicket);
            $check = oci_execute($status);


            if ($check) {
                // Delete Old Team Assign From Ticket Team Member
                $delete = "DELETE FROM TICKETING.TICKET_TEAM_MEMBERS WHERE TICKET_NO = " . $ticketNumber;
                $deleteAssign = oci_parse($conn, $delete);
                $resultDelete = oci_execute($deleteAssign);
                foreach ($memberAssigned as $row) {
                    $userID         = $row['userID'];
                    $userName       = $row['userName'];
                    $description    = $row['description'];
                    $teamLeader     = $row['teamLeader'];
                    $UN = "Changed To: " . $userName;

                    $lastSequanceNo = "SELECT MAX(SEQUENCE_NUMBER) FROM  TICKETING.TICKET_ACTION_HISTORY
                                    WHERE TICKET_NO = " . $ticketNumber;
                    $seqStatment = oci_parse($conn, $lastSequanceNo);
                    oci_execute($seqStatment);
                    $SeqResult = oci_fetch_assoc($seqStatment);
                    $SeqNo   = ++$SeqResult['MAX(SEQUENCE_NUMBER)'];

                    // Insert This Action (Assign) To The Action History Table
                    $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                        (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                        ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
                                                        LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                                VALUES ($ticketNumber, $SeqNo, $statusUpdate, 
                                                        CURRENT_TIMESTAMP, '$UN' , $TicketTransactionSessionID, CURRENT_TIMESTAMP,
                                                        $TicketTransactionSessionID, CURRENT_TIMESTAMP)";
                    $newHistory = oci_parse($conn, $addTicketHistory);
                    $resault = oci_execute($newHistory);

                    if ($resault) {
                        // Insert This Action (Assign) To The Ticket Team Member Table
                        $addTicketTeamMember = "INSERT INTO TICKETING.TICKET_TEAM_MEMBERS 
                                                (TEAM_NO, TICKET_NO, TEAM_LEADER, TEAM_MEMBER, DESCRIPTION, 
                                                CREATED_BY, CREATION_DATE) 
                                        VALUES ($assignTeam, $ticketNumber, '$teamLeader', $userID,
                                                '$description', $TicketTransactionSessionID, CURRENT_TIMESTAMP)";
                        $newTeamMembreAssigned = oci_parse($conn, $addTicketTeamMember);
                        $run = oci_execute($newTeamMembreAssigned);

                        if ($run) {
                            http_response_code(200);
                            echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
                        } else {
                            http_response_code(500); // Internal Server Error
                            $errorMessage = oci_error($newTeamMembreAssigned)['message'];
                            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                            // Optionally, you can log the error message for debugging purposes
                            error_log("Error occurred: $errorMessage");
                        }
                    } else {
                        http_response_code(500); // Internal Server Error
                        $errorMessage = oci_error($newHistory)['message'];
                        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                        // Optionally, you can log the error message for debugging purposes
                        error_log("Error occurred: $errorMessage");
                    }
                }
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($status)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
            $conn::commit();
        } catch (Exception $e) {
            // print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'start') {                             // Start Solving Ticket ( Change Ticket Status To Started ) 

        $ticketid                           = $_POST['tickid'];         //  ticket number
        $TicketTransactionSessionID         = $_POST['TicketTransactionSessionID'];  // User Who Start The Ticket 
        $comments                           = 'Ticket Started';         // Comment For  Adding History
        $statusUpdate                       = 30;                         // Ticket  Status Code : Start

        try {

            // Update Ticket Status In Ticket Table
            $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = " . $statusUpdate . ", LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            TICKET_START_DATE = CURRENT_TIMESTAMP, LAST_UPDATED_BY = " . $TicketTransactionSessionID . "
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

                // Insert This Action (Start) To The Action History Table
                $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                    (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                    ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                            VALUES ($ticketid, $SeqNo, $statusUpdate, 
                                                    CURRENT_TIMESTAMP, '$comments' , $TicketTransactionSessionID, CURRENT_TIMESTAMP,
                                                    $TicketTransactionSessionID, CURRENT_TIMESTAMP)";
                $newHistory = oci_parse($conn, $addTicketHistory);
                $resault = oci_execute($newHistory);

                if ($resault) {
                    actionDate('Start By Tech', date("d/m/y H:i:s"), $ticketid, 'Assigned By Supervisor', 'Started By Tech After');
                    // CalTime('Assigned By Supervisor', "Start By Tech",  $ticketid, 'Started By Tech After');
                    http_response_code(200);
                    echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($newHistory)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($status)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            $conn::rollback();
        }
    } elseif ($action == 'solve') {                             // Solved Ticket ( Change Ticket Status To Solved )

        $ticketid                           = $_POST['tickid'];                 // Ticket Number
        $issue                              = $_POST['issue'];                  // Technichen  Issue Description
        $resolution                         = $_POST['resolution'];             //  Technichen Resolution Description
        $TicketTransactionSessionID         = $_POST['TicketTransactionSessionID'];          //  User Who Solve The Ticket
        $comments                           = 'Ticket Solved ';                 //  Commente For  this action
        $statusUpdate                       = 60;                                 //  Ticket Status

        try {

            // Update Ticket Status In Ticket Table
            $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = " . $statusUpdate . ", LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            LAST_UPDATED_BY = " . $TicketTransactionSessionID . ", 
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

                // Insert This Action (Solve) To The Action History Table
                $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                    (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE,
                                                    ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                            VALUES ($ticketid, $SeqNo, $statusUpdate, 
                                                    CURRENT_TIMESTAMP, '$comments' ,  $TicketTransactionSessionID, CURRENT_TIMESTAMP,
                                                    $TicketTransactionSessionID, CURRENT_TIMESTAMP)";
                $newHistory = oci_parse($conn, $addTicketHistory);
                $resault = oci_execute($newHistory);

                if ($resault) {
                    actionDate('Solved By Tech', date("d/m/y H:i:s"), $ticketid, 'Start By Tech', 'Solved By Tech After');
                    // CalTime('Start By Tech', "Solved By Tech",  $ticketid, 'Solved By Tech After');
                    http_response_code(200);
                    echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($newHistory)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($status)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'add') {                               // Add New Ticket Function

        $userName       = $_POST['name'];           // user who create the ticket
        $serviceType    = $_POST['service'];        //  service type of this ticket
        $details        = $_POST['details'];        //  Service details about this issue
        $description    = $_POST['description'];    //   Description for this ticket
        $ticketStatus   = 10;                       // Ticket Status Static Because All Ticket Created With Status Initial (new) with code (10)
        $device         = $_POST['device'];         // its will be not empty if $details = custody  else then = null 

        if ($device === null) {
            $deviceValue = "null";  // Do not enclose NULL in quotes
        } else {
            $deviceValue = $device;  // Use the actual numeric value
        }

        try {
            // Get The Old ID And Increment the ID for the new Ticket
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

            $departmentNo       = $row['COST_CENTER'];      // We Goona Use It For 2 Column (Depasrtment No, Cost Center)
            $ebsEmployee        = $row['EBS_EMPLOYEE_ID'];
            $userEnName         = $row['USER_EN_NAME'];
            $userID             = $row['USER_ID'];          // We Goona Use It For 3 Column (Created By, User ID, Last Update By)
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
                    // actionDate('Creation Date', date("d/m/y H:i:s"), $ticketNO, 'Creation Date', "Assigned By Supervisor", 'Assigned By Supervisor After');

                    $getActionDate = "SELECT ACTION_DATE FROM TICKETING.TICKETS WHERE TICKET_NO = " . $ticketNO;
                    $actionDateTime = oci_parse($conn, $getActionDate);
                    oci_execute($actionDateTime);
                    $row = oci_fetch_assoc($actionDateTime);

                    $actionDateData = json_decode($row['ACTION_DATE'], true);

                    $actionDateData['Creation Date'] = date("d/m/y H:i:s");

                    $newActionDate = json_encode($actionDateData);

                    $updateActionDate = "UPDATE TICKETING.TICKETS SET ACTION_DATE='$newActionDate'  WHERE TICKET_NO = " . $ticketNO;
                    $up = oci_parse($conn, $updateActionDate);
                    oci_execute($up);

                    // actionDate('Creation Date', date("d/m/y H:i:s"), $ticketNO);
                    echo $ticketNO;
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(['status' => 'error', 'message' => oci_error($action)['message']]);
                }
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($add)['message']]);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'confirm') {                           // Confirm Ticket ( Change Ticket Status To Confirm )

        $returnedTicketNumber           = $_POST['returnedTicketNumber'];       // Ticket Numbe
        $evaluationDescription          = $_POST['evaluationDescription'];      //  Description of the evaluation
        $responseTime                   = $_POST['responseTime'];               //   Response Time
        $confirmSelection               = $_POST['confirmSelection'];           //   Confirm Selection
        $technicianAttitude             = $_POST['technicianAttitude'];         //  Technichen Attitude
        $serviceEvaluation              = $_POST['serviceEvaluation'];          //   General Service Evaluation
        $TicketTransactionSessionID                  = $_POST['TicketTransactionSessionID'];              //   User Who Confirme The Ticket

        $statusUpdate = ' ';

        if ($confirmSelection == 'confirm') {
            $statusUpdate                   = 40;
        } elseif ($confirmSelection == 'reject') {
            $statusUpdate                   = 50;
        }

        try {

            // Update Ticket Status In Ticket Table
            $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = " . $statusUpdate . ", LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            TICKET_END_DATE = CURRENT_TIMESTAMP, LAST_UPDATED_BY = " . $TicketTransactionSessionID . "
                            WHERE TICKET_NO = " . $returnedTicketNumber;

            $status = oci_parse($conn, $statusTicket);
            $run = oci_execute($status);

            if ($run) {
                $lastSequanceNo = "SELECT MAX(SEQUENCE_NUMBER) FROM  TICKETING.TICKET_ACTION_HISTORY
                                WHERE TICKET_NO = " . $returnedTicketNumber;
                $seqStatment = oci_parse($conn, $lastSequanceNo);
                oci_execute($seqStatment);
                $SeqResult = oci_fetch_assoc($seqStatment);
                $SeqNo   = ++$SeqResult['MAX(SEQUENCE_NUMBER)'];

                // Insert This Action (Confirm) To The Action History Table
                $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                    (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                    ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                            VALUES ($returnedTicketNumber, $SeqNo, $statusUpdate, 
                                                    CURRENT_TIMESTAMP, '$evaluationDescription', $TicketTransactionSessionID, CURRENT_TIMESTAMP,
                                                    $TicketTransactionSessionID, CURRENT_TIMESTAMP)";
                $newHistory = oci_parse($conn, $addTicketHistory);
                $resault = oci_execute($newHistory);

                if ($resault) {
                    $lastResponseNo = "SELECT MAX(RESPONSE_ID) FROM  TICKETING.TICKET_EVALUATION";
                    $resStatment = oci_parse($conn, $lastResponseNo);
                    oci_execute($resStatment);
                    $ResResult = oci_fetch_assoc($resStatment);
                    $ResNo   = ++$ResResult['MAX(RESPONSE_ID)'];

                    // Insert User Evaluation To The Ticket Evaluation Table
                    $addTicketEvaluation = "INSERT INTO TICKETING.TICKET_EVALUATION
                                                    (RESPONSE_ID, TICKET_NO, RESPONSE_TIME, 
                                                    TECHNICIAN_ATTITUDE, SERVICE_EVALUATION, CREATED_BY, 
                                                    CREATION_DATE, LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                            VALUES ($ResNo, $returnedTicketNumber,  $responseTime, 
                                                    $technicianAttitude, $serviceEvaluation, $TicketTransactionSessionID,
                                                    CURRENT_TIMESTAMP, $TicketTransactionSessionID, CURRENT_TIMESTAMP)";
                    $newEvaluation = oci_parse($conn, $addTicketEvaluation);
                    $eva = oci_execute($newEvaluation);
                    if ($eva) {
                        actionDate('Confirmed By User', date("d/m/y H:i:s"), $returnedTicketNumber, 'Solved By Tech', 'User Confirmed After');
                        // actionDate('Confirmed By User', date("d/m/y H:i:s"), $returnedTicketNumber);
                        // CalTime('Solved By Tech', "Confirmed By User",  $ticketid, 'User Confirmed After');
                        http_response_code(200);
                        echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
                    } else {
                        http_response_code(500); // Internal Server Error
                        $errorMessage = oci_error($newEvaluation)['message'];
                        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                        // Optionally, you can log the error message for debugging purposes
                        error_log("Error occurred: $errorMessage");
                    }
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($newHistory)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($status)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'cancel') {                            // Cancel Ticket ( Change Ticket Status To Canceled )

        $ticketid       = $_POST['tickid'];         // Ticket Number
        $TicketTransactionSessionID         = $_POST['TicketTransactionSessionID'];  //  User ID Who Cancle The Ticket
        $comments       = 'Ticket Canceled';        //  Comments About Status Change
        $statusUpdate   = 70;                       //   New Status Code For Closed Tickets

        try {

            // Update Ticket Status In Ticket Table
            $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            TICKET_STATUS = " . $statusUpdate . ", LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            TICKET_START_DATE = CURRENT_TIMESTAMP, LAST_UPDATED_BY = " . $TicketTransactionSessionID . "
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

                // Insert This Action (Cancel) To The Action History Table
                $addTicketHistory = "INSERT INTO TICKETING.TICKET_ACTION_HISTORY 
                                                    (TICKET_NO, SEQUENCE_NUMBER, ACTION_CODE, 
                                                    ACTION_DATE, COMMENTS, CREATED_BY, CREATION_DATE,
                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE) 
                                            VALUES ($ticketid, $SeqNo, $statusUpdate, 
                                                    CURRENT_TIMESTAMP, '$comments' , $TicketTransactionSessionID, CURRENT_TIMESTAMP,
                                                    $TicketTransactionSessionID, CURRENT_TIMESTAMP)";
                $newHistory = oci_parse($conn, $addTicketHistory);
                $resault = oci_execute($newHistory);
                if ($resault) {

                    $getActionDate = "SELECT ACTION_DATE FROM TICKETING.TICKETS WHERE TICKET_NO = " . $ticketNO;
                    $actionDateTime = oci_parse($conn, $getActionDate);
                    oci_execute($actionDateTime);
                    $row = oci_fetch_assoc($actionDateTime);

                    $actionDateData = json_decode($row['ACTION_DATE'], true);

                    $actionDateData['Cancel Date'] = date("d/m/y H:i:s");

                    $newActionDate = json_encode($actionDateData);

                    $updateActionDate = "UPDATE TICKETING.TICKETS SET ACTION_DATE='$newActionDate'  WHERE TICKET_NO = " . $ticketNO;
                    $up = oci_parse($conn, $updateActionDate);
                    oci_execute($up);
                    http_response_code(200);
                    echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($newHistory)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($status)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'search') {                              // Fetch Ticket From DB Based On Search Field 

        $TicketTransactionSessionID              = $_POST['TicketTransactionSessionID'];
        $USER_ID                    = $_POST['USER_ID'];
        $searchParams               = $_POST['searchParams'];
        $order                      = !empty($_POST['order']) ? $_POST['order'] :  'TICKET_NO';
        $sortOrder                  = $_POST['sortOrder'];

        try {

            $ticketTransation = "INSERT INTO ticketing.global_temp_table (NAME, VALUE)  
                            VALUES ('$USER_ID', $TicketTransactionSessionID)";
            $insertValue = oci_parse($conn, $ticketTransation);
            $run = oci_execute($insertValue);

            if ($run) {

                // Construct base SQL query
                $sql = "SELECT 
                    TICKET_NO, 
                    SERVICE_TYPE, 
                    SERVICE_DETAIL, 
                    TICKET_PERIORITY_MEANING, 
                    TICKET_STATUS, 
                    REQUEST_TYPE_NO, 
                    SERVICE_DETAIL_NO, 
                    TICKET_PERIORITY,
                    ISSUE_DESCRIPTION, 
                    TECHNICAL_ISSUE_DESCRIPTION, 
                    TECHNICAL_ISSUE_RESOLUTION,
                    USERNAME, 
                    DEPARTMENT_NAME, 
                    TICKET_START_DATE, 
                    BRANCH_CODE,  
                    ASSIGNED_TO ,
                    TICKET_END_DATE,  
                    TTOTAL_TIME, 
                    TOTAL_TIME,
                    TICKET_STATUS_MEANING, 
                    USER_EN_NAME, 
                    EMAIL,
                    EMP_DEPARTMENT,
                    RESPONSE_TIME,
                    TECHNICIAN_ATTITUDE,
                    SERVICE_EVALUATION,
                    REQUESTOR_COMMENTS,
                    EVALUATION_FLAG
                FROM 
                    TICKETING.TICKETS_TRANSACTIONS_V";


                // Initialize array to store conditions
                $conditions = array();

                // Build conditions based on user input
                if (isset($searchParams['SearchTicketNumber']) && !empty($searchParams['SearchTicketNumber'])) {
                    $SearchTicketNumber = $searchParams['SearchTicketNumber'];
                    $conditions[] = "TICKET_NO = " . $SearchTicketNumber;
                }
                if (isset($searchParams['SearchTicketStatus']) && !empty($searchParams['SearchTicketStatus'])) {
                    $SearchTicketStatus             = $searchParams['SearchTicketStatus'];
                    $conditions[] = "TICKET_STATUS =" . $SearchTicketStatus;
                }
                if (isset($searchParams['SearchTicketBranch']) && !empty($searchParams['SearchTicketBranch'])) {
                    $SearchTicketBranch             = $searchParams['SearchTicketBranch'];
                    $conditions[] = "BRANCH_CODE LIKE '%$SearchTicketBranch%'";
                }
                if (isset($searchParams['SearchTicketPriority']) && !empty($searchParams['SearchTicketPriority'])) {
                    $SearchTicketPriority             = $searchParams['SearchTicketPriority'];
                    $conditions[] = "TICKET_PERIORITY = " . $SearchTicketPriority;
                }
                if (isset($searchParams['SearchTicketAssignedTo']) && !empty($searchParams['SearchTicketAssignedTo'])) {
                    $SearchTicketAssignedTo             = $searchParams['SearchTicketAssignedTo'];
                    $conditions[] = "ASSIGNED_TO LIKE '%$SearchTicketAssignedTo%'";
                }
                if (isset($searchParams['SearchTecIssueDiscription']) && !empty($searchParams['SearchTecIssueDiscription'])) {
                    $SearchTecIssueDiscription             = $searchParams['SearchTecIssueDiscription'];
                    $conditions[] = "TECHNICAL_ISSUE_DESCRIPTION LIKE '%$SearchTecIssueDiscription%'";
                }
                if (isset($searchParams['SearchTecIssueResolution']) && !empty($searchParams['SearchTecIssueResolution'])) {
                    $SearchTecIssueResolution             = $searchParams['SearchTecIssueResolution'];
                    $conditions[] = "TECHNICAL_ISSUE_RESOLUTION LIKE '%$SearchTecIssueResolution%'";
                }
                if (isset($searchParams['SearchUserIsseDescription']) && !empty($searchParams['SearchUserIsseDescription'])) {
                    $SearchUserIsseDescription             = $searchParams['SearchUserIsseDescription'];
                    $conditions[] = "ISSUE_DESCRIPTION LIKE '%$SearchUserIsseDescription%'";
                }
                if (isset($searchParams['SearchServiceType']) && !empty($searchParams['SearchServiceType'])) {
                    $SearchServiceType = $searchParams['SearchServiceType'];
                    $conditions[] = "SERVICE_TYPE LIKE '%$SearchServiceType%'";
                }
                if (isset($searchParams['SearchServiceDetails']) && !empty($searchParams['SearchServiceDetails'])) {
                    $SearchServiceDetails = $searchParams['SearchServiceDetails'];
                    $conditions[] = "SERVICE_DETAIL LIKE '%$SearchServiceDetails%'";
                }
                if (isset($searchParams['SearchCreatedBy']) && !empty($searchParams['SearchCreatedBy'])) {
                    $SearchCreatedBy = $searchParams['SearchCreatedBy'];
                    $conditions[] = "EBS_EMPLOYEE_ID = " . $SearchCreatedBy;
                }
                if (isset($searchParams['SearchDepartment']) && !empty($searchParams['SearchDepartment'])) {
                    $SearchDepartment = $searchParams['SearchDepartment'];
                    $conditions[] = "DEPARTMENT_NAME LIKE '%$SearchDepartment%'";
                }

                if (isset($searchParams['SearchFromDate']) && !empty($searchParams['SearchFromDate'])) {
                    $SearchFromDate = $searchParams['SearchFromDate'];
                    $conditions[] = "TICKET_START_DATE >= TO_DATE('$SearchFromDate', 'DD-MM-YY') ";
                }

                if (isset($searchParams['SearchToDate']) && !empty($searchParams['SearchToDate'])) {
                    $SearchToDate = $searchParams['SearchToDate'];
                    $conditions[] = "TICKET_START_DATE <= TO_DATE('$SearchToDate', 'DD-MM-YY')";
                }

                if (isset($searchParams['SearchResponsibleDept']) && !empty($searchParams['SearchResponsibleDept'])) {
                    $SearchResponsibleDept = $searchParams['SearchResponsibleDept'];
                    $conditions[] = "DEPARTMENT_NO = " . $SearchResponsibleDept;
                }

                // Add WHERE clause if conditions exist
                if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                }

                $sql .= "  ORDER BY $order  $sortOrder";

                $all = oci_parse($conn, $sql);
                // Execute the query
                oci_execute($all);

                $data = array();
                while ($row = oci_fetch_assoc($all)) {
                    $data[] = array(
                        'TICKET_NO'                     => $row['TICKET_NO'],
                        'SERVICE_TYPE'                  => $row['SERVICE_TYPE'],
                        'SERVICE_DETAIL'                => $row['SERVICE_DETAIL'],
                        'TICKET_PERIORITY_MEANING'      => $row['TICKET_PERIORITY_MEANING'],
                        'TICKET_STATUS'                 => $row['TICKET_STATUS'],
                        'REQUEST_TYPE_NO'               => $row['REQUEST_TYPE_NO'],
                        'SERVICE_DETAIL_NO'             => $row['SERVICE_DETAIL_NO'],
                        'TICKET_PERIORITY'              => $row['TICKET_PERIORITY'],
                        'ISSUE_DESCRIPTION'             => $row['ISSUE_DESCRIPTION'],
                        'TECHNICAL_ISSUE_DESCRIPTION'   => $row['TECHNICAL_ISSUE_DESCRIPTION'],
                        'TECHNICAL_ISSUE_RESOLUTION'    => $row['TECHNICAL_ISSUE_RESOLUTION'],
                        'USERNAME'                      => $row['USERNAME'],
                        'DEPARTMENT_NAME'               => $row['DEPARTMENT_NAME'],
                        'TICKET_START_DATE'             => $row['TICKET_START_DATE'],
                        'BRANCH_CODE'                   => $row['BRANCH_CODE'],
                        'ASSIGNED_TO'                   => $row['ASSIGNED_TO'],
                        'TICKET_END_DATE'               => $row['TICKET_END_DATE'],
                        'TTOTAL_TIME'                   => $row['TTOTAL_TIME'],
                        'TOTAL_TIME'                    => $row['TOTAL_TIME'],
                        'TICKET_STATUS_MEANING'         => $row['TICKET_STATUS_MEANING'],
                        'USER_EN_NAME'                  => $row['USER_EN_NAME'],
                        'EMAIL'                         => $row['EMAIL'],
                        'EMP_DEPARTMENT'                => $row['EMP_DEPARTMENT'],
                        'RESPONSE_TIME'                 => $row['RESPONSE_TIME'],
                        'TECHNICIAN_ATTITUDE'           => $row['TECHNICIAN_ATTITUDE'],
                        'SERVICE_EVALUATION'            => $row['SERVICE_EVALUATION'],
                        'REQUESTOR_COMMENTS'            => $row['REQUESTOR_COMMENTS'],
                        'EVALUATION_FLAG'            => $row['EVALUATION_FLAG']
                    );
                }

                echo json_encode($data);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($insertValue)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }

            // if (!empty($time)) {
            //     // Assuming the time field is stored as a string in the format day:hour:min:sec
            //     $timeParts = explode(':', $time);
            //     $timeCondition = '';
            //     if (!empty($timeParts[0])) {
            //         $timeCondition .= "day = '{$timeParts[0]}'";
            //     }
            //     if (!empty($timeParts[1])) {
            //         $timeCondition .= (!empty($timeCondition) ? ' AND ' : '') . "hour = '{$timeParts[1]}'";
            //     }
            //     if (!empty($timeParts[2])) {
            //         $timeCondition .= (!empty($timeCondition) ? ' AND ' : '') . "min = '{$timeParts[2]}'";
            //     }
            //     if (!empty($timeParts[3])) {
            //         $timeCondition .= (!empty($timeCondition) ? ' AND ' : '') . "sec = '{$timeParts[3]}'";
            //     }
            //     $conditions[] = "time_field = '{$time}'";
            // }

        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'TicketDetailsInformation') {                              // Fetch Ticket From DB Based On Search Field 

        $TicketNumberDetails                    = $_POST['TicketNumberDetails'];
        $TechnicianIssueDiscriptionDetails      = $_POST['TechnicianIssueDiscriptionDetails'];
        $TechnicianIssueResolutionDetails       = $_POST['TechnicianIssueResolutionDetails'];
        $RequestorCommentDetails                = $_POST['RequestorCommentDetails'];
        $TicketTransactionSessionID             = $_POST['TicketTransactionSessionID'];

        try {
            // Update Ticket Status In Ticket Table
            $statusTicket = "UPDATE TICKETING.TICKETS SET 
                            LAST_UPDATE_DATE = CURRENT_TIMESTAMP,
                            LAST_UPDATED_BY = " . $TicketTransactionSessionID . ", 
                            TECHNICAL_ISSUE_DESCRIPTION = '" . $TechnicianIssueDiscriptionDetails . "', 
                            TECHNICAL_ISSUE_RESOLUTION = '" . $TechnicianIssueResolutionDetails . "'
                            WHERE TICKET_NO = " . $TicketNumberDetails;
            $status = oci_parse($conn, $statusTicket);
            $run = oci_execute($status);

            if ($run) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($status)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'TicketTimeDetails') {                              // Fetch Ticket From DB Based On Search Field 

        $DetailsTimePopup                          = $_POST['DetailsTimePopup'];

        try {
            // Update Ticket Status In Ticket Table
            $TimeDeatails = "SELECT DATE_DEFF, CALCULATED_STATUS_TIME FROM TICKETING.TIME_FOR_TKT_VW WHERE TICKET_NO = " . $DetailsTimePopup . " ORDER BY SEQUENCE_NUMBER";
            $calTime = oci_parse($conn, $TimeDeatails);
            $run = oci_execute($calTime);
            $data = array();
            while ($row = oci_fetch_assoc($calTime)) {
                $data[] = array(
                    'DATE_DEFF'                     => $row['DATE_DEFF'],
                    'CALCULATED_STATUS_TIME'                  => $row['CALCULATED_STATUS_TIME']
                );
            }
            if ($run) {
                echo json_encode($data);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($calTime)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'chatMessage') {                              // Fetch Ticket From DB Based On Search Field 

        $messageFeild                          = $_POST['messageFeild'];
        $TicketTransactionSessionID                          = $_POST['TicketTransactionSessionID'];
        $ticketNumber                          = $_POST['ticketNumber'];

        try {
            // Update Ticket Status In Ticket Table
            $commentID = "SELECT MAX(COMMENT_ID) FROM TICKETING.COMMENTS ";
            $comment = oci_parse($conn, $commentID);
            $run = oci_execute($comment);
            $result = oci_fetch_assoc($comment);
            $newid = $result['MAX(COMMENT_ID)'];
            $newCommentID = ++$newid;

            $addNewComment = "INSERT INTO TICKETING.COMMENTS (COMMENT_ID, TICKET_NO, DESCRIPTION, 
                                        CREATED_BY, CREATION_DATE, LAST_UPDATED_BY, LAST_UPDATE_DATE )  
                            VALUES ($newCommentID, $ticketNumber, '$messageFeild' , $TicketTransactionSessionID, 
                            CURRENT_TIMESTAMP, $TicketTransactionSessionID , CURRENT_TIMESTAMP)";
            $newComment = oci_parse($conn, $addNewComment);
            $run = oci_execute($newComment);

            if ($run) {
                echo http_response_code(200);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($newComment)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'chatHistory') {                              // Fetch Ticket From DB Based On Search Field 

        $ticketNumber                          = $_POST['ticketNumber'];

        try {
            // Update Ticket Status In Ticket Table
            $commentID = "SELECT 
                            TICKETING.xxajmi_ticket_user_info.USERNAME, 
                            TICKETING.COMMENTS.DESCRIPTION, TICKETING.COMMENTS.CREATION_DATE
                            FROM TICKETING.COMMENTS 
                            JOIN 
                                TICKETING.xxajmi_ticket_user_info 
                            ON 
                                TICKETING.xxajmi_ticket_user_info.USER_ID = TICKETING.COMMENTS.CREATED_BY
                            WHERE TICKETING.COMMENTS.TICKET_NO = " . $ticketNumber . "ORDER BY CREATION_DATE ASC";
            $comment = oci_parse($conn, $commentID);
            $run = oci_execute($comment);

            $data = array();
            while ($row = oci_fetch_assoc($comment)) {
                $data[] = array(
                    'DESCRIPTION'                     => $row['DESCRIPTION'],
                    'CREATED_BY'                  => $row['USERNAME'],
                    'CREATION_DATE'                => $row['CREATION_DATE']
                );
            }
            if ($run) {
                echo json_encode($data);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($comment)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'getdetailsteammembersForAssignPopup') {         // Retrive  Service Details Based On Ticket Info
        $selectedServiceDetailsNo = $_POST['selectDetailsTeamMember'];  // Service Type Number

        try {
            // Query to fetch Service Details based on the selected Service Type
            $ServiceDetailTeamMemberName = "SELECT TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO, 
                                                    TICKETING.SERVICE_DETAILS_TEAMS.TEAM_NO,
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
                                                WHERE TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO = '" . $selectedServiceDetailsNo . "'";

            $TeamMemberName = oci_parse($conn, $ServiceDetailTeamMemberName);
            $run = oci_execute($TeamMemberName);
            $options = '';
            while ($row = oci_fetch_assoc($TeamMemberName)) {
                $options .= "<option value='{$row['TEAM_NO']}'>{$row['TEAM_NAME']}</option>";
            }

            if ($run) {
                // Query to fetch Service Details based on the selected Service Type
                $ticketWeight = "SELECT CODE, MEANING From TICKETING.LOOKUP_VALUES WHERE lookup_type_id =" . 8;

                $weight = oci_parse($conn, $ticketWeight);
                $weightRun = oci_execute($weight);
                $weights = '';
                while ($row = oci_fetch_assoc($weight)) {
                    $weights .= "<option value='{$row['CODE']}'>{$row['MEANING']}</option>";
                }

                if ($weightRun) {
                    // Query to fetch Service Details based on the selected Service Type
                    $ticketPeriority = "SELECT CODE, MEANING From TICKETING.LOOKUP_VALUES WHERE lookup_type_id =" . 4;

                    $periority = oci_parse($conn, $ticketPeriority);
                    $preorityRun = oci_execute($periority);
                    $perioritys = '';
                    while ($row = oci_fetch_assoc($periority)) {
                        $perioritys .= "<option value='{$row['CODE']}'>{$row['MEANING']}</option>";
                    }

                    if ($preorityRun) {
                        $response = array(
                            'weights' =>  $weights,
                            'teams' =>  $options,
                            'priorities' =>  $perioritys,
                        );

                        // Return the JSON-encoded response
                        echo json_encode($response);
                    } else {
                        http_response_code(500); // Internal Server Error
                        $errorMessage = oci_error($periority)['message'];
                        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                        // Optionally, you can log the error message for debugging purposes
                        error_log("Error occurred: $errorMessage");
                    }
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($weight)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($TeamMemberName)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'getTeammemberDetail') {                     // Choose Team Member Based On Team Number In Assign Page
        $teamNumber = $_POST['teamMembers'];  // Team Number

        try {
            // Query to fetch Team Member based on the selected Team Number
            $teamMembers = "SELECT 
                            TICKETING.TEAM_MEMBERS.ACTIVE, 
                            TICKETING.xxajmi_ticket_user_info.USERNAME,
                            TICKETING.xxajmi_ticket_user_info.USER_ID
                                FROM 
                                    TICKETING.TEAM_MEMBERS 
                                JOIN 
                                    TICKETING.xxajmi_ticket_user_info
                                ON 
                                    TICKETING.xxajmi_ticket_user_info.USER_ID = TICKETING.TEAM_MEMBERS.TEAM_MEMBER_USER_ID
                                WHERE TEAM_NO = " . $teamNumber;
            $team = oci_parse($conn, $teamMembers);

            $run = oci_execute($team);
            $data = array();
            while ($row = oci_fetch_assoc($team)) {
                $data[] = array(
                    'ID'            => $row['USER_ID'],
                    'name'          => $row['USERNAME'],
                    'active'        => $row['ACTIVE']
                );
            }

            if ($run) {
                echo json_encode($data);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($team)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($team)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($action == 'getEditServiceDetails') {                 // Edit Service Type Information
        $EditServiceType = $_POST['EditServiceType'];  // Service Type Number
        $selectedServiceDetailsNo = $_POST['EditServiceDetails'];  // Service Type Number

        try {
            // Query to fetch Service Details based on the selected Service Type
            $ServiceDetailName = "SELECT
                                    TICKETING.SERVICE.SERVICE_NO,
                                    TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO,
                                    TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NAME
                                FROM
                                    TICKETING.SERVICE
                                JOIN
                                    TICKETING.SERVICE_DETAILS 
                                ON 
                                    TICKETING.SERVICE_DETAILS.SERVICE_NO = TICKETING.SERVICE.SERVICE_NO
                                WHERE
                                    TICKETING.SERVICE.SERVICE_NO = '" . $EditServiceType . "'
                                    AND TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO NOT IN ('" . $selectedServiceDetailsNo . "')";

            $EditServiceDetailsName = oci_parse($conn, $ServiceDetailName);
            $run = oci_execute($EditServiceDetailsName);
            $options = '';
            while ($row = oci_fetch_assoc($EditServiceDetailsName)) {
                $options .= "<option value='{$row['SERVICE_DETAIL_NO']}'>{$row['SERVICE_DETAIL_NAME']}</option>";
            }
            if ($run) {
                echo $options;
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($EditServiceDetailsName)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'updateTicketInformation') {   // Update Ticket Information

        $EditTicketNumber = $_POST['UpdateTicketInformationButton'];  // Service Type Number
        $EditServiceDetails = $_POST['EditServiceDetails'];  // Service Type Number

        try {

            // Query to fetch Service Details based on the selected Service Type
            $UpdateTicketInformation = "UPDATE TICKETING.TICKETS SET SERVICE_DETAIL_NO = " . $EditServiceDetails . " WHERE TICKET_NO = " . $EditTicketNumber;

            $NewTicketInformation = oci_parse($conn, $UpdateTicketInformation);
            $run = oci_execute($NewTicketInformation);

            if ($run) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($NewTicketInformation)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'getHistory') {                   // Retrive Action History Information based on Ticket Number from DB

        $ticketNumber = $_POST['actionHistory'];  // Service Number
        $lookupTypeID =  1;

        try {
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
                            AND TICKETING.LOOKUP_VALUES.lookup_type_id = " . $lookupTypeID . " ORDER BY SEQUENCE_NUMBER DESC";
            $action = oci_parse($conn, $actionHistory);

            $run = oci_execute($action);

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
            if ($run) {
                echo json_encode($data);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($action)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'allUsers') {                          // Get Number Of Ticket  Based On Filter Criteria

        $UserSessionID           = $_POST['allUsers'];

        try {

            $allTicket = "SELECT EBS_EMPLOYEE_ID, USER_EN_NAME, EMAIL, EMP_DEPARTMENT,BRANCH_CODE, USERNAME FROM   TICKETING.xxajmi_ticket_user_info  ";
            $all = oci_parse($conn, $allTicket);
            // Execute the query
            $run = oci_execute($all);

            $data = array();
            while ($row = oci_fetch_assoc($all)) {
                $data[] = array(
                    'EBS_EMPLOYEE_ID'               => $row['EBS_EMPLOYEE_ID'],
                    'USER_EN_NAME'                  => $row['USER_EN_NAME'],
                    'EMAIL'                         => $row['EMAIL'],
                    'EMP_DEPARTMENT'                => $row['EMP_DEPARTMENT'],
                    'BRANCH_CODE'                   => $row['BRANCH_CODE'],
                    'USERNAME'                   => $row['USERNAME']
                );
            }
            if ($run) {
                echo json_encode($data);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($all)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'getFilterdData') {                          // Get Number Of Ticket  Based On Filter Criteria

        $USER_ID         =  $_POST['USER_ID'];
        $TicketTransactionSessionID           = $_POST['TicketTransactionSessionID'];
        $status          = $_POST['filter'];

        try {
            // Insert UserID Into global_temp_table Table After Returned From User Table
            $ticketTransation = "INSERT INTO ticketing.global_temp_table (NAME, VALUE)  
                                VALUES ('$USER_ID', $TicketTransactionSessionID)";
            $insertValue = oci_parse($conn, $ticketTransation);
            $run = oci_execute($insertValue);

            if ($run) {

                // Sanitize the input to prevent SQL injection
                $status = intval($status);

                // Select Tickets Based On User Permission and Ticket Status
                $allTickets = "SELECT COUNT(*) AS total_rows
                    FROM TICKETING.TICKETS_TRANSACTIONS_V
                    WHERE TICKET_STATUS = :status";

                $alltick = oci_parse($conn, $allTickets);
                oci_bind_by_name($alltick, ":status", $status);

                // Execute the query
                oci_execute($alltick);

                // Fetch the result
                $row = oci_fetch_assoc($alltick);
                $allRows = $row['TOTAL_ROWS'];

                echo $allRows;
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($alltick)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'getTeamMemebersAssigned') {             // Choose Team Member Based On Team Number In Change Page
        $ticketNumberforAssignedTeam = $_POST['teamMembersAssigned'];  // Team Number

        try {

            // Query to fetch Team Member based on the selected Team Number
            $teamMembersAssigned = "SELECT 
                                        TICKETING.TICKET_TEAM_MEMBERS.TEAM_NO,
                                        TICKETING.TICKET_TEAM_MEMBERS.TEAM_LEADER,
                                        TICKETING.TICKET_TEAM_MEMBERS.team_member,
                                        TICKETING.TICKET_TEAM_MEMBERS.DESCRIPTION,
                                        TICKETING.xxajmi_ticket_user_info.USERNAME
                                    FROM TICKETING.TICKET_TEAM_MEMBERS
                                    JOIN
                                        TICKETING.xxajmi_ticket_user_info
                                    ON
                                        TICKETING.xxajmi_ticket_user_info.USER_ID = TICKETING.TICKET_TEAM_MEMBERS.team_member
                                    WHERE
                                        TICKETING.TICKET_TEAM_MEMBERS.TICKET_NO =" . $ticketNumberforAssignedTeam;

            $teamAssigned = oci_parse($conn, $teamMembersAssigned);

            $runteam = oci_execute($teamAssigned);
            $teamAssignedTable = array();
            while ($row = oci_fetch_assoc($teamAssigned)) {
                $teamAssignedTable[] = array(
                    'ID'            => $row['TEAM_MEMBER'],
                    'name'          => $row['USERNAME'],
                    'disc'          => $row['DESCRIPTION'],
                    'teamLeader'    => $row['TEAM_LEADER'],
                    'team'          => $row['TEAM_NO']
                );
            }

            if ($runteam) {
                $returnTeamNo = "SELECT TEAM_NO FROM TICKETING.TICKET_TEAM_MEMBERS WHERE TICKET_NO = " . $ticketNumberforAssignedTeam;
                $returnTeamNoS = oci_parse($conn, $returnTeamNo);

                oci_execute($returnTeamNoS);
                $te = oci_fetch_assoc($returnTeamNoS);
                $team_no = $te['TEAM_NO'];

                $teamMembersTable = "SELECT 
                                    TICKETING.TEAM_MEMBERS.ACTIVE, 
                                    TICKETING.xxajmi_ticket_user_info.USERNAME, 
                                    TICKETING.xxajmi_ticket_user_info.USER_ID
                                FROM 
                                    TICKETING.TEAM_MEMBERS 
                                JOIN 
                                    TICKETING.xxajmi_ticket_user_info
                                ON 
                                    TICKETING.xxajmi_ticket_user_info.USER_ID = TICKETING.TEAM_MEMBERS.TEAM_MEMBER_USER_ID
                                WHERE TEAM_NO = " . $team_no . " AND 
                                USER_ID NOT IN (
                                    SELECT team_member
                                    FROM TICKETING.TICKET_TEAM_MEMBERS
                                    WHERE TICKET_NO = " . $ticketNumberforAssignedTeam . ")";
                $teamTable = oci_parse($conn, $teamMembersTable);

                $runassignteam = oci_execute($teamTable);
                $teamTables = array();
                while ($row = oci_fetch_assoc($teamTable)) {
                    $teamTables[] = array(
                        'ID'            => $row['USER_ID'],
                        'name'          => $row['USERNAME'],
                        'active'        => $row['ACTIVE']
                    );
                }

                if ($runassignteam) {
                    $returnTeamOption = "SELECT TEAM_NO, TEAM_NAME FROM TICKETING.TEAMS WHERE TEAM_NO = " . $team_no;
                    $returnTeamOptions = oci_parse($conn, $returnTeamOption);
                    $runallteam = oci_execute($returnTeamOptions);
                    $teamOption = array();
                    while ($row = oci_fetch_assoc($returnTeamOptions)) {
                        $teamOption[] = array( // Append to $teamOption array
                            'TEAM_NO'   => $row['TEAM_NO'],
                            'TEAM_NAME' => $row['TEAM_NAME']
                        );
                    }
                    if ($runallteam) {
                        // Combine both arrays into a single array
                        $responseData = array(
                            'teamAssigned'  => $teamAssignedTable,
                            'teamTables'    => $teamTables,
                            'teamOption'    => $teamOption
                        );

                        echo json_encode($responseData);
                    } else {
                        http_response_code(500); // Internal Server Error
                        $errorMessage = oci_error($returnTeamOptions)['message'];
                        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                        // Optionally, you can log the error message for debugging purposes
                        error_log("Error occurred: $errorMessage");
                    }
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($teamTable)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($teamAssigned)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            $conn::rollback();
        }
    } elseif ($action == 'getRoleID') {
        $UserID = $_POST['UserID'];
        // Query to fetch users role based on User ID
        $permission = " SELECT ROLE_ID FROM TICKETING.TKT_REL_ROLE_USERS WHERE USER_ID =  " . $UserID;
        $userPermission = oci_parse($conn, $permission);
        $run = oci_execute($userPermission);
        $roles = oci_fetch_assoc($userPermission); // User Roles
        $resault = $roles['ROLE_ID'];

        if ($run) {
            echo $resault;
        } else {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($userPermission)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
        }
    }
}

///////////////////////////////////////////***************** Ticket Transation Page Request Functions End  *************************/////////////////////////////////////////









////////////////////////////////****************************************** Add New Ticket Page Request Functions Start  ***********************************************///////////////////////////////

if (isset($_POST['NewTicket'])) {  // All Add New Ticket Ajax Request

    $NewTicket = $_POST['NewTicket'];

    if ($NewTicket == 'getservicesdetails') {   // Choose Service Details Debends On Service Type
        $selectedService = $_POST['serviceType'];  // Service Type Number

        try {

            // Query to fetch Service Details based on the selected Service Type
            $depUser = "SELECT SERVICE_DETAIL_NO, SERVICE_DETAIL_NAME FROM TICKETING.SERVICE_DETAILS WHERE SERVICE_NO = " . $selectedService;
            $dept = oci_parse($conn, $depUser);
            $run = oci_execute($dept);
            // Build HTML options for users
            $options = '';
            while ($row = oci_fetch_assoc($dept)) {
                $options .= "<option value='{$row['SERVICE_DETAIL_NO']}'>{$row['SERVICE_DETAIL_NAME']}</option>";
            }
            if ($run) {
                echo $options;
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($dept)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($dept)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($NewTicket == 'getDeviceNumber') {   // Choose Device Number Debends On Service Details

        $details = $_POST['details'];  // User Name
        $UserSessionName = $_POST['UserSessionName'];  // User Name

        try {
            // Check Custody 
            $custody = "SELECT CUSTODY_LINK FROM TICKETING.SERVICE_DETAILS WHERE SERVICE_DETAIL_NO =" . $details;
            $custodyLink = oci_parse($conn, $custody);
            $run = oci_execute($custodyLink);
            $resault = oci_fetch_assoc($custodyLink);
            $CustodyLink = $resault["CUSTODY_LINK"];

            if ($CustodyLink === 'Y') {
                // Query to fetch User EBS ID based on the User NAME
                $empID = "SELECT EBS_EMPLOYEE_ID FROM TICKETING.xxajmi_ticket_user_info WHERE  USERNAME = '" . $UserSessionName . "'";
                $emp = oci_parse($conn, $empID);
                oci_execute($emp);
                $userEmpID = oci_fetch_assoc($emp);
                $userEBSID = $userEmpID['EBS_EMPLOYEE_ID'];

                // Query to fetch Device Number and Category based on the User EBS ID
                $deviceNo = "SELECT DEVICE_NO, CATEGORY  FROM  CUSTODY.dev_spar_cust_v WHERE EMP_FILE_NO ='" . $userEBSID . "'";
                $device = oci_parse($conn, $deviceNo);
                $run = oci_execute($device);

                // Build HTML options for devices
                $options = '';
                while ($row = oci_fetch_assoc($device)) {
                    $options .= "<option value='{$row['DEVICE_NO']}'>{$row['CATEGORY']}</option>";
                }
                if ($run) {
                    echo $options;
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($device)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                // If Custody Link is not 'Y', indicate that the result is empty
                echo "empty";
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($device)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    }
}
////////////////////////////////****************************************** Add New Ticket Page Request Functions Start  ***********************************************///////////////////////////////






////////////////////////////////****************************************** Service & Service Details Page Request Functions Start  ***********************************************///////////////////////////////


////////////////////////////////////////////////////////////   Service & Service Details Page Request Functions Start 

if (isset($_POST['service'])) {

    $service = $_POST['service'];

    if ($service == 'NewService') {                             // Add New Service Function

        $NewServiceName         = $_POST['serviceName'];
        $ServiceUserSessionID            = $_POST['ServiceUserSessionID'];

        try {
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
                $result        = oci_fetch_assoc($serviceNo);
                $NewServiceID  = ++$result['MAX(SERVICE_NO)'];

                $NewService = "INSERT INTO TICKETING.SERVICE (SERVICE_NO, SERVICE_NAME, CREATED_BY, 
                                        CREATION_DATE, LAST_UPDATED_BY, LAST_UPDATE_DATE)
                                VALUES ($NewServiceID, '$NewServiceName' , $ServiceUserSessionID, CURRENT_TIMESTAMP, $ServiceUserSessionID, CURRENT_TIMESTAMP)";
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
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($AddNewService)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                echo 'exist';
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($service == 'NewServiceDetails') {                 // Add New Service Details Function


        $NewServiceDetailsName              = $_POST['NewServiceDetailsName'];
        $ServiceUserSessionID               = $_POST['ServiceUserSessionID'];
        $ServiceTypeID                      = $_POST['ServiceTypeID'];
        $ServiceDetailsDescription          = $_POST['ServiceDetailsDescription'];

        try {
            $checkServiceDetailName = "SELECT SERVICE_DETAIL_NAME, SERVICE_NO
                                        FROM TICKETING.SERVICE_DETAILS 
                                        WHERE SERVICE_NO=" . $ServiceTypeID . " AND SERVICE_DETAIL_NAME = '" . $NewServiceDetailsName . "'";

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
                                        VALUES ($NewServiceDetailsNo, $ServiceTypeID, '$NewServiceDetailsName', 
                                                '$ServiceDetailsDescription',  $ServiceUserSessionID, CURRENT_TIMESTAMP, 
                                                $ServiceUserSessionID, CURRENT_TIMESTAMP)";
                $AddNewServiceDetails = oci_parse($conn, $NewServiceDetails);
                $run = oci_execute($AddNewServiceDetails);

                if ($run) {
                    echo 'done';
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($AddNewServiceDetails)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                echo 'exist';
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($service == 'NewServiceDetailsTeam') {             // Add New Service Details Team Function

        $GetServiceDetailsName                  = $_POST['GetServiceDetailsName'];
        $GetServiceDetailsTeamNumber            = $_POST['GetServiceDetailsTeamNumber'];
        $GetServiceDetailsID                    = $_POST['GetServiceDetailsID'];
        $ServiceUserSessionID                          = $_POST['ServiceUserSessionID'];

        try {

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
                                                $GetServiceDetailsTeamNumber, $ServiceUserSessionID, 
                                                CURRENT_TIMESTAMP,$ServiceUserSessionID, CURRENT_TIMESTAMP)";
            $AddNewServiceDetailsTeam = oci_parse($conn, $NewServiceDetailsTeam);
            $run = oci_execute($AddNewServiceDetailsTeam);

            if ($run) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($AddNewServiceDetailsTeam)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($service == 'updateTeamTable') {                   // Update Team Enabled Function

        $teamEnabled = json_decode($_POST['teamEnabled'], true);
        $ServiceUserSessionID = $_POST['ServiceUserSessionID'];

        try {
            foreach ($teamEnabled as $row) {
                $teamNo             = $row['teamNo'];
                $newStatus          = $row['newStatus'];
                $serviceDetailsID   = $row['serviceDetailsID'];

                $ServiceDetailTeamID = "UPDATE TICKETING.SERVICE_DETAILS_TEAMS SET 
                                ENABLED ='" . $newStatus . "', LAST_UPDATED_BY = " . $ServiceUserSessionID . ",
                                LAST_UPDATE_DATE = CURRENT_TIMESTAMP  WHERE TEAM_NO= " . $teamNo . " AND SERVICE_DETAIL_NO=" . $serviceDetailsID;
                $TeamID = oci_parse($conn, $ServiceDetailTeamID);
                $run = oci_execute($TeamID);

                if (!$run) {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($TeamID)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            }

            // Send a success response with HTTP status 200
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($service == 'updateServiceDetailsTable') {         // Update Custody And Private In Service Details Table Function

        $custodyColumnJson                  = json_decode($_POST['custodyColumnJson'], true);
        $privateColumnJson                  = json_decode($_POST['privateColumnJson'], true);
        $ServiceUserSessionID               = $_POST['ServiceUserSessionID'];

        try {
            if (!empty($custodyColumnJson)) {
                foreach ($custodyColumnJson as $row) {
                    $servaiceDetailsNo              = $row['servaiceDetailsNo'];
                    $newStatus                      = $row['newStatus'];

                    $ServiceDetailID = "UPDATE TICKETING.SERVICE_DETAILS SET 
                                CUSTODY_LINK ='" . $newStatus . "', LAST_UPDATED_BY = " . $ServiceUserSessionID . ",
                                LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE SERVICE_DETAIL_NO= " . $servaiceDetailsNo;
                    $custodyStatus = oci_parse($conn, $ServiceDetailID);
                    $run = oci_execute($custodyStatus);

                    if (!$run) {
                        http_response_code(500); // Internal Server Error
                        $errorMessage = oci_error($custodyStatus)['message'];
                        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                        // Optionally, you can log the error message for debugging purposes
                        error_log("Error occurred: $errorMessage");
                    }
                }
            }

            if (!empty($privateColumnJson)) {
                foreach ($privateColumnJson as $row) {
                    $servaiceDetailsNo              = $row['servaiceDetailsNo'];
                    $newStatus                      = $row['newStatus'];

                    $ServiceDetailID = "UPDATE TICKETING.SERVICE_DETAILS SET 
                                PRIVATE_FLAG ='" . $newStatus . "', LAST_UPDATED_BY = " . $ServiceUserSessionID . ",
                                LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE SERVICE_DETAIL_NO= " . $servaiceDetailsNo;
                    $privateStatus = oci_parse($conn, $ServiceDetailID);
                    $run = oci_execute($privateStatus);

                    if (!$run) {
                        http_response_code(500); // Internal Server Error
                        $errorMessage = oci_error($privateStatus)['message'];
                        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                        // Optionally, you can log the error message for debugging purposes
                        error_log("Error occurred: $errorMessage");
                    }
                }
            }
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($service == 'EditServiceDetailsInformation') {     // Update Service Details Information In Service Details Table Function

        $EditServiceDetailsName         = $_POST['EditServiceDetailsName'];
        $ServiceUserSessionID           = $_POST['ServiceUserSessionID'];
        $EditServiceDetailsDescription  = $_POST['EditServiceDetailsDescription'];
        $serviceDetailsId               = $_POST['serviceDetailsId'];
        $serviceTypeID               = $_POST['serviceTypeID'];

        try {
            $checkServiceDetailName = "SELECT SERVICE_DETAIL_NAME, SERVICE_NO
                                        FROM TICKETING.SERVICE_DETAILS 
                                        WHERE SERVICE_NO =" . $serviceTypeID . " AND SERVICE_DETAIL_NAME = '" . $EditServiceDetailsName . "'";

            $serviceDetailName = oci_parse($conn, $checkServiceDetailName);
            // Execute the query
            oci_execute($serviceDetailName);
            $empty = oci_fetch($serviceDetailName);

            if ($empty == 0) {
                $UpdateServiceDetailsInfo = "UPDATE TICKETING.SERVICE_DETAILS SET " .
                    "SERVICE_DETAIL_NAME = '" . $EditServiceDetailsName . "'" . ",
                                        DESCRIPTION = '" . $EditServiceDetailsDescription . "'" . ",
                                        LAST_UPDATED_BY = " . $ServiceUserSessionID . ",
                                        LAST_UPDATE_DATE = CURRENT_TIMESTAMP
                                        WHERE SERVICE_DETAIL_NO =" . $serviceDetailsId;

                $updatedServiceDetailsinfo = oci_parse($conn, $UpdateServiceDetailsInfo);

                $run = oci_execute($updatedServiceDetailsinfo);

                if ($run) {
                    echo 'done';
                } else {
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($updatedServiceDetailsinfo)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                echo 'exist';
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($service == 'chooseService') {                     // Retrive Service Details Information based on Service Number from DB

        $serviceNo = $_POST['serviceTypeNumber'];  // Service Number

        try {
            // Query to fetch Service Details based on the selected Service Number
            $serviceDetail = "SELECT 
                                TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NO, 
                                TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NAME,
                                TICKETING.SERVICE_DETAILS.DESCRIPTION, 
                                TICKETING.SERVICE_DETAILS.CUSTODY_LINK, 
                                TICKETING.SERVICE_DETAILS.PRIVATE_FLAG, 
                                TICKETING.SERVICE.SERVICE_NAME                         
                                FROM 
                                    TICKETING.SERVICE_DETAILS
                                JOIN 
                                    TICKETING.SERVICE
                                ON
                                    TICKETING.SERVICE.SERVICE_NO = TICKETING.SERVICE_DETAILS.SERVICE_NO
                                WHERE TICKETING.SERVICE_DETAILS.SERVICE_NO =" . $serviceNo;
            $details = oci_parse($conn, $serviceDetail);

            $run = oci_execute($details);
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

            if ($run) {
                echo json_encode($data);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($details)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = $e->getMessage();
            // Output the error message as JSON
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
        }
    } elseif ($service == 'getservicedetailsteam') {                 // Retrive Service Details Team Name Debends On Service Type

        $ServiceDetailsID = $_POST['ServiceDetailsID'];

        try {
            // Query to fetch Team Name based on the selected Service Type
            $serviceTeam = "SELECT 
                        TICKETING.SERVICE_DETAILS_TEAMS.TEAM_NO, 
                        TICKETING.SERVICE_DETAILS_TEAMS.ENABLED, 
                        TICKETING.TEAMS.TEAM_NAME, 
                        TICKETING.SERVICE_DETAILS.SERVICE_DETAIL_NAME
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
            $run = oci_execute($teamDetails);
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
            if ($run) {
                echo json_encode($data);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($teamDetails)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($service == 'getAssignedTeam') {   // Retrive Not Selected Service Details Teams Debends On Service Details Number
        $notassignedteam = $_POST['notassignedteam'];  // Service Type Number

        try {
            // Query to retrieve a list of teams not associated with the service details
            $notAssignedTeams = "SELECT TEAM_NO, TEAM_NAME FROM TICKETING.TEAMS WHERE TEAM_NO NOT IN 
                (SELECT TEAM_NO FROM TICKETING.SERVICE_DETAILS_TEAMS WHERE SERVICE_DETAIL_NO = " . $notassignedteam . ")";

            $notAssignedTeams = oci_parse($conn, $notAssignedTeams);
            $run = oci_execute($notAssignedTeams);
            // Build HTML options for users
            $options = '';
            while ($row = oci_fetch_assoc($notAssignedTeams)) {
                $options .= "<option value='{$row['TEAM_NO']}'>{$row['TEAM_NAME']}</option>";
            }
            if ($run) {
                echo $options;
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($notAssignedTeams)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    }
}

////////////////////////////////////////////////////////////   Service & Service Details Page Request Functions End 
////////////////////////////////****************************************** Service & Service Details Page Request Functions Start  ***********************************************///////////////////////////////






////////////////////////////////****************************************** Team Member Page Request Functions Start  ***********************************************///////////////////////////////


////////////////////////////////////////////////////////////   Team & Team Member Page Request Functions Start

if (isset($_POST['team'])) {

    $team = $_POST['team'];

    if ($team == 'NewTeam') {                             // Add New Team Function

        $NewTeamName            = $_POST['NewTeamName'];
        $branchCode             = $_POST['branchCode'];
        $description            = $_POST['description'];
        $departmentID           = $_POST['departmentID'];
        $TeamPageSessionID          = $_POST['TeamPageSessionID'];

        try {
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
                                VALUES ($NewTeamID, '$NewTeamName', '$description', $TeamPageSessionID,
                                CURRENT_TIMESTAMP, $TeamPageSessionID, CURRENT_TIMESTAMP, '$branchCode', $departmentID)";
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
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($AddNewTeam)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                echo 'exist';
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($team == 'EditTeamInformation') {               // Edit Team Information Function

        $EditTeamID            = $_POST['EditTeamID'];
        $TeamPageSessionID         = $_POST['TeamPageSessionID'];
        $EditTeamName          = $_POST['EditTeamName'];
        $EditTeamDescription   = $_POST['EditTeamDescription'];
        $EditTeamBranchCode    = $_POST['EditTeamBranchCode'];
        $EditTeamStatus        = $_POST['EditTeamStatus'];
        $EditTeamDepartmentID  = $_POST['EditTeamDepartmentID'];

        try {

            $checkTeamName = "SELECT TEAM_NAME FROM TICKETING.TEAMS WHERE TEAM_NAME = '" . $EditTeamName . "'";

            $teamName = oci_parse($conn, $checkTeamName);
            // Execute the query
            oci_execute($teamName);
            $empty = oci_fetch($teamName);
            if ($empty == 0) {
                $NewTeamInfo = "UPDATE TICKETING.TEAMS SET " .
                    "TEAM_NAME = '" . $EditTeamName . "'" . ",
                    ACTIVE = '" . $EditTeamStatus . "'" . ",
                    DESCRIPTION = '" . $EditTeamDescription . "'" . ",
                    LAST_UPDATED_BY = " . $TeamPageSessionID . ",
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
                    http_response_code(500); // Internal Server Error
                    $errorMessage = oci_error($UpdateTeamInfo)['message'];
                    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                    // Optionally, you can log the error message for debugging purposes
                    error_log("Error occurred: $errorMessage");
                }
            } else {
                echo 'exist';
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($UpdateTeamInfo)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($team == 'updateTeamMemberTable') {             // Update  Active Column In Team Member Table Function

        $TeamPageSessionID                 = $_POST['TeamPageSessionID'];

        try {
            if (!empty($_POST['activeColumnJson'])) {
                $activeColumnJson       = json_decode($_POST['activeColumnJson'], true);
                foreach ($activeColumnJson as $row) {
                    $TeamMemberNo                   = $row['TeamMemberNo'];
                    $newStatus                      = $row['newStatus'];

                    $TeamMemberActive = "UPDATE TICKETING.TEAM_MEMBERS SET 
                                ACTIVE ='" . $newStatus . "', LAST_UPDATED_BY = " . $TeamPageSessionID . ",
                                LAST_UPDATE_DATE = CURRENT_TIMESTAMP WHERE TEAM_MEMBER_USER_ID = " . $TeamMemberNo;
                    $activeStatus = oci_parse($conn, $TeamMemberActive);
                    $run = oci_execute($activeStatus);

                    if ($run) {
                        // Send a success response with HTTP status 200
                        http_response_code(200);
                        echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
                    } else {

                        http_response_code(500); // Internal Server Error
                        $errorMessage = oci_error($activeStatus)['message'];
                        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                        // Optionally, you can log the error message for debugging purposes
                        error_log("Error occurred: $errorMessage");
                    }
                }
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($activeStatus)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($team == 'getTeamInformation') {          // Retrive Team Information Debends On Team Number
        $selectedTeamID = $_POST['teamInfo'];  // Team Number

        try {
            $data = array();
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
            $run = oci_execute($team);
            // Build HTML options for users
            // $result = oci_fetch_assoc($team);
            $data['teamInfo'] = oci_fetch_assoc($team);


            // Query to fetch Team Member based on the selected Team Number
            $teamMember = "SELECT
                                TICKETING.TEAM_MEMBERS.* , 
                                TICKETING.xxajmi_ticket_user_info.USERNAME, 
                                TICKETING.xxajmi_ticket_user_info.USER_EN_NAME, 
                                TICKETING.TKT_REL_ROLE_USERS.ROLE_ID, 
                                TICKETING.TKT_REL_ROLE_USERS.STATUS
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
                                TICKETING.TEAM_MEMBERS.TEAM_NO =" . $selectedTeamID;
            $member = oci_parse($conn, $teamMember);
            oci_execute($member);
            $data['teamMembers'] = array();
            while ($row = oci_fetch_assoc($member)) {
                $data['teamMembers'][] = array(
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
                            TICKETING.DELEGATED_TEM_SUPER.TEAM_NO =" . $selectedTeamID;
            $delegate = oci_parse($conn, $memberDelegated);
            oci_execute($delegate);
            $data['delegatedUsers']  = array();
            while ($row = oci_fetch_assoc($delegate)) {
                $data['delegatedUsers'][]  = array(
                    'name'  => $row['USER_EN_NAME'],
                    'start' => $row['START_DATE'],
                    'end'   => $row['END_DATE']
                );
            }

            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessages = oci_error($team)['message'];
            $errorMessagess = oci_error($delegate)['message'];
            $errorMessagesss = oci_error($member)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage . $errorMessagess . $errorMessagesss]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage . $errorMessagess . $errorMessagesss");
        }
    } elseif ($team == 'restMember') {                  // Retrive Not Selected Team Member Based On Team Number In Team Member Page
        $DepartmentNumber = $_POST['GetMember'];  // Team Number
        $TeamNumber = $_POST['GetTeam'];  // Team Number
        $active = 'Y';

        try {
            // Query to fetch Team Member based on the selected Team Number
            $UsersDepartmentID = "SELECT USER_EN_NAME, USER_ID FROM TICKETING.xxajmi_ticket_user_info  WHERE USER_ID NOT IN 
                        (SELECT TEAM_MEMBER_USER_ID FROM TICKETING.TEAM_MEMBERS WHERE ACTIVE = 'Y' AND  TEAM_NO = " . $TeamNumber . ") AND COST_CENTER = " . $DepartmentNumber;
            $Nomember = oci_parse($conn, $UsersDepartmentID);

            $run = oci_execute($Nomember);
            $options = '';
            while ($row = oci_fetch_assoc($Nomember)) {
                $options .= "<option value='{$row['USER_ID']}'>{$row['USER_EN_NAME']}</option>";
            }
            if ($run) {
                echo $options;
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($Nomember)['message'];

                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($Nomember)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($team == 'addNewTeamMember') {   // Add New Team Member To The Team Based On Team Number And Department ID

        $GetTeamID = $_POST['GetTeamID'];  // Team Number
        $TeamPageSessionID = $_POST['TeamPageSessionID'];
        $GetMemberName = $_POST['GetMemberName'];
        $GetMemberDeacription = $_POST['GetMemberDeacription'];
        $active = 'Y';

        try {
            // Query to fetch Last Team Member No To Create The Next ID
            $lastTeamMemberNo = "SELECT MAX(TEAM_MEMBER_NO) FROM TICKETING.TEAM_MEMBERS";
            $TeamMemberNo     = oci_parse($conn, $lastTeamMemberNo);
            oci_execute($TeamMemberNo);
            $result        = oci_fetch_array($TeamMemberNo);
            $NewTeamMemberNo  = ++$result['MAX(TEAM_MEMBER_NO)'];

            // Query to Insert New Member To the Selected Team 
            $TeamMember = "INSERT INTO TICKETING.TEAM_MEMBERS (TEAM_MEMBER_NO, TEAM_MEMBER_USER_ID, ACTIVE,
                                                        DESCRIPTION, TEAM_NO,  CREATED_BY,  CREATION_DATE)
                                                VALUES ($NewTeamMemberNo, $GetMemberName , '$active', 
                                                '$GetMemberDeacription', $GetTeamID, $TeamPageSessionID, CURRENT_TIMESTAMP)";
            $AddNewTeamMember = oci_parse($conn, $TeamMember);
            $run = oci_execute($AddNewTeamMember);

            if ($run) {
                http_response_code(200); // Internal Server Error
                echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($AddNewTeamMember)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessages = oci_error($Nomember)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    }
}

////////////////////////////////////////////////////////////   Team & Team Member Page Request Functions End
///////////////////////////////////**************************************** Team Member Page Request Functions End  ************************************************///////////////////////////////





///////////////////////////////////////////***************** Delegate Supervisor Page Request Functions Start  *************************/////////////////////////////////////////


if (isset($_POST['delegate'])) {

    $delegate = $_POST['delegate'];

    if ($delegate == 'returnUser') {   // Display Delegated User Debends On Team Number In Delegate Supervisor Section
        $teamInfoDelegate = $_POST['teamInfoDelegate']; // Team Number
        try {
            // Query to fetch Delegated users based on the selected Team Number
            $memberDelegated = "SELECT USER_ID,USERNAME 
                        FROM TICKETING.xxajmi_ticket_user_info 
                        WHERE USER_ID IN (SELECT TEAM_MEMBER_USER_ID FROM TICKETING.TEAM_MEMBERS WHERE TEAM_NO =" . $teamInfoDelegate . ")";

            $delegate = oci_parse($conn, $memberDelegated);
            // Bind the variables
            $run = oci_execute($delegate);

            $options = '';
            while ($row = oci_fetch_assoc($delegate)) {
                $options .= "<option value='{$row['USER_ID']}'>{$row['USERNAME']}</option>";
            }
            if ($run) {
                echo $options;
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($delegate)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($delegate)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($delegate == 'createNewDelegate') {   // Display Delegated User Debends On Team Number In Delegate Supervisor Section

        $delegateTeam =         $_POST['delegateTeam']; // Team Number
        $delegateUser =         $_POST['delegateUser']; // Team Number
        $StartDate =            $_POST['StartDate']; // Team Number
        $EndDate =              $_POST['EndDate']; // Team Number
        $delegateSessionID =        $_POST['delegateSessionID']; // Team Number
        // Reformat the date values to match the expected format 'MM/DD/YYYY'
        $StartDate = date('m/d/Y', strtotime($StartDate));
        $EndDate = date('m/d/Y', strtotime($EndDate));

        // Convert the reformatted date values to Oracle date values using TO_DATE()
        $StartDate = "TO_DATE('$StartDate', 'MM/DD/YYYY')";
        $EndDate = "TO_DATE('$EndDate', 'MM/DD/YYYY')";

        try {

            // Query to fetch Delegated users based on the selected Team Number
            $max        = "SELECT MAX(DELEGATE_ID) FROM TICKETING.DELEGATED_TEM_SUPER";
            $deleID     = oci_parse($conn, $max);
            oci_execute($deleID);
            $id         = oci_fetch_assoc($deleID);
            $delegateNo   = ++$id['MAX(DELEGATE_ID)'];

            $addNewDelegate = "INSERT INTO TICKETING.DELEGATED_TEM_SUPER (DELEGATE_ID, DELEGATE_USER_ID, TEAM_NO,
                                                                    END_DATE, CREATED_BY, CREATION_DATE,
                                                                    LAST_UPDATED_BY, LAST_UPDATE_DATE, START_DATE) 
                                                            VALUES ($delegateNo, $delegateUser, $delegateTeam, 
                                                                    $EndDate, $delegateSessionID, CURRENT_TIMESTAMP, 
                                                                    $delegateSessionID,CURRENT_TIMESTAMP, $StartDate)";
            $newDelegate = oci_parse($conn, $addNewDelegate);
            $resualt = oci_execute($newDelegate);

            if ($resualt) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'User Delegated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($newDelegate)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($newDelegate)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    } elseif ($delegate == 'delegateHistory') {   // Display Delegated User Debends On Team Number In Delegate Supervisor Section
        $delegateUser = $_POST['delegated']; // Team Number
        try {

            // Query to fetch Delegated users based on the selected Team Number
            $memberDelegated = "SELECT
                            TICKETING.DELEGATED_TEM_SUPER.* , 
                            TICKETING.xxajmi_ticket_user_info.USERNAME,USER_EN_NAME
                        FROM 
                            TICKETING.DELEGATED_TEM_SUPER
                        JOIN
                        TICKETING.xxajmi_ticket_user_info
                        ON 
                        TICKETING.xxajmi_ticket_user_info.USER_ID = TICKETING.DELEGATED_TEM_SUPER.DELEGATE_USER_ID
                        WHERE
                            TICKETING.DELEGATED_TEM_SUPER.TEAM_NO = " . $delegateUser;
            $delegate = oci_parse($conn, $memberDelegated);
            // Execute the query
            $run = oci_execute($delegate);
            $data = array();
            while ($row = oci_fetch_assoc($delegate)) {
                $data[] = array(
                    'name'  => $row['USER_EN_NAME'],
                    'start' => $row['START_DATE'],
                    'end'   => $row['END_DATE']
                );
            }
            if ($run) {
                echo json_encode($data);
            } else {
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($delegate)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            $errorMessage = oci_error($delegate)['message'];
            echo json_encode(['status' => 'error', 'message' => $errorMessage]);
            // Optionally, you can log the error message for debugging purposes
            error_log("Error occurred: $errorMessage");
            $conn::rollback();
        }
    }
}

///////////////////////////////////////////***************** Delegate Supervisor Page Request Functions End  *************************/////////////////////////////////////////







///////////////////////////////////////////***************** Change All Solved Ticket To Confirmed Request Functions Start  *************************/////////////////////////////////////////

if (isset($_POST['UserNameSession'])) {   // Change All Status Solved Ticket To Confirmed Status From Application Manager
    $UserSessionID = $_POST['UserNameSession']; // User ID Session
    $NewStatus = 140;
    $oldStatus =  60;

    try {

        $NoTicket = "SELECT COUNT(*) AS NUM FROM TICKETING.TICKETS WHERE TICKET_STATUS = " . $oldStatus;
        $allSolvedTicket = oci_parse($conn, $NoTicket);
        $resualt = oci_execute($allSolvedTicket);
        $row = oci_fetch_assoc($allSolvedTicket);

        if ($row['NUM'] > 0) {
            // Query to Update Ticket Status To Comfirme
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
                http_response_code(500); // Internal Server Error
                $errorMessage = oci_error($confirmed)['message'];
                echo json_encode(['status' => 'error', 'message' => $errorMessage]);
                // Optionally, you can log the error message for debugging purposes
                error_log("Error occurred: $errorMessage");
            }
        } else {
            echo 'empty';
        }
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        $errorMessage = oci_error($confirmed)['message'];
        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
        // Optionally, you can log the error message for debugging purposes
        error_log("Error occurred: $errorMessage");
        $conn::rollback();
    }
}

///////////////////////////////////////////***************** Change All Solved Ticket To Confirmed Request Functions End  *************************/////////////////////////////////////////



///////////////////////////////////////////***************** Change All Solved Ticket To Confirmed Request Functions Start  *************************/////////////////////////////////////////

if (isset($_POST['TurnOffFunction'])) {   // Change All Status Solved Ticket To Confirmed Status From Application Manager

    $turnoff = $_POST['TurnOffFunction'];

    session_start();        // Start Session

    if ($turnoff == 'turnOff') {
        $UserSessionID = $_POST['UserNameSession']; // User ID Session

        $active = 'N';

        // Query to fetch users Information based on User Name
        $activeUsers   = "UPDATE TICKETING.xxajmi_ticket_user_info SET ACTIVE_LOGIN = '" . $active . "'  WHERE USER_ID = '" .  $UserSessionID . "'";
        $actives       = oci_parse($conn, $activeUsers);
        oci_execute($actives);

        session_unset();        // Unset  Data

        session_destroy();      // Destroy The Session

        header('Location: index.php');

        exit();
    }
}

///////////////////////////////////////////***************** Change All Solved Ticket To Confirmed Request Functions End  *************************/////////////////////////////////////////





/*********************************************************** End  ***********************************************************************/





/*
    ** actionDate Function That Update Action Date And Cal Time  Columns Debends On The Action
    ** Has Five Parameter  First Is Key Of Value (Action Name) Second Is Value Of That Key (Action Date) And Third Is Ticket Number 
        Fourth First Date To Calculate The Different Between Dates  Fifth Key Of The Different Time Between To Action
*/

function actionDate($actionName, $actionDate, $ticketNumber, $firstDate, $calTimeKey)
{
    // Oracle database connection settings
    $host = '192.168.15.245';
    $port = '1521';
    $sid = 'ARCHDEV';
    $username = 'selfticket';
    $password = 'selfticket';

    putenv('NLS_LANG=AMERICAN_AMERICA.AL32UTF8');
    $conn = oci_connect($username, $password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SID=$sid)))");


    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        echo "Connectoin to Oracle Database Failed!<br>";
    }

    try {
        $getActionDate = "SELECT ACTION_DATE FROM TICKETING.TICKETS WHERE TICKET_NO = " . $ticketNumber;
        $actionDateTime = oci_parse($conn, $getActionDate);
        oci_execute($actionDateTime);
        $row = oci_fetch_assoc($actionDateTime);

        $actionDateData = json_decode($row['ACTION_DATE'], true);

        $actionDateData[$actionName] = $actionDate;

        $newActionDate = json_encode($actionDateData);

        $dateOne = DateTime::createFromFormat('d/m/y H:i:s', $actionDateData[$firstDate]);
        $dateTwo = DateTime::createFromFormat('d/m/y H:i:s', $actionDate);

        $interval = $dateOne->diff($dateTwo);

        $DaysDifference = $interval->format('%a');
        $HoursDifference = $interval->format('%h');
        $MinDifference = $interval->format('%i');
        $SecDifference = $interval->format('%s');

        $difference = $DaysDifference . " : " . $HoursDifference .  " : " .  $MinDifference . " : " . $SecDifference;

        $getCalTime = "SELECT CAL_TIME FROM TICKETING.TICKETS WHERE TICKET_NO =" . $ticketNumber;
        $cal_time = oci_parse($conn, $getCalTime);
        oci_execute($cal_time);
        $calTime = oci_fetch_assoc($cal_time);

        $calTimeData = json_decode($calTime['CAL_TIME'], true);

        $calTimeData[$calTimeKey] = $difference;

        $diffTime = json_encode($calTimeData);

        $updateActionDate = "UPDATE TICKETING.TICKETS SET ACTION_DATE='$newActionDate' , CAL_TIME = '$diffTime'  WHERE TICKET_NO = " . $ticketNumber;
        $up = oci_parse($conn, $updateActionDate);
        oci_execute($up);
    } catch (Exception $e) {
        print_r($e->getMessage());
        $conn::rollback();
    }
}
