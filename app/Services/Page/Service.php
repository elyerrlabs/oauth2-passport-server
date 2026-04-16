<?php

namespace App\Services\Page;


class Service
{

    protected $realPath;

    public function __construct()
    {
        $this->realPath = base_path('resources/views/pages');
    }

    public function getRealPath()
    {
        return $this->realPath;
    }
}
