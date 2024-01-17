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


// Select Row on Click Function
document.addEventListener('DOMContentLoaded', function() {
    var table = document.querySelector('.scroll table');

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
});

document.addEventListener('DOMContentLoaded', function() {
    var table = document.querySelector('.details .detailsTable');

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
});

// List Ticket Action 
document.addEventListener("DOMContentLoaded", function() {
    // Check if the current page URL contains a specific string
    if (window.location.href.includes("dashboard.php")) {
        // Your code for the specific page
        const tableRows = document.querySelectorAll(".hiddenList tbody tr");
        const contextMenu = document.querySelector(".wrapper");

        tableRows.forEach(row => {
            row.addEventListener("contextmenu", function(event) {
                event.preventDefault();

                const ticketNumber = row.querySelector("td").textContent;

                // Show the context menu near the click position
                contextMenu.style.visibility = "visible";
                contextMenu.style.left = event.pageX + "px";
                contextMenu.style.top = event.pageY + "px";
                storedTicketNumber = ticketNumber;
            });
        });

        // Hide the context menu when clicking outside of it
        document.addEventListener("click", function(event) {
            if (!event.target.closest(".hiddenList tbody tr")) {
                contextMenu.style.visibility = "hidden";
            }
        });
    }
});


$(function () {

    'use strict';

    // Hide Placeholder On Form Focus

    //  // Include button click event for moving rows from teamMember to memberAssigned
    // $('#teamMember').on('click', '.include', function() {
    //     moveRow($(this), '#teamMember', '#memberAssigned');
    // });

    // // Exclude button click event for moving rows from memberAssigned back to teamMember
    // $('#memberAssigned').on('click', '.exclude', function() {
    //     moveRow($(this), '#memberAssigned', '#teamMember');
    // });

    // function moveRow(clickedButton, sourceTable, destinationTable) {
    //     // Get the entire row
    //     var row = clickedButton.closest('tr');

    //     // Create a new row for the destination table
    //     var newRow = $('<tr>');

    //     // Find the specific columns in the clicked row and clone them
    //     var userNameColumn = row.find('.userName').clone();
    //     var nameColumn = row.find('.name').clone();
    //     var statusColumn = row.find('td:eq(2)').clone(); // Assuming the status is at index 2
    //     var teamLeaderColumn = $('<td><div class="check"><input type="checkbox"></div></td>');

    //     // Find the "Control" column and clone it
    //     var controlColumn = clickedButton.clone();

    //     // Toggle class and change text based on the action
    //     controlColumn.toggleClass('include exclude').text(controlColumn.hasClass('include') ? 'Exclude' : 'Include');

    //     // Append the cloned columns to the new row in the desired order
    //     newRow.append(userNameColumn, nameColumn, statusColumn, teamLeaderColumn, controlColumn);

    //     // Append the new row to the destination table
    //     $(destinationTable).append(newRow);

    //     // Remove the original row from the source table
    //     row.remove();
    // }

    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');

    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    })

    // Add Asterisk On Required Field
    // $('input').each(function () {
    //     if ($(this).attr('required') === 'required') { 
    //         $(this).after('<span class="asterisk">*</span>');
    //     }
    // });

    // Confirmation Message On Button
    $('.confirm').click(function() {
        return confirm('Are You Sure About Delete This Information !!!');
    });

    // $('.users').click(function() {
    //     $('.userno').toggle(100);
    // });


    $(document).on('click', function(event) {
        // Check if the clicked element is not part of .users or .userno
        if (!$(event.target).closest('.users, .userno').length) {
            // Hide the .userno element
            $('.userno').hide(100);
        }
        
    });
    $('.users').click(function(event) {
        // Prevent the event from bubbling up to the document
        event.stopPropagation();
        // Toggle the .userno element
        $('.userno').toggle(100);
    });
    
    $(document).on('click', function(event) {
        // Check if the clicked element is not part of .users or .userno
        if (!$(event.target).closest('.trans, .tran').length) {
            // Hide the .userno element
            $('.tran').hide(100);
        }
    });
    $('.trans').click(function() {
        $('.tran').toggle(100);
    });










    /////////////////////////////////////////// ***************** Service Page Actions Start  *************************/////////////////////////////////////////

    $(document).on('click', '#AddNewService', function(e) {                     // Add New Service To Service Table Function

        e.preventDefault();

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "serviceName":              $(this).closest('.content').find('#NewServiceName').val(),
                "UserSessionID":            $(this).closest('.content').find('#UserSessionID').val(),
                "action" :                  "NewService"
            },
            success: function (response) {
                    $('#NewService').modal('hide');
                    Swal.fire("Service Added Successfully ");
                    $('#serviceLOV').html(response);
            },
            error: function () {
                    $('#NewService').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "This Service Name Is Already Exist!",
                    });
                    $('#serviceLOV').html(response);
            }
        });
    });

    $(document).on('click', '#AddNewServiceDetails', function(e) {              // Add New Service Details To Service Details Table Function

        e.preventDefault();

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "NewServiceDetailsName":            $(this).closest('.content').find('#NewServiceDetailsName').val(),
                "UserSessionID":                    $(this).closest('.content').find('#UserSessionID').val(),
                "GetServiceTypeID":                 $(this).closest('.content').find('#GetServiceTypeID').val(),
                "ServiceDetailsDescription":        $(this).closest('.content').find('#ServiceDetailsDescription').val(),
                "action" :                          "NewServiceDetails"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    $('#NewServiceDetailsName').empty();
                    $('#ServiceDetailsDescription').empty();
                    $('#NewServiceDetail').modal('hide');
                    Swal.fire("Service Details Added Successfully ");
                    
                } 

                if (response.trim() === 'wrong') {
                    $('#NewServiceDetailsName').empty();
                    $('#ServiceDetailsDescription').empty();
                    $('#NewServiceDetail').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "This Service Details Name Is Already Exist!",
                    });
                }
                setTimeout(function() {
                    fillServiceDetailsTable();
                }, 0);
            }
        });
    });

    $(document).on('click', '#AddNewServiceDetailsTeam', function(e) {          // Add New Service Details Team To Service Details Team Table Function

        e.preventDefault();

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "GetServiceDetailsName":                        $(this).closest('.content').find('#GetServiceDetailsName').val(),
                "GetServiceDetailsTeamNumber":                  $(this).closest('.content').find('#GetServiceDetailsTeamNumber').val(),
                "GetServiceDetailsID":                          $(this).closest('.content').find('#GetServiceDetailsID').val(),
                "UserSessionID":                                $(this).closest('.content').find('#UserSessionID').val(),
                "action" :                                      "NewServiceDetailsTeam"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    $('#NewDetailTeam').modal('hide');
                    Swal.fire("Service Details Team Added Successfully ");
                    
                } 

                if (response.trim() === 'wrong') {
                    $('#NewDetailTeam').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "This Service Details Name Is Already Exist!",
                    });
                }
                setTimeout(function() {
                    fillServiceDetailsTeamTable();
                }, 0);
            }
        });
    });

    $(document).on('click', '#UpdateServiceDetailsInfoButton', function(e) {    // Add New Service Details Information Into Service Details Table Function

        e.preventDefault();

        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "EditServiceDetailsName":           $(this).closest('.content').find('#EditServiceDetailsName').val(),
                "UserSessionID":                    $(this).closest('.content').find('#UserSessionID').val(),
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
    });

    $(document).on('click', '#updateServiceDetailsButton', function(e) {        //  Update Custody And Private Columns In Service Details Table Function

        e.preventDefault();
        var custodyColumn = [];
        $(':input[type="checkbox"].serviceDetailsCustody').each(function(i){
            var servaiceDetailsNo = $(this).val();
            var newStatus = this.checked ? 'Y' : 'N';

                // Assign data for each row to the object
                custodyColumn.push({
                    servaiceDetailsNo: servaiceDetailsNo,
                    newStatus: newStatus
                });
        });
        var custodyColumnJson = JSON.stringify(custodyColumn);
        
        var privateColumn = [];
        $(':input[type="checkbox"].serviceDetailsPrivate').each(function(i){
            var servaiceDetailsNo = $(this).val();
            var newStatus = this.checked ? 'Y' : 'N';

                // Assign data for each row to the object
                privateColumn.push({
                    servaiceDetailsNo: servaiceDetailsNo,
                    newStatus: newStatus
                });
        });
        var privateColumnJson = JSON.stringify(privateColumn);

        $.ajax({
                type: 'POST',
                url: 'function.php',
                dataType: 'json',
                data: { 
                    custodyColumnJson:  custodyColumnJson,
                    privateColumnJson:  privateColumnJson,
                    userID:             $('#UserSessionID').val(),
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
                        $('#serviceDetails').load('service.php #serviceDetails');
                    }, 0);
                }
            });
    });

    $(document).on('click', '#updateTeamEnabled', function(e) {                 //  Update Enabled Column In Team Table Function

        e.preventDefault();
        var teamEnabled = [];

        $(':input[type="checkbox"].teamTable').each(function(i){

            var teamNo = $(this).val();
            var newStatus = this.checked ? 'Y' : 'N';
            var serviceDetailsID = $(this).attr('id');

                // Assign data for each row to the object
                teamEnabled.push({
                    teamNo: teamNo,
                    newStatus: newStatus,
                    serviceDetailsID: serviceDetailsID
                });
        });
        var teamEnabledJson = JSON.stringify(teamEnabled);
        $.ajax({
                type: 'POST',
                url: 'function.php',
                dataType: 'json',
                data: { 
                    teamEnabled:    teamEnabledJson,
                    userID:         $('#UserSessionID').val(),
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

    var serviceId;
    // Handle right-click on table rows
    $('#ServiceDetailsID tbody').on('contextmenu', 'tr', function (e) {
        e.preventDefault();
        // Show context menu at the mouse position
        $('#service-list').css({
            display: 'block',
            left: e.pageX,
            top: e.pageY
        });
        serviceId = $(this).find('td:first').text();
    });

    // Hide context menu on click outside
    $(document).on('click', function () {
        $('#service-list').css('display', 'none');
        // $('.tran').css('display', 'none');
        // $('.userno').css('display', 'none');
    });

    // Handle menu item clicks
    $(document).on('click', '#editServiceDetailsButton',  function () {
        // Implement your logic for "Edit Service"
        $('#service-list').css('display', 'none');
        $('#EditServiceDetailsID').val(serviceId);

            $.ajax({
                method: "POST",
                url: "function.php",  // Function Page For All ajax Function
                data: { ServiceDetailsInformation: serviceId },
                dataType: 'json',
                success: function (data) {
                    $('#EditServiceDetailsName').val(data.SERVICE_DETAIL_NAME);
                    $('#EditServiceDetailsDescription').val(data.DESCRIPTION);
                    
                },
                error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Error fetching Service Details Information",
                        });
                }
                    
                        
            });
    });

    // ***************** Edit Service Details End  *************************

    $('#serviceLOV').on('change', function ()                           // Call fillServiceDetailsTable() Function 
    {  // Return Service Number  Based On Service Name Function
        fillServiceDetailsTable();
    });

    function fillServiceDetailsTable()                                  // Display Service Details Information Based On Service Type Number Function 
    {       // This function is not used now 4JAN2024-- Display Service Details Information Based On Service Number Function
        $('#ServiceID').val($('#serviceLOV').val()); // to retrive the selected ID into ServiceID text Box
        $('#serviceDetails').empty();
        $('#serviceDetails').text("Waiting Data");
        $('#serviceDetailsTeam').empty();
        $('#GetServiceTypeID').val($('#serviceLOV').val());
        $('#GetServiceTypeName').val($('#serviceLOV').find('option:selected').text());
        $('#waitingMessage').empty().removeClass('mt-5');
        // $('.change').text('There Is No Data You Can See It Yet.');
    
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

    function fillServiceDetailsTeamTable() {                            //  Display Service Team Information Based On Service Details Number Function
    
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

    $('#TeamName').on('change', function () {     // Return Team Information  Based On Team Number Function
        $('#TeamNoID').val($(this).val()); // Return  Team ID Based On Team Name From DB Using Select Option

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { teamInfo: $(this).val() },
            dataType: 'json',
            success: function (data) {
                $('#status').prop('checked', data.ACTIVE === 'Y');
                $('#dept').val(data.DEPT_ID);
                $('#branch').val(data.BRANCH_CODE);
                $('#description').val(data.DESCRIPTION);
            },
            error: function () {
                alert('Error fetching Team Information!!!');
            }
        });
    });

    $('#TeamName').on('change', function () {     // Return Team Member Information  Based On Team Number Function

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { member: $(this).val() },
            success: function (data) {
                // Parse the returned JSON data
                
                var tableBody = $('#table-body');

                var jsonData = JSON.parse(data);

                // Clear existing rows
                tableBody.empty();

                jsonData.forEach(function (row) {
                    var newRow = $('<tr>');
                    // Populate each cell with data
                    newRow.html(`
                        <td>${row.userName}</td>
                        <td>${row.name}</td>
                        <td>${row.description}</td>
                        <td>
                            <div class='check'>
                                <input type='checkbox' ${row.active === 'Y' ? 'checked' : ''}>
                            </div>
                        </td>`
                        // Check supervisor and manager conditions
                        + (row.supervisor == 3 ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.supervisor == 3 ? 'checked' : ''} disabled>
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' disabled>
                                </div>
                            </td>`)

                            + (row.manager == 1 ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.manager == 1 ? 'checked' : ''} disabled>
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox'  disabled>
                                </div>
                            </td>` )
                            
                            );

                    // Append the new row to the table body
                    tableBody.append(newRow);
                });
                        },
                        error: function () {
                            alert('Error fetching users');
                        }
                    });
    });

    $('#TeamName').on('change', function () {     // Return Delegated Users Based On Team Number Function
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { delegateMember: $(this).val() },
            success: function (data) {
                // Parse the returned JSON data
                
            var tableDBody = $('#tableBody');
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

    function fillTable(data) {              // This function is not used now 10JAN2024-- Dispaly Team Member Information Based On Team Number Function
        var tableBody = $('#table-body');
    
        var jsonData = JSON.parse(data);
    
        // Clear existing rows
        tableBody.empty();
    
        jsonData.forEach(function (row) {
            var newRow = $('<tr>');
            // Populate each cell with data
            newRow.html(`
                <td>${row.userName}</td>
                <td>${row.name}</td>
                <td>${row.description}</td>
                <td>
                    <div class='check'>
                        <input type='checkbox' ${row.active === 'Y' ? 'checked' : ''}>
                    </div>
                </td>`
                // Check supervisor and manager conditions
                + (row.supervisor == 3 ? `
                    <td>
                        <div class='check'>
                            <input type='checkbox' ${row.supervisor == 3 ? 'checked' : ''} disabled>
                        </div>
                    </td>` :  `
                    <td>
                        <div class='check'>
                            <input type='checkbox' disabled>
                        </div>
                    </td>`)
    
                    + (row.manager == 1 ? `
                    <td>
                        <div class='check'>
                            <input type='checkbox' ${row.manager == 1 ? 'checked' : ''} disabled>
                        </div>
                    </td>` :  `
                    <td>
                        <div class='check'>
                            <input type='checkbox'  disabled>
                        </div>
                    </td>` )
                    
                    );
    
            // Append the new row to the table body
            tableBody.append(newRow);
        });
    }
    
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

    ///////////////////////////////////////////***************** Team Member Page Start  *************************/////////////////////////////////////////










    ///////////////////////////////////////////***************** Ticket Transation Page Start  *************************/////////////////////////////////////////

    $(document).on('click', '.assign', function(e) {
        e.preventDefault();
        $('#RequestedBy').val(" ");
        $('#requestType').val(" ");
        $('#serviceFor').val(" ");
        $('#ticketNumber').val(" ");
        $('#assignTeam').html(" ");
        $('#teamMember').empty();

        $('#ticketNumber').val(storedTicketNumber);
        $.ajax({
            type: 'POST',
            url: 'function.php', // Replace with the correct URL
            data: { ticketNumber: storedTicketNumber },
            dataType: 'json',
            success: function (data) {
                // Fill the input fields based on the received data
                $('#RequestedBy').val(data.USER_EN_NAME);
                $('#requestType').val(data.SERVICE_NAME);
                $('#serviceFor').val(data.SERVICE_DETAIL_NAME);
                $.ajax({
                    type: 'POST',
                    url: 'function.php', // Function Page For All ajax Function
                    data: { selectDetailsTeamMember: data.SERVICE_DETAIL_NAME },
                    success: function (data) {
                        $('#assignTeam').html(data);
                    },
                    error: function () {
                        alert('Error fetching users');
                    }
                });
                
            },
            error: function () {
                alert('Error fetching ticket information');
            }
        });
    });

    $('#assignTeam').on('change', function () {             // Return Service Number  Based On Service Name Function
        $('#teamMember').text(" Waiting Data ");
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
            
                        <td>${row.name}</td>
                        <td>${row.Ename}</td>`
            
                        // Check Custody and Private conditions
                        + (row.active === 'Y' ? `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' ${row.active === 'Y' ? 'checked' : ''} >
                                </div>
                            </td>` :  `
                            <td>
                                <div class='check'>
                                    <input type='checkbox' >
                                </div>
                            </td>`)
                            +
                            `<td><button class='btn btn-warning include'>Include</button></td>`

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

    $(document).on('click', '.startTicket', function(e) {  // Update Ticket Status To Start Ticket Function

        e.preventDefault();
        var tickid = storedTicketNumber;  // Ticket Number
        
        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "tickid":        tickid,
                "action" :      "start"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    Swal.fire("Ticket Started... ");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.solveTicket', function(e) {  // Update Ticket Status To Solve Ticket Function

        e.preventDefault();
        var tickid              = storedTicketNumber; // Ticket Number
        var issue               =$(this).closest('.content').find('.issue').val(); // Technition issue description
        var resolution          =$(this).closest('.content').find('.resolution').val();  // Technition solve description
        
        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "tickid":           tickid,
                "issue":            issue,
                "resolution":       resolution,
                "action" :          "solve"
            },
            success: function (response) {
                if (response.trim() === 'done') {
                    Swal.fire("Ticket Solved Successfully");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.rejectTicket', function(e) {

        e.preventDefault();

        var tickid = $(this).val();

        // alert( tickid );
        
        $.ajax({
            method: "POST",
            url: "function.php",
            data: {
                "tickid":        tickid,
                "action" :      "reject"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    Swal.fire("Ticket Rejected Successfully");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.deleteUser', function(e) {

        e.preventDefault();

        var userid = $(this).val();

        // alert( tickid );
        
        $.ajax({
            method: "POST",
            url: "function.php",
            data: {
                "userid":        userid,
                "action" :      "remove"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    Swal.fire("User Deleted Successfully");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    }); 
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.addUsers', function(e) {

        e.preventDefault();

        var username          =$(this).closest('.content').find('.username').val();
        var password          =$(this).closest('.content').find('.password').val();
        var email             =$(this).closest('.content').find('.email').val();
        var usertype          =$(this).closest('.content').find('.usertype').val();
        var phone             =$(this).closest('.content').find('.phone').val();
        var admin             =$(this).closest('.content').find('.admin').val();
        var userStatus        =$(this).closest('.content').find('.userStatus').val();

        // alert( username +  password + email + department + usertype);
        
        $.ajax({
            method: "POST",
            url: "function.php",
            data: {
                "username":        username,
                "password":        password,
                "email":           email,
                "userStatus":      userStatus,
                "usertype":        usertype,
                "phone":           phone,
                "admin":           admin,
                "action" :         "new"
            },
            success: function (response) {

                if (response.trim() === 'exist') {
                    alert('This username already exist ');
                } else if (response.trim() === 'success') {
                        Swal.fire("User Added Successfully ");
                    } else {
                        Swal.fire("User Added Successfully ");
                    }
                
                
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.updateTicket', function(e) {

        e.preventDefault();

        var userid              = $(this).val();
        var username            =$(this).closest('.content').find('.username').val();
        var email               =$(this).closest('.content').find('.email').val();
        var phone               =$(this).closest('.content').find('.phone').val();
        var usertype            =$(this).closest('.content').find('.usertype').val();

        // alert( userid + username + email + phone + usertype );
        
        $.ajax({
            method: "POST",
            url: "function.php",
            data: {
                "userid":           userid,
                "username":         username,
                "email":            email,
                "phone":            phone,
                "usertype":         usertype,
                "action" :         "edit"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    Swal.fire("User Information Updated Successfully ");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.completTicket', function(e) {     // Update Ticket Status To Confirme Ticket Function

        e.preventDefault();
        var tickid              = $(this).val(); // Ticket Number
        var comment             =$(this).closest('.content').find('.comment').val();  // User Evaluation
        
        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "tickid":        tickid,
                "comment":        comment,
                "action" :      "complete"
            },
            success: function (response) {
                if (response.trim() === 'done') {
                    Swal.fire("Ticket Completed Successfully");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.deleteTicket', function(e) {

        e.preventDefault();

        var tickid = $(this).val();

        // alert( tickid );
        
        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "tickid":        tickid,
                "action" :      "delete"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    Swal.fire("Ticket Deleted Successfully ");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    ///////////////////////////////////////////***************** Ticket Transation Page End  *************************/////////////////////////////////////////








    ///////////////////////////////////////////***************** Add New Ticket Page Start  *************************/////////////////////////////////////////


    $('#service').on('change', function () {    // Return Service Details Based On Service Type Function
        var selectedService = $(this).val(); // Service Type Number

        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { type: selectedService },
            success: function (data) {
                $('#details').html(data);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    $('#details').on('change', function () {    // Return Device Number Based On Service Details If its value custody Function
        
        // Check if selectedDetails is '14' and update #device field
                if ($(this).val() === '14') {
                    $.ajax({
                        type: 'POST',
                        url: 'function.php', // Handle Page For All AJAX Function
                        data: { details: $(this).val(), username: $(this).closest('.content').find('.name').val() }, // Include both details and username
                        success: function (data) {
                            $('#device').html(data);
                        },
                        error: function () {
                            alert('Error fetching users');
                        }
                    });
                    $('#device').prop('required', true);
                } else {
                    $('#device').prop('required', false);
                }
    });

    $(document).on('click', '.addTicket', function(e) {         // Add New Ticket To Tickets Table Function

        e.preventDefault();
        var name            =$(this).closest('.content').find('.name').val();           // User Name who Create The Ticket
        var service         =$(this).closest('.content').find('.service').val();        // Service Type
        var details         =$(this).closest('.content').find('.details').val();        // Service Details 
        var device          =$(this).closest('.content').find('.device').val();        // Device Details 
        var description     =$(this).closest('.content').find('.description').val();    // Ticket Issue Description
        
        // alert(name + " " + service + " " + details + " " +  device + " " + description);
        
        $.ajax({
            method: "POST",
            url: "function.php",  // Function Page For All ajax Function
            data: {
                "name":             name,
                "service":          service,
                "details":          details,
                "description":      description,
                "device":           device,
                "action" :          "add"
            },
            success: function (response) {
                if (response.trim() === 'done') {
                    Swal.fire("Ticket #No " + response + " Created Successfully!!!");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response,
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    ///////////////////////////////////////////***************** Add New Ticket Page End  *************************/////////////////////////////////////////



    

    ///////////////////////////////////////////***************** Delegate Page Start  *************************/////////////////////////////////////////


    $('#delegate').on('change', function () {   // Return Delegated Users  Based On Team Number Function
        var delegateUser = $(this).val();  // Team Number
        $.ajax({
            type: 'POST',
            url: 'function.php', // Function Page For All ajax Function
            data: { delegated: delegateUser },
            success: function (data) {
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
                // Call function to fill the table
                fillDelegateTable(jsonData);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    function fillDelegateTable(data) {      // Display Delegated Users Based On Team Number Function In Delegate Supervisor Section
        var tableDBody = $('#delegateBody');
    
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

    ///////////////////////////////////////////***************** Delegate Page End  *************************/////////////////////////////////////////
    

});












