<?php
require __DIR__ . '/config.php';
require __DIR__ . '/csrf.php';
require __DIR__ . '/rate_limit.php';
require __DIR__ . '/helpers.php';

// Determine base path
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$basePath = str_replace('/php', '', $basePath);

if (!isset($_POST['csrf']) || !csrf_verify($_POST['csrf'])) {
  http_response_code(403);
  exit('Invalid request. Please refresh the page and try again.');
}
if (!rate_limit('contact', 60)) {
  http_response_code(429);
  exit('Please wait 60 seconds before submitting again.');
}

function required($key) { return isset($_POST[$key]) && trim($_POST[$key]) !== ''; }

if (!required('name') || !required('email') || !required('message')) {
  http_response_code(400);
  exit('Missing required fields. Please fill all mandatory fields.');
}

$name    = trim($_POST['name']);
$email   = trim($_POST['email']);
$phone   = isset($_POST['phone']) ? trim($_POST['phone']) : null;
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : null;
$message = trim($_POST['message']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
  http_response_code(400);
  exit('Invalid email address format.'); 
}

// Normalize and limit lengths to match schema
$name    = mb_substr($name, 0, 120);
$email   = mb_substr($email, 0, 160);
$phone   = $phone ? mb_substr($phone, 0, 40) : null;
$subject = $subject ? mb_substr($subject, 0, 160) : null;
$message = mb_substr($message, 0, 4000);

try {
  $stmt = $pdo->prepare(
    'INSERT INTO messages (name, email, phone, subject, message, created_at)
     VALUES (:name, :email, :phone, :subject, :message, NOW())'
  );
  $stmt->execute([
    ':name'    => $name,
    ':email'   => $email,
    ':phone'   => $phone,
    ':subject' => $subject,
    ':message' => $message
  ]);
} catch (Throwable $e) {
  log_error('Contact message save failed', ['error' => $e->getMessage(), 'data' => $_POST]);
  http_response_code(500);
  exit('Unable to send message. Please try again or contact us directly.');
}

// Success - redirect to thank you page
header('Location: ' . $basePath . '/thank-you.php');
exit;
