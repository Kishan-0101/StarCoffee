<?php
include("inc/header.php");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header dark-mode">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Products</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
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
              <h3 class="card-title">Add new product</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="product_process.php" method="post" enctype="multipart/form-data">
              <div class="card-body">

                <?php
                // if (!empty($_SESSION['error'])) {
                //   # code...
                //   foreach ($_SESSION['error'] as $err) {
                //     # code...
                //     echo '<font color="red">' . $err . '</font><br/>';
                //   }
                // }
                
                if (isset($_SESSION['success'])) {
                  echo '<p class="alert alert-success">' . $_SESSION['success'] . '</p>';
                  unset($_SESSION['success']);
                }
                ?>

                <div class="form-group">
                  <label for="pro_name">Product Name</label>
                  <input type="text" class="form-control" name="pro_name" id="pro_name" placeholder="Add product name.">
                  <?php
                  if (isset($_SESSION['error']['pro_name'])) {
                    # code...
                    echo '<font color="red">' . $_SESSION['error']['pro_name'] . '</font>';
                    unset($_SESSION['error']['pro_name']);
                  }
                  ?>
                </div>

                <div class="form-group">
                  <label for="pro_category">Product Category</label>
                  <select class="form-control" name="cat_name" id="category">
                    <option value="0">Select Category</option>
                    <?php
                    $query = "SELECT * FROM category WHERE category_status = 1";

                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                      echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                    }
                    ?>
                  </select>
                  <?php
                  if (isset($_SESSION['error']['cat_name'])) {
                    # code...
                    echo '<font color="red">' . $_SESSION['error']['cat_name'] . '</font>';
                    unset($_SESSION['error']['cat_name']);
                  }
                  ?>
                </div>

                <div class="form-group">
                  <label for="pro_price">Product Price</label>
                  <input type="text" class="form-control" name="pro_price" id="pro_price"
                    placeholder="Add product price.">
                  <?php
                  if (isset($_SESSION['error']['pro_price'])) {
                    # code...
                    echo '<font color="red">' . $_SESSION['error']['pro_price'] . '</font>';
                    unset($_SESSION['error']['pro_price']);
                  }
                  ?>
                </div>

                <div class="form-group">
                  <label for="pro_desc">Product Description</label>
                  <textarea name="pro_desc" id="pro_desc" class="form-control" placeholder="Add product's description."
                    rows="3"></textarea>
                  <?php
                  if (isset($_SESSION['error']['pro_desc'])) {
                    echo '<font color="red">' . $_SESSION['error']['pro_desc'] . '</font>';
                    unset($_SESSION['error']['pro_desc']);
                  }
                  ?>
                </div>

                <div class="form-group">
                  <label for="pro_img">Product Image</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" name="product_img" class="custom-file-input" id="pro_img">
                      <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                  </div>
                  <?php
                  if (isset($_SESSION['error']['product_img'])) {
                    echo '<font color="red">' . $_SESSION['error']['product_img'] . '</font>';
                    unset($_SESSION['error']['product_img']);
                  }
                  ?>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-info">Submit</button>
              </div>
              <?php
              if (!empty($_SESSION['error'])) {
                # code...
                unset($_SESSION['error']);
              }
              ?>
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