
$(document).ready(function() {

	$('body').on('click', '.alert-close', function() {
		$(this).parents('.alert').hide();
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
		startDate: '2018-04-10',
	}, 
		function(start, end, label) {
			console.log("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		}
	);
	$('input[name="daterange"]').val('');
	$('input[name="daterange"]').attr("placeholder","Select Range");
	$('[data-disable-touch-keyboard]').attr('readonly', 'readonly');

	initializeDropdown();

	// Initialize dropdown for sterilization pages, allow additions to input field beyond 30
	function initializeDropdown () {
		$('.sterilizeNumberDropdown').select2({
			theme: "bootstrap",
			placeholder: 'Add a Number',
			tags: true,
			// dropdownAutoWidth: false,
			// width:240,
			// width: 'resolve',
			createTag: function (params) {
				return {
				id: params.term,
				text: params.term,
				newOption: true
				}
			},
			templateResult: function (data) {
				var $result = $("<span></span>");
	
				$result.text(data.text);
	
				if (data.newOption) {
				$result.append(" <em></em>");
				}
	
				return $result;
			}
	
		});
	}

	// $('.scrollContainer').hide()
	// $('.scrollContainer[data-id="1"]').show();

	$('.page-item').click(function(){
		var id = $(this).children().html();
		$('.scrollContainer').slideUp();
		$('.scrollContainer[data-id='+id+']').slideDown(function(){
			initializeDropdown();
		});

	})

	$('.sterilizerSelect').select2({
		theme: "bootstrap",
		placeholder: 'Choose a Sterilizer',
		dropdownCssClass: "sterilizer"
	})

	//Updates cycle number when user changes sterilzier
	$('#sterilizer').change(function () {
		 var selectedCycleNum = $('#sterilizer option:selected').attr('data-cyclenum');
		 $('#cycle_number').val(selectedCycleNum)
	})

	//The call to action when user tries to log a staerilization after selecting the necessary.
	$('.log').click(function(e) {
		e.preventDefault()
		var sterilizer_id = $('#sterilizer option:selected').attr('id');
		var sterilizer = $('#sterilizer option:selected').val();
		var cycleNumber = $('#cycle_number').val();
		var comment = $('#comment').val();
		var type_5 =  $('#type_5_switch').is(':checked')  ? 1 : 0;

		
		
		var cleaner_ids = $('.cleaners').children().find('span.input-group-text').map(function(e){
			return this.id;
		}).get();

		var num_printable = $('.cleaners').children().find('.cleanerUnits').map(function() {
			return this.value;
		}).get();
		
		var selected_options = {}
		cleaner_ids.forEach(function(cleaner_id, i ) {
			selected_options[cleaner_id] = num_printable[i];
		})

		console.log(selected_options)

		var postData = { 
			"_token":  $('meta[name=csrf-token]').attr('content'),
			'data': selected_options, 
			'type_5': type_5,
			'sterilizer_id': sterilizer_id,
			'sterilizer': sterilizer,  
			'cycle_number': cycleNumber, 
			'comment': comment
		}
			console.log(postData)
		if ( validate(sterilizer, cycleNumber, selected_options ) ) {
			$.post('/sterilize', postData, function(res){
				var response = res.response;
				var printFiles = res.printFiles;
				var filepaths = res.filepaths;
				var message = res.message;
				console.log(response)
				
				if (response == 'success' ) {
					console.log('documents rendered')
					printData(printFiles, filepaths);

				} else if (response == 'error'){
					console.log(response)
					$('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>')
					window.scrollTo(0, 0);
				}
				console.log(response, status); 
			}).fail(function(err) {
				var error = err.responseJSON;
				$('.errorMsg').removeClass('hidden').find('ul').append('<li>'+error.message+'</li>')
				console.log(error.exception, error.message, error.line, error.file);
				window.scrollTo(0, 0);
			});
		}
	});
	// Used in the log click handler to verify if all the params are met. if they are, the function
	//returns bool that makes the log ajax call to pass and do the call to the server. 
	function validate(sterilizer, cycleNumber, selectedOptions )
	{
		console.log(sterilizer, cycleNumber, isNaN(cycleNumber));
		$('.invalid-feedback').removeClass('d-flex').addClass('hidden');
		$('#sterilizer').parent().removeClass('is-invalid')
		$('#cycle_number').removeClass('is-invalid');

		if (sterilizer === '') {
			$('#sterilizer').parent().addClass('is-invalid')
			$('#sterilizer_error').removeClass('hidden').addClass('d-flex');  
			return false;
		}
		if (cycleNumber === '') {
			$('#cycle_number').addClass('is-invalid').parent().next().removeClass('hidden').addClass('d-flex');
			return false;
		}
		if ( isNaN(cycleNumber) ) {
			var text = "Cycle Number has to be a number!" 
			$('#cycle_number').addClass('is-invalid').parent().next().removeClass('hidden').addClass('d-flex').children().text(text);
			return false;
		}
		return true;
	}


	var cycle_number, cycle_id, clicked_row;

	$('tr.pointer').click(function(){
		$('.confirm').hide(); 
		clicked_row = $(this);
		clicked_row.addClass('highlightSelectedRow');

		var cycle_completed = $(this).attr('data-cycleCompleted');
		
		var ent_user = $(this).find('.ent_user').html();
		var package = $(this).find('.package').html();
		cycle_number = $(this).find('.cycle_number').html();
		var units_printed = $(this).find('.units_printed').html();
		var ent_date = $(this).find('.ent_date').html();
		cycle_id = $(this).attr('data-cycleId');
		var type_5 = $(this).attr('data-type5');
		var sterilizer = $(this).find('.sterilizer').html();
		var type1 = $(this).find('.type1').html();
		var type4 = $(this).find('.type4').html();
		var type5 = $(this).find('.type5').html();

		var completed_on = $(this).attr('data-completedOn');
		var completed_by = $(this).attr('data-completedBy');
		var e_comment = $(this).find('.comment span').html();
		var remov_comment = $(this).attr('data-removComment');

		if (cycle_completed) {
			$('.completed').show();
			$('.input').hide();
			console.log('completed');
			var remov_date = $(this).attr('data-completedOn');
			var r_operator = $(this).attr('data-completedBy');

			$('#entry_date').text(ent_date);
			$('#removal_date').text(remov_date);
			$('#ent_operator').text(ent_user);
			$('#remov_operator').text(r_operator);
			$('#cycle_num').text(cycle_number);
			$('#type_1_status').text(type1)
			$('#type_4_status').text(type4)
			$('#type_5_status').text(type5)
			$('#ent_comment').text(e_comment)
			$('#sterilizer').text(sterilizer);
			$('#addtl_comment').val(remov_comment);
		} else {
			$('.input').show();
			$('.completed').hide();
			var state =  type_5 === '1' ? false : true;
			console.log('type_5' ,state)
			$('#type_5_switch').prop('disabled', state);

			$('#package').text(package);
			$('#date').text(ent_date);
			$('#operator').text(ent_user);
		}
			$('#modal_header').text(package + ' ( '+units_printed +' units ) : Cycle #:' + cycle_number);
	});

	// Toggles the confirmation modal when user clicks $('.clicked_row')
	$('.logChanges').click(function(){
		$('#modal_header').text('Confirm Log Changes')
		$('.input').slideUp();
		$('.confirm').slideDown(); 
	})

	$('.confirmLogChanges').click(function(){
		var $button = $(this)
		var verified = $button.attr('data-verified');
		$('#activeSterilizeModal').modal('hide')

		logHandler(verified);
	})

	function logHandler(verified) {
		var type1_sterile = $('#type_1_switch').is(':checked') ? 1 : 0;
		var type4_sterile = $('#type_4_switch').is(':checked')  ? 1 : 0;
		var type5_sterile = $('#type_5_switch').is(':checked')  ? 1 : 0;
		var batch = $('#batch').is(':checked')  ? 1 : 0;
		var comments = $('#additional_comments').val();
		
		var changeData = {
			"_token":  $('meta[name=csrf-token]').attr('content'),
			'type1': type1_sterile,
			'type4': type4_sterile,
			'type5': type5_sterile,
			'batch' : batch,
			'params_verified': verified,
			'cycle_number': cycle_number,
			'cycle_id' : cycle_id,
			'comments': comments
		}

		$.post('/sterilize/update', changeData, function(res) {
			var response = res.response;
			var log = res.log
			if (response == 'success') {
				if (batch === 1 ) {
					var message = "Sterilization logs have been updated";
					var rows = $('tbody').children().filter( function (){
						return $(this).attr('data-batchNum') === clicked_row.attr('data-batchNum');
					});
					rows.attr('data-completedOn', log.date);
					rows.attr('data-completedBy', log.remover);
					rows.attr('data-cycleCompleted', 1);
					rows.attr('data-removComment', comments);
					rows.find('.type1').html(log.type1);
					rows.find('.type4').html(log.type4);
					rows.find('.type5').html(log.type5);
					rows.find('.params_verified').html(log.params);
					
				} else if (clicked_row) {
					var message = 'Sterilization log has been updated'
					clicked_row.attr('data-completedOn', log.date);
					clicked_row.attr('data-completedBy', log.remover);
					clicked_row.attr('data-cycleCompleted', 1);
					clicked_row.find('.type1').html(log.type1);
					clicked_row.attr('data-removComment', comments);
					clicked_row.find('.type4').html(log.type4);
					clicked_row.find('.type5').html(log.type5);
					clicked_row.find('.params_verified').html(log.params);
				}
				$('html, body').animate({scrollTop: '0px'}, 500);
				$('.successMsg').removeClass('hidden').slideDown().find('ul').append('<li>'+message+'</li>')
				setTimeout(() => {
					$('.successMsg').slideUp().addClass('hidden').find('ul').children().remove();
				}, 3000);
			} else if (response == 'error'){
				$('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>')
				window.scrollTo(0, 0);
			}

		}).fail(function(err) {
			var error = err.responseJSON;
			$('.errorMsg').removeClass('hidden').find('ul').append('<li>'+error.message+'</li>')
			window.scrollTo(0, 0);
			console.log(error.exception, error.message, error.line, error.file);
		});
	}



	
	$("#additional_comments, #addtl_comment").on("focus", function(){
		$('.modal').addClass('height400');
		$('.modal').animate({scrollTop: $(document).height() + $(window).height()});
	}).on('blur',function(){
		$('.modal').removeClass('height400');
	});

	// Sterilize comment box initally when adding 
	$('#comment').on("focus", function(){
		console.log('yes')
		$('body').addClass("margin350");
		// .css('margin-bottom', '200px !important')
		$('html, body').animate({scrollTop:$(document).height()}, 'slow');

		// $('body').animate({scrollTop: $(document).height() + $(window).height()});
	})
	.on('blur',function(){
		$('body').removeClass("margin350");
	});

	$('.updateComment').click(function(){
		var comment = $('#addtl_comment').val();

		var data = {
			"_token":  $('meta[name=csrf-token]').attr('content'),
			'cycle_id': cycle_id,
			'comment': comment,
		}
		$('#activeSterilizeModal').modal('hide')

		$.post('/sterilize/update/comment', data, function(res) {
			var response = res.response;
			if (response == 'success' ) {
				// console.log('comment updated')
				clicked_row.attr('data-removComment',  comment) 

			} else if (response == 'error'){
				$('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>')
				
			}
		}).fail(function(err) {
			var error = err.responseJSON;
			$('.errorMsg').removeClass('hidden').find('ul').append('<li>'+error.message+'</li>')
			console.log(error.exception, error.message, error.line, error.file);
			window.scrollTo(0, 0);
		});
			
		
	})


	function printData(printFiles, filepaths) {
		qz.print(config, [{
			type: 'raw', 
			format: 'base64',
			data: printFiles
		}]).then(function(){
			console.log('deleting print')
			deletePdf(filepaths);
		}).catch(function(e) { 
			var message = "There was an issue with sending print data to the printer"
			$('.errorMsg').removeClass('hidden').find('ul').append('<li>'+message+'</li>')
			window.scrollTo(0, 0);
			console.log(e); 
		});
	   
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
				console.log('documents printed & deleted')
				var message = 'Print request has been sent to the printer.';
				window.scrollTo(0, 0);
				$('.successMsg').removeClass('hidden').slideDown().find('ul').append('<li>'+message+'</li>')
				setTimeout(() => {
					$('.successMsg').slideUp().addClass('hidden').find('ul').children().remove();
				}, 3000);
			}
		}).fail(function(a,b,c) {
			console.log(a,b,c);
		});
	}


	$('.modal-child').on('show.bs.modal', function () {
		var modalParent = $(this).attr('data-modal-parent');
		$(modalParent).css('opacity', 0);
	});
 
	$('.modal-child').on('hidden.bs.modal', function () {
		console.log('yo')
		var modalParent = $(this).attr('data-modal-parent');
		$(".switch-input").prop('checked', false);
		$('.switch-input').prop('disabled', false);
		$('.completed').hide();
		$('.input').show();
		if (typeof(cicked_row) !== 'undefined')  {
			clicked_row.removeClass('highlightSelectedRow');
		}
	});

	$('#activeSterilizeModal').on('hidden.bs.modal', function (e) {
		$(".switch-input").prop('checked', false);
		// if (typeof(cicked_row) !== 'undefined')  {
			clicked_row.removeClass('highlightSelectedRow');
		// }
	});

	// $(document).on('click',  '.downloadCsv', function(e){
	// 	// e.preventDefault();
	// 	console.log('yo')
	// 	var dateRange = $('input[name="daterange"]').val();
	// 	var operator =  $('select[name*="operator"]').val();
	// 	var sterilizer = $('select[name="sterilizer"]').val();
	// 	var package = $('select[name="package"]').val();	
	// 	var cycle = $('input[name="cycle"]').val();	

	// 	console.log(operator, dateRange, sterilizer, package, cycle);
	// 	var downloadLink = '/sterilize/log/download';
	// 	var postData = {
	// 		"_token":  $('meta[name=csrf-token]').attr('content'),
	// 		'daterange': dateRange, 
	// 		'operator ': operator,
	// 		'sterilizer' : sterilizer,
	// 		'package' : package,
	// 		'cycle' : cycle, 
	// 	}
		
	// 	$.post(downloadLink, postData, function (res){
	// 		var response = res.response;
	// 		if (response == 'success') {

	// 		} else if (response == 'error') {
				
	// 		}
	// 	})
	// });
});