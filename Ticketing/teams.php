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
?>

    <!-- Team Member Information Start -->
    <main class="content px-3 py-2"> <!-- Main Start -->
        <div class="container-fluid"> <!-- Container-fluid Div Start -->
            <div class="mb-3">

                <h2 class="text-center mt-3 mb-5">Team Members</h2>

                <div class="scro container-fluid mb-4 mt-4">
                    <div class="row d-flex justify-content-center">

                        <div class="col-sm-4 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="div">
                                <h3 class=" mt-3 mb-4 text-dark">Team</h3>
                            </div>
                            <div class="row">
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
                                    <textarea type="text" class="form-control" aria-label="City"></textarea>
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

                                    <input type='checkbox' name='status' id='status'>
                                </div>
                            </div>
                        </div>

                        <div class=" col-sm-7 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="div">
                                <h3 class=" mt-3 mb-4 text-dark">Delegate Supervisors</h3>
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
                                    <tbody id="tableBody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" container-fluid mb-4 mt-4">
                    <div class="row d-flex justify-content-center">

                        <div class="col-sm-11 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="div">
                                <h3 class=" mt-3 mb-4 text-dark">Team Members</h3>
                            </div>
                            <div class="scroll">
                                <table class="main-table text-center table table-bordered mt-3 ">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Active</th>
                                            <th>Supervisor</th>
                                            <th style="min-width: 100px;">Manager</th>
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