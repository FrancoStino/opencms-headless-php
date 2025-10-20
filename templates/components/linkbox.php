<?php
/** @var array $localeContent */
/** @var array $settings */
?>

<section class="max-w-4xl mx-auto">
    <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 text-center text-white">
        <?php if (!empty($localeContent['Title'])): ?>
            <h2 class="text-3xl font-bold mb-4">
                <?php echo htmlspecialchars($localeContent['Title']); ?>
            </h2>
        <?php endif; ?>
        
        <?php if (!empty($localeContent['Text'])): ?>
            <div class="prose prose-lg prose-invert max-w-none mb-6">
                <?php echo $localeContent['Text']; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($localeContent['Link']['link'])): ?>
            <a href="<?php echo htmlspecialchars($localeContent['Link']['link']); ?>" 
               class="inline-block bg-white text-blue-600 font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <?php echo htmlspecialchars($localeContent['Link']['text'] ?? 'Learn More'); ?>
            </a>
        <?php endif; ?>
    </div>
</section>
