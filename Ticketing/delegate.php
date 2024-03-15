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

    $userSession = $_SESSION['user'];
    // Query to fetch users Information based on User Name
    $userInfo   = "SELECT USER_ID  FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = '" . $userSession . "'";
    $info       = oci_parse($conn, $userInfo);
    oci_execute($info);
    $row        = oci_fetch_assoc($info);
    $UserSessionID = $row['USER_ID'];

?>

    <input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>" disabled readonly>
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
                                    <button type="submit" class="btn btn-success btn-lg mt-3  " id="DelegateNewUser"> <i class="fa-solid fa-user-minus pe-2"></i> Delegate</button>
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