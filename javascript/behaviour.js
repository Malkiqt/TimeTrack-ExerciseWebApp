var current_date = '';


$(function(){




    $(window).resize(function(){
        hide_content();
    });
    hide_content();


	$('.change_content').on('click',function(e){
		e.preventDefault();
		var what_to_show = $(this).attr('value');

		$('#day_container').hide();
		$('#activity_container').hide();
		$('#stats_container').hide();

		$('#' + what_to_show + '_container').show();
	});


	$('#stats_filter').submit(function(e){
		e.preventDefault();
		var form_values = $(this).serializeArray();
		if(form_values[0].value==='selected'){
			load_stats(form_values[0].value,current_date);
		}else{
			load_stats(form_values[0].value);
		}
		
	});

	$('#day_search').submit(function(e){
		e.preventDefault();
		var search_input = $('#search_input').val();

		load_days(search_input);
	});


	$('#days').on('click','span',function(e){
		e.preventDefault();
		current_date = $(this).parent().find('.day_date').html();
		$('#create_activity_container').show();
		$('#daily_stats').show();
		$('#current_date').html(current_date);
	    if ($(window).width() < 768) {
	    	$('#day_container').hide();
	    	$('#activity_container').show();
	    }
		load_activities(current_date);
	});

	$('#toggle_content').on('click',function(e){
		e.preventDefault();
		if($('#content').is(':visible')){
			$(this).html('Activity');

			$('.content').hide();
			$('.stats').show();
		}else{
			$(this).html('Statistics');

			$('.content').show();
			$('.stats').hide();
		}
	});


	

	control_content_buttons();

	validate_forms();

});






function load_days(search){
	var form_data = new FormData();
	form_data.append('action','load_posts');
	form_data.append('content','days');
	
	if(typeof search !== 'undefined'){
		form_data.append('search',search);
	}

	$('#day_list').empty();

	send_ajax(form_data,function(data){
		var result_data = $.parseJSON(data);
		for(var info in result_data){
			create_post('day',result_data[info]);
		}		
	});	
}


function load_activities(date){
	var form_data = new FormData();
	form_data.append('action','load_posts');
	form_data.append('content','activities');	
	
	form_data.append('date',date);

	$('#activity_list').empty();
	$('#activity_list').show();
	send_ajax(form_data,function(data){
		var result_data = $.parseJSON(data);
		for(var info in result_data){
			create_post('activity',result_data[info]);
		}	
		timepicker_initialize(true);	
	});	
}


function load_stats(time_span,date){
	var form_data = new FormData();
	form_data.append('action','load_stats');
	
	form_data.append('time_span',time_span);
	
	if(typeof date !== 'undefined'){
		form_data.append('date',date);
	}

	send_ajax(form_data,function(data){
		var result_data = $.parseJSON(data);
		update_stats(result_data[0]);	
	});		
}


function update_stats(received_data){


	$('#stats').find('#work_percentage').html(received_data.work_time +"%");
	$('#stats').find('#work_bar').css('width',received_data.work_time+'%');

	$('#stats').find('#leasure_percentage').html(received_data.leasure_time +"%");
	$('#stats').find('#leasure_bar').css('width',received_data.leasure_time+'%');

	$('#stats').find('#social_percentage').html(received_data.social_time +"%");
	$('#stats').find('#social_bar').css('width',received_data.social_time+'%');

	$('#stats').find('#family_percentage').html(received_data.family_time +"%");
	$('#stats').find('#family_bar').css('width',received_data.family_time+'%');

	$('#stats').find('#sports_percentage').html(received_data.sports_time +"%");
	$('#stats').find('#sports_bar').css('width',received_data.sports_time+'%');

}



function validate_forms(){

	validate_logout();

	validate_login();
	validate_register();

	create_activity();
	update_activity();
	delete_acivity();

	create_day();
	update_day();

}


function delete_acivity(){
	$('#activities').on('click','.delete',function(e){
		e.preventDefault();
		var form_data = new FormData();

		var id = $(this).parent().parent().find('.activity_update').attr('id');

		form_data.append('action','delete_activity');
		form_data.append('id',id);
		send_ajax(form_data,function(){

			activity_change();
		});
	});
}

function update_activity(){

	$('#activities').on('submit','.activity_update_form',function(e){
		e.preventDefault();
		var form_values = $(this).serializeArray();

		var id = $(this).parent().attr('id');
		form_values.push({name: 'id',value: id});	

		form_ajax('update_activity',$(this),form_values,function(){

			activity_change();
		});		
	})
}



function create_activity(){
	$('#create_activity_form').submit(function(e){
		e.preventDefault();
		var form_values = $(this).serializeArray();
		var start_time_string = $(this).find('.start_time').val();
		var end_time_string = $(this).find('.end_time').val();
		
		form_values.push({name: 'date', value: current_date});

		form_ajax('create_activity',$(this),form_values,function(){

			activity_change();
		});		
	})
}

