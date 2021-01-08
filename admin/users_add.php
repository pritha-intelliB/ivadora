<?php
	include 'includes/session.php';
	include 'includes/functions.php';	

	if(isset($_POST['add'])){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$type = 0;
		$address = $_POST['address'];
		$contact = $_POST['contact'];
		$activate_code = getToken(10);
		$reset_code = getToken(12);

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE email=:email");
		$stmt->execute(['email'=>$email]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			$_SESSION['error'] = 'Email already taken';
		}
		else{
			$password = password_hash($password, PASSWORD_DEFAULT);
			$filename = $currentdatetime.$_FILES['photo']['name'];
			$now = date('Y-m-d');
			if(!empty($filename)){
				move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
			}
			try{
				$stmt = $conn->prepare("INSERT INTO users (email, password, type, firstname, lastname, address, contact_info, photo, status, activate_code, reset_code, created_on) VALUES (:email, :password, :type, :firstname, :lastname, :address, :contact, :photo, :status, :activate_code, :reset_code, :created_on)");
				$stmt->execute(['email'=>$email, 'password'=>$password, 'type'=>$type, 'firstname'=>$firstname, 'lastname'=>$lastname, 'address'=>$address, 'contact'=>$contact, 'photo'=>$filename, 'status'=>1, 'activate_code'=>$activate_code, 'reset_code'=>$reset_code, 'created_on'=>$now]);
				$_SESSION['success'] = 'User added successfully';

			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up user form first';
	}

	header('location: users.php');

?>