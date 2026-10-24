<?php
session_start();
include 'db.php';

// Fetch products from database
$product = null;
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
  $stmt = $conn->prepare(
    "SELECT p.p_id, p.p_name, p.p_price, p.p_image, p.p_short_description, p.p_status, c.category_name, c.category_status FROM products p INNER JOIN category c ON c.category_id = p.p_category WHERE p.p_id = ?"
  );
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();
  $stmt->close();

  if ($product && intval($product['p_status']) === 1 && isset($product['category_status']) && intval($product['category_status']) === 1) {
    $pageTitle = $product['p_name'];
  }
}

include('inc/header.php');

?>

<section class="popular section">
  <h2 class="section__title">BEVERAGE DETAILS</h2>
  <p class="section__description" style="text-align: center; max-width: 700px; margin: auto;">
    Discover the rich flavors and aromas of our expertly crafted coffee beverages. Each cup is made with premium beans
    and meticulous care to ensure a perfect experience every time.
  </p>

  <div class="single container ">

    <?php if ($product): ?>
      <?php if (intval($product['p_status']) === 1 && isset($product['category_status']) && intval($product['category_status']) === 1): ?>
        <div class="single__grid">
          <div class="single__media">
            <div class="single__shape"></div>
            <img src="assets/img/bean-img.png" alt="coffee bean" class="popular__bean-1">
            <img src="assets/img/bean-img.png" alt="coffee bean" class="popular__bean-2">
            <img src="product-images/<?php echo htmlspecialchars($product['p_image']); ?>"
              alt="<?php echo htmlspecialchars($product['p_name']); ?>" class="single__image">
          </div>
          <div class="single__content">
            <h1 class="single__title"><?php echo strtoupper(htmlspecialchars($product['p_name'])); ?></h1>
            <p class="single__category"><strong>Type :</strong>
              <span><?php echo strtoupper(htmlspecialchars($product['category_name'])); ?></span>
            </p><br>
            <p class="single__description"><strong>About :</strong>
              <br><br><?php echo htmlspecialchars($product['p_short_description'] ?? ''); ?>
            </p>
            <div class="single__footer">
              <span class="single__price">₹<?php echo htmlspecialchars($product['p_price']); ?></span>
              <a href="cart.php?id=<?php echo intval($product['p_id']); ?>" class="button button-dark">Order now</a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <p style="text-align:center; color: var(--white-color);">Product not found or unavailable.</p>
      <?php endif; ?>
    <?php endif; ?>
</section>
<?php include('inc/footer.php'); ?>