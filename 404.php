<?php 
http_response_code(404);
include __DIR__ . '/partials/header.php'; 
?>
<section class="section">
  <div class="container container-narrow">
    <div class="card text-center">
      <div class="mb-6">
        <svg width="80" height="80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto; color: var(--color-text-light);">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <h1 class="mb-4">Page Not Found</h1>
      <p class="text-lead text-muted mb-8">The page you're looking for doesn't exist or has been moved.</p>
      <div class="flex gap-4 justify-center flex-wrap">
        <a class="btn btn-lg" href="<?php echo $basePath; ?>/index.php">Go to Homepage</a>
        <a class="btn btn-lg outline" href="<?php echo $basePath; ?>/contact.php">Contact Us</a>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
