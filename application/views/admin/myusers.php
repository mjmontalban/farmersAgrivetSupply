
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
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
     <h5><i class="icon fas fa-info"></i> Info!</h5>
     <?php echo $this->session->flashdata("message"); ?>
   </div>
<?php endif; ?>
        <div class="row">
          <!-- left column -->
          
    <div class="col-12">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Users List</h3>
                <button style="float: right !important;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addUserModal"><i class="fas fa-user"></i>  Add User</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="usersTable" class="table table-bordered table-hover">
                  <thead>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Last Login</th>
                    <th>Status</th>
                    <th>ACTION</th>
                  </thead>
                  <tbody>
                  
                </table>
              </div>
            </div>
        </div>
</div>
</section>
<div class="modal fade" id="addUserModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add User</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url("auth/create_user"); ?>" method="POST">
            <div class="modal-body">
              <div class="row">
                      <div class="col-md-12">
                        <label for="">First Name</label>
                        <input type="text" name="firstname" class="form-control" required>
                      </div>
                      <div class="col-md-12">
                      <label for="">Last Name</label>
                      <input type="text" name="lastname" class="form-control" required>
                      </div>
                      <div class="col-md-12">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" required>
                      </div>
                      <div class="col-md-12">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" required>
                      </div>
                      <div class="col-md-12">
                        <label for="">Confirm Password</label>
                        <input type="password" name="password_confirm" class="form-control" required>
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


      <div class="modal fade show" id="modifyAccessModal" style="display: none; padding-right: 16px;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Access Setting</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <form id="modifyAccessForm">
            <input type="hidden" id="user_id" name="user_id">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <select name="setActive" class="form-control">
                    <option value="0">Deactivate</option>
                    <option value="1">Activate</option>
                  </select>
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