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

    $pageTitle = 'Delegate Page';
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

    <!-- Delegate Page  Start -->
    <main class="content px-3 py-2"> <!-- Main Start -->
        <div class="container-fluid"> <!-- Container-fluid Div Start -->
            <div class="mb-3">

                <h2 class="text-center mt-3 mb-5">Delegate Supervisor</h2>

                <div class="scro container-fluid mb-4 mt-4">
                    <div class="row d-flex justify-content-around">

                        <div class=" col-sm-10 mx-2" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                            <div class="div">
                                <h3 class=" mt-3 mb-4 text-dark">Delegate Supervisor</h3>
                            </div>

                            <div class='col-sm-4'>
                                <label class="" for="autoSizingSelect">Team Name</label>
                                <select class="form-select delegate" name="delegate" id="delegate" required>
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
        </div><!-- Container-fluid Div End  -->
    </main> <!-- Main End -->
    <!-- Delegate Page  End -->

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