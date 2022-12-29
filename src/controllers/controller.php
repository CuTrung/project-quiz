<?php class Controller
{
    function render($path)
    {
        return $this->path = str_replace('controllers', $path, dirname(__FILE__));
    }
}
