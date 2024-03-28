<?php
$startTime = microtime(true);
/*
    ================================================
    == Home Page
    == You Can Access to the other pages from this page  
    ================================================
*/

ob_start(); // Output Buffering Start

session_start();

include 'init.php';  // This File Contain ( Header, Footer, Navbar, JS File,  Style File ) File
// Check if the user is logged in and the session is active
// if (isset($_SESSION['user'])) {
//     // Check if the last activity time is set
//     if (isset($_SESSION['LAST_ACTIVITY'])) {
//         // Calculate the time difference since the last activity
//         $elapsedTime = time() - $_SESSION['LAST_ACTIVITY'];

//         // Check if the elapsed time exceeds 2 minutes (120 seconds)
//         if ($elapsedTime > 3600) {
//             // Destroy the session
//             session_unset(); // Unset all session variables
//             session_destroy(); // Destroy the session

//             // Redirect the user to the login page
//             header("Location: index.php");
//             exit(); // Ensure that no further code is executed
//         }
//     }

//     // Update the last activity time
//     $_SESSION['LAST_ACTIVITY'] = time();
// }

// if (isset($_SESSION['user'])) {

// $userFileNum = 99999998;

// $userInfo   = "SELECT USER_ID, USERNAME  FROM TICKETING.xxajmi_ticket_user_info WHERE EBS_EMPLOYEE_ID = '" . $userFileNum . "'";
// $info       = oci_parse($conn, $userInfo);
// oci_execute($info);
// $row        = oci_fetch_assoc($info);
// // $UserSessionID = $row['USER_ID'];
// // $UserSessionName = $row['USERNAME'];
// $_SESSION['USER_ID'] = $row['USER_ID'];
// $_SESSION['USERNAME'] = $row['USERNAME'];


// echo $_SESSION['USER_ID'] . '<br>';
// echo $_SESSION['USERNAME'] . '<br>';



/*******  This Condition to print DB name to know which one Opened  ***************/
if ($sid == 'ARCHDEV') {
    echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Test_Application</span></div>';
} elseif ($sid == 'ARCHPROD') {
    echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Production_Application</span></div>';
} else {
    echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;">' . $sid . '</span></div>';
}
?>

<input type="hidden" class="form-control" id="UserSessionID" aria-label="State" value="<?php echo $row['USER_ID']  ?>" disabled readonly>
<!-- Service Page  Start -->
<main class="content px-3 py-2"> <!-- Main Start -->
    <div class="container-fluid"> <!-- Container-fluid Div Start -->
        <div class="mb-3">

            <h2 class="text-center mt-3 ">Ticketing System</h2>
            <div class="home">
                <div class="homeMenu">
                    <a href="home.php?hashkey=135DB93A259D4EA6E060A8C0E30F24C9">ticketing system</a>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid Div End  -->
</main> <!-- Main End -->


<div class="overlay" id="spinner">
    <span class="loader"></span>
</div>

<?php

include $inc . 'footer.php';
// } else {
//     header('Location: index.php');
//     exit();
// }
$endTime = microtime(true); // CALCULAT page loaded time

$timeTaken = $endTime - $startTime;

echo "<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>Page Loaded In: " . round($timeTaken, 2)  . " Seconds</h5>";
ob_end_flush(); // Release The Output
?>