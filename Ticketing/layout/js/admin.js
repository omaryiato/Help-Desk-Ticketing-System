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

    // Hide Placeholder On Form Focus

    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');

    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // Toggel User Dropdown List

    $('.users').click(function(event) {
        // Prevent the event from bubbling up to the document
        event.stopPropagation();
        // Toggle the .userno element
        $('.userno').toggle(100);
    });

    $(document).on('click', function(event) {
        // Check if the clicked element is not part of .users or .userno
        if (!$(event.target).closest('.users, .userno').length) {
            // Hide the .userno element
            $('.userno').hide(100);
        }
        
    });
    
    // Toggel Ticket Transaction Dropdown List

    $('.trans').click(function() {
        $('.tran').toggle(100);
    });

    $(document).on('click', function(event) {
        // Check if the clicked element is not part of .users or .userno
        if (!$(event.target).closest('.trans, .tran').length) {
            // Hide the .userno element
            $('.tran').hide(100);
        }
    });



    /////////////////////////////////////////// ***************** Manage Service Page Start  *************************/////////////////////////////////////////

    var UserSessionID =  $('#UserSessionID').val(); // User Name In This Session (Who Logged In)


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
            // Form is valid, proceed with AJAX submission
            $.ajax({
                method: "POST",
                url: "function.php",
                data: {
                    "serviceName":          $(this).closest('.content').find('#NewServiceName').val(),
                    "UserSessionID":        UserSessionID,
                    "action" :              "NewService"
                },
                success: function (response) {
                    $('#NewService').modal('hide');
                    Swal.fire("Service Added Successfully ");
                    $('#serviceLOV').html(response);
                    $('#NewServiceName').val('');
                },
                error: function () {
                    $('#NewService').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "This Service Name Is Already Exist!",
                    });
                    $('#serviceLOV').html(response);
                    $('#NewServiceName').val('');
                }
            });
        } 
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
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "NewServiceDetailsName":            $(this).closest('.content').find('#NewServiceDetailsName').val(),
                    "UserSessionID":                    UserSessionID,
                    "GetServiceTypeID":                 $(this).closest('.content').find('#GetServiceTypeID').val(),
                    "ServiceDetailsDescription":        $(this).closest('.content').find('#ServiceDetailsDescription').val(),
                    "action" :                          "NewServiceDetails"
                },
                success: function (response) {
                    $('#NewServiceDetailsName').val('');
                    $('#ServiceDetailsDescription').val('');
                    $('#NewServiceDetail').modal('hide');
                    Swal.fire("Service Details Added Successfully ");
                    setTimeout(function() {
                        fillServiceDetailsTable();
                    }, 0);
                },
                error: function (response) { 
                    $('#NewServiceDetailsName').val('');
                    $('#ServiceDetailsDescription').val('');
                    $('#NewServiceDetail').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "This Service Details Name Is Already Exist!",
                    });
                    setTimeout(function() {
                        fillServiceDetailsTable();
                    }, 0);
                }
            });
        }
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
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "GetServiceDetailsName":                        $(this).closest('.content').find('#GetServiceDetailsName').val(),
                    "GetServiceDetailsTeamNumber":                  $(this).closest('.content').find('#GetServiceDetailsTeamNumber').val(),
                    "GetServiceDetailsID":                          $(this).closest('.content').find('#GetServiceDetailsID').val(),
                    "UserSessionID":                                UserSessionID,
                    "action" :                                      "NewServiceDetailsTeam"
                },
                success: function (response) {
                    $('#NewDetailTeam').modal('hide');
                    Swal.fire("Service Details Team Added Successfully ");
                    setTimeout(function() {
                        fillServiceDetailsTeamTable();
                    }, 0);
                },
                error: function (response) { 
                    $('#NewDetailTeam').modal('hide');
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "This Service Details Name Is Already Exist!",
                        });
                    setTimeout(function() {
                        fillServiceDetailsTeamTable();
                    }, 0);
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

    $(document).on('click', '#UpdateServiceDetailsInfoButton', function(e) {    // Edit Service Details Information Function

        e.preventDefault();
        if ($("#EditServiceDetailsInformationForm").valid()) {
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "EditServiceDetailsName":           $(this).closest('.content').find('#EditServiceDetailsName').val(),
                    "UserSessionID":                    UserSessionID,
                    "EditServiceDetailsDescription":    $(this).closest('.content').find('#EditServiceDetailsDescription').val(),
                    "EditServiceDetailsID":             $(this).closest('.content').find('#EditServiceDetailsID').val(),
                    "action" :                          "EditServiceDetailsInformation"
                },
                success: function (data) {
                    $('#EditServiceDetails').modal('hide');
                    Swal.fire("Service Details Updated Successfully ");
                    setTimeout(function() {
                        fillServiceDetailsTable();
                    }, 0);
                },
                error: function () {
                    $('#EditServiceDetails').modal('hide');
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Error fetching Service Details Information",
                        });
                        setTimeout(function() {
                            fillServiceDetailsTable();
                        }, 0);
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
        $.ajax({
                type: 'POST',
                url: 'function.php',
                dataType: 'json',
                data: { 
                    custodyColumnJson:  custodyColumnJson,
                    privateColumnJson:  privateColumnJson,
                    UserSessionID:             UserSessionID,
                    action:             "updateServiceDetailsTable"
                },
                success: function (response) {
                    // Replace this Popup To normal popup 
                    Swal.fire("Service Detail Updated Successfully"); 
                    setTimeout(function() {
                        fillServiceDetailsTable();
                    }, 0);
                    
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Error updating Service Detail",
                    });
                    setTimeout(function() {
                        fillServiceDetailsTable();
                    }, 0);
                }
        });
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
        $.ajax({
                type: 'POST',
                url: 'function.php',
                dataType: 'json',
                data: { 
                    teamEnabled:    enableTeamServiceJson,
                    userID:         UserSessionID,
                    action:         "updateTeamTable"
                },
                success: function (response) {
                    Swal.fire("Enabled updated successfully ");
                    setTimeout(function() {
                        fillServiceDetailsTeamTable();
                    }, 0);

                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Error updating Enabled",
                    });
                    setTimeout(function() {
                        fillServiceDetailsTeamTable();
                    }, 0);
                }
            });
    });

    $(document).on('click', '#notAssignedTeam', function(e) {                   // Retrive Not Selected Service Details Team  Function

        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { notassignedteam: $('#GetServiceDetailsID').val() },
            success: function (data) {
                $('#GetServiceDetailsTeamNumber').html(data);
            },
            error: function () {
                alert('Error fetching Teams');
            }
        });
    });

    // ***************** Edit Service Details Start  *************************

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
        $('#EditServiceDetailsID').empty();
        $('#EditServiceDetailsName').empty();
        $('#EditServiceDetailsDescription').empty();
        $('#EditServiceDetailsID').val(serviceDetailsId);
        $('#EditServiceDetailsName').val(serviceDetailsName);
        $('#EditServiceDetailsDescription').val(serviceDetailsDescription);
    });

    // ***************** Edit Service Details End  *************************

    $('#serviceLOV').on('change', function ()                           // Call fillServiceDetailsTable() Function 
    {  // Return Service Number  Based On Service Name Function
        fillServiceDetailsTable();
    });

    function fillServiceDetailsTable()                                  // Retrive Service Details Information Based On Service Type Number Function 
    {       // This function is not used now 4JAN2024-- Display Service Details Information Based On Service Number Function
        $('#ServiceID').val($('#serviceLOV').val()); // to retrive the selected ID into ServiceID text Box
        $('#serviceDetails').empty();
        $('#serviceDetails').text("Waiting Data");
        $('#serviceDetailsTeam').empty();
        $('#GetServiceTypeID').val($('#serviceLOV').val());
        $('#GetServiceTypeName').val($('#serviceLOV').find('option:selected').text());
        $('#waitingMessage').empty().removeClass('mt-5');
    
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { details: $('#serviceLOV').val() },
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
                //fillServiceTable(data);
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
            error: function () {
                alert('Error fetching users');
            }
        });
    }

    var currentServiceDetailsID;
    var currentServiceDetailsName;
    $('#ServiceDetailsID tbody').on('click', 'tr td', function () {     // Call fillServiceDetailsTeamTable() Function

        currentServiceDetailsID = $(this).closest('tr').find('td:first').text();
        currentServiceDetailsName = $(this).closest('tr').find('td:nth-child(2)').text();

        $('#GetServiceDetailsID').val(currentServiceDetailsID);
        $('#GetServiceDetailsName').val(currentServiceDetailsName);

        if (!$(this).is(':last-child, :nth-child(4)')) {
            fillServiceDetailsTeamTable();
        }
    });

    function fillServiceDetailsTeamTable() {                            //  Retrive Service Team Information Based On Service Details Number Function
    
            $('#serviceDetailsTeam').empty();
            $('#serviceDetailsTeam').text("Waiting Data");
            $('#GetServiceDetailsID').empty();
            $('#GetServiceDetailsName').empty();
            $('#waitingMessages').empty();    
                    
            $.ajax({
                
                type: 'POST',
                url: 'function.php', // Function Page For All ajax Function
                data: { ServiceDetailsID: currentServiceDetailsID},
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
                    //fillServiceTeamTable(data);
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
                error: function () {
                    alert('Error fetching users');
                }
            });
        
    }

    ///////////////////////////////////////////***************** Service Page Start  *************************/////////////////////////////////////////








    ///////////////////////////////////////////***************** Team Member Page Start  *************************/////////////////////////////////////////

    var UserSessionID = $('#UserSessionID').val();

    $('#TeamName').on('change', function () {     // Call fillTeamInfo Function 
        fillTeamInfo();
    });

    function fillTeamInfo() {              //  Retrive Team Information Based On Team Number Function
        $('#TeamNoID').val($('#TeamName').val()); // Return  Team ID Based On Team Name From DB Using Select Option
        $('#EditTeamID').val($('#TeamName').val());
        $('#TeamMemberBodyTable').text("Waiting Data");
        $('#DelegateMemberHeadTable').text("Waiting Data");
        $('#waitingTeamMemberInfo').empty().removeClass('mt-5');
        $('#waitingDelegateMember').empty().removeClass('mt-5');
        $('#status').prop('checked', false);
        $('#dept').val(" ");
        $('#depID').val(" ");
        $('#GetDeptID').val(" ");
        $('#branch').val(" ");

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
            <button class="btn btn-primary" data-bs-toggle='modal' data-bs-target="#NewTeam" data-bs-whatever="NewTeam" data-bs-toggle='tooltip' data-bs-placement='top' title='Add New Team'>
                <i class="fa-solid fa-plus"></i>
                <span>Create New</span>
            </button>
            <button class="btn btn-success" data-bs-toggle='modal' data-bs-target="#EditTeam" data-bs-whatever="EditTeam" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Team'>
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Update</span>
            </button>
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
            data: { teamInfo: $('#TeamName').val() },
            dataType: 'json',
            success: function (data) {

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

                $('#status').prop('checked', data.TEAM_ACTIVE === 'Y');
                $('#dept').val(data.EMP_DEPARTMENT);
                $('#depID').val(data.DEPT_ID);
                $('#GetDeptID').val(data.DEPT_ID);
                $('#branch').val(data.BRANCH_CODE);
                $('#teamDescription').val(data.TEAM_DESCRIPTION);
                $('#EditTeamStatus').prop('checked', data.TEAM_ACTIVE === 'Y');
                $('#EditTeamDepartmentID').val(data.DEPT_ID);
                $('#EditTeamBranchCode').val(data.BRANCH_CODE);
                $('#EditTeamDescription').val(data.TEAM_DESCRIPTION);
                $('#EditTeamName').val($('#TeamName').find('option:selected').text());
            },
            error: function () {
                alert('Error fetching Team Information!!!');
            }
        });
    }

    $('#TeamName').on('change', function () {     // Call fillTable Function
        fillTable();
    });

    function fillTable() {              //  Retrive Team Member Information Based On Team Number Function
        $('#GetMemberName').empty();
        $('#TeamMemberBodyTable').empty();
        $('#TeamMemberBodyTable').text("Waiting Data");
        $('#GetTeamName').val($('#TeamName').find('option:selected').text());
        $('#GetTeamID').val($('#TeamName').val());
        
          // Parse the returned JSON data
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { teamMember: $('#TeamName').val() },
            success: function (data) {

                var tableBody = $('#TeamMemberBodyTable');
                // Clear existing rows
                tableBody.empty();

                var jsonData = JSON.parse(data);

                jsonData.forEach(function (row) {
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
                    tableBody.append(newRow);

                    $.ajax({
                        type: 'POST',
                        url: 'function.php',
                        data: { 
                            GetMember: $('#GetDeptID').val(), 
                            GetTeam: $('#TeamName').val() 
                        },
                        success: function (data) { 
                            $('#GetMemberName').html(data)
                        },
                        error: function (data) { 
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Error fetching Members",
                            });
                        }
            
                    });
                });
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Error fetching Team Information",
                    });
                }
        });
    }

    $('#TeamName').on('change', function () {     // Return Delegated Users Based On Team Number Function
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { delegateTeamMember: $(this).val() },
            success: function (data) {
                // Parse the returned JSON data
                
            var tableDBody = $('#DelegateMemberBodyTable');
            var jsonData = JSON.parse(data);
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
                    error: function () {
                        alert('Error fetching users');
                    }
        });
    });

    function fillDTable(data) {             //  This function is not used now 10JAN2024-- Display Delegated Users Based On Team Number Function
        var tableDBody = $('#tableBody');
    
        // Clear existing rows
        tableDBody.empty();
    
        data.forEach(function (row) {
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
    }

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
            
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "NewTeamName":              $(this).closest('.content').find('#NewTeamName').val(),
                    "branchCode":               $(this).closest('.content').find('#branchCode').val(),
                    "description":              $(this).closest('.content').find('#description').val(),
                    "departmentID":             $(this).closest('.content').find('#departmentID').val(),
                    "UserSessionID":            UserSessionID,
                    "action" :                  "NewTeam"
                },
                success: function (response) {
                        $('#NewTeam').modal('hide');
                        Swal.fire("Team Added Successfully ");
                        $('#TeamName').html(response);
                        $(this).closest('.content').find('#NewTeamName').val(" ");
                        $(this).closest('.content').find('#branchCode').val(" ");
                        $(this).closest('.content').find('#description').val(" ");
                        $(this).closest('.content').find('#departmentID').val(" ");
                },
                error: function () {
                        $('#NewTeam').modal('hide');
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "This Team Name Is Already Exist!",
                        });
                        $('#TeamName').html(response);
                        $(this).closest('.content').find('#NewTeamName').val(" ");
                        $(this).closest('.content').find('#branchCode').val(" ");
                        $(this).closest('.content').find('#description').val(" ");
                        $(this).closest('.content').find('#departmentID').val(" ");
                }
            });
        }
    });

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
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    "EditTeamID":                   $(this).closest('.content').find('#EditTeamID').val(),
                    "UserSessionID":                UserSessionID,
                    "EditTeamName":                 $(this).closest('.content').find('#EditTeamName').val(),
                    "EditTeamDescription":          $(this).closest('.content').find('#EditTeamDescription').val(),
                    "EditTeamBranchCode":           $(this).closest('.content').find('#EditTeamBranchCode').val(),
                    "EditTeamStatus":               $(this).closest('.content').find('#EditTeamStatus').prop('checked') ? 'Y' : 'N',
                    "EditTeamDepartmentID":         $(this).closest('.content').find('#EditTeamDepartmentID').val(),
                    "action" :                      "EditTeamInformation"
                },
                success: function (data) {
                    $('#EditTeamInformation').modal('hide');
                    $('#TeamName').html(data);
                    Swal.fire("Team Information Updated Successfully ");
                    
                    setTimeout(function() {
                        fillTeamInfo();
                    }, 0);
                },
                error: function () {
                    $('#EditTeamInformation').modal('hide');
                    $('#TeamName').html(data);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Error fetching Team Information",
                        });
                        
                        setTimeout(function() {
                            fillTeamInfo();
                        }, 0);
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
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    GetTeamID:                   $(this).closest('.content').find('#GetTeamID').val(),
                    UserSessionID:                UserSessionID,
                    GetMemberName:                 $(this).closest('.content').find('#GetMemberName').val(),
                    GetMemberDeacription:          $(this).closest('.content').find('#GetMemberDeacription').val()
                },
                success: function (data) {
                    $('#NewTeamMember').modal('hide');
                    $('#GetMemberName').empty();
                    $('#GetMemberDeacription').val(" ");
                    Swal.fire('Member Add To His Team Successfully');
                    setTimeout(function() {
                        fillTable();
                    }, 0);
                },
                error: function () {
                    $('#NewTeamMember').modal('hide');
                    $('#GetMemberName').empty();
                    $('#GetMemberDeacription').val(" ");
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Error Somthing Went Wrong",
                        });
                        setTimeout(function() {
                            fillTable();
                        }, 0);
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
        
        $.ajax({
                type: 'POST',
                url: 'function.php',
                dataType: 'json',
                data: { 
                    activeColumnJson:        activeColumnJson,
                    userID:                  UserSessionID,
                    action:                  "updateTeamMemberTable"
                },
                success: function (response) {
                    // Replace this Popup To normal popup 
                    Swal.fire("Team Member Updated Successfully"); 
                    setTimeout(function() {
                        fillTable();
                    }, 0);
                    
                },
                error: function (response) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text:  JSON.stringify(response),
                    });
                    setTimeout(function() {
                        fillTable();
                    }, 0);
                }
        });
    });


    ///////////////////////////////////////////***************** Team Member Page Start  *************************/////////////////////////////////////////



    ///////////////////////////////////////////***************** Ticket Transation Page Start  *************************/////////////////////////////////////////

    $(document).on('click', function () { // Hide context menu on click outside 
        $('.wrapper').css('visibility', 'hidden');
    });

    var UserSessionID = $('#UserSessionID').val(); // User Who Logged In To The System
    var USER_ID = 'USER_ID'; //  Add To Global Table To Fetch User Ticket Data 
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
        $('#returnTicketNumber').text($(this).find('td:first').text());
        $('#returnedTicketNumber').val($(this).find('td:first').text());
        $('#ticketActionNumber').text($(this).find('td:first').text());

        var UserRole = $('#UserRole').val();
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
                        <button class="item  startTicket" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Start Ticket'>
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
                        <button class="item  startTicket" style='margin-right: 5px;' data-bs-toggle='tooltip' data-bs-placement='top' title='Start Ticket'>
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
            // GM & Supervisor Permission 
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
            data: { selectDetailsTeamMember: serviceDetailsNo},
            success: function (data) {
                // Parse the JSON response
                var responseData = JSON.parse(data);

                // Update HTML elements based on IDs
                $('#ticketWeight').append(responseData.weights);
                $('#assignTeam').append(responseData.teams);
                $('#ticketPeriority').append(responseData.priorities);
                
            },
            error: function (data) {
                alert('Error fetching Ticket Information');
            }
        });
        
    });

    $('#assignTeam').on('change', function () {             // Retrive Team Member Based ON Team Choosen Function
        
        $('#teamMember').text("Loading ...");
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { teamMembers: $(this).val() },
            success: function (data) {
                // Call function to fill Service Deatails Table
                //fillServiceTable(data);
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
            error: function () {
                alert('Error fetching users');
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
                text: 'You Should Choose Member To Assigne This Ticket!',
            });
        } else {
            $.ajax({
                type: 'POST',
                url: 'function.php', // Function Page For All ajax Function
                data: { 
                    'ticketNumber':             ticketNumber,
                    "UserSessionID":            UserSessionID,
                    "ticketWeight":             $('#ticketWeight').val(),
                    "ticketPeriority":          $('#ticketPeriority').val(),
                    "memberAssigned":           jsonData,
                    "assignTeam":               $('#assignTeam').val(),
                    'action':                   'assignTicket'
                },
                success: function (response) {
                    $('#assignPopup').modal('hide');
                    Swal.fire("Ticket Assigned Successfully... ");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                    row.find('td:eq(4)').html('<span class="badge bg-warning">Assigned</span>');
                    $('#memberAssigned').empty();
                    $('#ticketWeight').val(" ");
                    $('#ticketPeriority').val(" ");
                    row.remove();
                    updateCounts();
                },
                error: function (response) {
                    $('#assignPopup').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(response),
                    });
                    $('#memberAssigned').empty();
                    $('#ticketWeight').val(" ");
                    $('#ticketPeriority').val(" ");
                }
            });
        }

        
        
    });

    $(document).on('click', '.startTicket', function(e) {  // Update Ticket Status To Start Ticket Function

        e.preventDefault();

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function ///cleab =
            data: {
                "tickid":               ticketNumber,
                "UserSessionID":        UserSessionID,
                "action" :              "start"
            },
            success: function (response) {
                    Swal.fire("Ticket Started Successfully... ");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                    row.find('td:eq(4)').html('<span class="badge bg-info">Started</span>');
                    row.remove();
                    updateCounts();
                },
                error: function (response) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(response),
                    });
                }
            
        });
    });

    $(document).on('click', '#change', function(e) {        // Retrive Ticket Information To Change Popup Function  
        e.preventDefault();

        $('#memberAssignedChange').text("Loading...");
        $('#teamMemberChange').text("Loading...");
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { teamMembersAssigned: ticketNumber },
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

            },
            error: function () {
                alert('Error fetching users');
            }
        });
        
    });

    $(document).on('click', '#change', function(e) {        // Retrive Ticket Information To Assign Popup Function  
        e.preventDefault();

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
            data: { selectDetailsTeamMember: serviceDetailsNo},
            success: function (data) {
                // Parse the JSON response
                var responseData = JSON.parse(data);

                // Update HTML elements based on IDs
                $('#ticketWeightChange').append(responseData.weights);
                $('#assignTeamChange').append(responseData.teams);
                $('#ticketPeriorityChange').append(responseData.priorities);
            },
            error: function (data) {
                alert('Error fetching Ticket Information');
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
            data: { teamMembers: $(this).val() },
            success: function (data) {
                // Call function to fill Service Deatails Table
                //fillServiceTable(data);
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
            error: function () {
                alert('Error fetching users');
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
            $.ajax({
                type: 'POST',
                url: 'function.php', // Function Page For All ajax Function
                data: { 
                    'ticketNumber':                      ticketNumber,
                    "UserSessionID":                     UserSessionID,
                    "ticketWeightChange":                $('#ticketWeightChange').val(),
                    "ticketPeriorityChange":             $('#ticketPeriorityChange').val(),
                    "memberAssignedChange":              jsonData,
                    "assignTeamChange":                  $('#assignTeamChange').val(),
                    'action':                            'assignTicketChange'
                },
                success: function (response) {
                    $('#changePopup').modal('hide');
                    Swal.fire("Assign Changed Successfully... ");
                    $('#memberAssignedChange').empty();
                    $('#ticketWeightChange').val(" ");
                    $('#ticketPeriorityChange').val(" ");
                    row.remove();
                },
                error: function (response) {
                    $('#changePopup').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(response),
                    });
                    $('#memberAssignedChange').empty();
                    $('#ticketWeightChange').val(" ");
                    $('#ticketPeriorityChange').val(" ");
                }
            });
        }
    });
    
    $(document).on('click', '#solveTicket', function(e) {  // Update Ticket Status To Solve Ticket Function

        e.preventDefault();
        
        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "tickid":               ticketNumber,
                "issue":                $('#issue').val(),
                "resolution":           $('#resolution').val(),
                "UserSessionID":        UserSessionID,
                "action" :              "solve"
            },
            success: function (response) {
                    $('#solvePopup').modal('hide');
                    Swal.fire("Ticket Solved Successfully");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                    row.find('td:eq(4)').html('<span class="badge bg-success">Solved</span>');
                    row.remove();
                    updateCounts();
            },
            error: function (response){
                $('#solvePopup').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(response),
                    });
            }
        });
    });

    $(document).on('click', '#cancelTicket', function(e) {  // Update Ticket Status To Cancele Ticket Function

        e.preventDefault();
        
        $.ajax({
            method: "POST",
            url: "function.php",
            data: {
                "tickid":               ticketNumber,
                "UserSessionID":        UserSessionID,
                "action" :              "cancel"
            },
            success: function (response) {
                    Swal.fire("Ticket Canceled Successfully");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                    row.find('td:eq(4)').html('<span class="badge bg-danger">Canceled</span>');
                    row.remove();
                    updateCounts();
            },
            error: function (response){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                });    
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
            data: { EditServiceType: serviceTypeNo, EditServiceDetails: serviceDetailsNo},
            success: function (data) {
                $('#EditServiceDetails').append(data);

            },
            error: function () {
                alert('Error fetching users');
            }
        });
        
    });

    $(document).on('click', '#UpdateTicketInformationButton', function(e) {  // Update Ticket Information 
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                UpdateTicketInformationButton:  $('#EditTicketNumber').val(),
                EditRequestedBy:                $('#EditRequestedBy').val(),
                EditrequestType:                $('#EditrequestType').val(),
                EditServiceDetails:             $('#EditServiceDetails').find('option:selected').text()
        },
            success: function (data) {
                $('#EditTicketPopup').modal('hide');
                Swal.fire("Ticket Information Updated Successfully ");
                var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                row.find('td:eq(2)').html('<span>' + $('#EditServiceDetails').find('option:selected').text() + '</span>');
            },
            error: function () {
                $('#EditTicketPopup').modal('hide');
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Somthing Went Wrong!!",
                });
            }
        });
        
    });

    $(document).on('click', '#ConfirmTicket', function(e) {     // Update Ticket Status To Confirme Ticket Function

        e.preventDefault();
            // Get values of individual inputs
            var returnedTicketNumber = $("#returnedTicketNumber").val();
            var evaluationDescription = $("#evaluation").val();
            // Get selected radio buttons
            var  responseTime = $("input[name='responseTime']:checked").val();
            var confirmSelection = $("input[name='confirmation']:checked").val();
            var technicianAttitude = $("input[name='technicianAttitude']:checked").val();
            var serviceEvaluation = $("#generalEvaluation").val();

            // Send AJAX request
            $.ajax({
                type: "POST",
                url: "function.php", // Replace with your PHP file handling the request
                data: {
                    "returnedTicketNumber":         returnedTicketNumber,
                    "evaluationDescription":        evaluationDescription,
                    "responseTime":                 responseTime,
                    "confirmSelection":             confirmSelection,
                    "technicianAttitude":           technicianAttitude,
                    "serviceEvaluation":            serviceEvaluation,
                    "UserSessionID":                UserSessionID,
                    "action":                       "confirm"
                },
                success: function(response){
                    $('#finishPopup').modal('hide');
                    Swal.fire("Ticket Confirmed Successfully");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + ticketNumber + ')').closest('tr');
                    row.find('td:eq(4)').html('<span class="badge bg-success">Confirmed</span>');
                    row.remove();
                    updateCounts();
                },
                error: function(error){
                    $('#finishPopup').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(error),
                    });
                }
            });
        
    });

    $(document).on('click', '#actionHistoryTable', function(e) {  // Retrive Ticket Action History 

        e.preventDefault();

        $('#ticketActionHistoryBodyTable').empty();
        $('#ticketActionHistoryBodyTable').text("Waiting Data");

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: { actionHistory:  $('#returnTicketNumber').text()},
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
            error: function(data) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(data),
                    });
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
                    console.log(data);

                    $('#timeDetails').empty();
                    var jsonData = JSON.parse(data);

                    console.log(jsonData);

                    var tableBody = $('#timeDetails');
                    
                    // Iterate over each key-value pair in the JSON object
                    for (var key in jsonData) {
                        if (jsonData.hasOwnProperty(key)) {
                            var row = $('<tr>');
                            row.append($('<td>').text(key)); // Key in the "Description" column
                            row.append($('<td>').text(jsonData[key])); // Value in the second column
                            tableBody.append(row);
                        }
                    }
                },
                error: function(data) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: JSON.stringify(data),
                        });
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

        if ($(this).find('td:nth-child(28)').text() === 'Y') {
            $('#EvaluationDetails').prop('checked', true);
            $('#hideEvaluation').append(`<h3 class="text-start mt-3 mb-4 text-dark">Evaluation</h3>
            <div class="row g-3" style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                <div class="col-sm-4">
                    <label class="" for="ResponsTimeDetails">Respons Time</label><br>
                    <span style="text-align: center;" id="ResponsTimeDetails" aria-label="City" title="" disabled readonly></span>
                </div>
                <div class="col-sm-4">
                    <label class="" for="TechnicianAttitudeDetails">Technician Attitude</label><br>
                    <span style="text-align: center;" id="TechnicianAttitudeDetails" aria-label="State" title="" disabled readonly></span>
                </div>
                <div class="col-sm-4">
                    <label class="" for="ServiceEvaluationInGeneralDetails">Service Evaluation In General</label>
                    <input type="text" class="form-control" id="ServiceEvaluationInGeneralDetails" aria-label="State" title="" disabled readonly>
                </div>
            </div>`);
        };

        if ($(this).find('td:nth-child(20)').text() === 'Assigned') {
            $('#hideTechnician').append(`<h3 class="text-start mt-2 mb-2 text-dark">Technician Information</h3>
            <div class="row " style=" border: #bcbaba 1px solid; padding: 10px; border-radius: 10px;">
                <div class="col-sm-6">
                    <label class="" for="TechnicianNameDetails">Name</label>
                    <input type="text" class="form-control" id="TechnicianNameDetails" aria-label="City" title="" disabled readonly>
                </div>
                <div class="col-sm-6">
                    <label class="" for="TechnicianDepartmentDetails">Department</label>
                    <input type="text" class="form-control" id="TechnicianDepartmentDetails" aria-label="State" title="" disabled readonly>
                </div>
                <div class="col-sm-6">
                    <label class="" for="TechnicianIssueDiscriptionDetails">Issue Discription</label>
                    <textarea type="text" class="form-control" id="TechnicianIssueDiscriptionDetails" title="" style="overflow: scroll; "></textarea>
                </div>
                <div class="col-sm-6">
                    <label class="" for="TechnicianIssueResolutionDetails">Issue Resolution</label>
                    <textarea type="text" class="form-control" id="TechnicianIssueResolutionDetails" title="" style="overflow: scroll; "></textarea>
                </div>
            </div>`);
        };

        

        $('#TicketNumberDetails').val($(this).find('td:first').text()).attr('title', $(this).find('td:first').text());
        $('#ServiceTypeDetails').val($(this).find('td:nth-child(2)').text()).attr('title', $(this).find('td:nth-child(2)').text());
        $('#ServiceDetailsDetails').val($(this).find('td:nth-child(3)').text()).attr('title', $(this).find('td:nth-child(3)').text());
        $('#TicketPeriorityDetails').val($(this).find('td:nth-child(4)').text()).attr('title', $(this).find('td:nth-child(4)').text());
        $('#TicketStatusDetails').val($(this).find('td:nth-child(20)').text()).attr('title', $(this).find('td:nth-child(20)').text());
        $('#BranchCodeDetails').val($(this).find('td:nth-child(15)').text()).attr('title', $(this).find('td:nth-child(15)').text());
        $('#StartDateDetails').val($(this).find('td:nth-child(14)').text()).attr('title', $(this).find('td:nth-child(14)').text());
        $('#EndDateDetails').val($(this).find('td:nth-child(17)').text()).attr('title', $(this).find('td:nth-child(17)').text());
        $('#ITTotaleTimeDetails').val($(this).find('td:nth-child(18)').text()).attr('title', $(this).find('td:nth-child(18)').text());
        $('#RequestorNameDetails').val($(this).find('td:nth-child(21)').text()).attr('title', $(this).find('td:nth-child(21)').text());
        $('#RequestorDepartmentDetails').val($(this).find('td:nth-child(13)').text()).attr('title', $(this).find('td:nth-child(23)').text());
        $('#RequestorEmailDetails').val($(this).find('td:nth-child(22)').text()).attr('title', $(this).find('td:nth-child(22)').text());
        $('#RequestorIssueDiscriptionDetails').val($(this).find('td:nth-child(9)').text()).attr('title', $(this).find('td:nth-child(9)').text());
        $('#TechnicianNameDetails').val($(this).find('td:nth-child(16)').text()).attr('title', $(this).find('td:nth-child(16)').text());
        $('#TechnicianDepartmentDetails').val($(this).find('td:nth-child(23)').text()).attr('title', $(this).find('td:nth-child(13)').text());
        $('#TechnicianIssueDiscriptionDetails').val($(this).find('td:nth-child(10)').text()).attr('title', $(this).find('td:nth-child(10)').text());
        $('#TechnicianIssueResolutionDetails').val($(this).find('td:nth-child(11)').text()).attr('title', $(this).find('td:nth-child(11)').text());
        $('#ResponsTimeDetails').html(emojiRespons).attr('title', $(this).find('td:nth-child(24)').text());
        $('#TechnicianAttitudeDetails').html(emojiAttetude).attr('title', $(this).find('td:nth-child(25)').text());
        $('#ServiceEvaluationInGeneralDetails').val($(this).find('td:nth-child(26)').text()).attr('title', $(this).find('td:nth-child(26)').text());
        $('#RequestorCommentDetails').val($(this).find('td:nth-child(27)').text()).attr('title', $(this).find('td:nth-child(27)').text());
    });

    $(document).on('click', '#SaveTicketDetailsInformation', function(e) {     // Update Ticket Status To Confirme Ticket Function

        e.preventDefault();
            // Get values of individual inputs
    
            $.ajax({
                type: "POST",
                url: "function.php", // Replace with your PHP file handling the request
                data: {
                    "TicketNumberDetails":                      $('#TicketNumberDetails').val(),
                    "TechnicianIssueDiscriptionDetails":        $('#TechnicianIssueDiscriptionDetails').val(),
                    "TechnicianIssueResolutionDetails":         $('#TechnicianIssueResolutionDetails').val(),
                    "RequestorCommentDetails":                  $('#RequestorCommentDetails').val(),
                    "UserSessionID":                            UserSessionID,
                    "action":                                   "TicketDetailsInformation"
                },
                success: function(response){
                    $('#TicketDetailsPopup').modal('hide');
                    Swal.fire("Ticket Details Information Saved Successfully");
                    var row = $('#mainTableTicketTransation').find('td:contains(' + $('#TicketNumberDetails').val() + ')').closest('tr');
                    row.find('td:eq(10)').html($('#TechnicianIssueDiscriptionDetails').val());
                    row.find('td:eq(11)').html($('#TechnicianIssueResolutionDetails').val());
                    row.find('td:eq(27)').html($('#RequestorCommentDetails').val());
                },
                error: function(error){
                    $('#TicketDetailsPopup').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(error),
                    });
                }
            });
        
    });

    $(document).on('dblclick', '#activeUsers', function(e) {
        e.preventDefault();

        $('#TicketBehalfUserPopup').modal('show');
        $('#allEmployee').text("Loading...");
        $('#userSearch').val(' ');

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: { allUsers:  UserSessionID},
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
            error: function(data) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(data),
                    });
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
        $('#addTicket').closest('.content').find('#UserSessionID').val($(this).find('td:nth-child(6)').text());
    });

    $(document).on('click', '#sendMessage', function(e) {     // Update Ticket Status To Confirme Ticket Function

        e.preventDefault();
            // Get values of individual inputs
            $.ajax({
                type: "POST",
                url: "function.php", // Replace with your PHP file handling the request
                data: {
                    "messageFeild":                      $('#messageFeild').val(),
                    "UserSessionID":                     UserSessionID,
                    "ticketNumber":                         ticketNumber,
                    "action":                            "chatMessage"
                },
                success: function(response){
                    
                    var messageHTML = '<div class="message">';
                    messageHTML += '<p><strong> You </strong> (Just now) :  ' + $('#messageFeild').val() + '</p>';
                    messageHTML += '</div>';
                    $('#chatScreen').append(messageHTML);
                    $('#messageFeild').val(' ');
                },
                error: function(error){
                    $('#TicketDetailsPopup').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(error),
                    });
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
                error: function(error){
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: JSON.stringify(error),
                    });
                }
            });
        
    });

    

    ///////////////////////////////////////////***************** Ticket Transation Page End  *************************/////////////////////////////////////////




