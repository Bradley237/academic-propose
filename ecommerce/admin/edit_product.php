<?php
$pageTitle = "Edit Product";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { header("Location: manage_products.php"); exit(); }
$id = (int)$_GET['id'];
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM product WHERE id=$id"));
if (!$product) { header("Location: manage_products.php"); exit(); }

$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY name");
$success = ""; $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $desc  = trim(mysqli_real_escape_string($conn, $_POST['description']));
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $cat   = (int)$_POST['category_id'];
    $image = $product['image'];

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            $image = uniqid('prod_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/images/' . $image);
        }
    }
    mysqli_query($conn, "UPDATE product SET name='$name',description='$desc',price=$price,stock=$stock,category_id=$cat,image='$image' WHERE id=$id");
    $success = "Product updated!";
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM product WHERE id=$id"));
}
?>
<a href="manage_products.php" style="color:#2d6a4f;font-weight:700;">← Back to Products</a>
<div class="form-box" style="max-width:600px;margin-top:20px;">
    <h2>Edit Product</h2>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Product Name</label><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required></div>
        <div class="form-group"><label>Description</label><textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea></div>
        <div class="form-group"><label>Price ($)</label><input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required></div>
        <div class="form-group"><label>Stock</label><input type="number" name="stock" value="<?= $product['stock'] ?>"></div>
        <div class="form-group">
            <label>Category</label>
            <select name="category_id">
                <option value="0">-- No Category --</option>
                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Change Image (optional)</label>
            <?php if ($product['image']): ?><p style="font-size:13px;color:#888;">Current: <?= $product['image'] ?></p><?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Save Changes</button>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
