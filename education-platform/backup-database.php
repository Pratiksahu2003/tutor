<?php

/**
 * Database Backup Script for Education Platform
 * 
 * This script creates a backup of the database that can be easily restored
 * on any other system using the setup scripts.
 * 
 * Usage: php backup-database.php [options]
 * 
 * Options:
 * --format=sql|json  : Output format (default: sql)
 * --output=filename  : Output filename (default: auto-generated)
 * --compress         : Compress the backup file
 */

if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.' . PHP_EOL);
}

// Parse command line arguments
$options = getopt('', ['format:', 'output:', 'compress', 'help']);

if (isset($options['help'])) {
    showHelp();
    exit(0);
}

$format = $options['format'] ?? 'sql';
$compress = isset($options['compress']);
$output = $options['output'] ?? null;

// Check if Laravel artisan exists
if (!file_exists('artisan')) {
    echo "Error: Laravel artisan file not found. Please run this script from the Laravel root directory.\n";
    exit(1);
}

echo "===========================================\n";
echo "Education Platform Database Backup\n";
echo "===========================================\n\n";

try {
    // Generate default filename if not provided
    if (!$output) {
        $timestamp = date('Y-m-d_H-i-s');
        $output = "backup_education_platform_{$timestamp}";
        if ($format === 'sql') {
            $output .= '.sql';
        } else {
            $output .= '.json';
        }
        if ($compress) {
            $output .= '.gz';
        }
    }

    echo "📋 Backup Configuration:\n";
    echo "- Format: $format\n";
    echo "- Output: $output\n";
    echo "- Compressed: " . ($compress ? 'YES' : 'NO') . "\n\n";

    echo "🚀 Starting backup...\n\n";

    if ($format === 'sql') {
        createSqlBackup($output, $compress);
    } else {
        createJsonBackup($output, $compress);
    }

    $fileSize = formatBytes(filesize($output));
    echo "✅ Backup completed successfully!\n";
    echo "📁 File: $output (Size: $fileSize)\n\n";

    echo "📋 To restore this backup:\n";
    if ($format === 'sql') {
        echo "1. Copy the backup file to your new Laravel installation\n";
        echo "2. Set up your .env database configuration\n";
        if ($compress) {
            echo "3. gunzip $output\n";
            echo "4. Import: mysql database_name < " . str_replace('.gz', '', $output) . "\n";
        } else {
            echo "3. Import: mysql database_name < $output\n";
        }
    } else {
        echo "1. Copy the backup file to your new Laravel installation\n";
        echo "2. Run: php artisan migrate:fresh --force\n";
        echo "3. Import data using your preferred JSON import method\n";
    }

} catch (Exception $e) {
    echo "❌ Error during backup: " . $e->getMessage() . "\n";
    exit(1);
}

function createSqlBackup($output, $compress) {
    echo "📊 Creating SQL backup...\n";
    
    // Get database configuration
    $dbConfig = getDatabaseConfig();
    
    if ($dbConfig['connection'] === 'sqlite') {
        // For SQLite, just copy the file
        $dbFile = $dbConfig['database'];
        if ($compress) {
            $command = "gzip -c \"$dbFile\" > \"$output\"";
        } else {
            copy($dbFile, $output);
            echo "   SQLite database copied successfully\n";
            return;
        }
    } else {
        // For MySQL/PostgreSQL, use mysqldump/pg_dump
        $host = $dbConfig['host'];
        $port = $dbConfig['port'];
        $database = $dbConfig['database'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];
        
        if ($dbConfig['connection'] === 'mysql') {
            $command = "mysqldump -h\"$host\" -P\"$port\" -u\"$username\" -p\"$password\" \"$database\"";
        } else {
            $command = "pg_dump -h \"$host\" -p \"$port\" -U \"$username\" \"$database\"";
        }
        
        if ($compress) {
            $command .= " | gzip > \"$output\"";
        } else {
            $command .= " > \"$output\"";
        }
    }
    
    echo "   Running: Database export\n";
    exec($command, $outputLines, $returnCode);
    
    if ($returnCode !== 0) {
        throw new Exception("Database export failed");
    }
    
    echo "   SQL backup created successfully\n";
}

function createJsonBackup($output, $compress) {
    echo "📊 Creating JSON backup...\n";
    
    // Get all table data using Laravel
    $tables = [
        'users', 'roles', 'permissions', 'user_roles', 'role_permissions',
        'subjects', 'exam_categories', 'exams', 'exam_subjects',
        'teacher_profiles', 'student_profiles', 'institutes',
        'teacher_subjects', 'institute_subjects', 'teacher_exams', 'institute_exams',
        'pages', 'blog_posts', 'questions', 'leads', 'inquiries', 'site_settings'
    ];
    
    $data = [];
    
    foreach ($tables as $table) {
        echo "   Exporting table: $table\n";
        $command = "php artisan tinker --execute=\"echo json_encode(DB::table('$table')->get()->toArray());\"";
        $tableData = shell_exec($command);
        $data[$table] = json_decode(trim($tableData), true) ?: [];
    }
    
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    
    if ($compress) {
        file_put_contents($output, gzencode($jsonData));
    } else {
        file_put_contents($output, $jsonData);
    }
    
    echo "   JSON backup created successfully\n";
}

function getDatabaseConfig() {
    // Load .env file
    $envFile = '.env';
    if (!file_exists($envFile)) {
        throw new Exception('.env file not found');
    }
    
    $envContent = file_get_contents($envFile);
    $lines = explode("\n", $envContent);
    $config = [];
    
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && !str_starts_with(trim($line), '#')) {
            list($key, $value) = explode('=', $line, 2);
            $config[trim($key)] = trim($value, '"\'');
        }
    }
    
    return [
        'connection' => $config['DB_CONNECTION'] ?? 'sqlite',
        'host' => $config['DB_HOST'] ?? '127.0.0.1',
        'port' => $config['DB_PORT'] ?? '3306',
        'database' => $config['DB_DATABASE'] ?? '',
        'username' => $config['DB_USERNAME'] ?? '',
        'password' => $config['DB_PASSWORD'] ?? '',
    ];
}

function formatBytes($size, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    
    return round($size, $precision) . ' ' . $units[$i];
}

function showHelp() {
    echo "Education Platform Database Backup Script\n\n";
    echo "Usage: php backup-database.php [options]\n\n";
    echo "Options:\n";
    echo "  --format=sql|json   Output format (default: sql)\n";
    echo "  --output=filename   Output filename (default: auto-generated)\n";
    echo "  --compress          Compress the backup file\n";
    echo "  --help              Show this help message\n\n";
    echo "Examples:\n";
    echo "  php backup-database.php --format=sql --compress\n";
    echo "  php backup-database.php --format=json --output=my_backup.json\n";
    echo "  php backup-database.php --compress\n\n";
} 