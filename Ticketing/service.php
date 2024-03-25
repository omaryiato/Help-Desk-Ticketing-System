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

    include 'init.php';  // This File Contain ( Header, Footer, Navbar, Function, JS File,  Style File ) File

    $userSession = $_SESSION['user'];
    // Query to fetch users Information based on User Name
    $userInfo   = "SELECT USER_ID  FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = '" . $userSession . "'";
    $info       = oci_parse($conn, $userInfo);
    oci_execute($info);
    $row        = oci_fetch_assoc($info);

    /*******  This Condition to print DB name to know which one Opened  ***************/

    if ($sid == 'ARCHDEV') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Test_Application</span></div>';
    } elseif ($sid == 'ARCHPROD') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Production_Application</span></div>';
    } else {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;">' . $sid . '</span></div>';
    }

?>

    <input type="hidden" class="form-control" id="ServiceUserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>" disabled readonly>
    <!-- Service Page  Start -->
    <main class="content px-3 py-2"> <!-- Main Start -->
        <div class="container-fluid"> <!-- Container-fluid Div Start -->
            <div class="mb-3">

                <h2 class="text-center mt-3 ">Services</h2>
                <div class="scro container-fluid mb-2 mt-2">
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm-10 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="d-flex justify-content-between">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark d-inline">Services</h3>
                                </div>
                                <div class="mx-2 my-2">
                                    <button class="btn btn-primary" data-bs-toggle='modal' data-bs-target="#NewService" data-bs-whatever="NewService" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Service'>
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Add New Service</span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <label class="" for="ServiceID">Service #</label>
                                    <input type="text" class="form-control" id="ServiceID" aria-label="State" readonly disabled>
                                </div>
                                <div class="col-sm-10" id="newservice">
                                    <label class="" for="serviceLOV">Service Name</label>
                                    <select class="form-select serviceLOV" name="serviceLOV" id="serviceLOV" required>
                                        <option value="">Choose Service Name</option>
                                        <?php
                                        // // Query to retrieve a list of tables
                                        $serviceNo = "SELECT SERVICE_NO, SERVICE_NAME FROM TICKETING.SERVICE";
                                        $service = oci_parse($conn, $serviceNo);
                                        // Execute the query
                                        oci_execute($service);
                                        while ($services = oci_fetch_assoc($service)) {
                                            echo "<option value='" . $services['SERVICE_NO'] . "'>" . $services['SERVICE_NAME'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" container-fluid mb-4 mt-2">
                    <div class="row d-flex justify-content-center">
                        <div class=" col-sm-5 mx-1 " style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class=" d-flex justify-content-between">
                                <div class="">
                                    <h3 class=" mt-3 mb-4 text-dark d-inline">Service Details</h3>
                                </div>
                                <div class="mx-2 my-2" id="addNewServiceDetailsButton">

                                </div>
                            </div>
                            <div class=" text-center mt-5" id="waitingMessage">
                                <div class="alert alert-primary" role="alert">
                                    There Is No Data You Can See It Yet.
                                </div>
                            </div>
                            <div class="details">
                                <table class=" detailsTable  text-center table table-bordered mt-3 " id="ServiceDetailsID">
                                    <thead id="serviceDetailsHeadTable">
                                    </thead>
                                    <tbody id="serviceDetails" style="cursor: pointer;">

                                    </tbody>
                                </table>

                            </div>
                            <div class="mx-2 my-4 d-flex justify-content-start" id="updateServiceDetailButton">
                                <!-- Update button will be appended here -->
                            </div>
                        </div>
                        <div class=" col-sm-5 mx-1" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="d-flex justify-content-between">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark">Teams</h3>
                                </div>
                                <div class="mx-2 my-2" id="addNewTeamDetailsButton">

                                </div>
                            </div>

                            <div class=" text-center mt-1" id="waitingMessages">
                                <div class="alert alert-primary change" role="alert">
                                    There Is No Data You Can See It Yet.
                                </div>
                            </div>

                            <div class="scroll ">
                                <table class="main-table text-center table table-bordered mt-3 ">
                                    <thead id="TeamDetailsHeadTable">

                                    </thead>
                                    <tbody id="serviceDetailsTeam">
                                    </tbody>
                                </table>
                            </div>
                            <div class="mx-2 my-4 d-flex justify-content-start" id="updateDetailTeamButton">
                                <!-- Update button will be appended here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- Container-fluid Div End  -->
    </main> <!-- Main End -->
    <!-- Service Page End -->

    <div class="serviceList" id="service-list">
        <div class="contentes">
            <ul class="shortMenu">
                <li>
                    <button class="item" style='margin-right: 5px;' id="editServiceDetailsButton" data-bs-toggle='modal' data-bs-target="#EditServiceDetails" data-bs-whatever="Edit" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Service Details'>
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Service</span>
                    </button>
                </li>
                <!-- <li>
                    <a class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Service Details'>
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Delete Service</span>
                    </a>
                </li> -->
            </ul>
        </div>
    </div>

    <!-- New Service Pop Up Form Start -->
    <div class="modal fade" id="NewService" tabindex="-1" aria-labelledby="NewServicePopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- New Section  Start -->
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container-fluid"> <!-- Container-fluid Div Start -->
                            <div class="mb-3">
                                <h2 class="text-center" id="NewServicePopupLabel">Add New Service</h2>
                                <div class="container mb-4 mt-4">
                                    <form class="row d-flex justify-content-between" id="AddNewServiceForm">
                                        <div class="col-sm-8">
                                            <label class="" for="NewServiceName">Service Name</label>
                                            <input type="text" class="form-control" id="NewServiceName" name="NewServiceName" aria-label="State" required>
                                        </div>
                                        <div class="col-sm-4 mt-4">
                                            <button type="submit" class="btn btn-success button" id="AddNewService" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Service'>
                                                <i class="fa-solid fa-plus pe-1"></i><span>Add</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                    <!-- New Section Info End -->
                </div>
            </div>
        </div>
    </div>
    <!-- New Service Pop Up Form End -->

    <!-- New Service Details Pop Up Form Start -->
    <div class="modal fade" id="NewServiceDetail" tabindex="-1" aria-labelledby="NewServiceDetailPopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- New Service  Start -->
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container-fluid"> <!-- Container-fluid Div Start -->
                            <div class="mb-3">
                                <h2 class="text-center" id="NewServiceDetailPopupLabel">Add New Service Detail</h2>
                                <div class="container mb-4 mt-4">
                                    <form class="row" id="AddNewServiceDetailsForm">
                                        <div class="col-sm-6 ">
                                            <label class="" for="GetServiceTypeName">Service Name</label>
                                            <input type="text" class="form-control" id="GetServiceTypeName" aria-label="Name" readonly required disabled>
                                            <!-- <input type="hidden" class="form-control" id="GetServiceTypeID" aria-label="ID" readonly required disabled> -->
                                        </div>
                                        <div class="col-sm-6 ">
                                            <label class="" for="NewServiceDetailsName">Service Detail Name</label>
                                            <input type="text" class="form-control" id="NewServiceDetailsName" name="NewServiceDetailsName" aria-label="Name" required>
                                        </div>
                                        <div class="col-sm-12 mt-2">
                                            <label class="" for="ServiceDetailsDescription">Service Detail Description</label>
                                            <input type="text" class="form-control" id="ServiceDetailsDescription" name="ServiceDetailsDescription" aria-label="Description" required>
                                        </div>
                                        <div class="col-sm-10  mt-4">
                                            <button type="submit" class="btn btn-success button width-75" id="AddNewServiceDetails" data-bs-toggle='tooltip' data-bs-placement='top' title='Create New Service Details'>
                                                <i class="fa-solid fa-plus pe-1"></i><span>Add</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                    <!-- New Service Info End -->
                </div>
            </div>
        </div>
    </div>
    <!-- New Service Details Pop Up Form End -->

    <!-- Edit Service Details Pop Up Form Start -->
    <div class="modal fade" id="EditServiceDetails" tabindex="-1" aria-labelledby="EditServiceDetailsPopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- New Section  Start -->
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container-fluid"> <!-- Container-fluid Div Start -->
                            <div class="mb-3">
                                <h2 class="text-center" id="EditServiceDetailsPopupLabel">Edit Service Details</h2>
                                <div class="container mb-4 mt-4">
                                    <form class="row d-flex justify-content-between" id="EditServiceDetailsInformationForm">

                                        <div class="col-sm-8">
                                            <label class="" for="EditServiceDetailsName">Service Details Name</label>
                                            <input type="text" class="form-control" id="EditServiceDetailsName" name="EditServiceDetailsName" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-8">
                                            <label class="" for="EditServiceDetailsDescription">Service Details Description</label>
                                            <input type="text" class="form-control" id="EditServiceDetailsDescription" name="EditServiceDetailsDescription" aria-label="State" required>
                                        </div>
                                        <div class="col-sm-4 mt-4">
                                            <button type="submit" class="btn btn-success button" id="UpdateServiceDetailsInfoButton" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Service Details'>
                                                <i class="fa-solid fa-pen-to-square pe-1"></i><span>Update</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                    <!-- New Section Info End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Service Pop Up Form End -->

    <!-- Assign New Service Details Team  Pop Up Form Start -->
    <div class="modal fade" id="NewDetailTeam" tabindex="-1" aria-labelledby="NewDetailTeamPopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- New Service  Start -->
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container-fluid"> <!-- Container-fluid Div Start -->
                            <div class="mb-3">
                                <h2 class="text-center" id="NewDetailTeamPopupLabel">Assign New Team</h2>
                                <div class="container mb-4 mt-4">
                                    <form class="row" id="AddNewServiceDetailsTeamForm">
                                        <div class="col-sm-6">
                                            <label class="" for="GetServiceDetailsName">Service Details Name</label>
                                            <input type="text" class="form-control" id="GetServiceDetailsName" aria-label="Name" readonly disabled>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="" for="GetServiceDetailsTeamNumber">Team Name</label>
                                            <select class="form-select" name="ServiceTypeNumber" id="GetServiceDetailsTeamNumber" name="GetServiceDetailsTeamNumber" required>
                                                <option value="">Choose Service Details Team Name </option>

                                            </select>
                                        </div>
                                        <div class="col-sm-10  mt-4">
                                            <button type="submit" class="btn btn-success button width-75" id="AddNewServiceDetailsTeam" data-bs-toggle='tooltip' data-bs-placement='top' title='Create New Service Details'>
                                                <i class="fa-solid fa-plus pe-1"></i><span>Add</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                    <!-- New Service Info End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Assign New Service Details Teams Pop Up Form End -->

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
                                                    <input type="text" class="form-control" id="AddUserSessionName" aria-label="State" value="<?php echo $_SESSION['user'] ?>" disabled readonly>
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