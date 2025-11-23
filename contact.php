<?php
require __DIR__ . '/php/csrf.php';
$token = csrf_token();
include __DIR__ . '/partials/header.php';
?>
<section class="section">
  <div class="container">
    <div class="text-center mb-8">
      <h1>Contact Us</h1>
      <p class="text-lead text-muted">Questions or custom requests? Send a message and we'll respond promptly.</p>
    </div>

    <div class="grid-2">
      <!-- Contact Form -->
      <div class="card">
        <h3 class="mb-6">Send us a message</h3>
        <form method="POST" action="<?php echo $basePath; ?>/php/handle_contact.php" data-validate>
          <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($token); ?>">

          <div class="form-field">
            <label class="form-label" for="name">Name</label>
            <input type="text" id="name" name="name" class="form-input" required>
          </div>

          <div class="form-field">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-input" placeholder="abc@gmail.com" required>
          </div>

          <div class="form-field">
            <label class="form-label" for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+91 9XXXXXXXXX" required>
          </div>

          <div class="form-field">
            <label class="form-label" for="message">Message</label>
            <textarea id="message" name="message" class="form-textarea" rows="5" required></textarea>
          </div>

          <button class="btn btn-lg btn-full" type="submit">Send Message</button>
        </form>
      </div>

      <!-- Contact Info -->
      <div>
        <div class="card mb-6">
          <h3 class="mb-4">Get in touch</h3>
          <div class="mb-4">
            <h4 class="text-sm font-semibold mb-2">Address</h4>
            <p class="text-muted text-sm">65/6, Chella Pillayar Koil St, Padupakkam, Royapettah, Chennai, Tamil Nadu 600014</p>
          </div>
          <div class="mb-4">
            <h4 class="text-sm font-semibold mb-2">Phone</h4>
            <p class="text-muted text-sm"><a href="tel:+919940671829">+91 9940671829</a></p>
          </div>
          <div class="mb-4">
            <h4 class="text-sm font-semibold mb-2">Email</h4>
            <p class="text-muted text-sm"><a href="mailto:nmdtravelss@gmail.com">nmdtravelss@gmail.com</a></p>
          </div>
          
          <div class="flex flex-wrap gap-3">
            <a class="btn outline btn-sm" href="tel:+919940671829">Call Now</a>
            <a class="btn outline btn-sm" href="mailto:nmdtravelss@gmail.com">Email Us</a>
            <a class="btn outline btn-sm" href="https://www.justdial.com/Chennai/Nmd-Travels-Near-Zam-Bazaar-Royapettah/044P1221473572G7U2J6_BZDET" target="_blank" rel="noopener">Justdial</a>
          </div>
        </div>

        <!-- Map -->
        <div class="card p-0" style="overflow: hidden;">
          <iframe
            title="NMD Travels Location"
            width="100%" height="320" style="border:0; display:block;"
            loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3891.651721971839!2d80.26805!3d13.06059!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a52677a24d0348f%3A0x5afb539a97d7b3c9!2sNMD%20Travels!5e0!3m2!1sen!2sin!4v1730399400">
          </iframe>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
