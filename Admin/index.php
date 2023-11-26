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

if (isset($_SESSION['member'])) {
    header('Location: dashboard.php');  // Redirect To Home Page
}

include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['user'];
    $password = $_POST['pass'];
    // $hashedPass = sha1($password);

    // // Query to retrieve a list of tables
    $loginInfo = "SELECT ID, NAME, PASSWORD FROM users WHERE NAME = :name AND PASSWORD = :pass";
    $login = oci_parse($conn, $loginInfo);

    // Bind the variables
    oci_bind_by_name($login, ":name", $username);
    oci_bind_by_name($login, ":pass", $password);
    // Execute the query
    oci_execute($login);

    // Get the number of rows returned by the query

    while ($row = oci_fetch_assoc($login)) {
        // Process each row
    }

    $numRows = oci_num_rows($login);

    oci_free_statement($login);

    // If Count > 0 This Mean The Database Contain Record About This Username 

    if ($numRows > 0) {
        $_SESSION['member'] = $username; // Register Session Name
        $_SESSION['ID'] = $row['ID'];  // Register Session ID
        header('Location: dashboard.php'); // Redirect To Dashboard Page
        exit();
    }
}

?>

<!-- Login Form For Admin Side Start -->

<div class="container mt-5 pt-5"> <!-- Container Div Start -->
    <div class="row"> <!-- Row Div Start -->
        <div class="col-12 col-sm-8 col-md-6 m-auto">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <i class="fa-solid fa-circle-user fa-5x my-4"></i>
                    <!-- Login Form Start -->
                    <form class="login w-50" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <h4 class="text-center ">Login Page</h4>
                        <input class="form-control mt-3 my-4 py-2 " type="text" name="user" placeholder="Enter your username please..." autocomplete="off">
                        <input class="form-control mt-3  py-2 " type="password" name="pass" placeholder="Enter your password please..." autocomplete="new-password">
                        <a href="validat.html" class="d-block mb-4 text-start text-decoration-underline" style="font-size: 15px;">Forget your password ?</a>
                        <input class="btn btn-primary btn-block " type="submit" value="Login">
                    </form>
                    <!-- Login Form End -->
                </div>
            </div>
        </div>
    </div> <!-- Row Div End -->
</div> <!-- Container Div End -->

<!-- Login Form For Admin Side End -->

<?php

include $inc . 'footer.php';
?>