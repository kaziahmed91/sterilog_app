$(document).ready(function() {
    
    $('input[name="daterange"]').daterangepicker(
    {
        locale: {
        format: 'YYYY-MM-DD'
        },
        startDate: '2018-03-10',
        endDate: '2018-4-30'
    }, 
    function(start, end, label) {
        console.log("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });

    $('#sterilizer').change(function () {
         var selectedCycleNum = $('#sterilizer option:selected').attr('data-cyclenum');
         $('#cycle_number').val(selectedCycleNum)
    })

    $('.log').click(function(e) {
        e.preventDefault()
        var sterilizer_id = $('#sterilizer option:selected').attr('id');
        var sterilizer = $('#sterilizer option:selected').val();
        var cycleNumber = $('#cycle_number').val();
        var comment = $('#comment').val();

        var cleaner_ids = $('.cleaners').children().find('span').map(function(e){
            return this.id;
        }).get();

        var num_printable = $('.cleaners').children().find('input').map(function() {
            return this.value;
        }).get();
        
        var selected_options = {}
        cleaner_ids.forEach(function(cleaner_id, i ) {
            selected_options[cleaner_id] = num_printable[i];
        })
        
        var postData = { 
            "_token":  $('meta[name=csrf-token]').attr('content'),
            'data': selected_options, 
            'sterilizer_id': sterilizer_id,
            'sterilizer': sterilizer,  
            'cycle_number': cycleNumber, 
            'comment': comment
        }

        $.post('/sterilize', postData, function(res){
            var response = res.response;
            var printFiles = res.printFiles
            var filepaths = res.filepaths;
            if (response == 'success' ){
                console.log(printFiles, filepaths);
                printData(printFiles, filepaths);
            }
            console.log(response, status); 
        }).fail(function(a,b,c,d){
            console.log(a,b,c,d)
        });
    });



    var package, operator, cycle_number, units_printed, date, cycle_id;

    $('tr').click(function(){
        operator = $(this).find('.user_name').html();
        package = $(this).find('.package').html();
        cycle_number = $(this).find('.cycle_number').html();
        units_printed = $(this).find('.units_printed').html();
        date = $(this).find('.date').html();
        cycle_id = $(this).attr('data-cycleId')

        $('#package').text(package);
        $('#date').text(date);
        $('#operator').text(operator);
        $('#modal_header').text(package + ' ( '+units_printed +' units ) : Cycle #:' + cycle_number);
    });

    $('.addChanges').click(function(){
        var type1_sterile = $('#type_1').is(':checked') ? 1 : 0;
        var type4_sterile = $('#type_4').is(':checked')  ? 1 : 0;
        var type5_sterile = $('#type_5').is(':checked')  ? 1 : 0;
        var batch = $('#batch').is(':checked')  ? 1 : 0;
        var comments = $('.comments').val();
        var changeData = {
            "_token":  $('meta[name=csrf-token]').attr('content'),
            'type1': type1_sterile,
            'type4': type4_sterile,
            'type5': type5_sterile,
            'batch' : batch,
            'cycle_number': cycle_number,
            'cycle_id' : cycle_id,
            'comments': comments
        }
        console.log(changeData);

        $.post('/cycleChanges', changeData, function(response, status){
            if (response == 'success') {
                window.reload()
            }
            console.log(response, status); 
        });
    })

    $('#activeSterilizeModal').on('hidden.bs.modal', function (e) {
        $(".switch-input").prop('checked', false);
    });

    function printData(printFiles, filepaths) {
        
        var printData = {
            type: 'raw', 
            format: 'base64',
            data: file 
        }
        console.log('printing with config ', config, printData);

        qz.print(config, printData).then(function(){
            deletePdf(filepaths);
        }).catch(function(e) { console.log(e); });
       
    }

    function deletePdf(filepaths) {
        var postData = { 
            "_token":  $('meta[name=csrf-token]').attr('content'),
            "filepaths" : filepaths
        }
        console.log(postData)
        $.post('/deletePdf', postData, function (res){
            var response = res.response;
            if (response == 'success'){
                console.log('documents printed');
            }
        }).fail(function(a,b,c) {
            console.log(a,b,c);
        });
    }
});