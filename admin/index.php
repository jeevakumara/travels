<?php
require_once __DIR__ . '/../config.php';

// Security Check: Enforce Admin Session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . $basePath . '/admin/login.php');
    exit;
}

// Logout Action
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $basePath . '/admin/login.php');
    exit;
}

// ----------------- CONFIG (fill if you want PHPMailer SMTP) -----------------
$smtp = [
  'enabled' => false,    // set true to use PHPMailer SMTP
  'host' => 'smtp.example.com',
  'username' => 'user@example.com',
  'password' => 'secret',
  'port' => 587,
  'secure' => 'tls',     // 'tls' or 'ssl'
  'from_email' => 'noreply@nmdtravels.com',
  'from_name' => 'NMD Travels'
];
// ---------------------------------------------------------------------------

// ----- Handle POST actions: send_mail, mark_responded, search handled via GET -----
$messages_feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // CSRF Check (using the unified token)
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $messages_feedback = 'Invalid CSRF token.';
  } else {
      // Send mail action
      if (isset($_POST['action']) && $_POST['action'] === 'send_mail') {
        $to = filter_var($_POST['to'] ?? '', FILTER_VALIDATE_EMAIL);
        $subject = trim($_POST['subject'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $message_id = intval($_POST['message_id'] ?? 0);

        if ($to && $subject && $body) {
          // Try PHPMailer if configured and installed
          $sent = false;
          if ($smtp['enabled'] && file_exists(__DIR__ . '/../vendor/autoload.php')) {
            require __DIR__ . '/../vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
              $mail->isSMTP();
              $mail->Host = $smtp['host'];
              $mail->SMTPAuth = true;
              $mail->Username = $smtp['username'];
              $mail->Password = $smtp['password'];
              $mail->SMTPSecure = $smtp['secure'];
              $mail->Port = $smtp['port'];
              $mail->setFrom($smtp['from_email'], $smtp['from_name']);
              $mail->addAddress($to);
              $mail->Subject = $subject;
              $mail->Body = $body;
              $mail->isHTML(false);
              $mail->send();
              $sent = true;
            } catch (Exception $e) {
              $messages_feedback = 'Mailer error: ' . htmlspecialchars($mail->ErrorInfo);
            }
          } else {
            // Fallback to PHP mail()
            $headers = "From: " . ($smtp['from_email'] ?? 'noreply@nmdtravels.com') . "\r\n";
            $headers .= "Reply-To: " . ($smtp['from_email'] ?? 'noreply@nmdtravels.com') . "\r\n";
            if (@mail($to, $subject, $body, $headers)) {
              $sent = true;
            } else {
              $messages_feedback = 'Failed to send mail via PHP mail().';
            }
          }

          if ($sent) {
            // mark message responded in DB (if message id provided)
            if ($message_id) {
              $stmt = $pdo->prepare("UPDATE messages SET responded = 1, responded_at = NOW() WHERE id = ?");
              $stmt->execute([$message_id]);
            }
            $messages_feedback = 'Mail sent and message marked responded.';
          }
        } else {
          $messages_feedback = 'Invalid mail data (check recipient, subject, message).';
        }
      }

      // Mark message responded action (no mail)
      if (isset($_POST['action']) && $_POST['action'] === 'mark_responded') {
        $message_id = intval($_POST['message_id'] ?? 0);
        if ($message_id) {
          $stmt = $pdo->prepare("UPDATE messages SET responded = 1, responded_at = NOW() WHERE id = ?");
          $stmt->execute([$message_id]);
          $messages_feedback = 'Message marked as responded.';
        }
      }

      // Update booking status (optional feature)
      if (isset($_POST['action']) && $_POST['action'] === 'update_booking_status') {
        $booking_id = intval($_POST['booking_id'] ?? 0);
        $new_status = trim($_POST['new_status'] ?? '');
        if ($booking_id && in_array($new_status, ['Pending','Confirmed','Cancelled','Completed'])) {
          $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
          $stmt->execute([$new_status, $booking_id]);
          $messages_feedback = 'Booking status updated.';
        }
      }
  }
}

