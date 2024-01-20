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

    $pageTitle = 'Service Page';
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
                                    <label class="" for="autoSizingSelect">Service #</label>
                                    <input type="text" class="form-control" id="ServiceID" aria-label="State" readonly>
                                </div>
                                <div class="col-sm-10" id="newservice">
                                    <label class="" for="autoSizingSelect">Service Name</label>
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
                                <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">
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
                                <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">
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
                <li>
                    <a class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Service Details'>
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Delete Service</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

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
                                    <div class="row d-flex justify-content-between">
                                        <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">

                                        <div class="col-sm-8">
                                            <input type="hidden" class="form-control" id="EditServiceDetailsID" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-8">
                                            <label class="" for="autoSizingSelect">Service Details Name</label>
                                            <input type="text" class="form-control" id="EditServiceDetailsName" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-8">
                                            <label class="" for="autoSizingSelect">Service Details Description</label>
                                            <input type="text" class="form-control" id="EditServiceDetailsDescription" aria-label="State" required>
                                        </div>
                                        <div class="col-sm-4 mt-4">
                                            <button class="btn btn-success button" id="UpdateServiceDetailsInfoButton" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Service Details'>
                                                <span>Update</span>
                                            </button>
                                        </div>
                                    </div>
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
                                    <div class="row d-flex justify-content-between">
                                        <div class="col-sm-8">
                                            <label class="" for="autoSizingSelect">Service Name</label>
                                            <input type="text" class="form-control" id="NewServiceName" aria-label="State" required>
                                            <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">
                                        </div>
                                        <div class="col-sm-4 mt-4">
                                            <button class="btn btn-success button" id="AddNewService" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Service'>
                                                <span>Save</span>
                                            </button>
                                        </div>
                                    </div>
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
                                    <div class="row">
                                        <div class="col-sm-6 ">
                                            <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">
                                            <label class="" for="autoSizingSelect">Service Name</label>
                                            <input type="text" class="form-control" id="GetServiceTypeName" aria-label="Name" readonly required>
                                            <input type="hidden" class="form-control" id="GetServiceTypeID" aria-label="ID" readonly required>
                                        </div>
                                        <div class="col-sm-6 ">
                                            <label class="" for="autoSizingSelect">Service Detail Name</label>
                                            <input type="text" class="form-control" id="NewServiceDetailsName" aria-label="Name" required>
                                        </div>
                                        <div class="col-sm-12 mt-2">
                                            <label class="" for="autoSizingSelect">Service Detail Description</label>
                                            <input type="text" class="form-control" id="ServiceDetailsDescription" aria-label="Description" required>
                                        </div>
                                        <div class="col-sm-10  mt-4">
                                            <button class="btn btn-success button width-75" id="AddNewServiceDetails" data-bs-toggle='tooltip' data-bs-placement='top' title='Create New Service Details'>
                                                <span>Save</span>
                                            </button>
                                        </div>
                                    </div>
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
                                    <div class="row">
                                        <div class="col-sm-6 ">
                                            <label class="" for="autoSizingSelect">Service Details Name</label>
                                            <input type="text" class="form-control" id="GetServiceDetailsName" aria-label="Name" readonly required>
                                            <input type="hidden" class="form-control" id="GetServiceDetailsID" aria-label="ID" readonly required>
                                            <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="" for="autoSizingSelect">Team Name</label>
                                            <select class="form-select" name="ServiceTypeNumber" id="GetServiceDetailsTeamNumber" required>
                                                <option value="">Choose Service Details Team Name </option>

                                            </select>
                                        </div>
                                        <div class="col-sm-10  mt-4">
                                            <button class="btn btn-success button width-75" id="AddNewServiceDetailsTeam" data-bs-toggle='tooltip' data-bs-placement='top' title='Create New Service Details'>
                                                <span>Save</span>
                                            </button>
                                        </div>
                                    </div>
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