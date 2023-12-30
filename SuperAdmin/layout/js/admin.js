const exampleModal = document.getElementById('exampleModal')
if (exampleModal) {
  exampleModal.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const button = event.relatedTarget
    // Extract info from data-bs-* attributes
    const recipient = button.getAttribute('data-bs-whatever')
    // If necessary, you could initiate an Ajax request here
    // and then do the updating in a callback.

    // Update the modal's content.
    const modalTitle = exampleModal.querySelector('.modal-title')
    const modalBodyInput = exampleModal.querySelector('.modal-body input')

    modalTitle.textContent = `New message to ${recipient}`
    modalBodyInput.value = recipient
  })
}

const tableRows = document.querySelectorAll(".main-table tbody tr");
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
            if (!event.target.closest(".main-table tbody tr")) {
                contextMenu.style.visibility = "hidden";
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

    // ***************** Ticket Actions Start  *************************

    $(document).on('click', '.assignTicket', function(e) {  //  Assign Ticket To Team Member 

        e.preventDefault();

        var tickid              = $(this).val(); // Ticket Number 
        var department          =$(this).closest('.content').find('.department').val();
        var user                =$(this).closest('tr').find('.user').text();
        
        // alert( tickid + user );

        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "user":         user,
                "department":   department,
                "tickid":       tickid,
                "action" :      "assign"
            },
            success: function (response) {
                if (response === 'done') {
                    Swal.fire("Ticket Assigned Successfully ");
                } else {
                    Swal.fire("Ticket Assigned Successfully ");
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Error',
                //         text: 'Ticket assignment failed. ' + response
                // })
                setTimeout(function () {
                    location.reload();
                }, 100);
                
            }}
        });
    });

    $(document).on('click', '#assign', function(e) {

        e.preventDefault();

        var TicketNumber = storedTicketNumber;  // Ticket Number

        const assignLink = document.querySelector(".assign");
                assignLink.href = `?action=Assign&tickid=${TicketNumber}`;

        // $.ajax({
        //     type: 'POST',
        //     url: 'dashboard.php', // Handel Page For All ajax Function
        //     data: { Number: TicketNumber },
        //     dataType: 'json',
        //     success: function (data) {
        //         $('#ticketNumber').val(data.TICKET_NO);
        //         $('#serviceType').val(data.REQUETS_TYPE_NO);
        //         $('#serviceDetail').val(data.SERVICE_DETAIL_NO);
        //     },
        //     error: function () {
        //         alert('Error fetching users');
        //     }
        // });
    });

    $('#assign').on('change', function () {      //  Return Team Member To Based On Team Number Function
        var selectedTeamMember = $(this).val(); // Team Number

        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
            data: { teamMember: selectedTeamMember },
            success: function (data) {
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
                // Call function to fill the table
                fillTeamMemberTable(jsonData);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    $('#department').on('change', function () { // Deleted Function
        var selectedDepartment = $(this).val();

        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
            data: { department: selectedDepartment },
            success: function (data) {
                $('#user').html(data);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    $(document).on('click', '.startTicket', function(e) {  // Start Ticket Function

        e.preventDefault();
        var tickid = storedTicketNumber;  // Ticket Number
        
        $.ajax({
            method: "POST",
            url: "handel.php",  // Handel Page For All ajax Function
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

    $(document).on('click', '.solveTicket', function(e) {  // Solve Ticket Function

        e.preventDefault();
        var tickid              = storedTicketNumber; // Ticket Number
        var issue               =$(this).closest('.content').find('.issue').val(); // Technition issue description
        var resolution          =$(this).closest('.content').find('.resolution').val();  // Technition solve description
        
        $.ajax({
            method: "POST",
            url: "handel.php",  // Handel Page For All ajax Function
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
            url: "handel.php",
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
            url: "handel.php",
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
            url: "handel.php",
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
            url: "handel.php",
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

    $(document).on('click', '.addService', function(e) {

        e.preventDefault();

        var name             =$(this).closest('.content').find('.name').val();
        var admin             =$(this).closest('.content').find('.admin').val();
        
        // alert(name + admin)

        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "name":        name,
                "admin":        admin,
                "action" :      "service"
            },
            success: function (response) {

                if (response.trim() === 'exist') {
                    Swal.fire("This Name already exist");
                } else if (response.trim() === 'success') {
                    Swal.fire("Service Added Successfully");
                    } else {
                        Swal.fire("Service Added Successfully");
                    }
                
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.updateService', function(e) {

        e.preventDefault();

        var serviceID              = $(this).val();
        var serviceName            =$(this).closest('.content').find('.serviceName').val();
        
        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "serviceID":           serviceID,
                "serviceName":         serviceName,
                "action" :         "editservice"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    Swal.fire("Service Information Updated Successfully ");
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

    $(document).on('click', '.deleteService', function(e) {

        e.preventDefault();

        var serviceid = $(this).val();

        // alert( tickid );
        
        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "serviceid":        serviceid,
                "action" :      "deleservice"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    Swal.fire("Service Deleted Successfully");
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

    $('.users').click(function() {

        $('.userno').toggle(100);
    });

    $('.trans').click(function() {

        $('.tran').toggle(100);
    });

    $(document).on('click', '.addTicket', function(e) {   // Add New Ticket Function

        e.preventDefault();
        var details         =$(this).closest('.content').find('.details').val();        // Service Details 
        var name            =$(this).closest('.content').find('.name').val();           // User Name who Create The Ticket
        var description     =$(this).closest('.content').find('.description').val();    // Ticket Issue Description
        var service         =$(this).closest('.content').find('.service').val();        // Service Type
        
        $.ajax({
            method: "POST",
            url: "handel.php",  // Handel Page For All ajax Function
            data: {
                "name":             name,
                "details":          details,
                "description":      description,
                "service":          service,
                "action" :          "add"
            },
            success: function (response) {
                if (response.trim() === 'done') {
                    Swal.fire("Your Ticket Created Successfully!!!");
                } else {
                    // Swal.fire({
                    //     icon: "error",
                    //     title: "Oops...",
                    //     text: "Something went wrong!",
                    // });
                    Swal.fire("Your Ticket Created Successfully!!!");
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.completTicket', function(e) {   // Confirme Ticket Function

        e.preventDefault();
        var tickid              = $(this).val(); // Ticket Number
        var comment             =$(this).closest('.content').find('.comment').val();  // User Evaluation
        
        $.ajax({
            method: "POST",
            url: "handel.php",  // Handel Page For All ajax Function
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
            url: "handel.php",  // Handel Page For All ajax Function
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

    $('#service').on('change', function () {    // Return Service Details Based On Service Type Function
        var selectedService = $(this).val(); // Service Type Number

        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
            data: { type: selectedService },
            success: function (data) {
                $('#details').html(data);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    $('#teamno').on('change', function () {     // Return Team Information  Based On Team Number Function
        var selectedTeam = $(this).val();  // Team Number

        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
            data: { team: selectedTeam },
            dataType: 'json',
            success: function (data) {
                $('#name').val(data.TEAM_NAME);
                $('#status').prop('checked', data.ACTIVE === 'Y');
                $('#dept').val(data.DEPT_ID);
                $('#branch').val(data.BRANCH_CODE);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    $('#teamno').on('change', function () {     // Return Team Member Information  Based On Team Number Function
        var selectedTeamMember = $(this).val(); // Team Number

        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
            data: { member: selectedTeamMember },
            success: function (data) {
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
                
                // Call function to fill the table
                fillTable(jsonData);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    $('#teamno').on('change', function () {     // Return Delegated Users Based On Team Number Function
        var delegateUser = $(this).val(); // Team Member 
        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
            data: { delegate: delegateUser },
            success: function (data) {
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
                // Call function to fill the table
                fillDTable(jsonData);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    $('#delegate').on('change', function () {   // Return Delegated Users  Based On Team Number Function
        var delegateUser = $(this).val();  // Team Number
        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
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

    $('#serviceno').on('change', function () {  // Return Service Name  Based On Service Number Function
        var selectedService = $(this).val();  // Service Number

        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
            data: { service: selectedService },
            success: function (data) {
                $('#name').val(data);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    $('#serviceno').on('change', function () {  // Return Service Details Information  Based On Service Number Function
        var serviceDetails = $(this).val();  // Service Number
        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
            data: { details: serviceDetails },
            success: function (data) {
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
                // Call function to fill the table
                fillServiceTable(jsonData);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    $('#serviceno').on('change', function () {  // Return Service Team  Information  Based On Service Number Function
        var serviceTeam = $(this).val();  // Service Number
        $.ajax({
            type: 'POST',
            url: 'handel.php', // Handel Page For All ajax Function
            data: { serviceTeam: serviceTeam },
            success: function (data) {
                // Parse the returned JSON data
                var jsonData = JSON.parse(data);
                // Call function to fill the table
                fillServiceTeamTable(jsonData);
            },
            error: function () {
                alert('Error fetching users');
            }
        });
    });

    

    // ***************** Ticket Actions End  *************************

});


function fillTable(data) {              // Dispaly Team Member Information Based On Team Number Function
    var tableBody = $('#table-body');

    // Clear existing rows
    tableBody.empty();

    data.forEach(function (row) {
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

function fillDTable(data) {             // Display Delegated Users Based On Team Number Function
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

function fillServiceTable(data) {       // Display Service Details Information Based On Service Number Function
    var tableDBody = $('#serviceDetails');

    // Clear existing rows
    tableDBody.empty();

    data.forEach(function (row) {
        var newDRow = $('<tr>');

        // Populate each cell with data
        newDRow.html(`
            <td>${row.name}</td>
            <td>${row.desc}</td>`

            // Check Custody and Private conditions
            + (row.custody === 'Y' ? `
                <td>
                    <div class='check'>
                        <input type='checkbox' ${row.custody === 'Y' ? 'checked' : ''} disabled>
                    </div>
                </td>` :  `
                <td>
                    <div class='check'>
                        <input type='checkbox' disabled>
                    </div>
                </td>`)

                + (row.private === 'Y' ? `
                <td>
                    <div class='check'>
                        <input type='checkbox' ${row.private === 'Y' ? 'checked' : ''} disabled>
                    </div>
                </td>` :  `
                <td>
                    <div class='check'>
                        <input type='checkbox'  disabled>
                    </div>
                </td>` )
                );

        // Append the new row to the table body
        tableDBody.append(newDRow);
    });
}

function fillServiceTeamTable(data) {   // Display Service Team Information Based On Service Number Function
    var tableDBody = $('#serviceTeam');

    // Clear existing rows
    tableDBody.empty();

    data.forEach(function (row) {
        var newDRow = $('<tr>');

        // Populate each cell with data
        newDRow.html(`
            <td>${row.name}</td>`
            // Check Custody and Private conditions
            + (row.enable === 'Y' ? `
                <td>
                    <div class='check'>
                        <input type='checkbox' ${row.enable === 'Y' ? 'checked' : ''} disabled>
                    </div>
                </td>` :  `
                <td>
                    <div class='check'>
                        <input type='checkbox' disabled>
                    </div>
                </td>`)
                );

        // Append the new row to the table body
        tableDBody.append(newDRow);
    });
}

function fillTeamMemberTable(data) {   // Display Service Team Information Based On Service Number Function
    var tableDBody = $('#teamMember');

    // Clear existing rows
    tableDBody.empty();

    data.forEach(function (row) {
        var newDRow = $('<tr>');

        // Populate each cell with data
        newDRow.html(`
            <td>${row.name}</td>
            <td>${row.Ename}</td>`
            // Check Active conditions
            + (row.active === 'Y' ? `
                <td>
                    <div class='check'>
                        <input type='checkbox' ${row.active === 'Y' ? 'checked' : ''} disabled>
                    </div>
                </td>` :  `
                <td>
                    <div class='check'>
                        <input type='checkbox' disabled>
                    </div>
                </td>`
            )
            // Add button to the last td
            + `<td>
                <button type='submit' class='btn btn-warning btn-lg mt-3 assignTicket' name='assignTicket'>Include</button>
            </td>`
        );

        // Append the new row to the table body
        tableDBody.append(newDRow);
    });
}








