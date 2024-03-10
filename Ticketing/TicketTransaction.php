<?php
set_time_limit(2000);
$startTime = microtime(true);

/*
    ================================================
    == Manage Ticketing Page
    == You Can Edit | Delete | Assign | Response Ticket From Here 
    ================================================
*/

ob_start(); // Output Buffering Start

session_start();


if (isset($_SESSION['user'])) {

    $pageTitle = 'Ticket Transation';
    // // Oracle database connection settings
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
    putenv('NLS_LANG=AMERICAN_AMERICA.AL32UTF8');
    $conn = oci_connect($username, $password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SID=$sid)))");

    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        echo "Connectoin to Oracle Database Failed!<br>";
    }

    // include 'connect.php';  // Connection Database File 

    include 'init.php';  // This File Contain ( Header, Footer, Navbar, Function, connection DB) File

    // Select UserID bsed on Username To return User Roles
    $userNamePre = "SELECT USER_ID FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = '" . $_SESSION["user"] . "'";
    $prevlag = oci_parse($conn, $userNamePre);
    oci_execute($prevlag);
    $prevlegs = oci_fetch_assoc($prevlag);
    $userNamePreResault = $prevlegs['USER_ID'];  // UserID

    // Select User Roles Based On UserID To Display Data Based On Users Permission

    $role = " SELECT ROLE_ID FROM TICKETING.TKT_REL_ROLE_USERS WHERE USER_ID =  " . $userNamePreResault;
    $userRole = oci_parse($conn, $role);
    oci_execute($userRole);
    $roles = oci_fetch_assoc($userRole); // User Roles

    $USER_ID = 'USER_ID';

    // Insert UserID Into global_temp_table Table After Returned From User Table
    $ticketTransation = "INSERT INTO ticketing.global_temp_table (NAME, VALUE)  
    VALUES ('$USER_ID', $userNamePreResault)";
    $insertValue = oci_parse($conn, $ticketTransation);
    $run = oci_execute($insertValue);


    if ($sid == 'ARCHDEV') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Test_Applecation</span></div>';
    } elseif ($sid == 'ARCHPROD') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Production_Applecation</span></div>';
    } else {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;">' . $sid . '</span></div>';
    }


