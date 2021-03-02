<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Categories</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
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
     <h5><i class="icon fas fa-info"></i> Alert!</h5>
     <?php echo $this->session->flashdata("message"); ?>
   </div>
<?php endif; ?>
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="<?php echo base_url("admin/insertCategory"); ?>" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Category Name</label>
                    <input type="text" class="form-control" name="category_name" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Status</label>
                    <select name="status" id="" class="form-control" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Category Image (Optional)</label>
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
    <div class="col-md-6">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Category List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="categoryTable" class="table table-bordered table-hover">
                  <thead>
                    <th>Category Name</th>
                    <th>Status</th>
                    <th>Date Added</th>
                    <th>Action</th>
                  </thead>
                  <tbody>
                    <?php foreach($categories as $key => $value): ?>
                        <tr>
                           <td><?php echo $value->category_name; ?></td>
                           <td><button type="button" class="<?php echo ($value->status == 1 ) ? 'btn btn-block btn-success btn-xs' : 'btn btn-block btn-danger btn-xs' ?>"><?php echo ($value->status == 1 ) ? 'Active' : 'Inactive' ?></button></td>
                           <td><?php echo $value->add_date; ?></td>
                           <td><button data-id="<?php echo $value->id; ?>" class="btn btn-sm btn-primary">EDIT</button></td>
                        </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
</div>
</section>