function update_day(){
	$('#days').on('submit','.day_update_form',function(e){
		e.preventDefault();
		var form_values = $(this).serializeArray();

		var id = $(this).parent().attr('id');
		form_values.push({name: 'id',value: id});

		form_ajax('update_day',$(this),form_values,function(){
			load_days();
		});		
	})
}

function create_day(){
	$('#create_day_form').submit(function(e){
		e.preventDefault();		
		var form_values = $(this).serializeArray();


		form_ajax('create_day',$(this),form_values,function(data){
			var result_data = $.parseJSON(data);
			if(result_data.result==0){
				alert('you already made a day today');
			}else{
				load_days();
			}
			
		});	
	})
}


function validate_login(){
	$('#login_form').submit(function(e){
		e.preventDefault();
		var form_values = $(this).serializeArray();


		form_ajax('login',$(this),form_values,function(data){

			var received_data = $.parseJSON(data);
			if(received_data.result==0){
				clear_form_inputs($(this));
				alert('Wrong Username or Password');
			}else{
				$('#logged_out_content').hide();
				$('#logged_in_content').show();
				$('#create_activity_container').hide();
				$('#activity_list').hide();
				$('#current_date').html('');
				load_days();
			}

		});
	})
}

function validate_register(){
	$('#register_form').submit(function(e){
		e.preventDefault();
		var form_values = $(this).serializeArray();

		var password = form_values[1].value;
		var repeat_password = form_values[2].value;

		if(password!==repeat_password){
			clear_form_inputs($(this));
			alert('Passwords do not match..')
		}else{
			form_ajax('check',$(this),form_values,function(data){
				var received_data = $.parseJSON(data);
				if(received_data.result==0){
					clear_form_inputs($(this));
					alert('Username Exists')
				}else{
					var form_data = new FormData();
					for(var info of form_values){
						form_data.append(info.name,info.value);
					}
					form_data.append('action','register');
					send_ajax(form_data,function(){
							alert('Registered');
						},
						function(){
							alert('A problem occured..');
						}
					);						
				}
			})			
		}

	})
}



function validate_logout(){
	$('#logout').on('click',function(e){
		e.preventDefault();
		var form_data = new FormData();
		form_data.append('action','logout');
		send_ajax(form_data,function(){
			$('#logged_out_content').show();
			$('#logged_in_content').hide();
		});
	});
}





function form_ajax(action, form_object ,form_values, success_callback, error_callback){

	var fill_fields = false;

	form_object.find('input').each(function(){
		if(!$(this).val()){
			fill_fields = true;
			return false;
		}
	});

	if(!fill_fields){
		clear_form_inputs(form_object);

		var form_data = new FormData();
		for(var info of form_values){
			form_data.append(info.name,info.value);
		}
		form_data.append('action',action);

		send_ajax(form_data,success_callback,error_callback);
	}else{
		alert('Fill All Fields..')
	}

}


function send_ajax(form_data,success_callback,error_callback){
	$.ajax({
		method: 'POST',
		url: 'php/validate.php',
		processData: false,  // tell jQuery not to process the data
	    contentType: false,  // tell jQuery not to set contentType
		data: form_data,
		success: function(data){
			success_callback(data);
		},
		error: function(){
			error_callback();
		}
	});		
}









