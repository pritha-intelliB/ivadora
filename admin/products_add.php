<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['add'])){
		$name = $_POST['name'];
		$slug = slugify($name);
		$category = $_POST['category'][0];
		$categories = implode(',', $_POST['category']);
		$price = $_POST['price'];
		$description = $_POST['description'];
		$filename = $_FILES['photo']['name'];
		$sellerid = $_POST['seller'];
		$currentdate = date('Y-m-d');
		
		$totaldiscount = $_POST['discprice'];
		$totalstock = $_POST['stock'];
		$updateon = date('Y-m-d');

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM products WHERE slug=:slug");
		$stmt->execute(['slug'=>$slug]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			$_SESSION['error'] = 'Product already exist';
		}
		else{
			if(!empty($filename)){
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$new_filename = $slug.'.'.$ext;
				move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$new_filename);	
			}
			else{
				$new_filename = '';
			}

			try{
				$stmt = $conn->prepare("INSERT INTO products (category_id, categories, seller_id, name, description, slug, price, discount, stock, photo, date, update_on) VALUES (:category, :categories, :seller_id, :name, :description, :slug, :price, :totaldiscount, :totalstock, :photo, :date, :updateon)");
				$stmt->execute(['category'=>$category, 'categories'=>$categories, 'seller_id'=>$sellerid, 'name'=>$name, 'description'=>$description, 'slug'=>$slug, 'price'=>$price, 'totaldiscount'=>$totaldiscount, 'totalstock'=>$totalstock, 'photo'=>$new_filename, 'date'=>$currentdate, 'updateon'=>$updateon]);
				$_SESSION['success'] = 'User added successfully';

			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up product form first';
	}

	header('location: products.php');

?>