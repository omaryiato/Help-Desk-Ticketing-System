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
                                echo "<tr>\n";
                                echo "<td>" . $ticks["TICKET_NO"] . "</td>\n";
                                echo "<td>" . $ticks["SERVICE_TYPE"] . "</td>\n";
                                echo "<td>" . $ticks["SERVICE_DETAIL"] . "</td>\n";
                                echo "<td>" . $ticks["TICKET_PERIORITY"] . "</td>\n";
                                echo "<td>";
                                if ($ticks["TICKET_STATUS"] == '10') {
                                    echo '<span class="badge bg-primary ">New</span>';
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
                                    echo '<span class="badge bg-danger">Confirmed by system</span>';
                                }
                                echo "</td>\n";
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
                        <div class="card text-bg-warning text-white mb-3" style="max-width: 15rem;">
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
                        <div class="card text-bg-secondary mb-3" style="max-width: 15rem;">
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
                            $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V ";

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
                <span>Ticket No#:</span>
                <span id="returnTicketNumber"></span>
                <ul class="menu" id="actionTicketTransactionList">
                    <input type="hidden" id="UserSessionID" value="<?php echo  $userNamePreResault ?>">

                </ul>
            </div>
        </div>

        <!-- Success Pop Up Form Start -->
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
                                <label for="message-text" class="col-form-label">Technician Issue Description:</label>
                                <textarea class="form-control issue" id="message-text"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Technician Solve Resolution:</label>
                                <textarea class="form-control resolution" id="message-text"></textarea>
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
        <!-- Success Pop Up Form Start -->

        <!-- Assign Pop Up Form Start -->
        <div class="modal fade" id="assignPopup" tabindex="-1" aria-labelledby="assignPopupLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
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
                                    <h2 class="text-center mt-3 mb-5" id="assignPopupLabel">Assign Tickets</h2>
                                    <div class="container mb-4 mt-4">
                                        <h3 class="text-start mt-3 mb-4 text-dark">Ticket Information</h3>
                                        <div class="row g-3" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                            <div class="col-sm-4">
                                                <label class="" for="autoSizingSelect">Ticket #</label>
                                                <input type="text" class="form-control" id="ticketNumber" aria-label="City" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="autoSizingSelect">Requested By</label>
                                                <input type="text" class="form-control" id="RequestedBy" aria-label="State" readonly>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="" for="autoSizingSelect">Ticket Weight</label>
                                                <select class="form-select" id="autoSizingSelect">
                                                    <option selected>Choose...</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
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
                                                <label class="" for="autoSizingSelect">Ticket Periority</label>
                                                <select class="form-select" id="autoSizingSelect">
                                                    <option selected>Choose...</option>
                                                    <option value="U">U</option>
                                                    <option value="H">H</option>
                                                    <option value="M">M</option>
                                                    <option value="L">L</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container mt-4 mb-4">
                                        <div class="mb-3 row">
                                            <label for="select" class="col-sm-2 col-form-label">Setect Team</label>
                                            <div class="col-sm-6 mb-3">
                                                <select class="form-select" id="assignTeam">
                                                    <option>Choose Team...</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container">
                                        <div class="omar" style="display: flex; justify-content:space-around; max-width: 1200px;"> <!-- Container Div Start  -->
                                            <div class="scroll" style="width: 500px; margin-right: 15px;">
                                                <h3 class="text-start mt-3 text-dark">Team Member</h3>
                                                <table class="main-table text-center table table-bordered mt-3 ">
                                                    <thead>
                                                        <tr>
                                                            <th>User Name</th>
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                            <th>Control</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="teamMember">
                                                        <tr>
                                                            <td class="userName" hidden>omar</td>
                                                            <td class="name" hidden>omar alkhateeb</td>
                                                            <td hidden>active</td>
                                                            <td hidden><button class='btn btn-warning include'>Include</button></td>
                                                        </tr>
                                                    </tbody>

                                                </table>
                                            </div>

                                            <div class="scroll" style="width: 500px; margin-right: 15px">
                                                <h3 class="text-start mt-3 text-dark">Selected Team Member for Ticket</h3>
                                                <table class="main-table text-center table table-bordered mt-3  ">
                                                    <thead>
                                                        <tr>
                                                            <th>User Name</th>
                                                            <th>Name</th>
                                                            <th>Description</th>
                                                            <th>Team Leader</th>
                                                            <th>Control</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="memberAssigned">
                                                        <tr>
                                                            <td class="userName" hidden>omar</td>
                                                            <td class="name" hidden>omar alkhateeb</td>
                                                            <td hidden></td>
                                                            <td hidden>
                                                                <div class="check"><input type="checkbox"></div>
                                                            </td>
                                                            <td hidden><button class='btn btn-warning exclude'>Exclude</button></td>
                                                        </tr>
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
                                            <div class="teamMemberTable">
                                                <h2 class="text-center" id="actionHistoryLabel">Action History</h2>
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

    </div>
</main>

<!-- Main Table End -->
<!-- Dashboard (Main)  Page End  -->