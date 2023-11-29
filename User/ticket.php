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
                                        <td>Comments</td>
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
                                        echo "<td>" . $tick["CREATED_DATE"] . "</td>\n";
                                        echo "<td>" . $tick["UPDATED_DATE"] . "</td>\n";
                                        echo "<td>" . $tick["CREATED_BY"] . "</td>\n";
                                        echo "<td> 
                                        <div style='display: flex; justify-content: center; align-items: center;'>
                                        <a style='margin-right: 5px;' href='?action=Edit&tickid=" . $tick["ID"] . "' class='btn btn-warning'><i class='fa-solid fa-pen-to-square '></i></a>
                                        <a style='margin-right: 5px;' href='?action=View&tickid=" . $tick["ID"] . "' class='btn btn-info text-white'><i class='fa-solid fa-eye '></i></a>
                                        <button style='margin-right: 5px;' value='" . $tick["ID"] . "' class='btn btn-danger deleteTicket'><i class='fa-solid fa-trash-can '></i></button>";
                                        if ($tick["STATUS"] == 'solved') {
                                            echo "<button  value='" . $tick["ID"] . "' class='btn btn-success completTicket'><i class='fa-solid fa-check'></i></button>";
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
                                    <input type="text" name="description" value="" class=" form-control  description" placeholder="Enter issue description please..." required='required'>
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
                                    <select class="form-select" name="name" id="" required>
                                        <option value="0">Choes Your Name</option>

                                    </select>
                                </div>
                            </div>
                            <!-- End Name  SelectBox -->

                            <!-- Start Issue Description Field -->
                            <div class="form-group">
                                <label class="col-sm-2 control-lable mt-3" for="">Issue Description</label>
                                <div class="col-sm-10">
                                    <input type="text" name="description" value="" class=" form-control" placeholder=" Enter issue description please..." required='required'>
                                </div>
                            </div>
                            <!-- End Issue Description Field -->


                            <!-- Start Periority Field -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="">Periority</label>
                                <div class="col-sm-10 ">
                                    <input type="text" name="periority" class=" form-control" placeholder="Enter ticket periority..." required='required'>
                                </div>
                            </div>
                            <!-- End Periority Field -->

                            <!-- Start   SERVICE_NO Field -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="">Service No</label>
                                <div class="col-sm-10">
                                    <input type="text" name="service" class=" form-control" placeholder=" Enter service number ..." required>
                                </div>
                            </div>
                            <!-- End   SERVICE_NOField -->

                            <!-- Start Department SelectBox -->
                            <div class="form-group">
                                <label class="col-sm-2 form-label mt-3" for="">Department</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="department" id="" required>
                                        <option value="0">Choes Department</option>

                                    </select>
                                </div>
                            </div>
                            <!-- End Department  SelectBox -->

                            <!-- Start Ticket Start Date Cal -->
                            <!-- <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-lable" for="">Ticket Start Date</label>
                            <div class="col-sm-10">
                                <input type="date" name="date" class=" form-control" required>
                            </div>
                        </div> -->
                            <!-- End Ticket Start Date  Cal -->

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
                                                    <span>User Name : </span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-phone"></i>
                                                    <span>Phone Number : </span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Department :</span>
                                                </li>

                                                <li>
                                                    <i class="fa-solid fa-envelope"></i>
                                                    <span>Email Address : </span>
                                                </li>
                                            </ul>
                                        </div> <!-- Panel-body Div End -->

                                    </div>
                                </div>
                            </div> <!-- Information Div End -->
                        </form>
                        <a href='?action=update' class='btn btn-success mt-5 mb-5'><i class='fa-solid fa-pen-to-square pe-2'></i>Edit Information</a>
                    </div>

                </div>
            </div> <!-- Container-fluid Div End -->
        </main> <!-- Main End -->
        <!-- User Profile Info End -->
    <?php
    } elseif ($action == 'update') {   // Edit User Info Page
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
                                    <input type="text" name="name" value="" class=" form-control input" placeholder=" Enter your name please...">
                                </div>
                            </div>
                            <!-- End User Name Field -->

                            <!-- Start Phone Number Field -->
                            <div class="form-group form-group-lg ">
                                <label class="col-sm-2 control-lable mt-3 mb-2" for="">Phone Number</label>
                                <div class="col-sm-10">
                                    <input type="text" name="number" value="" oninput="restrictInput(event)" class=" form-control input" placeholder=" Enter your phone number please...">
                                </div>
                            </div>
                            <!-- End Phone Number Field -->

                            <!-- Start Submit Button -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Update" class="btn btn-success btn-lg mt-3 mb-2">
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
                                            <h2 class="text-white p-2">Issue Description:</h2>
                                            <h3></h3>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <i class="fa-solid fa-clock"></i>
                                                    <span>Send Date:</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-user"></i>
                                                    <span>Created By:</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-briefcase"></i>
                                                    <span>Department:</span>
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
            <div class="container"> <!-- Container Div Start  -->
                <div class="review block"> <!-- Review Div Start  -->

                    <div class="container"> <!-- Container Div Start  -->
                        <div class="panel panel-primary" style=" border-color: white; box-shadow: 0px 10px 20px 0px rgb(0 0 0 / 20%)">
                            <div class="panel-heading" style="background-color: #00205b; border: 0;">
                                <h3 class="text-white p-3">Tickets Evaluation</h3>
                            </div>
                            <div class="panel-body"> <!-- panel-body Div Start  -->

                                <ul class="list-unstyled">
                                    <li>
                                        <i class="fa-solid fa-ticket"></i>
                                        <span>Ticket No: </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-clock"></i>
                                        <span>Response Time: </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-comment"></i>
                                        <span>Admin Response: </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-clock"></i>
                                        <span>User Evaluation: </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-reply"></i>
                                        <span>User Evaluation: </span>
                                    </li>

                                </ul>
                            </div> <!-- panel-body Div End  -->
                        </div>
                    </div> <!-- Container Div End  -->

                </div> <!-- Review Div End  -->
            </div> <!-- Container Div End  -->

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