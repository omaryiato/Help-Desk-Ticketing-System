// Solve Ticket Popup Script
const solvePopup = document.getElementById('solvePopup')
if (solvePopup) {
    solvePopup.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const button = event.relatedTarget
    // Extract info from data-bs-* attributes
    const recipient = button.getAttribute('data-bs-whatever')
    // If necessary, you could initiate an Ajax request here
    // and then do the updating in a callback.

    // Update the modal's content.
    const modalTitle = solvePopup.querySelector('.modal-title')
    const modalBodyInput = solvePopup.querySelector('.modal-body input')

    modalTitle.textContent = `New message to ${recipient}`
    modalBodyInput.value = recipient
  })
}

// Select Row on Click Function In Ticket Transaction Page In Ticket Transaction Table
document.addEventListener('DOMContentLoaded', function() {
    var table = document.querySelector('.scroll table');

    if (table) {
        table.addEventListener('click', function(event) {
            var targetRow = event.target.closest('tr');
            if (targetRow) {
                // Remove the 'selected' class from all rows
                var rows = table.querySelectorAll('tr');
                rows.forEach(function(row) {
                    row.classList.remove('selected');
                });
    
                // Add the 'selected' class to the clicked row
                targetRow.classList.add('selected');
            }
        });
    
        // Add click event listener to the document body
        document.body.addEventListener('click', function(event) {
            // Check if the clicked element is outside the table
            if (!table.contains(event.target)) {
                // Remove the 'selected' class from all rows
                var rows = table.querySelectorAll('tr');
                rows.forEach(function(row) {
                    row.classList.remove('selected');
                });
            }
        });
    }
});

// Select Row on Click Function In Service Page In Service Details Table
document.addEventListener('DOMContentLoaded', function() {
    var table = document.querySelector('.details .detailsTable');

    if (table) {
        table.addEventListener('click', function(event) {
            var targetRow = event.target.closest('tr');
            if (targetRow) {
                // Remove the 'selected' class from all rows
                var rows = table.querySelectorAll('tr');
                rows.forEach(function(row) {
                    row.classList.remove('selected');
                });
    
                // Add the 'selected' class to the clicked row
                targetRow.classList.add('selected');
            }
        });
    
    // Add click event listener to the document body
    document.body.addEventListener('click', function(event) {
        // Check if the clicked element is outside the table
        if (!table.contains(event.target)) {
            // Remove the 'selected' class from all rows
            var rows = table.querySelectorAll('tr');
            rows.forEach(function(row) {
                row.classList.remove('selected');
            });
        }
    });
    
    }
});

