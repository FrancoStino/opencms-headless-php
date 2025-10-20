<?php

namespace App\Components;

class Article
{
    public static function render($content, $locale, $mode = 'preview')
    {
        $localeContent = $content['localeContent'] ?? $content;

        if ($mode === 'preview') {
            $image = $localeContent['Paragraph'][0]['Image'] ?? null;
            $imageAlt = $localeContent['Title'] ?? '';
            $path = $content['path'];

            echo '<article onclick="window.location.href = "?path=' . ''. $path .'' . '&locale=' . ''. $locale .'"" class="glass-card overflow-hidden cursor-pointer">';
            echo '<div class="relative w-full h-48 overflow-hidden bg-gray-100 dark:bg-gray-800">';
            Image::render($image, $imageAlt, 'w-full h-full object-cover');
            echo '</div>';
            echo '<div class="p-4">';
            echo '<h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">' . htmlspecialchars($localeContent['Title'] ?? '') . '</h3>';
            if (!empty($localeContent['Intro'])) {
                echo '<p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">' . htmlspecialchars($localeContent['Intro']) . '</p>';
            }
            if (!empty($localeContent['Author'])) {
                echo '<p class="text-xs text-gray-500 dark:text-gray-500">' . htmlspecialchars($localeContent['Author']) . '</p>';
            }
            echo '</div>';
            echo '</article>';
        } else { // detail view
            echo '<section class="max-w-3xl mx-auto glass-card">';
            echo '<div class="mb-6">';
            echo '<h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">' . htmlspecialchars($localeContent['Title'] ?? '') . '</h1>';
            if (!empty($localeContent['Author'])) {
                echo '<p class="text-sm text-gray-600 dark:text-gray-400">di ' . htmlspecialchars($localeContent['Author']) . '</p>';
            }
            echo '</div>';
            echo '<div class="prose prose-sm dark:prose-invert max-w-none">';
            if (!empty($localeContent['Intro'])) {
                echo '<p class="text-base text-gray-700 dark:text-gray-300 mb-6">' . htmlspecialchars($localeContent['Intro']) . '</p>';
            }
            echo '<div class="space-y-6">';
            if (!empty($localeContent['Paragraph'])) {
                foreach ($localeContent['Paragraph'] as $paragraph) {
                    Paragraph::render($paragraph);
                }
            }
            echo '</div>';
            echo '</div>';
            echo '</section>';
        }
    }
}
