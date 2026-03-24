<?php
$pageTitle = "Home";
require_once __DIR__ . '/includes/header.php';

// Get latest 6 products
$result = mysqli_query($conn, "
    SELECT p.*, c.name as category_name 
    FROM product p 
    LEFT JOIN category c ON p.category_id = c.id 
    ORDER BY p.created_at DESC 
    LIMIT 6
");
?>

<!-- Hero Section -->
<div class="hero">
    <h1>Welcome to MyShop 🛍️</h1>
    <p>Find great products at amazing prices!</p>
    <a href="/ecommerce/pages/products.php" class="btn btn-primary" style="background:#fff;color:#2d6a4f;font-size:17px;">Browse Products</a>
</div>

<!-- Latest Products -->
<div class="flex-between mb-20">
    <h2 class="page-title" style="margin-bottom:0;">Latest Products</h2>
    <a href="/ecommerce/pages/products.php" class="btn btn-primary btn-sm">View All →</a>
</div>

<?php if (mysqli_num_rows($result) > 0): ?>
    <div class="products-grid">
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <?php if (!empty($product['image']) && file_exists(__DIR__ . '/assets/images/' . $product['image'])): ?>
                    <img src="/ecommerce/assets/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <?php else: ?>
                    <div class="product-img-placeholder">📦</div>
                <?php endif; ?>
                <div class="product-card-body">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="price">$<?= number_format($product['price'], 2) ?></div>
                    <?php if ($product['category_name']): ?>
                        <span class="category-tag"><?= htmlspecialchars($product['category_name']) ?></span>
                    <?php endif; ?>
                    <br>
                    <a href="/ecommerce/pages/product_detail.php?id=<?= $product['id'] ?>" class="btn btn-primary btn-sm mt-10">View Product</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="no-items">No products yet. Check back soon! 😊</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
