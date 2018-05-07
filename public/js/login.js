$(document).ready(function(){
    var password = '';
    var passwordIcon = '<span class="numberInput"></span>';
    
    $('.button').click( function() {
        var input = $(this).html().toString();
        password = password.concat(input);
        $('.passwordField').append(passwordIcon)
        console.log(password)
    });

    $('.removeBtn').click(function(){
        $('.passwordField').children().last().remove();
        $('.error').slideUp().addClass('hidden');

        password = password.slice(0,-1);
        console.log(password)
    });

    $('#login').click( function() { 
        let data = {
            'password': password, 
            "_token":  $('meta[name=csrf-token]').attr('content'),
        }
        $.post("/user/login", data, function(res){
            // console.log(res);
            var response = res.response; 
            console.log(response)
            if (response == 'success' ) {
                window.location.href = '/home';
            } else if (response == 'error'){

            }

            console.log(response, status); 
        }).fail(function(err) {
            var error = err.responseJSON;
            $('.error').slideDown('fast').removeClass('hidden');
            console.log(error.exception, error.message, error.line, error.file);
        });
    });

});


