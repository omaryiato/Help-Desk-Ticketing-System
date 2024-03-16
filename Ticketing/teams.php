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

    $pageTitle = 'Team Members Page';

    include 'init.php';  // This File Contain ( Header, Footer, Navbar, Function, JS File,  Style File ) File

    $userSession = $_SESSION['user'];
    // Query to fetch users Information based on User Name
    $userInfo   = "SELECT USER_ID  FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = '" . $userSession . "'";
    $info       = oci_parse($conn, $userInfo);
    oci_execute($info);
    $row        = oci_fetch_assoc($info);

    if ($sid == 'ARCHDEV') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Test_Application</span></div>';
    } elseif ($sid == 'ARCHPROD') {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Production_Application</span></div>';
    } else {
        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;">' . $sid . '</span></div>';
    }
?>

    <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>" disabled readonly>
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

                                <div class='col-sm-3'>
                                    <label class="" for="TeamNoID">Team No</label>
                                    <input type="text" class="form-control" id="TeamNoID" aria-label="State" readonly disabled>
                                </div>
                                <div class="col-sm-8">
                                    <label class="" for="TeamName">Name</label>
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
                                <div class="col-sm-8 my-2">
                                    <label class="" for="teamDescription">Description</label>
                                    <textarea type="text" class="form-control" id="teamDescription" aria-label="City" readonly disabled></textarea>
                                </div>
                                <div class='check  col-sm-3' style='display: flex; justify-content: center; align-items: center;'>
                                    <label class="pe-2" for="status">Status</label>
                                    <input type='checkbox' name='status' id='status' disabled>
                                </div>
                                <div class="col-sm-8 my-2">
                                    <label class="" for="dept">Department</label>
                                    <input type="text" class="form-control" id="dept" aria-label="State" readonly disabled>
                                    <input type="hidden" class="form-control" id="depID" aria-label="State" readonly disabled>
                                </div>
                                <div class='col-sm-3 my-2'>
                                    <label class="" for="branch">Branch</label>
                                    <input type="text" class="form-control" id="branch" aria-label="State" readonly disabled>
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
                                    <form class="row d-flex justify-content-between" id="AddNewTeamForm">
                                        <div class="col-sm-10 mb-2">
                                            <label class="" for="NewTeamName">Team Name</label>
                                            <input type="text" class="form-control" id="NewTeamName" name="NewTeamName" aria-label="State" required>
                                        </div>
                                        <div class="col-sm-10 mb-2">
                                            <label class="" for="description">Description</label>
                                            <input type="text" class="form-control" id="description" name="description" aria-label="State" required>
                                        </div>
                                        <div class="col-sm-10 mb-2">
                                            <label class="" for="departmentID">Department ID</label>
                                            <select class="form-select departmentID" name="departmentID" id="departmentID" required>
                                                <option value="" selected>Choose Department ID...</option>
                                                <option value="1017">IT Dept-Tracking</option>
                                                <option value="1013">Information Technology Dept</option>
                                                <option value="1005">Legal Affairs</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="" for="branchCode">Branch Code</label>
                                            <select class="form-select branchCode" name="branchCode" id="branchCode" required>
                                                <option value="null" selected>Choose Branch Code...</option>
                                                <option value="RYD">RYD</option>
                                                <option value="HUF">HUF</option>
                                                <option value="JIZ">JIZ</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-5 mt-4">
                                            <button type="submit" class="btn btn-success button" id="AddNewTeam" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Team'>
                                                <span>Save</span>
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
                                    <form class="row d-flex justify-content-between" id="UpdateTeamInformationForm">
                                        <div class="col-sm-10">
                                            <input type="hidden" class="form-control" id="EditTeamID" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-10 mb-2">
                                            <label class="" for="EditTeamName">Team Name</label>
                                            <input type="text" class="form-control" id="EditTeamName" name="EditTeamName" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-10 mb-2">
                                            <label class="" for="EditTeamDescription">Description</label>
                                            <input type="text" class="form-control" id="EditTeamDescription" name="EditTeamDescription" aria-label="State" required>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="" for="EditTeamBranchCode">Branch Code</label>
                                            <select class="form-select EditTeamBranchCode" name="EditTeamBranchCode" id="EditTeamBranchCode" required>

                                            </select>
                                        </div>

                                        <div class="col-sm-4">
                                            <label class="" for="EditTeamDepartmentID">Department ID</label>
                                            <select class="form-select EditTeamDepartmentID" name="EditTeamDepartmentID" id="EditTeamDepartmentID" required>

                                            </select>
                                        </div>

                                        <div class='check  col-sm-4 mb-2'>
                                            <label class="pe-2" for="EditTeamStatus">Active</label>
                                            <input type='checkbox' name='status' id='EditTeamStatus'>
                                        </div>

                                        <div class="col-sm-4 mt-2 ">
                                            <button type="submit" class="btn btn-success button" id="UpdateTeamInfoButton" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Service Details'>
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>Update</span>
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
    <!-- Edit Team Information Pop Up Form End -->

    <!-- Add New Team Member  Details Pop Up Form Start -->
    <div class="modal fade" id="NewTeamMember" tabindex="-1" aria-labelledby="NewTeamMemberPopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- New Team  Start -->
                    <main class="content px-3 py-2"> <!-- Main Start -->
                        <div class="container-fluid"> <!-- Container-fluid Div Start -->
                            <div class="mb-3">
                                <h2 class="text-center" id="NewTeamMemberPopupLabel">Add New Member</h2>
                                <div class="container mb-4 mt-4">
                                    <form class="row" id="AddNewTeamMemberForm">
                                        <div class="col-sm-6 ">
                                            <label class="" for="GetTeamName">Team Name</label>
                                            <input type="text" class="form-control" id="GetTeamName" aria-label="Name" required readonly disabled>
                                            <input type="hidden" class="form-control" id="GetTeamID" aria-label="ID" readonly required disabled>
                                        </div>

                                        <div class="col-sm-6 ">
                                            <label class="" for="GetMemberName">Member Name</label>
                                            <input type="hidden" class="form-control" id="GetDeptID" aria-label="ID" readonly required disabled>
                                            <select class="form-select GetMemberName" name="GetMemberName" id="GetMemberName" required>

                                            </select>
                                        </div>

                                        <div class="col-sm-12 mt-2">
                                            <label class="" for="GetMemberDeacription">Description</label>
                                            <input type="text" class="form-control" id="GetMemberDeacription" name="GetMemberDeacription" aria-label="Description" required>
                                        </div>
                                        <div class="col-sm-10  mt-4">
                                            <button type="submit" class="btn btn-success button width-75" id="AddNewTeamMemberButton" data-bs-toggle='tooltip' data-bs-placement='top' title='Create New Service Details'>
                                                <span>Save</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- Container-fluid Div End  -->
                    </main> <!-- Main End -->
                    <!-- New Team Info End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Add New Team Member Pop Up Form End -->
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