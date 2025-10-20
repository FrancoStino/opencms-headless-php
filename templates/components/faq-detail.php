<?php
/** @var \App\Model\Page $page */

use App\Model\Component\Paragraph;
?>

<section class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
    <div class="mb-8">
        <!-- FAQ Icon -->
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide font-semibold">
                    Frequently Asked Question
                </p>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            <?php echo htmlspecialchars($page->title); ?>
        </h1>
    </div>

    <div class="prose prose-lg dark:prose-invert max-w-none">
        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-6 rounded-r-lg mb-8">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Answer:</h3>
            <div class="space-y-6">
                <?php foreach ($page->components as $component): ?>
                    <?php if ($component instanceof Paragraph): ?>
                        <?php if (!empty($component->caption)): ?>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mt-6 mb-3">
                                <?php echo htmlspecialchars($component->caption); ?>
                            </h4>
                        <?php endif; ?>
                        
                        <?php if (!empty($component->text)): ?>
                            <div class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                <?php echo $component->text; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
