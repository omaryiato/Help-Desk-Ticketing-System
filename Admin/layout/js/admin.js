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
                    alert('Ticket Started ');
                } else {
                    alert('Something Wrong Please Try Again Later...');
                }
                setTimeout(function () {
                    location.reload();
                }, 100);
            }
        });
    });

    $(document).on('click', '.solveTicket', function(e) {

        e.preventDefault();

        var tickid = $(this).val();

        // alert( tickid );
        
        $.ajax({
            method: "POST",
            url: "handel.php",
            data: {
                "tickid":        tickid,
                "action" :      "solve"
            },
            success: function (response) {

                if (response.trim() === 'done') {
                    alert('Ticket Solved Successfully ');
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


