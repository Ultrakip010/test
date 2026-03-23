<?php
session_start();
require_once 'products.php';

// Winkelmandje logica
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header('Location: index.php');
    exit;
}

// Haal de top 4 meest verkochte producten op voor de slideshow
$all_products_copy = $products;
usort($all_products_copy, function($a, $b) {
    return $b['sales'] - $a['sales'];
});
$slideshow_products = array_slice($all_products_copy, 0, 4);

$page_title = 'Home';
require_once 'header.php';
?>

<div class="hero-slideshow">
    <?php foreach ($slideshow_products as $index => $product): ?>
        <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <div class="slide-content">
                <h2><?php echo $product['name']; ?></h2>
                <p><?php echo $product['description']; ?></p>
                <a href="all_products.php" class="btn">Bekijk Producten</a>
            </div>
        </div>
    <?php endforeach; ?>
    
    <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
    <button class="next" onclick="moveSlide(1)">&#10095;</button>
</div>

<div class="container">
    <section class="featured-products">
        <h2 class="text-center mb-2">Uitgelichte Producten</h2>
        <div class="product-grid">
            <?php 
            // Toon 4 willekeurige of populaire producten op de home
            $featured = array_slice($products, 0, 4);
            foreach ($featured as $product): 
            ?>
                <div class="product-item">
                    <div class="category-tag"><?php echo $product['category']; ?></div>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    <h3><?php echo $product['name']; ?></h3>
                    <p class="price">&euro;<?php echo number_format($product['price'], 2, ',', '.'); ?></p>
                    <p style="font-size: 0.8rem; color: <?php echo $product['stock'] < 20 ? '#d9534f' : '#5cb85c'; ?>; font-weight: 600; margin-bottom: 1rem;">
                        <?php echo $product['stock'] < 20 ? 'Slechts ' . $product['stock'] . ' op voorraad!' : 'Op voorraad: ' . $product['stock']; ?>
                    </p>
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_cart" class="btn">In winkelmandje</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="center-content">
            <a href="all_products.php" class="btn btn-secondary">Bekijk alle producten</a>
        </div>
    </section>
</div>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');

function showSlide(n, direction = 1) {
    // Verwijder huidige klassen van alle slides om conflicten te voorkomen
    slides.forEach(slide => {
        slide.classList.remove('active');
        slide.classList.remove('prev-slide');
        slide.classList.remove('next-slide');
    });
    
    // De huidige slide wordt de 'vorige' slide (of 'volgende' bij achteruit gaan)
    let lastSlide = currentSlide;
    
    // Bereken nieuwe slide index
    currentSlide = (n + slides.length) % slides.length;
    
    // Animate slides based on direction
    if (direction === 1) {
        // Vooruit: huidige schuift naar links, nieuwe komt van rechts
        slides[lastSlide].classList.add('prev-slide');
    } else {
        // Achteruit: huidige schuift naar rechts, nieuwe komt van links
        slides[lastSlide].classList.add('next-slide');
    }
    
    // Zet de nieuwe op 'active'
    slides[currentSlide].classList.add('active');
}

function moveSlide(n) {
    showSlide(currentSlide + n, n > 0 ? 1 : -1);
}

// Automatische slideshow elke 10 seconden
setInterval(() => {
    moveSlide(1);
}, 10000);
</script>

<?php
include 'footer.php';
?>