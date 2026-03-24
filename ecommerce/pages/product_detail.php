<?php
$pageTitle = "Product Detail";
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = (int)$_GET['id'];
$result = mysqli_query($conn, "
    SELECT p.*, c.name as category_name 
    FROM product p 
    LEFT JOIN category c ON p.category_id = c.id 
    WHERE p.id = $id
");

if (mysqli_num_rows($result) === 0) {
    echo '<div class="alert alert-error">Product not found.</div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit();
}

$product = mysqli_fetch_assoc($result);
$success = "";
$error   = "";

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isLoggedIn()) {
        header("Location: /ecommerce/pages/login.php");
        exit();
    }

    $qty = max(1, (int)$_POST['quantity']);
    $uid = $_SESSION['user_id'];

    // Check if already in cart
    $existing = mysqli_query($conn, "SELECT * FROM cart WHERE user_id=$uid AND product_id=$id");
    if (mysqli_num_rows($existing) > 0) {
        $row = mysqli_fetch_assoc($existing);
        $new_qty = $row['quantity'] + $qty;
        mysqli_query($conn, "UPDATE cart SET quantity=$new_qty WHERE user_id=$uid AND product_id=$id");
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($uid, $id, $qty)");
    }
    $success = "Product added to cart! <a href='/ecommerce/pages/cart.php'>View Cart</a>";
}
?>

<a href="products.php" style="color:#2d6a4f;font-weight:700;">← Back to Products</a>

<div style="background:#fff;border-radius:14px;padding:30px;margin-top:16px;box-shadow:0 2px 10px rgba(0,0,0,0.08);display:flex;gap:30px;flex-wrap:wrap;">
    <div style="flex:0 0 280px;">
        <?php if (!empty($product['image']) && file_exists(__DIR__ . '/../assets/images/' . $product['image'])): ?>
            <img src="/ecommerce/assets/images/<?= htmlspecialchars($product['image']) ?>" 
                 alt="<?= htmlspecialchars($product['name']) ?>" 
                 style="width:100%;border-radius:10px;max-height:300px;object-fit:cover;">
        <?php else: ?>
            <div style="width:100%;height:260px;background:linear-gradient(135deg,#d8f3dc,#b7e4c7);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:72px;">📦</div>
        <?php endif; ?>
    </div>

    <div style="flex:1;min-width:240px;">
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <h1 style="font-family:'Fredoka One',cursive;color:#1b4332;font-size:30px;margin-bottom:10px;"><?= htmlspecialchars($product['name']) ?></h1>
        
        <?php if ($product['category_name']): ?>
            <span class="category-tag"><?= htmlspecialchars($product['category_name']) ?></span>
        <?php endif; ?>

        <div style="font-size:28px;font-weight:700;color:#2d6a4f;margin:14px 0;">
            $<?= number_format($product['price'], 2) ?>
        </div>

        <p style="color:#555;line-height:1.6;margin-bottom:16px;">
            <?= nl2br(htmlspecialchars($product['description'] ?? 'No description available.')) ?>
        </p>

        <p style="font-size:14px;color:#888;">
            <strong>In stock:</strong> <?= $product['stock'] ?> units
        </p>

        <?php if ($product['stock'] > 0): ?>
            <form method="POST" style="margin-top:20px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" class="qty-input">
                <button type="submit" name="add_to_cart" class="btn btn-primary">🛒 Add to Cart</button>
            </form>
        <?php else: ?>
            <div class="alert alert-error" style="margin-top:16px;">Out of stock</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
