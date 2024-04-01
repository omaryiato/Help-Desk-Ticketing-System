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

include 'DBConnection.php';


$_SESSION['e-Ticketing'] = $_GET['hashkey'];

// Get the IP address of the client
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip_address = $_SERVER['REMOTE_ADDR'];
}

$hashKey = $_SESSION['e-Ticketing'];

// $ip_address = '192.168.203.64';
$ip_address = '192.168.15.94';


$checkUser = "SELECT xxajmi_sshr_ticketing.xxajmi_user_valid@TKT_TO_SELF_SERV('$hashKey' ,'$ip_address') AS User_Validat
                from dual";
$parsChek = oci_parse($conn, $checkUser);

oci_execute($parsChek);
$returnedFileNumber = oci_fetch_assoc($parsChek);

$no_file_number = $returnedFileNumber['USER_VALIDAT'];

$_SESSION['EmpNo'] = $no_file_number;

if ($no_file_number != 'User not Valid') {


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

    $checkUserAccounts = "SELECT count(*) AS ACCOUNTNUM  FROM TICKETING.xxajmi_ticket_user_info WHERE EBS_EMPLOYEE_ID = '" . $no_file_number . "'";
    $noAccount = oci_parse($conn, $checkUserAccounts);
    $run = oci_execute($noAccount);
    $resault = oci_fetch_assoc($noAccount);
    $_SESSION['NoAccount'] = $resault['ACCOUNTNUM'];


    $userInfo   = "SELECT USER_ID, USERNAME  FROM TICKETING.xxajmi_ticket_user_info WHERE EBS_EMPLOYEE_ID = '" . $no_file_number . "'";
    $info       = oci_parse($conn, $userInfo);
    oci_execute($info);
    $row        = oci_fetch_assoc($info);
    $_SESSION['USER_ID'] = $row['USER_ID'];
    $_SESSION['USERNAME'] = $row['USERNAME'];


    // count active Users

    $active = 'Y';
    // Query to fetch users Information based on User Name
    $activeUsers   = "UPDATE TICKETING.xxajmi_ticket_user_info SET ACTIVE_LOGIN = '" . $active . "'  WHERE EBS_EMPLOYEE_ID = '" .  $no_file_number . "'";
    $actives       = oci_parse($conn, $activeUsers);
    oci_execute($actives);

    include 'init.php';  // This File Contain ( Header, Footer, Navbar, JS File,  Style File ) File

    /*******  This Condition to print DB name to know which one Opened  ***************/
    if ($sid == 'ARCHDEV') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Test_Application</span></div>';
    } elseif ($sid == 'ARCHPROD') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Production_Application</span></div>';
    } else {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;">' . $sid . '</span></div>';
    }
?>
    <input type="hidden" value="<?php echo $row['USER_ID'] ?>" id="userID">
    <!-- Home Page  Start -->
    <main class="content px-3 py-2"> <!-- Main Start -->
        <div class="container-fluid"> <!-- Container-fluid Div Start -->
            <div class="mb-3">

                <h2 class="text-center mt-3 ">Ticketing System</h2>
                <div class="home">
                    <div class="homeMenu">
                        <div class="row " id="HomePageItems">

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

    <div class="overlay" id="spinner">
        <span class="loader"></span>
    </div>

<?php

    include $inc . 'footer.php';
} else {
    header('Location: https://sshr.alajmi.com.sa/public/index.php/login');
    exit();
}
$endTime = microtime(true); // CALCULAT page loaded time

$timeTaken = $endTime - $startTime;

echo "<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>Page Loaded In: " . round($timeTaken, 2)  . " Seconds</h5>";
ob_end_flush(); // Release The Output
?>