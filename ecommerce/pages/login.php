<?php
$pageTitle = "Login";
require_once __DIR__ . '/../includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: /ecommerce/index.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter your username and password.";
    } else {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$username'");
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id']  = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role']     = $row['role'];

                if ($row['role'] === 'admin') {
                    header("Location: /ecommerce/admin/dashboard.php");
                } else {
                    header("Location: /ecommerce/index.php");
                }
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>

<div class="form-box">
    <h2>Login 👋</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'logout'): ?>
        <div class="alert alert-success">You have been logged out.</div>
    <?php endif; ?>

    <div class="alert alert-info" style="font-size:13px;">
        <strong>Demo Admin:</strong> username: <code>admin</code> / password: <code>password</code>
    </div>

    <form method="POST">
        <div class="form-group">
            <label>Username or Email</label>
            <input type="text" name="username" placeholder="Your username or email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Your password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;font-size:16px;">Login</button>
    </form>

    <p class="text-center mt-10" style="font-size:14px;">
        Don't have an account? <a href="register.php" style="color:#2d6a4f;font-weight:700;">Register here</a>
    </p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
