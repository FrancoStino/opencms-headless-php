<?php

namespace App\Controllers;

use App\Api\ApiClient;

class ViewController
{
    private $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    public function showList($type, $locale)
    {
        $pages = $this->apiClient->loadContentList($type, $locale);
        
        // Check if API is available
        if (empty($pages) && !$this->apiClient->checkApiAvailability()) {
            $view = 'exception.php';
        } else {
            $view = 'list.php';
        }
        
        // Make variables available to templates
        $type = $type;
        $locale = $locale;
        
        require __DIR__ . '/../../templates/layout.php';
    }

    public function showDetail($path, $locale)
    {
        $page = $this->apiClient->loadContentDetail($path, $locale);
        $type = $_GET['type'] ?? 'article-m';
        
        if (!$page) {
            $view = 'exception.php';
        } else {
            $view = 'detail.php';
        }
        
        // Make variables available to templates
        $locale = $locale;
        
        require __DIR__ . '/../../templates/layout.php';
    }
    
    public function showPage($pagePath, $locale)
    {
        $pageData = $this->apiClient->loadContainerPage($pagePath, $locale);
        
        // Debug
        error_log("Page Path: $pagePath");
        error_log("Page Data: " . print_r($pageData, true));
        
        if (!$pageData) {
            $view = 'exception.php';
            $containers = [];
            $pageTitle = 'Error';
        } else {
            $view = 'page.php';
            $containers = $pageData['containers'] ?? [];
            $pageTitle = $pageData['properties']['Title'] ?? 'Page';
        }
        
        // Make variables available to templates
        $locale = $locale;
        
        error_log("Containers count: " . count($containers));
        
        require __DIR__ . '/../../templates/layout.php';
    }
}
