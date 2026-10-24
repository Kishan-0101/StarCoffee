<?php
session_start();
include 'db.php';

// Initialize cart session structure
if (!isset($_SESSION['cart_items']) || !is_array($_SESSION['cart_items'])) {
	$_SESSION['cart_items'] = [];
}

// Handle add/customize submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart']) && intval($_POST['add_to_cart']) === 1) {
	$product_id_post = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
	$size = isset($_POST['size']) ? trim($_POST['size']) : 'tall';
	$oz = isset($_POST['oz']) ? intval($_POST['oz']) : 12;
	$syrup = isset($_POST['syrup']) ? intval($_POST['syrup']) : 2;
	$ice = isset($_POST['ice']) ? trim($_POST['ice']) : 'ice';
	$quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;

	// Fetch product (server-side trust)
	if ($product_id_post > 0) {
		$stmt = $conn->prepare("SELECT p_id, p_name, p_price, p_image FROM products WHERE p_id = ?");
		$stmt->bind_param("i", $product_id_post);
		$stmt->execute();
		$result = $stmt->get_result();
		$product_row = $result->fetch_assoc();
		$stmt->close();

		if ($product_row) {
			$item = [
				'id' => intval($product_row['p_id']),
				'name' => $product_row['p_name'],
				'image' => $product_row['p_image'],
				'unit_price' => floatval($product_row['p_price']),
				'size' => $size,
				'oz' => $oz,
				'syrup' => $syrup,
				'ice' => $ice,
				'quantity' => $quantity,
				'total_price' => floatval($product_row['p_price']) * $quantity
			];

			$_SESSION['cart_items'][] = $item;
		}
	}

	// Redirect to cart summary view
	header("Location: cart.php");
	exit;
}

// Handle remove item (place after session_start() and before output)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
	$remove_index = intval($_POST['remove_item']);
	if (isset($_SESSION['cart_items'][$remove_index])) {
		array_splice($_SESSION['cart_items'], $remove_index, 1); // remove that item and reindex
		$_SESSION['success'] = "Item removed from cart.";
	} else {
		$_SESSION['error'] = "Item not found in cart.";
	}
	header('Location: cart.php');
	exit;
}

// Check if the user is logged in, if not then redirect to the login page
if (empty($_SESSION['user'])) {
	$_SESSION['error'] = "You must be logged in to access your cart.";
	$_SESSION['show_auth_modal'] = true; // Add this line to show the auth model
	header("Location: index.php");
	exit;
}

