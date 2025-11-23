<?php include __DIR__ . '/partials/header.php'; ?>
<section class="section">
  <div class="container container-narrow">
    <div class="card text-center">
      <div class="mb-6">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto; color: var(--color-success);">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <h1>Thank you!</h1>
      <p class="text-lead text-muted mb-8">Your submission was received. We'll get back to you shortly.</p>
      <a class="btn btn-lg" href="<?php echo $basePath; ?>/index.php">Back to Home</a>
    </div>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
