<?php

/*
    ================================================
    == Manage Ticketing Page
    == You Can Edit | Delete | Assign | Response Ticket From Here 
    ================================================
*/

ob_start(); // Output Buffering Start

session_start();

if (isset($_SESSION['user'])) {

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

    include 'init.php';  // This File Contain ( Header, Footer, Navbar, Function) File

    // This action variable to redirect pages and move between them using GET
    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = 'Manage';
    }

    if ($action == 'Manage') {              // Home page thats contain Ticket Transation based on user permission 

        InsertUserID(); // This Function To Insert user permission to table global_temp_table until you can see the tickets
        // select all tickets based on user permission
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) { // If the table is not empty you can see the tickets else you will see else message 
            include 'main-page.php'; // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'Open') {          // Open Ticket page thats contain Started Ticket based on user permission

        InsertUserID(); // This Function To Insert user permission to table global_temp_table until you can see the tickets

        $ticketStatus = 30;

        // Select Started Ticket
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE ticket_status = :t_status ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        oci_bind_by_name($all, ":t_status", $ticketStatus);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) {  // If the table is not empty you can see the tickets else you will see else message
            include 'main-page.php';  // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'Solved') {        // Solve Ticket page thats contain Solved Ticket based on user permission

        InsertUserID();  // This Function To Insert user permission to table global_temp_table until you can see the tickets

        $ticketStatus = 60;

        // Select Solved Ticket
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE ticket_status = :t_status ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        oci_bind_by_name($all, ":t_status", $ticketStatus);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) {   // If the table is not empty you can see the tickets else you will see else message
            include 'main-page.php';   // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'Rejected') {      // Reject Ticket page thats contain Rejected Ticket based on user permission

        InsertUserID();  // This Function To Insert user permission to table global_temp_table until you can see the tickets

        $ticketStatus = 50;

        // Select Rejected Ticket
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE ticket_status = :t_status ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        oci_bind_by_name($all, ":t_status", $ticketStatus);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) {  // If the table is not empty you can see the tickets else you will see else message
            include 'main-page.php';  // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'Pending') {       // New Ticket page thats contain New Ticket based on user permission

        InsertUserID();  // This Function To Insert user permission to table global_temp_table until you can see the tickets

        $ticketStatus = 10;

        // Select New Ticket
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE ticket_status = :t_status ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        oci_bind_by_name($all, ":t_status", $ticketStatus);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) {  // If the table is not empty you can see the tickets else you will see else message
            include 'main-page.php';  // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'Assigned') {      // Assign Ticket page thats contain Assigned Ticket based on user permission

        InsertUserID();  // This Function To Insert user permission to table global_temp_table until you can see the tickets

        $ticketStatus = 20;

        // Select Assigned Ticket
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE ticket_status = :t_status ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        oci_bind_by_name($all, ":t_status", $ticketStatus);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) {   // If the table is not empty you can see the tickets else you will see else message
            include 'main-page.php';   // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'Sent') {          // Sent Out Ticket page thats contain Sent Out Ticket based on user permission

        InsertUserID();  // This Function To Insert user permission to table global_temp_table until you can see the tickets

        $ticketStatus = 110;

        // Select Sent Out Ticket
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE ticket_status = :t_status ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        oci_bind_by_name($all, ":t_status", $ticketStatus);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) {   // If the table is not empty you can see the tickets else you will see else message
            include 'main-page.php';  // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'Confirmed') {     // Confirme Ticket page thats contain Confirmed Ticket based on user permission

        InsertUserID();  // This Function To Insert user permission to table global_temp_table until you can see the tickets

        $ticketStatus = 40;

        // Select Confirmed Ticket
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE ticket_status = :t_status ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        oci_bind_by_name($all, ":t_status", $ticketStatus);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) {  // If the table is not empty you can see the tickets else you will see else message
            include 'main-page.php';  // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'Canceled') {      // Cancel Ticket page thats contain Canceled Ticket based on user permission

        InsertUserID();  // This Function To Insert user permission to table global_temp_table until you can see the tickets

        $ticketStatus = 70;

        // Select Canceled Ticket
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE ticket_status = :t_status ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        oci_bind_by_name($all, ":t_status", $ticketStatus);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) {   // If the table is not empty you can see the tickets else you will see else message
            include 'main-page.php';  // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'Received') {      // Receive Ticket page thats contain Received Ticket based on user permission

        InsertUserID();   // This Function To Insert user permission to table global_temp_table until you can see the tickets

        $ticketStatus = 120;

        // Select Received Ticket
        $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE ticket_status = :t_status ORDER BY TICKET_NO DESC";
        $all = oci_parse($conn, $allTicket);
        oci_bind_by_name($all, ":t_status", $ticketStatus);
        // Execute the query
        oci_execute($all);

        if (!empty(oci_execute($all))) {   // If the table is not empty you can see the tickets else you will see else message
            include 'main-page.php';    // this file include the main table thats contain tickets information
        } else {
            echo    '<div class="container">
                        <div class="alert alert-primary" role="alert" style="margin-top: 20px;">
                            There is no ticket to display yet!
                        </div>
                    </div>';
        }
    } elseif ($action == 'User') {
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
                                    <td>User Type</td>
                                    <td>Email</td>
                                    <td>Phone Number</td>
                                    <td>Registerd Date</td>
                                    <td>Created By</td>
                                    <td>Control</td>
                                </tr>

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
                                    <input type="text" name="username" class="form-control  username" placeholder=" Enter username to access please..." autocomplete="off" required="required">
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
                                    <input type="email" name="email" class="form-control  email" placeholder="  Enter a valide email please..." autocomplete="off">
                                </div>
                            </div>
                            <!-- End Email Field -->

                            <!-- Start Phone Number Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Phone Number</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" oninput="restrictInput(event)" class="form-control  phone" placeholder="  Enter Phone number please..." autocomplete="off">
                                </div>
                            </div>
                            <!-- End Phone Number Field -->

                            <!-- Start Phone Number Field -->
                            <input type="text" name="admin" class="form-control  admin" hidden>
                            <!-- End Phone Number Field -->

                            <!-- Start User Type SelectBox -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="usertype">User Type</label>
                                <div class="col-sm-10">
                                    <select class="form-select usertype" name="usertype" id="usertype">
                                        <option value="0">Choes User Type</option>
                                        <option value='employee'>Employee</option>
                                        <option value='team_member_assigned'>Team member assigned</option>
                                        <option value='team_leader'>Team leader</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End  User Type  SelectBox -->

                            <!-- Start User Status SelectBox -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="userStatus">User Status</label>
                                <div class="col-sm-10">
                                    <select class="form-select userStatus" name="userStatus" id="userStatus" required>
                                        <option value="">Choes User Status</option>
                                        <option value='Active'>Active</option>
                                        <option value='Deactive'>Deactive</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End  User Status  SelectBox -->

                            <!-- Start Submit Button -->
                            <div class="form-group form-group-lg mt-3 mb-1">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-lg addUsers">Add Users</button>
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
                            <input type="hidden" class="id" name="id">

                            <!-- Start Username Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" name="username" class="form-control  username" placeholder="Enter new username please..." autocomplete="off" required="required">
                                </div>
                            </div>
                            <!-- End Username Field -->

                            <!-- Start Email Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control   email" placeholder="Enter new email please..." autocomplete="off">
                                </div>
                            </div>
                            <!-- End Email Field -->

                            <!-- Start Phone Number Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Phone Number</label>
                                <div class="col-sm-10">
                                    <input type="text" name="phone" oninput="restrictInput(event)" class="form-control   phone" placeholder="Enter new email please..." autocomplete="off">
                                </div>
                            </div>
                            <!-- End Phone Number Field -->

                            <!-- Start User Status SelectBox -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="usertype">User Status</label>
                                <div class="col-sm-10">
                                    <select class="form-select usertype" name="usertype" id="usertype">
                                        <option value="">Choes User Status</option>
                                        <option value='Active'>Active</option>

                                        <option value='Deactive'>Deactive</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End User Status SelectBox -->

                            <!-- Start Submit Button -->
                            <div class="form-group form-group-lg mt-3 mb-1">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success btn-lg updateTicket">Update</button>
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
                                                    <span>User Name : <?php echo " " . $infos['NAME'] ?></span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-phone"></i>
                                                    <span>Phone Number : </span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Department : </span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-envelope"></i>
                                                    <span>Email Address : </span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-clock"></i>
                                                    <span>Register Date :</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-battery-half"></i>
                                                    <span>Status : </span>
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
        <!-- Content Profile End -->
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
        </main>
        <!-- Content Profile End -->
    <?php
    } elseif ($action == 'View') {          // View Ticket page thats contain Ticket Details Information
        InsertUserID();  // This Function To Insert user permission to table global_temp_table until you can see the tickets
        $ticketid = isset($_GET['tickid']) && is_numeric($_GET['tickid']) ?   intval($_GET['tickid']) : 0;  // GET Ticket Number
        // Select Ticket Information Based On Ticket Number
        $ticketInfo = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V WHERE TICKET_NO = :t_id";
        $info = oci_parse($conn, $ticketInfo);
        oci_bind_by_name($info, ':t_id', $ticketid);
        oci_execute($info);
        $infos = oci_fetch_assoc($info);
    ?>
        <!-- Ticket Information Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center mt-3">Ticket Information</h2>
                    <div class="container"> <!-- Container Div Start  -->
                        <!-- Display Ticket Information Form Start -->
                        <form class="form-horizontal" action="" method="">
                            <input type="hidden" name="tickid" value="<?php echo $infos['TICKET_NO'] ?>">
                            <div class="information block">
                                <div class="container">
                                    <div class="panel panel-primary" style=" border-color: white; box-shadow: 0px 10px 20px 0px rgb(0 0 0 / 20%)">
                                        <div class="panel-heading" style="background-color: #00205b; border: 0;">
                                            <h2 class="text-white p-2">Ticket Information</h2>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-unstyled ">
                                                <li>
                                                    <i class="fa-solid fa-user"></i>
                                                    <span>Created By: <?php echo $infos['USER_EN_NAME'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-clock"></i>
                                                    <span>Service Type: <?php echo " " . $infos['SERVICE_TYPE'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Service Details: <?php echo " " .  $infos['SERVICE_DETAIL'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-circle-info"></i>
                                                    <span>Ticket Periority: <?php echo " " . $infos['TICKET_PERIORITY'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-box-tissue"></i>
                                                    <span>Issue Description: <?php echo " " . $infos['ISSUE_DESCRIPTION'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-box-tissue"></i>
                                                    <span>Department Name: <?php echo " " . $infos['DEPARTMENT_NAME'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-comment"></i>
                                                    <span>Technical Issue Description: <?php echo " " . $infos['TECHNICAL_ISSUE_DESCRIPTION'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-comment"></i>
                                                    <span>Technical Issue Resolution: <?php echo " " . $infos['TECHNICAL_ISSUE_RESOLUTION'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-comment"></i>
                                                    <span>Created Date: <?php echo " " . $infos['TICKET_START_DATE'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-battery-half"></i>
                                                    <span>Ticket Status: <?php if ($infos["TICKET_STATUS"] == '10') {
                                                                                echo '<span class="badge bg-primary "> New</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '20') {
                                                                                echo '<span class="badge bg-warning"> Assign</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '30') {
                                                                                echo '<span class="badge bg-info"> Started</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '60') {
                                                                                echo '<span class="badge bg-success"> Solved</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '40') {
                                                                                echo '<span class="badge bg-success"> Confirm</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '50') {
                                                                                echo '<span class="badge bg-danger"> Rejected</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '70') {
                                                                                echo '<span class="badge bg-danger"> Canceled</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '100') {
                                                                                echo '<span class="badge bg-danger"> Included</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '90') {
                                                                                echo '<span class="badge bg-danger"> Excluded</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '110') {
                                                                                echo '<span class="badge bg-danger"> Send Out</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '120') {
                                                                                echo '<span class="badge bg-danger"> Received</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '130') {
                                                                                echo '<span class="badge bg-danger"> Exclude All</span>';
                                                                            } elseif ($infos["TICKET_STATUS"] == '140') {
                                                                                echo '<span class="badge bg-danger"> Confirmed by system</span>';
                                                                            } ?></span>
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

            <hr class="custom-hr">

        </main>
        <!-- Ticket Information Table End -->
    <?php
    } elseif ($action == 'AddService') {
    ?>
        <!-- Manage Users Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center">Add New Service</h2>
                    <div class="container">
                        <!-- Add Users Form Start -->
                        <form class="form-horizontal" action="" method="">

                            <!-- Start Username Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Service Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control  name" placeholder=" Enter Service Name please..." autocomplete="off" required="required">
                                </div>
                            </div>
                            <!-- End Username Field -->

                            <!-- Start Phone Number Field -->
                            <input type="text" name="admin" class="form-control  admin" value="<?php echo $_SESSION['user'] ?>" hidden>
                            <!-- End Phone Number Field -->

                            <!-- Start Submit Button -->
                            <div class="form-group form-group-lg mt-3 mb-1">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-lg addService">Add Service</button>
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
    } elseif ($action == 'serviceEdit') {
    ?>
        <!-- Manage Users Table Start -->
        <main class="content px-3 py-2">
            <div class="container-fluid">
                <div class="mb-3">
                    <h2 class="text-center">Edit Service Information</h2>
                    <div class="container">
                        <form class="form-horizontal" action="" method="">
                            <input type="hidden" class="id" name="id">

                            <!-- Start Username Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-lable mt-3 mb-1" for="">Service Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="serviceName" class="form-control  serviceName" placeholder="Enter new username please..." autocomplete="off" required="required">
                                </div>
                            </div>
                            <!-- End Username Field -->

                            <!-- Start Submit Button -->
                            <div class="form-group form-group-lg mt-3 mb-1">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success btn-lg updateService">Update</button>
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
    } elseif ($action == 'Assign') {        // Assigned Ticket Page 
        $ticketid = isset($_GET['tickid']) && is_numeric($_GET['tickid']) ?   intval($_GET['tickid']) : 0; // GET Ticket Number  

        if (isset($_POST['Number'])) {   // Choose Team Member Debends On Team Number
            $ticketNumber = $_POST['Number'];  // Service Number

            // InsertUserID();

            // Query to fetch Team Member based on the selected Team Number
            $ticketNo = "SELECT TICKET_NO, REQUETS_TYPE_NO, SERVICE_DETAIL_NO FROM TICKETING.TICKETS WHERE TICKET_NO = :t_ticket";
            $ticket = oci_parse($conn, $ticketNo);
            // Bind the variables
            oci_bind_by_name($ticket, ":t_ticket", $ticketNumber);
            // Execute the query
            oci_execute($ticket);

            $result = oci_fetch_assoc($ticket);
            echo json_encode($result);
        }
    ?>
        <!-- Assign Ticket  Start -->
        <main class="content px-3 py-2"> <!-- Main Start -->
            <div class="container-fluid"> <!-- Container-fluid Div Start -->
                <div class="mb-3">
                    <h2 class="text-center mt-3 mb-5">Assign Tickets</h2>
                    <div class="container mb-4 mt-4">
                        <h3 class="text-start mt-3 mb-4 text-dark">Ticket Information</h3>
                        <div class="row g-3" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="col-sm-4">
                                <label class="" for="autoSizingSelect">Ticket #</label>
                                <input type="text" class="form-control" id="ticketNumber" aria-label="City" readonly>
                            </div>
                            <div class="col-sm-4">
                                <label class="" for="autoSizingSelect">Requested By</label>
                                <input type="text" class="form-control" aria-label="State">
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
                                <label class="" for="autoSizingSelect">Service Name</label>
                                <input type="text" class="form-control" id="serviceType" aria-label="City" readonly>
                            </div>
                            <div class="col-sm-4">
                                <label class="" for="autoSizingSelect">Service For</label>
                                <input type="text" class="form-control" id="serviceDetail" aria-label="State" readonly>
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
                                <select class="form-select" id="assign">
                                    <option>Choose Team...</option>
                                    <?php
                                    $firstTeam = 1;
                                    $secTeam = 6;
                                    // // Query to retrieve a list of tables
                                    $teamName = "SELECT TEAM_NO, TEAM_NAME  FROM TICKETING.TEAMS WHERE TEAM_NO = :t_no OR TEAM_NO = :t_id";
                                    $teams = oci_parse($conn, $teamName);
                                    oci_bind_by_name($teams, ":t_no", $firstTeam);
                                    oci_bind_by_name($teams, ":t_id", $secTeam);
                                    // Execute the query
                                    oci_execute($teams);
                                    while ($team = oci_fetch_assoc($teams)) {
                                        echo "<option value='" . $team['TEAM_NO'] . "'>" . $team['TEAM_NAME'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <div class="omar" style="display: flex; justify-content:space-around; max-width: 1200px;"> <!-- Container Div Start  -->
                            <div style="width: 500px; margin-right: 15px">
                                <h3 class="text-start mt-3 text-dark">Team Member</h3>
                                <table class="main-table text-center table table-bordered mt-3 ">

                                    <thead>
                                        <tr>
                                            <td>User Name</td>
                                            <td>Name</td>
                                            <td>Status</td>
                                            <td>Control</td>
                                        </tr>
                                    </thead>

                                    <tbody id="teamMember">

                                    </tbody>

                                </table>
                            </div>

                            <div style="width: 500px; margin-right: 15px">
                                <h3 class="text-start mt-3 text-dark">Selected Team Member for Ticket</h3>
                                <table class="main-table text-center table table-bordered mt-3  ">

                                    <tr>
                                        <td>Name</td>
                                        <td>Description</td>
                                        <td>Team Leader</td>
                                    </tr>
                                </table>
                            </div>

                        </div> <!-- Container Div End  -->
                    </div>

                </div>
            </div><!-- Container-fluid Div End  -->
        </main> <!-- Main End -->
        <!-- Assign Ticket Info End -->
    <?php
    } elseif ($action == 'new') {           // Create New Ticket Page  
    ?>
        <!-- New Ticket Form Start -->
        <main class="content px-3 py-2"> <!-- Main Start  -->
            <div class="container-fluid"> <!-- Container-fluid Div Start  -->
                <div class="mb-3">
                    <h2 class="text-center mt-3">Create New Ticket</h2>
                    <div class="container"> <!-- Container Div Start  -->
                        <!-- Edit Ticket Information Form Start -->
                        <form class="form-horizontal" action="" method="POST" style="margin: auto;">
                            <!-- Start Name SelectBox -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="">User Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="<?php echo $_SESSION['user'] ?>" class=" form-control name" disabled>
                                </div>
                            </div>
                            <!-- End Name  SelectBox -->
                            <!-- Start Service Type Field Start-->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="service">Service Type</label>
                                <div class="col-sm-10">
                                    <select class="form-select service" name="service" id="service" required>
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
                            </div>
                            <!-- End TService Type Field End -->
                            <!-- Start Service Details Field Start-->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="details">Service Details</label>
                                <div class="col-sm-10">
                                    <select class="form-select details" name="details" id="details" required>
                                        <option value="">Choose Service Detail</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Service Details Field End -->
                            <!-- Start Device Field Start-->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="details">Device</label>
                                <div class="col-sm-10">
                                    <select class="form-select details" name="details" id="details">
                                        <option value="">Choose Service Detail</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Device End -->
                            <!-- Start Issue Description Field -->
                            <div class="form-group">
                                <label class="col-sm-2 control-lable mt-3" for="">Issue Description</label>
                                <div class="col-sm-10">
                                    <textarea name="description" id="" class=" form-control  description" cols="30" rows="10" placeholder="Enter issue description please..." required='required'></textarea>
                                </div>
                            </div>
                            <!-- End Issue Description Field -->
                            <!-- Start Submit Button -->
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-lg mt-3  addTicket" name="addTicket">Create Ticket</button>
                                </div>
                            </div>
                            <!-- End Submit Button  -->
                        </form>
                        <!-- Edit Ticket Information Form End -->
                    </div> <!-- Container Div End  -->
                </div>
            </div> <!-- Container-fluid Div End  -->
        </main> <!-- Main End  -->
        <!-- New Ticket Form End -->
    <?php
    } elseif ($action == 'edit') {
    ?>
        <!-- Edit Ticket Info Start -->
        <main class="content px-3 py-2"> <!-- Main Start -->
            <div class="container-fluid"> <!-- Container-fluid Div Start -->
                <div class="mb-3">

                    <h2 class="text-center mt-3">Edit Tickets</h2>

                    <div class="container"> <!-- Container Div Start  -->

                        <form class="form-horizontal" action="" method="" style="margin: auto;">

                            <!-- Start Name SelectBox -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="">User Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="<?php echo $_SESSION['user'] ?>" class=" form-control name" disabled>
                                </div>
                            </div>
                            <!-- End Name  SelectBox -->

                            <!-- Start Service Type Field Start-->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="service">Service Type</label>
                                <div class="col-sm-10">
                                    <select class="form-select service" name="service" id="service" required>
                                        <option value="">Choes Service</option>
                                        <?php
                                        // // Query to retrieve a list of tables
                                        $department = "SELECT  * FROM service_type";
                                        $dep = oci_parse($conn, $department);

                                        // Execute the query
                                        oci_execute($dep);

                                        while ($dept = oci_fetch_assoc($dep)) {
                                            echo "<option value='" . $dept['ID'] . "'>" . $dept['TYPE'] . "</option>";
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <!-- End TService Type Field End -->

                            <!-- Start Service Details Field Start-->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="details">Service Details</label>
                                <div class="col-sm-10">
                                    <select class="form-select details" name="details" id="details" required>
                                        <option value="">Choose Service Detail</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Service Details Field End -->

                            <!-- Start Issue Description Field -->
                            <div class="form-group">
                                <label class="col-sm-2 control-lable mt-3" for="">Issue Description</label>
                                <div class="col-sm-10">
                                    <textarea name="description" id="" class=" form-control  description" cols="30" rows="10" placeholder="Enter issue description please..." required='required'></textarea>
                                </div>
                            </div>
                            <!-- End Issue Description Field -->

                            <!-- Start Submit Button -->
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Update" class="btn btn-success btn-lg mt-3">
                                </div>
                            </div>
                            <!-- End Submit Button  -->
                        </form>
                    </div> <!-- Container Div End  -->
                </div>
            </div><!-- Container-fluid Div End  -->
        </main> <!-- Main End -->
        <!-- Edit Ticket Info End -->
    <?php
    } elseif ($action == 'team') {          // Team Member Page Thats contain Teams Information   
    ?>
        <!-- Team Member Information Start -->
        <main class="content px-3 py-2"> <!-- Main Start -->
            <div class="container-fluid"> <!-- Container-fluid Div Start -->
                <div class="mb-3">

                    <h2 class="text-center mt-3 mb-5">Team Members</h2>

                    <div class="scro container-fluid mb-4 mt-4">
                        <div class="row d-flex justify-content-around">

                            <div class="col-sm-5 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark">Team</h3>
                                </div>
                                <div class="row">
                                    <div class='col-sm-4'>
                                        <label class="" for="autoSizingSelect">Team No</label>
                                        <select class="form-select teamno" name="teamno" id="teamno" required>
                                            <option value="">Choose Team No</option>
                                            <?php
                                            // // Query to retrieve a list of tables
                                            $teamNo = "SELECT  * FROM TICKETING.TEAMS";
                                            $team = oci_parse($conn, $teamNo);

                                            // Execute the query
                                            oci_execute($team);

                                            while ($teams = oci_fetch_assoc($team)) {
                                                echo "<option value='" . $teams['TEAM_NO'] . "'>" . $teams['TEAM_NO'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="" for="autoSizingSelect">Name</label>
                                        <input type="text" class="form-control" id="name" aria-label="State" readonly>
                                    </div>

                                    <div class='check  col-sm-4' style='display: flex; justify-content: center; align-items: center;'>
                                        <label class="pe-2" for="autoSizingSelect">Status</label>

                                        <input type='checkbox' name='status' id='status'>
                                    </div>
                                    <div class="col-sm-10 my-2">
                                        <label class="" for="autoSizingSelect">Description</label>
                                        <textarea type="text" class="form-control" aria-label="City"></textarea>
                                    </div>
                                    <div class="col-sm-6 my-2">
                                        <label class="" for="autoSizingSelect">Department</label>
                                        <input type="text" class="form-control" id="dept" aria-label="State" readonly>
                                        <!-- <select class="form-select dept" name="dept" id="dept" required>
                                            <option value="">Choose Department</option>
                                        </select> -->
                                    </div>
                                    <div class='col-sm-6 my-2'>
                                        <label class="" for="autoSizingSelect">Branch</label>
                                        <input type="text" class="form-control" id="branch" aria-label="State" readonly>
                                        <!-- <select class="form-select branch" name="branch" id="branch" required>
                                            <option value="">Choose Branch</option>
                                        </select> -->
                                    </div>
                                </div>
                            </div>

                            <div class=" col-sm-5 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark">Delegate Supervisors</h3>
                                </div>

                                <div class="scroll">
                                    <table class="main-table text-center table table-bordered mt-3 ">
                                        <thead>
                                            <tr>
                                                <td>Name</td>
                                                <td>Start Date</td>
                                                <td>End Date</td>
                                            </tr>
                                        </thead>

                                        <tbody id="tableBody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="scro container-fluid mb-4 mt-4">
                        <div class="row d-flex justify-content-around">

                            <div class="col-sm-10 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark">Team Members</h3>
                                </div>
                                <div class="scroll">
                                    <table class="main-table text-center table table-bordered mt-3 ">
                                        <thead>
                                            <tr>
                                                <td>User Name</td>
                                                <td>Name</td>
                                                <td>Description</td>
                                                <td>Active</td>
                                                <td>Supervisor</td>
                                                <td>Manager</td>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div><!-- Container-fluid Div End  -->
        </main> <!-- Main End -->
        <!-- Team Member Information Start -->
    <?php
    } elseif ($action == 'service') {       // Service Page Thats contain Service Information  
    ?>
        <!-- Service Page  Start -->
        <main class="content px-3 py-2"> <!-- Main Start -->
            <div class="container-fluid"> <!-- Container-fluid Div Start -->
                <div class="mb-3">

                    <h2 class="text-center mt-3 mb-5">Services</h2>

                    <div class="scro container-fluid mb-4 mt-4">
                        <div class="row d-flex justify-content-around">

                            <div class="col-sm-10 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark">Services</h3>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label class="" for="autoSizingSelect">Service #</label>
                                        <select class="form-select serviceno" name="serviceno" id="serviceno" required>
                                            <option value="">Choose Service No</option>
                                            <?php
                                            // // Query to retrieve a list of tables
                                            $serviceNo = "SELECT * FROM TICKETING.SERVICE";
                                            $service = oci_parse($conn, $serviceNo);

                                            // Execute the query
                                            oci_execute($service);

                                            while ($services = oci_fetch_assoc($service)) {
                                                echo "<option value='" . $services['SERVICE_NO'] . "'>" . $services['SERVICE_NO'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="" for="autoSizingSelect">Name</label>
                                        <input type="text" class="form-control" id="name" aria-label="State" readonly>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="scro container-fluid mb-4 mt-4">
                        <div class="row d-flex justify-content-around">
                            <div class=" col-sm-5 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark">Service Details</h3>
                                </div>
                                <div class="scroll">
                                    <table class="main-table text-center table table-bordered mt-3 ">
                                        <thead>
                                            <tr>
                                                <td>Service Details Name</td>
                                                <td>Description</td>
                                                <td>Custody</td>
                                                <td>Private</td>
                                            </tr>
                                        </thead>

                                        <tbody id="serviceDetails">

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class=" col-sm-5 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark">Teams</h3>
                                </div>
                                <div class="scroll">
                                    <table class="main-table text-center table table-bordered mt-3 ">

                                        <thead>
                                            <tr>
                                                <td>Team Name</td>
                                                <td>Enabled</td>
                                            </tr>
                                        </thead>

                                        <tbody id="serviceTeam">

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- Container-fluid Div End  -->
        </main> <!-- Main End -->
        <!-- Service Page End -->
    <?php
    } elseif ($action == 'delegate') {      // Delegate Page Thats contain Delegated Information 
    ?>
        <!-- Delegate Page  Start -->
        <main class="content px-3 py-2"> <!-- Main Start -->
            <div class="container-fluid"> <!-- Container-fluid Div Start -->
                <div class="mb-3">

                    <h2 class="text-center mt-3 mb-5">Delegate Supervisor</h2>

                    <div class="scro container-fluid mb-4 mt-4">
                        <div class="row d-flex justify-content-around">

                            <div class=" col-sm-10 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark">Delegate Supervisor</h3>
                                </div>

                                <div class='col-sm-4'>
                                    <label class="" for="autoSizingSelect">Team No</label>
                                    <select class="form-select delegate" name="delegate" id="delegate" required>
                                        <option value="">Choose Team No</option>
                                        <?php
                                        // // Query to retrieve a list of tables
                                        $teamNo = "SELECT  * FROM TICKETING.TEAMS";
                                        $team = oci_parse($conn, $teamNo);

                                        // Execute the query
                                        oci_execute($team);

                                        while ($teams = oci_fetch_assoc($team)) {
                                            echo "<option value='" . $teams['TEAM_NO'] . "'>" . $teams['TEAM_NO'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="scroll">
                                    <table class="main-table text-center table table-bordered mt-3 ">
                                        <thead>
                                            <tr>

                                                <td>Name</td>
                                                <td>Start Date</td>
                                                <td>End Date</td>
                                            </tr>
                                        </thead>

                                        <tbody id="delegateBody">

                                        </tbody>

                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div><!-- Container-fluid Div End  -->
        </main> <!-- Main End -->
        <!-- Delegate Page  End -->
<?php
    }
    include $inc . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush(); // Release The Output
?>