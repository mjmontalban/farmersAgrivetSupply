
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Category : <?php echo $catName; ?></h1>
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
                    <th>Discription</th>
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