$(function () {

    'use strict';

    var UserAccount = localStorage.getItem('UserAccount') || $('#UserAccount option:first').val() ; 

    function getUserPrivilag(UserAccount) { 
        var userRoleID ;
        $(".overlay").css("display", "flex");
        $.ajax({
            method: "POST",
            url: "function.php",
            data: {
                "UserID":                  UserAccount,
                "action" :                     "getRoleID"
            },
            success: function (response) {
                $(".overlay").css("display", "none");
                userRoleID = response;

                $("#NavbarItems").empty();
                $("#HomePageItems").empty();
    
                $("#NavbarItems").append(`
                    <li><a href="TicketTransaction.php" aria-label="Go To The User Profile"><i class="fa-solid fa-ticket pe-2"></i>Ticketing Transactions</a></li>
                    <li><a href="##" id="CreateNewTicket" data-bs-toggle='modal' data-bs-target="#AddNewTicketPopup" data-bs-whatever="AddNewTicketPopup" aria-label="Logout From User Account"><i class="fa-solid fa-plus pe-2"></i>New Tickets</a></li>
                `)
    
                if (userRoleID == 1 || userRoleID == 3) {
                    $("#NavbarItems").append(`<li><a href="delegate.php" aria-label="Logout From User Account"><i class="fa-solid fa-user-minus pe-2"></i>Delegate Supervisors</a></li>`);
                } 
                
                if (userRoleID == 1) {
                    $("#NavbarItems").append(`
                        <li><a href="teams.php" aria-label="Go To The User Orders"><i class="fa-solid fa-users pe-2"></i>Team Member</a></li>
                        <li><a href="service.php" aria-label="Logout From User Account"><i class="fa-solid fa-headphones pe-2"></i>Services</a></li>
                        <li><a href="##" aria-label="Confirm All Solved Ticket " id="UpdateAllSolveTicketToConfirm"><i class="fa-solid fa-circle-check pe-2"></i>Update Solved to Confirm</a></li>
                    `);
                }
                
                $("#HomePageItems").append(`
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3  ">
                    <div class="card" style="width: 15rem; height: 15rem;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><i class="fa-solid fa-ticket pe-2"></i>Ticketing Transactions Page</h5>
                            <p class="card-text mt-2">Go To The Ticket Transaction Page.</p>
                            <button class="mt-auto"><a href="TicketTransaction.php" id="TicketTransationTable" aria-label="Go To The User Profile" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                        </div>
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3 ">
                        <div class="card" style="width: 15rem; height: 15rem;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><i class="fa-solid fa-plus pe-2"></i>Create New Tickets</h5>
                                <p class="card-text mt-2">Tell Us About Your Problem.</p>
                                <button class="mt-auto"><a href="##" id="CreateNewTicket" data-bs-toggle='modal' data-bs-target="#AddNewTicketPopup" data-bs-whatever="AddNewTicketPopup" aria-label="Logout From User Account" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                            </div>
                        </div>
                    </div>
                `);
    
                if (userRoleID == 1 || userRoleID == 3) {
                    $("#HomePageItems").append(`
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-3 ">
                            <div class="card" style="width: 15rem; height: 15rem;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="fa-solid fa-user-minus pe-2"></i>Delegate Supervisors</h5>
                                    <p class="card-text mt-2">Delegate With Other Supervisors.</p>
                                    <button class="mt-auto"><a href="delegate.php" aria-label="Logout From User Account" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                </div>
                            </div>
                        </div>
                    `)
                } 
    
                if (userRoleID == 1) {
                    $("#HomePageItems").append(`
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-3 ">
                            <div class="card" style="width: 15rem; height: 15rem;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="fa-solid fa-users pe-2"></i>Team Member</h5>
                                    <p class="card-text mt-2">Go To The Manage Team Member Page.</p>
                                    <button class="mt-auto"><a href="teams.php" aria-label="Go To The User Orders" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-3 ">
                            <div class="card" style="width: 15rem; height: 15rem;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="fa-solid fa-headphones pe-2"></i>Services</h5>
                                    <p class="card-text mt-2">Go To The Manage Service Page.</p>
                                    <button class="mt-auto"><a href="service.php" aria-label="Logout From User Account" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-3 ">
                            <div class="card " style="width: 15rem; height: 15rem;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><i class="fa-solid fa-circle-check pe-2"></i>Update Solved to Confirm</h5>
                                    <p class="card-text mt-2">Confirm All Solved Tickets Thats Not Confirmeds.</p>
                                    <button class="mt-auto"><a href="##" aria-label="Confirm All Solved Ticket " id="UpdateAllSolveTicketToConfirmhome" style="font-weight: bold;">Go To<i class="fa-solid fa-arrow-right ps-2"></i></a></li>
                                </div>
                            </div>
                        </div>
                    `);
                }
                
            },
            error: function(xhr, status, error) {
                $(".overlay").css("display", "none");
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!!",
                });
                console.error(xhr.responseText); // For debugging
                
            }
        });
    }

    getUserPrivilag(UserAccount);

    // Hide Placeholder On Form Focus

    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');

    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    var refreshMode = localStorage.getItem('refreshMode') || 'auto'; // Get refresh mode from local storage or default to 'auto'
    var refreshTableData;
    var refreshCountSection;

    $('#refreshMode').val(refreshMode); // Set the value of the select element to the saved refresh mode

    $('#refreshMode').on('change', function() { // Event listener for refresh mode change
        refreshMode = $(this).val();
        localStorage.setItem('refreshMode', refreshMode); // Save refresh mode to local storage

        // If refresh mode is manually, clear the interval
        if (refreshMode === 'manually') {
            clearInterval(refreshTableData);
            clearInterval(refreshCountSection);
        } else {
            // Start intervals for auto refresh
            refreshTableData = setInterval(refreshData, 180000);
            refreshCountSection = setInterval(updateCounts, 180000);
        }
    });

    if (refreshMode === 'auto') { // If refresh mode is auto, start intervals
        refreshTableData = setInterval(refreshData, 180000);
        refreshCountSection = setInterval(updateCounts, 180000);
    }

    // Toggel Ticket Transaction Dropdown List

    $('.trans').click(function() {
        $('.tran').toggle(100);
    });

    $(document).on('click', function(event) {
        // Check if the clicked element is not part of .users or .userno
        if (!$(event.target).closest('.trans, .tran').length) {
            // Hide the transaction list element
            $('.tran').hide(100);
        }
    });

    

    /////////////////////////////////////////// ***************** Manage Service Page Start  *************************/////////////////////////////////////////

    var ServiceUserSessionID =  UserAccount; // User Name In This Session (Who Logged In)

    $("#AddNewServiceForm").validate({                                          // Validate Function For Add New Service PopUp
        rules: {
            NewServiceName: "required" // Name field is required
        },
        messages: {
            NewServiceName: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Service Name</div>"
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $(document).on('click', '#AddNewService', function(e) {                     // Add New Service To Service Table Function

        e.preventDefault();
        
        // Validate the form
        if ($("#AddNewServiceForm").valid()) {
            $('#NewService').modal('hide');
            $(".overlay").css("display", "flex");
            // Form is valid, proceed with AJAX submission
            $.ajax({
                method: "POST",
                url: "function.php",
                data: {
                    "serviceName":                  $(this).closest('.content').find('#NewServiceName').val(),
                    "ServiceUserSessionID":         ServiceUserSessionID,
                    "service" :                     "NewService"
                },
                success: function (response) {
                    if (response === 'exist') {
                        
                        $(".overlay").css("display", "none");
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "This Service Name Is Already Exist!",
                        });
                        $('#NewServiceName').val('');
                        
                    } else {
                        
                        $(".overlay").css("display", "none");
                        Swal.fire("Service Added Successfully ");
                        $('#serviceLOV').html(response);
                        $('#NewServiceName').val('');
                    }
                },
                error: function(xhr, status, error) {
                    
                    $(".overlay").css("display", "none");
                    // Display the error message
                    $('#NewServiceName').val('');
                    $("#spinner").hide();
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!!",
                    });
                    console.error(xhr.responseText); // For debugging
                    
                }
            });
        } 
    });

    $('#serviceLOV').on('change', function ()                           // Call fillServiceDetailsTable() Function 
    {  // Return Service Number  Based On Service Name Function
        fillServiceDetailsTable();
    });

    function fillServiceDetailsTable()                                  // Retrive Service Details Information Based On Service Type Number Function 
    {       // This function is not used now 4JAN2024-- Display Service Details Information Based On Service Number Function
        $('#ServiceID').val($('#serviceLOV').val()); // to retrive the selected ID into ServiceID text Box
        $('#serviceDetails').empty();
        $('#serviceDetails').text("Loading Data...");
        $('#serviceDetailsTeam').empty();
        // $('#GetServiceTypeID').val($('#serviceLOV').val());
        $('#GetServiceTypeName').val($('#serviceLOV').find('option:selected').text());
        $('#waitingMessage').empty().removeClass('mt-5');
    
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "serviceTypeNumber":            $('#serviceLOV').val(),
                "service" :         "chooseService" 
            },
            success: function (data) {
    
                $('#addNewServiceDetailsButton').html(`
                    <button class="btn btn-primary ms-auto " data-bs-toggle='modal' data-bs-target="#NewServiceDetail" data-bs-whatever="NewServiceDetail" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Service Detail'>
                        <i class="fa-solid fa-plus"></i>
                        <span>Add New Service Details</span>
                    </button>
                `);
                $('#serviceDetailsHeadTable').html(`
                    <tr>
                        <th hidden>ID</th>
                        <th>Service Details Name</th>
                        <th>Description</th>
                        <th>Custody</th>
                        <th>Private</th>
                    </tr>
                `);
                
                // Call function to fill Service Deatails Table
                ////////////////////////////////Parsing the data retrived and plot it in table view///////////////////////////////////////////////////
                var tableDBody = $('#serviceDetails');
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
            
                // Clear existing rows
                tableDBody.empty();
            
                jsonData.forEach(function (row) {
                    var newDRow = $('<tr>');
            
                    // Populate each cell with data
                    newDRow.html(`
            
                        <td id='sdid' hidden>${row.id}</td>
                        <td>${row.name}</td>
                        <td>${row.desc}</td>`
            
                        // Check Custody and Private conditions
                        + (row.custody === 'Y' ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.custody === 'Y' ? 'checked' : ''}  value='${row.id}' class='serviceDetailsCustody' name='checkbox[]' >
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' value='${row.id}' class='serviceDetailsCustody' name='checkbox[]' >
                                </div>
                            </td>`)
            
                            + (row.private === 'Y' ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.private === 'Y' ? 'checked' : ''} value='${row.id}' class='serviceDetailsPrivate'  name='checkbox[]' >
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox'  value='${row.id}' class='serviceDetailsPrivate' name='checkbox[]' >
                                </div>
                            </td>` )
                            );
            
                    // Append the new row to the table body
                    tableDBody.append(newDRow);
                    // Clear Existing Data In Table Service Details Team Member (tbody = serviceDetailsTeam) 
                });
    
    
                // Append the "Update" button after loading the data
                $('#updateServiceDetailButton').html(`
                    <button type="button" name="btnUpdate" id="updateServiceDetailsButton" class="btn btn-success button" data-bs-toggle='tooltip' data-bs-placement='top' title='Update Information'>
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Update</span>
                    </button>
                `);
                ////////////////////////////////////////////////////////////////////////////////////////
            },
            error: function(xhr, status, error) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!!",
                });
                // Handle error response
                console.error(xhr.responseText); // For debugging
                
            }
        });
    }

    // *****************  Service Details Start  *************************

    var serviceDetailsId;
    var serviceDetailsName;
    var serviceDetailsDescription;
    // Handle right-click on table rows
    $('#ServiceDetailsID tbody').on('contextmenu', 'tr', function (e) { // Show Service Details List Action When Click Right 
        e.preventDefault();
        // Show context menu at the mouse position
        $('#service-list').css({
            display: 'block',
            left: e.pageX,
            top: e.pageY
        });
        serviceDetailsId = $(this).find('td:first').text();
        serviceDetailsName = $(this).find('td:nth-child(2)').text();
        serviceDetailsDescription = $(this).find('td:nth-child(3)').text();
    });

    $(document).on('click', function () {   // Hide context menu on click outside
        $('#service-list').css('display', 'none');
    });

    $(document).on('click', '#editServiceDetailsButton',  function () {   // Fill All Input Field In Edit Servivce Details Popup 
        // Implement your logic for "Edit Service"
        $('#service-list').css('display', 'none');
        $('#EditServiceDetailsName').empty();
        $('#EditServiceDetailsDescription').empty();
        $('#EditServiceDetailsName').val(serviceDetailsName);
        $('#EditServiceDetailsDescription').val(serviceDetailsDescription);
    });

    $("#AddNewServiceDetailsForm").validate({                                   // Validate Function For Add New Service Details PopUp
        rules: {
            NewServiceDetailsName: "required", // Name field is required
            ServiceDetailsDescription: "required" // Name field is required
        },
        messages: {
            NewServiceDetailsName: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Service Details Name</div>",
            ServiceDetailsDescription: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Service Details Description</div>"
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $(document).on('click', '#AddNewServiceDetails', function(e) {              // Add New Service Details To Service Details Table Function

        e.preventDefault();
        
        if ($("#AddNewServiceDetailsForm").valid()) {
            $('#NewServiceDetail').modal('hide');
            $(".overlay").css("display", "flex");
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "NewServiceDetailsName":            $(this).closest('.content').find('#NewServiceDetailsName').val(),
                    "ServiceUserSessionID":             ServiceUserSessionID,
                    "ServiceTypeID":                    $('#serviceLOV').val(),
                    "ServiceDetailsDescription":        $(this).closest('.content').find('#ServiceDetailsDescription').val(),
                    "service" :                          "NewServiceDetails"
                },
                success: function (response) {
                    if (response === 'exist') {
                        $('#NewServiceDetailsName').val('');
                        $('#ServiceDetailsDescription').val('');
                        
                        $(".overlay").css("display", "none");
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "This Service Details Name Is Already Exist!",
                        });
                        fillServiceDetailsTable();
                    } else {
                        $('#NewServiceDetailsName').val('');
                        $('#ServiceDetailsDescription').val('');
                        
                        $(".overlay").css("display", "none");
                        Swal.fire("Service Details Added Successfully ");
                        fillServiceDetailsTable();
                    }
                    
                },
                error: function(xhr, status, error) {
                    $('#NewServiceDetailsName').val('');
                    $('#ServiceDetailsDescription').val('');
                    
                    $("#spinner").hide();
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!!",
                    });
                    console.error(xhr.responseText); // For debugging
                    fillServiceDetailsTable();
                }
            });
        }
    });
    
    $("#EditServiceDetailsInformationForm").validate({                          // Validate Function For Edit Service Details Information PopUp
            rules: {
                EditServiceDetailsName: "required",
                EditServiceDetailsDescription: "required"
            },
            messages: {
                EditServiceDetailsName: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Service Details Name</div>",
                EditServiceDetailsDescription: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Service Details Description</div>"
            },
            submitHandler: function(form) {
                // Form is valid, proceed with form submission
                form.submit();
            }
    });

    $(document).on('click', '#UpdateServiceDetailsInfoButton', function(e) {    // Update Service Details Information Function

        e.preventDefault();
        
        if ($("#EditServiceDetailsInformationForm").valid()) {
            $('#EditServiceDetails').modal('hide');
            $(".overlay").css("display", "flex");
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "EditServiceDetailsName":           $(this).closest('.content').find('#EditServiceDetailsName').val(),
                    "ServiceUserSessionID":             ServiceUserSessionID,
                    "EditServiceDetailsDescription":    $(this).closest('.content').find('#EditServiceDetailsDescription').val(),
                    "serviceDetailsId":                 serviceDetailsId,
                    "serviceTypeID":                    $('#serviceLOV').val(),
                    "service" :                         "EditServiceDetailsInformation"
                },
                success: function (data) {
                    if (data === 'exist') {
                        $(".overlay").css("display", "none");
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "This Service Details Name Is Already Exist!",
                        });
                    } else {
                        
                        $(".overlay").css("display", "none");
                        Swal.fire("Service Details Updated Successfully ");
                        fillServiceDetailsTable();
                    }
                    
                },
                
                error: function(xhr, status, error) {
                    
                    $("#spinner").hide();
                        Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong!!!",
                    });
                    console.error(xhr.responseText); // For debugging
                    fillServiceDetailsTable();
                }
            });
        }
    });

    var custodyColumn = [];
    var custodyColumnJson = [];
    $(document).on('change', ':input[type="checkbox"].serviceDetailsCustody', function() { //  Store All Custody Checkboxes Changes To Update Them
        // Find the manager checkbox in the same row and uncheck it
        var servaiceDetailsNo = $(this).val();
        var newStatus = this.checked ? 'Y' : 'N';

        custodyColumn.push({
            servaiceDetailsNo: servaiceDetailsNo,
            newStatus: newStatus
        });

        custodyColumnJson = JSON.stringify(custodyColumn);
    });
    
    var privateColumn = [];    
    var privateColumnJson = [];    
    $(document).on('change', ':input[type="checkbox"].serviceDetailsPrivate', function() {   //  Store All Private Checkboxes Changes To Update Them

        var servaiceDetailsNo = $(this).val();
        var newStatus = this.checked ? 'Y' : 'N';

                // Assign data for each row to the object
        privateColumn.push({
            servaiceDetailsNo: servaiceDetailsNo,
            newStatus: newStatus
        });

        privateColumnJson = JSON.stringify(privateColumn);
    });

    $(document).on('click', '#updateServiceDetailsButton', function(e) {        //  Update Custody And Private Columns In Service Details Table Function

        e.preventDefault();
        $(".overlay").css("display", "flex");
        $.ajax({
            type: 'POST',
            url: 'function.php',
            dataType: 'json',
            data: { 
                "custodyColumnJson":          custodyColumnJson,
                "privateColumnJson":          privateColumnJson,
                "ServiceUserSessionID":       ServiceUserSessionID,
                "service":                    "updateServiceDetailsTable"
            },
            success: function (response) {
                // Replace this Popup To normal popup 
                $(".overlay").css("display", "none");
                Swal.fire("Service Detail Updated Successfully"); 
                fillServiceDetailsTable();
            },
            error: function(xhr, status, error) {
                $(".overlay").css("display", "none");
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error updating Service Detail",
                });
                console.error(xhr.responseText); // For debugging
                fillServiceDetailsTable();
            }
        });
    });

    // *****************  Service Details End  *************************

    // *****************  Service Details Team  Start  *************************

    var currentServiceDetailsID;
    var currentServiceDetailsName;
    $('#serviceDetails').on('click', 'tr td', function () {     // Call fillServiceDetailsTeamTable() Function

        currentServiceDetailsID = $(this).closest('tr').find('td:first').text();
        currentServiceDetailsName = $(this).closest('tr').find('td:nth-child(2)').text();

        $('#GetServiceDetailsName').val(currentServiceDetailsName);

        if (!$(this).is(':last-child, :nth-child(4)')) {
            fillServiceDetailsTeamTable();
        }
    });

    function fillServiceDetailsTeamTable() {                            //  Retrive Service Team Information Based On Service Details Number Function
    
            $('#serviceDetailsTeam').empty();
            $('#serviceDetailsTeam').text("Loading Data...");
            $('#GetServiceDetailsName').empty();
            $('#waitingMessages').empty();    
                    
            $.ajax({
                
                type: 'POST',
                url: 'function.php', // Function Page For All ajax Function
                data: { 
                    "ServiceDetailsID": currentServiceDetailsID,
                    "service":          "getservicedetailsteam"
                },
                success: function (data) {
    
                    $('#addNewTeamDetailsButton').html(`
                        <button class="btn btn-primary" id='notAssignedTeam' data-bs-toggle='modal' data-bs-target="#NewDetailTeam" data-bs-whatever="NewDetailTeam" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Team'>
                            <i class="fa-solid fa-plus"></i>
                            <span>Assign New Team</span>
                        </button>
                    `);
    
                    $('#TeamDetailsHeadTable').html(`
                        <tr>
                            <th hidden>Team ID</th>
                            <th hidden>Service Details ID</th>
                            <th hidden>Service Details Name</th>
                            <th>Team Name</th>
                            <th>Enabled</th>
                        </tr>
                    `);
                    
                    // Call function to fill Service Details Team Table
                    ////////////////////////////////Parsing the data retrived and plot it in table view///////////////////////////////////////////////////
                    var tableDBody = $('#serviceDetailsTeam');
                    // Clear existing rows
                    tableDBody.empty();
                    
                    // Parse the returned JSON data
                    var jsonData = JSON.parse(data);
                
                    jsonData.forEach(function (row) {
                        var newDRow = $('<tr>');
                        // Populate each cell with data
                        newDRow.html(`
                            <td hidden>${row.teamID}</td>
                            <td hidden>${row.serviceDetailsID}</td>
                            <td hidden>${row.serviceDetailName}</td>
                            <td>${row.name}</td>`
                            // Check Custody and Private conditions
                            + (row.enable === 'Y' ? `
                                <td>
                                    <div class='check'>
                                        <input type='checkbox' ${row.enable === 'Y' ? 'checked' : ''} value='${row.teamID}' name='checkbox[]' id='${row.serviceDetailsID}' class='teamTable'>
                                    </div>
                                </td>` :  `
                                <td>
                                    <div class='check'>
                                        <input type='checkbox' value='${row.teamID}' name='checkbox[]' id='${row.serviceDetailsID}' class='teamTable' >
                                    </div>
                                </td>`)
                                );
                
                        // Append the new row to the table body
                        tableDBody.append(newDRow);
                    });
    
                    // Append the "Update" button after loading the data
                    $('#updateDetailTeamButton').html(`
                        <button type="button" name="btnUpdate" id="updateTeamEnabled" class="btn btn-success button" data-bs-toggle='tooltip' data-bs-placement='top' title='Update Information'>
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Update</span>
                        </button>
                    `);
                    /////////////////////////////////////////////////////////////////////////////////////
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Error Fetching Service Detail Team",
                    });
                    console.error(xhr.responseText); // For debugging
                }
            });
        
    }

    $(document).on('click', '#notAssignedTeam', function(e) {                   // Retrive Not Selected Service Details Team  Function

        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "notassignedteam": currentServiceDetailsID,
                "service":       'getAssignedTeam'
            },
            success: function (data) {
                $('#GetServiceDetailsTeamNumber').append(data);
            },
            error: function(xhr, status, error){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error Fetching Assigned Team!!!",
                });
                console.error(xhr.responseText); // For debugging
            }
        });
    });

    $("#AddNewServiceDetailsTeamForm").validate({                                   // Validate Function For Add New Service Details Team PopUp
        rules: {
            GetServiceDetailsTeamNumber: "required"
        },
        messages: {
            GetServiceDetailsTeamNumber: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Team</div>"
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $(document).on('click', '#AddNewServiceDetailsTeam', function(e) {          // Add New Service Details Team To Service Details Team Table Function

        e.preventDefault();
        
        if ($("#AddNewServiceDetailsTeamForm").valid()) {
            $('#NewDetailTeam').modal('hide');
            $(".overlay").css("display", "flex");
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "GetServiceDetailsName":                        $(this).closest('.content').find('#GetServiceDetailsName').val(),
                    "GetServiceDetailsTeamNumber":                  $(this).closest('.content').find('#GetServiceDetailsTeamNumber').val(),
                    "GetServiceDetailsID":                          currentServiceDetailsID,
                    "ServiceUserSessionID":                         ServiceUserSessionID,
                    "service" :                                     "NewServiceDetailsTeam"
                },
                success: function (response) {
                    
                    $(".overlay").css("display", "none");
                    Swal.fire("Service Details Team Added Successfully ");
                    fillServiceDetailsTeamTable();
                },
                error:  function(xhr, status, error) { 
                    
                    $(".overlay").css("display", "none");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!!!",
                    });
                    console.error(xhr.responseText); // For debugging
                    fillServiceDetailsTeamTable();
                }
            });
        }
    });

    var enableTeamService = [];    
    var enableTeamServiceJson = [];    
    $(document).on('change', ':input[type="checkbox"].teamTable', function() { //  Store All Enable Checkboxes Changes To Update Them

        var teamNo = $(this).val();
        var newStatus = this.checked ? 'Y' : 'N';
        var serviceDetailsID = $(this).attr('id');

                // Assign data for each row to the object
        enableTeamService.push({
            teamNo: teamNo,
            newStatus: newStatus,
            serviceDetailsID: serviceDetailsID
        });

        enableTeamServiceJson = JSON.stringify(enableTeamService);
    });

    $(document).on('click', '#updateTeamEnabled', function(e) {                 //  Update Enabled Column In Team Table Function

        e.preventDefault();
        $(".overlay").css("display", "flex");
        $.ajax({
                type: 'POST',
                url: 'function.php',
                dataType: 'json',
                data: { 
                    "teamEnabled":                  enableTeamServiceJson,
                    "ServiceUserSessionID":         ServiceUserSessionID,
                    "service":                      "updateTeamTable"
                },
                success: function (response) {
                    $(".overlay").css("display", "none");
                    Swal.fire("Enabled updated successfully ");
                    fillServiceDetailsTeamTable();
                },
                error: function(xhr, status, error) {
                    $(".overlay").css("display", "none");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Error updating Enabled",
                    });
                    console.error(xhr.responseText); // For debugging
                    fillServiceDetailsTeamTable();
                }
            });
    });

    // *****************  Service Details Team  Start  *************************

    ///////////////////////////////////////////***************** Service Page Start  *************************/////////////////////////////////////////








    ///////////////////////////////////////////***************** Team Member Page Start  *************************/////////////////////////////////////////

    var TeamPageSessionID = UserAccount;

    $("#AddNewTeamForm").validate({                                          // Validate Function For Add New Team PopUp
        rules: {
            NewTeamName: "required", // Name field is required
            description: "required", // Name field is required
            departmentID: "required", // Name field is required
            branchCode: "required" // Name field is required
        },
        messages: {
            NewTeamName: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Team Name</div>",
            description: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Team Description </div>",
            departmentID: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Department</div>",
            branchCode: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Branch</div>"
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $(document).on('click', '#AddNewTeam', function(e) {                     // Add New Team To Team Table Function

        e.preventDefault();
        
        if ($("#AddNewTeamForm").valid()) {
            $('#NewTeam').modal('hide');
            $(".overlay").css("display", "flex");
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "NewTeamName":              $(this).closest('.content').find('#NewTeamName').val(),
                    "branchCode":               $(this).closest('.content').find('#branchCode').val(),
                    "description":              $(this).closest('.content').find('#description').val(),
                    "departmentID":             $(this).closest('.content').find('#departmentID').val(),
                    "TeamPageSessionID":        TeamPageSessionID,
                    "team" :                    "NewTeam"
                },
                success: function (response) {

                    if (response === 'exist') {
                        $(".overlay").css("display", "none");
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "This Team Name Is Already Exist!",
                        });
                        $(this).closest('.content').find('#NewTeamName').val(" ");
                        $(this).closest('.content').find('#branchCode').val(" ");
                        $(this).closest('.content').find('#description').val(" ");
                        $(this).closest('.content').find('#departmentID').val(" ");
                    } else {
                        
                        $(".overlay").css("display", "none");
                        Swal.fire("Team Added Successfully ");
                        $('#TeamName').html(response);
                        $(this).closest('.content').find('#NewTeamName').val(" ");
                        $(this).closest('.content').find('#branchCode').val(" ");
                        $(this).closest('.content').find('#description').val(" ");
                        $(this).closest('.content').find('#departmentID').val(" ");
                    }
                        
                },
                error: function(xhr, status, error) {
                    
                    $(".overlay").css("display", "none");
                    $(this).closest('.content').find('#NewTeamName').val(" ");
                    $(this).closest('.content').find('#branchCode').val(" ");
                    $(this).closest('.content').find('#description').val(" ");
                    $(this).closest('.content').find('#departmentID').val(" ");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!!",
                    });
                    console.error(xhr.responseText); // For debugging
                }
            });
        }
    });

    $('#TeamName').on('change', function () {     // Call fillTeamInfo Function 
        fillTeamInfo();
    });

    function fillTeamInfo() {              //  Retrive Team Information Based On Team Number Function

        $('#GetMemberName').empty();
        $('#TeamMemberBodyTable').empty();
        $('#TeamNoID').val($('#TeamName').val()); // Return  Team ID Based On Team Name From DB Using Select Option
        $('#TeamMemberBodyTable').text("Loading Data...");  // Team Member Table 
        $('#DelegateMemberHeadTable').text("Loading Data..."); // Team Delegate Table
        $('#waitingTeamMemberInfo').empty().removeClass('mt-5');
        $('#waitingDelegateMember').empty().removeClass('mt-5');
        $('#status').prop('checked', false);
        $('#dept').val(" ");
        $('#depID').val(" ");
        $('#GetDeptID').val(" ");
        $('#branch').val(" ");

        $('#GetTeamName').val($('#TeamName').find('option:selected').text());
        $('#GetTeamID').val($('#TeamName').val());
        

        $('#updateTeamInfoButton').html(`
            <button class="btn btn-primary" data-bs-toggle='modal' data-bs-target="#NewTeam" data-bs-whatever="NewTeam" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Team'>
                <i class="fa-solid fa-plus"></i>
                <span>Add New Team</span>
            </button>
            <button class="btn btn-success" id='updateTeamPopupButton' data-bs-toggle='modal' data-bs-target="#EditTeamInformation" data-bs-whatever="EditTeamInformation" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Team Information'>
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Update</span>
            </button>
        `);

        $('#DelegateMemberButtons').html(`
            <a class="btn btn-primary" href='delegate.php' data-bs-toggle='tooltip' data-bs-placement='top' title='Go To delegate Page'>
                <i class="fa-solid fa-plus"></i>
                <span>Create New</span>
            </a>
        `);

        $('#TeamMemberButtons').html(`
            <button class="btn btn-primary" id='addNewMemberPopupButton' data-bs-toggle='modal' data-bs-target="#NewTeamMember" data-bs-whatever="NewTeamMember" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Team Member'>
                <i class="fa-solid fa-plus"></i>
                <span>Add New Member</span>
            </button>
            <button class="btn btn-success" id='updateTeamMemberActivity' data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Team Member Info'>
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Update</span>
            </button>
        `);

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "teamInfo": $('#TeamName').val(),
                "team": "getTeamInformation"
            },
            dataType: 'json',
            success: function (response) {

                $('#DelegateMemberHeadTable').html(`
                    <tr>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                `);

                $('#TeamMemberHeadTable').html(`
                    <tr>
                        <th hidden>User ID</th>
                        <th>User Name</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Active</th>
                        <th>Supervisor</th>
                        <th>Manager</th>
                    </tr>
                `);

                $('#status').prop('checked', response.teamInfo.TEAM_ACTIVE === 'Y');
                $('#dept').val(response.teamInfo.EMP_DEPARTMENT);
                $('#depID').val(response.teamInfo.DEPT_ID);
                $('#GetDeptID').val(response.teamInfo.DEPT_ID);
                $('#branch').val(response.teamInfo.BRANCH_CODE);
                $('#teamDescription').val(response.teamInfo.TEAM_DESCRIPTION);
                $('#EditTeamStatus').prop('checked', response.teamInfo.TEAM_ACTIVE === 'Y');
                $('#EditTeamDepartmentID').val(response.teamInfo.DEPT_ID);
                $('#EditTeamBranchCode').val(response.teamInfo.BRANCH_CODE);
                $('#EditTeamDescription').val(response.teamInfo.TEAM_DESCRIPTION);
                $('#EditTeamName').val($('#TeamName').find('option:selected').text());

                var TeamMemberBodyTable = $('#TeamMemberBodyTable');
                // Clear existing rows
                TeamMemberBodyTable.empty();

                response.teamMembers.forEach(function (row) {
                    var newRow = $('<tr>');
                    // Populate each cell with data
                    newRow.html(`
                        <td hidden>${row.userID}</td>
                        <td>${row.userName}</td>
                        <td>${row.name}</td>
                        <td>${row.description}</td>
                        <td>
                            <div class='check'>
                                <input type='checkbox' ${row.active === 'Y' ? 'checked' : ''} id='${row.userID}' class='active'>
                            </div>
                        </td>`
                        // Check supervisor and manager conditions
                        + (row.supervisor == 3 ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.supervisor == 3 ? 'checked' : ''} id='${row.userID}' class='supervisor' disabled>
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' id='${row.userID}' class='supervisor' disabled>
                                </div>
                            </td>`)

                            + (row.manager == 1 ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.manager == 1 ? 'checked' : ''} id='${row.userID}' class='manager' disabled>
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox'  id='${row.userID}' class='manager'  disabled>
                                </div>
                            </td>` )

                            );

                    // Append the new row to the table body
                    TeamMemberBodyTable.append(newRow);

                    $.ajax({
                        type: 'POST',
                        url: 'function.php',
                        data: { 
                            "GetMember": $('#GetDeptID').val(), 
                            "GetTeam": $('#TeamName').val(),
                            "team":   "restMember"
                        },
                        success: function (data) { 
                            $('#GetMemberName').html(data)
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "There's Somthing Wrong !!!",
                            });
                            console.error(xhr.responseText); // For debugging
                        }
                    });
                });
                
                var DelegateMemberBodyTable = $('#DelegateMemberBodyTable');
                // Clear existing rows
                DelegateMemberBodyTable.empty();

                response.delegatedUsers.forEach(function (row) {
                    var newDRow = $('<tr>');
                    // Populate each cell with data
                    newDRow.html(`
                        <td>${row.name}</td>
                        <td>${row.start}</td>
                        <td>${row.end}</td>`
                            );
                    // Append the new row to the table body
                    DelegateMemberBodyTable.append(newDRow);
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!!",
                });
                console.error(xhr.responseText); // For debugging
            }
        });
    }

    $(document).on('click', '#updateTeamPopupButton', function(e) {    // Get Team Information For Update Popup

        e.preventDefault();

        const staticBranchOptions = {
            'RYD': `<option value="HUF">HUF</option><option value="JIZ">JIZ</option>`,
            'HUF': `<option value="RYD">RYD</option><option value="JIZ">JIZ</option>`,
            'JIZ': `<option value="RYD">RYD</option><option value="HUF">HUF</option>`
        };
        $('#EditTeamBranchCode').html(`<option value='${$('#branch').val()}' selected>${$('#branch').val()}</option>`);
        if (staticBranchOptions.hasOwnProperty($('#branch').val())) {
            $('#EditTeamBranchCode').append(staticBranchOptions[$('#branch').val()]);
        }

        const staticDepartmentOptions = {
            '1017': `<option value="1013">Information Technology Dept</option><option value="1005">Legal Affairs</option>`,
            '1013': `<option value="1017">IT Dept-Tracking</option><option value="1005">Legal Affairs</option>`,
            '1005': `<option value="1017">IT Dept-Tracking</option><option value="1013">Information Technology Dept</option>`
        };
        $('#EditTeamDepartmentID').html(`<option value='${$('#depID').val()}' selected>${$('#dept').val()}</option>`);
        if (staticDepartmentOptions.hasOwnProperty($('#depID').val())) {
            $('#EditTeamDepartmentID').append(staticDepartmentOptions[$('#depID').val()]);
        }

    });

    $("#UpdateTeamInformationForm").validate({                                          // Validate Function For Edit Information  Team PopUp
        rules: {
            EditTeamName: "required", // Name field is required
            EditTeamDescription: "required", // Name field is required
            EditTeamBranchCode: "required", // Name field is required
            EditTeamDepartmentID: "required" // Name field is required

        },
        messages: {
            EditTeamName: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Team Name</div>",
            EditTeamDescription: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Team Description </div>",
            EditTeamDepartmentID: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Department</div>",
            EditTeamBranchCode: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Branch</div>"
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $(document).on('click', '#UpdateTeamInfoButton', function(e) {    // Update Team Information Into Team Table Function

        e.preventDefault();
        
        if ($("#UpdateTeamInformationForm").valid()) {
            $('#EditTeamInformation').modal('hide');
            $(".overlay").css("display", "flex");
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "EditTeamID":                   $('#TeamName').val(),
                    "TeamPageSessionID":            TeamPageSessionID,
                    "EditTeamName":                 $(this).closest('.content').find('#EditTeamName').val(),
                    "EditTeamDescription":          $(this).closest('.content').find('#EditTeamDescription').val(),
                    "EditTeamBranchCode":           $(this).closest('.content').find('#EditTeamBranchCode').val(),
                    "EditTeamStatus":               $(this).closest('.content').find('#EditTeamStatus').prop('checked') ? 'Y' : 'N',
                    "EditTeamDepartmentID":         $(this).closest('.content').find('#EditTeamDepartmentID').val(),
                    "team" :                        "EditTeamInformation"
                },
                success: function (data) {
                    if (data === 'exist') {
                        $(".overlay").css("display", "none");
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "This Team Name Is Already Exist !!!",
                        });
                    } else {
                        
                        $(".overlay").css("display", "none");
                        $('#TeamName').html(data);
                        Swal.fire("Team Information Updated Successfully ");
                        fillTeamInfo();
                    }
                    
                },
                error: function(xhr, status, error) {
                    
                    $(".overlay").css("display", "none");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!!",
                    });
                    
                    console.error(xhr.responseText); // For debugging
                    fillTeamInfo();
                }

            });
        }
    });

    $("#AddNewTeamMemberForm").validate({                                          // Validate Function For Add New Team Member PopUp
        rules: {
            GetMemberName: "required", // Name field is required
            GetMemberDeacription: "required"
        },
        messages: {
            GetMemberName: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Member Name</div>",
            GetMemberDeacription: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Member Description </div>"
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $(document).on('click', '#AddNewTeamMemberButton', function(e) {    // Add New Team Member Into Team Member Table Function

        e.preventDefault();
        
        if ($("#AddNewTeamMemberForm").valid()) {
            $('#NewTeamMember').modal('hide');
            $(".overlay").css("display", "flex");
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "GetTeamID":                      $('#TeamName').val(),
                    "TeamPageSessionID":             TeamPageSessionID,
                    "GetMemberName":                 $(this).closest('.content').find('#GetMemberName').val(),
                    "GetMemberDeacription":          $(this).closest('.content').find('#GetMemberDeacription').val(),
                    "team" :                        "addNewTeamMember"
                },
                success: function (data) {
                    
                    $(".overlay").css("display", "none");
                    $('#GetMemberName').empty();
                    $('#GetMemberDeacription').val(" ");
                    Swal.fire('Member Add To His Team Successfully');
                    fillTeamInfo();
                },
                error: function(xhr, status, error) {
                    
                    $(".overlay").css("display", "none");
                    $('#GetMemberName').empty();
                    $('#GetMemberDeacription').val(" ");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!!",
                    });
                    console.error(xhr.responseText); // For debugging
                    fillTeamInfo();
                }
            });
        }
    });

    var activeColumn = [];
    var activeColumnJson = [];
    $(document).on('change', ':input[type="checkbox"].active', function() {   //  Store All Active Checkboxes Changes To Update Them
        // Find the manager checkbox in the same row and uncheck it
        
            var TeamMemberNo = $(this).attr('id');
            var newStatus = this.checked ? 'Y' : 'N';

                // Assign data for each row to the object
                activeColumn.push({
                    TeamMemberNo: TeamMemberNo,
                    newStatus: newStatus
                });
            activeColumnJson = JSON.stringify(activeColumn);
    });

    $(document).on('click', '#updateTeamMemberActivity', function(e) {        //  Update Active Columns In Team Member Table Function

        e.preventDefault();
        $(".overlay").css("display", "flex");
        
        if (activeColumnJson.length === 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "There's no changes to update !!!",
            });
        } else {
            
            $.ajax({
                type: 'POST',
                url: 'function.php',
                dataType: 'json',
                data: { 
                    "activeColumnJson":        activeColumnJson,
                    "TeamPageSessionID":       TeamPageSessionID,
                    "team":                  "updateTeamMemberTable"
                },
                success: function (response) {
                    $(".overlay").css("display", "none");
                    Swal.fire("Team Member Updated Successfully"); 
                    fillTeamInfo();  
                    activeColumn = [];
                    activeColumnJson = [];        
                },
                error: function(xhr, status, error) {
                    $(".overlay").css("display", "none");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!!",
                    });
                    activeColumn = [];
                    activeColumnJson = [];     
                    console.error(xhr.responseText); // For debugging
                }
            });
        }
        
    });


    ///////////////////////////////////////////***************** Team Member Page Start  *************************/////////////////////////////////////////



    
    ///////////////////////////////////////////***************** Ticket Transation Page Start  *************************/////////////////////////////////////////

    

    $(document).on('click', function () { // Hide context menu on click outside 
        $('.wrapper').css('visibility', 'hidden');
    });

    
    var TicketTransactionSessionID = UserAccount; // User Who Logged In To The System
    var USER_ID = 'USER_ID'; //  Add To Global Table To Fetch User Ticket Data 
    var noRecord = $('#recoredPerPage').val();
    var order = '';
    var sortOrder = 'DESC';
    var page = 1;
    var filter = 10;
    var allData = [];

    var ticketNumber;
    var requestedBy;
    var serviceTypeNo;
    var serviceTypeName;
    var serviceDetailsNo;
    var serviceDetailsName;
    var periorityNo;
    var periorityNName;

    $('.hiddenList tbody').on('contextmenu', 'tr', function (e) {  // Show Action List  when Right Click on Table Row And Retrive Row Information
        e.preventDefault();

        ticketNumber            = $(this).find('td:first').text();
        serviceTypeName         = $(this).find('td:nth-child(2)').text();
        serviceDetailsName      = $(this).find('td:nth-child(3)').text();
        periorityNName          = $(this).find('td:nth-child(4)').text();
        serviceTypeNo           = $(this).find('td:nth-child(6)').text();
        serviceDetailsNo        = $(this).find('td:nth-child(7)').text();
        periorityNo             = $(this).find('td:nth-child(8)').text();
        requestedBy             = $(this).find('td:nth-child(12)').text();
        // Show context menu at the mouse position
        $('.wrapper').css({
            visibility: 'visible',
            left: e.pageX,
            top: e.pageY
        });

        $('#returnedTicketNumber').val(ticketNumber);
        $('#returnTicketNumber').text(ticketNumber);

    
        var UserRole = userRoleID;
        var ticketStatus = $(this).find('td:nth-child(5)').text();
        $('#actionTicketTransactionList').empty();

        // GM & Supervisor Permission 
        if (UserRole == 1 || UserRole == 3) {
            
            $('#actionTicketTransactionList').append(`
                <li>
                    <a href="newTicket.php" class="item"  data-bs-toggle='modal' data-bs-target="#AddNewTicketPopup" data-bs-whatever="AddNewTicketPopup" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='New Ticket'>
                        <i class="fa-solid fa-folder-open"></i>
                        <span>New Ticket</span>
                    </a>
                </li>
            `);

            if (ticketStatus == 'New') {
                // Additional content for status code 10
                $('#actionTicketTransactionList').append(`
                    <li>
                        <a class="item" style='margin-right: 5px;' id='editTicketInformation' data-bs-toggle='modal' data-bs-target="#EditTicketPopup" data-bs-whatever="EditTicketPopup" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Ticket'>
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Edit Ticket</span>
                        </a>
                    </li>
                    <li>
                        <button class="item" id='cancelTicket' style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cancel Ticket'>
                            <i class="fa-solid fa-ban"></i>
                            <span>Cancel</span>
                        </button>
                    </li>
                    <li>
                        <button class="item " id='assign' style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#assignPopup" data-bs-whatever="assign" data-bs-toggle='tooltip' data-bs-placement='top' title='Assign Ticket'>
                            <i class='fa-solid fa-at'></i>
                            <span>Assign</span>
                        </button>
                    </li>
                `);
            }

            if (ticketStatus == 'Assigned') {
                // Additional content for status code 20
                $('#actionTicketTransactionList').append(`
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Forword Ticket'>
                            <i class="fa-solid fa-share"></i>
                            <span>Forword</span>
                        </button>
                    </li>
                    <li>
                        <button class="item  startTicket" id='startTicket' style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Start Ticket'>
                            <i class="fa-solid fa-play"></i>
                            <span>Start</span>
                        </button>
                    </li>
                    <li>
                        <button class="item " id='change' style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#changePopup" data-bs-whatever="changePopup" data-bs-toggle='tooltip' data-bs-placement='top' title='Change Ticket'>
                            <i class="fa-solid fa-pen"></i>
                            <span>Change</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" id='cancelTicket' style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cancel Ticket'>
                            <i class="fa-solid fa-ban"></i>
                            <span>Cancel</span>
                        </button>
                    </li>
                `);
            }

            if (ticketStatus == 'Started') {
                // Additional content for status code 30
                $('#actionTicketTransactionList').append(`
                    <li>
                        <button class="item " id='change' style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#changePopup" data-bs-whatever="changePopup" data-bs-toggle='tooltip' data-bs-placement='top' title='Change Ticket'>
                            <i class="fa-solid fa-pen"></i>
                            <span>Change</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Forword Ticket'>
                            <i class="fa-solid fa-share"></i>
                            <span>Forword</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Update Out Service'>
                            <i class="fa-solid fa-wrench"></i>
                            <span>Update Out Service</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Received From Out'>
                            <i class="fa-solid fa-inbox"></i>
                            <span>Received From Out</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='SendOut Service'>
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>SendOut Service</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#solvePopup" data-bs-whatever="User" data-bs-toggle='tooltip' data-bs-placement='top' title='Solve Ticket'>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Complete</span>
                        </button>
                    </li>
                `);
            }

            if (ticketStatus == 'Solved') {
                // Additional content for status code 30
                $('#actionTicketTransactionList').append(`
                    <li>
                        <button class="item" style='margin-right: 5px;'  data-bs-toggle='modal' data-bs-target="#finishPopup" data-bs-whatever="finishPopup" data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Ticket'>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Confirm</span>
                        </button>
                    </li>
                `);
            }
            // Add common HTML content for all roles
            $('#actionTicketTransactionList').append(`
                <li>
                    <button class="item" style='margin-right: 5px;' id="actionHistoryTable" data-bs-toggle='modal' data-bs-target="#actionHistory" data-bs-whatever="assign" data-bs-toggle='tooltip' data-bs-placement='top' title='Action History'>
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Action History</span>
                    </button>
                </li>
                <li>
                    <button class="item" style='margin-right: 5px;' id='chatButton' data-bs-toggle='modal' data-bs-target="#TicketChat" data-bs-whatever="TicketChat" data-bs-toggle='tooltip' data-bs-placement='top' title='Chat'>
                        <i class="fa-solid fa-comments"></i>
                        <span>Chat</span>
                    </button>
                </li>
            `);
        }

        // Technichin Permission
        if (UserRole == 4) {
            $('#actionTicketTransactionList').append(`
                <li>
                    <a href="newTicket.php" class="item" data-bs-toggle='modal' data-bs-target="#AddNewTicketPopup" data-bs-whatever="AddNewTicketPopup" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='New Ticket'>
                        <i class="fa-solid fa-folder-open"></i>
                        <span>New Ticket</span>
                    </a>
                </li>
            `);

            if (ticketStatus == 'New') {
                // Additional content for status code 10
                $('#actionTicketTransactionList').append(`
                    <li>
                        <button class="item" id='cancelTicket' style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cancel Ticket'>
                            <i class="fa-solid fa-ban"></i>
                            <span>Cancel</span>
                        </button>
                    </li>
                `);
            }

            if (ticketStatus == 'Assigned') {
                // Additional content for status code 20
                $('#actionTicketTransactionList').append(`
                    <li>
                        <button class="item  startTicket" id='startTicket' style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Start Ticket'>
                            <i class="fa-solid fa-play"></i>
                            <span>Start</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" id='cancelTicket' style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cancel Ticket'>
                            <i class="fa-solid fa-ban"></i>
                            <span>Cancel</span>
                        </button>
                    </li>
                `);
            }

            if (ticketStatus == 'Started') {
                // Additional content for status code 30
                $('#actionTicketTransactionList').append(`
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Update Out Service'>
                            <i class="fa-solid fa-wrench"></i>
                            <span>Update Out Service</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Received From Out'>
                            <i class="fa-solid fa-inbox"></i>
                            <span>Received From Out</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='SendOut Service'>
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>SendOut Service</span>
                        </button>
                    </li>
                    <li>
                        <button class="item" style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#solvePopup" data-bs-whatever="User" data-bs-toggle='tooltip' data-bs-placement='top' title='Solve Ticket'>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Complete</span>
                        </button>
                    </li>
                `);
            }

            if (ticketStatus == 'Solved') {
                // Additional content for status code 30
                $('#actionTicketTransactionList').append(`
                    <li>
                        <button class="item" style='margin-right: 5px;'  data-bs-toggle='modal' data-bs-target="#finishPopup" data-bs-whatever="finishPopup" data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Ticket'>
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Confirm</span>
                        </button>
                    </li>
                `);
            }

            // Add common HTML content for all roles
            $('#actionTicketTransactionList').append(`
            <li>
                <button class="item" style='margin-right: 5px;' id="actionHistoryTable" data-bs-toggle='modal' data-bs-target="#actionHistory" data-bs-whatever="assign" data-bs-toggle='tooltip' data-bs-placement='top' title='Action History'>
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Action History</span>
                </button>
            </li>
            <li>
                <button class="item" style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#TicketChat" data-bs-whatever="TicketChat" data-bs-toggle='tooltip' data-bs-placement='top' title='Chat'>
                    <i class="fa-solid fa-comments"></i>
                    <span>Chat</span>
                </button>
            </li>
            `);
        }

        // List Action For End User
        if (UserRole == 2) {
            // End User Permission 
            $('#actionTicketTransactionList').append(`
                <li>
                    <a href="newTicket.php" class="item" data-bs-toggle='modal' data-bs-target="#AddNewTicketPopup" data-bs-whatever="AddNewTicketPopup" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='New Ticket'>
                        <i class="fa-solid fa-folder-open"></i>
                        <span>New Ticket</span>
                    </a>
                </li>
            `);

            if (ticketStatus == 'New') {
                // Additional content for status code 10
                $('#actionTicketTransactionList').append(`
                    
                    <li>
                        <button class="item" id='cancelTicket' style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cancel Ticket'>
                            <i class="fa-solid fa-ban"></i>
                            <span>Cancel</span>
                        </button>
                    </li>
                `);
            }

            if (ticketStatus == 'Assigned') {
                // Additional content for status code 20
                $('#actionTicketTransactionList').append(`
                    <li>
                        <button class="item" id='cancelTicket' style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cancel Ticket'>
                            <i class="fa-solid fa-ban"></i>
                            <span>Cancel</span>
                        </button>
                    </li>
                `);
            }

            if (ticketStatus == 'Solved') {
                // Additional content for status code 30
                $('#actionTicketTransactionList').append(`
                <li>
                    <button class="item" style='margin-right: 5px;'   data-bs-toggle='modal' data-bs-target="#finishPopup" data-bs-whatever="finishPopup" data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Ticket'>
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Confirm</span>
                    </button>
                </li>
                `);
            }

            // Add common HTML content for all roles
            $('#actionTicketTransactionList').append(`
            <li>
                <button class="item" style='margin-right: 5px;' id="actionHistoryTable" data-bs-toggle='modal' data-bs-target="#actionHistory" data-bs-whatever="assign" data-bs-toggle='tooltip' data-bs-placement='top' title='Action History'>
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Action History</span>
                </button>
            </li>
            <li>
                <button class="item" style='margin-right: 5px;' data-bs-toggle='modal' data-bs-target="#TicketChat" data-bs-whatever="TicketChat" data-bs-toggle='tooltip' data-bs-placement='top' title='Chat'>
                    <i class="fa-solid fa-comments"></i>
                    <span>Chat</span>
                </button>
            </li>
            `);
        }
        
    });

    $(document).on('click', '#assign', function(e) {        // Retrive Ticket Information To Assign Popup Function  
        e.preventDefault();
        $('#RequestedBy').val(" ");
        $('#requestType').val(" ");
        $('#serviceFor').val(" ");
        $('#ticketNumber').val(" ");
        $('#assignTeam').html(" ");
        $('#teamMember').empty();
        $('#ticketWeight').val(" ");
        $('#ticketPeriority').val(" ");
        $('#memberAssigned').empty();
        
        $('#ticketNumber').val(ticketNumber);
        $('#RequestedBy').val(requestedBy);
        $('#requestType').val(serviceTypeName);
        $('#serviceFor').val(serviceDetailsName);
        $('#ticketWeight').html(`"<option value='0' selected> Select Ticket Weight...</option>"`);
        $('#ticketPeriority').html(`"<option value='0' selected>Select Ticket Periority...</option>"`);
        $('#assignTeam').html(`"<option value='0' selected>Select Team...</option>"`);

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "selectDetailsTeamMember": serviceDetailsNo,
                "action":   "getdetailsteammembersForAssignPopup"
            
            },
            success: function (data) {
                // Parse the JSON response
                var responseData = JSON.parse(data);

                // Update HTML elements based on IDs
                $('#ticketWeight').append(responseData.weights);
                $('#assignTeam').append(responseData.teams);
                $('#ticketPeriority').append(responseData.priorities);
                
            },
            error: function(xhr, status, error){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText); // For debugging 
            }
        });
        
    });

    $('#assignTeam').on('change', function () {             // Retrive Team Member Based ON Team Choosen Function
        
        $('#teamMember').text("Loading ...");
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "teamMembers":    $(this).val(),
                "action" :     "getTeammemberDetail"
            
            },
            success: function (data) {
                
                ////////////////////////////////Parsing the data retrived and plot it in table view///////////////////////////////////////////////////
                var tableDBody = $('#teamMember');
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
            
                // Clear existing rows
                tableDBody.empty();
                jsonData.forEach(function (row) {
                    var newDRow = $('<tr>');
            
                    // Populate each cell with data
                    newDRow.html(`
            
                        <td hidden>${row.ID}</td>
                        <td>${row.name}</td>`
            
                        // Check Custody and Private conditions
                        + (row.active === 'Y' ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.active === 'Y' ? 'checked' : ''} disabled >
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox'  disabled>
                                </div>
                            </td>`)
                            +
                            `<td><button class='btn btn-warning includeBtn'>Include</button></td>`
                            );
            
                    // Append the new row to the table body
                    tableDBody.append(newDRow);
                    // Clear Existing Data In Table Service Details Team Member (tbody = serviceDetailsTeam) 
                    
                });
                ////////////////////////////////////////////////////////////////////////////////////////
            },
            error: function(xhr, status, error){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText); // For debugging 
            }
        });
    });
    
    var isChecked;
    $('.teamMemberTable').on('click', '.includeBtn', function() {  // Move Row From Team Member Table To Selected Team Member For Ticket
        // Get the row data
        var rowData = $(this).closest('tr').find('td').map(function() {
            return $(this).text();
        }).get();

        isChecked = $(this).closest('tr').find('input[type="checkbox"]').prop('checked');

        // Create a new row for the memberAssigned table
        var newRow = $('<tr>').append(
            $('<td hidden>').text(rowData[0]),  // User ID
            $('<td>').text(rowData[1]),  // User Name
            $('<td>').text(''),          // Description (empty)
            $('<td>').html('<div class="check"><input type="checkbox"></div>'),  // Team Leader checkbox   
            $('<td>').html('<button class="btn btn-warning excludeBtn">Exclude</button>')  // Exclude button 
        );

            // Clone the new row for the second table
        var newRowClone = newRow.clone();

        // Append the new row to the memberAssigned table
        $('#memberAssigned').append(newRow);
        // Append the cloned row to the second table
        $('#memberAssignedChange').append(newRowClone);

        // Remove the row from the teamMember table
        $(this).closest('tr').remove();

        var memberAssignedTable = $('#memberAssigned');
        var checkboxes = memberAssignedTable.find('input[type="checkbox"]');

        if (memberAssignedTable.find('tr').length === 1) {
            // Disable the checkbox if there is only one row
            checkboxes.prop('disabled', true);
        } else {
            // Enable all checkboxes
            checkboxes.prop('disabled', false);
            $('#assignTicket').hide();
            // Disable other checkboxes if one is checked
            checkboxes.on('change', function() {
                if ($(this).prop('checked')) {
                    checkboxes.not(this).prop('disabled', true);
                    $('#assignTicket').show();
                } else {
                    checkboxes.prop('disabled', false);
                }
            });
        }
    });

    // Exclude button click event
    $('.teamMemberTable').on('click', '.excludeBtn', function() {  // Return Row From Selected Team Member For Ticket To Team Member Table
        // Get the row data
        var rowData = $(this).closest('tr').find('td').map(function() {
            return $(this).text();
        }).get();

        // Create a new row for the teamMember table
        var newRow = $('<tr>').append(
            $('<td hidden>').text(rowData[0]),  // User ID
            $('<td>').text(rowData[1]),  // User Name
            $('<td>').html('<div class="check"><input type="checkbox"' + (isChecked ? ' checked' : '') + ' disabled></div>'),  // Status (assuming it's the third column)
            $('<td>').html('<button class="btn btn-warning includeBtn">Include</button>')  // Include button
        );

        // Append the new row to the teamMember table
        $('#teamMember').append(newRow);

        // Remove the row from the memberAssigned table
        $(this).closest('tr').remove();

        var memberAssignedTable = $('#memberAssigned');
        var checkboxes = memberAssignedTable.find('input[type="checkbox"]');

        if (memberAssignedTable.find('tr').length === 1) {
            // Disable the checkbox if there is only one row
            checkboxes.prop('disabled', true);
        } else {
            // Enable all checkboxes
            checkboxes.prop('disabled', false);

            // Disable other checkboxes if one is checked
            checkboxes.on('change', function() {
                if ($(this).prop('checked')) {
                    checkboxes.not(this).prop('disabled', true);
                } else {
                    checkboxes.prop('disabled', false);
                }
            });
        }
    });

    $(document).on('click', '#assignTicket', function(e) {        // Assign Ticket To The Team Member  
        e.preventDefault();

        
        var tableData = [];

        // Iterate through each row in the table
        $('.main-table #memberAssigned tr').each(function () {
            var isTeamLeader = $(this).find('td:eq(3) input[type=checkbox]').prop('checked');
            var rowData = {
                userID:         $(this).find('td:eq(0)').text(),
                userName:       $(this).find('td:eq(1)').text(),
                description:    $(this).find('td:eq(2)').text(),
                teamLeader:     isTeamLeader ? 'Y' : 'N',
            };

            // Add the row data to the array
            tableData.push(rowData);
        });

        // Convert data to JSON
        var jsonData = JSON.stringify(tableData);

        if (jsonData == '[]') {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: 'Please Choose Member To Assigne This Ticket!',
            });
        } else {
            $('#assignPopup').modal('hide');
        $(".overlay").css("display", "flex");
            $.ajax({
                type: 'POST',
                url: 'function.php', // Function Page For All ajax Function
                data: { 
                    'ticketNumber':                         ticketNumber,
                    "TicketTransactionSessionID":            TicketTransactionSessionID,
                    "ticketWeight":                         $('#ticketWeight').val(),
                    "ticketPeriority":                      $('#ticketPeriority').val(),
                    "memberAssigned":                       jsonData,
                    "assignTeam":                           $('#assignTeam').val(),
                    'action':                               'assignTicket'
                },
                success: function (response) {
                    
                    $(".overlay").css("display", "none");
                    Swal.fire("Ticket Assigned Successfully... ");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                    row.find('td:eq(4)').html('<span class="badge bg-warning">Assigned</span>');
                    $('#memberAssigned').empty();
                    $('#ticketWeight').val(" ");
                    $('#ticketPeriority').val(" ");
                    row.remove();
                    updateCounts();
                },
                error: function(xhr, status, error) {
                    
                    $(".overlay").css("display", "none");
                    $('#memberAssigned').empty();
                    $('#ticketWeight').val(" ");
                    $('#ticketPeriority').val(" ");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText); // For debugging 
                }
            });
        }

        
        
    });

    $(document).on('click', '#startTicket', function(e) {  // Update Ticket Status To Start Ticket Function

        e.preventDefault();
        
        $(".overlay").css("display", "flex");

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function ///cleab =
            data: {
                "tickid":                               ticketNumber,
                "TicketTransactionSessionID":           TicketTransactionSessionID,
                "action" :                              "start"
            },
            success: function (response) {
                $(".overlay").css("display", "none");
                    Swal.fire("Ticket Started Successfully... ");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                    row.find('td:eq(4)').html('<span class="badge bg-info">Started</span>');
                    row.remove();
                    updateCounts();
                },
                error: function(xhr, status, error) {
                    $(".overlay").css("display", "none");

                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText);
                }
            
        });
    });

    $(document).on('click', '#change', function(e) {        // Retrive Ticket Information To Change Popup Function  
        e.preventDefault();

        $('#memberAssignedChange').text("Loading...");
        $('#teamMemberChange').text("Loading...");

        $('#ticketNumberChange').val(ticketNumber);
        $('#RequestedByChange').val(requestedBy);
        $('#requestTypeChange').val(serviceTypeName);
        $('#serviceForChange').val(serviceDetailsName);
        $('#ticketWeightChange').empty();
        $('#assignTeamChange').empty();
        $('#ticketPeriorityChange').empty();

        if (periorityNo == " " || periorityNName == " ") {
            $('#ticketPeriorityChange').html(`"<option value='0' selected>Selecte Periority ....</option>"`);
        } else{
            $('#ticketPeriorityChange').html(`"<option value='${periorityNo}' selected>` + periorityNName + `</option>"`);
        }
        
        $('#teamMemberChange').text("Loading...");
        $('#waitingMessageForTeamAssignMemberChange').empty().removeClass('mt-5');
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "teamMembersAssigned": ticketNumber,
                "action" : "getTeamMemebersAssigned"
            },
            success: function (data) {

                var responseData = JSON.parse(data);
                ////////////////////////////////Parsing the data retrived and plot it in table view///////////////////////////////////////////////////
                
                var assignedTableBody = $('#memberAssignedChange');
                assignedTableBody.empty();

                responseData.teamAssigned.forEach(function (row) {
                    var newDRow = $('<tr>');
            
                    // Populate each cell with data
                    newDRow.html(`
            
                        <td hidden>${row.ID}</td>
                        <td>${row.name}</td>
                        <td>${row.disc}</td>`
            
                        // Check Custody and Private conditions
                        + (row.teamLeader === 'Y' ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.teamLeader === 'Y' ? 'checked' : ''}  >
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox'  >
                                </div>
                            </td>`)
                            +
                            `<td><button class='btn btn-warning excludeChangeBtn'>Exclude</button></td>`
                            );
            
                    // Append the new row to the table body
                    assignedTableBody.append(newDRow);
                    // Clear Existing Data In Table Service Details Team Member (tbody = serviceDetailsTeam) 
                    
                    var memberAssignedTable = $('#memberAssignedChange');
                    var checkboxes = memberAssignedTable.find('input[type="checkbox"]');

                    if (memberAssignedTable.find('tr').length === 1) {
                        // Disable the checkbox if there is only one row
                        checkboxes.prop('disabled', true);
                    } else {
                        // Enable all checkboxes
                        checkboxes.prop('disabled', false);
                    
                        // Disable other checkboxes if one is checked
                        checkboxes.on('change', function() {
                            if ($(this).prop('checked')) {
                                checkboxes.not(this).prop('disabled', true);
                            } else {
                                checkboxes.prop('disabled', false);
                            }
                        });
                    }
                });
                ////////////////////////////////////////////////////////////////////////////////////////
            
                var teamTableBody = $('#teamMemberChange');
                teamTableBody.empty();
                responseData.teamTables.forEach(function (row) {
                    var newDRow = $('<tr>');
            
                    // Populate each cell with data
                    newDRow.html(`
            
                        <td hidden>${row.ID}</td>
                        <td>${row.name}</td>`
            
                        // Check Custody and Private conditions
                        + (row.active === 'Y' ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.active === 'Y' ? 'checked' : ''} disabled >
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox'  disabled>
                                </div>
                            </td>`)
                            +
                            `<td><button class='btn btn-warning includeChangeBtn'>Include</button></td>`
                            );
            
                    // Append the new row to the table body
                    teamTableBody.append(newDRow);
                    // Clear Existing Data In Table Service Details Team Member (tbody = serviceDetailsTeam) 
                    
                });
                
                responseData.teamOption.forEach(function (option) {
                    var optionElement = $('<option selected></option>').attr('value', option.TEAM_NO).text(option.TEAM_NAME);
                    $('#assignTeamChange').append(optionElement);
                });

                $.ajax({
                    type: 'POST',
                    url: 'function.php', // Function Page For All ajax Function
                    data: { 
                        "selectDetailsTeamMember": serviceDetailsNo,
                        "action":   "getdetailsteammembersForAssignPopup"
                    },
                    success: function (data) {
                        // Parse the JSON response
                        var responseData = JSON.parse(data);
        
                        // Update HTML elements based on IDs
                        $('#ticketWeightChange').append(responseData.weights);
                        $('#assignTeamChange').append(responseData.teams);
                        $('#ticketPeriorityChange').append(responseData.priorities);
                    },
                    error: function(xhr, status, error){
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "There's Somthing Wrong !!",
                        });
                        console.error(xhr.responseText); // For debugging 
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText);
            }
        });
        
    });

    $('#assignTeamChange').on('change', function () {             // Retrive Team Member Based ON Team Choosen Function
        $('#memberAssignedChange').empty();
        $('#teamMemberChange').empty();
        $('#teamMemberChange').text(" Loading...");
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "teamMembers":    $(this).val(),
                "action" :     "getTeammemberDetail"
            
            },
            success: function (data) {
                
                ////////////////////////////////Parsing the data retrived and plot it in table view///////////////////////////////////////////////////
                var tableDBody = $('#teamMemberChange');
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
            
                // Clear existing rows
                tableDBody.empty();
                jsonData.forEach(function (row) {
                    var newDRow = $('<tr>');
            
                    // Populate each cell with data
                    newDRow.html(`
            
                        <td hidden>${row.ID}</td>
                        <td>${row.name}</td>`
            
                        // Check Custody and Private conditions
                        + (row.active === 'Y' ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.active === 'Y' ? 'checked' : ''} disabled >
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox'  disabled>
                                </div>
                            </td>`)
                            +
                            `<td><button class='btn btn-warning includeChangeBtn'>Include</button></td>`
                            );
            
                    // Append the new row to the table body
                    tableDBody.append(newDRow);
                    // Clear Existing Data In Table Service Details Team Member (tbody = serviceDetailsTeam) 
                    
                });
                ////////////////////////////////////////////////////////////////////////////////////////
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText);
            }
        });
    });

    var IsChecked;
    $('.teamMemberTable').on('click', '.includeChangeBtn', function() {  // Move Row From Team Member Change Table To Selected Team Member Change For Ticket
        // Get the row data
        var rowData = $(this).closest('tr').find('td').map(function() {
            return $(this).text();
        }).get();

        IsChecked = $(this).closest('tr').find('input[type="checkbox"]').prop('checked');

        // Create a new row for the memberAssigned table
        var newRow = $('<tr>').append(
            $('<td hidden>').text(rowData[0]),  // User ID
            $('<td>').text(rowData[1]),  // User Name
            $('<td>').text(''),          // Description (empty)
            $('<td>').html('<div class="check"><input type="checkbox"></div>'),  // Team Leader checkbox   
            $('<td>').html('<button class="btn btn-warning excludeChangeBtn">Exclude</button>')  // Exclude button 
        );

        // Append the new row to the memberAssigned table
        $('#memberAssignedChange').append(newRow);

        // Remove the row from the teamMember table
        $(this).closest('tr').remove();

        var memberAssignedTable = $('#memberAssignedChange');
        var checkboxes = memberAssignedTable.find('input[type="checkbox"]');

        if (memberAssignedTable.find('tr').length === 1) {
            // Disable the checkbox if there is only one row
            checkboxes.prop('disabled', true);
        } else {
            // Enable all checkboxes
            checkboxes.prop('disabled', false);
            $('#assignTicketChange').hide();
            // Disable other checkboxes if one is checked
            checkboxes.on('change', function() {
                if ($(this).prop('checked')) {
                    checkboxes.not(this).prop('disabled', true);
                    $('#assignTicketChange').show();
                } else {
                    checkboxes.prop('disabled', false);
                }
            });
        }

    });

    // Exclude button click event
    $('.teamMemberTable').on('click', '.excludeChangeBtn', function() {  // Return Row From Selected Team Member Change For Ticket To Team Member Change Table
        // Get the row data
        var rowData = $(this).closest('tr').find('td').map(function() {
            return $(this).text();
        }).get();

        // Create a new row for the teamMember table
        var newRow = $('<tr>').append(
            $('<td hidden>').text(rowData[0]),  // User ID
            $('<td>').text(rowData[1]),  // User Name
            $('<td>').html('<div class="check"><input type="checkbox"' + (IsChecked ? ' checked' : '') + ' disabled></div>'),  // Status (assuming it's the third column)
            $('<td>').html('<button class="btn btn-warning includeChangeBtn">Include</button>')  // Include button
        );

        // Append the new row to the teamMember table
        $('#teamMemberChange').append(newRow);

        // Remove the row from the memberAssigned table
        $(this).closest('tr').remove();

        var memberAssignedTable = $('#memberAssignedChange');
        var checkboxes = memberAssignedTable.find('input[type="checkbox"]');

        if (memberAssignedTable.find('tr').length === 1) {
            // Disable the checkbox if there is only one row
            checkboxes.prop('disabled', true);
        } else {
            // Enable all checkboxes
            checkboxes.prop('disabled', false);

            // Disable other checkboxes if one is checked
            checkboxes.on('change', function() {
                if ($(this).prop('checked')) {
                    checkboxes.not(this).prop('disabled', true);
                } else {
                    checkboxes.prop('disabled', false);
                }
            });
        }

    });

    $(document).on('click', '#assignTicketChange', function(e) {        // Return Service Details  To Update Ticket Information Popup Function  
        e.preventDefault();

        
        var tableData = [];

        // Iterate through each row in the table
        $('.main-table #memberAssignedChange tr').each(function () {
            var isTeamLeader = $(this).find('td:eq(3) input[type=checkbox]').prop('checked');
            var rowData = {
                userID:         $(this).find('td:eq(0)').text(),
                userName:       $(this).find('td:eq(1)').text(),
                description:    $(this).find('td:eq(2)').text(),
                teamLeader:     isTeamLeader ? 'Y' : 'N',
            };

            // Add the row data to the array
            tableData.push(rowData);
        });

        // Convert data to JSON
        var jsonData = JSON.stringify(tableData);

        if (jsonData == '[]') {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: 'You Should Choose Member To Assigne This Ticket!',
            });
        } else {
            $('#changePopup').modal('hide');
            $(".overlay").css("display", "flex");
            $.ajax({
                type: 'POST',
                url: 'function.php', // Function Page For All ajax Function
                data: { 
                    'ticketNumber':                      ticketNumber,
                    "TicketTransactionSessionID":                     TicketTransactionSessionID,
                    "ticketWeightChange":                $('#ticketWeightChange').val(),
                    "ticketPeriorityChange":             $('#ticketPeriorityChange').val(),
                    "memberAssignedChange":              jsonData,
                    "assignTeamChange":                  $('#assignTeamChange').val(),
                    'action':                            'assignTicketChange'
                },
                success: function (response) {
                    
                    $(".overlay").css("display", "none");
                    Swal.fire("Assign Changed Successfully... ");
                    $('#memberAssignedChange').empty();
                    $('#ticketWeightChange').val(" ");
                    $('#ticketPeriorityChange').val(" ");
                },
                error: function(xhr, status, error) {
                    
                    $(".overlay").css("display", "none");
                    $('#memberAssignedChange').empty();
                    $('#ticketWeightChange').val(" ");
                    $('#ticketPeriorityChange').val(" ");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText);
                }
            });
        }
    });
    
    $("#solveTicketForm").validate({                                          // Validate Function For Add New Team Member PopUp
        rules: {
            

            issue: "required", // Name field is required
            resolution: "required"
        },
        messages: {
            issue: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Ticket Issue</div>",
            resolution: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Ticket resolution </div>"
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $(document).on('click', '#solveTicket', function(e) {  // Update Ticket Status To Solve Ticket Function

        e.preventDefault();
        

        if ($("#solveTicketForm").valid()) {
            $('#solvePopup').modal('hide');
            $(".overlay").css("display", "flex");
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "tickid":                               ticketNumber,
                    "issue":                                $('#issue').val(),
                    "resolution":                           $('#resolution').val(),
                    "TicketTransactionSessionID":           TicketTransactionSessionID,
                    "action" :                              "solve"
                },
                success: function (response) {
                        
                        $(".overlay").css("display", "none");
                        Swal.fire("Ticket Solved Successfully");
                        var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                        row.find('td:eq(4)').html('<span class="badge bg-success">Solved</span>');
                        row.remove();
                        updateCounts();
                },
                error: function(xhr, status, error) {
                    
                    $(".overlay").css("display", "none");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText);
                }
            });
        } 
    });

    $(document).on('click', '#cancelTicket', function(e) {  // Update Ticket Status To Cancele Ticket Function

        e.preventDefault();
        $(".overlay").css("display", "flex");
        $.ajax({
            method: "POST",
            url: "function.php",
            data: {
                "tickid":                           ticketNumber,
                "TicketTransactionSessionID":        TicketTransactionSessionID,
                "action" :                          "cancel"
            },
            success: function (response) {
                $(".overlay").css("display", "none");
                    Swal.fire("Ticket Canceled Successfully");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                    row.find('td:eq(4)').html('<span class="badge bg-danger">Canceled</span>');
                    row.remove();
                    updateCounts();
            },
            error: function(xhr, status, error) {
                $(".overlay").css("display", "none");
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on('click', '#editTicketInformation', function(e) {  // Retrive Service Details For Ticket Updating     
        e.preventDefault();
        $('#EditRequestedBy').val(" ");
        $('#EditrequestType').val(" ");
        $('#EditTicketNumber').val(" ");
        $('#EditServiceDetails').empty();

        $('#EditTicketNumber').val(ticketNumber);
        $('#EditRequestedBy').val(requestedBy);
        $('#EditrequestType').val(serviceTypeName);
        $('#EditServiceDetails').html(`"<option value='${serviceDetailsNo}' selected>` + serviceDetailsName + `</option>"`);

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "EditServiceType":      serviceTypeNo, 
                "EditServiceDetails":   serviceDetailsNo,
                "action":               "getEditServiceDetails"
            },
            success: function (data) {
                $('#EditServiceDetails').append(data);
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText);
            }
        });
        
    });

    $(document).on('click', '#UpdateTicketInformationButton', function(e) {  // Update Ticket Information 
        e.preventDefault();
        $('#EditTicketPopup').modal('hide');
        $(".overlay").css("display", "flex");
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "UpdateTicketInformationButton":  $('#EditTicketNumber').val(),
                "EditRequestedBy":                $('#EditRequestedBy').val(),
                "EditrequestType":                $('#EditrequestType').val(),
                "EditServiceDetails":             $('#EditServiceDetails').val(),
                "action":                         "updateTicketInformation"
        },
            success: function (data) {
                
                $(".overlay").css("display", "none");
                Swal.fire("Ticket Information Updated Successfully ");
                var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                row.find('td:eq(2)').html('<span>' + $('#EditServiceDetails').find('option:selected').text() + '</span>');
            },
            error: function(xhr, status, error) {
                
                $(".overlay").css("display", "none");
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText);
            }
        });
        
    });

    $("#confirmTicketForm").validate({                                          // Validate Function For Add New Team Member PopUp
        rules: {
            evaluation: "required"
        },
        messages: {
            evaluation: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Ticket Evaluation </div>"
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $(document).on('click', '#ConfirmTicket', function(e) {     // Update Ticket Status To Confirme Ticket Function

        e.preventDefault();
        
            // Get values of individual inputs
            var evaluationDescription = $("#evaluation").val();
            // Get selected radio buttons
            var  responseTime = $("input[name='responseTime']:checked").val();
            var confirmSelection = $("input[name='confirmation']:checked").val();
            var technicianAttitude = $("input[name='technicianAttitude']:checked").val();
            var serviceEvaluation = $("#generalEvaluation").val();

        if ($("#confirmTicketForm").valid()) {
            $('#finishPopup').modal('hide');
            $(".overlay").css("display", "flex");
            $.ajax({
                type: "POST",
                url: "function.php", // Replace with your PHP file handling the request
                data: {
                    "returnedTicketNumber":         ticketNumber,
                    "evaluationDescription":        evaluationDescription,
                    "responseTime":                 responseTime,
                    "confirmSelection":             confirmSelection,
                    "technicianAttitude":           technicianAttitude,
                    "serviceEvaluation":            serviceEvaluation,
                    "TicketTransactionSessionID":                TicketTransactionSessionID,
                    "action":                       "confirm"
                },
                success: function(response){
                    
                    $(".overlay").css("display", "none");
                    Swal.fire("Ticket Confirmed Successfully");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                    row.find('td:eq(4)').html('<span class="badge bg-success">Confirmed</span>');
                    row.remove();
                    updateCounts();
                },
                error: function(xhr, status, error) {
                    
                    $(".overlay").css("display", "none");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText);
                }
            });
        }
    });

    $(document).on('click', '#actionHistoryTable', function(e) {  // Retrive Ticket Action History 

        e.preventDefault();

        $('#ticketActionHistoryBodyTable').empty();
        $('#ticketActionHistoryBodyTable').text("Loading Data...");

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: { 
                "actionHistory":  ticketNumber,
                "action":      "getHistory"   
            },
            success: function (data) {
                var tableBody = $('#ticketActionHistoryBodyTable');
                // Clear existing rows
                tableBody.empty();

                var jsonData = JSON.parse(data);

                jsonData.forEach(function (row) {
                    var newRow = $('<tr>');
                    // Populate each cell with data
                    newRow.html(`
                        <td >${row.SEQUENCE_NUMBER}</td>
                        <td>${row.ACTION_CODE}</td>
                        <td>${row.CREATED_BY}</td>
                        <td>${row.ACTION_DATE}</td>
                        <td>${row.COMMENTS}</td>
                        `);
                    // Append the new row to the table body
                    tableBody.append(newRow);
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText);
            }
        });
    });

    $('.hiddenList tbody').on('dblclick', 'tr', function() {
        // Get data from the clicked row if needed

        $('#timeDetails').text('Loading...');

        var tick = $(this).find('td:first').text();
        $(this).find('td:nth-child(18), td:nth-child(19)').on('click', function() {
            // Show the second popup
            $('#TimeDetails').modal('show');
            
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: { 
                    'DetailsTimePopup':  tick,
                    'action': 'TicketTimeDetails'
                },
                success: function (data) {
                    
                    $('#timeDetails').empty();
                    var jsonData = JSON.parse(data);
                    
                    var tableDBody = $('#timeDetails');

                // Clear existing rows
                tableDBody.empty();

                // Loop through the data and append rows to the table
                jsonData.forEach(function(ticket) {
                    var newDRow = $('<tr>');
                    // Populate each cell with data
                    newDRow.html(`
                    <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.DATE_DEFF}'>${ticket.DATE_DEFF}</td>
                        <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.CALCULATED_STATUS_TIME}'>${ticket.CALCULATED_STATUS_TIME}</td>
                        
                    `);
                    // Append the new row to the table body
                    tableDBody.append(newDRow);
                });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText);
                }
            });
            
        });

        $('#TicketDetailsPopup').modal('show');

        $('#TicketNumberDetails').val('');
        $('#ServiceTypeDetails').val('');
        $('#ServiceDetailsDetails').val('');
        $('#TicketPeriorityDetails').val('');
        $('#TicketStatusDetails').val(' ');
        $('#BranchCodeDetails').val(' ');
        $('#StartDateDetails').val(' ');
        $('#EndDateDetails').val(' ');
        $('#ITTotaleTimeDetails').val(' ');
        $('#RequestorNameDetails').val(' ');
        $('#RequestorDepartmentDetails').val(' ');
        $('#RequestorEmailDetails').val(' ');
        $('#RequestorIssueDiscriptionDetails').val(' ');
        $('#TechnicianNameDetails').val(' ');
        $('#TechnicianDepartmentDetails').val(' ');
        $('#TechnicianIssueDiscriptionDetails').val(' ');
        $('#TechnicianIssueResolutionDetails').val(' ');
        $('#ResponsTimeDetails').val(' ');
        $('#TechnicianAttitudeDetails').val(' ');
        $('#ServiceEvaluationInGeneralDetails').val(' ');
        $('#RequestorCommentDetails').val(' ');

        if($(this).find('td:nth-child(24)').text() == 1){
            var emojiRespons = '<i class="fa-solid fa-face-smile-beam "></i>';
        } else if($(this).find('td:nth-child(24)').text() == 0){
            var emojiRespons = '<i class="fa-solid fa-face-angry "></i>';
        } else {
            var emojiRespons = 'null';
        }

        if($(this).find('td:nth-child(25)').text() == 1){
            var emojiAttetude = '<i class="fa-solid fa-face-smile-beam "></i>';
        } else if($(this).find('td:nth-child(25)').text() == 0){
            var emojiAttetude = '<i class="fa-solid fa-face-angry "></i>';
        } else {
            var emojiAttetude = 'null';
        }

        $('#TicketNumberDetails').val($(this).find('td:first').text()).attr('title', $(this).find('td:first').text());
        $('#ServiceTypeDetails').val($(this).find('td:nth-child(2)').text()).attr('title', $(this).find('td:nth-child(2)').text());
        $('#ServiceDetailsDetails').val($(this).find('td:nth-child(3)').text()).attr('title', $(this).find('td:nth-child(3)').text());
        $('#TicketPeriorityDetails').val($(this).find('td:nth-child(4)').text()).attr('title', $(this).find('td:nth-child(4)').text());
        $('#TicketStatusDetails').val($(this).find('td:nth-child(20)').text()).attr('title', $(this).find('td:nth-child(20)').text());
        $('#BranchCodeDetails').val($(this).find('td:nth-child(15)').text()).attr('title', $(this).find('td:nth-child(15)').text());
        $('#StartDateDetails').val($(this).find('td:nth-child(14)').text()).attr('title', $(this).find('td:nth-child(14)').text());
        $('#EndDateDetails').val($(this).find('td:nth-child(17)').text()).attr('title', $(this).find('td:nth-child(17)').text());
        $('#ITTotaleTimeDetails').val($(this).find('td:nth-child(18)').text()).attr('title', $(this).find('td:nth-child(18)').text());
        $('#RequestorNameDetails').val($(this).find('td:nth-child(12)').text()).attr('title', $(this).find('td:nth-child(21)').text());
        $('#RequestorDepartmentDetails').val($(this).find('td:nth-child(23)').text()).attr('title', $(this).find('td:nth-child(23)').text());
        $('#RequestorEmailDetails').val($(this).find('td:nth-child(22)').text()).attr('title', $(this).find('td:nth-child(22)').text());
        $('#RequestorIssueDiscriptionDetails').val($(this).find('td:nth-child(9)').text()).attr('title', $(this).find('td:nth-child(9)').text());
        $('#TechnicianNameDetails').val($(this).find('td:nth-child(16)').text()).attr('title', $(this).find('td:nth-child(16)').text());
        $('#TechnicianDepartmentDetails').val($(this).find('td:nth-child(13)').text()).attr('title', $(this).find('td:nth-child(13)').text());
        $('#TechnicianIssueDiscriptionDetails').val($(this).find('td:nth-child(10)').text()).attr('title', $(this).find('td:nth-child(10)').text());
        $('#TechnicianIssueResolutionDetails').val($(this).find('td:nth-child(11)').text()).attr('title', $(this).find('td:nth-child(11)').text());
        $('#ResponsTimeDetails').html(emojiRespons).attr('title', $(this).find('td:nth-child(24)').text());
        $('#TechnicianAttitudeDetails').html(emojiAttetude).attr('title', $(this).find('td:nth-child(25)').text());
        $('#ServiceEvaluationInGeneralDetails').val($(this).find('td:nth-child(26)').text()).attr('title', $(this).find('td:nth-child(26)').text());
        $('#RequestorCommentDetails').val($(this).find('td:nth-child(27)').text()).attr('title', $(this).find('td:nth-child(27)').text());
    });

    $("#ticketDetailsForm").validate({                                          // Validate Function For Add New Team Member PopUp
        rules: {
            RequestorCommentDetails: "required",
            TechnicianIssueDiscriptionDetails: "required",
            TechnicianIssueResolutionDetails: "required"
        },
        messages: {
            RequestorCommentDetails: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Your Comment Evaluation </div>",
            TechnicianIssueDiscriptionDetails: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Ticket Issue </div>",
            TechnicianIssueResolutionDetails: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Ticket Resolution </div>"
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $(document).on('click', '#SaveTicketDetailsInformation', function(e) {     // Update Ticket Status To Confirme Ticket Function

        e.preventDefault();
            if ($("#ticketDetailsForm").valid()) {
                $('#TicketDetailsPopup').modal('hide');
                $(".overlay").css("display", "flex");
                $.ajax({
                    type: "POST",
                    url: "function.php", // Replace with your PHP file handling the request
                    data: {
                        "TicketNumberDetails":                      $('#TicketNumberDetails').val(),
                        "TechnicianIssueDiscriptionDetails":        $('#TechnicianIssueDiscriptionDetails').val(),
                        "TechnicianIssueResolutionDetails":         $('#TechnicianIssueResolutionDetails').val(),
                        "RequestorCommentDetails":                  $('#RequestorCommentDetails').val(),
                        "TicketTransactionSessionID":               TicketTransactionSessionID,
                        "action":                                   "TicketDetailsInformation"
                    },
                    success: function(response){
                       
                        $(".overlay").css("display", "none");
                        Swal.fire("Ticket Details Information Saved Successfully");
                        var row = $('#mainTableTicketTransation').find('td:contains(' + $('#TicketNumberDetails').val() + ')').closest('tr');
                        row.find('td:eq(10)').html($('#TechnicianIssueDiscriptionDetails').val());
                        row.find('td:eq(11)').html($('#TechnicianIssueResolutionDetails').val());
                        row.find('td:eq(27)').html($('#RequestorCommentDetails').val());
                    },
                    error: function(xhr, status, error) {
                        $(".overlay").css("display", "none");
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "There's Somthing Wrong !!",
                        });
                        console.error(xhr.responseText);
                    }
                });
            }
        
    });

    $(document).on('dblclick', '#activeUsers', function(e) {
        e.preventDefault();

        $('#TicketBehalfUserPopup').modal('show');
        $('#allEmployee').text("Loading...");
        $('#userSearch').val(' ');

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: { 
                "allUsers":  TicketTransactionSessionID,
                "action":    "allUsers"
            },
            success: function (data) {
                var jsonData = JSON.parse(data);
                var tableBody = $('#allEmployee');
                // Clear existing rows
                tableBody.empty();

                jsonData.forEach(function (row) {
                    var newRow = $('<tr>');
                    // Populate each cell with data
                    newRow.html(`
                        <td >${row.EBS_EMPLOYEE_ID}</td>
                        <td>${row.USER_EN_NAME}</td>
                        <td>${row.BRANCH_CODE}</td>
                        <td>${row.EMP_DEPARTMENT}</td>
                        <td>${row.EMAIL}</td>
                        <td>${row.USERNAME}</td>
                        `);
                    // Append the new row to the table body
                    tableBody.append(newRow);
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText);
            }
        });
        

    });

    $('#userSearch').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        // Loop through each table row
        $('#allEmployee tr').each(function() {
            var rowData = $(this).text().toLowerCase();
            // Show/hide rows based on search match
            if (rowData.indexOf(searchText) === -1) {
                $(this).addClass('hidden');
            } else {
                $(this).removeClass('hidden');
            }
        });
    });

    $('#allEmployee ').on('dblclick', 'tr ', function(e) {
        e.preventDefault();

        $('#TicketBehalfUserPopup').modal('hide');
        $('#AddNewTicketPopup').modal('show');
        $('#addTicket').closest('.content').find('#AddUserSessionName').val($(this).find('td:nth-child(6)').text());
    });

    $(document).on('click', '#sendMessage', function(e) {     // Update Ticket Status To Confirme Ticket Function

        e.preventDefault();
            // Get values of individual inputs
            $.ajax({
                type: "POST",
                url: "function.php", // Replace with your PHP file handling the request
                data: {
                    "messageFeild":                      $('#messageFeild').val(),
                    "TicketTransactionSessionID":        TicketTransactionSessionID,
                    "ticketNumber":                      ticketNumber,
                    "action":                            "chatMessage"
                },
                success: function(response){
                    
                    var messageHTML = '<div class="message">';
                    messageHTML += '<p><strong> You </strong> (Just now) :  ' + $('#messageFeild').val() + '</p>';
                    messageHTML += '</div>';
                    $('#chatScreen').append(messageHTML);
                    $('#messageFeild').val(' ');
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText);
                }
            });
        
    });

    $(document).on('click', '#chatButton', function(e) {     // Update Ticket Status To Confirme Ticket Function

        $('#chatScreen').val(' ');
        $('#chatScreen').text('Loading...');
        e.preventDefault();
            // Get values of individual inputs
            $.ajax({
                type: "POST",
                url: "function.php", // Replace with your PHP file handling the request
                data: {
                    "ticketNumber":                     ticketNumber,
                    "action":                            "chatHistory"
                },
                success: function(jsonData){
                    // Parse JSON data
                    var messages = JSON.parse(jsonData);

                    // Clear previous messages
                    $('#chatScreen').empty();

                    // Loop through messages and append them to the chat screen
                    for (var i = 0; i < messages.length; i++) {
                        var message = messages[i];
                        var messageHTML = '<div class="message">';
                        messageHTML += '<p><strong> ' + message.CREATED_BY +  ' </strong> (' + message.CREATION_DATE + ') :  ' + message.DESCRIPTION + '</p>';
                        messageHTML += '</div>';
                        $('#chatScreen').append(messageHTML);
                    }
                    
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText);
                }
            });
        
    });

    $(document).on('click', '#toExcel', function(e) {         // Export All Recored To Excel File

        e.preventDefault();

        // Convert JSON to worksheet
        const worksheet = XLSX.utils.json_to_sheet(allData);

        // Create a new workbook
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");

        // Generate Excel file
        const excelBuffer = XLSX.write(workbook, {
            bookType: 'xlsx',
            type: 'array'
        });

        // Convert to binary string
        const data = new Blob([excelBuffer], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });

        // Create download link
        const url = window.URL.createObjectURL(data);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'e-Ticketing System.xlsx');
        document.body.appendChild(link);

        // Initiate download
        link.click();

        // Cleanup
        setTimeout(function() {
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);
        }, 0);

        // var table2excel = new Table2Excel();
        // table2excel.export(document.querySelectorAll("table"));
    });
    
    $(document).on('click', '#orderBy', function(e) { // Fetch Ticket Transaction Data From DB Based On User Session And Ticket Status When Click On Tickets Button
        e.preventDefault();
        // Get the filter value from the 'data-filter' attribute of the clicked button
        order = $(this).data('filter');
        basedon = '#' + order;

        if (sortOrder === 'DESC') {
            sortOrder = 'ASC';
            $(basedon).removeClass('fa-solid fa-arrow-up').addClass('fa-solid fa-arrow-down');
        } else {
            sortOrder = 'DESC';
            $(basedon).removeClass('fa-solid fa-arrow-down').addClass('fa-solid fa-arrow-up');
        }

        var column = $(this).index(); // Get the index of the clicked column

        // Sort the table rows based on the column data
        $('#mainTableTicketTransation').each(function() {
            var rows = $(this).find('tr').get();
            rows.sort(function(a, b) {
                var aValue = $(a).children('td').eq(column).text();
                var bValue = $(b).children('td').eq(column).text();
                if (sortOrder === 'ASC') {
                    return aValue.localeCompare(bValue);
                } else {
                    return bValue.localeCompare(aValue);
                }
            });
            // Re-render the table rows with the sorted data
            $.each(rows, function(index, row) {
                $(this).parent().append(row);
            });
        });

    });

    
    ///////////////////////////////////////////***************** Search Ticket Start  *************************////////////////////////////////////////

    

    function updateCounts() {

        var allRecord = 0; // Initialize the total count

        $('.tickets').each(function() {
            var filter = $(this).data('filter');
            $.ajax({
                type: 'POST',
                url: 'function.php', // Replace with the URL of your PHP file to get the count
                data: {
                    "filter": filter,
                    "USER_ID": USER_ID,
                    "TicketTransactionSessionID": TicketTransactionSessionID,
                    "action": 'getFilterdData'
                },
                success: function(response) {
                    $('#count-' + filter).text('( ' + response + ' )');
                    allRecord += parseInt(response);
                    $('#allRows').text('( ' + allRecord + ' )');
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText);
                }
            });
        });
    }

    function refreshData() {
        getAllData(TicketTransactionSessionID, order, sortOrder);
    }

    function getAllData(TicketTransactionSessionID, order, sortOrder) {
        $('.tran').hide(100);
        $('#mainTableTicketTransation').empty();
        $('#mainTableTicketTransation').append('Loading....');

        var startTime = new Date().getTime();
        $.ajax({
            type: 'POST',
            url: 'function.php',
            data: {
                "userNamePreResault": 'USER_ID',
                "TicketTransactionSessionID": TicketTransactionSessionID,
                "order": order,
                "sortOrder": sortOrder,
                "Filter": 10,
                "action": 'TicketTransactionFilter'
            },
            success: function(data) { 
                    allData = JSON.parse(data);
                    displayFilterData(page, noRecord);
                    var duration = new Date().getTime() - startTime;
                    var durationInSeconds = duration / 1000;
                    $('#time').html("<h5 class='text-center' style='color: red; border: 1px solid black; max-width: 300px; padding: 10px; margin-left: 20px;  '>AJAX request took " + durationInSeconds + " seconds</h5>");
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText);
            }
        });
    }
    
    function displayFilterData(page, noRecord) {

        $('#paginationContainer').empty();
        $('#numberOfPages').empty();

        let startIndex = (page - 1) * noRecord;
        let endIndex = page * noRecord;

        let pageData = allData.slice(startIndex, endIndex);
        var tableDBody = $('#mainTableTicketTransation');

        // Clear existing rows
        tableDBody.empty();

        // Loop through the data and append rows to the table
        pageData.forEach(function(ticket) {
            var newDRow = $('<tr>');

            if (ticket.TICKET_STATUS == '70') {
                newDRow.addClass('canceled-row');
            }

            // Populate each cell with data
            newDRow.html(`
            <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TICKET_NO}'>${ticket.TICKET_NO}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.SERVICE_TYPE}'>${ticket.SERVICE_TYPE}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.SERVICE_DETAIL}'>${ticket.SERVICE_DETAIL}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TICKET_PERIORITY_MEANING}'>${ticket.TICKET_PERIORITY_MEANING}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TICKET_STATUS}'>${
                ticket.TICKET_STATUS == '10' ? '<span class="badge bg-secondary">New</span>' :
                ticket.TICKET_STATUS == '20' ? '<span class="badge bg-warning">Assigned</span>' :
                ticket.TICKET_STATUS == '30' ? '<span class="badge bg-info">Started</span>' :
                ticket.TICKET_STATUS == '60' ? '<span class="badge bg-success">Solved</span>' :
                ticket.TICKET_STATUS == '40' ? '<span class="badge bg-success">Confirmed</span>' :
                ticket.TICKET_STATUS == '50' ? '<span class="badge bg-danger">Rejected</span>' :
                ticket.TICKET_STATUS == '70' ? '<span class="badge bg-danger">Canceled</span>' :
                ticket.TICKET_STATUS == '110' ? '<span class="badge bg-info">Sent Out</span>' :
                ticket.TICKET_STATUS == '120' ? '<span class="badge bg-primary">Recevied</span>' :
                ticket.TICKET_STATUS == '140' ? '<span class="badge bg-success">Confirmed by system</span>' :
                ''
                    }</td>
                <td hidden>${ticket.REQUEST_TYPE_NO}</td>
                <td hidden>${ticket.SERVICE_DETAIL_NO}</td>
                <td hidden>${ticket.TICKET_PERIORITY}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.ISSUE_DESCRIPTION}'>${ticket.ISSUE_DESCRIPTION}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TECHNICAL_ISSUE_DESCRIPTION}'>${ticket.TECHNICAL_ISSUE_DESCRIPTION}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TECHNICAL_ISSUE_RESOLUTION}'>${ticket.TECHNICAL_ISSUE_RESOLUTION}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.USERNAME}'>${ticket.USERNAME}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.DEPARTMENT_NAME}'>${ticket.DEPARTMENT_NAME}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TICKET_START_DATE}'>${ticket.TICKET_START_DATE}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.BRANCH_CODE}'>${ticket.BRANCH_CODE}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.ASSIGNED_TO}'>${ticket.ASSIGNED_TO}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TICKET_END_DATE}'>${ticket.TICKET_END_DATE}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TTOTAL_TIME}'>${ticket.TTOTAL_TIME}</td>
                <td data-bs-toggle='tooltip' data-bs-placement='top' title='${ticket.TOTAL_TIME}'>${ticket.TOTAL_TIME}</td>
                <td hidden>${ticket.TICKET_STATUS_MEANING}</td>
                <td hidden>${ticket.USER_EN_NAME}</td>
                <td hidden>${ticket.EMAIL}</td>
                <td hidden>${ticket.EMP_DEPARTMENT}</td>
                <td hidden>${ticket.RESPONSE_TIME}</td>
                <td hidden>${ticket.TECHNICIAN_ATTITUDE}</td>
                <td hidden>${ticket.SERVICE_EVALUATION}</td>
                <td hidden>${ticket.REQUESTOR_COMMENTS}</td>
                <td hidden>${ticket.EVALUATION_FLAG}</td>
            `);

            // Append the new row to the table body
            tableDBody.append(newDRow);
        });

        let noPage = Math.ceil(allData.length / noRecord);

        if (page > 1) {
            let previous = (page - 1);
            $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + 1 + "'>First</span></li>");
            $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + previous + "'>Previous</span></li>");
        }

        let count = 0;
        for (let i = page; i <= noPage - 1; i++) {
            $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + i + "'>" + i + "</span></li>");
            count++;
            if (count == 3 || i == noPage) {
                break;
            }
        }

        if (noPage == page) {
            $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>" + noPage + "</span></li>");
        } else {
            $('#paginationContainer').append("<li class='page-item'><span class='' style='margin: 5px;' >....</span></li>");
            $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link ' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>" + noPage + "</span></li>");
        }

        if (page < noPage) {
            var next = parseInt(page) + 1;
            $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + next + "'>Next</span></li>");
            $('#paginationContainer').append("<li class='page-item'><span class='pagination_link pagination page-link' style='cursor: pointer; padding: 5px 10px; margin: 5px; border: 1px solid #0069d9; border-radius: 50% ; ' id='" + noPage + "'>Last</span></li>");
        }

        $('#numberOfPages').html('<span style="color: #0069d9;"> Showing <b> ' + page + ' </b> of <b>' + noPage + ' </b> Pages : </span>');

        console.log('from success case');
    }

    


    ///////////////////////////////////////////***************** Ticket Transation Page End  *************************/////////////////////////////////////////

