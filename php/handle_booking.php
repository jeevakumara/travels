<?php
require __DIR__ . '/config.php';
require __DIR__ . '/csrf.php';
require __DIR__ . '/rate_limit.php';

if (!isset($_POST['csrf']) || !csrf_verify($_POST['csrf'])) {
  exit('Invalid request.');
}
if (!rate_limit('booking', 60)) {
  exit('Please wait before submitting again.');
}

function required($key) {
  return isset($_POST[$key]) && trim($_POST[$key]) !== '';
}

if (!required('full_name') || !required('email') || !required('phone') || !required('package_name') || !required('travel_date') || !required('adults')) {
  exit('Missing required fields.');
}

$full_name    = trim($_POST['full_name']);
$email        = trim($_POST['email']);
$phone        = trim($_POST['phone']);
$package_name = trim($_POST['package_name']);
$travel_date  = $_POST['travel_date'];
$adults       = (int)$_POST['adults'];
$notes        = isset($_POST['notes']) ? trim($_POST['notes']) : null;

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { exit('Invalid email.'); }
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $travel_date)) { exit('Invalid date.'); }

// Normalize and limit lengths to match schema
$full_name    = mb_substr($full_name, 0, 120);
$email        = mb_substr($email, 0, 160);
$phone        = mb_substr($phone, 0, 40);
$package_name = mb_substr($package_name, 0, 160);
$notes        = $notes !== null ? mb_substr($notes, 0, 2000) : null;

try {
  $stmt = $pdo->prepare(
    'INSERT INTO bookings (full_name, email, phone, package_name, travel_date, adults, notes, status, created_at)
     VALUES (:full_name, :email, :phone, :package_name, :travel_date, :adults, :notes, :status, NOW())'
  );
  $stmt->execute([
    ':full_name'    => $full_name,
    ':email'        => $email,
    ':phone'        => $phone,
    ':package_name' => $package_name,
    ':travel_date'  => $travel_date,
    ':adults'       => $adults,
    ':notes'        => $notes,
    ':status'       => 'new'
  ]);
} catch (Throwable $e) {
  exit('Booking save failed: ' . $e->getMessage());
}

header('Location: /travel-site/thank-you.php');
exit;
