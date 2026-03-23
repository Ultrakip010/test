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
    header('Location: all_products.php');
    exit;
}

// Filters logica
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$max_price_filter = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 0;

$all_products = $products;
$filtered_products = array_filter($all_products, function($product) use ($category_filter, $max_price_filter) {
    $category_match = empty($category_filter) || $product['category'] === $category_filter;
    $price_match = $max_price_filter <= 0 || $product['price'] <= $max_price_filter;
    return $category_match && $price_match;
});

// Unieke categorieën voor het filter
$categories = array_unique(array_column($all_products, 'category'));

$page_title = 'Alle Producten';
require_once 'header.php';
?>

<div class="container">
    <h2 class="mb-2">Alle Producten</h2>
    
    <div class="shop-container">
        <aside class="filters">
            <h3>Filters</h3>
            <form method="get" action="all_products.php">
                <div class="filter-group">
                    <label for="category">Categorie:</label>
                    <select name="category" id="category">
                        <option value="">Alle Categorieën</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat; ?>" <?php echo $category_filter === $cat ? 'selected' : ''; ?>>
                                <?php echo $cat; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="max_price">Maximale Prijs: &euro;<span id="price_val"><?php echo $max_price_filter > 0 ? $max_price_filter : '200'; ?></span></label>
                    <input type="range" name="max_price" id="max_price" min="0" max="200" step="5" value="<?php echo $max_price_filter > 0 ? $max_price_filter : 200; ?>" oninput="document.getElementById('price_val').innerText = this.value">
                </div>
                
                <button type="submit" class="btn">Filteren</button>
                <a href="all_products.php" class="btn btn-secondary">Reset</a>
            </form>
        </aside>

        <main class="product-display">
            <?php if (empty($filtered_products)): ?>
                <p>Geen producten gevonden die aan de criteria voldoen.</p>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($filtered_products as $product): ?>
                        <div class="product-item">
                            <div class="category-tag"><?php echo $product['category']; ?></div>
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <h3><?php echo $product['name']; ?></h3>
                            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem; flex-grow: 1;"><?php echo $product['description']; ?></p>
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
            <?php endif; ?>
        </main>
    </div>
</div>

<?php
include 'footer.php';
?>
