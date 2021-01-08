<?php
	include 'includes/session.php';
	
	$output = '';
	$targetDir = "../gallery/";
	$conn = $pdo->open();
	if(isset($_POST['galleryid'])){
	$id = $_POST['galleryid'];
	$conn = $pdo->open();

		try{
			
			$stmt = $conn->prepare("SELECT * FROM products_gallery WHERE id=:id");
			$stmt->execute(['id'=>$id]);
			$row = $stmt->fetch();
			$filename = $row['picture'];			
			$stmt2 = $conn->prepare("DELETE FROM products_gallery WHERE id=:id");
			$stmt2->execute(['id'=>$id]);
			
			$path =$targetDir.$filename;
			unlink($path);		

			$_SESSION['success'] = 'Product Picture deleted successfully';
			$output = 'true';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}
	echo ($output);
?>
