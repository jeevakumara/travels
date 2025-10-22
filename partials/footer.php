<footer class="site-footer simple" style="background:#f9fafb; padding:40px 0; font-family:Arial, sans-serif; color:#333;">
  <div class="container" style="display:flex; flex-wrap:wrap; gap:30px; justify-content:space-between;">

    <!-- Brand Section -->
    <div class="ft-brand" style="flex:1 1 220px; min-width:220px;">
      <h4 style="margin-bottom:8px; color:#1e3c72;">NMD Travels</h4>
      <p style="margin-bottom:6px;">Trusted journeys since 1999. Safe, comfortable, and on time.</p>
      <p class="help" style="margin-bottom:6px;">65/6, Chella Pillayar Koil St, Padupakkam, Royapettah, Chennai 600014</p>
      <p style="margin-bottom:0;">
        <a href="tel:+919940671829" style="color:#1e3c72; text-decoration:none;">+91 9940671829</a> ·
        <a href="mailto:nmdtravelss@gmail.com" style="color:#1e3c72; text-decoration:none;">nmdtravelss@gmail.com</a>
      </p>
    </div>

    <!-- Quick Links -->
    <div class="ft-links" style="flex:1 1 150px; min-width:150px;">
      <h4 style="margin-bottom:12px; color:#1e3c72;">Quick Links</h4>
      <ul style="list-style:none; padding:0; margin:0; line-height:1.8;">
        <li><a href="<?php echo $basePath; ?>/services.php#sedan" style="color:#333; text-decoration:none;">Sedans</a></li>
        <li><a href="<?php echo $basePath; ?>/services.php#suv" style="color:#333; text-decoration:none;">SUVs</a></li>
        <li><a href="<?php echo $basePath; ?>/services.php#tempo" style="color:#333; text-decoration:none;">Tempo Travellers</a></li>
        <li><a href="<?php echo $basePath; ?>/services.php#bus" style="color:#333; text-decoration:none;">Buses</a></li>
      </ul>
    </div>

    <!-- Services -->
    <div class="ft-services" style="flex:1 1 180px; min-width:180px;">
      <h4 style="margin-bottom:12px; color:#1e3c72;">Services</h4>
      <ul style="list-style:none; padding:0; margin:0; line-height:1.8;">
        <li><a href="<?php echo $basePath; ?>/services.php#airport" style="color:#333; text-decoration:none;">Airport Transfers</a></li>
        <li><a href="<?php echo $basePath; ?>/services.php#corporate" style="color:#333; text-decoration:none;">Corporate Travel</a></li>
        <li><a href="<?php echo $basePath; ?>/services.php#wedding" style="color:#333; text-decoration:none;">Weddings & Events</a></li>
        <li><a href="<?php echo $basePath; ?>/services.php#tours" style="color:#333; text-decoration:none;">Custom Tours</a></li>
      </ul>
    </div>

    <!-- Booking CTA -->
    <div class="ft-cta" style="flex:1 1 250px; min-width:250px; text-align:center; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
      <h4 style="margin-bottom:10px; color:#1e3c72;">Bookings</h4>
      <p class="help" style="margin-bottom:16px; font-size:15px; color:#555;">Tell us your date and destination; availability confirmed promptly.</p>
      
      <a href="<?php echo $basePath; ?>/booking.php" 
         style="padding:12px 28px; background:linear-gradient(90deg,#1e3c72,#3a5ca8); color:#fff; font-weight:600; font-size:16px; border-radius:8px; text-decoration:none; box-shadow:0 4px 8px rgba(0,0,0,0.15); transition:all 0.3s; display:inline-block; margin-bottom:12px;">
         Book Now
      </a>

      <div class="socials" style="margin-top:12px; display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
        <a href="https://www.linkedin.com/in/nmd-travels-4a20bb381/" aria-label="LinkedIn" style="color:#1e3c72; text-decoration:none; font-weight:500;">LinkedIn</a>
        <a href="https://www.justdial.com/Chennai/Nmd-Travels-Near-Zam-Bazaar-Royapettah/044P1221473572G7U2J6_BZDET" target="_blank" rel="noopener" aria-label="Justdial" style="color:#1e3c72; text-decoration:none; font-weight:500;">Justdial</a>
        <a href="#" aria-label="Instagram" style="color:#1e3c72; text-decoration:none; font-weight:500;">Instagram</a>
        <a href="#" aria-label="Facebook" style="color:#1e3c72; text-decoration:none; font-weight:500;">Facebook</a>
      </div>
    </div>

  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom" style="margin-top:40px; border-top:1px solid #ddd; padding-top:16px; display:flex; flex-wrap:wrap; justify-content:space-between; font-size:14px; color:#555;">
    <span>© <?php echo date('Y'); ?> NMD Travels. All rights reserved.</span>
    <nav class="small-links" style="display:flex; gap:12px;">
      <a href="#" style="color:#555; text-decoration:none;">Privacy</a>
      <a href="#" style="color:#555; text-decoration:none;">Terms</a>
      <a href="<?php echo $basePath; ?>/contact.php" style="color:#555; text-decoration:none;">Support</a>
    </nav>
  </div>

</footer>

<style>
  .ft-cta a:hover {
    background:linear-gradient(90deg,#16335c,#2a4a7a);
    transform:translateY(-2px);
    box-shadow:0 6px 12px rgba(0,0,0,0.2);
  }
  .ft-cta .socials a:hover {
    color:#3a5ca8;
    text-decoration:underline;
  }
  .site-footer a:hover {
    text-decoration:underline;
  }
</style>
