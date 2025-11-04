<?php
/**
 * Laravel Database Connection Test File
 * This file tests if the database connection is working correctly
 * 
 * Access via:
 * - php -S localhost:8000 (then visit http://localhost:8000/test_database.php)
 * - XAMPP: http://localhost/ashcol_portal/test_database.php
 */

// Include Composer autoload
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Bootstrap the appropriate kernel based on SAPI
$isCli = php_sapi_name() === 'cli';
if ($isCli) {
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
} else {
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
}
$kernel->bootstrap();


if (!$isCli) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><title>Database Connection Test</title>';
    echo '<style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;background:#f5f5f5;}';
    echo '.container{background:white;padding:30px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);}';
    echo 'h1{color:#333;border-bottom:3px solid #6366f1;padding-bottom:10px;}';
    echo '.test{margin:15px 0;padding:10px;background:#f9fafb;border-left:4px solid #ccc;border-radius:4px;}';
    echo '.pass{color:#10b981;border-left-color:#10b981;}';
    echo '.fail{color:#ef4444;border-left-color:#ef4444;background:#fee;}';
    echo '.warning{color:#f59e0b;border-left-color:#f59e0b;}';
    echo '.info{color:#3b82f6;border-left-color:#3b82f6;background:#eff6ff;}';
    echo '.summary{background:#e0e7ff;padding:15px;border-radius:4px;margin-top:20px;}';
    echo 'pre{background:#f3f4f6;padding:10px;border-radius:4px;overflow-x:auto;}';
    echo '</style></head><body><div class="container">';
    echo '<h1>Database Connection Test</h1>';
}

$tests = [];
$allPassed = true;

// Test 1: Check if .env file exists
$testName = "Test 1: Checking .env file";
if (!$isCli) echo '<div class="test">';
if (!$isCli) echo '<strong>' . $testName . '</strong><br>';
if ($isCli) echo "=== Database Connection Test ===\n\n";
if ($isCli) echo "$testName...\n";

$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $tests[] = ['name' => $testName, 'status' => 'pass', 'message' => '.env file found'];
    if ($isCli) echo "✓ .env file found\n";
    if (!$isCli) echo '✓ .env file found<br>';
} else {
    $tests[] = ['name' => $testName, 'status' => 'warning', 'message' => '.env file not found - using default configuration'];
    if ($isCli) echo "⚠ WARNING: .env file not found - using default configuration\n";
    if (!$isCli) echo '<span class="warning">⚠ WARNING: .env file not found - using default configuration</span><br>';
}

if (!$isCli) echo '</div>';

// Test 2: Check database configuration
$testName = "Test 2: Reading database configuration";
if (!$isCli) echo '<div class="test">';
if (!$isCli) echo '<strong>' . $testName . '</strong><br>';
if ($isCli) echo "\n$testName...\n";

try {
    $connection = config('database.default');
    $dbConfig = config("database.connections.{$connection}");
    
    if ($dbConfig) {
        $tests[] = ['name' => $testName, 'status' => 'pass', 'message' => "Database configuration loaded (Connection: {$connection})"];
        if ($isCli) echo "✓ Database configuration loaded\n";
        if ($isCli) echo "  Connection: {$connection}\n";
        if (!$isCli) echo "✓ Database configuration loaded<br>";
        if (!$isCli) echo "Connection: <strong>{$connection}</strong><br>";
        
        // Display connection details (without password)
        if (!$isCli) {
            echo '<div class="info" style="margin-top:10px;">';
            echo '<strong>Connection Details:</strong><br>';
            if (isset($dbConfig['host'])) echo 'Host: ' . htmlspecialchars($dbConfig['host']) . '<br>';
            if (isset($dbConfig['port'])) echo 'Port: ' . htmlspecialchars($dbConfig['port']) . '<br>';
            if (isset($dbConfig['database'])) echo 'Database: ' . htmlspecialchars($dbConfig['database']) . '<br>';
            if (isset($dbConfig['username'])) echo 'Username: ' . htmlspecialchars($dbConfig['username']) . '<br>';
            echo 'Password: ' . (isset($dbConfig['password']) && $dbConfig['password'] ? '***' : '(empty)') . '<br>';
            echo '</div>';
        } else {
            if (isset($dbConfig['host'])) echo "  Host: {$dbConfig['host']}\n";
            if (isset($dbConfig['port'])) echo "  Port: {$dbConfig['port']}\n";
            if (isset($dbConfig['database'])) echo "  Database: {$dbConfig['database']}\n";
            if (isset($dbConfig['username'])) echo "  Username: {$dbConfig['username']}\n";
        }
    } else {
        $tests[] = ['name' => $testName, 'status' => 'fail', 'message' => 'Database configuration not found'];
        if ($isCli) echo "✗ ERROR: Database configuration not found\n\n";
        if (!$isCli) echo '<div class="test fail"><strong>✗ ' . $testName . '</strong><br>ERROR: Database configuration not found</div>';
        $allPassed = false;
    }
} catch (Exception $e) {
    $tests[] = ['name' => $testName, 'status' => 'fail', 'message' => $e->getMessage()];
    if ($isCli) echo "✗ ERROR: " . $e->getMessage() . "\n\n";
    if (!$isCli) echo '<div class="test fail"><strong>✗ ' . $testName . '</strong><br>ERROR: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $allPassed = false;
}

