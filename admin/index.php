<?php
declare(strict_types=1);

require __DIR__ . '/../includes/db.php';
require __DIR__ . '/includes/auth.php';
require_admin_login();

const LEAD_STATUSES = [
    'new'         => 'New',
    'in_progress' => 'In Progress',
    'follow_up'   => 'Follow Up',
    'deal_close'  => 'Deal Close',
    'dead_query'  => 'Dead Query',
];

function admin_wa_number(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone) ?? '';
    if (strlen($digits) === 10) {
        return '91' . $digits;
    }
    if (strlen($digits) === 11 && $digits[0] === '0') {
        return '91' . substr($digits, 1);
    }
    return $digits;
}

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
        } elseif ($id > 0 && $action === 'set_status') {
            $status = $_POST['status'] ?? '';
            if (array_key_exists($status, LEAD_STATUSES)) {
                $stmt = $pdo->prepare('UPDATE contact_submissions SET status = :status WHERE id = :id');
                $stmt->execute(['status' => $status, 'id' => $id]);
            }
        } elseif ($id > 0 && $action === 'delete') {
            $stmt = $pdo->prepare('DELETE FROM contact_submissions WHERE id = :id');
            $stmt->execute(['id' => $id]);
        }

        $qs = [];
        if (isset($_GET['q'])) { $qs['q'] = $_GET['q']; }
        if (isset($_GET['status'])) { $qs['status'] = $_GET['status']; }
        if (isset($_GET['page'])) { $qs['page'] = $_GET['page']; }
        header('Location: index.php' . ($qs ? '?' . http_build_query($qs) : ''));
        exit;
    }
}

$search       = trim($_GET['q'] ?? '');
$statusFilter = $_GET['status'] ?? '';
if (!array_key_exists($statusFilter, LEAD_STATUSES)) {
    $statusFilter = '';
}
$page    = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;

