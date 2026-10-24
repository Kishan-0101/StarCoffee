<?php
include("inc/header.php");
?>

<div class="content-wrapper dark-mode">
  <div class="content-header dark-mode">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Order Details</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Order Details</li>
          </ol>
        </div>
      </div>
      <section class="content dark-mode">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Order Details</h3>
                </div>
                <div class="card-body">
                  <?php
                  $oid = isset($_GET['id']) ? intval($_GET['id']) : 0;
                  // Order header
                  $order = null;
                  if ($oid > 0) {
                    $res = mysqli_query($conn, "SELECT * FROM orders WHERE order_id = " . $oid . " LIMIT 1");
                    if ($res) {
                      $order = mysqli_fetch_assoc($res);
                    }
                  }
                  ?>

                  <h5 class="bg-info p-2">Order Summary</h5>
                  <table class="table table-sm table-bordered">
                    <tbody>
                      <?php if ($order): ?>
                        <tr>
                          <th style="width:160px">Order</th>
                          <td>#<?php echo htmlspecialchars($order['order_key'] ?: $order['order_id']); ?></td>
                        </tr>
                        <tr>
                          <th>Customer</th>
                          <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        </tr>
                        <tr>
                          <th>Contact</th>
                          <td><?php echo htmlspecialchars($order['customer_email']); ?> ·
                            <?php echo htmlspecialchars($order['customer_phone']); ?></td>
                        </tr>
                        <tr>
                          <th>Address</th>
                          <td><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></td>
                        </tr>
                        <tr>
                          <th>Payment</th>
                          <td>
                            <?php echo strtoupper(htmlspecialchars($order['payment_method'])); ?>  <?php if ($order['payment_method'] === 'upi' && !empty($order['upi_id']))
                                   echo ' · ' . htmlspecialchars($order['upi_id']); ?>
                          </td>
                        </tr>
                        <tr>
                          <th>Totals</th>
                          <td>Subtotal: ₹<?php echo number_format((float) $order['subtotal'], 2); ?> · Delivery & Handling:
                            ₹<?php echo number_format((float) $order['delivery_charge'], 2); ?> · Total:
                            ₹<?php echo number_format((float) $order['total_amount'], 2); ?></td>
                        </tr>
                        <tr>
                          <th>Placed At</th>
                          <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        </tr>
                        <tr>
                          <th>Status</th>
                          <td></td>
                        </tr>
                      <?php else: ?>
                        <tr>
                          <td colspan="2">Order not found.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>

                  <h5 class="bg-info p-2 mt-3">Items</h5>
                  <table id="dtable" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Size Label</th>
                        <th>Fl oz</th>
                        <th>Syrup Pumps</th>
                        <th>Add-ins</th>
                        <th>Unit Price</th>
                        <th>Line Total</th>
                        <th>Placed At</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($oid > 0) {
                        $sql = "SELECT oi.product_name, oi.quantity, oi.size_label, oi.oz, oi.syrup_pumps, oi.ice, oi.unit_price, oi.created_at, p.p_image
                              FROM order_items oi
                              LEFT JOIN products p ON p.p_id = oi.product_id
                              WHERE oi.order_id = " . $oid . "
                              ORDER BY oi.order_item_id ASC";
                        $res = mysqli_query($conn, $sql);
                        $i = 1;
                        if ($res) {
                          while ($row = mysqli_fetch_assoc($res)) {
                            $line = (float) $row['unit_price'] * (int) $row['quantity'];
                            echo '<tr>' .
                              '<td>' . $i++ . '</td>' .
                              '<td>' . htmlspecialchars($row['product_name']) . '</td>' .
                              '<td>' . (!empty($row['p_image']) ? '<img src="../product-images/' . htmlspecialchars($row['p_image']) . '" alt="' . htmlspecialchars($row['product_name']) . '" style="width:64px;height:100px;object-fit:cover;border-radius:6px;">' : '—') . '</td>' .
                              '<td>' . intval($row['quantity']) . '</td>' .
                              '<td>' . ucfirst(htmlspecialchars($row['size_label'] ?: '—')) . '</td>' .
                              '<td>' . intval($row['oz']) . ' fl oz </td>' .
                              '<td>' . intval($row['syrup_pumps']) . ' pumps</td>' .
                              '<td>' . ucfirst(htmlspecialchars($row['ice'] ?: '—')) . '</td>' .
                              '<td>₹' . number_format((float) $row['unit_price'], 2) . '</td>' .
                              '<td>₹' . number_format($line, 2) . '</td>' .
                              '<td>' . htmlspecialchars($row['created_at']) . '</td>' .
                              '</tr>';
                          }
                        } else {
                          echo '<tr><td colspan="10">Unable to load order items.</td></tr>';
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
      </section>
    </div>
  </div>
</div>

<?php
include("inc/footer.php");
?>