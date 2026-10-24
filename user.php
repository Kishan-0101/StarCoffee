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

// CSRF token
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf_token'];

// Resolve user id/email from session shape
$user_id = intval($_SESSION['user_id'] ?? ($_SESSION['user']['id'] ?? 0));
$user_email = '';
if (is_array($_SESSION['user'])) {
  $user_email = $_SESSION['user']['email'] ?? '';
} else {
  $user_email = (string) ($_SESSION['user'] ?? '');
}


$errors = [];
$success = '';

// Handle review submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
  if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $errors[] = 'Invalid request.';
  } else {
    $rating = intval($_POST['rating'] ?? 0);
    $review_text = trim($_POST['review_text'] ?? '');
    $order_id_for_review = intval($_POST['order_id'] ?? 0) ?: null;
    $product_id_for_review = intval($_POST['product_id'] ?? 0) ?: null;
    if ($rating < 1 || $rating > 5) {
      $errors[] = 'Please select a rating between 1 and 5.';
    }
    if (empty($product_id_for_review)) {
      $errors[] = 'Please select a product.';
    }
    if (empty($errors)) {
      $stmt = $conn->prepare("INSERT INTO reviews (user_id, order_id, product_id, rating, review_text) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('iiiis', $user_id, $order_id_for_review, $product_id_for_review, $rating, $review_text);
      $stmt->execute();
      $stmt->close();
      $success = 'Thank you for your feedback!';
      // rotate csrf
      $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
      $csrf = $_SESSION['csrf_token'];
    }
  }
}

// Fetch latest order for summary
$order = null;
$items = [];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC LIMIT 1");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows) {
  $order = $res->fetch_assoc();
}
$stmt->close();

if ($order) {
  $stmt2 = $conn->prepare("SELECT oi.*, p.p_image FROM order_items oi LEFT JOIN products p ON p.p_id = oi.product_id WHERE oi.order_id = ? ORDER BY oi.order_item_id ASC");
  $stmt2->bind_param('i', $order['order_id']);
  $stmt2->execute();
  $res2 = $stmt2->get_result();
  while ($row = $res2->fetch_assoc()) {
    $items[] = $row;
  }
  $stmt2->close();
}

// Fetch recent orders (for list)
$ordersList = [];
$stmtOL = $conn->prepare("SELECT order_id, order_key, total_amount, created_at FROM orders WHERE user_id = ? ORDER BY order_id DESC LIMIT 10");
$stmtOL->bind_param('i', $user_id);
$stmtOL->execute();
$resOL = $stmtOL->get_result();
while ($o = $resOL->fetch_assoc()) {
  $ordersList[] = $o;
}
$stmtOL->close();

// Fetch user reviews
$user_reviews = [];
$stmt3 = $conn->prepare("SELECT r.review_id, r.rating, r.review_text, r.created_at, r.order_id, r.product_id, p.p_name, p.p_image FROM reviews r LEFT JOIN products p ON p.p_id = r.product_id WHERE r.user_id = ? AND r.status = 1 ORDER BY r.review_id DESC LIMIT 10");
$stmt3->bind_param('i', $user_id);
$stmt3->execute();
$res3 = $stmt3->get_result();
while ($r = $res3->fetch_assoc()) {
  $user_reviews[] = $r;
}
$stmt3->close();

$pageTitle = 'My Account · StarCoffee';
include 'inc/header.php';
?>