$conditions = [];
$params     = [];
if ($search !== '') {
    $conditions[] = '(name LIKE :q OR email LIKE :q OR phone LIKE :q OR message LIKE :q)';
    $params['q']  = '%' . $search . '%';
}
if ($statusFilter !== '') {
    $conditions[]      = 'status = :status';
    $params['status']  = $statusFilter;
}
$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM contact_submissions $where");
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$listStmt = $pdo->prepare("SELECT * FROM contact_submissions $where ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
foreach ($params as $k => $v) {
    $listStmt->bindValue(':' . $k, $v);
}
$listStmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$listStmt->execute();
$rows = $listStmt->fetchAll();

$unreadCount = (int) $pdo->query('SELECT COUNT(*) FROM contact_submissions WHERE is_read = 0')->fetchColumn();

function admin_page_url(int $p, string $search, string $statusFilter): string
{
    $qs = ['page' => $p];
    if ($search !== '') {
        $qs['q'] = $search;
    }
    if ($statusFilter !== '') {
        $qs['status'] = $statusFilter;
    }
    return 'index.php?' . http_build_query($qs);
}
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

<div id="topProgressBar"></div>

<div class="admin-topbar">
    <div class="brand">Arpan Township — Admin</div>
    <button type="button" class="admin-hamburger" id="adminHamburger" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
    <div class="admin-topbar-menu" id="adminTopbarMenu">
        <span class="admin-topbar-user">Signed in as <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
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
                <select name="status" class="admin-status-filter" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    <?php foreach (LEAD_STATUSES as $key => $label): ?>
                        <option value="<?php echo htmlspecialchars($key); ?>" <?php echo $statusFilter === $key ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                    <?php endforeach; ?>
                </select>
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
                    <th>Source</th>
                    <th>Message</th>
                    <th>Read</th>
                    <th>Lead Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$rows): ?>
                    <tr><td colspan="9" style="text-align:center; color:#999; padding:24px;">No queries found.</td></tr>
                <?php endif; ?>
                <?php foreach ($rows as $row): ?>
                    <tr class="<?php echo $row['is_read'] ? '' : 'unread'; ?>">
                        <td data-label="Date"><?php echo htmlspecialchars(date('M- d, Y', strtotime($row['created_at']))); ?></td>
                        <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td data-label="Email"><?php echo $row['email'] !== null && $row['email'] !== '' ? htmlspecialchars($row['email']) : '—'; ?></td>
                        <td data-label="Phone">
                            <?php $waNum = admin_wa_number($row['phone']); ?>
                            <a href="tel:+<?php echo htmlspecialchars($waNum); ?>" class="phone-link"><?php echo htmlspecialchars($row['phone']); ?></a>
                            <a href="https://wa.me/<?php echo htmlspecialchars($waNum); ?>" target="_blank" rel="noopener" class="wa-link" title="Message on WhatsApp">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.262.489 1.693.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.004 2.003c-5.514 0-9.997 4.483-9.997 9.997 0 1.762.462 3.484 1.34 5.003L2 22l5.116-1.342a9.96 9.96 0 0 0 4.888 1.28h.004c5.514 0 9.997-4.483 9.997-9.997 0-2.671-1.04-5.182-2.929-7.07a9.93 9.93 0 0 0-7.072-2.868zm0 18.191h-.003a8.19 8.19 0 0 1-4.175-1.144l-.3-.178-3.036.797.81-2.959-.195-.304a8.2 8.2 0 0 1-1.256-4.386c0-4.528 3.685-8.213 8.159-8.213a8.12 8.12 0 0 1 5.775 2.393 8.12 8.12 0 0 1 2.386 5.778c0 4.528-3.685 8.216-8.165 8.216z"/></svg>
                            </a>
                        </td>
                        <td data-label="Source">
                            <?php if ($row['source'] === 'whatsapp'): ?>
                                <span class="badge source-whatsapp">WhatsApp</span>
                            <?php else: ?>
                                <span class="badge source-contact_form">Contact Form</span>
                            <?php endif; ?>
                        </td>
                        <td data-label="Message" class="msg-cell"><?php echo $row['message'] !== null && $row['message'] !== '' ? nl2br(htmlspecialchars($row['message'])) : '—'; ?></td>
                        <td data-label="Read">
                            <?php if ($row['is_read']): ?>
                                <span class="badge read">Read</span>
                            <?php else: ?>
                                <span class="badge unread">New</span>
                            <?php endif; ?>
                        </td>
                        <td data-label="Lead Status">
                            <?php $rowStatus = $row['status'] ?? 'new'; if (!array_key_exists($rowStatus, LEAD_STATUSES)) { $rowStatus = 'new'; } ?>
                            <form method="post" action="index.php?<?php echo http_build_query(array_filter(['q' => $search, 'status' => $statusFilter, 'page' => $page])); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                <input type="hidden" name="action" value="set_status">
                                <select name="status" class="status-select status-<?php echo htmlspecialchars($rowStatus); ?>" onchange="this.className='status-select status-' + this.value; this.form.submit()">
                                    <?php foreach (LEAD_STATUSES as $key => $label): ?>
                                        <option value="<?php echo htmlspecialchars($key); ?>" <?php echo $rowStatus === $key ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td data-label="Actions" class="admin-actions">
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
                            <form method="post" action="index.php<?php echo $search !== '' ? '?q=' . urlencode($search) : ''; ?>" onsubmit="return handleDeleteSubmit(this);">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="danger">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6h16ZM10 11v6M14 11v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="admin-pagination">
            <a class="<?php echo $page <= 1 ? 'disabled' : ''; ?>" href="<?php echo admin_page_url(max(1, $page - 1), $search, $statusFilter); ?>">&laquo; Prev</a>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <?php if ($p === $page): ?>
                    <span class="current"><?php echo $p; ?></span>
                <?php else: ?>
                    <a href="<?php echo admin_page_url($p, $search, $statusFilter); ?>"><?php echo $p; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <a class="<?php echo $page >= $totalPages ? 'disabled' : ''; ?>" href="<?php echo admin_page_url(min($totalPages, $page + 1), $search, $statusFilter); ?>">Next &raquo;</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    (function () {
        var btn = document.getElementById('adminHamburger');
        var menu = document.getElementById('adminTopbarMenu');
        if (!btn || !menu) { return; }
        btn.addEventListener('click', function () {
            menu.classList.toggle('admin-menu-open');
        });
    })();

    function handleDeleteSubmit(form) {
        if (!confirm('Delete this query permanently?')) {
            return false;
        }
        var bar = document.getElementById('topProgressBar');
        if (bar) {
            bar.classList.add('progress-active');
        }
        var btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Deleting...';
        }
        return true;
    }
</script>

</body>
</html>
