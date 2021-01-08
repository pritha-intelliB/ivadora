<?php include 'includes/session.php'; ?>
<?php
  $where = '';
  if(isset($_SESSION['type'])&&$_SESSION['type']=='1'){ $subwhere = '';}else{$subwhere = "seller_id='".$_SESSION['sellers']."'";}
  
  if(isset($_GET['category'])){
    $catid = $_GET['category'];
	if($_SESSION['type']=='2'){
    //$where = 'WHERE category_id ='.$catid.' AND '.$subwhere;
	//$where = "WHERE categories LIKE '%".$catid.",%' AND ".$subwhere;
	$where = "WHERE FIND_IN_SET('".$catid."',categories) AND ".$subwhere;
	
	}else{
		//$where = 'WHERE category_id ='.$catid;
		$where = "WHERE FIND_IN_SET('".$catid."',categories)";
	}
  }else{
	  $catid ='';
	  if($_SESSION['type']=='2'){
	  $where = 'WHERE '.$subwhere;
	  }else{
		  
	  }
  }

?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Product List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Products</li>
        <li class="active">Product List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat" id="addproduct"><i class="fa fa-plus"></i> New</a>
              <div class="pull-right">
                <form class="form-inline">
                  <div class="form-group">
                    <label>Category: </label>
                    <select class="form-control input-sm" id="select_category">
                      <option value="0">ALL</option>
                      <?php
                        $conn = $pdo->open();

                        $stmt = $conn->prepare("SELECT * FROM category");
                        $stmt->execute();

                        foreach($stmt as $crow){
                          $selected = ($crow['id'] == $catid) ? 'selected' : ''; 
                          echo "
                            <option value='".$crow['id']."' ".$selected.">".$crow['name']."</option>
                          ";
                        }

                        $pdo->close();
                      ?>
                    </select>
                  </div>
                </form>
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Reference ID</th>
                  <th>Product Name</th>
                  <th>Stock</th>
                  <th>Photo</th>
                  <th>Description/Gallery</th>
                  <th>Price</th>
                  <th>Categories</th>
                  <th>Views Today</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $now = date('Y-m-d'); //echo "SELECT * FROM products $where ORDER BY date DESC";
                      $stmt = $conn->prepare("SELECT * FROM products $where ORDER BY date DESC");
                      $stmt->execute();
                      foreach($stmt as $row){
						$approved = $row['approved']; if($approved=='0'){$bgcolor='style="background-color:#f8f4ae!important;"';}else{$bgcolor='';}
                        $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/noimage.jpg';
                        $counter = ($row['date_view'] == $now) ? $row['counter'] : 0;
						$saler = $conn->prepare("SELECT firstname,lastname FROM users WHERE id='".$row['seller_id']."'");
						$saler->execute();
						$salerrow =  $saler->fetch();
						
						$string = $row['categories'];
						$result = array();
						unset($result);
						$str_arr = explode (",", $string);  
						foreach ( $str_arr as $catid ) {
						  $stmtcat = $conn->prepare("SELECT * FROM category WHERE id='".$catid."'");
						  $stmtcat->execute();
						  $rowcat = $stmtcat->fetch();
						  $result[] = $rowcat['name'];
						  //$catnames .=$rowcat['name'].", ";
						}
						$percent = $row['price'] - (($row['price']*$row['discount'])/100) ;
                        echo "
                          <tr ".$bgcolor.">
						  <td>".$row['id']."</td>
                            <td>".$row['name']."<br><strong>Saler :</strong> ".$salerrow['firstname']." ".$salerrow['lastname']."<strong> Creation :</strong> ".date('d-m-Y',strtotime($row['date']))." <strong>Updated :</strong> ".date('d-m-Y',strtotime($row['update_on']))."</td>
							<td>".$row['stock']."</td>
                            <td>                             
							  <a class='imgpopup' href='#thumb'><img src='".$image."' height='30px' width='30px'><span><img src='".$image."' height='220px' width='220px' /></span></a>
                              <span class='pull-right'><a href='#edit_photo' class='photo' data-toggle='modal' data-id='".$row['id']."'><i class='fa fa-edit'></i></a></span>
                            </td>
                            <td><a href='#description' data-toggle='modal' class='btn btn-info btn-sm btn-flat desc' data-id='".$row['id']."'><i class='fa fa-search'></i> View</a></td>
                            <td>&#8377;  ".number_format($row['price'], 2)." ( Discount : ".$row['discount']."% )<br> After Discount : &#8377;  ".number_format($percent, 2)."</td>
							<td>".implode(', ', $result)."</td>
                            <td>".$counter."</td>
                            <td>";
							if($_SESSION['type']=='1' && $row['approved']=='0'){
                              echo "<button class='btn btn-warning btn-sm approve btn-flat' data-id='".$row['id']."'><i class='fa fa-check'></i> Approve</button> ";}else{
							  echo "<button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Edit</button>";}
                              echo " <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'><i class='fa fa-trash'></i> Delete</button>
                            </td>
                          </tr>
                        ";
                      }
                    }
                    catch(PDOException $e){
                      echo $e->getMessage();
                    }

                    $pdo->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
     
  </div>
  	<?php include 'includes/footer.php'; ?>
    <?php include 'includes/products_modal.php'; ?>
    <?php include 'includes/products_modal2.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRowEdit(id);
  });

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });
  $(document).on('click', '.picdelete', function(e){
    e.preventDefault();    
    var id = $(this).data('id');
	var parents = $(this).parent();
    /*var parent = $(this).parent();
	parents.css("border", "5px solid black");*/
	$.ajax({
		type: "post",
		url: "products_gallery_delete.php",
		cache: true,
		data: 'galleryid=' + id,
		success: function(response) { 
				if (response == 'true') {
					//parent('li').css("border", "5px solid black");
					//$(this).parent('li').css("border", "5px solid black");
					parents.slideUp("slow", function() { $(this).remove(); } );
				}
			else{
				alert('Exception while request..');
			}
		},

		error: function() {
			alert('Error while request..');
		}
	});
  });

  $(document).on('click', '.photo', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.desc', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
	getGallery(id)
  });
  
  $(document).on('click', '.approve', function(e){
    e.preventDefault();
    $('#papprove').modal('show');
    var id = $(this).data('id');
    getRowApprove(id);
  });

  $('#select_category').change(function(){
    var val = $(this).val();
    if(val == 0){
      window.location = 'products.php';
    }
    else{
      window.location = 'products.php?category='+val;
    }
  });

  $('#addproduct').click(function(e){
    e.preventDefault();
    getCategory();
  });

  $("#addnew").on("hidden.bs.modal", function () {
      $('.append_items').remove();
  });

  $("#edit").on("hidden.bs.modal", function () {
      $('.append_items').remove();
  });

});
function getRowEdit(id){ 
  $.ajax({
    type: 'POST',
    url: 'products_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.prodid').val(response.prodid);
      $('#edit_name1').val(response.prodname);
      //$('#catselected1').val(response.category_id).html(response.catname);
      $('#edit_price1').val(response.price);
	  $('#edit_discprice1').val(response.discount);
	  $('#edit_stock1').val(response.stock);
      CKEDITOR.instances["editor2"].setData(response.description);
      getCategories(response.categories);
	  $('#desc').html(response.description);
      $('.name').html(response.prodname);
    }
  });
}
function getCategories(categories){
	var categories;
  $.ajax({
    type: 'POST',
    url: 'category_fetch.php',
	data: {id:categories},
    dataType: 'json',
    success:function(response){      
      $('#edit_category1').html(response);
	  //$('#category').append(response);
    }
  });
}
function getRow(id){ 
  $.ajax({
    type: 'POST',
    url: 'products_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.prodid').val(response.prodid);
      $('#edit_name1').val(response.prodname);
      $('#catselected1').val(response.category_id).html(response.catname);
      $('#edit_price1').val(response.price);
      CKEDITOR.instances["editor2"].setData(response.description);
      getCategory();
	  $('#desc').html(response.description);
      $('.name').html(response.prodname);
    }
  });
}

