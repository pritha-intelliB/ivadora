<?php
	include 'includes/session.php';

	if(isset($_POST['upload'])){
		$id = $_POST['id'];
		$filename = $_FILES['photo']['name'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT * FROM products WHERE id=:id");
		$stmt->execute(['id'=>$id]);
		$row = $stmt->fetch();

		if(!empty($filename)){
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$new_filename = $row['slug'].'_'.time().'.'.$ext;
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$new_filename);	
		}
		
		try{
			$stmt = $conn->prepare("UPDATE products SET photo=:photo WHERE id=:id");
			$stmt->execute(['photo'=>$new_filename, 'id'=>$id]);
			
			if(isset($_SESSION['sellers'])){
				$message = "
					<h3>Thank you for Updating the Gallery.</h3>
					<p>We will Review your product as soon as possible.</p>
				";				
				$_SESSION['success'] = 'Product Main photo updated successfully'.$message;
				
	
				//Load phpmailer
				/*require 'vendor/autoload.php';
	
				$mail = new PHPMailer(true);                             
				try {
					//Server settings
					$mail->isSMTP();                                     
					$mail->Host = 'smtp.gmail.com';                      
					$mail->SMTPAuth = true;                               
					$mail->Username = 'testsourcecodester@gmail.com';     
					$mail->Password = 'mysourcepassfd';                    
					$mail->SMTPOptions = array(
						'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
						)
					);                         
					$mail->SMTPSecure = 'ssl';                           
					$mail->Port = 465;                                   
	
					$mail->setFrom('testsourcecodester@gmail.com');
					
					//Recipients
					$mail->addAddress($email);              
					$mail->addReplyTo('testsourcecodester@gmail.com');
				   
					//Content
					$mail->isHTML(true);                                  
					$mail->Subject = 'ECommerce Site Sign Up';
					$mail->Body    = $message;
	
					$mail->send();
	
				} 
				catch (Exception $e) {
					$_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;				
				}*/
			}else{
				$_SESSION['success'] = 'Product Main photo updated successfully';
			}
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();

	}
	else{
		$_SESSION['error'] = 'Select product to update photo first';
	}

	header('location: products.php');
?>