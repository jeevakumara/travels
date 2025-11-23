# NMD Travels - Production-Ready Website

A fully responsive, professional-grade travel booking website built with PHP, MySQL, and vanilla JavaScript.

## 🚀 Features

### Frontend
- ✅ **Fully Responsive** - Mobile-first design with breakpoints at 480px, 768px, 1024px, 1280px
- ✅ **Modern UI/UX** - Clean, professional design with smooth animations and transitions
- ✅ **Accessible** - WCAG AA compliant with proper ARIA labels, keyboard navigation, and focus states
- ✅ **Performance Optimized** - Lazy loading, optimized images, minimal JavaScript
- ✅ **Subtle Background** - Elegant bus image overlay with 3% opacity for brand identity
- ✅ **Interactive Elements** - Ripple effects, hover animations, smooth scrolling
- ✅ **Flash Messages** - Auto-dismissing notifications with animations

### Backend
- ✅ **Secure** - CSRF protection, prepared statements, input validation, rate limiting
- ✅ **Optimized** - Efficient database queries with PDO
- ✅ **Error Handling** - Comprehensive error logging and user-friendly messages
- ✅ **Helper Functions** - Reusable utilities for common tasks

### Pages
- **Homepage** - Hero section, value propositions, services, reviews, CTAs
- **Services** - Dynamic service cards with images and pricing
- **Booking** - Responsive two-column form with validation
- **Contact** - Form + contact info + Google Maps integration
- **About** - Company story with founder images
- **Admin Panel** - Dashboard, bookings, messages, services management
- **Thank You** - Success confirmation page

## 📁 Project Structure

```
travel-site/
├── assets/
│   ├── css/
│   │   └── site.css          # Main stylesheet (production-ready)
│   ├── js/
│   │   └── site.js           # Vanilla JavaScript (no frameworks)
│   └── img/                  # Images and assets
├── partials/
│   ├── header.php            # Responsive header with mobile menu
│   └── footer.php            # Structured footer with social links
├── php/
│   ├── config.php            # Database configuration
│   ├── csrf.php              # CSRF token generation/validation
│   ├── rate_limit.php        # Rate limiting for forms
│   ├── security_headers.php  # HTTP security headers
│   ├── helpers.php           # Utility functions
│   ├── handle_booking.php    # Booking form handler
│   ├── handle_contact.php    # Contact form handler
│   └── uploads/              # Service images directory
├── index.php                 # Homepage
├── services.php              # Services listing
├── booking.php               # Booking form
├── contact.php               # Contact page
├── about.php                 # About page
├── admin.php                 # Admin dashboard
├── admin_services.php        # Service management
└── thank-you.php             # Success page
```

## 🎨 Design System

### Colors
- **Primary**: #0EA5E9 (Sky Blue)
- **Accent**: #22C55E (Green)
- **Text**: #0F172A / #334155
- **Background**: #F8FAFC
- **Surface**: #FFFFFF

### Typography
- **Font Stack**: System UI (optimized for performance)
- **Sizes**: Responsive scale from 0.75rem to 3rem
- **Weights**: 400 (regular), 600 (semibold), 700 (bold), 800 (extrabold)

### Spacing
- **Scale**: 0.25rem to 5rem (4px to 80px)
- **Consistent**: All spacing uses CSS variables

### Components
- Buttons (primary, outline, ghost, accent)
- Cards (standard, interactive)
- Forms (labels, inputs, validation)
- Badges & Chips
- Tables (responsive with horizontal scroll)
- Reviews & Testimonials

## 🔧 Setup Instructions

### Prerequisites
- XAMPP (or any PHP 7.4+ environment)
- MySQL 5.7+
- Modern web browser

### Installation

1. **Clone/Copy** the project to your XAMPP htdocs directory:
   ```
   C:\xampp\htdocs\travel-site\
   ```

2. **Database Setup**:
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create database: `travels`
   - Import the SQL schema (create tables for bookings, messages, services)

3. **Configuration**:
   - Update `php/config.php` if needed (default uses localhost/root/no password)
   - Set correct `$basePath` in `partials/header.php` (default: `/travel-site`)

4. **Start Server**:
   ```bash
   # Using PHP built-in server
   cd C:\xampp\htdocs\travel-site
   C:\xampp\php\php.exe -S localhost:8000
   ```
   
   Or use XAMPP Apache:
   ```
   http://localhost/travel-site
   ```

5. **Access**:
   - Frontend: `http://localhost:8000` or `http://localhost/travel-site`
   - Admin: `http://localhost:8000/admin.php?key=YOUR_SECRET_KEY`

## 📱 Responsive Breakpoints

- **Mobile**: < 480px (single column, stacked layout)
- **Small**: 480px - 767px (2-column grids)
- **Tablet**: 768px - 1023px (2-3 column grids)
- **Desktop**: 1024px - 1279px (3-4 column grids)
- **Large**: ≥ 1280px (full layout)

## ⚡ Performance Features

- **Lazy Loading**: Images load only when visible
- **Async Decoding**: Non-blocking image rendering
- **Optimized CSS**: Single file, minified selectors
- **Minimal JS**: Vanilla JavaScript, no frameworks
- **Efficient Queries**: Prepared statements, indexed columns
- **Caching Headers**: Browser caching for static assets
- **Reduced Motion**: Respects user preferences

## 🔒 Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **SQL Injection Prevention**: Prepared statements only
- **XSS Protection**: Output escaping with htmlspecialchars()
- **Rate Limiting**: Prevents form spam
- **Input Validation**: Server-side validation for all inputs
- **Security Headers**: X-Frame-Options, X-Content-Type-Options, etc.

## 🎯 Browser Support

- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Mobile browsers (iOS Safari, Chrome Mobile)

## 📊 Admin Panel

Access: `admin.php?key=YOUR_SECRET_KEY`

Features:
- Dashboard with statistics
- Booking management (view, update status)
- Message inbox
- Service management (add, edit, delete, upload images)
- Analytics charts
- Responsive tables with horizontal scroll

## 🚀 Deployment Checklist

Before going live:

1. ✅ Update `$basePath` in `partials/header.php`
2. ✅ Change database credentials in `php/config.php`
3. ✅ Set strong admin secret key
4. ✅ Enable error logging (disable display_errors)
5. ✅ Set up SSL certificate (HTTPS)
6. ✅ Configure email for form notifications
7. ✅ Optimize images (compress, WebP format)
8. ✅ Enable gzip compression
9. ✅ Set up database backups
10. ✅ Test all forms and validation

## 🎨 Customization

### Change Colors
Edit CSS variables in `assets/css/site.css`:
```css
:root {
  --color-primary: #0EA5E9;  /* Your brand color */
  --color-accent: #22C55E;   /* Secondary color */
}
```

### Modify Background Opacity
In `assets/css/site.css`, find:
```css
body::before {
  opacity: 0.03;  /* Adjust 0.01 to 0.1 */
}
```

### Update Content
- Edit PHP files directly
- Images go in `assets/img/`
- Service images upload via admin panel

## 📝 License

Proprietary - NMD Travels © 2025

## 🆘 Support

For issues or questions:
- Email: nmdtravelss@gmail.com
- Phone: +91 9940671829

---

**Built with ❤️ for NMD Travels - Chennai's Trusted Travel Partner Since 1999**
