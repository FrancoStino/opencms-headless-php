<?php

namespace App\Model\Component;

class Image extends BaseComponent
{
    public string $image;
    public string $title;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->image = $data['value']['image']['uri'];
        $this->title = $data['value']['title'];
    }
}
