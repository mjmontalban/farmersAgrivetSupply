
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Analytics</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Analytics</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <?php if(empty($analytics)): ?>
        <div class="alert alert-warning alert-dismissible">
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
     <h5><i class="icon fas fa-warning"></i> Info!</h5>
     <p>--No Data</p>
   </div>
       <?php endif; ?>
            <div class="row">
                <?php foreach($analytics as $key => $value): ?>
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="card-title">
                                    <?php echo $value->user; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <p>QUANTITY : <?php echo $value->quantity; ?></p>
                                <p>SALES : <?php echo $value->sales; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
      </div>
</section>

</div>
