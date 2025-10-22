<?php include __DIR__ . '/partials/header.php'; ?>

<!-- Hero -->
<section class="hero hero--home">
  <div class="container">
    <div class="hero__grid">
      <div class="hero__copy">
        <span class="badge">NMD Travels</span>
        <h1 class="h1">Discover the world with NMD Travels</h1>
        <p class="lead">Handcrafted tours, seamless bookings, and unforgettable experiences tailored for every traveler.</p>
        <a class="btn" href="<?php echo $basePath; ?>/services.php">Explore Packages</a>
      </div>
      <div class="hero__media">
        <img class="hero__img" src="<?php echo $basePath; ?>/assets/img/hero.jpg" alt="Travel destinations">
      </div>
    </div>
  </div>
</section>

<!-- Value props -->
<section class="section">
  <div class="container card-grid">
    <div class="card">
      <h3 class="h3">Curated Tours</h3>
      <p>City breaks, beach escapes, and adventure trails crafted by local experts.</p>
    </div>
    <div class="card">
      <h3 class="h3">End to end Support</h3>
      <p>Flights, hotels, visas, insurance, and 24/7 assistance for peace of mind.</p>
    </div>
    <div class="card">
      <h3 class="h3">Best Value</h3>
      <p>Transparent pricing and seasonal offers for every budget.</p>
    </div>
  </div>
</section>

<!-- Services snapshot -->
<section class="section">
  <div class="container">
    <div class="section-head">
      <h2 class="h2">Vehicles & Services</h2>
      <p class="muted">Sedans, SUVs, Tempo Travellers, and Buses for airport runs, corporate travel, weddings, and custom tours.</p>
    </div>
    <div class="grid-3">
      <div class="card">
        <h3 class="h3">Sedans & SUVs</h3>
        <p>Comfortable rides for city and outstation trips with AC and professional chauffeurs.</p>
      </div>
      <div class="card">
        <h3 class="h3">Tempo Travellers</h3>
        <p>12–21 seater options for family tours and team outings with ample luggage space.</p>
      </div>
      <div class="card">
        <h3 class="h3">Mini/Full Buses</h3>
        <p>Group travel, pilgrimages, and long routes with safe, well‑maintained coaches.</p>
      </div>
    </div>
    <div class="actions">
      <a class="btn" href="<?php echo $basePath; ?>/services.php">See all services</a>
    </div>
  </div>
</section>

<!-- Trust badges -->
<section class="section">
  <div class="container">
    <div class="card row-between">
      <div>
        <h3 class="h3">Trusted by families and businesses</h3>
        <p class="muted">Licensed vehicles, experienced chauffeurs, and reliable schedules for 26+ years.</p>
      </div>
      <div class="chips">
        <span class="badge">On‑time</span>
        <span class="badge">Clean Cars</span>
        <span class="badge">Verified Drivers</span>
      </div>
    </div>
  </div>
</section>

<!-- Reviews (compliant summary with link) -->
<section class="section">
  <div class="container">
    <div class="section-head">
      <h2 class="h2">What customers say</h2>
      <p class="muted">High ratings and many positive comments on Justdial from real travelers.</p>
    </div>

    <div class="grid-3">
      <article class="review">
        <img class="avatar" src="<?php echo $basePath; ?>/assets/img/saravana.jpg" alt="">
        <div class="review__body">
          <div class="review__head">
            <strong>Saravana</strong>
            <span class="stars">★★★★★</span>
          </div>
          <p class="muted">“Clean vehicle and punctual pickup. Seamless airport drop.”</p>
        </div>
      </article>

      <article class="review">
        <img class="avatar" src="<?php echo $basePath; ?>/assets/img/dharshan.jpg" alt="">
        <div class="review__body">
          <div class="review__head">
            <strong>Dharshan</strong>
            <span class="stars">★★★★☆</span>
          </div>
          <p class="muted">“Driver was courteous and the bus was comfortable for our group.”</p>
        </div>
      </article>

      <article class="review">
        <img class="avatar" src="<?php echo $basePath; ?>/assets/img/passport size photo.jpg" alt="">
        <div class="review__body">
          <div class="review__head">
            <strong>Jeeevakumar</strong>
            <span class="stars">★★★★★</span>
          </div>
          <p class="muted">“Great experience on our temple tour. Highly recommended.”</p>
        </div>
      </article>
    </div>

    <div class="actions between">
      <p class="muted">Read full reviews and ratings on Justdial.</p>
      <a class="btn" href="https://www.justdial.com/Chennai/Nmd-Travels-Near-Zam-Bazaar-Royapettah/044P1221473572G7U2J6_BZDET" target="_blank" rel="noopener">See reviews on Justdial</a>
    </div>
  </div>
</section>

<!-- CTA banner -->
<section class="section">
  <div class="container">
    <div class="card row-between" style="padding:25px; background:#f9fafb; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.05); align-items:center;">
      
      <div>
        <h3 class="h3" style="margin:0 0 8px; color:#1e3c72;">Ready to book your ride?</h3>
        <p class="muted" style="margin:0; font-size:15px; color:#555;">Tell us your date and destination; availability is confirmed promptly.</p>
      </div>
      
      <a href="<?php echo $basePath; ?>/booking.php" 
         style="padding:12px 28px; background:linear-gradient(90deg,#1e3c72,#3a5ca8); color:#fff; font-weight:600; font-size:16px; border-radius:8px; text-decoration:none; box-shadow:0 4px 8px rgba(0,0,0,0.15); transition:all 0.3s; display:inline-block;">
         Book Now
      </a>
      
    </div>
  </div>
</section>

<style>
  .card a:hover {
    background:linear-gradient(90deg,#16335c,#2a4a7a);
    transform:translateY(-2px);
    box-shadow:0 6px 12px rgba(0,0,0,0.2);
  }
</style>


<?php include __DIR__ . '/partials/footer.php'; ?>
