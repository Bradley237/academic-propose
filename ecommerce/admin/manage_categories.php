<?php
$pageTitle = "Manage Categories";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

$success = ""; $error = "";

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM category WHERE id=$id");
    $success = "Category deleted.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $desc = trim(mysqli_real_escape_string($conn, $_POST['description']));
    if (empty($name)) { $error = "Category name is required."; }
    else {
        mysqli_query($conn, "INSERT INTO category (name, description) VALUES ('$name','$desc')");
        $success = "Category added!";
    }
}

$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY name");
?>
<h2 class="page-title">Manage Categories 🏷️</h2>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 350px;gap:24px;flex-wrap:wrap;">
    <div class="table-box">
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (mysqli_num_rows($categories) > 0): ?>
                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <tr>
                    <td>#<?= $cat['id'] ?></td>
                    <td><?= htmlspecialchars($cat['name']) ?></td>
                    <td><?= htmlspecialchars($cat['description']) ?></td>
                    <td><a href="manage_categories.php?delete=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('Delete this category?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;color:#aaa;">No categories yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="form-box" style="margin:0;max-width:none;">
        <h3 style="font-family:'Fredoka One',cursive;color:#2d6a4f;font-size:20px;margin-bottom:16px;">Add Category</h3>
        <form method="POST">
            <div class="form-group"><label>Category Name</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Description</label><textarea name="description"></textarea></div>
            <button type="submit" class="btn btn-primary" style="width:100%">Add Category</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
