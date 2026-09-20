<?php
declare(strict_types=1);

header('Content-Type: application/json');

function respond(bool $ok, string $message): void
{
    echo json_encode(['success' => $ok, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    respond(false, 'Invalid request method.');
}

$name      = trim($_POST['name'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$honeypot  = trim($_POST['website'] ?? '');

if ($honeypot !== '') {
    // Spam bot — pretend success, store nothing.
    respond(true, 'Thanks!');
}

if ($name === '' || strlen($name) > 150) {
    respond(false, 'Please enter your name.');
}

if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
    respond(false, 'Please enter a valid phone number.');
}

try {
    require __DIR__ . '/includes/db.php';
    $insert = $pdo->prepare(
        'INSERT INTO contact_submissions (name, phone, source, ip_address, created_at) VALUES (:name, :phone, :source, :ip, NOW())'
    );
    $insert->execute([
        'name'   => $name,
        'phone'  => $phone,
        'source' => 'whatsapp',
        'ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
} catch (Throwable $e) {
    error_log('Failed to store WhatsApp lead: ' . $e->getMessage());
    respond(false, 'Something went wrong. Please try again.');
}

respond(true, 'Thanks! Redirecting you to WhatsApp...');
