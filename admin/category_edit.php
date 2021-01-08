<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		
		$filename = $currentdatetime.$_FILES['photo']['name'];
		$now = date('Y-m-d');
		if(!empty($filename)){
			$uploaddone = move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
		}

		try{
			if($uploaddone){
			$stmt = $conn->prepare("UPDATE category SET name=:name, picture=:picture WHERE id=:id");
			$stmt->execute(['name'=>$name, 'picture'=>$filename, 'id'=>$id]);	
			}else{
			$stmt = $conn->prepare("UPDATE category SET name=:name WHERE id=:id");
			$stmt->execute(['name'=>$name, 'id'=>$id]);
			}
			
			$_SESSION['success'] = 'Category updated successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}
		
		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up edit category form first';
	}

	header('location: category.php');

?>