///////////////////////////////////////////***************** Add New Ticket Page Start  *************************/////////////////////////////////////////


$('#service').on('change', function () {    // Retrive Service Details Based On Service Type Function
    var selectedService = $(this).val(); // Service Type Number
    $.ajax({
        type: 'POST',
        url: 'function.php', // Function Page For All ajax Function
        data: { 
            "serviceType": selectedService,
            "NewTicket" : "getservicesdetails"
        
        },
        success: function (data) {
            $('#details').empty();
            $('#details').append(`<option  value="">Select Service Details....</option>`);
            $('#details').append(data);
        },
        error: function(xhr, status, error){
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "There's Somthing Wrong !!",
            });
            console.error(xhr.responseText); // For debugging 
        }
    });
});

$('#details').on('change', function () {    // Retrive Device Number Based On Service Details If its value custody Function
    
    // Check if selectedDetails is '14' and update #device field
    $.ajax({
        type: 'POST',
        url: 'function.php', // Handle Page For All AJAX Function
        data: { 
            'details':          $(this).val(),
            'UserSessionName':    $(this).closest('.content').find('#AddUserSessionName').val(),
            'NewTicket':        'getDeviceNumber'
        }, 
        success: function (data) {
            
            if (data === 'empty') {
                $('#device').prop('disabled', true);
                $('#device').prop('required', false);
            } else {
                $('#device').prop('disabled', false);
                $('#device').append(`<option  value="">Select  Device....</option>`);
                $('#device').append(data);
            }
        },
        error: function(xhr, status, error){
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "There's Somthing Wrong !!",
            });
            console.error(xhr.responseText); // For debugging 
        }
    });
});

