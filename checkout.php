<?php
session_start();
require_once 'products.php';

$order_placed = false;
$mail_sent = false;

if (isset($_POST['place_order']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    
    // Verzamel besteldetails voor de e-mail
    $order_details = "";
    $total_price = 0;
    
    $all_products = $products;
    foreach ($_SESSION['cart'] as $id => $quantity) {
        foreach ($all_products as $product) {
            if ($product['id'] == $id) {
                $subtotal = $product['price'] * $quantity;
                $total_price += $subtotal;
                $order_details .= "- " . $product['name'] . " (" . $quantity . "x) - €" . number_format($subtotal, 2, ',', '.') . "\n";
                break;
            }
        }
    }
    
    // E-mail samenstellen
    $to = $email;
    $subject = "Bevestiging van je bestelling bij Mijn Webshop";
    $message = "Beste " . $name . ",\n\n";
    $message .= "Bedankt voor je bestelling! Hieronder vind je een overzicht van je aankopen:\n\n";
    $message .= $order_details . "\n";
    $message .= "Totaalbedrag: €" . number_format($total_price, 2, ',', '.') . "\n\n";
    $message .= "Bezorgadres:\n" . $address . "\n\n";
    $message .= "We gaan direct aan de slag met je bestelling.\n\n";
    $message .= "Met vriendelijke groet,\nMijn Webshop Team";
    
    $headers = "From: no-reply@mijnwebshop.nl\r\n";
    $headers .= "Reply-To: support@mijnwebshop.nl\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Verstuur de e-mail
    if (mail($to, $subject, $message, $headers)) {
        $mail_sent = true;
    }

    // Hier zou je normaal de bestelling in de database opslaan
    unset($_SESSION['cart']);
    $order_placed = true;
}

$page_title = 'Afrekenen';
require_once 'header.php';
?>

    <div class="container">
        <?php if ($order_placed): ?>
            <div class="text-center">
                <h2 class="mb-2">Bedankt voor je bestelling!</h2>
                <p>Je bestelling is succesvol geplaatst. We nemen zo snel mogelijk contact met je op.</p>
                <?php if ($mail_sent): ?>
                    <p>Er is een bevestigingsmail gestuurd naar <strong><?php echo htmlspecialchars($email); ?></strong>.</p>
                <?php else: ?>
                    <p>Helaas konden we geen bevestigingsmail sturen, maar je bestelling is wel ontvangen.</p>
                <?php endif; ?>
                <div style="margin-top: 2rem;">
                    <a href="index.php" class="btn" style="width: auto;">Terug naar de winkel</a>
                </div>
            </div>
        <?php else: ?>
            <h2 class="mb-2 text-center">Afrekenen</h2>
            <form method="post" style="max-width: 600px; margin: 0 auto; background: var(--card-bg); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border-color); box-shadow: var(--shadow);">
                <div style="margin-bottom: 1.5rem;">
                    <label for="name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Naam:</label>
                    <input type="text" id="name" name="name" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); font-family: inherit;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">E-mail:</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); font-family: inherit;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label for="address" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Adres:</label>
                    <textarea id="address" name="address" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); font-family: inherit; min-height: 100px;"></textarea>
                </div>
                <button type="submit" name="place_order" class="btn">Plaats Bestelling</button>
            </form>
        <?php endif; ?>
    </div>

<?php
include 'footer.php';
?>