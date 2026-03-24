<?php
$pageTitle = "Products";
require_once __DIR__ . '/../includes/header.php';

// Filter by category
$category_filter = "";
$where = "";
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $cat_id = (int)$_GET['category'];
    $where = "WHERE p.category_id = $cat_id";
}

// Search
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $where = $where ? $where . " AND p.name LIKE '%$search%'" : "WHERE p.name LIKE '%$search%'";
}

$products = mysqli_query($conn, "
    SELECT p.*, c.name as category_name 
    FROM product p 
    LEFT JOIN category c ON p.category_id = c.id 
    $where
    ORDER BY p.created_at DESC
");

$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY name");
?>

<div class="flex-between mb-20">
    <h2 class="page-title" style="margin-bottom:0;">All Products 🛍️</h2>
</div>

<!-- Search & Filter Bar -->
<form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
    <input type="text" name="search" placeholder="Search products..." 
        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
        style="padding:10px 14px;border:2px solid #dee2e6;border-radius:8px;font-family:'Nunito',sans-serif;font-size:15px;flex:1;min-width:200px;">
    <select name="category" style="padding:10px 14px;border:2px solid #dee2e6;border-radius:8px;font-family:'Nunito',sans-serif;font-size:15px;">
        <option value="">All Categories</option>
        <?php
        mysqli_data_seek($categories, 0);
        while ($cat = mysqli_fetch_assoc($categories)):
        ?>
            <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit" class="btn btn-primary">Search</button>
    <a href="products.php" class="btn btn-secondary">Reset</a>
</form>

<?php if (mysqli_num_rows($products) > 0): ?>
    <div class="products-grid">
        <?php while ($product = mysqli_fetch_assoc($products)): ?>
            <div class="product-card">
                <?php if (!empty($product['image']) && file_exists(__DIR__ . '/../assets/images/' . $product['image'])): ?>
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
                    <p style="font-size:13px;color:#888;margin:6px 0;">Stock: <?= $product['stock'] ?></p>
                    <a href="/ecommerce/pages/product_detail.php?id=<?= $product['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="no-items">😕 No products found. Try a different search!</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
