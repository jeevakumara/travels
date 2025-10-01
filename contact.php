<?php
require __DIR__ . '/php/csrf.php';
$token = csrf_token();
include __DIR__ . '/partials/header.php';
?>
<section class="section">
  <div class="container">
    <h1>Contact</h1>
    <p class="muted">Questions or custom requests? Send a message and we’ll respond promptly.</p>

    <div class="card">
      <form method="POST" action="<?php echo $basePath; ?>/php/handle_contact.php" class="form-card">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($token); ?>">

        <label>Name
          <input type="text" name="name" required>
        </label>

        <label>Email
          <input type="email" name="email" required>
        </label>

        <label>Phone
          <input type="tel" name="phone" placeholder="+91 9XXXXXXXXX">
        </label>

        <label>Message
          <textarea name="message" rows="5" required></textarea>
        </label>

        <button class="btn" type="submit">Send Message</button>
      </form>

      <div style="margin-top:16px;">
        <p><strong>Address:</strong> 65/6, Chella Pillayar Koil St, Padupakkam, Royapettah, Chennai, Tamil Nadu 600014</p>
        <p><strong>Phone:</strong> +91 99999 99999</p>
        <p class="muted" style="margin-top:6px;">Chennai landline/STD code is 044; mobiles use +91 followed by 10 digits.</p>

        <div style="margin-top:10px;">
          <!-- Public Google Maps embed (no API key needed) pointing to NMD Travels in Royapettah -->
          <iframe
            title="NMD Travels Location"
            width="100%" height="320" style="border:0; border-radius:12px;"
            loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3891.651721971839!2d80.26805!3d13.06059!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a52677a24d0348f%3A0x5afb539a97d7b3c9!2sNMD%20Travels!5e0!3m2!1sen!2sin!4v1730399400">
          </iframe>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:12px;">
          <a class="btn outline" href="https://www.justdial.com/Chennai/Nmd-Travels-Near-Zam-Bazaar-Royapettah/044P1221473572G7U2J6_BZDET" target="_blank" rel="noopener">Justdial Listing</a>
          <a class="btn outline" href="tel:+919999999999">Call Now</a>
          <a class="btn outline" href="mailto:info@nmdtravels.in">Email Us</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
