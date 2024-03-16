<?php

session_start();


$no_sidebar = '';
$pageTitle = 'Login';


if (isset($_SESSION['user'])) {
    header('Location: home.php');  // Redirect To Home Page
}

include 'init.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $UserSessionID = $_POST['UserSessionID'];
    $password = $_POST['pass'];
    $sessionID = $_POST['sessionID'];
    $ARCHSTATUS = 'logon';

    $numericSessionID = preg_replace('/[^0-9]/', '', $sessionID);

    if (!empty($UserSessionID) && !empty($password)) {

        // // Query to retrieve The User Who Trying To Log In
        $loginInfo = "SELECT USERNAME, PASSWORD FROM DOCARCH.ACT_USERS_VW WHERE USERNAME = '" . $UserSessionID . "' ";

        // On your code add the latest parameter to bind the cursor resource to the Oracle argument
        // $loginInfo = "SELECT TICKETING.xxajmi_tkt_login_verify(:UserSessionID, :password) AS VERIFY FROM dual";

        // $loginInfo = 'BEGIN  xxajmi_tkt_login_verify(:UserSessionID,:password, :OUTPUT_CUR);  END;';
        $login = oci_parse($conn, $loginInfo);

        oci_bind_by_name($login, ':UserSessionID', $UserSessionID);
        oci_bind_by_name($login, ':password', $password);

        $resault = oci_execute($login);

        $row = oci_fetch_assoc($login);

        $CHECK = $row['VERIFY'];

        // Get the number of rows returned by the query
        // If Count > 0 This Mean The Database Contain Record About This Username 

        if (1) {

            // Query to fetch Last History Login ID To Create The Next ID
            // $lastHistoryID = "SELECT MAX(HISTORY_ID) FROM DOCARCH.XX_LOGIN_HIST@ST_CC";
            $lastHistoryID = "SELECT MAX(HISTORY_ID) FROM DOCARCH.XX_LOGIN_HIST";
            $historyNo     = oci_parse($conn, $lastHistoryID);
            oci_execute($historyNo);
            $result        = oci_fetch_array($historyNo);
            $NewHistoryID  = ++$result['MAX(HISTORY_ID)'];

            // $NewLogin = "INSERT INTO DOCARCH.XX_LOGIN_HIST@ST_CC (HISTORY_ID, USERNAME, SESSIONID, 
            //                                             LOGIN_TIME, ARCH_STATUS)
            //                     VALUES ($NewHistoryID, '$UserSessionID' , $numericSessionID, CURRENT_TIMESTAMP, '$ARCHSTATUS')";
            $NewLogin = "INSERT INTO DOCARCH.XX_LOGIN_HIST (HISTORY_ID, USERNAME, SESSIONID, 
                                                        LOGIN_TIME, ARCH_STATUS)
                                VALUES ($NewHistoryID, '$UserSessionID' , $numericSessionID, CURRENT_TIMESTAMP, '$ARCHSTATUS')";
            $AddNewLogin = oci_parse($conn, $NewLogin);
            $run = oci_execute($AddNewLogin);

            if ($run) {
                $_SESSION['user'] = $UserSessionID; // Register Session Name
                // $_SESSION['ID'] = $row['ID'];  // Register Session ID
                http_response_code(200);
                header('Location: home.php'); // Redirect To Dashboard Page
                echo json_encode(['status' => 'success', 'message' => 'Tables updated successfully']);
                exit();
            } else {
                http_response_code(404); // Internal Server Error
                echo json_encode(['status' => 'error', 'message' => oci_error($AddNewLogin)['message']]);
            }
        } else {
            echo "<div class=' d-flex justify-content-center mt-4'><div class='text-center alert alert-danger' style='max-width: 500px; '  >Incorrect Username or Password Please Try Again</div></div>";
        }
    } else {
        echo "<div class=' d-flex justify-content-center mt-4'><div class='text-center alert alert-danger' style='max-width: 500px; '  >Please Enter Username and Password</div></div>";
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
                    <?php
                    if ($sid == 'ARCHDEV') {
                        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Test_Application</span></div>';
                    } elseif ($sid == 'ARCHPROD') {
                        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;"># Production_Application</span></div>';
                    } else {
                        echo '<div style="text-align: right;"><span style="color: #0069d9; font-weight: bold; padding: 15px; margin-bottom: 5px;">' . $sid . '</span></div>';
                    }

                    ?>
                </div>
            </div>
        </div>
    </div> <!-- Row Div End -->
</div> <!-- Container Div End -->

<!-- Login Form For Sup Admin Side End -->

<?php

include $inc . 'footer.php';
?>