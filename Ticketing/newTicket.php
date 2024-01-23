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

if (isset($_SESSION['user'])) {

    $pageTitle = 'Create New Ticket';
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
    include 'init.php';  // This File Contain ( Header, Footer, Navbar, Function, JS File,  Style File ) File

    $userSession = $_SESSION['user'];
    // Query to fetch users Information based on User Name
    $userInfo   = "SELECT USER_ID  FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = '" . $userSession . "'";
    $info       = oci_parse($conn, $userInfo);
    oci_execute($info);
    $row        = oci_fetch_assoc($info);
?>

    <!-- New Ticket Form Start -->
    <main class="content px-3 py-2"> <!-- Main Start -->
        <div class="container"> <!-- Container-fluid Div Start -->
            <div class="mb-3">
                <h2 class="text-center mt-3 ">Create New Ticket</h2>
                <div class=" container  mt-2">
                    <div class="row d-flex justify-content-center" id="formContainer" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                        <div class=" col-sm-5 mx-1 ">
                            <div class="row">
                                <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">
                                <!-- Start Name SelectBox -->
                                <div class='col-sm-10'>
                                    <label class="" for="autoSizingSelect">User Name</label>
                                    <input type="text" name="name" value="<?php echo $_SESSION['user'] ?>" class=" form-control name" disabled>
                                </div>
                                <!-- End Name  SelectBox -->
                                <!-- Start Service Type Field Start-->
                                <div class="col-sm-10">
                                    <label class="" for="autoSizingSelect" for="service">Service Type</label>
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
                                <!-- End TService Type Field End -->
                                <!-- Start Service Details Field Start-->
                                <div class="col-sm-10">
                                    <label class="" for="autoSizingSelect" for="details">Service Details</label>
                                    <select class="form-select details" name="details" id="details" required>
                                        <option value="">Choose Service Detail</option>
                                    </select>
                                </div>
                                <!-- End Service Details Field End -->
                                <!-- Start Device Field Start-->
                                <div class="col-sm-10">
                                    <label class="" for="autoSizingSelect" for="device">Device</label>
                                    <select class="form-select device" name="device" id="device">
                                        <option value="">Choose Device</option>
                                    </select>
                                </div>
                                <!-- End Device End -->
                            </div>
                        </div>
                        <div class=" col-sm-5 mx-1">
                            <!-- Start Issue Description Field -->
                            <div class='col-sm-12'>
                                <label class="control-lable" for="autoSizingSelect">Issue Description</label>
                                <textarea name="description" id="description" class="description" cols="50" rows="10" placeholder="Enter issue description please..." required='required'></textarea>
                            </div>
                            <!-- End Issue Description Field -->
                        </div>
                        <div class=" col-sm-10 mx-1 ">
                            <div class="row">
                                <!-- Start Submit Button -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary btn-lg mt-3  addTicket" name="addTicket">Create Ticket</button>
                                    </div>
                                </div>
                                <!-- End Submit Button  -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div><!-- Container-fluid Div End  -->
    </main> <!-- Main End -->
    <!-- New Ticket Form End -->

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