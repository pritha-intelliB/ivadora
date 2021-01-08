<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$slug = slugify($name);
		$category = $_POST['category'][0];
		$categories = implode(',', $_POST['category']);
		$price = $_POST['price'];
		$description = $_POST['description'];		
		
		$totaldiscount = $_POST['discprice'];
		$totalstock = $_POST['stock'];
		$updateon = date('Y-m-d');
		
		$midifies = '<strong>Edited Fields Are :</strong><br>';
		$fieldstmt = $conn->prepare("SELECT * FROM products WHERE id=:id");
		$fieldstmt->execute(['id'=>$id]);
		$field = $fieldstmt->fetch();
		if($field['categories']!=$categories){$midifies .='Categories<br>';}
		if($field['name']!=$name){$midifies .='Name<br>';}
		if($field['description']!=$description){$midifies .='Description<br>';}
		if($field['price']!=$price){$midifies .='Price<br>';}
		if($field['discount']!=$totaldiscount){$midifies .='Discount<br>';}
		if($field['stock']!=$totalstock){$midifies .='Stock<br>';}
		

		$conn = $pdo->open();

		try{
			if(isset($_SESSION['sellers'])){
				$approved  = '0';
				$stmt = $conn->prepare("UPDATE products SET name=:name, slug=:slug, categories=:categories, category_id=:category, price=:price, discount=:totaldiscount, stock=:totalstock, description=:description, approved=:approved, update_on=:updateon WHERE id=:id");
				$stmt->execute(['name'=>$name, 'slug'=>$slug, 'categories'=>$categories, 'category'=>$category, 'price'=>$price, 'totaldiscount'=>$totaldiscount, 'totalstock'=>$totalstock, 'description'=>$description, 'approved'=>$approved, 'updateon'=>$updateon, 'id'=>$id]);
			}else{
				$approved  = '1';
				$stmt = $conn->prepare("UPDATE products SET name=:name, slug=:slug, categories=:categories, category_id=:category, price=:price, discount=:totaldiscount, stock=:totalstock, description=:description, approved=:approved, update_on=:updateon WHERE id=:id");
				$stmt->execute(['name'=>$name, 'slug'=>$slug, 'categories'=>$categories, 'category'=>$category, 'price'=>$price, 'totaldiscount'=>$totaldiscount, 'totalstock'=>$totalstock, 'description'=>$description, 'approved'=>$approved, 'updateon'=>$updateon, 'id'=>$id]);
			}
			if(isset($_SESSION['sellers'])){
				$message = "
					<h3>Thank you for Updating.</h3>
					<p>".$midifies."</p>
					<p>We will Review your product as soon as possible.</p>
				";				
				$_SESSION['success'] = 'Product updated successfully'.$message;
				
	
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
				$_SESSION['success'] = 'Product updated successfully';	
			}
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}
		
		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up edit product form first';
	}

	header('location: products.php');

?>