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


    $(document).on('click', '.addTicket', function(e) {

        e.preventDefault();

        var details        =$(this).closest('.content').find('.details').val();
        var name        =$(this).closest('.content').find('.name').val();
        var description =$(this).closest('.content').find('.description').val();
        var service     =$(this).closest('.content').find('.service').val();
        var tags        =$(this).closest('.content').find('.tags').val();

        // alert(name + service + description + details + tags);
        
        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "name":             name,
                "details":          details,
                "description":      description,
                "service":          service,
                "tags":             tags,
                "action" :          "add"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    Swal.fire("Your Ticket Created Successfully!!!");
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

    $(document).on('click', '.completTicket', function(e) {

        e.preventDefault();

        var tickid              = $(this).val();
        var comment             =$(this).closest('.content').find('.comment').val();


        // alert( tickid );
        
        $.ajax({
            method: "POST",
            url: "handel.php",
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
            url: "handel.php",
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


    $(document).on('click', '.updateProfile', function(e) {

        e.preventDefault();

        var userid               = $(this).val();
        var userName             =$(this).closest('.content').find('.userName').val();
        var userNumber             =$(this).closest('.content').find('.userNumber').val();
        
        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "userid":        userid,
                "userName":        userName,
                "userNumber":        userNumber,
                "action" :      "updateProfile"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    Swal.fire("Profile Information Updated Successfully ");
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

    $('#service').on('change', function () {
        var selectedService = $(this).val();

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