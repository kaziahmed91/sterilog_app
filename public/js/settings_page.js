$(document).ready(function() {

    $('.settingsNav').children().hover(function(){
        if (!$(this).hasClass('currentPage'))
            $(this).toggleClass('active');
    });

    $('.createUserBtn').click( function(e) {
        e.preventDefault();
        $(this).find('.btn').toggleClass('active'); 

        $('.addEqptBtn').find('.btn').removeClass('active');
        $('.viewUserBtn').find('.btn').removeClass('active');

        $('.addEqptForm').fadeOut('fast', function() {
            $('.createUserForm').fadeIn('fast')
        });  
    });

    $('.addEqptBtn').click(function(e) {
        e.preventDefault();
        $(this).find('.btn').toggleClass('active'); 

        $('.createUserBtn').find('.btn').removeClass('active');
        $('.viewUserBtn').find('.btn').removeClass('active');

        $('.createUserForm, .viewUsers').fadeOut('fast', function() {
            $('.addEqptForm').fadeIn('fast');
        });
    });

    $('.viewUsersBtn').click(function(e) {
        e.preventDefault();
        $(this).find('.btn').toggleClass('active'); 
        $('.addEqptBtn').find('.btn').removeClass('active');
        $('.addEqptBtn').find('.btn').removeClass('active');

        $('.createUserForm , .addEqptForm').fadeOut('fast', function() {
            $('.viewUsers').fadeIn('fast');
        });
    });

});

