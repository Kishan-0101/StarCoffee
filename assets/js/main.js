/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu'),
    navToggle = document.getElementById('nav-toggle'),
    navClose = document.getElementById('nav-close')

// Menu Show
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu')
    })
}

// Menu Hidden
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu')
    })
}

/*=============== REMOVE MENU MOBILE ===============*/
const navLink = document.querySelectorAll('.nav__link')

const linkAction = (e) => {
    if (!e.target.closest('.dropdown')) {
        const navMenu = document.getElementById('nav-menu')
        // When we click on each nav__link, we remove the show-
        navMenu.classList.remove('show-menu')
    }
}
navLink.forEach(n => n.addEventListener('click', linkAction))

/*=============== ADD SHADOW HEADER ===============*/
const shadowHeader = () => {
    const header = document.getElementById('header')
    // Add a class if the button offset is greater than 50 of the value
    this.scrollY >= 50 ? header.classList.add('shadow-header')
        : header.classList.remove('shadow-header')
}
window.addEventListener('scroll', shadowHeader)

/*=============== SWIPER POPULAR ===============*/
const swiperPopular = new Swiper('.popular__swiper', {
    loop: true,
    grabCursor: true,
    spaceBetween: 32,
    slidesPerView: 'auto',
    centeredSlides: 'auto',

    brakpoints: {
        1150: {
            spaceBetween: 80,
        }
    }
})

/*=============== SHOW SCROLL UP ===============*/
const scrollUp = () => {
    const scrollUp = document.getElementById('scroll-up')
    // When the scroll is higher then 350 viewport innerHeight, add the
    this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
        : scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]')

const scrollActive = () => {
    const scrollDown = window.scrollY

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight,
            sectionTop = current.offsetTop - 58,
            sectionId = current.getAttribute('id'),
            sectionClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

        if (scrollDown > sectionTop && scrollDown <= sectionTop + sectionHeight) {
            sectionClass.classList.add('active-link')
        } else {
            sectionClass.classList.remove('active-link')
        }
    })
}
window.addEventListener('scroll', scrollActive)

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 2000,
    delay: 300,
    // reset: true, // Animation repeat
})

sr.reveal(`.popular__swiper, .footer__container, .footer__copy`)
sr.reveal(`.home__shape`, { origin: 'bottom' })
sr.reveal(`.home__coffee`, { delay: 1000, distance: '200px', duration: 1500 })
sr.reveal(`.home__splash`, { delay: 1600, scale: 0, duration: 1500 })
sr.reveal(`.home__bean-1, .home__bean-2`, { delay: 2200, scale: 0, duration: 1500, rotate: { z: 180 } })
sr.reveal(`.home__ice-1, .home__ice-2`, { delay: 2600, scale: 0, duration: 1500, rotate: { z: 180 } })
sr.reveal(`.home__leaf`, { delay: 2800, scale: 0, duration: 1500, rotate: { z: 90 } })
sr.reveal(`.home__title`, { delay: 3500 })
sr.reveal(`.home__data, .home__sticker`, { delay: 4000 })
sr.reveal(`.about__data`, { origin: 'left' })
sr.reveal(`.about__images`, { origin: 'right' })
sr.reveal(`.about__coffee`, { delay: 1000 })
sr.reveal(`.about__leaf-1, .about__leaf-2`, { delay: 1400, rotate: { z: 90 } })
sr.reveal(`.products__card, .contact__info`, { interval: 100 })
sr.reveal(`.contact__shape`, { delay: 600, scale: 0 })
sr.reveal(`.contact__delivery`, { delay: 1200 })

// ==== Auth Modal Handling ====
function openAuth() {
    document.getElementById('auth-modal').style.display = 'flex';
}

document.addEventListener("DOMContentLoaded", function () {
    const closeBtn = document.getElementById("close-auth");
    const modal = document.getElementById("auth-modal");

    if (closeBtn && modal) {
        closeBtn.onclick = function () {
            modal.style.display = "none";
        };
    } else {
        console.warn("close-auth or auth-modal not found in DOM");
    }
});


window.onclick = function (event) {
    if (event.target == document.getElementById('auth-modal')) {
        document.getElementById('auth-modal').style.display = 'none';
    }
}

function showSignup() {
    document.getElementById('login-form').style.display = 'none';
    document.getElementById('signup-form').style.display = 'block';
}

function showLogin() {
    document.getElementById('signup-form').style.display = 'none';
    document.getElementById('login-form').style.display = 'block';
}

/*=============== CART INTERACTIVITY ===============*/
const initCartUI = () => {
    // Size selection toggle
    const sizeButtons = document.querySelectorAll('.cart-size')
    const hiddenInput = document.getElementById('selectedSize')

    sizeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active from all
            sizeButtons.forEach(b => b.classList.remove('is-active'))
            // Add active to clicked
            btn.classList.add('is-active')
            // Update hidden input
            if (hiddenInput) hiddenInput.value = btn.dataset.size
            console.log('Selected size:', btn.dataset.size)
        })
    })

    // Counter controls (generic)
    document.querySelectorAll('.cart-counter').forEach(counter => {
        const valueEl = counter.querySelector('.counter-value')
        const minus = counter.querySelector('.minus')
        const plus = counter.querySelector('.plus')
        const min = parseInt(counter.getAttribute('data-min') || '0', 10)

        const getValue = () => parseInt(valueEl.textContent, 10) || 0
        const setValue = (v) => { valueEl.textContent = String(v) }

        if (minus) minus.addEventListener('click', () => {
            const current = getValue()
            if (current > min) setValue(current - 1)
        })
        if (plus) plus.addEventListener('click', () => setValue(getValue() + 1))
    })
}

// Initialize after DOM loaded
document.addEventListener('DOMContentLoaded', initCartUI)
