<?php
session_start();
include 'db.php';

// Require login
if (empty($_SESSION['user'])) {
  $_SESSION['error'] = "You must be logged in to view this page.";
  $_SESSION['show_auth_modal'] = true;
  header('Location: index.php');
  exit;
}

$order_key = isset($_GET['ok']) ? trim($_GET['ok']) : '';
$user_id = intval($_SESSION['user_id'] ?? ($_SESSION['user']['id'] ?? 0));

if ($order_key === '' || $user_id <= 0) {
  header('Location: index.php');
  exit;
}

// Fetch order for this user and order_key
$order = null;
$items = [];

$stmt = $conn->prepare("SELECT order_id, order_key, customer_name, customer_email, customer_phone, customer_address, payment_method, upi_id, subtotal, delivery_charge, total_amount, created_at FROM orders WHERE order_key = ? AND user_id = ? LIMIT 1");
$stmt->bind_param('si', $order_key, $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows) {
  $order = $res->fetch_assoc();
}
$stmt->close();

if (!$order) {
  header('Location: index.php');
  exit;
}

// Fetch order items
$stmt2 = $conn->prepare("SELECT oi.product_id, oi.product_name, oi.unit_price, oi.quantity, oi.size_label, oi.oz, oi.syrup_pumps, oi.ice, p.p_image
                         FROM order_items oi
                         LEFT JOIN products p ON p.p_id = oi.product_id
                         WHERE oi.order_id = ?
                         ORDER BY oi.order_item_id ASC");
$stmt2->bind_param('i', $order['order_id']);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($row = $res2->fetch_assoc()) {
  $items[] = $row;
}
$stmt2->close();

include 'inc/header.php';
?>

<main class="main section">
  <section class="success-hero section">
    <div class="container">
      <div class="success-badge"><i class="ri-check-line"></i></div>
      <h1 class="section__title success-title">THANK YOU!</h1>
      <p class="success-hero__text">Your order has been received and is being prepared. A confirmation has been sent to
        <strong><?php echo htmlspecialchars($order['customer_email']); ?></strong>.</p>
      <div class="success-pills">
        <span class="success-pill">Order: <strong><?php echo htmlspecialchars($order['order_key']); ?></strong></span>
        <span class="success-pill">Total:
          <strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong></span>
        <span class="success-pill">Placed: <strong><?php echo htmlspecialchars($order['created_at']); ?></strong></span>
      </div>
      <div class="success-cta">
        <a href="index.php" class="button">Continue Shopping</a>
      </div>
    </div>
  </section>

  <section class="popular section checkout-section">
    <div class="container">
      <div class="row checkout__grid">
        <div>
          <section class="section-card">
            <h3 class="checkout__subtitle">ORDER ITEMS</h3>
            <div class="cart__list">
              <?php foreach ($items as $ci):
                $line = floatval($ci['unit_price']) * intval($ci['quantity']); ?>
                <article class="cart__item">
                  <div class="cart__item-media">
                    <img src="product-images/<?php echo htmlspecialchars($ci['p_image'] ?? ''); ?>"
                      alt="<?php echo htmlspecialchars($ci['product_name']); ?>" class="cart__item-image">
                  </div>
                  <div class="cart__item-info">
                    <div class="cart__item-title"><?php echo strtoupper(htmlspecialchars($ci['product_name'])); ?>
                      <span
                        class="cart__item-variant">(<?php echo $ci['size_label'] ? strtoupper(htmlspecialchars($ci['size_label'])) : '—'; ?>
                        · <?php echo intval($ci['oz']); ?> oz)</span>
                    </div>
                    <div class="cart__item-meta">Pumps: <?php echo intval($ci['syrup_pumps']); ?> · Add-ins:
                      <?php echo $ci['ice'] ? htmlspecialchars($ci['ice']) : '—'; ?> · Qty:
                      <?php echo intval($ci['quantity']); ?></div>
                  </div>
                  <div class="cart__item-price">₹<?php echo number_format($line, 2); ?></div>
                </article>
              <?php endforeach; ?>
            </div>

            <div class="totals-card">
              <div class="totals-row"><span class="cart__summary-label">Subtotal</span><span
                  class="cart__summary-value">₹<?php echo number_format($order['subtotal'], 2); ?></span></div>
              <div class="totals-row"><span class="cart__summary-label">Delivery & Handling</span><span
                  class="cart__summary-value">₹<?php echo number_format($order['delivery_charge'], 2); ?></span></div>
              <div class="totals-row totals-row--strong"><span class="cart__summary-label">Total</span><span
                  class="cart__summary-value">₹<?php echo number_format($order['total_amount'], 2); ?></span></div>
            </div>
          </section>
        </div>

        <div>
          <section class="section-card">
            <h3 class="checkout__subtitle">CUSTOMER</h3>
            <div class="totals-card">
              <div class="totals-row"><span class="cart__summary-label">Name</span><span
                  class="cart__summary-value"><?php echo htmlspecialchars($order['customer_name']); ?></span></div>
              <div class="totals-row"><span class="cart__summary-label">Email</span><span
                  class="cart__summary-value"><?php echo htmlspecialchars($order['customer_email']); ?></span></div>
              <div class="totals-row"><span class="cart__summary-label">Phone</span><span
                  class="cart__summary-value"><?php echo htmlspecialchars($order['customer_phone']); ?></span></div>
              <div class="totals-row"><span class="cart__summary-label">Address</span><span
                  class="cart__summary-value"><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></span>
              </div>
              <div class="totals-row"><span class="cart__summary-label">Payment</span><span
                  class="cart__summary-value"><?php echo strtoupper(htmlspecialchars($order['payment_method'])); ?></span>
              </div>
              <?php if (!empty($order['upi_id'])): ?>
                <div class="totals-row"><span class="cart__summary-label">UPI</span><span
                    class="cart__summary-value"><?php echo htmlspecialchars($order['upi_id']); ?></span></div>
              <?php endif; ?>
            </div>
          </section>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'inc/footer.php'; ?>