<?php
// Test database connection

// Load .env manually
$envFile = __DIR__ . '/.env';
echo "<h3>1. Loading .env file</h3>";
if (file_exists($envFile)) {
    echo "✅ .env file found<br>";
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv(trim($line));
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
} else {
    echo "❌ .env file NOT found<br>";
}

echo "<h3>2. Environment Variables</h3>";
echo "MARIADB_PUBLIC_HOST: " . (getenv('MARIADB_PUBLIC_HOST') ?: 'NOT SET') . "<br>";
echo "MARIADB_PUBLIC_PORT: " . (getenv('MARIADB_PUBLIC_PORT') ?: 'NOT SET') . "<br>";
echo "MARIADB_USER: " . (getenv('MARIADB_USER') ?: 'NOT SET') . "<br>";
echo "MARIADB_PASSWORD: " . (getenv('MARIADB_PASSWORD') ? '***SET***' : 'NOT SET') . "<br>";
echo "MARIADB_DATABASE: " . (getenv('MARIADB_DATABASE') ?: 'NOT SET') . "<br>";

echo "<h3>3. Testing Connection</h3>";
$dbhost = getenv('MARIADB_PUBLIC_HOST') ?: 'shuttle.proxy.rlwy.net';
$dbport = getenv('MARIADB_PUBLIC_PORT') ?: '24272';
$dbuser = getenv('MARIADB_USER') ?: 'railway';
$dbpass = getenv('MARIADB_PASSWORD') ?: '';
$dbname = getenv('MARIADB_DATABASE') ?: 'railway';

echo "Connecting to: $dbhost:$dbport as $dbuser<br>";

try {
    $db = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass, [
        PDO::ATTR_TIMEOUT => 10,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $db->exec("set names utf8mb4");
    echo "✅ <strong style='color:green'>Connection successful!</strong><br>";
    
    // Test query
    $stmt = $db->query("SELECT COUNT(*) as cnt FROM tb_customer");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ tb_customer has " . $result['cnt'] . " records<br>";
    
} catch (PDOException $e) {
    echo "❌ <strong style='color:red'>Connection failed!</strong><br>";
    echo "Error: " . $e->getMessage() . "<br>";
}
?>
