<?php

namespace Views;

class Renderer
{
    private $templatesDirectory;

    public function __construct($templatesDirectory)
    {
        $this->templatesDirectory = $templatesDirectory;
    }

    public function render($template, $data)
    {
        var_dump($data);
    }
}