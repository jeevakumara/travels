<?php
  $basePath = '/travel-site';
  require __DIR__ . '/../php/security_headers.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="NMD Travels - Trusted travel services in Chennai since 1999. Sedans, SUVs, Tempo Travellers, and Buses for all your travel needs.">
  <meta name="theme-color" content="#0EA5E9">
  <meta name="format-detection" content="telephone=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="dns-prefetch" href="https://www.google.com">
  <title>NMD Travels - Chennai's Trusted Travel Partner Since 1999</title>
  <link rel="stylesheet" href="<?php echo $basePath; ?>/assets/css/site.css">
</head>
<body>
  <a href="#main-content" class="skip-to-content">Skip to main content</a>
  
  <header class="site-header" role="banner">
    <div class="container header-container">
      <!-- Brand -->
      <a href="<?php echo $basePath; ?>/index.php" class="header-brand">
        <img src="<?php echo $basePath; ?>/assets/img/logo.jpg" alt="" class="header-logo">
        <div class="header-brand-text">
          <div class="header-brand-name">NMD Travels</div>
          <div class="header-brand-tagline">Trusted since 1999</div>
        </div>
      </a>

      <!-- Desktop Navigation -->
      <nav class="header-nav" role="navigation" aria-label="Main navigation">
        <a href="<?php echo $basePath; ?>/index.php" class="nav-link">Home</a>
        <a href="<?php echo $basePath; ?>/services.php" class="nav-link">Services</a>
        <a href="<?php echo $basePath; ?>/about.php" class="nav-link">About</a>
        <a href="<?php echo $basePath; ?>/booking.php" class="nav-link">Booking</a>
        <a href="<?php echo $basePath; ?>/contact.php" class="nav-link">Contact</a>
      </nav>

      <!-- CTA Button -->
      <div class="header-cta">
        <a href="<?php echo $basePath; ?>/booking.php" class="btn">Book Now</a>
      </div>

      <!-- Mobile Menu Toggle -->
      <button class="menu-toggle" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobile-nav">
        <span class="menu-icon">
          <span></span>
          <span></span>
          <span></span>
        </span>
      </button>
    </div>

    <!-- Mobile Navigation Panel -->
    <nav id="mobile-nav" class="mobile-nav" role="navigation" aria-label="Mobile navigation">
      <div class="mobile-nav-list">
        <a href="<?php echo $basePath; ?>/index.php" class="mobile-nav-link">Home</a>
        <a href="<?php echo $basePath; ?>/services.php" class="mobile-nav-link">Services</a>
        <a href="<?php echo $basePath; ?>/about.php" class="mobile-nav-link">About</a>
        <a href="<?php echo $basePath; ?>/booking.php" class="mobile-nav-link">Booking</a>
        <a href="<?php echo $basePath; ?>/contact.php" class="mobile-nav-link">Contact</a>
        <a href="<?php echo $basePath; ?>/booking.php" class="btn btn-full">Book Now</a>
      </div>
    </nav>
  </header>

  <main id="main-content" role="main">
    <?php
    // Display flash messages
    require_once __DIR__ . '/../php/helpers.php';
    $flash = get_flash_message();
    if ($flash):
    ?>
    <div class="flash-message flash-<?php echo h($flash['type']); ?>" role="alert">
      <div class="container">
        <div class="flash-content">
          <span class="flash-text"><?php echo h($flash['text']); ?></span>
          <button class="flash-close" aria-label="Close message">&times;</button>
        </div>
      </div>
    </div>
    <?php endif; ?>

