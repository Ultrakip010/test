<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Mijn Webshop</title>
    <!-- Modern Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center;">
            <div class="logo">
                <h1 style="margin: 0;"><a href="index.php" style="color: white; text-decoration: none;">Mijn Webshop</a></h1>
            </div>
            <nav style="display: flex; gap: 1.5rem; align-items: center;">
                <a href="index.php" style="color: #cbd5e1; text-decoration: none; font-weight: 500;">Home</a>
                <a href="all_products.php" style="color: #cbd5e1; text-decoration: none; font-weight: 500;">Producten</a>
                <a href="cart.php" style="color: white; text-decoration: none; font-weight: 600; background: var(--primary-color); padding: 0.5rem 1rem; border-radius: 8px; display: flex; align-items: center; gap: 0.5rem;">
                    <span>🛒</span>
                    Winkelmandje (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)
                </a>
            </nav>
        </div>
    </header>