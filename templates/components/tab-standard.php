<?php
/** @var array $localeContent */
/** @var array $settings */
/** @var array $element - entire element with containers */

// Get tab containers
$tabContainers = $element['containers'] ?? [];
$tabs = [];

// Extract tab data
foreach ($tabContainers as $container) {
    if (!empty($container['elements'])) {
        $tabs[] = [
            'name' => $container['name'] ?? 'Tab',
            'elements' => $container['elements']
        ];
    }
}

if (empty($tabs)) {
    return;
}

$tabId = 'tab-' . md5($element['path'] ?? uniqid());
?>

<section class="max-w-6xl mx-auto mb-12">
    <!-- Tab Headers -->
    <div class="flex border-b border-gray-200 dark:border-gray-700 mb-8">
        <?php foreach ($tabs as $index => $tab): ?>
            <button onclick="switchTab('<?php echo $tabId; ?>', <?php echo $index; ?>)" 
                    class="tab-button px-6 py-3 font-medium transition-colors <?php echo $index === 0 ? 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100'; ?>"
                    data-tab-id="<?php echo $tabId; ?>"
                    data-tab-index="<?php echo $index; ?>">
                Tab <?php echo $index + 1; ?>
            </button>
        <?php endforeach; ?>
    </div>
    
    <!-- Tab Contents -->
    <div class="tab-contents">
        <?php foreach ($tabs as $index => $tab): ?>
            <div class="tab-content <?php echo $index === 0 ? '' : 'hidden'; ?>" 
                 data-tab-id="<?php echo $tabId; ?>"
                 data-tab-index="<?php echo $index; ?>">
                <?php
                // Render elements in this tab
                foreach ($tab['elements'] as $tabElement) {
                    $formatterKey = $tabElement['formatterKey'] ?? '';
                    $elementPath = $tabElement['path'] ?? '';
                    $tabSettings = $tabElement['settings'] ?? [];
                    
                    // Skip layout formatters
                    if (strpos($formatterKey, 'm/layout/') === 0) {
                        continue;
                    }
                    
                    // Fetch element content
                    $elementContent = fetchElementContent($elementPath, $locale, $baseUrl);
                    if (!$elementContent || !is_array($elementContent)) {
                        continue;
                    }
                    
                    // Get locale content
                    $hasLocaleStructure = false;
                    foreach (['en', 'it', 'de', 'fr', 'es'] as $checkLocale) {
                        if (isset($elementContent[$checkLocale]) && is_array($elementContent[$checkLocale])) {
                            $hasLocaleStructure = true;
                            break;
                        }
                    }
                    
                    if ($hasLocaleStructure) {
                        $tabLocaleContent = $elementContent[$locale] ?? $elementContent['en'] ?? [];
                    } else {
                        $tabLocaleContent = $elementContent;
                    }
                    
                    if (empty($tabLocaleContent)) {
                        continue;
                    }
                    
                    // Render element
                    renderElement($formatterKey, $tabLocaleContent, $tabSettings, $elementPath, $locale);
                }
                ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
function switchTab(tabId, index) {
    // Update buttons
    const buttons = document.querySelectorAll(`[data-tab-id="${tabId}"].tab-button`);
    buttons.forEach((btn, i) => {
        if (i === index) {
            btn.classList.add('border-b-2', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            btn.classList.remove('text-gray-600', 'dark:text-gray-400');
        } else {
            btn.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            btn.classList.add('text-gray-600', 'dark:text-gray-400');
        }
    });
    
    // Update content
    const contents = document.querySelectorAll(`[data-tab-id="${tabId}"].tab-content`);
    contents.forEach((content, i) => {
        if (i === index) {
            content.classList.remove('hidden');
        } else {
            content.classList.add('hidden');
        }
    });
}
</script>
