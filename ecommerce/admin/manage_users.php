<?php
$pageTitle = "Manage Users";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>
<h2 class="page-title">Manage Users 👥</h2>
<div class="table-box">
    <table>
        <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
        <tbody>
        <?php if (mysqli_num_rows($users) > 0): ?>
            <?php while ($u = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td>#<?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><span style="background:<?= $u['role']==='admin'?'#f4a261':'#d8f3dc' ?>;color:<?= $u['role']==='admin'?'#fff':'#1b4332' ?>;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;"><?= ucfirst($u['role']) ?></span></td>
                <td><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;color:#aaa;">No users found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
