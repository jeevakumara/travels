<?php
/**
 * Production Database Configuration
 * 
 * IMPORTANT: 
 * 1. Rename this file to config.php when deploying
 * 2. Update credentials with your production values
 * 3. Never commit config.php to version control
 */

// Production Database Credentials
$host = 'localhost';  // or your production DB host
$db   = 'travels';
$user = 'travels_user';  // CHANGE THIS - create dedicated DB user
$pass = 'STRONG_PASSWORD_HERE';  // CHANGE THIS - use strong password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
  PDO::ATTR_PERSISTENT => false,  // Set to true for connection pooling
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  // In production, log error instead of displaying
  error_log('Database connection failed: ' . $e->getMessage());
  
  // Show generic error to users
  http_response_code(503);
  exit('Service temporarily unavailable. Please try again later.');
}

// Production PHP Settings
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-errors.log');

// Ensure logs directory exists
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
  @mkdir($logDir, 0755, true);
}
