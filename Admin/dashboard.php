<?php

/*
    ================================================
    == Tracking Ticketing Page
    == You Can  Edit | Delete | Response Ticket From Here 
    ================================================
*/

ob_start(); // Output Buffering Start

session_start();

if (isset($_SESSION['member'])) {

    $pageTitle = 'Home Page';

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

    include 'init.php';

    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = 'Manage';
    }

    if ($action == 'Manage') {

        $userName = $_SESSION['member'];

        // Select All Users Except Admin 


        $allTicket = "SELECT 
                            ID 
                        FROM 
                            users  
                        WHERE 
                        name = :v_user ";

        $all = oci_parse($conn, $allTicket);

        // Bind the variables
        oci_bind_by_name($all, ":v_user", $userName);

        // Execute the query
        oci_execute($all);

        // Fetch the result
        oci_fetch($all);


        $id = oci_result($all, 'ID');

        $ticketInfo = "SELECT 
                            tickets.*, users.name AS Username, service_details.service_details 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id
                        LEFT OUTER JOIN
                            service_details
                        ON
                            tickets.service_details = service_details.id
                        WHERE 
                            TEAM_MEMBER_ASSIGNED_ID = :v_member
                        ORDER BY 
                            tickets.ID DESC";

        $ticket = oci_parse($conn, $ticketInfo);


        // Bind the variables
        oci_bind_by_name($ticket, ":v_member", $id);

        // Execute the query
        oci_execute($ticket);

        // Fetch All Data From Tickets Table

        if (!empty(oci_execute($ticket))) {

?>
            <!-- Main Table Start -->
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="mb-3">

                        <div class="container mt-4 m-auto">

                            <div class="row">

                                <div class="col-4">
                                    <div class="card text-bg-info text-white mb-3" style="max-width: 10rem;">
                                        <?php
                                        $allTickets = "SELECT 
                                                            * 
                                                        FROM 
                                                            tickets
                                                        WHERE TEAM_MEMBER_ASSIGNED_ID = :v_member ";
                                        $alltick = oci_parse($conn, $allTickets);
                                        oci_bind_by_name($alltick, ":v_member", $id);

                                        // Execute the query
                                        oci_execute($alltick);

                                        while ($row = oci_fetch_assoc($alltick)) {
                                            // Process each row
                                        }

                                        $allRows = oci_num_rows($alltick);
                                        ?>

                                        <div class="card-header"><i class="fa-solid fa-layer-group pe-2"></i>All Ticket: <?php echo $allRows ?></div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="card text-bg-primary mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-unlock pe-2"></i>Opend: <?php echo getcount('started') ?> </div>
                                    </div>
                                </div>


                                <div class="col-4">
                                    <div class="card text-bg-success mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-circle-check pe-2"></i>Solved: <?php echo getcount('solved') ?></div>
                                    </div>
                                </div>


                                <div class="col-4">
                                    <div class="card text-bg-danger mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-circle-xmark pe-2"></i>Rejected: <?php echo getcount('rejected') ?></div>
                                    </div>
                                </div>


                                <div class="col-4">
                                    <div class="card text-bg-success text-white mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-circle-half-stroke pe-2"></i>Completed: <?php echo getcount('completed') ?></div>

                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="card text-bg-secondary mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-at pe-2"></i>Assigned: <?php echo getcount('assign') ?></div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-center mt-4">Manage Tickets</h2>
                        <div class="container">
                            <div class="table-responsive">
                                <table class="main-table text-center table table-bordered mt-3">
                                    <tr>
                                        <td>Ticket NO</td>
                                        <td>User Name</td>
                                        <td>Description</td>
                                        <td>Service Details</td>
                                        <td>Status</td>
                                        <td>User Comments</td>
                                        <td>Admin Comments</td>
                                        <td>Tickets Date</td>
                                        <td>Update Date</td>
                                        <td>Created By</td>
                                        <td>Control</td>
                                    </tr>
                                    <?php
                                    while ($ticks = oci_fetch_assoc($ticket)) {
                                        echo "<tr>\n";
                                        echo "<td>" . $ticks["ID"] . "</td>\n";
                                        echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                        echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                        echo "<td>" . $ticks["SERVICE_DETAILS"] . "</td>\n";
                                        echo "<td>";
                                        if ($ticks["STATUS"] == 'initial') {
                                            echo '<span class="badge bg-primary ">' . $ticks["STATUS"] . '</span>';
                                        } elseif ($ticks["STATUS"] == 'assign') {
                                            echo '<span class="badge bg-warning">' . $ticks["STATUS"] . '</span>';
                                        } elseif ($ticks["STATUS"] == 'started') {
                                            echo '<span class="badge bg-info">' . $ticks["STATUS"] . '</span>';
                                        } elseif ($ticks["STATUS"] == 'solved') {
                                            echo '<span class="badge bg-success">' . $ticks["STATUS"] . '</span>';
                                        } elseif ($ticks["STATUS"] == 'completed') {
                                            echo '<span class="badge bg-success">' . $ticks["STATUS"] . '</span>';
                                        } elseif ($tick["STATUS"] == 'rejected') {
                                            echo '<span class="badge bg-danger">' . $tick["STATUS"] . '</span>';
                                        }
                                        echo "</td>\n";
                                        echo "<td>" . $ticks["COMMENTS"] . "</td>\n";
                                        echo "<td>" . $ticks["ADMIN_COMMENTS"] . "</td>\n";
                                        echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                        echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                        echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                        echo "<td> 
                                        <div style='display: flex; justify-content: center; align-items: center;'>";
                                        if ($ticks["STATUS"] == 'assign') {
                                            echo "<button style='margin-right: 5px; color: white;' value='" . $ticks["ID"] . "'  class='btn btn-info  startTicket' data-bs-toggle='tooltip' data-bs-placement='top' title='Start Ticket' ><i class='fa-solid fa-play' ></i></button>";
                                        }
                                        if ($ticks["STATUS"] == 'started') {
                                    ?>
                                            <button class='btn btn-success' style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#exampleModal" data-bs-whatever="User" data-bs-toggle='tooltip' data-bs-placement='top' title='Solve Ticket'><i class='fa-solid fa-check'></i></button>

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
                                                                    <label for="message-text" class="col-form-label">Message:</label>
                                                                    <textarea class="form-control comment" id="message-text"></textarea>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="button" value='<?php echo $ticks["ID"] ?>' class="btn btn-primary solveTicket">Send message</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                        echo "<a style='margin-right: 5px;' href='?action=View&tickid=" . $ticks["ID"] . "' class='btn btn-primary text-white'  data-bs-toggle='tooltip' data-bs-placement='top' title='View Ticket'  ><i class='fa-solid fa-eye '></i></a>
                                        </div>
                                        </td>\n";
                                        echo "</tr>\n";
                                    }
                                    oci_free_statement($ticket);
                                    ?>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Main Table End -->
        <?php

        }
    } elseif ($action == 'Open') {

        $userName = $_SESSION['member'];

        $ticketStatus = 'started';

        // Select All Users Except Admin 


        $allTicket = "SELECT 
                            ID 
                        FROM 
                            users  
                        WHERE 
                        name = :v_user ";

        $all = oci_parse($conn, $allTicket);

        // Bind the variables
        oci_bind_by_name($all, ":v_user", $userName);

        // Execute the query
        oci_execute($all);

        // Fetch the result
        oci_fetch($all);


        $id = oci_result($all, 'ID');

        $startedTicket = "SELECT 
                            tickets.*, users.name AS Username, service_details.service_details 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id
                        LEFT OUTER JOIN
                            service_details
                        ON
                            tickets.service_details = service_details.id
                        WHERE 
                            TEAM_MEMBER_ASSIGNED_ID = :v_member
                        AND
                            tickets.STATUS = :t_status
                        ORDER BY 
                            tickets.ID DESC";

        $started = oci_parse($conn, $startedTicket);

        // Bind the variables
        oci_bind_by_name($started, ":v_member", $id);
        oci_bind_by_name($started, ":t_status", $ticketStatus);

        // Execute the query
        oci_execute($started);
        ?>
        <!-- Open Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center mt-3">Opened Tickets</h2>
                    <div class="container">
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered mt-3">
                                <tr>
                                    <td>Ticket NO</td>
                                    <td>User Name</td>
                                    <td>Description</td>
                                    <td>Service Details</td>
                                    <td>Comments</td>
                                    <td>Tickets Date</td>
                                    <td>Update Date</td>
                                    <td>Created By</td>
                                </tr>
                                <?php
                                while ($ticks = oci_fetch_assoc($started)) {

                                    echo "<tr>\n";
                                    echo "<td>" . $ticks["ID"] . "</td>\n";
                                    echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                    echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                    echo "<td>" . $ticks["SERVICE_DETAILS"] . "</td>\n";
                                    echo "<td>" . $ticks["COMMENTS"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                    echo "</tr>\n";
                                }

                                oci_free_statement($started);
                                ?>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <!-- Open Table End -->
    <?php
    } elseif ($action == 'Rejected') {

        $userName = $_SESSION['member'];

        $ticketStatus = 'rejected';

        // Select All Users Except Admin 


        $allTicket = "SELECT 
                            ID 
                        FROM 
                            users  
                        WHERE 
                        name = :v_user ";

        $all = oci_parse($conn, $allTicket);

        // Bind the variables
        oci_bind_by_name($all, ":v_user", $userName);

        // Execute the query
        oci_execute($all);

        // Fetch the result
        oci_fetch($all);


        $id = oci_result($all, 'ID');

        $rejectedTicket = "SELECT 
                            tickets.*, users.name AS Username, service_details.service_details 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id
                        LEFT OUTER JOIN
                            service_details
                        ON
                            tickets.service_details = service_details.id
                        WHERE 
                            TEAM_MEMBER_ASSIGNED_ID = :v_member
                        AND
                            tickets.STATUS = :t_status
                        ORDER BY 
                            tickets.ID DESC";

        $rejected = oci_parse($conn, $rejectedTicket);

        // Bind the variables
        oci_bind_by_name($rejected, ":v_member", $id);
        oci_bind_by_name($rejected, ":t_status", $ticketStatus);

        // Execute the query
        oci_execute($rejected);
    ?>
        <!-- Rejected Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center mt-3">Rejected Tickets</h2>
                    <div class="container">
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered mt-3">
                                <tr>
                                    <td>Ticket NO</td>
                                    <td>User Name</td>
                                    <td>Description</td>
                                    <td>Service Details</td>
                                    <td>Comments</td>
                                    <td>Tickets Date</td>
                                    <td>Update Date</td>
                                    <td>Created By</td>
                                </tr>
                                <?php
                                while ($ticks = oci_fetch_assoc($rejected)) {
                                    echo "<tr>\n";
                                    echo "<td>" . $ticks["ID"] . "</td>\n";
                                    echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                    echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                    echo "<td>" . $ticks["SERVICE_DETAILS"] . "</td>\n";
                                    echo "<td>" . $ticks["COMMENTS"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                    echo "</tr>\n";
                                }
                                oci_free_statement($rejected);
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Rejected Table End -->

    <?php
    } elseif ($action == 'Assigned') {

        $userName = $_SESSION['member'];

        $ticketStatus = 'assign';

        // Select All Users Except Admin 


        $allTicket = "SELECT 
                            ID 
                        FROM 
                            users  
                        WHERE 
                        name = :v_user ";

        $all = oci_parse($conn, $allTicket);

        // Bind the variables
        oci_bind_by_name($all, ":v_user", $userName);

        // Execute the query
        oci_execute($all);

        // Fetch the result
        oci_fetch($all);


        $id = oci_result($all, 'ID');

        $assignedTicket = "SELECT 
                            tickets.*, users.name AS Username, service_details.service_details 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id
                        LEFT OUTER JOIN
                            service_details
                        ON
                            tickets.service_details = service_details.id
                        WHERE 
                            TEAM_MEMBER_ASSIGNED_ID = :v_member
                        AND
                            tickets.STATUS = :t_status
                        ORDER BY 
                            tickets.ID DESC";

        $assign = oci_parse($conn, $assignedTicket);

        // Bind the variables
        oci_bind_by_name($assign, ":v_member", $id);
        oci_bind_by_name($assign, ":t_status", $ticketStatus);

        // Execute the query
        oci_execute($assign);
    ?>
        <!-- Assign Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center mt-3">Assigned Tickets</h2>
                    <div class="container">
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered mt-3">
                                <tr>
                                    <td>Ticket NO</td>
                                    <td>User Name</td>
                                    <td>Description</td>
                                    <td>Service Details</td>
                                    <td>Comments</td>
                                    <td>Tickets Date</td>
                                    <td>Update Date</td>
                                    <td>Created By</td>
                                </tr>
                                <?php
                                while ($ticks = oci_fetch_assoc($assign)) {
                                    echo "<tr>\n";
                                    echo "<td>" . $ticks["ID"] . "</td>\n";
                                    echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                    echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                    echo "<td>" . $ticks["SERVICE_DETAILS"] . "</td>\n";
                                    echo "<td>" . $ticks["COMMENTS"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                    echo "</tr>\n";
                                }
                                oci_free_statement($assign);
                                ?>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main> <!-- Assign Table End -->
    <?php
    } elseif ($action == 'Solved') {

        $userName = $_SESSION['member'];

        $ticketStatus = 'solved';

        // Select All Users Except Admin 

        $allTicket = "SELECT 
                            ID 
                        FROM 
                            users  
                        WHERE 
                        name = :v_user ";

        $all = oci_parse($conn, $allTicket);

        // Bind the variables
        oci_bind_by_name($all, ":v_user", $userName);

        // Execute the query
        oci_execute($all);

        // Fetch the result
        oci_fetch($all);

        $id = oci_result($all, 'ID');

        $solvedTicket = "SELECT 
                            tickets.*, users.name AS Username, service_details.service_details 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id
                        LEFT OUTER JOIN
                            service_details
                        ON
                            tickets.service_details = service_details.id
                        WHERE 
                            TEAM_MEMBER_ASSIGNED_ID = :v_member
                        AND
                            tickets.STATUS = :t_status
                        ORDER BY 
                            tickets.ID DESC";

        $solved = oci_parse($conn, $solvedTicket);

        // Bind the variables
        oci_bind_by_name($solved, ":v_member", $id);
        oci_bind_by_name($solved, ":t_status", $ticketStatus);

        // Execute the query
        oci_execute($solved);
    ?>
        <!-- Solved Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center mt-3">Solved Tickets</h2>
                    <div class="container">
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered mt-3">
                                <tr>
                                    <td>Ticket NO</td>
                                    <td>User Name</td>
                                    <td>Description</td>
                                    <td>Service Details</td>
                                    <td>Comments</td>
                                    <td>Tickets Date</td>
                                    <td>Update Date</td>
                                    <td>Created By</td>
                                </tr>
                                <?php
                                while ($ticks = oci_fetch_assoc($solved)) {

                                    echo "<tr>\n";
                                    echo "<td>" . $ticks["ID"] . "</td>\n";
                                    echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                    echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                    echo "<td>" . $ticks["SERVICE_DETAILS"] . "</td>\n";
                                    echo "<td>" . $ticks["COMMENTS"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                    echo "</tr>\n";
                                }

                                oci_free_statement($solved);
                                ?>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <!-- Solved Table End -->
    <?php
    } elseif ($action == 'View') {

        $ticketid = isset($_GET['tickid']) && is_numeric($_GET['tickid']) ?   intval($_GET['tickid']) : 0;

        $ticketInfo = "SELECT
                            tickets.*, users.name AS Username, service_type.type, service_details.service_details 
                        FROM 
                            tickets
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id
                        INNER JOIN
                            service_type
                        ON
                            tickets.service_type = service_type.id
                        INNER JOIN
                            service_details
                        ON
                            tickets.service_details = service_details.id
                        WHERE tickets.ID = :t_id";
        $info = oci_parse($conn, $ticketInfo);

        oci_bind_by_name($info, ':t_id', $ticketid);

        oci_execute($info);

        $infos = oci_fetch_assoc($info);

    ?>
        <!-- Ticket Information Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">

                    <h2 class="text-center mt-3">View Tickets</h2>

                    <div class="container"> <!-- Container Div Start  -->

                        <!-- Display Ticket Information Form Start -->
                        <form class="form-horizontal" action="" method="">
                            <input type="hidden" name="tickid" value="">

                            <div class="information block"> <!-- Information Div Start  -->
                                <div class="container">
                                    <div class="panel panel-primary" style=" border-color: white; box-shadow: 0px 10px 20px 0px rgb(0 0 0 / 20%)">
                                        <div class="panel-heading" style="background-color: #00205b; border: 0;">
                                            <h2 class="text-white p-2">Ticket Information</h2>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-unstyled ">

                                                <li>
                                                    <i class="fa-solid fa-user"></i>
                                                    <span>Created By: <?php echo " " . $infos['USERNAME'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-clock"></i>
                                                    <span>Send Date: <?php echo " " . $infos['CREATED_DATE'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Service Type: <?php echo " " .  $infos['TYPE'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-circle-info"></i>
                                                    <span>Service Details: <?php echo " " . $infos['SERVICE_DETAILS'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-box-tissue"></i>
                                                    <span>Issue Description: <?php echo " " . $infos['DESCRIPTION'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-comment"></i>
                                                    <span>Employee Comment: <?php echo " " . $infos['COMMENTS'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-comment"></i>
                                                    <span>Admin Comment:</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-battery-half"></i>
                                                    <span>Ticket Status: <?php echo " " . $infos['STATUS'] ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- Information Div End  -->
                        </form>
                        <!-- Display Ticket Information Form End -->
                    </div> <!-- Container Div End  -->
                </div>
            </div>


            <hr class="custom-hr">
        </main>
        <!-- Ticket Information Table Start -->
    <?php
    } elseif ($action == 'Profile') {

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?   intval($_GET['userid']) : 0;

        $ticketInfo = "SELECT
                            users.*, service_type.type
                        FROM 
                            users
                        INNER JOIN
                            service_type
                        ON
                            users.department = service_type.type
                        WHERE users.ID = :t_id";
        $info = oci_parse($conn, $ticketInfo);

        oci_bind_by_name($info, ':t_id', $userid);

        oci_execute($info);

        $infos = oci_fetch_assoc($info);
    ?>
        <!-- Content Profile Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center mt-3">Manage Profile</h2>
                    <div class="container">
                        <form class="form-horizontal" action="" method="">
                            <input type="hidden" name="tickid" value="">

                            <div class="information block">
                                <div class="container">
                                    <div class="panel panel-primary" style=" border-color: white; box-shadow: 0px 10px 20px 0px rgb(0 0 0 / 20%)">
                                        <div class="panel-heading" style="background-color: #00205b; border: 0;">
                                            <h2 class="text-white p-2">User Information</h2>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-unstyled">

                                                <li>
                                                    <i class="fa-solid fa-user"></i>
                                                    <span>User Name : <?php echo " " . $infos['NAME'] ?></span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-phone"></i>
                                                    <span>Phone Number : <?php echo " " . $infos['PHONE_NUMBER'] ?></span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Department : <?php echo " " . $infos['DEPARTMENT'] ?></span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-envelope"></i>
                                                    <span>Email Address : <?php echo " " . $infos['EMAIL'] ?> </span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-clock"></i>
                                                    <span>Register Date : <?php echo " " . $infos['CREATED_DATE'] ?> </span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-battery-half"></i>
                                                    <span>Status : <?php echo " " . $infos['STATUS'] ?> </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <a href='?action=EditInfo' class='btn btn-success mt-5 mb-5'><i class='fa-solid fa-pen-to-square pe-2'></i>Edit Information</a>
                    </div>
                </div>
            </div>
        </main>
        <!-- Content Profile end -->
    <?php
    } elseif ($action == 'EditInfo') {
    ?>
        <!-- Content Profile Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">

                    <h2 class="text-center mt-3 mb-3">Edit Profile</h2>
                    <div class="container">

                        <!-- Edit User Information Form Start -->
                        <form class="form-horizontal" action="" method="">

                            <!-- Start User Name Field -->
                            <div class="form-group form-group-lg ">
                                <label class="col-sm-2 control-lable mt-3 mb-2" for="">User Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="" class=" form-control input" placeholder=" Enter your name please...">
                                </div>
                            </div>
                            <!-- End User Name Field -->

                            <!-- Start Phone Number Field -->
                            <div class="form-group form-group-lg ">
                                <label class="col-sm-2 control-lable mt-3 mb-2" for="">Phone Number</label>
                                <div class="col-sm-10">
                                    <input type="text" name="number" value="" oninput="restrictInput(event)" class=" form-control input" placeholder=" Enter your phone number please...">
                                </div>
                            </div>
                            <!-- End Phone Number Field -->

                            <!-- Start Submit Button -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Update" class="btn btn-success btn-lg mt-3 mb-2">
                                </div>
                            </div>
                            <!-- End Submit Button  -->
                        </form>
                        <!-- Edit User Information Form End -->
                    </div>
                </div>
            </div>
        </main>
        <!-- Content Profile end -->
<?php
    }
    include $inc . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush(); // Release The Output

?>