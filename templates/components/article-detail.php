<?php
/** @var \App\Model\Page $page */

use App\Model\Component\Image;
use App\Model\Component\Paragraph;
?>

<section class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            <?php echo htmlspecialchars($page->title); ?>
        </h1>
        
        <?php if (!empty($page->author)): ?>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                di <?php echo htmlspecialchars($page->author); ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="prose prose-lg dark:prose-invert max-w-none">
        <?php if (!empty($page->intro)): ?>
            <p class="text-xl text-gray-700 dark:text-gray-300 mb-8 font-medium leading-relaxed">
                <?php echo htmlspecialchars($page->intro); ?>
            </p>
        <?php endif; ?>

        <div class="space-y-8">
            <?php foreach ($page->components as $component): ?>
                <?php if ($component instanceof Paragraph): ?>
                    <!-- Paragraph Component -->
                    <?php if (!empty($component->caption)): ?>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 my-6 flex items-center gap-3">
                            <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full"></div>
                            <span class="italic"><?php echo htmlspecialchars($component->caption); ?></span>
                        </h4>
                    <?php endif; ?>
                    
                    <?php if (!empty($component->image)): ?>
                        <div class="my-10">
                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-2xl transform rotate-1 group-hover:rotate-2 transition-transform duration-300"></div>
                                <img 
                                    src="<?php echo htmlspecialchars($component->image); ?>" 
                                    alt="<?php echo htmlspecialchars($component->title ?? ''); ?>" 
                                    class="relative rounded-2xl shadow-2xl w-full transform group-hover:scale-[1.02] transition-transform duration-300"
                                >
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($component->text)): ?>
                        <div class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            <?php 
                            // Check if text contains YouTube URL
                            $text = $component->text;
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $text, $matches)) {
                                $videoId = $matches[1];
                                echo '<div class="relative w-full my-8" style="padding-bottom: 56.25%;">';
                                echo '<iframe class="absolute top-0 left-0 w-full h-full rounded-lg shadow-lg" ';
                                echo 'src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '?rel=0" ';
                                echo 'frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                echo '</div>';
                            } else {
                                echo $text; // Already HTML formatted
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                    
                <?php elseif ($component instanceof Image): ?>
                    <!-- Image Component -->
                    <figure class="my-10">
                        <div class="relative group overflow-hidden rounded-2xl shadow-2xl">
                            <img 
                                src="<?php echo htmlspecialchars($component->image); ?>" 
                                alt="<?php echo htmlspecialchars($component->title); ?>" 
                                class="w-full h-auto transform group-hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                        <?php if (!empty($component->title)): ?>
                            <figcaption class="text-center text-gray-600 dark:text-gray-400 mt-4 italic">
                                <?php echo htmlspecialchars($component->title); ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
