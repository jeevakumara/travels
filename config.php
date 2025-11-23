<?php
/**
 * Main Configuration File
 * 
 * Handles database connection and environment-specific settings.
 * Automatically detects if running on localhost or production (InfinityFree).
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

// Environment Detection
$whitelist = ['127.0.0.1', '::1', 'localhost'];
$isLocal = in_array($_SERVER['REMOTE_ADDR'], $whitelist);

// Database Configuration
// Load Database Credentials
$credentialsFile = __DIR__ . '/db_credentials.php';
if (!file_exists($credentialsFile)) {
    die("Configuration error: db_credentials.php not found.");
}
$allCredentials = require $credentialsFile;

if ($isLocal) {
    // Localhost Configuration (XAMPP)
    $creds = $allCredentials['local'];
    
    // Error Reporting for Local
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Base URL
    $basePath = '/travel-site'; // Adjust if your local folder name is different
} else {
    // InfinityFree / Production Configuration
    $creds = $allCredentials['production'];
    
    // Error Reporting for Production
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error_log'); // Log to file
    
    // Base URL (Empty for root domain)
    $basePath = ''; 
}

// Define Constants
define('DB_HOST', $creds['DB_HOST']);
define('DB_NAME', $creds['DB_NAME']);
define('DB_USER', $creds['DB_USER']);
define('DB_PASS', $creds['DB_PASS']);
define('DB_CHARSET', $creds['DB_CHARSET']);

// Database Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5, // 5 seconds timeout
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
} catch (PDOException $e) {
    // Log the error
    error_log("Database Connection Error: " . $e->getMessage());
    
    // Show user-friendly message
    if ($isLocal) {
        die("Connection failed: " . $e->getMessage());
    } else {
        http_response_code(503);
        die("<h1>Service Temporarily Unavailable</h1><p>We are experiencing technical difficulties. Please try again later.</p>");
    }
}

// Start Session Securely
if (session_status() === PHP_SESSION_NONE) {
    // Set secure session parameters before starting
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    
    if (!$isLocal && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    
    session_start();
}

// Helper Functions
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
