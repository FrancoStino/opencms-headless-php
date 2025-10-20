<?php
/** @var array $localeContent */
/** @var string $elementPath */
/** @var string $locale */

$baseUrl = getenv('OPENCMS_SERVER') ?: 'http://172.30.0.5:8080';

// Fix image URL
$imageUrl = null;
if (!empty($localeContent['Paragraph'][0]['Image']['link'])) {
    $imageUrl = $localeContent['Paragraph'][0]['Image']['link'];
    // Replace mercury.local with correct server
    $imageUrl = str_replace('http://mercury.local', $baseUrl, $imageUrl);
    $imageUrl = str_replace('https://mercury.local', $baseUrl, $imageUrl);
    // Fix escaped slashes
    $imageUrl = str_replace('\\/', '/', $imageUrl);
    // Handle relative URLs
    if (strpos($imageUrl, 'http') !== 0) {
        $imageUrl = $baseUrl . $imageUrl;
    }
    // Force HTTP
    $imageUrl = str_replace('https://', 'http://', $imageUrl);
    // Use image proxy
    $imageUrl = '/image-proxy.php?url=' . urlencode($imageUrl);
}
?>

<section class="max-w-6xl mx-auto">
    <a href="?path=<?php echo urlencode($elementPath); ?>&locale=<?php echo htmlspecialchars($locale); ?>" 
       class="block bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php if ($imageUrl): ?>
                <div class="relative h-64 lg:h-full">
                    <img src="<?php echo htmlspecialchars($imageUrl); ?>" 
                         alt="<?php echo htmlspecialchars($localeContent['Title'] ?? ''); ?>"
                         class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            
            <div class="p-6 flex flex-col justify-center">
                <?php if (!empty($localeContent['Title'])): ?>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">
                        <?php echo htmlspecialchars($localeContent['Title']); ?>
                    </h3>
                <?php endif; ?>
                
                <?php if (!empty($localeContent['Intro'])): ?>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        <?php echo htmlspecialchars($localeContent['Intro']); ?>
                    </p>
                <?php endif; ?>
                
                <span class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                    Read more
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </div>
        </div>
    </a>
</section>
