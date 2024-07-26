<?php
$script_nonce = base64_encode(random_bytes(16));

// Content Security Policy
header(
    "Content-Security-Policy: default-src 'none'; script-src 'self' 'nonce-$script_nonce' https://cdnjs.cloudflare.com/ajax/libs/highlight.js/ https://cdnjs.cloudflare.com/ajax/libs/showdown/ ; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; style-src-elem 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; img-src 'self' data: https://pascscha.ch; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; connect-src 'self'; frame-src 'none'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; upgrade-insecure-requests; manifest-src 'self';"
);

// X-Content-Type-Options
header('X-Content-Type-Options: nosniff');

// Referrer-Policy
header('Referrer-Policy: strict-origin-when-cross-origin');

?>