// ----------------- Search handling -----------------
$searchQ = trim($_GET['q'] ?? '');
$searchClause = '';
$searchParams = [];
if ($searchQ !== '') {
  // we'll use it to filter bookings, messages, services with LIKE
  $searchClause = ' WHERE (full_name LIKE :q OR package_name LIKE :q OR email LIKE :q OR subject LIKE :q OR title LIKE :q) ';
  $searchParams[':q'] = '%' . $searchQ . '%';
}

// ----------------- Live Queries -----------------
// Totals
$totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$totalMessages = (int)$pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$totalServices = (int)$pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

// Users table may or may not exist - attempt safely
try {
  $totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
} catch (\Throwable $e) {
  $totalUsers = 0;
}

// Recent rows (respecting search)
if ($searchQ !== '') {
  // Bookings search
  $stmt = $pdo->prepare("SELECT * FROM bookings WHERE (full_name LIKE :q OR package_name LIKE :q OR email LIKE :q) ORDER BY id DESC LIMIT 25");
  $stmt->execute([':q' => '%' . $searchQ . '%']);
  $bookings = $stmt->fetchAll();

  // Messages search
  $stmt = $pdo->prepare("SELECT * FROM messages WHERE (name LIKE :q OR email LIKE :q OR subject LIKE :q) ORDER BY id DESC LIMIT 25");
  $stmt->execute([':q' => '%' . $searchQ . '%']);
  $messages = $stmt->fetchAll();

  // Services search
  $stmt = $pdo->prepare("SELECT * FROM services WHERE title LIKE :q OR summary LIKE :q ORDER BY id DESC LIMIT 25");
  $stmt->execute([':q' => '%' . $searchQ . '%']);
  $services = $stmt->fetchAll();
} else {
  // default recent
  $bookings = $pdo->query('SELECT * FROM bookings ORDER BY id DESC LIMIT 15')->fetchAll();
  $messages = $pdo->query('SELECT * FROM messages ORDER BY id DESC LIMIT 15')->fetchAll();
  $services = $pdo->query('SELECT * FROM services ORDER BY id DESC LIMIT 25')->fetchAll();
  $totalUsers = $pdo->query("
  SELECT COUNT(DISTINCT email)
  FROM (
    SELECT email FROM bookings
    UNION
    SELECT email FROM messages
  ) AS combined
")->fetchColumn();

}

// Monthly bookings for last 12 months (year-month labels)
$stmt = $pdo->prepare("
  SELECT DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as cnt
  FROM bookings
  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
  GROUP BY ym
  ORDER BY ym ASC
");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$months = [];
$counts = [];
// Build last 12 months labels and map
$period = new DatePeriod(
  new DateTime(date('Y-m-01', strtotime('-11 months'))),
  new DateInterval('P1M'),
  12
);
$map = [];
foreach ($rows as $r) $map[$r['ym']] = (int)$r['cnt'];
foreach ($period as $dt) {
  $ym = $dt->format('Y-m');
  $label = $dt->format('M Y');
  $months[] = $label;
  $counts[] = $map[$ym] ?? 0;
}

// Top packages (by bookings) if package_name used in bookings
$stmt = $pdo->query("
  SELECT package_name, COUNT(*) as c
  FROM bookings
  WHERE package_name IS NOT NULL AND package_name <> ''
  GROUP BY package_name
  ORDER BY c DESC
  LIMIT 6
");
$topPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare JSON for JS
$chartMonths = json_encode($months);
$chartCounts = json_encode($counts);
$topPackagesLabels = json_encode(array_column($topPackages, 'package_name'));
$topPackagesCounts = json_encode(array_column($topPackages, 'c'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>NMD Travels — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $basePath; ?>/assets/css/site.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
/* --- Admin Specific Styles --- */
:root{
  --brand:#1e3c72;
  --accent:#3a5ca8;
  --muted:#6b7280;
  --bg:#f4f6f9;
}
body{background:var(--bg);}
.admin-wrap{display:flex;min-height:100vh;}
.sidebar{width:240px;background:var(--brand);color:#fff;padding:22px;position:fixed;height:100vh;display:flex;flex-direction:column;justify-content:space-between; z-index: 100;}
.logo{font-size:20px;font-weight:700;text-align:center;margin-bottom:8px}
.nav{margin-top:12px}
.nav a{display:block;color:#fff;text-decoration:none;padding:10px 12px;border-radius:8px;margin:6px 0;font-weight:500}
.nav a.active, .nav a:hover{background:rgba(255,255,255,0.08)}
.footer-note{font-size:12px;text-align:center;opacity:0.85}
.main{margin-left:260px;padding:24px;flex:1; width: calc(100% - 260px);}
.topbar{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px}
.topbar .search{width:420px;max-width:60%}
.topbar input[type="search"]{width:100%;padding:10px 12px;border-radius:8px;border:1px solid #e6e9ee}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:18px}
.card{background:#fff;padding:18px;border-radius:12px;box-shadow:0 6px 18px rgba(16,24,40,0.06)}
.card h4{margin:0;color:var(--brand)}
.stat-num{font-size:26px;font-weight:700;margin-top:8px}
.section{margin-top:24px}
.table-responsive{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;border-radius:8px;box-shadow:0 6px 18px rgba(16,24,40,0.06)}
.table{width:100%;border-collapse:collapse;background:#fff}
.table thead th{background:#f8fafc;color:var(--brand);padding:12px;border-bottom:1px solid #eef2f7;text-align:left;font-weight:600}
.table tbody td{padding:12px;border-bottom:1px solid #f1f5f9}
.table tbody tr:hover{background:#fbfdff}
.status{padding:6px 8px;border-radius:8px;font-weight:600;font-size:13px}
.status.pending{background:#fff4e5;color:#92400e}
.status.confirmed{background:#e6ffef;color:#065f46}
.status.cancelled{background:#fee2e5;color:#9b1c1c}
.message-card{background:#fff;padding:14px;border-radius:10px;margin-bottom:12px;box-shadow:0 4px 12px rgba(16,24,40,0.04)}
.flex-row{display:flex;gap:12px;align-items:center}
.right{margin-left:auto}
.small{font-size:13px;color:var(--muted)}
.modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;padding:20px;z-index:999}
.modal .box{background:#fff;padding:18px;border-radius:10px;max-width:640px;width:100%}
.form-row{margin-bottom:10px}
.form-row label{display:block;font-weight:600;margin-bottom:6px}
.form-row input,.form-row textarea{width:100%;padding:10px;border:1px solid #e6e9ee;border-radius:8px}
.kv{font-size:12px;color:var(--muted)}
@media(max-width:900px){
  .topbar .search{max-width:100%}
  .sidebar{display:none}
  .main{margin-left:0;padding:16px; width: 100%;}
}
.feedback{margin-top:10px;color:green;font-weight:600}
</style>
</head>
<body>
<div class="admin-wrap">
  <aside class="sidebar">
    <div>
      <div class="logo">NMD <span style="color:#a5c0ff">Admin</span></div>
      <nav class="nav">
        <a href="#overview" class="active">Dashboard</a>
        <a href="#bookings">Bookings</a>
        <a href="#messages">Messages</a>
        <a href="services.php">Services</a>
        <a href="#analytics">Analytics</a>
        <a href="?logout=true" style="margin-top: 20px; background: rgba(255,0,0,0.1);">Logout</a>
      </nav>
    </div>
    <div class="footer-note">© <?php echo date('Y'); ?> NMD Travels</div>
  </aside>

  <main class="main">
    <div class="topbar">
      <div>
        <h2 style="margin:0;color:var(--brand)">Admin Dashboard</h2>
        <div class="kv small">Manage bookings, messages and services in one place</div>
      </div>

      <div class="search">
        <form method="get" action="">
          <input type="search" name="q" placeholder="Search bookings, messages, services..." value="<?php echo htmlspecialchars($searchQ); ?>">
        </form>
      </div>
    </div>

    <!-- Overview Cards -->
    <section id="overview" class="cards">
      <div class="card">
        <h4>Total Bookings</h4>
        <div class="stat-num"><?php echo $totalBookings; ?></div>
        <div class="kv">Recent <?php echo count($bookings); ?> shown</div>
      </div>

      <div class="card">
        <h4>Total Messages</h4>
        <div class="stat-num"><?php echo $totalMessages; ?></div>
        <div class="kv">Pending: <?php
          $pending = (int)$pdo->query("SELECT COUNT(*) FROM messages WHERE responded = 0")->fetchColumn();
          echo $pending;
        ?></div>
      </div>

      <div class="card">
        <h4>Total Services</h4>
        <div class="stat-num"><?php echo $totalServices; ?></div>
        <div class="kv">Active: <?php
          $active = (int)$pdo->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
          echo $active;
        ?></div>
      </div>

<div class="card">
  <h4>Total Users</h4>
  <div class="stat-num"><?php echo $totalUsers; ?></div>
  <div class="kv">Unique customers (Bookings + Messages)</div>
</div>

    </section>

    <!-- Bookings Table -->
    <section id="bookings" class="section">
      <h3>Recent Bookings</h3>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr><th>Name</th><th>Package</th><th>Travel Date</th><th>Adults</th><th>When</th><th>Status</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php if (count($bookings)===0): ?>
              <tr><td colspan="7" class="kv">No bookings found.</td></tr>
            <?php else: foreach ($bookings as $b): ?>
              <tr>
                <td><?php echo htmlspecialchars($b['full_name']); ?></td>
                <td><?php echo htmlspecialchars($b['package_name']); ?></td>
                <td><?php echo htmlspecialchars($b['travel_date']); ?></td>
                <td><?php echo htmlspecialchars($b['adults']); ?></td>
                <td class="small"><?php echo htmlspecialchars($b['created_at']); ?></td>
                <td><span class="status <?php echo strtolower($b['status'] ?? 'pending'); ?>"><?php echo htmlspecialchars($b['status'] ?? 'Pending'); ?></span></td>
                <td>
                  <form method="post" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="action" value="update_booking_status">
                    <input type="hidden" name="booking_id" value="<?php echo intval($b['id']); ?>">
                    <select name="new_status" style="padding:6px;border-radius:6px;border:1px solid #e6e9ee">
                      <option value="Pending" <?php if (($b['status'] ?? '')==='Pending') echo 'selected'; ?>>Pending</option>
                      <option value="Confirmed" <?php if (($b['status'] ?? '')==='Confirmed') echo 'selected'; ?>>Confirmed</option>
                      <option value="Cancelled" <?php if (($b['status'] ?? '')==='Cancelled') echo 'selected'; ?>>Cancelled</option>
                      <option value="Completed" <?php if (($b['status'] ?? '')==='Completed') echo 'selected'; ?>>Completed</option>
                    </select>
                    <button class="btn" type="submit" style="margin-left:6px">Update</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Messages -->
    <section id="messages" class="section">
      <h3>Messages & Enquiries</h3>
      <?php if (count($messages)===0): ?>
        <div class="kv">No messages available.</div>
      <?php else: foreach ($messages as $m): ?>
        <div class="message-card">
          <div class="flex-row">
            <div>
              <strong><?php echo htmlspecialchars($m['name']); ?></strong>
              <div class="small"><?php echo htmlspecialchars($m['email']); ?> • <?php echo htmlspecialchars($m['phone'] ?? '-'); ?></div>
            </div>
            <div class="right">
              <div class="small">Received: <?php echo htmlspecialchars($m['created_at']); ?></div>
              <div style="margin-top:8px">
                <?php if (empty($m['responded']) || $m['responded']==0): ?>
                  <form method="post" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="action" value="mark_responded">
                    <input type="hidden" name="message_id" value="<?php echo intval($m['id']); ?>">
                    <button class="btn outline" type="submit">Mark Responded</button>
                  </form>
                <?php else: ?>
                  <span class="kv">Responded: <?php echo htmlspecialchars($m['responded_at'] ?? ''); ?></span>
                <?php endif; ?>
                <button class="btn" style="margin-left:8px" onclick="openReplyModal('<?php echo htmlspecialchars($m['email']); ?>', '<?php echo htmlspecialchars(addslashes($m['subject'] ?? 'Reply')); ?>', <?php echo intval($m['id']); ?>)">Reply</button>
              </div>
            </div>
          </div>
          <div style="margin-top:12px"><?php echo nl2br(htmlspecialchars($m['subject'] ?? '')); ?></div>
        </div>
      <?php endforeach; endif; ?>
      <?php if ($messages_feedback): ?>
        <div class="feedback"><?php echo htmlspecialchars($messages_feedback); ?></div>
      <?php endif; ?>
    </section>
              <tr><td colspan="4" class="kv">No services found.</td></tr>
            <?php else: foreach ($services as $s): ?>
              <tr>
                <td><?php echo htmlspecialchars($s['title']); ?></td>
                <td><?php echo htmlspecialchars(($s['currency'] ?? 'INR') . ' ' . number_format($s['price'],2)); ?></td>
                <td><?php echo htmlspecialchars($s['duration_label'] ?? '-'); ?></td>
                <td><?php echo ($s['is_active'] ? '<span class="status confirmed">Active</span>' : '<span class="status pending">Inactive</span>'); ?></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Analytics -->
    <section id="analytics" class="section">
      <h3>Analytics</h3>
      <div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start">
        <div class="card">
          <canvas id="bookingChart" height="140"></canvas>
        </div>
        <div class="card">
          <h4>Top Packages</h4>
          <?php if (count($topPackages)===0): ?>
            <div class="kv">No package data yet.</div>
          <?php else: ?>
            <ul style="padding-left:16px;margin:10px 0">
              <?php foreach ($topPackages as $p): ?>
                <li style="margin-bottom:8px"><?php echo htmlspecialchars($p['package_name']); ?> — <strong><?php echo intval($p['c']); ?></strong></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </section>

  </main>
</div>

<!-- Reply Modal -->
<div id="replyModal" class="modal" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="box">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
      <h3>Reply to message</h3>
      <button onclick="closeReplyModal()" class="btn outline">Close</button>
    </div>
    <form method="post" onsubmit="return submitReplyForm(this)">
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
      <input type="hidden" name="action" value="send_mail">
      <input type="hidden" id="msg_id" name="message_id" value="">
      <div class="form-row">
        <label>To (email)</label>
        <input type="email" id="replyTo" name="to" required>
      </div>
      <div class="form-row">
        <label>Subject</label>
        <input type="text" id="replySub" name="subject" required>
      </div>
      <div class="form-row">
        <label>Message</label>
        <textarea id="replyBody" name="body" rows="8" required></textarea>
      </div>
      <div style="display:flex;gap:10px;align-items:center">
        <button class="btn" type="submit">Send & Mark Responded</button>
        <span class="kv">After sending, the message status will be updated.</span>
      </div>
    </form>
  </div>
</div>

<script>
function openReplyModal(email, subject, messageId){
  document.getElementById('replyTo').value = email || '';
  document.getElementById('replySub').value = subject || '';
  document.getElementById('replyBody').value = '';
  document.getElementById('msg_id').value = messageId || 0;
  document.getElementById('replyModal').style.display = 'flex';
}
function closeReplyModal(){ document.getElementById('replyModal').style.display = 'none'; }
function submitReplyForm(form){
  // basic validation
  if(!form.to.value || !form.subject.value || !form.body.value){
    alert('Fill all fields');
    return false;
  }
  // allow form POST to submit (page reload)
  return true;
}

// Chart data from PHP
const months = <?php echo $chartMonths; ?>;
const counts = <?php echo $chartCounts; ?>;
const ctx = document.getElementById('bookingChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: months,
    datasets: [{
      label: 'Bookings (last 12 months)',
      data: counts,
      borderColor: getComputedStyle(document.documentElement).getPropertyValue('--brand') || '#1e3c72',
      backgroundColor: 'rgba(30,60,114,0.06)',
      tension: 0.25,
      fill: true,
      pointRadius: 4
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true }
    },
    responsive: true,
    maintainAspectRatio: false
  }
});
</script>
</body>
</html>