function getCategory(){
  $.ajax({
    type: 'POST',
    url: 'category_fetch.php',
    dataType: 'json',
    success:function(response){      
      $('#edit_category1').html(response);
	  $('#category').html(response);
    }
  });
}


function getRowApprove(id){
  $.ajax({
    type: 'POST',
    url: 'products_rowapprove.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('#desc').html(response.description);
      $('.name').html(response.prodname);
      $('.prodid').val(response.prodid);
      $('#edit_name').val(response.prodname);
      //$('#catselected').val(response.category_id).html(response.catname);
      $('#edit_price').val(response.price);
      CKEDITOR.instances["editor3"].setData(response.description);
      getCategoriesApprove(response.categories);
    }
  });
}
function getCategoriesApprove(categories){
	var categories;
  $.ajax({
    type: 'POST',
    url: 'category_fetch.php',
	data: {id:categories},
    dataType: 'json',
    success:function(response){      
      $('#edit_category2').html(response);
	  //$('#category').append(response);
    }
  });
}
function getCategory2(){
  $.ajax({
    type: 'POST',
    url: 'category_fetch.php',
    dataType: 'json',
    success:function(response){
		
      $('#edit_category2').html(response);
	  //$('#category2').append(response);
    }
  });
}

function getGallery(productid){
	var productid;
  $.ajax({
    type: 'POST',
    url: 'products_gallery_fetch.php',
	data: {id:productid},
    dataType: 'json',
    success:function(response){      
      $('#view_gallery').html(response);
	  //$('#category').append(response);
    }
  });
}

</script>
</body>
</html>
