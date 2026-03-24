<?php
$pageTitle = "Order Placed!";
require_once __DIR__ . '/../includes/header.php';
requireLogin();
?>

<div style="text-align:center;padding:60px 20px;">
    <div style="font-size:80px;margin-bottom:20px;">🎉</div>
    <h1 style="font-family:'Fredoka One',cursive;color:#2d6a4f;font-size:36px;margin-bottom:12px;">Order Placed Successfully!</h1>
    <p style="color:#555;font-size:17px;margin-bottom:24px;">Thank you for your order! We will get it to you soon.</p>
    <a href="/ecommerce/index.php" class="btn btn-primary" style="font-size:16px;">Continue Shopping</a>
    &nbsp;
    <a href="/ecommerce/pages/orders.php" class="btn btn-secondary" style="font-size:16px;">View My Orders</a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
