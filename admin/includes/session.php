<?php
	include '../includes/conn.php';
	session_start();
if($_SESSION['type'] == '1'){
	if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
		header('location: ../index.php');
		exit();
	}
}
if($_SESSION['type'] == '2'){
	if(!isset($_SESSION['sellers']) || trim($_SESSION['sellers']) == ''){
		header('location: ../index.php');
		exit();
	}
}
	$conn = $pdo->open();

	$stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
	if($_SESSION['type'] == '1'){$stmt->execute(['id'=>$_SESSION['admin']]);}
	if($_SESSION['type'] == '2'){$stmt->execute(['id'=>$_SESSION['sellers']]);}
	$admin = $stmt->fetch();

	$pdo->close();

?>