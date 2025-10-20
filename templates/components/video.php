<?php
/** @var array $localeContent */
/** @var array $settings */

// Get video URL
$videoUrl = null;
if (isset($localeContent['VideoUrl'])) {
    $videoUrl = $localeContent['VideoUrl'];
} elseif (isset($localeContent['Video']['link'])) {
    $videoUrl = $localeContent['Video']['link'];
} elseif (isset($localeContent['Uri']['link'])) {
    $videoUrl = $localeContent['Uri']['link'];
}

if (empty($videoUrl)) {
    return;
}

// Extract YouTube video ID
$videoId = null;
if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches)) {
    $videoId = $matches[1];
}

if (!$videoId) {
    // Not a YouTube video or invalid URL
    return;
}
?>

<section class="max-w-6xl mx-auto mb-12">
    <?php if (!empty($localeContent['Title'])): ?>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
            <?php echo htmlspecialchars($localeContent['Title']); ?>
        </h2>
    <?php endif; ?>
    
    <div class="relative w-full" style="padding-bottom: 56.25%;">
        <iframe 
            class="absolute top-0 left-0 w-full h-full rounded-lg shadow-lg"
            src="https://www.youtube.com/embed/<?php echo htmlspecialchars($videoId); ?>?rel=0" 
            title="<?php echo htmlspecialchars($localeContent['Title'] ?? 'Video'); ?>"
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
        </iframe>
    </div>
    
    <?php if (!empty($localeContent['Text'])): ?>
        <div class="mt-6 prose dark:prose-invert max-w-none">
            <?php echo $localeContent['Text']; ?>
        </div>
    <?php endif; ?>
</section>
