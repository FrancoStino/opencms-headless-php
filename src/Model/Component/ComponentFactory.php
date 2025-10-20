<?php

namespace App\Model\Component;

class ComponentFactory
{
    public static function create(array $data): ?BaseComponent
    {
        $type = $data['type'] ?? '';
        
        switch ($type) {
            case 'paragraph':
            case 'mercury/components/paragraph':
                return new Paragraph($data);
            case 'image':
            case 'mercury/components/image':
                return new Image($data);
            default:
                return null;
        }
    }
}
