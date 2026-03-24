<?php
$pageTitle = "Manage Products";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

$success = "";
$error = "";

// Delete product
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM product WHERE id=$id");
    $success = "Product deleted.";
}

$products = mysqli_query($conn, "
    SELECT p.*, c.name as category_name 
    FROM product p 
    LEFT JOIN category c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
");
?>
<div class="flex-between mb-20">
    <h2 class="page-title" style="margin-bottom:0;">Manage Products 📦</h2>
    <a href="add_product.php" class="btn btn-primary">+ Add Product</a>
</div>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<div class="table-box">
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Category</th><th>Stock</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if (mysqli_num_rows($products) > 0): ?>
            <?php while ($p = mysqli_fetch_assoc($products)): ?>
            <tr>
                <td>#<?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td>$<?= number_format($p['price'], 2) ?></td>
                <td><?= htmlspecialchars($p['category_name'] ?? 'None') ?></td>
                <td><?= $p['stock'] ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="manage_products.php?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('Delete this product?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;color:#aaa;">No products yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
