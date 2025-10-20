<?php

namespace App\Components;

class Image
{
    public static function render($image, $alt, $className)
    {
        if (empty($image) || empty($image['link'])) {
            return;
        }

        $imageUrl = getenv('NEXT_PUBLIC_OPENCMS_SERVER_IMAGE') . $image['link'];

        echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($alt) . '" class="' . htmlspecialchars($className) . '">';
    }
}
