<?php

namespace App;

use App\Controllers\ViewController;

class Router
{
    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);
        
        $type = $_GET['type'] ?? 'article-m';
        $contentPath = $_GET['path'] ?? null;
        $locale = $_GET['locale'] ?? 'en';

        $controller = new ViewController();

        // Route per pagine specifiche
        if ($path === '/contatti' || $path === '/contatti/') {
            // Pagina contatti
            $controller->showPage('/sites/mercury.local/contatti/', $locale);
        } elseif ($path === '/' || $path === '/index.php') {
            // Homepage
            if ($contentPath) {
                // Detail view di un contenuto
                $controller->showDetail($contentPath, $locale);
            } else {
                // Homepage
                $controller->showPage('/sites/mercury.local/index.html', $locale);
            }
        } else {
            // Lista contenuti per tipo
            if ($contentPath) {
                $controller->showDetail($contentPath, $locale);
            } else {
                $controller->showList($type, $locale);
            }
        }
    }
}
