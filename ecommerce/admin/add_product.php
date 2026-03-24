<?php
$pageTitle = "Add Product";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

$success = "";
$error = "";
$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $desc     = trim(mysqli_real_escape_string($conn, $_POST['description']));
    $price    = (float)$_POST['price'];
    $stock    = (int)$_POST['stock'];
    $cat_id   = (int)$_POST['category_id'];
    $uid      = $_SESSION['user_id'];
    $image    = "";

    if (empty($name) || $price <= 0) {
        $error = "Name and a valid price are required.";
    } else {
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array($ext, $allowed)) {
                $image = uniqid('prod_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/images/' . $image);
            } else {
                $error = "Only JPG, PNG, GIF, WEBP images are allowed.";
            }
        }

        if (!$error) {
            mysqli_query($conn, "INSERT INTO product (name, description, price, stock, category_id, image, created_by) VALUES ('$name','$desc',$price,$stock,$cat_id,'$image',$uid)");
            $success = "Product added successfully!";
        }
    }
}
?>
<a href="manage_products.php" style="color:#2d6a4f;font-weight:700;">← Back to Products</a>
<div class="form-box" style="max-width:600px;margin-top:20px;">
    <h2>Add New Product</h2>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group"><label>Product Name</label><input type="text" name="name" required></div>
        <div class="form-group"><label>Description</label><textarea name="description"></textarea></div>
        <div class="form-group"><label>Price ($)</label><input type="number" name="price" step="0.01" min="0" required></div>
        <div class="form-group"><label>Stock Quantity</label><input type="number" name="stock" value="0" min="0" required></div>
        <div class="form-group">
            <label>Category</label>
            <select name="category_id">
                <option value="0">-- No Category --</option>
                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group"><label>Product Image (optional)</label><input type="file" name="image" accept="image/*"></div>
        <button type="submit" class="btn btn-primary" style="width:100%">Add Product</button>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
