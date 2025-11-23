<?php
/**
 * Helper functions for NMD Travels
 * Production-ready utilities
 */

// Sanitize output for HTML display
function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Validate email format
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate phone number (basic)
function is_valid_phone($phone) {
    return preg_match('/^[\d\s\+\-\(\)]{7,20}$/', $phone);
}

// Validate date format (YYYY-MM-DD)
function is_valid_date($date) {
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && strtotime($date) !== false;
}

// Sanitize string input
function sanitize_string($input, $maxLength = 255) {
    $input = trim($input);
    $input = strip_tags($input);
    return mb_substr($input, 0, $maxLength);
}

// Redirect with message
function redirect_with_message($url, $message, $type = 'success') {
    session_start();
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
    header("Location: $url");
    exit;
}

// Get and clear flash message
function get_flash_message() {
  //  session_start();
    if (isset($_SESSION['flash_message'])) {
        $message = [
            'text' => $_SESSION['flash_message'],
            'type' => $_SESSION['flash_type'] ?? 'info'
        ];
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return $message;
    }
    return null;
}

// Log errors to file (production)
function log_error($message, $context = []) {
    $logFile = __DIR__ . '/../logs/error.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $contextStr = !empty($context) ? json_encode($context) : '';
    $logMessage = "[$timestamp] $message $contextStr" . PHP_EOL;
    
    @file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
}

// Check if request is AJAX
function is_ajax_request() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// JSON response helper
function json_response($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Validate required POST fields
function validate_required_fields($fields) {
    $missing = [];
    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            $missing[] = $field;
        }
    }
    return empty($missing) ? true : $missing;
}
