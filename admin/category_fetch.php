<?php
	include 'includes/session.php';

	$output = '';
	$selected ='';
	$conn = $pdo->open();
	if(isset($_POST['id'])){
		$id = explode(',', $_POST['id']);
	}
	$stmt = $conn->prepare("SELECT * FROM category");
	$stmt->execute();

	foreach($stmt as $row){
		if(isset($_POST['id'])){ if (in_array($row['id'], $id)) { $selected ='selected'; }else { $selected =''; }}
		$output .= "
			<option ".$selected." value='".$row['id']."' class='append_items'>".$row['name']."</option>
		";
	}

	$pdo->close();
	echo json_encode($output);

?>