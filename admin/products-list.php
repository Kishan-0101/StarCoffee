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
          <h1>All Products</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">All Coffees</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content dark-mode">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">All Product displayes here.</h3>
            </div>
            <!-- /.card-header -->
            <?php
            if (isset($_SESSION['success'])) {
              echo '<div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-check"></i> Success!</h5>
                      ' . $_SESSION['success'] . '
                    </div>';
              unset($_SESSION['success']);
            }
            ?>
            <?php
            if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
              echo '<div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-ban"></i> Error!</h5>';
              foreach ($_SESSION['error'] as $err) {
                echo '<div>' . htmlspecialchars($err) . '</div>';
              }
              echo '</div>';
              unset($_SESSION['error']);
            }
            ?>
            <div class="card-body">
              <table id="dtable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr. No.</th>
                    <th>Coffees</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Manage</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  $query = "SELECT p.*, c.category_name 
                            FROM products p
                            LEFT JOIN category c ON p.p_category = c.category_id
                            ORDER BY p.p_id ASC";

                  $result = mysqli_query($conn, $query);

                  while ($row = mysqli_fetch_assoc($result)) {
                    # code...
                    echo '<tr>
                              <td>' . $row['p_id'] . '</td>
                              <td><a href="../index.php#popular" target="_blank">' . $row['p_name'] . '</a></td>
                              <td>' . $row['category_name'] . '</td>
                              <td>₹' . $row['p_price'] . '</td>
                              <td>
                                <img src="../product-images/' . $row['p_image'] . '" style="width: 50px"
                                  alt="Product image representing ' . $row['p_name'] . ' displayed in the StarCoffee admin panel" />
                              </td>
                              <td>' . $row['p_short_description'] . '</td>
                              <td>
                                <a href="edit-product.php?id=' . $row['p_id'] . '" class="btn btn-success btn-sm">Edit</a>';
                    if ($row['p_status'] == 0) {
                      # code...
                      echo ' <a href="disable-product.php?id=' . $row['p_id'] . '&status=1" class="btn btn-primary btn-sm" onclick="return confirm(\'Are you sure you want to enable this product?\')">Enable</a>';
                    } else {
                      # code...
                      echo ' <a href="disable-product.php?id=' . $row['p_id'] . '&status=0" class="btn btn-warning btn-sm" onclick="return confirm(\'Are you sure you want to disable this product?\')">Disable</a>';
                    }
                    echo ' <a href="delete-product.php?id=' . $row['p_id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                              </td>
                          </tr>';
                  }
                  ?>

                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr. No.</th>
                    <th>Coffees</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Manage</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include("inc/footer.php");
?>