?>

    <!-- Main Table Start -->
    <input type="hidden" id="UserSessionID" value="<?php echo $userNamePreResault ?>" disabled readonly>
    <main class="content px-3 py-2">
        <div class="container-fluid">
            <div class="mb-1">
                <h2 class="text-center mt-2">Ticketing Transactions</h2>
                <div class="scroll-wrapper mb-5 mt-3">
                    <div class='my-2 '>
                        <div class='col-sm-2 my-1  d-flex '>
                            <label class="pe-1" for="recoredPerPage">No Of Recoed</label>
                            <select class="form-select w-50" id="recoredPerPage">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="scroll">
                        <table class="hiddenList scro">
                            <thead>
                                <tr>
                                    <th id="orderBy" data-filter="TICKET_NO">Ticket NO <i id="TICKET_NO" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="SERVICE_TYPE">Service Type <i id="SERVICE_TYPE" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="SERVICE_DETAIL">Service Details <i id="SERVICE_DETAIL" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="TICKET_PERIORITY_MEANING">! <i id="TICKET_PERIORITY_MEANING" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="TICKET_STATUS">Status <i id="TICKET_STATUS" class="fa-solid fa-arrow-up"></i></th>
                                    <th hidden>Request Type No</th>
                                    <th hidden>Service Detail No</th>
                                    <th hidden>Periority No</th>
                                    <th id="orderBy" data-filter="ISSUE_DESCRIPTION">User Issue Description <i id="ISSUE_DESCRIPTION" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="TECHNICAL_ISSUE_DESCRIPTION">Tech Issue Description <i id="TECHNICAL_ISSUE_DESCRIPTION" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="TECHNICAL_ISSUE_RESOLUTION">Tech Issue Resolution <i id="TECHNICAL_ISSUE_RESOLUTION" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="USERNAME">Created By <i id="USERNAME" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="DEPARTMENT_NAME">Department <i id="DEPARTMENT_NAME" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="TICKET_START_DATE">Creation Date <i id="TICKET_START_DATE" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="BRANCH_CODE">Branch <i id="BRANCH_CODE" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="ASSIGNED_TO">Assigned To <i id="ASSIGNED_TO" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="TICKET_END_DATE">End Date <i id="TICKET_END_DATE" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="TTOTAL_TIME">Total IT Time <i id="TTOTAL_TIME" class="fa-solid fa-arrow-up"></i></th>
                                    <th id="orderBy" data-filter="TOTAL_TIME">Total Time <i id="TOTAL_TIME" class="fa-solid fa-arrow-up"></i></th>
                                </tr>
                            </thead>
                            <tbody id="mainTableTicketTransation">
                                <?php

                                // //     $userNamePreResault         =  'USER_ID'; // User Who Logged In 
                                // //     $userIDPreResault           = $_POST['userIDPreResault'];
                                // $page                     = isset($_GET['page']) ? $_GET['page'] : 1; // In this Case Its Equal 0
                                // $recordPerPage = 10;

                                // //     // Insert UserID Into global_temp_table Table After Returned From User Table
                                // //     $ticketTransation = "INSERT INTO ticketing.global_temp_table (NAME, VALUE)  
                                // // VALUES ('$userNamePreResault', $userIDPreResault)";
                                // //     $insertValue = oci_parse($conn, $ticketTransation);
                                // //     $run = oci_execute($insertValue);


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
                                //         $updateActionDate = "UPDATE TICKETING.TICKETS SET TOTAL_TIME = '$difference' ";
                                //         $up = oci_parse($conn, $updateActionDate);

                                //         oci_execute($up);
                                //     }
                                // }


                                // $startFrom = ($page - 1) * $recordPerPage;

                                // $endAt = $page * $recordPerPage;

                                // $allTicket = "SELECT *
                                // FROM (
                                //     SELECT t.*, ROWNUM AS rn
                                //     FROM (
                                //         SELECT *
                                //         FROM TICKETING.TICKETS
                                //         WHERE TICKET_NO IN (
                                //                 SELECT TICKET_NO
                                //                 FROM TICKETING.ticket_team_members
                                //                 WHERE TEAM_MEMBER = 10003
                                //                 UNION
                                //                 SELECT TICKET_NO
                                //                 FROM TICKETING.TICKETS
                                //                 WHERE REQUEST_TYPE_NO = 1
                                //         ) 
                                //     ) t
                                // )
                                // WHERE rn BETWEEN '$startFrom' AND '$endAt'";
                                // $all = oci_parse($conn, $allTicket);
                                // // Execute the query
                                // oci_execute($all);

                                // while ($row = oci_fetch_assoc($all)) {
                                //     echo "<tr>";
                                //     echo "<td>{$row['t.TICKET_NO']}</td>";
                                //     echo "<td>{$row['t.SERVICE_TYPE']}</td>";
                                //     echo "<td>{$row['t.SERVICE_DETAIL']}</td>";
                                //     echo "<td>{$row['t.TICKET_PERIORITY_MEANING']}</td>";
                                //     echo "<td>{$row['t.TICKET_STATUS']}</td>";
                                //     echo "<td>{$row['t.REQUEST_TYPE_NO']}</td>";
                                //     echo "<td>{$row['t.SERVICE_DETAIL_NO']}</td>";
                                //     echo "<td>{$row['t.TICKET_PERIORITY']}</td>";
                                //     echo "<td>{$row['t.ISSUE_DESCRIPTION']}</td>";
                                //     echo "<td>{$row['t.TECHNICAL_ISSUE_DESCRIPTION']}</td>";
                                //     echo "<td>{$row['t.TECHNICAL_ISSUE_RESOLUTION']}</td>";
                                //     echo "<td>{$row['t.USERNAME']}</td>";
                                //     echo "<td>{$row['t.DEPARTMENT_NAME']}</td>";
                                //     echo "<td>{$row['t.TICKET_START_DATE']}</td>";
                                //     echo "<td>{$row['t.BRANCH_CODE']}</td>";
                                //     echo "<td>{$row['t.ASSIGNED_TO']}</td>";
                                //     echo "<td>{$row['t.TICKET_END_DATE']}</td>";
                                //     echo "<td>{$row['t.ACTION_DATE']}</td>";
                                //     echo "<td>{$row['t.CAL_TIME']}</td>";
                                //     echo "<td>{$row['t.TOTAL_TIME']}</td>";
                                //     echo "</tr>";
                                // };

                                // $numberOfRecord = "SELECT count(*) AS COUNTS FROM TICKETING.TICKETS_TRANSACTIONS_V";
                                // $noRecord = oci_parse($conn, $numberOfRecord);
                                // oci_execute($noRecord);
                                // $num = oci_fetch_assoc($noRecord);
                                // $NoPage = ceil($num['COUNTS'] / $recordPerPage);
                                // $pagination = '';

                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php

                    // echo '<div class="d-flex justify-content-center align-center mt-2 mb-2" id="paginationContainer">';
                    // for ($i = 1; $i <= $NoPage; $i++) {
                    //     $pagination .= "<a  href='?page=$i' class='pagination_link pagination' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" . $i . "'>" . $i . "</a>";
                    // };
                    // echo $pagination;
                    // echo '</div>';

                    ?>
                    <div class="d-flex justify-content-between mt-2 mb-3" id="paging">
                        <div id="numberOfPages"></div>
                        <nav aria-label="Page navigation example" class="d-flex justify-content-center align-items-center">
                            <ul class="pagination" id="paginationContainer">
                            </ul>
                        </nav>
                    </div>

                </div>
                <!-- Ticket Filtering Section Start ( Filter Ticket Based On Ticket Status)-->
                <div class="container-fluid  " style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                    <div class="row">
                        <div class="col-2">
                            <div class="card text-bg-secondary text-white mb-3" style="max-width: 15rem;">
                                <div class="card-header">
                                    <button class="tickets" id="ticketButton" data-filter="10"><i class="fa-solid fa-plus pe-2"></i>New Ticket: <span id="count-10">Loading...</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="card text-bg-primary mb-3" style="max-width: 20rem;">
                                <div class="card-header ">
                                    <button class="tickets" id="ticketButton" data-filter="30"><i class="fa-solid fa-envelope-open pe-2"></i>Started working on ticket: <span id="count-30">Loading...</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="card text-bg-primary mb-3" style="max-width: 15rem;">
                                <div class="card-header">
                                    <button class="tickets" id="ticketButton" data-filter="110"><i class="fa-solid fa-paper-plane pe-2"></i>Sent Out: <span id="count-110">Loading...</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="card text-bg-success mb-3" style="max-width: 17rem;">
                                <div class="card-header">
                                    <button class="tickets" id="ticketButton" data-filter="40"><i class="fa-solid fa-at pe-2"></i>Requester confirmation: <span id="count-40">Loading...</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="card text-bg-danger mb-3" style="max-width: 15rem;">
                                <div class="card-header">
                                    <button class="tickets" id="ticketButton" data-filter="70"><i class="fa-solid fa-circle-xmark pe-2"></i>Canceled Ticket: <span id="count-70">Loading...</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="card text-bg-warning mb-3" style="max-width: 15rem;">
                                <div class="card-header">
                                    <button class="tickets" id="ticketButton" data-filter="20"><i class="fa-solid fa-at pe-2"></i>Ticket assigned: <span id="count-20">Loading...</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="card text-bg-success mb-3" style="max-width: 20rem;">
                                <div class="card-header">
                                    <button class="tickets" id="ticketButton" data-filter="60"><i class="fa-solid fa-circle-check pe-2"></i>Solved by technician: <span id="count-60">Loading...</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="card text-bg-info mb-3" style="max-width: 15rem;">
                                <div class="card-header">
                                    <button class="tickets" id="ticketButton" data-filter="120"><i class="fa-solid fa-at pe-2"></i>Received: <span id="count-120">Loading...</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="card text-bg-danger mb-3" style="max-width: 17rem;">
                                <div class="card-header">
                                    <button class="tickets" id="ticketButton" data-filter="50"><i class="fa-solid fa-at pe-2"></i>Rejected By requester: <span id="count-50">Loading...</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="card text-bg-light mb-3" style="max-width: 15rem;">
                                <?php

                                $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_V ";

                                $alltick = oci_parse($conn, $allTicket);
                                // oci_bind_by_name($alltick, ":t_service", $user['DEPARTMENT']);

                                // Execute the query
                                oci_execute($alltick);

                                // Fetch the result
                                $row = oci_fetch_assoc($alltick);

                                // Close the connection
                                oci_close($conn);

                                while ($row = oci_fetch_assoc($alltick)) {
                                    // Process each row
                                }

                                $allRows = oci_num_rows($alltick);
                                ?>

                                <div class="card-header">
                                    <button class="tickets" style="color: black;" id="ticketButton" data-filter="0"><i class="fa-solid fa-layer-group pe-2"></i>Total: <?php echo $allRows ?></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Ticket Filtering Section End -->
            </div>

            <!-- Action List Start  -->

            <div class="wrapper" id="wrapper">
                <div class="contents">
                    <span style="padding: 10px;"> Ticket No#:</span>
                    <span id="returnTicketNumber"></span>
                    <input type="hidden" id="UserSessionID" value="<?php echo  $userNamePreResault ?>" disabled readonly>
                    <input type="hidden" id="UserRole" value="<?php echo  $roles['ROLE_ID'] ?>" disabled readonly>
                    <ul class="menu" id="actionTicketTransactionList">

                    </ul>
                </div>
            </div>
        </div>
    </main>


    <!-- Solve Pop Up Form Start -->
    <div class="modal fade" id="solvePopup" tabindex="-1" aria-labelledby="solvePopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="solvePopupLabel">Any Comment For User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>

                        <div class="mb-3">
                            <label for="issue" class="col-form-label">Technician Issue Description:</label>
                            <textarea class="form-control issue" id="issue"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="resolution" class="col-form-label">Technician Solve Resolution:</label>
                            <textarea class="form-control resolution" id="resolution"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " id="solveTicket" value="<?php echo  $prevlegs['USER_ID'] ?>">Send message</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Solve Pop Up Form Start -->

    <!-- Confirm Pop Up Form Start -->
    <div class="modal fade" id="finishPopup" tabindex="-1" aria-labelledby="finishPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="finishPopupLabel">Send Your Evaluation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="radioConfirm">
                        <input type="radio" name="confirmation" id="confirm" value="confirm" checked>
                        <input type="radio" name="confirmation" id="reject" value="reject">
                        <label for="confirm" class="option confirm">
                            <div class="dot"></div>
                            <span>Confirm</span>
                        </label>
                        <label for="reject" class="option reject">
                            <div class="dot"></div>
                            <span>Reject</span>
                        </label>
                    </div>
                    <label for="returnedTicketNumber" class="d-inline">Ticket Number:</label>
                    <input style="width: 100px;" id="returnedTicketNumber">
                    <div class=" " style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                        <div class="text-center">
                            <h4>Quick Evaluation</h4>
                            <p>( Take few second to serve you better )</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div style="margin-right: 40px;">
                                        <p>Response Time:</p>
                                    </div>
                                    <div class="radioConfirm" style="width: 100px; height: 90px;">
                                        <input type="radio" name="responseTime" id="fast" value="1" checked>
                                        <input type="radio" name="responseTime" id="slow" value="0">
                                        <label for="fast" class="option fast">
                                            <span class="emoji"><i class="fa-solid fa-face-smile-beam "></i></span>
                                        </label>
                                        <label for="slow" class="option slow">
                                            <span class="emoji"><i class="fa-solid fa-face-angry "></i></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex  align-items-center justify-content-between">
                                    <div style="margin-right: 40px;">
                                        <p>Technician Attitude:</p>
                                    </div>
                                    <div class="radioConfirm" style="width: 100px; height: 90px;">
                                        <input type="radio" name="technicianAttitude" id="nice" value="1" checked>
                                        <input type="radio" name="technicianAttitude" id="bad" value="0">
                                        <label for="nice" class="option nice">
                                            <span class="emoji"><i class="fa-solid fa-face-smile-beam "></i></span>
                                        </label>
                                        <label for="bad" class="option bad">
                                            <span class="emoji"><i class="fa-solid fa-face-angry "></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class=" row ms-2">
                                    <label for="generalEvaluation" class="col-sm-10 col-form-label">Service Evaluation In General</label>
                                    <div class="col-sm-10 mb-3">
                                        <select class="form-select" id="generalEvaluation">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="evaluation" class="col-form-label">User Evaluation Description:</label>
                        <textarea class="form-control " id="evaluation"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary " id="ConfirmTicket" value="<?php echo  $prevlegs['USER_ID'] ?>">Send message</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirm Pop Up Form Start -->

    <!-- Assign Pop Up Form Start -->
    <div class="modal fade" id="assignPopup" tabindex="-1" aria-labelledby="assignPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container-fluid"> <!-- Container-fluid Div Start -->
                            <div class="mb-1">
                                <h2 class="text-center" id="assignPopupLabel">Assign Tickets</h2>
                                <div class="container  mt-1">
                                    <h3 class="text-start mt-3 mb-4 text-dark">Ticket Information</h3>
                                    <div class="row g-3" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                        <div class="col-sm-4">
                                            <label class="" for="ticketNumber">Ticket #</label>
                                            <input type="text" class="form-control" id="ticketNumber" aria-label="City" disabled readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="RequestedBy">Requested By</label>
                                            <input type="text" class="form-control" id="RequestedBy" aria-label="State" disabled readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="ticketWeight">Ticket Weight</label>
                                            <select class="form-select" id="ticketWeight">
                                                <option value='0' selected>Select Weight...</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="requestType">Service Name</label>
                                            <input type="text" class="form-control" id="requestType" aria-label="City" disabled readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="serviceFor">Service For</label>
                                            <input type="text" class="form-control" id="serviceFor" aria-label="State" disabled readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="ticketPeriority">Ticket Periority</label>
                                            <select class="form-select" id="ticketPeriority">
                                                <option value='0' selected>Select Priority...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="container mt-2 mb-2">
                                    <div class="mb-3 row">
                                        <label for="assignTeam" class="col-sm-2 col-form-label">Setect Team</label>
                                        <div class="col-sm-6 mb-3">
                                            <select class="form-select" id="assignTeam">
                                                <option value=''>Select Team...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid ">
                                    <div class="row d-flex justify-content-center"> <!-- Container Div Start  -->
                                        <div class="col-sm-6 ">
                                            <div class=" text-center mt-5" id="waitingMessageForTeamAssignMember">
                                                <div class="alert alert-primary" role="alert">
                                                    There Is No Data You Can See It Yet.
                                                </div>
                                            </div>
                                            <h3 class="text-start mt-3 text-dark">Team Member</h3>
                                            <div class="teamMemberTable">
                                                <table class="main-table text-center table table-bordered mt-3 ">
                                                    <thead>
                                                        <tr>
                                                            <th hidden>ID</th>
                                                            <th>User Name</th>
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                            <th>Control</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="teamMember">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class=" col-sm-6 ">
                                            <h3 class="text-start mt-3 text-dark">Selected Team Member for Ticket</h3>
                                            <div class="teamMemberTable">
                                                <table class="main-table text-center table table-bordered mt-3  ">
                                                    <thead>
                                                        <tr>
                                                            <th hidden>ID</th>
                                                            <th>User Name</th>
                                                            <th>Name</th>
                                                            <th>Description</th>
                                                            <th>Team Leader</th>
                                                            <th>Control</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="memberAssigned">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> <!-- Container Div End  -->
                                    <div class="col-sm-4 mt-4">
                                        <button class="btn btn-success button" id="assignTicket" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Team'>
                                            <i class='fa-solid fa-at'></i>
                                            <span>Assign</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Assign Pop Up Form Start -->

    <!-- Action History Pop Up Form Start -->
    <div class="modal fade" id="actionHistory" tabindex="-1" aria-labelledby="actionHistoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h1 class="modal-title fs-5" id="assignPopupLabel">Any Comment For User</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Assign Ticket  Start -->
                    <main class="content px-3 py-2 teamMember"> <!-- Main Start -->
                        <div class="container-fluid"> <!-- Container-fluid Div Start -->
                            <div class="mb-3">
                                <div class="container">
                                    <div class="omar"> <!-- Container Div Start  -->
                                        <h2 class="text-center" id="actionHistoryLabel">Action History</h2>
                                        <div class="teamMemberTable">
                                            <table class="main-table text-center table table-bordered mt-3 ">
                                                <thead>
                                                    <tr>
                                                        <th>Sequence</th>
                                                        <th>Action</th>
                                                        <th>Action By</th>
                                                        <th>Action Date</th>
                                                        <th>Comments</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="ticketActionHistoryBodyTable">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> <!-- Container Div End  -->
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                    <!-- Assign Ticket Info End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Action History Pop Up Form Start -->

    <!-- Edit Ticket Pop Up Form Start -->
    <div class="modal fade" id="EditTicketPopup" tabindex="-1" aria-labelledby="EditTicketPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h1 class="modal-title fs-5" id="assignPopupLabel">Any Comment For User</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Assign Ticket  Start -->
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container-fluid"> <!-- Container-fluid Div Start -->
                            <div class="mb-3">
                                <h2 class="text-center mb-2" id="EditTicketPopupLabel">Edit Ticket</h2>
                                <div class="container  mt-2">
                                    <div class="row g-3 mt-4" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                        <div class="col-sm-6">
                                            <label class="" for="EditTicketNumber">Ticket #</label>
                                            <input type="text" class="form-control" id="EditTicketNumber" aria-label="City" disabled readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="" for="EditRequestedBy">Requested By</label>
                                            <input type="text" class="form-control" id="EditRequestedBy" aria-label="State" disabled readonly>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label class="" for="EditrequestType">Service Name</label>
                                            <input type="text" class="form-control" id="EditrequestType" aria-label="City" disabled readonly>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label class="" for="EditServiceDetails">Service Details</label>
                                            <select class="form-select" id="EditServiceDetails">
                                                <option selected></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-success button" id="UpdateTicketInformationButton" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Service Details'>
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>Update</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                    <!-- Assign Ticket Info End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Ticket Pop Up Form Start -->


    <!-- Change Pop Up Form Start -->
    <div class="modal fade" id="changePopup" tabindex="-1" aria-labelledby="changePopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container-fluid"> <!-- Container-fluid Div Start -->
                            <div class="mb-1">
                                <h2 class="text-center" id="changePopupLabel">Change Team</h2>
                                <div class="container  mt-1">
                                    <h3 class="text-start  mb-2 text-dark">Ticket Information</h3>
                                    <div class="row g-3" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                        <div class="col-sm-4">
                                            <label class="" for="ticketNumberChange">Ticket #</label>
                                            <input type="text" class="form-control" id="ticketNumberChange" aria-label="City" disabled readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="RequestedByChange">Requested By</label>
                                            <input type="text" class="form-control" id="RequestedByChange" aria-label="State" disabled readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="ticketWeightChange">Ticket Weight</label>
                                            <select class="form-select" id="ticketWeightChange">
                                                <option value='0' selected>Select Weight...</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="requestTypeChange">Service Name</label>
                                            <input type="text" class="form-control" id="requestTypeChange" aria-label="City" disabled readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="serviceForChange">Service For</label>
                                            <input type="text" class="form-control" id="serviceForChange" aria-label="State" disabled readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="" for="ticketPeriorityChange">Ticket Periority</label>
                                            <select class="form-select" id="ticketPeriorityChange">
                                                <option value='0' selected>Select Priority...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="container mt-2 mb-2">
                                    <div class="mb-3 row">
                                        <label for="assignTeamChange" class="col-sm-2 col-form-label">Setect Team</label>
                                        <div class="col-sm-6 mb-3">
                                            <select class="form-select" id="assignTeamChange">
                                                <option value="">Select Team....</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="container-fluid ">
                                    <div class="row d-flex justify-content-center"> <!-- Container Div Start  -->
                                        <div class="col-sm-6 ">
                                            <div class=" text-center mt-5" id="waitingMessageForTeamAssignMemberChange">
                                                <div class="alert alert-primary" role="alert">
                                                    There Is No Data You Can See It Yet.
                                                </div>
                                            </div>
                                            <h3 class="text-start mt-3 text-dark">Team Member</h3>
                                            <div class="teamMemberTable">
                                                <table class="main-table text-center table table-bordered mt-3 ">
                                                    <thead>
                                                        <tr>
                                                            <th hidden>ID</th>
                                                            <th>User Name</th>
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                            <th>Control</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="teamMemberChange">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class=" col-sm-6 ">
                                            <h3 class="text-start mt-3 text-dark">Selected Team Member for Ticket</h3>
                                            <div class="teamMemberTable">
                                                <table class="main-table text-center table table-bordered mt-3  ">
                                                    <thead>
                                                        <tr>
                                                            <th hidden>ID</th>
                                                            <th>User Name</th>
                                                            <th>Name</th>
                                                            <th>Description</th>
                                                            <th>Team Leader</th>
                                                            <th>Control</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="memberAssignedChange">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> <!-- Container Div End  -->
                                    <div class="col-sm-4 mt-4">
                                        <button class="btn btn-success button" id="assignTicketChange" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Team'>
                                            <i class="fa-solid fa-pen"></i>
                                            <span>Change</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Change Pop Up Form Start -->


    <!-- Add New Ticket  Pop Up Form Start -->
    <div class="modal fade" id="AddNewTicketPopup" tabindex="-1" aria-labelledby="AddNewTicketPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- New Ticket Form Start -->
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container"> <!-- Container-fluid Div Start -->
                            <div class="mb-3">
                                <h2 class="text-center" id="AddNewTicketPopupLabel">Create New Ticket</h2>
                                <div class=" container  mt-2">
                                    <form class="row d-flex justify-content-center" id="AddNewTicketForm" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                        <div class=" col-sm-5 mx-1 ">
                                            <div class="row">
                                                <!-- Start Ticket Branch Field -->
                                                <div class='col-sm-10'>
                                                    <label class="" for="UserSessionID">User Name</label>
                                                    <input type="text" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $_SESSION['user'] ?>" disabled readonly>
                                                </div>
                                                <!-- End Name  SelectBox -->
                                                <!-- Start Service Type Field Start-->
                                                <div class="col-sm-10">
                                                    <label class="" for="service" for="service">Service Type</label>
                                                    <select class="form-select service" name="service" id="service" name="service" required>
                                                        <option value="">Choes Service</option>
                                                        <?php
                                                        // // Query to retrieve a list of tables
                                                        $department = "SELECT  * FROM TICKETING.SERVICE";
                                                        $dep = oci_parse($conn, $department);
                                                        // Execute the query
                                                        oci_execute($dep);
                                                        while ($dept = oci_fetch_assoc($dep)) {
                                                            echo "<option value='" . $dept['SERVICE_NO'] . "'>" . $dept['SERVICE_NAME'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- End TService Type Field End -->
                                                <!-- Start Service Details Field Start-->
                                                <div class="col-sm-10">
                                                    <label class="" for="details" for="details">Service Details</label>
                                                    <select class="form-select details" name="details" id="details" name="details" required>
                                                        <option value="">Choose Service Detail</option>
                                                    </select>
                                                </div>
                                                <!-- End Service Details Field End -->
                                                <!-- Start Device Field Start-->
                                                <div class="col-sm-10">
                                                    <label class="" for="device" for="device">Device</label>
                                                    <select class="form-select device" name="device" id="device" disabled>
                                                        <option value="">Choose Device</option>
                                                    </select>
                                                </div>
                                                <!-- End Device End -->
                                            </div>
                                        </div>
                                        <div class=" col-sm-5 mx-1">
                                            <!-- Start Issue Description Field -->
                                            <div class='col-sm-12'>
                                                <label class="control-lable" for="description">Issue Description</label>
                                                <textarea name="description" id="description" class="description" cols="50" rows="10" placeholder="Enter issue description please..." required='required'></textarea>
                                            </div>
                                            <!-- End Issue Description Field -->
                                        </div>
                                        <div class=" col-sm-10 mx-1 ">
                                            <div class="row">
                                                <!-- Start Submit Button -->
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-primary btn-lg mt-3  addTicket" id="addTicket" name="addTicket">Create Ticket</button>
                                                    </div>
                                                </div>
                                                <!-- End Submit Button  -->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Add New Ticket Pop Up Form Start -->

    <!-- Search Ticket  Pop Up Form Start -->
    <div class="modal fade" id="SearchTicket" tabindex="-1" aria-labelledby="SearchTicketPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- New Ticket Form Start -->
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container"> <!-- Container-fluid Div Start -->
                            <div class="mb-3">
                                <h2 class="text-center" id="SearchTicketPopupLabel">Search Ticket</h2>
                                <div class=" container  mt-2">
                                    <form class="row d-flex justify-content-center" id="SearchTicketForm" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                        <div class=" col-sm-5 mx-1 ">
                                            <div class="row">
                                                <!-- Start Ticket Number Field -->
                                                <div class='col-sm-5 my-1'>
                                                    <label class="" for="SearchTicketNumber">Ticket #</label>
                                                    <input type="text" class="form-control" id="SearchTicketNumber" name="TICKET_NO" aria-label="SearchTicketNumber">
                                                </div>
                                                <!-- End  Ticket Number Field -->
                                                <!-- Start Ticket Status Field -->
                                                <div class='col-sm-5 my-1'>
                                                    <label class="" for="SearchTicketStatus">Status</label>
                                                    <select class="form-select" id="SearchTicketStatus">
                                                        <?php
                                                        $ticketStatus = "SELECT CODE, MEANING FROM TICKETING.LOOKUP_VALUES WHERE lookup_type_id = 1 ";
                                                        $status = oci_parse($conn, $ticketStatus);
                                                        oci_execute($status);
                                                        echo '<option ></option>';
                                                        while ($row = oci_fetch_array($status)) {
                                                            echo '<option value="' . $row['CODE'] . '">' . $row['MEANING'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- End Ticket Status Field -->
                                                <!-- Start Ticket Branch Field-->
                                                <div class='col-sm-5 my-1'>
                                                    <label class="" for="SearchTicketBranch">Branch</label>
                                                    <select class="form-select" id="SearchTicketBranch">
                                                        <option></option>
                                                        <option value="RYD">RYD</option>
                                                        <option value="HUF">HUF</option>
                                                        <option value="JIZ">JIZ</option>
                                                    </select>
                                                </div>
                                                <!-- End Ticket Branch Field -->
                                                <!-- Start Ticket Priority Field-->
                                                <div class='col-sm-5 my-1'>
                                                    <label class="" for="SearchTicketPriority">Priority</label>
                                                    <select class="form-select" id="SearchTicketPriority">
                                                        <?php
                                                        $ticketStatus = "SELECT CODE, MEANING FROM TICKETING.LOOKUP_VALUES WHERE lookup_type_id = 4 ";
                                                        $status = oci_parse($conn, $ticketStatus);
                                                        oci_execute($status);
                                                        echo '<option  ></option>';
                                                        while ($row = oci_fetch_array($status)) {
                                                            echo '<option value="' . $row['CODE'] . '">' . $row['MEANING'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- End Ticket Priority Field -->
                                                <!-- Start Ticket IT Time Field -->
                                                <div class='col-sm-3 my-1'>
                                                    <label class="" for="SearchITTime">IT Time</label>
                                                    <input type="number" class="form-control" id="SearchITTime" min="0" aria-label="SearchITTime" disabled>
                                                </div>
                                                <!-- End Ticket IT Time Field -->
                                                <!-- Start Ticket IT Time Per Hour Field -->
                                                <div class='col-sm-3 my-1'>
                                                    <label class="" for="SearchITTimePerHour">Hour</label>
                                                    <input type="number" class="form-control" id="SearchITTimePerHour" min="0" max="24" aria-label="SearchITTimePerHour" disabled>
                                                </div>
                                                <!-- End Ticket IT Time Per Hour Field -->
                                                <!-- Start Ticket IT Time Per Min Field -->
                                                <div class='col-sm-3 my-1'>
                                                    <label class="" for="SearchITTimePerMin">Min</label>
                                                    <input type="number" class="form-control" id="SearchITTimePerMin" min="0" max="59" aria-label="SearchITTimePerMin" disabled>
                                                </div>
                                                <!-- End Ticket IT Time Per Min Field -->
                                                <!-- Start Ticket IT Time Per Sec Field -->
                                                <div class='col-sm-3  my-1'>
                                                    <label class="" for="SearchITTimePerSec">Sec</label>
                                                    <input type="number" class="form-control" id="SearchITTimePerSec" min="0" max="59" aria-label="SearchITTimePerSec" disabled>
                                                </div>
                                                <!-- End Ticket IT Time Per Sec Field -->
                                                <!-- Start Ticket Total Time Field -->
                                                <div class='col-sm-3 my-1'>
                                                    <label class="" for="SearchTotalTime">Total Time</label>
                                                    <input type="number" class="form-control" id="SearchTotalTime" min="0" aria-label="SearchTotalTime" disabled>
                                                </div>
                                                <!-- End Ticket Total Time Field -->
                                                <!-- Start Ticket Total Time Per Hour Field -->
                                                <div class='col-sm-3 my-1'>
                                                    <label class="" for="SearchTotalTimePerHour">Hour</label>
                                                    <input type="number" class="form-control" id="SearchTotalTimePerHour" min="0" max="24" aria-label="SearchTotalTimePerHour" disabled>
                                                </div>
                                                <!-- End Ticket Total Time Per Hour Field -->
                                                <!-- Start Ticket Total Time Per Min Field -->
                                                <div class='col-sm-3 my-1'>
                                                    <label class="" for="SearchTotalTimePerMin">Min</label>
                                                    <input type="number" class="form-control" id="SearchTotalTimePerMin" min="0" max="59" aria-label="SearchTotalTimePerMin" disabled>
                                                </div>
                                                <!-- End Ticket Total Time Per Min Field -->
                                                <!-- Start Ticket Total Time Per Sec Field -->
                                                <div class='col-sm-3 my-1'>
                                                    <label class="" for="SearchTotalTimePerSec">Sec</label>
                                                    <input type="number" class="form-control" id="SearchTotalTimePerSec" min="0" max="59" aria-label="SearchTotalTimePerSec" disabled>
                                                </div>
                                                <!-- End Ticket Total Time Per Sec Field -->
                                                <!-- Start Ticket Assigned To Field -->
                                                <div class='col-sm-10 my-1'>
                                                    <label class="" for="SearchTicketAssignedTo">Assigned To</label>
                                                    <input type="text" class="form-control" id="SearchTicketAssignedTo" aria-label="SearchTicketAssignedTo">
                                                </div>
                                                <!-- End Ticket Assigned To Field -->
                                                <!-- Start Ticket Tec Issue Discription Field -->
                                                <div class='col-sm-10 my-1'>
                                                    <label class="" for="SearchTecIssueDiscription">Tec Issue Discription</label>
                                                    <input type="text" class="form-control" id="SearchTecIssueDiscription" aria-label="SearchTecIssueDiscription">
                                                </div>
                                                <!-- End Ticket Tec Issue Discription Field -->
                                                <!-- Start Ticket Tec Issue Resolution Field -->
                                                <div class='col-sm-10 my-1'>
                                                    <label class="" for="SearchTecIssueResolution">Tec Issue Resolution</label>
                                                    <input type="text" class="form-control" id="SearchTecIssueResolution" aria-label="SearchTecIssueResolution">
                                                </div>
                                                <!-- End Ticket Tec Issue Resolution Field -->
                                            </div>
                                        </div>
                                        <div class=" col-sm-5 mx-1">
                                            <!-- Start Ticket Responsible Dept Field -->
                                            <div class='col-sm-10 my-1'>
                                                <label class="" for="SearchResponsibleDept">Responsible Dept</label>
                                                <input type="text" class="form-control" id="SearchResponsibleDept" aria-label="SearchResponsibleDept" disabled>
                                            </div>
                                            <!-- End Ticket Responsible Dept Field -->
                                            <!-- Start Ticket Service Type Field -->
                                            <div class='col-sm-10 my-1'>
                                                <label class="" for="SearchServiceType">Service Type</label>
                                                <input type="text" class="form-control" id="SearchServiceType" aria-label="SearchServiceType">
                                            </div>
                                            <!-- End Ticket Service Type Field -->
                                            <!-- Start Ticket Service Details Field -->
                                            <div class='col-sm-10 my-1'>
                                                <label class="" for="SearchServiceDetails">Service Details</label>
                                                <input type="text" class="form-control" id="SearchServiceDetails" aria-label="SearchServiceDetails">
                                            </div>
                                            <!-- End Ticket Service Details Field -->
                                            <!-- Start Ticket Created By Field -->
                                            <div class='col-sm-10 my-1'>
                                                <label class="" for="SearchCreatedBy">Created By</label>
                                                <input type="text" class="form-control" id="SearchCreatedBy" aria-label="SearchCreatedBy">
                                            </div>
                                            <!-- End Ticket Created By Field -->
                                            <!-- Start Ticket Department Field -->
                                            <div class='col-sm-10 my-1'>
                                                <label class="" for="SearchDepartment">Department</label>
                                                <input type="text" class="form-control" id="SearchDepartment" aria-label="SearchDepartment">
                                            </div>
                                            <!-- End Ticket Department Field -->
                                            <!-- Start Ticket User Issue Description Field -->
                                            <div class='col-sm-10 my-1'>
                                                <label class="" for="SearchUserIsseDescription">User Issue Description</label>
                                                <input type="text" class="form-control" id="SearchUserIsseDescription" aria-label="SearchUserIsseDescription">
                                            </div>
                                            <!-- End Ticket User Issue Description Field -->
                                            <!-- Start Ticket From Date Field -->
                                            <div class='col-sm-10 my-1'>
                                                <label class="" for="SearchFromDate">From Date</label>
                                                <input type="text" class="form-control" id="SearchFromDate" aria-label="SearchFromDate" disabled>
                                            </div>
                                            <!-- End Ticket From Date Field -->
                                            <!-- Start Ticket To Date Field -->
                                            <div class='col-sm-10 my-1'>
                                                <label class="" for="SearchToDate">To Date</label>
                                                <input type="text" class="form-control" id="SearchToDate" aria-label="SearchToDate" disabled>
                                            </div>
                                            <!-- End Ticket To Date Field -->
                                        </div>
                                        <div class=" col-sm-10 mx-1 ">
                                            <div class="row">
                                                <!-- Start Submit Button -->
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-primary btn-lg mt-3  " id="SearchTicketButton"> <i class="fa-solid fa-magnifying-glass px-1"></i> Search</button>
                                                    </div>
                                                </div>
                                                <!-- End Submit Button  -->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Search Ticket Pop Up Form Start -->


    <?php

    include $inc . 'footer.php';

    ?>

    <script>
        $(function() {

            var UserSessionID = $('#UserSessionID').val(); // User Who Logged In To The System
            var USER_ID = 'USER_ID'; //  Add To Global Table To Fetch User Ticket Data 
            var noRecord = $('#recoredPerPage').val();
            var order = '';
            var sortOrder = 'ASC';
            var page = 1;
            var filter = ' ';
            var allData = [];

            $('.tran').hide(100);
            $('#mainTableTicketTransation').empty();
            $('#mainTableTicketTransation').append('Loading....');

            var startTime = new Date().getTime();
            $.ajax({
                type: 'POST',
                url: 'function.php',
                data: {
                    userNamePreResault: 'USER_ID',
                    userIDPreResault: UserSessionID,
                    order: order,
                    sortOrder: sortOrder,
                    action: 'TicketTransation'
                },
                success: function(data) {

                    allData = JSON.parse(data);
                    displayData(page, noRecord);
                    // console.log(allData);
                },
                error: function(data) {
                    console.log('from error case' + data);
                    var duration = new Date().getTime() - startTime;
                    var durationInSeconds = duration / 1000;
                    $('#time').html("<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>AJAX request took " + durationInSeconds + " seconds</h5>");
                }
            });

            function displayData(page, noRecord) {

                $('#paginationContainer').empty();
                $('#numberOfPages').empty();

                let startIndex = (page - 1) * noRecord;
                let endIndex = page * noRecord;

                let pageData = allData.slice(startIndex, endIndex);

                var tableDBody = $('#mainTableTicketTransation');

                // Clear existing rows
                tableDBody.empty();

                // Loop through the data and append rows to the table
                pageData.forEach(function(ticket) {
                    var newDRow = $('<tr>');

                    if (ticket.TICKET_STATUS == '70') {
                        newDRow.addClass('canceled-row');
                    }

                    // Populate each cell with data
                    newDRow.html(`
                        <td >${ticket.TICKET_NO}</td>
                        <td>${ticket.SERVICE_TYPE}</td>
                        <td>${ticket.SERVICE_DETAIL}</td>
                        <td>${ticket.TICKET_PERIORITY_MEANING}</td>
                        <td>${
                        ticket.TICKET_STATUS == '10' ? '<span class="badge bg-secondary">New</span>' :
                        ticket.TICKET_STATUS == '20' ? '<span class="badge bg-warning">Assign</span>' :
                        ticket.TICKET_STATUS == '30' ? '<span class="badge bg-info">Started</span>' :
                        ticket.TICKET_STATUS == '60' ? '<span class="badge bg-success">Solved</span>' :
                        ticket.TICKET_STATUS == '40' ? '<span class="badge bg-success">Confirmed</span>' :
                        ticket.TICKET_STATUS == '50' ? '<span class="badge bg-danger">Rejected</span>' :
                        ticket.TICKET_STATUS == '70' ? '<span class="badge bg-danger">Canceled</span>' :
                        ticket.TICKET_STATUS == '110' ? '<span class="badge bg-info">Sent Out</span>' :
                        ticket.TICKET_STATUS == '120' ? '<span class="badge bg-primary">Recevied</span>' :
                        ticket.TICKET_STATUS == '140' ? '<span class="badge bg-success">Confirmed by system</span>' :
                        ''
                            }</td>
                        <td hidden>${ticket.REQUEST_TYPE_NO}</td>
                        <td hidden>${ticket.SERVICE_DETAIL_NO}</td>
                        <td hidden>${ticket.TICKET_PERIORITY}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.ISSUE_DESCRIPTION}'>${ticket.ISSUE_DESCRIPTION}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TECHNICAL_ISSUE_DESCRIPTION}'>${ticket.TECHNICAL_ISSUE_DESCRIPTION}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TECHNICAL_ISSUE_RESOLUTION}'>${ticket.TECHNICAL_ISSUE_RESOLUTION}</td>
                        <td>${ticket.USERNAME}</td>
                        <td>${ticket.DEPARTMENT_NAME}</td>
                        <td>${ticket.TICKET_START_DATE}</td>
                        <td>${ticket.BRANCH_CODE}</td>
                        <td>${ticket.ASSIGNED_TO}</td>
                        <td>${ticket.TICKET_END_DATE}</td>
                        <td>${ticket.TTOTAL_TIME}</td>
                        <td>${ticket.TOTAL_TIME}</td>
                    `);

                    // Append the new row to the table body
                    tableDBody.append(newDRow);
                });

                let noPage = Math.ceil(allData.length / noRecord);

                if (page > 1) {
                    let previous = (page - 1);
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + 1 + "'>First</span></li>");
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + previous + "'>Previous</span></li>");
                }

                let count = 0;
                for (let i = page; i <= noPage - 1; i++) {
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + i + "'>" + i + "</span></li>");
                    count++;
                    if (count == 3 || i == noPage) {
                        break;
                    }
                }

                if (noPage == page) {
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>" + noPage + "</span></li>");
                } else {
                    $('#paginationContainer').append("<li class='page-item'><span class='' style='margin: 5px;' >....</span></li>");
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>" + noPage + "</span></li>");
                }

                if (page < noPage) {
                    var next = parseInt(page) + 1;
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + next + "'>Next</span></li>");
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>Last</span></li>");
                }

                $('#numberOfPages').html('<span style="color: #0069d9;"> Showing <b> ' + page + ' </b> of <b>' + noPage + ' </b> Pages : </span>');

                console.log('from success case');
                var duration = new Date().getTime() - startTime;
                var durationInSeconds = duration / 1000;
                $('#time').html("<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>AJAX request took " + durationInSeconds + " seconds</h5>");

            }

            $(document).on('click', '#ticketButton', function(e) { // Fetch Ticket Transaction Data From DB Based On User Session And Ticket Status When Click On Tickets Button
                e.preventDefault();
                // Get the filter value from the 'data-filter' attribute of the clicked button
                $('#paginationContainer').empty();
                $('#numberOfPages').empty();
                $('#mainTableTicketTransation').empty();
                $('#mainTableTicketTransation').append('Loading....');

                filter = $(this).data('filter');

                $.ajax({
                    type: 'POST',
                    url: 'function.php',
                    data: {
                        userNamePreResault: 'USER_ID',
                        userIDPreResault: UserSessionID,
                        Filter: filter,
                        order: order,
                        sortOrder: sortOrder,
                        action: 'TicketTransactionFilter'
                    },
                    success: function(data) {
                        allData = JSON.parse(data);
                        displayFilterData(page, noRecord);
                    },
                    error: function(data) {
                        console.log('from error case' + data);
                        var duration = new Date().getTime() - startTime;
                        var durationInSeconds = duration / 1000;
                        $('#time').html("<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>AJAX request took " + durationInSeconds + " seconds</h5>");
                    }
                });
            });

            function displayFilterData(page, noRecord) {

                $('#paginationContainer').empty();
                $('#numberOfPages').empty();

                let startIndex = (page - 1) * noRecord;
                let endIndex = page * noRecord;

                let pageData = allData.slice(startIndex, endIndex);
                var tableDBody = $('#mainTableTicketTransation');

                // Clear existing rows
                tableDBody.empty();

                // Loop through the data and append rows to the table
                pageData.forEach(function(ticket) {
                    var newDRow = $('<tr>');

                    if (ticket.TICKET_STATUS == '70') {
                        newDRow.addClass('canceled-row');
                    }

                    // Populate each cell with data
                    newDRow.html(`
                        <td >${ticket.TICKET_NO}</td>
                        <td>${ticket.SERVICE_TYPE}</td>
                        <td>${ticket.SERVICE_DETAIL}</td>
                        <td>${ticket.TICKET_PERIORITY_MEANING}</td>
                        <td>${
                        ticket.TICKET_STATUS == '10' ? '<span class="badge bg-secondary">New</span>' :
                        ticket.TICKET_STATUS == '20' ? '<span class="badge bg-warning">Assign</span>' :
                        ticket.TICKET_STATUS == '30' ? '<span class="badge bg-info">Started</span>' :
                        ticket.TICKET_STATUS == '60' ? '<span class="badge bg-success">Solved</span>' :
                        ticket.TICKET_STATUS == '40' ? '<span class="badge bg-success">Confirmed</span>' :
                        ticket.TICKET_STATUS == '50' ? '<span class="badge bg-danger">Rejected</span>' :
                        ticket.TICKET_STATUS == '70' ? '<span class="badge bg-danger">Canceled</span>' :
                        ticket.TICKET_STATUS == '110' ? '<span class="badge bg-info">Sent Out</span>' :
                        ticket.TICKET_STATUS == '120' ? '<span class="badge bg-primary">Recevied</span>' :
                        ticket.TICKET_STATUS == '140' ? '<span class="badge bg-success">Confirmed by system</span>' :
                        ''
                            }</td>
                        <td hidden>${ticket.REQUEST_TYPE_NO}</td>
                        <td hidden>${ticket.SERVICE_DETAIL_NO}</td>
                        <td hidden>${ticket.TICKET_PERIORITY}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.ISSUE_DESCRIPTION}'>${ticket.ISSUE_DESCRIPTION}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TECHNICAL_ISSUE_DESCRIPTION}'>${ticket.TECHNICAL_ISSUE_DESCRIPTION}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TECHNICAL_ISSUE_RESOLUTION}'>${ticket.TECHNICAL_ISSUE_RESOLUTION}</td>
                        <td>${ticket.USERNAME}</td>
                        <td>${ticket.DEPARTMENT_NAME}</td>
                        <td>${ticket.TICKET_START_DATE}</td>
                        <td>${ticket.BRANCH_CODE}</td>
                        <td>${ticket.ASSIGNED_TO}</td>
                        <td>${ticket.TICKET_END_DATE}</td>
                        <td>${ticket.TTOTAL_TIME}</td>
                        <td>${ticket.TOTAL_TIME}</td>
                    `);

                    // Append the new row to the table body
                    tableDBody.append(newDRow);
                });

                let noPage = Math.ceil(allData.length / noRecord);

                if (page > 1) {
                    let previous = (page - 1);
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + 1 + "'>First</span></li>");
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + previous + "'>Previous</span></li>");
                }

                let count = 0;
                for (let i = page; i <= noPage - 1; i++) {
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + i + "'>" + i + "</span></li>");
                    count++;
                    if (count == 3 || i == noPage) {
                        break;
                    }
                }

                if (noPage == page) {
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>" + noPage + "</span></li>");
                } else {
                    $('#paginationContainer').append("<li class='page-item'><span class='' style='margin: 5px;' >....</span></li>");
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>" + noPage + "</span></li>");
                }

                if (page < noPage) {
                    var next = parseInt(page) + 1;
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + next + "'>Next</span></li>");
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>Last</span></li>");
                }

                $('#numberOfPages').html('<span style="color: #0069d9;"> Showing <b> ' + page + ' </b> of <b>' + noPage + ' </b> Pages : </span>');

                console.log('from success case');
                var duration = new Date().getTime() - startTime;
                var durationInSeconds = duration / 1000;
                $('#time').html("<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>AJAX request took " + durationInSeconds + " seconds</h5>");

            }

            $(document).on('click', '#orderBy', function(e) { // Fetch Ticket Transaction Data From DB Based On User Session And Ticket Status When Click On Tickets Button
                e.preventDefault();
                // Get the filter value from the 'data-filter' attribute of the clicked button
                order = $(this).data('filter');
                basedon = '#' + order;

                if (sortOrder === 'ASC') {
                    sortOrder = 'DESC';
                    $(basedon).removeClass('fa-solid fa-arrow-up').addClass('fa-solid fa-arrow-down');
                } else {
                    sortOrder = 'ASC';
                    $(basedon).removeClass('fa-solid fa-arrow-down').addClass('fa-solid fa-arrow-up');
                }

                var column = $(this).index(); // Get the index of the clicked column

                // Sort the table rows based on the column data
                $('#mainTableTicketTransation').each(function() {
                    var rows = $(this).find('tr').get();
                    rows.sort(function(a, b) {
                        var aValue = $(a).children('td').eq(column).text();
                        var bValue = $(b).children('td').eq(column).text();
                        if (sortOrder === 'ASC') {
                            return aValue.localeCompare(bValue);
                        } else {
                            return bValue.localeCompare(aValue);
                        }
                    });
                    // Re-render the table rows with the sorted data
                    $.each(rows, function(index, row) {
                        $(this).parent().append(row);
                    });
                });

            });

            $('#recoredPerPage').on('change', function(e) { // Return Delegated Users Based On Team Number Function
                e.preventDefault();
                noRecord = $(this).val();
                if (filter == ' ' && Object.keys(searchParams).length === 0) {
                    displayData(page, noRecord);
                } else if (filter != ' ' && Object.keys(searchParams).length === 0) {
                    displayFilterData(page, noRecord);
                } else if (Object.keys(searchParams).length !== 0) {
                    displaySearchData(page, noRecord);
                }
            });

            $(document).on('click', '.pagination_link', function(e) {
                e.preventDefault();
                page = $(this).attr("id");

                if (filter == ' ' && Object.keys(searchParams).length === 0) {
                    displayData(page, noRecord);
                } else if (filter != ' ' && Object.keys(searchParams).length === 0) {
                    displayFilterData(page, noRecord);
                } else if (Object.keys(searchParams).length !== 0) {
                    displaySearchData(page, noRecord);
                }
            });

            ///////////////////////////////////////////***************** Search Ticket Start  *************************/////////////////////////////////////////

            // Define a global object to store search parameters
            var searchParams = {};

            // Event listener for search button click
            $(document).on('click', '#SearchTicketButton', function(e) {
                e.preventDefault();
                var startTime = new Date().getTime();
                // Update search parameters from form inputs
                searchParams = {
                    SearchTicketNumber: $('#SearchTicketNumber').val(),
                    SearchTicketStatus: $('#SearchTicketStatus').val(),
                    SearchTicketBranch: $('#SearchTicketBranch').val(),
                    SearchTicketPriority: $('#SearchTicketPriority').val(),
                    SearchITTime: $('#SearchITTime').val(),
                    SearchITTimePerHour: $('#SearchITTimePerHour').val(),
                    SearchITTimePerMin: $('#SearchITTimePerMin').val(),
                    SearchITTimePerSec: $('#SearchITTimePerSec').val(),
                    SearchITTimePerSec: $('#SearchITTimePerSec').val(),
                    SearchTotalTime: $('#SearchTotalTime').val(),
                    SearchTotalTimePerHour: $('#SearchTotalTimePerHour').val(),
                    SearchTotalTimePerMin: $('#SearchTotalTimePerMin').val(),
                    SearchTotalTimePerSec: $('#SearchTotalTimePerSec').val(),
                    SearchTicketAssignedTo: $('#SearchTicketAssignedTo').val(),
                    SearchTecIssueDiscription: $('#SearchTecIssueDiscription').val(),
                    SearchTecIssueResolution: $('#SearchTecIssueResolution').val(),
                    SearchResponsibleDept: $('#SearchResponsibleDept').val(),
                    SearchServiceType: $('#SearchServiceType').val(),
                    SearchServiceDetails: $('#SearchServiceDetails').val(),
                    SearchCreatedBy: $('#SearchCreatedBy').val(),
                    SearchDepartment: $('#SearchDepartment').val(),
                    SearchUserIsseDescription: $('#SearchUserIsseDescription').val(),
                    SearchFromDate: $('#SearchFromDate').val(),
                    SearchToDate: $('#SearchToDate').val()
                };

                $('#SearchTicket').modal('hide');
                $('#paginationContainer').empty();
                $('#numberOfPages').empty();
                $('#mainTableTicketTransation').empty();
                $('#mainTableTicketTransation').append('Loading....');


                $.ajax({
                    type: 'POST',
                    url: 'function.php',
                    data: {
                        // Include search parameters along with page number
                        searchParams: searchParams,
                        UserSessionID: UserSessionID,
                        USER_ID: 'USER_ID',
                        order: order,
                        sortOrder: sortOrder,
                        action: 'search'
                    },
                    success: function(data) {
                        $('#SearchTicketNumber').val('');
                        $('#SearchServiceType').val('');
                        $('#SearchServiceDetails').val('');
                        $('#SearchCreatedBy').val('');
                        $('#SearchDepartment').val('');
                        $('#SearchTicketStatus').val('');
                        $('#SearchTicketBranch').val('');
                        $('#SearchTicketPriority').val('');
                        $('#SearchTicketAssignedTo').val('');
                        $('#SearchTecIssueDiscription').val('');
                        $('#SearchTecIssueResolution').val('');
                        $('#SearchResponsibleDept').val('');
                        $('#SearchUserIsseDescription').val('');

                        allData = JSON.parse(data);
                        displaySearchData(page, noRecord);
                    },
                    error: function() {
                        alert("Error Fetching Data");
                        $('#SearchTicketNumber').val('');
                        $('#SearchServiceType').val('');
                        $('#SearchServiceDetails').val('');
                        $('#SearchCreatedBy').val('');
                        $('#SearchDepartment').val('');
                        $('#SearchTicketStatus').val('');
                        $('#SearchTicketBranch').val('');
                        $('#SearchTicketPriority').val('');
                        $('#SearchTicketAssignedTo').val('');
                        $('#SearchTecIssueDiscription').val('');
                        $('#SearchTecIssueResolution').val('');
                        $('#SearchResponsibleDept').val('');
                        $('#SearchUserIsseDescription').val('');
                    }
                });
            });

            function displaySearchData(page, noRecord) {

                $('#paginationContainer').empty();
                $('#numberOfPages').empty();

                let startIndex = (page - 1) * noRecord;
                let endIndex = page * noRecord;

                let pageData = allData.slice(startIndex, endIndex);
                var tableDBody = $('#mainTableTicketTransation');

                // Clear existing rows
                tableDBody.empty();

                // Loop through the data and append rows to the table
                pageData.forEach(function(ticket) {
                    var newDRow = $('<tr>');

                    if (ticket.TICKET_STATUS == '70') {
                        newDRow.addClass('canceled-row');
                    }

                    // Populate each cell with data
                    newDRow.html(`
                        <td >${ticket.TICKET_NO}</td>
                        <td>${ticket.SERVICE_TYPE}</td>
                        <td>${ticket.SERVICE_DETAIL}</td>
                        <td>${ticket.TICKET_PERIORITY_MEANING}</td>
                        <td>${
                        ticket.TICKET_STATUS == '10' ? '<span class="badge bg-secondary">New</span>' :
                        ticket.TICKET_STATUS == '20' ? '<span class="badge bg-warning">Assign</span>' :
                        ticket.TICKET_STATUS == '30' ? '<span class="badge bg-info">Started</span>' :
                        ticket.TICKET_STATUS == '60' ? '<span class="badge bg-success">Solved</span>' :
                        ticket.TICKET_STATUS == '40' ? '<span class="badge bg-success">Confirmed</span>' :
                        ticket.TICKET_STATUS == '50' ? '<span class="badge bg-danger">Rejected</span>' :
                        ticket.TICKET_STATUS == '70' ? '<span class="badge bg-danger">Canceled</span>' :
                        ticket.TICKET_STATUS == '110' ? '<span class="badge bg-info">Sent Out</span>' :
                        ticket.TICKET_STATUS == '120' ? '<span class="badge bg-primary">Recevied</span>' :
                        ticket.TICKET_STATUS == '140' ? '<span class="badge bg-success">Confirmed by system</span>' :
                        ''
                            }</td>
                        <td hidden>${ticket.REQUEST_TYPE_NO}</td>
                        <td hidden>${ticket.SERVICE_DETAIL_NO}</td>
                        <td hidden>${ticket.TICKET_PERIORITY}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.ISSUE_DESCRIPTION}'>${ticket.ISSUE_DESCRIPTION}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TECHNICAL_ISSUE_DESCRIPTION}'>${ticket.TECHNICAL_ISSUE_DESCRIPTION}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TECHNICAL_ISSUE_RESOLUTION}'>${ticket.TECHNICAL_ISSUE_RESOLUTION}</td>
                        <td>${ticket.USERNAME}</td>
                        <td>${ticket.DEPARTMENT_NAME}</td>
                        <td>${ticket.TICKET_START_DATE}</td>
                        <td>${ticket.BRANCH_CODE}</td>
                        <td>${ticket.ASSIGNED_TO}</td>
                        <td>${ticket.TICKET_END_DATE}</td>
                        <td>${ticket.TTOTAL_TIME}</td>
                        <td>${ticket.TOTAL_TIME}</td>
                    `);

                    // Append the new row to the table body
                    tableDBody.append(newDRow);
                });

                let noPage = Math.ceil(allData.length / noRecord);

                if (page > 1) {
                    let previous = (page - 1);
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + 1 + "'>First</span></li>");
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + previous + "'>Previous</span></li>");
                }

                let count = 0;
                for (let i = page; i <= noPage - 1; i++) {
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + i + "'>" + i + "</span></li>");
                    count++;
                    if (count == 3 || i == noPage) {
                        break;
                    }
                }

                if (noPage == page) {
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>" + noPage + "</span></li>");
                } else {
                    $('#paginationContainer').append("<li class='page-item'><span class='' style='margin: 5px;' >....</span></li>");
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>" + noPage + "</span></li>");
                }

                if (page < noPage) {
                    var next = parseInt(page) + 1;
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + next + "'>Next</span></li>");
                    $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>Last</span></li>");
                }

                $('#numberOfPages').html('<span style="color: #0069d9;"> Showing <b> ' + page + ' </b> of <b>' + noPage + ' </b> Pages : </span>');

                console.log('from success case');
                var duration = new Date().getTime() - startTime;
                var durationInSeconds = duration / 1000;
                $('#time').html("<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>AJAX request took " + durationInSeconds + " seconds</h5>");

            }

            ///////////////////////////////////////////***************** Search Ticket End  *************************/////////////////////////////////////////

        });
    </script>
