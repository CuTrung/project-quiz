<?php class Controller
{
    function render($path)
    {
        return str_replace('controllers', $path, dirname(__FILE__));
    }
}
