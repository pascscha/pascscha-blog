<?php
// Read the JSON file
$jsonFile = '../inventory.json';
$jsonData = file_get_contents($jsonFile);

// Decode JSON data into a PHP array
$items = json_decode($jsonData, true);

// Start the RSS feed output
header('Content-Type: application/rss+xml; charset=UTF-8');

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
<rss version="2.0">
  <channel>
    <title>Pascal Schärli</title>
    <link>https://pascscha.ch</link>
    <description>Hi, I'm Pascal. A cyber security master's graduate from ETH Zürich, now a dedicated Cryptography Engineer with a strong passion for coding and scripting.</description>
    <language>en-us</language>
    <lastBuildDate><?php echo date('r', time()); ?></lastBuildDate>

    <?php foreach ($items as $item): ?>
    <item>
      <title><?php echo htmlspecialchars($item['title']); ?></title>
      <link>https://pascscha.ch<?php echo htmlspecialchars(
          $item['link']
      ); ?></link>
      <description><?php echo htmlspecialchars(
          $item['description']
      ); ?></description>
      <pubDate><?php echo date('r', $item['timestamp']); ?></pubDate>
      <guid>https://pascscha.ch<?php echo htmlspecialchars(
          $item['link']
      ); ?></guid>
    </item>
    <?php endforeach; ?>

  </channel>
</rss>
