<?php
/**
 * Script to test OpenCMS JSON API endpoints
 */

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

$baseUrl = getenv('OPENCMS_SERVER') ?: 'http://localhost';

$endpoints = [
    'API Root' => '/json',
    'Mercury Demo Site' => '/json/sites/default/mercury-demo',
    'Mercury Demo Content' => '/json/sites/default/mercury-demo/.content/article-m?content&wrapper&locale=en&fallbackLocale',
    'Mercury Local Site' => '/json/sites/mercury.local',
    'Mercury Local Content' => '/json/sites/mercury.local/.content/article-m?content&wrapper&locale=en&fallbackLocale',
    'Sites List' => '/json/sites',
];

echo "Testing OpenCMS JSON API Endpoints\n";
echo "Base URL: $baseUrl\n";
echo str_repeat("=", 80) . "\n\n";

foreach ($endpoints as $name => $path) {
    $url = $baseUrl . $path;
    echo "Testing: $name\n";
    echo "URL: $url\n";
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "❌ FAILED - Could not connect\n";
    } else {
        $data = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "✅ SUCCESS - Valid JSON response\n";
            if (isset($data['entries'])) {
                echo "   Found " . count($data['entries']) . " entries\n";
            }
            if (isset($data['path'])) {
                echo "   Path: " . $data['path'] . "\n";
            }
        } else {
            echo "⚠️  WARNING - Response received but not valid JSON\n";
            echo "   First 200 chars: " . substr($response, 0, 200) . "\n";
        }
    }
    echo "\n";
}

echo str_repeat("=", 80) . "\n";
echo "Test completed!\n";
