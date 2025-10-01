<?php
// Common security headers (adjust CSP as assets are added)
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: no-referrer-when-downgrade');
header('X-XSS-Protection: 0'); // modern browsers rely on CSP
header("Content-Security-Policy: default-src 'self' https://maps.google.com https://www.google.com; img-src 'self' data: https://maps.gstatic.com; style-src 'self' 'unsafe-inline'; script-src 'self'; frame-src https://maps.google.com https://www.google.com;");
