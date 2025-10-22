<?php
require __DIR__ . '/php/config.php';
include __DIR__ . '/partials/header.php';

$secret = isset($_GET['key']) ? $_GET['key'] : '';
if ($secret !== 'local-admin') {
  echo '<section class="section"><div class="container"><div class="card"><h3>Access denied</h3><p>Append ?key=local-admin to the URL for local review.</p></div></div></section>';
  include __DIR__ . '/partials/footer.php';
  exit;
}

// Fetch latest bookings
$bookings = $pdo->query('SELECT id, full_name, email, phone, package_name, travel_date, adults, status, created_at FROM bookings ORDER BY id DESC LIMIT 20')->fetchAll();

// Fetch latest messages
$messages = $pdo->query('SELECT id, name, email, phone, subject, created_at FROM messages ORDER BY id DESC LIMIT 20')->fetchAll();

// Fetch all services for admin management
$services = $pdo->query('SELECT id, title, summary, price, currency, duration_label, is_active FROM services ORDER BY id DESC')->fetchAll();
?>

<section class="section">
  <div class="container">
    <h1 style="margin:0 0 14px;">Admin Dashboard</h1>

    <div class="grid-2">
      <!-- Recent Bookings -->
      <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
          <h3 style="margin:0;">Recent Bookings</h3>
          <span class="badge"><?php echo count($bookings); ?> latest</span>
        </div>
        <?php if (!$bookings): ?>
          <p class="help">No bookings yet.</p>
        <?php else: ?>
          <div style="overflow:auto;">
            <table style="width:100%; border-collapse:collapse;">
              <thead>
                <tr>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">When</th>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Name</th>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Package</th>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Date</th>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Adults</th>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Status</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($bookings as $b): ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f1f5f9;"><?php echo htmlspecialchars($b['created_at']); ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f1f5f9;"><?php echo htmlspecialchars($b['full_name']); ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f1f5f9;"><?php echo htmlspecialchars($b['package_name']); ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f1f5f9;"><?php echo htmlspecialchars($b['travel_date']); ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f1f5f9;"><?php echo htmlspecialchars($b['adults']); ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f1f5f9;"><?php echo htmlspecialchars($b['status']); ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

      <!-- Recent Messages -->
      <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
          <h3 style="margin:0;">Recent Messages</h3>
          <span class="badge"><?php echo count($messages); ?> latest</span>
        </div>
        <?php if (!$messages): ?>
          <p class="help">No messages yet.</p>
        <?php else: ?>
          <ul style="margin:0; padding-left:18px;">
            <?php foreach ($messages as $m): ?>
              <li style="margin:6px 0;">
                <strong><?php echo htmlspecialchars($m['name']); ?></strong>
                <span class="help"> — <?php echo htmlspecialchars($m['email']); ?> · <?php echo htmlspecialchars($m['created_at']); ?></span>
                <div><?php echo htmlspecialchars($m['subject'] ?? 'No subject'); ?></div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>

    <!-- Manage Services -->
    <div class="card" style="margin-top:24px;">
      <h3>Manage Services / Packages</h3>
      <a class="btn" href="admin_services.php?key=local-admin" style="margin-bottom:12px;">Add New Service</a>

      <?php if (!$services): ?>
        <p class="help">No services added yet.</p>
      <?php else: ?>
        <table style="width:100%; border-collapse:collapse;">
          <thead>
            <tr>
              <th>Title</th>
              <th>Price</th>
              <th>Duration</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($services as $s): ?>
              <tr>
                <td><?php echo htmlspecialchars($s['title']); ?></td>
                <td><?php echo htmlspecialchars($s['currency'] . ' ' . number_format($s['price'],2)); ?></td>
                <td><?php echo htmlspecialchars($s['duration_label']); ?></td>
                <td><?php echo $s['is_active'] ? 'Active' : 'Inactive'; ?></td>
                <td>
                  <a href="admin_services.php?id=<?php echo $s['id']; ?>&key=local-admin" class="btn outline">Edit</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
