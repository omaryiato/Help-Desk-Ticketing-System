<?php

session_start();
$no_sidebar = '';
$pageTitle = 'Login';


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

if (isset($_SESSION['user'])) {
    header('Location: TicketTransaction.php');  // Redirect To Home Page
}

include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $sessionID = $_POST['sessionID'];
    $UserSessionID = $_POST['UserSessionID'];
    $ARCHSTATUS = 'logon';

    $numericSessionID = preg_replace('/[^0-9]/', '', $sessionID);

    // // Query to retrieve The User Who Trying To Log In
    $loginInfo = "SELECT USERNAME FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = '" . $UserSessionID . "'";
    $login = oci_parse($conn, $loginInfo);

    $resault = oci_execute($login);

    // Get the number of rows returned by the query

    while ($row = oci_fetch_assoc($login)) {
        // Process each row
    }

    $numRows = oci_num_rows($login);

    oci_free_statement($login);

    // If Count > 0 This Mean The Database Contain Record About This Username 

    if ($numRows > 0) {

        // Query to fetch Last History Login ID To Create The Next ID
        $lastHistoryID = "SELECT MAX(HISTORY_ID) FROM DOCARCH.XX_LOGIN_HIST";
        $historyNo     = oci_parse($conn, $lastHistoryID);
        oci_execute($historyNo);
        $result        = oci_fetch_array($historyNo);
        $NewHistoryID  = ++$result['MAX(HISTORY_ID)'];

        $NewLogin = "INSERT INTO DOCARCH.XX_LOGIN_HIST (HISTORY_ID, USERNAME, SESSIONID, 
                                                    LOGIN_TIME, ARCH_STATUS)
                            VALUES ($NewHistoryID, '$UserSessionID' , $numericSessionID, CURRENT_TIMESTAMP, '$ARCHSTATUS')";
        $AddNewLogin = oci_parse($conn, $NewLogin);
        $run = oci_execute($AddNewLogin);

        if ($run) {
            $_SESSION['user'] = $UserSessionID; // Register Session Name
            // $_SESSION['ID'] = $row['ID'];  // Register Session ID
            http_response_code(200);
            header('Location: TicketTransaction.php'); // Redirect To Dashboard Page
            echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
            exit();
        } else {
            http_response_code(404); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => oci_error($AddNewLogin)['message']]);
        }
    } else {
        echo "<div class=' d-flex justify-content-center mt-4'><div class='text-center alert alert-danger' style='max-width: 500px; '  >Incorrect Username or Password Please Try Again</div></div>";
    }
}

?>

<!-- Login Form For Sup Admin Side Start -->

<div class="container mt-5 pt-5"> <!-- Container Div Start -->
    <div class="row"> <!-- Row Div Start -->
        <div class="col-12 col-sm-8 col-md-6 m-auto">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <i class="fa-solid fa-circle-user fa-5x my-4"></i>
                    <!-- Login Form Start -->
                    <form class="login w-50" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="loginForm">
                        <h4 class="text-center ">Login Page</h4>
                        <input class="form-control mt-3 my-4 py-2 " id="UserSessionID" type="text" name="UserSessionID" placeholder="Enter your username please..." autocomplete="off">
                        <input class="form-control mt-3  py-2 " type="password" name="pass" placeholder="Enter your password please..." autocomplete="new-password">
                        <input type="hidden" id="sessionID" name="sessionID" value="<?php echo session_id() ?>" readonly />
                        <a href="validat.html" class="d-block mb-4 text-start text-decoration-underline" style="font-size: 15px;">Forget your password ?</a>
                        <input class="btn btn-primary btn-block " type="submit" value="Login" id="login">
                    </form>
                    <!-- Login Form End -->
                </div>
            </div>
        </div>
    </div> <!-- Row Div End -->
</div> <!-- Container Div End -->

<!-- Login Form For Sup Admin Side End -->

<?php

include $inc . 'footer.php';
?>