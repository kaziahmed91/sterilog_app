$(document).ready(function(){

    $('#addSporeTest').click(function() {
        var sterilizer_id = $('#sterilizer option:selected').attr('id');
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

        console.log(formData);
        
        $.post('/spore/new', formData, function(res) {
            var response = res.response;
            console.log('posted ',response)
            if (response == 'success' ){
                location.reload();
            }
            console.log(response, status); 
        }).fail(function(a,b,c,d){
            console.log(a,b,c,d)
        });
    })

    var cycle_id;
    $('tr').click(function(){
        cycle_id = $(this).attr('data-testId');
    });

    $('#updateSporeTest').click(function() {
        var control_sterile = $('#control_sterile').is(':checked') ? 1 : 0;
        var test_sterile = $('#test_sterile').is(':checked')  ? 1 : 0;
        var additional_comments = $('#additional_comments').val();
        var postData = {
            "_token":  $('meta[name=csrf-token]').attr('content'),
            'control_sterile': control_sterile, 
            'test_sterile': test_sterile, 
            'cycle_id' : cycle_id, 
            'additional_comments' : additional_comments
        }
        
        $.post('/spore/update', postData, function(res) {
            var response = res.response;
            console.log('posted ',response)
            if (response == 'success' ){
                location.reload();
            }
            console.log(response, status); 
        }).fail(function(a,b,c,d){
            console.log(a,b,c,d)
        });
    });

    $('#activeTestModal').on('hidden.bs.modal', function (e) {
        $(".switch-input").prop('checked', false);
    });

    $('#addSporeTestModal').on('hidden.bs.modal', function (e) {
        $(".switch-input").prop('checked', false);
    });

});