<main class="main section">
  <section class="popular section checkout-section">
    <div class="container">
      <h2 class="section__title">MY ACCOUNT</h2>

      <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <?php foreach ($errors as $e): ?>
            <div><?php echo htmlspecialchars($e); ?></div><?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="row checkout__grid">
        <div>
          <section class="section-card">
            <h3 class="checkout__subtitle">YOUR DETAILS</h3>
            <div class="totals-card">
              <?php if ($order): ?>
                <div class="totals-row"><span class="cart__summary-label">Full Name</span><span
                    class="cart__summary-value"><?php echo htmlspecialchars($order['customer_name']); ?></span></div>
              <?php endif; ?>
              <div class="totals-row"><span class="cart__summary-label">Email</span><span
                  class="cart__summary-value"><?php echo htmlspecialchars($user_email); ?></span></div>
              <?php if ($order): ?>
                <div class="totals-row"><span class="cart__summary-label">Phone</span><span
                    class="cart__summary-value"><?php echo htmlspecialchars($order['customer_phone']); ?></span></div>
                <div class="totals-row"><span class="cart__summary-label">Default Address</span><span
                    class="cart__summary-value"><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></span>
                </div>
              <?php endif; ?>
            </div>
          </section>

          <section class="section-card" style="margin-top:16px;">
            <h3 class="checkout__subtitle">LATEST ORDER SUMMARY</h3>
            <?php if ($order): ?>
              <div class="success-pills" style="margin-bottom:12px;">
                <span class="success-pill">Order:
                  <strong><?php echo htmlspecialchars($order['order_key']); ?></strong></span>
                <span class="success-pill">Total:
                  <strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong></span>
                <span class="success-pill">Placed:
                  <strong><?php echo htmlspecialchars($order['created_at']); ?></strong></span>
              </div>

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
            <?php else: ?>
              <div class="alert alert-info">No orders yet. Start with our <a href="products.php">menu</a>.</div>
            <?php endif; ?>
          </section>
        </div>

        <div>
          <?php if (!empty($ordersList)): ?>
            <section class="section-card" style="margin-bottom:16px;">
              <h3 class="checkout__subtitle">PREVIOUS ORDERS</h3>
              <div class="totals-card orders-list">
                <?php foreach ($ordersList as $ol): ?>
                  <div class="order-row">
                    <span class="order-row-left">#<?php echo htmlspecialchars($ol['order_key']); ?> ·
                      <?php echo htmlspecialchars($ol['created_at']); ?></span>
                    <span class="order-row-right">
                      <span>₹<?php echo number_format($ol['total_amount'], 2); ?></span>
                      <a class="button button-small"
                        href="order_summary.php?ok=<?php echo urlencode($ol['order_key']); ?>">VIEW</a>
                    </span>
                  </div>
                <?php endforeach; ?>
              </div>
            </section>
          <?php endif; ?>

          <section class="section-card">
            <h3 class="checkout__subtitle">WRITE A REVIEW</h3>
            <form method="post" action="user.php" class="checkout__form" novalidate>
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
              <input type="hidden" name="submit_review" value="1">
              <?php if ($order): ?>
                <input type="hidden" name="order_id" value="<?php echo intval($order['order_id']); ?>">
              <?php endif; ?>

              <div class="form-group">
                <label for="product_id">PRODUCT</label>
                <?php $itemCount = count($items); ?>
                <?php if ($itemCount === 1):
                  $one = $items[0]; ?>
                  <select id="product_id" class="form-control" disabled>
                    <option value="<?php echo intval($one['product_id']); ?>" selected>
                      <?php echo htmlspecialchars($one['product_name']); ?> (Qty: <?php echo intval($one['quantity']); ?>)
                    </option>
                  </select>
                  <input type="hidden" name="product_id" value="<?php echo intval($one['product_id']); ?>">
                <?php else: ?>
                  <select id="product_id" name="product_id" class="form-control" required>
                    <option value="">Select a product from your order</option>
                    <?php foreach ($items as $ci): ?>
                      <option value="<?php echo intval($ci['product_id']); ?>">
                        <?php echo htmlspecialchars($ci['product_name']); ?> (Qty: <?php echo intval($ci['quantity']); ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label class="form-label">RATING</label>
                <div class="rating" id="rating" style="display:flex;gap:8px;font-size:24px;color:#ffb703;">
                  <button type="button" class="star" data-value="1" aria-label="1 star"><i
                      class="ri-star-line"></i></button>
                  <button type="button" class="star" data-value="2" aria-label="2 stars"><i
                      class="ri-star-line"></i></button>
                  <button type="button" class="star" data-value="3" aria-label="3 stars"><i
                      class="ri-star-line"></i></button>
                  <button type="button" class="star" data-value="4" aria-label="4 stars"><i
                      class="ri-star-line"></i></button>
                  <button type="button" class="star" data-value="5" aria-label="5 stars"><i
                      class="ri-star-line"></i></button>
                </div>
                <input type="hidden" name="rating" id="rating_value" value="0" required>
              </div>

              <div class="form-group">
                <label for="review_text">YOUR REVIEW</label>
                <textarea id="review_text" name="review_text" class="form-control" rows="5"
                  placeholder="Share your experience... (optional)"></textarea>
              </div>

              <div class="checkout__actions">
                <button type="submit" class="button">SUBMIT REVIEW</button>
                <a href="products.php" class="button button-dark">CONTINUE SHOPPING</a>
              </div>
            </form>
          </section>

          <?php if (!empty($user_reviews)): ?>
            <section class="section-card" style="margin-top:16px;">
              <h3 class="checkout__subtitle">YOUR RECENT REVIEWS</h3>
              <div class="cart__list">
                <?php foreach ($user_reviews as $rv): ?>
                  <article class="cart__item" style="align-items:flex-start;">
                    <div class="cart__item-media">
                      <img src="product-images/<?php echo htmlspecialchars($rv['p_image'] ?? ''); ?>"
                        alt="<?php echo htmlspecialchars($rv['p_name'] ?? ''); ?>" class="cart__item-image">
                    </div>
                    <div class="cart__item-info" style="width:100%;">
                      <div class="cart__item-title">
                        <?php echo $rv['p_name'] ? strtoupper(htmlspecialchars($rv['p_name'])) : 'PRODUCT'; ?>
                        <span class="cart__item-variant" style="margin-left:8px; color:#888; font-size:13px;">on
                          <?php echo htmlspecialchars($rv['created_at']); ?></span>
                      </div>
                      <div class="cart__item-meta" style="margin:6px 0;">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                          <i class="<?php echo $i <= intval($rv['rating']) ? 'ri-star-fill' : 'ri-star-line'; ?>"
                            style="color:#ffb703;"></i>
                        <?php endfor; ?>
                      </div>
                      <div class="cart__item-meta" style="white-space:pre-wrap;">
                        <?php echo $rv['review_text'] ? htmlspecialchars($rv['review_text']) : '—'; ?></div>
                    </div>
                  </article>
                <?php endforeach; ?>
              </div>
            </section>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
  // Interactive star rating
  document.addEventListener('DOMContentLoaded', function () {
    var stars = document.querySelectorAll('.star');
    var valueInput = document.getElementById('rating_value');
    var reviewForm = document.querySelector('form.checkout__form');
    var productSelect = document.getElementById('product_id');
    function paint(n) {
      stars.forEach(function (s, i) {
        var icon = s.querySelector('i');
        if (i < n) { icon.classList.remove('ri-star-line'); icon.classList.add('ri-star-fill'); }
        else { icon.classList.remove('ri-star-fill'); icon.classList.add('ri-star-line'); }
      });
    }
    stars.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var v = parseInt(this.getAttribute('data-value')) || 0;
        valueInput.value = v;
        paint(v);
      });
      btn.addEventListener('mouseenter', function () { paint(parseInt(this.getAttribute('data-value')) || 0); });
    });

    // Client-side validation on submit
    if (reviewForm) {
      reviewForm.addEventListener('submit', function (e) {
        var ratingOk = parseInt(valueInput.value || '0') >= 1;
        var productOk = true;
        if (productSelect && !productSelect.disabled) {
          productOk = !!productSelect.value;
        }
        if (!ratingOk || !productOk) {
          e.preventDefault();
          alert(!ratingOk ? 'Please select a star rating.' : 'Please select a product.');
        }
      });
    }
  });
</script>

<?php include 'inc/footer.php'; ?>