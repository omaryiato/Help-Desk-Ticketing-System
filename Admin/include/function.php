<?php

/*
    ** Title Function That Echo The Page Title In Case The Page
    ** Has The Variable $pageTitle And Echo Default Title For Other Pages
*/

function getTitle()
{
    global $pageTitle;

    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'Ticketing System';
    }
}

/*
    ** Count Function That Echo The Numbers of  Ticket in differnt Status
    ** Has one Paramter $status to display number of rows for each status
*/

function getcount($status)
{

    $userName = $_SESSION['member'];

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

    $allTicket = "SELECT ID FROM users  WHERE name = :v_user ";

    $all = oci_parse($conn, $allTicket);

    // Bind the variables
    oci_bind_by_name($all, ":v_user", $userName);

    // Execute the query
    oci_execute($all);

    // Fetch the result
    oci_fetch($all);

    $id = oci_result($all, 'ID');

    $allTickets = "SELECT * FROM tickets WHERE TEAM_MEMBER_ASSIGNED_ID = :v_member AND status = :t_status";
    $alltick = oci_parse($conn, $allTickets);

    oci_bind_by_name($alltick, ":v_member", $id);

    oci_bind_by_name($alltick, ":t_status", $status);

    // Execute the query
    oci_execute($alltick);

    while ($row = oci_fetch_assoc($alltick)) {
        // Process each row
    }

    $allRows = oci_num_rows($alltick);

    return $allRows;
}
