$(document).ready(function(){

    $('body').on('click', '.alert-close', function() {
        $(this).parent().addClass('hidden').find('ul').children().remove();
    });

    $('#closeNavBtn').click(function(){
        var icons = $(this).children();
        icons.each(function(icon, i){
            $(this).toggleClass('active');
        })
        $('.menulist').slideToggle();
    })


    $('input[name="daterange"]').daterangepicker(
    {
        locale: {
        format: 'YYYY-MM-DD'    
        },
        startDate: '2018-03-10',
        
    }, 
        function(start, end, label) {
            console.log("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        }
    );
    $('input[name="daterange"]').val('');
    $('input[name="daterange"]').attr("placeholder","Select Range");
    $('[data-disable-touch-keyboard]').attr('readonly', 'readonly');

    $('#sterilizer').select2({
        theme: "bootstrap",
        placeholder: 'Choose a Sterilizer'
    });

    $('#sterilizer').on("change", function(e) { 
        console.log(e.target);
        var cycle_num  = $('#sterilizer option:selected').data('cycleid');
        $('#cycle_number').val(cycle_num);
    });     

    $('#addSporeTest').click(function() {

        $('#addSporeTestModal').modal('hide');

        var sterilizer_id = $('#sterilizer option:selected').data('id');
        var cycleNumber = $('#cycle_number').val();
        var comment = $('#comments').val();
        var lot_number = $('#lot_number').val();

        var formData = {
            "_token":  $('meta[name=csrf-token]').attr('content'),
            'sterilizer_id' : sterilizer_id,
            'cycle_number' : cycleNumber, 
            'comment': comment, 
            'lot_number' : lot_number
        };
        if (cycleNumber === '' || lot_number === ''){
            var message = 'Cycle number & lot number cannot be empty!';
            window.scrollTo(0, 0);
            $('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>');
            return false;
        }

        console.log('posting')
        $.post('/spore/new', formData, function(res) {
            var response = res.response;
            var message = 'New Spore test has been added';
            var log = res.log
            if (response == 'success' ){
                $('tbody').prepend([
                    "<tr class='pointer' data-target='#activeTestModal'data-testId='" + log.id + "' data-toggle='modal'>",
                        '<td>'+ log.date +'</td>',
                        '<td>'+ log.time +'</td>',
                        '<td>'+ log.creator +'</td>',
                        '<td>'+ log.sterilizer +'</td>',
                        '<td>'+ log.cycle +'</td>',
                        '<td>'+ log.lot +'</td>',
                        '<td></td>',
                        '<td></td>',
                        '<td>'+ log.comment +'</td>',
                    '</tr>'
                ].join(''));
                
                // window.scrollTo(0, 0);
                $('html, body').animate({scrollTop: '0px'}, 500);
                $('.successMsg').removeClass('hidden').slideDown().find('ul').append('<li>'+message+'</li>')
                setTimeout(() => {
                    $('.successMsg').slideUp().addClass('hidden').find('ul').children().remove();
                }, 3000);
            } else if (response == 'error') {
                console.log(response)
                window.scrollTo(0, 0);
                $('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>')
            }
                console.log(response, status); 
            }).fail(function(err) {
                var error = err.responseJSON;
                console.log(error)
                var message = error.message; 
                window.scrollTo(0, 0);
                $('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>')
                console.log(error.exception, error.message, error.line, error.file); 
            });
        })


    $('#updateSporeTest').click(function() {
        var control_sterile = $('#control_sterile').is(':checked') ? 1 : 0;
        var test_sterile = $('#test_sterile').is(':checked')  ? 1 : 0;
        var additional_comments = $('#additional_comments_before').val();
        var postData = {
            "_token":  $('meta[name=csrf-token]').attr('content'),
            'control_sterile': control_sterile, 
            'test_sterile': test_sterile, 
            'cycle_id' : cycle_id, 
            'additional_comments' : additional_comments
        }
        console.log(postData);
        $('#activeTestModal').modal('hide');
        $.post('/spore/update', postData, function(res) {
            var response = res.response;
            var log = res.log;
            console.log('posted ',response)
            if (response == 'success' ){
                var message = 'Spore Test has been updated!';
                if (clicked_row) {
                    clicked_row.remove();
                }
                $('html, body').animate({scrollTop: '0px'}, 500);
                $('.successMsg').removeClass('hidden').slideDown().find('ul').append('<li>'+message+'</li>')
                setTimeout(() => {
                    $('.successMsg').slideUp().addClass('hidden').find('ul').children().remove();
                }, 3000);
            } else if (response == 'error'){
                console.log(response)
                window.scrollTo(0, 0);
                $('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>')
            }
            console.log(response, status); 
        }).fail(function(err) {
            var error = err.responseJSON;
            console.log(error)
            console.log(error.exception, error.message, error.line, error.file); 
            var message = error.message; 
            window.scrollTo(0, 0);
            $('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>')
            console.log(error.exception, error.message, error.line, error.file); 
        });
    });

    var cycle_id, clicked_row;
    $('tr.pointer').click(function() {
        clicked_row = $(this);
        var e_date = $(clicked_row).find('.entryDt').html()
        var r_date = $(clicked_row).find('.removDt').html();
        var e_operator = $(clicked_row).find('.entryUser').html();
        var r_operator = $(clicked_row).find('.removUser').html();
        var sterilizer = $(clicked_row).find('.sterilizer').html();
        var e_comment = $(clicked_row).find('.entComm span').html();
        var r_comment = $(clicked_row).find('.removComm span').html();
        var lotNum = $(clicked_row).find('.lot').html();
        var cycleNum = $(clicked_row).find('.cycle').html();
        var control = $(clicked_row).find('.control').html();
        var test = $(clicked_row).find('.test').html();
        cycle_id = $(clicked_row).attr('data-testid');
        console.log(cycle_id);

        $('#entry_date').text(e_date);
        $('#removal_date').text(r_date);
        $('#ent_operator').text(e_operator);
        $('#remov_operator').text(r_operator);
        $('#ent_comment').text(e_comment);
        $('#additional_comments_after').val(r_comment);
        $('#additional_comments_before').val(r_comment);
        $('#lot_num').text(lotNum);
        $('#cycle_num').text(cycleNum)
        $('#control').text(control);
        $('#test').text(test);

        clicked_row .addClass('highlightSelectedRow')
        var attr = $(this).attr('data-target');
        
        // // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
        // if (typeof attr !== typeof undefined && attr !== false  && attr === '#activeTestModal') {
        //     console.log('opening active test modal Modal')
        //     $('#activeTestModal').modal('show');
        // } else if (typeof attr !== typeof undefined && attr !== false && attr === '#completedTestModal') {
        //     $('#completedTestModal').modal('show');
        // }
    });

    $('#updateComments').click(function() {
        var comment = $('#additional_comments_after').val().trim();
        var data = {
            "_token":  $('meta[name=csrf-token]').attr('content'),
            'additional_comment': comment,
            'cycle_id': cycle_id
        }
        $.post('/spore/update/comment',data, function(res) {
            var response = res.response;

            if (response == 'success') {
                clicked_row.find('.removComm span').html(comment);
                $('.dismiss-modal').click();
                console.log('saved');
            }
            
        }).fail(function(a,b,c){
            console.log(a,b,c)
        });
    });
            

    $('#completedTestModal').on('hidden.bs.modal', function (e) {
        $(".switch-input").prop('checked', false);
        // if (typeof(cicked_row) !== 'undefined')  {

            clicked_row.removeClass('highlightSelectedRow');
        // }

    });
    $('#activeTestModal').on('hidden.bs.modal', function (e) {
        // if (typeof(cicked_row) !== 'undefined')  {
            clicked_row.removeClass('highlightSelectedRow');
            $(".switch-input").prop('checked', false);

        // }
    });
    
    $('#addSporeTestModal').on('hidden.bs.modal', function (e) {
        $(".switch-input").prop('checked', false);
        if (typeof(cicked_row) !== 'undefined')  {
            clicked_row.removeClass('highlightSelectedRow');
        }
    });
});