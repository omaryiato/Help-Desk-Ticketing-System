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
                                        <div class="card-header"><i class="fa-solid fa-layer-group pe-2"></i>All Ticket: 5</div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="card text-bg-primary mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-unlock pe-2"></i>Opend: 5</div>
                                    </div>
                                </div>


                                <div class="col-4">
                                    <div class="card text-bg-success mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-circle-check pe-2"></i>Solved: 5</div>
                                    </div>
                                </div>


                                <div class="col-4">
                                    <div class="card text-bg-danger mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-circle-xmark pe-2"></i>Rejected: 5</div>
                                    </div>
                                </div>


                                <div class="col-4">
                                    <div class="card text-bg-warning text-white mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-circle-half-stroke pe-2"></i>Pending: 5</div>

                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="card text-bg-secondary mb-3" style="max-width: 10rem;">
                                        <div class="card-header"><i class="fa-solid fa-at pe-2"></i>Assigned: 5</div>

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
                                        <td>Comments</td>
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
                                        echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                        echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                        echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                        echo "<td> 
                                        <div style='display: flex; justify-content: center; align-items: center;'>";
                                        if ($ticks["STATUS"] == 'assign') {
                                            echo "<button style='margin-right: 5px; color: white;' value='" . $ticks["ID"] . "'  class='btn btn-info  startTicket' ><i class='fa-solid fa-play' ></i></button>";
                                        }
                                        if ($ticks["STATUS"] == 'started') {
                                            echo "<button style='margin-right: 5px;' value='" . $ticks["ID"] . "' class='btn btn-success solveTicket' ><i class='fa-solid fa-check'></i></button>";
                                        }
                                        echo "<a style='margin-right: 5px;' href='?action=View&tickid=" . $ticks["ID"] . "' class='btn btn-primary text-white'><i class='fa-solid fa-eye '></i></a>
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
                                            <h2 class="text-white p-2">Issue Description:</h2>
                                            <h3></h3>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <i class="fa-solid fa-clock"></i>
                                                    <span>Send Date:</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-user"></i>
                                                    <span>Created By:</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Department:</span>
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

            <div class="container"> <!-- Container Div Start  -->
                <div class="review block"> <!-- Review Div Start  -->

                    <div class="container"> <!-- Container Div Start  -->
                        <div class="panel panel-primary" style=" border-color: white; box-shadow: 0px 10px 20px 0px rgb(0 0 0 / 20%)">
                            <div class="panel-heading" style="background-color: #00205b; border: 0;">
                                <h3 class="text-white p-3">Tickets Evaluation</h3>
                            </div>
                            <div class="panel-body"> <!-- panel-body Div Start  -->

                                <ul class="list-unstyled">
                                    <li>
                                        <i class="fa-solid fa-ticket"></i>
                                        <span>Ticket No: </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-clock"></i>
                                        <span>Response Time: </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-comment"></i>
                                        <span>Admin Response: </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-clock"></i>
                                        <span>User Evaluation: </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-reply"></i>
                                        <span>User Evaluation: </span>
                                    </li>

                                </ul>
                            </div> <!-- panel-body Div End  -->
                        </div>
                    </div> <!-- Container Div End  -->

                </div> <!-- Review Div End  -->
            </div> <!-- Container Div End  -->
            <hr class="custom-hr">
        </main>
        <!-- Ticket Information Table Start -->
    <?php
    } elseif ($action == 'Profile') {
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
                                                    <span>User Name : </span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-phone"></i>
                                                    <span>Phone Number : </span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Department :</span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-envelope"></i>
                                                    <span>Email Address : </span>
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