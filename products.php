<?php
session_start();
include 'db.php';

// Get category ID form URL, default to 1 if not set
$category_id = isset($_GET['cat']) ? intval($_GET['cat']) : 1;

// Fetch category name for $pageTitle (only if category is enabled)
$pageTitle = "Products";
$cat_stmt = $conn->prepare("SELECT category_name, category_description FROM category WHERE category_id = ? AND category_status = 1");
$cat_stmt->bind_param("i", $category_id);
$cat_stmt->execute();
$cat_stmt->bind_result($category_name, $category_description);
if ($cat_stmt->fetch()) {
  $pageTitle = $category_name;
} else {
  // If category doesn't exist or is disabled, redirect to home
  header("Location: index.php");
  exit();
}
$cat_stmt->close();

// Fetch products in the selected category
$products = [];
$prod_stmt = $conn->prepare("SELECT * FROM products WHERE p_category = ? AND p_status = 1 ORDER BY p_id DESC");
$prod_stmt->bind_param("i", $category_id);
$prod_stmt->execute();
$result = $prod_stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}
$prod_stmt->close();

include 'inc/header.php';
?>
<main class="main section">
  <h2 class="section__title" style="color: var(--white-color);">
    <?php echo htmlspecialchars(strtoupper($pageTitle)); ?>
  </h2>
  <p class="about__description"
    style="text-align: center; margin-bottom: 2rem; color: var(--white-color); max-width: 600px; margin-left: auto; margin-right: auto;">
    <?php echo htmlspecialchars($category_description ?? ''); ?>
  </p>

  <section class="products section" id="products">
    <div class="products__container container grid">
      <?php if ($products): ?>
        <?php foreach ($products as $product): ?>
          <a href="single-product.php?id=<?php echo $product['p_id']; ?>">
            <article class="products__card">
              <div class="products__images">
                <div class="products__shape"></div>
                <img src="assets/img/ice-img.png" alt="image" class="products__ice-1">
                <img src="assets/img/ice-img.png" alt="image" class="products__ice-2">
                <img src="product-images/<?php echo htmlspecialchars($product['p_image']); ?>" alt="image"
                  class="products__coffee">
              </div>
              <div class="products__data">
                <h3 class="products__name"><?php echo strtoupper(htmlspecialchars($product['p_name'])); ?></h3>
                <span class="products__price">₹<?php echo htmlspecialchars($product['p_price']); ?></span>

                <button class="products__button"><i class="ri-shopping-bag-3-fill"
                    style="color: var(--dark-color);"></i></button>
              </div>
            </article>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="color: var(--white-color); text-align:center; width:100%;">No products found in this category.</div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php include('inc/footer.php'); ?>