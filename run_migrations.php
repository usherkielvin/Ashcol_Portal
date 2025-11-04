<?php
/**
 * Run Migrations Script
 * This script runs all pending migrations
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Running migrations...\n";

try {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "✓ Migrations completed successfully!\n\n";
    
    echo "Seeding ticket statuses...\n";
    \Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--class' => 'TicketStatusSeeder',
        '--force' => true
    ]);
    echo "✓ Ticket statuses seeded successfully!\n\n";
    
    echo "All done! ✓\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

