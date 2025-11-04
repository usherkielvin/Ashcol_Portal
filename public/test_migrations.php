<?php
/**
 * Test Migrations Script
 * Run this to verify migrations work correctly
 * 
 * Access via: http://localhost/ashcol_portal/public/test_migrations.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$isCli = php_sapi_name() === 'cli';

if ($isCli) {
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
} else {
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
}
$kernel->bootstrap();

if (!$isCli) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><title>Migration Test</title>';
    echo '<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}';
    echo '.container{background:white;padding:20px;border-radius:8px;max-width:800px;margin:0 auto;}';
    echo '.success{color:#10b981;background:#d1fae5;padding:10px;border-radius:4px;margin:10px 0;}';
    echo '.error{color:#ef4444;background:#fee;padding:10px;border-radius:4px;margin:10px 0;}';
    echo '.warning{color:#f59e0b;background:#fef3c7;padding:10px;border-radius:4px;margin:10px 0;}';
    echo 'pre{background:#f3f4f6;padding:10px;border-radius:4px;overflow-x:auto;}';
    echo 'h2{color:#333;border-bottom:2px solid #6366f1;padding-bottom:10px;}';
    echo '</style></head><body><div class="container"><h1>Migration Test</h1>';
}

try {
    // Check if tables exist
    $tables = ['ticket_statuses', 'tickets', 'ticket_comments'];
    $existingTables = [];
    $missingTables = [];
    
    foreach ($tables as $table) {
        try {
            $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
            if ($exists) {
                $existingTables[] = $table;
                $count = \Illuminate\Support\Facades\DB::table($table)->count();
                if ($isCli) {
                    echo "✓ Table '$table' exists (records: $count)\n";
                } else {
                    echo "<div class='success'>✓ Table '$table' exists (records: $count)</div>";
                }
            } else {
                $missingTables[] = $table;
                if ($isCli) {
                    echo "✗ Table '$table' does not exist\n";
                } else {
                    echo "<div class='error'>✗ Table '$table' does not exist</div>";
                }
            }
        } catch (\Exception $e) {
            if ($isCli) {
                echo "✗ Error checking table '$table': " . $e->getMessage() . "\n";
            } else {
                echo "<div class='error'>✗ Error checking table '$table': " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
    }
    
    if (count($existingTables) === count($tables)) {
        // Check ticket statuses
        $statusCount = \App\Models\TicketStatus::count();
        if ($isCli) {
            echo "\n✓ Ticket Statuses seeded: $statusCount\n";
        } else {
            echo "<div class='success'><h2>✓ Ticket Statuses seeded: $statusCount</h2></div>";
        }
        
        if ($statusCount > 0) {
            $statuses = \App\Models\TicketStatus::all();
            if (!$isCli) {
                echo "<h3>Statuses:</h3><ul>";
                foreach ($statuses as $status) {
                    echo "<li><strong>{$status->name}</strong> (Color: {$status->color}, Default: " . ($status->is_default ? 'Yes' : 'No') . ")</li>";
                }
                echo "</ul>";
            }
        } else {
            if ($isCli) {
                echo "\n⚠ Run: php artisan db:seed --class=TicketStatusSeeder\n";
            } else {
                echo "<div class='warning'><h3>⚠ Run seeder:</h3><pre>php artisan db:seed --class=TicketStatusSeeder</pre></div>";
            }
        }
        
        if ($isCli) {
            echo "\n✓ All migrations completed successfully!\n";
        } else {
            echo "<div class='success'><h2>✓ All migrations completed successfully!</h2></div>";
        }
    } else {
        if ($isCli) {
            echo "\n⚠ Run migrations: php artisan migrate\n";
        } else {
            echo "<div class='warning'><h3>⚠ Missing Tables. Run migrations:</h3>";
            echo "<pre>php artisan migrate</pre>";
            echo "<p>Missing tables: " . implode(', ', $missingTables) . "</p></div>";
        }
    }
    
} catch (\Exception $e) {
    if ($isCli) {
        echo "ERROR: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
    } else {
        echo "<div class='error'><h2>ERROR</h2><p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre></div>";
    }
}

if (!$isCli) {
    echo '</div></body></html>';
}

