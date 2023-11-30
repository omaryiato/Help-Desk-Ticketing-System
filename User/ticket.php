<?php

/*
    ================================================
    == Tracking Ticketing Page
    == You Can Create New Ticket | Edit | Delete Ticket From Here 
    ================================================
*/

ob_start(); // Output Buffering Start

session_start();

if (isset($_SESSION['employee'])) {

    $pageTitle = 'Tickets Page';

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

    include 'init.php';

    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = 'Manage';
    }

    // Start Manage Page

    if ($action == 'Manage') { //Manage Page 

        // Select All Users Except Admin 

        $ticketInfo = "SELECT 
                            tickets.*, users.name AS Username, service_type.type, service_details.service_details
                        FROM 
                            tickets  
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id
                        INNER JOIN
                            service_type
                        ON
                            tickets.service_type = service_type.id
                        JOIN
                            service_details
                        ON
                            tickets.service_detailS = service_details.id
                        WHERE 
                            users.name = :v_user
                        ORDER BY 
                            tickets.ID DESC";

        $ticket = oci_parse($conn, $ticketInfo);
        $userName = $_SESSION['employee'];

        // Bind the variables
        oci_bind_by_name($ticket, ":v_user", $userName);

        // Execute the query
        oci_execute($ticket);

        // Fetch All Data From Tickets Table

        if (!empty(oci_execute($ticket))) {

?>
            <!-- Tracking Ticket Page Start-->

            <!-- Ticket Table Start -->
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="mb-3">
                        <h2 class="text-center mt-3">Manage Tickets</h2>
                        <div class="container">
                            <div class="table-responsive">
                                <table class="main-table text-center table table-bordered mt-3">
                                    <tr>
                                        <td>Ticket_NO</td>
                                        <td>User Name</td>
                                        <td>Description</td>
                                        <td>Status</td>
                                        <td>Service Type</td>
                                        <td>Service Details</td>
                                        <td>Tags</td>
                                        <td>User Comments</td>
                                        <td>Admin Comments</td>
                                        <td>Tickets Date</td>
                                        <td>UPDATED_DATE</td>
                                        <td>Created By</td>
                                        <td>Control</td>
                                    </tr>
                                    <?php
                                    while ($tick = oci_fetch_assoc($ticket)) {

                                        echo "<tr>\n";
                                        echo "<td>" . $tick["ID"] . "</td>\n";
                                        echo "<td>" . $tick["USERNAME"] . "</td>\n";
                                        echo "<td>" . $tick["DESCRIPTION"] . "</td>\n";
                                        echo "<td>";
                                        if ($tick["STATUS"] == 'initial') {
                                            echo '<span class="badge bg-primary ">' . $tick["STATUS"] . '</span>';
                                        } elseif ($tick["STATUS"] == 'assign') {
                                            echo '<span class="badge bg-warning">' . $tick["STATUS"] . '</span>';
                                        } elseif ($tick["STATUS"] == 'started') {
                                            echo '<span class="badge bg-info">' . $tick["STATUS"] . '</span>';
                                        } elseif ($tick["STATUS"] == 'solved') {
                                            echo '<span class="badge bg-success">' . $tick["STATUS"] . '</span>';
                                        } elseif ($tick["STATUS"] == 'completed') {
                                            echo '<span class="badge bg-success">' . $tick["STATUS"] . '</span>';
                                        } elseif ($tick["STATUS"] == 'rejected') {
                                            echo '<span class="badge bg-danger">' . $tick["STATUS"] . '</span>';
                                        }
                                        echo "</td>\n";
                                        echo "<td>" . $tick["TYPE"] . "</td>\n";
                                        echo "<td>" . $tick["SERVICE_DETAILS"] . "</td>\n";
                                        echo "<td>" . $tick["TAGS"] . "</td>\n";
                                        echo "<td>" . $tick["COMMENTS"] . "</td>\n";
                                        echo "<td>" . $tick["ADMIN_COMMENTS"] . "</td>\n";
                                        echo "<td>" . $tick["CREATED_DATE"] . "</td>\n";
                                        echo "<td>" . $tick["UPDATED_DATE"] . "</td>\n";
                                        echo "<td>" . $tick["CREATED_BY"] . "</td>\n";
                                        echo "<td> 
                                        <div style='display: flex; justify-content: center; align-items: center;'>
                                        <a style='margin-right: 5px;' href='?action=Edit&tickid=" . $tick["ID"] . "' class='btn btn-warning'  data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Ticket' ><i class='fa-solid fa-pen-to-square '></i></a>
                                        <a style='margin-right: 5px;' href='?action=View&tickid=" . $tick["ID"] . "' class='btn btn-info text-white'  data-bs-toggle='tooltip' data-bs-placement='top' title='View Ticket' ><i class='fa-solid fa-eye '></i></a>
                                        <button style='margin-right: 5px;' value='" . $tick["ID"] . "' class='btn btn-danger deleteTicket'  data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Ticket' ><i class='fa-solid fa-trash-can '></i></button>";
                                        if ($tick["STATUS"] == 'solved') {
                                    ?>
                                            <button class='btn btn-success' style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#exampleModal" data-bs-whatever="Admin" data-bs-toggle='tooltip' data-bs-placement='top' title='Complete Ticket'><i class='fa-solid fa-check'></i></button>

                                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Any Comment For User</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form>
                                                                <!-- <div class="mb-3">
                                                                <label for="recipient-name" class="col-form-label">Recipient:</label>
                                                                <input type="text" class="form-control" id="recipient-name">
                                                                        </div> -->
                                                                <div class="mb-3">
                                                                    <label for="message-text" class="col-form-label">Message:</label>
                                                                    <textarea class="form-control comment" id="message-text"></textarea>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="button" value='<?php echo $tick["ID"] ?>' class="btn btn-primary completTicket">Send message</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                        echo "</div>
                                            </td>\n";
                                        echo "</tr>\n";
                                    }

                                    oci_free_statement($ticket);
                                    ?>
                                </table>
                            </div>

                            <a class="btn btn-primary " href="?action=Add" style="margin-top: 50px; margin-bottom: 30px;"><i class="fa-solid fa-plus"></i> Create New Ticket </a>
                        </div>
                    </div>
                </div>

            </main>
            <!-- Ticket Table End -->
        <?php  }
    } elseif ($action == 'Add') {  // Create New Ticket Page 
        ?>
        <!-- New Ticket Form Start -->
        <main class="content px-3 py-2"> <!-- Main Start  -->
            <div class="container-fluid"> <!-- Container-fluid Div Start  -->
                <div class="mb-3">

                    <h2 class="text-center mt-3">Create New Ticket</h2>

                    <div class="container"> <!-- Container Div Start  -->
                        <!-- Edit Ticket Information Form Start -->
                        <form class="form-horizontal" action="" method="POST" style="margin: auto;">

                            <!-- Start Name SelectBox -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="">User Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="<?php echo $_SESSION['employee'] ?>" class=" form-control name" disabled>
                                </div>
                            </div>
                            <!-- End Name  SelectBox -->

                            <!-- Start Service Type Field Start-->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="service">Service Type</label>
                                <div class="col-sm-10">
                                    <select class="form-select service" name="service" id="service" required>
                                        <option value="">Choes Service</option>
                                        <?php
                                        // // Query to retrieve a list of tables
                                        $department = "SELECT  * FROM service_type";
                                        $dep = oci_parse($conn, $department);

                                        // Execute the query
                                        oci_execute($dep);

                                        while ($dept = oci_fetch_assoc($dep)) {
                                            echo "<option value='" . $dept['ID'] . "'>" . $dept['TYPE'] . "</option>";
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <!-- End TService Type Field End -->

                            <!-- Start Service Details Field Start-->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="details">Service Details</label>
                                <div class="col-sm-10">
                                    <select class="form-select details" name="details" id="details" required>
                                        <option value="">Choose Service Detail</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Service Details Field End -->

                            <!-- Start Issue Description Field -->
                            <div class="form-group">
                                <label class="col-sm-2 control-lable mt-3" for="">Issue Description</label>
                                <div class="col-sm-10">
                                    <textarea name="description" id="" class=" form-control  description" cols="30" rows="10" placeholder="Enter issue description please..." required='required'></textarea>
                                </div>
                            </div>
                            <!-- End Issue Description Field -->

                            <!-- Start Tags Field -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="">Tags</label>
                                <div class="col-sm-10">
                                    <select class="form-select tags" name="tags[]" id="" required multiple>
                                        <option value="Hardware">Hardware</option>
                                        <option value="Software">Software</option>
                                        <option value="IT">IT</option>
                                        <option value="Fix">Fix</option>
                                    </select>
                                </div>
                                <!-- End Tags Field -->

                                <!-- Start Submit Button -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary btn-lg mt-3  addTicket" name="addTicket">Create Ticket</button>
                                    </div>
                                </div>
                                <!-- End Submit Button  -->
                        </form>

                        <!-- Edit Ticket Information Form End -->
                    </div> <!-- Container Div End  -->
                </div>
            </div> <!-- Container-fluid Div End  -->
        </main> <!-- Main End  -->
        <!-- New Ticket Form End -->


    <?php
    } elseif ($action == 'Edit') {  //Edit Ticket Info Page
    ?>

        <!-- Edit Ticket Info Start -->
        <main class="content px-3 py-2"> <!-- Main Start -->
            <div class="container-fluid"> <!-- Container-fluid Div Start -->
                <div class="mb-3">

                    <h2 class="text-center mt-3">Edit Tickets</h2>

                    <div class="container"> <!-- Container Div Start  -->

                        <form class="form-horizontal" action="" method="" style="margin: auto;">

                            <!-- Start Name SelectBox -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="">User Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="<?php echo $_SESSION['employee'] ?>" class=" form-control name" disabled>
                                </div>
                            </div>
                            <!-- End Name  SelectBox -->

                            <!-- Start Service Type Field Start-->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="service">Service Type</label>
                                <div class="col-sm-10">
                                    <select class="form-select service" name="service" id="service" required>
                                        <option value="">Choes Service</option>
                                        <?php
                                        // // Query to retrieve a list of tables
                                        $department = "SELECT  * FROM service_type";
                                        $dep = oci_parse($conn, $department);

                                        // Execute the query
                                        oci_execute($dep);

                                        while ($dept = oci_fetch_assoc($dep)) {
                                            echo "<option value='" . $dept['ID'] . "'>" . $dept['TYPE'] . "</option>";
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <!-- End TService Type Field End -->

                            <!-- Start Service Details Field Start-->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="details">Service Details</label>
                                <div class="col-sm-10">
                                    <select class="form-select details" name="details" id="details" required>
                                        <option value="">Choose Service Detail</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Service Details Field End -->

                            <!-- Start Issue Description Field -->
                            <div class="form-group">
                                <label class="col-sm-2 control-lable mt-3" for="">Issue Description</label>
                                <div class="col-sm-10">
                                    <textarea name="description" id="" class=" form-control  description" cols="30" rows="10" placeholder="Enter issue description please..." required='required'></textarea>
                                </div>
                            </div>
                            <!-- End Issue Description Field -->

                            <!-- Start Tags Field -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="">Tags</label>
                                <div class="col-sm-10">
                                    <select class="form-select tags" name="tags[]" id="" required multiple>
                                        <option value="Hardware">Hardware</option>
                                        <option value="Software">Software</option>
                                        <option value="IT">IT</option>
                                        <option value="Fix">Fix</option>
                                    </select>
                                </div>
                                <!-- End Tags Field -->

                                <!-- Start Submit Button -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" value="Update" class="btn btn-success btn-lg mt-3">
                                    </div>
                                </div>
                                <!-- End Submit Button  -->
                        </form>
                    </div> <!-- Container Div End  -->
                </div>
            </div><!-- Container-fluid Div End  -->
        </main> <!-- Main End -->
        <!-- Edit Ticket Info End -->
    <?php
    } elseif ($action == 'Info') {  // User Profile Info Page

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?   intval($_GET['userid']) : 0;

        $ticketInfo = "SELECT
                            users.*, service_type.type
                        FROM 
                            users
                        INNER JOIN
                            service_type
                        ON
                            users.department = service_type.type
                        WHERE users.ID = :t_id";
        $info = oci_parse($conn, $ticketInfo);

        oci_bind_by_name($info, ':t_id', $userid);

        oci_execute($info);

        $infos = oci_fetch_assoc($info);
    ?>
        <!-- User Profile Info STart -->
        <main class="content px-3 py-2"> <!-- Main Start -->
            <div class="container-fluid"> <!-- Container-fluid Div Start -->
                <div class="mb-3">
                    <h2 class="text-center mt-3">Manage Profile</h2>
                    <div class="container">
                        <form class="form-horizontal" action="" method="">
                            <input type="hidden" name="tickid" value="">

                            <div class="information block"> <!-- Information Div Start -->
                                <div class="container">
                                    <div class="panel panel-primary" style=" border-color: white; box-shadow: 0px 10px 20px 0px rgb(0 0 0 / 20%)">
                                        <div class="panel-heading" style="background-color: #00205b; border: 0;">
                                            <h2 class="text-white p-2">User Information</h2>
                                        </div>
                                        <div class="panel-body"> <!-- Panel-body Div Start -->
                                            <ul class="list-unstyled">

                                                <li>
                                                    <i class="fa-solid fa-user"></i>
                                                    <span>User Name : <?php echo " " . $infos['NAME'] ?></span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-phone"></i>
                                                    <span>Phone Number : <?php echo " " . $infos['PHONE_NUMBER'] ?></span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Department : <?php echo " " . $infos['DEPARTMENT'] ?></span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-envelope"></i>
                                                    <span>Email Address : <?php echo " " . $infos['EMAIL'] ?> </span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-clock"></i>
                                                    <span>Register Date : <?php echo " " . $infos['CREATED_DATE'] ?> </span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-battery-half"></i>
                                                    <span>Status : <?php echo " " . $infos['STATUS'] ?> </span>
                                                </li>
                                            </ul>
                                        </div> <!-- Panel-body Div End -->

                                    </div>
                                </div>
                            </div> <!-- Information Div End -->
                        </form>
                        <a href='?action=update&userid=<?php echo $infos['ID'] ?>' class='btn btn-success mt-5 mb-5'><i class='fa-solid fa-pen-to-square pe-2'></i>Edit Information</a>
                    </div>

                </div>
            </div> <!-- Container-fluid Div End -->
        </main> <!-- Main End -->
        <!-- User Profile Info End -->
    <?php
    } elseif ($action == 'update') {   // Edit User Info Page

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?   intval($_GET['userid']) : 0;

        $ticketInfo = "SELECT
                            *
                        FROM 
                            users
                        WHERE users.ID = :t_id";
        $info = oci_parse($conn, $ticketInfo);

        oci_bind_by_name($info, ':t_id', $userid);

        oci_execute($info);

        $infos = oci_fetch_assoc($info);
    ?>
        <!-- Edit User Info Page STart -->
        <main class="content px-3 py-2"> <!-- Main STart -->
            <div class="container-fluid"> <!-- Container-fluid Div STart -->
                <div class="mb-3">
                    <h2 class="text-center mt-3 mb-3">Edit Profile</h2>
                    <div class="container">
                        <!-- Edit User Information Form Start -->
                        <form class="form-horizontal" action="" method="">

                            <!-- Start User Name Field -->
                            <div class="form-group form-group-lg ">
                                <label class="col-sm-2 control-lable mt-3 mb-2" for="">User Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="<?php echo $infos['NAME'] ?>" class=" form-control input userName" placeholder=" Enter your name please...">
                                </div>
                            </div>
                            <!-- End User Name Field -->

                            <!-- Start Phone Number Field -->
                            <div class="form-group form-group-lg ">
                                <label class="col-sm-2 control-lable mt-3 mb-2" for="">Phone Number</label>
                                <div class="col-sm-10">
                                    <input type="text" name="number" value="<?php echo $infos['PHONE_NUMBER'] ?>" oninput="restrictInput(event)" class=" form-control input userNumber" placeholder=" Enter your phone number please...">
                                </div>
                            </div>
                            <!-- End Phone Number Field -->

                            <!-- Start Submit Button -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" value="<?php echo $infos['ID'] ?>" class="btn btn-success btn-lg mt-3 mb-2 updateProfile">UPDATE</button>
                                </div>
                            </div>
                            <!-- End Submit Button  -->
                        </form>
                        <!-- Edit User Information Form End -->
                    </div>
                </div>
            </div> <!-- Container-fluid Div End -->
        </main>
        <!-- Main End -->
        <!-- Edit User Info Page End -->
    <?php
    } elseif ($action == 'View') {  // View Tickets Info Page

        $ticketid = isset($_GET['tickid']) && is_numeric($_GET['tickid']) ?   intval($_GET['tickid']) : 0;

        $ticketInfo = "SELECT
                            tickets.*, users.name AS Username, service_type.type, service_details.service_details 
                        FROM 
                            tickets
                        INNER JOIN
                            users 
                        ON 
                            tickets.user_id = users.id
                        INNER JOIN
                            service_type
                        ON
                            tickets.service_type = service_type.id
                        INNER JOIN
                            service_details
                        ON
                            tickets.service_details = service_details.id
                        WHERE tickets.ID = :t_id";
        $info = oci_parse($conn, $ticketInfo);

        oci_bind_by_name($info, ':t_id', $ticketid);

        oci_execute($info);

        $infos = oci_fetch_assoc($info);

    ?>
        <!-- View Tickets Info Page STart -->
        <main class="content px-3 py-2"> <!-- Main STart -->
            <div class="container-fluid"> <!-- Container-fluid Div STart -->
                <div class="mb-3">

                    <h2 class="text-center mt-3">View Tickets</h2>

                    <div class="container"> <!-- Container Div Start  -->
                        <!-- Display Ticket Information Form Start -->
                        <form class="form-horizontal" action="" method="">
                            <input type="hidden" name="tickid" value="">

                            <div class="information block">
                                <div class="container">
                                    <div class="panel panel-primary" style=" border-color: white; box-shadow: 0px 10px 20px 0px rgb(0 0 0 / 20%)">
                                        <div class="panel-heading" style="background-color: #00205b; border: 0;">
                                            <h2 class="text-white p-2">Ticket Information</h2>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-unstyled ">

                                                <li>
                                                    <i class="fa-solid fa-user"></i>
                                                    <span>Created By: <?php echo " " . $infos['USERNAME'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-clock"></i>
                                                    <span>Send Date: <?php echo " " . $infos['CREATED_DATE'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Service Type: <?php echo " " .  $infos['TYPE'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-circle-info"></i>
                                                    <span>Service Details: <?php echo " " . $infos['SERVICE_DETAILS'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-box-tissue"></i>
                                                    <span>Issue Description: <?php echo " " . $infos['DESCRIPTION'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-comment"></i>
                                                    <span>Employee Comment: <?php echo " " . $infos['COMMENTS'] ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-comment"></i>
                                                    <span>Admin Comment:</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-battery-half"></i>
                                                    <span>Ticket Status: <?php echo " " . $infos['STATUS'] ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- Display Ticket Information Form End -->
                    </div> <!-- Container Div End  -->
                </div>
            </div> <!-- Container-fluid Div End -->


            <hr class="custom-hr">

        </main> <!-- Main End -->
        <!-- View Tickets Info Page STart -->
<?php
    }


    include $inc . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush(); // Release The Output
?>