$product = null;
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id > 0) {
	$stmt = $conn->prepare("SELECT p.p_id, p.p_name, p.p_price, p.p_image, p.p_short_description, p.p_status, c.category_name, c.category_status FROM products p INNER JOIN category c ON c.category_id = p.p_category WHERE p.p_id = ?");
	$stmt->bind_param("i", $product_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$product = $result->fetch_assoc();
	$stmt->close();
}

// Set dynamic page title for browser tab
$pageTitle = $product ? (htmlspecialchars($product['p_name'])) : 'Your Cart';

if ($product_id > 0 && isset($_GET['add'])) {
	$_SESSION['cart'][] = $product_id;
}

include 'inc/header.php';
?>

<section class="popular section cart section">
	<div class="container cart__container">
		<?php if ($product): ?>
			<div class="cart__header">
				<h2 class="section__title">CUSTOMIZE YOUR DRINK</h2>
				<p class="cart__subtitle">Choose a size and personalize it to your taste.</p>
			</div>
			<form method="post" action="cart.php" class="cart__grid cart__grid--media-first">
				<input type="hidden" name="add_to_cart" value="1">
				<input type="hidden" name="product_id" value="<?php echo intval($product['p_id']); ?>">
				<input type="hidden" name="size" id="form-size" value="tall">
				<input type="hidden" name="oz" id="form-oz" value="12">
				<input type="hidden" name="syrup" id="form-syrup" value="2">
				<input type="hidden" name="quantity" id="form-quantity" value="1">

				<!-- Right: Product media on top -->
				<div class="cart__media">
					<div class="single__content"
						style="text-align: center; max-width: 700px; margin: auto; margin-bottom: 1.75rem;">
						<h1 class="single__title">
							<?php echo strtoupper(htmlspecialchars($product['p_name'])); ?>
						</h1>
					</div>
					<div class="single__media">
						<div class="single__shape"></div>
						<img src="assets/img/bean-img.png" alt="coffee bean" class="popular__bean-1">
						<img src="assets/img/bean-img.png" alt="coffee bean" class="popular__bean-2">
						<img src="product-images/<?php echo htmlspecialchars($product['p_image']); ?>"
							alt="<?php echo htmlspecialchars($product['p_name'] ?? 'Coffee'); ?>" class="single__image">
					</div>
				</div>

				<!-- Sizes block below the media -->
				<div class="cart__sizes-list" id="size-buttons">
					<button type="button" class="cart-size is-active" data-size="tall" data-oz="12">
						<span class="cart-size__icon" aria-hidden="true"><i class="ri-drinks-fill"></i></span>
						<span class="cart-size__name">Tall</span>
						<span class="cart-size__oz">12 fl oz</span>
					</button>
					<button type="button" class="cart-size" data-size="grande" data-oz="16">
						<span class="cart-size__icon" aria-hidden="true"><i class="ri-drinks-fill"></i></span>
						<span class="cart-size__name">Grande</span>
						<span class="cart-size__oz">16 fl oz</span>
					</button>
					<button type="button" class="cart-size" data-size="venti" data-oz="24">
						<span class="cart-size__icon" aria-hidden="true"><i class="ri-drinks-fill"></i></span>
						<span class="cart-size__name">Venti</span>
						<span class="cart-size__oz">24 fl oz</span>
					</button>
					<button type="button" class="cart-size" data-size="trenta" data-oz="30">
						<span class="cart-size__icon" aria-hidden="true"><i class="ri-drinks-fill"></i></span>
						<span class="cart-size__name">Trenta</span>
						<span class="cart-size__oz">30 fl oz</span>
					</button>

				</div>
				<div class="cart__availability">
					<a href="https://maps.app.goo.gl/FBP7vHpNANon8umY9" target="_blank" class="contact__map"><i
							class="ri-map-pin-fill"></i><span>Select a store to view availability</span></a>
				</div>

				<!-- Left: What's included -->
				<div class="cart__included">
					<h3 class="cart__block-title">What's included</h3>

					<div class="cart-field">
						<div class="cart-field__label">
							<p class="single__description">About :
								<br><br><?php echo htmlspecialchars($product['p_short_description'] ?? ''); ?>
							</p>
						</div>
					</div>

					<div class="cart-field">
						<div class="cart-field__label">
							<p class="single__category">Type :
								<span><?php echo strtoupper(htmlspecialchars($product['category_name'])); ?></span>
							</p><br>
						</div>
					</div>

					<div class="cart-field">
						<div class="cart-field__label">Flavors pumps</div>
						<div class="cart-counter" data-counter="syrup">
							<button type="button" class="counter-btn minus" aria-label="Decrease">−</button>
							<span class="counter-value">2</span>
							<button type="button" class="counter-btn plus" aria-label="Increase">+</button>
						</div>
					</div>

					<!-- Ice Options -->
					<div class="cart-field">
						<div class="cart-field__label">Add-ins</div>
						<div class="cart-select">
							<select name="ice" id="ice-select">
								<option value="extra-ice">Extra Ice</option>
								<option value="light-ice">Light Ice</option>
								<option value="no-ice">No Ice</option>
								<option value="ice" selected>Ice</option>
							</select>
						</div>
					</div>

					<!-- Quantity below Flavors -->
					<div class="cart-field">
						<div class="cart-field__label">Quantity</div>
						<div class="cart-counter" data-counter="quantity" data-min="1">
							<button type="button" class="counter-btn minus" aria-label="Decrease">−</button>
							<span class="counter-value">1</span>
							<button type="button" class="counter-btn plus" aria-label="Increase">+</button>
						</div>
					</div>

					<div class="cart-field">
						<div class="cart-field__label">Price</div>
						<span class="single__price" id="display-price"
							data-unit-price="<?php echo htmlspecialchars($product['p_price']); ?>">₹<?php echo htmlspecialchars($product['p_price']); ?></span>
					</div>

					<div class="cart__actions">
						<button type="submit" class="button button-dark cart__customize">Customize</button>
						<a href="checkout.php"><button type="button" class="button cart__add"
								style="margin-left: .5rem;">Add to Order</button></a>
					</div>
				</div>
			</form>
		<?php else: ?>
			<div class="cart__header">
				<h2 class="section__title">YOUR CART</h2>
				<p class="cart__subtitle">Review your customized drinks before checkout.</p>
			</div>
			<div class="cart__list">
				<?php if (!empty($_SESSION['cart_items'])): ?>
					<?php $grand_total = 0.0; ?>
					<?php foreach ($_SESSION['cart_items'] as $index => $item): ?>
						<?php $line_total = floatval($item['total_price']);
						$unit_price = floatval($item['unit_price']);
						$qty = intval($item['quantity']);
						$grand_total += $line_total; ?>
						<form method="post" action="cart.php" style="display:inline">
							<input type="hidden" name="remove_item" value="<?php echo $index; ?>">
							<button type="submit" aria-label="Remove item">
								<i class="ri-close-large-fill"></i>
							</button>
						</form>
						<div class="cart__item">
							<div class="cart__item-media">
								<img class="cart__item-image" src="product-images/<?php echo htmlspecialchars($item['image']); ?>"
									alt="<?php echo htmlspecialchars($item['name']); ?>">
							</div>
							<div class="cart__item-info">
								<div class="cart__item-title"><?php echo strtoupper(htmlspecialchars($item['name'])); ?> <span
										class="cart__item-variant">(<?php echo htmlspecialchars(strtoupper($item['size'])); ?> ·
										<?php echo intval($item['oz']); ?> oz)</span></div>
								<div class="cart__item-meta">Pumps: <?php echo intval($item['syrup']); ?> · Add-ins:
									<?php echo htmlspecialchars($item['ice']); ?> · Qty: <?php echo intval($item['quantity']); ?>
								</div>
								<div class="cart__item-pricing">₹<?php echo number_format($unit_price, 2); ?> × <?php echo $qty; ?>
									= <strong>₹<?php echo number_format($line_total, 2); ?></strong></div>
							</div>
							<div class="cart__item-price">₹<?php echo number_format($line_total, 2); ?></div>
						</div>
					<?php endforeach; ?>
					<div class="cart__totals">
						<div class="cart__summary">
							<div class="cart__summary-label">Subtotal</div>
							<div class="cart__summary-value">₹<?php echo number_format($grand_total, 2); ?></div>
						</div>
						<div class="cart__summary-note">Taxes and fees calculated at checkout.</div>
					</div>
					<div class="cart__actions cart__actions--footer">
						<a href="checkout.php"><button type="button" class="button cart__add">Proceed to Checkout</button></a>
					</div>
				<?php else: ?>
					<p style="text-align:center; color: var(--white-color);">Your cart is empty. Browse products to add items.
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php include 'inc/footer.php'; ?>

<script>
	(function () {
		var sizeButtons = document.querySelectorAll('#size-buttons .cart-size');
		var formSize = document.getElementById('form-size');
		var formOz = document.getElementById('form-oz');
		var syrupCounter = document.querySelector('[data-counter="syrup"] .counter-value');
		var qtyCounter = document.querySelector('[data-counter="quantity"] .counter-value');
		var formSyrup = document.getElementById('form-syrup');
		var formQuantity = document.getElementById('form-quantity');
		var displayPrice = document.getElementById('display-price');
		var unitPrice = displayPrice ? parseFloat(displayPrice.getAttribute('data-unit-price')) : 0;

		if (sizeButtons && formSize && formOz) {
			sizeButtons.forEach(function (btn) {
				btn.addEventListener('click', function () {
					sizeButtons.forEach(function (b) { b.classList.remove('is-active'); });
					btn.classList.add('is-active');
					formSize.value = btn.getAttribute('data-size');
					formOz.value = btn.getAttribute('data-oz');
				});
			});
		}

		function attachCounter(selector, minVal) {
			var container = document.querySelector(selector);
			if (!container) return;
			var minus = container.querySelector('.counter-btn.minus');
			var plus = container.querySelector('.counter-btn.plus');
			var valueEl = container.querySelector('.counter-value');
			var min = parseInt(container.getAttribute('data-min') || minVal || 0, 10);
			function updateHidden() {
				if (selector.indexOf('syrup') !== -1 && formSyrup) {
					formSyrup.value = valueEl.textContent.trim();
				}
				if (selector.indexOf('quantity') !== -1 && formQuantity) {
					formQuantity.value = valueEl.textContent.trim();
					if (displayPrice && !isNaN(unitPrice)) {
						var qty = parseInt(formQuantity.value, 10) || 1;
						displayPrice.textContent = '₹' + (unitPrice * qty).toFixed(2);
					}
				}
			}
			// Do not modify the value here to avoid double increments.
			// Let the site's main script update the UI, then sync hidden fields.
			function deferSync() { setTimeout(updateHidden, 0); }
			if (minus) minus.addEventListener('click', deferSync);
			if (plus) plus.addEventListener('click', deferSync);
		}

		attachCounter('[data-counter="syrup"]', 0);
		attachCounter('[data-counter="quantity"]', 1);
	})();
</script>