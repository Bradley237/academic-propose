<?php
$pageTitle = "Admin Dashboard";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

// Stats
$total_users    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as n FROM users WHERE role='user'"))['n'];
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as n FROM product"))['n'];
$total_orders   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as n FROM payment"))['n'];
$total_revenue  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as n FROM payment WHERE status='completed'"))['n'] ?? 0;
$total_cats     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as n FROM category"))['n'];

// Recent orders
$recent_orders = mysqli_query($conn, "
    SELECT p.*, u.username 
    FROM payment p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.paid_at DESC 
    LIMIT 5
");
?>

<h2 class="page-title">Admin Dashboard 🛠️</h2>

<!-- Stats Cards -->
<div class="dashboard-cards">
    <a href="manage_products.php" class="dash-card">
        <div class="dash-icon">📦</div>
        <h3>Products</h3>
        <div class="dash-num"><?= $total_products ?></div>
    </a>
    <a href="manage_categories.php" class="dash-card">
        <div class="dash-icon">🏷️</div>
        <h3>Categories</h3>
        <div class="dash-num"><?= $total_cats ?></div>
    </a>
    <a href="manage_users.php" class="dash-card">
        <div class="dash-icon">👥</div>
        <h3>Users</h3>
        <div class="dash-num"><?= $total_users ?></div>
    </a>
    <a href="manage_orders.php" class="dash-card">
        <div class="dash-icon">🧾</div>
        <h3>Orders</h3>
        <div class="dash-num"><?= $total_orders ?></div>
    </a>
    <div class="dash-card">
        <div class="dash-icon">💰</div>
        <h3>Revenue</h3>
        <div class="dash-num" style="font-size:22px;">$<?= number_format($total_revenue, 2) ?></div>
    </div>
</div>

<!-- Quick Actions -->
<div class="flex-between mb-20">
    <h3 style="font-family:'Fredoka One',cursive;color:#2d6a4f;font-size:22px;">Quick Actions</h3>
</div>
<div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:30px;">
    <a href="add_product.php" class="btn btn-primary">+ Add Product</a>
    <a href="manage_categories.php" class="btn btn-warning">+ Add Category</a>
    <a href="manage_orders.php" class="btn btn-secondary">View All Orders</a>
    <a href="/ecommerce/index.php" class="btn btn-secondary">View Shop</a>
</div>

<!-- Recent Orders -->
<h3 style="font-family:'Fredoka One',cursive;color:#2d6a4f;font-size:22px;margin-bottom:14px;">Recent Orders</h3>
<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($recent_orders) > 0): ?>
                <?php while ($o = mysqli_fetch_assoc($recent_orders)): ?>
                <tr>
                    <td>#<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($o['username']) ?></td>
                    <td><strong>$<?= number_format($o['total_amount'], 2) ?></strong></td>
                    <td><?= ucfirst(str_replace('_',' ',$o['payment_method'])) ?></td>
                    <td>
                        <?php
                        $badge = ['completed'=>'#d8f3dc','pending'=>'#fff3cd','failed'=>'#ffe0e0'];
                        $color = ['completed'=>'#1b4332','pending'=>'#856404','failed'=>'#c0392b'];
                        $status = $o['status'];
                        ?>
                        <span style="background:<?= $badge[$status] ?>;color:<?= $color[$status] ?>;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                            <?= ucfirst($status) ?>
                        </span>
                    </td>
                    <td><?= date('M j, Y', strtotime($o['paid_at'])) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;color:#aaa;">No orders yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
