<?php
declare(strict_types=1);

require __DIR__ . '/../includes/db.php';
require __DIR__ . '/includes/auth.php';

if (admin_logged_in()) {
    header('Location: index.php');
    exit;
}

$adminCount = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
if ($adminCount === 0) {
    header('Location: setup.php');
    exit;
}

const MAX_ATTEMPTS     = 5;
const LOCKOUT_MINUTES  = 15;
const DUMMY_HASH       = '$2y$10$Qk5F3nW1s0m5Zc8b7l9k9uV8m0m4nQ6f9zH2x3c4v5b6n7m8k9l0e';

$error = '';
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Your session expired. Please reload the page and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        $since = date('Y-m-d H:i:s', time() - LOCKOUT_MINUTES * 60);
        $chk = $pdo->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip_address = :ip AND success = 0 AND attempted_at > :since');
        $chk->execute(['ip' => $ip, 'since' => $since]);
        $recentFailures = (int) $chk->fetchColumn();

        if ($recentFailures >= MAX_ATTEMPTS) {
            $error = 'Too many failed attempts. Please try again in ' . LOCKOUT_MINUTES . ' minutes.';
        } else {
            $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = :u LIMIT 1');
            $stmt->execute(['u' => $username]);
            $admin = $stmt->fetch();

            if ($admin) {
                $ok = password_verify($password, $admin['password_hash']);
            } else {
                password_verify($password, DUMMY_HASH); // keep timing consistent whether or not the user exists
                $ok = false;
            }

            $log = $pdo->prepare('INSERT INTO login_attempts (username, ip_address, success, attempted_at) VALUES (:u, :ip, :s, NOW())');
            $log->execute(['u' => $username, 'ip' => $ip, 's' => $ok ? 1 : 0]);

            if ($ok) {
                session_regenerate_id(true);
                $_SESSION['admin_id']       = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['last_activity']  = time();

                $upd = $pdo->prepare('UPDATE admin_users SET last_login_at = NOW() WHERE id = :id');
                $upd->execute(['id' => $admin['id']]);

                header('Location: index.php');
                exit;
            }

            $error = 'Invalid username or password.';
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
<title>Admin Login</title>
<?php require __DIR__ . '/includes/style.php'; ?>
</head>
<body>
<div class="admin-auth-page">
    <div class="admin-auth-box">
        <img src="../images/logo.png" alt="Arpan Township" class="admin-auth-logo">
        <h1>Arpan Township</h1>
        <p class="sub">Admin login</p>

        <?php if (isset($_GET['timeout'])): ?>
            <div class="admin-alert error">You were signed out due to inactivity.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="admin-alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="login.php" autocomplete="off">
            <?php echo csrf_field(); ?>
            <div class="admin-field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div class="admin-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="admin-btn">Login</button>
        </form>
    </div>
</div>
</body>
</html>
