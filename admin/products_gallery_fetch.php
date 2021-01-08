
<?php
	include 'includes/session.php';
	
	$output = '';
	$selected ='';
	$conn = $pdo->open();
	if(isset($_POST['id'])){
	$id = $_POST['id'];
	$stmt = $conn->prepare("SELECT * FROM products_gallery WHERE product_id='".$id."'");
	$stmt->execute();
$output .= "
<script>
 $(function(){
  $('.popup-gallery').magnificPopup({
      delegate: 'a',
      type: 'image',
      tLoading: 'Loading image #%curr%...',
      mainClass: 'mfp-img-mobile',
      gallery: {
          enabled: true,
          navigateByImgClick: true,
          preload: [0,1] // Will preload 0 - before current, and 1 after the current image
          },
          image: {
                  tError: '<a href=\"%url%\">The image #%curr%</a> could not be loaded.',
                  titleSrc: function(item) {
                      return item.el.attr('title') + '<small>by saler</small>';
                  }
              }
        })
	});
</script>
<ul class=\"gallery popup-gallery\">";
	foreach($stmt as $row){		
		$output .= "
			<li class='gallery_items'><span class=\"picdelete\" data-id=\"".$row['id']."\">DEL</span><a href=../gallery/".$row['picture']." title=\"Product Picture\"><img src=../gallery/".$row['picture']." height=\"80px\"></a></li>
		";
	}
$output .= "</ul>";
	$pdo->close();
	echo json_encode($output);
	}
?>
