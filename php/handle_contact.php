<?php
require __DIR__ . '/config.php';
require __DIR__ . '/csrf.php';
require __DIR__ . '/rate_limit.php';

if (!isset($_POST['csrf']) || !csrf_verify($_POST['csrf'])) {
  exit('Invalid request.');
}
if (!rate_limit('contact', 60)) {
  exit('Please wait before submitting again.');
}

function required($key) { return isset($_POST[$key]) && trim($_POST[$key]) !== ''; }

if (!required('name') || !required('email') || !required('message')) {
  exit('Missing required fields.');
}

$name    = trim($_POST['name']);
$email   = trim($_POST['email']);
$phone   = isset($_POST['phone']) ? trim($_POST['phone']) : null;
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : null;
$message = trim($_POST['message']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { exit('Invalid email.'); }

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
  exit('Message save failed: ' . $e->getMessage());
}

header('Location: /travel-site/thank-you.php');
exit;
