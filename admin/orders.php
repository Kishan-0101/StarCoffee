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
          <h1>All Orders</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Orders</li>
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
              <h3 class="card-title">Recent orders with item summary and customer details.</h3>
            </div>
            <div class="card-body">
              <?php
              $sql = "
                  SELECT 
                    o.order_id,
                    o.order_key,
                    o.customer_name,
                    o.customer_email,
                    o.customer_phone,
                    o.customer_address,
                    o.payment_method,
                    o.upi_id,
                    o.subtotal,
                    o.delivery_charge,
                    o.total_amount,
                    o.created_at,
                    COUNT(oi.order_item_id) AS line_count,
                    COALESCE(SUM(oi.quantity),0) AS item_qty,
                    GROUP_CONCAT(CONCAT(oi.product_name,' x',oi.quantity) SEPARATOR ', ') AS items_text
                  FROM orders o
                  LEFT JOIN order_items oi ON oi.order_id = o.order_id
                  GROUP BY o.order_id
                  ORDER BY o.order_id DESC
                ";
              $result = mysqli_query($conn, $sql);
              while ($row = mysqli_fetch_assoc($result)) {
                $orderLabel = !empty($row['order_key']) ? $row['order_key'] : $row['order_id'];
                ?>
                <h5 class="bg-info p-2 mb-2">Order #<?php echo htmlspecialchars($orderLabel); ?></h5>
                <table class="table table-sm table-bordered mb-4">
                  <tbody>
                    <tr>
                      <th style="width:160px">Order</th>
                      <td>#<?php echo htmlspecialchars($orderLabel); ?></td>
                    </tr>
                    <tr>
                      <th>Customer</th>
                      <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    </tr>
                    <tr>
                      <th>Contact</th>
                      <td><?php echo htmlspecialchars($row['customer_email']); ?> ·
                        <?php echo htmlspecialchars($row['customer_phone']); ?></td>
                    </tr>
                    <tr>
                      <th>Address</th>
                      <td><?php echo nl2br(htmlspecialchars($row['customer_address'])); ?></td>
                    </tr>
                    <tr>
                      <th>Payment</th>
                      <td>
                        <?php echo strtoupper(htmlspecialchars($row['payment_method'])); ?>  <?php if ($row['payment_method'] === 'upi' && !empty($row['upi_id']))
                               echo ' · ' . htmlspecialchars($row['upi_id']); ?>
                      </td>
                    </tr>
                    <tr>
                      <th>Items</th>
                      <td><?php echo htmlspecialchars($row['items_text'] ?? '—'); ?>
                        (<?php echo intval($row['item_qty']); ?> total)</td>
                    </tr>
                    <tr>
                      <th>Totals</th>
                      <td>Subtotal: ₹<?php echo number_format((float) $row['subtotal'], 2); ?> · Delivery & Handling:
                        ₹<?php echo number_format((float) $row['delivery_charge'], 2); ?> · Total:
                        ₹<?php echo number_format((float) $row['total_amount'], 2); ?></td>
                    </tr>
                    <tr>
                      <th>Placed At</th>
                      <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                    <tr>
                      <th>Order Details</th>
                      <td><a class="btn btn-primary btn-sm"
                          href="order-details.php?id=<?php echo intval($row['order_id']); ?>">View</a></td>
                    </tr>
                  </tbody>
                </table>
              <?php } ?>
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