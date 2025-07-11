<?php

/**
 * Education Platform Database Setup Script
 * 
 * This script sets up the complete database structure for the education platform.
 * It can be run on any fresh Laravel installation to create the entire database.
 * 
 * Usage:
 * php setup-database.php [options]
 * 
 * Options:
 * --fresh     : Drop all tables and recreate (CAUTION: This will delete all data)
 * --seed      : Run seeders after migration
 * --force     : Skip confirmation prompts
 * 
 * Examples:
 * php setup-database.php --fresh --seed --force
 * php setup-database.php --seed
 */

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.' . PHP_EOL);
}

// Parse command line arguments
$options = getopt('', ['fresh', 'seed', 'force', 'help']);

if (isset($options['help'])) {
    showHelp();
    exit(0);
}

$fresh = isset($options['fresh']);
$seed = isset($options['seed']);
$force = isset($options['force']);

echo "===========================================\n";
echo "Education Platform Database Setup\n";
echo "===========================================\n\n";

// Check if Laravel artisan exists
if (!file_exists('artisan')) {
    echo "Error: Laravel artisan file not found. Please run this script from the Laravel root directory.\n";
    exit(1);
}

// Show what will be done
echo "Configuration:\n";
echo "- Fresh installation: " . ($fresh ? "YES (⚠️  ALL DATA WILL BE LOST)" : "NO") . "\n";
echo "- Run seeders: " . ($seed ? "YES" : "NO") . "\n";
echo "- Skip confirmations: " . ($force ? "YES" : "NO") . "\n\n";

// Confirmation if not forcing
if (!$force) {
    if ($fresh) {
        echo "⚠️  WARNING: Fresh installation will delete ALL existing data!\n";
        echo "Are you sure you want to continue? (yes/no): ";
        $confirm = trim(fgets(STDIN));
        if (strtolower($confirm) !== 'yes') {
            echo "Operation cancelled.\n";
            exit(0);
        }
    } else {
        echo "Continue with database setup? (yes/no): ";
        $confirm = trim(fgets(STDIN));
        if (strtolower($confirm) !== 'yes') {
            echo "Operation cancelled.\n";
            exit(0);
        }
    }
}

echo "\n🚀 Starting database setup...\n\n";

try {
    // Step 1: Clear caches
    echo "📋 Step 1: Clearing caches...\n";
    runCommand('php artisan config:clear');
    runCommand('php artisan cache:clear');
    runCommand('php artisan route:clear');
    runCommand('php artisan view:clear');
    echo "✅ Caches cleared successfully.\n\n";

    // Step 2: Run migrations
    if ($fresh) {
        echo "📋 Step 2: Running fresh migrations (dropping all tables)...\n";
        runCommand('php artisan migrate:fresh --force');
    } else {
        echo "📋 Step 2: Running migrations...\n";
        runCommand('php artisan migrate --force');
    }
    echo "✅ Migrations completed successfully.\n\n";

    // Step 3: Run seeders if requested
    if ($seed) {
        echo "📋 Step 3: Running database seeders...\n";
        runCommand('php artisan db:seed --class=CompleteEducationPlatformSeeder --force');
        echo "✅ Database seeded successfully.\n\n";
    }

    // Step 4: Generate application key if needed
    echo "📋 Step 4: Checking application key...\n";
    if (empty(env('APP_KEY'))) {
        runCommand('php artisan key:generate --force');
        echo "✅ Application key generated.\n\n";
    } else {
        echo "✅ Application key already exists.\n\n";
    }

    // Step 5: Create storage links
    echo "📋 Step 5: Creating storage links...\n";
    runCommand('php artisan storage:link');
    echo "✅ Storage links created.\n\n";

    // Step 6: Optimize application
    echo "📋 Step 6: Optimizing application...\n";
    runCommand('php artisan config:cache');
    runCommand('php artisan route:cache');
    runCommand('php artisan view:cache');
    echo "✅ Application optimized.\n\n";

    // Success message
    echo "🎉 Database setup completed successfully!\n\n";
    
    if ($seed) {
        echo "📝 Sample Data Created:\n";
        echo "-----------------------------------\n";
        echo "👤 Admin User:\n";
        echo "   Email: admin@educonnect.com\n";
        echo "   Password: admin123\n\n";
        echo "👨‍🏫 Sample Teachers:\n";
        echo "   Email: rajesh.kumar@example.com (Password: teacher123)\n";
        echo "   Email: priya.sharma@example.com (Password: teacher123)\n";
        echo "   And 3 more teachers...\n\n";
        echo "🏫 Sample Institutes:\n";
        echo "   Email: info@excellenceacademy.com (Password: institute123)\n";
        echo "   Email: contact@brightfuture.com (Password: institute123)\n";
        echo "   And 3 more institutes...\n\n";
        echo "👨‍🎓 Sample Students:\n";
        echo "   Email: rahul.verma@example.com (Password: student123)\n";
        echo "   And 2 more students...\n\n";
    }

    echo "🌐 You can now start your application:\n";
    echo "   php artisan serve\n\n";

} catch (Exception $e) {
    echo "❌ Error during setup: " . $e->getMessage() . "\n";
    exit(1);
}

function runCommand($command) {
    echo "   Running: $command\n";
    $output = [];
    $returnCode = 0;
    exec($command . ' 2>&1', $output, $returnCode);
    
    if ($returnCode !== 0) {
        echo "   Error output:\n";
        foreach ($output as $line) {
            echo "   $line\n";
        }
        throw new Exception("Command failed: $command");
    }
    
    // Show relevant output
    foreach ($output as $line) {
        if (strpos($line, 'Migration') !== false || 
            strpos($line, 'Seeded') !== false ||
            strpos($line, 'Generated') !== false ||
            strpos($line, 'Created') !== false) {
            echo "   $line\n";
        }
    }
}

function showHelp() {
    echo "Education Platform Database Setup Script\n\n";
    echo "Usage: php setup-database.php [options]\n\n";
    echo "Options:\n";
    echo "  --fresh     Drop all tables and recreate (⚠️  DELETES ALL DATA)\n";
    echo "  --seed      Run database seeders after migration\n";
    echo "  --force     Skip confirmation prompts\n";
    echo "  --help      Show this help message\n\n";
    echo "Examples:\n";
    echo "  php setup-database.php --fresh --seed --force\n";
    echo "  php setup-database.php --seed\n";
    echo "  php setup-database.php --fresh\n\n";
    echo "Notes:\n";
    echo "- Run this script from your Laravel project root directory\n";
    echo "- Make sure your .env file is configured with database credentials\n";
    echo "- Use --fresh with caution as it will delete all existing data\n";
} 