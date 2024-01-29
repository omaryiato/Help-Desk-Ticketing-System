<?php

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

?>
<!-- Main Table Start -->

<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="mb-1">
            <h2 class="text-center mt-2">Ticketing Transactions</h2>
            <div class="scroll-wrapper">
                <div class="scroll">
                    <table class="hiddenList scro">
                        <thead>
                            <tr>
                                <th>Ticket NO</th>
                                <th>Service Type</th>
                                <th>Service Details</th>
                                <th>!</th>
                                <th>Status</th>
                                <th hidden>Request Type No</th>
                                <th hidden>Service Detail No</th>
                                <th hidden>Periority No</th>
                                <th>User Issue Description</th>
                                <th>Tech Issue Description</th>
                                <th>Tech Issue Resolution</th>
                                <th>Created By</th>
                                <th>Department</th>
                                <th>Creation Date</th>
                                <th>Branch</th>
                                <th>Assigned To</th>
                                <th>End Date</th>
                                <th>Total IT Time</th>
                                <th>Total Time</th>
                            </tr>
                        </thead>
                        <tbody id="mainTableTicketTransation">
                            <?php
                            while ($ticks = oci_fetch_assoc($all)) {
                                echo "<tr";
                                if ($ticks["TICKET_STATUS"] == '70') {
                                    echo ' class="canceled-row"';
                                }
                                echo ">\n";
                                echo "<td>" . $ticks["TICKET_NO"] . "</td>\n";
                                echo "<td>" . $ticks["SERVICE_TYPE"] . "</td>\n";
                                echo "<td>" . $ticks["SERVICE_DETAIL"] . "</td>\n";
                                echo "<td>" . $ticks["TICKET_PERIORITY_MEANING"] . "</td>\n";
                                echo "<td>";
                                if ($ticks["TICKET_STATUS"] == '10') {
                                    echo '<span class="badge bg-secondary ">New</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '20') {
                                    echo '<span class="badge bg-warning">Assign</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '30') {
                                    echo '<span class="badge bg-info">Started</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '60') {
                                    echo '<span class="badge bg-success">Solved</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '40') {
                                    echo '<span class="badge bg-success">Confirm</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '50') {
                                    echo '<span class="badge bg-danger">Rejected</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '70') {
                                    echo '<span class="badge bg-danger">Canceled</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '100') {
                                    echo '<span class="badge bg-danger">Included</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '90') {
                                    echo '<span class="badge bg-danger">Excluded</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '110') {
                                    echo '<span class="badge bg-danger">Send Out</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '120') {
                                    echo '<span class="badge bg-danger">Received</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '130') {
                                    echo '<span class="badge bg-danger">Exclude All</span>';
                                } elseif ($ticks["TICKET_STATUS"] == '140') {
                                    echo '<span class="badge bg-success">Confirmed by system</span>';
                                }
                                echo "</td>\n";
                                echo "<td hidden>" . $ticks["REQUEST_TYPE_NO"] . "</td>\n";
                                echo "<td hidden>" . $ticks["SERVICE_DETAIL_NO"] . "</td>\n";
                                echo "<td hidden>" . $ticks["TICKET_PERIORITY"] . "</td>\n";
                                echo "<td>" . $ticks["ISSUE_DESCRIPTION"] . "</td>\n";
                                echo "<td>" . $ticks["TECHNICAL_ISSUE_DESCRIPTION"] . "</td>\n";
                                echo "<td>" . $ticks["TECHNICAL_ISSUE_RESOLUTION"] . "</td>\n";
                                echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                echo "<td>" . $ticks["DEPARTMENT_NAME"] . "</td>\n";
                                echo "<td>" . $ticks["TICKET_START_DATE"] . "</td>\n";
                                echo "<td>" . $ticks["BRANCH_CODE"] . "</td>\n";
                                echo "<td>" . $ticks["ASSIGNED_TO"] . "</td>\n";
                                echo "<td>" . $ticks["TICKET_END_DATE"] . "</td>\n";
                                echo "<td>" . $ticks["TTOTAL_TIME"] . "</td>\n";
                                echo "<td>" . $ticks["TOTAL_TIME"] . "</td>\n";
                                echo "</tr>";
                            }
                            oci_free_statement($all);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Ticket Filtering Section Start ( Filter Ticket Based On Ticket Status)-->
            <div class="container-fluid mt-5 m-auto" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                <div class="row">
                    <div class="col-2">
                        <div class="card text-bg-secondary text-white mb-3" style="max-width: 15rem;">
                            <div class="card-header">
                                <a href="?action=Pending" class="tickets"><i class="fa-solid fa-plus pe-2"></i>New Ticket: <?php echo getcount(10) ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card text-bg-primary mb-3" style="max-width: 17rem;">
                            <div class="card-header ">
                                <a href="?action=Open" class="tickets"><i class="fa-solid fa-envelope-open pe-2"></i>Started working on ticket: <?php echo getcount(30) ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="card text-bg-primary mb-3" style="max-width: 15rem;">
                            <div class="card-header">
                                <a href="?action=Sent" class="tickets"><i class="fa-solid fa-paper-plane pe-2"></i>Sent Out: <?php echo getcount(110) ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card text-bg-success mb-3" style="max-width: 17rem;">
                            <div class="card-header">
                                <a href="?action=Confirmed" class="tickets"><i class="fa-solid fa-at pe-2"></i>Requester confirmation: <?php echo getcount(40) ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="card text-bg-danger mb-3" style="max-width: 15rem;">
                            <div class="card-header">
                                <a href="?action=Canceled" class="tickets"><i class="fa-solid fa-circle-xmark pe-2"></i>Canceled Ticket: <?php echo getcount(70) ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="card text-bg-warning mb-3" style="max-width: 15rem;">
                            <div class="card-header">
                                <a href="?action=Assigned" class="tickets"><i class="fa-solid fa-at pe-2"></i>Ticket assigned: <?php echo getcount(20) ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card text-bg-success mb-3" style="max-width: 17rem;">
                            <div class="card-header">
                                <a href="?action=Solved" class="tickets"><i class="fa-solid fa-circle-check pe-2"></i>Solved by technician: <?php echo getcount(60) ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="card text-bg-info mb-3" style="max-width: 15rem;">
                            <div class="card-header">
                                <a href="?action=Received" class="tickets"><i class="fa-solid fa-at pe-2"></i>Received: <?php echo getcount(120) ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card text-bg-danger mb-3" style="max-width: 15rem;">
                            <div class="card-header">
                                <a href="?action=Rejected" class="tickets"><i class="fa-solid fa-at pe-2"></i>Rejected By requester: <?php echo getcount(50) ?></a>
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

                            <div class="card-header"><i class="fa-solid fa-layer-group pe-2"></i>Total: <?php echo $allRows ?></div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Ticket Filtering Section End -->
        </div>

        <!-- Action List Start  -->

        <div class="wrapper" id="wrapper">
            <div class="contents">
                <span style="padding: 10px;">Ticket No#:</span>
                <span id="returnTicketNumber"></span>
                <input type="hidden" id="UserSessionID" value="<?php echo  $userNamePreResault ?>" readonly>
                <input type="hidden" id="UserRole" value="<?php echo  $roles['ROLE_ID'] ?>" readonly>
                <ul class="menu" id="actionTicketTransactionList">

                </ul>
            </div>
        </div>

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
                        <button type="button" class="btn btn-primary solveTicket" value="<?php echo  $prevlegs['USER_ID'] ?>">Send message</button>
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
                                                <input type="text" class="form-control" id="ticketNumber" aria-label="City" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="RequestedBy">Requested By</label>
                                                <input type="text" class="form-control" id="RequestedBy" aria-label="State" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="ticketWeight">Ticket Weight</label>
                                                <select class="form-select" id="ticketWeight">
                                                    <option value='0' selected>Select Weight...</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="requestType">Service Name</label>
                                                <input type="text" class="form-control" id="requestType" aria-label="City" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="serviceFor">Service For</label>
                                                <input type="text" class="form-control" id="serviceFor" aria-label="State" readonly>
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
                                                <input type="hidden" id="UserSessionID" value="<?php echo  $userNamePreResault ?>">
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
                                                <input type="text" class="form-control" id="EditTicketNumber" aria-label="City" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="" for="EditRequestedBy">Requested By</label>
                                                <input type="text" class="form-control" id="EditRequestedBy" aria-label="State" readonly>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <label class="" for="EditrequestType">Service Name</label>
                                                <input type="text" class="form-control" id="EditrequestType" aria-label="City" readonly>
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
                                        <h3 class="text-start mt-3 mb-4 text-dark">Ticket Information</h3>
                                        <div class="row g-3" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                            <div class="col-sm-4">
                                                <label class="" for="ticketNumberChange">Ticket #</label>
                                                <input type="text" class="form-control" id="ticketNumberChange" aria-label="City" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="RequestedByChange">Requested By</label>
                                                <input type="text" class="form-control" id="RequestedByChange" aria-label="State" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="ticketWeightChange">Ticket Weight</label>
                                                <select class="form-select" id="ticketWeightChange">

                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="requestTypeChange">Service Name</label>
                                                <input type="text" class="form-control" id="requestTypeChange" aria-label="City" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="serviceForChange">Service For</label>
                                                <input type="text" class="form-control" id="serviceForChange" aria-label="State" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="ticketPeriorityChange">Ticket Periority</label>
                                                <select class="form-select" id="ticketPeriorityChange">

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container mt-2 mb-2">
                                        <div class="mb-3 row">
                                            <label for="assignTeamChange" class="col-sm-2 col-form-label">Setect Team</label>
                                            <div class="col-sm-6 mb-3">
                                                <select class="form-select" id="assignTeamChange">

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
                                                <i class='fa-solid fa-at'></i>
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


    </div>
</main>

<!-- Main Table End -->
<!-- Dashboard (Main)  Page End  -->