<?php
/**
 * Image Proxy - Serves images from HTTP OpenCMS via HTTPS
 */

$url = $_GET['url'] ?? '';

if (empty($url)) {
    header('HTTP/1.1 400 Bad Request');
    exit('Missing URL parameter');
}

// Validate URL
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    header('HTTP/1.1 400 Bad Request');
    exit('Invalid URL');
}

// Only allow images from our OpenCMS server
$baseUrl = getenv('OPENCMS_SERVER') ?: 'http://172.30.0.5:8080';
if (strpos($url, $baseUrl) !== 0) {
    header('HTTP/1.1 403 Forbidden');
    exit('URL not allowed');
}

// Fetch the image
$imageData = @file_get_contents($url);

if ($imageData === false) {
    header('HTTP/1.1 404 Not Found');
    exit('Image not found');
}

// Detect content type from URL
$ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
$contentTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'webp' => 'image/webp',
];

$contentType = $contentTypes[strtolower($ext)] ?? 'application/octet-stream';

// Send the image
header('Content-Type: ' . $contentType);
header('Content-Length: ' . strlen($imageData));
header('Cache-Control: public, max-age=31536000'); // Cache for 1 year

echo $imageData;