<?php
} else {
    header('Location: index.php');
    exit();
}

echo '<div class="d-inline" id="time"></div>';
$endTime = microtime(true); // CALCULAT page loaded time

$timeTaken = $endTime - $startTime;

// $timeTaken = round($timeTaken, 5);

echo "<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>Page Loaded In: " . round($timeTaken, 2)  . " Seconds</h5>";
ob_end_flush(); // Release The Output





// fetchData(UserSessionID, USER_ID, noRecord, order, sortOrder);

//             function fetchData(UserSessionID, USER_ID, noRecord, order, sortOrder) {
//                 $('.tran').hide(100);
//                 $('#mainTableTicketTransation').empty();
//                 $('#mainTableTicketTransation').append('Loading....');

//                 var startTime = new Date().getTime();
//                 $.ajax({
//                     type: 'POST',
//                     url: 'function.php',
//                     data: {
//                         userNamePreResault: 'USER_ID',
//                         userIDPreResault: UserSessionID,
//                         order: order,
//                         sortOrder: sortOrder,
//                         action: 'TicketTransation'
//                     },
//                     success: function(data) {

//                         allData = JSON.parse(data);
//                         displayData(page);
//                         // console.log(allData);
//                     },
//                     error: function(data) {
//                         console.log('from error case' + data);
//                         var duration = new Date().getTime() - startTime;
//                         var durationInSeconds = duration / 1000;
//                         $('#time').html("<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>AJAX request took " + durationInSeconds + " seconds</h5>");
//                     }
//                 });
//             }