
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Items</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Item List</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          
    <div class="col-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Item List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="itemTable" class="table table-bordered table-hover">
                  <thead>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Date Added</th>
                    <th>Action</th>
                  </thead>
                  <tbody>
                  <?php foreach($items as $key => $value): ?>
                        <tr>
                           <td><?php echo $value->item_name; ?></td>
                           <td><?php echo $value->description; ?></td>
                           <td><?php echo $value->quantity; ?></td>
                           <td><?php echo $value->item_price; ?></td>
                           <td><?php echo $value->add_date; ?></td>
                           <td><button data-id="<?php echo $value->id; ?>" data-toggle="modal" 
                           data-id="<?php echo $value->id; ?>"
                           data-item="<?php echo $value->item_name; ?>"
                           data-description="<?php echo $value->description; ?>"
                           data-quantity="<?php echo $value->quantity; ?>"
                           data-price="<?php echo $value->item_price; ?>"
                           data-target="#updateItem" id="itemButtonUpdate" class="btn btn-sm btn-primary">EDIT</button></td>
                        </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
</div>
</section>
<div class="modal fade" id="updateItem">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Modify Category</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="form_edit_items">
            <div class="modal-body">
              <div class="row">
                      <input type="hidden" id="itemId" name="itemId">
                      <div class="col-md-12">
                        <label for="">Item Name</label>
                        <input type="text" id="iname" name="iname" class="form-control" required>
                      </div>
                      <div class="col-md-12">
                        <label for="">Description</label>
                        <input type="text" id="description" name="description" class="form-control" required>
                      </div>
                      <div class="col-md-12">
                        <label for="">Quantity</label>
                        <input type="text" id="quantity" name="quantity" class="form-control" required>
                      </div>
                      <div class="col-md-12">
                        <label for="">Price</label>
                        <input type="text" id="price" name="price" class="form-control" required>
                      </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->