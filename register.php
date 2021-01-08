<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	include 'includes/session.php';
$type = 0;
	if($_POST['newregister']=='yes'){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		$eventname = $_POST['eventname'];
		$eventdate = date('Y-m-d',strtotime($_POST['eventdate']));

		$_SESSION['firstnamej'] = $firstname;$test =' fdfdfdf';
		$_SESSION['lastname'] = $lastname;
		$_SESSION['email'] = $email;
		$_SESSION['eventname'] = $eventname;
		$_SESSION['contact_info'] = $phone;

		if(!isset($_SESSION['captcha'])){
			require('recaptcha/src/autoload.php');		
			$recaptcha = new \ReCaptcha\ReCaptcha('6LeIxcgUAAAAABwJHZT1Hf1qV85KwsmvHUE_IZs7', new \ReCaptcha\RequestMethod\SocketPost());
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

			if (!$resp->isSuccess()){
		  		$_SESSION['error'] = 'Please answer recaptcha correctly';
		  		header('location: signup.php');	
		  		exit();
		  	}	
		  	else{
		  		$_SESSION['captcha'] = time() + (10*60);
		  	}

		}

		if($password != $repassword){
			$_SESSION['error'] = 'Passwords did not match';
			header('location: signup.php');
		}
		else{
			$conn = $pdo->open();

			$stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM users WHERE email=:email");
			$stmt->execute(['email'=>$email]);
			$row = $stmt->fetch();
			if($row['numrows'] > 0){
				$_SESSION['error'] = 'Email already taken';
				header('location: signup.php');
			}
			else{
				$now = date('Y-m-d');
				$password = password_hash($password, PASSWORD_DEFAULT);

				//generate code
				$set='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$code=substr(str_shuffle($set), 0, 12);

				try{
					$stmt = $conn->prepare("INSERT INTO users (email, password, type, firstname, lastname, contact_info, activate_code, created_on, event_name, event_date) VALUES (:email, :password, :type, :firstname, :lastname, :phone, :code, :now, :eventname, :eventdate)");
					$stmt->execute(['email'=>$email, 'password'=>$password, 'type'=>$type, 'firstname'=>$firstname, 'lastname'=>$lastname, 'phone'=>$phone, 'code'=>$code, 'now'=>$now, 'eventname'=>$eventname, 'eventdate'=>$eventdate]);
					$userid = $conn->lastInsertId();

					$message = "
						<h2>Thank you for Registering.</h2>
						<p>Your Account:</p>
						<p>Email: ".$email."</p>
						<p>Password: ".$_POST['password']."</p>
						<p>Please click the link below to activate your account.</p>
						<a href='http://www.ifernatix.com/Ivadora/activate.php?code=".$code."&user=".$userid."'>Activate Account</a>
					";

					//Load phpmailer
		    		require 'vendor/autoload.php';
					include_once 'vendor/phpmailer/class.phpmailer.php';
					include_once 'vendor/phpmailer/class.smtp.php';

		    		$mail = new PHPMailer(true);                             
				    try {
				        //Server settings
				        $mail->SMTPDebug = 2;
					$mail->SMTPAuth = true;                                  
			        $mail->Host = "ssl://smtp.gmail.com";                
			        $mail->Username = 'ivadorashopping@gmail.com';     
			        $mail->Password = 'Ivadora@123';                    
			        $mail->SMTPOptions = array(
			            'ssl' => array(
			            'verify_peer' => false,
			            'verify_peer_name' => false,
			            'allow_self_signed' => true
			            )
			        );                         
			        $mail->SMTPSecure = 'ssl';                           
			        $mail->Port = 465;                                   

			        $mail->setFrom('ivadorashopping@gmail.com');
			        
			        //Recipients
			        $mail->addAddress($email);              
			        $mail->addReplyTo('ivadorashopping@gmail.com');
				       
				        //Content
				        $mail->isHTML(true);                                  
				        $mail->Subject = 'New User Sign Up';
				        $mail->Body    = $message;

				        $mail->send();

				        unset($_SESSION['firstname']);
				        unset($_SESSION['lastname']);
				        unset($_SESSION['email']);
						unset($_SESSION['eventname']);

				        $_SESSION['success'] = 'Account created. Check your email to activate.';
				        header('location: signup.php');

				    } 
				    catch (Exception $e) {
				        $_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
						/* $_SESSION['error'] = $message;*/
				        header('location: signup.php');
				    }

				}
				catch(PDOException $e){
					$_SESSION['error'] = $e->getMessage();
					header('location: register.php');
				}

				$pdo->close();

			}

		}

	}
	else{
		$_SESSION['error'] = 'Fill up signup form first'.$_POST['newregister'];
		header('location: signup.php');
	}

?>