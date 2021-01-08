
<?php
	include 'includes/session.php';
	include 'includes/functions.php';

	if(isset($_POST['add'])){
		$name = $_POST['name'];
		$slug = replacewith($name);

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM category WHERE name=:name");
		$stmt->execute(['name'=>$name]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			$_SESSION['error'] = 'Category already exist';
		}
		else{
			$filename = $currentdatetime.$_FILES['photo']['name'];
			$now = date('Y-m-d');
			if(!empty($filename)){
				move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
			}
			try{
				$stmt = $conn->prepare("INSERT INTO category (name, cat_slug, picture) VALUES (:name, :cat_slug, :picture)");
				$stmt->execute(['name'=>$name, 'picture'=>$filename, 'cat_slug'=>$slug]);
				$_SESSION['success'] = 'Category added successfully';
			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up category form first';
	}

	header('location: category.php');

?>