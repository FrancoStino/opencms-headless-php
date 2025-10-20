<?php

namespace App\Model;

use App\Model\Component\ComponentFactory;

class Page
{
    public string $title;
    public string $path;
    public ?string $intro = null;
    public ?string $author = null;
    public ?string $image = null;
    public array $components = [];

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? 'Untitled';
        $this->path = $data['path'] ?? '';
        $this->intro = $data['intro'] ?? null;
        $this->author = $data['author'] ?? null;
        $this->image = $data['image'] ?? null;
        
        if (isset($data['content']['main']['components'])) {
            foreach ($data['content']['main']['components'] as $componentData) {
                $component = ComponentFactory::create($componentData);
                if ($component) {
                    $this->components[] = $component;
                }
            }
        }
    }
}
