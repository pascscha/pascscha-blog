<!DOCTYPE html>
<html lang="en">

<?php include '../../php/set-http-headers.php'; ?>

<?php
// Get the current URL path
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Read and parse the JSON inventory
$inventory = json_decode(file_get_contents('../inventory.json'), true);

// Find the matching entry
$previous_entry = null;
$matching_entry = null;
$next_entry = null;
foreach ($inventory as $entry) {
    if ($matching_entry) {
        $next_entry = $entry;
        break;
    } elseif ($entry['link'] === $current_path) {
        $matching_entry = $entry;
    } else {
        $previous_entry = $entry;
    }
}

// If a matching entry is found, update the meta tags
if ($matching_entry) {
    $title = htmlspecialchars($matching_entry['title']);
    $description = htmlspecialchars($matching_entry['description']);
    $timestamp = $matching_entry['timestamp'];
} else {
    $title = 'Pascal Schärli';
    $description =
        "Pascal Schärli's latest research and projects on cryptography and cyber security.";
    $timestamp = 1606863632;
}

if ($previous_entry) {
    $previous_title = htmlspecialchars('← ' . $previous_entry['title']);
    $previous_link = htmlspecialchars($previous_entry['link']);
} else {
    $previous_title = '';
    $previous_link = '#';
}

if ($next_entry) {
    $next_title = htmlspecialchars($next_entry['title'] . ' →');
    $next_link = htmlspecialchars($next_entry['link']);
} else {
    $next_title = '';
    $next_link = '#';
}

// Update the title tag
echo "<title>{$title}</title>\n";

// Update the description meta tag
echo "<meta name=\"description\" content=\"{$description}\">\n";

// Update Open Graph title and description
echo "<meta property=\"og:title\" content=\"{$title}\">\n";
echo "<meta property=\"og:description\" content=\"{$description}\">\n";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo $title; ?>
    </title>
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="Pascal Schärli, cyber security, cryptography, encryption, research, ETH Zürich">

    <!-- Icons -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/img/favicon/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="64x64" href="/img/favicon/favicon-64x64.png">
    <link rel="icon" type="image/png" sizes="128x128" href="/img/favicon/favicon-128x128.png">
    <link rel="icon" type="image/png" sizes="152x152" href="/img/favicon/favicon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/favicon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/img/favicon/favicon-192x192.png">
    <link rel="icon" type="image/png" sizes="1024x1024" href="/img/favicon/favicon-1024x1024.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- SEO -->
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:description" content="<?php echo $description; ?>">
    <meta property="og:image" content="https://pascscha.ch<?php echo $current_path; ?>img/banner.webp">
    <meta property="og:url" content="https://pascscha.ch">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" rel="preload" as="style" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.10.0/styles/atom-one-dark.min.css"
        integrity="sha512-Jk4AqjWsdSzSWCSuQTfYRIF84Rq/eV0G2+tu07byYwHcbTGfdmLrHjUSwvzp5HvbiqK4ibmNwdcG49Y5RGYPTg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" rel="preload" as="style" />
    <link rel="stylesheet" type="text/css" href="/css/main.css">
</head>

<body>
    <header>
        <nav><a href="/" class="home-link" title="Home"><span class="site-title home-link">Pascal Schärli</span></a><a
                href="/" class="home-link" title="Home"><i class="fas fa-home"></i></a></nav>
    </header>
    <div id="content-wrapper">

        <div id="sidebar"><button id="toc-toggle">☰</button>
            <div id="toc" class="collapsed"></div>
        </div>

        <div id="content">
            <div class="banner" style="background-image: url('img/banner.webp')"></div>
            <div id="post-info"><span id="post-date">
                    <?php echo date('Y-m-d', $timestamp); ?>
                </span><span id="read-time"></span></div>
            <div id="rendered-content"><noscript>It's okay if you prefer not to enable JavaScript. You can find a view
                    this post in markdown at<a href="index.md">index.md</a>.</noscript></div>
            <div id="post-navigation">
                <a id="prev-post" class="nav-link" href="<?php echo $previous_link; ?>">
                    <?php echo $previous_title; ?>
                </a>
                <a id="next-post" class="nav-link" href="<?php echo $next_link; ?>">
                    <?php echo $next_title; ?>
                </a>
            </div>
        </div>

    </div>
    <footer>
        <div id="social-links">
            <a href="mailto:mail@pascscha.ch" class="social-link" title="Email">
                <i class="fas fa-envelope"></i>
            </a>
            <a href="https://github.com/pascscha" class="social-link" title="GitHub">
                <i class="fab fa-github"></i>
            </a>
            <a href="https://www.linkedin.com/in/pascscha" class="social-link" title="LinkedIn">
                <i class="fab fa-linkedin"></i>
            </a>
            <a href="https://mastodon.social/@pascscha" class="social-link" title="Mastodon">
                <i class="fab fa-mastodon"></i>
            </a>
            <a href="https://twitter.com/pascscha" class="social-link" title="Twitter">
                <i class="fab fa-twitter"></i>
            </a>
        </div>
        <div id="footer-links">
            <a href="/privacy" class="footer-link">Privacy Policy</a>
        </div>
    </footer>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js"
    integrity="sha512-LhccdVNGe2QMEfI3x4DVV3ckMRe36TfydKss6mJpdHjNFiV07dFpS2xzeZedptKZrwxfICJpez09iNioiSZ3hA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.10.0/highlight.min.js"
    integrity="sha512-6yoqbrcLAHDWAdQmiRlHG4+m0g/CT/V9AGyxabG8j7Jk8j3r3K6due7oqpiRMZqcYe9WM2gPcaNNxnl2ux+3tA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/js/blog/blog.js"></script>

</html>