///////////////////////////////////////////***************** Add New Ticket Page Start  *************************/////////////////////////////////////////


$('#service').on('change', function () {    // Retrive Service Details Based On Service Type Function
    var selectedService = $(this).val(); // Service Type Number
    $.ajax({
        type: 'POST',
        url: 'function.php', // Function Page For All ajax Function
        data: { type: selectedService },
        success: function (data) {
            $('#details').empty();
            $('#details').append(`<option  value="">Select Service Details....</option>`);
            $('#details').append(data);
        },
        error: function () {
            alert('Error fetching users');
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
            'UserSessionID':    $(this).closest('.content').find('#UserSessionID').val(),
            'det':              'det'
        }, // Include both details and username
        success: function (data) {
            $('#device').append(data);
            if (data.trim() === 'empty[]') {
                $('#device').prop('disabled', true);
                $('#device').prop('required', false);
            } else {
                $('#device').prop('disabled', false);
                $('#device').append(`<option  value="">Select  Device....</option>`);
            }
        },
        error: function () {
            alert('Error fetching users');
        }
    });
});

$(document).on('click', '#CreateNewTicket', function(e) {         // Hide Ticket Transation Dropdown  List

    e.preventDefault();
    $('.tran').hide(100);
    
}); 

///////////////////////////////////////////***************** Add New Ticket Page End  *************************/////////////////////////////////////////







