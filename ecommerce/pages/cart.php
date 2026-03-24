<?php
$pageTitle = "My Cart";
require_once __DIR__ . '/../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];
$success = "";
$error   = "";

// Remove item
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $cart_id = (int)$_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id=$cart_id AND user_id=$uid");
    header("Location: cart.php?msg=removed");
    exit();
}

// Update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $cart_id => $qty) {
        $cart_id = (int)$cart_id;
        $qty = max(1, (int)$qty);
        mysqli_query($conn, "UPDATE cart SET quantity=$qty WHERE id=$cart_id AND user_id=$uid");
    }
    $success = "Cart updated!";
}

if (isset($_GET['msg']) && $_GET['msg'] === 'removed') {
    $success = "Item removed from cart.";
}

// Fetch cart items
$cartItems = mysqli_query($conn, "
    SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.image, p.stock
    FROM cart c
    JOIN product p ON c.product_id = p.id
    WHERE c.user_id = $uid
");

$total = 0;
$items = [];
while ($item = mysqli_fetch_assoc($cartItems)) {
    $items[] = $item;
    $total += $item['price'] * $item['quantity'];
}
?>

<h2 class="page-title">My Cart 🛒</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if (empty($items)): ?>
    <div class="no-items">
        <p style="font-size:48px;">🛒</p>
        <p>Your cart is empty!</p>
        <a href="products.php" class="btn btn-primary mt-10">Shop Now</a>
    </div>
<?php else: ?>
    <form method="POST">
        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <?php if (!empty($item['image']) && file_exists(__DIR__ . '/../assets/images/' . $item['image'])): ?>
                                    <img src="/ecommerce/assets/images/<?= htmlspecialchars($item['image']) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                <?php else: ?>
                                    <div style="width:50px;height:50px;background:#d8f3dc;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:22px;">📦</div>
                                <?php endif; ?>
                                <a href="product_detail.php?id=<?= $item['product_id'] ?>" style="color:#2d6a4f;font-weight:700;">
                                    <?= htmlspecialchars($item['name']) ?>
                                </a>
                            </div>
                        </td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <input type="number" name="qty[<?= $item['cart_id'] ?>]" 
                                   value="<?= $item['quantity'] ?>" 
                                   min="1" max="<?= $item['stock'] ?>" 
                                   class="qty-input">
                        </td>
                        <td><strong>$<?= number_format($item['price'] * $item['quantity'], 2) ?></strong></td>
                        <td>
                            <a href="cart.php?remove=<?= $item['cart_id'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirmDelete('Remove this item from cart?')">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-total-box">
            <div class="cart-total">Total: $<?= number_format($total, 2) ?></div>
            <div style="margin-top:14px;display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
                <button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button>
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout →</a>
            </div>
        </div>
    </form>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
