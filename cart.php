<?php
session_start();
require_once 'products.php';

// Verwijderen uit winkelmandje
if (isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
}

// Productgegevens ophalen voor het mandje
$cart_items = [];
$total_price = 0;

$all_products = $products;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        foreach ($all_products as $product) {
            if ($product['id'] == $id) {
                $subtotal = $product['price'] * $quantity;
                $total_price += $subtotal;
                $cart_items[] = [
                    'id' => $id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
                break;
            }
        }
    }
}
$page_title = 'Winkelmandje';
require_once 'header.php';
?>

    <div class="container">
        <h2 class="mb-2">Jouw Winkelmandje</h2>
        
        <?php if (empty($cart_items)): ?>
            <p>Je winkelmandje is leeg.</p>
            <a href="index.php" class="btn">Ga winkelen</a>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Prijs</th>
                        <th>Aantal</th>
                        <th>Totaal</th>
                        <th>Actie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>&euro;<?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>&euro;<?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="remove" class="btn" style="background: #d9534f;">Verwijder</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="total">
                Totaal: &euro;<?php echo number_format($total_price, 2, ',', '.'); ?>
            </div>
            
            <div style="margin-top: 20px; text-align: right;">
                <a href="checkout.php" class="btn">Afrekenen</a>
            </div>
        <?php endif; ?>
    </div>

<?php
include 'footer.php';
?>