<?php

namespace App\Model\Component;

class BaseComponent
{
    public string $id;
    public string $type;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? uniqid('component_');
        $this->type = $data['type'] ?? 'unknown';
    }
}
