<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!--=============== FAVICON ===============-->
   <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

   <!--=============== REMIXICONS ===============-->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">

   <!--=============== SWIPER CSS ===============-->
   <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">

   <!--=============== CSS ===============-->
   <link rel="stylesheet" href="assets/css/styles.css">

   <title><?php echo $pageTitle ?? 'StarCoffee'; ?></title>
</head>

<body>
   <?php
   // Helper to mark active nav link based on current script name
   $reqUri = $_SERVER['REQUEST_URI'] ?? '';
   $currentPath = basename(parse_url($reqUri, PHP_URL_PATH));
   if (!function_exists('nav_active')) {
      function nav_active($targets)
      {
         global $currentPath;
         if (!is_array($targets)) {
            $targets = [$targets];
         }
         return in_array($currentPath, $targets, true) ? ' active-link' : '';
      }
   }
   if (!function_exists('anchor_to')) {
      function anchor_to($hash)
      {
         global $currentPath;
         $hash = ltrim((string) $hash, '#');
         return ($currentPath === 'index.php') ? ('#' . $hash) : ('index.php#' . $hash);
      }
   }
   ?>
   <!--==================== HEADER ====================-->
   <header class="header" id="header">
      <nav class="nav container">
         <a href="index.php" class="nav__logo">STARCOFFEE</a>

         <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
               <li>
                  <a href="index.php" class="nav__link<?php echo nav_active('index.php'); ?>">HOME</a>
               </li>

               <li>
                  <a href="<?php echo anchor_to('popular'); ?>" class="nav__link">POPULAR</a>
               </li>

               <li>
                  <a href="about.php" class="nav__link">ABOUT US</a>
               </li>

               <li class="nav__item dropdown">
                  <a href="<?php echo anchor_to('products'); ?>"
                     class="nav__link<?php echo nav_active(['products.php', 'single-product.php']); ?>">MENU<i
                        class="ri-arrow-down-s-line"></i></a>

                  <?php
                  // Include the header file for database connection
                  require_once(__DIR__ . '/../db.php');

                  // Change your SQL to fetch both ID and name, only enabled categories:
                  $result = mysqli_query($conn, "SELECT category_id, category_name FROM category WHERE category_status = 1 ORDER BY category_name ASC");

                  $categories = [];
                  if ($result) {
                     while ($row = mysqli_fetch_assoc($result)) {
                        $categories[] = $row;
                     }
                  }
                  ?>

                  <!-- Hidden Dropdown Menu -->
                  <ul class="category-menu" id="category-menu">
                     <?php foreach ($categories as $cat): ?>
                        <li>
                           <a href="products.php?cat=<?php echo $cat['category_id']; ?>"
                              class="nav__link"><?php echo htmlspecialchars(strtoupper($cat['category_name'])); ?></a>
                        </li>
                     <?php endforeach; ?>
                  </ul>
               </li>

               <li>
                  <a href="contact.php" class="nav__link">CONTACT</a>
               </li>

               <?php if (isset($_SESSION['user'])): ?>
                  <?php
                  $userEmailForGreeting = '';
                  if (is_array($_SESSION['user'])) {
                     $userEmailForGreeting = $_SESSION['user']['email'] ?? '';
                  } else {
                     $userEmailForGreeting = (string) $_SESSION['user'];
                  }
                  $greetName = $userEmailForGreeting ? strtoupper(explode('@', $userEmailForGreeting)[0]) : 'USER';
                  ?>
                  <li><a href="user.php"><span class="nav__link">HI, <?php echo $greetName; ?>
                           <i class="ri-user-line"></i></span></a></li>

                  <li>
                     <a href="cart.php" class="nav__link<?php echo nav_active('cart.php'); ?>">
                        <i class="ri-shopping-bag-3-fill"></i>
                        <span class="cart-badge" id="cart-count">
                           <?php
                           $cartCount = 0;
                           if (isset($_SESSION['cart_items']) && is_array($_SESSION['cart_items'])) {
                              foreach ($_SESSION['cart_items'] as $ci) {
                                 $cartCount += isset($ci['quantity']) ? (int) $ci['quantity'] : 1;
                              }
                           } elseif (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                              // Legacy fallback
                              $cartCount = count($_SESSION['cart']);
                           }
                           echo $cartCount;
                           ?>
                        </span>
                     </a>
                  </li>
                  <li><a href="logout.php" class="nav__link"><i class="ri-logout-box-r-line"></i>LOGOUT</a></li>

               <?php else: ?>
                  <li><a href="<?php echo anchor_to('login'); ?>" class="nav__link" onclick="openAuth()">LOGIN / SIGNUP</a>
                  </li>
               <?php endif; ?>
            </ul>

            <!-- Close Button -->
            <div class="nav__close" id="nav-close">
               <i class="ri-close-large-line"></i>
            </div>
         </div>

         <!-- Toggle Button -->
         <div class="nav__toggle" id="nav-toggle">
            <i class="ri-apps-2-fill"></i>
         </div>
      </nav>
   </header>

   <?php if (!empty($_SESSION['show_auth_modal'])): ?>
      <script>
         document.addEventListener('DOMContentLoaded', function () {
            openAuth(); // This is your JS function to open the modal
         });
      </script>
      <?php unset($_SESSION['show_auth_modal']); ?>
   <?php endif; ?>

   <script>
      // Open auth modal from any page using URL hash (#login) or empty hash (#)
      document.addEventListener('DOMContentLoaded', function () {
         try {
            var hash = (window.location.hash || '').trim();
            var onHome = (<?php echo json_encode($currentPath === 'index.php'); ?>);
            if (hash === '#login' || hash === '#auth') {
               if (typeof openAuth === 'function') {
                  openAuth();
               } else if (!onHome) {
                  // If modal markup isn't on this page, go to home and open there
                  window.location.replace('index.php#login');
               }
            }
         } catch (e) { /* no-op */ }
      });
   </script>