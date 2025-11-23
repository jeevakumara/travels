# 🧪 Comprehensive Testing Guide

## Manual Testing Checklist

### 1. Homepage (index.php)
- [ ] Page loads without errors
- [ ] Hero section displays correctly
- [ ] All images load properly
- [ ] "Explore Packages" button works
- [ ] Value proposition cards display in grid
- [ ] Services section shows 3 cards
- [ ] Reviews section displays customer testimonials
- [ ] All review avatars load
- [ ] Justdial link works
- [ ] "Book Now" CTA button works
- [ ] Footer displays correctly
- [ ] All navigation links work

### 2. Services Page (services.php)
- [ ] Page loads without errors
- [ ] Services load from database
- [ ] Service images display correctly
- [ ] Service cards show title, summary, price
- [ ] "Book now" buttons work on each card
- [ ] Grid layout is responsive
- [ ] Empty state shows if no services
- [ ] Currency and duration display correctly

### 3. Booking Page (booking.php)
- [ ] Page loads without errors
- [ ] Form displays in two-column layout (desktop)
- [ ] All form fields are present
- [ ] CSRF token is generated
- [ ] Required fields are marked
- [ ] Date picker works
- [ ] Number input for adults works
- [ ] Form validation works (try submitting empty)
- [ ] Email validation works (try invalid email)
- [ ] Phone field accepts input
- [ ] Notes textarea works
- [ ] Submit button is clickable
- [ ] Form submits successfully
- [ ] Redirects to thank-you page
- [ ] Data saves to database

### 4. Contact Page (contact.php)
- [ ] Page loads without errors
- [ ] Two-column layout (form + info)
- [ ] Contact form displays correctly
- [ ] CSRF token is generated
- [ ] All required fields marked
- [ ] Email validation works
- [ ] Phone field accepts input
- [ ] Message textarea works
- [ ] Submit button works
- [ ] Form submits successfully
- [ ] Redirects to thank-you page
- [ ] Data saves to database
- [ ] Contact info displays correctly
- [ ] Google Maps iframe loads
- [ ] Social links work
- [ ] Call/Email buttons work

### 5. About Page (about.php)
- [ ] Page loads without errors
- [ ] Hero section displays with gradient
- [ ] Story section shows two-column layout
- [ ] Content aligns with images
- [ ] Founder image loads
- [ ] Office image loads
- [ ] Image captions display
- [ ] Stats cards show (26+ Years, 50+ Vehicles, 10K+ Clients)
- [ ] Highlight box displays with checkmarks
- [ ] Services grid shows 6 items with icons
- [ ] Location card displays address, phone, email
- [ ] Social links display with icons
- [ ] Social links have hover effects
- [ ] LinkedIn link works
- [ ] Justdial link works
- [ ] Call Now button works
- [ ] CTA section displays with gradient
- [ ] Book Now and Contact Us buttons work

### 6. Thank You Page (thank-you.php)
- [ ] Page loads without errors
- [ ] Success icon displays
- [ ] Thank you message shows
- [ ] "Back to Home" button works

### 7. Admin Panel (admin.php)
- [ ] Requires secret key to access
- [ ] Dashboard displays statistics
- [ ] Bookings table shows data
- [ ] Messages table shows data
- [ ] Tables are responsive (scroll on mobile)
- [ ] Status badges display correctly
- [ ] Date formatting is correct
- [ ] Pagination works (if implemented)
- [ ] Can update booking status
- [ ] Logout/exit works

### 8. Admin Services (admin_services.php)
- [ ] Page loads without errors
- [ ] Services list displays
- [ ] Can add new service
- [ ] Can edit existing service
- [ ] Can delete service
- [ ] Image upload works
- [ ] Form validation works
- [ ] Changes save to database
- [ ] Active/inactive toggle works

---

## Responsive Testing

### Mobile (320px - 480px)
- [ ] Navigation collapses to hamburger menu
- [ ] Mobile menu opens/closes smoothly
- [ ] All text is readable
- [ ] Buttons are tap-friendly (44px min)
- [ ] Forms stack to single column
- [ ] Images scale properly
- [ ] No horizontal scroll
- [ ] Cards stack vertically
- [ ] Footer is readable

### Tablet (768px - 1024px)
- [ ] Navigation shows full menu
- [ ] Grids show 2 columns
- [ ] Forms maintain two-column layout
- [ ] Images scale appropriately
- [ ] Spacing looks good
- [ ] About page images side-by-side

### Desktop (1280px+)
- [ ] Full navigation visible
- [ ] Grids show 3-4 columns
- [ ] Hero section looks balanced
- [ ] Images are high quality
- [ ] Spacing is comfortable
- [ ] About page sticky images work

---

## Browser Testing

### Chrome
- [ ] All pages load correctly
- [ ] Forms work
- [ ] Animations smooth
- [ ] No console errors

### Firefox
- [ ] All pages load correctly
- [ ] Forms work
- [ ] Animations smooth
- [ ] No console errors

### Safari (Mac/iOS)
- [ ] All pages load correctly
- [ ] Forms work
- [ ] Date picker works
- [ ] Animations smooth
- [ ] No console errors

### Edge
- [ ] All pages load correctly
- [ ] Forms work
- [ ] Animations smooth
- [ ] No console errors

---

## Security Testing

### CSRF Protection
```bash
# Try submitting form without CSRF token
curl -X POST http://localhost:8000/php/handle_booking.php \
  -d "full_name=Test&email=test@test.com"
# Should return: Invalid request
```

