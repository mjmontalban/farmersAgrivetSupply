
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
              <li class="breadcrumb-item active">Add Item</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <?php if($this->session->flashdata("message")): ?>
  
  <div class="alert alert-success alert-dismissible">
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
     <h5><i class="icon fas fa-success"></i> Alert!</h5>
     <?php echo $this->session->flashdata("message"); ?>
   </div>
<?php endif; ?>
        <div class="row">
          <!-- left column -->
<div class="col-12">
            <!-- general form elements -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Item</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="<?php echo base_url("admin/insertItems"); ?>" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Item Name</label>
                    <input type="text" class="form-control" name="item_name" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Select Category</label>
                    <select name="category" id="" class="form-control" required>
                        <?php foreach($categories as $key => $value): ?>
                           <option value="<?php echo $value->id; ?>"><?php echo $value->category_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <input type="text" class="form-control" name="description" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Quantity</label>
                    <input type="text" class="form-control" name="quantity" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Supplier Price Cost (Overall cost of this item)</label>
                    <input type="text" class="form-control" name="spc" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Pricing Options</label>
                    <select name="options" id="pricingOptions" class="form-control">
                          <option value="0">- Fix Price</option>
                          <option value="1">- Per Bundle</option>
                          <option value="2">- Per Sack/Kilo</option>
                    </select>
                  </div>
                  <div class="form-group">
                  <p style="font-weight: bold" class="text-center">Set Price List</p>
                    <div class="row" id="priceList">
                      <div class="col-md-12">
                          <label for="exampleInputPassword1">Price</label>
                          <input type="text" class="form-control numOnly" name="price" required>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                  <p style="font-weight: bold" class="text-center">Set Bidding List</p>
                     <div class="row" id="bidding">
                          <div class="col-md-12">
                            <label for="exampleInputPassword1">Minimum Bidding</label>
                            <input type="text" class="form-control numOnly" name="fix_bid" required>
                          </div>
                     </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Item Image (Optional)</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
    </div>

    </div>
</section>