<?php
// Test database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Environment Variables Check:</h2>";
echo "<pre>";
echo "MARIADB_HOST: " . (getenv('MARIADB_HOST') ?: 'NOT SET') . "\n";
echo "MARIADB_PORT: " . (getenv('MARIADB_PORT') ?: 'NOT SET') . "\n";
echo "MARIADB_USER: " . (getenv('MARIADB_USER') ?: 'NOT SET') . "\n";
echo "MARIADB_PASSWORD: " . (getenv('MARIADB_PASSWORD') ? '***SET***' : 'NOT SET') . "\n";
echo "MARIADB_DATABASE: " . (getenv('MARIADB_DATABASE') ?: 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>Database Connection Test:</h2>";
try {
    require_once('functions/config.php');
    $db = connect();
    echo "<p style='color:green;'>✅ Database connection successful!</p>";
    
    // Test query
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Tables found: " . count($tables) . "</p>";
    echo "<ul>";
    foreach($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
