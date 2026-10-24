<?php
include("inc/header.php");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper dark-mode">
  <!-- Content Header (Page header) -->
  <section class="content-header dark-mode">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Category</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Category</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content dark-mode">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Add new category</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="category_process.php" method="post" enctype="multipart/form-data">
              <div class="card-body">

                <?php
                if (isset($_SESSION['success'])) {
                  echo '<p class="alert alert-success">' . $_SESSION['success'] . '</p>';
                  unset($_SESSION['success']);
                }
                ?>

                <div class="form-group">
                  <label for="category">Category Name</label>
                  <input type="text" class="form-control" name="cat_name" id="category" placeholder="Add new category">
                  <?php
                  if (isset($_SESSION['error']['cat_name'])) {
                    echo '<font color="red">' . $_SESSION['error']['cat_name'] . '</font>';
                    unset($_SESSION['error']['cat_name']);
                  }
                  ?>
                </div>
                <div class="form-group">
                  <label for="category">Category Description</label>
                  <textarea class="form-control" name="cat_desc" id="category" placeholder="Add Description"
                    rows="3"></textarea>
                  <?php
                  if (isset($_SESSION['error']['cat_desc'])) {
                    echo '<font color="red">' . $_SESSION['error']['cat_desc'] . '</font>';
                    unset($_SESSION['error']['cat_desc']);
                  }
                  ?>
                </div>
                <div class="form-group">
                  <label for="category_img">Image</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="category_img" name="category_image">
                      <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                  </div>
                  <?php
                  if (isset($_SESSION['error']['category_image'])) {
                    echo '<font color="red">' . $_SESSION['error']['category_image'] . '</font>';
                    unset($_SESSION['error']['category_image']);
                  }
                  ?>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-info">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include("inc/footer.php");
?>