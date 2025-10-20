<?php

namespace App\Components;

class Paragraph
{
    public static function render($paragraph)
    {
        if (empty($paragraph)) {
            return;
        }

        $caption = $paragraph['Caption'] ?? null;
        $imageContent = $paragraph['Image'] ?? null;
        $text = $paragraph['Text'] ?? null;
        $title = $paragraph['Title'] ?? null;

        if ($caption) {
            echo '<h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 my-6 flex items-center gap-3"><div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-secondary-500 rounded-full"></div><span class="italic">' . htmlspecialchars($caption) . '</span></h4>';
        }

        if ($imageContent) {
            echo '<div class="my-10"><div class="relative group"><div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-secondary-500/10 rounded-2xl transform rotate-1 group-hover:rotate-2 transition-transform duration-300"></div>';
            Image::render($imageContent, $title, 'relative rounded-2xl shadow-2xl w-full transform group-hover:scale-[1.02] transition-transform duration-300');
            echo '</div></div>';
        }

        if ($text) {
            echo '<div class="prose-content text-gray-700 dark:text-gray-300 leading-relaxed">' . $text . '</div>';
        }
    }
}
