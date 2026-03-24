<?php
$pageTitle = "Register";
require_once __DIR__ . '/../includes/header.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if username or email exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username or email already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed')");
            if ($insert) {
                $success = "Account created! You can now <a href='login.php'>login here</a>.";
            } else {
                $error = "Something went wrong. Try again.";
            }
        }
    }
}
?>

<div class="form-box">
    <h2>Create Account 📝</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="e.g. john123" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="you@example.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="At least 6 characters" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Repeat your password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;font-size:16px;">Register</button>
    </form>

    <p class="text-center mt-10" style="font-size:14px;">
        Already have an account? <a href="login.php" style="color:#2d6a4f;font-weight:700;">Login here</a>
    </p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
