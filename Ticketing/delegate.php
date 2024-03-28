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

include 'init.php';  // This File Contain ( Header, Footer, Navbar, Function, JS File,  Style File ) File

// $_SESSION['e-Ticketing'] = $_GET['hashkey'];

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
$ip_address = '192.168.15.27';

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
            header("Location: index.php");
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

    if ($sid == 'ARCHDEV') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Test_Application</span></div>';
    } elseif ($sid == 'ARCHPROD') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Production_Application</span></div>';
    } else {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;">' . $sid . '</span></div>';
    }

?>

    <input type="hidden" class="form-control" id="delegateSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>" disabled readonly>
    <!-- Delegate Page  Start -->
    <main class="content px-3 py-2"> <!-- Main Start -->
        <div class="container-fluid"> <!-- Container-fluid Div Start -->
            <div class="mb-3">
                <h2 class="text-center mt-3 ">Delegate Supervisor</h2>
                <div class="scro container-fluid mb-2 mt-2">
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm-8 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <form class="row" id="DelegateForm">
                                <div class="col-sm-6 mt-3">
                                    <label class="" for="delegateTeam">Team Name</label>
                                    <select class="form-select delegate" name="delegateTeam" id="delegateTeam" required>
                                        <option value="">Choose Team...</option>
                                        <?php
                                        // // Query to retrieve a list of tables
                                        $teamNo = "SELECT TICKETING.TEAMS.TEAM_NAME, TEAM_NO
                                                    FROM TICKETING.TEAMS 
                                                    WHERE TEAM_NO IN 
                                                    (SELECT TEAM_NO FROM TICKETING.TEAM_MEMBERS WHERE TEAM_MEMBER_USER_ID = " . $UserSessionID . ")";
                                        $team = oci_parse($conn, $teamNo);

                                        // Execute the query
                                        oci_execute($team);

                                        while ($teams = oci_fetch_assoc($team)) {
                                            echo "<option value='" . $teams['TEAM_NO'] . "'>" . $teams['TEAM_NAME'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <label class="" for="delegateUser">User Name</label>
                                    <select class="form-select delegate" name="delegateUser" id="delegateUser" required>
                                        <option value="">Choose User Name...</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <label class="" for="StartDate">Start Date</label>
                                    <input type="date" class="form-control" id="StartDate" name="StartDate" aria-label="SearchTicketNumber">
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <label class="" for="EndDate">End Date</label>
                                    <input type="date" class="form-control" id="EndDate" name="EndDate" aria-label="SearchTicketNumber" disabled>
                                </div>
                                <div class="col-sm-4 mt-3">
                                    <button type="submit" class="btn btn-success  mt-3  " id="DelegateNewUser"> <i class="fa-solid fa-user-minus pe-2"></i> Delegate</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="scro container-fluid mb-4 mt-4">
                    <div class="row d-flex justify-content-around">

                        <div class=" col-sm-8 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="div">
                                <h3 class=" mt-3 mb-4 text-dark">Delegate History</h3>
                            </div>

                            <div class='col-sm-4'>
                                <label class="" for="delegateHistory">Team Name</label>
                                <select class="form-select delegate" name="delegateHistory" id="delegateHistory" required>
                                    <option value="">Choose Team Name</option>
                                    <?php
                                    // // Query to retrieve a list of tables
                                    $teamNo = "SELECT  TEAM_NO, TEAM_NAME FROM TICKETING.TEAMS";
                                    $team = oci_parse($conn, $teamNo);

                                    // Execute the query
                                    oci_execute($team);

                                    while ($teams = oci_fetch_assoc($team)) {
                                        echo "<option value='" . $teams['TEAM_NO'] . "'>" . $teams['TEAM_NAME'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="scroll">
                                <table class="main-table text-center table table-bordered mt-3 ">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
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
        </div>
        </div><!-- Container-fluid Div End  -->
    </main> <!-- Main End -->
    <!-- Delegate Page  End -->

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

<?php

    include $inc . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
$endTime = microtime(true); // CALCULAT page loaded time

$timeTaken = $endTime - $startTime;

// $timeTaken = round($timeTaken, 5);

echo "<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>Page Loaded In: " . round($timeTaken, 2)  . " Seconds</h5>";
ob_end_flush(); // Release The Output
?>