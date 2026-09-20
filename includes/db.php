<?php
declare(strict_types=1);

$dbConfigFile = __DIR__ . '/../db-config.php';
if (!file_exists($dbConfigFile)) {
    throw new RuntimeException('Database is not configured. Copy db-config.sample.php to db-config.php and fill in your credentials.');
}

$dbConfig = require $dbConfigFile;

try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset=utf8mb4",
        $dbConfig['username'],
        $dbConfig['password'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log('DB connection failed: ' . $e->getMessage());
    throw new RuntimeException('Could not connect to the database.');
}
