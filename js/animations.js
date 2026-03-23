document.addEventListener('DOMContentLoaded', () => {
    // 1. Reveal on scroll for product items
    const revealOnScroll = () => {
        const items = document.querySelectorAll('.product-item');
        const windowHeight = window.innerHeight;
        const revealPoint = 150;

        items.forEach(item => {
            const itemTop = item.getBoundingClientRect().top;
            const itemBottom = item.getBoundingClientRect().bottom;

            // Als het item in het zicht komt (scrollen naar beneden)
            if (itemTop < windowHeight - revealPoint && itemBottom > 0) {
                item.classList.add('revealed');
            } 
            // Als het item uit het zicht gaat (vooral bij scrollen naar boven)
            else {
                item.classList.remove('revealed');
            }
        });
    };

    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll(); // Initial check

    // 2. Cart Icon Animation on update (Simulated)
    // In this simple PHP setup, we check if there's a specific class in the cart
    const cartIcon = document.querySelector('nav a[href="cart.php"]');
    if (cartIcon) {
        // Simple scale animation when hovering or on first load if cart is not empty
        cartIcon.addEventListener('mouseenter', () => {
            cartIcon.style.transform = 'scale(1.05)';
        });
        cartIcon.addEventListener('mouseleave', () => {
            cartIcon.style.transform = 'scale(1)';
        });
    }

    // 3. Button Click Animation (Feedback)
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Only add effect if it's not a direct link to another page
            if (this.tagName === 'BUTTON') {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 100);
            }
        });
    });

    // 4. Smooth scroll for internal links if any (bonus)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
