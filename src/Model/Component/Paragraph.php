<?php

namespace App\Model\Component;

class Paragraph extends BaseComponent
{
    public string $text;
    public string $caption;
    public ?string $title = null;
    public ?string $image = null;

    public function __construct(array $data)
    {
        parent::__construct($data);
        // Support both old and new data structures
        $this->text = $data['text'] ?? $data['value']['text'] ?? '';
        $this->caption = $data['caption'] ?? $data['value']['caption'] ?? '';
        $this->title = $data['title'] ?? $data['value']['title'] ?? null;
        $this->image = $data['image'] ?? $data['value']['image'] ?? null;
    }
}