### Rate Limiting
- [ ] Submit booking form
- [ ] Try submitting again immediately
- [ ] Should show: Please wait before submitting again

### SQL Injection
- [ ] Try entering `' OR '1'='1` in form fields
- [ ] Should be sanitized/escaped
- [ ] No database errors

### XSS Prevention
- [ ] Try entering `<script>alert('XSS')</script>` in forms
- [ ] Should be escaped when displayed
- [ ] No script execution

### File Upload (Admin)
- [ ] Try uploading .php file
- [ ] Should be rejected
- [ ] Only images allowed

---

## Performance Testing

### Page Load Speed
```bash
# Using curl to measure load time
curl -w "@curl-format.txt" -o /dev/null -s http://localhost:8000/

# Create curl-format.txt:
time_namelookup:  %{time_namelookup}\n
time_connect:  %{time_connect}\n
time_starttransfer:  %{time_starttransfer}\n
time_total:  %{time_total}\n
```

### Google PageSpeed Insights
1. Go to https://pagespeed.web.dev/
2. Enter your URL
3. Check scores for:
   - Performance (target: 90+)
   - Accessibility (target: 95+)
   - Best Practices (target: 90+)
   - SEO (target: 95+)

### Lighthouse Audit (Chrome DevTools)
1. Open Chrome DevTools (F12)
2. Go to Lighthouse tab
3. Run audit for:
   - Performance
   - Accessibility
   - Best Practices
   - SEO
4. Review recommendations

---

## Database Testing

### Connection Test
```php
// Create test-db.php
<?php
require __DIR__ . '/php/config.php';
try {
    $stmt = $pdo->query('SELECT 1');
    echo "✅ Database connection successful\n";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}
```

### Data Integrity
```sql
-- Check bookings table
SELECT COUNT(*) FROM bookings;
SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5;

-- Check messages table
SELECT COUNT(*) FROM messages;
SELECT * FROM messages ORDER BY created_at DESC LIMIT 5;

-- Check services table
SELECT COUNT(*) FROM services WHERE is_active = 1;
SELECT title, price FROM services;
```

### Query Performance
```sql
-- Explain query execution
EXPLAIN SELECT * FROM bookings WHERE status = 'new';
EXPLAIN SELECT * FROM services WHERE is_active = 1;

-- Check indexes
SHOW INDEX FROM bookings;
SHOW INDEX FROM messages;
SHOW INDEX FROM services;
```

---

## Accessibility Testing

### Keyboard Navigation
- [ ] Can tab through all links
- [ ] Can tab through all form fields
- [ ] Can submit forms with Enter
- [ ] Focus indicators visible
- [ ] Skip to content link works

### Screen Reader
- [ ] All images have alt text
- [ ] Form labels are associated
- [ ] Headings are hierarchical (H1, H2, H3)
- [ ] ARIA labels present where needed
- [ ] Buttons have descriptive text

### Color Contrast
- [ ] Text meets WCAG AA standards (4.5:1)
- [ ] Links are distinguishable
- [ ] Buttons have sufficient contrast
- [ ] Error messages are readable

### Tools
- [ ] WAVE (https://wave.webaim.org/)
- [ ] axe DevTools (Chrome extension)
- [ ] Lighthouse Accessibility audit

---

## Error Handling Testing

### 404 Page
- [ ] Visit non-existent page
- [ ] 404 page displays
- [ ] "Go to Homepage" button works

### Form Errors
- [ ] Submit empty booking form → validation errors
- [ ] Submit invalid email → error message
- [ ] Submit past date → error message
- [ ] Submit with 0 adults → error message

### Database Errors
- [ ] Stop MySQL service
- [ ] Try loading services page
- [ ] Should show error message (not expose DB details)

---

## Load Testing (Optional)

### Apache Bench
```bash
# Test 100 requests, 10 concurrent
ab -n 100 -c 10 http://localhost:8000/

# Check results for:
# - Requests per second
# - Time per request
# - Failed requests (should be 0)
```

### Stress Test
```bash
# Test 1000 requests, 50 concurrent
ab -n 1000 -c 50 http://localhost:8000/services.php
```

---

## Final Checklist

### Before Going Live
- [ ] All manual tests pass
- [ ] All responsive tests pass
- [ ] All browser tests pass
- [ ] All security tests pass
- [ ] Performance scores > 90
- [ ] Accessibility score > 95
- [ ] No console errors
- [ ] No PHP errors in logs
- [ ] Database optimized
- [ ] Backups configured
- [ ] SSL certificate installed
- [ ] Analytics configured
- [ ] Sitemap submitted
- [ ] robots.txt configured

### Post-Launch
- [ ] Monitor error logs for 24 hours
- [ ] Check form submissions work
- [ ] Verify email notifications
- [ ] Test from different devices
- [ ] Test from different locations
- [ ] Monitor server resources
- [ ] Check analytics tracking

---

## Bug Reporting Template

When you find a bug, document it:

**Bug Title**: [Short description]

**Severity**: Critical / High / Medium / Low

**Steps to Reproduce**:
1. Go to [page]
2. Click on [element]
3. See error

**Expected Behavior**: [What should happen]

**Actual Behavior**: [What actually happens]

**Browser**: Chrome 120 / Firefox 121 / Safari 17

**Device**: Desktop / Mobile / Tablet

**Screenshot**: [Attach if applicable]

**Error Message**: [Copy exact error]

---

**Testing Completed By**: _________________

**Date**: _________________

**All Tests Passed**: ☐ Yes ☐ No

**Notes**: 
_______________________________________________________
