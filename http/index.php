<!DOCTYPE html>
<html lang="en">

<!-- Welcome to the source code of my website! -->

<?php include 'php/set-http-headers.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pascal Schärli</title>
    <meta name="description"
        content="Pascal Schärli's latest research and projects on cryptography and cyber security.">
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
    <meta property="og:title" content="Pascal Schärli - Cryptography Engineer">
    <meta property="og:description"
        content="Pascal Schärli's latest research and projects on cryptography and cyber security.">
    <meta property="og:image" content="https://pascscha.ch/img/social-preview.webp">
    <meta property="og:url" content="https://pascscha.ch">
    <meta name="twitter:card" content="summary_large_image">

    <script src="/js/banner/MilitaryGradeEncryptor.js" nonce="<?php echo $script_nonce; ?>"></script>
    <script src="/js/banner/paddingOracleDemo.js" nonce="<?php echo $script_nonce; ?>">></script>
    <script src="/js/banner/animationHelpers.js" nonce="<?php echo $script_nonce; ?>">></script>

    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <div id="banner">
        <!-- This is a demo for a padding oracle attack on AES in CBC mode. Have a look at js/banner/MilitaryGradeEncryptor.js to which I've intentionally added a padding oracle, which is then used by js/banner/paddingOracleDemo.js to retrieve the plaintext letter-by-letter, which, in this case, is "Pascal Schärli" -->
        <h1 id="banner-title">&nbsp;</h1>
        <div id="banner-diagram"></div>

        <div id="info-circle">
            ?
            <div id="info-tooltip">This is a demo for a padding oracle attack on AES in CBC mode. Click here for more
                information.</div>
        </div>
    </div>

    <div id="content">
        <div id="about-me">
            <img src="/img/pascscha.webp" alt="Pascal Schärli" id="profile-image">
            <p>
                Hi, I'm Pascal. A cyber security master's graduate from ETH Zürich, now a dedicated Cryptography
                Engineer with a strong passion for coding and scripting.
            </p>
        </div>

        <div id="blog-posts">
            <h2>
                My Projects
                <a href="/blog/rss" class="rss-link" title="RSS Feed">
                    <i class="fas fa-rss"></i>
                </a>
            </h2>

            <div class="post-list">
                <?php
                // Read the JSON file
                $jsonFile = 'blog/inventory.json';
                $jsonData = file_get_contents($jsonFile);

                // Decode JSON data into a PHP array
                $posts = json_decode($jsonData, true);

                // Reverse the array to show the most recent posts first
                $posts = array_reverse($posts);
                ?>
                <?php foreach ($posts as $post): ?>
                <article class="blog-post">
                    <div class="post-image">
                        <img src="<?php echo htmlspecialchars(
                            $post['link']
                        ); ?>img/thumbnail.webp" alt="Thumbnail for <?php echo htmlspecialchars(
    $post['title']
); ?>">
                    </div>
                    <div class="post-content">
                        <div class="post-date">
                            <?php echo date('Y-m-d', $post['timestamp']); ?>
                        </div>
                        <h3 class="post-title">
                            <a href="<?php echo htmlspecialchars(
                                $post['link']
                            ); ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <p class="post-summary">
                            <?php echo htmlspecialchars(
                                $post['description']
                            ); ?>
                        </p>
                    </div>
                </article>
                <?php endforeach; ?>

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

<script nonce="<?php echo $script_nonce; ?>">

        window.addEventListener('DOMContentLoaded', (event) => {
            // Add click event to the info circle
            document.getElementById('info-circle').addEventListener('click', function () {
                window.location.href = 'blog/3-breaking-military-grade-encryption-to-animate-my-name/';
            });

            // Animate Padding oracle
            getDiagramElements('banner-diagram').then(
                (diagramElements) => {
                    // Banner Title
                    diagramElements["T"] = document.getElementById("banner-title")

                    // Can be any string with < 15 utf-8 encoded bytes
                    let encryptor = new MilitaryGradeEncryptor("Pascal Schärli");

                    encryptor.getEncryptedSecret().then((ciphertext) => {
                        decryptBlockWithPaddingOracle(ciphertext, encryptor, diagramElements, 100);
                    });

                }
            )
        })
</script>


<!-- SEO -->
<script type="application/ld+json" nonce="<?php echo $script_nonce; ?>">
    {
      "@context": "https://schema.org",
      "@type": "Person",
      "name": "Pascal Schärli",
      "url": "https://pascscha.ch",
      "jobTitle": "Cryptography Engineer",
      "alumniOf": {
        "@type": "CollegeOrUniversity",
        "name": "ETH Zürich"
      },
      "sameAs": [
        "https://linkedin.com/in/pascscha",
        "https://github.com/pascscha",
        "https://stackoverflow.com/users/10046273/pascscha"
      ]
    }
    </script>

</html>