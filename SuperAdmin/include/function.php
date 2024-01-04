<?php

/*
    ** Title Function That Echo The Page Title In Case The Page
    ** Has The Variable $pageTitle And Echo Default Title For Other Pages
*/

function getTitle() {
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

function redirect($url) {
    header('Location:' . $url);
    exit();
}

/*
    ** Count Function That Echo The Numbers of  Ticket in differnt Status
    ** Has one Paramter $status to display number of rows for each status
*/

function getcount($status) {

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

    // Select Tickets Based On User Permission and Ticket Status
    $allTickets = "SELECT 
                        *  
                    FROM 
                    TICKETING.TICKETS_TRANSACTIONS_SUB_V 
                    WHERE  TICKET_STATUS = :t_status ";
    $alltick = oci_parse($conn, $allTickets);
    oci_bind_by_name($alltick, ":t_status", $status);

    // Execute the query
    oci_execute($alltick);

    while ($row = oci_fetch_assoc($alltick)) {
        // Process each row
    }
    $allRows = oci_num_rows($alltick);
    return $allRows;
}

/*
    ** Insert Function That Insert User 
    Into global_temp_table Table and Return User 
    Permissions To Ticket Transation Table **
*/

function InsertUserID() {

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

    // Select UserID Based on UserName To Insert It Into Table global_temp_table
    $userNamePre = "SELECT USER_ID FROM ACT_USERS_VW WHERE USERNAME = :t_name";
    $prevlag = oci_parse($conn, $userNamePre);
    oci_bind_by_name($prevlag, ":t_name", $_SESSION["user"]);
    oci_execute($prevlag);
    $prevlegs = oci_fetch_assoc($prevlag);
    $userIDPreResault = $prevlegs['USER_ID'];
    $userNamePreResault = 'USER_ID';

    // Insert UserID Into global_temp_table Table After Returned From User Table
    $ticketTransation = "INSERT INTO ticketing.global_temp_table (NAME, VALUE)  
                        VALUES (:t_user, :t_value)";
    $insertValue = oci_parse($conn, $ticketTransation);
    oci_bind_by_name($insertValue, ':t_user', $userNamePreResault);
    oci_bind_by_name($insertValue, ':t_value', $userIDPreResault);
    $run = oci_execute($insertValue, OCI_NO_AUTO_COMMIT);
}

// function SelectTicket()
// {

//     // Oracle database connection settings
//     $host = '192.168.15.245';
//     $port = '1521';
//     $sid = 'ARCHDEV';
//     //old
//     // $username = 'ticketing';
//     // $password = 'ticketing';
//     //new
//     $username = 'selfticket';
//     $password = 'selfticket';

//     // Establish a connection to the Oracle database
//     $conn = oci_connect($username, $password, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SID=$sid)))");

//     if (!$conn) {
//         $e = oci_error();
//         trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
//         echo "Connectoin to Oracle Database Failed!<br>";
//     }

//     $allTicket = "SELECT * FROM TICKETING.TICKETS_TRANSACTIONS_SUB_V ORDER BY TICKET_NO DESC";

//     $all = oci_parse($conn, $allTicket);

//     // Execute the query
//     $run =  oci_execute($all);

//     return $run;
// }
