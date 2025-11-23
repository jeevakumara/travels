# 🚀 PRODUCTION DEPLOYMENT CHECKLIST

## ✅ PRE-DEPLOYMENT (Complete Before Going Live)

### 1. Configuration Files
- [ ] Copy `php/config.production.php` to `php/config.php`
- [ ] Update database credentials in `php/config.php`
- [ ] Set strong database password
- [ ] Update `$basePath` in `partials/header.php` (set to '' for root domain)
- [ ] Change admin secret key in `admin.php` (line ~20)

### 2. Database Setup
- [ ] Import `sql/schema.sql` into production database
- [ ] Create dedicated database user (not root)
- [ ] Grant only necessary privileges (SELECT, INSERT, UPDATE, DELETE)
- [ ] Test database connection
- [ ] Verify all tables created successfully
- [ ] Add sample services data (or real data)

### 3. File Permissions
```bash
chmod 755 /path/to/travel-site
chmod 644 /path/to/travel-site/*.php
chmod 755 /path/to/travel-site/php/uploads
chmod 755 /path/to/travel-site/logs
chmod 644 /path/to/travel-site/assets/css/*
chmod 644 /path/to/travel-site/assets/js/*
```

### 4. Security
- [ ] Enable HTTPS/SSL certificate
- [ ] Update `.htaccess` - uncomment HTTPS redirect
- [ ] Verify security headers are active
- [ ] Test CSRF protection on forms
- [ ] Test rate limiting (submit form multiple times)
- [ ] Disable PHP error display (check `php/config.php`)
- [ ] Enable error logging to file
- [ ] Remove or protect `.git` directory
- [ ] Verify sensitive files are blocked (.htaccess)

### 5. SEO & Analytics
- [ ] Update `sitemap.xml` with your domain
- [ ] Update `robots.txt` with your domain
- [ ] Add Google Analytics code to `partials/header.php`
- [ ] Add meta tags for social sharing (Open Graph)
- [ ] Submit sitemap to Google Search Console
- [ ] Submit sitemap to Bing Webmaster Tools
- [ ] Verify structured data (Schema.org)

### 6. Performance
- [ ] Enable gzip compression (verify `.htaccess`)
- [ ] Enable browser caching (verify `.htaccess`)
- [ ] Optimize all images (compress, convert to WebP)
- [ ] Test page load speed (Google PageSpeed Insights)
- [ ] Enable OPcache in PHP
- [ ] Minify CSS/JS (optional - already optimized)

### 7. Content Updates
- [ ] Update all "yourdomain.com" references
- [ ] Verify contact information (phone, email, address)
- [ ] Update social media links
- [ ] Add real service images
- [ ] Update service descriptions and pricing
- [ ] Add real customer reviews
- [ ] Update copyright year in footer

### 8. Testing
- [ ] Test all navigation links
- [ ] Test booking form submission
- [ ] Test contact form submission
- [ ] Verify email notifications work
- [ ] Test admin panel access
- [ ] Test service management (add/edit/delete)
- [ ] Test on mobile devices (iOS, Android)
- [ ] Test on different browsers (Chrome, Firefox, Safari, Edge)
- [ ] Test 404 error page
- [ ] Verify Google Maps loads correctly

### 9. Responsive Design
- [ ] Test at 320px (iPhone SE)
- [ ] Test at 375px (iPhone X)
- [ ] Test at 414px (iPhone Plus)
- [ ] Test at 768px (iPad)
- [ ] Test at 1024px (iPad Pro)
- [ ] Test at 1280px (Desktop)
- [ ] Test at 1920px (Large Desktop)

### 10. Backup & Recovery
- [ ] Set up automated database backups
- [ ] Set up file backups
- [ ] Test backup restoration process
- [ ] Document backup locations
- [ ] Create rollback plan

---

## 🔧 POST-DEPLOYMENT (After Going Live)

### Immediate Actions (Day 1)
- [ ] Verify site is accessible via domain
- [ ] Test all forms in production
- [ ] Check error logs for any issues
- [ ] Monitor server resources
- [ ] Test email notifications
- [ ] Verify SSL certificate is valid
- [ ] Check mobile responsiveness

### First Week
- [ ] Monitor error logs daily
- [ ] Check form submissions
- [ ] Review analytics data
- [ ] Test backup system
- [ ] Monitor page load times
- [ ] Check for broken links
- [ ] Review security logs

### Ongoing Maintenance
- [ ] Weekly: Review bookings and messages
- [ ] Weekly: Check error logs
- [ ] Monthly: Update content and services
- [ ] Monthly: Review analytics
- [ ] Quarterly: Security audit
- [ ] Quarterly: Performance optimization
- [ ] Annually: Renew SSL certificate
- [ ] Annually: Update dependencies

---

## 📊 PERFORMANCE TARGETS

### Speed
- [ ] Page Load Time: < 2 seconds
- [ ] Time to Interactive: < 3 seconds
- [ ] First Contentful Paint: < 1.5 seconds

### SEO
- [ ] Google PageSpeed Score: > 90
- [ ] Mobile Friendly: 100%
- [ ] Lighthouse Performance: > 90
- [ ] Lighthouse Accessibility: > 95
- [ ] Lighthouse Best Practices: > 90
- [ ] Lighthouse SEO: > 95

### Security
- [ ] SSL Labs Rating: A or A+
- [ ] Security Headers: All green
- [ ] No XSS vulnerabilities
- [ ] No SQL injection vulnerabilities
- [ ] CSRF protection active
- [ ] Rate limiting functional

---

## 🆘 TROUBLESHOOTING

### Database Connection Fails
1. Verify credentials in `php/config.php`
2. Check if database exists
3. Verify user has correct privileges
4. Check MySQL service is running

### Forms Not Submitting
1. Check error logs: `logs/php-errors.log`
2. Verify CSRF tokens are generated
3. Check rate limiting isn't blocking
4. Verify database tables exist

### Images Not Loading
1. Check file permissions on `php/uploads/`
2. Verify image paths in database
3. Check `.htaccess` isn't blocking
4. Verify files exist on server

### 500 Internal Server Error
1. Check `.htaccess` syntax
2. Review PHP error logs
3. Verify file permissions
4. Check PHP version compatibility (7.4+)

---

## 📞 SUPPORT CONTACTS

- **Hosting Provider**: [Provider name and support number]
- **Domain Registrar**: [Registrar name and support]
- **SSL Certificate**: [Certificate provider]
- **Developer**: [Your contact information]
- **Client**: nmdtravelss@gmail.com / +91 9940671829

---

## 🎯 FINAL VERIFICATION

Before announcing the site is live:

- [ ] All checklist items above are completed
- [ ] Site loads correctly on your domain
- [ ] All forms work and send notifications
- [ ] Admin panel is accessible and functional
- [ ] Mobile version looks perfect
- [ ] No console errors in browser
- [ ] No PHP errors in logs
- [ ] SSL certificate is valid and active
- [ ] Analytics tracking is working
- [ ] Backup system is configured

---

**Deployment Date**: _________________

**Deployed By**: _________________

**Verified By**: _________________

**Notes**: 
_______________________________________________________
_______________________________________________________
_______________________________________________________
