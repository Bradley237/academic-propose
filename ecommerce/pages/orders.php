<?php
$pageTitle = "My Orders";
require_once __DIR__ . '/../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];
$orders = mysqli_query($conn, "SELECT * FROM payment WHERE user_id=$uid ORDER BY paid_at DESC");
?>

<h2 class="page-title">My Orders 📦</h2>

<?php if (mysqli_num_rows($orders) > 0): ?>
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                <tr>
                    <td>#<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td><strong>$<?= number_format($order['total_amount'], 2) ?></strong></td>
                    <td><?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?></td>
                    <td>
                        <?php
                        $badge = ['completed'=>'#d8f3dc','pending'=>'#fff3cd','failed'=>'#ffe0e0'];
                        $color = ['completed'=>'#1b4332','pending'=>'#856404','failed'=>'#c0392b'];
                        $status = $order['status'];
                        ?>
                        <span style="background:<?= $badge[$status] ?>;color:<?= $color[$status] ?>;padding:3px 10px;border-radius:20px;font-size:13px;font-weight:700;">
                            <?= ucfirst($status) ?>
                        </span>
                    </td>
                    <td><?= date('M j, Y H:i', strtotime($order['paid_at'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="no-items">
        <p>You haven't placed any orders yet.</p>
        <a href="products.php" class="btn btn-primary mt-10">Start Shopping</a>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
