<?php


// Select UserID bsed on Username To return User Roles
$userNamePre = "SELECT USER_ID FROM ACT_USERS_VW WHERE USERNAME = :t_name";
$prevlag = oci_parse($conn, $userNamePre);
oci_bind_by_name($prevlag, ":t_name", $_SESSION["user"]);
oci_execute($prevlag);
$prevlegs = oci_fetch_assoc($prevlag);
$userNamePreResault = $prevlegs['USER_ID'];  // UserID

// Select User Roles Based On UserID To Display Data Based On Users Permission

$role = " SELECT ROLE_ID FROM TICKETING.TKT_REL_ROLE_USERS WHERE USER_ID = :t_id ";
$userRole = oci_parse($conn, $role);
$prev = $_SESSION['user'];
oci_bind_by_name($userRole, ":t_id", $userNamePreResault);
oci_execute($userRole);
$roles = oci_fetch_assoc($userRole); // User Roles

?>
<!-- Main Table Start -->
<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="mb-3">
            <h2 class="text-center mt-4">Ticketing Transactions</h2>
            <div class="scro container">
                <div class="table-responsive scrolls">
                    <table class="main-table text-center table table-bordered mt-3">
                        <tr>
                            <td>Ticket NO</td>
                            <td>Service Type</td>
                            <td>Service Details</td>
                            <td>!</td>
                            <td>Status</td>
                            <td>User Issue Description</td>
                            <td>Tech Issue Description</td>
                            <td>Tech Issue Resolution</td>
                            <td>Total Time</td>
                        </tr>
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
                            echo "<td>" . $ticks["TOTAL_TIME"] . "</td>\n";
                            echo "</tr>";
                        }
                        oci_free_statement($all);

                        ?>
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

        <?php




        ?>

        <div class="wrapper" id="wrapper">
            <div class="contents">
                <ul class="menu">
                    <?php
                    if ($roles['ROLE_ID'] == 2) {  // End User Permission 
                    ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='New Ticket' disabled>
                                <i class="fa-solid fa-folder-open"></i>
                                <span>New Ticket</span>
                            </button>
                        </li>

                        <li>
                            <a class="item" style='margin-right: 5px; pointer-events: none;' data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Ticket'>
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit Ticket</span>
                            </a>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cancel Ticket' disabled>
                                <i class="fa-solid fa-ban"></i>
                                <span>Cancel</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Ticket' disabled>
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <span>Finish</span>
                            </button>
                        </li>
                        <li>
                            <a class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='View Ticket'>
                                <i class="fa-solid fa-eye"></i>
                                <span>View Ticket</span>
                            </a>
                        </li>
                    <?php } ?>

                    <?php
                    if ($roles['ROLE_ID'] == 1 || $roles['ROLE_ID'] == 3) {  // GM & Supervisor Permission 
                    ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='New Ticket' disabled>
                                <i class="fa-solid fa-folder-open"></i>
                                <span>New Ticket </span>
                            </button>
                        </li>
                        <?php
                        // if ($row["TICKET_STATUS"] == 10) {
                        ?>
                        <li>
                            <a class="item" style='margin-right: 5px; pointer-events: none;' data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Ticket'>
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit Ticket</span>
                            </a>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cancel Ticket' disabled>
                                <i class="fa-solid fa-ban"></i>
                                <span>Cancel</span>
                            </button>
                        </li>
                        <li>
                            <a class="item assign" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Assign Ticket'>
                                <i class='fa-solid fa-at'></i>
                                <span>Assign</span>
                            </a>
                        </li>
                        <?php
                        // }
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Ticket' disabled>
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <span>Finish</span>
                            </button>
                        </li>
                        <li>
                            <a class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='View Ticket'>
                                <i class="fa-solid fa-eye"></i>
                                <span>View Ticket</span>
                            </a>
                        </li>
                        <?php
                        // if ($row["TICKET_STATUS"] == 20) {
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Forword Ticket' disabled>
                                <i class="fa-solid fa-share"></i>
                                <span>Forword</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Change Ticket' disabled>
                                <i class="fa-solid fa-pen"></i>
                                <span>Change</span>
                            </button>
                        </li>
                        <li>
                            <button class="item  startTicket" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Start Ticket'>
                                <i class="fa-solid fa-play"></i>
                                <span>Start</span>
                            </button>
                        </li>
                        <?php
                        // }
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Update Out Service' disabled>
                                <i class="fa-solid fa-wrench"></i>
                                <span>Update Out Service</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Received From Out' disabled>
                                <i class="fa-solid fa-inbox"></i>
                                <span>Received From Out</span>
                            </button>
                        </li>
                        <?php
                        // if ($row["TICKET_STATUS"] == 30) {
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Forword Ticket' disabled>
                                <i class="fa-solid fa-share"></i>
                                <span>Forword</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Change Ticket' disabled>
                                <i class="fa-solid fa-pen"></i>
                                <span>Change</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='SendOut Service' disabled>
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>SendOut Service</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#exampleModal" data-bs-whatever="User" data-bs-toggle='tooltip' data-bs-placement='top' title='Solve Ticket'>
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Complete</span>
                            </button>
                        </li>
                        <?php
                        // }
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Action History' disabled>
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Action History</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Chat' disabled>
                                <i class="fa-solid fa-comments"></i>
                                <span>Chat</span>
                            </button>
                        </li>
                    <?php
                    }
                    ?>
                    <?php
                    if ($roles['ROLE_ID'] == 4) {  // Technichin Permission 
                    ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='New Ticket' disabled>
                                <i class="fa-solid fa-folder-open"></i>
                                <span>New Ticket </span>
                            </button>
                        </li>
                        <?php
                        // if ($row["TICKET_STATUS"] == 10) {
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cancel Ticket' disabled>
                                <i class="fa-solid fa-ban"></i>
                                <span>Cancel</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Ticket' disabled>
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <span>Finish</span>
                            </button>
                        </li>
                        <?php
                        // }
                        ?>
                        <li>
                            <a class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='View Ticket'>
                                <i class="fa-solid fa-eye"></i>
                                <span>View Ticket</span>
                            </a>
                        </li>
                        <li>
                            <a class="item assign" id="assign" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Assign Ticket'>
                                <i class='fa-solid fa-at'></i>
                                <span>Assign</span>
                            </a>
                        </li>
                        <?php
                        // if ($row["TICKET_STATUS"] == 20) {
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Forword Ticket' disabled>
                                <i class="fa-solid fa-share"></i>
                                <span>Forword</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Change Ticket' disabled>
                                <i class="fa-solid fa-pen"></i>
                                <span>Change</span>
                            </button>
                        </li>
                        <li>
                            <button class="item  startTicket" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Start Ticket'>
                                <i class="fa-solid fa-play"></i>
                                <span>Start</span>
                            </button>
                        </li>
                        <?php
                        // }
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Update Out Service' disabled>
                                <i class="fa-solid fa-wrench"></i>
                                <span>Update Out Service</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Received From Out' disabled>
                                <i class="fa-solid fa-inbox"></i>
                                <span>Received From Out</span>
                            </button>
                        </li>
                        <?php
                        // if ($row["TICKET_STATUS"] == 30) {
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Forword Ticket' disabled>
                                <i class="fa-solid fa-share"></i>
                                <span>Forword</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Change Ticket' disabled>
                                <i class="fa-solid fa-pen"></i>
                                <span>Change</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='SendOut Service' disabled>
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>SendOut Service</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#exampleModal" data-bs-whatever="User" data-bs-toggle='tooltip' data-bs-placement='top' title='Solve Ticket'>
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Complete</span>
                            </button>
                        </li>
                        <?php
                        // }
                        ?>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Action History' disabled>
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Action History</span>
                            </button>
                        </li>
                        <li>
                            <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Chat' disabled>
                                <i class="fa-solid fa-comments"></i>
                                <span>Chat</span>
                            </button>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Any Comment For User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <!-- <div class="mb-3">
                                                    <label for="recipient-name" class="col-form-label">Recipient:</label>
                                                    <input type="text" class="form-control" id="recipient-name">
                                                            </div> -->
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Technician Issue Description:</label>
                                <textarea class="form-control issue" id="message-text"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Technician Solve Description:</label>
                                <textarea class="form-control resolution" id="message-text"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary solveTicket">Send message</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action List End  -->

    </div>
</main>
<!-- Main Table End -->
<!-- Dashboard (Main)  Page End  -->

<?php
/*
// if ($roles['ROLE_ID'] == 2) {  // End User Permission 
                            //     echo "<td > 
                            //             <div style='display: flex; justify-content: center; align-items: center;'>
                            //         <button style='margin-right: 5px;' value='" . $ticks["TICKET_NO"] . "' class='btn btn-danger deleteTicket'  data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Ticket' ><i class='fa-solid fa-trash-can '></i></button>
                            //         <a style='margin-right: 5px;' href='?action=edit&tickid=" . $ticks["TICKET_NO"] . "' class='btn btn-warning'  data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Ticket' ><i class='fa-solid fa-pen-to-square '></i></a>
                            //         <a style='margin-right: 5px;' href='?action=View&tickid=" . $ticks["TICKET_NO"] . "' class='btn btn-primary text-white'  data-bs-toggle='tooltip' data-bs-placement='top' title='View Ticket' ><i class='fa-solid fa-eye '></i></a>";
                            //     echo "</div>
                            //         </td>\n";
                            // } else { // Technition, Supervisor, Application Manager Permission
                                echo "<td > 
                                <div style='display: flex; justify-content: center; align-items: center;'>";

                                if ($ticks["TICKET_STATUS"] == '10') {  // If Ticket Status == New Show Assign Ticket Button
                                    echo "<a style='margin-right: 5px;' href='?action=Assign&tickid=" . $ticks["TICKET_NO"] . "' class='btn btn-warning'  data-bs-toggle='tooltip' data-bs-placement='top' title='Assign Ticket' ><i class='fa-solid fa-at'></i></a>";
                                }
                                if ($ticks["TICKET_STATUS"] == '10') { // If Ticket Status == New Show Start Ticket Button
                                    echo "<button style='margin-right: 5px; color: white;' value='" . $ticks["TICKET_NO"] . "'  class='btn btn-info startTicket'  data-bs-toggle='tooltip' data-bs-placement='top' title='Start Ticket' ><i class='fa-solid fa-play' ></i></button>";
                                }
                                if ($ticks["TICKET_STATUS"] == '30') {  // If Ticket Status == Started Show Solve Ticket Button
                        ?>
                                    <!-- <button class='btn btn-success' style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#exampleModal" data-bs-whatever="User" data-bs-toggle='tooltip' data-bs-placement='top' title='Solve Ticket'><i class='fa-solid fa-check'></i></button> -->

                        <?php
                                }
                                if ($ticks["TICKET_STATUS"] == '10') {  // If Ticket Status == New Show Cancele Ticket Button
                                    echo "<button style='margin-right: 5px;' value='" . $ticks["TICKET_NO"] . "' class='btn btn-danger  rejectTicket'  data-bs-toggle='tooltip' data-bs-placement='top' title='Reject Ticket' ><i class='fa-solid fa-circle-xmark'></i></button>";
                                }
                                // View Ticket Information Button Show it Always
                                echo "
                                <a style='margin-right: 5px;' href='?action=View&tickid=" . $ticks["TICKET_NO"] . "' class='btn btn-primary text-white'  data-bs-toggle='tooltip' data-bs-placement='top' title='View Ticket' ><i class='fa-solid fa-eye '></i></a>";
                                echo "</div>
                                </td>\n";
                            }
                            echo "</tr>\n";
                        
                            */
?>