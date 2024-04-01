<?php

session_start();        // Start Session

include 'DBConnection.php';

$active = 'N';
$userSession = $_SESSION['user'];
// Query to fetch users Information based on User Name
$activeUsers   = "UPDATE TICKETING.xxajmi_ticket_user_info SET ACTIVE_LOGIN = '" . $active . "'  WHERE USERNAME = '" .  $userSession . "'";
$actives       = oci_parse($conn, $activeUsers);
oci_execute($actives);


session_unset();        // Unset  Data

session_destroy();      // Destroy The Session

header('Location: https://sshr.alajmi.com.sa/public/index.php/login');

exit();
