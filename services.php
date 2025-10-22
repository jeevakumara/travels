<?php
require __DIR__ . '/php/config.php';
include __DIR__ . '/partials/header.php';

$stmt = $pdo->query('SELECT title, summary, price, currency, duration_label, image FROM services WHERE is_active = 1 ORDER BY id DESC');
$services = $stmt->fetchAll();
?>
<section class="section">
  <div class="container">
    <h1>Services & Packages</h1>
    <p>Browse popular tours and travel services. More packages available on request.</p>
    <div class="card-grid-wrap">
      <div class="card-grid">
        <?php if (!$services): ?>
          <div class="card">
            <h3>Packages coming soon</h3>
            <p>Please check back later or contact us for custom itineraries.</p>
          </div>
        <?php else: ?>
          <?php foreach ($services as $s): ?>
            <div class="card">
              <?php if (!empty($s['image'])): ?>
                <img src="<?php echo htmlspecialchars($basePath . '/php/uploads/' . $s['image']); ?>" alt="<?php echo htmlspecialchars($s['title']); ?>" style="width:100%;height:200px;object-fit:cover;border-radius:6px;">
              <?php endif; ?>
              <h3><?php echo htmlspecialchars($s['title']); ?></h3>
              <p><?php echo htmlspecialchars($s['summary'] ?? ''); ?></p>
              <?php if (!is_null($s['price'])): ?>
                <p><strong>From:</strong> <?php echo htmlspecialchars($s['currency']); ?> <?php echo number_format($s['price'], 2); ?> · <?php echo htmlspecialchars($s['duration_label'] ?? ''); ?></p>
              <?php endif; ?>
              <p><a class="btn" href="<?php echo $basePath; ?>/booking.php">Book now</a></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
