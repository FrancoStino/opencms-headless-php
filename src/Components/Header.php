<?php

namespace App\Components;

class Header
{
    public static function render($result)
    {
        $title = $result['wrapper']['title'] ?? 'Demo 1';
        echo '<div class="py-4 border-b border-gray-200"><h1 class="text-2xl font-bold">' . htmlspecialchars($title) . '</h1></div>';
    }
}
