<?php
include("inc/header.php");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper dark-mode">
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Content Header (Page header) -->
  <section class="content-header dark-mode">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">General Form</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <section class="content dark-mode">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <?php
              $order_count = 0;
              $order_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
              if ($order_result) {
                $row = mysqli_fetch_assoc($order_result);
                $order_count = $row['total'];
              }
              ?>
              <h3><?php echo $order_count; ?></h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <a href="orders.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <?php
              $review_count = 0;
              $review_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM reviews");
              if ($review_result) {
                $row = mysqli_fetch_assoc($review_result);
                $review_count = $row['total'];
              }
              ?>
              <h3><?php echo $review_count; ?></h3>

              <p>Reviews</p>
            </div>
            <div class="icon">
              <i class="fa-solid fa-chart-line"></i>
            </div>
            <a href="reviews.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <!-- User cout -->
        <?php

        // Count total users
        $user_count = 0;
        $user_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
        if ($user_result) {
          $row = mysqli_fetch_assoc($user_result);
          $user_count = $row['total'];
        }
        ?>
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <?php
              $user_count = 0;
              $user_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
              if ($user_result) {
                $row = mysqli_fetch_assoc($user_result);
                $user_count = $row['total'];
              }
              ?>
              <h3><?php echo $user_count; ?></h3>

              <p>Users</p>
            </div>
            <div class="icon">
              <i class="fa-solid fa-users"></i>
            </div>
            <a href="users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <?php
              $product_count = 0;
              $product_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
              if ($product_result) {
                $row = mysqli_fetch_assoc($product_result);
                $product_count = $row['total'];
              }
              ?>
              <h3><?php echo $product_count; ?></h3>

              <p>Available Products</p>
            </div>
            <div class="icon">
              <i class="fa-solid fa-chart-pie"></i>
            </div>
            <a href="products-list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
    </div>
  </section>
  <!-- Main content -->

  <section class="content dark-mode">
    <div class="container-fluid">
      <div class="card row col-md-8 mb-4 float-left">
        <div class="card-header border-transparent">
          <h3 class="card-title">Latest Orders</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table m-0">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Item</th>
                  <th>Status</th>
                  <th>Date & Time</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "
                    SELECT 
                      o.order_id,
                      o.order_key,
                      o.total_amount,
                      o.created_at,
                      (
                        SELECT oi.product_name 
                        FROM order_items oi 
                        WHERE oi.order_id = o.order_id 
                        ORDER BY oi.order_item_id ASC 
                        LIMIT 1
                      ) AS first_product
                    FROM orders o 
                    ORDER BY o.order_id DESC LIMIT 5
                  ";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                  <tr>
                    <td><a
                        href="order-details.php?id=<?php echo htmlspecialchars($row['order_id']); ?>">#<?php echo htmlspecialchars($row['order_key'] ?: $row['order_id']); ?></a>
                    </td>
                    <td><?php echo htmlspecialchars($row['first_product'] ?? '—'); ?></td>
                    <td><span class="badge badge-success">Delivered</span></td>
                    <td><?php echo htmlspecialchars($row['created_at'] ?? '—'); ?></td>
                    <td>₹<?php echo htmlspecialchars($row['total_amount'] ?? '—'); ?></td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
          <a href="orders.php" class="btn btn-sm btn-secondary float-right">View All Orders</a>
        </div>
        <!-- /.card-footer -->
      </div>
    </div>
  </section>

  <section class="content dark-mode">
    <div class="container-fluid">
      <!-- PRODUCT LIST -->
      <div class="card col-md-4 mb-4 float-right">
        <div class="card-header">
          <h3 class="card-title">Recently Added Products</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <ul class="products-list product-list-in-card pl-2 pr-2">

            <?php

            $query = "SELECT p.*, c.category_name 
                      FROM products p
                      LEFT JOIN category c ON p.p_category = c.category_id
                      ORDER BY p.p_id DESC
                      LIMIT 3";

            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
              # code...
              echo '<li class="item">
                        <div class="product-img">
                        <img src="../product-images/' . $row['p_image'] . '" alt="product image" style="width: 70px; height: 100px; object-fit:cover;">
                        </div>
                      <div class="product-info">
                        <a href="products-list.php" class="product-title">' . $row['p_name'] . '
                          <span class="badge badge-warning float-right">₹' . $row['p_price'] . ' </span>
                          <span class="badge badge-info float-right mr-2">' . $row['category_name'] . ' </span>  
                        </a>
                        <span class="product-description">
                          ' . $row['p_short_description'] . '
                        </span>
                      </div>
                    </li>';
            }
            ?>
            <!-- /.item -->
          </ul>
        </div>
        <!-- /.card-body -->
        <div class="card-footer text-center">
          <a href="products-list.php" class="uppercase">View All Products</a>
        </div>
        <!-- /.card-footer -->
      </div>
    </div>
  </section>

  <section class="content dark-mode">
    <div class="container-fluid">
      <div class="row col-md-12">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Quick Example</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form>
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">File input</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="exampleInputFile">
                      <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                    <div class="input-group-append">
                      <span class="input-group-text">Upload</span>
                    </div>
                  </div>
                </div>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="exampleCheck1">
                  <label class="form-check-label" for="exampleCheck1">Check me out</label>
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