if (!$isCli) echo '</div>';

// Test 3: Test database connection
$testName = "Test 3: Testing database connection";
if (!$isCli) echo '<div class="test">';
if (!$isCli) echo '<strong>' . $testName . '</strong><br>';
if ($isCli) echo "\n$testName...\n";

try {
    $db = \Illuminate\Support\Facades\DB::connection();
    $db->getPdo();
    
    $tests[] = ['name' => $testName, 'status' => 'pass', 'message' => 'Database connection successful'];
    if ($isCli) echo "✓ Database connection successful\n";
    if (!$isCli) echo '✓ Database connection successful<br>';
    
    // Get database version
    try {
        $version = $db->select('SELECT VERSION() as version')[0]->version ?? 'Unknown';
        if ($isCli) echo "  Database Version: {$version}\n";
        if (!$isCli) echo "Database Version: <strong>" . htmlspecialchars($version) . "</strong><br>";
    } catch (Exception $e) {
        // Version query might not work for all databases, skip it
    }
    
} catch (\Illuminate\Database\QueryException $e) {
    $tests[] = ['name' => $testName, 'status' => 'fail', 'message' => 'Database connection failed: ' . $e->getMessage()];
    if ($isCli) echo "✗ ERROR: Database connection failed\n";
    if ($isCli) echo "  " . $e->getMessage() . "\n\n";
    if (!$isCli) echo '<div class="test fail"><strong>✗ ' . $testName . '</strong><br>';
    if (!$isCli) echo 'ERROR: Database connection failed<br>';
    if (!$isCli) echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre></div>';
    $allPassed = false;
} catch (Exception $e) {
    $tests[] = ['name' => $testName, 'status' => 'fail', 'message' => $e->getMessage()];
    if ($isCli) echo "✗ ERROR: " . $e->getMessage() . "\n\n";
    if (!$isCli) echo '<div class="test fail"><strong>✗ ' . $testName . '</strong><br>ERROR: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $allPassed = false;
}

if (!$isCli) echo '</div>';

// Test 4: Test simple query
$testName = "Test 4: Testing database query";
if (!$isCli) echo '<div class="test">';
if (!$isCli) echo '<strong>' . $testName . '</strong><br>';
if ($isCli) echo "\n$testName...\n";

try {
    $result = \Illuminate\Support\Facades\DB::select('SELECT 1 as test');
    if (!empty($result) && isset($result[0]->test) && $result[0]->test == 1) {
        $tests[] = ['name' => $testName, 'status' => 'pass', 'message' => 'Database query executed successfully'];
        if ($isCli) echo "✓ Database query executed successfully\n";
        if (!$isCli) echo '✓ Database query executed successfully<br>';
    } else {
        $tests[] = ['name' => $testName, 'status' => 'fail', 'message' => 'Query returned unexpected result'];
        if ($isCli) echo "✗ ERROR: Query returned unexpected result\n\n";
        if (!$isCli) echo '<div class="test fail"><strong>✗ ' . $testName . '</strong><br>ERROR: Query returned unexpected result</div>';
        $allPassed = false;
    }
} catch (Exception $e) {
    $tests[] = ['name' => $testName, 'status' => 'fail', 'message' => $e->getMessage()];
    if ($isCli) echo "✗ ERROR: " . $e->getMessage() . "\n\n";
    if (!$isCli) echo '<div class="test fail"><strong>✗ ' . $testName . '</strong><br>ERROR: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $allPassed = false;
}

if (!$isCli) echo '</div>';

// Summary
if (!$isCli) {
    echo '<div class="summary">';
    if ($allPassed) {
        echo '<h2 style="color:#10b981;margin:0;">✓ All Tests Passed!</h2>';
        echo '<p>Database connection is working correctly.</p>';
    } else {
        echo '<h2 style="color:#ef4444;margin:0;">✗ Some Tests Failed</h2>';
        echo '<p>Please check the errors above and fix any issues.</p>';
        echo '<p><strong>Common issues:</strong></p>';
        echo '<ul>';
        echo '<li>Make sure your database server is running (e.g., MySQL/MariaDB in XAMPP)</li>';
        echo '<li>Check your .env file for correct database credentials</li>';
        echo '<li>Verify the database exists</li>';
        echo '<li>Check database user permissions</li>';
        echo '</ul>';
    }
    echo '</div>';
    echo '</div></body></html>';
} else {
    echo "\n";
    if ($allPassed) {
        echo "=== All Tests Passed! ===\n";
        echo "Database connection is working correctly.\n";
    } else {
        echo "=== Some Tests Failed ===\n";
        echo "Please check the errors above and fix any issues.\n";
        echo "\nCommon issues:\n";
        echo "- Make sure your database server is running (e.g., MySQL/MariaDB in XAMPP)\n";
        echo "- Check your .env file for correct database credentials\n";
        echo "- Verify the database exists\n";
        echo "- Check database user permissions\n";
    }
}