$("#AddNewTicketForm").validate({ // Validate Function For Add New Service PopUp
    rules: {
        service: "required", // Name field is required
        details: "required", // Name field is required
        description: "required", // Name field is required
        device: "required" // Name field is required
    },
    messages: {
        service: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Service Name</div>", // Name field is required
        details: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Service Details Name</div>", // Name field is required
        description: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Service Issue Description</div>", // Name field is required
        device: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Device</div>" // Name field is required
    },
    submitHandler: function(form) {
        // Form is valid, proceed with form submission
        form.submit();
    }
});

$(document).on('click', '#addTicket', function(e) { // Add New Ticket To Tickets Table Function

    e.preventDefault();

    var allRecord = 0;
    if ($("#AddNewTicketForm").valid()) {

        $('#AddNewTicketPopup').modal('hide');
        $(".overlay").css("display", "flex");
        $.ajax({
            method: "POST",
            url: "function.php", // Function Page For All ajax Function
            data: {
                "name": $(this).closest('.content').find('#AddUserSessionName').val(),
                "service": $(this).closest('.content').find('.service').val(),
                "details": $(this).closest('.content').find('.details').val(),
                "device": $(this).closest('.content').find('.device').val(),
                "description": $(this).closest('.content').find('.description').val(),
                "action": "add"
            },
            success: function(response) {

                $(".overlay").css("display", "none");
                var regex = /[\[\]]/g;
                var cleanedText = response.replace(regex, '');
                Swal.fire("Ticket # " + cleanedText + " Created Successfully!!!");
                $('#service').val('');
                $('#details').val('');
                $('#device').val('');
                $('#description').val('');
                $('#addTicket').closest('.content').find('#AddUserSessionName').val();
                $('#addTicket').closest('.content').find('#AddUserSessionName').val($('#addTicket').closest('.content').find('#AddUserSessionName').val());
                updateCounts();
                refreshData();
            },
            error: function(xhr, status, error) {
                $(".overlay").css("display", "none");
                $('#service').val('');
                $('#details').val('');
                $('#device').val('');
                $('#description').val('');
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText);
            }
        });
    }
});


