<?php
declare(strict_types=1);

require __DIR__ . '/../includes/db.php';
require __DIR__ . '/includes/auth.php';
require_admin_login();

$actionMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $actionMessage = 'Session expired, please try again.';
    } else {
        $id = (int) ($_POST['id'] ?? 0);
        $action = $_POST['action'] ?? '';

        if ($id > 0 && $action === 'mark_read') {
            $stmt = $pdo->prepare('UPDATE contact_submissions SET is_read = 1 WHERE id = :id');
            $stmt->execute(['id' => $id]);
        } elseif ($id > 0 && $action === 'mark_unread') {
            $stmt = $pdo->prepare('UPDATE contact_submissions SET is_read = 0 WHERE id = :id');
            $stmt->execute(['id' => $id]);
        } elseif ($id > 0 && $action === 'delete') {
            $stmt = $pdo->prepare('DELETE FROM contact_submissions WHERE id = :id');
            $stmt->execute(['id' => $id]);
        }

        header('Location: index.php' . (isset($_GET['q']) ? '?q=' . urlencode($_GET['q']) : ''));
        exit;
    }
}

$search = trim($_GET['q'] ?? '');
$page   = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$where  = '';
$params = [];
if ($search !== '') {
    $where = 'WHERE name LIKE :q OR email LIKE :q OR phone LIKE :q OR message LIKE :q';
    $params['q'] = '%' . $search . '%';
}

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM contact_submissions $where");
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));

$listStmt = $pdo->prepare("SELECT * FROM contact_submissions $where ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
foreach ($params as $k => $v) {
    $listStmt->bindValue(':' . $k, $v);
}
$listStmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$listStmt->execute();
$rows = $listStmt->fetchAll();

$unreadCount = (int) $pdo->query('SELECT COUNT(*) FROM contact_submissions WHERE is_read = 0')->fetchColumn();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Contact Queries — Admin</title>
<?php require __DIR__ . '/includes/style.php'; ?>
</head>
<body>

<div class="admin-topbar">
    <div class="brand">Arpan Township — Admin</div>
    <div>
        <span style="margin-right:16px; font-size:13px;">Signed in as <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="admin-wrap">
    <div class="admin-card">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
            <div>
                <h2 style="margin:0 0 4px;">Contact Queries</h2>
                <p style="margin:0; color:#7a7a7a; font-size:13px;">
                    <?php echo $total; ?> total &middot; <?php echo $unreadCount; ?> unread
                </p>
            </div>
            <form method="get" action="index.php" class="admin-search">
                <input type="text" name="q" placeholder="Search name, email, phone..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="admin-btn ghost">Search</button>
            </form>
        </div>

        <?php if ($actionMessage): ?>
            <div class="admin-alert error"><?php echo htmlspecialchars($actionMessage); ?></div>
        <?php endif; ?>

        <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$rows): ?>
                    <tr><td colspan="7" style="text-align:center; color:#999; padding:24px;">No queries found.</td></tr>
                <?php endif; ?>
                <?php foreach ($rows as $row): ?>
                    <tr class="<?php echo $row['is_read'] ? '' : 'unread'; ?>">
                        <td><?php echo htmlspecialchars(date('d M Y, h:i A', strtotime($row['created_at']))); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td class="msg-cell"><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td>
                            <?php if ($row['is_read']): ?>
                                <span class="badge read">Read</span>
                            <?php else: ?>
                                <span class="badge unread">New</span>
                            <?php endif; ?>
                        </td>
                        <td class="admin-actions">
                            <form method="post" action="index.php<?php echo $search !== '' ? '?q=' . urlencode($search) : ''; ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                <?php if ($row['is_read']): ?>
                                    <input type="hidden" name="action" value="mark_unread">
                                    <button type="submit">Mark unread</button>
                                <?php else: ?>
                                    <input type="hidden" name="action" value="mark_read">
                                    <button type="submit">Mark read</button>
                                <?php endif; ?>
                            </form>
                            <form method="post" action="index.php<?php echo $search !== '' ? '?q=' . urlencode($search) : ''; ?>" onsubmit="return confirm('Delete this query permanently?');">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="admin-pagination">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <?php if ($p === $page): ?>
                    <span class="current"><?php echo $p; ?></span>
                <?php else: ?>
                    <a href="index.php?page=<?php echo $p; ?><?php echo $search !== '' ? '&q=' . urlencode($search) : ''; ?>"><?php echo $p; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
