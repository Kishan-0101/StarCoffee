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
          <h1>All Category</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">All Category</li>
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
              <h3 class="card-title">All Coffee types list.</h3>
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
                    <th>Name</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  $query = "SELECT * FROM category ORDER BY category_name ASC";

                  $result = mysqli_query($conn, $query);

                  $count = 1;

                  while ($row = mysqli_fetch_assoc($result)) {
                    # code...
                    echo '<tr>
                              <td>' . $count . '</td>
                              <td>' . $row['category_name'] . '</td>
                                <td>
                                <img src="../category-images/' . $row['category_image'] . '" style="width: 70px"
                                  alt="Category icon representing ' . $row['category_name'] . ' displayed in the StarCoffee admin panel" />
                                </td>
                                <td>' . $row['category_description'] . '</td>
                              <td>
                                <a href="edit-category.php?id=' . $row['category_id'] . '" class="btn btn-success btn-sm">Edit</a>';
                    if ($row['category_status'] == 1) {
                      # code...
                      echo '<a href="disable-category.php?id=' . $row['category_id'] . '&status=0" class="btn btn-warning btn-sm" onclick="return confirm(\'Are you sure you want to disable this category?\')">Disable</a>';
                    } else {
                      # code...
                      echo '<a href="disable-category.php?id=' . $row['category_id'] . '&status=1" class="btn btn-primary btn-sm" onclick="return confirm(\'Are you sure you want to enable this category?\')">Enable</a>';
                    }

                    echo '<a href="delete-category.php?id=' . $row['category_id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                              </td>
                              </td>
                            </tr>';
                    $count++;
                  }

                  ?>

                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Action</th>
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