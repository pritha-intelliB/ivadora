<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Deleting...</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="products_delete.php">
                <input type="hidden" class="prodid" name="id">
                <div class="text-center">
                    <p>DELETE PRODUCT</p>
                    <h2 class="bold name"></h2>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Delete</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Edit Product</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="products_edit.php">
                <input type="hidden" class="prodid" name="id">
                <div class="form-group">
                  <label for="edit_name1" class="col-sm-1 control-label">Name</label>

                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_name1" name="name">
                  </div>

                  <label for="edit_category1" class="col-sm-1 control-label" style="padding-right:7px;">Category</label>

                  <div class="col-sm-5">
                    <select multiple class="form-control" id="edit_category1" name="category[]">
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="edit_price1" class="col-sm-1 control-label">Price</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_price1" name="price">
                  </div>
                  <label for="edit_price1" class="col-sm-1 control-label">Stock</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_stock1" name="stock" value="0">
                  </div>
                  
                </div>
                
                <div class="form-group">
                  <label for="edit_price1" class="col-sm-1 control-label">Discount</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="edit_discprice1" name="discprice" value="0">
                  </div>
                  <div class="col-sm-1">%</div>
                </div>
                
                <div class="form-group">
                  <label for="edit_price1" class="col-sm-1 control-label">Description</label>
                  <div class="col-sm-12">
                    <textarea id="editor2" name="description" rows="10" cols="80"></textarea>
                  </div>
                  
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>



