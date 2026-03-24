<?php
$pageTitle = "Checkout";
require_once __DIR__ . '/../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];

// Fetch cart items
$cartItems = mysqli_query($conn, "
    SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price
    FROM cart c
    JOIN product p ON c.product_id = p.id
    WHERE c.user_id = $uid
");

$items = [];
$total = 0;
while ($item = mysqli_fetch_assoc($cartItems)) {
    $items[] = $item;
    $total += $item['price'] * $item['quantity'];
}

if (empty($items)) {
    header("Location: cart.php");
    exit();
}

$success = "";
$error   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = mysqli_real_escape_string($conn, $_POST['payment_method'] ?? 'cash');
    
    // Create payment record
    $insert = mysqli_query($conn, "INSERT INTO payment (user_id, total_amount, payment_method, status) VALUES ($uid, $total, '$method', 'completed')");
    
    if ($insert) {
        // Clear cart
        mysqli_query($conn, "DELETE FROM cart WHERE user_id=$uid");
        header("Location: order_success.php");
        exit();
    } else {
        $error = "Payment failed. Please try again.";
    }
}
?>

<h2 class="page-title">Checkout 💳</h2>

<?php if ($error): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 340px;gap:24px;flex-wrap:wrap;">
    <!-- Order Summary -->
    <div class="table-box">
        <h3 style="font-family:'Fredoka One',cursive;color:#2d6a4f;font-size:20px;margin-bottom:16px;">Order Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td><strong>$<?= number_format($item['price'] * $item['quantity'], 2) ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="text-align:right;margin-top:16px;font-size:20px;font-weight:700;color:#2d6a4f;">
            Total: $<?= number_format($total, 2) ?>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="form-box" style="margin:0;max-width:none;">
        <h3 style="font-family:'Fredoka One',cursive;color:#2d6a4f;font-size:20px;margin-bottom:16px;">Payment Info</h3>
        <form method="POST">
            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method">
                    <option value="cash">Cash on Delivery</option>
                    <option value="card">Credit/Debit Card</option>
                    <option value="mobile_money">Mobile Money</option>
                </select>
            </div>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" placeholder="Your full name" required>
            </div>
            <div class="form-group">
                <label>Delivery Address</label>
                <textarea placeholder="Enter your address"></textarea>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" placeholder="+1 234 567 8900">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;font-size:16px;">
                ✅ Place Order ($<?= number_format($total, 2) ?>)
            </button>
        </form>
        <a href="cart.php" style="display:block;text-align:center;margin-top:10px;color:#888;font-size:13px;">← Back to cart</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
