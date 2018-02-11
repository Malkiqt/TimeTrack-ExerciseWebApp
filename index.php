<html>

<head>
	<?php 

		include('php/functions.php');
		echo_header();
	?>
</head>

<body>


	<div id='wrapper' class='col-xs-12 col-xs-push-0'>








	<?php 
		if(isset($_SESSION['logged'])){
			echo "<div id='logged_out_content' hidden>";
		}else{
			echo "<div id='logged_out_content'>";
		}
	?>
		<div class='header'>

			<div id='login_form_container' class='col-md-4 col-md-push-2 col-xs-12'>
							<h3>Login :</h3>
							<form id='login_form'>
								<input type='text' name='username' placeholder='Username' maxlength="15" ><br>
								<input type='password' name='password' placeholder='Password' maxlength="15"><br>
								<button type='submit' name='submit' value='Submit'>Submit</button>
							</form>
			</div>

			<div id='register_form_container' class='col-md-4 col-md-push-2 col-xs-12'>
						<h3>Register :</h3>
						<form id='register_form'>
							<input type='text' name='username' placeholder='Username' maxlength="15" pattern="(?=.*[A-Za-z]).{6,}" title="Must only contain letters,minimum 6"><br>
							<input type='password' name='password' placeholder='Password' maxlength="15"><br>
							<input type='password' name='repeat_password' placeholder='Repeat Password' maxlength="15"><br>
							<button type='submit' name='submit' value='Submit'>Submit</button>
						</form>
			</div>

		</div>

				<img id='main_image' src='images/main.jpg' class='col-md-8 col-md-push-2 col-xs-12'>
	</div>












	<?php 
	if(isset($_SESSION['logged'])){
		echo "<div id='logged_in_content'>";
	}else{
		echo "<div id='logged_in_content' hidden>";
	}
	?>


		<div id='navbar' class='col-xs-12 hidden-lg hidden-md'>
			<ul>
				<li class='col-xs-12 change_content' value='day'>Days Dashboard</li>
				<li class='col-xs-12 change_content' value='activity'>Activities</li>
				<li class='col-xs-12 change_content' value='stats'>Statistics</li>
			</ul>

		</div>



		<div id='day_container' class='col-md-3 col-xs-12 '>

							<div id='create_day_container' class='day_container header col-xs-12'>
								<h3>Create Day : </h3>
								<h5><?php echo date('d/m/y'); ?></h5>
								<form id='create_day_form'>
									<input type='text' name='name' placeholder='Day Name' maxlength="20"><br>
									<button type='submit'>Create Day</button>
								</form>
							</div>


							<div id='days' class='day_container col-xs-12'>
								<form id='day_search' class='col-xs-12'>
									<input type='text' id='search_input' name='search' placeholder='Find Day'>
									<button type='submit'>Submit</button>
								</form>
								<div id='day_list' class='col-xs-12'>

									<?php 
										echo_days(isset($_SESSION['logged']));
									?>
								</div>
							</div>
		</div>







		<div id='activity_container' class='col-md-5 col-xs-12'>

							<div id='create_activity_container' class='header col-xs-12' hidden>
								<h3>Add Activity : </h3>
								<form id='create_activity_form'>
									<input type='text' name='description' placeholder='Name/Description' maxlength="20">
									<select name='type'>
										<option value="" selected disabled hidden>Type</option>
										<option>Work</option>
										<option>Leasure</option>
										<option>Social</option>
										<option>Family</option>
										<option>Sports</option>
									</select>
									<br>
									<input type='text' class='timepicker' placeholder='Start Time' name='start_time' pattern='(?=.*[0-9:]).{5,}' title='Time Format (hh:mm) required'>
									<input type='text' class='timepicker' placeholder='End Time' name='end_time' pattern='(?=.*[0-9:]).{5,}' title='Time Format (hh:mm) required'>
									<br>
									<button type='submit'>Add Activity</button>
								</form>
							</div>



							<div id='activities' class='col-xs-12'>
								<h3 id='current_date'></h3>
								<div id='activity_list'>

								</div>
							</div>

		</div>

		<div id='stats_container' class='col-md-3 col-xs-12'>

							<div class='col-xs-12'>
								<div class='header col-md-12 col-xs-12'>
									<div id='stats_options'>
										
										<form id='stats_filter'>
											<input type="radio" name="time_span" value="total" checked> Total <br>
											<input type="radio" name="time_span" value="year"> Last Year<br>
											<input type="radio" name="time_span" value="month"> Last Month<br>
											<input type="radio" name="time_span" value="week"> Last Week<br>
											<input type="radio" name="time_span" value="selected"> Selected Day<br>

											  			  
											<button id='load_stats' type='submit'>Load Stats</button>
										</form>						
									</div>
								</div>


							</div>




							<div id='stats' class='stats col-xs-12'>

								<div>
									<span id='work_stat'>Work</span>
									<br>
									<div id='work_bar' class='work'><span id='work_percentage'>%</span></div>
								</div>
								<br>

								<div>
									<span id='leasure_stat'>Leasure</span>
									<br>
									<div id='leasure_bar' class='leasure'><span id='leasure_percentage'>%</span></div>
								</div>
								<br>

								<div>
									<span id='social_stat'>Social</span>
									<br>
									<div id='social_bar' class='social'><span id='social_percentage'>%</span></div>
								</div>
								<br>


								<div>
									<span id='family_stat'>Family</span>
									<br>
									<div id='family_bar' class='family'><span id='family_percentage'>%</span></div>
								</div>
								<br>

								<div>
									<span id='sports_stat'>Sports</span>
									<br>
									<div id='sports_bar' class='sports'><span id='sports_percentage'>%</span></div>
								</div>
							</div>



		</div>



		<div class='col-md-1 col-xs-12'>
			<button class='content_link' id='logout'>Logout</button>
		</div>	

	</div>


</body>



</html>
