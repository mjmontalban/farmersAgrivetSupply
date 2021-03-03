
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Purchase</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Purchasing</li>
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
                <h3 class="card-title">Purchased List</h3>  
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                 <div id="mainContent">
                    <div class="row">
                        <div class="col-md-4">
                        <label for="">Select Item</label>
                            <select name="item[]"  class="form-control select_item">
                            </select>
                        </div>
                        <div class="col-md-4" id="what">
                            <label for="">Quantity</label>
                            <input type="text" name="quantity[]" class="form-control">
                        </div>
                        <div class="col-md-4">
                        <label for="">Status</label></br>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" name="check[]" id="checkboxPrimary1">
                            <label for="checkboxPrimary1">
                                Confirm
                            </label>
                      </div>
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mt-2">
                       <button class="btn btn-secondary btn-sm" id="add_row">Add Row</button>
                    </div>
                </div>
              </div>
            </div>
        </div>
</div>
</section>
