<?php
	include 'includes/session.php';
	
	$targetDir = "../gallery/";
    $allowTypes = array('jpg','png','jpeg','gif');
	 $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
	 function get_extension($file) {
		 $extension = end(explode(".", $file));
		 return $extension ? $extension : false;
		}

	if(isset($_POST['galleryupload'])){
		$id = $_POST['id'];
		$filename = $_FILES['gallery']['name'];
	
		
	if(!empty(array_filter($_FILES['gallery']['name']))){
		foreach($_FILES['gallery']['name'] as $key=>$val){
            // File upload path
			$fileName = basename($_FILES['gallery']['name'][$key]);
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$mainfileName = basename($_FILES['gallery']['name'][$key]);
			$finalfileName = $id.'_gallery_'.$mainfileName.$ext;            
            $targetFilePath = $targetDir . $finalfileName;
            
            // Check whether file type is valid
            //$fileType = pathinfo($fileName,PATHINFO_EXTENSION);  $_SESSION['error'] = 'Please select a file to upload '.pathinfo($fileName,PATHINFO_EXTENSION);
			$fileType = get_extension($_FILES['gallery']['name'][$key]);  
            if(in_array($fileType, $allowTypes)){  
                // Upload file to server
				$uploaddone = move_uploaded_file($_FILES['gallery']['tmp_name'][$key], '../gallery/'.$finalfileName);
                if($uploaddone){
					$conn = $pdo->open();
                    try{						
						$currentdttime = date('Y-m-d H:i:s');
						$stmt = $conn->prepare("INSERT INTO products_gallery SET product_id=:productid, picture=:picturenm, uploadedon=:uptime");
						$stmt->execute(['productid'=>$id,'picturenm'=>$finalfileName,'uptime'=>$currentdttime]);						
						
						if(isset($_SESSION['sellers'])){
							$message = "
								<h3>Thank you for Updating the Gallery.</h3>
								<p>We will Review your product as soon as possible.</p>
							";				
							$_SESSION['success'] = 'Gallery added successfully'.$message;
							
				
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
							$_SESSION['success'] = 'Gallery added successfully';
						}
		
					}
					catch(PDOException $e){
						$_SESSION['error'] = $e->getMessage();
					}
					$pdo->close();
                }else{
                    $_SESSION['error'] .= $_FILES['gallery']['name'][$key].', ';
                }
            }else{
                $_SESSION['error'] .= $_FILES['gallery']['name'][$key].', ';
            }
        }
		
			
	}
}else{
	$_SESSION['error'] = 'Please select a file to upload.';
	}
	header('location: products.php');
?>