function create_post(type,received_data){
	if(type=='day'){
		/*
		<div class='day info_container'>
			<div class='day_show'>
				<span class='day_name'> + received_data.name + </span>
				<span> - </span>
				<span class='day_date'> + received_data.date + </span>
				<button class='update'>Update</button>
			</div>
			<div class='day_update' id=' + received_data.id + ' hidden>
				<form class='day_update_form'>
					<input type='text' name='name' placeholder='Day Name'><br>
					<button type='submit'>Update Day</button>
					<button class='exit'>Exit</button>
				</form>
			</div>
		</div>
		*/
		var content = "<div class='day info_container'><div class='day_show'><span class='day_name'>" + received_data.name + "</span><span> - </span><span class='day_date'>" + received_data.date + "</span><button class='update'>Update</button></div><div class='day_update' id='" + received_data.id + "' hidden>";
		content += "<form class='day_update_form'><input type='text' name='name' placeholder='Day Name' maxlength='15'><br><button type='submit'>Update Day</button><button class='exit'>Exit</button></form></div></div>";
		$('#day_list').append(content);
	}else if(type=='activity'){
		/*
		<div class='activity info_container'>
			<div class='activity_show class='received_data.type + _activity'>
				<span class='data_start_time'>received_data.start_time</span>
				<span> - </span>
				<span class='data_end_time'>received_data.end_time</span>
				<span class='data_description'>: received_data.description</span>
				<span class='data_type'>: received_data.type</span>
				<button class='update'>Update</button>
				<button class='delete'>Delete</button>
			</div>

			<div class='activity_update' id='item_id' hidden>
				<form class='activity_update_form'>
					<input type='text' name='description' class='update_description' placeholder='Name/Description'>

					<select name='type' class='update_type'>
						<option value="" selected disabled hidden>Type</option>
						<option>Work</option>
						<option>Leasure</option>
						<option>Social</option>
						<option>Family</option>
						<option>Sports</option>
					</select>
					<br>

					<input type='text' class='timepicker update_start_time' placeholder='Start Time' name='start_time' pattern='(?=.*[0-9:]).{5,}' title='Time Format (hh:mm) required'>
					<input type='text' class='timepicker update_end_time' placeholder='End Time' name='end_time' pattern='(?=.*[0-9:]).{5,}' title='Time Format (hh:mm) required'>

					<button type='submit'>Update Activity</button>
					<button class='exit'>Exit</button>
				</form>
			</div>
		</div>
		*/


		
		var content = "<div class='activity info_container " + received_data.type.toLowerCase() + "'><div id='" + received_data.id + "' class='activity_show'><span class='data_start_time'>" + received_data.start_time + "</span><span> - </span><span class='data_end_time'>" + received_data.end_time + "</span><span> : </span><span class='data_description'>" + received_data.description + "</span><span> : </span><span class='data_type'>" + received_data.type + "</span><button class='update'>Update</button><button class='delete'>Delete</button></div><div class='activity_update' id='" + received_data.id + "' hidden>";
		content += "<form class='activity_update_form'><input type='text' name='description' placeholder='Name/Description' class='update_description' maxlength='15'><select name='type' class='update_type'><option value='' selected disabled hidden>Type</option><option>Work</option><option>Leasure</option><option>Social</option><option>Family</option><option>Sports</option></select><br><input type='text' class='timepicker update_start_time' placeholder='Start Time' name='start_time' pattern='(?=.*[0-9:]).{5,}' title='Time Format (hh:mm) required'><input type='text' class='timepicker update_end_time' placeholder='End Time' name='end_time' pattern='(?=.*[0-9:]).{5,}' title='Time Format (hh:mm) required'><button type='submit'>Update Activity</button><button class='exit'>Exit</button></form></div></div>";
		
		$('#activity_list').append(content);

			var height = ((received_data.length/1440)*100)/1.2;
			$('#' + received_data.id).css('height',height +'%');
			$('#' + received_data.id).css('min-height',3 +'%');

		
	}
}






function clear_form_inputs(form_object){
	form_object.find('input').each(function(){
		$(this).val('');
	});
}






function control_content_buttons(){
	$('#activities').on('click','.update',function(e){
		e.preventDefault();

		var parent = $(this).parent();
		var activity_form = $(this).parent().parent().find('.activity_update').find('.activity_update_form');


		var start_time = parent.find('.data_start_time').html();
		var end_time = parent.find('.data_end_time').html();
		var type = parent.find('.data_type').html();
		var description = parent.find('.data_description').html();

		activity_form.find('.update_start_time').val(start_time);
		activity_form.find('.update_end_time').val(end_time);		
		activity_form.find('.update_type').val(type);	
		activity_form.find('.update_description').val(description);	

		$('.activity_update').hide();
		$('.activity_show').show();
		$(this).parent().parent().find('.activity_update').show();


	});

	$('#activities').on('click','.exit',function(e){
		e.preventDefault();
		$(this).parent().parent().parent().find('.activity_update').hide();


	});

	$('#days').on('click','.update',function(e){
		e.preventDefault();
		$('.day_update').hide();
		$('.day_show').show();
		$(this).parent().parent().find('.day_update').show();


	});

	$('#days').on('click','.exit',function(e){
		e.preventDefault();
		$(this).parent().parent().parent().find('.day_update').hide();


	});	
}




$(window).on('load',function(){
	timepicker_initialize(false);
})


function timepicker_initialize(dynamic){
	if(dynamic){
		$('#activities .timepicker').each(function(){
			$(this).timepicker({
			    timeFormat: 'HH:mm',
			    use24hours: true,
			    interval: 60,
			    minTime: '0',
			    maxTime: '23:59',
			    defaultTime: '',
			    startTime: '00:00',
			    dynamic: true,
			    dropdown: true,
			    scrollbar: true
			});	
		})
	}else{
		$('.timepicker').timepicker({
		    timeFormat: 'HH:mm',
		    use24hours: true,
		    interval: 60,
		    minTime: '0',
		    maxTime: '23:59',
		    defaultTime: '',
		    startTime: '00:00',
		    dynamic: true,
		    dropdown: true,
		    scrollbar: true
		});		
	}

}



function activity_change(){
	load_stats('total');
	load_stats('daily',current_date);
	load_activities(current_date);
}






function hide_content(){

    if ($(window).width() < 768) {

		$('#day_container').show();
		$('#activity_container').hide();
		$('#stats_container').hide();

    }else{

		$('#day_container').show();
		$('#activity_container').show();
		$('#stats_container').show();

    }

}




