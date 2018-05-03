$(document).ready(function(){
    var password = '';
    var passwordIcon = '<span class="numberInput"></span>';
    $('.button').click( function() {
        var input = $(this).html();
        password.concat(input);
        $('.passwordField').append(passwordIcon)
    })

});


