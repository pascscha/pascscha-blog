<?php
// Read the JSON file
$jsonFile = '../inventory.json';
$jsonData = file_get_contents($jsonFile);

// Decode JSON data into a PHP array
$posts = json_decode($jsonData, true);

// Start the XML output
header('Content-Type: application/xml; charset=UTF-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($posts as $post): ?>
    <url>
        <loc>https://pascscha.ch<?php echo htmlspecialchars(
            $post['link']
        ); ?></loc>
        <lastmod><?php echo date('Y-m-d', $post['timestamp']); ?></lastmod>
        <priority>0.80</priority>
    </url>
    <?php endforeach; ?>
</urlset>
