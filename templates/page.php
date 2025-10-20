<?php
/** @var array $containers */
/** @var string $pageTitle */
/** @var string $locale */

// Helper function to fetch element content
function fetchElementContent($path, $locale, $baseUrl) {
    $url = $baseUrl . '/json' . $path . '?locale=' . $locale . '&fallbackLocale';
    $json = @file_get_contents($url);
    if ($json === false) {
        return null;
    }
    return json_decode($json, true);
}

// Recursive function to render nested containers
function renderNestedContainers($containers, $locale, $baseUrl) {
    foreach ($containers as $container) {
        if (!empty($container['elements'])) {
            foreach ($container['elements'] as $element) {
                $formatterKey = $element['formatterKey'] ?? '';
                $elementPath = $element['path'] ?? '';
                $settings = $element['settings'] ?? [];
                
                // Skip layout formatters (they're just wrappers)
                if (strpos($formatterKey, 'm/layout/') === 0) {
                    // Render nested containers inside layout
                    if (!empty($element['containers'])) {
                        renderNestedContainers($element['containers'], $locale, $baseUrl);
                    }
                    continue;
                }
                
                // Fetch element content
                $elementContent = fetchElementContent($elementPath, $locale, $baseUrl);
                
                // Check if element content is valid
                if (!$elementContent || !is_array($elementContent)) {
                    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">';
                    echo '❌ <strong>Failed to fetch:</strong> ' . htmlspecialchars($elementPath);
                    echo '</div>';
                    continue;
                }
                
                // Try to get content in requested locale, fallback to any available locale
                $localeContent = null;
                
                // Check if content has locale structure (en, it, de, etc.)
                $hasLocaleStructure = false;
                foreach (['en', 'it', 'de', 'fr', 'es'] as $checkLocale) {
                    if (isset($elementContent[$checkLocale]) && is_array($elementContent[$checkLocale])) {
                        $hasLocaleStructure = true;
                        break;
                    }
                }
                
                if ($hasLocaleStructure) {
                    // Content has locale structure
                    if (isset($elementContent[$locale])) {
                        $localeContent = $elementContent[$locale];
                    } else {
                        // Try common locales
                        foreach (['en', 'it', 'de', 'fr', 'es'] as $fallbackLocale) {
                            if (isset($elementContent[$fallbackLocale]) && !empty($elementContent[$fallbackLocale])) {
                                $localeContent = $elementContent[$fallbackLocale];
                                break;
                            }
                        }
                    }
                } else {
                    // Content is directly structured (no locale wrapper)
                    // Use the content as-is
                    $localeContent = $elementContent;
                }
                
                // Skip if no content
                if (empty($localeContent)) {
                    echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">';
                    echo '⚠️ <strong>No content</strong> - ' . htmlspecialchars($formatterKey);
                    echo '</div>';
                    continue;
                }
                
                // Special handling for tab-standard
                if ($formatterKey === 'm/element/tab-standard') {
                    // Pass entire element to tab component
                    require __DIR__ . '/components/tab-standard.php';
                } else {
                    // Render based on formatter
                    renderElement($formatterKey, $localeContent, $settings, $elementPath, $locale);
                    
                    // Handle nested containers (for non-tab elements)
                    if (!empty($element['containers'])) {
                        echo '<div class="space-y-8">';
                        renderNestedContainers($element['containers'], $locale, $baseUrl);
                        echo '</div>';
                    }
                }
            }
        }
    }
}

// Function to render a single element
function renderElement($formatterKey, $localeContent, $settings, $elementPath, $locale) {
    if ($formatterKey === 'm/section/text-only') {
        require __DIR__ . '/components/text-only.php';
    } elseif ($formatterKey === 'm/section/text-image') {
        require __DIR__ . '/components/text-image.php';
    } elseif ($formatterKey === 'm/webform/webform') {
        require __DIR__ . '/components/webform.php';
    } elseif ($formatterKey === 'm/element/slider-hero') {
        require __DIR__ . '/components/slider-hero.php';
    } elseif ($formatterKey === 'm/display/article-elaborate' || $formatterKey === 'm/detail/media') {
        require __DIR__ . '/components/article-teaser.php';
    } elseif ($formatterKey === 'm/section/linkbox') {
        // Linkbox component
        require __DIR__ . '/components/linkbox.php';
    } elseif (strpos($formatterKey, 'video') !== false || isset($localeContent['VideoUrl']) || isset($localeContent['Video'])) {
        // Video component (any formatter with video in name or VideoUrl field)
        require __DIR__ . '/components/video.php';
    } elseif ($formatterKey === 'm/element/map-google') {
        // Map component
        require __DIR__ . '/components/map-google.php';
    } else {
        // Unknown formatter - log it
        error_log("Unknown formatter: $formatterKey for path: $elementPath");
    }
}

$baseUrl = getenv('OPENCMS_SERVER') ?: 'http://localhost';

// Debug
error_log("Template page.php - Containers: " . print_r($containers ?? 'NOT SET', true));

// Show debug only if containers are empty
$showDebug = empty($containers);
?>

<?php if ($showDebug): ?>
<div class="bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-400 dark:border-yellow-600 rounded-lg p-6 mb-8">
    <h2 class="text-xl font-bold text-yellow-800 dark:text-yellow-200 mb-4">🔍 Debug Info</h2>
    <div class="space-y-2 text-sm font-mono">
        <p><strong>Containers:</strong> <?php echo isset($containers) ? 'SET' : 'NOT SET'; ?></p>
        <p><strong>Containers Count:</strong> <?php echo count($containers ?? []); ?></p>
        <p><strong>Page Title:</strong> <?php echo htmlspecialchars($pageTitle ?? 'NOT SET'); ?></p>
        <p><strong>Locale:</strong> <?php echo htmlspecialchars($locale ?? 'NOT SET'); ?></p>
        <p><strong>Base URL:</strong> <?php echo htmlspecialchars($baseUrl); ?></p>
        <p><strong>View:</strong> <?php echo htmlspecialchars($view ?? 'NOT SET'); ?></p>
        <?php if (isset($containers) && !empty($containers)): ?>
            <details class="mt-4">
                <summary class="cursor-pointer text-yellow-700 dark:text-yellow-300 font-bold">View Containers Data</summary>
                <pre class="mt-2 bg-gray-900 text-green-400 p-4 rounded overflow-auto max-h-96"><?php echo htmlspecialchars(print_r($containers, true)); ?></pre>
            </details>
        <?php else: ?>
            <p class="text-red-600 font-bold mt-4">⚠️ NO CONTAINERS!</p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="space-y-12">
    <?php if (!empty($containers)): ?>
        <?php renderNestedContainers($containers, $locale, $baseUrl); ?>
    <?php else: ?>
        <div class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">No content available.</p>
        </div>
    <?php endif; ?>
</div>
