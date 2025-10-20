<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

use App\Api\ApiClient;

$apiClient = new ApiClient();

echo "<h1>Debug OpenCMS API</h1>";
echo "<hr>";

echo "<h2>Environment</h2>";
echo "<pre>";
echo "OPENCMS_SERVER: " . getenv('OPENCMS_SERVER') . "\n";
echo "</pre>";

echo "<h2>API Availability Check</h2>";
echo "<pre>";
$available = $apiClient->checkApiAvailability();
echo "Available: " . ($available ? 'YES' : 'NO') . "\n";
echo "</pre>";

echo "<h2>Load Content List (article-m, en)</h2>";
echo "<pre>";
$pages = $apiClient->loadContentList('article-m', 'en');
echo "Number of pages: " . count($pages) . "\n\n";

if (!empty($pages)) {
    foreach ($pages as $i => $page) {
        echo "Page $i:\n";
        echo "  Title: " . $page->title . "\n";
        echo "  Path: " . $page->path . "\n";
        echo "  Components: " . count($page->components) . "\n";
        echo "\n";
    }
} else {
    echo "No pages found!\n";
}
echo "</pre>";

echo "<h2>Raw API Response</h2>";
echo "<pre>";
$url = getenv('OPENCMS_SERVER') . '/json/sites/mercury.local/.content/article-m?content&wrapper&locale=en&fallbackLocale';
echo "URL: $url\n\n";
$json = @file_get_contents($url);
if ($json) {
    $data = json_decode($json, true);
    echo "JSON Response:\n";
    print_r($data);
} else {
    echo "Failed to fetch data!\n";
}
echo "</pre>";
