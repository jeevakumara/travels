<?php
require __DIR__ . '/php/csrf.php';
$token = csrf_token();
include __DIR__ . '/partials/header.php';
?>
<section class="section">
  <div class="container container-narrow">
    <div class="text-center mb-8">
      <h1>Book Your Ride</h1>
      <p class="text-lead text-muted">Fill in details and preferred package. We'll confirm availability and price.</p>
    </div>
    
    <div class="card">
      <form method="POST" action="<?php echo $basePath; ?>/php/handle_booking.php" data-validate>
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($token); ?>">
        
        <div class="form-row">
          <div class="form-field">
            <label class="form-label" for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" class="form-input" required>
          </div>
          
          <div class="form-field">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-input" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-field">
            <label class="form-label" for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" class="form-input" required>
          </div>
          
          <div class="form-field">
            <label class="form-label" for="package_name">Package</label>
            <input type="text" id="package_name" name="package_name" class="form-input" placeholder="e.g., Classic Europe (10D)" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-field">
            <label class="form-label" for="travel_date">Travel Date</label>
            <input type="date" id="travel_date" name="travel_date" class="form-input" required>
          </div>
          
          <div class="form-field">
            <label class="form-label" for="adults">Number of Adults</label>
            <input type="number" id="adults" name="adults" class="form-input" min="1" value="2" required>
          </div>
        </div>
        
        <div class="form-field">
          <label class="form-label" for="notes">Additional Notes</label>
          <textarea id="notes" name="notes" class="form-textarea" rows="4" placeholder="Any special requirements or questions?"></textarea>
          <span class="form-helper">Optional: Let us know if you have any special requirements</span>
        </div>
        
        <button class="btn btn-lg btn-full" type="submit">Submit Booking Request</button>
      </form>
    </div>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
