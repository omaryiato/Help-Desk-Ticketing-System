<?php
$startTime = microtime(true);
/*
    ================================================
    == Manage Service Page
    == You Can Edit | Delete | Add |  Service & Service DEtails From Here 
    ================================================
*/

ob_start(); // Output Buffering Start

session_start();

// Check if the user is logged in and the session is active
if (isset($_SESSION['user'])) {
    // Check if the last activity time is set
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        // Calculate the time difference since the last activity
        $elapsedTime = time() - $_SESSION['LAST_ACTIVITY'];

        // Check if the elapsed time exceeds 2 minutes (120 seconds)
        if ($elapsedTime > 3600) {
            // Destroy the session
            session_unset(); // Unset all session variables
            session_destroy(); // Destroy the session

            // Redirect the user to the login page
            header("Location: index.php");
            exit(); // Ensure that no further code is executed
        }
    }

    // Update the last activity time
    $_SESSION['LAST_ACTIVITY'] = time();
}

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

    putenv('NLS_LANG=AMERICAN_AMERICA.AL32UTF8');
    $conn = oci_connect($username, $password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SID=$sid)))");

    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        echo "Connectoin to Oracle Database Failed!<br>";
    }

    include 'init.php';  // This File Contain ( Header, Footer, Navbar, Function, JS File,  Style File ) File


    $userSession = $_SESSION['user'];
    // Query to fetch users Information based on User Name
    $userInfo   = "SELECT USER_ID  FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = '" . $userSession . "'";
    $info       = oci_parse($conn, $userInfo);
    oci_execute($info);
    $row        = oci_fetch_assoc($info);
    $UserSessionID = $row['USER_ID'];

    $permission = " SELECT ROLE_ID FROM TICKETING.TKT_REL_ROLE_USERS WHERE USER_ID =  " . $row['USER_ID'];
    $userPermission = oci_parse($conn, $permission);
    oci_execute($userPermission);
    $roles = oci_fetch_assoc($userPermission); // User Roles

    if ($sid == 'ARCHDEV') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Test_Application</span></div>';
    } elseif ($sid == 'ARCHPROD') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Production_Application</span></div>';
    } else {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;">' . $sid . '</span></div>';
    }

?>

    <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>" disabled readonly>
    <!-- Service Page  Start -->
    <main class="content px-3 py-2"> <!-- Main Start -->
        <div class="container-fluid"> <!-- Container-fluid Div Start -->
            <div class="mb-3">

                <h2 class="text-center mt-3 ">Ticketing System</h2>
                <div class="home">
                    <div class="homeMenu">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-sm-3 mb-3 mx-3 ">
                                <div class="card" style="width: 15rem; height: 15rem;">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><i class="fa-solid fa-ticket pe-2"></i>Ticketing Transactions Page</h5>
                                        <p class="card-text mt-2">Go To The Ticket Transaction Page.</p>
                                        <button class="mt-auto"><a href="TicketTransaction.php" id="TicketTransationTable" aria-label="Go To The User Profile" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 mb-3 mx-3">
                                <div class="card" style="width: 15rem; height: 15rem;">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><i class="fa-solid fa-plus pe-2"></i>Create New Tickets</h5>
                                        <p class="card-text mt-2">Tell Us About Your Problem.</p>
                                        <button class="mt-auto"><a href="##" id="CreateNewTicket" data-bs-toggle='modal' data-bs-target="#AddNewTicketPopup" data-bs-whatever="AddNewTicketPopup" aria-label="Logout From User Account" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if ($roles['ROLE_ID'] == 1 || $roles['ROLE_ID'] == 3) {
                            ?>
                                <div class="col-sm-3 mb-3 mx-3">
                                    <div class="card" style="width: 15rem; height: 15rem;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><i class="fa-solid fa-user-minus pe-2"></i>Delegate Supervisors</h5>
                                            <p class="card-text mt-2">Delegate With Other Supervisors.</p>
                                            <button class="mt-auto"><a href="delegate.php" aria-label="Logout From User Account" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if ($roles['ROLE_ID'] == 1) {
                            ?>
                                <div class="col-sm-3 mb-3 mx-3">
                                    <div class="card" style="width: 15rem; height: 15rem;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><i class="fa-solid fa-users pe-2"></i>Team Member</h5>
                                            <p class="card-text mt-2">Go To The Manage Team Member Page.</p>
                                            <button class="mt-auto"><a href="teams.php" aria-label="Go To The User Orders" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-3 mx-3">
                                    <div class="card" style="width: 15rem; height: 15rem;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><i class="fa-solid fa-headphones pe-2"></i>Services</h5>
                                            <p class="card-text mt-2">Go To The Manage Service Page.</p>
                                            <button class="mt-auto"><a href="service.php" aria-label="Logout From User Account" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-3 mx-3">
                                    <div class="card " style="width: 15rem; height: 15rem;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><i class="fa-solid fa-circle-check pe-2"></i>Update Solved to Confirm</h5>
                                            <p class="card-text mt-2">Confirm All Solved Tickets Thats Not Confirmeds.</p>
                                            <button class="mt-auto"><a href="##" aria-label="Confirm All Solved Ticket " id="UpdateAllSolveTicketToConfirm" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></li>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- Container-fluid Div End  -->
    </main> <!-- Main End -->

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
                                                <!-- Start Name SelectBox -->
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

<?php

    include $inc . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
$endTime = microtime(true); // CALCULAT page loaded time

$timeTaken = $endTime - $startTime;

echo "<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>Page Loaded In: " . round($timeTaken, 2)  . " Seconds</h5>";
ob_end_flush(); // Release The Output
?>