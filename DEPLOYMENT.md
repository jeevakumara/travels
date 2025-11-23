# 🚀 Production Deployment Guide

## Pre-Deployment Checklist

### 1. Configuration Updates

- [ ] **Update Base Path**
  ```php
  // partials/header.php
  $basePath = ''; // Change from '/travel-site' to '' for root deployment
  ```

- [ ] **Database Credentials**
  ```php
  // php/config.php
  $host = 'your-production-host';
  $db   = 'your-database-name';
  $user = 'your-db-username';
  $pass = 'your-secure-password';
  ```

- [ ] **Admin Secret Key**
  ```php
  // admin.php (line ~20)
  $secret = 'CHANGE-THIS-TO-STRONG-RANDOM-STRING';
  ```

### 2. Security Hardening

- [ ] **Disable Error Display**
  ```php
  // Add to php/config.php
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
  ini_set('error_log', __DIR__ . '/../logs/php-errors.log');
  ```

- [ ] **Create .htaccess** (if using Apache)
  ```apache
  # Prevent directory listing
  Options -Indexes
  
  # Protect sensitive files
  <FilesMatch "^\.">
    Order allow,deny
    Deny from all
  </FilesMatch>
  
  # Enable gzip compression
  <IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
  </IfModule>
  
  # Browser caching
  <IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
  </IfModule>
  
  # Force HTTPS
  RewriteEngine On
  RewriteCond %{HTTPS} off
  RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
  ```

- [ ] **Protect PHP Directory**
  ```apache
  # php/.htaccess
  Order deny,allow
  Deny from all
  <FilesMatch "\.(php)$">
    Allow from all
  </FilesMatch>
  ```

### 3. File Permissions

```bash
# Set correct permissions
chmod 755 /path/to/travel-site
chmod 644 /path/to/travel-site/*.php
chmod 755 /path/to/travel-site/php/uploads
chmod 755 /path/to/travel-site/logs
chmod 644 /path/to/travel-site/assets/css/*
chmod 644 /path/to/travel-site/assets/js/*
```

### 4. Database Optimization

- [ ] **Add Indexes**
  ```sql
  ALTER TABLE bookings ADD INDEX idx_created_at (created_at);
  ALTER TABLE bookings ADD INDEX idx_status (status);
  ALTER TABLE messages ADD INDEX idx_created_at (created_at);
  ALTER TABLE services ADD INDEX idx_is_active (is_active);
  ```

- [ ] **Set Up Backups**
  ```bash
  # Daily backup cron job
  0 2 * * * mysqldump -u username -p'password' travels > /backups/travels_$(date +\%Y\%m\%d).sql
  ```

### 5. Performance Optimization

- [ ] **Enable OPcache** (php.ini)
  ```ini
  opcache.enable=1
  opcache.memory_consumption=128
  opcache.max_accelerated_files=10000
  opcache.revalidate_freq=60
  ```

- [ ] **Optimize Images**
  ```bash
  # Use tools like ImageOptim, TinyPNG, or:
  jpegoptim --max=85 assets/img/*.jpg
  optipng -o7 assets/img/*.png
  ```

- [ ] **Minify CSS** (optional - already optimized)
  ```bash
  # If you want further minification:
  cssnano assets/css/site.css assets/css/site.min.css
  ```

### 6. SSL Certificate

