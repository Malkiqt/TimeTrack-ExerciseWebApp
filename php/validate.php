<?php 

	session_start();
	include('connect.php');

	$action = $_POST['action'];

	switch($action){




		case 'load_posts':
			$username = $_SESSION['username'];
			$content = $_POST['content'];

			if($content=='activities'){
				$date = $_POST['date'];
				$query = "SELECT * FROM activity WHERE username='".$username."' AND date='".$date."' ORDER BY start_time";
			}else if($content=='days'){
				if(isset($_POST['search'])){
					$search = $_POST['search'];
					$query = "SELECT * FROM days WHERE username='".$username."' AND name LIKE '%".$search."%' OR date LIKE '%".$search."%' ORDER BY id DESC";
				}else{
					$query = "SELECT * FROM days WHERE username='".$username."' ORDER BY id DESC";
				}
				
			}
			
		
			
			$result = $conn->query($query);


			if($result){
				$result_rows = $result->num_rows;
				$count = 0;
				echo '[';
				while($row = $result->fetch_row()){
			
					if($content=='activities'){
						echo '{"id":"'.$row[0].'","start_time":"'.$row[3].'","end_time":"'.$row[4].'","length":"'.$row[5].'","type":"'.$row[6].'","description":"'.$row[7].'"}';
					}else{
						echo '{"id":"'.$row[0].'","date":"'.$row[2].'","name":"'.$row[3].'"}';
					}
					
					$count += 1;
					if($count<$result_rows){
						echo ',';
					}
				}
				echo ']';				
			}

			break;



		case 'load_stats':
			$username = $_SESSION['username'];

			$time_span = $_POST['time_span'];

			switch($time_span){
				case 'total':
					$query = "SELECT type,length FROM activity WHERE username='".$username."'";	
					break;
				case 'selected':
					$date = $_POST['date'];
					$query = "SELECT type,length FROM activity WHERE username='".$username."' AND date='".$date."'";	
					break;
				case 'week':
					$query = "SELECT type,length FROM activity WHERE username='".$username."' ORDER BY id DESC limit 7";	
					break;
				case 'month':
					$query = "SELECT type,length FROM activity WHERE username='".$username."' ORDER BY id DESC limit 30";	
					break;
				case 'year':
					$query = "SELECT type,length FROM activity WHERE username='".$username."' ORDER BY id DESC limit 365";	
					break;

			}

			
			$result = $conn->query($query);
			
			$total_length = 0;
			$work_time = 0;
			$leasure_time = 0;
			$social_time = 0;
			$family_time = 0;
			$sports_time = 0;
			if($result){
				while($row = $result->fetch_row()){
					$total_length += $row[1];
					switch($row[0]){
						case 'Work':
							$work_time += $row[1];
							break;
						case 'Leasure':
							$leasure_time += $row[1];
							break;
						case 'Social':
							$social_time += $row[1];
							break;
						case 'Family':
							$family_time += $row[1];
							break;		
						case 'Sports':
							$sports_time += $row[1];
							break;
					}
				}
				$result_rows = $result->num_rows;
				$count = 0;

				if($total_length){
					$work_time = round((($work_time/$total_length)*100),2);
					$leasure_time = round((($leasure_time/$total_length)*100),2);
					$social_time = round((($social_time/$total_length)*100),2);
					$family_time = round((($family_time/$total_length)*100),2);
					$sports_time = round((($sports_time/$total_length)*100),2);				
				}

				echo '[';
				echo '{"work_time":"'.$work_time.'","leasure_time":"'.$leasure_time.'","social_time":"'.$social_time.'","family_time":"'.$family_time.'","sports_time":"'.$sports_time.'"}';
				echo ']';	
			}
			break;


		case 'check': 
			$username = $_POST['username'];
			$query = "SELECT * FROM users WHERE username='".$username."'";
			$result = $conn->query($query);
			if($result->num_rows){
				echo '{"result":"0"}';
			}else{
				echo '{"result":"1"}';
			}
			break;



		case 'register':

			$username = $_POST['username'];
			$password = hash('md4',$_POST['password']);
			$query = "INSERT INTO users(username,password,admin) VALUES('" .$username. "','". $password ."','0')";	
			$conn->query($query);	

			if($result){
				echo '{"result":"1"}';
			}else{
				echo '{"result":"0"}';
			}
			break;	



		case 'login':
			$username = $_POST['username'];
			$password = hash('md4',$_POST['password']);
			$query = "SELECT * FROM users WHERE username='".$username."' AND password='".$password."'";
			$result = $conn->query($query);
			if($result->num_rows){
				$_SESSION['logged'] = true;
				$_SESSION['username'] = $username;
				echo '{"result":"1"}';
			}else{
				echo '{"result":"0"}';
			}
			break;



		case 'logout':

			session_destroy();
			break;



		case 'create_activity':
			$username = $_SESSION['username'];

			$date = $_POST['date'];

			
			$type = $_POST['type'];
			$description = $_POST['description'];
			$start_time = $_POST['start_time'];
			$end_time = $_POST['end_time'];


			$length = get_time_length($start_time,$end_time);


			$query = "INSERT INTO activity(username,date,start_time,end_time,length,type,description) 
			VALUES('" .$username. "','" .$date."','" .$start_time."','". $end_time ."','". $length ."','". $type ."','". $description ."')";	
			
			$result = $conn->query($query);

			if($result){			
				echo '{"result":"1"}';
			}else{
				echo '{"result":"0"}';
			}

			
			break;	

		
		case 'update_activity':

			$id = $_POST['id'];

			$description = $_POST['description'];
			$type = $_POST['type'];

			$start_time = $_POST['start_time'];
			$end_time = $_POST['end_time'];

			$length = get_time_length($start_time,$end_time);


			$query = "UPDATE activity SET description='".$description."',type='".$type."',start_time='".$start_time."',end_time='".$end_time."',length='".$length."'
			WHERE id='".$id."'";

			$result = $conn->query($query);

			if($result){			
				echo '{"result":"1"}';
			}else{
				echo '{"result":"0"}';
			}

			break;


		case 'delete_activity':

			$id = $_POST['id'];


			$query = "DELETE FROM activity WHERE id='".$id."'";
			$conn->query($query);

			echo $conn->error;

			break;

		case 'create_day':
			$username = $_SESSION['username'];
			$name = $_POST['name'];
			$date = date('d/m/y');
			$query = "SELECT * FROM days WHERE username='".$username."' AND date='".$date."'";
			$result = $conn->query($query);

			if(!$result->num_rows){
				$query = "INSERT INTO days(username,name,date) VALUES('" .$username. "','". $name ."','". $date ."')";	
				$conn->query($query);				
				echo '{"result":"1"}';
			}else{
				echo '{"result":"0"}';
			}
			break;	
		

		case 'update_day':
			$id = $_POST['id'];
			$name = $_POST['name'];


			$query = "UPDATE days SET name='".$name."' WHERE id='".$id."'";
			$result = $conn->query($query);

			if($result){				
				echo '{"result":"1"}';
			}else{
				echo '{"result":"0"}';
			}

			break;

	}





function get_time_length($start_time,$end_time){
	$start_hour = intval(substr($start_time, 0, strpos($start_time, ":")));
	$end_hour = intval(substr($end_time, 0, strpos($end_time, ":")));

	$start_minutes = intval(substr($start_time, (strpos($start_time, ":")+1) , (strlen($start_time)-3)));
	$end_minutes = intval(substr($end_time, (strpos($end_time, ":")+1) , (strlen($end_time)-3)));

	if($end_hour<$start_hour){
		$end_hour += 24;
	}
	if($end_minutes<$start_minutes){
		$end_hour--;
		$end_minutes+=60;
	}
	$length = (($end_hour - $start_hour)*60 + ($end_minutes - $start_minutes));	

	return $length;
}





?>