///////////////////////////////////////////***************** Delegate Page Start  *************************/////////////////////////////////////////

    $('#delegateTeam').on('change', function () {   // Return Delegated Users  Based On Team Number Function
        var teamName = $(this).val();  // Team Number
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { 
                teamInfoDelegate: teamName
            },
            success: function (data) {
                $('#delegateUser').empty();
                $('#delegateUser').append(`<option  value="">Select User ....</option>`);
                $('#delegateUser').append(data);
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error Fetching Users In This Team",
                });
            }
        });
    });

    $('#StartDate').change(function() {
        // Get the selected value of the Start Date input field
        var startDateValue = $(this).val();
        // Set the minimum date of the End Date input field to the selected value of the Start Date input field

        $('#EndDate').prop('disabled', false);
        $('#EndDate').attr('min', startDateValue);
    });

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
    
    $(document).on('click', '#DelegateNewUser', function(e) {         // Add New Delegate User Table Function

        e.preventDefault();
    
        if ( $("#DelegateForm").valid()){
        
            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: {
                    
                    "delegateTeam":             $(this).closest('.content').find('#delegateTeam').val(),
                    "delegateUser":             $(this).closest('.content').find('#delegateUser').val(),
                    "StartDate":                $(this).closest('.content').find('#StartDate').val(),
                    "EndDate":                  $(this).closest('.content').find('#EndDate').val(),
                    "UserSessionID":            UserSessionID,
                    "action" :                  "delegate"
                },
                success: function (response) {
                    Swal.fire("User Delegated Successfully");
                    $('#delegateTeam').val('');
                    $('#delegateUser').val('');
                    $('#StartDate').val('');
                    $('#EndDate').val('');
                },
                error: function(response) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: JSON.stringify(response),
                        });
                        $('#delegateTeam').val('');
                    $('#delegateUser').val('');
                    $('#StartDate').val('');
                    $('#EndDate').val('');
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
            data: { delegated: delegateUser },
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
            error: function () {
                alert('Error fetching users');
            }
        });
    });

///////////////////////////////////////////***************** Delegate Page End  *************************/////////////////////////////////////////



///////////////////////////////////////////***************** Change All Solved Ticket To Confirm Button Start  *************************/////////////////////////////////////////

    $('#UpdateAllSolveTicketToConfirm').on('click', function () {   // Change All Solved Ticket To Confirm

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { UserNameSession: $('#UserSessionName').val() },
            success: function (data) {
                $('.tran').hide(100);
                Swal.fire(" Tickets Confirmed Successfully ");
            },
            error: function () {
                $('.tran').hide(100);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Theres No Ticket To Confirmed It",
                });
            }
        });
    });

    ///////////////////////////////////////////***************** Change All Solved Ticket To Confirm Button End  *************************/////////////////////////////////////////

});












