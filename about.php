<?php
$pageTitle = 'About Us - StarCoffee';
include 'inc/header.php';
?>

<main class="main">
    <!--==================== ABOUT HERO ====================-->
    <section class="about section" id="about">
        <div class="about__container container grid">
            <div class="about__data">
                <h1 class="section__title about__title">
                    Our Story<br>
                    <span>Brewing Since 2020</span>
                </h1>
                <p class="about__description">
                    At StarCoffee, we believe in more than just serving coffee. 
                    We're dedicated to creating a community space where people can connect, 
                    ideas can flourish, and every cup tells a story.
                </p>
            </div>
            <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Coffee beans and brewing equipment" class="about__img">
        </div>
    </section>

    <!--==================== OUR MISSION ====================-->
    <section class="mission section">
        <div class="mission__container container grid">
            <div class="mission__content">
                <h2 class="section__title">
                    Our Mission
                    <span class="section__subtitle">What Drives Us</span>
                </h2>
                <div class="mission__data">
                    <div class="mission__group">
                        <i class="ri-leaf-line"></i>
                        <h3 class="mission__title">Quality Ingredients</h3>
                        <p class="mission__description">We source only the finest coffee beans from sustainable farms around the world.</p>
                    </div>
                    <div class="mission__group">
                        <i class="ri-cup-line"></i>
                        <h3 class="mission__title">Perfect Brew</h3>
                        <p class="mission__description">Our baristas are trained to craft each cup with precision and care.</p>
                    </div>
                    <div class="mission__group">
                        <i class="ri-community-line"></i>
                        <h3 class="mission__title">Community</h3>
                        <p class="mission__description">We're committed to being a welcoming space for all coffee lovers.</p>
                    </div>
                </div>
            </div>
            <img src="https://images.unsplash.com/photo-1511537190424-bbbab87ac5eb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Coffee being brewed" class="mission__img">
        </div>
    </section>

<!--==================== VISIT US ====================-->
    <section class="visit section">
        <div class="visit__container container">
            <div class="visit__content">
                <h2 class="section__title">
                    Visit Us
                    <span class="section__subtitle">We'd Love to See You</span>
                </h2>
                <p class="visit__description section__description">
                    Come experience the StarCoffee difference. Our doors are open seven days a week, 
                    and our baristas are ready to craft your perfect cup.
                </p>
                <a href="contact.php" class="button">Get in Touch</a>
            </div>
        </div>
    </section>
</main>

<?php
include 'inc/footer.php';
?>
