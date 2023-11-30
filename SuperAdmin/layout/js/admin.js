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

    //  Assign Ticket To Team Member 

    $(document).on('click', '.assignTicket', function(e) {

        e.preventDefault();

        var tickid              = $(this).val();
        var department          =$(this).closest('.content').find('.department').val();
        var user                =$(this).closest('.content').find('.user').val();

        // alert(department + user  + tickid );
        
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
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
                
            }
        });
    });


    $('#department').on('change', function () {
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


    $(document).on('click', '.startTicket', function(e) {

        e.preventDefault();

        var tickid = $(this).val();

        // alert( tickid );
        
        $.ajax({
            method: "POST",
            url: "handel.php",
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

    $(document).on('click', '.solveTicket', function(e) {

        e.preventDefault();

        var tickid              = $(this).val();
        var comment             =$(this).closest('.content').find('.comment').val();
        
        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "tickid":        tickid,
                "comment":        comment,
                "action" :      "solve"
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



});

const toggler = document.querySelector(".btn");
toggler.addEventListener("click",function(){
    document.querySelector("#sidebar").classList.toggle("collapsed");
});

function restrictInput(event) {
    const input = event.target;
    input.value = input.value.replace(/[^0-9]/g, '');
}


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


