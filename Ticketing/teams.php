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

    $pageTitle = 'Team Members Page';
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

    <!-- Team Member Information Start -->
    <main class="content px-3 py-2"> <!-- Main Start -->
        <div class="container-fluid"> <!-- Container-fluid Div Start -->
            <div class="mb-3">
                <h2 class="text-center mt-3 ">Team Members</h2>
                <div class=" container-fluid  mt-2">
                    <div class="row d-flex justify-content-center">
                        <div class=" col-sm-4 mx-1 " style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="d-flex justify-content-between">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark d-inline">Team</h3>
                                </div>
                                <div class="mx-2 my-2" id="updateTeamInfoButton">
                                    <button class="btn btn-primary" data-bs-toggle='modal' data-bs-target="#NewTeam" data-bs-whatever="NewTeam" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Team'>
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Add New Team</span>
                                    </button>

                                </div>
                            </div>

                            <div class="row">
                                <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">
                                <div class='col-sm-3'>
                                    <label class="" for="autoSizingSelect">Team No</label>
                                    <input type="text" class="form-control" id="TeamNoID" aria-label="State" readonly>
                                </div>
                                <div class="col-sm-8">
                                    <label class="" for="autoSizingSelect">Name</label>
                                    <select class="form-select TeamName" name="TeamName" id="TeamName" required>
                                        <option value="">Choose Team Name</option>
                                        <?php
                                        // // Query to retrieve a list of tables
                                        $teamNo = "SELECT  TEAM_NO, TEAM_NAME  FROM TICKETING.TEAMS";
                                        $team = oci_parse($conn, $teamNo);
                                        // Execute the query
                                        oci_execute($team);
                                        while ($teams = oci_fetch_assoc($team)) {
                                            echo "<option value='" . $teams['TEAM_NO'] . "'>" . $teams['TEAM_NAME'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-9 my-2">
                                    <label class="" for="autoSizingSelect">Description</label>
                                    <textarea type="text" class="form-control" id="description" aria-label="City" readonly></textarea>
                                </div>
                                <div class="col-sm-4 my-2">
                                    <label class="" for="autoSizingSelect">Department</label>
                                    <input type="text" class="form-control" id="dept" aria-label="State" readonly>
                                    <!-- <select class="form-select dept" name="dept" id="dept" required>
                                            <option value="">Choose Department</option>
                                        </select> -->
                                </div>
                                <div class='col-sm-4 my-2'>
                                    <label class="" for="autoSizingSelect">Branch</label>
                                    <input type="text" class="form-control" id="branch" aria-label="State" readonly>
                                    <!-- <select class="form-select branch" name="branch" id="branch" required>
                                            <option value="">Choose Branch</option>
                                        </select> -->
                                </div>
                                <div class='check  col-sm-4' style='display: flex; justify-content: center; align-items: center;'>
                                    <label class="pe-2" for="autoSizingSelect">Status</label>
                                    <input type='checkbox' name='status' id='status' disabled>
                                </div>
                            </div>
                        </div>
                        <div class=" col-sm-7 mx-1" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="d-flex justify-content-between">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark">Delegate Supervisors</h3>
                                </div>
                                <div class="mx-2 my-2" id="DelegateMemberButtons">

                                </div>
                            </div>

                            <div class=" text-center mt-1" id="waitingDelegateMember">
                                <div class="alert alert-primary change" role="alert">
                                    There Is No Data You Can See It Yet.
                                </div>
                            </div>

                            <div class="scroll">
                                <table class="main-table text-center table table-bordered mt-3 ">
                                    <thead id="DelegateMemberHeadTable">

                                    </thead>
                                    <tbody id="DelegateMemberBodyTable">

                                    </tbody>
                                </table>
                                <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" teamMember container-fluid mt-2">
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm-11 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="d-flex justify-content-between">
                                <div class="div">
                                    <h3 class=" mt-3 mb-4 text-dark d-inline">Team Members</h3>
                                </div>
                                <div class="mx-2 my-2" id="TeamMemberButtons">

                                </div>
                            </div>

                            <div class=" text-center mt-1" id="waitingTeamMemberInfo">
                                <div class="alert alert-primary change" role="alert">
                                    There Is No Data You Can See It Yet.
                                </div>
                            </div>

                            <div class="teamMemberTable mx-2">
                                <table class="main-table text-center table table-bordered mt-3  ">
                                    <thead id="TeamMemberHeadTable">

                                    </thead>
                                    <tbody id="TeamMemberBodyTable">

                                    </tbody>
                                </table>
                                <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- Container-fluid Div End  -->
    </main> <!-- Main End -->
    <!-- Team Member Information End -->


    <!-- Add New Team Pop Up Form Start -->
    <div class="modal fade" id="NewTeam" tabindex="-1" aria-labelledby="NewTeamPopupLabel" aria-hidden="true">
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
                                <h2 class="text-center" id="NewTeamPopupLabel">Add New Team</h2>
                                <div class="container mb-4 mt-4">
                                    <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">
                                    <div class="row d-flex justify-content-between">
                                        <div class="col-sm-8 mb-2">
                                            <label class="" for="autoSizingSelect">Team Name</label>
                                            <input type="text" class="form-control" id="NewTeamName" aria-label="State" required>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label class="" for="autoSizingSelect">Branch Code</label>
                                            <input type="text" class="form-control" id="branchCode" aria-label="State" required>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="" for="autoSizingSelect">Description</label>
                                            <input type="text" class="form-control" id="description" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="" for="autoSizingSelect">Department ID</label>
                                            <input type="text" class="form-control" id="departmentID" aria-label="State" required>
                                        </div>
                                        <div class="col-sm-4 mt-4">
                                            <button class="btn btn-success button" id="AddNewTeam" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Team'>
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
    <!-- Add New Service Pop Up Form End -->

    <!-- Edit Team Information Pop Up Form Start -->
    <div class="modal fade" id="EditTeamInformation" tabindex="-1" aria-labelledby="EditTeamInformationPopupLabel" aria-hidden="true">
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
                                <h2 class="text-center" id="EditTeamInformationPopupLabel">Edit Team Information</h2>
                                <div class="container mb-4 mt-4">
                                    <div class="row d-flex justify-content-between">
                                        <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>">

                                        <div class="col-sm-10">
                                            <input type="hidden" class="form-control" id="EditTeamID" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-10 mb-2">
                                            <label class="" for="autoSizingSelect">Team Name</label>
                                            <input type="text" class="form-control" id="EditTeamName" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-10 mb-2">
                                            <label class="" for="autoSizingSelect">Description</label>
                                            <input type="text" class="form-control" id="EditTeamDescription" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="" for="autoSizingSelect">Branch Code</label>
                                            <input type="text" class="form-control" id="EditTeamBranchCode" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="" for="autoSizingSelect">Department ID</label>
                                            <input type="text" class="form-control" id="EditTeamDepartmentID" aria-label="State" required>
                                        </div>

                                        <div class='check  col-sm-4 mb-2'>
                                            <label class="pe-2" for="autoSizingSelect">Active</label>
                                            <input type='checkbox' name='status' id='EditTeamStatus'>
                                        </div>

                                        <div class="col-sm-4 mt-2 ">
                                            <button class="btn btn-success button" id="UpdateTeamInfoButton" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Service Details'>
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
    <!-- Edit Team Information Pop Up Form End -->

    <!-- New Service Details Pop Up Form Start -->
    <div class="modal fade" id="NewTeamMember" tabindex="-1" aria-labelledby="NewTeamMemberPopupLabel" aria-hidden="true">
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
                                <h2 class="text-center" id="NewTeamMemberPopupLabel">Add New Service Detail</h2>
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