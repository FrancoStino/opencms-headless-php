<?php

namespace App\Components;

class Contact
{
    public static function render($content, $locale, $mode = 'preview')
    {
        $localeContent = $content['localeContent'] ?? $content;

        $title = ($localeContent['Kind'] ?? '') === 'person'
            ? ($localeContent['Name']['FirstName'] ?? '') . ' ' . ($localeContent['Name']['LastName'] ?? '')
            : ($localeContent['Organization'] ?? '');

        if ($mode === 'preview') {
            $path = $content['path'];

            echo '<article onclick="window.location.href = "?path=' . ''. $path .'' . '&locale=' . ''. $locale .'"" class="glass-card overflow-hidden cursor-pointer">';
            echo '<div class="relative w-full h-48 overflow-hidden bg-gray-100 dark:bg-gray-800">';
            Image::render($localeContent['Image'] ?? null, $title, 'w-full h-full object-cover');
            echo '</div>';
            echo '<div class="p-4">';
            echo '<h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">' . htmlspecialchars($title) . '</h3>';
            if (!empty($localeContent['Position'])) {
                echo '<p class="text-sm text-gray-600 dark:text-gray-400 mb-1">' . htmlspecialchars($localeContent['Position']) . '</p>';
            }
            if (!empty($localeContent['Organization']) && ($localeContent['Kind'] ?? '') === 'person') {
                echo '<p class="text-xs text-gray-500 dark:text-gray-500">' . htmlspecialchars($localeContent['Organization']) . '</p>';
            }
            echo '</div>';
            echo '</article>';
        } else { // detail view
            $contactDetails = $localeContent['Contact'] ?? [];
            $address = $contactDetails['AddressChoice']['Address'] ?? null;

            echo '<section class="max-w-3xl mx-auto glass-card">';
            echo '<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">';
            echo '<div class="md:col-span-1">';
            Image::render($localeContent['Image'] ?? null, $title, 'w-full rounded-lg');
            echo '</div>';
            echo '<div class="md:col-span-2">';
            echo '<h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">' . htmlspecialchars($title) . '</h1>';
            if (!empty($localeContent['Position'])) {
                echo '<p class="text-base text-gray-600 dark:text-gray-400 mb-1">' . htmlspecialchars($localeContent['Position']) . '</p>';
            }
            if (!empty($localeContent['Organization']) && ($localeContent['Kind'] ?? '') === 'person') {
                echo '<p class="text-sm text-gray-500 dark:text-gray-500">' . htmlspecialchars($localeContent['Organization']) . '</p>';
            }
            echo '</div>';
            echo '</div>';

            echo '<div class="space-y-4 text-sm">';
            if ($address) {
                echo '<div><h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">Indirizzo</h3>';
                echo '<p class="text-gray-600 dark:text-gray-400">' . htmlspecialchars($address['StreetAddress']) . '</p>';
                echo '<p class="text-gray-600 dark:text-gray-400">' . htmlspecialchars($address['PostalCode']) . ' ' . htmlspecialchars($address['Locality']) . '</p>';
                echo '</div>';
            }
            if (!empty($contactDetails['Email']['Email'])) {
                echo '<div><h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">Email</h3>';
                echo '<a href="mailto:' . htmlspecialchars($contactDetails['Email']['Email']) . '" class="text-primary-600 dark:text-primary-400 hover:underline">' . htmlspecialchars($contactDetails['Email']['Email']) . '</a>';
                echo '</div>';
            }
            if (!empty($contactDetails['Phone'])) {
                echo '<div><h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">Telefono</h3>';
                echo '<p class="text-gray-600 dark:text-gray-400">' . htmlspecialchars($contactDetails['Phone']) . '</p>';
                echo '</div>';
            }
            if (!empty($contactDetails['Mobile'])) {
                echo '<div><h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">Cellulare</h3>';
                echo '<p class="text-gray-600 dark:text-gray-400">' . htmlspecialchars($contactDetails['Mobile']) . '</p>';
                echo '</div>';
            }
            echo '</div>';
            echo '</section>';
        }
    }
}
