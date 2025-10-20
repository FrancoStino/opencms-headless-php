<?php
/** @var \App\Model\Page $page */
/** @var string $type */
/** @var string $locale */

$currentType = $type ?? 'article-m';
$currentLocale = $locale ?? 'en';

?>

<div class="space-y-6">
    <!-- Back Navigation -->
    <a 
        href="?type=<?php echo htmlspecialchars($currentType); ?>&locale=<?php echo htmlspecialchars($currentLocale); ?>"
        class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span>Torna alla lista</span>
    </a>

    <!-- Content - Type-specific rendering -->
    <?php
    // Determine which component template to use based on content type
    $componentTemplate = match($currentType) {
        'faq-m' => __DIR__ . '/components/faq-detail.php',
        'contact-m' => __DIR__ . '/components/contact-detail.php',
        default => __DIR__ . '/components/article-detail.php',
    };
    
    if (file_exists($componentTemplate)) {
        require $componentTemplate;
    } else {
        // Fallback to inline rendering
        require __DIR__ . '/components/article-detail.php';
    }
    ?>
</div>