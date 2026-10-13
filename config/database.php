<?php

/* ══════════════════════════════════════════
   DATABASE CONNECTION (PDO / MySQL)
   Edit the defaults below or set the matching
   environment variables (DB_HOST, DB_PORT, ...).
══════════════════════════════════════════ */

function db_config(): array
{
    return [
        'host'    => getenv('DB_HOST') ?: '127.0.0.1',
        'port'    => getenv('DB_PORT') ?: '3306',
        'name'    => getenv('DB_NAME') ?: 'voltcore',
        'user'    => getenv('DB_USER') ?: 'root',
        'pass'    => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ];
}

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $cfg = db_config();
        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['name']};charset={$cfg['charset']}";

        try {
            $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            exit(
                "Database connection failed. Check config/database.php.\n" .
                "Have you imported db/schema.sql and db/seed.sql?\n\n" .
                $e->getMessage()
            );
        }
    }

    return $pdo;
}
