<?php
/**
 * Seed Users Script
 * Run this to create test users in the database
 * 
 * Access via: http://localhost/ashcol_portal/public/seed_users.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html><head><title>Seed Users</title>';
echo '<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}';
echo '.container{background:white;padding:20px;border-radius:8px;max-width:800px;margin:0 auto;}';
echo '.success{color:#10b981;background:#d1fae5;padding:10px;border-radius:4px;margin:10px 0;}';
echo '.error{color:#ef4444;background:#fee;padding:10px;border-radius:4px;margin:10px 0;}';
echo '.info{color:#3b82f6;background:#dbeafe;padding:10px;border-radius:4px;margin:10px 0;}';
echo 'pre{background:#f3f4f6;padding:10px;border-radius:4px;overflow-x:auto;}';
echo '</style></head><body><div class="container"><h1>Seed Users</h1>';

try {
    // Check if users exist
    $userCount = \App\Models\User::count();
    echo "<div class='info'>Current users in database: <strong>{$userCount}</strong></div>";
    
    if ($userCount > 0) {
        echo "<div class='info'>Users already exist. Showing existing users:</div>";
        $users = \App\Models\User::all();
        echo "<table border='1' cellpadding='10' style='width:100%;border-collapse:collapse;margin-top:10px;'>";
        echo "<tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user->name}</td>";
            echo "<td>{$user->email}</td>";
            echo "<td>{$user->role}</td>";
            echo "<td>{$user->created_at}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='error'>No users found. Running seeder...</div>";
        
        // Run seeder
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        
        echo "<div class='success'>Seeder completed! Users created:</div>";
        
        $users = \App\Models\User::all();
        echo "<table border='1' cellpadding='10' style='width:100%;border-collapse:collapse;margin-top:10px;'>";
        echo "<tr><th>Name</th><th>Email</th><th>Role</th><th>Password</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user->name}</td>";
            echo "<td>{$user->email}</td>";
            echo "<td>{$user->role}</td>";
            echo "<td><strong>password</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Show login credentials
    echo "<div class='success' style='margin-top:20px;'>";
    echo "<h3>Login Credentials:</h3>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@example.com / password</li>";
    echo "<li><strong>Staff:</strong> staff@example.com / password</li>";
    echo "<li><strong>Customer:</strong> customer@example.com / password</li>";
    echo "</ul>";
    echo "</div>";
    
} catch (\Exception $e) {
    echo "<div class='error'>ERROR: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo '</div></body></html>';

