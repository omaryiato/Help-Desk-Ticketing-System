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

        var name        =$(this).closest('.content').find('.name').val();
        var user        =$(this).closest('.content').find('.user').val();
        var description =$(this).closest('.content').find('.description').val();
        var department  =$(this).closest('.content').find('.department').val();
        var tags        =$(this).closest('.content').find('.tags').val();

        // alert(name + user + description + department + tags);
        
        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "name":         name,
                "user":         user,
                "description":  description,
                "department":   department,
                "tags":         tags,
                "action" : "add"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    alert('Your Ticket Created Successfully!!!');
                } else {
                    alert('Something Wrong Please Try Again Later...');
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.completTicket', function(e) {

        e.preventDefault();

        var tickid = $(this).val();

        // alert( tickid );
        
        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "tickid":        tickid,
                "action" :      "complete"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    alert('Ticket Completed Successfully ');
                } else {
                    alert('Something Wrong Please Try Again Later...');
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
                    alert('Ticket Deleted Successfully ');
                } else {
                    alert('Something Wrong Please Try Again Later...');
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