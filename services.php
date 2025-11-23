<?php
require __DIR__ . '/php/config.php';
include __DIR__ . '/partials/header.php';

$stmt = $pdo->query('SELECT title, summary, price, currency, duration_label, image FROM services WHERE is_active = 1 ORDER BY id DESC');
$services = $stmt->fetchAll();
?>
<section class="section">
  <div class="container">
    <div class="text-center mb-8">
      <h1>Services & Packages</h1>
      <p class="text-lead text-muted">Browse popular tours and travel services. More packages available on request.</p>
    </div>
    
    <div class="grid-3">
      <?php if (!$services): ?>
        <div class="card">
          <h3>Packages coming soon</h3>
          <p class="text-muted">Please check back later or contact us for custom itineraries.</p>
        </div>
      <?php else: ?>
        <?php foreach ($services as $s): ?>
          <div class="card card-interactive">
            <?php if (!empty($s['image'])): ?>
              <img src="<?php echo htmlspecialchars($basePath . '/php/uploads/' . $s['image']); ?>" 
                   alt="<?php echo htmlspecialchars($s['title']); ?>" 
                   class="img-cover aspect-4-3 mb-4" 
                   loading="lazy" 
                   decoding="async">
            <?php endif; ?>
            <h3><?php echo htmlspecialchars($s['title']); ?></h3>
            <p class="text-muted"><?php echo htmlspecialchars($s['summary'] ?? ''); ?></p>
            <?php if (!is_null($s['price'])): ?>
              <p class="text-sm text-muted mb-4">
                <strong>From:</strong> <?php echo htmlspecialchars($s['currency']); ?> <?php echo number_format($s['price'], 2); ?> 
                <span class="text-light">·</span> 
                <?php echo htmlspecialchars($s['duration_label'] ?? ''); ?>
              </p>
            <?php endif; ?>
            <a class="btn btn-full" href="<?php echo $basePath; ?>/booking.php">Book now</a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
