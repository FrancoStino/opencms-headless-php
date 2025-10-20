<?php

namespace App\Api;

use App\Model\Page;

class ApiClient
{
    private $apiEndpoint;
    private $baseUrl;

    public function __construct()
    {
        // Get base URL from environment or use default
        $this->baseUrl = getenv('OPENCMS_SERVER') ?: 'http://localhost';
        $this->apiEndpoint = $this->baseUrl . '/json/sites/mercury.local';
    }

    public function loadContentList($type, $locale): array
    {
        $url = $this->apiEndpoint . '/.content/' . $type . '?content&wrapper&locale=' . $locale . '&fallbackLocale';
        $data = $this->fetchData($url);
        $pages = [];
        
        if ($data && is_array($data)) {
            foreach ($data as $key => $entry) {
                // Skip non-content entries
                if (!is_array($entry) || !isset($entry['path'])) {
                    continue;
                }
                
                $localeContent = $entry[$locale] ?? $entry['localeContent'] ?? [];
                
                // Transform the data structure to match what Page expects
                $pageData = [
                    'title' => $localeContent['Title'] ?? $entry['properties']['Title'] ?? 'Untitled',
                    'path' => $entry['path'] ?? '',
                    'intro' => $localeContent['Intro'] ?? null,
                    'author' => $localeContent['Author'] ?? null,
                    'image' => null,
                    'content' => [
                        'main' => [
                            'components' => []
                        ]
                    ]
                ];
                
                // Extract first image from first paragraph for preview
                if (isset($localeContent['Paragraph']) && is_array($localeContent['Paragraph'])) {
                    foreach ($localeContent['Paragraph'] as $paragraph) {
                        if (isset($paragraph['Image']['link'])) {
                            $pageData['image'] = $paragraph['Image']['link'];
                            break;
                        }
                    }
                    
                    // Add paragraphs as components
                    foreach ($localeContent['Paragraph'] as $paragraph) {
                        $pageData['content']['main']['components'][] = [
                            'type' => 'paragraph',
                            'text' => $paragraph['Text'] ?? '',
                            'caption' => $paragraph['Caption'] ?? '',
                            'title' => $paragraph['Title'] ?? '',
                            'image' => $paragraph['Image']['link'] ?? null
                        ];
                    }
                }
                
                $pages[] = new Page($pageData);
            }
        }
        
        return $pages;
    }

    public function loadContentDetail($path, $locale): ?Page
    {
        $url = $this->baseUrl . '/json' . $path . '?content&wrapper&locale=' . $locale . '&fallbackLocale';
        $data = $this->fetchData($url);
        
        if ($data && is_array($data)) {
            $localeContent = $data[$locale] ?? $data['localeContent'] ?? [];
            
            // Transform the data structure
            $pageData = [
                'title' => $localeContent['Title'] ?? $data['properties']['Title'] ?? 'Untitled',
                'path' => $data['path'] ?? $path,
                'intro' => $localeContent['Intro'] ?? null,
                'author' => $localeContent['Author'] ?? null,
                'image' => null,
                'content' => [
                    'main' => [
                        'components' => []
                    ]
                ]
            ];
            
            // Add paragraphs as components
            if (isset($localeContent['Paragraph']) && is_array($localeContent['Paragraph'])) {
                foreach ($localeContent['Paragraph'] as $paragraph) {
                    $pageData['content']['main']['components'][] = [
                        'type' => 'paragraph',
                        'text' => $paragraph['Text'] ?? '',
                        'caption' => $paragraph['Caption'] ?? '',
                        'title' => $paragraph['Title'] ?? '',
                        'image' => $paragraph['Image']['link'] ?? null
                    ];
                }
            }
            
            // Add YouTube video if present
            if (isset($localeContent['MediaContent']['YouTube']['YouTubeId'])) {
                $youtubeId = $localeContent['MediaContent']['YouTube']['YouTubeId'];
                $youtubeUrl = 'https://www.youtube.com/watch?v=' . $youtubeId;
                $pageData['content']['main']['components'][] = [
                    'type' => 'paragraph',
                    'text' => $youtubeUrl, // The article-detail.php will detect this and convert to iframe
                    'caption' => '',
                    'title' => '',
                    'image' => null
                ];
            }
            
            return new Page($pageData);
        }
        
        return null;
    }

    private function fetchData($url)
    {
        $json = @file_get_contents($url);
        if ($json === false) {
            return null;
        }
        return json_decode($json, true);
    }

    public function loadContainerPage($pagePath, $locale): ?array
    {
        // Try direct path first
        $url = $this->baseUrl . '/json' . $pagePath . '?locale=' . $locale . '&fallbackLocale';
        $data = $this->fetchData($url);
        
        if ($data && is_array($data)) {
            // Check if it's a folder listing
            if ($this->isFolderListing($data)) {
                // Look for index.html in the folder
                if (isset($data['index.html'])) {
                    // Try to load index.html
                    $indexPath = rtrim($pagePath, '/') . '/index.html';
                    $url = $this->baseUrl . '/json' . $indexPath . '?locale=' . $locale . '&fallbackLocale';
                    $indexData = $this->fetchData($url);
                    if ($indexData && is_array($indexData)) {
                        return $indexData;
                    }
                }
            }
            return $data;
        }
        
        return null;
    }
    
    private function isFolderListing($data): bool
    {
        // A folder listing has entries with 'attributes', 'isFolder', etc.
        // A container page has 'containers' array
        if (isset($data['containers'])) {
            return false; // It's a container page
        }
        
        // Check if it looks like a folder listing
        foreach ($data as $key => $value) {
            if (is_array($value) && isset($value['attributes']) && isset($value['isFolder'])) {
                return true;
            }
        }
        
        return false;
    }

    public function checkApiAvailability()
    {
        $response = @get_headers($this->apiEndpoint);
        return $response && strpos($response[0], '200') !== false;
    }
}
