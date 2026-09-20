<?php
declare(strict_types=1);

require __DIR__ . '/../includes/db.php';
require __DIR__ . '/includes/auth.php';

$adminCount = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();

$error = '';
$success = false;

if ($adminCount > 0) {
    // Setup already completed — this page refuses to create additional accounts.
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Your session expired. Please reload the page and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $confirm  = (string) ($_POST['password_confirm'] ?? '');

        if ($username === '' || strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
            $error = 'Username must be at least 3 characters (letters, numbers, _ . - only).';
        } elseif (strlen($password) < 10) {
            $error = 'Password must be at least 10 characters long.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $ins = $pdo->prepare('INSERT INTO admin_users (username, password_hash, created_at) VALUES (:u, :p, NOW())');
            $ins->execute([
                'u' => $username,
                'p' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            $success = true;
            $adminCount = 1;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Admin Setup</title>
<?php require __DIR__ . '/includes/style.php'; ?>
</head>
<body>
<div class="admin-auth-page">
    <div class="admin-auth-box">
        <h1>Create Admin Account</h1>
        <p class="sub">One-time setup for the Arpan Township admin panel.</p>

        <?php if ($adminCount > 0 && !$success): ?>
            <div class="admin-alert error">An admin account already exists. Setup is locked.</div>
            <a class="admin-btn" href="login.php">Go to Login</a>
        <?php elseif ($success): ?>
            <div class="admin-alert success">Admin account created successfully.</div>
            <a class="admin-btn" href="login.php">Go to Login</a>
        <?php else: ?>
            <?php if ($error): ?>
                <div class="admin-alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" action="setup.php" autocomplete="off">
                <?php echo csrf_field(); ?>
                <div class="admin-field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required minlength="3" autocomplete="off">
                </div>
                <div class="admin-field">
                    <label for="password">Password (min 10 characters)</label>
                    <input type="password" id="password" name="password" required minlength="10" autocomplete="new-password">
                </div>
                <div class="admin-field">
                    <label for="password_confirm">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="10" autocomplete="new-password">
                </div>
                <button type="submit" class="admin-btn">Create Account</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
