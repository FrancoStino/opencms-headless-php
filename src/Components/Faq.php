<?php

namespace App\Components;

class Faq
{
    public static function render($content, $locale, $mode = 'preview')
    {
        $localeContent = $content['localeContent'] ?? $content;

        if ($mode === 'preview') {
            $path = $content['path'];
            $answerPreview = '';
            if (!empty($localeContent['Paragraph'][0]['Text'])) {
                $answerPreview = substr($localeContent['Paragraph'][0]['Text'], 0, 150) . '...';
            }

            echo '<article onclick="window.location.href = "?path=' . ''. $path .'' . '&locale=' . ''. $locale .'"" class="glass-card cursor-pointer p-4">';
            echo '<h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">' . htmlspecialchars($localeContent['Question'] ?? '') . '</h3>';
            if ($answerPreview) {
                echo '<div class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">' . $answerPreview . '</div>';
            }
            echo '</article>';
        } else { // detail view
            echo '<section class="max-w-3xl mx-auto glass-card">';
            echo '<div class="mb-6">';
            echo '<h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">' . htmlspecialchars($localeContent['Question'] ?? '') . '</h1>';
            echo '</div>';
            echo '<div class="prose prose-sm dark:prose-invert max-w-none">';
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
