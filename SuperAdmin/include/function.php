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
    ** Redirect Function That Redirect to the prives page 
    ** Has The Parmeter $url that reference to the next page 
*/

function redirect($url)
{
    header('Location:' . $url);
    exit();
}


/*
    ** Count Function That Echo The Numbers of  Ticket in differnt Status
    ** Has one Paramter $status to display number of rows for each status
*/

function getcount($status)
{

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

    // Select Users Based On Department E

    $leaderMember = "SELECT DEPARTMENT FROM users  WHERE NAME = :u_name";

    $leader = oci_parse($conn, $leaderMember);
    oci_bind_by_name($leader, ":u_name", $_SESSION["leader"]);

    // Execute the query
    oci_execute($leader);

    $user = oci_fetch_assoc($leader);

    $allTickets = "SELECT 
                        tickets.*, service_type.type  
                    FROM 
                        tickets
                    INNER JOIN
                        service_type
                    ON
                        tickets.service_type = service_type.id
                    WHERE service_type.type = :t_service
                    AND tickets.status = :t_status ";
    $alltick = oci_parse($conn, $allTickets);
    oci_bind_by_name($alltick, ":t_service", $user['DEPARTMENT']);
    oci_bind_by_name($alltick, ":t_status", $status);

    // Execute the query
    oci_execute($alltick);

    while ($row = oci_fetch_assoc($alltick)) {
        // Process each row
    }

    $allRows = oci_num_rows($alltick);

    return $allRows;
}
