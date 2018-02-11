<?php 
	function echo_header(){
		
		session_start();


		echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';

		//Optional theme 
		echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">';


		echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>';

		echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>';


		echo '<link href="//cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css"  rel="stylesheet">';
		
		echo '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">';

		echo '<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>';

		echo  '<link rel="stylesheet" type="text/css" href="css/style.css">';

		echo  '<script src="javascript/behaviour.js"> </script>';

	}




	function echo_days($logged){

		include('php/connect.php');

		$username = $_SESSION['username'];

		$query = "SELECT * FROM days WHERE username='".$username."' ORDER BY id DESC";

		$result = $conn->query($query);

		if($result){

			while($row = $result->fetch_row()){	

				echo "

				<div class='day info_container'>
					<div class='day_show'>
						<span class='day_name'>".$row[3]."</span>
						<span> - </span>
						<span class='day_date'>".$row[2]."</span>
						<button class='update'>Update</button>
					</div>
					<div class='day_update' id='".$row[0]."' hidden>
						<form class='day_update_form'>
							<input type='text' name='name' placeholder='Day Name' maxlength='15'><br>
							<button type='submit'>Update Day</button>
							<button class='exit'>Exit</button>
						</form>
					</div>
				</div>

				";

			}

		}	

	}
?>