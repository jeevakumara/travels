<?php
  $basePath = '/travel-site';
  require __DIR__ . '/../php/security_headers.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>NMD Travels</title>
  <link rel="stylesheet" href="<?php echo $basePath; ?>/assets/css/style.css">
</head>
<body>
  <div class="site-bg" aria-hidden="true"></div>

  <header class="site-header pro">
    <div class="container header-row">
      <!-- Left: big logo + stacked brand -->
      <div class="header-left">
        <a class="logo" href="<?php echo $basePath; ?>/index.php">
          <img src="<?php echo $basePath; ?>/assets/img/logo.jpg" alt="NMD Travels">
        </a>
        <div class="brand">
          <div class="brand-name">NMD Travels</div>
          <div class="brand-addr">65/6, Chella Pillayar Koil St, Padupakkam, Royapettah, Chennai 600014</div>
        </div>
      </div>

      <!-- Center: navigation -->
      <nav class="header-center nav">
        <a href="<?php echo $basePath; ?>/index.php">Home</a>
        <a href="<?php echo $basePath; ?>/services.php">Services</a>
        <a href="<?php echo $basePath; ?>/booking.php">Booking</a>
        <a href="<?php echo $basePath; ?>/about.php">About</a>
        <a href="<?php echo $basePath; ?>/contact.php">Contact</a>
      </nav>

      <!-- Right: phone + CTA -->
      <div class="header-right">
        <a class="btn cta" href="<?php echo $basePath; ?>/booking.php">Get Quote</a>
      </div>
    </div>
  </header>

  <main class="site-main">

