<?php include __DIR__ . '/partials/header.php'; ?>

<!-- Hero -->
<section class="hero">
  <div class="container">
    <div class="hero-grid">
      <div class="hero-content">
        <span class="badge">NMD Travels</span>
        <h1>Discover the world with NMD Travels</h1>
        <p class="text-lead">Handcrafted tours, seamless bookings, and unforgettable experiences tailored for every traveler.</p>
        <a class="btn btn-lg" href="<?php echo $basePath; ?>/services.php">Explore Packages</a>
      </div>
      <div class="hero-media">
        <img class="hero-image" src="<?php echo $basePath; ?>/assets/img/hero.jpg" alt="Travel destinations" loading="lazy" decoding="async">
      </div>
    </div>
  </div>
</section>

<!-- Value props -->
<section class="section">
  <div class="container">
    <div class="grid-3">
      <div class="card">
        <h3>Curated Tours</h3>
        <p class="text-muted">City breaks, beach escapes, and adventure trails crafted by local experts.</p>
      </div>
      <div class="card">
        <h3>End to end Support</h3>
        <p class="text-muted">Flights, hotels, visas, insurance, and 24/7 assistance for peace of mind.</p>
      </div>
      <div class="card">
        <h3>Best Value</h3>
        <p class="text-muted">Transparent pricing and seasonal offers for every budget.</p>
      </div>
    </div>
  </div>
</section>

<!-- Services snapshot -->
<section class="section">
  <div class="container">
    <div class="text-center mb-8">
      <h2>Vehicles & Services</h2>
      <p class="text-lead text-muted">Sedans, SUVs, Tempo Travellers, and Buses for airport runs, corporate travel, weddings, and custom tours.</p>
    </div>
    <div class="grid-3">
      <div class="card">
        <h3>Sedans & SUVs</h3>
        <p class="text-muted">Comfortable rides for city and outstation trips with AC and professional chauffeurs.</p>
      </div>
      <div class="card">
        <h3>Tempo Travellers</h3>
        <p class="text-muted">12–21 seater options for family tours and team outings with ample luggage space.</p>
      </div>
      <div class="card">
        <h3>Mini/Full Buses</h3>
        <p class="text-muted">Group travel, pilgrimages, and long routes with safe, well‑maintained coaches.</p>
      </div>
    </div>
    <div class="text-center mt-8">
      <a class="btn btn-lg" href="<?php echo $basePath; ?>/services.php">See all services</a>
    </div>
  </div>
</section>

<!-- Trust badges -->
<section class="section-sm">
  <div class="container">
    <div class="card">
      <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
          <h3>Trusted by families and businesses</h3>
          <p class="text-muted m-0">Licensed vehicles, experienced chauffeurs, and reliable schedules for 26+ years.</p>
        </div>
        <div class="flex flex-wrap gap-3 justify-center">
          <span class="badge">On‑time</span>
          <span class="badge">Clean Cars</span>
          <span class="badge">Verified Drivers</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Reviews -->
<section class="section">
  <div class="container">
    <div class="text-center mb-8">
      <h2>What customers say</h2>
      <p class="text-lead text-muted">High ratings and many positive comments on Justdial from real travelers.</p>
    </div>

    <div class="grid-3">
      <article class="review">
        <img class="review-avatar" src="<?php echo $basePath; ?>/assets/img/saravana.jpg" alt="" loading="lazy">
        <div class="review-body">
          <div class="review-header">
            <strong class="review-author">Saravana</strong>
            <span class="review-stars">★★★★★</span>
          </div>
          <p class="review-text">"Clean vehicle and punctual pickup. Seamless airport drop."</p>
        </div>
      </article>

      <article class="review">
        <img class="review-avatar" src="<?php echo $basePath; ?>/assets/img/dharshan.jpg" alt="" loading="lazy">
        <div class="review-body">
          <div class="review-header">
            <strong class="review-author">Dharshan</strong>
            <span class="review-stars">★★★★☆</span>
          </div>
          <p class="review-text">"Driver was courteous and the bus was comfortable for our group."</p>
        </div>
      </article>

      <article class="review">
        <img class="review-avatar" src="<?php echo $basePath; ?>/assets/img/passport size photo.jpg" alt="" loading="lazy">
        <div class="review-body">
          <div class="review-header">
            <strong class="review-author">Jeeevakumar</strong>
            <span class="review-stars">★★★★★</span>
          </div>
          <p class="review-text">"Great experience on our temple tour. Highly recommended."</p>
        </div>
      </article>
    </div>

    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mt-8">
      <p class="text-muted m-0">Read full reviews and ratings on Justdial.</p>
      <a class="btn outline" href="https://www.justdial.com/Chennai/Nmd-Travels-Near-Zam-Bazaar-Royapettah/044P1221473572G7U2J6_BZDET" target="_blank" rel="noopener">See reviews on Justdial</a>
    </div>
  </div>
</section>

<!-- CTA banner -->
<section class="section-sm">
  <div class="container">
    <div class="card">
      <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
          <h3 class="m-0 mb-2">Ready to book your ride?</h3>
          <p class="text-muted m-0">Tell us your date and destination; availability is confirmed promptly.</p>
        </div>
        <a href="<?php echo $basePath; ?>/booking.php" class="btn btn-lg accent">Book Now</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
