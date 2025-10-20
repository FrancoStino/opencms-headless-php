<?php
/** @var \App\Model\Page[] $pages */
/** @var string $type */
/** @var string $locale */

$availableTypes = [
    'article-m' => 'Articles',
    'contact-m' => 'Contacts',
    'faq-m' => 'FAQs'
];

$availableLocales = [
    'en' => 'English',
    'it' => 'Italiano'
];

$currentType = $type ?? 'article-m';
$currentLocale = $locale ?? 'en';
?>

<div class="space-y-6">
    <!-- Controls Section -->
    <section class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Content Type Select -->
            <div>
                <label for="content-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Content Type
                </label>
                <select 
                    id="content-type" 
                    onchange="window.location.href='?type=' + this.value + '&locale=<?php echo htmlspecialchars($currentLocale); ?>'"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <?php foreach ($availableTypes as $typeKey => $typeName): ?>
                        <option value="<?php echo htmlspecialchars($typeKey); ?>" <?php echo $currentType === $typeKey ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($typeName); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Locale Select -->
            <div>
                <label for="locale" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Language
                </label>
                <select 
                    id="locale" 
                    onchange="window.location.href='?type=<?php echo htmlspecialchars($currentType); ?>&locale=' + this.value"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <?php foreach ($availableLocales as $localeKey => $localeName): ?>
                        <option value="<?php echo htmlspecialchars($localeKey); ?>" <?php echo $currentLocale === $localeKey ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($localeName); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Contenuti
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($pages)): ?>
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No content found.</p>
                </div>
            <?php else: ?>
                <?php foreach ($pages as $page): ?>
                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1">
                        <a href="?path=<?php echo urlencode($page->path); ?>&type=<?php echo htmlspecialchars($currentType); ?>&locale=<?php echo htmlspecialchars($currentLocale); ?>" class="block">
                            <!-- Image placeholder if available -->
                            <?php if (!empty($page->image)): ?>
                                <div class="relative w-full h-48 overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img 
                                        src="<?php echo htmlspecialchars($page->image); ?>" 
                                        alt="<?php echo htmlspecialchars($page->title); ?>"
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                                    <?php echo htmlspecialchars($page->title); ?>
                                </h3>
                                
                                <?php if (!empty($page->intro)): ?>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mb-4">
                                        <?php echo htmlspecialchars($page->intro); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <span class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium text-sm">
                                    Read more
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>