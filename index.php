<?php session_start();
include('inc/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $email = $_POST['email'];
   $password = $_POST['password'];

   // Check if email exists
   $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
   $stmt->bind_param("s", $email);
   $stmt->execute();
   $result = $stmt->get_result();
   $user = $result->fetch_assoc();

   // Verify password
   if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user['email']; // Save session
      header("Location: dashboard.php");  // Redirect to dashboard
      exit();
   } else {
      echo "Invalid email or password.";
   }
}
?>

<!--==================== MAIN ====================-->
<main class="main">
   <!--==================== HOME ====================-->
   <section class="home section" id="home">
      <div class="home__container container grid">
         <h1 class="home__title">COLD COFFEE</h1>

         <div class="home__images">
            <div class="home__shape"></div>
            <img src="assets/img/home-splash.png" alt="image" class="home__splash">
            <img src="assets/img/bean-img.png" alt="image" class="home__bean-2">
            <img src="assets/img/home-coffee.png" alt="image" class="home__coffee">
            <img src="assets/img/bean-img.png" alt="image" class="home__bean-1">
            <img src="assets/img/ice-img.png" alt="image" class="home__ice-1">
            <img src="assets/img/ice-img.png" alt="image" class="home__ice-2">
            <img src="assets/img/leaf-img.png" alt="image" class="home__leaf">
         </div>

         <img src="assets/img/home-sticker.svg" alt="image" class="home__sticker">

         <div class="home__data">
            <p class="home__description">
               Find delicious hot and cold coffees with the
               best varieties, calm the pleasure and enjoy
               a good coffee, order now.
            </p>

            <a href="#about" class="button">Learn More</a>
         </div>
      </div>
   </section>

   <!--==================== POPULAR ====================-->
   <section class="popular section" id="popular">
      <div class="popular__container container">
         <h2 class="section__title">POPULAR <br> CREATIONS</h2>

         <div class="popular__swiper swiper">
            <div class="swiper-wrapper">

               <?php
               $query = "SELECT * FROM products WHERE p_id < 4 AND p_status = 1 ORDER BY p_id DESC LIMIT 3";
               $result = mysqli_query($conn, $query);
               while ($row = mysqli_fetch_assoc($result)) {
                  echo '<article class="popular__card swiper-slide">
                  <div class="popular__images">
                     <div class="popular__shape"></div>
                     <img src="assets/img/bean-img.png" alt="coffee bean" class="popular__bean-1">
                     <img src="assets/img/bean-img.png" alt="coffee bean" class="popular__bean-2">
                     <img src="product-images/' . htmlspecialchars($row['p_image']) . '" alt="Product coffee" class="popular__coffee">
                  </div>

                  <div class="popular__data">
                     <h2 class="popular__name">' . strtoupper($row['p_name']) . '</h2>

                     <p class="popular__description">
                        ' . $row['p_short_description'] . '
                     </p>

                     <a href="single-product.php?id=' . $row['p_id'] . '" class="button button-dark">Order now: ₹' . $row['p_price'] . '</a>
                  </div>
               </article>';
               }
               ?>
            </div>
         </div>
      </div>
   </section>

   <!--==================== ABOUT ====================-->
   <section class="about section" id="about">
      <div class="about__container container grid">
         <div class="about__data">
            <h2 class="section__title">LEARN MORE <br> ABOUT US</h2>

            <p class="about__description">
               Welcome to StarCoffee, where coffee is pure passion.
               From bean to cup, we are dedicated to delivering
               excellence in every sip. Join us on a journey of
               flavor and quality, crafted with love to create the
               ultimate coffee experience.
            </p>

            <a href="#popular" class="button">The Best Coffees</a>
         </div>

         <div class="about__images">
            <div class="about__shape"></div>
            <img src="assets/img/leaf-img.png" alt="image" class="about__leaf-1">
            <img src="assets/img/leaf-img.png" alt="image" class="about__leaf-2">
            <img src="assets/img/about-coffee.png" alt="image" class="about__coffee">
         </div>
      </div>
   </section>

   <!--==================== PRODUCTS ====================-->
   <section class="products section" id="products">
      <h2 class="section__title">THE MOST <br> REQUESTED</h2>

      <div class="products__container container grid">

         <?php

         $query = "SELECT * FROM products WHERE p_status = 1 AND p_id BETWEEN 4 AND 12 ORDER BY p_id ASC";
         $result = mysqli_query($conn, $query);
         while ($row = mysqli_fetch_assoc($result)) {
            echo '<a href="single-product.php?id=' . $row['p_id'] . '">
            <article class="products__card">
            <div class="products__images">
               <div class="products__shape"></div>
               <img src="assets/img/ice-img.png" alt="image" class="products__ice-1">
               <img src="assets/img/ice-img.png" alt="image" class="products__ice-2">
               <img src="product-images/' . htmlspecialchars($row['p_image']) . '" alt="image" class="products__coffee">
            </div>

            <div class="products__data">
               <h3 class="products__name">' . strtoupper($row['p_name']) . '</h3>

               <span class="products__price">₹' . $row['p_price'] . '</span>

               <div class="products__button">
                  <i class="ri-shopping-bag-3-fill"></i>
               </div>

            </div>
            </article> 
         </a>';
         }

         ?>
      </div>
   </section>

   <!--==================== CONTACT ====================-->
   <section class="contact section" id="contact">
      <h2 class="section__title">CONTACT US</h2>

      <div class="contact__container container grid">
         <div class="contact__info grid">
            <div>
               <h3 class="contact__title">Write Us</h3>

               <div class="contact__social">
                  <a href="https://www.whatsapp.com/" target="_blank" class="contact__social-link">
                     <i class="ri-whatsapp-fill"></i>
                  </a>

                  <a href="https://www.messenger.com/" target="_blank" class="contact__social-link">
                     <i class="ri-messenger-fill"></i>
                  </a>

                  <a href="https://telegram.org/" target="_blank" class="contact__social-link">
                     <i class="ri-telegram-2-fill"></i>
                  </a>
               </div>
            </div>

            <div>
               <h3 class="contact__title">Location</h3>

               <address class="contact__address">
                  Rajkot - Sun City - India <br>
                  Av. Moon #4321
               </address>

               <a href="https://maps.app.goo.gl/FBP7vHpNANon8umY9" target="_blank" class="contact__map">
                  <i class="ri-map-pin-fill"></i>
                  <span>View On Map</span>
               </a>
            </div>
         </div>

         <div class="contact__info grid">
            <div>
               <h3 class="contact__title">Delivery</h3>

               <address class="contact__address">
                  +91-987-7654-321 <br>
                  +91-012345
               </address>
            </div>

            <div>
               <h3 class="contact__title">Business Hours</h3>

               <address class="contact__address">
                  Open Daily <br>
                  7:00 AM - 11:00 PM
               </address>
            </div>
         </div>

         <div class="contact__images">
            <div class="contact__shape"></div>
            <img src="assets/img/contact-delivery.png" alt="image" class="contact__delivery">
         </div>
      </div>
   </section>

   <!-- ========== AUTH MODAL ========== -->
   <div class="auth-modal" id="auth-modal">
      <div class="auth-content">
         <span class="close-auth" id="close-auth">&times;</span>

         <div id="login-form" class="auth-form">
            <h2 class="section__title">LOGIN</h2>
            <?php if (!empty($_SESSION['auth_error'])): ?>
               <div class="alert alert-error" style="color:var(--white-color); margin: 0 0 12px;">
                  <?php echo htmlspecialchars($_SESSION['auth_error']); ?>
               </div>
               <?php unset($_SESSION['auth_error']); ?>
            <?php endif; ?>
            <form action="login.php" method="POST">
               <input type="email" name="email" placeholder="Email" class="footer__input" required>
               <input type="password" name="password" placeholder="Password" class="footer__input" required>
               <button type="submit" class="button">LOGIN</button>
            </form>
            <p>Don't have an account? <a href="#" onclick="showSignup()">SIGN UP</a></p>
         </div>

         <div id="signup-form" class="auth-form" style="display:none;">
            <h2 class="section__title">SIGN UP</h2>
            <form action="register.php" method="POST">
               <input type="email" name="email" placeholder="Email" class="footer__input" required>
               <input type="password" name="password" placeholder="Password" class="footer__input" required
                  pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                  title="Password must be at least 8 characters, include uppercase, lowercase, number, and special character.">
               <button type="submit" class="button">SIGN UP</button>
            </form>
            <p>Already have an account? <a href="#" onclick="showLogin()">LOGIN</a></p>
         </div>
      </div>
   </div>

</main>

<?php
include('inc/footer.php');
?>