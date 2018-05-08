$(document).ready(function() {

    $('.userNames').select2({
        theme: "bootstrap",

    });


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

    function deleteClickHandler (clickObject , link, payload  ) {

        var linkClicked = clickObject; // to access in ajax below.
		var currentText = $(clickObject).text();
        var newState 	= '1';
        console.log(currentText)
		if (currentText == 'Delete'){
			console.log('true')
			$(linkClicked).text('Confirm?');
			setTimeout(function(){ if ($(linkClicked).text() == 'Confirm?') $(linkClicked).text('Delete');},2000);
			return false;
		}
		else if (currentText == 'Confirm?'){
			// Continue with delete...
			$(linkClicked).text('Deleting...');
		}
		else if (currentText == 'Undo'){
			newState = '0';
			$(linkClicked).text('Undoing ...');
		}
		else { // if it's Deleting, Deleted, or whatever else, return false.
			return false;
		}

        payload['newState'] = newState;
		$.post( link , payload, function( res ) {

			// console.log(obj, newState);
			var response = res.response;

			if (response == 'success'){
				console.log('Data was accepted');
				// Change the interface
				if (newState == '1') { // Delete was done!
					$(linkClicked).text('Deleted');
					$(linkClicked).parent().css('opacity',0.3);
					setTimeout(function(){ $(linkClicked).text('Undo'); },1000);
				}
				else if (newState == '0'){ // Delete was undone!
					$(linkClicked).text('Recovered');
					$(linkClicked).parent().css('opacity',1);
					setTimeout(function(){ $(linkClicked).text('Delete'); },1000);
				}
			}
			else{
				console.log('Error: ' + obj['error']);
				// reflect error in interface
				$(linkClicked).text('Error!');
			}

		});

		return false;
    }

    $('.deleteCleaner').click(function(e)	{
		e.preventDefault();
        var cleanerId = $(this).attr('data-id');
        var link = '/settings/register/cleaners/remove';
        var data = { cleanerId:cleanerId, "_token":  $('meta[name=csrf-token]').attr('content') }
        
        deleteClickHandler( $(this), link, data );
    }); 
    
    $('.deleteSterilizer').click(function(e)	{
        e.preventDefault();
        var sterilizerId = $(this).attr('data-id');
        var link = '/settings/register/equiptment/remove';
        var data = { sterilizerId:sterilizerId, "_token":  $('meta[name=csrf-token]').attr('content') }
        
        deleteClickHandler($(this), link, data );
    }); 

});