- [ ] Install SSL certificate (Let's Encrypt recommended)
  ```bash
  # Using Certbot
  certbot --apache -d yourdomain.com -d www.yourdomain.com
  ```

- [ ] Update all URLs to HTTPS
- [ ] Test SSL configuration: https://www.ssllabs.com/ssltest/

### 7. Email Configuration

- [ ] **Set Up SMTP** for form notifications
  ```php
  // Add to handle_booking.php and handle_contact.php
  $to = 'nmdtravelss@gmail.com';
  $subject = 'New Booking from ' . $full_name;
  $message = "Booking Details:\n\n" . 
             "Name: $full_name\n" .
             "Email: $email\n" .
             "Phone: $phone\n" .
             "Package: $package_name\n" .
             "Date: $travel_date\n" .
             "Adults: $adults\n" .
             "Notes: $notes";
  $headers = "From: noreply@yourdomain.com\r\n" .
             "Reply-To: $email\r\n" .
             "X-Mailer: PHP/" . phpversion();
  
  mail($to, $subject, $message, $headers);
  ```

### 8. Testing

- [ ] **Test All Forms**
  - Booking form submission
  - Contact form submission
  - Admin login
  - Service management

- [ ] **Test Responsiveness**
  - Mobile (320px, 375px, 414px)
  - Tablet (768px, 1024px)
  - Desktop (1280px, 1920px)

- [ ] **Cross-Browser Testing**
  - Chrome
  - Firefox
  - Safari
  - Edge

- [ ] **Performance Testing**
  - Google PageSpeed Insights
  - GTmetrix
  - WebPageTest

- [ ] **Security Testing**
  - SQL injection attempts
  - XSS attempts
  - CSRF validation
  - Rate limiting

### 9. SEO Optimization

- [ ] **Add robots.txt**
  ```
  User-agent: *
  Allow: /
  Disallow: /php/
  Disallow: /admin.php
  Disallow: /admin_services.php
  
  Sitemap: https://yourdomain.com/sitemap.xml
  ```

- [ ] **Create sitemap.xml**
  ```xml
  <?xml version="1.0" encoding="UTF-8"?>
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
      <loc>https://yourdomain.com/</loc>
      <priority>1.0</priority>
    </url>
    <url>
      <loc>https://yourdomain.com/services.php</loc>
      <priority>0.8</priority>
    </url>
    <url>
      <loc>https://yourdomain.com/about.php</loc>
      <priority>0.7</priority>
    </url>
    <url>
      <loc>https://yourdomain.com/booking.php</loc>
      <priority>0.9</priority>
    </url>
    <url>
      <loc>https://yourdomain.com/contact.php</loc>
      <priority>0.8</priority>
    </url>
  </urlset>
  ```

- [ ] **Update Meta Tags** (per page)
  ```php
  <title>Specific Page Title - NMD Travels</title>
  <meta name="description" content="Page-specific description">
  <meta property="og:title" content="Page Title">
  <meta property="og:description" content="Description">
  <meta property="og:image" content="https://yourdomain.com/assets/img/og-image.jpg">
  ```

### 10. Monitoring & Analytics

- [ ] **Google Analytics**
  ```html
  <!-- Add before </head> in header.php -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'GA_MEASUREMENT_ID');
  </script>
  ```

- [ ] **Error Monitoring**
  - Set up error logging
  - Monitor logs regularly
  - Set up alerts for critical errors

- [ ] **Uptime Monitoring**
  - Use services like UptimeRobot or Pingdom
  - Set up email/SMS alerts

## Post-Deployment

### Immediate Actions
1. ✅ Test all pages and forms
2. ✅ Verify SSL certificate
3. ✅ Check mobile responsiveness
4. ✅ Test admin panel
5. ✅ Verify email notifications
6. ✅ Check Google Search Console
7. ✅ Submit sitemap to search engines

### First Week
- Monitor error logs daily
- Check form submissions
- Review analytics
- Test backup restoration
- Monitor server performance

### Ongoing Maintenance
- Weekly: Review bookings and messages
- Monthly: Update content and services
- Quarterly: Security audit
- Annually: Renew SSL certificate

## Rollback Plan

If issues occur:

1. **Database Rollback**
   ```bash
   mysql -u username -p travels < /backups/travels_backup.sql
   ```

2. **File Rollback**
   - Keep previous version in `/backup/` directory
   - Copy files back if needed

3. **Emergency Contact**
   - Have hosting provider support number ready
   - Document all changes made

## Performance Benchmarks

Target metrics:
- **Page Load Time**: < 2 seconds
- **Time to Interactive**: < 3 seconds
- **First Contentful Paint**: < 1.5 seconds
- **Lighthouse Score**: > 90
- **Mobile Friendly**: 100%

## Support Contacts

- **Hosting Provider**: [Provider name and support number]
- **Domain Registrar**: [Registrar name and support]
- **Developer**: [Your contact information]
- **Client**: nmdtravelss@gmail.com / +91 9940671829

---

**Last Updated**: October 2025
**Version**: 1.0.0