$(document).on('click', '#CreateNewTicket', function(e) {         // Hide Ticket Transation Dropdown  List

    e.preventDefault();
    $('.tran').hide(100);
    
}); 

///////////////////////////////////////////***************** Add New Ticket Page End  *************************/////////////////////////////////////////







///////////////////////////////////////////***************** Delegate Page Start  *************************/////////////////////////////////////////

var delegateSessionID = UserAccount;

var currentDate = new Date();

// Format the current date as YYYY-MM-DD
var formattedDate = currentDate.toISOString().split('T')[0];

// Set the minimum value of the Start Date input to the current date
$('#StartDate').attr('min', formattedDate);

    $("#DelegateForm").validate({                                          // Validate Function For Add New Delegate
        rules: {
            delegateTeam: "required" ,// Name field is required
            delegateUser: "required", // Name field is required
            StartDate: "required", // Name field is required
            EndDate: "required" // Name field is required
        },
        messages: {
            delegateTeam: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose Team Name</div>" ,// Name field is required
            delegateUser: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Choose User Name</div>", // Name field is required
            StartDate: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter Start Date</div>", // Name field is required
            EndDate: "<div class='alert alert-danger' role='alert' style=' margin-top: 5px;' >Please Enter End Date</div>" // Name field is required
        },
        submitHandler: function(form) {
            // Form is valid, proceed with form submission
            form.submit();
        }
    });

    $('#StartDate').change(function() {
        // Get the selected value of the Start Date input field
        var startDateValue = $(this).val();
        // Set the minimum date of the End Date input field to the selected value of the Start Date input field

        $('#EndDate').prop('disabled', false);
        $('#EndDate').attr('min', startDateValue);
    });

    $('#delegateTeam').on('change', function () {   // Return  Users To Delegate  Based On Team Number Function
        var teamName = $(this).val();  // Team Number
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "teamInfoDelegate": teamName,
                "delegate" :    "returnUser"
            },
            success: function (data) {
                $('#delegateUser').empty();
                $('#delegateUser').append(`<option  value="">Select User ....</option>`);
                $('#delegateUser').append(data);
            },
            error: function(xhr, status, error){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText); // For debugging 
            }
        });
    });
    
    $(document).on('click', '#DelegateNewUser', function(e) {         // Add New Delegate User Table Function

        e.preventDefault();
        
        if ( $("#DelegateForm").valid()){
            $(".overlay").css("display", "flex");
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    
                    "delegateTeam":             $(this).closest('.content').find('#delegateTeam').val(),
                    "delegateUser":             $(this).closest('.content').find('#delegateUser').val(),
                    "StartDate":                $(this).closest('.content').find('#StartDate').val(),
                    "EndDate":                  $(this).closest('.content').find('#EndDate').val(),
                    "delegateSessionID":        delegateSessionID,
                    "delegate" :                  "createNewDelegate"
                },
                success: function (response) {
                    $(".overlay").css("display", "none");
                    Swal.fire("User Delegated Successfully");
                    $('#delegateTeam').val('');
                    $('#delegateUser').val('');
                    $('#StartDate').val('');
                    $('#EndDate').val('');
                },
                error: function(xhr, status, error) {
                    $(".overlay").css("display", "none");
                    $('#delegateTeam').val('');
                    $('#delegateUser').val('');
                    $('#StartDate').val('');
                    $('#EndDate').val('');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "There's Somthing Wrong !!",
                    });
                    console.error(xhr.responseText); // For debugging 
                }
            });
        }
    }); 

    $('#delegateHistory').on('change', function () {   // Return Delegated Users  Based On Team Number Function
        var delegateUser = $(this).val();  // Team Number

        $('#delegateBody').text('Loading...');
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                "delegated": delegateUser,
                "delegate": "delegateHistory"
            },
            success: function (data) {
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
                var tableDBody = $('#delegateBody');
                // Clear existing rows
                tableDBody.empty();
                jsonData.forEach(function (row) {
                    var newDRow = $('<tr>');
                    // Populate each cell with data
                    newDRow.html(`
                        <td>${row.name}</td>
                        <td>${row.start}</td>
                        <td>${row.end}</td>`
                            );
                    // Append the new row to the table body
                    tableDBody.append(newDRow);
                });
            },
            error: function(xhr, status, error){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText); // For debugging 
            }
        });
    });


