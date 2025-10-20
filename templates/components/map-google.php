<?php
/** @var array $localeContent */
/** @var array $settings */

// Get map data
$title = $localeContent['Title'] ?? 'Map';
$address = $localeContent['Address'] ?? '';
$coordinates = $localeContent['Coordinates'] ?? null;
$zoom = $settings['zoom'] ?? '15';

// If we have coordinates, use them; otherwise use address
$mapSrc = '';
if ($coordinates && isset($coordinates['lat']) && isset($coordinates['lng'])) {
    $lat = $coordinates['lat'];
    $lng = $coordinates['lng'];
    $mapSrc = "https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q={$lat},{$lng}&zoom={$zoom}";
} elseif ($address) {
    $encodedAddress = urlencode($address);
    $mapSrc = "https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q={$encodedAddress}&zoom={$zoom}";
}

// Fallback: use OpenStreetMap (no API key needed)
if ($coordinates && isset($coordinates['lat']) && isset($coordinates['lng'])) {
    $lat = $coordinates['lat'];
    $lng = $coordinates['lng'];
    $osmSrc = "https://www.openstreetmap.org/export/embed.html?bbox=" . ($lng - 0.01) . "%2C" . ($lat - 0.01) . "%2C" . ($lng + 0.01) . "%2C" . ($lat + 0.01) . "&layer=mapnik&marker={$lat}%2C{$lng}";
} else {
    // Default location (Bari, Italy from the screenshot)
    $osmSrc = "https://www.openstreetmap.org/export/embed.html?bbox=16.8%2C41.1%2C16.9%2C41.2&layer=mapnik&marker=41.125%2C16.866";
}
?>

<section class="max-w-6xl mx-auto mb-12">
    <?php if (!empty($title)): ?>
        <div class="bg-gray-100 dark:bg-gray-800 rounded-t-lg p-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                <?php echo htmlspecialchars($title); ?>
            </h3>
        </div>
    <?php endif; ?>
    
    <div class="relative w-full bg-gray-200 dark:bg-gray-700 rounded-b-lg overflow-hidden shadow-lg" style="height: 450px;">
        <iframe 
            src="<?php echo htmlspecialchars($osmSrc); ?>"
            class="absolute inset-0 w-full h-full border-0"
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
    
    <?php if ($address): ?>
        <div class="mt-4 text-center text-gray-600 dark:text-gray-400">
            <p><?php echo htmlspecialchars($address); ?></p>
        </div>
    <?php endif; ?>
</section>
