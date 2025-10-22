<?php
require __DIR__ . '/php/csrf.php';
$token = csrf_token();
include __DIR__ . '/partials/header.php';
?>
<section class="section">
  <div class="container">
    <h1>Booking</h1>
    <p>Fill in details and preferred package. We’ll confirm availability and price.</p>
     <form method="POST" action="<?php echo $basePath; ?>/php/handle_booking.php" class="card form-card">
      <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($token); ?>">
      <label>Full Name
        <input type="text" name="full_name" required style="width:100%; padding:10px; margin:6px 0;">
      </label>
      <label>Email
        <input type="email" name="email" required style="width:100%; padding:10px; margin:6px 0;">
      </label>
      <label>Phone
        <input type="tel" name="phone" required style="width:100%; padding:10px; margin:6px 0;">
      </label>
      <label>Package
        <input type="text" name="package_name" placeholder="e.g., Classic Europe (10D)" required style="width:100%; padding:10px; margin:6px 0;">
      </label>
      <label>Travel Date
        <input type="date" name="travel_date" required style="width:100%; padding:10px; margin:6px 0;">
      </label>
      <label>Adults
        <input type="number" name="adults" min="1" value="2" required style="width:100%; padding:10px; margin:6px 0;">
      </label>
      <label>Notes
        <textarea name="notes" rows="4" style="width:100%; padding:10px; margin:6px 0;"></textarea>
      </label>
      <button class="btn" type="submit">Submit Booking</button>
    </form>
  </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
