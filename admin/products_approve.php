<?php
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
		$approved = 1;
		$updateon = date('Y-m-d');

		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("UPDATE products SET name=:name, slug=:slug, category_id=:category, categories=:categories, price=:price, description=:description, approved=:approved, update_on=:updateon WHERE id=:id");
			$stmt->execute(['name'=>$name, 'slug'=>$slug, 'category'=>$category, 'categories'=>$categories, 'price'=>$price, 'description'=>$description, 'approved'=>$approved, 'updateon'=>$updateon, 'id'=>$id]);
			$_SESSION['success'] = 'Product approve successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}
		
		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up approve product form first';
	}

	header('location: products.php');

?>