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
                    Swal.fire("Ticket Started ");
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


        // alert( tickid );
        
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
                    Swal.fire("Ticket Solved Successfully ");
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