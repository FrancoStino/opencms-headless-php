<?php
/** @var array $localeContent */
/** @var array $settings */

$imageFirst = ($settings['pieceLayout'] ?? '6') === '6';
$image = $localeContent['Image'] ?? null;

// Fix image URL
$baseUrl = getenv('OPENCMS_SERVER') ?: 'http://172.30.0.5:8080';

// Check for nested Image structure
$imageLink = null;
if ($image) {
    if (isset($image['Image']['link'])) {
        $imageLink = $image['Image']['link'];
    } elseif (isset($image['link'])) {
        $imageLink = $image['link'];
    }
}

if ($imageLink) {
    $imageUrl = $imageLink;
    
    // Replace mercury.local with correct server
    $imageUrl = str_replace('http://mercury.local', $baseUrl, $imageUrl);
    $imageUrl = str_replace('https://mercury.local', $baseUrl, $imageUrl);
    
    // Fix escaped slashes
    $imageUrl = str_replace('\\/', '/', $imageUrl);
    
    // Also handle relative URLs
    if (strpos($imageUrl, 'http') !== 0) {
        $imageUrl = $baseUrl . $imageUrl;
    }
    
    // Force HTTP
    $imageUrl = str_replace('https://', 'http://', $imageUrl);
    
    // Use image proxy to avoid mixed content issues
    $imageUrl = '/image-proxy.php?url=' . urlencode($imageUrl);
} else {
    $imageUrl = null;
}
?>

<section class="max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <?php if ($imageFirst && $imageUrl): ?>
            <div class="relative h-64 lg:h-80 flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-lg">
                <img src="<?php echo htmlspecialchars($imageUrl); ?>" 
                     alt="<?php echo htmlspecialchars($localeContent['Title'] ?? ''); ?>"
                     class="max-w-full max-h-full object-contain <?php echo strpos($imageUrl, '.svg') !== false ? 'svg-icon-red' : ''; ?>">
            </div>
        <?php endif; ?>
        
        <div class="p-8">
            <?php if (!empty($localeContent['Title'])): ?>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    <?php echo htmlspecialchars($localeContent['Title']); ?>
                </h2>
            <?php endif; ?>
            
            <?php if (!empty($localeContent['Text'])): ?>
                <div class="prose dark:prose-invert mb-6">
                    <?php echo $localeContent['Text']; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($localeContent['Link']['link'])): ?>
                <a href="<?php echo htmlspecialchars($localeContent['Link']['link']); ?>" 
                   class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                    <?php echo htmlspecialchars($localeContent['Link']['text'] ?? 'Learn More'); ?>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
        
        <?php if (!$imageFirst && $imageUrl): ?>
            <div class="relative h-64 lg:h-80 flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-lg">
                <img src="<?php echo htmlspecialchars($imageUrl); ?>" 
                     alt="<?php echo htmlspecialchars($localeContent['Title'] ?? ''); ?>"
                     class="max-w-full max-h-full object-contain <?php echo strpos($imageUrl, '.svg') !== false ? 'svg-icon-red' : ''; ?>">
            </div>
        <?php endif; ?>
    </div>
</section>
