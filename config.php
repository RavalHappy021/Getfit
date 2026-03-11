<?php
require_once __DIR__ . '/vendor/autoload.php';

// Manual .env loader
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Database Configuration
$mongoUri = getenv('MONGO_URI') ?: 'mongodb://localhost:27017';
$mongoDbName = getenv('MONGO_DB') ?: 'getfit_db';

try {
    $client = new MongoDB\Client($mongoUri);
    $db = $client->selectDatabase($mongoDbName);
    // Keeping $conn for compatibility during migration, but it will store the DB object
    $conn = $db; 
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
