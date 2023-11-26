<?php

/*
    ================================================
    == Manage Ticketing Page
    == You Can Edit | Delete | Assign | Response Ticket From Here 
    ================================================
*/

ob_start(); // Output Buffering Start

session_start();

if (isset($_SESSION['leader'])) {

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

        // Select All Users Except Admin 

        $allTicket = "SELECT 
                            tickets.*, users.name AS Username 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id 
                        ORDER BY 
                            tickets.ID DESC";

        $all = oci_parse($conn, $allTicket);

        // Execute the query
        oci_execute($all);

        // Fetch All Data From Tickets Table

        if (!empty(oci_execute($all))) {

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
                                        <td>Ticket_NO</td>
                                        <td>User Name</td>
                                        <td>Ticket Name</td>
                                        <td>Description</td>
                                        <td>Team Member</td>
                                        <td>Status</td>
                                        <td>Comments</td>
                                        <td>Tickets Date</td>
                                        <td>UPDATED_DATE</td>
                                        <td>Created By</td>
                                        <td>Tickets Date</td>
                                        <td>Control</td>
                                    </tr>

                                    <?php
                                    while ($ticks = oci_fetch_assoc($all)) {

                                        echo "<tr>\n";

                                        echo "<td>" . $ticks["ID"] . "</td>\n";
                                        echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                        echo "<td>" . $ticks["NAME"] . "</td>\n";
                                        echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                        echo "<td>" . $ticks["TEAM_MEMBER_ASSIGNED_ID"] . "</td>\n";
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
                                        } elseif ($ticks["STATUS"] == 'rejected') {
                                            echo '<span class="badge bg-danger">' . $ticks["STATUS"] . '</span>';
                                        }
                                        echo "</td>\n";
                                        echo "<td>" . $ticks["COMMENTS"] . "</td>\n";
                                        echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                        echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                        echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                        echo "<td>" . $ticks["UPDATED_BY"] . "</td>\n";
                                        echo "<td > 
                                        <div style='display: flex; justify-content: center; align-items: center;'>
                                            <a style='margin-right: 5px;' href='?action=Assign&tickid=" . $ticks["ID"] . "' class='btn btn-warning'><i class='fa-solid fa-at'></i></a>
                                            <button style='margin-right: 5px;' value='" . $ticks["ID"] . "' class='btn btn-danger  rejectTicket'><i class='fa-solid fa-circle-xmark'></i></button>";
                                        if ($ticks["STATUS"] == 'initial') {
                                            echo "<button style='margin-right: 5px; color: white;' value='" . $ticks["ID"] . "'  class='btn btn-info startTicket' ><i class='fa-solid fa-play' ></i></button>";
                                        } else {
                                            echo "<button style='margin-right: 5px; color: white;' value='" . $ticks["ID"] . "'  class='btn btn-info' disabled ><i class='fa-solid fa-play' ></i></button>";
                                        }
                                        if ($ticks["STATUS"] == 'started') {
                                            echo "<button style='margin-right: 5px;' value='" . $ticks["ID"] . "' class='btn btn-success solveTicket' ><i class='fa-solid fa-check'></i></button>";
                                        } else {
                                            echo "<button style='margin-right: 5px;' value='" . $ticks["ID"] . "' class='btn btn-success' disabled><i class='fa-solid fa-check'></i></button>";
                                        }
                                        echo "<a style='margin-right: 5px;' href='?action=View&tickid=" . $ticks["ID"] . "' class='btn btn-primary text-white'><i class='fa-solid fa-eye '></i></a>";
                                        echo "</div>
                                        </td>\n";

                                        echo "</tr>\n";
                                    }

                                    oci_free_statement($all);
                                    ?>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
            <!-- Main Table End -->
            <!-- Dashboard (Main)  Page End  -->

        <?php
        }
    } elseif ($action == 'Open') {

        $ticketStatus = 'started';

        // Select Started Ticket

        $startedTicket = "SELECT 
                            tickets.*, users.name AS Username 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id 
                        WHERE 
                            tickets.STATUS = :t_status
                        ORDER BY 
                            tickets.ID DESC";

        $start = oci_parse($conn, $startedTicket);
        oci_bind_by_name($start, ":t_status", $ticketStatus);

        // Execute the query
        oci_execute($start);

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
                                    <td>Ticket_NO</td>
                                    <td>User Name</td>
                                    <td>Ticket Name</td>
                                    <td>Description</td>
                                    <td>Team Member</td>
                                    <td>Comments</td>
                                    <td>Tickets Date</td>
                                    <td>UPDATED_DATE</td>
                                    <td>Created By</td>
                                </tr>
                                <?php
                                while ($ticks = oci_fetch_assoc($start)) {

                                    echo "<tr>\n";
                                    echo "<td>" . $ticks["ID"] . "</td>\n";
                                    echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                    echo "<td>" . $ticks["NAME"] . "</td>\n";
                                    echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                    echo "<td>" . $ticks["TEAM_MEMBER_ASSIGNED_ID"] . "</td>\n";
                                    echo "<td>" . $ticks["COMMENTS"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                    echo "</tr>\n";
                                }

                                oci_free_statement($start);
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <!-- Open Table End -->
    <?php
    } elseif ($action == 'Solved') {

        $ticketStatus = 'solved';

        // Select Started Ticket

        $solvedTicket = "SELECT 
                            tickets.*, users.name AS Username 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id 
                        WHERE 
                            tickets.STATUS = :t_status
                        ORDER BY 
                            tickets.ID DESC";

        $solved = oci_parse($conn, $solvedTicket);
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
                                    <td>Ticket_NO</td>
                                    <td>User Name</td>
                                    <td>Ticket Name</td>
                                    <td>Description</td>
                                    <td>Team Member</td>
                                    <td>Comments</td>
                                    <td>Tickets Date</td>
                                    <td>UPDATED_DATE</td>
                                    <td>Created By</td>
                                </tr>
                                <?php
                                while ($ticks = oci_fetch_assoc($solved)) {

                                    echo "<tr>\n";
                                    echo "<td>" . $ticks["ID"] . "</td>\n";
                                    echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                    echo "<td>" . $ticks["NAME"] . "</td>\n";
                                    echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                    echo "<td>" . $ticks["TEAM_MEMBER_ASSIGNED_ID"] . "</td>\n";
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
    } elseif ($action == 'Rejected') {

        $ticketStatus = 'rejected';

        // Select Started Ticket

        $rejectedTicket = "SELECT 
                            tickets.*, users.name AS Username 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id 
                        WHERE 
                            tickets.STATUS = :t_status
                        ORDER BY 
                            tickets.ID DESC";

        $rejected = oci_parse($conn, $rejectedTicket);
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
                                    <td>Ticket_NO</td>
                                    <td>User Name</td>
                                    <td>Ticket Name</td>
                                    <td>Description</td>
                                    <td>Team Member</td>
                                    <td>Comments</td>
                                    <td>Tickets Date</td>
                                    <td>UPDATED_DATE</td>
                                    <td>Created By</td>
                                </tr>
                                <?php
                                while ($ticks = oci_fetch_assoc($rejected)) {

                                    echo "<tr>\n";
                                    echo "<td>" . $ticks["ID"] . "</td>\n";
                                    echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                    echo "<td>" . $ticks["NAME"] . "</td>\n";
                                    echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                    echo "<td>" . $ticks["TEAM_MEMBER_ASSIGNED_ID"] . "</td>\n";
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
    } elseif ($action == 'Pending') {

        $ticketStatus = 'initial';

        // Select Started Ticket

        $pendingTicket = "SELECT 
                            tickets.*, users.name AS Username 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id 
                        WHERE 
                            tickets.STATUS = :t_status
                        ORDER BY 
                            tickets.ID DESC";

        $pending = oci_parse($conn, $pendingTicket);
        oci_bind_by_name($pending, ":t_status", $ticketStatus);

        // Execute the query
        oci_execute($pending);
    ?>
        <!-- Pending Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center mt-3">Pending Tickets</h2>
                    <div class="container">
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered mt-3">
                                <tr>
                                    <td>Ticket_NO</td>
                                    <td>User Name</td>
                                    <td>Ticket Name</td>
                                    <td>Description</td>
                                    <td>Team Member</td>
                                    <td>Comments</td>
                                    <td>Tickets Date</td>
                                    <td>UPDATED_DATE</td>
                                    <td>Created By</td>

                                </tr>
                                <?php
                                while ($ticks = oci_fetch_assoc($pending)) {

                                    echo "<tr>\n";
                                    echo "<td>" . $ticks["ID"] . "</td>\n";
                                    echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                    echo "<td>" . $ticks["NAME"] . "</td>\n";
                                    echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                    echo "<td>" . $ticks["TEAM_MEMBER_ASSIGNED_ID"] . "</td>\n";
                                    echo "<td>" . $ticks["COMMENTS"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                    echo "</tr>\n";
                                }

                                oci_free_statement($pending);
                                ?>


                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <!-- Pending Table End -->
    <?php
    } elseif ($action == 'Assigned') {

        $ticketStatus = 'assign';

        // Select Started Ticket

        $assignedTicket = "SELECT 
                            tickets.*, users.name AS Username 
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id 
                        WHERE 
                            tickets.STATUS = :t_status
                        ORDER BY 
                            tickets.ID DESC";

        $assigned = oci_parse($conn, $assignedTicket);
        oci_bind_by_name($assigned, ":t_status", $ticketStatus);

        // Execute the query
        oci_execute($assigned);
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
                                    <td>Ticket_NO</td>
                                    <td>User Name</td>
                                    <td>Ticket Name</td>
                                    <td>Description</td>
                                    <td>Team Member</td>
                                    <td>Comments</td>
                                    <td>Tickets Date</td>
                                    <td>UPDATED_DATE</td>
                                    <td>Created By</td>
                                </tr>
                                <?php
                                while ($ticks = oci_fetch_assoc($assigned)) {

                                    echo "<tr>\n";
                                    echo "<td>" . $ticks["ID"] . "</td>\n";
                                    echo "<td>" . $ticks["USERNAME"] . "</td>\n";
                                    echo "<td>" . $ticks["NAME"] . "</td>\n";
                                    echo "<td>" . $ticks["DESCRIPTION"] . "</td>\n";
                                    echo "<td>" . $ticks["TEAM_MEMBER_ASSIGNED_ID"] . "</td>\n";
                                    echo "<td>" . $ticks["COMMENTS"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["UPDATED_DATE"] . "</td>\n";
                                    echo "<td>" . $ticks["CREATED_BY"] . "</td>\n";
                                    echo "</tr>\n";
                                }

                                oci_free_statement($assigned);
                                ?>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main> <!-- Assign Table End -->
    <?php
    } elseif ($action == 'User') {

        // Select Started Ticket

        $users = "SELECT * FROM users ORDER BY ID DESC";

        $user = oci_parse($conn, $users);

        // Execute the query
        oci_execute($user);
    ?>
        <!-- Manage Users Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center mt-3">Manage Users</h2>
                    <div class="container">
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered mt-3">
                                <tr>
                                    <td>#ID</td>
                                    <td>Username</td>
                                    <td>Email</td>
                                    <td>Department</td>
                                    <td>Registerd Date</td>
                                    <td>User Type</td>
                                    <td>Status</td>
                                    <td>Control</td>
                                </tr>

                                <?php
                                while ($member = oci_fetch_assoc($user)) {

                                    echo "<tr>\n";
                                    echo "<td>" . $member["ID"] . "</td>\n";
                                    echo "<td>" . $member["NAME"] . "</td>\n";
                                    echo "<td>" . $member["TYPE"] . "</td>\n";
                                    echo "<td>" . $member["EMAIL"] . "</td>\n";
                                    echo "<td>" . $member["DEPARTMENT"] . "</td>\n";
                                    echo "<td>" . $member["IP_ADDRESS"] . "</td>\n";
                                    echo "<td>" . $member["PHONE_NUMBER"] . "</td>\n";
                                    echo "<td > 
                                        <div style='display: flex; justify-content: center; align-items: center;'>
                                            <a style='margin-right: 5px;' href='?action=Edit&tickid=" . $member["ID"] . "' class='btn btn-warning'><i class='fa-solid fa-pen-to-square'></i></a>
                                            <button style='margin-right: 5px;' value='" . $member["ID"] . "' class='btn btn-danger  deleteUser'><i class='fa-solid fa-trash-can'></i></button>";
                                    echo "</div>
                                        </td>\n";
                                    echo "</tr>\n";
                                }

                                oci_free_statement($user);
                                ?>

                            </table>
                        </div>
                        <a class="btn btn-primary " href="?action=Add" style="margin-top: 50px; margin-bottom: 30px;"><i class="fa-solid fa-plus"></i> Add New Mermber </a>
                    </div>
                </div>
            </div>

        </main>
        <!-- Manage Users Table End -->
    <?php
    } elseif ($action == 'Add') {
    ?>

        <!-- Manage Users Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center">Add New Member</h2>
                    <div class="container">
                        <!-- Add Users Form Start -->
                        <form class="form-horizontal" action="" method="">

                            <!-- Start Username Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" name="username" class="form-control" placeholder=" Enter username to access please..." autocomplete="off" required="required">
                                </div>
                            </div>
                            <!-- End Username Field -->

                            <!-- Start Password Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Password</label>
                                <div class="col-sm-10">
                                    <input id="password" type="password" name="password" class="password form-control" placeholder="Your password must be hard & complex..." autocomplete="new-password" required="required">
                                    <!-- <i id="showpass" class="showpass fa-solid fa-eye "></i> -->
                                </div>
                            </div>
                            <!-- End Password Field -->

                            <!-- Start Email Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" placeholder="  Enter a valide email please..." autocomplete="off">
                                </div>
                            </div>
                            <!-- End Email Field -->

                            <!-- Start Department SelectBox -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Department</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="department" id="">
                                        <option value="0">Choes Department</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End  Department  SelectBox -->

                            <!-- Start User Type SelectBox -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">User Type</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="usertype" id="">
                                        <option value="0">Choes User Type</option>
                                        <option value='admin'>admin</option>
                                        <option value='member'>member</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End  User Type  SelectBox -->

                            <!-- Start Submit Button -->
                            <div class="form-group form-group-lg mt-3 mb-1">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Add Users" class="btn btn-success btn-lg">
                                </div>
                            </div>
                            <!-- End Submit Button  -->
                        </form>
                        <!-- Add Users Form End -->
                    </div>
                </div>
            </div>
        </main>
        <!-- Manage Users Table End -->

    <?php
    } elseif ($action == 'Edit') {
    ?>
        <!-- Manage Users Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center">Edit User Information</h2>
                    <div class="container">
                        <form class="form-horizontal" action="" method="">
                            <input type="hidden" name="id" value="">
                            <!-- Start Username Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" name="username" value="" class="form-control" placeholder="Enter new username please..." autocomplete="off" required="required">
                                </div>
                            </div>
                            <!-- End Username Field -->

                            <!-- Start Password Field -->
                            <div class="form-group form-group-lg">

                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Password</label>

                                <div class="col-sm-10">

                                    <input type="hidden" name="oldpassword" value=" ">

                                    <input type="password" name="newpassword" class="form-control" placeholder="Enter new password please... (optional)" autocomplete="new-password">
                                </div>
                            </div>
                            <!-- End Password Field -->

                            <!-- Start Email Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" value="" class="form-control" placeholder="Enter new email please..." autocomplete="off">
                                </div>
                            </div>
                            <!-- End Email Field -->

                            <!-- Start Department SelectBox -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Department</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="department" id="">
                                        <option value="0">Choes Your Department</option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <!-- End  Department  SelectBox -->

                            <!-- Start User Type SelectBox -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">User Type</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="usertype" id="">
                                        <option value="0">Choes User Type</option>
                                        <option value='admin'></option>
                                        <option value='member'></option>
                                    </select>
                                </div>
                            </div>
                            <!-- End User Type  SelectBox -->

                            <!-- Start Submit Button -->
                            <div class="form-group form-group-lg mt-3 mb-1">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Update" class="btn btn-success btn-lg">
                                </div>
                            </div>
                            <!-- End Submit Button  -->
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <!-- Manage Users Table End -->
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
        </main> <!-- Content Profile End -->
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
                                    <input type="text" name="name" value="" class=" form-control input" placeholder="Enter your name please...">
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
        </main> <!-- Content Profile End -->
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

                            <div class="information block">
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
                            </div>
                        </form>
                        <!-- Display Ticket Information Form End -->
                    </div> <!-- Container Div Start  -->
                </div>
            </div>

            <div class="container"> <!-- Container Div Start  -->
                <div class="review block"> <!-- Review Div Start  -->

                    <div class="container"> <!-- Container Div Start  -->
                        <div class="panel panel-primary" style=" border-color: white; box-shadow: 0px 10px 20px 0px rgb(0 0 0 / 20%)">
                            <div class="panel-heading" style="background-color: #00205b; border: 0;">
                                <h3 class="text-white p-3">Tickets Evaluation</h3>
                            </div>
                            <div class="panel-body">

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
                            </div>
                        </div>
                    </div> <!-- Container Div End  -->

                </div> <!-- Review Div End  -->
            </div> <!-- Container Div End  -->

            <hr class="custom-hr">

        </main>
        <!-- Ticket Information Table End -->
    <?php
    } elseif ($action == 'Assign') {  // Assigned Ticket Page 

        $ticketid = isset($_GET['tickid']) && is_numeric($_GET['tickid']) ?   intval($_GET['tickid']) : 0;
    ?>
        <!-- Assign Ticket  Start -->
        <main class="content px-3 py-2"> <!-- Main Start -->
            <div class="container-fluid"> <!-- Container-fluid Div Start -->
                <div class="mb-3">

                    <h2 class="text-center mt-3">Assign Tickets</h2>

                    <div class="container"> <!-- Container Div Start  -->

                        <form class="form-horizontal" action="" method="POST" style="margin: auto;">

                            <!-- Start Department SelectBox -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="department">Department</label>
                                <div class="col-sm-10">
                                    <select class="form-select department" name="department" id="department" required>
                                        <option value="0">Choes Department</option>
                                        <option value="IT">IT Services</option>
                                        <option value="EBS">EBS</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Department  SelectBox -->

                            <!-- Start Name SelectBox -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="user">User Name</label>
                                <div class="col-sm-10">
                                    <select class="form-select user" name="user" id="user" required>
                                        <option value="0">Choes Your Name</option>

                                    </select>
                                </div>
                            </div>
                            <!-- End Name  SelectBox -->


                            <!-- Start Issue Description Field -->
                            <!-- <input type="text" name="id" value="" class=" form-control  description" hidden> -->
                            <!-- End Issue Description Field -->

                            <!-- Start Submit Button -->
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" value="<?php echo $ticketid ?>" class="btn btn-primary btn-lg mt-3  assignTicket" name="assignTicket">Assign</button>
                                </div>
                            </div>
                            <!-- End Submit Button  -->
                        </form>
                    </div> <!-- Container Div End  -->
                </div>
            </div><!-- Container-fluid Div End  -->
        </main> <!-- Main End -->
        <!-- Assign Ticket Info End -->

<?php
    }
    include $inc . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush(); // Release The Output
?>