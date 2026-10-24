<?php
session_start();
include 'db.php';
// Make mysqli throw exceptions so our try/catch works reliably
if (function_exists('mysqli_report')) {
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}
// Ensure local timezone for timestamps shown to users
if (function_exists('date_default_timezone_set')) {
  date_default_timezone_set('Asia/Kolkata');
}

// require login (adjust session key you use)
if (empty($_SESSION['user'])) {
  $_SESSION['error'] = "You must be logged in to checkout.";
  $_SESSION['show_auth_modal'] = true;
  header('Location: index.php');
  exit;
}

// initialize cart
if (!isset($_SESSION['cart_items']) || !is_array($_SESSION['cart_items'])) {
  $_SESSION['cart_items'] = [];
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf_token'];

// Messages (flash via session)
$errors = [];
$success = '';

// compute totals (server-side)
$subTotal = 0.0;
foreach ($_SESSION['cart_items'] as $item) {
  $unit = max(0.0, floatval($item['unit_price'] ?? 0));
  $qty = max(1, min(20, intval($item['quantity'] ?? 1))); // clamp qty 1..20
  $subTotal += $unit * $qty;
}
$deliveryCharge = ($subTotal > 0) ? 30.00 : 0.00;
$total = $subTotal + $deliveryCharge;

// Handle POST (checkout)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['checkout'])) {

  // CSRF validate
  if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $errors[] = "Invalid request.";
  }

  // sanitize & validate
  $customer_name = trim($_POST['customer_name'] ?? '');
  $customer_email = trim($_POST['customer_email'] ?? '');
  $customer_phone = trim($_POST['customer_phone'] ?? '');
  $customer_address = trim($_POST['customer_address'] ?? '');
  $payment_method = $_POST['payment_method'] ?? '';
  $upi_id = trim($_POST['upi_id'] ?? '');

  if ($customer_name === '') {
    $errors[] = 'Full name is required.';
  }
  if ($customer_email === '' || !filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required.';
  }
  if ($customer_phone === '' || !preg_match('/^[0-9\+\-\s]{6,20}$/', $customer_phone)) {
    $errors[] = 'Valid phone number is required.';
  }
  if ($customer_address === '') {
    $errors[] = 'Address is required.';
  }
  if (!in_array($payment_method, ['cod', 'upi'], true)) {
    $errors[] = 'Please select a payment method.';
  }
  if ($payment_method === 'upi' && $upi_id !== '' && !preg_match('/^[A-Za-z0-9.\-_]{2,}@[A-Za-z0-9]{3,}$/', $upi_id)) {
    $errors[] = 'Please enter a valid UPI id (example: name@bank).';
  }
  if (empty($_SESSION['cart_items'])) {
    $errors[] = 'Your cart is empty.';
  }

  if (empty($errors)) {
    // persist order (transaction)
    $user_id = intval($_SESSION['user_id'] ?? ($_SESSION['user']['id'] ?? 0));
    if ($user_id <= 0) {
      $errors[] = 'Authentication error. Please login again.';
    }
  }

  if (empty($errors)) {
    // generate public order key (human friendly)
    $order_key = 'OR' . random_int(10000, 99999);

    try {
      $conn->begin_transaction();

      $stmt = $conn->prepare("
                INSERT INTO orders (order_key, user_id, customer_name, customer_email, customer_phone, customer_address, payment_method, upi_id, subtotal, delivery_charge, total_amount, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
      // types: s i s s s s s s d d d (7 strings total including order_key)
      $stmt->bind_param(
        "sissssssddd",
        $order_key,
        $user_id,
        $customer_name,
        $customer_email,
        $customer_phone,
        $customer_address,
        $payment_method,
        $upi_id,
        $subTotal,
        $deliveryCharge,
        $total
      );
      $stmt->execute();
      $order_id = $conn->insert_id;
      $stmt->close();

      // insert order items
      $stmtItem = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity, size_label, oz, syrup_pumps, ice, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
      foreach ($_SESSION['cart_items'] as $ci) {
        $pid = intval($ci['id'] ?? 0);
        $pname = substr($ci['name'] ?? '', 0, 255);
        $unitPrice = floatval($ci['unit_price'] ?? 0);
        $qty = max(1, min(20, intval($ci['quantity'] ?? 1)));
        $sizeLabel = substr($ci['size'] ?? '', 0, 32);
        $oz = intval($ci['oz'] ?? 0);
        $syrup = intval($ci['syrup'] ?? 0);
        $ice = substr($ci['ice'] ?? '', 0, 32);

        // types: i i s d i s i i s
        $stmtItem->bind_param("iisdisiis", $order_id, $pid, $pname, $unitPrice, $qty, $sizeLabel, $oz, $syrup, $ice);
        // Note: bind types above are illustrative; adapt to your schema types
        $stmtItem->execute();
      }
      $stmtItem->close();

      $conn->commit();

      require_once __DIR__ . '/inc/mailer.php';
      $orderLabel = $order_key;
      $itemsHtml = '';
      $embeds = [];
      foreach ($_SESSION['cart_items'] as $ci) {
        $img = htmlspecialchars($ci['image'] ?? '');
        $name = htmlspecialchars($ci['name'] ?? 'Product');
        $qty = max(1, min(20, intval($ci['quantity'] ?? 1)));
        $size = htmlspecialchars($ci['size'] ?? '');
        $ozv = intval($ci['oz'] ?? 0);
        $unitp = max(0.0, floatval($ci['unit_price'] ?? 0));
        $syr = intval($ci['syrup'] ?? 0);
        $icev = htmlspecialchars($ci['ice'] ?? '');
        $line = $unitp * $qty;
        $cid = '';
        $imgPath = __DIR__ . '/product-images/' . ($ci['image'] ?? '');
        if (!empty($ci['image']) && file_exists($imgPath)) {
          $cid = 'img' . uniqid();
          $embeds[] = ['path' => $imgPath, 'cid' => $cid];
        }
        $itemsHtml .= '<tr>'
          . '<td style="padding:8px 6px;display:flex;align-items:center;gap:8px;">'
          . ($cid ? '<img src="cid:' . $cid . '" alt="' . $name . '" style="width:56px;height:56px;object-fit:cover;border-radius:6px;">' : '')
          . '<div><div style="font-weight:600;color:#111;">' . strtoupper($name) . '</div>'
          . '<div style="color:#555;font-size:13px;">' . ($size ? strtoupper($size) : '-') . ' | ' . $ozv . ' oz | Pumps: ' . $syr . ' | Add-ins: ' . ($icev ?: '-') . ' | Qty: ' . $qty . '</div></div></td>'
          . '<td style="padding:8px 6px;text-align:right;color:#0b7b6e;font-weight:600;">₹' . number_format($line, 2) . '</td>'
          . '</tr>';
      }
      $addrHtml = nl2br(htmlspecialchars($customer_address));
      $payHtml = strtoupper(htmlspecialchars($payment_method));
      if ($payment_method === 'upi' && $upi_id !== '') {
        $payHtml .= ' | ' . htmlspecialchars($upi_id);
      }
      $placedAt = date('d M Y, h:i A');
      $body = '
              <div style="font-family:Segoe UI,Arial,sans-serif;background:#f6f9fb;padding:24px;">
                <div style="max-width:680px;margin:0 auto;background:#ffffff;border:1px solid #e8eef3;border-radius:10px;overflow:hidden;">
                  <div style="background:#0fb995;color:#fff;padding:16px 20px;font-weight:600;">StarCoffee · Order Confirmation</div>
                  <div style="padding:20px;">
                    <p style="margin:0 0 10px;color:#111;font-size:16px;">Hi ' . htmlspecialchars($customer_name) . ',</p>
                    <p style="margin:0 0 14px;color:#333;">Thank you for your order. We\'re getting it ready. Here are your order details:</p>
                  <table style="width:100%;border-collapse:collapse;margin:12px 0 8px;">
                      <tr><td style="padding:6px 0;color:#555;width:160px;">Order</td><td style="padding:6px 0;color:#111;font-weight:600;">#' . htmlspecialchars($orderLabel) . '</td></tr>
                      <tr><td style="padding:6px 0;color:#555;">Placed At</td><td style="padding:6px 0;color:#111;">' . htmlspecialchars($placedAt) . ' (IST)</td></tr>
                      <tr><td style="padding:6px 0;color:#555;">Customer</td><td style="padding:6px 0;color:#111;">' . htmlspecialchars($customer_name) . ' | ' . htmlspecialchars($customer_email) . ' | ' . htmlspecialchars($customer_phone) . '</td></tr>
                      <tr><td style="padding:6px 0;color:#555;">Address</td><td style="padding:6px 0;color:#111;">' . $addrHtml . '</td></tr>
                      <tr><td style="padding:6px 0;color:#555;">Payment</td><td style="padding:6px 0;color:#111;">' . $payHtml . '</td></tr>
                    </table>
                    <div style="margin:14px 0 6px;font-weight:600;color:#111;">Items</div>
                    <table style="width:100%;border-collapse:collapse;border:1px solid #eef2f6;">
                      <thead>
                        <tr>
                          <th style="text-align:left;padding:10px 6px;background:#f8fbfd;color:#333;border-bottom:1px solid #eef2f6;">Product</th>
                          <th style="text-align:right;padding:10px 6px;background:#f8fbfd;color:#333;border-bottom:1px solid #eef2f6;">Amount</th>
                        </tr>
                      </thead>
                      <tbody>' . $itemsHtml . '</tbody>
                    </table>
                    <table style="width:100%;border-collapse:collapse;margin-top:12px;">
                      <tr><td style="padding:6px 0;color:#555;">Subtotal</td><td style="padding:6px 0;color:#0b7b6e;font-weight:600;text-align:right;">₹' . number_format($subTotal, 2) . '</td></tr>
                      <tr><td style="padding:6px 0;color:#555;">Delivery & Handling</td><td style="padding:6px 0;color:#0b7b6e;font-weight:600;text-align:right;">₹' . number_format($deliveryCharge, 2) . '</td></tr>
                      <tr><td style="padding:6px 0;color:#111;font-weight:700;">Total</td><td style="padding:6px 0;color:#0b7b6e;font-weight:700;text-align:right;">₹' . number_format($total, 2) . '</td></tr>
                    </table>
                    <p style="margin:16px 0 0;color:#333;">We\'ll email you again when your order is out for delivery. If you have any questions, just reply to this email.</p>
                    <p style="margin:8px 0 0;color:#111;font-weight:600;">Warm regards,<br>StarCoffee Team</p>
                  </div>
                </div>
              </div>';
      send_mail($customer_email, $customer_name, 'Your StarCoffee Order #' . $orderLabel, $body, '', $embeds);

      // clear cart and redirect to success page
      $_SESSION['cart_items'] = [];
      $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
      header('Location: order_success.php?ok=' . urlencode($order_key));
      exit;

    } catch (Exception $e) {
      $conn->rollback();
      $errors[] = "Unable to place order. Please try again later.";
      error_log("Order error: " . $e->getMessage());
    }
  }

  // on errors, store and redirect (PRG)
  if (!empty($errors)) {
    $_SESSION['error_checkout'] = $errors;
    header('Location: checkout.php');
    exit;
  }
}

// pull flash messages
if (!empty($_SESSION['success'])) {
  $success = $_SESSION['success'];
  unset($_SESSION['success']);
}
if (!empty($_SESSION['error_checkout'])) {
  $errors = $_SESSION['error_checkout'];
  unset($_SESSION['error_checkout']);
}

// compute totals
$subTotal = 0.0;
foreach ($_SESSION['cart_items'] as $item) {
  $unit = floatval($item['unit_price'] ?? 0);
  $qty = intval($item['quantity'] ?? 1);
  $subTotal += $unit * $qty;
}
$deliveryCharge = ($subTotal > 0) ? 30.00 : 0.00;
$total = $subTotal + $deliveryCharge;

// Prepare hero item
$firstItem = !empty($_SESSION['cart_items']) ? $_SESSION['cart_items'][0] : null;

include 'inc/header.php';
?>

<main class="main section">
  <section class="popular section checkout-section">
    <div class="container">
      <h2 class="section__title">CHECKOUT</h2>

      <?php if ($success): ?>
        <div class="alert alert-success">
          <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <?php foreach ($errors as $err): ?>
            <div><?php echo htmlspecialchars($err); ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="row checkout__grid">
        <div class="checkout__summary-container">
          <section>
            <h3 class="checkout__subtitle">ORDER SUMMARY</h3>

            <?php if (!empty($_SESSION['cart_items'])): ?>
              <div class="cart__list">
                <?php foreach ($_SESSION['cart_items'] as $ci):
                  $img = htmlspecialchars($ci['image'] ?? '');
                  $name = htmlspecialchars($ci['name'] ?? 'Product');
                  $qty = intval($ci['quantity'] ?? 1);
                  $size = htmlspecialchars($ci['size'] ?? '');
                  $oz = intval($ci['oz'] ?? 0);
                  $unit = floatval($ci['unit_price'] ?? 0);
                  $syrup = intval($ci['syrup'] ?? 0);
                  $ice = htmlspecialchars($ci['ice'] ?? '');
                  ?>
                  <article class="cart__item">
                    <div class="cart__item-media">
                      <img src="product-images/<?php echo $img; ?>" alt="<?php echo $name; ?>" class="cart__item-image">
                    </div>
                    <div class="cart__item-info">
                      <div class="cart__item-title"><?php echo strtoupper($name); ?> <span
                          class="cart__item-variant">(<?php echo $size ? strtoupper($size) : '—'; ?> · <?php echo $oz; ?>
                          oz)</span></div>
                      <div class="cart__item-meta">Pumps: <?php echo $syrup; ?> · Add-ins: <?php echo $ice ?: '—'; ?> · Qty:
                        <?php echo $qty; ?></div>
                    </div>
                    <div class="cart__item-price">₹
                      <?php echo number_format($unit * $qty, 2); ?>
                    </div>
                  </article>
                <?php endforeach; ?>
              </div>

              <div class="totals-card">
                <div class="totals-row">
                  <span class="cart__summary-label">Subtotal</span>
                  <span class="cart__summary-value">₹<?php echo number_format($subTotal, 2); ?></span>
                </div>
                <div class="totals-row">
                  <span class="cart__summary-label">Delivery & Handling</span>
                  <span class="cart__summary-value">₹<?php echo number_format($deliveryCharge, 2); ?></span>
                </div>
                <div class="totals-row totals-row--strong">
                  <span class="cart__summary-label">Total</span>
                  <span class="cart__summary-value">₹<?php echo number_format($total, 2); ?></span>
                </div>
              </div>

            <?php else: ?>
              <div class="alert alert-info">Your cart is empty.</div>
            <?php endif; ?>
          </section>
        </div>

        <div class="checkout__form-container">
          <section>
            <h3 class="checkout__subtitle">BILLING & DELIVERY DETAILS</h3>

            <form method="post" action="checkout.php" novalidate class="checkout__form">
              <input type="hidden" id="checkout_flag" name="checkout" value="1">
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">

              <div class="form-group">
                <label for="customer_name">FULL NAME</label>
                <input id="customer_name" name="customer_name" class="form-control" type="text"
                  value="<?php echo htmlspecialchars($_POST['customer_name'] ?? ''); ?>" required>
              </div>

              <div class="form-group">
                <label for="customer_email">EMAIL</label>
                <input id="customer_email" name="customer_email" class="form-control" type="email"
                  value="<?php echo htmlspecialchars($_POST['customer_email'] ?? ''); ?>" required>
              </div>

              <div class="form-group">
                <label for="customer_phone">PHONE</label>
                <input id="customer_phone" name="customer_phone" class="form-control" type="tel"
                  pattern="^[0-9\+\-\s]{6,20}$" value="<?php echo htmlspecialchars($_POST['customer_phone'] ?? ''); ?>"
                  required>
              </div>

              <div class="form-group">
                <label for="customer_address">ADDRESS</label>
                <textarea id="customer_address" name="customer_address" class="form-control" rows="4"
                  required><?php echo htmlspecialchars($_POST['customer_address'] ?? ''); ?></textarea>
              </div>

              <div class="form-group">
                <label class="form-label">PAYMENT METHOD</label>
                <div class="checkout__payment-options">
                  <label class="checkout__payment-option" for="payment_cod">
                    <input id="payment_cod" type="radio" name="payment_method" value="cod" <?php if (($_POST['payment_method'] ?? '') === 'cod')
                      echo 'checked'; ?>> CASH ON DELIVERY
                  </label>
                  <label class="checkout__payment-option" for="payment_upi">
                    <input id="payment_upi" type="radio" name="payment_method" value="upi" <?php if (($_POST['payment_method'] ?? '') === 'upi')
                      echo 'checked'; ?>> UPI
                  </label>
                </div>
              </div>

              <div class="form-group" id="upi_details"
                style="display: <?php echo (($_POST['payment_method'] ?? '') === 'upi') ? 'block' : 'none'; ?>;">
                <label for="upi_id">UPI ID (optional)</label>
                <input id="upi_id" name="upi_id" class="form-control" type="text"
                  value="<?php echo htmlspecialchars($_POST['upi_id'] ?? ''); ?>">
                <small class="form-text text-muted">If you choose UPI, you can provide your UPI id for
                  pre-payment</small>
              </div>

              <div class="checkout__actions">
                <button type="submit" class="button">PLACE ORDER</button>
                <a href="cart.php" class="button button-dark">BACK TO CART</a>
              </div>
            </form>

          </section>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
  // Client-side: toggle UPI field based on selection and lightweight validation hints
  document.addEventListener('DOMContentLoaded', function () {
    var radios = document.querySelectorAll('input[name="payment_method"]');
    var upiDiv = document.getElementById('upi_details');
    radios.forEach(function (r) {
      r.addEventListener('change', function () {
        if (this.value === 'upi') upiDiv.style.display = 'block';
        else upiDiv.style.display = 'none';
      });
    });
  });
</script>

<?php include 'inc/footer.php'; ?>