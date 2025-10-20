<?php
/** @var array $localeContent */
/** @var array $settings */
?>

<section class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
    <?php if (!empty($localeContent['Title'])): ?>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
            <?php echo htmlspecialchars($localeContent['Title']); ?>
        </h2>
    <?php endif; ?>
    
    <?php if (!empty($localeContent['Text'])): ?>
        <div class="prose prose-lg dark:prose-invert max-w-none text-center">
            <?php echo $localeContent['Text']; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($localeContent['Link']['link'])): ?>
        <div class="mt-6 text-center">
            <a href="<?php echo htmlspecialchars($localeContent['Link']['link']); ?>" 
               class="inline-block bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105">
                <?php echo htmlspecialchars($localeContent['Link']['text'] ?? 'Learn More'); ?>
            </a>
        </div>
    <?php endif; ?>
</section>
