$(document).ready(function(){


    $('tr').click(function(){
        var e_date = $(this).find('.entryDt').html()
        var r_date = $(this).find('.removDt').html();
        var e_operator = $(this).find('.entryUser').html();
        var r_operator = $(this).find('.removUser').html();
        var sterilizer = $(this).find('.sterilizer').html();
        var e_comment = $(this).find('.entComm').html();
        var r_comment = $(this).find('.removComm').html();
        var lotNum = $(this).find('.lot').html();
        var cycleNum = $(this).find('.cycle').html();
        var control = $(this).find('.control').html();
        var test = $(this).find('.test').html();

        console.log(e_comment,r_comment,e_date, r_date, lotNum,cycleNum, test, control)

        
        $('#entry_date').text(e_date);
        $('#removal_date').text(r_date);
        $('#ent_operator').text(e_operator);
        $('#remov_operator').text(r_operator);
        $('#ent_comment').text(e_comment);
        $('#remov_comment').text(r_comment);
        $('#lot_num').text(lotNum);
        $('#cycle_num').text(cycleNum)
        $('#control').text(control);
        $('#test').text(test);



    })

})