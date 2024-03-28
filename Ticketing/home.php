<?php
$startTime = microtime(true);
/*
    ================================================
    == Home Page
    == You Can Access to the other pages from this page   o9m9a9r1y0a5h2y1a2m9 
    ================================================
*/

ob_start(); // Output Buffering Start

session_start();
include 'init.php';  // This File Contain ( Header, Footer, Navbar, JS File,  Style File ) File

$_SESSION['e-Ticketing'] = $_GET['hashkey'];

// Get the IP address of the client
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip_address = $_SERVER['REMOTE_ADDR'];
}

// $hashKey = $_SESSION['e-Ticketing'];
$hashKey = $_SESSION['e-Ticketing'];
$ip_address = '192.168.203.64';


// if (isset($_SESSION['user'])) {

$checkUser = "SELECT xxajmi_sshr_ticketing.xxajmi_user_valid@TKT_TO_SELF_SERV('$hashKey' ,'$ip_address') AS User_Validat
from dual";
$parsChek = oci_parse($conn, $checkUser);
// oci_bind_by_name($parsChek, ":hashKey", $hashKey);
// oci_bind_by_name($parsChek, ":ip_address", $ip_address);
oci_execute($parsChek);
$run = oci_fetch_assoc($parsChek);
// $run = oci_result($parsChek, 'VALIDAT');

if ($run['USER_VALIDAT'] !== 'User not Valid') {

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
            header("Location: portal.php");
            exit(); // Ensure that no further code is executed
        }
    }

    // Update the last activity time
    $_SESSION['LAST_ACTIVITY'] = time();


    $userFileNum =  $run['USER_VALIDAT'];
    // count active Users

    $active = 'Y';
    // Query to fetch users Information based on User Name
    $activeUsers   = "UPDATE TICKETING.xxajmi_ticket_user_info SET ACTIVE_LOGIN = '" . $active . "'  WHERE EBS_EMPLOYEE_ID = '" .  $userFileNum . "'";
    $actives       = oci_parse($conn, $activeUsers);
    oci_execute($actives);


    $userInfo   = "SELECT USER_ID, USERNAME  FROM TICKETING.xxajmi_ticket_user_info WHERE EBS_EMPLOYEE_ID = '" . $userFileNum . "'";
    $info       = oci_parse($conn, $userInfo);
    oci_execute($info);
    $row        = oci_fetch_assoc($info);
    $_SESSION['USER_ID'] = $row['USER_ID'];
    $_SESSION['USERNAME'] = $row['USERNAME'];

    // Query to fetch users role based on User ID
    $permission = " SELECT ROLE_ID FROM TICKETING.TKT_REL_ROLE_USERS WHERE USER_ID =  " . $row['USER_ID'];
    $userPermission = oci_parse($conn, $permission);
    oci_execute($userPermission);
    $roles = oci_fetch_assoc($userPermission); // User Roles

    /*******  This Condition to print DB name to know which one Opened  ***************/
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
                        <div class="row ">
                            <div class="col-sm-4 mb-3  ">
                                <div class="card" style="width: 15rem; height: 15rem;">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><i class="fa-solid fa-ticket pe-2"></i>Ticketing Transactions Page</h5>
                                        <p class="card-text mt-2">Go To The Ticket Transaction Page.</p>
                                        <button class="mt-auto"><a href="TicketTransaction.php" id="TicketTransationTable" aria-label="Go To The User Profile" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 mb-3 ">
                                <div class="card" style="width: 15rem; height: 15rem;">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><i class="fa-solid fa-plus pe-2"></i>Create New Tickets</h5>
                                        <p class="card-text mt-2">Tell Us About Your Problem.</p>
                                        <button class="mt-auto"><a href="##" id="CreateNewTicket" data-bs-toggle='modal' data-bs-target="#AddNewTicketPopup" data-bs-whatever="AddNewTicketPopup" aria-label="Logout From User Account" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if ($roles['ROLE_ID'] == 1 || $roles['ROLE_ID'] == 3) { /* GM & Supervisor */
                            ?>
                                <div class="col-sm-4 mb-3 ">
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
                            if ($roles['ROLE_ID'] == 1) { /* GM */
                            ?>
                                <div class="col-sm-4 mb-3 ">
                                    <div class="card" style="width: 15rem; height: 15rem;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><i class="fa-solid fa-users pe-2"></i>Team Member</h5>
                                            <p class="card-text mt-2">Go To The Manage Team Member Page.</p>
                                            <button class="mt-auto"><a href="teams.php" aria-label="Go To The User Orders" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-3 ">
                                    <div class="card" style="width: 15rem; height: 15rem;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><i class="fa-solid fa-headphones pe-2"></i>Services</h5>
                                            <p class="card-text mt-2">Go To The Manage Service Page.</p>
                                            <button class="mt-auto"><a href="service.php" aria-label="Logout From User Account" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-3 ">
                                    <div class="card " style="width: 15rem; height: 15rem;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><i class="fa-solid fa-circle-check pe-2"></i>Update Solved to Confirm</h5>
                                            <p class="card-text mt-2">Confirm All Solved Tickets Thats Not Confirmeds.</p>
                                            <button class="mt-auto"><a href="##" aria-label="Confirm All Solved Ticket " id="UpdateAllSolveTicketToConfirmhome" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></li>
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
        <div class="modal-dialog modal-lg">
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
                                    <form class="row " id="AddNewTicketForm" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                                        <div class=" col-sm-6 ">
                                            <div class="row">
                                                <!-- Start Ticket Branch Field -->
                                                <div class='col-sm-10'>
                                                    <label class="" for="TicketTransactionSessionID">User Name</label>
                                                    <input type="text" class="form-control" id="AddUserSessionName" aria-label="State" value="<?php echo $_SESSION['USERNAME'] ?>" disabled readonly>
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
                                        <div class=" col-sm-6 ">
                                            <!-- Start Issue Description Field -->
                                            <div class='col-sm-12'>
                                                <label class="control-lable" for="description">Issue Description</label>
                                                <textarea name="description" id="description" class="description" cols="40" rows="10" placeholder="Enter issue description please..." required='required'></textarea>
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

    <div class="overlay" id="spinner">
        <span class="loader"></span>
    </div>

<?php

    include $inc . 'footer.php';
    // } else {
    //     header('Location: index.php');
    //     exit();
    // }
} else {
    header('Location: portal.php');
    exit();
}
$endTime = microtime(true); // CALCULAT page loaded time

$timeTaken = $endTime - $startTime;

echo "<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>Page Loaded In: " . round($timeTaken, 2)  . " Seconds</h5>";
ob_end_flush(); // Release The Output
?>