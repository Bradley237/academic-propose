<?php
$pageTitle = "Manage Orders";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

$success = "";

if (isset($_GET['update']) && is_numeric($_GET['update']) && isset($_GET['status'])) {
    $oid    = (int)$_GET['update'];
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    if (in_array($status, ['pending','completed','failed'])) {
        mysqli_query($conn, "UPDATE payment SET status='$status' WHERE id=$oid");
        $success = "Order status updated!";
    }
}

$orders = mysqli_query($conn, "SELECT p.*, u.username, u.email FROM payment p JOIN users u ON p.user_id=u.id ORDER BY p.paid_at DESC");
?>
<h2 class="page-title">Manage Orders 🧾</h2>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<div class="table-box">
    <table>
        <thead><tr><th>Order ID</th><th>Customer</th><th>Total</th><th>Method</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        <?php if (mysqli_num_rows($orders) > 0): ?>
            <?php while ($o = mysqli_fetch_assoc($orders)):
                $badge = ['completed'=>'#d8f3dc','pending'=>'#fff3cd','failed'=>'#ffe0e0'];
                $color = ['completed'=>'#1b4332','pending'=>'#856404','failed'=>'#c0392b'];
                $s = $o['status'];
            ?>
            <tr>
                <td>#<?= str_pad($o['id'],4,'0',STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($o['username']) ?></td>
                <td><strong>$<?= number_format($o['total_amount'],2) ?></strong></td>
                <td><?= ucfirst(str_replace('_',' ',$o['payment_method'])) ?></td>
                <td><span style="background:<?= $badge[$s] ?>;color:<?= $color[$s] ?>;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;"><?= ucfirst($s) ?></span></td>
                <td><?= date('M j, Y', strtotime($o['paid_at'])) ?></td>
                <td style="display:flex;gap:4px;flex-wrap:wrap;">
                    <a href="?update=<?= $o['id'] ?>&status=completed" class="btn btn-primary btn-sm">✅</a>
                    <a href="?update=<?= $o['id'] ?>&status=pending" class="btn btn-warning btn-sm">⏳</a>
                    <a href="?update=<?= $o['id'] ?>&status=failed" class="btn btn-danger btn-sm">❌</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align:center;color:#aaa;">No orders yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