///////////////////////////////////////////***************** Delegate Page End  *************************/////////////////////////////////////////



///////////////////////////////////////////***************** Change All Solved Ticket To Confirm Button Start  *************************/////////////////////////////////////////

    $('#UpdateAllSolveTicketToConfirm').on('click', function () {   // Change All Solved Ticket To Confirm

        $(".overlay").css("display", "flex");
        
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { UserNameSession: TicketTransactionSessionID },
            success: function (data) {
                if (data === 'empty') {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Theres No Ticket To Confirmed It",
                    });
                } else {
                    $(".overlay").css("display", "none");
                    $('.tran').hide(100);
                    Swal.fire(" Tickets Confirmed Successfully ");
                }
                
            },
            error: function(xhr, status, error){
                $('.tran').hide(100);
                $(".overlay").css("display", "none");
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText); // For debugging 
            }
        });
    });

    $('#UpdateAllSolveTicketToConfirmhome').on('click', function () {   // Change All Solved Ticket To Confirm

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { UserNameSession: TicketTransactionSessionID },
            success: function (data) {
                if (data === 'empty') {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Theres No Ticket To Confirmed It",
                    });
                } else {
                    $('.tran').hide(100);
                    Swal.fire(" Tickets Confirmed Successfully ");
                }
                
            },
            error: function(xhr, status, error){
                $('.tran').hide(100);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "There's Somthing Wrong !!",
                });
                console.error(xhr.responseText); // For debugging 
            }
        });
    });

    ///////////////////////////////////////////***************** Change All Solved Ticket To Confirm Button End  *************************/////////////////////////////////////////


    $(document).on('click', '#turnoff',  function (e) {
        e.preventDefault();

        Swal.fire({
            iconHtml: '<i class="fas fa-power-off"></i>',
            title: "Logging  Out ...",
            text: "Good Bye",
            showConfirmButton: false,
            allowOutsideClick: false 
        });

        window.location.href = 'logout.php';

    }); 


    $('#UserAccount').val(UserAccount); // Set the value of the select element to the saved refresh mode

    $('#UserAccount').on('change', function() { // Event listener for refresh mode change
        UserAccount = $(this).val();
        localStorage.setItem('UserAccount', UserAccount); // Save refresh mode to local storage
        TicketTransactionSessionID = UserAccount;
        TeamPageSessionID = UserAccount;
        ServiceUserSessionID =  UserAccount;
        delegateSessionID = UserAccount;
        getUserPrivilag(UserAccount);
    });


});












